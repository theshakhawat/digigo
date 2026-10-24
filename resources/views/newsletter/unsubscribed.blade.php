@extends('layouts.site')

@section('title', 'Unsubscribed - ' . setting('site_name', 'DigiGo'))

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-8 text-center shadow-sm">
        <div class="w-16 h-16 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 mx-auto flex items-center justify-center text-2xl mb-5">
            <i class="fa-solid fa-check"></i>
        </div>

        <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mb-2">
            Unsubscribed Successfully
        </h1>

        <p class="text-sm text-slate-600 dark:text-slate-400 mb-6 leading-relaxed">
            @if($subscriber)
                <strong>{{ $subscriber->email }}</strong> has been unsubscribed from our promotional and newsletter emails. You will no longer receive marketing communications from us.
            @else
                You have been unsubscribed from our marketing mailing list.
            @endif
        </p>

        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition shadow-sm">
                <i class="fa-solid fa-store"></i>
                <span>Return to Store</span>
            </a>
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold transition">
                <span>Account Login</span>
            </a>
        </div>
    </div>
</div>
@endsection

