/**
 * T7.3.3 Semantic Token Customizer Controls
 * Enhanced WordPress Customizer controls for semantic token system
 *
 * @package MedSpaTheme
 * @version 4.1.0 - Sprint 7 T7.3.3 Implementation
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 */

(function($, wp) {
    'use strict';

    /**
     * T7.3.3 Semantic Token Customizer Controls
     */
    var SemanticTokenCustomizerControls = {

        /**
         * Performance tracking
         */
        performance: {
            startTime: performance.now(),
            controlUpdateTime: 0,
            previewUpdateTime: 0
        },

        /**
         * Optimized token cache
         */
        tokenCache: {},

        /**
         * Initialize customizer controls
         */
        init: function() {
            console.log('🎨 Initializing T7.3.3 Semantic Token Customizer Controls...');

            this.setupPaletteControls();
            this.setupColorOverrideControls();
            this.setupComponentControls();
            this.setupPerformanceControls();
            this.setupAccessibilityFeatures();
            this.setupRealTimePreview();

            console.log('✅ T7.3.3 Semantic Token Controls Ready');
        },

        /**
         * Setup semantic palette controls with visual previews
         */
        setupPaletteControls: function() {
            var $paletteControl = $('#customize-control-semantic_color_palette');
            if ($paletteControl.length === 0) return;

            // Create enhanced palette preview
            var $previewContainer = $('<div class="semantic-palette-preview-container"></div>');
            $paletteControl.find('.customize-control-content').append($previewContainer);

            // Build palette previews for each option
            this.buildPaletteViews($previewContainer);

            // Handle palette selection
            $paletteControl.on('change', 'select', function() {
                var selectedPalette = $(this).val();
                SemanticTokenCustomizerControls.handlePaletteChange(selectedPalette);
                SemanticTokenCustomizerControls.updatePalettePreview(selectedPalette);
            });

            // Set initial preview
            var currentPalette = wp.customize('semantic_color_palette')();
            this.updatePalettePreview(currentPalette);
        },

        /**
         * Build visual palette previews
         */
        buildPaletteViews: function($container) {
            if (typeof semanticTokenCustomizer === 'undefined' || !semanticTokenCustomizer.palettes) {
                return;
            }

            var $grid = $('<div class="semantic-palette-grid"></div>');

            $.each(semanticTokenCustomizer.palettes, function(paletteId, palette) {
                var $paletteItem = $('<div class="semantic-palette-item" data-palette="' + paletteId + '"></div>');

                // Palette name
                $paletteItem.append('<div class="palette-name">' + palette.name + '</div>');

                // Color swatches
                var $swatches = $('<div class="palette-swatches"></div>');
                $swatches.append('<div class="color-swatch primary" style="background-color: ' + palette.primary + '"></div>');
                $swatches.append('<div class="color-swatch secondary" style="background-color: ' + palette.secondary + '"></div>');
                $swatches.append('<div class="color-swatch accent" style="background-color: ' + palette.accent + '"></div>');
                $paletteItem.append($swatches);

                // Description
                $paletteItem.append('<div class="palette-description">' + palette.description + '</div>');

                // Performance indicator
                $paletteItem.append('<div class="performance-indicator">⚡ Optimized</div>');

                $grid.append($paletteItem);
            });

            $container.append($grid);

            // Handle click selection
            $grid.on('click', '.semantic-palette-item', function() {
                var paletteId = $(this).data('palette');
                wp.customize('semantic_color_palette').set(paletteId);

                $grid.find('.semantic-palette-item').removeClass('selected');
                $(this).addClass('selected');
            });
        },

        /**
         * Setup color override controls with real-time preview
         */
        setupColorOverrideControls: function() {
            // Primary color override
            this.setupColorOverride('semantic_primary_override', 'primary');

            // Accent color override
            this.setupColorOverride('semantic_accent_override', 'accent');
        },

        /**
         * Setup individual color override control
         */
        setupColorOverride: function(settingId, colorRole) {
            var $control = $('#customize-control-' + settingId);
            if ($control.length === 0) return;

            // Add accessibility warning
            var $warning = $('<div class="accessibility-warning" style="display:none;">' +
                '<span class="warning-icon">⚠️</span> ' +
                'This color may not meet WCAG accessibility standards. Consider choosing a higher contrast color.' +
                '</div>');
            $control.append($warning);

            // Handle color changes
            wp.customize(settingId, function(value) {
                value.bind(function(newColor) {
                    SemanticTokenCustomizerControls.validateColorAccessibility(newColor, colorRole, $warning);
                    SemanticTokenCustomizerControls.updateColorPreview(colorRole, newColor);
                });
            });
        },

        /**
         * Setup component token controls
         */
        setupComponentControls: function() {
            // Button style control with visual preview
            var $buttonControl = $('#customize-control-semantic_button_style');
            if ($buttonControl.length > 0) {
                this.setupButtonStylePreview($buttonControl);
            }
        },

        /**
         * Setup button style preview
         */
        setupButtonStylePreview: function($control) {
            var $previewContainer = $('<div class="button-style-preview"></div>');

            // Create sample buttons for each style
            var styles = ['default', 'rounded', 'sharp', 'pill'];

            $.each(styles, function(index, style) {
                var $button = $('<button class="preview-button ' + style + '">' +
                    style.charAt(0).toUpperCase() + style.slice(1) +
                    '</button>');
                $previewContainer.append($button);
            });

            $control.find('.customize-control-content').append($previewContainer);

            // Handle style changes
            wp.customize('semantic_button_style', function(value) {
                value.bind(function(newStyle) {
                    $previewContainer.find('.preview-button').removeClass('active');
                    $previewContainer.find('.preview-button.' + newStyle).addClass('active');
                });
            });

            // Set initial state
            var currentStyle = wp.customize('semantic_button_style')();
            $previewContainer.find('.preview-button.' + currentStyle).addClass('active');
        },

        /**
         * Setup performance controls
         */
        setupPerformanceControls: function() {
            var $performanceControl = $('#customize-control-semantic_css_resolution');
            if ($performanceControl.length === 0) return;

            // Add performance metrics display
            var $metricsDisplay = $('<div class="performance-metrics">' +
                '<div class="metric">' +
                    '<span class="label">Token Resolution:</span> ' +
                    '<span class="value" id="resolution-time">-</span>' +
                '</div>' +
                '<div class="metric">' +
                    '<span class="label">CSS Generation:</span> ' +
                    '<span class="value" id="css-generation-time">-</span>' +
                '</div>' +
                '<div class="metric">' +
                    '<span class="label">Total Tokens:</span> ' +
                    '<span class="value">114 optimized</span>' +
                '</div>' +
                '</div>');

            $performanceControl.append($metricsDisplay);

            // Monitor performance
            this.updatePerformanceMetrics();
        },

        /**
         * Setup accessibility features
         */
        setupAccessibilityFeatures: function() {
            // Add keyboard navigation
            $('.semantic-palette-item, .preview-button').attr('tabindex', '0');

            // Handle keyboard selection
            $(document).on('keydown', '.semantic-palette-item, .preview-button', function(e) {
                if (e.keyCode === 13 || e.keyCode === 32) { // Enter or Space
                    e.preventDefault();
                    $(this).click();
                }
            });

            // Add ARIA labels
            $('.semantic-palette-item').each(function() {
                var paletteName = $(this).find('.palette-name').text();
                $(this).attr('aria-label', 'Select ' + paletteName + ' color palette');
                $(this).attr('role', 'button');
            });
        },

        /**
         * Setup real-time preview system
         */
        setupRealTimePreview: function() {
            // Performance-optimized preview updates
            this.previewUpdateQueue = [];
            this.previewUpdateTimeout = null;

            // Batch preview updates for performance
            this.scheduleBatchedPreviewUpdate = function(updateFunction) {
                this.previewUpdateQueue.push(updateFunction);

                if (this.previewUpdateTimeout) {
                    clearTimeout(this.previewUpdateTimeout);
                }

                this.previewUpdateTimeout = setTimeout(function() {
                    var startTime = performance.now();

                    while (SemanticTokenCustomizerControls.previewUpdateQueue.length > 0) {
                        var updateFn = SemanticTokenCustomizerControls.previewUpdateQueue.shift();
                        updateFn();
                    }

                    SemanticTokenCustomizerControls.performance.previewUpdateTime =
                        performance.now() - startTime;

                    SemanticTokenCustomizerControls.updatePerformanceMetrics();
                }, 50); // < 100ms response time target
            };
        },

        /**
         * Handle palette change with performance optimization
         */
        handlePaletteChange: function(paletteId) {
            var startTime = performance.now();

            // Update token cache
            var palette = semanticTokenCustomizer.palettes[paletteId];
            if (palette) {
                this.tokenCache.currentPalette = palette;

                // Clear color overrides when palette changes
                wp.customize('semantic_primary_override').set('');
                wp.customize('semantic_accent_override').set('');
            }

            this.performance.controlUpdateTime = performance.now() - startTime;
            this.updatePerformanceMetrics();
        },

        /**
         * Update palette preview visual feedback
         */
        updatePalettePreview: function(paletteId) {
            $('.semantic-palette-item').removeClass('selected');
            $('.semantic-palette-item[data-palette="' + paletteId + '"]').addClass('selected');

            // Add visual feedback
            $('.semantic-palette-item.selected').addClass('updating').delay(200).queue(function() {
                $(this).removeClass('updating').dequeue();
            });
        },

        /**
         * Update color preview for overrides
         */
        updateColorPreview: function(colorRole, color) {
            if (!color) return;

            var $preview = $('.color-preview.' + colorRole);
            if ($preview.length === 0) {
                $preview = $('<div class="color-preview ' + colorRole + '"></div>');
                $('#customize-control-semantic_' + colorRole + '_override').append($preview);
            }

            $preview.css('background-color', color);
        },

        /**
         * Validate color accessibility
         */
        validateColorAccessibility: function(color, colorRole, $warningElement) {
            if (!color) {
                $warningElement.hide();
                return;
            }

            // Simple contrast ratio check (simplified for demo)
            var contrast = this.calculateContrastRatio(color, '#ffffff');

            if (contrast < 4.5) { // WCAG AA standard
                $warningElement.show();
            } else {
                $warningElement.hide();
            }
        },

        /**
         * Calculate contrast ratio (simplified implementation)
         */
        calculateContrastRatio: function(color1, color2) {
            // Simplified contrast calculation
            // In production, use proper luminance calculation
            var rgb1 = this.hexToRgb(color1);
            var rgb2 = this.hexToRgb(color2);

            if (!rgb1 || !rgb2) return 1;

            var brightness1 = (rgb1.r * 299 + rgb1.g * 587 + rgb1.b * 114) / 1000;
            var brightness2 = (rgb2.r * 299 + rgb2.g * 587 + rgb2.b * 114) / 1000;

            return brightness1 > brightness2 ?
                (brightness1 + 0.05) / (brightness2 + 0.05) :
                (brightness2 + 0.05) / (brightness1 + 0.05);
        },

        /**
         * Convert hex to RGB
         */
        hexToRgb: function(hex) {
            var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            } : null;
        },

        /**
         * Update performance metrics display
         */
        updatePerformanceMetrics: function() {
            $('#resolution-time').text(Math.round(this.performance.controlUpdateTime) + 'ms');
            $('#css-generation-time').text(Math.round(this.performance.previewUpdateTime) + 'ms');

            // Add performance indicators
            var totalTime = this.performance.controlUpdateTime + this.performance.previewUpdateTime;
            var $indicator = $('.performance-indicator');

            if (totalTime < 100) {
                $indicator.removeClass('warning danger').addClass('good').text('⚡ Optimized');
            } else if (totalTime < 200) {
                $indicator.removeClass('good danger').addClass('warning').text('⚠️ Moderate');
            } else {
                $indicator.removeClass('good warning').addClass('danger').text('🐌 Slow');
            }
        }
    };

    /**
     * Initialize when customizer controls are ready
     */
    wp.customize.controlConstructor['select'] = wp.customize.Control.extend({
        ready: function() {
            // Call parent ready
            wp.customize.Control.prototype.ready.call(this);

            // Initialize semantic token controls if this is a semantic control
            if (this.id.indexOf('semantic_') === 0) {
                SemanticTokenCustomizerControls.init();
            }
        }
    });

    /**
     * Initialize when customizer is ready
     */
    wp.customize.bind('ready', function() {
        console.log('🎨 WordPress Customizer Ready - Initializing T7.3.3 Controls...');

        // Small delay to ensure all controls are rendered
        setTimeout(function() {
            SemanticTokenCustomizerControls.init();
        }, 100);
    });

    // Export for global access
    window.SemanticTokenCustomizerControls = SemanticTokenCustomizerControls;

})(jQuery, wp);
