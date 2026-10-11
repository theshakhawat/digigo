@extends('layouts.site')

@section('title', 'DigiGo - Digital Products, Software & Subscriptions')

@section('content')
<div class="layout-container">


        <!-- Main Content Area with Banner Slider & Mobile Features -->
        <main class="main-content">
            <div class="banner-wrapper">

                <!-- Hero Banner Carousel (Mobile & Desktop - Controlled via Admin Panel) -->
                @php
                    $carouselBanners = isset($banners) && $banners->isNotEmpty() ? $banners : \App\Models\Banner::active()->orderBy('order', 'asc')->get();
                @endphp
                <section class="hero-carousel"
                         id="heroCarousel"
                         aria-label="Featured Promotions"
                         data-autoplay="{{ setting('banner_autoplay', '1') === '1' ? 'true' : 'false' }}"
                         data-delay="{{ setting('banner_delay', '4000') }}">
                    <div class="carousel-track">
                        @forelse($carouselBanners as $index => $banner)
                            <!-- Slide {{ $index + 1 }}: {{ $banner->title }} -->
                            <div class="carousel-slide {{ $loop->first ? 'active' : '' }}">
                                <div class="banner-card">
                                    <img src="{{ $banner->image_url }}"
                                         alt="{{ $banner->title }}"
                                         class="banner-cover-img"
                                         loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                                    <div class="banner-content">
                                        <h2 class="banner-title">
                                            {!! nl2br(e($banner->title)) !!}
                                        </h2>
                                        @if($banner->subtitle)
                                            <p class="banner-description">
                                                {{ $banner->subtitle }}
                                            </p>
                                        @endif
                                        <div class="banner-action">
                                            <a href="{{ $banner->button_link }}" class="btn-shop-now">
                                                {{ $banner->button_text ?? 'SHOP NOW' }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <!-- Fallback Slide if no banners are configured -->
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
                        @endforelse
                    </div>

                    <!-- Carousel Pagination Dots (Capsule Pill matching Bongo Digital) -->
                    @if(setting('banner_dots', '1') === '1' && count($carouselBanners) > 1)
                        <div class="carousel-pagination banner-dots-pill" id="carouselDots" role="tablist" aria-label="Banner pagination">
                            @foreach($carouselBanners as $index => $banner)
                                <button class="dot-btn {{ $loop->first ? 'active' : '' }}"
                                        role="tab"
                                        aria-label="Slide {{ $index + 1 }}"
                                        aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                        data-slide="{{ $index }}"></button>
                            @endforeach
                        </div>
                    @endif

                    <!-- Desktop Nav Arrows -->
                    @if(setting('banner_arrows', '1') === '1' && count($carouselBanners) > 1)
                        <button type="button" class="carousel-nav-btn prev js-carousel-prev desktop-only" aria-label="Previous Slide">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                        </button>
                        <button type="button" class="carousel-nav-btn next js-carousel-next desktop-only" aria-label="Next Slide">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </button>
                    @endif
                </section>

                <!-- =========================================================================
                     Trust & Features Bar (Continuous Scrolling Marquee)
                     ========================================================================= -->
                @php
                    $trustFeaturesEnabled = setting('trust_features_enabled', '1') === '1';
                    $trustFeaturesSpeed = (int) setting('trust_features_speed', 28);
                    $trustFeaturesList = $trustFeaturesEnabled ? trust_features() : [];
                @endphp

                @if($trustFeaturesEnabled && count($trustFeaturesList) > 0)
                    <section class="trust-features-bar" aria-label="Trust & Guarantees">
                        <div class="trust-marquee-track" style="animation-duration: {{ $trustFeaturesSpeed > 0 ? $trustFeaturesSpeed : 28 }}s;">
                            <!-- Set 1 -->
                            <div class="trust-marquee-group">
                                @foreach($trustFeaturesList as $feature)
                                    <div class="trust-feature-item">
                                        <span class="trust-icon-box" aria-hidden="true">
                                            @if(str_starts_with(trim($feature['icon'] ?? ''), '<svg'))
                                                {!! $feature['icon'] !!}
                                            @else
                                                <i class="{{ $feature['icon'] ?: 'fa-solid fa-circle-check' }}"></i>
                                            @endif
                                        </span>
                                        @if(!empty($feature['link']))
                                            <a href="{{ $feature['link'] }}" class="trust-text hover:text-brand-600 transition">{{ $feature['text'] }}</a>
                                        @else
                                            <span class="trust-text">{{ $feature['text'] }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Set 2 (Duplicate for smooth infinite seamless loop) -->
                            <div class="trust-marquee-group" aria-hidden="true">
                                @foreach($trustFeaturesList as $feature)
                                    <div class="trust-feature-item">
                                        <span class="trust-icon-box">
                                            @if(str_starts_with(trim($feature['icon'] ?? ''), '<svg'))
                                                {!! $feature['icon'] !!}
                                            @else
                                                <i class="{{ $feature['icon'] ?: 'fa-solid fa-circle-check' }}"></i>
                                            @endif
                                        </span>
                                        @if(!empty($feature['link']))
                                            <a href="{{ $feature['link'] }}" tabindex="-1" class="trust-text hover:text-brand-600 transition">{{ $feature['text'] }}</a>
                                        @else
                                            <span class="trust-text">{{ $feature['text'] }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif

                <!-- =========================================================================
                     Featured Categories Quick Access (Matching Screenshot)
                     ========================================================================= -->
                @php
                    $circleCategories = isset($featuredCategories) && $featuredCategories->isNotEmpty()
                        ? $featuredCategories
                        : \App\Models\Category::active()->where('is_featured', true)->take(10)->get();
                @endphp

                @if($circleCategories->isNotEmpty())
                    <section class="category-circle-slider" aria-label="Featured Categories">
                        <div class="circle-items-track">
                            @foreach($circleCategories as $cat)
                                <a href="{{ route('search', ['category' => $cat->slug]) }}" class="circle-item">
                                    <div class="circle-avatar-wrap">
                                        @if($cat->image_url)
                                            <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="circle-category-img" loading="lazy">
                                        @elseif($cat->image && str_starts_with($cat->image, 'http'))
                                            <img src="{{ $cat->image }}" alt="{{ $cat->name }}" class="circle-category-img" loading="lazy">
                                        @else
                                            <div class="w-full h-full rounded-full bg-slate-100 flex items-center justify-center text-brand-600 font-bold text-sm">
                                                <i class="fa-solid fa-shapes text-sm"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <span class="circle-item-name">{!! nl2br(e($cat->name)) !!}</span>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

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
@endsection
