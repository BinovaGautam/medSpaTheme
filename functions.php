<?php

/**
 * PreetiDreams Medical Spa Theme
 *
 * @package PreetiDreams
 * @author PreetiDreams Development Team
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme version
define('PREETIDREAMS_VERSION', '1.0.0');

/**
 * Theme setup
 */
function preetidreams_theme_support() {
    // Theme supports
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', [
        'comment-list',
        'comment-form',
        'search-form',
        'gallery',
        'caption'
    ]);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');

    // Navigation menus
    register_nav_menus([
        'primary' => __('Primary Menu', 'preetidreams'),
        'footer' => __('Footer Menu', 'preetidreams'),
    ]);

    // Custom image sizes for medical spa
    add_image_size('treatment-card', 400, 300, true);
    add_image_size('before-after', 600, 400, true);
    add_image_size('hero-banner', 1920, 800, true);
    add_image_size('staff-photo', 300, 300, true);
}
add_action('after_setup_theme', 'preetidreams_theme_support');

/**
 * Enqueue scripts and styles
 */
function preetidreams_enqueue_scripts() {
    // Always enqueue the main stylesheet
    wp_enqueue_style(
        'preetidreams-style',
        get_stylesheet_uri(),
        [],
        PREETIDREAMS_VERSION
    );

    // Enqueue medical spa theme CSS
    wp_enqueue_style(
        'preetidreams-medical-spa',
        get_template_directory_uri() . '/assets/css/medical-spa-theme.css',
        ['preetidreams-style'],
        PREETIDREAMS_VERSION
    );

    // Enqueue core JavaScript
    wp_enqueue_script(
        'preetidreams-app-core',
        get_template_directory_uri() . '/assets/js/core/app.js',
        [],
        PREETIDREAMS_VERSION,
        true
    );

    // Enqueue mobile menu component
    wp_enqueue_script(
        'preetidreams-mobile-menu',
        get_template_directory_uri() . '/assets/js/components/mobile-menu.js',
        ['preetidreams-app-core'],
        PREETIDREAMS_VERSION,
        true
    );

    // Enqueue treatment filter on relevant pages
    if (is_post_type_archive('treatment') || is_page_template('page-treatments.php') || is_front_page()) {
        wp_enqueue_script(
            'preetidreams-treatment-filter',
            get_template_directory_uri() . '/assets/js/components/treatment-filter.js',
            ['preetidreams-app-core'],
            PREETIDREAMS_VERSION,
            true
        );
    }

    // Localize script with theme data
    wp_localize_script('preetidreams-app-core', 'medicalSpaTheme', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('preetidreams_nonce'),
        'version' => PREETIDREAMS_VERSION,
        'themeUrl' => get_template_directory_uri(),
        'components' => [
            'treatmentFilter' => is_post_type_archive('treatment') || is_page_template('page-treatments.php') || is_front_page(),
            'mobileMenu' => true,
        ],
        'settings' => [
            'phone' => get_theme_mod('preetidreams_phone', ''),
            'email' => get_theme_mod('preetidreams_email', ''),
            'address' => get_theme_mod('preetidreams_address', ''),
        ]
    ]);

    // Check if Vite build exists and enqueue compiled assets
    $vite_manifest = get_template_directory() . '/public/build/manifest.json';

    if (file_exists($vite_manifest)) {
        $manifest = json_decode(file_get_contents($vite_manifest), true);

        // Enqueue compiled CSS if available
        if (isset($manifest['resources/styles/app.scss'])) {
            wp_enqueue_style(
                'preetidreams-app',
                get_template_directory_uri() . '/public/build/' . $manifest['resources/styles/app.scss']['file'],
                ['preetidreams-medical-spa'],
                PREETIDREAMS_VERSION
            );
        }

        // Enqueue compiled JS if available
        if (isset($manifest['resources/scripts/app.ts'])) {
            wp_enqueue_script(
                'preetidreams-app',
                get_template_directory_uri() . '/public/build/' . $manifest['resources/scripts/app.ts']['file'],
                ['preetidreams-app-core'],
                PREETIDREAMS_VERSION,
                true
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'preetidreams_enqueue_scripts');

/**
 * Enqueue Premium Hero System Assets
 */
function preetidreams_enqueue_hero_assets() {
    if (is_front_page()) {
        // AOS Library for animations
        wp_enqueue_style('aos-css', 'https://unpkg.com/aos@2.3.1/dist/aos.css', [], '2.3.1');
        wp_enqueue_script('aos-js', 'https://unpkg.com/aos@2.3.1/dist/aos.js', [], '2.3.1', true);

        // Premium Hero System
        wp_enqueue_script(
            'preetidreams-premium-hero',
            get_template_directory_uri() . '/assets/js/components/premium-hero.js',
            ['jquery'],
            PREETIDREAMS_VERSION,
            true
        );

        // Localize script for AJAX
        wp_localize_script('preetidreams-premium-hero', 'ajax_object', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('hero_consultation_nonce')
        ]);

        // Header scroll behavior for seamless hero integration
        wp_add_inline_script('preetidreams-premium-hero', '
            document.addEventListener("DOMContentLoaded", function() {
                const header = document.querySelector(".professional-header");

                if (header) {
                    let lastScrollTop = 0;
                    let scrollTimeout;

                    function handleScroll() {
                        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                        const headerHeight = header.offsetHeight;

                        // Add scrolled class when scrolled past 50px for transparent-to-opaque effect
                        if (scrollTop > 50) {
                            header.classList.add("scrolled");
                        } else {
                            header.classList.remove("scrolled");
                        }

                        // Hide/show header based on scroll direction (only when scrolled significantly)
                        if (scrollTop > lastScrollTop && scrollTop > headerHeight * 2) {
                            // Scrolling down - hide header
                            header.classList.add("hidden");
                            header.classList.remove("visible");
                        } else if (scrollTop < lastScrollTop || scrollTop <= headerHeight) {
                            // Scrolling up or near top - show header
                            header.classList.remove("hidden");
                            header.classList.add("visible");
                        }

                        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // For Mobile or negative scrolling
                    }

                    // Throttled scroll handler for better performance
                    function throttledScrollHandler() {
                        if (scrollTimeout) {
                            clearTimeout(scrollTimeout);
                        }
                        scrollTimeout = setTimeout(handleScroll, 10);
                    }

                    // Add scroll event listener
                    window.addEventListener("scroll", throttledScrollHandler, { passive: true });

                    // Initial call to set proper state
                    handleScroll();

                    // Ensure header is visible when page loads
                    header.classList.add("visible");
                }
            });
        ');
    }
}
add_action('wp_enqueue_scripts', 'preetidreams_enqueue_hero_assets');

/**
 * Handle Hero Consultation Form Submission
 */
function handle_hero_consultation_submission() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'hero_consultation_nonce')) {
        wp_send_json_error('Security check failed');
    }

    // Sanitize form data
    $data = [
        'full_name' => sanitize_text_field($_POST['full_name']),
        'email' => sanitize_email($_POST['email']),
        'phone' => sanitize_text_field($_POST['phone']),
        'message' => sanitize_textarea_field($_POST['message']),
        'selected_category' => sanitize_text_field($_POST['selected_category']),
        'selected_treatment' => sanitize_text_field($_POST['selected_treatment']),
        'source' => sanitize_text_field($_POST['source'])
    ];

    // Validate required fields
    if (empty($data['full_name']) || empty($data['email']) || empty($data['phone'])) {
        wp_send_json_error('Please fill in all required fields');
    }

    // Validate email
    if (!is_email($data['email'])) {
        wp_send_json_error('Please enter a valid email address');
    }

    // Store consultation request
    $consultation_id = wp_insert_post([
        'post_type' => 'consultation_request',
        'post_status' => 'private',
        'post_title' => 'Hero Consultation: ' . $data['full_name'],
        'post_content' => $data['message'],
        'meta_input' => [
            'contact_email' => $data['email'],
            'contact_phone' => $data['phone'],
            'selected_category' => $data['selected_category'],
            'selected_treatment' => $data['selected_treatment'],
            'source' => $data['source'],
            'submission_date' => current_time('mysql'),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]
    ]);

    if ($consultation_id) {
        // Send notification email to admin
        $admin_email = get_option('admin_email');
        $site_name = get_bloginfo('name');
        $subject = sprintf('[%s] New Hero Consultation Request from %s', $site_name, $data['full_name']);

        $message = "New consultation request received via Premium Hero System:\n\n";
        $message .= "Name: " . $data['full_name'] . "\n";
        $message .= "Email: " . $data['email'] . "\n";
        $message .= "Phone: " . $data['phone'] . "\n";
        $message .= "Category: " . $data['selected_category'] . "\n";
        $message .= "Treatment: " . $data['selected_treatment'] . "\n";
        $message .= "Message: " . $data['message'] . "\n";
        $message .= "Source: " . $data['source'] . "\n";
        $message .= "Submitted: " . current_time('mysql') . "\n\n";
        $message .= "View in admin: " . admin_url('edit.php?post_type=consultation_request');

        $headers = [
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . $site_name . ' <noreply@' . $_SERVER['HTTP_HOST'] . '>',
            'Reply-To: ' . $data['full_name'] . ' <' . $data['email'] . '>'
        ];

        wp_mail($admin_email, $subject, $message, $headers);

        // Send confirmation email to user
        $user_subject = sprintf('Thank you for your consultation request - %s', $site_name);
        $user_message = "Dear " . $data['full_name'] . ",\n\n";
        $user_message .= "Thank you for your consultation request. We have received your inquiry about " . $data['selected_treatment'] . ".\n\n";
        $user_message .= "We will contact you within 24 hours to schedule your consultation.\n\n";
        $user_message .= "If you have any immediate questions, please call us at " . preetidreams_get_phone() . ".\n\n";
        $user_message .= "Best regards,\n" . $site_name;

        wp_mail($data['email'], $user_subject, $user_message, $headers);

        wp_send_json_success('Consultation request submitted successfully');
    } else {
        wp_send_json_error('Failed to submit consultation request');
    }
}
add_action('wp_ajax_submit_hero_consultation', 'handle_hero_consultation_submission');
add_action('wp_ajax_nopriv_submit_hero_consultation', 'handle_hero_consultation_submission');

/**
 * Register Consultation Request Custom Post Type
 */
function register_consultation_request_post_type() {
    $labels = [
        'name' => 'Consultation Requests',
        'singular_name' => 'Consultation Request',
        'menu_name' => 'Consultations',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Consultation Request',
        'new_item' => 'New Consultation Request',
        'edit_item' => 'Edit Consultation Request',
        'view_item' => 'View Consultation Request',
        'all_items' => 'All Consultations',
        'search_items' => 'Search Consultations',
        'not_found' => 'No consultation requests found.',
        'not_found_in_trash' => 'No consultation requests found in Trash.'
    ];

    $args = [
        'labels' => $labels,
        'description' => 'Consultation requests from the website',
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => false,
        'rewrite' => false,
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => 25,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => ['title', 'editor'],
        'show_in_admin_bar' => false,
        'show_in_nav_menus' => false,
        'can_export' => true,
        'exclude_from_search' => true
    ];

    register_post_type('consultation_request', $args);
}
add_action('init', 'register_consultation_request_post_type');

/**
 * Add custom columns to consultation requests admin
 */
function consultation_request_admin_columns($columns) {
    $new_columns = [];
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = 'Name';
    $new_columns['contact_info'] = 'Contact Info';
    $new_columns['treatment'] = 'Treatment Interest';
    $new_columns['source'] = 'Source';
    $new_columns['date'] = 'Submitted';

    return $new_columns;
}
add_filter('manage_consultation_request_posts_columns', 'consultation_request_admin_columns');

/**
 * Fill custom columns with data
 */
function consultation_request_admin_column_content($column, $post_id) {
    switch ($column) {
        case 'contact_info':
            $email = get_post_meta($post_id, 'contact_email', true);
            $phone = get_post_meta($post_id, 'contact_phone', true);
            echo '<strong>Email:</strong> <a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a><br>';
            echo '<strong>Phone:</strong> <a href="tel:' . esc_attr($phone) . '">' . esc_html($phone) . '</a>';
            break;

        case 'treatment':
            $category = get_post_meta($post_id, 'selected_category', true);
            $treatment = get_post_meta($post_id, 'selected_treatment', true);
            echo '<strong>Category:</strong> ' . esc_html(ucfirst($category)) . '<br>';
            echo '<strong>Treatment:</strong> ' . esc_html($treatment);
            break;

        case 'source':
            $source = get_post_meta($post_id, 'source', true);
            echo esc_html($source);
            break;
    }
}
add_action('manage_consultation_request_posts_custom_column', 'consultation_request_admin_column_content', 10, 2);

/**
 * Add Hero Customizer Options
 */
function preetidreams_hero_customizer($wp_customize) {
    // Hero Section
    $wp_customize->add_section('hero_section', [
        'title' => 'Premium Hero Section',
        'priority' => 30,
        'description' => 'Configure the premium hero section with dynamic backgrounds and interactive treatment selection.'
    ]);

    // Hero Title
    $wp_customize->add_setting('hero_title', [
        'default' => 'Transform Your Beauty with Advanced Medical Spa Treatments',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh'
    ]);

    $wp_customize->add_control('hero_title', [
        'label' => 'Hero Title',
        'section' => 'hero_section',
        'type' => 'text',
        'description' => 'Main headline for the hero section'
    ]);

    // Hero Subtitle
    $wp_customize->add_setting('hero_subtitle', [
        'default' => 'Experience the latest in non-surgical aesthetic treatments performed by board-certified professionals in a luxurious, comfortable environment.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport' => 'refresh'
    ]);

    $wp_customize->add_control('hero_subtitle', [
        'label' => 'Hero Subtitle',
        'section' => 'hero_section',
        'type' => 'textarea',
        'description' => 'Supporting text under the main headline'
    ]);

    // Background Image
    $wp_customize->add_setting('hero_background_image', [
        'default' => get_template_directory_uri() . '/assets/images/hero-medical-spa.jpg',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh'
    ]);

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_background_image', [
        'label' => 'Hero Background Image',
        'section' => 'hero_section',
        'description' => 'Background image for the hero section (recommended: 1920x1080px)'
    ]));

    // Background Video
    $wp_customize->add_setting('hero_background_video', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh'
    ]);

    $wp_customize->add_control('hero_background_video', [
        'label' => 'Hero Background Video URL',
        'section' => 'hero_section',
        'type' => 'url',
        'description' => 'MP4 video URL for background (optional, will auto-rotate with image)'
    ]);

    // Enable Dynamic Backgrounds
    $wp_customize->add_setting('hero_dynamic_backgrounds', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport' => 'refresh'
    ]);

    $wp_customize->add_control('hero_dynamic_backgrounds', [
        'label' => 'Enable Dynamic Background Rotation',
        'section' => 'hero_section',
        'type' => 'checkbox',
        'description' => 'Automatically rotate between image, video, and gradient backgrounds'
    ]);
}
add_action('customize_register', 'preetidreams_hero_customizer');

/**
 * Admin notice for theme status
 */
function preetidreams_admin_notice() {
    $screen = get_current_screen();
    if ($screen && $screen->base !== 'themes') {
        return;
    }

    echo '<div class="notice notice-success"><p>';
    echo '<strong>🏥 PreetiDreams Medical Spa Theme:</strong> ';
    echo 'Theme is active and working properly. All customization features are available.';
    echo '</p></div>';
}
add_action('admin_notices', 'preetidreams_admin_notice');

/**
 * Theme activation hook
 */
function preetidreams_theme_activation() {
    // Flush rewrite rules
    flush_rewrite_rules();

    // Set default options
    if (!get_option('preetidreams_setup_complete')) {
        update_option('preetidreams_setup_complete', true);
    }

    // Create sample treatment data
    preetidreams_create_sample_treatments();
}
add_action('after_switch_theme', 'preetidreams_theme_activation');

/**
 * Customizer additions
 */
function preetidreams_customize_register($wp_customize) {
    // Medical Spa Settings Section
    $wp_customize->add_section('preetidreams_medical_spa', [
        'title' => __('Medical Spa Settings', 'preetidreams'),
        'priority' => 30,
        'description' => __('Configure your medical spa contact information and settings.', 'preetidreams'),
    ]);

    // Phone number
    $wp_customize->add_setting('preetidreams_phone', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('preetidreams_phone', [
        'label' => __('Phone Number', 'preetidreams'),
        'section' => 'preetidreams_medical_spa',
        'type' => 'tel',
        'description' => __('Enter your medical spa phone number.', 'preetidreams'),
    ]);

    // Address
    $wp_customize->add_setting('preetidreams_address', [
        'default' => '',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('preetidreams_address', [
        'label' => __('Address', 'preetidreams'),
        'section' => 'preetidreams_medical_spa',
        'type' => 'textarea',
        'description' => __('Enter your medical spa address.', 'preetidreams'),
    ]);

    // Email
    $wp_customize->add_setting('preetidreams_email', [
        'default' => '',
        'sanitize_callback' => 'sanitize_email',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('preetidreams_email', [
        'label' => __('Email Address', 'preetidreams'),
        'section' => 'preetidreams_medical_spa',
        'type' => 'email',
        'description' => __('Enter your medical spa email address.', 'preetidreams'),
    ]);

    // Business Hours
    $wp_customize->add_setting('preetidreams_hours', [
        'default' => '',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('preetidreams_hours', [
        'label' => __('Business Hours', 'preetidreams'),
        'section' => 'preetidreams_medical_spa',
        'type' => 'textarea',
        'description' => __('Enter your business hours (e.g., Mon-Fri: 9AM-6PM).', 'preetidreams'),
    ]);

    // Social Media Section
    $wp_customize->add_section('preetidreams_social_media', [
        'title' => __('Social Media', 'preetidreams'),
        'priority' => 31,
        'description' => __('Add your social media links.', 'preetidreams'),
    ]);

    $social_platforms = [
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'twitter' => 'Twitter',
        'youtube' => 'YouTube',
        'linkedin' => 'LinkedIn'
    ];

    foreach ($social_platforms as $platform => $label) {
        $wp_customize->add_setting("preetidreams_social_{$platform}", [
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport' => 'refresh',
        ]);

        $wp_customize->add_control("preetidreams_social_{$platform}", [
            'label' => $label . ' URL',
            'section' => 'preetidreams_social_media',
            'type' => 'url',
        ]);
    }
}
add_action('customize_register', 'preetidreams_customize_register');

/**
 * Widget areas
 */
function preetidreams_widgets_init() {
    $widget_config = [
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Footer Widgets', 'preetidreams'),
        'id' => 'footer-widgets',
        'description' => __('Add widgets here to appear in your footer.', 'preetidreams'),
    ] + $widget_config);

    register_sidebar([
        'name' => __('Sidebar', 'preetidreams'),
        'id' => 'sidebar-primary',
        'description' => __('Add widgets here to appear in your sidebar.', 'preetidreams'),
    ] + $widget_config);
}
add_action('widgets_init', 'preetidreams_widgets_init');

/**
 * Medical Spa Custom Post Types
 */
function preetidreams_custom_post_types() {
    // Treatments post type
    register_post_type('treatment', [
        'labels' => [
            'name' => __('Treatments', 'preetidreams'),
            'singular_name' => __('Treatment', 'preetidreams'),
            'add_new' => __('Add New Treatment', 'preetidreams'),
            'add_new_item' => __('Add New Treatment', 'preetidreams'),
            'edit_item' => __('Edit Treatment', 'preetidreams'),
            'new_item' => __('New Treatment', 'preetidreams'),
            'view_item' => __('View Treatment', 'preetidreams'),
            'search_items' => __('Search Treatments', 'preetidreams'),
            'not_found' => __('No treatments found', 'preetidreams'),
            'not_found_in_trash' => __('No treatments found in trash', 'preetidreams'),
        ],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'menu_icon' => 'dashicons-heart',
        'rewrite' => ['slug' => 'treatments'],
        'show_in_rest' => true,
        'menu_position' => 20,
    ]);

    // Staff post type
    register_post_type('staff', [
        'labels' => [
            'name' => __('Staff', 'preetidreams'),
            'singular_name' => __('Staff Member', 'preetidreams'),
            'add_new' => __('Add Staff Member', 'preetidreams'),
            'add_new_item' => __('Add New Staff Member', 'preetidreams'),
            'edit_item' => __('Edit Staff Member', 'preetidreams'),
            'new_item' => __('New Staff Member', 'preetidreams'),
            'view_item' => __('View Staff Member', 'preetidreams'),
            'search_items' => __('Search Staff', 'preetidreams'),
            'not_found' => __('No staff found', 'preetidreams'),
            'not_found_in_trash' => __('No staff found in trash', 'preetidreams'),
        ],
        'public' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'custom-fields'],
        'menu_icon' => 'dashicons-businessperson',
        'rewrite' => ['slug' => 'staff'],
        'show_in_rest' => true,
        'menu_position' => 21,
    ]);

    // Testimonials post type
    register_post_type('testimonial', [
        'labels' => [
            'name' => __('Testimonials', 'preetidreams'),
            'singular_name' => __('Testimonial', 'preetidreams'),
            'add_new' => __('Add Testimonial', 'preetidreams'),
            'add_new_item' => __('Add New Testimonial', 'preetidreams'),
            'edit_item' => __('Edit Testimonial', 'preetidreams'),
        ],
        'public' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
        'menu_icon' => 'dashicons-star-filled',
        'rewrite' => ['slug' => 'testimonials'],
        'show_in_rest' => true,
        'menu_position' => 22,
    ]);
}
add_action('init', 'preetidreams_custom_post_types');

/**
 * Register Treatment Taxonomies
 */
function preetidreams_custom_taxonomies() {
    // Treatment Categories
    register_taxonomy('treatment_category', 'treatment', [
        'labels' => [
            'name' => __('Treatment Categories', 'preetidreams'),
            'singular_name' => __('Treatment Category', 'preetidreams'),
            'search_items' => __('Search Categories', 'preetidreams'),
            'all_items' => __('All Categories', 'preetidreams'),
            'parent_item' => __('Parent Category', 'preetidreams'),
            'parent_item_colon' => __('Parent Category:', 'preetidreams'),
            'edit_item' => __('Edit Category', 'preetidreams'),
            'update_item' => __('Update Category', 'preetidreams'),
            'add_new_item' => __('Add New Category', 'preetidreams'),
            'new_item_name' => __('New Category Name', 'preetidreams'),
        ],
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'treatment-category'],
        'show_in_rest' => true,
    ]);

    // Treatment Areas
    register_taxonomy('treatment_area', 'treatment', [
        'labels' => [
            'name' => __('Treatment Areas', 'preetidreams'),
            'singular_name' => __('Treatment Area', 'preetidreams'),
            'search_items' => __('Search Areas', 'preetidreams'),
            'all_items' => __('All Areas', 'preetidreams'),
            'edit_item' => __('Edit Area', 'preetidreams'),
            'update_item' => __('Update Area', 'preetidreams'),
            'add_new_item' => __('Add New Area', 'preetidreams'),
            'new_item_name' => __('New Area Name', 'preetidreams'),
        ],
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'treatment-area'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'preetidreams_custom_taxonomies');

/**
 * Create sample treatment data on theme activation
 */
function preetidreams_create_sample_treatments() {
    // Check if we've already created sample data
    if (get_option('preetidreams_sample_data_created')) {
        return;
    }

    // Create treatment categories
    $categories = [
        'facial-treatments' => 'Facial Treatments',
        'injectable-treatments' => 'Injectable Treatments',
        'body-treatments' => 'Body Treatments',
        'laser-treatments' => 'Laser Treatments',
        'wellness-treatments' => 'Wellness Treatments'
    ];

    foreach ($categories as $slug => $name) {
        if (!term_exists($name, 'treatment_category')) {
            wp_insert_term($name, 'treatment_category', ['slug' => $slug]);
        }
    }

    // Create treatment areas
    $areas = [
        'face' => 'Face',
        'body' => 'Body',
        'hands' => 'Hands',
        'neck-decolletage' => 'Neck & Décolletage',
        'full-body' => 'Full Body'
    ];

    foreach ($areas as $slug => $name) {
        if (!term_exists($name, 'treatment_area')) {
            wp_insert_term($name, 'treatment_area', ['slug' => $slug]);
        }
    }

    // Sample treatments data
    $sample_treatments = [
        [
            'title' => 'Botox Cosmetic',
            'content' => 'Reduce fine lines and wrinkles with our FDA-approved Botox treatments performed by board-certified professionals in a comfortable, spa-like environment.',
            'excerpt' => 'FDA-approved injectable treatment to smooth wrinkles and fine lines.',
            'category' => 'injectable-treatments',
            'area' => 'face',
            'meta' => [
                'treatment_duration' => '15-30 minutes',
                'treatment_duration_minutes' => '20',
                'treatment_price_range' => '$300-$600',
                'treatment_price' => '450',
                'treatment_downtime' => 'Minimal',
                'treatment_results_timeline' => '3-7 days',
                'treatment_sessions_needed' => '1',
                'treatment_featured' => '1',
                'treatment_popularity' => '5'
            ]
        ],
        [
            'title' => 'HydraFacial',
            'content' => 'Experience the ultimate skin rejuvenation with our HydraFacial treatment that cleanses, extracts, and hydrates your skin for an instant glow.',
            'excerpt' => 'Multi-step facial treatment for instant skin rejuvenation and glow.',
            'category' => 'facial-treatments',
            'area' => 'face',
            'meta' => [
                'treatment_duration' => '30-45 minutes',
                'treatment_duration_minutes' => '35',
                'treatment_price_range' => '$150-$250',
                'treatment_price' => '200',
                'treatment_downtime' => 'None',
                'treatment_results_timeline' => 'Immediate',
                'treatment_sessions_needed' => '1-3',
                'treatment_featured' => '1',
                'treatment_popularity' => '5'
            ]
        ],
        [
            'title' => 'Dermal Fillers',
            'content' => 'Restore volume and smooth wrinkles with our premium dermal filler treatments using hyaluronic acid-based products for natural-looking results.',
            'excerpt' => 'Restore facial volume and smooth wrinkles with premium fillers.',
            'category' => 'injectable-treatments',
            'area' => 'face',
            'meta' => [
                'treatment_duration' => '30-60 minutes',
                'treatment_duration_minutes' => '45',
                'treatment_price_range' => '$500-$1200',
                'treatment_price' => '800',
                'treatment_downtime' => '1-3 days',
                'treatment_results_timeline' => 'Immediate',
                'treatment_sessions_needed' => '1',
                'treatment_featured' => '1',
                'treatment_popularity' => '4'
            ]
        ],
        [
            'title' => 'Laser Hair Removal',
            'content' => 'Achieve long-lasting smooth skin with our advanced laser hair removal technology. Safe and effective for all skin types.',
            'excerpt' => 'Advanced laser technology for permanent hair reduction.',
            'category' => 'laser-treatments',
            'area' => 'body',
            'meta' => [
                'treatment_duration' => '15-90 minutes',
                'treatment_duration_minutes' => '45',
                'treatment_price_range' => '$100-$500',
                'treatment_price' => '250',
                'treatment_downtime' => 'Minimal',
                'treatment_results_timeline' => '2-4 weeks',
                'treatment_sessions_needed' => '6-8',
                'treatment_featured' => '1',
                'treatment_popularity' => '4'
            ]
        ],
        [
            'title' => 'Chemical Peel',
            'content' => 'Reveal brighter, smoother skin with our professional chemical peels tailored to your skin type and concerns.',
            'excerpt' => 'Professional chemical peels for brighter, smoother skin.',
            'category' => 'facial-treatments',
            'area' => 'face',
            'meta' => [
                'treatment_duration' => '30-45 minutes',
                'treatment_duration_minutes' => '40',
                'treatment_price_range' => '$100-$300',
                'treatment_price' => '180',
                'treatment_downtime' => '3-7 days',
                'treatment_results_timeline' => '1-2 weeks',
                'treatment_sessions_needed' => '3-6',
                'treatment_featured' => '1',
                'treatment_popularity' => '3'
            ]
        ],
        [
            'title' => 'Microneedling',
            'content' => 'Stimulate your skin\'s natural healing process with our professional microneedling treatments for improved texture and tone.',
            'excerpt' => 'Stimulate collagen production for improved skin texture.',
            'category' => 'facial-treatments',
            'area' => 'face',
            'meta' => [
                'treatment_duration' => '45-60 minutes',
                'treatment_duration_minutes' => '50',
                'treatment_price_range' => '$200-$400',
                'treatment_price' => '300',
                'treatment_downtime' => '2-3 days',
                'treatment_results_timeline' => '2-4 weeks',
                'treatment_sessions_needed' => '3-4',
                'treatment_featured' => '1',
                'treatment_popularity' => '3'
            ]
        ],
        [
            'title' => 'CoolSculpting',
            'content' => 'Non-invasive fat reduction treatment that freezes away stubborn fat cells for a more contoured silhouette.',
            'excerpt' => 'Non-invasive fat freezing technology for body contouring.',
            'category' => 'body-treatments',
            'area' => 'body',
            'meta' => [
                'treatment_duration' => '35-60 minutes',
                'treatment_duration_minutes' => '45',
                'treatment_price_range' => '$600-$1200',
                'treatment_price' => '900',
                'treatment_downtime' => 'Minimal',
                'treatment_results_timeline' => '2-4 months',
                'treatment_sessions_needed' => '1-3',
                'treatment_featured' => '1',
                'treatment_popularity' => '4'
            ]
        ],
        [
            'title' => 'IPL Photofacial',
            'content' => 'Improve skin tone, reduce sun damage, and minimize pores with our advanced Intense Pulsed Light therapy.',
            'excerpt' => 'Advanced light therapy for improved skin tone and texture.',
            'category' => 'laser-treatments',
            'area' => 'face',
            'meta' => [
                'treatment_duration' => '20-30 minutes',
                'treatment_duration_minutes' => '25',
                'treatment_price_range' => '$150-$350',
                'treatment_price' => '250',
                'treatment_downtime' => '1-2 days',
                'treatment_results_timeline' => '1-2 weeks',
                'treatment_sessions_needed' => '3-5',
                'treatment_featured' => '1',
                'treatment_popularity' => '3'
            ]
        ],
        [
            'title' => 'LED Light Therapy',
            'content' => 'Harness the power of medical-grade LED light to reduce inflammation, stimulate collagen, and improve overall skin health.',
            'excerpt' => 'Medical-grade LED therapy for skin rejuvenation.',
            'category' => 'wellness-treatments',
            'area' => 'face',
            'meta' => [
                'treatment_duration' => '20-30 minutes',
                'treatment_duration_minutes' => '25',
                'treatment_price_range' => '$75-$150',
                'treatment_price' => '100',
                'treatment_downtime' => 'None',
                'treatment_results_timeline' => '2-4 weeks',
                'treatment_sessions_needed' => '6-12',
                'treatment_featured' => '0',
                'treatment_popularity' => '2'
            ]
        ],
        [
            'title' => 'Vampire Facial (PRP)',
            'content' => 'Rejuvenate your skin using your body\'s own platelet-rich plasma combined with microneedling for natural anti-aging results.',
            'excerpt' => 'Natural skin rejuvenation using your own platelet-rich plasma.',
            'category' => 'facial-treatments',
            'area' => 'face',
            'meta' => [
                'treatment_duration' => '60-90 minutes',
                'treatment_duration_minutes' => '75',
                'treatment_price_range' => '$400-$800',
                'treatment_price' => '600',
                'treatment_downtime' => '3-5 days',
                'treatment_results_timeline' => '3-6 weeks',
                'treatment_sessions_needed' => '2-3',
                'treatment_featured' => '1',
                'treatment_popularity' => '4'
            ]
        ],
        [
            'title' => 'Radiofrequency Skin Tightening',
            'content' => 'Non-surgical skin tightening using advanced radiofrequency technology to stimulate collagen and improve skin elasticity.',
            'excerpt' => 'Non-surgical radiofrequency treatment for skin tightening.',
            'category' => 'facial-treatments',
            'area' => 'face',
            'meta' => [
                'treatment_duration' => '45-60 minutes',
                'treatment_duration_minutes' => '50',
                'treatment_price_range' => '$300-$600',
                'treatment_price' => '450',
                'treatment_downtime' => 'None',
                'treatment_results_timeline' => '2-6 months',
                'treatment_sessions_needed' => '4-6',
                'treatment_featured' => '0',
                'treatment_popularity' => '3'
            ]
        ],
        [
            'title' => 'Body Contouring Massage',
            'content' => 'Therapeutic massage techniques combined with advanced technology to improve circulation and reduce the appearance of cellulite.',
            'excerpt' => 'Therapeutic massage for body contouring and circulation.',
            'category' => 'body-treatments',
            'area' => 'body',
            'meta' => [
                'treatment_duration' => '60-90 minutes',
                'treatment_duration_minutes' => '75',
                'treatment_price_range' => '$150-$300',
                'treatment_price' => '200',
                'treatment_downtime' => 'None',
                'treatment_results_timeline' => '2-4 weeks',
                'treatment_sessions_needed' => '6-10',
                'treatment_featured' => '0',
                'treatment_popularity' => '2'
            ]
        ]
    ];

    // Create the treatment posts
    foreach ($sample_treatments as $treatment_data) {
        // Create the post
        $post_id = wp_insert_post([
            'post_title' => $treatment_data['title'],
            'post_content' => $treatment_data['content'],
            'post_excerpt' => $treatment_data['excerpt'],
            'post_status' => 'publish',
            'post_type' => 'treatment',
            'post_author' => 1
        ]);

        if ($post_id && !is_wp_error($post_id)) {
            // Set category
            $category_term = get_term_by('slug', $treatment_data['category'], 'treatment_category');
            if ($category_term) {
                wp_set_post_terms($post_id, [$category_term->term_id], 'treatment_category');
            }

            // Set area
            $area_term = get_term_by('slug', $treatment_data['area'], 'treatment_area');
            if ($area_term) {
                wp_set_post_terms($post_id, [$area_term->term_id], 'treatment_area');
            }

            // Set custom meta fields
            foreach ($treatment_data['meta'] as $meta_key => $meta_value) {
                update_post_meta($post_id, $meta_key, $meta_value);
            }
        }
    }

    // Mark that we've created sample data
    update_option('preetidreams_sample_data_created', true);
}

// Also provide a way to manually trigger sample data creation
function preetidreams_admin_menu() {
    add_theme_page(
        'Medical Spa Theme Setup',
        'Theme Setup',
        'manage_options',
        'preetidreams-setup',
        'preetidreams_setup_page'
    );
}
add_action('admin_menu', 'preetidreams_admin_menu');

function preetidreams_setup_page() {
    if (isset($_POST['create_sample_data']) && check_admin_referer('preetidreams_setup')) {
        delete_option('preetidreams_sample_data_created');
        preetidreams_create_sample_treatments();
        echo '<div class="notice notice-success"><p>Sample treatment data has been created successfully!</p></div>';
    }

    ?>
    <div class="wrap">
        <h1>Medical Spa Theme Setup</h1>
        <p>Use this page to set up your medical spa theme with sample data.</p>

        <form method="post" action="">
            <?php wp_nonce_field('preetidreams_setup'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Sample Data</th>
                    <td>
                        <button type="submit" name="create_sample_data" class="button button-primary">
                            Create Sample Treatments
                        </button>
                        <p class="description">
                            This will create 12 sample treatments with categories, areas, and metadata for demonstration purposes.
                        </p>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php
}

/**
 * Helper functions for theme customization data
 */
function preetidreams_get_phone() {
    return get_theme_mod('preetidreams_phone', '');
}

function preetidreams_get_email() {
    return get_theme_mod('preetidreams_email', '');
}

function preetidreams_get_address() {
    return get_theme_mod('preetidreams_address', '');
}

function preetidreams_get_hours() {
    return get_theme_mod('preetidreams_hours', '');
}

function preetidreams_get_social_link($platform) {
    return get_theme_mod("preetidreams_social_{$platform}", '');
}
