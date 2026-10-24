<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - {{ setting('site_name', 'DigiGo') }} Administration</title>

    <!-- Browser Favicon -->
    <link rel="icon" href="{{ default_site_favicon() }}">

    <!-- Google Fonts: Plus Jakarta Sans & Hind Siliguri -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN with dark mode enabled -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb', // DigiGo Vibrant Blue
                            700: '#1d4ed8',
                            800: '#1e40af', // DigiGo Royal Blue
                            900: '#1e3a8a',
                            950: '#0f172a',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Hind Siliguri', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Theme Initialization Script -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('digigo_admin_theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Hind Siliguri', sans-serif;
        }
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 9999px;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-slate-50 text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-100 flex flex-col selection:bg-brand-600 selection:text-white">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div id="adminSidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 hidden transition-opacity duration-300 md:hidden" aria-hidden="true"></div>

    <div class="min-h-full flex flex-row">

        <!-- =========================================================================
             SIDEBAR (Sidebar links are square / rounded-none; rest is normal)
             ========================================================================= -->
        <aside id="adminSidebar" class="fixed top-0 bottom-0 left-0 z-50 w-64 bg-white border-r border-slate-200 flex flex-col transition-transform duration-300 -translate-x-full md:translate-x-0 dark:bg-slate-900 dark:border-slate-800" aria-label="Admin Sidebar">

            <!-- Sidebar Top: Brand Logo Only (Strictly locked 64px height to match navbar) -->
            <div class="h-16 min-h-[4rem] max-h-16 box-border px-5 border-b border-slate-200 flex items-center justify-between dark:border-slate-800 shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center group overflow-hidden" title="Dashboard">
                    <img src="{{ default_site_logo() }}" alt="{{ setting('site_name', 'DigiGo') }}" class="h-9 max-h-9 w-auto max-w-[170px] object-contain shrink-0">
                </a>

                <!-- Mobile Close Sidebar Button -->
                <button type="button" id="adminSidebarCloseBtn" class="md:hidden text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Sidebar Navigation Links (SQUARE LINKS / rounded-none) -->
            <div class="flex-1 overflow-y-auto py-3">

                <!-- Section: GENERAL -->
                <div class="px-5 pb-2 pt-2">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">General</p>
                </div>

                <nav class="space-y-0.5">
                    <!-- 1. Dashboard Link (Square) -->
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between px-5 py-2.5 text-sm font-semibold rounded-none transition border-l-4 {{ request()->routeIs('admin.dashboard') ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-gauge-high w-4 text-center text-slate-400 dark:text-slate-500"></i>
                            <span>Dashboard</span>
                        </div>
                    </a>

                    <!-- Notifications Link (Square) -->
                    <a href="{{ route('admin.notifications.index') }}" class="flex items-center justify-between px-5 py-2.5 text-sm font-semibold rounded-none transition border-l-4 {{ request()->routeIs('admin.notifications.*') ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-bell w-4 text-center text-slate-400 dark:text-slate-500"></i>
                            <span>Notifications</span>
                        </div>
                        @if(($adminUnreadCount ?? 0) > 0)
                            <span class="bg-rose-500 text-white text-[10px] font-extrabold px-1.5 py-0.5 rounded-full shadow-xs">
                                {{ $adminUnreadCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Section: STORE CATALOG -->
                    <div class="px-5 pb-2 pt-4">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Store Catalog</p>
                    </div>

                    <!-- 2. Products Dropdown (Square) -->
                    <div class="sidebar-dropdown-group">
                        <button type="button" class="js-sidebar-dropdown-toggle w-full flex items-center justify-between px-5 py-2.5 text-sm font-medium rounded-none transition border-l-4 {{ request()->routeIs('admin.products.*') ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500 font-semibold' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }} cursor-pointer">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-box-archive w-4 text-center {{ request()->routeIs('admin.products.*') ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500' }}"></i>
                                <span>Products</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="bg-brand-50 text-brand-700 text-[10px] font-bold px-1.5 py-0.5 rounded-full dark:bg-slate-800 dark:text-brand-300">
                                    {{ $adminSidebarCounts['products'] ?? 0 }}
                                </span>
                                <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 js-chevron {{ request()->routeIs('admin.products.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </button>
                        <div class="sidebar-submenu {{ request()->routeIs('admin.products.*') ? '' : 'hidden' }} bg-slate-50/70 border-y border-slate-100 dark:bg-slate-950/60 dark:border-slate-800/60 py-1">
                            <a href="{{ route('admin.products.index') }}" class="flex items-center px-11 py-2 text-xs font-medium {{ request()->routeIs('admin.products.index') && !request('product_type') ? 'text-brand-600 font-semibold bg-brand-50/50 dark:text-brand-400 dark:bg-slate-800/50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40' }} rounded-none transition">
                                All Products
                            </a>
                            <a href="{{ route('admin.products.create') }}" class="flex items-center px-11 py-2 text-xs font-medium {{ request()->routeIs('admin.products.create') ? 'text-brand-600 font-semibold bg-brand-50/50 dark:text-brand-400 dark:bg-slate-800/50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40' }} rounded-none transition">
                                Add Product
                            </a>
                            <a href="{{ route('admin.products.index', ['product_type' => 'physical']) }}" class="flex items-center px-11 py-2 text-xs font-medium {{ request('product_type') === 'physical' ? 'text-brand-600 font-semibold bg-brand-50/50 dark:text-brand-400 dark:bg-slate-800/50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40' }} rounded-none transition">
                                📦 Physical Items
                            </a>
                            <a href="{{ route('admin.products.index', ['product_type' => 'digital']) }}" class="flex items-center px-11 py-2 text-xs font-medium {{ request('product_type') === 'digital' ? 'text-brand-600 font-semibold bg-brand-50/50 dark:text-brand-400 dark:bg-slate-800/50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40' }} rounded-none transition">
                                ⚡ Digital / Subs
                            </a>
                            <a href="{{ route('admin.categories.index') }}" class="flex items-center px-11 py-2 text-xs font-medium {{ request()->routeIs('admin.categories.*') ? 'text-brand-600 font-semibold bg-brand-50/50 dark:text-brand-400 dark:bg-slate-800/50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40' }} rounded-none transition">
                                Categories
                            </a>
                            <a href="{{ route('admin.brands.index') }}" class="flex items-center px-11 py-2 text-xs font-medium {{ request()->routeIs('admin.brands.*') ? 'text-brand-600 font-semibold bg-brand-50/50 dark:text-brand-400 dark:bg-slate-800/50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40' }} rounded-none transition">
                                Brands
                            </a>
                        </div>
                    </div>

                    <!-- Categories Link (Dedicated Square Sidebar Link) -->
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center justify-between px-5 py-2.5 text-sm font-semibold rounded-none transition border-l-4 {{ request()->routeIs('admin.categories.*') ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-layer-group w-4 text-center text-slate-400 dark:text-slate-500"></i>
                            <span>Categories</span>
                        </div>
                    </a>

                    <!-- Brands Link (Dedicated Square Sidebar Link) -->
                    <a href="{{ route('admin.brands.index') }}" class="flex items-center justify-between px-5 py-2.5 text-sm font-semibold rounded-none transition border-l-4 {{ request()->routeIs('admin.brands.*') ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-certificate w-4 text-center text-slate-400 dark:text-slate-500"></i>
                            <span>Brands</span>
                        </div>
                    </a>

                    <!-- 3. Orders Dropdown (Square) -->
                    <div class="sidebar-dropdown-group">
                        <button type="button" class="js-sidebar-dropdown-toggle w-full flex items-center justify-between px-5 py-2.5 text-sm font-medium rounded-none transition border-l-4 {{ request()->routeIs('admin.orders.*') ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }} cursor-pointer">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-cart-shopping w-4 text-center text-slate-400 dark:text-slate-500"></i>
                                <span>Orders</span>
                            </div>
                            <div class="flex items-center gap-2">
                                @php
                                    $newOrdersCount = $adminSidebarCounts['pending_orders'] ?? 0;
                                @endphp
                                @if($newOrdersCount > 0)
                                    <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-1.5 py-0.5 rounded-full dark:bg-amber-950 dark:text-amber-300">{{ $newOrdersCount }} New</span>
                                @endif
                                <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 js-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </button>
                        <div class="sidebar-submenu {{ request()->routeIs('admin.orders.*') ? '' : 'hidden' }} bg-slate-50/70 border-y border-slate-100 dark:bg-slate-950/60 dark:border-slate-800/60 py-1">
                            <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-between pl-11 pr-5 py-2 text-xs font-medium {{ request()->routeIs('admin.orders.index') && !request('status') ? 'text-brand-600 font-bold bg-slate-100/80 dark:text-brand-400' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40' }} rounded-none transition">
                                <span>All Orders</span>
                                @if(($adminSidebarCounts['total_orders'] ?? 0) > 0)
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-slate-200/80 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                        {{ $adminSidebarCounts['total_orders'] }}
                                    </span>
                                @endif
                            </a>
                            <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}" class="flex items-center justify-between pl-11 pr-5 py-2 text-xs font-medium {{ request('status') === 'confirmed' ? 'text-brand-600 font-bold bg-slate-100/80 dark:text-brand-400' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40' }} rounded-none transition">
                                <span>Confirmed Orders</span>
                                @if(($adminSidebarCounts['confirmed_orders'] ?? 0) > 0)
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300">
                                        {{ $adminSidebarCounts['confirmed_orders'] }}
                                    </span>
                                @endif
                            </a>
                            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="flex items-center justify-between pl-11 pr-5 py-2 text-xs font-medium {{ request('status') === 'pending' ? 'text-brand-600 font-bold bg-slate-100/80 dark:text-brand-400' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40' }} rounded-none transition">
                                <span>Pending Orders</span>
                                @if(($adminSidebarCounts['pending_orders'] ?? 0) > 0)
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                                        {{ $adminSidebarCounts['pending_orders'] }}
                                    </span>
                                @endif
                            </a>
                            <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="flex items-center justify-between pl-11 pr-5 py-2 text-xs font-medium {{ request('status') === 'completed' ? 'text-brand-600 font-bold bg-slate-100/80 dark:text-brand-400' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40' }} rounded-none transition">
                                <span>Completed Orders</span>
                                @if(($adminSidebarCounts['completed_orders'] ?? 0) > 0)
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                        {{ $adminSidebarCounts['completed_orders'] }}
                                    </span>
                                @endif
                            </a>
                        </div>
                    </div>

                    <!-- Subscriptions Menu Item -->
                    <a href="{{ route('admin.subscriptions.index') }}" class="flex items-center justify-between px-5 py-2.5 text-sm font-medium rounded-none transition border-l-4 {{ request()->routeIs('admin.subscriptions.*') ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }} cursor-pointer">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-arrows-spin w-4 text-center text-slate-400 dark:text-slate-500"></i>
                            <span>Subscriptions</span>
                        </div>
                        @php
                            $pendingSubCount = $adminSidebarCounts['pending_subscriptions'] ?? 0;
                        @endphp
                        @if($pendingSubCount > 0)
                            <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-1.5 py-0.5 rounded-full dark:bg-amber-950 dark:text-amber-300">{{ $pendingSubCount }} Pending</span>
                        @endif
                    </a>

                    <!-- Payments Menu Item -->
                    <a href="{{ route('admin.payments.index') }}" class="flex items-center justify-between px-5 py-2.5 text-sm font-medium rounded-none transition border-l-4 {{ request()->routeIs('admin.payments.*') ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }} cursor-pointer">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-credit-card w-4 text-center text-slate-400 dark:text-slate-500"></i>
                            <span>Payments</span>
                        </div>
                    </a>

                    <!-- Withdrawals Menu Item -->
                    <a href="{{ route('admin.withdrawals.index') }}" class="flex items-center justify-between px-5 py-2.5 text-sm font-medium rounded-none transition border-l-4 {{ request()->routeIs('admin.withdrawals.*') ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }} cursor-pointer">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-money-bill-transfer w-4 text-center text-slate-400 dark:text-slate-500"></i>
                            <span>Withdrawals</span>
                        </div>
                        @php
                            $pendingWithdrawCount = $adminSidebarCounts['pending_withdrawals'] ?? 0;
                        @endphp
                        @if($pendingWithdrawCount > 0)
                            <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-1.5 py-0.5 rounded-full dark:bg-amber-950 dark:text-amber-300">{{ $pendingWithdrawCount }} New</span>
                        @endif
                    </a>

                    <!-- Support Tickets Menu Item -->
                    <a href="{{ route('admin.tickets.index') }}" class="flex items-center justify-between px-5 py-2.5 text-sm font-medium rounded-none transition border-l-4 {{ request()->routeIs('admin.tickets.*') ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }} cursor-pointer">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-headset w-4 text-center text-slate-400 dark:text-slate-500"></i>
                            <span>Support Tickets</span>
                        </div>
                        @php
                            $openTicketsCount = $adminSidebarCounts['open_tickets'] ?? 0;
                        @endphp
                        @if($openTicketsCount > 0)
                            <span class="bg-rose-100 text-rose-800 text-[10px] font-bold px-1.5 py-0.5 rounded-full dark:bg-rose-950 dark:text-rose-300">{{ $openTicketsCount }} Open</span>
                        @endif
                    </a>

                    <!-- 5. Customers & Admins Dropdown (Square) -->
                    <div class="sidebar-dropdown-group">
                        <button type="button" class="js-sidebar-dropdown-toggle w-full flex items-center justify-between px-5 py-2.5 text-sm font-medium rounded-none transition border-l-4 {{ request()->routeIs('admin.users.*') ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }} cursor-pointer">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-users w-4 text-center text-slate-400 dark:text-slate-500"></i>
                                <span>Users</span>
                            </div>
                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 js-chevron {{ request()->routeIs('admin.users.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="sidebar-submenu {{ request()->routeIs('admin.users.*') ? '' : 'hidden' }} bg-slate-50/70 border-y border-slate-100 dark:bg-slate-950/60 dark:border-slate-800/60 py-1">
                            <a href="{{ route('admin.users.index') }}" class="flex items-center px-11 py-2 text-xs font-medium {{ request()->routeIs('admin.users.index') && !request('role') ? 'text-brand-600 font-semibold bg-brand-50/50 dark:text-brand-400 dark:bg-slate-800/50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40' }} rounded-none transition">
                                All Users
                            </a>
                            <a href="{{ route('admin.users.index', ['role' => 'user']) }}" class="flex items-center px-11 py-2 text-xs font-medium {{ request('role') === 'user' ? 'text-brand-600 font-semibold bg-brand-50/50 dark:text-brand-400 dark:bg-slate-800/50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40' }} rounded-none transition">
                                Customers
                            </a>
                            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="flex items-center px-11 py-2 text-xs font-medium {{ request('role') === 'admin' ? 'text-brand-600 font-semibold bg-brand-50/50 dark:text-brand-400 dark:bg-slate-800/50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40' }} rounded-none transition">
                                Staff & Admins
                            </a>
                        </div>
                    </div>

                    <!-- 6. Coupons & Discounts (Square Single Link) -->
                    <a href="{{ route('admin.coupons.index') }}" class="flex items-center justify-between px-5 py-2.5 text-sm font-medium rounded-none transition border-l-4 {{ request()->routeIs('admin.coupons.*') ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500 font-semibold' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-ticket w-4 text-center {{ request()->routeIs('admin.coupons.*') ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500' }}"></i>
                            <span>Coupons</span>
                        </div>
                        <span class="bg-indigo-50 text-indigo-700 text-[10px] font-bold px-1.5 py-0.5 rounded-full dark:bg-indigo-950 dark:text-indigo-300">
                            {{ $adminSidebarCounts['coupons'] ?? 0 }}
                        </span>
                    </a>

                    <!-- Subscribers Menu Item -->
                    <a href="{{ route('admin.subscribers.index') }}" class="flex items-center justify-between px-5 py-2.5 text-sm font-medium rounded-none transition border-l-4 {{ request()->routeIs('admin.subscribers.*') ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500 font-semibold' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-envelope-open-text w-4 text-center {{ request()->routeIs('admin.subscribers.*') ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500' }}"></i>
                            <span>Subscribers</span>
                        </div>
                        <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-1.5 py-0.5 rounded-full dark:bg-emerald-950 dark:text-emerald-300">
                            {{ $adminSidebarCounts['subscribers'] ?? 0 }}
                        </span>
                    </a>

                    <!-- Blog Dropdown (Square) -->
                    <div class="sidebar-dropdown-group">
                        <button type="button" class="js-sidebar-dropdown-toggle w-full flex items-center justify-between px-5 py-2.5 text-sm font-medium rounded-none transition border-l-4 {{ request()->routeIs('admin.blog.*') ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500 font-semibold' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }} cursor-pointer">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-newspaper w-4 text-center {{ request()->routeIs('admin.blog.*') ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500' }}"></i>
                                <span>Blog</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-1.5 py-0.5 rounded-full dark:bg-blue-950 dark:text-blue-300">
                                    {{ $adminSidebarCounts['blog_posts'] ?? 0 }}
                                </span>
                                <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 js-chevron {{ request()->routeIs('admin.blog.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </button>
                        <div class="sidebar-submenu {{ request()->routeIs('admin.blog.*') ? '' : 'hidden' }} bg-slate-50/70 border-y border-slate-100 dark:bg-slate-950/60 dark:border-slate-800/60 py-1">
                            <a href="{{ route('admin.blog.categories.index') }}" class="flex items-center justify-between px-11 py-2 text-xs font-medium {{ request()->routeIs('admin.blog.categories.*') ? 'text-brand-600 font-semibold bg-brand-50/50 dark:text-brand-400 dark:bg-slate-800/50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40' }} rounded-none transition">
                                <span>Category</span>
                                <span class="text-[10px] text-slate-400">{{ $adminSidebarCounts['blog_categories'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('admin.blog.posts.index') }}" class="flex items-center justify-between px-11 py-2 text-xs font-medium {{ request()->routeIs('admin.blog.posts.*') ? 'text-brand-600 font-semibold bg-brand-50/50 dark:text-brand-400 dark:bg-slate-800/50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40' }} rounded-none transition">
                                <span>Posts</span>
                                <span class="text-[10px] text-slate-400">{{ $adminSidebarCounts['blog_posts'] ?? 0 }}</span>
                            </a>
                        </div>
                    </div>

                    <!-- Section: WEBSITE APPEARANCE -->
                    <div class="px-5 pb-2 pt-4">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Website Appearance</p>
                    </div>

                    <!-- Header Link (Dedicated Square Sidebar Link) -->
                    <a href="{{ route('admin.appearance.header') }}" class="flex items-center justify-between px-5 py-2.5 text-sm font-semibold rounded-none transition border-l-4 {{ request()->routeIs('admin.appearance.header*') ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-window-maximize w-4 text-center text-slate-400 dark:text-slate-500"></i>
                            <span>Header</span>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300">Live</span>
                    </a>

                    <!-- Banners Link (Dedicated Square Sidebar Link) -->
                    <a href="{{ route('admin.appearance.banners.index', ['tab' => 'slides']) }}" class="flex items-center justify-between px-5 py-2.5 text-sm font-semibold rounded-none transition border-l-4 {{ request()->routeIs('admin.appearance.banners.*') && !in_array(request('tab'), ['ticker', 'promo']) ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-images w-4 text-center text-slate-400 dark:text-slate-500"></i>
                            <span>Hero Banners</span>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300">Live</span>
                    </a>

                    <!-- Trust Ticker Link (Dedicated Square Sidebar Link) -->
                    <a href="{{ route('admin.appearance.banners.index', ['tab' => 'ticker']) }}" class="flex items-center justify-between px-5 py-2.5 text-sm font-semibold rounded-none transition border-l-4 {{ request()->routeIs('admin.appearance.banners.*') && request('tab') === 'ticker' ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-bullhorn w-4 text-center text-slate-400 dark:text-slate-500"></i>
                            <span>Trust Ticker</span>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 dark:bg-purple-950 dark:text-purple-300">Live</span>
                    </a>

                    <!-- Promo Showcase Banner Link (Dedicated Square Sidebar Link) -->
                    <a href="{{ route('admin.appearance.banners.index', ['tab' => 'promo']) }}" class="flex items-center justify-between px-5 py-2.5 text-sm font-semibold rounded-none transition border-l-4 {{ request()->routeIs('admin.appearance.banners.*') && request('tab') === 'promo' ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-rectangle-ad w-4 text-center text-slate-400 dark:text-slate-500"></i>
                            <span>Promo Showcase</span>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300">Live</span>
                    </a>

                    <!-- 3-Card Highlights Promo Link (Dedicated Square Sidebar Link) -->
                    <a href="{{ route('admin.appearance.banners.index', ['tab' => 'tri_promo']) }}" class="flex items-center justify-between px-5 py-2.5 text-sm font-semibold rounded-none transition border-l-4 {{ request()->routeIs('admin.appearance.banners.*') && request('tab') === 'tri_promo' ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-table-columns w-4 text-center text-slate-400 dark:text-slate-500"></i>
                            <span>3-Card Highlights</span>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">Live</span>
                    </a>

                    <!-- 2-Card Coupon Discount Promo Link (Dedicated Square Sidebar Link) -->
                    <a href="{{ route('admin.appearance.banners.index', ['tab' => 'dual_promo']) }}" class="flex items-center justify-between px-5 py-2.5 text-sm font-semibold rounded-none transition border-l-4 {{ request()->routeIs('admin.appearance.banners.*') && request('tab') === 'dual_promo' ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-tags w-4 text-center text-slate-400 dark:text-slate-500"></i>
                            <span>Coupon & Deals (2-Cards)</span>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300">New</span>
                    </a>

                    <!-- Remini AI Banner Link (Dedicated Square Sidebar Link) -->
                    <a href="{{ route('admin.appearance.banners.index', ['tab' => 'remini']) }}" class="flex items-center justify-between px-5 py-2.5 text-sm font-semibold rounded-none transition border-l-4 {{ request()->routeIs('admin.appearance.banners.*') && request('tab') === 'remini' ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-wand-magic-sparkles w-4 text-center text-slate-400 dark:text-slate-500"></i>
                            <span>AI Tool Banner</span>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-300">New</span>
                    </a>

                    <!-- Footer Link (Dedicated Square Sidebar Link) -->
                    <a href="{{ route('admin.appearance.footer') }}" class="flex items-center justify-between px-5 py-2.5 text-sm font-semibold rounded-none transition border-l-4 {{ request()->routeIs('admin.appearance.footer*') ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-table-cells-large w-4 text-center text-slate-400 dark:text-slate-500"></i>
                            <span>Footer</span>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">Live</span>
                    </a>

                    <!-- Section: CONFIGURATION -->
                    <div class="px-5 pb-2 pt-4">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Configuration</p>
                    </div>

                    <!-- 7. Reports & Analytics Dropdown (Square) -->
                    <div class="sidebar-dropdown-group">
                        <button type="button" class="js-sidebar-dropdown-toggle w-full flex items-center justify-between px-5 py-2.5 text-sm font-medium rounded-none transition border-l-4 {{ request()->routeIs('admin.reports.*') ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500 font-semibold' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }} cursor-pointer">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-chart-line w-4 text-center {{ request()->routeIs('admin.reports.*') ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500' }}"></i>
                                <span>Reports</span>
                            </div>
                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 js-chevron {{ request()->routeIs('admin.reports.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="sidebar-submenu {{ request()->routeIs('admin.reports.*') ? '' : 'hidden' }} bg-slate-50/70 border-y border-slate-100 dark:bg-slate-950/60 dark:border-slate-800/60 py-1">
                            <a href="{{ route('admin.reports.sales') }}" class="flex items-center px-11 py-2 text-xs font-medium {{ request()->routeIs('admin.reports.sales*') ? 'text-brand-600 font-semibold bg-brand-50/50 dark:text-brand-400 dark:bg-slate-800/50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40' }} rounded-none transition">
                                Sales Report
                            </a>
                            <a href="{{ route('admin.reports.customer-growth') }}" class="flex items-center px-11 py-2 text-xs font-medium {{ request()->routeIs('admin.reports.customer-growth*') ? 'text-brand-600 font-semibold bg-brand-50/50 dark:text-brand-400 dark:bg-slate-800/50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40' }} rounded-none transition">
                                Customer Growth
                            </a>
                        </div>
                    </div>

                    <!-- 8. System Settings Dropdown (Square) -->
                    <div class="sidebar-dropdown-group">
                        <button type="button" class="js-sidebar-dropdown-toggle w-full flex items-center justify-between px-5 py-2.5 text-sm rounded-none transition border-l-4 {{ request()->routeIs('admin.settings.*') ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500 font-semibold' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60 font-medium' }} cursor-pointer">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-gear w-4 text-center text-slate-400 dark:text-slate-500"></i>
                                <span>Settings</span>
                            </div>
                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 js-chevron {{ request()->routeIs('admin.settings.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="sidebar-submenu {{ request()->routeIs('admin.settings.*') ? '' : 'hidden' }} bg-slate-50/70 border-y border-slate-100 dark:bg-slate-950/60 dark:border-slate-800/60 py-1">
                            <a href="{{ route('admin.settings.shipping') }}" class="flex items-center px-11 py-2 text-xs font-medium {{ request()->routeIs('admin.settings.shipping*') ? 'text-brand-600 font-semibold bg-brand-50/50 dark:text-brand-400 dark:bg-slate-800/50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40' }} rounded-none transition">
                                Shipping & Delivery
                            </a>
                            <a href="{{ route('admin.settings.affiliate') }}" class="flex items-center px-11 py-2 text-xs font-medium {{ request()->routeIs('admin.settings.affiliate*') ? 'text-brand-600 font-semibold bg-brand-50/50 dark:text-brand-400 dark:bg-slate-800/50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40' }} rounded-none transition">
                                Affiliate Commission
                            </a>
                            <a href="{{ route('admin.settings.smtp') }}" class="flex items-center px-11 py-2 text-xs font-medium {{ request()->routeIs('admin.settings.smtp*') ? 'text-brand-600 font-semibold bg-brand-50/50 dark:text-brand-400 dark:bg-slate-800/50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40' }} rounded-none transition">
                                SMTP & Email Settings
                            </a>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Sidebar Bottom: Live Status Info (Normal Rounded Status Dot) -->
            <div class="p-4 border-t border-slate-200 dark:border-slate-800 shrink-0 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between text-xs text-slate-500">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>v1.0.0</span>
                </div>
                <a href="{{ url('/') }}" target="_blank" class="text-brand-600 hover:underline dark:text-brand-400 font-medium">Store ↗</a>
            </div>
        </aside>

        <!-- =========================================================================
             MAIN CONTENT WRAPPER
             ========================================================================= -->
        <div class="flex-1 flex flex-col min-w-0 md:pl-64">

            <!-- =========================================================================
                 NAVBAR (Normal Rounded Buttons, Inputs, Dropdowns - Strict 64px height)
                 ========================================================================= -->
            <header class="h-16 min-h-[4rem] max-h-16 box-border shrink-0 bg-white border-b border-slate-200 sticky top-0 z-30 px-4 sm:px-6 flex items-center justify-between dark:bg-slate-900 dark:border-slate-800">

                <!-- Left Section: Mobile Toggle & Global Search Bar -->
                <div class="flex items-center gap-3 sm:gap-4 flex-1">
                    <!-- Mobile Hamburger Button (Normal Rounded) -->
                    <button type="button" id="adminSidebarOpenBtn" class="md:hidden w-9 h-9 flex items-center justify-center text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer" aria-label="Open Sidebar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    <!-- Global Search Bar & Quick Navigation Dropdown -->
                    <div class="relative w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg" id="adminGlobalSearchWrapper">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text"
                               id="globalSearchInput"
                               placeholder="Search pages, settings, actions (Press '/' or Ctrl+K)..."
                               autocomplete="off"
                               class="w-full h-9 pl-9 pr-12 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 dark:focus:border-brand-500 transition">
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                            <kbd class="hidden sm:inline-block px-1.5 py-0.5 text-[10px] font-semibold text-slate-400 bg-white border border-slate-200 rounded-md dark:bg-slate-900 dark:border-slate-700">Ctrl+K</kbd>
                        </div>

                        <!-- Dropdown Panel (Categorized Navigation Actions) -->
                        <div id="adminGlobalSearchDropdown"
                             class="hidden absolute left-0 right-0 mt-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl z-50 overflow-hidden max-h-[75vh] flex flex-col">
                            
                            <div class="p-3 bg-slate-50/70 dark:bg-slate-800/40 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
                                <span class="font-bold uppercase tracking-wider text-[10px]">Quick Actions & Page Links</span>
                                <span class="text-[10px]">Navigate with <kbd class="px-1 py-0.5 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded text-[9px]">↑</kbd> <kbd class="px-1 py-0.5 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded text-[9px]">↓</kbd></span>
                            </div>

                            <div class="overflow-y-auto p-2 space-y-4 divide-y divide-slate-100 dark:divide-slate-800/60" id="searchItemsContainer">
                                
                                <!-- Group 1: General & Overview -->
                                <div class="search-group space-y-1">
                                    <p class="px-3 pt-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">Overview & System</p>
                                    
                                    <a href="{{ route('admin.dashboard') }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="dashboard overview stats analytics home admin">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 text-sm">
                                                <i class="fa-solid fa-gauge-high"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">Dashboard</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Store performance metrics & revenue overview</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>

                                    <a href="{{ route('admin.notifications.index') }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="notifications alerts unread system">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 text-sm">
                                                <i class="fa-solid fa-bell"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">Notifications Center</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Check new orders, registrations, and system alerts</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>
                                </div>

                                <!-- Group 2: Products & Catalog -->
                                <div class="search-group space-y-1 pt-2">
                                    <p class="px-3 pt-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">Products & Catalog</p>
                                    
                                    <a href="{{ route('admin.products.index') }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="products all products catalog inventory item">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 text-sm">
                                                <i class="fa-solid fa-box-archive"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">All Products Catalog</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Manage full product inventory and pricing</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>

                                    <a href="{{ route('admin.products.create') }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="add product create new digital physical item">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 flex items-center justify-center shrink-0 text-sm font-bold">
                                                <i class="fa-solid fa-plus"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">Add New Product</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Create digital subscription or physical gadget item</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>

                                    <a href="{{ route('admin.products.index', ['product_type' => 'digital']) }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="digital subscriptions license keys plans tools software">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 text-sm">
                                                <i class="fa-solid fa-bolt"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">Digital & Subscription Products</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Filter software, AI tools, and license subscriptions</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>

                                    <a href="{{ route('admin.categories.index') }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="categories product categories browse taxonomy">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 text-sm">
                                                <i class="fa-solid fa-layer-group"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">Product Categories</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Manage store collections & categories hierarchy</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>

                                    <a href="{{ route('admin.brands.index') }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="brands brand manufacturers logos">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 text-sm">
                                                <i class="fa-solid fa-certificate"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">Brands Management</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Manage product brands and logos</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>
                                </div>

                                <!-- Group 3: Sales, Orders & Subscriptions -->
                                <div class="search-group space-y-1 pt-2">
                                    <p class="px-3 pt-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">Sales & Operations</p>
                                    
                                    <a href="{{ route('admin.orders.index') }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="orders all orders sales customer checkout delivery">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 text-sm">
                                                <i class="fa-solid fa-cart-shopping"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">Customer Orders</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Process orders, change delivery status & invoices</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>

                                    <a href="{{ route('admin.subscriptions.index') }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="subscriptions digital access license recurring expiring active">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-cyan-50 dark:bg-cyan-950/60 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0 text-sm">
                                                <i class="fa-solid fa-arrows-spin"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">Active Subscriptions</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Manage customer digital subscription validity & credentials</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>

                                    <a href="{{ route('admin.payments.index') }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="payments transactions payment status paid gateway invoice">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 text-sm">
                                                <i class="fa-solid fa-credit-card"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">Payment Transactions</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Review paid invoices and gateway transaction IDs</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>

                                    <a href="{{ route('admin.withdrawals.index') }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="withdrawals affiliate payout requests approve reject">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 text-sm">
                                                <i class="fa-solid fa-money-bill-transfer"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">Withdrawal Requests</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Process affiliate partner payout withdrawal requests</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>

                                    <a href="{{ route('admin.coupons.index') }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="coupons discounts promo voucher promo code">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 text-sm">
                                                <i class="fa-solid fa-ticket"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">Coupons & Promo Codes</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Create discount codes and manage coupon usage limits</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>
                                </div>

                                <!-- Group 4: Users & Audience -->
                                <div class="search-group space-y-1 pt-2">
                                    <p class="px-3 pt-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">Users & Audience</p>
                                    
                                    <a href="{{ route('admin.users.index') }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="users customer members account list">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 text-sm">
                                                <i class="fa-solid fa-users"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">Users Directory</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">View all registered customers and system administrators</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>

                                    <a href="{{ route('admin.users.create') }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="add user create admin new customer">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300 flex items-center justify-center shrink-0 text-sm font-bold">
                                                <i class="fa-solid fa-user-plus"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">Add New User / Admin</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Create customer or administrator account with credentials</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>

                                    <a href="{{ route('admin.subscribers.index') }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="subscribers newsletter mailing list email audience">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 text-sm">
                                                <i class="fa-solid fa-envelope-open-text"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">Newsletter Subscribers</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Manage active email subscribers & marketing list</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>
                                </div>

                                <!-- Group 5: Appearance & Customization -->
                                <div class="search-group space-y-1 pt-2">
                                    <p class="px-3 pt-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">Appearance & Theme</p>
                                    
                                    <a href="{{ route('admin.appearance.header') }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="header logo favicon appearance branding hotline support phone">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 text-sm">
                                                <i class="fa-solid fa-window-maximize"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">Header & Branding Settings</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Website logo, favicon, topbar support hotline</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>

                                    <a href="{{ route('admin.appearance.banners.index', ['tab' => 'slides']) }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="banners slider hero banners slides homepage appearance">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 text-sm">
                                                <i class="fa-solid fa-images"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">Hero Slider Banners</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Customize main homepage sliding carousel banners</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>

                                    <a href="{{ route('admin.appearance.banners.index', ['tab' => 'ticker']) }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="ticker marquee trust live marquee ticker banner">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 text-sm">
                                                <i class="fa-solid fa-bullhorn"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">Live Trust Ticker Marquee</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Configure scrolling announcements, links, and icons</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>

                                    <a href="{{ route('admin.appearance.footer') }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="footer appearance social links payment badges copyright footer logo">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center shrink-0 text-sm">
                                                <i class="fa-solid fa-table-cells-large"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">Footer Customization</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Social links, payment badges (DGEPay), contact & copyright</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>
                                </div>

                                <!-- Group 6: Settings & Configuration -->
                                <div class="search-group space-y-1 pt-2">
                                    <p class="px-3 pt-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">Settings & System</p>
                                    
                                    <a href="{{ route('admin.settings.shipping') }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="shipping delivery delivery charges rates settings dhaka outside">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 text-sm">
                                                <i class="fa-solid fa-truck-fast"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">Shipping & Delivery Rates</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Inside/Outside Dhaka delivery fees & free shipping rules</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>

                                    <a href="{{ route('admin.settings.affiliate') }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="affiliate commission rates earnings partner settings">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 text-sm">
                                                <i class="fa-solid fa-handshake"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">Affiliate Program Settings</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Global and category-based referral commission percentages</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>

                                    <a href="{{ route('admin.settings.smtp') }}" class="search-item group flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-slate-800 dark:hover:text-brand-400 transition" data-keywords="smtp email mail configuration mailer host port test email">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 text-sm">
                                                <i class="fa-solid fa-envelope-circle-check"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold leading-snug truncate">SMTP Email Server</p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Mail driver configuration & send test email verification</p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all"></i>
                                    </a>
                                </div>

                            </div>

                            <!-- Empty search result state -->
                            <div id="searchNoResults" class="hidden p-8 text-center text-slate-400">
                                <i class="fa-solid fa-magnifying-glass text-2xl mb-2 opacity-50 block"></i>
                                <p class="text-xs font-bold text-slate-600 dark:text-slate-300">No matching action or page found</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Try searching for keywords like "products", "shipping", "orders", "banners", etc.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Section: Store Link, Theme Toggle, Notifications & User Menu -->
                <div class="flex items-center gap-2 sm:gap-3">

                    <!-- Store View Link (Normal Rounded) -->
                    <a href="{{ url('/') }}" target="_blank" class="hidden lg:inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-brand-600 border border-slate-200 hover:border-brand-300 dark:text-slate-400 dark:border-slate-700 dark:hover:text-white rounded-lg transition" title="Go to storefront">
                        <i class="fa-solid fa-arrow-up-right-from-square text-[11px]"></i>
                        <span>Visit Store</span>
                    </a>

                    <!-- Cache Clear / Refresh Button (With Spinner Animation) -->
                    <button type="button"
                            id="adminClearCacheBtn"
                            data-url="{{ route('admin.system.clear-cache') }}"
                            class="w-9 h-9 flex items-center justify-center text-slate-600 hover:text-brand-600 dark:text-slate-400 dark:hover:text-brand-400 border border-slate-200 dark:border-slate-700 rounded-lg bg-slate-50 hover:bg-white dark:bg-slate-800 transition cursor-pointer relative group"
                            title="Clear Cache, Views, Routes & Events"
                            aria-label="Clear Cache">
                        <i id="adminClearCacheIcon" class="fa-solid fa-rotate text-sm transition-transform duration-500"></i>
                    </button>

                    <!-- 1. Dark / Light Mode Toggle Button (Normal Rounded) -->
                    <button type="button" id="themeToggleBtn" class="w-9 h-9 flex items-center justify-center text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-amber-400 border border-slate-200 dark:border-slate-700 rounded-lg bg-slate-50 hover:bg-white dark:bg-slate-800 transition cursor-pointer" aria-label="Toggle Theme" title="Toggle Dark/Light Mode">
                        <!-- Sun Icon (Shown in Dark Mode) -->
                        <svg class="w-4 h-4 hidden dark:block text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <!-- Moon Icon (Shown in Light Mode) -->
                        <svg class="w-4 h-4 block dark:hidden text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>

                    <!-- 2. Notification Dropdown (Dynamic Real-time) -->
                    <div class="relative" id="notificationWrapper">
                        <button type="button" id="notificationBtn" class="w-9 h-9 flex items-center justify-center text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white border border-slate-200 dark:border-slate-700 rounded-lg bg-slate-50 hover:bg-white dark:bg-slate-800 transition relative cursor-pointer" aria-label="Notifications" title="Notifications">
                            <i class="fa-regular fa-bell text-sm"></i>
                            @if(($adminUnreadCount ?? 0) > 0)
                                <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-500 text-white font-extrabold text-[10px] flex items-center justify-center shadow-xs">
                                    {{ $adminUnreadCount > 9 ? '9+' : $adminUnreadCount }}
                                </span>
                            @endif
                        </button>

                        <!-- Notification Dropdown Panel -->
                        <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-[calc(100vw-2rem)] max-w-sm sm:w-96 bg-white border border-slate-200 shadow-xl rounded-2xl py-2 z-50 dark:bg-slate-900 dark:border-slate-800 overflow-hidden">
                            <div class="px-4 py-2.5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/30">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200">Notifications</span>
                                    @if(($adminUnreadCount ?? 0) > 0)
                                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300">
                                            {{ $adminUnreadCount }} new
                                        </span>
                                    @endif
                                </div>
                                @if(($adminUnreadCount ?? 0) > 0)
                                    <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-[11px] font-semibold text-brand-600 dark:text-brand-400 cursor-pointer hover:underline">
                                            Mark all read
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="divide-y divide-slate-100 dark:divide-slate-800/80 max-h-80 overflow-y-auto overflow-x-hidden">
                                @forelse(($adminNotifications ?? []) as $notif)
                                    <div class="group relative flex items-start gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition {{ !$notif->is_read ? 'bg-blue-50/40 dark:bg-blue-950/20' : '' }}">
                                        <a href="{{ route('admin.notifications.read', $notif->id) }}" class="flex-1 flex items-start gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 text-xs shadow-xs
                                                {{ $notif->type === 'order' ? 'bg-blue-100 text-blue-600 dark:bg-blue-950 dark:text-blue-400' :
                                                   ($notif->type === 'user' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400' :
                                                   ($notif->type === 'subscription' ? 'bg-purple-100 text-purple-600 dark:bg-purple-950 dark:text-purple-400' :
                                                   ($notif->type === 'product' ? 'bg-rose-100 text-rose-600 dark:bg-rose-950 dark:text-rose-400' : 'bg-amber-100 text-amber-600 dark:bg-amber-950 dark:text-amber-400'))) }}">
                                                <i class="fa-solid {{ $notif->type === 'order' ? 'fa-bag-shopping' : ($notif->type === 'user' ? 'fa-user-plus' : ($notif->type === 'subscription' ? 'fa-key' : ($notif->type === 'product' ? 'fa-triangle-exclamation' : 'fa-bell'))) }}"></i>
                                            </div>
                                            <div class="text-xs flex-1 min-w-0 pr-6">
                                                <div class="flex items-center gap-1.5">
                                                    <p class="font-bold text-slate-800 dark:text-slate-200 truncate {{ !$notif->is_read ? 'text-blue-900 dark:text-blue-200' : '' }}" title="{{ $notif->title }}">
                                                        {{ $notif->title }}
                                                    </p>
                                                    @if(!$notif->is_read)
                                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 shrink-0"></span>
                                                    @endif
                                                </div>
                                                <p class="text-[11px] text-slate-500 dark:text-slate-400 break-words mt-0.5 leading-relaxed">{{ $notif->message }}</p>
                                                <span class="text-[10px] text-slate-400 mt-1 block">{{ $notif->created_at->diffForHumans() }}</span>
                                            </div>
                                        </a>

                                        <!-- Fast Toggle Read Action -->
                                        <div class="absolute top-3 right-3 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                                            <form action="{{ route('admin.notifications.toggle', $notif->id) }}" method="POST" class="inline" title="{{ $notif->is_read ? 'Mark as unread' : 'Mark as read' }}">
                                                @csrf
                                                <button type="submit" class="w-6 h-6 rounded flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-slate-100 dark:hover:bg-slate-700 transition cursor-pointer">
                                                    <i class="fa-solid {{ $notif->is_read ? 'fa-envelope' : 'fa-envelope-open' }} text-[10px]"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-8 text-center text-slate-400">
                                        <i class="fa-regular fa-bell-slash text-2xl mb-1.5 block opacity-50"></i>
                                        <p class="text-xs font-semibold">No notifications yet</p>
                                    </div>
                                @endforelse
                            </div>

                            <div class="px-4 py-2 border-t border-slate-100 dark:border-slate-800 text-center bg-slate-50/30 dark:bg-slate-800/20">
                                <a href="{{ route('admin.notifications.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 dark:text-brand-400">
                                    View all notifications ({{ $totalCount ?? ($adminNotifications->count() ?? 0) }}) →
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 3. User Image Dropdown (Normal Rounded & Circular Avatar) -->
                    <div class="relative" id="userMenuWrapper">
                        <button type="button" id="userMenuBtn" class="flex items-center gap-2 p-1 border border-slate-200 dark:border-slate-700 rounded-lg bg-slate-50 hover:bg-white dark:bg-slate-800 transition cursor-pointer">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=1e40af&color=fff&bold=true&size=64"
                                 alt="{{ auth()->user()->name ?? 'Admin' }}"
                                 class="w-7 h-7 rounded-full object-cover">
                            <span class="hidden sm:inline-block text-xs font-semibold text-slate-700 dark:text-slate-200 max-w-[120px] truncate">
                                {{ auth()->user()->name ?? 'Admin' }}
                            </span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <!-- User Profile Dropdown Menu (Normal Rounded) -->
                        <div id="userMenuDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white border border-slate-200 shadow-xl rounded-xl py-2 z-50 dark:bg-slate-900 dark:border-slate-800">
                            <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-800">
                                <p class="text-xs font-bold text-slate-900 dark:text-white">{{ auth()->user()->name ?? 'Admin' }}</p>
                                <p class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email ?? 'admin@digigo.com.bd' }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 bg-brand-50 text-brand-700 text-[10px] font-bold rounded-full uppercase dark:bg-slate-800 dark:text-brand-400">
                                    {{ auth()->user()->role ?? 'Administrator' }}
                                </span>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800 transition">
                                    <i class="fa-solid fa-sliders text-slate-400 w-4 text-center"></i>
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800 transition">
                                    <i class="fa-regular fa-user text-slate-400 w-4 text-center"></i>
                                    <span>My Account View</span>
                                </a>
                                <a href="{{ url('/') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800 transition">
                                    <i class="fa-solid fa-shop text-slate-400 w-4 text-center"></i>
                                    <span>Storefront</span>
                                </a>
                            </div>
                            <div class="pt-1 border-t border-slate-100 dark:border-slate-800">
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition cursor-pointer text-left">
                                        <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </header>

            <!-- =========================================================================
                 MAIN BODY CONTENT
                 ========================================================================= -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <!-- Session Alerts (Normal Rounded) -->
                {{-- @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 dark:bg-emerald-950/50 dark:border-emerald-800 dark:text-emerald-200 text-sm flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 dark:bg-rose-950/50 dark:border-rose-800 dark:text-rose-200 text-sm flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-exclamation text-rose-600 dark:text-rose-400"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 dark:bg-amber-950/50 dark:border-amber-800 dark:text-amber-200 text-sm flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-triangle-exclamation text-amber-600 dark:text-amber-400"></i>
                            <div>{{ session('warning') }}</div>
                        </div>
                    </div>
                @endif --}}

                @yield('admin_content')
                @yield('content')
            </main>

            <!-- Minimalist Admin Footer -->
            <footer class="h-12 border-t border-slate-200 dark:border-slate-800 px-6 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-900 shrink-0">
                <p>&copy; {{ date('Y') }} <strong class="text-slate-800 dark:text-slate-200">DigiGo</strong> - All Rights Reserved.</p>
                <p class="hidden sm:inline text-xs ">Develop by <a href="https://shakhawatdev.com" target="_blank" rel="noopener noreferrer" class="hover:underline">Shakhawat</a></p>
            </footer>

        </div>
    </div>

    <!-- Global Custom Delete Confirmation Modal (Rule: Never browser confirm) -->
    <div id="customDeleteModal"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity"
         role="dialog"
         aria-modal="true"
         aria-labelledby="customDeleteTitle">
        <div class="relative w-full max-w-md bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-2xl space-y-4">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                </div>
                <div class="space-y-1.5 flex-1 min-w-0">
                    <h3 id="customDeleteTitle" class="text-base font-bold text-slate-900 dark:text-white leading-snug">
                        Confirm Deletion
                    </h3>
                    <p id="customDeleteMessage" class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        Are you sure you want to delete this item? This action cannot be undone.
                    </p>
                </div>
            </div>

            <form id="customDeleteForm" method="POST" action="" class="flex items-center justify-end gap-2.5 pt-2">
                @csrf
                @method('DELETE')
                <button type="button"
                        id="customDeleteCancelBtn"
                        class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm transition cursor-pointer">
                    <i class="fa-solid fa-trash text-xs"></i>
                    <span>Yes, Delete</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Global Toast Container (Bottom Right) -->
    <div id="adminToastContainer" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2.5 max-w-sm pointer-events-none" aria-live="polite"></div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            /* 1. Dark / Light Theme Toggle */
            const themeToggleBtn = document.getElementById('themeToggleBtn');
            themeToggleBtn?.addEventListener('click', () => {
                const isDark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('digigo_admin_theme', isDark ? 'dark' : 'light');
            });

            /* 2. Sidebar Mobile Drawer Toggle */
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('adminSidebarOverlay');
            const openBtn = document.getElementById('adminSidebarOpenBtn');
            const closeBtn = document.getElementById('adminSidebarCloseBtn');

            const openSidebar = () => {
                sidebar?.classList.remove('-translate-x-full');
                overlay?.classList.remove('hidden');
            };

            const closeSidebar = () => {
                sidebar?.classList.add('-translate-x-full');
                overlay?.classList.add('hidden');
            };

            openBtn?.addEventListener('click', openSidebar);
            closeBtn?.addEventListener('click', closeSidebar);
            overlay?.addEventListener('click', closeSidebar);

            /* 3. Sidebar Dropdown Accordions */
            document.querySelectorAll('.js-sidebar-dropdown-toggle').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const group = this.closest('.sidebar-dropdown-group');
                    const submenu = group.querySelector('.sidebar-submenu');
                    const chevron = this.querySelector('.js-chevron');

                    const isCurrentlyOpen = !submenu.classList.contains('hidden');
                    if (isCurrentlyOpen) {
                        submenu.classList.add('hidden');
                        chevron?.classList.remove('rotate-180');
                    } else {
                        submenu.classList.remove('hidden');
                        chevron?.classList.add('rotate-180');
                    }
                });
            });

            /* 4. Top Navbar Dropdowns (Notifications & User Avatar Menu) */
            const notificationBtn = document.getElementById('notificationBtn');
            const notificationDropdown = document.getElementById('notificationDropdown');
            const userMenuBtn = document.getElementById('userMenuBtn');
            const userMenuDropdown = document.getElementById('userMenuDropdown');

            notificationBtn?.addEventListener('click', (e) => {
                e.stopPropagation();
                userMenuDropdown?.classList.add('hidden');
                notificationDropdown?.classList.toggle('hidden');
            });

            userMenuBtn?.addEventListener('click', (e) => {
                e.stopPropagation();
                notificationDropdown?.classList.add('hidden');
                userMenuDropdown?.classList.toggle('hidden');
            });

            // Close dropdowns on outside click
            document.addEventListener('click', (e) => {
                if (!e.target.closest('#notificationWrapper')) {
                    notificationDropdown?.classList.add('hidden');
                }
                if (!e.target.closest('#userMenuWrapper')) {
                    userMenuDropdown?.classList.add('hidden');
                }
                if (!e.target.closest('#adminGlobalSearchWrapper')) {
                    searchDropdown?.classList.add('hidden');
                }
            });

            /* 4.1 Global Search Dropdown & Interactive Keyboard Navigation */
            const searchInput = document.getElementById('globalSearchInput');
            const searchDropdown = document.getElementById('adminGlobalSearchDropdown');
            const searchItemsContainer = document.getElementById('searchItemsContainer');
            const searchNoResults = document.getElementById('searchNoResults');
            const searchItems = Array.from(document.querySelectorAll('.search-item'));
            const searchGroups = Array.from(document.querySelectorAll('.search-group'));
            let activeIndex = -1;

            const openSearchDropdown = () => {
                searchDropdown?.classList.remove('hidden');
                notificationDropdown?.classList.add('hidden');
                userMenuDropdown?.classList.add('hidden');
            };

            const closeSearchDropdown = () => {
                searchDropdown?.classList.add('hidden');
                activeIndex = -1;
                updateItemHighlight();
            };

            const updateItemHighlight = () => {
                const visibleItems = searchItems.filter(el => !el.classList.contains('hidden'));
                visibleItems.forEach((item, idx) => {
                    if (idx === activeIndex) {
                        item.classList.add('bg-brand-50', 'text-brand-700', 'dark:bg-slate-800', 'dark:text-brand-400');
                        item.scrollIntoView({ block: 'nearest' });
                    } else {
                        item.classList.remove('bg-brand-50', 'text-brand-700', 'dark:bg-slate-800', 'dark:text-brand-400');
                    }
                });
            };

            searchInput?.addEventListener('focus', openSearchDropdown);
            searchInput?.addEventListener('click', (e) => {
                e.stopPropagation();
                openSearchDropdown();
            });

            searchInput?.addEventListener('input', (e) => {
                const query = e.target.value.trim().toLowerCase();
                openSearchDropdown();
                activeIndex = -1;

                let totalMatches = 0;

                searchGroups.forEach(group => {
                    let groupMatches = 0;
                    const itemsInGroup = group.querySelectorAll('.search-item');

                    itemsInGroup.forEach(item => {
                        const text = item.textContent.toLowerCase();
                        const keywords = (item.getAttribute('data-keywords') || '').toLowerCase();
                        const matches = !query || text.includes(query) || keywords.includes(query);

                        if (matches) {
                            item.classList.remove('hidden');
                            groupMatches++;
                            totalMatches++;
                        } else {
                            item.classList.add('hidden');
                        }
                    });

                    if (groupMatches > 0) {
                        group.classList.remove('hidden');
                    } else {
                        group.classList.add('hidden');
                    }
                });

                if (totalMatches === 0) {
                    searchNoResults?.classList.remove('hidden');
                } else {
                    searchNoResults?.classList.add('hidden');
                }
            });

            searchInput?.addEventListener('keydown', (e) => {
                const visibleItems = searchItems.filter(el => !el.classList.contains('hidden'));

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (!searchDropdown || searchDropdown.classList.contains('hidden')) {
                        openSearchDropdown();
                    }
                    if (visibleItems.length > 0) {
                        activeIndex = (activeIndex + 1) % visibleItems.length;
                        updateItemHighlight();
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (visibleItems.length > 0) {
                        activeIndex = (activeIndex - 1 + visibleItems.length) % visibleItems.length;
                        updateItemHighlight();
                    }
                } else if (e.key === 'Enter') {
                    if (activeIndex >= 0 && visibleItems[activeIndex]) {
                        e.preventDefault();
                        window.location.href = visibleItems[activeIndex].getAttribute('href');
                    }
                } else if (e.key === 'Escape') {
                    closeSearchDropdown();
                    searchInput.blur();
                }
            });

            // Global search keyboard shortcut (Ctrl+K, Cmd+K, or '/')
            document.addEventListener('keydown', (e) => {
                const activeTag = document.activeElement ? document.activeElement.tagName.toLowerCase() : '';
                const isInputActive = activeTag === 'input' || activeTag === 'textarea' || activeTag === 'select';

                if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                    e.preventDefault();
                    searchInput?.focus();
                    openSearchDropdown();
                } else if (e.key === '/' && !isInputActive) {
                    e.preventDefault();
                    searchInput?.focus();
                    openSearchDropdown();
                }
            });

            /* 5. Global Custom Delete Confirmation Modal */
            const deleteModal = document.getElementById('customDeleteModal');
            const deleteForm = document.getElementById('customDeleteForm');
            const deleteTitle = document.getElementById('customDeleteTitle');
            const deleteMessage = document.getElementById('customDeleteMessage');
            const deleteCancelBtn = document.getElementById('customDeleteCancelBtn');

            const closeDeleteModal = () => {
                if (deleteModal) {
                    deleteModal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            };

            document.addEventListener('click', (e) => {
                const btn = e.target.closest('.js-custom-delete');
                if (!btn) return;

                e.preventDefault();
                e.stopPropagation();

                const action = btn.getAttribute('data-action');
                const title = btn.getAttribute('data-title') || 'Confirm Deletion';
                const message = btn.getAttribute('data-message') || 'Are you sure you want to delete this item? This action cannot be undone.';

                if (deleteModal && deleteForm) {
                    deleteForm.setAttribute('action', action);
                    if (deleteTitle) deleteTitle.textContent = title;
                    if (deleteMessage) deleteMessage.textContent = message;

                    deleteModal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                }
            });

            deleteCancelBtn?.addEventListener('click', closeDeleteModal);

            deleteModal?.addEventListener('click', (e) => {
                if (e.target === deleteModal) {
                    closeDeleteModal();
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && deleteModal && !deleteModal.classList.contains('hidden')) {
                    closeDeleteModal();
                }
            });

            /* 6. Global Admin Toast Notification System */
            window.showAdminToast = function(message, type = 'success') {
                const container = document.getElementById('adminToastContainer');
                if (!container) return;

                const isSuccess = type === 'success';
                const toast = document.createElement('div');
                toast.className = `pointer-events-auto flex items-center gap-3 px-4 py-3 rounded-xl border shadow-lg backdrop-blur-md transition-all duration-300 transform translate-y-4 opacity-0 ${
                    isSuccess
                        ? 'bg-slate-900/95 dark:bg-slate-800/95 text-white border-slate-700/80 dark:border-slate-700 shadow-slate-950/20'
                        : 'bg-rose-900/95 text-white border-rose-700 shadow-rose-950/20'
                }`;

                const iconClass = isSuccess ? 'fa-solid fa-circle-check text-emerald-400' : 'fa-solid fa-circle-exclamation text-rose-300';

                toast.innerHTML = `
                    <div class="w-7 h-7 rounded-lg ${isSuccess ? 'bg-emerald-500/20' : 'bg-rose-500/20'} flex items-center justify-center shrink-0">
                        <i class="${iconClass} text-sm"></i>
                    </div>
                    <div class="flex-1 text-xs font-semibold leading-relaxed pr-2">
                        ${message}
                    </div>
                    <button type="button" class="text-slate-400 hover:text-white transition p-1 shrink-0" aria-label="Close">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </button>
                `;

                container.appendChild(toast);

                // Animate in
                requestAnimationFrame(() => {
                    toast.classList.remove('translate-y-4', 'opacity-0');
                    toast.classList.add('translate-y-0', 'opacity-100');
                });

                const closeToast = () => {
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    toast.classList.add('translate-y-2', 'opacity-0');
                    setTimeout(() => toast.remove(), 300);
                };

                toast.querySelector('button')?.addEventListener('click', closeToast);
                setTimeout(closeToast, 4000);
            };

            /* 7. Admin Cache / View / Route / Event Clear Button */
            const clearCacheBtn = document.getElementById('adminClearCacheBtn');
            const clearCacheIcon = document.getElementById('adminClearCacheIcon');
            let isClearingCache = false;

            clearCacheBtn?.addEventListener('click', async () => {
                if (isClearingCache) return;

                const url = clearCacheBtn.getAttribute('data-url');
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                isClearingCache = true;
                clearCacheBtn.disabled = true;
                clearCacheBtn.classList.add('opacity-75');
                clearCacheIcon?.classList.add('animate-spin');

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || ''
                        }
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        window.showAdminToast(data.message || 'Cache, views, routes & events cleared successfully!', 'success');
                    } else {
                        throw new Error(data.message || 'Something went wrong while clearing cache.');
                    }
                } catch (err) {
                    window.showAdminToast(err.message || 'Failed to clear cache.', 'error');
                } finally {
                    setTimeout(() => {
                        clearCacheIcon?.classList.remove('animate-spin');
                        clearCacheBtn.classList.remove('opacity-75');
                        clearCacheBtn.disabled = false;
                        isClearingCache = false;
                    }, 500);
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
