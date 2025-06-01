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
                    // Check if we should include the HTML treatments page
                    $custom_treatments_content = get_field('use_custom_treatments_layout');

                    // Default to showing treatments.html if no custom field is set or if treatments.html exists
                    $treatments_html_path = get_template_directory() . '/treatments.html';

                    if ($custom_treatments_content || (!$custom_treatments_content && file_exists($treatments_html_path))) {
                        // Include the treatments.html content
                        include $treatments_html_path;
                    } else {
                        // Show regular page content
                        the_content();

                        // If no content, show a helpful message
                        if (empty(get_the_content())) {
                            echo '<div class="treatments-placeholder">';
                            echo '<h2>Treatments Coming Soon</h2>';
                            echo '<p>Our comprehensive treatment list is being prepared. Please check back soon or contact us for more information about our available services.</p>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
