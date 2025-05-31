<?php get_header(); ?>

<main id="main" class="site-main">

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-background">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1 class="hero-title">
                            <?php
                            $hero_title = get_theme_mod('hero_title', __('Transform Your Beauty with Advanced Medical Spa Treatments', 'preetidreams'));
                            echo esc_html($hero_title);
                            ?>
                        </h1>

                        <p class="hero-subtitle">
                            <?php
                            $hero_subtitle = get_theme_mod('hero_subtitle', __('Experience the latest in non-surgical aesthetic treatments performed by board-certified professionals in a luxurious, comfortable environment.', 'preetidreams'));
                            echo esc_html($hero_subtitle);
                            ?>
                        </p>

                        <div class="hero-actions">
                            <a href="#consultation" class="btn btn-primary btn-large">
                                <?php esc_html_e('Free Consultation', 'preetidreams'); ?>
                            </a>

                            <?php
                            $phone = preetidreams_get_phone();
                            if ($phone) : ?>
                                <a href="tel:<?php echo esc_attr($phone); ?>" class="btn btn-outline btn-large">
                                    <span class="icon">📞</span>
                                    <?php echo esc_html($phone); ?>
                                </a>
                            <?php endif; ?>
                        </div>

                        <!-- Trust Indicators -->
                        <div class="trust-indicators">
                            <div class="trust-item">
                                <span class="trust-icon">✅</span>
                                <span class="trust-text"><?php esc_html_e('Board Certified', 'preetidreams'); ?></span>
                            </div>
                            <div class="trust-item">
                                <span class="trust-icon">🏆</span>
                                <span class="trust-text"><?php esc_html_e('Award Winning', 'preetidreams'); ?></span>
                            </div>
                            <div class="trust-item">
                                <span class="trust-icon">💯</span>
                                <span class="trust-text"><?php esc_html_e('1000+ Happy Patients', 'preetidreams'); ?></span>
                            </div>
                        </div>
                    </div>

                    <?php
                    $hero_image = get_theme_mod('hero_image');
                    if ($hero_image) : ?>
                        <div class="hero-image">
                            <img src="<?php echo esc_url($hero_image); ?>" alt="<?php esc_attr_e('Medical Spa Treatment', 'preetidreams'); ?>" loading="eager">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Treatments Section -->
    <section class="featured-treatments">
        <div class="container">
            <header class="section-header">
                <h2 class="section-title"><?php esc_html_e('Popular Treatments', 'preetidreams'); ?></h2>
                <p class="section-subtitle"><?php esc_html_e('Discover our most sought-after aesthetic treatments designed to enhance your natural beauty.', 'preetidreams'); ?></p>
            </header>

            <div class="treatments-showcase">
                <?php
                // Get featured treatments
                $featured_treatments = get_posts([
                    'post_type' => 'treatment',
                    'posts_per_page' => 6,
                    'meta_key' => 'treatment_featured',
                    'meta_value' => '1',
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                ]);

                if ($featured_treatments) :
                    foreach ($featured_treatments as $treatment) : setup_postdata($treatment); ?>

                        <div class="treatment-showcase-item">
                            <?php if (has_post_thumbnail($treatment->ID)) : ?>
                                <div class="treatment-image">
                                    <a href="<?php echo get_permalink($treatment->ID); ?>">
                                        <?php echo get_the_post_thumbnail($treatment->ID, 'treatment-card', ['alt' => get_the_title($treatment->ID)]); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <div class="treatment-content">
                                <h3 class="treatment-name">
                                    <a href="<?php echo get_permalink($treatment->ID); ?>">
                                        <?php echo get_the_title($treatment->ID); ?>
                                    </a>
                                </h3>

                                <p class="treatment-description">
                                    <?php echo wp_trim_words(get_the_excerpt($treatment->ID), 15); ?>
                                </p>

                                <div class="treatment-meta">
                                    <?php
                                    $duration = get_post_meta($treatment->ID, 'treatment_duration', true);
                                    $price_range = get_post_meta($treatment->ID, 'treatment_price_range', true);
                                    ?>

                                    <?php if ($duration) : ?>
                                        <span class="meta-item">
                                            <span class="icon">⏱️</span>
                                            <?php echo esc_html($duration); ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if ($price_range) : ?>
                                        <span class="meta-item">
                                            <span class="icon">💰</span>
                                            <?php echo esc_html($price_range); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <a href="<?php echo get_permalink($treatment->ID); ?>" class="treatment-link">
                                    <?php esc_html_e('Learn More', 'preetidreams'); ?>
                                </a>
                            </div>
                        </div>

                    <?php endforeach;
                    wp_reset_postdata();
                else : ?>
                    <!-- Fallback content if no treatments -->
                    <div class="no-treatments-message">
                        <p><?php esc_html_e('Featured treatments coming soon. Contact us to learn about our available services.', 'preetidreams'); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="section-cta">
                <a href="<?php echo esc_url(get_post_type_archive_link('treatment')); ?>" class="btn btn-primary">
                    <?php esc_html_e('View All Treatments', 'preetidreams'); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="about-layout">
                <div class="about-content">
                    <h2 class="about-title"><?php esc_html_e('Why Choose Our Medical Spa?', 'preetidreams'); ?></h2>

                    <div class="about-text">
                        <p><?php esc_html_e('We combine advanced medical expertise with luxurious spa comfort to deliver exceptional aesthetic results. Our board-certified professionals use the latest technology and techniques to help you achieve your beauty goals safely and effectively.', 'preetidreams'); ?></p>
                    </div>

                    <div class="features-grid">
                        <div class="feature-item">
                            <div class="feature-icon">👨‍⚕️</div>
                            <h3><?php esc_html_e('Expert Professionals', 'preetidreams'); ?></h3>
                            <p><?php esc_html_e('Board-certified doctors and experienced aestheticians.', 'preetidreams'); ?></p>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">🔬</div>
                            <h3><?php esc_html_e('Advanced Technology', 'preetidreams'); ?></h3>
                            <p><?php esc_html_e('State-of-the-art equipment for optimal results.', 'preetidreams'); ?></p>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">🏥</div>
                            <h3><?php esc_html_e('Medical-Grade Safety', 'preetidreams'); ?></h3>
                            <p><?php esc_html_e('Highest standards of safety and cleanliness.', 'preetidreams'); ?></p>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">💎</div>
                            <h3><?php esc_html_e('Luxury Experience', 'preetidreams'); ?></h3>
                            <p><?php esc_html_e('Comfortable, spa-like environment for relaxation.', 'preetidreams'); ?></p>
                        </div>
                    </div>

                    <div class="about-cta">
                        <a href="<?php echo esc_url(get_post_type_archive_link('staff')); ?>" class="btn btn-outline">
                            <?php esc_html_e('Meet Our Team', 'preetidreams'); ?>
                        </a>
                    </div>
                </div>

                <div class="about-image">
                    <?php
                    $about_image = get_theme_mod('about_image');
                    if ($about_image) : ?>
                        <img src="<?php echo esc_url($about_image); ?>" alt="<?php esc_attr_e('Medical Spa Interior', 'preetidreams'); ?>" loading="lazy">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <header class="section-header">
                <h2 class="section-title"><?php esc_html_e('What Our Patients Say', 'preetidreams'); ?></h2>
                <p class="section-subtitle"><?php esc_html_e('Real stories from real patients who have transformed their confidence with our treatments.', 'preetidreams'); ?></p>
            </header>

            <div class="testimonials-grid">
                <?php
                // Get featured testimonials
                $testimonials = get_posts([
                    'post_type' => 'testimonial',
                    'posts_per_page' => 3,
                    'orderby' => 'rand'
                ]);

                if ($testimonials) :
                    foreach ($testimonials as $testimonial) : setup_postdata($testimonial); ?>

                        <div class="testimonial-card">
                            <?php if (has_post_thumbnail($testimonial->ID)) : ?>
                                <div class="testimonial-photo">
                                    <?php echo get_the_post_thumbnail($testimonial->ID, 'thumbnail', ['alt' => get_the_title($testimonial->ID)]); ?>
                                </div>
                            <?php endif; ?>

                            <div class="testimonial-content">
                                <div class="testimonial-text">
                                    <?php echo wp_trim_words(get_the_content(null, false, $testimonial->ID), 30); ?>
                                </div>

                                <div class="testimonial-author">
                                    <h4 class="author-name"><?php echo get_the_title($testimonial->ID); ?></h4>
                                    <?php
                                    $treatment = get_post_meta($testimonial->ID, 'testimonial_treatment', true);
                                    if ($treatment) : ?>
                                        <p class="treatment-received"><?php echo esc_html($treatment); ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="testimonial-rating">
                                    <span class="stars">⭐⭐⭐⭐⭐</span>
                                </div>
                            </div>
                        </div>

                    <?php endforeach;
                    wp_reset_postdata();
                endif; ?>
            </div>

            <div class="section-cta">
                <a href="<?php echo esc_url(get_post_type_archive_link('testimonial')); ?>" class="btn btn-outline">
                    <?php esc_html_e('Read More Reviews', 'preetidreams'); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- Consultation CTA Section -->
    <section id="consultation" class="consultation-section">
        <div class="container">
            <div class="consultation-content">
                <div class="consultation-text">
                    <h2><?php esc_html_e('Ready to Start Your Transformation?', 'preetidreams'); ?></h2>
                    <p><?php esc_html_e('Book your complimentary consultation today and discover how our personalized treatments can help you achieve your aesthetic goals.', 'preetidreams'); ?></p>

                    <div class="consultation-benefits">
                        <div class="benefit-item">
                            <span class="icon">✅</span>
                            <span class="text"><?php esc_html_e('Complimentary consultation', 'preetidreams'); ?></span>
                        </div>
                        <div class="benefit-item">
                            <span class="icon">✅</span>
                            <span class="text"><?php esc_html_e('Personalized treatment plan', 'preetidreams'); ?></span>
                        </div>
                        <div class="benefit-item">
                            <span class="icon">✅</span>
                            <span class="text"><?php esc_html_e('No pressure, just expert advice', 'preetidreams'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="consultation-form-wrapper">
                    <form class="consultation-form" action="#" method="post">
                        <h3><?php esc_html_e('Book Your Free Consultation', 'preetidreams'); ?></h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="consultation-name"><?php esc_html_e('Full Name', 'preetidreams'); ?></label>
                                <input type="text" id="consultation-name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="consultation-phone"><?php esc_html_e('Phone Number', 'preetidreams'); ?></label>
                                <input type="tel" id="consultation-phone" name="phone" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="consultation-email"><?php esc_html_e('Email Address', 'preetidreams'); ?></label>
                            <input type="email" id="consultation-email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="consultation-treatment"><?php esc_html_e('Treatment Interest', 'preetidreams'); ?></label>
                            <select id="consultation-treatment" name="treatment">
                                <option value=""><?php esc_html_e('Select a treatment (optional)', 'preetidreams'); ?></option>
                                <option value="botox"><?php esc_html_e('Botox', 'preetidreams'); ?></option>
                                <option value="fillers"><?php esc_html_e('Dermal Fillers', 'preetidreams'); ?></option>
                                <option value="hydrafacial"><?php esc_html_e('HydraFacial', 'preetidreams'); ?></option>
                                <option value="laser"><?php esc_html_e('Laser Treatments', 'preetidreams'); ?></option>
                                <option value="other"><?php esc_html_e('Other', 'preetidreams'); ?></option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="consultation-message"><?php esc_html_e('Message (Optional)', 'preetidreams'); ?></label>
                            <textarea id="consultation-message" name="message" rows="3" placeholder="<?php esc_attr_e('Tell us about your aesthetic goals...', 'preetidreams'); ?>"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="consent" required>
                                <span class="checkmark"></span>
                                <?php esc_html_e('I consent to receive communications about my consultation and treatment options.', 'preetidreams'); ?>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <?php esc_html_e('Schedule My Consultation', 'preetidreams'); ?>
                        </button>

                        <p class="form-privacy">
                            <?php esc_html_e('Your information is secure and confidential. We follow HIPAA guidelines.', 'preetidreams'); ?>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>
