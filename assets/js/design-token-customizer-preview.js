/**
 * Design Token System - WordPress Customizer Preview Integration
 * Real-time preview updates for the extensible design token architecture
 *
 * @since PVC-007-DT
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // Wait for WordPress Customizer and our systems to be ready
    wp.customize.bind('ready', function() {
        console.log('[Design Token Customizer] Initializing real-time preview...');

        // Ensure our Universal Customization Engine is available
        if (typeof window.universalCustomizationEngine === 'undefined') {
            console.warn('[Design Token Customizer] Universal Customization Engine not available, retrying...');
            setTimeout(initializeDesignTokenPreview, 500);
            return;
        }

        initializeDesignTokenPreview();
    });

    /**
     * Initialize Design Token System preview integration
     */
    function initializeDesignTokenPreview() {
        const engine = window.universalCustomizationEngine;

        // =============================================================================
        // COLOR DOMAIN PREVIEW
        // =============================================================================

        // Semantic Color Palette Changes
        wp.customize('dt_color_palette', function(value) {
            value.bind(function(newval) {
                console.log('[Design Token Preview] Color palette changed to:', newval);

                // Apply through Universal Customization Engine
                engine.applyCustomization('color', {
                    paletteId: newval
                }).then(results => {
                    console.log('[Design Token Preview] Color palette applied:', results);
                    updateCSSVariables(results.directChanges);
                }).catch(error => {
                    console.error('[Design Token Preview] Color palette error:', error);
                });
            });
        });

        // Primary Color Override
        wp.customize('dt_color_primary', function(value) {
            value.bind(function(newval) {
                if (newval) {
                    console.log('[Design Token Preview] Primary color override:', newval);

                    engine.applyCustomization('color', {
                        primaryColorOverride: newval
                    }).then(results => {
                        updateCSSVariables(results.directChanges);
                    });
                }
            });
        });

        // Contrast Mode Changes
        wp.customize('dt_color_contrast_mode', function(value) {
            value.bind(function(newval) {
                console.log('[Design Token Preview] Contrast mode changed to:', newval);

                engine.applyCustomization('color', {
                    contrastMode: newval
                }).then(results => {
                    updateCSSVariables(results.directChanges);
                });
            });
        });

        // =============================================================================
        // TYPOGRAPHY DOMAIN PREVIEW
        // =============================================================================

        // Typography Pairing Changes
        wp.customize('dt_typography_pairing', function(value) {
            value.bind(function(newval) {
                console.log('[Design Token Preview] Typography pairing changed to:', newval);

                engine.applyCustomization('typography', {
                    pairingId: newval
                }).then(results => {
                    updateCSSVariables(results.directChanges);
                    updateFontLoading(results.directChanges);
                });
            });
        });

        // Typography Scale Changes
        wp.customize('dt_typography_scale', function(value) {
            value.bind(function(newval) {
                console.log('[Design Token Preview] Typography scale changed to:', newval);

                engine.applyCustomization('typography', {
                    scale: newval
                }).then(results => {
                    updateCSSVariables(results.directChanges);
                });
            });
        });

        // Base Font Size Changes
        wp.customize('dt_typography_base_size', function(value) {
            value.bind(function(newval) {
                console.log('[Design Token Preview] Base font size changed to:', newval);

                engine.applyCustomization('typography', {
                    baseSize: parseInt(newval)
                }).then(results => {
                    updateCSSVariables(results.directChanges);
                });
            });
        });

        // =============================================================================
        // COMPONENT DOMAIN PREVIEW
        // =============================================================================

        // Component Style Changes
        wp.customize('dt_component_style', function(value) {
            value.bind(function(newval) {
                console.log('[Design Token Preview] Component style changed to:', newval);

                engine.applyCustomization('component', {
                    stylePreset: newval
                }).then(results => {
                    updateCSSVariables(results.directChanges);
                });
            });
        });

        // Border Radius Changes
        wp.customize('dt_component_border_radius', function(value) {
            value.bind(function(newval) {
                console.log('[Design Token Preview] Border radius changed to:', newval);

                engine.applyCustomization('component', {
                    borderRadiusScale: newval
                }).then(results => {
                    updateCSSVariables(results.directChanges);
                });
            });
        });

        // =============================================================================
        // EXAMPLE PLUGINS PREVIEW
        // =============================================================================

        // Spacing Plugin - Base Spacing
        wp.customize('dt_spacing_base', function(value) {
            value.bind(function(newval) {
                console.log('[Design Token Preview] Spacing base changed to:', newval);

                if (engine.getDomain('spacing-customization')) {
                    engine.applyCustomization('spacing-customization', {
                        baseSpacing: parseInt(newval)
                    }).then(results => {
                        updateCSSVariables(results.directChanges);
                    });
                }
            });
        });

        // Spacing Plugin - Scale Ratio
        wp.customize('dt_spacing_ratio', function(value) {
            value.bind(function(newval) {
                console.log('[Design Token Preview] Spacing ratio changed to:', newval);

                if (engine.getDomain('spacing-customization')) {
                    engine.applyCustomization('spacing-customization', {
                        scaleRatio: parseFloat(newval)
                    }).then(results => {
                        updateCSSVariables(results.directChanges);
                    });
                }
            });
        });

        // Animation Plugin
        wp.customize('dt_animation_style', function(value) {
            value.bind(function(newval) {
                console.log('[Design Token Preview] Animation style changed to:', newval);

                if (engine.getDomain('animation-customization')) {
                    engine.applyCustomization('animation-customization', {
                        animationStyle: newval
                    }).then(results => {
                        updateCSSVariables(results.directChanges);
                    });
                }
            });
        });

        // Dark Mode Plugin
        wp.customize('dt_dark_mode_enabled', function(value) {
            value.bind(function(newval) {
                console.log('[Design Token Preview] Dark mode changed to:', newval);

                if (engine.getDomain('dark-mode-customization')) {
                    engine.applyCustomization('dark-mode-customization', {
                        enabled: newval
                    }).then(results => {
                        updateCSSVariables(results.directChanges);
                        toggleDarkModeClass(newval);
                    });
                }
            });
        });

        console.log('[Design Token Customizer] Real-time preview ready ✅');
    }

    /**
     * Update CSS variables with new token values
     * @param {Object} changes - Changes object with tokens
     */
    function updateCSSVariables(changes) {
        if (!changes || !changes.tokens) {
            return;
        }

        const root = document.documentElement;
        const tokens = changes.tokens;

        // Update CSS custom properties
        Object.values(tokens).forEach(token => {
            if (token.cssVariable && token.value) {
                root.style.setProperty(token.cssVariable, token.value);
                console.log(`[CSS Variable Update] ${token.cssVariable}: ${token.value}`);
            }
        });

        // Trigger a custom event for other systems
        $(document).trigger('designTokensUpdated', {
            changes: changes,
            timestamp: Date.now()
        });
    }

    /**
     * Handle font loading for typography changes
     * @param {Object} changes - Typography changes
     */
    function updateFontLoading(changes) {
        if (!changes || !changes.metadata || !changes.metadata.googleFontsUrl) {
            return;
        }

        const existingFontLink = document.getElementById('design-token-google-fonts');
        if (existingFontLink) {
            existingFontLink.remove();
        }

        // Add new Google Fonts link
        const fontLink = document.createElement('link');
        fontLink.id = 'design-token-google-fonts';
        fontLink.rel = 'stylesheet';
        fontLink.href = changes.metadata.googleFontsUrl;
        document.head.appendChild(fontLink);

        console.log('[Font Loading] Google Fonts updated:', changes.metadata.googleFontsUrl);
    }

    /**
     * Toggle dark mode class on body
     * @param {boolean} enabled - Dark mode enabled
     */
    function toggleDarkModeClass(enabled) {
        const body = document.body;
        if (enabled) {
            body.classList.add('dark-mode');
        } else {
            body.classList.remove('dark-mode');
        }
        console.log('[Dark Mode] Toggled:', enabled);
    }

    /**
     * Performance monitoring for customizer updates
     */
    function monitorPerformance() {
        const performanceEntries = performance.getEntriesByType('measure');
        const customizationEntries = performanceEntries.filter(entry =>
            entry.name.includes('customization-engine')
        );

        if (customizationEntries.length > 0) {
            const avgTime = customizationEntries.reduce((sum, entry) => sum + entry.duration, 0) / customizationEntries.length;
            console.log(`[Performance] Average customization time: ${avgTime.toFixed(2)}ms`);

            if (avgTime > 100) {
                console.warn('[Performance] Customization updates taking longer than 100ms target');
            }
        }
    }

    // Monitor performance every 5 seconds
    setInterval(monitorPerformance, 5000);

    // Export for debugging
    window.designTokenCustomizerPreview = {
        updateCSSVariables: updateCSSVariables,
        updateFontLoading: updateFontLoading,
        toggleDarkModeClass: toggleDarkModeClass,
        monitorPerformance: monitorPerformance
    };

})(jQuery);
