<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'My account') - {{ setting('site_name', 'DigiGo') }}</title>

    <!-- Browser Favicon -->
    <link rel="icon" href="{{ default_site_favicon() }}">

    <!-- Google Fonts (Hind Siliguri + Plus Jakarta Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
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
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        :root {
            --theme-header: #1e40af;            /* Deep DigiGo Royal Blue */
            --theme-header-hover: #1d4ed8;      /* Hover Blue */
            --theme-accent: #2563eb;            /* Bright Vibrant Blue */
            --account-border: #e2e8f0;
            --account-active-bg: #eff6ff;
            --account-active-text: #1d4ed8;
        }

        body {
            background-color: #ffffff;
            color: #1e293b;
            font-family: 'Plus Jakarta Sans', 'Hind Siliguri', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Content Card Box for child views (Orders, Downloads, Addresses, etc.) */
        .content-card-box {
            background: #ffffff;
            border: 1px solid var(--account-border);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 1.5rem;
        }

        .content-card-box h2,
        .content-card-box h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #111827;
            margin-top: 0;
            margin-bottom: 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        /* Form Controls for Account Details */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            height: 44px;
            padding: 0.5rem 0.85rem;
            font-size: 0.925rem;
            border: 1px solid var(--account-border);
            border-radius: 6px;
            outline: none;
            box-sizing: border-box;
            transition: all 0.2s;
        }

        .form-input:focus {
            border-color: var(--theme-accent);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .btn-theme-submit {
            background-color: var(--theme-header);
            color: #ffffff;
            border: none;
            padding: 0.75rem 1.75rem;
            font-size: 0.925rem;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-theme-submit:hover {
            background-color: var(--theme-header-hover);
        }

        .ribbon-pill-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: #ffffff;
            color: #0f172a;
            padding: 0.42rem 1.15rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen bg-white text-slate-800 antialiased selection:bg-blue-600 selection:text-white">

    <!-- =========================================================================
         Header Section (Matches Homepage / Site Layout Navbar Exactly)
         ========================================================================= -->
    <header class="sticky top-0 z-50 bg-white shadow-sm border-b border-slate-200">

        <!-- Optional Top Announcement Banner -->
        @if(setting('topbar_announcement_enabled') === '1' && setting('topbar_announcement_text'))
            <div class="bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700 text-white text-xs py-2 px-4 text-center font-medium shadow-xs">
                <div class="max-w-[1440px] mx-auto flex items-center justify-center gap-2">
                    <span>{{ setting('topbar_announcement_text') }}</span>
                    @if(setting('topbar_announcement_link'))
                        <a href="{{ setting('topbar_announcement_link') }}" class="underline font-bold hover:text-blue-100 transition ml-1">Learn More &rarr;</a>
                    @endif
                </div>
            </div>
        @endif

        <!-- Top White Bar -->
        <div class="h-16 border-b border-slate-200 bg-white">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 h-full flex items-center justify-between gap-4">

                <!-- Left: Category Sidebar Toggle & Brand Logo -->
                <div class="flex items-center gap-3">
                    <button type="button" class="w-10 h-10 rounded-lg flex items-center justify-center text-slate-700 hover:text-blue-700 hover:bg-blue-50 transition js-sidebar-toggle" aria-label="Toggle navigation categories">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>

                    <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                        <img src="{{ default_site_logo() }}" alt="{{ setting('site_name', 'DigiGo') }}" class="h-9 sm:h-10 w-auto max-w-[180px] object-contain">
                    </a>
                </div>

                <!-- Center: Search Input Bar -->
                <div class="hidden md:flex flex-1 max-w-xl mx-4">
                    <form action="{{ route('search') }}" method="GET" class="relative w-full">
                        <input type="text"
                                name="q"
                                value="{{ request('q') }}"
                                placeholder="{{ setting('header_search_placeholder', 'Search for AI tools, software, gift cards, subscriptions...') }}"
                                class="w-full h-11 pl-5 pr-12 rounded-full border border-slate-300 bg-white text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition shadow-sm">
                        <button type="submit" class="absolute right-1.5 top-1.5 w-8 h-8 rounded-full bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center transition shadow-sm" aria-label="Submit Search">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Right: Support Hotline & Mobile Search Trigger -->
                <div class="flex items-center gap-4">
                    <div class="hidden lg:flex items-center gap-5 text-xs">
                        <div class="flex flex-col">
                            <span class="font-bold text-slate-700">{{ setting('topbar_support_label', '24/7 SUPPORT') }}</span>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', setting('topbar_support_phone', '+8809611678088')) }}" class="font-semibold text-blue-600 hover:underline">
                                {{ setting('topbar_support_phone', '+8809611678088') }}
                            </a>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold text-slate-700">{{ setting('topbar_whatsapp_label', 'WHATSAPP') }}</span>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('topbar_whatsapp_phone', '8801600793325')) }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-blue-600 hover:underline">
                                {{ setting('topbar_whatsapp_phone', '+8801600793325') }}
                            </a>
                        </div>
                    </div>

                    <!-- Mobile search modal trigger -->
                    <button type="button" class="md:hidden w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center js-search-modal-open" aria-label="Open search">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        <!-- Desktop Blue Ribbon Navigation Bar -->
        <nav class="hidden md:block bg-blue-700 text-white h-12 shadow-md">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 h-full flex items-center justify-between">

                <!-- Categories Pill Button -->
                <button type="button" class="inline-flex items-center gap-2 bg-white/20 hover:bg-white text-white hover:text-blue-700 px-4 py-1.5 rounded-full text-sm font-semibold transition js-sidebar-toggle">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                    <span>All Categories</span>
                </button>

                <!-- Navigation Links with Home, Physical Products, Digital Products, Contacts -->
                <div class="flex items-center gap-7 text-sm font-medium">
                    <a href="{{ url('/') }}" class="text-white/90 hover:text-white transition {{ request()->is('/') ? 'font-bold text-white' : '' }}">
                        Home
                    </a>
                    <a href="{{ url('/shop?type=physical') }}" class="text-white/90 hover:text-white transition whitespace-nowrap {{ request('type') === 'physical' ? 'font-bold text-white underline underline-offset-4 decoration-2' : '' }}">
                        Physical Products
                    </a>
                    <a href="{{ url('/shop?type=digital') }}" class="text-white/90 hover:text-white transition whitespace-nowrap {{ request('type') === 'digital' ? 'font-bold text-white underline underline-offset-4 decoration-2' : '' }}">
                        Digital Products
                    </a>
                    <a href="{{ route('contact') }}" class="text-white/90 hover:text-white transition {{ request()->routeIs('contact') ? 'font-bold text-white underline underline-offset-4 decoration-2' : '' }}">
                        Contacts
                    </a>
                </div>

                <!-- Right Action Icons (Cart, Wishlist, Account) -->
                @php
                    $headerCartCount = array_sum(array_column(session('cart_items', []), 'quantity'));
                    $headerWishlistCount = count(session('wishlist_items', []));
                @endphp
                <div class="flex items-center gap-3">
                    <!-- User Notifications Bell Dropdown -->
                    @auth
                        <div class="relative" id="userNotificationWrapper">
                            <button type="button" id="userNotificationBtn" class="relative w-9 h-9 rounded-full bg-white/15 hover:bg-white text-white hover:text-blue-700 flex items-center justify-center transition cursor-pointer" aria-label="Notifications" title="Notifications">
                                <i class="fa-regular fa-bell text-sm"></i>
                                @if(($userUnreadCount ?? 0) > 0)
                                    <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-500 text-white font-extrabold text-[10px] flex items-center justify-center shadow-xs">
                                        {{ $userUnreadCount > 9 ? '9+' : $userUnreadCount }}
                                    </span>
                                @endif
                            </button>

                            <!-- User Notification Dropdown Box -->
                            <div id="userNotificationDropdown" class="hidden absolute right-0 mt-2 w-[calc(100vw-2rem)] max-w-sm sm:w-96 bg-white border border-slate-200 shadow-xl rounded-2xl py-2 z-50 text-slate-800 overflow-hidden">
                                <div class="px-4 py-2.5 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold uppercase tracking-wider text-slate-800">Notifications</span>
                                        @if(($userUnreadCount ?? 0) > 0)
                                            <span class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-700">
                                                {{ $userUnreadCount }} new
                                            </span>
                                        @endif
                                    </div>
                                    @if(($userUnreadCount ?? 0) > 0)
                                        <form action="{{ route('user.notifications.mark-all-read') }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-[11px] font-semibold text-blue-600 hover:underline cursor-pointer">
                                                Mark all read
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto overflow-x-hidden">
                                    @forelse(($userNotifications ?? []) as $notif)
                                        <div class="group relative flex items-start gap-3 px-4 py-3 hover:bg-slate-50 transition {{ !$notif->is_read ? 'bg-blue-50/50' : '' }}">
                                            <a href="{{ route('user.notifications.read', $notif->id) }}" class="flex-1 flex items-start gap-3 min-w-0">
                                                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 text-xs shadow-xs
                                                    {{ $notif->type === 'order' ? 'bg-blue-100 text-blue-600' :
                                                       ($notif->type === 'subscription' ? 'bg-purple-100 text-purple-600' :
                                                       ($notif->type === 'withdrawal' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600')) }}">
                                                    <i class="fa-solid {{ $notif->type === 'order' ? 'fa-bag-shopping' : ($notif->type === 'subscription' ? 'fa-key' : ($notif->type === 'withdrawal' ? 'fa-wallet' : 'fa-bell')) }}"></i>
                                                </div>
                                                <div class="text-xs flex-1 min-w-0 pr-6">
                                                    <div class="flex items-center gap-1.5">
                                                        <p class="font-bold text-slate-800 truncate {{ !$notif->is_read ? 'text-blue-900 font-extrabold' : '' }}" title="{{ $notif->title }}">
                                                            {{ $notif->title }}
                                                        </p>
                                                        @if(!$notif->is_read)
                                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600 shrink-0"></span>
                                                        @endif
                                                    </div>
                                                    <p class="text-[11px] text-slate-500 break-words mt-0.5 leading-relaxed">{{ $notif->message }}</p>
                                                    <span class="text-[10px] text-slate-400 mt-1 block">{{ $notif->created_at->diffForHumans() }}</span>
                                                </div>
                                            </a>

                                            <!-- Toggle Read Action -->
                                            <div class="absolute top-3 right-3 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                                                <form action="{{ route('user.notifications.toggle', $notif->id) }}" method="POST" class="inline" title="{{ $notif->is_read ? 'Mark as unread' : 'Mark as read' }}">
                                                    @csrf
                                                    <button type="submit" class="w-6 h-6 rounded flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-slate-100 transition cursor-pointer">
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

                                <div class="px-4 py-2 border-t border-slate-100 text-center bg-slate-50/50">
                                    <a href="{{ route('user.notifications.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                                        View all notifications →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endauth

                    <a href="{{ route('cart') }}" class="relative w-9 h-9 rounded-full bg-white/15 hover:bg-white text-white hover:text-blue-700 flex items-center justify-center transition" aria-label="Cart">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <span class="header-cart-badge absolute -top-1 -right-1 min-w-4 h-4 px-1 rounded-full bg-blue-500 border border-white text-white text-[10px] font-bold flex items-center justify-center {{ $headerCartCount > 0 ? '' : 'hidden' }}">{{ $headerCartCount }}</span>
                    </a>

                    <a href="{{ route('wishlist') }}" class="relative w-9 h-9 rounded-full bg-white/15 hover:bg-white text-white hover:text-blue-700 flex items-center justify-center transition" aria-label="Wishlist">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                        <span class="header-wishlist-badge absolute -top-1 -right-1 min-w-4 h-4 px-1 rounded-full bg-rose-500 border border-white text-white text-[10px] font-bold flex items-center justify-center {{ $headerWishlistCount > 0 ? '' : 'hidden' }}">{{ $headerWishlistCount }}</span>
                    </a>

                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="w-9 h-9 rounded-full bg-white/15 hover:bg-white text-white hover:text-blue-700 flex items-center justify-center transition" title="Admin Panel">
                                <i class="fa-solid fa-user-shield text-sm"></i>
                            </a>
                        @else
                            <a href="{{ route('user.dashboard') }}" class="w-9 h-9 rounded-full bg-white text-blue-700 hover:bg-blue-50 flex items-center justify-center transition shadow-xs" title="My Account">
                                <i class="fa-regular fa-user text-sm"></i>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="w-9 h-9 rounded-full bg-white/15 hover:bg-white text-white hover:text-blue-700 flex items-center justify-center transition" title="Sign In">
                            <i class="fa-regular fa-user text-sm"></i>
                        </a>
                    @endauth
                </div>

            </div>
        </nav>
    </header>

    <!-- =========================================================================
         Full Slide-out Category Drawer (Categories & Menu)
         ========================================================================= -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 opacity-0 pointer-events-none transition-opacity duration-300 [&.active]:opacity-100 [&.active]:pointer-events-auto" aria-hidden="true"></div>

    <aside id="categorySidebar" class="fixed top-0 left-0 bottom-0 w-80 max-w-[85vw] bg-white z-50 -translate-x-full transition-transform duration-300 ease-out shadow-2xl overflow-y-auto [&.open]:translate-x-0 flex flex-col" aria-label="Sidebar Navigation">
        <div class="flex border-b border-slate-200">
            <button type="button" class="flex-1 py-3.5 text-xs font-bold tracking-wider text-slate-500 border-b-2 border-transparent transition [&.active]:text-blue-700 [&.active]:border-blue-700 [&.active]:bg-blue-50/50 sidebar-tab-btn active" data-tab="categories">CATEGORIES</button>
            <button type="button" class="flex-1 py-3.5 text-xs font-bold tracking-wider text-slate-500 border-b-2 border-transparent transition [&.active]:text-blue-700 [&.active]:border-blue-700 [&.active]:bg-blue-50/50 sidebar-tab-btn" data-tab="menu">MENU</button>
        </div>

        <div class="sidebar-tab-content active p-2" id="tabCategories">
            <ul class="space-y-1">
                @php
                    $categoriesList = $siteCategories ?? collect();
                @endphp

                @forelse($categoriesList as $cat)
                    @if(is_object($cat) && !empty($cat->slug))
                        <li>
                            <a href="{{ route('category.show', $cat->slug) }}" class="flex items-center justify-between px-4 py-2.5 rounded-lg text-sm font-medium text-slate-700 hover:text-blue-700 hover:bg-blue-50 transition group">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-md bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold overflow-hidden shrink-0">
                                        @if($cat->image_url)
                                            <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="w-full h-full object-cover">
                                        @elseif($cat->image && str_starts_with($cat->image, 'http'))
                                            <img src="{{ $cat->image }}" alt="{{ $cat->name }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($cat->name, 0, 1) }}
                                        @endif
                                    </span>
                                    <span>{{ $cat->name }}</span>
                                </div>
                                @if(isset($cat->products_count))
                                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 group-hover:bg-blue-100 group-hover:text-blue-700 transition">
                                        {{ $cat->products_count }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    @endif
                @empty
                    <li class="px-4 py-6 text-center text-xs text-slate-400">
                        No active categories available.
                    </li>
                @endforelse
            </ul>
        </div>

        <div class="sidebar-tab-content hidden [&.active]:block p-2" id="tabMenu">
            <ul class="space-y-1">
                <li><a href="{{ url('/') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-slate-700 hover:text-blue-700 hover:bg-blue-50 transition">Home</a></li>
                <li><a href="{{ url('/shop?type=physical') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-slate-700 hover:text-blue-700 hover:bg-blue-50 transition">Physical Products</a></li>
                <li><a href="{{ url('/shop?type=digital') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-slate-700 hover:text-blue-700 hover:bg-blue-50 transition">Digital Products</a></li>
                <li><a href="{{ route('contact') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-slate-700 hover:text-blue-700 hover:bg-blue-50 transition">Contacts</a></li>
                <li class="pt-2 border-t border-slate-100">
                    <a href="{{ route('cart') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-slate-700 hover:text-blue-700 hover:bg-blue-50 transition">Shopping Cart</a>
                </li>
                <li><a href="{{ route('wishlist') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-slate-700 hover:text-blue-700 hover:bg-blue-50 transition">Wishlist</a></li>
                <li>
                    @auth
                        <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-slate-700 hover:text-blue-700 hover:bg-blue-50 transition">My Account</a>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-slate-700 hover:text-blue-700 hover:bg-blue-50 transition">Sign In / Register</a>
                    @endauth
                </li>
            </ul>
        </div>
    </aside>

    <!-- =========================================================================
         2. Main Account Layout: Rail + Navigation Sidebar + Content Area
         ========================================================================= -->
    <div class="flex flex-1 min-h-[calc(100vh-56px)] w-full bg-white">

        <!-- Column 2: Inner Left Sidebar Menu (MY ACCOUNT) -->
        <aside class="w-56 md:w-64 shrink-0 bg-white border-r border-slate-200/80 p-6 hidden md:block" aria-label="Account Menu">
            <h2 class="text-xs font-bold text-slate-400 tracking-wider uppercase mb-4 px-3">MY ACCOUNT</h2>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }} px-3.5 py-2.5 rounded-lg flex items-center gap-3 transition text-sm">
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.orders') }}" class="{{ request()->routeIs('user.orders') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }} px-3.5 py-2.5 rounded-lg flex items-center gap-3 transition text-sm">
                        <span>Orders</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.subscriptions') }}" class="{{ request()->routeIs('user.subscriptions') || request()->routeIs('user.downloads') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }} px-3.5 py-2.5 rounded-lg flex items-center gap-3 transition text-sm">
                        <span>Subscriptions</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.addresses') }}" class="{{ request()->routeIs('user.addresses') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }} px-3.5 py-2.5 rounded-lg flex items-center gap-3 transition text-sm">
                        <span>Addresses</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.account-details') }}" class="{{ request()->routeIs('user.account-details') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }} px-3.5 py-2.5 rounded-lg flex items-center gap-3 transition text-sm">
                        <span>Account details</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.affiliate') }}" class="{{ request()->routeIs('user.affiliate') || request()->routeIs('user.rewards') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }} px-3.5 py-2.5 rounded-lg flex items-center gap-3 transition text-sm">
                        <span>Affiliate</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.withdraw') }}" class="{{ request()->routeIs('user.withdraw') || request()->routeIs('user.coupons') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }} px-3.5 py-2.5 rounded-lg flex items-center gap-3 transition text-sm">
                        <span>Withdraw</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.wishlist') }}" class="{{ request()->routeIs('user.wishlist') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }} px-3.5 py-2.5 rounded-lg flex items-center justify-between transition text-sm">
                        <span>Wishlist</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.tickets.index') }}" class="{{ request()->routeIs('user.tickets.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }} px-3.5 py-2.5 rounded-lg flex items-center justify-between transition text-sm">
                        <span class="flex items-center gap-2">
                            <span>Support Tickets</span>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.notifications.index') }}" class="{{ request()->routeIs('user.notifications.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium' }} px-3.5 py-2.5 rounded-lg flex items-center justify-between transition text-sm">
                        <span class="flex items-center gap-2">
                            <span>Notifications</span>
                        </span>
                        @if(($userUnreadCount ?? 0) > 0)
                            <span class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-500 text-white">
                                {{ $userUnreadCount }}
                            </span>
                        @endif
                    </a>
                </li>
                <li class="pt-2 border-t border-slate-100">
                    <form action="{{ route('logout') }}" method="POST" id="sidebarLogoutForm" class="m-0">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-600 hover:bg-red-50 font-medium px-3.5 py-2.5 rounded-lg flex items-center gap-3 transition text-sm w-full text-left cursor-pointer">
                            <span>Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        <!-- Column 3: Main Account Center Area -->
        <main class="flex-1 min-w-0 p-6 md:p-10 lg:p-12">

            <!-- Mobile Navigation Bar (Horizontal Scrollable Tabs for Mobile Screens) -->
            <nav class="md:hidden flex items-center gap-2 overflow-x-auto pb-3 mb-6 scrollbar-none border-b border-slate-100" aria-label="Mobile Navigation">
                <a href="{{ route('user.dashboard') }}" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-semibold {{ request()->routeIs('user.dashboard') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700' }}">Dashboard</a>
                <a href="{{ route('user.orders') }}" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-semibold {{ request()->routeIs('user.orders') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700' }}">Orders</a>
                <a href="{{ route('user.subscriptions') }}" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-semibold {{ request()->routeIs('user.subscriptions') || request()->routeIs('user.downloads') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700' }}">Subscriptions</a>
                <a href="{{ route('user.addresses') }}" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-semibold {{ request()->routeIs('user.addresses') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700' }}">Addresses</a>
                <a href="{{ route('user.account-details') }}" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-semibold {{ request()->routeIs('user.account-details') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700' }}">Account details</a>
                <a href="{{ route('user.affiliate') }}" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-semibold {{ request()->routeIs('user.affiliate') || request()->routeIs('user.rewards') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700' }}">Affiliate</a>
                <a href="{{ route('user.withdraw') }}" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-semibold {{ request()->routeIs('user.withdraw') || request()->routeIs('user.coupons') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700' }}">Withdraw</a>
                <a href="{{ route('user.wishlist') }}" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-semibold {{ request()->routeIs('user.wishlist') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700' }}">Wishlist</a>
                <a href="{{ route('user.tickets.index') }}" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-semibold {{ request()->routeIs('user.tickets.*') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700' }}">Support Tickets</a>
                <a href="{{ route('user.notifications.index') }}" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-semibold {{ request()->routeIs('user.notifications.*') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700' }}">Notifications {{ ($userUnreadCount ?? 0) > 0 ? "({$userUnreadCount})" : '' }}</a>
            </nav>

            <!-- Page Title & Breadcrumbs -->
            <div class="mb-8">
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight mb-1.5">My account</h1>
                <nav class="flex items-center gap-2 text-xs md:text-sm text-slate-500 font-medium" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Home</a>
                    <span>/</span>
                    <a href="{{ route('user.dashboard') }}" class="text-slate-800 font-semibold hover:text-blue-600 transition">My account</a>
                    @hasSection('breadcrumb_current')
                        <span>/</span>
                        <span class="text-slate-600">@yield('breadcrumb_current')</span>
                    @endif
                </nav>
            </div>

            <!-- Global Flash Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <!-- Account Content -->
            @yield('account_content')
        </main>
    </div>

    <!-- =========================================================================
         3. Site Footer (Black Background - Matching Reference Screenshot)
         ========================================================================= -->
    <footer class="bg-black text-white w-full pt-14 pb-12 border-t border-white/10 relative z-10" aria-label="Site Footer">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-10 pb-10 border-b border-white/10">

                <!-- Column 1: Brand Info -->
                <div class="lg:col-span-4 space-y-4">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 group text-white text-2xl font-extrabold tracking-tight">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-blue-700 text-white font-extrabold flex items-center justify-center text-lg">d</div>
                        <span>digigo<span class="text-blue-400">.com.bd</span></span>
                    </a>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-xs">
                        We specialize in providing exclusively authentic digital products, gadgets and strategic solutions to help turn your ambitious ideas into reality.
                    </p>
                </div>

                <!-- Column 2: Popular Categories / Policies -->
                <div class="lg:col-span-3">
                    <h3 class="text-white font-bold text-base mb-4 tracking-tight">Popular Categories</h3>
                    <ul class="space-y-2 text-sm text-slate-300">
                        <li><a href="#privacy-policy" class="hover:text-blue-400 transition">Privacy Policy</a></li>
                        <li><a href="#refund-policy" class="hover:text-blue-400 transition">Refund Policy</a></li>
                        <li><a href="#delivery-policy" class="hover:text-blue-400 transition">Delivery Policy</a></li>
                        <li><a href="#support-faq" class="hover:text-blue-400 transition">Support & FAQ</a></li>
                    </ul>
                </div>

                <!-- Column 3: Useful Links -->
                <div class="lg:col-span-2">
                    <h3 class="text-white font-bold text-base mb-4 tracking-tight">Useful Links</h3>
                    <ul class="space-y-2 text-sm text-slate-300">
                        <li><a href="#about-us" class="hover:text-blue-400 transition">About Us</a></li>
                        <li><a href="#contact-us" class="hover:text-blue-400 transition">Contact Us</a></li>
                        <li><a href="#corporate-partnership" class="hover:text-blue-400 transition">Corporate Partnership</a></li>
                        <li><a href="#career" class="hover:text-blue-400 transition">Career</a></li>
                    </ul>
                </div>

                <!-- Column 4: App Downloads & Social Links -->
                <div class="lg:col-span-3 space-y-4">
                    <h3 class="text-white font-bold text-base tracking-tight">Available On:</h3>
                    <div class="flex flex-col gap-2.5">
                        <!-- Google Play -->
                        <a href="#google-play" class="inline-flex items-center gap-3 bg-[#18181b] hover:bg-[#27272a] border border-white/20 hover:border-blue-500 rounded-lg px-4 py-2 text-white transition max-w-max shadow-sm" title="Get it on Google Play">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 shrink-0"><path d="M3.609 1.814L13.793 12 3.61 22.186c-.352-.365-.558-.87-.558-1.428V3.242c0-.558.206-1.063.557-1.428zM15.207 13.414l2.586 2.586-11.897 6.84 9.31-9.426zm0-2.828L5.897 1.16 17.793 8l-2.586 2.586zm1.414 1.414l3.772-2.176c.725-.418.725-1.1 0-1.518L16.621 12z"/></svg>
                            <div class="flex flex-col leading-tight">
                                <span class="text-[9px] text-slate-400 uppercase font-semibold tracking-wider">GET IT ON</span>
                                <span class="text-xs font-bold text-white">Google Play</span>
                            </div>
                        </a>
                        <!-- App Store -->
                        <a href="#app-store" class="inline-flex items-center gap-3 bg-[#18181b] hover:bg-[#27272a] border border-white/20 hover:border-blue-500 rounded-lg px-4 py-2 text-white transition max-w-max shadow-sm" title="Download on the App Store">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 shrink-0"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 6.37c.61-.75 1.04-1.8 0.92-2.85-.92.04-2.02.62-2.67 1.37-.58.66-1.1 1.73-.96 2.76 1.02.08 2.08-.53 2.71-1.28"/></svg>
                            <div class="flex flex-col leading-tight">
                                <span class="text-[9px] text-slate-400 uppercase font-semibold tracking-wider">Download on the</span>
                                <span class="text-xs font-bold text-white">App Store</span>
                            </div>
                        </a>
                    </div>

                    <h3 class="text-white font-bold text-sm tracking-tight pt-1">Social links:</h3>
                    <div class="flex items-center gap-2.5">
                        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-[#1877f2] text-white flex items-center justify-center hover:scale-110 transition shadow-sm" title="Facebook">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-gradient-to-tr from-amber-400 via-rose-500 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition shadow-sm" title="Instagram">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="https://whatsapp.com" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-[#25d366] text-white flex items-center justify-center hover:scale-110 transition shadow-sm" title="WhatsApp">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.969.587 1.761.889 2.796.889 3.182 0 5.768-2.587 5.769-5.766.001-3.182-2.585-5.776-5.769-5.776zm3.385 8.213c-.14.394-.712.729-1.009.776-.282.045-.635.074-1.802-.408-1.493-.618-2.457-2.138-2.531-2.237-.074-.099-.607-.808-.607-1.543s.385-1.096.522-1.246c.137-.15.299-.187.399-.187.1 0 .2.001.288.006.092.005.215-.035.337.257.126.301.431 1.05.469 1.127.038.077.063.167.013.267-.05.1-.075.162-.15.25-.075.088-.158.196-.226.264-.075.075-.153.157-.066.307.088.15.39 1.02.836 1.417.575.512 1.059.67 1.209.745.15.075.238.063.326-.038.088-.1.376-.438.476-.588.1-.15.2-.125.338-.075.138.05.876.413 1.026.488.15.075.25.112.288.175.038.063.038.363-.102.757z"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright & Payment Badges -->
            <div class="pt-6 flex flex-col md:flex-row items-center justify-between text-xs text-slate-400 gap-4">
                <p>
                    <strong class="text-white font-bold">DigiGo</strong> Copyright 2018-{{ date('Y') }} <strong class="text-white font-bold">All Right Reserved.</strong>
                </p>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center justify-center bg-white text-[#d12053] font-extrabold text-xs px-2.5 py-1 rounded shadow-sm h-7">bKash</span>
                    <span class="inline-flex items-center justify-center bg-white text-[#f7941d] font-extrabold text-xs px-2.5 py-1 rounded shadow-sm h-7">নগদ</span>
                    <span class="inline-flex items-center justify-center bg-white text-[#8c3494] font-extrabold text-xs px-2.5 py-1 rounded shadow-sm h-7">rocket</span>
                    <span class="inline-flex items-center justify-center bg-white text-[#005baa] font-extrabold text-xs px-2.5 py-1 rounded shadow-sm h-7">upay</span>
                    <span class="inline-flex items-center justify-center bg-[#0284c7] text-white font-extrabold text-xs px-2.5 py-1 rounded shadow-sm h-7">VISA / Master</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- =========================================================================
         4. Floating Widgets: Scroll to Top + "Contact us" WhatsApp Pill
         ========================================================================= -->
    <div class="fixed bottom-6 right-6 z-50 flex items-center gap-3">
        <!-- Scroll-To-Top Button (Circle with up chevron) -->
        <button type="button" id="scrollToTopBtn" aria-label="Scroll to top" class="w-10 h-10 rounded-full bg-slate-900/80 hover:bg-slate-900 text-white flex items-center justify-center shadow-lg transition-all duration-300 opacity-0 pointer-events-none hover:scale-105 cursor-pointer">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="18 15 12 9 6 15"></polyline>
            </svg>
        </button>

        <!-- "Contact us" Pill WhatsApp Support -->
        <a href="https://wa.me/8801600793325" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2.5 group cursor-pointer" aria-label="Contact Customer Support">
            <div class="w-11 h-11 rounded-full bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center text-lg shadow-xl group-hover:scale-105 transition">
                <i class="fa-solid fa-comment-dots"></i>
            </div>
        </a>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        // Smooth Scroll To Top Handler
        const scrollToTopBtn = document.getElementById('scrollToTopBtn');
        if (scrollToTopBtn) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 200) {
                    scrollToTopBtn.classList.remove('opacity-0', 'pointer-events-none');
                    scrollToTopBtn.classList.add('opacity-100', 'pointer-events-auto');
                } else {
                    scrollToTopBtn.classList.add('opacity-0', 'pointer-events-none');
                    scrollToTopBtn.classList.remove('opacity-100', 'pointer-events-auto');
                }
            });
            scrollToTopBtn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        // User Notification Dropdown Toggle
        const userNotifBtn = document.getElementById('userNotificationBtn');
        const userNotifDropdown = document.getElementById('userNotificationDropdown');
        if (userNotifBtn && userNotifDropdown) {
            userNotifBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                userNotifDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!e.target.closest('#userNotificationWrapper')) {
                    userNotifDropdown.classList.add('hidden');
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
