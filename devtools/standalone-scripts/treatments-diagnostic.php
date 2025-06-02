<?php
/**
 * Treatments Page Diagnostic Tool
 * DevKinsta WordPress Debugging Script (CLI + Browser)
 * Created by BUG-RESOLVER-001 Agent
 */

// Detect if running via browser or CLI
$is_browser = isset($_SERVER['HTTP_HOST']);
$is_cli = php_sapi_name() === 'cli';

// Security check for browser access
if ($is_browser) {
    // Basic security - check for local environment
    $allowed_hosts = ['localhost', '127.0.0.1', '.local'];
    $is_allowed = false;
    foreach ($allowed_hosts as $host) {
        if (strpos($_SERVER['HTTP_HOST'], $host) !== false) {
            $is_allowed = true;
            break;
        }
    }

    if (!$is_allowed) {
        http_response_code(403);
        die('Access denied. This tool is only available in local development environments.');
    }

    // Set content type for browser
    header('Content-Type: text/html; charset=utf-8');

    // Start HTML output
    echo "<!DOCTYPE html>\n";
    echo "<html lang='en'>\n";
    echo "<head>\n";
    echo "    <meta charset='utf-8'>\n";
    echo "    <meta name='viewport' content='width=device-width, initial-scale=1'>\n";
    echo "    <title>Treatments Diagnostic Tool</title>\n";
    echo "    <style>\n";
    echo "        body { font-family: 'Courier New', monospace; background: #f1f1f1; margin: 0; padding: 20px; }\n";
    echo "        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }\n";
    echo "        .header { background: #0073aa; color: white; padding: 20px; margin: -30px -30px 30px -30px; border-radius: 8px 8px 0 0; }\n";
    echo "        .diagnostic-output { background: #f8f9fa; border: 1px solid #ddd; padding: 20px; margin: 20px 0; border-radius: 4px; white-space: pre-wrap; font-family: 'Courier New', monospace; }\n";
    echo "        .status-success { color: #155724; background: #d4edda; border-color: #c3e6cb; }\n";
    echo "        .status-warning { color: #856404; background: #fff3cd; border-color: #ffeaa7; }\n";
    echo "        .status-error { color: #721c24; background: #f8d7da; border-color: #f5c6cb; }\n";
    echo "        .actions { margin: 20px 0; }\n";
    echo "        .button { display: inline-block; padding: 10px 20px; background: #0073aa; color: white; text-decoration: none; border-radius: 4px; margin-right: 10px; }\n";
    echo "        .button:hover { background: #005a87; }\n";
    echo "        .button-secondary { background: #666; }\n";
    echo "        .button-secondary:hover { background: #555; }\n";
    echo "    </style>\n";
    echo "</head>\n";
    echo "<body>\n";
    echo "    <div class='container'>\n";
    echo "        <div class='header'>\n";
    echo "            <h1>🔍 Treatments Diagnostic Tool</h1>\n";
    echo "            <p>DevKinsta WordPress Debugging Script - Browser Version</p>\n";
    echo "        </div>\n";
    echo "        <div class='diagnostic-output'>\n";
}

echo "=== TREATMENTS PAGE DIAGNOSTIC TOOL ===\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n";
echo "Mode: " . ($is_browser ? 'Browser' : 'CLI') . "\n\n";

// Load WordPress (try multiple paths)
$wp_config_paths = [
    '../../../wp-config.php',
    '../../../wp-load.php',
    '../../../../wp-config.php',
    '../../../../wp-load.php'
];

$wp_loaded = false;
foreach ($wp_config_paths as $path) {
    if (file_exists($path)) {
        try {
            require_once($path);
            $wp_loaded = true;
            echo "✅ WordPress loaded successfully from: {$path}\n";
            break;
        } catch (Exception $e) {
            echo "⚠️  Failed to load WordPress from {$path}: " . $e->getMessage() . "\n";
            continue;
        }
    }
}

if (!$wp_loaded) {
    echo "❌ CRITICAL: Could not load WordPress\n";
    echo "   - DevKinsta may not be running\n";
    echo "   - Database connection may be down\n";
    echo "   - WordPress installation may be corrupted\n\n";

    echo "🔧 SOLUTION STEPS:\n";
    echo "1. Start DevKinsta application\n";
    echo "2. Ensure MySQL service is running\n";
    echo "3. Check WordPress installation integrity\n";
    echo "4. Verify database credentials in wp-config.php\n";

    if ($is_browser) {
        echo "        </div>\n";
        echo "        <div class='actions'>\n";
        echo "            <a href='javascript:window.location.reload()' class='button'>🔄 Refresh</a>\n";
        echo "            <a href='../wp-admin-tools/treatments-diagnostic-admin.php' class='button button-secondary'>🔧 Try Admin Tool</a>\n";
        echo "        </div>\n";
        echo "    </div>\n";
        echo "</body></html>\n";
    }
    exit(1);
}

echo "\n🔍 1. WORDPRESS ENVIRONMENT CHECK\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "• WordPress Version: " . get_bloginfo('version') . "\n";
echo "• Site URL: " . get_site_url() . "\n";
echo "• Admin URL: " . admin_url() . "\n";
echo "• Theme: " . get_template() . "\n";
echo "• Active Theme: " . (get_template_directory() ? '✅ Found' : '❌ Missing') . "\n";

echo "\n🔍 2. DATABASE CONNECTION CHECK\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
global $wpdb;
try {
    $db_check = $wpdb->get_var("SELECT 1");
    if ($db_check === '1') {
        echo "✅ Database connection: Working\n";
    } else {
        echo "❌ Database connection: Failed\n";
    }
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n🔍 3. TREATMENTS PAGE CHECK\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

// Check if treatments page exists
$treatments_page = get_page_by_path('treatments');
if ($treatments_page) {
    echo "✅ Treatments page exists\n";
    echo "  • Page ID: {$treatments_page->ID}\n";
    echo "  • Title: {$treatments_page->post_title}\n";
    echo "  • Status: {$treatments_page->post_status}\n";
    echo "  • URL: " . get_permalink($treatments_page->ID) . "\n";

    // Check template
    $template = get_post_meta($treatments_page->ID, '_wp_page_template', true);
    echo "  • Template: " . ($template ?: 'default') . "\n";

    if ($template !== 'page-treatments.php') {
        echo "  ⚠️  Template should be 'page-treatments.php'\n";
    }

    // Check page content
    $content = get_the_content(null, false, $treatments_page);
    echo "  • Content length: " . strlen($content) . " characters\n";
    if (strlen($content) == 0) {
        echo "  ⚠️  Page has no content\n";
    }
} else {
    echo "❌ Treatments page does not exist\n";
}

echo "\n🔍 4. TREATMENT POST TYPE CHECK\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

// Check if treatment post type is registered
$treatment_post_type = get_post_type_object('treatment');
if ($treatment_post_type) {
    echo "✅ Treatment post type is registered\n";
    echo "  • Label: {$treatment_post_type->label}\n";
    echo "  • Public: " . ($treatment_post_type->public ? 'Yes' : 'No') . "\n";
    echo "  • Show in UI: " . ($treatment_post_type->show_ui ? 'Yes' : 'No') . "\n";
    echo "  • Hierarchical: " . ($treatment_post_type->hierarchical ? 'Yes' : 'No') . "\n";
} else {
    echo "❌ Treatment post type is NOT registered\n";
    echo "  • Check functions.php for register_post_type('treatment')\n";
}

echo "\n🔍 5. TREATMENTS QUERY TEST\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

// Test the exact query used in treatments-content.php
$treatments_query = new WP_Query([
    'post_type' => 'treatment',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'post_status' => 'publish'
]);

echo "• Query parameters:\n";
echo "  - Post type: treatment\n";
echo "  - Posts per page: -1 (all)\n";
echo "  - Order by: menu_order\n";
echo "  - Order: ASC\n";
echo "  - Status: publish\n\n";

echo "• Query results:\n";
echo "  - Found posts: {$treatments_query->found_posts}\n";
echo "  - Post count: {$treatments_query->post_count}\n";
echo "  - Have posts: " . ($treatments_query->have_posts() ? 'Yes' : 'No') . "\n";

if ($treatments_query->have_posts()) {
    echo "\n✅ TREATMENTS FOUND:\n";
    $count = 1;
    while ($treatments_query->have_posts()) {
        $treatments_query->the_post();
        echo "  {$count}. " . get_the_title() . " (ID: " . get_the_ID() . ")\n";
        echo "     Status: " . get_post_status() . "\n";
        echo "     Date: " . get_the_date() . "\n";
        echo "     Excerpt: " . wp_trim_words(get_the_excerpt(), 10) . "\n\n";
        $count++;
    }
    wp_reset_postdata();
} else {
    echo "\n❌ NO TREATMENTS FOUND\n";
    echo "   Possible causes:\n";
    echo "   • No treatment posts have been created\n";
    echo "   • Treatment posts are in draft status\n";
    echo "   • Treatment post type registration issue\n";
    echo "   • Database query issue\n";
}

echo "\n🔍 6. ALL POSTS CHECK\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

// Check for any posts with 'treatment' in the title or content
$all_posts_query = new WP_Query([
    's' => 'treatment',
    'post_type' => 'any',
    'posts_per_page' => 10,
    'post_status' => 'any'
]);

echo "• Searching all posts for 'treatment':\n";
echo "  - Found posts: {$all_posts_query->found_posts}\n";

if ($all_posts_query->have_posts()) {
    while ($all_posts_query->have_posts()) {
        $all_posts_query->the_post();
        echo "  - " . get_the_title() . " (Type: " . get_post_type() . ", Status: " . get_post_status() . ")\n";
    }
    wp_reset_postdata();
}

echo "\n🔍 7. CUSTOM POST TYPES CHECK\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$post_types = get_post_types(['_builtin' => false], 'objects');
echo "• Registered custom post types:\n";
foreach ($post_types as $post_type) {
    echo "  - {$post_type->name}: {$post_type->label}\n";
}

echo "\n🔍 8. TEMPLATE FILES CHECK\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$template_files = [
    'page-treatments.php',
    'treatments-content.php',
    'single-treatment.php',
    'archive-treatment.php'
];

foreach ($template_files as $file) {
    $path = get_template_directory() . '/' . $file;
    if (file_exists($path)) {
        $size = filesize($path);
        echo "✅ {$file}: Found ({$size} bytes)\n";
    } else {
        echo "❌ {$file}: Missing\n";
    }
}

echo "\n🔍 9. SOLUTION RECOMMENDATIONS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$has_issues = false;
$recommendations = [];

if (!$treatments_query->have_posts()) {
    $has_issues = true;
    echo "🎯 PRIMARY ISSUE: No treatment posts found\n\n";

    echo "💡 IMMEDIATE SOLUTIONS:\n";
    if ($is_browser) {
        echo "1. Use the Fix Utility (recommended)\n";
        echo "2. Create treatments manually in WordPress admin\n";
        echo "3. Run the standalone fix script\n\n";
    } else {
        echo "1. CREATE SAMPLE TREATMENTS:\n";
        echo "   php " . __DIR__ . "/../dev-utilities/fix-treatments-page.php\n\n";

        echo "2. MANUAL CREATION:\n";
        echo "   • Go to: " . admin_url('post-new.php?post_type=treatment') . "\n";
        echo "   • Create 5-10 treatment posts\n";
        echo "   • Set status to 'Published'\n\n";
    }
}

if (!$has_issues) {
    echo "✅ NO MAJOR ISSUES DETECTED\n";
    echo "Your treatments page appears to be configured correctly.\n";
}

echo "\n🔧 NEXT STEPS:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

if ($is_browser) {
    echo "Browser Mode - Available Actions:\n";
    echo "1. Use the Fix Utility for automatic fixes\n";
    echo "2. Access WordPress admin for manual changes\n";
    echo "3. View the treatments page to test\n\n";
} else {
    echo "CLI Mode - Available Commands:\n";
    echo "1. If no treatments exist, create them:\n";
    echo "   php " . __DIR__ . "/../dev-utilities/fix-treatments-page.php\n\n";
    echo "2. If treatments exist but don't show:\n";
    echo "   • Check query parameters\n";
    echo "   • Verify post status = 'publish'\n";
    echo "   • Clear any caching\n\n";
    echo "3. Test the page again:\n";
    echo "   " . get_site_url() . "/treatments/\n\n";
}

echo "📊 DIAGNOSTIC COMPLETE\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "For additional help, check:\n";
echo "• WordPress admin: " . admin_url() . "\n";
echo "• Debug log: " . (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG ? 'Enabled' : 'Disabled') . "\n";
echo "• Error log location: " . ini_get('error_log') . "\n";

// Browser-specific output
if ($is_browser) {
    echo "        </div>\n";
    echo "        <div class='actions'>\n";
    echo "            <a href='" . get_site_url() . "/treatments/' class='button' target='_blank'>🌐 View Treatments Page</a>\n";
    echo "            <a href='" . admin_url() . "' class='button button-secondary' target='_blank'>⚙️ WordPress Admin</a>\n";
    echo "            <a href='javascript:window.location.reload()' class='button button-secondary'>🔄 Refresh Diagnostic</a>\n";
    if (file_exists('../wp-admin-tools/load-admin-tools.php')) {
        echo "            <a href='" . admin_url('tools.php?page=treatments-fix-utility') . "' class='button' target='_blank'>🔧 Fix Utility</a>\n";
    }
    echo "        </div>\n";
    echo "        <div style='margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 12px;'>\n";
    echo "            <p><strong>Access Methods:</strong></p>\n";
    echo "            <p>• Browser: " . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "</p>\n";
    echo "            <p>• Command Line: php " . __FILE__ . "</p>\n";
    echo "            <p>• WordPress Admin: Install devtools/wp-admin-tools/load-admin-tools.php</p>\n";
    echo "        </div>\n";
    echo "    </div>\n";
    echo "</body></html>\n";
}

?>
