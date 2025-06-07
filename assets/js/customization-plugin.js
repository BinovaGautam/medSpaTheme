/**
 * Customization Plugin - Base Plugin Architecture
 * Standard interface for extensible customization domains
 *
 * @since PVC-007-DT
 * @version 1.0.0
 * @performance Optimized plugin lifecycle with lazy loading
 */

class CustomizationPlugin {
    constructor(config = {}) {
        // Plugin configuration
        this.config = {
            name: config.name || 'Unnamed Plugin',
            version: config.version || '1.0.0',
            description: config.description || '',
            author: config.author || '',
            dependencies: config.dependencies || [],
            priority: config.priority || 10,
            ...config
        };

        // Plugin state
        this.isRegistered = false;
        this.isInitialized = false;
        this.engine = null;
        this.tokens = new Map();
        this.cache = new Map();

        // Performance tracking
        this.performanceMetrics = {
            initializationTime: 0,
            tokenGenerationTime: 0,
            applicationTime: 0,
            cacheHits: 0
        };

        // Bind methods
        this.register = this.register.bind(this);
        this.apply = this.apply.bind(this);
        this.generateTokens = this.generateTokens.bind(this);
        this.getWordPressControls = this.getWordPressControls.bind(this);
    }

    /**
     * Register plugin with the Universal Customization Engine
     * @param {UniversalCustomizationEngine} engine - Engine instance
     * @returns {Promise<boolean>} Registration success
     */
    async register(engine) {
        const startTime = performance.now();

        try {
            if (this.isRegistered) {
                console.warn(`[CustomizationPlugin] Plugin '${this.config.name}' already registered`);
                return true;
            }

            // Validate engine
            if (!engine || typeof engine.registerDomain !== 'function') {
                throw new Error('Invalid Universal Customization Engine provided');
            }

            this.engine = engine;

            // Validate dependencies
            if (!this.validateDependencies()) {
                throw new Error(`Unmet dependencies for plugin '${this.config.name}': ${this.config.dependencies.join(', ')}`);
            }

            // Initialize plugin
            await this.initialize();

            // Register domain with engine
            const registrationSuccess = engine.registerDomain(this.getDomainName(), {
                name: this.config.name,
                version: this.config.version,
                description: this.config.description,
                generator: this,
                priority: this.config.priority,
                dependencies: this.config.dependencies,
                wpControls: this.hasWordPressControls() ? 'auto-generate' : 'none',
                previewMode: this.getPreviewMode(),
                tokens: this.getTokenNames()
            });

            if (!registrationSuccess) {
                throw new Error(`Failed to register domain with engine`);
            }

            this.isRegistered = true;

            // Update performance metrics
            this.performanceMetrics.initializationTime = performance.now() - startTime;

            console.log(`[CustomizationPlugin] Plugin '${this.config.name}' registered successfully in ${this.performanceMetrics.initializationTime.toFixed(2)}ms`);
            return true;

        } catch (error) {
            console.error(`[CustomizationPlugin] Failed to register plugin '${this.config.name}':`, error);
            return false;
        }
    }

    /**
     * Initialize plugin (override in subclasses)
     * @returns {Promise<void>}
     */
    async initialize() {
        // Base initialization
        this.setupTokenDefinitions();
        this.setupWordPressControls();
        this.setupPreviewIntegration();
        this.isInitialized = true;
    }

    /**
     * Apply customization changes (main plugin interface)
     * @param {Object} changes - Changes to apply
     * @param {Object} options - Application options
     * @returns {Promise<Object>} Application results
     */
    async apply(changes, options = {}) {
        const startTime = performance.now();

        try {
            // Validate plugin is registered
            if (!this.isRegistered) {
                throw new Error(`Plugin '${this.config.name}' not registered`);
            }

            // Check cache first
            const cacheKey = JSON.stringify(changes);
            if (this.cache.has(cacheKey) && !options.skipCache) {
                this.performanceMetrics.cacheHits++;
                return this.cache.get(cacheKey);
            }

            // Validate changes
            const validatedChanges = this.validateChanges(changes);

            // Generate tokens
            const tokens = await this.generateTokens(validatedChanges, options);

            // Apply domain-specific processing
            const processedTokens = await this.processTokens(tokens, validatedChanges, options);

            // Generate CSS if needed
            const css = this.generateCSS ? await this.generateCSS(processedTokens, options) : null;

            // Create results
            const results = {
                plugin: this.config.name,
                domain: this.getDomainName(),
                tokens: processedTokens,
                css: css,
                appliedAt: Date.now(),
                cached: false,
                metadata: {
                    tokenCount: Object.keys(processedTokens).length,
                    generationTime: performance.now() - startTime
                }
            };

            // Cache results
            this.cache.set(cacheKey, results);
            results.cached = true;

            // Update performance metrics
            this.performanceMetrics.applicationTime += results.metadata.generationTime;

            console.log(`[CustomizationPlugin] Applied '${this.config.name}' in ${results.metadata.generationTime.toFixed(2)}ms`);
            return results;

        } catch (error) {
            console.error(`[CustomizationPlugin] Error applying '${this.config.name}':`, error);
            throw error;
        }
    }

    /**
     * Generate tokens from changes (override in subclasses)
     * @param {Object} changes - Validated changes
     * @param {Object} options - Generation options
     * @returns {Promise<Object>} Generated tokens
     */
    async generateTokens(changes, options = {}) {
        const startTime = performance.now();

        // Default implementation returns empty tokens
        // Subclasses should override this method
        const tokens = this.getDefaultTokens();

        this.performanceMetrics.tokenGenerationTime += performance.now() - startTime;
        return tokens;
    }

    /**
     * Process generated tokens (override in subclasses)
     * @param {Object} tokens - Generated tokens
     * @param {Object} changes - Original changes
     * @param {Object} options - Processing options
     * @returns {Promise<Object>} Processed tokens
     */
    async processTokens(tokens, changes, options = {}) {
        // Default implementation returns tokens as-is
        // Subclasses can override for additional processing
        return tokens;
    }

    /**
     * Validate changes against plugin schema (override in subclasses)
     * @param {Object} changes - Changes to validate
     * @returns {Object} Validated changes
     */
    validateChanges(changes) {
        // Default validation - ensure changes is an object
        if (!changes || typeof changes !== 'object') {
            throw new Error(`Invalid changes provided to plugin '${this.config.name}'`);
        }
        return changes;
    }

    /**
     * Setup token definitions (override in subclasses)
     */
    setupTokenDefinitions() {
        // Default: no tokens
        // Subclasses should define their token structure
    }

    /**
     * Setup WordPress controls (override in subclasses)
     */
    setupWordPressControls() {
        // Default: no WordPress controls
        // Subclasses can define WordPress Customizer controls
    }

    /**
     * Setup preview integration (override in subclasses)
     */
    setupPreviewIntegration() {
        // Default: no preview integration
        // Subclasses can define preview behavior
    }

    /**
     * Get domain name for this plugin
     * @returns {string} Domain name
     */
    getDomainName() {
        // Default: convert plugin name to domain name
        return this.config.name.toLowerCase().replace(/\s+/g, '-');
    }

    /**
     * Get token names for registration
     * @returns {Array<string>} Token names
     */
    getTokenNames() {
        return Array.from(this.tokens.keys());
    }

    /**
     * Check if plugin has WordPress controls
     * @returns {boolean} Has WordPress controls
     */
    hasWordPressControls() {
        return typeof this.getWordPressControls === 'function';
    }

    /**
     * Get preview mode for this plugin
     * @returns {string} Preview mode ('live', 'refresh', 'none')
     */
    getPreviewMode() {
        return 'live'; // Default to live preview
    }

    /**
     * Get WordPress controls configuration (override in subclasses)
     * @returns {Array<Object>} WordPress control configurations
     */
    getWordPressControls() {
        return [];
    }

    /**
     * Get default tokens (override in subclasses)
     * @returns {Object} Default tokens
     */
    getDefaultTokens() {
        return {};
    }

    /**
     * Validate plugin dependencies
     * @returns {boolean} Dependencies satisfied
     */
    validateDependencies() {
        if (!this.engine) return false;

        return this.config.dependencies.every(dependency => {
            const domain = this.engine.getDomain(dependency);
            return domain && domain.status === 'active';
        });
    }

    /**
     * Unregister plugin from engine
     * @returns {boolean} Success
     */
    unregister() {
        if (!this.isRegistered || !this.engine) {
            return false;
        }

        const success = this.engine.unregisterDomain(this.getDomainName());
        if (success) {
            this.isRegistered = false;
            this.engine = null;
            this.cache.clear();
            console.log(`[CustomizationPlugin] Plugin '${this.config.name}' unregistered`);
        }

        return success;
    }

    /**
     * Clear plugin cache
     */
    clearCache() {
        this.cache.clear();
        console.log(`[CustomizationPlugin] Cache cleared for '${this.config.name}'`);
    }

    /**
     * Get plugin information
     * @returns {Object} Plugin information
     */
    getInfo() {
        return {
            config: { ...this.config },
            state: {
                isRegistered: this.isRegistered,
                isInitialized: this.isInitialized,
                domainName: this.getDomainName(),
                tokenCount: this.tokens.size,
                cacheSize: this.cache.size
            },
            performance: { ...this.performanceMetrics }
        };
    }

    /**
     * Get performance metrics
     * @returns {Object} Performance metrics
     */
    getPerformanceMetrics() {
        return {
            ...this.performanceMetrics,
            cacheSize: this.cache.size,
            tokenDefinitionsCount: this.tokens.size,
            averageApplicationTime: this.performanceMetrics.applicationTime > 0 ?
                this.performanceMetrics.applicationTime / Math.max(1, this.cache.size) : 0
        };
    }

    /**
     * Export plugin data for debugging/analysis
     * @returns {Object} Plugin export data
     */
    export() {
        return {
            info: this.getInfo(),
            tokens: Object.fromEntries(this.tokens),
            config: this.config
        };
    }
}

// Export for global use
window.CustomizationPlugin = CustomizationPlugin;

console.log('[CustomizationPlugin] Base plugin architecture ready ✅');
