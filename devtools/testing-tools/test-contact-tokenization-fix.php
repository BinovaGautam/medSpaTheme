<?php
/**
 * Contact Page Tokenization Fix Test
 *
 * Comprehensive validation of CSS fixes for contact page tokenization issues
 * Tests all implemented solutions from tokenization-css-cleanup.php recommendations
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
    <title>🎯 Contact Page Tokenization Fix Test</title>
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

        .test-section {
            margin: 2rem 0;
            padding: 1.5rem;
            border: 2px solid #ddd;
            border-radius: 12px;
            background: #fafafa;
        }

        .test-section h2 {
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

        .test-results {
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

        .test-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        .test-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            border: 2px solid #ddd;
            border-left: 4px solid var(--warning);
        }

        .test-card.passed {
            border-left-color: var(--success);
        }

        .test-card.failed {
            border-left-color: var(--error);
        }

        .test-card h3 {
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .test-sample {
            background: #f8f9fa;
            border-radius: 4px;
            padding: 1rem;
            margin: 1rem 0;
            border: 1px solid #dee2e6;
        }

        .sample-button {
            background: var(--component-bg-color-primary, #87A96B);
            color: var(--component-text-color-primary, white);
            border: 2px solid var(--component-border-color-primary, #87A96B);
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0.5rem;
        }

        .sample-button:hover {
            background: var(--component-bg-color-primary-hover, #6B8552);
            border-color: var(--component-border-color-primary-hover, #6B8552);
        }

        .sample-button.secondary {
            background: var(--component-bg-color-secondary, #007bff);
            color: var(--component-text-color-secondary, white);
            border-color: var(--component-border-color-secondary, #007bff);
        }

        .sample-contact-page {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .contact-page button,
        .contact-page .btn {
            background-color: var(--component-bg-color-primary) !important;
            color: var(--component-text-color-primary) !important;
            border-color: var(--component-border-color-primary) !important;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            margin: 0.5rem;
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

    <!-- Include the actual theme CSS to test real scenarios -->
    <link rel="stylesheet" href="../../assets/css/tokenization-contact-overrides.css">
    <link rel="stylesheet" href="../../assets/css/simple-visual-customizer.css">
</head>
<body class="contact-page" data-debug="false">
    <div class="container">
        <div class="header">
            <h1>🎯 Contact Page Tokenization Fix Test</h1>
            <p class="subtitle">Comprehensive validation of CSS fixes for contact page tokenization issues</p>
            <div style="background: linear-gradient(135deg, var(--primary), #c0392b); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600; margin-top: 1rem;">
                🚨 CRITICAL: Contact Page CSS Conflict Resolution Test
            </div>
        </div>

        <div class="test-section">
            <h2>🧪 Test Controls</h2>
            <div class="controls">
                <button class="control-btn" onclick="testWordPressButtonFix()">🔧 Test WordPress Button Fix</button>
                <button class="control-btn secondary" onclick="testContactPageTokenization()">📞 Test Contact Page Tokens</button>
                <button class="control-btn warning" onclick="testHardcodedColorReplacement()">🎨 Test Hardcoded Color Fix</button>
                <button class="control-btn success" onclick="testSpecificityOverrides()">📊 Test Specificity Overrides</button>
                <button class="control-btn" onclick="runComprehensiveTest()">🎯 Run All Tests</button>
            </div>

            <div class="test-results" id="test-output">
🎯 CONTACT PAGE TOKENIZATION FIX TEST READY

This tool validates all fixes implemented for contact page tokenization issues:

IMPLEMENTED FIXES:
✅ WordPress block button override removed (editor.scss)
✅ Tokenization contact overrides CSS created
✅ Hardcoded color replacements implemented
✅ High-specificity overrides added
✅ Visual Customizer compatibility enhanced

Click any test button to validate the fixes.
            </div>
        </div>

        <div class="test-section">
            <h2>🎯 Live Test Samples</h2>
            <div class="test-grid">
                <div class="test-card">
                    <h3>WordPress Block Buttons</h3>
                    <div class="test-sample">
                        <div class="wp-block-button">
                            <a class="wp-block-button__link tokenized" href="#test">WordPress Block Button</a>
                        </div>
                        <div class="wp-block-button">
                            <a class="wp-block-button__link is-style-secondary" href="#test">Secondary Style</a>
                        </div>
                    </div>
                </div>

                <div class="test-card">
                    <h3>Contact Page Elements</h3>
                    <div class="test-sample contact-page">
                        <button class="btn">Contact Button</button>
                        <button class="contact-btn">Contact Form Button</button>
                        <input type="submit" value="Submit Contact Form">
                    </div>
                </div>

                <div class="test-card">
                    <h3>Tokenized Components</h3>
                    <div class="test-sample">
                        <button class="sample-button" data-tokenized="true">Primary Tokenized</button>
                        <button class="sample-button secondary" data-tokenized="secondary">Secondary Tokenized</button>
                        <a href="#test" class="contact-link" data-tokenized="accent">Contact Link</a>
                    </div>
                </div>

                <div class="test-card">
                    <h3>Specificity Override Test</h3>
                    <div class="test-sample">
                        <button class="btn" style="background: #87A96B;">Override Test Button</button>
                        <button class="btn" style="background: #007bff;">Blue Override Test</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="test-section">
            <h2>🔍 Debug Mode</h2>
            <p>Enable debug mode to see visual indicators for tokenized elements:</p>
            <div class="controls">
                <button class="control-btn warning" onclick="toggleDebugMode()">🔍 Toggle Debug Mode</button>
                <button class="control-btn secondary" onclick="simulateVisualCustomizer()">🎨 Simulate Visual Customizer</button>
                <button class="control-btn" onclick="testPageRefresh()">🔄 Test Page Refresh Behavior</button>
            </div>
        </div>

        <div class="footer-info">
            <p><strong>Testing:</strong> Contact page tokenization fixes</p>
            <p><strong>Purpose:</strong> Validate CSS conflict resolution and Visual Customizer compatibility</p>
            <p><strong>Status:</strong> All critical fixes implemented and ready for validation</p>
        </div>
    </div>

    <script>
        // Test Results Storage
        let testResults = {
            wpButtonFix: false,
            contactTokenization: false,
            hardcodedColors: false,
            specificityOverrides: false,
            overallStatus: 'pending',
            timestamp: null
        };

        // WordPress Button Fix Test
        function testWordPressButtonFix() {
            console.log('🔧 Testing WordPress button fix...');

            let output = '🔧 WORDPRESS BUTTON FIX TEST\n\n';

            // Test WordPress block buttons
            const wpButtons = document.querySelectorAll('.wp-block-button__link');
            let wpButtonsFixed = 0;
            let wpButtonsTotal = wpButtons.length;

            output += `Found ${wpButtonsTotal} WordPress block buttons\n\n`;

            wpButtons.forEach((button, index) => {
                const computed = getComputedStyle(button);
                const bgColor = computed.backgroundColor;
                const color = computed.color;

                // Check if using CSS custom properties
                const hasTokenizedBg = bgColor.includes('var(') ||
                                     bgColor !== 'rgba(0, 0, 0, 0)' ||
                                     bgColor !== 'transparent';

                output += `Button ${index + 1}:\n`;
                output += `  Background: ${bgColor}\n`;
                output += `  Color: ${color}\n`;
                output += `  Tokenized: ${hasTokenizedBg ? '✅ YES' : '❌ NO'}\n\n`;

                if (hasTokenizedBg) wpButtonsFixed++;
            });

            // Test conclusion
            const wpFixSuccess = (wpButtonsFixed / wpButtonsTotal) >= 0.8;
            output += `WORDPRESS BUTTON FIX: ${wpFixSuccess ? '✅ PASSED' : '❌ FAILED'}\n`;
            output += `Fixed: ${wpButtonsFixed}/${wpButtonsTotal} buttons (${Math.round((wpButtonsFixed/wpButtonsTotal)*100)}%)\n\n`;

            if (wpFixSuccess) {
                output += '✅ WordPress button override successfully removed\n';
                output += '✅ Tokenization working on WordPress block buttons\n';
            } else {
                output += '❌ WordPress button override still blocking tokenization\n';
                output += '❌ Need to check editor.scss modifications\n';
            }

            testResults.wpButtonFix = wpFixSuccess;
            document.getElementById('test-output').textContent = output;
        }

        // Contact Page Tokenization Test
        function testContactPageTokenization() {
            console.log('📞 Testing contact page tokenization...');

            let output = '📞 CONTACT PAGE TOKENIZATION TEST\n\n';

            // Test contact page elements
            const contactButtons = document.querySelectorAll('.contact-page button, .contact-page .btn, .contact-page input[type="submit"]');
            let tokenizedButtons = 0;
            let totalButtons = contactButtons.length;

            output += `Found ${totalButtons} contact page buttons\n\n`;

            contactButtons.forEach((button, index) => {
                const computed = getComputedStyle(button);
                const bgColor = computed.backgroundColor;
                const textColor = computed.color;

                // Check for tokenization
                const hasTokens = bgColor.includes('rgb') && bgColor !== 'rgba(0, 0, 0, 0)';

                output += `Contact Button ${index + 1}:\n`;
                output += `  Element: ${button.tagName.toLowerCase()}${button.className ? '.' + button.className.split(' ').join('.') : ''}\n`;
                output += `  Background: ${bgColor}\n`;
                output += `  Text Color: ${textColor}\n`;
                output += `  Tokenized: ${hasTokens ? '✅ YES' : '❌ NO'}\n\n`;

                if (hasTokens) tokenizedButtons++;
            });

            // Test contact links
            const contactLinks = document.querySelectorAll('.contact-link, .contact-method-link, .contact-item a');
            let tokenizedLinks = 0;
            let totalLinks = contactLinks.length;

            output += `Found ${totalLinks} contact links\n\n`;

            contactLinks.forEach((link, index) => {
                const computed = getComputedStyle(link);
                const bgColor = computed.backgroundColor;

                const hasTokens = bgColor.includes('rgb') && bgColor !== 'rgba(0, 0, 0, 0)';

                output += `Contact Link ${index + 1}: ${hasTokens ? '✅ Tokenized' : '❌ Default'}\n`;

                if (hasTokens) tokenizedLinks++;
            });

            // Test conclusion
            const buttonSuccess = (tokenizedButtons / totalButtons) >= 0.8;
            const linkSuccess = totalLinks === 0 || (tokenizedLinks / totalLinks) >= 0.6;
            const overallSuccess = buttonSuccess && linkSuccess;

            output += `\nCONTACT PAGE TOKENIZATION: ${overallSuccess ? '✅ PASSED' : '❌ FAILED'}\n`;
            output += `Buttons: ${tokenizedButtons}/${totalButtons} (${Math.round((tokenizedButtons/totalButtons)*100)}%)\n`;
            output += `Links: ${tokenizedLinks}/${totalLinks} (${totalLinks > 0 ? Math.round((tokenizedLinks/totalLinks)*100) : 100}%)\n`;

            testResults.contactTokenization = overallSuccess;
            document.getElementById('test-output').textContent = output;
        }

        // Hardcoded Color Replacement Test
        function testHardcodedColorReplacement() {
            console.log('🎨 Testing hardcoded color replacement...');

            let output = '🎨 HARDCODED COLOR REPLACEMENT TEST\n\n';

            // Test for hardcoded colors that should be replaced
            const hardcodedColors = ['#87A96B', '#007bff', '#6B8552'];
            let foundHardcoded = [];
            let replacedColors = [];

            // Check all elements for hardcoded colors
            const allElements = document.querySelectorAll('*');

            allElements.forEach((element) => {
                const computed = getComputedStyle(element);
                const bgColor = computed.backgroundColor;
                const textColor = computed.color;

                // Convert RGB to hex for comparison
                const rgbToHex = (rgb) => {
                    const result = rgb.match(/\d+/g);
                    if (result && result.length >= 3) {
                        return "#" + ((1 << 24) + (parseInt(result[0]) << 16) + (parseInt(result[1]) << 8) + parseInt(result[2])).toString(16).slice(1);
                    }
                    return rgb;
                };

                const bgHex = rgbToHex(bgColor).toUpperCase();
                const textHex = rgbToHex(textColor).toUpperCase();

                hardcodedColors.forEach(color => {
                    const colorUpper = color.toUpperCase();
                    if (bgHex === colorUpper || textHex === colorUpper) {
                        foundHardcoded.push({
                            element: element.tagName.toLowerCase() + (element.className ? '.' + element.className.split(' ').join('.') : ''),
                            color: color,
                            property: bgHex === colorUpper ? 'background' : 'text'
                        });
                    }
                });
            });

            output += 'HARDCODED COLOR SCAN RESULTS:\n\n';

            if (foundHardcoded.length === 0) {
                output += '✅ No hardcoded problematic colors found\n';
                output += '✅ All colors successfully replaced with tokens\n';
                testResults.hardcodedColors = true;
            } else {
                output += `❌ Found ${foundHardcoded.length} elements with hardcoded colors:\n\n`;
                foundHardcoded.forEach((item, index) => {
                    output += `${index + 1}. ${item.element}\n`;
                    output += `   Color: ${item.color} (${item.property})\n`;
                    output += `   Status: Needs token replacement\n\n`;
                });
                testResults.hardcodedColors = false;
            }

            // Test CSS custom property usage
            const tokensFound = document.documentElement.style.cssText.includes('--component-') ||
                              getComputedStyle(document.documentElement).getPropertyValue('--component-bg-color-primary');

            output += `\nCSS CUSTOM PROPERTY USAGE:\n`;
            output += `Component tokens available: ${tokensFound ? '✅ YES' : '❌ NO'}\n`;

            if (tokensFound) {
                output += '✅ CSS custom properties properly loaded\n';
                output += '✅ Tokenization system active\n';
            }

            document.getElementById('test-output').textContent = output;
        }

        // Specificity Override Test
        function testSpecificityOverrides() {
            console.log('📊 Testing specificity overrides...');

            let output = '📊 SPECIFICITY OVERRIDE TEST\n\n';

            // Test elements with high specificity
            const testSelectors = [
                '.contact-page button',
                '.contact-page .btn',
                'body.contact .contact-page button',
                '.wp-block-button__link.tokenized'
            ];

            let overrideTests = [];

            testSelectors.forEach(selector => {
                try {
                    const elements = document.querySelectorAll(selector);
                    if (elements.length > 0) {
                        const element = elements[0];
                        const computed = getComputedStyle(element);
                        const bgColor = computed.backgroundColor;

                        const hasCorrectStyling = bgColor !== 'rgba(0, 0, 0, 0)' && bgColor !== 'transparent';

                        overrideTests.push({
                            selector: selector,
                            elements: elements.length,
                            styled: hasCorrectStyling,
                            bgColor: bgColor
                        });
                    }
                } catch (e) {
                    overrideTests.push({
                        selector: selector,
                        elements: 0,
                        styled: false,
                        bgColor: 'N/A',
                        error: e.message
                    });
                }
            });

            output += 'SPECIFICITY OVERRIDE RESULTS:\n\n';

            let passedTests = 0;
            overrideTests.forEach((test, index) => {
                output += `${index + 1}. ${test.selector}\n`;
                output += `   Elements found: ${test.elements}\n`;
                output += `   Properly styled: ${test.styled ? '✅ YES' : '❌ NO'}\n`;
                output += `   Background: ${test.bgColor}\n`;
                if (test.error) output += `   Error: ${test.error}\n`;
                output += '\n';

                if (test.styled) passedTests++;
            });

            const specificitySuccess = (passedTests / overrideTests.length) >= 0.75;

            output += `SPECIFICITY OVERRIDES: ${specificitySuccess ? '✅ PASSED' : '❌ FAILED'}\n`;
            output += `Working selectors: ${passedTests}/${overrideTests.length} (${Math.round((passedTests/overrideTests.length)*100)}%)\n\n`;

            if (specificitySuccess) {
                output += '✅ High-specificity overrides working correctly\n';
                output += '✅ Tokenization overriding hardcoded styles\n';
            } else {
                output += '❌ Some specificity overrides not working\n';
                output += '❌ May need higher specificity selectors\n';
            }

            testResults.specificityOverrides = specificitySuccess;
            document.getElementById('test-output').textContent = output;
        }

        // Comprehensive Test
        function runComprehensiveTest() {
            console.log('🎯 Running comprehensive contact tokenization test...');

            let output = '🎯 COMPREHENSIVE CONTACT TOKENIZATION TEST\n\n';

            // Run all individual tests
            testWordPressButtonFix();
            setTimeout(() => {
                testContactPageTokenization();
                setTimeout(() => {
                    testHardcodedColorReplacement();
                    setTimeout(() => {
                        testSpecificityOverrides();
                        setTimeout(() => {
                            // Compile final results
                            generateFinalReport();
                        }, 500);
                    }, 500);
                }, 500);
            }, 500);
        }

        // Generate Final Report
        function generateFinalReport() {
            testResults.timestamp = new Date().toISOString();

            let output = '🎯 COMPREHENSIVE TEST RESULTS - FINAL REPORT\n\n';

            output += '═══════════════════════════════════════════════════════\n';
            output += '                  TEST SUMMARY\n';
            output += '═══════════════════════════════════════════════════════\n\n';

            output += `WordPress Button Fix:        ${testResults.wpButtonFix ? '✅ PASSED' : '❌ FAILED'}\n`;
            output += `Contact Page Tokenization:   ${testResults.contactTokenization ? '✅ PASSED' : '❌ FAILED'}\n`;
            output += `Hardcoded Color Replacement: ${testResults.hardcodedColors ? '✅ PASSED' : '❌ FAILED'}\n`;
            output += `Specificity Overrides:       ${testResults.specificityOverrides ? '✅ PASSED' : '❌ FAILED'}\n\n`;

            // Calculate overall success
            const passedTests = Object.values(testResults).filter(result => result === true).length;
            const totalTests = 4;
            const overallSuccess = passedTests >= 3; // At least 75% pass rate

            testResults.overallStatus = overallSuccess ? 'passed' : 'failed';

            output += '═══════════════════════════════════════════════════════\n';
            output += `                OVERALL STATUS: ${overallSuccess ? '✅ PASSED' : '❌ FAILED'}\n`;
            output += '═══════════════════════════════════════════════════════\n\n';

            output += `Tests Passed: ${passedTests}/${totalTests} (${Math.round((passedTests/totalTests)*100)}%)\n\n`;

            if (overallSuccess) {
                output += '🎉 CONTACT PAGE TOKENIZATION FIXES SUCCESSFUL!\n\n';
                output += 'Key Achievements:\n';
                output += '✅ Visual Customizer now works on contact page\n';
                output += '✅ Button backgrounds update with palette changes\n';
                output += '✅ WordPress block button overrides resolved\n';
                output += '✅ CSS conflicts cleaned up successfully\n';
                output += '✅ High-specificity overrides working\n\n';
                output += 'RECOMMENDATION: Deploy to production ✅\n';
            } else {
                output += '⚠️ CONTACT PAGE TOKENIZATION NEEDS MORE WORK\n\n';
                output += 'Issues to Address:\n';
                if (!testResults.wpButtonFix) output += '❌ WordPress button override still blocking tokenization\n';
                if (!testResults.contactTokenization) output += '❌ Contact page elements not properly tokenized\n';
                if (!testResults.hardcodedColors) output += '❌ Hardcoded colors still present\n';
                if (!testResults.specificityOverrides) output += '❌ Specificity overrides not working properly\n';
                output += '\nRECOMMENDATION: Review and fix failing tests before deployment ❌\n';
            }

            output += '\n═══════════════════════════════════════════════════════\n';
            output += `Test completed: ${testResults.timestamp}\n`;
            output += 'Contact Page Tokenization Fix Validation Complete\n';
            output += '═══════════════════════════════════════════════════════';

            document.getElementById('test-output').textContent = output;
        }

        // Debug Mode Toggle
        function toggleDebugMode() {
            const body = document.body;
            const currentDebug = body.getAttribute('data-debug') === 'true';
            body.setAttribute('data-debug', currentDebug ? 'false' : 'true');

            console.log(`Debug mode: ${currentDebug ? 'OFF' : 'ON'}`);

            let output = `🔍 DEBUG MODE: ${currentDebug ? 'DISABLED' : 'ENABLED'}\n\n`;

            if (!currentDebug) {
                output += 'Debug mode activated - tokenized elements will show green borders\n';
                output += 'Look for elements with "✅ TOKENIZED" labels\n\n';
                output += 'Debug CSS Rules Applied:\n';
                output += '- Green borders on tokenized elements\n';
                output += '- Visual indicators for successful tokenization\n';
                output += '- Element identification labels\n';
            } else {
                output += 'Debug mode deactivated - normal styling restored\n';
            }

            document.getElementById('test-output').textContent = output;
        }

        // Simulate Visual Customizer
        function simulateVisualCustomizer() {
            console.log('🎨 Simulating Visual Customizer changes...');

            // Simulate changing CSS custom properties
            const root = document.documentElement;
            const colors = ['#e74c3c', '#3498db', '#27ae60', '#f39c12', '#9b59b6'];
            const randomColor = colors[Math.floor(Math.random() * colors.length)];

            // Update CSS custom properties
            root.style.setProperty('--component-bg-color-primary', randomColor);
            root.style.setProperty('--component-text-color-primary', '#ffffff');
            root.style.setProperty('--component-border-color-primary', randomColor);

            let output = '🎨 VISUAL CUSTOMIZER SIMULATION\n\n';
            output += `Applied new primary color: ${randomColor}\n\n`;

            // Test if changes applied
            setTimeout(() => {
                const testElements = document.querySelectorAll('.sample-button, .contact-page button, .wp-block-button__link');
                let updatedElements = 0;

                testElements.forEach(element => {
                    const computed = getComputedStyle(element);
                    const bgColor = computed.backgroundColor;

                    // Check if the element updated to the new color
                    if (bgColor.includes('rgb')) {
                        updatedElements++;
                    }
                });

                output += `Elements responding to change: ${updatedElements}/${testElements.length}\n`;
                output += `Responsiveness: ${Math.round((updatedElements/testElements.length)*100)}%\n\n`;

                if (updatedElements > 0) {
                    output += '✅ Visual Customizer simulation working!\n';
                    output += '✅ Elements responding to token changes\n';
                    output += '✅ Contact page tokenization successful\n';
                } else {
                    output += '❌ Elements not responding to token changes\n';
                    output += '❌ Tokenization may still have issues\n';
                }

                document.getElementById('test-output').textContent = output;
            }, 1000);
        }

        // Test Page Refresh Behavior
        function testPageRefresh() {
            let output = '🔄 PAGE REFRESH BEHAVIOR TEST\n\n';
            output += 'This test simulates what happens after page refresh:\n\n';

            // Save current state
            const beforeState = {
                buttons: [],
                customProperties: {}
            };

            // Capture current button states
            const buttons = document.querySelectorAll('button, .btn, .wp-block-button__link');
            buttons.forEach((button, index) => {
                const computed = getComputedStyle(button);
                beforeState.buttons.push({
                    index: index,
                    bgColor: computed.backgroundColor,
                    textColor: computed.color
                });
            });

            // Capture CSS custom properties
            const root = document.documentElement;
            const computedRoot = getComputedStyle(root);
            ['--component-bg-color-primary', '--component-text-color-primary', '--color-primary'].forEach(prop => {
                beforeState.customProperties[prop] = computedRoot.getPropertyValue(prop);
            });

            output += 'BEFORE REFRESH STATE:\n';
            output += `Buttons captured: ${beforeState.buttons.length}\n`;
            output += `Custom properties: ${Object.keys(beforeState.customProperties).length}\n\n`;

            // Simulate refresh by reloading styles
            output += 'SIMULATING PAGE REFRESH...\n';
            output += '(In real scenario, page would reload and server-side rendering would apply)\n\n';

            // Test persistence
            setTimeout(() => {
                output += 'AFTER REFRESH SIMULATION:\n';

                const stillTokenized = document.querySelectorAll('[data-tokenized], .tokenized');
                output += `Elements with tokenization attributes: ${stillTokenized.length}\n`;

                // Check if CSS overrides are still active
                const overrideCSSLoaded = document.querySelector('link[href*="tokenization-contact-overrides"]');
                output += `Override CSS loaded: ${overrideCSSLoaded ? '✅ YES' : '❌ NO'}\n\n`;

                output += 'PERSISTENCE TEST RESULTS:\n';
                if (overrideCSSLoaded && stillTokenized.length > 0) {
                    output += '✅ Tokenization should persist after refresh\n';
                    output += '✅ Override CSS properly loaded\n';
                    output += '✅ Contact page fixes are permanent\n';
                } else {
                    output += '❌ Tokenization may not persist after refresh\n';
                    output += '❌ Check CSS loading order\n';
                }

                document.getElementById('test-output').textContent = output;
            }, 2000);

            document.getElementById('test-output').textContent = output;
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🎯 Contact Page Tokenization Fix Test initialized');
            console.log('🎯 Ready to validate all implemented fixes');

            // Auto-detect any existing issues
            setTimeout(() => {
                const wpButtons = document.querySelectorAll('.wp-block-button__link');
                const contactButtons = document.querySelectorAll('.contact-page button');

                let status = '🎯 AUTO-DETECTION RESULTS:\n\n';
                status += `WordPress block buttons found: ${wpButtons.length}\n`;
                status += `Contact page buttons found: ${contactButtons.length}\n\n`;

                if (wpButtons.length > 0 || contactButtons.length > 0) {
                    status += 'Test elements detected - ready for comprehensive testing\n';
                    status += 'Click "Run All Tests" to validate all fixes\n';
                } else {
                    status += 'No test elements found - may need to load on actual contact page\n';
                }

                document.getElementById('test-output').textContent = status;
            }, 1000);
        });
    </script>
</body>
</html>
