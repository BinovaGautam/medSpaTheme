<!-- Modern Medical Spa Footer -->
<footer class="footer-component footer-modern" role="contentinfo">

    <!-- Hero CTA Section with Background -->
    <section class="footer-hero-cta" style="background-image: url('<?php echo esc_url(get_theme_mod('footer_hero_background_image', '')); ?>');">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="hero-inner">
                <h2 class="hero-headline">
                    <?php echo esc_html(get_theme_mod('footer_hero_headline', 'Ready to Transform Your Beauty Journey?')); ?>
                </h2>
                <p class="hero-subtext">
                    <?php echo esc_html(get_theme_mod('footer_hero_subtext', 'Experience luxury medical aesthetics with Dr. Preeti Sharma in Beverly Hills')); ?>
                </p>

                <div class="hero-cta-group">
                    <button class="cta-primary-modern" onclick="window.open('<?php echo esc_url(get_theme_mod('footer_hero_primary_link', '/contact')); ?>', '_self')">
                        <span class="cta-icon">📅</span>
                        <span><?php echo esc_html(get_theme_mod('footer_hero_primary_text', 'Book Your Consultation')); ?></span>
                    </button>
                    <button class="cta-secondary-modern" onclick="window.open('tel:<?php echo esc_attr(preetidreams_get_phone()); ?>', '_self')">
                        <span class="cta-icon">📞</span>
                        <span><?php echo esc_html(get_theme_mod('footer_hero_secondary_text', 'Call Now')); ?></span>
                    </button>
                </div>

                <div class="trust-badges">
                    <div class="trust-badge">
                        <span class="badge-icon">🏥</span>
                        <span>Board Certified</span>
                    </div>
                    <div class="trust-badge">
                        <span class="badge-icon">✨</span>
                        <span>15+ Years Experience</span>
                    </div>
                    <div class="trust-badge">
                        <span class="badge-icon">🔒</span>
                        <span>HIPAA Compliant</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Full-Width Interactive Map Section -->
    <?php if (get_theme_mod('footer_map_enabled', true)) : ?>
    <section class="footer-map-section">
        <div class="map-container">
            <div class="google-map-wrapper">
                <iframe
                    src="<?php echo esc_url(get_theme_mod('footer_map_embed_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3305.2069!2d-118.3974881!3d34.0738!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzTCsDA0JzI1LjciTiAxMTjCsDIzJzUxLjAiVw!5e0!3m2!1sen!2sus!4v1234567890')); ?>"
                    width="100%"
                    height="<?php echo esc_attr(get_theme_mod('footer_map_height', '400')); ?>"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="<?php esc_attr_e('PreetiDreams Medical Spa Location', 'preetidreams'); ?>"
                    aria-label="<?php esc_attr_e('Interactive map showing clinic location', 'preetidreams'); ?>">
                </iframe>
            </div>

            <!-- Map Overlay with Clinic Information -->
            <div class="map-overlay">
                <div class="clinic-info-card">
                    <div class="clinic-marker">
                        <span class="marker-icon">📍</span>
                        <div class="clinic-details">
                            <h3 class="clinic-name"><?php bloginfo('name'); ?></h3>
                            <p class="clinic-tagline"><?php echo esc_html(get_theme_mod('footer_clinic_tagline', 'Beverly Hills Medical Spa')); ?></p>
                            <?php $address = preetidreams_get_address(); if ($address) : ?>
                                <p class="clinic-address"><?php echo esc_html($address); ?></p>
                            <?php endif; ?>
                            <a href="<?php echo esc_url(get_theme_mod('footer_directions_link', 'https://maps.google.com')); ?>"
                               class="directions-btn" target="_blank" rel="noopener noreferrer">
                                <span>🗺️</span> Get Directions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Information Cards Grid -->
    <section class="footer-cards-section">
        <div class="footer-container">
            <div class="cards-grid">

                <!-- Contact Information Card -->
                <div class="info-card contact-card">
                    <div class="card-header">
                        <div class="card-icon">📞</div>
                        <h3 class="card-title"><?php esc_html_e('Contact Information', 'preetidreams'); ?></h3>
                    </div>
                    <div class="card-content">
                        <?php $phone = preetidreams_get_phone(); if ($phone) : ?>
                            <div class="contact-item">
                                <span class="contact-label">Phone:</span>
                                <a href="tel:<?php echo esc_attr($phone); ?>" class="contact-link">
                                    <?php echo esc_html($phone); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php $email = preetidreams_get_email(); if ($email) : ?>
                            <div class="contact-item">
                                <span class="contact-label">Email:</span>
                                <a href="mailto:<?php echo esc_attr($email); ?>" class="contact-link">
                                    <?php echo esc_html($email); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php $hours = preetidreams_get_hours(); if ($hours) : ?>
                            <div class="contact-item">
                                <span class="contact-label">Hours:</span>
                                <span class="contact-value"><?php echo wp_kses_post(nl2br(esc_html($hours))); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Services Highlight Card -->
                <div class="info-card services-card">
                    <div class="card-header">
                        <div class="card-icon">✨</div>
                        <h3 class="card-title"><?php esc_html_e('Popular Treatments', 'preetidreams'); ?></h3>
                    </div>
                    <div class="card-content">
                        <div class="service-item">
                            <span class="service-name"><?php echo esc_html(get_theme_mod('footer_service_1', 'Botox & Dermal Fillers')); ?></span>
                        </div>
                        <div class="service-item">
                            <span class="service-name"><?php echo esc_html(get_theme_mod('footer_service_2', 'Laser Skin Resurfacing')); ?></span>
                        </div>
                        <div class="service-item">
                            <span class="service-name"><?php echo esc_html(get_theme_mod('footer_service_3', 'Chemical Peels')); ?></span>
                        </div>
                        <div class="service-item">
                            <span class="service-name"><?php echo esc_html(get_theme_mod('footer_service_4', 'Body Contouring')); ?></span>
                        </div>
                        <a href="<?php echo esc_url(home_url('/treatments/')); ?>" class="view-all-services">
                            View All Treatments →
                        </a>
                    </div>
                </div>

                <!-- Credentials & Awards Card -->
                <div class="info-card credentials-card">
                    <div class="card-header">
                        <div class="card-icon">🏆</div>
                        <h3 class="card-title"><?php esc_html_e('Medical Excellence', 'preetidreams'); ?></h3>
                    </div>
                    <div class="card-content">
                        <div class="credential-item">
                            <div class="credential-icon">🏥</div>
                            <div class="credential-details">
                                <strong><?php echo esc_html(get_theme_mod('footer_board_certification', 'Board-Certified Physician')); ?></strong>
                                <span><?php echo esc_html(get_theme_mod('footer_certification_details', 'American Board of Dermatology')); ?></span>
                            </div>
                        </div>
                        <div class="credential-item">
                            <div class="credential-icon">⭐</div>
                            <div class="credential-details">
                                <strong><?php echo esc_html(get_theme_mod('footer_experience_years', '15+ Years of Excellence')); ?></strong>
                                <span><?php echo esc_html(get_theme_mod('footer_expertise_area', 'Aesthetic Medicine Expertise')); ?></span>
                            </div>
                        </div>
                        <div class="credential-item">
                            <div class="credential-icon">🏆</div>
                            <div class="credential-details">
                                <strong><?php echo esc_html(get_theme_mod('footer_recognition', 'Recognition & Awards')); ?></strong>
                                <span><?php echo esc_html(get_theme_mod('footer_award_details', 'Top Medical Spa - Beverly Hills')); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media & Reviews Card -->
                <div class="info-card social-card">
                    <div class="card-header">
                        <div class="card-icon">💬</div>
                        <h3 class="card-title"><?php esc_html_e('Connect & Reviews', 'preetidreams'); ?></h3>
                    </div>
                    <div class="card-content">
                        <div class="social-links">
                            <?php $facebook = preetidreams_get_social_link('facebook'); if ($facebook) : ?>
                                <a href="<?php echo esc_url($facebook); ?>" class="social-link facebook" target="_blank" rel="noopener noreferrer">
                                    <span class="social-icon">📘</span>
                                    <span>Facebook</span>
                                </a>
                            <?php endif; ?>

                            <?php $instagram = preetidreams_get_social_link('instagram'); if ($instagram) : ?>
                                <a href="<?php echo esc_url($instagram); ?>" class="social-link instagram" target="_blank" rel="noopener noreferrer">
                                    <span class="social-icon">📷</span>
                                    <span>Instagram</span>
                                </a>
                            <?php endif; ?>

                            <?php $linkedin = preetidreams_get_social_link('linkedin'); if ($linkedin) : ?>
                                <a href="<?php echo esc_url($linkedin); ?>" class="social-link linkedin" target="_blank" rel="noopener noreferrer">
                                    <span class="social-icon">💼</span>
                                    <span>LinkedIn</span>
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="review-highlight">
                            <div class="review-stars">⭐⭐⭐⭐⭐</div>
                            <p class="review-text"><?php echo esc_html(get_theme_mod('footer_review_text', '5.0 stars from 200+ verified patients')); ?></p>
                            <a href="<?php echo esc_url(get_theme_mod('footer_reviews_link', '#reviews')); ?>" class="view-reviews">
                                Read All Reviews →
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Newsletter & Additional Social -->
    <section class="footer-newsletter-section">
        <div class="footer-container">
            <div class="newsletter-content">
                <div class="newsletter-info">
                    <h3 class="newsletter-title"><?php echo esc_html(get_theme_mod('footer_newsletter_title', 'Stay Informed About Latest Treatments')); ?></h3>
                    <p class="newsletter-description"><?php echo esc_html(get_theme_mod('footer_newsletter_description', 'Get exclusive beauty tips, treatment updates, and special offers delivered to your inbox.')); ?></p>
                </div>
                <div class="newsletter-form">
                    <form class="newsletter-signup" action="#" method="post">
                        <div class="form-group">
                            <input type="email" name="newsletter_email" placeholder="Enter your email address" required class="newsletter-input">
                            <button type="submit" class="newsletter-submit">
                                <span>Subscribe</span>
                                <span class="submit-icon">→</span>
                            </button>
                        </div>
                        <p class="newsletter-privacy">
                            <small>🔒 Your privacy is protected. Unsubscribe anytime.</small>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Bottom Navigation & Legal -->
    <section class="footer-bottom">
        <div class="footer-container">
            <div class="footer-bottom-content">

                <!-- Footer Navigation -->
                <nav class="footer-nav" role="navigation" aria-label="<?php esc_attr_e('Footer navigation', 'preetidreams'); ?>">
                    <?php if (has_nav_menu('footer')) : ?>
                        <?php wp_nav_menu([
                            'theme_location' => 'footer',
                            'menu_class'     => 'footer-menu',
                            'container'      => false,
                            'depth'          => 1,
                        ]); ?>
                    <?php else : ?>
                        <ul class="footer-menu">
                            <li><a href="<?php echo esc_url(home_url('/treatments/')); ?>"><?php esc_html_e('Treatments', 'preetidreams'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/about/')); ?>"><?php esc_html_e('About', 'preetidreams'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Contact', 'preetidreams'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>"><?php esc_html_e('Privacy', 'preetidreams'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/terms/')); ?>"><?php esc_html_e('Terms', 'preetidreams'); ?></a></li>
                        </ul>
                    <?php endif; ?>
                </nav>

                <!-- Copyright -->
                <div class="site-info">
                    <p>
                        &copy; <?php echo date('Y'); ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="brand-link">
                            <?php bloginfo('name'); ?>
                        </a>
                        <?php esc_html_e('Medical Spa. All rights reserved.', 'preetidreams'); ?>
                    </p>
                    <p class="medical-disclaimer">
                        <small><?php echo esc_html(get_theme_mod('footer_medical_disclaimer', 'Individual results may vary. Not a guarantee of specific results.')); ?></small>
                    </p>
                </div>

            </div>

            <!-- Back to Top Button -->
            <button class="back-to-top-modern" aria-label="<?php esc_attr_e('Scroll back to top', 'preetidreams'); ?>" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
                <span class="back-icon">↑</span>
                <span class="sr-only"><?php esc_html_e('Back to top', 'preetidreams'); ?></span>
            </button>

        </div>
    </section>

</footer>

<?php wp_footer(); ?>

</body>
</html>
