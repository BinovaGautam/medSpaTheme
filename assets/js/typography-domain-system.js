/**
 * Typography Domain System - Intelligent Typography Token Generation
 * Handles typography coordination with colors, modular scale generation, and readability optimization
 *
 * @since PVC-006-DT
 * @version 1.0.0
 * @performance Optimized typography generation with cross-domain coordination
 */

class TypographyDomainSystem {
    constructor() {
        this.typographyTokens = new Map();
        this.fontPairings = new Map();
        this.typeScales = new Map();
        this.readabilityCache = new Map();

        // Performance tracking
        this.performanceMetrics = {
            generationTime: 0,
            readabilityOptimizations: 0,
            fontPairingValidations: 0
        };

        // Initialize typography system
        this.initializeTypographyRules();
        this.initializeFontPairings();
        this.initializeTypeScales();

        // Bind methods for performance
        this.generateFromSelection = this.generateFromSelection.bind(this);
        this.optimizeReadability = this.optimizeReadability.bind(this);
        this.coordinateWithColors = this.coordinateWithColors.bind(this);
    }

    /**
     * Initialize typography rules and semantic structure
     */
    initializeTypographyRules() {
        // Semantic typography roles
        this.typographyRoles = {
            'heading-1': {
                description: 'Primary heading (H1)',
                cssVariable: '--font-heading-1',
                scaleMultiplier: 2.5,
                lineHeightRatio: 1.1,
                letterSpacing: '-0.02em',
                fontWeight: 700,
                semanticRole: 'title'
            },
            'heading-2': {
                description: 'Secondary heading (H2)',
                cssVariable: '--font-heading-2',
                scaleMultiplier: 2.0,
                lineHeightRatio: 1.2,
                letterSpacing: '-0.01em',
                fontWeight: 600,
                semanticRole: 'heading'
            },
            'heading-3': {
                description: 'Tertiary heading (H3)',
                cssVariable: '--font-heading-3',
                scaleMultiplier: 1.5,
                lineHeightRatio: 1.3,
                letterSpacing: '0em',
                fontWeight: 600,
                semanticRole: 'heading'
            },
            'heading-4': {
                description: 'Quaternary heading (H4)',
                cssVariable: '--font-heading-4',
                scaleMultiplier: 1.25,
                lineHeightRatio: 1.4,
                letterSpacing: '0em',
                fontWeight: 500,
                semanticRole: 'heading'
            },
            'body': {
                description: 'Body text',
                cssVariable: '--font-body',
                scaleMultiplier: 1.0,
                lineHeightRatio: 1.6,
                letterSpacing: '0em',
                fontWeight: 400,
                semanticRole: 'content'
            },
            'body-large': {
                description: 'Large body text',
                cssVariable: '--font-body-large',
                scaleMultiplier: 1.125,
                lineHeightRatio: 1.5,
                letterSpacing: '0em',
                fontWeight: 400,
                semanticRole: 'content'
            },
            'body-small': {
                description: 'Small body text',
                cssVariable: '--font-body-small',
                scaleMultiplier: 0.875,
                lineHeightRatio: 1.6,
                letterSpacing: '0.01em',
                fontWeight: 400,
                semanticRole: 'content'
            },
            'caption': {
                description: 'Caption text',
                cssVariable: '--font-caption',
                scaleMultiplier: 0.75,
                lineHeightRatio: 1.5,
                letterSpacing: '0.02em',
                fontWeight: 400,
                semanticRole: 'metadata'
            },
            'button': {
                description: 'Button text',
                cssVariable: '--font-button',
                scaleMultiplier: 0.875,
                lineHeightRatio: 1.0,
                letterSpacing: '0.01em',
                fontWeight: 500,
                semanticRole: 'interactive'
            },
            'label': {
                description: 'Form label text',
                cssVariable: '--font-label',
                scaleMultiplier: 0.875,
                lineHeightRatio: 1.4,
                letterSpacing: '0em',
                fontWeight: 500,
                semanticRole: 'metadata'
            }
        };

        console.log('[TypographyDomainSystem] Typography roles initialized');
    }

    /**
     * Initialize font pairing recommendations
     */
    initializeFontPairings() {
        // Curated font pairings for different brand personalities
        this.fontPairings = new Map([
            ['modern-elegant', {
                heading: 'Playfair Display',
                body: 'Inter',
                fallback: {
                    heading: 'Georgia, serif',
                    body: 'system-ui, -apple-system, sans-serif'
                },
                personality: 'sophisticated, luxury',
                webfonts: {
                    heading: 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap',
                    body: 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap'
                }
            }],
            ['clean-minimal', {
                heading: 'Inter',
                body: 'Inter',
                fallback: {
                    heading: 'system-ui, -apple-system, sans-serif',
                    body: 'system-ui, -apple-system, sans-serif'
                },
                personality: 'clean, modern, professional',
                webfonts: {
                    heading: 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap',
                    body: 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap'
                }
            }],
            ['warm-friendly', {
                heading: 'Nunito Sans',
                body: 'Open Sans',
                fallback: {
                    heading: 'system-ui, -apple-system, sans-serif',
                    body: 'system-ui, -apple-system, sans-serif'
                },
                personality: 'approachable, friendly, medical',
                webfonts: {
                    heading: 'https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700&display=swap',
                    body: 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap'
                }
            }],
            ['professional-medical', {
                heading: 'Source Sans Pro',
                body: 'Source Sans Pro',
                fallback: {
                    heading: 'system-ui, -apple-system, sans-serif',
                    body: 'system-ui, -apple-system, sans-serif'
                },
                personality: 'professional, trustworthy, medical',
                webfonts: {
                    heading: 'https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap',
                    body: 'https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;500;600&display=swap'
                }
            }],
            ['luxury-spa', {
                heading: 'Crimson Text',
                body: 'Lato',
                fallback: {
                    heading: 'Georgia, serif',
                    body: 'system-ui, -apple-system, sans-serif'
                },
                personality: 'luxurious, relaxing, spa-like',
                webfonts: {
                    heading: 'https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600;700&display=swap',
                    body: 'https://fonts.googleapis.com/css2?family=Lato:wght@300;400;500;700&display=swap'
                }
            }]
        ]);

        console.log('[TypographyDomainSystem] Font pairings initialized');
    }

    /**
     * Initialize modular type scales
     */
    initializeTypeScales() {
        // Different type scale systems
        this.typeScales = new Map([
            ['minor-second', { ratio: 1.067, name: 'Minor Second' }],
            ['major-second', { ratio: 1.125, name: 'Major Second' }],
            ['minor-third', { ratio: 1.2, name: 'Minor Third' }],
            ['major-third', { ratio: 1.25, name: 'Major Third' }],
            ['perfect-fourth', { ratio: 1.333, name: 'Perfect Fourth' }],
            ['augmented-fourth', { ratio: 1.414, name: 'Augmented Fourth' }],
            ['perfect-fifth', { ratio: 1.5, name: 'Perfect Fifth' }],
            ['golden-ratio', { ratio: 1.618, name: 'Golden Ratio' }]
        ]);

        console.log('[TypographyDomainSystem] Type scales initialized');
    }

    /**
     * Generate typography tokens from font selection
     * @param {Object} fontSelection - Font selection configuration
     * @returns {Object} Generated typography tokens
     */
    generateFromSelection(fontSelection) {
        const startTime = performance.now();

        try {
            // Validate font selection
            if (!this.validateFontSelection(fontSelection)) {
                throw new Error('Invalid font selection configuration');
            }

            // Get font pairing configuration
            const fontPairing = this.getFontPairing(fontSelection.style || 'modern-elegant');

            // Generate base typography tokens
            const baseTokens = this.generateBaseTypographyTokens(fontSelection, fontPairing);

            // Apply modular scale
            const scaledTokens = this.applyModularScale(baseTokens, fontSelection.scale || 'minor-third');

            // Optimize for readability
            const optimizedTokens = this.optimizeReadability(scaledTokens, fontSelection.readabilityLevel || 'optimal');

            // Generate responsive tokens
            const responsiveTokens = this.generateResponsiveTokens(optimizedTokens);

            // Generate CSS font loading optimization
            const fontLoadingCSS = this.generateFontLoadingCSS(fontPairing);

            // Combine all tokens
            const allTokens = {
                base: baseTokens,
                scaled: scaledTokens,
                optimized: optimizedTokens,
                responsive: responsiveTokens,
                fontLoading: fontLoadingCSS,
                metadata: {
                    generatedAt: Date.now(),
                    fontPairing: fontSelection.style || 'modern-elegant',
                    scale: fontSelection.scale || 'minor-third',
                    baseSize: fontSelection.baseSize || '16px',
                    totalTokens: Object.keys(optimizedTokens).length
                }
            };

            // Update performance metrics
            this.performanceMetrics.generationTime = performance.now() - startTime;

            console.log(`[TypographyDomainSystem] Generated ${allTokens.metadata.totalTokens} tokens in ${this.performanceMetrics.generationTime.toFixed(2)}ms`);

            return allTokens;

        } catch (error) {
            console.error('[TypographyDomainSystem] Error generating typography:', error);
            return this.generateFallbackTypography();
        }
    }

    /**
     * Validate font selection configuration
     * @param {Object} fontSelection - Font selection to validate
     * @returns {boolean} Is valid
     */
    validateFontSelection(fontSelection) {
        return fontSelection &&
               typeof fontSelection === 'object' &&
               (fontSelection.style || fontSelection.headingFont || fontSelection.bodyFont);
    }

    /**
     * Get font pairing configuration
     * @param {string} style - Font pairing style
     * @returns {Object} Font pairing configuration
     */
    getFontPairing(style) {
        return this.fontPairings.get(style) || this.fontPairings.get('modern-elegant');
    }

    /**
     * Generate base typography tokens
     * @param {Object} fontSelection - Font selection
     * @param {Object} fontPairing - Font pairing configuration
     * @returns {Object} Base typography tokens
     */
    generateBaseTypographyTokens(fontSelection, fontPairing) {
        const tokens = {};
        const baseSize = this.parseSize(fontSelection.baseSize || '16px');

        Object.entries(this.typographyRoles).forEach(([roleKey, role]) => {
            const isHeading = role.semanticRole === 'title' || role.semanticRole === 'heading';
            const fontFamily = isHeading ?
                (fontSelection.headingFont || fontPairing.heading) :
                (fontSelection.bodyFont || fontPairing.body);

            const fallbackFamily = isHeading ?
                fontPairing.fallback.heading :
                fontPairing.fallback.body;

            tokens[roleKey] = {
                fontSize: `${(baseSize * role.scaleMultiplier).toFixed(2)}px`,
                fontFamily: `"${fontFamily}", ${fallbackFamily}`,
                fontWeight: role.fontWeight,
                lineHeight: role.lineHeightRatio,
                letterSpacing: role.letterSpacing,
                cssVariable: role.cssVariable,
                description: role.description,
                semanticRole: role.semanticRole
            };
        });

        return tokens;
    }

    /**
     * Apply modular scale to typography tokens
     * @param {Object} baseTokens - Base typography tokens
     * @param {string} scaleType - Type scale to apply
     * @returns {Object} Scaled typography tokens
     */
    applyModularScale(baseTokens, scaleType) {
        const scale = this.typeScales.get(scaleType) || this.typeScales.get('minor-third');
        const scaledTokens = {};

        Object.entries(baseTokens).forEach(([roleKey, token]) => {
            const role = this.typographyRoles[roleKey];
            if (!role) return;

            // Calculate scaled font size using modular scale
            const baseSize = this.parseSize(baseTokens.body.fontSize);
            let scaledSize;

            if (role.semanticRole === 'title') {
                scaledSize = baseSize * Math.pow(scale.ratio, 3);
            } else if (role.semanticRole === 'heading') {
                const headingLevel = parseInt(roleKey.replace('heading-', '')) || 2;
                scaledSize = baseSize * Math.pow(scale.ratio, 4 - headingLevel);
            } else if (roleKey === 'body-large') {
                scaledSize = baseSize * scale.ratio;
            } else if (roleKey === 'body-small') {
                scaledSize = baseSize / scale.ratio;
            } else if (roleKey === 'caption') {
                scaledSize = baseSize / Math.pow(scale.ratio, 2);
            } else {
                scaledSize = baseSize; // Body size
            }

            scaledTokens[roleKey] = {
                ...token,
                fontSize: `${scaledSize.toFixed(2)}px`,
                scaledBy: scale.name
            };
        });

        return scaledTokens;
    }

    /**
     * Optimize typography for readability
     * @param {Object} tokens - Typography tokens to optimize
     * @param {string} level - Readability optimization level
     * @returns {Object} Optimized typography tokens
     */
    optimizeReadability(tokens, level = 'optimal') {
        const optimizedTokens = {};

        Object.entries(tokens).forEach(([roleKey, token]) => {
            const fontSize = this.parseSize(token.fontSize);
            const role = this.typographyRoles[roleKey];

            // Apply readability optimizations
            let optimizedLineHeight = token.lineHeight;
            let optimizedLetterSpacing = token.letterSpacing;
            let optimizedFontSize = fontSize;

            // Font size optimizations
            if (level === 'optimal') {
                // Ensure minimum readable sizes
                if (role.semanticRole === 'content' && fontSize < 16) {
                    optimizedFontSize = 16;
                }
                if (role.semanticRole === 'metadata' && fontSize < 14) {
                    optimizedFontSize = 14;
                }
            }

            // Line height optimizations
            if (role.semanticRole === 'content') {
                // Optimal line height for body text: 1.4-1.6
                optimizedLineHeight = this.calculateOptimalLineHeight(optimizedFontSize, level);
            } else if (role.semanticRole === 'title' || role.semanticRole === 'heading') {
                // Tighter line height for headings
                optimizedLineHeight = this.calculateHeadingLineHeight(optimizedFontSize);
            }

            // Letter spacing optimizations
            if (optimizedFontSize < 16) {
                // Increase letter spacing for smaller text
                optimizedLetterSpacing = '0.01em';
            }

            optimizedTokens[roleKey] = {
                ...token,
                fontSize: `${optimizedFontSize.toFixed(2)}px`,
                lineHeight: optimizedLineHeight,
                letterSpacing: optimizedLetterSpacing,
                readabilityOptimized: true,
                optimizationLevel: level
            };
        });

        this.performanceMetrics.readabilityOptimizations++;
        return optimizedTokens;
    }

    /**
     * Generate responsive typography tokens
     * @param {Object} tokens - Base typography tokens
     * @returns {Object} Responsive typography tokens
     */
    generateResponsiveTokens(tokens) {
        const responsiveTokens = {};

        Object.entries(tokens).forEach(([roleKey, token]) => {
            const baseFontSize = this.parseSize(token.fontSize);
            const role = this.typographyRoles[roleKey];

            // Generate responsive scaling
            const mobileScale = 0.875; // 87.5% on mobile
            const tabletScale = 0.9375; // 93.75% on tablet

            responsiveTokens[roleKey] = {
                ...token,
                responsive: {
                    mobile: {
                        fontSize: `${(baseFontSize * mobileScale).toFixed(2)}px`,
                        lineHeight: this.adjustLineHeightForSize(token.lineHeight, mobileScale)
                    },
                    tablet: {
                        fontSize: `${(baseFontSize * tabletScale).toFixed(2)}px`,
                        lineHeight: this.adjustLineHeightForSize(token.lineHeight, tabletScale)
                    },
                    desktop: {
                        fontSize: token.fontSize,
                        lineHeight: token.lineHeight
                    }
                }
            };
        });

        return responsiveTokens;
    }

    /**
     * Generate font loading CSS for performance
     * @param {Object} fontPairing - Font pairing configuration
     * @returns {Object} Font loading CSS and preload links
     */
    generateFontLoadingCSS(fontPairing) {
        const preloadLinks = [];
        const fontFaceCSS = [];

        // Generate preload links for critical fonts
        if (fontPairing.webfonts) {
            Object.entries(fontPairing.webfonts).forEach(([type, url]) => {
                preloadLinks.push(`<link rel="preload" href="${url}" as="style" onload="this.onload=null;this.rel='stylesheet'">`);
            });
        }

        // Generate font-display: swap for better performance
        const fontDisplayCSS = `
            @font-face {
                font-family: "${fontPairing.heading}";
                font-display: swap;
            }
            @font-face {
                font-family: "${fontPairing.body}";
                font-display: swap;
            }
        `;

        return {
            preloadLinks,
            fontDisplayCSS,
            fallbackCSS: this.generateFallbackCSS(fontPairing)
        };
    }

    /**
     * Generate fallback CSS for font loading failures
     * @param {Object} fontPairing - Font pairing configuration
     * @returns {string} Fallback CSS
     */
    generateFallbackCSS(fontPairing) {
        return `
            .font-loading-failed {
                font-family: ${fontPairing.fallback.body};
            }
            .font-loading-failed h1,
            .font-loading-failed h2,
            .font-loading-failed h3,
            .font-loading-failed h4,
            .font-loading-failed h5,
            .font-loading-failed h6 {
                font-family: ${fontPairing.fallback.heading};
            }
        `;
    }

    /**
     * Coordinate typography with color system
     * @param {Object} typographyTokens - Typography tokens
     * @param {Object} colorTokens - Color tokens from color domain
     * @returns {Object} Coordinated tokens
     */
    coordinateWithColors(typographyTokens, colorTokens) {
        const coordinatedTokens = {};

        Object.entries(typographyTokens).forEach(([roleKey, token]) => {
            const role = this.typographyRoles[roleKey];

            // Determine appropriate text color based on role
            let textColor;
            switch (role.semanticRole) {
                case 'title':
                case 'heading':
                    textColor = colorTokens['text-primary']?.value || 'var(--color-text-primary)';
                    break;
                case 'content':
                    textColor = colorTokens['text-primary']?.value || 'var(--color-text-primary)';
                    break;
                case 'metadata':
                    textColor = colorTokens['text-secondary']?.value || 'var(--color-text-secondary)';
                    break;
                case 'interactive':
                    textColor = colorTokens['primary']?.value || 'var(--color-primary)';
                    break;
                default:
                    textColor = colorTokens['text-primary']?.value || 'var(--color-text-primary)';
            }

            coordinatedTokens[roleKey] = {
                ...token,
                color: textColor,
                coordinatedWithColors: true
            };
        });

        return coordinatedTokens;
    }

    /**
     * Generate fallback typography when configuration is invalid
     * @returns {Object} Fallback typography tokens
     */
    generateFallbackTypography() {
        const fallbackSelection = {
            style: 'clean-minimal',
            baseSize: '16px',
            scale: 'minor-third',
            readabilityLevel: 'optimal'
        };

        console.warn('[TypographyDomainSystem] Using fallback typography');
        return this.generateFromSelection(fallbackSelection);
    }

    // Utility functions

    /**
     * Parse size value to number
     * @param {string} size - Size with unit
     * @returns {number} Numeric size value
     */
    parseSize(size) {
        return parseFloat(size.replace(/[^\d.]/g, ''));
    }

    /**
     * Calculate optimal line height for content text
     * @param {number} fontSize - Font size in px
     * @param {string} level - Readability level
     * @returns {number} Optimal line height ratio
     */
    calculateOptimalLineHeight(fontSize, level) {
        if (level === 'optimal') {
            // Optimal line height formula based on font size
            if (fontSize <= 14) return 1.6;
            if (fontSize <= 18) return 1.5;
            if (fontSize <= 24) return 1.4;
            return 1.3;
        } else {
            // Standard line height
            return 1.5;
        }
    }

    /**
     * Calculate line height for headings
     * @param {number} fontSize - Font size in px
     * @returns {number} Heading line height ratio
     */
    calculateHeadingLineHeight(fontSize) {
        if (fontSize >= 32) return 1.1;
        if (fontSize >= 24) return 1.2;
        if (fontSize >= 20) return 1.3;
        return 1.4;
    }

    /**
     * Adjust line height for responsive scaling
     * @param {number} lineHeight - Base line height
     * @param {number} scale - Scaling factor
     * @returns {number} Adjusted line height
     */
    adjustLineHeightForSize(lineHeight, scale) {
        // Slightly increase line height for smaller text
        return lineHeight + (1 - scale) * 0.1;
    }

    /**
     * Get performance metrics
     * @returns {Object} Performance metrics
     */
    getPerformanceMetrics() {
        return {
            ...this.performanceMetrics,
            typographyRolesCount: Object.keys(this.typographyRoles).length,
            fontPairingsCount: this.fontPairings.size,
            typeScalesCount: this.typeScales.size,
            cacheSize: this.readabilityCache.size
        };
    }

    /**
     * Export typography tokens as CSS
     * @param {Object} tokens - Typography tokens
     * @returns {string} CSS custom properties
     */
    exportAsCSS(tokens) {
        let css = ':root {\n';

        // Export typography custom properties
        Object.entries(tokens.optimized || tokens.scaled || tokens.base || {}).forEach(([roleKey, token]) => {
            const role = this.typographyRoles[roleKey];
            if (!role) return;

            css += `  ${role.cssVariable}-size: ${token.fontSize};\n`;
            css += `  ${role.cssVariable}-family: ${token.fontFamily};\n`;
            css += `  ${role.cssVariable}-weight: ${token.fontWeight};\n`;
            css += `  ${role.cssVariable}-line-height: ${token.lineHeight};\n`;
            css += `  ${role.cssVariable}-letter-spacing: ${token.letterSpacing};\n`;

            if (token.color) {
                css += `  ${role.cssVariable}-color: ${token.color};\n`;
            }
        });

        css += '}\n';

        // Add responsive typography
        if (tokens.responsive) {
            css += this.generateResponsiveCSS(tokens.responsive);
        }

        return css;
    }

    /**
     * Generate responsive CSS media queries
     * @param {Object} responsiveTokens - Responsive typography tokens
     * @returns {string} Responsive CSS
     */
    generateResponsiveCSS(responsiveTokens) {
        let css = '';

        // Mobile styles
        css += '\n@media (max-width: 768px) {\n  :root {\n';
        Object.entries(responsiveTokens).forEach(([roleKey, token]) => {
            const role = this.typographyRoles[roleKey];
            if (role && token.responsive && token.responsive.mobile) {
                css += `    ${role.cssVariable}-size: ${token.responsive.mobile.fontSize};\n`;
                css += `    ${role.cssVariable}-line-height: ${token.responsive.mobile.lineHeight};\n`;
            }
        });
        css += '  }\n}\n';

        // Tablet styles
        css += '\n@media (min-width: 769px) and (max-width: 1024px) {\n  :root {\n';
        Object.entries(responsiveTokens).forEach(([roleKey, token]) => {
            const role = this.typographyRoles[roleKey];
            if (role && token.responsive && token.responsive.tablet) {
                css += `    ${role.cssVariable}-size: ${token.responsive.tablet.fontSize};\n`;
                css += `    ${role.cssVariable}-line-height: ${token.responsive.tablet.lineHeight};\n`;
            }
        });
        css += '  }\n}\n';

        return css;
    }
}

// Export for global use
window.TypographyDomainSystem = TypographyDomainSystem;

// Create global instance
window.typographyDomainSystem = new TypographyDomainSystem();

console.log('[TypographyDomainSystem] Initialized with intelligent typography coordination ✅');
