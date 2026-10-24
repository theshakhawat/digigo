<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * Show the request password reset email link form.
     */
    public function showLinkRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a reset link to the given user.
     */
    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Please provide your email address.',
            'email.email' => 'Please provide a valid email address.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->with('status', 'If your account exists, a password reset link has been sent to your email.');
        }

        // Generate token
        $token = Str::random(64);

        // Delete any existing tokens for this user
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Save new token
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now(),
        ]);

        $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->email]);

        try {
            Mail::to($request->email)->queue(new ResetPasswordMail($user, $resetUrl));

            return back()->with('status', 'We have emailed your password reset link! Please check your inbox or spam folder.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to send email right now. Please try again later.');
        }
    }

    /**
     * Show the reset password form.
     */
    public function showResetForm(Request $request, string $token): View|RedirectResponse
    {
        $email = $request->query('email');

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Handle updating user's password with the reset token.
     */
    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'token.required' => 'Reset token is required.',
            'email.required' => 'Please provide your email address.',
            'password.required' => 'Please enter your new password.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'The password must be at least 6 characters.',
        ]);

        // Find token record
        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (! $record) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'This password reset token is invalid or has expired.']);
        }

        // Check token expiration (e.g., 60 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'This password reset link has expired. Please request a new one.']);
        }

        // Verify token hash
        if (! Hash::check($request->token, $record->token)) {
            return back()->withInput($request->only('email'))
                ->withErrors(['token' => 'The reset token is invalid.']);
        }

        // Update user password
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'We could not find a user with that email address.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        // Delete token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Your password has been reset successfully! You can now log in.');
    }
}
