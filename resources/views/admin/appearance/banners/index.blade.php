@extends('layouts.admin')

@section('title', request('tab') === 'ticker' ? 'Scrolling Trust Features Ticker' : 'Hero Banner Management')

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
                @endif
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                {{ request('tab') === 'ticker' ? 'Scrolling Trust Features Ticker' : 'Hero Banner Management' }}
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                {{ request('tab') === 'ticker' 
                    ? 'Manage the continuous scrolling marquee ticker directly beneath your hero banner: toggle visibility, control animation speed, and edit trust badges.' 
                    : 'Control every slide of your homepage hero carousel: upload banner graphics, edit headlines, set CTA buttons, and adjust slide transitions.' }}
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 shadow-sm transition">
                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                <span>View Live Store</span>
            </a>
            @if(request('tab') !== 'ticker')
                <a href="{{ route('admin.appearance.banners.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-sm transition">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Add Banner Slide</span>
                </a>
            @else
                <button type="submit" form="trustFeaturesForm" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-sm transition cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save Ticker Settings</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Section Tabs -->
    <div class="border-b border-slate-200 dark:border-slate-700 flex items-center gap-2">
        <a href="{{ route('admin.appearance.banners.index', ['tab' => 'slides']) }}"
           class="inline-flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold border-b-2 transition whitespace-nowrap {{ request('tab') !== 'ticker' ? 'border-brand-600 text-brand-600 dark:text-brand-400 dark:border-brand-400 bg-brand-50/50 dark:bg-brand-950/30' : 'border-transparent text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-images text-sm"></i>
            <span>Hero Carousel Slides</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ request('tab') !== 'ticker' ? 'bg-brand-100 text-brand-700 dark:bg-brand-900/60 dark:text-brand-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400' }}">{{ $totalBanners }}</span>
        </a>

        <a href="{{ route('admin.appearance.banners.index', ['tab' => 'ticker']) }}"
           class="inline-flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold border-b-2 transition whitespace-nowrap {{ request('tab') === 'ticker' ? 'border-brand-600 text-brand-600 dark:text-brand-400 dark:border-brand-400 bg-brand-50/50 dark:bg-brand-950/30' : 'border-transparent text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-bullhorn text-sm"></i>
            <span>Scrolling Trust Ticker (Under Banner)</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ request('tab') === 'ticker' ? 'bg-brand-100 text-brand-700 dark:bg-brand-900/60 dark:text-brand-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400' }}">{{ count(trust_features()) }} Badges</span>
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
    });
</script>
@endpush
@endsection

