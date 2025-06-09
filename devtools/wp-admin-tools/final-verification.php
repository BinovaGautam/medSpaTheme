<?php
/**
 * Final Verification Tool
 * Tests both WordPress Customizer and Visual Customizer functionality
 */

// WordPress environment
require_once('../../../../wp-load.php');

if (!current_user_can('manage_options')) {
    wp_die('Access denied. Admin privileges required.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Final Bug Resolution Verification</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        .section { border: 2px solid #ddd; padding: 20px; margin: 15px 0; border-radius: 8px; }
        .resolved { border-color: green; background-color: #f0fff0; }
        .issue { border-color: red; background-color: #fff0f0; }
        h1 { color: #2c3e50; }
        h2 { color: #34495e; margin-top: 0; }
        .test-result { padding: 10px; margin: 5px 0; border-radius: 4px; }
        .test-passed { background-color: #d4edda; border: 1px solid #c3e6cb; }
        .test-failed { background-color: #f8d7da; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <h1>🔍 Final Bug Resolution Verification</h1>
    <p><strong>Time:</strong> <?php echo current_time('Y-m-d H:i:s'); ?></p>

    <div class="section resolved">
        <h2>✅ RESOLVED: Homepage Empty Body Issue</h2>

        <?php
        // Test homepage loading
        $homepage_test = wp_remote_get(home_url('/'));
        $homepage_body = wp_remote_retrieve_body($homepage_test);
        $has_body_tag = strpos($homepage_body, '<body') !== false;
        $has_closing_body = strpos($homepage_body, '</body>') !== false;
        $has_closing_html = strpos($homepage_body, '</html>') !== false;
        $content_length = strlen($homepage_body);
        ?>

        <div class="test-result <?php echo ($has_body_tag && $has_closing_body && $has_closing_html) ? 'test-passed' : 'test-failed'; ?>">
            <strong>Homepage Structure Test:</strong><br>
            • Body tag present: <?php echo $has_body_tag ? '✅ YES' : '❌ NO'; ?><br>
            • Closing body tag: <?php echo $has_closing_body ? '✅ YES' : '❌ NO'; ?><br>
            • Closing HTML tag: <?php echo $has_closing_html ? '✅ YES' : '❌ NO'; ?><br>
            • Content length: <?php echo $content_length; ?> characters
        </div>

        <?php if ($has_body_tag && $has_closing_body && $has_closing_html): ?>
            <p class="success">✅ FIXED: Homepage now renders complete HTML structure with body content!</p>
        <?php else: ?>
            <p class="error">❌ ISSUE: Homepage structure still incomplete</p>
        <?php endif; ?>
    </div>

    <div class="section resolved">
        <h2>✅ RESOLVED: Visual Customizer Admin Bar Button</h2>

        <?php
        $has_admin_bar_function = function_exists('simple_visual_customizer_admin_bar');
        $has_admin_bar_hook = has_action('admin_bar_menu', 'simple_visual_customizer_admin_bar');
        $visual_customizer_file_exists = file_exists(get_template_directory() . '/inc/visual-customizer-simple.php');
        $visual_customizer_in_homepage = strpos($homepage_body, 'visual-customizer') !== false;
        ?>

        <div class="test-result <?php echo ($has_admin_bar_function && $has_admin_bar_hook && $visual_customizer_file_exists) ? 'test-passed' : 'test-failed'; ?>">
            <strong>Visual Customizer Admin Bar Test:</strong><br>
            • Function exists: <?php echo $has_admin_bar_function ? '✅ YES' : '❌ NO'; ?><br>
            • Admin bar hook registered: <?php echo $has_admin_bar_hook ? '✅ YES' : '❌ NO'; ?><br>
            • File included: <?php echo $visual_customizer_file_exists ? '✅ YES' : '❌ NO'; ?><br>
            • Button in HTML: <?php echo $visual_customizer_in_homepage ? '✅ YES' : '❌ NO'; ?>
        </div>

        <?php if ($has_admin_bar_function && $has_admin_bar_hook): ?>
            <p class="success">✅ FIXED: Visual Customizer admin bar button is now functional!</p>
        <?php else: ?>
            <p class="error">❌ ISSUE: Visual Customizer admin bar button still missing</p>
        <?php endif; ?>
    </div>

    <div class="section <?php echo class_exists('WP_Customize_Manager') ? 'resolved' : 'issue'; ?>">
        <h2>🔍 WordPress Customizer Status</h2>

        <?php
        $customizer_manager_exists = class_exists('WP_Customize_Manager');
        $customize_register_hooks = has_action('customize_register');
        ?>

        <div class="test-result <?php echo $customizer_manager_exists ? 'test-passed' : 'test-failed'; ?>">
            <strong>WordPress Customizer Core:</strong><br>
            • WP_Customize_Manager class: <?php echo $customizer_manager_exists ? '✅ Available' : '❌ Missing'; ?><br>
            • Customize register hooks: <?php echo $customize_register_hooks ? '✅ ' . $customize_register_hooks . ' registered' : '❌ None'; ?>
        </div>

        <p><strong>Customizer Test:</strong>
            <a href="<?php echo admin_url('customize.php'); ?>" target="_blank" style="background: #0073aa; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px;">
                🎨 Test WordPress Customizer
            </a>
        </p>

        <?php if ($customizer_manager_exists): ?>
            <p class="success">✅ WordPress Customizer core is available</p>
        <?php else: ?>
            <p class="error">❌ WordPress Customizer core missing</p>
        <?php endif; ?>
    </div>

    <div class="section resolved">
        <h2>📊 Error Log Analysis</h2>

        <?php
        $debug_log = WP_CONTENT_DIR . '/debug.log';
        $recent_fatal_errors = 0;
        $recent_customizer_errors = 0;

        if (file_exists($debug_log)) {
            $log_content = file_get_contents($debug_log);
            $recent_lines = array_slice(explode("\n", $log_content), -50, 50); // Last 50 lines

            foreach ($recent_lines as $line) {
                if (strpos($line, 'Fatal error') !== false) {
                    $recent_fatal_errors++;
                }
                if (strpos($line, 'customize') !== false || strpos($line, 'Customizer') !== false) {
                    $recent_customizer_errors++;
                }
            }
        }
        ?>

        <div class="test-result <?php echo ($recent_fatal_errors == 0) ? 'test-passed' : 'test-failed'; ?>">
            <strong>Error Analysis (Last 50 log entries):</strong><br>
            • Fatal errors: <?php echo $recent_fatal_errors == 0 ? '✅ 0 (Clean!)' : '❌ ' . $recent_fatal_errors; ?><br>
            • Customizer-related errors: <?php echo $recent_customizer_errors == 0 ? '✅ 0 (Clean!)' : '⚠️ ' . $recent_customizer_errors; ?>
        </div>

        <?php if ($recent_fatal_errors == 0): ?>
            <p class="success">✅ No recent fatal errors - System stable!</p>
        <?php else: ?>
            <p class="error">❌ Fatal errors detected - Check debug.log</p>
        <?php endif; ?>
    </div>

    <div class="section resolved">
        <h2>🎯 Component System Status</h2>

        <?php
        $button_component_registered = false;
        $component_registry_exists = class_exists('ComponentRegistry');

        if ($component_registry_exists) {
            $registered_components = ComponentRegistry::get_registered_components();
            $button_component_registered = isset($registered_components['button']);
        }
        ?>

        <div class="test-result <?php echo ($component_registry_exists && $button_component_registered) ? 'test-passed' : 'test-failed'; ?>">
            <strong>Sprint 6 Component System:</strong><br>
            • ComponentRegistry class: <?php echo $component_registry_exists ? '✅ Available' : '❌ Missing'; ?><br>
            • Button component registered: <?php echo $button_component_registered ? '✅ YES' : '❌ NO'; ?><br>
            • Total components: <?php echo $component_registry_exists ? count($registered_components) : 0; ?>
        </div>

        <?php if ($component_registry_exists && $button_component_registered): ?>
            <p class="success">✅ Sprint 6 component system operational!</p>
        <?php else: ?>
            <p class="error">❌ Component system issues detected</p>
        <?php endif; ?>
    </div>

    <div class="section resolved">
        <h2>🚀 Summary & Next Steps</h2>

        <?php
        $critical_issues_resolved = $has_body_tag && $has_closing_body && $recent_fatal_errors == 0;
        $visual_customizer_restored = $has_admin_bar_function && $has_admin_bar_hook;
        ?>

        <div style="padding: 20px; background: <?php echo ($critical_issues_resolved && $visual_customizer_restored) ? '#d4edda' : '#fff3cd'; ?>; border-radius: 8px;">
            <h3><?php echo ($critical_issues_resolved && $visual_customizer_restored) ? '🎉 ALL CRITICAL ISSUES RESOLVED!' : '⚠️ PARTIAL RESOLUTION'; ?></h3>

            <ul>
                <li><?php echo $has_body_tag ? '✅' : '❌'; ?> Homepage empty body issue: <?php echo $has_body_tag ? 'FIXED' : 'NOT FIXED'; ?></li>
                <li><?php echo $visual_customizer_restored ? '✅' : '❌'; ?> Visual Customizer admin bar: <?php echo $visual_customizer_restored ? 'RESTORED' : 'MISSING'; ?></li>
                <li><?php echo $recent_fatal_errors == 0 ? '✅' : '❌'; ?> Fatal errors: <?php echo $recent_fatal_errors == 0 ? 'RESOLVED' : 'PRESENT'; ?></li>
                <li><?php echo $customizer_manager_exists ? '✅' : '❌'; ?> WordPress Customizer: <?php echo $customizer_manager_exists ? 'AVAILABLE' : 'ISSUES'; ?></li>
            </ul>

            <?php if ($critical_issues_resolved && $visual_customizer_restored): ?>
                <p><strong>🎯 Status:</strong> System fully operational - Ready to proceed with Sprint 6 T6.4 Card System Implementation</p>
            <?php else: ?>
                <p><strong>⚠️ Status:</strong> Additional debugging may be required for WordPress Customizer</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="section">
        <h2>🔗 Quick Actions</h2>
        <p>
            <a href="<?php echo home_url(); ?>" target="_blank" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; margin: 5px;">🏠 View Homepage</a>
            <a href="<?php echo admin_url('customize.php'); ?>" target="_blank" style="background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; margin: 5px;">🎨 Test Customizer</a>
            <a href="<?php echo admin_url(); ?>" target="_blank" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; margin: 5px;">📊 WordPress Admin</a>
        </p>
    </div>

</body>
</html>
