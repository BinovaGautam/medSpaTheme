/**
 * WordPress Customizer Bridge - PVC-005 T5.4 Implementation
 * Bridge between ColorPaletteInterface and WordPress Customizer API
 *
 * Features:
 * - WordPress customize_preview_init hooks
 * - Preview query parameter handling
 * - Session state management
 * - Theme compatibility layers
 */

class WordPressCustomizerBridge {
    constructor(options = {}) {
        this.options = {
            enableDebug: options.debug || false,
            settingPrefix: 'visual_customizer_',
            autoSave: true,
            previewDelay: 100, // ms delay for preview updates
            ...options
        };

        this.isCustomizerPreview = this.detectCustomizerPreview();
        this.isCustomizerControls = this.detectCustomizerControls();
        this.livePreviewSystem = null;
        this.previewMessenger = null;
        this.currentSettings = {};

        this.init();
    }

    /**
     * Initialize the WordPress Customizer Bridge
     */
    init() {
        this.log('🔗 WordPress Customizer Bridge: Initializing...');

        if (this.isCustomizerPreview) {
            this.initPreviewMode();
        } else if (this.isCustomizerControls) {
            this.initControlsMode();
        } else {
            this.initStandaloneMode();
        }

        this.log('✅ WordPress Customizer Bridge: Ready');
    }

    /**
     * Detect if we're in WordPress Customizer preview
     */
    detectCustomizerPreview() {
        // Check for WordPress Customizer preview indicators
        return (
            (typeof wp !== 'undefined' && wp.customize && wp.customize.preview) ||
            window.location.search.includes('customize_changeset_uuid') ||
            window.location.search.includes('wp_customize=on') ||
            document.body.classList.contains('wp-customizer-unloading')
        );
    }

    /**
     * Detect if we're in WordPress Customizer controls panel
     */
    detectCustomizerControls() {
        return (
            typeof wp !== 'undefined' &&
            wp.customize &&
            wp.customize.control &&
            !wp.customize.preview
        );
    }

    /**
     * Initialize preview mode (runs in preview iframe)
     */
    initPreviewMode() {
        this.log('🖼️ Initializing WordPress Customizer Preview Mode');

        // Wait for WordPress Customizer to be ready
        if (typeof wp !== 'undefined' && wp.customize && wp.customize.preview) {
            this.setupWordPressPreview();
        } else {
            // Fallback - wait for wp object
            this.waitForWordPress(() => {
                this.setupWordPressPreview();
            });
        }

        // Initialize live preview system
        this.initLivePreviewSystem();

        // Setup preview messenger for communication
        this.initPreviewMessenger();
    }

    /**
     * Initialize controls mode (runs in customizer controls panel)
     */
    initControlsMode() {
        this.log('🎛️ Initializing WordPress Customizer Controls Mode');

        // Setup controls integration
        this.setupCustomizerControls();

        // Setup preview communication
        this.initControlsMessenger();
    }

    /**
     * Initialize standalone mode (outside customizer)
     */
    initStandaloneMode() {
        this.log('🔧 Initializing Standalone Mode');

        // Initialize basic live preview
        this.initLivePreviewSystem();

        // Apply any saved settings
        this.applySavedSettings();
    }

    /**
     * Setup WordPress Customizer Preview
     */
    setupWordPressPreview() {
        this.log('🔄 Setting up WordPress Customizer Preview integration');

        // Bind to WordPress customizer preview events
        wp.customize.preview.bind('active', () => {
            this.log('✅ WordPress Customizer Preview: Active');
            this.handlePreviewActivated();
        });

        // Listen for setting changes
        this.setupSettingBindings();

        // Send ready message
        wp.customize.preview.send('visual-customizer-ready', {
            capabilities: this.getCapabilities()
        });
    }

    /**
     * Setup WordPress Customizer Controls
     */
    setupCustomizerControls() {
        this.log('🎛️ Setting up WordPress Customizer Controls');

        // Wait for customizer ready
        wp.customize.bind('ready', () => {
            this.log('✅ WordPress Customizer Controls: Ready');
            this.registerCustomizerControls();
        });

        // Listen for preview messages
        wp.customize.previewer.bind('visual-customizer-ready', (data) => {
            this.log('📡 Preview window ready:', data);
            this.handlePreviewReady(data);
        });
    }

    /**
     * Register WordPress Customizer Controls
     */
    registerCustomizerControls() {
        // Register color palette control
        this.registerPaletteControl();

        // Register preview mode control
        this.registerPreviewModeControl();

        // Setup control event handlers
        this.setupControlEventHandlers();
    }

    /**
     * Register color palette control
     */
    registerPaletteControl() {
        const settingId = this.options.settingPrefix + 'active_palette';

        // Add setting if it doesn't exist
        if (!wp.customize.has(settingId)) {
            wp.customize.add(settingId, new wp.customize.Value('medical-clean'));
        }

        // Bind to setting changes
        wp.customize(settingId, (setting) => {
            setting.bind((value) => {
                this.handlePaletteChange(value);
            });
        });
    }

    /**
     * Register preview mode control
     */
    registerPreviewModeControl() {
        const settingId = this.options.settingPrefix + 'preview_mode';

        if (!wp.customize.has(settingId)) {
            wp.customize.add(settingId, new wp.customize.Value(false));
        }

        wp.customize(settingId, (setting) => {
            setting.bind((enabled) => {
                this.handlePreviewModeChange(enabled);
            });
        });
    }

    /**
     * Setup setting bindings for live preview
     */
    setupSettingBindings() {
        // Available palette setting
        const paletteSettingId = this.options.settingPrefix + 'active_palette';

        wp.customize(paletteSettingId, (setting) => {
            setting.bind((value) => {
                this.log(`🎨 Palette setting changed: ${value}`);
                this.applyPaletteFromSetting(value);
            });
        });

        // Preview mode setting
        const previewModeSettingId = this.options.settingPrefix + 'preview_mode';

        wp.customize(previewModeSettingId, (setting) => {
            setting.bind((enabled) => {
                this.log(`🖼️ Preview mode changed: ${enabled}`);
                if (this.livePreviewSystem) {
                    this.livePreviewSystem.togglePreviewMode(enabled);
                }
            });
        });
    }

    /**
     * Setup control event handlers
     */
    setupControlEventHandlers() {
        // Listen for color palette interface events
        document.addEventListener('paletteInterface:paletteSelected', (event) => {
            const { paletteId } = event.detail;
            this.updateCustomizerSetting('active_palette', paletteId);
        });

        // Listen for preview mode changes
        document.addEventListener('preview:modeEnabled', () => {
            this.updateCustomizerSetting('preview_mode', true);
        });

        document.addEventListener('preview:modeDisabled', () => {
            this.updateCustomizerSetting('preview_mode', false);
        });
    }

    /**
     * Initialize Live Preview System
     */
    initLivePreviewSystem() {
        if (typeof LivePreviewSystem !== 'undefined') {
            this.livePreviewSystem = new LivePreviewSystem({
                debug: this.options.enableDebug
            });

            this.log('✅ Live Preview System initialized');
        } else {
            this.log('⚠️ LivePreviewSystem not available');
        }
    }

    /**
     * Initialize Preview Messenger
     */
    initPreviewMessenger() {
        if (typeof PreviewMessenger !== 'undefined') {
            this.previewMessenger = new PreviewMessenger({
                debug: this.options.enableDebug
            });

            // Setup preview handlers
            if (this.livePreviewSystem) {
                this.previewMessenger.setupPreviewHandlers(this.livePreviewSystem);
            }

            this.log('✅ Preview Messenger initialized');
        } else {
            this.log('⚠️ PreviewMessenger not available');
        }
    }

    /**
     * Initialize Controls Messenger
     */
    initControlsMessenger() {
        if (typeof PreviewMessenger !== 'undefined') {
            this.previewMessenger = new PreviewMessenger({
                debug: this.options.enableDebug
            });

            this.log('✅ Controls Messenger initialized');
        }
    }

    /**
     * Handle palette change from WordPress setting
     */
    async applyPaletteFromSetting(paletteId) {
        try {
            // Get palette data from SemanticColorSystem
            if (typeof SemanticColorSystem !== 'undefined') {
                const colorSystem = new SemanticColorSystem();
                const paletteData = colorSystem.getPalette(paletteId);

                if (paletteData && this.livePreviewSystem) {
                    await this.livePreviewSystem.applyPalette(paletteData);
                }
            } else {
                this.log('⚠️ SemanticColorSystem not available');
            }
        } catch (error) {
            this.log('❌ Error applying palette from setting:', error);
        }
    }

    /**
     * Handle palette change from control
     */
    handlePaletteChange(paletteId) {
        this.log(`🎨 Handling palette change: ${paletteId}`);

        // Send to preview window if in controls mode
        if (this.isCustomizerControls && this.previewMessenger) {
            this.previewMessenger.applyPalette({ id: paletteId });
        }

        // Apply directly if in preview mode
        if (this.isCustomizerPreview) {
            this.applyPaletteFromSetting(paletteId);
        }
    }

    /**
     * Handle preview mode change
     */
    handlePreviewModeChange(enabled) {
        this.log(`🖼️ Handling preview mode change: ${enabled}`);

        if (this.previewMessenger) {
            if (enabled) {
                this.previewMessenger.enablePreviewMode();
            } else {
                this.previewMessenger.disablePreviewMode();
            }
        }
    }

    /**
     * Handle preview activated
     */
    handlePreviewActivated() {
        // Apply current settings
        this.applySavedSettings();

        // Enable live preview if needed
        if (this.livePreviewSystem) {
            this.livePreviewSystem.enablePreviewMode();
        }
    }

    /**
     * Handle preview ready
     */
    handlePreviewReady(data) {
        this.log('📡 Preview ready with capabilities:', data.capabilities);

        // Store preview capabilities
        this.previewCapabilities = data.capabilities;

        // Send current settings to preview
        this.syncSettingsToPreview();
    }

    /**
     * Update WordPress Customizer setting
     */
    updateCustomizerSetting(settingName, value) {
        const settingId = this.options.settingPrefix + settingName;

        if (wp.customize.has(settingId)) {
            wp.customize(settingId).set(value);
            this.log(`📝 Updated setting ${settingId}: ${value}`);
        } else {
            this.log(`⚠️ Setting ${settingId} not found`);
        }
    }

    /**
     * Get WordPress Customizer setting
     */
    getCustomizerSetting(settingName, defaultValue = null) {
        const settingId = this.options.settingPrefix + settingName;

        if (wp.customize.has(settingId)) {
            return wp.customize(settingId).get();
        }

        return defaultValue;
    }

    /**
     * Apply saved settings
     */
    applySavedSettings() {
        this.log('🔄 Applying saved settings...');

        // Get saved palette
        const savedPalette = this.getSavedSetting('active_palette', 'medical-clean');

        if (savedPalette) {
            this.applyPaletteFromSetting(savedPalette);
        }
    }

    /**
     * Get saved setting (WordPress or localStorage fallback)
     */
    getSavedSetting(settingName, defaultValue = null) {
        // Try WordPress customizer first
        if (typeof wp !== 'undefined' && wp.customize) {
            const value = this.getCustomizerSetting(settingName);
            if (value !== null) {
                return value;
            }
        }

        // Fallback to localStorage
        try {
            const stored = localStorage.getItem(this.options.settingPrefix + settingName);
            return stored ? JSON.parse(stored) : defaultValue;
        } catch (error) {
            this.log('⚠️ Error reading from localStorage:', error);
            return defaultValue;
        }
    }

    /**
     * Sync settings to preview window
     */
    syncSettingsToPreview() {
        if (!this.previewMessenger) return;

        const settings = {
            activePalette: this.getSavedSetting('active_palette'),
            previewMode: this.getSavedSetting('preview_mode', false)
        };

        // Send settings to preview
        Object.entries(settings).forEach(([key, value]) => {
            if (value !== null) {
                this.previewMessenger.sendToChild({
                    type: 'sync-setting',
                    setting: key,
                    value: value
                });
            }
        });
    }

    /**
     * Get system capabilities
     */
    getCapabilities() {
        return {
            wordPressCustomizer: typeof wp !== 'undefined' && wp.customize,
            livePreview: !!this.livePreviewSystem,
            previewMessenger: !!this.previewMessenger,
            semanticColors: typeof SemanticColorSystem !== 'undefined',
            colorPalettes: typeof ColorPaletteInterface !== 'undefined'
        };
    }

    /**
     * Wait for WordPress object to be available
     */
    waitForWordPress(callback, timeout = 5000) {
        const startTime = Date.now();

        const check = () => {
            if (typeof wp !== 'undefined' && wp.customize) {
                callback();
            } else if (Date.now() - startTime < timeout) {
                setTimeout(check, 100);
            } else {
                this.log('⚠️ WordPress object not available after timeout');
            }
        };

        check();
    }

    /**
     * Theme compatibility layer
     */
    applyThemeCompatibility() {
        // Add compatibility classes
        document.body.classList.add('visual-customizer-active');

        if (this.isCustomizerPreview) {
            document.body.classList.add('visual-customizer-preview');
        }

        // Detect theme and apply specific compatibility
        this.detectAndApplyThemeCompatibility();
    }

    /**
     * Detect and apply theme-specific compatibility
     */
    detectAndApplyThemeCompatibility() {
        // Check for common theme indicators
        const themeIndicators = {
            'twentytwentythree': '.wp-site-blocks',
            'astra': '.ast-container',
            'generatepress': '.inside-container',
            'oceanwp': '.oceanwp-theme',
            'hello-elementor': '.elementor-kit-'
        };

        Object.entries(themeIndicators).forEach(([theme, selector]) => {
            if (document.querySelector(selector)) {
                document.body.classList.add(`visual-customizer-theme-${theme}`);
                this.log(`🎨 Theme compatibility applied: ${theme}`);
            }
        });
    }

    /**
     * Utility methods
     */
    log(...args) {
        if (this.options.enableDebug) {
            console.log('[WP Customizer Bridge]', ...args);
        }
    }

    /**
     * Get bridge status
     */
    getStatus() {
        return {
            isCustomizerPreview: this.isCustomizerPreview,
            isCustomizerControls: this.isCustomizerControls,
            hasLivePreview: !!this.livePreviewSystem,
            hasPreviewMessenger: !!this.previewMessenger,
            capabilities: this.getCapabilities(),
            currentSettings: this.currentSettings
        };
    }

    /**
     * Cleanup method
     */
    destroy() {
        if (this.livePreviewSystem && typeof this.livePreviewSystem.destroy === 'function') {
            this.livePreviewSystem.destroy();
        }

        if (this.previewMessenger && typeof this.previewMessenger.destroy === 'function') {
            this.previewMessenger.destroy();
        }

        this.log('🧹 WordPress Customizer Bridge: Destroyed');
    }
}

// Auto-initialize when WordPress Customizer is detected
document.addEventListener('DOMContentLoaded', () => {
    // Only auto-initialize in WordPress context
    if (typeof wp !== 'undefined' ||
        window.location.search.includes('customize_changeset_uuid') ||
        document.body.classList.contains('wp-customizer-unloading')) {

        window.wpCustomizerBridge = new WordPressCustomizerBridge({
            debug: window.location.search.includes('debug=true')
        });
    }
});

// Export for both module and global usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = WordPressCustomizerBridge;
} else {
    window.WordPressCustomizerBridge = WordPressCustomizerBridge;
}
