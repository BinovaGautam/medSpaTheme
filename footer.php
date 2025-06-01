<!-- Footer -->
<footer class="site-footer" role="contentinfo">
    <div class="container">

        <!-- Footer Widgets -->
        <?php if (is_active_sidebar('footer-widgets')) : ?>
            <div class="footer-widgets">
                <?php dynamic_sidebar('footer-widgets'); ?>
            </div>
        <?php endif; ?>

        <!-- Footer Content -->
        <div class="footer-content">

            <!-- Medical Spa Info -->
            <div class="footer-section spa-info">
                <h3 class="footer-title">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <?php bloginfo('name'); ?>
                    </a>
                </h3>

                <?php
                $description = get_bloginfo('description', 'display');
                if ($description) : ?>
                    <p class="spa-description"><?php echo esc_html($description); ?></p>
                <?php endif; ?>

                <?php
                $address = preetidreams_get_address();
                if ($address) : ?>
                    <div class="contact-info">
                        <div class="contact-item">
                            <span class="icon">📍</span>
                            <span class="text"><?php echo nl2br(esc_html($address)); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Contact Information -->
            <div class="footer-section contact-info">
                <h3 class="footer-title"><?php esc_html_e('Contact Us', 'preetidreams'); ?></h3>

                <?php
                $phone = preetidreams_get_phone();
                $email = preetidreams_get_email();
                $hours = preetidreams_get_hours();
                ?>

                <?php if ($phone) : ?>
                    <div class="contact-item">
                        <span class="icon">📞</span>
                        <a href="tel:<?php echo esc_attr($phone); ?>" class="contact-link">
                            <?php echo esc_html($phone); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if ($email) : ?>
                    <div class="contact-item">
                        <span class="icon">✉️</span>
                        <a href="mailto:<?php echo esc_attr($email); ?>" class="contact-link">
                            <?php echo esc_html($email); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if ($hours) : ?>
                    <div class="contact-item">
                        <span class="icon">🕒</span>
                        <span class="text"><?php echo esc_html($hours); ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Quick Links -->
            <div class="footer-section quick-links">
                <h3 class="footer-title"><?php esc_html_e('Services', 'preetidreams'); ?></h3>

                <?php if (has_nav_menu('footer')) : ?>
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'footer',
                        'menu_class'     => 'footer-menu',
                        'container'      => false,
                        'depth'          => 1,
                    ]);
                    ?>
                <?php else : ?>
                    <!-- Fallback menu -->
                    <ul class="footer-menu">
                        <li><a href="<?php echo esc_url(home_url('/treatments/')); ?>"><?php esc_html_e('All Treatments', 'preetidreams'); ?></a></li>
                        <li><a href="<?php echo esc_url(get_post_type_archive_link('staff')); ?>"><?php esc_html_e('Our Specialists', 'preetidreams'); ?></a></li>
                        <li><a href="<?php echo esc_url(get_post_type_archive_link('testimonial')); ?>"><?php esc_html_e('Patient Reviews', 'preetidreams'); ?></a></li>
                        <li><a href="#consultation"><?php esc_html_e('Book Consultation', 'preetidreams'); ?></a></li>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Social Media -->
            <div class="footer-section social-media">
                <h3 class="footer-title"><?php esc_html_e('Follow Us', 'preetidreams'); ?></h3>

                <div class="social-links">
                    <?php
                    $social_platforms = [
                        'facebook' => ['label' => 'Facebook', 'icon' => '📘'],
                        'instagram' => ['label' => 'Instagram', 'icon' => '📷'],
                        'twitter' => ['label' => 'Twitter', 'icon' => '🐦'],
                        'youtube' => ['label' => 'YouTube', 'icon' => '📺'],
                        'linkedin' => ['label' => 'LinkedIn', 'icon' => '💼']
                    ];

                    foreach ($social_platforms as $platform => $data) {
                        $url = preetidreams_get_social_link($platform);
                        if ($url) : ?>
                            <a href="<?php echo esc_url($url); ?>"
                               class="social-link social-<?php echo esc_attr($platform); ?>"
                               target="_blank"
                               rel="noopener noreferrer"
                               aria-label="<?php echo esc_attr(sprintf(__('Follow us on %s', 'preetidreams'), $data['label'])); ?>">
                                <span class="social-icon"><?php echo $data['icon']; ?></span>
                                <span class="social-name"><?php echo esc_html($data['label']); ?></span>
                            </a>
                        <?php endif;
                    }
                    ?>
                </div>

                <!-- Newsletter Signup -->
                <div class="newsletter-signup">
                    <h4><?php esc_html_e('Stay Updated', 'preetidreams'); ?></h4>
                    <p><?php esc_html_e('Get the latest beauty tips and special offers.', 'preetidreams'); ?></p>

                    <form class="newsletter-form" action="#" method="post">
                        <div class="form-group">
                            <label for="newsletter-email" class="sr-only"><?php esc_html_e('Email Address', 'preetidreams'); ?></label>
                            <input type="email"
                                   id="newsletter-email"
                                   name="newsletter_email"
                                   placeholder="<?php esc_attr_e('Your email address', 'preetidreams'); ?>"
                                   required>
                            <button type="submit" class="newsletter-btn">
                                <?php esc_html_e('Subscribe', 'preetidreams'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="site-info">
                <p>
                    &copy; <?php echo date('Y'); ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <?php bloginfo('name'); ?>
                    </a>
                    <?php esc_html_e('All rights reserved.', 'preetidreams'); ?>
                </p>

                <p class="privacy-links">
                    <a href="/privacy-policy"><?php esc_html_e('Privacy Policy', 'preetidreams'); ?></a>
                    <span class="separator">|</span>
                    <a href="/terms-of-service"><?php esc_html_e('Terms of Service', 'preetidreams'); ?></a>
                    <span class="separator">|</span>
                    <a href="/hipaa-notice"><?php esc_html_e('HIPAA Notice', 'preetidreams'); ?></a>
                </p>
            </div>

            <!-- Back to Top -->
            <button class="back-to-top" aria-label="<?php esc_attr_e('Back to top', 'preetidreams'); ?>">
                <span class="icon">⬆️</span>
                <span class="text"><?php esc_html_e('Top', 'preetidreams'); ?></span>
            </button>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
