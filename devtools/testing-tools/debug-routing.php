<?php
/**
 * WordPress Routing Debug and Fix Script
 * This script checks and fixes common routing issues
 */

// Load WordPress
require_once('./wp-config.php');
require_once('./wp-load.php');

echo "=== WORDPRESS ROUTING DEBUG AND FIX ===\n\n";

// 1. Check permalink structure
echo "1. PERMALINK STRUCTURE:\n";
$permalink_structure = get_option('permalink_structure');
echo "Current: " . ($permalink_structure ?: 'Default (causes routing issues!)') . "\n";

if (empty($permalink_structure)) {
    echo "❌ PROBLEM: Permalink structure is set to default!\n";
    echo "Fixing: Setting to Post name structure...\n";
    update_option('permalink_structure', '/%postname%/');
    echo "✅ Fixed: Permalink structure updated to /%postname%/\n";
} else {
    echo "✅ Good: Permalink structure is properly set\n";
}
echo "\n";

// 2. Check if essential pages exist
echo "2. ESSENTIAL PAGES CHECK:\n";
$essential_pages = ['treatments', 'about', 'contact', 'testimonials'];

foreach ($essential_pages as $page_slug) {
    $page = get_page_by_path($page_slug);
    if ($page) {
        $template = get_post_meta($page->ID, '_wp_page_template', true);
        echo "✅ {$page_slug}: Exists (ID: {$page->ID}, Template: " . ($template ?: 'default') . ")\n";
    } else {
        echo "❌ {$page_slug}: Does not exist\n";
    }
}
echo "\n";

// 3. Create missing pages
echo "3. CREATING MISSING PAGES:\n";
if (function_exists('preetidreams_create_essential_pages')) {
    preetidreams_create_essential_pages();
    echo "✅ Essential pages creation function executed\n";
} else {
    echo "❌ Page creation function not available\n";
}
echo "\n";

// 4. Check rewrite rules
echo "4. REWRITE RULES:\n";
$rewrite_rules = get_option('rewrite_rules');
if ($rewrite_rules && is_array($rewrite_rules)) {
    echo "✅ Rewrite rules exist (" . count($rewrite_rules) . " rules)\n";

    // Check for our specific rules
    $has_treatments = false;
    foreach ($rewrite_rules as $pattern => $rewrite) {
        if (strpos($pattern, 'treatments') !== false) {
            echo "Found rule: {$pattern} => {$rewrite}\n";
            $has_treatments = true;
        }
    }

    if (!$has_treatments) {
        echo "⚠️  No treatments-related rules found\n";
    }
} else {
    echo "❌ No rewrite rules found\n";
}
echo "\n";

// 5. Flush rewrite rules
echo "5. FLUSHING REWRITE RULES:\n";
flush_rewrite_rules(true); // true = hard flush
echo "✅ Rewrite rules flushed (hard flush)\n\n";

// 6. Test page URLs
echo "6. TESTING PAGE URLS:\n";
foreach ($essential_pages as $page_slug) {
    $page = get_page_by_path($page_slug);
    if ($page) {
        $url = get_permalink($page->ID);
        echo "✅ {$page_slug}: {$url}\n";
    } else {
        echo "❌ {$page_slug}: Page not found\n";
    }
}
echo "\n";

// 7. Check current theme
echo "7. THEME INFORMATION:\n";
$theme = wp_get_theme();
echo "Active theme: " . $theme->get('Name') . " v" . $theme->get('Version') . "\n";
echo "Theme directory: " . get_template_directory() . "\n\n";

// 8. Check if theme functions are loaded
echo "8. THEME FUNCTIONS CHECK:\n";
$functions_to_check = [
    'preetidreams_create_essential_pages',
    'preetidreams_custom_post_types',
    'preetidreams_theme_support'
];

foreach ($functions_to_check as $function_name) {
    if (function_exists($function_name)) {
        echo "✅ {$function_name}: Available\n";
    } else {
        echo "❌ {$function_name}: Not available\n";
    }
}
echo "\n";

// 9. Final recommendations
echo "9. RECOMMENDATIONS:\n";
echo "- Visit your WordPress admin: /wp-admin/\n";
echo "- Go to Settings > Permalinks and click 'Save Changes'\n";
echo "- Test navigation links\n";
echo "- If issues persist, check server configuration (Nginx/Apache)\n\n";

echo "=== DEBUG COMPLETE ===\n";
echo "Please test your navigation now.\n";
?>
