<?php
/**
 * Footer Map Section Template
 * Sprint: SPRINT-FOOTER-IMPL-001 | Task: T-FOOTER-004
 *
 * Full-width Google Maps integration with Beverly Hills clinic location
 * Floating clinic info overlay with mobile-responsive behavior
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get customizer settings
$enable_map = get_theme_mod('footer_enable_map', true);
$clinic_name = get_theme_mod('footer_clinic_name', 'Beverly Hills Medical Spa');
$clinic_tagline = get_theme_mod('footer_clinic_tagline', 'Visit Our Luxury Medical Spa');
$map_embed = get_theme_mod('footer_map_embed', '');
$address = get_theme_mod('footer_address', '123 Beverly Drive, Beverly Hills, CA 90210');
$phone = get_theme_mod('footer_phone', '(310) 555-0123');
$directions_url = get_theme_mod('footer_directions_url', 'https://maps.google.com');

// Only render if map is enabled
if (!$enable_map) {
    return;
}
?>

<section class="footer-map-section"
         id="footer-map-section"
         role="region"
         aria-labelledby="map-heading"
         data-section="map">

    <div class="map-container-wrapper">

        <!-- Screen Reader Heading -->
        <h3 id="map-heading" class="sr-only">
            <?php esc_html_e('Location and Directions', 'medspatheme'); ?>
        </h3>

        <!-- Map Container -->
        <div class="interactive-map-container"
             id="footer-interactive-map"
             data-map-location="beverly-hills"
             data-clinic-name="<?php echo esc_attr($clinic_name); ?>"
             aria-label="<?php esc_attr_e('Interactive map showing clinic location', 'medspatheme'); ?>">

            <?php if (!empty($map_embed)) : ?>
                <!-- Custom Google Maps Embed -->
                <div class="google-maps-embed"
                     data-embed="custom">
                    <?php
                    // Sanitize and output the embed code
                    echo wp_kses_post($map_embed);
                    ?>
                </div>
            <?php else : ?>
                <!-- Default Google Maps Implementation -->
                <div class="google-maps-default"
                     id="google-maps-default"
                     data-embed="default"
                     data-lat="34.0736"
                     data-lng="-118.4004"
                     data-zoom="15"
                     data-marker-title="<?php echo esc_attr($clinic_name); ?>"
                     data-marker-address="<?php echo esc_attr($address); ?>">

                    <!-- Map Loading State -->
                    <div class="map-loading-state"
                         id="map-loading-state"
                         aria-live="polite"
                         aria-atomic="true">
                        <div class="loading-spinner">
                            <div class="spinner-circle" aria-hidden="true"></div>
                        </div>
                        <p class="loading-text">
                            <?php esc_html_e('Loading interactive map...', 'medspatheme'); ?>
                        </p>
                    </div>

                    <!-- Map Fallback (Static Image) -->
                    <div class="map-fallback"
                         id="map-fallback"
                         style="display: none;"
                         role="img"
                         aria-label="<?php esc_attr_e('Static map image of clinic location', 'medspatheme'); ?>">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/static-map-beverly-hills.jpg'); ?>"
                             alt="<?php esc_attr_e('Map showing Beverly Hills clinic location', 'medspatheme'); ?>"
                             loading="lazy"
                             width="1200"
                             height="400">
                    </div>

                </div>
            <?php endif; ?>

            <!-- Floating Clinic Info Overlay -->
            <div class="clinic-info-overlay"
                 id="clinic-info-overlay"
                 role="dialog"
                 aria-labelledby="clinic-overlay-title"
                 data-overlay="clinic-info">

                <div class="overlay-content">

                    <!-- Close Button -->
                    <button class="overlay-close"
                            id="overlay-close-btn"
                            type="button"
                            aria-label="<?php esc_attr_e('Close clinic information', 'medspatheme'); ?>"
                            data-action="close-overlay">
                        <span class="close-icon" aria-hidden="true">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </button>

                    <!-- Clinic Information -->
                    <div class="clinic-info-content">

                        <h4 id="clinic-overlay-title" class="clinic-name">
                            <?php echo esc_html($clinic_name); ?>
                        </h4>

                        <p class="clinic-tagline">
                            <?php echo esc_html($clinic_tagline); ?>
                        </p>

                        <div class="clinic-details">

                            <!-- Address -->
                            <div class="clinic-address" data-info="address">
                                <div class="info-icon" aria-hidden="true">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M21 10C21 17 12 23 12 23S3 17 3 10C3 5.02944 7.02944 1 12 1C16.9706 1 21 5.02944 21 10Z" stroke="currentColor" stroke-width="2"/>
                                        <circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <span class="info-label sr-only">
                                        <?php esc_html_e('Address:', 'medspatheme'); ?>
                                    </span>
                                    <address class="address-text">
                                        <?php echo wp_kses_post(nl2br($address)); ?>
                                    </address>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="clinic-phone" data-info="phone">
                                <div class="info-icon" aria-hidden="true">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M22 16.92V19.92C22 20.52 21.52 21 20.92 21C10.93 21 3 13.07 3 3.08C3 2.48 3.48 2 4.08 2H7.09C7.69 2 8.17 2.48 8.17 3.08C8.17 4.43 8.44 5.72 8.94 6.9C9.1 7.3 8.99 7.77 8.65 8.11L6.74 10.02C8.38 13.29 10.71 15.62 13.98 17.26L15.89 15.35C16.23 15.01 16.7 14.9 17.1 15.06C18.28 15.56 19.57 15.83 20.92 15.83C21.52 15.83 22 16.31 22 16.92Z" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <span class="info-label sr-only">
                                        <?php esc_html_e('Phone:', 'medspatheme'); ?>
                                    </span>
                                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>"
                                       class="phone-link"
                                       data-analytics="phone-call-footer-map">
                                        <?php echo esc_html($phone); ?>
                                    </a>
                                </div>
                            </div>

                        </div>

                        <!-- Action Buttons -->
                        <div class="clinic-actions">

                            <a href="<?php echo esc_url($directions_url); ?>"
                               class="directions-btn btn-secondary"
                               target="_blank"
                               rel="noopener noreferrer"
                               data-analytics="directions-click-footer-map"
                               aria-describedby="directions-description">
                                <span class="btn-icon" aria-hidden="true">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 11L22 2L13 21L11 13L3 11Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <?php esc_html_e('Get Directions', 'medspatheme'); ?>
                                <span id="directions-description" class="sr-only">
                                    <?php esc_html_e('Opens Google Maps in new window', 'medspatheme'); ?>
                                </span>
                            </a>

                            <a href="/contact/"
                               class="contact-btn btn-primary"
                               data-analytics="contact-click-footer-map">
                                <span class="btn-icon" aria-hidden="true">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 10.5H16M8 14.5H13M6 20.5H18C19.1046 20.5 20 19.6046 20 18.5V6.5C20 5.39543 19.1046 4.5 18 4.5H6C4.89543 4.5 4 5.39543 4 6.5V18.5C4 19.6046 4.89543 20.5 6 20.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </span>
                                <?php esc_html_e('Contact Us', 'medspatheme'); ?>
                            </a>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Map Controls -->
            <div class="map-controls"
                 id="map-controls"
                 role="toolbar"
                 aria-label="<?php esc_attr_e('Map controls', 'medspatheme'); ?>">

                <button class="map-control-btn info-toggle"
                        id="info-toggle-btn"
                        type="button"
                        aria-pressed="false"
                        aria-describedby="info-toggle-description"
                        data-action="toggle-overlay">
                    <span class="btn-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                            <path d="M12 16V12M12 8H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <span class="btn-text">
                        <?php esc_html_e('Clinic Info', 'medspatheme'); ?>
                    </span>
                    <span id="info-toggle-description" class="sr-only">
                        <?php esc_html_e('Toggle clinic information overlay', 'medspatheme'); ?>
                    </span>
                </button>

                <button class="map-control-btn fullscreen-toggle"
                        id="fullscreen-toggle-btn"
                        type="button"
                        aria-pressed="false"
                        aria-describedby="fullscreen-toggle-description"
                        data-action="toggle-fullscreen">
                    <span class="btn-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 3H5C3.89543 3 3 3.89543 3 5V8M21 8V5C21 3.89543 20.1046 3 19 3H16M16 21H19C20.1046 21 21 20.1046 21 19V16M8 21H5C3.89543 21 3 20.1046 3 19V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span class="btn-text">
                        <?php esc_html_e('Fullscreen', 'medspatheme'); ?>
                    </span>
                    <span id="fullscreen-toggle-description" class="sr-only">
                        <?php esc_html_e('Toggle fullscreen map view', 'medspatheme'); ?>
                    </span>
                </button>

            </div>

        </div>

        <!-- Mobile Touch Instructions -->
        <div class="mobile-touch-hint"
             id="mobile-touch-hint"
             aria-live="polite"
             data-mobile-only="true">
            <p class="touch-instruction">
                <span class="touch-icon" aria-hidden="true">👆</span>
                <?php esc_html_e('Tap the map to interact', 'medspatheme'); ?>
            </p>
        </div>

    </div>

</section>

<?php
// Nonce for AJAX interactions
wp_nonce_field('footer_map_nonce', 'footer_map_nonce_field');
?>
