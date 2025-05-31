<?php get_header(); ?>

<main id="main" class="site-main">
    <div class="container">

        <!-- Page Header -->
        <header class="page-header treatment-archive-header">
            <h1 class="page-title"><?php esc_html_e('Our Treatments', 'preetidreams'); ?></h1>
            <p class="page-description">
                <?php esc_html_e('Discover our comprehensive range of advanced medical spa treatments designed to enhance your natural beauty and well-being.', 'preetidreams'); ?>
            </p>
        </header>

        <?php if (have_posts()) : ?>

            <!-- Treatments Grid -->
            <div class="treatments-grid">
                <?php while (have_posts()) : the_post(); ?>

                    <article id="treatment-<?php the_ID(); ?>" <?php post_class('treatment-card'); ?>>

                        <!-- Treatment Image -->
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="treatment-image">
                                <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(sprintf(__('Learn more about %s', 'preetidreams'), get_the_title())); ?>">
                                    <?php the_post_thumbnail('treatment-card', [
                                        'alt' => get_the_title(),
                                        'loading' => 'lazy'
                                    ]); ?>
                                </a>

                                <!-- Treatment Category Badge -->
                                <?php
                                $categories = get_the_terms(get_the_ID(), 'treatment_category');
                                if ($categories && !is_wp_error($categories)) :
                                    $primary_category = $categories[0];
                                ?>
                                    <span class="treatment-category"><?php echo esc_html($primary_category->name); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Treatment Content -->
                        <div class="treatment-content">
                            <header class="treatment-header">
                                <h2 class="treatment-title">
                                    <a href="<?php the_permalink(); ?>" rel="bookmark">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <!-- Treatment Meta -->
                                <div class="treatment-meta">
                                    <?php
                                    // Custom fields for treatment details
                                    $duration = get_post_meta(get_the_ID(), 'treatment_duration', true);
                                    $price_range = get_post_meta(get_the_ID(), 'treatment_price_range', true);
                                    $popularity = get_post_meta(get_the_ID(), 'treatment_popularity', true);
                                    ?>

                                    <?php if ($duration) : ?>
                                        <span class="treatment-duration">
                                            <span class="icon">⏱️</span>
                                            <?php echo esc_html($duration); ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if ($price_range) : ?>
                                        <span class="treatment-price">
                                            <span class="icon">💰</span>
                                            <?php echo esc_html($price_range); ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if ($popularity === 'popular') : ?>
                                        <span class="treatment-badge popular">
                                            <span class="icon">⭐</span>
                                            <?php esc_html_e('Popular', 'preetidreams'); ?>
                                        </span>
                                    <?php elseif ($popularity === 'new') : ?>
                                        <span class="treatment-badge new">
                                            <span class="icon">✨</span>
                                            <?php esc_html_e('New', 'preetidreams'); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </header>

                            <!-- Treatment Description -->
                            <div class="treatment-description">
                                <?php
                                if (has_excerpt()) {
                                    the_excerpt();
                                } else {
                                    echo wp_trim_words(get_the_content(), 25, '...');
                                }
                                ?>
                            </div>

                            <!-- Treatment Actions -->
                            <div class="treatment-actions">
                                <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                                    <?php esc_html_e('Learn More', 'preetidreams'); ?>
                                </a>

                                <a href="#consultation" class="btn btn-secondary consultation-link"
                                   data-treatment="<?php echo esc_attr(get_the_title()); ?>">
                                    <?php esc_html_e('Book Consultation', 'preetidreams'); ?>
                                </a>
                            </div>
                        </div>

                    </article>

                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <?php
            the_posts_pagination([
                'prev_text' => __('&laquo; Previous Treatments', 'preetidreams'),
                'next_text' => __('More Treatments &raquo;', 'preetidreams'),
                'class'     => 'treatments-pagination',
            ]);
            ?>

        <?php else : ?>

            <!-- No Treatments Found -->
            <div class="no-treatments">
                <h2><?php esc_html_e('No Treatments Available', 'preetidreams'); ?></h2>
                <p><?php esc_html_e('We are currently updating our treatment offerings. Please check back soon or contact us for more information.', 'preetidreams'); ?></p>

                <div class="contact-actions">
                    <?php
                    $phone = preetidreams_get_phone();
                    if ($phone) : ?>
                        <a href="tel:<?php echo esc_attr($phone); ?>" class="btn btn-primary">
                            <?php esc_html_e('Call Us', 'preetidreams'); ?>
                        </a>
                    <?php endif; ?>

                    <a href="#consultation" class="btn btn-secondary">
                        <?php esc_html_e('Request Information', 'preetidreams'); ?>
                    </a>
                </div>
            </div>

        <?php endif; ?>

        <!-- Call-to-Action Section -->
        <section class="treatments-cta">
            <div class="cta-content">
                <h2><?php esc_html_e('Ready to Get Started?', 'preetidreams'); ?></h2>
                <p><?php esc_html_e('Schedule a consultation with our expert team to discuss which treatments are right for you.', 'preetidreams'); ?></p>

                <div class="cta-actions">
                    <a href="#consultation" class="btn btn-primary btn-large">
                        <?php esc_html_e('Free Consultation', 'preetidreams'); ?>
                    </a>

                    <?php
                    $phone = preetidreams_get_phone();
                    if ($phone) : ?>
                        <a href="tel:<?php echo esc_attr($phone); ?>" class="btn btn-outline btn-large">
                            <?php echo esc_html($phone); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

    </div>
</main>

<?php get_footer(); ?>
