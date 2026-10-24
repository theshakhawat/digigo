@extends('layouts.admin')

@section('title', 'Ticket #' . $ticket->ticket_number . ' - DigiGo Admin')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="font-mono text-xs font-bold text-brand-600 dark:text-brand-400 bg-brand-50 dark:bg-slate-800 px-2 py-0.5 rounded">
                    #{{ $ticket->ticket_number }}
                </span>
                @php $sBadge = $ticket->status_badge; @endphp
                <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full border {{ $sBadge['class'] }}">
                    {{ $sBadge['label'] }}
                </span>
                @php $pBadge = $ticket->priority_badge; @endphp
                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full border {{ $pBadge['class'] }}">
                    {{ $pBadge['label'] }} Priority
                </span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white mt-1">
                {{ $ticket->subject }}
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Department: <strong class="text-slate-700 dark:text-slate-300">{{ $ticket->department_label }}</strong> •
                Created {{ $ticket->created_at->format('d M, Y h:i A') }} ({{ $ticket->created_at->diffForHumans() }})
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.tickets.index') }}" class="px-4 py-2 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg text-xs font-bold text-slate-700 dark:text-slate-200 transition">
                ← Back to Tickets
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Left 8 Cols: Message History & Admin Reply Form -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Conversation Thread -->
            <div class="space-y-4">
                @foreach($ticket->messages as $msg)
                    <div class="rounded-2xl border p-5 sm:p-6 transition {{ $msg->is_admin ? 'bg-brand-50/30 border-brand-200 dark:bg-slate-800/60 dark:border-slate-700' : 'bg-white border-slate-200 dark:bg-slate-900 dark:border-slate-800' }}">
                        <div class="flex items-center justify-between pb-3 mb-3 border-b {{ $msg->is_admin ? 'border-brand-100 dark:border-slate-700' : 'border-slate-100 dark:border-slate-800' }}">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold {{ $msg->is_admin ? 'bg-brand-600 text-white' : 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-200' }}">
                                    @if($msg->is_admin)
                                        <i class="fa-solid fa-user-shield"></i>
                                    @else
                                        <i class="fa-solid fa-user"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-xs text-slate-900 dark:text-white flex items-center gap-2">
                                        <span>{{ $msg->is_admin ? ($msg->user->name . ' (Staff)') : ($msg->user->name ?? 'Customer') }}</span>
                                        @if($msg->is_admin)
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-extrabold uppercase bg-brand-600 text-white">Staff</span>
                                        @else
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-extrabold uppercase bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300">Customer</span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-slate-400">{{ $msg->created_at->format('d M, Y h:i A') }} ({{ $msg->created_at->diffForHumans() }})</span>
                                </div>
                            </div>
                        </div>

                        <!-- Message Text -->
                        <div class="text-xs sm:text-sm text-slate-800 dark:text-slate-200 whitespace-pre-wrap leading-relaxed">
                            {{ $msg->message }}
                        </div>

                        <!-- Attachments -->
                        @if(!empty($msg->attachments) && is_array($msg->attachments))
                            <div class="mt-4 pt-3 border-t {{ $msg->is_admin ? 'border-brand-100 dark:border-slate-700' : 'border-slate-100 dark:border-slate-800' }}">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2 flex items-center gap-1.5">
                                    <i class="fa-solid fa-paperclip text-[10px]"></i>
                                    <span>Attachments ({{ count($msg->attachments) }})</span>
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($msg->attachments as $att)
                                        <a href="{{ $att['url'] ?? asset('storage/' . ($att['path'] ?? '')) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 hover:text-brand-600 text-xs font-semibold transition shadow-2xs">
                                            <i class="fa-solid fa-file-arrow-down text-brand-500"></i>
                                            <span class="truncate max-w-[200px]">{{ $att['name'] ?? 'Attachment' }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Admin Reply Box -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-xs">
                <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-reply text-brand-600 dark:text-brand-400"></i>
                    <span>Reply to Ticket & Send Notification</span>
                </h3>

                <form action="{{ route('admin.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <textarea name="message" rows="5" placeholder="Write response to customer... Explain resolution steps, refund processing status, replacement details, etc." required class="w-full p-4 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 leading-relaxed">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Update Status To:</label>
                            <select name="status" class="w-full h-10 px-3 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-brand-500 cursor-pointer">
                                <option value="answered" {{ $ticket->status === 'answered' ? 'selected' : '' }}>Answered (Waiting on Customer)</option>
                                <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress (Under Investigation)</option>
                                <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed / Resolved</option>
                                <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Attach Files / Screenshots:</label>
                            <input type="file" name="attachments[]" multiple class="text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-800">
                        <span class="text-xs text-slate-400">
                            Customer will receive an immediate in-app notification & confirmation email.
                        </span>

                        <button type="submit" class="px-6 py-2.5 bg-brand-800 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-paper-plane text-[11px]"></i>
                            <span>Send Reply</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- Right 4 Cols: Ticket Info, Order & Customer Details -->
        <div class="lg:col-span-4 space-y-6">

            <!-- Status & Management Panel -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs space-y-4">
                <h3 class="font-bold text-slate-900 dark:text-white text-sm pb-2 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <span>Ticket Management</span>
                </h3>

                <form action="{{ route('admin.tickets.status', $ticket->id) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Status</label>
                        <select name="status" class="w-full h-9 px-3 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-brand-500">
                            <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="answered" {{ $ticket->status === 'answered' ? 'selected' : '' }}>Answered</option>
                            <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed / Resolved</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Priority</label>
                        <select name="priority" class="w-full h-9 px-3 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-brand-500">
                            <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ $ticket->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-white rounded-lg text-xs font-bold transition cursor-pointer">
                        Update Status
                    </button>
                </form>
            </div>

            <!-- Customer Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs space-y-3">
                <h3 class="font-bold text-slate-900 dark:text-white text-sm pb-2 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <span>Customer Information</span>
                </h3>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center font-bold text-slate-700 dark:text-slate-300 text-sm">
                        {{ substr($ticket->user->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="text-xs">
                        <div class="font-bold text-slate-900 dark:text-white">{{ $ticket->user->name ?? 'Guest' }}</div>
                        <div class="text-slate-400">{{ $ticket->user->email ?? 'N/A' }}</div>
                        @if(!empty($ticket->user->phone))
                            <div class="text-slate-400">{{ $ticket->user->phone }}</div>
                        @endif
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-100 dark:border-slate-800 text-xs">
                    <a href="{{ route('admin.users.index', ['search' => $ticket->user->email ?? '']) }}" class="text-brand-600 dark:text-brand-400 font-bold hover:underline flex items-center gap-1">
                        <span>View user profile</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Related Order Card (if available) -->
            @if($ticket->order)
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs space-y-3">
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm pb-2 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <span>Associated Order</span>
                        <span class="text-xs font-mono font-bold text-brand-600">#{{ $ticket->order->order_number }}</span>
                    </h3>

                    <div class="text-xs space-y-2 text-slate-600 dark:text-slate-300">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Total Amount:</span>
                            <span class="font-black text-slate-900 dark:text-white">৳{{ number_format($ticket->order->total, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Payment Status:</span>
                            <span class="font-bold uppercase text-[10px] px-1.5 py-0.5 rounded {{ $ticket->order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $ticket->order->payment_status }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Order Status:</span>
                            <span class="font-bold uppercase text-[10px] px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                {{ $ticket->order->order_status }}
                            </span>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-slate-100 dark:border-slate-800">
                        <a href="{{ route('admin.orders.show', $ticket->order->id) }}" target="_blank" class="text-xs text-brand-600 dark:text-brand-400 font-bold hover:underline flex items-center gap-1">
                            <span>Open Order Invoice</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection

