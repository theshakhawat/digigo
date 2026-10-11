@extends('layouts.admin')

@section('title', 'Users Management - DigiGo')

@section('admin_content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white">
            Users Management
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Manage store customers, administrators, roles, and account access.
        </p>
    </div>

    <div class="flex items-center gap-2">
        <a href="{{ route('admin.users.create', ['role' => 'user']) }}" class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-blue-50 text-brand-700 hover:bg-blue-100 dark:bg-slate-800 dark:text-brand-400 text-xs sm:text-sm font-semibold rounded-lg shadow-xs transition">
            <i class="fa-solid fa-user-plus text-xs"></i>
            <span>Add Customer</span>
        </a>
        <a href="{{ route('admin.users.create', ['role' => 'admin']) }}" class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-brand-800 hover:bg-brand-700 text-white dark:bg-brand-600 dark:hover:bg-brand-700 text-xs sm:text-sm font-semibold rounded-lg shadow-xs transition">
            <i class="fa-solid fa-user-shield text-xs"></i>
            <span>Add Administrator</span>
        </a>
    </div>
</div>

<!-- Role Switcher Tabs -->
<div class="mb-6 border-b border-slate-200 dark:border-slate-800 flex items-center gap-2 overflow-x-auto">
    <a href="{{ route('admin.users.index') }}"
       class="pb-3 px-3 text-sm font-semibold border-b-2 whitespace-nowrap transition {{ !request('role') ? 'border-brand-600 text-brand-700 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">
        All Accounts ({{ $totalAll }})
    </a>
    <a href="{{ route('admin.users.index', ['role' => 'user']) }}"
       class="pb-3 px-3 text-sm font-semibold border-b-2 whitespace-nowrap transition {{ request('role') === 'user' ? 'border-brand-600 text-brand-700 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">
        Customers ({{ $totalCustomers }})
    </a>
    <a href="{{ route('admin.users.index', ['role' => 'admin']) }}"
       class="pb-3 px-3 text-sm font-semibold border-b-2 whitespace-nowrap transition {{ request('role') === 'admin' ? 'border-brand-600 text-brand-700 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">
        Administrators ({{ $totalAdmins }})
    </a>
</div>

<!-- Search & Filter Card -->
<div class="mb-6 p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs">
    <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center gap-3">
        @if(request('role'))
            <input type="hidden" name="role" value="{{ request('role') }}">
        @endif

        <!-- Search Input -->
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search by name, email, or phone number..."
                   class="w-full h-10 pl-9 pr-4 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 dark:focus:border-brand-500 transition">
        </div>

        <!-- Status Filter -->
        <div class="w-full sm:w-44">
            <select name="status" class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-700 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2 shrink-0">
            <button type="submit" class="px-4 h-10 bg-slate-800 hover:bg-slate-900 text-white text-xs sm:text-sm font-semibold rounded-lg transition dark:bg-slate-700 dark:hover:bg-slate-600">
                Filter
            </button>
            @if(request('search') || request('status') || request('role'))
                <a href="{{ route('admin.users.index') }}" class="px-3 h-10 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm rounded-lg transition dark:bg-slate-800 dark:text-slate-300">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Users Table Card -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs sm:text-sm">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 dark:bg-slate-950 dark:border-slate-800 dark:text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                <tr>
                    <th class="px-5 py-3.5">User Profile</th>
                    <th class="px-5 py-3.5">Email</th>
                    <th class="px-5 py-3.5">Phone</th>
                    <th class="px-5 py-3.5">Role</th>
                    <th class="px-5 py-3.5">Status</th>
                    <th class="px-5 py-3.5">Registered</th>
                    <th class="px-5 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse ($users as $u)
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                        <!-- User Name & Avatar -->
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <img src="{{ $u->avatar_url }}"
                                     alt="{{ $u->name }}"
                                     class="w-9 h-9 rounded-full object-cover shrink-0 border border-slate-200 dark:border-slate-700">
                                <div>
                                    <div class="font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                                        <span>{{ $u->name }}</span>
                                        @if(auth()->id() === $u->id)
                                            <span class="text-[9px] bg-emerald-100 text-emerald-800 px-1.5 py-0.2 rounded-full font-bold uppercase">You</span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-slate-400">ID: #{{ $u->id }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Email -->
                        <td class="px-5 py-3.5 text-slate-600 dark:text-slate-300">
                            {{ $u->email }}
                        </td>

                        <!-- Phone -->
                        <td class="px-5 py-3.5 text-slate-600 dark:text-slate-300">
                            {{ $u->phone ?? '—' }}
                        </td>

                        <!-- Role -->
                        <td class="px-5 py-3.5">
                            @if ($u->role === 'admin')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-[11px] font-bold uppercase bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300 rounded-full border border-purple-200 dark:border-purple-800">
                                    <i class="fa-solid fa-user-shield text-[10px]"></i>
                                    Admin
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-[11px] font-bold uppercase bg-blue-100 text-brand-800 dark:bg-blue-950 dark:text-brand-300 rounded-full border border-blue-200 dark:border-blue-900">
                                    <i class="fa-solid fa-user text-[10px]"></i>
                                    Customer
                                </span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="px-5 py-3.5">
                            @if ($u->status === 'active')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-[11px] font-bold uppercase bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 rounded-full border border-emerald-200 dark:border-emerald-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-[11px] font-bold uppercase bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 rounded-full border border-slate-200 dark:border-slate-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Inactive
                                </span>
                            @endif
                        </td>

                        <!-- Registered -->
                        <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400 text-xs whitespace-nowrap">
                            {{ $u->created_at ? $u->created_at->format('M d, Y') : '—' }}
                        </td>

                        <!-- Actions -->
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.users.edit', $u) }}" class="p-2 text-slate-500 hover:text-brand-600 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition" title="Edit User">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                @if(auth()->id() !== $u->id)
                                    <!-- Custom Delete Modal Trigger -->
                                    <button type="button"
                                            class="js-custom-delete p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition cursor-pointer"
                                            data-action="{{ route('admin.users.destroy', $u) }}"
                                            data-title="Delete User: {{ $u->name }}"
                                            data-message="Are you sure you want to permanently delete user '{{ $u->name }}' ({{ $u->email }})? This action cannot be undone."
                                            title="Delete Account">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                @else
                                    <span class="p-2 text-slate-300 dark:text-slate-700 cursor-not-allowed" title="You cannot delete yourself">
                                        <i class="fa-solid fa-trash"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-users text-3xl mb-3 text-slate-300 dark:text-slate-600"></i>
                                <p class="text-base font-semibold text-slate-700 dark:text-slate-300">No users found</p>
                                <p class="text-xs text-slate-400 mt-1">Try adjusting your filters or search keywords.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($users->hasPages())
        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
