<!-- Luxury Medical Spa Footer -->
<footer class="luxury-footer" role="contentinfo">

    <!-- Consultation Invitation Section -->
    <section class="consultation-invitation">
        <div class="invitation-content">
            <h2 class="invitation-headline">
                <?php echo esc_html(get_theme_mod('footer_consultation_headline', 'Ready to Begin Your Aesthetic Journey?')); ?>
            </h2>
            <p class="invitation-subtext">
                <?php echo esc_html(get_theme_mod('footer_consultation_subtext', 'Experience the artistry of medical aesthetics with Dr. Preeti Sharma in our luxury clinic')); ?>
            </p>

            <div class="cta-group" role="group" aria-label="<?php esc_attr_e('Consultation booking options', 'preetidreams'); ?>">
                <button class="cta-primary" onclick="window.open('<?php echo esc_url(get_theme_mod('footer_consultation_link', '/contact')); ?>', '_self')">
                    <span class="cta-icon" aria-hidden="true">📅</span>
                    <span><?php esc_html_e('Schedule Your In-Clinic Consultation', 'preetidreams'); ?></span>
                </button>
                <button class="cta-secondary" onclick="window.open('tel:<?php echo esc_attr(preetidreams_get_phone()); ?>', '_self')">
                    <span class="cta-icon" aria-hidden="true">📞</span>
                    <span><?php esc_html_e('Call Our Practice', 'preetidreams'); ?></span>
                </button>
            </div>

            <div class="trust-indicators" role="list">
                <span class="indicator" role="listitem">
                    <span aria-hidden="true">🔒</span>
                    <span><?php esc_html_e('Completely Confidential', 'preetidreams'); ?></span>
                </span>
                <span class="indicator" role="listitem">
                    <span aria-hidden="true">✨</span>
                    <span><?php esc_html_e('Complimentary In-Clinic Consultation', 'preetidreams'); ?></span>
                </span>
                <span class="indicator" role="listitem">
                    <span aria-hidden="true">🏥</span>
                    <span><?php esc_html_e('Board-Certified Excellence', 'preetidreams'); ?></span>
                </span>
            </div>
        </div>
    </section>

    <!-- Practice Information Section -->
    <section class="practice-information">
        <div class="info-grid">

            <!-- Contact Information -->
            <div class="info-column">
                <h3 class="column-title"><?php esc_html_e('Practice Information', 'preetidreams'); ?></h3>
                <div class="contact-details">

                    <?php $address = preetidreams_get_address(); if ($address) : ?>
                        <div class="contact-item">
                        <span class="icon" aria-hidden="true">📍</span>
                        <div class="details">
                            <strong><?php echo wp_kses_post(nl2br(esc_html($address))); ?></strong>
                            <span><?php echo esc_html(get_theme_mod('footer_address_line2', '')); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                    <?php $phone = preetidreams_get_phone(); if ($phone) : ?>
                    <div class="contact-item">
                        <span class="icon" aria-hidden="true">📞</span>
                        <div class="details">
                            <strong>
                        <a href="tel:<?php echo esc_attr($phone); ?>" class="contact-link">
                            <?php echo esc_html($phone); ?>
                        </a>
                            </strong>
                            <span><?php esc_html_e('Direct Practice Line', 'preetidreams'); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                    <?php $hours = preetidreams_get_hours(); if ($hours) : ?>
                    <div class="contact-item">
                        <span class="icon" aria-hidden="true">🕒</span>
                        <div class="details">
                            <strong><?php esc_html_e('Consultation Hours', 'preetidreams'); ?></strong>
                            <span><?php echo wp_kses_post(nl2br(esc_html($hours))); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>

            <!-- Medical Excellence -->
            <div class="info-column">
                <h3 class="column-title"><?php esc_html_e('Medical Excellence', 'preetidreams'); ?></h3>
                <div class="credentials-list">

                    <div class="credential-item">
                        <span class="icon" aria-hidden="true">🏥</span>
                        <div class="credential-text">
                            <strong><?php echo esc_html(get_theme_mod('footer_board_certification', 'Board-Certified Physician')); ?></strong>
                            <span><?php echo esc_html(get_theme_mod('footer_certification_details', 'American Board of Dermatology')); ?></span>
                        </div>
                    </div>

                    <div class="credential-item">
                        <span class="icon" aria-hidden="true">⭐</span>
                        <div class="credential-text">
                            <strong><?php echo esc_html(get_theme_mod('footer_experience_years', '15+ Years of Excellence')); ?></strong>
                            <span><?php echo esc_html(get_theme_mod('footer_expertise_area', 'Aesthetic Medicine Expertise')); ?></span>
                        </div>
                    </div>

                    <div class="credential-item">
                        <span class="icon" aria-hidden="true">🏆</span>
                        <div class="credential-text">
                            <strong><?php echo esc_html(get_theme_mod('footer_recognition', 'Recognition & Awards')); ?></strong>
                            <span><?php echo esc_html(get_theme_mod('footer_award_details', 'Top Medical Spa - Beverly Hills')); ?></span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Luxury Clinic Experience -->
            <div class="info-column">
                <h3 class="column-title"><?php esc_html_e('Luxury Clinic Experience', 'preetidreams'); ?></h3>
                <div class="clinic-experience">

                    <div class="experience-item">
                        <span class="icon" aria-hidden="true">🏛️</span>
                        <div class="experience-text">
                            <strong><?php echo esc_html(get_theme_mod('footer_private_suites', 'Private Consultation Suites')); ?></strong>
                            <span><?php echo esc_html(get_theme_mod('footer_suite_description', 'Discrete, comfortable consultation rooms')); ?></span>
                        </div>
                    </div>

                    <div class="experience-item">
                        <span class="icon" aria-hidden="true">🔬</span>
                        <div class="experience-text">
                            <strong><?php echo esc_html(get_theme_mod('footer_equipment', 'Advanced Medical Equipment')); ?></strong>
                            <span><?php echo esc_html(get_theme_mod('footer_equipment_description', 'State-of-the-art diagnostic and treatment tools')); ?></span>
                        </div>
                    </div>

                    <div class="experience-item">
                        <span class="icon" aria-hidden="true">✨</span>
                        <div class="experience-text">
                            <strong><?php echo esc_html(get_theme_mod('footer_personalized_planning', 'Personalized Treatment Planning')); ?></strong>
                            <span><?php echo esc_html(get_theme_mod('footer_planning_description', 'Custom aesthetic plans during your visit')); ?></span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Connect With Us -->
            <div class="info-column">
                <h3 class="column-title"><?php esc_html_e('Connect With Us', 'preetidreams'); ?></h3>
                <div class="social-professional">

                    <?php $email = preetidreams_get_email(); if ($email) : ?>
                    <div class="social-item">
                        <span class="icon" aria-hidden="true">📧</span>
                        <div class="social-text">
                            <strong>
                        <a href="mailto:<?php echo esc_attr($email); ?>" class="contact-link">
                            <?php echo esc_html($email); ?>
                        </a>
                            </strong>
                            <span><?php esc_html_e('Confidential inquiries welcomed', 'preetidreams'); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                    <div class="social-links">
                        <?php $linkedin = preetidreams_get_social_link('linkedin'); if ($linkedin) : ?>
                        <a href="<?php echo esc_url($linkedin); ?>" class="social-link" aria-label="<?php esc_attr_e('Professional LinkedIn Profile', 'preetidreams'); ?>" target="_blank" rel="noopener noreferrer">
                            <span class="icon" aria-hidden="true">💼</span>
                            <span><?php esc_html_e('Professional Profile', 'preetidreams'); ?></span>
                        </a>
                        <?php endif; ?>

                        <?php if (get_theme_mod('footer_educational_resources_link')) : ?>
                        <a href="<?php echo esc_url(get_theme_mod('footer_educational_resources_link', '#')); ?>" class="social-link" aria-label="<?php esc_attr_e('Educational Content and Resources', 'preetidreams'); ?>">
                            <span class="icon" aria-hidden="true">📚</span>
                            <span><?php esc_html_e('Educational Resources', 'preetidreams'); ?></span>
                        </a>
                        <?php endif; ?>
                    </div>

                    <div class="location-link">
                        <a href="<?php echo esc_url(get_theme_mod('footer_directions_link', 'https://maps.google.com')); ?>" class="location-cta" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Get directions to our clinic', 'preetidreams'); ?>">
                            <span class="icon" aria-hidden="true">📍</span>
                            <span><?php esc_html_e('Get Directions to Our Clinic', 'preetidreams'); ?></span>
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- Luxury Location Showcase -->
    <section class="luxury-location-showcase hidden" role="region" aria-label="<?php esc_attr_e('Clinic Location and Experience', 'preetidreams'); ?>">
        <div class="location-container">

            <!-- Location Invitation -->
            <div class="location-invitation">
                <h2 class="location-headline">
                    <?php echo esc_html(get_theme_mod('footer_location_headline', 'Experience Our Luxury Medical Spa')); ?>
                </h2>
                <p class="location-subtext">
                    <?php echo esc_html(get_theme_mod('footer_location_description', 'Discover our state-of-the-art facility designed for your comfort and privacy. Schedule your personalized consultation in our sophisticated Beverly Hills location.')); ?>
                </p>
            </div>

            <!-- Interactive Map & Location Details -->
            <div class="location-experience">

                <!-- Premium Map Display -->
                <div class="map-showcase">
                    <div class="map-container" id="luxury-clinic-map">

                        <!-- Enhanced Google Maps Integration -->
                        <div class="google-map-wrapper">
                            <iframe
                                src="<?php echo esc_url(get_theme_mod('footer_map_embed_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3305.2069!2d-118.3974881!3d34.0738!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzTCsDA0JzI1LjciTiAxMTjCsDIzJzUxLjAiVw!5e0!3m2!1sen!2sus!4v1234567890')); ?>"
                                width="100%"
                                height="400"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                title="<?php esc_attr_e('PreetiDreams Medical Spa Location', 'preetidreams'); ?>"
                                aria-label="<?php esc_attr_e('Interactive map showing clinic location', 'preetidreams'); ?>">
                            </iframe>
                        </div>

                        <!-- Map Overlay with Luxury Branding -->
                        <div class="map-overlay">
                            <div class="clinic-marker">
                                <span class="marker-icon" aria-hidden="true">📍</span>
                                <div class="marker-details">
                                    <strong class="clinic-name"><?php bloginfo('name'); ?></strong>
                                    <span class="clinic-tagline"><?php echo esc_html(get_theme_mod('footer_clinic_tagline', 'Beverly Hills Medical Spa')); ?></span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Location Experience Details -->
                <div class="location-experience-details">

                    <!-- Premium Location Features -->
                    <div class="location-features">
                        <h3 class="features-title"><?php esc_html_e('What Makes Our Location Special', 'preetidreams'); ?></h3>

                        <div class="feature-grid">
                            <div class="feature-item">
                                <span class="feature-icon" aria-hidden="true">🏛️</span>
                                <div class="feature-content">
                                    <strong><?php echo esc_html(get_theme_mod('footer_location_feature_1', 'Prime Beverly Hills Address')); ?></strong>
                                    <span><?php echo esc_html(get_theme_mod('footer_location_feature_1_desc', 'Prestigious Rodeo Drive vicinity')); ?></span>
                                </div>
                            </div>

                            <div class="feature-item">
                                <span class="feature-icon" aria-hidden="true">🚗</span>
                                <div class="feature-content">
                                    <strong><?php echo esc_html(get_theme_mod('footer_location_feature_2', 'Valet Parking Available')); ?></strong>
                                    <span><?php echo esc_html(get_theme_mod('footer_location_feature_2_desc', 'Complimentary for all appointments')); ?></span>
                                </div>
                            </div>

                            <div class="feature-item">
                                <span class="feature-icon" aria-hidden="true">🔒</span>
                                <div class="feature-content">
                                    <strong><?php echo esc_html(get_theme_mod('footer_location_feature_3', 'Private Entrance')); ?></strong>
                                    <span><?php echo esc_html(get_theme_mod('footer_location_feature_3_desc', 'Discrete and confidential access')); ?></span>
                                </div>
                            </div>

                            <div class="feature-item">
                                <span class="feature-icon" aria-hidden="true">✨</span>
                                <div class="feature-content">
                                    <strong><?php echo esc_html(get_theme_mod('footer_location_feature_4', 'Luxury Amenities')); ?></strong>
                                    <span><?php echo esc_html(get_theme_mod('footer_location_feature_4_desc', 'Curated comfort experience')); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Consultation Invitation CTAs -->
                    <div class="location-consultation-ctas">
                        <h3 class="cta-title"><?php esc_html_e('Visit Our Clinic', 'preetidreams'); ?></h3>

                        <div class="location-cta-group">
                            <a href="<?php echo esc_url(get_theme_mod('footer_book_consultation_link', '/contact/#consultation')); ?>"
                               class="location-cta-primary"
                               aria-label="<?php esc_attr_e('Schedule consultation at our clinic', 'preetidreams'); ?>">
                                <span class="cta-icon" aria-hidden="true">📅</span>
                                <span><?php esc_html_e('Schedule Clinic Visit', 'preetidreams'); ?></span>
                            </a>

                            <a href="<?php echo esc_url(get_theme_mod('footer_virtual_tour_link', '#virtual-tour')); ?>"
                               class="location-cta-secondary"
                               aria-label="<?php esc_attr_e('Take virtual tour of our facilities', 'preetidreams'); ?>">
                                <span class="cta-icon" aria-hidden="true">👁️</span>
                                <span><?php esc_html_e('Virtual Facility Tour', 'preetidreams'); ?></span>
                            </a>

                            <a href="<?php echo esc_url(get_theme_mod('footer_directions_link', 'https://maps.google.com')); ?>"
                               class="location-cta-tertiary"
                               target="_blank"
                               rel="noopener noreferrer"
                               aria-label="<?php esc_attr_e('Get driving directions to clinic', 'preetidreams'); ?>">
                                <span class="cta-icon" aria-hidden="true">🗺️</span>
                                <span><?php esc_html_e('Get Directions', 'preetidreams'); ?></span>
                            </a>
                        </div>
                    </div>

                    <!-- Location Contact Summary -->
                    <div class="location-contact-summary">
                        <div class="contact-quick-access">
                            <?php $address = preetidreams_get_address(); if ($address) : ?>
                            <div class="quick-contact-item">
                                <span class="contact-label"><?php esc_html_e('Address:', 'preetidreams'); ?></span>
                                <span class="contact-value"><?php echo esc_html($address); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php $phone = preetidreams_get_phone(); if ($phone) : ?>
                            <div class="quick-contact-item">
                                <span class="contact-label"><?php esc_html_e('Direct Line:', 'preetidreams'); ?></span>
                                <a href="tel:<?php echo esc_attr($phone); ?>" class="contact-value contact-link">
                                    <?php echo esc_html($phone); ?>
                                </a>
                            </div>
                            <?php endif; ?>

                            <?php $hours = preetidreams_get_hours(); if ($hours) : ?>
                            <div class="quick-contact-item">
                                <span class="contact-label"><?php esc_html_e('Hours:', 'preetidreams'); ?></span>
                                <span class="contact-value"><?php echo esc_html($hours); ?></span>
                    </div>
                <?php endif; ?>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>

    <!-- Footer Navigation & Legal -->
    <section class="footer-bottom ">
        <div class="footer-navigation">

            <!-- Quick Links -->
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
            </div>

        </div>

        <!-- Back to Top -->
        <button class="back-to-top" aria-label="<?php esc_attr_e('Scroll back to top', 'preetidreams'); ?>" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
            <span class="icon" aria-hidden="true">↑</span>
            <span class="sr-only"><?php esc_html_e('Back to top', 'preetidreams'); ?></span>
        </button>

    </section>

</footer>

<?php wp_footer(); ?>

</body>
</html>
