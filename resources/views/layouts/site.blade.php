<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', setting('meta_title', 'DigiGo - Digital Products, Software & Subscriptions'))</title>

    <!-- Dynamic SEO & Meta Tags -->
    <meta name="description" content="@yield('meta_description', setting('meta_description', 'Bangladesh\'s leading digital software, subscription keys, gift cards, and AI tools marketplace.'))">
    <meta name="keywords" content="{{ setting('meta_keywords', 'digital products, software keys, subscription, ai tools, bangladesh, gift cards') }}">
    <meta name="author" content="{{ setting('meta_author', 'DigiGo Bangladesh') }}">
    <meta name="robots" content="{{ setting('meta_robots', 'index, follow') }}">

    <!-- Open Graph (Facebook / WhatsApp / Social Media) -->
    <meta property="og:title" content="@yield('og_title', setting('og_title', setting('meta_title', 'DigiGo - Digital Products, Software & Subscriptions')))">
    <meta property="og:description" content="@yield('og_description', setting('og_description', setting('meta_description', 'Bangladesh\'s leading digital software, subscription keys, gift cards, and AI tools marketplace.')))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    @if(setting_url('og_image'))
        <meta property="og:image" content="{{ setting_url('og_image') }}">
    @elseif(setting_url('site_logo'))
        <meta property="og:image" content="{{ setting_url('site_logo') }}">
    @endif

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="{{ setting('twitter_card', 'summary_large_image') }}">
    <meta name="twitter:title" content="@yield('og_title', setting('og_title', setting('meta_title', 'DigiGo')))">
    <meta name="twitter:description" content="@yield('og_description', setting('og_description', setting('meta_description', '')))">
    @if(setting_url('og_image'))
        <meta name="twitter:image" content="{{ setting_url('og_image') }}">
    @endif

    <!-- Browser Favicon -->
    <link rel="icon" href="{{ default_site_favicon() }}">

    <!-- Google Fonts: Plus Jakarta Sans & Hind Siliguri -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Owl Carousel 2 CSS for product sliders -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

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
    <!-- Core App & Homepage Custom Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col selection:bg-blue-600 selection:text-white">

    <!-- =========================================================================
         Header Section (Top Header + Desktop Blue Ribbon Nav)
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
                            <a href="{{ route('user.dashboard') }}" class="w-9 h-9 rounded-full bg-white/15 hover:bg-white text-white hover:text-blue-700 flex items-center justify-center transition" title="My Account">
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

    <!-- Mobile Fullscreen Search Modal -->
    <div id="mobileSearchModal" class="fixed inset-0 bg-white z-50 opacity-0 pointer-events-none -translate-y-2 transition-all duration-200 overflow-y-auto p-4 [&.active]:opacity-100 [&.active]:pointer-events-auto [&.active]:translate-y-0" aria-hidden="true">
        <div class="flex items-center justify-between pb-4 border-b border-slate-200">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-blue-600 text-white font-extrabold flex items-center justify-center">d</div>
                <span class="text-xl font-extrabold text-slate-900">digigo<span class="text-blue-600">.com.bd</span></span>
            </a>
            <button type="button" class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center js-search-modal-close" aria-label="Close search">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="12" x2="18" y2="12"></line></svg>
            </button>
        </div>
        <div class="pt-4 space-y-5">
            <form action="{{ route('search') }}" method="GET" class="relative">
                <input type="text" name="q" id="mobileSearchInput" class="w-full h-12 pl-4 pr-10 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-blue-600" placeholder="Search for products..." autocomplete="off">
            </form>
            <div>
                <div class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-3">POPULAR REQUESTS</div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ url('/search?q=Canva') }}" class="px-3 py-1.5 rounded-full bg-slate-100 hover:bg-blue-50 text-xs font-semibold text-slate-700 hover:text-blue-700 transition">CANVA</a>
                    <a href="{{ url('/search?q=ChatGPT') }}" class="px-3 py-1.5 rounded-full bg-slate-100 hover:bg-blue-50 text-xs font-semibold text-slate-700 hover:text-blue-700 transition">CHATGPT</a>
                    <a href="{{ url('/search?q=Perplexity') }}" class="px-3 py-1.5 rounded-full bg-slate-100 hover:bg-blue-50 text-xs font-semibold text-slate-700 hover:text-blue-700 transition">PERPLEXITY</a>
                    <a href="{{ url('/search?q=VPN') }}" class="px-3 py-1.5 rounded-full bg-slate-100 hover:bg-blue-50 text-xs font-semibold text-slate-700 hover:text-blue-700 transition">SURFSHARK VPN</a>
                    <a href="{{ url('/search?q=Apple') }}" class="px-3 py-1.5 rounded-full bg-slate-100 hover:bg-blue-50 text-xs font-semibold text-slate-700 hover:text-blue-700 transition">APPLE GIFT CARD</a>
                </div>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         Main Content Area
         ========================================================================= -->
    <main class="flex-1 w-full">
        @yield('content')
    </main>

    <!-- =========================================================================
         Featured Blog Section (Above Footer - Shows max 3 featured posts)
         ========================================================================= -->
    @php
        $siteFeaturedBlogPosts = \App\Models\BlogPost::with('category')
            ->where('status', 'active')
            ->where('is_featured', true)
            ->orderByDesc('id')
            ->take(3)
            ->get();
    @endphp
    @if($siteFeaturedBlogPosts->isNotEmpty())
        <section class="w-full bg-slate-50 border-t border-slate-200 py-12 md:py-16">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 md:mb-10 gap-4">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold uppercase tracking-wider mb-2">
                            <i class="fa-solid fa-newspaper text-[11px]"></i>
                            <span>Latest Articles & Guides</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                            From Our Blog and Resources
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Stay updated with digital software tips, AI tricks, tutorials, and tech insights.
                        </p>
                    </div>

                    <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-700 group transition">
                        <span>View All Articles</span>
                        <i class="fa-solid fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @foreach($siteFeaturedBlogPosts as $post)
                        <article class="bg-white rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition duration-300 overflow-hidden flex flex-col group">
                            <a href="{{ route('blog.show', $post->slug) }}" class="relative aspect-[16/10] overflow-hidden bg-slate-100 block">
                                @if($post->thumbnail_img)
                                    <img src="{{ $post->thumbnail_img }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400 bg-gradient-to-br from-slate-100 to-slate-200">
                                        <i class="fa-regular fa-newspaper text-3xl"></i>
                                    </div>
                                @endif
                                @if($post->category)
                                    <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-blue-600 text-white text-[11px] font-bold shadow-sm">
                                        {{ $post->category->name }}
                                    </span>
                                @endif
                            </a>

                            <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center gap-2 text-xs text-slate-400 mb-2.5">
                                        <span><i class="fa-regular fa-calendar mr-1"></i>{{ $post->created_at->format('M d, Y') }}</span>
                                        <span>&bull;</span>
                                        <span>{{ max(1, ceil(str_word_count(strip_tags($post->detailes)) / 200)) }} min read</span>
                                    </div>
                                    <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-blue-600 transition line-clamp-2 leading-snug mb-2">
                                        <a href="{{ route('blog.show', $post->slug) }}">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    <p class="text-xs sm:text-sm text-slate-500 line-clamp-2 leading-relaxed">
                                        {{ Str::limit(strip_tags($post->detailes), 110) }}
                                    </p>
                                </div>

                                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                                        <span>Read Full Article</span>
                                        <i class="fa-solid fa-angle-right text-[10px]"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- =========================================================================
         Site Footer (Black Background - Completely Dynamic from Admin Panel)
         ========================================================================= -->
    <footer class="bg-black text-white w-full mt-16 pt-14 pb-12 mb-14 md:mb-0 border-t border-white/10 relative z-10" aria-label="Site Footer">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-10 pb-10 border-b border-white/10">

                <!-- Column 1: Brand Info -->
                <div class="lg:col-span-4 space-y-4">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 group text-white text-2xl font-extrabold tracking-tight">
                        @if(setting_url('footer_custom_logo'))
                            <img src="{{ setting_url('footer_custom_logo') }}" alt="{{ setting('site_name', 'DigiGo') }}" class="h-9 w-auto max-w-[180px] object-contain">
                        @else
                            <img src="{{ default_site_logo() }}" alt="{{ setting('site_name', 'DigiGo') }}" class="h-9 w-auto max-w-[180px] object-contain">
                        @endif
                    </a>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-xs">
                        {{ setting('footer_about_text', 'We specialize in providing exclusively authentic digital products, gadgets and strategic solutions to help turn your ambitious ideas into reality.') }}
                    </p>
                </div>

                <!-- Column 2: Popular Categories / Policies -->
                <div class="lg:col-span-3">
                    <h3 class="text-white font-bold text-base mb-4 tracking-tight">
                        {{ setting('footer_col2_title', 'Popular Categories') }}
                    </h3>
                    <ul class="space-y-2 text-sm text-slate-300">
                        @for($i = 1; $i <= 4; $i++)
                            @if(setting("footer_col2_link{$i}_text"))
                                <li>
                                    <a href="{{ setting("footer_col2_link{$i}_url", '#') }}" class="hover:text-blue-400 transition">
                                        {{ setting("footer_col2_link{$i}_text") }}
                                    </a>
                                </li>
                            @endif
                        @endfor
                    </ul>
                </div>

                <!-- Column 3: Useful Links -->
                <div class="lg:col-span-2">
                    <h3 class="text-white font-bold text-base mb-4 tracking-tight">
                        {{ setting('footer_col3_title', 'Useful Links') }}
                    </h3>
                    <ul class="space-y-2 text-sm text-slate-300">
                        @for($i = 1; $i <= 4; $i++)
                            @if(setting("footer_col3_link{$i}_text"))
                                <li>
                                    <a href="{{ setting("footer_col3_link{$i}_url", '#') }}" class="hover:text-blue-400 transition">
                                        {{ setting("footer_col3_link{$i}_text") }}
                                    </a>
                                </li>
                            @endif
                        @endfor
                    </ul>
                </div>

                <!-- Column 4: App Downloads & Social Links -->
                <div class="lg:col-span-3 space-y-4">
                    @if(setting('footer_play_store_enabled', '1') === '1' || setting('footer_app_store_enabled', '1') === '1')
                        <h3 class="text-white font-bold text-base tracking-tight">
                            {{ setting('footer_app_title', 'Available On:') }}
                        </h3>
                        <div class="flex flex-col gap-2.5">
                            <!-- Google Play -->
                            @if(setting('footer_play_store_enabled', '1') === '1')
                                <a href="{{ setting('footer_play_store_url', '#google-play') }}" class="inline-flex items-center gap-3 bg-[#18181b] hover:bg-[#27272a] border border-white/20 hover:border-blue-500 rounded-lg px-4 py-2 text-white transition max-w-max shadow-sm" title="Get it on Google Play">
                                    <svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 shrink-0"><path d="M3.609 1.814L13.793 12 3.61 22.186c-.352-.365-.558-.87-.558-1.428V3.242c0-.558.206-1.063.557-1.428zM15.207 13.414l2.586 2.586-11.897 6.84 9.31-9.426zm0-2.828L5.897 1.16 17.793 8l-2.586 2.586zm1.414 1.414l3.772-2.176c.725-.418.725-1.1 0-1.518L16.621 12z"/></svg>
                                    <div class="flex flex-col leading-tight">
                                        <span class="text-[9px] text-slate-400 uppercase font-semibold tracking-wider">GET IT ON</span>
                                        <span class="text-xs font-bold text-white">Google Play</span>
                                    </div>
                                </a>
                            @endif

                            <!-- App Store -->
                            @if(setting('footer_app_store_enabled', '1') === '1')
                                <a href="{{ setting('footer_app_store_url', '#app-store') }}" class="inline-flex items-center gap-3 bg-[#18181b] hover:bg-[#27272a] border border-white/20 hover:border-blue-500 rounded-lg px-4 py-2 text-white transition max-w-max shadow-sm" title="Download on the App Store">
                                    <svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 shrink-0"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 6.37c.61-.75 1.04-1.8 0.92-2.85-.92.04-2.02.62-2.67 1.37-.58.66-1.1 1.73-.96 2.76 1.02.08 2.08-.53 2.71-1.28"/></svg>
                                    <div class="flex flex-col leading-tight">
                                        <span class="text-[9px] text-slate-400 uppercase font-semibold tracking-wider">Download on the</span>
                                        <span class="text-xs font-bold text-white">App Store</span>
                                    </div>
                                </a>
                            @endif
                        </div>
                    @endif

                    <h3 class="text-white font-bold text-sm tracking-tight pt-1">
                        {{ setting('footer_social_title', 'Social links:') }}
                    </h3>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        @if(setting('footer_facebook_url'))
                            <a href="{{ setting('footer_facebook_url') }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-[#1877f2] text-white flex items-center justify-center hover:scale-110 transition shadow-sm" title="Facebook">
                                <svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                        @endif

                        @if(setting('footer_instagram_url'))
                            <a href="{{ setting('footer_instagram_url') }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-gradient-to-tr from-amber-400 via-rose-500 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition shadow-sm" title="Instagram">
                                <svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                        @endif

                        @if(setting('footer_whatsapp_url'))
                            <a href="{{ setting('footer_whatsapp_url') }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-[#25d366] text-white flex items-center justify-center hover:scale-110 transition shadow-sm" title="WhatsApp">
                                <svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.969.587 1.761.889 2.796.889 3.182 0 5.768-2.587 5.769-5.766.001-3.182-2.585-5.776-5.769-5.776zm3.385 8.213c-.14.394-.712.729-1.009.776-.282.045-.635.074-1.802-.408-1.493-.618-2.457-2.138-2.531-2.237-.074-.099-.607-.808-.607-1.543s.385-1.096.522-1.246c.137-.15.299-.187.399-.187.1 0 .2.001.288.006.092.005.215-.035.337.257.126.301.431 1.05.469 1.127.038.077.063.167.013.267-.05.1-.075.162-.15.25-.075.088-.158.196-.226.264-.075.075-.153.157-.066.307.088.15.39 1.02.836 1.417.575.512 1.059.67 1.209.745.15.075.238.063.326-.038.088-.1.376-.438.476-.588.1-.15.2-.125.338-.075.138.05.876.413 1.026.488.15.075.25.112.288.175.038.063.038.363-.102.757z"/></svg>
                            </a>
                        @endif

                        @if(setting('footer_youtube_url'))
                            <a href="{{ setting('footer_youtube_url') }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-[#ff0000] text-white flex items-center justify-center hover:scale-110 transition shadow-sm" title="YouTube">
                                <i class="fa-brands fa-youtube text-sm"></i>
                            </a>
                        @endif

                        @if(setting('footer_twitter_url'))
                            <a href="{{ setting('footer_twitter_url') }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center hover:scale-110 transition shadow-sm border border-slate-700" title="X (Twitter)">
                                <i class="fa-brands fa-x-twitter text-sm"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright & Payment Badges -->
            <div class="pt-6 flex flex-col md:flex-row items-center justify-between text-xs text-slate-400 gap-4 md:pr-16">
                <p>
                    {{ setting('footer_copyright_text', 'DigiGo Copyright 2018-2026 All Right Reserved.') }}
                </p>

                @if(setting('footer_payment_methods_enabled', '1') === '1')
                    <div class="flex items-center gap-2 flex-wrap">
                        @if(setting('footer_payment_dgepay', '1') === '1')
                            <span class="inline-flex items-center justify-center bg-white px-2 py-1 rounded shadow-sm h-7 border border-slate-200">
                                <img src="{{ asset('assets/images/dgepay.png') }}" alt="DGePay Payment Gateway" class="h-4 w-auto object-contain">
                            </span>
                        @endif
                        @if(setting('footer_payment_bkash', '1') === '1')
                            <span class="inline-flex items-center justify-center bg-white text-[#d12053] font-extrabold text-xs px-2.5 py-1 rounded shadow-sm h-7">bKash</span>
                        @endif
                        @if(setting('footer_payment_nagad', '1') === '1')
                            <span class="inline-flex items-center justify-center bg-white text-[#f7941d] font-extrabold text-xs px-2.5 py-1 rounded shadow-sm h-7">নগদ</span>
                        @endif
                        @if(setting('footer_payment_rocket', '1') === '1')
                            <span class="inline-flex items-center justify-center bg-white text-[#8c3494] font-extrabold text-xs px-2.5 py-1 rounded shadow-sm h-7">rocket</span>
                        @endif
                        @if(setting('footer_payment_upay', '1') === '1')
                            <span class="inline-flex items-center justify-center bg-white text-[#005baa] font-extrabold text-xs px-2.5 py-1 rounded shadow-sm h-7">upay</span>
                        @endif
                        @if(setting('footer_payment_cards', '1') === '1')
                            <span class="inline-flex items-center justify-center bg-[#0284c7] text-white font-extrabold text-xs px-2.5 py-1 rounded shadow-sm h-7">VISA / Master</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </footer>

    <!-- Floating Contact Support Widget (Circular Button) -->
    @if(setting('footer_floating_chat_enabled', '1') === '1')
        <aside class="fixed bottom-20 md:bottom-8 right-4 md:right-8 z-50" aria-label="Customer Contact Support">
            <a href="{{ setting('footer_floating_chat_link', 'https://wa.me/8801600793325') }}" target="_blank" rel="noopener noreferrer" class="w-12 h-12 rounded-full bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center text-xl shadow-xl hover:scale-110 transition active:scale-95" aria-label="Chat with Support">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="w-6 h-6">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    <line x1="8" y1="9" x2="16" y2="9"></line>
                    <line x1="8" y1="13" x2="14" y2="13"></line>
                </svg>
            </a>
        </aside>
    @endif

    <!-- Mobile Fixed Bottom Navigation Bar -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 h-14 bg-white border-t border-slate-200 flex items-center justify-around z-40 px-2" aria-label="Mobile Navigation">
        <button type="button" class="flex flex-col items-center text-xs text-slate-600 hover:text-blue-600 js-sidebar-toggle">
            <svg class="w-5 h-5 mb-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            <span>Menu</span>
        </button>

        <a href="{{ route('wishlist') }}" class="flex flex-col items-center text-xs text-slate-600 hover:text-blue-600 relative">
            <svg class="w-5 h-5 mb-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
            <span class="header-wishlist-badge absolute -top-1 right-2 min-w-3.5 h-3.5 px-1 rounded-full bg-rose-500 text-white text-[9px] font-bold flex items-center justify-center {{ $headerWishlistCount > 0 ? '' : 'hidden' }}">{{ $headerWishlistCount }}</span>
            <span>Wishlist</span>
        </a>

        <a href="{{ route('cart') }}" class="flex flex-col items-center text-xs text-slate-600 hover:text-blue-600 relative">
            <svg class="w-5 h-5 mb-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            <span class="header-cart-badge absolute -top-1 right-2 min-w-3.5 h-3.5 px-1 rounded-full bg-blue-600 text-white text-[9px] font-bold flex items-center justify-center {{ $headerCartCount > 0 ? '' : 'hidden' }}">{{ $headerCartCount }}</span>
            <span>Cart</span>
        </a>

        @auth
            <a href="{{ route('user.dashboard') }}" class="flex flex-col items-center text-xs text-slate-600 hover:text-blue-600">
                <svg class="w-5 h-5 mb-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                <span>Account</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="flex flex-col items-center text-xs text-slate-600 hover:text-blue-600">
                <svg class="w-5 h-5 mb-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                <span>Login</span>
            </a>
        @endauth
    </nav>

    <!-- jQuery & Custom JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
