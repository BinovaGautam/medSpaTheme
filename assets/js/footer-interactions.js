/**
 * Luxury Medical Spa Footer Interactions
 * PreetiDreams Theme - Enhanced UX & Accessibility
 *
 * @version 1.0.0
 * @description Complete footer interaction handling
 * @accessibility WCAG AAA compliant interactions
 * @performance Optimized for mobile and desktop
 */

(function() {
    'use strict';

    // ==========================================================================
    // Configuration & Constants
    // ==========================================================================

    const FOOTER_CONFIG = {
        animationDuration: 300,
        scrollOffset: 100,
        debounceDelay: 150,
        mobileBreakpoint: 768,
        reducedMotionQuery: '(prefers-reduced-motion: reduce)',
        highContrastQuery: '(prefers-contrast: high)'
    };

    // ==========================================================================
    // Utility Functions
    // ==========================================================================

    /**
     * Debounce function for performance optimization
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    /**
     * Check if user prefers reduced motion
     */
    function prefersReducedMotion() {
        return window.matchMedia && window.matchMedia(FOOTER_CONFIG.reducedMotionQuery).matches;
    }

    /**
     * Check if device is mobile
     */
    function isMobile() {
        return window.innerWidth < FOOTER_CONFIG.mobileBreakpoint;
    }

    /**
     * Smooth scroll to top with accessibility considerations
     */
    function scrollToTop() {
        const startPosition = window.pageYOffset;
        const duration = prefersReducedMotion() ? 0 : FOOTER_CONFIG.animationDuration;

        if (duration === 0) {
            window.scrollTo(0, 0);
            return;
        }

        const startTime = performance.now();

        function animateScroll(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            // Easing function for smooth animation
            const easeOutCubic = 1 - Math.pow(1 - progress, 3);

            window.scrollTo(0, startPosition * (1 - easeOutCubic));

            if (progress < 1) {
                requestAnimationFrame(animateScroll);
            }
        }

        requestAnimationFrame(animateScroll);
    }

    // ==========================================================================
    // Back to Top Button Enhancement
    // ==========================================================================

    class BackToTopButton {
        constructor() {
            this.button = document.querySelector('.back-to-top');
            this.footer = document.querySelector('.luxury-footer');
            this.isVisible = false;

            if (this.button && this.footer) {
                this.init();
            }
        }

        init() {
            this.bindEvents();
            this.checkVisibility();
        }

        bindEvents() {
            // Handle button click
            this.button.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleClick();
            });

            // Handle keyboard activation
            this.button.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.handleClick();
                }
            });

            // Handle scroll visibility
            window.addEventListener('scroll', debounce(() => {
                this.checkVisibility();
            }, FOOTER_CONFIG.debounceDelay));

            // Handle resize for mobile optimization
            window.addEventListener('resize', debounce(() => {
                this.optimizeForDevice();
            }, FOOTER_CONFIG.debounceDelay));
        }

        handleClick() {
            // Add visual feedback
            this.button.style.transform = 'scale(0.95)';

            setTimeout(() => {
                this.button.style.transform = '';
            }, 150);

            scrollToTop();

            // Announce to screen readers
            this.announceToScreenReader('Scrolling to top of page');
        }

        checkVisibility() {
            const footerRect = this.footer.getBoundingClientRect();
            const windowHeight = window.innerHeight;

            // Show button when footer is partially or fully visible
            const shouldBeVisible = footerRect.top < windowHeight;

            if (shouldBeVisible !== this.isVisible) {
                this.isVisible = shouldBeVisible;
                this.toggleVisibility();
            }
        }

        toggleVisibility() {
            if (prefersReducedMotion()) {
                this.button.style.opacity = this.isVisible ? '1' : '0';
                return;
            }

            if (this.isVisible) {
                this.button.style.opacity = '1';
                this.button.style.transform = 'translateY(0)';
            } else {
                this.button.style.opacity = '0';
                this.button.style.transform = 'translateY(10px)';
            }
        }

        optimizeForDevice() {
            if (isMobile()) {
                this.button.style.width = '48px';
                this.button.style.height = '48px';
                this.button.style.fontSize = '1.125rem';
            } else {
                this.button.style.width = '56px';
                this.button.style.height = '56px';
                this.button.style.fontSize = '1.25rem';
            }
        }

        announceToScreenReader(message) {
            const announcement = document.createElement('div');
            announcement.setAttribute('aria-live', 'polite');
            announcement.setAttribute('aria-atomic', 'true');
            announcement.className = 'sr-only';
            announcement.textContent = message;

            document.body.appendChild(announcement);

            setTimeout(() => {
                document.body.removeChild(announcement);
            }, 1000);
        }
    }

    // ==========================================================================
    // CTA Button Enhancements
    // ==========================================================================

    class CTAButtonEnhancer {
        constructor() {
            this.primaryCTA = document.querySelector('.cta-primary');
            this.secondaryCTA = document.querySelector('.cta-secondary');

            if (this.primaryCTA || this.secondaryCTA) {
                this.init();
            }
        }

        init() {
            this.enhanceButtons();
            this.addAnalytics();
        }

        enhanceButtons() {
            [this.primaryCTA, this.secondaryCTA].forEach(button => {
                if (!button) return;

                // Enhanced keyboard navigation
                button.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.triggerButtonAction(button);
                    }
                });

                // Visual feedback on interaction
                button.addEventListener('mousedown', () => {
                    this.addPressedState(button);
                });

                button.addEventListener('mouseup', () => {
                    this.removePressedState(button);
                });

                button.addEventListener('mouseleave', () => {
                    this.removePressedState(button);
                });

                // Loading state simulation
                button.addEventListener('click', () => {
                    this.showLoadingState(button);
                });
            });
        }

        triggerButtonAction(button) {
            button.click();
        }

        addPressedState(button) {
            if (prefersReducedMotion()) return;

            button.style.transform = button.classList.contains('cta-primary')
                ? 'translateY(0)'
                : 'scale(0.98)';
        }

        removePressedState(button) {
            if (prefersReducedMotion()) return;

            button.style.transform = '';
        }

        showLoadingState(button) {
            const originalContent = button.innerHTML;
            const loadingText = button.classList.contains('cta-primary')
                ? 'Connecting...'
                : 'Dialing...';

            button.innerHTML = `<span class="cta-icon" aria-hidden="true">⏳</span><span>${loadingText}</span>`;
            button.disabled = true;

            // Reset after a short delay (simulating connection)
            setTimeout(() => {
                button.innerHTML = originalContent;
                button.disabled = false;
            }, 1500);
        }

        addAnalytics() {
            // Track CTA interactions for analytics
            [this.primaryCTA, this.secondaryCTA].forEach(button => {
                if (!button) return;

                button.addEventListener('click', (e) => {
                    const ctaType = button.classList.contains('cta-primary') ? 'primary' : 'secondary';
                    const action = button.classList.contains('cta-primary') ? 'schedule_consultation' : 'call_practice';

                    // Custom event for analytics tracking
                    window.dispatchEvent(new CustomEvent('footer_cta_click', {
                        detail: {
                            type: ctaType,
                            action: action,
                            position: 'footer',
                            timestamp: Date.now()
                        }
                    }));
                });
            });
        }
    }

    // ==========================================================================
    // Contact Information Enhancement
    // ==========================================================================

    class ContactEnhancer {
        constructor() {
            this.phoneLinks = document.querySelectorAll('a[href^="tel:"]');
            this.emailLinks = document.querySelectorAll('a[href^="mailto:"]');
            this.locationLinks = document.querySelectorAll('.location-cta');

            this.init();
        }

        init() {
            this.enhancePhoneLinks();
            this.enhanceEmailLinks();
            this.enhanceLocationLinks();
        }

        enhancePhoneLinks() {
            this.phoneLinks.forEach(link => {
                link.addEventListener('click', () => {
                    this.trackInteraction('phone_click', link.href);
                });

                // Add click-to-call indication on mobile
                if (isMobile()) {
                    link.setAttribute('title', 'Tap to call');
                }
            });
        }

        enhanceEmailLinks() {
            this.emailLinks.forEach(link => {
                link.addEventListener('click', () => {
                    this.trackInteraction('email_click', link.href);
                });

                link.setAttribute('title', 'Send confidential email');
            });
        }

        enhanceLocationLinks() {
            this.locationLinks.forEach(link => {
                link.addEventListener('click', () => {
                    this.trackInteraction('directions_click', link.href);
                });

                // Enhanced loading state for directions
                link.addEventListener('click', (e) => {
                    const originalText = link.querySelector('span:last-child').textContent;
                    link.querySelector('span:last-child').textContent = 'Opening Maps...';

                    setTimeout(() => {
                        link.querySelector('span:last-child').textContent = originalText;
                    }, 2000);
                });
            });
        }

        trackInteraction(type, target) {
            window.dispatchEvent(new CustomEvent('footer_contact_interaction', {
                detail: {
                    type: type,
                    target: target,
                    timestamp: Date.now()
                }
            }));
        }
    }

    // ==========================================================================
    // Accessibility Enhancements
    // ==========================================================================

    class AccessibilityEnhancer {
        constructor() {
            this.footer = document.querySelector('.luxury-footer');

            if (this.footer) {
                this.init();
            }
        }

        init() {
            this.enhanceKeyboardNavigation();
            this.addSkipLinks();
            this.optimizeForScreenReaders();
            this.handleReducedMotion();
            this.handleHighContrast();
        }

        enhanceKeyboardNavigation() {
            // Ensure all interactive elements are keyboard accessible
            const interactiveElements = this.footer.querySelectorAll(
                'button, a, [tabindex]:not([tabindex="-1"])'
            );

            interactiveElements.forEach((element, index) => {
                // Add visual focus indicator enhancement
                element.addEventListener('focus', () => {
                    element.style.outline = '3px solid var(--premium-gold)';
                    element.style.outlineOffset = '2px';
                });

                element.addEventListener('blur', () => {
                    element.style.outline = '';
                    element.style.outlineOffset = '';
                });
            });
        }

        addSkipLinks() {
            // Add skip link for footer navigation
            const skipLink = document.createElement('a');
            skipLink.href = '#footer-nav';
            skipLink.textContent = 'Skip to footer navigation';
            skipLink.className = 'sr-only';
            skipLink.style.position = 'absolute';
            skipLink.style.top = '-40px';
            skipLink.style.left = '6px';
            skipLink.style.background = 'var(--premium-gold)';
            skipLink.style.color = 'var(--medical-navy)';
            skipLink.style.padding = '8px';
            skipLink.style.textDecoration = 'none';
            skipLink.style.zIndex = '1000';

            skipLink.addEventListener('focus', () => {
                skipLink.style.top = '6px';
            });

            skipLink.addEventListener('blur', () => {
                skipLink.style.top = '-40px';
            });

            this.footer.insertBefore(skipLink, this.footer.firstChild);
        }

        optimizeForScreenReaders() {
            // Add helpful landmarks and descriptions
            const practiceInfo = this.footer.querySelector('.practice-information');
            if (practiceInfo) {
                practiceInfo.setAttribute('aria-label', 'Practice contact information and credentials');
            }

            const ctaGroup = this.footer.querySelector('.cta-group');
            if (ctaGroup) {
                ctaGroup.setAttribute('aria-label', 'Consultation booking options');
            }

            // Add live region for dynamic content updates
            const liveRegion = document.createElement('div');
            liveRegion.setAttribute('aria-live', 'polite');
            liveRegion.setAttribute('aria-atomic', 'true');
            liveRegion.className = 'sr-only';
            liveRegion.id = 'footer-announcements';
            this.footer.appendChild(liveRegion);
        }

        handleReducedMotion() {
            if (prefersReducedMotion()) {
                this.footer.style.setProperty('--transition-smooth', 'none');
                this.footer.style.setProperty('--transition-gentle', 'none');
            }

            // Listen for changes in motion preference
            const mediaQuery = window.matchMedia(FOOTER_CONFIG.reducedMotionQuery);
            mediaQuery.addListener((e) => {
                if (e.matches) {
                    this.footer.style.setProperty('--transition-smooth', 'none');
                    this.footer.style.setProperty('--transition-gentle', 'none');
                } else {
                    this.footer.style.removeProperty('--transition-smooth');
                    this.footer.style.removeProperty('--transition-gentle');
                }
            });
        }

        handleHighContrast() {
            const highContrastQuery = window.matchMedia(FOOTER_CONFIG.highContrastQuery);

            const updateHighContrast = (matches) => {
                if (matches) {
                    this.footer.classList.add('high-contrast');
                } else {
                    this.footer.classList.remove('high-contrast');
                }
            };

            updateHighContrast(highContrastQuery.matches);
            highContrastQuery.addListener((e) => updateHighContrast(e.matches));
        }
    }

    // ==========================================================================
    // Performance Monitor
    // ==========================================================================

    class PerformanceMonitor {
        constructor() {
            this.metrics = {
                loadTime: 0,
                interactionCount: 0,
                lastInteraction: 0
            };

            this.init();
        }

        init() {
            this.measureLoadTime();
            this.trackInteractions();
        }

        measureLoadTime() {
            if (typeof performance !== 'undefined' && performance.timing) {
                window.addEventListener('load', () => {
                    this.metrics.loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
                });
            }
        }

        trackInteractions() {
            document.addEventListener('click', (e) => {
                if (e.target.closest('.luxury-footer')) {
                    this.metrics.interactionCount++;
                    this.metrics.lastInteraction = performance.now();
                }
            });
        }

        getMetrics() {
            return this.metrics;
        }
    }

    // ==========================================================================
    // Luxury Location Showcase Manager
    // ==========================================================================

    class LuxuryLocationShowcase {
        constructor() {
            this.mapContainer = document.getElementById('luxury-clinic-map');
            this.locationFeatures = document.querySelectorAll('.feature-item');
            this.locationCTAs = document.querySelectorAll('.location-cta-primary, .location-cta-secondary, .location-cta-tertiary');
            this.mapOverlay = document.querySelector('.map-overlay');
            this.clinicMarker = document.querySelector('.clinic-marker');

            this.init();
        }

        init() {
            if (!this.mapContainer) return;

            this.setupMapInteractions();
            this.setupLocationFeatureAnimations();
            this.setupLocationCTATracking();
            this.setupAccessibilityFeatures();
            this.observeLocationSection();
        }

        /**
         * Setup luxury map interactions
         */
        setupMapInteractions() {
            const iframe = this.mapContainer.querySelector('iframe');
            if (!iframe) return;

            // Enhanced map loading with luxury styling
            iframe.addEventListener('load', () => {
                this.mapContainer.classList.add('map-loaded');
                this.animateMarkerEntrance();
            });

            // Sophisticated hover interactions
            this.mapContainer.addEventListener('mouseenter', () => {
                this.mapContainer.classList.add('map-active');
                this.enhanceMarkerVisibility();
            });

            this.mapContainer.addEventListener('mouseleave', () => {
                this.mapContainer.classList.remove('map-active');
                this.resetMarkerVisibility();
            });

            // Accessibility: keyboard navigation for map
            this.mapContainer.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    this.openMapInNewTab();
                }
            });
        }

        /**
         * Animate clinic marker entrance
         */
        animateMarkerEntrance() {
            if (!this.clinicMarker) return;

            setTimeout(() => {
                this.clinicMarker.style.animation = 'markerSlideIn 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards';

                // Add CSS for slide-in animation
                const style = document.createElement('style');
                style.textContent = `
                    @keyframes markerSlideIn {
                        from {
                            transform: translateY(-100%) scale(0.8);
                            opacity: 0;
                        }
                        to {
                            transform: translateY(0) scale(1);
                            opacity: 1;
                        }
                    }
                `;
                document.head.appendChild(style);
            }, 500);
        }

        /**
         * Enhanced marker visibility on hover
         */
        enhanceMarkerVisibility() {
            if (this.clinicMarker) {
                this.clinicMarker.style.boxShadow = '0 12px 48px rgba(27, 54, 93, 0.4), 0 4px 16px rgba(212, 175, 55, 0.3)';
                this.clinicMarker.style.transform = 'scale(1.05)';
            }
        }

        /**
         * Reset marker visibility
         */
        resetMarkerVisibility() {
            if (this.clinicMarker) {
                this.clinicMarker.style.boxShadow = '';
                this.clinicMarker.style.transform = '';
            }
        }

        /**
         * Open map in new tab for better accessibility
         */
        openMapInNewTab() {
            const iframe = this.mapContainer.querySelector('iframe');
            if (iframe && iframe.src) {
                // Extract location from embed URL and create Google Maps link
                const googleMapsUrl = iframe.src.replace('/embed', '/');
                window.open(googleMapsUrl, '_blank', 'noopener,noreferrer');
            }
        }

        /**
         * Setup luxury location feature animations
         */
        setupLocationFeatureAnimations() {
            this.locationFeatures.forEach((feature, index) => {
                // Staggered entrance animation
                feature.style.opacity = '0';
                feature.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    feature.style.transition = 'all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                    feature.style.opacity = '1';
                    feature.style.transform = 'translateY(0)';
                }, index * 150 + 300);

                // Enhanced hover interactions
                feature.addEventListener('mouseenter', () => {
                    this.highlightFeature(feature);
                });

                feature.addEventListener('mouseleave', () => {
                    this.resetFeatureHighlight(feature);
                });

                // Keyboard accessibility
                feature.addEventListener('focus', () => {
                    this.highlightFeature(feature);
                });

                feature.addEventListener('blur', () => {
                    this.resetFeatureHighlight(feature);
                });
            });
        }

        /**
         * Highlight location feature with luxury styling
         */
        highlightFeature(feature) {
            const icon = feature.querySelector('.feature-icon');
            if (icon) {
                icon.style.transform = 'scale(1.2) rotate(5deg)';
                icon.style.color = 'var(--premium-gold)';
            }

            feature.style.background = 'linear-gradient(135deg, rgba(135, 169, 107, 0.08) 0%, rgba(212, 175, 55, 0.05) 100%)';
            feature.style.borderColor = 'rgba(135, 169, 107, 0.3)';
            feature.style.boxShadow = '0 8px 32px rgba(135, 169, 107, 0.15)';
        }

        /**
         * Reset feature highlight
         */
        resetFeatureHighlight(feature) {
            const icon = feature.querySelector('.feature-icon');
            if (icon) {
                icon.style.transform = '';
                icon.style.color = '';
            }

            feature.style.background = '';
            feature.style.borderColor = '';
            feature.style.boxShadow = '';
        }

        /**
         * Setup location CTA tracking and enhancements
         */
        setupLocationCTATracking() {
            this.locationCTAs.forEach(cta => {
                cta.addEventListener('click', (e) => {
                    const ctaType = cta.classList.contains('location-cta-primary') ? 'primary' :
                                   cta.classList.contains('location-cta-secondary') ? 'secondary' : 'tertiary';
                    const ctaText = cta.textContent.trim();

                    // Enhanced analytics tracking
                    this.trackLocationCTAClick(ctaType, ctaText, cta.href);

                    // Visual feedback
                    this.provideCTAFeedback(cta);
                });

                // Accessibility enhancements
                cta.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        cta.click();
                    }
                });
            });
        }

        /**
         * Track location CTA clicks for analytics
         */
        trackLocationCTAClick(type, text, href) {
            // Google Analytics 4 event
            if (typeof gtag !== 'undefined') {
                gtag('event', 'location_cta_click', {
                    event_category: 'Footer Location Showcase',
                    event_label: text,
                    cta_type: type,
                    destination_url: href,
                    section: 'luxury_location_showcase'
                });
            }

            // WordPress analytics hook
            if (typeof window.preetidreamsAnalytics !== 'undefined') {
                window.preetidreamsAnalytics.track('location_cta_interaction', {
                    type: type,
                    text: text,
                    href: href,
                    timestamp: new Date().toISOString()
                });
            }

            console.log('🏥 Location CTA Interaction:', { type, text, href });
        }

        /**
         * Provide visual feedback for CTA clicks
         */
        provideCTAFeedback(cta) {
            const originalText = cta.innerHTML;
            const icon = cta.querySelector('.cta-icon');

            if (icon) {
                icon.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    icon.style.transform = 'scale(1.1)';
                    setTimeout(() => {
                        icon.style.transform = '';
                    }, 150);
                }, 100);
            }

            // Ripple effect for primary CTA
            if (cta.classList.contains('location-cta-primary')) {
                this.createRippleEffect(cta);
            }
        }

        /**
         * Create luxury ripple effect
         */
        createRippleEffect(element) {
            const ripple = document.createElement('div');
            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(212, 175, 55, 0.3);
                transform: scale(0);
                animation: rippleAnimation 0.6s linear;
                pointer-events: none;
                top: 50%;
                left: 50%;
                width: 100px;
                height: 100px;
                margin: -50px 0 0 -50px;
            `;

            element.style.position = 'relative';
            element.style.overflow = 'hidden';
            element.appendChild(ripple);

            // Add ripple animation CSS
            if (!document.querySelector('#ripple-styles')) {
                const style = document.createElement('style');
                style.id = 'ripple-styles';
                style.textContent = `
                    @keyframes rippleAnimation {
                        to {
                            transform: scale(2);
                            opacity: 0;
                        }
                    }
                `;
                document.head.appendChild(style);
            }

            setTimeout(() => {
                ripple.remove();
            }, 600);
        }

        /**
         * Setup enhanced accessibility features
         */
        setupAccessibilityFeatures() {
            // Screen reader announcements for map interactions
            if (this.mapContainer) {
                const announcement = document.createElement('div');
                announcement.setAttribute('aria-live', 'polite');
                announcement.setAttribute('aria-atomic', 'true');
                announcement.className = 'sr-only';
                announcement.id = 'map-announcements';
                this.mapContainer.appendChild(announcement);

                // Announce map readiness
                setTimeout(() => {
                    announcement.textContent = 'Interactive map of clinic location is now available. Press Enter to open in new tab for full accessibility.';
                }, 1000);
            }

            // Enhanced keyboard navigation for location features
            this.locationFeatures.forEach((feature, index) => {
                feature.setAttribute('tabindex', '0');
                feature.setAttribute('role', 'button');
                feature.setAttribute('aria-describedby', `feature-description-${index}`);

                const description = feature.querySelector('.feature-content span');
                if (description) {
                    description.id = `feature-description-${index}`;
                }
            });

            // Location CTA accessibility enhancements
            this.locationCTAs.forEach(cta => {
                if (!cta.getAttribute('aria-label')) {
                    const text = cta.textContent.trim();
                    cta.setAttribute('aria-label', `${text} - Opens in ${cta.target === '_blank' ? 'new tab' : 'same tab'}`);
                }
            });
        }

        /**
         * Observe location section for entrance animations
         */
        observeLocationSection() {
            const locationSection = document.querySelector('.luxury-location-showcase');
            if (!locationSection || !window.IntersectionObserver) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.animateLocationSectionEntrance();
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.2,
                rootMargin: '0px 0px -50px 0px'
            });

            observer.observe(locationSection);
        }

        /**
         * Animate location section entrance
         */
        animateLocationSectionEntrance() {
            const locationHeadline = document.querySelector('.location-headline');
            const locationSubtext = document.querySelector('.location-subtext');
            const mapShowcase = document.querySelector('.map-showcase');

            // Staggered entrance animation
            [locationHeadline, locationSubtext, mapShowcase].forEach((element, index) => {
                if (element) {
                    element.style.opacity = '0';
                    element.style.transform = 'translateY(30px)';

                    setTimeout(() => {
                        element.style.transition = 'all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                        element.style.opacity = '1';
                        element.style.transform = 'translateY(0)';
                    }, index * 200);
                }
            });
        }
    }

    // ==========================================================================
    // Initialization & Error Handling
    // ==========================================================================

    function initializeFooterEnhancements() {
        try {
            // Initialize all enhancement modules
            new BackToTopButton();
            new CTAButtonEnhancer();
            new ContactEnhancer();
            new AccessibilityEnhancer();
            new PerformanceMonitor();
            new LuxuryLocationShowcase();

            // Dispatch initialization complete event
            window.dispatchEvent(new CustomEvent('footer_enhancements_ready', {
                detail: {
                    timestamp: Date.now(),
                    modules: ['BackToTopButton', 'CTAButtonEnhancer', 'ContactEnhancer', 'AccessibilityEnhancer', 'PerformanceMonitor', 'LuxuryLocationShowcase']
                }
            }));

        } catch (error) {
            console.warn('Footer enhancements initialization error:', error);

            // Fallback: ensure basic functionality still works
            const backToTopButton = document.querySelector('.back-to-top');
            if (backToTopButton) {
                backToTopButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }
        }
    }

    // ==========================================================================
    // DOM Ready Initialization
    // ==========================================================================

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeFooterEnhancements);
    } else {
        initializeFooterEnhancements();
    }

    // Export for testing and external access
    if (typeof window !== 'undefined') {
        window.FooterEnhancements = {
            BackToTopButton,
            CTAButtonEnhancer,
            ContactEnhancer,
            AccessibilityEnhancer,
            PerformanceMonitor,
            LuxuryLocationShowcase,
            init: initializeFooterEnhancements
        };
    }

})();
