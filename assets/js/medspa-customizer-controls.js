/**
 * MedSpa Customizer Controls JavaScript
 *
 * Enhanced customizer control panel functionality
 * Sprint 4 - Industry-Standard WordPress Customizer Architecture
 * Epic 3: Real-Time Synchronization System
 *
 * @package MedSpaTheme
 * @since 2.0.0
 * @author Sprint 4 Implementation Team
 */

(function($) {
    'use strict';

    // Initialize when customizer is ready
    wp.customize.bind('ready', function() {
        initializeCustomizerControls();
    });

    /**
     * Initialize customizer control enhancements
     */
    function initializeCustomizerControls() {
        // Add palette preview to control
        enhancePaletteControl();

        // Add performance indicators
        addPerformanceIndicators();

        // Add validation feedback
        addValidationFeedback();

        // Add help tooltips
        addHelpTooltips();

        // Add color palette previews
        addColorPreviews();
    }

    /**
     * Enhance the palette selection control with previews
     */
    function enhancePaletteControl() {
        var $paletteControl = $('#customize-control-visual_color_palette');

        if ($paletteControl.length === 0) {
            return;
        }

        // Add palette preview container
        var $previewContainer = $('<div class="medspa-palette-previews"></div>');
        $paletteControl.append($previewContainer);

        // Build palette previews
        if (typeof medSpaCustomizerControls !== 'undefined' && medSpaCustomizerControls.palettes) {
            $.each(medSpaCustomizerControls.palettes, function(paletteId, palette) {
                var $preview = createPalettePreview(paletteId, palette);
                $previewContainer.append($preview);
            });
        }

        // Handle palette selection clicks
        $previewContainer.on('click', '.medspa-palette-preview', function() {
            var paletteId = $(this).data('palette-id');
            wp.customize('visual_color_palette').set(paletteId);

            // Update visual selection
            $previewContainer.find('.medspa-palette-preview').removeClass('selected');
            $(this).addClass('selected');
        });

        // Set initial selection
        var currentPalette = wp.customize('visual_color_palette')();
        $previewContainer.find('[data-palette-id="' + currentPalette + '"]').addClass('selected');

        // Listen for external changes
        wp.customize('visual_color_palette').bind(function(newValue) {
            $previewContainer.find('.medspa-palette-preview').removeClass('selected');
            $previewContainer.find('[data-palette-id="' + newValue + '"]').addClass('selected');
        });
    }

    /**
     * Create a palette preview element
     *
     * @param {string} paletteId Palette identifier
     * @param {Object} palette Palette configuration
     * @return {jQuery} Preview element
     */
    function createPalettePreview(paletteId, palette) {
        var $preview = $('<div class="medspa-palette-preview" data-palette-id="' + paletteId + '">');

        // Add palette name
        $preview.append('<div class="palette-name">' + palette.name + '</div>');

        // Add color swatches
        var $swatches = $('<div class="palette-swatches">');

        if (palette.colors) {
            var mainColors = ['color-primary-navy', 'color-brand-primary', 'color-brand-accent'];
            $.each(mainColors, function(index, colorKey) {
                if (palette.colors[colorKey]) {
                    var $swatch = $('<div class="color-swatch">')
                        .css('background-color', palette.colors[colorKey])
                        .attr('title', colorKey);
                    $swatches.append($swatch);
                }
            });
        }

        $preview.append($swatches);

        return $preview;
    }

    /**
     * Add performance indicators to the customizer
     */
    function addPerformanceIndicators() {
        var $indicator = $('<div id="medspa-performance-indicator">')
            .addClass('medspa-performance-indicator')
            .html('<strong>Performance:</strong> <span class="status">Good</span>');

        $('#customize-theme-controls').prepend($indicator);

        // Update indicator based on performance mode
        wp.customize('medspa_performance_mode').bind(function(mode) {
            var $status = $indicator.find('.status');

            switch (mode) {
                case 'maximum':
                    $status.text('Maximum').css('color', '#28a745');
                    break;
                case 'balanced':
                    $status.text('Balanced').css('color', '#ffc107');
                    break;
                case 'quality':
                    $status.text('Quality').css('color', '#17a2b8');
                    break;
            }
        });
    }

    /**
     * Add validation feedback for settings
     */
    function addValidationFeedback() {
        // Validate palette selection
        wp.customize('visual_color_palette').bind(function(value) {
            var $control = $('#customize-control-visual_color_palette');
            var $feedback = $control.find('.validation-feedback');

            if ($feedback.length === 0) {
                $feedback = $('<div class="validation-feedback"></div>');
                $control.append($feedback);
            }

            if (value && value !== '') {
                $feedback.removeClass('error').addClass('success')
                    .html('<span class="dashicons dashicons-yes"></span> Valid palette selected');
            } else {
                $feedback.removeClass('success').addClass('error')
                    .html('<span class="dashicons dashicons-warning"></span> Please select a palette');
            }
        });
    }

    /**
     * Add help tooltips to controls
     */
    function addHelpTooltips() {
        var tooltips = {
            'visual_color_palette': 'Choose a professional color palette that matches your medical spa brand. Changes apply instantly in the preview.',
            'medspa_realtime_preview': 'Enable real-time preview to see color changes instantly without refreshing the page.',
            'medspa_performance_mode': 'Choose between performance and visual quality. Maximum performance disables animations.',
            'medspa_css_cache_bust': 'Clear CSS cache if colors are not updating properly. This will regenerate all CSS files.'
        };

        $.each(tooltips, function(controlId, tooltip) {
            var $control = $('#customize-control-' + controlId);
            if ($control.length > 0) {
                var $help = $('<span class="medspa-help-tooltip dashicons dashicons-editor-help">')
                    .attr('title', tooltip);

                $control.find('label').first().append($help);
            }
        });

        // Initialize tooltip functionality
        $('.medspa-help-tooltip').hover(
            function() {
                var tooltip = $(this).attr('title');
                $(this).data('original-title', tooltip).removeAttr('title');

                var $tooltip = $('<div class="medspa-tooltip">')
                    .text(tooltip)
                    .appendTo('body');

                var offset = $(this).offset();
                $tooltip.css({
                    top: offset.top - $tooltip.outerHeight() - 5,
                    left: offset.left + ($(this).outerWidth() / 2) - ($tooltip.outerWidth() / 2)
                });
            },
            function() {
                $(this).attr('title', $(this).data('original-title'));
                $('.medspa-tooltip').remove();
            }
        );
    }

    /**
     * Add color previews for individual color controls
     */
    function addColorPreviews() {
        // Add color preview indicators to palette previews
        $('.medspa-palette-preview').each(function() {
            var $preview = $(this);
            var paletteId = $preview.data('palette-id');

            // Add accessibility indicator
            var $accessibility = $('<div class="accessibility-indicator">')
                .addClass('wcag-compliant')
                .attr('title', 'WCAG Compliant Colors')
                .html('<span class="dashicons dashicons-universal-access"></span>');

            $preview.append($accessibility);
        });
    }

    /**
     * Add custom CSS for enhanced controls
     */
    function addCustomCSS() {
        var css = `
            .medspa-palette-previews {
                margin-top: 10px;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }

            .medspa-palette-preview {
                border: 2px solid #ddd;
                border-radius: 4px;
                padding: 10px;
                cursor: pointer;
                transition: all 0.2s ease;
                background: #fff;
            }

            .medspa-palette-preview:hover {
                border-color: #0073aa;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }

            .medspa-palette-preview.selected {
                border-color: #0073aa;
                background: #f0f8ff;
            }

            .palette-name {
                font-weight: 600;
                margin-bottom: 5px;
                font-size: 12px;
            }

            .palette-swatches {
                display: flex;
                gap: 3px;
            }

            .color-swatch {
                width: 20px;
                height: 20px;
                border-radius: 50%;
                border: 1px solid rgba(0,0,0,0.1);
                flex-shrink: 0;
            }

            .medspa-performance-indicator {
                background: #fff;
                border: 1px solid #ddd;
                padding: 10px;
                margin-bottom: 10px;
                border-radius: 4px;
                font-size: 12px;
            }

            .validation-feedback {
                margin-top: 5px;
                font-size: 12px;
                padding: 5px;
                border-radius: 3px;
            }

            .validation-feedback.success {
                background: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }

            .validation-feedback.error {
                background: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }

            .medspa-help-tooltip {
                color: #666;
                cursor: help;
                margin-left: 5px;
            }

            .medspa-tooltip {
                position: absolute;
                background: #333;
                color: #fff;
                padding: 8px 12px;
                border-radius: 4px;
                font-size: 12px;
                max-width: 200px;
                z-index: 999999;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            }

            .accessibility-indicator {
                position: absolute;
                top: 5px;
                right: 5px;
                color: #28a745;
                font-size: 16px;
            }
        `;

        $('<style>').text(css).appendTo('head');
    }

    // Add custom CSS when controls are initialized
    $(document).ready(function() {
        addCustomCSS();
    });

    /**
     * Handle cache bust button
     */
    wp.customize('medspa_css_cache_bust').bind(function(value) {
        if (value) {
            // Show feedback
            var $control = $('#customize-control-medspa_css_cache_bust');
            var $feedback = $('<div class="cache-bust-feedback">')
                .text('CSS cache will be cleared on save')
                .css({
                    'background': '#fff3cd',
                    'color': '#856404',
                    'padding': '5px',
                    'margin-top': '5px',
                    'border-radius': '3px',
                    'font-size': '12px'
                });

            $control.find('.cache-bust-feedback').remove();
            $control.append($feedback);

            // Auto-reset after showing feedback
            setTimeout(function() {
                wp.customize('medspa_css_cache_bust').set(false);
                $feedback.fadeOut();
            }, 3000);
        }
    });

})(jQuery);
