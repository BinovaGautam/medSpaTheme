<?php
/**
 * Footer Debug Tool - WordPress Admin Page
 *
 * BUG-RESOLVER-001 DevKinsta debugging tool for systematic footer issue resolution
 * Accessible via WordPress Admin: Tools > Footer Debugger
 *
 * @package MedSpaTheme
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Footer Debug Tool in WordPress Admin
 */
add_action('admin_menu', 'footer_debug_tool_menu');
function footer_debug_tool_menu() {
    add_management_page(
        'Footer Debug Tool',
        'Footer Debugger',
        'manage_options',
        'footer-debug-tool',
        'footer_debug_tool_page'
    );
}

/**
 * Footer Debug Tool Admin Page
 */
function footer_debug_tool_page() {
    // Handle form submissions
    if (isset($_POST['action']) && check_admin_referer('footer_debug_nonce')) {
        $action = sanitize_text_field($_POST['action']);

        switch ($action) {
            case 'disable_emergency_css':
                footer_debug_disable_emergency_css();
                break;
            case 'enable_emergency_css':
                footer_debug_enable_emergency_css();
                break;
            case 'reset_footer_css':
                footer_debug_reset_footer_css();
                break;
            case 'analyze_css_conflicts':
                footer_debug_analyze_css_conflicts();
                break;
        }
    }

    ?>
    <div class="wrap">
        <h1>🔧 Footer Debug Tool</h1>
        <p><strong>BUG-RESOLVER-001</strong> | Systematic footer debugging for DevKinsta environment</p>

        <?php footer_debug_system_status(); ?>

        <div class="footer-debug-sections">

            <!-- CSS Status Section -->
            <div class="postbox">
                <h2 class="hndle">📊 CSS Loading Status</h2>
                <div class="inside">
                    <?php footer_debug_css_status(); ?>
                </div>
            </div>

            <!-- Quick Actions Section -->
            <div class="postbox">
                <h2 class="hndle">⚡ Quick Actions</h2>
                <div class="inside">
                    <form method="post" style="display: inline;">
                        <?php wp_nonce_field('footer_debug_nonce'); ?>
                        <input type="hidden" name="action" value="disable_emergency_css">
                        <input type="submit" class="button button-secondary" value="Disable Emergency CSS" onclick="return confirm('This will disable emergency CSS fixes. Continue?');">
                    </form>

                    <form method="post" style="display: inline; margin-left: 10px;">
                        <?php wp_nonce_field('footer_debug_nonce'); ?>
                        <input type="hidden" name="action" value="enable_emergency_css">
                        <input type="submit" class="button button-secondary" value="Enable Emergency CSS">
                    </form>

                    <form method="post" style="display: inline; margin-left: 10px;">
                        <?php wp_nonce_field('footer_debug_nonce'); ?>
                        <input type="hidden" name="action" value="reset_footer_css">
                        <input type="submit" class="button button-primary" value="Reset Footer CSS" onclick="return confirm('This will reset all footer CSS to defaults. Continue?');">
                    </form>

                    <form method="post" style="display: inline; margin-left: 10px;">
                        <?php wp_nonce_field('footer_debug_nonce'); ?>
                        <input type="hidden" name="action" value="analyze_css_conflicts">
                        <input type="submit" class="button" value="Analyze CSS Conflicts">
                    </form>
                </div>
            </div>

            <!-- CSS Files Analysis -->
            <div class="postbox">
                <h2 class="hndle">📁 CSS Files Analysis</h2>
                <div class="inside">
                    <?php footer_debug_css_files_analysis(); ?>
                </div>
            </div>

            <!-- Live Footer Preview -->
            <div class="postbox">
                <h2 class="hndle">👁️ Live Footer Preview</h2>
                <div class="inside">
                    <p><strong>Current footer preview:</strong></p>
                    <iframe src="<?php echo home_url(); ?>" width="100%" height="400" style="border: 1px solid #ccc; border-radius: 4px;"></iframe>
                    <p><a href="<?php echo home_url(); ?>" target="_blank" class="button">View Full Site</a></p>
                </div>
            </div>

        </div>
    </div>

    <style>
    .footer-debug-sections {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-top: 20px;
    }
    .footer-debug-sections .postbox {
        margin-bottom: 0;
    }
    .status-good { color: #46b450; font-weight: bold; }
    .status-warning { color: #ffb900; font-weight: bold; }
    .status-error { color: #dc3232; font-weight: bold; }
    .css-file-status {
        margin: 10px 0;
        padding: 10px;
        border-left: 4px solid #ccc;
        background: #f9f9f9;
    }
    .css-file-status.exists { border-left-color: #46b450; }
    .css-file-status.missing { border-left-color: #dc3232; }
    </style>
    <?php
}

/**
 * Display system status
 */
function footer_debug_system_status() {
    $status = footer_debug_get_system_status();

    echo '<div class="notice notice-info">';
    echo '<h3>🔍 System Status</h3>';
    echo '<p><strong>DevKinsta Status:</strong> <span class="status-good">✅ Running</span></p>';
    echo '<p><strong>WordPress Admin:</strong> <span class="status-good">✅ Accessible</span></p>';
    echo '<p><strong>Theme:</strong> ' . get_stylesheet() . '</p>';
    echo '<p><strong>Emergency CSS Status:</strong> ' . $status['emergency_css'] . '</p>';
    echo '</div>';
}

/**
 * Display CSS loading status
 */
function footer_debug_css_status() {
    global $wp_styles;

    $footer_css_handles = [
        'footer-emergency-fixes',
        'footer-component-styles',
        'footer-luxury-styles',
        'footer-tokenized',
        'footer-tokens'
    ];

    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>CSS Handle</th><th>Status</th><th>Dependencies</th><th>Version</th></tr></thead>';
    echo '<tbody>';

    foreach ($footer_css_handles as $handle) {
        $registered = wp_style_is($handle, 'registered');
        $enqueued = wp_style_is($handle, 'enqueued');

        if ($registered) {
            $style = $wp_styles->registered[$handle];
            $status = $enqueued ? '<span class="status-good">✅ Enqueued</span>' : '<span class="status-warning">⚠️ Registered</span>';
            $deps = !empty($style->deps) ? implode(', ', $style->deps) : 'None';
            $version = $style->ver ?: 'None';
        } else {
            $status = '<span class="status-error">❌ Not Registered</span>';
            $deps = 'N/A';
            $version = 'N/A';
        }

        echo "<tr><td><strong>$handle</strong></td><td>$status</td><td>$deps</td><td>$version</td></tr>";
    }

    echo '</tbody></table>';
}

/**
 * Analyze CSS files
 */
function footer_debug_css_files_analysis() {
    $css_files = [
        'Emergency Fixes' => 'assets/css/components/footer-emergency-fixes.css',
        'Footer Component' => 'assets/css/components/footer.css',
        'Footer Luxury' => 'assets/css/components/footer-luxury.css',
        'Footer Tokenized' => 'assets/css/components/footer-tokenized.css',
        'Footer Tokens' => 'assets/css/tokens/footer-tokens.css',
        'Main Theme' => 'style.css'
    ];

    foreach ($css_files as $name => $path) {
        $full_path = get_template_directory() . '/' . $path;
        $exists = file_exists($full_path);
        $size = $exists ? size_format(filesize($full_path)) : 'N/A';
        $modified = $exists ? date('Y-m-d H:i:s', filemtime($full_path)) : 'N/A';

        $class = $exists ? 'exists' : 'missing';
        $status = $exists ? '✅ Exists' : '❌ Missing';

        echo "<div class='css-file-status $class'>";
        echo "<strong>$name</strong> - $status<br>";
        echo "Path: <code>$path</code><br>";
        echo "Size: $size | Modified: $modified";
        echo "</div>";
    }
}

/**
 * Get system status
 */
function footer_debug_get_system_status() {
    $emergency_css_exists = file_exists(get_template_directory() . '/assets/css/components/footer-emergency-fixes.css');
    $emergency_css_enqueued = wp_style_is('footer-emergency-fixes', 'enqueued');

    if ($emergency_css_exists && $emergency_css_enqueued) {
        $emergency_status = '<span class="status-warning">⚠️ Active</span>';
    } elseif ($emergency_css_exists) {
        $emergency_status = '<span class="status-warning">⚠️ Available but not enqueued</span>';
    } else {
        $emergency_status = '<span class="status-good">✅ Disabled</span>';
    }

    return [
        'emergency_css' => $emergency_status
    ];
}

/**
 * Disable emergency CSS
 */
function footer_debug_disable_emergency_css() {
    $emergency_file = get_template_directory() . '/assets/css/components/footer-emergency-fixes.css';
    $backup_file = get_template_directory() . '/assets/css/components/footer-emergency-fixes.css.disabled';

    if (file_exists($emergency_file)) {
        if (rename($emergency_file, $backup_file)) {
            echo '<div class="notice notice-success"><p>✅ Emergency CSS disabled successfully.</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>❌ Failed to disable emergency CSS.</p></div>';
        }
    } else {
        echo '<div class="notice notice-warning"><p>⚠️ Emergency CSS already disabled.</p></div>';
    }
}

/**
 * Enable emergency CSS
 */
function footer_debug_enable_emergency_css() {
    $emergency_file = get_template_directory() . '/assets/css/components/footer-emergency-fixes.css';
    $backup_file = get_template_directory() . '/assets/css/components/footer-emergency-fixes.css.disabled';

    if (file_exists($backup_file)) {
        if (rename($backup_file, $emergency_file)) {
            echo '<div class="notice notice-success"><p>✅ Emergency CSS enabled successfully.</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>❌ Failed to enable emergency CSS.</p></div>';
        }
    } else {
        echo '<div class="notice notice-warning"><p>⚠️ Emergency CSS backup not found.</p></div>';
    }
}

/**
 * Reset footer CSS
 */
function footer_debug_reset_footer_css() {
    // This would restore original footer styles
    echo '<div class="notice notice-info"><p>🔄 Footer CSS reset functionality - Implementation pending...</p></div>';
}

/**
 * Analyze CSS conflicts
 */
function footer_debug_analyze_css_conflicts() {
    echo '<div class="notice notice-info"><p>🔍 CSS conflict analysis - Implementation pending...</p></div>';
}
?>
