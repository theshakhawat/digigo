<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Order;
use App\Models\SupportTicket;
use App\Models\TicketMessage;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TicketController extends Controller
{
    /**
     * Display a listing of user support tickets.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $status = $request->query('status');

        $query = SupportTicket::with('order')
            ->where('user_id', $user->id)
            ->latest('last_reply_at')
            ->latest('id');

        if ($status && in_array($status, ['open', 'in_progress', 'answered', 'closed'], true)) {
            $query->where('status', $status);
        }

        $tickets = $query->paginate(10)->withQueryString();

        $counts = [
            'all' => SupportTicket::where('user_id', $user->id)->count(),
            'open' => SupportTicket::where('user_id', $user->id)->whereIn('status', ['open', 'in_progress'])->count(),
            'answered' => SupportTicket::where('user_id', $user->id)->where('status', 'answered')->count(),
            'closed' => SupportTicket::where('user_id', $user->id)->where('status', 'closed')->count(),
        ];

        return view('user.tickets.index', compact('tickets', 'counts', 'status'));
    }

    /**
     * Show form to create a new ticket.
     */
    public function create(): View
    {
        $user = auth()->user();
        $orders = Order::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhere('customer_email', $user->email);
        })->latest()->take(30)->get();

        return view('user.tickets.create', compact('orders'));
    }

    /**
     * Store newly created support ticket with multiple attachments.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'department' => ['required', 'in:refund,return,technical,general'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'order_id' => ['nullable', 'exists:orders,id'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:10000'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:jpeg,png,jpg,webp,pdf,zip,doc,docx', 'max:10240'], // 10MB per file
        ]);

        $user = auth()->user();

        DB::beginTransaction();
        try {
            // Upload multiple attachments if provided
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

            // Create Ticket
            $ticket = SupportTicket::create([
                'ticket_number' => SupportTicket::generateTicketNumber(),
                'user_id' => $user->id,
                'order_id' => $validated['order_id'] ?? null,
                'department' => $validated['department'],
                'priority' => $validated['priority'],
                'status' => 'open',
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'attachments' => $uploadedFiles,
                'last_reply_at' => now(),
            ]);

            // Create initial customer message in thread
            TicketMessage::create([
                'support_ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'is_admin' => false,
                'message' => $validated['message'],
                'attachments' => $uploadedFiles,
            ]);

            // Notify Admin via AdminNotification
            AdminNotification::create([
                'type' => 'ticket',
                'title' => 'New Support Ticket #'.$ticket->ticket_number,
                'message' => "Customer {$user->name} opened a {$ticket->department_label} ticket: {$ticket->subject}",
                'link' => route('admin.tickets.show', $ticket->id),
                'data' => [
                    'ticket_id' => $ticket->id,
                    'ticket_number' => $ticket->ticket_number,
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'department' => $ticket->department,
                    'priority' => $ticket->priority,
                ],
                'is_read' => false,
            ]);

            DB::commit();

            return redirect()->route('user.tickets.show', $ticket->id)
                ->with('success', "Support Ticket #{$ticket->ticket_number} created successfully. Our team will review and respond shortly.");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create support ticket: '.$e->getMessage());

            return back()->withInput()->with('error', 'Failed to create support ticket. Please try again.');
        }
    }

    /**
     * Show ticket details and message history.
     */
    public function show(SupportTicket $ticket): View
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket->load(['order', 'messages.user']);

        return view('user.tickets.show', compact('ticket'));
    }

    /**
     * Reply to an open ticket as a customer.
     */
    public function reply(Request $request, SupportTicket $ticket): RedirectResponse
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:10000'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:jpeg,png,jpg,webp,pdf,zip,doc,docx', 'max:10240'],
        ]);

        $user = auth()->user();

        $uploadedFiles = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('tickets/attachments', 'public');
                $uploadedFiles[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'url' => Storage::disk('public')->url($path),
                    'size' => $file->getSize(),
                    'mime' => $file->getClientMimeType(),
                ];
            }
        }

        TicketMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'is_admin' => false,
            'message' => $validated['message'],
            'attachments' => $uploadedFiles,
        ]);

        $ticket->update([
            'status' => 'open', // Re-open or keep open for admin attention
            'last_reply_at' => now(),
        ]);

        // Notify Admin of customer reply
        AdminNotification::create([
            'type' => 'ticket',
            'title' => 'Customer Replied: #'.$ticket->ticket_number,
            'message' => "Customer {$user->name} replied to ticket: {$ticket->subject}",
            'link' => route('admin.tickets.show', $ticket->id),
            'data' => [
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'user_name' => $user->name,
            ],
            'is_read' => false,
        ]);

        return back()->with('success', 'Your reply has been sent successfully.');
    }

    /**
     * Close the ticket by customer.
     */
    public function close(SupportTicket $ticket): RedirectResponse
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket->update(['status' => 'closed']);

        return back()->with('success', "Ticket #{$ticket->ticket_number} has been closed.");
    }
}
