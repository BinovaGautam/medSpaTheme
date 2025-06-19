/**
 * Visual Content Interactions - T11.7 Implementation
 *
 * Handles visual content interactions including:
 * - Lazy loading with intersection observer
 * - Image zoom and lightbox functionality
 * - Video player controls
 * - Before/after comparison sliders
 * - Performance optimization
 * - Accessibility enhancements
 *
 * @package MedSpaTheme
 * @subpackage JavaScript
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Visual Content Manager
     */
    class VisualContentManager {

        constructor() {
            this.config = {
                lazyLoadOffset: 100,
                animationDuration: 300,
                videoPlaybackThreshold: 0.5,
                imageZoomScale: 2.5,
                beforeAfterSensitivity: 0.5
            };

            this.observers = {
                lazyLoad: null,
                videoPlayback: null,
                animations: null
            };

            this.activeModals = [];
            this.touchStartX = 0;
            this.touchStartY = 0;

            this.init();
        }

        /**
         * Initialize visual content functionality
         */
        init() {
            this.setupLazyLoading();
            this.setupImageInteractions();
            this.setupVideoControls();
            this.setupBeforeAfterSliders();
            this.setupGalleryNavigation();
            this.setupAccessibilityFeatures();
            this.setupPerformanceOptimizations();

            // Initialize on DOM ready
            $(document).ready(() => {
                this.initializeComponents();
            });
        }

        /**
         * Setup lazy loading with intersection observer
         */
        setupLazyLoading() {
            if ('IntersectionObserver' in window) {
                this.observers.lazyLoad = new IntersectionObserver(
                    (entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                this.loadImage(entry.target);
                                this.observers.lazyLoad.unobserve(entry.target);
                            }
                        });
                    },
                    {
                        rootMargin: `${this.config.lazyLoadOffset}px`,
                        threshold: 0.1
                    }
                );

                // Observe all lazy-loadable images
                $('.visual-content-gallery img[loading="lazy"]').each((index, img) => {
                    this.observers.lazyLoad.observe(img);
                });
            } else {
                // Fallback for browsers without intersection observer
                this.setupLazyLoadingFallback();
            }
        }

        /**
         * Load image with optimization
         */
        loadImage(img) {
            const $img = $(img);
            const srcset = $img.attr('srcset');
            const src = $img.attr('src');

            // Create a new image to preload
            const preloadImg = new Image();

            preloadImg.onload = () => {
                $img.addClass('loaded');
                this.triggerImageLoadedEvent($img);
            };

            preloadImg.onerror = () => {
                $img.addClass('error');
                this.handleImageError($img);
            };

            // Load the image
            if (srcset) {
                preloadImg.srcset = srcset;
            }
            preloadImg.src = src;
        }

        /**
         * Setup image interactions (zoom, lightbox)
         */
        setupImageInteractions() {
            // Image zoom on hover
            $(document).on('mouseenter', '.visual-content-gallery img', (e) => {
                const $img = $(e.currentTarget);
                if (!$img.hasClass('zoomed')) {
                    this.enableImageZoom($img);
                }
            });

            $(document).on('mouseleave', '.visual-content-gallery img', (e) => {
                const $img = $(e.currentTarget);
                this.disableImageZoom($img);
            });

            // Click to open lightbox
            $(document).on('click', '.visual-content-gallery img', (e) => {
                e.preventDefault();
                const $img = $(e.currentTarget);
                this.openImageLightbox($img);
            });

            // Keyboard navigation for images
            $(document).on('keydown', '.visual-content-gallery img', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.openImageLightbox($(e.currentTarget));
                }
            });
        }

        /**
         * Enable image zoom effect
         */
        enableImageZoom($img) {
            $img.addClass('zoomed');
            $img.css({
                transform: `scale(${this.config.imageZoomScale})`,
                transition: `transform ${this.config.animationDuration}ms ease-out`
            });
        }

        /**
         * Disable image zoom effect
         */
        disableImageZoom($img) {
            $img.removeClass('zoomed');
            $img.css({
                transform: 'scale(1)',
                transition: `transform ${this.config.animationDuration}ms ease-out`
            });
        }

        /**
         * Open image in lightbox
         */
        openImageLightbox($img) {
            const src = $img.attr('src');
            const alt = $img.attr('alt');
            const caption = $img.closest('.visual-content-gallery').find('.gallery-title').text();

            const lightboxHTML = `
                <div class="visual-content-lightbox" role="dialog" aria-modal="true" aria-label="Image lightbox">
                    <div class="lightbox-backdrop" aria-hidden="true"></div>
                    <div class="lightbox-container">
                        <button class="lightbox-close" aria-label="Close lightbox">&times;</button>
                        <img src="${src}" alt="${alt}" class="lightbox-image" />
                        <div class="lightbox-caption">
                            <h4>${caption}</h4>
                            <p>${alt}</p>
                        </div>
                    </div>
                </div>
            `;

            const $lightbox = $(lightboxHTML);
            $('body').append($lightbox);
            this.activeModals.push($lightbox);

            // Animate in
            setTimeout(() => {
                $lightbox.addClass('active');
            }, 10);

            // Setup close handlers
            $lightbox.find('.lightbox-close, .lightbox-backdrop').on('click', () => {
                this.closeLightbox($lightbox);
            });

            // Keyboard navigation
            $(document).on('keydown.lightbox', (e) => {
                if (e.key === 'Escape') {
                    this.closeLightbox($lightbox);
                }
            });

            // Focus management
            $lightbox.find('.lightbox-close').focus();
        }

        /**
         * Close lightbox
         */
        closeLightbox($lightbox) {
            $lightbox.removeClass('active');

            setTimeout(() => {
                $lightbox.remove();
                this.activeModals = this.activeModals.filter(modal => modal !== $lightbox);
                $(document).off('keydown.lightbox');
            }, this.config.animationDuration);
        }

        /**
         * Setup video controls
         */
        setupVideoControls() {
            // Video play button interaction
            $(document).on('click', '.video-play-button', (e) => {
                e.preventDefault();
                const $button = $(e.currentTarget);
                const $videoContainer = $button.closest('.video-container');
                const videoUrl = $button.data('video-url');

                if (videoUrl && videoUrl !== '#') {
                    this.playVideo($videoContainer, videoUrl);
                } else {
                    this.showVideoPlaceholder($videoContainer);
                }
            });

            // Setup video intersection observer for autoplay
            if ('IntersectionObserver' in window) {
                this.observers.videoPlayback = new IntersectionObserver(
                    (entries) => {
                        entries.forEach(entry => {
                            const $video = $(entry.target);
                            if (entry.isIntersecting && entry.intersectionRatio >= this.config.videoPlaybackThreshold) {
                                this.handleVideoInView($video);
                            } else {
                                this.handleVideoOutOfView($video);
                            }
                        });
                    },
                    {
                        threshold: this.config.videoPlaybackThreshold
                    }
                );

                $('.treatment-video').each((index, video) => {
                    this.observers.videoPlayback.observe(video);
                });
            }
        }

        /**
         * Play video in container
         */
        playVideo($container, videoUrl) {
            const videoHTML = `
                <video class="treatment-video-player" controls autoplay muted>
                    <source src="${videoUrl}" type="video/mp4">
                    <p>Your browser doesn't support video playback. <a href="${videoUrl}">Download the video</a> instead.</p>
                </video>
            `;

            $container.html(videoHTML);
            $container.addClass('playing');

            // Track video interaction
            this.trackVideoPlay(videoUrl);
        }

        /**
         * Show video placeholder when no URL provided
         */
        showVideoPlaceholder($container) {
            const placeholderHTML = `
                <div class="video-placeholder">
                    <div class="placeholder-content">
                        <h4>Treatment Video</h4>
                        <p>Video demonstration coming soon</p>
                    </div>
                </div>
            `;

            $container.html(placeholderHTML);
            $container.addClass('placeholder');
        }

        /**
         * Setup before/after comparison sliders
         */
        setupBeforeAfterSliders() {
            // Initialize sliders
            $('.before-after-comparison').each((index, comparison) => {
                this.initializeBeforeAfterSlider($(comparison));
            });

            // Touch and mouse interactions
            $(document).on('mousedown touchstart', '.before-after-slider', (e) => {
                e.preventDefault();
                const $slider = $(e.currentTarget);
                const $comparison = $slider.closest('.before-after-comparison');

                this.startBeforeAfterDrag($comparison, e);
            });

            $(document).on('mousemove touchmove', (e) => {
                if (this.activeSlider) {
                    this.updateBeforeAfterSlider(this.activeSlider, e);
                }
            });

            $(document).on('mouseup touchend', () => {
                if (this.activeSlider) {
                    this.endBeforeAfterDrag();
                }
            });
        }

        /**
         * Initialize before/after slider
         */
        initializeBeforeAfterSlider($comparison) {
            if ($comparison.find('.before-after-slider').length > 0) {
                return; // Already initialized
            }

            const sliderHTML = `
                <div class="before-after-slider" role="slider" aria-label="Before and after comparison slider"
                     aria-valuemin="0" aria-valuemax="100" aria-valuenow="50" tabindex="0">
                    <div class="slider-handle">
                        <span class="slider-line"></span>
                        <span class="slider-button">⟷</span>
                    </div>
                </div>
            `;

            $comparison.append(sliderHTML);
            $comparison.addClass('has-slider');

            // Keyboard navigation
            $comparison.find('.before-after-slider').on('keydown', (e) => {
                this.handleSliderKeyboard($(e.currentTarget), e);
            });
        }

        /**
         * Start before/after drag interaction
         */
        startBeforeAfterDrag($comparison, e) {
            this.activeSlider = $comparison;
            $comparison.addClass('dragging');

            // Store initial touch/mouse position
            const clientX = e.type.includes('touch') ? e.originalEvent.touches[0].clientX : e.clientX;
            this.touchStartX = clientX;

            // Prevent text selection
            $('body').addClass('no-select');
        }

        /**
         * Update before/after slider position
         */
        updateBeforeAfterSlider($comparison, e) {
            const rect = $comparison[0].getBoundingClientRect();
            const clientX = e.type.includes('touch') ? e.originalEvent.touches[0].clientX : e.clientX;
            const x = clientX - rect.left;
            const percentage = Math.max(0, Math.min(100, (x / rect.width) * 100));

            // Update slider position
            const $slider = $comparison.find('.before-after-slider');
            $slider.css('left', `${percentage}%`);
            $slider.attr('aria-valuenow', Math.round(percentage));

            // Update before image clip
            const $beforeContainer = $comparison.find('.before-image-container');
            $beforeContainer.css('clip-path', `inset(0 ${100 - percentage}% 0 0)`);
        }

        /**
         * End before/after drag interaction
         */
        endBeforeAfterDrag() {
            if (this.activeSlider) {
                this.activeSlider.removeClass('dragging');
                this.activeSlider = null;
                $('body').removeClass('no-select');
            }
        }

        /**
         * Handle slider keyboard navigation
         */
        handleSliderKeyboard($slider, e) {
            const currentValue = parseInt($slider.attr('aria-valuenow'));
            let newValue = currentValue;

            switch (e.key) {
                case 'ArrowLeft':
                case 'ArrowDown':
                    newValue = Math.max(0, currentValue - 5);
                    break;
                case 'ArrowRight':
                case 'ArrowUp':
                    newValue = Math.min(100, currentValue + 5);
                    break;
                case 'Home':
                    newValue = 0;
                    break;
                case 'End':
                    newValue = 100;
                    break;
                default:
                    return; // Don't prevent default for other keys
            }

            e.preventDefault();

            // Update slider position
            $slider.css('left', `${newValue}%`);
            $slider.attr('aria-valuenow', newValue);

            // Update before image clip
            const $comparison = $slider.closest('.before-after-comparison');
            const $beforeContainer = $comparison.find('.before-image-container');
            $beforeContainer.css('clip-path', `inset(0 ${100 - newValue}% 0 0)`);
        }

        /**
         * Setup gallery navigation
         */
        setupGalleryNavigation() {
            // Gallery item focus management
            $('.visual-content-gallery').each((index, gallery) => {
                const $gallery = $(gallery);
                this.setupGalleryKeyboardNavigation($gallery);
            });
        }

        /**
         * Setup keyboard navigation for gallery
         */
        setupGalleryKeyboardNavigation($gallery) {
            const $items = $gallery.find('img, .video-play-button');

            $items.on('keydown', (e) => {
                const currentIndex = $items.index(e.currentTarget);
                let targetIndex = currentIndex;

                switch (e.key) {
                    case 'ArrowRight':
                        targetIndex = (currentIndex + 1) % $items.length;
                        break;
                    case 'ArrowLeft':
                        targetIndex = (currentIndex - 1 + $items.length) % $items.length;
                        break;
                    case 'Home':
                        targetIndex = 0;
                        break;
                    case 'End':
                        targetIndex = $items.length - 1;
                        break;
                    default:
                        return;
                }

                e.preventDefault();
                $items.eq(targetIndex).focus();
            });
        }

        /**
         * Setup accessibility features
         */
        setupAccessibilityFeatures() {
            // Add ARIA labels and roles
            $('.visual-content-gallery').attr('role', 'region');
            $('.before-after-comparison').attr('role', 'img');

            // Announce image loading status to screen readers
            $('.visual-content-gallery img').on('load', function() {
                const $img = $(this);
                $img.attr('aria-describedby', $img.attr('id') + '-status');

                // Create status announcement
                const statusId = $img.attr('id') + '-status';
                if (!$('#' + statusId).length) {
                    $('<div>')
                        .attr('id', statusId)
                        .addClass('sr-only')
                        .text('Image loaded successfully')
                        .appendTo('body');
                }
            });

            // High contrast mode support
            if (window.matchMedia && window.matchMedia('(prefers-contrast: high)').matches) {
                $('body').addClass('high-contrast-mode');
            }

            // Reduced motion support
            if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                $('body').addClass('reduced-motion-mode');
                this.config.animationDuration = 0;
            }
        }

        /**
         * Setup performance optimizations
         */
        setupPerformanceOptimizations() {
            // Debounce resize events
            let resizeTimeout;
            $(window).on('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    this.handleWindowResize();
                }, 150);
            });

            // Throttle scroll events
            let scrollTimeout;
            $(window).on('scroll', () => {
                if (!scrollTimeout) {
                    scrollTimeout = setTimeout(() => {
                        this.handleWindowScroll();
                        scrollTimeout = null;
                    }, 16); // ~60fps
                }
            });

            // Preload critical images
            this.preloadCriticalImages();
        }

        /**
         * Initialize components after DOM ready
         */
        initializeComponents() {
            // Add loaded class to critical images
            $('.critical-image').addClass('loaded');

            // Initialize any visible galleries
            $('.visual-content-gallery').each((index, gallery) => {
                const $gallery = $(gallery);
                if (this.isElementInViewport($gallery[0])) {
                    $gallery.addClass('visible');
                }
            });

            // Setup animation observer for scroll-triggered animations
            this.setupAnimationObserver();
        }

        /**
         * Setup animation observer
         */
        setupAnimationObserver() {
            if ('IntersectionObserver' in window) {
                this.observers.animations = new IntersectionObserver(
                    (entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                $(entry.target).addClass('animate-in');
                            }
                        });
                    },
                    {
                        threshold: 0.2,
                        rootMargin: '0px 0px -50px 0px'
                    }
                );

                $('.visual-content-gallery').each((index, gallery) => {
                    this.observers.animations.observe(gallery);
                });
            }
        }

        /**
         * Preload critical images
         */
        preloadCriticalImages() {
            $('.critical-image').each((index, img) => {
                const $img = $(img);
                const src = $img.attr('src');
                const srcset = $img.attr('srcset');

                if (src) {
                    const preloadImg = new Image();
                    if (srcset) preloadImg.srcset = srcset;
                    preloadImg.src = src;
                }
            });
        }

        /**
         * Handle window resize
         */
        handleWindowResize() {
            // Recalculate before/after slider positions
            $('.before-after-comparison.has-slider').each((index, comparison) => {
                const $comparison = $(comparison);
                const $slider = $comparison.find('.before-after-slider');
                const currentValue = parseInt($slider.attr('aria-valuenow'));

                // Update clip path based on new dimensions
                const $beforeContainer = $comparison.find('.before-image-container');
                $beforeContainer.css('clip-path', `inset(0 ${100 - currentValue}% 0 0)`);
            });
        }

        /**
         * Handle window scroll
         */
        handleWindowScroll() {
            // Update visible galleries
            $('.visual-content-gallery').each((index, gallery) => {
                const $gallery = $(gallery);
                if (this.isElementInViewport(gallery) && !$gallery.hasClass('visible')) {
                    $gallery.addClass('visible');
                }
            });
        }

        /**
         * Check if element is in viewport
         */
        isElementInViewport(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }

        /**
         * Handle image loaded event
         */
        triggerImageLoadedEvent($img) {
            $img.trigger('visualContentImageLoaded');
        }

        /**
         * Handle image error
         */
        handleImageError($img) {
            const altText = $img.attr('alt') || 'Image';
            const fallbackHTML = `
                <div class="image-error-placeholder">
                    <span class="error-icon">⚠</span>
                    <span class="error-text">${altText}</span>
                </div>
            `;

            $img.closest('.visual-content-gallery').append(fallbackHTML);
        }

        /**
         * Track video play event
         */
        trackVideoPlay(videoUrl) {
            // Analytics tracking can be added here
            if (typeof gtag !== 'undefined') {
                gtag('event', 'video_play', {
                    'video_url': videoUrl,
                    'event_category': 'visual_content'
                });
            }
        }

        /**
         * Handle video in view
         */
        handleVideoInView($video) {
            $video.addClass('in-view');
        }

        /**
         * Handle video out of view
         */
        handleVideoOutOfView($video) {
            $video.removeClass('in-view');
        }

        /**
         * Lazy loading fallback for older browsers
         */
        setupLazyLoadingFallback() {
            let lazyImages = $('.visual-content-gallery img[loading="lazy"]');

            const loadImagesInViewport = () => {
                lazyImages.each((index, img) => {
                    if (this.isElementInViewport(img)) {
                        this.loadImage(img);
                        lazyImages = lazyImages.not(img);
                    }
                });
            };

            // Load images on scroll and resize
            $(window).on('scroll resize', loadImagesInViewport);

            // Initial load
            loadImagesInViewport();
        }

        /**
         * Cleanup method
         */
        destroy() {
            // Disconnect observers
            if (this.observers.lazyLoad) this.observers.lazyLoad.disconnect();
            if (this.observers.videoPlayback) this.observers.videoPlayback.disconnect();
            if (this.observers.animations) this.observers.animations.disconnect();

            // Close any active modals
            this.activeModals.forEach($modal => {
                $modal.remove();
            });

            // Remove event listeners
            $(document).off('.visualContent');
            $(window).off('.visualContent');
        }
    }

    /**
     * Initialize Visual Content Manager
     */
    const visualContentManager = new VisualContentManager();

    // Make available globally
    window.VisualContentManager = visualContentManager;

    // jQuery plugin for easy initialization
    $.fn.visualContent = function(options) {
        return this.each(function() {
            const $element = $(this);
            if (!$element.data('visualContent')) {
                $element.data('visualContent', new VisualContentManager(options));
            }
        });
    };

})(jQuery);
