<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- SEO Meta Tags -->
    <title>DigiGo - Digital Products, Software & Subscription Services</title>
    <meta name="description" content="DigiGo is your trusted digital store for AI tools, Gift Cards, Cloud Storage, Streaming Subscriptions, Operating Systems, and software solutions.">
    <meta name="keywords" content="digital products, ai tools, subscriptions, gift cards, software keys, vps, cloud storage">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="DigiGo - Digital Products & Software Store">
    <meta property="og:description" content="Best deals on AI tools, Software Subscriptions, and Digital Services.">

    <!-- Google Fonts (Hind Siliguri for Bengali + Plus Jakarta Sans for UI) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome 6.5.1 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Owl Carousel 2 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>

    <!-- =========================================================================
         Header Section (Desktop & Mobile)
         ========================================================================= -->
    <header class="site-header">
        <!-- Top Header Bar -->
        <!-- Top Header Bar -->
        <div class="top-header">
            <div class="container top-header-inner">

                <!-- Left: Sidebar Toggle Button -->
                <div class="header-left-group">
                    <button type="button" class="sidebar-toggle-btn js-sidebar-toggle" aria-label="Toggle Navigation Menu">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <!-- Center: Brand Logo (Desktop & Mobile Centered) -->
                <div class="header-logo-group">
                    <a href="{{ url('/') }}" class="brand-logo" aria-label="DigiGo Homepage">
                        <div class="logo-icon">d</div>
                        <span class="brand-text">digigo<span class="text-primary">.com.bd</span></span>
                    </a>
                </div>

                <!-- Desktop Search Bar -->
                <div class="header-search-box desktop-search-box desktop-only">
                    <form action="#" method="GET" class="search-form" role="search">
                        <input type="text" name="q" class="search-input" placeholder="Search for products..." aria-label="Search products">
                        <button type="submit" class="search-submit-btn" aria-label="Submit Search">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Right: Desktop Contact Numbers & Mobile Search Button -->
                <div class="header-right-group">
                    <div class="header-contact-info desktop-only">
                        <div class="contact-item">
                            <span class="contact-label">24 Support</span>
                            <a href="tel:+8809611678088" class="contact-value">+8809611678088</a>
                        </div>
                        <div class="contact-item">
                            <span class="contact-label">WhatsApp</span>
                            <a href="https://wa.me/8801600793325" target="_blank" rel="noopener noreferrer" class="contact-value">+8801600793325</a>
                        </div>
                    </div>

                    <!-- Mobile Search Trigger Button (Circular Magenta/Purple Button matching Bongo Digital) -->
                    <button type="button" class="mobile-search-trigger js-search-modal-open" aria-label="Open Search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        <!-- Sub Header Bar (Desktop Blue Ribbon) -->
        <div class="sub-header-bar desktop-only">
            <div class="container sub-header-inner">

                <!-- Left: Menu Pill Toggle -->
                <button type="button" class="menu-pill-btn js-sidebar-toggle" aria-label="Open Categories Menu">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                    <span>All Categories</span>
                </button>

                <!-- Center: Navigation Links -->
                <nav class="main-nav-links" aria-label="Main Navigation">
                    <a href="{{ url('/') }}" class="nav-link-item active">Home</a>
                    <a href="#" class="nav-link-item">All Products</a>
                    <a href="#" class="nav-link-item">Special Offers</a>
                    <a href="#" class="nav-link-item">Support</a>
                </nav>

                <!-- Right: Action Icons (Cart, Wishlist, Account) -->
                <div class="header-action-icons">
                    <a href="#" class="action-icon-btn" aria-label="Shopping Cart">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <span class="action-badge">1</span>
                    </a>

                    <a href="#" class="action-icon-btn" aria-label="Wishlist">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                        <span class="action-badge">0</span>
                    </a>

                    <a href="#" class="action-icon-btn" aria-label="User Account">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </a>
                </div>

            </div>
        </div>
    </header>

    <!-- =========================================================================
         Mobile Fullscreen Search Modal (Matching Mobile Screenshot 4)
         ========================================================================= -->
    <div class="mobile-search-modal" id="mobileSearchModal" aria-hidden="true">
        <div class="mobile-search-header">
            <a href="{{ url('/') }}" class="brand-logo">
                <div class="logo-icon">d</div>
                <span class="brand-text">digigo<span class="text-primary">.com.bd</span></span>
            </a>

            <button type="button" class="search-modal-close-btn js-search-modal-close" aria-label="Close Search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="mobile-search-body">
            <!-- Search Input Box -->
            <form action="#" method="GET" class="mobile-search-input-wrap">
                <input type="text" name="q" id="mobileSearchInput" class="mobile-search-input" placeholder="Search for products" autocomplete="off">
            </form>

            <!-- Search History Section -->
            <div class="search-section-block">
                <div class="search-section-title">SEARCH HISTORY</div>
                <div class="search-history-list">
                    <div class="history-tag">
                        <span class="tag-text">sa</span>
                        <button type="button" class="tag-remove" aria-label="Remove search item">✕</button>
                    </div>
                </div>
            </div>

            <!-- Popular Requests Section -->
            <div class="search-section-block">
                <div class="search-section-title">POPULAR REQUESTS</div>
                <div class="popular-tags-grid">
                    <button type="button" class="popular-tag-btn">CANVA</button>
                    <button type="button" class="popular-tag-btn">VEO 3</button>
                    <button type="button" class="popular-tag-btn">PERPLEXITY</button>
                    <button type="button" class="popular-tag-btn">TRUECALLER</button>
                    <button type="button" class="popular-tag-btn">APPLE GIFT CARD</button>
                    <button type="button" class="popular-tag-btn">SURFSHARK VPN</button>
                    <button type="button" class="popular-tag-btn">WINDOWS 11</button>
                    <button type="button" class="popular-tag-btn">CRUNCHYROLL</button>
                </div>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         Mobile & Desktop Sidebar Drawer with Tabs (CATEGORIES & MENU)
         (Matching Mobile Screenshots 2 & 3)
         ========================================================================= -->
    <!-- Overlay Backdrop -->
    <div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>

    <!-- Category Sidebar Drawer -->
    <aside class="category-sidebar" id="categorySidebar" aria-label="Sidebar Navigation">
        <!-- Top Tabs Header (CATEGORIES / MENU) -->
        <div class="sidebar-tabs-header">
            <button type="button" class="sidebar-tab-btn active" data-tab="categories">CATEGORIES</button>
            <button type="button" class="sidebar-tab-btn" data-tab="menu">MENU</button>
        </div>

        <!-- Tab 1: Categories Content -->
        <div class="sidebar-tab-content active" id="tabCategories">
            <ul class="sidebar-menu-list">
                <!-- 1. Artificial intelligence (AI) -->
                <li class="sidebar-menu-item">
                    <a href="#ai" class="sidebar-menu-link active">
                        <span class="sidebar-item-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"></path>
                            </svg>
                        </span>
                        <span class="sidebar-item-text">Artificial Intelligence (AI)</span>
                    </a>
                </li>

                <!-- 2. Game & Gift Card -->
                <li class="sidebar-menu-item">
                    <a href="#games-gift-cards" class="sidebar-menu-link">
                        <span class="sidebar-item-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="6" width="20" height="12" rx="2"></rect>
                                <line x1="6" y1="12" x2="10" y2="12"></line>
                                <line x1="8" y1="10" x2="8" y2="14"></line>
                                <line x1="15" y1="13" x2="15.01" y2="13"></line>
                                <line x1="18" y1="11" x2="18.01" y2="11"></line>
                            </svg>
                        </span>
                        <span class="sidebar-item-text">Game & Gift Card</span>
                    </a>
                </li>

                <!-- 3. Cloud Storage -->
                <li class="sidebar-menu-item">
                    <a href="#cloud-storage" class="sidebar-menu-link">
                        <span class="sidebar-item-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"></path>
                            </svg>
                        </span>
                        <span class="sidebar-item-text">Cloud Storage</span>
                    </a>
                </li>

                <!-- 4. Entertainment & Streaming -->
                <li class="sidebar-menu-item">
                    <a href="#entertainment-streaming" class="sidebar-menu-link">
                        <span class="sidebar-item-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="15" rx="2"></rect>
                                <polygon points="10 8 16 11.5 10 15 10 8"></polygon>
                            </svg>
                        </span>
                        <span class="sidebar-item-text">Entertainment & Streaming</span>
                    </a>
                </li>

                <!-- 5. Mobile Apps -->
                <li class="sidebar-menu-item">
                    <a href="#mobile-apps" class="sidebar-menu-link">
                        <span class="sidebar-item-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                                <line x1="12" y1="18" x2="12.01" y2="18"></line>
                            </svg>
                        </span>
                        <span class="sidebar-item-text">Mobile Apps</span>
                    </a>
                </li>

                <!-- 6. Office & Productivity -->
                <li class="sidebar-menu-item">
                    <a href="#office-productivity" class="sidebar-menu-link">
                        <span class="sidebar-item-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                            </svg>
                        </span>
                        <span class="sidebar-item-text">Office & Productivity</span>
                    </a>
                </li>

                <!-- 7. Operating Systems -->
                <li class="sidebar-menu-item">
                    <a href="#operating-systems" class="sidebar-menu-link">
                        <span class="sidebar-item-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                <line x1="8" y1="21" x2="16" y2="21"></line>
                                <line x1="12" y1="17" x2="12" y2="21"></line>
                            </svg>
                        </span>
                        <span class="sidebar-item-text">Operating Systems</span>
                    </a>
                </li>

                <!-- 8. Privacy & Security -->
                <li class="sidebar-menu-item">
                    <a href="#privacy-security" class="sidebar-menu-link">
                        <span class="sidebar-item-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                        </span>
                        <span class="sidebar-item-text">Privacy & Security</span>
                    </a>
                </li>

                <!-- 9. Subscription Services -->
                <li class="sidebar-menu-item">
                    <a href="#subscription-services" class="sidebar-menu-link">
                        <span class="sidebar-item-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </span>
                        <span class="sidebar-item-text">Subscription Services</span>
                    </a>
                </li>

                <!-- 10. E-Learning -->
                <li class="sidebar-menu-item">
                    <a href="#e-learning" class="sidebar-menu-link">
                        <span class="sidebar-item-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                            </svg>
                        </span>
                        <span class="sidebar-item-text">E-Learning</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Tab 2: Menu Content (Matching Mobile Screenshot 3) -->
        <div class="sidebar-tab-content" id="tabMenu">
            <ul class="sidebar-menu-list">
                <li class="sidebar-menu-item">
                    <a href="{{ url('/') }}" class="sidebar-menu-link">
                        <span class="sidebar-item-text">Home</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="#promotions" class="sidebar-menu-link">
                        <span class="sidebar-item-text">Promotions</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="#support-zone" class="sidebar-menu-link">
                        <span class="sidebar-item-text">Support Zone</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="#about-us" class="sidebar-menu-link">
                        <span class="sidebar-item-text">About Us</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="#contact-us" class="sidebar-menu-link">
                        <span class="sidebar-item-text">Contact Us</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="#store-locations" class="sidebar-menu-link">
                        <span class="sidebar-item-text">Store Locations</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="#wishlist" class="sidebar-menu-link">
                        <span class="sidebar-item-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </span>
                        <span class="sidebar-item-text">Wishlist</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- =========================================================================
         Main Layout: Mini Icon Rail (Desktop) + Content Area (Mobile & Desktop)
         ========================================================================= -->
    <div class="layout-container">


        <!-- Main Content Area with Banner Slider & Mobile Features -->
        <main class="main-content">
            <div class="banner-wrapper">

                <!-- Hero Banner Carousel (Mobile & Desktop - Matching Bongo Digital) -->
                <section class="hero-carousel" id="heroCarousel" aria-label="Featured Promotions">
                    <div class="carousel-track">

                        <!-- Slide 1: Elevate your audio experience -->
                        <div class="carousel-slide active">
                            <div class="banner-card">
                                <img src="https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=1600&q=80" alt="Audio Experience" class="banner-cover-img" loading="eager">
                                <div class="banner-content">
                                    <h1 class="banner-title">
                                        Elevate your audio <span class="highlight-symbol">🎧</span><br>
                                        experience
                                    </h1>
                                    <p class="banner-description">
                                        Immerse yourself in crystal clear sound with our premium wireless headphones and high-fidelity earbuds at best prices.
                                    </p>
                                    <div class="banner-action">
                                        <a href="#audio-sound" class="btn-shop-now">SHOP NOW</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 2: Premium AI Tools & Software Keys -->
                        <div class="carousel-slide">
                            <div class="banner-card">
                                <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=1600&q=80" alt="Premium AI Tools & Software Keys" class="banner-cover-img" loading="lazy">
                                <div class="banner-content">
                                    <h2 class="banner-title">
                                        Premium AI Tools <span class="highlight-symbol">🚀</span><br>
                                        & Software Keys
                                    </h2>
                                    <p class="banner-description">
                                        Supercharge your workflow with genuine licenses for top AI tools, creative software, and productivity suites with instant delivery.
                                    </p>
                                    <div class="banner-action">
                                        <a href="#ai-tools" class="btn-shop-now">EXPLORE DEALS</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 3: Smart Watches & Accessories -->
                        <div class="carousel-slide">
                            <div class="banner-card">
                                <img src="https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?auto=format&fit=crop&w=1600&q=80" alt="Smart Wearable Tech" class="banner-cover-img" loading="lazy">
                                <div class="banner-content">
                                    <h2 class="banner-title">
                                        Smart Devices <span class="highlight-symbol">⌚</span><br>
                                        & Wearable Tech
                                    </h2>
                                    <p class="banner-description">
                                        Discover the latest smartwatches, fitness trackers, and modern smart accessories engineered for your everyday lifestyle.
                                    </p>
                                    <div class="banner-action">
                                        <a href="#smartwatches" class="btn-shop-now">ORDER NOW</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 4: Ultra Fast Charging & Power Solutions -->
                        <div class="carousel-slide">
                            <div class="banner-card">
                                <img src="https://images.unsplash.com/photo-1546868871-7041f2a55e12?auto=format&fit=crop&w=1600&q=80" alt="Fast Charging Solutions" class="banner-cover-img" loading="lazy">
                                <div class="banner-content">
                                    <h2 class="banner-title">
                                        Fast Charging <span class="highlight-symbol">⚡</span><br>
                                        & Power Solutions
                                    </h2>
                                    <p class="banner-description">
                                        Ultra-compact, lightning-fast 20W to 100W GaN chargers and power banks built to keep all your gadgets charged anywhere.
                                    </p>
                                    <div class="banner-action">
                                        <a href="#power-charging" class="btn-shop-now">SHOP NOW</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Carousel Pagination Dots (Capsule Pill matching Bongo Digital) -->
                    <div class="carousel-pagination banner-dots-pill" id="carouselDots" role="tablist" aria-label="Banner pagination">
                        <button class="dot-btn active" role="tab" aria-label="Slide 1" aria-selected="true" data-slide="0"></button>
                        <button class="dot-btn" role="tab" aria-label="Slide 2" aria-selected="false" data-slide="1"></button>
                        <button class="dot-btn" role="tab" aria-label="Slide 3" aria-selected="false" data-slide="2"></button>
                        <button class="dot-btn" role="tab" aria-label="Slide 4" aria-selected="false" data-slide="3"></button>
                    </div>

                    <!-- Desktop Nav Arrows -->
                    <button type="button" class="carousel-nav-btn prev js-carousel-prev desktop-only" aria-label="Previous Slide">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </button>
                    <button type="button" class="carousel-nav-btn next js-carousel-next desktop-only" aria-label="Next Slide">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                </section>

                <!-- =========================================================================
                     Trust & Features Bar (Continuous Scrolling Marquee)
                     ========================================================================= -->
                <section class="trust-features-bar" aria-label="Trust & Guarantees">
                    <div class="trust-marquee-track">
                        <!-- Set 1 -->
                        <div class="trust-marquee-group">
                            <div class="trust-feature-item">
                                <span class="trust-icon-box">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                        <polyline points="9 12 11 14 15 10"></polyline>
                                    </svg>
                                </span>
                                <span class="trust-text">Authentic & Genuine Product.</span>
                            </div>
                            <div class="trust-feature-item">
                                <span class="trust-icon-box">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                                        <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
                                    </svg>
                                </span>
                                <span class="trust-text">24/7 Support Always Be There for You</span>
                            </div>
                            <div class="trust-feature-item">
                                <span class="trust-icon-box">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                </span>
                                <span class="trust-text">Low Prices Than in Other Stores</span>
                            </div>
                            <div class="trust-feature-item">
                                <span class="trust-icon-box">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                        <line x1="1" y1="10" x2="23" y2="10"></line>
                                    </svg>
                                </span>
                                <span class="trust-text">Safe Payment With Multiple Method</span>
                            </div>
                        </div>

                        <!-- Set 2 (Duplicate for smooth infinite seamless loop) -->
                        <div class="trust-marquee-group" aria-hidden="true">
                            <div class="trust-feature-item">
                                <span class="trust-icon-box">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                        <polyline points="9 12 11 14 15 10"></polyline>
                                    </svg>
                                </span>
                                <span class="trust-text">Authentic & Genuine Product.</span>
                            </div>
                            <div class="trust-feature-item">
                                <span class="trust-icon-box">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                                        <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
                                    </svg>
                                </span>
                                <span class="trust-text">24/7 Support Always Be There for You</span>
                            </div>
                            <div class="trust-feature-item">
                                <span class="trust-icon-box">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                </span>
                                <span class="trust-text">Low Prices Than in Other Stores</span>
                            </div>
                            <div class="trust-feature-item">
                                <span class="trust-icon-box">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                        <line x1="1" y1="10" x2="23" y2="10"></line>
                                    </svg>
                                </span>
                                <span class="trust-text">Safe Payment With Multiple Method</span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- =========================================================================
                     Featured Categories Quick Access (Matching Screenshot)
                     ========================================================================= -->
                <section class="category-circle-slider" aria-label="Featured Categories">
                    <div class="circle-items-track">
                        <!-- 1. Audio & Sound -->
                        <a href="#audio-sound" class="circle-item">
                            <div class="circle-avatar-wrap">
                                <img src="https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=200&h=200&q=80" alt="Audio & Sound" class="circle-category-img" loading="lazy">
                            </div>
                            <span class="circle-item-name">Audio & Sound</span>
                        </a>

                        <!-- 2. Smartwatches & Accessories -->
                        <a href="#smartwatches" class="circle-item">
                            <div class="circle-avatar-wrap">
                                <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=200&h=200&q=80" alt="Smartwatches & Accessories" class="circle-category-img" loading="lazy">
                            </div>
                            <span class="circle-item-name">Smartwatches<br>& Accessories</span>
                        </a>

                        <!-- 3. Power & Charging Solutions -->
                        <a href="#power-charging" class="circle-item">
                            <div class="circle-avatar-wrap">
                                <img src="https://images.unsplash.com/photo-1583863788434-e58a36330cf0?auto=format&fit=crop&w=200&h=200&q=80" alt="Power & Charging Solutions" class="circle-category-img" loading="lazy">
                            </div>
                            <span class="circle-item-name">Power &<br>Charging<br>Solutions</span>
                        </a>

                        <!-- 4. Computer Peripherals -->
                        <a href="#computer-peripherals" class="circle-item">
                            <div class="circle-avatar-wrap">
                                <img src="https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=200&h=200&q=80" alt="Computer Peripherals" class="circle-category-img" loading="lazy">
                            </div>
                            <span class="circle-item-name">Computer<br>Peripherals</span>
                        </a>

                        <!-- 5. Networking & Smart Security -->
                        <a href="#networking-security" class="circle-item">
                            <div class="circle-avatar-wrap">
                                <img src="https://images.unsplash.com/photo-1557597774-9d273605dfa9?auto=format&fit=crop&w=200&h=200&q=80" alt="Networking & Smart Security" class="circle-category-img" loading="lazy">
                            </div>
                            <span class="circle-item-name">Networking &<br>Smart<br>Security</span>
                        </a>

                        <!-- 6. Lighting & Studio Gear -->
                        <a href="#lighting-studio" class="circle-item">
                            <div class="circle-avatar-wrap">
                                <img src="https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=200&h=200&q=80" alt="Lighting & Studio Gear" class="circle-category-img" loading="lazy">
                            </div>
                            <span class="circle-item-name">Lighting &<br>Studio Gear</span>
                        </a>

                        <!-- 7. Home & Kitchen -->
                        <a href="#home-kitchen" class="circle-item">
                            <div class="circle-avatar-wrap">
                                <img src="https://images.unsplash.com/photo-1584269600464-37b1b58a9fe7?auto=format&fit=crop&w=200&h=200&q=80" alt="Home & Kitchen" class="circle-category-img" loading="lazy">
                            </div>
                            <span class="circle-item-name">Home &<br>Kitchen</span>
                        </a>

                        <!-- 8. Mobile Accessories & Lifestyle -->
                        <a href="#mobile-accessories" class="circle-item">
                            <div class="circle-avatar-wrap">
                                <img src="https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb?auto=format&fit=crop&w=200&h=200&q=80" alt="Mobile Accessories & Lifestyle" class="circle-category-img" loading="lazy">
                            </div>
                            <span class="circle-item-name">Mobile<br>Accessories &<br>Lifestyle</span>
                        </a>

                        <!-- 9. Lifestyle -->
                        <a href="#lifestyle" class="circle-item">
                            <div class="circle-avatar-wrap">
                                <img src="https://images.unsplash.com/photo-1621607512214-68297480165e?auto=format&fit=crop&w=200&h=200&q=80" alt="Lifestyle" class="circle-category-img" loading="lazy">
                            </div>
                            <span class="circle-item-name">Lifestyle</span>
                        </a>

                        <!-- 10. Digital Goods -->
                        <a href="#digital-goods" class="circle-item">
                            <div class="circle-avatar-wrap">
                                <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=200&h=200&q=80" alt="Digital Goods" class="circle-category-img" loading="lazy">
                            </div>
                            <span class="circle-item-name">Digital Goods</span>
                        </a>
                    </div>
                </section>

                <!-- =========================================================================
                     Recently Viewed Products Section (Owl Carousel)
                     ========================================================================= -->
                <section class="recently-viewed-section" aria-label="Recently Viewed Products">
                    <div class="section-title-wrap">
                        <h2 class="section-heading">Recently Viewed</h2>
                    </div>

                    <div class="owl-carousel owl-theme recently-viewed-carousel" id="recentlyViewedCarousel">
                        <!-- Product 1: 600Mbps Dual Band -->
                        <div class="item">
                            <a href="#product-600mbps" class="recent-product-card">
                                <div class="recent-thumb-box">
                                    <img src="https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&w=160&h=160&q=80" alt="600Mbps Dual Band Wireless Adapter" class="recent-product-img" loading="lazy">
                                </div>
                                <div class="recent-product-info">
                                    <h3 class="recent-product-title">600Mbps Dual Band</h3>
                                    <div class="recent-rating-wrap">
                                        <span class="star-icons">★★★★★</span>
                                        <span class="review-count">(3)</span>
                                    </div>
                                    <div class="recent-price-wrap">
                                        <span class="recent-current-price">899.00৳</span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Product 2: Amazon Prime -->
                        <div class="item">
                            <a href="#product-amazon-prime" class="recent-product-card">
                                <div class="recent-thumb-box prime-thumb">
                                    <img src="https://images.unsplash.com/photo-1574375927938-d5a98e8ffe85?auto=format&fit=crop&w=160&h=160&q=80" alt="Amazon Prime Video" class="recent-product-img" loading="lazy">
                                </div>
                                <div class="recent-product-info">
                                    <h3 class="recent-product-title">Amazon Prime</h3>
                                    <div class="recent-rating-wrap">
                                        <span class="star-icons">★★★★★</span>
                                        <span class="review-count">(7)</span>
                                    </div>
                                    <div class="recent-price-wrap">
                                        <span class="recent-current-price">150.00৳ - 1,560.00৳</span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Product 3: Anime T-Shirt / Bundle -->
                        <div class="item">
                            <a href="#product-anime-bundle" class="recent-product-card">
                                <div class="recent-thumb-box">
                                    <img src="https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?auto=format&fit=crop&w=160&h=160&q=80" alt="Anime T-Shirt Design Bundle" class="recent-product-img" loading="lazy">
                                </div>
                                <div class="recent-product-info">
                                    <h3 class="recent-product-title">Anime T-Shirt</h3>
                                    <div class="recent-rating-wrap">
                                        <span class="star-icons">★★★★☆</span>
                                        <span class="review-count">(5)</span>
                                    </div>
                                    <div class="recent-price-wrap">
                                        <del class="recent-old-price">11,000.00৳</del>
                                        <span class="recent-current-price">210.00৳</span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Product 4: Anker 511 -->
                        <div class="item">
                            <a href="#product-anker-511" class="recent-product-card">
                                <div class="recent-thumb-box">
                                    <img src="https://images.unsplash.com/photo-1583863788434-e58a36330cf0?auto=format&fit=crop&w=160&h=160&q=80" alt="Anker 511 Nano Pro Charger" class="recent-product-img" loading="lazy">
                                </div>
                                <div class="recent-product-info">
                                    <h3 class="recent-product-title">Anker 511</h3>
                                    <div class="recent-rating-wrap">
                                        <span class="star-icons">★★★★★</span>
                                        <span class="review-count">(4)</span>
                                    </div>
                                    <div class="recent-price-wrap">
                                        <span class="recent-current-price">1,399.00৳</span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Product 5: Apple ID (US) -->
                        <div class="item">
                            <a href="#product-apple-id" class="recent-product-card">
                                <div class="recent-thumb-box">
                                    <img src="https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?auto=format&fit=crop&w=160&h=160&q=80" alt="Apple ID US Region" class="recent-product-img" loading="lazy">
                                </div>
                                <div class="recent-product-info">
                                    <h3 class="recent-product-title">Apple ID (US)</h3>
                                    <div class="recent-rating-wrap">
                                        <span class="star-icons">★★★★★</span>
                                        <span class="review-count">(3)</span>
                                    </div>
                                    <div class="recent-price-wrap">
                                        <span class="recent-current-price">1,530.00৳</span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Product 6: Canva Pro -->
                        <div class="item">
                            <a href="#product-canva-pro" class="recent-product-card">
                                <div class="recent-thumb-box">
                                    <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=160&h=160&q=80" alt="Canva Pro Subscription" class="recent-product-img" loading="lazy">
                                </div>
                                <div class="recent-product-info">
                                    <h3 class="recent-product-title">Canva Pro</h3>
                                    <div class="recent-rating-wrap">
                                        <span class="star-icons">★★★★★</span>
                                        <span class="review-count">(18)</span>
                                    </div>
                                    <div class="recent-price-wrap">
                                        <del class="recent-old-price">1,200.00৳</del>
                                        <span class="recent-current-price">299.00৳</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </section>

                <!-- =========================================================================
                     Promotional Product Banner (WF-1000XM3 - Matching Screenshot)
                     ========================================================================= -->
                <section class="promo-banner-section" aria-label="Special Product Promotion">
                    <div class="promo-banner-card">
                        <!-- Left Info Column -->
                        <div class="promo-content">
                            <span class="promo-badge">WF-1000XM3</span>
                            <h2 class="promo-title">
                                শব্দ যেখানে নিখুঁত, সুর সেখানে জীবন্ত!
                            </h2>
                            <p class="promo-subtitle">
                                —হারিয়ে যান এক অন্যরকম অনুভূতির দুনিয়ায়।
                            </p>
                            <div class="promo-action">
                                <a href="#product-wf1000xm3" class="btn-promo-get">Get Now</a>
                            </div>
                        </div>

                        <!-- Center / Product Earbuds Showcase -->
                        <div class="promo-product-visual">
                            <div class="promo-earbud-item earbud-top">
                                <img src="https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=400&q=80" alt="Sony WF-1000XM3 Earbud" class="earbud-img">
                            </div>
                            <div class="promo-earbud-item earbud-bottom">
                                <img src="https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=400&q=80" alt="Sony WF-1000XM3 Earbud" class="earbud-img">
                            </div>
                        </div>

                        <!-- Right Lifestyle Backdrop Visual -->
                        <div class="promo-backdrop-wrap">
                            <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=1200&q=80" alt="Music Lifestyle" class="promo-bg-img" loading="lazy">
                            <div class="promo-gradient-mask"></div>
                        </div>
                    </div>
                </section>

                <!-- =========================================================================
                     Featured Brands Carousel (Owl Carousel - 6 on PC, 3 on Mobile, 3s Autoplay)
                     ========================================================================= -->
                <section class="brands-carousel-section" aria-label="Featured Brands">
                    <div class="owl-carousel owl-theme brands-carousel" id="brandsCarousel">

                        <!-- Brand 1: UGREEN -->
                        <div class="item">
                            <a href="#brand-ugreen" class="brand-item-card" title="UGREEN">
                                <div class="brand-logo-wrap brand-ugreen">
                                    <span class="brand-badge-pill ugreen-pill">UGREEN</span>
                                </div>
                            </a>
                        </div>

                        <!-- Brand 2: Ulanzi -->
                        <div class="item">
                            <a href="#brand-ulanzi" class="brand-item-card" title="Ulanzi">
                                <div class="brand-logo-wrap brand-ulanzi">
                                    <span class="ulanzi-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="#e11d48" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="9"></circle>
                                            <path d="M8 12a4 4 0 1 0 8 0 4 4 0 1 0-8 0"></path>
                                        </svg>
                                    </span>
                                    <span class="ulanzi-text">Ulanzi</span>
                                </div>
                            </a>
                        </div>

                        <!-- Brand 3: bongo digital -->
                        <div class="item">
                            <a href="#brand-bongo" class="brand-item-card" title="Bongo Digital">
                                <div class="brand-logo-wrap brand-bongo">
                                    <span class="bongo-icon">b</span>
                                    <span class="bongo-text">bongo digital</span>
                                </div>
                            </a>
                        </div>

                        <!-- Brand 4: Xiaomi -->
                        <div class="item">
                            <a href="#brand-xiaomi" class="brand-item-card" title="Xiaomi">
                                <div class="brand-logo-wrap brand-xiaomi">
                                    <span class="mi-icon">mi</span>
                                    <span class="mi-text">xiaomi</span>
                                </div>
                            </a>
                        </div>

                        <!-- Brand 5: YES -->
                        <div class="item">
                            <a href="#brand-yes" class="brand-item-card" title="YES">
                                <div class="brand-logo-wrap brand-yes">
                                    <span class="yes-check">✓</span>
                                    <span class="yes-text">ES</span>
                                </div>
                            </a>
                        </div>

                        <!-- Brand 6: COLMI -->
                        <div class="item">
                            <a href="#brand-colmi" class="brand-item-card" title="COLMI">
                                <div class="brand-logo-wrap brand-colmi">
                                    <span class="colmi-icon">C</span>
                                    <span class="colmi-text">COLMI</span>
                                </div>
                            </a>
                        </div>

                        <!-- Brand 7: Anker -->
                        <div class="item">
                            <a href="#brand-anker" class="brand-item-card" title="ANKER">
                                <div class="brand-logo-wrap brand-anker">
                                    <span class="anker-text">ANKER</span>
                                </div>
                            </a>
                        </div>

                        <!-- Brand 8: Baseus -->
                        <div class="item">
                            <a href="#brand-baseus" class="brand-item-card" title="Baseus">
                                <div class="brand-logo-wrap brand-baseus">
                                    <span class="baseus-text">Baseus</span>
                                </div>
                            </a>
                        </div>

                        <!-- Brand 9: Sony -->
                        <div class="item">
                            <a href="#brand-sony" class="brand-item-card" title="SONY">
                                <div class="brand-logo-wrap brand-sony">
                                    <span class="sony-text">SONY</span>
                                </div>
                            </a>
                        </div>

                        <!-- Brand 10: Apple -->
                        <div class="item">
                            <a href="#brand-apple" class="brand-item-card" title="Apple">
                                <div class="brand-logo-wrap brand-apple">
                                    <svg viewBox="0 0 170 170" width="18" height="18" fill="currentColor">
                                        <path d="M150.37 130.25c-2.45 5.66-5.35 10.87-8.71 15.66-4.58 6.53-8.33 11.05-11.22 13.56-4.48 4.12-9.28 6.23-14.42 6.35-3.69 0-8.14-1.05-13.32-3.18-5.19-2.12-9.97-3.17-14.34-3.17-4.58 0-9.49 1.05-14.75 3.17-5.26 2.13-9.5 3.24-12.74 3.35-4.35.13-9.16-1.9-14.42-6.08-3.69-3.04-7.69-7.85-12-14.42-6.09-9.35-10.88-20.08-14.38-32.18-3.5-12.1-5.25-23.23-5.25-33.39 0-14.35 3.75-26.04 11.25-35.09 7.5-9.05 16.89-13.67 28.17-13.85 4.9 0 10.42 1.25 16.57 3.75 6.15 2.5 10.05 3.8 11.69 3.9 1.42 0 5.48-1.35 12.18-4.05 6.7-2.7 12.44-3.9 17.22-3.6 12.75.65 22.84 5.3 30.29 13.95-10.87 6.64-16.18 15.77-15.93 27.39.26 9.14 3.76 16.8 10.51 22.99 6.75 6.19 14.88 9.71 24.39 10.56-2.61 7.84-5.88 15.68-9.8 23.53zM119.22 31.84c0-7.39 2.65-14.28 7.95-20.67 5.3-6.39 11.75-10.15 19.34-11.27.22 1.3.33 2.5.33 3.6 0 7.39-2.77 14.43-8.31 21.11-5.54 6.68-12.28 10.58-20.21 11.69-.33-1.52-.5-2.82-.5-3.91z"/>
                                    </svg>
                                    <span class="apple-text">Apple</span>
                                </div>
                            </a>
                        </div>

                    </div>
                </section>

                <!-- =========================================================================
                     New Arrival Products Section (Owl Carousel - Matching Screenshot)
                     ========================================================================= -->
                <section class="new-arrival-section" aria-label="New Arrival Products">
                    <div class="section-header-flex">
                        <div class="section-title-wrap">
                            <h2 class="section-heading">
                                <span class="title-sparkle-icon">✨</span> New Arrival
                            </h2>
                        </div>
                        <a href="#all-products" class="view-all-link">All Products</a>
                    </div>

                    <div class="owl-carousel owl-theme product-grid-carousel" id="newArrivalCarousel">
                        <!-- Product 1: TP-Link Archer -->
                        <div class="item">
                            <div class="arrival-product-card">
                                <a href="#product-details" class="arrival-thumb-box">
                                    <img src="https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&w=400&q=80" alt="TP-Link Archer" class="arrival-product-img" loading="lazy">
                                    <div class="card-support-badge">
                                        <span class="badge-brand">d</span>
                                        <span class="badge-text">24/7 Support</span>
                                    </div>
                                </a>
                                <div class="arrival-product-body">
                                    <div class="arrival-title-rating">
                                        <h3 class="arrival-product-title"><a href="#product-details">TP-Link Archer</a></h3>
                                        <div class="arrival-rating">
                                            <span class="rating-num">4.7</span>
                                            <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                            <span class="rating-count">(3)</span>
                                        </div>
                                    </div>
                                    <a href="#category" class="arrival-category-sub">Networking & Security</a>
                                    <div class="arrival-price-wrap">
                                        <span class="arrival-price">3,590.00৳</span>
                                    </div>
                                    <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 2: BD-PON Mini -->
                        <div class="item">
                            <div class="arrival-product-card">
                                <a href="#product-details" class="arrival-thumb-box">
                                    <span class="discount-badge">-15%</span>
                                    <img src="https://images.unsplash.com/photo-1583863788434-e58a36330cf0?auto=format&fit=crop&w=400&q=80" alt="BD-PON Mini" class="arrival-product-img" loading="lazy">
                                    <div class="card-support-badge">
                                        <span class="badge-brand">d</span>
                                        <span class="badge-text">24/7 Support</span>
                                    </div>
                                </a>
                                <div class="arrival-product-body">
                                    <div class="arrival-title-rating">
                                        <h3 class="arrival-product-title"><a href="#product-details">BD-PON Mini</a></h3>
                                        <div class="arrival-rating">
                                            <span class="rating-num">4.6</span>
                                            <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                            <span class="rating-count">(5)</span>
                                        </div>
                                    </div>
                                    <a href="#category" class="arrival-category-sub">Gadget & Power Solutions</a>
                                    <div class="arrival-price-wrap">
                                        <del class="arrival-old-price">2,000.00৳</del>
                                        <span class="arrival-price">1,699.00৳</span>
                                    </div>
                                    <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 3: Ulanzi VIJIM- -->
                        <div class="item">
                            <div class="arrival-product-card">
                                <a href="#product-details" class="arrival-thumb-box">
                                    <img src="https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=400&q=80" alt="Ulanzi VIJIM" class="arrival-product-img" loading="lazy">
                                    <div class="card-support-badge">
                                        <span class="badge-brand">d</span>
                                        <span class="badge-text">24/7 Support</span>
                                    </div>
                                </a>
                                <div class="arrival-product-body">
                                    <div class="arrival-title-rating">
                                        <h3 class="arrival-product-title"><a href="#product-details">Ulanzi VIJIM-</a></h3>
                                        <div class="arrival-rating">
                                            <span class="rating-num">4.3</span>
                                            <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                            <span class="rating-count">(4)</span>
                                        </div>
                                    </div>
                                    <a href="#category" class="arrival-category-sub">Studio & Lighting Gear</a>
                                    <div class="arrival-price-wrap">
                                        <span class="arrival-price">2,500.00৳</span>
                                    </div>
                                    <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 4: Sony WF- -->
                        <div class="item">
                            <div class="arrival-product-card">
                                <a href="#product-details" class="arrival-thumb-box">
                                    <img src="https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=400&q=80" alt="Sony WF" class="arrival-product-img" loading="lazy">
                                    <div class="card-support-badge">
                                        <span class="badge-brand">d</span>
                                        <span class="badge-text">24/7 Support</span>
                                    </div>
                                </a>
                                <div class="arrival-product-body">
                                    <div class="arrival-title-rating">
                                        <h3 class="arrival-product-title"><a href="#product-details">Sony WF-</a></h3>
                                        <div class="arrival-rating">
                                            <span class="rating-num">4.3</span>
                                            <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                            <span class="rating-count">(6)</span>
                                        </div>
                                    </div>
                                    <a href="#category" class="arrival-category-sub">Audio & Sound</a>
                                    <div class="arrival-price-wrap">
                                        <span class="arrival-price">11,899.00৳</span>
                                    </div>
                                    <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 5: BYZ S623 -->
                        <div class="item">
                            <div class="arrival-product-card">
                                <a href="#product-details" class="arrival-thumb-box">
                                    <img src="https://images.unsplash.com/photo-1606220588913-b3aacb4d2f46?auto=format&fit=crop&w=400&q=80" alt="BYZ S623" class="arrival-product-img" loading="lazy">
                                    <div class="card-support-badge">
                                        <span class="badge-brand">d</span>
                                        <span class="badge-text">24/7 Support</span>
                                    </div>
                                </a>
                                <div class="arrival-product-body">
                                    <div class="arrival-title-rating">
                                        <h3 class="arrival-product-title"><a href="#product-details">BYZ S623</a></h3>
                                        <div class="arrival-rating">
                                            <span class="rating-num">4.4</span>
                                            <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                            <span class="rating-count">(5)</span>
                                        </div>
                                    </div>
                                    <a href="#category" class="arrival-category-sub">Audio & Sound</a>
                                    <div class="arrival-price-wrap">
                                        <span class="arrival-price">1,250.00৳</span>
                                    </div>
                                    <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 6: Magnetic Mobile Holder -->
                        <div class="item">
                            <div class="arrival-product-card">
                                <a href="#product-details" class="arrival-thumb-box">
                                    <img src="https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb?auto=format&fit=crop&w=400&q=80" alt="Magnetic Holder" class="arrival-product-img" loading="lazy">
                                    <div class="card-support-badge">
                                        <span class="badge-brand">d</span>
                                        <span class="badge-text">24/7 Support</span>
                                    </div>
                                </a>
                                <div class="arrival-product-body">
                                    <div class="arrival-title-rating">
                                        <h3 class="arrival-product-title"><a href="#product-details">Magnetic</a></h3>
                                        <div class="arrival-rating">
                                            <span class="rating-num">4.8</span>
                                            <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                            <span class="rating-count">(11)</span>
                                        </div>
                                    </div>
                                    <a href="#category" class="arrival-category-sub">Mobile Accessories</a>
                                    <div class="arrival-price-wrap">
                                        <span class="arrival-price">699.00৳</span>
                                    </div>
                                    <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 7: Anker 511 Nano Pro -->
                        <div class="item">
                            <div class="arrival-product-card">
                                <a href="#product-details" class="arrival-thumb-box">
                                    <img src="https://images.unsplash.com/photo-1583863788434-e58a36330cf0?auto=format&fit=crop&w=400&q=80" alt="Anker 511" class="arrival-product-img" loading="lazy">
                                    <div class="card-support-badge">
                                        <span class="badge-brand">d</span>
                                        <span class="badge-text">24/7 Support</span>
                                    </div>
                                </a>
                                <div class="arrival-product-body">
                                    <div class="arrival-title-rating">
                                        <h3 class="arrival-product-title"><a href="#product-details">Anker 511</a></h3>
                                        <div class="arrival-rating">
                                            <span class="rating-num">4.9</span>
                                            <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                            <span class="rating-count">(9)</span>
                                        </div>
                                    </div>
                                    <a href="#category" class="arrival-category-sub">Charging & Power</a>
                                    <div class="arrival-price-wrap">
                                        <span class="arrival-price">1,399.00৳</span>
                                    </div>
                                    <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- =========================================================================
                     Tri-Banner Promo Grid (Feature Promo Cards - Smartwatch, Earbuds, Mic)
                     ========================================================================= -->
                <section class="tri-promo-section" aria-label="Featured Product Highlights">
                    <div class="tri-promo-grid">
                        <!-- Card 1: Next level adventure (Smartwatch) -->
                        <div class="tri-promo-card card-light">
                            <div class="tri-promo-img-wrap">
                                <img src="{{ asset('assets/images/promos/promo_smartwatch.png') }}" alt="Next level adventure - Smartwatch" class="tri-promo-img" loading="lazy">
                            </div>
                            <div class="tri-promo-body">
                                <h3 class="tri-promo-title">Next level adventure</h3>
                                <p class="tri-promo-desc">
                                    Elevate your adventure through the perfect fusion of military-grade toughness and striking silver aesthetics.
                                </p>
                                <a href="#smartwatch-deals" class="btn-tri-shop">Shop Now</a>
                            </div>
                        </div>

                        <!-- Card 2: Dare To Leap (Earbuds - Dark Theme) -->
                        <div class="tri-promo-card card-dark">
                            <div class="tri-promo-img-wrap">
                                <img src="{{ asset('assets/images/promos/promo_earbuds.png') }}" alt="Dare To Leap - Dual Tone Earbuds" class="tri-promo-img" loading="lazy">
                            </div>
                            <div class="tri-promo-body">
                                <h3 class="tri-promo-title">Dare To Leap</h3>
                                <p class="tri-promo-desc">
                                    Experience the ultimate blend of striking aesthetics and deep audio immersion with its premium dual-tone design.
                                </p>
                                <a href="#earbuds-deals" class="btn-tri-shop">Shop Now</a>
                            </div>
                        </div>

                        <!-- Card 3: Stream Like A Pro (USB/RGB Mic) -->
                        <div class="tri-promo-card card-light">
                            <div class="tri-promo-img-wrap">
                                <img src="{{ asset('assets/images/promos/promo_mic.png') }}" alt="Stream Like A Pro - Studio Microphone" class="tri-promo-img" loading="lazy">
                            </div>
                            <div class="tri-promo-body">
                                <h3 class="tri-promo-title">Stream Like A Pro</h3>
                                <p class="tri-promo-desc">
                                    Level up your stream with real-time game/chat mixing, dual-mic noise cancellation, and dynamic RGB.
                                </p>
                                <a href="#streaming-deals" class="btn-tri-shop">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </section>



                <!-- =========================================================================
                     Dual Promo Banner Grid (Mounting Stand & Portable Fan)
                     ========================================================================= -->
                <section class="dual-promo-section" aria-label="Special Product Offers">
                    <div class="dual-promo-grid">
                        <!-- Banner 1: Heavy-Duty Dual Device Mounting -->
                        <a href="#mounting-offers" class="dual-promo-card banner-mounting">
                            <div class="dual-promo-content">
                                <h3 class="dual-promo-title">Heavy-Duty Dual<br>Device Mounting</h3>
                                <div class="dual-promo-offer-box">
                                    <span>Get 10% OFF; Promo code:</span>
                                    <span class="promo-code-pill">ULNJI2026M</span>
                                </div>
                                <p class="dual-promo-disclaimer">*Not combined with promotional offers and discounts</p>
                            </div>
                            <div class="dual-promo-visual">
                                <img src="{{ asset('assets/images/promos/visual_mounting.png') }}" alt="Heavy-Duty Dual Device Mounting" class="dual-promo-visual-img" loading="lazy">
                            </div>
                        </a>

                        <!-- Banner 2: Stay Cool In Style -->
                        <a href="#fan-offers" class="dual-promo-card banner-fancool">
                            <div class="dual-promo-content">
                                <h3 class="dual-promo-title">Stay Cool In<br>Style</h3>
                                <p class="dual-promo-desc">
                                    Stay effortlessly cool with an ultra-compact 40,000 RPM turbo airflow.
                                </p>
                            </div>
                            <div class="dual-promo-visual">
                                <img src="{{ asset('assets/images/promos/visual_fancool.png') }}" alt="Stay Cool In Style - Turbo Airflow Fan" class="dual-promo-visual-img" loading="lazy">
                            </div>
                        </a>
                    </div>
                </section>





                <!-- =========================================================================
                     Most Sold Products Section (Owl Carousel - Matching Screenshot)
                     ========================================================================= -->
                <section class="most-sold-section" aria-label="Most Sold Products">
                    <div class="section-header-flex">
                        <div class="section-title-wrap">
                            <h2 class="section-heading">
                                <span class="title-sparkle-icon">🏆</span> Most Sold!
                            </h2>
                        </div>
                        <a href="#all-products" class="view-all-link">All Products</a>
                    </div>

                    <div class="owl-carousel owl-theme product-grid-carousel" id="mostSoldCarousel">
                        <!-- Product 1: Remax Watch 9 -->
                        <div class="item">
                            <div class="arrival-product-card">
                                <a href="#product-details" class="arrival-thumb-box">
                                    <img src="{{ asset('assets/images/products/sold_remax_watch9.png') }}" alt="Remax Watch 9" class="arrival-product-img" loading="lazy">
                                    <div class="card-support-badge">
                                        <span class="badge-brand">d</span>
                                        <span class="badge-text">24/7 Support</span>
                                    </div>
                                </a>
                                <div class="arrival-product-body">
                                    <div class="arrival-title-rating">
                                        <h3 class="arrival-product-title"><a href="#product-details">Remax Watch 9</a></h3>
                                        <div class="arrival-rating">
                                            <span class="rating-num">4.3</span>
                                            <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                            <span class="rating-count">(3)</span>
                                        </div>
                                    </div>
                                    <a href="#category" class="arrival-category-sub">Smartwatches & Accessories</a>
                                    <div class="arrival-price-wrap">
                                        <span class="arrival-price">3,299.00৳</span>
                                    </div>
                                    <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 2: TP-Link Deco E4 -->
                        <div class="item">
                            <div class="arrival-product-card">
                                <a href="#product-details" class="arrival-thumb-box">
                                    <img src="{{ asset('assets/images/products/sold_tplink_deco.png') }}" alt="TP-Link Deco E4" class="arrival-product-img" loading="lazy">
                                    <div class="card-support-badge">
                                        <span class="badge-brand">d</span>
                                        <span class="badge-text">24/7 Support</span>
                                    </div>
                                </a>
                                <div class="arrival-product-body">
                                    <div class="arrival-title-rating">
                                        <h3 class="arrival-product-title"><a href="#product-details">TP-Link Deco E4</a></h3>
                                        <div class="arrival-rating">
                                            <span class="rating-num">4.5</span>
                                            <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                            <span class="rating-count">(4)</span>
                                        </div>
                                    </div>
                                    <a href="#category" class="arrival-category-sub">Gadget & Electronics, Networking & Security</a>
                                    <div class="arrival-price-wrap">
                                        <span class="arrival-price">2,950.00৳ - 8,550.00৳</span>
                                    </div>
                                    <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 3: BYZ S623 -->
                        <div class="item">
                            <div class="arrival-product-card">
                                <a href="#product-details" class="arrival-thumb-box">
                                    <img src="{{ asset('assets/images/products/sold_byz_s623.png') }}" alt="BYZ S623" class="arrival-product-img" loading="lazy">
                                    <div class="card-support-badge">
                                        <span class="badge-brand">d</span>
                                        <span class="badge-text">24/7 Support</span>
                                    </div>
                                </a>
                                <div class="arrival-product-body">
                                    <div class="arrival-title-rating">
                                        <h3 class="arrival-product-title"><a href="#product-details">BYZ S623</a></h3>
                                        <div class="arrival-rating">
                                            <span class="rating-num">4.4</span>
                                            <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                            <span class="rating-count">(5)</span>
                                        </div>
                                    </div>
                                    <a href="#category" class="arrival-category-sub">Audio & Sound</a>
                                    <div class="arrival-price-wrap">
                                        <span class="arrival-price">1,250.00৳</span>
                                    </div>
                                    <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 4: 600Mbps Dual -->
                        <div class="item">
                            <div class="arrival-product-card">
                                <a href="#product-details" class="arrival-thumb-box">
                                    <span class="discount-badge discount-green">NEW</span>
                                    <img src="{{ asset('assets/images/products/sold_600mbps_dual.png') }}" alt="600Mbps Dual" class="arrival-product-img" loading="lazy">
                                    <div class="card-support-badge">
                                        <span class="badge-brand">d</span>
                                        <span class="badge-text">24/7 Support</span>
                                    </div>
                                </a>
                                <div class="arrival-product-body">
                                    <div class="arrival-title-rating">
                                        <h3 class="arrival-product-title"><a href="#product-details">600Mbps Dual</a></h3>
                                        <div class="arrival-rating">
                                            <span class="rating-num">5.0</span>
                                            <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                            <span class="rating-count">(3)</span>
                                        </div>
                                    </div>
                                    <a href="#category" class="arrival-category-sub">Gadget & Electronics</a>
                                    <div class="arrival-price-wrap">
                                        <span class="arrival-price">899.00৳</span>
                                    </div>
                                    <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 5: JOYROOM JR-FC2 -->
                        <div class="item">
                            <div class="arrival-product-card">
                                <a href="#product-details" class="arrival-thumb-box">
                                    <img src="{{ asset('assets/images/products/sold_joyroom_watch.png') }}" alt="JOYROOM JR-FC2" class="arrival-product-img" loading="lazy">
                                    <div class="card-support-badge">
                                        <span class="badge-brand">d</span>
                                        <span class="badge-text">24/7 Support</span>
                                    </div>
                                </a>
                                <div class="arrival-product-body">
                                    <div class="arrival-title-rating">
                                        <h3 class="arrival-product-title"><a href="#product-details">JOYROOM JR-FC2</a></h3>
                                        <div class="arrival-rating">
                                            <span class="rating-num">4.3</span>
                                            <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                            <span class="rating-count">(4)</span>
                                        </div>
                                    </div>
                                    <a href="#category" class="arrival-category-sub">Smartwatches & Accessories</a>
                                    <div class="arrival-price-wrap">
                                        <span class="arrival-price">3,550.00৳</span>
                                    </div>
                                    <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 6: MiLi Smart Find -->
                        <div class="item">
                            <div class="arrival-product-card">
                                <a href="#product-details" class="arrival-thumb-box">
                                    <img src="{{ asset('assets/images/products/sold_mili_tracker.png') }}" alt="MiLi Smart Find" class="arrival-product-img" loading="lazy">
                                    <div class="card-support-badge">
                                        <span class="badge-brand">d</span>
                                        <span class="badge-text">24/7 Support</span>
                                    </div>
                                </a>
                                <div class="arrival-product-body">
                                    <div class="arrival-title-rating">
                                        <h3 class="arrival-product-title"><a href="#product-details">MiLi Smart Find</a></h3>
                                        <div class="arrival-rating">
                                            <span class="rating-num">4.8</span>
                                            <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                            <span class="rating-count">(5)</span>
                                        </div>
                                    </div>
                                    <a href="#category" class="arrival-category-sub">Gadget & Electronics, Networking & Security</a>
                                    <div class="arrival-price-wrap">
                                        <span class="arrival-price">799.00৳ - 1,480.00৳</span>
                                    </div>
                                    <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                                </div>
                            </div>
                        </div>

                        <!-- Product 7: Anker 511 -->
                        <div class="item">
                            <div class="arrival-product-card">
                                <a href="#product-details" class="arrival-thumb-box">
                                    <img src="https://images.unsplash.com/photo-1583863788434-e58a36330cf0?auto=format&fit=crop&w=400&q=80" alt="Anker 511" class="arrival-product-img" loading="lazy">
                                    <div class="card-support-badge">
                                        <span class="badge-brand">d</span>
                                        <span class="badge-text">24/7 Support</span>
                                    </div>
                                </a>
                                <div class="arrival-product-body">
                                    <div class="arrival-title-rating">
                                        <h3 class="arrival-product-title"><a href="#product-details">Anker 511</a></h3>
                                        <div class="arrival-rating">
                                            <span class="rating-num">4.9</span>
                                            <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                            <span class="rating-count">(9)</span>
                                        </div>
                                    </div>
                                    <a href="#category" class="arrival-category-sub">Charging & Power</a>
                                    <div class="arrival-price-wrap">
                                        <span class="arrival-price">1,399.00৳</span>
                                    </div>
                                    <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- =========================================================================
                     Digital Items & Software Deals Section (Blue Vibe Showcase)
                     ========================================================================= -->
                <section class="digital-deals-section" aria-label="Digital Items & Software Deals">
                    <div class="digital-deals-card">
                        <!-- Left Call to Action Column -->
                        <div class="digital-deals-cta">
                            <h2 class="digital-deals-title">
                                Get special deals on digital items
                            </h2>
                            <a href="#digital-goods" class="btn-buy-now">Buy Now</a>
                            <div class="digital-boxes-visual">
                                <img src="{{ asset('assets/images/digital/software_boxes_transparent.png') }}" alt="Software Packages - Windows 11 Pro, Avast Security, IDM" class="digital-boxes-img" loading="lazy">
                            </div>
                        </div>

                        <!-- Right Products Slider Column -->
                        <div class="digital-products-slider">
                            <div class="owl-carousel owl-theme digital-deals-carousel" id="digitalDealsCarousel">
                                <!-- Product 1: Amazon Prime -->
                                <div class="item">
                                    <div class="arrival-product-card">
                                        <a href="#product-details" class="arrival-thumb-box">
                                            <img src="{{ asset('assets/images/digital/digital_prime.png') }}" alt="Amazon Prime Video" class="arrival-product-img" loading="lazy">
                                            <div class="card-support-badge">
                                                <span class="badge-brand">d</span>
                                                <span class="badge-text">24/7 Support</span>
                                            </div>
                                        </a>
                                        <div class="arrival-product-body">
                                            <div class="arrival-title-rating">
                                                <h3 class="arrival-product-title"><a href="#product-details">Amazon Prime</a></h3>
                                                <div class="arrival-rating">
                                                    <span class="rating-num">4.6</span>
                                                    <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                                    <span class="rating-count">(7)</span>
                                                </div>
                                            </div>
                                            <a href="#category" class="arrival-category-sub">Digital, Entertainment & Streaming, Subscription Services</a>
                                            <div class="arrival-price-wrap">
                                                <span class="arrival-price">150.00৳ - 1,560.00৳</span>
                                            </div>
                                            <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product 2: Microsoft 365 -->
                                <div class="item">
                                    <div class="arrival-product-card">
                                        <a href="#product-details" class="arrival-thumb-box">
                                            <img src="{{ asset('assets/images/digital/digital_office365.png') }}" alt="Microsoft 365 Personal" class="arrival-product-img" loading="lazy">
                                            <div class="card-support-badge">
                                                <span class="badge-brand">d</span>
                                                <span class="badge-text">24/7 Support</span>
                                            </div>
                                        </a>
                                        <div class="arrival-product-body">
                                            <div class="arrival-title-rating">
                                                <h3 class="arrival-product-title"><a href="#product-details">Microsoft 365</a></h3>
                                                <div class="arrival-rating">
                                                    <span class="rating-num">4.5</span>
                                                    <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                                    <span class="rating-count">(4)</span>
                                                </div>
                                            </div>
                                            <a href="#category" class="arrival-category-sub">Digital, Office & Productivity, Subscription Services</a>
                                            <div class="arrival-price-wrap">
                                                <span class="arrival-price">310.00৳ - 2,600.00৳</span>
                                            </div>
                                            <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product 3: Windows Digital -->
                                <div class="item">
                                    <div class="arrival-product-card">
                                        <a href="#product-details" class="arrival-thumb-box">
                                            <span class="discount-badge">-94%</span>
                                            <img src="{{ asset('assets/images/digital/digital_windows.png') }}" alt="Windows Digital License" class="arrival-product-img" loading="lazy">
                                            <div class="card-support-badge">
                                                <span class="badge-brand">d</span>
                                                <span class="badge-text">24/7 Support</span>
                                            </div>
                                        </a>
                                        <div class="arrival-product-body">
                                            <div class="arrival-title-rating">
                                                <h3 class="arrival-product-title"><a href="#product-details">Windows Digital</a></h3>
                                                <div class="arrival-rating">
                                                    <span class="rating-num">4.7</span>
                                                    <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                                    <span class="rating-count">(3)</span>
                                                </div>
                                            </div>
                                            <a href="#category" class="arrival-category-sub">Operating Systems, Perpetual License</a>
                                            <div class="arrival-price-wrap">
                                                <span class="arrival-price">780.00৳ - 890.00৳</span>
                                            </div>
                                            <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product 4: Canva Pro -->
                                <div class="item">
                                    <div class="arrival-product-card">
                                        <a href="#product-details" class="arrival-thumb-box">
                                            <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=400&q=80" alt="Canva Pro Subscription" class="arrival-product-img" loading="lazy">
                                            <div class="card-support-badge">
                                                <span class="badge-brand">d</span>
                                                <span class="badge-text">24/7 Support</span>
                                            </div>
                                        </a>
                                        <div class="arrival-product-body">
                                            <div class="arrival-title-rating">
                                                <h3 class="arrival-product-title"><a href="#product-details">Canva Pro</a></h3>
                                                <div class="arrival-rating">
                                                    <span class="rating-num">4.9</span>
                                                    <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                                    <span class="rating-count">(18)</span>
                                                </div>
                                            </div>
                                            <a href="#category" class="arrival-category-sub">Digital, Design & Productivity, Subscription</a>
                                            <div class="arrival-price-wrap">
                                                <del class="arrival-old-price">1,200.00৳</del>
                                                <span class="arrival-price">299.00৳</span>
                                            </div>
                                            <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>


                <!-- =========================================================================
                     Explore The World Of Digital Goods Section (Matching Screenshot)
                     ========================================================================= -->
                <section class="digital-goods-catalog-section" aria-label="Explore Digital Goods">
                    <div class="section-header-flex">
                        <div class="section-title-wrap">
                            <h2 class="section-heading">
                                <span class="title-sparkle-icon">💻</span> Explore the world of digital goods
                            </h2>
                        </div>
                        <a href="#all-digital-goods" class="view-all-link">All Products</a>
                    </div>

                    <div class="digital-goods-grid" id="digitalGoodsGrid">
                        <!-- Row 1: Product 1 - Claude AI Pro & Max -->
                        <div class="arrival-product-card">
                            <a href="#product-details" class="arrival-thumb-box">
                                <img src="{{ asset('assets/images/digital/prod_claude_ai.png') }}" alt="Claude AI Pro & Max" class="arrival-product-img" loading="lazy">
                                <div class="card-support-badge">
                                    <span class="badge-brand">d</span>
                                    <span class="badge-text">24/7 Support</span>
                                </div>
                            </a>
                            <div class="arrival-product-body">
                                <div class="arrival-title-rating">
                                    <h3 class="arrival-product-title"><a href="#product-details">Claude AI Pro & Max</a></h3>
                                    <div class="arrival-rating">
                                        <span class="rating-num">4.8</span>
                                        <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                        <span class="rating-count">(5)</span>
                                    </div>
                                </div>
                                <a href="#category" class="arrival-category-sub">Artificial intelligence (AI), Subscription Services</a>
                                <div class="arrival-price-wrap">
                                    <span class="arrival-price">3,200.00৳ - 32,000.00৳</span>
                                </div>
                                <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                            </div>
                        </div>

                        <!-- Row 1: Product 2 - Elementor Pro -->
                        <div class="arrival-product-card">
                            <a href="#product-details" class="arrival-thumb-box">
                                <img src="{{ asset('assets/images/digital/prod_elementor_pro.png') }}" alt="Elementor Pro" class="arrival-product-img" loading="lazy">
                                <div class="card-support-badge">
                                    <span class="badge-brand">d</span>
                                    <span class="badge-text">24/7 Support</span>
                                </div>
                            </a>
                            <div class="arrival-product-body">
                                <div class="arrival-title-rating">
                                    <h3 class="arrival-product-title"><a href="#product-details">Elementor Pro</a></h3>
                                    <div class="arrival-rating">
                                        <span class="rating-num">4.3</span>
                                        <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                        <span class="rating-count">(3)</span>
                                    </div>
                                </div>
                                <a href="#category" class="arrival-category-sub">Subscription Services, Themes & Plugins</a>
                                <div class="arrival-price-wrap">
                                    <span class="arrival-price">1,020.00৳</span>
                                </div>
                                <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                            </div>
                        </div>

                        <!-- Row 1: Product 3 - Remini Premium -->
                        <div class="arrival-product-card">
                            <a href="#product-details" class="arrival-thumb-box">
                                <img src="{{ asset('assets/images/digital/prod_remini_pro.png') }}" alt="Remini Premium" class="arrival-product-img" loading="lazy">
                                <div class="card-support-badge">
                                    <span class="badge-brand">d</span>
                                    <span class="badge-text">24/7 Support</span>
                                </div>
                            </a>
                            <div class="arrival-product-body">
                                <div class="arrival-title-rating">
                                    <h3 class="arrival-product-title"><a href="#product-details">Remini Premium</a></h3>
                                    <div class="arrival-rating">
                                        <span class="rating-num">4.5</span>
                                        <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                        <span class="rating-count">(4)</span>
                                    </div>
                                </div>
                                <a href="#category" class="arrival-category-sub">Artificial intelligence (AI), Subscription Services</a>
                                <div class="arrival-price-wrap">
                                    <span class="arrival-price">1,500.00৳ - 13,500.00৳</span>
                                </div>
                                <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                            </div>
                        </div>

                        <!-- Row 1: Product 4 - Spotify Premium -->
                        <div class="arrival-product-card">
                            <a href="#product-details" class="arrival-thumb-box">
                                <img src="{{ asset('assets/images/digital/prod_spotify_premium.png') }}" alt="Spotify Premium" class="arrival-product-img" loading="lazy">
                                <div class="card-support-badge">
                                    <span class="badge-brand">d</span>
                                    <span class="badge-text">24/7 Support</span>
                                </div>
                            </a>
                            <div class="arrival-product-body">
                                <div class="arrival-title-rating">
                                    <h3 class="arrival-product-title"><a href="#product-details">Spotify Premium</a></h3>
                                    <div class="arrival-rating">
                                        <span class="rating-num">4.0</span>
                                        <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                        <span class="rating-count">(3)</span>
                                    </div>
                                </div>
                                <a href="#category" class="arrival-category-sub">Entertainment & Streaming, Subscription</a>
                                <div class="arrival-price-wrap">
                                    <span class="arrival-price">205.00৳ - 2,040.00৳</span>
                                </div>
                                <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                            </div>
                        </div>

                        <!-- Row 1: Product 5 - FreeFire MAX -->
                        <div class="arrival-product-card">
                            <a href="#product-details" class="arrival-thumb-box">
                                <span class="discount-badge discount-green">NEW</span>
                                <img src="{{ asset('assets/images/digital/prod_freefire_max.png') }}" alt="FreeFire MAX" class="arrival-product-img" loading="lazy">
                                <div class="card-support-badge">
                                    <span class="badge-brand">d</span>
                                    <span class="badge-text">24/7 Support</span>
                                </div>
                            </a>
                            <div class="arrival-product-body">
                                <div class="arrival-title-rating">
                                    <h3 class="arrival-product-title"><a href="#product-details">FreeFire MAX</a></h3>
                                    <div class="arrival-rating">
                                        <span class="rating-num">4.0</span>
                                        <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                        <span class="rating-count">(3)</span>
                                    </div>
                                </div>
                                <a href="#category" class="arrival-category-sub">Game & Gift Card</a>
                                <div class="arrival-price-wrap">
                                    <span class="arrival-price">380.00৳ - 1,880.00৳</span>
                                </div>
                                <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                            </div>
                        </div>

                        <!-- Row 2: Product 6 - Amazon Prime -->
                        <div class="arrival-product-card">
                            <a href="#product-details" class="arrival-thumb-box">
                                <img src="{{ asset('assets/images/digital/digital_prime.png') }}" alt="Amazon Prime Video" class="arrival-product-img" loading="lazy">
                                <div class="card-support-badge">
                                    <span class="badge-brand">d</span>
                                    <span class="badge-text">24/7 Support</span>
                                </div>
                            </a>
                            <div class="arrival-product-body">
                                <div class="arrival-title-rating">
                                    <h3 class="arrival-product-title"><a href="#product-details">Amazon Prime</a></h3>
                                    <div class="arrival-rating">
                                        <span class="rating-num">4.6</span>
                                        <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                        <span class="rating-count">(7)</span>
                                    </div>
                                </div>
                                <a href="#category" class="arrival-category-sub">Digital, Entertainment & Streaming</a>
                                <div class="arrival-price-wrap">
                                    <span class="arrival-price">150.00৳ - 1,560.00৳</span>
                                </div>
                                <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                            </div>
                        </div>

                        <!-- Row 2: Product 7 - Microsoft 365 -->
                        <div class="arrival-product-card">
                            <a href="#product-details" class="arrival-thumb-box">
                                <img src="{{ asset('assets/images/digital/digital_office365.png') }}" alt="Microsoft 365 Personal" class="arrival-product-img" loading="lazy">
                                <div class="card-support-badge">
                                    <span class="badge-brand">d</span>
                                    <span class="badge-text">24/7 Support</span>
                                </div>
                            </a>
                            <div class="arrival-product-body">
                                <div class="arrival-title-rating">
                                    <h3 class="arrival-product-title"><a href="#product-details">Microsoft 365</a></h3>
                                    <div class="arrival-rating">
                                        <span class="rating-num">4.5</span>
                                        <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                        <span class="rating-count">(4)</span>
                                    </div>
                                </div>
                                <a href="#category" class="arrival-category-sub">Digital, Office & Productivity</a>
                                <div class="arrival-price-wrap">
                                    <span class="arrival-price">310.00৳ - 2,600.00৳</span>
                                </div>
                                <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                            </div>
                        </div>

                        <!-- Row 2: Product 8 - Windows Digital -->
                        <div class="arrival-product-card">
                            <a href="#product-details" class="arrival-thumb-box">
                                <span class="discount-badge">-94%</span>
                                <img src="{{ asset('assets/images/digital/digital_windows.png') }}" alt="Windows Digital License" class="arrival-product-img" loading="lazy">
                                <div class="card-support-badge">
                                    <span class="badge-brand">d</span>
                                    <span class="badge-text">24/7 Support</span>
                                </div>
                            </a>
                            <div class="arrival-product-body">
                                <div class="arrival-title-rating">
                                    <h3 class="arrival-product-title"><a href="#product-details">Windows Digital</a></h3>
                                    <div class="arrival-rating">
                                        <span class="rating-num">4.7</span>
                                        <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                        <span class="rating-count">(3)</span>
                                    </div>
                                </div>
                                <a href="#category" class="arrival-category-sub">Operating Systems, Perpetual License</a>
                                <div class="arrival-price-wrap">
                                    <span class="arrival-price">780.00৳ - 890.00৳</span>
                                </div>
                                <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                            </div>
                        </div>

                        <!-- Row 2: Product 9 - Canva Pro -->
                        <div class="arrival-product-card">
                            <a href="#product-details" class="arrival-thumb-box">
                                <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=400&q=80" alt="Canva Pro Subscription" class="arrival-product-img" loading="lazy">
                                <div class="card-support-badge">
                                    <span class="badge-brand">d</span>
                                    <span class="badge-text">24/7 Support</span>
                                </div>
                            </a>
                            <div class="arrival-product-body">
                                <div class="arrival-title-rating">
                                    <h3 class="arrival-product-title"><a href="#product-details">Canva Pro</a></h3>
                                    <div class="arrival-rating">
                                        <span class="rating-num">4.9</span>
                                        <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                        <span class="rating-count">(18)</span>
                                    </div>
                                </div>
                                <a href="#category" class="arrival-category-sub">Digital, Design & Productivity</a>
                                <div class="arrival-price-wrap">
                                    <del class="arrival-old-price">1,200.00৳</del>
                                    <span class="arrival-price">299.00৳</span>
                                </div>
                                <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                            </div>
                        </div>

                        <!-- Row 2: Product 10 - ChatGPT Plus -->
                        <div class="arrival-product-card">
                            <a href="#product-details" class="arrival-thumb-box">
                                <img src="https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&w=400&q=80" alt="ChatGPT Plus GPT-4o" class="arrival-product-img" loading="lazy">
                                <div class="card-support-badge">
                                    <span class="badge-brand">d</span>
                                    <span class="badge-text">24/7 Support</span>
                                </div>
                            </a>
                            <div class="arrival-product-body">
                                <div class="arrival-title-rating">
                                    <h3 class="arrival-product-title"><a href="#product-details">ChatGPT Plus (GPT-4o)</a></h3>
                                    <div class="arrival-rating">
                                        <span class="rating-num">4.9</span>
                                        <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                        <span class="rating-count">(12)</span>
                                    </div>
                                </div>
                                <a href="#category" class="arrival-category-sub">Artificial intelligence (AI), Subscription Services</a>
                                <div class="arrival-price-wrap">
                                    <span class="arrival-price">2,450.00৳ - 24,000.00৳</span>
                                </div>
                                <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                            </div>
                        </div>

                        <!-- Row 3: Product 11 - Perplexity Pro AI -->
                        <div class="arrival-product-card">
                            <a href="#product-details" class="arrival-thumb-box">
                                <img src="https://images.unsplash.com/photo-1620712943543-bcc4688e7485?auto=format&fit=crop&w=400&q=80" alt="Perplexity Pro AI" class="arrival-product-img" loading="lazy">
                                <div class="card-support-badge">
                                    <span class="badge-brand">d</span>
                                    <span class="badge-text">24/7 Support</span>
                                </div>
                            </a>
                            <div class="arrival-product-body">
                                <div class="arrival-title-rating">
                                    <h3 class="arrival-product-title"><a href="#product-details">Perplexity Pro AI</a></h3>
                                    <div class="arrival-rating">
                                        <span class="rating-num">4.8</span>
                                        <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                        <span class="rating-count">(8)</span>
                                    </div>
                                </div>
                                <a href="#category" class="arrival-category-sub">Artificial intelligence (AI), Search & Research</a>
                                <div class="arrival-price-wrap">
                                    <span class="arrival-price">1,850.00৳ - 18,500.00৳</span>
                                </div>
                                <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                            </div>
                        </div>

                        <!-- Row 3: Product 12 - Midjourney Standard -->
                        <div class="arrival-product-card">
                            <a href="#product-details" class="arrival-thumb-box">
                                <img src="https://images.unsplash.com/photo-1579783902614-a3fb3927b675?auto=format&fit=crop&w=400&q=80" alt="Midjourney AI Standard" class="arrival-product-img" loading="lazy">
                                <div class="card-support-badge">
                                    <span class="badge-brand">d</span>
                                    <span class="badge-text">24/7 Support</span>
                                </div>
                            </a>
                            <div class="arrival-product-body">
                                <div class="arrival-title-rating">
                                    <h3 class="arrival-product-title"><a href="#product-details">Midjourney AI</a></h3>
                                    <div class="arrival-rating">
                                        <span class="rating-num">4.7</span>
                                        <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                        <span class="rating-count">(6)</span>
                                    </div>
                                </div>
                                <a href="#category" class="arrival-category-sub">Artificial intelligence (AI), Generative Art</a>
                                <div class="arrival-price-wrap">
                                    <span class="arrival-price">3,500.00৳ - 35,000.00৳</span>
                                </div>
                                <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                            </div>
                        </div>

                        <!-- Row 3: Product 13 - Apple ID (US Region) -->
                        <div class="arrival-product-card">
                            <a href="#product-details" class="arrival-thumb-box">
                                <img src="https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?auto=format&fit=crop&w=400&q=80" alt="Apple ID US Region" class="arrival-product-img" loading="lazy">
                                <div class="card-support-badge">
                                    <span class="badge-brand">d</span>
                                    <span class="badge-text">24/7 Support</span>
                                </div>
                            </a>
                            <div class="arrival-product-body">
                                <div class="arrival-title-rating">
                                    <h3 class="arrival-product-title"><a href="#product-details">Apple ID (US)</a></h3>
                                    <div class="arrival-rating">
                                        <span class="rating-num">5.0</span>
                                        <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                        <span class="rating-count">(9)</span>
                                    </div>
                                </div>
                                <a href="#category" class="arrival-category-sub">Game & Gift Card, Apple Services</a>
                                <div class="arrival-price-wrap">
                                    <span class="arrival-price">1,530.00৳</span>
                                </div>
                                <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                            </div>
                        </div>

                        <!-- Row 3: Product 14 - Crunchyroll Mega Fan -->
                        <div class="arrival-product-card">
                            <a href="#product-details" class="arrival-thumb-box">
                                <img src="https://images.unsplash.com/photo-1578632767115-351597cf2477?auto=format&fit=crop&w=400&q=80" alt="Crunchyroll Mega Fan" class="arrival-product-img" loading="lazy">
                                <div class="card-support-badge">
                                    <span class="badge-brand">d</span>
                                    <span class="badge-text">24/7 Support</span>
                                </div>
                            </a>
                            <div class="arrival-product-body">
                                <div class="arrival-title-rating">
                                    <h3 class="arrival-product-title"><a href="#product-details">Crunchyroll Fan</a></h3>
                                    <div class="arrival-rating">
                                        <span class="rating-num">4.6</span>
                                        <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                        <span class="rating-count">(4)</span>
                                    </div>
                                </div>
                                <a href="#category" class="arrival-category-sub">Entertainment & Streaming, Anime</a>
                                <div class="arrival-price-wrap">
                                    <span class="arrival-price">450.00৳ - 4,200.00৳</span>
                                </div>
                                <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                            </div>
                        </div>

                        <!-- Row 3: Product 15 - YouTube Premium -->
                        <div class="arrival-product-card">
                            <a href="#product-details" class="arrival-thumb-box">
                                <img src="https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&w=400&q=80" alt="YouTube Premium Subscription" class="arrival-product-img" loading="lazy">
                                <div class="card-support-badge">
                                    <span class="badge-brand">d</span>
                                    <span class="badge-text">24/7 Support</span>
                                </div>
                            </a>
                            <div class="arrival-product-body">
                                <div class="arrival-title-rating">
                                    <h3 class="arrival-product-title"><a href="#product-details">YouTube Premium</a></h3>
                                    <div class="arrival-rating">
                                        <span class="rating-num">4.9</span>
                                        <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                        <span class="rating-count">(15)</span>
                                    </div>
                                </div>
                                <a href="#category" class="arrival-category-sub">Entertainment & Streaming, Ad-Free</a>
                                <div class="arrival-price-wrap">
                                    <span class="arrival-price">280.00৳ - 2,800.00৳</span>
                                </div>
                                <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                            </div>
                        </div>

                        <!-- Row 4: Product 16 - PUBG Mobile UC -->
                        <div class="arrival-product-card">
                            <a href="#product-details" class="arrival-thumb-box">
                                <span class="discount-badge discount-green">NEW</span>
                                <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=400&q=80" alt="PUBG Mobile UC Global" class="arrival-product-img" loading="lazy">
                                <div class="card-support-badge">
                                    <span class="badge-brand">d</span>
                                    <span class="badge-text">24/7 Support</span>
                                </div>
                            </a>
                            <div class="arrival-product-body">
                                <div class="arrival-title-rating">
                                    <h3 class="arrival-product-title"><a href="#product-details">PUBG Mobile UC</a></h3>
                                    <div class="arrival-rating">
                                        <span class="rating-num">4.8</span>
                                        <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                        <span class="rating-count">(11)</span>
                                    </div>
                                </div>
                                <a href="#category" class="arrival-category-sub">Game & Gift Card, In-Game Currency</a>
                                <div class="arrival-price-wrap">
                                    <span class="arrival-price">420.00৳ - 8,500.00৳</span>
                                </div>
                                <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                            </div>
                        </div>

                        <!-- Row 4: Product 17 - Surfshark VPN -->
                        <div class="arrival-product-card">
                            <a href="#product-details" class="arrival-thumb-box">
                                <span class="discount-badge">-65%</span>
                                <img src="https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&w=400&q=80" alt="Surfshark VPN Premium" class="arrival-product-img" loading="lazy">
                                <div class="card-support-badge">
                                    <span class="badge-brand">d</span>
                                    <span class="badge-text">24/7 Support</span>
                                </div>
                            </a>
                            <div class="arrival-product-body">
                                <div class="arrival-title-rating">
                                    <h3 class="arrival-product-title"><a href="#product-details">Surfshark VPN</a></h3>
                                    <div class="arrival-rating">
                                        <span class="rating-num">4.7</span>
                                        <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                        <span class="rating-count">(5)</span>
                                    </div>
                                </div>
                                <a href="#category" class="arrival-category-sub">Security & Privacy, Virtual Private Network</a>
                                <div class="arrival-price-wrap">
                                    <span class="arrival-price">650.00৳ - 3,900.00৳</span>
                                </div>
                                <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                            </div>
                        </div>

                        <!-- Row 4: Product 18 - Grammarly Premium -->
                        <div class="arrival-product-card">
                            <a href="#product-details" class="arrival-thumb-box">
                                <img src="https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=400&q=80" alt="Grammarly Premium" class="arrival-product-img" loading="lazy">
                                <div class="card-support-badge">
                                    <span class="badge-brand">d</span>
                                    <span class="badge-text">24/7 Support</span>
                                </div>
                            </a>
                            <div class="arrival-product-body">
                                <div class="arrival-title-rating">
                                    <h3 class="arrival-product-title"><a href="#product-details">Grammarly Premium</a></h3>
                                    <div class="arrival-rating">
                                        <span class="rating-num">4.8</span>
                                        <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                        <span class="rating-count">(7)</span>
                                    </div>
                                </div>
                                <a href="#category" class="arrival-category-sub">Digital, Writing & Productivity</a>
                                <div class="arrival-price-wrap">
                                    <span class="arrival-price">499.00৳ - 4,800.00৳</span>
                                </div>
                                <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                            </div>
                        </div>

                        <!-- Row 4: Product 19 - Discord Nitro -->
                        <div class="arrival-product-card">
                            <a href="#product-details" class="arrival-thumb-box">
                                <img src="https://images.unsplash.com/photo-1614680376593-902f749f7ffc?auto=format&fit=crop&w=400&q=80" alt="Discord Nitro Boost" class="arrival-product-img" loading="lazy">
                                <div class="card-support-badge">
                                    <span class="badge-brand">d</span>
                                    <span class="badge-text">24/7 Support</span>
                                </div>
                            </a>
                            <div class="arrival-product-body">
                                <div class="arrival-title-rating">
                                    <h3 class="arrival-product-title"><a href="#product-details">Discord Nitro Boost</a></h3>
                                    <div class="arrival-rating">
                                        <span class="rating-num">4.9</span>
                                        <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                        <span class="rating-count">(10)</span>
                                    </div>
                                </div>
                                <a href="#category" class="arrival-category-sub">Game & Gift Card, Chat & Streaming</a>
                                <div class="arrival-price-wrap">
                                    <span class="arrival-price">1,150.00৳ - 11,000.00৳</span>
                                </div>
                                <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                            </div>
                        </div>

                        <!-- Row 4: Product 20 - Adobe All Apps CC -->
                        <div class="arrival-product-card">
                            <a href="#product-details" class="arrival-thumb-box">
                                <img src="https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?auto=format&fit=crop&w=400&q=80" alt="Adobe Creative Cloud All Apps" class="arrival-product-img" loading="lazy">
                                <div class="card-support-badge">
                                    <span class="badge-brand">d</span>
                                    <span class="badge-text">24/7 Support</span>
                                </div>
                            </a>
                            <div class="arrival-product-body">
                                <div class="arrival-title-rating">
                                    <h3 class="arrival-product-title"><a href="#product-details">Adobe All Apps CC</a></h3>
                                    <div class="arrival-rating">
                                        <span class="rating-num">4.9</span>
                                        <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                        <span class="rating-count">(8)</span>
                                    </div>
                                </div>
                                <a href="#category" class="arrival-category-sub">Design & Creativity, Subscription</a>
                                <div class="arrival-price-wrap">
                                    <span class="arrival-price">4,500.00৳ - 45,000.00৳</span>
                                </div>
                                <a href="#cart" class="btn-add-cart"><i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span></a>
                            </div>
                        </div>
                    </div>
                </section>



                <!-- =========================================================================
                     Remini AI Image Editing Tool Promotional Banner (Matching Screenshot)
                     ========================================================================= -->
                <section class="remini-promo-section" aria-label="Remini AI Image Editing Tool">
                    <div class="remini-banner-card">
                        <!-- Left Content Info -->
                        <div class="remini-content-col">
                            <span class="remini-badge">AI Image Editing Tool</span>
                            <h2 class="remini-title">
                                এক ক্লিকেই আপনার ঝাপসা ছবিকে<br>করে তুলুন<br>
                                <span class="remini-highlight">ক্রিস্টাল ক্লিয়ার!</span>
                            </h2>
                            <div class="remini-action">
                                <a href="#remini-pro" class="btn-remini-get">Get Now</a>
                            </div>
                        </div>

                        <!-- Center Remini App Icon Badge -->
                        <div class="remini-app-badge">
                            <div class="remini-icon-frame">
                                <svg viewBox="0 0 100 100" fill="none" class="remini-svg-frame">
                                    <rect x="22" y="24" width="56" height="46" rx="4" stroke="#fbcfe8" stroke-width="5.5" fill="none" transform="rotate(-6 50 47)"/>
                                    <path d="M57 32L59.5 38.5L66 41L59.5 43.5L57 50L54.5 43.5L48 41L54.5 38.5Z" fill="#ffffff"/>
                                </svg>
                            </div>
                            <span class="remini-brand-text">Remini</span>
                        </div>

                        <!-- Right Visual Crystal Clear Landscape Comparison -->
                        <div class="remini-visual-col">
                            <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1000&q=80" alt="Crystal Clear Lake Landscape" class="remini-landscape-img" loading="lazy">
                            <div class="remini-slider-handle" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </div>
                    </div>
                </section>




            </div>
        </main>
    </div>

    <!-- =========================================================================
         Floating Contact Us Widget (Bottom Right)
         ========================================================================= -->

    <!-- =========================================================================
         Site Footer (Black Background - Matching Reference Structure)
         ========================================================================= -->
    <footer class="site-footer" aria-label="Site Footer">
        <div class="footer-container">
            <div class="footer-grid">
                <!-- Column 1: Brand Info -->
                <div class="footer-col brand-col">
                    <a href="{{ url('/') }}" class="footer-brand-logo">
                        <span class="logo-icon">d</span>
                        <span class="logo-text">digigo<span class="text-domain">.com.bd</span></span>
                    </a>
                    <p class="footer-brand-desc">
                        We specialize in providing exclusively authentic digital products, gadgets and strategic solutions to help turn your ambitious ideas into reality.
                    </p>
                </div>

                <!-- Column 2: Popular Categories / Policies -->
                <div class="footer-col">
                    <h3 class="footer-col-title">Popular Categories</h3>
                    <ul class="footer-links-list">
                        <li><a href="#privacy-policy">Privacy Policy</a></li>
                        <li><a href="#refund-policy">Refund Policy</a></li>
                        <li><a href="#delivery-policy">Delivery Policy</a></li>
                        <li><a href="#support-faq">Support & FAQ</a></li>
                    </ul>
                </div>

                <!-- Column 3: Useful Links -->
                <div class="footer-col">
                    <h3 class="footer-col-title">Useful Links</h3>
                    <ul class="footer-links-list">
                        <li><a href="#about-us">About Us</a></li>
                        <li><a href="#contact-us">Contact Us</a></li>
                        <li><a href="#corporate-partnership">Corporate Partnership</a></li>
                        <li><a href="#career">Career</a></li>
                    </ul>
                </div>

                <!-- Column 4: App Downloads & Social Links -->
                <div class="footer-col apps-social-col">
                    <h3 class="footer-col-title">Available On:</h3>
                    <div class="app-download-badges">
                        <!-- Google Play -->
                        <a href="#google-play" class="store-badge-btn" title="Get it on Google Play">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="store-svg-icon"><path d="M3.609 1.814L13.793 12 3.61 22.186c-.352-.365-.558-.87-.558-1.428V3.242c0-.558.206-1.063.557-1.428zM15.207 13.414l2.586 2.586-11.897 6.84 9.31-9.426zm0-2.828L5.897 1.16 17.793 8l-2.586 2.586zm1.414 1.414l3.772-2.176c.725-.418.725-1.1 0-1.518L16.621 12z"/></svg>
                            <div class="store-badge-text">
                                <span class="badge-sub">GET IT ON</span>
                                <span class="badge-main">Google Play</span>
                            </div>
                        </a>
                        <!-- App Store -->
                        <a href="#app-store" class="store-badge-btn" title="Download on the App Store">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="store-svg-icon"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 6.37c.61-.75 1.04-1.8 0.92-2.85-.92.04-2.02.62-2.67 1.37-.58.66-1.1 1.73-.96 2.76 1.02.08 2.08-.53 2.71-1.28"/></svg>
                            <div class="store-badge-text">
                                <span class="badge-sub">Download on the</span>
                                <span class="badge-main">App Store</span>
                            </div>
                        </a>
                    </div>

                    <h3 class="footer-col-title social-title">Social links:</h3>
                    <div class="footer-social-links">
                        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="social-icon-circle fb" title="Facebook">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="social-icon-circle insta" title="Instagram">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="https://whatsapp.com" target="_blank" rel="noopener noreferrer" class="social-icon-circle wa" title="WhatsApp">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.969.587 1.761.889 2.796.889 3.182 0 5.768-2.587 5.769-5.766.001-3.182-2.585-5.776-5.769-5.776zm3.385 8.213c-.14.394-.712.729-1.009.776-.282.045-.635.074-1.802-.408-1.493-.618-2.457-2.138-2.531-2.237-.074-.099-.607-.808-.607-1.543s.385-1.096.522-1.246c.137-.15.299-.187.399-.187.1 0 .2.001.288.006.092.005.215-.035.337.257.126.301.431 1.05.469 1.127.038.077.063.167.013.267-.05.1-.075.162-.15.25-.075.088-.158.196-.226.264-.075.075-.153.157-.066.307.088.15.39 1.02.836 1.417.575.512 1.059.67 1.209.745.15.075.238.063.326-.038.088-.1.376-.438.476-.588.1-.15.2-.125.338-.075.138.05.876.413 1.026.488.15.075.25.112.288.175.038.063.038.363-.102.757z"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright & Payment Badges -->
            <div class="footer-bottom-bar">
                <p class="footer-copyright">
                    <strong>DigiGo</strong> Copyright 2018-2026 <strong>All Right Reserved.</strong>
                </p>
                <div class="footer-payment-badges">
                    <span class="pay-badge bkash">bKash</span>
                    <span class="pay-badge nagad">নগদ</span>
                    <span class="pay-badge rocket">rocket</span>
                    <span class="pay-badge upay">upay</span>
                    <span class="pay-badge card">VISA / Master</span>
                </div>
            </div>
        </div>
    </footer>


    <aside class="floating-contact-widget" aria-label="Customer Contact Support">
        <a href="https://wa.me/8801600793325" target="_blank" rel="noopener noreferrer" class="contact-bubble-btn" aria-label="Chat with Support">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                <line x1="8" y1="9" x2="16" y2="9"></line>
                <line x1="8" y1="13" x2="14" y2="13"></line>
            </svg>
        </a>
    </aside>

    <!-- =========================================================================
         Mobile Fixed Bottom Navigation Bar (Matching Mobile Screenshot 1)
         ========================================================================= -->
    <nav class="mobile-bottom-nav" aria-label="Mobile Navigation">
        <button type="button" class="mobile-nav-item js-sidebar-toggle">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
            <span>Menu</span>
        </button>

        <a href="#wishlist" class="mobile-nav-item">
            <div class="nav-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
                <span class="nav-badge">0</span>
            </div>
            <span>Wishlist</span>
        </a>

        <a href="#cart" class="mobile-nav-item">
            <div class="nav-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <span class="nav-badge">1</span>
            </div>
            <span>Cart</span>
        </a>

        <a href="#account" class="mobile-nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <span>My account</span>
        </a>
    </nav>

    <!-- jQuery & Owl Carousel 2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <!-- Custom Vanilla JavaScript -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>
