<?php
/**
 * Footer Customizer Integration
 * Sprint: SPRINT-FOOTER-IMPL-001 | Task: T-FOOTER-003
 *
 * WordPress Customizer controls for luxury medical spa footer
 * 50+ controls with real-time preview and settings persistence
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Footer Customizer Class
 *
 * Manages all footer-related customizer controls with proper categorization
 */
class Footer_Customizer {

    /**
     * Initialize the customizer
     */
    public function __construct() {
        add_action('customize_register', array($this, 'register_footer_customizer'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_customizer_styles'));
    }

    /**
     * Register Footer Customizer Controls
     *
     * @param WP_Customize_Manager $wp_customize
     */
    public function register_footer_customizer($wp_customize) {

        // Add Footer Panel
        $wp_customize->add_panel('footer_panel', array(
            'title'       => __('Footer Settings', 'medspatheme'),
            'description' => __('Customize your luxury medical spa footer appearance and content', 'medspatheme'),
            'priority'    => 160,
            'capability'  => 'edit_theme_options'
        ));

        // Register all footer sections
        $this->register_hero_section($wp_customize);
        $this->register_contact_section($wp_customize);
        $this->register_services_section($wp_customize);
        $this->register_hours_section($wp_customize);
        $this->register_doctor_section($wp_customize);
        $this->register_map_section($wp_customize);
        $this->register_newsletter_section($wp_customize);
        $this->register_social_section($wp_customize);
        $this->register_legal_section($wp_customize);
        $this->register_styling_section($wp_customize);
    }

    /**
     * Section 1: Hero CTA Section Controls
     */
    private function register_hero_section($wp_customize) {

        // Hero Section
        $wp_customize->add_section('footer_hero_section', array(
            'title'    => __('Hero Call-to-Action', 'medspatheme'),
            'priority' => 10,
            'panel'    => 'footer_panel'
        ));

        // Hero Title
        $wp_customize->add_setting('footer_hero_title', array(
            'default'           => 'Ready to Transform Your Wellness Journey?',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_hero_title', array(
            'label'   => __('Hero Title', 'medspatheme'),
            'section' => 'footer_hero_section',
            'type'    => 'text'
        ));

        // Hero Subtitle
        $wp_customize->add_setting('footer_hero_subtitle', array(
            'default'           => 'Experience luxury medical spa treatments with personalized care and proven results',
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_hero_subtitle', array(
            'label'   => __('Hero Subtitle', 'medspatheme'),
            'section' => 'footer_hero_section',
            'type'    => 'textarea'
        ));

        // Primary CTA Text
        $wp_customize->add_setting('footer_primary_cta_text', array(
            'default'           => 'Schedule Consultation',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_primary_cta_text', array(
            'label'   => __('Primary CTA Text', 'medspatheme'),
            'section' => 'footer_hero_section',
            'type'    => 'text'
        ));

        // Primary CTA URL
        $wp_customize->add_setting('footer_primary_cta_url', array(
            'default'           => '/contact/',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('footer_primary_cta_url', array(
            'label'   => __('Primary CTA URL', 'medspatheme'),
            'section' => 'footer_hero_section',
            'type'    => 'url'
        ));

        // Secondary CTA Text
        $wp_customize->add_setting('footer_secondary_cta_text', array(
            'default'           => 'View Services',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_secondary_cta_text', array(
            'label'   => __('Secondary CTA Text', 'medspatheme'),
            'section' => 'footer_hero_section',
            'type'    => 'text'
        ));

        // Secondary CTA URL
        $wp_customize->add_setting('footer_secondary_cta_url', array(
            'default'           => '/treatments/',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('footer_secondary_cta_url', array(
            'label'   => __('Secondary CTA URL', 'medspatheme'),
            'section' => 'footer_hero_section',
            'type'    => 'url'
        ));

        // Badge 1 Text
        $wp_customize->add_setting('footer_badge_1', array(
            'default'           => 'Board Certified',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_badge_1', array(
            'label'   => __('Badge 1 Text', 'medspatheme'),
            'section' => 'footer_hero_section',
            'type'    => 'text'
        ));

        // Badge 2 Text
        $wp_customize->add_setting('footer_badge_2', array(
            'default'           => 'Award Winning',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_badge_2', array(
            'label'   => __('Badge 2 Text', 'medspatheme'),
            'section' => 'footer_hero_section',
            'type'    => 'text'
        ));

        // Badge 3 Text
        $wp_customize->add_setting('footer_badge_3', array(
            'default'           => '5-Star Reviews',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_badge_3', array(
            'label'   => __('Badge 3 Text', 'medspatheme'),
            'section' => 'footer_hero_section',
            'type'    => 'text'
        ));
    }

    /**
     * Section 2: Contact Information Controls
     */
    private function register_contact_section($wp_customize) {

        // Contact Section
        $wp_customize->add_section('footer_contact_section', array(
            'title'    => __('Contact Information', 'medspatheme'),
            'priority' => 20,
            'panel'    => 'footer_panel'
        ));

        // Phone Number
        $wp_customize->add_setting('footer_phone', array(
            'default'           => '(310) 555-0123',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_phone', array(
            'label'   => __('Phone Number', 'medspatheme'),
            'section' => 'footer_contact_section',
            'type'    => 'tel'
        ));

        // Email Address
        $wp_customize->add_setting('footer_email', array(
            'default'           => 'info@medspaa.com',
            'sanitize_callback' => 'sanitize_email',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_email', array(
            'label'   => __('Email Address', 'medspatheme'),
            'section' => 'footer_contact_section',
            'type'    => 'email'
        ));

        // Street Address
        $wp_customize->add_setting('footer_address', array(
            'default'           => '123 Beverly Drive, Beverly Hills, CA 90210',
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_address', array(
            'label'   => __('Street Address', 'medspatheme'),
            'section' => 'footer_contact_section',
            'type'    => 'textarea'
        ));

        // Directions URL
        $wp_customize->add_setting('footer_directions_url', array(
            'default'           => 'https://maps.google.com',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('footer_directions_url', array(
            'label'   => __('Google Maps Directions URL', 'medspatheme'),
            'section' => 'footer_contact_section',
            'type'    => 'url'
        ));
    }

    /**
     * Section 3: Services Section Controls
     */
    private function register_services_section($wp_customize) {

        // Services Section
        $wp_customize->add_section('footer_services_section', array(
            'title'    => __('Our Services', 'medspatheme'),
            'priority' => 30,
            'panel'    => 'footer_panel'
        ));

        // Service 1
        $wp_customize->add_setting('footer_service_1', array(
            'default'           => 'Luxury Facials',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_service_1', array(
            'label'   => __('Service 1 Name', 'medspatheme'),
            'section' => 'footer_services_section',
            'type'    => 'text'
        ));

        $wp_customize->add_setting('footer_service_1_url', array(
            'default'           => '/treatments/facials/',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('footer_service_1_url', array(
            'label'   => __('Service 1 URL', 'medspatheme'),
            'section' => 'footer_services_section',
            'type'    => 'url'
        ));

        // Service 2
        $wp_customize->add_setting('footer_service_2', array(
            'default'           => 'Botox & Fillers',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_service_2', array(
            'label'   => __('Service 2 Name', 'medspatheme'),
            'section' => 'footer_services_section',
            'type'    => 'text'
        ));

        $wp_customize->add_setting('footer_service_2_url', array(
            'default'           => '/treatments/botox/',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('footer_service_2_url', array(
            'label'   => __('Service 2 URL', 'medspatheme'),
            'section' => 'footer_services_section',
            'type'    => 'url'
        ));

        // Service 3
        $wp_customize->add_setting('footer_service_3', array(
            'default'           => 'Laser Treatments',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_service_3', array(
            'label'   => __('Service 3 Name', 'medspatheme'),
            'section' => 'footer_services_section',
            'type'    => 'text'
        ));

        $wp_customize->add_setting('footer_service_3_url', array(
            'default'           => '/treatments/laser/',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('footer_service_3_url', array(
            'label'   => __('Service 3 URL', 'medspatheme'),
            'section' => 'footer_services_section',
            'type'    => 'url'
        ));

        // Service 4
        $wp_customize->add_setting('footer_service_4', array(
            'default'           => 'Therapeutic Massage',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_service_4', array(
            'label'   => __('Service 4 Name', 'medspatheme'),
            'section' => 'footer_services_section',
            'type'    => 'text'
        ));

        $wp_customize->add_setting('footer_service_4_url', array(
            'default'           => '/treatments/massage/',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('footer_service_4_url', array(
            'label'   => __('Service 4 URL', 'medspatheme'),
            'section' => 'footer_services_section',
            'type'    => 'url'
        ));
    }

    /**
     * Section 4: Hours Section Controls
     */
    private function register_hours_section($wp_customize) {

        // Hours Section
        $wp_customize->add_section('footer_hours_section', array(
            'title'    => __('Business Hours', 'medspatheme'),
            'priority' => 40,
            'panel'    => 'footer_panel'
        ));

        // Weekday Hours
        $wp_customize->add_setting('footer_hours_weekday', array(
            'default'           => '9:00 AM - 6:00 PM',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_hours_weekday', array(
            'label'   => __('Monday - Friday Hours', 'medspatheme'),
            'section' => 'footer_hours_section',
            'type'    => 'text'
        ));

        // Saturday Hours
        $wp_customize->add_setting('footer_hours_saturday', array(
            'default'           => '10:00 AM - 4:00 PM',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_hours_saturday', array(
            'label'   => __('Saturday Hours', 'medspatheme'),
            'section' => 'footer_hours_section',
            'type'    => 'text'
        ));

        // Sunday Hours
        $wp_customize->add_setting('footer_hours_sunday', array(
            'default'           => 'By Appointment',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_hours_sunday', array(
            'label'   => __('Sunday Hours', 'medspatheme'),
            'section' => 'footer_hours_section',
            'type'    => 'text'
        ));

        // Hours Note
        $wp_customize->add_setting('footer_hours_note', array(
            'default'           => 'Emergency consultations available 24/7',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_hours_note', array(
            'label'   => __('Hours Note', 'medspatheme'),
            'section' => 'footer_hours_section',
            'type'    => 'text'
        ));
    }

    /**
     * Section 5: Doctor/About Section Controls
     */
    private function register_doctor_section($wp_customize) {

        // Doctor Section
        $wp_customize->add_section('footer_doctor_section', array(
            'title'    => __('About Us / Doctor Info', 'medspatheme'),
            'priority' => 50,
            'panel'    => 'footer_panel'
        ));

        // Doctor Name
        $wp_customize->add_setting('footer_doctor_name', array(
            'default'           => 'Dr. Preeti Sharma',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_doctor_name', array(
            'label'   => __('Doctor Name', 'medspatheme'),
            'section' => 'footer_doctor_section',
            'type'    => 'text'
        ));

        // Doctor Credentials
        $wp_customize->add_setting('footer_doctor_credentials', array(
            'default'           => 'Board Certified Physician',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_doctor_credentials', array(
            'label'   => __('Doctor Credentials', 'medspatheme'),
            'section' => 'footer_doctor_section',
            'type'    => 'text'
        ));

        // Doctor Bio
        $wp_customize->add_setting('footer_doctor_bio', array(
            'default'           => 'Specializing in luxury medical spa treatments with over 15 years of experience.',
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_doctor_bio', array(
            'label'   => __('Doctor Bio', 'medspatheme'),
            'section' => 'footer_doctor_section',
            'type'    => 'textarea'
        ));

        // Doctor Image
        $wp_customize->add_setting('footer_doctor_image', array(
            'default'           => get_template_directory_uri() . '/assets/images/default-doctor.jpg',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'footer_doctor_image', array(
            'label'   => __('Doctor Photo', 'medspatheme'),
            'section' => 'footer_doctor_section'
        )));
    }

    /**
     * Section 6: Map Section Controls
     */
    private function register_map_section($wp_customize) {

        // Map Section
        $wp_customize->add_section('footer_map_section', array(
            'title'    => __('Map & Location', 'medspatheme'),
            'priority' => 60,
            'panel'    => 'footer_panel'
        ));

        // Clinic Name
        $wp_customize->add_setting('footer_clinic_name', array(
            'default'           => 'Beverly Hills Medical Spa',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_clinic_name', array(
            'label'   => __('Clinic Name', 'medspatheme'),
            'section' => 'footer_map_section',
            'type'    => 'text'
        ));

        // Clinic Tagline
        $wp_customize->add_setting('footer_clinic_tagline', array(
            'default'           => 'Visit Our Luxury Medical Spa',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_clinic_tagline', array(
            'label'   => __('Clinic Tagline', 'medspatheme'),
            'section' => 'footer_map_section',
            'type'    => 'text'
        ));

        // Google Maps Embed Code
        $wp_customize->add_setting('footer_map_embed', array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('footer_map_embed', array(
            'label'       => __('Google Maps Embed Code', 'medspatheme'),
            'description' => __('Paste your Google Maps embed iframe code here', 'medspatheme'),
            'section'     => 'footer_map_section',
            'type'        => 'textarea'
        ));
    }

    /**
     * Section 7: Newsletter Section Controls
     */
    private function register_newsletter_section($wp_customize) {

        // Newsletter Section
        $wp_customize->add_section('footer_newsletter_section', array(
            'title'    => __('Newsletter & Subscription', 'medspatheme'),
            'priority' => 70,
            'panel'    => 'footer_panel'
        ));

        // Newsletter Title
        $wp_customize->add_setting('footer_newsletter_title', array(
            'default'           => 'Stay Connected with Exclusive Wellness Tips',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_newsletter_title', array(
            'label'   => __('Newsletter Title', 'medspatheme'),
            'section' => 'footer_newsletter_section',
            'type'    => 'text'
        ));

        // Newsletter Subtitle
        $wp_customize->add_setting('footer_newsletter_subtitle', array(
            'default'           => 'Get the latest beauty and wellness insights delivered to your inbox',
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_newsletter_subtitle', array(
            'label'   => __('Newsletter Subtitle', 'medspatheme'),
            'section' => 'footer_newsletter_section',
            'type'    => 'textarea'
        ));

        // Newsletter Privacy Text
        $wp_customize->add_setting('footer_newsletter_privacy', array(
            'default'           => 'We respect your privacy. Unsubscribe at any time.',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_newsletter_privacy', array(
            'label'   => __('Privacy Statement', 'medspatheme'),
            'section' => 'footer_newsletter_section',
            'type'    => 'text'
        ));
    }

    /**
     * Section 8: Social Media Controls
     */
    private function register_social_section($wp_customize) {

        // Social Section
        $wp_customize->add_section('footer_social_section', array(
            'title'    => __('Social Media Links', 'medspatheme'),
            'priority' => 80,
            'panel'    => 'footer_panel'
        ));

        // Instagram URL
        $wp_customize->add_setting('footer_instagram_url', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('footer_instagram_url', array(
            'label'   => __('Instagram URL', 'medspatheme'),
            'section' => 'footer_social_section',
            'type'    => 'url'
        ));

        // Facebook URL
        $wp_customize->add_setting('footer_facebook_url', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('footer_facebook_url', array(
            'label'   => __('Facebook URL', 'medspatheme'),
            'section' => 'footer_social_section',
            'type'    => 'url'
        ));

        // Twitter URL
        $wp_customize->add_setting('footer_twitter_url', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('footer_twitter_url', array(
            'label'   => __('Twitter URL', 'medspatheme'),
            'section' => 'footer_social_section',
            'type'    => 'url'
        ));

        // YouTube URL
        $wp_customize->add_setting('footer_youtube_url', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('footer_youtube_url', array(
            'label'   => __('YouTube URL', 'medspatheme'),
            'section' => 'footer_social_section',
            'type'    => 'url'
        ));

        // LinkedIn URL
        $wp_customize->add_setting('footer_linkedin_url', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('footer_linkedin_url', array(
            'label'   => __('LinkedIn URL', 'medspatheme'),
            'section' => 'footer_social_section',
            'type'    => 'url'
        ));
    }

    /**
     * Section 9: Legal Section Controls
     */
    private function register_legal_section($wp_customize) {

        // Legal Section
        $wp_customize->add_section('footer_legal_section', array(
            'title'    => __('Legal & Navigation', 'medspatheme'),
            'priority' => 90,
            'panel'    => 'footer_panel'
        ));

        // Privacy Policy URL
        $wp_customize->add_setting('footer_privacy_url', array(
            'default'           => '/privacy-policy/',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('footer_privacy_url', array(
            'label'   => __('Privacy Policy URL', 'medspatheme'),
            'section' => 'footer_legal_section',
            'type'    => 'url'
        ));

        // Terms of Service URL
        $wp_customize->add_setting('footer_terms_url', array(
            'default'           => '/terms-of-service/',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('footer_terms_url', array(
            'label'   => __('Terms of Service URL', 'medspatheme'),
            'section' => 'footer_legal_section',
            'type'    => 'url'
        ));

        // Accessibility URL
        $wp_customize->add_setting('footer_accessibility_url', array(
            'default'           => '/accessibility/',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('footer_accessibility_url', array(
            'label'   => __('Accessibility URL', 'medspatheme'),
            'section' => 'footer_legal_section',
            'type'    => 'url'
        ));

        // HIPAA Compliance URL
        $wp_customize->add_setting('footer_hipaa_url', array(
            'default'           => '/hipaa-compliance/',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('footer_hipaa_url', array(
            'label'   => __('HIPAA Compliance URL', 'medspatheme'),
            'section' => 'footer_legal_section',
            'type'    => 'url'
        ));

        // Copyright Name
        $wp_customize->add_setting('footer_copyright_name', array(
            'default'           => get_bloginfo('name'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_copyright_name', array(
            'label'   => __('Copyright Name', 'medspatheme'),
            'section' => 'footer_legal_section',
            'type'    => 'text'
        ));

        // Licensing Text
        $wp_customize->add_setting('footer_licensing_text', array(
            'default'           => 'Licensed Medical Practice | HIPAA Compliant Facility',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control('footer_licensing_text', array(
            'label'   => __('Licensing Text', 'medspatheme'),
            'section' => 'footer_legal_section',
            'type'    => 'text'
        ));
    }

    /**
     * Section 10: Styling Controls
     */
    private function register_styling_section($wp_customize) {

        // Styling Section
        $wp_customize->add_section('footer_styling_section', array(
            'title'    => __('Footer Styling', 'medspatheme'),
            'priority' => 100,
            'panel'    => 'footer_panel'
        ));

        // Enable Hero Section
        $wp_customize->add_setting('footer_enable_hero', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('footer_enable_hero', array(
            'label'   => __('Enable Hero Section', 'medspatheme'),
            'section' => 'footer_styling_section',
            'type'    => 'checkbox'
        ));

        // Enable Map Section
        $wp_customize->add_setting('footer_enable_map', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('footer_enable_map', array(
            'label'   => __('Enable Map Section', 'medspatheme'),
            'section' => 'footer_styling_section',
            'type'    => 'checkbox'
        ));

        // Enable Newsletter Section
        $wp_customize->add_setting('footer_enable_newsletter', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('footer_enable_newsletter', array(
            'label'   => __('Enable Newsletter Section', 'medspatheme'),
            'section' => 'footer_styling_section',
            'type'    => 'checkbox'
        ));

        // Column Layout
        $wp_customize->add_setting('footer_column_layout', array(
            'default'           => '4',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh'
        ));
        $wp_customize->add_control('footer_column_layout', array(
            'label'   => __('Info Grid Columns', 'medspatheme'),
            'section' => 'footer_styling_section',
            'type'    => 'select',
            'choices' => array(
                '2' => __('2 Columns', 'medspatheme'),
                '3' => __('3 Columns', 'medspatheme'),
                '4' => __('4 Columns', 'medspatheme')
            )
        ));

        // Footer Background Color
        $wp_customize->add_setting('footer_background_color', array(
            'default'           => '#1B365D',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_background_color', array(
            'label'   => __('Footer Background Color', 'medspatheme'),
            'section' => 'footer_styling_section'
        )));

        // Footer Text Color
        $wp_customize->add_setting('footer_text_color', array(
            'default'           => '#FFFFFF',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_text_color', array(
            'label'   => __('Footer Text Color', 'medspatheme'),
            'section' => 'footer_styling_section'
        )));

        // CTA Button Primary Color
        $wp_customize->add_setting('footer_cta_primary_color', array(
            'default'           => '#D4AF37',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_cta_primary_color', array(
            'label'   => __('Primary CTA Button Color', 'medspatheme'),
            'section' => 'footer_styling_section'
        )));
    }

    /**
     * Enqueue customizer styles
     */
    public function enqueue_customizer_styles() {

        // Generate dynamic CSS from customizer settings
        $custom_css = $this->generate_customizer_css();

        if (!empty($custom_css)) {
            wp_add_inline_style('footer-tokenized', $custom_css);
        }
    }

    /**
     * Generate CSS from customizer settings
     */
    private function generate_customizer_css() {

        $css = '';

        // Background Color
        $bg_color = get_theme_mod('footer_background_color', '#1B365D');
        if ($bg_color !== '#1B365D') {
            $css .= "
            .site-footer.luxury-footer {
                --footer-background-primary: {$bg_color};
            }";
        }

        // Text Color
        $text_color = get_theme_mod('footer_text_color', '#FFFFFF');
        if ($text_color !== '#FFFFFF') {
            $css .= "
            .site-footer.luxury-footer {
                --footer-text-primary: {$text_color};
            }";
        }

        // CTA Primary Color
        $cta_color = get_theme_mod('footer_cta_primary_color', '#D4AF37');
        if ($cta_color !== '#D4AF37') {
            $css .= "
            .site-footer.luxury-footer {
                --footer-cta-primary-background: {$cta_color};
                --footer-cta-primary-border: {$cta_color};
            }";
        }

        // Column Layout
        $columns = get_theme_mod('footer_column_layout', '4');
        if ($columns !== '4') {
            $css .= "
            .info-grid-wrapper {
                --footer-info-grid-columns: {$columns};
            }";
        }

        return $css;
    }
}

// Initialize the Footer Customizer
new Footer_Customizer();

/**
 * Newsletter form handler
 */
function handle_footer_newsletter_signup() {

    // Verify nonce
    if (!wp_verify_nonce($_POST['newsletter_nonce'], 'footer_newsletter_signup')) {
        wp_die(__('Security check failed', 'medspatheme'));
    }

    $email = sanitize_email($_POST['newsletter_email']);

    if (!is_email($email)) {
        wp_redirect(add_query_arg('newsletter', 'invalid', wp_get_referer()));
        exit;
    }

    // Process newsletter signup (integrate with your preferred service)
    $result = wp_remote_post('https://your-newsletter-service.com/api/subscribe', array(
        'body' => array(
            'email' => $email,
            'source' => 'footer'
        )
    ));

    if (is_wp_error($result)) {
        wp_redirect(add_query_arg('newsletter', 'error', wp_get_referer()));
    } else {
        wp_redirect(add_query_arg('newsletter', 'success', wp_get_referer()));
    }
    exit;
}
add_action('admin_post_footer_newsletter_signup', 'handle_footer_newsletter_signup');
add_action('admin_post_nopriv_footer_newsletter_signup', 'handle_footer_newsletter_signup');
