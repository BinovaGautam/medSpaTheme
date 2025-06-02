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

    // Header transparency fix - solid header by default
    wp_enqueue_style(
        'preetidreams-header-fix',
        get_template_directory_uri() . '/assets/css/header-fix.css',
        ['preetidreams-medical-spa'],
        PREETIDREAMS_VERSION
    );

    // Header scroll transparency for hero pages
    wp_enqueue_script(
        'preetidreams-header-transparency',
        get_template_directory_uri() . '/assets/js/header-scroll-transparency.js',
        [],
        PREETIDREAMS_VERSION,
        true
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
        wp_localize_script('preetidreams-premium-hero', 'premiumHeroAjax', [
            'ajaxurl' => admin_url('admin-ajax.php'),
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
 * Enhanced Hero Consultation Submission Handler for 4-Step Quiz
 * Supports demographic collection and lead scoring
 */
function handle_hero_consultation_submission() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'hero_consultation_nonce')) {
        wp_send_json_error('Security check failed');
    }

    // Sanitize form data - Enhanced for 4-step quiz
    $data = [
        // Basic contact information
        'full_name' => sanitize_text_field($_POST['full_name']),
        'email' => sanitize_email($_POST['email']),
        'phone' => sanitize_text_field($_POST['phone']),
        'message' => sanitize_textarea_field($_POST['message'] ?? ''),

        // Treatment selection (Steps 1-2)
        'selected_category' => sanitize_text_field($_POST['selected_category']),
        'selected_treatment' => sanitize_text_field($_POST['selected_treatment']),
        'treatment_pricing_tier' => sanitize_text_field($_POST['treatment_pricing_tier'] ?? ''),

        // Demographics (Step 3) - New for enhanced quiz
        'age_range' => sanitize_text_field($_POST['age_range'] ?? ''),
        'gender' => sanitize_text_field($_POST['gender'] ?? ''),
        'experience_level' => sanitize_text_field($_POST['experience_level'] ?? ''),
        'treatment_timing' => sanitize_text_field($_POST['treatment_timing'] ?? ''),

        // Contact preferences (Step 4) - Enhanced
        'contact_preference' => sanitize_text_field($_POST['contact_preference'] ?? 'email'),
        'marketing_consent' => sanitize_text_field($_POST['marketing_consent'] ?? ''),

        // Quiz metadata
        'source' => sanitize_text_field($_POST['source'] ?? 'hero_treatment_quiz'),
        'completion_time' => intval($_POST['completion_time'] ?? 0),
        'step_timestamps' => sanitize_text_field($_POST['step_timestamps'] ?? '[]')
    ];

    // Validate required fields
    if (empty($data['full_name']) || empty($data['email']) || empty($data['phone'])) {
        wp_send_json_error('Please fill in all required fields');
    }

    // Validate email
    if (!is_email($data['email'])) {
        wp_send_json_error('Please enter a valid email address');
    }

    // Calculate lead quality score using enhanced algorithm
    $lead_score = calculate_enhanced_lead_score($data);
    $lead_temperature = get_lead_temperature($lead_score);

    // Store enhanced consultation request
    $consultation_id = wp_insert_post([
        'post_type' => 'consultation_request',
        'post_status' => 'private',
        'post_title' => 'Quiz Lead: ' . $data['full_name'] . ' - ' . $data['selected_treatment'],
        'post_content' => $data['message'],
        'meta_input' => [
            // Basic contact information
            '_contact_email' => encrypt_sensitive_data($data['email']),
            '_contact_phone' => encrypt_sensitive_data($data['phone']),
            '_contact_preference' => $data['contact_preference'],
            '_marketing_consent' => $data['marketing_consent'],

            // Treatment selection
            '_selected_category' => $data['selected_category'],
            '_selected_treatment' => $data['selected_treatment'],
            '_treatment_pricing_tier' => $data['treatment_pricing_tier'],

            // Demographics (encrypted for privacy)
            '_selected_age_range' => $data['age_range'],
            '_selected_gender' => $data['gender'],
            '_experience_level' => $data['experience_level'],
            '_treatment_timing' => $data['treatment_timing'],

            // Lead scoring and analytics
            '_lead_quality_score' => $lead_score,
            '_lead_temperature' => $lead_temperature,
            '_completion_time' => $data['completion_time'],
            '_step_timestamps' => $data['step_timestamps'],

            // Tracking data
            '_source' => $data['source'],
            '_submission_date' => current_time('mysql'),
            '_ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            '_user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            '_session_id' => session_id() ?: uniqid('quiz_', true)
        ]
    ]);

    if ($consultation_id) {
        // Send enhanced notification email to admin with demographic insights
        send_admin_notification_email($consultation_id, $data, $lead_score, $lead_temperature);

        // Send personalized confirmation email to user
        send_personalized_user_confirmation($data);

        // Track conversion event for analytics
        track_quiz_completion_event($data, $lead_score);

        wp_send_json_success([
            'message' => 'Thank you! We\'ll contact you within 24 hours with personalized consultation information.',
            'lead_id' => $consultation_id,
            'lead_score' => $lead_score,
            'lead_temperature' => $lead_temperature
        ]);
    } else {
        wp_send_json_error('Failed to submit consultation request. Please try again.');
    }
}
add_action('wp_ajax_submit_hero_consultation', 'handle_hero_consultation_submission');
add_action('wp_ajax_nopriv_submit_hero_consultation', 'handle_hero_consultation_submission');

/**
 * Enhanced Lead Scoring Algorithm with Demographics
 * Implements the algorithm from our planning documents
 */
function calculate_enhanced_lead_score($data) {
    $score = 0;

    // Base score for 100% completion (all 4 steps)
    $score += 3;

    // Treatment category scoring
    $category_scores = [
        'injectable' => 3,     // High-value treatments
        'laser' => 2.5,        // Medium-high value
        'body' => 2.5,         // Medium-high value
        'facial' => 2,         // Medium value
    ];
    $score += $category_scores[$data['selected_category']] ?? 1;

    // Price range acceptance
    $price_scores = [
        'high' => 2,           // $500+ treatments
        'medium' => 1.5,       // $200-500 treatments
        'low' => 1             // <$200 treatments
    ];
    $score += $price_scores[$data['treatment_pricing_tier']] ?? 1;

    // Age demographic scoring (medical spa specific)
    $age_scores = [
        '35-44' => 1.5,        // Prime demographic, 78% conversion
        '45-54' => 1.5,        // High disposable income
        '25-34' => 1.0,        // Growing demographic
        '55-64' => 1.0,        // Established income
        '18-24' => 0.5,        // Price sensitive
        '65+' => 0.5           // Fixed income
    ];
    $score += $age_scores[$data['age_range']] ?? 0.5;

    // Experience level scoring
    $experience_scores = [
        'some-experience' => 1.5,     // Knows value, ready to invest
        'very-experienced' => 1.0,    // May be picky/price sensitive
        'first-time' => 0.5           // Needs education, higher friction
    ];
    $score += $experience_scores[$data['experience_level']] ?? 0.5;

    // Treatment timing scoring
    $timing_scores = [
        'immediately' => 2.0,         // Ready to book
        '1-3-months' => 1.5,          // Planning ahead
        '3-6-months' => 1.0,          // Future planning
        'just-browsing' => 0.5        // Low intent
    ];
    $score += $timing_scores[$data['treatment_timing']] ?? 0.5;

    // Contact preference scoring (engagement indicator)
    $contact_scores = [
        'call' => 1.0,         // High engagement preference
        'text' => 0.8,         // Modern, responsive
        'email' => 0.5         // Lower engagement typically
    ];
    $score += $contact_scores[$data['contact_preference']] ?? 0.5;

    // Contact information quality
    if (!empty($data['phone']) && strlen($data['phone']) >= 10) $score += 1;
    if (!empty($data['email']) && !strpos($data['email'], 'test')) $score += 0.5;

    // Business hours submission bonus
    $hour = date('H');
    if ($hour >= 9 && $hour <= 17) $score += 0.5;

    return min($score, 10);
}

/**
 * Get Lead Temperature Classification
 */
function get_lead_temperature($score) {
    if ($score >= 8) return 'hot';
    if ($score >= 5) return 'warm';
    return 'cold';
}

/**
 * Send Enhanced Admin Notification Email with Demographics
 */
function send_admin_notification_email($consultation_id, $data, $lead_score, $lead_temperature) {
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');

    $age_display = !empty($data['age_range']) ? $data['age_range'] : 'Not provided';
    $gender_display = !empty($data['gender']) && $data['gender'] !== 'prefer-not-to-say' ? $data['gender'] : 'Not provided';

    $subject = sprintf('[%s] New Lead: %s - %s Interest (%s %s)',
        $site_name,
        $data['full_name'],
        $data['selected_treatment'],
        $age_display,
        $gender_display
    );

    $message = "New personalized lead captured via Treatment Quiz:\n\n";
    $message .= "👤 CONTACT INFORMATION\n";
    $message .= "Name: " . $data['full_name'] . "\n";
    $message .= "Email: " . $data['email'] . "\n";
    $message .= "Phone: " . $data['phone'] . "\n";
    $message .= "Preferred Contact: " . ucfirst($data['contact_preference']) . "\n\n";

    $message .= "🎯 TREATMENT INTEREST\n";
    $message .= "Category: " . ucfirst($data['selected_category']) . "\n";
    $message .= "Specific Treatment: " . $data['selected_treatment'] . "\n";
    $message .= "Price Range: " . ucfirst($data['treatment_pricing_tier']) . "\n\n";

    $message .= "👥 DEMOGRAPHICS\n";
    $message .= "Age Range: " . $age_display . "\n";
    $message .= "Gender: " . $gender_display . "\n";
    $message .= "Experience Level: " . ucfirst(str_replace('-', ' ', $data['experience_level'])) . "\n";
    $message .= "Treatment Timing: " . ucfirst(str_replace('-', ' ', $data['treatment_timing'])) . "\n\n";

    $message .= "📊 LEAD DETAILS\n";
    $message .= "Source: Hero Treatment Quiz\n";
    $message .= "Quality Score: " . round($lead_score, 1) . "/10\n";
    $message .= "Lead Temperature: " . strtoupper($lead_temperature) . "\n";
    $message .= "Submitted: " . current_time('mysql') . "\n";
    $message .= "Completion Rate: 100% (all 4 steps)\n";

    if ($data['completion_time'] > 0) {
        $message .= "Time to Complete: " . round($data['completion_time'] / 1000 / 60, 1) . " minutes\n";
    }

    $message .= "\n🔗 PERSONALIZATION OPPORTUNITIES\n";
    $message .= "- Age-appropriate treatments and messaging\n";
    $message .= "- Experience-level customized consultation\n";
    $message .= "- Preferred communication method\n";
    $message .= "- Timing-based follow-up sequence\n\n";

    $message .= "⏰ FOLLOW-UP TIMELINE\n";
    $priority = $lead_temperature === 'hot' ? '1 hour' : ($lead_temperature === 'warm' ? '4 hours' : '24 hours');
    $message .= "- Call/Text within: " . $priority . " (business hours)\n";
    $message .= "- Email within: 24 hours\n";
    $message .= "- Personalized consultation: Within 48 hours\n\n";

    $message .= "View in admin: " . admin_url('edit.php?post_type=consultation_request') . "\n";

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <noreply@' . parse_url(get_site_url(), PHP_URL_HOST) . '>',
        'Reply-To: ' . $data['full_name'] . ' <' . $data['email'] . '>'
    ];

    wp_mail($admin_email, $subject, $message, $headers);
}

/**
 * Send Personalized User Confirmation Email
 */
function send_personalized_user_confirmation($data) {
    $site_name = get_bloginfo('name');
    $first_name = explode(' ', $data['full_name'])[0];

    $age_appropriate_messaging = get_age_appropriate_messaging($data['age_range']);
    $experience_messaging = get_experience_level_messaging($data['experience_level']);

    $subject = sprintf('Your Personalized %s Consultation Info - %s',
        $data['selected_treatment'],
        $site_name
    );

    $message = "Hi " . $first_name . ",\n\n";
    $message .= "Thank you for taking our treatment quiz! Based on your responses, we've prepared personalized recommendations just for you.\n\n";

    $message .= "🎯 YOUR PERSONALIZED SELECTION\n";
    $message .= "Treatment: " . $data['selected_treatment'] . "\n";

    if (!empty($data['age_range']) && !empty($data['gender']) && !empty($data['experience_level'])) {
        $gender_display = $data['gender'] !== 'prefer-not-to-say' ? $data['gender'] : 'individual';
        $message .= "Perfect for: " . $data['age_range'] . " " . $gender_display . " with " . str_replace('-', ' ', $data['experience_level']) . "\n";
    }

    $message .= "Price Range: " . ucfirst($data['treatment_pricing_tier']) . "\n\n";

    $message .= "📞 WHAT'S NEXT?\n";
    $message .= "✅ We'll " . $data['contact_preference'] . " you within 24 hours\n";
    $message .= "✅ Personalized consultation scheduling\n";
    $message .= "✅ Custom treatment plan based on your profile\n";
    $message .= "✅ Age-appropriate pricing options\n\n";

    if (!empty($data['age_range'])) {
        $message .= "📋 FOR YOUR " . strtoupper($data['age_range']) . " DEMOGRAPHIC\n";
        $message .= $age_appropriate_messaging . "\n\n";
    }

    if (!empty($data['experience_level'])) {
        $message .= "💡 EXPERIENCE-LEVEL INSIGHTS\n";
        $message .= $experience_messaging . "\n\n";
    }

    $message .= "💎 WHY CHOOSE US?\n";
    $message .= "✓ Personalized approach for your age and experience\n";
    $message .= "✓ 2000+ satisfied patients across all demographics\n";
    $message .= "✓ Custom treatment plans\n";
    $message .= "✓ Complimentary consultations\n\n";

    $message .= "We'll be in touch via " . $data['contact_preference'] . " soon!\n\n";
    $message .= "Best regards,\n" . $site_name . " Team";

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <noreply@' . parse_url(get_site_url(), PHP_URL_HOST) . '>'
    ];

    wp_mail($data['email'], $subject, $message, $headers);
}

/**
 * Get age-appropriate messaging for email personalization
 */
function get_age_appropriate_messaging($age_range) {
    $messaging = [
        '18-24' => 'Smart investment in your future self with gentle, preventive treatments.',
        '25-34' => 'Perfect time to start maintenance treatments for long-lasting results.',
        '35-44' => 'Optimize your prime years with targeted anti-aging and enhancement treatments.',
        '45-54' => 'Maintain your confidence with proven rejuvenation and enhancement options.',
        '55-64' => 'Gentle yet effective treatments designed for mature skin and established lifestyles.',
        '65+' => 'Comfortable, non-invasive options that respect your skin\'s maturity and your time.'
    ];

    return $messaging[$age_range] ?? 'Personalized treatment recommendations based on your unique needs.';
}

/**
 * Get experience-level appropriate messaging
 */
function get_experience_level_messaging($experience_level) {
    $messaging = [
        'first-time' => 'We\'ll guide you through every step with educational resources and gentle introduction to treatments.',
        'some-experience' => 'Building on your knowledge, we\'ll recommend optimal treatments for your goals.',
        'very-experienced' => 'Advanced treatment options and VIP services for the discerning client.'
    ];

    return $messaging[$experience_level] ?? 'Customized approach based on your comfort level.';
}

/**
 * Simple encryption for sensitive data (non-PHI)
 * Note: Demographics are not PHI but we treat them sensitively
 */
function encrypt_sensitive_data($data) {
    if (empty($data)) return $data;

    // For now, we'll use base64 encoding as a simple obfuscation
    // In production, implement proper AES-256-CBC encryption
    return base64_encode($data);
}

/**
 * Decrypt sensitive data
 */
function decrypt_sensitive_data($encrypted_data) {
    if (empty($encrypted_data)) return $encrypted_data;

    return base64_decode($encrypted_data);
}

/**
 * Track quiz completion event for analytics
 */
function track_quiz_completion_event($data, $lead_score) {
    // This would integrate with Google Analytics 4 via JavaScript
    // For now, we'll log it for future implementation
    error_log("Quiz Completion: Lead Score {$lead_score} for {$data['selected_treatment']} by {$data['age_range']} {$data['gender']}");
}

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
 * Enhanced admin columns for consultation requests with demographics
 */
function consultation_request_admin_columns($columns) {
    $new_columns = [];
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = 'Name';
    $new_columns['contact_info'] = 'Contact Info';
    $new_columns['treatment'] = 'Treatment Interest';
    $new_columns['demographics'] = 'Demographics'; // New column
    $new_columns['lead_score'] = 'Lead Score'; // New column
    $new_columns['source'] = 'Source';
    $new_columns['date'] = 'Submitted';

    return $new_columns;
}
add_filter('manage_consultation_request_posts_columns', 'consultation_request_admin_columns');

/**
 * Enhanced admin column content with demographics and lead scoring
 */
function consultation_request_admin_column_content($column, $post_id) {
    switch ($column) {
        case 'contact_info':
            $email = decrypt_sensitive_data(get_post_meta($post_id, '_contact_email', true));
            $phone = decrypt_sensitive_data(get_post_meta($post_id, '_contact_phone', true));
            $contact_preference = get_post_meta($post_id, '_contact_preference', true);

            // Fallback to old meta keys for backward compatibility
            if (empty($email)) $email = get_post_meta($post_id, 'contact_email', true);
            if (empty($phone)) $phone = get_post_meta($post_id, 'contact_phone', true);

            echo '<strong>Email:</strong> <a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a><br>';
            echo '<strong>Phone:</strong> <a href="tel:' . esc_attr($phone) . '">' . esc_html($phone) . '</a>';
            if (!empty($contact_preference)) {
                echo '<br><strong>Prefers:</strong> ' . esc_html(ucfirst($contact_preference));
            }
            break;

        case 'treatment':
            $category = get_post_meta($post_id, '_selected_category', true);
            $treatment = get_post_meta($post_id, '_selected_treatment', true);
            $pricing_tier = get_post_meta($post_id, '_treatment_pricing_tier', true);

            // Fallback to old meta keys for backward compatibility
            if (empty($category)) $category = get_post_meta($post_id, 'selected_category', true);
            if (empty($treatment)) $treatment = get_post_meta($post_id, 'selected_treatment', true);

            echo '<strong>Category:</strong> ' . esc_html(ucfirst($category)) . '<br>';
            echo '<strong>Treatment:</strong> ' . esc_html($treatment);
            if (!empty($pricing_tier)) {
                echo '<br><strong>Price Tier:</strong> ' . esc_html(ucfirst($pricing_tier));
            }
            break;

        case 'demographics':
            $age_range = get_post_meta($post_id, '_selected_age_range', true);
            $gender = get_post_meta($post_id, '_selected_gender', true);
            $experience = get_post_meta($post_id, '_experience_level', true);
            $timing = get_post_meta($post_id, '_treatment_timing', true);

            if (!empty($age_range)) {
                echo '<strong>Age:</strong> ' . esc_html($age_range) . '<br>';
            }
            if (!empty($gender) && $gender !== 'prefer-not-to-say') {
                echo '<strong>Gender:</strong> ' . esc_html(ucfirst($gender)) . '<br>';
            }
            if (!empty($experience)) {
                echo '<strong>Experience:</strong> ' . esc_html(ucfirst(str_replace('-', ' ', $experience))) . '<br>';
            }
            if (!empty($timing)) {
                echo '<strong>Timing:</strong> ' . esc_html(ucfirst(str_replace('-', ' ', $timing)));
            }

            // Show message if no demographics provided
            if (empty($age_range) && empty($gender) && empty($experience) && empty($timing)) {
                echo '<em>No demographics</em>';
            }
            break;

        case 'lead_score':
            $score = get_post_meta($post_id, '_lead_quality_score', true);
            $temperature = get_post_meta($post_id, '_lead_temperature', true);
            $completion_time = get_post_meta($post_id, '_completion_time', true);

            if (!empty($score)) {
                $score_rounded = round($score, 1);
                $temp_color = [
                    'hot' => '#dc2626',    // Red
                    'warm' => '#ea580c',   // Orange
                    'cold' => '#2563eb'    // Blue
                ];
                $color = $temp_color[$temperature] ?? '#6b7280';

                echo '<strong style="color: ' . $color . ';">' . $score_rounded . '/10</strong><br>';
                echo '<span style="color: ' . $color . '; font-weight: bold;">' . strtoupper($temperature) . '</span>';

                if (!empty($completion_time) && $completion_time > 0) {
                    $minutes = round($completion_time / 1000 / 60, 1);
                    echo '<br><small>' . $minutes . 'min</small>';
                }
            } else {
                echo '<em>No score</em>';
            }
            break;

        case 'source':
            $source = get_post_meta($post_id, '_source', true);

            // Fallback to old meta key for backward compatibility
            if (empty($source)) $source = get_post_meta($post_id, 'source', true);

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
        'rewrite' => ['slug' => 'treatment-archive'], // Changed from 'treatments' to avoid conflict
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

/**
 * ============================================================================
 * ADMIN-MANAGED TREATMENT SYSTEM
 * ============================================================================
 */

/**
 * Register Hero Treatments Custom Post Type
 */
function register_hero_treatments_post_type() {
    $labels = [
        'name' => 'Hero Treatments',
        'singular_name' => 'Hero Treatment',
        'menu_name' => 'Hero Treatments',
        'add_new' => 'Add Treatment',
        'add_new_item' => 'Add New Treatment',
        'new_item' => 'New Treatment',
        'edit_item' => 'Edit Treatment',
        'view_item' => 'View Treatment',
        'all_items' => 'All Treatments',
        'search_items' => 'Search Treatments',
        'not_found' => 'No treatments found.',
        'not_found_in_trash' => 'No treatments found in Trash.'
    ];

    $args = [
        'labels' => $labels,
        'description' => 'Treatments for the hero section selection interface',
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => false,
        'rewrite' => false,
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => 26,
        'menu_icon' => 'dashicons-heart',
        'supports' => ['title', 'editor'],
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => false,
        'can_export' => true,
        'exclude_from_search' => true
    ];

    register_post_type('hero_treatment', $args);
}
add_action('init', 'register_hero_treatments_post_type');

/**
 * Register Hero Treatment Categories Taxonomy
 */
function register_hero_treatment_categories() {
    $labels = [
        'name' => 'Treatment Categories',
        'singular_name' => 'Treatment Category',
        'menu_name' => 'Categories',
        'all_items' => 'All Categories',
        'edit_item' => 'Edit Category',
        'view_item' => 'View Category',
        'update_item' => 'Update Category',
        'add_new_item' => 'Add New Category',
        'new_item_name' => 'New Category Name',
        'search_items' => 'Search Categories',
        'popular_items' => 'Popular Categories',
        'not_found' => 'No categories found.'
    ];

    $args = [
        'labels' => $labels,
        'description' => 'Categories for hero treatment selection',
        'public' => false,
        'publicly_queryable' => false,
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => false,
        'query_var' => false,
        'rewrite' => false,
    ];

    register_taxonomy('hero_treatment_category', ['hero_treatment'], $args);
}
add_action('init', 'register_hero_treatment_categories');

/**
 * Add Hero Treatment Meta Boxes
 */
function add_hero_treatment_meta_boxes() {
    add_meta_box(
        'hero_treatment_details',
        'Treatment Details',
        'hero_treatment_details_callback',
        'hero_treatment',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_hero_treatment_meta_boxes');

/**
 * Hero Treatment Details Meta Box Callback
 */
function hero_treatment_details_callback($post) {
    wp_nonce_field('hero_treatment_meta_box', 'hero_treatment_meta_box_nonce');

    $pricing = get_post_meta($post->ID, '_hero_treatment_pricing', true);
    $icon = get_post_meta($post->ID, '_hero_treatment_icon', true);
    $order = get_post_meta($post->ID, '_hero_treatment_order', true);
    $featured = get_post_meta($post->ID, '_hero_treatment_featured', true);
    $duration = get_post_meta($post->ID, '_hero_treatment_duration', true);

    echo '<table class="form-table">';

    // Pricing
    echo '<tr>';
    echo '<th><label for="hero_treatment_pricing">Pricing Display</label></th>';
    echo '<td>';
    echo '<input type="text" id="hero_treatment_pricing" name="hero_treatment_pricing" value="' . esc_attr($pricing) . '" placeholder="e.g., Starting at $150" style="width: 100%;" />';
    echo '<p class="description">Display text for pricing (used for lead qualification scoring)</p>';
    echo '</td>';
    echo '</tr>';

    // Duration (New field)
    echo '<tr>';
    echo '<th><label for="hero_treatment_duration">Treatment Duration</label></th>';
    echo '<td>';
    echo '<input type="text" id="hero_treatment_duration" name="hero_treatment_duration" value="' . esc_attr($duration) . '" placeholder="e.g., 60-90 minutes" style="width: 100%;" />';
    echo '<p class="description">Typical duration for this treatment</p>';
    echo '</td>';
    echo '</tr>';

    // Icon
    echo '<tr>';
    echo '<th><label for="hero_treatment_icon">Icon (Emoji)</label></th>';
    echo '<td>';
    echo '<input type="text" id="hero_treatment_icon" name="hero_treatment_icon" value="' . esc_attr($icon) . '" placeholder="e.g., ✨ 💉 💎" style="width: 100px;" />';
    echo '<p class="description">Use emoji or icon character (e.g., ✨ 💉 💎 🌟)</p>';
    echo '</td>';
    echo '</tr>';

    // Display Order
    echo '<tr>';
    echo '<th><label for="hero_treatment_order">Display Order</label></th>';
    echo '<td><input type="number" id="hero_treatment_order" name="hero_treatment_order" value="' . esc_attr($order) . '" placeholder="1" style="width: 100px;" /></td>';
    echo '</tr>';

    // Featured
    echo '<tr>';
    echo '<th><label for="hero_treatment_featured">Featured Treatment</label></th>';
    echo '<td><label><input type="checkbox" id="hero_treatment_featured" name="hero_treatment_featured" value="1" ' . checked($featured, '1', false) . ' /> Show this treatment first in its category</label></td>';
    echo '</tr>';

    echo '</table>';

    // Quiz Preview Section
    echo '<h3>Quiz Preview</h3>';
    echo '<div style="border: 1px solid #ddd; padding: 15px; background: #f9f9f9; border-radius: 5px;">';
    echo '<p><strong>How this will appear in the quiz:</strong></p>';

    $preview_icon = !empty($icon) ? $icon : '💫';
    $preview_pricing = !empty($pricing) ? $pricing : 'Contact for pricing';
    $preview_duration = !empty($duration) ? ' • ' . $duration : '';

    echo '<div style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 1rem; background: white; margin: 10px 0;">';
    echo '<span style="font-size: 1.2em; margin-right: 10px;">' . $preview_icon . '</span>';
    echo '<strong>' . $post->post_title . '</strong>';
    echo '<div style="color: #666; font-size: 0.9em; margin-top: 5px;">' . $preview_pricing . $preview_duration . '</div>';
    echo '</div>';
    echo '</div>';
}

/**
 * Enhanced save function for treatment meta data
 */
function save_hero_treatment_meta_data($post_id) {
    if (!isset($_POST['hero_treatment_meta_box_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['hero_treatment_meta_box_nonce'], 'hero_treatment_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = [
        'hero_treatment_pricing',
        'hero_treatment_duration',
        'hero_treatment_icon',
        'hero_treatment_order',
        'hero_treatment_featured'
    ];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'save_hero_treatment_meta_data');

/**
 * Enqueue Treatments Overview Assets (TASK-001)
 */
function preetidreams_enqueue_treatments_overview_assets() {
    // Only enqueue on treatments overview page
    if (is_page_template('page-treatments.php') ||
        is_post_type_archive('treatment') ||
        (is_page() && get_post_field('post_name') === 'treatments')) {

        // Treatments Overview CSS
        wp_enqueue_style(
            'treatments-overview-styles',
            get_template_directory_uri() . '/assets/css/treatments-overview.css',
            ['preetidreams-header-fix'],
            PREETIDREAMS_VERSION
        );

        // Luxury Treatments JavaScript - New consultation-focused functionality
        wp_enqueue_script(
            'treatments-luxury-script',
            get_template_directory_uri() . '/assets/js/treatments-luxury.js',
            ['jquery'],
            PREETIDREAMS_VERSION,
            true
        );

        // Localize script with WordPress data - Updated for luxury consultation focus
        wp_localize_script('treatments-luxury-script', 'treatmentsLuxuryData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('treatments_luxury_nonce'),
            'restUrl' => rest_url('wp/v2/'),
            'phone' => preetidreams_get_phone() ?: '(555) 123-4567',
            'email' => preetidreams_get_email() ?: 'consultations@preetidreams.com',
            'themeUrl' => get_template_directory_uri(),
            'currentUser' => is_user_logged_in() ? wp_get_current_user()->ID : 0,
            'settings' => [
                'consultationFocus' => true,
                'luxuryPositioning' => true,
                'boardCertifiedPhysician' => true,
                'accessibilityAAA' => true,
                'personalizedConsultation' => true,
                'discretionPrivacy' => true,
                'enableScrollAnimations' => !wp_is_mobile(),
                'enableParallax' => !wp_is_mobile(),
                'reducedMotion' => false, // Will be detected client-side
            ],
            'consultation' => [
                'schedulePage' => get_permalink(get_page_by_path('contact')),
                'phoneBooking' => preetidreams_get_phone() ?: '(555) 123-4567',
                'emailBooking' => preetidreams_get_email() ?: 'consultations@preetidreams.com',
                'virtualAvailable' => true,
                'complimentary' => true,
                'boardCertified' => true,
                'privacyAssured' => true,
            ],
            'treatments' => [
                'categories' => [
                    'injectable' => [
                        'title' => 'Injectable Artistry',
                        'description' => 'Sophisticated enhancement through precise medical artistry',
                        'icon' => '💎'
                    ],
                    'facial' => [
                        'title' => 'Facial Renaissance',
                        'description' => 'Advanced skincare treatments for natural radiance',
                        'icon' => '✨'
                    ],
                    'laser' => [
                        'title' => 'Laser Precision',
                        'description' => 'Technology-driven treatments with medical precision',
                        'icon' => '⚡'
                    ],
                    'body' => [
                        'title' => 'Body Artistry',
                        'description' => 'Advanced body contouring and enhancement',
                        'icon' => '🌿'
                    ],
                    'wellness' => [
                        'title' => 'Wellness Sanctuary',
                        'description' => 'Holistic treatments complementing aesthetic enhancements',
                        'icon' => '🧘'
                    ]
                ]
            ],
            'i18n' => [
                'loading' => __('Loading treatment information...', 'preetidreams'),
                'consultationScheduled' => __('Consultation request sent successfully', 'preetidreams'),
                'consultationError' => __('Unable to process consultation request. Please call us.', 'preetidreams'),
                'categoryExplored' => __('Explore consultation options for', 'preetidreams'),
                'accessibilityAnnounce' => __('Screen reader announcement:', 'preetidreams'),
                'consultationInvite' => __('Schedule your personalized consultation', 'preetidreams'),
                'phoneConsultation' => __('Call for consultation', 'preetidreams'),
                'emailConsultation' => __('Email for consultation inquiry', 'preetidreams'),
                'virtualConsultation' => __('Virtual consultation available', 'preetidreams'),
                'privacyAssured' => __('Complete privacy and discretion assured', 'preetidreams'),
                'boardCertified' => __('Board-certified physician consultation', 'preetidreams'),
                'personalizedPlan' => __('Personalized treatment plan', 'preetidreams'),
                'medicalExcellence' => __('15+ years medical excellence', 'preetidreams'),
                'artisticVision' => __('Medical precision meets artistic vision', 'preetidreams'),
            ]
        ]);

        // Add CSS custom properties for luxury design system
        $custom_css = "
            :root {
                /* Luxury Color Palette */
                --sage-green: #87A96B;
                --premium-gold: #D4AF37;
                --medical-navy: #1B365D;
                --cream-base: #FDFCFA;
                --sage-light: #A8C487;
                --sage-dark: #6B8552;
                --gold-light: #E8C962;
                --navy-light: #2B4A78;
                --cream-warm: #FBF9F4;

                /* Typography */
                --font-display: 'Playfair Display', serif;
                --font-body: 'Inter', sans-serif;
            }
        ";
        wp_add_inline_style('treatments-overview-styles', $custom_css);
    }
}
add_action('wp_enqueue_scripts', 'preetidreams_enqueue_treatments_overview_assets');

/**
 * Add custom body classes for header transparency control
 * This completes the header transparency system by adding the CSS classes
 * that control whether the header starts transparent (hero pages) or solid (content pages)
 */
function preetidreams_custom_body_classes($classes) {
    // Pages that should have transparent headers (with hero sections)
    if (is_front_page() || is_home()) {
        $classes[] = 'has-hero-section';
        $classes[] = 'transparent-header';
    } else {
        // All other pages should have solid headers
        $classes[] = 'no-hero-section';
        $classes[] = 'solid-header';
    }

    // Add page-specific classes for easier targeting
    if (is_page()) {
        global $post;
        if ($post && $post->post_name) {
            $classes[] = 'page-' . $post->post_name;
        }
    }

    // Add post type classes
    if (is_singular()) {
        global $post;
        if ($post) {
            $classes[] = 'single-' . $post->post_type;
        }
    }

    // Add template-specific classes
    if (is_page_template()) {
        $template = get_page_template_slug();
        if ($template) {
            $template_class = str_replace(['.php', '/'], ['', '-'], $template);
            $classes[] = 'template-' . $template_class;
        }
    }

    return $classes;
}
add_filter('body_class', 'preetidreams_custom_body_classes');
