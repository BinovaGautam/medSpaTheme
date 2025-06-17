/**
 * Universal Customization Engine - Extensible Architecture Foundation
 * Core engine for plugin-based customization domain management
 *
 * @since PVC-007-DT
 * @version 1.0.0
 * @performance Optimized plugin system with lazy loading and caching
 */

class UniversalCustomizationEngine {
    constructor() {
        this.registeredDomains = new Map();
        this.domainPlugins = new Map();
        this.crossDomainRelationships = new Map();
        this.eventListeners = new Map();
        this.pluginCache = new Map();

        // Performance tracking
        this.performanceMetrics = {
            registrationTime: 0,
            pluginLoadTime: 0,
            domainChangePropagation: 0,
            crossDomainResolutions: 0,
            totalPluginsLoaded: 0
        };

        // Core domain coordination
        this.domainCoordinator = null;
        this.previewEngine = null;
        this.wordpressIntegration = null;

        // Initialize core engine
        this.initializeCoreEngine();

        // Bind methods for performance
        this.registerDomain = this.registerDomain.bind(this);
        this.applyCustomization = this.applyCustomization.bind(this);
        this.propagateCrossDomainChanges = this.propagateCrossDomainChanges.bind(this);

        console.log('[UniversalCustomizationEngine] Initialized ✅');
    }

    /**
     * Initialize core engine systems
     */
    initializeCoreEngine() {
        // Register built-in domains
        this.registerBuiltInDomains();

        // Setup cross-domain relationship rules
        this.initializeCrossDomainRules();

        // Setup event system for plugin communication
        this.initializeEventSystem();

        // Initialize performance monitoring
        this.initializePerformanceMonitoring();
    }

    /**
     * Register built-in domains that come with the system
     */
    registerBuiltInDomains() {
        // Check for generator availability before registration
        const generators = {
            color: window.colorDomainGenerator,
            typography: window.typographyDomainSystem,
            component: window.componentTokenSystem
        };

        // Color domain (already implemented)
        if (generators.color) {
            this.registerDomain('color', {
                name: 'Color Customization',
                version: '1.0.0',
                generator: generators.color,
                priority: 1,
                dependencies: [],
                wpControls: 'auto-generate',
                previewMode: 'live',
                tokens: ['primary', 'secondary', 'accent', 'surface', 'background', 'text-primary', 'text-secondary', 'border', 'success', 'warning', 'error']
            });
        } else {
            console.warn('[UniversalCustomizationEngine] Color domain generator not available, skipping registration');
        }

        // Typography domain (already implemented)
        if (generators.typography) {
            this.registerDomain('typography', {
                name: 'Typography Customization',
                version: '1.0.0',
                generator: generators.typography,
                priority: 2,
                dependencies: ['color'],
                wpControls: 'auto-generate',
                previewMode: 'live',
                tokens: ['heading-1', 'heading-2', 'heading-3', 'heading-4', 'body', 'body-large', 'body-small', 'caption', 'button', 'label']
            });
        } else {
            console.warn('[UniversalCustomizationEngine] Typography domain generator not available, skipping registration');
        }

        // Component domain (already implemented)
        if (generators.component) {
            this.registerDomain('component', {
                name: 'Component Customization',
                version: '1.0.0',
                generator: generators.component,
                priority: 3,
                dependencies: ['color', 'typography'],
                wpControls: 'auto-generate',
                previewMode: 'live',
                tokens: ['button', 'card', 'input', 'modal', 'navbar', 'alert', 'tooltip']
            });
        } else {
            console.warn('[UniversalCustomizationEngine] Component domain generator not available, skipping registration');
        }

        console.log('[UniversalCustomizationEngine] Built-in domains registration completed');
    }

    /**
     * Register a new customization domain (plugin or built-in)
     * @param {string} domainName - Domain identifier
     * @param {Object} domainConfig - Domain configuration
     * @returns {boolean} Registration success
     */
    registerDomain(domainName, domainConfig) {
        const startTime = performance.now();

        try {
            // Validate domain configuration
            if (!this.validateDomainConfig(domainConfig)) {
                throw new Error(`Invalid domain configuration for ${domainName}`);
            }

            // Check dependencies
            if (!this.validateDependencies(domainConfig.dependencies || [])) {
                throw new Error(`Unmet dependencies for ${domainName}`);
            }

            // Create domain registration
            const domainRegistration = {
                ...domainConfig,
                id: domainName,
                registeredAt: Date.now(),
                status: 'active',
                events: new Set(),
                relationships: new Set(),
                cache: new Map()
            };

            // Register domain
            this.registeredDomains.set(domainName, domainRegistration);

            // Setup cross-domain relationships
            this.establishCrossDomainRelationships(domainName, domainConfig);

            // Initialize WordPress controls if needed
            if (domainConfig.wpControls === 'auto-generate') {
                this.initializeWordPressControls(domainName, domainRegistration);
            }

            // Setup preview integration
            if (domainConfig.previewMode === 'live') {
                this.integrateLivePreview(domainName, domainRegistration);
            }

            // Cache plugin for performance
            if (domainConfig.generator) {
                this.pluginCache.set(domainName, domainConfig.generator);
            }

            // Update performance metrics
            this.performanceMetrics.registrationTime += performance.now() - startTime;
            this.performanceMetrics.totalPluginsLoaded++;

            // Emit registration event
            this.emitEvent('domain:registered', { domainName, config: domainRegistration });

            console.log(`[UniversalCustomizationEngine] Domain '${domainName}' registered successfully`);
            return true;

        } catch (error) {
            console.error(`[UniversalCustomizationEngine] Failed to register domain '${domainName}':`, error);
            return false;
        }
    }

    /**
     * Apply customization changes to a domain
     * @param {string} domainName - Target domain
     * @param {Object} changes - Changes to apply
     * @param {Object} options - Application options
     * @returns {Promise<Object>} Application results
     */
    async applyCustomization(domainName, changes, options = {}) {
        const startTime = performance.now();

        try {
            // Validate domain exists
            const domain = this.registeredDomains.get(domainName);
            if (!domain) {
                throw new Error(`Domain '${domainName}' not registered`);
            }

            // Get domain generator
            const generator = this.pluginCache.get(domainName) || domain.generator;
            if (!generator) {
                throw new Error(`No generator available for domain '${domainName}'`);
            }

            // Apply changes to domain
            const domainResults = await this.applyDomainChanges(
                domain,
                generator,
                changes,
                options
            );

            // Propagate cross-domain changes
            const crossDomainResults = await this.propagateCrossDomainChanges(
                domainName,
                changes,
                domainResults,
                options
            );

            // Update preview if enabled
            if (domain.previewMode === 'live' && !options.skipPreview) {
                await this.updateLivePreview(domainName, domainResults, crossDomainResults);
            }

            // Update WordPress customizer if needed
            if (domain.wpControls && !options.skipWordPress) {
                await this.updateWordPressControls(domainName, changes, domainResults);
            }

            // Combine results
            const allResults = {
                domain: domainName,
                directChanges: domainResults,
                crossDomainChanges: crossDomainResults,
                appliedAt: Date.now(),
                performanceTime: performance.now() - startTime
            };

            // Update performance metrics
            this.performanceMetrics.domainChangePropagation += allResults.performanceTime;

            // Emit change event
            this.emitEvent('customization:applied', allResults);

            console.log(`[UniversalCustomizationEngine] Applied customization to '${domainName}' in ${allResults.performanceTime.toFixed(2)}ms`);
            return allResults;

        } catch (error) {
            console.error(`[UniversalCustomizationEngine] Failed to apply customization to '${domainName}':`, error);
            throw error;
        }
    }

    /**
     * Apply changes to specific domain
     * @param {Object} domain - Domain registration
     * @param {Object} generator - Domain generator
     * @param {Object} changes - Changes to apply
     * @param {Object} options - Options
     * @returns {Promise<Object>} Domain-specific results
     */
    async applyDomainChanges(domain, generator, changes, options) {
        try {
            // Check cache first
            const cacheKey = JSON.stringify(changes);
            if (domain.cache.has(cacheKey) && !options.skipCache) {
                this.performanceMetrics.cacheHits++;
                return domain.cache.get(cacheKey);
            }

            // Apply changes through domain generator
            let results;
            if (domain.id === 'color' && generator.generateFromPalette) {
                results = generator.generateFromPalette(changes);
            } else if (domain.id === 'typography' && generator.generateFromSelection) {
                results = generator.generateFromSelection(changes);
            } else if (domain.id === 'component' && generator.generateComponentTokens) {
                results = generator.generateComponentTokens(changes);
            } else if (generator.apply) {
                // Generic plugin interface
                results = await generator.apply(changes, options);
            } else {
                throw new Error(`No valid application method found for domain '${domain.id}'`);
            }

            // Cache results
            domain.cache.set(cacheKey, results);

            return results;

        } catch (error) {
            console.error(`[UniversalCustomizationEngine] Error applying changes to domain '${domain.id}':`, error);
            throw error;
        }
    }

    /**
     * Propagate changes across related domains
     * @param {string} sourceDomain - Source domain name
     * @param {Object} sourceChanges - Original changes
     * @param {Object} sourceResults - Results from source domain
     * @param {Object} options - Options
     * @returns {Promise<Object>} Cross-domain propagation results
     */
    async propagateCrossDomainChanges(sourceDomain, sourceChanges, sourceResults, options) {
        const startTime = performance.now();
        const crossDomainResults = {};

        try {
            // Get domains that depend on the source domain
            const affectedDomains = this.getAffectedDomains(sourceDomain);

            // Process each affected domain
            for (const targetDomainName of affectedDomains) {
                const targetDomain = this.registeredDomains.get(targetDomainName);
                if (!targetDomain) continue;

                // Generate cross-domain changes
                const crossDomainChanges = this.generateCrossDomainChanges(
                    sourceDomain,
                    targetDomainName,
                    sourceChanges,
                    sourceResults
                );

                if (Object.keys(crossDomainChanges).length > 0) {
                    // Apply cross-domain changes
                    const targetResults = await this.applyCustomization(
                        targetDomainName,
                        crossDomainChanges,
                        { ...options, skipCrossDomain: true } // Prevent infinite loops
                    );

                    crossDomainResults[targetDomainName] = targetResults;
                }
            }

            // Update performance metrics
            this.performanceMetrics.crossDomainResolutions++;
            const propagationTime = performance.now() - startTime;

            console.log(`[UniversalCustomizationEngine] Cross-domain propagation completed in ${propagationTime.toFixed(2)}ms`);
            return crossDomainResults;

        } catch (error) {
            console.error('[UniversalCustomizationEngine] Error in cross-domain propagation:', error);
            return crossDomainResults; // Return partial results
        }
    }

    /**
     * Get domains affected by changes to source domain
     * @param {string} sourceDomain - Source domain name
     * @returns {Array<string>} Affected domain names
     */
    getAffectedDomains(sourceDomain) {
        const affected = [];

        this.registeredDomains.forEach((domain, domainName) => {
            if (domain.dependencies && domain.dependencies.includes(sourceDomain)) {
                affected.push(domainName);
            }
        });

        return affected;
    }

    /**
     * Generate changes for cross-domain relationships
     * @param {string} sourceDomain - Source domain
     * @param {string} targetDomain - Target domain
     * @param {Object} sourceChanges - Original changes
     * @param {Object} sourceResults - Source domain results
     * @returns {Object} Cross-domain changes
     */
    generateCrossDomainChanges(sourceDomain, targetDomain, sourceChanges, sourceResults) {
        const crossDomainChanges = {};

        try {
            // Color → Typography relationships
            if (sourceDomain === 'color' && targetDomain === 'typography') {
                if (sourceResults && sourceResults.semantic) {
                    crossDomainChanges.colorTokens = sourceResults.semantic;
                }
            }

            // Color + Typography → Component relationships
            if (['color', 'typography'].includes(sourceDomain) && targetDomain === 'component') {
                if (sourceDomain === 'color' && sourceResults.semantic) {
                    crossDomainChanges.color = sourceResults;
                }
                if (sourceDomain === 'typography' && sourceResults.optimized) {
                    crossDomainChanges.typography = sourceResults;
                }
            }

            // Custom plugin relationships
            const relationshipRules = this.crossDomainRelationships.get(`${sourceDomain}->${targetDomain}`);
            if (relationshipRules) {
                Object.assign(crossDomainChanges, relationshipRules(sourceChanges, sourceResults));
            }

            return crossDomainChanges;

        } catch (error) {
            console.error(`[UniversalCustomizationEngine] Error generating cross-domain changes from ${sourceDomain} to ${targetDomain}:`, error);
            return {};
        }
    }

    /**
     * Validate domain configuration
     * @param {Object} config - Domain configuration
     * @returns {boolean} Is valid
     */
    validateDomainConfig(config) {
        const required = ['name', 'version'];
        return required.every(field => config[field]) &&
               (config.generator || config.apply) &&
               typeof config.name === 'string' &&
               typeof config.version === 'string';
    }

    /**
     * Validate domain dependencies are met
     * @param {Array<string>} dependencies - Required dependencies
     * @returns {boolean} Dependencies satisfied
     */
    validateDependencies(dependencies) {
        return dependencies.every(dep => this.registeredDomains.has(dep));
    }

    /**
     * Establish cross-domain relationships
     * @param {string} domainName - Domain name
     * @param {Object} domainConfig - Domain configuration
     */
    establishCrossDomainRelationships(domainName, domainConfig) {
        if (domainConfig.dependencies) {
            domainConfig.dependencies.forEach(dependency => {
                const relationshipKey = `${dependency}->${domainName}`;
                if (!this.crossDomainRelationships.has(relationshipKey)) {
                    this.crossDomainRelationships.set(relationshipKey, this.getDefaultRelationshipRule(dependency, domainName));
                }
            });
        }

        // Setup reverse relationships for propagation
        this.registeredDomains.forEach((domain, otherDomainName) => {
            if (domain.dependencies && domain.dependencies.includes(domainName)) {
                const relationshipKey = `${domainName}->${otherDomainName}`;
                if (!this.crossDomainRelationships.has(relationshipKey)) {
                    this.crossDomainRelationships.set(relationshipKey, this.getDefaultRelationshipRule(domainName, otherDomainName));
                }
            }
        });
    }

    /**
     * Get default relationship rule between domains
     * @param {string} sourceDomain - Source domain
     * @param {string} targetDomain - Target domain
     * @returns {Function} Relationship rule function
     */
    getDefaultRelationshipRule(sourceDomain, targetDomain) {
        return (sourceChanges, sourceResults) => {
            // Default: pass through semantic tokens
            if (sourceResults && sourceResults.semantic) {
                return { [`${sourceDomain}Tokens`]: sourceResults.semantic };
            }
            return {};
        };
    }

    /**
     * Initialize WordPress controls for domain
     * @param {string} domainName - Domain name
     * @param {Object} domain - Domain registration
     */
    initializeWordPressControls(domainName, domain) {
        // This would integrate with WordPress Customizer API
        // For now, just prepare the configuration
        const wpConfig = {
            section: `${domainName}_customization`,
            title: domain.name,
            priority: domain.priority * 10,
            controls: this.generateWordPressControls(domain)
        };

        // Store WordPress integration configuration
        if (!this.wordpressIntegration) {
            this.wordpressIntegration = new Map();
        }
        this.wordpressIntegration.set(domainName, wpConfig);

        console.log(`[UniversalCustomizationEngine] WordPress controls prepared for '${domainName}'`);
    }

    /**
     * Generate WordPress control configuration
     * @param {Object} domain - Domain registration
     * @returns {Array<Object>} WordPress control configurations
     */
    generateWordPressControls(domain) {
        const controls = [];

        if (domain.tokens) {
            domain.tokens.forEach(token => {
                controls.push({
                    id: `${domain.id}_${token}`,
                    label: this.humanizeTokenName(token),
                    type: this.getControlType(domain.id, token),
                    section: `${domain.id}_customization`,
                    transport: 'postMessage' // For live preview
                });
            });
        }

        return controls;
    }

    /**
     * Integrate domain with live preview system
     * @param {string} domainName - Domain name
     * @param {Object} domain - Domain registration
     */
    integrateLivePreview(domainName, domain) {
        // This would integrate with the Universal Preview System
        // For now, just register the preview capability
        if (!this.previewEngine) {
            this.previewEngine = new Map();
        }

        this.previewEngine.set(domainName, {
            updateMethod: 'live',
            selector: this.getPreviewSelector(domainName),
            tokens: domain.tokens || []
        });

        console.log(`[UniversalCustomizationEngine] Live preview integrated for '${domainName}'`);
    }

    /**
     * Initialize cross-domain relationship rules
     */
    initializeCrossDomainRules() {
        // Built-in cross-domain rules
        this.crossDomainRelationships.set('color->typography', (colorChanges, colorResults) => {
            if (colorResults && colorResults.semantic) {
                return {
                    colorTokens: colorResults.semantic,
                    coordinateWithColors: true
                };
            }
            return {};
        });

        this.crossDomainRelationships.set('typography->component', (typographyChanges, typographyResults) => {
            if (typographyResults && typographyResults.optimized) {
                return {
                    typography: typographyResults
                };
            }
            return {};
        });

        this.crossDomainRelationships.set('color->component', (colorChanges, colorResults) => {
            if (colorResults && colorResults.semantic) {
                return {
                    color: colorResults
                };
            }
            return {};
        });
    }

    /**
     * Initialize event system for plugin communication
     */
    initializeEventSystem() {
        this.eventListeners = new Map([
            ['domain:registered', []],
            ['domain:unregistered', []],
            ['customization:applied', []],
            ['customization:reverted', []],
            ['preview:updated', []],
            ['error:occurred', []]
        ]);
    }

    /**
     * Initialize performance monitoring
     */
    initializePerformanceMonitoring() {
        // Setup performance observers if available
        if ('PerformanceObserver' in window) {
            const observer = new PerformanceObserver((list) => {
                list.getEntries().forEach((entry) => {
                    if (entry.name.includes('customization-engine')) {
                        console.log(`[Performance] ${entry.name}: ${entry.duration.toFixed(2)}ms`);
                    }
                });
            });

            observer.observe({ entryTypes: ['measure'] });
        }
    }

    /**
     * Emit event to registered listeners
     * @param {string} eventName - Event name
     * @param {*} data - Event data
     */
    emitEvent(eventName, data) {
        const listeners = this.eventListeners.get(eventName);
        if (listeners) {
            listeners.forEach(listener => {
                try {
                    listener(data);
                } catch (error) {
                    console.error(`[UniversalCustomizationEngine] Error in event listener for '${eventName}':`, error);
                }
            });
        }
    }

    /**
     * Register event listener
     * @param {string} eventName - Event name
     * @param {Function} listener - Event listener
     */
    addEventListener(eventName, listener) {
        if (!this.eventListeners.has(eventName)) {
            this.eventListeners.set(eventName, []);
        }
        this.eventListeners.get(eventName).push(listener);
    }

    /**
     * Remove event listener
     * @param {string} eventName - Event name
     * @param {Function} listener - Event listener to remove
     */
    removeEventListener(eventName, listener) {
        const listeners = this.eventListeners.get(eventName);
        if (listeners) {
            const index = listeners.indexOf(listener);
            if (index > -1) {
                listeners.splice(index, 1);
            }
        }
    }

    // Utility methods

    humanizeTokenName(token) {
        return token.split('-').map(word =>
            word.charAt(0).toUpperCase() + word.slice(1)
        ).join(' ');
    }

    getControlType(domainId, token) {
        if (domainId === 'color') return 'color';
        if (domainId === 'typography' && token.includes('font')) return 'select';
        if (domainId === 'typography' && token.includes('size')) return 'range';
        return 'text';
    }

    getPreviewSelector(domainName) {
        const selectors = {
            'color': ':root',
            'typography': 'body',
            'component': '.component'
        };
        return selectors[domainName] || 'body';
    }

    /**
     * Get registered domains
     * @returns {Map} Registered domains
     */
    getDomains() {
        return new Map(this.registeredDomains);
    }

    /**
     * Get domain by name
     * @param {string} domainName - Domain name
     * @returns {Object|null} Domain registration
     */
    getDomain(domainName) {
        return this.registeredDomains.get(domainName) || null;
    }

    /**
     * Unregister domain
     * @param {string} domainName - Domain name to unregister
     * @returns {boolean} Success
     */
    unregisterDomain(domainName) {
        if (this.registeredDomains.has(domainName)) {
            this.registeredDomains.delete(domainName);
            this.pluginCache.delete(domainName);

            if (this.wordpressIntegration) {
                this.wordpressIntegration.delete(domainName);
            }

            if (this.previewEngine) {
                this.previewEngine.delete(domainName);
            }

            this.emitEvent('domain:unregistered', { domainName });
            console.log(`[UniversalCustomizationEngine] Domain '${domainName}' unregistered`);
            return true;
        }
        return false;
    }

    /**
     * Get performance metrics
     * @returns {Object} Performance metrics
     */
    getPerformanceMetrics() {
        return {
            ...this.performanceMetrics,
            registeredDomainsCount: this.registeredDomains.size,
            crossDomainRelationshipsCount: this.crossDomainRelationships.size,
            eventListenersCount: Array.from(this.eventListeners.values()).reduce((sum, listeners) => sum + listeners.length, 0),
            cacheSize: Array.from(this.registeredDomains.values()).reduce((sum, domain) => sum + domain.cache.size, 0)
        };
    }

    /**
     * Clear all caches
     */
    clearCaches() {
        this.pluginCache.clear();
        this.registeredDomains.forEach(domain => {
            domain.cache.clear();
        });
        console.log('[UniversalCustomizationEngine] All caches cleared');
    }
}

// Export for global use
window.UniversalCustomizationEngine = UniversalCustomizationEngine;

// Create global instance
window.universalCustomizationEngine = new UniversalCustomizationEngine();

console.log('[UniversalCustomizationEngine] Extensible architecture foundation ready ✅');
