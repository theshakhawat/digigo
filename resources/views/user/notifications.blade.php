@extends('layouts.user')

@section('title', 'My Notifications - DigiGo')
@section('breadcrumb_current', 'Notifications')

@section('account_content')
<div class="content-card-box">
    <!-- Header with actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-100">
        <div>
            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2 mb-0">
                <i class="fa-solid fa-bell text-blue-600"></i>
                <span>Notifications</span>
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Updates regarding your orders, subscriptions, activations, and rewards.
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @if(($unreadCount ?? 0) > 0)
                <form action="{{ route('user.notifications.mark-all-read') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs shadow-xs transition cursor-pointer">
                        <i class="fa-solid fa-check-double text-xs"></i>
                        <span>Mark All Read</span>
                    </button>
                </form>
            @endif

            @if(($totalCount ?? 0) > 0)
                <button type="button"
                        class="js-custom-delete inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-semibold text-xs transition cursor-pointer"
                        data-action="{{ route('user.notifications.destroy-all') }}"
                        data-title="Clear All Notifications"
                        data-message="Are you sure you want to delete all your notifications? This action cannot be reversed."
                        data-btn-text="Yes, Delete All">
                    <i class="fa-regular fa-trash-can text-xs"></i>
                    <span>Delete All</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center gap-2 pt-4 pb-4 border-b border-slate-100">
        <a href="{{ route('user.notifications.index', ['filter' => 'all']) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ ($filter ?? 'all') === 'all' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
            All ({{ $totalCount ?? 0 }})
        </a>
        <a href="{{ route('user.notifications.index', ['filter' => 'unread']) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ ($filter ?? 'all') === 'unread' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
            <span>Unread</span>
            @if(($unreadCount ?? 0) > 0)
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ ($filter ?? 'all') === 'unread' ? 'bg-white text-blue-700' : 'bg-blue-600 text-white' }}">
                    {{ $unreadCount }}
                </span>
            @endif
        </a>
        <a href="{{ route('user.notifications.index', ['filter' => 'read']) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ ($filter ?? 'all') === 'read' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
            Read ({{ max(0, ($totalCount ?? 0) - ($unreadCount ?? 0)) }})
        </a>
    </div>

    <!-- Notifications List -->
    @if($notifications->isNotEmpty())
        <div class="divide-y divide-slate-100 mt-2">
            @foreach($notifications as $notif)
                <div class="py-4 px-2 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/80 rounded-xl transition {{ !$notif->is_read ? 'bg-blue-50/40' : '' }}">
                    <a href="{{ route('user.notifications.read', $notif->id) }}" class="flex-1 flex items-start gap-3.5 cursor-pointer">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 text-sm shadow-xs
                            {{ $notif->type === 'order' ? 'bg-blue-100 text-blue-700' :
                               ($notif->type === 'subscription' ? 'bg-purple-100 text-purple-700' :
                               ($notif->type === 'withdrawal' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700')) }}">
                            <i class="fa-solid {{ $notif->type === 'order' ? 'fa-bag-shopping' : ($notif->type === 'subscription' ? 'fa-key' : ($notif->type === 'withdrawal' ? 'fa-wallet' : 'fa-bell')) }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="font-bold text-sm text-slate-900 {{ !$notif->is_read ? 'text-blue-900 font-extrabold' : '' }}">
                                    {{ $notif->title }}
                                </h4>
                                @if(!$notif->is_read)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-blue-100 text-blue-800">
                                        Unread
                                    </span>
                                @endif
                                <span class="text-xs text-slate-400 font-medium ml-auto sm:ml-0">
                                    • {{ $notif->created_at->format('d M, Y - h:i A') }} ({{ $notif->created_at->diffForHumans() }})
                                </span>
                            </div>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                                {{ $notif->message }}
                            </p>
                        </div>
                    </a>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                        <!-- Toggle Read / Unread -->
                        <form action="{{ route('user.notifications.toggle', $notif->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border text-xs font-semibold transition cursor-pointer {{ $notif->is_read ? 'border-slate-200 text-slate-600 hover:bg-slate-100' : 'border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100' }}"
                                    title="{{ $notif->is_read ? 'Mark as Unread' : 'Mark as Read' }}">
                                <i class="fa-regular {{ $notif->is_read ? 'fa-envelope' : 'fa-envelope-open' }} text-[11px]"></i>
                                <span>{{ $notif->is_read ? 'Mark Unread' : 'Mark Read' }}</span>
                            </button>
                        </form>

                        <!-- Delete Button using Custom Modal as per Views Rule -->
                        <button type="button"
                                class="js-custom-delete w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                data-action="{{ route('user.notifications.destroy', $notif->id) }}"
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
            <div class="pt-6 border-t border-slate-100 mt-4">
                {{ $notifications->links() }}
            </div>
        @endif
    @else
        <div class="py-16 text-center text-slate-400">
            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-3 text-2xl">
                <i class="fa-regular fa-bell-slash"></i>
            </div>
            <h3 class="text-base font-bold text-slate-700">No notifications found</h3>
            <p class="text-xs text-slate-400 mt-1">You're all caught up! Order and subscription updates will appear here.</p>
        </div>
    @endif
</div>

<!-- Custom Delete Confirmation Modal (Rule Compliance) -->
<div id="customDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity duration-200">
    <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-slate-200 transform transition-all scale-95 opacity-0 duration-200" id="customDeleteCard">
        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-xl mb-4 mx-auto">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h3 class="text-base font-bold text-slate-900 text-center" id="customDeleteTitle">Confirm Delete</h3>
        <p class="text-xs text-slate-500 text-center mt-1.5 leading-relaxed" id="customDeleteMessage">Are you sure you want to proceed?</p>
        <div class="mt-6 flex items-center gap-3">
            <button type="button" id="customDeleteCancel" class="flex-1 py-2 px-3 border border-slate-200 text-slate-600 font-semibold text-xs rounded-xl hover:bg-slate-100 transition cursor-pointer">
                Cancel
            </button>
            <form id="customDeleteForm" method="POST" action="" class="flex-1 m-0">
                @csrf
                @method('DELETE')
                <button type="submit" id="customDeleteConfirmBtn" class="w-full py-2 px-3 bg-rose-600 text-white font-semibold text-xs rounded-xl hover:bg-rose-700 transition cursor-pointer shadow-sm">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('customDeleteModal');
        const card = document.getElementById('customDeleteCard');
        const form = document.getElementById('customDeleteForm');
        const titleElem = document.getElementById('customDeleteTitle');
        const msgElem = document.getElementById('customDeleteMessage');
        const confirmBtn = document.getElementById('customDeleteConfirmBtn');
        const cancelBtn = document.getElementById('customDeleteCancel');

        function openModal(action, title, message, btnText) {
            form.action = action;
            if (title) titleElem.textContent = title;
            if (message) msgElem.textContent = message;
            if (btnText) confirmBtn.textContent = btnText;

            modal.classList.remove('hidden');
            setTimeout(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal() {
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        document.querySelectorAll('.js-custom-delete').forEach(btn => {
            btn.addEventListener('click', function () {
                const action = this.getAttribute('data-action');
                const title = this.getAttribute('data-title');
                const message = this.getAttribute('data-message');
                const btnText = this.getAttribute('data-btn-text');
                openModal(action, title, message, btnText);
            });
        });

        cancelBtn?.addEventListener('click', closeModal);
        modal?.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });
    });
</script>
@endpush

