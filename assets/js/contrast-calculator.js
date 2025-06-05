/**
 * WCAG Contrast Calculator and Automatic Color Pairing
 * Medical Spa Visual Customizer - Accessibility Module
 */

class ContrastCalculator {
    constructor() {
        this.contrastRatios = new Map();
        this.wcagLevels = {
            AA_NORMAL: 4.5,
            AA_LARGE: 3.0,
            AAA_NORMAL: 7.0,
            AAA_LARGE: 4.5
        };
    }

    /**
     * Calculate WCAG contrast ratio between two colors
     * @param {string} color1 - First color (hex, rgb, or hsl)
     * @param {string} color2 - Second color (hex, rgb, or hsl)
     * @returns {number} Contrast ratio (1-21)
     */
    calculateContrast(color1, color2) {
        const rgb1 = this.parseColor(color1);
        const rgb2 = this.parseColor(color2);

        if (!rgb1 || !rgb2) {
            console.warn('ContrastCalculator: Invalid color format', color1, color2);
            return 1;
        }

        const luminance1 = this.calculateLuminance(rgb1);
        const luminance2 = this.calculateLuminance(rgb2);

        const lighter = Math.max(luminance1, luminance2);
        const darker = Math.min(luminance1, luminance2);

        const ratio = (lighter + 0.05) / (darker + 0.05);

        // Cache the result
        const key = `${color1}-${color2}`;
        this.contrastRatios.set(key, ratio);

        return Math.round(ratio * 10) / 10;
    }

    /**
     * Calculate relative luminance of a color
     * @param {Object} rgb - RGB color object {r, g, b}
     * @returns {number} Luminance value (0-1)
     */
    calculateLuminance(rgb) {
        const {r, g, b} = rgb;

        const sRGB = [r, g, b].map(component => {
            component = component / 255;
            return component <= 0.03928
                ? component / 12.92
                : Math.pow((component + 0.055) / 1.055, 2.4);
        });

        return 0.2126 * sRGB[0] + 0.7152 * sRGB[1] + 0.0722 * sRGB[2];
    }

    /**
     * Parse color string to RGB object
     * @param {string} color - Color in various formats
     * @returns {Object|null} RGB object or null if invalid
     */
    parseColor(color) {
        // Remove whitespace
        color = color.trim();

        // Hex color
        if (color.match(/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/)) {
            return this.hexToRgb(color);
        }

        // RGB color
        const rgbMatch = color.match(/rgb\((\d+),\s*(\d+),\s*(\d+)\)/);
        if (rgbMatch) {
            return {
                r: parseInt(rgbMatch[1]),
                g: parseInt(rgbMatch[2]),
                b: parseInt(rgbMatch[3])
            };
        }

        // RGBA color
        const rgbaMatch = color.match(/rgba\((\d+),\s*(\d+),\s*(\d+),\s*[\d.]+\)/);
        if (rgbaMatch) {
            return {
                r: parseInt(rgbaMatch[1]),
                g: parseInt(rgbaMatch[2]),
                b: parseInt(rgbaMatch[3])
            };
        }

        // Named colors (basic support)
        const namedColors = {
            'white': '#FFFFFF',
            'black': '#000000',
            'red': '#FF0000',
            'green': '#008000',
            'blue': '#0000FF'
        };

        if (namedColors[color.toLowerCase()]) {
            return this.hexToRgb(namedColors[color.toLowerCase()]);
        }

        return null;
    }

    /**
     * Convert hex color to RGB object
     * @param {string} hex - Hex color string
     * @returns {Object} RGB object
     */
    hexToRgb(hex) {
        const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }

    /**
     * Find the best contrasting text color for a background
     * @param {string} backgroundColor - Background color
     * @param {Array} textColors - Array of potential text colors
     * @returns {Object} Best contrast result
     */
    findBestContrast(backgroundColor, textColors = ['#FFFFFF', '#000000', '#2C3E50']) {
        let bestContrast = {
            color: textColors[0],
            ratio: 0,
            level: 'FAIL'
        };

        textColors.forEach(textColor => {
            const ratio = this.calculateContrast(backgroundColor, textColor);
            if (ratio > bestContrast.ratio) {
                bestContrast = {
                    color: textColor,
                    ratio: ratio,
                    level: this.getWCAGLevel(ratio)
                };
            }
        });

        return bestContrast;
    }

    /**
     * Get WCAG compliance level for contrast ratio
     * @param {number} ratio - Contrast ratio
     * @returns {string} WCAG level
     */
    getWCAGLevel(ratio) {
        if (ratio >= this.wcagLevels.AAA_NORMAL) {
            return 'AAA';
        } else if (ratio >= this.wcagLevels.AA_NORMAL) {
            return 'AA';
        } else if (ratio >= this.wcagLevels.AA_LARGE) {
            return 'AA_LARGE';
        } else {
            return 'FAIL';
        }
    }

    /**
     * Generate automatic contrast pairs for medical spa theme
     * @param {Object} colors - Color palette object
     * @returns {Object} Contrast pairs with ratios and labels
     */
    generateMedicalSpaContrastPairs(colors) {
        const pairs = {};

        // Light background with dark text
        if (colors.light && colors.dark) {
            const ratio = this.calculateContrast(colors.light, colors.dark);
            pairs.lightBackground = {
                background: colors.light,
                text: colors.dark,
                ratio: `${ratio}:1`,
                label: ratio >= 7 ? 'Easy Reading' : 'Good Reading',
                wcagLevel: this.getWCAGLevel(ratio),
                usage: 'Perfect for main content areas, cards, and text blocks'
            };
        }

        // Dark background with light text
        if (colors.dark && colors.light) {
            const ratio = this.calculateContrast(colors.dark, colors.light);
            pairs.darkBackground = {
                background: colors.dark,
                text: colors.light,
                ratio: `${ratio}:1`,
                label: ratio >= 7 ? 'High Contrast' : 'Good Contrast',
                wcagLevel: this.getWCAGLevel(ratio),
                usage: 'Ideal for headers, footers, and emphasis sections'
            };
        }

        // Primary color with white text
        if (colors.primary) {
            const ratio = this.calculateContrast(colors.primary, '#FFFFFF');
            pairs.primaryColor = {
                background: colors.primary,
                text: '#FFFFFF',
                ratio: `${ratio}:1`,
                label: ratio >= 7 ? 'Professional' : 'Brand Standard',
                wcagLevel: this.getWCAGLevel(ratio),
                usage: 'Perfect for buttons, calls-to-action, and branding'
            };
        }

        // Secondary color auto-pairing
        if (colors.secondary) {
            const whiteContrast = this.calculateContrast(colors.secondary, '#FFFFFF');
            const darkContrast = this.calculateContrast(colors.secondary, colors.dark || '#2C3E50');

            const bestText = whiteContrast > darkContrast ? '#FFFFFF' : (colors.dark || '#2C3E50');
            const bestRatio = Math.max(whiteContrast, darkContrast);

            pairs.secondaryColor = {
                background: colors.secondary,
                text: bestText,
                ratio: `${bestRatio}:1`,
                label: bestRatio >= 7 ? 'Therapeutic' : 'Calming',
                wcagLevel: this.getWCAGLevel(bestRatio),
                usage: 'Great for accent elements and supporting content'
            };
        }

        // Accent color auto-pairing
        if (colors.accent) {
            const whiteContrast = this.calculateContrast(colors.accent, '#FFFFFF');
            const darkContrast = this.calculateContrast(colors.accent, colors.dark || '#2C3E50');

            const bestText = whiteContrast > darkContrast ? '#FFFFFF' : (colors.dark || '#2C3E50');
            const bestRatio = Math.max(whiteContrast, darkContrast);

            pairs.accentColor = {
                background: colors.accent,
                text: bestText,
                ratio: `${bestRatio}:1`,
                label: bestRatio >= 7 ? 'Luxury' : 'Premium',
                wcagLevel: this.getWCAGLevel(bestRatio),
                usage: 'Perfect for premium features and highlights'
            };
        }

        return pairs;
    }

    /**
     * Validate color accessibility for medical spa requirements
     * @param {Object} colors - Color palette
     * @returns {Object} Validation results
     */
    validateMedicalSpaAccessibility(colors) {
        const validation = {
            passed: true,
            issues: [],
            recommendations: [],
            score: 0
        };

        const requiredPairs = this.generateMedicalSpaContrastPairs(colors);
        const totalPairs = Object.keys(requiredPairs).length;
        let passedPairs = 0;

        Object.entries(requiredPairs).forEach(([key, pair]) => {
            const ratio = parseFloat(pair.ratio.split(':')[0]);

            if (ratio >= 7.0) {
                passedPairs++;
                validation.score += 25; // AAA compliance
            } else if (ratio >= 4.5) {
                passedPairs++;
                validation.score += 15; // AA compliance
                validation.recommendations.push(
                    `${key}: Good contrast (${pair.ratio}) but could be improved for AAA compliance`
                );
            } else {
                validation.passed = false;
                validation.issues.push(
                    `${key}: Poor contrast (${pair.ratio}) - fails WCAG standards`
                );
            }
        });

        // Additional medical spa specific checks
        if (colors.primary && colors.light) {
            const ratio = this.calculateContrast(colors.primary, colors.light);
            if (ratio < 3.0) {
                validation.issues.push(
                    'Primary color and light background have insufficient contrast for UI elements'
                );
            }
        }

        validation.score = Math.min(100, validation.score);

        return validation;
    }

    /**
     * Get user-friendly contrast description
     * @param {number} ratio - Contrast ratio
     * @returns {string} User-friendly description
     */
    getContrastDescription(ratio) {
        if (ratio >= 15) {
            return 'Excellent contrast - perfect for all users including those with visual impairments';
        } else if (ratio >= 7) {
            return 'High contrast - exceeds accessibility standards and ensures easy reading';
        } else if (ratio >= 4.5) {
            return 'Good contrast - meets accessibility standards for normal text';
        } else if (ratio >= 3) {
            return 'Adequate contrast - acceptable for large text and UI elements';
        } else {
            return 'Poor contrast - may be difficult to read, especially for users with visual impairments';
        }
    }

    /**
     * Generate CSS custom properties for contrast pairs
     * @param {Object} pairs - Contrast pairs object
     * @returns {string} CSS custom properties
     */
    generateCSSCustomProperties(pairs) {
        let css = '/* Auto-generated contrast pairs for accessibility */\n:root {\n';

        Object.entries(pairs).forEach(([key, pair]) => {
            const kebabKey = key.replace(/([A-Z])/g, '-$1').toLowerCase();
            css += `  --contrast-${kebabKey}-bg: ${pair.background};\n`;
            css += `  --contrast-${kebabKey}-text: ${pair.text};\n`;
            css += `  --contrast-${kebabKey}-ratio: ${pair.ratio};\n`;
        });

        css += '}\n';
        return css;
    }
}

// Initialize global contrast calculator
window.ContrastCalculator = ContrastCalculator;
window.contrastCalculator = new ContrastCalculator();

// Export for use in visual customizer
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ContrastCalculator;
}
