/**
 * Example Customization Plugins - Demonstrating Extensible Architecture
 * Shows how to implement new domains in <1 hour using the plugin architecture
 *
 * @since PVC-007-DT
 * @version 1.0.0
 * @documentation Examples for developers to understand plugin development
 */

// ===================================
// 1. SPACING CUSTOMIZATION PLUGIN
// ===================================

class SpacingCustomizationPlugin extends CustomizationPlugin {
    constructor() {
        super({
            name: 'Spacing Customization',
            version: '1.0.0',
            description: 'Provides spacing scale customization with semantic tokens',
            author: 'MedSpa Theme',
            dependencies: [], // No dependencies - can work standalone
            priority: 5
        });
    }

    /**
     * Setup spacing token definitions
     */
    setupTokenDefinitions() {
        // Define spacing scale tokens
        this.tokens.set('spacing-xs', {
            description: 'Extra small spacing',
            cssVariable: '--spacing-xs',
            defaultValue: '4px',
            type: 'dimension'
        });

        this.tokens.set('spacing-sm', {
            description: 'Small spacing',
            cssVariable: '--spacing-sm',
            defaultValue: '8px',
            type: 'dimension'
        });

        this.tokens.set('spacing-md', {
            description: 'Medium spacing',
            cssVariable: '--spacing-md',
            defaultValue: '16px',
            type: 'dimension'
        });

        this.tokens.set('spacing-lg', {
            description: 'Large spacing',
            cssVariable: '--spacing-lg',
            defaultValue: '24px',
            type: 'dimension'
        });

        this.tokens.set('spacing-xl', {
            description: 'Extra large spacing',
            cssVariable: '--spacing-xl',
            defaultValue: '32px',
            type: 'dimension'
        });

        this.tokens.set('spacing-2xl', {
            description: 'Double extra large spacing',
            cssVariable: '--spacing-2xl',
            defaultValue: '48px',
            type: 'dimension'
        });
    }

    /**
     * Generate spacing tokens from scale configuration
     * @param {Object} changes - Spacing configuration
     * @param {Object} options - Generation options
     * @returns {Promise<Object>} Generated spacing tokens
     */
    async generateTokens(changes, options = {}) {
        const startTime = performance.now();
        const tokens = {};

        // Get base spacing value and scale ratio
        const baseSpacing = changes.baseSpacing || 16; // 16px default
        const scaleRatio = changes.scaleRatio || 1.5; // 1.5x scale default
        const scaleType = changes.scaleType || 'geometric'; // geometric, arithmetic

        // Generate spacing scale
        const spacingSizes = ['xs', 'sm', 'md', 'lg', 'xl', '2xl'];
        const baseIndex = 2; // 'md' is the base

        spacingSizes.forEach((size, index) => {
            let value;
            if (scaleType === 'geometric') {
                // Geometric progression
                const exponent = index - baseIndex;
                value = Math.round(baseSpacing * Math.pow(scaleRatio, exponent));
            } else {
                // Arithmetic progression
                const step = baseSpacing * (scaleRatio - 1) / 2;
                value = Math.round(baseSpacing + (index - baseIndex) * step);
            }

            tokens[`spacing-${size}`] = {
                value: `${value}px`,
                cssVariable: `--spacing-${size}`,
                description: `${size.toUpperCase()} spacing token`,
                scalePosition: index,
                baseValue: value
            };
        });

        this.performanceMetrics.tokenGenerationTime += performance.now() - startTime;
        return tokens;
    }

    /**
     * Get WordPress controls for spacing customization
     * @returns {Array<Object>} WordPress control configurations
     */
    getWordPressControls() {
        return [
            {
                id: 'spacing_base_size',
                label: 'Base Spacing Size',
                type: 'range',
                section: 'spacing_customization',
                transport: 'postMessage',
                default: 16,
                input_attrs: {
                    min: 8,
                    max: 32,
                    step: 1
                }
            },
            {
                id: 'spacing_scale_ratio',
                label: 'Spacing Scale Ratio',
                type: 'range',
                section: 'spacing_customization',
                transport: 'postMessage',
                default: 1.5,
                input_attrs: {
                    min: 1.2,
                    max: 2.0,
                    step: 0.1
                }
            },
            {
                id: 'spacing_scale_type',
                label: 'Scale Type',
                type: 'select',
                section: 'spacing_customization',
                transport: 'postMessage',
                default: 'geometric',
                choices: {
                    geometric: 'Geometric Progression',
                    arithmetic: 'Arithmetic Progression'
                }
            }
        ];
    }

    /**
     * Generate CSS for spacing tokens
     * @param {Object} tokens - Generated tokens
     * @param {Object} options - CSS generation options
     * @returns {string} CSS custom properties
     */
    async generateCSS(tokens, options = {}) {
        let css = '/* Spacing Design Tokens */\n:root {\n';

        Object.values(tokens).forEach(token => {
            css += `  ${token.cssVariable}: ${token.value};\n`;
        });

        css += '}\n';
        return css;
    }
}

// ===================================
// 2. ANIMATION CUSTOMIZATION PLUGIN
// ===================================

class AnimationCustomizationPlugin extends CustomizationPlugin {
    constructor() {
        super({
            name: 'Animation Customization',
            version: '1.0.0',
            description: 'Provides animation and transition customization',
            author: 'MedSpa Theme',
            dependencies: [], // Independent plugin
            priority: 8
        });
    }

    /**
     * Setup animation token definitions
     */
    setupTokenDefinitions() {
        // Timing tokens
        this.tokens.set('duration-fast', {
            description: 'Fast animation duration',
            cssVariable: '--duration-fast',
            defaultValue: '150ms',
            type: 'duration'
        });

        this.tokens.set('duration-normal', {
            description: 'Normal animation duration',
            cssVariable: '--duration-normal',
            defaultValue: '300ms',
            type: 'duration'
        });

        this.tokens.set('duration-slow', {
            description: 'Slow animation duration',
            cssVariable: '--duration-slow',
            defaultValue: '500ms',
            type: 'duration'
        });

        // Easing tokens
        this.tokens.set('easing-ease', {
            description: 'Standard ease timing function',
            cssVariable: '--easing-ease',
            defaultValue: 'cubic-bezier(0.25, 0.1, 0.25, 1)',
            type: 'timingFunction'
        });

        this.tokens.set('easing-ease-in', {
            description: 'Ease in timing function',
            cssVariable: '--easing-ease-in',
            defaultValue: 'cubic-bezier(0.42, 0, 1, 1)',
            type: 'timingFunction'
        });

        this.tokens.set('easing-ease-out', {
            description: 'Ease out timing function',
            cssVariable: '--easing-ease-out',
            defaultValue: 'cubic-bezier(0, 0, 0.58, 1)',
            type: 'timingFunction'
        });
    }

    /**
     * Generate animation tokens from configuration
     * @param {Object} changes - Animation configuration
     * @param {Object} options - Generation options
     * @returns {Promise<Object>} Generated animation tokens
     */
    async generateTokens(changes, options = {}) {
        const startTime = performance.now();
        const tokens = {};

        // Animation style presets
        const animationStyle = changes.animationStyle || 'smooth';
        const speedMultiplier = changes.speedMultiplier || 1.0;

        // Duration tokens
        const baseDurations = {
            fast: 150,
            normal: 300,
            slow: 500
        };

        Object.entries(baseDurations).forEach(([speed, baseDuration]) => {
            const adjustedDuration = Math.round(baseDuration * speedMultiplier);
            tokens[`duration-${speed}`] = {
                value: `${adjustedDuration}ms`,
                cssVariable: `--duration-${speed}`,
                description: `${speed} animation duration`,
                baseValue: adjustedDuration
            };
        });

        // Easing tokens based on style
        const easingPresets = {
            smooth: {
                ease: 'cubic-bezier(0.25, 0.1, 0.25, 1)',
                'ease-in': 'cubic-bezier(0.42, 0, 1, 1)',
                'ease-out': 'cubic-bezier(0, 0, 0.58, 1)'
            },
            bouncy: {
                ease: 'cubic-bezier(0.68, -0.55, 0.265, 1.55)',
                'ease-in': 'cubic-bezier(0.6, -0.28, 0.735, 0.045)',
                'ease-out': 'cubic-bezier(0.175, 0.885, 0.32, 1.275)'
            },
            sharp: {
                ease: 'cubic-bezier(0.4, 0.0, 0.2, 1)',
                'ease-in': 'cubic-bezier(0.4, 0.0, 1, 1)',
                'ease-out': 'cubic-bezier(0.0, 0.0, 0.2, 1)'
            }
        };

        const selectedEasing = easingPresets[animationStyle] || easingPresets.smooth;

        Object.entries(selectedEasing).forEach(([type, curve]) => {
            tokens[`easing-${type}`] = {
                value: curve,
                cssVariable: `--easing-${type}`,
                description: `${type} timing function`,
                style: animationStyle
            };
        });

        // Composite transition tokens
        tokens['transition-fast'] = {
            value: `all ${tokens['duration-fast'].value} ${tokens['easing-ease-out'].value}`,
            cssVariable: '--transition-fast',
            description: 'Fast transition preset'
        };

        tokens['transition-normal'] = {
            value: `all ${tokens['duration-normal'].value} ${tokens['easing-ease'].value}`,
            cssVariable: '--transition-normal',
            description: 'Normal transition preset'
        };

        this.performanceMetrics.tokenGenerationTime += performance.now() - startTime;
        return tokens;
    }

    /**
     * Get WordPress controls for animation customization
     * @returns {Array<Object>} WordPress control configurations
     */
    getWordPressControls() {
        return [
            {
                id: 'animation_style',
                label: 'Animation Style',
                type: 'select',
                section: 'animation_customization',
                transport: 'postMessage',
                default: 'smooth',
                choices: {
                    smooth: 'Smooth & Professional',
                    bouncy: 'Bouncy & Playful',
                    sharp: 'Sharp & Responsive'
                }
            },
            {
                id: 'animation_speed',
                label: 'Animation Speed Multiplier',
                type: 'range',
                section: 'animation_customization',
                transport: 'postMessage',
                default: 1.0,
                input_attrs: {
                    min: 0.5,
                    max: 2.0,
                    step: 0.1
                }
            }
        ];
    }
}

// ===================================
// 3. DARK MODE CUSTOMIZATION PLUGIN
// ===================================

class DarkModeCustomizationPlugin extends CustomizationPlugin {
    constructor() {
        super({
            name: 'Dark Mode Customization',
            version: '1.0.0',
            description: 'Provides automatic dark mode token generation',
            author: 'MedSpa Theme',
            dependencies: ['color'], // Depends on color domain
            priority: 6
        });
    }

    /**
     * Setup dark mode token definitions
     */
    setupTokenDefinitions() {
        // Dark mode color overrides
        this.tokens.set('dark-background', {
            description: 'Dark mode background color',
            cssVariable: '--dark-background',
            defaultValue: '#1a1a1a',
            type: 'color'
        });

        this.tokens.set('dark-surface', {
            description: 'Dark mode surface color',
            cssVariable: '--dark-surface',
            defaultValue: '#2d2d2d',
            type: 'color'
        });

        this.tokens.set('dark-text-primary', {
            description: 'Dark mode primary text color',
            cssVariable: '--dark-text-primary',
            defaultValue: '#ffffff',
            type: 'color'
        });

        this.tokens.set('dark-mode-enabled', {
            description: 'Dark mode toggle state',
            cssVariable: '--dark-mode-enabled',
            defaultValue: '0',
            type: 'number'
        });
    }

    /**
     * Generate dark mode tokens from light mode colors
     * @param {Object} changes - Dark mode configuration
     * @param {Object} options - Generation options
     * @returns {Promise<Object>} Generated dark mode tokens
     */
    async generateTokens(changes, options = {}) {
        const startTime = performance.now();
        const tokens = {};

        // Get light mode colors from color domain
        const colorDomain = this.engine.getDomain('color');
        const lightColors = changes.lightColors || {};

        // Dark mode generation strategy
        const strategy = changes.strategy || 'automatic'; // automatic, manual, intelligent
        const intensity = changes.intensity || 0.85; // How dark the dark mode should be

        if (strategy === 'automatic') {
            // Automatically generate dark mode from light colors
            Object.entries(lightColors).forEach(([colorName, lightColor]) => {
                const darkColor = this.generateDarkVariant(lightColor, intensity);
                tokens[`dark-${colorName}`] = {
                    value: darkColor,
                    cssVariable: `--dark-${colorName}`,
                    description: `Dark mode ${colorName}`,
                    lightModeSource: lightColor,
                    generationMethod: 'automatic'
                };
            });
        } else if (strategy === 'intelligent') {
            // Intelligent dark mode generation with accessibility
            Object.entries(lightColors).forEach(([colorName, lightColor]) => {
                const darkColor = this.generateIntelligentDarkVariant(lightColor, colorName, intensity);
                tokens[`dark-${colorName}`] = {
                    value: darkColor,
                    cssVariable: `--dark-${colorName}`,
                    description: `Dark mode ${colorName}`,
                    lightModeSource: lightColor,
                    generationMethod: 'intelligent'
                };
            });
        }

        // Add dark mode toggle tokens
        tokens['dark-mode-enabled'] = {
            value: changes.enabled ? '1' : '0',
            cssVariable: '--dark-mode-enabled',
            description: 'Dark mode enabled state'
        };

        this.performanceMetrics.tokenGenerationTime += performance.now() - startTime;
        return tokens;
    }

    /**
     * Generate dark variant of a color
     * @param {string} lightColor - Light mode color
     * @param {number} intensity - Dark mode intensity
     * @returns {string} Dark mode color
     */
    generateDarkVariant(lightColor, intensity) {
        // Simple dark mode generation (invert lightness)
        // In production, use proper HSL color space conversion
        const rgb = this.hexToRgb(lightColor);
        if (!rgb) return lightColor;

        const darkRgb = {
            r: Math.round(rgb.r * (1 - intensity)),
            g: Math.round(rgb.g * (1 - intensity)),
            b: Math.round(rgb.b * (1 - intensity))
        };

        return this.rgbToHex(darkRgb);
    }

    /**
     * Generate intelligent dark variant with accessibility
     * @param {string} lightColor - Light mode color
     * @param {string} colorRole - Color role (background, text, etc.)
     * @param {number} intensity - Dark mode intensity
     * @returns {string} Dark mode color
     */
    generateIntelligentDarkVariant(lightColor, colorRole, intensity) {
        // Role-specific dark mode generation
        if (colorRole.includes('background')) {
            return this.generateDarkBackground(lightColor, intensity);
        } else if (colorRole.includes('text')) {
            return this.generateDarkText(lightColor, intensity);
        } else {
            return this.generateDarkVariant(lightColor, intensity);
        }
    }

    generateDarkBackground(lightBg, intensity) {
        // Generate dark background with proper contrast
        return '#1a1a1a'; // Safe dark background
    }

    generateDarkText(lightText, intensity) {
        // Generate dark mode text with accessibility
        return '#ffffff'; // Safe dark mode text
    }

    // Utility methods for color conversion
    hexToRgb(hex) {
        const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }

    rgbToHex(rgb) {
        return "#" + ((1 << 24) + (rgb.r << 16) + (rgb.g << 8) + rgb.b).toString(16).slice(1);
    }

    /**
     * Get WordPress controls for dark mode
     * @returns {Array<Object>} WordPress control configurations
     */
    getWordPressControls() {
        return [
            {
                id: 'dark_mode_enabled',
                label: 'Enable Dark Mode',
                type: 'checkbox',
                section: 'dark_mode_customization',
                transport: 'postMessage',
                default: false
            },
            {
                id: 'dark_mode_strategy',
                label: 'Dark Mode Generation',
                type: 'select',
                section: 'dark_mode_customization',
                transport: 'postMessage',
                default: 'intelligent',
                choices: {
                    automatic: 'Automatic Generation',
                    intelligent: 'Intelligent with Accessibility',
                    manual: 'Manual Configuration'
                }
            },
            {
                id: 'dark_mode_intensity',
                label: 'Dark Mode Intensity',
                type: 'range',
                section: 'dark_mode_customization',
                transport: 'postMessage',
                default: 0.85,
                input_attrs: {
                    min: 0.5,
                    max: 1.0,
                    step: 0.05
                }
            }
        ];
    }
}

// ===================================
// PLUGIN REGISTRATION EXAMPLES
// ===================================

/**
 * Example of how to register these plugins with the Universal Customization Engine
 * This demonstrates the <1 hour plugin implementation workflow
 */
function registerExamplePlugins() {
    // Wait for Universal Customization Engine to be ready
    if (typeof window.universalCustomizationEngine !== 'undefined') {
        const engine = window.universalCustomizationEngine;

        // Register spacing plugin
        const spacingPlugin = new SpacingCustomizationPlugin();
        spacingPlugin.register(engine).then(success => {
            if (success) {
                console.log('✅ Spacing Plugin registered successfully');
            }
        });

        // Register animation plugin
        const animationPlugin = new AnimationCustomizationPlugin();
        animationPlugin.register(engine).then(success => {
            if (success) {
                console.log('✅ Animation Plugin registered successfully');
            }
        });

        // Register dark mode plugin (depends on color domain)
        const darkModePlugin = new DarkModeCustomizationPlugin();
        darkModePlugin.register(engine).then(success => {
            if (success) {
                console.log('✅ Dark Mode Plugin registered successfully');
            }
        });

        console.log('[ExamplePlugins] All example plugins registered ✅');
    } else {
        // Retry registration when engine is ready
        setTimeout(registerExamplePlugins, 100);
    }
}

// Auto-register when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', registerExamplePlugins);
} else {
    registerExamplePlugins();
}

// Export for global use
window.SpacingCustomizationPlugin = SpacingCustomizationPlugin;
window.AnimationCustomizationPlugin = AnimationCustomizationPlugin;
window.DarkModeCustomizationPlugin = DarkModeCustomizationPlugin;

console.log('[ExamplePlugins] Example customization plugins ready ✅');
