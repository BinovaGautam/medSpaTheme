<?php
/**
 * Homepage Sections Customizer Controls
 *
 * WordPress Customizer integration for Sprint 11 homepage components
 *
 * @package MedSpaTheme
 * @since 1.0.0
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 * @task T11.6 WordPress Integration & Customizer Controls
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register homepage sections customizer controls
 *
 * @param WP_Customize_Manager $wp_customize
 */
function medspa_register_homepage_sections_customizer($wp_customize) {

    // Create Homepage Sections Panel
    $wp_customize->add_panel('homepage_sections', [
        'title' => __('Homepage Sections', 'medspa-theme'),
        'description' => __('Customize the content and appearance of homepage sections', 'medspa-theme'),
        'priority' => 25,
        'capability' => 'edit_theme_options'
    ]);

    // =========================================================================
    // SERVICES OVERVIEW SECTION
    // =========================================================================

    $wp_customize->add_section('services_overview_section', [
        'title' => __('Services Overview', 'medspa-theme'),
        'description' => __('Customize the grouped services section content and display', 'medspa-theme'),
        'panel' => 'homepage_sections',
        'priority' => 10
    ]);

    // Services Overview - Section Title
    $wp_customize->add_setting('services_overview_title', [
        'default' => 'Our Treatment Artistry',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage'
    ]);

    $wp_customize->add_control('services_overview_title', [
        'label' => __('Section Title', 'medspa-theme'),
        'description' => __('Main heading for the services overview section', 'medspa-theme'),
        'section' => 'services_overview_section',
        'type' => 'text',
        'priority' => 10
    ]);

    // Services Overview - Section Subtitle
    $wp_customize->add_setting('services_overview_subtitle', [
        'default' => 'Discover Personalized Medical Aesthetics',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage'
    ]);

    $wp_customize->add_control('services_overview_subtitle', [
        'label' => __('Section Subtitle', 'medspa-theme'),
        'description' => __('Supporting text below the main heading', 'medspa-theme'),
        'section' => 'services_overview_section',
        'type' => 'text',
        'priority' => 20
    ]);

    // Services Overview - Section Description
    $wp_customize->add_setting('services_overview_description', [
        'default' => 'Each treatment is carefully curated to enhance your natural beauty with precision, artistry, and innovation.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport' => 'postMessage'
    ]);

    $wp_customize->add_control('services_overview_description', [
        'label' => __('Section Description', 'medspa-theme'),
        'description' => __('Detailed description of your services approach', 'medspa-theme'),
        'section' => 'services_overview_section',
        'type' => 'textarea',
        'priority' => 30
    ]);

    // Service Section Visibility Controls
    $service_sections = [
        'injectable_artistry' => __('Injectable Artistry', 'medspa-theme'),
        'facial_renaissance' => __('Facial Renaissance', 'medspa-theme'),
        'laser_precision' => __('Laser Precision', 'medspa-theme'),
        'body_sculpting' => __('Body Sculpting', 'medspa-theme'),
        'wellness_sanctuary' => __('Wellness Sanctuary', 'medspa-theme')
    ];

    foreach ($service_sections as $section_key => $section_label) {
        $wp_customize->add_setting("show_service_{$section_key}", [
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control("show_service_{$section_key}", [
            'label' => sprintf(__('Show %s Section', 'medspa-theme'), $section_label),
            'section' => 'services_overview_section',
            'type' => 'checkbox',
            'priority' => 40
        ]);
    }

    // =========================================================================
    // TRUST INDICATORS SECTION
    // =========================================================================

    $wp_customize->add_section('trust_indicators_section', [
        'title' => __('Trust Indicators', 'medspa-theme'),
        'description' => __('Customize the "Why Choose Us" trust indicators section', 'medspa-theme'),
        'panel' => 'homepage_sections',
        'priority' => 20
    ]);

    // Trust Indicators - Section Title
    $wp_customize->add_setting('trust_indicators_title', [
        'default' => 'Why Choose PreetiDreams',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage'
    ]);

    $wp_customize->add_control('trust_indicators_title', [
        'label' => __('Section Title', 'medspa-theme'),
        'description' => __('Main heading for the trust indicators section', 'medspa-theme'),
        'section' => 'trust_indicators_section',
        'type' => 'text',
        'priority' => 10
    ]);

    // Trust Indicators - Section Subtitle
    $wp_customize->add_setting('trust_indicators_subtitle', [
        'default' => 'Experience the difference of medical artistry combined with luxury care',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage'
    ]);

    $wp_customize->add_control('trust_indicators_subtitle', [
        'label' => __('Section Subtitle', 'medspa-theme'),
        'description' => __('Supporting text that explains your unique value proposition', 'medspa-theme'),
        'section' => 'trust_indicators_section',
        'type' => 'text',
        'priority' => 20
    ]);

    // Trust Indicator Visibility Controls
    $trust_indicators = [
        'board_certified' => __('Board Certified', 'medspa-theme'),
        'award_winning' => __('Award Winning', 'medspa-theme'),
        'happy_patients' => __('2000+ Happy Patients', 'medspa-theme'),
        'hipaa_compliant' => __('HIPAA Compliant', 'medspa-theme')
    ];

    foreach ($trust_indicators as $indicator_key => $indicator_label) {
        $wp_customize->add_setting("show_trust_{$indicator_key}", [
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control("show_trust_{$indicator_key}", [
            'label' => sprintf(__('Show %s Indicator', 'medspa-theme'), $indicator_label),
            'section' => 'trust_indicators_section',
            'type' => 'checkbox',
            'priority' => 30
        ]);
    }

    // =========================================================================
    // CUSTOMIZABLE TRUST INDICATOR CONTENT
    // =========================================================================

    // Board Certified Content
    $wp_customize->add_setting('trust_board_certified_title', [
        'default' => 'Board Certified',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage'
    ]);

    $wp_customize->add_control('trust_board_certified_title', [
        'label' => __('Board Certified Title', 'medspa-theme'),
        'section' => 'trust_indicators_section',
        'type' => 'text',
        'priority' => 40
    ]);

    $wp_customize->add_setting('trust_board_certified_description', [
        'default' => 'Expert medical professionals with advanced training in aesthetic medicine and patient safety.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport' => 'postMessage'
    ]);

    $wp_customize->add_control('trust_board_certified_description', [
        'label' => __('Board Certified Description', 'medspa-theme'),
        'section' => 'trust_indicators_section',
        'type' => 'textarea',
        'priority' => 41
    ]);

    // Award Winning Content
    $wp_customize->add_setting('trust_award_winning_title', [
        'default' => 'Award Winning',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage'
    ]);

    $wp_customize->add_control('trust_award_winning_title', [
        'label' => __('Award Winning Title', 'medspa-theme'),
        'section' => 'trust_indicators_section',
        'type' => 'text',
        'priority' => 42
    ]);

    $wp_customize->add_setting('trust_award_winning_description', [
        'default' => 'Recognized excellence in medical aesthetics with industry awards and patient satisfaction.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport' => 'postMessage'
    ]);

    $wp_customize->add_control('trust_award_winning_description', [
        'label' => __('Award Winning Description', 'medspa-theme'),
        'section' => 'trust_indicators_section',
        'type' => 'textarea',
        'priority' => 43
    ]);

    // Happy Patients Content
    $wp_customize->add_setting('trust_happy_patients_title', [
        'default' => '2000+ Happy Patients',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage'
    ]);

    $wp_customize->add_control('trust_happy_patients_title', [
        'label' => __('Happy Patients Title', 'medspa-theme'),
        'section' => 'trust_indicators_section',
        'type' => 'text',
        'priority' => 44
    ]);

    $wp_customize->add_setting('trust_happy_patients_description', [
        'default' => 'Trusted by thousands of patients for natural-looking results and exceptional care.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport' => 'postMessage'
    ]);

    $wp_customize->add_control('trust_happy_patients_description', [
        'label' => __('Happy Patients Description', 'medspa-theme'),
        'section' => 'trust_indicators_section',
        'type' => 'textarea',
        'priority' => 45
    ]);

    // HIPAA Compliant Content
    $wp_customize->add_setting('trust_hipaa_compliant_title', [
        'default' => 'HIPAA Compliant',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage'
    ]);

    $wp_customize->add_control('trust_hipaa_compliant_title', [
        'label' => __('HIPAA Compliant Title', 'medspa-theme'),
        'section' => 'trust_indicators_section',
        'type' => 'text',
        'priority' => 46
    ]);

    $wp_customize->add_setting('trust_hipaa_compliant_description', [
        'default' => 'Your privacy and medical information are protected with the highest security standards.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport' => 'postMessage'
    ]);

    $wp_customize->add_control('trust_hipaa_compliant_description', [
        'label' => __('HIPAA Compliant Description', 'medspa-theme'),
        'section' => 'trust_indicators_section',
        'type' => 'textarea',
        'priority' => 47
    ]);

    // =========================================================================
    // SECTION DISPLAY OPTIONS
    // =========================================================================

    $wp_customize->add_section('homepage_sections_display', [
        'title' => __('Section Display Options', 'medspa-theme'),
        'description' => __('Control the overall display and ordering of homepage sections', 'medspa-theme'),
        'panel' => 'homepage_sections',
        'priority' => 30
    ]);

    // Show/Hide Entire Sections
    $wp_customize->add_setting('show_services_overview_section', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport' => 'postMessage'
    ]);

    $wp_customize->add_control('show_services_overview_section', [
        'label' => __('Show Services Overview Section', 'medspa-theme'),
        'description' => __('Toggle the entire services overview section on/off', 'medspa-theme'),
        'section' => 'homepage_sections_display',
        'type' => 'checkbox',
        'priority' => 10
    ]);

    $wp_customize->add_setting('show_trust_indicators_section', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport' => 'postMessage'
    ]);

    $wp_customize->add_control('show_trust_indicators_section', [
        'label' => __('Show Trust Indicators Section', 'medspa-theme'),
        'description' => __('Toggle the entire trust indicators section on/off', 'medspa-theme'),
        'section' => 'homepage_sections_display',
        'type' => 'checkbox',
        'priority' => 20
    ]);
}

// Hook into WordPress Customizer
add_action('customize_register', 'medspa_register_homepage_sections_customizer');

/**
 * Enqueue customizer preview scripts for real-time updates
 */
function medspa_customizer_preview_scripts() {
    wp_enqueue_script(
        'medspa-customizer-preview',
        get_template_directory_uri() . '/assets/js/customizer-preview.js',
        ['customize-preview'],
        '1.0.0',
        true
    );
}
add_action('customize_preview_init', 'medspa_customizer_preview_scripts');

/**
 * Helper function to get service sections based on customizer settings
 *
 * @return array Active service sections
 */
function medspa_get_active_service_sections() {
    $sections = [];

    $available_sections = [
        'injectable-artistry' => 'injectable_artistry',
        'facial-renaissance' => 'facial_renaissance',
        'laser-precision' => 'laser_precision',
        'body-sculpting' => 'body_sculpting',
        'wellness-sanctuary' => 'wellness_sanctuary'
    ];

    foreach ($available_sections as $section_key => $setting_key) {
        if (get_theme_mod("show_service_{$setting_key}", true)) {
            $sections[] = $section_key;
        }
    }

    return $sections;
}

/**
 * Helper function to get active trust indicators based on customizer settings
 *
 * @return array Active trust indicators
 */
function medspa_get_active_trust_indicators() {
    $indicators = [];

    $available_indicators = [
        'board-certified' => 'board_certified',
        'award-winning' => 'award_winning',
        'happy-patients' => 'happy_patients',
        'hipaa-compliant' => 'hipaa_compliant'
    ];

    foreach ($available_indicators as $indicator_key => $setting_key) {
        if (get_theme_mod("show_trust_{$setting_key}", true)) {
            $indicators[] = $indicator_key;
        }
    }

    return $indicators;
}

/**
 * Output customizer CSS for real-time preview
 */
function medspa_customizer_css_output() {
    ?>
    <style type="text/css" id="medspa-customizer-css">
        /* Real-time customizer preview styles will be injected here */
    </style>
    <?php
}
add_action('wp_head', 'medspa_customizer_css_output');
