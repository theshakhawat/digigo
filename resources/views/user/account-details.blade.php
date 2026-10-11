@extends('layouts.user')

@section('title', 'Account Details - My Account - DigiGo')
@section('breadcrumb_current', 'Account details')

@section('account_content')
<div class="content-card-box">
    <h2>
        <i class="fa-regular fa-circle-user" style="color: var(--theme-accent);"></i> Account Details
    </h2>

    <form action="{{ route('user.account-details.update') }}" method="POST">
        @csrf

        <!-- Name & Email -->
        <div class="form-row">
            <div class="form-group">
                <label for="name" class="form-label">Full Name <span style="color: #ef4444;">*</span></label>
                <input type="text"
                       name="name"
                       id="name"
                       class="form-input @error('name') is-invalid @enderror"
                       value="{{ old('name', $user->name) }}"
                       required>
                @error('name')
                    <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Address <span style="color: #ef4444;">*</span></label>
                <input type="email"
                       name="email"
                       id="email"
                       class="form-input @error('email') is-invalid @enderror"
                       value="{{ old('email', $user->email) }}"
                       required>
                @error('email')
                    <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Phone -->
        <div class="form-group">
            <label for="phone" class="form-label">Phone Number (Optional)</label>
            <input type="tel"
                   name="phone"
                   id="phone"
                   class="form-input @error('phone') is-invalid @enderror"
                   value="{{ old('phone', $user->phone) }}"
                   placeholder="017XXXXXXXX">
            @error('phone')
                <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password Change Section -->
        <fieldset style="border: 1px solid var(--account-border); border-radius: 8px; padding: 1.5rem; margin-top: 2rem; margin-bottom: 2rem;">
            <legend style="padding: 0 0.5rem; font-size: 0.95rem; font-weight: 700; color: #111827;">Password change</legend>

            <div class="form-group">
                <label for="current_password" class="form-label">Current password (leave blank to leave unchanged)</label>
                <input type="password"
                       name="current_password"
                       id="current_password"
                       class="form-input @error('current_password') is-invalid @enderror"
                       autocomplete="current-password">
                @error('current_password')
                    <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password" class="form-label">New password (leave blank to leave unchanged)</label>
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-input @error('password') is-invalid @enderror"
                           autocomplete="new-password">
                    @error('password')
                        <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm new password</label>
                    <input type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           class="form-input"
                           autocomplete="new-password">
                </div>
            </div>
        </fieldset>

        <button type="submit" class="btn-theme-submit">
            Save changes
        </button>
    </form>
</div>
@endsection
