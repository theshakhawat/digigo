<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - {{ setting('site_name', 'DigiGo') }} Administration</title>

    @if(setting_url('site_favicon'))
        <link rel="icon" href="{{ setting_url('site_favicon') }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif

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

            <!-- Sidebar Top: Brand Logo & Title (Strictly locked 64px height to match navbar) -->
            <div class="h-16 min-h-[4rem] max-h-16 box-border px-5 border-b border-slate-200 flex items-center justify-between dark:border-slate-800 shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 group overflow-hidden">
                    @if(setting_url('site_logo'))
                        <img src="{{ setting_url('site_logo') }}" alt="{{ setting('site_name', 'DigiGo') }}" class="h-8 max-h-8 max-w-[120px] object-contain shrink-0">
                    @else
                        <div class="w-8 h-8 rounded-lg bg-brand-800 text-white font-extrabold flex items-center justify-center text-sm shadow-xs group-hover:bg-brand-700 transition shrink-0">
                            d
                        </div>
                    @endif
                    <div class="flex flex-col min-w-0">
                        <span class="text-base font-extrabold text-slate-900 leading-tight dark:text-white tracking-tight truncate">
                            {{ strtolower(setting('site_name', 'digigo')) }}<span class="text-brand-600 dark:text-brand-400">.admin</span>
                        </span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider truncate">Management Portal</span>
                    </div>
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

                    <!-- Section: STORE CATALOG -->
                    <div class="px-5 pb-2 pt-4">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Store Catalog</p>
                    </div>

                    <!-- 2. Products Dropdown (Square) -->
                    <div class="sidebar-dropdown-group">
                        <button type="button" class="js-sidebar-dropdown-toggle w-full flex items-center justify-between px-5 py-2.5 text-sm font-medium rounded-none transition border-l-4 border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60 cursor-pointer">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-box-archive w-4 text-center text-slate-400 dark:text-slate-500"></i>
                                <span>Products</span>
                            </div>
                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 js-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="sidebar-submenu hidden bg-slate-50/70 border-y border-slate-100 dark:bg-slate-950/60 dark:border-slate-800/60 py-1">
                            <a href="#all-products" class="flex items-center px-11 py-2 text-xs font-medium text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40 rounded-none transition">
                                All Products
                            </a>
                            <a href="#add-product" class="flex items-center px-11 py-2 text-xs font-medium text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40 rounded-none transition">
                                Add Product
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
                        <button type="button" class="js-sidebar-dropdown-toggle w-full flex items-center justify-between px-5 py-2.5 text-sm font-medium rounded-none transition border-l-4 border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60 cursor-pointer">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-cart-shopping w-4 text-center text-slate-400 dark:text-slate-500"></i>
                                <span>Orders</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-1.5 py-0.5 rounded-full dark:bg-amber-950 dark:text-amber-300">5 New</span>
                                <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 js-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </button>
                        <div class="sidebar-submenu hidden bg-slate-50/70 border-y border-slate-100 dark:bg-slate-950/60 dark:border-slate-800/60 py-1">
                            <a href="#all-orders" class="flex items-center px-11 py-2 text-xs font-medium text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40 rounded-none transition">
                                All Orders
                            </a>
                            <a href="#pending-orders" class="flex items-center px-11 py-2 text-xs font-medium text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40 rounded-none transition">
                                Pending Orders
                            </a>
                            <a href="#completed-orders" class="flex items-center px-11 py-2 text-xs font-medium text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40 rounded-none transition">
                                Completed Orders
                            </a>
                            <a href="#refund-requests" class="flex items-center px-11 py-2 text-xs font-medium text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40 rounded-none transition">
                                Refunds
                            </a>
                        </div>
                    </div>

                    <!-- 4. Digital Keys & Inventory Dropdown (Square) -->
                    <div class="sidebar-dropdown-group">
                        <button type="button" class="js-sidebar-dropdown-toggle w-full flex items-center justify-between px-5 py-2.5 text-sm font-medium rounded-none transition border-l-4 border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60 cursor-pointer">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-key w-4 text-center text-slate-400 dark:text-slate-500"></i>
                                <span>License Keys</span>
                            </div>
                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 js-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="sidebar-submenu hidden bg-slate-50/70 border-y border-slate-100 dark:bg-slate-950/60 dark:border-slate-800/60 py-1">
                            <a href="#license-pool" class="flex items-center px-11 py-2 text-xs font-medium text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40 rounded-none transition">
                                Key Pools
                            </a>
                            <a href="#subscriptions" class="flex items-center px-11 py-2 text-xs font-medium text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40 rounded-none transition">
                                Subscriptions
                            </a>
                            <a href="#stock-alerts" class="flex items-center px-11 py-2 text-xs font-medium text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40 rounded-none transition">
                                Low Stock Alerts
                            </a>
                        </div>
                    </div>

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
                    <a href="#coupons" class="flex items-center justify-between px-5 py-2.5 text-sm font-medium rounded-none transition border-l-4 border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-ticket w-4 text-center text-slate-400 dark:text-slate-500"></i>
                            <span>Coupons</span>
                        </div>
                    </a>

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
                    <a href="{{ route('admin.appearance.banners.index', ['tab' => 'slides']) }}" class="flex items-center justify-between px-5 py-2.5 text-sm font-semibold rounded-none transition border-l-4 {{ request()->routeIs('admin.appearance.banners.*') && request('tab') !== 'ticker' ? 'border-brand-600 bg-brand-50/80 text-brand-700 dark:bg-slate-800 dark:text-brand-400 dark:border-brand-500' : 'border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-images w-4 text-center text-slate-400 dark:text-slate-500"></i>
                            <span>Banners</span>
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
                        <button type="button" class="js-sidebar-dropdown-toggle w-full flex items-center justify-between px-5 py-2.5 text-sm font-medium rounded-none transition border-l-4 border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/60 cursor-pointer">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-chart-line w-4 text-center text-slate-400 dark:text-slate-500"></i>
                                <span>Reports</span>
                            </div>
                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 js-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="sidebar-submenu hidden bg-slate-50/70 border-y border-slate-100 dark:bg-slate-950/60 dark:border-slate-800/60 py-1">
                            <a href="#sales-report" class="flex items-center px-11 py-2 text-xs font-medium text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40 rounded-none transition">
                                Sales Report
                            </a>
                            <a href="#customer-growth" class="flex items-center px-11 py-2 text-xs font-medium text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40 rounded-none transition">
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
                            <a href="{{ route('admin.settings.smtp') }}" class="flex items-center px-11 py-2 text-xs font-medium {{ request()->routeIs('admin.settings.smtp*') ? 'text-brand-600 font-semibold bg-brand-50/50 dark:text-brand-400 dark:bg-slate-800/50' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40' }} rounded-none transition">
                                SMTP & Email Settings
                            </a>
                            <a href="#general-settings" class="flex items-center px-11 py-2 text-xs font-medium text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40 rounded-none transition">
                                General Settings
                            </a>
                            <a href="#payment-gateways" class="flex items-center px-11 py-2 text-xs font-medium text-slate-600 hover:text-brand-600 hover:bg-slate-100/60 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800/40 rounded-none transition">
                                Payment Gateways
                            </a>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Sidebar Bottom: Live Status Info (Normal Rounded Status Dot) -->
            <div class="p-4 border-t border-slate-200 dark:border-slate-800 shrink-0 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between text-xs text-slate-500">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>System v1.4.0 Online</span>
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

                    <!-- Global Search Bar (Normal Rounded Input) -->
                    <div class="relative w-full max-w-xs sm:max-w-sm md:max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text"
                               id="globalSearchInput"
                               placeholder="Search orders, products, users..."
                               class="w-full h-9 pl-9 pr-12 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 dark:focus:border-brand-500 transition">
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                            <kbd class="hidden sm:inline-block px-1.5 py-0.5 text-[10px] font-semibold text-slate-400 bg-white border border-slate-200 rounded-md dark:bg-slate-900 dark:border-slate-700">Ctrl+K</kbd>
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

                    <!-- 2. Notification Dropdown (Normal Rounded) -->
                    <div class="relative" id="notificationWrapper">
                        <button type="button" id="notificationBtn" class="w-9 h-9 flex items-center justify-center text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white border border-slate-200 dark:border-slate-700 rounded-lg bg-slate-50 hover:bg-white dark:bg-slate-800 transition relative cursor-pointer" aria-label="Notifications" title="Notifications">
                            <i class="fa-regular fa-bell text-sm"></i>
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-rose-500"></span>
                        </button>

                        <!-- Notification Dropdown Panel (Normal Rounded) -->
                        <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white border border-slate-200 shadow-xl rounded-xl py-2 z-50 dark:bg-slate-900 dark:border-slate-800">
                            <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Notifications</span>
                                <span class="text-[10px] font-semibold text-brand-600 dark:text-brand-400 cursor-pointer hover:underline">Mark all read</span>
                            </div>
                            <div class="divide-y divide-slate-100 dark:divide-slate-800/80 max-h-72 overflow-y-auto">
                                <a href="#order-1" class="flex gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                    <div class="w-7 h-7 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center shrink-0 dark:bg-blue-950 dark:text-blue-400 text-xs">
                                        <i class="fa-solid fa-bag-shopping"></i>
                                    </div>
                                    <div class="text-xs">
                                        <p class="font-semibold text-slate-800 dark:text-slate-200">New order #1084 received</p>
                                        <p class="text-[11px] text-slate-500">Customer purchased ChatGPT Plus (৳2,450)</p>
                                        <span class="text-[10px] text-slate-400">2 minutes ago</span>
                                    </div>
                                </a>
                                <a href="#user-1" class="flex gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                    <div class="w-7 h-7 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center shrink-0 dark:bg-emerald-950 dark:text-emerald-400 text-xs">
                                        <i class="fa-solid fa-user-plus"></i>
                                    </div>
                                    <div class="text-xs">
                                        <p class="font-semibold text-slate-800 dark:text-slate-200">New user registered</p>
                                        <p class="text-[11px] text-slate-500">Tanvir Ahmed created an account</p>
                                        <span class="text-[10px] text-slate-400">20 minutes ago</span>
                                    </div>
                                </a>
                                <a href="#stock-1" class="flex gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                    <div class="w-7 h-7 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center shrink-0 dark:bg-amber-950 dark:text-amber-400 text-xs">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                    </div>
                                    <div class="text-xs">
                                        <p class="font-semibold text-slate-800 dark:text-slate-200">Low license key alert</p>
                                        <p class="text-[11px] text-slate-500">Canva Pro licenses running low (3 keys remaining)</p>
                                        <span class="text-[10px] text-slate-400">1 hour ago</span>
                                    </div>
                                </a>
                            </div>
                            <div class="px-4 py-2 border-t border-slate-100 dark:border-slate-800 text-center">
                                <a href="#all-notifications" class="text-xs font-semibold text-brand-600 hover:text-brand-700 dark:text-brand-400">View all notifications →</a>
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
                @if (session('success'))
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
                @endif

                @yield('admin_content')
                @yield('content')
            </main>

            <!-- Minimalist Admin Footer -->
            <footer class="h-12 border-t border-slate-200 dark:border-slate-800 px-6 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-900 shrink-0">
                <p>&copy; {{ date('Y') }} <strong class="text-slate-800 dark:text-slate-200">DigiGo</strong> - All Rights Reserved.</p>
                <p class="hidden sm:inline">Built with Tailwind CSS & Laravel</p>
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
            });

            // Global search keyboard shortcut (Ctrl+K or Cmd+K)
            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    document.getElementById('globalSearchInput')?.focus();
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
        });
    </script>
    @stack('scripts')
</body>
</html>
