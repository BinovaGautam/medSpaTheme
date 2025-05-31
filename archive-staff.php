<?php get_header(); ?>

<main id="main" class="site-main">
    <div class="container">

        <!-- Page Header -->
        <header class="page-header staff-archive-header">
            <h1 class="page-title"><?php esc_html_e('Our Expert Team', 'preetidreams'); ?></h1>
            <p class="page-description">
                <?php esc_html_e('Meet our highly trained and experienced medical professionals dedicated to helping you achieve your aesthetic goals safely and effectively.', 'preetidreams'); ?>
            </p>
        </header>

        <?php if (have_posts()) : ?>

            <!-- Staff Grid -->
            <div class="staff-grid">
                <?php while (have_posts()) : the_post(); ?>

                    <article id="staff-<?php the_ID(); ?>" <?php post_class('staff-card'); ?>>

                        <!-- Staff Photo -->
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="staff-photo">
                                <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(sprintf(__('Learn more about %s', 'preetidreams'), get_the_title())); ?>">
                                    <?php the_post_thumbnail('staff-photo', [
                                        'alt' => get_the_title(),
                                        'loading' => 'lazy'
                                    ]); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- Staff Content -->
                        <div class="staff-content">
                            <header class="staff-header">
                                <h2 class="staff-name">
                                    <a href="<?php the_permalink(); ?>" rel="bookmark">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <?php
                                // Staff position/title
                                $position = get_post_meta(get_the_ID(), 'staff_position', true);
                                if ($position) : ?>
                                    <p class="staff-position"><?php echo esc_html($position); ?></p>
                                <?php endif; ?>

                                <?php
                                // Staff credentials
                                $credentials = get_post_meta(get_the_ID(), 'staff_credentials', true);
                                if ($credentials) : ?>
                                    <p class="staff-credentials"><?php echo esc_html($credentials); ?></p>
                                <?php endif; ?>
                            </header>

                            <!-- Staff Bio Preview -->
                            <div class="staff-bio">
                                <?php
                                if (has_excerpt()) {
                                    the_excerpt();
                                } else {
                                    echo wp_trim_words(get_the_content(), 20, '...');
                                }
                                ?>
                            </div>

                            <!-- Staff Specialties -->
                            <?php
                            $specialties = get_post_meta(get_the_ID(), 'staff_specialties', true);
                            if ($specialties) : ?>
                                <div class="staff-specialties">
                                    <h4><?php esc_html_e('Specialties', 'preetidreams'); ?></h4>
                                    <div class="specialties-list">
                                        <?php
                                        $specialty_array = explode(',', $specialties);
                                        foreach ($specialty_array as $specialty) : ?>
                                            <span class="specialty-tag"><?php echo esc_html(trim($specialty)); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Staff Actions -->
                            <div class="staff-actions">
                                <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                                    <?php esc_html_e('View Profile', 'preetidreams'); ?>
                                </a>

                                <a href="#consultation" class="btn btn-secondary consultation-link"
                                   data-staff="<?php echo esc_attr(get_the_title()); ?>">
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
                'prev_text' => __('&laquo; Previous Team Members', 'preetidreams'),
                'next_text' => __('More Team Members &raquo;', 'preetidreams'),
                'class'     => 'staff-pagination',
            ]);
            ?>

        <?php else : ?>

            <!-- No Staff Found -->
            <div class="no-staff">
                <h2><?php esc_html_e('Our Team Information Coming Soon', 'preetidreams'); ?></h2>
                <p><?php esc_html_e('We are currently updating our team profiles. Please contact us to learn more about our expert medical professionals.', 'preetidreams'); ?></p>

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

        <!-- Why Choose Our Team Section -->
        <section class="team-benefits">
            <div class="benefits-content">
                <h2><?php esc_html_e('Why Choose Our Expert Team?', 'preetidreams'); ?></h2>

                <div class="benefits-grid">
                    <div class="benefit-item">
                        <div class="benefit-icon">🎓</div>
                        <h3><?php esc_html_e('Highly Trained', 'preetidreams'); ?></h3>
                        <p><?php esc_html_e('Board-certified professionals with extensive training in advanced aesthetic procedures.', 'preetidreams'); ?></p>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">👥</div>
                        <h3><?php esc_html_e('Personalized Care', 'preetidreams'); ?></h3>
                        <p><?php esc_html_e('Individual attention and customized treatment plans tailored to your unique needs.', 'preetidreams'); ?></p>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">🏆</div>
                        <h3><?php esc_html_e('Award-Winning Results', 'preetidreams'); ?></h3>
                        <p><?php esc_html_e('Recognized excellence in aesthetic medicine with thousands of satisfied patients.', 'preetidreams'); ?></p>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">🔬</div>
                        <h3><?php esc_html_e('Latest Technology', 'preetidreams'); ?></h3>
                        <p><?php esc_html_e('State-of-the-art equipment and cutting-edge techniques for optimal results.', 'preetidreams'); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Team CTA Section -->
        <section class="team-cta">
            <div class="cta-content">
                <h2><?php esc_html_e('Ready to Meet Our Team?', 'preetidreams'); ?></h2>
                <p><?php esc_html_e('Schedule a consultation with our expert medical professionals and discover how we can help you achieve your aesthetic goals.', 'preetidreams'); ?></p>

                <div class="cta-actions">
                    <a href="#consultation" class="btn btn-primary btn-large">
                        <?php esc_html_e('Schedule Consultation', 'preetidreams'); ?>
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
