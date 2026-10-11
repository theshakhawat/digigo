@extends('layouts.admin')

@section('title', 'Footer & Links Settings')

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
                <span class="text-slate-800 dark:text-slate-200 font-medium">Footer</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Footer Section Settings</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Control every element of your website footer: brand description, link columns, apps, social profiles, copyright, and payment badges.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 shadow-sm transition">
                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                <span>View Live Store</span>
            </a>
            <button type="submit" form="footerSettingsForm" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-sm transition cursor-pointer">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save All Changes</span>
            </button>
        </div>
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

    <form id="footerSettingsForm" action="{{ route('admin.appearance.footer.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Tab Navigation Buttons -->
        <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-2 overflow-x-auto">
            <button type="button" class="footer-tab-btn inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold transition bg-brand-600 text-white shadow-sm" data-tab="tab-brand-about">
                <i class="fa-solid fa-building text-xs"></i>
                <span>Brand & Description</span>
            </button>
            <button type="button" class="footer-tab-btn inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold transition bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300" data-tab="tab-navigation-links">
                <i class="fa-solid fa-list-check text-xs"></i>
                <span>Navigation Link Columns</span>
            </button>
            <button type="button" class="footer-tab-btn inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold transition bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300" data-tab="tab-apps-social">
                <i class="fa-solid fa-share-nodes text-xs"></i>
                <span>Apps & Social Links</span>
            </button>
            <button type="button" class="footer-tab-btn inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold transition bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300" data-tab="tab-copyright-payments">
                <i class="fa-solid fa-shield-halved text-xs"></i>
                <span>Copyright & Payment Badges</span>
            </button>
        </div>

        <!-- =========================================================================
             TAB 1: BRAND & ABOUT (COLUMN 1)
             ========================================================================= -->
        <div id="tab-brand-about" class="footer-tab-pane space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-xs space-y-5">
                        <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3">
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Footer Brand Info (Column 1)</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Control the brand logo and the introductory bio text shown on the footer's first column.</p>
                        </div>

                        <!-- About / Description Text -->
                        <div>
                            <label for="footer_about_text" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">
                                Footer About / Bio Description
                            </label>
                            <textarea id="footer_about_text"
                                      name="footer_about_text"
                                      rows="4"
                                      placeholder="We specialize in providing exclusively authentic digital products, gadgets and strategic solutions..."
                                      class="w-full p-3 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition">{{ old('footer_about_text', $settings['footer_about_text'] ?? 'We specialize in providing exclusively authentic digital products, gadgets and strategic solutions to help turn your ambitious ideas into reality.') }}</textarea>
                            <p class="text-[11px] text-slate-400 mt-1">Short paragraph displayed directly below the logo in the footer.</p>
                        </div>

                        <!-- Custom Footer Logo (Optional) -->
                        <div class="border-t border-slate-100 dark:border-slate-700/60 pt-4">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">
                                Custom Footer Logo <span class="text-slate-400 font-normal lowercase">(Optional - falls back to primary logo)</span>
                            </label>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                <div class="w-36 h-20 rounded-lg border-2 border-dashed border-slate-300 dark:border-slate-700 bg-slate-900 flex items-center justify-center p-2 overflow-hidden shrink-0">
                                    @if(!empty($settings['footer_custom_logo']) && \Illuminate\Support\Facades\Storage::disk('public')->exists($settings['footer_custom_logo']))
                                        <img id="footerLogoPreviewImg" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($settings['footer_custom_logo']) }}" alt="Footer Logo" class="max-h-full max-w-full object-contain">
                                    @elseif(setting_url('site_logo'))
                                        <img id="footerLogoPreviewImg" src="{{ setting_url('site_logo') }}" alt="Default Logo" class="max-h-full max-w-full object-contain">
                                    @else
                                        <div id="footerLogoDefaultPlaceholder" class="flex flex-col items-center justify-center text-slate-400 text-center">
                                            <i class="fa-solid fa-image text-xl mb-1"></i>
                                            <span class="text-[10px]">Primary Logo</span>
                                        </div>
                                        <img id="footerLogoPreviewImg" src="" alt="Preview" class="hidden max-h-full max-w-full object-contain">
                                    @endif
                                </div>

                                <div class="space-y-2 flex-1">
                                    <input type="file" id="footer_custom_logo" name="footer_custom_logo" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 dark:file:bg-slate-700 dark:file:text-slate-200 cursor-pointer">
                                    <p class="text-[11px] text-slate-400">PNG, SVG, or WEBP with transparent background recommended for dark footer. Max 3MB.</p>
                                    @if(!empty($settings['footer_custom_logo']))
                                        <label class="inline-flex items-center gap-2 text-xs text-rose-600 dark:text-rose-400 cursor-pointer pt-1">
                                            <input type="checkbox" name="remove_footer_custom_logo" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                            <span>Remove custom footer logo and use site logo</span>
                                        </label>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Preview Helper Column -->
                <div class="space-y-6">
                    <div class="bg-black text-white rounded-xl p-5 border border-slate-800 shadow-xs space-y-3">
                        <div class="flex items-center gap-2 pb-2 border-b border-white/10 text-xs font-bold uppercase tracking-wider text-slate-400">
                            <i class="fa-solid fa-eye text-blue-400"></i>
                            <span>Column 1 Live Preview</span>
                        </div>
                        <div class="space-y-3 pt-1">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-blue-600 text-white font-extrabold flex items-center justify-center text-sm">d</div>
                                <span class="font-extrabold text-base tracking-tight text-white">{{ strtolower(setting('site_name', 'digigo')) }}<span class="text-blue-400">.com.bd</span></span>
                            </div>
                            <p class="text-slate-400 text-xs leading-relaxed">
                                {{ $settings['footer_about_text'] ?? 'We specialize in providing exclusively authentic digital products, gadgets and strategic solutions to help turn your ambitious ideas into reality.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- =========================================================================
             TAB 2: NAVIGATION LINK COLUMNS (COLUMNS 2 & 3)
             ========================================================================= -->
        <div id="tab-navigation-links" class="footer-tab-pane hidden space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Column 2 (Popular Categories / Quick Links) -->
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-xs space-y-5">
                    <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Footer Column 2</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Title and 4 customizable links for the second footer column.</p>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300">Column 2</span>
                    </div>

                    <!-- Column 2 Title -->
                    <div>
                        <label for="footer_col2_title" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">
                            Column 2 Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               id="footer_col2_title"
                               name="footer_col2_title"
                               value="{{ old('footer_col2_title', $settings['footer_col2_title'] ?? 'Popular Categories') }}"
                               placeholder="Popular Categories"
                               required
                               class="w-full h-10 px-3.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition">
                    </div>

                    <!-- 4 Link Pairs for Column 2 -->
                    <div class="space-y-3 pt-2">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Column Links (Label & URL)</p>

                        @for($i = 1; $i <= 4; $i++)
                            <div class="p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/40 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase">Link #{{ $i }}</span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <input type="text"
                                           name="footer_col2_link{{ $i }}_text"
                                           value="{{ old('footer_col2_link'.$i.'_text', $settings['footer_col2_link'.$i.'_text'] ?? '') }}"
                                           placeholder="Label (e.g. Privacy Policy)"
                                           class="w-full h-9 px-3 text-xs rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600">
                                    <input type="text"
                                           name="footer_col2_link{{ $i }}_url"
                                           value="{{ old('footer_col2_link'.$i.'_url', $settings['footer_col2_link'.$i.'_url'] ?? '') }}"
                                           placeholder="URL (e.g. #privacy-policy or /privacy)"
                                           class="w-full h-9 px-3 text-xs rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 font-mono">
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Column 3 (Useful Links) -->
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-xs space-y-5">
                    <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Footer Column 3</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Title and 4 customizable links for the third footer column.</p>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300">Column 3</span>
                    </div>

                    <!-- Column 3 Title -->
                    <div>
                        <label for="footer_col3_title" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">
                            Column 3 Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               id="footer_col3_title"
                               name="footer_col3_title"
                               value="{{ old('footer_col3_title', $settings['footer_col3_title'] ?? 'Useful Links') }}"
                               placeholder="Useful Links"
                               required
                               class="w-full h-10 px-3.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition">
                    </div>

                    <!-- 4 Link Pairs for Column 3 -->
                    <div class="space-y-3 pt-2">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Column Links (Label & URL)</p>

                        @for($i = 1; $i <= 4; $i++)
                            <div class="p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/40 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase">Link #{{ $i }}</span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <input type="text"
                                           name="footer_col3_link{{ $i }}_text"
                                           value="{{ old('footer_col3_link'.$i.'_text', $settings['footer_col3_link'.$i.'_text'] ?? '') }}"
                                           placeholder="Label (e.g. About Us)"
                                           class="w-full h-9 px-3 text-xs rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600">
                                    <input type="text"
                                           name="footer_col3_link{{ $i }}_url"
                                           value="{{ old('footer_col3_link'.$i.'_url', $settings['footer_col3_link'.$i.'_url'] ?? '') }}"
                                           placeholder="URL (e.g. #about-us or /about)"
                                           class="w-full h-9 px-3 text-xs rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 font-mono">
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

            </div>
        </div>

        <!-- =========================================================================
             TAB 3: APPS & SOCIAL MEDIA (COLUMN 4)
             ========================================================================= -->
        <div id="tab-apps-social" class="footer-tab-pane hidden space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- App Store Badges -->
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-xs space-y-5">
                    <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3">
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Mobile App Downloads</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Control "Available On:" badges for Google Play and Apple App Store.</p>
                    </div>

                    <div>
                        <label for="footer_app_title" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">
                            App Section Heading
                        </label>
                        <input type="text"
                               id="footer_app_title"
                               name="footer_app_title"
                               value="{{ old('footer_app_title', $settings['footer_app_title'] ?? 'Available On:') }}"
                               placeholder="Available On:"
                               class="w-full h-10 px-3.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600">
                    </div>

                    <!-- Google Play -->
                    <div class="p-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/40 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                <i class="fa-brands fa-google-play text-emerald-500"></i> Google Play Store
                            </span>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="footer_play_store_enabled" value="1" class="sr-only peer" {{ old('footer_play_store_enabled', $settings['footer_play_store_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-brand-600"></div>
                            </label>
                        </div>
                        <input type="text"
                               name="footer_play_store_url"
                               value="{{ old('footer_play_store_url', $settings['footer_play_store_url'] ?? '#google-play') }}"
                               placeholder="Google Play URL or #google-play"
                               class="w-full h-9 px-3 text-xs rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 font-mono">
                    </div>

                    <!-- App Store -->
                    <div class="p-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/40 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                <i class="fa-brands fa-apple text-slate-900 dark:text-white"></i> Apple App Store
                            </span>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="footer_app_store_enabled" value="1" class="sr-only peer" {{ old('footer_app_store_enabled', $settings['footer_app_store_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-brand-600"></div>
                            </label>
                        </div>
                        <input type="text"
                               name="footer_app_store_url"
                               value="{{ old('footer_app_store_url', $settings['footer_app_store_url'] ?? '#app-store') }}"
                               placeholder="App Store URL or #app-store"
                               class="w-full h-9 px-3 text-xs rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 font-mono">
                    </div>
                </div>

                <!-- Social Media Links -->
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-xs space-y-5">
                    <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3">
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Social Media Channels</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Control the circular social icons and URLs displayed in the footer.</p>
                    </div>

                    <div>
                        <label for="footer_social_title" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">
                            Social Section Heading
                        </label>
                        <input type="text"
                               id="footer_social_title"
                               name="footer_social_title"
                               value="{{ old('footer_social_title', $settings['footer_social_title'] ?? 'Social links:') }}"
                               placeholder="Social links:"
                               class="w-full h-10 px-3.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600">
                    </div>

                    <div class="space-y-3">
                        <!-- Facebook -->
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-[#1877f2] text-white flex items-center justify-center shrink-0 text-sm">
                                <i class="fa-brands fa-facebook-f"></i>
                            </span>
                            <input type="url"
                                   name="footer_facebook_url"
                                   value="{{ old('footer_facebook_url', $settings['footer_facebook_url'] ?? '') }}"
                                   placeholder="Facebook Page URL (e.g. https://facebook.com/digigo)"
                                   class="w-full h-9 px-3 text-xs rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 font-mono">
                        </div>

                        <!-- Instagram -->
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-gradient-to-tr from-amber-400 via-rose-500 to-purple-600 text-white flex items-center justify-center shrink-0 text-sm">
                                <i class="fa-brands fa-instagram"></i>
                            </span>
                            <input type="url"
                                   name="footer_instagram_url"
                                   value="{{ old('footer_instagram_url', $settings['footer_instagram_url'] ?? '') }}"
                                   placeholder="Instagram URL (e.g. https://instagram.com/digigo)"
                                   class="w-full h-9 px-3 text-xs rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 font-mono">
                        </div>

                        <!-- WhatsApp -->
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-[#25d366] text-white flex items-center justify-center shrink-0 text-sm">
                                <i class="fa-brands fa-whatsapp"></i>
                            </span>
                            <input type="text"
                                   name="footer_whatsapp_url"
                                   value="{{ old('footer_whatsapp_url', $settings['footer_whatsapp_url'] ?? '') }}"
                                   placeholder="WhatsApp Link or URL (e.g. https://wa.me/8801600793325)"
                                   class="w-full h-9 px-3 text-xs rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 font-mono">
                        </div>

                        <!-- YouTube -->
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-[#ff0000] text-white flex items-center justify-center shrink-0 text-sm">
                                <i class="fa-brands fa-youtube"></i>
                            </span>
                            <input type="url"
                                   name="footer_youtube_url"
                                   value="{{ old('footer_youtube_url', $settings['footer_youtube_url'] ?? '') }}"
                                   placeholder="YouTube Channel URL (Optional)"
                                   class="w-full h-9 px-3 text-xs rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 font-mono">
                        </div>

                        <!-- Twitter / X -->
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center shrink-0 text-sm border border-slate-700">
                                <i class="fa-brands fa-x-twitter"></i>
                            </span>
                            <input type="url"
                                   name="footer_twitter_url"
                                   value="{{ old('footer_twitter_url', $settings['footer_twitter_url'] ?? '') }}"
                                   placeholder="X (Twitter) Profile URL (Optional)"
                                   class="w-full h-9 px-3 text-xs rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 font-mono">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- =========================================================================
             TAB 4: COPYRIGHT, PAYMENT BADGES & FLOATING WIDGET
             ========================================================================= -->
        <div id="tab-copyright-payments" class="footer-tab-pane hidden space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Bottom Copyright Bar -->
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-xs space-y-5">
                    <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3">
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Bottom Bar & Copyright</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Control the copyright disclaimer text at the very bottom.</p>
                    </div>

                    <div>
                        <label for="footer_copyright_text" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">
                            Copyright Disclaimer Text <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               id="footer_copyright_text"
                               name="footer_copyright_text"
                               value="{{ old('footer_copyright_text', $settings['footer_copyright_text'] ?? 'DigiGo Copyright 2018-2026 All Right Reserved.') }}"
                               placeholder="DigiGo Copyright 2018-2026 All Right Reserved."
                               required
                               class="w-full h-10 px-3.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 transition">
                        <p class="text-[11px] text-slate-400 mt-1">Displayed on the left side of the bottom footer ribbon.</p>
                    </div>

                    <!-- Floating Chat Widget -->
                    <div class="border-t border-slate-100 dark:border-slate-700/60 pt-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Floating Support Chat Widget</h3>
                                <p class="text-[11px] text-slate-400">Circular floating chat button at the bottom-right of every public page.</p>
                            </div>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="footer_floating_chat_enabled" value="1" class="sr-only peer" {{ old('footer_floating_chat_enabled', $settings['footer_floating_chat_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-brand-600"></div>
                            </label>
                        </div>

                        <div>
                            <label for="footer_floating_chat_link" class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 mb-1">
                                Chat Destination Link (WhatsApp / Messenger)
                            </label>
                            <input type="text"
                                   id="footer_floating_chat_link"
                                   name="footer_floating_chat_link"
                                   value="{{ old('footer_floating_chat_link', $settings['footer_floating_chat_link'] ?? 'https://wa.me/8801600793325') }}"
                                   placeholder="https://wa.me/8801600793325"
                                   class="w-full h-9 px-3 text-xs rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600 font-mono">
                        </div>
                    </div>
                </div>

                <!-- Payment Methods Badges -->
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-xs space-y-5">
                    <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Payment Method Badges</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Toggle and display trusted payment provider badges.</p>
                        </div>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="footer_payment_methods_enabled" value="1" class="sr-only peer" {{ old('footer_payment_methods_enabled', $settings['footer_payment_methods_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-brand-600"></div>
                        </label>
                    </div>

                    <div class="space-y-3 pt-1">
                        <!-- bKash -->
                        <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/40">
                            <div class="flex items-center gap-3">
                                <span class="bg-white text-[#d12053] font-extrabold text-xs px-2.5 py-1 rounded shadow-xs border border-slate-200">bKash</span>
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">bKash Payment Badge</span>
                            </div>
                            <input type="checkbox" name="footer_payment_bkash" value="1" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500" {{ old('footer_payment_bkash', $settings['footer_payment_bkash'] ?? '1') === '1' ? 'checked' : '' }}>
                        </div>

                        <!-- Nagad -->
                        <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/40">
                            <div class="flex items-center gap-3">
                                <span class="bg-white text-[#f7941d] font-extrabold text-xs px-2.5 py-1 rounded shadow-xs border border-slate-200">নগদ</span>
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Nagad Payment Badge</span>
                            </div>
                            <input type="checkbox" name="footer_payment_nagad" value="1" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500" {{ old('footer_payment_nagad', $settings['footer_payment_nagad'] ?? '1') === '1' ? 'checked' : '' }}>
                        </div>

                        <!-- Rocket -->
                        <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/40">
                            <div class="flex items-center gap-3">
                                <span class="bg-white text-[#8c3494] font-extrabold text-xs px-2.5 py-1 rounded shadow-xs border border-slate-200">rocket</span>
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Rocket Payment Badge</span>
                            </div>
                            <input type="checkbox" name="footer_payment_rocket" value="1" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500" {{ old('footer_payment_rocket', $settings['footer_payment_rocket'] ?? '1') === '1' ? 'checked' : '' }}>
                        </div>

                        <!-- Upay -->
                        <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/40">
                            <div class="flex items-center gap-3">
                                <span class="bg-white text-[#005baa] font-extrabold text-xs px-2.5 py-1 rounded shadow-xs border border-slate-200">upay</span>
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Upay Payment Badge</span>
                            </div>
                            <input type="checkbox" name="footer_payment_upay" value="1" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500" {{ old('footer_payment_upay', $settings['footer_payment_upay'] ?? '1') === '1' ? 'checked' : '' }}>
                        </div>

                        <!-- VISA / Mastercard -->
                        <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/40">
                            <div class="flex items-center gap-3">
                                <span class="bg-[#0284c7] text-white font-extrabold text-xs px-2.5 py-1 rounded shadow-xs">VISA / Master</span>
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Cards (Visa / MasterCard)</span>
                            </div>
                            <input type="checkbox" name="footer_payment_cards" value="1" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500" {{ old('footer_payment_cards', $settings['footer_payment_cards'] ?? '1') === '1' ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Sticky Bottom Save Bar -->
        <div class="p-4 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-between shadow-xs">
            <p class="text-xs text-slate-500 dark:text-slate-400">Click save to immediately update all footer content across the entire store.</p>
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-sm transition cursor-pointer">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save All Footer Settings</span>
            </button>
        </div>

    </form>

</div>

<!-- Tab Switching & Logo Preview Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const tabBtns = document.querySelectorAll('.footer-tab-btn');
    const tabPanes = document.querySelectorAll('.footer-tab-pane');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const targetTab = btn.getAttribute('data-tab');

            // Toggle active classes on tab buttons
            tabBtns.forEach(b => {
                b.classList.remove('bg-brand-600', 'text-white', 'font-bold', 'shadow-sm');
                b.classList.add('bg-slate-100', 'text-slate-700', 'hover:bg-slate-200', 'dark:bg-slate-800', 'dark:text-slate-300', 'font-semibold');
            });
            btn.classList.add('bg-brand-600', 'text-white', 'font-bold', 'shadow-sm');
            btn.classList.remove('bg-slate-100', 'text-slate-700', 'hover:bg-slate-200', 'dark:bg-slate-800', 'dark:text-slate-300');

            // Show corresponding pane
            tabPanes.forEach(pane => {
                if (pane.id === targetTab) {
                    pane.classList.remove('hidden');
                } else {
                    pane.classList.add('hidden');
                }
            });
        });
    });

    // File Preview for Custom Footer Logo
    const fileInput = document.getElementById('footer_custom_logo');
    const previewImg = document.getElementById('footerLogoPreviewImg');
    const defaultPlaceholder = document.getElementById('footerLogoDefaultPlaceholder');

    fileInput?.addEventListener('change', function(e) {
        const file = e.target.files?.[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(evt) {
                if (previewImg) {
                    previewImg.src = evt.target.result;
                    previewImg.classList.remove('hidden');
                }
                if (defaultPlaceholder) {
                    defaultPlaceholder.classList.add('hidden');
                }
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endsection
