@extends('layouts.admin')

@section('title', 'Manage Subscription #' . $subscription->subscription_code . ' - DigiGo')

@section('admin_content')
<!-- Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.subscriptions.index') }}" class="w-9 h-9 rounded-lg border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800 transition">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white flex items-center gap-2.5">
                <span>Subscription #{{ $subscription->subscription_code }}</span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Created on {{ $subscription->created_at->format('l, d F Y - h:i A') }} ({{ $subscription->created_at->diffForHumans() }})
            </p>
        </div>
    </div>

    <div class="flex items-center gap-2.5">
        <form action="{{ route('admin.subscriptions.destroy', $subscription->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this subscription?');" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center gap-2 px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs sm:text-sm font-semibold rounded-lg shadow-sm transition cursor-pointer">
                <i class="fa-regular fa-trash-can"></i>
                <span>Delete</span>
            </button>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-2 text-sm dark:bg-emerald-950/50 dark:border-emerald-800 dark:text-emerald-300">
        <i class="fa-solid fa-circle-check text-emerald-600"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<form action="{{ route('admin.subscriptions.update', $subscription->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Left 8 Cols: Credentials & Provisioning Form -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Card: Fulfillment Details -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-xs space-y-5">
                <div class="pb-3 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-slate-900 dark:text-white text-base flex items-center gap-2">
                            <i class="fa-solid fa-key text-brand-600"></i>
                            <span>Subscription Credentials & Delivery</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Input the customer's account credentials, license keys, or access details.</p>
                    </div>
                </div>

                <!-- Status Selector -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            Subscription Status *
                        </label>
                        @if($subscription->isExpired())
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-200">
                                <i class="fa-solid fa-hourglass-end text-[10px]"></i> Currently Past Expiration Date
                            </span>
                        @endif
                    </div>
                    <select name="status" class="w-full h-11 px-3 text-sm font-bold rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-brand-500">
                        <option value="pending" {{ old('status', $subscription->effective_status) === 'pending' ? 'selected' : '' }}>🟡 Pending Activation</option>
                        <option value="active" {{ old('status', $subscription->effective_status) === 'active' ? 'selected' : '' }}>🟢 Active (Customer can view credentials)</option>
                        <option value="expired" {{ old('status', $subscription->effective_status) === 'expired' ? 'selected' : '' }}>⚪ Expired</option>
                        <option value="cancelled" {{ old('status', $subscription->effective_status) === 'cancelled' ? 'selected' : '' }}>🔴 Cancelled</option>
                    </select>
                </div>

                <!-- Credentials (Email / Password / Profile Pin) -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                        Account Credentials (Email, Username, Password, Profile PIN, etc.)
                    </label>
                    <textarea name="credentials" rows="4" placeholder="Email: user@example.com&#10;Password: SecretPass123&#10;Profile: Slot 2 (PIN: 1234)" class="w-full p-3 font-mono text-xs sm:text-sm rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-brand-500">{{ old('credentials', $subscription->credentials) }}</textarea>
                    <p class="text-[11px] text-slate-400 mt-1">This will be formatted nicely on the user's dashboard with a one-click copy button.</p>
                </div>

                <!-- License Key -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                        License Key / Serial Code (If Applicable)
                    </label>
                    <input type="text" name="license_key" value="{{ old('license_key', $subscription->license_key) }}" placeholder="e.g. XXXX-YYYY-ZZZZ-1234" class="w-full h-11 px-3 font-mono text-xs sm:text-sm rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-brand-500">
                </div>

                <!-- Login URL -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                        Service Direct Login URL
                    </label>
                    <input type="url" name="login_url" value="{{ old('login_url', $subscription->login_url) }}" placeholder="https://www.netflix.com or https://chatgpt.com" class="w-full h-11 px-3 text-xs sm:text-sm rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-brand-500">
                </div>

                <!-- Access Guidelines -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                        Access Instructions & Rules
                    </label>
                    <textarea name="access_instructions" rows="3" placeholder="Please do not change password or email. Use profile 2 only." class="w-full p-3 text-xs sm:text-sm rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-brand-500">{{ old('access_instructions', $subscription->access_instructions) }}</textarea>
                </div>

                <!-- Dates: Validity, Starts At, Expires At -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 border-t border-slate-100 dark:border-slate-800">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Validity Label</label>
                        <input type="text" name="validity" value="{{ old('validity', $subscription->validity) }}" placeholder="1 Month" class="w-full h-10 px-3 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Starts At</label>
                        <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $subscription->starts_at?->format('Y-m-d\TH:i')) }}" class="w-full h-10 px-3 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Expires At</label>
                        <input type="datetime-local" name="expires_at" value="{{ old('expires_at', $subscription->expires_at?->format('Y-m-d\TH:i')) }}" class="w-full h-10 px-3 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100">
                    </div>
                </div>

                <!-- Admin Internal Note -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                        Internal Admin Notes (Private)
                    </label>
                    <textarea name="admin_note" rows="2" placeholder="Bought from supplier X, renewed on..." class="w-full p-2.5 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100">{{ old('admin_note', $subscription->admin_note) }}</textarea>
                </div>

                <div class="pt-3 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-brand-800 hover:bg-brand-700 text-white font-bold text-sm rounded-lg shadow-sm transition dark:bg-brand-600 dark:hover:bg-brand-700 cursor-pointer">
                        <i class="fa-solid fa-floppy-disk mr-1.5"></i> Save & Update Subscription
                    </button>
                </div>
            </div>

        </div>

        <!-- Right 4 Cols: Product & Customer Info -->
        <div class="lg:col-span-4 space-y-6">

            <!-- Product Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-xs space-y-3">
                <h3 class="font-bold text-slate-900 dark:text-white text-sm pb-2 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <span>Product & Plan</span>
                    <span class="text-xs text-brand-600 font-extrabold">৳{{ number_format($subscription->price, 2) }}</span>
                </h3>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden shrink-0 flex items-center justify-center">
                        @if($subscription->product && $subscription->product->thumbnail_url)
                            <img src="{{ $subscription->product->thumbnail_url }}" alt="{{ $subscription->product_name }}" class="w-full h-full object-cover">
                        @else
                            <i class="fa-solid fa-box text-slate-400"></i>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 dark:text-white text-sm">{{ $subscription->product_name }}</h4>
                        @if($subscription->plan_name)
                            <span class="text-xs text-slate-500 font-medium">Plan: {{ $subscription->plan_name }}</span>
                        @endif
                    </div>
                </div>

                @if($subscription->order)
                    <div class="pt-2 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-500 space-y-1">
                        <div class="flex justify-between">
                            <span>Order Number:</span>
                            <a href="{{ route('admin.orders.show', $subscription->order_id) }}" class="font-mono text-brand-600 font-bold hover:underline">
                                #{{ $subscription->order->order_number }}
                            </a>
                        </div>
                        <div class="flex justify-between">
                            <span>Payment Method:</span>
                            <span class="uppercase font-semibold text-slate-700 dark:text-slate-300">{{ $subscription->order->payment_method }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Target Subscription Destination (Important) -->
            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-950/40 dark:to-blue-950/30 border-2 border-indigo-200 dark:border-indigo-800 rounded-xl p-5 shadow-xs space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-indigo-200 dark:border-indigo-800">
                    <h3 class="font-bold text-indigo-950 dark:text-indigo-200 text-sm flex items-center gap-1.5">
                        <i class="fa-solid fa-paper-plane text-indigo-600 dark:text-indigo-400"></i>
                        <span>Activation Target</span>
                    </h3>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-indigo-600 text-white uppercase">Deliver Here</span>
                </div>

                <div class="space-y-2 text-xs">
                    <div>
                        <span class="text-slate-500 dark:text-slate-400 block text-[11px] font-semibold">Target Gmail / Email:</span>
                        <div class="flex items-center justify-between mt-0.5 bg-white dark:bg-slate-900 border border-indigo-200 dark:border-indigo-800 rounded-lg p-2 font-mono font-bold text-indigo-700 dark:text-indigo-300">
                            <span class="select-all">{{ $subscription->target_email ?: ($subscription->user?->email ?? 'Not specified') }}</span>
                            @if($subscription->target_email || $subscription->user?->email)
                                <button type="button" onclick="navigator.clipboard.writeText('{{ $subscription->target_email ?: $subscription->user?->email }}'); alert('Copied target email to clipboard!');" class="text-slate-400 hover:text-indigo-600 ml-2" title="Copy email">
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    @if($subscription->target_phone)
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 block text-[11px] font-semibold">Target Phone / WhatsApp:</span>
                            <div class="font-mono font-bold text-slate-800 dark:text-slate-200 mt-0.5 select-all">
                                {{ $subscription->target_phone }}
                            </div>
                        </div>
                    @endif

                    @if($subscription->order && $subscription->order->digital_account_notes)
                        <div class="pt-2 border-t border-indigo-200 dark:border-indigo-800">
                            <span class="text-slate-500 dark:text-slate-400 block text-[11px] font-semibold">Buyer Instructions:</span>
                            <p class="font-medium text-slate-800 dark:text-slate-200 italic mt-0.5">
                                "{{ $subscription->order->digital_account_notes }}"
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Customer Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-xs space-y-3">
                <h3 class="font-bold text-slate-900 dark:text-white text-sm pb-2 border-b border-slate-100 dark:border-slate-800">
                    Customer Information
                </h3>
                @if($subscription->user)
                    <div class="space-y-1.5 text-xs text-slate-600 dark:text-slate-400">
                        <div class="font-bold text-slate-900 dark:text-white text-sm">{{ $subscription->user->name }}</div>
                        <div>Email: <a href="mailto:{{ $subscription->user->email }}" class="text-brand-600 hover:underline">{{ $subscription->user->email }}</a></div>
                        @if($subscription->user->phone)
                            <div>Phone: <strong>{{ $subscription->user->phone }}</strong></div>
                        @endif
                        <div class="pt-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">Registered Customer</span>
                        </div>
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">No user linked to this subscription.</p>
                @endif
            </div>

        </div>

    </div>
</form>
@endsection

