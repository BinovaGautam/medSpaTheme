/**
 * Token Relationship Engine - Cross-domain Intelligence System
 * Handles automatic token relationship resolution, dependency management, and constraint validation
 *
 * @since PVC-005-DT
 * @version 1.0.0
 * @performance Optimized dependency resolution, constraint validation, relationship mapping
 */

class TokenRelationshipEngine {
    constructor() {
        this.relationships = new Map();
        this.constraints = new Map();
        this.generators = new Map();
        this.validationRules = new Map();
        this.dependencyGraph = new Map();
        this.circularDependencyCache = new Set();

        // Performance tracking
        this.performanceMetrics = {
            relationshipResolutions: 0,
            constraintValidations: 0,
            dependencyUpdates: 0,
            averageResolutionTime: 0
        };

        // Initialize the engine
        this.initialize();

        // Bind methods for performance
        this.resolveRelationships = this.resolveRelationships.bind(this);
        this.validateConstraints = this.validateConstraints.bind(this);
        this.updateDependencyGraph = this.updateDependencyGraph.bind(this);
    }

    /**
     * Initialize the relationship engine with default relationships
     */
    initialize() {
        // Set up default cross-domain relationships
        this.initializeDefaultRelationships();

        // Set up constraint validators
        this.initializeConstraintValidators();

        // Set up token generators
        this.initializeTokenGenerators();

        console.log('[TokenRelationshipEngine] Initialized with cross-domain intelligence ✅');
    }

    /**
     * Initialize default token relationships following design system principles
     */
    initializeDefaultRelationships() {
        // Color domain relationships
        this.registerRelationship('color-primary', {
            generates: [
                'color-primary-light',
                'color-primary-dark',
                'color-primary-hover',
                'button-primary-background',
                'link-color'
            ],
            affects: [
                'color-accent', // Accent should harmonize with primary
                'color-text-primary' // Text contrast must be maintained
            ],
            constraints: ['wcag-aa-contrast', 'color-harmony'],
            domain: 'color'
        });

        this.registerRelationship('color-background', {
            generates: [
                'color-surface',
                'color-card-background'
            ],
            affects: [
                'color-text-primary',
                'color-text-secondary',
                'color-border'
            ],
            constraints: ['wcag-aa-contrast'],
            domain: 'color'
        });

        // Typography domain relationships
        this.registerRelationship('typography-base-size', {
            generates: [
                'typography-heading-1-size',
                'typography-heading-2-size',
                'typography-heading-3-size',
                'typography-small-size'
            ],
            affects: [
                'spacing-line-height',
                'spacing-paragraph-margin',
                'button-padding-y'
            ],
            constraints: ['readability-minimum', 'mobile-accessibility'],
            domain: 'typography'
        });

        this.registerRelationship('typography-line-height', {
            affects: [
                'spacing-paragraph-margin',
                'button-height',
                'input-height'
            ],
            constraints: ['readability-optimal'],
            domain: 'typography'
        });

        // Spacing domain relationships
        this.registerRelationship('spacing-base-unit', {
            generates: [
                'spacing-xs',
                'spacing-sm',
                'spacing-md',
                'spacing-lg',
                'spacing-xl'
            ],
            affects: [
                'button-padding',
                'card-padding',
                'input-padding',
                'component-margin'
            ],
            constraints: ['touch-target-minimum', 'visual-rhythm'],
            domain: 'spacing'
        });

        // Component domain relationships
        this.registerRelationship('button-border-radius', {
            affects: [
                'card-border-radius',
                'input-border-radius',
                'modal-border-radius'
            ],
            constraints: ['consistent-radius-scale'],
            domain: 'component'
        });

        console.log('[TokenRelationshipEngine] Default relationships registered');
    }

    /**
     * Initialize constraint validators for different domains
     */
    initializeConstraintValidators() {
        // WCAG contrast validators
        this.registerConstraintValidator('wcag-aa-contrast', (baseToken, affectedTokens) => {
            return this.validateWCAGContrast(baseToken, affectedTokens, 4.5);
        });

        this.registerConstraintValidator('wcag-aaa-contrast', (baseToken, affectedTokens) => {
            return this.validateWCAGContrast(baseToken, affectedTokens, 7);
        });

        // Color harmony validators
        this.registerConstraintValidator('color-harmony', (baseToken, affectedTokens) => {
            return this.validateColorHarmony(baseToken, affectedTokens);
        });

        // Typography readability validators
        this.registerConstraintValidator('readability-minimum', (baseToken, affectedTokens) => {
            return this.validateReadability(baseToken, affectedTokens, 'minimum');
        });

        this.registerConstraintValidator('readability-optimal', (baseToken, affectedTokens) => {
            return this.validateReadability(baseToken, affectedTokens, 'optimal');
        });

        // Touch target validators
        this.registerConstraintValidator('touch-target-minimum', (baseToken, affectedTokens) => {
            return this.validateTouchTargets(baseToken, affectedTokens);
        });

        // Visual rhythm validators
        this.registerConstraintValidator('visual-rhythm', (baseToken, affectedTokens) => {
            return this.validateVisualRhythm(baseToken, affectedTokens);
        });

        console.log('[TokenRelationshipEngine] Constraint validators initialized');
    }

    /**
     * Initialize token generators for different relationship types
     */
    initializeTokenGenerators() {
        // Color generators
        this.registerTokenGenerator('color-primary-light', (baseValue) => {
            return this.generateLighterColor(baseValue, 0.15);
        });

        this.registerTokenGenerator('color-primary-dark', (baseValue) => {
            return this.generateDarkerColor(baseValue, 0.15);
        });

        this.registerTokenGenerator('color-primary-hover', (baseValue) => {
            return this.generateDarkerColor(baseValue, 0.08);
        });

        // Typography generators
        this.registerTokenGenerator('typography-heading-1-size', (baseValue) => {
            return this.generateTypographyScale(baseValue, 2.5);
        });

        this.registerTokenGenerator('typography-heading-2-size', (baseValue) => {
            return this.generateTypographyScale(baseValue, 2);
        });

        this.registerTokenGenerator('typography-heading-3-size', (baseValue) => {
            return this.generateTypographyScale(baseValue, 1.5);
        });

        this.registerTokenGenerator('typography-small-size', (baseValue) => {
            return this.generateTypographyScale(baseValue, 0.875);
        });

        // Spacing generators
        this.registerTokenGenerator('spacing-xs', (baseValue) => {
            return this.generateSpacingScale(baseValue, 0.25);
        });

        this.registerTokenGenerator('spacing-sm', (baseValue) => {
            return this.generateSpacingScale(baseValue, 0.5);
        });

        this.registerTokenGenerator('spacing-md', (baseValue) => {
            return this.generateSpacingScale(baseValue, 1);
        });

        this.registerTokenGenerator('spacing-lg', (baseValue) => {
            return this.generateSpacingScale(baseValue, 1.5);
        });

        this.registerTokenGenerator('spacing-xl', (baseValue) => {
            return this.generateSpacingScale(baseValue, 2);
        });

        console.log('[TokenRelationshipEngine] Token generators initialized');
    }

    /**
     * Register a token relationship
     * @param {string} tokenName - Base token name
     * @param {Object} relationship - Relationship configuration
     */
    registerRelationship(tokenName, relationship) {
        this.relationships.set(tokenName, {
            ...relationship,
            registeredAt: Date.now()
        });

        // Update dependency graph
        this.updateDependencyGraph(tokenName, relationship);
    }

    /**
     * Register a constraint validator
     * @param {string} constraintName - Constraint name
     * @param {Function} validator - Validation function
     */
    registerConstraintValidator(constraintName, validator) {
        this.validationRules.set(constraintName, validator);
    }

    /**
     * Register a token generator
     * @param {string} tokenName - Token name to generate
     * @param {Function} generator - Generation function
     */
    registerTokenGenerator(tokenName, generator) {
        this.generators.set(tokenName, generator);
    }

    /**
     * Update dependency graph for circular dependency detection
     * @param {string} tokenName - Token name
     * @param {Object} relationship - Relationship configuration
     */
    updateDependencyGraph(tokenName, relationship) {
        if (!this.dependencyGraph.has(tokenName)) {
            this.dependencyGraph.set(tokenName, new Set());
        }

        const dependencies = this.dependencyGraph.get(tokenName);

        // Add generated tokens as dependencies
        if (relationship.generates) {
            relationship.generates.forEach(generated => {
                dependencies.add(generated);
            });
        }

        // Add affected tokens as dependencies
        if (relationship.affects) {
            relationship.affects.forEach(affected => {
                dependencies.add(affected);
            });
        }
    }

    /**
     * Resolve relationships when a token value changes
     * @param {string} tokenName - Changed token name
     * @param {*} newValue - New token value
     * @returns {Array} Array of updated tokens
     */
    async resolveRelationships(tokenName, newValue) {
        const startTime = performance.now();
        const updatedTokens = [];

        try {
            // Check for circular dependencies
            if (this.hasCircularDependency(tokenName)) {
                console.warn(`[TokenRelationshipEngine] Circular dependency detected for ${tokenName}`);
                return updatedTokens;
            }

            // Get relationships for the token
            const relationship = this.relationships.get(tokenName);
            if (!relationship) {
                return updatedTokens;
            }

            // Generate dependent tokens
            if (relationship.generates) {
                for (const generatedToken of relationship.generates) {
                    const generator = this.generators.get(generatedToken);
                    if (generator) {
                        const generatedValue = generator(newValue);
                        updatedTokens.push({
                            name: generatedToken,
                            value: generatedValue,
                            cssVariable: `--${generatedToken.replace(/[A-Z]/g, letter => `-${letter.toLowerCase()}`)}`,
                            generatedFrom: tokenName
                        });
                    }
                }
            }

            // Update affected tokens based on constraints
            if (relationship.affects) {
                for (const affectedToken of relationship.affects) {
                    const adjustedValue = await this.calculateAffectedTokenValue(
                        affectedToken,
                        tokenName,
                        newValue,
                        relationship.constraints || []
                    );

                    if (adjustedValue !== null) {
                        updatedTokens.push({
                            name: affectedToken,
                            value: adjustedValue,
                            cssVariable: `--${affectedToken.replace(/[A-Z]/g, letter => `-${letter.toLowerCase()}`)}`,
                            affectedBy: tokenName
                        });
                    }
                }
            }

            // Validate constraints
            const constraintValidation = await this.validateConstraints(tokenName, newValue, updatedTokens);
            if (!constraintValidation.valid) {
                console.warn(`[TokenRelationshipEngine] Constraint validation failed for ${tokenName}:`, constraintValidation.violations);
                // Apply constraint corrections if available
                constraintValidation.corrections.forEach(correction => {
                    const existingUpdate = updatedTokens.find(token => token.name === correction.tokenName);
                    if (existingUpdate) {
                        existingUpdate.value = correction.correctedValue;
                        existingUpdate.constraintCorrected = true;
                    }
                });
            }

            // Update performance metrics
            const resolutionTime = performance.now() - startTime;
            this.updatePerformanceMetrics('resolution', resolutionTime);

            console.log(`[TokenRelationshipEngine] Resolved ${updatedTokens.length} relationships for ${tokenName} in ${resolutionTime.toFixed(2)}ms`);

            return updatedTokens;

        } catch (error) {
            console.error(`[TokenRelationshipEngine] Error resolving relationships for ${tokenName}:`, error);
            return updatedTokens;
        }
    }

    /**
     * Check for circular dependencies
     * @param {string} tokenName - Token to check
     * @param {Set} visited - Already visited tokens
     * @returns {boolean} Has circular dependency
     */
    hasCircularDependency(tokenName, visited = new Set()) {
        if (visited.has(tokenName)) {
            return true;
        }

        visited.add(tokenName);
        const dependencies = this.dependencyGraph.get(tokenName);

        if (dependencies) {
            for (const dependency of dependencies) {
                if (this.hasCircularDependency(dependency, new Set(visited))) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Calculate affected token value based on constraints
     * @param {string} affectedToken - Token being affected
     * @param {string} baseToken - Token causing the change
     * @param {*} baseValue - New base value
     * @param {Array} constraints - Applied constraints
     * @returns {*} Calculated value or null
     */
    async calculateAffectedTokenValue(affectedToken, baseToken, baseValue, constraints) {
        // Implementation depends on token types and constraints

        // Color contrast adjustments
        if (affectedToken.includes('text') && baseToken.includes('background')) {
            return this.calculateContrastingTextColor(baseValue);
        }

        // Typography line height adjustments
        if (affectedToken.includes('line-height') && baseToken.includes('size')) {
            return this.calculateOptimalLineHeight(baseValue);
        }

        // Spacing adjustments based on typography
        if (affectedToken.includes('spacing') && baseToken.includes('typography')) {
            return this.calculateTypographyBasedSpacing(baseValue);
        }

        return null;
    }

    /**
     * Validate constraints for token changes
     * @param {string} tokenName - Changed token
     * @param {*} newValue - New value
     * @param {Array} updatedTokens - All updated tokens
     * @returns {Object} Validation result
     */
    async validateConstraints(tokenName, newValue, updatedTokens) {
        const startTime = performance.now();
        const result = {
            valid: true,
            violations: [],
            corrections: []
        };

        try {
            const relationship = this.relationships.get(tokenName);
            if (!relationship || !relationship.constraints) {
                return result;
            }

            // Validate each constraint
            for (const constraintName of relationship.constraints) {
                const validator = this.validationRules.get(constraintName);
                if (validator) {
                    const validation = await validator(
                        { name: tokenName, value: newValue },
                        updatedTokens
                    );

                    if (!validation.valid) {
                        result.valid = false;
                        result.violations.push({
                            constraint: constraintName,
                            message: validation.message,
                            severity: validation.severity || 'warning'
                        });

                        // Add corrections if available
                        if (validation.corrections) {
                            result.corrections.push(...validation.corrections);
                        }
                    }
                }
            }

            // Update performance metrics
            const validationTime = performance.now() - startTime;
            this.updatePerformanceMetrics('validation', validationTime);

            return result;

        } catch (error) {
            console.error(`[TokenRelationshipEngine] Error validating constraints for ${tokenName}:`, error);
            result.valid = false;
            result.violations.push({
                constraint: 'system-error',
                message: 'Constraint validation system error',
                severity: 'error'
            });
            return result;
        }
    }

    /**
     * WCAG contrast validation
     * @param {Object} baseToken - Base token
     * @param {Array} affectedTokens - Affected tokens
     * @param {number} minimumRatio - Minimum contrast ratio
     * @returns {Object} Validation result
     */
    validateWCAGContrast(baseToken, affectedTokens, minimumRatio) {
        const result = { valid: true, message: '', corrections: [] };

        // Find text color tokens that might be affected
        const textTokens = affectedTokens.filter(token =>
            token.name.includes('text') || token.name.includes('color')
        );

        textTokens.forEach(textToken => {
            const contrastRatio = this.calculateContrastRatio(baseToken.value, textToken.value);

            if (contrastRatio < minimumRatio) {
                result.valid = false;
                result.message = `Contrast ratio ${contrastRatio.toFixed(2)} below minimum ${minimumRatio}`;

                // Suggest correction
                const correctedColor = this.findContrastingColor(baseToken.value, minimumRatio);
                result.corrections.push({
                    tokenName: textToken.name,
                    correctedValue: correctedColor,
                    reason: `Adjusted for WCAG ${minimumRatio}:1 contrast ratio`
                });
            }
        });

        return result;
    }

    /**
     * Color harmony validation
     * @param {Object} baseToken - Base token
     * @param {Array} affectedTokens - Affected tokens
     * @returns {Object} Validation result
     */
    validateColorHarmony(baseToken, affectedTokens) {
        const result = { valid: true, message: '', corrections: [] };

        // Basic color harmony validation
        // In production, this would use color theory algorithms
        const baseHue = this.getColorHue(baseToken.value);

        affectedTokens.forEach(token => {
            if (token.name.includes('color')) {
                const tokenHue = this.getColorHue(token.value);
                const hueDifference = Math.abs(baseHue - tokenHue);

                // Check if colors are harmonious (complementary, triadic, etc.)
                if (!this.isHarmoniousHueDifference(hueDifference)) {
                    result.valid = false;
                    result.message = 'Color harmony could be improved';

                    // Suggest harmonious color
                    const harmoniousColor = this.generateHarmoniousColor(baseToken.value, token.name);
                    result.corrections.push({
                        tokenName: token.name,
                        correctedValue: harmoniousColor,
                        reason: 'Adjusted for better color harmony'
                    });
                }
            }
        });

        return result;
    }

    /**
     * Readability validation for typography
     * @param {Object} baseToken - Base token
     * @param {Array} affectedTokens - Affected tokens
     * @param {string} level - Validation level (minimum/optimal)
     * @returns {Object} Validation result
     */
    validateReadability(baseToken, affectedTokens, level) {
        const result = { valid: true, message: '', corrections: [] };

        // Typography readability validation
        if (baseToken.name.includes('size')) {
            const fontSize = parseFloat(baseToken.value);
            const minimumSize = level === 'optimal' ? 16 : 14;

            if (fontSize < minimumSize) {
                result.valid = false;
                result.message = `Font size ${fontSize}px below ${level} minimum ${minimumSize}px`;

                result.corrections.push({
                    tokenName: baseToken.name,
                    correctedValue: `${minimumSize}px`,
                    reason: `Adjusted for ${level} readability`
                });
            }
        }

        return result;
    }

    /**
     * Touch target validation
     * @param {Object} baseToken - Base token
     * @param {Array} affectedTokens - Affected tokens
     * @returns {Object} Validation result
     */
    validateTouchTargets(baseToken, affectedTokens) {
        const result = { valid: true, message: '', corrections: [] };

        // Touch target size validation (minimum 44px)
        const minimumTouchTarget = 44;

        affectedTokens.forEach(token => {
            if (token.name.includes('button') || token.name.includes('input')) {
                const size = parseFloat(token.value);

                if (size < minimumTouchTarget) {
                    result.valid = false;
                    result.message = `Touch target ${size}px below minimum ${minimumTouchTarget}px`;

                    result.corrections.push({
                        tokenName: token.name,
                        correctedValue: `${minimumTouchTarget}px`,
                        reason: 'Adjusted for minimum touch target size'
                    });
                }
            }
        });

        return result;
    }

    /**
     * Visual rhythm validation
     * @param {Object} baseToken - Base token
     * @param {Array} affectedTokens - Affected tokens
     * @returns {Object} Validation result
     */
    validateVisualRhythm(baseToken, affectedTokens) {
        const result = { valid: true, message: '', corrections: [] };

        // Basic visual rhythm validation
        // Ensure spacing follows consistent mathematical relationships
        const baseValue = parseFloat(baseToken.value);

        affectedTokens.forEach(token => {
            if (token.name.includes('spacing')) {
                const tokenValue = parseFloat(token.value);
                const ratio = tokenValue / baseValue;

                // Check if ratio follows common scales (0.5, 0.75, 1, 1.5, 2, etc.)
                const commonRatios = [0.25, 0.5, 0.75, 1, 1.25, 1.5, 2, 2.5, 3];
                const closestRatio = commonRatios.reduce((prev, curr) =>
                    Math.abs(curr - ratio) < Math.abs(prev - ratio) ? curr : prev
                );

                if (Math.abs(ratio - closestRatio) > 0.1) {
                    result.corrections.push({
                        tokenName: token.name,
                        correctedValue: `${Math.round(baseValue * closestRatio)}px`,
                        reason: 'Adjusted for consistent visual rhythm'
                    });
                }
            }
        });

        return result;
    }

    // Utility functions for color manipulation and calculations

    generateLighterColor(color, amount) {
        const num = parseInt(color.replace("#", ""), 16);
        const amt = Math.round(2.55 * amount * 100);
        const R = (num >> 16) + amt;
        const G = (num >> 8 & 0x00FF) + amt;
        const B = (num & 0x0000FF) + amt;
        return "#" + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
            (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
            (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
    }

    generateDarkerColor(color, amount) {
        const num = parseInt(color.replace("#", ""), 16);
        const amt = Math.round(2.55 * amount * 100);
        const R = (num >> 16) - amt;
        const G = (num >> 8 & 0x00FF) - amt;
        const B = (num & 0x0000FF) - amt;
        return "#" + (0x1000000 + (R > 255 ? 255 : R < 0 ? 0 : R) * 0x10000 +
            (G > 255 ? 255 : G < 0 ? 0 : G) * 0x100 +
            (B > 255 ? 255 : B < 0 ? 0 : B)).toString(16).slice(1);
    }

    generateTypographyScale(baseSize, multiplier) {
        const size = parseFloat(baseSize);
        const unit = baseSize.replace(/[\d.]/g, '');
        return `${Math.round(size * multiplier * 100) / 100}${unit}`;
    }

    generateSpacingScale(baseSpacing, multiplier) {
        const size = parseFloat(baseSpacing);
        const unit = baseSpacing.replace(/[\d.]/g, '');
        return `${Math.round(size * multiplier * 100) / 100}${unit}`;
    }

    calculateContrastRatio(color1, color2) {
        // Simplified contrast ratio calculation
        // In production, use a proper color contrast library
        const luminance1 = this.getLuminance(color1);
        const luminance2 = this.getLuminance(color2);

        const lighter = Math.max(luminance1, luminance2);
        const darker = Math.min(luminance1, luminance2);

        return (lighter + 0.05) / (darker + 0.05);
    }

    getLuminance(color) {
        // Simplified luminance calculation
        const hex = color.replace('#', '');
        const r = parseInt(hex.substr(0, 2), 16) / 255;
        const g = parseInt(hex.substr(2, 2), 16) / 255;
        const b = parseInt(hex.substr(4, 2), 16) / 255;

        return 0.299 * r + 0.587 * g + 0.114 * b;
    }

    getColorHue(color) {
        // Simplified hue calculation
        // In production, convert to HSL properly
        const hex = color.replace('#', '');
        const r = parseInt(hex.substr(0, 2), 16) / 255;
        const g = parseInt(hex.substr(2, 2), 16) / 255;
        const b = parseInt(hex.substr(4, 2), 16) / 255;

        const max = Math.max(r, g, b);
        const min = Math.min(r, g, b);
        const diff = max - min;

        if (diff === 0) return 0;

        let hue;
        if (max === r) hue = ((g - b) / diff) % 6;
        else if (max === g) hue = (b - r) / diff + 2;
        else hue = (r - g) / diff + 4;

        return Math.round(hue * 60);
    }

    isHarmoniousHueDifference(difference) {
        // Check for harmonious color relationships
        const harmoniousAngles = [0, 30, 60, 90, 120, 150, 180, 210, 240, 270, 300, 330];
        return harmoniousAngles.some(angle => Math.abs(difference - angle) < 15);
    }

    generateHarmoniousColor(baseColor, tokenName) {
        // Generate harmonious color based on token purpose
        const baseHue = this.getColorHue(baseColor);
        let targetHue;

        if (tokenName.includes('accent')) {
            targetHue = (baseHue + 180) % 360; // Complementary
        } else if (tokenName.includes('secondary')) {
            targetHue = (baseHue + 120) % 360; // Triadic
        } else {
            targetHue = (baseHue + 30) % 360; // Analogous
        }

        // Convert back to hex (simplified)
        return this.hueToHex(targetHue);
    }

    hueToHex(hue) {
        // Simplified hue to hex conversion
        // In production, use proper HSL to RGB conversion
        const normalizedHue = hue / 360;
        const r = Math.round(255 * Math.abs(normalizedHue * 6 - 3) - 1);
        const g = Math.round(255 * (2 - Math.abs(normalizedHue * 6 - 2)));
        const b = Math.round(255 * (2 - Math.abs(normalizedHue * 6 - 4)));

        return `#${r.toString(16).padStart(2, '0')}${g.toString(16).padStart(2, '0')}${b.toString(16).padStart(2, '0')}`;
    }

    calculateContrastingTextColor(backgroundColor) {
        const luminance = this.getLuminance(backgroundColor);
        return luminance > 0.5 ? '#000000' : '#ffffff';
    }

    calculateOptimalLineHeight(fontSize) {
        const size = parseFloat(fontSize);
        // Optimal line height is typically 1.4-1.6 times font size
        const optimal = size * 1.5;
        return `${optimal}px`;
    }

    calculateTypographyBasedSpacing(fontSize) {
        const size = parseFloat(fontSize);
        // Base spacing on typography size
        return `${Math.round(size * 0.75)}px`;
    }

    findContrastingColor(backgroundColor, minimumRatio) {
        // Find a color that meets the minimum contrast ratio
        const bgLuminance = this.getLuminance(backgroundColor);

        if (bgLuminance > 0.5) {
            // Light background, return dark text
            return '#000000';
        } else {
            // Dark background, return light text
            return '#ffffff';
        }
    }

    /**
     * Update performance metrics
     * @param {string} operation - Operation type
     * @param {number} time - Time taken
     */
    updatePerformanceMetrics(operation, time) {
        switch (operation) {
            case 'resolution':
                this.performanceMetrics.relationshipResolutions++;
                break;
            case 'validation':
                this.performanceMetrics.constraintValidations++;
                break;
            case 'dependency':
                this.performanceMetrics.dependencyUpdates++;
                break;
        }

        // Update average resolution time
        const totalOps = this.performanceMetrics.relationshipResolutions +
                         this.performanceMetrics.constraintValidations +
                         this.performanceMetrics.dependencyUpdates;

        this.performanceMetrics.averageResolutionTime =
            ((this.performanceMetrics.averageResolutionTime * (totalOps - 1)) + time) / totalOps;
    }

    /**
     * Get performance metrics
     * @returns {Object} Performance metrics
     */
    getPerformanceMetrics() {
        return {
            ...this.performanceMetrics,
            totalRelationships: this.relationships.size,
            totalConstraints: this.validationRules.size,
            totalGenerators: this.generators.size,
            circularDependencies: this.circularDependencyCache.size
        };
    }

    /**
     * Export relationship configuration
     * @returns {Object} Relationship data
     */
    exportRelationships() {
        return {
            relationships: Array.from(this.relationships.entries()),
            constraints: Array.from(this.validationRules.entries()),
            generators: Array.from(this.generators.entries()),
            dependencyGraph: Array.from(this.dependencyGraph.entries()),
            metadata: {
                exportedAt: Date.now(),
                version: '1.0.0'
            }
        };
    }
}

// Export for global use
window.TokenRelationshipEngine = TokenRelationshipEngine;

// Create global instance
window.tokenRelationshipEngine = new TokenRelationshipEngine();

console.log('[TokenRelationshipEngine] Initialized with cross-domain intelligence ✅');
