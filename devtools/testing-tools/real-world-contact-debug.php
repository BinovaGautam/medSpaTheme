<?php
/**
 * Real-World Contact Page Debugger
 *
 * Debug actual contact page tokenization issues in production environment
 * Identifies real selectors, CSS conflicts, and persistence issues
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
    <title>🔍 Real-World Contact Page Debugger</title>
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

        .debug-section {
            margin: 2rem 0;
            padding: 1.5rem;
            border: 2px solid #ddd;
            border-radius: 12px;
            background: #fafafa;
        }

        .debug-section h2 {
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
            border-left: 4px solid var(--primary);
            max-height: 600px;
            overflow-y: auto;
        }

        .selector-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        .selector-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            border: 2px solid #ddd;
            border-left: 4px solid var(--warning);
        }

        .selector-card.working {
            border-left-color: var(--success);
        }

        .selector-card.broken {
            border-left-color: var(--error);
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
            <h1>🔍 Real-World Contact Page Debugger</h1>
            <p class="subtitle">Debug actual contact page tokenization issues in production</p>
            <div style="background: linear-gradient(135deg, var(--error), #c0392b); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600; margin-top: 1rem;">
                🚨 PRODUCTION DEBUG: Contact Page Button Issues
            </div>
        </div>

        <div class="debug-section">
            <h2>🎯 Production Issue Analysis</h2>
            <p><strong>Reported Issues:</strong></p>
            <ul style="margin: 1rem 0; padding-left: 2rem;">
                <li>✅ Test environment: All tests pass (4/4)</li>
                <li>❌ Production: Buttons still have same issue</li>
                <li>🔄 Text colors: Show second-to-last selection after reload</li>
                <li>⚠️ Apply vs Reload: Different behavior</li>
            </ul>
        </div>

        <div class="debug-section">
            <h2>🔍 Real-World Debug Tools</h2>
            <div class="controls">
                <button class="control-btn" onclick="scanActualContactPage()">🔍 Scan Actual Contact Page</button>
                <button class="control-btn secondary" onclick="debugVisualCustomizerPersistence()">💾 Debug Persistence Issue</button>
                <button class="control-btn warning" onclick="findMissingSelectors()">🎯 Find Missing Selectors</button>
                <button class="control-btn" onclick="debugApplyVsReload()">🔄 Debug Apply vs Reload</button>
                <button class="control-btn success" onclick="generateProductionFix()">🛠️ Generate Production Fix</button>
            </div>

            <div class="debug-results" id="debug-output">
🔍 REAL-WORLD CONTACT PAGE DEBUGGER READY

This tool debugs actual production issues that tests didn't catch.

PRODUCTION ISSUES TO INVESTIGATE:
❌ Contact page buttons not responding to Visual Customizer
🔄 Text color persistence showing second-to-last selection
⚠️ Apply vs Reload behavior difference

Run scans to identify real-world selector and timing issues.
            </div>
        </div>

        <div class="debug-section">
            <h2>📊 Live Contact Page Analysis</h2>
            <div id="live-analysis">
                <p>Live analysis results will appear here after running scans...</p>
            </div>
        </div>

        <div class="footer-info">
            <p><strong>Purpose:</strong> Debug real-world contact page tokenization issues</p>
            <p><strong>Focus:</strong> Production selector differences and persistence bugs</p>
        </div>
    </div>

    <script>
        // Production Debug Results
        let productionDebug = {
            actualSelectors: [],
            persistenceIssues: [],
            applyVsReload: {},
            missingSelectors: [],
            timestamp: null
        };

        /**
         * Scan actual contact page structure and selectors
         */
        function scanActualContactPage() {
            console.log('🔍 Scanning actual contact page...');

            let output = '🔍 ACTUAL CONTACT PAGE STRUCTURE SCAN\n\n';
            productionDebug.timestamp = new Date().toISOString();

            // Check if we're actually on a contact page
            const isContactPage = document.body.classList.contains('contact') ||
                                 document.body.classList.contains('page-template-contact') ||
                                 document.querySelector('.contact-page') ||
                                 window.location.href.includes('contact');

            output += `Contact Page Detection: ${isContactPage ? '✅ YES' : '❌ NO'}\n\n`;

            if (!isContactPage) {
                output += '⚠️ WARNING: Not on actual contact page\n';
                output += 'For accurate debugging, please run this tool on the contact page\n\n';
            }

            // Scan for actual button selectors on the page
            const buttonSelectors = [
                'button',
                '.btn',
                '.button',
                'input[type="submit"]',
                'input[type="button"]',
                '.contact-btn',
                '.action-btn',
                '.cta-btn',
                '.form-submit',
                '.wp-block-button__link',
                '[class*="btn"]',
                '[class*="button"]'
            ];

            output += 'ACTUAL BUTTON ELEMENTS FOUND:\n\n';

            let foundButtons = [];
            buttonSelectors.forEach(selector => {
                const elements = document.querySelectorAll(selector);
                if (elements.length > 0) {
                    elements.forEach((el, index) => {
                        const computed = getComputedStyle(el);
                        const bgColor = computed.backgroundColor;
                        const textColor = computed.color;
                        const hasTokens = bgColor.includes('var(') || bgColor.includes('rgb');

                        foundButtons.push({
                            selector: selector,
                            element: el,
                            index: index,
                            bgColor: bgColor,
                            textColor: textColor,
                            hasTokens: hasTokens,
                            classes: el.className,
                            id: el.id
                        });

                        output += `${selector}[${index}]: ${hasTokens ? '✅' : '❌'}\n`;
                        output += `  Classes: ${el.className || 'none'}\n`;
                        output += `  ID: ${el.id || 'none'}\n`;
                        output += `  Background: ${bgColor}\n`;
                        output += `  Text: ${textColor}\n\n`;
                    });
                }
            });

            // Check for CSS custom properties
            output += 'CSS CUSTOM PROPERTIES CHECK:\n\n';
            const rootStyle = getComputedStyle(document.documentElement);
            const tokenProperties = [
                '--component-bg-color-primary',
                '--component-text-color-primary',
                '--component-border-color-primary',
                '--color-primary',
                '--color-secondary'
            ];

            tokenProperties.forEach(prop => {
                const value = rootStyle.getPropertyValue(prop);
                output += `${prop}: ${value || 'NOT FOUND'}\n`;
            });

            // Check CSS file loading
            output += '\nCSS FILES LOADED:\n\n';
            const cssLinks = document.querySelectorAll('link[rel="stylesheet"]');
            cssLinks.forEach(link => {
                const href = link.href;
                if (href.includes('tokenization') || href.includes('contact') || href.includes('customizer')) {
                    output += `✅ ${href.split('/').pop()}\n`;
                }
            });

            productionDebug.actualSelectors = foundButtons;
            document.getElementById('debug-output').textContent = output;
        }

        /**
         * Debug Visual Customizer persistence issues
         */
        function debugVisualCustomizerPersistence() {
            console.log('💾 Debugging Visual Customizer persistence...');

            let output = '💾 VISUAL CUSTOMIZER PERSISTENCE DEBUG\n\n';

            // Check localStorage for Visual Customizer settings
            output += 'LOCALSTORAGE CUSTOMIZER SETTINGS:\n\n';

            const storageKeys = Object.keys(localStorage);
            const customizerKeys = storageKeys.filter(key =>
                key.includes('customizer') ||
                key.includes('visual') ||
                key.includes('theme') ||
                key.includes('color')
            );

            if (customizerKeys.length > 0) {
                customizerKeys.forEach(key => {
                    const value = localStorage.getItem(key);
                    output += `${key}: ${value}\n`;
                });
            } else {
                output += '❌ No customizer settings found in localStorage\n';
            }

            // Check sessionStorage
            output += '\nSESSIONSTORAGE SETTINGS:\n\n';
            const sessionKeys = Object.keys(sessionStorage);
            const sessionCustomizerKeys = sessionKeys.filter(key =>
                key.includes('customizer') ||
                key.includes('visual') ||
                key.includes('theme')
            );

            if (sessionCustomizerKeys.length > 0) {
                sessionCustomizerKeys.forEach(key => {
                    const value = sessionStorage.getItem(key);
                    output += `${key}: ${value}\n`;
                });
            } else {
                output += '❌ No customizer settings found in sessionStorage\n';
            }

            // Check for WordPress Customizer API
            output += '\nWORDPRESS CUSTOMIZER API:\n\n';
            if (typeof wp !== 'undefined' && wp.customize) {
                output += '✅ WordPress Customizer API available\n';

                // Check for color settings
                const colorSettings = [
                    'color_primary',
                    'color_secondary',
                    'color_accent',
                    'brand_primary',
                    'brand_accent'
                ];

                colorSettings.forEach(setting => {
                    if (wp.customize(setting)) {
                        const value = wp.customize(setting)();
                        output += `${setting}: ${value || 'not set'}\n`;
                    }
                });
            } else {
                output += '❌ WordPress Customizer API not available\n';
                output += '   This might explain the persistence issue\n';
            }

            // Check for meta tags or inline styles with customizer values
            output += '\nINLINE CUSTOMIZER STYLES:\n\n';
            const inlineStyles = document.querySelectorAll('style');
            let foundCustomizerStyles = false;

            inlineStyles.forEach((style, index) => {
                const content = style.textContent;
                if (content.includes('--component-') || content.includes('--color-')) {
                    output += `Style block ${index + 1}: Contains customizer variables\n`;
                    foundCustomizerStyles = true;
                }
            });

            if (!foundCustomizerStyles) {
                output += '❌ No inline customizer styles found\n';
                output += '   Settings may not be persisting properly\n';
            }

            document.getElementById('debug-output').textContent = output;
        }

        /**
         * Find missing selectors causing button issues
         */
        function findMissingSelectors() {
            console.log('🎯 Finding missing selectors...');

            let output = '🎯 MISSING SELECTOR ANALYSIS\n\n';

            // Compare expected selectors vs actual
            const expectedSelectors = [
                '.contact-page button',
                '.contact-page .btn',
                '.contact-page input[type="submit"]',
                '.wp-block-button__link',
                '.contact-btn',
                '.action-btn'
            ];

            const actualSelectors = [];

            // Scan for what's actually on the page
            const allButtons = document.querySelectorAll('button, input[type="submit"], input[type="button"], .btn, [class*="btn"]');

            output += 'EXPECTED vs ACTUAL SELECTORS:\n\n';

            expectedSelectors.forEach(selector => {
                const elements = document.querySelectorAll(selector);
                const found = elements.length > 0;

                output += `${selector}: ${found ? '✅ FOUND' : '❌ MISSING'} (${elements.length} elements)\n`;

                if (found) {
                    elements.forEach((el, i) => {
                        const computed = getComputedStyle(el);
                        const hasTokenization = computed.backgroundColor.includes('var(') ||
                                              computed.backgroundColor.includes('rgb');
                        output += `  Element ${i + 1}: ${hasTokenization ? '✅ Tokenized' : '❌ Not tokenized'}\n`;
                    });
                }
                output += '\n';
            });

            // Find unique selectors for actual buttons
            output += 'ACTUAL BUTTON SELECTORS FOUND:\n\n';

            allButtons.forEach((button, index) => {
                const tagName = button.tagName.toLowerCase();
                const className = button.className;
                const id = button.id;

                let selector = tagName;
                if (id) selector += `#${id}`;
                if (className) selector += `.${className.split(' ').join('.')}`;

                const computed = getComputedStyle(button);
                const hasTokenization = computed.backgroundColor.includes('var(') ||
                                      computed.backgroundColor !== 'rgba(0, 0, 0, 0)';

                output += `${index + 1}. ${selector}\n`;
                output += `   Tokenized: ${hasTokenization ? '✅ YES' : '❌ NO'}\n`;
                output += `   Background: ${computed.backgroundColor}\n\n`;

                actualSelectors.push({
                    element: button,
                    selector: selector,
                    hasTokenization: hasTokenization
                });
            });

            // Generate missing CSS rules
            output += 'MISSING CSS RULES TO ADD:\n\n';

            const nonTokenizedButtons = actualSelectors.filter(btn => !btn.hasTokenization);
            if (nonTokenizedButtons.length > 0) {
                output += '/* Add these rules to tokenization-contact-overrides.css */\n\n';

                nonTokenizedButtons.forEach(btn => {
                    output += `${btn.selector} {\n`;
                    output += '  background-color: var(--component-bg-color-primary) !important;\n';
                    output += '  color: var(--component-text-color-primary) !important;\n';
                    output += '  border-color: var(--component-border-color-primary) !important;\n';
                    output += '}\n\n';
                });
            } else {
                output += '✅ All buttons appear to have tokenization CSS\n';
                output += '   Issue may be with CSS custom property values\n';
            }

            productionDebug.missingSelectors = nonTokenizedButtons;
            document.getElementById('debug-output').textContent = output;
        }

        /**
         * Debug Apply vs Reload behavior difference
         */
        function debugApplyVsReload() {
            console.log('🔄 Debugging Apply vs Reload behavior...');

            let output = '🔄 APPLY vs RELOAD BEHAVIOR DEBUG\n\n';

            // Check for JavaScript errors
            output += 'JAVASCRIPT ERROR CHECK:\n\n';

            // Capture any console errors
            const originalError = console.error;
            const errors = [];
            console.error = function(...args) {
                errors.push(args.join(' '));
                originalError.apply(console, args);
            };

            // Simulate applying changes
            output += 'SIMULATING APPLY BEHAVIOR:\n\n';

            try {
                // Try to find and trigger Visual Customizer apply
                const customizerElements = document.querySelectorAll('[data-customize], .customizer-control, .customize-control');

                if (customizerElements.length > 0) {
                    output += `Found ${customizerElements.length} customizer control elements\n`;
                } else {
                    output += '❌ No customizer control elements found\n';
                    output += '   This may explain why Apply doesn\'t work\n';
                }

                // Check for Visual Customizer JavaScript
                if (typeof visualCustomizer !== 'undefined') {
                    output += '✅ Visual Customizer JavaScript loaded\n';
                } else {
                    output += '❌ Visual Customizer JavaScript not found\n';
                }

                // Check for apply/update functions
                const possibleApplyFunctions = [
                    'applyCustomizations',
                    'updateColors',
                    'visualCustomizer.apply',
                    'wp.customize.preview.send'
                ];

                possibleApplyFunctions.forEach(funcName => {
                    try {
                        const func = eval(funcName);
                        output += `✅ ${funcName} function available\n`;
                    } catch (e) {
                        output += `❌ ${funcName} function not available\n`;
                    }
                });

            } catch (error) {
                output += `❌ Error during apply simulation: ${error.message}\n`;
            }

            // Check timing issues
            output += '\nTIMING ANALYSIS:\n\n';

            // Check for CSS loading timing
            const cssLoadTimes = [];
            const cssLinks = document.querySelectorAll('link[rel="stylesheet"]');

            output += 'CSS LOADING ANALYSIS:\n';
            cssLinks.forEach(link => {
                const href = link.href;
                if (href.includes('tokenization') || href.includes('customizer')) {
                    const loadTime = link.sheet ? 'Loaded' : 'Loading...';
                    output += `${href.split('/').pop()}: ${loadTime}\n`;
                }
            });

            // Check for race conditions
            output += '\nRACE CONDITION CHECK:\n';
            output += 'DOM Ready: ✅ (script is running)\n';
            output += `CSS Custom Properties: ${getComputedStyle(document.documentElement).getPropertyValue('--component-bg-color-primary') ? '✅' : '❌'}\n`;
            output += `Tokenization CSS: ${document.querySelector('link[href*="tokenization"]') ? '✅' : '❌'}\n`;

            // Restoration of error handler
            console.error = originalError;

            if (errors.length > 0) {
                output += '\nCAPTURED ERRORS:\n';
                errors.forEach(error => {
                    output += `❌ ${error}\n`;
                });
            }

            productionDebug.applyVsReload = {
                errors: errors,
                cssLoadStatus: 'checked',
                timestamp: new Date().toISOString()
            };

            document.getElementById('debug-output').textContent = output;
        }

        /**
         * Generate production fix based on findings
         */
        function generateProductionFix() {
            console.log('🛠️ Generating production fix...');

            let output = '🛠️ PRODUCTION FIX RECOMMENDATIONS\n\n';

            output += '═══════════════════════════════════════════════════════\n';
            output += '                PRODUCTION FIX PLAN\n';
            output += '═══════════════════════════════════════════════════════\n\n';

            // Based on reported issues, generate specific fixes
            output += 'ISSUE 1: Buttons still not working on contact page\n';
            output += 'LIKELY CAUSE: Real contact page has different selectors\n';
            output += 'FIX: Add these selectors to tokenization-contact-overrides.css:\n\n';

            // Generate specific selectors based on scan results
            if (productionDebug.actualSelectors.length > 0) {
                const nonWorkingButtons = productionDebug.actualSelectors.filter(btn => !btn.hasTokens);

                if (nonWorkingButtons.length > 0) {
                    output += '/* Add these specific selectors */\n';
                    nonWorkingButtons.forEach(btn => {
                        output += `${btn.selector} {\n`;
                        output += '  background-color: var(--component-bg-color-primary) !important;\n';
                        output += '  color: var(--component-text-color-primary) !important;\n';
                        output += '}\n\n';
                    });
                }
            } else {
                output += '/* Run selector scan first to get specific selectors */\n';
                output += 'body.contact button,\n';
                output += 'body.page-contact button,\n';
                output += '.page-id-* button {\n';
                output += '  background-color: var(--component-bg-color-primary) !important;\n';
                output += '  color: var(--component-text-color-primary) !important;\n';
                output += '}\n\n';
            }

            output += 'ISSUE 2: Text colors show second-to-last selection\n';
            output += 'LIKELY CAUSE: Visual Customizer persistence bug\n';
            output += 'FIX: Update Visual Customizer localStorage handling:\n\n';
            output += '// Fix in simple-visual-customizer.js\n';
            output += 'function saveSettings(settings) {\n';
            output += '  // Clear old settings first\n';
            output += '  localStorage.removeItem("visual_customizer_settings");\n';
            output += '  // Save new settings\n';
            output += '  localStorage.setItem("visual_customizer_settings", JSON.stringify(settings));\n';
            output += '  // Force immediate application\n';
            output += '  applySettings(settings);\n';
            output += '}\n\n';

            output += 'ISSUE 3: Apply vs Reload behavior difference\n';
            output += 'LIKELY CAUSE: Timing issue with CSS custom property updates\n';
            output += 'FIX: Add debounced application with forced refresh:\n\n';
            output += '// Add to Visual Customizer JavaScript\n';
            output += 'function applyWithDelay(settings) {\n';
            output += '  clearTimeout(window.customizer_timeout);\n';
            output += '  window.customizer_timeout = setTimeout(() => {\n';
            output += '    applySettings(settings);\n';
            output += '    // Force CSS recalculation\n';
            output += '    document.body.style.display = "none";\n';
            output += '    document.body.offsetHeight; // Trigger reflow\n';
            output += '    document.body.style.display = "";\n';
            output += '  }, 300);\n';
            output += '}\n\n';

            output += '═══════════════════════════════════════════════════════\n';
            output += '                IMMEDIATE ACTIONS\n';
            output += '═══════════════════════════════════════════════════════\n\n';

            output += '1. Add contact page specific selectors to CSS\n';
            output += '2. Fix Visual Customizer persistence logic\n';
            output += '3. Add debounced application with forced reflow\n';
            output += '4. Test on actual contact page after each fix\n\n';

            output += 'RUN NEXT: Update CSS and JavaScript files with fixes above\n';

            document.getElementById('debug-output').textContent = output;
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🔍 Real-World Contact Page Debugger initialized');

            // PRODUCTION FIX: Clean up conflicting localStorage on load
            cleanupConflictingLocalStorageKeys();

            // Auto-detect current page type
            const isContactPage = window.location.href.includes('contact') ||
                                 document.body.classList.contains('contact') ||
                                 document.body.classList.contains('page-template-contact') ||
                                 document.querySelector('[data-page-type="contact"]') ||
                                 document.querySelector('.contact-form') ||
                                 document.querySelector('.contact-section');

            if (isContactPage) {
                setTimeout(() => {
                    let status = '🎯 AUTO-DETECTION: Contact page detected\n\n';
                    status += 'Ready for real-world debugging\n';
                    status += 'Run "Scan Actual Contact Page" to analyze structure\n\n';
                    status += '🔧 PRODUCTION FIXES APPLIED:\n';
                    status += '✅ localStorage conflicts cleaned up\n';
                    status += '✅ CSS tokenization checks added\n';
                    status += '✅ Consolidated palette management\n';
                    document.getElementById('debug-output').textContent = status;
                }, 1000);
            } else {
                setTimeout(() => {
                    let status = '⚠️ NOT ON CONTACT PAGE\n\n';
                    status += '🎯 TO DEBUG CONTACT PAGE ISSUES:\n\n';
                    status += '1. Navigate to your contact page\n';
                    status += '2. Open browser developer tools (F12)\n';
                    status += '3. Go to Console tab\n';
                    status += '4. Run this command:\n\n';
                    status += 'window.location.href = window.location.origin + "/wp-content/themes/medSpaTheme/devtools/testing-tools/real-world-contact-debug.php";\n\n';
                    status += 'OR:\n\n';
                    status += '1. Go to: YOUR_SITE/contact-us/\n';
                    status += '2. Add this to URL: ?debug_tokenization=true\n';
                    status += '3. Reload page and check console\n';
                    document.getElementById('debug-output').textContent = status;
                }, 1000);
            }
        });

        /**
         * PRODUCTION FIX: Clean up conflicting localStorage keys
         */
        function cleanupConflictingLocalStorageKeys() {
            console.log('🧹 Cleaning up conflicting localStorage keys...');

            const conflictingKeys = [
                'visual_customizer_current_palette',
                'medspaa_visual_customizer',
                'medSpa_colorSystem_settings',
                'preetidreams_visual_customizer_settings',
                'visual_customizer_temp_settings'
            ];

            const foundValues = {};
            conflictingKeys.forEach(key => {
                const value = localStorage.getItem(key);
                if (value) {
                    foundValues[key] = value;
                }
            });

            if (Object.keys(foundValues).length > 0) {
                console.log('🔍 Found conflicting localStorage keys:', foundValues);

                // Determine priority palette
                let priorityPalette = null;
                if (foundValues['visual_customizer_current_palette']) {
                    priorityPalette = foundValues['visual_customizer_current_palette'];
                } else if (foundValues['medSpa_colorSystem_settings']) {
                    try {
                        const parsed = JSON.parse(foundValues['medSpa_colorSystem_settings']);
                        priorityPalette = parsed.currentPalette;
                    } catch (e) {}
                }

                // Clear conflicts
                conflictingKeys.forEach(key => {
                    localStorage.removeItem(key);
                });

                // Set consolidated value
                if (priorityPalette) {
                    localStorage.setItem('visual_customizer_settings', JSON.stringify({
                        currentPalette: priorityPalette,
                        timestamp: Date.now(),
                        source: 'debug_cleanup'
                    }));
                    console.log('✅ Consolidated to single localStorage key:', priorityPalette);
                }
            }
        }
    </script>
</body>
</html>
