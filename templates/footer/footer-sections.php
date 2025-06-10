<?php
/**
 * Footer Sections Template
 *
 * Contains all 5 footer sections for the luxury medical spa footer
 * Sprint: SPRINT-FOOTER-IMPL-001 | Task: T-FOOTER-001
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Section 1: Hero Call-to-Action Section
 */
function render_footer_hero_section() {
    ?>
    <section class="footer-hero-section" aria-labelledby="footer-hero-heading">
        <div class="container">
            <div class="footer-hero-content">
                <header class="footer-hero-header">
                    <h2 id="footer-hero-heading" class="footer-hero-title">
                        <?php echo get_theme_mod('footer_hero_title', 'Ready to Transform Your Wellness Journey 123?'); ?>
                    </h2>
                    <p class="footer-hero-subtitle">
                        <?php echo get_theme_mod('footer_hero_subtitle', 'Experience luxury medical spa treatments with personalized care and proven results'); ?>
                    </p>
                </header>

                <div class="footer-hero-actions">
                    <a href="<?php echo get_theme_mod('footer_primary_cta_url', '/contact/'); ?>"
                       class="btn btn-primary btn-large footer-cta-primary"
                       aria-describedby="footer-cta-primary-desc">
                        <?php echo get_theme_mod('footer_primary_cta_text', 'Schedule Consultation'); ?>
                    </a>
                    <span id="footer-cta-primary-desc" class="sr-only">Book your personalized consultation today</span>

                    <a href="<?php echo get_theme_mod('footer_secondary_cta_url', '/treatments/'); ?>"
                       class="btn btn-secondary btn-large footer-cta-secondary"
                       aria-describedby="footer-cta-secondary-desc">
                        <?php echo get_theme_mod('footer_secondary_cta_text', 'View Services'); ?>
                    </a>
                    <span id="footer-cta-secondary-desc" class="sr-only">Explore our medical spa treatment options</span>
                </div>

                <div class="footer-hero-badges" role="group" aria-label="Medical Spa Credentials">
                    <div class="badge-item">
                        <span class="badge-icon" aria-hidden="true">⭐</span>
                        <span class="badge-text"><?php echo get_theme_mod('footer_badge_1', 'Board Certified'); ?></span>
                    </div>
                    <div class="badge-item">
                        <span class="badge-icon" aria-hidden="true">🏆</span>
                        <span class="badge-text"><?php echo get_theme_mod('footer_badge_2', 'Award Winning'); ?></span>
                    </div>
                    <div class="badge-item">
                        <span class="badge-icon" aria-hidden="true">✨</span>
                        <span class="badge-text"><?php echo get_theme_mod('footer_badge_3', '5-Star Reviews'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
}

/**
 * Section 2: Four-Column Information Grid
 */
function render_footer_info_grid() {
    ?>
    <section class="footer-info-grid" aria-label="Contact Information and Services">
        <div class="container">
            <div class="info-grid-wrapper">

                <!-- Contact Information Column -->
                <div class="info-column contact-info" role="region" aria-labelledby="contact-heading">
                    <h3 id="contact-heading" class="column-title">
                        <span class="column-icon" aria-hidden="true">📍</span>
                        Contact Info
                    </h3>
                    <div class="column-content">
                        <div class="contact-item">
                            <span class="contact-icon" aria-hidden="true">📞</span>
                            <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', get_theme_mod('footer_phone', '(310) 555-0123')); ?>"
                               class="contact-link">
                                <span class="contact-label">Call Now:</span>
                                <span class="contact-value"><?php echo get_theme_mod('footer_phone', '(310) 555-0123'); ?></span>
                            </a>
                        </div>
                        <div class="contact-item">
                            <span class="contact-icon" aria-hidden="true">📧</span>
                            <a href="mailto:<?php echo get_theme_mod('footer_email', 'info@medspaa.com'); ?>"
                               class="contact-link">
                                <span class="contact-label">Email:</span>
                                <span class="contact-value"><?php echo get_theme_mod('footer_email', 'info@medspaa.com'); ?></span>
                            </a>
                        </div>
                        <div class="contact-item">
                            <span class="contact-icon" aria-hidden="true">📍</span>
                            <address class="contact-address">
                                <span class="contact-label">Address:</span>
                                <span class="contact-value"><?php echo get_theme_mod('footer_address', '123 Beverly Drive, Beverly Hills, CA 90210'); ?></span>
                            </address>
                        </div>
                        <div class="contact-item">
                            <span class="contact-icon" aria-hidden="true">🗺️</span>
                            <a href="<?php echo get_theme_mod('footer_directions_url', 'https://maps.google.com'); ?>"
                               class="contact-link" target="_blank" rel="noopener">
                                <span class="contact-label">Get Directions</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Services Column -->
                <div class="info-column services-info" role="region" aria-labelledby="services-heading">
                    <h3 id="services-heading" class="column-title">
                        <span class="column-icon" aria-hidden="true">🏥</span>
                        Our Services
                    </h3>
                    <nav class="column-content" aria-label="Medical Spa Services">
                        <ul class="services-list">
                            <li class="service-item">
                                <a href="<?php echo get_theme_mod('footer_service_1_url', '/treatments/facials/'); ?>" class="service-link">
                                    <span class="service-icon" aria-hidden="true">✨</span>
                                    <?php echo get_theme_mod('footer_service_1', 'Luxury Facials'); ?>
                                </a>
                            </li>
                            <li class="service-item">
                                <a href="<?php echo get_theme_mod('footer_service_2_url', '/treatments/botox/'); ?>" class="service-link">
                                    <span class="service-icon" aria-hidden="true">💉</span>
                                    <?php echo get_theme_mod('footer_service_2', 'Botox & Fillers'); ?>
                                </a>
                            </li>
                            <li class="service-item">
                                <a href="<?php echo get_theme_mod('footer_service_3_url', '/treatments/laser/'); ?>" class="service-link">
                                    <span class="service-icon" aria-hidden="true">🌟</span>
                                    <?php echo get_theme_mod('footer_service_3', 'Laser Treatments'); ?>
                                </a>
                            </li>
                            <li class="service-item">
                                <a href="<?php echo get_theme_mod('footer_service_4_url', '/treatments/massage/'); ?>" class="service-link">
                                    <span class="service-icon" aria-hidden="true">💆</span>
                                    <?php echo get_theme_mod('footer_service_4', 'Therapeutic Massage'); ?>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>

                <!-- Hours Column -->
                <div class="info-column hours-info" role="region" aria-labelledby="hours-heading">
                    <h3 id="hours-heading" class="column-title">
                        <span class="column-icon" aria-hidden="true">⏰</span>
                        Hours
                    </h3>
                    <div class="column-content">
                        <div class="hours-list">
                            <div class="hours-item">
                                <span class="hours-day">Monday - Friday:</span>
                                <span class="hours-time"><?php echo get_theme_mod('footer_hours_weekday', '9:00 AM - 6:00 PM'); ?></span>
                            </div>
                            <div class="hours-item">
                                <span class="hours-day">Saturday:</span>
                                <span class="hours-time"><?php echo get_theme_mod('footer_hours_saturday', '10:00 AM - 4:00 PM'); ?></span>
                            </div>
                            <div class="hours-item">
                                <span class="hours-day">Sunday:</span>
                                <span class="hours-time"><?php echo get_theme_mod('footer_hours_sunday', 'By Appointment'); ?></span>
                            </div>
                        </div>
                        <p class="hours-note">
                            <?php echo get_theme_mod('footer_hours_note', 'Emergency consultations available 24/7'); ?>
                        </p>
                    </div>
                </div>

                <!-- About/Doctor Column -->
                <div class="info-column about-info" role="region" aria-labelledby="about-heading">
                    <h3 id="about-heading" class="column-title">
                        <span class="column-icon" aria-hidden="true">👨‍⚕️</span>
                        About Us
                    </h3>
                    <div class="column-content">
                        <div class="doctor-profile">
                            <div class="doctor-image">
                                <img src="<?php echo get_theme_mod('footer_doctor_image', get_template_directory_uri() . '/assets/images/default-doctor.jpg'); ?>"
                                     alt="<?php echo get_theme_mod('footer_doctor_name', 'Dr. Preeti Sharma'); ?>"
                                     class="doctor-photo" loading="lazy">
                            </div>
                            <div class="doctor-info">
                                <h4 class="doctor-name"><?php echo get_theme_mod('footer_doctor_name', 'Dr. Preeti Sharma'); ?></h4>
                                <p class="doctor-credentials"><?php echo get_theme_mod('footer_doctor_credentials', 'Board Certified Physician'); ?></p>
                                <p class="doctor-bio"><?php echo get_theme_mod('footer_doctor_bio', 'Specializing in luxury medical spa treatments with over 15 years of experience.'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <?php
}

/**
 * Section 3: Interactive Map Section
 */
function render_footer_map_section() {
    ?>
    <section class="footer-map-section" aria-label="Location and Directions">
        <div class="map-container">
            <div class="interactive-map" id="footer-google-map" role="img" aria-label="Map showing medical spa location in Beverly Hills">
                <!-- Google Maps will be loaded here via JavaScript -->
                <div class="map-placeholder">
                    <p>Loading map...</p>
                </div>
            </div>
            <div class="map-overlay-info">
                <div class="clinic-info-card">
                    <h3 class="clinic-name"><?php echo get_theme_mod('footer_clinic_name', 'Beverly Hills Medical Spa'); ?></h3>
                    <p class="clinic-tagline"><?php echo get_theme_mod('footer_clinic_tagline', 'Visit Our Luxury Medical Spa'); ?></p>
                    <a href="<?php echo get_theme_mod('footer_directions_url', 'https://maps.google.com'); ?>"
                       class="btn btn-outline map-directions-btn" target="_blank" rel="noopener">
                        <span class="btn-icon" aria-hidden="true">🗺️</span>
                        Get Directions
                    </a>
                </div>
            </div>
        </div>
    </section>
    <?php
}

/**
 * Section 4: Newsletter & Social Engagement
 */
function render_footer_newsletter_section() {
    ?>
    <section class="footer-newsletter-section" aria-labelledby="newsletter-heading">
        <div class="container">
            <div class="newsletter-wrapper">
                <header class="newsletter-header">
                    <h3 id="newsletter-heading" class="newsletter-title">
                        <?php echo get_theme_mod('footer_newsletter_title', 'Stay Connected with Exclusive Wellness Tips'); ?>
                    </h3>
                    <p class="newsletter-subtitle">
                        <?php echo get_theme_mod('footer_newsletter_subtitle', 'Get the latest beauty and wellness insights delivered to your inbox'); ?>
                    </p>
                </header>

                <form class="newsletter-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" novalidate>
                    <input type="hidden" name="action" value="footer_newsletter_signup">
                    <?php wp_nonce_field('footer_newsletter_signup', 'newsletter_nonce'); ?>

                    <div class="form-group">
                        <label for="newsletter-email" class="sr-only">Email Address</label>
                        <input type="email"
                               id="newsletter-email"
                               name="newsletter_email"
                               class="newsletter-input"
                               placeholder="Enter your email address"
                               required
                               aria-describedby="newsletter-privacy">
                        <button type="submit" class="btn btn-primary newsletter-submit">
                            Subscribe
                        </button>
                    </div>

                    <p id="newsletter-privacy" class="newsletter-privacy">
                        <?php echo get_theme_mod('footer_newsletter_privacy', 'We respect your privacy. Unsubscribe at any time.'); ?>
                    </p>
                </form>

                <div class="social-media-links" role="group" aria-label="Social Media Links">
                    <h4 class="social-title">Follow Us</h4>
                    <div class="social-icons">
                        <?php if (get_theme_mod('footer_instagram_url')) : ?>
                        <a href="<?php echo esc_url(get_theme_mod('footer_instagram_url')); ?>"
                           class="social-link instagram" target="_blank" rel="noopener" aria-label="Follow us on Instagram">
                            <span class="social-icon" aria-hidden="true">📱</span>
                            <span class="social-text">Instagram</span>
                        </a>
                        <?php endif; ?>

                        <?php if (get_theme_mod('footer_facebook_url')) : ?>
                        <a href="<?php echo esc_url(get_theme_mod('footer_facebook_url')); ?>"
                           class="social-link facebook" target="_blank" rel="noopener" aria-label="Follow us on Facebook">
                            <span class="social-icon" aria-hidden="true">📘</span>
                            <span class="social-text">Facebook</span>
                        </a>
                        <?php endif; ?>

                        <?php if (get_theme_mod('footer_twitter_url')) : ?>
                        <a href="<?php echo esc_url(get_theme_mod('footer_twitter_url')); ?>"
                           class="social-link twitter" target="_blank" rel="noopener" aria-label="Follow us on Twitter">
                            <span class="social-icon" aria-hidden="true">🐦</span>
                            <span class="social-text">Twitter</span>
                        </a>
                        <?php endif; ?>

                        <?php if (get_theme_mod('footer_youtube_url')) : ?>
                        <a href="<?php echo esc_url(get_theme_mod('footer_youtube_url')); ?>"
                           class="social-link youtube" target="_blank" rel="noopener" aria-label="Subscribe to our YouTube channel">
                            <span class="social-icon" aria-hidden="true">📹</span>
                            <span class="social-text">YouTube</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
}

/**
 * Section 5: Footer Navigation & Legal
 */
function render_footer_legal_section() {
    ?>
    <section class="footer-legal-section" aria-label="Legal Information and Site Navigation">
        <div class="container">
            <nav class="footer-legal-nav" aria-label="Footer Navigation">
                <ul class="legal-links">
                    <li><a href="<?php echo get_theme_mod('footer_privacy_url', '/privacy-policy/'); ?>" class="legal-link">Privacy Policy</a></li>
                    <li><a href="<?php echo get_theme_mod('footer_terms_url', '/terms-of-service/'); ?>" class="legal-link">Terms of Service</a></li>
                    <li><a href="<?php echo get_theme_mod('footer_accessibility_url', '/accessibility/'); ?>" class="legal-link">Accessibility</a></li>
                    <li><a href="<?php echo get_theme_mod('footer_hipaa_url', '/hipaa-compliance/'); ?>" class="legal-link">HIPAA Compliance</a></li>
                </ul>
            </nav>

            <div class="footer-copyright">
                <p class="copyright-text">
                    © <?php echo date('Y'); ?> <?php echo get_theme_mod('footer_copyright_name', get_bloginfo('name')); ?>. All Rights Reserved.
                </p>
                <p class="licensing-text">
                    <?php echo get_theme_mod('footer_licensing_text', 'Licensed Medical Practice | HIPAA Compliant Facility'); ?>
                </p>
            </div>
        </div>
    </section>
    <?php
}
