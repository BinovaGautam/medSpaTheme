/**
 * Medical Spa Theme - Core Application
 *
 * Main application orchestrator for the medical spa WordPress theme.
 * Handles component initialization, performance monitoring, and global functionality.
 *
 * @author PreetiDreams Development Team
 * @version 2.0.0
 */

'use strict';

class MedicalSpaApp {
    constructor() {
        this.version = '2.0.0';
        this.debug = window.location.hostname === 'localhost' || window.location.hostname.includes('dev');
        this.components = new Map();
        this.modules = new Map();
        this.performance = {
            startTime: performance.now(),
            marks: new Map(),
            metrics: new Map()
        };

        // Configuration from WordPress
        this.config = window.medicalSpaTheme || {
            ajaxUrl: '/wp-admin/admin-ajax.php',
            nonce: '',
            version: '1.0.0',
            components: {},
            settings: {}
        };

        // Bind methods
        this.init = this.init.bind(this);
        this.markPerformance = this.markPerformance.bind(this);
        this.measurePerformance = this.measurePerformance.bind(this);
        this.logPerformance = this.logPerformance.bind(this);

        // Auto-initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', this.init);
        } else {
            this.init();
        }
    }

    /**
     * Initialize the application
     */
    async init() {
        try {
            this.markPerformance('app-init-start');

            console.log(`🏥 Medical Spa Theme v${this.version} initializing...`);

            // Set up global event listeners
            this.setupGlobalEvents();

            // Initialize core functionality
            this.setupAccessibility();
            this.setupAnalytics();
            this.setupErrorHandling();

            // Initialize modules
            await this.initializeModules();

            // Load and initialize components
            await this.loadComponents();

            this.markPerformance('app-init-end');
            this.measurePerformance('app-initialization', 'app-init-start', 'app-init-end');

            console.log(`✅ Medical Spa Theme initialized successfully in ${this.getMetric('app-initialization')}ms`);

            // Log performance metrics
            if (this.debug) {
                this.logPerformance();
            }

            // Dispatch custom event
            this.dispatchEvent('medicalSpaAppReady', {
                version: this.version,
                components: Array.from(this.components.keys()),
                initTime: this.getMetric('app-initialization')
            });

        } catch (error) {
            console.error('❌ Medical Spa Theme initialization failed:', error);
            this.handleError(error, 'app_initialization_failed');
        }
    }

    /**
     * Load component scripts dynamically
     */
    async loadComponents() {
        this.markPerformance('components-load-start');

        const componentPromises = [];

        // Load mobile menu component
        if (this.config.components?.mobileMenu !== false) {
            componentPromises.push(this.loadComponent('mobile-menu'));
        }

        // Load treatment filter component
        if (this.config.components?.treatmentFilter) {
            componentPromises.push(this.loadComponent('treatment-filter'));
        }

        // Wait for all components to load
        await Promise.all(componentPromises);

        // Initialize mobile menu after components are loaded
        this.initializeMobileMenu();

        this.markPerformance('components-load-end');
        this.measurePerformance('components-loading', 'components-load-start', 'components-load-end');

        console.log(`📦 Components loaded in ${this.getMetric('components-loading')}ms`);
    }

    /**
     * Initialize mobile menu functionality
     */
    initializeMobileMenu() {
        const mobileToggle = document.querySelector('.mobile-menu-toggle');
        const mobileMenu = document.querySelector('.mobile-menu');
        const mobileOverlay = document.querySelector('.mobile-menu-overlay');
        const mobileClose = document.querySelector('.mobile-menu-close');

        if (!mobileToggle || !mobileMenu || !mobileOverlay) {
            console.warn('Mobile menu elements not found');
            return;
        }

        let isOpen = false;

        // Toggle function
        const toggleMenu = () => {
            isOpen = !isOpen;

            if (isOpen) {
                // Open menu
                mobileMenu.classList.add('open');
                mobileOverlay.classList.add('open');
                document.body.classList.add('mobile-menu-open');
                mobileToggle.setAttribute('aria-expanded', 'true');
                mobileMenu.setAttribute('aria-hidden', 'false');
                mobileOverlay.setAttribute('aria-hidden', 'false');

                // Focus first menu item
                const firstMenuItem = mobileMenu.querySelector('a');
                if (firstMenuItem) {
                    setTimeout(() => firstMenuItem.focus(), 100);
                }
            } else {
                // Close menu
                mobileMenu.classList.remove('open');
                mobileOverlay.classList.remove('open');
                document.body.classList.remove('mobile-menu-open');
                mobileToggle.setAttribute('aria-expanded', 'false');
                mobileMenu.setAttribute('aria-hidden', 'true');
                mobileOverlay.setAttribute('aria-hidden', 'true');

                // Return focus to toggle button
                mobileToggle.focus();
            }
        };

        // Event listeners
        mobileToggle.addEventListener('click', (e) => {
            e.preventDefault();
            toggleMenu();
        });

        if (mobileClose) {
            mobileClose.addEventListener('click', (e) => {
                e.preventDefault();
                if (isOpen) toggleMenu();
            });
        }

        // Close on overlay click
        mobileOverlay.addEventListener('click', (e) => {
            if (e.target === mobileOverlay && isOpen) {
                toggleMenu();
            }
        });

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && isOpen) {
                toggleMenu();
            }
        });

        // Close on menu item click (for single page apps)
        const menuItems = mobileMenu.querySelectorAll('a');
        menuItems.forEach(item => {
            item.addEventListener('click', () => {
                // Close menu after a short delay to allow navigation
                setTimeout(() => {
                    if (isOpen) toggleMenu();
                }, 150);
            });
        });

        console.log('📱 Mobile menu initialized successfully');

        // Store mobile menu instance
        this.components.set('mobile-menu', {
            name: 'mobile-menu',
            loaded: true,
            initialized: true,
            toggle: toggleMenu,
            isOpen: () => isOpen
        });
    }

    /**
     * Load individual component
     */
    async loadComponent(componentName) {
        try {
            // Components are already loaded via WordPress wp_enqueue_script
            // Just register them as available
            this.components.set(componentName, {
                name: componentName,
                loaded: true,
                initialized: false
            });

            console.log(`✅ Component registered: ${componentName}`);

        } catch (error) {
            console.error(`❌ Failed to load component: ${componentName}`, error);
        }
    }

    /**
     * Initialize core modules
     */
    async initializeModules() {
        // Performance monitoring module
        this.modules.set('performance', {
            mark: this.markPerformance,
            measure: this.measurePerformance,
            getMetric: this.getMetric.bind(this),
            getMetrics: () => this.performance.metrics
        });

        // Analytics module
        this.modules.set('analytics', {
            track: this.trackEvent.bind(this),
            page: this.trackPageView.bind(this),
            conversion: this.trackConversion.bind(this)
        });

        // Accessibility module
        this.modules.set('accessibility', {
            manageFocus: this.manageFocus.bind(this),
            announceToScreenReader: this.announceToScreenReader.bind(this),
            setupSkipLinks: this.setupSkipLinks.bind(this)
        });

        console.log('🔧 Core modules initialized');
    }

    /**
     * Set up global event listeners
     */
    setupGlobalEvents() {
        // Responsive design handlers
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                this.dispatchEvent('responsiveBreakpointChange', {
                    width: window.innerWidth,
                    height: window.innerHeight,
                    breakpoint: this.getCurrentBreakpoint()
                });
            }, 250);
        });

        // Smart header scroll behavior
        this.setupSmartHeader();

        // Scroll performance optimization
        let scrollTimeout;
        let isScrolling = false;
        window.addEventListener('scroll', () => {
            if (!isScrolling) {
                isScrolling = true;
                this.dispatchEvent('scrollStart');
            }

            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                isScrolling = false;
                this.dispatchEvent('scrollEnd');
            }, 150);

            // Update header on scroll
            this.updateHeaderOnScroll();
        }, { passive: true });

        // Global click handler
        document.addEventListener('click', this.handleGlobalClick);

        // Global keyboard handler
        document.addEventListener('keydown', this.handleGlobalKeyboard);

        console.log('🌐 Global event listeners set up');
    }

    /**
     * Set up smart header scroll behavior
     */
    setupSmartHeader() {
        this.header = {
            element: document.querySelector('.professional-header'),
            lastScrollY: 0,
            isHidden: false,
            isScrolled: false,
            ticking: false
        };

        if (!this.header.element) {
            console.warn('Header element not found');
            return;
        }

        // Ensure body has the layout-stable class for smooth transitions
        document.body.classList.add('layout-stable');

        // Add resize handler to update header behavior on orientation change
        let resizeHeaderTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeHeaderTimeout);
            resizeHeaderTimeout = setTimeout(() => {
                // Reset header state on resize to prevent layout issues
                if (this.header.isHidden) {
                    this.header.element.classList.remove('hidden');
                    this.header.element.classList.add('visible');
                    this.header.isHidden = false;
                }
            }, 100);
        });

        console.log('🎯 Smart header initialized with responsive support');
    }

    /**
     * Update header state based on scroll position
     */
    updateHeaderOnScroll() {
        if (!this.header.element || this.header.ticking) return;

        this.header.ticking = true;

        requestAnimationFrame(() => {
            const currentScrollY = window.pageYOffset;
            const scrollingDown = currentScrollY > this.header.lastScrollY;
            const scrollingUp = currentScrollY < this.header.lastScrollY;
            const scrollThreshold = 50;
            const hideThreshold = 120;

            // Header separation and body class management
            if (currentScrollY > scrollThreshold) {
                if (!this.header.isScrolled) {
                    this.header.element.classList.add('scrolled');
                    document.body.classList.add('header-scrolled');
                    this.header.isScrolled = true;
                }
            } else {
                if (this.header.isScrolled) {
                    this.header.element.classList.remove('scrolled');
                    document.body.classList.remove('header-scrolled');
                    this.header.isScrolled = false;
                }
            }

            // Hide/show header based on scroll direction
            if (currentScrollY > hideThreshold) {
                if (scrollingDown && !this.header.isHidden && (currentScrollY - this.header.lastScrollY) > 5) {
                    // Hide header when scrolling down with momentum
                    this.header.element.classList.add('hidden');
                    this.header.element.classList.remove('visible');
                    this.header.isHidden = true;

                    // Dispatch event for other components
                    this.dispatchEvent('headerHidden');
                } else if (scrollingUp && this.header.isHidden && (this.header.lastScrollY - currentScrollY) > 3) {
                    // Show header when scrolling up with momentum
                    this.header.element.classList.remove('hidden');
                    this.header.element.classList.add('visible');
                    this.header.isHidden = false;

                    // Dispatch event for other components
                    this.dispatchEvent('headerVisible');
                }
            } else if (currentScrollY <= hideThreshold && this.header.isHidden) {
                // Always show header when near top
                this.header.element.classList.remove('hidden');
                this.header.element.classList.add('visible');
                this.header.isHidden = false;

                this.dispatchEvent('headerVisible');
            }

            // Update last scroll position
            this.header.lastScrollY = Math.max(0, currentScrollY);
            this.header.ticking = false;
        });
    }

    /**
     * Handle global click events
     */
    handleGlobalClick(event) {
        const target = event.target.closest('[data-track]');
        if (target) {
            const trackingData = target.dataset.track;
            try {
                const data = JSON.parse(trackingData);
                this.trackEvent('element_clicked', {
                    ...data,
                    element: target.tagName.toLowerCase(),
                    text: target.textContent?.trim().substring(0, 50)
                });
            } catch (e) {
                this.trackEvent('element_clicked', {
                    action: trackingData,
                    element: target.tagName.toLowerCase()
                });
            }
        }

        // Track CTA button clicks
        if (target && target.classList.contains('btn')) {
            this.trackEvent('cta_clicked', {
                button_text: target.textContent?.trim(),
                button_class: target.className,
                page_location: window.location.pathname
            });
        }
    }

    /**
     * Handle global keyboard events
     */
    handleGlobalKeyboard(event) {
        // Skip links (Alt + S)
        if (event.altKey && event.key === 's') {
            event.preventDefault();
            this.focusSkipLink();
            return;
        }

        // Help modal (Alt + H)
        if (event.altKey && event.key === 'h') {
            event.preventDefault();
            this.showKeyboardShortcuts();
            return;
        }

        // Mobile menu (Alt + M)
        if (event.altKey && event.key === 'm') {
            event.preventDefault();
            this.dispatchEvent('toggleMobileMenu');
            return;
        }
    }

    /**
     * Accessibility setup
     */
    setupAccessibility() {
        // Create skip links
        this.setupSkipLinks();

        // Manage focus for keyboard users
        this.setupFocusManagement();

        // ARIA live region for announcements
        this.createAriaLiveRegion();

        console.log('♿ Accessibility features initialized');
    }

    /**
     * Create skip links for keyboard navigation
     */
    setupSkipLinks() {
        const skipLinksHTML = `
            <div class="skip-links sr-only-focusable" role="navigation" aria-label="Skip links">
                <a href="#main" class="skip-link">Skip to main content</a>
                <a href="#navigation" class="skip-link">Skip to navigation</a>
                <a href="#consultation" class="skip-link">Skip to consultation form</a>
            </div>
        `;

        document.body.insertAdjacentHTML('afterbegin', skipLinksHTML);
    }

    /**
     * Focus management for accessibility
     */
    setupFocusManagement() {
        // Trap focus in modals
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Tab') {
                const modal = document.querySelector('.modal:not(.hidden)');
                if (modal) {
                    this.trapFocus(event, modal);
                }
            }
        });
    }

    /**
     * Create ARIA live region for screen reader announcements
     */
    createAriaLiveRegion() {
        const liveRegion = document.createElement('div');
        liveRegion.setAttribute('aria-live', 'polite');
        liveRegion.setAttribute('aria-atomic', 'true');
        liveRegion.className = 'sr-only';
        liveRegion.id = 'aria-live-region';
        document.body.appendChild(liveRegion);
    }

    /**
     * Announce message to screen readers
     */
    announceToScreenReader(message) {
        const liveRegion = document.getElementById('aria-live-region');
        if (liveRegion) {
            liveRegion.textContent = message;
            setTimeout(() => {
                liveRegion.textContent = '';
            }, 1000);
        }
    }

    /**
     * Focus skip link
     */
    focusSkipLink() {
        const skipLink = document.querySelector('.skip-link');
        if (skipLink) {
            skipLink.focus();
        }
    }

    /**
     * Show keyboard shortcuts help
     */
    showKeyboardShortcuts() {
        const shortcuts = [
            'Alt + S: Focus skip links',
            'Alt + M: Toggle mobile menu',
            'Alt + H: Show this help',
            'Escape: Close modals/menus'
        ];

        this.announceToScreenReader('Keyboard shortcuts: ' + shortcuts.join(', '));

        if (this.debug) {
            console.log('⌨️ Keyboard Shortcuts:', shortcuts);
        }
    }

    /**
     * Trap focus within element
     */
    trapFocus(event, container) {
        const focusableElements = container.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );

        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        if (event.shiftKey) {
            if (document.activeElement === firstElement) {
                event.preventDefault();
                lastElement.focus();
            }
        } else {
            if (document.activeElement === lastElement) {
                event.preventDefault();
                firstElement.focus();
            }
        }
    }

    /**
     * Focus management helper
     */
    manageFocus(element, options = {}) {
        if (typeof element === 'string') {
            element = document.querySelector(element);
        }

        if (element) {
            if (options.preventScroll) {
                element.focus({ preventScroll: true });
            } else {
                element.focus();
            }

            if (options.announce) {
                this.announceToScreenReader(options.announce);
            }
        }
    }

    /**
     * Analytics setup
     */
    setupAnalytics() {
        // Track page view
        this.trackPageView();

        // Set up conversion tracking
        this.setupConversionTracking();

        console.log('📊 Analytics initialized');
    }

    /**
     * Track page view
     */
    trackPageView() {
        this.trackEvent('page_view', {
            page_title: document.title,
            page_location: window.location.href,
            page_path: window.location.pathname,
            referrer: document.referrer
        });
    }

    /**
     * Track custom event
     */
    trackEvent(eventName, properties = {}) {
        const eventData = {
            event: eventName,
            timestamp: Date.now(),
            session_id: this.getSessionId(),
            user_agent: navigator.userAgent,
            viewport: {
                width: window.innerWidth,
                height: window.innerHeight
            },
            ...properties
        };

        // Send to analytics service (Google Analytics, etc.)
        if (typeof gtag !== 'undefined') {
            gtag('event', eventName, properties);
        }

        // Log in debug mode
        if (this.debug) {
            console.log('📊 Analytics Event:', eventName, eventData);
        }

        // Store locally for debugging
        this.storeAnalyticsEvent(eventData);
    }

    /**
     * Track conversion events
     */
    trackConversion(type, data = {}) {
        this.trackEvent('conversion', {
            conversion_type: type,
            conversion_value: data.value || 0,
            conversion_category: data.category || 'medical_spa',
            ...data
        });
    }

    /**
     * Set up conversion tracking for forms
     */
    setupConversionTracking() {
        // Track consultation form submissions
        document.addEventListener('submit', (event) => {
            const form = event.target;
            if (form.matches('.consultation-form, [data-track-conversion]')) {
                this.trackConversion('form_submission', {
                    form_type: form.dataset.formType || 'consultation',
                    form_location: window.location.pathname
                });
            }
        });

        // Track phone number clicks
        document.addEventListener('click', (event) => {
            if (event.target.matches('a[href^="tel:"]')) {
                this.trackConversion('phone_call', {
                    phone_number: event.target.href.replace('tel:', ''),
                    source_location: window.location.pathname
                });
            }
        });
    }

    /**
     * Error handling setup
     */
    setupErrorHandling() {
        window.addEventListener('error', (event) => {
            this.handleError(event.error, 'javascript_error', {
                filename: event.filename,
                lineno: event.lineno,
                colno: event.colno
            });
        });

        window.addEventListener('unhandledrejection', (event) => {
            this.handleError(event.reason, 'unhandled_promise_rejection');
        });

        console.log('🛠️ Error handling initialized');
    }

    /**
     * Handle errors
     */
    handleError(error, type, additionalData = {}) {
        const errorData = {
            error_type: type,
            error_message: error.message || error.toString(),
            error_stack: error.stack,
            timestamp: Date.now(),
            user_agent: navigator.userAgent,
            url: window.location.href,
            ...additionalData
        };

        // Log error
        console.error(`❌ ${type}:`, error);

        // Track error in analytics
        this.trackEvent('error_occurred', errorData);

        // Store error for debugging
        this.storeError(errorData);
    }

    /**
     * Performance monitoring methods
     */
    markPerformance(name) {
        this.performance.marks.set(name, performance.now());
        if (performance.mark) {
            performance.mark(name);
        }
    }

    measurePerformance(name, startMark, endMark) {
        const startTime = this.performance.marks.get(startMark);
        const endTime = this.performance.marks.get(endMark);

        if (startTime && endTime) {
            const duration = endTime - startTime;
            this.performance.metrics.set(name, duration);

            if (performance.measure) {
                performance.measure(name, startMark, endMark);
            }

            return duration;
        }

        return null;
    }

    getMetric(name) {
        return this.performance.metrics.get(name);
    }

    logPerformance() {
        console.group('📈 Performance Metrics');
        this.performance.metrics.forEach((value, key) => {
            console.log(`${key}: ${value.toFixed(2)}ms`);
        });
        console.groupEnd();
    }

    /**
     * Utility methods
     */
    getCurrentBreakpoint() {
        const width = window.innerWidth;
        if (width < 576) return 'xs';
        if (width < 768) return 'sm';
        if (width < 992) return 'md';
        if (width < 1200) return 'lg';
        if (width < 1400) return 'xl';
        return '2xl';
    }

    getSessionId() {
        let sessionId = sessionStorage.getItem('medical_spa_session_id');
        if (!sessionId) {
            sessionId = 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            sessionStorage.setItem('medical_spa_session_id', sessionId);
        }
        return sessionId;
    }

    storeAnalyticsEvent(eventData) {
        try {
            const events = JSON.parse(localStorage.getItem('medical_spa_analytics') || '[]');
            events.push(eventData);
            // Keep only last 100 events
            if (events.length > 100) {
                events.splice(0, events.length - 100);
            }
            localStorage.setItem('medical_spa_analytics', JSON.stringify(events));
        } catch (e) {
            console.warn('Could not store analytics event:', e);
        }
    }

    storeError(errorData) {
        try {
            const errors = JSON.parse(localStorage.getItem('medical_spa_errors') || '[]');
            errors.push(errorData);
            // Keep only last 20 errors
            if (errors.length > 20) {
                errors.splice(0, errors.length - 20);
            }
            localStorage.setItem('medical_spa_errors', JSON.stringify(errors));
        } catch (e) {
            console.warn('Could not store error:', e);
        }
    }

    /**
     * Custom event system
     */
    dispatchEvent(eventName, detail = {}) {
        const customEvent = new CustomEvent(`medicalSpa:${eventName}`, {
            detail: {
                timestamp: Date.now(),
                source: 'MedicalSpaApp',
                ...detail
            },
            bubbles: true,
            cancelable: true
        });

        document.dispatchEvent(customEvent);

        if (this.debug) {
            console.log(`🎪 Event dispatched: medicalSpa:${eventName}`, detail);
        }
    }

    addEventListener(eventName, callback) {
        document.addEventListener(`medicalSpa:${eventName}`, callback);
    }

    removeEventListener(eventName, callback) {
        document.removeEventListener(`medicalSpa:${eventName}`, callback);
    }

    /**
     * Public API methods
     */
    getComponent(name) {
        return this.components.get(name);
    }

    getModule(name) {
        return this.modules.get(name);
    }

    getConfig() {
        return { ...this.config };
    }

    getVersion() {
        return this.version;
    }

    isDebug() {
        return this.debug;
    }

    destroy() {
        // Clean up event listeners and references
        this.components.clear();
        this.modules.clear();
        console.log('🏥 Medical Spa App destroyed');
    }
}

// Initialize the application
const medicalSpaApp = new MedicalSpaApp();

// Make app available globally
window.MedicalSpaApp = medicalSpaApp;

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = MedicalSpaApp;
}
