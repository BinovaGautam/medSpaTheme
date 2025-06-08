<?php
/**
 * Palette Data Structure Debug Tool
 *
 * Diagnoses why server-side CSS generation is failing
 *
 * Usage: Add ?debug_palette_data=1 to any URL
 */

// Security check
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize Palette Data Debug Tool
 */
function init_palette_data_debug() {
    if (!isset($_GET['debug_palette_data']) || !current_user_can('manage_options')) {
        return;
    }

    add_action('wp_head', 'output_palette_data_analysis', 1);
}
add_action('init', 'init_palette_data_debug');

/**
 * Output palette data analysis
 */
function output_palette_data_analysis() {
    ?>
    <style>
    .palette-debug-panel {
        position: fixed;
        top: 50px;
        right: 20px;
        width: 500px;
        max-height: 80vh;
        background: #1e1e1e;
        color: #fff;
        border: 2px solid #ff6b6b;
        border-radius: 8px;
        z-index: 999999;
        overflow-y: auto;
        font-family: 'Courier New', monospace;
        font-size: 11px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    }

    .palette-debug-header {
        background: #ff6b6b;
        padding: 12px;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .palette-debug-content {
        padding: 16px;
    }

    .debug-section {
        margin-bottom: 20px;
        border-bottom: 1px solid #333;
        padding-bottom: 16px;
    }

    .debug-section h4 {
        color: #ff6b6b;
        margin: 0 0 8px 0;
        font-size: 14px;
    }

    .debug-json {
        background: #2a2a2a;
        padding: 12px;
        border-radius: 4px;
        overflow-x: auto;
        white-space: pre-wrap;
        font-size: 10px;
        max-height: 200px;
        overflow-y: auto;
    }

    .debug-test {
        background: #1a4a1a;
        padding: 8px;
        border-radius: 4px;
        margin: 8px 0;
    }

    .debug-error {
        background: #4a1a1a;
        padding: 8px;
        border-radius: 4px;
        margin: 8px 0;
        color: #ffcccc;
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
    </style>

    <div class="palette-debug-panel">
        <div class="palette-debug-header">
            🔍 Palette Data Structure Debug
            <button class="debug-close" onclick="this.closest('.palette-debug-panel').style.display='none'">×</button>
        </div>
        <div class="palette-debug-content">

            <div class="debug-section">
                <h4>📊 Raw Database Data</h4>
                <?php
                $config = get_option('preetidreams_visual_customizer_config', []);
                ?>
                <div class="debug-json"><?php echo json_encode($config, JSON_PRETTY_PRINT); ?></div>
            </div>

            <div class="debug-section">
                <h4>🎨 Palette Data Analysis</h4>
                <?php
                if (empty($config)) {
                    echo '<div class="debug-error">❌ No configuration data found in database</div>';
                } else {
                    echo '<div class="debug-test">✅ Configuration exists</div>';

                    if (!isset($config['paletteData'])) {
                        echo '<div class="debug-error">❌ No paletteData key found</div>';
                    } else {
                        echo '<div class="debug-test">✅ paletteData key exists</div>';

                        $palette_data = $config['paletteData'];

                        if (!is_array($palette_data)) {
                            echo '<div class="debug-error">❌ paletteData is not an array: ' . gettype($palette_data) . '</div>';
                        } else {
                            echo '<div class="debug-test">✅ paletteData is an array</div>';

                            if (!isset($palette_data['colors'])) {
                                echo '<div class="debug-error">❌ No colors key in paletteData</div>';
                                echo '<div class="debug-error">Available keys: ' . implode(', ', array_keys($palette_data)) . '</div>';
                            } else {
                                echo '<div class="debug-test">✅ colors key exists</div>';

                                $colors = $palette_data['colors'];
                                if (!is_array($colors)) {
                                    echo '<div class="debug-error">❌ colors is not an array: ' . gettype($colors) . '</div>';
                                } else {
                                    echo '<div class="debug-test">✅ colors is an array with ' . count($colors) . ' items</div>';

                                    foreach ($colors as $role => $color_data) {
                                        echo '<div class="debug-test">• ' . $role . ': ';
                                        if (is_array($color_data)) {
                                            echo 'Array(' . implode(', ', array_keys($color_data)) . ')';
                                            if (isset($color_data['hex'])) {
                                                echo ' = ' . $color_data['hex'];
                                            }
                                        } else {
                                            echo $color_data;
                                        }
                                        echo '</div>';
                                    }
                                }
                            }
                        }
                    }
                }
                ?>
            </div>

            <div class="debug-section">
                <h4>🔧 CSS Generation Test</h4>
                <?php
                if (!empty($config) && isset($config['paletteData'])) {
                    echo '<div class="debug-test">Testing CSS generation...</div>';

                    $test_css = '';
                    try {
                        $test_css = generate_css_from_palette_data($config['paletteData']);

                        if (empty($test_css)) {
                            echo '<div class="debug-error">❌ CSS generation returned empty string</div>';
                        } else {
                            echo '<div class="debug-test">✅ CSS generated: ' . strlen($test_css) . ' characters</div>';

                            // Check for specific variables
                            $critical_vars = [
                                '--color-primary-navy',
                                '--color-primary-teal',
                                '--component-bg-color-primary',
                                '--component-text-color-primary'
                            ];

                            foreach ($critical_vars as $var) {
                                if (strpos($test_css, $var) !== false) {
                                    echo '<div class="debug-test">✅ Contains ' . $var . '</div>';
                                } else {
                                    echo '<div class="debug-error">❌ Missing ' . $var . '</div>';
                                }
                            }
                        }
                    } catch (Exception $e) {
                        echo '<div class="debug-error">❌ CSS generation error: ' . $e->getMessage() . '</div>';
                    }

                    if (!empty($test_css)) {
                        echo '<h5 style="color: #ff6b6b; margin: 12px 0 8px 0;">Generated CSS Preview:</h5>';
                        echo '<div class="debug-json">' . esc_html(substr($test_css, 0, 1000)) . '...</div>';
                    }
                } else {
                    echo '<div class="debug-error">❌ Cannot test - no valid palette data</div>';
                }
                ?>
            </div>

            <div class="debug-section">
                <h4>⚙️ Hook Analysis</h4>
                <?php
                global $wp_filter;

                echo '<div class="debug-test">Checking wp_head hooks...</div>';

                $hook_found = false;
                if (isset($wp_filter['wp_head'])) {
                    foreach ($wp_filter['wp_head']->callbacks as $priority => $callbacks) {
                        foreach ($callbacks as $callback) {
                            if (is_string($callback['function']) && $callback['function'] === 'output_visual_customizer_global_css') {
                                echo '<div class="debug-test">✅ Found hook at priority ' . $priority . '</div>';
                                $hook_found = true;
                                break 2;
                            }
                        }
                    }
                }

                if (!$hook_found) {
                    echo '<div class="debug-error">❌ output_visual_customizer_global_css hook not found</div>';
                }

                // Test if function exists
                if (function_exists('output_visual_customizer_global_css')) {
                    echo '<div class="debug-test">✅ Function output_visual_customizer_global_css exists</div>';
                } else {
                    echo '<div class="debug-error">❌ Function output_visual_customizer_global_css does not exist</div>';
                }

                // Test manual execution
                echo '<div class="debug-test">Testing manual function execution...</div>';
                ob_start();
                try {
                    output_visual_customizer_global_css();
                    $manual_output = ob_get_contents();
                    if (empty($manual_output)) {
                        echo '<div class="debug-error">❌ Manual execution produced no output</div>';
                    } else {
                        echo '<div class="debug-test">✅ Manual execution produced ' . strlen($manual_output) . ' characters</div>';
                    }
                } catch (Exception $e) {
                    echo '<div class="debug-error">❌ Manual execution error: ' . $e->getMessage() . '</div>';
                }
                ob_end_clean();
                ?>
            </div>

            <div class="debug-section">
                <h4>🛠️ Quick Fixes</h4>
                <button onclick="fixCSSGeneration()" style="background: #28a745; color: white; border: none; padding: 8px 16px; border-radius: 4px; margin: 4px; cursor: pointer;">
                    Force CSS Regeneration
                </button>
                <button onclick="debugPaletteStructure()" style="background: #17a2b8; color: white; border: none; padding: 8px 16px; border-radius: 4px; margin: 4px; cursor: pointer;">
                    Debug Palette Structure
                </button>
                <button onclick="testHookExecution()" style="background: #ffc107; color: black; border: none; padding: 8px 16px; border-radius: 4px; margin: 4px; cursor: pointer;">
                    Test Hook Execution
                </button>
            </div>

        </div>
    </div>

    <script>
    function fixCSSGeneration() {
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'force_css_regeneration',
                nonce: '<?php echo wp_create_nonce('simple_visual_customizer'); ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.success ? '✅ CSS regenerated' : '❌ Error: ' + data.data.message);
            if (data.success) location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Network error');
        });
    }

    function debugPaletteStructure() {
        console.log('🔍 Palette Structure Debug');
        const config = <?php echo json_encode($config); ?>;
        console.log('Raw config:', config);

        if (config.paletteData) {
            console.log('Palette data:', config.paletteData);
            if (config.paletteData.colors) {
                console.log('Colors:', config.paletteData.colors);
                Object.entries(config.paletteData.colors).forEach(([role, data]) => {
                    console.log(`${role}:`, data);
                });
            }
        }
    }

    function testHookExecution() {
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'test_hook_execution',
                nonce: '<?php echo wp_create_nonce('simple_visual_customizer'); ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Hook test result:', data);
            alert(data.success ? '✅ Hook test passed' : '❌ Hook test failed: ' + data.data.message);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Network error');
        });
    }
    </script>
    <?php
}

/**
 * AJAX Handler: Force CSS Regeneration
 */
function handle_force_css_regeneration() {
    if (!wp_verify_nonce($_POST['nonce'], 'simple_visual_customizer')) {
        wp_send_json_error(['message' => 'Security check failed']);
    }

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Insufficient permissions']);
    }

    $config = get_option('preetidreams_visual_customizer_config', []);

    if (empty($config) || !isset($config['paletteData'])) {
        wp_send_json_error(['message' => 'No palette data found']);
    }

    // Force regenerate CSS
    $css = generate_css_from_palette_data($config['paletteData']);

    if (empty($css)) {
        wp_send_json_error(['message' => 'CSS generation failed - empty result']);
    }

    // Store generated CSS
    update_option('preetidreams_generated_css', $css);

    wp_send_json_success([
        'message' => 'CSS regenerated successfully',
        'css_length' => strlen($css),
        'css_preview' => substr($css, 0, 500)
    ]);
}
add_action('wp_ajax_force_css_regeneration', 'handle_force_css_regeneration');

/**
 * AJAX Handler: Test Hook Execution
 */
function handle_test_hook_execution() {
    if (!wp_verify_nonce($_POST['nonce'], 'simple_visual_customizer')) {
        wp_send_json_error(['message' => 'Security check failed']);
    }

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Insufficient permissions']);
    }

    $results = [];

    // Test function existence
    $results['function_exists'] = function_exists('output_visual_customizer_global_css');

    // Test hook registration
    $results['hook_registered'] = has_action('wp_head', 'output_visual_customizer_global_css');

    // Test manual execution
    ob_start();
    try {
        output_visual_customizer_global_css();
        $output = ob_get_contents();
        $results['manual_execution'] = [
            'success' => true,
            'output_length' => strlen($output),
            'has_style_tag' => strpos($output, '<style') !== false,
            'has_variables' => strpos($output, '--component-bg-color-primary') !== false
        ];
    } catch (Exception $e) {
        $results['manual_execution'] = [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
    ob_end_clean();

    wp_send_json_success($results);
}
add_action('wp_ajax_test_hook_execution', 'handle_test_hook_execution');
