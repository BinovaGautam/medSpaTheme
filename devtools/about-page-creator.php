<?php
/**
 * About Us Page Creator - DevKinsta Debug Tool
 * Creates About Us page and assigns page-about.php template
 */

// WordPress environment check
if (!defined('ABSPATH')) {
    // Load WordPress
    require_once('../../../wp-load.php');
}

// Function to create About Us page
function create_about_us_page() {
    echo "<h2>About Us Page Creator</h2>\n";

    // Check if page already exists
    $existing_page = get_page_by_title('About Us');
    if ($existing_page) {
        echo "<p>✅ About Us page already exists (ID: {$existing_page->ID})</p>\n";
        echo "<p>📄 Current template: " . get_page_template_slug($existing_page->ID) . "</p>\n";
        echo "<p>🔗 URL: <a href='" . get_permalink($existing_page->ID) . "'>" . get_permalink($existing_page->ID) . "</a></p>\n";
        return $existing_page->ID;
    }

    // Create new page
    $page_data = array(
        'post_title'    => 'About Us',
        'post_name'     => 'about-us',
        'post_content'  => 'About Dr. Preeti Sharma and PreetiDreams Medical Spa',
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_author'   => 1,
        'meta_input'    => array(
            '_wp_page_template' => 'page-about.php'
        )
    );

    $page_id = wp_insert_post($page_data);

    if (is_wp_error($page_id)) {
        echo "<p>❌ Error creating page: " . $page_id->get_error_message() . "</p>\n";
        return false;
    }

    echo "<p>✅ About Us page created successfully (ID: {$page_id})</p>\n";
    echo "<p>🔗 URL: <a href='" . get_permalink($page_id) . "'>" . get_permalink($page_id) . "</a></p>\n";

    return $page_id;
}

// Function to verify About Us CSS
function check_about_us_css() {
    echo "<h2>About Us CSS Analysis</h2>\n";

    $theme_dir = get_template_directory();
    $css_file = $theme_dir . '/assets/css/medical-spa-theme.css';

    if (!file_exists($css_file)) {
        echo "<p>❌ CSS file not found: {$css_file}</p>\n";
        return;
    }

    $css_content = file_get_contents($css_file);

    // Check for About Us specific classes
    $about_classes = array(
        'dr-preeti-hero',
        'practice-story',
        'signature-treatments',
        'expert-team',
        'arizona-locations',
        'success-metrics'
    );

    echo "<h3>CSS Class Analysis:</h3>\n";
    $missing_classes = array();

    foreach ($about_classes as $class) {
        if (strpos($css_content, ".{$class}") !== false) {
            echo "<p>✅ .{$class} - Found in CSS</p>\n";
        } else {
            echo "<p>❌ .{$class} - Missing from CSS</p>\n";
            $missing_classes[] = $class;
        }
    }

    if (!empty($missing_classes)) {
        echo "<h3>Missing CSS Classes:</h3>\n";
        echo "<p>The following classes need to be added to the CSS:</p>\n";
        echo "<ul>\n";
        foreach ($missing_classes as $class) {
            echo "<li>.{$class}</li>\n";
        }
        echo "</ul>\n";
    }

    return $missing_classes;
}

// Function to test page accessibility
function test_page_accessibility() {
    echo "<h2>Page Accessibility Test</h2>\n";

    $about_page = get_page_by_title('About Us');
    if (!$about_page) {
        echo "<p>❌ About Us page not found</p>\n";
        return;
    }

    $url = get_permalink($about_page->ID);
    echo "<p>Testing URL: {$url}</p>\n";

    // Simple accessibility test
    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        echo "<p>❌ Error accessing page: " . $response->get_error_message() . "</p>\n";
    } else {
        $status_code = wp_remote_retrieve_response_code($response);
        echo "<p>✅ Page accessible with status code: {$status_code}</p>\n";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>About Us Page Creator - DevKinsta Debug Tool</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #1B365D; border-bottom: 2px solid #D4AF37; padding-bottom: 5px; }
        h3 { color: #87A96B; }
        p { margin: 10px 0; }
        .status-ok { color: green; }
        .status-error { color: red; }
        ul { margin: 10px 0; }
        li { margin: 5px 0; }
    </style>
</head>
<body>
    <h1>🏥 About Us Page Creator - DevKinsta Debug Tool</h1>

    <?php
    // Execute checks
    $page_id = create_about_us_page();
    echo "<hr>\n";

    $missing_css = check_about_us_css();
    echo "<hr>\n";

    test_page_accessibility();
    echo "<hr>\n";

    // Summary
    echo "<h2>Summary & Next Steps</h2>\n";
    if ($page_id) {
        echo "<p>✅ About Us page is ready</p>\n";
    }

    if (!empty($missing_css)) {
        echo "<p>⚠️ Missing CSS classes need to be implemented for proper styling</p>\n";
        echo "<p>📋 <strong>Action Required:</strong> Add CSS styles for About Us page sections</p>\n";
    } else {
        echo "<p>✅ All CSS classes appear to be present</p>\n";
    }
    ?>

    <h3>Quick Links:</h3>
    <?php if ($page_id): ?>
        <p><a href="<?php echo get_permalink($page_id); ?>">🔗 View About Us Page</a></p>
    <?php endif; ?>
    <p><a href="<?php echo admin_url(); ?>">🔧 WordPress Admin</a></p>
    <p><a href="<?php echo home_url(); ?>">🏠 Home Page</a></p>

</body>
</html>
