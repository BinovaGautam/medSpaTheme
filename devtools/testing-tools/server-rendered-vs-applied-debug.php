<?php
/**
 * Server-Rendered vs Applied Design Debug Tool
 *
 * Identifies and resolves differences between what Visual Customizer applied
 * and what the server actually renders to visitors
 *
 * Usage: Add ?debug_server_rendering=1 to any URL
 */

// Security check
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize Server Rendering Debug Tool
 */
function init_server_rendering_debug() {
    if (!isset($_GET['debug_server_rendering']) || !current_user_can('manage_options')) {
        return;
    }

    add_action('wp_head', 'output_server_rendering_debug_analysis', 1);
    add_action('wp_footer', 'output_server_rendering_comparison_tool');
}
add_action('init', 'init_server_rendering_debug');

/**
 * Output comprehensive debug analysis in head
 */
function output_server_rendering_debug_analysis() {
    ?>
    <script>
    console.log('🔍 SERVER RENDERING DEBUG: Starting comprehensive analysis...');

    // Store original document state
    window.serverRenderingDebug = {
        originalHTML: document.documentElement.outerHTML,
        initialCSSVariables: {},
        serverRenderedColors: {},
        appliedColors: {},
        differences: [],
        startTime: performance.now()
    };

    // Capture initial CSS variables state
    const rootStyle = getComputedStyle(document.documentElement);
    const criticalVars = [
        '--color-primary-navy',
        '--color-primary-teal',
        '--color-secondary-peach',
        '--color-neutral-white',
        '--color-soft-cream',
        '--color-charcoal',
        '--gradient-primary',
        '--gradient-accent',
        '--component-bg-color-primary',
        '--component-text-color-primary',
        '--component-bg-color-secondary',
        '--component-bg-color-accent'
    ];

    criticalVars.forEach(varName => {
        const value = rootStyle.getPropertyValue(varName).trim();
        window.serverRenderingDebug.initialCSSVariables[varName] = value || 'NOT SET';
    });

    console.log('🎯 Initial CSS Variables (Server-Rendered):', window.serverRenderingDebug.initialCSSVariables);
    </script>

    <style id="server-debug-styles">
    /* Server Rendering Debug Styles */
    .server-debug-panel {
        position: fixed;
        top: 32px;
        right: 20px;
        width: 400px;
        max-height: 80vh;
        background: #1e1e1e;
        color: #fff;
        border: 2px solid #007cba;
        border-radius: 8px;
        z-index: 999999;
        overflow-y: auto;
        font-family: 'Courier New', monospace;
        font-size: 12px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    }

    .server-debug-header {
        background: #007cba;
        padding: 12px;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .server-debug-content {
        padding: 16px;
        max-height: 600px;
        overflow-y: auto;
    }

    .debug-section {
        margin-bottom: 20px;
        border-bottom: 1px solid #333;
        padding-bottom: 16px;
    }

    .debug-section h4 {
        color: #00a0d2;
        margin: 0 0 8px 0;
        font-size: 14px;
    }

    .debug-item {
        margin: 8px 0;
        padding: 8px;
        background: #2a2a2a;
        border-radius: 4px;
        border-left: 3px solid #555;
    }

    .debug-item.success {
        border-left-color: #46b450;
        background: rgba(70, 180, 80, 0.1);
    }

    .debug-item.warning {
        border-left-color: #ffb900;
        background: rgba(255, 185, 0, 0.1);
    }

    .debug-item.error {
        border-left-color: #dc3232;
        background: rgba(220, 50, 50, 0.1);
    }

    .color-swatch {
        display: inline-block;
        width: 20px;
        height: 20px;
        border-radius: 3px;
        margin-right: 8px;
        vertical-align: middle;
        border: 1px solid #666;
    }

    .debug-close {
        background: none;
        border: none;
        color: #fff;
        cursor: pointer;
        font-size: 18px;
        padding: 0;
        line-height: 1;
    }

    .fix-button {
        background: #007cba;
        color: #fff;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        margin: 4px;
        font-size: 11px;
    }

    .fix-button:hover {
        background: #005a87;
    }

    .fix-button.success {
        background: #46b450;
    }

    .fix-button.warning {
        background: #ffb900;
    }
    </style>

    <?php
    // Output server-side configuration data
    $server_config = analyze_server_rendering_config();
    ?>
    <script>
    // Inject server configuration for comparison
    window.serverRenderingDebug.serverConfig = <?php echo json_encode($server_config); ?>;

    console.log('🎯 Server Configuration:', window.serverRenderingDebug.serverConfig);
    </script>
    <?php
}

/**
 * Analyze server rendering configuration
 */
function analyze_server_rendering_config() {
    $analysis = [
        'timestamp' => current_time('c'),
        'saved_config' => [],
        'theme_mods' => [],
        'options' => [],
        'css_generation' => [],
        'hooks_analysis' => [],
        'differences' => []
    ];

    // Check saved Visual Customizer config
    $saved_config = get_option('preetidreams_visual_customizer_config', []);
    $analysis['saved_config'] = [
        'exists' => !empty($saved_config),
        'active_palette' => $saved_config['activePalette'] ?? 'NOT SET',
        'palette_data' => isset($saved_config['paletteData']) ? 'PRESENT' : 'MISSING',
        'saved_at' => $saved_config['saved_at'] ?? 'UNKNOWN',
        'config_keys' => array_keys($saved_config)
    ];

    // Check theme mods
    $analysis['theme_mods'] = [
        'visual_customizer_active_palette' => get_theme_mod('visual_customizer_active_palette', 'NOT SET'),
        'medspaa_color_palette' => get_theme_mod('medspaa_color_palette', 'NOT SET')
    ];

    // Check options
    $analysis['options'] = [
        'preetidreams_active_palette' => get_option('preetidreams_active_palette', 'NOT SET'),
        'preetidreams_generated_css' => !empty(get_option('preetidreams_generated_css')) ? 'PRESENT' : 'MISSING'
    ];

    // Test CSS generation
    if (!empty($saved_config['paletteData'])) {
        $generated_css = generate_css_from_palette_data($saved_config['paletteData']);
        $analysis['css_generation'] = [
            'can_generate' => !empty($generated_css),
            'css_length' => strlen($generated_css),
            'contains_variables' => strpos($generated_css, '--color-primary') !== false,
            'css_preview' => substr($generated_css, 0, 200) . '...'
        ];
    }

    // Check what hooks are running
    $analysis['hooks_analysis'] = [
        'wp_head_hooks' => get_wp_head_hook_analysis(),
        'css_output_active' => has_action('wp_head', 'output_visual_customizer_global_css'),
        'hook_priority' => wp_get_hook_priority('wp_head', 'output_visual_customizer_global_css')
    ];

    return $analysis;
}

/**
 * Get WordPress head hook analysis
 */
function get_wp_head_hook_analysis() {
    global $wp_filter;

    $analysis = [
        'total_hooks' => 0,
        'style_hooks' => [],
        'customizer_hooks' => []
    ];

    if (isset($wp_filter['wp_head'])) {
        $analysis['total_hooks'] = count($wp_filter['wp_head']->callbacks, COUNT_RECURSIVE);

        foreach ($wp_filter['wp_head']->callbacks as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                $function_name = '';
                if (is_array($callback['function'])) {
                    $function_name = get_class($callback['function'][0]) . '::' . $callback['function'][1];
                } else if (is_string($callback['function'])) {
                    $function_name = $callback['function'];
                }

                if (strpos($function_name, 'style') !== false || strpos($function_name, 'css') !== false) {
                    $analysis['style_hooks'][] = [
                        'priority' => $priority,
                        'function' => $function_name
                    ];
                }

                if (strpos($function_name, 'customizer') !== false || strpos($function_name, 'visual') !== false) {
                    $analysis['customizer_hooks'][] = [
                        'priority' => $priority,
                        'function' => $function_name
                    ];
                }
            }
        }
    }

    return $analysis;
}

/**
 * Get WordPress hook priority
 */
function wp_get_hook_priority($tag, $function_to_check) {
    global $wp_filter;

    if (!isset($wp_filter[$tag])) {
        return false;
    }

    foreach ($wp_filter[$tag]->callbacks as $priority => $callbacks) {
        foreach ($callbacks as $callback) {
            if (is_string($callback['function']) && $callback['function'] === $function_to_check) {
                return $priority;
            }
        }
    }

    return false;
}

/**
 * Output comprehensive comparison tool in footer
 */
function output_server_rendering_comparison_tool() {
    ?>
    <div id="server-debug-panel" class="server-debug-panel">
        <div class="server-debug-header">
            🔍 Server Rendering Debug
            <button class="debug-close" onclick="document.getElementById('server-debug-panel').style.display='none'">×</button>
        </div>
        <div class="server-debug-content" id="debug-content">
            <div class="debug-section">
                <h4>🚀 Initializing Analysis...</h4>
                <div class="debug-item">Loading comprehensive server vs applied comparison...</div>
            </div>
        </div>
    </div>

    <script>
    (function() {
        'use strict';

        let debugData = window.serverRenderingDebug;

        // Wait for DOM to be fully loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', startServerRenderingAnalysis);
        } else {
            startServerRenderingAnalysis();
        }

        function startServerRenderingAnalysis() {
            console.log('🔍 Starting comprehensive server rendering analysis...');

            // Wait a bit for any dynamic CSS to load
            setTimeout(() => {
                performComprehensiveAnalysis();
            }, 1000);
        }

        function performComprehensiveAnalysis() {
            const analysis = {
                serverConfig: debugData.serverConfig,
                currentCSSVariables: getCurrentCSSVariables(),
                visualCustomizerState: getVisualCustomizerState(),
                appliedStyles: getAppliedStyles(),
                differences: [],
                fixes: []
            };

            // Compare server config vs current state
            analysis.differences = findServerVsAppliedDifferences(analysis);

            // Generate fixes
            analysis.fixes = generateServerRenderingFixes(analysis);

            // Update debug panel
            updateDebugPanel(analysis);

            console.log('🎯 Comprehensive Analysis Complete:', analysis);
        }

        function getCurrentCSSVariables() {
            const rootStyle = getComputedStyle(document.documentElement);
            const vars = {};

            // Get all CSS variables that might be relevant
            const allVars = [
                '--color-primary-navy', '--color-primary-teal', '--color-secondary-peach',
                '--color-neutral-white', '--color-soft-cream', '--color-charcoal',
                '--gradient-primary', '--gradient-accent',
                '--component-bg-color-primary', '--component-text-color-primary',
                '--component-bg-color-secondary', '--component-bg-color-accent',
                '--color-primary', '--color-secondary', '--color-accent'
            ];

            allVars.forEach(varName => {
                const value = rootStyle.getPropertyValue(varName).trim();
                vars[varName] = value || 'NOT SET';
            });

            return vars;
        }

        function getVisualCustomizerState() {
            const state = {
                localStorage: {},
                sessionData: {},
                appliedPalette: 'UNKNOWN'
            };

            // Check localStorage for Visual Customizer data
            try {
                const vcSettings = localStorage.getItem('visual_customizer_settings');
                if (vcSettings) {
                    state.localStorage = JSON.parse(vcSettings);
                    state.appliedPalette = state.localStorage.currentPalette || 'UNKNOWN';
                }
            } catch (e) {
                console.warn('Error reading localStorage:', e);
            }

            // Check for data attributes
            state.bodyAttributes = {
                paletteDebug: document.body.getAttribute('data-palette-debug'),
                currentPalette: document.body.getAttribute('data-current-palette'),
                customizerApplied: document.body.getAttribute('data-customizer-applied')
            };

            return state;
        }

        function getAppliedStyles() {
            const styles = {
                buttons: [],
                headers: [],
                navigation: [],
                background: {}
            };

            // Analyze button styles
            const buttons = document.querySelectorAll('button, .btn, .btn-consultation');
            buttons.forEach((button, index) => {
                if (index < 5) { // Limit to first 5 buttons
                    const computedStyle = getComputedStyle(button);
                    styles.buttons.push({
                        selector: button.className || button.tagName,
                        backgroundColor: computedStyle.backgroundColor,
                        color: computedStyle.color,
                        borderColor: computedStyle.borderColor
                    });
                }
            });

            // Analyze header styles
            const headers = document.querySelectorAll('h1, h2, h3, .site-title');
            headers.forEach((header, index) => {
                if (index < 3) { // Limit to first 3 headers
                    const computedStyle = getComputedStyle(header);
                    styles.headers.push({
                        selector: header.className || header.tagName,
                        color: computedStyle.color,
                        fontFamily: computedStyle.fontFamily
                    });
                }
            });

            // Analyze navigation
            const nav = document.querySelector('.professional-header, .nav-menu, .navigation');
            if (nav) {
                const computedStyle = getComputedStyle(nav);
                styles.navigation = {
                    backgroundColor: computedStyle.backgroundColor,
                    background: computedStyle.background
                };
            }

            return styles;
        }

        function findServerVsAppliedDifferences(analysis) {
            const differences = [];

            // Compare saved palette vs current CSS variables
            const serverPalette = analysis.serverConfig.saved_config.active_palette;
            const appliedPalette = analysis.visualCustomizerState.appliedPalette;

            if (serverPalette !== appliedPalette) {
                differences.push({
                    type: 'palette_mismatch',
                    severity: 'error',
                    description: `Server palette (${serverPalette}) differs from applied palette (${appliedPalette})`,
                    serverValue: serverPalette,
                    appliedValue: appliedPalette
                });
            }

            // Compare CSS variables
            const serverVars = debugData.initialCSSVariables;
            const currentVars = analysis.currentCSSVariables;

            Object.keys(serverVars).forEach(varName => {
                if (serverVars[varName] !== currentVars[varName]) {
                    differences.push({
                        type: 'css_variable_mismatch',
                        severity: 'warning',
                        description: `CSS variable ${varName} differs`,
                        serverValue: serverVars[varName],
                        appliedValue: currentVars[varName],
                        variable: varName
                    });
                }
            });

            // Check if CSS generation is working
            if (!analysis.serverConfig.css_generation.can_generate) {
                differences.push({
                    type: 'css_generation_failed',
                    severity: 'error',
                    description: 'Server cannot generate CSS from saved palette data',
                    solution: 'Check palette data structure and CSS generation function'
                });
            }

            // Check if hooks are properly configured
            if (!analysis.serverConfig.hooks_analysis.css_output_active) {
                differences.push({
                    type: 'hook_not_active',
                    severity: 'error',
                    description: 'CSS output hook is not active - server CSS not being generated',
                    solution: 'Ensure output_visual_customizer_global_css hook is properly registered'
                });
            }

            return differences;
        }

        function generateServerRenderingFixes(analysis) {
            const fixes = [];

            analysis.differences.forEach(diff => {
                switch (diff.type) {
                    case 'palette_mismatch':
                        fixes.push({
                            id: 'fix_palette_sync',
                            title: 'Sync Server & Applied Palette',
                            description: 'Force server to use the applied palette',
                            action: () => syncServerPalette(diff.appliedValue),
                            severity: 'error'
                        });
                        break;

                    case 'css_variable_mismatch':
                        fixes.push({
                            id: 'fix_css_variable_' + diff.variable.replace(/[^a-z0-9]/gi, '_'),
                            title: `Fix ${diff.variable}`,
                            description: `Update server CSS variable to match applied value`,
                            action: () => fixCSSVariable(diff.variable, diff.appliedValue),
                            severity: 'warning'
                        });
                        break;

                    case 'css_generation_failed':
                        fixes.push({
                            id: 'fix_css_generation',
                            title: 'Fix CSS Generation',
                            description: 'Repair server-side CSS generation function',
                            action: () => fixCSSGeneration(),
                            severity: 'error'
                        });
                        break;

                    case 'hook_not_active':
                        fixes.push({
                            id: 'fix_css_output_hook',
                            title: 'Activate CSS Output Hook',
                            description: 'Ensure server CSS output is properly hooked',
                            action: () => fixCSSOutputHook(),
                            severity: 'error'
                        });
                        break;
                }
            });

            return fixes;
        }

        function updateDebugPanel(analysis) {
            const content = document.getElementById('debug-content');
            let html = '';

            // Server Configuration Section
            html += `
                <div class="debug-section">
                    <h4>🖥️ Server Configuration</h4>
                    <div class="debug-item ${analysis.serverConfig.saved_config.exists ? 'success' : 'error'}">
                        <strong>Saved Config:</strong> ${analysis.serverConfig.saved_config.exists ? 'EXISTS' : 'MISSING'}<br>
                        <strong>Active Palette:</strong> ${analysis.serverConfig.saved_config.active_palette}<br>
                        <strong>Saved At:</strong> ${analysis.serverConfig.saved_config.saved_at}
                    </div>
                    <div class="debug-item ${analysis.serverConfig.css_generation.can_generate ? 'success' : 'error'}">
                        <strong>CSS Generation:</strong> ${analysis.serverConfig.css_generation.can_generate ? 'WORKING' : 'FAILED'}<br>
                        <strong>CSS Length:</strong> ${analysis.serverConfig.css_generation.css_length || 0} chars
                    </div>
                </div>
            `;

            // Current State Section
            html += `
                <div class="debug-section">
                    <h4>🎨 Current Applied State</h4>
                    <div class="debug-item ${analysis.visualCustomizerState.appliedPalette !== 'UNKNOWN' ? 'success' : 'warning'}">
                        <strong>Applied Palette:</strong> ${analysis.visualCustomizerState.appliedPalette}<br>
                        <strong>Body Attributes:</strong> ${Object.entries(analysis.visualCustomizerState.bodyAttributes).map(([k,v]) => `${k}=${v || 'none'}`).join(', ')}
                    </div>
                </div>
            `;

            // Differences Section
            html += `
                <div class="debug-section">
                    <h4>⚠️ Differences Found (${analysis.differences.length})</h4>
            `;

            if (analysis.differences.length === 0) {
                html += `<div class="debug-item success">✅ No differences found - server and applied state match!</div>`;
            } else {
                analysis.differences.forEach(diff => {
                    html += `
                        <div class="debug-item ${diff.severity}">
                            <strong>${diff.type}:</strong> ${diff.description}<br>
                            ${diff.serverValue ? `<strong>Server:</strong> <span class="color-swatch" style="background-color: ${diff.serverValue}"></span> ${diff.serverValue}<br>` : ''}
                            ${diff.appliedValue ? `<strong>Applied:</strong> <span class="color-swatch" style="background-color: ${diff.appliedValue}"></span> ${diff.appliedValue}<br>` : ''}
                            ${diff.solution ? `<strong>Solution:</strong> ${diff.solution}` : ''}
                        </div>
                    `;
                });
            }

            html += `</div>`;

            // Fixes Section
            if (analysis.fixes.length > 0) {
                html += `
                    <div class="debug-section">
                        <h4>🔧 Available Fixes (${analysis.fixes.length})</h4>
                `;

                analysis.fixes.forEach(fix => {
                    html += `
                        <div class="debug-item ${fix.severity}">
                            <strong>${fix.title}:</strong> ${fix.description}<br>
                            <button class="fix-button ${fix.severity}" onclick="applyFix('${fix.id}')">Apply Fix</button>
                        </div>
                    `;
                });

                html += `</div>`;
            }

            // CSS Variables Comparison
            html += `
                <div class="debug-section">
                    <h4>🎯 CSS Variables Comparison</h4>
            `;

            const serverVars = debugData.initialCSSVariables;
            const currentVars = analysis.currentCSSVariables;

            Object.keys(serverVars).forEach(varName => {
                const serverVal = serverVars[varName];
                const currentVal = currentVars[varName];
                const matches = serverVal === currentVal;

                html += `
                    <div class="debug-item ${matches ? 'success' : 'warning'}">
                        <strong>${varName}:</strong><br>
                        Server: <span class="color-swatch" style="background-color: ${serverVal}"></span> ${serverVal}<br>
                        Current: <span class="color-swatch" style="background-color: ${currentVal}"></span> ${currentVal}
                        ${matches ? ' ✅' : ' ⚠️'}
                    </div>
                `;
            });

            html += `</div>`;

            content.innerHTML = html;

            // Store fixes globally for the fix functions
            window.serverRenderingFixes = analysis.fixes;
        }

        // Fix functions
        window.applyFix = function(fixId) {
            const fix = window.serverRenderingFixes.find(f => f.id === fixId);
            if (fix && fix.action) {
                console.log(`Applying fix: ${fix.title}`);
                fix.action();
            }
        };

        function syncServerPalette(appliedPalette) {
            // Send AJAX request to sync server palette with applied palette
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'sync_server_palette',
                    nonce: '<?php echo wp_create_nonce('simple_visual_customizer'); ?>',
                    palette: appliedPalette
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Server palette synced successfully! Refresh page to see changes.');
                } else {
                    alert('❌ Error syncing server palette: ' + data.data.message);
                }
            })
            .catch(error => {
                console.error('Error syncing server palette:', error);
                alert('❌ Network error syncing server palette');
            });
        }

        function fixCSSVariable(varName, value) {
            // Force update CSS variable on server
            document.documentElement.style.setProperty(varName, value);
            alert(`✅ CSS variable ${varName} updated to ${value}`);
        }

        function fixCSSGeneration() {
            // Trigger CSS regeneration
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'regenerate_css',
                    nonce: '<?php echo wp_create_nonce('simple_visual_customizer'); ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ CSS regenerated successfully! Refresh page to see changes.');
                } else {
                    alert('❌ Error regenerating CSS: ' + data.data.message);
                }
            })
            .catch(error => {
                console.error('Error regenerating CSS:', error);
                alert('❌ Network error regenerating CSS');
            });
        }

        function fixCSSOutputHook() {
            alert('⚠️ CSS Output Hook fix requires server-side changes. Check that output_visual_customizer_global_css is properly hooked to wp_head.');
        }

    })();
    </script>
    <?php
}

/**
 * AJAX Handler: Sync Server Palette
 */
function handle_sync_server_palette() {
    if (!wp_verify_nonce($_POST['nonce'], 'simple_visual_customizer')) {
        wp_send_json_error(['message' => 'Security check failed']);
    }

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Insufficient permissions']);
    }

    $palette = sanitize_text_field($_POST['palette']);

    // Update all server storage locations
    set_theme_mod('visual_customizer_active_palette', $palette);
    update_option('preetidreams_active_palette', $palette);

    // Update saved config if it exists
    $config = get_option('preetidreams_visual_customizer_config', []);
    if (!empty($config)) {
        $config['activePalette'] = $palette;
        update_option('preetidreams_visual_customizer_config', $config);
    }

    wp_send_json_success([
        'message' => 'Server palette synced successfully',
        'palette' => $palette
    ]);
}
add_action('wp_ajax_sync_server_palette', 'handle_sync_server_palette');

/**
 * AJAX Handler: Regenerate CSS
 */
function handle_regenerate_css() {
    if (!wp_verify_nonce($_POST['nonce'], 'simple_visual_customizer')) {
        wp_send_json_error(['message' => 'Security check failed']);
    }

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Insufficient permissions']);
    }

    $config = get_option('preetidreams_visual_customizer_config', []);

    if (empty($config) || !isset($config['paletteData'])) {
        wp_send_json_error(['message' => 'No palette data available for CSS generation']);
    }

    // Regenerate CSS
    $css_generated = generate_global_css_from_config($config);

    if ($css_generated) {
        wp_send_json_success([
            'message' => 'CSS regenerated successfully',
            'generated' => true
        ]);
    } else {
        wp_send_json_error(['message' => 'CSS generation failed']);
    }
}
add_action('wp_ajax_regenerate_css', 'handle_regenerate_css');
