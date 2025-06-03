<?php
/**
 * WordPress CLI Script to Create About Us Page
 * Run this through WP-CLI or WordPress admin
 */

// Ensure we're in WordPress environment
if (!function_exists('wp_insert_post')) {
    echo "Error: WordPress not loaded. Run this through WP-CLI or WordPress admin.\n";
    exit;
}

function create_about_us_page_cli() {
    // Check if page exists
    $existing_page = get_page_by_title('About Us');
    if ($existing_page) {
        echo "✅ About Us page already exists (ID: {$existing_page->ID})\n";
        echo "🔗 URL: " . get_permalink($existing_page->ID) . "\n";
        return $existing_page->ID;
    }

    // Create the page
    $page_data = array(
        'post_title'    => 'About Us',
        'post_name'     => 'about-us',
        'post_content'  => '<!-- About Us content will be handled by page-about.php template -->',
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_author'   => 1,
        'meta_input'    => array(
            '_wp_page_template' => 'page-about.php'
        )
    );

    $page_id = wp_insert_post($page_data);

    if (is_wp_error($page_id)) {
        echo "❌ Error creating page: " . $page_id->get_error_message() . "\n";
        return false;
    }

    echo "✅ About Us page created successfully (ID: {$page_id})\n";
    echo "🔗 URL: " . get_permalink($page_id) . "\n";

    return $page_id;
}

// Run the function
$result = create_about_us_page_cli();

if ($result) {
    echo "\n🎉 About Us page setup complete!\n";
    echo "📋 Next steps:\n";
    echo "1. Visit the page to verify styling\n";
    echo "2. Test responsive design\n";
    echo "3. Verify all CSS classes are working\n";
} else {
    echo "\n❌ Failed to create About Us page\n";
}
?>
