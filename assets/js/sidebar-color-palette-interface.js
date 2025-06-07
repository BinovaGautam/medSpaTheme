/**
 * Sidebar Color Palette Interface - PVC-008-CRITICAL
 * Visual color palette selector for sidebar integration
 *
 * Renders actual color swatches instead of dropdown menus
 * Integrates with Design Token System and Universal Customization Engine
 *
 * @since Sprint 2 Extension
 * @version 1.0.0
 */

class SidebarColorPaletteInterface {
    constructor(bridge) {
        this.bridge = bridge;
        this.palettes = [];
        this.currentPalette = null;
        this.isLoading = false;

        this.log('🎨 Initializing Sidebar Color Palette Interface...');
        this.loadPalettes();
    }

    /**
     * Load available color palettes
     */
    async loadPalettes() {
        this.isLoading = true;

        try {
            // Get palettes from multiple sources
            await Promise.all([
                this.loadFromColorDomainSystem(),
                this.loadFromSemanticColorSystem(),
                this.loadFromPSASBSystem(),
                this.loadFallbackPalettes()
            ]);

            // Remove duplicates and sort
            this.palettes = this.deduplicatePalettes();
            this.log(`✅ Loaded ${this.palettes.length} color palettes`);

        } catch (error) {
            console.error('❌ Error loading palettes:', error);
            this.loadFallbackPalettes();
        } finally {
            this.isLoading = false;
        }
    }

    /**
     * Load palettes from Color Domain System
     */
    async loadFromColorDomainSystem() {
        if (window.colorDomainGenerator) {
            try {
                const domainPalettes = await window.colorDomainGenerator.getAllPalettes();
                if (domainPalettes && domainPalettes.length > 0) {
                    this.palettes.push(...domainPalettes.map(p => this.normalizePalette(p, 'domain')));
                    this.log('✅ Loaded palettes from Color Domain System');
                }
            } catch (error) {
                this.log('⚠️ Could not load from Color Domain System:', error.message);
            }
        }
    }

    /**
     * Load palettes from Semantic Color System
     */
    async loadFromSemanticColorSystem() {
        if (window.semanticColorSystem) {
            try {
                const semanticPalettes = await window.semanticColorSystem.getAvailablePalettes();
                if (semanticPalettes && semanticPalettes.length > 0) {
                    this.palettes.push(...semanticPalettes.map(p => this.normalizePalette(p, 'semantic')));
                    this.log('✅ Loaded palettes from Semantic Color System');
                }
            } catch (error) {
                this.log('⚠️ Could not load from Semantic Color System:', error.message);
            }
        }
    }

    /**
     * Load palettes from PSASB System
     */
    async loadFromPSASBSystem() {
        if (window.psasbColorPalettes || window.PSASB_PALETTES) {
            try {
                const psasbPalettes = window.psasbColorPalettes || window.PSASB_PALETTES;
                if (psasbPalettes && Array.isArray(psasbPalettes)) {
                    this.palettes.push(...psasbPalettes.map(p => this.normalizePalette(p, 'psasb')));
                    this.log('✅ Loaded palettes from PSASB System');
                }
            } catch (error) {
                this.log('⚠️ Could not load from PSASB System:', error.message);
            }
        }
    }

    /**
     * Load fallback palettes if nothing else works
     */
    loadFallbackPalettes() {
        const fallbackPalettes = [
            {
                id: 'medical-clean',
                name: 'Medical Clean',
                description: 'Professional medical spa aesthetic',
                colors: {
                    primary: '#87A96B',
                    secondary: '#1B365D',
                    accent: '#F8F5F2',
                    surface: '#FFFFFF',
                    background: '#F9FAFE'
                },
                category: 'medical',
                source: 'fallback'
            },
            {
                id: 'luxury-spa',
                name: 'Luxury Spa',
                description: 'Elegant luxury spa experience',
                colors: {
                    primary: '#9B7B5C',
                    secondary: '#2C3E4C',
                    accent: '#F4F0EC',
                    surface: '#FFFFFF',
                    background: '#FAF8F6'
                },
                category: 'luxury',
                source: 'fallback'
            },
            {
                id: 'calming-oasis',
                name: 'Calming Oasis',
                description: 'Tranquil and peaceful atmosphere',
                colors: {
                    primary: '#7BA098',
                    secondary: '#4A5D5A',
                    accent: '#E8F2F0',
                    surface: '#FFFFFF',
                    background: '#F0F7F6'
                },
                category: 'wellness',
                source: 'fallback'
            },
            {
                id: 'professional-medical',
                name: 'Professional Medical',
                description: 'Clean professional medical environment',
                colors: {
                    primary: '#4A90A4',
                    secondary: '#2C3E50',
                    accent: '#E8F4F8',
                    surface: '#FFFFFF',
                    background: '#F8FAFB'
                },
                category: 'medical',
                source: 'fallback'
            },
            {
                id: 'earth-wellness',
                name: 'Earth Wellness',
                description: 'Natural earth-inspired tones',
                colors: {
                    primary: '#A67B5B',
                    secondary: '#3E2723',
                    accent: '#F5F1ED',
                    surface: '#FFFFFF',
                    background: '#FAF7F4'
                },
                category: 'natural',
                source: 'fallback'
            },
            {
                id: 'boutique-elegant',
                name: 'Boutique Elegant',
                description: 'Sophisticated boutique aesthetic',
                colors: {
                    primary: '#8E6B8E',
                    secondary: '#3A2A3A',
                    accent: '#F3F0F3',
                    surface: '#FFFFFF',
                    background: '#F9F7F9'
                },
                category: 'boutique',
                source: 'fallback'
            },
            {
                id: 'modern-wellness',
                name: 'Modern Wellness',
                description: 'Contemporary wellness center',
                colors: {
                    primary: '#6B9F7A',
                    secondary: '#2E4D36',
                    accent: '#EDF5EF',
                    surface: '#FFFFFF',
                    background: '#F7FBF8'
                },
                category: 'modern',
                source: 'fallback'
            }
        ];

        this.palettes.push(...fallbackPalettes.map(p => this.normalizePalette(p, 'fallback')));
        this.log('✅ Loaded fallback palettes');
    }

    /**
     * Normalize palette data structure
     */
    normalizePalette(palette, source) {
        // Ensure consistent structure
        const normalized = {
            id: palette.id || palette.name?.toLowerCase().replace(/\s+/g, '-') || `palette-${Date.now()}`,
            name: palette.name || palette.id || 'Unnamed Palette',
            description: palette.description || '',
            category: palette.category || 'general',
            source: source,
            colors: {},
            metadata: palette.metadata || {}
        };

        // Normalize color structure
        if (palette.colors) {
            if (typeof palette.colors === 'object') {
                // Handle different color structure formats
                normalized.colors = this.normalizeColorStructure(palette.colors);
            } else if (Array.isArray(palette.colors)) {
                // Convert array to object
                normalized.colors = this.convertArrayToColorObject(palette.colors);
            }
        }

        // Generate missing colors if needed
        if (Object.keys(normalized.colors).length < 3) {
            normalized.colors = this.generateMissingColors(normalized.colors);
        }

        return normalized;
    }

    /**
     * Normalize color structure to consistent format
     */
    normalizeColorStructure(colors) {
        const normalized = {};

        // Common color mappings
        const colorMappings = {
            primary: ['primary', 'main', 'brand', 'accent1'],
            secondary: ['secondary', 'accent', 'accent2', 'highlight'],
            accent: ['accent', 'accent3', 'tertiary', 'complement'],
            surface: ['surface', 'card', 'panel', 'background2'],
            background: ['background', 'bg', 'base', 'neutral']
        };

        // Map colors to standard names
        for (const [standardName, aliases] of Object.entries(colorMappings)) {
            for (const alias of aliases) {
                if (colors[alias]) {
                    normalized[standardName] = this.extractColorValue(colors[alias]);
                    break;
                }
            }
        }

        // If we still don't have enough colors, add direct mappings
        for (const [key, value] of Object.entries(colors)) {
            if (!normalized[key] && this.isValidColor(value)) {
                normalized[key] = this.extractColorValue(value);
            }
        }

        return normalized;
    }

    /**
     * Extract color value from different formats
     */
    extractColorValue(colorData) {
        if (typeof colorData === 'string') {
            return colorData;
        } else if (typeof colorData === 'object') {
            return colorData.hex || colorData.value || colorData.color || null;
        }
        return null;
    }

    /**
     * Check if a value is a valid color
     */
    isValidColor(value) {
        if (typeof value === 'string') {
            return /^#[0-9A-Fa-f]{6}$/.test(value) ||
                   /^#[0-9A-Fa-f]{3}$/.test(value) ||
                   /^rgb/.test(value) ||
                   /^hsl/.test(value);
        }
        return false;
    }

    /**
     * Convert array of colors to object
     */
    convertArrayToColorObject(colorArray) {
        const colorNames = ['primary', 'secondary', 'accent', 'surface', 'background'];
        const colors = {};

        colorArray.forEach((color, index) => {
            if (index < colorNames.length) {
                colors[colorNames[index]] = this.extractColorValue(color);
            }
        });

        return colors;
    }

    /**
     * Generate missing colors based on existing colors
     */
    generateMissingColors(existingColors) {
        const colors = { ...existingColors };

        // If we have a primary color, generate others
        if (colors.primary) {
            if (!colors.secondary) {
                colors.secondary = this.darkenColor(colors.primary, 20);
            }
            if (!colors.accent) {
                colors.accent = this.lightenColor(colors.primary, 40);
            }
            if (!colors.surface) {
                colors.surface = '#FFFFFF';
            }
            if (!colors.background) {
                colors.background = this.lightenColor(colors.primary, 50);
            }
        } else {
            // Default color scheme
            colors.primary = '#87A96B';
            colors.secondary = '#1B365D';
            colors.accent = '#F8F5F2';
            colors.surface = '#FFFFFF';
            colors.background = '#F9FAFE';
        }

        return colors;
    }

    /**
     * Darken a hex color by percentage
     */
    darkenColor(hex, percent) {
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
     * Lighten a hex color by percentage
     */
    lightenColor(hex, percent) {
        const num = parseInt(hex.replace('#', ''), 16);
        const amt = Math.round(2.55 * percent);
        const R = (num >> 16) + amt;
        const G = (num >> 8 & 0x00FF) + amt;
        const B = (num & 0x0000FF) + amt;
        return '#' + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
            (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
            (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
    }

    /**
     * Remove duplicate palettes
     */
    deduplicatePalettes() {
        const seen = new Set();
        return this.palettes.filter(palette => {
            const key = palette.id;
            if (seen.has(key)) {
                return false;
            }
            seen.add(key);
            return true;
        });
    }

    /**
     * Render the visual palette interface
     */
    render() {
        if (this.isLoading) {
            return this.renderLoadingState();
        }

        if (this.palettes.length === 0) {
            return this.renderEmptyState();
        }

        return `
            <div class="sidebar-palette-grid">
                ${this.renderCategoryFilters()}
                ${this.renderSearchInput()}
                ${this.renderPaletteCards()}
            </div>
        `;
    }

    /**
     * Render loading state
     */
    renderLoadingState() {
        return `
            <div class="sidebar-palette-loading">
                <div class="loading-spinner"></div>
                <p>Loading color palettes...</p>
            </div>
        `;
    }

    /**
     * Render empty state
     */
    renderEmptyState() {
        return `
            <div class="sidebar-palette-empty">
                <div class="empty-icon">🎨</div>
                <p>No color palettes available</p>
                <button class="reload-palettes-btn" onclick="window.sidebarTokenBridge.colorPaletteInterface.loadPalettes()">
                    Reload Palettes
                </button>
            </div>
        `;
    }

    /**
     * Render category filters
     */
    renderCategoryFilters() {
        const categories = [...new Set(this.palettes.map(p => p.category))];

        return `
            <div class="category-filters">
                <button class="category-filter-btn active" data-category="all">All</button>
                ${categories.map(category => `
                    <button class="category-filter-btn" data-category="${category}">
                        ${this.capitalizeFirst(category)}
                    </button>
                `).join('')}
            </div>
        `;
    }

    /**
     * Render search input
     */
    renderSearchInput() {
        return `
            <div class="search-container">
                <input type="text" class="palette-search" placeholder="Search palettes..." />
            </div>
        `;
    }

    /**
     * Render palette cards
     */
    renderPaletteCards() {
        return this.palettes.map(palette => this.renderPaletteCard(palette)).join('');
    }

    /**
     * Render individual palette card
     */
    renderPaletteCard(palette) {
        const colorSwatches = Object.values(palette.colors)
            .filter(color => color && this.isValidColor(color))
            .slice(0, 6) // Show max 6 colors
            .map(color => `<div class="color-swatch" style="background-color: ${color};" title="${color}"></div>`)
            .join('');

        const isSelected = this.currentPalette?.id === palette.id;

        return `
            <div class="palette-card ${isSelected ? 'selected' : ''}"
                 data-palette="${palette.id}"
                 data-palette-data='${JSON.stringify(palette)}'
                 data-category="${palette.category}">
                <div class="palette-header">
                    <h5 class="palette-name">${palette.name}</h5>
                    <div class="palette-source">${palette.source}</div>
                </div>
                <div class="palette-swatches">
                    ${colorSwatches}
                </div>
                <div class="palette-description">${palette.description}</div>
                <div class="palette-metadata">
                    <span class="palette-category badge">${palette.category}</span>
                    <span class="color-count">${Object.keys(palette.colors).length} colors</span>
                </div>
            </div>
        `;
    }

    /**
     * Refresh the interface
     */
    async refresh() {
        this.log('🔄 Refreshing color palette interface');
        await this.loadPalettes();

        // Re-render if container exists
        const container = document.querySelector('#simple-color-palette-container');
        if (container) {
            container.innerHTML = this.render();
            this.setupInteractions(container);
        }
    }

    /**
     * Setup interactions after rendering
     */
    setupInteractions(container) {
        // Category filter interactions
        $(container).on('click', '.category-filter-btn', (e) => {
            const btn = $(e.currentTarget);
            const category = btn.data('category');

            // Update active state
            container.querySelectorAll('.category-filter-btn').forEach(b => b.classList.remove('active'));
            btn.addClass('active');

            // Filter palettes
            this.filterPalettesByCategory(category);
        });

        // Search interactions
        $(container).on('input', '.palette-search', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            this.filterPalettesBySearch(searchTerm);
        });

        this.log('✅ Color palette interactions setup');
    }

    /**
     * Filter palettes by category
     */
    filterPalettesByCategory(category) {
        const cards = document.querySelectorAll('.palette-card');

        cards.forEach(card => {
            const cardCategory = card.dataset.category;
            const shouldShow = category === 'all' || cardCategory === category;

            card.style.display = shouldShow ? 'block' : 'none';
        });
    }

    /**
     * Filter palettes by search term
     */
    filterPalettesBySearch(searchTerm) {
        const cards = document.querySelectorAll('.palette-card');

        cards.forEach(card => {
            const paletteName = card.querySelector('.palette-name').textContent.toLowerCase();
            const paletteDescription = card.querySelector('.palette-description').textContent.toLowerCase();
            const shouldShow = !searchTerm ||
                             paletteName.includes(searchTerm) ||
                             paletteDescription.includes(searchTerm);

            card.style.display = shouldShow ? 'block' : 'none';
        });
    }

    /**
     * Set current palette
     */
    setCurrentPalette(paletteId) {
        this.currentPalette = this.palettes.find(p => p.id === paletteId);

        // Update visual selection
        document.querySelectorAll('.palette-card').forEach(card => {
            card.classList.toggle('selected', card.dataset.palette === paletteId);
        });

        this.log(`✅ Current palette set to: ${paletteId}`);
    }

    /**
     * Get current palette
     */
    getCurrentPalette() {
        return this.currentPalette;
    }

    /**
     * Get all palettes
     */
    getAllPalettes() {
        return this.palettes;
    }

    /**
     * Utility methods
     */
    capitalizeFirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    log(message, ...args) {
        if (this.bridge?.debug) {
            console.log(`[SidebarColorPaletteInterface] ${message}`, ...args);
        }
    }
}

// Make available globally
window.SidebarColorPaletteInterface = SidebarColorPaletteInterface;
