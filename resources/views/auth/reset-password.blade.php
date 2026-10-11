@extends('layouts.auth')

@section('title', 'Set New Password - DigiGo')

@section('content')
<div class="auth-header">
    <h1>Set New Password 🔒</h1>
    <p>Please enter your new password to regain access to your account.</p>
</div>

<form action="{{ route('password.update') }}" method="POST">
    @csrf

    <!-- Reset Token -->
    <input type="hidden" name="token" value="{{ $token }}">

    <!-- Email Field -->
    <div class="form-group">
        <label for="email" class="form-label">Email Address</label>
        <div class="input-wrapper">
            <i class="fa-regular fa-envelope input-icon"></i>
            <input type="email"
                   id="email"
                   name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', $email) }}"
                   placeholder="name@example.com"
                   required
                   readonly>
        </div>
        @error('email')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <!-- Password Field -->
    <div class="form-group">
        <label for="password" class="form-label">New Password</label>
        <div class="input-wrapper">
            <i class="fa-solid fa-lock input-icon"></i>
            <input type="password"
                   id="password"
                   name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Minimum 6 characters"
                   required
                   autofocus>
            <button type="button"
                    class="toggle-password-btn"
                    onclick="togglePassword('password', 'resetPwIcon')"
                    aria-label="Toggle password visibility">
                <i class="fa-regular fa-eye" id="resetPwIcon"></i>
            </button>
        </div>
        @error('password')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <!-- Password Confirmation -->
    <div class="form-group">
        <label for="password_confirmation" class="form-label">Confirm New Password</label>
        <div class="input-wrapper">
            <i class="fa-solid fa-shield-halved input-icon"></i>
            <input type="password"
                   id="password_confirmation"
                   name="password_confirmation"
                   class="form-control"
                   placeholder="Re-type your password"
                   required>
            <button type="button"
                    class="toggle-password-btn"
                    onclick="togglePassword('password_confirmation', 'resetConfirmIcon')"
                    aria-label="Toggle confirm password visibility">
                <i class="fa-regular fa-eye" id="resetConfirmIcon"></i>
            </button>
        </div>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn-submit" style="margin-top: 1.5rem;">
        <i class="fa-solid fa-check"></i> Update Password
    </button>
</form>

<div class="auth-footer">
    <a href="{{ route('login') }}">Back to sign in</a>
</div>
@endsection
