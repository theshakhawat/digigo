@extends('layouts.admin')

@section('title', 'Affiliate Withdrawal Requests - DigiGo Administration')

@section('page_title', 'Affiliate Withdrawals')
@section('page_subtitle', 'Manage, verify and disburse customer affiliate payout requests')

@section('content')
<div class="space-y-6">

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Pending Requests -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400 block mb-1">Pending Requests</span>
                    <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $metrics['total_pending_count'] }}</div>
                </div>
                <div class="w-11 h-11 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
            </div>
            <div class="mt-3 text-xs text-slate-500 dark:text-slate-400">
                Pending Volume: <strong class="text-slate-800 dark:text-slate-200">৳{{ number_format($metrics['total_pending_amount'], 2) }}</strong>
            </div>
        </div>

        <!-- Total Disbursed / Approved -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 block mb-1">Total Disbursed</span>
                    <div class="text-2xl font-black text-slate-900 dark:text-white">৳{{ number_format($metrics['total_approved_amount'], 2) }}</div>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="mt-3 text-xs text-slate-500 dark:text-slate-400">
                Processed & settled payouts
            </div>
        </div>

        <!-- Rejected Requests -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 block mb-1">Rejected Requests</span>
                    <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $metrics['total_rejected_count'] }}</div>
                </div>
                <div class="w-11 h-11 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-ban"></i>
                </div>
            </div>
            <div class="mt-3 text-xs text-slate-500 dark:text-slate-400">
                Requests returned / rejected
            </div>
        </div>

        <!-- Settings Shortcut -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-xs flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Commission Settings</span>
            <div class="flex items-center justify-between mt-2">
                <span class="text-xs text-slate-600 dark:text-slate-400">Category & Global Rules</span>
                <a href="{{ route('admin.settings.affiliate') }}" class="px-3 py-1.5 bg-brand-800 hover:bg-brand-700 text-white rounded-lg text-xs font-bold transition">
                    Configure &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <form method="GET" action="{{ route('admin.withdrawals.index') }}" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 shadow-xs flex flex-col sm:flex-row items-center gap-3">
        <!-- Search -->
        <div class="relative flex-1 w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input type="text"
                   name="search"
                   value="{{ $search }}"
                   placeholder="Search by Request #, Trx Ref, Customer Name, Phone, Email..."
                   class="w-full h-10 pl-9 pr-4 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 dark:focus:border-brand-500 transition">
        </div>

        <!-- Status Filter -->
        <div class="w-full sm:w-40">
            <select name="status" class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-700 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                <option value="">All Statuses</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved / Paid</option>
                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <!-- Method Filter -->
        <div class="w-full sm:w-40">
            <select name="method" class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-700 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                <option value="">All Methods</option>
                <option value="mfs" {{ $method === 'mfs' ? 'selected' : '' }}>MFS (bKash/Nagad)</option>
                <option value="bank" {{ $method === 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                <option value="dgepay" {{ $method === 'dgepay' ? 'selected' : '' }}>DGePay Wallet</option>
            </select>
        </div>

        <!-- Submit & Reset -->
        <div class="flex items-center gap-2 shrink-0 w-full sm:w-auto">
            <button type="submit" class="flex-1 sm:flex-initial px-4 h-10 bg-brand-800 hover:bg-brand-700 text-white dark:bg-brand-600 text-xs sm:text-sm font-semibold rounded-lg transition flex items-center justify-center gap-2 cursor-pointer">
                <i class="fa-solid fa-filter text-xs"></i>
                <span>Filter</span>
            </button>
            @if($search || $status || $method)
                <a href="{{ route('admin.withdrawals.index') }}" class="px-3 h-10 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs sm:text-sm font-semibold rounded-lg transition flex items-center justify-center" title="Reset Filters">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </div>
    </form>

    <!-- Withdrawals Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase text-[11px] tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <th class="px-5 py-3.5">Request # & Date</th>
                        <th class="px-5 py-3.5">Affiliate Customer</th>
                        <th class="px-5 py-3.5">Method & Destination</th>
                        <th class="px-5 py-3.5 text-right">Amount</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($withdrawals as $w)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="font-mono font-bold text-brand-600 dark:text-brand-400 block">
                                    #{{ $w->request_number }}
                                </span>
                                <span class="text-[11px] text-slate-400">
                                    {{ $w->created_at->format('d M, Y h:i A') }}
                                </span>
                            </td>

                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900 dark:text-white">
                                    {{ $w->user->name ?? 'Deleted User' }}
                                </div>
                                <div class="text-xs text-slate-500">{{ $w->user->email ?? '' }}</div>
                                <div class="text-xs text-slate-400">{{ $w->user->phone ?? '' }}</div>
                                <div class="text-[11px] text-slate-500 mt-1">
                                    Current User Balance: <strong class="text-blue-600">৳{{ number_format($w->user->affiliate_balance ?? 0, 2) }}</strong>
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <span class="font-bold uppercase text-xs text-slate-800 dark:text-slate-200 block">
                                    {{ $w->method === 'mfs' ? 'MFS' : ($w->method === 'bank' ? 'Bank Transfer' : 'DGePay Wallet') }}
                                </span>
                                <div class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                                    @if($w->method === 'mfs')
                                        <strong>{{ $w->payment_details['provider'] ?? 'MFS' }}</strong> &bull;
                                        <span class="font-mono font-bold">{{ $w->payment_details['number'] ?? '' }}</span>
                                        ({{ ucfirst($w->payment_details['account_type'] ?? 'Personal') }})
                                    @elseif($w->method === 'bank')
                                        <strong>{{ $w->payment_details['bank_name'] ?? 'Bank' }}</strong><br>
                                        <span>A/C:</span> <strong class="font-mono">{{ $w->payment_details['account_number'] ?? '' }}</strong> &bull;
                                        <span>{{ $w->payment_details['account_name'] ?? '' }}</span><br>
                                        <span class="text-[11px] text-slate-400">Branch: {{ $w->payment_details['branch'] ?? 'N/A' }} @if(!empty($w->payment_details['routing_number'])) | Routing: {{ $w->payment_details['routing_number'] }} @endif</span>
                                    @elseif($w->method === 'dgepay')
                                        <span class="font-mono font-bold">{{ $w->payment_details['wallet_number'] ?? '' }}</span>
                                        @if(!empty($w->payment_details['account_name']))
                                            ({{ $w->payment_details['account_name'] }})
                                        @endif
                                    @endif
                                </div>
                                @if(!empty($w->payment_details['user_note']))
                                    <div class="text-[11px] text-slate-500 italic mt-1">User Note: "{{ $w->payment_details['user_note'] }}"</div>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-right font-black text-slate-900 dark:text-white text-base whitespace-nowrap">
                                ৳{{ number_format($w->amount, 2) }}
                            </td>

                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                @if($w->status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 text-xs font-bold">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Approved & Disbursed
                                    </span>
                                    @if($w->transaction_reference)
                                        <div class="text-[11px] font-mono text-slate-500 mt-1">Trx: {{ $w->transaction_reference }}</div>
                                    @endif
                                @elseif($w->status === 'rejected')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300 text-xs font-bold">
                                        <i class="fa-solid fa-ban text-[10px]"></i> Rejected
                                    </span>
                                    @if($w->admin_note)
                                        <div class="text-[11px] text-slate-500 mt-1 italic">"{{ $w->admin_note }}"</div>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 text-xs font-bold">
                                        <i class="fa-solid fa-clock text-[10px]"></i> Pending Review
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                @if($w->status === 'pending')
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Approve Modal Trigger -->
                                        <button type="button"
                                                onclick="openApproveModal({{ $w->id }}, '{{ $w->request_number }}', '{{ $w->amount }}')"
                                                class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition flex items-center gap-1 cursor-pointer">
                                            <i class="fa-solid fa-check"></i>
                                            <span>Disburse</span>
                                        </button>

                                        <!-- Reject Modal Trigger -->
                                        <button type="button"
                                                onclick="openRejectModal({{ $w->id }}, '{{ $w->request_number }}')"
                                                class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold transition flex items-center gap-1 cursor-pointer">
                                            <i class="fa-solid fa-xmark"></i>
                                            <span>Reject</span>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400">Processed by {{ $w->processor->name ?? 'Admin' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 dark:text-slate-500">
                                <i class="fa-solid fa-money-bill-transfer text-3xl mb-2 text-slate-300 dark:text-slate-600 block"></i>
                                No withdrawal requests found matching your filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($withdrawals->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xl max-w-md w-full p-6">
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600"></i>
            <span>Confirm Payout Disbursement</span>
        </h3>
        <p class="text-xs text-slate-500 mb-5">
            Approving this request will mark the funds as disbursed and permanently deduct the balance from the customer's affiliate wallet.
        </p>

        <form id="approveForm" method="POST" action="" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Transaction / Reference ID *</label>
                <input type="text"
                       name="transaction_reference"
                       required
                       placeholder="e.g. MFS Trx ID, Bank Reference #, DGePay Trx"
                       class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs sm:text-sm text-slate-800 dark:text-slate-200 font-mono focus:outline-none focus:border-emerald-600">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Admin Processing Note (Optional)</label>
                <textarea name="admin_note"
                          rows="2"
                          placeholder="Note visible to customer (e.g. Sent via bKash personal wallet)..."
                          class="w-full p-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs sm:text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-emerald-600"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeModals()" class="px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold cursor-pointer transition">
                    Confirm & Disburse Funds
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xl max-w-md w-full p-6">
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
            <span>Reject Withdrawal Request</span>
        </h3>
        <p class="text-xs text-slate-500 mb-5">
            The customer will be notified with your rejection reason, and their wallet balance will remain untouched.
        </p>

        <form id="rejectForm" method="POST" action="" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Rejection Reason *</label>
                <textarea name="admin_note"
                          rows="3"
                          required
                          placeholder="Explain why this request is being rejected (e.g. Invalid account number, account name mismatch)..."
                          class="w-full p-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs sm:text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-rose-600"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeModals()" class="px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold cursor-pointer transition">
                    Confirm Rejection
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openApproveModal(id, requestNumber, amount) {
        const form = document.getElementById('approveForm');
        form.action = `/admin/withdrawals/${id}/approve`;
        document.getElementById('approveModal').classList.remove('hidden');
    }

    function openRejectModal(id, requestNumber) {
        const form = document.getElementById('rejectForm');
        form.action = `/admin/withdrawals/${id}/reject`;
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeModals() {
        document.getElementById('approveModal').classList.add('hidden');
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endpush
@endsection

