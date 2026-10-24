@extends('layouts.admin')

@section('title', 'Edit Order #' . $order->order_number . ' - DigiGo')

@section('admin_content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.orders.show', $order->id) }}" class="w-9 h-9 rounded-lg border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800 transition">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white flex items-center gap-2.5">
                <i class="fa-regular fa-pen-to-square text-amber-500"></i>
                <span>Edit Order #{{ $order->order_number }}</span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Modify order fulfillment, payment status, customer information and delivery address.
            </p>
        </div>
    </div>
</div>

<form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Left Column: Customer & Delivery Details (Col 8) -->
        <div class="lg:col-span-8 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-xs space-y-5">
            <h3 class="font-bold text-slate-900 dark:text-white text-base pb-3 border-b border-slate-100 dark:border-slate-800">
                Customer & Shipping Details
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="customer_name" class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1.5">Customer Name *</label>
                    <input type="text"
                           name="customer_name"
                           id="customer_name"
                           value="{{ old('customer_name', $order->customer_name) }}"
                           class="w-full h-11 px-3.5 rounded-lg border border-slate-300 bg-white text-sm focus:outline-none focus:border-brand-600 dark:bg-slate-800 dark:border-slate-700 dark:text-white"
                           required>
                    @error('customer_name')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="customer_email" class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1.5">Customer Email *</label>
                    <input type="email"
                           name="customer_email"
                           id="customer_email"
                           value="{{ old('customer_email', $order->customer_email) }}"
                           class="w-full h-11 px-3.5 rounded-lg border border-slate-300 bg-white text-sm focus:outline-none focus:border-brand-600 dark:bg-slate-800 dark:border-slate-700 dark:text-white"
                           required>
                    @error('customer_email')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="customer_phone" class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1.5">Phone / WhatsApp Number *</label>
                <input type="text"
                       name="customer_phone"
                       id="customer_phone"
                       value="{{ old('customer_phone', $order->customer_phone) }}"
                       class="w-full h-11 px-3.5 rounded-lg border border-slate-300 bg-white text-sm focus:outline-none focus:border-brand-600 dark:bg-slate-800 dark:border-slate-700 dark:text-white"
                       required>
                @error('customer_phone')
                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="division" class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1.5">Division *</label>
                    <input type="text"
                           name="division"
                           id="division"
                           value="{{ old('division', $order->division) }}"
                           class="w-full h-11 px-3.5 rounded-lg border border-slate-300 bg-white text-sm focus:outline-none focus:border-brand-600 dark:bg-slate-800 dark:border-slate-700 dark:text-white"
                           required>
                    @error('division')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="district" class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1.5">District *</label>
                    <input type="text"
                           name="district"
                           id="district"
                           value="{{ old('district', $order->district) }}"
                           class="w-full h-11 px-3.5 rounded-lg border border-slate-300 bg-white text-sm focus:outline-none focus:border-brand-600 dark:bg-slate-800 dark:border-slate-700 dark:text-white"
                           required>
                    @error('district')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="street_address" class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1.5">Full Street Address *</label>
                <textarea name="street_address"
                          id="street_address"
                          rows="3"
                          class="w-full p-3.5 rounded-lg border border-slate-300 bg-white text-sm focus:outline-none focus:border-brand-600 dark:bg-slate-800 dark:border-slate-700 dark:text-white"
                          required>{{ old('street_address', $order->street_address) }}</textarea>
                @error('street_address')
                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="postal_code" class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1.5">Postal Code</label>
                    <input type="text"
                           name="postal_code"
                           id="postal_code"
                           value="{{ old('postal_code', $order->postal_code) }}"
                           class="w-full h-11 px-3.5 rounded-lg border border-slate-300 bg-white text-sm focus:outline-none focus:border-brand-600 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                </div>

                <div>
                    <label for="payment_method" class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1.5">Payment Method *</label>
                    <select name="payment_method" id="payment_method" class="w-full h-11 px-3.5 rounded-lg border border-slate-300 bg-white text-sm focus:outline-none focus:border-brand-600 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                        <option value="cod" {{ old('payment_method', $order->payment_method) === 'cod' ? 'selected' : '' }}>Cash on Delivery (COD)</option>
                        <option value="dgepay" {{ old('payment_method', $order->payment_method) === 'dgepay' ? 'selected' : '' }}>DGePay Online</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="notes" class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1.5">Order Notes</label>
                <textarea name="notes"
                          id="notes"
                          rows="2"
                          class="w-full p-3.5 rounded-lg border border-slate-300 bg-white text-sm focus:outline-none focus:border-brand-600 dark:bg-slate-800 dark:border-slate-700 dark:text-white">{{ old('notes', $order->notes) }}</textarea>
            </div>
        </div>

        <!-- Right Column: Status & Save (Col 4) -->
        <div class="lg:col-span-4 space-y-6">

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-xs space-y-4">
                <h3 class="font-bold text-slate-900 dark:text-white text-sm pb-2 border-b border-slate-100 dark:border-slate-800">
                    Order Statuses
                </h3>

                <div>
                    <label for="order_status" class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1.5">Order Fulfillment *</label>
                    <select name="order_status" id="order_status" class="w-full h-11 px-3.5 rounded-lg border border-slate-300 bg-white text-sm focus:outline-none focus:border-brand-600 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                        <option value="pending" {{ old('order_status', $order->order_status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ old('order_status', $order->order_status) === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="processing" {{ old('order_status', $order->order_status) === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ old('order_status', $order->order_status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('order_status', $order->order_status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label for="payment_status" class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1.5">Payment Status *</label>
                    <select name="payment_status" id="payment_status" class="w-full h-11 px-3.5 rounded-lg border border-slate-300 bg-white text-sm focus:outline-none focus:border-brand-600 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                        <option value="unpaid" {{ old('payment_status', $order->payment_status) === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="paid" {{ old('payment_status', $order->payment_status) === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ old('payment_status', $order->payment_status) === 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="cancelled" {{ old('payment_status', $order->payment_status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex flex-col gap-2.5">
                    <button type="submit" class="w-full h-11 bg-brand-800 hover:bg-brand-700 text-white dark:bg-brand-600 font-bold rounded-lg transition flex items-center justify-center gap-2 cursor-pointer shadow-sm">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>Save Changes</span>
                    </button>
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="w-full h-10 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold rounded-lg transition flex items-center justify-center text-xs">
                        Cancel
                    </a>
                </div>
            </div>

            <!-- Order Total Reminder -->
            <div class="bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900 rounded-xl p-4 text-xs text-blue-900 dark:text-blue-300 space-y-1">
                <div class="font-bold uppercase tracking-wider text-[10px] text-blue-600">Total Order Amount</div>
                <div class="text-2xl font-extrabold text-blue-700 dark:text-blue-400">৳{{ number_format($order->total, 2) }}</div>
                <div class="text-slate-500 text-[11px]">Subtotal: ৳{{ number_format($order->subtotal, 2) }} | Delivery: ৳{{ number_format($order->delivery_charge, 2) }}</div>
            </div>

        </div>

    </div>
</form>
@endsection

