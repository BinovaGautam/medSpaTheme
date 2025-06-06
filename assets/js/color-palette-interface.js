/**
 * Professional Visual Customizer - Color Palette Selection Interface
 * Version: 1.0.0
 * Interactive interface for palette selection with accessibility features
 *
 * CODE-GEN-WF-001 Implementation
 * Story: PVC-006 - Color Palette Selection Interface
 * Quality Gates: Visual design, Category filtering, Accessibility display, Live preview
 */

/**
 * ColorPaletteInterface - Visual Palette Selection Component
 * T6.1: Visual palette cards with color swatches and metadata
 * T6.2: Category filtering and search functionality
 * T6.3: Accessibility information display
 * T6.4: Live preview integration
 * T6.5: Responsive design with mobile optimization
 */
class ColorPaletteInterface {
    constructor(containerId, options = {}) {
        this.containerId = containerId;
        this.container = document.getElementById(containerId);

        if (!this.container) {
            throw new Error(`Container element with ID '${containerId}' not found`);
        }

        // Core system integration
        this.colorSystemManager = options.colorSystemManager || window.colorSystemManager;
        this.semanticColorSystem = options.semanticColorSystem || window.semanticColorSystem;

        // Configuration
        this.config = {
            enablePreview: options.enablePreview !== false,
            previewDelay: options.previewDelay || 500,
            enableAnimations: options.enableAnimations !== false,
            showAccessibilityInfo: options.showAccessibilityInfo !== false,
            enableSearch: options.enableSearch !== false,
            cardsPerRow: options.cardsPerRow || 'auto',
            compactMode: options.compactMode || false,
            ...options
        };

        // State
        this.state = {
            selectedCategory: 'all',
            selectedPalette: null,
            searchQuery: '',
            filteredPalettes: [],
            allPalettes: [],
            isPreviewMode: false,
            currentPreview: null
        };

        // UI Elements
        this.elements = {
            header: null,
            categoryFilter: null,
            searchInput: null,
            paletteGrid: null,
            accessibilityPanel: null,
            previewControls: null
        };

        // Event handlers
        this.previewTimer = null;

        // Initialize
        this.initialize();
    }

    /**
     * T6.1: Initialize the interface
     */
    async initialize() {
        try {
            console.log('🚀 Starting ColorPaletteInterface initialization...');

            // Load palettes data
            console.log('📊 Loading palettes data...');
            await this.loadPalettesData();
            console.log(`✅ Palettes loaded: ${this.state.allPalettes.length} total`);

            // Build interface
            console.log('🏗️ Building interface structure...');
            this.buildInterface();
            console.log('✅ Interface structure built');

            // Set up event listeners
            console.log('👂 Setting up event listeners...');
            this.setupEventListeners();
            console.log('✅ Event listeners attached');

            // Initial render
            console.log('🎨 Performing initial render...');
            this.render();
            console.log('✅ Initial render complete');

            console.log('✅ Color Palette Interface initialized successfully');
            return true;

        } catch (error) {
            console.error('❌ Color Palette Interface initialization failed:', error);
            console.error('Stack trace:', error.stack);

            // Show error in the interface
            if (this.container) {
                this.container.innerHTML = `
                    <div style="padding: 20px; background: #fef2f2; border: 2px solid #fecaca; border-radius: 8px; color: #dc2626;">
                        <h3>❌ Interface Initialization Failed</h3>
                        <p><strong>Error:</strong> ${error.message}</p>
                        <details>
                            <summary>Technical Details</summary>
                            <pre style="font-size: 11px; background: #f9fafb; padding: 10px; border-radius: 4px; margin-top: 10px;">${error.stack}</pre>
                        </details>
                    </div>
                `;
            }

            throw error;
        }
    }

    /**
     * Load palettes data from semantic color system
     */
    async loadPalettesData() {
        // Initialize semantic color system if not available
        if (!this.semanticColorSystem) {
            try {
                if (typeof SemanticColorSystem === 'function') {
                    this.semanticColorSystem = new SemanticColorSystem();
                    console.log('✅ SemanticColorSystem initialized');
                } else {
                    throw new Error('SemanticColorSystem class not available');
                }
            } catch (error) {
                console.error('❌ Failed to initialize SemanticColorSystem:', error);
                throw new Error('Cannot initialize without SemanticColorSystem');
            }
        }

        // Load palette data
        try {
            this.state.allPalettes = this.semanticColorSystem.getAllPalettes();
            this.state.filteredPalettes = [...this.state.allPalettes];

            console.log(`📊 Loaded ${this.state.allPalettes.length} palettes`);

            // Get current palette if available
            if (this.colorSystemManager) {
                const currentPalette = this.colorSystemManager.getCurrentPalette();
                if (currentPalette) {
                    this.state.selectedPalette = currentPalette.id;
                    console.log(`🎯 Current palette: ${currentPalette.id}`);
                }
            }
        } catch (error) {
            console.error('❌ Failed to load palette data:', error);
            throw error;
        }
    }

    /**
     * T6.1 & T6.5: Build the complete interface structure
     */
    buildInterface() {
        this.container.className = 'color-palette-interface';

        this.container.innerHTML = `
            <div class="palette-interface-header">
                <h3 class="interface-title">
                    <span class="title-icon">🎨</span>
                    Choose Your Color Palette
                </h3>
                <p class="interface-description">
                    Select from professional color palettes designed for medical spa excellence
                </p>
            </div>

            <div class="palette-controls">
                <div class="category-filters" id="${this.containerId}-categories">
                    <!-- Categories will be populated here -->
                </div>

                ${this.config.enableSearch ? `
                <div class="search-container">
                    <input type="text"
                           id="${this.containerId}-search"
                           class="palette-search"
                           placeholder="Search palettes..."
                           aria-label="Search color palettes">
                    <span class="search-icon">🔍</span>
                </div>
                ` : ''}
            </div>

            <div class="palette-grid" id="${this.containerId}-grid">
                <!-- Palette cards will be populated here -->
            </div>

            ${this.config.showAccessibilityInfo ? `
            <div class="accessibility-panel" id="${this.containerId}-accessibility">
                <h4>
                    <span class="panel-icon">♿</span>
                    Accessibility Information
                </h4>
                <div class="accessibility-content">
                    <p>Select a palette to view accessibility details</p>
                </div>
            </div>
            ` : ''}

            ${this.config.enablePreview ? `
            <div class="preview-controls" id="${this.containerId}-preview">
                <button class="preview-btn" data-action="toggle-preview" disabled>
                    <span class="btn-icon">👁️</span>
                    Preview Selected
                </button>
                <button class="apply-btn" data-action="apply-palette" disabled>
                    <span class="btn-icon">✨</span>
                    Apply Palette
                </button>
            </div>
            ` : ''}
        `;

        // Store element references
        this.elements.header = this.container.querySelector('.palette-interface-header');
        this.elements.categoryFilter = this.container.querySelector(`#${this.containerId}-categories`);
        this.elements.searchInput = this.container.querySelector(`#${this.containerId}-search`);
        this.elements.paletteGrid = this.container.querySelector(`#${this.containerId}-grid`);
        this.elements.accessibilityPanel = this.container.querySelector(`#${this.containerId}-accessibility`);
        this.elements.previewControls = this.container.querySelector(`#${this.containerId}-preview`);

        // Add CSS classes for styling
        this.addInterfaceStyles();
    }

    /**
     * T6.2: Build category filters
     */
    buildCategoryFilters() {
        if (!this.elements.categoryFilter) return;

        const categories = this.semanticColorSystem.getCategories();
        const categoryButtons = [
            { id: 'all', name: 'All Palettes', count: this.state.allPalettes.length }
        ];

        // Add categories with counts
        Object.entries(categories).forEach(([id, category]) => {
            const count = this.state.allPalettes.filter(p => p.category === id).length;

            // Ensure we have a proper display name
            let displayName = 'Unknown Category';
            if (category && typeof category === 'object') {
                displayName = category.displayName || category.name || id;
            } else if (typeof category === 'string') {
                displayName = category;
            }

            // Convert id to readable format if no display name available
            if (displayName === 'Unknown Category' || displayName === id) {
                displayName = id.split('-').map(word =>
                    word.charAt(0).toUpperCase() + word.slice(1)
                ).join(' ');
            }

            categoryButtons.push({
                id,
                name: displayName,
                count
            });
        });

        // Also check if we have palettes without recognized categories
        const recognizedCategories = Object.keys(categories);
        const unrecognizedPalettes = this.state.allPalettes.filter(p =>
            !recognizedCategories.includes(p.category)
        );

        if (unrecognizedPalettes.length > 0) {
            // Group unrecognized palettes by their category
            const unrecognizedCategories = {};
            unrecognizedPalettes.forEach(p => {
                if (!unrecognizedCategories[p.category]) {
                    unrecognizedCategories[p.category] = 0;
                }
                unrecognizedCategories[p.category]++;
            });

            // Add these as categories
            Object.entries(unrecognizedCategories).forEach(([categoryId, count]) => {
                const displayName = categoryId.split('-').map(word =>
                    word.charAt(0).toUpperCase() + word.slice(1)
                ).join(' ');

                categoryButtons.push({
                    id: categoryId,
                    name: displayName,
                    count
                });
            });
        }

        console.log('🔍 Building category filters:', categoryButtons);

        this.elements.categoryFilter.innerHTML = categoryButtons.map(cat => `
            <button class="category-filter-btn ${cat.id === this.state.selectedCategory ? 'active' : ''}"
                    data-category="${cat.id}"
                    aria-pressed="${cat.id === this.state.selectedCategory}">
                <span class="category-name">${cat.name}</span>
                <span class="category-count">${cat.count}</span>
            </button>
        `).join('');
    }

    /**
     * T6.1: Build palette grid with visual cards
     */
    buildPaletteGrid() {
        if (!this.elements.paletteGrid) return;

        if (this.state.filteredPalettes.length === 0) {
            this.elements.paletteGrid.innerHTML = `
                <div class="no-palettes-message">
                    <span class="message-icon">🎨</span>
                    <h4>No palettes found</h4>
                    <p>Try adjusting your search or category filter</p>
                </div>
            `;
            return;
        }

        this.elements.paletteGrid.innerHTML = this.state.filteredPalettes.map(palette =>
            this.createPaletteCard(palette)
        ).join('');
    }

    /**
     * T6.1: Create individual palette card
     */
    createPaletteCard(palette) {
        const isSelected = this.state.selectedPalette === palette.id;
        const accessibility = this.semanticColorSystem.validatePaletteAccessibility(palette.id);

        return `
            <div class="palette-card ${isSelected ? 'selected' : ''}"
                 data-palette-id="${palette.id}"
                 role="button"
                 tabindex="0"
                 aria-pressed="${isSelected}"
                 aria-describedby="palette-${palette.id}-desc">

                <div class="palette-header">
                    <h4 class="palette-name">${palette.name}</h4>
                    <div class="palette-badges">
                        ${this.createAccessibilityBadges(accessibility)}
                    </div>
                </div>

                <div class="color-swatches">
                    ${this.createColorSwatches(palette)}
                </div>

                <div class="palette-info" id="palette-${palette.id}-desc">
                    <p class="palette-description">${palette.description}</p>
                    <div class="palette-meta">
                        <span class="meta-item">
                            <span class="meta-icon">🏥</span>
                            ${this.getCategoryDisplayName(palette.category)}
                        </span>
                        <span class="meta-item">
                            <span class="meta-icon">🎯</span>
                            ${palette.psychology?.emotion || 'Professional'}
                        </span>
                    </div>
                </div>

                ${isSelected ? `
                <div class="selection-indicator">
                    <span class="selection-icon">✓</span>
                    Currently Active
                </div>
                ` : ''}
            </div>
        `;
    }

    /**
     * T6.1: Create color swatches for palette preview
     */
    createColorSwatches(palette) {
        const colors = palette.colors;
        const swatchOrder = ['primary', 'secondary', 'accent', 'surface', 'background'];

        return swatchOrder.map(role => {
            const color = colors[role];
            if (!color) return '';

            return `
                <div class="color-swatch"
                     style="background-color: ${color.hex};"
                     title="${role}: ${color.hex}"
                     aria-label="${role} color: ${color.hex}">
                    <span class="swatch-label">${role.charAt(0).toUpperCase()}</span>
                </div>
            `;
        }).join('');
    }

    /**
     * T6.3: Create accessibility badges
     */
    createAccessibilityBadges(accessibility) {
        const badges = [];

        if (accessibility.overallScore >= 90) {
            badges.push('<span class="badge badge-excellent" title="Excellent accessibility">AAA</span>');
        } else if (accessibility.overallScore >= 70) {
            badges.push('<span class="badge badge-good" title="Good accessibility">AA</span>');
        } else {
            badges.push('<span class="badge badge-warning" title="Accessibility concerns">⚠️</span>');
        }

        if (accessibility.colorBlindFriendly) {
            badges.push('<span class="badge badge-colorblind" title="Color blind friendly">🎨</span>');
        }

        return badges.join('');
    }

    /**
     * Get category display name
     */
    getCategoryDisplayName(categoryId) {
        const categories = this.semanticColorSystem.getCategories();
        return categories[categoryId]?.displayName || categoryId;
    }

    /**
     * T6.3: Update accessibility panel with selected palette info
     */
    updateAccessibilityPanel(paletteId) {
        if (!this.elements.accessibilityPanel) return;

        const contentDiv = this.elements.accessibilityPanel.querySelector('.accessibility-content');

        if (!paletteId) {
            contentDiv.innerHTML = '<p>Select a palette to view accessibility details</p>';
            return;
        }

        try {
            // Ensure semantic color system is available
            if (!this.semanticColorSystem) {
                contentDiv.innerHTML = '<p>⏳ Loading accessibility information...</p>';
                return;
            }

            const accessibility = this.semanticColorSystem.validatePaletteAccessibility(paletteId);
            const palette = this.semanticColorSystem.getPalette(paletteId);

            if (!accessibility || !palette) {
                contentDiv.innerHTML = '<p>❌ Unable to load accessibility information</p>';
                return;
            }

            console.log(`♿ Updating accessibility panel for: ${paletteId}`, accessibility);

            // Generate contrast ratios if not provided by semantic system
            let contrastRatios = accessibility.contrastRatios || {};

            if (!contrastRatios || Object.keys(contrastRatios).length === 0) {
                console.log('⚠️ No contrast ratios in accessibility data, generating fallback...');
                contrastRatios = this.generateContrastRatios(palette);
                console.log('✅ Generated contrast ratios:', contrastRatios);
            }

            // Calculate overall score if not provided
            let overallScore = accessibility.overallScore;
            if (overallScore === undefined || overallScore === null || isNaN(overallScore)) {
                console.log('⚠️ No overall score available, calculating fallback...');
                overallScore = this.calculateAccessibilityScore(accessibility, contrastRatios);
                console.log('✅ Calculated accessibility score:', overallScore);
            }

            contentDiv.innerHTML = `
                <div class="accessibility-summary">
                    <div class="score-display">
                        <div class="score-circle ${this.getScoreClass(overallScore)}">
                            <span class="score-number">${overallScore}</span>
                            <span class="score-label">Score</span>
                        </div>
                        <div class="score-details">
                            <h5>${palette.name}</h5>
                            <p class="score-description">
                                ${this.getScoreDescription(overallScore)}
                            </p>
                        </div>
                    </div>

                    <div class="accessibility-metrics">
                        <div class="metric-row">
                            <span class="metric-label">WCAG AA Compliance:</span>
                            <span class="metric-value ${accessibility.wcagAACompliant ? 'compliant' : 'non-compliant'}">
                                ${accessibility.wcagAACompliant ? '✅ Compliant' : '❌ Non-compliant'}
                            </span>
                        </div>
                        <div class="metric-row">
                            <span class="metric-label">WCAG AAA Titles:</span>
                            <span class="metric-value">
                                ${accessibility.titleContrast && accessibility.titleContrast.aaaCompliant ? '✅ Excellent' : '⚠️ Standard'}
                            </span>
                        </div>
                        <div class="metric-row">
                            <span class="metric-label">Color Blind Support:</span>
                            <span class="metric-value ${accessibility.colorBlindFriendly ? 'supported' : 'limited'}">
                                ${accessibility.colorBlindFriendly ? '✅ Supported' : '⚠️ Limited'}
                            </span>
                        </div>
                        <div class="metric-row">
                            <span class="metric-label">Text Readability:</span>
                            <span class="metric-value">
                                ${accessibility.readabilityScore >= 80 ? '✅ Excellent' :
                                  accessibility.readabilityScore >= 60 ? '⚠️ Good' : '❌ Poor'}
                            </span>
                        </div>
                    </div>

                    <div class="contrast-details">
                        <h6>Contrast Ratios</h6>
                        <div class="contrast-grid">
                            ${Object.keys(contrastRatios).length > 0 ?
                                Object.entries(contrastRatios).map(([textType, ratio]) => `
                                    <div class="contrast-item">
                                        <span class="contrast-type">${this.formatTextType(textType)}:</span>
                                        <span class="contrast-ratio ${this.getContrastClass(ratio)}">${ratio.toFixed(1)}:1</span>
                                    </div>
                                `).join('') :
                                '<p>No contrast ratio data available</p>'
                            }
                        </div>
                    </div>
                </div>
            `;
        } catch (error) {
            console.error('Error updating accessibility panel:', error);
            contentDiv.innerHTML = `
                <p>❌ Error loading accessibility information</p>
                <pre style="font-size: 11px; color: #666;">${error.message}</pre>
            `;
        }
    }

    /**
     * Generate contrast ratios for palette colors if not provided
     */
    generateContrastRatios(palette) {
        const contrastRatios = {};
        const colors = palette.colors;

        if (!colors) return contrastRatios;

        try {
            // Use ContrastCalculator if available, otherwise basic calculation
            const calculateContrast = (color1, color2) => {
                if (typeof ContrastCalculator !== 'undefined' && ContrastCalculator.calculateContrast) {
                    return ContrastCalculator.calculateContrast(color1, color2);
                }
                // Fallback basic contrast calculation
                return this.basicContrastCalculation(color1, color2);
            };

            // Primary text on background
            if (colors.primary && colors.background) {
                contrastRatios.primaryText = calculateContrast(colors.primary.hex, colors.background.hex);
            }

            // Secondary text on background
            if (colors.secondary && colors.background) {
                contrastRatios.secondaryText = calculateContrast(colors.secondary.hex, colors.background.hex);
            }

            // Accent text on background
            if (colors.accent && colors.background) {
                contrastRatios.accentText = calculateContrast(colors.accent.hex, colors.background.hex);
            }

            // Text on surface
            if (colors.primary && colors.surface) {
                contrastRatios.textOnSurface = calculateContrast(colors.primary.hex, colors.surface.hex);
            }

            // White text on primary (common pattern)
            if (colors.primary) {
                contrastRatios.whiteOnPrimary = calculateContrast('#ffffff', colors.primary.hex);
            }

            // Black text on surface
            if (colors.surface) {
                contrastRatios.blackOnSurface = calculateContrast('#000000', colors.surface.hex);
            }

            console.log('📊 Generated contrast ratios for', palette.name, contrastRatios);

        } catch (error) {
            console.error('Error generating contrast ratios:', error);
        }

        return contrastRatios;
    }

    /**
     * Basic contrast calculation fallback
     */
    basicContrastCalculation(color1, color2) {
        try {
            // Convert hex to RGB
            const getRGB = (hex) => {
                const r = parseInt(hex.slice(1, 3), 16);
                const g = parseInt(hex.slice(3, 5), 16);
                const b = parseInt(hex.slice(5, 7), 16);
                return [r, g, b];
            };

            // Calculate relative luminance
            const getLuminance = (rgb) => {
                const [r, g, b] = rgb.map(c => {
                    c = c / 255;
                    return c <= 0.03928 ? c / 12.92 : Math.pow((c + 0.055) / 1.055, 2.4);
                });
                return 0.2126 * r + 0.7152 * g + 0.0722 * b;
            };

            const rgb1 = getRGB(color1);
            const rgb2 = getRGB(color2);
            const lum1 = getLuminance(rgb1);
            const lum2 = getLuminance(rgb2);

            const brightest = Math.max(lum1, lum2);
            const darkest = Math.min(lum1, lum2);

            return (brightest + 0.05) / (darkest + 0.05);
        } catch (error) {
            console.error('Error in basic contrast calculation:', error);
            return 1; // Fallback to minimum contrast
        }
    }

    /**
     * Helper methods for accessibility display
     */
    getScoreClass(score) {
        if (score >= 90) return 'excellent';
        if (score >= 70) return 'good';
        if (score >= 50) return 'fair';
        return 'poor';
    }

    getScoreDescription(score) {
        if (score >= 90) return 'Excellent accessibility with AAA compliance for key elements';
        if (score >= 70) return 'Good accessibility with solid WCAG AA compliance';
        if (score >= 50) return 'Fair accessibility with some areas for improvement';
        return 'Poor accessibility - consider choosing a different palette';
    }

    getContrastClass(ratio) {
        if (ratio >= 7.0) return 'aaa-compliant';
        if (ratio >= 4.5) return 'aa-compliant';
        return 'non-compliant';
    }

    formatTextType(textType) {
        return textType.replace(/([A-Z])/g, ' $1').replace(/^./, str => str.toUpperCase());
    }

    /**
     * T6.2: Filter palettes based on category and search
     */
    filterPalettes() {
        let filtered = [...this.state.allPalettes];

        // Category filter
        if (this.state.selectedCategory !== 'all') {
            filtered = filtered.filter(palette => palette.category === this.state.selectedCategory);
        }

        // Search filter
        if (this.state.searchQuery) {
            const query = this.state.searchQuery.toLowerCase();
            filtered = filtered.filter(palette =>
                palette.name.toLowerCase().includes(query) ||
                palette.description.toLowerCase().includes(query) ||
                (palette.psychology?.emotion || '').toLowerCase().includes(query)
            );
        }

        this.state.filteredPalettes = filtered;
    }

    /**
     * T6.4: Handle palette selection
     */
    selectPalette(paletteId) {
        console.log(`🎨 selectPalette called with: ${paletteId}`);

        const previousSelection = this.state.selectedPalette;
        this.state.selectedPalette = paletteId;

        try {
            // Update UI
            this.updatePaletteSelection();
            console.log(`✅ Palette selection UI updated`);

            // Update accessibility panel
            this.updateAccessibilityPanel(paletteId);
            console.log(`✅ Accessibility panel updated`);

            // Enable preview/apply buttons
            this.updatePreviewControls(true);
            console.log(`✅ Preview controls updated`);

            // Get palette data for event
            const palette = this.semanticColorSystem ? this.semanticColorSystem.getPalette(paletteId) : null;

            // Emit selection event
            const eventData = {
                paletteId,
                previousSelection,
                palette
            };

            this.emit('paletteSelected', eventData);
            console.log(`📡 paletteSelected event emitted:`, eventData);

        } catch (error) {
            console.error('❌ Error in selectPalette:', error);
        }
    }

    /**
     * Update visual selection state
     */
    updatePaletteSelection() {
        const cards = this.elements.paletteGrid.querySelectorAll('.palette-card');
        cards.forEach(card => {
            const cardPaletteId = card.getAttribute('data-palette-id');
            const isSelected = cardPaletteId === this.state.selectedPalette;

            card.classList.toggle('selected', isSelected);
            card.setAttribute('aria-pressed', isSelected);

            // Update selection indicator
            const existingIndicator = card.querySelector('.selection-indicator');
            if (existingIndicator) {
                existingIndicator.remove();
            }

            if (isSelected) {
                card.insertAdjacentHTML('beforeend', `
                    <div class="selection-indicator">
                        <span class="selection-icon">✓</span>
                        Currently Selected
                    </div>
                `);
            }
        });
    }

    /**
     * T6.4: Update preview control buttons
     */
    updatePreviewControls(hasSelection) {
        if (!this.elements.previewControls) return;

        const previewBtn = this.elements.previewControls.querySelector('[data-action="toggle-preview"]');
        const applyBtn = this.elements.previewControls.querySelector('[data-action="apply-palette"]');

        if (previewBtn) {
            previewBtn.disabled = !hasSelection;

            // Update button text with proper HTML structure
            const isPreviewMode = this.state.isPreviewMode;
            previewBtn.innerHTML = `
                <span class="btn-icon">👁️</span>
                ${isPreviewMode ? 'Exit Preview' : 'Preview Selected'}
            `;

            console.log(`🎯 Preview button updated: ${hasSelection ? 'enabled' : 'disabled'}, mode: ${isPreviewMode ? 'exit' : 'preview'}`);
        }

        if (applyBtn) {
            applyBtn.disabled = !hasSelection;
            console.log(`🎯 Apply button updated: ${hasSelection ? 'enabled' : 'disabled'}`);
        }
    }

    /**
     * T6.4: Handle preview mode
     */
    async togglePreview() {
        if (!this.state.selectedPalette || !this.colorSystemManager) return;

        if (this.state.isPreviewMode) {
            // Exit preview mode
            this.colorSystemManager.exitPreviewMode();
            this.state.isPreviewMode = false;
            this.state.currentPreview = null;
        } else {
            // Enter preview mode
            this.colorSystemManager.enterPreviewMode(this.state.selectedPalette);
            this.state.isPreviewMode = true;
            this.state.currentPreview = this.state.selectedPalette;
        }

        this.updatePreviewControls(true);

        this.emit('previewToggled', {
            isPreviewMode: this.state.isPreviewMode,
            paletteId: this.state.selectedPalette
        });
    }

    /**
     * T6.4: Apply selected palette
     */
    async applyPalette() {
        if (!this.state.selectedPalette || !this.colorSystemManager) return;

        try {
            await this.colorSystemManager.switchPalette(this.state.selectedPalette, {
                source: 'interface',
                forceSave: true
            });

            // Exit preview mode if active
            if (this.state.isPreviewMode) {
                this.state.isPreviewMode = false;
                this.state.currentPreview = null;
                this.updatePreviewControls(true);
            }

            this.emit('paletteApplied', {
                paletteId: this.state.selectedPalette,
                palette: this.semanticColorSystem.getPalette(this.state.selectedPalette)
            });

            // Show success feedback
            this.showSuccessMessage('Palette applied successfully!');

        } catch (error) {
            console.error('Failed to apply palette:', error);
            this.showError('Failed to apply palette. Please try again.');
        }
    }

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        // Category filter clicks
        if (this.elements.categoryFilter) {
            this.elements.categoryFilter.addEventListener('click', (e) => {
                const btn = e.target.closest('.category-filter-btn');
                if (!btn) return;

                const category = btn.getAttribute('data-category');

                // Update state and visual feedback first
                this.state.selectedCategory = category;

                // Update active button state immediately
                this.elements.categoryFilter.querySelectorAll('.category-filter-btn').forEach(button => {
                    button.classList.remove('active');
                    button.setAttribute('aria-pressed', 'false');
                });
                btn.classList.add('active');
                btn.setAttribute('aria-pressed', 'true');

                // Then filter and render
                this.filterAndRender();

                console.log(`🔍 Category filter selected: ${category}`);
            });
        }

        // Search input
        if (this.elements.searchInput) {
            this.elements.searchInput.addEventListener('input', (e) => {
                this.state.searchQuery = e.target.value.trim();
                this.filterAndRender();
                console.log(`🔍 Search query: "${this.state.searchQuery}"`);
            });
        }

        // Palette card selection
        this.elements.paletteGrid.addEventListener('click', (e) => {
            const card = e.target.closest('.palette-card');
            if (!card) return;

            const paletteId = card.getAttribute('data-palette-id');
            console.log(`🎨 Palette selected: ${paletteId}`);
            this.selectPalette(paletteId);
        });

        // Keyboard navigation for palette cards
        this.elements.paletteGrid.addEventListener('keydown', (e) => {
            const card = e.target.closest('.palette-card');
            if (!card) return;

            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const paletteId = card.getAttribute('data-palette-id');
                console.log(`🎨 Palette selected via keyboard: ${paletteId}`);
                this.selectPalette(paletteId);
            }
        });

        // Preview controls
        if (this.elements.previewControls) {
            this.elements.previewControls.addEventListener('click', (e) => {
                const btn = e.target.closest('button');
                if (!btn) return;

                const action = btn.getAttribute('data-action');
                console.log(`🎯 Preview action: ${action}`);

                if (action === 'toggle-preview') {
                    this.togglePreview();
                } else if (action === 'apply-palette') {
                    this.applyPalette();
                }
            });
        }

        // Listen to color system manager events
        if (this.colorSystemManager) {
            document.addEventListener('colorManager:palette:switched', (e) => {
                const { paletteId } = e.detail;
                if (paletteId !== this.state.selectedPalette) {
                    this.state.selectedPalette = paletteId;
                    this.updatePaletteSelection();
                    this.updateAccessibilityPanel(paletteId);
                }
            });
        }
    }

    /**
     * Filter and re-render interface
     */
    filterAndRender() {
        this.filterPalettes();
        this.render();
    }

    /**
     * Main render method
     */
    render() {
        this.buildCategoryFilters();
        this.buildPaletteGrid();

        if (this.state.selectedPalette) {
            this.updateAccessibilityPanel(this.state.selectedPalette);
            this.updatePreviewControls(true);
        }
    }

    /**
     * Show success message
     */
    showSuccessMessage(message) {
        const notification = document.createElement('div');
        notification.className = 'palette-notification success';
        notification.innerHTML = `
            <span class="notification-icon">✅</span>
            <span class="notification-message">${message}</span>
        `;

        this.container.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    /**
     * Show error message
     */
    showError(message) {
        const notification = document.createElement('div');
        notification.className = 'palette-notification error';
        notification.innerHTML = `
            <span class="notification-icon">❌</span>
            <span class="notification-message">${message}</span>
        `;

        this.container.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    /**
     * Event emission
     */
    emit(eventType, data) {
        const event = new CustomEvent(`paletteInterface:${eventType}`, { detail: data });
        document.dispatchEvent(event);
    }

    /**
     * T6.5: Add responsive CSS styles
     */
    addInterfaceStyles() {
        if (document.getElementById('color-palette-interface-styles')) return;

        const style = document.createElement('style');
        style.id = 'color-palette-interface-styles';
        style.textContent = `
            /* Color Palette Interface Styles */
            .color-palette-interface {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                max-width: 1200px;
                margin: 0 auto;
                padding: 20px;
                background: #ffffff;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }

            .palette-interface-header {
                text-align: center;
                margin-bottom: 30px;
                padding-bottom: 20px;
                border-bottom: 2px solid #f1f5f9;
            }

            .interface-title {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                margin: 0 0 10px 0;
                font-size: 24px;
                color: #1e293b;
            }

            .interface-description {
                color: #64748b;
                margin: 0;
                font-size: 16px;
            }

            .palette-controls {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 30px;
                padding: 20px;
                background: #f8fafc;
                border-radius: 6px;
            }

            .category-filters {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            }

            .category-filter-btn {
                display: flex;
                align-items: center;
                gap: 6px;
                padding: 8px 16px;
                border: 2px solid #e2e8f0;
                background: white;
                border-radius: 20px;
                font-size: 14px;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .category-filter-btn:hover {
                border-color: #3b82f6;
                background: #eff6ff;
            }

            .category-filter-btn.active {
                border-color: #3b82f6;
                background: #3b82f6;
                color: white;
            }

            .category-count {
                background: rgba(255,255,255,0.2);
                padding: 2px 6px;
                border-radius: 10px;
                font-size: 12px;
                font-weight: bold;
            }

            .search-container {
                position: relative;
                min-width: 250px;
            }

            .palette-search {
                width: 100%;
                padding: 10px 40px 10px 12px;
                border: 2px solid #e2e8f0;
                border-radius: 6px;
                font-size: 14px;
            }

            .palette-search:focus {
                outline: none;
                border-color: #3b82f6;
            }

            .search-icon {
                position: absolute;
                right: 12px;
                top: 50%;
                transform: translateY(-50%);
                color: #64748b;
            }

            .palette-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
                gap: 20px;
                margin-bottom: 30px;
            }

            .palette-card {
                background: white;
                border: 2px solid #e2e8f0;
                border-radius: 8px;
                padding: 20px;
                cursor: pointer;
                transition: all 0.3s ease;
                position: relative;
            }

            .palette-card:hover {
                border-color: #3b82f6;
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
                transform: translateY(-2px);
            }

            .palette-card.selected {
                border-color: #059669;
                background: #f0fdf4;
                box-shadow: 0 4px 12px rgba(5, 150, 105, 0.2);
            }

            .palette-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                margin-bottom: 15px;
            }

            .palette-name {
                margin: 0;
                font-size: 18px;
                color: #1e293b;
            }

            .palette-badges {
                display: flex;
                gap: 4px;
            }

            .badge {
                padding: 2px 6px;
                border-radius: 4px;
                font-size: 10px;
                font-weight: bold;
                text-transform: uppercase;
            }

            .badge-excellent {
                background: #059669;
                color: white;
            }

            .badge-good {
                background: #0284c7;
                color: white;
            }

            .badge-warning {
                background: #d97706;
                color: white;
            }

            .badge-colorblind {
                background: #7c3aed;
                color: white;
            }

            .color-swatches {
                display: flex;
                gap: 8px;
                margin-bottom: 15px;
            }

            .color-swatch {
                flex: 1;
                height: 40px;
                border-radius: 4px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                font-weight: bold;
                color: white;
                text-shadow: 0 1px 2px rgba(0,0,0,0.5);
            }

            .palette-description {
                color: #64748b;
                font-size: 14px;
                margin: 0 0 10px 0;
                line-height: 1.5;
            }

            .palette-meta {
                display: flex;
                gap: 15px;
                font-size: 12px;
                color: #64748b;
            }

            .meta-item {
                display: flex;
                align-items: center;
                gap: 4px;
            }

            .selection-indicator {
                position: absolute;
                top: -2px;
                right: -2px;
                background: #059669;
                color: white;
                padding: 4px 8px;
                border-radius: 0 6px 0 6px;
                font-size: 12px;
                font-weight: bold;
                display: flex;
                align-items: center;
                gap: 4px;
            }

            .accessibility-panel {
                background: #f8fafc;
                border: 2px solid #e2e8f0;
                border-radius: 8px;
                padding: 20px;
                margin-bottom: 20px;
            }

            .accessibility-panel h4 {
                display: flex;
                align-items: center;
                gap: 8px;
                margin: 0 0 15px 0;
                color: #1e293b;
            }

            .accessibility-summary {
                display: grid;
                gap: 20px;
            }

            .score-display {
                display: flex;
                gap: 20px;
                align-items: center;
            }

            .score-circle {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: bold;
            }

            .score-circle.excellent { background: #059669; }
            .score-circle.good { background: #0284c7; }
            .score-circle.fair { background: #d97706; }
            .score-circle.poor { background: #dc2626; }

            .score-number {
                font-size: 24px;
            }

            .score-label {
                font-size: 10px;
                text-transform: uppercase;
            }

            .accessibility-metrics {
                display: grid;
                gap: 8px;
            }

            .metric-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 8px 0;
                border-bottom: 1px solid #e2e8f0;
            }

            .metric-value.compliant,
            .metric-value.supported {
                color: #059669;
                font-weight: bold;
            }

            .metric-value.non-compliant,
            .metric-value.limited {
                color: #d97706;
                font-weight: bold;
            }

            .contrast-details h6 {
                margin: 15px 0 10px 0;
                color: #1e293b;
            }

            .contrast-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 8px;
            }

            .contrast-item {
                display: flex;
                justify-content: space-between;
                padding: 6px 8px;
                background: white;
                border-radius: 4px;
                font-size: 13px;
            }

            .contrast-ratio.aaa-compliant { color: #059669; font-weight: bold; }
            .contrast-ratio.aa-compliant { color: #0284c7; font-weight: bold; }
            .contrast-ratio.non-compliant { color: #dc2626; font-weight: bold; }

            .preview-controls {
                display: flex;
                gap: 12px;
                justify-content: center;
                padding: 20px;
                border-top: 2px solid #f1f5f9;
            }

            .preview-btn,
            .apply-btn {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 12px 24px;
                border: none;
                border-radius: 6px;
                font-size: 16px;
                font-weight: bold;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .preview-btn {
                background: #3b82f6;
                color: white;
            }

            .preview-btn:hover:not(:disabled) {
                background: #2563eb;
            }

            .apply-btn {
                background: #059669;
                color: white;
            }

            .apply-btn:hover:not(:disabled) {
                background: #047857;
            }

            .preview-btn:disabled,
            .apply-btn:disabled {
                background: #94a3b8;
                cursor: not-allowed;
                opacity: 0.6;
            }

            .palette-notification {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 12px 20px;
                border-radius: 6px;
                display: flex;
                align-items: center;
                gap: 8px;
                font-weight: bold;
                z-index: 1000;
                animation: slideIn 0.3s ease;
            }

            .palette-notification.success {
                background: #f0fdf4;
                color: #059669;
                border: 2px solid #bbf7d0;
            }

            .palette-notification.error {
                background: #fef2f2;
                color: #dc2626;
                border: 2px solid #fecaca;
            }

            .no-palettes-message {
                grid-column: 1 / -1;
                text-align: center;
                padding: 60px 20px;
                color: #64748b;
            }

            .message-icon {
                font-size: 48px;
                display: block;
                margin-bottom: 16px;
            }

            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .color-palette-interface {
                    padding: 15px;
                }

                .palette-controls {
                    flex-direction: column;
                    align-items: stretch;
                }

                .category-filters {
                    justify-content: center;
                }

                .search-container {
                    min-width: auto;
                }

                .palette-grid {
                    grid-template-columns: 1fr;
                    gap: 15px;
                }

                .score-display {
                    flex-direction: column;
                    text-align: center;
                }

                .contrast-grid {
                    grid-template-columns: 1fr;
                }

                .preview-controls {
                    flex-direction: column;
                }

                .palette-notification {
                    position: relative;
                    top: auto;
                    right: auto;
                    margin: 10px 0;
                }
            }

            @media (max-width: 480px) {
                .interface-title {
                    font-size: 20px;
                }

                .palette-card {
                    padding: 15px;
                }

                .color-swatches {
                    height: 30px;
                }

                .color-swatch {
                    height: 30px;
                }
            }
        `;

        document.head.appendChild(style);
    }

    /**
     * Cleanup method
     */
    destroy() {
        if (this.previewTimer) {
            clearTimeout(this.previewTimer);
        }

        // Remove styles
        const styles = document.getElementById('color-palette-interface-styles');
        if (styles) {
            styles.remove();
        }

        // Clear container
        if (this.container) {
            this.container.innerHTML = '';
        }
    }

    /**
     * Calculate accessibility score when not provided by semantic system
     */
    calculateAccessibilityScore(accessibility, contrastRatios) {
        let score = 0;
        let maxScore = 100;

        try {
            // WCAG AA Compliance (30 points)
            if (accessibility.wcagAACompliant) {
                score += 30;
            } else {
                score += 10; // Partial credit
            }

            // Contrast ratios analysis (40 points)
            if (contrastRatios && Object.keys(contrastRatios).length > 0) {
                const ratios = Object.values(contrastRatios);
                const aaaCompliant = ratios.filter(r => r >= 7.0).length;
                const aaCompliant = ratios.filter(r => r >= 4.5 && r < 7.0).length;
                const total = ratios.length;

                // Calculate contrast score
                const contrastScore = ((aaaCompliant * 2 + aaCompliant) / (total * 2)) * 40;
                score += Math.round(contrastScore);
            } else {
                score += 20; // Default if no contrast data
            }

            // Color blind support (15 points)
            if (accessibility.colorBlindFriendly) {
                score += 15;
            } else {
                score += 5; // Partial credit
            }

            // Text readability (15 points)
            if (accessibility.readabilityScore !== undefined) {
                score += Math.round((accessibility.readabilityScore / 100) * 15);
            } else {
                score += 8; // Default moderate score
            }

            // Ensure score is within bounds
            score = Math.max(0, Math.min(maxScore, score));

            console.log('📊 Accessibility score breakdown:', {
                wcagCompliance: accessibility.wcagAACompliant ? 30 : 10,
                contrastRatios: contrastRatios ? Object.keys(contrastRatios).length : 0,
                colorBlindSupport: accessibility.colorBlindFriendly ? 15 : 5,
                readability: accessibility.readabilityScore,
                totalScore: score
            });

        } catch (error) {
            console.error('Error calculating accessibility score:', error);
            score = 50; // Fallback to moderate score
        }

        return score;
    }
}

// Export for both ES6 modules and global scope
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ColorPaletteInterface;
} else if (typeof window !== 'undefined') {
    window.ColorPaletteInterface = ColorPaletteInterface;
}
