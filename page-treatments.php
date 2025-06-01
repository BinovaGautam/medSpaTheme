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
                    if ($custom_treatments_content) {
                        // Include the treatments.html content
                        include get_template_directory() . '/treatments.html';
                    } else {
                        // Show regular page content
                        the_content();
                    }
                    ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
