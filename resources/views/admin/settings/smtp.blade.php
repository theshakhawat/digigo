@extends('layouts.admin')

@section('title', 'SMTP & Email Settings')

@section('admin_content')
<div class="space-y-6">

    <!-- Top Action & Breadcrumb Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
                <span>/</span>
                <span>Configuration</span>
                <span>/</span>
                <span class="text-slate-800 dark:text-slate-200 font-medium">SMTP Settings</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">SMTP & Email Configuration</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Configure your outgoing email server (SMTP), credentials, sender profile, and test live email delivery.</p>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" form="smtpSettingsForm" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-sm transition cursor-pointer">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save SMTP Settings</span>
            </button>
        </div>
    </div>

    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 flex items-start gap-3 text-emerald-800 dark:text-emerald-300 text-sm shadow-xs">
            <i class="fa-solid fa-circle-check text-lg shrink-0 text-emerald-600 dark:text-emerald-400 mt-0.5"></i>
            <div>
                <p class="font-bold">Success</p>
                <p class="text-xs mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 flex items-start gap-3 text-rose-800 dark:text-rose-300 text-sm shadow-xs">
            <i class="fa-solid fa-circle-exclamation text-lg shrink-0 text-rose-600 dark:text-rose-400 mt-0.5"></i>
            <div>
                <p class="font-bold">SMTP Test Failed</p>
                <p class="text-xs mt-0.5 font-mono break-all">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm shadow-xs space-y-1">
            <div class="flex items-center gap-2 font-bold text-rose-700 dark:text-rose-400">
                <i class="fa-solid fa-triangle-exclamation text-base"></i>
                <span>Please correct the errors below:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5 text-rose-600 dark:text-rose-300 pl-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left 2 Cols: Main SMTP Settings Form -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Presets Banner -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-950 text-brand-600 dark:text-brand-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Quick Configuration Presets</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Click a preset to quickly fill the recommended host, port, and encryption.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-1">
                    <button type="button" onclick="applyPreset('gmail')" class="flex flex-col items-center justify-center p-3 rounded-lg border border-slate-200 dark:border-slate-700 hover:border-brand-500 hover:bg-brand-50/50 dark:hover:bg-slate-800/80 transition group text-center cursor-pointer">
                        <i class="fa-brands fa-google text-red-500 text-base mb-1 group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">Gmail</span>
                        <span class="text-[10px] text-slate-400">Port 587 / TLS</span>
                    </button>

                    <button type="button" onclick="applyPreset('outlook')" class="flex flex-col items-center justify-center p-3 rounded-lg border border-slate-200 dark:border-slate-700 hover:border-brand-500 hover:bg-brand-50/50 dark:hover:bg-slate-800/80 transition group text-center cursor-pointer">
                        <i class="fa-brands fa-microsoft text-blue-500 text-base mb-1 group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">Outlook 365</span>
                        <span class="text-[10px] text-slate-400">Port 587 / STARTTLS</span>
                    </button>

                    <button type="button" onclick="applyPreset('mailtrap')" class="flex flex-col items-center justify-center p-3 rounded-lg border border-slate-200 dark:border-slate-700 hover:border-brand-500 hover:bg-brand-50/50 dark:hover:bg-slate-800/80 transition group text-center cursor-pointer">
                        <i class="fa-solid fa-flask text-emerald-500 text-base mb-1 group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">Mailtrap</span>
                        <span class="text-[10px] text-slate-400">Port 2525 / Sandbox</span>
                    </button>

                    <button type="button" onclick="applyPreset('custom')" class="flex flex-col items-center justify-center p-3 rounded-lg border border-slate-200 dark:border-slate-700 hover:border-brand-500 hover:bg-brand-50/50 dark:hover:bg-slate-800/80 transition group text-center cursor-pointer">
                        <i class="fa-solid fa-server text-indigo-500 text-base mb-1 group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">cPanel / Custom</span>
                        <span class="text-[10px] text-slate-400">Port 465 / SSL</span>
                    </button>
                </div>
            </div>

            <!-- Form Card -->
            <form id="smtpSettingsForm" action="{{ route('admin.settings.smtp.update') }}" method="POST" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-xs space-y-6">
                @csrf
                @method('PUT')

                <!-- Section 1: Server Connection -->
                <div>
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">
                        <i class="fa-solid fa-network-wired text-brand-600 dark:text-brand-400"></i>
                        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-200">Mail Server Connection</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Mail Driver -->
                        <div>
                            <label for="mail_mailer" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                Mail Driver / Transport <span class="text-rose-500">*</span>
                            </label>
                            <select id="mail_mailer" name="mail_mailer" class="w-full px-3.5 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none focus:border-brand-600 focus:bg-white dark:focus:bg-slate-900 transition">
                                <option value="smtp" {{ old('mail_mailer', $settings['mail_mailer'] ?? 'smtp') === 'smtp' ? 'selected' : '' }}>SMTP (Standard Outgoing Mail)</option>
                                <option value="sendmail" {{ old('mail_mailer', $settings['mail_mailer'] ?? '') === 'sendmail' ? 'selected' : '' }}>Sendmail (System Binary)</option>
                                <option value="log" {{ old('mail_mailer', $settings['mail_mailer'] ?? '') === 'log' ? 'selected' : '' }}>Log (Testing / Write to laravel.log)</option>
                            </select>
                            <p class="text-[11px] text-slate-400 mt-1">Select "SMTP" for commercial and external email delivery.</p>
                        </div>

                        <!-- Mail Encryption -->
                        <div>
                            <label for="mail_encryption" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                Encryption Protocol
                            </label>
                            <select id="mail_encryption" name="mail_encryption" class="w-full px-3.5 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none focus:border-brand-600 focus:bg-white dark:focus:bg-slate-900 transition">
                                <option value="tls" {{ old('mail_encryption', $settings['mail_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS (STARTTLS - Recommended for Port 587/2525)</option>
                                <option value="ssl" {{ old('mail_encryption', $settings['mail_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL (Direct SMTPS - Recommended for Port 465)</option>
                                <option value="none" {{ old('mail_encryption', $settings['mail_encryption'] ?? '') === 'none' ? 'selected' : '' }}>None (Unencrypted)</option>
                            </select>
                            <p class="text-[11px] text-slate-400 mt-1">TLS is recommended for Port 587; SSL for Port 465.</p>
                        </div>

                        <!-- Host -->
                        <div>
                            <label for="mail_host" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                SMTP Host Server
                            </label>
                            <input type="text"
                                   id="mail_host"
                                   name="mail_host"
                                   value="{{ old('mail_host', $settings['mail_host'] ?? '') }}"
                                   placeholder="e.g. smtp.gmail.com or mail.digigo.com.bd"
                                   class="w-full px-3.5 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none focus:border-brand-600 focus:bg-white dark:focus:bg-slate-900 transition font-mono">
                            <p class="text-[11px] text-slate-400 mt-1">Your mail server hostname or IP address.</p>
                        </div>

                        <!-- Port -->
                        <div>
                            <label for="mail_port" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                SMTP Port
                            </label>
                            <input type="number"
                                   id="mail_port"
                                   name="mail_port"
                                   value="{{ old('mail_port', $settings['mail_port'] ?? '587') }}"
                                   placeholder="587"
                                   class="w-full px-3.5 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none focus:border-brand-600 focus:bg-white dark:focus:bg-slate-900 transition font-mono">
                            <p class="text-[11px] text-slate-400 mt-1">Standard ports: 587 (TLS), 465 (SSL), 2525 (Mailtrap), 25 (cPanel).</p>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Authentication Credentials -->
                <div>
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">
                        <i class="fa-solid fa-key text-brand-600 dark:text-brand-400"></i>
                        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-200">Authentication Credentials</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Username -->
                        <div>
                            <label for="mail_username" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                SMTP Username / Account Email
                            </label>
                            <input type="text"
                                   id="mail_username"
                                   name="mail_username"
                                   value="{{ old('mail_username', $settings['mail_username'] ?? '') }}"
                                   placeholder="e.g. info@digigo.com.bd or your-email@gmail.com"
                                   autocomplete="off"
                                   class="w-full px-3.5 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none focus:border-brand-600 focus:bg-white dark:focus:bg-slate-900 transition">
                            <p class="text-[11px] text-slate-400 mt-1">Account login email or API client username.</p>
                        </div>

                        <!-- Password -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="mail_password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                    SMTP Password / App Password
                                </label>
                                @if(!empty($settings['mail_password']))
                                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-600 dark:text-emerald-400">
                                        <i class="fa-solid fa-shield-halved"></i> Configured
                                    </span>
                                @endif
                            </div>
                            <div class="relative">
                                <input type="password"
                                       id="mail_password"
                                       name="mail_password"
                                       placeholder="{{ !empty($settings['mail_password']) ? '•••••••••••••••• (Leave blank to keep existing)' : 'Enter password or App Password' }}"
                                       autocomplete="new-password"
                                       class="w-full px-3.5 py-2 pr-10 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none focus:border-brand-600 focus:bg-white dark:focus:bg-slate-900 transition font-mono">
                                <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                                    <i id="passwordEyeIcon" class="fa-solid fa-eye text-xs"></i>
                                </button>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Leave empty to keep the currently stored password unchanged.</p>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Sender Identity -->
                <div>
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">
                        <i class="fa-solid fa-envelope-open-text text-brand-600 dark:text-brand-400"></i>
                        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-200">Default Sender Identity (From)</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- From Email -->
                        <div>
                            <label for="mail_from_address" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                Sender Email Address (From Address) <span class="text-rose-500">*</span>
                            </label>
                            <input type="email"
                                   id="mail_from_address"
                                   name="mail_from_address"
                                   value="{{ old('mail_from_address', $settings['mail_from_address'] ?? 'no-reply@digigo.com.bd') }}"
                                   placeholder="no-reply@digigo.com.bd"
                                   required
                                   class="w-full px-3.5 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none focus:border-brand-600 focus:bg-white dark:focus:bg-slate-900 transition">
                            <p class="text-[11px] text-slate-400 mt-1">The email address recipients will see in their inbox header.</p>
                        </div>

                        <!-- From Name -->
                        <div>
                            <label for="mail_from_name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                Sender Display Name (From Name) <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   id="mail_from_name"
                                   name="mail_from_name"
                                   value="{{ old('mail_from_name', $settings['mail_from_name'] ?? 'DigiGo') }}"
                                   placeholder="DigiGo"
                                   required
                                   class="w-full px-3.5 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none focus:border-brand-600 focus:bg-white dark:focus:bg-slate-900 transition">
                            <p class="text-[11px] text-slate-400 mt-1">e.g. "DigiGo Support" or your store name.</p>
                        </div>
                    </div>
                </div>

                <!-- Footer Save Action -->
                <div class="pt-2 flex items-center justify-between border-t border-slate-100 dark:border-slate-800">
                    <p class="text-xs text-slate-400">Settings take effect immediately upon saving.</p>
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-sm transition cursor-pointer">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>Save SMTP Configuration</span>
                    </button>
                </div>
            </form>

        </div>

        <!-- Right 1 Col: Test Email Card & Guidelines -->
        <div class="space-y-6">

            <!-- Send Test Email Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-xs">
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-paper-plane"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Send Test Email</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Verify your SMTP settings live</p>
                    </div>
                </div>

                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                    Send a diagnostic test email to any inbox using the currently configured SMTP parameters to ensure authentication and delivery are working properly.
                </p>

                <form action="{{ route('admin.settings.smtp.test') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label for="test_email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Recipient Email Address <span class="text-rose-500">*</span>
                        </label>
                        <input type="email"
                               id="test_email"
                               name="test_email"
                               value="{{ old('test_email', auth()->user()->email ?? '') }}"
                               placeholder="your-email@example.com"
                               required
                               class="w-full px-3.5 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none focus:border-brand-600 focus:bg-white dark:focus:bg-slate-900 transition">
                    </div>

                    <div class="pt-0.5">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="send_via_queue" value="1" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                            <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Test via Background Queue (Job Pipeline)</span>
                        </label>
                        <p class="text-[11px] text-slate-400 mt-0.5 pl-6">Dispatches email to the background queue table instead of synchronous send.</p>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs transition cursor-pointer">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        <span>Send Test Email Now</span>
                    </button>
                </form>
            </div>

            <!-- Current Status Overview Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-xs">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">
                    Live System & Queue Status
                </h3>

                <ul class="space-y-2.5 text-xs">
                    <li class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Queue Driver:</span>
                        <span class="font-bold text-emerald-600 dark:text-emerald-400 font-mono uppercase flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block animate-pulse"></span>
                            {{ config('queue.default', 'database') }}
                        </span>
                    </li>
                    <li class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Pending Jobs:</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 font-mono">{{ $pendingJobsCount ?? 0 }}</span>
                    </li>
                    <li class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Failed Jobs:</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 font-mono">{{ $failedJobsCount ?? 0 }}</span>
                    </li>
                    <li class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Default Mailer:</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 font-mono uppercase">{{ config('mail.default') }}</span>
                    </li>
                    <li class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Current Host:</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 font-mono">{{ config('mail.mailers.smtp.host') ?: 'Not configured' }}</span>
                    </li>
                    <li class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Current Port:</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 font-mono">{{ config('mail.mailers.smtp.port') }}</span>
                    </li>
                    <li class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Default From:</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200 truncate max-w-[150px]">{{ config('mail.from.address') }}</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Password Status:</span>
                        @if(!empty($settings['mail_password']))
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">Saved</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300">Not Set</span>
                        @endif
                    </li>
                </ul>
            </div>

            <!-- Helpful Tips Card -->
            <div class="bg-blue-50/60 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-900/60 rounded-xl p-5 text-xs text-blue-900 dark:text-blue-200 space-y-2.5">
                <div class="flex items-center gap-2 font-bold text-blue-800 dark:text-blue-300">
                    <i class="fa-solid fa-lightbulb"></i>
                    <span>Important Setup Notes</span>
                </div>
                <ul class="list-disc list-inside space-y-1.5 text-[11px] leading-relaxed text-blue-800/90 dark:text-blue-300/90">
                    <li><strong>Gmail:</strong> You must create a Google <em>App Password</em> (16-character code) from your Google Account Security settings. Your normal account password will be rejected.</li>
                    <li><strong>cPanel / Webmail:</strong> Typically use <code>mail.yourdomain.com</code> with Port <code>465</code> and <code>SSL</code> encryption.</li>
                    <li><strong>Outlook 365:</strong> Use <code>smtp.office365.com</code>, Port <code>587</code>, and <code>TLS</code> encryption.</li>
                </ul>
            </div>

        </div>

    </div>

</div>

<!-- JavaScript for Quick Presets & Show/Hide Password -->
<script>
function togglePasswordVisibility() {
    const input = document.getElementById('mail_password');
    const icon = document.getElementById('passwordEyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function applyPreset(preset) {
    const mailer = document.getElementById('mail_mailer');
    const host = document.getElementById('mail_host');
    const port = document.getElementById('mail_port');
    const encryption = document.getElementById('mail_encryption');

    mailer.value = 'smtp';

    switch (preset) {
        case 'gmail':
            host.value = 'smtp.gmail.com';
            port.value = '587';
            encryption.value = 'tls';
            break;
        case 'outlook':
            host.value = 'smtp.office365.com';
            port.value = '587';
            encryption.value = 'tls';
            break;
        case 'mailtrap':
            host.value = 'sandbox.smtp.mailtrap.io';
            port.value = '2525';
            encryption.value = 'tls';
            break;
        case 'custom':
            host.value = '';
            port.value = '465';
            encryption.value = 'ssl';
            break;
    }

    // Flash border highlight for visual confirmation
    [host, port, encryption].forEach(el => {
        el.classList.add('ring-2', 'ring-brand-500');
        setTimeout(() => el.classList.remove('ring-2', 'ring-brand-500'), 800);
    });
}
</script>
@endsection
