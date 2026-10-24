@extends('layouts.admin')

@section('title', request('tab') === 'ticker' ? 'Scrolling Trust Features Ticker' : (request('tab') === 'promo' ? 'Promotional Showcase Banner' : (request('tab') === 'tri_promo' ? '3-Card Feature Highlights' : (request('tab') === 'dual_promo' ? 'Coupon & Deals Announcement Banners' : (request('tab') === 'remini' ? 'AI Tool Promotional Banner' : 'Hero Banner Management')))))

@push('styles')
<style>
/* Continuous scrolling marquee animation for admin live preview */
.trust-features-bar {
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    overflow: hidden;
    position: relative;
    user-select: none;
}
.trust-marquee-track {
    display: flex !important;
    flex-direction: row !important;
    flex-wrap: nowrap !important;
    width: max-content !important;
    will-change: transform;
    animation: trustMarqueeScroll 28s linear infinite;
}
.trust-features-bar:hover .trust-marquee-track {
    animation-play-state: paused;
}
.trust-marquee-group {
    display: flex !important;
    flex-direction: row !important;
    flex-wrap: nowrap !important;
    align-items: center !important;
    gap: 1.5rem !important;
    padding-right: 1.5rem !important;
    flex-shrink: 0 !important;
}
.trust-feature-item {
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.65rem !important;
    white-space: nowrap !important;
    font-size: 0.88rem;
    font-weight: 600;
    color: #1e293b;
    background: #f8fafc;
    padding: 0.45rem 0.9rem;
    border-radius: 9999px;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}
.dark .trust-feature-item {
    color: #f1f5f9;
    background: #0f172a;
    border-color: #334155;
}
.trust-icon-box {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 26px !important;
    height: 26px !important;
    border-radius: 50% !important;
    background: #eff6ff !important;
    color: #2563eb !important;
    flex-shrink: 0 !important;
}
.dark .trust-icon-box {
    background: #1e293b !important;
    color: #60a5fa !important;
}
.trust-icon-box svg {
    width: 14px;
    height: 14px;
}
.trust-icon-box i {
    font-size: 13px;
    line-height: 1;
}
@keyframes trustMarqueeScroll {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}
</style>
@endpush

@section('admin_content')
<div class="space-y-6">

    <!-- Top Action & Breadcrumb Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
                <span>/</span>
                <span>Website Appearance</span>
                <span>/</span>
                <span class="text-slate-800 dark:text-slate-200 font-medium">Banners</span>
                @if(request('tab') === 'ticker')
                    <span>/</span>
                    <span class="text-brand-600 dark:text-brand-400 font-medium">Trust Features Ticker</span>
                @elseif(request('tab') === 'promo')
                    <span>/</span>
                    <span class="text-brand-600 dark:text-brand-400 font-medium">Promo Showcase Banner</span>
                @elseif(request('tab') === 'tri_promo')
                    <span>/</span>
                    <span class="text-brand-600 dark:text-brand-400 font-medium">3-Card Feature Highlights</span>
                @elseif(request('tab') === 'dual_promo')
                    <span>/</span>
                    <span class="text-brand-600 dark:text-brand-400 font-medium">Coupon & Deals (2-Cards)</span>
                @elseif(request('tab') === 'remini')
                    <span>/</span>
                    <span class="text-brand-600 dark:text-brand-400 font-medium">AI Tool Promo Banner</span>
                @endif
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                @if(request('tab') === 'ticker')
                    Scrolling Trust Features Ticker
                @elseif(request('tab') === 'promo')
                    Promotional Showcase Banner
                @elseif(request('tab') === 'tri_promo')
                    3-Card Feature Highlights
                @elseif(request('tab') === 'dual_promo')
                    Coupon & Deals Announcement Banners
                @elseif(request('tab') === 'remini')
                    AI Tool Promotional Banner
                @else
                    Hero Banner Management
                @endif
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                @if(request('tab') === 'ticker')
                    Manage the continuous scrolling marquee ticker directly beneath your hero banner: toggle visibility, control animation speed, and edit trust badges.
                @elseif(request('tab') === 'promo')
                    Customize the homepage middle promotional showcase banner: headlines, floating earbud/product visuals, background image, button labels, and targets.
                @elseif(request('tab') === 'tri_promo')
                    Customize the 3-column promotional feature cards on the homepage: edit titles, descriptions, buttons, light/dark themes, and upload custom product graphics.
                @elseif(request('tab') === 'remini')
                    Customize the AI image editing promotional banner (Remini): edit headlines, colors, app badge icon & name, and landscape comparison photo.
                @else
                    Control every slide of your homepage hero carousel: upload banner graphics, edit headlines, set CTA buttons, and adjust slide transitions.
                @endif
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 shadow-sm transition">
                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                <span>View Live Store</span>
            </a>
            @if(request('tab') === 'ticker')
                <button type="submit" form="trustFeaturesForm" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-sm transition cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save Ticker Settings</span>
                </button>
            @elseif(request('tab') === 'promo')
                <button type="submit" form="promoBannerForm" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-sm transition cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save Promo Banner</span>
                </button>
            @elseif(request('tab') === 'tri_promo')
                <button type="submit" form="triPromoForm" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-sm transition cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save 3-Card Highlights</span>
                </button>
            @elseif(request('tab') === 'remini')
                <button type="submit" form="reminiBannerForm" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-sm transition cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save AI Tool Banner</span>
                </button>
            @else
                <a href="{{ route('admin.appearance.banners.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-sm transition">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Add Banner Slide</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Section Tabs -->
    <div class="border-b border-slate-200 dark:border-slate-700 flex items-center gap-2 overflow-x-auto">
        <a href="{{ route('admin.appearance.banners.index', ['tab' => 'slides']) }}"
           class="inline-flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold border-b-2 transition whitespace-nowrap {{ !in_array(request('tab'), ['ticker', 'promo', 'tri_promo', 'remini']) ? 'border-brand-600 text-brand-600 dark:text-brand-400 dark:border-brand-400 bg-brand-50/50 dark:bg-brand-950/30' : 'border-transparent text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-images text-sm"></i>
            <span>Hero Carousel Slides</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ !in_array(request('tab'), ['ticker', 'promo', 'tri_promo', 'remini']) ? 'bg-brand-100 text-brand-700 dark:bg-brand-900/60 dark:text-brand-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400' }}">{{ $totalBanners }}</span>
        </a>

        <a href="{{ route('admin.appearance.banners.index', ['tab' => 'ticker']) }}"
           class="inline-flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold border-b-2 transition whitespace-nowrap {{ request('tab') === 'ticker' ? 'border-brand-600 text-brand-600 dark:text-brand-400 dark:border-brand-400 bg-brand-50/50 dark:bg-brand-950/30' : 'border-transparent text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-bullhorn text-sm"></i>
            <span>Scrolling Trust Ticker (Under Banner)</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ request('tab') === 'ticker' ? 'bg-brand-100 text-brand-700 dark:bg-brand-900/60 dark:text-brand-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400' }}">{{ count(trust_features()) }} Badges</span>
        </a>

        <a href="{{ route('admin.appearance.banners.index', ['tab' => 'promo']) }}"
           class="inline-flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold border-b-2 transition whitespace-nowrap {{ request('tab') === 'promo' ? 'border-brand-600 text-brand-600 dark:text-brand-400 dark:border-brand-400 bg-brand-50/50 dark:bg-brand-950/30' : 'border-transparent text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-rectangle-ad text-sm"></i>
            <span>Promotional Showcase Banner</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ request('tab') === 'promo' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950/70 dark:text-amber-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400' }}">
                {{ setting('promo_banner_enabled', '1') === '1' ? 'Active' : 'Disabled' }}
            </span>
        </a>

        <a href="{{ route('admin.appearance.banners.index', ['tab' => 'tri_promo']) }}"
           class="inline-flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold border-b-2 transition whitespace-nowrap {{ request('tab') === 'tri_promo' ? 'border-brand-600 text-brand-600 dark:text-brand-400 dark:border-brand-400 bg-brand-50/50 dark:bg-brand-950/30' : 'border-transparent text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-table-columns text-sm"></i>
            <span>3-Card Feature Highlights</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ request('tab') === 'tri_promo' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/70 dark:text-emerald-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400' }}">
                {{ setting('tri_promo_enabled', '1') === '1' ? 'Active' : 'Disabled' }}
            </span>
        </a>

        <a href="{{ route('admin.appearance.banners.index', ['tab' => 'remini']) }}"
           class="inline-flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold border-b-2 transition whitespace-nowrap {{ request('tab') === 'remini' ? 'border-brand-600 text-brand-600 dark:text-brand-400 dark:border-brand-400 bg-brand-50/50 dark:bg-brand-950/30' : 'border-transparent text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-wand-magic-sparkles text-sm"></i>
            <span>AI Tool Banner (Remini)</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ request('tab') === 'remini' ? 'bg-rose-100 text-rose-800 dark:bg-rose-950/70 dark:text-rose-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400' }}">
                {{ setting('remini_banner_enabled', '1') === '1' ? 'Active' : 'Disabled' }}
            </span>
        </a>
    </div>

    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 flex items-center gap-3 text-emerald-800 dark:text-emerald-300 text-sm shadow-sm">
            <i class="fa-solid fa-circle-check text-lg shrink-0 text-emerald-600 dark:text-emerald-400"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm shadow-sm space-y-1">
            <div class="flex items-center gap-2 font-bold text-rose-700 dark:text-rose-400">
                <i class="fa-solid fa-triangle-exclamation text-base"></i>
                <span>Please fix the errors below:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5 text-rose-600 dark:text-rose-300 pl-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(request('tab') === 'ticker')
        <!-- =========================================================================
             TAB 2: SCROLLING TRUST FEATURES TICKER MANAGEMENT
             ========================================================================= -->
        @php
            $tickerEnabled = setting('trust_features_enabled', '1') === '1';
            $tickerSpeed = (int) setting('trust_features_speed', 28);
            $tickerItems = trust_features();
        @endphp

        <!-- Metric Overview Cards for Ticker -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg {{ $tickerEnabled ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400' : 'bg-slate-100 dark:bg-slate-700 text-slate-500' }} flex items-center justify-center shrink-0">
                    <i class="fa-solid {{ $tickerEnabled ? 'fa-circle-check' : 'fa-circle-pause' }} text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Ticker Status</p>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-0.5">
                        {{ $tickerEnabled ? 'Live on Store' : 'Disabled' }}
                    </h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-stopwatch text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Scroll Speed</p>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-0.5">{{ $tickerSpeed }}s Duration</h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-950/50 text-brand-600 dark:text-brand-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-certificate text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Active Badges</p>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-0.5">{{ count($tickerItems) }} Items</h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-arrows-left-right text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Animation Type</p>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-0.5">Continuous Loop</h3>
                </div>
            </div>
        </div>

        <!-- Interactive Live Preview Card -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs p-5 space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-eye text-brand-600 dark:text-brand-400 text-xs"></i>
                        <span>Live Marquee Preview</span>
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">This preview reflects how the scrolling pills appear right beneath the hero banner on your live website.</p>
                </div>
                <span class="text-[11px] font-semibold text-slate-400 bg-slate-100 dark:bg-slate-700 px-2.5 py-1 rounded-md">Hover to pause</span>
            </div>

            <div class="border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-100/70 dark:bg-slate-900/60 p-4 overflow-hidden relative">
                <div class="trust-features-bar bg-white dark:bg-slate-800 shadow-xs rounded-xl py-3 px-0 overflow-hidden relative" style="margin-top: 0;">
                    <div id="previewMarqueeTrack" class="trust-marquee-track" style="animation: trustMarqueeScroll {{ $tickerSpeed }}s linear infinite;">
                        <!-- Group 1 -->
                        <div id="previewGroup1" class="trust-marquee-group">
                            @foreach($tickerItems as $item)
                                <div class="trust-feature-item">
                                    <span class="trust-icon-box">
                                        @if(str_starts_with(trim($item['icon'] ?? ''), '<svg'))
                                            {!! $item['icon'] !!}
                                        @else
                                            <i class="{{ $item['icon'] ?: 'fa-solid fa-circle-check' }}"></i>
                                        @endif
                                    </span>
                                    <span class="trust-text">{{ $item['text'] }}</span>
                                </div>
                            @endforeach
                        </div>
                        <!-- Group 2 (Seamless loop duplicate) -->
                        <div id="previewGroup2" class="trust-marquee-group" aria-hidden="true">
                            @foreach($tickerItems as $item)
                                <div class="trust-feature-item">
                                    <span class="trust-icon-box">
                                        @if(str_starts_with(trim($item['icon'] ?? ''), '<svg'))
                                            {!! $item['icon'] !!}
                                        @else
                                            <i class="{{ $item['icon'] ?: 'fa-solid fa-circle-check' }}"></i>
                                        @endif
                                    </span>
                                    <span class="trust-text">{{ $item['text'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ticker Settings Form -->
        <form id="trustFeaturesForm" action="{{ route('admin.appearance.banners.trust-features.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- General Display Controls Card -->
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs p-5 space-y-5">
                <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Marquee Display Settings</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Control visibility and scroll speed of the trust badges bar.</p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center">
                        <i class="fa-solid fa-sliders text-xs"></i>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Enable / Disable Switch -->
                    <div class="flex items-center justify-between p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/40">
                        <div>
                            <span class="block text-xs font-bold text-slate-800 dark:text-slate-200">Show Trust Ticker Bar</span>
                            <span class="block text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Display scrolling trust badges directly under hero banner carousel</span>
                        </div>
                        <input type="checkbox"
                               name="trust_features_enabled"
                               value="1"
                               class="rounded border-slate-300 text-brand-600 focus:ring-brand-500 w-4 h-4 cursor-pointer"
                               {{ $tickerEnabled ? 'checked' : '' }}>
                    </div>

                    <!-- Scroll Speed Control -->
                    <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/40">
                        <label for="trust_features_speed" class="block text-xs font-bold text-slate-800 dark:text-slate-200 mb-1">
                            Marquee Scroll Duration (Seconds)
                        </label>
                        <div class="flex items-center gap-3">
                            <select id="trust_features_speed"
                                    name="trust_features_speed"
                                    class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">
                                <option value="18" {{ $tickerSpeed == 18 ? 'selected' : '' }}>18 Seconds (Fast)</option>
                                <option value="24" {{ $tickerSpeed == 24 ? 'selected' : '' }}>24 Seconds (Snappy)</option>
                                <option value="28" {{ $tickerSpeed == 28 ? 'selected' : '' }}>28 Seconds (Standard Default)</option>
                                <option value="36" {{ $tickerSpeed == 36 ? 'selected' : '' }}>36 Seconds (Slow)</option>
                                <option value="45" {{ $tickerSpeed == 45 ? 'selected' : '' }}>45 Seconds (Very Relaxed)</option>
                            </select>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Lower duration scrolls faster; higher duration scrolls slower.</p>
                    </div>
                </div>
            </div>

            <!-- Dynamic Trust Badges List Card -->
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs p-5 space-y-5">
                <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Trust Feature Badges</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Customize icons, titles, and optional links for each pill in the scrolling bar.</p>
                    </div>

                    <button type="button"
                            id="addTrustFeatureBtn"
                            class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 text-xs font-bold transition cursor-pointer">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Add New Badge</span>
                    </button>
                </div>

                <!-- Features Container -->
                <div id="trustFeaturesContainer" class="space-y-3">
                    @forelse($tickerItems as $index => $item)
                        <div class="js-feature-row bg-slate-50/70 dark:bg-slate-900/40 p-4 rounded-xl border border-slate-200 dark:border-slate-700 flex flex-col md:flex-row md:items-center gap-3 transition">
                            
                            <!-- Index Badge -->
                            <div class="shrink-0 flex items-center gap-2">
                                <span class="js-row-index w-7 h-7 rounded-lg bg-brand-100 dark:bg-brand-950/80 text-brand-700 dark:text-brand-300 font-bold text-xs flex items-center justify-center">
                                    #{{ $index + 1 }}
                                </span>
                            </div>

                            <!-- Icon Visual Preview & Picker -->
                            <div class="shrink-0 flex items-center gap-2">
                                <div class="js-icon-preview-box w-10 h-10 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-brand-600 dark:text-brand-400 flex items-center justify-center text-sm shadow-2xs">
                                    @if(str_starts_with(trim($item['icon'] ?? ''), '<svg'))
                                        {!! $item['icon'] !!}
                                    @else
                                        <i class="{{ $item['icon'] ?: 'fa-solid fa-circle-check' }}"></i>
                                    @endif
                                </div>
                            </div>

                            <!-- Icon Selection Input & Preset Buttons -->
                            <div class="w-full md:w-56 shrink-0 space-y-1">
                                <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400">
                                    Icon (FontAwesome Class)
                                </label>
                                <input type="text"
                                       name="items[{{ $index }}][icon]"
                                       value="{{ $item['icon'] ?? 'fa-solid fa-circle-check' }}"
                                       class="js-icon-input w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500"
                                       placeholder="e.g. fa-solid fa-shield-halved">
                            </div>

                            <!-- Text Input -->
                            <div class="flex-1 space-y-1">
                                <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400">
                                    Badge Headline / Text <span class="text-rose-500">*</span>
                                </label>
                                <input type="text"
                                       name="items[{{ $index }}][text]"
                                       value="{{ $item['text'] ?? '' }}"
                                       required
                                       class="js-text-input w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500"
                                       placeholder="e.g. Authentic & Genuine Product.">
                            </div>

                            <!-- Optional Link URL -->
                            <div class="w-full md:w-48 shrink-0 space-y-1">
                                <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400">
                                    Target Link (Optional)
                                </label>
                                <input type="text"
                                       name="items[{{ $index }}][link]"
                                       value="{{ $item['link'] ?? '' }}"
                                       class="js-link-input w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500"
                                       placeholder="e.g. /policies or #">
                            </div>

                            <!-- Action Remove Button -->
                            <div class="shrink-0 flex items-center pt-5 md:pt-0">
                                <button type="button"
                                        class="js-remove-feature-row p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition cursor-pointer"
                                        title="Delete Badge">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </button>
                            </div>

                        </div>
                    @empty
                        <div id="noFeaturesNotice" class="p-8 text-center text-slate-400 dark:text-slate-500 text-xs">
                            No trust badges configured yet. Click "+ Add New Badge" to add one.
                        </div>
                    @endforelse
                </div>

                <!-- Popular Icon Presets Helper Box -->
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700/80 space-y-2">
                    <span class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                        <i class="fa-solid fa-lightbulb text-amber-500 mr-1"></i> Popular Icon Quick Presets:
                    </span>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <button type="button" class="js-preset-pill inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-brand-500 hover:text-brand-600 transition cursor-pointer" data-icon="fa-solid fa-shield-halved">
                            <i class="fa-solid fa-shield-halved text-brand-600"></i> Shield Check
                        </button>
                        <button type="button" class="js-preset-pill inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-brand-500 hover:text-brand-600 transition cursor-pointer" data-icon="fa-solid fa-headset">
                            <i class="fa-solid fa-headset text-brand-600"></i> 24/7 Support
                        </button>
                        <button type="button" class="js-preset-pill inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-brand-500 hover:text-brand-600 transition cursor-pointer" data-icon="fa-solid fa-tags">
                            <i class="fa-solid fa-tags text-brand-600"></i> Low Prices
                        </button>
                        <button type="button" class="js-preset-pill inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-brand-500 hover:text-brand-600 transition cursor-pointer" data-icon="fa-solid fa-credit-card">
                            <i class="fa-solid fa-credit-card text-brand-600"></i> Safe Payment
                        </button>
                        <button type="button" class="js-preset-pill inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-brand-500 hover:text-brand-600 transition cursor-pointer" data-icon="fa-solid fa-truck-fast">
                            <i class="fa-solid fa-truck-fast text-brand-600"></i> Fast Delivery
                        </button>
                        <button type="button" class="js-preset-pill inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-brand-500 hover:text-brand-600 transition cursor-pointer" data-icon="fa-solid fa-rotate-left">
                            <i class="fa-solid fa-rotate-left text-brand-600"></i> Easy Return
                        </button>
                        <button type="button" class="js-preset-pill inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-brand-500 hover:text-brand-600 transition cursor-pointer" data-icon="fa-solid fa-lock">
                            <i class="fa-solid fa-lock text-brand-600"></i> SSL Secure
                        </button>
                        <button type="button" class="js-preset-pill inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-brand-500 hover:text-brand-600 transition cursor-pointer" data-icon="fa-solid fa-award">
                            <i class="fa-solid fa-award text-brand-600"></i> Guaranteed
                        </button>
                        <button type="button" class="js-preset-pill inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-brand-500 hover:text-brand-600 transition cursor-pointer" data-icon="fa-solid fa-bolt">
                            <i class="fa-solid fa-bolt text-brand-600"></i> Instant Keys
                        </button>
                        <button type="button" class="js-preset-pill inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-brand-500 hover:text-brand-600 transition cursor-pointer" data-icon="fa-solid fa-gift">
                            <i class="fa-solid fa-gift text-brand-600"></i> Bonus Reward
                        </button>
                    </div>
                </div>

                <!-- Bottom Save Button -->
                <div class="pt-3 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-sm transition cursor-pointer">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>Save Scrolling Trust Marquee</span>
                    </button>
                </div>
            </div>
        </form>

    @elseif(request('tab') === 'promo')
        <!-- =========================================================================
             TAB 3: PROMOTIONAL SHOWCASE BANNER
             ========================================================================= -->
        @php
            $promoEnabled = setting('promo_banner_enabled', '1') === '1';
            $promoBadge = setting('promo_badge', 'WF-1000XM3');
            $promoTitle = setting('promo_title', 'শব্দ যেখানে নিখুঁত, সুর সেখানে জীবন্ত!');
            $promoSubtitle = setting('promo_subtitle', '—হারিয়ে যান এক অন্যরকম অনুভূতির দুনিয়ায়।');
            $promoBtnText = setting('promo_button_text', 'Get Now');
            $promoBtnLink = setting('promo_button_link', '#product-wf1000xm3');

            $promoVisual1Url = setting_url('promo_visual_1', 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=400&q=80');
            $promoVisual2Url = setting_url('promo_visual_2', 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=400&q=80');
            $promoBackdropUrl = setting_url('promo_backdrop', 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=1200&q=80');

            $hasCustomVisual1 = !empty(setting('promo_visual_1'));
            $hasCustomVisual2 = !empty(setting('promo_visual_2'));
            $hasCustomBackdrop = !empty(setting('promo_backdrop'));
        @endphp

        <!-- Metric Overview Cards for Promo Banner -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg {{ $promoEnabled ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400' : 'bg-slate-100 dark:bg-slate-700 text-slate-500' }} flex items-center justify-center shrink-0">
                    <i class="fa-solid {{ $promoEnabled ? 'fa-circle-check' : 'fa-circle-pause' }} text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Banner Status</p>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-0.5">
                        {{ $promoEnabled ? 'Active & Visible' : 'Hidden on Store' }}
                    </h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-tag text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Badge Tag</p>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-0.5 truncate max-w-[150px]" title="{{ $promoBadge }}">
                        {{ $promoBadge ?: 'None' }}
                    </h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-layer-group text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Floating Visuals</p>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-0.5">2 Layer Elements</h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-location-arrow text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">CTA Button</p>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-0.5 truncate max-w-[150px]">
                        {{ $promoBtnText ?: 'Default' }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- Promo Banner Configuration Form -->
        <form id="promoBannerForm" action="{{ route('admin.appearance.banners.promo.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Left 7 Cols: Text Content & Links -->
                <div class="lg:col-span-7 space-y-6">

                    <!-- General Activation & Texts Card -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs p-5 space-y-5">
                        <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3 flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Promotion Information</h2>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Customize the typography, tags, and promotional pitch.</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400">
                                Middle Homepage Section
                            </span>
                        </div>

                        <!-- Visibility Switch -->
                        <div class="flex items-center justify-between p-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/40">
                            <div>
                                <span class="block text-xs font-bold text-slate-800 dark:text-slate-200">Enable Promo Showcase Banner</span>
                                <span class="block text-[11px] text-slate-500 dark:text-slate-400">Display this promotional banner section between product categories on the homepage</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox"
                                       name="promo_banner_enabled"
                                       value="1"
                                       class="sr-only peer"
                                       {{ $promoEnabled ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-600"></div>
                            </label>
                        </div>

                        <!-- Badge Input -->
                        <div class="space-y-1.5">
                            <label for="promo_badge" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                Product / Highlight Badge Text
                            </label>
                            <input type="text"
                                   id="promo_badge"
                                   name="promo_badge"
                                   value="{{ old('promo_badge', $promoBadge) }}"
                                   placeholder="e.g. WF-1000XM3, HOT DEAL, SPECIAL OFFER"
                                   class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">
                            <p class="text-[11px] text-slate-400 dark:text-slate-500">Small stylish pill shown at the top of the text block.</p>
                        </div>

                        <!-- Title Input -->
                        <div class="space-y-1.5">
                            <label for="promo_title" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                Main Headline / Bengali or English Title <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   id="promo_title"
                                   name="promo_title"
                                   value="{{ old('promo_title', $promoTitle) }}"
                                   required
                                   placeholder="e.g. শব্দ যেখানে নিখুঁত, সুর সেখানে জীবন্ত!"
                                   class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">
                        </div>

                        <!-- Subtitle Input -->
                        <div class="space-y-1.5">
                            <label for="promo_subtitle" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                Subtitle / Tagline Description
                            </label>
                            <textarea id="promo_subtitle"
                                      name="promo_subtitle"
                                      rows="2"
                                      placeholder="e.g. —হারিয়ে যান এক অন্যরকম অনুভূতির দুনিয়ায়।"
                                      class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">{{ old('promo_subtitle', $promoSubtitle) }}</textarea>
                        </div>

                        <!-- Button Action Details -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100 dark:border-slate-700/60">
                            <div class="space-y-1.5">
                                <label for="promo_button_text" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                    CTA Button Label
                                </label>
                                <input type="text"
                                       id="promo_button_text"
                                       name="promo_button_text"
                                       value="{{ old('promo_button_text', $promoBtnText) }}"
                                       placeholder="e.g. Get Now, Order Now, View Details"
                                       class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">
                            </div>

                            <div class="space-y-1.5">
                                <label for="promo_button_link" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                    CTA Target URL
                                </label>
                                <input type="text"
                                       id="promo_button_link"
                                       name="promo_button_link"
                                       value="{{ old('promo_button_link', $promoBtnLink) }}"
                                       placeholder="e.g. /product/sony-wf-1000xm3 or #link"
                                       class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">
                            </div>
                        </div>

                    </div>

                    <!-- Live Visual Preview of Banner -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                <i class="fa-solid fa-eye text-brand-600"></i>
                                <span>Preview Approximation</span>
                            </h3>
                            <span class="text-[11px] text-slate-400">Renders as shown on homepage</span>
                        </div>

                        <!-- Card Mockup Container -->
                        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#0d1624] via-[#102238] to-[#122b4a] p-6 text-white min-h-[220px] flex items-center justify-between border border-slate-700/60 shadow-lg">
                            <!-- Background backdrop preview -->
                            <div class="absolute inset-0 opacity-25 bg-cover bg-center" style="background-image: url('{{ $promoBackdropUrl }}');"></div>
                            <div class="absolute inset-0 bg-gradient-to-r from-[#0d1624] via-[#0d1624]/90 to-transparent"></div>

                            <!-- Content preview -->
                            <div class="relative z-10 max-w-sm space-y-2.5">
                                <span id="previewPromoBadge" class="inline-block px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-white/10 border border-white/20 text-cyan-300 backdrop-blur-sm">
                                    {{ $promoBadge ?: 'WF-1000XM3' }}
                                </span>
                                <h3 id="previewPromoTitle" class="text-lg sm:text-xl font-black text-white leading-snug drop-shadow-sm">
                                    {{ $promoTitle ?: 'শব্দ যেখানে নিখুঁত, সুর সেখানে জীবন্ত!' }}
                                </h3>
                                <p id="previewPromoSubtitle" class="text-xs text-slate-300 leading-relaxed drop-shadow">
                                    {{ $promoSubtitle ?: '—হারিয়ে যান এক অন্যরকম অনুভূতির দুনিয়ায়।' }}
                                </p>
                                <div class="pt-1">
                                    <span id="previewPromoBtn" class="inline-block px-4 py-1.5 rounded-lg text-xs font-bold text-white bg-gradient-to-r from-cyan-500 to-blue-600 shadow-md">
                                        {{ $promoBtnText ?: 'Get Now' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Floating product earbuds preview -->
                            <div class="relative z-10 hidden sm:flex flex-col items-center gap-2 w-32 shrink-0">
                                <div class="w-20 h-20 rounded-full bg-black/40 border border-white/10 p-2 shadow-2xl backdrop-blur-xs flex items-center justify-center transform -rotate-12">
                                    <img src="{{ $promoVisual1Url }}" alt="Visual 1" class="w-full h-full object-contain">
                                </div>
                                <div class="w-16 h-16 rounded-full bg-black/40 border border-white/10 p-2 shadow-2xl backdrop-blur-xs flex items-center justify-center transform rotate-12 -mt-4 ml-6">
                                    <img src="{{ $promoVisual2Url }}" alt="Visual 2" class="w-full h-full object-contain">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right 5 Cols: Images & Visual Uploads -->
                <div class="lg:col-span-5 space-y-6">

                    <!-- Visual 1: Floating Product Top Image -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs p-5 space-y-3.5">
                        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700/60 pb-2.5">
                            <div>
                                <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Floating Visual 1 (Top Product/Earbud)</h3>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">Transparent PNG recommended (e.g. 400 &times; 400 px).</p>
                            </div>
                            <span class="w-6 h-6 rounded-full bg-cyan-50 text-cyan-600 dark:bg-cyan-950/60 dark:text-cyan-400 flex items-center justify-center text-xs font-bold">1</span>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-24 h-24 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-900/90 p-2 flex items-center justify-center shrink-0 overflow-hidden shadow-xs relative group">
                                <img src="{{ $promoVisual1Url }}" alt="Visual 1" class="w-full h-full object-contain transition group-hover:scale-105">
                                @if($hasCustomVisual1)
                                    <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-600 text-white">Custom</span>
                                @else
                                    <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-700 text-slate-300">Default</span>
                                @endif
                            </div>

                            <div class="flex-1 space-y-2">
                                <input type="file"
                                       name="promo_visual_1"
                                       accept="image/png,image/jpeg,image/webp,image/svg+xml"
                                       class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 dark:file:bg-brand-950/60 dark:file:text-brand-300 hover:file:bg-brand-100 cursor-pointer">

                                @if($hasCustomVisual1)
                                    <label class="inline-flex items-center gap-1.5 text-[11px] text-rose-600 dark:text-rose-400 cursor-pointer">
                                        <input type="checkbox" name="remove_visual_1" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                        <span>Reset to default graphic</span>
                                    </label>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Visual 2: Floating Product Bottom Image -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs p-5 space-y-3.5">
                        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700/60 pb-2.5">
                            <div>
                                <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Floating Visual 2 (Bottom Product/Earbud)</h3>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">Transparent PNG recommended (e.g. 400 &times; 400 px).</p>
                            </div>
                            <span class="w-6 h-6 rounded-full bg-cyan-50 text-cyan-600 dark:bg-cyan-950/60 dark:text-cyan-400 flex items-center justify-center text-xs font-bold">2</span>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-24 h-24 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-900/90 p-2 flex items-center justify-center shrink-0 overflow-hidden shadow-xs relative group">
                                <img src="{{ $promoVisual2Url }}" alt="Visual 2" class="w-full h-full object-contain transition group-hover:scale-105">
                                @if($hasCustomVisual2)
                                    <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-600 text-white">Custom</span>
                                @else
                                    <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-700 text-slate-300">Default</span>
                                @endif
                            </div>

                            <div class="flex-1 space-y-2">
                                <input type="file"
                                       name="promo_visual_2"
                                       accept="image/png,image/jpeg,image/webp,image/svg+xml"
                                       class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 dark:file:bg-brand-950/60 dark:file:text-brand-300 hover:file:bg-brand-100 cursor-pointer">

                                @if($hasCustomVisual2)
                                    <label class="inline-flex items-center gap-1.5 text-[11px] text-rose-600 dark:text-rose-400 cursor-pointer">
                                        <input type="checkbox" name="remove_visual_2" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                        <span>Reset to default graphic</span>
                                    </label>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Backdrop Lifestyle Image -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs p-5 space-y-3.5">
                        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700/60 pb-2.5">
                            <div>
                                <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Lifestyle / Background Backdrop Image</h3>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">Atmospheric photo displayed behind the banner (e.g. 1200 &times; 600 px).</p>
                            </div>
                            <span class="w-6 h-6 rounded-full bg-purple-50 text-purple-600 dark:bg-purple-950/60 dark:text-purple-400 flex items-center justify-center text-xs font-bold">BG</span>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-32 h-20 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-900 p-1 shrink-0 overflow-hidden shadow-xs relative group">
                                <img src="{{ $promoBackdropUrl }}" alt="Backdrop" class="w-full h-full object-cover rounded-lg transition group-hover:scale-105">
                                @if($hasCustomBackdrop)
                                    <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-600 text-white">Custom</span>
                                @else
                                    <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-700 text-slate-300">Default</span>
                                @endif
                            </div>

                            <div class="flex-1 space-y-2">
                                <input type="file"
                                       name="promo_backdrop"
                                       accept="image/png,image/jpeg,image/webp,image/svg+xml"
                                       class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 dark:file:bg-brand-950/60 dark:file:text-brand-300 hover:file:bg-brand-100 cursor-pointer">

                                @if($hasCustomBackdrop)
                                    <label class="inline-flex items-center gap-1.5 text-[11px] text-rose-600 dark:text-rose-400 cursor-pointer">
                                        <input type="checkbox" name="remove_backdrop" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                        <span>Reset to default graphic</span>
                                    </label>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Save Actions -->
                    <div class="pt-2">
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-500/20 transition cursor-pointer">
                            <i class="fa-solid fa-floppy-disk text-sm"></i>
                            <span>Save Promotional Banner Changes</span>
                        </button>
                    </div>

                </div>

            </div>
        </form>

    @elseif(request('tab') === 'tri_promo')
        <!-- =========================================================================
             TAB 4: 3-CARD FEATURE HIGHLIGHTS (Smartwatch, Earbuds, Mic)
             ========================================================================= -->
        @php
            $triPromoEnabled = setting('tri_promo_enabled', '1') === '1';
            $triCardsData = tri_promo_cards();
        @endphp

        <!-- Metric Overview Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg {{ $triPromoEnabled ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400' : 'bg-slate-100 dark:bg-slate-700 text-slate-500' }} flex items-center justify-center shrink-0">
                    <i class="fa-solid {{ $triPromoEnabled ? 'fa-circle-check' : 'fa-circle-pause' }} text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Section Status</p>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-0.5">
                        {{ $triPromoEnabled ? 'Active & Live' : 'Hidden on Store' }}
                    </h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-table-columns text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total Promo Cards</p>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-0.5">3 Columns</h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg bg-slate-900 text-white flex items-center justify-center shrink-0 shadow-xs">
                    <i class="fa-solid fa-moon text-lg text-amber-400"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Center Card Theme</p>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-0.5 uppercase tracking-wider text-xs">
                        {{ $triCardsData[2]['theme'] ?? 'dark' }} Theme
                    </h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-wand-magic-sparkles text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Custom Graphics</p>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-0.5">
                        {{ ($triCardsData[1]['has_custom_img'] ? 1 : 0) + ($triCardsData[2]['has_custom_img'] ? 1 : 0) + ($triCardsData[3]['has_custom_img'] ? 1 : 0) }} / 3 Uploaded
                    </h3>
                </div>
            </div>
        </div>

        <!-- Configuration Form -->
        <form id="triPromoForm" action="{{ route('admin.appearance.banners.tri-promo.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section Global Toggle Card -->
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Enable 3-Card Highlights Promo Section</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Show or hide the 3 highlight cards (Smartwatch, Earbuds, Mic) on the homepage.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox"
                           name="tri_promo_enabled"
                           value="1"
                           class="sr-only peer"
                           {{ $triPromoEnabled ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-600"></div>
                </label>
            </div>

            <!-- 3 Cards Configuration Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                @for($idx = 1; $idx <= 3; $idx++)
                    @php
                        $c = $triCardsData[$idx];
                        $badgeTitle = $idx === 1 ? 'Left Card (Default: Smartwatch)' : ($idx === 2 ? 'Center Card (Default: Earbuds)' : 'Right Card (Default: Microphone)');
                    @endphp
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs p-5 space-y-4 flex flex-col justify-between">
                        <div class="space-y-4">
                            <!-- Card Header -->
                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700/60 pb-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-brand-100 text-brand-700 dark:bg-brand-950 dark:text-brand-300 text-xs font-black flex items-center justify-center">
                                        {{ $idx }}
                                    </span>
                                    <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">
                                        {{ $badgeTitle }}
                                    </h3>
                                </div>
                                <span class="text-[11px] font-semibold text-slate-400">Card #{{ $idx }}</span>
                            </div>

                            <!-- Card Theme Switcher -->
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                    Visual Theme / Background Style
                                </label>
                                <select name="tri_promo_{{ $idx }}_theme" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500 font-semibold">
                                    <option value="light" {{ old("tri_promo_{$idx}_theme", $c['theme']) === 'light' ? 'selected' : '' }}>Light Card (White with dark text)</option>
                                    <option value="dark" {{ old("tri_promo_{$idx}_theme", $c['theme']) === 'dark' ? 'selected' : '' }}>Dark Card (Black with white text)</option>
                                </select>
                            </div>

                            <!-- Image Preview & File Upload -->
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                    Product Graphics Image
                                </label>
                                <div class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/40">
                                    <div class="w-20 h-20 rounded-lg {{ $c['theme'] === 'dark' ? 'bg-black border-slate-800' : 'bg-white border-slate-200' }} border p-1.5 flex items-center justify-center shrink-0 overflow-hidden shadow-xs relative">
                                        <img src="{{ $c['image_url'] }}" alt="{{ $c['title'] }}" class="max-w-full max-h-full object-contain">
                                        @if($c['has_custom_img'])
                                            <span class="absolute bottom-1 right-1 px-1 py-0.5 rounded text-[8px] font-bold bg-emerald-600 text-white">Custom</span>
                                        @else
                                            <span class="absolute bottom-1 right-1 px-1 py-0.5 rounded text-[8px] font-bold bg-slate-700 text-slate-300">Default</span>
                                        @endif
                                    </div>
                                    <div class="flex-1 space-y-1.5">
                                        <input type="file"
                                               name="tri_promo_{{ $idx }}_img"
                                               accept="image/png,image/jpeg,image/webp,image/svg+xml"
                                               class="block w-full text-[11px] text-slate-500 dark:text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[11px] file:font-semibold file:bg-brand-50 file:text-brand-700 dark:file:bg-brand-950/60 dark:file:text-brand-300 hover:file:bg-brand-100 cursor-pointer">
                                        <p class="text-[10px] text-slate-400">Transparent PNG recommended (approx. 400 &times; 400 px).</p>
                                        @if($c['has_custom_img'])
                                            <label class="inline-flex items-center gap-1.5 text-[11px] text-rose-600 dark:text-rose-400 cursor-pointer pt-0.5">
                                                <input type="checkbox" name="remove_card_{{ $idx }}_img" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                                <span>Reset to default graphic</span>
                                            </label>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Card Title -->
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                    Headline Title <span class="text-rose-500">*</span>
                                </label>
                                <input type="text"
                                       name="tri_promo_{{ $idx }}_title"
                                       value="{{ old("tri_promo_{$idx}_title", $c['title']) }}"
                                       required
                                       class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">
                            </div>

                            <!-- Card Description -->
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                    Description Text
                                </label>
                                <textarea name="tri_promo_{{ $idx }}_desc"
                                          rows="3"
                                          class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">{{ old("tri_promo_{$idx}_desc", $c['desc']) }}</textarea>
                            </div>

                            <!-- Button Label & Target URL -->
                            <div class="grid grid-cols-2 gap-2 pt-1">
                                <div class="space-y-1">
                                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300">
                                        Button Text
                                    </label>
                                    <input type="text"
                                           name="tri_promo_{{ $idx }}_button_text"
                                           value="{{ old("tri_promo_{$idx}_button_text", $c['button_text']) }}"
                                           placeholder="Shop Now"
                                           class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300">
                                        Target URL
                                    </label>
                                    <input type="text"
                                           name="tri_promo_{{ $idx }}_button_link"
                                           value="{{ old("tri_promo_{$idx}_button_link", $c['button_link']) }}"
                                           placeholder="/category/..."
                                           class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">
                                </div>
                            </div>
                        </div>

                        <!-- Card Mini Mockup Footer -->
                        <div class="pt-3 border-t border-slate-100 dark:border-slate-700/60 text-center">
                            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $c['theme'] === 'dark' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700' }}">
                                Preview: {{ $c['theme'] === 'dark' ? 'Dark Accent Style' : 'Clean Light Style' }}
                            </span>
                        </div>
                    </div>
                @endfor
            </div>

            <!-- Save Form Submit -->
            <div class="flex justify-end pt-2">
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-500/20 transition cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-sm"></i>
                    <span>Save 3-Card Highlights Changes</span>
                </button>
            </div>
        </form>

    @elseif(request('tab') === 'dual_promo')
        <!-- =========================================================================
             TAB: DUAL PROMO BANNERS (Coupon & Discount Announcements)
             ========================================================================= -->
        @php
            $dualEnabled = setting('dual_promo_enabled', '1') === '1';
            $dualBannersData = dual_promo_banners();
        @endphp

        <!-- Metric Overview Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg {{ $dualEnabled ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400' : 'bg-slate-100 dark:bg-slate-700 text-slate-500' }} flex items-center justify-center shrink-0">
                    <i class="fa-solid {{ $dualEnabled ? 'fa-circle-check' : 'fa-circle-pause' }} text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Banners Status</p>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-0.5">
                        {{ $dualEnabled ? 'Active & Live' : 'Hidden on Store' }}
                    </h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-ticket text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Card 1 Coupon</p>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-0.5 font-mono text-sm">
                        {{ $dualBannersData[1]['coupon_code'] ?: 'None' }}
                    </h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg bg-pink-50 dark:bg-pink-950/50 text-pink-600 dark:text-pink-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-bullhorn text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Card 2 Promo</p>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-0.5 truncate max-w-[150px] text-xs">
                        {{ $dualBannersData[2]['title'] }}
                    </h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-palette text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Gradients</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="w-4 h-4 rounded-full border border-black/20" style="background: linear-gradient(135deg, {{ $dualBannersData[1]['bg_gradient_start'] }}, {{ $dualBannersData[1]['bg_gradient_end'] }});" title="Card 1 Gradient"></span>
                        <span class="w-4 h-4 rounded-full border border-black/20" style="background: linear-gradient(135deg, {{ $dualBannersData[2]['bg_gradient_start'] }}, {{ $dualBannersData[2]['bg_gradient_end'] }});" title="Card 2 Gradient"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuration Form -->
        <form id="dualPromoForm" action="{{ route('admin.appearance.banners.dual-promo.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Global Section Toggle -->
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Enable Dual Coupon & Deals Banners Section</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Show or hide the 2-column coupon & campaign announcement banners on the homepage.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox"
                           name="dual_promo_enabled"
                           value="1"
                           class="sr-only peer"
                           {{ $dualEnabled ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-600"></div>
                </label>
            </div>

            <!-- 2 Banners Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @for($bIdx = 1; $bIdx <= 2; $bIdx++)
                    @php
                        $b = $dualBannersData[$bIdx];
                        $badgeLabel = $bIdx === 1 ? 'Left Banner (Default: Dual Device Mounting + Coupon)' : 'Right Banner (Default: Turbo Fan Campaign)';
                    @endphp
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs p-5 space-y-5 flex flex-col justify-between">
                        <div class="space-y-4">
                            <!-- Card Header -->
                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700/60 pb-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300 text-xs font-black flex items-center justify-center">
                                        {{ $bIdx }}
                                    </span>
                                    <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">
                                        {{ $badgeLabel }}
                                    </h3>
                                </div>
                                <span class="text-[11px] font-semibold text-slate-400">Card #{{ $bIdx }}</span>
                            </div>

                            <!-- Title -->
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                    Headline Title <span class="text-rose-500">*</span>
                                </label>
                                <textarea name="dual_promo_{{ $bIdx }}_title"
                                          rows="2"
                                          required
                                          class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500 font-semibold">{{ old("dual_promo_{$bIdx}_title", $b['title']) }}</textarea>
                                <p class="text-[10px] text-slate-400">Line breaks will be preserved.</p>
                            </div>

                            <!-- Coupon Offer Details -->
                            <div class="p-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/40 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Coupon / Offer Badge (Optional)</span>
                                    <span class="text-[10px] text-slate-400">Highlights promo code</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="space-y-1">
                                        <label class="block text-[11px] font-medium text-slate-600 dark:text-slate-400">
                                            Offer Text Label
                                        </label>
                                        <input type="text"
                                               name="dual_promo_{{ $bIdx }}_offer_label"
                                               value="{{ old("dual_promo_{$bIdx}_offer_label", $b['offer_label']) }}"
                                               placeholder="e.g. Get 10% OFF; Promo code:"
                                               class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">
                                    </div>

                                    <div class="space-y-1">
                                        <label class="block text-[11px] font-medium text-slate-600 dark:text-slate-400">
                                            Coupon Code Pill
                                        </label>
                                        <input type="text"
                                               name="dual_promo_{{ $bIdx }}_coupon_code"
                                               value="{{ old("dual_promo_{$bIdx}_coupon_code", $b['coupon_code']) }}"
                                               placeholder="e.g. ULNJI2026M or DIGIGO10"
                                               class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500 font-mono font-bold uppercase">
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 pt-1">
                                    <label class="text-[11px] font-medium text-slate-600 dark:text-slate-400">
                                        Coupon Pill Color:
                                    </label>
                                    <input type="color"
                                           name="dual_promo_{{ $bIdx }}_coupon_bg_color"
                                           value="{{ old("dual_promo_{$bIdx}_coupon_bg_color", $b['coupon_bg_color']) }}"
                                           class="w-7 h-7 rounded border border-slate-300 dark:border-slate-600 cursor-pointer p-0.5">
                                    <span class="text-[11px] text-slate-400 font-mono">{{ $b['coupon_bg_color'] }}</span>
                                </div>
                            </div>

                            <!-- Description & Disclaimer -->
                            <div class="space-y-3">
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                        Campaign Subtitle / Description Text
                                    </label>
                                    <textarea name="dual_promo_{{ $bIdx }}_desc"
                                              rows="2"
                                              placeholder="e.g. Stay effortlessly cool with an ultra-compact 40,000 RPM turbo airflow."
                                              class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">{{ old("dual_promo_{$bIdx}_desc", $b['desc']) }}</textarea>
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                        Disclaimer / Fine Print Text
                                    </label>
                                    <input type="text"
                                           name="dual_promo_{{ $bIdx }}_disclaimer"
                                           value="{{ old("dual_promo_{$bIdx}_disclaimer", $b['disclaimer']) }}"
                                           placeholder="e.g. *Not combined with promotional offers and discounts"
                                           class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500 text-slate-500">
                                </div>
                            </div>

                            <!-- Link & Colors -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-slate-100 dark:border-slate-700/60">
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                        Target Redirect URL
                                    </label>
                                    <input type="text"
                                           name="dual_promo_{{ $bIdx }}_link"
                                           value="{{ old("dual_promo_{$bIdx}_link", $b['link']) }}"
                                           placeholder="e.g. /category/accessories or #link"
                                           class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                        Background Gradient
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <input type="color"
                                               name="dual_promo_{{ $bIdx }}_bg_start"
                                               value="{{ old("dual_promo_{$bIdx}_bg_start", $b['bg_gradient_start']) }}"
                                               title="Gradient Start Color"
                                               class="w-8 h-8 rounded border border-slate-300 dark:border-slate-600 cursor-pointer p-0.5">
                                        <i class="fa-solid fa-arrow-right text-[10px] text-slate-400"></i>
                                        <input type="color"
                                               name="dual_promo_{{ $bIdx }}_bg_end"
                                               value="{{ old("dual_promo_{$bIdx}_bg_end", $b['bg_gradient_end']) }}"
                                               title="Gradient End Color"
                                               class="w-8 h-8 rounded border border-slate-300 dark:border-slate-600 cursor-pointer p-0.5">
                                    </div>
                                </div>
                            </div>

                            <!-- Graphic Image Upload -->
                            <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-slate-700/60">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                    Right Visual Graphic Image
                                </label>
                                <div class="flex items-center gap-3">
                                    <div class="w-24 h-16 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-900 shrink-0 relative group">
                                        <img src="{{ $b['image_url'] }}" alt="{{ $b['title'] }}" class="w-full h-full object-cover">
                                        @if($b['has_custom_img'])
                                            <span class="absolute bottom-1 right-1 px-1 py-0.2 rounded text-[7px] font-bold bg-emerald-600 text-white">Custom</span>
                                        @else
                                            <span class="absolute bottom-1 right-1 px-1 py-0.2 rounded text-[7px] font-bold bg-slate-800 text-slate-300">Default</span>
                                        @endif
                                    </div>
                                    <div class="flex-1 space-y-1">
                                        <input type="file"
                                               name="dual_promo_{{ $bIdx }}_img"
                                               accept="image/png,image/jpeg,image/webp,image/svg+xml"
                                               class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 dark:file:bg-brand-950/60 dark:file:text-brand-300 hover:file:bg-brand-100 cursor-pointer">
                                        @if($b['has_custom_img'])
                                            <label class="inline-flex items-center gap-1.5 text-[11px] text-rose-600 dark:text-rose-400 cursor-pointer">
                                                <input type="checkbox" name="remove_dual_{{ $bIdx }}_img" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                                <span>Reset to default photo</span>
                                            </label>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Mini Mockup Footer -->
                        <div class="pt-3 border-t border-slate-100 dark:border-slate-700/60">
                            <div class="rounded-xl p-3 text-white flex items-center justify-between overflow-hidden shadow-xs" style="background: linear-gradient(135deg, {{ $b['bg_gradient_start'] }}, {{ $b['bg_gradient_end'] }});">
                                <div class="max-w-[70%]">
                                    <span class="block text-xs font-bold truncate">{{ $b['title'] }}</span>
                                    @if(!empty($b['coupon_code']))
                                        <span class="inline-block mt-1 px-1.5 py-0.5 rounded text-[9px] font-mono font-bold" style="background-color: {{ $b['coupon_bg_color'] }};">
                                            {{ $b['coupon_code'] }}
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[10px] text-white/80 font-semibold uppercase">Preview</span>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

            <!-- Save Form Submit -->
            <div class="flex justify-end pt-2">
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-500/20 transition cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-sm"></i>
                    <span>Save Coupon & Deals Banners</span>
                </button>
            </div>
        </form>

    @elseif(request('tab') === 'remini')
        <!-- =========================================================================
             TAB 5: REMINI AI IMAGE EDITING TOOL PROMOTIONAL BANNER
             ========================================================================= -->
        @php
            $reminiEnabled = setting('remini_banner_enabled', '1') === '1';
            $reminiBadge = setting('remini_badge', 'AI Image Editing Tool');
            $reminiTitle = setting('remini_title', "এক ক্লিকেই আপনার ঝাপসা ছবিকে\nকরে তুলুন");
            $reminiHighlight = setting('remini_title_highlight', 'ক্রিস্টাল ক্লিয়ার!');
            $reminiBtnText = setting('remini_button_text', 'Get Now');
            $reminiBtnLink = setting('remini_button_link', '#remini-pro');
            $reminiAppName = setting('remini_app_name', 'Remini');

            $reminiBgColor = setting('remini_bg_color', '#f3c4c4');
            $reminiBadgeColor = setting('remini_badge_color', '#8b1d24');
            $reminiAppBgColor = setting('remini_app_bg_color', '#991b1b');

            $customAppIcon = setting_url('remini_app_icon');
            $hasCustomIcon = !empty(setting('remini_app_icon'));
            $landscapeImg = setting_url('remini_landscape_img', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1000&q=80');
            $hasCustomLandscape = !empty(setting('remini_landscape_img'));
        @endphp

        <!-- Metric Overview Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg {{ $reminiEnabled ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400' : 'bg-slate-100 dark:bg-slate-700 text-slate-500' }} flex items-center justify-center shrink-0">
                    <i class="fa-solid {{ $reminiEnabled ? 'fa-circle-check' : 'fa-circle-pause' }} text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Banner Status</p>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-0.5">
                        {{ $reminiEnabled ? 'Active & Live' : 'Hidden on Store' }}
                    </h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-wand-magic-sparkles text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Tool Name</p>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-0.5 truncate max-w-[150px]">
                        {{ $reminiAppName ?: 'Remini' }}
                    </h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg bg-pink-50 dark:bg-pink-950/50 text-pink-600 dark:text-pink-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-palette text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Banner Palette</p>
                    <div class="flex items-center gap-1.5 mt-1">
                        <span class="w-4 h-4 rounded-full border border-black/20" style="background-color: {{ $reminiBgColor }};" title="Background"></span>
                        <span class="w-4 h-4 rounded-full border border-black/20" style="background-color: {{ $reminiBadgeColor }};" title="Badge"></span>
                        <span class="w-4 h-4 rounded-full border border-black/20" style="background-color: {{ $reminiAppBgColor }};" title="App Badge"></span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-image text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Visual Landscape</p>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-0.5 text-xs">
                        {{ $hasCustomLandscape ? 'Custom Photo' : 'Default Landscape' }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- Remini Banner Configuration Form -->
        <form id="reminiBannerForm" action="{{ route('admin.appearance.banners.remini.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Left 7 Cols: Text Content, Typography & Colors -->
                <div class="lg:col-span-7 space-y-6">

                    <!-- Section Activation & Texts Card -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs p-5 space-y-5">
                        <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3 flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">AI Promo Banner Information</h2>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Edit headlines, buttons, and app details for the Remini AI banner.</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400">
                                Near Footer Section
                            </span>
                        </div>

                        <!-- Visibility Switch -->
                        <div class="flex items-center justify-between p-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/40">
                            <div>
                                <span class="block text-xs font-bold text-slate-800 dark:text-slate-200">Enable AI Tool Promotional Banner</span>
                                <span class="block text-[11px] text-slate-500 dark:text-slate-400">Show this Remini AI editing promotional showcase near the bottom of the homepage</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox"
                                       name="remini_banner_enabled"
                                       value="1"
                                       class="sr-only peer"
                                       {{ $reminiEnabled ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-600"></div>
                            </label>
                        </div>

                        <!-- Badge Input -->
                        <div class="space-y-1.5">
                            <label for="remini_badge" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                Top Category Pill / Badge Text
                            </label>
                            <input type="text"
                                   id="remini_badge"
                                   name="remini_badge"
                                   value="{{ old('remini_badge', $reminiBadge) }}"
                                   placeholder="e.g. AI Image Editing Tool"
                                   class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">
                        </div>

                        <!-- Main Headline & Highlighted Text -->
                        <div class="space-y-1.5">
                            <label for="remini_title" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                Headline Title (Line 1 & 2) <span class="text-rose-500">*</span>
                            </label>
                            <textarea id="remini_title"
                                      name="remini_title"
                                      rows="2"
                                      required
                                      placeholder="e.g. এক ক্লিকেই আপনার ঝাপসা ছবিকে করে তুলুন"
                                      class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">{{ old('remini_title', $reminiTitle) }}</textarea>
                            <p class="text-[11px] text-slate-400">Line breaks will be preserved.</p>
                        </div>

                        <div class="space-y-1.5">
                            <label for="remini_title_highlight" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                Headline Highlighted Text (Bottom Bold Line)
                            </label>
                            <input type="text"
                                   id="remini_title_highlight"
                                   name="remini_title_highlight"
                                   value="{{ old('remini_title_highlight', $reminiHighlight) }}"
                                   placeholder="e.g. ক্রিস্টাল ক্লিয়ার!"
                                   class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">
                        </div>

                        <!-- Button Text & Link -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100 dark:border-slate-700/60">
                            <div class="space-y-1.5">
                                <label for="remini_button_text" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                    CTA Button Label
                                </label>
                                <input type="text"
                                       id="remini_button_text"
                                       name="remini_button_text"
                                       value="{{ old('remini_button_text', $reminiBtnText) }}"
                                       placeholder="e.g. Get Now"
                                       class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">
                            </div>

                            <div class="space-y-1.5">
                                <label for="remini_button_link" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                    CTA Button Link
                                </label>
                                <input type="text"
                                       id="remini_button_link"
                                       name="remini_button_link"
                                       value="{{ old('remini_button_link', $reminiBtnLink) }}"
                                       placeholder="e.g. /product/remini-pro or #remini-pro"
                                       class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">
                            </div>
                        </div>

                    </div>

                    <!-- Custom Colors Palette Card -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs p-5 space-y-4">
                        <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3">
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Color Customization</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Customize the hex background colors to match your brand campaign.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                    Card Background
                                </label>
                                <div class="flex items-center gap-2">
                                    <input type="color"
                                           name="remini_bg_color"
                                           value="{{ old('remini_bg_color', $reminiBgColor) }}"
                                           class="w-9 h-9 rounded-lg border border-slate-300 dark:border-slate-600 cursor-pointer p-0.5">
                                    <input type="text"
                                           value="{{ old('remini_bg_color', $reminiBgColor) }}"
                                           readonly
                                           class="w-24 px-2 py-1 text-xs rounded border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-600 dark:text-slate-400 font-mono">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                    Badge Pill Color
                                </label>
                                <div class="flex items-center gap-2">
                                    <input type="color"
                                           name="remini_badge_color"
                                           value="{{ old('remini_badge_color', $reminiBadgeColor) }}"
                                           class="w-9 h-9 rounded-lg border border-slate-300 dark:border-slate-600 cursor-pointer p-0.5">
                                    <input type="text"
                                           value="{{ old('remini_badge_color', $reminiBadgeColor) }}"
                                           readonly
                                           class="w-24 px-2 py-1 text-xs rounded border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-600 dark:text-slate-400 font-mono">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                    Center App Card Color
                                </label>
                                <div class="flex items-center gap-2">
                                    <input type="color"
                                           name="remini_app_bg_color"
                                           value="{{ old('remini_app_bg_color', $reminiAppBgColor) }}"
                                           class="w-9 h-9 rounded-lg border border-slate-300 dark:border-slate-600 cursor-pointer p-0.5">
                                    <input type="text"
                                           value="{{ old('remini_app_bg_color', $reminiAppBgColor) }}"
                                           readonly
                                           class="w-24 px-2 py-1 text-xs rounded border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-600 dark:text-slate-400 font-mono">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right 5 Cols: App Badge & Visual Landscape Uploads -->
                <div class="lg:col-span-5 space-y-6">

                    <!-- Center App Badge Settings -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs p-5 space-y-4">
                        <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3 flex items-center justify-between">
                            <div>
                                <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Center App Badge</h3>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">Floating square icon badge placed in the middle.</p>
                            </div>
                            <span class="w-6 h-6 rounded-full bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400 flex items-center justify-center text-xs font-bold">App</span>
                        </div>

                        <!-- App Name -->
                        <div class="space-y-1.5">
                            <label for="remini_app_name" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                App / Product Brand Name
                            </label>
                            <input type="text"
                                   id="remini_app_name"
                                   name="remini_app_name"
                                   value="{{ old('remini_app_name', $reminiAppName) }}"
                                   placeholder="e.g. Remini, Canva, Lightroom"
                                   class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500 font-bold">
                        </div>

                        <!-- App Icon Upload -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                Custom App Icon (Optional)
                            </label>
                            <div class="flex items-center gap-4">
                                <div class="w-20 h-20 rounded-2xl flex flex-col items-center justify-center shrink-0 border border-white/20 shadow-md text-white overflow-hidden relative" style="background-color: {{ $reminiAppBgColor }};">
                                    @if($customAppIcon)
                                        <img src="{{ $customAppIcon }}" alt="{{ $reminiAppName }}" class="max-w-[50px] max-h-[40px] object-contain">
                                        <span class="absolute bottom-1 right-1 px-1 py-0.2 rounded text-[7px] font-bold bg-emerald-600 text-white">Custom</span>
                                    @else
                                        <svg viewBox="0 0 100 100" fill="none" class="w-10 h-10">
                                            <rect x="22" y="24" width="56" height="46" rx="4" stroke="#fbcfe8" stroke-width="5.5" fill="none" transform="rotate(-6 50 47)"/>
                                            <path d="M57 32L59.5 38.5L66 41L59.5 43.5L57 50L54.5 43.5L48 41L54.5 38.5Z" fill="#ffffff"/>
                                        </svg>
                                        <span class="text-[10px] font-extrabold mt-0.5">{{ $reminiAppName ?: 'Remini' }}</span>
                                    @endif
                                </div>

                                <div class="flex-1 space-y-2">
                                    <input type="file"
                                           name="remini_app_icon"
                                           accept="image/png,image/jpeg,image/webp,image/svg+xml"
                                           class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 dark:file:bg-brand-950/60 dark:file:text-brand-300 hover:file:bg-brand-100 cursor-pointer">
                                    @if($hasCustomIcon)
                                        <label class="inline-flex items-center gap-1.5 text-[11px] text-rose-600 dark:text-rose-400 cursor-pointer">
                                            <input type="checkbox" name="remove_remini_icon" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                            <span>Reset to default Remini logo</span>
                                        </label>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Landscape Comparison Image -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs p-5 space-y-4">
                        <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3 flex items-center justify-between">
                            <div>
                                <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Landscape Visual Comparison</h3>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">Atmospheric landscape photo displayed on the right.</p>
                            </div>
                            <span class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400 flex items-center justify-center text-xs font-bold">Img</span>
                        </div>

                        <div class="space-y-3">
                            <div class="w-full h-32 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 relative group">
                                <img src="{{ $landscapeImg }}" alt="Landscape comparison" class="w-full h-full object-cover">
                                @if($hasCustomLandscape)
                                    <span class="absolute bottom-2 right-2 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-600 text-white shadow-xs">Custom Uploaded</span>
                                @else
                                    <span class="absolute bottom-2 right-2 px-2 py-0.5 rounded text-[10px] font-bold bg-slate-800/80 text-slate-200 shadow-xs">Default Photo</span>
                                @endif
                            </div>

                            <input type="file"
                                   name="remini_landscape_img"
                                   accept="image/png,image/jpeg,image/webp,image/svg+xml"
                                   class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 dark:file:bg-brand-950/60 dark:file:text-brand-300 hover:file:bg-brand-100 cursor-pointer">

                            @if($hasCustomLandscape)
                                <label class="inline-flex items-center gap-1.5 text-[11px] text-rose-600 dark:text-rose-400 cursor-pointer">
                                    <input type="checkbox" name="remove_remini_landscape" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                    <span>Reset to default landscape photo</span>
                                </label>
                            @endif
                        </div>
                    </div>

                    <!-- Save Actions -->
                    <div class="pt-2">
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-500/20 transition cursor-pointer">
                            <i class="fa-solid fa-floppy-disk text-sm"></i>
                            <span>Save AI Tool Banner Changes</span>
                        </button>
                    </div>

                </div>

            </div>
        </form>

    @else
        <!-- =========================================================================
             TAB 1: HERO BANNER SLIDES & CAROUSEL CONTROLS
             ========================================================================= -->

        <!-- Metric Overview Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-950/50 text-brand-600 dark:text-brand-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-images text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total Slides</p>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mt-0.5">{{ $totalBanners }}</h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Active on Site</p>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mt-0.5">{{ $activeBanners }}</h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-play text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Auto-play</p>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mt-0.5">
                        {{ setting('banner_autoplay', '1') === '1' ? 'Enabled' : 'Paused' }}
                    </h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-stopwatch text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Slide Interval</p>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mt-0.5">
                        {{ round(((int) setting('banner_delay', '4000')) / 1000, 1) }}s
                    </h3>
                </div>
            </div>
        </div>

        <!-- Main Content Grid: Slides Table (left) & Carousel Settings Card (right) -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- Left 2 Cols: Banner Slides Table -->
            <div class="xl:col-span-2 space-y-4">
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs overflow-hidden">

                    <!-- Table Header & Filter Bar -->
                    <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Active Banner Slides</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Slides are ordered by sequence number (lower numbers appear first).</p>
                        </div>

                        <form method="GET" action="{{ route('admin.appearance.banners.index') }}" class="flex items-center gap-2">
                            <input type="hidden" name="tab" value="slides">
                            <div class="relative">
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Search slides..."
                                       class="w-44 sm:w-52 pl-8 pr-3 py-1.5 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">
                                <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2.5 text-slate-400 text-xs"></i>
                            </div>

                            <select name="status"
                                    onchange="this.form.submit()"
                                    class="px-2.5 py-1.5 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>

                            @if(request('search') || request('status'))
                                <a href="{{ route('admin.appearance.banners.index', ['tab' => 'slides']) }}" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition" title="Reset filter">
                                    <i class="fa-solid fa-xmark text-sm"></i>
                                </a>
                            @endif
                        </form>
                    </div>

                    <!-- Slides Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                            <thead class="bg-slate-50 dark:bg-slate-900/60 text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider text-[11px] border-b border-slate-200 dark:border-slate-700">
                                <tr>
                                    <th class="py-3 px-4 w-16 text-center">Order</th>
                                    <th class="py-3 px-4">Slide Preview & Content</th>
                                    <th class="py-3 px-4 hidden md:table-cell">CTA Button</th>
                                    <th class="py-3 px-4 text-center">Status</th>
                                    <th class="py-3 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                                @forelse($banners as $banner)
                                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/30 transition">

                                        <!-- Order Sequence Badge -->
                                        <td class="py-3.5 px-4 text-center">
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-slate-100 dark:bg-slate-700 font-bold text-slate-700 dark:text-slate-300 text-xs">
                                                {{ $banner->order }}
                                            </span>
                                        </td>

                                        <!-- Banner Media & Title -->
                                        <td class="py-3.5 px-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-20 h-11 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-900 shrink-0 border border-slate-200 dark:border-slate-700 flex items-center justify-center">
                                                    @if($banner->image)
                                                        <img src="{{ str_starts_with($banner->image, 'http') ? $banner->image : asset('storage/' . $banner->image) }}"
                                                             alt="{{ $banner->title }}"
                                                             class="w-full h-full object-cover">
                                                    @else
                                                        <i class="fa-solid fa-image text-slate-300 dark:text-slate-600 text-base"></i>
                                                    @endif
                                                </div>
                                                <div class="min-w-0">
                                                    <h3 class="font-bold text-slate-900 dark:text-white truncate max-w-xs text-xs">
                                                        {{ $banner->title }}
                                                    </h3>
                                                    @if($banner->subtitle)
                                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate max-w-xs mt-0.5">
                                                            {{ $banner->subtitle }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <!-- CTA Link Badge -->
                                        <td class="py-3.5 px-4 hidden md:table-cell">
                                            @if($banner->button_text)
                                                <div class="flex flex-col">
                                                    <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $banner->button_text }}</span>
                                                    <span class="text-[10px] text-slate-400 truncate max-w-[140px]">{{ $banner->button_link ?? '#' }}</span>
                                                </div>
                                            @else
                                                <span class="text-slate-400 italic text-[11px]">— None —</span>
                                            @endif
                                        </td>

                                        <!-- Status Toggle Form -->
                                        <td class="py-3.5 px-4 text-center">
                                            <form action="{{ route('admin.appearance.banners.toggle-status', $banner) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold transition cursor-pointer {{ $banner->status === 'active' ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-400' }}">
                                                    <span class="w-1.5 h-1.5 rounded-full {{ $banner->status === 'active' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                                    <span>{{ ucfirst($banner->status) }}</span>
                                                </button>
                                            </form>
                                        </td>

                                        <!-- Action Buttons -->
                                        <td class="py-3.5 px-4 text-right">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <a href="{{ route('admin.appearance.banners.edit', $banner) }}"
                                                   class="p-1.5 rounded-lg text-slate-500 hover:text-brand-600 hover:bg-brand-50 dark:hover:bg-slate-700 transition"
                                                   title="Edit Slide">
                                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                </a>

                                                <button type="button"
                                                        class="p-1.5 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition cursor-pointer js-custom-delete"
                                                        data-action="{{ route('admin.appearance.banners.destroy', $banner) }}"
                                                        data-title="Delete Banner Slide"
                                                        data-message="Are you sure you want to delete '{{ addslashes($banner->title) }}'? This slide will immediately be removed from the homepage hero carousel."
                                                        title="Delete Slide">
                                                    <i class="fa-solid fa-trash text-xs"></i>
                                                </button>
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                            <i class="fa-solid fa-images text-3xl mb-2 text-slate-300 dark:text-slate-600"></i>
                                            <p class="font-medium text-slate-600 dark:text-slate-400">No banner slides found.</p>
                                            <p class="text-xs text-slate-400 mt-0.5">Click "Add Banner Slide" to upload your first hero slide.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($banners->hasPages())
                        <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                            {{ $banners->links() }}
                        </div>
                    @endif

                </div>

                <!-- Scrolling Ticker Quick Switch Notice -->
                <div class="p-4 rounded-xl bg-purple-50/70 dark:bg-purple-950/30 border border-purple-200 dark:border-purple-900/40 text-purple-900 dark:text-purple-300 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/60 text-purple-700 dark:text-purple-300 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-bullhorn text-sm"></i>
                        </div>
                        <div>
                            <span class="font-bold text-slate-900 dark:text-white">Looking for the Scrolling Trust Features Bar?</span>
                            <p class="text-[11px] text-purple-700 dark:text-purple-300/80 mt-0.5">Manage the animated guarantee pills scrolling directly underneath this carousel.</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.appearance.banners.index', ['tab' => 'ticker']) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shrink-0 shadow-2xs transition">
                        <span>Configure Trust Ticker</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Right 1 Col: Carousel Display & Autoplay Settings Card -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs p-5 space-y-5">
                    <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Carousel Controls</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Control slider behavior on the homepage.</p>
                        </div>
                        <div class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center">
                            <i class="fa-solid fa-sliders text-xs"></i>
                        </div>
                    </div>

                    <form action="{{ route('admin.appearance.banners.settings.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <!-- Autoplay Toggle -->
                        <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/40">
                            <div>
                                <span class="block text-xs font-bold text-slate-800 dark:text-slate-200">Auto-Slide Play</span>
                                <span class="block text-[11px] text-slate-500 dark:text-slate-400">Automatically transitions to next banner</span>
                            </div>
                            <input type="checkbox"
                                   name="banner_autoplay"
                                   value="1"
                                   class="rounded border-slate-300 text-brand-600 focus:ring-brand-500"
                                   {{ setting('banner_autoplay', '1') === '1' ? 'checked' : '' }}>
                        </div>

                        <!-- Slide Duration (Interval) -->
                        <div>
                            <label for="banner_delay" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">
                                Slide Transition Delay
                            </label>
                            <select id="banner_delay"
                                    name="banner_delay"
                                    class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">
                                <option value="3000" {{ setting('banner_delay', '4000') == '3000' ? 'selected' : '' }}>3 Seconds (Fast)</option>
                                <option value="4000" {{ setting('banner_delay', '4000') == '4000' ? 'selected' : '' }}>4 Seconds (Standard)</option>
                                <option value="5000" {{ setting('banner_delay', '4000') == '5000' ? 'selected' : '' }}>5 Seconds (Relaxed)</option>
                                <option value="6000" {{ setting('banner_delay', '4000') == '6000' ? 'selected' : '' }}>6 Seconds (Slow)</option>
                                <option value="8000" {{ setting('banner_delay', '4000') == '8000' ? 'selected' : '' }}>8 Seconds (Very Slow)</option>
                            </select>
                            <p class="text-[11px] text-slate-400 mt-1">Time each banner remains visible before sliding.</p>
                        </div>

                        <!-- Navigation Arrows Toggle -->
                        <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/40">
                            <div>
                                <span class="block text-xs font-bold text-slate-800 dark:text-slate-200">Navigation Arrows</span>
                                <span class="block text-[11px] text-slate-500 dark:text-slate-400">Show Left / Right arrow buttons on hover</span>
                            </div>
                            <input type="checkbox"
                                   name="banner_arrows"
                                   value="1"
                                   class="rounded border-slate-300 text-brand-600 focus:ring-brand-500"
                                   {{ setting('banner_arrows', '1') === '1' ? 'checked' : '' }}>
                        </div>

                        <!-- Pagination Dots Toggle -->
                        <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/40">
                            <div>
                                <span class="block text-xs font-bold text-slate-800 dark:text-slate-200">Pagination Pill Dots</span>
                                <span class="block text-[11px] text-slate-500 dark:text-slate-400">Show bottom capsule dot indicator</span>
                            </div>
                            <input type="checkbox"
                                   name="banner_dots"
                                   value="1"
                                   class="rounded border-slate-300 text-brand-600 focus:ring-brand-500"
                                   {{ setting('banner_dots', '1') === '1' ? 'checked' : '' }}>
                        </div>

                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-sm transition cursor-pointer">
                            <i class="fa-solid fa-floppy-disk text-xs"></i>
                            <span>Save Carousel Controls</span>
                        </button>
                    </form>
                </div>

                <!-- Helpful Information Box -->
                <div class="p-4 rounded-xl bg-blue-50/60 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-900/40 text-blue-900 dark:text-blue-300 text-xs space-y-2">
                    <div class="flex items-center gap-2 font-bold">
                        <i class="fa-solid fa-lightbulb text-sm text-blue-600 dark:text-blue-400"></i>
                        <span>Banner Best Practices</span>
                    </div>
                    <p class="text-[11px] leading-relaxed text-blue-800 dark:text-blue-300/90">
                        Recommended dimensions for hero banner images are <strong>1600 &times; 500 px</strong> or wider (21:9 to 16:9 aspect ratio). Both desktop and mobile views will scale gracefully.
                    </p>
                </div>
            </div>

        </div>
    @endif

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('trustFeaturesContainer');
        const addBtn = document.getElementById('addTrustFeatureBtn');
        const speedSelect = document.getElementById('trust_features_speed');
        const previewTrack = document.getElementById('previewMarqueeTrack');
        const previewGroup1 = document.getElementById('previewGroup1');
        const previewGroup2 = document.getElementById('previewGroup2');

        // Update preview animation speed if changed
        if (speedSelect && previewTrack) {
            speedSelect.addEventListener('change', function () {
                previewTrack.style.animation = `trustMarqueeScroll ${this.value}s linear infinite`;
            });
        }

        // Re-index all feature rows
        function reindexRows() {
            if (!container) return;
            const rows = container.querySelectorAll('.js-feature-row');
            rows.forEach((row, idx) => {
                const badge = row.querySelector('.js-row-index');
                if (badge) badge.textContent = `#${idx + 1}`;

                const iconInput = row.querySelector('.js-icon-input');
                if (iconInput) iconInput.name = `items[${idx}][icon]`;

                const textInput = row.querySelector('.js-text-input');
                if (textInput) textInput.name = `items[${idx}][text]`;

                const linkInput = row.querySelector('.js-link-input');
                if (linkInput) linkInput.name = `items[${idx}][link]`;
            });
            updateLivePreview();
        }

        // Live preview generator
        function updateLivePreview() {
            if (!previewGroup1 || !previewGroup2 || !container) return;
            const rows = container.querySelectorAll('.js-feature-row');
            let html = '';

            rows.forEach(row => {
                const textInput = row.querySelector('.js-text-input');
                const iconInput = row.querySelector('.js-icon-input');
                const text = textInput ? textInput.value.trim() : '';
                const icon = iconInput ? iconInput.value.trim() : 'fa-solid fa-circle-check';

                if (text) {
                    const isSvg = icon.startsWith('<svg');
                    const iconMarkup = isSvg ? icon : `<i class="${icon}"></i>`;
                    html += `
                        <div class="trust-feature-item">
                            <span class="trust-icon-box">${iconMarkup}</span>
                            <span class="trust-text">${escapeHtml(text)}</span>
                        </div>
                    `;
                }
            });

            if (!html) {
                html = '<div class="trust-feature-item text-xs text-slate-400"><span>No active badges</span></div>';
            }

            previewGroup1.innerHTML = html;
            previewGroup2.innerHTML = html;
        }

        function escapeHtml(text) {
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return text.replace(/[&<>"']/g, m => map[m]);
        }

        // Delegate icon inputs and text inputs for instant live preview
        if (container) {
            container.addEventListener('input', function (e) {
                if (e.target.classList.contains('js-icon-input')) {
                    const row = e.target.closest('.js-feature-row');
                    const previewBox = row ? row.querySelector('.js-icon-preview-box') : null;
                    if (previewBox) {
                        const val = e.target.value.trim();
                        if (val.startsWith('<svg')) {
                            previewBox.innerHTML = val;
                        } else {
                            previewBox.innerHTML = `<i class="${val || 'fa-solid fa-circle-check'}"></i>`;
                        }
                    }
                }
                updateLivePreview();
            });

            // Delete row
            container.addEventListener('click', function (e) {
                const removeBtn = e.target.closest('.js-remove-feature-row');
                if (removeBtn) {
                    const row = removeBtn.closest('.js-feature-row');
                    if (row) {
                        row.remove();
                        reindexRows();
                    }
                }
            });
        }

        // Preset pill clicks: inject into the last focused or first row
        document.querySelectorAll('.js-preset-pill').forEach(pill => {
            pill.addEventListener('click', function () {
                const icon = this.dataset.icon;
                const rows = container.querySelectorAll('.js-feature-row');
                if (rows.length > 0) {
                    const targetRow = rows[rows.length - 1];
                    const input = targetRow.querySelector('.js-icon-input');
                    const preview = targetRow.querySelector('.js-icon-preview-box');
                    if (input) {
                        input.value = icon;
                        if (preview) preview.innerHTML = `<i class="${icon}"></i>`;
                        updateLivePreview();
                    }
                }
            });
        });

        // Add new feature row
        if (addBtn && container) {
            addBtn.addEventListener('click', function () {
                const noNotice = document.getElementById('noFeaturesNotice');
                if (noNotice) noNotice.remove();

                const count = container.querySelectorAll('.js-feature-row').length;
                const newRow = document.createElement('div');
                newRow.className = 'js-feature-row bg-slate-50/70 dark:bg-slate-900/40 p-4 rounded-xl border border-slate-200 dark:border-slate-700 flex flex-col md:flex-row md:items-center gap-3 transition';
                newRow.innerHTML = `
                    <div class="shrink-0 flex items-center gap-2">
                        <span class="js-row-index w-7 h-7 rounded-lg bg-brand-100 dark:bg-brand-950/80 text-brand-700 dark:text-brand-300 font-bold text-xs flex items-center justify-center">
                            #${count + 1}
                        </span>
                    </div>

                    <div class="shrink-0 flex items-center gap-2">
                        <div class="js-icon-preview-box w-10 h-10 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-brand-600 dark:text-brand-400 flex items-center justify-center text-sm shadow-2xs">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>

                    <div class="w-full md:w-56 shrink-0 space-y-1">
                        <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400">
                            Icon (FontAwesome Class)
                        </label>
                        <input type="text"
                               name="items[${count}][icon]"
                               value="fa-solid fa-circle-check"
                               class="js-icon-input w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500"
                               placeholder="e.g. fa-solid fa-shield-halved">
                    </div>

                    <div class="flex-1 space-y-1">
                        <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400">
                            Badge Headline / Text <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="items[${count}][text]"
                               value=""
                               required
                               class="js-text-input w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500"
                               placeholder="e.g. 100% Genuine Licenses Guaranteed">
                    </div>

                    <div class="w-full md:w-48 shrink-0 space-y-1">
                        <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400">
                            Target Link (Optional)
                        </label>
                        <input type="text"
                               name="items[${count}][link]"
                               value=""
                               class="js-link-input w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500"
                               placeholder="e.g. /policies or #">
                    </div>

                    <div class="shrink-0 flex items-center pt-5 md:pt-0">
                        <button type="button"
                                class="js-remove-feature-row p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition cursor-pointer"
                                title="Delete Badge">
                            <i class="fa-solid fa-trash-can text-sm"></i>
                        </button>
                    </div>
                `;

                container.appendChild(newRow);
                newRow.querySelector('.js-text-input').focus();
                reindexRows();
            });
        }

        // Live preview synchronization for Promo Banner tab
        const promoBadgeInput = document.getElementById('promo_badge');
        const promoTitleInput = document.getElementById('promo_title');
        const promoSubtitleInput = document.getElementById('promo_subtitle');
        const promoBtnTextInput = document.getElementById('promo_button_text');

        const previewBadge = document.getElementById('previewPromoBadge');
        const previewTitle = document.getElementById('previewPromoTitle');
        const previewSubtitle = document.getElementById('previewPromoSubtitle');
        const previewBtn = document.getElementById('previewPromoBtn');

        if (promoBadgeInput && previewBadge) {
            promoBadgeInput.addEventListener('input', function() {
                previewBadge.textContent = this.value.trim() || 'WF-1000XM3';
            });
        }
        if (promoTitleInput && previewTitle) {
            promoTitleInput.addEventListener('input', function() {
                previewTitle.textContent = this.value.trim() || 'শব্দ যেখানে নিখুঁত, সুর সেখানে জীবন্ত!';
            });
        }
        if (promoSubtitleInput && previewSubtitle) {
            promoSubtitleInput.addEventListener('input', function() {
                previewSubtitle.textContent = this.value.trim() || '—হারিয়ে যান এক অন্যরকম অনুভূতির দুনিয়ায়।';
            });
        }
        if (promoBtnTextInput && previewBtn) {
            promoBtnTextInput.addEventListener('input', function() {
                previewBtn.textContent = this.value.trim() || 'Get Now';
            });
        }
    });
</script>
@endpush
@endsection

