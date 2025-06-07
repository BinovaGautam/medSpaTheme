/**
 * Simple Visual Customizer JavaScript
 * Clean implementation for admin bar triggered sidebar
 */

(function($) {
    'use strict';

    let colorPaletteInterface = null;
    let currentConfig = {};

    /**
     * Initialize Simple Visual Customizer
     */
    function initSimpleVisualCustomizer() {
        // Admin bar trigger
        $(document).on('click', '#wp-admin-bar-visual-customizer a', function(e) {
            e.preventDefault();
            openSidebar();
        });

        // Close button
        $(document).on('click', '#simple-vc-close', function() {
            closeSidebar();
        });

        // Overlay click to close
        $(document).on('click', '#simple-vc-overlay', function() {
            closeSidebar();
        });

        // Apply button
        $(document).on('click', '#simple-vc-apply', function() {
            applyChanges();
        });

        // Reset button
        $(document).on('click', '#simple-vc-reset', function() {
            resetChanges();
        });

        // ESC key to close
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $('.simple-vc-sidebar').hasClass('open')) {
                closeSidebar();
            }
        });

        console.log('Simple Visual Customizer initialized');
    }

    /**
     * Open Sidebar
     */
    function openSidebar() {
        $('.simple-vc-sidebar').addClass('open');
        $('.simple-vc-overlay').addClass('show');
        $('body').css('overflow', 'hidden');

        // Load color palette interface if not already loaded
        if (!colorPaletteInterface) {
            loadColorPaletteInterface();
        }
    }

    /**
     * Close Sidebar
     */
    function closeSidebar() {
        $('.simple-vc-sidebar').removeClass('open');
        $('.simple-vc-overlay').removeClass('show');
        $('body').css('overflow', '');
    }

    /**
     * Load Color Palette Interface
     */
    function loadColorPaletteInterface() {
        const container = $('#simple-color-palette-container');

        // Check if all required classes are available
        if (typeof ColorPaletteInterface !== 'undefined' &&
            typeof ColorSystemManager !== 'undefined' &&
            typeof SemanticColorSystem !== 'undefined') {

            try {
                console.log('🎨 Initializing full ColorPaletteInterface...');
                container.html('<p>Initializing color palette interface...</p>');

                // Initialize ColorSystemManager first
                const colorSystemManager = new ColorSystemManager({ autoInit: false });
                console.log('✅ ColorSystemManager created');

                // Create ColorPaletteInterface with proper configuration
                colorPaletteInterface = new ColorPaletteInterface('simple-color-palette-container', {
                    colorSystemManager: colorSystemManager,
                    enablePreview: true,
                    showAccessibilityInfo: true,
                    enableSearch: true,
                    compact: true,
                    onPaletteChange: function(paletteId, paletteData) {
                        currentConfig = {
                            activePalette: paletteId,
                            colorConfig: paletteData.colors || {},
                            paletteData: paletteData,
                            timestamp: Date.now()
                        };
                        console.log('🎯 Palette changed via ColorPaletteInterface:', paletteId);
                    }
                });

                console.log('✅ ColorPaletteInterface created');

                // Initialize the interface
                if (typeof colorPaletteInterface.initialize === 'function') {
                    colorPaletteInterface.initialize().then(initResult => {
                        console.log('✅ ColorPaletteInterface initialized successfully:', initResult);

                        // Set up event listeners for the interface
                        setupColorPaletteInterfaceEvents();

                    }).catch(error => {
                        console.error('❌ ColorPaletteInterface initialization failed:', error);
                        showFallbackInterface(container);
                    });
                } else {
                    console.log('✅ ColorPaletteInterface created (no async initialization)');
                    setupColorPaletteInterfaceEvents();
                }

                return;

            } catch (error) {
                console.error('❌ Error creating ColorPaletteInterface:', error);
                showFallbackInterface(container);
                return;
            }
        }

        console.log('⚠️ ColorPaletteInterface components not available, showing fallback');
        showFallbackInterface(container);
    }

    /**
     * Setup Color Palette Interface Events
     */
    function setupColorPaletteInterfaceEvents() {
        // Listen for palette selection events from the interface
        document.addEventListener('paletteInterface:paletteSelected', function(event) {
            const { paletteId, paletteData } = event.detail;
            console.log('📡 Received paletteSelected event:', paletteId);

            currentConfig = {
                activePalette: paletteId,
                colorConfig: paletteData.colors || {},
                paletteData: paletteData,
                timestamp: Date.now()
            };

            // Show preview immediately when palette is selected
            if (paletteData) {
                console.log('🎨 Auto-previewing selected palette');
                applyPaletteCSS(paletteData);
                showMessage(`Previewing "${paletteData.name}" palette. Click "Apply Changes" to save.`, 'success');
            }
        });

        // Listen for apply button clicks from the interface
        document.addEventListener('paletteInterface:paletteApplied', function(event) {
            console.log('📡 Received paletteApplied event:', event.detail);
            // The interface applied the palette, now save it
            applyChanges();
        });

        // Listen for preview toggle events
        document.addEventListener('paletteInterface:previewToggled', function(event) {
            const { isPreviewMode, paletteData } = event.detail;
            console.log('📡 Preview toggled:', isPreviewMode);

            if (isPreviewMode && paletteData) {
                applyPaletteCSS(paletteData);
                showMessage('Preview mode enabled', 'success');
            } else {
                // Remove preview styles
                $('#simple-vc-custom-styles').remove();
                showMessage('Preview mode disabled', 'success');
            }
        });

        console.log('✅ ColorPaletteInterface event listeners set up');
    }

    /**
     * Show Fallback Interface
     */
    function showFallbackInterface(container) {
        console.log('🔄 Loading fallback interface...');

        // Try to use SemanticColorSystem for fallback
        if (typeof SemanticColorSystem !== 'undefined') {
            try {
                const colorSystem = new SemanticColorSystem();
                const allPalettes = colorSystem.getAllPalettes();

                console.log(`Found ${allPalettes.length} palettes for fallback:`, allPalettes.map(p => p.name));

                let paletteHTML = `
                    <div class="simple-palette-fallback">
                        <h5>Available Palettes (${allPalettes.length})</h5>
                        <div class="palette-list">
                `;

                allPalettes.forEach((palette, index) => {
                    const isActive = index === 0 ? 'active' : '';
                    const categoryInfo = colorSystem.getCategory(palette.category);
                    const icon = categoryInfo?.icon || '🎨';

                    paletteHTML += `
                        <div class="palette-item ${isActive}" data-palette="${palette.id}">
                            ${icon} ${palette.name}
                            <small style="display: block; opacity: 0.7; font-size: 10px;">
                                ${palette.description?.substring(0, 40) || ''}...
                            </small>
                        </div>
                    `;
                });

                paletteHTML += `
                        </div>
                        <p><small>Using fallback palette selector (${allPalettes.length} palettes)</small></p>
                    </div>
                `;

                container.html(paletteHTML);

                // Set default config to first palette
                if (allPalettes.length > 0) {
                    currentConfig = {
                        activePalette: allPalettes[0].id,
                        colorConfig: allPalettes[0].colors,
                        timestamp: Date.now()
                    };
                }

                setupSemanticPaletteHandlers(colorSystem);
                return;

            } catch (error) {
                console.error('❌ Error with SemanticColorSystem fallback:', error);
            }
        }

        // Final fallback: Basic palette selector
        console.log('🔄 Using basic fallback palette selector');
        container.html(`
            <div class="simple-vc-message error">
                Advanced color systems unavailable
            </div>
            <div class="simple-palette-fallback">
                <h5>Basic Palettes</h5>
                <div class="palette-list">
                    <div class="palette-item active" data-palette="medical-clean">🏥 Medical Clean</div>
                    <div class="palette-item" data-palette="luxury-spa">✨ Luxury Spa</div>
                    <div class="palette-item" data-palette="professional">💼 Professional</div>
                </div>
                <p><small>Basic fallback (3 palettes)</small></p>
            </div>
        `);

        currentConfig = {
            activePalette: 'medical-clean',
            colorConfig: {},
            timestamp: Date.now()
        };

        setupFallbackPaletteHandlers();
    }

    /**
     * Setup Semantic Color System Palette Handlers
     */
    function setupSemanticPaletteHandlers(colorSystem) {
        $(document).on('click', '.palette-item', function() {
            const paletteId = $(this).data('palette');
            $('.palette-item').removeClass('active');
            $(this).addClass('active');

            try {
                const palette = colorSystem.getPalette(paletteId);
                currentConfig = {
                    activePalette: paletteId,
                    colorConfig: palette.colors,
                    paletteData: palette,
                    timestamp: Date.now()
                };

                console.log('Semantic palette selected:', paletteId, palette);

                // Show preview of colors
                showPalettePreview(palette);

                // Apply CSS immediately for visual feedback
                applyPaletteCSS(palette);
                showMessage(`Previewing "${palette.name}" palette. Click "Apply Changes" to save.`, 'success');

            } catch (error) {
                console.error('Error selecting palette:', error);
                currentConfig = {
                    activePalette: paletteId,
                    colorConfig: {},
                    timestamp: Date.now()
                };
                showMessage('Error loading palette, but selection saved.', 'error');
            }
        });
    }

    /**
     * Show Palette Preview
     */
    function showPalettePreview(palette) {
        // Create a small preview of the selected palette colors
        let previewHTML = '<div style="margin-top: 10px; padding: 10px; background: #f8f9fa; border-radius: 4px;">';
        previewHTML += '<strong>Preview:</strong><br>';
        previewHTML += '<div style="display: flex; gap: 3px; margin-top: 5px;">';

        Object.entries(palette.colors).forEach(([role, color]) => {
            previewHTML += `
                <div style="
                    width: 20px;
                    height: 20px;
                    background: ${color.hex};
                    border-radius: 3px;
                    border: 1px solid #ddd;
                    title: '${role}: ${color.hex}'
                "></div>
            `;
        });

        previewHTML += '</div></div>';

        // Remove existing preview and add new one
        $('.palette-preview').remove();
        $(previewHTML).addClass('palette-preview').insertAfter('.palette-list');
    }

    /**
     * Setup Fallback Palette Handlers
     */
    function setupFallbackPaletteHandlers() {
        $(document).on('click', '.palette-item', function() {
            const paletteId = $(this).data('palette');
            $('.palette-item').removeClass('active');
            $(this).addClass('active');

            // Create fallback palette data
            const paletteData = {
                id: paletteId,
                name: paletteId.replace('-', ' ').replace(/\b\w/g, l => l.toUpperCase()),
                colors: {}
            };

            currentConfig = {
                activePalette: paletteId,
                colorConfig: {},
                paletteData: paletteData,
                timestamp: Date.now()
            };

            console.log('Fallback palette selected:', paletteId);

            // Apply CSS immediately for visual feedback
            applyPaletteCSS(paletteData);
            showMessage(`Previewing "${paletteData.name}" palette. Click "Apply Changes" to save.`, 'success');
        });
    }

    /**
     * Apply Changes
     */
    function applyChanges() {
        if (!currentConfig.activePalette) {
            showMessage('Please select a color palette first.', 'error');
            return;
        }

        const $button = $('#simple-vc-apply');
        $button.prop('disabled', true).text('Applying...');

        // Generate and apply CSS immediately for visual feedback
        if (currentConfig.paletteData) {
            applyPaletteCSS(currentConfig.paletteData);
            showMessage('Palette applied to page! Saving settings...', 'success');
        }

        $.ajax({
            url: simpleCustomizer.ajaxUrl,
            type: 'POST',
            data: {
                action: 'simple_visual_customizer_apply',
                nonce: simpleCustomizer.nonce,
                config: JSON.stringify(currentConfig)
            },
            success: function(response) {
                if (response.success) {
                    showMessage('Visual customizer settings saved successfully! Changes are now permanent.', 'success');

                    // No need to reload if CSS is already applied
                    console.log('✅ Settings saved to database');
                } else {
                    showMessage(response.data || 'Failed to save changes permanently.', 'error');
                }
            },
            error: function() {
                showMessage('Network error. Changes applied locally but may not be saved.', 'error');
            },
            complete: function() {
                $button.prop('disabled', false).text('Apply Changes');
            }
        });
    }

    /**
     * Apply Palette CSS to the Page
     */
    function applyPaletteCSS(paletteData) {
        try {
            console.log('🎨 Applying palette CSS:', paletteData);

            // Remove existing custom palette styles
            $('#simple-vc-custom-styles').remove();

            // Generate CSS for the palette
            let css = generatePaletteCSS(paletteData);

            // Create style element and inject CSS
            const styleElement = $(`<style id="simple-vc-custom-styles">${css}</style>`);
            $('head').append(styleElement);

            console.log('✅ Palette CSS applied to page');

            // Show visual feedback
            $('body').addClass('simple-vc-palette-applied');
            setTimeout(() => {
                $('body').removeClass('simple-vc-palette-applied');
            }, 1000);

        } catch (error) {
            console.error('❌ Error applying palette CSS:', error);
            showMessage('Error applying visual changes: ' + error.message, 'error');
        }
    }

    /**
     * Generate CSS from Palette Data
     */
    function generatePaletteCSS(paletteData) {
        if (!paletteData.colors) {
            console.warn('⚠️ No colors in palette data, using SemanticColorSystem fallback');
            return generateFallbackCSS(paletteData.id);
        }

        let css = `
        /* Simple Visual Customizer - Applied Palette: ${paletteData.name} */
        :root {
        `;

        // Generate CSS custom properties from palette colors
        Object.entries(paletteData.colors).forEach(([role, colorData]) => {
            const colorValue = colorData.hex || colorData;
            css += `    --color-${role}: ${colorValue};\n`;
            css += `    --color-${role}-rgb: ${hexToRgb(colorValue)};\n`;
        });

        css += `
        }

        /* ===== MEDICAL SPA THEME INTEGRATION ===== */

        /* Professional Header (Transparent/Fixed Navigation) */
        .professional-header {
            background-color: var(--color-primary, ${paletteData.colors.primary?.hex || '#87A96B'}) !important;
        }

        .professional-header.scrolled {
            background: rgba(${hexToRgb(paletteData.colors.primary?.hex || '#87A96B')}, 0.95) !important;
        }

        .professional-header .nav-link,
        .professional-header .logo-fallback {
            color: var(--color-surface, ${paletteData.colors.surface?.hex || '#FFFFFF'}) !important;
        }

        /* Buttons - Medical Spa Theme */
        .btn-primary, .button-primary,
        .hero-discovery-btn,
        .consultation-booking-btn {
            background-color: var(--color-primary, ${paletteData.colors.primary?.hex || '#87A96B'}) !important;
            border-color: var(--color-primary, ${paletteData.colors.primary?.hex || '#87A96B'}) !important;
            color: var(--color-surface, ${paletteData.colors.surface?.hex || '#FFFFFF'}) !important;
        }

        .btn-secondary, .button-secondary {
            background-color: var(--color-secondary, ${paletteData.colors.secondary?.hex || '#1B365D'}) !important;
            border-color: var(--color-secondary, ${paletteData.colors.secondary?.hex || '#1B365D'}) !important;
            color: var(--color-surface, ${paletteData.colors.surface?.hex || '#FFFFFF'}) !important;
        }

        /* Hero Sections - Medical Spa */
        .treatments-hero-luxury,
        .hero-content-luxury,
        .premium-hero {
            background: linear-gradient(
                135deg,
                var(--color-secondary, ${paletteData.colors.secondary?.hex || '#1B365D'}),
                var(--color-primary, ${paletteData.colors.primary?.hex || '#87A96B'})
            ) !important;
        }

        .hero-title-main,
        .premium-hero .hero-title {
            color: var(--color-surface, ${paletteData.colors.surface?.hex || '#FFFFFF'}) !important;
        }

        .hero-title-accent {
            color: var(--color-accent, ${paletteData.colors.accent?.hex || '#D4AF37'}) !important;
        }

        /* Treatment Cards - Modern Cards */
        .modern-card {
            border-color: var(--color-primary, ${paletteData.colors.primary?.hex || '#87A96B'}) !important;
        }

        .modern-card:hover {
            border-color: var(--color-accent, ${paletteData.colors.accent?.hex || '#D4AF37'}) !important;
            box-shadow: 0 8px 25px rgba(${hexToRgb(paletteData.colors.primary?.hex || '#87A96B')}, 0.3) !important;
        }

        .artistry-card:hover,
        .category-card:hover {
            border-color: var(--color-accent, ${paletteData.colors.accent?.hex || '#D4AF37'}) !important;
        }

        /* Section Backgrounds */
        .bg-primary,
        .treatment-artistry-discovery {
            background: linear-gradient(
                135deg,
                var(--color-primary, ${paletteData.colors.primary?.hex || '#87A96B'}),
                var(--color-secondary, ${paletteData.colors.secondary?.hex || '#1B365D'})
            ) !important;
            color: var(--color-surface, ${paletteData.colors.surface?.hex || '#FFFFFF'}) !important;
        }

        .modern-section {
            background-color: var(--color-background, ${paletteData.colors.background?.hex || '#FDFCFA'}) !important;
        }

        /* Typography Colors */
        .section-title,
        .artistry-title {
            color: var(--color-secondary, ${paletteData.colors.secondary?.hex || '#1B365D'}) !important;
        }

        .text-primary {
            color: var(--color-primary, ${paletteData.colors.primary?.hex || '#87A96B'}) !important;
        }

        /* Footer Styling */
        .site-footer,
        .luxury-footer {
            background-color: var(--color-secondary, ${paletteData.colors.secondary?.hex || '#1B365D'}) !important;
            color: var(--color-surface, ${paletteData.colors.surface?.hex || '#FFFFFF'}) !important;
        }

        /* Consultation/CTA Elements */
        .consultation-cta,
        .discovery-invitation {
            background: linear-gradient(
                135deg,
                var(--color-accent, ${paletteData.colors.accent?.hex || '#D4AF37'}),
                var(--color-primary, ${paletteData.colors.primary?.hex || '#87A96B'})
            ) !important;
        }

        /* Interactive Elements */
        .treatment-selection-interface {
            border-color: var(--color-primary, ${paletteData.colors.primary?.hex || '#87A96B'}) !important;
        }

        .category-btn.active,
        .treatment-btn.active {
            background-color: var(--color-primary, ${paletteData.colors.primary?.hex || '#87A96B'}) !important;
            color: var(--color-surface, ${paletteData.colors.surface?.hex || '#FFFFFF'}) !important;
        }

        /* Visual feedback for applied changes */
        body.simple-vc-palette-applied {
            transition: all 0.3s ease;
        }
        `;

        return css;
    }

    /**
     * Generate Fallback CSS for Basic Palettes
     */
    function generateFallbackCSS(paletteId) {
        const fallbackPalettes = {
            'medical-clean': {
                primary: '#87A96B',
                secondary: '#1B365D',
                accent: '#D4AF37',
                surface: '#FFFFFF',
                background: '#FDFCFA'
            },
            'luxury-spa': {
                primary: '#D4AF37',
                secondary: '#8B4513',
                accent: '#87A96B',
                surface: '#FFF8DC',
                background: '#FFFACD'
            },
            'professional': {
                primary: '#1B365D',
                secondary: '#87A96B',
                accent: '#D4AF37',
                surface: '#FFFFFF',
                background: '#F8F9FA'
            }
        };

        const colors = fallbackPalettes[paletteId] || fallbackPalettes['medical-clean'];

        return generatePaletteCSS({
            id: paletteId,
            name: paletteId,
            colors: Object.fromEntries(
                Object.entries(colors).map(([role, hex]) => [role, { hex }])
            )
        });
    }

    /**
     * Convert hex to RGB string
     */
    function hexToRgb(hex) {
        const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        if (!result) return '0, 0, 0';

        const r = parseInt(result[1], 16);
        const g = parseInt(result[2], 16);
        const b = parseInt(result[3], 16);
        return `${r}, ${g}, ${b}`;
    }

    /**
     * Reset Changes
     */
    function resetChanges() {
        if (!confirm('Are you sure you want to reset all customizations to defaults?')) {
            return;
        }

        const $button = $('#simple-vc-reset');
        $button.prop('disabled', true).text('Resetting...');

        $.ajax({
            url: simpleCustomizer.ajaxUrl,
            type: 'POST',
            data: {
                action: 'simple_visual_customizer_reset',
                nonce: simpleCustomizer.nonce
            },
            success: function(response) {
                if (response.success) {
                    showMessage(response.data.message, 'success');
                    currentConfig = {};

                    // Reset interface
                    if (colorPaletteInterface && colorPaletteInterface.reset) {
                        colorPaletteInterface.reset();
                    }

                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                } else {
                    showMessage(response.data || 'Failed to reset changes.', 'error');
                }
            },
            error: function() {
                showMessage('Network error. Please try again.', 'error');
            },
            complete: function() {
                $button.prop('disabled', false).text('Reset');
            }
        });
    }

    /**
     * Show Message
     */
    function showMessage(message, type) {
        const messageHtml = `<div class="simple-vc-message ${type}">${message}</div>`;

        // Remove existing messages
        $('.simple-vc-message').remove();

        // Add new message
        $('.simple-vc-content').prepend(messageHtml);

        // Auto-remove after 5 seconds
        setTimeout(function() {
            $('.simple-vc-message').fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
    }

    /**
     * Initialize when document is ready
     */
    $(document).ready(function() {
        // Only initialize for admin users
        if (simpleCustomizer.isAdmin) {
            initSimpleVisualCustomizer();
        }
    });

})(jQuery);
