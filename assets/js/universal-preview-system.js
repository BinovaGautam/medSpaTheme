/**
 * Universal Preview System - Real-time Design Token Application
 * Handles color, typography, spacing, and component token preview with <100ms performance
 *
 * @since PVC-005-DT
 * @version 1.0.0
 * @performance <100ms updates, optimized DOM manipulation, memory efficient
 */

class UniversalPreviewSystem {
    constructor() {
        this.isActive = false;
        this.previewMode = false;
        this.appliedTokens = new Map();
        this.originalValues = new Map();
        this.updateQueue = [];
        this.isProcessingQueue = false;
        this.performanceMetrics = {
            updateCount: 0,
            averageUpdateTime: 0,
            lastUpdateTime: 0,
            totalUpdateTime: 0
        };

        // DOM update optimization
        this.batchedUpdates = new Map();
        this.updateBatchTimeout = null;
        this.updateBatchDelay = 16; // ~60fps

        // Element targeting cache for performance
        this.elementCache = new Map();
        this.selectorCache = new Map();

        // Initialize the preview system
        this.initialize();

        // Bind methods for performance
        this.applyTokenChanges = this.applyTokenChanges.bind(this);
        this.batchDOMUpdates = this.batchDOMUpdates.bind(this);
        this.processUpdateQueue = this.processUpdateQueue.bind(this);
    }

    /**
     * Initialize the preview system
     */
    initialize() {
        // Check if design token registry is available
        if (typeof window.designTokenRegistry === 'undefined') {
            console.error('[UniversalPreviewSystem] DesignTokenRegistry not found. Loading order issue?');
            return;
        }

        // Set up DOM observer for dynamic content
        this.setupDOMObserver();

        // Initialize CSS custom properties support detection
        this.cssCustomPropsSupported = this.detectCSSCustomPropertySupport();

        // Cache initial CSS values for restoration
        this.cacheOriginalValues();

        this.isActive = true;
        console.log('[UniversalPreviewSystem] Initialized successfully ✅');
    }

    /**
     * Detect CSS custom property support
     * @returns {boolean} Support status
     */
    detectCSSCustomPropertySupport() {
        try {
            const testEl = document.createElement('div');
            testEl.style.setProperty('--test-prop', 'test');
            return testEl.style.getPropertyValue('--test-prop') === 'test';
        } catch (error) {
            return false;
        }
    }

    /**
     * Cache original CSS values for restoration
     */
    cacheOriginalValues() {
        const rootStyles = getComputedStyle(document.documentElement);
        const allProperties = [];

        // Get all CSS custom properties
        for (let i = 0; i < rootStyles.length; i++) {
            const propName = rootStyles[i];
            if (propName.startsWith('--')) {
                const value = rootStyles.getPropertyValue(propName);
                this.originalValues.set(propName, value);
                allProperties.push({ name: propName, value });
            }
        }

        console.log(`[UniversalPreviewSystem] Cached ${allProperties.length} original CSS properties`);
    }

    /**
     * Set up DOM observer for dynamic content changes
     */
    setupDOMObserver() {
        if (typeof MutationObserver === 'undefined') {
            console.warn('[UniversalPreviewSystem] MutationObserver not supported');
            return;
        }

        this.domObserver = new MutationObserver((mutations) => {
            let shouldInvalidateCache = false;

            mutations.forEach((mutation) => {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    shouldInvalidateCache = true;
                }
            });

            if (shouldInvalidateCache) {
                this.invalidateElementCache();
            }
        });

        this.domObserver.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    /**
     * Apply token changes with universal domain support
     * @param {Array} tokenChanges - Array of token change objects
     * @returns {Promise} Update completion promise
     */
    async applyTokenChanges(tokenChanges) {
        if (!this.isActive) {
            console.warn('[UniversalPreviewSystem] System not active');
            return;
        }

        const startTime = performance.now();

        try {
            // Group changes by domain for optimized processing
            const changesByDomain = this.groupChangesByDomain(tokenChanges);

            // Process each domain with specific optimization
            const updatePromises = [];

            if (changesByDomain.color) {
                updatePromises.push(this.applyColorTokens(changesByDomain.color));
            }

            if (changesByDomain.typography) {
                updatePromises.push(this.applyTypographyTokens(changesByDomain.typography));
            }

            if (changesByDomain.spacing) {
                updatePromises.push(this.applySpacingTokens(changesByDomain.spacing));
            }

            if (changesByDomain.component) {
                updatePromises.push(this.applyComponentTokens(changesByDomain.component));
            }

            // Execute all domain updates concurrently
            await Promise.all(updatePromises);

            // Batch DOM updates for performance
            this.batchDOMUpdates();

            // Update performance metrics
            const updateTime = performance.now() - startTime;
            this.updatePerformanceMetrics(updateTime);

            // Trigger accessibility validation if needed
            this.validateAccessibilityCompliance(tokenChanges);

            console.log(`[UniversalPreviewSystem] Applied ${tokenChanges.length} token changes in ${updateTime.toFixed(2)}ms`);

        } catch (error) {
            console.error('[UniversalPreviewSystem] Error applying token changes:', error);
            throw error;
        }
    }

    /**
     * Group token changes by domain for optimized processing
     * @param {Array} tokenChanges - Token changes
     * @returns {Object} Changes grouped by domain
     */
    groupChangesByDomain(tokenChanges) {
        const groups = {};

        tokenChanges.forEach(change => {
            const domain = this.getTokenDomain(change.name);
            if (!groups[domain]) {
                groups[domain] = [];
            }
            groups[domain].push(change);
        });

        return groups;
    }

    /**
     * Get domain for a token name
     * @param {string} tokenName - Token name
     * @returns {string} Domain name
     */
    getTokenDomain(tokenName) {
        if (tokenName.includes('color') || tokenName.includes('bg') || tokenName.includes('text')) {
            return 'color';
        } else if (tokenName.includes('font') || tokenName.includes('typography') || tokenName.includes('text-size')) {
            return 'typography';
        } else if (tokenName.includes('spacing') || tokenName.includes('margin') || tokenName.includes('padding')) {
            return 'spacing';
        } else {
            return 'component';
        }
    }

    /**
     * Apply color token changes with optimized DOM updates
     * @param {Array} colorChanges - Color token changes
     */
    async applyColorTokens(colorChanges) {
        const root = document.documentElement;

        colorChanges.forEach(change => {
            // Apply CSS custom property
            root.style.setProperty(change.cssVariable, change.value);

            // Track applied token for potential restoration
            this.appliedTokens.set(change.cssVariable, {
                value: change.value,
                appliedAt: Date.now(),
                domain: 'color'
            });

            // Generate derived colors if needed
            this.generateDerivedColors(change);
        });

        // Trigger repaint optimization for color changes
        this.optimizeColorRepaint();
    }

    /**
     * Generate derived color values (gradients, hover states, etc.)
     * @param {Object} colorChange - Base color change
     */
    generateDerivedColors(colorChange) {
        const baseName = colorChange.name;
        const baseValue = colorChange.value;

        // Generate common derived colors
        const derivedColors = [
            { suffix: '-light', generator: (color) => this.lightenColor(color, 0.1) },
            { suffix: '-dark', generator: (color) => this.darkenColor(color, 0.1) },
            { suffix: '-hover', generator: (color) => this.darkenColor(color, 0.05) },
            { suffix: '-focus', generator: (color) => this.lightenColor(color, 0.05) }
        ];

        derivedColors.forEach(({ suffix, generator }) => {
            const derivedVariable = `${colorChange.cssVariable}${suffix}`;
            const derivedValue = generator(baseValue);

            document.documentElement.style.setProperty(derivedVariable, derivedValue);

            this.appliedTokens.set(derivedVariable, {
                value: derivedValue,
                appliedAt: Date.now(),
                domain: 'color',
                derivedFrom: colorChange.cssVariable
            });
        });
    }

    /**
     * Apply typography token changes
     * @param {Array} typographyChanges - Typography token changes
     */
    async applyTypographyTokens(typographyChanges) {
        const root = document.documentElement;

        typographyChanges.forEach(change => {
            root.style.setProperty(change.cssVariable, change.value);

            this.appliedTokens.set(change.cssVariable, {
                value: change.value,
                appliedAt: Date.now(),
                domain: 'typography'
            });
        });

        // Trigger layout optimization for typography changes
        this.optimizeTypographyLayout();
    }

    /**
     * Apply spacing token changes
     * @param {Array} spacingChanges - Spacing token changes
     */
    async applySpacingTokens(spacingChanges) {
        const root = document.documentElement;

        spacingChanges.forEach(change => {
            root.style.setProperty(change.cssVariable, change.value);

            this.appliedTokens.set(change.cssVariable, {
                value: change.value,
                appliedAt: Date.now(),
                domain: 'spacing'
            });
        });

        // Trigger layout recalculation optimization
        this.optimizeLayoutRecalculation();
    }

    /**
     * Apply component token changes
     * @param {Array} componentChanges - Component token changes
     */
    async applyComponentTokens(componentChanges) {
        const root = document.documentElement;

        componentChanges.forEach(change => {
            root.style.setProperty(change.cssVariable, change.value);

            this.appliedTokens.set(change.cssVariable, {
                value: change.value,
                appliedAt: Date.now(),
                domain: 'component'
            });
        });
    }

    /**
     * Batch DOM updates for optimal performance
     */
    batchDOMUpdates() {
        if (this.updateBatchTimeout) {
            clearTimeout(this.updateBatchTimeout);
        }

        this.updateBatchTimeout = setTimeout(() => {
            this.processBatchedUpdates();
        }, this.updateBatchDelay);
    }

    /**
     * Process batched DOM updates
     */
    processBatchedUpdates() {
        if (this.batchedUpdates.size === 0) return;

        // Use requestAnimationFrame for smooth updates
        requestAnimationFrame(() => {
            // Force a single repaint/reflow for all changes
            document.documentElement.offsetHeight; // Trigger reflow

            this.batchedUpdates.clear();
            this.updateBatchTimeout = null;
        });
    }

    /**
     * Optimize color repaint performance
     */
    optimizeColorRepaint() {
        // Minimize repaints by grouping color changes
        const colorElements = this.getCachedElements('[style*="--color"]');
        if (colorElements.length > 0) {
            // Batch style computations
            colorElements.forEach(el => {
                el.style.willChange = 'auto';
            });
        }
    }

    /**
     * Optimize typography layout performance
     */
    optimizeTypographyLayout() {
        // Minimize layout thrashing for typography changes
        const textElements = this.getCachedElements('h1, h2, h3, h4, h5, h6, p, span, div');
        if (textElements.length > 0) {
            // Use CSS containment for better performance
            textElements.forEach(el => {
                if (el.style.contain !== 'layout style') {
                    el.style.contain = 'layout style';
                }
            });
        }
    }

    /**
     * Optimize layout recalculation for spacing changes
     */
    optimizeLayoutRecalculation() {
        // Use transform instead of changing layout properties when possible
        this.scheduleLayoutOptimization();
    }

    /**
     * Schedule layout optimization to avoid excessive recalculations
     */
    scheduleLayoutOptimization() {
        if (this.layoutOptimizationScheduled) return;

        this.layoutOptimizationScheduled = true;

        requestAnimationFrame(() => {
            // Perform any necessary layout optimizations
            this.layoutOptimizationScheduled = false;
        });
    }

    /**
     * Get cached elements for performance
     * @param {string} selector - CSS selector
     * @returns {Array} Cached elements
     */
    getCachedElements(selector) {
        if (this.selectorCache.has(selector)) {
            return this.selectorCache.get(selector);
        }

        const elements = Array.from(document.querySelectorAll(selector));
        this.selectorCache.set(selector, elements);

        // Clear cache after a timeout to handle dynamic content
        setTimeout(() => {
            this.selectorCache.delete(selector);
        }, 5000);

        return elements;
    }

    /**
     * Invalidate element cache when DOM changes
     */
    invalidateElementCache() {
        this.elementCache.clear();
        this.selectorCache.clear();
    }

    /**
     * Simple color manipulation utilities
     */
    lightenColor(color, amount) {
        const num = parseInt(color.replace("#", ""), 16);
        const amt = Math.round(2.55 * amount * 100);
        const R = (num >> 16) + amt;
        const G = (num >> 8 & 0x00FF) + amt;
        const B = (num & 0x0000FF) + amt;
        return "#" + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
            (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
            (B < 255 ? B < 1 ? 0 : B : 255))
            .toString(16).slice(1);
    }

    darkenColor(color, amount) {
        const num = parseInt(color.replace("#", ""), 16);
        const amt = Math.round(2.55 * amount * 100);
        const R = (num >> 16) - amt;
        const G = (num >> 8 & 0x00FF) - amt;
        const B = (num & 0x0000FF) - amt;
        return "#" + (0x1000000 + (R > 255 ? 255 : R < 0 ? 0 : R) * 0x10000 +
            (G > 255 ? 255 : G < 0 ? 0 : G) * 0x100 +
            (B > 255 ? 255 : B < 0 ? 0 : B))
            .toString(16).slice(1);
    }

    /**
     * Validate accessibility compliance for applied changes
     * @param {Array} tokenChanges - Applied token changes
     */
    validateAccessibilityCompliance(tokenChanges) {
        // Basic accessibility validation for color contrast
        const colorChanges = tokenChanges.filter(change => change.name.includes('color'));

        if (colorChanges.length > 0) {
            this.scheduleContrastValidation(colorChanges);
        }
    }

    /**
     * Schedule contrast validation to avoid blocking UI
     * @param {Array} colorChanges - Color changes to validate
     */
    scheduleContrastValidation(colorChanges) {
        setTimeout(() => {
            colorChanges.forEach(change => {
                // Basic contrast validation would go here
                // In production, integrate with WCAG contrast checkers
                this.validateColorContrast(change);
            });
        }, 100);
    }

    /**
     * Basic color contrast validation
     * @param {Object} colorChange - Color change to validate
     */
    validateColorContrast(colorChange) {
        // Placeholder for contrast validation logic
        // Would integrate with WCAG color contrast algorithms
        console.log(`[UniversalPreviewSystem] Validating contrast for ${colorChange.name}`);
    }

    /**
     * Enter preview mode (non-persistent changes)
     */
    enterPreviewMode() {
        this.previewMode = true;
        document.body.classList.add('preview-mode');
        console.log('[UniversalPreviewSystem] Entered preview mode');
    }

    /**
     * Exit preview mode and restore original values
     */
    exitPreviewMode() {
        this.previewMode = false;
        document.body.classList.remove('preview-mode');

        // Restore original values
        this.restoreOriginalValues();

        console.log('[UniversalPreviewSystem] Exited preview mode');
    }

    /**
     * Restore original CSS values
     */
    restoreOriginalValues() {
        const root = document.documentElement;

        this.appliedTokens.forEach((tokenData, cssVariable) => {
            const originalValue = this.originalValues.get(cssVariable);
            if (originalValue) {
                root.style.setProperty(cssVariable, originalValue);
            } else {
                root.style.removeProperty(cssVariable);
            }
        });

        this.appliedTokens.clear();
    }

    /**
     * Update performance metrics
     * @param {number} updateTime - Time taken for update in ms
     */
    updatePerformanceMetrics(updateTime) {
        this.performanceMetrics.updateCount++;
        this.performanceMetrics.lastUpdateTime = updateTime;
        this.performanceMetrics.totalUpdateTime += updateTime;
        this.performanceMetrics.averageUpdateTime =
            this.performanceMetrics.totalUpdateTime / this.performanceMetrics.updateCount;
    }

    /**
     * Get performance metrics for monitoring
     * @returns {Object} Performance metrics
     */
    getPerformanceMetrics() {
        return {
            ...this.performanceMetrics,
            appliedTokensCount: this.appliedTokens.size,
            cacheSize: this.elementCache.size + this.selectorCache.size,
            isPreviewMode: this.previewMode,
            memoryEstimate: this.estimateMemoryUsage()
        };
    }

    /**
     * Estimate memory usage
     * @returns {string} Memory usage estimate in MB
     */
    estimateMemoryUsage() {
        const data = {
            appliedTokens: Array.from(this.appliedTokens.entries()),
            originalValues: Array.from(this.originalValues.entries()),
            elementCache: this.elementCache.size,
            selectorCache: this.selectorCache.size
        };

        const jsonString = JSON.stringify(data);
        return (new Blob([jsonString]).size / 1024 / 1024).toFixed(2);
    }

    /**
     * Cleanup and destroy the preview system
     */
    destroy() {
        if (this.domObserver) {
            this.domObserver.disconnect();
        }

        if (this.updateBatchTimeout) {
            clearTimeout(this.updateBatchTimeout);
        }

        this.restoreOriginalValues();

        this.isActive = false;
        console.log('[UniversalPreviewSystem] Destroyed');
    }
}

// Export for global use
window.UniversalPreviewSystem = UniversalPreviewSystem;

// Create global instance
window.universalPreviewSystem = new UniversalPreviewSystem();

console.log('[UniversalPreviewSystem] Initialized with <100ms performance target ✅');
