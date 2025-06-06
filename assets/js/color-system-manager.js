/**
 * Professional Visual Customizer - Color System Manager
 * Version: 2.0.0
 * High-level management class coordinating color system operations
 *
 * CODE-GEN-WF-001 Implementation
 * Story: PVC-003 - Color System Management Class
 * Quality Gates: WordPress integration, State management, Developer APIs, Performance coordination
 */

/**
 * ColorSystemManager - High-Level Management Class
 * T3.1: State management and coordination layer
 * T3.2: WordPress integration and persistence
 * T3.3: Configuration and settings management
 * T3.4: Event coordination and notification system
 * T3.5: Performance coordination and developer APIs
 */
class ColorSystemManager {
    constructor(options = {}) {
        // Core system integration
        this.semanticColorSystem = options.semanticColorSystem || new SemanticColorSystem();

        // T3.1: State Management
        this.state = {
            currentPalette: null,
            previousPalette: null,
            isPreviewMode: false,
            isDirty: false,
            lastSaved: null,
            userPreferences: {},
            themeSettings: {},
            customizations: new Map()
        };

        // T3.2: WordPress Integration
        this.wordpress = {
            isWordPress: typeof wp !== 'undefined',
            customizer: null,
            ajaxUrl: options.ajaxUrl || '/wp-admin/admin-ajax.php',
            nonce: options.nonce || '',
            themeSlug: options.themeSlug || 'medSpaTheme',
            settingsPrefix: options.settingsPrefix || 'medSpa_colors_'
        };

        // T3.3: Configuration Management
        this.config = {
            autoSave: options.autoSave !== false,
            autoSaveDelay: options.autoSaveDelay || 2000,
            previewDelay: options.previewDelay || 500,
            enableAnalytics: options.enableAnalytics !== false,
            enableCache: options.enableCache !== false,
            maxUndoSteps: options.maxUndoSteps || 10,
            enableRealTimePreview: options.enableRealTimePreview !== false
        };

        // T3.4: Event System
        this.eventEmitter = new EventTarget();
        this.eventHandlers = new Map();
        this.deferredEvents = [];

        // T3.5: Performance Coordination
        this.performance = {
            operationQueue: [],
            batchUpdates: false,
            updateTimer: null,
            metrics: {
                totalOperations: 0,
                averageTime: 0,
                cacheHitRate: 0,
                errorCount: 0
            }
        };

        // Internal management
        this.undoStack = [];
        this.redoStack = [];
        this.isInitialized = false;
        this.initializationPromise = null;

        // Bind methods for event handling
        this.handlePaletteChange = this.handlePaletteChange.bind(this);
        this.handleAutoSave = this.handleAutoSave.bind(this);
        this.handlePerformanceMetrics = this.handlePerformanceMetrics.bind(this);

        // Auto-initialize if not explicitly disabled
        if (options.autoInit !== false) {
            this.initialize();
        }
    }

    /**
     * T3.1: Initialize the Color System Manager
     * Comprehensive initialization with error handling and recovery
     */
    async initialize() {
        if (this.isInitialized) {
            return this.initializationPromise;
        }

        this.initializationPromise = this._performInitialization();
        return this.initializationPromise;
    }

    async _performInitialization() {
        const startTime = performance.now();

        try {
            this.emit('manager:initializing', { timestamp: Date.now() });

            // Step 1: Initialize WordPress integration
            await this._initializeWordPressIntegration();

            // Step 2: Load saved settings and preferences
            await this._loadSettings();

            // Step 3: Set up event listeners
            this._setupEventListeners();

            // Step 4: Initialize semantic color system
            await this._initializeSemanticSystem();

            // Step 5: Load current palette or set default
            await this._loadCurrentPalette();

            // Step 6: Initialize performance monitoring
            this._initializePerformanceMonitoring();

            this.isInitialized = true;
            const initTime = performance.now() - startTime;

            this.emit('manager:initialized', {
                initializationTime: Math.round(initTime),
                currentPalette: this.state.currentPalette,
                wordPressIntegration: this.wordpress.isWordPress,
                timestamp: Date.now()
            });

            console.log(`✅ ColorSystemManager initialized in ${Math.round(initTime)}ms`);
            return true;

        } catch (error) {
            this.emit('manager:error', {
                type: 'initialization_failed',
                error: error.message,
                timestamp: Date.now()
            });

            console.error('❌ ColorSystemManager initialization failed:', error);
            throw error;
        }
    }

    /**
     * T3.2: WordPress Integration Setup
     * Initialize WordPress customizer and AJAX handling
     */
    async _initializeWordPressIntegration() {
        if (!this.wordpress.isWordPress) {
            console.log('ℹ️ WordPress not detected, running in standalone mode');
            return;
        }

        try {
            // Initialize WordPress Customizer integration
            if (typeof wp.customize !== 'undefined') {
                this.wordpress.customizer = wp.customize;
                this._setupCustomizerIntegration();
            }

            // Verify AJAX endpoint
            if (this.wordpress.ajaxUrl && this.wordpress.nonce) {
                await this._verifyAjaxEndpoint();
            }

            this.emit('wordpress:initialized', {
                hasCustomizer: !!this.wordpress.customizer,
                hasAjax: !!(this.wordpress.ajaxUrl && this.wordpress.nonce),
                timestamp: Date.now()
            });

        } catch (error) {
            console.warn('⚠️ WordPress integration setup failed:', error);
            // Continue without WordPress integration
        }
    }

    /**
     * T3.2: Setup WordPress Customizer Integration
     */
    _setupCustomizerIntegration() {
        const customizer = this.wordpress.customizer;

        // Register color palette setting
        const settingId = `${this.wordpress.settingsPrefix}palette`;

        if (customizer(settingId)) {
            // Bind to existing setting
            customizer(settingId, (value) => {
                value.bind((paletteId) => {
                    if (paletteId && paletteId !== this.state.currentPalette) {
                        this.switchPalette(paletteId, { source: 'customizer' });
                    }
                });
            });
        }

        // Set up live preview
        if (customizer.selectiveRefresh) {
            customizer.selectiveRefresh.bind('color-palette-changed', () => {
                this._updateLivePreview();
            });
        }
    }

    /**
     * T3.2: Verify AJAX endpoint availability
     */
    async _verifyAjaxEndpoint() {
        try {
            const response = await fetch(this.wordpress.ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    action: 'medSpa_colors_verify',
                    nonce: this.wordpress.nonce
                })
            });

            if (!response.ok) {
                throw new Error(`AJAX endpoint verification failed: ${response.status}`);
            }

            const data = await response.json();
            if (!data.success) {
                throw new Error('AJAX endpoint returned error');
            }

        } catch (error) {
            console.warn('⚠️ AJAX endpoint verification failed:', error);
            // Disable WordPress features that require AJAX
            this.config.autoSave = false;
        }
    }

    /**
     * T3.1: Load Settings and User Preferences
     */
    async _loadSettings() {
        try {
            // Load from WordPress if available
            if (this.wordpress.isWordPress && this.wordpress.customizer) {
                await this._loadWordPressSettings();
            } else {
                // Load from localStorage for standalone mode
                await this._loadLocalStorageSettings();
            }

            this.emit('settings:loaded', {
                source: this.wordpress.isWordPress ? 'wordpress' : 'localStorage',
                settings: { ...this.state.themeSettings },
                preferences: { ...this.state.userPreferences },
                timestamp: Date.now()
            });

        } catch (error) {
            console.warn('⚠️ Settings loading failed, using defaults:', error);
            this._setDefaultSettings();
        }
    }

    /**
     * T3.2: Load WordPress Settings
     */
    async _loadWordPressSettings() {
        const customizer = this.wordpress.customizer;
        const prefix = this.wordpress.settingsPrefix;

        // Load palette setting
        const paletteId = customizer(`${prefix}palette`)?.get();
        if (paletteId) {
            this.state.currentPalette = paletteId;
        }

        // Load theme settings
        const settingKeys = [
            'auto_preview',
            'enable_animations',
            'accessibility_mode',
            'color_blind_support'
        ];

        for (const key of settingKeys) {
            const setting = customizer(`${prefix}${key}`);
            if (setting) {
                this.state.themeSettings[key] = setting.get();
            }
        }
    }

    /**
     * T3.1: Load LocalStorage Settings (Standalone Mode)
     */
    async _loadLocalStorageSettings() {
        try {
            const stored = localStorage.getItem('medSpa_colorSystem_settings');
            if (stored) {
                const settings = JSON.parse(stored);
                this.state.currentPalette = settings.currentPalette || null;
                this.state.themeSettings = settings.themeSettings || {};
                this.state.userPreferences = settings.userPreferences || {};
                this.state.lastSaved = settings.lastSaved || null;
            }
        } catch (error) {
            console.warn('⚠️ LocalStorage settings corrupted, resetting:', error);
            localStorage.removeItem('medSpa_colorSystem_settings');
        }
    }

    /**
     * T3.1: Set Default Settings
     */
    _setDefaultSettings() {
        this.state.themeSettings = {
            auto_preview: true,
            enable_animations: true,
            accessibility_mode: false,
            color_blind_support: false
        };

        this.state.userPreferences = {
            preferredPaletteCategory: 'medical-clinical',
            showAdvancedOptions: false,
            enableTooltips: true
        };
    }

    /**
     * T3.4: Setup Event Listeners
     */
    _setupEventListeners() {
        // Listen to semantic color system events
        document.addEventListener('paletteChanged', this.handlePaletteChange);

        // Auto-save timer
        if (this.config.autoSave) {
            this.autoSaveTimer = setInterval(this.handleAutoSave, this.config.autoSaveDelay);
        }

        // Performance monitoring
        setInterval(this.handlePerformanceMetrics, 30000); // Every 30 seconds

        // Browser events
        window.addEventListener('beforeunload', this._handleBeforeUnload.bind(this));
        document.addEventListener('visibilitychange', this._handleVisibilityChange.bind(this));
    }

    /**
     * T3.1: Initialize Semantic Color System
     */
    async _initializeSemanticSystem() {
        // Ensure semantic color system is ready
        if (!this.semanticColorSystem) {
            throw new Error('SemanticColorSystem not available');
        }

        // Set up performance coordination
        if (this.config.enableCache) {
            // The semantic system already has caching, we just coordinate
            this.performance.semanticSystemMetrics = this.semanticColorSystem.getPerformanceMetrics();
        }
    }

    /**
     * T3.1: Load Current Palette
     */
    async _loadCurrentPalette() {
        let paletteId = this.state.currentPalette;

        // Use default if no palette set
        if (!paletteId) {
            const category = this.state.userPreferences.preferredPaletteCategory || 'medical-clinical';
            const categoryPalettes = this.semanticColorSystem.getPalettesByCategory(category);
            paletteId = categoryPalettes.palettes[0]?.id || 'professional-trust';
        }

        // Validate palette exists
        const palette = this.semanticColorSystem.getPalette(paletteId);
        if (!palette) {
            console.warn(`⚠️ Palette '${paletteId}' not found, using fallback`);
            paletteId = 'professional-trust';
        }

        // Set current palette
        await this.switchPalette(paletteId, { source: 'initialization', skipSave: true });
    }

    /**
     * T3.5: Initialize Performance Monitoring
     */
    _initializePerformanceMonitoring() {
        this.performance.startTime = performance.now();
        this.performance.metrics = {
            totalOperations: 0,
            averageTime: 0,
            cacheHitRate: 0,
            errorCount: 0,
            memoryUsage: 0
        };

        // Monitor memory if available
        if (performance.memory) {
            this.performance.metrics.memoryUsage = performance.memory.usedJSHeapSize;
        }
    }

    /**
     * T3.1: High-Level Palette Switching with State Management
     */
    async switchPalette(paletteId, options = {}) {
        const startTime = performance.now();

        try {
            this.emit('palette:switching', {
                from: this.state.currentPalette,
                to: paletteId,
                options,
                timestamp: Date.now()
            });

            // Validation
            const palette = this.semanticColorSystem.getPalette(paletteId);
            if (!palette) {
                throw new Error(`Palette '${paletteId}' not found`);
            }

            // State management - create undo point
            if (!options.skipUndo && this.state.currentPalette) {
                this._createUndoPoint();
            }

            // Update state
            this.state.previousPalette = this.state.currentPalette;
            this.state.currentPalette = paletteId;
            this.state.isDirty = !options.skipSave;

            // Switch in semantic system
            const success = this.semanticColorSystem.setCurrentPalette(paletteId);
            if (!success) {
                throw new Error('Failed to set palette in semantic system');
            }

            // Apply to DOM if not in preview mode or if forced
            if (!this.state.isPreviewMode || options.force) {
                this._applyPaletteToDOM(paletteId);
            }

            // Save settings if not disabled
            if (!options.skipSave && (this.config.autoSave || options.forceSave)) {
                await this._saveSettings();
            }

            // Update WordPress customizer if available
            if (this.wordpress.customizer && !options.skipCustomizer) {
                this._updateCustomizerSetting(paletteId);
            }

            const switchTime = performance.now() - startTime;
            this._trackOperation('switchPalette', switchTime);

            this.emit('palette:switched', {
                paletteId,
                palette,
                previousPalette: this.state.previousPalette,
                switchTime: Math.round(switchTime),
                source: options.source || 'api',
                timestamp: Date.now()
            });

            return true;

        } catch (error) {
            this.performance.metrics.errorCount++;

            this.emit('palette:error', {
                paletteId,
                error: error.message,
                source: options.source || 'api',
                timestamp: Date.now()
            });

            console.error('❌ Palette switch failed:', error);
            throw error;
        }
    }

    /**
     * T3.1: Preview Mode Management
     */
    enterPreviewMode(paletteId) {
        if (this.state.isPreviewMode) {
            this.exitPreviewMode();
        }

        this.state.isPreviewMode = true;
        this.state.previewPalette = paletteId;

        this._applyPaletteToDOM(paletteId, { preview: true });

        this.emit('preview:entered', {
            paletteId,
            originalPalette: this.state.currentPalette,
            timestamp: Date.now()
        });
    }

    exitPreviewMode() {
        if (!this.state.isPreviewMode) return;

        const originalPalette = this.state.currentPalette;
        this.state.isPreviewMode = false;
        this.state.previewPalette = null;

        this._applyPaletteToDOM(originalPalette);

        this.emit('preview:exited', {
            originalPalette,
            timestamp: Date.now()
        });
    }

    /**
     * T3.1: Undo/Redo Management
     */
    _createUndoPoint() {
        const undoPoint = {
            paletteId: this.state.currentPalette,
            customizations: new Map(this.state.customizations),
            timestamp: Date.now()
        };

        this.undoStack.push(undoPoint);

        // Limit undo stack size
        if (this.undoStack.length > this.config.maxUndoSteps) {
            this.undoStack.shift();
        }

        // Clear redo stack when new action is performed
        this.redoStack = [];
    }

    undo() {
        if (this.undoStack.length === 0) return false;

        const currentState = {
            paletteId: this.state.currentPalette,
            customizations: new Map(this.state.customizations),
            timestamp: Date.now()
        };

        this.redoStack.push(currentState);

        const undoPoint = this.undoStack.pop();
        this.switchPalette(undoPoint.paletteId, {
            source: 'undo',
            skipUndo: true
        });

        this.state.customizations = undoPoint.customizations;

        this.emit('state:undo', {
            restoredPalette: undoPoint.paletteId,
            timestamp: Date.now()
        });

        return true;
    }

    redo() {
        if (this.redoStack.length === 0) return false;

        const currentState = {
            paletteId: this.state.currentPalette,
            customizations: new Map(this.state.customizations),
            timestamp: Date.now()
        };

        this.undoStack.push(currentState);

        const redoPoint = this.redoStack.pop();
        this.switchPalette(redoPoint.paletteId, {
            source: 'redo',
            skipUndo: true
        });

        this.state.customizations = redoPoint.customizations;

        this.emit('state:redo', {
            restoredPalette: redoPoint.paletteId,
            timestamp: Date.now()
        });

        return true;
    }

    /**
     * T3.1: Apply Palette to DOM
     * Efficiently update CSS custom properties
     */
    _applyPaletteToDOM(paletteId, options = {}) {
        const startTime = performance.now();

        try {
            const css = this.semanticColorSystem.generateCSSProperties(paletteId);

            // Create or update style element
            let styleElement = document.getElementById('medSpa-color-system-styles');
            if (!styleElement) {
                styleElement = document.createElement('style');
                styleElement.id = 'medSpa-color-system-styles';
                document.head.appendChild(styleElement);
            }

            // Add preview indicator if in preview mode
            let finalCSS = css;
            if (options.preview) {
                finalCSS += `
                    /* Preview Mode Styles */
                    body::before {
                        content: "🎨 Preview Mode";
                        position: fixed;
                        top: 10px;
                        right: 10px;
                        background: var(--color-primary);
                        color: var(--color-primary-text-title-primary);
                        padding: 8px 12px;
                        border-radius: 4px;
                        font-size: 12px;
                        font-weight: bold;
                        z-index: 10000;
                        pointer-events: none;
                    }
                `;
            }

            styleElement.textContent = finalCSS;

            // Add transition animations if enabled
            if (this.state.themeSettings.enable_animations && !options.preview) {
                this._addTransitionAnimations();
            }

            const applyTime = performance.now() - startTime;
            this._trackOperation('applyPaletteToDOM', applyTime);

            this.emit('dom:updated', {
                paletteId,
                applyTime: Math.round(applyTime),
                preview: !!options.preview,
                timestamp: Date.now()
            });

        } catch (error) {
            console.error('❌ Failed to apply palette to DOM:', error);
            this.emit('dom:error', {
                paletteId,
                error: error.message,
                timestamp: Date.now()
            });
        }
    }

    /**
     * T3.1: Add CSS Transition Animations
     */
    _addTransitionAnimations() {
        const transitionCSS = `
            * {
                transition:
                    background-color 0.3s ease,
                    color 0.3s ease,
                    border-color 0.3s ease,
                    box-shadow 0.3s ease !important;
            }
        `;

        let animationElement = document.getElementById('medSpa-color-transitions');
        if (!animationElement) {
            animationElement = document.createElement('style');
            animationElement.id = 'medSpa-color-transitions';
            document.head.appendChild(animationElement);
        }

        animationElement.textContent = transitionCSS;

        // Remove transitions after animation period
        setTimeout(() => {
            if (animationElement && animationElement.parentNode) {
                animationElement.parentNode.removeChild(animationElement);
            }
        }, 500);
    }

    /**
     * T3.2: Save Settings (WordPress or LocalStorage)
     */
    async _saveSettings() {
        const startTime = performance.now();

        try {
            const settings = {
                currentPalette: this.state.currentPalette,
                themeSettings: { ...this.state.themeSettings },
                userPreferences: { ...this.state.userPreferences },
                lastSaved: Date.now()
            };

            if (this.wordpress.isWordPress && this.wordpress.customizer) {
                await this._saveWordPressSettings(settings);
            } else {
                await this._saveLocalStorageSettings(settings);
            }

            this.state.isDirty = false;
            this.state.lastSaved = settings.lastSaved;

            const saveTime = performance.now() - startTime;
            this._trackOperation('saveSettings', saveTime);

            this.emit('settings:saved', {
                settings,
                saveTime: Math.round(saveTime),
                method: this.wordpress.isWordPress ? 'wordpress' : 'localStorage',
                timestamp: Date.now()
            });

        } catch (error) {
            console.error('❌ Failed to save settings:', error);
            this.emit('settings:error', {
                error: error.message,
                timestamp: Date.now()
            });
        }
    }

    /**
     * T3.2: Save WordPress Settings
     */
    async _saveWordPressSettings(settings) {
        const customizer = this.wordpress.customizer;
        const prefix = this.wordpress.settingsPrefix;

        // Save palette setting
        const paletteSetting = customizer(`${prefix}palette`);
        if (paletteSetting) {
            paletteSetting.set(settings.currentPalette);
        }

        // Save theme settings
        for (const [key, value] of Object.entries(settings.themeSettings)) {
            const setting = customizer(`${prefix}${key}`);
            if (setting) {
                setting.set(value);
            }
        }

        // Trigger customizer save if auto-save is enabled
        if (this.config.autoSave && customizer.state) {
            customizer.state('saved').set(false);
        }
    }

    /**
     * T3.1: Save LocalStorage Settings
     */
    async _saveLocalStorageSettings(settings) {
        try {
            localStorage.setItem('medSpa_colorSystem_settings', JSON.stringify(settings));
        } catch (error) {
            // Handle localStorage quota exceeded
            if (error.name === 'QuotaExceededError') {
                localStorage.removeItem('medSpa_colorSystem_settings');
                localStorage.setItem('medSpa_colorSystem_settings', JSON.stringify(settings));
            } else {
                throw error;
            }
        }
    }

    /**
     * T3.2: Update WordPress Customizer Setting
     */
    _updateCustomizerSetting(paletteId) {
        if (!this.wordpress.customizer) return;

        const settingId = `${this.wordpress.settingsPrefix}palette`;
        const setting = this.wordpress.customizer(settingId);

        if (setting && setting.get() !== paletteId) {
            setting.set(paletteId);
        }
    }

    /**
     * T3.2: Update Live Preview
     */
    _updateLivePreview() {
        if (!this.state.currentPalette) return;

        this._applyPaletteToDOM(this.state.currentPalette, {
            preview: this.state.isPreviewMode
        });

        this.emit('preview:updated', {
            paletteId: this.state.currentPalette,
            timestamp: Date.now()
        });
    }

    /**
     * T3.4: Event Handlers
     */
    handlePaletteChange(event) {
        const { paletteId, palette, timestamp } = event.detail;

        // Coordinate with manager state
        if (paletteId !== this.state.currentPalette) {
            this.state.currentPalette = paletteId;
            this.state.isDirty = true;
        }

        this.emit('manager:paletteChanged', {
            paletteId,
            palette,
            managerState: { ...this.state },
            timestamp
        });
    }

    handleAutoSave() {
        if (this.state.isDirty && this.config.autoSave) {
            this._saveSettings();
        }
    }

    handlePerformanceMetrics() {
        // Update performance metrics
        const semanticMetrics = this.semanticColorSystem.getPerformanceMetrics();

        this.performance.metrics.semanticSystemMetrics = semanticMetrics;

        if (performance.memory) {
            this.performance.metrics.memoryUsage = performance.memory.usedJSHeapSize;
        }

        this.emit('performance:updated', {
            metrics: { ...this.performance.metrics },
            timestamp: Date.now()
        });
    }

    _handleBeforeUnload(event) {
        if (this.state.isDirty) {
            const message = 'You have unsaved color changes. Are you sure you want to leave?';
            event.returnValue = message;
            return message;
        }
    }

    _handleVisibilityChange() {
        if (document.visibilityState === 'hidden' && this.state.isDirty) {
            // Save on page hide
            this._saveSettings();
        }
    }

    /**
     * T3.5: Performance Tracking
     */
    _trackOperation(operationType, duration) {
        this.performance.metrics.totalOperations++;

        // Update average time
        const currentAvg = this.performance.metrics.averageTime;
        const newAvg = (currentAvg * (this.performance.metrics.totalOperations - 1) + duration) /
                      this.performance.metrics.totalOperations;
        this.performance.metrics.averageTime = Math.round(newAvg * 100) / 100;

        // Track specific operation types
        if (!this.performance.operationStats) {
            this.performance.operationStats = {};
        }

        if (!this.performance.operationStats[operationType]) {
            this.performance.operationStats[operationType] = {
                count: 0,
                totalTime: 0,
                averageTime: 0
            };
        }

        const opStats = this.performance.operationStats[operationType];
        opStats.count++;
        opStats.totalTime += duration;
        opStats.averageTime = opStats.totalTime / opStats.count;
    }

    /**
     * T3.4: Event System Methods
     */
    emit(eventType, data) {
        const event = new CustomEvent(eventType, { detail: data });
        this.eventEmitter.dispatchEvent(event);

        // Also emit on document for global listening
        document.dispatchEvent(new CustomEvent(`colorManager:${eventType}`, { detail: data }));
    }

    on(eventType, handler) {
        this.eventEmitter.addEventListener(eventType, handler);

        // Store handler for cleanup
        if (!this.eventHandlers.has(eventType)) {
            this.eventHandlers.set(eventType, new Set());
        }
        this.eventHandlers.get(eventType).add(handler);
    }

    off(eventType, handler) {
        this.eventEmitter.removeEventListener(eventType, handler);

        // Remove from stored handlers
        if (this.eventHandlers.has(eventType)) {
            this.eventHandlers.get(eventType).delete(handler);
        }
    }

    /**
     * T3.5: Developer API Methods
     */

    // Get current state and configuration
    getState() {
        return {
            ...this.state,
            config: { ...this.config },
            wordpress: {
                isWordPress: this.wordpress.isWordPress,
                hasCustomizer: !!this.wordpress.customizer,
                hasAjax: !!(this.wordpress.ajaxUrl && this.wordpress.nonce)
            },
            isInitialized: this.isInitialized
        };
    }

    // Get all available palettes with enhanced information
    getAllPalettes() {
        return this.semanticColorSystem.getAllPalettes();
    }

    // Get palettes by category
    getPalettesByCategory(category) {
        return this.semanticColorSystem.getPalettesByCategory(category);
    }

    // Get current palette information
    getCurrentPalette() {
        if (!this.state.currentPalette) return null;

        return {
            ...this.semanticColorSystem.getCurrentPalette(),
            managerState: {
                isPreviewMode: this.state.isPreviewMode,
                isDirty: this.state.isDirty,
                lastSaved: this.state.lastSaved
            }
        };
    }

    // Get accessibility validation for current palette
    validateCurrentPaletteAccessibility() {
        if (!this.state.currentPalette) return null;
        return this.semanticColorSystem.validatePaletteAccessibility(this.state.currentPalette);
    }

    // Get performance metrics
    getPerformanceMetrics() {
        return {
            manager: { ...this.performance.metrics },
            semanticSystem: this.semanticColorSystem.getPerformanceMetrics(),
            operationStats: { ...this.performance.operationStats }
        };
    }

    // Configuration management
    updateConfig(newConfig) {
        const oldConfig = { ...this.config };
        this.config = { ...this.config, ...newConfig };

        // Handle config changes that require action
        if (newConfig.autoSave !== undefined && newConfig.autoSave !== oldConfig.autoSave) {
            if (newConfig.autoSave && !this.autoSaveTimer) {
                this.autoSaveTimer = setInterval(this.handleAutoSave, this.config.autoSaveDelay);
            } else if (!newConfig.autoSave && this.autoSaveTimer) {
                clearInterval(this.autoSaveTimer);
                this.autoSaveTimer = null;
            }
        }

        this.emit('config:updated', {
            oldConfig,
            newConfig: { ...this.config },
            timestamp: Date.now()
        });
    }

    // Theme settings management
    updateThemeSettings(newSettings) {
        const oldSettings = { ...this.state.themeSettings };
        this.state.themeSettings = { ...this.state.themeSettings, ...newSettings };
        this.state.isDirty = true;

        this.emit('settings:updated', {
            oldSettings,
            newSettings: { ...this.state.themeSettings },
            timestamp: Date.now()
        });

        // Auto-save if enabled
        if (this.config.autoSave) {
            this._saveSettings();
        }
    }

    // User preferences management
    updateUserPreferences(newPreferences) {
        const oldPreferences = { ...this.state.userPreferences };
        this.state.userPreferences = { ...this.state.userPreferences, ...newPreferences };
        this.state.isDirty = true;

        this.emit('preferences:updated', {
            oldPreferences,
            newPreferences: { ...this.state.userPreferences },
            timestamp: Date.now()
        });

        // Auto-save if enabled
        if (this.config.autoSave) {
            this._saveSettings();
        }
    }

    // Force save settings
    async saveSettings() {
        return this._saveSettings();
    }

    // Reset to defaults
    async resetToDefaults() {
        this._createUndoPoint();

        // Reset settings
        this._setDefaultSettings();

        // Reset to default palette
        const defaultCategory = this.state.userPreferences.preferredPaletteCategory || 'medical-clinical';
        const categoryPalettes = this.semanticColorSystem.getPalettesByCategory(defaultCategory);
        const defaultPalette = categoryPalettes.palettes[0]?.id || 'professional-trust';

        await this.switchPalette(defaultPalette, {
            source: 'reset',
            forceSave: true
        });

        this.emit('system:reset', {
            defaultPalette,
            timestamp: Date.now()
        });
    }

    // Cleanup method
    destroy() {
        // Clear timers
        if (this.autoSaveTimer) {
            clearInterval(this.autoSaveTimer);
        }

        // Remove event listeners
        document.removeEventListener('paletteChanged', this.handlePaletteChange);
        window.removeEventListener('beforeunload', this._handleBeforeUnload);
        document.removeEventListener('visibilitychange', this._handleVisibilityChange);

        // Clear event handlers
        for (const [eventType, handlers] of this.eventHandlers.entries()) {
            for (const handler of handlers) {
                this.eventEmitter.removeEventListener(eventType, handler);
            }
        }

        // Clear caches
        if (this.semanticColorSystem && typeof this.semanticColorSystem.clearCaches === 'function') {
            this.semanticColorSystem.clearCaches();
        }

        this.emit('manager:destroyed', {
            timestamp: Date.now()
        });
    }
}

// Export for both ES6 modules and global scope
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ColorSystemManager;
} else if (typeof window !== 'undefined') {
    window.ColorSystemManager = ColorSystemManager;
}

// Auto-initialize global instance if semantic color system is available
if (typeof window !== 'undefined' && window.SemanticColorSystem) {
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            window.colorSystemManager = new ColorSystemManager();
        });
    } else {
        window.colorSystemManager = new ColorSystemManager();
    }
}
