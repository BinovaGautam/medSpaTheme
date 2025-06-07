/**
 * WordPress Customizer Bridge
 * Connects Visual Customizer with WordPress Customizer API
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * WordPress Customizer Bridge Class
     */
    class WordPressCustomizerBridge {
        constructor() {
            this.colorPaletteInterface = null;
            this.colorSystemManager = null;
            this.isInitialized = false;
            this.debugMode = false;

            this.init();
        }

        /**
         * Initialize the bridge
         */
        async init() {
            this.log('Initializing WordPress Customizer Bridge...');

            // Wait for WordPress customizer to be ready
            if (typeof wp !== 'undefined' && wp.customize) {
                this.setupWordPressIntegration();
            } else {
                // Wait for WordPress customizer to load
                $(document).ready(() => {
                    this.waitForCustomizer();
                });
            }
        }

        /**
         * Wait for WordPress Customizer to be available
         */
        waitForCustomizer() {
            const checkCustomizer = () => {
                if (typeof wp !== 'undefined' && wp.customize) {
                    this.setupWordPressIntegration();
                } else {
                    setTimeout(checkCustomizer, 100);
                }
            };
            checkCustomizer();
        }

        /**
         * Setup WordPress Customizer integration
         */
        setupWordPressIntegration() {
            this.log('Setting up WordPress Customizer integration...');

            // Load Sprint 1 components
            this.loadSprintOneComponents();

            // Setup customizer bindings
            this.setupCustomizerBindings();

            // Setup preview communication
            this.setupPreviewCommunication();

            // Setup AJAX handlers
            this.setupAJAXHandlers();

            this.isInitialized = true;
            this.log('WordPress Customizer Bridge initialized successfully');
        }

        /**
         * Load Sprint 1 components
         */
        async loadSprintOneComponents() {
            this.log('Loading Sprint 1 components...');

            try {
                // Initialize Color System Manager if available
                if (typeof ColorSystemManager !== 'undefined') {
                    this.colorSystemManager = new ColorSystemManager();
                    this.log('Color System Manager loaded');
                }

                // Initialize Color Palette Interface if available
                if (typeof ColorPaletteInterface !== 'undefined') {
                    this.colorPaletteInterface = new ColorPaletteInterface();
                    this.log('Color Palette Interface loaded');
                }

                // Wait a moment for initialization
                await this.delay(500);

            } catch (error) {
                this.log('Error loading Sprint 1 components:', error);
            }
        }

        /**
         * Setup WordPress Customizer bindings
         */
        setupCustomizerBindings() {
            this.log('Setting up customizer bindings...');

            // Bind to active palette changes
            wp.customize('visual_customizer_active_palette', (setting) => {
                setting.bind((newValue) => {
                    this.handlePaletteChange(newValue);
                });
            });

            // Bind to other customizer settings
            this.bindCustomizerSettings();
        }

        /**
         * Bind additional customizer settings
         */
        bindCustomizerSettings() {
            const settings = [
                'visual_customizer_enable_customization',
                'visual_customizer_category_filter',
                'visual_customizer_wcag_level',
                'visual_customizer_colorblind_simulation',
                'visual_customizer_accessibility_reporting'
            ];

            settings.forEach(settingId => {
                if (wp.customize(settingId)) {
                    wp.customize(settingId, (setting) => {
                        setting.bind((newValue) => {
                            this.handleSettingChange(settingId, newValue);
                        });
                    });
                }
            });
        }

        /**
         * Setup preview window communication
         */
        setupPreviewCommunication() {
            this.log('Setting up preview communication...');

            // Listen for preview window messages
            window.addEventListener('message', (event) => {
                this.handlePreviewMessage(event);
            });

            // Setup WordPress customizer preview integration
            if (wp.customize.preview) {
                wp.customize.preview.bind('active', () => {
                    this.handlePreviewActive();
                });
            }
        }

        /**
         * Setup AJAX handlers
         */
        setupAJAXHandlers() {
            this.log('Setting up AJAX handlers...');

            // Ensure we have the necessary data for AJAX calls
            if (typeof visualCustomizerPreview !== 'undefined') {
                this.ajaxData = visualCustomizerPreview;
                this.debugMode = this.ajaxData.debug || false;
            }
        }

        /**
         * Handle palette change from WordPress Customizer
         */
        async handlePaletteChange(paletteId) {
            this.log('Handling palette change:', paletteId);

            try {
                // Update Color Palette Interface if available
                if (this.colorPaletteInterface) {
                    await this.colorPaletteInterface.selectPalette(paletteId);
                }

                // Update preview window
                this.updatePreviewWindow(paletteId);

                // Send palette change to preview
                this.sendToPreview('paletteChanged', {
                    paletteId: paletteId,
                    timestamp: Date.now()
                });

            } catch (error) {
                this.log('Error handling palette change:', error);
            }
        }

        /**
         * Handle setting change from WordPress Customizer
         */
        handleSettingChange(settingId, newValue) {
            this.log('Handling setting change:', settingId, newValue);

            // Handle specific settings
            switch (settingId) {
                case 'visual_customizer_category_filter':
                    this.handleCategoryFilterChange(newValue);
                    break;
                case 'visual_customizer_colorblind_simulation':
                    this.handleColorblindSimulationChange(newValue);
                    break;
                case 'visual_customizer_accessibility_reporting':
                    this.handleAccessibilityReportingChange(newValue);
                    break;
            }

            // Send setting change to preview
            this.sendToPreview('settingChanged', {
                settingId: settingId,
                newValue: newValue,
                timestamp: Date.now()
            });
        }

        /**
         * Handle category filter change
         */
        handleCategoryFilterChange(category) {
            if (this.colorPaletteInterface) {
                this.colorPaletteInterface.filterByCategory(category);
            }
        }

        /**
         * Handle colorblind simulation change
         */
        handleColorblindSimulationChange(enabled) {
            this.sendToPreview('colorblindSimulation', {
                enabled: enabled
            });
        }

        /**
         * Handle accessibility reporting change
         */
        handleAccessibilityReportingChange(enabled) {
            if (this.colorPaletteInterface) {
                this.colorPaletteInterface.toggleAccessibilityReporting(enabled);
            }
        }

        /**
         * Update preview window with new palette
         */
        async updatePreviewWindow(paletteId) {
            try {
                const response = await this.ajaxRequest('visual_customizer_preview_palette', {
                    palette_id: paletteId
                });

                if (response.success) {
                    this.sendToPreview('updatePalette', response.data);
                }
            } catch (error) {
                this.log('Error updating preview window:', error);
            }
        }

        /**
         * Send message to preview window
         */
        sendToPreview(action, data = {}) {
            if (wp.customize.preview && wp.customize.preview.iframe && wp.customize.preview.iframe[0]) {
                const message = {
                    action: action,
                    data: data,
                    source: 'visual-customizer-bridge'
                };

                wp.customize.preview.iframe[0].contentWindow.postMessage(message, '*');
                this.log('Sent to preview:', message);
            }
        }

        /**
         * Handle messages from preview window
         */
        handlePreviewMessage(event) {
            if (event.data && event.data.source === 'visual-customizer-preview') {
                this.log('Received from preview:', event.data);

                switch (event.data.action) {
                    case 'paletteApplied':
                        this.handlePreviewPaletteApplied(event.data.data);
                        break;
                    case 'accessibilityUpdated':
                        this.handlePreviewAccessibilityUpdated(event.data.data);
                        break;
                    case 'error':
                        this.handlePreviewError(event.data.data);
                        break;
                }
            }
        }

        /**
         * Handle preview active event
         */
        handlePreviewActive() {
            this.log('Preview window is active');

            // Send current settings to preview
            const currentPalette = wp.customize('visual_customizer_active_palette').get();
            if (currentPalette) {
                this.updatePreviewWindow(currentPalette);
            }
        }

        /**
         * Handle palette applied in preview
         */
        handlePreviewPaletteApplied(data) {
            this.log('Palette applied in preview:', data);

            // Update Color Palette Interface if needed
            if (this.colorPaletteInterface && data.paletteId) {
                this.colorPaletteInterface.updateCurrentPalette(data.paletteId);
            }
        }

        /**
         * Handle accessibility updated in preview
         */
        handlePreviewAccessibilityUpdated(data) {
            this.log('Accessibility updated in preview:', data);

            // Update Color Palette Interface accessibility panel
            if (this.colorPaletteInterface && data.accessibilityScore) {
                this.colorPaletteInterface.updateAccessibilityScore(data.accessibilityScore);
            }
        }

        /**
         * Handle preview error
         */
        handlePreviewError(data) {
            this.log('Preview error:', data);
            console.error('Visual Customizer Preview Error:', data);
        }

        /**
         * Make AJAX request
         */
        async ajaxRequest(action, data = {}) {
            if (!this.ajaxData) {
                throw new Error('AJAX data not available');
            }

            const requestData = {
                action: action,
                nonce: this.ajaxData.nonce,
                ...data
            };

            try {
                const response = await $.ajax({
                    url: this.ajaxData.ajaxUrl,
                    type: 'POST',
                    data: requestData,
                    dataType: 'json'
                });

                return response;
            } catch (error) {
                this.log('AJAX request failed:', error);
                throw error;
            }
        }

        /**
         * Get available palettes via AJAX
         */
        async getAvailablePalettes(category = 'all', search = '') {
            try {
                const response = await this.ajaxRequest('visual_customizer_get_palettes', {
                    category: category,
                    search: search
                });

                return response.success ? response.data.palettes : {};
            } catch (error) {
                this.log('Error getting palettes:', error);
                return {};
            }
        }

        /**
         * Validate accessibility for palette
         */
        async validateAccessibility(paletteId) {
            try {
                const response = await this.ajaxRequest('visual_customizer_validate_accessibility', {
                    palette_id: paletteId
                });

                return response.success ? response.data : null;
            } catch (error) {
                this.log('Error validating accessibility:', error);
                return null;
            }
        }

        /**
         * Update palette via AJAX
         */
        async updatePalette(paletteId) {
            try {
                const response = await this.ajaxRequest('visual_customizer_update_palette', {
                    palette_id: paletteId
                });

                if (response.success) {
                    this.log('Palette updated successfully:', paletteId);
                    return response.data;
                } else {
                    throw new Error(response.data.message || 'Update failed');
                }
            } catch (error) {
                this.log('Error updating palette:', error);
                throw error;
            }
        }

        /**
         * Get current WordPress Customizer settings
         */
        getCurrentSettings() {
            const settings = {};

            // Get all Visual Customizer settings
            const settingIds = [
                'visual_customizer_active_palette',
                'visual_customizer_enable_customization',
                'visual_customizer_category_filter',
                'visual_customizer_wcag_level',
                'visual_customizer_colorblind_simulation',
                'visual_customizer_accessibility_reporting'
            ];

            settingIds.forEach(settingId => {
                if (wp.customize(settingId)) {
                    settings[settingId] = wp.customize(settingId).get();
                }
            });

            return settings;
        }

        /**
         * Utility: Delay function
         */
        delay(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        /**
         * Logging utility
         */
        log(...args) {
            if (this.debugMode) {
                console.log('[WordPress Customizer Bridge]', ...args);
            }
        }

        /**
         * Get bridge instance (for external access)
         */
        static getInstance() {
            if (!window.wpCustomizerBridge) {
                window.wpCustomizerBridge = new WordPressCustomizerBridge();
            }
            return window.wpCustomizerBridge;
        }
    }

    // Initialize when document is ready
    $(document).ready(function() {
        // Create global instance
        window.wpCustomizerBridge = new WordPressCustomizerBridge();

        // Make it available globally for Sprint 1 components
        window.WordPressCustomizerBridge = WordPressCustomizerBridge;
    });

    // Export for modules if needed
    if (typeof module !== 'undefined' && module.exports) {
        module.exports = WordPressCustomizerBridge;
    }

})(jQuery);
