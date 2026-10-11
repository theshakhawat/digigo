<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard page.
     */
    public function index(): View
    {
        $user = Auth::user();

        return view('user.dashboard', compact('user'));
    }

    /**
     * Show the user orders page.
     */
    public function orders(): View
    {
        $user = Auth::user();

        return view('user.orders', compact('user'));
    }

    /**
     * Show the user downloads page.
     */
    public function downloads(): View
    {
        $user = Auth::user();

        return view('user.downloads', compact('user'));
    }

    /**
     * Show the user addresses page.
     */
    public function addresses(): View
    {
        $user = Auth::user();

        return view('user.addresses', compact('user'));
    }

    /**
     * Show the user account details & password edit page.
     */
    public function accountDetails(): View
    {
        $user = Auth::user();

        return view('user.account-details', compact('user'));
    }

    /**
     * Update user account details and optional password.
     */
    public function updateAccountDetails(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'current_password' => ['nullable', 'required_with:password'],
            'password' => ['nullable', 'confirmed', Password::min(6)],
        ]);

        if (! empty($validated['current_password'])) {
            if (! Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'The provided current password does not match our records.']);
            }

            $user->password = Hash::make($validated['password']);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->save();

        return back()->with('success', 'Account details updated successfully.');
    }

    /**
     * Show the user coupons page.
     */
    public function coupons(): View
    {
        $user = Auth::user();

        return view('user.coupons', compact('user'));
    }

    /**
     * Show the user points & rewards page.
     */
    public function rewards(): View
    {
        $user = Auth::user();

        return view('user.rewards', compact('user'));
    }

    /**
     * Show the user wishlist page.
     */
    public function wishlist(): View
    {
        $user = Auth::user();

        return view('user.wishlist', compact('user'));
    }
}
