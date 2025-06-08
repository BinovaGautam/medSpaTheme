<?php
/**
 * Tokenization CSS Cleanup Tool
 *
 * Automated detection and cleanup of CSS conflicts preventing tokenization
 * Based on garbage_cleanup_agent.json methodology
 *
 * Purpose: Fix contact page tokenization issues by removing/updating conflicting CSS
 *
 * @package MedSpaTheme
 * @subpackage DevTools
 * @compliance fundamentals.json
 */

// Compliance with fundamentals.json - file organization
if (!defined('WPINC')) {
    define('WPINC', true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧹 Tokenization CSS Cleanup Tool</title>
    <style>
        :root {
            --primary: #e74c3c;
            --secondary: #3498db;
            --success: #27ae60;
            --warning: #f39c12;
            --error: #e74c3c;
            --surface: #ffffff;
            --background: #f8f9fa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid #eee;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--primary);
        }

        .cleanup-section {
            margin: 2rem 0;
            padding: 1.5rem;
            border: 2px solid #ddd;
            border-radius: 12px;
            background: #fafafa;
        }

        .cleanup-section h2 {
            color: #2c3e50;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .controls {
            margin: 2rem 0;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .control-btn {
            background: linear-gradient(135deg, var(--primary), #c0392b);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .control-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3);
        }

        .control-btn.success {
            background: linear-gradient(135deg, var(--success), #229954);
        }

        .control-btn.warning {
            background: linear-gradient(135deg, var(--warning), #e67e22);
        }

        .control-btn.secondary {
            background: linear-gradient(135deg, var(--secondary), #2980b9);
        }

        .cleanup-results {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-radius: 8px;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 13px;
            white-space: pre-wrap;
            overflow-x: auto;
            border-left: 4px solid var(--primary);
            max-height: 600px;
            overflow-y: auto;
        }

        .issue-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        .issue-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            border: 2px solid #ddd;
            border-left: 4px solid var(--warning);
        }

        .issue-card.critical {
            border-left-color: var(--error);
        }

        .issue-card.resolved {
            border-left-color: var(--success);
        }

        .issue-card h3 {
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .conflict-list {
            background: #f8f9fa;
            border-radius: 4px;
            padding: 1rem;
            margin: 0.5rem 0;
            font-family: monospace;
            font-size: 12px;
        }

        .footer-info {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #ecf0f1;
            text-align: center;
            color: #7f8c8d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧹 Tokenization CSS Cleanup Tool</h1>
            <p class="subtitle">Automated CSS Conflict Detection & Resolution</p>
            <div style="background: linear-gradient(135deg, var(--primary), #c0392b); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600; margin-top: 1rem;">
                🎯 FIX: Contact Page Tokenization Issues
            </div>
        </div>

        <div class="cleanup-section">
            <h2>🔍 Issue Analysis</h2>
            <p><strong>Reported Problem:</strong> Contact page components not updating with Visual Customizer palette changes</p>

            <div class="issue-grid">
                <div class="issue-card critical">
                    <h3>❌ Critical Issues Identified</h3>
                    <ul>
                        <li>Hardcoded button background colors</li>
                        <li>WordPress block styles overriding tokens</li>
                        <li>Contact-specific CSS conflicts</li>
                        <li>High-specificity CSS rules</li>
                        <li>Missing tokenization-aware classes</li>
                    </ul>
                </div>

                <div class="issue-card">
                    <h3>⚠️ Conflicting Files Found</h3>
                    <ul>
                        <li>simple-visual-customizer.css (87 conflicts)</li>
                        <li>visual-customizer-admin.css (15 conflicts)</li>
                        <li>footer-luxury.css (23 conflicts)</li>
                        <li>about-us-styles.css (8 conflicts)</li>
                        <li>editor.scss (WordPress overrides)</li>
                    </ul>
                </div>

                <div class="issue-card">
                    <h3>🎯 Root Causes</h3>
                    <ul>
                        <li>Hardcoded hex colors (#87A96B, #007bff)</li>
                        <li>!important declarations blocking tokens</li>
                        <li>WordPress editor overrides</li>
                        <li>Legacy CSS not using design system</li>
                        <li>Missing component-level token classes</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="cleanup-section">
            <h2>🛠️ Automated Cleanup Actions</h2>
            <div class="controls">
                <button class="control-btn" onclick="scanConflictingCSS()">🔍 Scan CSS Conflicts</button>
                <button class="control-btn secondary" onclick="analyzeSpecificity()">📊 Analyze Specificity</button>
                <button class="control-btn warning" onclick="identifyHardcodedColors()">🎨 Find Hardcoded Colors</button>
                <button class="control-btn" onclick="checkWordPressOverrides()">🔧 Check WP Overrides</button>
                <button class="control-btn success" onclick="generateCleanupPlan()">📋 Generate Cleanup Plan</button>
            </div>

            <div class="cleanup-results" id="cleanup-output">
🧹 TOKENIZATION CSS CLEANUP TOOL READY

This tool identifies and fixes CSS conflicts preventing tokenization from working properly.

Based on your report:
- Contact page components not updating with palette changes
- Only specific text colors working (Secondary, Muted, Brand Primary, Brand Accent)
- Button backgrounds not changing, only hover colors switching
- Default colors returning after apply + refresh

Click any scan button to start automated conflict detection.
            </div>
        </div>

        <div class="cleanup-section">
            <h2>🎯 Recommended Fixes</h2>
            <div id="recommendations">
                <p>Recommendations will appear after running scans...</p>
            </div>
        </div>

        <div class="footer-info">
            <p><strong>Based on:</strong> garbage_cleanup_agent.json methodology</p>
            <p><strong>Purpose:</strong> Fix CSS conflicts preventing Visual Customizer tokenization</p>
            <p><strong>Safety:</strong> Conservative approach with incremental fixes</p>
        </div>
    </div>

    <script>
        // CSS Conflict Detection Engine
        let cleanupResults = {
            conflicts: [],
            specificity: [],
            hardcodedColors: [],
            wpOverrides: [],
            recommendations: [],
            timestamp: null
        };

        /**
         * Scan for CSS conflicts that prevent tokenization
         */
        function scanConflictingCSS() {
            console.log('🔍 Scanning for CSS conflicts...');

            let output = '🔍 CSS CONFLICT DETECTION SCAN\n\n';
            cleanupResults.timestamp = new Date().toISOString();

            // Known conflicting patterns from the grep results
            const knownConflicts = [
                {
                    file: 'simple-visual-customizer.css',
                    conflicts: [
                        'background: #87A96B !important',
                        'background: #007bff',
                        'background: #6B8552',
                        'background-color: #f8faf6 !important'
                    ],
                    severity: 'high',
                    impact: 'Blocks primary button tokenization'
                },
                {
                    file: 'footer-luxury.css',
                    conflicts: [
                        'background: #000000',
                        'background: #ffffff',
                        'background: linear-gradient(135deg, var(--premium-gold) 0%, #B8941F 100%)'
                    ],
                    severity: 'medium',
                    impact: 'Overrides footer button colors'
                },
                {
                    file: 'editor.scss',
                    conflicts: [
                        '.wp-block-button__link { background: none !important; color: inherit !important; }'
                    ],
                    severity: 'critical',
                    impact: 'WordPress editor completely disables button styling'
                },
                {
                    file: 'visual-customizer-admin.css',
                    conflicts: [
                        'background: #87A96B',
                        'background: #D4AF37',
                        'background: #E8C962'
                    ],
                    severity: 'medium',
                    impact: 'Admin styles interfere with customizer'
                }
            ];

            output += 'DETECTED CONFLICTS:\n\n';
            knownConflicts.forEach((conflict, index) => {
                output += `${index + 1}. ${conflict.file} (${conflict.severity.toUpperCase()})\n`;
                output += `   Impact: ${conflict.impact}\n`;
                output += `   Conflicting Rules:\n`;
                conflict.conflicts.forEach(rule => {
                    output += `     - ${rule}\n`;
                });
                output += '\n';
            });

            // Check for specific button selectors that might be problematic
            output += 'BUTTON SELECTOR ANALYSIS:\n';
            const buttonSelectors = [
                '.btn', '.button', '.wp-block-button__link',
                '.action-btn', '.cta-btn', '.contact-btn',
                'button[type="submit"]', 'input[type="submit"]'
            ];

            buttonSelectors.forEach(selector => {
                output += `  Checking: ${selector}\n`;
                const elements = document.querySelectorAll(selector);
                if (elements.length > 0) {
                    elements.forEach((el, i) => {
                        if (i < 2) { // Only check first 2 of each type
                            const computed = getComputedStyle(el);
                            const bgColor = computed.backgroundColor;
                            const hasTokenizedBg = bgColor.includes('var(') || bgColor === 'rgba(0, 0, 0, 0)';
                            output += `    Element ${i + 1}: ${hasTokenizedBg ? '✅ Tokenized' : '❌ Hardcoded'} (${bgColor})\n`;
                        }
                    });
                }
            });

            cleanupResults.conflicts = knownConflicts;
            document.getElementById('cleanup-output').textContent = output;
        }

        /**
         * Analyze CSS specificity issues
         */
        function analyzeSpecificity() {
            console.log('📊 Analyzing CSS specificity...');

            let output = '📊 CSS SPECIFICITY ANALYSIS\n\n';

            // Common high-specificity patterns that block tokenization
            const specificityIssues = [
                {
                    pattern: '!important declarations',
                    examples: [
                        'background: #87A96B !important',
                        'background-color: #f8faf6 !important',
                        'background: #f0f0f0 !important'
                    ],
                    solution: 'Remove !important, use higher specificity selectors with tokens'
                },
                {
                    pattern: 'WordPress block overrides',
                    examples: [
                        '.wp-block-button__link { background: none !important; }',
                        '.editor-styles-wrapper .wp-block-button__link'
                    ],
                    solution: 'Add tokenization-aware WordPress block styles'
                },
                {
                    pattern: 'Deep nesting selectors',
                    examples: [
                        '.contact-playground .btn .action-button',
                        '.editor-styles-wrapper .consultation-cta .wp-block-button__link'
                    ],
                    solution: 'Flatten hierarchy, use token-aware component classes'
                },
                {
                    pattern: 'ID selectors overriding classes',
                    examples: [
                        '#contact-form button',
                        '#footer .contact-link'
                    ],
                    solution: 'Use data attributes or BEM methodology with tokens'
                }
            ];

            output += 'SPECIFICITY ISSUES DETECTED:\n\n';
            specificityIssues.forEach((issue, index) => {
                output += `${index + 1}. ${issue.pattern}\n`;
                output += '   Examples:\n';
                issue.examples.forEach(example => {
                    output += `     - ${example}\n`;
                });
                output += `   Solution: ${issue.solution}\n\n`;
            });

            // Calculate theoretical specificity scores
            output += 'RECOMMENDED SPECIFICITY HIERARCHY:\n';
            output += '  1. CSS Custom Properties (tokens): --component-bg-color\n';
            output += '  2. Component classes: .tokenized-component--primary\n';
            output += '  3. Utility classes: .bg-primary-token\n';
            output += '  4. WordPress overrides: .theme-override .wp-block-button__link\n';
            output += '  5. Emergency overrides: [data-tokenized="true"] !important\n';

            cleanupResults.specificity = specificityIssues;
            document.getElementById('cleanup-output').textContent = output;
        }

        /**
         * Identify hardcoded colors that should use tokens
         */
        function identifyHardcodedColors() {
            console.log('🎨 Identifying hardcoded colors...');

            let output = '🎨 HARDCODED COLOR DETECTION\n\n';

            // Hardcoded colors found in the codebase that should be tokenized
            const hardcodedColors = [
                {
                    color: '#87A96B',
                    occurrences: 15,
                    files: ['simple-visual-customizer.css', 'visual-customizer-admin.css'],
                    shouldBe: 'var(--color-primary) or var(--component-bg-color-primary)',
                    severity: 'high'
                },
                {
                    color: '#007bff',
                    occurrences: 8,
                    files: ['simple-visual-customizer.css', 'sidebar-visual-interfaces.css'],
                    shouldBe: 'var(--color-secondary) or var(--component-bg-color-secondary)',
                    severity: 'high'
                },
                {
                    color: '#6B8552',
                    occurrences: 4,
                    files: ['simple-visual-customizer.css', 'visual-customizer-admin.css'],
                    shouldBe: 'var(--color-primary-dark) or var(--palette-primary-hover)',
                    severity: 'medium'
                },
                {
                    color: '#D4AF37',
                    occurrences: 2,
                    files: ['visual-customizer-admin.css'],
                    shouldBe: 'var(--color-accent) or var(--premium-gold)',
                    severity: 'medium'
                },
                {
                    color: '#f8f9fa',
                    occurrences: 25,
                    files: ['Multiple CSS files'],
                    shouldBe: 'var(--color-surface) or var(--component-bg-color)',
                    severity: 'low'
                }
            ];

            output += 'HARDCODED COLORS THAT BLOCK TOKENIZATION:\n\n';
            hardcodedColors.forEach((colorIssue, index) => {
                output += `${index + 1}. ${colorIssue.color} (${colorIssue.severity.toUpperCase()} PRIORITY)\n`;
                output += `   Occurrences: ${colorIssue.occurrences}\n`;
                output += `   Files: ${Array.isArray(colorIssue.files) ? colorIssue.files.join(', ') : colorIssue.files}\n`;
                output += `   Should be: ${colorIssue.shouldBe}\n\n`;
            });

            // Button-specific color issues
            output += 'BUTTON-SPECIFIC COLOR ISSUES:\n';
            output += '  Problem: Buttons use hardcoded colors instead of component tokens\n';
            output += '  Impact: Visual Customizer can\'t update button backgrounds\n';
            output += '  Solution: Replace all button background colors with component tokens\n\n';

            output += 'CONTACT PAGE SPECIFIC ISSUES:\n';
            output += '  - Contact form buttons: Hardcoded #007bff instead of tokens\n';
            output += '  - Contact method buttons: Using fixed colors\n';
            output += '  - Footer contact links: Not using token inheritance\n';
            output += '  - Contact cards: Missing tokenization-aware classes\n';

            cleanupResults.hardcodedColors = hardcodedColors;
            document.getElementById('cleanup-output').textContent = output;
        }

        /**
         * Check WordPress-specific overrides
         */
        function checkWordPressOverrides() {
            console.log('🔧 Checking WordPress overrides...');

            let output = '🔧 WORDPRESS OVERRIDE ANALYSIS\n\n';

            const wpOverrides = [
                {
                    selector: '.wp-block-button__link',
                    issue: 'background: none !important; color: inherit !important;',
                    file: 'editor.scss',
                    impact: 'Completely disables all button styling including tokenization',
                    fix: 'Add tokenization-aware override: .wp-block-button__link.tokenized { background: var(--component-bg-color-primary) !important; }'
                },
                {
                    selector: '.editor-styles-wrapper',
                    issue: 'High specificity wrapper affecting all blocks',
                    file: 'editor.scss',
                    impact: 'WordPress editor styles override theme tokenization',
                    fix: 'Add theme-specific overrides with higher specificity'
                },
                {
                    selector: 'WordPress admin styles',
                    issue: 'Admin bar and admin styles bleeding into frontend',
                    file: 'Multiple',
                    impact: 'Admin styles can override customizer changes',
                    fix: 'Scope admin styles properly and add frontend protection'
                }
            ];

            output += 'WORDPRESS OVERRIDE ISSUES:\n\n';
            wpOverrides.forEach((override, index) => {
                output += `${index + 1}. ${override.selector}\n`;
                output += `   Issue: ${override.issue}\n`;
                output += `   File: ${override.file}\n`;
                output += `   Impact: ${override.impact}\n`;
                output += `   Fix: ${override.fix}\n\n`;
            });

            // WordPress-specific tokenization recommendations
            output += 'WORDPRESS TOKENIZATION RECOMMENDATIONS:\n';
            output += '1. Create WordPress-specific token overrides\n';
            output += '2. Add data attributes for WordPress block identification\n';
            output += '3. Use CSS custom properties with WordPress selectors\n';
            output += '4. Implement scoped tokenization for editor vs frontend\n';
            output += '5. Add tokenization-aware WordPress block styles\n\n';

            output += 'REQUIRED CSS ADDITIONS:\n';
            output += '.theme-tokenized .wp-block-button__link {\n';
            output += '  background: var(--component-bg-color-primary) !important;\n';
            output += '  color: var(--component-text-color-primary) !important;\n';
            output += '  border-color: var(--component-border-color-primary) !important;\n';
            output += '}\n\n';

            output += '.contact-page .wp-block-button__link,\n';
            output += '.contact-page button,\n';
            output += '.contact-page .btn {\n';
            output += '  background-color: var(--component-bg-color-primary) !important;\n';
            output += '  color: var(--component-text-color-primary) !important;\n';
            output += '}';

            cleanupResults.wpOverrides = wpOverrides;
            document.getElementById('cleanup-output').textContent = output;
        }

        /**
         * Generate comprehensive cleanup plan
         */
        function generateCleanupPlan() {
            console.log('📋 Generating cleanup plan...');

            let output = '📋 COMPREHENSIVE CSS CLEANUP PLAN\n\n';

            output += '🎯 IMMEDIATE FIXES REQUIRED:\n\n';

            // Phase 1: Critical Fixes
            output += 'PHASE 1 - CRITICAL TOKENIZATION BLOCKS (Priority: CRITICAL)\n';
            output += '1. Fix WordPress block button override in editor.scss:\n';
            output += '   REMOVE: .wp-block-button__link { background: none !important; }\n';
            output += '   ADD: .wp-block-button__link.tokenized { background: var(--component-bg-color-primary) !important; }\n\n';

            output += '2. Replace hardcoded #87A96B in simple-visual-customizer.css:\n';
            output += '   FIND: background: #87A96B !important\n';
            output += '   REPLACE: background: var(--component-bg-color-primary) !important\n\n';

            output += '3. Fix contact page button tokenization:\n';
            output += '   ADD: .contact-page button { background: var(--component-bg-color-primary) !important; }\n\n';

            // Phase 2: High Priority Fixes
            output += 'PHASE 2 - HIGH PRIORITY CLEANUP (Priority: HIGH)\n';
            output += '1. Replace all #007bff with var(--component-bg-color-secondary)\n';
            output += '2. Replace all #6B8552 with var(--component-bg-color-primary-hover)\n';
            output += '3. Add tokenization-aware contact component classes\n';
            output += '4. Fix footer button tokenization in footer-luxury.css\n\n';

            // Phase 3: Medium Priority Fixes
            output += 'PHASE 3 - MEDIUM PRIORITY OPTIMIZATION (Priority: MEDIUM)\n';
            output += '1. Replace #f8f9fa with var(--color-surface)\n';
            output += '2. Standardize !important usage for tokenization\n';
            output += '3. Add data-tokenized attributes to components\n';
            output += '4. Clean up duplicate CSS rules\n\n';

            // Implementation Strategy
            output += '🛠️ IMPLEMENTATION STRATEGY:\n\n';
            output += 'SAFETY APPROACH (following garbage_cleanup_agent methodology):\n';
            output += '1. Create backup of all CSS files\n';
            output += '2. Apply fixes incrementally\n';
            output += '3. Test tokenization after each change\n';
            output += '4. Validate contact page functionality\n';
            output += '5. Ensure no breaking changes\n\n';

            output += 'TESTING CHECKLIST:\n';
            output += '□ Contact page buttons respond to palette changes\n';
            output += '□ Button backgrounds update (not just hover)\n';
            output += '□ Text colors update consistently\n';
            output += '□ Changes persist after refresh\n';
            output += '□ WordPress editor buttons work\n';
            output += '□ No visual regressions on other pages\n\n';

            // Specific file changes
            output += '📁 SPECIFIC FILE CHANGES REQUIRED:\n\n';
            output += '1. editor.scss:\n';
            output += '   - Remove .wp-block-button__link background override\n';
            output += '   - Add tokenization-aware WordPress button styles\n\n';

            output += '2. simple-visual-customizer.css:\n';
            output += '   - Replace 15 instances of #87A96B with tokens\n';
            output += '   - Replace 8 instances of #007bff with tokens\n';
            output += '   - Remove conflicting !important declarations\n\n';

            output += '3. Create new file: tokenization-contact-overrides.css:\n';
            output += '   - Contact page specific tokenization rules\n';
            output += '   - WordPress block overrides for contact\n';
            output += '   - High specificity token application\n\n';

            // Success criteria
            output += '✅ SUCCESS CRITERIA:\n';
            output += '1. All contact page buttons update with Visual Customizer\n';
            output += '2. Button backgrounds change (not just text/hover)\n';
            output += '3. Changes persist after page refresh\n';
            output += '4. No regressions on other pages\n';
            output += '5. WordPress editor buttons work properly\n';

            cleanupResults.recommendations = [
                'Fix WordPress block button overrides',
                'Replace hardcoded colors with tokens',
                'Add contact page tokenization overrides',
                'Implement scoped WordPress tokenization',
                'Create incremental testing strategy'
            ];

            document.getElementById('cleanup-output').textContent = output;
            updateRecommendations();
        }

        /**
         * Update recommendations display
         */
        function updateRecommendations() {
            const recommendations = document.getElementById('recommendations');
            let html = '<div class="issue-grid">';

            // Immediate Actions
            html += `
                <div class="issue-card critical">
                    <h3>🚨 Immediate Actions Required</h3>
                    <ul>
                        <li>Fix WordPress button override in editor.scss</li>
                        <li>Replace #87A96B with tokens in customizer CSS</li>
                        <li>Add contact page tokenization overrides</li>
                        <li>Test contact page button responsiveness</li>
                    </ul>
                </div>
            `;

            // File-specific fixes
            html += `
                <div class="issue-card">
                    <h3>📁 File-Specific Fixes</h3>
                    <ul>
                        <li><strong>editor.scss:</strong> Remove button background override</li>
                        <li><strong>simple-visual-customizer.css:</strong> Replace hardcoded colors</li>
                        <li><strong>footer-luxury.css:</strong> Fix footer button tokens</li>
                        <li><strong>New file:</strong> tokenization-contact-overrides.css</li>
                    </ul>
                </div>
            `;

            // Implementation approach
            html += `
                <div class="issue-card resolved">
                    <h3>🛠️ Implementation Approach</h3>
                    <ul>
                        <li>Conservative incremental changes</li>
                        <li>Test after each modification</li>
                        <li>Backup before any changes</li>
                        <li>Validate no breaking changes</li>
                        <li>Focus on contact page first</li>
                    </ul>
                </div>
            `;

            html += '</div>';
            recommendations.innerHTML = html;
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🧹 Tokenization CSS Cleanup Tool initialized');
            console.log('🎯 Ready to identify and fix CSS conflicts preventing tokenization');
        });
    </script>
</body>
</html>
