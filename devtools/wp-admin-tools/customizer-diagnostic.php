<?php
/**
 * WordPress Customizer Diagnostic Tool
 *
 * Tests customizer functionality and identifies issues
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
    <title>WordPress Customizer Diagnostic</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .diagnostic-section { border: 1px solid #ddd; padding: 15px; margin: 10px 0; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>🔍 WordPress Customizer Diagnostic</h1>

    <div class="diagnostic-section">
        <h2>System Environment</h2>
        <p><strong>WordPress Version:</strong> <?php echo get_bloginfo('version'); ?></p>
        <p><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></p>
        <p><strong>Theme:</strong> <?php echo wp_get_theme()->get('Name') . ' v' . wp_get_theme()->get('Version'); ?></p>
        <p><strong>Debug Mode:</strong>
            <span class="<?php echo WP_DEBUG ? 'warning' : 'success'; ?>">
                <?php echo WP_DEBUG ? '⚠️ ENABLED' : '✅ DISABLED'; ?>
            </span>
        </p>
    </div>

    <div class="diagnostic-section">
        <h2>Customizer Core Functions</h2>

        <?php
        $functions_to_check = [
            'WP_Customize_Manager' => class_exists('WP_Customize_Manager'),
            'get_theme_mod' => function_exists('get_theme_mod'),
            'set_theme_mod' => function_exists('set_theme_mod'),
            'is_customize_preview' => function_exists('is_customize_preview'),
            'customize_register action' => has_action('customize_register')
        ];

        foreach ($functions_to_check as $item => $exists) {
            echo '<p><strong>' . $item . ':</strong> ';
            echo '<span class="' . ($exists ? 'success' : 'error') . '">';
            echo $exists ? '✅ Available' : '❌ Missing';
            echo '</span></p>';
        }
        ?>
    </div>

    <div class="diagnostic-section">
        <h2>Visual Customizer Integration</h2>

        <?php
        $visual_customizer_checks = [
            'visual-customizer-simple.php' => file_exists(get_template_directory() . '/inc/visual-customizer-simple.php'),
            'simple_visual_customizer_admin_bar function' => function_exists('simple_visual_customizer_admin_bar'),
            'Visual Customizer Admin Bar Hook' => has_action('admin_bar_menu', 'simple_visual_customizer_admin_bar'),
        ];

        foreach ($visual_customizer_checks as $item => $exists) {
            echo '<p><strong>' . $item . ':</strong> ';
            echo '<span class="' . ($exists ? 'success' : 'error') . '">';
            echo $exists ? '✅ OK' : '❌ Missing';
            echo '</span></p>';
        }
        ?>
    </div>

    <div class="diagnostic-section">
        <h2>Theme Customizer Panels/Sections</h2>

        <?php
        // Simulate customizer registration
        global $wp_customize;
        if (!$wp_customize) {
            require_once(ABSPATH . WPINC . '/class-wp-customize-manager.php');
            $wp_customize = new WP_Customize_Manager();
        }

        // Trigger customizer registration
        do_action('customize_register', $wp_customize);

        $panels = $wp_customize->panels();
        $sections = $wp_customize->sections();
        $controls = $wp_customize->controls();

        echo '<p><strong>Registered Panels:</strong> ' . count($panels) . '</p>';
        echo '<p><strong>Registered Sections:</strong> ' . count($sections) . '</p>';
        echo '<p><strong>Registered Controls:</strong> ' . count($controls) . '</p>';

        if (!empty($panels)) {
            echo '<h4>Theme Panels:</h4><ul>';
            foreach ($panels as $panel_id => $panel) {
                if (strpos($panel_id, 'visual') !== false || strpos($panel_id, 'design') !== false) {
                    echo '<li><strong>' . $panel_id . ':</strong> ' . $panel->title . '</li>';
                }
            }
            echo '</ul>';
        }
        ?>
    </div>

    <div class="diagnostic-section">
        <h2>Error Log Check</h2>

        <?php
        $debug_log = WP_CONTENT_DIR . '/debug.log';
        if (file_exists($debug_log)) {
            $log_content = file_get_contents($debug_log);
            $recent_lines = array_slice(explode("\n", $log_content), -20, 20);

            $customizer_errors = array_filter($recent_lines, function($line) {
                return strpos($line, 'customize') !== false ||
                       strpos($line, 'Customizer') !== false ||
                       strpos($line, 'Fatal error') !== false;
            });

            if (!empty($customizer_errors)) {
                echo '<h4 class="error">Recent Customizer-Related Errors:</h4>';
                echo '<pre>' . implode("\n", $customizer_errors) . '</pre>';
            } else {
                echo '<p class="success">✅ No recent customizer errors found</p>';
            }
        } else {
            echo '<p class="warning">⚠️ Debug log file not found</p>';
        }
        ?>
    </div>

    <div class="diagnostic-section">
        <h2>Direct Customizer Test</h2>

        <p><strong>Customizer URL Test:</strong></p>

        <?php
        $customizer_url = admin_url('customize.php');
        echo '<p><a href="' . $customizer_url . '" target="_blank">🔗 Open WordPress Customizer</a></p>';

        // Test if we can create a simple customizer instance
        try {
            $test_customize = new WP_Customize_Manager();
            echo '<p class="success">✅ WP_Customize_Manager can be instantiated</p>';
        } catch (Exception $e) {
            echo '<p class="error">❌ Error creating WP_Customize_Manager: ' . $e->getMessage() . '</p>';
        }
        ?>
    </div>

    <div class="diagnostic-section">
        <h2>Admin Bar Check</h2>

        <?php
        global $wp_admin_bar;
        if (is_admin_bar_showing()) {
            echo '<p class="success">✅ Admin bar is enabled</p>';

            if ($wp_admin_bar) {
                $visual_customizer_node = $wp_admin_bar->get_node('visual-customizer');
                if ($visual_customizer_node) {
                    echo '<p class="success">✅ Visual Customizer admin bar node found</p>';
                    echo '<pre>' . print_r($visual_customizer_node, true) . '</pre>';
                } else {
                    echo '<p class="error">❌ Visual Customizer admin bar node not found</p>';
                }
            }
        } else {
            echo '<p class="warning">⚠️ Admin bar is not showing</p>';
        }
        ?>
    </div>

    <div class="diagnostic-section">
        <h2>Quick Actions</h2>

        <p><a href="<?php echo admin_url('customize.php'); ?>" target="_blank">🎨 Open WordPress Customizer</a></p>
        <p><a href="<?php echo home_url(); ?>" target="_blank">🏠 View Frontend</a></p>
        <p><a href="<?php echo admin_url(); ?>" target="_blank">📊 WordPress Admin</a></p>
    </div>

</body>
</html>
