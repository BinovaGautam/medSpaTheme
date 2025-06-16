/**
 * Performance Optimizer
 * Handles lazy loading, critical resource preloading, and performance monitoring
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

class PerformanceOptimizer {
    constructor() {
        this.lazyImages = [];
        this.lazyVideos = [];
        this.criticalResources = [];
        this.performanceMetrics = {};

        this.init();
    }

    init() {
        this.setupLazyLoading();
        this.preloadCriticalResources();
        this.optimizeImages();
        this.setupPerformanceMonitoring();
        this.optimizeWebFonts();
    }

    /**
     * Setup lazy loading for images and videos
     */
    setupLazyLoading() {
        // Native lazy loading support check
        if ('loading' in HTMLImageElement.prototype) {
            this.setupNativeLazyLoading();
        } else {
            this.setupIntersectionObserverLazyLoading();
        }

        // Setup lazy loading for videos
        this.setupVideoLazyLoading();
    }

    /**
     * Setup native lazy loading for supported browsers
     */
    setupNativeLazyLoading() {
        const images = document.querySelectorAll('img[data-src]');
        images.forEach(img => {
            img.src = img.dataset.src;
            img.loading = 'lazy';
            img.removeAttribute('data-src');
            img.classList.add('lazy-loaded');
        });
    }

    /**
     * Setup Intersection Observer lazy loading for older browsers
     */
    setupIntersectionObserverLazyLoading() {
        if (!window.IntersectionObserver) return;

        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    this.loadImage(img);
                    observer.unobserve(img);
                }
            });
        }, {
            rootMargin: '50px 0px',
            threshold: 0.01
        });

        // Observe all images with data-src
        const lazyImages = document.querySelectorAll('img[data-src]');
        lazyImages.forEach(img => {
            imageObserver.observe(img);
            this.lazyImages.push(img);
        });

        // Observe background images
        const lazyBackgrounds = document.querySelectorAll('[data-bg]');
        lazyBackgrounds.forEach(element => {
            imageObserver.observe(element);
        });
    }

    /**
     * Load individual image with error handling
     */
    loadImage(img) {
        return new Promise((resolve, reject) => {
            const imageLoader = new Image();

            imageLoader.onload = () => {
                // Update src and srcset
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                }

                if (img.dataset.srcset) {
                    img.srcset = img.dataset.srcset;
                    img.removeAttribute('data-srcset');
                }

                // Handle background images
                if (img.dataset.bg) {
                    img.style.backgroundImage = `url(${img.dataset.bg})`;
                    img.removeAttribute('data-bg');
                }

                // Add loaded class for CSS transitions
                img.classList.add('lazy-loaded');

                // Track loading performance
                this.trackImageLoad(img);

                resolve(img);
            };

            imageLoader.onerror = () => {
                // Fallback image or placeholder
                img.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlIE5vdCBBdmFpbGFibGU8L3RleHQ+PC9zdmc+';
                img.classList.add('lazy-error');
                reject(new Error('Image failed to load'));
            };

            imageLoader.src = img.dataset.src;
        });
    }

    /**
     * Setup lazy loading for videos
     */
    setupVideoLazyLoading() {
        if (!window.IntersectionObserver) return;

        const videoObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const video = entry.target;

                    if (video.dataset.src) {
                        video.src = video.dataset.src;
                        video.removeAttribute('data-src');
                    }

                    // Load video sources
                    const sources = video.querySelectorAll('source[data-src]');
                    sources.forEach(source => {
                        source.src = source.dataset.src;
                        source.removeAttribute('data-src');
                    });

                    video.load();
                    video.classList.add('lazy-loaded');
                    videoObserver.unobserve(video);
                }
            });
        }, { rootMargin: '100px' });

        const lazyVideos = document.querySelectorAll('video[data-src]');
        lazyVideos.forEach(video => {
            videoObserver.observe(video);
            this.lazyVideos.push(video);
        });
    }

    /**
     * Preload critical resources
     */
    preloadCriticalResources() {
        const themeUrl = document.documentElement.dataset.themeUrl || '';
        const criticalResources = [
            // Critical CSS
            {
                href: `${themeUrl}/assets/css/treatment-page.css`,
                as: 'style',
                type: 'text/css'
            },
            // Critical JavaScript
            {
                href: `${themeUrl}/assets/js/treatment-tabs.js`,
                as: 'script',
                type: 'text/javascript'
            }
        ];

        criticalResources.forEach(resource => {
            this.preloadResource(resource);
        });
    }

    /**
     * Preload individual resource
     */
    preloadResource(resource) {
        const link = document.createElement('link');
        link.rel = 'preload';
        link.href = resource.href;
        link.as = resource.as;

        if (resource.type) {
            link.type = resource.type;
        }

        // Add to head
        document.head.appendChild(link);

        // Track preload success
        link.onload = () => {
            this.trackResourcePreload(resource, true);
        };

        link.onerror = () => {
            this.trackResourcePreload(resource, false);
        };
    }

    /**
     * Optimize images with responsive loading
     */
    optimizeImages() {
        // Add responsive image loading
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            // Add decoding attribute for better performance
            if (!img.hasAttribute('decoding')) {
                img.decoding = 'async';
            }

            // Add loading attribute if not present
            if (!img.hasAttribute('loading') && img.dataset.src) {
                img.loading = 'lazy';
            }
        });
    }

    /**
     * Setup performance monitoring
     */
    setupPerformanceMonitoring() {
        // Monitor Core Web Vitals
        this.monitorCoreWebVitals();

        // Monitor resource loading
        this.monitorResourceLoading();

        // Monitor user interactions
        this.monitorUserInteractions();
    }

    /**
     * Monitor Core Web Vitals
     */
    monitorCoreWebVitals() {
        // Largest Contentful Paint (LCP)
        if ('PerformanceObserver' in window) {
            try {
                const lcpObserver = new PerformanceObserver((list) => {
                    const entries = list.getEntries();
                    const lastEntry = entries[entries.length - 1];
                    this.performanceMetrics.lcp = lastEntry.startTime;
                    this.reportMetric('LCP', lastEntry.startTime);
                });

                lcpObserver.observe({ entryTypes: ['largest-contentful-paint'] });

                // First Input Delay (FID)
                const fidObserver = new PerformanceObserver((list) => {
                    const entries = list.getEntries();
                    entries.forEach(entry => {
                        this.performanceMetrics.fid = entry.processingStart - entry.startTime;
                        this.reportMetric('FID', entry.processingStart - entry.startTime);
                    });
                });

                fidObserver.observe({ entryTypes: ['first-input'] });

                // Cumulative Layout Shift (CLS)
                let clsValue = 0;
                const clsObserver = new PerformanceObserver((list) => {
                    const entries = list.getEntries();
                    entries.forEach(entry => {
                        if (!entry.hadRecentInput) {
                            clsValue += entry.value;
                        }
                    });
                    this.performanceMetrics.cls = clsValue;
                    this.reportMetric('CLS', clsValue);
                });

                clsObserver.observe({ entryTypes: ['layout-shift'] });
            } catch (e) {
                console.log('Performance Observer not fully supported');
            }
        }
    }

    /**
     * Monitor resource loading performance
     */
    monitorResourceLoading() {
        window.addEventListener('load', () => {
            if (performance.getEntriesByType) {
                const navigation = performance.getEntriesByType('navigation')[0];
                const resources = performance.getEntriesByType('resource');

                if (navigation) {
                    this.performanceMetrics.pageLoadTime = navigation.loadEventEnd - navigation.fetchStart;
                    this.performanceMetrics.domContentLoaded = navigation.domContentLoadedEventEnd - navigation.fetchStart;
                }

                this.performanceMetrics.resourceCount = resources.length;

                // Report page load metrics
                this.reportMetric('Page Load Time', this.performanceMetrics.pageLoadTime);
                this.reportMetric('DOM Content Loaded', this.performanceMetrics.domContentLoaded);
            }
        });
    }

    /**
     * Monitor user interactions
     */
    monitorUserInteractions() {
        // Track tab interactions
        const tabButtons = document.querySelectorAll('.treatment-tabs__tab');
        tabButtons.forEach(tab => {
            tab.addEventListener('click', () => {
                this.trackInteraction('tab_click', {
                    tab: tab.dataset.tab,
                    timestamp: Date.now()
                });
            });
        });

        // Track scroll depth
        this.trackScrollDepth();
    }

    /**
     * Track scroll depth for engagement metrics
     */
    trackScrollDepth() {
        const scrollDepths = [25, 50, 75, 100];
        const trackedDepths = new Set();

        const trackScroll = () => {
            const scrollPercent = Math.round(
                (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100
            );

            scrollDepths.forEach(depth => {
                if (scrollPercent >= depth && !trackedDepths.has(depth)) {
                    trackedDepths.add(depth);
                    this.trackInteraction('scroll_depth', {
                        depth: depth,
                        timestamp: Date.now()
                    });
                }
            });
        };

        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    trackScroll();
                    ticking = false;
                });
                ticking = true;
            }
        });
    }

    /**
     * Optimize web font loading
     */
    optimizeWebFonts() {
        // Font display optimization
        if ('fonts' in document) {
            document.fonts.ready.then(() => {
                document.body.classList.add('fonts-loaded');
            });
        }
    }

    /**
     * Track image loading performance
     */
    trackImageLoad(img) {
        const loadTime = performance.now();
        this.trackInteraction('image_loaded', {
            src: img.src,
            loadTime: loadTime,
            lazy: img.classList.contains('lazy-loaded')
        });
    }

    /**
     * Track resource preload performance
     */
    trackResourcePreload(resource, success) {
        this.trackInteraction('resource_preload', {
            href: resource.href,
            as: resource.as,
            success: success,
            timestamp: Date.now()
        });
    }

    /**
     * Report performance metric
     */
    reportMetric(name, value) {
        // Send to analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'performance_metric', {
                metric_name: name,
                metric_value: Math.round(value),
                page_path: window.location.pathname
            });
        }

        // Console log for debugging
        if (window.location.hostname.includes('local')) {
            console.log(`Performance Metric - ${name}:`, Math.round(value));
        }
    }

    /**
     * Track user interaction
     */
    trackInteraction(action, data) {
        // Send to analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', action, data);
        }

        // Console log for debugging
        if (window.location.hostname.includes('local')) {
            console.log(`Interaction - ${action}:`, data);
        }
    }

    /**
     * Get performance summary
     */
    getPerformanceSummary() {
        return {
            ...this.performanceMetrics,
            lazyImagesCount: this.lazyImages.length,
            lazyVideosCount: this.lazyVideos.length,
            timestamp: Date.now()
        };
    }
}

// Initialize performance optimizer when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.performanceOptimizer = new PerformanceOptimizer();
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PerformanceOptimizer;
}
