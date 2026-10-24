@extends('layouts.admin')

@section('title', 'Support Tickets Management - DigiGo Admin')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                <i class="fa-solid fa-headset text-brand-600 dark:text-brand-400"></i>
                <span>Support Tickets Center</span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Manage customer issues, refund requests, return claims, and technical inquiries.
            </p>
        </div>
    </div>

    <!-- Status Tabs / Metrics -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <a href="{{ route('admin.tickets.index') }}" class="p-3.5 rounded-xl border transition {{ empty($status) ? 'bg-brand-50/70 border-brand-300 dark:bg-slate-800 dark:border-brand-500' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 hover:border-slate-300' }}">
            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">All Tickets</div>
            <div class="text-xl font-black mt-1 text-slate-900 dark:text-white">{{ $counts['all'] ?? 0 }}</div>
        </a>
        <a href="{{ route('admin.tickets.index', ['status' => 'open']) }}" class="p-3.5 rounded-xl border transition {{ $status === 'open' ? 'bg-blue-50/70 border-blue-300 dark:bg-blue-950/40 dark:border-blue-700' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 hover:border-slate-300' }}">
            <div class="text-[11px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Open</div>
            <div class="text-xl font-black mt-1 text-blue-600 dark:text-blue-400">{{ $counts['open'] ?? 0 }}</div>
        </a>
        <a href="{{ route('admin.tickets.index', ['status' => 'in_progress']) }}" class="p-3.5 rounded-xl border transition {{ $status === 'in_progress' ? 'bg-purple-50/70 border-purple-300 dark:bg-purple-950/40 dark:border-purple-700' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 hover:border-slate-300' }}">
            <div class="text-[11px] font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400">In Progress</div>
            <div class="text-xl font-black mt-1 text-purple-600 dark:text-purple-400">{{ $counts['in_progress'] ?? 0 }}</div>
        </a>
        <a href="{{ route('admin.tickets.index', ['status' => 'answered']) }}" class="p-3.5 rounded-xl border transition {{ $status === 'answered' ? 'bg-emerald-50/70 border-emerald-300 dark:bg-emerald-950/40 dark:border-emerald-700' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 hover:border-slate-300' }}">
            <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Answered</div>
            <div class="text-xl font-black mt-1 text-emerald-600 dark:text-emerald-400">{{ $counts['answered'] ?? 0 }}</div>
        </a>
        <a href="{{ route('admin.tickets.index', ['status' => 'closed']) }}" class="p-3.5 rounded-xl border transition {{ $status === 'closed' ? 'bg-slate-100 border-slate-300 dark:bg-slate-800 dark:border-slate-700' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 hover:border-slate-300' }}">
            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Closed</div>
            <div class="text-xl font-black mt-1 text-slate-600 dark:text-slate-300">{{ $counts['closed'] ?? 0 }}</div>
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.tickets.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            @if(!empty($status))
                <input type="hidden" name="status" value="{{ $status }}">
            @endif

            <!-- Search -->
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by ticket #, subject, customer name/email, order #..." class="w-full h-10 px-3.5 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Department -->
            <div>
                <select name="department" onchange="this.form.submit()" class="w-full h-10 px-3 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-brand-500 cursor-pointer">
                    <option value="">All Departments</option>
                    <option value="technical" {{ $department === 'technical' ? 'selected' : '' }}>Technical Support</option>
                    <option value="refund" {{ $department === 'refund' ? 'selected' : '' }}>Refund Request</option>
                    <option value="return" {{ $department === 'return' ? 'selected' : '' }}>Product Return</option>
                    <option value="general" {{ $department === 'general' ? 'selected' : '' }}>General Inquiry</option>
                </select>
            </div>

            <!-- Priority -->
            <div>
                <select name="priority" onchange="this.form.submit()" class="w-full h-10 px-3 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-brand-500 cursor-pointer">
                    <option value="">All Priorities</option>
                    <option value="urgent" {{ $priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="high" {{ $priority === 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ $priority === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ $priority === 'low' ? 'selected' : '' }}>Low</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-800/70 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Ticket #</th>
                        <th class="px-5 py-3.5">Customer</th>
                        <th class="px-5 py-3.5">Department</th>
                        <th class="px-5 py-3.5">Subject</th>
                        <th class="px-5 py-3.5">Priority</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Last Updated</th>
                        <th class="px-5 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                            <td class="px-5 py-4 whitespace-nowrap font-mono font-bold text-xs text-brand-600 dark:text-brand-400">
                                <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="hover:underline">
                                    #{{ $ticket->ticket_number }}
                                </a>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="font-bold text-slate-900 dark:text-white text-xs">{{ $ticket->user->name ?? 'User' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $ticket->user->email ?? 'N/A' }}</div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                    {{ $ticket->department_label }}
                                </span>
                            </td>
                            <td class="px-5 py-4 max-w-xs">
                                <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="font-semibold text-slate-900 dark:text-white text-xs hover:text-brand-600 truncate block">
                                    {{ $ticket->subject }}
                                </a>
                                @if($ticket->order)
                                    <div class="text-[11px] text-slate-400 mt-0.5">
                                        Order: <strong class="text-slate-600 dark:text-slate-300">#{{ $ticket->order->order_number }}</strong>
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @php $pBadge = $ticket->priority_badge; @endphp
                                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full border {{ $pBadge['class'] }}">
                                    {{ $pBadge['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @php $sBadge = $ticket->status_badge; @endphp
                                <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full border {{ $sBadge['class'] }}">
                                    {{ $sBadge['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-xs text-slate-500 dark:text-slate-400">
                                {{ $ticket->last_reply_at ? $ticket->last_reply_at->diffForHumans() : $ticket->created_at->diffForHumans() }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-brand-800 hover:bg-brand-700 text-white rounded-lg text-xs font-bold transition shadow-xs">
                                    <i class="fa-solid fa-reply text-[10px]"></i>
                                    <span>Reply / Resolve</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-slate-400 dark:text-slate-500">
                                <i class="fa-solid fa-headset text-2xl mb-2 block opacity-40"></i>
                                <p class="font-medium text-slate-700 dark:text-slate-300">No support tickets found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

