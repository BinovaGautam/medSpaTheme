/**
 * Sidebar Token Bridge - Visual Customizer to Design Token System Integration
 * Connects the Visual Customizer sidebar to the Universal Customization Engine
 *
 * Critical component for Sprint 2 Extension: Visual Customizer Integration Crisis Resolution
 * Ensures ALL Design Token controls are in Visual Customizer sidebar, NOT WordPress Customizer
 *
 * @since SPRINT-002-EXT-001
 * @version 1.0.0
 * @performance Real-time preview < 100ms requirement
 */

class SidebarTokenBridge {
    constructor() {
        this.universalEngine = null;
        this.designTokenRegistry = null;
        this.typographySystem = null;
        this.spacingGenerator = null;
        this.colorSystem = null;

        // Performance tracking
        this.performanceMetrics = {
            bridgeInitTime: 0,
            averageUpdateTime: 0,
            totalUpdates: 0,
            errors: 0
        };

        // State management
        this.currentTokens = new Map();
        this.isConnected = false;

        console.log('[SidebarTokenBridge] Initializing bridge to Universal Customization Engine...');
        this.initialize();
    }

    /**
     * Initialize the bridge and connect all systems
     */
    async initialize() {
        const startTime = performance.now();

        try {
            // Wait for Universal Customization Engine
            await this.waitForEngine();

            // Connect to design token systems
            await this.connectToSystems();

            // Setup sidebar integrations
            await this.setupSidebarIntegrations();

            // Setup event listeners
            this.setupEventListeners();

            // Update sidebar with current state
            this.refreshSidebarContent();

            this.performanceMetrics.bridgeInitTime = performance.now() - startTime;
            this.isConnected = true;

            console.log(`[SidebarTokenBridge] Bridge initialized in ${this.performanceMetrics.bridgeInitTime.toFixed(2)}ms ✅`);
            this.showConnectionStatus(true);

        } catch (error) {
            console.error('[SidebarTokenBridge] Failed to initialize bridge:', error);
            this.showConnectionStatus(false, error.message);
        }
    }

    /**
     * Wait for Universal Customization Engine to be available
     */
    async waitForEngine(maxAttempts = 20) {
        let attempts = 0;

        while (attempts < maxAttempts) {
            if (window.universalCustomizationEngine) {
                this.universalEngine = window.universalCustomizationEngine;
                console.log('[SidebarTokenBridge] Universal Customization Engine found ✅');
                return true;
            }

            await new Promise(resolve => setTimeout(resolve, 250));
            attempts++;
        }

        throw new Error('Universal Customization Engine not available after 5 seconds');
    }

    /**
     * Connect to all design token systems
     */
    async connectToSystems() {
        // Connect to Design Token Registry
        if (window.designTokenRegistry) {
            this.designTokenRegistry = window.designTokenRegistry;
            console.log('[SidebarTokenBridge] Connected to Design Token Registry ✅');
        }

        // Connect to Typography Domain System
        if (window.typographyDomainSystem) {
            this.typographySystem = window.typographyDomainSystem;
            console.log('[SidebarTokenBridge] Connected to Typography Domain System ✅');
        }

        // Connect to Spacing Domain Generator
        if (window.spacingDomainGenerator) {
            this.spacingGenerator = window.spacingDomainGenerator;
            console.log('[SidebarTokenBridge] Connected to Spacing Domain Generator ✅');
        }

        // Connect to Color System (existing in sidebar)
        if (window.colorSystemManager || window.colorPaletteInterface) {
            this.colorSystem = window.colorSystemManager || window.colorPaletteInterface;
            console.log('[SidebarTokenBridge] Connected to Color System ✅');
        }
    }

    /**
     * Setup sidebar section integrations
     */
    async setupSidebarIntegrations() {
        // Enhance Colors section (already partially working)
        this.enhanceColorSection();

        // Setup Typography section with Design Token System
        this.setupTypographySection();

        // Setup Layout/Spacing section
        this.setupSpacingSection();

        // Setup Effects section (future)
        this.setupEffectsSection();
    }

    /**
     * Enhance existing color section with Design Token integration
     */
    enhanceColorSection() {
        const colorSection = $('#section-colors');

        if (colorSection.length && this.universalEngine) {
            console.log('[SidebarTokenBridge] Enhancing color section with Design Token integration...');

            // Add Design Token status indicator
            const statusIndicator = `
                <div class="design-token-status">
                    <span class="status-badge success">🔗 Design Token System Connected</span>
                    <div class="performance-indicator">
                        <span class="perf-text">Avg Response: </span>
                        <span class="perf-value" id="color-performance">-</span>
                    </div>
                </div>
            `;

            colorSection.find('.section-header').append(statusIndicator);

            // The color palette interface is already working, just need to ensure
            // it connects to Universal Customization Engine instead of direct CSS
            this.interceptColorEvents();
        }
    }

    /**
     * Setup Typography section with visual font previews
     */
    setupTypographySection() {
        const typographySection = $('#section-typography');
        const container = $('#simple-typography-container');

        if (!typographySection.length) return;

        console.log('[SidebarTokenBridge] Setting up Typography section with Design Token System...');

        if (this.typographySystem) {
            // Get available font pairings from Typography Domain System
            const fontPairings = this.getTypographyPairings();

            // Generate visual typography interface
            const typographyHTML = this.generateTypographyInterface(fontPairings);
            container.html(typographyHTML);

            // Setup typography event handlers
            this.setupTypographyEventHandlers();

        } else {
            // Fallback interface
            container.html(`
                <div class="design-token-notice">
                    <div class="notice-icon">⚠️</div>
                    <h5>Typography System Loading...</h5>
                    <p>Typography Domain System is initializing. Please wait...</p>
                </div>
            `);
        }
    }

    /**
     * Setup Spacing section with visual controls
     */
    setupSpacingSection() {
        const layoutSection = $('#section-layout');
        const container = $('#simple-layout-container');

        if (!layoutSection.length) return;

        console.log('[SidebarTokenBridge] Setting up Spacing section with Design Token System...');

        if (this.spacingGenerator) {
            // Generate visual spacing interface
            const spacingHTML = this.generateSpacingInterface();
            container.html(spacingHTML);

            // Setup spacing event handlers
            this.setupSpacingEventHandlers();

        } else {
            container.html(`
                <div class="design-token-notice">
                    <div class="notice-icon">⚠️</div>
                    <h5>Spacing System Loading...</h5>
                    <p>Spacing Domain Generator is initializing. Please wait...</p>
                </div>
            `);
        }
    }

    /**
     * Setup Effects section placeholder
     */
    setupEffectsSection() {
        const effectsSection = $('#section-effects');
        const container = $('#simple-effects-container');

        if (container.length) {
            container.html(`
                <div class="coming-soon enhanced">
                    <div class="coming-soon-icon">🎭</div>
                    <h5>Visual Effects</h5>
                    <p>Component animation, shadows, and visual effects will be available when component domain is implemented.</p>
                    <div class="feature-preview">
                        <span class="preview-tag">🎯 Component Animations</span>
                        <span class="preview-tag">🎨 Shadow Presets</span>
                        <span class="preview-tag">⚡ Transition Effects</span>
                    </div>
                </div>
            `);
        }
    }

    /**
     * Generate Typography interface with visual previews
     */
    generateTypographyInterface(fontPairings) {
        const html = `
            <div class="design-token-typography">
                <div class="typography-options">
                    ${fontPairings.map(pairing => `
                        <div class="typography-preview" data-pairing="${pairing.id}">
                            <div class="font-samples">
                                <div class="heading-sample" style="font-family: ${pairing.heading.family}">
                                    <div class="sample-heading">Heading Aa</div>
                                    <div class="sample-numbers">123 456</div>
                                </div>
                                <div class="body-sample" style="font-family: ${pairing.body.family}">
                                    <div class="sample-text">Body text sample</div>
                                    <div class="sample-alphabet">ABCDEFGH</div>
                                </div>
                            </div>
                            <div class="pairing-info">
                                <div class="pairing-name">${pairing.name}</div>
                                <div class="pairing-description">${pairing.description || 'Professional font pairing'}</div>
                            </div>
                        </div>
                    `).join('')}
                </div>

                <div class="typography-controls">
                    <div class="control-group">
                        <label>Typography Scale</label>
                        <input type="range" id="typography-scale" min="1.125" max="1.618" step="0.05" value="1.25">
                        <span class="control-value">1.25</span>
                    </div>

                    <div class="control-group">
                        <label>Base Font Size</label>
                        <input type="range" id="base-font-size" min="14" max="20" step="1" value="16">
                        <span class="control-value">16px</span>
                    </div>
                </div>

                <div class="design-token-status">
                    <span class="status-badge success">🔗 Typography Tokens Connected</span>
                </div>
            </div>
        `;

        return html;
    }

    /**
     * Generate Spacing interface with visual controls
     */
    generateSpacingInterface() {
        const spacingScales = this.getSpacingScales();

        const html = `
            <div class="design-token-spacing">
                <div class="spacing-scale-options">
                    ${spacingScales.map(scale => `
                        <div class="spacing-scale-card" data-scale="${scale.id}">
                            <div class="scale-visualization">
                                ${scale.multipliers.slice(0, 5).map(mult => `
                                    <div class="spacing-visual" style="height: ${mult * 20}px; width: 100%; background: var(--color-primary, #007cba); margin-bottom: 2px; border-radius: 2px;"></div>
                                `).join('')}
                            </div>
                            <div class="scale-info">
                                <div class="scale-name">${scale.name}</div>
                                <div class="scale-personality">${scale.personality}</div>
                            </div>
                        </div>
                    `).join('')}
                </div>

                <div class="spacing-controls">
                    <div class="control-group">
                        <label>Base Spacing</label>
                        <input type="range" id="base-spacing" min="8" max="32" step="2" value="16">
                        <span class="control-value">16px</span>
                    </div>
                </div>

                <div class="design-token-status">
                    <span class="status-badge success">🔗 Spacing Tokens Connected</span>
                </div>
            </div>
        `;

        return html;
    }

    /**
     * Setup event listeners for all sidebar interactions
     */
    setupEventListeners() {
        // Intercept existing color palette events
        this.interceptColorEvents();

        // Setup new typography event handlers
        this.setupTypographyEventHandlers();

        // Setup new spacing event handlers
        this.setupSpacingEventHandlers();

        // Performance monitoring
        this.setupPerformanceMonitoring();

        console.log('[SidebarTokenBridge] Event listeners setup complete ✅');
    }

    /**
     * Intercept color events to route through Universal Customization Engine
     */
    interceptColorEvents() {
        // Listen for palette selection events and route through engine
        document.addEventListener('paletteInterface:paletteSelected', async (event) => {
            const startTime = performance.now();

            try {
                const { paletteId, palette } = event.detail;
                console.log('[SidebarTokenBridge] Intercepting color palette selection:', paletteId);

                // Route through Universal Customization Engine instead of direct CSS
                const results = await this.universalEngine.applyCustomization('color', {
                    paletteId: paletteId,
                    previewMode: true
                });

                // Apply results
                this.applyCSSVariables(results.directChanges);

                const duration = performance.now() - startTime;
                this.updatePerformanceMetric('color', duration);
                this.showFeedback(`Applied ${paletteId} palette`, 'success', duration);

            } catch (error) {
                console.error('[SidebarTokenBridge] Error applying color palette:', error);
                this.showFeedback('Error applying palette: ' + error.message, 'error');
                this.performanceMetrics.errors++;
            }
        });
    }

    /**
     * Setup typography event handlers
     */
    setupTypographyEventHandlers() {
        // Typography pairing selection
        $(document).on('click', '.typography-preview', async (e) => {
            const startTime = performance.now();

            try {
                const pairingId = $(e.currentTarget).data('pairing');
                console.log('[SidebarTokenBridge] Typography pairing selected:', pairingId);

                // Visual feedback
                $('.typography-preview').removeClass('selected');
                $(e.currentTarget).addClass('selected');

                // Apply through Universal Customization Engine
                const results = await this.universalEngine.applyCustomization('typography', {
                    pairingId: pairingId
                });

                this.applyCSSVariables(results.directChanges);

                const duration = performance.now() - startTime;
                this.updatePerformanceMetric('typography', duration);
                this.showFeedback(`Applied ${pairingId} typography`, 'success', duration);

            } catch (error) {
                console.error('[SidebarTokenBridge] Error applying typography:', error);
                this.showFeedback('Error applying typography: ' + error.message, 'error');
            }
        });

        // Typography scale changes
        $(document).on('input', '#typography-scale', (e) => {
            const value = parseFloat(e.target.value);
            $(e.target).siblings('.control-value').text(value.toString());
            this.debounceTypographyUpdate('scale', value);
        });

        // Base font size changes
        $(document).on('input', '#base-font-size', (e) => {
            const value = parseInt(e.target.value);
            $(e.target).siblings('.control-value').text(value + 'px');
            this.debounceTypographyUpdate('baseSize', value);
        });
    }

    /**
     * Setup spacing event handlers
     */
    setupSpacingEventHandlers() {
        // Spacing scale selection
        $(document).on('click', '.spacing-scale-card', async (e) => {
            const startTime = performance.now();

            try {
                const scaleId = $(e.currentTarget).data('scale');
                console.log('[SidebarTokenBridge] Spacing scale selected:', scaleId);

                // Visual feedback
                $('.spacing-scale-card').removeClass('selected');
                $(e.currentTarget).addClass('selected');

                // Apply through Universal Customization Engine
                if (this.spacingGenerator) {
                    const baseSpacing = parseInt($('#base-spacing').val()) || 16;
                    const spacingTokens = this.spacingGenerator.generateFromBase(baseSpacing, scaleId);

                    // Apply spacing tokens
                    this.applySpacingTokens(spacingTokens);

                    const duration = performance.now() - startTime;
                    this.updatePerformanceMetric('spacing', duration);
                    this.showFeedback(`Applied ${scaleId} spacing scale`, 'success', duration);
                }

            } catch (error) {
                console.error('[SidebarTokenBridge] Error applying spacing:', error);
                this.showFeedback('Error applying spacing: ' + error.message, 'error');
            }
        });

        // Base spacing changes
        $(document).on('input', '#base-spacing', (e) => {
            const value = parseInt(e.target.value);
            $(e.target).siblings('.control-value').text(value + 'px');
            this.debounceSpacingUpdate(value);
        });
    }

    /**
     * Apply CSS variables from Universal Customization Engine results
     */
    applyCSSVariables(changes) {
        if (!changes || !changes.tokens) return;

        const root = document.documentElement;
        let appliedCount = 0;

        Object.values(changes.tokens).forEach(token => {
            if (token.cssVariable && token.value) {
                root.style.setProperty(token.cssVariable, token.value);
                appliedCount++;
            }
        });

        console.log(`[SidebarTokenBridge] Applied ${appliedCount} CSS variables`);
    }

    /**
     * Apply spacing tokens to CSS
     */
    applySpacingTokens(spacingTokens) {
        if (!spacingTokens || !spacingTokens.base) return;

        const root = document.documentElement;
        let appliedCount = 0;

        // Apply base spacing tokens
        Object.values(spacingTokens.base).forEach(token => {
            if (token.cssVariable && token.value) {
                root.style.setProperty(token.cssVariable, token.value);
                appliedCount++;
            }
        });

        // Apply semantic spacing tokens
        if (spacingTokens.semantic) {
            Object.values(spacingTokens.semantic).forEach(token => {
                if (token.cssVariable && token.value) {
                    root.style.setProperty(token.cssVariable, token.value);
                    appliedCount++;
                }
            });
        }

        console.log(`[SidebarTokenBridge] Applied ${appliedCount} spacing tokens`);
    }

    /**
     * Get typography pairings from Typography Domain System
     */
    getTypographyPairings() {
        // Fallback pairings if system not available
        const fallbackPairings = [
            {
                id: 'medical-professional',
                name: 'Medical Professional',
                description: 'Clean and trustworthy',
                heading: { family: 'Inter, sans-serif' },
                body: { family: 'Source Sans Pro, sans-serif' }
            },
            {
                id: 'luxury-modern',
                name: 'Luxury Modern',
                description: 'Sophisticated and elegant',
                heading: { family: 'Playfair Display, serif' },
                body: { family: 'Inter, sans-serif' }
            },
            {
                id: 'contemporary-clean',
                name: 'Contemporary Clean',
                description: 'Modern and accessible',
                heading: { family: 'Poppins, sans-serif' },
                body: { family: 'Open Sans, sans-serif' }
            }
        ];

        if (this.typographySystem && this.typographySystem.getFontPairings) {
            return this.typographySystem.getFontPairings();
        }

        return fallbackPairings;
    }

    /**
     * Get spacing scales from Spacing Domain Generator
     */
    getSpacingScales() {
        const fallbackScales = [
            {
                id: 'geometric-major',
                name: 'Professional',
                personality: 'Balanced and clean',
                multipliers: [0.25, 0.5, 0.75, 1, 1.25]
            },
            {
                id: 'medical-professional',
                name: 'Medical',
                personality: 'Trustworthy spacing',
                multipliers: [0.25, 0.5, 0.75, 1, 1.2]
            },
            {
                id: 'spa-luxurious',
                name: 'Luxurious',
                personality: 'Spacious and relaxed',
                multipliers: [0.25, 0.5, 0.75, 1, 1.5]
            }
        ];

        if (this.spacingGenerator && this.spacingGenerator.scaleTypes) {
            const scales = [];
            this.spacingGenerator.scaleTypes.forEach((scale, id) => {
                scales.push({
                    id: id,
                    name: scale.name,
                    personality: scale.personality,
                    multipliers: scale.multipliers
                });
            });
            return scales;
        }

        return fallbackScales;
    }

    /**
     * Show feedback to user
     */
    showFeedback(message, type = 'info', duration = null) {
        // Update sidebar status
        const statusText = duration ? `${message} (${duration.toFixed(1)}ms)` : message;
        $('#simple-vc-status .status-indicator').text(`⚡ ${statusText}`);

        // Show temporary feedback
        this.showTemporaryFeedback(message, type);
    }

    /**
     * Show temporary feedback message
     */
    showTemporaryFeedback(message, type) {
        let feedback = $('.sidebar-feedback');

        if (!feedback.length) {
            feedback = $(`
                <div class="sidebar-feedback">
                    <div class="feedback-content"></div>
                </div>
            `);
            $('.simple-vc-content').prepend(feedback);
        }

        feedback.removeClass('success error warning info').addClass(type);
        feedback.find('.feedback-content').text(message);
        feedback.show();

        setTimeout(() => {
            feedback.fadeOut();
        }, 3000);
    }

    /**
     * Update performance metrics
     */
    updatePerformanceMetric(domain, duration) {
        this.performanceMetrics.totalUpdates++;
        this.performanceMetrics.averageUpdateTime =
            (this.performanceMetrics.averageUpdateTime * (this.performanceMetrics.totalUpdates - 1) + duration) /
            this.performanceMetrics.totalUpdates;

        // Update performance display
        $(`#${domain}-performance`).text(`${duration.toFixed(1)}ms`);

        // Check performance targets
        if (duration > 100) {
            console.warn(`[SidebarTokenBridge] ${domain} update took ${duration.toFixed(1)}ms - over 100ms target`);
        }
    }

    /**
     * Show connection status in sidebar
     */
    showConnectionStatus(connected, error = null) {
        const statusHTML = connected ?
            `<span class="status-badge success">🔗 Design Token System Connected</span>` :
            `<span class="status-badge error">❌ Connection Failed: ${error}</span>`;

        $('.design-token-status .status-badge').replaceWith(statusHTML);
    }

    /**
     * Debounce typography updates
     */
    debounceTypographyUpdate(property, value) {
        clearTimeout(this.typographyTimeout);
        this.typographyTimeout = setTimeout(() => {
            this.applyTypographyChange(property, value);
        }, 300);
    }

    /**
     * Debounce spacing updates
     */
    debounceSpacingUpdate(baseSpacing) {
        clearTimeout(this.spacingTimeout);
        this.spacingTimeout = setTimeout(() => {
            const selectedScale = $('.spacing-scale-card.selected').data('scale') || 'geometric-major';
            if (this.spacingGenerator) {
                const spacingTokens = this.spacingGenerator.generateFromBase(baseSpacing, selectedScale);
                this.applySpacingTokens(spacingTokens);
            }
        }, 300);
    }

    /**
     * Apply typography changes
     */
    async applyTypographyChange(property, value) {
        try {
            const currentPairing = $('.typography-preview.selected').data('pairing') || 'medical-professional';

            const config = {};
            config[property] = value;

            const results = await this.universalEngine.applyCustomization('typography', {
                pairingId: currentPairing,
                ...config
            });

            this.applyCSSVariables(results.directChanges);

        } catch (error) {
            console.error('[SidebarTokenBridge] Error applying typography change:', error);
        }
    }

    /**
     * Refresh sidebar content with current token state
     */
    refreshSidebarContent() {
        // Update each section with current tokens
        this.updateSectionStatus();
    }

    /**
     * Update section status indicators
     */
    updateSectionStatus() {
        const sections = ['colors', 'typography', 'layout'];

        sections.forEach(section => {
            const sectionElement = $(`#section-${section}`);
            if (sectionElement.length) {
                const status = this.getSectionConnectionStatus(section);
                sectionElement.find('.design-token-status').html(status);
            }
        });
    }

    /**
     * Get section connection status
     */
    getSectionConnectionStatus(section) {
        const connections = {
            colors: this.colorSystem && this.universalEngine,
            typography: this.typographySystem && this.universalEngine,
            layout: this.spacingGenerator && this.universalEngine
        };

        const isConnected = connections[section];

        return isConnected ?
            `<span class="status-badge success">🔗 ${section.charAt(0).toUpperCase() + section.slice(1)} Tokens Connected</span>` :
            `<span class="status-badge warning">⚠️ ${section.charAt(0).toUpperCase() + section.slice(1)} System Loading...</span>`;
    }

    /**
     * Setup performance monitoring
     */
    setupPerformanceMonitoring() {
        // Monitor every 5 seconds
        setInterval(() => {
            if (this.performanceMetrics.totalUpdates > 0) {
                const avgTime = this.performanceMetrics.averageUpdateTime;
                $('#performance-metrics').html(`
                    <div class="perf-summary">
                        Avg: ${avgTime.toFixed(1)}ms |
                        Updates: ${this.performanceMetrics.totalUpdates} |
                        Errors: ${this.performanceMetrics.errors}
                    </div>
                `);
            }
        }, 5000);
    }

    /**
     * Get bridge status for debugging
     */
    getStatus() {
        return {
            isConnected: this.isConnected,
            universalEngine: !!this.universalEngine,
            designTokenRegistry: !!this.designTokenRegistry,
            typographySystem: !!this.typographySystem,
            spacingGenerator: !!this.spacingGenerator,
            colorSystem: !!this.colorSystem,
            performanceMetrics: this.performanceMetrics
        };
    }
}

// Initialize when DOM is ready
$(document).ready(() => {
    // Wait a bit for other systems to initialize
    setTimeout(() => {
        window.sidebarTokenBridge = new SidebarTokenBridge();
        console.log('[SidebarTokenBridge] Global bridge instance created ✅');
    }, 1000);
});

// Export for global access
window.SidebarTokenBridge = SidebarTokenBridge;

