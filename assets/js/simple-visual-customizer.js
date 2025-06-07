/**
 * Simple Visual Customizer JavaScript - PVC-005 Enhanced
 * Clean implementation for admin bar triggered sidebar with Live Preview System integration
 */

(function($) {
    'use strict';

    let colorPaletteInterface = null;
    let currentConfig = {};
    let livePreviewSystem = null;
    let previewMessenger = null;
    let wpCustomizerBridge = null;

    /**
     * Initialize Simple Visual Customizer - PVC-005 Enhanced
     */
    function initSimpleVisualCustomizer() {
        console.log('🚀 Simple Visual Customizer: Initializing with PVC-005 Live Preview...');

        // Initialize PVC-005 components first
        initPVC005Components();

        // CRITICAL FIX: Make LivePreviewSystem globally available
        if (livePreviewSystem) {
            window.livePreviewSystem = livePreviewSystem;
            console.log('✅ LivePreviewSystem made globally available');
        }

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

        // PVC-005: Setup real-time preview event handlers
        setupLivePreviewEventHandlers();

        console.log('✅ Simple Visual Customizer: Ready with Live Preview');
    }

    /**
     * PVC-005: Initialize Live Preview System components with enhanced approach
     */
    function initPVC005Components() {
        try {
            // Initialize Live Preview System
            if (typeof LivePreviewSystem !== 'undefined') {
                livePreviewSystem = new LivePreviewSystem({
                    debug: simpleCustomizer.debug || false,
                    updateDelay: 50 // < 100ms requirement
                });
                console.log('✅ LivePreviewSystem initialized with Enhanced CSS Generation');
            } else {
                console.warn('⚠️ LivePreviewSystem not available');
            }

            // Initialize Preview Messenger
            if (typeof PreviewMessenger !== 'undefined') {
                previewMessenger = new PreviewMessenger({
                    debug: simpleCustomizer.debug || false
                });

                // Setup handlers if live preview is available
                if (livePreviewSystem) {
                    previewMessenger.setupPreviewHandlers(livePreviewSystem);
                }
                console.log('✅ PreviewMessenger initialized');
            }

            // Initialize WordPress Customizer Bridge
            if (typeof WordPressCustomizerBridge !== 'undefined') {
                wpCustomizerBridge = new WordPressCustomizerBridge({
                    debug: simpleCustomizer.debug || false
                });
                console.log('✅ WordPressCustomizerBridge initialized');
            }

        } catch (error) {
            console.error('❌ Error initializing PVC-005 components:', error);
        }
    }

    /**
     * PVC-005: Setup Live Preview event handlers
     */
    function setupLivePreviewEventHandlers() {
        console.log('🔧 Setting up Live Preview event handlers...');

        // Listen for palette selection events
        document.addEventListener('paletteInterface:paletteSelected', function(event) {
            const { paletteId, palette } = event.detail;
            console.log('🎨 Palette selected:', paletteId);
            console.log('📊 Palette data:', palette);

            // Store current config
            currentConfig = {
                activePalette: paletteId,
                paletteData: palette,
                timestamp: performance.now()
            };

            console.log('💾 Current config updated:', currentConfig);

            // Apply immediately via Live Preview System OR Direct Token Application
            if (livePreviewSystem && palette) {
                console.log('🚀 Applying palette via Live Preview System...');
                livePreviewSystem.applyPalette(palette).then(() => {
                    console.log('✅ Live Preview System: Palette applied successfully');
                    showMessage('Palette applied! Changes are visible in real-time.', 'success');
                }).catch(error => {
                    console.error('❌ Live Preview System error:', error);
                    showMessage('Error applying palette: ' + error.message, 'error');
                });
            } else if (palette) {
                console.warn('⚠️ Live Preview System not available, using direct token application...');
                console.log('🎯 Applying palette directly via Design Tokens:', palette);

                // CRITICAL FIX: Apply directly via Design Token System when LivePreviewSystem is not available
                applyColorTokensImmediately(palette);
            } else {
                console.error('❌ No palette data available for application');
                console.log('Live Preview System:', livePreviewSystem);
                console.log('Palette Data:', palette);
                showMessage('No palette data available', 'error');
            }

            // Update apply button state
            updateApplyButton();
        });

        // Listen for live preview events
        document.addEventListener('preview:paletteApplied', function(event) {
            const { palette, duration, performance } = event.detail;
            console.log(`✅ PALETTE APPLIED in ${duration.toFixed(2)}ms`, performance);
            console.log('🎨 Applied palette details:', palette);

            // Update UI feedback
            showPerformanceMetrics(duration, performance);
        });

        document.addEventListener('preview:error', function(event) {
            console.error('❌ Live Preview Error:', event.detail);
            showMessage('Preview error: ' + event.detail.message, 'error');
        });

        console.log('✅ Live Preview event handlers set up');
    }

    /**
     * Open sidebar with enhanced loading
     */
    function openSidebar() {
        console.log('📂 Opening Simple Visual Customizer sidebar...');

        // Create overlay
        if (!$('#simple-vc-overlay').length) {
            $('body').append('<div id="simple-vc-overlay" class="simple-vc-overlay"></div>');
        }

        // Create sidebar if it doesn't exist
        if (!$('#simple-vc-sidebar').length) {
            createSidebar();
        }

        // Show overlay and sidebar
        $('#simple-vc-overlay').fadeIn(200);
        $('#simple-vc-sidebar').addClass('open');

        // Load color palette interface
        loadColorPaletteInterface();

        // Load typography interface
        loadTypographyInterface();

        // CRITICAL FIX: Load and highlight current palette after interface loads
        setTimeout(() => {
            loadAndHighlightCurrentPalette();
        }, 1000);

        // Enable live preview mode
        if (livePreviewSystem) {
            livePreviewSystem.enablePreviewMode();
            console.log('✅ Live Preview mode enabled');
        } else {
            console.warn('⚠️ LivePreviewSystem not available when opening sidebar');
        }
    }

    /**
     * Close sidebar with proper cleanup
     */
    function closeSidebar() {
        $('#simple-vc-sidebar').removeClass('open');
        $('#simple-vc-overlay').fadeOut(200);

        // Disable live preview mode
        if (livePreviewSystem) {
            livePreviewSystem.disablePreviewMode();
        }

        console.log('📁 Simple Visual Customizer sidebar closed');
    }

    /**
     * Create sidebar HTML
     */
    function createSidebar() {
        const sidebarHtml = `
            <div id="simple-vc-sidebar" class="simple-vc-sidebar">
                <div class="simple-vc-header">
                    <h3>🎨 Visual Customizer</h3>
                    <button id="simple-vc-close" class="simple-vc-close" title="Close">×</button>
                </div>
                <div class="simple-vc-content">
                    <!-- Sprint 2 Extension Version Indicator -->
                    <div class="sprint-version-indicator">
                        <div class="version-header">
                            <div class="version-badge">
                                <span class="version-icon">🚀</span>
                                <div class="version-info">
                                    <div class="version-title">Sprint 2 Extension ACTIVE</div>
                                    <div class="version-subtitle">Design Token System v1.0.0 (Slug: dog)</div>
                                </div>
                            </div>
                            <div class="version-timestamp">
                                Updated: ${new Date().toLocaleDateString()}
                            </div>
                        </div>
                        <div class="version-features">
                            <span class="feature-tag">🎯 Design Tokens</span>
                            <span class="feature-tag">⚡ Live Preview</span>
                            <span class="feature-tag">🎨 Visual Interface</span>
                        </div>
                    </div>

                    <!-- Smartphone Launcher Style Menu -->
                    <div class="launcher-menu">
                        <div class="launcher-grid">
                            <div class="launcher-icon active" data-section="colors" title="Color Palettes">
                                <div class="icon-bg">🎨</div>
                                <span class="icon-label">Colors</span>
                            </div>
                            <div class="launcher-icon" data-section="typography" title="Typography">
                                <div class="icon-bg">📝</div>
                                <span class="icon-label">Typography</span>
                            </div>
                            <div class="launcher-icon" data-section="layout" title="Layout & Spacing">
                                <div class="icon-bg">📐</div>
                                <span class="icon-label">Layout</span>
                            </div>
                            <div class="launcher-icon" data-section="effects" title="Visual Effects">
                                <div class="icon-bg">✨</div>
                                <span class="icon-label">Effects</span>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Content Sections -->
                    <div class="customizer-sections">
                        <!-- Colors Section -->
                        <div class="customizer-section active" id="section-colors">
                            <div class="section-header">
                                <h4>🎨 Color Palettes</h4>
                                <p>Professional medical spa color schemes</p>
                            </div>
                            <div id="simple-color-palette-container" class="simple-color-palette-container">
                                <p>Loading color palettes...</p>
                            </div>
                        </div>

                        <!-- Typography Section -->
                        <div class="customizer-section" id="section-typography">
                            <div class="section-header">
                                <h4>📝 Typography</h4>
                                <p>Professional font pairings with live preview</p>
                            </div>
                            <div id="simple-typography-container" class="simple-typography-container">
                                <p>Loading typography options...</p>
                            </div>
                        </div>

                        <!-- Layout Section -->
                        <div class="customizer-section" id="section-layout">
                            <div class="section-header">
                                <h4>📐 Layout & Spacing</h4>
                                <p>Adjust spacing, margins, and layout properties</p>
                            </div>
                            <div id="simple-layout-container" class="simple-layout-container">
                                <div class="coming-soon">
                                    <div class="coming-soon-icon">🚧</div>
                                    <h5>Coming Soon</h5>
                                    <p>Layout customization tools are being developed</p>
                                </div>
                            </div>
                        </div>

                        <!-- Effects Section -->
                        <div class="customizer-section" id="section-effects">
                            <div class="section-header">
                                <h4>✨ Visual Effects</h4>
                                <p>Shadows, borders, animations, and visual enhancements</p>
                            </div>
                            <div id="simple-effects-container" class="simple-effects-container">
                                <div class="coming-soon">
                                    <div class="coming-soon-icon">🎭</div>
                                    <h5>Coming Soon</h5>
                                    <p>Visual effects customization tools are being developed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Live Preview Status -->
                    <div class="simple-vc-section">
                        <div id="simple-vc-status" class="simple-vc-status">
                            <span class="status-indicator">⚡ Design Token System Active</span>
                            <div id="performance-metrics" class="performance-metrics"></div>
                        </div>
                    </div>
                </div>
                <div class="simple-vc-footer">
                    <button id="simple-vc-reset" class="simple-vc-btn simple-vc-btn-secondary">Reset</button>
                    <button id="simple-vc-apply" class="simple-vc-btn simple-vc-btn-primary" disabled>Apply Globally</button>
                </div>
            </div>
        `;

        $('body').append(sidebarHtml);

        // Setup launcher menu interactions
        setupLauncherMenu();
    }

    /**
     * Load Color Palette Interface - PVC-005 Enhanced
     */
    function loadColorPaletteInterface() {
        const container = $('#simple-color-palette-container');

        // Check if all required classes are available
        if (typeof ColorPaletteInterface !== 'undefined' &&
            typeof ColorSystemManager !== 'undefined' &&
            typeof SemanticColorSystem !== 'undefined') {

            try {
                console.log('🎨 Initializing full ColorPaletteInterface with Live Preview...');
                container.html('<p>Initializing color palette interface...</p>');

                // Initialize ColorSystemManager first
                const colorSystemManager = new ColorSystemManager({ autoInit: false });

                // Create ColorPaletteInterface with enhanced config for sidebar
                colorPaletteInterface = new ColorPaletteInterface('simple-color-palette-container', {
                    colorSystemManager: colorSystemManager,
                    enablePreview: true, // PVC-005: Enable live preview
                    showAccessibilityInfo: true,
                    enableSearch: true,
                    compact: true, // For narrow sidebar
                    previewDelay: 50, // PVC-005: < 100ms requirement
                    onPaletteSelect: function(paletteId, paletteData) {
                        // This will trigger the event listener above
                        console.log('🎨 Palette selected via interface:', paletteId);
                    }
                });

                // Initialize
                colorSystemManager.initialize().then(() => {
                    return colorPaletteInterface.initialize();
                }).then(() => {
                    console.log('✅ ColorPaletteInterface ready with live preview');

                    // Setup real-time preview integration
                    setupColorPaletteIntegration();

                }).catch(error => {
                    console.error('❌ Error initializing ColorPaletteInterface:', error);
                    loadFallbackInterface();
                });

            } catch (error) {
                console.error('❌ Error setting up ColorPaletteInterface:', error);
                loadFallbackInterface();
            }
        } else {
            console.warn('⚠️ ColorPaletteInterface components not available, loading fallback');
            loadFallbackInterface();
        }
    }

    /**
     * PVC-005: Setup Color Palette Interface integration
     */
    function setupColorPaletteIntegration() {
        // Enhanced palette selection handling
        $(document).on('click', '.palette-card', function() {
            const paletteId = $(this).data('palette-id');
            const paletteData = $(this).data('palette-data');

            if (paletteId && paletteData) {
                // Immediate visual feedback
                $('.palette-card').removeClass('selected');
                $(this).addClass('selected');

                // Dispatch enhanced event with palette data
                document.dispatchEvent(new CustomEvent('paletteInterface:paletteSelected', {
                    detail: { paletteId, paletteData }
                }));
            }
        });

        // Performance monitoring display
        document.addEventListener('preview:paletteApplied', function(event) {
            updatePerformanceDisplay(event.detail);
        });
    }

    /**
     * Load fallback interface with live preview support
     */
    function loadFallbackInterface() {
        const container = $('#simple-color-palette-container');

        if (typeof SemanticColorSystem !== 'undefined') {
            try {
                console.log('🎨 Loading fallback interface with SemanticColorSystem...');
                const colorSystem = new SemanticColorSystem();
                const allPalettes = colorSystem.getAllPalettes();

                let html = `<div class="palette-grid-simple">`;

                allPalettes.forEach(palette => {
                    const truncatedDesc = palette.description ?
                        (palette.description.length > 50 ?
                            palette.description.substring(0, 50) + '...' :
                            palette.description) : '';

                    console.log(`🎨 Adding palette to interface: ${palette.id}`, palette);

                    html += `
                        <div class="palette-item-simple" data-palette="${palette.id}" data-palette-data='${JSON.stringify(palette)}'>
                            <div class="palette-header">
                                <span class="palette-icon">${palette.icon || '🎨'}</span>
                                <strong>${palette.name}</strong>
                            </div>
                            <div class="palette-description">${truncatedDesc}</div>
                            <div class="palette-colors">
                                ${Object.entries(palette.colors).slice(0, 4).map(([role, color]) =>
                                    `<span class="color-swatch" style="background-color: ${color.hex || color}" title="${role}"></span>`
                                ).join('')}
                            </div>
                        </div>
                    `;
                });

                html += `</div>`;
                container.html(html);

                // Setup enhanced fallback handlers
                setupFallbackHandlers(colorSystem);

                console.log('✅ Fallback interface loaded with live preview support');

            } catch (error) {
                console.error('❌ Error loading SemanticColorSystem fallback:', error);
                loadBasicFallback();
            }
        } else {
            loadBasicFallback();
        }
    }

    /**
     * Setup enhanced fallback handlers with live preview
     */
    function setupFallbackHandlers(colorSystem) {
        console.log('🔧 Setting up fallback handlers...');

        $(document).on('click', '.palette-item-simple', function() {
            console.log('🖱️ Fallback palette item clicked');

            const paletteId = $(this).data('palette');
            const paletteData = $(this).data('palette-data');

            console.log('📋 Palette ID:', paletteId);
            console.log('📋 Palette Data:', paletteData);
            console.log('📋 Raw Data Attributes:', {
                palette: $(this).attr('data-palette'),
                paletteData: $(this).attr('data-palette-data')
            });

            $('.palette-item-simple').removeClass('active');
            $(this).addClass('active');

            try {
                // Get full palette data if not embedded
                const fullPaletteData = paletteData || colorSystem.getPalette(paletteId);
                console.log('📋 Full Palette Data:', fullPaletteData);

                if (!fullPaletteData) {
                    console.error('❌ No palette data available for:', paletteId);
                    showMessage('No palette data found for: ' + paletteId, 'error');
                    return;
                }

                if (!fullPaletteData.colors) {
                    console.error('❌ Palette data has no colors:', fullPaletteData);
                    showMessage('Palette has no color data', 'error');
                    return;
                }

                currentConfig = {
                    activePalette: paletteId,
                    paletteData: fullPaletteData,
                    timestamp: performance.now()
                };

                console.log('🎨 Palette selected:', paletteId);
                console.log('🎨 About to apply color tokens with data:', fullPaletteData.colors);

                // CRITICAL FIX: Apply Design Tokens immediately instead of complex CSS injection
                applyColorTokensImmediately(fullPaletteData);

                // Show color preview
                showColorPreview(fullPaletteData);
                updateApplyButton();

            } catch (error) {
                console.error('❌ Error handling fallback palette selection:', error);
                showMessage('Error selecting palette: ' + error.message, 'error');
            }
        });

        console.log('✅ Fallback handlers set up');
    }

    /**
     * Apply Color Tokens Immediately - Design Token System Integration
     */
    function applyColorTokensImmediately(paletteData) {
        console.log('🔥 applyColorTokensImmediately called with:', paletteData);

        if (!paletteData || !paletteData.colors) {
            console.error('❌ No palette colors for token application');
            console.log('❌ paletteData:', paletteData);
            showMessage('No color data available', 'error');
            return;
        }

        console.log('🎯 Applying color tokens immediately:', paletteData);
        console.log('🎯 Available colors:', Object.keys(paletteData.colors));

        try {
            const startTime = performance.now();

            // UNIFIED APPROACH: Generate both CSS variables AND CSS class rules
            console.log('🔧 Generating unified CSS system...');

            // Generate CSS variables (for themes that use them)
            const colorTokens = generateColorTokens(paletteData.colors);
            console.log('🎯 Generated CSS variables:', Object.keys(colorTokens).length);

            // NEW: Generate CSS class rules (for themes that use traditional classes)
            const cssRules = generateCSSClassRules(paletteData.colors);
            console.log('🎯 Generated CSS class rules:', cssRules.length);

            // Apply CSS variables to document root
            console.log('🔧 Applying CSS variables to document root...');
            const documentRoot = document.documentElement;
            let appliedTokens = 0;

            Object.entries(colorTokens).forEach(([property, value]) => {
                documentRoot.style.setProperty(property, value);
                appliedTokens++;
            });

            // NEW: Inject CSS class rules into a dynamic stylesheet
            console.log('🔧 Injecting CSS class rules...');
            const appliedRules = injectCSSRules(cssRules, paletteData.id);

            // Force immediate DOM update
            documentRoot.offsetHeight;
            console.log('🔄 Forced DOM reflow');

            // Apply visual feedback
            document.body.classList.add('palette-transition');
            setTimeout(() => {
                document.body.classList.remove('palette-transition');
            }, 300);

            const duration = performance.now() - startTime;
            console.log(`✅ Unified CSS system applied in ${duration.toFixed(2)}ms`);
            console.log(`✅ Applied ${appliedTokens} CSS variables + ${appliedRules} CSS rules`);

            showMessage(`Colors applied via Unified CSS System! (${appliedTokens} vars + ${appliedRules} rules in ${duration.toFixed(0)}ms)`, 'success');

            // DEBUGGING: Inspect actual button CSS variables being used
            inspectButtonCSSVariables();

        } catch (error) {
            console.error('❌ Error applying unified CSS system:', error);
            showMessage('Error applying colors: ' + error.message, 'error');
        }
    }

    /**
     * Generate CSS Class Rules - Unified approach for client and server
     */
    function generateCSSClassRules(colors) {
        console.log('🎨 generateCSSClassRules called with colors:', colors);
        const rules = [];

        const primaryColor = colors.primary && (colors.primary.hex || colors.primary);
        const secondaryColor = colors.secondary && (colors.secondary.hex || colors.secondary);
        const accentColor = colors.accent && (colors.accent.hex || colors.accent);
        const surfaceColor = colors.surface && (colors.surface.hex || colors.surface);
        const backgroundColor = colors.background && (colors.background.hex || colors.background);

        if (primaryColor) {
            console.log('🎨 Generating primary button rules for:', primaryColor);
            const primaryDark = darkenColor(primaryColor, 15);
            const primaryLight = lightenColor(primaryColor, 10);

            // Primary button rules
            rules.push(`
                .btn-primary,
                .button-primary,
                .cta-primary,
                .btn.btn-primary,
                .action-btn-primary,
                button.btn-primary,
                input[type="submit"].btn-primary {
                    background-color: ${primaryColor} !important;
                    border-color: ${primaryColor} !important;
                    color: ${surfaceColor || '#ffffff'} !important;
                }
            `);

            rules.push(`
                .btn-primary:hover,
                .button-primary:hover,
                .cta-primary:hover,
                .btn.btn-primary:hover,
                .action-btn-primary:hover,
                button.btn-primary:hover,
                input[type="submit"].btn-primary:hover {
                    background-color: ${primaryDark} !important;
                    border-color: ${primaryDark} !important;
                    color: ${surfaceColor || '#ffffff'} !important;
                }
            `);

            // Outline primary buttons
            rules.push(`
                .btn-outline-primary,
                .button-outline-primary,
                .btn.btn-outline-primary {
                    background-color: transparent !important;
                    border-color: ${primaryColor} !important;
                    color: ${primaryColor} !important;
                }
            `);

            rules.push(`
                .btn-outline-primary:hover,
                .button-outline-primary:hover,
                .btn.btn-outline-primary:hover {
                    background-color: ${primaryColor} !important;
                    border-color: ${primaryColor} !important;
                    color: ${surfaceColor || '#ffffff'} !important;
                }
            `);
        }

        if (secondaryColor) {
            console.log('🎨 Generating secondary button rules for:', secondaryColor);
            const secondaryDark = darkenColor(secondaryColor, 15);

            // Secondary button rules
            rules.push(`
                .btn-secondary,
                .button-secondary,
                .cta-secondary,
                .btn.btn-secondary,
                .action-btn-secondary,
                button.btn-secondary {
                    background-color: ${secondaryColor} !important;
                    border-color: ${secondaryColor} !important;
                    color: ${surfaceColor || '#ffffff'} !important;
                }
            `);

            rules.push(`
                .btn-secondary:hover,
                .button-secondary:hover,
                .cta-secondary:hover,
                .btn.btn-secondary:hover,
                .action-btn-secondary:hover,
                button.btn-secondary:hover {
                    background-color: ${secondaryDark} !important;
                    border-color: ${secondaryDark} !important;
                    color: ${surfaceColor || '#ffffff'} !important;
                }
            `);
        }

        if (accentColor) {
            console.log('🎨 Generating accent button rules for:', accentColor);
            const accentDark = darkenColor(accentColor, 15);

            // Accent/Tertiary button rules
            rules.push(`
                .btn-accent,
                .btn-tertiary,
                .button-accent,
                .button-tertiary,
                .cta-accent,
                .btn.btn-accent,
                .btn.btn-tertiary {
                    background-color: ${accentColor} !important;
                    border-color: ${accentColor} !important;
                    color: ${surfaceColor || '#ffffff'} !important;
                }
            `);

            rules.push(`
                .btn-accent:hover,
                .btn-tertiary:hover,
                .button-accent:hover,
                .button-tertiary:hover,
                .cta-accent:hover,
                .btn.btn-accent:hover,
                .btn.btn-tertiary:hover {
                    background-color: ${accentDark} !important;
                    border-color: ${accentDark} !important;
                    color: ${surfaceColor || '#ffffff'} !important;
                }
            `);
        }

        // General text and background rules
        if (primaryColor) {
            rules.push(`
                .text-primary,
                .primary-text,
                h1, h2, h3, h4, h5, h6 {
                    color: ${primaryColor} !important;
                }
            `);

            rules.push(`
                .bg-primary,
                .primary-bg,
                .header,
                .navbar,
                .professional-header {
                    background-color: ${primaryColor} !important;
                }
            `);
        }

        if (secondaryColor) {
            rules.push(`
                .text-secondary,
                .secondary-text {
                    color: ${secondaryColor} !important;
                }
            `);
        }

        if (backgroundColor) {
            rules.push(`
                body,
                .main-content,
                .content-area {
                    background-color: ${backgroundColor} !important;
                }
            `);
        }

        if (surfaceColor) {
            rules.push(`
                .card,
                .panel,
                .treatment-card,
                .service-card {
                    background-color: ${surfaceColor} !important;
                }
            `);
        }

        console.log(`🎨 Generated ${rules.length} CSS class rules`);
        return rules;
    }

    /**
     * Inject CSS Rules into Dynamic Stylesheet
     */
    function injectCSSRules(rules, paletteId) {
        console.log('💉 Injecting CSS rules into dynamic stylesheet...');

        // Remove previous dynamic palette stylesheet if it exists
        const existingStyle = document.getElementById('dynamic-palette-styles');
        if (existingStyle) {
            existingStyle.remove();
            console.log('🗑️ Removed previous dynamic stylesheet');
        }

        // Create new dynamic stylesheet
        const styleElement = document.createElement('style');
        styleElement.id = 'dynamic-palette-styles';
        styleElement.setAttribute('data-palette', paletteId);

        // Combine all rules into one stylesheet
        const combinedCSS = rules.join('\n\n');
        styleElement.textContent = combinedCSS;

        // Inject into document head
        document.head.appendChild(styleElement);

        console.log('💉 Injected dynamic stylesheet with', rules.length, 'rules');
        console.log('💉 Stylesheet content preview:', combinedCSS.substring(0, 200) + '...');

        return rules.length;
    }

    /**
     * Inspect what CSS variables are actually being used by buttons on the page
     */
    function inspectButtonCSSVariables() {
        console.log('🔍 Inspecting actual button CSS variables used on page...');

        // Find action buttons, CTA buttons, and other button elements
        const buttonSelectors = [
            'button', '.btn', '.button', '.cta', '.action-btn', '.action-button',
            '[class*="btn"]', '[class*="button"]', '[class*="cta"]', '[class*="action"]',
            'input[type="submit"]', 'input[type="button"]', '.wp-block-button'
        ];

        buttonSelectors.forEach(selector => {
            const elements = document.querySelectorAll(selector);
            if (elements.length > 0) {
                console.log(`🔍 Found ${elements.length} elements with selector: ${selector}`);

                elements.forEach((element, index) => {
                    if (index < 3) { // Only inspect first 3 of each type
                        const computedStyle = window.getComputedStyle(element);
                        const backgroundColor = computedStyle.backgroundColor;
                        const borderColor = computedStyle.borderColor;
                        const color = computedStyle.color;

                        console.log(`  Element ${index + 1}:`, {
                            className: element.className,
                            backgroundColor,
                            borderColor,
                            color
                        });

                        // Try to detect CSS custom properties
                        const cssText = computedStyle.cssText;
                        if (cssText.includes('var(')) {
                            console.log(`  CSS Variables detected in style:`, cssText.match(/var\([^)]+\)/g));
                        }
                    }
                });
            }
        });

        // Also check document.documentElement for all CSS custom properties
        const rootStyles = window.getComputedStyle(document.documentElement);
        const allCustomProps = [];

        for (let i = 0; i < rootStyles.length; i++) {
            const prop = rootStyles[i];
            if (prop.startsWith('--') && (prop.includes('btn') || prop.includes('button') || prop.includes('action') || prop.includes('cta'))) {
                const value = rootStyles.getPropertyValue(prop);
                allCustomProps.push(`${prop}: ${value}`);
            }
        }

        if (allCustomProps.length > 0) {
            console.log('🔍 Button-related CSS custom properties found on document root:');
            allCustomProps.forEach(prop => console.log(`  ${prop}`));
        } else {
            console.log('🔍 No button-related CSS custom properties found on document root');
        }
    }

    /**
     * Generate Color Tokens from Palette Colors
     */
    function generateColorTokens(colors) {
        console.log('🔧 generateColorTokens called with colors:', colors);
        const tokens = {};

        // Map palette colors to design tokens
        Object.entries(colors).forEach(([colorName, colorData]) => {
            console.log(`🔍 Processing color: ${colorName}`, colorData);
            const colorValue = colorData.hex || colorData;
            console.log(`🔍 Extracted color value: ${colorValue}`);

            if (colorValue && isValidColor(colorValue)) {
                console.log(`✅ Valid color: ${colorName} = ${colorValue}`);
                // Primary token mapping
                tokens[`--color-${colorName}`] = colorValue;

                // Generate RGB variants for alpha usage
                const rgb = hexToRgb(colorValue);
                if (rgb) {
                    tokens[`--color-${colorName}-rgb`] = `${rgb.r}, ${rgb.g}, ${rgb.b}`;
                }

                // Generate variations
                tokens[`--color-${colorName}-light`] = lightenColor(colorValue, 20);
                tokens[`--color-${colorName}-dark`] = darkenColor(colorValue, 20);
            } else {
                console.warn(`❌ Invalid color: ${colorName} = ${colorValue}`);
            }
        });

        console.log('🔧 Basic tokens generated:', Object.keys(tokens).length);

        // Map to specific component tokens that exist in the theme
        if (colors.primary) {
            const primaryColor = colors.primary.hex || colors.primary;
            console.log('🎯 Mapping primary color:', primaryColor);

            // Core primary color variables
            tokens['--color-primary'] = primaryColor;
            tokens['--color-primary-navy'] = primaryColor;
            tokens['--color-primary-forest'] = primaryColor;
            tokens['--primary-color'] = primaryColor;
            tokens['--theme-primary'] = primaryColor;

            // Button primary variables
            tokens['--btn-primary-bg'] = primaryColor;
            tokens['--btn-primary-background'] = primaryColor;
            tokens['--button-primary-bg'] = primaryColor;
            tokens['--button-primary-background'] = primaryColor;
            tokens['--primary-button-bg'] = primaryColor;
            tokens['--primary-btn-background'] = primaryColor;

            // Action button specific variables
            tokens['--action-btn-bg'] = primaryColor;
            tokens['--action-button-bg'] = primaryColor;
            tokens['--action-btn-background'] = primaryColor;
            tokens['--action-button-background'] = primaryColor;
            tokens['--cta-btn-bg'] = primaryColor;
            tokens['--cta-button-bg'] = primaryColor;
            tokens['--cta-btn-background'] = primaryColor;
            tokens['--cta-button-background'] = primaryColor;

            // WordPress specific button variables
            tokens['--wp-btn-primary'] = primaryColor;
            tokens['--wp-button-primary'] = primaryColor;
            tokens['--wp-admin-color-primary'] = primaryColor;

            // Bootstrap/Framework button variables
            tokens['--bs-primary'] = primaryColor;
            tokens['--btn-primary'] = primaryColor;

            // Button hover states (darker version)
            const primaryDark = darkenColor(primaryColor, 15);
            tokens['--btn-primary-hover'] = primaryDark;
            tokens['--btn-primary-hover-bg'] = primaryDark;
            tokens['--button-primary-hover'] = primaryDark;
            tokens['--primary-button-hover'] = primaryDark;

            // Action button hover states
            tokens['--action-btn-hover'] = primaryDark;
            tokens['--action-button-hover'] = primaryDark;

            // Navigation and links
            tokens['--link-color'] = primaryColor;
            tokens['--link-primary'] = primaryColor;
            tokens['--nav-link-color'] = primaryColor;
            tokens['--nav-primary'] = primaryColor;
            tokens['--menu-link-color'] = primaryColor;

            // Header and navigation backgrounds
            tokens['--header-bg'] = primaryColor;
            tokens['--header-background'] = primaryColor;
            tokens['--navbar-bg'] = primaryColor;
            tokens['--navigation-bg'] = primaryColor;
            tokens['--menu-bg'] = primaryColor;

            // Border and accent colors
            tokens['--border-primary'] = primaryColor;
            tokens['--primary-border'] = primaryColor;
            tokens['--focus-color'] = primaryColor;
            tokens['--focus-ring'] = primaryColor;
        }

        if (colors.secondary) {
            const secondaryColor = colors.secondary.hex || colors.secondary;
            console.log('🎯 Mapping secondary color:', secondaryColor);

            // Core secondary color variables
            tokens['--color-secondary'] = secondaryColor;
            tokens['--color-primary-teal'] = secondaryColor;
            tokens['--color-primary-sage'] = secondaryColor;
            tokens['--secondary-color'] = secondaryColor;
            tokens['--theme-secondary'] = secondaryColor;

            // Button secondary variables
            tokens['--btn-secondary-bg'] = secondaryColor;
            tokens['--btn-secondary-background'] = secondaryColor;
            tokens['--button-secondary-bg'] = secondaryColor;
            tokens['--button-secondary-background'] = secondaryColor;
            tokens['--secondary-button-bg'] = secondaryColor;
            tokens['--secondary-btn-background'] = secondaryColor;

            // Button hover states
            const secondaryDark = darkenColor(secondaryColor, 15);
            tokens['--btn-secondary-hover'] = secondaryDark;
            tokens['--btn-secondary-hover-bg'] = secondaryDark;
            tokens['--button-secondary-hover'] = secondaryDark;
            tokens['--secondary-button-hover'] = secondaryDark;

            // Text colors
            tokens['--text-secondary'] = secondaryColor;
            tokens['--secondary-text'] = secondaryColor;
            tokens['--heading-color'] = secondaryColor;
            tokens['--heading-secondary'] = secondaryColor;
            tokens['--subtitle-color'] = secondaryColor;
        }

        if (colors.accent) {
            const accentColor = colors.accent.hex || colors.accent;
            console.log('🎯 Mapping accent color:', accentColor);

            // Core accent color variables
            tokens['--color-accent'] = accentColor;
            tokens['--color-secondary-peach'] = accentColor;
            tokens['--color-primary-gold'] = accentColor;
            tokens['--accent-color'] = accentColor;
            tokens['--theme-accent'] = accentColor;

            // Button accent/tertiary variables
            tokens['--btn-accent-bg'] = accentColor;
            tokens['--btn-tertiary-bg'] = accentColor;
            tokens['--button-accent-bg'] = accentColor;
            tokens['--button-tertiary-bg'] = accentColor;
            tokens['--accent-button-bg'] = accentColor;
            tokens['--tertiary-button-bg'] = accentColor;

            // Button hover states
            const accentDark = darkenColor(accentColor, 15);
            tokens['--btn-accent-hover'] = accentDark;
            tokens['--btn-tertiary-hover'] = accentDark;
            tokens['--button-accent-hover'] = accentDark;
            tokens['--accent-button-hover'] = accentDark;

            // Highlight and call-to-action
            tokens['--highlight-color'] = accentColor;
            tokens['--cta-color'] = accentColor;
            tokens['--cta-bg'] = accentColor;
            tokens['--call-to-action'] = accentColor;
        }

        if (colors.surface) {
            const surfaceColor = colors.surface.hex || colors.surface;
            console.log('🎯 Mapping surface color:', surfaceColor);

            // Core surface variables
            tokens['--color-surface'] = surfaceColor;
            tokens['--color-neutral-white'] = surfaceColor;
            tokens['--surface-color'] = surfaceColor;
            tokens['--theme-surface'] = surfaceColor;

            // Card and container backgrounds
            tokens['--card-bg'] = surfaceColor;
            tokens['--card-background'] = surfaceColor;
            tokens['--container-bg'] = surfaceColor;
            tokens['--section-bg'] = surfaceColor;
            tokens['--panel-bg'] = surfaceColor;
            tokens['--box-bg'] = surfaceColor;

            // Modal and overlay backgrounds
            tokens['--modal-bg'] = surfaceColor;
            tokens['--modal-background'] = surfaceColor;
            tokens['--overlay-bg'] = surfaceColor;
            tokens['--popup-bg'] = surfaceColor;

            // Input and form backgrounds
            tokens['--input-bg'] = surfaceColor;
            tokens['--input-background'] = surfaceColor;
            tokens['--form-bg'] = surfaceColor;
            tokens['--field-bg'] = surfaceColor;
        }

        if (colors.background) {
            const backgroundColor = colors.background.hex || colors.background;
            console.log('🎯 Mapping background color:', backgroundColor);

            // Core background variables
            tokens['--color-background'] = backgroundColor;
            tokens['--color-neutral-light'] = backgroundColor;
            tokens['--background-color'] = backgroundColor;
            tokens['--theme-background'] = backgroundColor;

            // Body and page backgrounds
            tokens['--body-bg'] = backgroundColor;
            tokens['--body-background'] = backgroundColor;
            tokens['--page-bg'] = backgroundColor;
            tokens['--site-bg'] = backgroundColor;
            tokens['--main-bg'] = backgroundColor;

            // Content area backgrounds
            tokens['--content-bg'] = backgroundColor;
            tokens['--wrapper-bg'] = backgroundColor;
            tokens['--container-background'] = backgroundColor;
        }

        // Add comprehensive button state variables for all color combinations
        console.log('🎯 Adding comprehensive button state variables...');

        // Outline button variants
        if (colors.primary) {
            const primaryColor = colors.primary.hex || colors.primary;
            tokens['--btn-outline-primary'] = primaryColor;
            tokens['--btn-outline-primary-border'] = primaryColor;
            tokens['--button-outline-primary'] = primaryColor;

            // More action button variants
            tokens['--action-btn-color'] = primaryColor;
            tokens['--action-button-color'] = primaryColor;
            tokens['--submit-btn-bg'] = primaryColor;
            tokens['--submit-button-bg'] = primaryColor;
            tokens['--form-submit-bg'] = primaryColor;

            // Theme-specific action buttons
            tokens['--theme-btn-primary'] = primaryColor;
            tokens['--theme-button-primary'] = primaryColor;
            tokens['--site-btn-primary'] = primaryColor;
            tokens['--custom-btn-primary'] = primaryColor;

            // Medical spa specific buttons
            tokens['--appointment-btn-bg'] = primaryColor;
            tokens['--booking-btn-bg'] = primaryColor;
            tokens['--contact-btn-bg'] = primaryColor;
            tokens['--consultation-btn-bg'] = primaryColor;
        }

        if (colors.secondary) {
            const secondaryColor = colors.secondary.hex || colors.secondary;
            tokens['--btn-outline-secondary'] = secondaryColor;
            tokens['--btn-outline-secondary-border'] = secondaryColor;
            tokens['--button-outline-secondary'] = secondaryColor;
        }

        if (colors.accent) {
            const accentColor = colors.accent.hex || colors.accent;
            tokens['--btn-outline-accent'] = accentColor;
            tokens['--btn-outline-tertiary'] = accentColor;
            tokens['--button-outline-accent'] = accentColor;
        }

        // Add theme-specific medical spa variables
        console.log('🎯 Adding medical spa theme-specific variables...');

        if (colors.primary) {
            const primaryColor = colors.primary.hex || colors.primary;
            tokens['--medical-primary'] = primaryColor;
            tokens['--spa-primary'] = primaryColor;
            tokens['--professional-color'] = primaryColor;
            tokens['--clinic-primary'] = primaryColor;
        }

        if (colors.secondary) {
            const secondaryColor = colors.secondary.hex || colors.secondary;
            tokens['--medical-secondary'] = secondaryColor;
            tokens['--spa-secondary'] = secondaryColor;
            tokens['--wellness-color'] = secondaryColor;
            tokens['--health-color'] = secondaryColor;
        }

        if (colors.accent) {
            const accentColor = colors.accent.hex || colors.accent;
            tokens['--medical-accent'] = accentColor;
            tokens['--spa-accent'] = accentColor;
            tokens['--luxury-color'] = accentColor;
            tokens['--premium-color'] = accentColor;
        }

        // Add comprehensive text color variables
        console.log('🎯 Adding comprehensive text color variables...');

        if (colors.primary) {
            const primaryColor = colors.primary.hex || colors.primary;
            tokens['--text-primary'] = primaryColor;
            tokens['--primary-text'] = primaryColor;
            tokens['--text-on-light'] = primaryColor;
            tokens['--dark-text'] = primaryColor;
        }

        if (colors.surface || colors.background) {
            const lightColor = (colors.surface && (colors.surface.hex || colors.surface)) ||
                              (colors.background && (colors.background.hex || colors.background)) || '#ffffff';
            tokens['--text-on-primary'] = lightColor;
            tokens['--text-on-dark'] = lightColor;
            tokens['--light-text'] = lightColor;
            tokens['--white-text'] = lightColor;
        }

        // Add border and shadow variables
        console.log('🎯 Adding border and shadow variables...');

        if (colors.primary) {
            const primaryColor = colors.primary.hex || colors.primary;
            const primaryLight = lightenColor(primaryColor, 40);
            const primaryAlpha = hexToRgb(primaryColor);

            tokens['--border-color'] = primaryLight;
            tokens['--primary-border-light'] = primaryLight;
            tokens['--divider-color'] = primaryLight;

            if (primaryAlpha) {
                tokens['--shadow-primary'] = `rgba(${primaryAlpha.r}, ${primaryAlpha.g}, ${primaryAlpha.b}, 0.1)`;
                tokens['--shadow-primary-strong'] = `rgba(${primaryAlpha.r}, ${primaryAlpha.g}, ${primaryAlpha.b}, 0.3)`;
                tokens['--primary-shadow'] = `rgba(${primaryAlpha.r}, ${primaryAlpha.g}, ${primaryAlpha.b}, 0.2)`;
            }
        }

        // Add component-specific variables that might be used in the theme
        console.log('🎯 Adding component-specific variables...');

        if (colors.primary) {
            const primaryColor = colors.primary.hex || colors.primary;
            // Hero section
            tokens['--hero-bg'] = primaryColor;
            tokens['--hero-primary'] = primaryColor;

            // Footer
            tokens['--footer-bg'] = primaryColor;
            tokens['--footer-background'] = primaryColor;

            // Sidebar
            tokens['--sidebar-bg'] = primaryColor;
            tokens['--sidebar-primary'] = primaryColor;

            // Widget
            tokens['--widget-bg'] = primaryColor;
            tokens['--widget-primary'] = primaryColor;
        }

        if (colors.secondary) {
            const secondaryColor = colors.secondary.hex || colors.secondary;
            // Content areas
            tokens['--content-secondary'] = secondaryColor;
            tokens['--sidebar-secondary'] = secondaryColor;
            tokens['--widget-secondary'] = secondaryColor;
        }

        if (colors.accent) {
            const accentColor = colors.accent.hex || colors.accent;
            // Special elements
            tokens['--feature-color'] = accentColor;
            tokens['--special-color'] = accentColor;
            tokens['--testimonial-accent'] = accentColor;
            tokens['--quote-accent'] = accentColor;
        }

        console.log(`🎯 Generated ${Object.keys(tokens).length} total color tokens`);
        console.log('🎯 Final tokens object:', tokens);
        return tokens;
    }

    /**
     * Color utility functions
     */
    function isValidColor(color) {
        return /^#[0-9A-Fa-f]{6}$/.test(color) ||
               /^#[0-9A-Fa-f]{3}$/.test(color) ||
               /^rgb/.test(color) ||
               /^hsl/.test(color);
    }

    function hexToRgb(hex) {
        const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        if (!result) return null;
        return {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        };
    }

    function lightenColor(hex, percent) {
        const num = parseInt(hex.replace('#', ''), 16);
        const amt = Math.round(2.55 * percent);
        const R = (num >> 16) + amt;
        const G = (num >> 8 & 0x00FF) + amt;
        const B = (num & 0x0000FF) + amt;
        return '#' + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
            (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
            (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
    }

    function darkenColor(hex, percent) {
        const num = parseInt(hex.replace('#', ''), 16);
        const amt = Math.round(2.55 * percent);
        const R = (num >> 16) - amt;
        const G = (num >> 8 & 0x00FF) - amt;
        const B = (num & 0x0000FF) - amt;
        return '#' + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
            (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
            (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
    }

    /**
     * Show enhanced color preview
     */
    function showColorPreview(paletteData) {
        const existingPreview = $('#palette-preview');
        existingPreview.remove();

        if (!paletteData || !paletteData.colors) return;

        const previewHtml = `
            <div id="palette-preview" class="palette-preview">
                <h5> ${paletteData.name || 'Selected Palette'}</h5>
                <div class="color-swatches">
                    ${Object.entries(paletteData.colors).map(([role, colorData]) => {
                        const colorValue = colorData.hex || colorData;
                        return `
                            <div class="color-swatch-detail">
                                <div class="swatch" style="background-color: ${colorValue}"></div>
                                <span class="role">${role}</span>
                                <span class="value">${colorValue}</span>
                            </div>
                        `;
                    }).join('')}
                </div>
                <div class="preview-status">
                    <span class="live-indicator">⚡ Design Tokens Applied</span>
                </div>
            </div>
        `;

        // Add to container
        const container = document.querySelector('#simple-color-palette-container');
        if (container) {
            container.insertAdjacentHTML('beforeend', previewHtml);
        }
    }

    /**
     * Update performance display
     */
    function updatePerformanceDisplay(data) {
        const { duration, performance } = data;
        const metricsContainer = $('#performance-metrics');

        if (metricsContainer.length) {
            const html = `
                <div class="perf-metric">
                    <span>Update Time:</span>
                    <span class="${duration < 50 ? 'good' : duration < 100 ? 'okay' : 'slow'}">
                        ${duration.toFixed(1)}ms
                    </span>
                </div>
                <div class="perf-metric">
                    <span>Avg Performance:</span>
                    <span>${performance.avgUpdateTime ? performance.avgUpdateTime.toFixed(1) + 'ms' : 'N/A'}</span>
                </div>
            `;
            metricsContainer.html(html);
        }
    }

    /**
     * Show performance metrics (enhanced version)
     */
    function showPerformanceMetrics(duration, performanceData) {
        console.log('📊 Showing performance metrics:', { duration, performanceData });

        const metricsContainer = $('#performance-metrics');

        if (metricsContainer.length) {
            const html = `
                <div class="perf-metrics-title">⚡ Performance</div>
                <div class="perf-metric">
                    <span>Update Time:</span>
                    <span class="perf-value ${duration < 50 ? 'good' : duration < 100 ? 'okay' : 'slow'}">
                        ${duration.toFixed(1)}ms
                    </span>
                </div>
                <div class="perf-metric">
                    <span>Average:</span>
                    <span class="perf-value">${performanceData?.avgUpdateTime ? performanceData.avgUpdateTime.toFixed(1) + 'ms' : 'N/A'}</span>
                </div>
                <div class="perf-metric">
                    <span>Total Updates:</span>
                    <span class="perf-value">${performanceData?.totalUpdates || 0}</span>
                </div>
            `;
            metricsContainer.html(html);

            console.log('📊 Performance metrics updated in UI');
        } else {
            console.warn('⚠️ Performance metrics container not found');
        }
    }

    /**
     * Apply Changes - Enhanced with Live Preview
     */
    function applyChanges() {
        if (!currentConfig.activePalette) {
            showMessage('Please select a color palette first.', 'error');
            return;
        }

        const $button = $('#simple-vc-apply');
        $button.prop('disabled', true).text('Applying...');

        console.log('🎨 Applying changes globally...', currentConfig);

        // Note: Changes are already applied via Live Preview
        // This saves them globally for all users

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
                    showMessage('Settings applied globally! All visitors will now see this theme.', 'success');

                    // Update apply button to show success
                    $button.text('Applied ✓').removeClass('simple-vc-btn-primary').addClass('simple-vc-btn-success');

                    // Reset after delay
                    setTimeout(() => {
                        $button.text('Apply Globally').removeClass('simple-vc-btn-success').addClass('simple-vc-btn-primary').prop('disabled', false);
                    }, 3000);
                } else {
                    showMessage('Error: ' + response.data.message, 'error');
                    $button.prop('disabled', false).text('Apply Globally');
                }
            },
            error: function() {
                showMessage('Network error occurred', 'error');
                $button.prop('disabled', false).text('Apply Globally');
            }
        });
    }

    /**
     * Reset Changes - Enhanced
     */
    function resetChanges() {
        console.log('🔄 Resetting visual customizer...');

        // Reset Live Preview System
        if (livePreviewSystem) {
            livePreviewSystem.resetPreview();
        }

        // Reset UI
        $('.palette-item, .palette-item-simple, .palette-card').removeClass('active selected');
        $('#palette-preview').remove();
        $('#performance-metrics').empty();

        currentConfig = {};
        updateApplyButton();

        showMessage('Visual customizer reset to defaults', 'info');
    }

    /**
     * Update apply button state
     */
    function updateApplyButton() {
        const $button = $('#simple-vc-apply');
        if (currentConfig.activePalette) {
            $button.prop('disabled', false);
        } else {
            $button.prop('disabled', true);
        }
    }

    /**
     * Show enhanced message with better styling
     */
    function showMessage(message, type = 'info') {
        const existingMessage = $('.simple-vc-message');
        existingMessage.remove();

        const messageClass = `simple-vc-message simple-vc-message-${type}`;
        const icon = type === 'success' ? '✅' : type === 'error' ? '❌' : type === 'warning' ? '⚠️' : 'ℹ️';

        const messageHtml = `
            <div class="${messageClass}">
                <span class="message-icon">${icon}</span>
                <span class="message-text">${message}</span>
            </div>
        `;

        $('.simple-vc-content').prepend(messageHtml);

        // Auto remove after delay
        setTimeout(() => {
            $('.simple-vc-message').fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
    }

    /**
     * Load basic fallback (last resort)
     */
    function loadBasicFallback() {
        const container = $('#simple-color-palette-container');
        container.html(`
            <div class="fallback-message">
                <p>⚠️ Color system not available</p>
                <p>Please ensure all required scripts are loaded.</p>
                <button class="simple-vc-btn simple-vc-btn-secondary" onclick="location.reload()">
                    Reload Page
                </button>
            </div>
        `);
    }

    /**
     * CRITICAL FIX: Load and highlight current palette
     */
    function loadAndHighlightCurrentPalette() {
        console.log('🎯 Loading current palette to highlight...');

        // Method 1: Try AJAX call to get current palette
        if (typeof simpleCustomizer !== 'undefined' && simpleCustomizer.ajaxUrl) {
            $.ajax({
                url: simpleCustomizer.ajaxUrl,
                method: 'POST',
                data: {
                    action: 'get_current_palette',
                    nonce: simpleCustomizer.nonce
                },
                success: function(response) {
                    if (response.success && response.data) {
                        const currentPaletteId = response.data;
                        console.log(`✅ Current palette from AJAX: ${currentPaletteId}`);

                        // CRITICAL FIX: Verify palette exists before using it
                        const paletteElement = $(`.palette-item-simple[data-palette="${currentPaletteId}"], .palette-card[data-palette-id="${currentPaletteId}"]`);
                        if (paletteElement.length > 0) {
                            console.log(`✅ Verified palette ${currentPaletteId} exists in interface`);
                            highlightPalette(currentPaletteId);
                            loadCurrentPaletteData(currentPaletteId);
                        } else {
                            console.warn(`⚠️ AJAX returned palette '${currentPaletteId}' but it doesn't exist in available palettes`);
                            console.log('📋 Available palettes in interface:');
                            $('.palette-item-simple[data-palette]').each(function() {
                                console.log(`  - ${$(this).data('palette')}`);
                            });
                            tryAlternativeCurrentPaletteDetection();
                        }
                    } else {
                        console.warn('⚠️ AJAX returned no current palette data');
                        tryAlternativeCurrentPaletteDetection();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ AJAX Error loading current palette:', status, error);
                    tryAlternativeCurrentPaletteDetection();
                }
            });
        } else {
            console.warn('⚠️ simpleCustomizer config not available for AJAX');
            tryAlternativeCurrentPaletteDetection();
        }
    }

    /**
     * Try alternative methods to detect current palette
     */
    function tryAlternativeCurrentPaletteDetection() {
        console.log('🔍 Trying alternative current palette detection...');

        // Method 2: Check semantic color system
        if (typeof SemanticColorSystem !== 'undefined') {
            try {
                const semanticSystem = new SemanticColorSystem();
                const currentPalette = semanticSystem.getCurrentPalette();
                if (currentPalette && currentPalette.id) {
                    console.log(`✅ Current palette from SemanticColorSystem: ${currentPalette.id}`);
                    highlightPalette(currentPalette.id);
                    return;
                }
            } catch (error) {
                console.error('❌ SemanticColorSystem getCurrentPalette failed:', error);
            }
        }

        // Method 3: Check color system manager
        if (typeof ColorSystemManager !== 'undefined') {
            try {
                const colorManager = new ColorSystemManager();
                const currentPalette = colorManager.getCurrentPalette();
                if (currentPalette && currentPalette.id) {
                    console.log(`✅ Current palette from ColorSystemManager: ${currentPalette.id}`);
                    highlightPalette(currentPalette.id);
                    return;
                }
            } catch (error) {
                console.error('❌ ColorSystemManager getCurrentPalette failed:', error);
            }
        }

        // Method 4: ENHANCED - Get first available palette from interface
        const availablePalettes = $('.palette-item-simple[data-palette]');
        if (availablePalettes.length > 0) {
            const firstPaletteId = $(availablePalettes[0]).data('palette');
            console.log(`✅ Using first available palette: ${firstPaletteId}`);
            highlightPalette(firstPaletteId);
            loadCurrentPaletteData(firstPaletteId);
            return;
        }

        // Method 5: Final fallback
        console.warn('⚠️ Could not detect any current palette, checking system defaults...');
        const fallbackPalettes = ['modern-monochrome', 'professional-trust', 'modern-clinical'];
        for (const fallbackId of fallbackPalettes) {
            if ($(`.palette-item-simple[data-palette="${fallbackId}"]`).length > 0) {
                console.log(`✅ Using system fallback: ${fallbackId}`);
                highlightPalette(fallbackId);
                loadCurrentPaletteData(fallbackId);
                return;
            }
        }

        console.warn('⚠️ No valid palettes found in the interface');
    }

    /**
     * Highlight the selected palette in the interface
     */
    function highlightPalette(paletteId) {
        console.log(`🎯 Highlighting palette: ${paletteId}`);

        // Remove existing selections
        $('.palette-item-simple, .palette-card').removeClass('active selected');

        // Highlight the current palette
        const paletteElement = $(`.palette-item-simple[data-palette="${paletteId}"], .palette-card[data-palette-id="${paletteId}"]`);
        if (paletteElement.length > 0) {
            paletteElement.addClass('active selected');
            console.log(`✅ Highlighted palette: ${paletteId}`);

            // Scroll to the highlighted palette if needed
            const container = $('#simple-color-palette-container');
            if (container.length && paletteElement.length) {
                container.animate({
                    scrollTop: paletteElement.offset().top - container.offset().top + container.scrollTop() - 50
                }, 300);
            }
        } else {
            console.warn(`⚠️ Could not find palette element for: ${paletteId}`);
        }
    }

    /**
     * Load current palette data for immediate preview
     */
    function loadCurrentPaletteData(paletteId) {
        console.log(`📊 Loading palette data for: ${paletteId}`);

        try {
            // Try to get full palette data
            let paletteData = null;

            if (typeof SemanticColorSystem !== 'undefined') {
                const semanticSystem = new SemanticColorSystem();
                paletteData = semanticSystem.getPalette(paletteId);
            }

            if (paletteData) {
                console.log(`✅ Loaded palette data for ${paletteId}:`, paletteData);

                // Store in current config
                currentConfig = {
                    activePalette: paletteId,
                    paletteData: paletteData,
                    timestamp: performance.now()
                };

                // Apply via LivePreviewSystem if available
                if (livePreviewSystem) {
                    console.log(`🚀 Applying current palette ${paletteId} via LivePreviewSystem...`);
                    livePreviewSystem.applyPalette(paletteData).then(() => {
                        console.log(`✅ Current palette ${paletteId} applied successfully`);
                    }).catch(error => {
                        console.error(`❌ Error applying current palette ${paletteId}:`, error);
                    });
                } else {
                    console.warn('⚠️ LivePreviewSystem not available for current palette application');
                }
            } else {
                console.warn(`⚠️ Could not load palette data for: ${paletteId}`);

                // ENHANCED: Try to get palette data from DOM element
                const paletteElement = $(`.palette-item-simple[data-palette="${paletteId}"]`);
                if (paletteElement.length > 0) {
                    const domPaletteData = paletteElement.data('palette-data');
                    if (domPaletteData) {
                        console.log(`✅ Found palette data in DOM for ${paletteId}:`, domPaletteData);

                        currentConfig = {
                            activePalette: paletteId,
                            paletteData: domPaletteData,
                            timestamp: performance.now()
                        };

                        if (livePreviewSystem) {
                            console.log(`🚀 Applying DOM palette ${paletteId} via LivePreviewSystem...`);
                            livePreviewSystem.applyPalette(domPaletteData).then(() => {
                                console.log(`✅ DOM palette ${paletteId} applied successfully`);
                            }).catch(error => {
                                console.error(`❌ Error applying DOM palette ${paletteId}:`, error);
                            });
                        }
                        return;
                    }
                }

                console.warn(`⚠️ No palette data available for ${paletteId} from any source`);
            }

        } catch (error) {
            console.error(`❌ Error loading palette data for ${paletteId}:`, error);
        }
    }

    /**
     * Setup Launcher Menu - Smartphone Style Navigation
     */
    function setupLauncherMenu() {
        console.log('📱 Setting up launcher menu...');

        $(document).on('click', '.launcher-icon', function() {
            const $icon = $(this);
            const sectionName = $icon.data('section');

            console.log(`📱 Launcher icon clicked: ${sectionName}`);

            // Update active icon
            $('.launcher-icon').removeClass('active');
            $icon.addClass('active');

            // Show corresponding section
            $('.customizer-section').removeClass('active');
            $(`#section-${sectionName}`).addClass('active');

            // Add visual feedback
            $icon.addClass('clicked');
            setTimeout(() => {
                $icon.removeClass('clicked');
            }, 200);

            console.log(`✅ Switched to section: ${sectionName}`);
        });

        console.log('✅ Launcher menu setup complete');
    }

    /**
     * Load Typography Interface - Integration with Design Token System
     */
    function loadTypographyInterface() {
        const container = $('#simple-typography-container');

        console.log('📝 Loading typography interface...');

        // Check if Sprint 2 Extension typography interface is available
        if (typeof SidebarTypographyInterface !== 'undefined') {
            try {
                console.log('📝 Using Sprint 2 Extension typography interface...');

                // Create a mock bridge for the typography interface
                const mockBridge = {
                    debug: true,
                    log: (message, ...args) => console.log(`[TypographyBridge] ${message}`, ...args)
                };

                // Initialize the typography interface
                const typographyInterface = new SidebarTypographyInterface(mockBridge);

                // Render the interface
                const typographyHtml = typographyInterface.render();
                container.html(typographyHtml);

                // Setup typography interactions
                setupTypographyInteractions(container);

                console.log('✅ Typography interface loaded with Sprint 2 Extension');

            } catch (error) {
                console.error('❌ Error loading Sprint 2 typography interface:', error);
                loadFallbackTypography();
            }
        } else {
            console.warn('⚠️ Sprint 2 Typography interface not available, loading fallback');
            loadFallbackTypography();
        }
    }

    /**
     * Setup Typography Interactions with Design Token System
     */
    function setupTypographyInteractions(container) {
        console.log('📝 Setting up typography interactions...');

        // Typography selection handling
        $(container).on('click', '.typography-preview', function() {
            const $preview = $(this);
            const pairingId = $preview.data('pairing');
            const pairingData = $preview.data('pairing-data');

            console.log('📝 Typography selected:', pairingId, pairingData);

            // Visual feedback
            container.find('.typography-preview').removeClass('selected');
            $preview.addClass('selected');

            // Store in current config
            currentConfig.typographyPairing = pairingId;
            currentConfig.typographyData = pairingData;

            // Apply immediately via Design Token System
            applyTypographyTokens(pairingData);

            // Update apply button
            updateApplyButton();

            showMessage(`Typography "${pairingData.name}" applied instantly!`, 'success');
        });

        // Category filter interactions
        $(container).on('click', '.typography-filter-btn', function() {
            const $btn = $(this);
            const category = $btn.data('category');

            // Update active state
            container.find('.typography-filter-btn').removeClass('active');
            $btn.addClass('active');

            // Filter typography
            filterTypographyByCategory(category, container);
        });

        // Search interactions
        $(container).on('input', '.typography-search', function() {
            const searchTerm = $(this).val().toLowerCase();
            filterTypographyBySearch(searchTerm, container);
        });

        console.log('✅ Typography interactions setup complete');
    }

    /**
     * Apply Typography Tokens - Design Token System Integration
     */
    function applyTypographyTokens(pairingData) {
        if (!pairingData) return;

        console.log('🎯 Applying typography tokens:', pairingData);

        try {
            // Generate CSS custom properties for typography
            const typographyTokens = {};

            // Heading font tokens
            if (pairingData.heading) {
                const headingFamily = `"${pairingData.heading.family}", ${pairingData.heading.fallback}`;
                typographyTokens['--font-heading'] = headingFamily;
                typographyTokens['--font-h1'] = headingFamily;
                typographyTokens['--font-h2'] = headingFamily;
                typographyTokens['--font-h3'] = headingFamily;
                typographyTokens['--font-h4'] = headingFamily;
                typographyTokens['--font-h5'] = headingFamily;
                typographyTokens['--font-h6'] = headingFamily;

                // Heading weights
                if (pairingData.heading.weights && pairingData.heading.weights.length > 0) {
                    typographyTokens['--font-weight-heading-light'] = pairingData.heading.weights[0];
                    typographyTokens['--font-weight-heading-normal'] = pairingData.heading.weights[Math.floor(pairingData.heading.weights.length / 2)];
                    typographyTokens['--font-weight-heading-bold'] = pairingData.heading.weights[pairingData.heading.weights.length - 1];
                }
            }

            // Body font tokens
            if (pairingData.body) {
                const bodyFamily = `"${pairingData.body.family}", ${pairingData.body.fallback}`;
                typographyTokens['--font-body'] = bodyFamily;
                typographyTokens['--font-text'] = bodyFamily;
                typographyTokens['--font-paragraph'] = bodyFamily;

                // Body weights
                if (pairingData.body.weights && pairingData.body.weights.length > 0) {
                    typographyTokens['--font-weight-light'] = pairingData.body.weights[0];
                    typographyTokens['--font-weight-normal'] = pairingData.body.weights[Math.floor(pairingData.body.weights.length / 2)];
                    typographyTokens['--font-weight-bold'] = pairingData.body.weights[pairingData.body.weights.length - 1];
                }
            }

            // Apply tokens to document root
            const documentRoot = document.documentElement;
            Object.entries(typographyTokens).forEach(([property, value]) => {
                documentRoot.style.setProperty(property, value);
                console.log(`🎯 Applied token: ${property} = ${value}`);
            });

            console.log('✅ Typography tokens applied successfully');

        } catch (error) {
            console.error('❌ Error applying typography tokens:', error);
        }
    }

    /**
     * Filter typography by category
     */
    function filterTypographyByCategory(category, container) {
        const previews = container.find('.typography-preview');

        previews.each(function() {
            const $preview = $(this);
            const previewCategory = $preview.data('category');
            const shouldShow = category === 'all' || previewCategory === category;
            $preview.toggle(shouldShow);
        });
    }

    /**
     * Filter typography by search term
     */
    function filterTypographyBySearch(searchTerm, container) {
        const previews = container.find('.typography-preview');

        previews.each(function() {
            const $preview = $(this);
            const pairingName = $preview.find('.pairing-name').text().toLowerCase();
            const pairingDescription = $preview.find('.pairing-description').text().toLowerCase();
            const fontInfo = $preview.find('.font-info').text().toLowerCase();

            const shouldShow = !searchTerm ||
                             pairingName.includes(searchTerm) ||
                             pairingDescription.includes(searchTerm) ||
                             fontInfo.includes(searchTerm);

            $preview.toggle(shouldShow);
        });
    }

    /**
     * Load Fallback Typography Interface
     */
    function loadFallbackTypography() {
        const container = $('#simple-typography-container');

        const fallbackHtml = `
            <div class="typography-fallback">
                <div class="fallback-message">
                    <div class="fallback-icon">📝</div>
                    <h5>Typography System Loading...</h5>
                    <p>Typography interface components are being loaded.</p>
                </div>

                <div class="basic-typography-options">
                    <h6>Quick Typography Options:</h6>
                    <div class="basic-font-grid">
                        <div class="basic-font-option" data-font="system-ui">
                            <div class="font-preview" style="font-family: system-ui;">Aa</div>
                            <span>System UI</span>
                        </div>
                        <div class="basic-font-option" data-font="Georgia">
                            <div class="font-preview" style="font-family: Georgia;">Aa</div>
                            <span>Georgia</span>
                        </div>
                        <div class="basic-font-option" data-font="Arial">
                            <div class="font-preview" style="font-family: Arial;">Aa</div>
                            <span>Arial</span>
                        </div>
                    </div>
                </div>
            </div>
        `;

        container.html(fallbackHtml);

        // Setup basic typography interactions
        $(container).on('click', '.basic-font-option', function() {
            const fontFamily = $(this).data('font');

            // Apply basic font change
            document.documentElement.style.setProperty('--font-body', fontFamily);
            document.documentElement.style.setProperty('--font-heading', fontFamily);

            showMessage(`Font changed to ${fontFamily}`, 'success');
        });

        console.log('✅ Fallback typography interface loaded');
    }

    /**
     * Emergency manual CSS injection when LivePreviewSystem fails - ENHANCED with Dynamic Classes
     */
    function emergencyManualCSSInjection(paletteData) {
        console.log('🚨 Emergency: Design Token system is working, this fallback is no longer needed');
        showMessage('Design Token application is active', 'success');
    }

    // Initialize when document is ready
    $(document).ready(function() {
        if (typeof simpleCustomizer !== 'undefined') {
            initSimpleVisualCustomizer();
        } else {
            console.warn('⚠️ Simple Customizer data not available');
        }
    });

    // Export for global access
    window.SimpleVisualCustomizer = {
        init: initSimpleVisualCustomizer,
        openSidebar: openSidebar,
        closeSidebar: closeSidebar,
        applyChanges: applyChanges,
        resetChanges: resetChanges,
        getCurrentConfig: () => currentConfig,
        getLivePreviewSystem: () => livePreviewSystem,
        getPreviewMessenger: () => previewMessenger,
        getWPCustomizerBridge: () => wpCustomizerBridge
    };

    // Also export lowercase version for WordPress integration
    window.simpleVisualCustomizer = window.SimpleVisualCustomizer;

})(jQuery);
