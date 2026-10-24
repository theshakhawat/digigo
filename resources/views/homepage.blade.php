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
                                    @php
                                        $hasLink = !empty(trim($feature['link'] ?? ''));
                                        $targetUrl = $hasLink ? url($feature['link']) : '#';
                                    @endphp
                                    @if($hasLink)
                                        <a href="{{ $targetUrl }}" class="trust-feature-item">
                                            <span class="trust-icon-box" aria-hidden="true">
                                                @if(str_starts_with(trim($feature['icon'] ?? ''), '<svg'))
                                                    {!! $feature['icon'] !!}
                                                @else
                                                    <i class="{{ $feature['icon'] ?: 'fa-solid fa-circle-check' }}"></i>
                                                @endif
                                            </span>
                                            <span class="trust-text">{{ $feature['text'] }}</span>
                                        </a>
                                    @else
                                        <div class="trust-feature-item">
                                            <span class="trust-icon-box" aria-hidden="true">
                                                @if(str_starts_with(trim($feature['icon'] ?? ''), '<svg'))
                                                    {!! $feature['icon'] !!}
                                                @else
                                                    <i class="{{ $feature['icon'] ?: 'fa-solid fa-circle-check' }}"></i>
                                                @endif
                                            </span>
                                            <span class="trust-text">{{ $feature['text'] }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Set 2 (Duplicate for smooth infinite seamless loop) -->
                            <div class="trust-marquee-group" aria-hidden="true">
                                @foreach($trustFeaturesList as $feature)
                                    @php
                                        $hasLink = !empty(trim($feature['link'] ?? ''));
                                        $targetUrl = $hasLink ? url($feature['link']) : '#';
                                    @endphp
                                    @if($hasLink)
                                        <a href="{{ $targetUrl }}" tabindex="-1" class="trust-feature-item">
                                            <span class="trust-icon-box" aria-hidden="true">
                                                @if(str_starts_with(trim($feature['icon'] ?? ''), '<svg'))
                                                    {!! $feature['icon'] !!}
                                                @else
                                                    <i class="{{ $feature['icon'] ?: 'fa-solid fa-circle-check' }}"></i>
                                                @endif
                                            </span>
                                            <span class="trust-text">{{ $feature['text'] }}</span>
                                        </a>
                                    @else
                                        <div class="trust-feature-item">
                                            <span class="trust-icon-box" aria-hidden="true">
                                                @if(str_starts_with(trim($feature['icon'] ?? ''), '<svg'))
                                                    {!! $feature['icon'] !!}
                                                @else
                                                    <i class="{{ $feature['icon'] ?: 'fa-solid fa-circle-check' }}"></i>
                                                @endif
                                            </span>
                                            <span class="trust-text">{{ $feature['text'] }}</span>
                                        </div>
                                    @endif
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
                        ? $featuredCategories->take(10)
                        : \App\Models\Category::active()->orderByRaw('is_featured DESC, name ASC')->take(10)->get();
                @endphp

                @if($circleCategories->isNotEmpty())
                    <section class="category-circle-slider" aria-label="Categories">
                        <div class="circle-items-track">
                            @foreach($circleCategories as $cat)
                                <a href="{{ route('category.show', $cat->slug) }}" class="circle-item group">
                                    <div class="circle-avatar-wrap">
                                        @if($cat->image_url)
                                            <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="circle-category-img" loading="lazy">
                                        @elseif($cat->image && str_starts_with($cat->image, 'http'))
                                            <img src="{{ $cat->image }}" alt="{{ $cat->name }}" class="circle-category-img" loading="lazy">
                                        @else
                                            <div class="w-full h-full rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-sm">
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
                @if(isset($recentlyViewed) && $recentlyViewed->isNotEmpty())
                    <section class="recently-viewed-section" aria-label="Recently Viewed Products">
                        <div class="section-title-wrap">
                            <h2 class="section-heading">Recently Viewed</h2>
                        </div>

                        <div class="owl-carousel owl-theme recently-viewed-carousel" id="recentlyViewedCarousel">
                            @foreach($recentlyViewed as $recentItem)
                                <div class="item">
                                    <a href="{{ route('product.show', $recentItem->slug) }}" class="recent-product-card">
                                        <div class="recent-thumb-box">
                                            <img src="{{ $recentItem->thumbnail_url ?: 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&w=160&h=160&q=80' }}" alt="{{ $recentItem->name }}" class="recent-product-img" loading="lazy">
                                        </div>
                                        <div class="recent-product-info">
                                            <h3 class="recent-product-title">{{ $recentItem->name }}</h3>
                                            <div class="recent-rating-wrap">
                                                <span class="star-icons">★★★★★</span>
                                                <span class="review-count">({{ $recentItem->reviews_count ?: 5 }})</span>
                                            </div>
                                            <div class="recent-price-wrap">
                                                @if($recentItem->sale_price && $recentItem->sale_price < $recentItem->price)
                                                    <del class="recent-old-price">{{ number_format($recentItem->price, $recentItem->price == (int)$recentItem->price ? 0 : 2) }}৳</del>
                                                @endif
                                                <span class="recent-current-price">{{ number_format($recentItem->effective_price, $recentItem->effective_price == (int)$recentItem->effective_price ? 0 : 2) }}৳</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <!-- =========================================================================
                     Promotional Product Banner (WF-1000XM3 - Fully Dynamic from Admin Panel)
                     ========================================================================= -->
                @if(setting('promo_banner_enabled', '1') === '1')
                    @php
                        $promoBadge = setting('promo_badge', 'WF-1000XM3');
                        $promoTitle = setting('promo_title', 'শব্দ যেখানে নিখুঁত, সুর সেখানে জীবন্ত!');
                        $promoSubtitle = setting('promo_subtitle', '—হারিয়ে যান এক অন্যরকম অনুভূতির দুনিয়ায়।');
                        $promoBtnText = setting('promo_button_text', 'Get Now');
                        $promoBtnLink = setting('promo_button_link', '#product-wf1000xm3');

                        $promoVisual1 = setting_url('promo_visual_1', 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=400&q=80');
                        $promoVisual2 = setting_url('promo_visual_2', 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=400&q=80');
                        $promoBackdrop = setting_url('promo_backdrop', 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=1200&q=80');
                    @endphp
                    <section class="promo-banner-section" aria-label="Special Product Promotion">
                        <div class="promo-banner-card">
                            <!-- Left Info Column -->
                            <div class="promo-content">
                                @if(!empty($promoBadge))
                                    <span class="promo-badge">{{ $promoBadge }}</span>
                                @endif

                                @if(!empty($promoTitle))
                                    <h2 class="promo-title">
                                        {{ $promoTitle }}
                                    </h2>
                                @endif

                                @if(!empty($promoSubtitle))
                                    <p class="promo-subtitle">
                                        {{ $promoSubtitle }}
                                    </p>
                                @endif

                                @if(!empty($promoBtnText))
                                    <div class="promo-action">
                                        <a href="{{ $promoBtnLink ?: '#' }}" class="btn-promo-get">{{ $promoBtnText }}</a>
                                    </div>
                                @endif
                            </div>

                            <!-- Center / Product Showcase Visuals -->
                            @if(!empty($promoVisual1) || !empty($promoVisual2))
                                <div class="promo-product-visual">
                                    @if(!empty($promoVisual1))
                                        <div class="promo-earbud-item earbud-top">
                                            <img src="{{ $promoVisual1 }}" alt="{{ $promoBadge ?: 'Product Visual 1' }}" class="earbud-img">
                                        </div>
                                    @endif
                                    @if(!empty($promoVisual2))
                                        <div class="promo-earbud-item earbud-bottom">
                                            <img src="{{ $promoVisual2 }}" alt="{{ $promoBadge ?: 'Product Visual 2' }}" class="earbud-img">
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Right Lifestyle Backdrop Visual -->
                            @if(!empty($promoBackdrop))
                                <div class="promo-backdrop-wrap">
                                    <img src="{{ $promoBackdrop }}" alt="{{ $promoBadge ?: 'Lifestyle Promotion' }}" class="promo-bg-img" loading="lazy">
                                    <div class="promo-gradient-mask"></div>
                                </div>
                            @endif
                        </div>
                    </section>
                @endif

                <!-- =========================================================================
                     Featured Brands Carousel (Owl Carousel - 6 on PC, 3 on Mobile, 3s Autoplay)
                     ========================================================================= -->
                @php
                    $brandsList = isset($featuredBrands) && $featuredBrands->isNotEmpty()
                        ? $featuredBrands
                        : \App\Models\Brand::active()->whereHas('products', fn($q) => $q->active())->get();
                @endphp
                @if($brandsList->isNotEmpty())
                    <section class="brands-carousel-section" aria-label="Featured Brands">
                        <div class="owl-carousel owl-theme brands-carousel" id="brandsCarousel">
                            @foreach($brandsList as $b)
                                <div class="item">
                                    <a href="{{ route('brand.show', $b->slug) }}" class="brand-item-card" title="{{ $b->name }}">
                                        <div class="brand-logo-wrap">
                                            @if($b->image_url)
                                                <img src="{{ $b->image_url }}" alt="{{ $b->name }}" class="brand-logo-img">
                                            @else
                                                <span class="brand-name-fallback">{{ $b->name }}</span>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

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
                        <a href="{{ route('search') }}" class="view-all-link">All Products</a>
                    </div>

                    <div class="owl-carousel owl-theme product-grid-carousel" id="newArrivalCarousel">
                        @foreach($newArrivals as $item)
                            <div class="item">
                                <div class="arrival-product-card">
                                    <a href="{{ route('product.show', $item->slug) }}" class="arrival-thumb-box">
                                        @if($item->isPhysical() && $item->stock_quantity <= 0)
                                            <span class="discount-badge !bg-slate-800 text-white font-extrabold uppercase tracking-wider text-[10px]">Sold Out</span>
                                        @elseif($item->discount_percentage)
                                            <span class="discount-badge">-{{ $item->discount_percentage }}%</span>
                                        @endif
                                        <img src="{{ $item->thumbnail_url ?: 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $item->name }}" class="arrival-product-img {{ ($item->isPhysical() && $item->stock_quantity <= 0) ? 'grayscale opacity-75' : '' }}" loading="lazy">
                                        <div class="card-support-badge">
                                            <span class="badge-brand">d</span>
                                            <span class="badge-text">24/7 Support</span>
                                        </div>
                                    </a>
                                    <div class="arrival-product-body">
                                        <div class="arrival-title-rating">
                                            <h3 class="arrival-product-title"><a href="{{ route('product.show', $item->slug) }}">{{ $item->name }}</a></h3>
                                            <div class="arrival-rating">
                                                <span class="rating-num">{{ number_format($item->rating ?: 4.8, 1) }}</span>
                                                <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                                <span class="rating-count">({{ $item->reviews_count ?: 5 }})</span>
                                            </div>
                                        </div>
                                        @if($item->category)
                                            <a href="{{ route('category.show', $item->category->slug) }}" class="arrival-category-sub">{{ $item->category->name }}</a>
                                        @endif
                                        <div class="arrival-price-wrap">
                                            @if($item->sale_price && $item->sale_price < $item->price)
                                                <del class="arrival-old-price">{{ number_format($item->price, 2) }}৳</del>
                                            @endif
                                            <span class="arrival-price">{{ number_format($item->effective_price, 2) }}৳</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 mt-2">
                                            @if($item->isPhysical() && $item->stock_quantity <= 0)
                                                <button type="button"
                                                        disabled
                                                        class="btn-add-cart flex-1 justify-center !bg-slate-400 !text-white !cursor-not-allowed !shadow-none">
                                                    <i class="fa-solid fa-ban"></i> <span>Sold Out</span>
                                                </button>
                                            @else
                                                <button type="button"
                                                        onclick="digigoAddToCart({{ $item->id }}, null, 1, this)"
                                                        class="btn-add-cart flex-1 justify-center cursor-pointer">
                                                    <i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <!-- =========================================================================
                     Tri-Banner Promo Grid (Feature Promo Cards - Smartwatch, Earbuds, Mic - Fully Dynamic)
                     ========================================================================= -->
                @if(setting('tri_promo_enabled', '1') === '1')
                    @php
                        $triCards = tri_promo_cards();
                    @endphp
                    <section class="tri-promo-section" aria-label="Featured Product Highlights">
                        <div class="tri-promo-grid">
                            @foreach($triCards as $cardIndex => $card)
                                <div class="tri-promo-card {{ $card['theme'] === 'dark' ? 'card-dark' : 'card-light' }}">
                                    @if(!empty($card['image_url']))
                                        <div class="tri-promo-img-wrap">
                                            <img src="{{ $card['image_url'] }}" alt="{{ $card['title'] }}" class="tri-promo-img" loading="lazy">
                                        </div>
                                    @endif
                                    <div class="tri-promo-body">
                                        @if(!empty($card['title']))
                                            <h3 class="tri-promo-title">{{ $card['title'] }}</h3>
                                        @endif
                                        @if(!empty($card['desc']))
                                            <p class="tri-promo-desc">{{ $card['desc'] }}</p>
                                        @endif
                                        @if(!empty($card['button_text']))
                                            <a href="{{ $card['button_link'] ?: '#' }}" class="btn-tri-shop">{{ $card['button_text'] }}</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif





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
                        <a href="{{ route('search') }}" class="view-all-link">All Products</a>
                    </div>

                    <div class="owl-carousel owl-theme product-grid-carousel" id="mostSoldCarousel">
                        @foreach($mostSold as $item)
                            <div class="item">
                                <div class="arrival-product-card">
                                    <a href="{{ route('product.show', $item->slug) }}" class="arrival-thumb-box">
                                        @if($item->isPhysical() && $item->stock_quantity <= 0)
                                            <span class="discount-badge !bg-slate-800 text-white font-extrabold uppercase tracking-wider text-[10px]">Sold Out</span>
                                        @elseif($item->discount_percentage)
                                            <span class="discount-badge">-{{ $item->discount_percentage }}%</span>
                                        @endif
                                        <img src="{{ $item->thumbnail_url ?: 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $item->name }}" class="arrival-product-img {{ ($item->isPhysical() && $item->stock_quantity <= 0) ? 'grayscale opacity-75' : '' }}" loading="lazy">
                                        <div class="card-support-badge">
                                            <span class="badge-brand">d</span>
                                            <span class="badge-text">24/7 Support</span>
                                        </div>
                                    </a>
                                    <div class="arrival-product-body">
                                        <div class="arrival-title-rating">
                                            <h3 class="arrival-product-title"><a href="{{ route('product.show', $item->slug) }}">{{ $item->name }}</a></h3>
                                            <div class="arrival-rating">
                                                <span class="rating-num">{{ number_format($item->rating ?: 4.9, 1) }}</span>
                                                <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                                <span class="rating-count">({{ $item->reviews_count ?: 12 }})</span>
                                            </div>
                                        </div>
                                        @if($item->category)
                                            <a href="{{ route('category.show', $item->category->slug) }}" class="arrival-category-sub">{{ $item->category->name }}</a>
                                        @endif
                                        <div class="arrival-price-wrap">
                                            @if($item->sale_price && $item->sale_price < $item->price)
                                                <del class="arrival-old-price">{{ number_format($item->price, 2) }}৳</del>
                                            @endif
                                            <span class="arrival-price">{{ number_format($item->effective_price, 2) }}৳</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 mt-2">
                                            @if($item->isPhysical() && $item->stock_quantity <= 0)
                                                <button type="button"
                                                        disabled
                                                        class="btn-add-cart flex-1 justify-center !bg-slate-400 !text-white !cursor-not-allowed !shadow-none">
                                                    <i class="fa-solid fa-ban"></i> <span>Sold Out</span>
                                                </button>
                                            @else
                                                <button type="button"
                                                        onclick="digigoAddToCart({{ $item->id }}, null, 1, this)"
                                                        class="btn-add-cart flex-1 justify-center cursor-pointer">
                                                    <i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>


                


                <!-- =========================================================================
                     Dual Promo Banner Grid (Mounting Stand & Portable Fan / Coupon & Announcement)
                     ========================================================================= -->
                @if(setting('dual_promo_enabled', '1') === '1')
                    @php
                        $dualPromos = dual_promo_banners();
                    @endphp
                    <section class="dual-promo-section" aria-label="Special Product Offers">
                        <div class="dual-promo-grid">
                            @foreach($dualPromos as $banner)
                                @php
                                    $bgStyle = 'background: linear-gradient(135deg, ' . e($banner['bg_gradient_start']) . ' 0%, ' . e($banner['bg_gradient_end']) . ' 100%);';
                                @endphp
                                <a href="{{ $banner['link'] }}"
                                   class="dual-promo-card"
                                   style="{{ $bgStyle }}">
                                    <div class="dual-promo-content">
                                        @if(!empty($banner['title']))
                                            <h3 class="dual-promo-title">{!! nl2br(e($banner['title'])) !!}</h3>
                                        @endif

                                        @if(!empty($banner['coupon_code']) || !empty($banner['offer_label']))
                                            <div class="dual-promo-offer-box">
                                                @if(!empty($banner['offer_label']))
                                                    <span>{{ $banner['offer_label'] }}</span>
                                                @endif
                                                @if(!empty($banner['coupon_code']))
                                                    <span class="promo-code-pill" style="background-color: {{ e($banner['coupon_bg_color']) }};">
                                                        {{ $banner['coupon_code'] }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif

                                        @if(!empty($banner['desc']))
                                            <p class="dual-promo-desc">{{ $banner['desc'] }}</p>
                                        @endif

                                        @if(!empty($banner['disclaimer']))
                                            <p class="dual-promo-disclaimer">{{ $banner['disclaimer'] }}</p>
                                        @endif
                                    </div>
                                    @if(!empty($banner['image_url']))
                                        <div class="dual-promo-visual">
                                            <img src="{{ $banner['image_url'] }}" alt="{{ strip_tags($banner['title']) }}" class="dual-promo-visual-img" loading="lazy">
                                        </div>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif



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
                                @foreach($digitalDeals as $item)
                                    <div class="item">
                                        <div class="arrival-product-card">
                                            <a href="{{ route('product.show', $item->slug) }}" class="arrival-thumb-box">
                                                @if($item->discount_percentage)
                                                    <span class="discount-badge">-{{ $item->discount_percentage }}%</span>
                                                @endif
                                                <img src="{{ $item->thumbnail_url ?: 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $item->name }}" class="arrival-product-img" loading="lazy">
                                                <div class="card-support-badge">
                                                    <span class="badge-brand">d</span>
                                                    <span class="badge-text">24/7 Support</span>
                                                </div>
                                            </a>
                                            <div class="arrival-product-body">
                                                <div class="arrival-title-rating">
                                                    <h3 class="arrival-product-title"><a href="{{ route('product.show', $item->slug) }}">{{ $item->name }}</a></h3>
                                                    <div class="arrival-rating">
                                                        <span class="rating-num">{{ number_format($item->rating ?: 4.9, 1) }}</span>
                                                        <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                                        <span class="rating-count">({{ $item->reviews_count ?: 10 }})</span>
                                                    </div>
                                                </div>
                                                @if($item->category)
                                                    <a href="{{ route('category.show', $item->category->slug) }}" class="arrival-category-sub">{{ $item->category->name }}</a>
                                                @endif
                                                <div class="arrival-price-wrap">
                                                    @if($item->sale_price && $item->sale_price < $item->price)
                                                        <del class="arrival-old-price">{{ number_format($item->price, 2) }}৳</del>
                                                    @endif
                                                    <span class="arrival-price">{{ number_format($item->effective_price, 2) }}৳</span>
                                                </div>
                                                <div class="flex items-center gap-1.5 mt-2">
                                            <button type="button"
                                                    onclick="digigoAddToCart({{ $item->id }}, null, 1, this)"
                                                    class="btn-add-cart flex-1 justify-center cursor-pointer">
                                                <i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span>
                                            </button>
                                        </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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
                        @foreach($digitalDeals as $item)
                            <div class="arrival-product-card">
                                <a href="{{ route('product.show', $item->slug) }}" class="arrival-thumb-box">
                                    @if($item->isPhysical() && $item->stock_quantity <= 0)
                                        <span class="discount-badge !bg-slate-800 text-white font-extrabold uppercase tracking-wider text-[10px]">Sold Out</span>
                                    @elseif($item->discount_percentage)
                                        <span class="discount-badge">-{{ $item->discount_percentage }}%</span>
                                    @endif
                                    <img src="{{ $item->thumbnail_url ?: 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $item->name }}" class="arrival-product-img {{ ($item->isPhysical() && $item->stock_quantity <= 0) ? 'grayscale opacity-75' : '' }}" loading="lazy">
                                    <div class="card-support-badge">
                                        <span class="badge-brand">d</span>
                                        <span class="badge-text">24/7 Support</span>
                                    </div>
                                </a>
                                <div class="arrival-product-body">
                                    <div class="arrival-title-rating">
                                        <h3 class="arrival-product-title"><a href="{{ route('product.show', $item->slug) }}">{{ $item->name }}</a></h3>
                                        <div class="arrival-rating">
                                            <span class="rating-num">{{ number_format($item->rating ?: 4.9, 1) }}</span>
                                            <span class="star-icon"><i class="fa-solid fa-star"></i></span>
                                            <span class="rating-count">({{ $item->reviews_count ?: 8 }})</span>
                                        </div>
                                    </div>
                                    @if($item->category)
                                        <a href="{{ route('category.show', $item->category->slug) }}" class="arrival-category-sub">{{ $item->category->name }}</a>
                                    @endif
                                    <div class="arrival-price-wrap">
                                        @if($item->sale_price && $item->sale_price < $item->price)
                                            <del class="arrival-old-price">{{ number_format($item->price, 2) }}৳</del>
                                        @endif
                                        <span class="arrival-price">{{ number_format($item->effective_price, 2) }}৳</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 mt-2">
                                        @if($item->isPhysical() && $item->stock_quantity <= 0)
                                            <button type="button"
                                                    disabled
                                                    class="btn-add-cart flex-1 justify-center !bg-slate-400 !text-white !cursor-not-allowed !shadow-none">
                                                <i class="fa-solid fa-ban"></i> <span>Sold Out</span>
                                            </button>
                                        @else
                                            <button type="button"
                                                    onclick="digigoAddToCart({{ $item->id }}, null, 1, this)"
                                                    class="btn-add-cart flex-1 justify-center cursor-pointer">
                                                <i class="fa-solid fa-cart-shopping"></i> <span>Add To Cart</span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>



                <!-- =========================================================================
                     Remini AI Image Editing Tool Promotional Banner (Fully Dynamic)
                     ========================================================================= -->
                @if(setting('remini_banner_enabled', '1') === '1')
                    @php
                        $reminiBadge = setting('remini_badge', 'AI Image Editing Tool');
                        $reminiTitle = setting('remini_title', "এক ক্লিকেই আপনার ঝাপসা ছবিকে\nকরে তুলুন");
                        $reminiHighlight = setting('remini_title_highlight', 'ক্রিস্টাল ক্লিয়ার!');
                        $reminiBtnText = setting('remini_button_text', 'Get Now');
                        $reminiBtnLink = setting('remini_button_link', '#remini-pro');
                        $reminiAppName = setting('remini_app_name', 'Remini');

                        $reminiBgColor = setting('remini_bg_color', '#f3c4c4');
                        $reminiBadgeColor = setting('remini_badge_color', '#8b1d24');
                        $reminiAppBgColor = setting('remini_app_bg_color', '#991b1b');

                        $customAppIcon = setting_url('remini_app_icon');
                        $landscapeImg = setting_url('remini_landscape_img', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1000&q=80');
                    @endphp
                    <section class="remini-promo-section" aria-label="Remini AI Image Editing Tool">
                        <div class="remini-banner-card" style="background-color: {{ $reminiBgColor }};">
                            <!-- Left Content Info -->
                            <div class="remini-content-col">
                                @if(!empty($reminiBadge))
                                    <span class="remini-badge" style="background-color: {{ $reminiBadgeColor }};">{{ $reminiBadge }}</span>
                                @endif
                                <h2 class="remini-title">
                                    {!! nl2br(e($reminiTitle)) !!}
                                    @if(!empty($reminiHighlight))
                                        <br><span class="remini-highlight">{{ $reminiHighlight }}</span>
                                    @endif
                                </h2>
                                @if(!empty($reminiBtnText))
                                    <div class="remini-action">
                                        <a href="{{ $reminiBtnLink ?: '#' }}" class="btn-remini-get">{{ $reminiBtnText }}</a>
                                    </div>
                                @endif
                            </div>

                            <!-- Center Remini App Icon Badge -->
                            <div class="remini-app-badge" style="background-color: {{ $reminiAppBgColor }};">
                                <div class="remini-icon-frame">
                                    @if($customAppIcon)
                                        <img src="{{ $customAppIcon }}" alt="{{ $reminiAppName }}" class="max-w-[70px] max-h-[60px] object-contain">
                                    @else
                                        <svg viewBox="0 0 100 100" fill="none" class="remini-svg-frame">
                                            <rect x="22" y="24" width="56" height="46" rx="4" stroke="#fbcfe8" stroke-width="5.5" fill="none" transform="rotate(-6 50 47)"/>
                                            <path d="M57 32L59.5 38.5L66 41L59.5 43.5L57 50L54.5 43.5L48 41L54.5 38.5Z" fill="#ffffff"/>
                                        </svg>
                                    @endif
                                </div>
                                <span class="remini-brand-text">{{ $reminiAppName }}</span>
                            </div>

                            <!-- Right Visual Crystal Clear Landscape Comparison -->
                            <div class="remini-visual-col">
                                <img src="{{ $landscapeImg }}" alt="Landscape Comparison" class="remini-landscape-img" loading="lazy">
                            </div>
                        </div>
                    </section>
                @endif




            </div>
        </main>
</div>
@endsection
