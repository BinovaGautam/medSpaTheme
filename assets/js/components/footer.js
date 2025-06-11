/**
 * Footer Component JavaScript
 *
 * Handles footer interactivity including newsletter signup and privacy controls
 * Following established theme patterns for semantic interaction
 *
 * @package MedSpaTheme
 * @since 1.0.0
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 */

(function() {
    'use strict';

    // ==========================================================================
    // FOOTER COMPONENT INITIALIZATION
    // ==========================================================================

    /**
     * Initialize footer component when DOM is ready
     */
    function initFooterComponent() {
        // Newsletter form handling
        initNewsletterForm();

        // Privacy controls handling
        initPrivacyControls();

        // Current year replacement
        initCurrentYear();

        // Accessibility enhancements
        initAccessibilityEnhancements();
    }

    // ==========================================================================
    // NEWSLETTER FORM HANDLING
    // ==========================================================================

    /**
     * Initialize newsletter form functionality
     */
    function initNewsletterForm() {
        const form = document.querySelector('.newsletter-form');
        if (!form) return;

        form.addEventListener('submit', handleNewsletterSubmit);

        // Input validation feedback
        const emailInput = form.querySelector('.newsletter-input');
        if (emailInput) {
            emailInput.addEventListener('input', validateEmailInput);
            emailInput.addEventListener('blur', validateEmailInput);
        }
    }

    /**
     * Handle newsletter form submission
     * @param {Event} event - Form submit event
     */
    function handleNewsletterSubmit(event) {
        event.preventDefault();

        const form = event.target;
        const emailInput = form.querySelector('.newsletter-input');
        const submitButton = form.querySelector('.newsletter-submit');

        if (!emailInput || !emailInput.value.trim()) {
            showFormFeedback(form, 'error', 'Please enter a valid email address.');
            return;
        }

        if (!isValidEmail(emailInput.value.trim())) {
            showFormFeedback(form, 'error', 'Please enter a valid email address.');
            return;
        }

        // Show loading state
        setSubmitButtonState(submitButton, 'loading');

        // Simulate newsletter signup (replace with actual implementation)
        setTimeout(() => {
            // Reset form
            form.reset();

            // Show success message
            showFormFeedback(form, 'success', 'Thank you for subscribing! You\'ll receive updates about our latest treatments and offers.');

            // Reset button state
            setSubmitButtonState(submitButton, 'default');

            // Analytics tracking
            if (typeof gtag !== 'undefined') {
                gtag('event', 'newsletter_signup', {
                    'event_category': 'engagement',
                    'event_label': 'footer_newsletter'
                });
            }
        }, 1500);
    }

    /**
     * Validate email input with visual feedback
     * @param {Event} event - Input event
     */
    function validateEmailInput(event) {
        const input = event.target;
        const value = input.value.trim();

        // Remove previous validation classes
        input.classList.remove('is-valid', 'is-invalid');

        if (value && !isValidEmail(value)) {
            input.classList.add('is-invalid');
            input.setAttribute('aria-invalid', 'true');
        } else if (value) {
            input.classList.add('is-valid');
            input.setAttribute('aria-invalid', 'false');
        }
    }

    /**
     * Simple email validation
     * @param {string} email - Email address to validate
     * @returns {boolean} - Whether email is valid
     */
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    /**
     * Show form feedback message
     * @param {HTMLElement} form - Form element
     * @param {string} type - Message type (success, error)
     * @param {string} message - Message text
     */
    function showFormFeedback(form, type, message) {
        // Remove existing feedback
        const existingFeedback = form.querySelector('.form-feedback');
        if (existingFeedback) {
            existingFeedback.remove();
        }

        // Create feedback element
        const feedback = document.createElement('div');
        feedback.className = `form-feedback form-feedback-${type}`;
        feedback.setAttribute('role', 'alert');
        feedback.setAttribute('aria-live', 'polite');
        feedback.textContent = message;

        // Insert feedback
        const inputGroup = form.querySelector('.newsletter-input-group');
        inputGroup.insertAdjacentElement('afterend', feedback);

        // Auto-remove success messages after delay
        if (type === 'success') {
            setTimeout(() => {
                if (feedback.parentNode) {
                    feedback.remove();
                }
            }, 5000);
        }
    }

    /**
     * Set submit button state
     * @param {HTMLElement} button - Submit button
     * @param {string} state - Button state (default, loading)
     */
    function setSubmitButtonState(button, state) {
        switch (state) {
            case 'loading':
                button.disabled = true;
                button.innerHTML = '<span class="loading-spinner" aria-hidden="true">⏳</span>';
                button.setAttribute('aria-label', 'Processing subscription...');
                break;
            case 'default':
            default:
                button.disabled = false;
                button.innerHTML = '<span class="submit-icon" aria-hidden="true">→</span>';
                button.setAttribute('aria-label', 'Subscribe to Newsletter');
                break;
        }
    }

    // ==========================================================================
    // PRIVACY CONTROLS HANDLING
    // ==========================================================================

    /**
     * Initialize privacy controls functionality
     */
    function initPrivacyControls() {
        const cookieBtn = document.getElementById('cookie-preferences');
        const privacyBtn = document.getElementById('privacy-choices');

        if (cookieBtn) {
            cookieBtn.addEventListener('click', handleCookiePreferences);
        }

        if (privacyBtn) {
            privacyBtn.addEventListener('click', handlePrivacyChoices);
        }
    }

    /**
     * Handle cookie preferences button click
     */
    function handleCookiePreferences() {
        // This would typically open a cookie preferences modal
        // For now, show a simple alert (replace with actual implementation)
        if (window.confirm('Would you like to manage your cookie preferences? This will open the cookie settings.')) {
            // Implement cookie preferences logic here
            console.log('Opening cookie preferences...');

            // Analytics tracking
            if (typeof gtag !== 'undefined') {
                gtag('event', 'cookie_preferences_opened', {
                    'event_category': 'privacy',
                    'event_label': 'footer'
                });
            }
        }
    }

    /**
     * Handle privacy choices button click
     */
    function handlePrivacyChoices() {
        // This would typically open privacy choices modal
        // For now, show a simple alert (replace with actual implementation)
        if (window.confirm('Would you like to review your privacy choices? This will open the privacy settings.')) {
            // Implement privacy choices logic here
            console.log('Opening privacy choices...');

            // Analytics tracking
            if (typeof gtag !== 'undefined') {
                gtag('event', 'privacy_choices_opened', {
                    'event_category': 'privacy',
                    'event_label': 'footer'
                });
            }
        }
    }

    // ==========================================================================
    // CURRENT YEAR REPLACEMENT
    // ==========================================================================

    /**
     * Replace {CURRENT_YEAR} placeholder with actual current year
     */
    function initCurrentYear() {
        const currentYear = new Date().getFullYear();
        const yearPlaceholders = document.querySelectorAll('[data-year-placeholder], .footer-copyright');

        yearPlaceholders.forEach(element => {
            const text = element.innerHTML;
            if (text.includes('{CURRENT_YEAR}')) {
                element.innerHTML = text.replace('{CURRENT_YEAR}', currentYear);
            }
        });
    }

    // ==========================================================================
    // ACCESSIBILITY ENHANCEMENTS
    // ==========================================================================

    /**
     * Initialize accessibility enhancements
     */
    function initAccessibilityEnhancements() {
        // Add skip link for footer navigation
        addFooterSkipLink();

        // Enhance keyboard navigation
        enhanceKeyboardNavigation();

        // Add screen reader announcements for dynamic content
        initScreenReaderAnnouncements();
    }

    /**
     * Add skip link for footer navigation
     */
    function addFooterSkipLink() {
        const footer = document.querySelector('.site-footer');
        if (!footer) return;

        // Create skip link (only visible on focus)
        const skipLink = document.createElement('a');
        skipLink.href = '#footer-navigation';
        skipLink.className = 'skip-link';
        skipLink.textContent = 'Skip to footer navigation';
        skipLink.style.cssText = `
            position: absolute;
            left: -9999px;
            z-index: 999999;
            padding: 8px 16px;
            background: #000;
            color: #fff;
            text-decoration: none;
        `;

        // Show on focus
        skipLink.addEventListener('focus', () => {
            skipLink.style.left = '6px';
            skipLink.style.top = '7px';
        });

        skipLink.addEventListener('blur', () => {
            skipLink.style.left = '-9999px';
        });

        footer.insertBefore(skipLink, footer.firstChild);

        // Add ID to footer navigation
        const footerNav = footer.querySelector('.footer-nav');
        if (footerNav) {
            footerNav.id = 'footer-navigation';
        }
    }

    /**
     * Enhance keyboard navigation for footer elements
     */
    function enhanceKeyboardNavigation() {
        const interactiveElements = document.querySelectorAll('.site-footer a, .site-footer button, .site-footer input');

        interactiveElements.forEach(element => {
            // Ensure minimum touch target size
            if (!element.style.minHeight) {
                element.style.minHeight = '44px';
            }

            // Add visible focus indicators
            element.addEventListener('focus', (e) => {
                e.target.style.outline = '2px solid var(--color-focus, #3b82f6)';
                e.target.style.outlineOffset = '2px';
            });

            element.addEventListener('blur', (e) => {
                e.target.style.outline = '';
                e.target.style.outlineOffset = '';
            });
        });
    }

    /**
     * Initialize screen reader announcements for dynamic content
     */
    function initScreenReaderAnnouncements() {
        // Create announcement region
        const announceRegion = document.createElement('div');
        announceRegion.setAttribute('aria-live', 'polite');
        announceRegion.setAttribute('aria-atomic', 'true');
        announceRegion.className = 'sr-announce';
        announceRegion.style.cssText = `
            position: absolute;
            left: -9999px;
            width: 1px;
            height: 1px;
            overflow: hidden;
        `;

        document.body.appendChild(announceRegion);

        // Store reference for use in other functions
        window.srAnnounce = function(message) {
            announceRegion.textContent = message;
            setTimeout(() => {
                announceRegion.textContent = '';
            }, 1000);
        };
    }

    // ==========================================================================
    // INITIALIZATION
    // ==========================================================================

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFooterComponent);
    } else {
        initFooterComponent();
    }

})();
