/**
 * Component Token System - UI Component Design Tokens
 * Generates component-level design tokens that inherit from semantic tokens
 *
 * @since PVC-006-DT
 * @version 1.0.0
 * @performance Optimized component token generation with semantic inheritance
 */

class ComponentTokenSystem {
    constructor() {
        this.componentTokens = new Map();
        this.componentDefinitions = new Map();
        this.semanticInheritance = new Map();
        this.responsiveRules = new Map();

        // Performance tracking
        this.performanceMetrics = {
            generationTime: 0,
            componentTokensGenerated: 0,
            inheritanceResolutions: 0
        };

        // Initialize component system
        this.initializeComponentDefinitions();
        this.initializeSemanticInheritance();
        this.initializeResponsiveRules();

        // Bind methods for performance
        this.generateComponentTokens = this.generateComponentTokens.bind(this);
        this.resolveSemanticInheritance = this.resolveSemanticInheritance.bind(this);
        this.generateResponsiveVariants = this.generateResponsiveVariants.bind(this);
    }

    /**
     * Initialize component definitions and their token structure
     */
    initializeComponentDefinitions() {
        // Define component token structures
        this.componentDefinitions = new Map([
            ['button', {
                name: 'Button',
                description: 'Interactive button components',
                variants: ['primary', 'secondary', 'outline', 'ghost', 'link'],
                states: ['default', 'hover', 'active', 'focus', 'disabled'],
                sizes: ['sm', 'md', 'lg'],
                tokens: {
                    'background': { inherit: 'color-primary', type: 'color' },
                    'color': { inherit: 'color-primary', type: 'color', transformation: 'contrast' },
                    'border': { inherit: 'color-primary', type: 'color' },
                    'border-radius': { inherit: 'spacing-sm', type: 'dimension' },
                    'padding-x': { inherit: 'spacing-md', type: 'dimension' },
                    'padding-y': { inherit: 'spacing-sm', type: 'dimension' },
                    'font-size': { inherit: 'font-body-size', type: 'dimension' },
                    'font-weight': { inherit: 'font-button-weight', type: 'fontWeight' },
                    'line-height': { inherit: 'font-button-line-height', type: 'dimension' },
                    'min-height': { value: '44px', type: 'dimension' }, // Accessibility requirement
                    'transition': { value: 'all 0.2s ease-in-out', type: 'transition' }
                }
            }],
            ['card', {
                name: 'Card',
                description: 'Card container components',
                variants: ['default', 'elevated', 'outlined'],
                states: ['default', 'hover'],
                sizes: ['sm', 'md', 'lg'],
                tokens: {
                    'background': { inherit: 'color-surface', type: 'color' },
                    'border': { inherit: 'color-border', type: 'color' },
                    'border-radius': { inherit: 'spacing-md', type: 'dimension' },
                    'padding': { inherit: 'spacing-lg', type: 'dimension' },
                    'shadow': { value: '0 1px 3px rgba(0, 0, 0, 0.1)', type: 'shadow' },
                    'shadow-hover': { value: '0 4px 12px rgba(0, 0, 0, 0.15)', type: 'shadow' },
                    'transition': { value: 'box-shadow 0.2s ease-in-out', type: 'transition' }
                }
            }],
            ['input', {
                name: 'Input',
                description: 'Form input components',
                variants: ['default', 'outlined', 'filled'],
                states: ['default', 'focus', 'error', 'disabled'],
                sizes: ['sm', 'md', 'lg'],
                tokens: {
                    'background': { inherit: 'color-background', type: 'color' },
                    'border': { inherit: 'color-border', type: 'color' },
                    'border-focus': { inherit: 'color-primary', type: 'color' },
                    'border-error': { inherit: 'color-error', type: 'color' },
                    'border-radius': { inherit: 'spacing-sm', type: 'dimension' },
                    'padding-x': { inherit: 'spacing-md', type: 'dimension' },
                    'padding-y': { inherit: 'spacing-sm', type: 'dimension' },
                    'font-size': { inherit: 'font-body-size', type: 'dimension' },
                    'line-height': { inherit: 'font-body-line-height', type: 'dimension' },
                    'min-height': { value: '44px', type: 'dimension' },
                    'transition': { value: 'border-color 0.2s ease-in-out', type: 'transition' }
                }
            }],
            ['modal', {
                name: 'Modal',
                description: 'Modal dialog components',
                variants: ['default', 'large', 'fullscreen'],
                states: ['default'],
                sizes: ['sm', 'md', 'lg', 'xl'],
                tokens: {
                    'background': { inherit: 'color-surface', type: 'color' },
                    'backdrop': { value: 'rgba(0, 0, 0, 0.5)', type: 'color' },
                    'border-radius': { inherit: 'spacing-lg', type: 'dimension' },
                    'padding': { inherit: 'spacing-xl', type: 'dimension' },
                    'shadow': { value: '0 10px 25px rgba(0, 0, 0, 0.2)', type: 'shadow' },
                    'max-width': { value: '90vw', type: 'dimension' },
                    'max-height': { value: '90vh', type: 'dimension' }
                }
            }],
            ['navbar', {
                name: 'Navigation Bar',
                description: 'Navigation components',
                variants: ['default', 'transparent', 'sticky'],
                states: ['default', 'scrolled'],
                sizes: ['sm', 'md', 'lg'],
                tokens: {
                    'background': { inherit: 'color-surface', type: 'color' },
                    'background-scrolled': { inherit: 'color-background', type: 'color' },
                    'border-bottom': { inherit: 'color-border', type: 'color' },
                    'padding-x': { inherit: 'spacing-lg', type: 'dimension' },
                    'padding-y': { inherit: 'spacing-md', type: 'dimension' },
                    'height': { value: '64px', type: 'dimension' },
                    'shadow': { value: '0 2px 8px rgba(0, 0, 0, 0.1)', type: 'shadow' },
                    'backdrop-filter': { value: 'blur(10px)', type: 'filter' }
                }
            }],
            ['alert', {
                name: 'Alert',
                description: 'Alert message components',
                variants: ['info', 'success', 'warning', 'error'],
                states: ['default', 'dismissible'],
                sizes: ['sm', 'md', 'lg'],
                tokens: {
                    'background': { inherit: 'color-surface', type: 'color' },
                    'border': { inherit: 'color-border', type: 'color' },
                    'border-radius': { inherit: 'spacing-sm', type: 'dimension' },
                    'padding': { inherit: 'spacing-md', type: 'dimension' },
                    'icon-size': { value: '20px', type: 'dimension' },
                    'icon-color': { inherit: 'color-primary', type: 'color' }
                }
            }],
            ['tooltip', {
                name: 'Tooltip',
                description: 'Tooltip components',
                variants: ['default', 'dark'],
                states: ['default'],
                sizes: ['sm', 'md'],
                tokens: {
                    'background': { value: 'rgba(0, 0, 0, 0.9)', type: 'color' },
                    'color': { value: '#ffffff', type: 'color' },
                    'border-radius': { inherit: 'spacing-xs', type: 'dimension' },
                    'padding-x': { inherit: 'spacing-sm', type: 'dimension' },
                    'padding-y': { inherit: 'spacing-xs', type: 'dimension' },
                    'font-size': { inherit: 'font-body-small-size', type: 'dimension' },
                    'max-width': { value: '200px', type: 'dimension' }
                }
            }]
        ]);

        console.log('[ComponentTokenSystem] Component definitions initialized');
    }

    /**
     * Initialize semantic inheritance mappings
     */
    initializeSemanticInheritance() {
        // Define how component tokens inherit from semantic tokens
        this.semanticInheritance = new Map([
            ['color-primary', 'primary'],
            ['color-secondary', 'secondary'],
            ['color-accent', 'accent'],
            ['color-surface', 'surface'],
            ['color-background', 'background'],
            ['color-text-primary', 'text-primary'],
            ['color-text-secondary', 'text-secondary'],
            ['color-border', 'border'],
            ['color-success', 'success'],
            ['color-warning', 'warning'],
            ['color-error', 'error'],
            ['spacing-xs', 'spacing-xs'],
            ['spacing-sm', 'spacing-sm'],
            ['spacing-md', 'spacing-md'],
            ['spacing-lg', 'spacing-lg'],
            ['spacing-xl', 'spacing-xl'],
            ['font-body-size', 'body'],
            ['font-body-line-height', 'body'],
            ['font-button-weight', 'button'],
            ['font-button-line-height', 'button']
        ]);

        console.log('[ComponentTokenSystem] Semantic inheritance initialized');
    }

    /**
     * Initialize responsive rules for component tokens
     */
    initializeResponsiveRules() {
        // Define responsive scaling rules for different component properties
        this.responsiveRules = new Map([
            ['padding', {
                mobile: 0.875,  // 87.5% on mobile
                tablet: 0.9375, // 93.75% on tablet
                desktop: 1.0    // 100% on desktop
            }],
            ['font-size', {
                mobile: 0.875,
                tablet: 0.9375,
                desktop: 1.0
            }],
            ['border-radius', {
                mobile: 0.75,   // Smaller border radius on mobile
                tablet: 0.875,
                desktop: 1.0
            }],
            ['spacing', {
                mobile: 0.875,
                tablet: 0.9375,
                desktop: 1.0
            }]
        ]);

        console.log('[ComponentTokenSystem] Responsive rules initialized');
    }

    /**
     * Generate component tokens from semantic tokens
     * @param {Object} semanticTokens - Semantic tokens from color and typography domains
     * @returns {Object} Generated component tokens
     */
    generateComponentTokens(semanticTokens) {
        const startTime = performance.now();

        try {
            const componentTokens = {};

            // Generate tokens for each component type
            this.componentDefinitions.forEach((definition, componentName) => {
                componentTokens[componentName] = this.generateComponentVariants(
                    componentName,
                    definition,
                    semanticTokens
                );
            });

            // Generate responsive variants
            const responsiveTokens = this.generateResponsiveVariants(componentTokens);

            // Generate interaction states
            const stateTokens = this.generateInteractionStates(componentTokens, semanticTokens);

            // Combine all tokens
            const allTokens = {
                base: componentTokens,
                responsive: responsiveTokens,
                states: stateTokens,
                metadata: {
                    generatedAt: Date.now(),
                    totalComponents: this.componentDefinitions.size,
                    totalTokens: this.countTotalTokens(componentTokens),
                    semanticInheritanceCount: this.semanticInheritance.size
                }
            };

            // Update performance metrics
            this.performanceMetrics.generationTime = performance.now() - startTime;
            this.performanceMetrics.componentTokensGenerated = allTokens.metadata.totalTokens;

            console.log(`[ComponentTokenSystem] Generated ${allTokens.metadata.totalTokens} tokens for ${allTokens.metadata.totalComponents} components in ${this.performanceMetrics.generationTime.toFixed(2)}ms`);

            return allTokens;

        } catch (error) {
            console.error('[ComponentTokenSystem] Error generating component tokens:', error);
            return this.generateFallbackTokens();
        }
    }

    /**
     * Generate variants for a specific component
     * @param {string} componentName - Component name
     * @param {Object} definition - Component definition
     * @param {Object} semanticTokens - Semantic tokens
     * @returns {Object} Component variant tokens
     */
    generateComponentVariants(componentName, definition, semanticTokens) {
        const componentTokens = {};

        definition.variants.forEach(variant => {
            componentTokens[variant] = {};

            Object.entries(definition.tokens).forEach(([tokenName, tokenConfig]) => {
                const resolvedValue = this.resolveSemanticInheritance(
                    tokenConfig,
                    semanticTokens,
                    variant,
                    componentName
                );

                componentTokens[variant][tokenName] = {
                    value: resolvedValue,
                    cssVariable: `--${componentName}-${variant}-${tokenName.replace(/_/g, '-')}`,
                    type: tokenConfig.type,
                    inheritedFrom: tokenConfig.inherit || null
                };
            });

            // Generate size variants
            definition.sizes.forEach(size => {
                componentTokens[`${variant}-${size}`] = this.generateSizeVariant(
                    componentTokens[variant],
                    size,
                    componentName,
                    variant
                );
            });
        });

        return componentTokens;
    }

    /**
     * Resolve semantic token inheritance
     * @param {Object} tokenConfig - Token configuration
     * @param {Object} semanticTokens - Available semantic tokens
     * @param {string} variant - Component variant
     * @param {string} componentName - Component name
     * @returns {string} Resolved token value
     */
    resolveSemanticInheritance(tokenConfig, semanticTokens, variant, componentName) {
        this.performanceMetrics.inheritanceResolutions++;

        // If token has a direct value, use it
        if (tokenConfig.value) {
            return tokenConfig.value;
        }

        // If token inherits from semantic tokens
        if (tokenConfig.inherit) {
            const semanticKey = this.mapSemanticKey(tokenConfig.inherit, variant, componentName);
            const semanticToken = this.findSemanticToken(semanticKey, semanticTokens);

            if (semanticToken) {
                // Apply transformation if specified
                if (tokenConfig.transformation) {
                    return this.applyTransformation(
                        semanticToken.value,
                        tokenConfig.transformation,
                        semanticTokens
                    );
                }
                return semanticToken.value;
            }
        }

        // Fallback to default values
        return this.getFallbackValue(tokenConfig.type);
    }

    /**
     * Map semantic key based on component variant
     * @param {string} inheritKey - Inheritance key
     * @param {string} variant - Component variant
     * @param {string} componentName - Component name
     * @returns {string} Mapped semantic key
     */
    mapSemanticKey(inheritKey, variant, componentName) {
        // Map variant-specific semantic tokens
        if (componentName === 'button') {
            if (variant === 'secondary') {
                return inheritKey.replace('color-primary', 'color-secondary');
            } else if (variant === 'outline') {
                return inheritKey.replace('color-primary', 'color-border');
            }
        }

        if (componentName === 'alert') {
            if (variant === 'success') {
                return inheritKey.replace('color-primary', 'color-success');
            } else if (variant === 'warning') {
                return inheritKey.replace('color-primary', 'color-warning');
            } else if (variant === 'error') {
                return inheritKey.replace('color-primary', 'color-error');
            }
        }

        return inheritKey;
    }

    /**
     * Find semantic token by key
     * @param {string} key - Semantic token key
     * @param {Object} semanticTokens - Available semantic tokens
     * @returns {Object|null} Found semantic token
     */
    findSemanticToken(key, semanticTokens) {
        // Search in color tokens
        if (semanticTokens.color && semanticTokens.color.semantic && semanticTokens.color.semantic[key]) {
            return semanticTokens.color.semantic[key];
        }

        // Search in typography tokens
        if (semanticTokens.typography && semanticTokens.typography.optimized && semanticTokens.typography.optimized[key]) {
            return semanticTokens.typography.optimized[key];
        }

        // Search in component tokens
        if (semanticTokens.component && semanticTokens.component[key]) {
            return semanticTokens.component[key];
        }

        return null;
    }

    /**
     * Apply transformation to inherited value
     * @param {string} value - Original value
     * @param {string} transformation - Transformation type
     * @param {Object} semanticTokens - Semantic tokens for context
     * @returns {string} Transformed value
     */
    applyTransformation(value, transformation, semanticTokens) {
        switch (transformation) {
            case 'contrast':
                return this.getContrastingColor(value);
            case 'lighten':
                return this.lightenColor(value, 0.1);
            case 'darken':
                return this.darkenColor(value, 0.1);
            case 'transparent':
                return this.addTransparency(value, 0.1);
            default:
                return value;
        }
    }

    /**
     * Generate size variants for component tokens
     * @param {Object} baseTokens - Base component tokens
     * @param {string} size - Size variant (sm, md, lg)
     * @param {string} componentName - Component name
     * @param {string} variant - Component variant
     * @returns {Object} Size variant tokens
     */
    generateSizeVariant(baseTokens, size, componentName, variant) {
        const sizeTokens = {};
        const sizeMultipliers = {
            'sm': 0.875,
            'md': 1.0,
            'lg': 1.125
        };

        const multiplier = sizeMultipliers[size] || 1.0;

        Object.entries(baseTokens).forEach(([tokenName, token]) => {
            let adjustedValue = token.value;

            // Apply size scaling to relevant token types
            if (token.type === 'dimension' && this.shouldScaleForSize(tokenName)) {
                adjustedValue = this.scaleDimensionValue(token.value, multiplier);
            }

            sizeTokens[tokenName] = {
                ...token,
                value: adjustedValue,
                cssVariable: `--${componentName}-${variant}-${size}-${tokenName.replace(/_/g, '-')}`,
                sizeVariant: size
            };
        });

        return sizeTokens;
    }

    /**
     * Generate responsive variants for all component tokens
     * @param {Object} componentTokens - Base component tokens
     * @returns {Object} Responsive component tokens
     */
    generateResponsiveVariants(componentTokens) {
        const responsiveTokens = {};

        Object.entries(componentTokens).forEach(([componentName, componentVariants]) => {
            responsiveTokens[componentName] = {};

            Object.entries(componentVariants).forEach(([variant, variantTokens]) => {
                responsiveTokens[componentName][variant] = {
                    mobile: this.generateBreakpointTokens(variantTokens, 'mobile'),
                    tablet: this.generateBreakpointTokens(variantTokens, 'tablet'),
                    desktop: this.generateBreakpointTokens(variantTokens, 'desktop')
                };
            });
        });

        return responsiveTokens;
    }

    /**
     * Generate tokens for specific breakpoint
     * @param {Object} baseTokens - Base tokens
     * @param {string} breakpoint - Breakpoint name
     * @returns {Object} Breakpoint-specific tokens
     */
    generateBreakpointTokens(baseTokens, breakpoint) {
        const breakpointTokens = {};

        Object.entries(baseTokens).forEach(([tokenName, token]) => {
            let adjustedValue = token.value;

            // Apply responsive scaling based on token type
            const tokenType = this.getTokenTypeForResponsive(tokenName);
            const responsiveRule = this.responsiveRules.get(tokenType);

            if (responsiveRule && token.type === 'dimension') {
                const scale = responsiveRule[breakpoint] || 1.0;
                adjustedValue = this.scaleDimensionValue(token.value, scale);
            }

            breakpointTokens[tokenName] = {
                ...token,
                value: adjustedValue,
                breakpoint
            };
        });

        return breakpointTokens;
    }

    /**
     * Generate interaction states for components
     * @param {Object} componentTokens - Base component tokens
     * @param {Object} semanticTokens - Semantic tokens
     * @returns {Object} Interaction state tokens
     */
    generateInteractionStates(componentTokens, semanticTokens) {
        const stateTokens = {};

        Object.entries(componentTokens).forEach(([componentName, componentVariants]) => {
            const definition = this.componentDefinitions.get(componentName);
            if (!definition) return;

            stateTokens[componentName] = {};

            Object.entries(componentVariants).forEach(([variant, variantTokens]) => {
                stateTokens[componentName][variant] = {};

                definition.states.forEach(state => {
                    if (state === 'default') return;

                    stateTokens[componentName][variant][state] = this.generateStateTokens(
                        variantTokens,
                        state,
                        componentName,
                        variant,
                        semanticTokens
                    );
                });
            });
        });

        return stateTokens;
    }

    /**
     * Generate tokens for specific interaction state
     * @param {Object} baseTokens - Base tokens
     * @param {string} state - Interaction state
     * @param {string} componentName - Component name
     * @param {string} variant - Variant name
     * @param {Object} semanticTokens - Semantic tokens
     * @returns {Object} State-specific tokens
     */
    generateStateTokens(baseTokens, state, componentName, variant, semanticTokens) {
        const stateTokens = {};

        Object.entries(baseTokens).forEach(([tokenName, token]) => {
            let stateValue = token.value;

            // Apply state-specific transformations
            if (state === 'hover') {
                stateValue = this.generateHoverState(token, tokenName);
            } else if (state === 'active') {
                stateValue = this.generateActiveState(token, tokenName);
            } else if (state === 'focus') {
                stateValue = this.generateFocusState(token, tokenName, semanticTokens);
            } else if (state === 'disabled') {
                stateValue = this.generateDisabledState(token, tokenName);
            }

            stateTokens[tokenName] = {
                ...token,
                value: stateValue,
                cssVariable: `--${componentName}-${variant}-${state}-${tokenName.replace(/_/g, '-')}`,
                state
            };
        });

        return stateTokens;
    }

    // State generation methods

    generateHoverState(token, tokenName) {
        if (token.type === 'color' && tokenName === 'background') {
            return this.darkenColor(token.value, 0.05);
        }
        return token.value;
    }

    generateActiveState(token, tokenName) {
        if (token.type === 'color' && tokenName === 'background') {
            return this.darkenColor(token.value, 0.1);
        }
        return token.value;
    }

    generateFocusState(token, tokenName, semanticTokens) {
        if (tokenName === 'border' || tokenName === 'border-focus') {
            const primaryColor = this.findSemanticToken('color-primary', semanticTokens);
            return primaryColor ? primaryColor.value : token.value;
        }
        return token.value;
    }

    generateDisabledState(token, tokenName) {
        if (token.type === 'color') {
            return this.addTransparency(token.value, 0.6);
        }
        return token.value;
    }

    // Utility methods

    shouldScaleForSize(tokenName) {
        const scalableTokens = ['padding', 'font-size', 'border-radius', 'margin'];
        return scalableTokens.some(scalable => tokenName.includes(scalable));
    }

    getTokenTypeForResponsive(tokenName) {
        if (tokenName.includes('padding') || tokenName.includes('margin')) return 'padding';
        if (tokenName.includes('font-size')) return 'font-size';
        if (tokenName.includes('border-radius')) return 'border-radius';
        return 'spacing';
    }

    scaleDimensionValue(value, multiplier) {
        const numericValue = parseFloat(value);
        const unit = value.replace(/[\d.]/g, '');
        return `${(numericValue * multiplier).toFixed(2)}${unit}`;
    }

    getContrastingColor(color) {
        // Simple contrasting color calculation
        const luminance = this.getLuminance(color);
        return luminance > 0.5 ? '#000000' : '#ffffff';
    }

    lightenColor(color, amount) {
        // Color manipulation utility (simplified)
        const num = parseInt(color.replace("#", ""), 16);
        const amt = Math.round(2.55 * amount * 100);
        const R = (num >> 16) + amt;
        const G = (num >> 8 & 0x00FF) + amt;
        const B = (num & 0x0000FF) + amt;
        return "#" + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
            (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
            (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
    }

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

    addTransparency(color, amount) {
        if (color.startsWith('rgba')) {
            return color.replace(/[\d.]+\)$/g, `${1 - amount})`);
        }
        const hex = color.replace('#', '');
        const alpha = Math.round((1 - amount) * 255).toString(16).padStart(2, '0');
        return `#${hex}${alpha}`;
    }

    getLuminance(color) {
        const hex = color.replace('#', '');
        const r = parseInt(hex.substr(0, 2), 16) / 255;
        const g = parseInt(hex.substr(2, 2), 16) / 255;
        const b = parseInt(hex.substr(4, 2), 16) / 255;
        return 0.299 * r + 0.587 * g + 0.114 * b;
    }

    getFallbackValue(type) {
        const fallbacks = {
            'color': '#000000',
            'dimension': '8px',
            'fontWeight': '400',
            'shadow': 'none',
            'transition': 'none',
            'filter': 'none'
        };
        return fallbacks[type] || 'inherit';
    }

    countTotalTokens(componentTokens) {
        let count = 0;
        Object.values(componentTokens).forEach(component => {
            Object.values(component).forEach(variant => {
                count += Object.keys(variant).length;
            });
        });
        return count;
    }

    generateFallbackTokens() {
        console.warn('[ComponentTokenSystem] Using fallback component tokens');
        return {
            base: {},
            responsive: {},
            states: {},
            metadata: {
                generatedAt: Date.now(),
                totalComponents: 0,
                totalTokens: 0,
                fallback: true
            }
        };
    }

    /**
     * Get performance metrics
     * @returns {Object} Performance metrics
     */
    getPerformanceMetrics() {
        return {
            ...this.performanceMetrics,
            componentDefinitionsCount: this.componentDefinitions.size,
            semanticInheritanceCount: this.semanticInheritance.size,
            responsiveRulesCount: this.responsiveRules.size
        };
    }

    /**
     * Export component tokens as CSS
     * @param {Object} tokens - Component tokens
     * @returns {string} CSS custom properties
     */
    exportAsCSS(tokens) {
        let css = '/* Component Design Tokens */\n:root {\n';

        // Export base component tokens
        Object.entries(tokens.base || {}).forEach(([componentName, componentVariants]) => {
            Object.entries(componentVariants).forEach(([variant, variantTokens]) => {
                Object.entries(variantTokens).forEach(([tokenName, token]) => {
                    if (token.cssVariable && token.value) {
                        css += `  ${token.cssVariable}: ${token.value};\n`;
                    }
                });
            });
        });

        css += '}\n\n';

        // Export responsive tokens
        if (tokens.responsive) {
            css += this.generateResponsiveCSS(tokens.responsive);
        }

        // Export state tokens
        if (tokens.states) {
            css += this.generateStateCSS(tokens.states);
        }

        return css;
    }

    generateResponsiveCSS(responsiveTokens) {
        let css = '';

        // Mobile styles
        css += '/* Mobile Component Tokens */\n@media (max-width: 768px) {\n  :root {\n';
        this.addResponsiveTokensToCSS(responsiveTokens, 'mobile', css);
        css += '  }\n}\n\n';

        // Tablet styles
        css += '/* Tablet Component Tokens */\n@media (min-width: 769px) and (max-width: 1024px) {\n  :root {\n';
        this.addResponsiveTokensToCSS(responsiveTokens, 'tablet', css);
        css += '  }\n}\n\n';

        return css;
    }

    addResponsiveTokensToCSS(responsiveTokens, breakpoint, css) {
        Object.entries(responsiveTokens).forEach(([componentName, componentVariants]) => {
            Object.entries(componentVariants).forEach(([variant, breakpointTokens]) => {
                const tokens = breakpointTokens[breakpoint];
                if (tokens) {
                    Object.entries(tokens).forEach(([tokenName, token]) => {
                        if (token.cssVariable && token.value) {
                            css += `    ${token.cssVariable}: ${token.value};\n`;
                        }
                    });
                }
            });
        });
    }

    generateStateCSS(stateTokens) {
        let css = '/* Component State Tokens */\n';

        Object.entries(stateTokens).forEach(([componentName, componentVariants]) => {
            Object.entries(componentVariants).forEach(([variant, stateVariants]) => {
                Object.entries(stateVariants).forEach(([state, stateTokensObj]) => {
                    css += `.${componentName}-${variant}:${state} {\n`;
                    Object.entries(stateTokensObj).forEach(([tokenName, token]) => {
                        if (token.cssVariable && token.value) {
                            css += `  ${token.cssVariable}: ${token.value};\n`;
                        }
                    });
                    css += '}\n\n';
                });
            });
        });

        return css;
    }
}

// Export for global use
window.ComponentTokenSystem = ComponentTokenSystem;

// Create global instance
window.componentTokenSystem = new ComponentTokenSystem();

console.log('[ComponentTokenSystem] Initialized with semantic inheritance ✅');
