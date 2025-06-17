/**
 * T8.3 Enhanced Semantic Token Customizer Preview
 *
 * Live Preview Enhancement - Real-time palette preview with smooth transitions
 * and comprehensive component coverage for WordPress Customizer
 *
 * @package MedSpaTheme
 * @version 2.0.0 - T8.3 Enhancement
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 * @sprint SPRINT-8-EXTENSIBLE-SEMANTIC-INTEGRATION
 */

(function($) {
    'use strict';

    // Ensure wp.customize is available
    if (typeof wp === 'undefined' || typeof wp.customize === 'undefined') {
        console.warn('SemanticTokenPreviewEnhanced: WordPress Customizer API not available');
        return;
    }

    /**
     * Enhanced Semantic Token Customizer Preview Class
     */
    class SemanticTokenCustomizerPreviewEnhanced {
        constructor() {
            this.palettes = semanticTokenData.palettes || {};
            this.ajaxUrl = semanticTokenData.ajaxUrl || '';
            this.nonce = semanticTokenData.nonce || '';
            this.currentPalette = null;
            this.cssCache = new Map();
            this.updateQueue = [];
            this.isUpdating = false;
            this.transitionDuration = 300; // ms
            this.performanceMetrics = {
                updateCount: 0,
                totalUpdateTime: 0,
                cacheHits: 0,
                cacheMisses: 0
            };

            this.init();
        }

        /**
         * Initialize the enhanced preview system
         */
        init() {
            console.log('SemanticTokenPreviewEnhanced: Initializing enhanced preview system');
            console.log('Available palettes:', Object.keys(this.palettes));

            // Initialize CSS transition system
            this.initTransitionSystem();

            // Bind to customizer setting changes
            this.bindCustomizerEvents();

            // Setup enhanced performance monitoring
            this.setupEnhancedPerformanceMonitoring();

            // Initialize enhanced CSS injection system
            this.initEnhancedCSSInjection();

            // Setup error handling
            this.setupErrorHandling();

            // Initialize component coverage system
            this.initComponentCoverage();

            console.log('SemanticTokenPreviewEnhanced: Enhanced initialization complete');
        }

        /**
         * Initialize CSS transition system for smooth palette changes
         */
        initTransitionSystem() {
            // Inject transition styles for smooth palette switching
            const transitionCSS = `
                <style id="semantic-token-transitions">
                    /* Enhanced transitions for palette switching */
                    * {
                        transition:
                            background-color ${this.transitionDuration}ms cubic-bezier(0.4, 0, 0.2, 1),
                            border-color ${this.transitionDuration}ms cubic-bezier(0.4, 0, 0.2, 1),
                            color ${this.transitionDuration}ms cubic-bezier(0.4, 0, 0.2, 1),
                            box-shadow ${this.transitionDuration}ms cubic-bezier(0.4, 0, 0.2, 1);
                    }

                    /* Preserve existing transitions but add palette transition */
                    .btn, .button,
                    .treatment-card, .staff-card, .testimonial-card,
                    .form-control, input, textarea, select,
                    .mobile-nav-list a, .nav-menu a {
                        transition:
                            background-color ${this.transitionDuration}ms cubic-bezier(0.4, 0, 0.2, 1),
                            border-color ${this.transitionDuration}ms cubic-bezier(0.4, 0, 0.2, 1),
                            color ${this.transitionDuration}ms cubic-bezier(0.4, 0, 0.2, 1),
                            box-shadow ${this.transitionDuration}ms cubic-bezier(0.4, 0, 0.2, 1),
                            transform 150ms ease,
                            opacity 150ms ease;
                    }

                    /* Loading state animations */
                    .semantic-preview-loading {
                        position: relative;
                        overflow: hidden;
                    }

                    .semantic-preview-loading::before {
                        content: '';
                        position: absolute;
                        top: 0;
                        left: -100%;
                        width: 100%;
                        height: 100%;
                        background: linear-gradient(
                            90deg,
                            transparent,
                            rgba(255, 255, 255, 0.4),
                            transparent
                        );
                        animation: shimmer 1.5s infinite;
                        z-index: 1000;
                    }

                    @keyframes shimmer {
                        0% { left: -100%; }
                        100% { left: 100%; }
                    }

                    /* Palette change indicator */
                    .palette-changing {
                        animation: paletteChange 0.6s ease-in-out;
                    }

                    @keyframes paletteChange {
                        0% { opacity: 1; transform: scale(1); }
                        25% { opacity: 0.8; transform: scale(0.98); }
                        50% { opacity: 0.9; transform: scale(1.01); }
                        100% { opacity: 1; transform: scale(1); }
                    }
                </style>
            `;

            $('head').append(transitionCSS);
        }

        /**
         * Bind to WordPress Customizer events with enhanced handling
         */
        bindCustomizerEvents() {
            // Listen for palette changes with enhanced feedback
            wp.customize('semantic_active_palette', (value) => {
                value.bind((newPalette) => {
                    this.handleEnhancedPaletteChange(newPalette);
                });
            });

            // Listen for customizer ready event
            wp.customize.bind('ready', () => {
                console.log('SemanticTokenPreviewEnhanced: Customizer ready');
                this.onCustomizerReady();
            });

            // Listen for customizer save events
            wp.customize.bind('saved', () => {
                this.onCustomizerSaved();
            });
        }

        /**
         * Enhanced palette change handler with visual feedback
         */
        handleEnhancedPaletteChange(newPalette) {
            console.log('SemanticTokenPreviewEnhanced: Palette changing to:', newPalette);

            if (!this.palettes[newPalette]) {
                console.error('SemanticTokenPreviewEnhanced: Unknown palette:', newPalette);
                this.showErrorState('Unknown palette: ' + newPalette);
                return;
            }

            // Show loading state
            this.showLoadingState();

            // Add visual feedback to body
            $('body').addClass('palette-changing');

            // Add to enhanced update queue
            this.queueEnhancedUpdate(newPalette);
        }

        /**
         * Enhanced update queue with priority and batching
         */
        queueEnhancedUpdate(palette) {
            this.updateQueue.push({
                palette: palette,
                timestamp: Date.now(),
                priority: 'normal'
            });

            // Enhanced debounce with priority handling
            clearTimeout(this.updateTimeout);
            this.updateTimeout = setTimeout(() => {
                this.processEnhancedUpdateQueue();
            }, 100); // Reduced debounce for better responsiveness
        }

        /**
         * Process enhanced update queue with batching
         */
        processEnhancedUpdateQueue() {
            if (this.isUpdating || this.updateQueue.length === 0) {
                return;
            }

            // Get the latest palette from queue (most recent)
            const latestUpdate = this.updateQueue[this.updateQueue.length - 1];
            this.updateQueue = [];

            this.updateEnhancedPalette(latestUpdate.palette);
        }

        /**
         * Enhanced palette update with comprehensive component coverage
         */
        async updateEnhancedPalette(paletteId) {
            if (this.isUpdating || this.currentPalette === paletteId) {
                this.hideLoadingState();
                return;
            }

            this.isUpdating = true;
            const startTime = performance.now();
            this.performanceMetrics.updateCount++;

            try {
                console.log('SemanticTokenPreviewEnhanced: Updating to palette:', paletteId);

                // Generate enhanced CSS for the new palette
                const css = await this.generateEnhancedPaletteCSS(paletteId);

                // Apply CSS to the preview with transition
                await this.applyEnhancedCSSToPreview(css);

                // Update component coverage
                this.updateComponentCoverage(paletteId);

                // Update current palette
                this.currentPalette = paletteId;

                // Send update to backend for persistence
                await this.updateBackendPalette(paletteId);

                const duration = performance.now() - startTime;
                this.performanceMetrics.totalUpdateTime += duration;

                console.log(`SemanticTokenPreviewEnhanced: Enhanced palette update completed in ${duration.toFixed(2)}ms`);

                // Hide loading state and show success
                this.hideLoadingState();
                this.showSuccessState();

                // Trigger enhanced custom event
                $(document).trigger('semanticPaletteChangedEnhanced', {
                    palette: paletteId,
                    duration: duration,
                    metrics: this.getPerformanceMetrics()
                });

            } catch (error) {
                console.error('SemanticTokenPreviewEnhanced: Error updating palette:', error);
                this.hideLoadingState();
                this.showErrorState(error.message);
            } finally {
                this.isUpdating = false;
                $('body').removeClass('palette-changing');
            }
        }

        /**
         * Generate enhanced CSS with comprehensive token coverage
         */
        async generateEnhancedPaletteCSS(paletteId) {
            // Check cache first
            if (this.cssCache.has(paletteId)) {
                this.performanceMetrics.cacheHits++;
                return this.cssCache.get(paletteId);
            }

            this.performanceMetrics.cacheMisses++;
            const palette = this.palettes[paletteId];

            if (!palette || !palette.colors) {
                throw new Error(`Invalid palette data: ${paletteId}`);
            }

            let css = `:root {\n`;
            css += `  /* Enhanced Semantic Color Tokens - Generated from ${paletteId} */\n`;
            css += `  /* Generated at: ${new Date().toISOString()} */\n\n`;

            // Enhanced semantic role mapping
            const roleMapping = {
                'primary': 'color-brand-primary',
                'secondary': 'color-brand-secondary',
                'surface': 'color-surface-primary',
                'background': 'color-surface-background',
                'accent': 'color-accent-primary'
            };

            // Generate semantic foundation tokens
            Object.keys(palette.colors).forEach(role => {
                const color = palette.colors[role];
                if (color && color.hex) {
                    const semanticToken = roleMapping[role] || `color-${role.replace('_', '-')}`;
                    css += `  --${semanticToken}: ${color.hex}; /* ${color.name || role} */\n`;
                }
            });

            // Add extended semantic tokens
            css += this.generateExtendedSemanticTokens(palette);

            // Add comprehensive component tokens
            css += this.generateComprehensiveComponentTokens(palette);

            css += '}\n';

            // Cache the generated CSS
            this.cssCache.set(paletteId, css);

            return css;
        }

        /**
         * Generate extended semantic tokens for comprehensive coverage
         */
        generateExtendedSemanticTokens(palette) {
            let css = '\n  /* Extended Semantic Tokens */\n';

            // Text tokens based on palette
            css += `  --color-text-primary: ${this.getContrastColor(palette.colors.background?.hex || '#ffffff')};\n`;
            css += `  --color-text-secondary: ${this.adjustColorOpacity(this.getContrastColor(palette.colors.background?.hex || '#ffffff'), 0.7)};\n`;
            css += `  --color-text-muted: ${this.adjustColorOpacity(this.getContrastColor(palette.colors.background?.hex || '#ffffff'), 0.5)};\n`;
            css += `  --color-text-inverse: ${palette.colors.background?.hex || '#ffffff'};\n`;

            // Border tokens
            css += `  --color-border-primary: ${this.adjustColorOpacity(palette.colors.primary?.hex || '#000000', 0.2)};\n`;
            css += `  --color-border-secondary: ${this.adjustColorOpacity(palette.colors.primary?.hex || '#000000', 0.1)};\n`;
            css += `  --color-border-light: ${this.adjustColorOpacity(palette.colors.primary?.hex || '#000000', 0.05)};\n`;

            // Interactive tokens
            css += `  --color-interactive-hover: ${this.adjustColorBrightness(palette.colors.primary?.hex || '#000000', -10)};\n`;
            css += `  --color-interactive-focus: ${palette.colors.accent?.hex || '#0066cc'};\n`;
            css += `  --color-interactive-active: ${this.adjustColorBrightness(palette.colors.primary?.hex || '#000000', -20)};\n`;

            // Feedback tokens
            css += `  --color-feedback-success: #10b981;\n`;
            css += `  --color-feedback-warning: #f59e0b;\n`;
            css += `  --color-feedback-error: #ef4444;\n`;
            css += `  --color-feedback-info: #3b82f6;\n`;

            return css;
        }

        /**
         * Generate comprehensive component tokens
         */
        generateComprehensiveComponentTokens(palette) {
            let css = '\n  /* Comprehensive Component Tokens */\n';

            // Button tokens
            if (palette.colors.primary) {
                css += `  --btn-primary-bg: var(--color-brand-primary);\n`;
                css += `  --btn-primary-text: var(--color-text-inverse);\n`;
                css += `  --btn-primary-border: var(--color-brand-primary);\n`;
                css += `  --btn-primary-hover-bg: var(--color-interactive-hover);\n`;
            }

            if (palette.colors.secondary) {
                css += `  --btn-secondary-bg: var(--color-brand-secondary);\n`;
                css += `  --btn-secondary-text: var(--color-text-inverse);\n`;
                css += `  --btn-secondary-border: var(--color-brand-secondary);\n`;
            }

            if (palette.colors.accent) {
                css += `  --btn-accent-bg: var(--color-accent-primary);\n`;
                css += `  --btn-accent-text: var(--color-text-primary);\n`;
                css += `  --btn-accent-border: var(--color-accent-primary);\n`;
            }

            // Form component tokens
            css += `  --input-bg: var(--color-surface-primary);\n`;
            css += `  --input-border: var(--color-border-secondary);\n`;
            css += `  --input-border-focus: var(--color-interactive-focus);\n`;
            css += `  --input-text: var(--color-text-primary);\n`;
            css += `  --input-placeholder: var(--color-text-muted);\n`;

            // Card component tokens
            css += `  --card-bg: var(--color-surface-primary);\n`;
            css += `  --card-border: var(--color-border-light);\n`;
            css += `  --card-shadow: rgba(0, 0, 0, 0.1);\n`;

            // Navigation tokens
            css += `  --nav-link-color: var(--color-text-primary);\n`;
            css += `  --nav-link-hover: var(--color-brand-primary);\n`;
            css += `  --nav-link-active: var(--color-brand-primary);\n`;

            // Modal/overlay tokens
            css += `  --modal-bg: var(--color-surface-primary);\n`;
            css += `  --modal-overlay: rgba(0, 0, 0, 0.5);\n`;
            css += `  --modal-border: var(--color-border-secondary);\n`;

            return css;
        }

        /**
         * Apply enhanced CSS to preview with smooth transitions
         */
        async applyEnhancedCSSToPreview(css) {
            return new Promise((resolve) => {
                // Remove existing semantic token styles
                $('#semantic-token-preview-styles-enhanced').remove();

                // Inject new styles with transition delay
                const $style = $('<style id="semantic-token-preview-styles-enhanced">')
                    .text(css)
                    .appendTo('head');

                // Wait for transition to complete
                setTimeout(() => {
                    console.log('SemanticTokenPreviewEnhanced: Enhanced CSS applied with transitions');
                    resolve();
                }, 50); // Small delay to ensure CSS is applied before transition
            });
        }

        /**
         * Update component coverage for comprehensive preview
         */
        updateComponentCoverage(paletteId) {
            const components = [
                '.btn, .button',
                '.treatment-card, .staff-card, .testimonial-card',
                '.form-control, input, textarea, select',
                '.nav-menu a, .mobile-nav-list a',
                '.modal, .popup',
                '.alert, .notice',
                '.badge, .tag',
                '.progress, .progress-bar'
            ];

            components.forEach(selector => {
                $(selector).addClass('semantic-token-enhanced');
            });
        }

        /**
         * Show loading state with visual feedback
         */
        showLoadingState() {
            // Add loading class to key components
            $('.btn, .treatment-card, .form-control').addClass('semantic-preview-loading');

            // Show loading indicator in customizer
            $('.semantic-palette-preview-container').addClass('updating');

            console.log('SemanticTokenPreviewEnhanced: Loading state shown');
        }

        /**
         * Hide loading state
         */
        hideLoadingState() {
            $('.semantic-preview-loading').removeClass('semantic-preview-loading');
            $('.semantic-palette-preview-container').removeClass('updating');

            console.log('SemanticTokenPreviewEnhanced: Loading state hidden');
        }

        /**
         * Show success state
         */
        showSuccessState() {
            $('.semantic-palette-preview-container').addClass('success');
            setTimeout(() => {
                $('.semantic-palette-preview-container').removeClass('success');
            }, 1000);
        }

        /**
         * Show error state
         */
        showErrorState(message) {
            $('.semantic-palette-preview-container').addClass('error');
            console.error('SemanticTokenPreviewEnhanced: Error -', message);

            setTimeout(() => {
                $('.semantic-palette-preview-container').removeClass('error');
            }, 3000);
        }

        /**
         * Color utility functions
         */
        getContrastColor(hexColor) {
            // Simple contrast calculation - returns black or white
            const rgb = this.hexToRgb(hexColor);
            const brightness = (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000;
            return brightness > 128 ? '#000000' : '#ffffff';
        }

        hexToRgb(hex) {
            const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            } : null;
        }

        adjustColorOpacity(hexColor, opacity) {
            const rgb = this.hexToRgb(hexColor);
            return rgb ? `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, ${opacity})` : hexColor;
        }

        adjustColorBrightness(hexColor, percent) {
            const rgb = this.hexToRgb(hexColor);
            if (!rgb) return hexColor;

            const adjust = (color) => {
                const adjusted = color + (color * percent / 100);
                return Math.max(0, Math.min(255, Math.round(adjusted)));
            };

            return `#${adjust(rgb.r).toString(16).padStart(2, '0')}${adjust(rgb.g).toString(16).padStart(2, '0')}${adjust(rgb.b).toString(16).padStart(2, '0')}`;
        }

        /**
         * Enhanced performance monitoring
         */
        setupEnhancedPerformanceMonitoring() {
            // Monitor CSS cache performance
            setInterval(() => {
                const metrics = this.getPerformanceMetrics();
                console.log('SemanticTokenPreviewEnhanced: Performance metrics:', metrics);
            }, 60000); // Log every minute

            // Clear cache if it gets too large
            if (this.cssCache.size > 20) {
                console.log('SemanticTokenPreviewEnhanced: Clearing CSS cache');
                this.cssCache.clear();
            }
        }

        /**
         * Initialize enhanced CSS injection system
         */
        initEnhancedCSSInjection() {
            // Enhanced MutationObserver for better DOM change handling
            if (typeof MutationObserver !== 'undefined') {
                const observer = new MutationObserver((mutations) => {
                    let shouldReapply = false;

                    mutations.forEach((mutation) => {
                        if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                            // Check if significant components were added
                            mutation.addedNodes.forEach(node => {
                                if (node.nodeType === 1) { // Element node
                                    const hasComponents = node.querySelector && (
                                        node.querySelector('.btn, .treatment-card, .form-control') ||
                                        node.classList.contains('btn') ||
                                        node.classList.contains('treatment-card')
                                    );
                                    if (hasComponents) {
                                        shouldReapply = true;
                                    }
                                }
                            });
                        }
                    });

                    if (shouldReapply) {
                        this.handleEnhancedDOMChange();
                    }
                });

                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            }
        }

        /**
         * Handle enhanced DOM changes
         */
        handleEnhancedDOMChange() {
            clearTimeout(this.domChangeTimeout);
            this.domChangeTimeout = setTimeout(() => {
                if (this.currentPalette) {
                    console.log('SemanticTokenPreviewEnhanced: Re-applying palette after DOM change');
                    this.updateComponentCoverage(this.currentPalette);
                }
            }, 300);
        }

        /**
         * Setup error handling
         */
        setupErrorHandling() {
            window.addEventListener('error', (event) => {
                if (event.error && event.error.message.includes('SemanticToken')) {
                    console.error('SemanticTokenPreviewEnhanced: Caught error:', event.error);
                    this.showErrorState('Preview system error');
                }
            });
        }

        /**
         * Initialize component coverage system
         */
        initComponentCoverage() {
            // Mark initial components for coverage tracking
            this.updateComponentCoverage('initial');
        }

        /**
         * Handle customizer ready event
         */
        onCustomizerReady() {
            const initialPalette = wp.customize('semantic_active_palette')();
            if (initialPalette && this.palettes[initialPalette]) {
                this.currentPalette = initialPalette;
                console.log('SemanticTokenPreviewEnhanced: Initial palette:', initialPalette);
            }
        }

        /**
         * Handle customizer saved event
         */
        onCustomizerSaved() {
            console.log('SemanticTokenPreviewEnhanced: Customizer saved');
            // Clear cache on save to ensure fresh data
            this.cssCache.clear();
        }

        /**
         * Update backend palette setting
         */
        async updateBackendPalette(paletteId) {
            if (!this.ajaxUrl || !this.nonce) {
                console.warn('SemanticTokenPreviewEnhanced: AJAX URL or nonce not available');
                return;
            }

            try {
                const response = await $.ajax({
                    url: this.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'update_semantic_palette',
                        palette_id: paletteId,
                        nonce: this.nonce
                    },
                    timeout: 5000 // 5 second timeout
                });

                if (response.success) {
                    console.log('SemanticTokenPreviewEnhanced: Backend updated successfully');
                } else {
                    throw new Error(response.data || 'Backend update failed');
                }
            } catch (error) {
                console.error('SemanticTokenPreviewEnhanced: AJAX error:', error);
                throw error;
            }
        }

        /**
         * Get current palette information
         */
        getCurrentPalette() {
            return {
                id: this.currentPalette,
                data: this.palettes[this.currentPalette] || null
            };
        }

        /**
         * Get enhanced performance metrics
         */
        getPerformanceMetrics() {
            const avgUpdateTime = this.performanceMetrics.updateCount > 0
                ? this.performanceMetrics.totalUpdateTime / this.performanceMetrics.updateCount
                : 0;

            return {
                cacheSize: this.cssCache.size,
                queueLength: this.updateQueue.length,
                isUpdating: this.isUpdating,
                availablePalettes: Object.keys(this.palettes).length,
                updateCount: this.performanceMetrics.updateCount,
                averageUpdateTime: Math.round(avgUpdateTime * 100) / 100,
                cacheHitRate: this.performanceMetrics.cacheHits / (this.performanceMetrics.cacheHits + this.performanceMetrics.cacheMisses) * 100 || 0
            };
        }
    }

    /**
     * Initialize enhanced preview when document is ready
     */
    $(document).ready(function() {
        // Only initialize if we're in the customizer preview
        if (typeof semanticTokenData !== 'undefined') {
            window.semanticTokenPreviewEnhanced = new SemanticTokenCustomizerPreviewEnhanced();

            // Expose to global scope for debugging
            if (window.wp && window.wp.customize) {
                window.wp.customize.semanticTokenPreviewEnhanced = window.semanticTokenPreviewEnhanced;
            }

            console.log('SemanticTokenPreviewEnhanced: Enhanced preview system ready');
        } else {
            console.warn('SemanticTokenPreviewEnhanced: semanticTokenData not available');
        }
    });

})(jQuery);
