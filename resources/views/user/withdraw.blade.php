@extends('layouts.user')

@section('title', 'Withdraw Balance & Payouts - My Account - DigiGo')
@section('breadcrumb_current', 'Withdraw')

@section('account_content')
<div class="space-y-8">

    <!-- Top Balance Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 text-white rounded-2xl p-6 shadow-sm relative overflow-hidden">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-100 block mb-1">Available Affiliate Balance</span>
            <div class="text-3xl font-black">৳{{ number_format($user->affiliate_balance, 2) }}</div>
            <p class="text-xs text-emerald-100 mt-3">
                Minimum Payout: <strong>৳{{ number_format($minWithdrawal, 2) }}</strong>
            </p>
            <i class="fa-solid fa-money-bill-transfer text-white/10 text-8xl absolute -right-3 -bottom-4 pointer-events-none"></i>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Earned</span>
                <div class="text-2xl sm:text-3xl font-black text-slate-900">৳{{ number_format($user->total_affiliate_earned, 2) }}</div>
            </div>
            <div class="text-xs text-slate-500 mt-3 flex items-center gap-1.5">
                <i class="fa-solid fa-check-circle text-emerald-600"></i>
                <span>All confirmed commissions</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Payouts Settled</span>
                <div class="text-2xl sm:text-3xl font-black text-slate-900">৳{{ number_format($user->total_affiliate_withdrawn, 2) }}</div>
            </div>
            <div class="text-xs text-slate-500 mt-3 flex items-center gap-1.5">
                <i class="fa-solid fa-building-columns text-blue-600"></i>
                <span>Successfully sent to your account</span>
            </div>
        </div>
    </div>

    <!-- New Withdrawal Request Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <h2 class="text-lg font-bold text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-paper-plane text-emerald-600"></i>
            <span>Request New Payout Withdrawal</span>
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 mb-6">
            You can withdraw your earnings using <strong>Mobile Financial Services (MFS)</strong>, <strong>Bank Transfer</strong>, or <strong>DGePay Wallet</strong> once your balance reaches at least ৳{{ number_format($minWithdrawal, 2) }}.
        </p>

        @if($user->affiliate_balance < $minWithdrawal)
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs sm:text-sm flex items-start gap-3">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 text-base mt-0.5 shrink-0"></i>
                <div>
                    <strong>Minimum withdrawal limit not reached.</strong> You need at least <strong>৳{{ number_format($minWithdrawal, 2) }}</strong> to make a withdrawal request. Your current balance is ৳{{ number_format($user->affiliate_balance, 2) }}. Share your affiliate links to earn more commissions!
                </div>
            </div>
        @else
            <form action="{{ route('user.withdraw.store') }}" method="POST" id="withdrawalForm" class="space-y-6">
                @csrf

                <!-- Withdrawal Amount -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Withdrawal Amount (BDT ৳) *</label>
                    <div class="relative max-w-md">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-bold">৳</span>
                        <input type="number"
                               name="amount"
                               step="0.01"
                               min="{{ $minWithdrawal }}"
                               max="{{ $user->affiliate_balance }}"
                               value="{{ old('amount', $user->affiliate_balance) }}"
                               required
                               class="w-full h-11 pl-8 pr-4 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:border-emerald-600 focus:bg-white transition">
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1">Available to withdraw: <strong>৳{{ number_format($user->affiliate_balance, 2) }}</strong></p>
                </div>

                <!-- Withdrawal Method Radio Cards (MFS, Bank, Dgepay) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Select Payout Method *</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <!-- 1. MFS -->
                        <label class="relative flex items-center gap-3 p-4 rounded-xl border border-slate-200 cursor-pointer hover:border-emerald-500 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/40 transition">
                            <input type="radio" name="method" value="mfs" onchange="toggleMethodFields('mfs')" {{ old('method', 'mfs') === 'mfs' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <div class="font-bold text-sm text-slate-900">MFS Mobile Banking</div>
                                <div class="text-[11px] text-slate-500">bKash, Nagad, Rocket, Upay</div>
                            </div>
                        </label>

                        <!-- 2. Bank -->
                        <label class="relative flex items-center gap-3 p-4 rounded-xl border border-slate-200 cursor-pointer hover:border-emerald-500 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/40 transition">
                            <input type="radio" name="method" value="bank" onchange="toggleMethodFields('bank')" {{ old('method') === 'bank' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <div class="font-bold text-sm text-slate-900">Bank Transfer</div>
                                <div class="text-[11px] text-slate-500">Any commercial bank account</div>
                            </div>
                        </label>

                        <!-- 3. DGePay -->
                        <label class="relative flex items-center gap-3 p-4 rounded-xl border border-slate-200 cursor-pointer hover:border-emerald-500 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/40 transition">
                            <input type="radio" name="method" value="dgepay" onchange="toggleMethodFields('dgepay')" {{ old('method') === 'dgepay' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <div class="font-bold text-sm text-slate-900">DGePay Wallet</div>
                                <div class="text-[11px] text-slate-500">Direct account disburse</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Dynamic Fields Container: MFS Fields -->
                <div id="mfsFields" class="p-5 rounded-xl bg-slate-50 border border-slate-200 space-y-4 {{ old('method', 'mfs') === 'mfs' ? '' : 'hidden' }}">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">MFS Provider *</label>
                            <select name="mfs_provider" class="w-full h-10 px-3 bg-white border border-slate-300 rounded-lg text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-emerald-600">
                                <option value="bKash" {{ old('mfs_provider') === 'bKash' ? 'selected' : '' }}>bKash</option>
                                <option value="Nagad" {{ old('mfs_provider') === 'Nagad' ? 'selected' : '' }}>Nagad</option>
                                <option value="Rocket" {{ old('mfs_provider') === 'Rocket' ? 'selected' : '' }}>Rocket</option>
                                <option value="Upay" {{ old('mfs_provider') === 'Upay' ? 'selected' : '' }}>Upay</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Mobile Account Number *</label>
                            <input type="tel"
                                   name="mfs_number"
                                   value="{{ old('mfs_number', $user->phone) }}"
                                   placeholder="017XXXXXXXX"
                                   class="w-full h-10 px-3 bg-white border border-slate-300 rounded-lg text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-emerald-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Account Type *</label>
                            <select name="mfs_account_type" class="w-full h-10 px-3 bg-white border border-slate-300 rounded-lg text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-emerald-600">
                                <option value="personal" {{ old('mfs_account_type') === 'personal' ? 'selected' : '' }}>Personal</option>
                                <option value="agent" {{ old('mfs_account_type') === 'agent' ? 'selected' : '' }}>Agent</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Fields Container: Bank Fields -->
                <div id="bankFields" class="p-5 rounded-xl bg-slate-50 border border-slate-200 space-y-4 {{ old('method') === 'bank' ? '' : 'hidden' }}">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bank Name *</label>
                            <input type="text"
                                   name="bank_name"
                                   value="{{ old('bank_name') }}"
                                   placeholder="e.g. Dutch-Bangla Bank, City Bank, BRAC Bank"
                                   class="w-full h-10 px-3 bg-white border border-slate-300 rounded-lg text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-emerald-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Account Holder Name *</label>
                            <input type="text"
                                   name="bank_account_name"
                                   value="{{ old('bank_account_name', $user->name) }}"
                                   placeholder="Name as registered with bank"
                                   class="w-full h-10 px-3 bg-white border border-slate-300 rounded-lg text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-emerald-600">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Account Number *</label>
                            <input type="text"
                                   name="bank_account_number"
                                   value="{{ old('bank_account_number') }}"
                                   placeholder="Enter account number"
                                   class="w-full h-10 px-3 bg-white border border-slate-300 rounded-lg text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-emerald-600 font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Branch Name *</label>
                            <input type="text"
                                   name="bank_branch"
                                   value="{{ old('bank_branch') }}"
                                   placeholder="e.g. Dhanmondi Branch"
                                   class="w-full h-10 px-3 bg-white border border-slate-300 rounded-lg text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-emerald-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Routing Number (Optional)</label>
                            <input type="text"
                                   name="bank_routing_number"
                                   value="{{ old('bank_routing_number') }}"
                                   placeholder="9-digit routing number"
                                   class="w-full h-10 px-3 bg-white border border-slate-300 rounded-lg text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-emerald-600 font-mono">
                        </div>
                    </div>
                </div>

                <!-- Dynamic Fields Container: DGePay Fields -->
                <div id="dgepayFields" class="p-5 rounded-xl bg-slate-50 border border-slate-200 space-y-4 {{ old('method') === 'dgepay' ? '' : 'hidden' }}">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">DGePay Wallet / Registered Number *</label>
                            <input type="text"
                                   name="dgepay_wallet_number"
                                   value="{{ old('dgepay_wallet_number', $user->phone) }}"
                                   placeholder="016XXXXXXXX"
                                   class="w-full h-10 px-3 bg-white border border-slate-300 rounded-lg text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-emerald-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Account Holder Name (Optional)</label>
                            <input type="text"
                                   name="dgepay_account_name"
                                   value="{{ old('dgepay_account_name', $user->name) }}"
                                   placeholder="Wallet holder name"
                                   class="w-full h-10 px-3 bg-white border border-slate-300 rounded-lg text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-emerald-600">
                        </div>
                    </div>
                </div>

                <!-- Note / Remarks -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Additional Instructions (Optional)</label>
                    <textarea name="user_note"
                              rows="2"
                              placeholder="Any specific instructions for finance team..."
                              class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-emerald-600 focus:bg-white"></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="px-8 h-12 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Submit Withdrawal Request</span>
                </button>
            </form>
        @endif
    </div>

    <!-- Payout Withdrawal History Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex items-center justify-between flex-wrap gap-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Withdrawal History & Status</h3>
                <p class="text-xs text-slate-500 mt-0.5">Track your submitted payout disbursement requests.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider border-b border-slate-200">
                        <th class="px-5 py-3.5">Date & Request #</th>
                        <th class="px-5 py-3.5">Method & Destination Details</th>
                        <th class="px-5 py-3.5 text-right">Amount</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5">Reference / Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($requests as $req)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="font-mono font-bold text-slate-900 block">#{{ $req->request_number }}</span>
                                <span class="text-[11px] text-slate-400">{{ $req->created_at->format('d M, Y h:i A') }}</span>
                            </td>

                            <td class="px-5 py-4">
                                <span class="font-bold uppercase text-xs text-slate-800 block">
                                    {{ $req->method === 'mfs' ? 'MFS Mobile Banking' : ($req->method === 'bank' ? 'Bank Transfer' : 'DGePay Wallet') }}
                                </span>
                                <div class="text-xs text-slate-600 mt-0.5">
                                    @if($req->method === 'mfs')
                                        <strong>{{ $req->payment_details['provider'] ?? 'MFS' }}</strong> &bull;
                                        <span class="font-mono">{{ $req->payment_details['number'] ?? '' }}</span>
                                        ({{ ucfirst($req->payment_details['account_type'] ?? 'Personal') }})
                                    @elseif($req->method === 'bank')
                                        <strong>{{ $req->payment_details['bank_name'] ?? 'Bank' }}</strong><br>
                                        <span class="text-slate-500">A/C:</span> <strong class="font-mono text-slate-800">{{ $req->payment_details['account_number'] ?? '' }}</strong> &bull;
                                        <span>{{ $req->payment_details['account_name'] ?? '' }}</span><br>
                                        <span class="text-[11px] text-slate-400">Branch: {{ $req->payment_details['branch'] ?? 'N/A' }}</span>
                                    @elseif($req->method === 'dgepay')
                                        <span class="font-mono">{{ $req->payment_details['wallet_number'] ?? '' }}</span>
                                        @if(!empty($req->payment_details['account_name']))
                                            ({{ $req->payment_details['account_name'] }})
                                        @endif
                                    @endif
                                </div>
                            </td>

                            <td class="px-5 py-4 text-right font-black text-slate-900 text-sm whitespace-nowrap">
                                ৳{{ number_format($req->amount, 2) }}
                            </td>

                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                @if($req->status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Disbursed & Settled
                                    </span>
                                @elseif($req->status === 'rejected')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-rose-100 text-rose-800 text-xs font-bold">
                                        <i class="fa-solid fa-ban text-[10px]"></i> Rejected
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">
                                        <i class="fa-solid fa-hourglass-half text-[10px]"></i> Pending Approval
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-xs text-slate-500">
                                @if($req->transaction_reference)
                                    <div><span class="text-slate-400 font-bold text-[10px] uppercase">Trx Ref:</span> <span class="font-mono font-bold text-slate-800">{{ $req->transaction_reference }}</span></div>
                                @endif
                                @if($req->admin_note)
                                    <div class="mt-0.5 text-slate-600 italic">"{{ $req->admin_note }}"</div>
                                @endif
                                @if(!$req->transaction_reference && !$req->admin_note)
                                    <span class="text-slate-400">Pending verification</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-receipt text-3xl mb-2 text-slate-300 block"></i>
                                No withdrawal requests submitted yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $requests->links() }}
            </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
    function toggleMethodFields(method) {
        document.getElementById('mfsFields').classList.add('hidden');
        document.getElementById('bankFields').classList.add('hidden');
        document.getElementById('dgepayFields').classList.add('hidden');

        if (method === 'mfs') {
            document.getElementById('mfsFields').classList.remove('hidden');
        } else if (method === 'bank') {
            document.getElementById('bankFields').classList.remove('hidden');
        } else if (method === 'dgepay') {
            document.getElementById('dgepayFields').classList.remove('hidden');
        }
    }
</script>
@endpush
@endsection

