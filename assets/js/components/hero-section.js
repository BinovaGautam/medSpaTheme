/**
 * Hero Section JavaScript - Luxury Hero with Quiz Integration
 *
 * Handles interactions, animations, and integration with the elegant quiz component
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Hero Section Manager
     */
    class HeroSectionManager {
        constructor() {
            this.heroSection = $('.hero-section.with-quiz');
            this.heroContent = this.heroSection.find('.hero-content');
            this.heroQuizContainer = this.heroSection.find('.hero-quiz-container');
            this.ctaButton = this.heroSection.find('.hero-cta-button');
            this.trustItems = this.heroSection.find('.hero-trust-item');
            this.features = this.heroSection.find('.hero-feature');

            this.init();
        }

        init() {
            if (this.heroSection.length === 0) return;

            this.bindEvents();
            this.initAnimations();
            this.initQuizIntegration();
            this.initAccessibility();
            this.initResponsiveHandling();
        }

        bindEvents() {
            // CTA button interactions
            this.ctaButton.on('click', this.handleCTAClick.bind(this));

            // Trust item hover effects
            this.trustItems.on('mouseenter', this.handleTrustItemHover.bind(this));
            this.trustItems.on('mouseleave', this.handleTrustItemLeave.bind(this));

            // Scroll-based animations
            $(window).on('scroll', this.handleScroll.bind(this));

            // Resize handling
            $(window).on('resize', this.handleResize.bind(this));
        }

        initAnimations() {
            // Intersection Observer for entrance animations
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('animate-in');
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '50px'
                });

                // Observe hero elements
                this.heroSection.find('.hero-title, .hero-subtitle, .hero-trust-indicators, .hero-features, .hero-primary-cta').each(function() {
                    observer.observe(this);
                });
            }

            // Fallback for browsers without IntersectionObserver
            if (!('IntersectionObserver' in window)) {
                this.heroSection.find('.hero-title, .hero-subtitle, .hero-trust-indicators, .hero-features, .hero-primary-cta').addClass('animate-in');
            }
        }

        initQuizIntegration() {
            // Listen for quiz events
            $(document).on('quiz:started', this.handleQuizStarted.bind(this));
            $(document).on('quiz:completed', this.handleQuizCompleted.bind(this));
            $(document).on('quiz:step-changed', this.handleQuizStepChanged.bind(this));

            // Smooth scroll to quiz when CTA is clicked
            this.ctaButton.on('click', (e) => {
                if ($(window).width() < 1024) { // Mobile/tablet
                    e.preventDefault();
                    this.scrollToQuiz();
                }
            });
        }

        initAccessibility() {
            // Keyboard navigation support
            this.ctaButton.on('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.ctaButton.trigger('click');
                }
            });

            // Focus management
            this.heroSection.on('focusin', '.hero-trust-item', function() {
                $(this).addClass('focus-visible');
            });

            this.heroSection.on('focusout', '.hero-trust-item', function() {
                $(this).removeClass('focus-visible');
            });

            // Reduced motion support
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                this.heroSection.addClass('reduced-motion');
            }
        }

        initResponsiveHandling() {
            this.updateLayout();

            // Handle orientation changes
            $(window).on('orientationchange', () => {
                setTimeout(() => {
                    this.updateLayout();
                }, 100);
            });
        }

        handleCTAClick(e) {
            // Track CTA click
            if (typeof gtag !== 'undefined') {
                gtag('event', 'click', {
                    event_category: 'Hero CTA',
                    event_label: 'Start Journey'
                });
            }

            // Add click animation
            this.ctaButton.addClass('clicked');
            setTimeout(() => {
                this.ctaButton.removeClass('clicked');
            }, 300);
        }

        handleTrustItemHover(e) {
            const $item = $(e.currentTarget);
            $item.addClass('hovered');

            // Add subtle parallax effect
            if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                $item.css('transform', 'translateY(-2px) scale(1.02)');
            }
        }

        handleTrustItemLeave(e) {
            const $item = $(e.currentTarget);
            $item.removeClass('hovered');
            $item.css('transform', '');
        }

        handleScroll() {
            const scrollTop = $(window).scrollTop();
            const windowHeight = $(window).height();

            // Parallax effect for hero content (if not reduced motion)
            if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                const parallaxOffset = scrollTop * 0.5;
                this.heroContent.css('transform', `translateY(${parallaxOffset}px)`);
            }

            // Update hero visibility
            const heroBottom = this.heroSection.offset().top + this.heroSection.outerHeight();
            const isHeroVisible = scrollTop < heroBottom;

            this.heroSection.toggleClass('scrolled-past', !isHeroVisible);
        }

        handleResize() {
            this.updateLayout();
        }

        updateLayout() {
            const windowWidth = $(window).width();

            // Update layout classes based on screen size
            this.heroSection.toggleClass('mobile-layout', windowWidth < 768);
            this.heroSection.toggleClass('tablet-layout', windowWidth >= 768 && windowWidth < 1024);
            this.heroSection.toggleClass('desktop-layout', windowWidth >= 1024);

            // Adjust quiz container height on desktop
            if (windowWidth >= 1024) {
                const contentHeight = this.heroContent.outerHeight();
                this.heroQuizContainer.css('min-height', contentHeight);
            } else {
                this.heroQuizContainer.css('min-height', '');
            }
        }

        scrollToQuiz() {
            const quizOffset = this.heroQuizContainer.offset().top;
            const headerHeight = $('.site-header').outerHeight() || 0;

            $('html, body').animate({
                scrollTop: quizOffset - headerHeight - 20
            }, 600, 'easeInOutCubic');
        }

        handleQuizStarted(e, data) {
            console.log('Hero: Quiz started', data);
            this.heroSection.addClass('quiz-active');

            // Track quiz start
            if (typeof gtag !== 'undefined') {
                gtag('event', 'quiz_start', {
                    event_category: 'Hero Quiz',
                    event_label: 'Treatment Discovery'
                });
            }
        }

        handleQuizCompleted(e, data) {
            console.log('Hero: Quiz completed', data);
            this.heroSection.addClass('quiz-completed');

            // Track quiz completion
            if (typeof gtag !== 'undefined') {
                gtag('event', 'quiz_complete', {
                    event_category: 'Hero Quiz',
                    event_label: 'Treatment Discovery',
                    value: data.score || 0
                });
            }

            // Show completion celebration
            this.showQuizCompletionCelebration();
        }

        handleQuizStepChanged(e, data) {
            // Update progress indicator if needed
            const progress = (data.currentStep / data.totalSteps) * 100;
            this.heroSection.css('--quiz-progress', `${progress}%`);
        }

        showQuizCompletionCelebration() {
            // Add celebration class for animations
            this.heroSection.addClass('quiz-celebration');

            setTimeout(() => {
                this.heroSection.removeClass('quiz-celebration');
            }, 3000);
        }

        // Public methods
        destroy() {
            $(window).off('scroll', this.handleScroll);
            $(window).off('resize', this.handleResize);
            $(window).off('orientationchange');
            $(document).off('quiz:started quiz:completed quiz:step-changed');
        }
    }

    /**
     * Initialize Hero Section on DOM ready
     */
    $(document).ready(function() {
        // Initialize hero section if present
        if ($('.hero-section.with-quiz').length > 0) {
            window.heroSectionManager = new HeroSectionManager();
        }
    });

    /**
     * Easing function for smooth animations
     */
    $.easing.easeInOutCubic = function(x, t, b, c, d) {
        if ((t /= d / 2) < 1) return c / 2 * t * t * t + b;
        return c / 2 * ((t -= 2) * t * t + 2) + b;
    };

    /**
     * CSS Custom Property Animation Support
     */
    function animateCSS(element, property, start, end, duration = 300) {
        if (typeof element.animate === 'function') {
            return element.animate([
                { [property]: start },
                { [property]: end }
            ], {
                duration: duration,
                easing: 'ease-in-out',
                fill: 'forwards'
            });
        } else {
            // Fallback for older browsers
            $(element).css(property, end);
            return Promise.resolve();
        }
    }

    // Export for global access
    window.HeroSectionManager = HeroSectionManager;
    window.animateCSS = animateCSS;

})(jQuery);
