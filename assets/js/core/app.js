/**
 * PreetiDreams Medical Spa Theme - Main Application
 *
 * Modern JavaScript application for interactive medical spa website
 * Built with ES6+ modules, accessibility focus, and performance optimization
 *
 * @author PreetiDreams Development Team
 * @version 2.0.0
 */

'use strict';

/**
 * Main Medical Spa Application Class
 */
class MedicalSpaApp {
    constructor() {
        this.config = {
            debug: false,
            analytics: true,
            performance: true,
            accessibility: true
        };

        this.modules = new Map();
        this.isInitialized = false;
        this.performance = {
            startTime: performance.now(),
            marks: new Map()
        };

        // Bind methods
        this.init = this.init.bind(this);
        this.handleDOMReady = this.handleDOMReady.bind(this);
        this.handleWindowLoad = this.handleWindowLoad.bind(this);

        this.log('App constructor initialized');
    }

    /**
     * Initialize the application
     */
    async init() {
        try {
            this.mark('init-start');

            // Check for DOM ready state
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', this.handleDOMReady);
            } else {
                await this.handleDOMReady();
            }

            // Handle window load for performance-dependent features
            if (document.readyState === 'complete') {
                await this.handleWindowLoad();
            } else {
                window.addEventListener('load', this.handleWindowLoad);
            }

            this.mark('init-end');
            this.log('App initialization completed');

        } catch (error) {
            this.handleError('App initialization failed', error);
        }
    }

    /**
     * Handle DOM ready state
     */
    async handleDOMReady() {
        try {
            this.mark('dom-ready-start');

            // Initialize core modules first
            await this.initializeCoreModules();

            // Initialize UI components
            await this.initializeUIComponents();

            // Setup global event listeners
            this.setupGlobalListeners();

            // Initialize accessibility features
            if (this.config.accessibility) {
                await this.initializeAccessibility();
            }

            this.isInitialized = true;
            this.mark('dom-ready-end');

            // Dispatch custom ready event
            this.dispatchEvent('medSpaDOMReady', {
                modules: Array.from(this.modules.keys()),
                performance: this.getPerformanceMetrics()
            });

        } catch (error) {
            this.handleError('DOM ready handling failed', error);
        }
    }

    /**
     * Handle window load state
     */
    async handleWindowLoad() {
        try {
            this.mark('window-load-start');

            // Initialize performance-dependent features
            await this.initializePerformanceFeatures();

            // Initialize analytics
            if (this.config.analytics) {
                await this.initializeAnalytics();
            }

            // Cleanup and optimization
            this.optimizePerformance();

            this.mark('window-load-end');

            // Dispatch custom load event
            this.dispatchEvent('medSpaFullyLoaded', {
                totalLoadTime: performance.now() - this.performance.startTime,
                performance: this.getPerformanceMetrics()
            });

        } catch (error) {
            this.handleError('Window load handling failed', error);
        }
    }

    /**
     * Initialize core modules
     */
    async initializeCoreModules() {
        const coreModules = [
            { name: 'utils', path: './utils.js' },
            { name: 'api', path: './api.js' }
        ];

        for (const module of coreModules) {
            try {
                const moduleClass = await import(module.path);
                const instance = new moduleClass.default();
                this.modules.set(module.name, instance);
                this.log(`Core module '${module.name}' initialized`);
            } catch (error) {
                this.handleError(`Failed to load core module '${module.name}'`, error);
            }
        }
    }

    /**
     * Initialize UI components
     */
    async initializeUIComponents() {
        const uiComponents = [
            { name: 'mobileMenu', path: '../components/mobile-menu.js', selector: '.mobile-menu-toggle' },
            { name: 'bookingForm', path: '../components/booking-form.js', selector: '.consultation-form' },
            { name: 'treatmentFilter', path: '../components/treatment-filter.js', selector: '.treatment-filters' },
            { name: 'testimonialCarousel', path: '../components/testimonial-carousel.js', selector: '.testimonial-carousel' },
            { name: 'imageGallery', path: '../components/image-gallery.js', selector: '.image-gallery' },
            { name: 'scrollEffects', path: '../components/scroll-effects.js', selector: 'body' }
        ];

        for (const component of uiComponents) {
            try {
                // Check if component is needed on current page
                if (document.querySelector(component.selector)) {
                    const componentClass = await import(component.path);
                    const instance = new componentClass.default(component.selector);
                    this.modules.set(component.name, instance);

                    // Initialize the component
                    if (typeof instance.init === 'function') {
                        await instance.init();
                    }

                    this.log(`UI component '${component.name}' initialized`);
                }
            } catch (error) {
                this.handleError(`Failed to load UI component '${component.name}'`, error);
            }
        }
    }

    /**
     * Setup global event listeners
     */
    setupGlobalListeners() {
        // Keyboard navigation
        document.addEventListener('keydown', this.handleGlobalKeydown.bind(this));

        // Click tracking for analytics
        document.addEventListener('click', this.handleGlobalClick.bind(this));

        // Form submissions
        document.addEventListener('submit', this.handleGlobalSubmit.bind(this));

        // Resize handler with throttling
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                this.handleResize();
            }, 150);
        });

        // Scroll handler with throttling
        let scrollTimeout;
        window.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                this.handleScroll();
            }, 16); // ~60fps
        });

        this.log('Global event listeners setup completed');
    }

    /**
     * Handle global keyboard events
     */
    handleGlobalKeydown(event) {
        // Escape key - close modals, menus
        if (event.key === 'Escape') {
            this.closeAllModals();
            this.closeAllMenus();
        }

        // Handle accessibility shortcuts
        if (event.altKey) {
            switch (event.key) {
                case 'm':
                    // Alt+M: Focus main navigation
                    event.preventDefault();
                    this.focusMainNavigation();
                    break;
                case 'c':
                    // Alt+C: Focus main content
                    event.preventDefault();
                    this.focusMainContent();
                    break;
                case 'b':
                    // Alt+B: Open booking form
                    event.preventDefault();
                    this.openBookingForm();
                    break;
            }
        }
    }

    /**
     * Handle global clicks for analytics
     */
    handleGlobalClick(event) {
        const target = event.target.closest('a, button, [data-track]');

        if (target) {
            // Track link clicks
            if (target.tagName === 'A') {
                this.trackEvent('link_click', {
                    url: target.href,
                    text: target.textContent.trim(),
                    location: window.location.pathname
                });
            }

            // Track button clicks
            if (target.tagName === 'BUTTON' || target.hasAttribute('data-track')) {
                this.trackEvent('button_click', {
                    action: target.getAttribute('data-track') || target.textContent.trim(),
                    location: window.location.pathname
                });
            }

            // Track CTA clicks
            if (target.classList.contains('btn-primary') || target.classList.contains('btn-phone')) {
                this.trackEvent('cta_click', {
                    type: target.classList.contains('btn-phone') ? 'phone' : 'booking',
                    text: target.textContent.trim(),
                    location: window.location.pathname
                });
            }
        }
    }

    /**
     * Handle global form submissions
     */
    handleGlobalSubmit(event) {
        const form = event.target;

        // Track form submissions
        this.trackEvent('form_submit', {
            formId: form.id || 'unnamed',
            formClass: form.className,
            location: window.location.pathname
        });

        // Add loading state
        const submitButton = form.querySelector('[type="submit"]');
        if (submitButton) {
            submitButton.classList.add('loading');
            submitButton.disabled = true;
        }
    }

    /**
     * Handle window resize
     */
    handleResize() {
        this.dispatchEvent('medSpaResize', {
            width: window.innerWidth,
            height: window.innerHeight,
            isMobile: window.innerWidth < 768
        });
    }

    /**
     * Handle window scroll
     */
    handleScroll() {
        const scrollY = window.scrollY;
        const scrollPercent = (scrollY / (document.body.scrollHeight - window.innerHeight)) * 100;

        this.dispatchEvent('medSpaScroll', {
            scrollY,
            scrollPercent,
            direction: this.lastScrollY > scrollY ? 'up' : 'down'
        });

        this.lastScrollY = scrollY;
    }

    /**
     * Initialize accessibility features
     */
    async initializeAccessibility() {
        // Skip links
        this.createSkipLinks();

        // Focus management
        this.setupFocusManagement();

        // Reduced motion handling
        this.handleReducedMotion();

        // High contrast handling
        this.handleHighContrast();

        this.log('Accessibility features initialized');
    }

    /**
     * Create skip links for accessibility
     */
    createSkipLinks() {
        const skipLinks = document.createElement('div');
        skipLinks.className = 'skip-links sr-only-focusable';
        skipLinks.innerHTML = `
            <a href="#main-navigation" class="skip-link">Skip to navigation</a>
            <a href="#main-content" class="skip-link">Skip to main content</a>
            <a href="#contact-form" class="skip-link">Skip to booking form</a>
        `;

        document.body.insertBefore(skipLinks, document.body.firstChild);
    }

    /**
     * Setup focus management
     */
    setupFocusManagement() {
        // Add focus indicators to dynamic elements
        document.addEventListener('focusin', (event) => {
            if (event.target.matches('button, a, input, select, textarea')) {
                event.target.classList.add('focused');
            }
        });

        document.addEventListener('focusout', (event) => {
            event.target.classList.remove('focused');
        });
    }

    /**
     * Handle reduced motion preference
     */
    handleReducedMotion() {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.documentElement.classList.add('reduce-motion');
            this.config.animations = false;
        }
    }

    /**
     * Handle high contrast preference
     */
    handleHighContrast() {
        if (window.matchMedia('(prefers-contrast: high)').matches) {
            document.documentElement.classList.add('high-contrast');
        }
    }

    /**
     * Initialize performance features
     */
    async initializePerformanceFeatures() {
        // Lazy loading
        const lazyLoadModule = await import('../modules/lazy-loading.js');
        const lazyLoader = new lazyLoadModule.default();
        this.modules.set('lazyLoader', lazyLoader);

        // Smooth scroll
        const smoothScrollModule = await import('../modules/smooth-scroll.js');
        const smoothScroll = new smoothScrollModule.default();
        this.modules.set('smoothScroll', smoothScroll);

        this.log('Performance features initialized');
    }

    /**
     * Initialize analytics
     */
    async initializeAnalytics() {
        try {
            const analyticsModule = await import('./analytics.js');
            const analytics = new analyticsModule.default();
            this.modules.set('analytics', analytics);

            // Track page view
            this.trackPageView();

            this.log('Analytics initialized');
        } catch (error) {
            this.handleError('Analytics initialization failed', error);
        }
    }

    /**
     * Optimize performance after load
     */
    optimizePerformance() {
        // Remove unused CSS (if implemented)
        this.removeUnusedCSS();

        // Preload critical resources
        this.preloadCriticalResources();

        // Setup intersection observers
        this.setupIntersectionObservers();

        this.log('Performance optimization completed');
    }

    /**
     * Utility methods
     */

    /**
     * Get module instance
     */
    getModule(name) {
        return this.modules.get(name);
    }

    /**
     * Dispatch custom event
     */
    dispatchEvent(eventName, detail = {}) {
        const event = new CustomEvent(eventName, { detail });
        document.dispatchEvent(event);
    }

    /**
     * Track analytics event
     */
    trackEvent(eventName, properties = {}) {
        const analytics = this.getModule('analytics');
        if (analytics && typeof analytics.track === 'function') {
            analytics.track(eventName, properties);
        }
    }

    /**
     * Track page view
     */
    trackPageView() {
        this.trackEvent('page_view', {
            url: window.location.href,
            title: document.title,
            referrer: document.referrer
        });
    }

    /**
     * Performance marking
     */
    mark(name) {
        const now = performance.now();
        this.performance.marks.set(name, now);

        if (this.config.performance) {
            performance.mark(`medSpa:${name}`);
        }
    }

    /**
     * Get performance metrics
     */
    getPerformanceMetrics() {
        const metrics = {};

        for (const [name, time] of this.performance.marks) {
            metrics[name] = Math.round(time - this.performance.startTime);
        }

        return metrics;
    }

    /**
     * Logging utility
     */
    log(message, data = null) {
        if (this.config.debug) {
            console.log(`[MedicalSpaApp] ${message}`, data || '');
        }
    }

    /**
     * Error handling
     */
    handleError(message, error) {
        console.error(`[MedicalSpaApp] ${message}:`, error);

        // Track error for analytics
        this.trackEvent('javascript_error', {
            message,
            error: error.message,
            stack: error.stack,
            url: window.location.href
        });
    }

    /**
     * Helper methods for accessibility
     */
    closeAllModals() {
        document.querySelectorAll('[data-modal].active').forEach(modal => {
            modal.classList.remove('active');
        });
    }

    closeAllMenus() {
        document.querySelectorAll('[data-menu].open').forEach(menu => {
            menu.classList.remove('open');
        });
    }

    focusMainNavigation() {
        const nav = document.querySelector('#main-navigation, nav[role="navigation"]');
        if (nav) nav.focus();
    }

    focusMainContent() {
        const main = document.querySelector('#main-content, main');
        if (main) main.focus();
    }

    openBookingForm() {
        const bookingForm = this.getModule('bookingForm');
        if (bookingForm && typeof bookingForm.open === 'function') {
            bookingForm.open();
        }
    }

    /**
     * Placeholder methods for future implementation
     */
    removeUnusedCSS() {
        // TODO: Implement CSS optimization
    }

    preloadCriticalResources() {
        // TODO: Implement resource preloading
    }

    setupIntersectionObservers() {
        // TODO: Implement intersection observers for animations
    }
}

/**
 * Initialize the application when the script loads
 */
const medicalSpaApp = new MedicalSpaApp();

// Export for module usage
export default MedicalSpaApp;

// Auto-initialize for direct script usage
if (typeof module === 'undefined') {
    medicalSpaApp.init();
}

// Make available globally for debugging
if (typeof window !== 'undefined') {
    window.MedicalSpaApp = medicalSpaApp;
}
