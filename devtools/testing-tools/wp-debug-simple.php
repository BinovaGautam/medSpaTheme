<?php
/*
Plugin Name: Visual Customizer Debug
Description: Simple debug page for Visual Customizer script loading issues
Version: 1.0.0
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    die('Direct access not permitted');
}

// Add admin menu
add_action('admin_menu', 'vc_debug_admin_menu');
function vc_debug_admin_menu() {
    add_submenu_page(
        'tools.php',
        'Visual Customizer Debug',
        'VC Debug',
        'manage_options',
        'vc-debug',
        'vc_debug_page'
    );
}

// Debug page content
function vc_debug_page() {
    global $wp_scripts;
    ?>
    <div class="wrap">
        <h1>🔧 Visual Customizer Debug - PVC-009</h1>

        <div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; border: 1px solid #ddd;">
            <h2>WordPress Environment</h2>
            <table class="widefat">
                <tr>
                    <td><strong>Current User ID:</strong></td>
                    <td><?php echo get_current_user_id(); ?></td>
                </tr>
                <tr>
                    <td><strong>Can Manage Options:</strong></td>
                    <td style="color: <?php echo current_user_can('manage_options') ? 'green' : 'red'; ?>">
                        <?php echo current_user_can('manage_options') ? '✅ YES' : '❌ NO'; ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Is Admin:</strong></td>
                    <td style="color: <?php echo is_admin() ? 'green' : 'orange'; ?>">
                        <?php echo is_admin() ? '✅ YES' : '⚠️ NO (Frontend)'; ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>PREETIDREAMS_VERSION:</strong></td>
                    <td><?php echo defined('PREETIDREAMS_VERSION') ? PREETIDREAMS_VERSION : 'NOT DEFINED'; ?></td>
                </tr>
                <tr>
                    <td><strong>Theme Directory:</strong></td>
                    <td><?php echo get_template_directory(); ?></td>
                </tr>
            </table>
        </div>

        <div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; border: 1px solid #ddd;">
            <h2>Function Status</h2>
            <table class="widefat">
                <tr>
                    <td><strong>simple_visual_customizer_enqueue_assets:</strong></td>
                    <td style="color: <?php echo function_exists('simple_visual_customizer_enqueue_assets') ? 'green' : 'red'; ?>">
                        <?php echo function_exists('simple_visual_customizer_enqueue_assets') ? '✅ EXISTS' : '❌ NOT FOUND'; ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>visual-customizer-simple.php:</strong></td>
                    <td style="color: <?php echo file_exists(get_template_directory() . '/inc/visual-customizer-simple.php') ? 'green' : 'red'; ?>">
                        <?php echo file_exists(get_template_directory() . '/inc/visual-customizer-simple.php') ? '✅ EXISTS' : '❌ NOT FOUND'; ?>
                    </td>
                </tr>
            </table>
        </div>

        <div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; border: 1px solid #ddd;">
            <h2>Script Registration Status</h2>
            <pre style="background: #f1f1f1; padding: 15px; overflow-x: auto;">
<?php
$scripts_to_check = [
    'live-preview-system',
    'simple-visual-customizer',
    'color-palette-interface',
    'semantic-color-system',
    'color-system-manager',
    'preview-messenger',
    'wp-customizer-bridge'
];

echo "Total Scripts Registered: " . count($wp_scripts->registered) . "\n\n";
echo "Visual Customizer Scripts:\n";
echo str_repeat("=", 40) . "\n";

foreach ($scripts_to_check as $script_handle) {
    $registered = isset($wp_scripts->registered[$script_handle]);
    $enqueued = in_array($script_handle, $wp_scripts->queue);

    echo "• {$script_handle}:\n";
    echo "  Registered: " . ($registered ? "✅ YES" : "❌ NO") . "\n";
    echo "  Enqueued: " . ($enqueued ? "✅ YES" : "❌ NO") . "\n";

    if ($registered) {
        $script = $wp_scripts->registered[$script_handle];
        echo "  URL: " . $script->src . "\n";
        echo "  Dependencies: " . implode(', ', $script->deps) . "\n";
    }
    echo "\n";
}
?>
            </pre>
        </div>

        <div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; border: 1px solid #ddd;">
            <h2>File Existence Check</h2>
            <pre style="background: #f1f1f1; padding: 15px; overflow-x: auto;">
<?php
$js_files_to_check = [
    '/assets/js/live-preview-system.js',
    '/assets/js/simple-visual-customizer.js',
    '/assets/js/color-palette-interface.js',
    '/assets/js/semantic-color-system.js',
    '/assets/js/color-system-manager.js',
    '/assets/css/simple-visual-customizer.css'
];

foreach ($js_files_to_check as $file_path) {
    $full_path = get_template_directory() . $file_path;
    $exists = file_exists($full_path);
    $size = $exists ? filesize($full_path) : 0;

    echo "• {$file_path}:\n";
    echo "  Exists: " . ($exists ? "✅ YES" : "❌ NO") . "\n";
    echo "  Size: " . ($size > 0 ? number_format($size) . " bytes" : "0 bytes") . "\n";
    echo "  URL: " . get_template_directory_uri() . $file_path . "\n\n";
}
?>
            </pre>
        </div>

        <div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; border: 1px solid #ddd;">
            <h2>WordPress Hooks Analysis</h2>
            <pre style="background: #f1f1f1; padding: 15px; overflow-x: auto;">
<?php
echo "WordPress Action Hooks:\n";
echo str_repeat("=", 30) . "\n";

// Check wp_enqueue_scripts hooks
$wp_enqueue_hooks = $GLOBALS['wp_filter']['wp_enqueue_scripts'] ?? null;
echo "wp_enqueue_scripts hooks:\n";
if ($wp_enqueue_hooks) {
    $found_vc_hooks = false;
    foreach ($wp_enqueue_hooks->callbacks as $priority => $callbacks) {
        foreach ($callbacks as $callback_id => $callback_data) {
            if (is_array($callback_data['function'])) {
                $function_name = get_class($callback_data['function'][0]) . '::' . $callback_data['function'][1];
            } else {
                $function_name = $callback_data['function'];
            }

            if (strpos($function_name, 'visual_customizer') !== false ||
                strpos($function_name, 'simple_visual_customizer') !== false) {
                echo "  ✅ Priority {$priority}: {$function_name}\n";
                $found_vc_hooks = true;
            }
        }
    }
    if (!$found_vc_hooks) {
        echo "  ❌ No Visual Customizer hooks found\n";
    }
} else {
    echo "  ❌ No wp_enqueue_scripts hooks found\n";
}

// Check admin_enqueue_scripts hooks
$admin_enqueue_hooks = $GLOBALS['wp_filter']['admin_enqueue_scripts'] ?? null;
echo "\nadmin_enqueue_scripts hooks:\n";
if ($admin_enqueue_hooks) {
    $found_admin_vc_hooks = false;
    foreach ($admin_enqueue_hooks->callbacks as $priority => $callbacks) {
        foreach ($callbacks as $callback_id => $callback_data) {
            if (is_array($callback_data['function'])) {
                $function_name = get_class($callback_data['function'][0]) . '::' . $callback_data['function'][1];
            } else {
                $function_name = $callback_data['function'];
            }

            if (strpos($function_name, 'visual_customizer') !== false ||
                strpos($function_name, 'simple_visual_customizer') !== false) {
                echo "  ✅ Priority {$priority}: {$function_name}\n";
                $found_admin_vc_hooks = true;
            }
        }
    }
    if (!$found_admin_vc_hooks) {
        echo "  ❌ No Visual Customizer admin hooks found\n";
    }
} else {
    echo "  ❌ No admin_enqueue_scripts hooks found\n";
}
?>
            </pre>
        </div>

        <div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; border: 1px solid #ddd;">
            <h2>Test Actions</h2>
            <p><strong>Manual Test:</strong> Try calling the enqueue function manually:</p>
            <?php if (function_exists('simple_visual_customizer_enqueue_assets')): ?>
                <button type="button" class="button button-primary" onclick="testManualEnqueue()">Test Manual Enqueue</button>
                <div id="test-results" style="margin-top: 15px; padding: 10px; background: #f9f9f9; display: none;"></div>

                <script>
                function testManualEnqueue() {
                    var results = document.getElementById('test-results');
                    results.style.display = 'block';
                    results.innerHTML = 'Testing manual enqueue...<br>';

                    // Make AJAX call to test manual enqueue
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', ajaxurl, true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4) {
                            if (xhr.status === 200) {
                                try {
                                    var response = JSON.parse(xhr.responseText);
                                    results.innerHTML += '✅ Manual enqueue test completed<br>';
                                    results.innerHTML += 'Response: ' + JSON.stringify(response, null, 2);
                                } catch (e) {
                                    results.innerHTML += '❌ Invalid JSON response<br>';
                                    results.innerHTML += 'Raw response: ' + xhr.responseText;
                                }
                            } else {
                                results.innerHTML += '❌ AJAX error: ' + xhr.status + '<br>';
                            }
                        }
                    };
                    xhr.send('action=test_manual_enqueue&nonce=<?php echo wp_create_nonce('test_manual_enqueue'); ?>');
                }
                </script>
            <?php else: ?>
                <p style="color: red;">❌ Function simple_visual_customizer_enqueue_assets not available for testing</p>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

// AJAX handler for manual enqueue test
add_action('wp_ajax_test_manual_enqueue', 'handle_test_manual_enqueue');
function handle_test_manual_enqueue() {
    if (!wp_verify_nonce($_POST['nonce'], 'test_manual_enqueue') || !current_user_can('manage_options')) {
        wp_die('Security check failed');
    }

    global $wp_scripts;
    $before_count = count($wp_scripts->registered);

    // Call the enqueue function manually
    if (function_exists('simple_visual_customizer_enqueue_assets')) {
        simple_visual_customizer_enqueue_assets();
        $after_count = count($wp_scripts->registered);

        wp_send_json_success([
            'message' => 'Manual enqueue completed',
            'scripts_before' => $before_count,
            'scripts_after' => $after_count,
            'scripts_added' => $after_count - $before_count,
            'user_can_manage' => current_user_can('manage_options'),
            'function_called' => true
        ]);
    } else {
        wp_send_json_error([
            'message' => 'Function simple_visual_customizer_enqueue_assets not found',
            'function_called' => false
        ]);
    }
}
?>
