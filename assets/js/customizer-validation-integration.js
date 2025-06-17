/**
 * T8.4 WordPress Customizer Validation Integration
 *
 * Real-time palette validation feedback in WordPress Customizer
 * with accessibility scoring and user guidance
 *
 * @package MedSpaTheme
 * @version 1.0.0 - T8.4 Implementation
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 * @sprint SPRINT-8-EXTENSIBLE-SEMANTIC-INTEGRATION
 */

(function($) {
    'use strict';

    // Ensure dependencies are available
    if (typeof wp === 'undefined' || typeof wp.customize === 'undefined') {
        console.warn('CustomizerValidationIntegration: WordPress Customizer API not available');
        return;
    }

    /**
     * Customizer Validation Integration Class
     */
    class CustomizerValidationIntegration {
        constructor() {
            this.validationSystem = null;
            this.currentValidation = null;
            this.validationPanel = null;
            this.isInitialized = false;
            this.debounceTimeout = null;

            this.init();
        }

        /**
         * Initialize the customizer validation integration
         */
        init() {
            console.log('CustomizerValidationIntegration: Initializing');

            // Wait for dependencies
            this.waitForDependencies().then(() => {
                this.setupCustomizerIntegration();
                this.createValidationPanel();
                this.bindEvents();
                this.isInitialized = true;
                console.log('CustomizerValidationIntegration: Ready');
            });
        }

        /**
         * Wait for required dependencies to load
         */
        waitForDependencies() {
            return new Promise((resolve) => {
                const checkDependencies = () => {
                    if (window.paletteValidationSystem && wp.customize) {
                        this.validationSystem = window.paletteValidationSystem;
                        resolve();
                    } else {
                        setTimeout(checkDependencies, 100);
                    }
                };
                checkDependencies();
            });
        }

        /**
         * Setup WordPress Customizer integration
         */
        setupCustomizerIntegration() {
            // Listen for customizer ready event
            wp.customize.bind('ready', () => {
                this.onCustomizerReady();
            });

            // Listen for palette changes
            wp.customize('semantic_active_palette', (value) => {
                value.bind((newPalette) => {
                    this.handlePaletteChange(newPalette);
                });
            });
        }

        /**
         * Handle customizer ready event
         */
        onCustomizerReady() {
            console.log('CustomizerValidationIntegration: Customizer ready');

            // Get initial palette and validate
            const initialPalette = wp.customize('semantic_active_palette')();
            if (initialPalette) {
                this.validateCurrentPalette(initialPalette);
            }

            // Inject validation styles
            this.injectValidationStyles();
        }

        /**
         * Handle palette change
         */
        handlePaletteChange(paletteId) {
            console.log('CustomizerValidationIntegration: Palette changed to:', paletteId);

            // Debounce validation to prevent excessive calls
            clearTimeout(this.debounceTimeout);
            this.debounceTimeout = setTimeout(() => {
                this.validateCurrentPalette(paletteId);
            }, 300);
        }

        /**
         * Validate current palette
         */
        async validateCurrentPalette(paletteId) {
            if (!this.validationSystem) return;

            try {
                // Show loading state
                this.showValidationLoading();

                // Get palette data
                const paletteData = this.getPaletteData(paletteId);
                if (!paletteData) {
                    console.warn('CustomizerValidationIntegration: No palette data found for:', paletteId);
                    return;
                }

                // Perform validation
                const validation = await this.validationSystem.validatePalette(paletteData);
                this.currentValidation = validation;

                // Update validation panel
                this.updateValidationPanel(validation);

                // Show validation feedback
                this.showValidationFeedback(validation);

                console.log('CustomizerValidationIntegration: Validation complete:', validation);

            } catch (error) {
                console.error('CustomizerValidationIntegration: Validation error:', error);
                this.showValidationError(error.message);
            }
        }

        /**
         * Get palette data for validation
         */
        getPaletteData(paletteId) {
            // Try to get from semantic color system
            if (window.semanticColorSystem) {
                return window.semanticColorSystem.getPalette(paletteId);
            }

            // Fallback: get from semantic token data
            if (window.semanticTokenData && window.semanticTokenData.palettes) {
                const palette = window.semanticTokenData.palettes[paletteId];
                return palette ? { id: paletteId, ...palette } : null;
            }

            return null;
        }

        /**
         * Create validation panel in customizer
         */
        createValidationPanel() {
            // Create validation panel HTML
            const panelHTML = `
                <div id="semantic-validation-panel" class="validation-panel">
                    <div class="validation-header">
                        <h3>🛡️ Palette Validation</h3>
                        <div class="validation-score">
                            <span class="score-label">Score:</span>
                            <span class="score-value">--</span>
                        </div>
                    </div>

                    <div class="validation-content">
                        <div class="validation-loading" style="display: none;">
                            <div class="loading-spinner"></div>
                            <span>Validating palette...</span>
                        </div>

                        <div class="validation-results" style="display: none;">
                            <div class="compliance-section">
                                <h4>🔍 Compliance Overview</h4>
                                <div class="compliance-grid">
                                    <div class="compliance-item wcag">
                                        <span class="label">WCAG</span>
                                        <span class="value">--</span>
                                        <span class="badge">--</span>
                                    </div>
                                    <div class="compliance-item accessibility">
                                        <span class="label">Accessibility</span>
                                        <span class="value">--</span>
                                        <span class="badge">--</span>
                                    </div>
                                    <div class="compliance-item semantic">
                                        <span class="label">Semantic</span>
                                        <span class="value">--</span>
                                        <span class="badge">--</span>
                                    </div>
                                    <div class="compliance-item usability">
                                        <span class="label">Usability</span>
                                        <span class="value">--</span>
                                        <span class="badge">--</span>
                                    </div>
                                </div>
                            </div>

                            <div class="issues-section">
                                <h4>⚠️ Issues & Recommendations</h4>
                                <div class="issues-list"></div>
                            </div>

                            <div class="metrics-section">
                                <h4>📊 Validation Metrics</h4>
                                <div class="metrics-grid">
                                    <div class="metric">
                                        <span class="label">Total Checks</span>
                                        <span class="value">--</span>
                                    </div>
                                    <div class="metric">
                                        <span class="label">Passed</span>
                                        <span class="value">--</span>
                                    </div>
                                    <div class="metric">
                                        <span class="label">Critical Issues</span>
                                        <span class="value">--</span>
                                    </div>
                                    <div class="metric">
                                        <span class="label">Warnings</span>
                                        <span class="value">--</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="validation-error" style="display: none;">
                            <div class="error-icon">❌</div>
                            <div class="error-message">Validation failed</div>
                        </div>
                    </div>
                </div>
            `;

            // Inject into customizer
            const customizeForm = $('#customize-theme-controls');
            if (customizeForm.length) {
                customizeForm.append(panelHTML);
                this.validationPanel = $('#semantic-validation-panel');
                console.log('CustomizerValidationIntegration: Validation panel created');
            }
        }

        /**
         * Update validation panel with results
         */
        updateValidationPanel(validation) {
            if (!this.validationPanel) return;

            // Hide loading, show results
            this.validationPanel.find('.validation-loading').hide();
            this.validationPanel.find('.validation-error').hide();
            this.validationPanel.find('.validation-results').show();

            // Update overall score
            const scoreClass = this.getScoreClass(validation.overallScore);
            this.validationPanel.find('.score-value')
                .text(validation.overallScore + '%')
                .removeClass('excellent good warning poor')
                .addClass(scoreClass);

            // Update compliance grid
            this.updateComplianceGrid(validation);

            // Update issues list
            this.updateIssuesList(validation);

            // Update metrics
            this.updateMetrics(validation);
        }

        /**
         * Update compliance grid
         */
        updateComplianceGrid(validation) {
            const complianceData = [
                {
                    key: 'wcag',
                    score: validation.compliance.wcag.score,
                    badge: validation.compliance.wcag.level || 'FAIL'
                },
                {
                    key: 'accessibility',
                    score: validation.compliance.accessibility.score,
                    badge: this.getScoreBadge(validation.compliance.accessibility.score)
                },
                {
                    key: 'semantic',
                    score: validation.compliance.semantic.score,
                    badge: this.getScoreBadge(validation.compliance.semantic.score)
                },
                {
                    key: 'usability',
                    score: validation.compliance.usability.score,
                    badge: this.getScoreBadge(validation.compliance.usability.score)
                }
            ];

            complianceData.forEach(item => {
                const element = this.validationPanel.find(`.compliance-item.${item.key}`);
                element.find('.value').text(item.score + '%');

                const badge = element.find('.badge');
                badge.text(item.badge)
                    .removeClass('excellent good warning poor')
                    .addClass(this.getScoreClass(item.score));
            });
        }

        /**
         * Update issues list
         */
        updateIssuesList(validation) {
            const issuesList = this.validationPanel.find('.issues-list');
            issuesList.empty();

            // Add critical violations
            validation.violations.forEach(violation => {
                const issueHTML = this.createIssueHTML(violation, 'violation');
                issuesList.append(issueHTML);
            });

            // Add warnings
            validation.warnings.forEach(warning => {
                const issueHTML = this.createIssueHTML(warning, 'warning');
                issuesList.append(issueHTML);
            });

            // Add recommendations
            validation.recommendations.forEach(recommendation => {
                const recHTML = this.createRecommendationHTML(recommendation);
                issuesList.append(recHTML);
            });

            // Show message if no issues
            if (validation.violations.length === 0 && validation.warnings.length === 0) {
                issuesList.append('<div class="no-issues">✅ No critical issues found!</div>');
            }
        }

        /**
         * Create issue HTML
         */
        createIssueHTML(issue, type) {
            const iconMap = {
                violation: '❌',
                warning: '⚠️',
                info: 'ℹ️'
            };

            const icon = iconMap[type] || 'ℹ️';
            const severityClass = issue.severity || 'medium';

            return `
                <div class="validation-issue ${type} ${severityClass}">
                    <div class="issue-header">
                        <span class="issue-icon">${icon}</span>
                        <span class="issue-type">${issue.type || 'Issue'}</span>
                        <span class="issue-severity">${issue.severity}</span>
                    </div>
                    <div class="issue-message">${issue.message}</div>
                    ${issue.code ? `<div class="issue-code">Code: ${issue.code}</div>` : ''}
                </div>
            `;
        }

        /**
         * Create recommendation HTML
         */
        createRecommendationHTML(recommendation) {
            return `
                <div class="validation-recommendation ${recommendation.type}">
                    <div class="rec-header">
                        <span class="rec-icon">💡</span>
                        <span class="rec-title">${recommendation.title}</span>
                        <span class="rec-priority">Priority: ${recommendation.priority}</span>
                    </div>
                    <div class="rec-message">${recommendation.message}</div>
                    ${recommendation.actions ? `
                        <div class="rec-actions">
                            <strong>Actions:</strong>
                            <ul>
                                ${recommendation.actions.map(action => `<li>${action}</li>`).join('')}
                            </ul>
                        </div>
                    ` : ''}
                </div>
            `;
        }

        /**
         * Update metrics display
         */
        updateMetrics(validation) {
            const metrics = validation.metrics;
            const metricsData = [
                { key: 'total-checks', value: metrics.totalChecks },
                { key: 'passed', value: metrics.passedChecks },
                { key: 'critical-issues', value: metrics.criticalIssues },
                { key: 'warnings', value: metrics.warningIssues }
            ];

            metricsData.forEach(metric => {
                this.validationPanel.find(`.metric:contains("${metric.key.replace('-', ' ')}")`).find('.value').text(metric.value);
            });
        }

        /**
         * Show validation loading state
         */
        showValidationLoading() {
            if (!this.validationPanel) return;

            this.validationPanel.find('.validation-results').hide();
            this.validationPanel.find('.validation-error').hide();
            this.validationPanel.find('.validation-loading').show();
        }

        /**
         * Show validation error
         */
        showValidationError(message) {
            if (!this.validationPanel) return;

            this.validationPanel.find('.validation-loading').hide();
            this.validationPanel.find('.validation-results').hide();
            this.validationPanel.find('.validation-error')
                .find('.error-message').text(message).end()
                .show();
        }

        /**
         * Show validation feedback notifications
         */
        showValidationFeedback(validation) {
            // Show notification based on validation results
            if (validation.metrics.criticalIssues > 0) {
                this.showNotification('critical', `${validation.metrics.criticalIssues} critical accessibility issues found!`);
            } else if (validation.overallScore < 70) {
                this.showNotification('warning', `Palette score: ${validation.overallScore}% - Consider improvements`);
            } else if (validation.overallScore >= 90) {
                this.showNotification('success', `Excellent palette! Score: ${validation.overallScore}%`);
            }
        }

        /**
         * Show notification
         */
        showNotification(type, message) {
            const notification = $(`
                <div class="validation-notification ${type}">
                    <span class="notification-message">${message}</span>
                    <button class="notification-close">&times;</button>
                </div>
            `);

            // Add to customizer
            $('.wp-full-overlay').append(notification);

            // Auto-hide after 5 seconds
            setTimeout(() => {
                notification.fadeOut(() => notification.remove());
            }, 5000);

            // Handle close button
            notification.find('.notification-close').on('click', () => {
                notification.fadeOut(() => notification.remove());
            });
        }

        /**
         * Inject validation styles
         */
        injectValidationStyles() {
            const styles = `
                <style id="customizer-validation-styles">
                /* Validation Panel Styles */
                .validation-panel {
                    background: #fff;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    margin: 15px;
                    padding: 0;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }

                .validation-header {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 15px;
                    border-radius: 8px 8px 0 0;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .validation-header h3 {
                    margin: 0;
                    font-size: 16px;
                    font-weight: 600;
                }

                .validation-score {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }

                .score-value {
                    font-weight: bold;
                    padding: 4px 8px;
                    border-radius: 4px;
                    background: rgba(255,255,255,0.2);
                }

                .score-value.excellent { background: #10b981; }
                .score-value.good { background: #3b82f6; }
                .score-value.warning { background: #f59e0b; }
                .score-value.poor { background: #ef4444; }

                .validation-content {
                    padding: 15px;
                }

                .validation-loading {
                    text-align: center;
                    padding: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 10px;
                }

                .loading-spinner {
                    width: 20px;
                    height: 20px;
                    border: 2px solid #f3f3f3;
                    border-top: 2px solid #667eea;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                }

                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }

                .compliance-section h4,
                .issues-section h4,
                .metrics-section h4 {
                    margin: 0 0 10px 0;
                    font-size: 14px;
                    color: #333;
                    border-bottom: 1px solid #eee;
                    padding-bottom: 5px;
                }

                .compliance-grid {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 10px;
                    margin-bottom: 20px;
                }

                .compliance-item {
                    padding: 10px;
                    background: #f8f9fa;
                    border-radius: 6px;
                    text-align: center;
                }

                .compliance-item .label {
                    display: block;
                    font-size: 12px;
                    color: #666;
                    margin-bottom: 4px;
                }

                .compliance-item .value {
                    display: block;
                    font-weight: bold;
                    font-size: 14px;
                }

                .compliance-item .badge {
                    display: inline-block;
                    padding: 2px 6px;
                    border-radius: 3px;
                    font-size: 10px;
                    font-weight: bold;
                    margin-top: 4px;
                    text-transform: uppercase;
                }

                .badge.excellent { background: #10b981; color: white; }
                .badge.good { background: #3b82f6; color: white; }
                .badge.warning { background: #f59e0b; color: black; }
                .badge.poor { background: #ef4444; color: white; }

                .validation-issue,
                .validation-recommendation {
                    margin-bottom: 10px;
                    padding: 10px;
                    border-radius: 6px;
                    border-left: 4px solid;
                }

                .validation-issue.violation {
                    background: #fef2f2;
                    border-left-color: #ef4444;
                }

                .validation-issue.warning {
                    background: #fffbeb;
                    border-left-color: #f59e0b;
                }

                .validation-recommendation {
                    background: #f0f9ff;
                    border-left-color: #3b82f6;
                }

                .issue-header,
                .rec-header {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    margin-bottom: 6px;
                    font-weight: 600;
                    font-size: 12px;
                }

                .issue-severity,
                .rec-priority {
                    margin-left: auto;
                    padding: 2px 6px;
                    border-radius: 3px;
                    font-size: 10px;
                    text-transform: uppercase;
                }

                .issue-message,
                .rec-message {
                    font-size: 12px;
                    color: #555;
                    line-height: 1.4;
                }

                .issue-code {
                    font-size: 10px;
                    color: #888;
                    margin-top: 4px;
                    font-family: monospace;
                }

                .rec-actions {
                    margin-top: 8px;
                    font-size: 11px;
                }

                .rec-actions ul {
                    margin: 4px 0 0 16px;
                    padding: 0;
                }

                .metrics-grid {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 8px;
                }

                .metric {
                    display: flex;
                    justify-content: space-between;
                    padding: 6px 8px;
                    background: #f8f9fa;
                    border-radius: 4px;
                    font-size: 12px;
                }

                .metric .value {
                    font-weight: bold;
                }

                .no-issues {
                    text-align: center;
                    padding: 20px;
                    color: #10b981;
                    font-weight: 600;
                }

                .validation-error {
                    text-align: center;
                    padding: 20px;
                    color: #ef4444;
                }

                .validation-notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 12px 16px;
                    border-radius: 6px;
                    color: white;
                    font-weight: 500;
                    z-index: 999999;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    max-width: 300px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                }

                .validation-notification.success { background: #10b981; }
                .validation-notification.warning { background: #f59e0b; }
                .validation-notification.critical { background: #ef4444; }

                .notification-close {
                    background: none;
                    border: none;
                    color: white;
                    font-size: 18px;
                    cursor: pointer;
                    padding: 0;
                    margin-left: auto;
                }
                </style>
            `;

            $('head').append(styles);
        }

        /**
         * Bind event listeners
         */
        bindEvents() {
            // Listen for validation complete events
            document.addEventListener('paletteValidationComplete', (event) => {
                console.log('CustomizerValidationIntegration: Validation complete event received');
            });
        }

        /**
         * Utility methods
         */
        getScoreClass(score) {
            if (score >= 90) return 'excellent';
            if (score >= 75) return 'good';
            if (score >= 60) return 'warning';
            return 'poor';
        }

        getScoreBadge(score) {
            if (score >= 90) return 'Excellent';
            if (score >= 75) return 'Good';
            if (score >= 60) return 'Fair';
            return 'Poor';
        }

        /**
         * Get current validation results
         */
        getCurrentValidation() {
            return this.currentValidation;
        }

        /**
         * Get validation system performance metrics
         */
        getPerformanceMetrics() {
            return this.validationSystem ? this.validationSystem.getPerformanceMetrics() : null;
        }
    }

    // Initialize when document is ready
    $(document).ready(function() {
        // Only initialize in customizer context
        if (window.parent && window.parent.wp && window.parent.wp.customize) {
            window.customizerValidationIntegration = new CustomizerValidationIntegration();
            console.log('CustomizerValidationIntegration: Initialized');
        }
    });

    // Export to global scope
    window.CustomizerValidationIntegration = CustomizerValidationIntegration;

})(jQuery);
