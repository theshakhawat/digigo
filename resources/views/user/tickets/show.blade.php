@extends('layouts.user')

@section('title', 'Ticket #' . $ticket->ticket_number . ' - DigiGo')
@section('breadcrumb_current', '#' . $ticket->ticket_number)

@section('account_content')
<div class="space-y-6">

    <!-- Header & Ticket Info Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-100">
        <div>
            <div class="flex items-center gap-2">
                <span class="font-mono text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">
                    #{{ $ticket->ticket_number }}
                </span>
                @php $sBadge = $ticket->status_badge; @endphp
                <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full border {{ $sBadge['class'] }}">
                    {{ $sBadge['label'] }}
                </span>
            </div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight mt-1">
                {{ $ticket->subject }}
            </h2>
            <div class="flex items-center gap-3 mt-1.5 text-xs text-slate-500 flex-wrap">
                <span>Department: <strong class="text-slate-700">{{ $ticket->department_label }}</strong></span>
                <span>•</span>
                <span>Priority: <strong class="text-slate-700">{{ ucfirst($ticket->priority) }}</strong></span>
                @if($ticket->order)
                    <span>•</span>
                    <span>Order: <strong class="text-slate-700">#{{ $ticket->order->order_number }}</strong></span>
                @endif
                <span>•</span>
                <span>Created {{ $ticket->created_at->format('d M, Y h:i A') }}</span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            @if($ticket->status !== 'closed')
                <form action="{{ route('user.tickets.close', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to close this ticket?');">
                    @csrf
                    <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer">
                        <i class="fa-solid fa-lock text-[10px] mr-1"></i> Close Ticket
                    </button>
                </form>
            @endif
            <a href="{{ route('user.tickets.index') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold transition">
                ← All Tickets
            </a>
        </div>
    </div>

    <!-- Message Thread -->
    <div class="space-y-4">
        @foreach($ticket->messages as $msg)
            <div class="rounded-2xl border p-5 sm:p-6 transition {{ $msg->is_admin ? 'bg-blue-50/40 border-blue-200' : 'bg-white border-slate-200' }}">
                <div class="flex items-center justify-between pb-3 mb-3 border-b {{ $msg->is_admin ? 'border-blue-100' : 'border-slate-100' }}">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold {{ $msg->is_admin ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-700' }}">
                            @if($msg->is_admin)
                                <i class="fa-solid fa-user-shield"></i>
                            @else
                                <i class="fa-solid fa-user"></i>
                            @endif
                        </div>
                        <div>
                            <div class="font-bold text-xs text-slate-900 flex items-center gap-2">
                                <span>{{ $msg->is_admin ? 'Support Staff (DigiGo)' : ($msg->user->name ?? 'You') }}</span>
                                @if($msg->is_admin)
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-extrabold uppercase bg-blue-600 text-white">Staff</span>
                                @endif
                            </div>
                            <span class="text-[10px] text-slate-400">{{ $msg->created_at->format('d M, Y h:i A') }} ({{ $msg->created_at->diffForHumans() }})</span>
                        </div>
                    </div>
                </div>

                <!-- Message Body -->
                <div class="text-xs sm:text-sm text-slate-800 whitespace-pre-wrap leading-relaxed">
                    {{ $msg->message }}
                </div>

                <!-- Multiple Attachments -->
                @if(!empty($msg->attachments) && is_array($msg->attachments))
                    <div class="mt-4 pt-3 border-t {{ $msg->is_admin ? 'border-blue-100' : 'border-slate-100' }}">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-paperclip text-[10px]"></i>
                            <span>Attachments ({{ count($msg->attachments) }})</span>
                        </p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($msg->attachments as $att)
                                <a href="{{ $att['url'] ?? asset('storage/' . ($att['path'] ?? '')) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 hover:text-blue-600 text-xs font-semibold transition shadow-2xs">
                                    <i class="fa-solid fa-file-arrow-down text-blue-500"></i>
                                    <span class="truncate max-w-[200px]">{{ $att['name'] ?? 'Attachment' }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Reply Box (if not closed) -->
    @if($ticket->status !== 'closed')
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs">
            <h3 class="font-bold text-slate-900 text-sm mb-3 flex items-center gap-2">
                <i class="fa-solid fa-reply text-blue-600"></i>
                <span>Post a Reply</span>
            </h3>

            <form action="{{ route('user.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <textarea name="message" rows="4" placeholder="Write your reply or additional information here..." required class="w-full p-3.5 text-xs rounded-xl border border-slate-300 bg-white text-slate-800 focus:ring-2 focus:ring-blue-500 leading-relaxed">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Attach Files / Screenshots (Optional, multiple allowed):</label>
                    <input type="file" name="attachments[]" multiple class="text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    @error('attachments')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition shadow-xs flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-paper-plane text-[10px]"></i>
                        <span>Send Reply</span>
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-center text-slate-500 text-xs font-medium">
            This support ticket has been closed. If you still need help, please <a href="{{ route('user.tickets.create') }}" class="text-blue-600 font-bold hover:underline">open a new support ticket</a>.
        </div>
    @endif

</div>
@endsection

