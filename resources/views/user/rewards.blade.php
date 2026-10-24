@extends('layouts.user')

@section('title', 'Affiliate Program & Link Generator - My Account - DigiGo')
@section('breadcrumb_current', 'Affiliate')

@section('account_content')
<div class="space-y-8">

    <!-- Top Earnings Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <!-- 1. Available Affiliate Balance -->
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-2xl p-6 shadow-sm relative overflow-hidden">
            <div class="relative z-10">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-100 block mb-1">Available Affiliate Balance</span>
                <div class="text-3xl font-black">৳{{ number_format($user->affiliate_balance, 2) }}</div>
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-xs text-blue-100">Ready to withdraw</span>
                    <a href="{{ route('user.withdraw') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white text-blue-700 rounded-lg text-xs font-bold hover:bg-blue-50 transition shadow-xs">
                        <span>Withdraw</span> &rarr;
                    </a>
                </div>
            </div>
            <i class="fa-solid fa-wallet text-white/10 text-8xl absolute -right-3 -bottom-4 pointer-events-none"></i>
        </div>

        <!-- 2. Total Earned -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Commission Earned</span>
                <div class="text-3xl font-black text-slate-900">৳{{ number_format($user->total_affiliate_earned, 2) }}</div>
            </div>
            <div class="text-xs text-slate-500 mt-4 flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-trend-up text-emerald-600"></i>
                <span>Lifetime referral revenue credited</span>
            </div>
        </div>

        <!-- 3. Total Withdrawn -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Payouts Withdrawn</span>
                <div class="text-3xl font-black text-slate-900">৳{{ number_format($user->total_affiliate_withdrawn, 2) }}</div>
            </div>
            <div class="text-xs text-slate-500 mt-4 flex items-center gap-1.5">
                <i class="fa-solid fa-money-bill-transfer text-blue-600"></i>
                <span>Disbursed to Bank / MFS / DGePay</span>
            </div>
        </div>
    </div>

    <!-- Your Global Referral Link & Code Box -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <h2 class="text-lg font-bold text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-share-nodes text-blue-600"></i>
            <span>Your General Referral Link</span>
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 mb-5">
            Share this link anywhere (social media, blogs, YouTube, WhatsApp). Any visitor using your link will have your referral cookie saved for 30 days. You will receive commissions when their order is paid or delivered!
        </p>

        @php
            $generalRefUrl = url('/?ref=' . $affiliateCode);
        @endphp

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <div class="relative flex-1">
                <input type="text"
                       id="generalRefInput"
                       readonly
                       value="{{ $generalRefUrl }}"
                       class="w-full h-11 pl-4 pr-12 text-xs sm:text-sm font-mono font-bold bg-slate-50 border border-slate-300 rounded-xl text-blue-700 select-all focus:outline-none">
            </div>
            <button type="button"
                    onclick="copyToClipboard('{{ $generalRefUrl }}', this)"
                    class="h-11 px-6 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs sm:text-sm font-bold transition flex items-center justify-center gap-2 cursor-pointer shadow-sm shrink-0">
                <i class="fa-regular fa-copy"></i>
                <span>Copy Referral Link</span>
            </button>
        </div>

        <div class="mt-4 flex items-center gap-4 text-xs text-slate-500">
            <div>Your Referral Code: <strong class="font-mono text-slate-800 bg-slate-100 px-2 py-0.5 rounded">{{ $affiliateCode }}</strong></div>
            <div>&bull;</div>
            <div>Cookie Lifetime: <strong>30 Days</strong></div>
        </div>
    </div>

    <!-- Specific Product Affiliate Link Generator -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <h2 class="text-lg font-bold text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-link text-blue-600"></i>
            <span>Product Specific Affiliate Link Generator</span>
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 mb-6">
            Paste any DigiGo product page link or select a product from our catalog below to generate an exact direct affiliate link.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
            <!-- Select from Catalog -->
            <div class="md:col-span-5">
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Select a Product</label>
                <select id="productSelect" onchange="generateFromSelect()" class="w-full h-11 px-3 bg-white border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-700 focus:outline-none focus:border-blue-600">
                    <option value="">-- Choose Product from Store --</option>
                    @foreach($products as $prod)
                        <option value="{{ route('product.show', $prod->slug) }}">{{ $prod->name }} (৳{{ number_format($prod->price, 0) }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Or Paste URL -->
            <div class="md:col-span-5">
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Or Paste Store URL</label>
                <input type="text"
                       id="customUrlInput"
                       placeholder="e.g. {{ url('/product/windows-11-pro') }}"
                       class="w-full h-11 px-4 bg-white border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-600">
            </div>

            <div class="md:col-span-2">
                <button type="button"
                        onclick="generateCustomAffiliateLink()"
                        class="w-full h-11 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs sm:text-sm font-bold transition flex items-center justify-center gap-1.5 cursor-pointer shadow-sm">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>Generate</span>
                </button>
            </div>
        </div>

        <!-- Generated Link Result Box -->
        <div id="generatedLinkBox" class="mt-6 p-4 rounded-xl bg-blue-50/70 border border-blue-200 hidden">
            <span class="text-xs font-bold text-blue-900 uppercase tracking-wider block mb-1.5">Your Generated Product Affiliate Link:</span>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <input type="text"
                       id="generatedLinkInput"
                       readonly
                       class="flex-1 h-10 px-3 bg-white border border-blue-300 rounded-lg text-xs font-mono font-bold text-blue-700 select-all focus:outline-none">
                <button type="button"
                        id="copyGeneratedBtn"
                        onclick="copyGeneratedLink(this)"
                        class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer shrink-0">
                    <i class="fa-regular fa-copy"></i>
                    <span>Copy Link</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Referral Commissions History Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex items-center justify-between flex-wrap gap-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Commission Earnings & Referral History</h3>
                <p class="text-xs text-slate-500 mt-0.5">Orders tracked through your affiliate link.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider border-b border-slate-200">
                        <th class="px-5 py-3.5">Date</th>
                        <th class="px-5 py-3.5">Order #</th>
                        <th class="px-5 py-3.5">Payment & Status</th>
                        <th class="px-5 py-3.5 text-right">Commission Amount</th>
                        <th class="px-5 py-3.5 text-center">Earning Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($earnings as $earning)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-5 py-4 text-slate-600 font-medium whitespace-nowrap">
                                {{ $earning->created_at->format('d M, Y') }}
                                <div class="text-[11px] text-slate-400">{{ $earning->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-5 py-4 font-mono font-bold text-slate-900 whitespace-nowrap">
                                #{{ $earning->order->order_number ?? 'N/A' }}
                                @if($earning->order)
                                    <div class="text-[11px] font-sans font-normal text-slate-500">
                                        Order Total: ৳{{ number_format($earning->order->total, 2) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="capitalize font-semibold text-slate-700">
                                    {{ $earning->order->payment_method ?? 'N/A' }}
                                </span>
                                <div class="text-[11px]">
                                    @if(($earning->order->payment_status ?? '') === 'paid')
                                        <span class="text-emerald-600 font-bold">Paid</span>
                                    @else
                                        <span class="text-amber-600 font-bold">{{ ucfirst($earning->order->payment_status ?? 'Pending') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right font-extrabold text-blue-700 text-sm whitespace-nowrap">
                                ৳{{ number_format($earning->amount, 2) }}
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                @if($earning->status === 'credited')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Credited
                                    </span>
                                @elseif($earning->status === 'cancelled')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-rose-100 text-rose-800 text-xs font-bold">
                                        <i class="fa-solid fa-ban text-[10px]"></i> Cancelled
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">
                                        <i class="fa-solid fa-clock text-[10px]"></i> Pending Delivery/Payment
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-chart-line text-3xl mb-2 text-slate-300 block"></i>
                                No affiliate referrals or commissions earned yet. Share your links to start earning!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($earnings->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $earnings->links() }}
            </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
    const userAffiliateCode = @json($affiliateCode);

    function copyToClipboard(text, btnElement) {
        navigator.clipboard.writeText(text).then(() => {
            const originalHtml = btnElement.innerHTML;
            btnElement.innerHTML = '<i class="fa-solid fa-check"></i> <span>Copied!</span>';
            btnElement.classList.replace('bg-blue-600', 'bg-emerald-600');
            setTimeout(() => {
                btnElement.innerHTML = originalHtml;
                btnElement.classList.replace('bg-emerald-600', 'bg-blue-600');
            }, 2000);
        });
    }

    function generateFromSelect() {
        const select = document.getElementById('productSelect');
        const url = select.value;
        if (!url) return;

        attachAffiliateAndShow(url);
    }

    function generateCustomAffiliateLink() {
        const input = document.getElementById('customUrlInput');
        let url = input.value.trim();
        if (!url) {
            alert('Please select a product or enter a valid store link.');
            return;
        }

        attachAffiliateAndShow(url);
    }

    function attachAffiliateAndShow(rawUrl) {
        try {
            let parsed = new URL(rawUrl, window.location.origin);
            parsed.searchParams.set('ref', userAffiliateCode);
            const finalLink = parsed.toString();

            const box = document.getElementById('generatedLinkBox');
            const linkInput = document.getElementById('generatedLinkInput');
            linkInput.value = finalLink;
            box.classList.remove('hidden');
            linkInput.select();
        } catch (e) {
            alert('Invalid URL format. Please paste a valid web address.');
        }
    }

    function copyGeneratedLink(btnElement) {
        const link = document.getElementById('generatedLinkInput').value;
        copyToClipboard(link, btnElement);
    }
</script>
@endpush
@endsection
