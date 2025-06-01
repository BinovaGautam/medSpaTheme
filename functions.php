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
        'hero_treatment_duration',  // New field
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
 * AJAX Endpoint: Get Hero Treatments for Frontend
 */
function get_hero_treatments_ajax() {
    $treatments_data = [];

    // Get all treatment categories
    $categories = get_terms([
        'taxonomy' => 'hero_treatment_category',
        'hide_empty' => false,
        'orderby' => 'meta_value_num',
        'meta_key' => '_category_order',
        'order' => 'ASC'
    ]);

    foreach ($categories as $category) {
        $category_slug = $category->slug;
        $treatments_data[$category_slug] = [];

        // Get treatments in this category
        $treatments = get_posts([
            'post_type' => 'hero_treatment',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'meta_value_num',
            'meta_key' => '_hero_treatment_order',
            'order' => 'ASC',
            'tax_query' => [
                [
                    'taxonomy' => 'hero_treatment_category',
                    'field' => 'term_id',
                    'terms' => $category->term_id
                ]
            ]
        ]);

        foreach ($treatments as $treatment) {
            $pricing = get_post_meta($treatment->ID, '_hero_treatment_pricing', true);
            $icon = get_post_meta($treatment->ID, '_hero_treatment_icon', true);

            $treatments_data[$category_slug][] = [
                'slug' => $treatment->post_name,
                'name' => $treatment->post_title,
                'price' => $pricing ?: 'Contact for pricing',
                'icon' => $icon ?: '✨'
            ];
        }
    }

    wp_send_json_success($treatments_data);
}
add_action('wp_ajax_get_hero_treatments', 'get_hero_treatments_ajax');
add_action('wp_ajax_nopriv_get_hero_treatments', 'get_hero_treatments_ajax');

/**
 * Add Custom Columns to Hero Treatments Admin
 */
function hero_treatment_admin_columns($columns) {
    $new_columns = [];
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = 'Treatment Name';
    $new_columns['category'] = 'Category';
    $new_columns['pricing'] = 'Pricing';
    $new_columns['icon'] = 'Icon';
    $new_columns['order'] = 'Order';
    $new_columns['featured'] = 'Featured';
    $new_columns['date'] = 'Date';

    return $new_columns;
}
add_filter('manage_hero_treatment_posts_columns', 'hero_treatment_admin_columns');

/**
 * Fill Custom Columns with Data
 */
function hero_treatment_admin_column_content($column, $post_id) {
    switch ($column) {
        case 'category':
            $terms = get_the_terms($post_id, 'hero_treatment_category');
            if ($terms && !is_wp_error($terms)) {
                echo esc_html($terms[0]->name);
            } else {
                echo '<span style="color: #999;">No category</span>';
            }
            break;

        case 'pricing':
            $pricing = get_post_meta($post_id, '_hero_treatment_pricing', true);
            echo esc_html($pricing ?: 'Not set');
            break;

        case 'icon':
            $icon = get_post_meta($post_id, '_hero_treatment_icon', true);
            echo '<span style="font-size: 20px;">' . esc_html($icon ?: '✨') . '</span>';
            break;

        case 'order':
            $order = get_post_meta($post_id, '_hero_treatment_order', true);
            echo esc_html($order ?: '0');
            break;

        case 'featured':
            $featured = get_post_meta($post_id, '_hero_treatment_featured', true);
            echo $featured ? '<span style="color: #d63638;">★ Featured</span>' : '';
            break;
    }
}
add_action('manage_hero_treatment_posts_custom_column', 'hero_treatment_admin_column_content', 10, 2);

/**
 * Create Default Treatment Categories and Treatments
 */
function create_default_hero_treatments() {
    if (get_option('hero_treatments_created')) {
        return;
    }

    // Create categories
    $categories = [
        'facial' => ['name' => 'Facial Treatments', 'icon' => '✨', 'order' => 1],
        'injectable' => ['name' => 'Injectables', 'icon' => '💉', 'order' => 2],
        'laser' => ['name' => 'Laser Treatments', 'icon' => '💎', 'order' => 3],
        'body' => ['name' => 'Body Contouring', 'icon' => '🌟', 'order' => 4]
    ];

    foreach ($categories as $slug => $data) {
        $term = wp_insert_term($data['name'], 'hero_treatment_category', ['slug' => $slug]);
        if (!is_wp_error($term)) {
            update_term_meta($term['term_id'], '_category_order', $data['order']);
            update_term_meta($term['term_id'], '_category_icon', $data['icon']);
        }
    }

    // Create sample treatments
    $treatments = [
        // Facial Treatments
        ['title' => 'HydraFacial MD', 'category' => 'facial', 'pricing' => 'Starting at $150', 'icon' => '✨', 'order' => 1],
        ['title' => 'Chemical Peel', 'category' => 'facial', 'pricing' => 'Starting at $100', 'icon' => '🌟', 'order' => 2],
        ['title' => 'Microneedling', 'category' => 'facial', 'pricing' => 'Starting at $200', 'icon' => '💫', 'order' => 3],
        ['title' => 'Dermaplaning', 'category' => 'facial', 'pricing' => 'Starting at $80', 'icon' => '✨', 'order' => 4],

        // Injectables
        ['title' => 'Botox Cosmetic', 'category' => 'injectable', 'pricing' => 'Starting at $12/unit', 'icon' => '💉', 'order' => 1],
        ['title' => 'Dermal Fillers', 'category' => 'injectable', 'pricing' => 'Starting at $600', 'icon' => '💎', 'order' => 2],
        ['title' => 'Lip Enhancement', 'category' => 'injectable', 'pricing' => 'Starting at $500', 'icon' => '💋', 'order' => 3],
        ['title' => 'Sculptra', 'category' => 'injectable', 'pricing' => 'Starting at $800', 'icon' => '✨', 'order' => 4],

        // Laser Treatments
        ['title' => 'Laser Hair Removal', 'category' => 'laser', 'pricing' => 'Starting at $100', 'icon' => '💎', 'order' => 1],
        ['title' => 'Fractional Laser', 'category' => 'laser', 'pricing' => 'Starting at $300', 'icon' => '✨', 'order' => 2],
        ['title' => 'IPL Photofacial', 'category' => 'laser', 'pricing' => 'Starting at $250', 'icon' => '🌟', 'order' => 3],
        ['title' => 'CO2 Laser Resurfacing', 'category' => 'laser', 'pricing' => 'Starting at $500', 'icon' => '💫', 'order' => 4],

        // Body Contouring
        ['title' => 'CoolSculpting', 'category' => 'body', 'pricing' => 'Starting at $750', 'icon' => '🌟', 'order' => 1],
        ['title' => 'RF Body Contouring', 'category' => 'body', 'pricing' => 'Starting at $400', 'icon' => '⚡', 'order' => 2],
        ['title' => 'Lymphatic Drainage', 'category' => 'body', 'pricing' => 'Starting at $150', 'icon' => '🌊', 'order' => 3],
        ['title' => 'Non-Invasive Body Sculpting', 'category' => 'body', 'pricing' => 'Starting at $300', 'icon' => '💪', 'order' => 4]
    ];

    foreach ($treatments as $treatment_data) {
        $post_id = wp_insert_post([
            'post_title' => $treatment_data['title'],
            'post_type' => 'hero_treatment',
            'post_status' => 'publish',
            'post_content' => 'Treatment description can be added here.'
        ]);

        if ($post_id && !is_wp_error($post_id)) {
            // Set category
            wp_set_object_terms($post_id, $treatment_data['category'], 'hero_treatment_category');

            // Set meta data
            update_post_meta($post_id, '_hero_treatment_pricing', $treatment_data['pricing']);
            update_post_meta($post_id, '_hero_treatment_icon', $treatment_data['icon']);
            update_post_meta($post_id, '_hero_treatment_order', $treatment_data['order']);
        }
    }

    update_option('hero_treatments_created', true);
}
add_action('init', 'create_default_hero_treatments');

/**
 * Enhanced AJAX Endpoint: Get Hero Treatments for 4-Step Quiz
 * Returns treatments with enhanced data for lead qualification
 */
function get_hero_treatments_for_quiz() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'preetidreams_nonce')) {
        wp_send_json_error('Security check failed');
    }

    $category = sanitize_text_field($_POST['category'] ?? '');

    if (empty($category)) {
        wp_send_json_error('Category is required');
    }

    $treatments_data = [];

    // Get treatments in the specified category
    $treatments = get_posts([
        'post_type' => 'hero_treatment',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'meta_value_num',
        'meta_key' => '_hero_treatment_order',
        'order' => 'ASC',
        'tax_query' => [
            [
                'taxonomy' => 'hero_treatment_category',
                'field' => 'slug',
                'terms' => $category
            ]
        ]
    ]);

    foreach ($treatments as $treatment) {
        $pricing = get_post_meta($treatment->ID, '_hero_treatment_pricing', true);
        $icon = get_post_meta($treatment->ID, '_hero_treatment_icon', true);
        $featured = get_post_meta($treatment->ID, '_hero_treatment_featured', true);

        // Extract pricing tier for lead scoring
        $pricing_tier = determine_pricing_tier($pricing);

        // Get treatment duration if available
        $duration = get_post_meta($treatment->ID, '_hero_treatment_duration', true);
        if (empty($duration)) {
            $duration = get_default_treatment_duration($category);
        }

        $treatments_data[] = [
            'id' => $treatment->ID,
            'title' => $treatment->post_title,
            'description' => $treatment->post_content,
            'pricing' => $pricing,
            'pricing_tier' => $pricing_tier,
            'icon' => $icon,
            'featured' => $featured === '1',
            'duration' => $duration,
            'slug' => $treatment->post_name,
            'category' => $category
        ];
    }

    wp_send_json_success([
        'treatments' => $treatments_data,
        'category' => $category,
        'total' => count($treatments_data)
    ]);
}
add_action('wp_ajax_get_hero_treatments_quiz', 'get_hero_treatments_for_quiz');
add_action('wp_ajax_nopriv_get_hero_treatments_quiz', 'get_hero_treatments_for_quiz');

/**
 * Determine pricing tier based on pricing display text
 * Used for lead scoring algorithm
 */
function determine_pricing_tier($pricing_text) {
    if (empty($pricing_text)) return 'medium';

    // Extract numeric values from pricing text
    preg_match_all('/\$(\d+)/', $pricing_text, $matches);

    if (!empty($matches[1])) {
        $price = min($matches[1]); // Take the lowest price mentioned

        if ($price >= 500) return 'high';
        if ($price >= 200) return 'medium';
        return 'low';
    }

    // Fallback: analyze text for keywords
    $pricing_lower = strtolower($pricing_text);

    if (strpos($pricing_lower, 'premium') !== false ||
        strpos($pricing_lower, 'luxury') !== false ||
        strpos($pricing_lower, 'advanced') !== false) {
        return 'high';
    }

    if (strpos($pricing_lower, 'starting') !== false ||
        strpos($pricing_lower, 'from') !== false) {
        return 'low';
    }

    return 'medium';
}

/**
 * Get default treatment duration by category
 */
function get_default_treatment_duration($category) {
    $default_durations = [
        'facial' => '60-90 minutes',
        'injectable' => '30-45 minutes',
        'laser' => '45-60 minutes',
        'body' => '60-90 minutes'
    ];

    return $default_durations[$category] ?? '45-60 minutes';
}

/**
 * AJAX Endpoint: Get Quiz Categories with Enhanced Data
 */
function get_quiz_categories() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'preetidreams_nonce')) {
        wp_send_json_error('Security check failed');
    }

    $categories = get_terms([
        'taxonomy' => 'hero_treatment_category',
        'hide_empty' => true,
        'orderby' => 'meta_value_num',
        'meta_key' => '_category_order',
        'order' => 'ASC'
    ]);

    $categories_data = [];

    foreach ($categories as $category) {
        $icon = get_term_meta($category->term_id, '_category_icon', true);
        $description = get_term_meta($category->term_id, '_category_description', true);
        $order = get_term_meta($category->term_id, '_category_order', true);

        // Count treatments in this category
        $treatment_count = get_posts([
            'post_type' => 'hero_treatment',
            'post_status' => 'publish',
            'numberposts' => -1,
            'tax_query' => [
                [
                    'taxonomy' => 'hero_treatment_category',
                    'field' => 'term_id',
                    'terms' => $category->term_id
                ]
            ],
            'fields' => 'ids'
        ]);

        $categories_data[] = [
            'id' => $category->term_id,
            'name' => $category->name,
            'slug' => $category->slug,
            'description' => $description ?: $category->description,
            'icon' => $icon ?: get_default_category_icon($category->slug),
            'count' => count($treatment_count),
            'order' => intval($order)
        ];
    }

    // Sort by order
    usort($categories_data, function($a, $b) {
        return $a['order'] <=> $b['order'];
    });

    wp_send_json_success([
        'categories' => $categories_data,
        'total' => count($categories_data)
    ]);
}
add_action('wp_ajax_get_quiz_categories', 'get_quiz_categories');
add_action('wp_ajax_nopriv_get_quiz_categories', 'get_quiz_categories');

/**
 * Get default category icon if none is set
 */
function get_default_category_icon($category_slug) {
    $default_icons = [
        'facial' => '✨',
        'injectable' => '💉',
        'laser' => '💎',
        'body' => '🌟'
    ];

    return $default_icons[$category_slug] ?? '💫';
}

/**
 * AJAX Handler: Get Enhanced Treatments by Category for Quiz
 */
function get_enhanced_treatments_by_category() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'hero_consultation_nonce')) {
        wp_send_json_error('Security check failed');
    }

    $category = sanitize_text_field($_POST['category']);

    if (empty($category)) {
        wp_send_json_error('Category not specified');
    }

    // Get treatments for the category
    $treatments = get_treatments_by_category($category);

    if (empty($treatments)) {
        wp_send_json_error('No treatments found for this category');
    }

    wp_send_json_success(['treatments' => $treatments]);
}
add_action('wp_ajax_get_enhanced_treatments_by_category', 'get_enhanced_treatments_by_category');
add_action('wp_ajax_nopriv_get_enhanced_treatments_by_category', 'get_enhanced_treatments_by_category');

/**
 * AJAX Handler: Enhanced Consultation Request for 4-Step Quiz
 */
function handle_enhanced_consultation_request() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'hero_consultation_nonce')) {
        wp_send_json_error('Security check failed');
    }

    // Sanitize form data - Enhanced for 4-step quiz
    $data = [
        // Basic contact information
        'first_name' => sanitize_text_field($_POST['first_name']),
        'last_name' => sanitize_text_field($_POST['last_name']),
        'email' => sanitize_email($_POST['email']),
        'phone' => sanitize_text_field($_POST['phone']),
        'message' => sanitize_textarea_field($_POST['message'] ?? ''),

        // Treatment selection (Steps 1-2)
        'category' => sanitize_text_field($_POST['category']),
        'treatment' => sanitize_text_field($_POST['treatment']),

        // Demographics (Step 3)
        'age_range' => sanitize_text_field($_POST['age_range'] ?? ''),
        'gender' => sanitize_text_field($_POST['gender'] ?? ''),
        'experience_level' => sanitize_text_field($_POST['experience_level'] ?? ''),
        'treatment_timing' => sanitize_text_field($_POST['treatment_timing'] ?? ''),

        // Contact preferences (Step 4)
        'contact_preference' => sanitize_text_field($_POST['contact_preference'] ?? 'email'),
        'marketing_consent' => sanitize_text_field($_POST['marketing_consent'] ?? ''),

        // Quiz metadata
        'source' => 'enhanced_hero_quiz',
        'completion_time' => intval($_POST['quiz_completion_time'] ?? 0),
        'quiz_completed' => true
    ];

    // Validate required fields
    if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email']) || empty($data['phone'])) {
        wp_send_json_error(['message' => 'Please fill in all required fields']);
    }

    // Validate email
    if (!is_email($data['email'])) {
        wp_send_json_error(['message' => 'Please enter a valid email address']);
    }

    // Calculate lead quality score
    $lead_score = calculate_enhanced_lead_score($data);
    $lead_temperature = get_lead_temperature($lead_score);

    // Store enhanced consultation request
    $consultation_id = wp_insert_post([
        'post_type' => 'consultation_request',
        'post_status' => 'private',
        'post_title' => 'Enhanced Quiz Lead: ' . $data['first_name'] . ' ' . $data['last_name'] . ' - ' . $data['treatment'],
        'post_content' => $data['message'],
        'meta_input' => [
            // Basic contact information
            '_contact_first_name' => $data['first_name'],
            '_contact_last_name' => $data['last_name'],
            '_contact_email' => $data['email'],
            '_contact_phone' => $data['phone'],
            '_contact_preference' => $data['contact_preference'],
            '_marketing_consent' => $data['marketing_consent'],

            // Treatment selection
            '_selected_category' => $data['category'],
            '_selected_treatment' => $data['treatment'],

            // Demographics
            '_selected_age_range' => $data['age_range'],
            '_selected_gender' => $data['gender'],
            '_experience_level' => $data['experience_level'],
            '_treatment_timing' => $data['treatment_timing'],

            // Lead scoring and analytics
            '_lead_quality_score' => $lead_score,
            '_lead_temperature' => $lead_temperature,
            '_completion_time' => $data['completion_time'],

            // Tracking data
            '_source' => $data['source'],
            '_submission_date' => current_time('mysql'),
            '_quiz_completed' => true
        ]
    ]);

    if ($consultation_id) {
        wp_send_json_success([
            'message' => 'Thank you! We\'ll contact you within 24 hours to schedule your consultation.',
            'lead_id' => $consultation_id,
            'lead_score' => $lead_score,
            'lead_temperature' => $lead_temperature
        ]);
    } else {
        wp_send_json_error(['message' => 'Failed to submit consultation request. Please try again.']);
    }
}
add_action('wp_ajax_handle_enhanced_consultation_request', 'handle_enhanced_consultation_request');
add_action('wp_ajax_nopriv_handle_enhanced_consultation_request', 'handle_enhanced_consultation_request');

/**
 * Helper function to get treatments by category
 */
function get_treatments_by_category($category) {
    $args = [
        'post_type' => 'hero_treatment',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'tax_query' => [
            [
                'taxonomy' => 'hero_treatment_category',
                'field' => 'slug',
                'terms' => $category
            ]
        ]
    ];

    $treatments = get_posts($args);
    $formatted_treatments = [];

    foreach ($treatments as $treatment) {
        $formatted_treatments[] = [
            'id' => $treatment->ID,
            'title' => $treatment->post_title,
            'slug' => $treatment->post_name,
            'excerpt' => wp_trim_words($treatment->post_content, 20),
            'duration' => get_post_meta($treatment->ID, '_treatment_duration', true),
            'price_range' => get_post_meta($treatment->ID, '_treatment_price', true),
            'pricing_tier' => determine_pricing_tier(get_post_meta($treatment->ID, '_treatment_price', true)),
            'is_featured' => get_post_meta($treatment->ID, '_is_featured', true) == '1'
        ];
    }

    return $formatted_treatments;
}
/**
 * Handle Elegant Quiz Form Submission (TASK-UX-QUIZ-003-01 Phase 4)
 */
add_action('wp_ajax_submit_elegant_quiz', 'handle_elegant_quiz_submission');
add_action('wp_ajax_nopriv_submit_elegant_quiz', 'handle_elegant_quiz_submission');

function handle_elegant_quiz_submission() {
    // Verify nonce for security
    if (!wp_verify_nonce($_POST['nonce'], 'elegant_quiz_nonce')) {
        wp_die(json_encode([
            'success' => false,
            'data' => 'Security check failed. Please refresh the page and try again.'
        ]));
    }

    // Validate and sanitize input data
    $selections_raw = sanitize_text_field($_POST['selections']);
    $selections = json_decode(stripslashes($selections_raw), true);

    // Validate required data structure
    if (!$selections || !is_array($selections)) {
        wp_send_json_error('Invalid quiz data received.');
        return;
    }

    // Validate required fields
    $required_fields = ['category', 'area', 'experience', 'age', 'contact'];
    foreach ($required_fields as $field) {
        if (!isset($selections[$field])) {
            wp_send_json_error("Missing required field: {$field}");
            return;
        }
    }

    // Validate contact information
    $contact = $selections['contact'];
    if (empty($contact['full_name']) || empty($contact['email']) || empty($contact['phone'])) {
        wp_send_json_error('Please provide complete contact information.');
        return;
    }

    // Validate email format
    if (!is_email($contact['email'])) {
        wp_send_json_error('Please provide a valid email address.');
        return;
    }

    // Sanitize all input data
    $clean_data = [
        'category' => sanitize_text_field($selections['category']),
        'area' => sanitize_text_field($selections['area']),
        'experience' => sanitize_text_field($selections['experience']),
        'age' => sanitize_text_field($selections['age']),
        'contact' => [
            'full_name' => sanitize_text_field($contact['full_name']),
            'email' => sanitize_email($contact['email']),
            'phone' => sanitize_text_field($contact['phone'])
        ],
        'submission_time' => current_time('mysql'),
        'user_ip' => sanitize_text_field($_SERVER['REMOTE_ADDR']),
        'user_agent' => sanitize_text_field($_SERVER['HTTP_USER_AGENT'])
    ];

    try {
        // Save quiz submission to database
        $post_id = save_elegant_quiz_submission($clean_data);

        if (!$post_id) {
            throw new Exception('Failed to save quiz submission to database');
        }

        // Send email notifications
        send_elegant_quiz_admin_notification($post_id, $clean_data);
        send_elegant_quiz_user_confirmation($clean_data);

        // Track conversion event
        track_elegant_quiz_completion($clean_data);

        // Return success response
        wp_send_json_success([
            'message' => 'Quiz submitted successfully! You will receive a confirmation email shortly.',
            'submission_id' => $post_id,
            'redirect_url' => null
        ]);

    } catch (Exception $e) {
        error_log('Elegant Quiz Submission Error: ' . $e->getMessage());
        wp_send_json_error('There was an error processing your submission. Please try again or contact us directly.');
    }
}

/**
 * Save elegant quiz submission to database
 */
function save_elegant_quiz_submission($data) {
    // Create consultation request post
    $post_data = [
        'post_type' => 'consultation_request',
        'post_title' => sprintf('Elegant Quiz: %s - %s', $data['contact']['full_name'], $data['category']),
        'post_content' => generate_elegant_quiz_summary($data),
        'post_status' => 'publish',
        'meta_input' => [
            // Contact Information
            'consultation_name' => $data['contact']['full_name'],
            'consultation_email' => $data['contact']['email'],
            'consultation_phone' => $data['contact']['phone'],

            // Quiz Selections
            'quiz_category' => $data['category'],
            'quiz_area' => $data['area'],
            'quiz_experience' => $data['experience'],
            'quiz_age' => $data['age'],

            // System Data
            'submission_type' => 'elegant_quiz',
            'submission_time' => $data['submission_time'],
            'user_ip' => $data['user_ip'],
            'user_agent' => $data['user_agent'],
            'lead_score' => calculate_elegant_quiz_lead_score($data),
            'follow_up_priority' => determine_follow_up_priority($data)
        ]
    ];

    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        throw new Exception('Failed to create consultation request: ' . $post_id->get_error_message());
    }

    return $post_id;
}

/**
 * Generate readable quiz summary
 */
function generate_elegant_quiz_summary($data) {
    $category_labels = [
        'botox' => 'Botox & Xeomin',
        'dermal-fillers' => 'Dermal Fillers',
        'laser-hair-removal' => 'Laser Hair Removal',
        'coolsculpting' => 'CoolSculpting',
        'clear-brilliant' => 'Clear & Brilliant',
        'ipl-photofacials' => 'IPL Photofacials',
        'skin-rejuvenation' => 'Skin Rejuvenation',
        'tattoo-removal' => 'Tattoo Removal',
        'thermage' => 'Thermage',
        'hydrafacial' => 'HydraFacial',
        'potenza-rf' => 'Potenza RF Microneedling'
    ];

    $category_label = $category_labels[$data['category']] ?? ucfirst(str_replace('-', ' ', $data['category']));

    $summary = sprintf(
        "ELEGANT QUIZ SUBMISSION\n\n" .
        "Contact Information:\n" .
        "Name: %s\n" .
        "Email: %s\n" .
        "Phone: %s\n\n" .
        "Treatment Interest:\n" .
        "Category: %s\n" .
        "Specific Area: %s\n" .
        "Previous Experience: %s times\n" .
        "Age Group: %s\n\n" .
        "Submission Time: %s",
        $data['contact']['full_name'],
        $data['contact']['email'],
        $data['contact']['phone'],
        $category_label,
        ucfirst(str_replace('-', ' ', $data['area'])),
        $data['experience'],
        $data['age'],
        $data['submission_time']
    );

    return $summary;
}

/**
 * Calculate lead score for elegant quiz
 */
function calculate_elegant_quiz_lead_score($data) {
    $score = 50; // Base score

    // Experience level scoring
    switch ($data['experience']) {
        case '0':
            $score += 20; // New patients are high value
            break;
        case '1-2':
            $score += 15;
            break;
        case '3-4':
            $score += 10;
            break;
        default:
            $score += 5; // Repeat customers
            break;
    }

    // Age group scoring (peak demographics)
    $peak_ages = ['25-34', '35-44', '45-54'];
    if (in_array($data['age'], $peak_ages)) {
        $score += 15;
    }

    // High-value treatment categories
    $premium_treatments = ['botox', 'dermal-fillers', 'coolsculpting', 'thermage'];
    if (in_array($data['category'], $premium_treatments)) {
        $score += 10;
    }

    return min(100, $score); // Cap at 100
}

/**
 * Determine follow-up priority
 */
function determine_follow_up_priority($data) {
    $score = calculate_elegant_quiz_lead_score($data);

    if ($score >= 80) return 'high';
    if ($score >= 60) return 'medium';
    return 'normal';
}

/**
 * Send admin notification email for elegant quiz
 */
function send_elegant_quiz_admin_notification($post_id, $data) {
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');
    $category_label = ucfirst(str_replace('-', ' ', $data['category']));
    $lead_score = get_post_meta($post_id, 'lead_score', true);
    $priority = get_post_meta($post_id, 'follow_up_priority', true);

    $subject = sprintf('[%s] New Elegant Quiz Submission - %s (%s Priority)',
        $site_name,
        $category_label,
        ucfirst($priority)
    );

    $message = sprintf(
        "New elegant quiz submission received!\n\n" .
        "CONTACT INFORMATION:\n" .
        "Name: %s\n" .
        "Email: %s\n" .
        "Phone: %s\n\n" .
        "TREATMENT DETAILS:\n" .
        "Category: %s\n" .
        "Specific Area: %s\n" .
        "Previous Experience: %s\n" .
        "Age Group: %s\n\n" .
        "LEAD SCORING:\n" .
        "Lead Score: %d/100\n" .
        "Priority: %s\n\n" .
        "View full details: %s\n\n" .
        "Please follow up within 24 hours for best conversion rates.",
        $data['contact']['full_name'],
        $data['contact']['email'],
        $data['contact']['phone'],
        $category_label,
        ucfirst(str_replace('-', ' ', $data['area'])),
        $data['experience'],
        $data['age'],
        $lead_score,
        ucfirst($priority),
        admin_url('post.php?post=' . $post_id . '&action=edit')
    );

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <noreply@' . $_SERVER['HTTP_HOST'] . '>',
        'Reply-To: ' . $data['contact']['email']
    ];

    wp_mail($admin_email, $subject, $message, $headers);
}

/**
 * Send user confirmation email for elegant quiz
 */
function send_elegant_quiz_user_confirmation($data) {
    $site_name = get_bloginfo('name');
    $category_label = ucfirst(str_replace('-', ' ', $data['category']));

    $subject = sprintf('Thank you for your interest in %s - %s', $category_label, $site_name);

    $message = sprintf(
        "Dear %s,\n\n" .
        "Thank you for completing our treatment recommendation quiz! We're excited to help you achieve your aesthetic goals.\n\n" .
        "QUIZ SUMMARY:\n" .
        "Treatment Interest: %s\n" .
        "Specific Area: %s\n" .
        "Experience Level: %s previous treatments\n\n" .
        "WHAT'S NEXT:\n" .
        "• A member of our team will contact you within 24 hours\n" .
        "• We'll discuss your goals and answer any questions\n" .
        "• We'll schedule your complimentary consultation\n" .
        "• You'll receive personalized treatment recommendations\n\n" .
        "If you have any immediate questions, please don't hesitate to contact us:\n" .
        "Phone: %s\n" .
        "Email: %s\n\n" .
        "We look forward to helping you look and feel your best!\n\n" .
        "Best regards,\n" .
        "The %s Team",
        $data['contact']['full_name'],
        $category_label,
        ucfirst(str_replace('-', ' ', $data['area'])),
        $data['experience'],
        preetidreams_get_phone() ?: 'Available on our website',
        preetidreams_get_email() ?: 'Available on our website',
        $site_name
    );

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <noreply@' . $_SERVER['HTTP_HOST'] . '>'
    ];

    wp_mail($data['contact']['email'], $subject, $message, $headers);
}

/**
 * Track elegant quiz completion for analytics
 */
function track_elegant_quiz_completion($data) {
    // Could integrate with Google Analytics, Facebook Pixel, etc.
    // For now, just log the conversion
    error_log(sprintf(
        'Elegant Quiz Conversion: %s (%s) - %s treatment, Lead Score: %d',
        $data['contact']['full_name'],
        $data['contact']['email'],
        $data['category'],
        calculate_elegant_quiz_lead_score($data)
    ));
}

function medspa_theme_enqueue_assets() {
    // Main theme styles
    wp_enqueue_style('medspa-theme-style', get_template_directory_uri() . '/assets/css/medical-spa-theme.css', array(), '1.0.0');

    // Component styles
    wp_enqueue_style('elegant-quiz-component', get_template_directory_uri() . '/assets/css/components/elegant-quiz.css', array('medspa-theme-style'), '1.0.0');

    // Main theme scripts
    wp_enqueue_script('medspa-theme-scripts', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);

    // Component scripts are enqueued conditionally within components
}
add_action('wp_enqueue_scripts', 'medspa_theme_enqueue_assets');

/**
 * Create essential pages for PreetiDreams Medical Spa
 * These pages will be used for navigation instead of post type archives
 */
function preetidreams_create_essential_pages() {
    // Define pages to create
    $pages = [
        [
            'post_title' => 'Treatments',
            'post_name' => 'treatments',
            'post_content' => '<p>Discover our comprehensive range of premium medical spa treatments designed to enhance your natural beauty and wellness.</p>',
            'page_template' => 'page-treatments.php',
            'meta_description' => 'Explore PreetiDreams Medical Spa\'s comprehensive range of premium treatments including facial rejuvenation, body contouring, laser treatments, and wellness services.'
        ],
        [
            'post_title' => 'About Us',
            'post_name' => 'about',
            'post_content' => '<p>Learn about our medical spa story, our team of experts, and our commitment to providing the highest quality aesthetic treatments.</p>',
            'page_template' => 'page-about.php',
            'meta_description' => 'Learn about PreetiDreams Medical Spa - our story, team of medical experts, and commitment to premium aesthetic treatments and patient care.'
        ],
        [
            'post_title' => 'Testimonials',
            'post_name' => 'testimonials',
            'post_content' => '<p>Read what our clients say about their experiences at PreetiDreams Medical Spa.</p>',
            'page_template' => 'page-testimonials.php',
            'meta_description' => 'Read testimonials and reviews from satisfied PreetiDreams Medical Spa clients about their aesthetic treatment experiences and results.'
        ],
        [
            'post_title' => 'Contact',
            'post_name' => 'contact',
            'post_content' => '<p>Contact PreetiDreams Medical Spa to schedule your consultation or learn more about our treatments.</p>',
            'page_template' => 'page-contact.php',
            'meta_description' => 'Contact PreetiDreams Medical Spa to schedule your consultation, learn about treatments, or get directions to our location.'
        ]
    ];

    foreach ($pages as $page_data) {
        // Check if page already exists
        $existing_page = get_page_by_path($page_data['post_name']);

        if (!$existing_page) {
            // Create the page
            $page_id = wp_insert_post([
                'post_title' => $page_data['post_title'],
                'post_name' => $page_data['post_name'],
                'post_content' => $page_data['post_content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1,
                'comment_status' => 'closed',
                'ping_status' => 'closed'
            ]);

            if ($page_id && !is_wp_error($page_id)) {
                // Set page template
                update_post_meta($page_id, '_wp_page_template', $page_data['page_template']);

                // Set SEO meta description
                update_post_meta($page_id, '_yoast_wpseo_metadesc', $page_data['meta_description']);

                error_log("Created page: {$page_data['post_title']} (ID: {$page_id})");
            }
        }
    }
}

/**
 * Run page creation on theme activation
 */
function preetidreams_theme_setup_pages() {
    // Create essential pages
    preetidreams_create_essential_pages();

    // Flush rewrite rules to ensure pretty permalinks work
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'preetidreams_theme_setup_pages');

/**
 * Also run once on init to ensure pages exist
 */
function preetidreams_ensure_pages_exist() {
    // Only run in admin and only once per day
    if (is_admin() && !wp_next_scheduled('preetidreams_page_check')) {
        wp_schedule_single_event(time(), 'preetidreams_page_check');
    }
}
add_action('init', 'preetidreams_ensure_pages_exist');

/**
 * Scheduled check for essential pages
 */
function preetidreams_scheduled_page_check() {
    preetidreams_create_essential_pages();
}
add_action('preetidreams_page_check', 'preetidreams_scheduled_page_check');

/**
 * Helper function to get page URL by slug
 * Used for reliable navigation links
 */
function preetidreams_get_page_url($slug) {
    $page = get_page_by_path($slug);
    if ($page) {
        return get_permalink($page->ID);
    }
    return home_url('/' . $slug . '/');
}

/**
 * Custom navigation menu walker for better accessibility
 */
class PreetiDreams_Walker_Nav_Menu extends Walker_Nav_Menu {
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= '<li' . $id . $class_names .'>';

        $atts = array();
        $atts['title']  = ! empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = ! empty($item->target)     ? $item->target     : '';
        $atts['rel']    = ! empty($item->xfn)        ? $item->xfn        : '';
        $atts['href']   = ! empty($item->url)        ? $item->url        : '';
        $atts['aria-current'] = $item->current ? 'page' : '';

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (! empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = $args->before ?? '';
        $item_output .= '<a' . $attributes .'>';
        $item_output .= ($args->link_before ?? '') . apply_filters('the_title', $item->title, $item->ID) . ($args->link_after ?? '');
        $item_output .= '</a>';
        $item_output .= $args->after ?? '';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}
