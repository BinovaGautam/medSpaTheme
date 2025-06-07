<?php
/**
 * Real-time Visual Customizer Diagnostic
 * Diagnoses why real-time changes are not working
 */

// WordPress configuration check
$wp_config_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
if (file_exists($wp_config_path)) {
    require_once($wp_config_path);
    require_once(ABSPATH . 'wp-load.php');
} else {
    die('WordPress not found. Please ensure this file is in the correct directory.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔬 Real-time Visual Customizer Diagnostic</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f8fafc;
            color: #1e293b;
            line-height: 1.6;
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 30px 0;
            text-align: center;
            margin-bottom: 30px;
        }
        .diagnostic-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .diagnostic-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .section-title {
            font-size: 18px;
            margin-bottom: 15px;
            color: #1e293b;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
        }
        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .status-ok { color: #059669; font-weight: bold; }
        .status-error { color: #dc2626; font-weight: bold; }
        .status-warning { color: #d97706; font-weight: bold; }
        .test-button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin: 5px;
        }
        .test-button:hover { background: #1d4ed8; }
        .test-result {
            background: #f1f5f9;
            padding: 15px;
            border-radius: 6px;
            margin: 10px 0;
            font-family: monospace;
            font-size: 12px;
        }
        .preview-element {
            background: var(--color-primary-navy, #2c3e50);
            color: var(--color-neutral-white, #ffffff);
            padding: 20px;
            border-radius: 8px;
            margin: 10px 0;
            transition: all 0.3s ease;
        }
        .cta-button {
            background: var(--color-secondary-peach, #f39c12);
            color: var(--color-neutral-white, #ffffff);
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            margin: 10px;
            transition: all 0.3s ease;
        }
        .nav-item {
            color: var(--color-primary-teal, #16a085);
            text-decoration: none;
            padding: 8px 16px;
            margin: 5px;
            border-radius: 4px;
            display: inline-block;
            transition: all 0.3s ease;
        }
        .nav-item:hover {
            background: rgba(var(--color-primary-teal-rgb, 22, 160, 133), 0.1);
        }
        .palette-selector {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .palette-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        .palette-btn {
            padding: 8px 16px;
            border: 2px solid #e2e8f0;
            background: white;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .palette-btn:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }
        .palette-btn.active {
            border-color: #059669;
            background: #f0fdf4;
            color: #059669;
            font-weight: bold;
        }
        .debug-log {
            background: #1e293b;
            color: #f1f5f9;
            padding: 15px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 11px;
            max-height: 300px;
            overflow-y: auto;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔬 Real-time Visual Customizer Diagnostic</h1>
        <p>Testing live preview functionality and system integration</p>
    </div>

    <div class="container">
        <div class="diagnostic-grid">
            <div class="diagnostic-section">
                <h3 class="section-title">🎨 System Status</h3>
                <div id="system-status">
                    <div class="status-item">
                        <span>WordPress Integration:</span>
                        <span id="wp-status" class="status-warning">⏳ Testing...</span>
                    </div>
                    <div class="status-item">
                        <span>JavaScript Systems:</span>
                        <span id="js-status" class="status-warning">⏳ Testing...</span>
                    </div>
                    <div class="status-item">
                        <span>LivePreviewSystem:</span>
                        <span id="live-preview-status" class="status-warning">⏳ Testing...</span>
                    </div>
                    <div class="status-item">
                        <span>Current Palette:</span>
                        <span id="current-palette-status" class="status-warning">⏳ Loading...</span>
                    </div>
                    <div class="status-item">
                        <span>Body Class Active:</span>
                        <span id="body-class-status" class="status-warning">⏳ Checking...</span>
                    </div>
                </div>
            </div>

            <div class="diagnostic-section">
                <h3 class="section-title">⚡ Real-time Tests</h3>
                <button class="test-button" onclick="testLivePreview()">Test Live Preview</button>
                <button class="test-button" onclick="testAJAXConnection()">Test AJAX</button>
                <button class="test-button" onclick="testCSSInjection()">Test CSS Injection</button>
                <button class="test-button" onclick="testCSSPersistence()">Test CSS Persistence Fix</button>
                <button class="test-button" onclick="resetAllStyles()">Reset All Styles</button>

                <div id="test-results" class="test-result" style="display: none;">
                    Test results will appear here...
                </div>
            </div>
        </div>

        <div class="palette-selector">
            <h3>🎯 Live Palette Testing</h3>
            <p>Select palettes below to test real-time changes:</p>

            <div class="palette-buttons" id="palette-buttons">
                <!-- Palette buttons will be populated here -->
            </div>
        </div>

        <div class="diagnostic-section">
            <h3 class="section-title">🖼️ Preview Elements</h3>
            <p>These elements should change colors in real-time when palettes are selected:</p>

            <div class="preview-element">
                <h4>Header Preview</h4>
                <p>This simulates the site header with navigation.</p>
                <a href="#" class="nav-item">Home</a>
                <a href="#" class="nav-item">Services</a>
                <a href="#" class="nav-item">About</a>
                <a href="#" class="nav-item">Contact</a>
            </div>

            <div style="background: var(--color-neutral-light, #ecf0f1); padding: 20px; border-radius: 8px; margin: 10px 0;">
                <h4 style="color: var(--color-primary-navy, #2c3e50);">Content Section</h4>
                <p style="color: var(--color-neutral-dark, #34495e);">This represents page content with text and buttons.</p>
                <button class="cta-button">Book Consultation</button>
                <button class="cta-button">Learn More</button>
            </div>
        </div>

        <div class="diagnostic-section">
            <h3 class="section-title">📋 Debug Log</h3>
            <div id="debug-log" class="debug-log">
                Initializing diagnostic system...<br>
            </div>
            <button class="test-button" onclick="clearDebugLog()">Clear Log</button>
        </div>
    </div>

    <!-- Include WordPress scripts -->
    <?php wp_head(); ?>

    <!-- DIAGNOSTIC: Force-load Visual Customizer scripts for comprehensive testing -->
    <?php
    // Get theme version for cache busting
    $theme_version = wp_get_theme()->get('Version');
    $theme_uri = get_template_directory_uri();
    ?>

    <script>
        console.log('🔧 Diagnostic: Force-loading Visual Customizer scripts...');

        // Check current user capabilities
        console.log('👤 Current user admin:', <?php echo current_user_can('manage_options') ? 'true' : 'false'; ?>);

        // Load scripts in dependency order
        const scriptsToLoad = [
            '<?php echo $theme_uri; ?>/assets/js/semantic-color-system.js?v=<?php echo $theme_version; ?>',
            '<?php echo $theme_uri; ?>/assets/js/color-system-manager.js?v=<?php echo $theme_version; ?>',
            '<?php echo $theme_uri; ?>/assets/js/live-preview-system.js?v=<?php echo $theme_version; ?>',
            '<?php echo $theme_uri; ?>/assets/js/preview-messenger.js?v=<?php echo $theme_version; ?>',
            '<?php echo $theme_uri; ?>/assets/js/wp-customizer-bridge.js?v=<?php echo $theme_version; ?>',
            '<?php echo $theme_uri; ?>/assets/js/color-palette-interface.js?v=<?php echo $theme_version; ?>',
            '<?php echo $theme_uri; ?>/assets/js/simple-visual-customizer.js?v=<?php echo $theme_version; ?>'
        ];

        // Load scripts sequentially to maintain dependencies
        function loadScriptSequentially(index = 0) {
            if (index >= scriptsToLoad.length) {
                console.log('✅ All Visual Customizer scripts loaded');
                initializeSystemsAfterLoad();
                return;
            }

            const script = document.createElement('script');
            script.src = scriptsToLoad[index];
            script.onload = () => {
                console.log(`✅ Loaded: ${scriptsToLoad[index].split('/').pop()}`);
                loadScriptSequentially(index + 1);
            };
            script.onerror = () => {
                console.error(`❌ Failed to load: ${scriptsToLoad[index]}`);
                loadScriptSequentially(index + 1); // Continue loading even if one fails
            };
            document.head.appendChild(script);
        }

        // Initialize WordPress-style configuration
        window.simpleCustomizer = {
            ajaxUrl: '<?php echo admin_url('admin-ajax.php'); ?>',
            nonce: '<?php echo wp_create_nonce('simple_visual_customizer'); ?>',
            isAdmin: <?php echo current_user_can('manage_options') ? 'true' : 'false'; ?>,
            debug: <?php echo (defined('WP_DEBUG') && WP_DEBUG) ? 'true' : 'false'; ?>,
            pvc005Enabled: true,
            capabilities: {
                livePreview: true,
                previewMessenger: true,
                wpCustomizerBridge: true,
                performanceMonitoring: true
            },
            config: {
                updateDelay: 50,
                previewMode: 'live',
                cssVariablePrefix: '--color-',
                enableDebug: <?php echo (defined('WP_DEBUG') && WP_DEBUG) ? 'true' : 'false'; ?>
            },
            strings: {
                loading: 'Loading Live Preview System...',
                applying: 'Applying palette...',
                applied: 'Palette applied in real-time!',
                error: 'Error applying palette',
                reset: 'Reset to defaults',
                performance: 'Performance metrics'
            }
        };

        console.log('🎯 simpleCustomizer config initialized:', window.simpleCustomizer);

        // Function to initialize systems after all scripts load
        function initializeSystemsAfterLoad() {
            console.log('🚀 Attempting to initialize Visual Customizer systems...');

            // Give scripts a moment to execute
            setTimeout(() => {
                try {
                    // Check if systems are available
                    if (typeof window.SemanticColorSystem !== 'undefined') {
                        console.log('✅ SemanticColorSystem class available');
                    }

                    if (typeof window.LivePreviewSystem !== 'undefined') {
                        console.log('✅ LivePreviewSystem class available');

                        // Try to instantiate if not already done
                        if (typeof window.livePreviewSystem === 'undefined') {
                            console.log('🔄 Instantiating LivePreviewSystem...');
                            window.livePreviewSystem = new window.LivePreviewSystem({
                                debug: true,
                                enableDebug: true
                            });
                            console.log('✅ LivePreviewSystem instantiated');
                        }
                    }

                    // Trigger system status check update
                    if (typeof checkSystemStatus === 'function') {
                        setTimeout(checkSystemStatus, 500);
                    }

                } catch (error) {
                    console.error('❌ Error initializing systems:', error);
                }
            }, 1000);
        }

        // Start loading scripts
        loadScriptSequentially();
    </script>

    <script>
        console.log('🔬 Real-time Diagnostic: Starting tests...');

        let debugLog = document.getElementById('debug-log');

        function log(message) {
            const timestamp = new Date().toLocaleTimeString();
            debugLog.innerHTML += `[${timestamp}] ${message}<br>`;
            debugLog.scrollTop = debugLog.scrollHeight;
            console.log(`[Diagnostic] ${message}`);
        }

        function clearDebugLog() {
            debugLog.innerHTML = 'Debug log cleared.<br>';
        }

        // System status checks
        function checkSystemStatus() {
            log('🔍 Checking system status...');

            // Check WordPress
            if (typeof wp !== 'undefined') {
                document.getElementById('wp-status').innerHTML = '✅ Available';
                document.getElementById('wp-status').className = 'status-ok';
                log('✅ WordPress: Available');
            } else {
                document.getElementById('wp-status').innerHTML = '❌ Missing';
                document.getElementById('wp-status').className = 'status-error';
                log('❌ WordPress: Missing');
            }

            // Check JavaScript systems
            const jsComponents = [
                'semanticColorSystem',
                'colorSystemManager',
                'livePreviewSystem',
                'colorPaletteInterface',
                'simpleVisualCustomizer'
            ];

            let jsAvailable = 0;
            jsComponents.forEach(component => {
                if (typeof window[component] !== 'undefined') {
                    jsAvailable++;
                    log(`✅ ${component}: Available`);
                } else {
                    log(`❌ ${component}: Missing`);
                }
            });

            if (jsAvailable >= 3) {
                document.getElementById('js-status').innerHTML = `✅ ${jsAvailable}/5 Available`;
                document.getElementById('js-status').className = 'status-ok';
            } else {
                document.getElementById('js-status').innerHTML = `⚠️ ${jsAvailable}/5 Available`;
                document.getElementById('js-status').className = 'status-warning';
            }

            // Check LivePreviewSystem specifically
            if (typeof window.livePreviewSystem !== 'undefined' && window.livePreviewSystem) {
                document.getElementById('live-preview-status').innerHTML = '✅ Active';
                document.getElementById('live-preview-status').className = 'status-ok';
                log('✅ LivePreviewSystem: Active');
            } else if (typeof window.LivePreviewSystem !== 'undefined') {
                document.getElementById('live-preview-status').innerHTML = '⚠️ Class Available';
                document.getElementById('live-preview-status').className = 'status-warning';
                log('⚠️ LivePreviewSystem: Class available but not instantiated');
            } else {
                document.getElementById('live-preview-status').innerHTML = '❌ Missing';
                document.getElementById('live-preview-status').className = 'status-error';
                log('❌ LivePreviewSystem: Missing');
            }

            // Check body class
            if (document.body.classList.contains('live-preview-active')) {
                document.getElementById('body-class-status').innerHTML = '✅ Active';
                document.getElementById('body-class-status').className = 'status-ok';
                log('✅ Body class: live-preview-active is present');
            } else {
                document.getElementById('body-class-status').innerHTML = '❌ Inactive';
                document.getElementById('body-class-status').className = 'status-error';
                log('❌ Body class: live-preview-active is missing');
            }

            // Load current palette
            loadCurrentPalette();
        }

        // Load current palette via AJAX
        function loadCurrentPalette() {
            log('🎯 Loading current palette...');

            if (typeof jQuery !== 'undefined' && typeof simpleCustomizer !== 'undefined') {
                jQuery.ajax({
                    url: simpleCustomizer.ajaxUrl,
                    method: 'POST',
                    data: {
                        action: 'get_current_palette',
                        nonce: simpleCustomizer.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            document.getElementById('current-palette-status').innerHTML = `✅ ${response.data}`;
                            document.getElementById('current-palette-status').className = 'status-ok';
                            log(`✅ Current palette: ${response.data}`);
                            highlightActivePalette(response.data);
                        } else {
                            document.getElementById('current-palette-status').innerHTML = '❌ Load Failed';
                            document.getElementById('current-palette-status').className = 'status-error';
                            log(`❌ Failed to load current palette: ${response.data || 'Unknown error'}`);
                        }
                    },
                    error: function(xhr, status, error) {
                        document.getElementById('current-palette-status').innerHTML = '❌ AJAX Error';
                        document.getElementById('current-palette-status').className = 'status-error';
                        log(`❌ AJAX Error loading current palette: ${status} - ${error}`);
                    }
                });
            } else {
                document.getElementById('current-palette-status').innerHTML = '❌ No AJAX';
                document.getElementById('current-palette-status').className = 'status-error';
                log('❌ jQuery or simpleCustomizer not available');
            }
        }

        // Test functions
        function testLivePreview() {
            log('⚡ Testing LivePreviewSystem...');

            const resultDiv = document.getElementById('test-results');
            resultDiv.style.display = 'block';

            if (typeof window.livePreviewSystem !== 'undefined') {
                try {
                    // Try to enable preview mode
                    window.livePreviewSystem.enablePreviewMode();
                    log('✅ LivePreviewSystem.enablePreviewMode() called');

                    // Check if body class was added
                    setTimeout(() => {
                        if (document.body.classList.contains('live-preview-active')) {
                            resultDiv.innerHTML = '✅ LivePreviewSystem test PASSED - Preview mode activated';
                            log('✅ LivePreviewSystem test PASSED');
                        } else {
                            resultDiv.innerHTML = '❌ LivePreviewSystem test FAILED - Body class not added';
                            log('❌ LivePreviewSystem test FAILED');
                        }
                    }, 100);

                } catch (error) {
                    resultDiv.innerHTML = `❌ LivePreviewSystem test ERROR: ${error.message}`;
                    log(`❌ LivePreviewSystem test ERROR: ${error.message}`);
                }
            } else {
                resultDiv.innerHTML = '❌ LivePreviewSystem not available';
                log('❌ LivePreviewSystem not available');
            }
        }

        function testAJAXConnection() {
            log('📡 Testing AJAX connection...');

            const resultDiv = document.getElementById('test-results');
            resultDiv.style.display = 'block';

            if (typeof jQuery !== 'undefined' && typeof simpleCustomizer !== 'undefined') {
                jQuery.ajax({
                    url: simpleCustomizer.ajaxUrl,
                    method: 'POST',
                    data: {
                        action: 'get_current_palette',
                        nonce: simpleCustomizer.nonce
                    },
                    success: function(response) {
                        resultDiv.innerHTML = `✅ AJAX test PASSED - Response: ${JSON.stringify(response)}`;
                        log('✅ AJAX test PASSED');
                    },
                    error: function(xhr, status, error) {
                        resultDiv.innerHTML = `❌ AJAX test FAILED - ${status}: ${error}`;
                        log(`❌ AJAX test FAILED: ${status} - ${error}`);
                    }
                });
            } else {
                resultDiv.innerHTML = '❌ AJAX requirements not available';
                log('❌ AJAX requirements not available');
            }
        }

        function testCSSInjection() {
            log('🎨 Testing CSS injection...');

            const resultDiv = document.getElementById('test-results');
            resultDiv.style.display = 'block';

            try {
                // Create test CSS
                const testCSS = `
                    :root {
                        --color-primary-navy: #ff0000 !important;
                        --color-primary-teal: #00ff00 !important;
                        --color-secondary-peach: #0000ff !important;
                    }
                    body.live-preview-active .preview-element {
                        background: var(--color-primary-navy) !important;
                        border: 3px solid var(--color-primary-teal) !important;
                    }
                    body.live-preview-active .cta-button {
                        background: var(--color-secondary-peach) !important;
                    }
                `;

                // Inject test CSS
                const styleElement = document.createElement('style');
                styleElement.id = 'diagnostic-test-css';
                styleElement.textContent = testCSS;
                document.head.appendChild(styleElement);

                // Add body class for testing
                document.body.classList.add('live-preview-active');

                // Force reflow
                document.body.offsetHeight;

                resultDiv.innerHTML = '✅ CSS injection test completed - Check if preview elements changed colors';
                log('✅ CSS injection test completed');

                // Clean up after 5 seconds
                setTimeout(() => {
                    const testStyle = document.getElementById('diagnostic-test-css');
                    if (testStyle) {
                        testStyle.remove();
                    }
                    log('🧹 CSS injection test cleanup completed');
                }, 5000);

            } catch (error) {
                resultDiv.innerHTML = `❌ CSS injection test ERROR: ${error.message}`;
                log(`❌ CSS injection test ERROR: ${error.message}`);
            }
        }

        function testCSSPersistence() {
            log('🎯 Testing CSS persistence fix with rapid palette changes...');

            const resultDiv = document.getElementById('test-results');
            resultDiv.style.display = 'block';

            if (typeof window.livePreviewSystem === 'undefined') {
                resultDiv.innerHTML = '❌ LivePreviewSystem not available for persistence test';
                log('❌ LivePreviewSystem not available');
                return;
            }

            try {
                // Define test palettes with distinct colors
                const testPalettes = [
                    {
                        id: 'test-red',
                        name: 'Test Red',
                        colors: {
                            primary: { hex: '#ff0000' },    // Red
                            secondary: { hex: '#ffcccc' },  // Light Red
                            accent: { hex: '#990000' },     // Dark Red
                            surface: { hex: '#ffffff' },
                            background: { hex: '#fff5f5' },
                            text: { hex: '#333333' }
                        }
                    },
                    {
                        id: 'test-green',
                        name: 'Test Green',
                        colors: {
                            primary: { hex: '#00ff00' },    // Green
                            secondary: { hex: '#ccffcc' },  // Light Green
                            accent: { hex: '#009900' },     // Dark Green
                            surface: { hex: '#ffffff' },
                            background: { hex: '#f5fff5' },
                            text: { hex: '#333333' }
                        }
                    },
                    {
                        id: 'test-blue',
                        name: 'Test Blue',
                        colors: {
                            primary: { hex: '#0000ff' },    // Blue
                            secondary: { hex: '#ccccff' },  // Light Blue
                            accent: { hex: '#000099' },     // Dark Blue
                            surface: { hex: '#ffffff' },
                            background: { hex: '#f5f5ff' },
                            text: { hex: '#333333' }
                        }
                    }
                ];

                let testIndex = 0;
                resultDiv.innerHTML = '🔄 Testing rapid palette changes...';

                function applyNextPalette() {
                    if (testIndex >= testPalettes.length) {
                        resultDiv.innerHTML = '✅ CSS persistence test COMPLETE - All palettes applied successfully!';
                        log('✅ CSS persistence test completed - rapid changes successful');
                        return;
                    }

                    const palette = testPalettes[testIndex];
                    log(`🎨 Applying test palette ${testIndex + 1}: ${palette.name} (${palette.colors.primary.hex})`);

                    try {
                        window.livePreviewSystem.applyPalette(palette);
                        log(`✅ Applied palette: ${palette.name}`);

                        // Update result display
                        resultDiv.innerHTML = `🔄 Applied ${testIndex + 1}/${testPalettes.length}: ${palette.name} (${palette.colors.primary.hex})`;

                        testIndex++;

                        // Apply next palette after 2 seconds
                        setTimeout(applyNextPalette, 2000);

                    } catch (error) {
                        resultDiv.innerHTML = `❌ Failed on palette ${testIndex + 1}: ${error.message}`;
                        log(`❌ Failed to apply palette ${palette.name}: ${error.message}`);
                    }
                }

                // Start the test
                applyNextPalette();

            } catch (error) {
                resultDiv.innerHTML = `❌ CSS persistence test ERROR: ${error.message}`;
                log(`❌ CSS persistence test ERROR: ${error.message}`);
            }
        }

        function resetAllStyles() {
            log('🔄 Resetting all styles...');

            // Remove all preview and emergency styles
            const stylesToRemove = document.querySelectorAll('style[id*="preview"], style[id*="emergency"], style[id*="live-preview"], style[id*="diagnostic"]');
            stylesToRemove.forEach(style => {
                style.remove();
                log(`🗑️ Removed style: ${style.id}`);
            });

            // Remove body classes
            document.body.classList.remove('live-preview-active');

            // Force reflow
            document.body.offsetHeight;

            log('✅ All styles reset');

            const resultDiv = document.getElementById('test-results');
            resultDiv.style.display = 'block';
            resultDiv.innerHTML = '✅ All preview styles have been reset';
        }

        // Palette testing
        function createPaletteButtons() {
            const palettes = [
                { id: 'luxury-spa', name: 'Luxury Spa', colors: ['#2c3e50', '#16a085', '#f39c12'] },
                { id: 'medical-clean', name: 'Medical Clean', colors: ['#3498db', '#2ecc71', '#e74c3c'] },
                { id: 'wellness-zen', name: 'Wellness Zen', colors: ['#8e44ad', '#27ae60', '#f1c40f'] },
                { id: 'professional-care', name: 'Professional Care', colors: ['#34495e', '#1abc9c', '#e67e22'] },
                { id: 'modern-elegance', name: 'Modern Elegance', colors: ['#2c3e50', '#95a5a6', '#d35400'] }
            ];

            const container = document.getElementById('palette-buttons');
            container.innerHTML = palettes.map(palette => `
                <button class="palette-btn" data-palette="${palette.id}" onclick="testPalette('${palette.id}')">
                    <span style="display: inline-block; width: 12px; height: 12px; background: ${palette.colors[0]}; border-radius: 50%; margin-right: 6px;"></span>
                    <span style="display: inline-block; width: 12px; height: 12px; background: ${palette.colors[1]}; border-radius: 50%; margin-right: 6px;"></span>
                    <span style="display: inline-block; width: 12px; height: 12px; background: ${palette.colors[2]}; border-radius: 50%; margin-right: 8px;"></span>
                    ${palette.name}
                </button>
            `).join('');
        }

        function highlightActivePalette(paletteId) {
            document.querySelectorAll('.palette-btn').forEach(btn => {
                btn.classList.remove('active');
                if (btn.getAttribute('data-palette') === paletteId) {
                    btn.classList.add('active');
                }
            });
        }

        function testPalette(paletteId) {
            log(`🎨 Testing palette: ${paletteId}`);

            // Highlight selected palette
            highlightActivePalette(paletteId);

            // Try different methods to apply palette
            if (typeof window.livePreviewSystem !== 'undefined') {
                try {
                    // Method 1: Direct LivePreviewSystem
                    log(`🔄 Attempting LivePreviewSystem.applyPalette('${paletteId}')`);

                    // ENHANCED: Try to get real palette data first
                    let testPaletteData = null;

                    // Check if we can get real palette data from semantic system
                    if (typeof window.semanticColorSystem !== 'undefined') {
                        try {
                            testPaletteData = window.semanticColorSystem.getPalette(paletteId);
                            if (testPaletteData) {
                                log(`✅ Got REAL palette data for ${paletteId}:`, testPaletteData);
                                log(`🎯 Palette colors:`, testPaletteData.colors);
                            }
                        } catch (error) {
                            log(`⚠️ Could not get real palette data: ${error.message}`);
                        }
                    }

                    // Fallback to test data if no real data available
                    if (!testPaletteData) {
                        log(`⚠️ Using fallback test data for ${paletteId}`);

                        // Create more distinct test colors for each palette
                        const paletteColors = {
                            'luxury-spa': {
                                primary: { hex: '#8b4513' },    // Saddle Brown
                                secondary: { hex: '#daa520' },  // Goldenrod
                                accent: { hex: '#cd853f' }      // Peru
                            },
                            'medical-clean': {
                                primary: { hex: '#4169e1' },    // Royal Blue
                                secondary: { hex: '#87ceeb' },  // Sky Blue
                                accent: { hex: '#00bfff' }      // Deep Sky Blue
                            },
                            'wellness-zen': {
                                primary: { hex: '#9370db' },    // Medium Purple
                                secondary: { hex: '#dda0dd' },  // Plum
                                accent: { hex: '#ba55d3' }      // Medium Orchid
                            },
                            'professional-care': {
                                primary: { hex: '#2f4f4f' },    // Dark Slate Gray
                                secondary: { hex: '#708090' },  // Slate Gray
                                accent: { hex: '#778899' }      // Light Slate Gray
                            },
                            'modern-elegance': {
                                primary: { hex: '#800080' },    // Purple
                                secondary: { hex: '#c0c0c0' },  // Silver
                                accent: { hex: '#ff6347' }      // Tomato
                            }
                        };

                        const colors = paletteColors[paletteId] || paletteColors['medical-clean'];

                        testPaletteData = {
                            id: paletteId,
                            name: paletteId.replace('-', ' ').replace(/\b\w/g, l => l.toUpperCase()),
                            colors: {
                                primary: colors.primary,
                                secondary: colors.secondary,
                                accent: colors.accent,
                                surface: { hex: '#ffffff' },
                                background: { hex: '#f8f9fa' },
                                text: { hex: '#212529' }
                            }
                        };

                        log(`🎨 Using distinct test colors for ${paletteId}:`, testPaletteData.colors);
                    }

                    window.livePreviewSystem.applyPalette(testPaletteData);
                    log(`✅ LivePreviewSystem.applyPalette() called successfully`);

                } catch (error) {
                    log(`❌ LivePreviewSystem.applyPalette() failed: ${error.message}`);
                }
            }

            // Method 2: Dispatch custom event
            try {
                log(`📡 Dispatching paletteInterface:paletteSelected event`);
                document.dispatchEvent(new CustomEvent('paletteInterface:paletteSelected', {
                    detail: { paletteId: paletteId }
                }));
                log(`✅ Event dispatched successfully`);
            } catch (error) {
                log(`❌ Event dispatch failed: ${error.message}`);
            }
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            log('🚀 Diagnostic system initialized');
            checkSystemStatus();
            createPaletteButtons();
        });

        // Check status every 30 seconds
        setInterval(checkSystemStatus, 30000);
    </script>
</body>
</html>
