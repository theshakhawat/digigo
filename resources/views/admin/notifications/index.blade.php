@extends('layouts.admin')

@section('title', 'Admin Notifications - DigiGo')

@section('admin_content')
<!-- Header Bar -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white flex items-center gap-3">
            <span class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-400 flex items-center justify-center text-lg shadow-xs">
                <i class="fa-solid fa-bell"></i>
            </span>
            <span>Notifications</span>
        </h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            Stay up to date with new store orders, registered customers, payments, and system alerts.
        </p>
    </div>

    <div class="flex items-center gap-2.5 flex-wrap">
        @if(($unreadCount ?? 0) > 0)
            <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition cursor-pointer">
                    <i class="fa-solid fa-check-double text-xs"></i>
                    <span>Mark All Read</span>
                </button>
            </form>
        @endif

        @if(($totalCount ?? 0) > 0)
            <button type="button"
                    class="js-custom-delete inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-900 font-semibold text-xs rounded-xl transition cursor-pointer"
                    data-action="{{ route('admin.notifications.destroy-all') }}"
                    data-title="Clear All Notifications"
                    data-message="Are you sure you want to permanently delete ALL notifications? This cannot be undone."
                    data-btn-text="Yes, Delete All">
                <i class="fa-regular fa-trash-can text-xs"></i>
                <span>Delete All</span>
            </button>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-2 text-sm dark:bg-emerald-950/50 dark:border-emerald-800 dark:text-emerald-300">
        <i class="fa-solid fa-circle-check text-emerald-600"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<!-- Tabs Bar -->
<div class="mb-6 flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.notifications.index', ['filter' => 'all']) }}"
           class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition {{ ($filter ?? 'all') === 'all' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300' }}">
            All ({{ $totalCount ?? 0 }})
        </a>
        <a href="{{ route('admin.notifications.index', ['filter' => 'unread']) }}"
           class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ ($filter ?? 'all') === 'unread' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300' }}">
            <span>Unread</span>
            @if(($unreadCount ?? 0) > 0)
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ ($filter ?? 'all') === 'unread' ? 'bg-white text-blue-700' : 'bg-blue-600 text-white' }}">
                    {{ $unreadCount }}
                </span>
            @endif
        </a>
        <a href="{{ route('admin.notifications.index', ['filter' => 'read']) }}"
           class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition {{ ($filter ?? 'all') === 'read' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300' }}">
            Read ({{ max(0, ($totalCount ?? 0) - ($unreadCount ?? 0)) }})
        </a>
    </div>
</div>

<!-- Notifications Card List -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xs overflow-hidden">
    @if($notifications->isNotEmpty())
        <div class="divide-y divide-slate-100 dark:divide-slate-800">
            @foreach($notifications as $notif)
                <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40 {{ !$notif->is_read ? 'bg-blue-50/30 dark:bg-blue-950/20' : '' }}">
                    <a href="{{ route('admin.notifications.read', $notif->id) }}" class="flex-1 flex items-start gap-4 cursor-pointer">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 text-sm shadow-xs
                            {{ $notif->type === 'order' ? 'bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-400' :
                               ($notif->type === 'user' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400' :
                               ($notif->type === 'subscription' ? 'bg-purple-100 text-purple-700 dark:bg-purple-950 dark:text-purple-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-400')) }}">
                            <i class="fa-solid {{ $notif->type === 'order' ? 'fa-bag-shopping' : ($notif->type === 'user' ? 'fa-user-plus' : ($notif->type === 'subscription' ? 'fa-key' : 'fa-bell')) }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-bold text-sm text-slate-900 dark:text-white {{ !$notif->is_read ? 'text-blue-900 dark:text-blue-300 font-extrabold' : '' }}">
                                    {{ $notif->title }}
                                </h3>
                                @if(!$notif->is_read)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        Unread
                                    </span>
                                @endif
                                <span class="text-xs text-slate-400 dark:text-slate-500 font-medium ml-auto sm:ml-0">
                                    • {{ $notif->created_at->format('d M, Y - h:i A') }} ({{ $notif->created_at->diffForHumans() }})
                                </span>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1 leading-relaxed">
                                {{ $notif->message }}
                            </p>
                        </div>
                    </a>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                        <!-- Toggle Read / Unread -->
                        <form action="{{ route('admin.notifications.toggle', $notif->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border text-xs font-semibold transition cursor-pointer {{ $notif->is_read ? 'border-slate-200 text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800' : 'border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 dark:bg-blue-950/60 dark:text-blue-300 dark:border-blue-900' }}"
                                    title="{{ $notif->is_read ? 'Mark as Unread' : 'Mark as Read' }}">
                                <i class="fa-regular {{ $notif->is_read ? 'fa-envelope' : 'fa-envelope-open' }} text-[11px]"></i>
                                <span>{{ $notif->is_read ? 'Mark Unread' : 'Mark Read' }}</span>
                            </button>
                        </form>

                        <!-- Delete Button using Custom Modal as per Views Rule -->
                        <button type="button"
                                class="js-custom-delete w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/50 transition cursor-pointer"
                                data-action="{{ route('admin.notifications.destroy', $notif->id) }}"
                                data-title="Delete Notification"
                                data-message="Are you sure you want to delete this notification?"
                                data-btn-text="Delete Notification"
                                title="Delete Notification">
                            <i class="fa-regular fa-trash-can text-xs"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        @if($notifications->hasPages())
            <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                {{ $notifications->links() }}
            </div>
        @endif
    @else
        <div class="py-16 text-center text-slate-400 dark:text-slate-500">
            <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-3 text-2xl">
                <i class="fa-regular fa-bell-slash"></i>
            </div>
            <h3 class="text-base font-bold text-slate-700 dark:text-slate-300">No notifications found</h3>
            <p class="text-xs text-slate-400 mt-1">You're all caught up! New orders and system updates will appear here.</p>
        </div>
    @endif
</div>
@endsection

