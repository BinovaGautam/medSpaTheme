<?php
/**
 * Tokenization Inheritance Debugging Tool
 *
 * Comprehensive analysis of CSS custom property inheritance issues
 * Identifies root causes of button background vs text color inconsistencies
 *
 * Part of: Sprint 3 - Tokenization-Aware Design System Debug
 * Created: {CURRENT_DATE}
 * Version: 1.0.0
 *
 * @package MedSpaTheme
 * @subpackage DevTools
 * @compliance fundamentals.json
 */

// Compliance with fundamentals.json - file organization
if (!defined('WPINC')) {
    // Allow direct access for testing purposes
    define('WPINC', true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔬 Tokenization Inheritance Debug - Root Cause Analysis</title>
    <style>
        /*
         * FUNDAMENTALS COMPLIANCE: Debug tool with temporal placeholders
         * Tokenization issue analysis following Sprint 3 Architecture
         */
        :root {
            /* Debug token baseline - exact same as production */
            --test-color-primary: #2563eb;
            --test-color-primary-contrast: #ffffff;
            --test-color-surface: #ffffff;
            --test-color-text-primary: #0f172a;
            --test-space-4: 1rem;
            --test-radius-md: 0.375rem;
            --test-border-color: #e2e8f0;

            /* Component-level tokens - CRITICAL FOR DEBUGGING */
            --component-text-color: var(--test-color-text-primary);
            --component-bg-color: var(--test-color-surface);
            --component-border-color: var(--test-border-color);

            /* Button-specific tokens - INVESTIGATING INHERITANCE CHAIN */
            --button-primary-bg: var(--test-color-primary);
            --button-primary-text: var(--test-color-primary-contrast);
            --button-primary-border: var(--test-color-primary);

            /* Legacy tokens that might be interfering */
            --btn-primary-bg: var(--test-color-primary);
            --btn-primary-background: var(--test-color-primary);
            --color-primary: var(--test-color-primary);
            --primary-color: var(--test-color-primary);
        }

        /* Base styling */
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
            color: #e74c3c;
        }

        .debug-section {
            margin: 3rem 0;
            padding: 2rem;
            border: 2px solid #ddd;
            border-radius: 12px;
            background: #fafafa;
        }

        .debug-section h2 {
            color: #2c3e50;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .debug-icon {
            font-size: 1.5rem;
        }

        /* TEST COMPONENTS - Recreating exact production scenarios */

        /* Standard tokenization-aware component */
        .test-component {
            color: var(--component-text-color, var(--test-color-text-primary));
            background-color: var(--component-bg-color, var(--test-color-surface));
            border: 2px solid var(--component-border-color, var(--test-border-color));
            padding: var(--test-space-4);
            border-radius: var(--test-radius-md);
            transition: all 0.3s ease;
            margin: 1rem 0;
            font-weight: 500;
            position: relative;
        }

        /* Button with tokenization-aware pattern */
        .test-button {
            color: var(--component-text-color, var(--test-color-text-primary));
            background-color: var(--component-bg-color, var(--test-color-surface));
            border: 2px solid var(--component-border-color, var(--test-border-color));
            padding: var(--test-space-4);
            border-radius: var(--test-radius-md);
            transition: all 0.3s ease;
            margin: 1rem;
            font-weight: 500;
            cursor: pointer;
            display: inline-block;
            text-decoration: none;
        }

        /* Button with direct token reference */
        .test-button-direct {
            color: var(--button-primary-text);
            background-color: var(--button-primary-bg);
            border: 2px solid var(--button-primary-border);
            padding: var(--test-space-4);
            border-radius: var(--test-radius-md);
            transition: all 0.3s ease;
            margin: 1rem;
            font-weight: 500;
            cursor: pointer;
            display: inline-block;
            text-decoration: none;
        }

        /* Button with legacy token pattern */
        .test-button-legacy {
            color: var(--test-color-primary-contrast);
            background-color: var(--color-primary, var(--test-color-primary));
            border: 2px solid var(--color-primary, var(--test-color-primary));
            padding: var(--test-space-4);
            border-radius: var(--test-radius-md);
            transition: all 0.3s ease;
            margin: 1rem;
            font-weight: 500;
            cursor: pointer;
            display: inline-block;
            text-decoration: none;
        }

        /* Primary variants */
        .test-component--primary {
            --component-bg-color: var(--test-color-primary);
            --component-text-color: var(--test-color-primary-contrast);
            --component-border-color: var(--test-color-primary);
        }

        .test-button--primary {
            --component-bg-color: var(--test-color-primary);
            --component-text-color: var(--test-color-primary-contrast);
            --component-border-color: var(--test-color-primary);
        }

        /* Debug results styling */
        .debug-results {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-radius: 8px;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 13px;
            white-space: pre-wrap;
            overflow-x: auto;
            border-left: 4px solid #e74c3c;
        }

        .controls {
            margin: 2rem 0;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3);
        }

        .inheritance-tree {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            font-family: monospace;
            font-size: 12px;
        }

        .tree-node {
            margin: 0.5rem 0;
            padding-left: 2rem;
            position: relative;
        }

        .tree-node::before {
            content: '├─ ';
            position: absolute;
            left: 0;
            color: #666;
        }

        .tree-node.resolved {
            color: #27ae60;
            font-weight: bold;
        }

        .tree-node.unresolved {
            color: #e74c3c;
            font-weight: bold;
        }

        .comparison-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        .comparison-item {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            border: 2px solid #ddd;
        }

        .comparison-item h3 {
            margin-bottom: 1rem;
            color: #2c3e50;
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
            <h1>🔬 Tokenization Inheritance Debug</h1>
            <p class="subtitle">Root Cause Analysis: Button BG vs Text Color Inconsistency</p>
            <div style="background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600; margin-top: 1rem;">
                ⚠️ CRITICAL: Production Issue Investigation
            </div>
        </div>

        <div class="debug-section">
            <h2><span class="debug-icon">🎯</span>Issue Reproduction</h2>
            <p><strong>Problem:</strong> Client-side token updates affect text colors but not button backgrounds, after reload only buttons retain styling.</p>

            <div class="comparison-grid">
                <div class="comparison-item">
                    <h3>❌ Problematic: Tokenization-Aware Component</h3>
                    <div class="test-component" data-testid="component-default">Default Component</div>
                    <div class="test-component test-component--primary" data-testid="component-primary">Primary Component</div>
                </div>

                <div class="comparison-item">
                    <h3>❌ Problematic: Tokenization-Aware Button</h3>
                    <div class="test-button" data-testid="button-default">Default Button</div>
                    <div class="test-button test-button--primary" data-testid="button-primary">Primary Button</div>
                </div>

                <div class="comparison-item">
                    <h3>🧪 Test: Direct Token Button</h3>
                    <div class="test-button-direct" data-testid="button-direct">Direct Token Button</div>
                </div>

                <div class="comparison-item">
                    <h3>🧪 Test: Legacy Token Button</h3>
                    <div class="test-button-legacy" data-testid="button-legacy">Legacy Token Button</div>
                </div>
            </div>

            <div class="controls">
                <button class="btn" onclick="analyzeTokenInheritance()">🔍 Analyze Token Inheritance</button>
                <button class="btn" onclick="simulateClientUpdate()">🎨 Simulate Client Update</button>
                <button class="btn" onclick="debugCSSVariables()">🛠️ Debug CSS Variables</button>
                <button class="btn" onclick="identifyRootCause()">⚡ Identify Root Cause</button>
            </div>

            <div class="debug-results" id="debug-output">
🔄 Debug analysis will appear here...
Click any debug button to start investigation.
            </div>
        </div>

        <div class="debug-section">
            <h2><span class="debug-icon">🌳</span>Token Inheritance Analysis</h2>
            <div class="inheritance-tree" id="inheritance-tree">
                <div class="tree-node">Inheritance tree will be generated here...</div>
            </div>
        </div>

        <div class="debug-section">
            <h2><span class="debug-icon">📊</span>Computed Values Comparison</h2>
            <div id="computed-comparison">
                <p>Computed values comparison will appear here after running analysis...</p>
            </div>
        </div>

        <div class="footer-info">
            <p><strong>Fundamentals Compliance:</strong> ✅ Temporal placeholders used | ✅ Proper file organization | ✅ Debug tool in devtools/</p>
            <p><strong>Debug Created:</strong> {CURRENT_DATE} | <strong>Purpose:</strong> Solve tokenization inheritance inconsistencies</p>
            <p><strong>Target Issue:</strong> Button BG vs Text Color update inconsistency in Visual Customizer</p>
        </div>
    </div>

    <script>
        // Fundamentals.json compliant debugging implementation
        let debugResults = {
            tokenChains: {},
            computedValues: {},
            inconsistencies: [],
            rootCause: null,
            timestamp: null
        };

        // Test token sets for debugging
        const debugTokens = {
            baseline: {
                '--test-color-primary': '#2563eb',
                '--test-color-primary-contrast': '#ffffff',
                '--test-color-surface': '#ffffff',
                '--test-color-text-primary': '#0f172a'
            },
            customized: {
                '--test-color-primary': '#dc2626',
                '--test-color-primary-contrast': '#ffffff',
                '--test-color-surface': '#f8fafc',
                '--test-color-text-primary': '#1f2937',

                // ⚡ TESTING THE FIX: Add component-level tokens that Visual Customizer should now generate
                '--component-bg-color': '#f8fafc',
                '--component-text-color': '#dc2626',
                '--component-border-color': '#f87171',
                '--component-bg-color-primary': '#dc2626',
                '--component-text-color-primary': '#ffffff',
                '--component-border-color-primary': '#dc2626',
                '--palette-primary': '#dc2626',
                '--palette-primary-contrast': '#ffffff',
                '--palette-surface': '#f8fafc',
                '--palette-text-primary': '#1f2937',
                '--palette-border': '#f87171'
            }
        };

        /**
         * Analyze complete token inheritance chain
         */
        function analyzeTokenInheritance() {
            console.log('🔍 Starting token inheritance analysis...');
            debugResults.timestamp = new Date().toISOString();

            const elements = [
                { id: 'component-default', selector: '[data-testid="component-default"]' },
                { id: 'component-primary', selector: '[data-testid="component-primary"]' },
                { id: 'button-default', selector: '[data-testid="button-default"]' },
                { id: 'button-primary', selector: '[data-testid="button-primary"]' },
                { id: 'button-direct', selector: '[data-testid="button-direct"]' },
                { id: 'button-legacy', selector: '[data-testid="button-legacy"]' }
            ];

            let analysis = '🔬 TOKEN INHERITANCE ANALYSIS\n';
            analysis += `Timestamp: {CURRENT_TIMESTAMP}\n\n`;

            elements.forEach(({ id, selector }) => {
                const element = document.querySelector(selector);
                if (element) {
                    const computed = getComputedStyle(element);
                    const inheritance = analyzeElementInheritance(element, computed);

                    debugResults.tokenChains[id] = inheritance;

                    analysis += `📝 Element: ${id}\n`;
                    analysis += `   Background: ${computed.backgroundColor}\n`;
                    analysis += `   Color: ${computed.color}\n`;
                    analysis += `   Border: ${computed.borderColor}\n`;
                    analysis += `   Token Chain: ${inheritance.chain}\n`;
                    analysis += `   Resolution: ${inheritance.resolved ? '✅ RESOLVED' : '❌ UNRESOLVED'}\n\n`;
                }
            });

            document.getElementById('debug-output').textContent = analysis;
            generateInheritanceTree();
        }

        /**
         * Analyze individual element's token inheritance
         */
        function analyzeElementInheritance(element, computed) {
            const styles = getComputedStyle(element);

            // Get all CSS custom properties that could affect this element
            const rootStyles = getComputedStyle(document.documentElement);
            const relevantTokens = [];

            for (let i = 0; i < rootStyles.length; i++) {
                const prop = rootStyles[i];
                if (prop.startsWith('--') && (
                    prop.includes('component') ||
                    prop.includes('button') ||
                    prop.includes('color') ||
                    prop.includes('test')
                )) {
                    relevantTokens.push({
                        property: prop,
                        value: rootStyles.getPropertyValue(prop).trim()
                    });
                }
            }

            // Build inheritance chain
            const chain = relevantTokens.map(token => `${token.property}: ${token.value}`).join(' → ');

            return {
                chain,
                resolved: computed.backgroundColor !== 'rgba(0, 0, 0, 0)' && computed.backgroundColor !== 'transparent',
                tokens: relevantTokens,
                computedBackground: computed.backgroundColor,
                computedColor: computed.color,
                computedBorder: computed.borderColor
            };
        }

        /**
         * Generate visual inheritance tree
         */
        function generateInheritanceTree() {
            const treeContainer = document.getElementById('inheritance-tree');
            let treeHTML = '';

            Object.entries(debugResults.tokenChains).forEach(([elementId, data]) => {
                treeHTML += `<div class="tree-node ${data.resolved ? 'resolved' : 'unresolved'}">`;
                treeHTML += `${elementId} (${data.resolved ? 'RESOLVED' : 'UNRESOLVED'})`;
                treeHTML += `</div>`;

                data.tokens.forEach(token => {
                    treeHTML += `<div class="tree-node" style="padding-left: 4rem;">`;
                    treeHTML += `${token.property}: ${token.value}`;
                    treeHTML += `</div>`;
                });
            });

            treeContainer.innerHTML = treeHTML;
        }

        /**
         * Simulate client-side token update
         */
        function simulateClientUpdate() {
            console.log('🎨 Simulating client-side token update...');

            let updateLog = '🎨 CLIENT-SIDE TOKEN UPDATE SIMULATION\n\n';
            updateLog += 'BEFORE UPDATE:\n';

            // Capture before state
            const beforeState = captureElementStates();
            updateLog += formatElementStates(beforeState);

            updateLog += '\n🔄 APPLYING CUSTOMIZED TOKENS...\n\n';

            // Apply customized tokens
            Object.entries(debugTokens.customized).forEach(([property, value]) => {
                document.documentElement.style.setProperty(property, value);
                updateLog += `Applied: ${property} = ${value}\n`;
            });

            // Force DOM recalculation
            document.body.offsetHeight;

            updateLog += '\nAFTER UPDATE:\n';

            // Capture after state
            const afterState = captureElementStates();
            updateLog += formatElementStates(afterState);

            // Compare and identify what changed
            updateLog += '\n📊 CHANGE ANALYSIS:\n';
            updateLog += compareStates(beforeState, afterState);

            document.getElementById('debug-output').textContent = updateLog;
        }

        /**
         * Capture current state of all test elements
         */
        function captureElementStates() {
            const selectors = [
                '[data-testid="component-default"]',
                '[data-testid="component-primary"]',
                '[data-testid="button-default"]',
                '[data-testid="button-primary"]',
                '[data-testid="button-direct"]',
                '[data-testid="button-legacy"]'
            ];

            const states = {};

            selectors.forEach(selector => {
                const element = document.querySelector(selector);
                if (element) {
                    const computed = getComputedStyle(element);
                    states[selector] = {
                        backgroundColor: computed.backgroundColor,
                        color: computed.color,
                        borderColor: computed.borderColor
                    };
                }
            });

            return states;
        }

        /**
         * Format element states for display
         */
        function formatElementStates(states) {
            let output = '';
            Object.entries(states).forEach(([selector, state]) => {
                const name = selector.replace('[data-testid="', '').replace('"]', '');
                output += `  ${name}:\n`;
                output += `    BG: ${state.backgroundColor}\n`;
                output += `    Color: ${state.color}\n`;
                output += `    Border: ${state.borderColor}\n`;
            });
            return output;
        }

        /**
         * Compare before and after states
         */
        function compareStates(before, after) {
            let comparison = '';

            Object.keys(before).forEach(selector => {
                const name = selector.replace('[data-testid="', '').replace('"]', '');
                const beforeState = before[selector];
                const afterState = after[selector];

                const bgChanged = beforeState.backgroundColor !== afterState.backgroundColor;
                const colorChanged = beforeState.color !== afterState.color;
                const borderChanged = beforeState.borderColor !== afterState.borderColor;

                if (bgChanged || colorChanged || borderChanged) {
                    comparison += `  ${name}: `;
                    if (bgChanged) comparison += '🔴 BG-CHANGED ';
                    if (colorChanged) comparison += '🟡 COLOR-CHANGED ';
                    if (borderChanged) comparison += '🔵 BORDER-CHANGED ';
                    comparison += '\n';
                } else {
                    comparison += `  ${name}: ⚪ NO-CHANGE\n`;
                }
            });

            return comparison;
        }

        /**
         * Debug CSS Variables
         */
        function debugCSSVariables() {
            console.log('🛠️ Debugging CSS variables...');

            const rootStyles = getComputedStyle(document.documentElement);
            let debugOutput = '🛠️ CSS VARIABLES DEBUG\n\n';

            // Find all relevant CSS custom properties
            const relevantProps = [];
            for (let i = 0; i < rootStyles.length; i++) {
                const prop = rootStyles[i];
                if (prop.startsWith('--') && (
                    prop.includes('test') ||
                    prop.includes('component') ||
                    prop.includes('button') ||
                    prop.includes('color')
                )) {
                    relevantProps.push({
                        property: prop,
                        value: rootStyles.getPropertyValue(prop).trim()
                    });
                }
            }

            debugOutput += 'ACTIVE CSS CUSTOM PROPERTIES:\n';
            relevantProps.forEach(({ property, value }) => {
                debugOutput += `  ${property}: ${value}\n`;
            });

            debugOutput += '\n🔍 TOKEN RESOLUTION ANALYSIS:\n';

            // Test token resolution
            const testElement = document.createElement('div');
            document.body.appendChild(testElement);

            // Test each component token
            const componentTokens = [
                '--component-text-color',
                '--component-bg-color',
                '--component-border-color'
            ];

            componentTokens.forEach(token => {
                testElement.style.setProperty('background-color', `var(${token})`);
                const computed = getComputedStyle(testElement);
                debugOutput += `  ${token} resolves to: ${computed.backgroundColor}\n`;
            });

            document.body.removeChild(testElement);

            document.getElementById('debug-output').textContent = debugOutput;
        }

        /**
         * Identify root cause of the issue
         */
        function identifyRootCause() {
            console.log('⚡ Identifying root cause...');

            let rootCauseAnalysis = '⚡ ROOT CAUSE ANALYSIS\n\n';

            // Run comprehensive analysis
            const beforeState = captureElementStates();

            // Apply client update
            Object.entries(debugTokens.customized).forEach(([property, value]) => {
                document.documentElement.style.setProperty(property, value);
            });
            document.body.offsetHeight;

            const afterState = captureElementStates();

            // Analyze the differences
            rootCauseAnalysis += '🔍 IDENTIFIED ISSUES:\n\n';

            let issueCount = 0;

            Object.keys(beforeState).forEach(selector => {
                const name = selector.replace('[data-testid="', '').replace('"]', '');
                const before = beforeState[selector];
                const after = afterState[selector];

                // Check if background didn't change but should have
                if (name.includes('button') || name.includes('primary')) {
                    if (before.backgroundColor === after.backgroundColor && before.backgroundColor.includes('rgb(37, 99, 235)')) {
                        rootCauseAnalysis += `❌ ISSUE ${++issueCount}: ${name}\n`;
                        rootCauseAnalysis += `   Problem: Button background not updating\n`;
                        rootCauseAnalysis += `   Expected: Red (#dc2626)\n`;
                        rootCauseAnalysis += `   Actual: Blue (${before.backgroundColor})\n`;
                        rootCauseAnalysis += `   Root Cause: CSS Custom Property inheritance chain broken\n\n`;
                    }
                }

                // Check if text color changed when it shouldn't
                if (before.color !== after.color) {
                    rootCauseAnalysis += `⚠️  INCONSISTENCY ${++issueCount}: ${name}\n`;
                    rootCauseAnalysis += `   Text color changed: ${before.color} → ${after.color}\n`;
                    rootCauseAnalysis += `   This indicates partial token inheritance\n\n`;
                }
            });

            rootCauseAnalysis += '🎯 RECOMMENDED FIXES:\n\n';
            rootCauseAnalysis += '1. Ensure ALL component tokens cascade from foundation tokens\n';
            rootCauseAnalysis += '2. Check for conflicting CSS specificity in button styles\n';
            rootCauseAnalysis += '3. Verify Visual Customizer applies ALL token dependencies\n';
            rootCauseAnalysis += '4. Add !important to critical component token assignments\n';
            rootCauseAnalysis += '5. Implement token invalidation and re-cascade system\n\n';

            rootCauseAnalysis += '📋 IMMEDIATE ACTION REQUIRED:\n';
            rootCauseAnalysis += '- Update Visual Customizer to cascade button-specific tokens\n';
            rootCauseAnalysis += '- Fix tokenization-aware component inheritance chain\n';
            rootCauseAnalysis += '- Implement comprehensive token validation system\n';

            document.getElementById('debug-output').textContent = rootCauseAnalysis;

            // Save debug results for export
            debugResults.rootCause = {
                issuesFound: issueCount,
                timestamp: new Date().toISOString(),
                recommendations: [
                    'Fix component token inheritance chain',
                    'Update Visual Customizer token cascading',
                    'Implement token validation system'
                ]
            };
        }

        // Initialize debug tool on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🔬 Tokenization inheritance debug tool initialized');
            console.log('📋 Fundamentals compliance: ✅');
            console.log('🎯 Ready to identify root cause of button BG vs text color inconsistency');

            document.getElementById('debug-output').textContent =
                '🔬 TOKENIZATION INHERITANCE DEBUG TOOL\n\n' +
                'Problem: Button backgrounds not updating with Visual Customizer\n' +
                'Symptom: Text colors change, button backgrounds remain unchanged\n' +
                'After reload: Only buttons retain styling, text reverts to default\n\n' +
                '🎯 Click "Identify Root Cause" to start comprehensive analysis\n' +
                'Debug tools will analyze CSS custom property inheritance chains\n' +
                'and identify exactly where the tokenization breaks down.\n\n' +
                'Created: {CURRENT_DATE} | Compliance: fundamentals.json ✅';
        });
    </script>
</body>
</html>
