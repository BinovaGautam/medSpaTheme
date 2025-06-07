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
     * PVC-005: Initialize Live Preview System components
     */
    function initPVC005Components() {
        try {
            // Initialize Live Preview System
            if (typeof LivePreviewSystem !== 'undefined') {
                livePreviewSystem = new LivePreviewSystem({
                    debug: simpleCustomizer.debug || false,
                    updateDelay: 50 // < 100ms requirement
                });
                console.log('✅ LivePreviewSystem initialized');
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

            // Apply immediately via Live Preview System
            if (livePreviewSystem && palette) {
                console.log('🚀 Applying palette via Live Preview System...');
                livePreviewSystem.applyPalette(palette).then(() => {
                    console.log('✅ Live Preview System: Palette applied successfully');
                    showMessage('Palette applied! Changes are visible in real-time.', 'success');
                }).catch(error => {
                    console.error('❌ Live Preview System error:', error);
                    showMessage('Error applying palette: ' + error.message, 'error');
                });
            } else {
                console.warn('⚠️ Live Preview System not available or no palette data');
                console.log('Live Preview System:', livePreviewSystem);
                console.log('Palette Data:', palette);
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

        // Enable live preview mode
        if (livePreviewSystem) {
            livePreviewSystem.enablePreviewMode();
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
                    <div class="simple-vc-section">
                        <h4>Color Palettes</h4>
                        <div id="simple-color-palette-container" class="simple-color-palette-container">
                            <p>Loading color palettes...</p>
                        </div>
                    </div>

                    <div class="simple-vc-section">
                        <h4>Live Preview</h4>
                        <div id="simple-vc-status" class="simple-vc-status">
                            <span class="status-indicator">⚡ Live Preview Active</span>
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

            console.log('📋 Fallback - Palette ID:', paletteId);
            console.log('📋 Fallback - Palette Data (raw):', paletteData);

            $('.palette-item-simple').removeClass('active');
            $(this).addClass('active');

            try {
                // Get full palette data if not embedded
                const fullPaletteData = paletteData || colorSystem.getPalette(paletteId);
                console.log('📋 Fallback - Full Palette Data:', fullPaletteData);

                currentConfig = {
                    activePalette: paletteId,
                    paletteData: fullPaletteData,
                    timestamp: performance.now()
                };

                console.log('🎨 Fallback palette selected:', paletteId, fullPaletteData);
                console.log('💾 Fallback - Current config updated:', currentConfig);

                // Dispatch the same event as the main interface
                console.log('📡 Dispatching paletteInterface:paletteSelected event...');
                document.dispatchEvent(new CustomEvent('paletteInterface:paletteSelected', {
                    detail: { paletteId, paletteData: fullPaletteData }
                }));

                // Apply via Live Preview System if available
                if (livePreviewSystem && fullPaletteData) {
                    console.log('🚀 Fallback - Applying via Live Preview System...');
                    livePreviewSystem.applyPalette(fullPaletteData).then(() => {
                        console.log('✅ Fallback - Live Preview applied successfully');
                        showMessage('Palette applied! Changes are live.', 'success');
                    }).catch(error => {
                        console.error('❌ Fallback - Live Preview error:', error);
                        showMessage('Error applying palette: ' + error.message, 'error');
                    });
                } else {
                    console.warn('⚠️ Fallback - Live Preview not available');
                    console.log('Live Preview System:', livePreviewSystem);
                    console.log('Full Palette Data:', fullPaletteData);
                }

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
     * Show enhanced color preview
     */
    function showColorPreview(paletteData) {
        const existingPreview = $('#palette-preview');
        existingPreview.remove();

        if (!paletteData || !paletteData.colors) return;

        const previewHtml = `
            <div id="palette-preview" class="palette-preview">
                <h5>🎨 ${paletteData.name} Preview</h5>
                <div class="color-swatches">
                    ${Object.entries(paletteData.colors).map(([role, colorData]) => {
                        const colorValue = colorData.hex || colorData;
                        return `
                            <div class="color-swatch-detail">
                                <div class="swatch" style="background-color: ${colorValue}"></div>
                                <span class="role">${role}</span>
                            </div>
                        `;
                    }).join('')}
                </div>
                <div class="preview-status">
                    <span class="live-indicator">⚡ Live Preview Active</span>
                </div>
            </div>
        `;

        $('#simple-color-palette-container').append(previewHtml);
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

})(jQuery);
