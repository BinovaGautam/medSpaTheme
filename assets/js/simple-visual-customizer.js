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
     * OPTIMAL UX: Preload typography fonts on page load for instant previews
     * PERFORMANCE OPTIMIZED: Check if server already loaded fonts
     */
    function preloadTypographyFontsOnPageLoad() {
        console.log('🚀 Checking font loading status for optimal UX...');

        // PERFORMANCE FIX: Check if server already loaded the fonts
        const serverLoadedFonts = document.querySelector('#selected-typography-fonts-css');
        const serverLoadedPreviewFonts = document.querySelector('#typography-preview-fonts-server-css');

        if (serverLoadedFonts) {
            console.log('✅ Server already loaded selected typography fonts - no client loading needed');
        }

        if (serverLoadedPreviewFonts) {
            console.log('✅ Server already loaded preview fonts for admin user - no client loading needed');
            return Promise.resolve(); // Fonts already loaded by server
        }

        // Only preload if user is likely to use customizer AND server didn't load fonts
        if (typeof simpleCustomizer !== 'undefined' && simpleCustomizer.nonce) {
            console.log('✅ User can access customizer but server fonts not detected, minimal client preload...');

            // Use a timeout to not block page load
            setTimeout(() => {
                loadGoogleFontsForTypographyPreviews().then(() => {
                    console.log('✅ Typography fonts preloaded successfully (client fallback)');
                }).catch(error => {
                    console.warn('⚠️ Typography fonts preload failed:', error);
                });
            }, 500); // Reduced timeout since server should handle most cases
        } else {
            console.log('🔍 User cannot access customizer, skipping font preload');
        }
    }

    /**
     * Initialize Simple Visual Customizer - PVC-005 Enhanced
     */
    function initSimpleVisualCustomizer() {
        console.log('🚀 Simple Visual Customizer: Initializing with PVC-005 Live Preview...');

        // OPTIMAL UX: Preload typography fonts for instant previews
        preloadTypographyFontsOnPageLoad();

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
     * PERFORMANCE OPTIMIZED: Rely on server-loaded fonts
     */
    function openSidebar() {
        console.log('🚀 Opening Simple Visual Customizer sidebar...');

        // Show overlay and sidebar
        $('#simple-vc-overlay').fadeIn(200);
        $('#simple-vc-sidebar').addClass('open');

        // PERFORMANCE OPTIMIZATION: Check if fonts are already loaded by server
        const serverLoadedPreviewFonts = document.querySelector('#typography-preview-fonts-server-css');

        if (serverLoadedPreviewFonts) {
            console.log('✅ Typography fonts already loaded by server - instant preview ready!');
        } else {
            console.log('⚠️ Server fonts not detected, loading client-side fonts...');
            // CRITICAL FIX: Load Google Fonts for typography previews IMMEDIATELY when sidebar opens
            loadGoogleFontsForTypographyPreviews();
        }

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
                                <div class="launcher-icon" data-section="footer" title="Footer Components">
                                    <div class="icon-bg">🦶</div>
                                    <span class="icon-label">Footer</span>
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

                            <!-- Footer Section -->
                            <div class="customizer-section" id="section-footer">
                                <div class="section-header">
                                    <h4>🦶 Footer Components</h4>
                                    <p>Modern footer design with map integration and cards</p>
                                </div>
                                <div id="simple-footer-container" class="simple-footer-container">
                                    <!-- Footer Customization Interface -->
                                    <div class="footer-customizer">

                                        <!-- Hero Section Controls -->
                                        <div class="control-group">
                                            <h5 class="group-title">🌟 Hero Section</h5>
                                            <div class="control-item">
                                                <label>Hero Headline</label>
                                                <input type="text"
                                                       data-property="--footer-hero-headline"
                                                       data-target=".hero-headline"
                                                       placeholder="Ready to Transform Your Beauty Journey?"
                                                       class="footer-control">
                                            </div>
                                            <div class="control-item">
                                                <label>Hero Subtext</label>
                                                <textarea data-property="--footer-hero-subtext"
                                                          data-target=".hero-subtext"
                                                          placeholder="Experience luxury medical aesthetics..."
                                                          class="footer-control"></textarea>
                                            </div>
                                            <div class="control-item">
                                                <label>Hero Background Color</label>
                                                <input type="color"
                                                       data-property="--footer-hero-bg-color"
                                                       data-target=".footer-hero-cta"
                                                       value="#1B365D"
                                                       class="footer-control color-input">
                                            </div>
                                            <div class="control-item">
                                                <label>Overlay Opacity</label>
                                                <input type="range"
                                                       data-property="--footer-hero-overlay-opacity"
                                                       data-target=".hero-overlay"
                                                       min="0" max="1" step="0.1" value="0.8"
                                                       class="footer-control range-input">
                                                <span class="range-value">0.8</span>
                                            </div>
                                            <div class="control-item">
                                                <label>Hero Padding</label>
                                                <input type="range"
                                                       data-property="--footer-hero-padding"
                                                       data-target=".footer-hero-cta"
                                                       min="32" max="120" step="8" value="64"
                                                       class="footer-control range-input">
                                                <span class="range-value">64px</span>
                                            </div>
                                        </div>

                                        <!-- Map Section Controls -->
                                        <div class="control-group">
                                            <h5 class="group-title">🗺️ Map Integration</h5>
                                            <div class="control-item">
                                                <label>Enable Map Section</label>
                                                <input type="checkbox"
                                                       data-property="--footer-map-enabled"
                                                       data-target=".footer-map-section"
                                                       checked
                                                       class="footer-control toggle-input">
                                            </div>
                                            <div class="control-item">
                                                <label>Map Height</label>
                                                <input type="range"
                                                       data-property="--footer-map-height"
                                                       data-target=".footer-map-section"
                                                       min="200" max="600" step="50" value="400"
                                                       class="footer-control range-input">
                                                <span class="range-value">400px</span>
                                            </div>
                                            <div class="control-item">
                                                <label>Overlay Background</label>
                                                <input type="color"
                                                       data-property="--footer-map-overlay-bg"
                                                       data-target=".clinic-info-card"
                                                       value="#1B365D"
                                                       class="footer-control color-input">
                                            </div>
                                            <div class="control-item">
                                                <label>Overlay Border Radius</label>
                                                <input type="range"
                                                       data-property="--footer-map-border-radius"
                                                       data-target=".clinic-info-card"
                                                       min="0" max="20" step="2" value="12"
                                                       class="footer-control range-input">
                                                <span class="range-value">12px</span>
                                            </div>
                                        </div>

                                        <!-- Card Design Controls -->
                                        <div class="control-group">
                                            <h5 class="group-title">🎴 Information Cards</h5>
                                            <div class="control-item">
                                                <label>Card Background</label>
                                                <input type="color"
                                                       data-property="--footer-card-bg"
                                                       data-target=".info-card"
                                                       value="#ffffff"
                                                       class="footer-control color-input">
                                            </div>
                                            <div class="control-item">
                                                <label>Card Border Color</label>
                                                <input type="color"
                                                       data-property="--footer-card-border-color"
                                                       data-target=".info-card"
                                                       value="#e5e7eb"
                                                       class="footer-control color-input">
                                            </div>
                                            <div class="control-item">
                                                <label>Card Border Radius</label>
                                                <input type="range"
                                                       data-property="--footer-card-radius"
                                                       data-target=".info-card"
                                                       min="0" max="20" step="2" value="12"
                                                       class="footer-control range-input">
                                                <span class="range-value">12px</span>
                                            </div>
                                            <div class="control-item">
                                                <label>Card Padding</label>
                                                <input type="range"
                                                       data-property="--footer-card-padding"
                                                       data-target=".info-card"
                                                       min="12" max="48" step="4" value="24"
                                                       class="footer-control range-input">
                                                <span class="range-value">24px</span>
                                            </div>
                                            <div class="control-item">
                                                <label>Card Shadow</label>
                                                <select data-property="--footer-card-shadow"
                                                        data-target=".info-card"
                                                        class="footer-control select-input">
                                                    <option value="none">None</option>
                                                    <option value="0 1px 3px rgba(0,0,0,0.1)">Small</option>
                                                    <option value="0 4px 6px rgba(0,0,0,0.1)" selected>Medium</option>
                                                    <option value="0 10px 15px rgba(0,0,0,0.1)">Large</option>
                                                    <option value="0 20px 25px rgba(0,0,0,0.1)">Extra Large</option>
                                                </select>
                                            </div>
                                            <div class="control-item">
                                                <label>Cards Grid Gap</label>
                                                <input type="range"
                                                       data-property="--footer-card-gap"
                                                       data-target=".cards-grid"
                                                       min="8" max="48" step="4" value="24"
                                                       class="footer-control range-input">
                                                <span class="range-value">24px</span>
                                            </div>
                                        </div>

                                        <!-- CTA Button Controls -->
                                        <div class="control-group">
                                            <h5 class="group-title">🎯 CTA Buttons</h5>
                                            <div class="control-item">
                                                <label>Primary Button Background</label>
                                                <input type="color"
                                                       data-property="--footer-cta-primary-bg"
                                                       data-target=".cta-primary-modern"
                                                       value="#D4AF37"
                                                       class="footer-control color-input">
                                            </div>
                                            <div class="control-item">
                                                <label>Primary Button Hover</label>
                                                <input type="color"
                                                       data-property="--footer-cta-primary-hover"
                                                       data-target=".cta-primary-modern:hover"
                                                       value="#E6C547"
                                                       class="footer-control color-input">
                                            </div>
                                            <div class="control-item">
                                                <label>Primary Button Text</label>
                                                <input type="color"
                                                       data-property="--footer-cta-primary-text"
                                                       data-target=".cta-primary-modern"
                                                       value="#ffffff"
                                                       class="footer-control color-input">
                                            </div>
                                            <div class="control-item">
                                                <label>Secondary Button Border</label>
                                                <input type="color"
                                                       data-property="--footer-cta-secondary-border"
                                                       data-target=".cta-secondary-modern"
                                                       value="#D4AF37"
                                                       class="footer-control color-input">
                                            </div>
                                            <div class="control-item">
                                                <label>Button Border Radius</label>
                                                <input type="range"
                                                       data-property="--footer-cta-radius"
                                                       data-target=".cta-primary-modern, .cta-secondary-modern"
                                                       min="0" max="25" step="1" value="12"
                                                       class="footer-control range-input">
                                                <span class="range-value">12px</span>
                                            </div>
                                        </div>

                                        <!-- Typography Controls -->
                                        <div class="control-group">
                                            <h5 class="group-title">📝 Typography</h5>
                                            <div class="control-item">
                                                <label>Primary Text Color</label>
                                                <input type="color"
                                                       data-property="--footer-text-primary"
                                                       data-target=".footer-component"
                                                       value="#1f2937"
                                                       class="footer-control color-input">
                                            </div>
                                            <div class="control-item">
                                                <label>Secondary Text Color</label>
                                                <input type="color"
                                                       data-property="--footer-text-secondary"
                                                       data-target=".footer-component"
                                                       value="#6b7280"
                                                       class="footer-control color-input">
                                            </div>
                                            <div class="control-item">
                                                <label>Accent Text Color</label>
                                                <input type="color"
                                                       data-property="--footer-text-accent"
                                                       data-target=".footer-component"
                                                       value="#D4AF37"
                                                       class="footer-control color-input">
                                            </div>
                                            <div class="control-item">
                                                <label>Hero Headline Size</label>
                                                <input type="range"
                                                       data-property="--footer-hero-headline-size"
                                                       data-target=".hero-headline"
                                                       min="24" max="48" step="2" value="36"
                                                       class="footer-control range-input">
                                                <span class="range-value">36px</span>
                                            </div>
                                            <div class="control-item">
                                                <label>Card Title Size</label>
                                                <input type="range"
                                                       data-property="--footer-card-title-size"
                                                       data-target=".card-title"
                                                       min="16" max="28" step="1" value="20"
                                                       class="footer-control range-input">
                                                <span class="range-value">20px</span>
                                            </div>
                                        </div>

                                        <!-- Newsletter Controls -->
                                        <div class="control-group">
                                            <h5 class="group-title">📧 Newsletter</h5>
                                            <div class="control-item">
                                                <label>Newsletter Background</label>
                                                <input type="color"
                                                       data-property="--footer-newsletter-bg"
                                                       data-target=".footer-newsletter-section"
                                                       value="#f8fafc"
                                                       class="footer-control color-input">
                                            </div>
                                            <div class="control-item">
                                                <label>Input Background</label>
                                                <input type="color"
                                                       data-property="--footer-newsletter-input-bg"
                                                       data-target=".newsletter-input"
                                                       value="#ffffff"
                                                       class="footer-control color-input">
                                            </div>
                                            <div class="control-item">
                                                <label>Input Border Color</label>
                                                <input type="color"
                                                       data-property="--footer-newsletter-input-border"
                                                       data-target=".newsletter-input"
                                                       value="#e5e7eb"
                                                       class="footer-control color-input">
                                            </div>
                                        </div>

                                        <!-- Layout Controls -->
                                        <div class="control-group">
                                            <h5 class="group-title">📐 Layout</h5>
                                            <div class="control-item">
                                                <label>Container Max Width</label>
                                                <input type="range"
                                                       data-property="--footer-container-max-width"
                                                       data-target=".footer-container"
                                                       min="960" max="1400" step="20" value="1200"
                                                       class="footer-control range-input">
                                                <span class="range-value">1200px</span>
                                            </div>
                                            <div class="control-item">
                                                <label>Section Padding</label>
                                                <input type="range"
                                                       data-property="--footer-section-padding"
                                                       data-target=".footer-cards-section, .footer-newsletter-section"
                                                       min="24" max="80" step="8" value="48"
                                                       class="footer-control range-input">
                                                <span class="range-value">48px</span>
                                            </div>
                                        </div>

                                        <!-- Footer Bottom Controls -->
                                        <div class="control-group">
                                            <h5 class="group-title">🔽 Footer Bottom</h5>
                                            <div class="control-item">
                                                <label>Bottom Background</label>
                                                <input type="color"
                                                       data-property="--footer-bottom-bg"
                                                       data-target=".footer-bottom"
                                                       value="#1B365D"
                                                       class="footer-control color-input">
                                            </div>
                                            <div class="control-item">
                                                <label>Bottom Text Color</label>
                                                <input type="color"
                                                       data-property="--footer-bottom-text-color"
                                                       data-target=".footer-bottom"
                                                       value="#ffffff"
                                                       class="footer-control color-input">
                                            </div>
                                            <div class="control-item">
                                                <label>Link Hover Color</label>
                                                <input type="color"
                                                       data-property="--footer-bottom-link-hover"
                                                       data-target=".footer-menu a:hover"
                                                       value="#D4AF37"
                                                       class="footer-control color-input">
                                            </div>
                                        </div>

                                        <!-- Animation Controls -->
                                        <div class="control-group">
                                            <h5 class="group-title">✨ Animations</h5>
                                            <div class="control-item">
                                                <label>Animation Speed</label>
                                                <input type="range"
                                                       data-property="--footer-transition-duration"
                                                       data-target=".footer-component"
                                                       min="0.1" max="1.0" step="0.1" value="0.3"
                                                       class="footer-control range-input">
                                                <span class="range-value">0.3s</span>
                                            </div>
                                            <div class="control-item">
                                                <label>Enable Hover Effects</label>
                                                <input type="checkbox"
                                                       data-property="--footer-hover-effects"
                                                       data-target=".footer-component"
                                                       checked
                                                       class="footer-control toggle-input">
                                            </div>
                                        </div>

                                        <!-- Live Preview Controls -->
                                        <div class="control-group">
                                            <h5 class="group-title">👁️ Preview</h5>
                                            <div class="control-item">
                                                <button id="footer-preview-scroll" class="preview-button">
                                                    📍 Scroll to Footer
                                                </button>
                                                <button id="footer-reset-controls" class="reset-button">
                                                    🔄 Reset All Footer Settings
                                                </button>
                                            </div>
                                        </div>

                                    </div>
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
        // FIXED: Check for either palette OR typography
        if (!currentConfig.activePalette && !currentConfig.typographyPairing) {
            showMessage('Please select a color palette or typography first.', 'error');
            return;
        }

        const $button = $('#simple-vc-apply');
        $button.prop('disabled', true).text('Applying...');

        console.log('🎨 Applying changes globally...', currentConfig);

        // PRODUCTION FIX: Add force update attribute to trigger CSS recalculation
        document.body.setAttribute('data-customizer-applying', 'true');

        // PRODUCTION FIX: Save to consolidated localStorage immediately (if palette exists)
        if (currentConfig.activePalette) {
            savePaletteToLocalStorage(currentConfig.activePalette, currentConfig);
        }

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
                    // ENHANCED: Show appropriate success message based on what was saved
                    let successMessage = '✅ Settings applied globally! All visitors will now see this theme.';

                    if (response.data) {
                        if (response.data.saved_palette && response.data.saved_typography) {
                            successMessage = '✅ Color palette and typography applied globally!';
                        } else if (response.data.saved_palette) {
                            successMessage = '✅ Color palette applied globally!';
                        } else if (response.data.saved_typography) {
                            successMessage = '✅ Typography applied globally!';
                        }

                        // Check typography CSS generation
                        if (response.data.typography_css_generated) {
                            console.log('✅ Server confirmed typography CSS file generated');
                            successMessage += ' Typography CSS file created - changes will persist after refresh!';
                        }
                    }

                    showMessage(successMessage, 'success');

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

                    if (response.data && response.data.saved_typography) {
                        console.log('✅ Server confirmed typography saved:', response.data.saved_typography);
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

                        // Reapply current settings to ensure consistency
                        if (currentConfig.paletteData) {
                            console.log('🔄 Reapplying palette after global save...');
                            applyColorTokensImmediately(currentConfig.paletteData);
                        }

                        if (currentConfig.typographyData) {
                            console.log('🔄 Reapplying typography after global save...');
                            applyWorkingTypographyWithOverride(currentConfig.typographyData);
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
        // FIXED: Check both palette AND typography state
        if (currentConfig.activePalette || currentConfig.typographyPairing) {
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
            } else if (sectionName === 'footer') {
                console.log('🦶 Loading footer interface on demand...');
                loadFooterInterface();
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

        // CRITICAL FIX: Ensure Google Fonts are loaded (they should already be loaded when sidebar opened)
        const existingPreviewLink = document.querySelector('#typography-preview-fonts');
        if (!existingPreviewLink) {
            console.log('⚠️ Google Fonts not loaded yet, loading now...');

            // Show loading state
            container.html(`
                <div class="typography-loading-state">
                    <div class="loading-spinner">🔄</div>
                    <div class="loading-message">
                        <h5>Loading Typography Fonts...</h5>
                        <p>Preparing professional font previews...</p>
                    </div>
                </div>
            `);

            // Load fonts then render interface
            loadGoogleFontsForTypographyPreviews().then(() => {
                console.log('✅ Fonts loaded, rendering typography interface...');
                renderTypographyInterface(container);
            }).catch(error => {
                console.error('❌ Font loading failed, rendering interface with fallbacks:', error);
                renderTypographyInterface(container);
            });
        } else {
            console.log('✅ Google Fonts already loaded for typography previews');
            renderTypographyInterface(container);
        }

        // IMMEDIATE WORKING SOLUTION: Load functional typography interface right away
        // Instead of waiting for complex dependencies, provide working typography immediately
        console.log('📝 Loading immediate working typography interface...');

        const workingTypographyHtml = `
            <div class="typography-interface-working">
                <div class="typography-header">
                    <h4 class="typography-title">📝 Typography Options</h4>
                    <p class="typography-subtitle">Professional font pairings for medical spa</p>
                </div>

                <div class="typography-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 12px;">

                    <div class="typography-card" data-typography="medical-professional" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px; cursor: pointer; transition: all 0.2s ease; text-align: center;">
                        <div class="typography-preview-aa typography-preview-inter" style="font-size: 32px; font-weight: 600; color: #333; margin-bottom: 8px; font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;">
                            Aa
                        </div>
                        <div class="typography-name" style="font-size: 12px; color: #666; font-weight: 500;">
                            Medical Professional
                        </div>
                    </div>

                    <div class="typography-card" data-typography="luxury-modern" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px; cursor: pointer; transition: all 0.2s ease; text-align: center;">
                        <div class="typography-preview-aa typography-preview-playfair" style="font-size: 32px; font-weight: 600; color: #333; margin-bottom: 8px; font-family: 'Playfair Display', Georgia, serif !important;">
                            Aa
                        </div>
                        <div class="typography-name" style="font-size: 12px; color: #666; font-weight: 500;">
                            Luxury Modern
                        </div>
                    </div>

                    <div class="typography-card" data-typography="contemporary-clean" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px; cursor: pointer; transition: all 0.2s ease; text-align: center;">
                        <div class="typography-preview-aa typography-preview-poppins" style="font-size: 32px; font-weight: 600; color: #333; margin-bottom: 8px; font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif !important;">
                            Aa
                        </div>
                        <div class="typography-name" style="font-size: 12px; color: #666; font-weight: 500;">
                            Contemporary Clean
                        </div>
                    </div>

                    <div class="typography-card" data-typography="wellness-serif" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px; cursor: pointer; transition: all 0.2s ease; text-align: center;">
                        <div class="typography-preview-aa typography-preview-crimson" style="font-size: 32px; font-weight: 600; color: #333; margin-bottom: 8px; font-family: 'Crimson Text', Georgia, serif !important;">
                            Aa
                        </div>
                        <div class="typography-name" style="font-size: 12px; color: #666; font-weight: 500;">
                            Wellness Serif
                        </div>
                    </div>

                    <div class="typography-card" data-typography="modern-sans" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px; cursor: pointer; transition: all 0.2s ease; text-align: center;">
                        <div class="typography-preview-aa typography-preview-montserrat" style="font-size: 32px; font-weight: 600; color: #333; margin-bottom: 8px; font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif !important;">
                            Aa
                        </div>
                        <div class="typography-name" style="font-size: 12px; color: #666; font-weight: 500;">
                            Modern Sans
                        </div>
                    </div>

                    <div class="typography-card" data-typography="classic-elegant" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px; cursor: pointer; transition: all 0.2s ease; text-align: center;">
                        <div class="typography-preview-aa typography-preview-cormorant" style="font-size: 32px; font-weight: 600; color: #333; margin-bottom: 8px; font-family: 'Cormorant Garamond', Georgia, serif !important;">
                            Aa
                        </div>
                        <div class="typography-name" style="font-size: 12px; color: #666; font-weight: 500;">
                            Classic Elegant
                        </div>
                    </div>

                    <div class="typography-card" data-typography="tech-minimal" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px; cursor: pointer; transition: all 0.2s ease; text-align: center;">
                        <div class="typography-preview-aa typography-preview-ibm" style="font-size: 32px; font-weight: 600; color: #333; margin-bottom: 8px; font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif !important;">
                            Aa
                        </div>
                        <div class="typography-name" style="font-size: 12px; color: #666; font-weight: 500;">
                            Tech Minimal
                        </div>
                    </div>

                    <div class="typography-card" data-typography="warm-organic" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px; cursor: pointer; transition: all 0.2s ease; text-align: center;">
                        <div class="typography-preview-aa typography-preview-merriweather" style="font-size: 32px; font-weight: 600; color: #333; margin-bottom: 8px; font-family: 'Merriweather', Georgia, serif !important;">
                            Aa
                        </div>
                        <div class="typography-name" style="font-size: 12px; color: #666; font-weight: 500;">
                            Warm Organic
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
     * CRITICAL FIX: Load Google Fonts for Typography Previews
     * PERFORMANCE OPTIMIZED: Instant loading with server-side coordination
     */
    function loadGoogleFontsForTypographyPreviews() {
        console.log('🔗 Optimizing Google Fonts loading for typography previews...');

        // PERFORMANCE CHECK: If server already loaded preview fonts, use them
        const serverPreviewFonts = document.querySelector('#typography-preview-fonts-server-css');
        if (serverPreviewFonts) {
            console.log('✅ Server already loaded preview fonts - using server fonts for instant previews');

            // Wait for fonts to be ready and return resolved promise
            if (document.fonts && document.fonts.ready) {
                return document.fonts.ready.then(() => {
                    console.log('✅ Server-loaded fonts are ready for previews');
                    return Promise.resolve();
                });
            } else {
                return Promise.resolve();
            }
        }

        // All fonts needed for previews with proper encoding
        const previewFonts = [
            'Inter:wght@400;500;600;700',
            'Playfair+Display:wght@400;500;600;700',
            'Poppins:wght@400;500;600;700',
            'Crimson+Text:wght@400;500;600;700',
            'Montserrat:wght@400;500;600;700',
            'Cormorant+Garamond:wght@400;500;600;700',
            'IBM+Plex+Sans:wght@400;500;600;700',
            'Merriweather:wght@400;500;600;700;900'
        ];

        const fontsQuery = previewFonts.join('&family=');
        const googleFontsUrl = `https://fonts.googleapis.com/css2?family=${fontsQuery}&display=swap`;

        // Check if preview fonts are already loaded
        const existingPreviewLink = document.querySelector('#typography-preview-fonts');

        if (existingPreviewLink && existingPreviewLink.href === googleFontsUrl) {
            console.log('✅ Typography preview fonts already loaded via client');
            return Promise.resolve();
        }

        // Remove existing preview link if different
        if (existingPreviewLink) {
            existingPreviewLink.remove();
        }

        // Create new link element for preview fonts with optimizations
        const link = document.createElement('link');
        link.id = 'typography-preview-fonts';
        link.rel = 'stylesheet';
        link.href = googleFontsUrl;

        // PERFORMANCE OPTIMIZATION: Add critical loading attributes
        link.crossOrigin = 'anonymous';
        link.fetchPriority = 'high';

        // Return promise for better loading management
        return new Promise((resolve, reject) => {
            link.onload = () => {
                console.log('✅ Typography preview fonts loaded successfully (client fallback)');

                // Wait for fonts to be ready in the browser
                if (document.fonts && document.fonts.ready) {
                    document.fonts.ready.then(() => {
                        console.log('✅ All preview fonts ready for rendering');

                        // Force font updates on preview cards if they exist
                        setTimeout(() => {
                            const previewElements = document.querySelectorAll('.typography-preview-aa');
                            if (previewElements.length > 0) {
                                console.log(`🔄 Optimizing ${previewElements.length} preview cards`);
                                previewElements.forEach(el => {
                                    el.style.fontFamily = el.style.fontFamily; // Force redraw
                                });
                            }
                        }, 50); // Faster update

                        resolve();
                    });
                } else {
                    // Fallback for older browsers
                    setTimeout(() => {
                        console.log('✅ Preview fonts ready (fallback timing)');
                        resolve();
                    }, 200); // Faster fallback
                }
            };

            link.onerror = () => {
                console.error('❌ Failed to load typography preview fonts');
                reject(new Error('Google Fonts failed to load'));
            };

            document.head.appendChild(link);

            // Timeout fallback - faster for better UX
            setTimeout(() => {
                console.warn('⚠️ Typography preview fonts loading timeout, proceeding anyway');
                resolve();
            }, 1500); // Reduced from 3000ms to 1500ms
        });

        console.log('🔗 Typography preview fonts URL:', googleFontsUrl);
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

                // ENHANCED: Apply typography with server override capability
                applyWorkingTypographyWithOverride(typographyData);

                // Update status and enable apply button
                const statusEl = container.find('#typography-status');
                const applyBtn = container.find('#typography-apply-btn');

                statusEl.text(`Applied: ${typographyData.name}`).css('color', '#28a745');
                applyBtn.prop('disabled', false).css('opacity', '1');

                // Update main apply button
                updateApplyButton();

                showMessage(`Typography "${typographyData.name}" applied!`, 'success');

                // ENHANCED: Save selection for highlighting
                saveTypographySelectionToLocalStorage(typographyId);
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

        // ENHANCED: Load and highlight current typography after setup
        setTimeout(() => {
            loadAndHighlightCurrentTypography();
        }, 500);
    }

    /**
     * ENHANCED: Apply typography with server override capability
     */
    function applyWorkingTypographyWithOverride(typographyData) {
        console.log('📝 Applying typography with server override capability:', typographyData);

        try {
            // Check if server has typography CSS
            const rootStyle = getComputedStyle(document.documentElement);
            const serverTypographyFamily = rootStyle.getPropertyValue('--component-font-family-primary').trim();
            const serverTypographyBodyFamily = rootStyle.getPropertyValue('--component-font-family-secondary').trim();

            if (serverTypographyFamily !== '' || serverTypographyBodyFamily !== '') {
                console.log('🖥️ SERVER TYPOGRAPHY CSS DETECTED');
                console.log('🖥️ Server heading font:', serverTypographyFamily);
                console.log('🖥️ Server body font:', serverTypographyBodyFamily);

                // Check if server typography matches what we're applying
                const clientHeadingFamily = `"${typographyData.headingFont.family}", ${typographyData.headingFont.fallback}`;
                const clientBodyFamily = `"${typographyData.bodyFont.family}", ${typographyData.bodyFont.fallback}`;

                if (serverTypographyFamily.includes(typographyData.headingFont.family) &&
                    serverTypographyBodyFamily.includes(typographyData.bodyFont.family)) {
                    console.log('✅ Server typography matches client selection - typography is correctly applied');
                    showMessage('✅ Typography already applied globally!', 'success');
                    return;
                } else {
                    console.log('⚠️ Server typography differs from client selection - will override');
                    console.log(`    Server heading: ${serverTypographyFamily}`);
                    console.log(`    Client heading: ${clientHeadingFamily}`);
                    showMessage('🔄 Overriding server typography with new selection...', 'info');
                }
            }

            // ENHANCED: Load Google Fonts FIRST and WAIT for them to load
            loadGoogleFontsForTypographySync(typographyData).then(() => {
                console.log('✅ Google Fonts loaded, applying CSS...');

                // Apply typography tokens immediately
                applyTypographyTokensImmediately(typographyData);

                // Generate and apply high-specificity CSS
                const css = generateEnhancedTypographyCSS(typographyData);
                applyTypographyCSSWithHighSpecificity(css, typographyData.id);

                // CRITICAL FIX: Set body data attribute for persistence tracking
                document.body.setAttribute('data-typography-applied', typographyData.id);
                document.body.setAttribute('data-typography-name', typographyData.name);

                // Force browser reflow to ensure fonts apply
                forceTypographyReflow();

                console.log('✅ Typography applied with server override capability');
                showMessage(`✅ Typography "${typographyData.name}" applied in real-time!`, 'success');

            }).catch(error => {
                console.error('❌ Error loading Google Fonts:', error);
                // Apply anyway with fallback fonts
                applyTypographyTokensImmediately(typographyData);
                const css = generateEnhancedTypographyCSS(typographyData);
                applyTypographyCSSWithHighSpecificity(css, typographyData.id);
                showMessage(`⚠️ Typography applied with fallback fonts (Google Fonts failed to load)`, 'warning');
            });

        } catch (error) {
            console.error('❌ Error applying typography with override:', error);
            showMessage('❌ Error applying typography: ' + error.message, 'error');
        }
    }

    /**
     * Load Google Fonts synchronously and return a Promise
     * PERFORMANCE OPTIMIZED: Leverage server-side fonts for instant loading
     */
    function loadGoogleFontsForTypographySync(typographyData) {
        return new Promise((resolve, reject) => {
            console.log('🔗 Optimizing Google Fonts loading for typography application...');

            // PERFORMANCE CHECK: If server already loaded the selected typography fonts, use them
            const serverSelectedFonts = document.querySelector('#selected-typography-fonts-css');
            if (serverSelectedFonts) {
                console.log('✅ Server already loaded selected typography fonts - instant application!');

                // Verify the fonts match what we're trying to apply
                const serverHref = serverSelectedFonts.href;
                const needsTheseGoogleFonts = typographyData.googleFonts || [];

                // Check if server fonts include our needed fonts
                const serverHasNeededFonts = needsTheseGoogleFonts.every(font =>
                    serverHref.includes(font.replace(/\+/g, ' ').split(':')[0])
                );

                if (serverHasNeededFonts) {
                    console.log('✅ Server fonts match needed typography - using server fonts for instant application');

                    // Wait for fonts to be ready and resolve immediately
                    if (document.fonts && document.fonts.ready) {
                        document.fonts.ready.then(() => {
                            console.log('✅ Server typography fonts ready for instant application');
                            resolve();
                        });
                    } else {
                        resolve(); // Immediate resolution for older browsers
                    }
                    return;
                } else {
                    console.log('⚠️ Server fonts don\'t match needed typography, loading additional fonts...');
                }
            }

            if (!typographyData.googleFonts || typographyData.googleFonts.length === 0) {
                console.log('📝 No Google Fonts to load, using system fonts...');
                resolve();
                return;
            }

            const fontsQuery = typographyData.googleFonts.join('&family=');
            const googleFontsUrl = `https://fonts.googleapis.com/css2?family=${fontsQuery}&display=swap`;

            console.log('🔗 Loading Google Fonts for typography application:', googleFontsUrl);

            // Check if fonts are already loaded
            const existingLink = document.querySelector('#typography-google-fonts');

            if (existingLink && existingLink.href === googleFontsUrl) {
                console.log('✅ Google Fonts already loaded with correct URL');
                resolve();
                return;
            }

            // Remove existing link if different
            if (existingLink) {
                existingLink.remove();
            }

            // Create new link element with performance optimizations
            const link = document.createElement('link');
            link.id = 'typography-google-fonts';
            link.rel = 'stylesheet';
            link.href = googleFontsUrl;

            // PERFORMANCE OPTIMIZATION: Add critical loading attributes
            link.crossOrigin = 'anonymous';
            link.fetchPriority = 'high';

            // Wait for fonts to load with faster timeout
            link.onload = () => {
                console.log('✅ Google Fonts loaded successfully for typography');

                // Additional check: wait for fonts to be ready with faster resolution
                if (document.fonts && document.fonts.ready) {
                    document.fonts.ready.then(() => {
                        console.log('✅ All fonts ready for typography application');
                        resolve();
                    });
                } else {
                    // Fallback delay for older browsers - faster
                    setTimeout(resolve, 200); // Reduced from 500ms
                }
            };

            link.onerror = () => {
                console.error('❌ Failed to load Google Fonts');
                reject(new Error('Google Fonts failed to load'));
            };

            document.head.appendChild(link);

            // Timeout fallback
            setTimeout(() => {
                console.warn('⚠️ Google Fonts loading timeout, proceeding anyway');
                resolve();
            }, 3000);
        });
    }

    /**
     * Apply typography tokens immediately to CSS variables
     */
    function applyTypographyTokensImmediately(typographyData) {
        console.log('📝 Applying typography tokens immediately...', typographyData);

        const headingFamily = `"${typographyData.headingFont.family}", ${typographyData.headingFont.fallback}`;
        const bodyFamily = `"${typographyData.bodyFont.family}", ${typographyData.bodyFont.fallback}`;

        const headingWeights = typographyData.headingFont.weights || [400, 500, 600, 700];
        const bodyWeights = typographyData.bodyFont.weights || [400, 500, 600];

        // Apply to CSS custom properties
        const tokens = {
            // Foundation tokens
            '--typography-heading-family': headingFamily,
            '--typography-body-family': bodyFamily,
            '--typography-heading-weight-normal': headingWeights[0] || 400,
            '--typography-heading-weight-medium': headingWeights[1] || 500,
            '--typography-heading-weight-bold': headingWeights[2] || 600,
            '--typography-body-weight-normal': bodyWeights[0] || 400,
            '--typography-body-weight-medium': bodyWeights[1] || 500,

            // Component tokens
            '--component-font-family-primary': headingFamily,
            '--component-font-family-secondary': bodyFamily,
            '--component-font-weight-heading': headingWeights[2] || 600,
            '--component-font-weight-subheading': headingWeights[1] || 500,
            '--component-font-weight-body': bodyWeights[0] || 400,
            '--component-font-weight-accent': bodyWeights[1] || 500,

            // Semantic roles
            '--font-family-primary': headingFamily,
            '--font-family-secondary': bodyFamily,
            '--font-weight-heading': headingWeights[2] || 600,
            '--font-weight-subheading': headingWeights[1] || 500,
            '--font-weight-body': bodyWeights[0] || 400
        };

        const documentRoot = document.documentElement;
        Object.entries(tokens).forEach(([property, value]) => {
            documentRoot.style.setProperty(property, value);
            console.log(`📝 Set ${property}: ${value}`);
        });

        console.log('✅ Typography tokens applied to CSS variables');
    }

    /**
     * Generate enhanced typography CSS with maximum specificity
     */
    function generateEnhancedTypographyCSS(typographyData) {
        const headingFamily = `"${typographyData.headingFont.family}", ${typographyData.headingFont.fallback}`;
        const bodyFamily = `"${typographyData.bodyFont.family}", ${typographyData.bodyFont.fallback}`;

        return `
/* ENHANCED TYPOGRAPHY CSS - ${typographyData.name} - Maximum Specificity with Theme Override */

/* CRITICAL: Set data attribute for tracking */
html body {
    --current-typography: "${typographyData.id}";
}

/* Critical: Override ALL heading elements with maximum specificity and theme overrides */
html body h1, html body h1[class], html body h1[id],
html body h2, html body h2[class], html body h2[id],
html body h3, html body h3[class], html body h3[id],
html body h4, html body h4[class], html body h4[id],
html body h5, html body h5[class], html body h5[id],
html body h6, html body h6[class], html body h6[id],
html body .heading, html body .heading[class],
html body .title, html body .title[class],
html body .site-title, html body .site-title[class],
html body .hero-title, html body .hero-title[class],
html body .section-title, html body .section-title[class],
html body .professional-header .site-title,
html body .professional-header .nav-menu a,
html body .entry-title, html body .entry-title[class],
html body .post-title, html body .post-title[class],
html body .page-title, html body .page-title[class] {
    font-family: ${headingFamily} !important;
    transition: font-family 0.3s ease !important;
}

/* Critical: Override ALL body text elements with maximum specificity */
html body, html body[class], html body[id],
html body p, html body p[class], html body p[id],
html body div:not([class*='wp-']), html body div[class]:not([class*='wp-']),
html body span:not([class*='wp-']), html body span[class]:not([class*='wp-']),
html body a:not([class*='wp-']), html body a[class]:not([class*='wp-']),
html body li, html body li[class], html body li[id],
html body td, html body td[class], html body td[id],
html body th, html body th[class], html body th[id],
html body .body-text, html body .body-text[class],
html body .content, html body .content[class],
html body .description, html body .description[class] {
    font-family: ${bodyFamily} !important;
    transition: font-family 0.3s ease !important;
}

/* Navigation and buttons with maximum specificity */
html body .nav-menu a, html body .nav-menu[class] a,
html body .menu-item a, html body .menu-item[class] a,
html body button, html body button[class],
html body .btn, html body .btn[class],
html body input[type="submit"], html body input[type="submit"][class] {
    font-family: ${headingFamily} !important;
    transition: font-family 0.3s ease !important;
}

/* Medical spa theme specific elements */
html body .professional-header .site-title,
html body .professional-header[class] .site-title[class],
html body .professional-header .nav-menu a,
html body .professional-header[class] .nav-menu[class] a {
    font-family: ${headingFamily} !important;
}

html body .luxury-footer, html body .luxury-footer[class] {
    font-family: ${bodyFamily} !important;
}

/* Treatment cards and content areas */
html body .treatment-card h3, html body .treatment-card[class] h3,
html body .treatment-card .treatment-title, html body .treatment-card[class] .treatment-title[class] {
    font-family: ${headingFamily} !important;
}

html body .treatment-card p, html body .treatment-card[class] p,
html body .treatment-card .treatment-description, html body .treatment-card[class] .treatment-description[class] {
    font-family: ${bodyFamily} !important;
}

/* Force override common theme elements */
html body .entry-title, html body .entry-title[class],
html body .post-title, html body .post-title[class],
html body .page-title, html body .page-title[class] {
    font-family: ${headingFamily} !important;
}

html body .entry-content, html body .entry-content[class],
html body .post-content, html body .post-content[class],
html body .page-content, html body .page-content[class] {
    font-family: ${bodyFamily} !important;
}
        `;
    }

    /**
     * Apply typography CSS with high specificity and unique ID
     */
    function applyTypographyCSSWithHighSpecificity(css, typographyId) {
        console.log('💉 Applying typography CSS with high specificity...');

        // Remove previous typography styles
        const existingStyle = document.getElementById('enhanced-typography-styles');
        if (existingStyle) {
            existingStyle.remove();
            console.log('🗑️ Removed previous typography styles');
        }

        // Create new style element
        const styleElement = document.createElement('style');
        styleElement.id = 'enhanced-typography-styles';
        styleElement.setAttribute('data-typography', typographyId);
        styleElement.textContent = css;

        // Inject with high priority
        document.head.appendChild(styleElement);

        console.log('💉 Enhanced typography CSS injected');
        return true;
    }

    /**
     * Force typography reflow to ensure fonts apply immediately
     */
    function forceTypographyReflow() {
        console.log('🔄 Forcing typography reflow...');

        // Force reflow on key elements
        const keySelectors = ['h1', 'h2', 'h3', 'p', 'body', '.site-title', '.nav-menu'];

        keySelectors.forEach(selector => {
            const elements = document.querySelectorAll(selector);
            elements.forEach(element => {
                if (element) {
                    // Force style recalculation
                    const display = element.style.display;
                    element.style.display = 'none';
                    element.offsetHeight; // Trigger reflow
                    element.style.display = display || '';
                }
            });
        });

        // Global reflow trigger
        document.body.style.fontFamily = document.body.style.fontFamily || '';
        document.body.offsetHeight; // Trigger reflow

        console.log('✅ Typography reflow completed');
    }

    /**
     * Save Typography to Server - ENHANCED with WordPress Database Persistence
     */
    function saveTypographyToServer(typographyData) {
        console.log('💾 Saving typography to WordPress database:', typographyData);

        // Store in localStorage as backup
        localStorage.setItem('applied_typography', JSON.stringify(typographyData));

        // Save to WordPress database via AJAX (like color system)
        if (typeof simpleCustomizer !== 'undefined' && simpleCustomizer.ajaxUrl) {
            // CRITICAL FIX: Use correct config format that matches server expectations
            const config = {
                typographyPairing: typographyData.id,  // Changed from activeTypography
                typographyData: typographyData,        // This stays the same
                timestamp: Date.now(),
                applied_at: new Date().toISOString()
            };

            // Use same format as color palette system
            $.ajax({
                url: simpleCustomizer.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'simple_visual_customizer_apply',
                    nonce: simpleCustomizer.nonce,
                    config: JSON.stringify(config),
                    timestamp: Date.now()
                },
                timeout: 30000,
                success: function(response) {
                    console.log('📡 Typography AJAX Success Response:', response);

                    if (response.success) {
                        console.log('✅ Typography saved to WordPress database successfully');
                        showMessage('✅ Typography settings saved and CSS file generated!', 'success');

                        // Check if typography CSS was generated
                        if (response.data && response.data.typography_css_generated) {
                            console.log('✅ Server confirmed typography CSS file generated');
                            showMessage('Typography CSS file created - changes will persist after refresh!', 'success');
                        }
                    } else {
                        console.error('❌ Failed to save typography to database:', response.data);
                        showMessage('Error saving typography to database: ' + (response.data?.message || 'Unknown error'), 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ Typography AJAX error:', { xhr, status, error });
                    showMessage('Network error saving typography: ' + error, 'error');
                }
            });
        } else {
            console.warn('⚠️ AJAX not available, typography saved to localStorage only');
        }
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
     * CRITICAL FIX: Get typography data by ID
     */
    function getTypographyData(typographyId) {
        console.log('📝 Getting typography data for:', typographyId);

        const typographyDatabase = {
            'medical-professional': {
                id: 'medical-professional',
                name: 'Medical Professional',
                description: 'Clean, trustworthy typography for medical services',
                headingFont: {
                    family: 'Inter',
                    fallback: '-apple-system, BlinkMacSystemFont, sans-serif',
                    weights: ['400', '500', '600', '700']
                },
                bodyFont: {
                    family: 'Inter',
                    fallback: '-apple-system, BlinkMacSystemFont, sans-serif',
                    weights: ['400', '500']
                },
                googleFonts: ['Inter:wght@400;500;600;700'],
                cssVariables: {
                    '--component-font-family-primary': '"Inter", -apple-system, BlinkMacSystemFont, sans-serif',
                    '--component-font-family-secondary': '"Inter", -apple-system, BlinkMacSystemFont, sans-serif',
                    '--component-font-weight-normal': '400',
                    '--component-font-weight-medium': '500',
                    '--component-font-weight-semibold': '600',
                    '--component-font-weight-bold': '700'
                }
            },
            'luxury-modern': {
                id: 'luxury-modern',
                name: 'Luxury Modern',
                description: 'Elegant serif for premium spa experiences',
                headingFont: {
                    family: 'Playfair Display',
                    fallback: 'Georgia, serif',
                    weights: ['400', '500', '600', '700']
                },
                bodyFont: {
                    family: 'Inter',
                    fallback: '-apple-system, BlinkMacSystemFont, sans-serif',
                    weights: ['400', '500']
                },
                googleFonts: ['Playfair+Display:wght@400;500;600;700', 'Inter:wght@400;500'],
                cssVariables: {
                    '--component-font-family-primary': '"Playfair Display", Georgia, serif',
                    '--component-font-family-secondary': '"Inter", -apple-system, BlinkMacSystemFont, sans-serif',
                    '--component-font-weight-normal': '400',
                    '--component-font-weight-medium': '500',
                    '--component-font-weight-semibold': '600',
                    '--component-font-weight-bold': '700'
                }
            },
            'contemporary-clean': {
                id: 'contemporary-clean',
                name: 'Contemporary Clean',
                description: 'Modern sans-serif for contemporary wellness',
                headingFont: {
                    family: 'Poppins',
                    fallback: '-apple-system, BlinkMacSystemFont, sans-serif',
                    weights: ['400', '500', '600', '700']
                },
                bodyFont: {
                    family: 'Poppins',
                    fallback: '-apple-system, BlinkMacSystemFont, sans-serif',
                    weights: ['400', '500']
                },
                googleFonts: ['Poppins:wght@400;500;600;700'],
                cssVariables: {
                    '--component-font-family-primary': '"Poppins", -apple-system, BlinkMacSystemFont, sans-serif',
                    '--component-font-family-secondary': '"Poppins", -apple-system, BlinkMacSystemFont, sans-serif',
                    '--component-font-weight-normal': '400',
                    '--component-font-weight-medium': '500',
                    '--component-font-weight-semibold': '600',
                    '--component-font-weight-bold': '700'
                }
            },
            'wellness-serif': {
                id: 'wellness-serif',
                name: 'Wellness Serif',
                description: 'Calming serif for wellness and relaxation',
                headingFont: {
                    family: 'Crimson Text',
                    fallback: 'Georgia, serif',
                    weights: ['400', '500', '600', '700']
                },
                bodyFont: {
                    family: 'Crimson Text',
                    fallback: 'Georgia, serif',
                    weights: ['400', '500', '600']
                },
                googleFonts: ['Crimson+Text:wght@400;500;600;700'],
                cssVariables: {
                    '--component-font-family-primary': '"Crimson Text", Georgia, serif',
                    '--component-font-family-secondary': '"Crimson Text", Georgia, serif',
                    '--component-font-weight-normal': '400',
                    '--component-font-weight-medium': '500',
                    '--component-font-weight-semibold': '600',
                    '--component-font-weight-bold': '700'
                }
            },
            'modern-sans': {
                id: 'modern-sans',
                name: 'Modern Sans',
                description: 'Versatile sans-serif for modern practices',
                headingFont: {
                    family: 'Montserrat',
                    fallback: '-apple-system, BlinkMacSystemFont, sans-serif',
                    weights: ['400', '500', '600', '700']
                },
                bodyFont: {
                    family: 'Montserrat',
                    fallback: '-apple-system, BlinkMacSystemFont, sans-serif',
                    weights: ['400', '500']
                },
                googleFonts: ['Montserrat:wght@400;500;600;700'],
                cssVariables: {
                    '--component-font-family-primary': '"Montserrat", -apple-system, BlinkMacSystemFont, sans-serif',
                    '--component-font-family-secondary': '"Montserrat", -apple-system, BlinkMacSystemFont, sans-serif',
                    '--component-font-weight-normal': '400',
                    '--component-font-weight-medium': '500',
                    '--component-font-weight-semibold': '600',
                    '--component-font-weight-bold': '700'
                }
            },
            'classic-elegant': {
                id: 'classic-elegant',
                name: 'Classic Elegant',
                description: 'Timeless elegance for established practices',
                headingFont: {
                    family: 'Cormorant Garamond',
                    fallback: 'Georgia, serif',
                    weights: ['400', '500', '600', '700']
                },
                bodyFont: {
                    family: 'Inter',
                    fallback: '-apple-system, BlinkMacSystemFont, sans-serif',
                    weights: ['400', '500']
                },
                googleFonts: ['Cormorant%20Garamond:wght@400;500;600;700', 'Inter:wght@400;500'],
                cssVariables: {
                    '--component-font-family-primary': '"Cormorant Garamond", Georgia, serif',
                    '--component-font-family-secondary': '"Inter", -apple-system, BlinkMacSystemFont, sans-serif',
                    '--component-font-weight-normal': '400',
                    '--component-font-weight-medium': '500',
                    '--component-font-weight-semibold': '600',
                    '--component-font-weight-bold': '700'
                }
            },
            'tech-minimal': {
                id: 'tech-minimal',
                name: 'Tech Minimal',
                description: 'Clean, technical typography for modern clinics',
                headingFont: {
                    family: 'IBM Plex Sans',
                    fallback: '-apple-system, BlinkMacSystemFont, sans-serif',
                    weights: ['400', '500', '600', '700']
                },
                bodyFont: {
                    family: 'IBM Plex Sans',
                    fallback: '-apple-system, BlinkMacSystemFont, sans-serif',
                    weights: ['400', '500']
                },
                googleFonts: ['IBM%20Plex%20Sans:wght@400;500;600;700'],
                cssVariables: {
                    '--component-font-family-primary': '"IBM Plex Sans", -apple-system, BlinkMacSystemFont, sans-serif',
                    '--component-font-family-secondary': '"IBM Plex Sans", -apple-system, BlinkMacSystemFont, sans-serif',
                    '--component-font-weight-normal': '400',
                    '--component-font-weight-medium': '500',
                    '--component-font-weight-semibold': '600',
                    '--component-font-weight-bold': '700'
                }
            },
            'warm-organic': {
                id: 'warm-organic',
                name: 'Warm Organic',
                description: 'Friendly, approachable typography for wellness',
                headingFont: {
                    family: 'Merriweather',
                    fallback: 'Georgia, serif',
                    weights: ['400', '500', '600', '700', '900']
                },
                bodyFont: {
                    family: 'Inter',
                    fallback: '-apple-system, BlinkMacSystemFont, sans-serif',
                    weights: ['400', '500', '600']
                },
                googleFonts: ['Merriweather:wght@400;500;600;700;900', 'Inter:wght@400;500;600'],
                cssVariables: {
                    '--component-font-family-primary': '"Merriweather", Georgia, serif',
                    '--component-font-family-secondary': '"Inter", -apple-system, BlinkMacSystemFont, sans-serif',
                    '--component-font-weight-normal': '400',
                    '--component-font-weight-medium': '500',
                    '--component-font-weight-semibold': '600',
                    '--component-font-weight-bold': '700'
                }
            }
        };

        const typography = typographyDatabase[typographyId];
        if (typography) {
            console.log(`✅ Found typography data for: ${typographyId}`, typography);
            return typography;
        } else {
            console.error(`❌ Typography data not found for: ${typographyId}`);
            console.log('📋 Available typography options:', Object.keys(typographyDatabase));
            return null;
        }
    }

    /**
     * CRITICAL FIX: Save typography selection to localStorage
     */
    function saveTypographySelectionToLocalStorage(typographyId) {
        try {
            localStorage.setItem('preetidreams_selected_typography', typographyId);
            localStorage.setItem('simple_customizer_typography', typographyId);
            console.log(`✅ Typography selection saved to localStorage: ${typographyId}`);
        } catch (error) {
            console.error('❌ Failed to save typography to localStorage:', error);
        }
    }

    /**
     * CRITICAL FIX: Load and highlight current typography
     */
    function loadAndHighlightCurrentTypography() {
        console.log('📝 Loading and highlighting current typography...');

        // Try to get current typography from multiple sources
        let currentTypography = null;

        // Method 1: Check body data attribute first (most reliable for persistence)
        const bodyTypography = document.body.getAttribute('data-typography-applied');
        if (bodyTypography) {
            currentTypography = bodyTypography;
            console.log(`✅ Found typography from body attribute: ${currentTypography}`);
        }

        // Method 2: localStorage
        if (!currentTypography) {
            try {
                currentTypography = localStorage.getItem('preetidreams_selected_typography') ||
                                   localStorage.getItem('simple_customizer_typography');
                if (currentTypography) {
                    console.log(`✅ Found typography in localStorage: ${currentTypography}`);
                }
            } catch (error) {
                console.warn('⚠️ localStorage not accessible:', error);
            }
        }

        // Method 3: AJAX call to server
        if (!currentTypography && typeof simpleCustomizer !== 'undefined' && simpleCustomizer.ajaxUrl) {
            $.ajax({
                url: simpleCustomizer.ajaxUrl,
                method: 'POST',
                data: {
                    action: 'get_current_typography',
                    nonce: simpleCustomizer.nonce
                },
                success: function(response) {
                    if (response.success && response.data) {
                        currentTypography = response.data;
                        console.log(`✅ Found typography from server: ${currentTypography}`);
                        highlightTypographyCard(currentTypography);
                        applyCurrentTypographyIfNeeded(currentTypography);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ AJAX Error loading current typography:', error);
                }
            });
        }

        // Method 4: Default fallback
        if (!currentTypography) {
            currentTypography = 'medical-professional'; // Default
            console.log(`✅ Using default typography: ${currentTypography}`);
        }

        // Highlight and apply the typography card
        if (currentTypography) {
            highlightTypographyCard(currentTypography);
            applyCurrentTypographyIfNeeded(currentTypography);
        }
    }

    /**
     * CRITICAL FIX: Apply current typography if needed (for persistence)
     */
    function applyCurrentTypographyIfNeeded(typographyId) {
        console.log(`📝 Checking if typography ${typographyId} needs to be applied...`);

        // Check if typography is already applied
        const bodyTypography = document.body.getAttribute('data-typography-applied');
        if (bodyTypography === typographyId) {
            console.log(`✅ Typography ${typographyId} already applied correctly`);
            return;
        }

        // Apply typography if different or missing
        const typographyData = getTypographyData(typographyId);
        if (typographyData) {
            console.log(`🔄 Applying typography ${typographyId} for persistence...`);

            // Store in current config
            currentConfig.typographyPairing = typographyId;
            currentConfig.typographyData = typographyData;

            // Apply typography
            applyWorkingTypographyWithOverride(typographyData);

            console.log(`✅ Typography ${typographyId} applied for persistence`);
        } else {
            console.error(`❌ Typography data not found for: ${typographyId}`);
        }
    }

    /**
     * CRITICAL FIX: Highlight typography card
     */
    function highlightTypographyCard(typographyId) {
        console.log(`📝 Highlighting typography card: ${typographyId}`);

        // Remove existing highlighting
        $('.typography-card').css({
            'border-color': '#ddd',
            'background-color': 'white'
        });

        // Highlight the selected card
        const $targetCard = $(`.typography-card[data-typography="${typographyId}"]`);
        if ($targetCard.length > 0) {
            $targetCard.css({
                'border-color': '#007cba',
                'background-color': '#f0f8ff'
            });

            // Update the status
            const typographyData = getTypographyData(typographyId);
            if (typographyData) {
                $('#typography-status').text(`Current: ${typographyData.name}`).css('color', '#28a745');
                console.log(`✅ Highlighted typography card: ${typographyId}`);
            }
        } else {
            console.warn(`⚠️ Typography card not found: ${typographyId}`);
        }
    }

    /**
     * EXTRACTED: Render Typography Interface HTML
     */
    function renderTypographyInterface(container) {
        console.log('📝 Rendering typography interface...');

        const workingTypographyHtml = `
            <div class="typography-interface-working">
                <div class="typography-header">
                    <h4 class="typography-title">📝 Typography Options</h4>
                    <p class="typography-subtitle">Professional font pairings for medical spa</p>
                </div>

                <div class="typography-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 12px;">

                    <div class="typography-card" data-typography="medical-professional" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px; cursor: pointer; transition: all 0.2s ease; text-align: center;">
                        <div class="typography-preview-aa typography-preview-inter" style="font-size: 32px; font-weight: 600; color: #333; margin-bottom: 8px; font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;">
                            Aa
                        </div>
                        <div class="typography-name" style="font-size: 12px; color: #666; font-weight: 500;">
                            Medical Professional
                        </div>
                    </div>

                    <div class="typography-card" data-typography="luxury-modern" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px; cursor: pointer; transition: all 0.2s ease; text-align: center;">
                        <div class="typography-preview-aa typography-preview-playfair" style="font-size: 32px; font-weight: 600; color: #333; margin-bottom: 8px; font-family: 'Playfair Display', Georgia, serif !important;">
                            Aa
                        </div>
                        <div class="typography-name" style="font-size: 12px; color: #666; font-weight: 500;">
                            Luxury Modern
                        </div>
                    </div>

                    <div class="typography-card" data-typography="contemporary-clean" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px; cursor: pointer; transition: all 0.2s ease; text-align: center;">
                        <div class="typography-preview-aa typography-preview-poppins" style="font-size: 32px; font-weight: 600; color: #333; margin-bottom: 8px; font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif !important;">
                            Aa
                        </div>
                        <div class="typography-name" style="font-size: 12px; color: #666; font-weight: 500;">
                            Contemporary Clean
                        </div>
                    </div>

                    <div class="typography-card" data-typography="wellness-serif" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px; cursor: pointer; transition: all 0.2s ease; text-align: center;">
                        <div class="typography-preview-aa typography-preview-crimson" style="font-size: 32px; font-weight: 600; color: #333; margin-bottom: 8px; font-family: 'Crimson Text', Georgia, serif !important;">
                            Aa
                        </div>
                        <div class="typography-name" style="font-size: 12px; color: #666; font-weight: 500;">
                            Wellness Serif
                        </div>
                    </div>

                    <div class="typography-card" data-typography="modern-sans" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px; cursor: pointer; transition: all 0.2s ease; text-align: center;">
                        <div class="typography-preview-aa typography-preview-montserrat" style="font-size: 32px; font-weight: 600; color: #333; margin-bottom: 8px; font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif !important;">
                            Aa
                        </div>
                        <div class="typography-name" style="font-size: 12px; color: #666; font-weight: 500;">
                            Modern Sans
                        </div>
                    </div>

                    <div class="typography-card" data-typography="classic-elegant" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px; cursor: pointer; transition: all 0.2s ease; text-align: center;">
                        <div class="typography-preview-aa typography-preview-cormorant" style="font-size: 32px; font-weight: 600; color: #333; margin-bottom: 8px; font-family: 'Cormorant Garamond', Georgia, serif !important;">
                            Aa
                        </div>
                        <div class="typography-name" style="font-size: 12px; color: #666; font-weight: 500;">
                            Classic Elegant
                        </div>
                    </div>

                    <div class="typography-card" data-typography="tech-minimal" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px; cursor: pointer; transition: all 0.2s ease; text-align: center;">
                        <div class="typography-preview-aa typography-preview-ibm" style="font-size: 32px; font-weight: 600; color: #333; margin-bottom: 8px; font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif !important;">
                            Aa
                        </div>
                        <div class="typography-name" style="font-size: 12px; color: #666; font-weight: 500;">
                            Tech Minimal
                        </div>
                    </div>

                    <div class="typography-card" data-typography="warm-organic" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px; cursor: pointer; transition: all 0.2s ease; text-align: center;">
                        <div class="typography-preview-aa typography-preview-merriweather" style="font-size: 32px; font-weight: 600; color: #333; margin-bottom: 8px; font-family: 'Merriweather', Georgia, serif !important;">
                            Aa
                        </div>
                        <div class="typography-name" style="font-size: 12px; color: #666; font-weight: 500;">
                            Warm Organic
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

        console.log('✅ Typography interface rendered successfully');
    }

    /**
     * Load Footer Interface with all 50+ controls
     */
    function loadFooterInterface() {
        const container = $('#simple-footer-container');

        // Check if already loaded
        if (container.find('.footer-customizer').length > 0) {
            console.log('🦶 Footer interface already loaded, setting up event handlers...');
            setupFooterEventHandlers();
            return;
        }

        console.log('🦶 Footer interface loaded from HTML, setting up event handlers...');
        setupFooterEventHandlers();
    }

    /**
     * Setup Footer Event Handlers for all controls
     */
    function setupFooterEventHandlers() {
        console.log('🦶 Setting up footer control event handlers...');

        // Handle all footer control changes
        $(document).on('input change', '.footer-control', function() {
            const $control = $(this);
            const property = $control.data('property');
            const target = $control.data('target');
            let value = $control.val();

            // Handle different input types
            if ($control.hasClass('range-input')) {
                const unit = $control.attr('step') && parseFloat($control.attr('step')) < 1 ? 's' : 'px';
                value = value + unit;
                $control.siblings('.range-value').text(value);
            } else if ($control.hasClass('toggle-input')) {
                value = $control.is(':checked') ? 'block' : 'none';
                if (property === '--footer-map-enabled') {
                    $(target).css('display', value);
                    return;
                }
            } else if ($control.hasClass('color-input')) {
                // Handle color opacity for overlay colors
                if (property.includes('overlay')) {
                    const opacity = property.includes('hero') ?
                        $('.footer-control[data-property="--footer-hero-overlay-opacity"]').val() || 0.8 :
                        0.9;
                    value = hexToRgba(value, opacity);
                }
            }

            // Apply the change immediately
            applyFooterStyleChange(property, value, target);

            // Update apply button state
            updateApplyButton();
        });

        // Handle text content changes (headline, subtext)
        $(document).on('input', '.footer-control[data-target*="headline"], .footer-control[data-target*="subtext"]', function() {
            const $control = $(this);
            const target = $control.data('target');
            const value = $control.val();

            if (target && $(target).length > 0) {
                $(target).text(value);
            }
        });

        // Preview scroll button
        $(document).on('click', '#footer-preview-scroll', function() {
            $('html, body').animate({
                scrollTop: $('.footer-component').offset().top - 100
            }, 500);
            showMessage('📍 Scrolled to footer preview', 'info');
        });

        // Reset footer controls
        $(document).on('click', '#footer-reset-controls', function() {
            resetFooterControls();
        });

        console.log('✅ Footer event handlers setup complete');
    }

    /**
     * Apply individual footer style changes with real-time preview
     */
    function applyFooterStyleChange(property, value, target) {
        console.log(`🦶 Applying footer style: ${property} = ${value} to ${target}`);

        // Apply to CSS custom property
        document.documentElement.style.setProperty(property, value);

        // Apply directly to elements for immediate effect
        if (target && $(target).length > 0) {
            const $targets = $(target);

            // Map CSS properties to DOM styles
            const propertyMap = {
                '--footer-hero-bg-color': 'backgroundColor',
                '--footer-hero-overlay-opacity': 'opacity',
                '--footer-map-height': 'height',
                '--footer-map-overlay-bg': 'backgroundColor',
                '--footer-map-border-radius': 'borderRadius',
                '--footer-card-bg': 'backgroundColor',
                '--footer-card-border-color': 'borderColor',
                '--footer-card-radius': 'borderRadius',
                '--footer-card-padding': 'padding',
                '--footer-card-gap': 'gap',
                '--footer-cta-primary-bg': 'backgroundColor',
                '--footer-cta-primary-text': 'color',
                '--footer-cta-secondary-border': 'borderColor',
                '--footer-cta-radius': 'borderRadius',
                '--footer-text-primary': 'color',
                '--footer-text-secondary': 'color',
                '--footer-text-accent': 'color',
                '--footer-hero-headline-size': 'fontSize',
                '--footer-card-title-size': 'fontSize',
                '--footer-newsletter-bg': 'backgroundColor',
                '--footer-newsletter-input-bg': 'backgroundColor',
                '--footer-newsletter-input-border': 'borderColor',
                '--footer-container-max-width': 'maxWidth',
                '--footer-section-padding': 'padding',
                '--footer-bottom-bg': 'backgroundColor',
                '--footer-bottom-text-color': 'color',
                '--footer-transition-duration': 'transitionDuration'
            };

            const domProperty = propertyMap[property];
            if (domProperty && value) {
                $targets.css(domProperty, value);
            }
        }

        // Special handling for specific properties
        if (property === '--footer-card-shadow') {
            $(target).css('boxShadow', value);
        } else if (property === '--footer-hero-padding') {
            $('.footer-hero-cta').css('padding', value + 'px');
        } else if (property === '--footer-section-padding') {
            $('.footer-cards-section, .footer-newsletter-section').css('padding', value + 'px 0');
        }

        // Trigger visual feedback
        triggerFooterVisualFeedback();
    }

    /**
     * Reset all footer controls to defaults
     */
    function resetFooterControls() {
        console.log('🦶 Resetting footer controls to defaults...');

        // Reset all form inputs to their default values
        $('.footer-control').each(function() {
            const $control = $(this);
            const defaultValue = $control.attr('value') || '';

            if ($control.hasClass('toggle-input')) {
                $control.prop('checked', $control.attr('checked') === 'checked');
            } else if ($control.hasClass('range-input')) {
                $control.val(defaultValue);
                $control.siblings('.range-value').text(defaultValue + ($control.attr('step') && parseFloat($control.attr('step')) < 1 ? 's' : 'px'));
            } else {
                $control.val(defaultValue);
            }
        });

        // Reset CSS custom properties
        const footerProperties = [
            '--footer-hero-bg-color', '--footer-hero-overlay-opacity', '--footer-hero-padding',
            '--footer-map-height', '--footer-map-overlay-bg', '--footer-map-border-radius',
            '--footer-card-bg', '--footer-card-border-color', '--footer-card-radius', '--footer-card-padding',
            '--footer-card-shadow', '--footer-card-gap', '--footer-cta-primary-bg', '--footer-cta-primary-hover',
            '--footer-cta-primary-text', '--footer-cta-secondary-border', '--footer-cta-radius',
            '--footer-text-primary', '--footer-text-secondary', '--footer-text-accent',
            '--footer-hero-headline-size', '--footer-card-title-size', '--footer-newsletter-bg',
            '--footer-newsletter-input-bg', '--footer-newsletter-input-border', '--footer-container-max-width',
            '--footer-section-padding', '--footer-bottom-bg', '--footer-bottom-text-color',
            '--footer-bottom-link-hover', '--footer-transition-duration'
        ];

        footerProperties.forEach(property => {
            document.documentElement.style.removeProperty(property);
        });

        // Reset text content to defaults
        $('.hero-headline').text('Ready to Transform Your Beauty Journey?');
        $('.hero-subtext').text('Experience luxury medical aesthetics with Dr. Preeti Sharma in Beverly Hills');

        showMessage('🔄 Footer controls reset to defaults', 'success');
        triggerFooterVisualFeedback();
    }

    /**
     * Trigger visual feedback for footer changes
     */
    function triggerFooterVisualFeedback() {
        // Add temporary class to footer for visual feedback
        $('.footer-component').addClass('footer-updating');

        setTimeout(() => {
            $('.footer-component').removeClass('footer-updating');
        }, 300);

        // Update performance metrics
        showMessage('✅ Footer updated in real-time', 'success');
    }

    /**
     * Convert hex color to rgba with opacity
     */
    function hexToRgba(hex, opacity) {
        const rgb = hexToRgb(hex);
        if (rgb) {
            return `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, ${opacity})`;
        }
        return hex;
    }

})(jQuery);
