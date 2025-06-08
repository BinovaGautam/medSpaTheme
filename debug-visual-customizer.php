<?php
/**
 * Debug Visual Customizer Loading
 * Access via: http://localhost:10004/medspaa/wp-content/themes/medSpaTheme/debug-visual-customizer.php
 */

// Load WordPress
require_once('../../../wp-load.php');

// Force user to be logged in as admin for testing
if (!is_user_logged_in()) {
    // Auto-login as admin for testing (REMOVE IN PRODUCTION)
    $admin_user = get_user_by('login', 'admin');
    if ($admin_user) {
        wp_set_current_user($admin_user->ID);
        wp_set_auth_cookie($admin_user->ID);
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Visual Customizer Debug</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f0f0f0; }
        .debug-section { background: white; padding: 20px; margin: 10px 0; border-radius: 8px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .info { color: blue; }
    </style>
    <?php wp_head(); ?>
</head>
<body>

<h1>🔧 Visual Customizer Debug Page</h1>

<div class="debug-section">
    <h2>User Authentication Status</h2>
    <p><strong>User Logged In:</strong> <span class="<?php echo is_user_logged_in() ? 'success' : 'error'; ?>">
        <?php echo is_user_logged_in() ? '✅ YES' : '❌ NO'; ?>
    </span></p>

    <?php if (is_user_logged_in()): ?>
        <p><strong>Current User ID:</strong> <?php echo get_current_user_id(); ?></p>
        <p><strong>Current User Login:</strong> <?php echo wp_get_current_user()->user_login; ?></p>
        <p><strong>Can Manage Options:</strong> <span class="<?php echo current_user_can('manage_options') ? 'success' : 'error'; ?>">
            <?php echo current_user_can('manage_options') ? '✅ YES' : '❌ NO'; ?>
        </span></p>
    <?php endif; ?>
</div>

<div class="debug-section">
    <h2>File Existence Check</h2>
    <p><strong>simple-visual-customizer.js:</strong>
        <span class="<?php echo file_exists(get_template_directory() . '/assets/js/simple-visual-customizer.js') ? 'success' : 'error'; ?>">
            <?php echo file_exists(get_template_directory() . '/assets/js/simple-visual-customizer.js') ? '✅ EXISTS' : '❌ NOT FOUND'; ?>
        </span>
    </p>

    <p><strong>simple-visual-customizer.css:</strong>
        <span class="<?php echo file_exists(get_template_directory() . '/assets/css/simple-visual-customizer.css') ? 'success' : 'error'; ?>">
            <?php echo file_exists(get_template_directory() . '/assets/css/simple-visual-customizer.css') ? '✅ EXISTS' : '❌ NOT FOUND'; ?>
        </span>
    </p>

    <p><strong>visual-customizer-simple.php:</strong>
        <span class="<?php echo file_exists(get_template_directory() . '/inc/visual-customizer-simple.php') ? 'success' : 'error'; ?>">
            <?php echo file_exists(get_template_directory() . '/inc/visual-customizer-simple.php') ? '✅ EXISTS' : '❌ NOT FOUND'; ?>
        </span>
    </p>
</div>

<div class="debug-section">
    <h2>Script Loading Test</h2>
    <p>Manually calling the enqueue function...</p>

    <?php
    // Manually call the enqueue function to see what happens
    if (function_exists('enqueue_simple_visual_customizer_scripts')) {
        echo '<p class="info">📞 Calling enqueue_simple_visual_customizer_scripts()...</p>';

        // Capture any output
        ob_start();
        enqueue_simple_visual_customizer_scripts();
        $output = ob_get_clean();

        if ($output) {
            echo '<p class="warning">⚠️ Function output: ' . htmlspecialchars($output) . '</p>';
        } else {
            echo '<p class="success">✅ Function called without errors</p>';
        }
    } else {
        echo '<p class="error">❌ enqueue_simple_visual_customizer_scripts function not found</p>';
    }
    ?>
</div>

<div class="debug-section">
    <h2>Admin Bar Test</h2>
    <p>Testing if admin bar Visual Customizer appears...</p>

    <?php
    global $wp_admin_bar;
    if ($wp_admin_bar) {
        echo '<p class="info">📊 Admin bar object exists</p>';

        // Check if our node exists
        $node = $wp_admin_bar->get_node('visual-customizer');
        if ($node) {
            echo '<p class="success">✅ Visual Customizer admin bar node found!</p>';
            echo '<pre>' . print_r($node, true) . '</pre>';
        } else {
            echo '<p class="error">❌ Visual Customizer admin bar node not found</p>';
        }
    } else {
        echo '<p class="warning">⚠️ Admin bar object not available</p>';
    }
    ?>
</div>

<div class="debug-section">
    <h2>JavaScript Test</h2>
    <p>Testing if Visual Customizer JavaScript is loaded...</p>

    <button id="test-visual-customizer" onclick="testVisualCustomizer()">🧪 Test Visual Customizer</button>
    <div id="test-results"></div>

    <script>
    function testVisualCustomizer() {
        const results = document.getElementById('test-results');
        let html = '<h4>JavaScript Test Results:</h4>';

        // Check if jQuery is loaded
        html += '<p><strong>jQuery:</strong> ' + (typeof jQuery !== 'undefined' ? '✅ Loaded' : '❌ Not loaded') + '</p>';

        // Check if simpleCustomizer config is available
        html += '<p><strong>simpleCustomizer config:</strong> ' + (typeof simpleCustomizer !== 'undefined' ? '✅ Available' : '❌ Not available') + '</p>';

        if (typeof simpleCustomizer !== 'undefined') {
            html += '<pre>' + JSON.stringify(simpleCustomizer, null, 2) + '</pre>';
        }

        // Check if SimpleVisualCustomizer is available
        html += '<p><strong>SimpleVisualCustomizer:</strong> ' + (typeof SimpleVisualCustomizer !== 'undefined' ? '✅ Available' : '❌ Not available') + '</p>';

        // Check if admin bar element exists
        const adminBarElement = document.getElementById('wp-admin-bar-visual-customizer');
        html += '<p><strong>Admin Bar Element:</strong> ' + (adminBarElement ? '✅ Found' : '❌ Not found') + '</p>';

        // Try to manually create and open sidebar
        if (typeof jQuery !== 'undefined') {
            html += '<p><strong>Manual Sidebar Test:</strong> Attempting to create sidebar...</p>';

            try {
                // Create overlay and sidebar manually
                if (jQuery('#simple-vc-overlay').length === 0) {
                    jQuery('body').append('<div id="simple-vc-overlay" class="simple-vc-overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:999998;"></div>');
                }

                if (jQuery('#simple-vc-sidebar').length === 0) {
                    jQuery('body').append(`
                        <div id="simple-vc-sidebar" class="simple-vc-sidebar" style="position:fixed;top:0;right:0;width:400px;height:100%;background:white;z-index:999999;transform:translateX(100%);transition:transform 0.3s ease;">
                            <div style="padding:20px;">
                                <h3>🎨 Visual Customizer Test</h3>
                                <p>✅ Sidebar created successfully!</p>
                                <button onclick="jQuery('#simple-vc-sidebar').css('transform', 'translateX(100%)'); jQuery('#simple-vc-overlay').remove();">Close</button>
                            </div>
                        </div>
                    `);
                }

                // Show sidebar
                jQuery('#simple-vc-sidebar').css('transform', 'translateX(0)');

                html += '<p class="success">✅ Manual sidebar created and shown!</p>';
            } catch (error) {
                html += '<p class="error">❌ Manual sidebar creation failed: ' + error.message + '</p>';
            }
        }

        results.innerHTML = html;
    }

    // Auto-run test after page loads
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(testVisualCustomizer, 1000);
    });
    </script>
</div>

<?php wp_footer(); ?>

</body>
</html>
