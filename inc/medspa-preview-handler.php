<?php
/**
 * MedSpa Preview Handler
 *
 * Real-time preview handler for customizer changes
 * Sprint 4 - Industry-Standard WordPress Customizer Architecture
 * Epic 3: Real-Time Synchronization System
 *
 * @package MedSpaTheme
 * @subpackage Customizer
 * @since 2.0.0
 * @author Sprint 4 Implementation Team
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * MedSpa Preview Handler Class
 *
 * Handles real-time preview updates for customizer changes.
 */
class MedSpa_Preview_Handler {

    /**
     * Constructor
     */
    public function __construct() {
        // Initialize if needed
    }

    /**
     * Initialize preview hooks
     */
    public function init_preview_hooks() {
        if (is_customize_preview()) {
            add_action('wp_head', array($this, 'output_preview_styles'), 999);
            add_action('wp_footer', array($this, 'output_preview_scripts'), 999);
        }
    }

    /**
     * Output preview styles for real-time updates
     */
    public function output_preview_styles() {
        ?>
        <style id="medspa-preview-styles">
        /* Preview styles will be updated by JavaScript */
        </style>
        <?php
    }

    /**
     * Output preview scripts for real-time updates
     */
    public function output_preview_scripts() {
        ?>
        <script>
        (function($) {
            'use strict';

            // Initialize preview handler when document is ready
            $(document).ready(function() {
                if (typeof wp !== 'undefined' && wp.customize) {
                    initializePreviewUpdates();
                }
            });

            function initializePreviewUpdates() {
                // Handle palette changes
                wp.customize('visual_color_palette', function(value) {
                    value.bind(function(newPalette) {
                        updatePalettePreview(newPalette);
                    });
                });

                // Handle real-time preview toggle
                wp.customize('medspa_realtime_preview', function(value) {
                    value.bind(function(enabled) {
                        toggleRealTimePreview(enabled);
                    });
                });
            }

            function updatePalettePreview(paletteId) {
                // Get palette configuration from localized data
                if (typeof medSpaCustomizer !== 'undefined' && medSpaCustomizer.palettes[paletteId]) {
                    var palette = medSpaCustomizer.palettes[paletteId];
                    applyPaletteColors(palette);
                }
            }

            function applyPaletteColors(palette) {
                if (!palette.colors) return;

                var $previewStyles = $('#medspa-preview-styles');
                var css = ':root {';

                // Build CSS variables
                $.each(palette.colors, function(property, value) {
                    var propName = property.indexOf('--') === 0 ? property : '--' + property;
                    css += propName + ':' + value + ';';
                });

                css += '}';

                // Update preview styles
                $previewStyles.text(css);

                // Trigger custom event for other scripts
                $(document).trigger('medSpaPreviewUpdated', [palette]);
            }

            function toggleRealTimePreview(enabled) {
                $('body').toggleClass('medspa-realtime-preview', enabled);
            }

        })(jQuery);
        </script>
        <?php
    }

    /**
     * Get preview configuration for JavaScript
     *
     * @return array Preview configuration
     */
    public function get_preview_config() {
        return array(
            'enabled' => get_theme_mod('medspa_realtime_preview', true),
            'performance_mode' => get_theme_mod('medspa_performance_mode', 'balanced'),
            'current_palette' => get_theme_mod('visual_color_palette', 'professional-trust'),
        );
    }
}
