@extends('layouts.admin')

@section('title', 'Header & Appearance Settings')

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
                <span class="text-slate-800 dark:text-slate-200 font-medium">Header</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Header & Appearance Settings</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Control website logo, favicon, top bar contact hotline, search bar, and SEO meta tags.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 shadow-sm transition">
                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                <span>View Live Store</span>
            </a>
            <button type="submit" form="headerSettingsForm" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-sm transition">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save All Changes</span>
            </button>
        </div>
    </div>

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

    <form id="headerSettingsForm" action="{{ route('admin.appearance.header.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Tab Navigation Buttons -->
        <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-2 overflow-x-auto">
            <button type="button" class="appearance-tab-btn inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold transition bg-brand-600 text-white shadow-sm" data-tab="tab-branding">
                <i class="fa-solid fa-palette text-xs"></i>
                <span>Logo & Branding</span>
            </button>
            <button type="button" class="appearance-tab-btn inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold transition bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300" data-tab="tab-topbar">
                <i class="fa-solid fa-phone-volume text-xs"></i>
                <span>Top Bar & Hotline</span>
            </button>
            <button type="button" class="appearance-tab-btn inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold transition bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300" data-tab="tab-seo">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                <span>SEO & Meta Data</span>
            </button>
        </div>

        <!-- TAB 1: LOGO & BRANDING -->
        <div id="tab-branding" class="appearance-tab-pane space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-xs space-y-5">
                        <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3">
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Website Branding</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Define your website identity, brand name, and header visual assets.</p>
                        </div>

                        <div>
                            <label for="site_name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">
                                Site Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? 'DigiGo') }}" placeholder="e.g. DigiGo" class="w-full h-10 px-3.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition" required>
                        </div>

                        <div>
                            <label for="header_search_placeholder" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">
                                Header Search Bar Placeholder
                            </label>
                            <input type="text" id="header_search_placeholder" name="header_search_placeholder" value="{{ old('header_search_placeholder', $settings['header_search_placeholder'] ?? 'Search for AI tools, software, gift cards, subscriptions...') }}" class="w-full h-10 px-3.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition">
                        </div>

                        <div class="border-t border-slate-100 dark:border-slate-700/60 pt-4">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Primary Website Logo</label>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                <div class="w-36 h-20 rounded-lg border-2 border-dashed border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 flex items-center justify-center p-2 overflow-hidden shrink-0">
                                    @if(!empty($settings['site_logo']) && \Illuminate\Support\Facades\Storage::disk('public')->exists($settings['site_logo']))
                                        <img id="logoPreviewImg" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($settings['site_logo']) }}" alt="Logo" class="max-h-full max-w-full object-contain">
                                    @else
                                        <img id="logoPreviewImg" src="{{ default_site_logo() }}" alt="Default Logo" class="max-h-full max-w-full object-contain">
                                    @endif
                                </div>
                                <div class="space-y-2 flex-1">
                                    <input type="file" id="site_logo" name="site_logo" accept="image/*" class="block w-full text-xs text-slate-600 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500">Recommended: Transparent PNG or SVG (Max 3MB). Fallback: default DigiGo logo.</p>
                                    @if(!empty($settings['site_logo']))
                                        <label class="inline-flex items-center gap-2 text-xs text-rose-600 hover:text-rose-700 cursor-pointer pt-1">
                                            <input type="checkbox" name="remove_site_logo" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                            <span>Remove current logo and restore default</span>
                                        </label>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 dark:border-slate-700/60 pt-4">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Browser Favicon</label>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                <div class="w-16 h-16 rounded-lg border-2 border-dashed border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 flex items-center justify-center p-2 overflow-hidden shrink-0">
                                    @if(!empty($settings['site_favicon']) && \Illuminate\Support\Facades\Storage::disk('public')->exists($settings['site_favicon']))
                                        <img id="faviconPreviewImg" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($settings['site_favicon']) }}" alt="Favicon" class="max-h-full max-w-full object-contain">
                                    @else
                                        <img id="faviconPreviewImg" src="{{ default_site_favicon() }}" alt="Default Favicon" class="max-h-full max-w-full object-contain">
                                    @endif
                                </div>
                                <div class="space-y-2 flex-1">
                                    <input type="file" id="site_favicon" name="site_favicon" accept=".ico,image/png,image/svg+xml" class="block w-full text-xs text-slate-600 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500">Recommended: 32×32 or 64×64 px `.ico` or `.png`. Fallback: default DigiGo favicon.</p>
                                    @if(!empty($settings['site_favicon']))
                                        <label class="inline-flex items-center gap-2 text-xs text-rose-600 hover:text-rose-700 cursor-pointer pt-1">
                                            <input type="checkbox" name="remove_site_favicon" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                            <span>Remove custom favicon and restore default</span>
                                        </label>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-xs space-y-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Header Preview</span>
                        <div class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white overflow-hidden text-slate-900 shadow-xs p-3">
                            <div class="flex items-center justify-between gap-2 text-xs">
                                <div class="flex items-center gap-1.5">
                                    <img src="{{ default_site_logo() }}" alt="DigiGo" class="h-6 w-auto max-w-[120px] object-contain">
                                </div>
                                <div class="text-[10px] text-right">
                                    <div class="font-bold text-slate-700">{{ $settings['topbar_support_label'] ?? '24/7 SUPPORT' }}</div>
                                    <div class="font-semibold text-blue-600">{{ $settings['topbar_support_phone'] ?? '+8809611678088' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: TOP BAR & HOTLINE -->
        <div id="tab-topbar" class="appearance-tab-pane hidden space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3 flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                            <i class="fa-solid fa-headset text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Hotline Support Details</h2>
                        </div>
                    </div>
                    <div>
                        <label for="topbar_support_label" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Support Headline Label</label>
                        <input type="text" id="topbar_support_label" name="topbar_support_label" value="{{ old('topbar_support_label', $settings['topbar_support_label'] ?? '24/7 SUPPORT') }}" class="w-full h-10 px-3.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition">
                    </div>
                    <div>
                        <label for="topbar_support_phone" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Support Phone / Hotline Number</label>
                        <input type="text" id="topbar_support_phone" name="topbar_support_phone" value="{{ old('topbar_support_phone', $settings['topbar_support_phone'] ?? '+8809611678088') }}" class="w-full h-10 px-3.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition">
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3 flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <i class="fa-brands fa-whatsapp text-base"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">WhatsApp Support</h2>
                        </div>
                    </div>
                    <div>
                        <label for="topbar_whatsapp_label" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">WhatsApp Headline Label</label>
                        <input type="text" id="topbar_whatsapp_label" name="topbar_whatsapp_label" value="{{ old('topbar_whatsapp_label', $settings['topbar_whatsapp_label'] ?? 'WHATSAPP') }}" class="w-full h-10 px-3.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition">
                    </div>
                    <div>
                        <label for="topbar_whatsapp_phone" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">WhatsApp Number</label>
                        <input type="text" id="topbar_whatsapp_phone" name="topbar_whatsapp_phone" value="{{ old('topbar_whatsapp_phone', $settings['topbar_whatsapp_phone'] ?? '+8801600793325') }}" class="w-full h-10 px-3.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition">
                    </div>
                </div>

                <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                                <i class="fa-solid fa-bullhorn text-sm"></i>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Top Announcement Banner</h2>
                            </div>
                        </div>
                        <label class="inline-flex items-center gap-2.5 cursor-pointer select-none">
                            <div class="relative">
                                <input type="checkbox"
                                       id="topbar_announcement_enabled"
                                       name="topbar_announcement_enabled"
                                       value="1"
                                       class="sr-only peer"
                                       {{ old('topbar_announcement_enabled', $settings['topbar_announcement_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-300 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-600 shadow-inner"></div>
                            </div>
                            <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Show Announcement</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label for="topbar_announcement_text" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Announcement Text</label>
                            <input type="text" id="topbar_announcement_text" name="topbar_announcement_text" value="{{ old('topbar_announcement_text', $settings['topbar_announcement_text'] ?? 'Special discount on AI tools! Use code DIGIGO10 at checkout.') }}" class="w-full h-10 px-3.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition">
                        </div>
                        <div>
                            <label for="topbar_announcement_link" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Button Link (Optional)</label>
                            <input type="text" id="topbar_announcement_link" name="topbar_announcement_link" value="{{ old('topbar_announcement_link', $settings['topbar_announcement_link'] ?? '') }}" class="w-full h-10 px-3.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 3: SEO & META DATA -->
        <div id="tab-seo" class="appearance-tab-pane hidden space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-xs space-y-4">
                        <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3">
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Search Engine Optimization (SEO)</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label for="meta_title" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Default Meta Title <span class="text-rose-500">*</span></label>
                                <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $settings['meta_title'] ?? 'DigiGo - Digital Products, Software & Subscriptions') }}" class="w-full h-10 px-3.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition" required>
                            </div>
                            <div>
                                <label for="meta_title_separator" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Title Separator</label>
                                <input type="text" id="meta_title_separator" name="meta_title_separator" value="{{ old('meta_title_separator', $settings['meta_title_separator'] ?? '|') }}" class="w-full h-10 px-3.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition">
                            </div>
                        </div>

                        <div>
                            <label for="meta_description" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Meta Description</label>
                            <textarea id="meta_description" name="meta_description" rows="3" class="w-full p-3 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition">{{ old('meta_description', $settings['meta_description'] ?? 'Bangladesh\'s leading digital software, subscription keys, gift cards, and AI tools marketplace.') }}</textarea>
                        </div>

                        <div>
                            <label for="meta_keywords" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Meta Keywords</label>
                            <input type="text" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $settings['meta_keywords'] ?? 'digital products, software keys, subscription, ai tools, bangladesh, gift cards') }}" class="w-full h-10 px-3.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="meta_author" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Meta Author</label>
                                <input type="text" id="meta_author" name="meta_author" value="{{ old('meta_author', $settings['meta_author'] ?? 'DigiGo Bangladesh') }}" class="w-full h-10 px-3.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition">
                            </div>
                            <div>
                                <label for="meta_robots" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Robots Directive</label>
                                <select id="meta_robots" name="meta_robots" class="w-full h-10 px-3.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition">
                                    <option value="index, follow" {{ old('meta_robots', $settings['meta_robots'] ?? '') === 'index, follow' ? 'selected' : '' }}>index, follow</option>
                                    <option value="noindex, follow" {{ old('meta_robots', $settings['meta_robots'] ?? '') === 'noindex, follow' ? 'selected' : '' }}>noindex, follow</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-xs space-y-4">
                        <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3">
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Social Share Cards (Open Graph)</h2>
                        </div>
                        <div>
                            <label for="og_title" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Open Graph (OG) Title</label>
                            <input type="text" id="og_title" name="og_title" value="{{ old('og_title', $settings['og_title'] ?? 'DigiGo - Digital Products & Software Marketplace') }}" class="w-full h-10 px-3.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition">
                        </div>
                        <div>
                            <label for="og_description" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Open Graph (OG) Description</label>
                            <textarea id="og_description" name="og_description" rows="2" class="w-full p-3 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition">{{ old('og_description', $settings['og_description'] ?? 'Buy authentic license keys, subscriptions, and AI tool memberships instantly.') }}</textarea>
                        </div>
                        <div class="border-t border-slate-100 dark:border-slate-700/60 pt-4">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Social Share Banner Image (OG Image)</label>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                <div class="w-40 h-24 rounded-lg border-2 border-dashed border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 flex items-center justify-center p-1.5 overflow-hidden shrink-0">
                                    @if(!empty($settings['og_image']) && \Illuminate\Support\Facades\Storage::disk('public')->exists($settings['og_image']))
                                        <img id="ogPreviewImg" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($settings['og_image']) }}" alt="OG Banner" class="max-h-full max-w-full object-cover rounded">
                                    @else
                                        <div id="ogDefaultPlaceholder" class="flex flex-col items-center justify-center text-slate-400 text-center">
                                            <i class="fa-solid fa-share-nodes text-xl mb-1"></i>
                                            <span class="text-[10px]">No Banner</span>
                                        </div>
                                        <img id="ogPreviewImg" src="" alt="OG Banner" class="hidden max-h-full max-w-full object-cover rounded">
                                    @endif
                                </div>
                                <div class="space-y-2 flex-1">
                                    <input type="file" id="og_image" name="og_image" accept="image/*" class="block w-full text-xs text-slate-600 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500">Recommended: 1200×630 px (Max 4MB).</p>
                                    @if(!empty($settings['og_image']))
                                        <label class="inline-flex items-center gap-2 text-xs text-rose-600 hover:text-rose-700 cursor-pointer pt-1">
                                            <input type="checkbox" name="remove_og_image" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                            <span>Remove banner</span>
                                        </label>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-xs space-y-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Google SERP Snippet</span>
                        <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/60 space-y-1.5 font-sans">
                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] font-bold">d</div>
                                <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">DigiGo Bangladesh</span>
                            </div>
                            <div id="serpTitlePreview" class="text-sm font-semibold text-blue-800 dark:text-blue-400 hover:underline cursor-pointer line-clamp-1">
                                {{ $settings['meta_title'] ?? 'DigiGo - Digital Products, Software & Subscriptions' }}
                            </div>
                            <div id="serpDescPreview" class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                {{ $settings['meta_description'] ?? 'Bangladesh\'s leading digital software, subscription keys, gift cards, and AI tools marketplace.' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Bottom Save Bar -->
        <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <span class="text-xs text-slate-500 dark:text-slate-400">Changes will take effect immediately.</span>
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-sm transition">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save All Changes</span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabBtns = document.querySelectorAll('.appearance-tab-btn');
        const tabPanes = document.querySelectorAll('.appearance-tab-pane');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.dataset.tab;
                tabBtns.forEach(b => {
                    b.classList.remove('bg-brand-600', 'text-white', 'shadow-sm');
                    b.classList.add('bg-slate-100', 'dark:bg-slate-800', 'text-slate-700', 'dark:text-slate-300');
                });
                this.classList.remove('bg-slate-100', 'dark:bg-slate-800', 'text-slate-700', 'dark:text-slate-300');
                this.classList.add('bg-brand-600', 'text-white', 'shadow-sm');

                tabPanes.forEach(pane => {
                    if (pane.id === targetId) {
                        pane.classList.remove('hidden');
                    } else {
                        pane.classList.add('hidden');
                    }
                });
            });
        });

        function setupPreview(inputId, imgId, placeholderId) {
            const input = document.getElementById(inputId);
            const img = document.getElementById(imgId);
            const placeholder = document.getElementById(placeholderId);
            if (!input || !img) return;

            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(evt) {
                        img.src = evt.target.result;
                        img.classList.remove('hidden');
                        if (placeholder) placeholder.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        setupPreview('site_logo', 'logoPreviewImg', 'logoDefaultPlaceholder');
        setupPreview('site_favicon', 'faviconPreviewImg', 'faviconDefaultPlaceholder');
        setupPreview('og_image', 'ogPreviewImg', 'ogDefaultPlaceholder');

        const metaTitleInput = document.getElementById('meta_title');
        const metaDescInput = document.getElementById('meta_description');
        const serpTitle = document.getElementById('serpTitlePreview');
        const serpDesc = document.getElementById('serpDescPreview');

        if (metaTitleInput && serpTitle) {
            metaTitleInput.addEventListener('input', function() {
                serpTitle.textContent = this.value || 'DigiGo - Digital Products & Software';
            });
        }
        if (metaDescInput && serpDesc) {
            metaDescInput.addEventListener('input', function() {
                serpDesc.textContent = this.value || 'Bangladesh\'s leading digital software marketplace.';
            });
        }
    });
</script>
@endpush
@endsection

