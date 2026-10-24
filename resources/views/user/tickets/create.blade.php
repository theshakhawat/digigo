@extends('layouts.user')

@section('title', 'Open Support Ticket - DigiGo')
@section('breadcrumb_current', 'Create Ticket')

@section('account_content')
<div class="max-w-3xl space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-circle-plus text-blue-600"></i>
                <span>Open a Support Ticket</span>
            </h2>
            <p class="text-xs text-slate-500 mt-1">Please provide clear details and attach relevant screenshots/files.</p>
        </div>

        <a href="{{ route('user.tickets.index') }}" class="text-xs font-bold text-slate-500 hover:text-blue-600 transition flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left text-[10px]"></i>
            <span>Back to Tickets</span>
        </a>
    </div>

    <!-- Ticket Form -->
    <form action="{{ route('user.tickets.store') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-xs space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Department -->
            <div>
                <label for="department" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Department / Issue Type <span class="text-rose-500">*</span>
                </label>
                <select name="department" id="department" required class="w-full h-11 px-3.5 text-xs rounded-xl border border-slate-300 bg-white text-slate-800 focus:ring-2 focus:ring-blue-500 cursor-pointer">
                    <option value="technical" {{ old('department') === 'technical' ? 'selected' : '' }}>🛠️ Technical Support & Access</option>
                    <option value="refund" {{ old('department') === 'refund' ? 'selected' : '' }}>💰 Refund Request</option>
                    <option value="return" {{ old('department') === 'return' ? 'selected' : '' }}>📦 Product Return / Replacement</option>
                    <option value="general" {{ old('department') === 'general' ? 'selected' : '' }}>💬 General Inquiry / Other</option>
                </select>
                @error('department')
                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Priority -->
            <div>
                <label for="priority" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Priority Level <span class="text-rose-500">*</span>
                </label>
                <select name="priority" id="priority" required class="w-full h-11 px-3.5 text-xs rounded-xl border border-slate-300 bg-white text-slate-800 focus:ring-2 focus:ring-blue-500 cursor-pointer">
                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium (Standard)</option>
                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
                @error('priority')
                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Related Order (Optional) -->
        <div>
            <label for="order_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Related Order (Optional)
            </label>
            <select name="order_id" id="order_id" class="w-full h-11 px-3.5 text-xs rounded-xl border border-slate-300 bg-white text-slate-800 focus:ring-2 focus:ring-blue-500 cursor-pointer">
                <option value="">-- None / General Account Issue --</option>
                @foreach($orders as $ord)
                    <option value="{{ $ord->id }}" {{ old('order_id') == $ord->id ? 'selected' : '' }}>
                        #{{ $ord->order_number }} — ৳{{ number_format($ord->total, 2) }} ({{ $ord->created_at->format('d M, Y') }})
                    </option>
                @endforeach
            </select>
            <p class="text-[11px] text-slate-400 mt-1">If this issue relates to a specific purchase, select it above.</p>
            @error('order_id')
                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Subject -->
        <div>
            <label for="subject" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Ticket Subject <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" placeholder="e.g. Canva Pro credentials not working / Need refund for order" required class="w-full h-11 px-3.5 text-xs rounded-xl border border-slate-300 bg-white text-slate-800 focus:ring-2 focus:ring-blue-500">
            @error('subject')
                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Message Details -->
        <div>
            <label for="message" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Describe Your Issue in Detail <span class="text-rose-500">*</span>
            </label>
            <textarea name="message" id="message" rows="6" placeholder="Please describe what happened, any error messages, or reason for return/refund..." required class="w-full p-4 text-xs rounded-xl border border-slate-300 bg-white text-slate-800 focus:ring-2 focus:ring-blue-500 leading-relaxed">{{ old('message') }}</textarea>
            @error('message')
                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Attachments (Multiple Files Supported) -->
        <div>
            <label for="attachments" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Upload Attachments / Screenshots (Multiple files allowed)
            </label>
            <div class="border-2 border-dashed border-slate-200 rounded-xl p-5 text-center hover:border-blue-400 transition bg-slate-50/50">
                <i class="fa-solid fa-cloud-arrow-up text-2xl text-slate-400 mb-2 block"></i>
                <input type="file" name="attachments[]" id="attachments" multiple class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                <p class="text-[11px] text-slate-400 mt-2">Accepted formats: JPG, PNG, WEBP, PDF, ZIP, DOCX (Max 10MB per file, up to 5 files)</p>
            </div>
            @error('attachments')
                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            @error('attachments.*')
                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
            <a href="{{ route('user.tickets.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition shadow-xs flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-paper-plane text-[11px]"></i>
                <span>Submit Ticket</span>
            </button>
        </div>
    </form>

</div>
@endsection

