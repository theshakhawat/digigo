<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TicketReplyMail;
use App\Models\SupportTicket;
use App\Models\TicketMessage;
use App\Models\UserNotification;
use App\Services\MailConfigService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TicketController extends Controller
{
    /**
     * Display a listing of all support tickets with filtering and search.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $department = $request->query('department');
        $priority = $request->query('priority');
        $search = trim($request->query('search', ''));

        $query = SupportTicket::with(['user', 'order'])
            ->latest('last_reply_at')
            ->latest('id');

        if ($status && in_array($status, ['open', 'in_progress', 'answered', 'closed'], true)) {
            $query->where('status', $status);
        }

        if ($department && in_array($department, ['refund', 'return', 'technical', 'general'], true)) {
            $query->where('department', $department);
        }

        if ($priority && in_array($priority, ['low', 'medium', 'high', 'urgent'], true)) {
            $query->where('priority', $priority);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('order', function ($oq) use ($search) {
                        $oq->where('order_number', 'like', "%{$search}%");
                    });
            });
        }

        $tickets = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => SupportTicket::count(),
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'answered' => SupportTicket::where('status', 'answered')->count(),
            'closed' => SupportTicket::where('status', 'closed')->count(),
        ];

        return view('admin.tickets.index', compact('tickets', 'counts', 'status', 'department', 'priority', 'search'));
    }

    /**
     * Show ticket details, message history, and resolution panel.
     */
    public function show(SupportTicket $ticket): View
    {
        $ticket->load(['user', 'order.items', 'messages.user']);

        return view('admin.tickets.show', compact('ticket'));
    }

    /**
     * Admin reply to support ticket with optional multiple attachments and status update.
     */
    public function reply(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:10000'],
            'status' => ['nullable', 'in:open,in_progress,answered,closed'],
            'priority' => ['nullable', 'in:low,medium,high,urgent'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:jpeg,png,jpg,webp,pdf,zip,doc,docx', 'max:10240'],
        ]);

        $admin = auth()->user();

        DB::beginTransaction();
        try {
            // Upload admin attachments if provided
            $uploadedFiles = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $path = $file->store('tickets/attachments', 'public');
                    $uploadedFiles[] = [
                        'name' => $originalName,
                        'path' => $path,
                        'url' => Storage::disk('public')->url($path),
                        'size' => $file->getSize(),
                        'mime' => $file->getClientMimeType(),
                    ];
                }
            }

            // Create admin reply message
            $message = TicketMessage::create([
                'support_ticket_id' => $ticket->id,
                'user_id' => $admin->id,
                'is_admin' => true,
                'message' => $validated['message'],
                'attachments' => $uploadedFiles,
            ]);

            // Update ticket status & last reply
            $newStatus = $validated['status'] ?? 'answered';
            $ticketUpdates = [
                'status' => $newStatus,
                'last_reply_at' => now(),
            ];
            if (! empty($validated['priority'])) {
                $ticketUpdates['priority'] = $validated['priority'];
            }
            $ticket->update($ticketUpdates);

            DB::commit();

            // 1. Notify Customer via in-app UserNotification
            try {
                UserNotification::create([
                    'user_id' => $ticket->user_id,
                    'type' => 'ticket',
                    'title' => 'Reply on Ticket #'.$ticket->ticket_number,
                    'message' => "Support staff replied to your ticket: {$ticket->subject}",
                    'link' => route('user.tickets.show', $ticket->id),
                    'data' => [
                        'ticket_id' => $ticket->id,
                        'ticket_number' => $ticket->ticket_number,
                        'status' => $newStatus,
                    ],
                    'is_read' => false,
                ]);
            } catch (Exception $e) {
                Log::error("Failed to create UserNotification for ticket #{$ticket->ticket_number}: {$e->getMessage()}");
            }

            // 2. Send Email to the customer
            try {
                if ($ticket->user && $ticket->user->email) {
                    app(MailConfigService::class)->apply();
                    Mail::to($ticket->user->email)->send(new TicketReplyMail($ticket, $message));
                }
            } catch (Exception $e) {
                Log::error("Failed to send TicketReplyMail to {$ticket->user?->email}: {$e->getMessage()}");
            }

            return back()->with('success', 'Reply sent successfully and customer notified.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to send ticket reply: {$e->getMessage()}");

            return back()->withInput()->with('error', 'Failed to send reply. Please try again.');
        }
    }

    /**
     * Quick status update (e.g. resolve / close / mark in progress).
     */
    public function updateStatus(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:open,in_progress,answered,closed'],
            'priority' => ['nullable', 'in:low,medium,high,urgent'],
        ]);

        $updates = ['status' => $validated['status']];
        if (! empty($validated['priority'])) {
            $updates['priority'] = $validated['priority'];
        }

        $ticket->update($updates);

        if ($validated['status'] === 'closed') {
            try {
                UserNotification::create([
                    'user_id' => $ticket->user_id,
                    'type' => 'ticket',
                    'title' => 'Ticket Resolved #'.$ticket->ticket_number,
                    'message' => "Your ticket #{$ticket->ticket_number} ({$ticket->subject}) has been marked as resolved/closed.",
                    'link' => route('user.tickets.show', $ticket->id),
                    'data' => ['ticket_id' => $ticket->id],
                    'is_read' => false,
                ]);
            } catch (Exception) {
            }
        }

        return back()->with('success', 'Ticket status updated to '.ucfirst(str_replace('_', ' ', $validated['status'])).'.');
    }
}
