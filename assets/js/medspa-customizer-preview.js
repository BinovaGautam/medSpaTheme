/**
 * MedSpa Customizer Preview JavaScript
 *
 * Real-time preview updates for WordPress customizer
 * Sprint 4 - Industry-Standard WordPress Customizer Architecture
 * Epic 3: Real-Time Synchronization System
 *
 * @package MedSpaTheme
 * @since 2.0.0
 * @author Sprint 4 Implementation Team
 */

(function($) {
    'use strict';

    // Initialize when document is ready
    $(document).ready(function() {
        if (typeof wp !== 'undefined' && wp.customize) {
            initializeCustomizerPreview();
        }
    });

    /**
     * Initialize customizer preview bindings
     */
    function initializeCustomizerPreview() {
        // Palette change handler with real-time updates
        wp.customize('visual_color_palette', function(value) {
            value.bind(function(newPalette) {
                updatePalettePreview(newPalette);
            });
        });

        // Real-time preview toggle
        wp.customize('medspa_realtime_preview', function(value) {
            value.bind(function(enabled) {
                toggleRealTimePreview(enabled);
            });
        });

        // Performance mode changes
        wp.customize('medspa_performance_mode', function(value) {
            value.bind(function(mode) {
                updatePerformanceMode(mode);
            });
        });

        // Initialize preview enhancements
        initializePreviewEnhancements();
    }

    /**
     * Update palette preview in real-time
     *
     * @param {string} paletteId Palette identifier
     */
    function updatePalettePreview(paletteId) {
        // Check if real-time preview is enabled
        var realtimeEnabled = wp.customize('medspa_realtime_preview')();
        if (!realtimeEnabled) {
            return; // Real-time preview disabled
        }

        // Get palette configuration from localized data
        if (typeof medSpaCustomizer !== 'undefined' && medSpaCustomizer.palettes[paletteId]) {
            var palette = medSpaCustomizer.palettes[paletteId];
            applyPaletteColors(palette);

            // Add visual feedback
            showPreviewUpdateFeedback(palette.name);
        } else {
            // Fallback: request palette data via AJAX
            requestPaletteData(paletteId);
        }
    }

    /**
     * Apply palette colors to the preview
     *
     * @param {Object} palette Palette configuration object
     */
    function applyPaletteColors(palette) {
        if (!palette.colors) {
            return;
        }

        // Update CSS custom properties on document root
        var root = document.documentElement;
        $.each(palette.colors, function(property, value) {
            var propName = property.indexOf('--') === 0 ? property : '--' + property;
            root.style.setProperty(propName, value);
        });

        // Update specific elements directly for immediate visual feedback
        updateComponentColors(palette.colors);

        // Trigger custom event for other scripts
        $(document).trigger('medSpaPreviewUpdated', [palette]);
    }

    /**
     * Update component colors directly
     *
     * @param {Object} colors Color configuration
     */
    function updateComponentColors(colors) {
        // Update buttons
        if (colors['color-brand-primary']) {
            $('.btn-primary').css('background-color', colors['color-brand-primary']);
        }

        // Update links
        if (colors['color-brand-primary']) {
            $('a').css('color', colors['color-brand-primary']);
        }

        // Update header
        if (colors['color-primary-navy']) {
            $('.site-header').css('background-color', colors['color-primary-navy']);
        }

        // Update navigation
        if (colors['color-brand-primary']) {
            $('.main-navigation a').css('color', colors['color-brand-primary']);
        }

        // Update text colors
        if (colors['color-secondary-text']) {
            $('.secondary-text').css('color', colors['color-secondary-text']);
        }

        if (colors['color-muted-text']) {
            $('.muted-text').css('color', colors['color-muted-text']);
        }

        // Update form focus colors
        if (colors['color-brand-primary']) {
            var brandColor = colors['color-brand-primary'];
            var focusStyle = 'border-color: ' + brandColor + '; box-shadow: 0 0 0 0.2rem ' + hexToRgba(brandColor, 0.25) + ';';
            updateDynamicCSS('.form-control:focus', focusStyle);
        }
    }

    /**
     * Update dynamic CSS rules
     *
     * @param {string} selector CSS selector
     * @param {string} styles CSS styles
     */
    function updateDynamicCSS(selector, styles) {
        var styleId = 'medspa-dynamic-preview-css';
        var $style = $('#' + styleId);

        if ($style.length === 0) {
            $style = $('<style id="' + styleId + '"></style>').appendTo('head');
        }

        var css = $style.text();
        var rule = selector + ' { ' + styles + ' }';

        // Replace existing rule or append new one
        var regex = new RegExp(selector.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '\\s*{[^}]*}', 'g');
        if (regex.test(css)) {
            css = css.replace(regex, rule);
        } else {
            css += '\n' + rule;
        }

        $style.text(css);
    }

    /**
     * Convert hex color to RGBA
     *
     * @param {string} hex Hex color
     * @param {number} alpha Alpha value
     * @return {string} RGBA color string
     */
    function hexToRgba(hex, alpha) {
        var r = parseInt(hex.slice(1, 3), 16);
        var g = parseInt(hex.slice(3, 5), 16);
        var b = parseInt(hex.slice(5, 7), 16);
        return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + alpha + ')';
    }

    /**
     * Show visual feedback for preview updates
     *
     * @param {string} paletteName Palette name
     */
    function showPreviewUpdateFeedback(paletteName) {
        // Remove existing feedback
        $('.medspa-preview-feedback').remove();

        // Add new feedback element
        var $feedback = $('<div class="medspa-preview-feedback">')
            .text('Applied: ' + paletteName)
            .css({
                position: 'fixed',
                top: '20px',
                right: '20px',
                background: 'rgba(0, 0, 0, 0.8)',
                color: 'white',
                padding: '10px 15px',
                borderRadius: '4px',
                zIndex: '999999',
                fontSize: '14px',
                transition: 'opacity 0.3s ease'
            })
            .appendTo('body');

        // Fade out after 2 seconds
        setTimeout(function() {
            $feedback.fadeOut(300, function() {
                $(this).remove();
            });
        }, 2000);
    }

    /**
     * Toggle real-time preview functionality
     *
     * @param {boolean} enabled Whether real-time preview is enabled
     */
    function toggleRealTimePreview(enabled) {
        $('body').toggleClass('medspa-realtime-preview', enabled);

        if (enabled) {
            // Re-apply current palette if enabling
            var currentPalette = wp.customize('visual_color_palette')();
            if (currentPalette) {
                updatePalettePreview(currentPalette);
            }
        } else {
            // Clear dynamic styles if disabling
            $('#medspa-dynamic-preview-css').remove();
        }
    }

    /**
     * Update performance mode
     *
     * @param {string} mode Performance mode
     */
    function updatePerformanceMode(mode) {
        // Remove existing performance classes
        $('body').removeClass('medspa-performance-maximum medspa-performance-balanced medspa-performance-quality');

        // Add new performance class
        $('body').addClass('medspa-performance-' + mode);

        // Apply performance-specific styles
        switch (mode) {
            case 'maximum':
                updateDynamicCSS('*', 'transition: none !important;');
                break;
            case 'balanced':
                updateDynamicCSS('.btn, a', 'transition: all 0.15s ease;');
                break;
            case 'quality':
                updateDynamicCSS('.btn, a, .site-header', 'transition: all 0.3s ease;');
                break;
        }
    }

    /**
     * Request palette data via AJAX (fallback)
     *
     * @param {string} paletteId Palette identifier
     */
    function requestPaletteData(paletteId) {
        if (typeof medSpaCustomizer === 'undefined' || !medSpaCustomizer.ajaxUrl) {
            return;
        }

        $.ajax({
            url: medSpaCustomizer.ajaxUrl,
            type: 'POST',
            data: {
                action: 'medspa_get_palette_data',
                palette_id: paletteId,
                nonce: medSpaCustomizer.nonce
            },
            success: function(response) {
                if (response.success && response.data) {
                    applyPaletteColors(response.data);
                }
            },
            error: function() {
                console.warn('Failed to load palette data for:', paletteId);
            }
        });
    }

    /**
     * Initialize additional preview enhancements
     */
    function initializePreviewEnhancements() {
        // Add preview indicator
        if ($('.medspa-preview-indicator').length === 0) {
            $('<div class="medspa-preview-indicator">')
                .text('Live Preview')
                .css({
                    position: 'fixed',
                    bottom: '20px',
                    left: '20px',
                    background: 'rgba(135, 169, 107, 0.9)',
                    color: 'white',
                    padding: '5px 10px',
                    borderRadius: '3px',
                    fontSize: '12px',
                    zIndex: '999998'
                })
                .appendTo('body');
        }

        // Add smooth transitions for preview mode
        $('head').append('<style>.medspa-realtime-preview * { transition: color 0.2s ease, background-color 0.2s ease; }</style>');
    }

    /**
     * Handle preview iframe messages (if needed)
     */
    window.addEventListener('message', function(event) {
        if (event.data && event.data.type === 'medspa-customizer-update') {
            updatePalettePreview(event.data.palette);
        }
    });

})(jQuery);
