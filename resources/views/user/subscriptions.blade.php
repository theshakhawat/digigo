@extends('layouts.user')

@section('title', 'My Subscriptions & Digital Licenses - DigiGo')
@section('breadcrumb_current', 'Subscriptions')

@section('account_content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-blue-700 via-indigo-700 to-indigo-800 text-white rounded-2xl p-6 sm:p-7 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-blue-200">Active Licenses & Subscriptions</span>
            <h1 class="text-xl sm:text-2xl font-black mt-1">My Digital Subscriptions</h1>
            <p class="text-xs sm:text-sm text-blue-100 mt-1 max-w-xl">
                View your active accounts, login credentials, license keys, validity periods, and fulfillment status for all purchased digital goods.
            </p>
        </div>
        <div class="shrink-0 flex items-center gap-3">
            <div class="px-4 py-2.5 rounded-xl bg-white/10 backdrop-blur-sm border border-white/20 text-center">
                <span class="block text-2xl font-black text-white">{{ $subscriptions->total() }}</span>
                <span class="text-[11px] font-semibold text-blue-200">Total Subscriptions</span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Subscriptions List -->
    @if($subscriptions->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-xs">
            <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fa-solid fa-cloud-arrow-down"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800">No subscriptions found</h3>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-md mx-auto">
                You haven't purchased any digital subscription or software license yet. Check our digital catalog for AI tools, VPNs, and streaming services.
            </p>
            <a href="{{ route('category.show', 'digital-goods') }}" class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs sm:text-sm font-bold shadow-sm transition">
                <i class="fa-solid fa-cart-shopping text-xs"></i>
                <span>Explore Digital Products</span>
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($subscriptions as $sub)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:border-slate-300 transition">
                    <!-- Top Bar of Card -->
                    <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-slate-50/50">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 overflow-hidden border border-blue-200">
                                @if($sub->product && $sub->product->thumbnail_url)
                                    <img src="{{ $sub->product->thumbnail_url }}" alt="{{ $sub->product_name }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-key text-lg"></i>
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="font-bold text-slate-900 text-base">{{ $sub->product_name }}</h3>
                                    <span class="font-mono text-xs text-slate-400">#{{ $sub->subscription_code }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-slate-500 mt-1 flex-wrap">
                                    @if($sub->plan_name)
                                        <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-bold text-[11px]">{{ $sub->plan_name }}</span>
                                    @endif
                                    @if($sub->validity)
                                        <span>• Validity: <strong class="text-slate-700">{{ $sub->validity }}</strong></span>
                                    @endif
                                    @if($sub->order)
                                        <span>• Order: <strong class="font-mono text-slate-700">#{{ $sub->order->order_number }}</strong></span>
                                    @endif
                                    @if($sub->target_email)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 font-semibold text-[11px] border border-indigo-100">
                                            <i class="fa-solid fa-paper-plane text-[10px]"></i>
                                            <span>Activation for: {{ $sub->target_email }}</span>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="shrink-0 flex items-center gap-2">
                            @php
                                $effectiveStatus = $sub->effective_status;
                            @endphp
                            @if($effectiveStatus === 'active')
                                <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>Active</span>
                                </span>
                            @elseif($effectiveStatus === 'pending')
                                <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-amber-100 text-amber-800 flex items-center gap-1.5">
                                    <i class="fa-solid fa-clock text-[11px]"></i>
                                    <span>Pending Activation</span>
                                </span>
                            @elseif($effectiveStatus === 'expired')
                                <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-slate-200 text-slate-700 font-bold flex items-center gap-1.5">
                                    <i class="fa-solid fa-hourglass-end text-[11px] text-slate-500"></i>
                                    <span>Expired</span>
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-rose-100 text-rose-700">
                                    {{ ucfirst($effectiveStatus) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Details Body -->
                    <div class="p-5 sm:p-6 space-y-4">
                        @if($effectiveStatus === 'pending')
                            <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-200 text-amber-900 text-xs sm:text-sm flex items-start gap-3">
                                <i class="fa-solid fa-hourglass-half text-amber-600 text-lg mt-0.5 shrink-0"></i>
                                <div>
                                    <strong class="font-bold">Subscription under processing!</strong>
                                    <p class="text-amber-800 mt-0.5 leading-relaxed">
                                        Your payment has been received successfully. Our team is generating and configuring your dedicated subscription account/license. Your login credentials and access instructions will appear right here as soon as activated.
                                    </p>
                                </div>
                            </div>
                        @elseif($effectiveStatus === 'expired')
                            <div class="p-4 rounded-xl bg-slate-100 border border-slate-300 text-slate-800 text-xs sm:text-sm flex items-start gap-3">
                                <i class="fa-solid fa-triangle-exclamation text-slate-500 text-lg mt-0.5 shrink-0"></i>
                                <div>
                                    <strong class="font-bold text-slate-900">This subscription has expired!</strong>
                                    <p class="text-slate-600 mt-0.5 leading-relaxed">
                                        Your subscription expired on {{ $sub->expires_at?->format('M d, Y h:i A') ?? 'N/A' }}. To continue enjoying premium access, please repurchase or renew the service.
                                    </p>
                                    @if($sub->product)
                                        <a href="{{ route('product.show', $sub->product->slug) }}" class="inline-flex items-center gap-1.5 mt-2.5 px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition">
                                            <i class="fa-solid fa-rotate-right text-xs"></i> Renew Subscription
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @else
                            <!-- Credentials & Access details when Active -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($sub->credentials)
                                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Account Credentials</span>
                                            <button type="button" onclick="navigator.clipboard.writeText('{{ addslashes($sub->credentials) }}'); alert('Credentials copied to clipboard!');" class="text-xs text-blue-600 hover:text-blue-800 font-semibold cursor-pointer">
                                                <i class="fa-regular fa-copy mr-1"></i> Copy
                                            </button>
                                        </div>
                                        <pre class="font-mono text-xs text-slate-800 bg-white p-2.5 rounded-lg border border-slate-200 whitespace-pre-wrap select-all">{{ $sub->credentials }}</pre>
                                    </div>
                                @endif

                                @if($sub->license_key)
                                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">License / Activation Key</span>
                                            <button type="button" onclick="navigator.clipboard.writeText('{{ addslashes($sub->license_key) }}'); alert('License key copied!');" class="text-xs text-blue-600 hover:text-blue-800 font-semibold cursor-pointer">
                                                <i class="fa-regular fa-copy mr-1"></i> Copy
                                            </button>
                                        </div>
                                        <pre class="font-mono text-xs text-slate-800 bg-white p-2.5 rounded-lg border border-slate-200 whitespace-pre-wrap select-all font-bold">{{ $sub->license_key }}</pre>
                                    </div>
                                @endif
                            </div>

                            @if($sub->login_url)
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="text-slate-500 font-medium">Service Login URL:</span>
                                    <a href="{{ $sub->login_url }}" target="_blank" class="text-blue-600 hover:underline font-bold flex items-center gap-1">
                                        <span>{{ $sub->login_url }}</span>
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                    </a>
                                </div>
                            @endif

                            @if($sub->access_instructions)
                                <div class="p-4 rounded-xl bg-blue-50/50 border border-blue-100 text-xs text-slate-700 space-y-1">
                                    <span class="font-bold text-blue-900 block uppercase tracking-wider text-[11px]">Instructions for Use:</span>
                                    <p class="leading-relaxed whitespace-pre-line">{{ $sub->access_instructions }}</p>
                                </div>
                            @endif
                        @endif

                        <!-- Dates timeline -->
                        <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 text-xs text-slate-500">
                            <div>
                                Placed on: <strong>{{ $sub->created_at->format('M d, Y h:i A') }}</strong>
                            </div>
                            @if($sub->activated_at)
                                <div>
                                    Activated on: <strong class="text-emerald-700">{{ $sub->activated_at->format('M d, Y h:i A') }}</strong>
                                </div>
                            @endif
                            @if($sub->expires_at)
                                <div>
                                    Expires on: <strong class="text-slate-800">{{ $sub->expires_at->format('M d, Y') }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="pt-4">
                {{ $subscriptions->links() }}
            </div>
        </div>
    @endif

</div>
@endsection

