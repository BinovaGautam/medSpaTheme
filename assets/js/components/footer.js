/**
 * Footer Component JavaScript
 *
 * Comprehensive JavaScript for FooterComponent with smooth scrolling,
 * accessibility enhancements, contact form validation, and interaction management.
 *
 * @package MedSpaTheme
 * @since 1.0.0
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 */

(function($) {
    'use strict';

    /**
     * FooterManager Class
     *
     * Manages footer component functionality including smooth scrolling,
     * accessibility enhancements, and contact information interactions.
     */
    class FooterManager {
        constructor() {
            this.footer = null;
            this.backToTopButton = null;
            this.ctaButtons = [];
            this.contactLinks = [];
            this.socialLinks = [];
            this.footerNavigation = null;
            this.isScrolling = false;
            this.lastScrollTop = 0;
            this.observers = [];

            // Configuration
            this.config = {
                scrollThreshold: 200,
                smoothScrollDuration: 800,
                fadeInOffset: 100,
                debounceDelay: 16,
                accessibility: {
                    announceScrollTop: true,
                    enhancedFocus: true,
                    reducedMotionSupport: true
                },
                analytics: {
                    trackCTAClicks: true,
                    trackSocialClicks: true,
                    trackContactClicks: true
                }
            };

            this.init();
        }

        /**
         * Initialize footer functionality
         */
        init() {
            $(document).ready(() => {
                this.setupFooterElements();
                this.bindEvents();
                this.initializeAccessibility();
                this.initializeAnalytics();
                this.handleReducedMotion();
                this.initializeIntersectionObserver();

                // Emit ready event
                $(document).trigger('footerManager:ready');
            });
        }

        /**
         * Setup footer DOM elements
         */
        setupFooterElements() {
            this.footer = $('.footer-component');

            if (!this.footer.length) {
                console.warn('FooterManager: Footer component not found');
                return;
            }

            // Cache important elements
            this.backToTopButton = this.footer.find('.back-to-top');
            this.ctaButtons = this.footer.find('.cta-primary, .cta-secondary');
            this.contactLinks = this.footer.find('.contact-link');
            this.socialLinks = this.footer.find('.social-link');
            this.footerNavigation = this.footer.find('.footer-nav');

            // Set initial states
            this.backToTopButton.attr('tabindex', '0');
            this.updateBackToTopVisibility();
        }

        /**
         * Bind event handlers
         */
        bindEvents() {
            // Back to top button
            this.backToTopButton.on('click', (e) => this.handleBackToTop(e));
            this.backToTopButton.on('keydown', (e) => this.handleBackToTopKeydown(e));

            // CTA button interactions
            this.ctaButtons.on('click', (e) => this.handleCTAClick(e));
            this.ctaButtons.on('keydown', (e) => this.handleCTAKeydown(e));

            // Contact link interactions
            this.contactLinks.on('click', (e) => this.handleContactClick(e));

            // Social link interactions
            this.socialLinks.on('click', (e) => this.handleSocialClick(e));

            // Scroll events for back to top visibility
            $(window).on('scroll', this.debounce(() => this.handleScroll(), this.config.debounceDelay));

            // Resize events
            $(window).on('resize', this.debounce(() => this.handleResize(), 250));

            // Focus events for accessibility
            this.footer.on('focusin', (e) => this.handleFocusIn(e));
            this.footer.on('focusout', (e) => this.handleFocusOut(e));

            // Keyboard navigation
            $(document).on('keydown', (e) => this.handleGlobalKeydown(e));
        }

        /**
         * Handle back to top button click
         */
        handleBackToTop(e) {
            e.preventDefault();

            this.trackEvent('back_to_top_click', {
                location: 'footer',
                scroll_position: window.pageYOffset
            });

            this.smoothScrollToTop();
        }

        /**
         * Handle back to top button keyboard navigation
         */
        handleBackToTopKeydown(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.handleBackToTop(e);
            }
        }

        /**
         * Smooth scroll to top of page
         */
        smoothScrollToTop() {
            const startPosition = window.pageYOffset;
            const startTime = performance.now();

            // Check for reduced motion preference
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const duration = prefersReducedMotion ? 0 : this.config.smoothScrollDuration;

            if (duration === 0) {
                window.scrollTo(0, 0);
                this.announceScrollTop();
                return;
            }

            const animateScroll = (currentTime) => {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);

                // Easing function (easeOutQuart)
                const easeProgress = 1 - Math.pow(1 - progress, 4);

                const currentPosition = startPosition * (1 - easeProgress);
                window.scrollTo(0, currentPosition);

                if (progress < 1) {
                    requestAnimationFrame(animateScroll);
                } else {
                    this.announceScrollTop();
                }
            };

            requestAnimationFrame(animateScroll);
        }

        /**
         * Announce scroll to top for screen readers
         */
        announceScrollTop() {
            if (this.config.accessibility.announceScrollTop) {
                const announcement = $('<div>', {
                    'aria-live': 'polite',
                    'aria-atomic': 'true',
                    'class': 'sr-only',
                    text: 'Scrolled to top of page'
                });

                $('body').append(announcement);

                setTimeout(() => {
                    announcement.remove();
                }, 1000);
            }
        }

        /**
         * Handle CTA button clicks
         */
        handleCTAClick(e) {
            const $button = $(e.currentTarget);
            const buttonType = $button.hasClass('cta-primary') ? 'primary' : 'secondary';
            const url = $button.attr('href');

            this.trackEvent('footer_cta_click', {
                button_type: buttonType,
                url: url,
                text: $button.text().trim()
            });

            // Add loading state
            this.addButtonLoadingState($button);

            // If it's a phone link, track and handle appropriately
            if (url && url.startsWith('tel:')) {
                this.handlePhoneCall(url);
            }
        }

        /**
         * Handle CTA button keyboard navigation
         */
        handleCTAKeydown(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.handleCTAClick(e);
            }
        }

        /**
         * Handle contact link clicks
         */
        handleContactClick(e) {
            const $link = $(e.currentTarget);
            const type = this.getContactLinkType($link);
            const value = $link.attr('href') || $link.text();

            this.trackEvent('footer_contact_click', {
                contact_type: type,
                value: value
            });

            if (type === 'phone') {
                this.handlePhoneCall(value);
            } else if (type === 'email') {
                this.handleEmailClick(value);
            }
        }

        /**
         * Get contact link type (phone, email, address)
         */
        getContactLinkType($link) {
            const href = $link.attr('href');

            if (href) {
                if (href.startsWith('tel:')) return 'phone';
                if (href.startsWith('mailto:')) return 'email';
                if (href.startsWith('http')) return 'address';
            }

            return 'other';
        }

        /**
         * Handle phone call interaction
         */
        handlePhoneCall(phoneUrl) {
            // Show feedback for phone call initiation
            this.showNotification('Initiating phone call...', 'info');

            // Track phone call attempt
            this.trackEvent('phone_call_initiated', {
                phone_number: phoneUrl.replace('tel:', ''),
                source: 'footer'
            });
        }

        /**
         * Handle email click
         */
        handleEmailClick(emailUrl) {
            this.trackEvent('email_click', {
                email: emailUrl.replace('mailto:', ''),
                source: 'footer'
            });
        }

        /**
         * Handle social link clicks
         */
        handleSocialClick(e) {
            const $link = $(e.currentTarget);
            const platform = this.getSocialPlatform($link);
            const url = $link.attr('href');

            this.trackEvent('footer_social_click', {
                platform: platform,
                url: url
            });

            // Add visual feedback
            this.addSocialClickFeedback($link);
        }

        /**
         * Get social platform from link
         */
        getSocialPlatform($link) {
            const href = $link.attr('href') || '';
            const text = $link.text().toLowerCase();

            if (href.includes('facebook') || text.includes('facebook')) return 'facebook';
            if (href.includes('instagram') || text.includes('instagram')) return 'instagram';
            if (href.includes('linkedin') || text.includes('linkedin')) return 'linkedin';
            if (href.includes('youtube') || text.includes('youtube')) return 'youtube';
            if (href.includes('twitter') || text.includes('twitter')) return 'twitter';

            return 'other';
        }

        /**
         * Add visual feedback for social link clicks
         */
        addSocialClickFeedback($link) {
            $link.addClass('clicked');

            setTimeout(() => {
                $link.removeClass('clicked');
            }, 200);
        }

        /**
         * Handle scroll events
         */
        handleScroll() {
            const scrollTop = $(window).scrollTop();

            // Update back to top button visibility
            this.updateBackToTopVisibility();

            // Track scroll direction for animations
            this.lastScrollTop = scrollTop;
        }

        /**
         * Update back to top button visibility based on scroll position
         */
        updateBackToTopVisibility() {
            const scrollTop = $(window).scrollTop();
            const isVisible = scrollTop > this.config.scrollThreshold;

            if (isVisible && !this.backToTopButton.hasClass('visible')) {
                this.showBackToTopButton();
            } else if (!isVisible && this.backToTopButton.hasClass('visible')) {
                this.hideBackToTopButton();
            }
        }

        /**
         * Show back to top button with animation
         */
        showBackToTopButton() {
            this.backToTopButton
                .addClass('visible')
                .attr('aria-hidden', 'false')
                .css('opacity', 0)
                .animate({ opacity: 1 }, 300);
        }

        /**
         * Hide back to top button with animation
         */
        hideBackToTopButton() {
            this.backToTopButton
                .animate({ opacity: 0 }, 300, () => {
                    this.backToTopButton
                        .removeClass('visible')
                        .attr('aria-hidden', 'true');
                });
        }

        /**
         * Handle window resize
         */
        handleResize() {
            // Recalculate any position-dependent elements
            this.updateBackToTopVisibility();
        }

        /**
         * Initialize accessibility features
         */
        initializeAccessibility() {
            // Add ARIA labels for better screen reader support
            this.enhanceARIALabels();

            // Setup keyboard navigation
            this.setupKeyboardNavigation();

            // Add focus indicators
            if (this.config.accessibility.enhancedFocus) {
                this.enhanceFocusIndicators();
            }

            // Setup skip links
            this.addSkipLinks();
        }

        /**
         * Enhance ARIA labels for better accessibility
         */
        enhanceARIALabels() {
            // Enhance back to top button
            if (this.backToTopButton.length) {
                this.backToTopButton.attr({
                    'aria-label': 'Scroll back to top of page',
                    'role': 'button'
                });
            }

            // Enhance contact links
            this.contactLinks.each((index, element) => {
                const $link = $(element);
                const type = this.getContactLinkType($link);
                const currentLabel = $link.attr('aria-label') || '';

                if (!currentLabel) {
                    let label = '';
                    switch (type) {
                        case 'phone':
                            label = `Call ${$link.text()}`;
                            break;
                        case 'email':
                            label = `Send email to ${$link.text()}`;
                            break;
                        default:
                            label = $link.text();
                    }
                    $link.attr('aria-label', label);
                }
            });

            // Enhance social links
            this.socialLinks.each((index, element) => {
                const $link = $(element);
                const platform = this.getSocialPlatform($link);
                const currentLabel = $link.attr('aria-label') || '';

                if (!currentLabel) {
                    $link.attr('aria-label', `Follow us on ${platform}`);
                }
            });
        }

        /**
         * Setup keyboard navigation
         */
        setupKeyboardNavigation() {
            // Ensure all interactive elements are focusable
            const interactiveElements = this.footer.find('a, button, [tabindex]');

            interactiveElements.each((index, element) => {
                const $element = $(element);

                if (!$element.attr('tabindex') && $element.is(':not(a):not(button)')) {
                    $element.attr('tabindex', '0');
                }
            });
        }

        /**
         * Enhance focus indicators
         */
        enhanceFocusIndicators() {
            const focusableElements = this.footer.find('a, button, [tabindex]');

            focusableElements.on('focus', function() {
                $(this).addClass('enhanced-focus');
            }).on('blur', function() {
                $(this).removeClass('enhanced-focus');
            });
        }

        /**
         * Add skip links for accessibility
         */
        addSkipLinks() {
            const skipLink = $('<a>', {
                href: '#footer-main',
                class: 'skip-link sr-only',
                text: 'Skip to footer content',
                'aria-label': 'Skip to footer content'
            });

            skipLink.on('focus', function() {
                $(this).removeClass('sr-only');
            }).on('blur', function() {
                $(this).addClass('sr-only');
            });

            this.footer.prepend(skipLink);
        }

        /**
         * Handle focus in events
         */
        handleFocusIn(e) {
            const $target = $(e.target);

            // Add focus indicator to parent elements if needed
            if ($target.hasClass('contact-link') || $target.hasClass('social-link')) {
                $target.closest('.contact-item, .social-links').addClass('has-focus');
            }
        }

        /**
         * Handle focus out events
         */
        handleFocusOut(e) {
            const $target = $(e.target);

            // Remove focus indicator from parent elements
            setTimeout(() => {
                if (!this.footer.find(':focus').length) {
                    this.footer.find('.has-focus').removeClass('has-focus');
                }
            }, 10);
        }

        /**
         * Handle global keyboard events
         */
        handleGlobalKeydown(e) {
            // Handle End key to jump to footer
            if (e.key === 'End' && e.ctrlKey) {
                e.preventDefault();
                this.focusFooter();
            }
        }

        /**
         * Focus the footer
         */
        focusFooter() {
            if (this.footer.length) {
                this.footer.attr('tabindex', '-1').focus();

                // Scroll to footer if not visible
                const footerTop = this.footer.offset().top;
                const windowBottom = $(window).scrollTop() + $(window).height();

                if (footerTop > windowBottom) {
                    $('html, body').animate({
                        scrollTop: footerTop - 100
                    }, 500);
                }
            }
        }

        /**
         * Initialize intersection observer for animations
         */
        initializeIntersectionObserver() {
            if (!window.IntersectionObserver) return;

            const observerOptions = {
                root: null,
                rootMargin: '0px 0px -100px 0px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in-view');
                    }
                });
            }, observerOptions);

            // Observe footer columns for fade-in animation
            this.footer.find('.footer-column').each((index, element) => {
                observer.observe(element);
            });

            this.observers.push(observer);
        }

        /**
         * Handle reduced motion preferences
         */
        handleReducedMotion() {
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

            if (prefersReducedMotion.matches) {
                this.config.smoothScrollDuration = 0;
                this.footer.addClass('reduced-motion');
            }

            // Listen for changes in motion preference
            prefersReducedMotion.addEventListener('change', (e) => {
                if (e.matches) {
                    this.config.smoothScrollDuration = 0;
                    this.footer.addClass('reduced-motion');
                } else {
                    this.config.smoothScrollDuration = 800;
                    this.footer.removeClass('reduced-motion');
                }
            });
        }

        /**
         * Initialize analytics tracking
         */
        initializeAnalytics() {
            // Track footer visibility
            this.trackFooterVisibility();

            // Track footer engagement
            this.trackFooterEngagement();
        }

        /**
         * Track footer visibility
         */
        trackFooterVisibility() {
            if (!window.IntersectionObserver) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.trackEvent('footer_viewed', {
                            scroll_depth: this.calculateScrollDepth()
                        });
                    }
                });
            }, { threshold: 0.5 });

            if (this.footer.length) {
                observer.observe(this.footer[0]);
                this.observers.push(observer);
            }
        }

        /**
         * Track footer engagement
         */
        trackFooterEngagement() {
            let engagementStartTime = null;
            let isEngaged = false;

            this.footer.on('mouseenter focusin', () => {
                if (!isEngaged) {
                    engagementStartTime = Date.now();
                    isEngaged = true;
                }
            });

            this.footer.on('mouseleave focusout', () => {
                if (isEngaged && engagementStartTime) {
                    const engagementTime = Date.now() - engagementStartTime;

                    if (engagementTime > 1000) { // Only track if engaged for more than 1 second
                        this.trackEvent('footer_engagement', {
                            engagement_time: engagementTime,
                            scroll_depth: this.calculateScrollDepth()
                        });
                    }

                    isEngaged = false;
                    engagementStartTime = null;
                }
            });
        }

        /**
         * Calculate scroll depth percentage
         */
        calculateScrollDepth() {
            const windowHeight = $(window).height();
            const documentHeight = $(document).height();
            const scrollTop = $(window).scrollTop();

            return Math.round(((scrollTop + windowHeight) / documentHeight) * 100);
        }

        /**
         * Add loading state to button
         */
        addButtonLoadingState($button) {
            const originalText = $button.text();

            $button.addClass('loading').prop('disabled', true);

            // Restore button state after a delay
            setTimeout(() => {
                $button.removeClass('loading').prop('disabled', false);
            }, 1000);
        }

        /**
         * Show notification message
         */
        showNotification(message, type = 'info') {
            const notification = $('<div>', {
                class: `footer-notification notification-${type}`,
                text: message,
                'aria-live': 'polite'
            });

            this.footer.append(notification);

            // Fade in
            notification.fadeIn(300);

            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.fadeOut(300, () => {
                    notification.remove();
                });
            }, 3000);
        }

        /**
         * Track events for analytics
         */
        trackEvent(eventName, eventData = {}) {
            // Google Analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', eventName, {
                    event_category: 'footer',
                    ...eventData
                });
            }

            // WordPress Analytics (if available)
            if (typeof wp !== 'undefined' && wp.hooks) {
                wp.hooks.doAction('footer.trackEvent', eventName, eventData);
            }

            // Custom analytics
            $(document).trigger('footer:trackEvent', [eventName, eventData]);
        }

        /**
         * Utility: Debounce function
         */
        debounce(func, wait, immediate) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    timeout = null;
                    if (!immediate) func.apply(this, args);
                };
                const callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(this, args);
            };
        }

        /**
         * Destroy footer manager and clean up
         */
        destroy() {
            // Remove event listeners
            $(window).off('scroll resize');
            this.footer.off();
            this.backToTopButton.off();
            this.ctaButtons.off();
            this.contactLinks.off();
            this.socialLinks.off();

            // Disconnect observers
            this.observers.forEach(observer => {
                if (observer.disconnect) {
                    observer.disconnect();
                }
            });
            this.observers = [];

            // Clean up DOM
            this.footer.find('.skip-link').remove();
            this.footer.find('.footer-notification').remove();
        }
    }

    /**
     * ContactFormValidator Class
     *
     * Validates and enhances contact forms in footer
     */
    class ContactFormValidator {
        constructor() {
            this.forms = $('.footer-component .contact-form');
            this.init();
        }

        init() {
            if (!this.forms.length) return;

            this.forms.each((index, form) => {
                this.setupFormValidation($(form));
            });
        }

        setupFormValidation($form) {
            const $inputs = $form.find('input, textarea, select');

            $inputs.on('blur', (e) => this.validateField($(e.target)));
            $form.on('submit', (e) => this.validateForm(e, $form));
        }

        validateField($field) {
            const value = $field.val().trim();
            const type = $field.attr('type') || $field.prop('tagName').toLowerCase();
            let isValid = true;
            let message = '';

            // Required field validation
            if ($field.prop('required') && !value) {
                isValid = false;
                message = 'This field is required';
            }

            // Email validation
            if (type === 'email' && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    isValid = false;
                    message = 'Please enter a valid email address';
                }
            }

            // Phone validation
            if (type === 'tel' && value) {
                const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
                if (!phoneRegex.test(value.replace(/[\s\-\(\)]/g, ''))) {
                    isValid = false;
                    message = 'Please enter a valid phone number';
                }
            }

            this.showFieldValidation($field, isValid, message);
            return isValid;
        }

        validateForm(e, $form) {
            let isFormValid = true;
            const $inputs = $form.find('input, textarea, select');

            $inputs.each((index, input) => {
                const $input = $(input);
                if (!this.validateField($input)) {
                    isFormValid = false;
                }
            });

            if (!isFormValid) {
                e.preventDefault();
                this.focusFirstInvalidField($form);
            }

            return isFormValid;
        }

        showFieldValidation($field, isValid, message) {
            const $errorElement = $field.siblings('.field-error');

            if (isValid) {
                $field.removeClass('invalid').addClass('valid');
                $errorElement.remove();
            } else {
                $field.removeClass('valid').addClass('invalid');

                if (!$errorElement.length) {
                    const errorElement = $('<div>', {
                        class: 'field-error',
                        text: message,
                        'aria-live': 'polite'
                    });
                    $field.after(errorElement);
                } else {
                    $errorElement.text(message);
                }
            }
        }

        focusFirstInvalidField($form) {
            const $firstInvalid = $form.find('.invalid').first();
            if ($firstInvalid.length) {
                $firstInvalid.focus();
            }
        }
    }

    /**
     * Initialize footer components when DOM is ready
     */
    $(document).ready(function() {
        // Initialize footer manager
        window.footerManager = new FooterManager();

        // Initialize contact form validator
        window.contactFormValidator = new ContactFormValidator();

        // Expose components globally for debugging
        if (window.medSpaTheme) {
            window.medSpaTheme.footer = {
                manager: window.footerManager,
                validator: window.contactFormValidator
            };
        }
    });

    /**
     * Handle page unload cleanup
     */
    $(window).on('beforeunload', function() {
        if (window.footerManager && typeof window.footerManager.destroy === 'function') {
            window.footerManager.destroy();
        }
    });

})(jQuery);

/**
 * CSS for enhanced footer interactions
 * This CSS is added dynamically for JavaScript-enhanced features
 */
const footerInteractionCSS = `
/* Enhanced focus states */
.footer-component .enhanced-focus {
    outline: 3px solid var(--color-gold-enhanced, #E0B83E) !important;
    outline-offset: 2px;
    box-shadow: 0 0 0 6px rgba(224, 184, 62, 0.2);
}

/* Button loading states */
.footer-component .cta-primary.loading,
.footer-component .cta-secondary.loading {
    position: relative;
    color: transparent;
    pointer-events: none;
}

.footer-component .cta-primary.loading::after,
.footer-component .cta-secondary.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: footerButtonSpin 1s linear infinite;
}

@keyframes footerButtonSpin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Social link click feedback */
.footer-component .social-link.clicked {
    transform: scale(0.95);
    opacity: 0.8;
}

/* Footer notifications */
.footer-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    padding: 12px 16px;
    background: var(--color-navy-primary, #1B365D);
    color: var(--color-cream-base, #FDFCFA);
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    font-size: 14px;
    max-width: 300px;
    display: none;
}

.footer-notification.notification-info {
    border-left: 4px solid var(--color-blue-500, #3b82f6);
}

.footer-notification.notification-success {
    border-left: 4px solid var(--color-green-500, #10b981);
}

.footer-notification.notification-error {
    border-left: 4px solid var(--color-red-500, #ef4444);
}

/* Back to top button states */
.footer-component .back-to-top {
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease;
}

.footer-component .back-to-top.visible {
    opacity: 1;
    visibility: visible;
}

/* Column fade-in animations */
.footer-component .footer-column {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

.footer-component .footer-column.in-view {
    opacity: 1;
    transform: translateY(0);
}

/* Stagger animation delays */
.footer-component .footer-column:nth-child(1) { transition-delay: 0.1s; }
.footer-component .footer-column:nth-child(2) { transition-delay: 0.2s; }
.footer-component .footer-column:nth-child(3) { transition-delay: 0.3s; }

/* Contact form validation styles */
.footer-component .contact-form .field-error {
    color: var(--color-red-500, #ef4444);
    font-size: 12px;
    margin-top: 4px;
    display: block;
}

.footer-component .contact-form input.invalid,
.footer-component .contact-form textarea.invalid {
    border-color: var(--color-red-500, #ef4444);
    box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
}

.footer-component .contact-form input.valid,
.footer-component .contact-form textarea.valid {
    border-color: var(--color-green-500, #10b981);
    box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
}

/* Skip link styles */
.footer-component .skip-link {
    position: absolute;
    top: -40px;
    left: 6px;
    background: var(--color-gold-primary, #D4AF37);
    color: var(--color-navy-primary, #1B365D);
    padding: 8px 12px;
    text-decoration: none;
    border-radius: 4px;
    font-weight: 600;
    z-index: 100;
    transition: top 0.3s ease;
}

.footer-component .skip-link:focus {
    top: 6px;
}

/* Focus indicators for parent elements */
.footer-component .contact-item.has-focus,
.footer-component .social-links.has-focus {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    padding: 8px;
    margin: -8px;
}

/* Reduced motion support */
.footer-component.reduced-motion * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
}

/* High contrast mode enhancements */
@media (prefers-contrast: high) {
    .footer-component .enhanced-focus {
        outline: 4px solid #ffffff !important;
        background: rgba(255, 255, 255, 0.1) !important;
    }

    .footer-notification {
        border: 2px solid #ffffff;
    }
}
`;

// Inject CSS for enhanced interactions
if (typeof document !== 'undefined') {
    const style = document.createElement('style');
    style.textContent = footerInteractionCSS;
    document.head.appendChild(style);
}
