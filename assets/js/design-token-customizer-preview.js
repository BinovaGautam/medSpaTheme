/**
 * Design Token System - WordPress Customizer Preview Integration
 * Real-time preview updates for the extensible design token architecture
 *
 * @since PVC-007-DT
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // Check if WordPress Customizer is available
    if (typeof wp === 'undefined' || typeof wp.customize === 'undefined') {
        console.warn('[Design Token Customizer] WordPress Customizer not available, skipping initialization');
        return;
    }

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
        const typographySystem = window.typographyDomainSystem;
        const tokenRegistry = window.designTokenRegistry;

        if (!engine) {
            console.warn('[Design Token Preview] Universal Customization Engine not available');
            return;
        }

        console.log('[Design Token Preview] Initializing with WordPress Customizer API');

        // =============================================================================
        // COLOR DOMAIN PREVIEW
        // =============================================================================

        // Semantic Color Palette Changes
        wp.customize('dt_color_palette', function(value) {
            value.bind(function(newval) {
                console.log('[Design Token Preview] Color palette changed to:', newval);

                engine.applyCustomization('color', {
                    paletteId: newval,
                    previewMode: true
                }).then(results => {
                    updateCSSVariables(results.directChanges);
                    showPreviewFeedback('Color palette updated', 'success');
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
                    showPreviewFeedback('Contrast mode updated', 'info');
                });
            });
        });

        // =============================================================================
        // TYPOGRAPHY DOMAIN PREVIEW - ENHANCED
        // =============================================================================

        // Typography Pairing Changes
        wp.customize('dt_typography_pairing', function(value) {
            value.bind(function(newval) {
                console.log('[Design Token Preview] Typography pairing changed to:', newval);

                if (typographySystem) {
                    // Generate typography tokens using Typography Domain System
                    const fontSelection = {
                        style: newval,
                        baseSize: wp.customize('dt_typography_base_size')() + 'px',
                        scale: wp.customize('dt_typography_scale')(),
                        readabilityLevel: 'optimal'
                    };

                    const typographyTokens = typographySystem.generateFromSelection(fontSelection);

                    if (typographyTokens && typographyTokens.optimized) {
                        // Apply typography tokens to preview
                        applyTypographyTokens(typographyTokens.optimized);

                        // Load web fonts if needed
                        if (typographyTokens.fontLoading && typographyTokens.fontLoading.webfonts) {
                            loadWebFonts(typographyTokens.fontLoading.webfonts);
                        }

                        showPreviewFeedback(`Typography pairing "${newval}" applied`, 'success');
                    }
                } else {
                    // Fallback to basic engine
                    engine.applyCustomization('typography', {
                        pairingId: newval
                    }).then(results => {
                        updateCSSVariables(results.directChanges);
                        updateFontLoading(results.directChanges);
                    });
                }
            });
        });

        // Typography Scale Changes
        wp.customize('dt_typography_scale', function(value) {
            value.bind(function(newval) {
                console.log('[Design Token Preview] Typography scale changed to:', newval);

                if (typographySystem) {
                    // Regenerate with new scale
                    const fontSelection = {
                        style: wp.customize('dt_typography_pairing')(),
                        baseSize: wp.customize('dt_typography_base_size')() + 'px',
                        scale: newval,
                        readabilityLevel: 'optimal'
                    };

                    const typographyTokens = typographySystem.generateFromSelection(fontSelection);

                    if (typographyTokens && typographyTokens.optimized) {
                        applyTypographyTokens(typographyTokens.optimized);
                        showPreviewFeedback(`Typography scale "${newval}" applied`, 'success');
                    }
                } else {
                    // Fallback
                    engine.applyCustomization('typography', {
                        scale: newval
                    }).then(results => {
                        updateCSSVariables(results.directChanges);
                    });
                }
            });
        });

        // Base Font Size Changes
        wp.customize('dt_typography_base_size', function(value) {
            value.bind(function(newval) {
                console.log('[Design Token Preview] Base font size changed to:', newval + 'px');

                if (typographySystem) {
                    // Regenerate with new base size
                    const fontSelection = {
                        style: wp.customize('dt_typography_pairing')(),
                        baseSize: newval + 'px',
                        scale: wp.customize('dt_typography_scale')(),
                        readabilityLevel: 'optimal'
                    };

                    const typographyTokens = typographySystem.generateFromSelection(fontSelection);

                    if (typographyTokens && typographyTokens.optimized) {
                        applyTypographyTokens(typographyTokens.optimized);
                        showPreviewFeedback(`Base font size ${newval}px applied`, 'success');
                    }
                } else {
                    // Fallback
                    engine.applyCustomization('typography', {
                        baseSize: newval + 'px'
                    }).then(results => {
                        updateCSSVariables(results.directChanges);
                    });
                }
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
                    styleId: newval
                }).then(results => {
                    updateCSSVariables(results.directChanges);
                    showPreviewFeedback('Component style updated', 'success');
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

    /**
     * Apply typography tokens to the preview
     * @param {Object} typographyTokens - Generated typography tokens
     */
    function applyTypographyTokens(typographyTokens) {
        if (!typographyTokens) return;

        const cssVariables = {};

        Object.entries(typographyTokens).forEach(([roleKey, token]) => {
            if (token.cssVariable) {
                // Apply font size
                cssVariables[token.cssVariable + '-size'] = token.fontSize;

                // Apply font family
                cssVariables[token.cssVariable + '-family'] = token.fontFamily;

                // Apply font weight
                cssVariables[token.cssVariable + '-weight'] = token.fontWeight;

                // Apply line height
                cssVariables[token.cssVariable + '-line-height'] = token.lineHeight;

                // Apply letter spacing
                cssVariables[token.cssVariable + '-letter-spacing'] = token.letterSpacing;

                // Apply color if coordinated
                if (token.color) {
                    cssVariables[token.cssVariable + '-color'] = token.color;
                }
            }
        });

        // Apply all CSS variables at once
        updateCSSVariables(cssVariables);

        console.log('[Design Token Preview] Applied typography tokens:', Object.keys(cssVariables).length, 'variables');
    }

    /**
     * Load web fonts dynamically
     * @param {Object} webfonts - Web font configuration
     */
    function loadWebFonts(webfonts) {
        if (!webfonts) return;

        Object.entries(webfonts).forEach(([fontType, fontUrl]) => {
            if (fontUrl && fontUrl.startsWith('http')) {
                const linkId = `webfont-${fontType}`;

                // Remove existing link if present
                const existingLink = document.getElementById(linkId);
                if (existingLink) {
                    existingLink.remove();
                }

                // Create new font link
                const link = document.createElement('link');
                link.id = linkId;
                link.rel = 'stylesheet';
                link.href = fontUrl;
                link.onload = () => {
                    console.log(`[Design Token Preview] Loaded web font: ${fontType}`);
                };

                document.head.appendChild(link);
            }
        });
    }

    /**
     * Show preview feedback to user
     * @param {string} message - Feedback message
     * @param {string} type - Message type (success, info, warning, error)
     */
    function showPreviewFeedback(message, type = 'info') {
        // Create or update feedback element
        let feedback = document.getElementById('design-token-preview-feedback');

        if (!feedback) {
            feedback = document.createElement('div');
            feedback.id = 'design-token-preview-feedback';
            feedback.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                padding: 12px 16px;
                border-radius: 4px;
                font-size: 14px;
                font-weight: 500;
                color: white;
                transition: all 0.3s ease;
                transform: translateX(100%);
            `;
            document.body.appendChild(feedback);
        }

        // Set message and style based on type
        feedback.textContent = message;

        const colors = {
            success: '#10B981',
            info: '#3B82F6',
            warning: '#F59E0B',
            error: '#EF4444'
        };

        feedback.style.backgroundColor = colors[type] || colors.info;
        feedback.style.transform = 'translateX(0)';

        // Hide after 3 seconds
        setTimeout(() => {
            feedback.style.transform = 'translateX(100%)';
        }, 3000);
    }

})(jQuery);
