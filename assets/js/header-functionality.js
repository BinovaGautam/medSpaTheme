/**
 * Enhanced Header Functionality - Professional Medical Spa Header System
 * Implements transparency transitions, hide/show on scroll, and mobile menu
 * VERSION: 2.0.0
 * COMPLIANCE: WCAG 2.1 AA Accessibility Standards
 */

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        headerSelector: '.site-header',
        heroSelectors: ['.premium-hero', '.modern-hero', '.hero-section', '.hero-banner'],
        scrollThreshold: 50,
        hideThreshold: 120,
        transparencyMaxScroll: 300,
        throttleDelay: 16,
        opacitySteps: [
            { threshold: 0, className: 'transparent', opacity: 0 },
            { threshold: 10, className: 'scroll-opacity-10', opacity: 0.1 },
            { threshold: 50, className: 'scroll-opacity-25', opacity: 0.25 },
            { threshold: 100, className: 'scroll-opacity-50', opacity: 0.5 },
            { threshold: 150, className: 'scroll-opacity-75', opacity: 0.75 },
            { threshold: 200, className: 'scroll-opacity-90', opacity: 0.9 },
            { threshold: 250, className: 'scroll-opacity-100', opacity: 0.95 }
        ]
    };

    // State Management
    let header = null;
    let body = null;
    let isHeroPage = false;
    let isScrolled = false;
    let isHidden = false;
    let lastScrollY = 0;
    let currentOpacityClass = '';
    let ticking = false;

    // Mobile menu elements
    let mobileMenuToggle = null;
    let mobileMenu = null;
    let mobileMenuOverlay = null;
    let mobileMenuClose = null;
    let isMenuOpen = false;

    /**
     * Initialize enhanced header functionality
     */
    function init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupHeader);
        } else {
            setupHeader();
        }
    }

    /**
     * Setup complete header system
     */
    function setupHeader() {
        // Get DOM elements
        getDOMElements();

        if (!header) {
            console.warn('Header element not found');
            return;
        }

        // Check if this is a hero page
        isHeroPage = checkIfHeroPage();

        // Setup event listeners
        setupEventListeners();

        // Setup initial states
        setupInitialStates();

        // Initialize scroll behavior
        handleInitialScroll();

        console.log('Enhanced header functionality initialized', { isHeroPage });
    }

    /**
     * Get all required DOM elements
     */
    function getDOMElements() {
        header = document.querySelector(CONFIG.headerSelector);
        body = document.body;
        mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        mobileMenu = document.querySelector('.mobile-menu');
        mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
        mobileMenuClose = document.querySelector('.mobile-menu-close');
    }

    /**
     * Check if current page has hero sections
     */
    function checkIfHeroPage() {
        // Check body classes
        if (body.classList.contains('transparent-header') ||
            body.classList.contains('has-hero-section') ||
            body.classList.contains('home')) {
            return true;
        }

        // Check for hero section elements
        for (const heroSelector of CONFIG.heroSelectors) {
            if (document.querySelector(heroSelector)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Setup all event listeners
     */
    function setupEventListeners() {
        // Scroll handling with throttling
        window.addEventListener('scroll', throttledScrollHandler, { passive: true });

        // Window resize
        window.addEventListener('resize', handleWindowResize, { passive: true });

        // Mobile menu events
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', handleMenuToggle);
        }

        if (mobileMenuClose) {
            mobileMenuClose.addEventListener('click', closeMenu);
        }

        if (mobileMenuOverlay) {
            mobileMenuOverlay.addEventListener('click', closeMenu);
        }

        // Keyboard navigation
        document.addEventListener('keydown', handleKeyboardNavigation);

        // Focus trap for mobile menu
        if (mobileMenu) {
            mobileMenu.addEventListener('keydown', handleMenuKeyNavigation);
        }
    }

    /**
     * Setup initial states and ARIA attributes
     */
    function setupInitialStates() {
        // Set initial transparency on hero pages
        if (isHeroPage) {
            header.classList.add('transparent');
            body.classList.add('has-hero-section');
        }

        // Set initial ARIA states for mobile menu
        if (mobileMenuToggle) {
            mobileMenuToggle.setAttribute('aria-expanded', 'false');
        }

        if (mobileMenu) {
            mobileMenu.setAttribute('aria-hidden', 'true');
        }

        if (mobileMenuOverlay) {
            mobileMenuOverlay.setAttribute('aria-hidden', 'true');
        }

        // Ensure menu is closed
        closeMenu();
    }

    /**
     * Handle initial scroll position
     */
    function handleInitialScroll() {
        lastScrollY = window.pageYOffset || document.documentElement.scrollTop;

        if (lastScrollY > 0) {
            updateHeaderOnScroll();
        }
    }

    /**
     * Throttled scroll event handler
     */
    function throttledScrollHandler() {
        if (!ticking) {
            requestAnimationFrame(updateHeaderOnScroll);
            ticking = true;
        }
    }

    /**
     * Main scroll handler - implements transparency and hide/show behavior
     */
    function updateHeaderOnScroll() {
        ticking = false;

        const currentScrollY = window.pageYOffset || document.documentElement.scrollTop;
        const scrollingDown = currentScrollY > lastScrollY;
        const scrollingUp = currentScrollY < lastScrollY;

        // Handle transparency on hero pages
        if (isHeroPage) {
            updateHeaderTransparency(currentScrollY);
        }

        // Handle header visibility based on scroll direction
        updateHeaderVisibility(currentScrollY, scrollingDown, scrollingUp);

        // Update scroll state
        updateScrollState(currentScrollY);

        // Update last scroll position
        lastScrollY = Math.max(0, currentScrollY);
    }

    /**
     * Update header transparency on hero pages
     */
    function updateHeaderTransparency(scrollY) {
        if (!isHeroPage) return;

        // Find appropriate opacity step
        let targetStep = CONFIG.opacitySteps[0];

        for (const step of CONFIG.opacitySteps) {
            if (scrollY >= step.threshold) {
                targetStep = step;
            } else {
                break;
            }
        }

        // Apply opacity class if it changed
        if (targetStep.className !== currentOpacityClass) {
            // Remove previous opacity classes
            CONFIG.opacitySteps.forEach(step => {
                if (step.className) {
                    header.classList.remove(step.className);
                }
            });

            // Add new opacity class
            if (targetStep.className) {
                header.classList.add(targetStep.className);
            }

            currentOpacityClass = targetStep.className;
        }
    }

    /**
     * Update header visibility based on scroll direction
     */
    function updateHeaderVisibility(currentScrollY, scrollingDown, scrollingUp) {
        // Show/hide header based on scroll direction (after hide threshold)
        if (currentScrollY > CONFIG.hideThreshold) {
            if (scrollingDown && !isHidden && (currentScrollY - lastScrollY) > 5) {
                // Hide header when scrolling down with momentum
                hideHeader();
            } else if (scrollingUp && isHidden && (lastScrollY - currentScrollY) > 3) {
                // Show header when scrolling up with momentum
                showHeader();
            }
        } else if (currentScrollY <= CONFIG.hideThreshold && isHidden) {
            // Always show header when near top
            showHeader();
        }
    }

    /**
     * Update scroll state classes
     */
    function updateScrollState(currentScrollY) {
        if (currentScrollY > CONFIG.scrollThreshold) {
            if (!isScrolled) {
                header.classList.add('scrolled');
                body.classList.add('header-scrolled');
                isScrolled = true;
            }
        } else {
            if (isScrolled) {
                header.classList.remove('scrolled');
                body.classList.remove('header-scrolled');
                isScrolled = false;
            }
        }
    }

    /**
     * Hide header
     */
    function hideHeader() {
        if (isHidden) return;

        header.classList.add('hidden');
        header.classList.remove('visible');
        isHidden = true;

        // Dispatch event
        dispatchHeaderEvent('headerHidden');
    }

    /**
     * Show header
     */
    function showHeader() {
        if (!isHidden) return;

        header.classList.remove('hidden');
        header.classList.add('visible');
        isHidden = false;

        // Dispatch event
        dispatchHeaderEvent('headerVisible');
    }

    /**
     * Handle mobile menu toggle
     */
    function handleMenuToggle(event) {
        event.preventDefault();

        if (isMenuOpen) {
            closeMenu();
        } else {
            openMenu();
        }
    }

    /**
     * Open mobile menu
     */
    function openMenu() {
        if (!mobileMenu || !mobileMenuToggle) return;

        isMenuOpen = true;

        // Update ARIA states
        mobileMenuToggle.setAttribute('aria-expanded', 'true');
        mobileMenu.setAttribute('aria-hidden', 'false');

        if (mobileMenuOverlay) {
            mobileMenuOverlay.setAttribute('aria-hidden', 'false');
        }

        // Add body class to prevent scrolling
        body.classList.add('menu-open');

        // Focus management
        setTimeout(() => {
            const firstFocusable = mobileMenu.querySelector('a, button');
            if (firstFocusable) {
                firstFocusable.focus();
            }
        }, 100);

        // Announce to screen readers
        announceToScreenReader('Navigation menu opened');

        console.log('Mobile menu opened');
    }

    /**
     * Close mobile menu
     */
    function closeMenu() {
        if (!mobileMenu || !mobileMenuToggle) return;

        isMenuOpen = false;

        // Update ARIA states
        mobileMenuToggle.setAttribute('aria-expanded', 'false');
        mobileMenu.setAttribute('aria-hidden', 'true');

        if (mobileMenuOverlay) {
            mobileMenuOverlay.setAttribute('aria-hidden', 'true');
        }

        // Remove body class
        body.classList.remove('menu-open');

        // Return focus to toggle button
        if (document.activeElement === document.body) {
            mobileMenuToggle.focus();
        }

        console.log('Mobile menu closed');
    }

    /**
     * Handle keyboard navigation
     */
    function handleKeyboardNavigation(event) {
        // Close menu on Escape key
        if (event.key === 'Escape' && isMenuOpen) {
            closeMenu();
        }
    }

    /**
     * Handle keyboard navigation within mobile menu
     */
    function handleMenuKeyNavigation(event) {
        if (!isMenuOpen) return;

        const focusableElements = mobileMenu.querySelectorAll(
            'a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );

        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        // Tab navigation within menu
        if (event.key === 'Tab') {
            if (event.shiftKey) {
                // Shift + Tab: focus previous element
                if (document.activeElement === firstElement) {
                    event.preventDefault();
                    lastElement.focus();
                }
            } else {
                // Tab: focus next element
                if (document.activeElement === lastElement) {
                    event.preventDefault();
                    firstElement.focus();
                }
            }
        }
    }

    /**
     * Handle window resize
     */
    function handleWindowResize() {
        // Close mobile menu on desktop resize
        if (window.innerWidth > 768 && isMenuOpen) {
            closeMenu();
        }
    }

    /**
     * Dispatch header events
     */
    function dispatchHeaderEvent(eventName) {
        const event = new CustomEvent(eventName, {
            detail: {
                header: header,
                isHidden: isHidden,
                isScrolled: isScrolled,
                isHeroPage: isHeroPage,
                scrollY: lastScrollY
            }
        });

        document.dispatchEvent(event);
    }

    /**
     * Announce message to screen readers
     */
    function announceToScreenReader(message) {
        const announcement = document.createElement('div');
        announcement.setAttribute('aria-live', 'polite');
        announcement.setAttribute('aria-atomic', 'true');
        announcement.className = 'sr-only';
        announcement.textContent = message;

        body.appendChild(announcement);

        // Remove after announcement
        setTimeout(() => {
            if (announcement.parentNode) {
                announcement.parentNode.removeChild(announcement);
            }
        }, 1000);
    }

    /**
     * Public API
     */
    window.HeaderFunctionality = {
        openMenu: openMenu,
        closeMenu: closeMenu,
        isMenuOpen: function() { return isMenuOpen; },
        showHeader: showHeader,
        hideHeader: hideHeader,
        isHidden: function() { return isHidden; },
        isHeroPage: function() { return isHeroPage; },
        getCurrentScrollY: function() { return lastScrollY; }
    };

    // Initialize when script loads
    init();

})();

/**
 * Navigation Enhancement Module
 * Handles current page highlighting and smooth interactions
 */
(function() {
    'use strict';

    function enhanceNavigation() {
        // Highlight current page in navigation
        highlightCurrentPage();

        // Add smooth scroll for anchor links
        setupSmoothScroll();

        // Add click analytics
        setupClickTracking();
    }

    function highlightCurrentPage() {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-menu a, .mobile-nav-list a');

        navLinks.forEach(link => {
            const linkPath = new URL(link.href).pathname;
            const listItem = link.closest('li');

            if (linkPath === currentPath) {
                if (listItem) {
                    listItem.classList.add('current-menu-item', 'current');
                }
                link.setAttribute('aria-current', 'page');
            }
        });
    }

    function setupSmoothScroll() {
        const anchorLinks = document.querySelectorAll('a[href^="#"]');

        anchorLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    event.preventDefault();

                    // Close mobile menu if open
                    if (window.HeaderFunctionality && window.HeaderFunctionality.isMenuOpen()) {
                        window.HeaderFunctionality.closeMenu();
                    }

                    // Smooth scroll to target
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                    // Update focus for accessibility
                    setTimeout(() => {
                        targetElement.focus({ preventScroll: true });
                    }, 500);
                }
            });
        });
    }

    function setupClickTracking() {
        const trackableElements = document.querySelectorAll('.btn-consultation, .nav-menu a, .mobile-consultation-btn');

        trackableElements.forEach(element => {
            element.addEventListener('click', function() {
                const action = this.textContent.trim();
                const category = this.classList.contains('btn-consultation') ? 'CTA' : 'Navigation';

                // Send to analytics if available
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'click', {
                        event_category: category,
                        event_label: action
                    });
                }

                console.log(`Tracked: ${category} - ${action}`);
            });
        });
    }

    // Initialize navigation enhancements
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', enhanceNavigation);
    } else {
        enhanceNavigation();
    }

})();
