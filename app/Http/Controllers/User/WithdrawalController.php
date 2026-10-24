<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Setting;
use App\Models\User;
use App\Models\WithdrawalRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WithdrawalController extends Controller
{
    /**
     * Display user withdrawal dashboard and history.
     */
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $minWithdrawal = (float) Setting::get('affiliate_min_withdrawal', '500');

        $requests = WithdrawalRequest::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('user.withdraw', compact('user', 'minWithdrawal', 'requests'));
    }

    /**
     * Store new withdrawal request.
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $minWithdrawal = (float) Setting::get('affiliate_min_withdrawal', '500');

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:'.$minWithdrawal],
            'method' => ['required', 'in:mfs,bank,dgepay'],

            // MFS Fields
            'mfs_provider' => ['required_if:method,mfs', 'nullable', 'string', 'in:bKash,Nagad,Rocket,Upay'],
            'mfs_number' => ['required_if:method,mfs', 'nullable', 'string', 'max:20'],
            'mfs_account_type' => ['required_if:method,mfs', 'nullable', 'string', 'in:personal,agent'],

            // Bank Fields
            'bank_name' => ['required_if:method,bank', 'nullable', 'string', 'max:100'],
            'bank_account_name' => ['required_if:method,bank', 'nullable', 'string', 'max:100'],
            'bank_account_number' => ['required_if:method,bank', 'nullable', 'string', 'max:50'],
            'bank_branch' => ['required_if:method,bank', 'nullable', 'string', 'max:100'],
            'bank_routing_number' => ['nullable', 'string', 'max:30'],

            // DGePay Fields
            'dgepay_wallet_number' => ['required_if:method,dgepay', 'nullable', 'string', 'max:30'],
            'dgepay_account_name' => ['nullable', 'string', 'max:100'],

            'user_note' => ['nullable', 'string', 'max:500'],
        ]);

        $amount = round((float) $validated['amount'], 2);

        // Check if user has sufficient available balance
        if ($user->affiliate_balance < $amount) {
            return back()->withInput()->with('error', 'Insufficient affiliate balance. Your current balance is ৳'.number_format($user->affiliate_balance, 2));
        }

        // Assemble payment details depending on method
        $paymentDetails = [];
        if ($validated['method'] === 'mfs') {
            $paymentDetails = [
                'provider' => $validated['mfs_provider'],
                'number' => $validated['mfs_number'],
                'account_type' => $validated['mfs_account_type'] ?? 'personal',
            ];
        } elseif ($validated['method'] === 'bank') {
            $paymentDetails = [
                'bank_name' => $validated['bank_name'],
                'account_name' => $validated['bank_account_name'],
                'account_number' => $validated['bank_account_number'],
                'branch' => $validated['bank_branch'],
                'routing_number' => $validated['bank_routing_number'] ?? null,
            ];
        } elseif ($validated['method'] === 'dgepay') {
            $paymentDetails = [
                'wallet_number' => $validated['dgepay_wallet_number'],
                'account_name' => $validated['dgepay_account_name'] ?? $user->name,
            ];
        }

        if (! empty($validated['user_note'])) {
            $paymentDetails['user_note'] = $validated['user_note'];
        }

        $withdrawalRequest = DB::transaction(function () use ($user, $amount, $validated, $paymentDetails) {
            return WithdrawalRequest::create([
                'request_number' => WithdrawalRequest::generateRequestNumber(),
                'user_id' => $user->id,
                'amount' => $amount,
                'method' => $validated['method'],
                'status' => 'pending',
                'payment_details' => $paymentDetails,
            ]);
        });

        // Notify Admin of new withdrawal request
        try {
            AdminNotification::create([
                'type' => 'withdrawal',
                'title' => 'New Withdrawal Request #'.$withdrawalRequest->request_number,
                'message' => "Customer {$user->name} requested withdrawal of ৳".number_format($amount, 2).' via '.strtoupper($validated['method']),
                'link' => route('admin.withdrawals.index'),
                'data' => [
                    'request_id' => $withdrawalRequest->id,
                    'request_number' => $withdrawalRequest->request_number,
                    'amount' => $amount,
                    'method' => $validated['method'],
                    'user_id' => $user->id,
                ],
                'is_read' => false,
            ]);
        } catch (Exception $e) {
            // Ignored notification exception
        }

        return redirect()->route('user.withdraw')->with('success', 'Your withdrawal request of ৳'.number_format($amount, 2).' has been submitted successfully and is pending admin approval.');
    }
}
