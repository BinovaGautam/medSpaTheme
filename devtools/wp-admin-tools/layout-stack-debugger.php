<?php
/**
 * Layout Stack Debugger - WordPress Admin Tool
 *
 * Detects and fixes layout stacking issues caused by fixed position overlays
 *
 * @package MedSpaTheme
 * @subpackage DevTools
 * @since 2.0.0
 * @author BUG-RESOLVER-001
 * @workflow BUG-RESOLUTION-WF-001
 */

// Security check
if (!defined('ABSPATH')) {
    exit;
}

// Admin menu registration
add_action('admin_menu', 'layout_stack_debugger_admin_menu');
function layout_stack_debugger_admin_menu() {
    add_management_page(
        'Layout Stack Debugger',
        'Layout Stack Debugger',
        'manage_options',
        'layout-stack-debugger',
        'layout_stack_debugger_admin_page'
    );
}

// Admin page content
function layout_stack_debugger_admin_page() {
    $current_site_url = home_url();
    $treatments_url = home_url('/treatments/');

    // Handle form submissions
    if (isset($_POST['action'])) {
        check_admin_referer('layout_debugger_action');

        switch ($_POST['action']) {
            case 'fix_overlays':
                layout_stack_fix_overlays();
                echo '<div class="notice notice-success"><p>✅ Overlay fixes applied!</p></div>';
                break;
            case 'scan_issues':
                $issues = layout_stack_scan_issues();
                break;
        }
    }

    ?>
    <div class="wrap">
        <h1>🔧 Layout Stack Debugger</h1>
        <p>Debug and fix layout stacking issues caused by fixed position overlays.</p>

        <div class="card">
            <h2>🚨 Emergency Layout Fix</h2>
            <p>If elements are stacked on top of each other, click this to force-hide problematic overlays:</p>
            <form method="post">
                <?php wp_nonce_field('layout_debugger_action'); ?>
                <input type="hidden" name="action" value="fix_overlays">
                <button type="submit" class="button button-primary button-large">
                    🛠️ Apply Emergency Fix
                </button>
            </form>
        </div>

        <div class="card">
            <h2>🔍 Layout Issue Scanner</h2>
            <form method="post">
                <?php wp_nonce_field('layout_debugger_action'); ?>
                <input type="hidden" name="action" value="scan_issues">
                <button type="submit" class="button button-secondary">
                    🔎 Scan for Layout Issues
                </button>
            </form>

            <?php if (isset($issues)): ?>
                <h3>📊 Scan Results</h3>
                <div class="layout-scan-results">
                    <?php foreach ($issues as $issue): ?>
                        <div class="notice notice-<?php echo $issue['type']; ?> inline">
                            <p><strong><?php echo esc_html($issue['title']); ?></strong></p>
                            <p><?php echo esc_html($issue['description']); ?></p>
                            <?php if (!empty($issue['file'])): ?>
                                <p><em>File: <?php echo esc_html($issue['file']); ?></em></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2>🌐 Test Links</h2>
            <p>Test these pages to verify layout is working correctly:</p>
            <ul>
                <li><a href="<?php echo esc_url($current_site_url); ?>" target="_blank">🏠 Homepage</a></li>
                <li><a href="<?php echo esc_url($treatments_url); ?>" target="_blank">💊 Treatments Page</a></li>
                <li><a href="<?php echo esc_url(home_url('/about/')); ?>" target="_blank">ℹ️ About Page</a></li>
                <li><a href="<?php echo esc_url(home_url('/contact/')); ?>" target="_blank">📞 Contact Page</a></li>
            </ul>
        </div>

        <div class="card">
            <h2>🎛️ Visual Customizer Status</h2>
            <?php layout_stack_check_customizer_status(); ?>
        </div>

        <div class="card">
            <h2>📋 CSS Files Status</h2>
            <?php layout_stack_check_css_files(); ?>
        </div>
    </div>

    <style>
    .card {
        background: white;
        border: 1px solid #ccd0d4;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
        margin: 20px 0;
        padding: 20px;
    }
    .layout-scan-results {
        margin-top: 15px;
    }
    .button-large {
        height: auto;
        line-height: 1.5;
        padding: 10px 20px;
        font-size: 16px;
    }
    .notice.inline {
        margin: 10px 0;
        padding: 8px 12px;
    }
    </style>
    <?php
}

// Apply emergency overlay fixes
function layout_stack_fix_overlays() {
    $css_fix = "
/* EMERGENCY LAYOUT FIX - Applied via Layout Stack Debugger */
.simple-vc-overlay {
    display: none !important;
    visibility: hidden !important;
}

.simple-vc-overlay.show,
.simple-vc-overlay.open,
.simple-vc-overlay.active {
    display: block !important;
    visibility: visible !important;
}

.simple-vc-sidebar {
    display: none !important;
}

.simple-vc-sidebar.open,
.simple-vc-sidebar.show {
    display: block !important;
}

body:not(.visual-customizer-active) .simple-vc-overlay,
body:not(.visual-customizer-active) .simple-vc-sidebar {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
}
";

    // Write emergency CSS fix
    $upload_dir = wp_upload_dir();
    $css_file = $upload_dir['basedir'] . '/emergency-layout-fix.css';
    file_put_contents($css_file, $css_fix);

    // Enqueue the emergency CSS
    add_action('wp_enqueue_scripts', function() use ($upload_dir) {
        wp_enqueue_style(
            'emergency-layout-fix',
            $upload_dir['baseurl'] . '/emergency-layout-fix.css',
            [],
            time()
        );
    }, 999);
}

// Scan for layout issues
function layout_stack_scan_issues() {
    $issues = [];
    $theme_dir = get_template_directory();

    // Check for fixed position elements
    $css_files = [
        '/assets/css/customizer-enhancements.css',
        '/assets/css/simple-visual-customizer.css',
        '/assets/css/sidebar-visual-interfaces.css',
        '/assets/css/components/footer.css'
    ];

    foreach ($css_files as $css_file) {
        $file_path = $theme_dir . $css_file;
        if (file_exists($file_path)) {
            $content = file_get_contents($file_path);
            $fixed_count = substr_count($content, 'position: fixed');

            if ($fixed_count > 0) {
                $issues[] = [
                    'type' => $fixed_count > 5 ? 'error' : 'warning',
                    'title' => "Fixed Position Elements Found",
                    'description' => "Found {$fixed_count} position: fixed declarations in this file",
                    'file' => $css_file
                ];
            }
        }
    }

    // Check for high z-index values
    foreach ($css_files as $css_file) {
        $file_path = $theme_dir . $css_file;
        if (file_exists($file_path)) {
            $content = file_get_contents($file_path);
            if (preg_match('/z-index:\s*9999\d+/', $content)) {
                $issues[] = [
                    'type' => 'error',
                    'title' => "High Z-Index Detected",
                    'description' => "Extremely high z-index values found - may cause stacking issues",
                    'file' => $css_file
                ];
            }
        }
    }

    if (empty($issues)) {
        $issues[] = [
            'type' => 'success',
            'title' => "No Layout Issues Detected",
            'description' => "CSS files appear to be clean of common layout stacking problems",
            'file' => ''
        ];
    }

    return $issues;
}

// Check Visual Customizer status
function layout_stack_check_customizer_status() {
    echo "<p>🔍 Checking Visual Customizer components...</p>";

    $theme_dir = get_template_directory();
    $js_files = [
        '/assets/js/simple-visual-customizer.js',
        '/assets/js/sidebar-visual-interfaces.js'
    ];

    foreach ($js_files as $js_file) {
        $file_path = $theme_dir . $js_file;
        if (file_exists($file_path)) {
            $size = filesize($file_path);
            echo "<p>✅ <strong>" . basename($js_file) . "</strong> - " . number_format($size / 1024, 1) . " KB</p>";
        } else {
            echo "<p>❌ <strong>" . basename($js_file) . "</strong> - File not found</p>";
        }
    }
}

// Check CSS files status
function layout_stack_check_css_files() {
    echo "<p>🔍 Checking CSS files for layout issues...</p>";

    $theme_dir = get_template_directory();
    $css_files = [
        '/assets/css/customizer-enhancements.css',
        '/assets/css/simple-visual-customizer.css',
        '/assets/css/components/footer.css'
    ];

    foreach ($css_files as $css_file) {
        $file_path = $theme_dir . $css_file;
        if (file_exists($css_file)) {
            $content = file_get_contents($file_path);
            $size = filesize($file_path);
            $fixed_count = substr_count($content, 'position: fixed');

            echo "<p>📄 <strong>" . basename($css_file) . "</strong><br>";
            echo "&nbsp;&nbsp;Size: " . number_format($size / 1024, 1) . " KB<br>";
            echo "&nbsp;&nbsp;Fixed Position Elements: {$fixed_count}</p>";
        } else {
            echo "<p>❌ <strong>" . basename($css_file) . "</strong> - File not found</p>";
        }
    }
}
?>
