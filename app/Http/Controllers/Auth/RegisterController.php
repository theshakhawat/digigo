<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeUserMail;
use App\Models\AdminNotification;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'name.required' => 'Please enter your full name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Please enter a password.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'The password must be at least 6 characters.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'photo' => null,
            'role' => 'user',
            'status' => 'active',
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        // Add to subscriber table if not already subscribed
        $subscriber = Subscriber::firstOrCreate(
            ['email' => $user->email],
            ['status' => 'active']
        );

        if ($subscriber->status !== 'active') {
            $subscriber->update(['status' => 'active']);
        }

        // Notify Admins about the new registered user
        try {
            AdminNotification::create([
                'type' => 'user',
                'title' => 'New Customer Registration',
                'message' => "{$user->name} ({$user->email}) just registered a new account.",
                'link' => route('admin.users.index', ['search' => $user->email]),
                'data' => [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

        try {
            Mail::to($user->email)->queue(new WelcomeUserMail($user, $subscriber));
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('user.dashboard')
            ->with('success', 'Congratulations! Your account has been created successfully.');
    }
}
