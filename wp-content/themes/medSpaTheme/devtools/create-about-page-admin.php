<?php
/**
 * About Us Page Creator - Admin Script
 * Access this file directly through the browser at:
 * http://medspaa.local/wp-content/themes/medSpaTheme/devtools/create-about-page-admin.php
 */

// Load WordPress
$wp_load_path = dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php';
if (file_exists($wp_load_path)) {
    require_once($wp_load_path);
} else {
    die('WordPress not found. Please ensure this file is in the correct location.');
}

// Security check - ensure user has admin privileges (basic check)
if (!current_user_can('manage_options')) {
    // If not logged in as admin, show login link
    echo '<p>Please <a href="' . wp_login_url(site_url($_SERVER['REQUEST_URI'])) . '">login as administrator</a> to create the About Us page.</p>';
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>About Us Page Creator</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f1f1f1; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #1B365D; }
        .success { color: green; padding: 10px; background: #f0f8f0; border-left: 4px solid green; margin: 20px 0; }
        .error { color: red; padding: 10px; background: #fef8f8; border-left: 4px solid red; margin: 20px 0; }
        .info { color: #666; padding: 10px; background: #f8f9fa; border-left: 4px solid #87A96B; margin: 20px 0; }
        button { background: #1B365D; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #87A96B; }
        .status-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0; }
        .status-card { padding: 15px; border-radius: 8px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏥 About Us Page Creator</h1>

        <?php
        // Handle page creation
        if (isset($_POST['create_page'])) {
            // Check if page already exists
            $existing_page = get_page_by_title('About Us');

            if ($existing_page) {
                echo '<div class="info">✅ About Us page already exists (ID: ' . $existing_page->ID . ')<br>';
                echo '🔗 <a href="' . get_permalink($existing_page->ID) . '" target="_blank">View Page</a></div>';
            } else {
                // Create the page
                $page_data = array(
                    'post_title'    => 'About Us',
                    'post_name'     => 'about-us',
                    'post_content'  => '<!-- About Us content managed by page-about.php template -->',
                    'post_status'   => 'publish',
                    'post_type'     => 'page',
                    'post_author'   => get_current_user_id(),
                    'meta_input'    => array(
                        '_wp_page_template' => 'page-about.php'
                    )
                );

                $page_id = wp_insert_post($page_data);

                if (is_wp_error($page_id)) {
                    echo '<div class="error">❌ Error creating page: ' . $page_id->get_error_message() . '</div>';
                } else {
                    echo '<div class="success">✅ About Us page created successfully!<br>';
                    echo 'Page ID: ' . $page_id . '<br>';
                    echo '🔗 <a href="' . get_permalink($page_id) . '" target="_blank">View Page</a><br>';
                    echo '🔧 <a href="' . admin_url('post.php?post=' . $page_id . '&action=edit') . '" target="_blank">Edit Page</a></div>';
                }
            }
        }

        // Status check
        $about_page = get_page_by_title('About Us');
        $css_file_exists = file_exists(get_template_directory() . '/assets/css/about-us-fix.css');
        $template_exists = file_exists(get_template_directory() . '/page-about.php');
        ?>

        <div class="status-grid">
            <div class="status-card">
                <h3>📄 Page Status</h3>
                <?php if ($about_page): ?>
                    <p>✅ About Us page exists</p>
                    <p><strong>ID:</strong> <?php echo $about_page->ID; ?></p>
                    <p><strong>Template:</strong> <?php echo get_page_template_slug($about_page->ID) ?: 'default'; ?></p>
                    <p><a href="<?php echo get_permalink($about_page->ID); ?>" target="_blank">🔗 View Page</a></p>
                <?php else: ?>
                    <p>❌ About Us page does not exist</p>
                    <form method="post" style="margin-top: 15px;">
                        <button type="submit" name="create_page">Create About Us Page</button>
                    </form>
                <?php endif; ?>
            </div>

            <div class="status-card">
                <h3>🎨 Assets Status</h3>
                <p><?php echo $css_file_exists ? '✅' : '❌'; ?> CSS File (about-us-fix.css)</p>
                <p><?php echo $template_exists ? '✅' : '❌'; ?> Template File (page-about.php)</p>
                <p><?php echo function_exists('medspa_theme_styles') ? '✅' : '❌'; ?> CSS Enqueue Function</p>
            </div>
        </div>

        <?php if ($about_page && $css_file_exists && $template_exists): ?>
            <div class="success">
                <h3>🎉 Setup Complete!</h3>
                <p>All components are in place. The About Us page should be working correctly.</p>
                <p><strong>Next Steps:</strong></p>
                <ol>
                    <li><a href="<?php echo get_permalink($about_page->ID); ?>" target="_blank">Test the About Us page</a></li>
                    <li>Verify responsive design on mobile/tablet</li>
                    <li>Check all CSS styling is applied</li>
                    <li>Test hover effects and animations</li>
                </ol>
            </div>
        <?php endif; ?>

        <div class="info">
            <h3>📋 Technical Details</h3>
            <p><strong>WordPress Version:</strong> <?php echo get_bloginfo('version'); ?></p>
            <p><strong>Theme Directory:</strong> <?php echo get_template_directory(); ?></p>
            <p><strong>Site URL:</strong> <?php echo home_url(); ?></p>
            <p><strong>Current User:</strong> <?php echo wp_get_current_user()->display_name; ?></p>
        </div>
    </div>
</body>
</html>
