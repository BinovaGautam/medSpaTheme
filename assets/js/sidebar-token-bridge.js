/**
 * Sidebar Token Bridge - PVC-008-CRITICAL
 * Connects Design Token System to Visual Customizer Sidebar
 *
 * This bridge replaces dropdown interfaces with visual interfaces and
 * integrates the Universal Customization Engine with the sidebar.
 *
 * @since Sprint 2 Extension
 * @version 1.0.0
 */

class SidebarTokenBridge {
    constructor() {
        this.universalEngine = null;
        this.sidebarManager = null;
        this.colorPaletteInterface = null;
        this.typographyInterface = null;
        this.previewEngine = null;

        this.isInitialized = false;
        this.debug = window.designTokenConfig?.debugMode || false;

        this.log('🌉 Initializing Sidebar Token Bridge...');
        this.init();
    }

    /**
     * Initialize the bridge system
     */
    async init() {
        try {
            // Wait for dependencies to be ready
            await this.waitForDependencies();

            // Setup core components
            this.setupComponents();

            // Replace dropdown interfaces with visual interfaces
            this.replaceDropdownInterfaces();

            // Setup immediate preview integration
            this.setupImmediatePreview();

            // Setup event listeners
            this.setupEventListeners();

            this.isInitialized = true;
            this.log('✅ Sidebar Token Bridge initialized successfully');

            // Notify systems that bridge is ready
            this.dispatchEvent('bridgeReady', { bridge: this });

        } catch (error) {
            console.error('❌ Failed to initialize Sidebar Token Bridge:', error);
            this.handleInitializationError(error);
        }
    }

    /**
     * Wait for required dependencies to be available
     */
    async waitForDependencies() {
        const maxAttempts = 20;
        const interval = 250;

        for (let attempt = 0; attempt < maxAttempts; attempt++) {
            if (this.checkDependencies()) {
                this.log('✅ All dependencies ready');
                return;
            }

            this.log(`⏳ Waiting for dependencies... (${attempt + 1}/${maxAttempts})`);
            await this.delay(interval);
        }

        throw new Error('Required dependencies not available after timeout');
    }

    /**
     * Check if all required dependencies are available
     */
    checkDependencies() {
        const required = [
            'window.universalCustomizationEngine',
            'window.simpleVisualCustomizer',
            'window.jQuery'
        ];

        return required.every(dep => {
            const exists = this.getNestedProperty(window, dep.replace('window.', ''));
            if (!exists) {
                this.log(`❌ Missing dependency: ${dep}`);
            }
            return exists;
        });
    }

    /**
     * Setup core component references
     */
    setupComponents() {
        this.universalEngine = window.universalCustomizationEngine;
        this.sidebarManager = window.simpleVisualCustomizer;

        // Initialize visual interfaces
        this.colorPaletteInterface = new SidebarColorPaletteInterface(this);
        this.typographyInterface = new SidebarTypographyInterface(this);
        this.previewEngine = new ImmediatePreviewIntegration(this);

        this.log('✅ Core components setup complete');
    }

    /**
     * Replace dropdown interfaces with visual interfaces
     */
    replaceDropdownInterfaces() {
        this.log('🔄 Replacing dropdown interfaces...');

        // Replace color dropdowns
        this.replaceColorDropdowns();

        // Replace typography dropdowns
        this.replaceTypographyDropdowns();

        // Remove any remaining dropdowns
        this.removeRemainingDropdowns();

        this.log('✅ Dropdown interfaces replaced with visual interfaces');
    }

    /**
     * Replace color dropdown with visual palette grid
     */
    replaceColorDropdowns() {
        const colorSection = this.findSidebarSection('colors') || this.findSidebarSection('color');

        if (colorSection) {
            const colorContainer = colorSection.querySelector('#simple-color-palette-container') ||
                                 colorSection.querySelector('.simple-color-palette-container') ||
                                 colorSection;

            if (colorContainer) {
                this.log('🎨 Replacing color dropdown with visual palette grid');

                // Generate visual color palette interface
                const paletteGrid = this.colorPaletteInterface.render();
                colorContainer.innerHTML = paletteGrid;

                // Add interaction handlers
                this.setupColorInteractions(colorContainer);
            }
        } else {
            this.log('⚠️ Color section not found, creating new section');
            this.createColorSection();
        }
    }

    /**
     * Replace typography dropdown with visual font preview
     */
    replaceTypographyDropdowns() {
        let typographySection = this.findSidebarSection('typography') || this.findSidebarSection('fonts');

        if (!typographySection) {
            this.log('📝 Typography section not found, creating new section');
            typographySection = this.createTypographySection();
        }

        if (typographySection) {
            this.log('📝 Replacing typography dropdown with visual font preview');

            // Generate visual typography interface
            const typographyGrid = this.typographyInterface.render();

            // Find or create container
            let container = typographySection.querySelector('.typography-container');
            if (!container) {
                container = document.createElement('div');
                container.className = 'typography-container';
                typographySection.appendChild(container);
            }

            container.innerHTML = typographyGrid;

            // Add interaction handlers
            this.setupTypographyInteractions(container);
        }
    }

    /**
     * Remove any remaining dropdown elements
     */
    removeRemainingDropdowns() {
        const sidebar = document.querySelector('#simple-vc-sidebar');
        if (sidebar) {
            // Remove select dropdowns
            const dropdowns = sidebar.querySelectorAll('select, .dropdown, [type="select"]');
            dropdowns.forEach(dropdown => {
                this.log(`🗑️ Removing dropdown: ${dropdown.tagName}`);
                dropdown.remove();
            });

            // Remove old dropdown containers
            const dropdownContainers = sidebar.querySelectorAll('.dropdown-container, .select-container');
            dropdownContainers.forEach(container => {
                container.remove();
            });
        }
    }

    /**
     * Setup immediate preview integration
     */
    setupImmediatePreview() {
        this.log('⚡ Setting up immediate preview integration');

        if (this.previewEngine) {
            this.previewEngine.initialize();
        }
    }

    /**
     * Setup event listeners for bridge functionality
     */
    setupEventListeners() {
        // Listen for sidebar open/close events
        $(document).on('sidebarOpened', () => {
            this.onSidebarOpened();
        });

        $(document).on('sidebarClosed', () => {
            this.onSidebarClosed();
        });

        // Listen for token system events
        document.addEventListener('tokenSystemReady', () => {
            this.refreshInterfaces();
        });

        // Listen for palette/typography selections
        $(document).on('paletteSelected', (event, data) => {
            this.handlePaletteSelection(data);
        });

        $(document).on('typographySelected', (event, data) => {
            this.handleTypographySelection(data);
        });

        this.log('✅ Event listeners setup complete');
    }

    /**
     * Handle sidebar opened event
     */
    onSidebarOpened() {
        this.log('📂 Sidebar opened - refreshing interfaces');

        // Add version indicator at the top of sidebar
        this.addVersionIndicator();

        this.refreshInterfaces();

        // Ensure visual interfaces are loaded
        setTimeout(() => {
            this.ensureVisualInterfaces();
        }, 100);
    }

    /**
     * Add version indicator to sidebar
     */
    addVersionIndicator() {
        const sidebar = document.querySelector('#simple-vc-sidebar .simple-vc-content');
        if (!sidebar) return;

        // Remove existing indicator if present
        const existingIndicator = sidebar.querySelector('.sprint-version-indicator');
        if (existingIndicator) {
            existingIndicator.remove();
        }

        // Create version indicator
        const indicator = document.createElement('div');
        indicator.className = 'sprint-version-indicator';
        indicator.innerHTML = `
            <div class="version-header">
                <div class="version-badge">
                    <span class="version-icon">🚀</span>
                    <div class="version-info">
                        <div class="version-title">Sprint 2 Extension ACTIVE</div>
                        <div class="version-subtitle">Visual Customizer v1.0.0</div>
                    </div>
                </div>
                <div class="version-timestamp">
                    Updated: ${new Date().toLocaleDateString()}
                </div>
            </div>
            <div class="version-features">
                <span class="feature-tag">✨ Visual Color Palettes</span>
                <span class="feature-tag">📝 Typography Previews</span>
                <span class="feature-tag">⚡ Real-time Updates</span>
            </div>
        `;

        // Insert at the very top
        sidebar.insertBefore(indicator, sidebar.firstChild);

        this.log('✅ Version indicator added to sidebar');
    }

    /**
     * Handle sidebar closed event
     */
    onSidebarClosed() {
        this.log('📁 Sidebar closed');
        // Cleanup any temporary states
    }

    /**
     * Ensure visual interfaces are properly loaded
     */
    ensureVisualInterfaces() {
        const colorContainer = document.querySelector('#simple-color-palette-container');
        const typographyContainer = document.querySelector('.typography-container');

        // Check if interfaces need to be re-rendered
        if (colorContainer && !colorContainer.querySelector('.sidebar-palette-grid')) {
            this.log('🔄 Re-rendering color interface');
            this.replaceColorDropdowns();
        }

        if (typographyContainer && !typographyContainer.querySelector('.typography-options')) {
            this.log('🔄 Re-rendering typography interface');
            this.replaceTypographyDropdowns();
        }
    }

    /**
     * Handle palette selection
     */
    async handlePaletteSelection(data) {
        this.log('🎨 Handling palette selection:', data);

        if (!this.universalEngine) {
            this.log('❌ Universal Engine not available');
            return;
        }

        try {
            const startTime = performance.now();

            // Apply customization through Universal Engine
            const results = await this.universalEngine.applyCustomization('color', {
                paletteId: data.paletteId,
                paletteData: data.paletteData
            });

            const duration = performance.now() - startTime;

            // Update sidebar feedback
            this.updateSidebarFeedback(results, duration);

            // Verify performance requirement
            if (duration > 100) {
                console.warn(`⚠️ Preview update took ${duration.toFixed(2)}ms - exceeds 100ms target`);
            }

        } catch (error) {
            console.error('❌ Error applying palette selection:', error);
            this.showErrorFeedback('Failed to apply color palette');
        }
    }

    /**
     * Handle typography selection
     */
    async handleTypographySelection(data) {
        this.log('📝 Handling typography selection:', data);

        if (!this.universalEngine) {
            this.log('❌ Universal Engine not available');
            return;
        }

        try {
            const startTime = performance.now();

            // Apply customization through Universal Engine
            const results = await this.universalEngine.applyCustomization('typography', {
                pairingId: data.pairingId,
                pairingData: data.pairingData
            });

            const duration = performance.now() - startTime;

            // Update sidebar feedback
            this.updateSidebarFeedback(results, duration);

            // Verify performance requirement
            if (duration > 100) {
                console.warn(`⚠️ Preview update took ${duration.toFixed(2)}ms - exceeds 100ms target`);
            }

        } catch (error) {
            console.error('❌ Error applying typography selection:', error);
            this.showErrorFeedback('Failed to apply typography selection');
        }
    }

    /**
     * Update sidebar feedback
     */
    updateSidebarFeedback(results, duration) {
        const feedbackContainer = this.getFeedbackContainer();

        feedbackContainer.innerHTML = `
            <div class="feedback success">
                <div class="checkmark">✓</div>
                <span>Applied in ${duration.toFixed(0)}ms</span>
            </div>
        `;

        // Clear feedback after 2 seconds
        setTimeout(() => {
            feedbackContainer.innerHTML = '';
        }, 2000);
    }

    /**
     * Show error feedback
     */
    showErrorFeedback(message) {
        const feedbackContainer = this.getFeedbackContainer();

        feedbackContainer.innerHTML = `
            <div class="feedback error">
                <div class="error-icon">⚠</div>
                <span>${message}</span>
            </div>
        `;

        // Clear error after 5 seconds
        setTimeout(() => {
            feedbackContainer.innerHTML = '';
        }, 5000);
    }

    /**
     * Get or create feedback container
     */
    getFeedbackContainer() {
        let container = document.querySelector('.sidebar-feedback');

        if (!container) {
            container = document.createElement('div');
            container.className = 'sidebar-feedback';

            const sidebar = document.querySelector('#simple-vc-sidebar .simple-vc-content');
            if (sidebar) {
                sidebar.insertBefore(container, sidebar.firstChild);
            }
        }

        return container;
    }

    /**
     * Find sidebar section by name
     */
    findSidebarSection(sectionName) {
        const sidebar = document.querySelector('#simple-vc-sidebar');
        if (!sidebar) return null;

        // Try multiple selectors
        const selectors = [
            `.simple-vc-section:has(h4:contains("${sectionName}"))`,
            `.simple-vc-section[data-section="${sectionName}"]`,
            `#${sectionName}-section`,
            `.${sectionName}-section`
        ];

        for (const selector of selectors) {
            try {
                const section = sidebar.querySelector(selector);
                if (section) return section;
            } catch (e) {
                // Selector not supported, continue
            }
        }

        // Fallback: search by text content
        const sections = sidebar.querySelectorAll('.simple-vc-section');
        for (const section of sections) {
            const heading = section.querySelector('h4');
            if (heading && heading.textContent.toLowerCase().includes(sectionName.toLowerCase())) {
                return section;
            }
        }

        return null;
    }

    /**
     * Create color section if it doesn't exist
     */
    createColorSection() {
        const sidebar = document.querySelector('#simple-vc-sidebar .simple-vc-content');
        if (!sidebar) return null;

        const section = document.createElement('div');
        section.className = 'simple-vc-section';
        section.innerHTML = `
            <h4>🎨 Color Palettes</h4>
            <div id="simple-color-palette-container" class="simple-color-palette-container">
                <!-- Color interface will be injected here -->
            </div>
        `;

        sidebar.insertBefore(section, sidebar.firstChild);
        return section;
    }

    /**
     * Create typography section if it doesn't exist
     */
    createTypographySection() {
        const sidebar = document.querySelector('#simple-vc-sidebar .simple-vc-content');
        if (!sidebar) return null;

        const section = document.createElement('div');
        section.className = 'simple-vc-section';
        section.innerHTML = `
            <h4>📝 Typography</h4>
            <div class="typography-container">
                <!-- Typography interface will be injected here -->
            </div>
        `;

        // Insert after color section if it exists
        const colorSection = this.findSidebarSection('colors');
        if (colorSection) {
            sidebar.insertBefore(section, colorSection.nextSibling);
        } else {
            sidebar.appendChild(section);
        }

        return section;
    }

    /**
     * Setup color interaction handlers
     */
    setupColorInteractions(container) {
        $(container).on('click', '.palette-card', (e) => {
            const card = $(e.currentTarget);
            const paletteId = card.data('palette');
            const paletteData = card.data('palette-data');

            // Visual feedback
            container.querySelectorAll('.palette-card').forEach(c => c.classList.remove('selected'));
            card.addClass('selected');

            // Trigger selection event
            $(document).trigger('paletteSelected', { paletteId, paletteData });
        });

        // Add hover effects
        $(container).on('mouseenter', '.palette-card', (e) => {
            $(e.currentTarget).addClass('hover');
        });

        $(container).on('mouseleave', '.palette-card', (e) => {
            $(e.currentTarget).removeClass('hover');
        });
    }

    /**
     * Setup typography interaction handlers
     */
    setupTypographyInteractions(container) {
        $(container).on('click', '.typography-preview', (e) => {
            const preview = $(e.currentTarget);
            const pairingId = preview.data('pairing');
            const pairingData = preview.data('pairing-data');

            // Visual feedback
            container.querySelectorAll('.typography-preview').forEach(p => p.classList.remove('selected'));
            preview.addClass('selected');

            // Trigger selection event
            $(document).trigger('typographySelected', { pairingId, pairingData });
        });

        // Add hover effects
        $(container).on('mouseenter', '.typography-preview', (e) => {
            $(e.currentTarget).addClass('hover');
        });

        $(container).on('mouseleave', '.typography-preview', (e) => {
            $(e.currentTarget).removeClass('hover');
        });
    }

    /**
     * Refresh all interfaces
     */
    refreshInterfaces() {
        if (this.isInitialized) {
            this.log('🔄 Refreshing all interfaces');

            if (this.colorPaletteInterface) {
                this.colorPaletteInterface.refresh();
            }

            if (this.typographyInterface) {
                this.typographyInterface.refresh();
            }
        }
    }

    /**
     * Handle initialization error
     */
    handleInitializationError(error) {
        console.error('❌ Sidebar Token Bridge initialization failed:', error);

        // Show error in sidebar if possible
        const sidebar = document.querySelector('#simple-vc-sidebar .simple-vc-content');
        if (sidebar) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'bridge-error';
            errorDiv.innerHTML = `
                <div class="error-message">
                    <h4>⚠️ Visual Customizer Error</h4>
                    <p>Unable to initialize advanced customization features.</p>
                    <small>Error: ${error.message}</small>
                </div>
            `;
            sidebar.insertBefore(errorDiv, sidebar.firstChild);
        }
    }

    /**
     * Utility methods
     */
    getNestedProperty(obj, path) {
        return path.split('.').reduce((current, key) => current && current[key], obj);
    }

    delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    dispatchEvent(eventName, detail) {
        document.dispatchEvent(new CustomEvent(eventName, { detail }));
    }

    log(message, ...args) {
        if (this.debug) {
            console.log(`[SidebarTokenBridge] ${message}`, ...args);
        }
    }
}

// Auto-initialize when DOM is ready
$(document).ready(() => {
    // Initialize with slight delay to ensure all dependencies are loaded
    setTimeout(() => {
        window.sidebarTokenBridge = new SidebarTokenBridge();
    }, 500);
});
