<?php
/**
 * Template Name: About Us
 * Description: About Us page for PreetiDreams Medical Spa
 */

get_header(); ?>

<main id="main" class="site-main about-page" role="main">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
