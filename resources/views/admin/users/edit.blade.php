@extends('layouts.admin')

@section('title', 'Edit User - ' . $user->name . ' - DigiGo')

@section('admin_content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-1" aria-label="Breadcrumb">
            <a href="{{ route('admin.users.index') }}" class="hover:text-brand-600 transition">Users</a>
            <span>/</span>
            <span class="text-slate-800 dark:text-slate-200 font-semibold">Edit</span>
        </nav>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white">
            Edit Account: {{ $user->name }}
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
            Modify credentials, roles, and status for this account.
        </p>
    </div>

    <div>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 text-slate-700 dark:text-slate-200 text-xs sm:text-sm font-semibold rounded-lg shadow-xs transition">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            <span>Back to Users</span>
        </a>
    </div>
</div>

<!-- Edit Form Card -->
<div class="max-w-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs p-6 sm:p-8">
    <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Name & Email (2-Column Grid) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="name" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                    Full Name <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       name="name"
                       id="name"
                       value="{{ old('name', $user->name) }}"
                       class="w-full h-11 px-3.5 text-sm bg-slate-50 border @error('name') border-rose-500 @else border-slate-200 @enderror rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 dark:focus:border-brand-500 transition"
                       required>
                @error('name')
                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                    Email Address <span class="text-rose-500">*</span>
                </label>
                <input type="email"
                       name="email"
                       id="email"
                       value="{{ old('email', $user->email) }}"
                       class="w-full h-11 px-3.5 text-sm bg-slate-50 border @error('email') border-rose-500 @else border-slate-200 @enderror rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 dark:focus:border-brand-500 transition"
                       required>
                @error('email')
                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Phone & Role (2-Column Grid) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="phone" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                    Phone Number <span class="text-slate-400 font-normal text-xs">(Optional)</span>
                </label>
                <input type="text"
                       name="phone"
                       id="phone"
                       value="{{ old('phone', $user->phone) }}"
                       class="w-full h-11 px-3.5 text-sm bg-slate-50 border @error('phone') border-rose-500 @else border-slate-200 @enderror rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 dark:focus:border-brand-500 transition">
                @error('phone')
                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="role" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                    Account Role <span class="text-rose-500">*</span>
                </label>
                <select name="role"
                        id="role"
                        class="w-full h-11 px-3.5 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100"
                        {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Customer (Standard User)</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator (Management Access)</option>
                </select>
                @if(auth()->id() === $user->id)
                    <input type="hidden" name="role" value="{{ $user->role }}">
                    <p class="text-[11px] text-slate-400 mt-1">You cannot modify your own administrator role.</p>
                @endif
                @error('role')
                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Status -->
        <div>
            <label for="status" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                Account Status <span class="text-rose-500">*</span>
            </label>
            <select name="status"
                    id="status"
                    class="w-full sm:w-60 h-11 px-3.5 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active (Can login)</option>
                <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive (Access blocked)</option>
            </select>
            @error('status')
                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Profile Photo -->
        <div>
            <label class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                Profile Photo <span class="text-slate-400 font-normal text-xs">(Optional, saved to /storage)</span>
            </label>

            <div class="flex items-start gap-4">
                <div id="photoPreviewContainer" class="w-16 h-16 rounded-full border border-slate-200 dark:border-slate-700 flex items-center justify-center bg-slate-50 dark:bg-slate-800 shrink-0 overflow-hidden text-slate-400">
                    <img id="photoPreview" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                </div>

                <div class="flex-1">
                    <input type="file"
                           name="photo"
                           id="photo"
                           accept="image/*"
                           class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 dark:file:bg-slate-800 dark:file:text-brand-400 file:cursor-pointer cursor-pointer border border-slate-200 dark:border-slate-700 rounded-lg p-1.5">
                    <p class="text-[11px] text-slate-400 mt-1">Upload a new photo to replace the current avatar. Allowed: JPG, PNG, WEBP (max 2MB).</p>
                    @error('photo')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Optional Password Reset Section -->
        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-1">
                Password Change
            </h3>
            <p class="text-xs text-slate-400 mb-4">Leave both fields blank if you do not wish to change the password.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="password" class="block text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        New Password
                    </label>
                    <input type="password"
                           name="password"
                           id="password"
                           placeholder="Leave empty to keep existing"
                           class="w-full h-11 px-3.5 text-sm bg-slate-50 border @error('password') border-rose-500 @else border-slate-200 @enderror rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 dark:focus:border-brand-500 transition">
                    @error('password')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Confirm New Password
                    </label>
                    <input type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           placeholder="Repeat new password"
                           class="w-full h-11 px-3.5 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 dark:focus:border-brand-500 transition">
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-brand-800 hover:bg-brand-700 text-white dark:bg-brand-600 dark:hover:bg-brand-700 text-sm font-semibold rounded-lg shadow-sm transition cursor-pointer">
                Update Account
            </button>
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-300 text-sm font-semibold rounded-lg transition">
                Cancel
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photoPreview');

    photoInput?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection
