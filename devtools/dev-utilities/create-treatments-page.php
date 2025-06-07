<?php
/**
 * Create Treatments Page - Standalone Script
 * This script can be run to create the treatments page if it doesn't exist
 */

// Load WordPress (adjust path if needed)
$wp_config_paths = [
    '../../../wp-config.php',
    '../../../wp-load.php',
    '../../wp-config.php',
    '../../wp-load.php'
];

$wp_loaded = false;
foreach ($wp_config_paths as $path) {
    if (file_exists($path)) {
        try {
            require_once($path);
            $wp_loaded = true;
            echo "✅ WordPress loaded successfully\n";
            break;
        } catch (Exception $e) {
            echo "⚠️  Failed to load WordPress from {$path}: " . $e->getMessage() . "\n";
            continue;
        }
    }
}

if (!$wp_loaded) {
    echo "❌ Could not load WordPress. Make sure:\n";
    echo "   - DevKinsta is running\n";
    echo "   - WordPress is installed in the parent directories\n";
    echo "   - Database connection is working\n\n";

    echo "🔧 MANUAL SOLUTION:\n";
    echo "1. Start DevKinsta\n";
    echo "2. Access WordPress admin: /wp-admin/\n";
    echo "3. Go to Pages > Add New\n";
    echo "4. Create a page titled 'Treatments' with slug 'treatments'\n";
    echo "5. Set the page template to 'Treatments' in Page Attributes\n";
    echo "6. Publish the page\n\n";

    echo "🔧 ALTERNATIVE - ADMIN PANEL TOOL:\n";
    echo "1. Access: /wp-admin/themes.php?page=preetidreams-fix-routing\n";
    echo "2. Click 'Fix All Routing Issues' button\n";
    echo "3. This will create all missing pages automatically\n\n";

    exit;
}

echo "\n=== TREATMENTS PAGE CREATION SCRIPT ===\n\n";

// Check if treatments page exists
echo "1. CHECKING EXISTING PAGE:\n";
$treatments_page = get_page_by_path('treatments');

if ($treatments_page) {
    echo "✅ Treatments page already exists!\n";
    echo "   - ID: {$treatments_page->ID}\n";
    echo "   - Title: {$treatments_page->post_title}\n";
    echo "   - Status: {$treatments_page->post_status}\n";
    echo "   - URL: " . get_permalink($treatments_page->ID) . "\n";

    // Check template
    $template = get_post_meta($treatments_page->ID, '_wp_page_template', true);
    echo "   - Template: " . ($template ?: 'default') . "\n";

    if ($template !== 'page-treatments.php') {
        echo "🔧 Fixing template assignment...\n";
        update_post_meta($treatments_page->ID, '_wp_page_template', 'page-treatments.php');
        echo "✅ Template updated to page-treatments.php\n";
    }

} else {
    echo "❌ Treatments page does not exist. Creating...\n";

    // Create the treatments page
    $page_data = array(
        'post_title' => 'Treatments',
        'post_name' => 'treatments',
        'post_content' => '<p>Discover our comprehensive range of premium medical spa treatments designed to enhance your natural beauty and wellness.</p>',
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_author' => 1,
        'comment_status' => 'closed',
        'ping_status' => 'closed',
        'menu_order' => 2
    );

    $page_id = wp_insert_post($page_data);

    if ($page_id && !is_wp_error($page_id)) {
        echo "✅ Treatments page created successfully!\n";
        echo "   - ID: {$page_id}\n";
        echo "   - URL: " . get_permalink($page_id) . "\n";

        // Set page template
        update_post_meta($page_id, '_wp_page_template', 'page-treatments.php');
        echo "✅ Page template set to page-treatments.php\n";

        // Set SEO meta description
        update_post_meta($page_id, '_yoast_wpseo_metadesc', 'Explore PreetiDreams Medical Spa\'s comprehensive range of premium treatments including facial rejuvenation, body contouring, laser treatments, and wellness services.');
        echo "✅ SEO meta description added\n";

        $treatments_page = get_post($page_id);
    } else {
        echo "❌ Failed to create treatments page\n";
        if (is_wp_error($page_id)) {
            echo "   Error: " . $page_id->get_error_message() . "\n";
        }
        exit;
    }
}

echo "\n2. CHECKING TEMPLATE FILES:\n";
$template_files = [
    'page-treatments.php' => get_template_directory() . '/page-treatments.php',
    'treatments-content.php' => get_template_directory() . '/treatments-content.php'
];

foreach ($template_files as $name => $path) {
    if (file_exists($path)) {
        echo "✅ {$name}: Exists\n";
    } else {
        echo "❌ {$name}: Missing\n";
    }
}

echo "\n3. CHECKING PERMALINK STRUCTURE:\n";
$permalink_structure = get_option('permalink_structure');
if (empty($permalink_structure)) {
    echo "❌ Permalink structure is set to default (this causes issues)\n";
    echo "🔧 Fixing permalink structure...\n";
    update_option('permalink_structure', '/%postname%/');
    echo "✅ Permalink structure updated to /%postname%/\n";
} else {
    echo "✅ Permalink structure: {$permalink_structure}\n";
}

echo "\n4. FLUSHING REWRITE RULES:\n";
flush_rewrite_rules(true);
echo "✅ Rewrite rules flushed\n";

echo "\n5. TESTING PAGE ACCESS:\n";
if ($treatments_page) {
    $page_url = get_permalink($treatments_page->ID);
    echo "✅ Page URL: {$page_url}\n";
    echo "🌐 Test in browser: {$page_url}\n";
}

echo "\n6. NAVIGATION MENU CHECK:\n";
$nav_menus = wp_get_nav_menus();
if (empty($nav_menus)) {
    echo "⚠️  No navigation menus found\n";
    echo "   You may need to create a menu in Appearance > Menus\n";
} else {
    echo "✅ Navigation menus exist (" . count($nav_menus) . " menus)\n";
}

echo "\n🎉 SETUP COMPLETE!\n";
echo "\nNext steps:\n";
echo "1. Visit your site and test the treatments page\n";
echo "2. If navigation doesn't work, go to Settings > Permalinks and click Save\n";
echo "3. If content doesn't show, check that your theme is active\n";
echo "4. For advanced debugging, use the admin tool at /wp-admin/themes.php?page=preetidreams-fix-routing\n";

echo "\n=== SCRIPT COMPLETE ===\n";
?>
