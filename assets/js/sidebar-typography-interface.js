/**
 * Sidebar Typography Interface - PVC-008-CRITICAL
 * Visual typography selector for sidebar integration
 *
 * Shows actual font rendering with A-Z samples, numbers, and weights
 * Replaces dropdown menus with visual font previews
 *
 * @since Sprint 2 Extension
 * @version 1.0.0
 */

class SidebarTypographyInterface {
    constructor(bridge) {
        this.bridge = bridge;
        this.fontPairings = [];
        this.currentPairing = null;
        this.isLoading = false;
        this.loadedFonts = new Set();

        this.log('📝 Initializing Sidebar Typography Interface...');
        this.loadFontPairings();
    }

    /**
     * Load available font pairings
     */
    async loadFontPairings() {
        this.isLoading = true;

        try {
            // Load from multiple sources
            await Promise.all([
                this.loadFromTypographyDomainSystem(),
                this.loadFromSemanticColorSystem(),
                this.loadSystemFonts(),
                this.loadFallbackPairings()
            ]);

            // Remove duplicates and sort
            this.fontPairings = this.deduplicatePairings();
            this.log(`✅ Loaded ${this.fontPairings.length} font pairings`);

            // Pre-load font files
            await this.preloadFonts();

        } catch (error) {
            console.error('❌ Error loading font pairings:', error);
            this.loadFallbackPairings();
        } finally {
            this.isLoading = false;
        }
    }

    /**
     * Load pairings from Typography Domain System
     */
    async loadFromTypographyDomainSystem() {
        if (window.typographyDomainGenerator) {
            try {
                const domainPairings = await window.typographyDomainGenerator.getAllPairings();
                if (domainPairings && domainPairings.length > 0) {
                    this.fontPairings.push(...domainPairings.map(p => this.normalizePairing(p, 'domain')));
                    this.log('✅ Loaded pairings from Typography Domain System');
                }
            } catch (error) {
                this.log('⚠️ Could not load from Typography Domain System:', error.message);
            }
        }
    }

    /**
     * Load pairings from Semantic Color System
     */
    async loadFromSemanticColorSystem() {
        if (window.semanticColorSystem?.getAvailableTypography) {
            try {
                const semanticPairings = await window.semanticColorSystem.getAvailableTypography();
                if (semanticPairings && semanticPairings.length > 0) {
                    this.fontPairings.push(...semanticPairings.map(p => this.normalizePairing(p, 'semantic')));
                    this.log('✅ Loaded pairings from Semantic Color System');
                }
            } catch (error) {
                this.log('⚠️ Could not load from Semantic Color System:', error.message);
            }
        }
    }

    /**
     * Load system fonts
     */
    async loadSystemFonts() {
        const systemFonts = this.getSystemFonts();
        const systemPairings = this.generateSystemFontPairings(systemFonts);

        this.fontPairings.push(...systemPairings);
        this.log('✅ Loaded system font pairings');
    }

    /**
     * Load fallback font pairings
     */
    loadFallbackPairings() {
        const fallbackPairings = [
            {
                id: 'modern-elegant',
                name: 'Modern Elegant',
                description: 'Perfect for luxury medical spas',
                category: 'luxury',
                heading: {
                    family: 'Playfair Display',
                    weights: [400, 600, 700],
                    fallback: 'serif',
                    googleFont: 'Playfair+Display:wght@400;600;700'
                },
                body: {
                    family: 'Inter',
                    weights: [300, 400, 500, 600],
                    fallback: 'sans-serif',
                    googleFont: 'Inter:wght@300;400;500;600'
                },
                source: 'fallback'
            },
            {
                id: 'clean-minimal',
                name: 'Clean & Minimal',
                description: 'Modern medical professional',
                category: 'medical',
                heading: {
                    family: 'Poppins',
                    weights: [400, 500, 600, 700],
                    fallback: 'sans-serif',
                    googleFont: 'Poppins:wght@400;500;600;700'
                },
                body: {
                    family: 'Source Sans Pro',
                    weights: [300, 400, 600],
                    fallback: 'sans-serif',
                    googleFont: 'Source+Sans+Pro:wght@300;400;600'
                },
                source: 'fallback'
            },
            {
                id: 'warm-friendly',
                name: 'Warm & Friendly',
                description: 'Approachable wellness center',
                category: 'wellness',
                heading: {
                    family: 'Montserrat',
                    weights: [400, 500, 600],
                    fallback: 'sans-serif',
                    googleFont: 'Montserrat:wght@400;500;600'
                },
                body: {
                    family: 'Open Sans',
                    weights: [300, 400, 600],
                    fallback: 'sans-serif',
                    googleFont: 'Open+Sans:wght@300;400;600'
                },
                source: 'fallback'
            },
            {
                id: 'professional-medical',
                name: 'Professional Medical',
                description: 'Clinical professionalism',
                category: 'medical',
                heading: {
                    family: 'Roboto',
                    weights: [400, 500, 700],
                    fallback: 'sans-serif',
                    googleFont: 'Roboto:wght@400;500;700'
                },
                body: {
                    family: 'Roboto',
                    weights: [300, 400, 500],
                    fallback: 'sans-serif',
                    googleFont: 'Roboto:wght@300;400;500'
                },
                source: 'fallback'
            },
            {
                id: 'luxury-spa',
                name: 'Luxury Spa',
                description: 'High-end spa experience',
                category: 'luxury',
                heading: {
                    family: 'Cormorant Garamond',
                    weights: [400, 500, 600],
                    fallback: 'serif',
                    googleFont: 'Cormorant+Garamond:wght@400;500;600'
                },
                body: {
                    family: 'Lato',
                    weights: [300, 400, 700],
                    fallback: 'sans-serif',
                    googleFont: 'Lato:wght@300;400;700'
                },
                source: 'fallback'
            }
        ];

        this.fontPairings.push(...fallbackPairings.map(p => this.normalizePairing(p, 'fallback')));
        this.log('✅ Loaded fallback font pairings');
    }

    /**
     * Get available system fonts
     */
    getSystemFonts() {
        return [
            'Arial', 'Helvetica', 'Times New Roman', 'Georgia',
            'Verdana', 'Trebuchet MS', 'Impact', 'Comic Sans MS',
            'Courier New', 'Lucida Console', 'system-ui'
        ];
    }

    /**
     * Generate system font pairings
     */
    generateSystemFontPairings(systemFonts) {
        const pairings = [];
        const sansSerif = ['Arial', 'Helvetica', 'Verdana', 'Trebuchet MS', 'system-ui'];
        const serif = ['Times New Roman', 'Georgia'];

        // Create safe system font pairings
        const safePairings = [
            {
                id: 'system-default',
                name: 'System Default',
                description: 'Safe system fonts',
                category: 'system',
                heading: { family: 'system-ui', fallback: 'sans-serif', weights: [400, 600, 700] },
                body: { family: 'system-ui', fallback: 'sans-serif', weights: [300, 400, 500] },
                source: 'system'
            },
            {
                id: 'arial-helvetica',
                name: 'Arial & Helvetica',
                description: 'Classic web-safe fonts',
                category: 'system',
                heading: { family: 'Arial', fallback: 'sans-serif', weights: [400, 600, 700] },
                body: { family: 'Helvetica', fallback: 'sans-serif', weights: [300, 400, 500] },
                source: 'system'
            },
            {
                id: 'georgia-verdana',
                name: 'Georgia & Verdana',
                description: 'Traditional serif and sans-serif',
                category: 'system',
                heading: { family: 'Georgia', fallback: 'serif', weights: [400, 600, 700] },
                body: { family: 'Verdana', fallback: 'sans-serif', weights: [300, 400, 500] },
                source: 'system'
            }
        ];

        return safePairings;
    }

    /**
     * Normalize pairing data structure
     */
    normalizePairing(pairing, source) {
        return {
            id: pairing.id || `pairing-${Date.now()}`,
            name: pairing.name || 'Unnamed Pairing',
            description: pairing.description || '',
            category: pairing.category || 'general',
            source: source,
            heading: this.normalizeFontDefinition(pairing.heading || pairing.headingFont),
            body: this.normalizeFontDefinition(pairing.body || pairing.bodyFont),
            metadata: pairing.metadata || {}
        };
    }

    /**
     * Normalize font definition
     */
    normalizeFontDefinition(font) {
        if (!font) {
            return {
                family: 'system-ui',
                weights: [400],
                fallback: 'sans-serif'
            };
        }

        return {
            family: font.family || font.name || 'system-ui',
            weights: font.weights || font.weight ? [font.weight] : [400],
            fallback: font.fallback || 'sans-serif',
            googleFont: font.googleFont || null,
            webFont: font.webFont || null
        };
    }

    /**
     * Pre-load Google Fonts
     */
    async preloadFonts() {
        const googleFonts = new Set();

        this.fontPairings.forEach(pairing => {
            if (pairing.heading.googleFont) {
                googleFonts.add(pairing.heading.googleFont);
            }
            if (pairing.body.googleFont) {
                googleFonts.add(pairing.body.googleFont);
            }
        });

        if (googleFonts.size > 0) {
            this.loadGoogleFonts([...googleFonts]);
        }
    }

    /**
     * Load Google Fonts
     */
    loadGoogleFonts(fonts) {
        const existingLink = document.querySelector('#sidebar-google-fonts');
        if (existingLink) {
            existingLink.remove();
        }

        const link = document.createElement('link');
        link.id = 'sidebar-google-fonts';
        link.rel = 'stylesheet';
        link.href = `https://fonts.googleapis.com/css2?${fonts.join('&')}&display=swap`;

        document.head.appendChild(link);
        this.log('✅ Google Fonts loaded');
    }

    /**
     * Remove duplicate pairings
     */
    deduplicatePairings() {
        const seen = new Set();
        return this.fontPairings.filter(pairing => {
            const key = pairing.id;
            if (seen.has(key)) {
                return false;
            }
            seen.add(key);
            return true;
        });
    }

    /**
     * Render the visual typography interface
     */
    render() {
        if (this.isLoading) {
            return this.renderLoadingState();
        }

        if (this.fontPairings.length === 0) {
            return this.renderEmptyState();
        }

        return `
            <div class="typography-options">
                ${this.renderCategoryFilters()}
                ${this.renderSearchInput()}
                ${this.renderTypographyPreviews()}
            </div>
        `;
    }

    /**
     * Render loading state
     */
    renderLoadingState() {
        return `
            <div class="typography-loading">
                <div class="loading-spinner"></div>
                <p>Loading typography options...</p>
            </div>
        `;
    }

    /**
     * Render empty state
     */
    renderEmptyState() {
        return `
            <div class="typography-empty">
                <div class="empty-icon">📝</div>
                <p>No typography pairings available</p>
                <button class="reload-typography-btn" onclick="window.sidebarTokenBridge.typographyInterface.loadFontPairings()">
                    Reload Typography
                </button>
            </div>
        `;
    }

    /**
     * Render category filters
     */
    renderCategoryFilters() {
        const categories = [...new Set(this.fontPairings.map(p => p.category))];

        return `
            <div class="typography-category-filters">
                <button class="typography-filter-btn active" data-category="all">All</button>
                ${categories.map(category => `
                    <button class="typography-filter-btn" data-category="${category}">
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
            <div class="typography-search-container">
                <input type="text" class="typography-search" placeholder="Search typography..." />
            </div>
        `;
    }

    /**
     * Render typography previews
     */
    renderTypographyPreviews() {
        return this.fontPairings.map(pairing => this.renderTypographyPreview(pairing)).join('');
    }

    /**
     * Render individual typography preview
     */
    renderTypographyPreview(pairing) {
        const isSelected = this.currentPairing?.id === pairing.id;

        return `
            <div class="typography-preview ${isSelected ? 'selected' : ''}"
                 data-pairing="${pairing.id}"
                 data-pairing-data='${JSON.stringify(pairing)}'
                 data-category="${pairing.category}">
                <div class="typography-header">
                    <h5 class="pairing-name">${pairing.name}</h5>
                    <div class="pairing-source">${pairing.source}</div>
                </div>
                <div class="font-samples">
                    ${this.renderHeadingSample(pairing.heading)}
                    ${this.renderBodySample(pairing.body)}
                </div>
                <div class="pairing-description">${pairing.description}</div>
                <div class="pairing-metadata">
                    <span class="pairing-category badge">${pairing.category}</span>
                    <span class="font-info">${pairing.heading.family} / ${pairing.body.family}</span>
                </div>
            </div>
        `;
    }

    /**
     * Render heading font sample
     */
    renderHeadingSample(headingFont) {
        const fontFamily = `"${headingFont.family}", ${headingFont.fallback}`;

        return `
            <div class="heading-sample" style="font-family: ${fontFamily};">
                <div class="sample-label">Heading Font</div>
                <div class="sample-large" style="font-weight: ${headingFont.weights[headingFont.weights.length - 1] || 600};">
                    Aa Bb Cc Dd Ee
                </div>
                <div class="sample-alphabet" style="font-weight: ${headingFont.weights[0] || 400};">
                    ABCDEFGHIJKLMNOPQRSTUVWXYZ
                </div>
                <div class="sample-numbers" style="font-weight: ${headingFont.weights[1] || 500};">
                    1234567890
                </div>
                <div class="sample-weights">
                    ${headingFont.weights.map(weight => `
                        <span class="weight-sample" style="font-weight: ${weight};">${this.getWeightName(weight)}</span>
                    `).join('')}
                </div>
            </div>
        `;
    }

    /**
     * Render body font sample
     */
    renderBodySample(bodyFont) {
        const fontFamily = `"${bodyFont.family}", ${bodyFont.fallback}`;

        return `
            <div class="body-sample" style="font-family: ${fontFamily};">
                <div class="sample-label">Body Font</div>
                <div class="sample-text" style="font-weight: ${bodyFont.weights[0] || 400};">
                    The quick brown fox jumps over the lazy dog
                </div>
                <div class="sample-alphabet" style="font-weight: ${bodyFont.weights[0] || 400};">
                    abcdefghijklmnopqrstuvwxyz ABCDEFGHIJKLMNOPQRSTUVWXYZ
                </div>
                <div class="sample-numbers" style="font-weight: ${bodyFont.weights[0] || 400};">
                    0123456789 !@#$%^&*()
                </div>
                <div class="sample-paragraph" style="font-weight: ${bodyFont.weights[0] || 400};">
                    Experience luxury medical spa treatments with professional care and exceptional results.
                </div>
            </div>
        `;
    }

    /**
     * Get weight name from number
     */
    getWeightName(weight) {
        const weightNames = {
            100: 'Thin',
            200: 'ExtraLight',
            300: 'Light',
            400: 'Regular',
            500: 'Medium',
            600: 'SemiBold',
            700: 'Bold',
            800: 'ExtraBold',
            900: 'Black'
        };

        return weightNames[weight] || weight.toString();
    }

    /**
     * Refresh the interface
     */
    async refresh() {
        this.log('🔄 Refreshing typography interface');
        await this.loadFontPairings();

        // Re-render if container exists
        const container = document.querySelector('.typography-container');
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
        $(container).on('click', '.typography-filter-btn', (e) => {
            const btn = $(e.currentTarget);
            const category = btn.data('category');

            // Update active state
            container.querySelectorAll('.typography-filter-btn').forEach(b => b.classList.remove('active'));
            btn.addClass('active');

            // Filter typography
            this.filterTypographyByCategory(category);
        });

        // Search interactions
        $(container).on('input', '.typography-search', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            this.filterTypographyBySearch(searchTerm);
        });

        this.log('✅ Typography interactions setup');
    }

    /**
     * Filter typography by category
     */
    filterTypographyByCategory(category) {
        const previews = document.querySelectorAll('.typography-preview');

        previews.forEach(preview => {
            const previewCategory = preview.dataset.category;
            const shouldShow = category === 'all' || previewCategory === category;

            preview.style.display = shouldShow ? 'block' : 'none';
        });
    }

    /**
     * Filter typography by search term
     */
    filterTypographyBySearch(searchTerm) {
        const previews = document.querySelectorAll('.typography-preview');

        previews.forEach(preview => {
            const pairingName = preview.querySelector('.pairing-name').textContent.toLowerCase();
            const pairingDescription = preview.querySelector('.pairing-description').textContent.toLowerCase();
            const fontInfo = preview.querySelector('.font-info').textContent.toLowerCase();

            const shouldShow = !searchTerm ||
                             pairingName.includes(searchTerm) ||
                             pairingDescription.includes(searchTerm) ||
                             fontInfo.includes(searchTerm);

            preview.style.display = shouldShow ? 'block' : 'none';
        });
    }

    /**
     * Set current pairing
     */
    setCurrentPairing(pairingId) {
        this.currentPairing = this.fontPairings.find(p => p.id === pairingId);

        // Update visual selection
        document.querySelectorAll('.typography-preview').forEach(preview => {
            preview.classList.toggle('selected', preview.dataset.pairing === pairingId);
        });

        this.log(`✅ Current typography pairing set to: ${pairingId}`);
    }

    /**
     * Get current pairing
     */
    getCurrentPairing() {
        return this.currentPairing;
    }

    /**
     * Get all pairings
     */
    getAllPairings() {
        return this.fontPairings;
    }

    /**
     * Utility methods
     */
    capitalizeFirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    log(message, ...args) {
        if (this.bridge?.debug) {
            console.log(`[SidebarTypographyInterface] ${message}`, ...args);
        }
    }
}

// Make available globally
window.SidebarTypographyInterface = SidebarTypographyInterface;
