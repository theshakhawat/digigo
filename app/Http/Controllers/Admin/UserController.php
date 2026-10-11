<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of users with role and status filtering.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        // Role Filter (user = Customer, admin = Staff/Admin)
        if ($role = $request->input('role')) {
            if (in_array($role, ['user', 'admin'])) {
                $query->where('role', $role);
            }
        }

        // Status Filter
        if ($status = $request->input('status')) {
            if (in_array($status, ['active', 'inactive'])) {
                $query->where('status', $status);
            }
        }

        // Search Filter (Name, Email, Phone)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        // Summary Counts
        $totalCustomers = User::where('role', 'user')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalAll = User::count();

        return view('admin.users.index', compact(
            'users',
            'totalCustomers',
            'totalAdmins',
            'totalAll'
        ));
    }

    /**
     * Show the form for creating a new user or administrator.
     */
    public function create(Request $request): View
    {
        $defaultRole = $request->input('role', 'user');
        if (! in_array($defaultRole, ['user', 'admin'])) {
            $defaultRole = 'user';
        }

        return view('admin.users.create', compact('defaultRole'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('users', 'public');
        }

        User::create($validated);

        return redirect()->route('admin.users.index', ['role' => $validated['role']])
            ->with('success', ucfirst($validated['role']).' account created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        // Safeguard: Current user cannot demote themselves from admin
        if (Auth::id() === $user->id && $user->role === 'admin' && $validated['role'] !== 'admin') {
            return redirect()->back()
                ->with('error', 'You cannot change your own administrator role.')
                ->withInput();
        }

        // If password is not provided, do not overwrite existing password
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $validated['photo'] = $request->file('photo')->store('users', 'public');
        }

        $user->update($validated);

        return redirect()->route('admin.users.index', ['role' => $user->role])
            ->with('success', ucfirst($user->role).' account updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Safeguard: Cannot delete currently logged in account
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $role = $user->role;
        $user->delete();

        return redirect()->route('admin.users.index', ['role' => $role])
            ->with('success', ucfirst($role).' account deleted successfully.');
    }
}
