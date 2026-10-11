@extends('layouts.auth')

@section('title', 'Forgot Password - DigiGo')

@section('content')
<div class="auth-header">
    <h1>Forgot Password? 🔑</h1>
    <p>Enter your registered email address and we'll send you a password reset link.</p>
</div>

<form action="{{ route('password.email') }}" method="POST">
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

    <!-- Submit Button -->
    <button type="submit" class="btn-submit" style="margin-top: 1.25rem;">
        <i class="fa-solid fa-paper-plane"></i> Send Password Reset Link
    </button>
</form>

<div class="auth-footer">
    Remember your password? <a href="{{ route('login') }}">Back to sign in</a>
</div>
@endsection
