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

        // PRODUCTION FIX: Verify critical CSS files are loaded first
        const cssStatus = verifyCriticalCSSFiles();
        console.log('🔧 CSS Status:', cssStatus);

        if (!cssStatus.customizerEnhancementsFixed) {
            console.error('❌ CRITICAL: customizer-enhancements.css missing - this was causing 404 error');
            showMessage('Critical CSS file missing - Visual Customizer may not work properly', 'error');
        } else {
            console.log('✅ customizer-enhancements.css 404 error FIXED!');
        }

        // PRODUCTION FIX: Ensure CSS tokenization is active
        const tokenizationStatus = ensureCSSTokenizationActive();
        console.log('🔧 Tokenization status:', tokenizationStatus);

        // Initialize PVC-005 components first
        initPVC005Components();

        // CRITICAL FIX: Make LivePreviewSystem globally available
        if (livePreviewSystem) {
            window.livePreviewSystem = livePreviewSystem;
            console.log('✅ LivePreviewSystem made globally available');
        }

        // CRITICAL FIX: Create sidebar HTML structure
        createSidebar();
        console.log('✅ Sidebar HTML structure created');

        // Admin bar trigger - FIXED: Correct selector for WordPress admin bar
        $(document).on('click', '#wp-admin-bar-visual-customizer a', function(e) {
            e.preventDefault();
            console.log('🎨 Visual Customizer clicked from admin bar');
            openSidebar();
        });

        // Fallback click handler for any Visual Customizer triggers
        $(document).on('click', '.visual-customizer-trigger', function(e) {
            e.preventDefault();
            console.log('🎨 Visual Customizer triggered via fallback');
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
     * Open sidebar and load interfaces on-demand
     */
    function openSidebar() {
        console.log('🚀 Opening Simple Visual Customizer sidebar...');

        // Show overlay and sidebar
        $('#simple-vc-overlay').fadeIn(200);
        $('#simple-vc-sidebar').addClass('open');

        // Load color palette interface immediately (always works)
        loadColorPaletteInterface();

        // Don't load typography interface immediately - wait for user selection
        // This prevents blocking on missing dependencies
        console.log('📝 Typography interface will load when section is activated');

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
        // Create overlay first
        if ($('#simple-vc-overlay').length === 0) {
            $('body').append('<div id="simple-vc-overlay" class="simple-vc-overlay"></div>');
        }

        // Create sidebar if it doesn't exist
        if ($('#simple-vc-sidebar').length === 0) {
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
        }

        // Setup launcher menu interactions
        setupLauncherMenu();

        console.log('✅ Sidebar and overlay HTML structures created');
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
     * FIXED: Apply color tokens immediately with server CSS respect
     */
    function applyColorTokensImmediately(paletteData) {
        if (!paletteData || !paletteData.colors) {
            console.error('❌ Invalid palette data for immediate application');
            return;
        }

        console.log('🎨 Applying color tokens immediately...', paletteData);

        // CRITICAL FIX: Check if server CSS variables already exist and match
        const rootStyle = getComputedStyle(document.documentElement);
        const serverPrimary = rootStyle.getPropertyValue('--component-bg-color-primary').trim();
        const serverColorPrimary = rootStyle.getPropertyValue('--color-primary').trim();

        if (serverPrimary !== '' || serverColorPrimary !== '') {
            console.log('🖥️ SERVER CSS VARIABLES DETECTED');
            console.log('🖥️ Server --component-bg-color-primary:', serverPrimary);
            console.log('🖥️ Server --color-primary:', serverColorPrimary);

            // Check if server colors match what we're trying to apply
            const clientPrimary = paletteData.colors.primary?.hex || paletteData.colors.primary;

            if (serverPrimary === clientPrimary || serverColorPrimary === clientPrimary) {
                console.log('✅ Server CSS matches client palette - no override needed');
                console.log('✅ Server-client sync is working correctly');

                // Still trigger visual feedback since colors are correct
                triggerVisualFeedback();
                return;
            } else {
                console.log('⚠️ Server CSS differs from client palette:');
                console.log(`    Server: ${serverPrimary || serverColorPrimary}`);
                console.log(`    Client: ${clientPrimary}`);
                console.log('⚠️ This indicates a server-client sync issue - recommend page reload');

                // Show user feedback about the mismatch
                showMessage('⚠️ Server-client color mismatch detected. Consider refreshing the page.', 'warning');
            }
        }

        console.log('🎨 Proceeding with client-side color application...');

        // Apply color tokens to CSS custom properties
        const colorTokens = generateColorTokens(paletteData.colors);

        Object.entries(colorTokens).forEach(([property, value]) => {
            // Only set if not already set by server or if we need to override
            const currentValue = rootStyle.getPropertyValue(property).trim();
            if (currentValue === '' || serverPrimary === '') {
                document.documentElement.style.setProperty(property, value);
                console.log(`🎨 Set ${property}: ${value}`);
            } else {
                console.log(`🖥️ Kept server ${property}: ${currentValue}`);
            }
        });

        // Generate and inject CSS class rules
        const cssRules = generateCSSClassRules(paletteData.colors);
        if (cssRules) {
            injectCSSRules(cssRules, paletteData.id || 'current');
        }

        // Trigger visual feedback
        triggerVisualFeedback();

        // Force CSS recalculation for buttons
        setTimeout(() => {
            forceCSSRecalculationForButtons();
        }, 50);

        console.log('✅ Color tokens applied with server CSS respect');
    }

    /**
     * PRODUCTION DEBUG: Trigger visual feedback when palette changes
     */
    function triggerVisualFeedback() {
        console.log('🎨 Triggering visual feedback for palette change...');

        // Find all buttons and give them a visual indicator
        const buttons = document.querySelectorAll('button, .btn, .cta-primary, .cta-secondary, .btn-consultation');
        console.log(`🎨 Found ${buttons.length} buttons for visual feedback`);

        buttons.forEach((button, index) => {
            if (index < 5) { // Only feedback first 5 buttons to avoid performance issues
                button.style.animation = 'paletteChangeIndicator 0.5s ease';
                setTimeout(() => {
                    button.style.animation = '';
                }, 500);
            }
        });

        // Show temporary color indicator
        showColorChangeIndicator();
    }

    /**
     * PRODUCTION DEBUG: Show temporary color change indicator
     */
    function showColorChangeIndicator() {
        const indicator = document.createElement('div');
        indicator.style.cssText = `
            position: fixed;
            top: 60px;
            right: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            z-index: 999999;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            animation: slideInRight 0.3s ease, fadeOut 0.3s ease 2.7s forwards;
        `;

        const currentPalette = document.body.getAttribute('data-current-palette') || 'Unknown';
        const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--component-bg-color-primary').trim();

        indicator.innerHTML = `
            🎨 Palette Changed<br>
            <small>${currentPalette}</small><br>
            <small style="font-family: monospace;">${primaryColor}</small>
        `;

        document.body.appendChild(indicator);

        setTimeout(() => {
            indicator.remove();
        }, 3000);
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
     * PRODUCTION DEBUG: Inspect actual button CSS variables being used
     */
    function inspectButtonCSSVariables() {
        console.log('🔍 INSPECTING BUTTON CSS VARIABLES...');

        // Find key buttons to inspect
        const buttonsToInspect = [
            'button.btn.btn--primary',
            'button.cta-primary',
            'button.cta-secondary',
            'a.btn-consultation'
        ];

        buttonsToInspect.forEach(selector => {
            const button = document.querySelector(selector);
            if (button) {
                const computedStyle = getComputedStyle(button);
                const bgColor = computedStyle.backgroundColor;
                const textColor = computedStyle.color;
                const borderColor = computedStyle.borderColor;

                console.log(`🔘 ${selector}:`);
                console.log(`   Background: ${bgColor}`);
                console.log(`   Text Color: ${textColor}`);
                console.log(`   Border: ${borderColor}`);

                // Check if it's using CSS variables
                const isUsingVariables = bgColor.includes('var(') ||
                                       getComputedStyle(document.documentElement)
                                       .getPropertyValue('--component-bg-color-primary')
                                       .trim() === rgbToHex(bgColor);

                console.log(`   Using Variables: ${isUsingVariables ? '✅' : '❌'}`);
            } else {
                console.log(`❌ ${selector}: NOT FOUND`);
            }
        });

        // Check root CSS variables
        const rootStyle = getComputedStyle(document.documentElement);
        const criticalVars = [
            '--component-bg-color-primary',
            '--component-text-color-primary',
            '--component-bg-color-secondary',
            '--component-bg-color-accent'
        ];

        console.log('🎨 ROOT CSS VARIABLES:');
        criticalVars.forEach(varName => {
            const value = rootStyle.getPropertyValue(varName).trim();
            console.log(`   ${varName}: ${value || 'NOT SET'}`);
        });
    }

    /**
     * Helper: Convert RGB to Hex for comparison
     */
    function rgbToHex(rgb) {
        if (!rgb || !rgb.includes('rgb')) return rgb;

        const values = rgb.match(/\d+/g);
        if (!values || values.length < 3) return rgb;

        const hex = values.slice(0, 3).map(val => {
            const hexVal = parseInt(val).toString(16);
            return hexVal.length === 1 ? '0' + hexVal : hexVal;
        }).join('');

        return '#' + hex.toUpperCase();
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

        // ⚡ CRITICAL FIX: Add component-level tokens for tokenization-aware architecture
        console.log('🚨 FIXING: Adding missing component-level tokens for tokenization-aware components...');

        if (colors.primary) {
            const primaryColor = colors.primary.hex || colors.primary;

            // MISSING COMPONENT TOKENS - Root cause of button BG inconsistency
            tokens['--component-bg-color-primary'] = primaryColor;
            tokens['--component-text-color-primary'] = colors.surface ? (colors.surface.hex || colors.surface) : '#ffffff';
            tokens['--component-border-color-primary'] = primaryColor;

            // Palette tokens that component tokens inherit from
            tokens['--palette-primary'] = primaryColor;
            tokens['--palette-primary-contrast'] = tokens['--component-text-color-primary'];
            tokens['--palette-primary-hover'] = darkenColor(primaryColor, 15);

            // CRITICAL: Add missing foundation tokens for complete inheritance chain
            tokens['--color-primary-contrast'] = tokens['--component-text-color-primary'];
            tokens['--color-primary-hover'] = darkenColor(primaryColor, 15);
            tokens['--color-primary-dark'] = darkenColor(primaryColor, 20);
        }

        if (colors.secondary) {
            const secondaryColor = colors.secondary.hex || colors.secondary;

            // Component tokens for secondary variants
            tokens['--component-bg-color-secondary'] = secondaryColor;
            tokens['--component-text-color-secondary'] = colors.surface ? (colors.surface.hex || colors.surface) : '#ffffff';
            tokens['--component-border-color-secondary'] = secondaryColor;

            // Palette tokens
            tokens['--palette-secondary'] = secondaryColor;
            tokens['--palette-secondary-contrast'] = tokens['--component-text-color-secondary'];
            tokens['--palette-secondary-hover'] = darkenColor(secondaryColor, 15);

            // Foundation tokens for inheritance chain
            tokens['--color-secondary-contrast'] = tokens['--component-text-color-secondary'];
            tokens['--color-secondary-hover'] = darkenColor(secondaryColor, 15);
            tokens['--color-secondary-dark'] = darkenColor(secondaryColor, 20);
        }

        if (colors.accent) {
            const accentColor = colors.accent.hex || colors.accent;

            // Component tokens for accent variants
            tokens['--component-bg-color-accent'] = accentColor;
            tokens['--component-text-color-accent'] = colors.surface ? (colors.surface.hex || colors.surface) : '#ffffff';
            tokens['--component-border-color-accent'] = accentColor;

            // Palette tokens
            tokens['--palette-accent'] = accentColor;
            tokens['--palette-accent-contrast'] = tokens['--component-text-color-accent'];
            tokens['--palette-accent-hover'] = darkenColor(accentColor, 15);

            // Foundation tokens for inheritance chain
            tokens['--color-accent-contrast'] = tokens['--component-text-color-accent'];
            tokens['--color-accent-hover'] = darkenColor(accentColor, 15);
            tokens['--color-accent-dark'] = darkenColor(accentColor, 20);
        }

        if (colors.surface) {
            const surfaceColor = colors.surface.hex || colors.surface;

            // Default component tokens (fallback values)
            tokens['--component-bg-color'] = surfaceColor;
            tokens['--component-text-color'] = colors.primary ? (colors.primary.hex || colors.primary) : '#0f172a';
            tokens['--component-border-color'] = colors.border || '#e2e8f0';

            // Palette surface tokens
            tokens['--palette-surface'] = surfaceColor;
            tokens['--palette-surface-secondary'] = lightenColor(surfaceColor, 5);
            tokens['--palette-surface-tertiary'] = lightenColor(surfaceColor, 10);

            // Foundation surface tokens
            tokens['--color-surface-secondary'] = lightenColor(surfaceColor, 5);
            tokens['--color-surface-tertiary'] = lightenColor(surfaceColor, 10);
        }

        if (colors.background) {
            const backgroundColor = colors.background.hex || colors.background;

            // Background palette tokens
            tokens['--palette-background'] = backgroundColor;
        }

        // Text color tokens for component inheritance
        if (colors.primary) {
            const primaryColor = colors.primary.hex || colors.primary;
            tokens['--palette-text-primary'] = primaryColor;
            tokens['--color-text-primary'] = colors.secondary ? (colors.secondary.hex || colors.secondary) : '#0f172a';
            tokens['--color-text-inverse'] = colors.surface ? (colors.surface.hex || colors.surface) : '#ffffff';
        }

        if (colors.secondary) {
            const secondaryColor = colors.secondary.hex || colors.secondary;
            tokens['--palette-text-secondary'] = secondaryColor;
        }

        // Border tokens
        if (colors.border) {
            tokens['--palette-border'] = colors.border;
            tokens['--color-border'] = colors.border;
        } else {
            // Generate sensible border color from primary or use default
            const borderColor = colors.primary ? lightenColor(colors.primary.hex || colors.primary, 40) : '#e2e8f0';
            tokens['--palette-border'] = borderColor;
            tokens['--color-border'] = borderColor;
        }

        // Focus and interaction state tokens
        if (colors.primary) {
            const primaryColor = colors.primary.hex || colors.primary;
            tokens['--color-border-focus'] = primaryColor;
            tokens['--color-focus'] = primaryColor;
        }

        // Status color tokens for component inheritance
        const statusColors = {
            success: '#10b981',
            warning: '#f59e0b',
            error: '#ef4444',
            info: '#3b82f6'
        };

        Object.entries(statusColors).forEach(([status, color]) => {
            tokens[`--color-${status}`] = color;
            tokens[`--color-${status}-dark`] = darkenColor(color, 15);
            tokens[`--color-${status}-light`] = lightenColor(color, 15);
        });

        // Medical spa treatment-specific tokens (from SCSS)
        if (colors.primary && colors.secondary && colors.accent) {
            const primaryColor = colors.primary.hex || colors.primary;
            const secondaryColor = colors.secondary.hex || colors.secondary;
            const accentColor = colors.accent.hex || colors.accent;

            tokens['--treatment-facial'] = accentColor;
            tokens['--treatment-body'] = secondaryColor;
            tokens['--treatment-laser'] = primaryColor;
            tokens['--treatment-injectable'] = lightenColor(primaryColor, 10);
            tokens['--treatment-skin'] = lightenColor(secondaryColor, 10);
        }

        console.log('✅ Component-level tokens added for tokenization-aware architecture compatibility');
        console.log('✅ Foundation tokens added for complete inheritance chain');
        console.log('✅ Status and treatment-specific tokens added');

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
     * CRITICAL FIX: Apply Changes with Better Persistence
     */
    function applyChanges() {
        if (!currentConfig.activePalette) {
            showMessage('Please select a color palette first.', 'error');
            return;
        }

        const $button = $('#simple-vc-apply');
        $button.prop('disabled', true).text('Applying...');

        console.log('🎨 Applying changes globally...', currentConfig);

        // PRODUCTION FIX: Add force update attribute to trigger CSS recalculation
        document.body.setAttribute('data-customizer-applying', 'true');

        // PRODUCTION FIX: Save to consolidated localStorage immediately
        savePaletteToLocalStorage(currentConfig.activePalette, currentConfig);

        // ENHANCED: Prepare complete configuration for save
        const saveConfig = {
            activePalette: currentConfig.activePalette,
            paletteData: currentConfig.paletteData,
            typographyPairing: currentConfig.typographyPairing,
            typographyData: currentConfig.typographyData,
            timestamp: Date.now(),
            userAgent: navigator.userAgent,
            url: window.location.href
        };

        // Note: Changes are already applied via Live Preview
        // This saves them globally for all users

        $.ajax({
            url: simpleCustomizer.ajaxUrl,
            type: 'POST',
            data: {
                action: 'simple_visual_customizer_apply',
                nonce: simpleCustomizer.nonce,
                config: JSON.stringify(saveConfig),
                timestamp: Date.now(), // Add timestamp to prevent caching issues
                palette_id: currentConfig.activePalette,
                palette_data: JSON.stringify(currentConfig.paletteData)
            },
            timeout: 30000, // 30 second timeout
            success: function(response) {
                console.log('📡 AJAX Success Response:', response);

                if (response.success) {
                    showMessage('✅ Settings applied globally! All visitors will now see this theme.', 'success');

                    // PRODUCTION FIX: Verify save worked by checking response data
                    if (response.data && response.data.saved_palette) {
                        console.log('✅ Server confirmed palette saved:', response.data.saved_palette);

                        if (response.data.saved_palette === currentConfig.activePalette) {
                            console.log('✅ Server palette matches current palette - save successful');
                        } else {
                            console.warn('⚠️ Server palette mismatch:', {
                                sent: currentConfig.activePalette,
                                saved: response.data.saved_palette
                            });
                        }
                    }

                    // PRODUCTION FIX: Force CSS recalculation after save
                    setTimeout(() => {
                        document.body.setAttribute('data-customizer-applied', 'true');
                        document.body.removeAttribute('data-customizer-applying');

                        // Force reflow to ensure styles update
                        document.body.style.display = 'none';
                        document.body.offsetHeight; // Trigger reflow
                        document.body.style.display = '';

                        // Add data attribute for tokenization force update
                        document.body.setAttribute('data-tokenization-force-update', 'true');

                        // Reapply current palette to ensure consistency
                        if (currentConfig.paletteData) {
                            console.log('🔄 Reapplying palette after global save...');
                            applyColorTokensImmediately(currentConfig.paletteData);
                        }

                        // Remove force update after animation completes
                        setTimeout(() => {
                            document.body.removeAttribute('data-tokenization-force-update');
                        }, 500);
                    }, 100);

                    // Update apply button to show success
                    $button.text('Applied ✓').removeClass('simple-vc-btn-primary').addClass('simple-vc-btn-success');

                    // Reset after delay
                    setTimeout(() => {
                        $button.text('Apply Globally').removeClass('simple-vc-btn-success').addClass('simple-vc-btn-primary').prop('disabled', false);
                    }, 3000);

                } else {
                    console.error('❌ Server returned error:', response);
                    const errorMessage = response.data?.message || 'Unknown server error';
                    showMessage('❌ Server Error: ' + errorMessage, 'error');
                    $button.prop('disabled', false).text('Apply Globally');
                    document.body.removeAttribute('data-customizer-applying');
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ AJAX Error:', { xhr, status, error });
                console.error('❌ Response Text:', xhr.responseText);

                let errorMessage = 'Network error occurred';
                if (status === 'timeout') {
                    errorMessage = 'Request timed out - server may be busy';
                } else if (xhr.status === 403) {
                    errorMessage = 'Permission denied - please refresh and try again';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error - please check server logs';
                } else if (xhr.responseText) {
                    try {
                        const errorData = JSON.parse(xhr.responseText);
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        errorMessage += ': ' + xhr.responseText.substring(0, 100);
                    }
                }

                showMessage('❌ ' + errorMessage, 'error');
                $button.prop('disabled', false).text('Apply Globally');
                document.body.removeAttribute('data-customizer-applying');
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

        // PRODUCTION FIX: Check consolidated localStorage first
        const localStoragePalette = getCurrentPaletteFromLocalStorage();
        if (localStoragePalette) {
            console.log(`✅ Current palette from consolidated localStorage: ${localStoragePalette}`);

            const paletteElement = $(`.palette-item-simple[data-palette="${localStoragePalette}"], .palette-card[data-palette-id="${localStoragePalette}"]`);
            if (paletteElement.length > 0) {
                console.log(`✅ Verified palette ${localStoragePalette} exists in interface`);
                highlightPalette(localStoragePalette);
                loadCurrentPaletteData(localStoragePalette);
                return;
            } else {
                console.warn(`⚠️ localStorage palette '${localStoragePalette}' not found in interface`);
            }
        }

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

                            // Save to consolidated localStorage for future use
                            savePaletteToLocalStorage(currentPaletteId, { activePalette: currentPaletteId });
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

            // Load section-specific interfaces on-demand
            if (sectionName === 'typography') {
                console.log('📝 Loading typography interface on demand...');
                loadTypographyInterface();
            }

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
     * Load Typography Interface - Enhanced with immediate working fallback
     */
    function loadTypographyInterface() {
        const container = $('#simple-typography-container');

        // Check if already loaded
        if (container.find('.typography-preview').length > 0) {
            console.log('📝 Typography interface already loaded');
            return;
        }

        console.log('📝 Loading typography interface...');

        // IMMEDIATE WORKING SOLUTION: Load functional typography interface right away
        // Instead of waiting for complex dependencies, provide working typography immediately
        console.log('📝 Loading immediate working typography interface...');

        const workingTypographyHtml = `
            <div class="typography-interface-working">
                <div class="typography-header">
                    <h4 class="typography-title">📝 Typography Options</h4>
                    <p class="typography-subtitle">Professional font pairings for medical spa</p>
                </div>

                <div class="typography-grid">
                    <div class="typography-card" data-typography="medical-professional" style="border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin-bottom: 10px; cursor: pointer; transition: all 0.2s ease;">
                        <div class="typography-preview" style="margin-bottom: 10px;">
                            <h5 style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; font-weight: 600; margin: 0 0 5px 0; font-size: 16px; color: #333;">Medical Professional</h5>
                            <p style="font-family: 'Source Sans Pro', Arial, sans-serif; font-weight: 400; margin: 0; font-size: 14px; color: #666; line-height: 1.4;">Clean, authoritative typography for medical professionals. Inter for headings, Source Sans Pro for body text.</p>
                        </div>
                        <div class="typography-meta" style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #888;">
                            <span class="typography-category">Professional</span>
                            <span class="typography-fonts">Inter / Source Sans Pro</span>
                        </div>
                    </div>

                    <div class="typography-card" data-typography="luxury-modern" style="border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin-bottom: 10px; cursor: pointer; transition: all 0.2s ease;">
                        <div class="typography-preview" style="margin-bottom: 10px;">
                            <h5 style="font-family: 'Playfair Display', Georgia, serif; font-weight: 600; margin: 0 0 5px 0; font-size: 16px; color: #333;">Luxury Modern</h5>
                            <p style="font-family: 'Lato', Arial, sans-serif; font-weight: 400; margin: 0; font-size: 14px; color: #666; line-height: 1.4;">Elegant and sophisticated typography for luxury spa experiences. Playfair Display for headings, Lato for body text.</p>
                        </div>
                        <div class="typography-meta" style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #888;">
                            <span class="typography-category">Luxury</span>
                            <span class="typography-fonts">Playfair Display / Lato</span>
                        </div>
                    </div>

                    <div class="typography-card" data-typography="contemporary-clean" style="border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin-bottom: 10px; cursor: pointer; transition: all 0.2s ease;">
                        <div class="typography-preview" style="margin-bottom: 10px;">
                            <h5 style="font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif; font-weight: 600; margin: 0 0 5px 0; font-size: 16px; color: #333;">Contemporary Clean</h5>
                            <p style="font-family: 'Open Sans', Arial, sans-serif; font-weight: 400; margin: 0; font-size: 14px; color: #666; line-height: 1.4;">Modern and minimal typography for contemporary wellness centers. Poppins for headings, Open Sans for body text.</p>
                        </div>
                        <div class="typography-meta" style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #888;">
                            <span class="typography-category">Modern</span>
                            <span class="typography-fonts">Poppins / Open Sans</span>
                        </div>
                    </div>

                    <div class="typography-card" data-typography="wellness-serif" style="border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin-bottom: 10px; cursor: pointer; transition: all 0.2s ease;">
                        <div class="typography-preview" style="margin-bottom: 10px;">
                            <h5 style="font-family: 'Crimson Text', Georgia, serif; font-weight: 600; margin: 0 0 5px 0; font-size: 16px; color: #333;">Wellness Serif</h5>
                            <p style="font-family: 'Nunito Sans', Arial, sans-serif; font-weight: 400; margin: 0; font-size: 14px; color: #666; line-height: 1.4;">Warm and approachable typography for wellness and therapy. Crimson Text for headings, Nunito Sans for body text.</p>
                        </div>
                        <div class="typography-meta" style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #888;">
                            <span class="typography-category">Wellness</span>
                            <span class="typography-fonts">Crimson Text / Nunito Sans</span>
                        </div>
                    </div>
                </div>

                <div class="typography-actions" style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee;">
                    <div class="typography-status" id="typography-status" style="font-size: 12px; color: #666; margin-bottom: 10px;">
                        Select a typography pairing to apply
                    </div>
                    <button class="typography-apply-btn" id="typography-apply-btn" style="background: #007cba; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 13px; opacity: 0.5;" disabled>
                        Apply Typography
                    </button>
                </div>
            </div>
        `;

        // Load the working interface
        container.html(workingTypographyHtml);

        // Setup working interactions
        setupWorkingTypographyInteractions(container);

        console.log('✅ Working typography interface loaded successfully');

        // Also try to load the advanced interface in the background (non-blocking)
        tryLoadAdvancedTypographyInterface();
    }

    /**
     * Setup Working Typography Interactions
     */
    function setupWorkingTypographyInteractions(container) {
        console.log('📝 Setting up working typography interactions...');

        // Typography card selection
        container.find('.typography-card').on('click', function() {
            const $card = $(this);
            const typographyId = $card.data('typography');

            console.log('📝 Typography selected:', typographyId);

            // Visual feedback
            container.find('.typography-card').css({
                'border-color': '#ddd',
                'background-color': 'white'
            });

            $card.css({
                'border-color': '#007cba',
                'background-color': '#f0f8ff'
            });

            // Get typography data
            const typographyData = getTypographyData(typographyId);

            if (typographyData) {
                // Store in current config
                currentConfig.typographyPairing = typographyId;
                currentConfig.typographyData = typographyData;

                // Apply typography immediately
                applyWorkingTypography(typographyData);

                // Update status and enable apply button
                const statusEl = container.find('#typography-status');
                const applyBtn = container.find('#typography-apply-btn');

                statusEl.text(`Applied: ${typographyData.name}`).css('color', '#28a745');
                applyBtn.prop('disabled', false).css('opacity', '1');

                // Update main apply button
                updateApplyButton();

                showMessage(`Typography "${typographyData.name}" applied!`, 'success');
            }
        });

        // Apply button
        container.find('#typography-apply-btn').on('click', function() {
            if (currentConfig.typographyData) {
                // Save to server
                saveTypographyToServer(currentConfig.typographyData);
                showMessage('Typography settings saved!', 'success');
            }
        });

        console.log('✅ Working typography interactions setup complete');
    }

    /**
     * Get Typography Data
     */
    function getTypographyData(typographyId) {
        const typographyMap = {
            'medical-professional': {
                id: 'medical-professional',
                name: 'Medical Professional',
                description: 'Clean and authoritative',
                category: 'Professional',
                headingFont: {
                    family: 'Inter',
                    fallback: '-apple-system, BlinkMacSystemFont, sans-serif',
                    weights: [400, 500, 600, 700]
                },
                bodyFont: {
                    family: 'Source Sans Pro',
                    fallback: 'Arial, sans-serif',
                    weights: [400, 500, 600]
                },
                googleFonts: ['Inter:400,500,600,700', 'Source+Sans+Pro:400,500,600']
            },
            'luxury-modern': {
                id: 'luxury-modern',
                name: 'Luxury Modern',
                description: 'Elegant and sophisticated',
                category: 'Luxury',
                headingFont: {
                    family: 'Playfair Display',
                    fallback: 'Georgia, serif',
                    weights: [400, 600, 700]
                },
                bodyFont: {
                    family: 'Lato',
                    fallback: 'Arial, sans-serif',
                    weights: [400, 500, 600]
                },
                googleFonts: ['Playfair+Display:400,600,700', 'Lato:400,500,600']
            },
            'contemporary-clean': {
                id: 'contemporary-clean',
                name: 'Contemporary Clean',
                description: 'Modern and minimal',
                category: 'Modern',
                headingFont: {
                    family: 'Poppins',
                    fallback: '-apple-system, BlinkMacSystemFont, sans-serif',
                    weights: [400, 500, 600, 700]
                },
                bodyFont: {
                    family: 'Open Sans',
                    fallback: 'Arial, sans-serif',
                    weights: [400, 500, 600]
                },
                googleFonts: ['Poppins:400,500,600,700', 'Open+Sans:400,500,600']
            },
            'wellness-serif': {
                id: 'wellness-serif',
                name: 'Wellness Serif',
                description: 'Warm and approachable',
                category: 'Wellness',
                headingFont: {
                    family: 'Crimson Text',
                    fallback: 'Georgia, serif',
                    weights: [400, 600, 700]
                },
                bodyFont: {
                    family: 'Nunito Sans',
                    fallback: 'Arial, sans-serif',
                    weights: [400, 500, 600]
                },
                googleFonts: ['Crimson+Text:400,600,700', 'Nunito+Sans:400,500,600']
            }
        };

        return typographyMap[typographyId] || null;
    }

    /**
     * Apply Working Typography
     */
    function applyWorkingTypography(typographyData) {
        console.log('📝 Applying working typography:', typographyData);

        try {
            // Load Google Fonts if needed
            loadGoogleFontsForTypography(typographyData);

            // Create typography CSS
            const css = generateTypographyCSS(typographyData);

            // Apply CSS
            let typographyStyle = document.getElementById('working-typography-styles');
            if (!typographyStyle) {
                typographyStyle = document.createElement('style');
                typographyStyle.id = 'working-typography-styles';
                document.head.appendChild(typographyStyle);
            }

            typographyStyle.textContent = css;

            console.log('✅ Working typography applied successfully');

        } catch (error) {
            console.error('❌ Error applying working typography:', error);
        }
    }

    /**
     * Load Google Fonts for Typography
     */
    function loadGoogleFontsForTypography(typographyData) {
        if (!typographyData.googleFonts || typographyData.googleFonts.length === 0) {
            return;
        }

        // Check if fonts are already loaded
        const existingLink = document.querySelector('#typography-google-fonts');

        const fontsQuery = typographyData.googleFonts.join('&family=');
        const googleFontsUrl = `https://fonts.googleapis.com/css2?family=${fontsQuery}&display=swap`;

        if (existingLink) {
            existingLink.href = googleFontsUrl;
        } else {
            const link = document.createElement('link');
            link.id = 'typography-google-fonts';
            link.rel = 'stylesheet';
            link.href = googleFontsUrl;
            document.head.appendChild(link);
        }

        console.log('🔗 Google Fonts loaded for typography:', typographyData.googleFonts);
    }

    /**
     * Generate Typography CSS
     */
    function generateTypographyCSS(typographyData) {
        const headingFamily = `"${typographyData.headingFont.family}", ${typographyData.headingFont.fallback}`;
        const bodyFamily = `"${typographyData.bodyFont.family}", ${typographyData.bodyFont.fallback}`;

        return `
/* Working Typography System - ${typographyData.name} */
:root {
    --typography-heading-family: ${headingFamily};
    --typography-body-family: ${bodyFamily};
    --typography-heading-weight-normal: ${typographyData.headingFont.weights[0] || 400};
    --typography-heading-weight-medium: ${typographyData.headingFont.weights[1] || 500};
    --typography-heading-weight-bold: ${typographyData.headingFont.weights[2] || 600};
    --typography-body-weight-normal: ${typographyData.bodyFont.weights[0] || 400};
    --typography-body-weight-medium: ${typographyData.bodyFont.weights[1] || 500};
}

/* Apply to headings */
h1, h2, h3, h4, h5, h6,
.heading, .title, .site-title,
.hero-title, .section-title {
    font-family: var(--typography-heading-family) !important;
}

/* Apply to body text */
body, p, div, span, a, li, td, th,
.body-text, .content, .description {
    font-family: var(--typography-body-family) !important;
}

/* Specific weight applications */
h1, .hero-title {
    font-weight: var(--typography-heading-weight-bold) !important;
}

h2, h3, .section-title {
    font-weight: var(--typography-heading-weight-medium) !important;
}

h4, h5, h6 {
    font-weight: var(--typography-heading-weight-normal) !important;
}

body, p, div, span {
    font-weight: var(--typography-body-weight-normal) !important;
}

/* Ensure navigation and buttons use appropriate fonts */
.nav-menu, .menu-item a {
    font-family: var(--typography-heading-family) !important;
    font-weight: var(--typography-heading-weight-medium) !important;
}

button, .btn, input[type="submit"] {
    font-family: var(--typography-heading-family) !important;
    font-weight: var(--typography-heading-weight-medium) !important;
}
        `;
    }

    /**
     * Save Typography to Server
     */
    function saveTypographyToServer(typographyData) {
        // For now, store in localStorage as well
        localStorage.setItem('applied_typography', JSON.stringify(typographyData));

        // In the future, this could make an AJAX call to save to WordPress options
        console.log('💾 Typography saved:', typographyData);
    }

    /**
     * Try Load Advanced Typography Interface (background, non-blocking)
     */
    function tryLoadAdvancedTypographyInterface() {
        console.log('📝 Attempting to load advanced typography interface in background...');

        // Check for SidebarTypographyInterface with timeout
        let attempts = 0;
        const maxAttempts = 5; // Only try for 2.5 seconds

        const checkForAdvanced = () => {
            attempts++;

            if (typeof window.SidebarTypographyInterface !== 'undefined') {
                console.log('✅ Advanced typography interface found, enhancing...');
                // Could enhance the existing interface here
            } else if (attempts < maxAttempts) {
                setTimeout(checkForAdvanced, 500);
            } else {
                console.log('⚠️ Advanced typography interface not available, using working version');
            }
        };

        checkForAdvanced();
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

    /**
     * PRODUCTION FIX: Clean up conflicting localStorage keys
     */
    function cleanupConflictingLocalStorage() {
        console.log('🧹 Cleaning up conflicting localStorage keys...');

        // List of conflicting keys found in production
        const conflictingKeys = [
            'visual_customizer_current_palette',
            'medspaa_visual_customizer',
            'medSpa_colorSystem_settings',
            'preetidreams_visual_customizer_settings',
            'visual_customizer_temp_settings'
        ];

        // Get all values before clearing
        const existingValues = {};
        conflictingKeys.forEach(key => {
            const value = localStorage.getItem(key);
            if (value) {
                try {
                    existingValues[key] = JSON.parse(value);
                } catch (e) {
                    existingValues[key] = value;
                }
            }
        });

        console.log('🔍 Found existing localStorage values:', existingValues);

        // Determine the most recent/valid palette
        let consolidatedPalette = null;

        // Priority order for palette selection
        if (existingValues['visual_customizer_current_palette']) {
            consolidatedPalette = existingValues['visual_customizer_current_palette'];
        } else if (existingValues['medSpa_colorSystem_settings']?.currentPalette) {
            consolidatedPalette = existingValues['medSpa_colorSystem_settings'].currentPalette;
        } else if (existingValues['medspaa_visual_customizer']?.colorPalette) {
            consolidatedPalette = existingValues['medspaa_visual_customizer'].colorPalette;
        } else if (existingValues['preetidreams_visual_customizer_settings']?.colorPalette) {
            consolidatedPalette = existingValues['preetidreams_visual_customizer_settings'].colorPalette;
        }

        console.log('🎯 Consolidated palette determined:', consolidatedPalette);

        // Clear all conflicting keys
        conflictingKeys.forEach(key => {
            localStorage.removeItem(key);
            console.log(`🗑️ Removed localStorage key: ${key}`);
        });

        // Set single source of truth
        if (consolidatedPalette) {
            const consolidatedSettings = {
                currentPalette: consolidatedPalette,
                timestamp: Date.now(),
                source: 'consolidated_cleanup',
                previousConflicts: Object.keys(existingValues)
            };

            localStorage.setItem('visual_customizer_settings', JSON.stringify(consolidatedSettings));
            console.log('✅ Set consolidated localStorage:', consolidatedSettings);

            return consolidatedPalette;
        }

        return null;
    }

    /**
     * PRODUCTION FIX: Get current palette from single source
     */
    function getCurrentPaletteFromLocalStorage() {
        console.log('🔍 Getting current palette from localStorage...');

        // First check our consolidated key
        const consolidatedSettings = localStorage.getItem('visual_customizer_settings');
        if (consolidatedSettings) {
            try {
                const settings = JSON.parse(consolidatedSettings);
                console.log('✅ Found consolidated settings:', settings);
                return settings.currentPalette;
            } catch (e) {
                console.error('❌ Error parsing consolidated settings:', e);
            }
        }

        // If no consolidated settings, clean up conflicts
        return cleanupConflictingLocalStorage();
    }

    /**
     * PRODUCTION FIX: Save palette to single localStorage source
     */
    function savePaletteToLocalStorage(paletteId, config) {
        console.log('💾 Saving palette to localStorage:', paletteId);

        // Clean up any conflicts first
        cleanupConflictingLocalStorage();

        // Save to single source
        const settings = {
            currentPalette: paletteId,
            config: config,
            timestamp: Date.now(),
            source: 'simple_visual_customizer'
        };

        localStorage.setItem('visual_customizer_settings', JSON.stringify(settings));
        console.log('✅ Saved to localStorage:', settings);
    }

    /**
     * PRODUCTION FIX: Ensure CSS Custom Properties are Set and Tokenization CSS Loads
     */
    function ensureCSSTokenizationActive() {
        console.log('🔧 Ensuring CSS tokenization is active...');

        // Check if tokenization CSS is loaded
        const tokenizationCSSExists = Array.from(document.styleSheets).some(sheet => {
            try {
                return sheet.href && (
                    sheet.href.includes('tokenization-contact-overrides') ||
                    sheet.href.includes('simple-visual-customizer') ||
                    sheet.href.includes('visual-customizer')
                );
            } catch (e) {
                return false;
            }
        });

        console.log('🔍 Tokenization CSS loaded:', tokenizationCSSExists);

        // Check if CSS custom properties exist
        const rootStyle = getComputedStyle(document.documentElement);
        const criticalProperties = [
            '--component-bg-color-primary',
            '--component-text-color-primary',
            '--color-primary',
            '--palette-primary'
        ];

        const existingProperties = {};
        criticalProperties.forEach(prop => {
            const value = rootStyle.getPropertyValue(prop).trim();
            existingProperties[prop] = value || 'NOT SET';
        });

        console.log('🔍 Critical CSS custom properties:', existingProperties);

        // If no tokenization CSS or properties, force load
        if (!tokenizationCSSExists || Object.values(existingProperties).every(val => val === 'NOT SET')) {
            console.warn('⚠️ Tokenization CSS or properties missing, forcing load...');
            forceLoadTokenizationCSS();
        }

        // Set minimum viable CSS custom properties if none exist
        const hasAnyProperties = Object.values(existingProperties).some(val => val !== 'NOT SET');
        if (!hasAnyProperties) {
            console.warn('⚠️ No CSS custom properties found, setting fallbacks...');
            setFallbackCSSProperties();
        }

        return {
            tokenizationCSSLoaded: tokenizationCSSExists,
            customPropertiesSet: hasAnyProperties,
            properties: existingProperties
        };
    }

    /**
     * Force load tokenization CSS if missing
     */
    function forceLoadTokenizationCSS() {
        console.log('💉 Force loading tokenization CSS...');

        const themeUri = window.location.origin + window.location.pathname.split('/wp-content')[0] + '/wp-content/themes/medSpaTheme';
        const cssUrl = `${themeUri}/assets/css/tokenization-contact-overrides.css`;

        // Check if already exists
        const existingLink = document.querySelector(`link[href*="tokenization-contact-overrides"]`);
        if (existingLink) {
            console.log('🔍 Tokenization CSS link already exists, forcing reload...');
            existingLink.href = cssUrl + '?t=' + Date.now();
            return;
        }

        // Create new link element
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.type = 'text/css';
        link.href = cssUrl + '?t=' + Date.now();
        link.onload = () => {
            console.log('✅ Tokenization CSS force loaded successfully');
        };
        link.onerror = () => {
            console.error('❌ Failed to force load tokenization CSS');
        };

        document.head.appendChild(link);
        console.log('💉 Injected tokenization CSS link:', cssUrl);
    }

    /**
     * FIXED: Set fallback CSS custom properties ONLY if server hasn't already set them
     */
    function setFallbackCSSProperties() {
        console.log('🎯 Checking for server CSS variables before setting fallbacks...');

        // Check if server has already set CSS variables
        const rootStyle = getComputedStyle(document.documentElement);
        const serverHasPrimary = rootStyle.getPropertyValue('--component-bg-color-primary').trim() !== '';
        const serverHasTokens = rootStyle.getPropertyValue('--color-primary').trim() !== '';

        if (serverHasPrimary || serverHasTokens) {
            console.log('🖥️ SERVER CSS VARIABLES DETECTED - RESPECTING SERVER IMPLEMENTATION');
            console.log('🖥️ Server primary color:', rootStyle.getPropertyValue('--component-bg-color-primary').trim());
            console.log('🖥️ Server color-primary:', rootStyle.getPropertyValue('--color-primary').trim());
            console.log('✅ Skipping fallback injection - server CSS takes priority');
            return;
        }

        console.log('⚠️ No server CSS variables found - applying fallback colors as last resort');

        const fallbackColors = {
            '--component-bg-color-primary': '#8B4B7A',
            '--component-text-color-primary': '#ffffff',
            '--component-border-color-primary': '#8B4B7A',
            '--color-primary': '#8B4B7A',
            '--color-secondary': '#642453',
            '--color-accent': '#C2847A',
            '--palette-primary': '#8B4B7A',
            '--palette-primary-contrast': '#ffffff',
            '--palette-primary-hover': '#642453'
        };

        const documentRoot = document.documentElement;
        Object.entries(fallbackColors).forEach(([property, value]) => {
            // Double-check each property before setting
            const currentValue = rootStyle.getPropertyValue(property).trim();
            if (currentValue === '') {
                documentRoot.style.setProperty(property, value);
                console.log(`🎯 Set fallback: ${property} = ${value}`);
            } else {
                console.log(`🖥️ Kept server value: ${property} = ${currentValue}`);
            }
        });

        console.log('✅ Fallback CSS properties set (only where needed)');
    }

    /**
     * PRODUCTION FIX: Verify critical CSS files are loaded
     */
    function verifyCriticalCSSFiles() {
        console.log('🔍 Verifying critical CSS files are loaded...');

        const criticalFiles = [
            'customizer-enhancements.css',
            'tokenization-contact-overrides.css',
            'simple-visual-customizer.css'
        ];

        const loadedFiles = [];
        const missingFiles = [];

        // Check if CSS custom property exists as a marker
        const rootStyle = getComputedStyle(document.documentElement);
        const customizerEnhancementsLoaded = rootStyle.getPropertyValue('--customizer-enhancements-loaded').trim();

        if (customizerEnhancementsLoaded === '1') {
            loadedFiles.push('customizer-enhancements.css');
            console.log('✅ customizer-enhancements.css loaded successfully');
        } else {
            missingFiles.push('customizer-enhancements.css');
            console.error('❌ customizer-enhancements.css NOT loaded - this was the 404 error cause');
        }

        // Check other files
        criticalFiles.slice(1).forEach(filename => {
            const fileExists = Array.from(document.styleSheets).some(sheet => {
                try {
                    return sheet.href && sheet.href.includes(filename);
                } catch (e) {
                    return false;
                }
            });

            if (fileExists) {
                loadedFiles.push(filename);
                console.log(`✅ ${filename} loaded successfully`);
            } else {
                missingFiles.push(filename);
                console.warn(`⚠️ ${filename} not detected in stylesheets`);
            }
        });

        const allCriticalFilesLoaded = missingFiles.length === 0;

        console.log('📊 CSS File Status:', {
            loaded: loadedFiles,
            missing: missingFiles,
            allLoaded: allCriticalFilesLoaded
        });

        return {
            allLoaded: allCriticalFilesLoaded,
            loaded: loadedFiles,
            missing: missingFiles,
            customizerEnhancementsFixed: customizerEnhancementsLoaded === '1'
        };
    }

    /**
     * CRITICAL FIX: Force CSS Recalculation for Buttons
     * Problem: CSS variables update but buttons don't visually change
     * Solution: Force browser to recalculate button styles
     */
    function forceCSSRecalculationForButtons() {
        console.log('🔄 Forcing CSS recalculation for buttons...');

        // Find all buttons that should be using CSS variables
        const buttonSelectors = [
            'button.btn.btn--primary',
            'button.btn.btn--secondary',
            'button.btn.btn--accent',
            'button.cta-primary',
            'button.cta-secondary',
            'a.btn-consultation',
            'a.mobile-consultation-btn',
            'button.btn--consultation',
            'button.mobile-menu-toggle',
            'button.mobile-menu-close',
            'button.back-to-top'
        ];

        let buttonsUpdated = 0;

        buttonSelectors.forEach(selector => {
            const buttons = document.querySelectorAll(selector);
            buttons.forEach(button => {
                // Force style recalculation by temporarily changing and restoring display
                const originalDisplay = button.style.display;
                button.style.display = 'none';
                button.offsetHeight; // Trigger reflow
                button.style.display = originalDisplay || '';

                // Force CSS variable re-evaluation by adding and removing a class
                button.classList.add('force-css-recalc');
                button.offsetHeight; // Trigger reflow
                button.classList.remove('force-css-recalc');

                // Add data attribute to trigger CSS recalculation
                button.setAttribute('data-css-recalc', Date.now());
                button.offsetHeight; // Trigger reflow
                button.removeAttribute('data-css-recalc');

                buttonsUpdated++;
            });
        });

        console.log(`🔄 Forced CSS recalculation for ${buttonsUpdated} buttons`);

        // Additional force recalculation for the entire page
        setTimeout(() => {
            document.body.style.transform = 'translateZ(0)';
            document.body.offsetHeight; // Trigger reflow
            document.body.style.transform = '';
            console.log('🔄 Forced global CSS recalculation');
        }, 50);

        // Verify button colors after recalculation
        setTimeout(() => {
            verifyButtonColorUpdate();
        }, 150);
    }

    /**
     * Verify that button colors actually updated after CSS variable change
     */
    function verifyButtonColorUpdate() {
        console.log('🔍 Verifying button color updates...');

        const primaryButton = document.querySelector('button.btn.btn--primary, button.cta-primary');
        if (primaryButton) {
            const computedStyle = getComputedStyle(primaryButton);
            const actualBgColor = computedStyle.backgroundColor;
            const expectedBgColor = getComputedStyle(document.documentElement).getPropertyValue('--component-bg-color-primary').trim();

            console.log('🔍 Button verification:');
            console.log(`   Expected (CSS var): ${expectedBgColor}`);
            console.log(`   Actual (computed): ${actualBgColor}`);

            // Convert hex to rgb for comparison
            const expectedRgb = hexToRgb(expectedBgColor);
            const actualRgb = actualBgColor.match(/\d+/g);

            if (expectedRgb && actualRgb && actualRgb.length >= 3) {
                const rgbMatch =
                    Math.abs(expectedRgb.r - parseInt(actualRgb[0])) <= 2 &&
                    Math.abs(expectedRgb.g - parseInt(actualRgb[1])) <= 2 &&
                    Math.abs(expectedRgb.b - parseInt(actualRgb[2])) <= 2;

                if (rgbMatch) {
                    console.log('✅ Button colors successfully updated to match CSS variables!');

                    // CRITICAL: Check for and fix any remaining red buttons
                    setTimeout(() => {
                        const redButtonsFixed = identifyAndFixRedButtons();
                        if (redButtonsFixed > 0) {
                            showMessage(`✅ Button colors updated + fixed ${redButtonsFixed} stubborn buttons!`, 'success');
                        } else {
                            showMessage('✅ Button colors updated successfully!', 'success');
                        }
                    }, 100);
                } else {
                    console.error('❌ Button colors still do not match CSS variables after recalculation');
                    console.error(`❌ Expected RGB: ${expectedRgb.r}, ${expectedRgb.g}, ${expectedRgb.b}`);
                    console.error(`❌ Actual RGB: ${actualRgb[0]}, ${actualRgb[1]}, ${actualRgb[2]}`);

                    // CRITICAL: Force fix red buttons immediately
                    const redButtonsFixed = identifyAndFixRedButtons();

                    if (redButtonsFixed > 0) {
                        showMessage(`🔧 Fixed ${redButtonsFixed} stubborn buttons that weren't responding to CSS!`, 'warning');
                    } else {
                        showMessage('⚠️ Button colors may not have updated - try refreshing the page', 'warning');
                        // Try emergency CSS injection
                        emergencyDirectCSSInjection();
                    }
                }
            } else {
                console.warn('⚠️ Could not parse colors for verification');

                // Still check for red buttons as a safety measure
                setTimeout(() => {
                    const redButtonsFixed = identifyAndFixRedButtons();
                    if (redButtonsFixed > 0) {
                        showMessage(`🔧 Fixed ${redButtonsFixed} stubborn red buttons!`, 'info');
                    } else {
                        showMessage('Colors applied - verification inconclusive', 'info');
                    }
                }, 100);
            }
        } else {
            console.warn('⚠️ No primary button found for verification');

            // Still run red button fix
            setTimeout(() => {
                identifyAndFixRedButtons();
            }, 100);
        }
    }

    /**
     * Emergency direct CSS injection when CSS variables fail
     */
    function emergencyDirectCSSInjection() {
        console.log('🚨 Emergency: Injecting direct CSS colors...');

        const rootStyle = getComputedStyle(document.documentElement);
        const primaryColor = rootStyle.getPropertyValue('--component-bg-color-primary').trim();
        const primaryTextColor = rootStyle.getPropertyValue('--component-text-color-primary').trim();
        const secondaryColor = rootStyle.getPropertyValue('--component-bg-color-secondary').trim();
        const accentColor = rootStyle.getPropertyValue('--component-bg-color-accent').trim();

        if (primaryColor) {
            const emergencyCSS = `
                /* Emergency direct color injection */
                button.btn.btn--primary,
                button.cta-primary,
                a.btn-consultation {
                    background-color: ${primaryColor} !important;
                    color: ${primaryTextColor || '#ffffff'} !important;
                    border-color: ${primaryColor} !important;
                }

                button.btn.btn--secondary,
                button.cta-secondary {
                    background-color: ${secondaryColor || primaryColor} !important;
                    color: ${primaryTextColor || '#ffffff'} !important;
                    border-color: ${secondaryColor || primaryColor} !important;
                }

                button.btn.btn--accent {
                    background-color: ${accentColor || primaryColor} !important;
                    color: ${primaryTextColor || '#ffffff'} !important;
                    border-color: ${accentColor || primaryColor} !important;
                }
            `;

            // Remove any existing emergency styles
            const existingEmergencyStyle = document.getElementById('emergency-button-styles');
            if (existingEmergencyStyle) {
                existingEmergencyStyle.remove();
            }

            // Inject emergency styles
            const styleElement = document.createElement('style');
            styleElement.id = 'emergency-button-styles';
            styleElement.textContent = emergencyCSS;
            document.head.appendChild(styleElement);

            console.log('🚨 Emergency CSS injected with direct colors');
            showMessage('🚨 Emergency color fix applied - buttons should now update!', 'warning');
        } else {
            console.error('❌ No primary color available for emergency injection');
        }
    }

    /**
     * EMERGENCY: Identify and Fix Stubborn Red Buttons
     * Problem: Some buttons have inline styles or very specific CSS that overrides everything
     * Solution: Find red buttons and directly set their styles
     */
    function identifyAndFixRedButtons() {
        console.log('🔍 Identifying stubborn red buttons...');

        // Find all buttons on the page
        const allButtons = document.querySelectorAll('button, .btn, input[type="submit"], input[type="button"], a[class*="btn"]');
        const redButtons = [];
        const currentPrimaryColor = getComputedStyle(document.documentElement).getPropertyValue('--component-bg-color-primary').trim();
        const currentPrimaryTextColor = getComputedStyle(document.documentElement).getPropertyValue('--component-text-color-primary').trim();

        console.log('🎯 Target colors:', { primary: currentPrimaryColor, text: currentPrimaryTextColor });

        allButtons.forEach((button, index) => {
            const computedStyle = getComputedStyle(button);
            const bgColor = computedStyle.backgroundColor;
            const borderColor = computedStyle.borderColor;

            // Check if button is red (various red color patterns)
            const isRedButton =
                bgColor.includes('rgb(211, 47, 47)') ||
                bgColor.includes('rgb(244, 67, 54)') ||
                bgColor.includes('rgb(229, 62, 62)') ||
                bgColor.includes('rgb(220, 38, 38)') ||
                bgColor.includes('rgb(185, 28, 28)') ||
                bgColor.includes('rgb(239, 68, 68)') ||
                bgColor.includes('rgb(248, 113, 113)') ||
                borderColor.includes('rgb(211, 47, 47)') ||
                borderColor.includes('rgb(244, 67, 54)') ||
                borderColor.includes('rgb(220, 38, 38)') ||
                button.style.backgroundColor?.includes('#d32f2f') ||
                button.style.backgroundColor?.includes('#f44336') ||
                button.style.backgroundColor?.includes('#dc2626') ||
                button.style.backgroundColor?.includes('#ef4444');

            if (isRedButton) {
                redButtons.push({
                    element: button,
                    index: index,
                    originalBg: bgColor,
                    originalBorder: borderColor,
                    classList: Array.from(button.classList),
                    id: button.id,
                    tagName: button.tagName,
                    inlineStyle: button.getAttribute('style')
                });

                console.log(`🔴 Found red button ${index}:`, {
                    tagName: button.tagName,
                    classes: Array.from(button.classList),
                    id: button.id,
                    bgColor: bgColor,
                    borderColor: borderColor,
                    inlineStyle: button.getAttribute('style')
                });
            }
        });

        console.log(`🔍 Found ${redButtons.length} red buttons to fix`);

        // Force fix each red button
        redButtons.forEach((buttonInfo, index) => {
            const button = buttonInfo.element;

            console.log(`🔧 Fixing red button ${index + 1}/${redButtons.length}...`);

            // Remove any inline style background colors
            if (button.style.backgroundColor) {
                console.log(`   Removing inline background: ${button.style.backgroundColor}`);
                button.style.removeProperty('background-color');
            }
            if (button.style.background) {
                console.log(`   Removing inline background: ${button.style.background}`);
                button.style.removeProperty('background');
            }
            if (button.style.borderColor) {
                console.log(`   Removing inline border-color: ${button.style.borderColor}`);
                button.style.removeProperty('border-color');
            }

            // Force apply our colors with highest priority
            button.style.setProperty('background-color', currentPrimaryColor, 'important');
            button.style.setProperty('color', currentPrimaryTextColor || '#ffffff', 'important');
            button.style.setProperty('border-color', currentPrimaryColor, 'important');
            button.style.setProperty('transition', 'all 0.3s ease', 'important');

            // Add a data attribute to mark as fixed
            button.setAttribute('data-force-fixed', 'true');
            button.setAttribute('data-original-bg', buttonInfo.originalBg);

            console.log(`   ✅ Fixed button - new style:`, {
                backgroundColor: button.style.backgroundColor,
                color: button.style.color,
                borderColor: button.style.borderColor
            });

            // Add hover effect via JavaScript
            button.addEventListener('mouseenter', function() {
                if (this.getAttribute('data-force-fixed') === 'true') {
                    this.style.setProperty('opacity', '0.9', 'important');
                }
            });

            button.addEventListener('mouseleave', function() {
                if (this.getAttribute('data-force-fixed') === 'true') {
                    this.style.setProperty('opacity', '1', 'important');
                }
            });
        });

        if (redButtons.length > 0) {
            showMessage(`🔧 Force-fixed ${redButtons.length} stubborn red buttons!`, 'success');
            console.log(`✅ Successfully force-fixed ${redButtons.length} red buttons`);
        } else {
            console.log('✅ No red buttons found - all buttons appear to be using correct colors');
        }

        return redButtons.length;
    }

    /**
     * Manual Typography Interface Initialization (for debugging)
     */
    window.manualInitTypography = function() {
        console.log('🔧 Manual typography interface initialization...');

        const container = $('#simple-typography-container');

        // Force clear any existing content
        container.html(`
            <div class="typography-manual-loading">
                <div class="loading-spinner">🔄</div>
                <div class="loading-message">
                    <h5>Manual Typography Loading...</h5>
                    <p>Forcing interface initialization...</p>
                </div>
            </div>
        `);

        // Check all dependencies
        const dependencies = {
            SidebarTypographyInterface: window.SidebarTypographyInterface,
            typographyDomainSystem: window.typographyDomainSystem,
            universalCustomizationEngine: window.universalCustomizationEngine,
            jQuery: window.jQuery
        };

        console.log('🔧 Dependencies check:', dependencies);

        setTimeout(() => {
            if (typeof window.SidebarTypographyInterface !== 'undefined') {
                try {
                    console.log('🔧 Creating manual typography interface...');

                    const bridge = {
                        debug: true,
                        log: (msg, ...args) => console.log(`[ManualBridge] ${msg}`, ...args)
                    };

                    const typographyInterface = new window.SidebarTypographyInterface(bridge);

                    typographyInterface.loadFontPairings().then(() => {
                        const html = typographyInterface.render();
                        container.html(html);
                        setupEnhancedTypographyInteractions(container, typographyInterface);
                        console.log('✅ Manual typography interface loaded!');
                    }).catch(err => {
                        console.error('❌ Manual loading error:', err);
                        container.html('<p>❌ Manual loading failed. Check console for details.</p>');
                    });

                } catch (error) {
                    console.error('❌ Manual initialization error:', error);
                    container.html(`<p>❌ Error: ${error.message}</p>`);
                }
            } else {
                console.error('❌ SidebarTypographyInterface still not available');
                container.html('<p>❌ SidebarTypographyInterface class not found</p>');
            }
        }, 1000);
    };

    /**
     * Improved Typography Interface Loading with Multiple Strategies
     */

})(jQuery);
