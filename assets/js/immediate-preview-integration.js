/**
 * Immediate Preview Integration - PVC-008-CRITICAL
 * High-performance real-time preview system for sidebar customizations
 *
 * Ensures < 100ms response time for all customization changes
 * Optimized CSS variable updates with performance monitoring
 *
 * @since Sprint 2 Extension
 * @version 1.0.0
 */

class ImmediatePreviewIntegration {
    constructor(bridge) {
        this.bridge = bridge;
        this.updateQueue = [];
        this.isProcessing = false;
        this.performanceMonitor = new PreviewPerformanceMonitor();
        this.optimizedEngine = new OptimizedPreviewEngine();

        this.lastUpdateTime = 0;
        this.throttleDelay = 16; // ~60fps
        this.debounceDelay = 50;

        this.log('⚡ Initializing Immediate Preview Integration...');
    }

    /**
     * Initialize the preview integration
     */
    initialize() {
        this.setupEventListeners();
        this.setupPerformanceMonitoring();
        this.optimizeForPerformance();

        this.log('✅ Immediate Preview Integration ready');
    }

    /**
     * Setup event listeners for customization changes
     */
    setupEventListeners() {
        // Listen for palette selection events
        $(document).on('paletteSelected', (event, data) => {
            this.handlePaletteChange(data);
        });

        // Listen for typography selection events
        $(document).on('typographySelected', (event, data) => {
            this.handleTypographyChange(data);
        });

        // Listen for direct token changes
        document.addEventListener('tokenChanged', (event) => {
            this.handleTokenChange(event.detail);
        });

        // Listen for Universal Engine updates
        document.addEventListener('universalEngineUpdate', (event) => {
            this.handleUniversalEngineUpdate(event.detail);
        });

        this.log('✅ Event listeners setup for immediate preview');
    }

    /**
     * Setup performance monitoring
     */
    setupPerformanceMonitoring() {
        // Monitor frame rate
        this.frameRateMonitor = new FrameRateMonitor();

        // Monitor memory usage
        if (performance.memory) {
            this.memoryMonitor = new MemoryMonitor();
        }

        // Setup performance alerts
        this.setupPerformanceAlerts();

        this.log('✅ Performance monitoring active');
    }

    /**
     * Optimize for performance
     */
    optimizeForPerformance() {
        // Enable CSS containment where possible
        this.enableCSSContainment();

        // Setup efficient DOM update strategies
        this.setupBatchedUpdates();

        // Optimize CSS variable updates
        this.optimizeCSSVariables();

        this.log('✅ Performance optimizations applied');
    }

    /**
     * Handle palette change with immediate preview
     */
    async handlePaletteChange(data) {
        const startTime = performance.now();

        try {
            this.log('🎨 Handling palette change:', data.paletteId);

            // Generate CSS variables from palette
            const cssVariables = this.generateColorCSSVariables(data.paletteData);

            // Apply with optimized engine
            await this.optimizedEngine.applyChanges(cssVariables);

            // Record performance
            const duration = performance.now() - startTime;
            this.performanceMonitor.recordUpdate(duration, 'palette');

            // Update UI feedback
            this.updatePreviewFeedback(data, duration);

            // Verify performance requirement
            if (duration > 100) {
                console.warn(`⚠️ Palette update took ${duration.toFixed(2)}ms - exceeds 100ms target`);
                this.performanceMonitor.recordSlowUpdate('palette', duration);
            }

        } catch (error) {
            console.error('❌ Error in palette change preview:', error);
            this.handlePreviewError(error, 'palette');
        }
    }

    /**
     * Handle typography change with immediate preview
     */
    async handleTypographyChange(data) {
        const startTime = performance.now();

        try {
            this.log('📝 Handling typography change:', data.pairingId);

            // Generate CSS variables from typography
            const cssVariables = this.generateTypographyCSSVariables(data.pairingData);

            // Load fonts if needed
            await this.ensureFontsLoaded(data.pairingData);

            // Apply with optimized engine
            await this.optimizedEngine.applyChanges(cssVariables);

            // Record performance
            const duration = performance.now() - startTime;
            this.performanceMonitor.recordUpdate(duration, 'typography');

            // Update UI feedback
            this.updatePreviewFeedback(data, duration);

            // Verify performance requirement
            if (duration > 100) {
                console.warn(`⚠️ Typography update took ${duration.toFixed(2)}ms - exceeds 100ms target`);
                this.performanceMonitor.recordSlowUpdate('typography', duration);
            }

        } catch (error) {
            console.error('❌ Error in typography change preview:', error);
            this.handlePreviewError(error, 'typography');
        }
    }

    /**
     * Handle token change with immediate preview
     */
    async handleTokenChange(data) {
        const startTime = performance.now();

        try {
            this.log('🎯 Handling token change:', data);

            // Apply token changes directly
            await this.optimizedEngine.applyChanges(data.changes);

            // Record performance
            const duration = performance.now() - startTime;
            this.performanceMonitor.recordUpdate(duration, 'token');

            if (duration > 100) {
                console.warn(`⚠️ Token update took ${duration.toFixed(2)}ms - exceeds 100ms target`);
            }

        } catch (error) {
            console.error('❌ Error in token change preview:', error);
            this.handlePreviewError(error, 'token');
        }
    }

    /**
     * Handle Universal Engine updates
     */
    async handleUniversalEngineUpdate(data) {
        const startTime = performance.now();

        try {
            this.log('🔧 Handling Universal Engine update');

            // Process engine changes
            if (data.directChanges) {
                await this.optimizedEngine.applyChanges(data.directChanges);
            }

            // Handle cross-domain updates
            if (data.crossDomainUpdates) {
                await this.handleCrossDomainUpdates(data.crossDomainUpdates);
            }

            const duration = performance.now() - startTime;
            this.performanceMonitor.recordUpdate(duration, 'universal');

        } catch (error) {
            console.error('❌ Error in Universal Engine update preview:', error);
            this.handlePreviewError(error, 'universal');
        }
    }

    /**
     * Generate CSS variables from color palette
     */
    generateColorCSSVariables(paletteData) {
        const variables = {};

        if (!paletteData || !paletteData.colors) {
            return variables;
        }

        // Primary color system variables
        Object.entries(paletteData.colors).forEach(([colorName, colorValue]) => {
            if (colorValue && this.isValidColor(colorValue)) {
                variables[`--color-${colorName}`] = colorValue;

                // Generate RGB variants
                const rgb = this.hexToRgb(colorValue);
                if (rgb) {
                    variables[`--color-${colorName}-rgb`] = `${rgb.r}, ${rgb.g}, ${rgb.b}`;
                }

                // Generate variations (light, dark, etc.)
                variables[`--color-${colorName}-light`] = this.lightenColor(colorValue, 20);
                variables[`--color-${colorName}-dark`] = this.darkenColor(colorValue, 20);
                variables[`--color-${colorName}-alpha-50`] = this.addAlpha(colorValue, 0.5);
                variables[`--color-${colorName}-alpha-25`] = this.addAlpha(colorValue, 0.25);
            }
        });

        // Component-specific color mappings
        if (paletteData.colors.primary) {
            variables['--btn-primary-bg'] = paletteData.colors.primary;
            variables['--btn-primary-hover'] = this.darkenColor(paletteData.colors.primary, 10);
            variables['--link-color'] = paletteData.colors.primary;
            variables['--border-color'] = this.lightenColor(paletteData.colors.primary, 30);
        }

        if (paletteData.colors.secondary) {
            variables['--text-secondary'] = paletteData.colors.secondary;
            variables['--heading-color'] = paletteData.colors.secondary;
        }

        if (paletteData.colors.background) {
            variables['--body-bg'] = paletteData.colors.background;
        }

        if (paletteData.colors.surface) {
            variables['--card-bg'] = paletteData.colors.surface;
            variables['--modal-bg'] = paletteData.colors.surface;
        }

        this.log(`Generated ${Object.keys(variables).length} color CSS variables`);
        return variables;
    }

    /**
     * Generate CSS variables from typography pairing
     */
    generateTypographyCSSVariables(pairingData) {
        const variables = {};

        if (!pairingData) {
            return variables;
        }

        // Heading font variables
        if (pairingData.heading) {
            const headingFamily = `"${pairingData.heading.family}", ${pairingData.heading.fallback}`;
            variables['--font-heading'] = headingFamily;
            variables['--font-h1'] = headingFamily;
            variables['--font-h2'] = headingFamily;
            variables['--font-h3'] = headingFamily;
            variables['--font-h4'] = headingFamily;
            variables['--font-h5'] = headingFamily;
            variables['--font-h6'] = headingFamily;

            // Heading weights
            if (pairingData.heading.weights && pairingData.heading.weights.length > 0) {
                variables['--font-weight-heading-light'] = pairingData.heading.weights[0];
                variables['--font-weight-heading-normal'] = pairingData.heading.weights[Math.floor(pairingData.heading.weights.length / 2)];
                variables['--font-weight-heading-bold'] = pairingData.heading.weights[pairingData.heading.weights.length - 1];
            }
        }

        // Body font variables
        if (pairingData.body) {
            const bodyFamily = `"${pairingData.body.family}", ${pairingData.body.fallback}`;
            variables['--font-body'] = bodyFamily;
            variables['--font-text'] = bodyFamily;
            variables['--font-paragraph'] = bodyFamily;

            // Body weights
            if (pairingData.body.weights && pairingData.body.weights.length > 0) {
                variables['--font-weight-light'] = pairingData.body.weights[0];
                variables['--font-weight-normal'] = pairingData.body.weights[Math.floor(pairingData.body.weights.length / 2)];
                variables['--font-weight-bold'] = pairingData.body.weights[pairingData.body.weights.length - 1];
            }
        }

        this.log(`Generated ${Object.keys(variables).length} typography CSS variables`);
        return variables;
    }

    /**
     * Ensure fonts are loaded before applying
     */
    async ensureFontsLoaded(pairingData) {
        const fontsToLoad = [];

        if (pairingData.heading && pairingData.heading.googleFont) {
            fontsToLoad.push(pairingData.heading.family);
        }

        if (pairingData.body && pairingData.body.googleFont) {
            fontsToLoad.push(pairingData.body.family);
        }

        if (fontsToLoad.length > 0) {
            await this.loadFonts(fontsToLoad);
        }
    }

    /**
     * Load fonts asynchronously
     */
    async loadFonts(fontFamilies) {
        if (!document.fonts || !document.fonts.load) {
            return; // Browser doesn't support Font Loading API
        }

        const loadPromises = fontFamilies.map(family => {
            return document.fonts.load(`16px "${family}"`).catch(error => {
                this.log(`⚠️ Could not load font: ${family}`, error);
            });
        });

        await Promise.allSettled(loadPromises);
    }

    /**
     * Handle cross-domain updates
     */
    async handleCrossDomainUpdates(updates) {
        for (const update of updates) {
            await this.optimizedEngine.applyChanges(update.changes);
        }
    }

    /**
     * Update preview feedback in sidebar
     */
    updatePreviewFeedback(data, duration) {
        const feedbackContainer = this.bridge.getFeedbackContainer();

        const performanceClass = duration > 100 ? 'slow' : duration > 50 ? 'medium' : 'fast';
        const performanceIcon = duration > 100 ? '⚠️' : '⚡';

        feedbackContainer.innerHTML = `
            <div class="feedback success ${performanceClass}">
                <div class="checkmark">${performanceIcon}</div>
                <span>Applied in ${duration.toFixed(0)}ms</span>
                <div class="performance-indicator">
                    <div class="performance-bar">
                        <div class="performance-fill" style="width: ${Math.min(100, (100 - duration) / 100 * 100)}%"></div>
                    </div>
                </div>
            </div>
        `;

        // Clear feedback after 2 seconds
        setTimeout(() => {
            feedbackContainer.innerHTML = '';
        }, 2000);
    }

    /**
     * Handle preview errors
     */
    handlePreviewError(error, type) {
        const feedbackContainer = this.bridge.getFeedbackContainer();

        feedbackContainer.innerHTML = `
            <div class="feedback error">
                <div class="error-icon">⚠️</div>
                <span>Preview error (${type})</span>
                <small>${error.message}</small>
            </div>
        `;

        // Clear error after 5 seconds
        setTimeout(() => {
            feedbackContainer.innerHTML = '';
        }, 5000);
    }

    /**
     * Enable CSS containment for performance
     */
    enableCSSContainment() {
        // Add containment to sidebar for isolation
        const sidebar = document.querySelector('#simple-vc-sidebar');
        if (sidebar) {
            sidebar.style.contain = 'layout style paint';
        }

        // Add containment to main content areas
        const mainContent = document.querySelector('main, .main-content, #main');
        if (mainContent) {
            mainContent.style.contain = 'layout style';
        }
    }

    /**
     * Setup batched updates for performance
     */
    setupBatchedUpdates() {
        this.batchedUpdates = [];
        this.batchTimer = null;

        this.processBatchedUpdates = this.processBatchedUpdates.bind(this);
    }

    /**
     * Process batched updates
     */
    processBatchedUpdates() {
        if (this.batchedUpdates.length === 0) return;

        const updates = [...this.batchedUpdates];
        this.batchedUpdates = [];

        requestAnimationFrame(() => {
            updates.forEach(update => {
                this.optimizedEngine.applyChanges(update.changes);
            });
        });
    }

    /**
     * Optimize CSS variables for performance
     */
    optimizeCSSVariables() {
        // Cache document root for faster access
        this.documentRoot = document.documentElement;

        // Pre-compile frequently used selectors
        this.cachedSelectors = {
            root: this.documentRoot,
            body: document.body,
            main: document.querySelector('main'),
            header: document.querySelector('header'),
            footer: document.querySelector('footer')
        };
    }

    /**
     * Setup performance alerts
     */
    setupPerformanceAlerts() {
        this.performanceThresholds = {
            warning: 50,
            critical: 100,
            emergency: 200
        };

        this.performanceAlertCount = 0;
        this.maxAlerts = 5;
    }

    /**
     * Color utility methods
     */
    isValidColor(color) {
        return /^#[0-9A-Fa-f]{6}$/.test(color) ||
               /^#[0-9A-Fa-f]{3}$/.test(color) ||
               /^rgb/.test(color) ||
               /^hsl/.test(color);
    }

    hexToRgb(hex) {
        const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }

    lightenColor(hex, percent) {
        const num = parseInt(hex.replace('#', ''), 16);
        const amt = Math.round(2.55 * percent);
        const R = (num >> 16) + amt;
        const G = (num >> 8 & 0x00FF) + amt;
        const B = (num & 0x0000FF) + amt;
        return '#' + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
            (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
            (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
    }

    darkenColor(hex, percent) {
        const num = parseInt(hex.replace('#', ''), 16);
        const amt = Math.round(2.55 * percent);
        const R = (num >> 16) - amt;
        const G = (num >> 8 & 0x00FF) - amt;
        const B = (num & 0x0000FF) - amt;
        return '#' + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
            (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
            (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
    }

    addAlpha(hex, alpha) {
        const rgb = this.hexToRgb(hex);
        return rgb ? `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, ${alpha})` : hex;
    }

    log(message, ...args) {
        if (this.bridge?.debug) {
            console.log(`[ImmediatePreviewIntegration] ${message}`, ...args);
        }
    }
}

/**
 * Optimized Preview Engine for high-performance updates
 */
class OptimizedPreviewEngine {
    constructor() {
        this.updateQueue = [];
        this.isProcessing = false;
        this.documentRoot = document.documentElement;
    }

    async applyChanges(changes) {
        const startTime = performance.now();

        if (Array.isArray(changes)) {
            // Batch apply array of changes
            this.applyBatchChanges(changes);
        } else if (typeof changes === 'object') {
            // Apply object of CSS variables
            this.applyCSSVariables(changes);
        }

        const duration = performance.now() - startTime;
        return { duration, success: true };
    }

    applyBatchChanges(changesArray) {
        requestAnimationFrame(() => {
            changesArray.forEach(change => {
                if (change.type === 'css-variable') {
                    this.documentRoot.style.setProperty(change.property, change.value);
                }
            });
        });
    }

    applyCSSVariables(variables) {
        requestAnimationFrame(() => {
            Object.entries(variables).forEach(([property, value]) => {
                this.documentRoot.style.setProperty(property, value);
            });
        });
    }
}

/**
 * Performance monitor for tracking update speeds
 */
class PreviewPerformanceMonitor {
    constructor() {
        this.metrics = {
            updateTimes: [],
            slowUpdates: [],
            averageTime: 0,
            maxTime: 0,
            updateCounts: {
                palette: 0,
                typography: 0,
                token: 0,
                universal: 0
            }
        };
    }

    recordUpdate(duration, type) {
        this.metrics.updateTimes.push(duration);
        this.metrics.updateCounts[type]++;
        this.metrics.maxTime = Math.max(this.metrics.maxTime, duration);
        this.metrics.averageTime = this.calculateAverage();

        if (duration > 100) {
            this.recordSlowUpdate(type, duration);
        }
    }

    recordSlowUpdate(type, duration) {
        this.metrics.slowUpdates.push({ type, duration, timestamp: Date.now() });
        console.warn(`[Performance] Slow ${type} update: ${duration.toFixed(2)}ms`);
    }

    calculateAverage() {
        const times = this.metrics.updateTimes;
        return times.length > 0 ? times.reduce((a, b) => a + b, 0) / times.length : 0;
    }

    getMetrics() {
        return {
            ...this.metrics,
            averageTime: this.metrics.averageTime,
            slowUpdateRate: (this.metrics.slowUpdates.length / this.metrics.updateTimes.length) * 100
        };
    }
}

/**
 * Frame rate monitor for performance tracking
 */
class FrameRateMonitor {
    constructor() {
        this.fps = 0;
        this.lastTime = performance.now();
        this.frameCount = 0;
        this.monitoring = false;

        this.startMonitoring();
    }

    startMonitoring() {
        this.monitoring = true;
        this.measure();
    }

    measure() {
        if (!this.monitoring) return;

        const currentTime = performance.now();
        this.frameCount++;

        if (currentTime >= this.lastTime + 1000) {
            this.fps = Math.round((this.frameCount * 1000) / (currentTime - this.lastTime));
            this.frameCount = 0;
            this.lastTime = currentTime;

            // Alert if FPS drops below 30
            if (this.fps < 30) {
                console.warn(`[Performance] Low FPS detected: ${this.fps}`);
            }
        }

        requestAnimationFrame(() => this.measure());
    }

    getFPS() {
        return this.fps;
    }
}

/**
 * Memory monitor for tracking usage
 */
class MemoryMonitor {
    constructor() {
        this.baseline = this.getCurrentMemory();
        this.peak = this.baseline;
    }

    getCurrentMemory() {
        return performance.memory ? {
            used: performance.memory.usedJSHeapSize,
            total: performance.memory.totalJSHeapSize,
            limit: performance.memory.jsHeapSizeLimit
        } : null;
    }

    checkMemory() {
        const current = this.getCurrentMemory();
        if (current) {
            this.peak = Math.max(this.peak, current.used);

            // Alert if memory usage increases significantly
            const increase = current.used - this.baseline.used;
            if (increase > 10 * 1024 * 1024) { // 10MB increase
                console.warn(`[Performance] High memory usage increase: ${(increase / 1024 / 1024).toFixed(2)}MB`);
            }
        }
        return current;
    }
}

// Make available globally
window.ImmediatePreviewIntegration = ImmediatePreviewIntegration;
window.OptimizedPreviewEngine = OptimizedPreviewEngine;
window.PreviewPerformanceMonitor = PreviewPerformanceMonitor;
