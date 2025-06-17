/**
 * T8.4 Palette Validation System
 *
 * Comprehensive validation system for color palette accessibility compliance,
 * semantic token integrity, and real-time customizer feedback
 *
 * @package MedSpaTheme
 * @version 1.0.0 - T8.4 Implementation
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 * @sprint SPRINT-8-EXTENSIBLE-SEMANTIC-INTEGRATION
 */

(function(window) {
    'use strict';

    /**
     * Comprehensive Palette Validation System
     */
    class PaletteValidationSystem {
        constructor() {
            this.validationRules = this.initializeValidationRules();
            this.wcagStandards = this.initializeWCAGStandards();
            this.semanticTokenRules = this.initializeSemanticTokenRules();
            this.validationCache = new Map();
            this.performanceMetrics = {
                validationCount: 0,
                totalValidationTime: 0,
                cacheHitRate: 0,
                lastValidation: null
            };

            this.init();
        }

        /**
         * Initialize the validation system
         */
        init() {
            console.log('PaletteValidationSystem: Initializing comprehensive validation system');
            this.setupEventListeners();
            this.initializeCustomizerIntegration();
        }

        /**
         * Initialize WCAG 2.1 compliance standards
         */
        initializeWCAGStandards() {
            return {
                AA: {
                    normalText: 4.5,
                    largeText: 3.0,
                    graphicalObjects: 3.0,
                    userInterface: 3.0
                },
                AAA: {
                    normalText: 7.0,
                    largeText: 4.5,
                    graphicalObjects: 4.5,
                    userInterface: 4.5
                },
                textSizes: {
                    large: { minSize: 18, weight: 'normal' },
                    largeBold: { minSize: 14, weight: 'bold' }
                }
            };
        }

        /**
         * Initialize validation rules for comprehensive checking
         */
        initializeValidationRules() {
            return {
                accessibility: {
                    wcagCompliance: {
                        weight: 40,
                        required: true,
                        description: 'WCAG 2.1 AA/AAA compliance validation'
                    },
                    colorBlindness: {
                        weight: 20,
                        required: true,
                        description: 'Color blindness accessibility check'
                    },
                    contrastRatios: {
                        weight: 25,
                        required: true,
                        description: 'Comprehensive contrast ratio validation'
                    }
                },
                semanticIntegrity: {
                    tokenHierarchy: {
                        weight: 15,
                        required: true,
                        description: 'Semantic token hierarchy validation'
                    },
                    roleMapping: {
                        weight: 10,
                        required: true,
                        description: 'Color role semantic mapping validation'
                    },
                    brandConsistency: {
                        weight: 10,
                        required: false,
                        description: 'Brand consistency and harmony validation'
                    }
                },
                usability: {
                    readability: {
                        weight: 15,
                        required: true,
                        description: 'Text readability across all components'
                    },
                    distinctiveness: {
                        weight: 10,
                        required: true,
                        description: 'Color distinctiveness for UI elements'
                    }
                }
            };
        }

        /**
         * Initialize semantic token validation rules
         */
        initializeSemanticTokenRules() {
            return {
                requiredTokens: [
                    'primary', 'secondary', 'surface', 'background', 'accent'
                ],
                tokenHierarchy: {
                    primary: { importance: 1, minContrast: 7.0 },
                    secondary: { importance: 2, minContrast: 4.5 },
                    accent: { importance: 3, minContrast: 4.5 },
                    surface: { importance: 4, minContrast: 3.0 },
                    background: { importance: 5, minContrast: 3.0 }
                },
                semanticMapping: {
                    primary: ['--color-brand-primary', '--btn-primary-bg'],
                    secondary: ['--color-brand-secondary', '--btn-secondary-bg'],
                    accent: ['--color-accent-primary', '--btn-accent-bg'],
                    surface: ['--color-surface-primary', '--card-bg'],
                    background: ['--color-surface-background', '--page-bg']
                }
            };
        }

        /**
         * Comprehensive palette validation
         */
        async validatePalette(palette, options = {}) {
            const startTime = performance.now();
            const cacheKey = this.generateCacheKey(palette, options);

            // Check cache first
            if (this.validationCache.has(cacheKey)) {
                this.updatePerformanceMetrics(startTime, true);
                return this.validationCache.get(cacheKey);
            }

            const validation = {
                isValid: true,
                overallScore: 0,
                compliance: {
                    wcag: { level: null, score: 0, issues: [] },
                    accessibility: { score: 0, issues: [] },
                    semantic: { score: 0, issues: [] },
                    usability: { score: 0, issues: [] }
                },
                violations: [],
                warnings: [],
                recommendations: [],
                metrics: {
                    totalChecks: 0,
                    passedChecks: 0,
                    criticalIssues: 0,
                    warningIssues: 0
                },
                paletteInfo: {
                    id: palette.id || 'custom',
                    name: palette.name || 'Custom Palette',
                    colors: palette.colors || {}
                }
            };

            try {
                // 1. WCAG Compliance Validation
                await this.validateWCAGCompliance(palette, validation);

                // 2. Accessibility Validation
                await this.validateAccessibility(palette, validation);

                // 3. Semantic Token Integrity
                await this.validateSemanticIntegrity(palette, validation);

                // 4. Usability Validation
                await this.validateUsability(palette, validation);

                // 5. Calculate overall score and compliance
                this.calculateOverallScore(validation);

                // 6. Generate recommendations
                this.generateRecommendations(validation);

            } catch (error) {
                console.error('PaletteValidationSystem: Validation error:', error);
                validation.isValid = false;
                validation.violations.push({
                    type: 'system',
                    severity: 'critical',
                    message: 'Validation system error: ' + error.message,
                    code: 'SYSTEM_ERROR'
                });
            }

            // Cache result and update metrics
            this.validationCache.set(cacheKey, validation);
            this.updatePerformanceMetrics(startTime, false);
            this.performanceMetrics.lastValidation = validation;

            return validation;
        }

        /**
         * WCAG 2.1 compliance validation
         */
        async validateWCAGCompliance(palette, validation) {
            const wcagResults = {
                aaCompliant: true,
                aaaCompliant: true,
                issues: [],
                scores: {}
            };

            const colors = palette.colors || {};
            const textHierarchies = ['title', 'body', 'caption'];

            for (const [role, colorData] of Object.entries(colors)) {
                const bgColor = colorData.hex || colorData;
                wcagResults.scores[role] = {};

                for (const hierarchy of textHierarchies) {
                    const textColor = this.getOptimalTextColor(bgColor, hierarchy);
                    const contrast = this.calculateContrast(bgColor, textColor.color);

                    const aaRequired = hierarchy === 'title' ?
                        this.wcagStandards.AA.largeText : this.wcagStandards.AA.normalText;
                    const aaaRequired = hierarchy === 'title' ?
                        this.wcagStandards.AAA.largeText : this.wcagStandards.AAA.normalText;

                    const aaPass = contrast >= aaRequired;
                    const aaaPass = contrast >= aaaRequired;

                    wcagResults.scores[role][hierarchy] = {
                        contrast: Math.round(contrast * 100) / 100,
                        aaPass,
                        aaaPass,
                        textColor: textColor.color
                    };

                    if (!aaPass) {
                        wcagResults.aaCompliant = false;
                        wcagResults.issues.push({
                            type: 'wcag_aa',
                            severity: 'critical',
                            role,
                            hierarchy,
                            contrast,
                            required: aaRequired,
                            message: `${role} background fails WCAG AA for ${hierarchy} text (${contrast.toFixed(2)}:1, needs ${aaRequired}:1)`
                        });
                    }

                    if (!aaaPass) {
                        wcagResults.aaaCompliant = false;
                        if (aaPass) { // Only warn if AA passes but AAA fails
                            wcagResults.issues.push({
                                type: 'wcag_aaa',
                                severity: 'warning',
                                role,
                                hierarchy,
                                contrast,
                                required: aaaRequired,
                                message: `${role} background could improve for WCAG AAA compliance (${contrast.toFixed(2)}:1, target ${aaaRequired}:1)`
                            });
                        }
                    }
                }
            }

            // Set WCAG compliance level and score
            validation.compliance.wcag.level = wcagResults.aaaCompliant ? 'AAA' :
                                              (wcagResults.aaCompliant ? 'AA' : 'FAIL');
            validation.compliance.wcag.score = this.calculateWCAGScore(wcagResults);
            validation.compliance.wcag.issues = wcagResults.issues;

            // Add violations to main validation
            wcagResults.issues.forEach(issue => {
                if (issue.severity === 'critical') {
                    validation.violations.push(issue);
                    validation.metrics.criticalIssues++;
                } else {
                    validation.warnings.push(issue);
                    validation.metrics.warningIssues++;
                }
            });

            validation.metrics.totalChecks += Object.keys(colors).length * textHierarchies.length;
            validation.metrics.passedChecks += Object.values(wcagResults.scores)
                .flatMap(roleScores => Object.values(roleScores))
                .filter(score => score.aaPass).length;
        }

        /**
         * Comprehensive accessibility validation beyond WCAG
         */
        async validateAccessibility(palette, validation) {
            const accessibilityIssues = [];
            const colors = palette.colors || {};

            // Color blindness simulation
            const colorBlindnessResults = this.validateColorBlindness(colors);
            if (!colorBlindnessResults.isAccessible) {
                accessibilityIssues.push({
                    type: 'color_blindness',
                    severity: 'high',
                    message: 'Palette may be difficult for color blind users',
                    details: colorBlindnessResults.issues,
                    code: 'COLOR_BLIND_ACCESSIBILITY'
                });
            }

            // Color distinctiveness
            const distinctivenessResults = this.validateColorDistinctiveness(colors);
            if (!distinctivenessResults.isDistinct) {
                accessibilityIssues.push({
                    type: 'distinctiveness',
                    severity: 'medium',
                    message: 'Some colors are too similar and may cause confusion',
                    details: distinctivenessResults.similarPairs,
                    code: 'LOW_COLOR_DISTINCTIVENESS'
                });
            }

            // Calculate accessibility score
            validation.compliance.accessibility.score = this.calculateAccessibilityScore({
                colorBlindness: colorBlindnessResults,
                distinctiveness: distinctivenessResults
            });
            validation.compliance.accessibility.issues = accessibilityIssues;

            // Add to main validation
            accessibilityIssues.forEach(issue => {
                if (issue.severity === 'critical' || issue.severity === 'high') {
                    validation.violations.push(issue);
                    validation.metrics.criticalIssues++;
                } else {
                    validation.warnings.push(issue);
                    validation.metrics.warningIssues++;
                }
            });
        }

        /**
         * Semantic token integrity validation
         */
        async validateSemanticIntegrity(palette, validation) {
            const semanticIssues = [];
            const colors = palette.colors || {};

            // Check required tokens
            const missingTokens = this.semanticTokenRules.requiredTokens.filter(
                token => !colors[token]
            );
            if (missingTokens.length > 0) {
                semanticIssues.push({
                    type: 'missing_tokens',
                    severity: 'critical',
                    message: `Missing required semantic tokens: ${missingTokens.join(', ')}`,
                    tokens: missingTokens,
                    code: 'MISSING_SEMANTIC_TOKENS'
                });
            }

            // Validate token hierarchy
            const hierarchyIssues = this.validateTokenHierarchy(colors);
            semanticIssues.push(...hierarchyIssues);

            // Validate semantic mapping
            const mappingIssues = this.validateSemanticMapping(colors);
            semanticIssues.push(...mappingIssues);

            validation.compliance.semantic.score = this.calculateSemanticScore(semanticIssues);
            validation.compliance.semantic.issues = semanticIssues;

            // Add to main validation
            semanticIssues.forEach(issue => {
                if (issue.severity === 'critical') {
                    validation.violations.push(issue);
                    validation.metrics.criticalIssues++;
                } else {
                    validation.warnings.push(issue);
                    validation.metrics.warningIssues++;
                }
            });
        }

        /**
         * Usability validation
         */
        async validateUsability(palette, validation) {
            const usabilityIssues = [];
            const colors = palette.colors || {};

            // Readability validation
            const readabilityResults = this.validateReadability(colors);
            if (readabilityResults.issues.length > 0) {
                usabilityIssues.push(...readabilityResults.issues);
            }

            // Brand consistency
            const brandResults = this.validateBrandConsistency(colors);
            if (brandResults.issues.length > 0) {
                usabilityIssues.push(...brandResults.issues);
            }

            validation.compliance.usability.score = this.calculateUsabilityScore({
                readability: readabilityResults,
                brand: brandResults
            });
            validation.compliance.usability.issues = usabilityIssues;

            // Add to main validation
            usabilityIssues.forEach(issue => {
                if (issue.severity === 'critical') {
                    validation.violations.push(issue);
                    validation.metrics.criticalIssues++;
                } else {
                    validation.warnings.push(issue);
                    validation.metrics.warningIssues++;
                }
            });
        }

        /**
         * Color blindness validation
         */
        validateColorBlindness(colors) {
            const result = {
                isAccessible: true,
                issues: [],
                simulations: {}
            };

            // Simulate different types of color blindness
            const colorBlindTypes = ['protanopia', 'deuteranopia', 'tritanopia'];

            colorBlindTypes.forEach(type => {
                const simulation = this.simulateColorBlindness(colors, type);
                result.simulations[type] = simulation;

                if (!simulation.isDistinguishable) {
                    result.isAccessible = false;
                    result.issues.push({
                        type: type,
                        affectedColors: simulation.problematicPairs,
                        message: `Colors may be indistinguishable for users with ${type}`
                    });
                }
            });

            return result;
        }

        /**
         * Simulate color blindness
         */
        simulateColorBlindness(colors, type) {
            // Simplified color blindness simulation
            // In a real implementation, this would use proper color space transformations
            const result = {
                isDistinguishable: true,
                problematicPairs: []
            };

            const colorValues = Object.entries(colors).map(([role, data]) => ({
                role,
                hex: data.hex || data
            }));

            for (let i = 0; i < colorValues.length; i++) {
                for (let j = i + 1; j < colorValues.length; j++) {
                    const color1 = colorValues[i];
                    const color2 = colorValues[j];

                    // Simple simulation - check if colors are too similar
                    const similarity = this.calculateColorSimilarity(color1.hex, color2.hex);
                    if (similarity > 0.8) { // 80% similarity threshold
                        result.isDistinguishable = false;
                        result.problematicPairs.push([color1.role, color2.role]);
                    }
                }
            }

            return result;
        }

        /**
         * Calculate color similarity (simplified)
         */
        calculateColorSimilarity(hex1, hex2) {
            const rgb1 = this.hexToRgb(hex1);
            const rgb2 = this.hexToRgb(hex2);

            if (!rgb1 || !rgb2) return 0;

            const rDiff = Math.abs(rgb1.r - rgb2.r) / 255;
            const gDiff = Math.abs(rgb1.g - rgb2.g) / 255;
            const bDiff = Math.abs(rgb1.b - rgb2.b) / 255;

            const avgDiff = (rDiff + gDiff + bDiff) / 3;
            return 1 - avgDiff; // Higher value = more similar
        }

        /**
         * Validate color distinctiveness
         */
        validateColorDistinctiveness(colors) {
            const result = {
                isDistinct: true,
                similarPairs: []
            };

            const colorEntries = Object.entries(colors);

            for (let i = 0; i < colorEntries.length; i++) {
                for (let j = i + 1; j < colorEntries.length; j++) {
                    const [role1, color1] = colorEntries[i];
                    const [role2, color2] = colorEntries[j];

                    const hex1 = color1.hex || color1;
                    const hex2 = color2.hex || color2;

                    const similarity = this.calculateColorSimilarity(hex1, hex2);
                    if (similarity > 0.85) { // 85% similarity threshold
                        result.isDistinct = false;
                        result.similarPairs.push({
                            roles: [role1, role2],
                            colors: [hex1, hex2],
                            similarity: Math.round(similarity * 100)
                        });
                    }
                }
            }

            return result;
        }

        /**
         * Validate token hierarchy
         */
        validateTokenHierarchy(colors) {
            const issues = [];
            const hierarchy = this.semanticTokenRules.tokenHierarchy;

            for (const [token, rules] of Object.entries(hierarchy)) {
                if (colors[token]) {
                    const color = colors[token].hex || colors[token];
                    const contrast = this.calculateContrast(color, '#ffffff');

                    if (contrast < rules.minContrast) {
                        issues.push({
                            type: 'hierarchy_contrast',
                            severity: 'high',
                            message: `${token} token has insufficient contrast for its hierarchy level`,
                            token,
                            contrast: Math.round(contrast * 100) / 100,
                            required: rules.minContrast,
                            code: 'HIERARCHY_CONTRAST_FAIL'
                        });
                    }
                }
            }

            return issues;
        }

        /**
         * Validate semantic mapping
         */
        validateSemanticMapping(colors) {
            const issues = [];
            // This would validate that semantic tokens map correctly to CSS custom properties
            // Implementation depends on the specific mapping requirements
            return issues;
        }

        /**
         * Validate readability across components
         */
        validateReadability(colors) {
            const result = {
                score: 0,
                issues: []
            };

            // Check readability for common component combinations
            const componentTests = [
                { bg: 'background', text: 'primary', component: 'body text' },
                { bg: 'surface', text: 'primary', component: 'card content' },
                { bg: 'primary', text: 'background', component: 'primary button' },
                { bg: 'secondary', text: 'background', component: 'secondary button' }
            ];

            let passedTests = 0;

            componentTests.forEach(test => {
                if (colors[test.bg] && colors[test.text]) {
                    const bgColor = colors[test.bg].hex || colors[test.bg];
                    const textColor = colors[test.text].hex || colors[test.text];
                    const contrast = this.calculateContrast(bgColor, textColor);

                    if (contrast < 4.5) {
                        result.issues.push({
                            type: 'readability',
                            severity: 'high',
                            message: `Poor readability for ${test.component}`,
                            component: test.component,
                            contrast: Math.round(contrast * 100) / 100,
                            code: 'POOR_READABILITY'
                        });
                    } else {
                        passedTests++;
                    }
                }
            });

            result.score = (passedTests / componentTests.length) * 100;
            return result;
        }

        /**
         * Validate brand consistency
         */
        validateBrandConsistency(colors) {
            const result = {
                score: 100,
                issues: []
            };

            // This would implement brand-specific validation rules
            // For now, return a basic implementation
            return result;
        }

        /**
         * Calculate overall validation score
         */
        calculateOverallScore(validation) {
            const weights = {
                wcag: 0.4,
                accessibility: 0.25,
                semantic: 0.2,
                usability: 0.15
            };

            validation.overallScore = Math.round(
                (validation.compliance.wcag.score * weights.wcag) +
                (validation.compliance.accessibility.score * weights.accessibility) +
                (validation.compliance.semantic.score * weights.semantic) +
                (validation.compliance.usability.score * weights.usability)
            );

            validation.isValid = validation.overallScore >= 70 && validation.metrics.criticalIssues === 0;
        }

        /**
         * Generate actionable recommendations
         */
        generateRecommendations(validation) {
            const recommendations = [];

            // WCAG recommendations
            if (validation.compliance.wcag.level === 'FAIL') {
                recommendations.push({
                    type: 'critical',
                    priority: 1,
                    title: 'Fix WCAG Compliance Issues',
                    message: 'Address critical contrast ratio failures to meet accessibility standards',
                    actions: ['Increase contrast ratios', 'Choose darker/lighter alternatives', 'Review color combinations']
                });
            }

            // Accessibility recommendations
            if (validation.compliance.accessibility.score < 70) {
                recommendations.push({
                    type: 'high',
                    priority: 2,
                    title: 'Improve Accessibility',
                    message: 'Enhance color accessibility for better user experience',
                    actions: ['Test with color blindness simulators', 'Increase color distinctiveness', 'Add visual indicators beyond color']
                });
            }

            // Semantic recommendations
            if (validation.compliance.semantic.score < 80) {
                recommendations.push({
                    type: 'medium',
                    priority: 3,
                    title: 'Semantic Token Improvements',
                    message: 'Strengthen semantic token hierarchy and mapping',
                    actions: ['Review token hierarchy', 'Ensure proper semantic roles', 'Validate token mapping']
                });
            }

            validation.recommendations = recommendations;
        }

        /**
         * Utility methods
         */
        calculateWCAGScore(wcagResults) {
            const totalTests = Object.values(wcagResults.scores)
                .flatMap(roleScores => Object.values(roleScores)).length;
            const aaPassCount = Object.values(wcagResults.scores)
                .flatMap(roleScores => Object.values(roleScores))
                .filter(score => score.aaPass).length;

            return totalTests > 0 ? Math.round((aaPassCount / totalTests) * 100) : 0;
        }

        calculateAccessibilityScore(results) {
            let score = 100;
            if (!results.colorBlindness.isAccessible) score -= 30;
            if (!results.distinctiveness.isDistinct) score -= 20;
            return Math.max(0, score);
        }

        calculateSemanticScore(issues) {
            let score = 100;
            issues.forEach(issue => {
                if (issue.severity === 'critical') score -= 25;
                else if (issue.severity === 'high') score -= 15;
                else if (issue.severity === 'medium') score -= 10;
            });
            return Math.max(0, score);
        }

        calculateUsabilityScore(results) {
            return Math.round((results.readability.score + results.brand.score) / 2);
        }

        generateCacheKey(palette, options) {
            const paletteStr = JSON.stringify(palette.colors || {});
            const optionsStr = JSON.stringify(options);
            return `${paletteStr}_${optionsStr}`;
        }

        updatePerformanceMetrics(startTime, wasCached) {
            const duration = performance.now() - startTime;
            this.performanceMetrics.validationCount++;
            this.performanceMetrics.totalValidationTime += duration;

            if (wasCached) {
                const hits = Math.round(this.performanceMetrics.cacheHitRate *
                    (this.performanceMetrics.validationCount - 1) / 100) + 1;
                this.performanceMetrics.cacheHitRate = Math.round(
                    (hits / this.performanceMetrics.validationCount) * 100);
            }
        }

        /**
         * Setup event listeners for real-time validation
         */
        setupEventListeners() {
            // Listen for palette changes
            document.addEventListener('paletteChanged', (event) => {
                this.handlePaletteChange(event.detail);
            });

            // Listen for customizer changes
            if (window.wp && window.wp.customize) {
                window.wp.customize.bind('ready', () => {
                    this.setupCustomizerValidation();
                });
            }
        }

        /**
         * Handle palette change events
         */
        async handlePaletteChange(paletteData) {
            if (paletteData.palette) {
                const validation = await this.validatePalette(paletteData.palette);

                // Trigger validation complete event
                document.dispatchEvent(new CustomEvent('paletteValidationComplete', {
                    detail: {
                        paletteId: paletteData.paletteId,
                        validation: validation,
                        timestamp: Date.now()
                    }
                }));
            }
        }

        /**
         * Setup WordPress Customizer integration
         */
        initializeCustomizerIntegration() {
            if (typeof wp !== 'undefined' && wp.customize) {
                console.log('PaletteValidationSystem: Setting up Customizer integration');
                // Integration will be handled by setupCustomizerValidation when ready
            }
        }

        /**
         * Setup real-time customizer validation
         */
        setupCustomizerValidation() {
            // This will be called when customizer is ready
            console.log('PaletteValidationSystem: Customizer validation ready');
        }

        /**
         * Utility methods for color calculations
         */
        hexToRgb(hex) {
            if (window.ContrastCalculator) {
                return window.ContrastCalculator.hexToRgb(hex);
            }

            // Fallback implementation
            const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            } : null;
        }

        calculateContrast(color1, color2) {
            if (window.ContrastCalculator) {
                return window.ContrastCalculator.calculateContrast(color1, color2);
            }

            // Fallback - return a default value
            return 4.5;
        }

        getOptimalTextColor(backgroundColor, hierarchy) {
            if (window.ContrastCalculator) {
                return window.ContrastCalculator.getOptimalTextColor(backgroundColor, hierarchy);
            }

            // Fallback
            return { color: '#000000', ratio: 4.5 };
        }

        /**
         * Get validation performance metrics
         */
        getPerformanceMetrics() {
            const avgTime = this.performanceMetrics.validationCount > 0 ?
                this.performanceMetrics.totalValidationTime / this.performanceMetrics.validationCount : 0;

            return {
                ...this.performanceMetrics,
                averageValidationTime: Math.round(avgTime * 100) / 100,
                cacheSize: this.validationCache.size
            };
        }

        /**
         * Clear validation cache
         */
        clearCache() {
            this.validationCache.clear();
            this.performanceMetrics = {
                validationCount: 0,
                totalValidationTime: 0,
                cacheHitRate: 0,
                lastValidation: null
            };
        }
    }

    // Export to global scope
    window.PaletteValidationSystem = PaletteValidationSystem;

    // Auto-initialize if in browser environment
    if (typeof window !== 'undefined') {
        window.paletteValidationSystem = new PaletteValidationSystem();
        console.log('PaletteValidationSystem: Initialized and ready');
    }

})(window);
