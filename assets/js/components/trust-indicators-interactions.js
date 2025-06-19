/**
 * Trust Indicators Interactions
 *
 * Provides enhanced user interactions for the trust indicators component:
 * - Card hover effects and animations
 * - Verification link interactions
 * - Statistics counter animations
 * - Accessibility enhancements
 * - Performance tracking
 *
 * @package MedSpaTheme
 * @subpackage JavaScript
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Trust Indicators Interactions Class
     */
    class TrustIndicatorsInteractions {
        constructor() {
            this.initialized = false;
            this.animatedCounters = new Set();
            this.performanceMetrics = {
                startTime: performance.now(),
                interactionCount: 0,
                animationsCompleted: 0
            };

            this.init();
        }

        /**
         * Initialize all interactions
         */
        init() {
            if (this.initialized) return;

            $(document).ready(() => {
                this.setupCardInteractions();
                this.setupCounterAnimations();
                this.setupVerificationLinks();
                this.setupAccessibilityEnhancements();
                this.setupIntersectionObserver();
                this.setupPerformanceTracking();

                this.initialized = true;
                this.logPerformance('initialization');
            });
        }

        /**
         * Setup trust indicator card interactions
         */
        setupCardInteractions() {
            const $cards = $('.trust-indicator-card');

            if ($cards.length === 0) return;

            // Enhanced hover effects with staggered animations
            $cards.on('mouseenter', function() {
                const $card = $(this);
                const index = $card.index();

                $card.addClass('is-hovered');

                // Add staggered animation delay
                $card.css('--hover-delay', `${index * 100}ms`);

                // Animate icon
                const $icon = $card.find('.trust-indicator__icon');
                $icon.addClass('animate-bounce');

                // Highlight verification link
                const $verificationLink = $card.find('.verification-link');
                $verificationLink.addClass('is-highlighted');
            });

            $cards.on('mouseleave', function() {
                const $card = $(this);

                $card.removeClass('is-hovered');

                // Reset animations
                const $icon = $card.find('.trust-indicator__icon');
                $icon.removeClass('animate-bounce');

                const $verificationLink = $card.find('.verification-link');
                $verificationLink.removeClass('is-highlighted');
            });

            // Click interactions for mobile
            $cards.on('click', function(e) {
                if (window.innerWidth <= 768) {
                    const $card = $(this);
                    const wasActive = $card.hasClass('is-active');

                    // Remove active state from other cards
                    $cards.removeClass('is-active');

                    // Toggle current card
                    if (!wasActive) {
                        $card.addClass('is-active');

                        // Track interaction
                        const cardId = $card.attr('id');
                        this.trackInteraction('card_mobile_tap', {
                            card_id: cardId,
                            card_title: $card.find('.trust-indicator__title').text()
                        });
                    }
                }
            });

            // Keyboard navigation
            $cards.on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    $(this).trigger('click');
                }
            });
        }

        /**
         * Setup counter animations for statistics
         */
        setupCounterAnimations() {
            const $counters = $('.stat-number');

            if ($counters.length === 0) return;

            $counters.each((index, counter) => {
                const $counter = $(counter);
                const text = $counter.text();
                const number = this.extractNumber(text);

                if (number > 0) {
                    $counter.data('target', number);
                    $counter.data('original-text', text);
                    $counter.text('0' + text.replace(/[\d,]/g, ''));
                }
            });
        }

        /**
         * Extract number from text
         */
        extractNumber(text) {
            const match = text.match(/[\d,]+/);
            if (match) {
                return parseInt(match[0].replace(/,/g, ''), 10);
            }
            return 0;
        }

        /**
         * Animate counter
         */
        animateCounter($counter) {
            const counterId = $counter.closest('.trust-indicator-card').attr('id');

            if (this.animatedCounters.has(counterId)) return;

            const target = $counter.data('target');
            const originalText = $counter.data('original-text');

            if (!target || !originalText) return;

            this.animatedCounters.add(counterId);

            let current = 0;
            const increment = Math.ceil(target / 50);
            const duration = 2000; // 2 seconds
            const stepTime = duration / (target / increment);

            const timer = setInterval(() => {
                current += increment;

                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                    this.performanceMetrics.animationsCompleted++;
                }

                // Update display
                const displayText = originalText.replace(/[\d,]+/, this.formatNumber(current));
                $counter.text(displayText);

                // Add completion class
                if (current === target) {
                    $counter.addClass('animation-complete');

                    // Track completion
                    this.trackInteraction('counter_animation_complete', {
                        counter_id: counterId,
                        final_value: target,
                        duration: duration
                    });
                }
            }, stepTime);
        }

        /**
         * Format number with commas
         */
        formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        /**
         * Setup verification link interactions
         */
        setupVerificationLinks() {
            const $verificationLinks = $('.verification-link');

            $verificationLinks.on('click', function(e) {
                const $link = $(this);
                const href = $link.attr('href');
                const cardId = $link.closest('.trust-indicator-card').attr('id');
                const linkText = $link.find('.verification-text').text();

                // Track verification link clicks
                this.trackInteraction('verification_link_click', {
                    card_id: cardId,
                    link_text: linkText,
                    link_url: href
                });

                // Add visual feedback
                $link.addClass('is-clicked');
                setTimeout(() => {
                    $link.removeClass('is-clicked');
                }, 200);

                // Handle external links
                if (href.startsWith('http') && !href.includes(window.location.hostname)) {
                    e.preventDefault();
                    this.handleExternalLink(href, linkText);
                }
            }.bind(this));

            // Keyboard support
            $verificationLinks.on('keydown', function(e) {
                if (e.key === 'Enter') {
                    $(this).trigger('click');
                }
            });
        }

        /**
         * Handle external link with confirmation
         */
        handleExternalLink(url, linkText) {
            const $modal = $(`
                <div class="external-link-modal">
                    <div class="modal-content">
                        <h3>External Link</h3>
                        <p>You are about to visit an external website:</p>
                        <p><strong>${linkText}</strong></p>
                        <div class="modal-actions">
                            <button class="btn-continue">Continue</button>
                            <button class="btn-cancel">Cancel</button>
                        </div>
                    </div>
                </div>
            `);

            $('body').append($modal);

            setTimeout(() => {
                $modal.addClass('active');
            }, 10);

            $modal.find('.btn-continue').on('click', () => {
                window.open(url, '_blank', 'noopener,noreferrer');
                this.closeModal($modal);
            });

            $modal.find('.btn-cancel').on('click', () => {
                this.closeModal($modal);
            });

            $modal.on('click', (e) => {
                if (e.target === $modal[0]) {
                    this.closeModal($modal);
                }
            });

            // Keyboard support
            $(document).on('keydown.external-link', (e) => {
                if (e.key === 'Escape') {
                    this.closeModal($modal);
                }
            });
        }

        /**
         * Close modal
         */
        closeModal($modal) {
            $modal.removeClass('active');
            setTimeout(() => {
                $modal.remove();
                $(document).off('keydown.external-link');
            }, 300);
        }

        /**
         * Setup accessibility enhancements
         */
        setupAccessibilityEnhancements() {
            // Add ARIA labels
            $('.trust-indicator-card').each((index, card) => {
                const $card = $(card);
                const title = $card.find('.trust-indicator__title').text();
                const description = $card.find('.trust-indicator__description').text();

                $card.attr('aria-label', `${title}: ${description}`);
            });

            // Focus management
            $('.trust-indicator-card').on('focusin', function() {
                $(this).addClass('has-focus');
            }).on('focusout', function() {
                $(this).removeClass('has-focus');
            });

            // Screen reader announcements
            this.setupScreenReaderAnnouncements();
        }

        /**
         * Setup screen reader announcements
         */
        setupScreenReaderAnnouncements() {
            // Announce when trust indicators come into view
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting && entry.intersectionRatio > 0.5) {
                            const $section = $(entry.target);
                            const sectionTitle = $section.find('.trust-indicators-main-title').text();

                            this.announceForScreenReader(`Now viewing ${sectionTitle} section with trust indicators`);
                        }
                    });
                }, {
                    threshold: 0.5
                });

                const $section = $('.trust-indicators-section');
                if ($section.length) {
                    observer.observe($section[0]);
                }
            }
        }

        /**
         * Setup intersection observer for animations
         */
        setupIntersectionObserver() {
            if (!('IntersectionObserver' in window)) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const $card = $(entry.target);

                        // Trigger entrance animation
                        setTimeout(() => {
                            $card.addClass('animate-in');
                        }, $card.index() * 200);

                        // Animate counters
                        const $counter = $card.find('.stat-number');
                        if ($counter.length) {
                            setTimeout(() => {
                                this.animateCounter($counter);
                            }, 500);
                        }

                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.3
            });

            $('.trust-indicator-card').each((index, card) => {
                observer.observe(card);
            });
        }

        /**
         * Announce content for screen readers
         */
        announceForScreenReader(message) {
            const $announcement = $('<div class="sr-only" aria-live="polite">');
            $announcement.text(message);
            $('body').append($announcement);

            setTimeout(() => {
                $announcement.remove();
            }, 1000);
        }

        /**
         * Setup performance tracking
         */
        setupPerformanceTracking() {
            // Track component load time
            const loadTime = performance.now() - this.performanceMetrics.startTime;

            if (loadTime > 100) {
                console.warn(`Trust Indicators: Load time ${loadTime.toFixed(2)}ms exceeds 100ms target`);
            }

            // Monitor performance periodically
            setInterval(() => {
                this.reportPerformanceMetrics();
            }, 30000); // Report every 30 seconds
        }

        /**
         * Track user interactions
         */
        trackInteraction(action, data = {}) {
            this.performanceMetrics.interactionCount++;

            // Send to analytics if available
            if (typeof gtag !== 'undefined') {
                gtag('event', action, {
                    event_category: 'trust_indicators',
                    ...data
                });
            }

            // Custom event for theme tracking
            $(document).trigger('trustIndicators:interaction', {
                action,
                data,
                timestamp: Date.now()
            });
        }

        /**
         * Log performance metrics
         */
        logPerformance(stage) {
            const currentTime = performance.now();
            const elapsed = currentTime - this.performanceMetrics.startTime;

            if (window.console && window.console.log) {
                console.log(`Trust Indicators ${stage}: ${elapsed.toFixed(2)}ms`);
            }
        }

        /**
         * Report performance metrics
         */
        reportPerformanceMetrics() {
            const metrics = {
                ...this.performanceMetrics,
                currentTime: performance.now(),
                animatedCountersCount: this.animatedCounters.size,
                memoryUsage: performance.memory ? {
                    used: performance.memory.usedJSHeapSize,
                    total: performance.memory.totalJSHeapSize,
                    limit: performance.memory.jsHeapSizeLimit
                } : null
            };

            // Send metrics to server if endpoint exists
            if (typeof trustIndicatorsData !== 'undefined' && trustIndicatorsData.ajaxUrl) {
                $.ajax({
                    url: trustIndicatorsData.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'log_trust_indicators_metrics',
                        metrics: metrics,
                        nonce: trustIndicatorsData.nonce
                    },
                    success: (response) => {
                        // Metrics logged successfully
                    },
                    error: (xhr, status, error) => {
                        console.warn('Failed to log trust indicators metrics:', error);
                    }
                });
            }
        }
    }

    // Initialize when DOM is ready
    $(document).ready(() => {
        new TrustIndicatorsInteractions();
    });

})(jQuery);
