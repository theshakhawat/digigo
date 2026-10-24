<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WithdrawalRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of withdrawal requests.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $method = $request->query('method');
        $search = trim($request->query('search', ''));

        $query = WithdrawalRequest::with('user', 'processor')->latest();

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($method && in_array($method, ['mfs', 'bank', 'dgepay'])) {
            $query->where('method', $method);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('request_number', 'like', "%{$search}%")
                    ->orWhere('transaction_reference', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        $withdrawals = $query->paginate(15)->withQueryString();

        $metrics = [
            'total_pending_count' => WithdrawalRequest::where('status', 'pending')->count(),
            'total_pending_amount' => WithdrawalRequest::where('status', 'pending')->sum('amount'),
            'total_approved_amount' => WithdrawalRequest::where('status', 'approved')->sum('amount'),
            'total_rejected_count' => WithdrawalRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.withdrawals.index', compact('withdrawals', 'metrics', 'status', 'method', 'search'));
    }

    /**
     * Show single withdrawal details.
     */
    public function show(WithdrawalRequest $withdrawal): View
    {
        $withdrawal->load('user', 'processor');

        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    /**
     * Approve and disburse withdrawal request, deducting user's affiliate balance.
     */
    public function approve(Request $request, WithdrawalRequest $withdrawal): RedirectResponse
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be approved.');
        }

        $validated = $request->validate([
            'transaction_reference' => ['required', 'string', 'max:100'],
            'admin_note' => ['nullable', 'string', 'max:500'],
        ]);

        $result = DB::transaction(function () use ($withdrawal, $validated) {
            /** @var User $user */
            $user = User::lockForUpdate()->findOrFail($withdrawal->user_id);

            // Double check user balance
            if ($user->affiliate_balance < $withdrawal->amount) {
                return [
                    'success' => false,
                    'message' => "User does not have enough balance (Current: ৳{$user->affiliate_balance}, Requested: ৳{$withdrawal->amount}).",
                ];
            }

            // Deduct balance and increment withdrawn
            $user->decrement('affiliate_balance', $withdrawal->amount);
            $user->increment('total_affiliate_withdrawn', $withdrawal->amount);

            // Update withdrawal status
            $withdrawal->update([
                'status' => 'approved',
                'transaction_reference' => $validated['transaction_reference'],
                'admin_note' => $validated['admin_note'] ?? null,
                'processed_by' => Auth::id(),
                'processed_at' => now(),
            ]);

            return ['success' => true];
        });

        if (! $result['success']) {
            return back()->with('error', $result['message']);
        }

        return redirect()->route('admin.withdrawals.index')->with('success', "Withdrawal request #{$withdrawal->request_number} approved and ৳".number_format($withdrawal->amount, 2).' successfully disbursed.');
    }

    /**
     * Reject withdrawal request (balance remains untouched or reverted).
     */
    public function reject(Request $request, WithdrawalRequest $withdrawal): RedirectResponse
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be rejected.');
        }

        $validated = $request->validate([
            'admin_note' => ['required', 'string', 'max:500'],
        ]);

        $withdrawal->update([
            'status' => 'rejected',
            'admin_note' => $validated['admin_note'],
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        return redirect()->route('admin.withdrawals.index')->with('success', "Withdrawal request #{$withdrawal->request_number} has been rejected.");
    }
}
