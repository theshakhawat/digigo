@extends('layouts.auth')

@section('title', 'Create Account - DigiGo')

@section('content')
<div class="auth-header">
    <h1>Create an Account ✨</h1>
    <p>Join DigiGo to explore top digital products and services</p>
</div>

<form action="{{ route('register') }}" method="POST">
    @csrf

    <!-- Full Name -->
    <div class="form-group">
        <label for="name" class="form-label">Full Name <span style="color: var(--danger)">*</span></label>
        <div class="input-wrapper">
            <i class="fa-regular fa-user input-icon"></i>
            <input type="text"
                   id="name"
                   name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}"
                   placeholder="e.g. John Doe"
                   required
                   autofocus>
        </div>
        @error('name')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <!-- Email Address -->
    <div class="form-group">
        <label for="email" class="form-label">Email Address <span style="color: var(--danger)">*</span></label>
        <div class="input-wrapper">
            <i class="fa-regular fa-envelope input-icon"></i>
            <input type="email"
                   id="email"
                   name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}"
                   placeholder="name@example.com"
                   required>
        </div>
        @error('email')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <!-- Phone Number -->
    <div class="form-group">
        <label for="phone" class="form-label">Phone Number <span style="font-weight: 400; color: var(--text-muted)">(Optional)</span></label>
        <div class="input-wrapper">
            <i class="fa-solid fa-phone input-icon"></i>
            <input type="tel"
                   id="phone"
                   name="phone"
                   class="form-control @error('phone') is-invalid @enderror"
                   value="{{ old('phone') }}"
                   placeholder="017XXXXXXXX">
        </div>
        @error('phone')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <!-- Password -->
    <div class="form-group">
        <label for="password" class="form-label">Password <span style="color: var(--danger)">*</span></label>
        <div class="input-wrapper">
            <i class="fa-solid fa-lock input-icon"></i>
            <input type="password"
                   id="password"
                   name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Minimum 6 characters"
                   required>
            <button type="button"
                    class="toggle-password-btn"
                    onclick="togglePassword('password', 'regPasswordIcon')"
                    aria-label="Toggle password visibility">
                <i class="fa-regular fa-eye" id="regPasswordIcon"></i>
            </button>
        </div>
        @error('password')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <!-- Password Confirmation -->
    <div class="form-group">
        <label for="password_confirmation" class="form-label">Confirm Password <span style="color: var(--danger)">*</span></label>
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
                    onclick="togglePassword('password_confirmation', 'regConfirmIcon')"
                    aria-label="Toggle confirm password visibility">
                <i class="fa-regular fa-eye" id="regConfirmIcon"></i>
            </button>
        </div>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn-submit" style="margin-top: 1.5rem;">
        <i class="fa-solid fa-user-plus"></i> Create Account
    </button>
</form>

<div class="auth-footer">
    Already have an account? <a href="{{ route('login') }}">Sign In</a>
</div>
@endsection
