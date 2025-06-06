/**
 * Professional Visual Customizer - Typography System
 * Version: 2.0.0
 * Visual font selection with accessibility scoring and live preview
 */

/**
 * Typography System Data Structure
 */
const TYPOGRAPHY_SYSTEM = {
    headingFonts: [
        {
            id: 'playfair-display',
            name: 'Playfair Display',
            category: 'serif',
            description: 'Elegant serif for luxury spa appeal',
            weights: ['300', '400', '700'],
            fallback: 'serif',
            googleFontsUrl: 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@300;400;700&display=swap',
            cssFamily: '"Playfair Display", serif',
            preview: {
                specimen: 'AaBbCc 123 ?!',
                context: 'Medical Spa Treatments',
                accessibility: 'excellent',
                readabilityScore: 95
            },
            characteristics: {
                elegance: 'high',
                readability: 'excellent',
                professionalism: 'luxury',
                medicalSuitability: 'spa-focused'
            }
        },
        {
            id: 'crimson-text',
            name: 'Crimson Text',
            category: 'serif',
            description: 'Professional serif for medical trust',
            weights: ['400', '600', '700'],
            fallback: 'serif',
            googleFontsUrl: 'https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600;700&display=swap',
            cssFamily: '"Crimson Text", serif',
            preview: {
                specimen: 'AaBbCc 123 ?!',
                context: 'Medical Spa Treatments',
                accessibility: 'excellent',
                readabilityScore: 98
            },
            characteristics: {
                elegance: 'medium',
                readability: 'excellent',
                professionalism: 'clinical',
                medicalSuitability: 'medical-focused'
            }
        },
        {
            id: 'libre-baskerville',
            name: 'Libre Baskerville',
            category: 'serif',
            description: 'Classic serif for timeless authority',
            weights: ['400', '700'],
            fallback: 'serif',
            googleFontsUrl: 'https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap',
            cssFamily: '"Libre Baskerville", serif',
            preview: {
                specimen: 'AaBbCc 123 ?!',
                context: 'Medical Spa Treatments',
                accessibility: 'excellent',
                readabilityScore: 92
            },
            characteristics: {
                elegance: 'high',
                readability: 'excellent',
                professionalism: 'authoritative',
                medicalSuitability: 'general'
            }
        },
        {
            id: 'cormorant-garamond',
            name: 'Cormorant Garamond',
            category: 'serif',
            description: 'Refined serif for premium spa elegance',
            weights: ['300', '400', '700'],
            fallback: 'serif',
            googleFontsUrl: 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;700&display=swap',
            cssFamily: '"Cormorant Garamond", serif',
            preview: {
                specimen: 'AaBbCc 123 ?!',
                context: 'Medical Spa Treatments',
                accessibility: 'good',
                readabilityScore: 88
            },
            characteristics: {
                elegance: 'very-high',
                readability: 'good',
                professionalism: 'luxury',
                medicalSuitability: 'luxury-spa'
            }
        }
    ],

    bodyFonts: [
        {
            id: 'inter',
            name: 'Inter',
            category: 'sans-serif',
            description: 'Modern sans-serif, highly readable and accessible',
            weights: ['300', '400', '500', '600', '700'],
            fallback: 'sans-serif',
            googleFontsUrl: 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap',
            cssFamily: '"Inter", sans-serif',
            preview: {
                specimen: 'AaBbCc 123 ?!',
                context: 'Professional medical services for your health and wellness needs.',
                accessibility: 'excellent',
                readabilityScore: 98
            },
            characteristics: {
                clarity: 'excellent',
                readability: 'excellent',
                professionalism: 'modern',
                medicalSuitability: 'universal'
            }
        },
        {
            id: 'source-sans-pro',
            name: 'Source Sans Pro',
            category: 'sans-serif',
            description: 'Professional sans-serif, clean and medical-appropriate',
            weights: ['300', '400', '600', '700'],
            fallback: 'sans-serif',
            googleFontsUrl: 'https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap',
            cssFamily: '"Source Sans Pro", sans-serif',
            preview: {
                specimen: 'AaBbCc 123 ?!',
                context: 'Professional medical services for your health and wellness needs.',
                accessibility: 'excellent',
                readabilityScore: 96
            },
            characteristics: {
                clarity: 'excellent',
                readability: 'excellent',
                professionalism: 'clinical',
                medicalSuitability: 'medical-focused'
            }
        },
        {
            id: 'nunito-sans',
            name: 'Nunito Sans',
            category: 'sans-serif',
            description: 'Friendly sans-serif, approachable and warm',
            weights: ['300', '400', '600', '700'],
            fallback: 'sans-serif',
            googleFontsUrl: 'https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700&display=swap',
            cssFamily: '"Nunito Sans", sans-serif',
            preview: {
                specimen: 'AaBbCc 123 ?!',
                context: 'Professional medical services for your health and wellness needs.',
                accessibility: 'excellent',
                readabilityScore: 94
            },
            characteristics: {
                clarity: 'excellent',
                readability: 'excellent',
                professionalism: 'approachable',
                medicalSuitability: 'wellness-focused'
            }
        },
        {
            id: 'open-sans',
            name: 'Open Sans',
            category: 'sans-serif',
            description: 'Universal sans-serif, reliable and versatile',
            weights: ['300', '400', '600', '700'],
            fallback: 'sans-serif',
            googleFontsUrl: 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap',
            cssFamily: '"Open Sans", sans-serif',
            preview: {
                specimen: 'AaBbCc 123 ?!',
                context: 'Professional medical services for your health and wellness needs.',
                accessibility: 'excellent',
                readabilityScore: 95
            },
            characteristics: {
                clarity: 'excellent',
                readability: 'excellent',
                professionalism: 'neutral',
                medicalSuitability: 'universal'
            }
        }
    ]
};

/**
 * Font Loading Manager
 */
class FontLoader {
    constructor() {
        this.loadedFonts = new Set();
        this.loadingPromises = new Map();
    }

    /**
     * Load a Google Font with promise-based tracking
     */
    async loadGoogleFont(fontData) {
        if (this.loadedFonts.has(fontData.id)) {
            return Promise.resolve();
        }

        if (this.loadingPromises.has(fontData.id)) {
            return this.loadingPromises.get(fontData.id);
        }

        const loadingPromise = new Promise((resolve, reject) => {
            // Create link element for Google Fonts
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = fontData.googleFontsUrl;

            link.onload = () => {
                this.loadedFonts.add(fontData.id);

                // Use Font Loading API if available for more reliable detection
                if ('fonts' in document) {
                    const fontPromises = fontData.weights.map(weight => {
                        return document.fonts.load(`${weight} 16px ${fontData.cssFamily}`);
                    });

                    Promise.all(fontPromises).then(() => {
                        resolve();
                    }).catch(() => {
                        // Fallback - assume loaded after delay
                        setTimeout(resolve, 1000);
                    });
                } else {
                    // Fallback for older browsers
                    setTimeout(resolve, 1000);
                }
            };

            link.onerror = () => {
                console.warn(`Failed to load font: ${fontData.name}`);
                reject(new Error(`Failed to load font: ${fontData.name}`));
            };

            document.head.appendChild(link);
        });

        this.loadingPromises.set(fontData.id, loadingPromise);
        return loadingPromise;
    }

    /**
     * Check if font is loaded
     */
    isFontLoaded(fontId) {
        return this.loadedFonts.has(fontId);
    }

    /**
     * Preload multiple fonts
     */
    async preloadFonts(fontList) {
        const loadPromises = fontList.map(font => this.loadGoogleFont(font));
        return Promise.allSettled(loadPromises);
    }
}

/**
 * Typography Preview Generator
 */
class TypographyPreview {
    constructor(fontLoader) {
        this.fontLoader = fontLoader;
    }

    /**
     * Create font preview card element
     */
    createFontCard(fontData, isSelected = false) {
        const card = document.createElement('div');
        card.className = `typography-font-card ${isSelected ? 'selected' : ''}`;
        card.dataset.fontId = fontData.id;

        card.innerHTML = `
            <div class="font-card-header">
                <h3 class="font-name">${fontData.name}</h3>
                <div class="font-category">${fontData.category}</div>
            </div>

            <div class="font-specimen" style="font-family: ${fontData.cssFamily};">
                <div class="specimen-display">${fontData.preview.specimen}</div>
                <div class="specimen-context">${fontData.preview.context}</div>
            </div>

            <div class="font-weights">
                ${fontData.weights.map(weight => `
                    <span class="weight-sample" style="font-family: ${fontData.cssFamily}; font-weight: ${weight};">
                        ${this.getWeightName(weight)}
                    </span>
                `).join('')}
            </div>

            <div class="font-info">
                <div class="font-description">${fontData.description}</div>
                <div class="accessibility-score">
                    <span class="score-label">Readability:</span>
                    <span class="score-value">${fontData.preview.readabilityScore}%</span>
                    <div class="score-bar">
                        <div class="score-fill" style="width: ${fontData.preview.readabilityScore}%"></div>
                    </div>
                </div>
            </div>

            <div class="font-selection">
                <input type="radio" name="font-selection-${fontData.category}" value="${fontData.id}"
                       id="font-${fontData.id}" ${isSelected ? 'checked' : ''}>
                <label for="font-${fontData.id}" class="selection-label">
                    ${isSelected ? '✓ Selected' : 'Select Font'}
                </label>
            </div>
        `;

        // Load font when card is created
        this.fontLoader.loadGoogleFont(fontData).then(() => {
            card.classList.add('font-loaded');
        }).catch(() => {
            card.classList.add('font-error');
        });

        return card;
    }

    /**
     * Get readable weight name
     */
    getWeightName(weight) {
        const weightNames = {
            '300': 'Light',
            '400': 'Regular',
            '500': 'Medium',
            '600': 'SemiBold',
            '700': 'Bold'
        };
        return weightNames[weight] || weight;
    }

    /**
     * Create typography section with all font cards
     */
    createTypographySection(selectedHeading = null, selectedBody = null) {
        const section = document.createElement('div');
        section.className = 'typography-selection-section';

        section.innerHTML = `
            <div class="typography-section-header">
                <h2>Professional Typography Selection</h2>
                <p>Choose fonts that reflect your brand and ensure excellent readability</p>
            </div>

            <div class="font-category-section">
                <h3 class="category-title">
                    <span class="category-icon">📝</span>
                    Heading Fonts
                </h3>
                <p class="category-description">For titles, headings, and brand elements</p>
                <div class="font-cards-grid heading-fonts">
                    ${TYPOGRAPHY_SYSTEM.headingFonts.map(font =>
                        this.createFontCard(font, font.id === selectedHeading).outerHTML
                    ).join('')}
                </div>
            </div>

            <div class="font-category-section">
                <h3 class="category-title">
                    <span class="category-icon">📄</span>
                    Body Fonts
                </h3>
                <p class="category-description">For paragraphs, descriptions, and general content</p>
                <div class="font-cards-grid body-fonts">
                    ${TYPOGRAPHY_SYSTEM.bodyFonts.map(font =>
                        this.createFontCard(font, font.id === selectedBody).outerHTML
                    ).join('')}
                </div>
            </div>

            <div class="typography-preview-panel">
                <h3>Live Preview</h3>
                <div class="preview-content">
                    <h1 class="preview-heading" id="preview-heading">Medical Spa Excellence</h1>
                    <h2 class="preview-subheading" id="preview-subheading">Rejuvenating Treatments</h2>
                    <p class="preview-body" id="preview-body">
                        Experience professional medical spa treatments designed to enhance your
                        natural beauty and promote wellness. Our expert team provides personalized
                        care in a luxurious, relaxing environment.
                    </p>
                    <p class="preview-muted" id="preview-muted">
                        Book your consultation today and discover the difference.
                    </p>
                </div>
            </div>
        `;

        // Add event listeners for font selection
        this.addFontSelectionListeners(section);

        // Update preview with initial selections
        this.updatePreview(selectedHeading, selectedBody);

        return section;
    }

    /**
     * Add event listeners for font selection
     */
    addFontSelectionListeners(section) {
        const headingInputs = section.querySelectorAll('input[name="font-selection-serif"]');
        const bodyInputs = section.querySelectorAll('input[name="font-selection-sans-serif"]');

        headingInputs.forEach(input => {
            input.addEventListener('change', (e) => {
                if (e.target.checked) {
                    this.updatePreview(e.target.value, null);
                    this.updateCardSelection('serif', e.target.value);
                }
            });
        });

        bodyInputs.forEach(input => {
            input.addEventListener('change', (e) => {
                if (e.target.checked) {
                    this.updatePreview(null, e.target.value);
                    this.updateCardSelection('sans-serif', e.target.value);
                }
            });
        });
    }

    /**
     * Update visual card selection states
     */
    updateCardSelection(category, selectedId) {
        const cards = document.querySelectorAll('.typography-font-card');

        cards.forEach(card => {
            const fontId = card.dataset.fontId;
            const font = this.getFontById(fontId);

            if (font && font.category === category) {
                if (fontId === selectedId) {
                    card.classList.add('selected');
                    card.querySelector('.selection-label').textContent = '✓ Selected';
                } else {
                    card.classList.remove('selected');
                    card.querySelector('.selection-label').textContent = 'Select Font';
                }
            }
        });
    }

    /**
     * Update live preview with selected fonts
     */
    updatePreview(headingFontId, bodyFontId) {
        const previewHeading = document.getElementById('preview-heading');
        const previewSubheading = document.getElementById('preview-subheading');
        const previewBody = document.getElementById('preview-body');
        const previewMuted = document.getElementById('preview-muted');

        if (headingFontId && previewHeading && previewSubheading) {
            const headingFont = this.getFontById(headingFontId);
            if (headingFont) {
                previewHeading.style.fontFamily = headingFont.cssFamily;
                previewSubheading.style.fontFamily = headingFont.cssFamily;
            }
        }

        if (bodyFontId && previewBody && previewMuted) {
            const bodyFont = this.getFontById(bodyFontId);
            if (bodyFont) {
                previewBody.style.fontFamily = bodyFont.cssFamily;
                previewMuted.style.fontFamily = bodyFont.cssFamily;
            }
        }
    }

    /**
     * Get font data by ID
     */
    getFontById(fontId) {
        const allFonts = [...TYPOGRAPHY_SYSTEM.headingFonts, ...TYPOGRAPHY_SYSTEM.bodyFonts];
        return allFonts.find(font => font.id === fontId);
    }
}

/**
 * Typography System Manager
 */
class TypographySystem {
    constructor() {
        this.fontLoader = new FontLoader();
        this.preview = new TypographyPreview(this.fontLoader);
        this.currentSelection = {
            heading: null,
            body: null
        };
    }

    /**
     * Initialize typography system
     */
    async initialize() {
        // Preload all fonts for better performance
        const allFonts = [...TYPOGRAPHY_SYSTEM.headingFonts, ...TYPOGRAPHY_SYSTEM.bodyFonts];
        await this.fontLoader.preloadFonts(allFonts);
    }

    /**
     * Get all available fonts
     */
    getAllFonts() {
        return {
            heading: TYPOGRAPHY_SYSTEM.headingFonts,
            body: TYPOGRAPHY_SYSTEM.bodyFonts
        };
    }

    /**
     * Get font by ID
     */
    getFont(fontId) {
        const allFonts = [...TYPOGRAPHY_SYSTEM.headingFonts, ...TYPOGRAPHY_SYSTEM.bodyFonts];
        return allFonts.find(font => font.id === fontId);
    }

    /**
     * Set current font selection
     */
    setFontSelection(headingFontId, bodyFontId) {
        this.currentSelection.heading = headingFontId;
        this.currentSelection.body = bodyFontId;

        // Dispatch font selection change event
        document.dispatchEvent(new CustomEvent('typographyChanged', {
            detail: {
                heading: this.getFont(headingFontId),
                body: this.getFont(bodyFontId),
                selection: this.currentSelection
            }
        }));
    }

    /**
     * Generate CSS for current font selection
     */
    generateFontCSS() {
        const headingFont = this.getFont(this.currentSelection.heading);
        const bodyFont = this.getFont(this.currentSelection.body);

        let css = ':root {\n';

        if (headingFont) {
            css += `  --font-heading: ${headingFont.cssFamily};\n`;
        }

        if (bodyFont) {
            css += `  --font-body: ${bodyFont.cssFamily};\n`;
        }

        css += '}\n\n';

        // Add font-face declarations if needed
        if (headingFont) {
            css += `/* Heading Font: ${headingFont.name} */\n`;
        }

        if (bodyFont) {
            css += `/* Body Font: ${bodyFont.name} */\n`;
        }

        return css;
    }

    /**
     * Create typography selection interface
     */
    createSelectionInterface(selectedHeading = null, selectedBody = null) {
        this.currentSelection.heading = selectedHeading;
        this.currentSelection.body = selectedBody;

        return this.preview.createTypographySection(selectedHeading, selectedBody);
    }

    /**
     * Get current selection
     */
    getCurrentSelection() {
        return {
            heading: this.currentSelection.heading,
            body: this.currentSelection.body,
            headingFont: this.getFont(this.currentSelection.heading),
            bodyFont: this.getFont(this.currentSelection.body)
        };
    }
}

// Initialize and export the typography system
window.TypographySystem = TypographySystem;
window.FontLoader = FontLoader;
window.TypographyPreview = TypographyPreview;

// Create global instance
if (typeof window !== 'undefined') {
    window.typographySystem = new TypographySystem();
}
