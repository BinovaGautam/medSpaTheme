/**
 * Color Domain Generator - Semantic Color System
 * Generates semantic color tokens from PSASB palettes with accessibility compliance
 *
 * @since PVC-006-DT
 * @version 1.0.0
 * @performance Optimized color generation with automatic accessibility validation
 */

class ColorDomainGenerator {
    constructor() {
        this.generatedTokens = new Map();
        this.colorRelationships = new Map();
        this.accessibilityCache = new Map();

        // Performance tracking
        this.performanceMetrics = {
            generationTime: 0,
            accessibilityChecks: 0,
            cacheHits: 0
        };

        // Initialize color relationship mappings
        this.initializeColorRelationships();

        // Bind methods for performance
        this.generateFromPalette = this.generateFromPalette.bind(this);
        this.generateSemanticColors = this.generateSemanticColors.bind(this);
        this.validateAccessibility = this.validateAccessibility.bind(this);
    }

    /**
     * Initialize semantic color relationships and generation rules
     */
    initializeColorRelationships() {
        // Semantic color roles (not fixed navy/teal)
        this.colorRoles = {
            primary: {
                description: 'Primary brand color',
                variants: ['primary-light', 'primary-dark', 'primary-hover', 'primary-focus'],
                cssVariable: '--color-primary',
                wcagRole: 'action'
            },
            secondary: {
                description: 'Secondary brand color',
                variants: ['secondary-light', 'secondary-dark', 'secondary-hover'],
                cssVariable: '--color-secondary',
                wcagRole: 'support'
            },
            accent: {
                description: 'Accent and highlight color',
                variants: ['accent-light', 'accent-dark'],
                cssVariable: '--color-accent',
                wcagRole: 'emphasis'
            },
            surface: {
                description: 'Surface and card backgrounds',
                variants: ['surface-elevated', 'surface-overlay'],
                cssVariable: '--color-surface',
                wcagRole: 'background'
            },
            background: {
                description: 'Main background color',
                variants: ['background-alt', 'background-disabled'],
                cssVariable: '--color-background',
                wcagRole: 'background'
            },
            'text-primary': {
                description: 'Primary text color',
                variants: ['text-primary-hover'],
                cssVariable: '--color-text-primary',
                wcagRole: 'text'
            },
            'text-secondary': {
                description: 'Secondary text color',
                variants: ['text-secondary-hover'],
                cssVariable: '--color-text-secondary',
                wcagRole: 'text'
            },
            'text-muted': {
                description: 'Muted text color',
                variants: [],
                cssVariable: '--color-text-muted',
                wcagRole: 'text'
            },
            border: {
                description: 'Border color',
                variants: ['border-light', 'border-focus'],
                cssVariable: '--color-border',
                wcagRole: 'decoration'
            },
            success: {
                description: 'Success state color',
                variants: ['success-light', 'success-dark'],
                cssVariable: '--color-success',
                wcagRole: 'status'
            },
            warning: {
                description: 'Warning state color',
                variants: ['warning-light', 'warning-dark'],
                cssVariable: '--color-warning',
                wcagRole: 'status'
            },
            error: {
                description: 'Error state color',
                variants: ['error-light', 'error-dark'],
                cssVariable: '--color-error',
                wcagRole: 'status'
            }
        };

        // Generation algorithms for variants
        this.variantGenerators = {
            'light': (baseColor) => this.lightenColor(baseColor, 0.15),
            'dark': (baseColor) => this.darkenColor(baseColor, 0.15),
            'hover': (baseColor) => this.darkenColor(baseColor, 0.08),
            'focus': (baseColor) => this.lightenColor(baseColor, 0.05),
            'elevated': (baseColor) => this.lightenColor(baseColor, 0.03),
            'overlay': (baseColor) => this.addOpacity(baseColor, 0.95),
            'alt': (baseColor) => this.shiftHue(baseColor, 5),
            'disabled': (baseColor) => this.desaturateColor(baseColor, 0.5)
        };

        console.log('[ColorDomainGenerator] Color relationships initialized');
    }

    /**
     * Generate semantic colors from PSASB palette
     * @param {Object} psasbPalette - PSASB color palette data
     * @returns {Object} Generated semantic color tokens
     */
    generateFromPalette(psasbPalette) {
        const startTime = performance.now();

        try {
            // Validate PSASB palette format
            if (!this.validatePSASBPalette(psasbPalette)) {
                throw new Error('Invalid PSASB palette format');
            }

            // Extract base colors from PSASB palette
            const baseColors = this.extractBaseColors(psasbPalette);

            // Generate semantic color tokens
            const semanticTokens = this.generateSemanticColors(baseColors);

            // Generate component-specific colors
            const componentTokens = this.generateComponentColors(semanticTokens);

            // Validate all colors for accessibility
            const accessibilityValidation = this.validateAccessibility(semanticTokens);

            // Apply accessibility corrections if needed
            if (!accessibilityValidation.allValid) {
                this.applyAccessibilityCorrections(semanticTokens, accessibilityValidation.corrections);
            }

            // Combine all tokens
            const allTokens = {
                semantic: semanticTokens,
                component: componentTokens,
                metadata: {
                    generatedAt: Date.now(),
                    paletteSource: psasbPalette.name || 'Unknown',
                    accessibilityCompliant: accessibilityValidation.allValid,
                    totalTokens: Object.keys(semanticTokens).length + Object.keys(componentTokens).length
                }
            };

            // Update performance metrics
            this.performanceMetrics.generationTime = performance.now() - startTime;

            console.log(`[ColorDomainGenerator] Generated ${allTokens.metadata.totalTokens} tokens in ${this.performanceMetrics.generationTime.toFixed(2)}ms`);

            return allTokens;

        } catch (error) {
            console.error('[ColorDomainGenerator] Error generating from palette:', error);
            return this.generateFallbackColors();
        }
    }

    /**
     * Validate PSASB palette format
     * @param {Object} palette - PSASB palette
     * @returns {boolean} Is valid
     */
    validatePSASBPalette(palette) {
        return palette &&
               palette.colors &&
               typeof palette.colors === 'object' &&
               palette.colors.primary &&
               palette.colors.secondary;
    }

    /**
     * Extract base colors from PSASB palette for semantic mapping
     * @param {Object} psasbPalette - PSASB palette
     * @returns {Object} Base colors mapped to semantic roles
     */
    extractBaseColors(psasbPalette) {
        const colors = psasbPalette.colors;

        return {
            primary: colors.primary?.hex || colors.primary,
            secondary: colors.secondary?.hex || colors.secondary,
            accent: colors.accent?.hex || colors.accent || colors.primary?.hex,
            surface: colors.surface?.hex || colors.background?.hex || '#ffffff',
            background: colors.background?.hex || colors.surface?.hex || '#ffffff',
            'text-primary': colors.textPrimary?.hex || colors.text?.hex || '#000000',
            'text-secondary': colors.textSecondary?.hex || this.generateTextColor(colors.background?.hex || '#ffffff'),
            border: colors.border?.hex || this.generateBorderColor(colors.background?.hex || '#ffffff'),
            success: colors.success?.hex || '#22c55e',
            warning: colors.warning?.hex || '#f59e0b',
            error: colors.error?.hex || '#ef4444'
        };
    }

    /**
     * Generate semantic color tokens from base colors
     * @param {Object} baseColors - Base color mapping
     * @returns {Object} Semantic color tokens
     */
    generateSemanticColors(baseColors) {
        const tokens = {};

        Object.entries(baseColors).forEach(([roleKey, color]) => {
            const role = this.colorRoles[roleKey];
            if (!role) return;

            // Base color token
            tokens[roleKey] = {
                value: color,
                cssVariable: role.cssVariable,
                description: role.description,
                wcagRole: role.wcagRole
            };

            // Generate variants
            role.variants.forEach(variantName => {
                const variantKey = variantName.split('-').pop(); // Get 'light', 'dark', etc.
                const generator = this.variantGenerators[variantKey];

                if (generator) {
                    const variantColor = generator(color);
                    tokens[variantName] = {
                        value: variantColor,
                        cssVariable: `${role.cssVariable}-${variantKey}`,
                        description: `${role.description} - ${variantKey} variant`,
                        wcagRole: role.wcagRole,
                        baseColor: roleKey
                    };
                }
            });
        });

        return tokens;
    }

    /**
     * Generate component-specific color tokens
     * @param {Object} semanticTokens - Semantic color tokens
     * @returns {Object} Component color tokens
     */
    generateComponentColors(semanticTokens) {
        const componentTokens = {};

        // Button colors
        componentTokens['button-primary-bg'] = {
            value: semanticTokens.primary.value,
            cssVariable: '--color-button-primary-bg',
            description: 'Primary button background',
            basedOn: 'primary'
        };

        componentTokens['button-primary-text'] = {
            value: this.getContrastingTextColor(semanticTokens.primary.value),
            cssVariable: '--color-button-primary-text',
            description: 'Primary button text',
            basedOn: 'primary'
        };

        componentTokens['button-secondary-bg'] = {
            value: semanticTokens.secondary.value,
            cssVariable: '--color-button-secondary-bg',
            description: 'Secondary button background',
            basedOn: 'secondary'
        };

        // Link colors
        componentTokens['link-color'] = {
            value: semanticTokens.primary.value,
            cssVariable: '--color-link',
            description: 'Link color',
            basedOn: 'primary'
        };

        componentTokens['link-hover'] = {
            value: semanticTokens['primary-hover']?.value || this.darkenColor(semanticTokens.primary.value, 0.1),
            cssVariable: '--color-link-hover',
            description: 'Link hover color',
            basedOn: 'primary-hover'
        };

        // Form element colors
        componentTokens['input-border'] = {
            value: semanticTokens.border.value,
            cssVariable: '--color-input-border',
            description: 'Input border color',
            basedOn: 'border'
        };

        componentTokens['input-focus'] = {
            value: semanticTokens.primary.value,
            cssVariable: '--color-input-focus',
            description: 'Input focus color',
            basedOn: 'primary'
        };

        // Card colors
        componentTokens['card-bg'] = {
            value: semanticTokens.surface.value,
            cssVariable: '--color-card-bg',
            description: 'Card background color',
            basedOn: 'surface'
        };

        componentTokens['card-border'] = {
            value: semanticTokens['border-light']?.value || this.lightenColor(semanticTokens.border.value, 0.3),
            cssVariable: '--color-card-border',
            description: 'Card border color',
            basedOn: 'border-light'
        };

        return componentTokens;
    }

    /**
     * Validate accessibility compliance for all generated colors
     * @param {Object} tokens - Color tokens to validate
     * @returns {Object} Validation results
     */
    validateAccessibility(tokens) {
        const startTime = performance.now();
        const results = {
            allValid: true,
            violations: [],
            corrections: []
        };

        // Check text/background contrast ratios
        const textTokens = Object.entries(tokens).filter(([name]) => name.includes('text'));
        const backgroundTokens = Object.entries(tokens).filter(([name]) =>
            name.includes('background') || name.includes('surface')
        );

        textTokens.forEach(([textName, textToken]) => {
            backgroundTokens.forEach(([bgName, bgToken]) => {
                const contrastRatio = this.calculateContrastRatio(textToken.value, bgToken.value);
                const minimumRatio = textToken.wcagRole === 'text' ? 4.5 : 3.0;

                if (contrastRatio < minimumRatio) {
                    results.allValid = false;
                    results.violations.push({
                        textColor: textName,
                        backgroundColor: bgName,
                        currentRatio: contrastRatio,
                        requiredRatio: minimumRatio
                    });

                    // Generate correction
                    const correctedTextColor = this.findAccessibleTextColor(bgToken.value, minimumRatio);
                    results.corrections.push({
                        tokenName: textName,
                        originalValue: textToken.value,
                        correctedValue: correctedTextColor,
                        reason: `WCAG ${minimumRatio}:1 contrast compliance`
                    });
                }
            });
        });

        this.performanceMetrics.accessibilityChecks++;
        const validationTime = performance.now() - startTime;

        console.log(`[ColorDomainGenerator] Accessibility validation completed in ${validationTime.toFixed(2)}ms`);

        return results;
    }

    /**
     * Apply accessibility corrections to tokens
     * @param {Object} tokens - Tokens to correct
     * @param {Array} corrections - Corrections to apply
     */
    applyAccessibilityCorrections(tokens, corrections) {
        corrections.forEach(correction => {
            if (tokens[correction.tokenName]) {
                tokens[correction.tokenName].value = correction.correctedValue;
                tokens[correction.tokenName].accessibilityCorrected = true;
                tokens[correction.tokenName].originalValue = correction.originalValue;
            }
        });

        console.log(`[ColorDomainGenerator] Applied ${corrections.length} accessibility corrections`);
    }

    /**
     * Generate fallback colors when PSASB palette is invalid
     * @returns {Object} Fallback color tokens
     */
    generateFallbackColors() {
        const fallbackPalette = {
            colors: {
                primary: '#3b82f6',
                secondary: '#64748b',
                accent: '#8b5cf6',
                background: '#ffffff',
                surface: '#f8fafc',
                textPrimary: '#1e293b',
                textSecondary: '#64748b',
                border: '#e2e8f0',
                success: '#22c55e',
                warning: '#f59e0b',
                error: '#ef4444'
            }
        };

        console.warn('[ColorDomainGenerator] Using fallback colors due to invalid palette');
        return this.generateFromPalette(fallbackPalette);
    }

    // Color manipulation utility functions

    /**
     * Lighten a color by a given amount
     * @param {string} color - Hex color
     * @param {number} amount - Amount to lighten (0-1)
     * @returns {string} Lightened color
     */
    lightenColor(color, amount) {
        const num = parseInt(color.replace("#", ""), 16);
        const amt = Math.round(2.55 * amount * 100);
        const R = (num >> 16) + amt;
        const G = (num >> 8 & 0x00FF) + amt;
        const B = (num & 0x0000FF) + amt;
        return "#" + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
            (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
            (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
    }

    /**
     * Darken a color by a given amount
     * @param {string} color - Hex color
     * @param {number} amount - Amount to darken (0-1)
     * @returns {string} Darkened color
     */
    darkenColor(color, amount) {
        const num = parseInt(color.replace("#", ""), 16);
        const amt = Math.round(2.55 * amount * 100);
        const R = (num >> 16) - amt;
        const G = (num >> 8 & 0x00FF) - amt;
        const B = (num & 0x0000FF) - amt;
        return "#" + (0x1000000 + (R > 255 ? 255 : R < 0 ? 0 : R) * 0x10000 +
            (G > 255 ? 255 : G < 0 ? 0 : G) * 0x100 +
            (B > 255 ? 255 : B < 0 ? 0 : B)).toString(16).slice(1);
    }

    /**
     * Add opacity to a color
     * @param {string} color - Hex color
     * @param {number} opacity - Opacity value (0-1)
     * @returns {string} Color with opacity
     */
    addOpacity(color, opacity) {
        const hex = color.replace('#', '');
        const alpha = Math.round(opacity * 255).toString(16).padStart(2, '0');
        return `#${hex}${alpha}`;
    }

    /**
     * Shift hue of a color
     * @param {string} color - Hex color
     * @param {number} degrees - Degrees to shift hue
     * @returns {string} Hue-shifted color
     */
    shiftHue(color, degrees) {
        // Simplified hue shifting - in production, use proper HSL conversion
        const hsl = this.hexToHsl(color);
        hsl.h = (hsl.h + degrees) % 360;
        return this.hslToHex(hsl);
    }

    /**
     * Desaturate a color
     * @param {string} color - Hex color
     * @param {number} amount - Amount to desaturate (0-1)
     * @returns {string} Desaturated color
     */
    desaturateColor(color, amount) {
        const hsl = this.hexToHsl(color);
        hsl.s *= (1 - amount);
        return this.hslToHex(hsl);
    }

    /**
     * Generate text color based on background
     * @param {string} backgroundColor - Background color
     * @returns {string} Appropriate text color
     */
    generateTextColor(backgroundColor) {
        const luminance = this.getLuminance(backgroundColor);
        return luminance > 0.5 ? '#1e293b' : '#ffffff';
    }

    /**
     * Generate border color based on background
     * @param {string} backgroundColor - Background color
     * @returns {string} Appropriate border color
     */
    generateBorderColor(backgroundColor) {
        const luminance = this.getLuminance(backgroundColor);
        return luminance > 0.5 ? '#e2e8f0' : '#374151';
    }

    /**
     * Get contrasting text color for accessibility
     * @param {string} backgroundColor - Background color
     * @returns {string} Contrasting text color
     */
    getContrastingTextColor(backgroundColor) {
        const luminance = this.getLuminance(backgroundColor);
        return luminance > 0.5 ? '#000000' : '#ffffff';
    }

    /**
     * Find accessible text color with minimum contrast ratio
     * @param {string} backgroundColor - Background color
     * @param {number} minimumRatio - Minimum contrast ratio
     * @returns {string} Accessible text color
     */
    findAccessibleTextColor(backgroundColor, minimumRatio = 4.5) {
        const bgLuminance = this.getLuminance(backgroundColor);

        // Try white text first
        const whiteContrast = (1 + 0.05) / (bgLuminance + 0.05);
        if (whiteContrast >= minimumRatio) {
            return '#ffffff';
        }

        // Try black text
        const blackContrast = (bgLuminance + 0.05) / (0 + 0.05);
        if (blackContrast >= minimumRatio) {
            return '#000000';
        }

        // Generate intermediate colors if needed
        return bgLuminance > 0.5 ? '#000000' : '#ffffff';
    }

    /**
     * Calculate contrast ratio between two colors
     * @param {string} color1 - First color
     * @param {string} color2 - Second color
     * @returns {number} Contrast ratio
     */
    calculateContrastRatio(color1, color2) {
        const luminance1 = this.getLuminance(color1);
        const luminance2 = this.getLuminance(color2);

        const lighter = Math.max(luminance1, luminance2);
        const darker = Math.min(luminance1, luminance2);

        return (lighter + 0.05) / (darker + 0.05);
    }

    /**
     * Calculate relative luminance of a color
     * @param {string} color - Hex color
     * @returns {number} Relative luminance
     */
    getLuminance(color) {
        const hex = color.replace('#', '');
        const r = parseInt(hex.substr(0, 2), 16) / 255;
        const g = parseInt(hex.substr(2, 2), 16) / 255;
        const b = parseInt(hex.substr(4, 2), 16) / 255;

        const rLum = r <= 0.03928 ? r / 12.92 : Math.pow((r + 0.055) / 1.055, 2.4);
        const gLum = g <= 0.03928 ? g / 12.92 : Math.pow((g + 0.055) / 1.055, 2.4);
        const bLum = b <= 0.03928 ? b / 12.92 : Math.pow((b + 0.055) / 1.055, 2.4);

        return 0.2126 * rLum + 0.7152 * gLum + 0.0722 * bLum;
    }

    /**
     * Convert hex to HSL
     * @param {string} hex - Hex color
     * @returns {Object} HSL values
     */
    hexToHsl(hex) {
        const r = parseInt(hex.slice(1, 3), 16) / 255;
        const g = parseInt(hex.slice(3, 5), 16) / 255;
        const b = parseInt(hex.slice(5, 7), 16) / 255;

        const max = Math.max(r, g, b);
        const min = Math.min(r, g, b);
        let h, s, l = (max + min) / 2;

        if (max === min) {
            h = s = 0;
        } else {
            const d = max - min;
            s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
            switch (max) {
                case r: h = (g - b) / d + (g < b ? 6 : 0); break;
                case g: h = (b - r) / d + 2; break;
                case b: h = (r - g) / d + 4; break;
            }
            h /= 6;
        }

        return { h: h * 360, s: s * 100, l: l * 100 };
    }

    /**
     * Convert HSL to hex
     * @param {Object} hsl - HSL values
     * @returns {string} Hex color
     */
    hslToHex(hsl) {
        const h = hsl.h / 360;
        const s = hsl.s / 100;
        const l = hsl.l / 100;

        const hue2rgb = (p, q, t) => {
            if (t < 0) t += 1;
            if (t > 1) t -= 1;
            if (t < 1/6) return p + (q - p) * 6 * t;
            if (t < 1/2) return q;
            if (t < 2/3) return p + (q - p) * (2/3 - t) * 6;
            return p;
        };

        let r, g, b;
        if (s === 0) {
            r = g = b = l;
        } else {
            const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
            const p = 2 * l - q;
            r = hue2rgb(p, q, h + 1/3);
            g = hue2rgb(p, q, h);
            b = hue2rgb(p, q, h - 1/3);
        }

        const toHex = (c) => {
            const hex = Math.round(c * 255).toString(16);
            return hex.length === 1 ? '0' + hex : hex;
        };

        return `#${toHex(r)}${toHex(g)}${toHex(b)}`;
    }

    /**
     * Get performance metrics
     * @returns {Object} Performance metrics
     */
    getPerformanceMetrics() {
        return {
            ...this.performanceMetrics,
            generatedTokensCount: this.generatedTokens.size,
            colorRolesCount: Object.keys(this.colorRoles).length,
            variantGeneratorsCount: Object.keys(this.variantGenerators).length
        };
    }

    /**
     * Export generated tokens as CSS custom properties
     * @param {Object} tokens - Generated tokens
     * @returns {string} CSS custom properties
     */
    exportAsCSS(tokens) {
        let css = ':root {\n';

        // Export semantic tokens
        Object.values(tokens.semantic || {}).forEach(token => {
            css += `  ${token.cssVariable}: ${token.value};\n`;
        });

        // Export component tokens
        Object.values(tokens.component || {}).forEach(token => {
            css += `  ${token.cssVariable}: ${token.value};\n`;
        });

        css += '}\n';
        return css;
    }
}

// Export for global use
window.ColorDomainGenerator = ColorDomainGenerator;

// Create global instance
window.colorDomainGenerator = new ColorDomainGenerator();

console.log('[ColorDomainGenerator] Initialized with semantic color generation ✅');
