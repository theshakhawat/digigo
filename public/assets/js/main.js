/**
 * DigiGo - Frontend Vanilla JavaScript
 * Handles:
 * 1. Mobile & Desktop Sidebar Drawer & Overlay
 * 2. Sidebar Tabs (CATEGORIES / MENU)
 * 3. Mobile Fullscreen Search Modal & Popular Tag Filters
 * 4. Hero Banner Carousel (Continuous Auto-play, Dots, Hover Arrows, Touch Swipe)
 * 5. Sticky Header Scroll Effect
 */

document.addEventListener('DOMContentLoaded', () => {
    /* =========================================================================
       1. Sidebar Drawer & Overlay
       ========================================================================= */
    const sidebar = document.getElementById('categorySidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleButtons = document.querySelectorAll('.js-sidebar-toggle');
    const closeButtons = document.querySelectorAll('.js-sidebar-close');

    const openSidebar = () => {
        if (!sidebar || !overlay) return;
        sidebar.classList.add('open');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    const closeSidebar = () => {
        if (!sidebar || !overlay) return;
        sidebar.classList.remove('open');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    };

    const toggleSidebar = () => {
        if (sidebar && sidebar.classList.contains('open')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    };

    toggleButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            toggleSidebar();
        });
    });

    closeButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            closeSidebar();
        });
    });

    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    /* =========================================================================
       2. Sidebar Tabs (CATEGORIES vs MENU - Matching Screenshots 2 & 3)
       ========================================================================= */
    const tabButtons = document.querySelectorAll('.sidebar-tab-btn');
    const tabCategories = document.getElementById('tabCategories');
    const tabMenu = document.getElementById('tabMenu');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const target = button.getAttribute('data-tab');

            tabButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            if (target === 'categories') {
                if (tabCategories) tabCategories.classList.add('active');
                if (tabMenu) tabMenu.classList.remove('active');
            } else if (target === 'menu') {
                if (tabMenu) tabMenu.classList.add('active');
                if (tabCategories) tabCategories.classList.remove('active');
            }
        });
    });

    /* =========================================================================
       3. Mobile Fullscreen Search Modal (Matching Screenshot 4)
       ========================================================================= */
    const searchModal = document.getElementById('mobileSearchModal');
    const searchOpenButtons = document.querySelectorAll('.js-search-modal-open');
    const searchCloseButtons = document.querySelectorAll('.js-search-modal-close');
    const searchInput = document.getElementById('mobileSearchInput');
    const popularTags = document.querySelectorAll('.popular-tag-btn');
    const historyRemoveBtn = document.querySelector('.tag-remove');

    const openSearchModal = () => {
        if (!searchModal) return;
        searchModal.classList.add('active');
        document.body.style.overflow = 'hidden';
        if (searchInput) {
            setTimeout(() => searchInput.focus(), 150);
        }
    };

    const closeSearchModal = () => {
        if (!searchModal) return;
        searchModal.classList.remove('active');
        document.body.style.overflow = '';
    };

    searchOpenButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            openSearchModal();
        });
    });

    searchCloseButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            closeSearchModal();
        });
    });

    // Populate search input on popular tag click
    popularTags.forEach(tag => {
        tag.addEventListener('click', () => {
            if (searchInput) {
                searchInput.value = tag.textContent.trim();
                searchInput.focus();
            }
        });
    });

    // Clear history item
    if (historyRemoveBtn) {
        historyRemoveBtn.addEventListener('click', () => {
            const historyTag = historyRemoveBtn.closest('.history-tag');
            if (historyTag) {
                historyTag.remove();
            }
        });
    }

    /* =========================================================================
       4. Keyboard Shortcuts (Escape to close modals)
       ========================================================================= */
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (searchModal && searchModal.classList.contains('active')) {
                closeSearchModal();
            }
            if (sidebar && sidebar.classList.contains('open')) {
                closeSidebar();
            }
        }
    });

    /* =========================================================================
       5. Hero Banner Carousel with Continuous Auto-play & Touch Swipe
       ========================================================================= */
    const carousel = document.getElementById('heroCarousel');
    if (carousel) {
        const slides = carousel.querySelectorAll('.carousel-slide');
        const dots = carousel.querySelectorAll('.dot-btn');
        const prevBtn = carousel.querySelector('.js-carousel-prev');
        const nextBtn = carousel.querySelector('.js-carousel-next');
        
        let currentSlide = 0;
        let slideInterval = null;
        const autoPlayDelay = parseInt(carousel.getAttribute('data-delay') || '4000', 10);
        const isAutoPlayEnabled = carousel.getAttribute('data-autoplay') !== 'false';

        const goToSlide = (index) => {
            if (index < 0) {
                index = slides.length - 1;
            } else if (index >= slides.length) {
                index = 0;
            }

            slides.forEach((slide, idx) => {
                slide.classList.toggle('active', idx === index);
            });

            dots.forEach((dot, idx) => {
                dot.classList.toggle('active', idx === index);
                dot.setAttribute('aria-selected', idx === index ? 'true' : 'false');
            });

            currentSlide = index;
        };

        const nextSlide = () => goToSlide(currentSlide + 1);
        const prevSlide = () => goToSlide(currentSlide - 1);

        const startAutoPlay = () => {
            stopAutoPlay();
            if (isAutoPlayEnabled && slides.length > 1) {
                slideInterval = setInterval(nextSlide, autoPlayDelay);
            }
        };

        const stopAutoPlay = () => {
            if (slideInterval) {
                clearInterval(slideInterval);
                slideInterval = null;
            }
        };

        // Dot button click handlers
        dots.forEach(dot => {
            dot.addEventListener('click', () => {
                const slideIndex = parseInt(dot.getAttribute('data-slide'), 10);
                goToSlide(slideIndex);
                startAutoPlay();
            });
        });

        // Arrow button click handlers
        if (prevBtn) {
            prevBtn.addEventListener('click', (e) => {
                e.preventDefault();
                prevSlide();
                startAutoPlay();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', (e) => {
                e.preventDefault();
                nextSlide();
                startAutoPlay();
            });
        }

        // Pause on desktop mouse hover & resume on mouse leave
        carousel.addEventListener('mouseenter', stopAutoPlay);
        carousel.addEventListener('mouseleave', startAutoPlay);

        // Mobile Touch Swipe support (left / right swipe)
        let touchStartX = 0;
        let touchEndX = 0;

        carousel.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        carousel.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            const swipeDistance = touchEndX - touchStartX;
            if (Math.abs(swipeDistance) > 40) {
                if (swipeDistance < 0) {
                    nextSlide(); // Swiped left -> next slide
                } else {
                    prevSlide(); // Swiped right -> prev slide
                }
                startAutoPlay();
            }
        }, { passive: true });

        // Start Auto-play immediately
        startAutoPlay();
    }

    /* =========================================================================
       6. Sticky Navbar Logic (Guaranteed Scroll Pinned)
       ========================================================================= */
    const subHeaderBar = document.querySelector('.sub-header-bar');
    const topHeader = document.querySelector('.top-header');

    const handleStickyNav = () => {
        const scrollY = window.pageYOffset || document.documentElement.scrollTop || window.scrollY || 0;
        const isDesktop = window.innerWidth > 992;
        
        if (isDesktop && subHeaderBar) {
            if (scrollY >= 65) {
                subHeaderBar.classList.add('is-fixed');
            } else {
                subHeaderBar.classList.remove('is-fixed');
            }
        } else if (subHeaderBar) {
            subHeaderBar.classList.remove('is-fixed');
        }

        if (topHeader) {
            topHeader.classList.toggle('is-scrolled', scrollY > 20);
        }
    };

    window.addEventListener('scroll', handleStickyNav, { passive: true });
    window.addEventListener('resize', handleStickyNav, { passive: true });
    handleStickyNav();

    /* =========================================================================
       7. Recently Viewed Products (Owl Carousel 2 Initialization)
       ========================================================================= */
    if (typeof jQuery !== 'undefined' && jQuery.fn.owlCarousel) {
        $('#recentlyViewedCarousel').owlCarousel({
            loop: false,
            margin: 14,
            nav: false,
            dots: true,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            smartSpeed: 450,
            responsive: {
                0: {
                    items: 1.2,
                    margin: 10
                },
                480: {
                    items: 2,
                    margin: 12
                },
                768: {
                    items: 3,
                    margin: 14
                },
                1024: {
                    items: 4,
                    margin: 14
                },
                1280: {
                    items: 5,
                    margin: 16
                }
            }
        });

        /* =========================================================================
           8. Featured Brands (Owl Carousel: 6 on PC, 3 on Mobile, 3s Autoplay)
           ========================================================================= */
        $('#brandsCarousel').owlCarousel({
            loop: true,
            margin: 14,
            nav: false,
            dots: false,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            smartSpeed: 500,
            responsive: {
                0: {
                    items: 3,
                    margin: 8
                },
                576: {
                    items: 4,
                    margin: 10
                },
                768: {
                    items: 5,
                    margin: 12
                },
                1024: {
                    items: 6,
                    margin: 14
                }
            }
        });

        /* =========================================================================
           9. New Arrival Products (Owl Carousel 2: 4 on Desktop for larger cards)
           ========================================================================= */
        $('#newArrivalCarousel').owlCarousel({
            loop: false,
            margin: 18,
            nav: false,
            dots: true,
            autoplay: false,
            smartSpeed: 450,
            responsive: {
                0: {
                    items: 2,
                    margin: 10
                },
                576: {
                    items: 2,
                    margin: 12
                },
                768: {
                    items: 3,
                    margin: 14
                },
                1024: {
                    items: 4,
                    margin: 16
                },
                1280: {
                    items: 4,
                    margin: 18
                }
            }
        });

        /* =========================================================================
           10. Most Sold Products (Owl Carousel 2 Initialization - 4 on Desktop, max 10 products)
           ========================================================================= */
        $('#mostSoldCarousel').owlCarousel({
            loop: false,
            margin: 16,
            nav: false,
            dots: true,
            autoplay: false,
            smartSpeed: 450,
            responsive: {
                0: {
                    items: 2,
                    margin: 10
                },
                576: {
                    items: 2,
                    margin: 12
                },
                768: {
                    items: 3,
                    margin: 14
                },
                1024: {
                    items: 4,
                    margin: 16
                },
                1280: {
                    items: 4,
                    margin: 16
                }
            }
        });

        /* =========================================================================
           11. Digital Deals Carousel (Owl Carousel 2 Initialization)
           ========================================================================= */
        $('#digitalDealsCarousel').owlCarousel({
            loop: false,
            margin: 14,
            nav: false,
            dots: true,
            autoplay: false,
            smartSpeed: 450,
            responsive: {
                0: {
                    items: 1.4,
                    margin: 10
                },
                480: {
                    items: 2,
                    margin: 12
                },
                768: {
                    items: 2.2,
                    margin: 12
                },
                1024: {
                    items: 3,
                    margin: 14
                }
            }
        });

        if ($('#digitalGoodsCarousel.owl-carousel').length) {
            $('#digitalGoodsCarousel').owlCarousel({
                loop: false,
                margin: 16,
                nav: false,
                dots: true,
                autoplay: false,
                smartSpeed: 450,
                responsive: {
                    0: { items: 2, margin: 10 },
                    576: { items: 3, margin: 12 },
                    768: { items: 4, margin: 14 },
                    1024: { items: 5, margin: 16 }
                }
            });
        }
    }

    /* =========================================================================
       6. Global Toast Notification Helper
       ========================================================================= */
    window.showDigigoToast = function(message, type = 'success') {
        let container = document.getElementById('digigoToastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'digigoToastContainer';
            container.className = 'fixed bottom-5 right-5 z-50 flex flex-col gap-2 pointer-events-none max-w-sm w-full px-4';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `pointer-events-auto transform transition-all duration-300 ease-out translate-y-2 opacity-0 flex items-center gap-3 p-4 rounded-xl shadow-xl text-white text-xs font-semibold ${
            type === 'success' ? 'bg-slate-900 border border-slate-700' : 'bg-rose-600'
        }`;

        const icon = type === 'success'
            ? '<i class="fa-solid fa-circle-check text-emerald-400 text-base"></i>'
            : '<i class="fa-solid fa-triangle-exclamation text-white text-base"></i>';

        toast.innerHTML = `
            ${icon}
            <div class="flex-1">${message}</div>
            <button type="button" class="text-white/60 hover:text-white ml-2 text-sm" onclick="this.parentElement.remove()">&times;</button>
        `;

        container.appendChild(toast);

        // Animate in
        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-2', 'opacity-0');
        });

        // Auto dismiss after 3.5s
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    };

    /* =========================================================================
       7. Global AJAX Add To Cart Handler
       ========================================================================= */
    window.digigoAddToCart = function(productId, planId = null, quantity = 1, btn = null) {
        let originalContent = '';
        if (btn) {
            originalContent = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Adding...';
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId,
                plan_id: planId,
                quantity: quantity
            })
        })
        .then(res => res.json())
        .then(data => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }

            if (data.success) {
                // Update badge numbers
                document.querySelectorAll('.header-cart-badge').forEach(badge => {
                    badge.textContent = data.cart_count;
                    badge.classList.remove('hidden');
                });

                window.showDigigoToast(data.message || 'Added to cart!', 'success');
            } else {
                window.showDigigoToast(data.message || 'Failed to add item', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
            window.showDigigoToast('Something went wrong. Please try again.', 'error');
        });
    };

    /* =========================================================================
       8. Global AJAX Wishlist Toggle Handler
       ========================================================================= */
    window.digigoToggleWishlist = function(productId, btn = null) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch('/wishlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Update badge numbers
                document.querySelectorAll('.header-wishlist-badge').forEach(badge => {
                    badge.textContent = data.wishlist_count;
                    if (data.wishlist_count > 0) {
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                });

                // Toggle icon visual if button provided
                if (btn) {
                    const icon = btn.querySelector('i');
                    if (icon) {
                        if (data.added) {
                            icon.classList.remove('fa-regular', 'text-slate-500');
                            icon.classList.add('fa-solid', 'text-red-500');
                            btn.classList.add('text-red-500');
                        } else {
                            icon.classList.remove('fa-solid', 'text-red-500');
                            icon.classList.add('fa-regular', 'text-slate-500');
                            btn.classList.remove('text-red-500');
                        }
                    }
                }

                window.showDigigoToast(data.message, data.added ? 'success' : 'success');
            } else {
                window.showDigigoToast(data.message || 'Error updating wishlist', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            window.showDigigoToast('Something went wrong. Please try again.', 'error');
        });
    };
});
