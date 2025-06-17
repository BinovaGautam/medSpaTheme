/**
 * Semantic Token Customizer Preview
 *
 * T8.1 Live Preview Enhancement - Real-time palette preview in customizer
 * Enables live updates when users change color palettes in WordPress Customizer
 *
 * @package MedSpaTheme
 * @version 1.0.0
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 * @sprint SPRINT-8-EXTENSIBLE-SEMANTIC-INTEGRATION
 */

(function($) {
    'use strict';

    // Ensure wp.customize is available
    if (typeof wp === 'undefined' || typeof wp.customize === 'undefined') {
        console.warn('SemanticTokenPreview: WordPress Customizer API not available');
        return;
    }

    /**
     * Semantic Token Customizer Preview Class
     */
    class SemanticTokenCustomizerPreview {
        constructor() {
            this.palettes = semanticTokenData.palettes || {};
            this.ajaxUrl = semanticTokenData.ajaxUrl || '';
            this.nonce = semanticTokenData.nonce || '';
            this.currentPalette = null;
            this.cssCache = new Map();
            this.updateQueue = [];
            this.isUpdating = false;

            this.init();
        }

        /**
         * Initialize the preview system
         */
        init() {
            console.log('SemanticTokenPreview: Initializing preview system');
            console.log('Available palettes:', Object.keys(this.palettes));

            // Bind to customizer setting changes
            this.bindCustomizerEvents();

            // Setup performance monitoring
            this.setupPerformanceMonitoring();

            // Initialize CSS injection system
            this.initCSSInjection();

            console.log('SemanticTokenPreview: Initialization complete');
        }

        /**
         * Bind to WordPress Customizer events
         */
        bindCustomizerEvents() {
            // Listen for palette changes
            wp.customize('semantic_active_palette', (value) => {
                value.bind((newPalette) => {
                    this.handlePaletteChange(newPalette);
                });
            });

            // Listen for customizer ready event
            wp.customize.bind('ready', () => {
                console.log('SemanticTokenPreview: Customizer ready');
                this.onCustomizerReady();
            });
        }

        /**
         * Handle customizer ready event
         */
        onCustomizerReady() {
            // Get initial palette value
            const initialPalette = wp.customize('semantic_active_palette')();
            if (initialPalette && this.palettes[initialPalette]) {
                this.currentPalette = initialPalette;
                console.log('SemanticTokenPreview: Initial palette:', initialPalette);
            }
        }

        /**
         * Handle palette change
         */
        handlePaletteChange(newPalette) {
            console.log('SemanticTokenPreview: Palette changed to:', newPalette);

            if (!this.palettes[newPalette]) {
                console.error('SemanticTokenPreview: Unknown palette:', newPalette);
                return;
            }

            // Add to update queue to prevent rapid changes
            this.queueUpdate(newPalette);
        }

        /**
         * Queue palette update to prevent rapid changes
         */
        queueUpdate(palette) {
            this.updateQueue.push(palette);

            // Debounce updates
            clearTimeout(this.updateTimeout);
            this.updateTimeout = setTimeout(() => {
                this.processUpdateQueue();
            }, 150); // 150ms debounce
        }

        /**
         * Process queued updates
         */
        processUpdateQueue() {
            if (this.isUpdating || this.updateQueue.length === 0) {
                return;
            }

            // Get the latest palette from queue
            const latestPalette = this.updateQueue[this.updateQueue.length - 1];
            this.updateQueue = [];

            this.updatePalette(latestPalette);
        }

        /**
         * Update the current palette
         */
        async updatePalette(paletteId) {
            if (this.isUpdating || this.currentPalette === paletteId) {
                return;
            }

            this.isUpdating = true;
            const startTime = performance.now();

            try {
                console.log('SemanticTokenPreview: Updating to palette:', paletteId);

                // Generate CSS for the new palette
                const css = this.generatePaletteCSS(paletteId);

                // Apply CSS to the preview
                this.applyCSSToPreview(css);

                // Update current palette
                this.currentPalette = paletteId;

                // Send update to backend for persistence
                await this.updateBackendPalette(paletteId);

                const duration = performance.now() - startTime;
                console.log(`SemanticTokenPreview: Palette update completed in ${duration.toFixed(2)}ms`);

                // Trigger custom event for other scripts
                $(document).trigger('semanticPaletteChanged', {
                    palette: paletteId,
                    duration: duration
                });

            } catch (error) {
                console.error('SemanticTokenPreview: Error updating palette:', error);
            } finally {
                this.isUpdating = false;
            }
        }

        /**
         * Generate CSS for a specific palette
         */
        generatePaletteCSS(paletteId) {
            // Check cache first
            if (this.cssCache.has(paletteId)) {
                return this.cssCache.get(paletteId);
            }

            const palette = this.palettes[paletteId];
            if (!palette || !palette.colors) {
                console.error('SemanticTokenPreview: Invalid palette data:', paletteId);
                return '';
            }

            let css = `:root {\n`;
            css += `  /* Semantic Color Tokens - Generated from ${paletteId} */\n`;

            // Semantic role mapping
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

            css += '\n  /* Component-specific tokens */\n';

            // Generate component tokens
            if (palette.colors.primary && palette.colors.primary.hex) {
                css += '  --btn-primary-bg: var(--color-brand-primary);\n';
                css += '  --btn-primary-text: var(--color-surface-primary);\n';
            }

            if (palette.colors.secondary && palette.colors.secondary.hex) {
                css += '  --btn-secondary-bg: var(--color-brand-secondary);\n';
                css += '  --btn-secondary-text: var(--color-surface-primary);\n';
            }

            if (palette.colors.accent && palette.colors.accent.hex) {
                css += '  --btn-accent-bg: var(--color-accent-primary);\n';
                css += '  --btn-accent-text: var(--color-text-primary);\n';
            }

            // Surface tokens
            if (palette.colors.surface && palette.colors.surface.hex) {
                css += '  --card-bg: var(--color-surface-primary);\n';
                css += '  --modal-bg: var(--color-surface-primary);\n';
            }

            if (palette.colors.background && palette.colors.background.hex) {
                css += '  --page-bg: var(--color-surface-background);\n';
                css += '  --section-bg: var(--color-surface-background);\n';
            }

            css += '}\n';

            // Cache the generated CSS
            this.cssCache.set(paletteId, css);

            return css;
        }

        /**
         * Apply CSS to the preview iframe
         */
        applyCSSToPreview(css) {
            // Remove existing semantic token styles
            $('#semantic-token-preview-styles').remove();

            // Inject new styles
            $('<style id="semantic-token-preview-styles">')
                .text(css)
                .appendTo('head');

            console.log('SemanticTokenPreview: CSS applied to preview');
        }

        /**
         * Update backend palette setting
         */
        async updateBackendPalette(paletteId) {
            if (!this.ajaxUrl || !this.nonce) {
                console.warn('SemanticTokenPreview: AJAX URL or nonce not available');
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
                    }
                });

                if (response.success) {
                    console.log('SemanticTokenPreview: Backend updated successfully');
                } else {
                    console.error('SemanticTokenPreview: Backend update failed:', response.data);
                }
            } catch (error) {
                console.error('SemanticTokenPreview: AJAX error:', error);
            }
        }

        /**
         * Setup performance monitoring
         */
        setupPerformanceMonitoring() {
            // Monitor CSS cache performance
            setInterval(() => {
                console.log('SemanticTokenPreview: CSS cache size:', this.cssCache.size);
            }, 30000); // Log every 30 seconds

            // Clear cache if it gets too large
            if (this.cssCache.size > 50) {
                console.log('SemanticTokenPreview: Clearing CSS cache');
                this.cssCache.clear();
            }
        }

        /**
         * Initialize CSS injection system
         */
        initCSSInjection() {
            // Create a MutationObserver to watch for DOM changes
            if (typeof MutationObserver !== 'undefined') {
                const observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.type === 'childList') {
                            // Re-apply current palette if DOM structure changed significantly
                            this.handleDOMChange();
                        }
                    });
                });

                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            }
        }

        /**
         * Handle significant DOM changes
         */
        handleDOMChange() {
            // Debounce DOM change handling
            clearTimeout(this.domChangeTimeout);
            this.domChangeTimeout = setTimeout(() => {
                if (this.currentPalette) {
                    console.log('SemanticTokenPreview: Re-applying palette after DOM change');
                    const css = this.generatePaletteCSS(this.currentPalette);
                    this.applyCSSToPreview(css);
                }
            }, 500);
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
         * Get performance metrics
         */
        getPerformanceMetrics() {
            return {
                cacheSize: this.cssCache.size,
                queueLength: this.updateQueue.length,
                isUpdating: this.isUpdating,
                availablePalettes: Object.keys(this.palettes).length
            };
        }
    }

    /**
     * Initialize when document is ready
     */
    $(document).ready(function() {
        // Only initialize if we're in the customizer preview
        if (typeof semanticTokenData !== 'undefined') {
            window.semanticTokenPreview = new SemanticTokenCustomizerPreview();

            // Expose to global scope for debugging
            if (window.wp && window.wp.customize) {
                window.wp.customize.semanticTokenPreview = window.semanticTokenPreview;
            }
        } else {
            console.warn('SemanticTokenPreview: semanticTokenData not available');
        }
    });

})(jQuery);
