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

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
        <div class="top-header">
            <div class="container top-header-inner">
                
                <!-- Left: Sidebar Toggle & Brand Logo -->
                <div class="header-left-group">
                    <button type="button" class="sidebar-toggle-btn js-sidebar-toggle" aria-label="Toggle Navigation Menu">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>

                    <a href="{{ url('/') }}" class="brand-logo" aria-label="DigiGo Homepage">
                        <div class="logo-icon">d</div>
                        <span class="brand-text">digigo<span class="text-primary">.com.bd</span></span>
                    </a>
                </div>

                <!-- Center: Desktop Search Bar -->
                <div class="header-search-box desktop-search-box">
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

                <!-- Right: Desktop Contact Information & Mobile Search Icon -->
                <div class="header-right-group">
                    <div class="header-contact-info">
                        <div class="contact-item">
                            <span class="contact-label">24 Support</span>
                            <a href="tel:+8809611678088" class="contact-value">+8809611678088</a>
                        </div>
                        <div class="contact-item">
                            <span class="contact-label">WhatsApp</span>
                            <a href="https://wa.me/8801600793325" target="_blank" rel="noopener noreferrer" class="contact-value">+8801600793325</a>
                        </div>
                    </div>

                    <!-- Mobile Search Trigger Button (As seen in Mobile Screenshot 1 & 4) -->
                    <button type="button" class="mobile-search-trigger js-search-modal-open" aria-label="Open Search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
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
        <!-- Left Mini Icon Rail (Desktop Only) -->
        <aside class="sidebar-icon-rail desktop-only" aria-label="Quick Category Access">
            <a href="#ai" class="rail-item" title="Artificial intelligence (AI)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"></path>
                </svg>
                <span class="rail-tooltip">AI Tools</span>
            </a>
            <a href="#games-gift-cards" class="rail-item" title="Game & Gift Card">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="6" width="20" height="12" rx="2"></rect>
                    <line x1="6" y1="12" x2="10" y2="12"></line>
                    <line x1="8" y1="10" x2="8" y2="14"></line>
                </svg>
                <span class="rail-tooltip">Games & Cards</span>
            </a>
            <a href="#cloud-storage" class="rail-item" title="Cloud Storage">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"></path>
                </svg>
                <span class="rail-tooltip">Cloud Storage</span>
            </a>
            <a href="#entertainment-streaming" class="rail-item" title="Entertainment & Streaming">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="4" width="20" height="15" rx="2"></rect>
                    <polygon points="10 8 16 11.5 10 15 10 8"></polygon>
                </svg>
                <span class="rail-tooltip">Streaming</span>
            </a>
            <a href="#mobile-apps" class="rail-item" title="Mobile Apps">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                    <line x1="12" y1="18" x2="12.01" y2="18"></line>
                </svg>
                <span class="rail-tooltip">Mobile Apps</span>
            </a>
            <a href="#office-productivity" class="rail-item" title="Office & Productivity">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                <span class="rail-tooltip">Office & Work</span>
            </a>
            <a href="#operating-systems" class="rail-item" title="Operating Systems">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                </svg>
                <span class="rail-tooltip">OS & Keys</span>
            </a>
        </aside>

        <!-- Main Content Area with Banner Slider & Mobile Features -->
        <main class="main-content">
            <div class="banner-wrapper">
                
                <!-- Hero Banner Carousel (Mobile & Desktop) -->
                <section class="hero-carousel" id="heroCarousel" aria-label="Featured Promotions">
                    <div class="carousel-track">
                        
                        <!-- Slide 1: Ultra-compact lightning-fast charging / Gadgets -->
                        <div class="carousel-slide active">
                            <div class="banner-card slide-bg-1">
                                <img src="https://images.unsplash.com/photo-1546868871-7041f2a55e12?auto=format&fit=crop&w=1600&q=80" alt="Ultra-compact 20W Fast Charging" class="banner-cover-img" loading="eager">
                                <div class="banner-overlay-gradient"></div>
                                <div class="banner-content">
                                    <h1 class="banner-title">
                                        Ultra-compact<br>
                                        lightning-fast 20W<br>
                                        3x Faster Charging <span class="highlight-symbol">⚡</span>
                                    </h1>
                                    <div class="banner-action">
                                        <a href="#shop" class="btn-shop-now">SHOP NOW</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 2: Premium AI Tools & Software Keys -->
                        <div class="carousel-slide">
                            <div class="banner-card slide-bg-2">
                                <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=1600&q=80" alt="Premium AI Tools & Software Keys" class="banner-cover-img" loading="lazy">
                                <div class="banner-overlay-gradient"></div>
                                <div class="banner-content">
                                    <span class="banner-badge">TOP RATED</span>
                                    <h2 class="banner-title">
                                        Premium AI Tools<br>
                                        & Software Keys<br>
                                        At Best Prices <span class="highlight-symbol">🚀</span>
                                    </h2>
                                    <div class="banner-action">
                                        <a href="#ai-tools" class="btn-shop-now">EXPLORE DEALS</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 3: Smart Devices & Audio Gadgets -->
                        <div class="carousel-slide">
                            <div class="banner-card slide-bg-3">
                                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=1600&q=80" alt="Smart Devices & Audio Gadgets" class="banner-cover-img" loading="lazy">
                                <div class="banner-overlay-gradient"></div>
                                <div class="banner-content">
                                    <span class="banner-badge">BEST SELLER</span>
                                    <h2 class="banner-title">
                                        Smart Audio &<br>
                                        Wearable Tech<br>
                                        Exclusive Deals <span class="highlight-symbol">🎧</span>
                                    </h2>
                                    <div class="banner-action">
                                        <a href="#audio-gadgets" class="btn-shop-now">ORDER NOW</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Carousel Pagination Dots (Middle bottom, slightly raised) -->
                    <div class="carousel-pagination" id="carouselDots" role="tablist" aria-label="Banner pagination">
                        <button class="dot-btn active" role="tab" aria-label="Slide 1" aria-selected="true" data-slide="0"></button>
                        <button class="dot-btn" role="tab" aria-label="Slide 2" aria-selected="false" data-slide="1"></button>
                        <button class="dot-btn" role="tab" aria-label="Slide 3" aria-selected="false" data-slide="2"></button>
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
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                        <polyline points="9 12 11 14 15 10"></polyline>
                                    </svg>
                                </span>
                                <span class="trust-text">Authentic & Genuine Product.</span>
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
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                        <polyline points="9 12 11 14 15 10"></polyline>
                                    </svg>
                                </span>
                                <span class="trust-text">Authentic & Genuine Product.</span>
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
                                <img src="https://images.unsplash.com/photo-1609592424359-57774e470876?auto=format&fit=crop&w=200&h=200&q=80" alt="Power & Charging Solutions" class="circle-category-img" loading="lazy">
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

            </div>
        </main>
    </div>

    <!-- =========================================================================
         Floating Contact Us Widget (Bottom Right)
         ========================================================================= -->
    <aside class="floating-contact-widget" aria-label="Customer Contact Support">
        <a href="https://wa.me/8801600793325" target="_blank" rel="noopener noreferrer" class="contact-pill-link">
            <span class="contact-pill-text">Contact us</span>
            <span class="contact-bubble-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    <line x1="8" y1="9" x2="16" y2="9"></line>
                    <line x1="8" y1="13" x2="14" y2="13"></line>
                </svg>
            </span>
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
