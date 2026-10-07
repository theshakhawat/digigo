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
        const autoPlayDelay = 4000; // 4 seconds auto change

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
            slideInterval = setInterval(nextSlide, autoPlayDelay);
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
    }
});
