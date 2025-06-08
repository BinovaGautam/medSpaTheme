/**
 * Spacing Domain Generator - Intelligent Spacing Token Generation
 * Handles spacing coordination with typography, geometric/arithmetic scales, and component inheritance
 *
 * @since PVC-006-DT
 * @version 1.0.0
 * @performance Optimized spacing generation with component coordination
 */

class SpacingDomainGenerator {
    constructor() {
        this.spacingTokens = new Map();
        this.scaleTypes = new Map();
        this.componentCoordination = new Map();

        // Performance tracking
        this.performanceMetrics = {
            generationTime: 0,
            componentCoordinations: 0,
            scaleCalculations: 0
        };

        // Initialize spacing scales and coordination rules
        this.initializeSpacingScales();
        this.initializeComponentCoordination();

        // Bind methods for performance
        this.generateFromBase = this.generateFromBase.bind(this);
        this.coordinateWithComponents = this.coordinateWithComponents.bind(this);
        this.coordinateWithTypography = this.coordinateWithTypography.bind(this);
    }

    /**
     * Initialize spacing scale configurations
     */
    initializeSpacingScales() {
        // Geometric progressions (multiplicative)
        this.scaleTypes.set('geometric-minor', {
            name: 'Geometric Minor',
            description: 'Subtle geometric progression (1.125)',
            ratio: 1.125,
            multipliers: [0.25, 0.5, 0.75, 1, 1.125, 1.266, 1.424, 1.602, 1.802, 2.027],
            personality: 'subtle, harmonious'
        });

        this.scaleTypes.set('geometric-major', {
            name: 'Geometric Major',
            description: 'Standard geometric progression (1.25)',
            ratio: 1.25,
            multipliers: [0.25, 0.5, 0.75, 1, 1.25, 1.563, 1.953, 2.441, 3.052, 3.815],
            personality: 'balanced, professional'
        });

        this.scaleTypes.set('geometric-golden', {
            name: 'Golden Ratio',
            description: 'Golden ratio progression (1.618)',
            ratio: 1.618,
            multipliers: [0.25, 0.5, 0.75, 1, 1.618, 2.618, 4.236, 6.854, 11.09, 17.944],
            personality: 'dynamic, luxurious'
        });

        // Arithmetic progressions (additive)
        this.scaleTypes.set('arithmetic-4px', {
            name: 'Arithmetic 4px',
            description: 'Linear progression in 4px increments',
            baseIncrement: 4,
            multipliers: [0.25, 0.5, 0.75, 1, 1.25, 1.5, 2, 2.5, 3, 4],
            personality: 'precise, systematic'
        });

        this.scaleTypes.set('arithmetic-8px', {
            name: 'Arithmetic 8px',
            description: 'Linear progression in 8px increments',
            baseIncrement: 8,
            multipliers: [0.25, 0.5, 0.75, 1, 1.125, 1.25, 1.5, 2, 2.5, 3],
            personality: 'clean, grid-aligned'
        });

        // Medical spa optimized scales
        this.scaleTypes.set('medical-professional', {
            name: 'Medical Professional',
            description: 'Optimized for medical spa layouts',
            ratio: 1.2,
            multipliers: [0.25, 0.5, 0.75, 1, 1.2, 1.44, 1.728, 2.074, 2.488, 2.986],
            personality: 'professional, trustworthy'
        });

        this.scaleTypes.set('spa-luxurious', {
            name: 'Spa Luxurious',
            description: 'Generous spacing for relaxed, luxury feel',
            ratio: 1.5,
            multipliers: [0.25, 0.5, 0.75, 1, 1.5, 2.25, 3.375, 5.063, 7.594, 11.391],
            personality: 'spacious, luxurious'
        });

        console.log('[SpacingDomainGenerator] Spacing scales initialized');
    }

    /**
     * Initialize component coordination rules
     */
    initializeComponentCoordination() {
        // Button spacing coordination
        this.componentCoordination.set('button', {
            paddingRatio: { x: 2, y: 1 }, // Horizontal padding 2x vertical
            minTouchTarget: 44, // Minimum 44px touch target
            borderRadiusRatio: 0.25, // Border radius relative to padding
            marginRatio: 0.5 // Margin relative to padding
        });

        // Card spacing coordination
        this.componentCoordination.set('card', {
            paddingRatio: { x: 1, y: 1 }, // Equal padding
            marginRatio: 1, // Margin equal to padding
            gapRatio: 0.75, // Internal gaps 75% of padding
            borderRadiusRatio: 0.5 // Border radius relative to padding
        });

        // Form element spacing
        this.componentCoordination.set('input', {
            paddingRatio: { x: 1, y: 0.75 }, // Slightly more horizontal padding
            marginRatio: 0.5, // Margin between form elements
            minHeight: 40, // Minimum input height
            borderRadiusRatio: 0.25
        });

        // Navigation spacing
        this.componentCoordination.set('navigation', {
            itemPaddingRatio: { x: 1.5, y: 1 },
            itemMarginRatio: 0.25,
            dropdownPaddingRatio: 1,
            verticalGapRatio: 0.5
        });

        // Grid and layout spacing
        this.componentCoordination.set('layout', {
            containerPaddingRatio: 2, // Container padding relative to base
            gridGapRatio: 1, // Grid gap equal to base spacing
            sectionMarginRatio: 3, // Section margins 3x base spacing
            responsiveScaling: { mobile: 0.75, tablet: 0.875, desktop: 1 }
        });

        console.log('[SpacingDomainGenerator] Component coordination rules initialized');
    }

    /**
     * Generate spacing tokens from base spacing and scale type
     * @param {number|string} baseSpacing - Base spacing value (number or with unit)
     * @param {string} scaleType - Scale type identifier
     * @returns {Object} Generated spacing tokens
     */
    generateFromBase(baseSpacing, scaleType = 'geometric-major') {
        const startTime = performance.now();

        try {
            // Parse base spacing
            const baseValue = this.parseSpacingValue(baseSpacing);
            const baseUnit = this.extractUnit(baseSpacing);

            // Get scale configuration
            const scale = this.scaleTypes.get(scaleType);
            if (!scale) {
                throw new Error(`Unknown scale type: ${scaleType}`);
            }

            // Generate spacing tokens
            const spacingTokens = this.generateScaleTokens(baseValue, baseUnit, scale);

            // Add semantic spacing roles
            const semanticTokens = this.generateSemanticSpacingTokens(spacingTokens);

            // Generate responsive variations
            const responsiveTokens = this.generateResponsiveSpacingTokens(spacingTokens);

            // Coordinate with components
            const componentTokens = this.coordinateWithComponents(spacingTokens);

            // Combine all tokens
            const allTokens = {
                base: spacingTokens,
                semantic: semanticTokens,
                responsive: responsiveTokens,
                components: componentTokens,
                metadata: {
                    generatedAt: Date.now(),
                    baseSpacing: baseSpacing,
                    scaleType: scaleType,
                    scaleRatio: scale.ratio || 'arithmetic',
                    totalTokens: Object.keys(spacingTokens).length
                }
            };

            // Update performance metrics
            this.performanceMetrics.generationTime = performance.now() - startTime;
            this.performanceMetrics.scaleCalculations++;

            console.log(`[SpacingDomainGenerator] Generated ${allTokens.metadata.totalTokens} spacing tokens in ${this.performanceMetrics.generationTime.toFixed(2)}ms`);

            return allTokens;

        } catch (error) {
            console.error('[SpacingDomainGenerator] Error generating spacing:', error);
            return this.generateFallbackSpacing();
        }
    }

    /**
     * Generate scale-based spacing tokens
     * @param {number} baseValue - Base spacing value
     * @param {string} baseUnit - Unit (px, rem, em)
     * @param {Object} scale - Scale configuration
     * @returns {Object} Spacing tokens
     */
    generateScaleTokens(baseValue, baseUnit, scale) {
        const tokens = {};

        // Standard spacing scale (xs, sm, md, lg, xl, etc.)
        const spacingNames = ['3xs', '2xs', 'xs', 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl'];

        scale.multipliers.forEach((multiplier, index) => {
            const spacingName = spacingNames[index] || `scale-${index}`;
            let scaledValue;

            if (scale.ratio) {
                // Geometric progression
                scaledValue = baseValue * multiplier;
            } else if (scale.baseIncrement) {
                // Arithmetic progression
                scaledValue = baseValue + (scale.baseIncrement * (multiplier - 1));
            } else {
                // Default to direct multiplication
                scaledValue = baseValue * multiplier;
            }

            tokens[spacingName] = {
                value: `${Math.round(scaledValue * 100) / 100}${baseUnit}`,
                cssVariable: `--spacing-${spacingName}`,
                multiplier: multiplier,
                description: `Spacing scale ${spacingName}`,
                index: index
            };
        });

        return tokens;
    }

    /**
     * Generate semantic spacing tokens for common use cases
     * @param {Object} spacingTokens - Base spacing tokens
     * @returns {Object} Semantic spacing tokens
     */
    generateSemanticSpacingTokens(spacingTokens) {
        const semanticTokens = {};

        // Map semantic names to scale positions
        const semanticMappings = {
            'button-padding-y': 'sm',
            'button-padding-x': 'md',
            'button-margin': 'sm',
            'card-padding': 'lg',
            'card-margin': 'lg',
            'card-gap': 'md',
            'input-padding-y': 'sm',
            'input-padding-x': 'md',
            'input-margin': 'sm',
            'section-padding': 'xl',
            'section-margin': '2xl',
            'container-padding': 'lg',
            'grid-gap': 'md',
            'text-margin': 'sm',
            'component-gap': 'md'
        };

        Object.entries(semanticMappings).forEach(([semanticName, scaleName]) => {
            const baseToken = spacingTokens[scaleName];
            if (baseToken) {
                semanticTokens[semanticName] = {
                    ...baseToken,
                    cssVariable: `--spacing-${semanticName}`,
                    description: `Semantic spacing for ${semanticName.replace(/-/g, ' ')}`,
                    semanticRole: semanticName
                };
            }
        });

        return semanticTokens;
    }

    /**
     * Generate responsive spacing tokens
     * @param {Object} spacingTokens - Base spacing tokens
     * @returns {Object} Responsive spacing tokens
     */
    generateResponsiveSpacingTokens(spacingTokens) {
        const responsiveTokens = {};

        // Responsive scaling factors
        const responsiveScales = {
            mobile: 0.75,
            tablet: 0.875,
            desktop: 1
        };

        Object.entries(spacingTokens).forEach(([spacingName, token]) => {
            const baseValue = this.parseSpacingValue(token.value);
            const unit = this.extractUnit(token.value);

            responsiveTokens[spacingName] = {
                ...token,
                responsive: {}
            };

            Object.entries(responsiveScales).forEach(([breakpoint, scale]) => {
                const scaledValue = baseValue * scale;
                responsiveTokens[spacingName].responsive[breakpoint] = {
                    value: `${Math.round(scaledValue * 100) / 100}${unit}`,
                    scale: scale
                };
            });
        });

        return responsiveTokens;
    }

    /**
     * Coordinate spacing with component requirements
     * @param {Object} spacingTokens - Base spacing tokens
     * @returns {Object} Component-coordinated spacing tokens
     */
    coordinateWithComponents(spacingTokens) {
        const componentTokens = {};

        this.componentCoordination.forEach((coordination, componentName) => {
            componentTokens[componentName] = {};

            // Find appropriate base spacing (usually 'md')
            const baseSpacing = spacingTokens['md'] || spacingTokens['sm'] || Object.values(spacingTokens)[0];
            if (!baseSpacing) return;

            const baseValue = this.parseSpacingValue(baseSpacing.value);
            const unit = this.extractUnit(baseSpacing.value);

            // Generate component-specific spacing
            Object.entries(coordination).forEach(([property, config]) => {
                if (property === 'paddingRatio' && typeof config === 'object') {
                    componentTokens[componentName]['padding-x'] = {
                        value: `${Math.round(baseValue * config.x * 100) / 100}${unit}`,
                        cssVariable: `--${componentName}-padding-x`,
                        description: `${componentName} horizontal padding`
                    };
                    componentTokens[componentName]['padding-y'] = {
                        value: `${Math.round(baseValue * config.y * 100) / 100}${unit}`,
                        cssVariable: `--${componentName}-padding-y`,
                        description: `${componentName} vertical padding`
                    };
                } else if (typeof config === 'number') {
                    componentTokens[componentName][property] = {
                        value: `${Math.round(baseValue * config * 100) / 100}${unit}`,
                        cssVariable: `--${componentName}-${property.replace(/([A-Z])/g, '-$1').toLowerCase()}`,
                        description: `${componentName} ${property.replace(/([A-Z])/g, ' $1').toLowerCase()}`
                    };
                }
            });

            // Accessibility compliance checks
            this.validateComponentAccessibility(componentName, componentTokens[componentName]);
        });

        this.performanceMetrics.componentCoordinations++;
        return componentTokens;
    }

    /**
     * Coordinate spacing with typography for optimal readability
     * @param {Object} spacingTokens - Spacing tokens
     * @param {Object} typographyTokens - Typography tokens
     * @returns {Object} Typography-coordinated spacing tokens
     */
    coordinateWithTypography(spacingTokens, typographyTokens) {
        if (!typographyTokens) return spacingTokens;

        const coordinatedTokens = { ...spacingTokens };

        // Extract base font size from typography
        const baseFontSize = this.extractBaseFontSize(typographyTokens);

        if (baseFontSize) {
            // Adjust spacing based on typography scale
            const typographyRatio = baseFontSize / 16; // Relative to 16px base

            Object.entries(coordinatedTokens).forEach(([spacingName, token]) => {
                const baseValue = this.parseSpacingValue(token.value);
                const unit = this.extractUnit(token.value);

                // Apply typography-based scaling
                const adjustedValue = baseValue * Math.sqrt(typographyRatio); // Square root for subtle adjustment

                coordinatedTokens[spacingName] = {
                    ...token,
                    value: `${Math.round(adjustedValue * 100) / 100}${unit}`,
                    typographyCoordinated: true,
                    originalValue: token.value
                };
            });
        }

        return coordinatedTokens;
    }

    /**
     * Validate component accessibility requirements
     * @param {string} componentName - Component name
     * @param {Object} componentSpacing - Component spacing tokens
     */
    validateComponentAccessibility(componentName, componentSpacing) {
        // Minimum touch target validation
        if (componentName === 'button' && componentSpacing['padding-y'] && componentSpacing['padding-x']) {
            const paddingY = this.parseSpacingValue(componentSpacing['padding-y'].value);
            const paddingX = this.parseSpacingValue(componentSpacing['padding-x'].value);

            // Estimate minimum button size (assuming 16px base font)
            const estimatedHeight = 16 + (paddingY * 2);
            const estimatedWidth = 60 + (paddingX * 2); // Minimum text width

            if (estimatedHeight < 44) {
                console.warn(`[SpacingDomainGenerator] Button touch target height (${estimatedHeight}px) below WCAG minimum (44px)`);
            }

            if (estimatedWidth < 44) {
                console.warn(`[SpacingDomainGenerator] Button touch target width (${estimatedWidth}px) below WCAG minimum (44px)`);
            }
        }
    }

    /**
     * Parse spacing value (with or without unit)
     * @param {number|string} value - Spacing value
     * @returns {number} Numeric value
     */
    parseSpacingValue(value) {
        if (typeof value === 'number') return value;
        return parseFloat(value) || 16; // Default to 16 if parsing fails
    }

    /**
     * Extract unit from spacing value
     * @param {number|string} value - Spacing value
     * @returns {string} Unit (px, rem, em)
     */
    extractUnit(value) {
        if (typeof value === 'number') return 'px';
        const match = String(value).match(/[a-z%]+$/i);
        return match ? match[0] : 'px';
    }

    /**
     * Extract base font size from typography tokens
     * @param {Object} typographyTokens - Typography tokens
     * @returns {number|null} Base font size in px
     */
    extractBaseFontSize(typographyTokens) {
        // Look for body font size
        const bodyToken = typographyTokens.body || typographyTokens['body-text'] || typographyTokens.base;

        if (bodyToken && bodyToken.fontSize) {
            return this.parseSpacingValue(bodyToken.fontSize);
        }

        return null;
    }

    /**
     * Generate fallback spacing when configuration fails
     * @returns {Object} Fallback spacing tokens
     */
    generateFallbackSpacing() {
        console.warn('[SpacingDomainGenerator] Using fallback spacing');

        return this.generateFromBase(16, 'geometric-major');
    }

    /**
     * Export spacing tokens as CSS
     * @param {Object} tokens - Spacing tokens
     * @returns {string} CSS custom properties
     */
    exportAsCSS(tokens) {
        let css = ':root {\n';

        // Export base spacing tokens
        if (tokens.base) {
            Object.entries(tokens.base).forEach(([spacingName, token]) => {
                css += `  ${token.cssVariable}: ${token.value};\n`;
            });
        }

        // Export semantic spacing tokens
        if (tokens.semantic) {
            Object.entries(tokens.semantic).forEach(([spacingName, token]) => {
                css += `  ${token.cssVariable}: ${token.value};\n`;
            });
        }

        // Export component spacing tokens
        if (tokens.components) {
            Object.entries(tokens.components).forEach(([componentName, componentTokens]) => {
                Object.entries(componentTokens).forEach(([property, token]) => {
                    css += `  ${token.cssVariable}: ${token.value};\n`;
                });
            });
        }

        css += '}\n';

        // Add responsive spacing
        if (tokens.responsive) {
            css += this.generateResponsiveCSS(tokens.responsive);
        }

        return css;
    }

    /**
     * Generate responsive CSS media queries
     * @param {Object} responsiveTokens - Responsive spacing tokens
     * @returns {string} Responsive CSS
     */
    generateResponsiveCSS(responsiveTokens) {
        let css = '';

        // Mobile styles
        css += '\n@media (max-width: 768px) {\n  :root {\n';
        Object.entries(responsiveTokens).forEach(([spacingName, token]) => {
            if (token.responsive && token.responsive.mobile) {
                css += `    ${token.cssVariable}: ${token.responsive.mobile.value};\n`;
            }
        });
        css += '  }\n}\n';

        // Tablet styles
        css += '\n@media (min-width: 769px) and (max-width: 1024px) {\n  :root {\n';
        Object.entries(responsiveTokens).forEach(([spacingName, token]) => {
            if (token.responsive && token.responsive.tablet) {
                css += `    ${token.cssVariable}: ${token.responsive.tablet.value};\n`;
            }
        });
        css += '  }\n}\n';

        return css;
    }

    /**
     * Get performance metrics
     * @returns {Object} Performance metrics
     */
    getPerformanceMetrics() {
        return {
            ...this.performanceMetrics,
            scaleTypesCount: this.scaleTypes.size,
            componentCoordinationRules: this.componentCoordination.size
        };
    }
}

// Export for global use
window.SpacingDomainGenerator = SpacingDomainGenerator;

// Create global instance
window.spacingDomainGenerator = new SpacingDomainGenerator();

console.log('[SpacingDomainGenerator] Initialized with intelligent spacing generation ✅');
