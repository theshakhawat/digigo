@extends('layouts.user')

@section('title', 'Support Tickets - DigiGo')
@section('breadcrumb_current', 'Support Tickets')

@section('account_content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-100">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-headset text-blue-600"></i>
                <span>Support Tickets</span>
            </h2>
            <p class="text-xs text-slate-500 mt-1">Open a ticket for Refund Request, Product Return, or Technical Support.</p>
        </div>

        <a href="{{ route('user.tickets.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-xs">
            <i class="fa-solid fa-plus text-[11px]"></i>
            <span>Create New Ticket</span>
        </a>
    </div>

    <!-- Status Tabs / Filter Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <a href="{{ route('user.tickets.index') }}" class="p-3.5 rounded-xl border transition {{ empty($status) ? 'bg-blue-50/70 border-blue-300 text-blue-900' : 'bg-white border-slate-200 hover:border-slate-300 text-slate-700' }}">
            <div class="text-[11px] font-bold uppercase tracking-wider opacity-75">All Tickets</div>
            <div class="text-xl font-black mt-1">{{ $counts['all'] ?? 0 }}</div>
        </a>
        <a href="{{ route('user.tickets.index', ['status' => 'open']) }}" class="p-3.5 rounded-xl border transition {{ $status === 'open' ? 'bg-blue-50/70 border-blue-300 text-blue-900' : 'bg-white border-slate-200 hover:border-slate-300 text-slate-700' }}">
            <div class="text-[11px] font-bold uppercase tracking-wider text-blue-700">Open / Processing</div>
            <div class="text-xl font-black mt-1 text-blue-700">{{ $counts['open'] ?? 0 }}</div>
        </a>
        <a href="{{ route('user.tickets.index', ['status' => 'answered']) }}" class="p-3.5 rounded-xl border transition {{ $status === 'answered' ? 'bg-emerald-50/70 border-emerald-300 text-emerald-900' : 'bg-white border-slate-200 hover:border-slate-300 text-slate-700' }}">
            <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Answered</div>
            <div class="text-xl font-black mt-1 text-emerald-700">{{ $counts['answered'] ?? 0 }}</div>
        </a>
        <a href="{{ route('user.tickets.index', ['status' => 'closed']) }}" class="p-3.5 rounded-xl border transition {{ $status === 'closed' ? 'bg-slate-100 border-slate-300 text-slate-900' : 'bg-white border-slate-200 hover:border-slate-300 text-slate-700' }}">
            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Closed</div>
            <div class="text-xl font-black mt-1 text-slate-600">{{ $counts['closed'] ?? 0 }}</div>
        </a>
    </div>

    <!-- Tickets List Table -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-500 text-xs uppercase font-semibold tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Ticket Info</th>
                        <th class="px-5 py-3.5">Department</th>
                        <th class="px-5 py-3.5">Priority</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Last Updated</th>
                        <th class="px-5 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-5 py-4">
                                <a href="{{ route('user.tickets.show', $ticket->id) }}" class="font-bold text-slate-900 hover:text-blue-600 transition block">
                                    {{ $ticket->subject }}
                                </a>
                                <div class="flex items-center gap-2 mt-1 text-xs text-slate-500">
                                    <span class="font-mono font-semibold text-blue-600">#{{ $ticket->ticket_number }}</span>
                                    @if($ticket->order)
                                        <span>•</span>
                                        <span>Order: <strong class="text-slate-700">#{{ $ticket->order->order_number }}</strong></span>
                                    @endif
                                    @if(!empty($ticket->attachments))
                                        <span>•</span>
                                        <span class="text-slate-400" title="{{ count($ticket->attachments) }} attachment(s)">
                                            <i class="fa-solid fa-paperclip text-[10px]"></i> {{ count($ticket->attachments) }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700">
                                    {{ $ticket->department_label }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @php $pBadge = $ticket->priority_badge; @endphp
                                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full border {{ $pBadge['class'] }}">
                                    {{ $pBadge['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @php $sBadge = $ticket->status_badge; @endphp
                                <span class="text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-full border {{ $sBadge['class'] }}">
                                    {{ $sBadge['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-xs text-slate-500">
                                {{ $ticket->last_reply_at ? $ticket->last_reply_at->diffForHumans() : $ticket->created_at->diffForHumans() }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('user.tickets.show', $ticket->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-blue-700 font-bold text-xs transition">
                                    <span>View Thread</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400">
                                    <i class="fa-solid fa-ticket text-xl"></i>
                                </div>
                                <h3 class="font-bold text-slate-700 text-sm">No support tickets found</h3>
                                <p class="text-xs text-slate-400 mt-1">Need help with a product, refund or technical issue? Open a new ticket anytime.</p>
                                <div class="mt-4">
                                    <a href="{{ route('user.tickets.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition">
                                        <i class="fa-solid fa-plus text-[10px]"></i> Create Ticket
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

