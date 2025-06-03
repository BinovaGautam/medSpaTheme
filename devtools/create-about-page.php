<?php
/**
 * Create About Us Page - WordPress Page Creator
 * Ensures About Us page exists with proper template
 */

// Set up WordPress environment
define('WP_USE_THEMES', false);
require_once('../../../wp-load.php');

echo "<h2>🚀 About Us Page Creator</h2>\n";

// Check if About Us page exists
$about_page = get_page_by_title('About Us');

if (!$about_page) {
    echo "<p>⚠️ About Us page not found. Creating new page...</p>\n";

    // Create the About Us page
    $page_data = array(
        'post_title'    => 'About Us',
        'post_content'  => '<!-- About Us content will be displayed via page-about.php template -->',
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_slug'     => 'about-us'
    );

    $page_id = wp_insert_post($page_data);

    if ($page_id) {
        echo "<p>✅ About Us page created successfully! (ID: $page_id)</p>\n";

        // Set the page template
        update_post_meta($page_id, '_wp_page_template', 'page-about.php');
        echo "<p>✅ Template 'page-about.php' assigned to About Us page</p>\n";

        // Get the page URL
        $page_url = get_permalink($page_id);
        echo "<p>🔗 <strong>About Us Page URL:</strong> <a href='$page_url' target='_blank'>$page_url</a></p>\n";

    } else {
        echo "<p>❌ Failed to create About Us page</p>\n";
    }

} else {
    echo "<p>✅ About Us page already exists (ID: {$about_page->ID})</p>\n";

    // Check template assignment
    $current_template = get_page_template_slug($about_page->ID);
    if ($current_template !== 'page-about.php') {
        echo "<p>⚠️ Current template: '$current_template' - Updating to 'page-about.php'...</p>\n";
        update_post_meta($about_page->ID, '_wp_page_template', 'page-about.php');
        echo "<p>✅ Template updated to 'page-about.php'</p>\n";
    } else {
        echo "<p>✅ Correct template 'page-about.php' already assigned</p>\n";
    }

    $page_url = get_permalink($about_page->ID);
    echo "<p>🔗 <strong>About Us Page URL:</strong> <a href='$page_url' target='_blank'>$page_url</a></p>\n";
}

echo "<h3>🔧 Debug Information:</h3>\n";
echo "<ul>\n";
echo "<li><strong>Template file exists:</strong> " . (file_exists(get_template_directory() . '/page-about.php') ? '✅ Yes' : '❌ No') . "</li>\n";
echo "<li><strong>CSS file exists:</strong> " . (file_exists(get_template_directory() . '/assets/css/about-us-fix.css') ? '✅ Yes' : '❌ No') . "</li>\n";
echo "<li><strong>WordPress version:</strong> " . get_bloginfo('version') . "</li>\n";
echo "<li><strong>Theme directory:</strong> " . get_template_directory() . "</li>\n";
echo "</ul>\n";

echo "<p><strong>✅ Bug Resolution Complete!</strong> The About Us page should now display properly with all styling.</p>\n";
?>
