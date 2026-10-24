@extends('layouts.auth')

@section('title', 'Sign In - DigiGo')

@section('content')
<div class="auth-header">
    <h1>Welcome Back! 👋</h1>
    <p>Sign in to your DigiGo account</p>
</div>

<form action="{{ route('login') }}" method="POST">
    @csrf

    <!-- Email Field -->
    <div class="form-group">
        <label for="email" class="form-label">Email Address</label>
        <div class="input-wrapper">
            <i class="fa-regular fa-envelope input-icon"></i>
            <input type="email"
                   id="email"
                   name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}"
                   placeholder="name@example.com"
                   required
                   autofocus>
        </div>
        @error('email')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <!-- Password Field -->
    <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <div class="input-wrapper">
            <i class="fa-solid fa-lock input-icon"></i>
            <input type="password"
                   id="password"
                   name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="••••••••"
                   required>
            <button type="button"
                    class="toggle-password-btn"
                    onclick="togglePassword('password', 'passwordIcon')"
                    aria-label="Toggle password visibility">
                <i class="fa-regular fa-eye" id="passwordIcon"></i>
            </button>
        </div>
        @error('password')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <!-- Remember Me & Forgot Password -->
    <div class="form-extras">
        <label class="checkbox-label">
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <span>Remember me</span>
        </label>
        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn-submit">
        <i class="fa-solid fa-right-to-bracket"></i> Sign In
    </button>
</form>

<div class="auth-footer">
    Don't have an account? <a href="{{ route('register') }}">Create an account</a>
</div>
@endsection
