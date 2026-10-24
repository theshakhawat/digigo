@extends('layouts.admin')

@section('title', 'Email Subscribers')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Stats -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">Newsletter Subscribers</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Manage all newsletter subscribers, toggle subscription status, and search subscriber records.</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 dark:bg-slate-800 dark:border-slate-700 text-slate-700 dark:text-slate-300 shadow-xs">
                <i class="fa-solid fa-users text-brand-600"></i>
                Total: <strong class="text-slate-900 dark:text-white">{{ $totalCount }}</strong>
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 border border-emerald-200 dark:bg-emerald-950/40 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 shadow-xs">
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                Active: <strong>{{ $activeCount }}</strong>
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 border border-slate-200 dark:bg-slate-800 dark:border-slate-700 text-slate-600 dark:text-slate-400 shadow-xs">
                <i class="fa-solid fa-circle-xmark text-slate-400"></i>
                Inactive: <strong>{{ $inactiveCount }}</strong>
            </span>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.subscribers.index') }}" class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex flex-1 items-center gap-2 max-w-md">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </div>
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Search by email address..."
                           class="w-full h-10 pl-9 pr-4 text-xs sm:text-sm bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:text-slate-100 transition">
                </div>
                @if($search || $status)
                    <a href="{{ route('admin.subscribers.index') }}" class="px-3 py-2 text-xs font-medium text-slate-500 hover:text-rose-600 dark:text-slate-400 transition" title="Clear Filters">
                        Clear
                    </a>
                @endif
            </div>

            <!-- Status Tabs Filter -->
            <div class="flex items-center gap-1.5 shrink-0 overflow-x-auto">
                <a href="{{ route('admin.subscribers.index', ['search' => $search]) }}"
                   class="px-3 py-1.5 text-xs font-bold rounded-lg transition {{ empty($status) ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 dark:bg-slate-700/60 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                    All ({{ $totalCount }})
                </a>
                <a href="{{ route('admin.subscribers.index', ['search' => $search, 'status' => 'active']) }}"
                   class="px-3 py-1.5 text-xs font-bold rounded-lg transition {{ $status === 'active' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 dark:bg-slate-700/60 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                    Active ({{ $activeCount }})
                </a>
                <a href="{{ route('admin.subscribers.index', ['search' => $search, 'status' => 'inactive']) }}"
                   class="px-3 py-1.5 text-xs font-bold rounded-lg transition {{ $status === 'inactive' ? 'bg-rose-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 dark:bg-slate-700/60 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                    Inactive ({{ $inactiveCount }})
                </a>
            </div>
        </form>
    </div>

    <!-- Subscribers Table -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 dark:bg-slate-900/60 border-b border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                        <th class="px-5 py-3.5">#</th>
                        <th class="px-5 py-3.5">Email Address</th>
                        <th class="px-5 py-3.5">Subscriber UUID</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Joined Date</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/70 text-slate-700 dark:text-slate-300">
                    @forelse($subscribers as $index => $subscriber)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-750 transition">
                            <td class="px-5 py-3.5 font-mono text-slate-400">
                                {{ $subscribers->firstItem() + $index }}
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-slate-900 dark:text-white">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-brand-50 dark:bg-brand-950 text-brand-600 dark:text-brand-400 flex items-center justify-center text-xs shrink-0 font-bold">
                                        {{ strtoupper(substr($subscriber->email, 0, 1)) }}
                                    </div>
                                    <span class="truncate">{{ $subscriber->email }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 font-mono text-[11px] text-slate-400 select-all">
                                {{ $subscriber->uuid }}
                            </td>
                            <td class="px-5 py-3.5">
                                @if($subscriber->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-slate-500 text-[11px]">
                                <div>{{ $subscriber->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-slate-400">{{ $subscriber->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5 justify-end">
                                    <!-- Toggle Status Form -->
                                    <form action="{{ route('admin.subscribers.toggle-status', $subscriber->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="px-2.5 py-1 text-xs font-semibold rounded-lg border transition {{ $subscriber->status === 'active' ? 'border-amber-200 text-amber-700 bg-amber-50 hover:bg-amber-100 dark:border-amber-900/60 dark:bg-amber-950/40 dark:text-amber-300' : 'border-emerald-200 text-emerald-700 bg-emerald-50 hover:bg-emerald-100 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-300' }}"
                                                title="Switch to {{ $subscriber->status === 'active' ? 'Inactive' : 'Active' }}">
                                            {{ $subscriber->status === 'active' ? 'Mark Inactive' : 'Mark Active' }}
                                        </button>
                                    </form>

                                    <!-- Delete Button (Trigger Custom Modal) -->
                                    <button type="button"
                                            class="js-custom-delete w-7 h-7 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 text-slate-400 hover:text-rose-600 hover:border-rose-300 dark:hover:border-rose-800 transition cursor-pointer"
                                            data-action="{{ route('admin.subscribers.destroy', $subscriber->id) }}"
                                            data-title="Delete Subscriber?"
                                            data-message="Are you sure you want to permanently remove {{ $subscriber->email }} from the subscriber list?"
                                            title="Delete Subscriber">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                <div class="max-w-xs mx-auto">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center mx-auto mb-3 text-slate-400 text-xl">
                                        <i class="fa-regular fa-envelope"></i>
                                    </div>
                                    <p class="font-bold text-slate-600 dark:text-slate-300 text-sm">No subscribers found</p>
                                    <p class="text-xs text-slate-400 mt-1">
                                        @if($search || $status)
                                            Try adjusting your search filters.
                                        @else
                                            When new customers register or subscribe to newsletter, they will appear here.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscribers->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-700/80">
                {{ $subscribers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

