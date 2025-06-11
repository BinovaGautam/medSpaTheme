/**
 * T7.3.3 Semantic Token Customizer Preview
 * Real-time preview functionality for semantic token customizer
 *
 * @package MedSpaTheme
 * @version 4.1.0 - Sprint 7 T7.3.3 Implementation
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 */

(function($, wp) {
    'use strict';

    /**
     * T7.3.3 Semantic Token Preview Handler
     */
    var SemanticTokenPreview = {

        /**
         * Performance tracking
         */
        performance: {
            updateCount: 0,
            totalUpdateTime: 0,
            averageUpdateTime: 0
        },

        /**
         * Token cache for performance
         */
        tokenCache: {},

        /**
         * Update queue for batched operations
         */
        updateQueue: [],
        updateTimeout: null,

        /**
         * Initialize preview functionality
         */
        init: function() {
            console.log('🎨 Initializing T7.3.3 Semantic Token Preview...');

            this.setupPalettePreview();
            this.setupColorOverridePreview();
            this.setupComponentPreview();
            this.setupPerformanceMonitoring();
            this.setupAccessibilityEnhancements();

            // Add preview body class
            $('body').addClass('semantic-token-preview-active');

            console.log('✅ T7.3.3 Semantic Token Preview Ready');
        },

        /**
         * Setup palette preview with real-time updates
         */
        setupPalettePreview: function() {
            wp.customize('semantic_color_palette', function(value) {
                value.bind(function(newPalette) {
                    SemanticTokenPreview.handlePaletteChange(newPalette);
                });
            });
        },

        /**
         * Setup color override previews
         */
        setupColorOverridePreview: function() {
            // Primary color override
            wp.customize('semantic_primary_override', function(value) {
                value.bind(function(newColor) {
                    SemanticTokenPreview.handlePrimaryColorChange(newColor);
                });
            });

            // Accent color override
            wp.customize('semantic_accent_override', function(value) {
                value.bind(function(newColor) {
                    SemanticTokenPreview.handleAccentColorChange(newColor);
                });
            });
        },

        /**
         * Setup component preview updates
         */
        setupComponentPreview: function() {
            wp.customize('semantic_button_style', function(value) {
                value.bind(function(newStyle) {
                    SemanticTokenPreview.handleButtonStyleChange(newStyle);
                });
            });
        },

        /**
         * Setup performance monitoring
         */
        setupPerformanceMonitoring: function() {
            // Monitor real-time performance
            setInterval(function() {
                SemanticTokenPreview.updatePerformanceMetrics();
            }, 1000);
        },

        /**
         * Setup accessibility enhancements
         */
        setupAccessibilityEnhancements: function() {
            // Add visual feedback for changes
            this.setupVisualFeedback();

            // Add screen reader announcements
            this.setupScreenReaderAnnouncements();
        },

        /**
         * Handle palette change with optimized updates
         */
        handlePaletteChange: function(paletteId) {
            var startTime = performance.now();

            if (!semanticTokenCustomizer || !semanticTokenCustomizer.palettes) {
                console.warn('Semantic token palette data not available');
                return;
            }

            var palette = semanticTokenCustomizer.palettes[paletteId];
            if (!palette) {
                console.warn('Palette not found:', paletteId);
                return;
            }

            // Cache the palette for performance
            this.tokenCache.currentPalette = palette;

            // Queue the update for batched processing
            this.queueUpdate(function() {
                SemanticTokenPreview.applyPaletteColors(palette);
                SemanticTokenPreview.updateComponentTokens(palette);
                SemanticTokenPreview.triggerVisualFeedback('palette-change');
            });

            this.recordPerformance(startTime);
        },

        /**
         * Handle primary color override
         */
        handlePrimaryColorChange: function(color) {
            var startTime = performance.now();

            this.queueUpdate(function() {
                if (color) {
                    document.documentElement.style.setProperty('--color-primary', color);
                    document.documentElement.style.setProperty('--btn-primary-bg', color);
                    SemanticTokenPreview.updatePrimaryColorDerivatives(color);
                } else {
                    // Reset to palette default
                    var palette = SemanticTokenPreview.tokenCache.currentPalette;
                    if (palette) {
                        document.documentElement.style.setProperty('--color-primary', palette.primary);
                        document.documentElement.style.setProperty('--btn-primary-bg', palette.primary);
                    }
                }

                SemanticTokenPreview.triggerVisualFeedback('primary-color-change');
            });

            this.recordPerformance(startTime);
        },

        /**
         * Handle accent color override
         */
        handleAccentColorChange: function(color) {
            var startTime = performance.now();

            this.queueUpdate(function() {
                if (color) {
                    document.documentElement.style.setProperty('--color-accent', color);
                    document.documentElement.style.setProperty('--btn-accent-bg', color);
                    SemanticTokenPreview.updateAccentColorDerivatives(color);
                } else {
                    // Reset to palette default
                    var palette = SemanticTokenPreview.tokenCache.currentPalette;
                    if (palette) {
                        document.documentElement.style.setProperty('--color-accent', palette.accent);
                        document.documentElement.style.setProperty('--btn-accent-bg', palette.accent);
                    }
                }

                SemanticTokenPreview.triggerVisualFeedback('accent-color-change');
            });

            this.recordPerformance(startTime);
        },

        /**
         * Handle button style changes
         */
        handleButtonStyleChange: function(style) {
            var startTime = performance.now();

            this.queueUpdate(function() {
                var borderRadius = '6px';

                switch (style) {
                    case 'rounded':
                        borderRadius = '8px';
                        break;
                    case 'sharp':
                        borderRadius = '2px';
                        break;
                    case 'pill':
                        borderRadius = '50px';
                        break;
                    default:
                        borderRadius = '6px';
                }

                document.documentElement.style.setProperty('--btn-border-radius', borderRadius);
                SemanticTokenPreview.triggerVisualFeedback('button-style-change');
            });

            this.recordPerformance(startTime);
        },

        /**
         * Apply palette colors to CSS custom properties
         */
        applyPaletteColors: function(palette) {
            var root = document.documentElement;

            // Primary colors
            root.style.setProperty('--color-primary', palette.primary);
            root.style.setProperty('--color-primary-hover', this.darkenColor(palette.primary, 20));
            root.style.setProperty('--color-primary-light', this.lightenColor(palette.primary, 20));

            // Secondary colors
            root.style.setProperty('--color-secondary', palette.secondary);
            root.style.setProperty('--color-secondary-hover', this.darkenColor(palette.secondary, 20));
            root.style.setProperty('--color-secondary-light', this.lightenColor(palette.secondary, 20));

            // Accent colors
            root.style.setProperty('--color-accent', palette.accent);
            root.style.setProperty('--color-accent-hover', this.darkenColor(palette.accent, 15));
            root.style.setProperty('--color-accent-light', this.lightenColor(palette.accent, 30));
        },

        /**
         * Update component tokens based on palette
         */
        updateComponentTokens: function(palette) {
            var root = document.documentElement;

            // Button component tokens
            root.style.setProperty('--btn-primary-bg', palette.primary);
            root.style.setProperty('--btn-primary-hover-bg', this.darkenColor(palette.primary, 20));
            root.style.setProperty('--btn-secondary-bg', palette.secondary);
            root.style.setProperty('--btn-accent-bg', palette.accent);

            // Other component tokens
            root.style.setProperty('--nav-link-color', palette.primary);
            root.style.setProperty('--nav-link-hover', palette.accent);
        },

        /**
         * Update primary color derivatives
         */
        updatePrimaryColorDerivatives: function(color) {
            var root = document.documentElement;
            root.style.setProperty('--color-primary-hover', this.darkenColor(color, 20));
            root.style.setProperty('--color-primary-light', this.lightenColor(color, 20));
            root.style.setProperty('--btn-primary-hover-bg', this.darkenColor(color, 20));
        },

        /**
         * Update accent color derivatives
         */
        updateAccentColorDerivatives: function(color) {
            var root = document.documentElement;
            root.style.setProperty('--color-accent-hover', this.darkenColor(color, 15));
            root.style.setProperty('--color-accent-light', this.lightenColor(color, 30));
        },

        /**
         * Queue update for batched processing
         */
        queueUpdate: function(updateFunction) {
            this.updateQueue.push(updateFunction);

            if (this.updateTimeout) {
                clearTimeout(this.updateTimeout);
            }

            // Batch updates for performance (< 100ms target)
            this.updateTimeout = setTimeout(function() {
                var startTime = performance.now();

                while (SemanticTokenPreview.updateQueue.length > 0) {
                    var updateFn = SemanticTokenPreview.updateQueue.shift();
                    updateFn();
                }

                var endTime = performance.now();
                SemanticTokenPreview.performance.totalUpdateTime += (endTime - startTime);
                SemanticTokenPreview.performance.updateCount++;
            }, 50); // 50ms delay for batching
        },

        /**
         * Setup visual feedback for changes
         */
        setupVisualFeedback: function() {
            // Add visual feedback styles
            if (!document.getElementById('semantic-token-feedback-styles')) {
                var style = document.createElement('style');
                style.id = 'semantic-token-feedback-styles';
                style.textContent = `
                    .semantic-token-updating * {
                        transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease !important;
                    }

                    .semantic-token-highlight {
                        box-shadow: 0 0 10px rgba(212, 175, 55, 0.5) !important;
                        animation: semanticTokenPulse 0.6s ease;
                    }

                    @keyframes semanticTokenPulse {
                        0% { transform: scale(1); }
                        50% { transform: scale(1.02); }
                        100% { transform: scale(1); }
                    }
                `;
                document.head.appendChild(style);
            }
        },

        /**
         * Trigger visual feedback for changes
         */
        triggerVisualFeedback: function(changeType) {
            $('body').addClass('semantic-token-updating');

            // Highlight affected elements based on change type
            var selectors = {
                'palette-change': '.btn, .site-header, h1, h2, h3, a',
                'primary-color-change': '.btn-primary, .site-title, h1, h2, h3',
                'accent-color-change': '.btn-accent, .accent-elements',
                'button-style-change': '.btn, button, input[type="submit"]'
            };

            var selector = selectors[changeType] || '.btn';
            $(selector).addClass('semantic-token-highlight');

            // Remove feedback classes after animation
            setTimeout(function() {
                $('body').removeClass('semantic-token-updating');
                $(selector).removeClass('semantic-token-highlight');
            }, 600);
        },

        /**
         * Setup screen reader announcements
         */
        setupScreenReaderAnnouncements: function() {
            // Create announcement region
            if (!document.getElementById('semantic-token-announcements')) {
                var announcer = document.createElement('div');
                announcer.id = 'semantic-token-announcements';
                announcer.setAttribute('aria-live', 'polite');
                announcer.setAttribute('aria-atomic', 'true');
                announcer.style.position = 'absolute';
                announcer.style.left = '-10000px';
                announcer.style.width = '1px';
                announcer.style.height = '1px';
                announcer.style.overflow = 'hidden';
                document.body.appendChild(announcer);
            }
        },

        /**
         * Announce changes to screen readers
         */
        announceChange: function(message) {
            var announcer = document.getElementById('semantic-token-announcements');
            if (announcer) {
                announcer.textContent = message;
            }
        },

        /**
         * Record performance metrics
         */
        recordPerformance: function(startTime) {
            var endTime = performance.now();
            this.performance.totalUpdateTime += (endTime - startTime);
            this.performance.updateCount++;
            this.performance.averageUpdateTime = this.performance.totalUpdateTime / this.performance.updateCount;
        },

        /**
         * Update performance metrics display
         */
        updatePerformanceMetrics: function() {
            // Send performance data to parent frame if available
            if (wp.customize && wp.customize.preview) {
                wp.customize.preview.send('semantic-token-performance', {
                    updateCount: this.performance.updateCount,
                    averageUpdateTime: Math.round(this.performance.averageUpdateTime),
                    totalUpdateTime: Math.round(this.performance.totalUpdateTime)
                });
            }
        },

        /**
         * Utility: Darken color by percentage
         */
        darkenColor: function(hex, percent) {
            hex = hex.replace('#', '');
            var num = parseInt(hex, 16);
            var amt = Math.round(2.55 * percent);
            var R = (num >> 16) - amt;
            var G = (num >> 8 & 0x00FF) - amt;
            var B = (num & 0x0000FF) - amt;

            return '#' + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
                (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
                (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
        },

        /**
         * Utility: Lighten color by percentage
         */
        lightenColor: function(hex, percent) {
            hex = hex.replace('#', '');
            var num = parseInt(hex, 16);
            var amt = Math.round(2.55 * percent);
            var R = (num >> 16) + amt;
            var G = (num >> 8 & 0x00FF) + amt;
            var B = (num & 0x0000FF) + amt;

            return '#' + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
                (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
                (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
        }
    };

    /**
     * Initialize when preview is ready
     */
    wp.customize.preview.bind('ready', function() {
        console.log('🎨 WordPress Customizer Preview Ready - Initializing T7.3.3...');

        // Small delay to ensure all systems are ready
        setTimeout(function() {
            SemanticTokenPreview.init();
        }, 100);
    });

    // Export for global access
    window.SemanticTokenPreview = SemanticTokenPreview;

})(jQuery, wp);
