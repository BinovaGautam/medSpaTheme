/**
 * Footer Newsletter & Social Engagement
 * Sprint: SPRINT-FOOTER-IMPL-001 | Task: T-FOOTER-007
 *
 * Newsletter subscription and social media engagement functionality
 * AJAX submission with validation and analytics tracking
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Footer Newsletter & Social Class
     */
    class FooterNewsletterSocial {

        constructor() {
            this.isSubmitting = false;
            this.validationRules = {
                email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                minLength: 3,
                maxLength: 254
            };

            this.init();
        }

        /**
         * Initialize the newsletter & social system
         */
        init() {
            this.bindEvents();
            this.setupValidation();
            this.initSocialTracking();

            console.log('Footer Newsletter & Social: Initialized successfully');
        }

        /**
         * Bind event listeners
         */
        bindEvents() {
            // Newsletter form submission
            $(document).on('submit', '.newsletter-form', (e) => {
                e.preventDefault();
                this.handleNewsletterSubmission(e);
            });

            // Real-time email validation
            $(document).on('input', '.newsletter-input', (e) => {
                this.validateEmailField(e.target);
            });

            // Social media link tracking
            $(document).on('click', '.social-link', (e) => {
                this.trackSocialClick(e);
            });

            // Newsletter input focus/blur effects
            $(document).on('focus', '.newsletter-input', (e) => {
                this.handleInputFocus(e);
            });

            $(document).on('blur', '.newsletter-input', (e) => {
                this.handleInputBlur(e);
            });

            // Keyboard accessibility
            $(document).on('keydown', '.newsletter-form', (e) => {
                this.handleKeyboardNavigation(e);
            });
        }

        /**
         * Handle newsletter form submission
         */
        async handleNewsletterSubmission(e) {
            const form = $(e.target);
            const emailInput = form.find('.newsletter-input');
            const submitBtn = form.find('.newsletter-submit');
            const email = emailInput.val().trim();

            // Prevent double submission
            if (this.isSubmitting) {
                return;
            }

            // Validate email
            const validation = this.validateEmail(email);
            if (!validation.isValid) {
                this.showValidationError(emailInput, validation.message);
                return;
            }

            // Set loading state
            this.isSubmitting = true;
            this.setLoadingState(submitBtn, true);
            this.clearValidationError(emailInput);

            try {
                // Submit to WordPress AJAX
                const response = await this.submitNewsletterSubscription(email);

                if (response.success) {
                    this.showSuccessState(form, response.data.message);
                    this.trackNewsletterSignup(email);
                } else {
                    this.showErrorState(form, response.data.message || 'Subscription failed. Please try again.');
                }
            } catch (error) {
                console.error('Newsletter subscription error:', error);
                this.showErrorState(form, 'Network error. Please check your connection and try again.');
            } finally {
                this.isSubmitting = false;
                this.setLoadingState(submitBtn, false);
            }
        }

        /**
         * Submit newsletter subscription via AJAX
         */
        submitNewsletterSubscription(email) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: footerSettings.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'footer_newsletter_subscribe',
                        email: email,
                        nonce: footerSettings.nonce
                    },
                    timeout: 10000,
                    success: function(response) {
                        resolve(response);
                    },
                    error: function(xhr, status, error) {
                        reject(new Error(`AJAX Error: ${status} - ${error}`));
                    }
                });
            });
        }

        /**
         * Validate email address
         */
        validateEmail(email) {
            if (!email) {
                return {
                    isValid: false,
                    message: 'Email address is required.'
                };
            }

            if (email.length < this.validationRules.minLength) {
                return {
                    isValid: false,
                    message: 'Email address is too short.'
                };
            }

            if (email.length > this.validationRules.maxLength) {
                return {
                    isValid: false,
                    message: 'Email address is too long.'
                };
            }

            if (!this.validationRules.email.test(email)) {
                return {
                    isValid: false,
                    message: 'Please enter a valid email address.'
                };
            }

            return {
                isValid: true,
                message: ''
            };
        }

        /**
         * Real-time email field validation
         */
        validateEmailField(input) {
            const $input = $(input);
            const email = $input.val().trim();

            if (email.length === 0) {
                this.clearValidationError($input);
                return;
            }

            const validation = this.validateEmail(email);

            if (validation.isValid) {
                this.showValidationSuccess($input);
            } else {
                this.showValidationError($input, validation.message);
            }
        }

        /**
         * Show validation error
         */
        showValidationError(input, message) {
            const $input = $(input);
            const $formGroup = $input.closest('.form-group');

            $input.addClass('error').removeClass('success');

            // Remove existing error message
            $formGroup.find('.validation-message').remove();

            // Add error message
            $formGroup.append(`
                <div class="validation-message error" role="alert">
                    <span class="message-icon">⚠️</span>
                    <span class="message-text">${message}</span>
                </div>
            `);

            // Accessibility
            $input.attr('aria-invalid', 'true');
            $input.attr('aria-describedby', 'email-error');
        }

        /**
         * Show validation success
         */
        showValidationSuccess(input) {
            const $input = $(input);
            const $formGroup = $input.closest('.form-group');

            $input.addClass('success').removeClass('error');

            // Remove existing messages
            $formGroup.find('.validation-message').remove();

            // Add success indicator
            $formGroup.append(`
                <div class="validation-message success">
                    <span class="message-icon">✓</span>
                    <span class="message-text">Valid email address</span>
                </div>
            `);

            // Accessibility
            $input.attr('aria-invalid', 'false');
            $input.removeAttr('aria-describedby');
        }

        /**
         * Clear validation error
         */
        clearValidationError(input) {
            const $input = $(input);
            const $formGroup = $input.closest('.form-group');

            $input.removeClass('error success');
            $formGroup.find('.validation-message').remove();

            // Accessibility
            $input.removeAttr('aria-invalid');
            $input.removeAttr('aria-describedby');
        }

        /**
         * Set loading state for submit button
         */
        setLoadingState(button, isLoading) {
            const $btn = $(button);

            if (isLoading) {
                $btn.addClass('loading');
                $btn.prop('disabled', true);
                $btn.find('.btn-text').text('Subscribing...');
            } else {
                $btn.removeClass('loading');
                $btn.prop('disabled', false);
                $btn.find('.btn-text').text('Subscribe');
            }
        }

        /**
         * Show success state
         */
        showSuccessState(form, message) {
            const $form = $(form);
            const $submitBtn = $form.find('.newsletter-submit');

            // Add success class to form
            $form.addClass('success');

            // Update button
            $submitBtn.addClass('success');
            $submitBtn.find('.btn-text').text('Subscribed!');

            // Show success message
            $form.append(`
                <div class="form-message success" role="status" aria-live="polite">
                    <span class="message-icon">🎉</span>
                    <span class="message-text">${message}</span>
                </div>
            `);

            // Reset form after delay
            setTimeout(() => {
                this.resetForm($form);
            }, 3000);
        }

        /**
         * Show error state
         */
        showErrorState(form, message) {
            const $form = $(form);

            // Remove existing messages
            $form.find('.form-message').remove();

            // Show error message
            $form.append(`
                <div class="form-message error" role="alert" aria-live="assertive">
                    <span class="message-icon">❌</span>
                    <span class="message-text">${message}</span>
                </div>
            `);

            // Remove error message after delay
            setTimeout(() => {
                $form.find('.form-message.error').fadeOut(() => {
                    $(this).remove();
                });
            }, 5000);
        }

        /**
         * Reset form to initial state
         */
        resetForm(form) {
            const $form = $(form);

            $form.removeClass('success');
            $form.find('.newsletter-input').val('');
            $form.find('.newsletter-submit').removeClass('success');
            $form.find('.btn-text').text('Subscribe');
            $form.find('.form-message').fadeOut(() => {
                $(this).remove();
            });

            this.clearValidationError($form.find('.newsletter-input'));
        }

        /**
         * Handle input focus effects
         */
        handleInputFocus(e) {
            const $input = $(e.target);
            const $formGroup = $input.closest('.form-group');

            $formGroup.addClass('focused');

            // Analytics
            this.trackEvent('newsletter_input_focus', {
                input_type: 'email'
            });
        }

        /**
         * Handle input blur effects
         */
        handleInputBlur(e) {
            const $input = $(e.target);
            const $formGroup = $input.closest('.form-group');

            $formGroup.removeClass('focused');
        }

        /**
         * Handle keyboard navigation
         */
        handleKeyboardNavigation(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                $(e.target).closest('.newsletter-form').submit();
            }
        }

        /**
         * Setup validation styles
         */
        setupValidation() {
            // Add validation CSS if not already added
            if (!$('#newsletter-validation-styles').length) {
                $('head').append(`
                    <style id="newsletter-validation-styles">
                        .form-group {
                            position: relative;
                        }

                        .newsletter-input.error {
                            border-color: #EF4444;
                            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
                        }

                        .newsletter-input.success {
                            border-color: #10B981;
                            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
                        }

                        .validation-message {
                            display: flex;
                            align-items: center;
                            gap: 0.5rem;
                            margin-top: 0.5rem;
                            font-size: 0.875rem;
                            line-height: 1.4;
                        }

                        .validation-message.error {
                            color: #EF4444;
                        }

                        .validation-message.success {
                            color: #10B981;
                        }

                        .form-message {
                            display: flex;
                            align-items: center;
                            gap: 0.75rem;
                            margin-top: 1rem;
                            padding: 1rem;
                            border-radius: 0.5rem;
                            font-weight: 500;
                        }

                        .form-message.success {
                            background: rgba(16, 185, 129, 0.1);
                            color: #10B981;
                            border: 1px solid rgba(16, 185, 129, 0.3);
                        }

                        .form-message.error {
                            background: rgba(239, 68, 68, 0.1);
                            color: #EF4444;
                            border: 1px solid rgba(239, 68, 68, 0.3);
                        }

                        .form-group.focused .newsletter-input {
                            transform: translateY(-2px);
                        }
                    </style>
                `);
            }
        }

        /**
         * Initialize social media tracking
         */
        initSocialTracking() {
            // Track social media impressions
            const socialLinks = $('.social-link');

            if (socialLinks.length > 0) {
                this.trackEvent('social_links_viewed', {
                    count: socialLinks.length,
                    platforms: this.getSocialPlatforms(socialLinks)
                });
            }
        }

        /**
         * Track social media click
         */
        trackSocialClick(e) {
            const $link = $(e.currentTarget);
            const platform = this.extractSocialPlatform($link);
            const href = $link.attr('href');

            // Track click
            this.trackEvent('social_media_click', {
                platform: platform,
                url: href,
                source: 'footer'
            });

            // Track conversion
            if (typeof gtag !== 'undefined') {
                gtag('event', 'social_engagement', {
                    social_network: platform,
                    engagement_type: 'click'
                });
            }

            console.log(`Social click tracked: ${platform}`);
        }

        /**
         * Extract social platform from link
         */
        extractSocialPlatform(link) {
            const href = link.attr('href') || '';
            const text = link.text().toLowerCase();

            // Platform detection
            if (href.includes('facebook.com') || text.includes('facebook')) return 'Facebook';
            if (href.includes('instagram.com') || text.includes('instagram')) return 'Instagram';
            if (href.includes('twitter.com') || text.includes('twitter')) return 'Twitter';
            if (href.includes('linkedin.com') || text.includes('linkedin')) return 'LinkedIn';
            if (href.includes('youtube.com') || text.includes('youtube')) return 'YouTube';
            if (href.includes('tiktok.com') || text.includes('tiktok')) return 'TikTok';

            return 'Other';
        }

        /**
         * Get social platforms from links
         */
        getSocialPlatforms(links) {
            const platforms = [];

            links.each((index, link) => {
                const platform = this.extractSocialPlatform($(link));
                if (!platforms.includes(platform)) {
                    platforms.push(platform);
                }
            });

            return platforms;
        }

        /**
         * Track newsletter signup
         */
        trackNewsletterSignup(email) {
            // Hash email for privacy
            const emailHash = this.hashEmail(email);

            this.trackEvent('newsletter_signup', {
                email_hash: emailHash,
                source: 'footer',
                timestamp: new Date().toISOString()
            });

            // Google Analytics conversion
            if (typeof gtag !== 'undefined') {
                gtag('event', 'sign_up', {
                    method: 'newsletter'
                });
            }

            console.log('Newsletter signup tracked successfully');
        }

        /**
         * Hash email for privacy
         */
        hashEmail(email) {
            // Simple hash for privacy (not cryptographically secure)
            let hash = 0;
            for (let i = 0; i < email.length; i++) {
                const char = email.charCodeAt(i);
                hash = ((hash << 5) - hash) + char;
                hash = hash & hash; // Convert to 32-bit integer
            }
            return Math.abs(hash).toString(36);
        }

        /**
         * Track custom events
         */
        trackEvent(eventName, parameters = {}) {
            // Google Analytics 4
            if (typeof gtag !== 'undefined') {
                gtag('event', eventName, parameters);
            }

            // WordPress hooks
            if (typeof wp !== 'undefined' && wp.hooks) {
                wp.hooks.doAction('footer_newsletter_event', eventName, parameters);
            }

            console.log('Event tracked:', eventName, parameters);
        }

    }

    /**
     * Initialize when DOM is ready
     */
    $(document).ready(function() {
        // Create global instance
        window.footerNewsletterSocial = new FooterNewsletterSocial();

        console.log('Footer Newsletter & Social: System ready');
    });

})(jQuery);
