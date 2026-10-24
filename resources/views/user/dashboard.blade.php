@extends('layouts.user')

@section('title', 'Dashboard - My Account - DigiGo')

@section('account_content')
<!-- 3-Column Action Tiles Grid Matching Reference Screenshot Exactly -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl">

    <!-- 1. Orders -->
    <a href="{{ route('user.orders') }}" class="group bg-white rounded-2xl border border-slate-200/90 p-8 flex flex-col items-center justify-center text-center shadow-xs hover:shadow-md hover:border-blue-500 hover:-translate-y-1 transition-all duration-200 min-h-[160px]">
        <div class="text-slate-400 group-hover:text-blue-600 transition-colors mb-3">
            <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
        </div>
        <h3 class="text-slate-800 group-hover:text-blue-700 font-semibold text-base transition-colors">Orders</h3>
    </a>

    <!-- 2. Subscriptions -->
    <a href="{{ route('user.subscriptions') }}" class="group bg-white rounded-2xl border border-slate-200/90 p-8 flex flex-col items-center justify-center text-center shadow-xs hover:shadow-md hover:border-blue-500 hover:-translate-y-1 transition-all duration-200 min-h-[160px]">
        <div class="text-slate-400 group-hover:text-blue-600 transition-colors mb-3">
            <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                <path d="M7 15h0M2 9.5h20"></path>
                <circle cx="16" cy="15" r="1.5"></circle>
            </svg>
        </div>
        <h3 class="text-slate-800 group-hover:text-blue-700 font-semibold text-base transition-colors">Subscriptions</h3>
    </a>

    <!-- 3. Addresses -->
    <a href="{{ route('user.addresses') }}" class="group bg-white rounded-2xl border border-slate-200/90 p-8 flex flex-col items-center justify-center text-center shadow-xs hover:shadow-md hover:border-blue-500 hover:-translate-y-1 transition-all duration-200 min-h-[160px]">
        <div class="text-slate-400 group-hover:text-blue-600 transition-colors mb-3">
            <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                <circle cx="12" cy="10" r="3"></circle>
            </svg>
        </div>
        <h3 class="text-slate-800 group-hover:text-blue-700 font-semibold text-base transition-colors">Addresses</h3>
    </a>

    <!-- 4. Account details -->
    <a href="{{ route('user.account-details') }}" class="group bg-white rounded-2xl border border-slate-200/90 p-8 flex flex-col items-center justify-center text-center shadow-xs hover:shadow-md hover:border-blue-500 hover:-translate-y-1 transition-all duration-200 min-h-[160px]">
        <div class="text-slate-400 group-hover:text-blue-600 transition-colors mb-3">
            <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="8" r="4"></circle>
                <path d="M20 21a8 8 0 1 0-16 0"></path>
                <circle cx="12" cy="12" r="10"></circle>
            </svg>
        </div>
        <h3 class="text-slate-800 group-hover:text-blue-700 font-semibold text-base transition-colors">Account details</h3>
    </a>

    <!-- 5. Affiliate -->
    <a href="{{ route('user.affiliate') }}" class="group bg-white rounded-2xl border border-slate-200/90 p-8 flex flex-col items-center justify-center text-center shadow-xs hover:shadow-md hover:border-blue-500 hover:-translate-y-1 transition-all duration-200 min-h-[160px]">
        <div class="text-slate-400 group-hover:text-blue-600 transition-colors mb-3">
            <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="8" r="6"></circle>
                <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
            </svg>
        </div>
        <h3 class="text-slate-800 group-hover:text-blue-700 font-semibold text-base transition-colors">Affiliate</h3>
    </a>

    <!-- 6. Withdraw -->
    <a href="{{ route('user.withdraw') }}" class="group bg-white rounded-2xl border border-slate-200/90 p-8 flex flex-col items-center justify-center text-center shadow-xs hover:shadow-md hover:border-blue-500 hover:-translate-y-1 transition-all duration-200 min-h-[160px]">
        <div class="text-slate-400 group-hover:text-blue-600 transition-colors mb-3">
            <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                <line x1="2" y1="10" x2="22" y2="10"></line>
            </svg>
        </div>
        <h3 class="text-slate-800 group-hover:text-blue-700 font-semibold text-base transition-colors">Withdraw</h3>
    </a>

    <!-- 7. Wishlist -->
    <a href="{{ route('user.wishlist') }}" class="group bg-white rounded-2xl border border-slate-200/90 p-8 flex flex-col items-center justify-center text-center shadow-xs hover:shadow-md hover:border-blue-500 hover:-translate-y-1 transition-all duration-200 min-h-[160px]">
        <div class="text-slate-400 group-hover:text-rose-500 transition-colors mb-3">
            <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
        </div>
        <h3 class="text-slate-800 group-hover:text-blue-700 font-semibold text-base transition-colors">Wishlist</h3>
    </a>

    <!-- 8. Logout -->
    <a href="javascript:void(0)" onclick="document.getElementById('sidebarLogoutForm').submit();" class="group bg-white rounded-2xl border border-slate-200/90 p-8 flex flex-col items-center justify-center text-center shadow-xs hover:shadow-md hover:border-red-400 hover:-translate-y-1 transition-all duration-200 min-h-[160px]">
        <div class="text-slate-400 group-hover:text-red-500 transition-colors mb-3">
            <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
        </div>
        <h3 class="text-slate-800 group-hover:text-red-600 font-semibold text-base transition-colors">Logout</h3>
    </a>

</div>
@endsection
