<?php get_header(); ?>

<main id="main" class="site-main">
    <?php while (have_posts()) : the_post(); ?>

        <!-- Treatment Hero Section -->
        <section class="treatment-hero">
            <div class="container">
                <div class="hero-content">

                    <!-- Breadcrumbs -->
                    <nav class="breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumbs', 'preetidreams'); ?>">
                        <ol>
                            <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'preetidreams'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/treatments/')); ?>"><?php esc_html_e('Treatments', 'preetidreams'); ?></a></li>
                            <li aria-current="page"><?php the_title(); ?></li>
                        </ol>
                    </nav>

                    <div class="hero-layout">
                        <!-- Treatment Info -->
                        <div class="treatment-info">
                            <header class="treatment-header">
                                <?php
                                // Treatment category
                                $categories = get_the_terms(get_the_ID(), 'treatment_category');
                                if ($categories && !is_wp_error($categories)) :
                                    $primary_category = $categories[0];
                                ?>
                                    <span class="treatment-category"><?php echo esc_html($primary_category->name); ?></span>
                                <?php endif; ?>

                                <h1 class="treatment-title"><?php the_title(); ?></h1>

                                <!-- Treatment Meta -->
                                <div class="treatment-meta">
                                    <?php
                                    $duration = get_post_meta(get_the_ID(), 'treatment_duration', true);
                                    $price_range = get_post_meta(get_the_ID(), 'treatment_price_range', true);
                                    $downtime = get_post_meta(get_the_ID(), 'treatment_downtime', true);
                                    $results_timeline = get_post_meta(get_the_ID(), 'treatment_results_timeline', true);
                                    ?>

                                    <div class="meta-grid">
                                        <?php if ($duration) : ?>
                                            <div class="meta-item">
                                                <span class="meta-label"><?php esc_html_e('Duration', 'preetidreams'); ?></span>
                                                <span class="meta-value">
                                                    <span class="icon">⏱️</span>
                                                    <?php echo esc_html($duration); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($price_range) : ?>
                                            <div class="meta-item">
                                                <span class="meta-label"><?php esc_html_e('Investment', 'preetidreams'); ?></span>
                                                <span class="meta-value">
                                                    <span class="icon">💰</span>
                                                    <?php echo esc_html($price_range); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($downtime) : ?>
                                            <div class="meta-item">
                                                <span class="meta-label"><?php esc_html_e('Downtime', 'preetidreams'); ?></span>
                                                <span class="meta-value">
                                                    <span class="icon">💤</span>
                                                    <?php echo esc_html($downtime); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($results_timeline) : ?>
                                            <div class="meta-item">
                                                <span class="meta-label"><?php esc_html_e('Results Timeline', 'preetidreams'); ?></span>
                                                <span class="meta-value">
                                                    <span class="icon">📅</span>
                                                    <?php echo esc_html($results_timeline); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Treatment Summary -->
                                <?php if (has_excerpt()) : ?>
                                    <div class="treatment-summary">
                                        <?php the_excerpt(); ?>
                                    </div>
                                <?php endif; ?>
                            </header>

                            <!-- Quick Actions -->
                            <div class="treatment-actions">
                                <a href="#consultation" class="btn btn-primary btn-large consultation-cta"
                                   data-treatment="<?php echo esc_attr(get_the_title()); ?>">
                                    <?php esc_html_e('Book Free Consultation', 'preetidreams'); ?>
                                </a>

                                <?php
                                $phone = preetidreams_get_phone();
                                if ($phone) : ?>
                                    <a href="tel:<?php echo esc_attr($phone); ?>" class="btn btn-outline">
                                        <?php esc_html_e('Call Now', 'preetidreams'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Treatment Image -->
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="treatment-image">
                                <?php the_post_thumbnail('hero-banner', [
                                    'alt' => get_the_title(),
                                ]); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Treatment Content -->
        <section class="treatment-content">
            <div class="container">
                <article id="treatment-<?php the_ID(); ?>" <?php post_class('treatment-details'); ?>>

                    <div class="content-layout">
                        <!-- Main Content -->
                        <div class="main-content">
                            <?php the_content(); ?>

                            <?php
                            // Treatment Benefits
                            $benefits = get_post_meta(get_the_ID(), 'treatment_benefits', true);
                            if ($benefits) : ?>
                                <div class="treatment-benefits">
                                    <h2><?php esc_html_e('Benefits', 'preetidreams'); ?></h2>
                                    <div class="benefits-content">
                                        <?php echo wp_kses_post($benefits); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php
                            // Treatment Process
                            $process = get_post_meta(get_the_ID(), 'treatment_process', true);
                            if ($process) : ?>
                                <div class="treatment-process">
                                    <h2><?php esc_html_e('What to Expect', 'preetidreams'); ?></h2>
                                    <div class="process-content">
                                        <?php echo wp_kses_post($process); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php
                            // Before & After Gallery
                            $before_after_gallery = get_post_meta(get_the_ID(), 'treatment_before_after', true);
                            if ($before_after_gallery) : ?>
                                <div class="before-after-gallery">
                                    <h2><?php esc_html_e('Real Results', 'preetidreams'); ?></h2>
                                    <div class="gallery-grid">
                                        <?php echo wp_kses_post($before_after_gallery); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Sidebar -->
                        <aside class="treatment-sidebar">

                            <!-- Quick Booking Widget -->
                            <div class="sidebar-widget booking-widget">
                                <h3><?php esc_html_e('Book Your Consultation', 'preetidreams'); ?></h3>
                                <p><?php esc_html_e('Ready to transform your look? Schedule a personalized consultation.', 'preetidreams'); ?></p>

                                <div class="booking-actions">
                                    <a href="#consultation" class="btn btn-primary btn-block consultation-cta"
                                       data-treatment="<?php echo esc_attr(get_the_title()); ?>">
                                        <?php esc_html_e('Free Consultation', 'preetidreams'); ?>
                                    </a>

                                    <?php if ($phone) : ?>
                                        <a href="tel:<?php echo esc_attr($phone); ?>" class="btn btn-outline btn-block">
                                            <span class="icon">📞</span>
                                            <?php echo esc_html($phone); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Treatment Facts -->
                            <div class="sidebar-widget treatment-facts">
                                <h3><?php esc_html_e('Treatment Facts', 'preetidreams'); ?></h3>

                                <dl class="facts-list">
                                    <?php if ($duration) : ?>
                                        <dt><?php esc_html_e('Session Duration', 'preetidreams'); ?></dt>
                                        <dd><?php echo esc_html($duration); ?></dd>
                                    <?php endif; ?>

                                    <?php if ($downtime) : ?>
                                        <dt><?php esc_html_e('Recovery Time', 'preetidreams'); ?></dt>
                                        <dd><?php echo esc_html($downtime); ?></dd>
                                    <?php endif; ?>

                                    <?php if ($results_timeline) : ?>
                                        <dt><?php esc_html_e('Results Visible', 'preetidreams'); ?></dt>
                                        <dd><?php echo esc_html($results_timeline); ?></dd>
                                    <?php endif; ?>

                                    <?php
                                    $sessions_needed = get_post_meta(get_the_ID(), 'treatment_sessions_needed', true);
                                    if ($sessions_needed) : ?>
                                        <dt><?php esc_html_e('Sessions Needed', 'preetidreams'); ?></dt>
                                        <dd><?php echo esc_html($sessions_needed); ?></dd>
                                    <?php endif; ?>
                                </dl>
                            </div>

                            <!-- Related Treatments -->
                            <?php
                            $related_treatments = get_posts([
                                'post_type' => 'treatment',
                                'posts_per_page' => 3,
                                'post__not_in' => [get_the_ID()],
                                'meta_query' => [
                                    [
                                        'key' => 'treatment_category',
                                        'value' => $primary_category->term_id ?? '',
                                        'compare' => '='
                                    ]
                                ]
                            ]);

                            if ($related_treatments) : ?>
                                <div class="sidebar-widget related-treatments">
                                    <h3><?php esc_html_e('Related Treatments', 'preetidreams'); ?></h3>

                                    <div class="related-list">
                                        <?php foreach ($related_treatments as $related) : ?>
                                            <div class="related-item">
                                                <?php if (has_post_thumbnail($related->ID)) : ?>
                                                    <div class="related-image">
                                                        <?php echo get_the_post_thumbnail($related->ID, 'thumbnail'); ?>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="related-content">
                                                    <h4><a href="<?php echo get_permalink($related->ID); ?>"><?php echo get_the_title($related->ID); ?></a></h4>
                                                    <p><?php echo wp_trim_words(get_the_excerpt($related->ID), 10); ?></p>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </aside>
                    </div>

                </article>
            </div>
        </section>

        <!-- Call-to-Action Section -->
        <section class="treatment-cta">
            <div class="container">
                <div class="cta-content">
                    <h2><?php esc_html_e('Ready to Begin Your Transformation?', 'preetidreams'); ?></h2>
                    <p><?php esc_html_e('Schedule your personalized consultation today and discover how this treatment can help you achieve your aesthetic goals.', 'preetidreams'); ?></p>

                    <div class="cta-actions">
                        <a href="#consultation" class="btn btn-primary btn-large consultation-cta"
                           data-treatment="<?php echo esc_attr(get_the_title()); ?>">
                            <?php esc_html_e('Schedule Consultation', 'preetidreams'); ?>
                        </a>

                        <a href="<?php echo esc_url(home_url('/treatments/')); ?>" class="btn btn-outline">
                            <?php esc_html_e('View All Treatments', 'preetidreams'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </section>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
