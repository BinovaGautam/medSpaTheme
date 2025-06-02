<?php
/**
 * Template Name: Treatments
 * Description: Treatments page for PreetiDreams Medical Spa
 */

get_header(); ?>

<main id="main" class="site-main treatments-page" role="main">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="entry-content">
                    <?php
                    // Debug: Check what's happening
                    $debug_mode = true; // Set to false to disable debug output

                    if ($debug_mode) {
                        echo '<!-- DEBUG: Template starting -->' . "\n";
                        echo '<!-- ACF Active: ' . (function_exists('get_field') ? 'YES' : 'NO') . ' -->' . "\n";
                    }

                    // Check if we should include the custom treatments layout
                    $custom_treatments_content = false;

                    // Only check ACF field if ACF is active
                    if (function_exists('get_field')) {
                        $custom_treatments_content = get_field('use_custom_treatments_layout');
                        if ($debug_mode) {
                            echo '<!-- ACF Field Value: ' . ($custom_treatments_content ? 'TRUE' : 'FALSE') . ' -->' . "\n";
                        }
                    }

                    // Check if the WordPress-compatible treatments content file exists
                    $treatments_content_path = get_template_directory() . '/treatments-content.php';
                    $file_exists = file_exists($treatments_content_path);

                    if ($debug_mode) {
                        echo '<!-- Treatments Content File: ' . ($file_exists ? 'EXISTS' : 'MISSING') . ' -->' . "\n";
                        echo '<!-- Path: ' . $treatments_content_path . ' -->' . "\n";
                    }

                    // Always try to include treatments content first (since we know it exists)
                    if ($file_exists) {
                        if ($debug_mode) {
                            echo '<!-- Including treatments-content.php -->' . "\n";
                        }

                        // Turn on error reporting for debugging
                        $error_reporting_before = error_reporting();
                        error_reporting(E_ALL);

                        ob_start();
                        $include_result = include $treatments_content_path;
                        $include_output = ob_get_contents();
                        ob_end_clean();

                        // Restore error reporting
                        error_reporting($error_reporting_before);

                        if ($debug_mode) {
                            echo '<!-- Include result: ' . ($include_result ? 'SUCCESS' : 'FAILED') . ' -->' . "\n";
                            echo '<!-- Output length: ' . strlen($include_output) . ' chars -->' . "\n";
                        }

                        // Output the included content
                        echo $include_output;

                        // If include failed or no output, show fallback
                        if (!$include_result || empty(trim($include_output))) {
                            if ($debug_mode) {
                                echo '<!-- Include failed, showing fallback -->' . "\n";
                            }
                            echo '<div class="treatments-fallback">';
                            echo '<h2>Treatments Loading Issue</h2>';
                            echo '<p>There seems to be an issue loading the treatments content. Please try refreshing the page.</p>';
                            echo '<p><strong>Debug Info:</strong> Include result: ' . ($include_result ? 'true' : 'false') . ', Output: ' . strlen($include_output) . ' characters</p>';
                            echo '</div>';
                        }
                    } else {
                        // Show regular page content
                        if ($debug_mode) {
                            echo '<!-- Showing regular page content -->' . "\n";
                        }
                        the_content();

                        // If no content, show a helpful message
                        if (empty(get_the_content())) {
                            if ($debug_mode) {
                                echo '<!-- Page content is empty, showing placeholder -->' . "\n";
                            }
                            echo '<div class="treatments-placeholder">';
                            echo '<h2>Treatments Coming Soon</h2>';
                            echo '<p>Our comprehensive treatment list is being prepared. Please check back soon or contact us for more information about our available services.</p>';
                            echo '</div>';
                        }
                    }

                    if ($debug_mode) {
                        echo '<!-- DEBUG: Template ending -->' . "\n";
                    }
                    ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
