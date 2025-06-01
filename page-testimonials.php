<?php
/**
 * Template Name: Testimonials
 * Description: Testimonials page for PreetiDreams Medical Spa
 */

get_header(); ?>

<main id="main" class="site-main testimonials-page" role="main">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="entry-content">
                    <?php the_content(); ?>

                    <!-- Display testimonials if needed -->
                    <?php
                    $testimonials = new WP_Query([
                        'post_type' => 'testimonial',
                        'posts_per_page' => -1,
                        'post_status' => 'publish'
                    ]);

                    if ($testimonials->have_posts()) : ?>
                        <section class="testimonials-grid">
                            <h2>What Our Clients Say</h2>
                            <div class="testimonials-container">
                                <?php while ($testimonials->have_posts()) : $testimonials->the_post(); ?>
                                    <div class="testimonial-item">
                                        <div class="testimonial-content">
                                            <?php the_content(); ?>
                                        </div>
                                        <div class="testimonial-author">
                                            <h3><?php the_title(); ?></h3>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </section>
                    <?php endif; wp_reset_postdata(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
