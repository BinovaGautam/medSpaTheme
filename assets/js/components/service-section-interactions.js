/**
 * Service Section Interactions
 *
 * Provides enhanced user interactions for the service section component:
 * - Treatment button hover effects
 * - Visual content lazy loading
 * - Gallery interactions
 * - Video play functionality
 * - Accessibility enhancements
 * - Performance optimization
 *
 * @package MedSpaTheme
 * @subpackage JavaScript
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Service Section Interactions Class
     */
    class ServiceSectionInteractions {
        constructor() {
            this.initialized = false;
            this.performanceMetrics = {
                startTime: performance.now(),
                interactionCount: 0,
                lazyLoadedImages: 0
            };

            this.init();
        }

        /**
         * Initialize all interactions
         */
        init() {
            if (this.initialized) return;

            $(document).ready(() => {
                this.setupTreatmentButtons();
                this.setupVisualContentInteractions();
                this.setupLazyLoading();
                this.setupVideoPlayers();
                this.setupAccessibilityEnhancements();
                this.setupPerformanceTracking();

                this.initialized = true;
                this.logPerformance('initialization');
            });
        }

        /**
         * Setup treatment button interactions
         */
        setupTreatmentButtons() {
            const $treatmentButtons = $('.treatment-button');

            if ($treatmentButtons.length === 0) return;

            // Enhanced hover effects
            $treatmentButtons.on('mouseenter', function() {
                const $button = $(this);
                $button.addClass('is-hovered');

                // Add subtle animation delay for staggered effect
                const index = $button.index();
                $button.css('animation-delay', `${index * 50}ms`);
            });

            $treatmentButtons.on('mouseleave', function() {
                $(this).removeClass('is-hovered');
            });

            // Click tracking and analytics
            $treatmentButtons.on('click', (e) => {
                const $button = $(e.currentTarget);
                const treatmentName = $button.find('.treatment-name').text();
                const treatmentSlug = $button.attr('href')?.split('/').pop();

                this.trackInteraction('treatment_button_click', {
                    treatment_name: treatmentName,
                    treatment_slug: treatmentSlug,
                    section: $button.closest('.service-section').attr('id')
                });
            });

            // Keyboard navigation enhancement
            $treatmentButtons.on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    $(this).trigger('click');
                }
            });
        }

        /**
         * Setup visual content interactions
         */
        setupVisualContentInteractions() {
            this.setupGalleryInteractions();
            this.setupImageZoom();
            this.setupBeforeAfterComparison();
        }

        /**
         * Setup gallery interactions
         */
        setupGalleryInteractions() {
            const $galleries = $('.before-after-gallery, .treatment-results-gallery, .transformation-gallery, .experience-gallery');

            $galleries.each((index, gallery) => {
                const $gallery = $(gallery);
                const $images = $gallery.find('img');

                // Add loading states
                $images.on('load', function() {
                    $(this).addClass('loaded');
                });

                // Add error handling
                $images.on('error', function() {
                    $(this).addClass('error').attr('alt', 'Image temporarily unavailable');
                });
            });
        }

        /**
         * Setup image zoom functionality
         */
        setupImageZoom() {
            const $zoomableImages = $('.before-image img, .after-image img, .result-item img, .transformation-item img');

            $zoomableImages.on('click', function(e) {
                e.preventDefault();
                const $img = $(this);

                // Create zoom overlay
                const $overlay = $('<div class="image-zoom-overlay">');
                const $zoomedImg = $img.clone().addClass('zoomed-image');
                const $closeBtn = $('<button class="zoom-close-btn" aria-label="Close zoomed image">×</button>');

                $overlay.append($zoomedImg, $closeBtn);
                $('body').append($overlay);

                // Add zoom functionality
                setTimeout(() => {
                    $overlay.addClass('active');
                }, 10);

                // Close handlers
                $closeBtn.on('click', () => this.closeImageZoom($overlay));
                $overlay.on('click', (e) => {
                    if (e.target === $overlay[0]) {
                        this.closeImageZoom($overlay);
                    }
                });

                // Keyboard close
                $(document).on('keydown.zoom', (e) => {
                    if (e.key === 'Escape') {
                        this.closeImageZoom($overlay);
                    }
                });

                this.trackInteraction('image_zoom', {
                    image_alt: $img.attr('alt'),
                    gallery_type: $img.closest('[class*="gallery"]').attr('class')
                });
            });
        }

        /**
         * Close image zoom
         */
        closeImageZoom($overlay) {
            $overlay.removeClass('active');
            setTimeout(() => {
                $overlay.remove();
                $(document).off('keydown.zoom');
            }, 300);
        }

        /**
         * Setup before/after comparison
         */
        setupBeforeAfterComparison() {
            const $beforeAfterPairs = $('.before-after-pair');

            $beforeAfterPairs.each((index, pair) => {
                const $pair = $(pair);
                const $beforeImg = $pair.find('.before-image img');
                const $afterImg = $pair.find('.after-image img');

                // Add comparison slider functionality
                this.createComparisonSlider($pair, $beforeImg, $afterImg);
            });
        }

        /**
         * Create comparison slider
         */
        createComparisonSlider($pair, $beforeImg, $afterImg) {
            const $slider = $('<div class="comparison-slider">');
            const $handle = $('<div class="comparison-handle">');

            $pair.addClass('has-comparison').append($slider.append($handle));

            let isDragging = false;
            let startX = 0;
            let currentX = 50; // Start at 50%

            // Mouse events
            $handle.on('mousedown', (e) => {
                isDragging = true;
                startX = e.clientX;
                $pair.addClass('is-dragging');
            });

            $(document).on('mousemove', (e) => {
                if (!isDragging) return;

                const rect = $pair[0].getBoundingClientRect();
                const x = e.clientX - rect.left;
                const percentage = Math.max(0, Math.min(100, (x / rect.width) * 100));

                this.updateComparison($pair, percentage);
                currentX = percentage;
            });

            $(document).on('mouseup', () => {
                if (isDragging) {
                    isDragging = false;
                    $pair.removeClass('is-dragging');
                }
            });

            // Touch events
            $handle.on('touchstart', (e) => {
                isDragging = true;
                startX = e.touches[0].clientX;
                $pair.addClass('is-dragging');
            });

            $(document).on('touchmove', (e) => {
                if (!isDragging) return;

                const rect = $pair[0].getBoundingClientRect();
                const x = e.touches[0].clientX - rect.left;
                const percentage = Math.max(0, Math.min(100, (x / rect.width) * 100));

                this.updateComparison($pair, percentage);
                currentX = percentage;
            });

            $(document).on('touchend', () => {
                if (isDragging) {
                    isDragging = false;
                    $pair.removeClass('is-dragging');
                }
            });

            // Initialize position
            this.updateComparison($pair, currentX);
        }

        /**
         * Update comparison position
         */
        updateComparison($pair, percentage) {
            const $slider = $pair.find('.comparison-slider');
            const $afterImg = $pair.find('.after-image');

            $slider.css('left', `${percentage}%`);
            $afterImg.css('clip-path', `inset(0 0 0 ${percentage}%)`);
        }

        /**
         * Setup lazy loading for images
         */
        setupLazyLoading() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            const src = img.dataset.src || img.src;

                            if (src && !img.classList.contains('loaded')) {
                                img.src = src;
                                img.classList.add('loading');

                                img.onload = () => {
                                    img.classList.remove('loading');
                                    img.classList.add('loaded');
                                    this.performanceMetrics.lazyLoadedImages++;
                                };

                                observer.unobserve(img);
                            }
                        }
                    });
                });

                // Observe all images in service sections
                $('.service-visual-content img').each((index, img) => {
                    imageObserver.observe(img);
                });
            }
        }

        /**
         * Setup video players
         */
        setupVideoPlayers() {
            const $videoContainers = $('.treatment-video');

            $videoContainers.each((index, container) => {
                const $container = $(container);
                const $playButton = $container.find('.video-play-button');
                const $poster = $container.find('.video-poster');

                $playButton.on('click', (e) => {
                    e.preventDefault();
                    this.playVideo($container);
                });

                // Keyboard support
                $playButton.on('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.playVideo($container);
                    }
                });
            });
        }

        /**
         * Play video
         */
        playVideo($container) {
            const videoUrl = $container.data('video-url') || '#';

            if (videoUrl === '#') {
                // Show placeholder modal for demo
                this.showVideoPlaceholder($container);
                return;
            }

            // Create video element
            const $video = $('<video controls autoplay>');
            $video.attr('src', videoUrl);

            // Replace poster with video
            const $videoContainer = $container.find('.video-container');
            $videoContainer.html($video);

            this.trackInteraction('video_play', {
                video_url: videoUrl,
                section: $container.closest('.service-section').attr('id')
            });
        }

        /**
         * Show video placeholder
         */
        showVideoPlaceholder($container) {
            const $modal = $(`
                <div class="video-placeholder-modal">
                    <div class="modal-content">
                        <h3>Treatment Demonstration Video</h3>
                        <p>This video would showcase our advanced treatment process and technology.</p>
                        <button class="close-modal">Close</button>
                    </div>
                </div>
            `);

            $('body').append($modal);

            setTimeout(() => {
                $modal.addClass('active');
            }, 10);

            $modal.find('.close-modal').on('click', () => {
                $modal.removeClass('active');
                setTimeout(() => $modal.remove(), 300);
            });

            $modal.on('click', (e) => {
                if (e.target === $modal[0]) {
                    $modal.removeClass('active');
                    setTimeout(() => $modal.remove(), 300);
                }
            });
        }

        /**
         * Setup accessibility enhancements
         */
        setupAccessibilityEnhancements() {
            // Add ARIA labels for interactive elements
            $('.treatment-button').each((index, button) => {
                const $button = $(button);
                const treatmentName = $button.find('.treatment-name').text();
                $button.attr('aria-label', `Learn more about ${treatmentName} treatment`);
            });

            // Add focus indicators
            $('.service-section').on('focusin', function() {
                $(this).addClass('has-focus');
            }).on('focusout', function() {
                $(this).removeClass('has-focus');
            });

            // Announce section changes for screen readers
            this.setupSectionAnnouncements();
        }

        /**
         * Setup section announcements for screen readers
         */
        setupSectionAnnouncements() {
            const $sections = $('.service-section');

            if ('IntersectionObserver' in window) {
                const sectionObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting && entry.intersectionRatio > 0.5) {
                            const $section = $(entry.target);
                            const sectionTitle = $section.find('.service-title').text();

                            // Announce section for screen readers
                            this.announceForScreenReader(`Now viewing ${sectionTitle} section`);
                        }
                    });
                }, {
                    threshold: 0.5
                });

                $sections.each((index, section) => {
                    sectionObserver.observe(section);
                });
            }
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
                console.warn(`Service Section: Load time ${loadTime.toFixed(2)}ms exceeds 100ms target`);
            }

            // Track interaction performance
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
                    event_category: 'service_section',
                    ...data
                });
            }

            // Custom event for theme tracking
            $(document).trigger('serviceSection:interaction', {
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
                console.log(`Service Section ${stage}: ${elapsed.toFixed(2)}ms`);
            }
        }

        /**
         * Report performance metrics
         */
        reportPerformanceMetrics() {
            const metrics = {
                ...this.performanceMetrics,
                currentTime: performance.now(),
                memoryUsage: performance.memory ? {
                    used: performance.memory.usedJSHeapSize,
                    total: performance.memory.totalJSHeapSize,
                    limit: performance.memory.jsHeapSizeLimit
                } : null
            };

            // Send metrics to server if endpoint exists
            if (typeof serviceSectionData !== 'undefined' && serviceSectionData.ajaxUrl) {
                $.ajax({
                    url: serviceSectionData.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'log_service_section_metrics',
                        metrics: metrics,
                        nonce: serviceSectionData.nonce
                    },
                    success: (response) => {
                        // Metrics logged successfully
                    },
                    error: (xhr, status, error) => {
                        console.warn('Failed to log service section metrics:', error);
                    }
                });
            }
        }
    }

    // Initialize when DOM is ready
    $(document).ready(() => {
        new ServiceSectionInteractions();
    });

})(jQuery);
