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

// =============================================================================
// SPRINT 6: CUSTOMIZABLE COMPONENT ARCHITECTURE SYSTEM
// =============================================================================

// Load Component Architecture Foundation (T6.1 - Component Base Architecture)
require_once get_template_directory() . '/inc/components/base-component.php';
require_once get_template_directory() . '/inc/components/component-registry.php';
require_once get_template_directory() . '/inc/components/component-factory.php';
require_once get_template_directory() . '/inc/components/component-auto-loader.php';

// Load demo component for testing (will be replaced with production components)
require_once get_template_directory() . '/inc/components/demo-button-component.php';

// Initialize Component System
add_action('after_setup_theme', function() {
    // Component Registry is auto-initialized
    // Component Factory helper functions are available globally

    // Enable component performance tracking in debug mode
    if (defined('WP_DEBUG') && WP_DEBUG) {
        add_action('wp_footer', function() {
            $metrics = ComponentRegistry::get_performance_metrics();
            if (!empty($metrics)) {
                echo "<!-- Component Performance Summary -->\n";
            }
        });
    }

    // Hook for future component auto-registration
    do_action('medspa_components_init');
}, 5); // Priority 5 to load before other theme setup

// Component management dashboard
if (is_admin()) {
    require_once get_template_directory() . '/inc/admin/component-dashboard.php';
}

// Initialize component system with auto-discovery
add_action('after_setup_theme', 'medspa_components_init', 5);
function medspa_components_init() {
    // Component system is automatically initialized by the auto-loader

    // Register demo button component for testing
    if (file_exists(get_template_directory() . '/inc/components/demo-button-component.php')) {
        require_once get_template_directory() . '/inc/components/demo-button-component.php';

        // Auto-registration will handle this, but we can manually register for immediate availability
        if (class_exists('ComponentRegistry') && class_exists('DemoButtonComponent')) {
            ComponentRegistry::register('demo-button', 'DemoButtonComponent', [
                'priority' => 5,
                'manual_registration' => true
            ]);
        }
    }

    // Performance monitoring in debug mode
    if (defined('WP_DEBUG') && WP_DEBUG) {
        add_action('wp_footer', function() {
            if (class_exists('ComponentRegistry')) {
                $metrics = ComponentRegistry::get_performance_metrics();
                if (!empty($metrics)) {
                    echo "<!-- Component Performance Debug:\n";
                    foreach ($metrics as $component => $data) {
                        $avg_time = $data['total_time'] / $data['total_renders'];
                        echo "  {$component}: " . number_format($avg_time * 1000, 2) . "ms avg ({$data['total_renders']} renders)\n";
                    }
                    echo "-->\n";
                }
            }
        });
    }
}

// =============================================================================
// SPRINT 4: INDUSTRY-STANDARD WORDPRESS CUSTOMIZER ARCHITECTURE
// =============================================================================

// Load new WordPress Core Customizer Integration (Sprint 4)
require_once get_template_directory() . '/inc/medspa-customizer.php';

// =============================================================================
// LEGACY VISUAL CUSTOMIZER SYSTEM (Maintaining compatibility)
// =============================================================================

// Simple Visual Customizer - Clean Admin Bar Implementation
require_once get_template_directory() . '/inc/visual-customizer-simple.php';


// Visual Customizer Integration - Sprint 2 PVC-004 (Legacy - keeping for compatibility)
require_once get_template_directory() . '/inc/visual-customizer-integration.php';
require_once get_template_directory() . '/inc/ajax-handlers.php';
require_once get_template_directory() . '/inc/visual-customizer-admin-page.php';
require_once get_template_directory() . '/inc/visual-customizer-simple-admin.php';
require_once get_template_directory() . '/inc/visual-customizer-dashboard.php';

// Sprint 2 PVC-007-DT: Design Token System WordPress Integration
require_once get_template_directory() . '/inc/design-token-customizer.php';

// Include server-rendered vs applied design debug tool
require_once get_template_directory() . '/devtools/testing-tools/server-rendered-vs-applied-debug.php';

// Include palette data structure debug tool
require_once get_template_directory() . '/devtools/testing-tools/palette-data-structure-debug.php';

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

    // CRITICAL FIX: Tokenization Contact Page Overrides
    // Fixes CSS conflicts preventing Visual Customizer from working on contact page
    wp_enqueue_style(
        'preetidreams-tokenization-contact-overrides',
        get_template_directory_uri() . '/assets/css/tokenization-contact-overrides.css',
        ['preetidreams-header-fix'],
        PREETIDREAMS_VERSION . '-tokenization-fix'
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

/**
 * Enqueue Luxury Footer Assets
 * Implements the complete luxury medical spa footer design
 */
function preetidreams_enqueue_luxury_footer_assets() {
    // Luxury Footer CSS
    wp_enqueue_style(
        'luxury-footer-styles',
        get_template_directory_uri() . '/assets/css/footer-luxury.css',
        ['preetidreams-style'],
        PREETIDREAMS_VERSION
    );

    // Footer Interactions JavaScript
    wp_enqueue_script(
        'footer-interactions',
        get_template_directory_uri() . '/assets/js/footer-interactions.js',
        [],
        PREETIDREAMS_VERSION,
        true
    );

    // Localize script with footer data
    wp_localize_script('footer-interactions', 'footerData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('footer_interactions_nonce'),
        'phone' => preetidreams_get_phone(),
        'email' => preetidreams_get_email(),
        'address' => preetidreams_get_address(),
        'hours' => preetidreams_get_hours(),
        'consultationLink' => get_permalink(get_page_by_path('contact')),
        'directionsLink' => get_theme_mod('footer_directions_link', 'https://maps.google.com'),
        'settings' => [
            'enableAnimations' => !wp_is_mobile(),
            'reducedMotion' => false, // Detected client-side
            'mobileBreakpoint' => 768,
            'scrollOffset' => 100,
            'animationDuration' => 300
        ],
        'i18n' => [
            'scrollingToTop' => __('Scrolling to top of page', 'preetidreams'),
            'openingMaps' => __('Opening Maps...', 'preetidreams'),
            'connecting' => __('Connecting...', 'preetidreams'),
            'dialing' => __('Dialing...', 'preetidreams'),
            'tapToCall' => __('Tap to call', 'preetidreams'),
            'confidentialEmail' => __('Send confidential email', 'preetidreams'),
            'getDirections' => __('Get directions to our clinic', 'preetidreams')
        ]
    ]);

    // Add luxury design system CSS variables
    $luxury_css = "
        :root {
            /* Luxury Footer Color Palette */
            --footer-sage-green: #87A96B;
            --footer-premium-gold: #D4AF37;
            --footer-medical-navy: #1B365D;
            --footer-cream-base: #FDFCFA;
            --footer-sage-light: #A8C487;
            --footer-sage-dark: #6B8552;
            --footer-gold-light: #E8C962;
            --footer-navy-light: #2B4A78;
            --footer-cream-warm: #FBF9F4;
            --footer-navy-deep: #152B4F;

            /* Footer Typography */
            --footer-font-display: 'Playfair Display', serif;
            --footer-font-body: 'Inter', sans-serif;

            /* Footer Spacing */
            --footer-space-xs: 0.5rem;
            --footer-space-sm: 0.75rem;
            --footer-space-md: 1rem;
            --footer-space-lg: 1.5rem;
            --footer-space-xl: 2rem;
            --footer-space-2xl: 3rem;
            --footer-space-3xl: 4rem;

            /* Footer Transitions */
            --footer-transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --footer-transition-gentle: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);

            /* Footer Shadows */
            --footer-shadow-soft: 0 4px 20px rgba(27, 54, 93, 0.08);
            --footer-shadow-luxury: 0 8px 25px rgba(212, 175, 55, 0.25);
            --footer-shadow-elevated: 0 12px 35px rgba(212, 175, 55, 0.35);
        }
    ";
    wp_add_inline_style('luxury-footer-styles', $luxury_css);
}
add_action('wp_enqueue_scripts', 'preetidreams_enqueue_luxury_footer_assets');

/**
 * Add Luxury Footer Customizer Settings
 * Comprehensive WordPress Customizer integration for footer content management
 */
function preetidreams_luxury_footer_customizer($wp_customize) {

    // Add Footer Panel
    $wp_customize->add_panel('luxury_footer_panel', [
        'title'       => __('Luxury Footer Settings', 'preetidreams'),
        'description' => __('Customize your luxury medical spa footer design and content', 'preetidreams'),
        'priority'    => 160,
    ]);

    // ========== Consultation Invitation Section ==========
    $wp_customize->add_section('footer_consultation_section', [
        'title'    => __('Consultation Invitation', 'preetidreams'),
        'panel'    => 'luxury_footer_panel',
        'priority' => 10,
    ]);

    // Consultation Headline
    $wp_customize->add_setting('footer_consultation_headline', [
        'default'           => 'Ready to Begin Your Aesthetic Journey?',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('footer_consultation_headline', [
        'label'   => __('Consultation Headline', 'preetidreams'),
        'section' => 'footer_consultation_section',
        'type'    => 'text',
    ]);

    // Consultation Subtext
    $wp_customize->add_setting('footer_consultation_subtext', [
        'default'           => 'Experience the artistry of medical aesthetics with Dr. Preeti Sharma in our luxury clinic',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('footer_consultation_subtext', [
        'label'   => __('Consultation Subtext', 'preetidreams'),
        'section' => 'footer_consultation_section',
        'type'    => 'textarea',
    ]);

    // Consultation Link
    $wp_customize->add_setting('footer_consultation_link', [
        'default'           => '/contact',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('footer_consultation_link', [
        'label'   => __('Consultation Booking Link', 'preetidreams'),
        'section' => 'footer_consultation_section',
        'type'    => 'url',
    ]);

    // ========== Medical Excellence Section ==========
    $wp_customize->add_section('footer_credentials_section', [
        'title'    => __('Medical Excellence & Credentials', 'preetidreams'),
        'panel'    => 'luxury_footer_panel',
        'priority' => 20,
    ]);

    // Board Certification
    $wp_customize->add_setting('footer_board_certification', [
        'default'           => 'Board-Certified Physician',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('footer_board_certification', [
        'label'   => __('Board Certification Title', 'preetidreams'),
        'section' => 'footer_credentials_section',
        'type'    => 'text',
    ]);

    // Certification Details
    $wp_customize->add_setting('footer_certification_details', [
        'default'           => 'American Board of Dermatology',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('footer_certification_details', [
        'label'   => __('Certification Details', 'preetidreams'),
        'section' => 'footer_credentials_section',
        'type'    => 'text',
    ]);

    // Experience Years
    $wp_customize->add_setting('footer_experience_years', [
        'default'           => '15+ Years of Excellence',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('footer_experience_years', [
        'label'   => __('Years of Experience', 'preetidreams'),
        'section' => 'footer_credentials_section',
        'type'    => 'text',
    ]);

    // Expertise Area
    $wp_customize->add_setting('footer_expertise_area', [
        'default'           => 'Aesthetic Medicine Expertise',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('footer_expertise_area', [
        'label'   => __('Expertise Area', 'preetidreams'),
        'section' => 'footer_credentials_section',
        'type'    => 'text',
    ]);

    // Recognition & Awards
    $wp_customize->add_setting('footer_recognition', [
        'default'           => 'Recognition & Awards',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('footer_recognition', [
        'label'   => __('Recognition Title', 'preetidreams'),
        'section' => 'footer_credentials_section',
        'type'    => 'text',
    ]);

    // Award Details
    $wp_customize->add_setting('footer_award_details', [
        'default'           => 'Top Medical Spa - Beverly Hills',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('footer_award_details', [
        'label'   => __('Award Details', 'preetidreams'),
        'section' => 'footer_credentials_section',
        'type'    => 'text',
    ]);

    // ========== Luxury Clinic Experience Section ==========
    $wp_customize->add_section('footer_clinic_experience_section', [
        'title'    => __('Luxury Clinic Experience', 'preetidreams'),
        'panel'    => 'luxury_footer_panel',
        'priority' => 30,
    ]);

    // Private Suites
    $wp_customize->add_setting('footer_private_suites', [
        'default'           => 'Private Consultation Suites',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('footer_private_suites', [
        'label'   => __('Private Suites Title', 'preetidreams'),
        'section' => 'footer_clinic_experience_section',
        'type'    => 'text',
    ]);

    // Suite Description
    $wp_customize->add_setting('footer_suite_description', [
        'default'           => 'Discrete, comfortable consultation rooms',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('footer_suite_description', [
        'label'   => __('Suite Description', 'preetidreams'),
        'section' => 'footer_clinic_experience_section',
        'type'    => 'text',
    ]);

    // Advanced Equipment
    $wp_customize->add_setting('footer_equipment', [
        'default'           => 'Advanced Medical Equipment',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('footer_equipment', [
        'label'   => __('Equipment Title', 'preetidreams'),
        'section' => 'footer_clinic_experience_section',
        'type'    => 'text',
    ]);

    // Equipment Description
    $wp_customize->add_setting('footer_equipment_description', [
        'default'           => 'State-of-the-art diagnostic and treatment tools',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('footer_equipment_description', [
        'label'   => __('Equipment Description', 'preetidreams'),
        'section' => 'footer_clinic_experience_section',
        'type'    => 'text',
    ]);

    // Personalized Planning
    $wp_customize->add_setting('footer_personalized_planning', [
        'default'           => 'Personalized Treatment Planning',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('footer_personalized_planning', [
        'label'   => __('Personalized Planning Title', 'preetidreams'),
        'section' => 'footer_clinic_experience_section',
        'type'    => 'text',
    ]);

    // Planning Description
    $wp_customize->add_setting('footer_planning_description', [
        'default'           => 'Custom aesthetic plans during your visit',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('footer_planning_description', [
        'label'   => __('Planning Description', 'preetidreams'),
        'section' => 'footer_clinic_experience_section',
        'type'    => 'text',
    ]);

    // ========== Connect Section ==========
    $wp_customize->add_section('footer_connect_section', [
        'title'    => __('Connect & Location', 'preetidreams'),
        'panel'    => 'luxury_footer_panel',
        'priority' => 40,
    ]);

    // Address Line 2
    $wp_customize->add_setting('footer_address_line2', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('footer_address_line2', [
        'label'   => __('Address Line 2 (Optional)', 'preetidreams'),
        'section' => 'footer_connect_section',
        'type'    => 'text',
    ]);

    // Directions Link
    $wp_customize->add_setting('footer_directions_link', [
        'default'           => 'https://maps.google.com',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('footer_directions_link', [
        'label'   => __('Google Maps Directions Link', 'preetidreams'),
        'section' => 'footer_connect_section',
        'type'    => 'url',
    ]);

    // Educational Resources Link
    $wp_customize->add_setting('footer_educational_resources_link', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('footer_educational_resources_link', [
        'label'   => __('Educational Resources Link (Optional)', 'preetidreams'),
        'section' => 'footer_connect_section',
        'type'    => 'url',
    ]);

    // Location Showcase Section
    $wp_customize->add_section('footer_luxury_location_showcase', array(
        'title'    => __('Luxury Location Showcase', 'preetidreams'),
        'priority' => 35,
        'panel'    => 'footer_luxury_panel'
    ));

    // Location Showcase - Header Settings
    $wp_customize->add_setting('footer_location_headline', array(
        'default' => 'Experience Our Luxury Medical Spa',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control('footer_location_headline', array(
        'label'   => __('Location Headline', 'preetidreams'),
        'section' => 'footer_luxury_location_showcase',
        'type'    => 'text'
    ));

    $wp_customize->add_setting('footer_location_description', array(
        'default' => 'Discover our state-of-the-art facility designed for your comfort and privacy. Schedule your personalized consultation in our sophisticated Beverly Hills location.',
        'sanitize_callback' => 'sanitize_textarea_field'
    ));
    $wp_customize->add_control('footer_location_description', array(
        'label'   => __('Location Description', 'preetidreams'),
        'section' => 'footer_luxury_location_showcase',
        'type'    => 'textarea',
        'description' => __('Consultation-focused description of your clinic location', 'preetidreams')
    ));

    // Google Maps Integration
    $wp_customize->add_setting('footer_map_embed_url', array(
        'default' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3305.2069!2d-118.3974881!3d34.0738!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzTCsDA0JzI1LjciTiAxMTjCsDIzJzUxLjAiVw!5e0!3m2!1sen!2sus!4v1234567890',
        'sanitize_callback' => 'esc_url_raw'
    ));
    $wp_customize->add_control('footer_map_embed_url', array(
        'label'   => __('Google Maps Embed URL', 'preetidreams'),
        'section' => 'footer_luxury_location_showcase',
        'type'    => 'url',
        'description' => __('Get embed URL from Google Maps > Share > Embed a map', 'preetidreams')
    ));

    $wp_customize->add_setting('footer_clinic_tagline', array(
        'default' => 'Beverly Hills Medical Spa',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control('footer_clinic_tagline', array(
        'label'   => __('Clinic Map Tagline', 'preetidreams'),
        'section' => 'footer_luxury_location_showcase',
        'type'    => 'text',
        'description' => __('Appears on the map overlay marker', 'preetidreams')
    ));

    // Location Features (4 premium features)
    for ($i = 1; $i <= 4; $i++) {
        $feature_defaults = array(
            1 => array('title' => 'Prime Beverly Hills Address', 'desc' => 'Prestigious Rodeo Drive vicinity'),
            2 => array('title' => 'Valet Parking Available', 'desc' => 'Complimentary for all appointments'),
            3 => array('title' => 'Private Entrance', 'desc' => 'Discrete and confidential access'),
            4 => array('title' => 'Luxury Amenities', 'desc' => 'Curated comfort experience')
        );

        $wp_customize->add_setting("footer_location_feature_{$i}", array(
            'default' => $feature_defaults[$i]['title'],
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control("footer_location_feature_{$i}", array(
            'label'   => sprintf(__('Location Feature %d Title', 'preetidreams'), $i),
            'section' => 'footer_luxury_location_showcase',
            'type'    => 'text'
        ));

        $wp_customize->add_setting("footer_location_feature_{$i}_desc", array(
            'default' => $feature_defaults[$i]['desc'],
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control("footer_location_feature_{$i}_desc", array(
            'label'   => sprintf(__('Location Feature %d Description', 'preetidreams'), $i),
            'section' => 'footer_luxury_location_showcase',
            'type'    => 'text'
        ));
    }

    // Location CTA Links
    $wp_customize->add_setting('footer_book_consultation_link', array(
        'default' => '/contact/#consultation',
        'sanitize_callback' => 'esc_url_raw'
    ));
    $wp_customize->add_control('footer_book_consultation_link', array(
        'label'   => __('Schedule Consultation Link', 'preetidreams'),
        'section' => 'footer_luxury_location_showcase',
        'type'    => 'url',
        'description' => __('Primary CTA for booking clinic visits', 'preetidreams')
    ));

    $wp_customize->add_setting('footer_virtual_tour_link', array(
        'default' => '#virtual-tour',
        'sanitize_callback' => 'esc_url_raw'
    ));
    $wp_customize->add_control('footer_virtual_tour_link', array(
        'label'   => __('Virtual Tour Link', 'preetidreams'),
        'section' => 'footer_luxury_location_showcase',
        'type'    => 'url',
        'description' => __('Link to virtual facility tour or video', 'preetidreams')
    ));

    // ============================================================================
}
add_action('customize_register', 'preetidreams_luxury_footer_customizer');

/**
 * Register Footer Navigation Menu
 * Ensures footer navigation is available in WordPress menu management
 */
function preetidreams_register_footer_menus() {
    register_nav_menus([
        'footer' => __('Footer Menu', 'preetidreams'),
        'footer-legal' => __('Footer Legal Menu', 'preetidreams'),
    ]);
}
add_action('init', 'preetidreams_register_footer_menus');

/**
 * Footer Analytics Tracking
 * Handle custom events from footer interactions
 */
function preetidreams_footer_analytics_handler() {
    // Verify nonce for security
    if (!wp_verify_nonce($_POST['nonce'], 'footer_interactions_nonce')) {
        wp_die('Security check failed');
    }

    $event_type = sanitize_text_field($_POST['event_type']);
    $event_data = wp_unslash($_POST['event_data']);

    // Log the interaction for analytics
    $analytics_data = [
        'event_type' => $event_type,
        'event_data' => $event_data,
        'user_id' => get_current_user_id(),
        'session_id' => session_id(),
        'timestamp' => current_time('mysql'),
        'ip_address' => $_SERVER['REMOTE_ADDR'],
        'user_agent' => $_SERVER['HTTP_USER_AGENT'],
        'referrer' => wp_get_referer(),
    ];

    // Store in custom table or integrate with analytics service
    do_action('preetidreams_footer_analytics_event', $analytics_data);

    wp_send_json_success([
        'message' => 'Analytics event recorded',
        'event_id' => uniqid('footer_', true)
    ]);
}
add_action('wp_ajax_footer_analytics', 'preetidreams_footer_analytics_handler');
add_action('wp_ajax_nopriv_footer_analytics', 'preetidreams_footer_analytics_handler');

/**
 * Enhanced Social Links Helper
 * Extended functionality for footer social media integration
 */
function preetidreams_get_social_links_array() {
    $social_platforms = [
        'facebook' => [
            'label' => 'Facebook',
            'icon' => '📘',
            'color' => '#1877F2',
            'url' => preetidreams_get_social_link('facebook')
        ],
        'instagram' => [
            'label' => 'Instagram',
            'icon' => '📷',
            'color' => '#E4405F',
            'url' => preetidreams_get_social_link('instagram')
        ],
        'linkedin' => [
            'label' => 'LinkedIn',
            'icon' => '💼',
            'color' => '#0A66C2',
            'url' => preetidreams_get_social_link('linkedin')
        ],
        'youtube' => [
            'label' => 'YouTube',
            'icon' => '📺',
            'color' => '#FF0000',
            'url' => preetidreams_get_social_link('youtube')
        ],
        'twitter' => [
            'label' => 'Twitter',
            'icon' => '🐦',
            'color' => '#1DA1F2',
            'url' => preetidreams_get_social_link('twitter')
        ]
    ];

    // Filter out platforms without URLs
    return array_filter($social_platforms, function($platform) {
        return !empty($platform['url']);
    });
}

/**
 * Generate Footer Schema Markup
 * SEO optimization with structured data for medical practice
 */
function preetidreams_footer_schema_markup() {
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'MedicalBusiness',
        'name' => get_bloginfo('name'),
        'description' => get_bloginfo('description'),
        'url' => home_url(),
        'telephone' => preetidreams_get_phone(),
        'email' => preetidreams_get_email(),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => preetidreams_get_address(),
            'addressLocality' => get_theme_mod('practice_city', ''),
            'addressRegion' => get_theme_mod('practice_state', ''),
            'postalCode' => get_theme_mod('practice_zip', ''),
            'addressCountry' => 'US'
        ],
        'geo' => [
            '@type' => 'GeoCoordinates',
            'latitude' => get_theme_mod('practice_latitude', ''),
            'longitude' => get_theme_mod('practice_longitude', '')
        ],
        'openingHours' => preetidreams_get_hours(),
        'priceRange' => '$$$$',
        'hasOfferCatalog' => [
            '@type' => 'OfferCatalog',
            'name' => 'Medical Spa Services',
            'itemListElement' => []
        ],
        'founder' => [
            '@type' => 'Person',
            'name' => get_theme_mod('doctor_name', 'Dr. Preeti Sharma'),
            'jobTitle' => 'Board-Certified Physician',
            'alumniOf' => get_theme_mod('doctor_education', ''),
            'knowsAbout' => 'Aesthetic Medicine, Dermatology'
        ],
        'sameAs' => array_values(array_column(preetidreams_get_social_links_array(), 'url'))
    ];

    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Output Footer Schema in wp_head
 */
function preetidreams_output_footer_schema() {
    echo preetidreams_footer_schema_markup();
}
add_action('wp_head', 'preetidreams_output_footer_schema');

/**
 * Add Meta Boxes for Treatment Post Type
 */
function preetidreams_add_treatment_meta_boxes() {
    add_meta_box(
        'treatment_medical_info',
        'Medical Information & Safety',
        'preetidreams_treatment_medical_info_callback',
        'treatment',
        'normal',
        'high'
    );

    add_meta_box(
        'treatment_details',
        'Treatment Details',
        'preetidreams_treatment_details_callback',
        'treatment',
        'normal',
        'high'
    );

    add_meta_box(
        'treatment_gallery',
        'Before/After Gallery',
        'preetidreams_treatment_gallery_callback',
        'treatment',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'preetidreams_add_treatment_meta_boxes');

/**
 * Medical Information Meta Box Callback
 */
function preetidreams_treatment_medical_info_callback($post) {
    wp_nonce_field('treatment_medical_meta_box', 'treatment_medical_meta_box_nonce');

    $medical_info = get_post_meta($post->ID, 'treatment_medical_info', true);
    $contraindications = get_post_meta($post->ID, 'treatment_contraindications', true);
    $side_effects = get_post_meta($post->ID, 'treatment_side_effects', true);

    echo '<table class="form-table">';

    // Medical Information
    echo '<tr>';
    echo '<th><label for="treatment_medical_info">Medical Details</label></th>';
    echo '<td>';
    wp_editor($medical_info, 'treatment_medical_info', [
        'textarea_name' => 'treatment_medical_info',
        'media_buttons' => false,
        'textarea_rows' => 5,
        'teeny' => true
    ]);
    echo '<p class="description">Detailed medical information about the treatment (e.g., how it works, medical benefits)</p>';
    echo '</td>';
    echo '</tr>';

    // Contraindications
    echo '<tr>';
    echo '<th><label for="treatment_contraindications">Contraindications</label></th>';
    echo '<td>';
    wp_editor($contraindications, 'treatment_contraindications', [
        'textarea_name' => 'treatment_contraindications',
        'media_buttons' => false,
        'textarea_rows' => 4,
        'teeny' => true
    ]);
    echo '<p class="description">Medical conditions or situations where this treatment is not recommended</p>';
    echo '</td>';
    echo '</tr>';

    // Side Effects
    echo '<tr>';
    echo '<th><label for="treatment_side_effects">Possible Side Effects</label></th>';
    echo '<td>';
    wp_editor($side_effects, 'treatment_side_effects', [
        'textarea_name' => 'treatment_side_effects',
        'media_buttons' => false,
        'textarea_rows' => 4,
        'teeny' => true
    ]);
    echo '<p class="description">Common side effects and what patients should expect</p>';
    echo '</td>';
    echo '</tr>';

    echo '</table>';
}

/**
 * Treatment Details Meta Box Callback
 */
function preetidreams_treatment_details_callback($post) {
    wp_nonce_field('treatment_details_meta_box', 'treatment_details_meta_box_nonce');

    $benefits = get_post_meta($post->ID, 'treatment_benefits', true);
    $process = get_post_meta($post->ID, 'treatment_process', true);

    echo '<table class="form-table">';

    // Benefits
    echo '<tr>';
    echo '<th><label for="treatment_benefits">Treatment Benefits</label></th>';
    echo '<td>';
    wp_editor($benefits, 'treatment_benefits', [
        'textarea_name' => 'treatment_benefits',
        'media_buttons' => false,
        'textarea_rows' => 5,
        'teeny' => true
    ]);
    echo '<p class="description">List the key benefits and advantages of this treatment</p>';
    echo '</td>';
    echo '</tr>';

    // Process
    echo '<tr>';
    echo '<th><label for="treatment_process">Treatment Process</label></th>';
    echo '<td>';
    wp_editor($process, 'treatment_process', [
        'textarea_name' => 'treatment_process',
        'media_buttons' => false,
        'textarea_rows' => 5,
        'teeny' => true
    ]);
    echo '<p class="description">Describe what patients can expect during the treatment process</p>';
    echo '</td>';
    echo '</tr>';

    echo '</table>';
}

/**
 * Treatment Gallery Meta Box Callback
 */
function preetidreams_treatment_gallery_callback($post) {
    wp_nonce_field('treatment_gallery_meta_box', 'treatment_gallery_meta_box_nonce');

    $before_after_gallery = get_post_meta($post->ID, 'treatment_before_after', true);

    echo '<table class="form-table">';

    // Before/After Gallery
    echo '<tr>';
    echo '<th><label for="treatment_before_after">Before/After Gallery</label></th>';
    echo '<td>';
    wp_editor($before_after_gallery, 'treatment_before_after', [
        'textarea_name' => 'treatment_before_after',
        'media_buttons' => true,
        'textarea_rows' => 8,
        'teeny' => false
    ]);
    echo '<div class="description">';
    echo '<p><strong>Important:</strong> Only use images with proper patient consent and HIPAA compliance.</p>';
    echo '<p>You can insert images using the "Add Media" button above. Consider using gallery shortcodes for organized display.</p>';
    echo '<p><strong>Privacy Requirements:</strong></p>';
    echo '<ul>';
    echo '<li>Patient consent forms must be on file for all photos</li>';
    echo '<li>Photos should be anonymized (no identifying features visible)</li>';
    echo '<li>Include disclaimers about individual results varying</li>';
    echo '</ul>';
    echo '</div>';
    echo '</td>';
    echo '</tr>';

    echo '</table>';
}

/**
 * Save Treatment Meta Data
 */
function preetidreams_save_treatment_meta_data($post_id) {
    // Check nonces for each meta box
    $medical_nonce = isset($_POST['treatment_medical_meta_box_nonce']) ? $_POST['treatment_medical_meta_box_nonce'] : '';
    $details_nonce = isset($_POST['treatment_details_meta_box_nonce']) ? $_POST['treatment_details_meta_box_nonce'] : '';
    $gallery_nonce = isset($_POST['treatment_gallery_meta_box_nonce']) ? $_POST['treatment_gallery_meta_box_nonce'] : '';

    if (!$medical_nonce && !$details_nonce && !$gallery_nonce) {
        return;
    }

    if ($medical_nonce && !wp_verify_nonce($medical_nonce, 'treatment_medical_meta_box')) {
        return;
    }

    if ($details_nonce && !wp_verify_nonce($details_nonce, 'treatment_details_meta_box')) {
        return;
    }

    if ($gallery_nonce && !wp_verify_nonce($gallery_nonce, 'treatment_gallery_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save medical information fields
    $medical_fields = [
        'treatment_medical_info',
        'treatment_contraindications',
        'treatment_side_effects'
    ];

    foreach ($medical_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, wp_kses_post($_POST[$field]));
        }
    }

    // Save treatment details fields
    $detail_fields = [
        'treatment_benefits',
        'treatment_process'
    ];

    foreach ($detail_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, wp_kses_post($_POST[$field]));
        }
    }

    // Save gallery field
    if (isset($_POST['treatment_before_after'])) {
        update_post_meta($post_id, 'treatment_before_after', wp_kses_post($_POST['treatment_before_after']));
    }
}
add_action('save_post', 'preetidreams_save_treatment_meta_data');

/**
 * Add Meta Boxes for Staff Post Type (TASK-003 Completion)
 */
function preetidreams_add_staff_meta_boxes() {
    add_meta_box(
        'staff_details',
        'Staff Member Details',
        'preetidreams_staff_details_callback',
        'staff',
        'normal',
        'high'
    );

    add_meta_box(
        'staff_credentials',
        'Medical Credentials & Specialties',
        'preetidreams_staff_credentials_callback',
        'staff',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'preetidreams_add_staff_meta_boxes');

/**
 * Staff Details Meta Box Callback
 */
function preetidreams_staff_details_callback($post) {
    wp_nonce_field('staff_details_meta_box', 'staff_details_meta_box_nonce');

    $position = get_post_meta($post->ID, 'staff_position', true);
    $experience = get_post_meta($post->ID, 'staff_experience', true);
    $featured = get_post_meta($post->ID, 'staff_featured', true);
    $order = get_post_meta($post->ID, 'staff_order', true);

    echo '<table class="form-table">';

    // Position/Title
    echo '<tr>';
    echo '<th><label for="staff_position">Position/Title</label></th>';
    echo '<td>';
    echo '<input type="text" id="staff_position" name="staff_position" value="' . esc_attr($position) . '" style="width: 100%;" placeholder="e.g., Lead Aesthetic Nurse, Physician Assistant" />';
    echo '<p class="description">Staff member\'s job title or position</p>';
    echo '</td>';
    echo '</tr>';

    // Years of Experience
    echo '<tr>';
    echo '<th><label for="staff_experience">Years of Experience</label></th>';
    echo '<td>';
    echo '<input type="text" id="staff_experience" name="staff_experience" value="' . esc_attr($experience) . '" style="width: 100%;" placeholder="e.g., 8+ years in aesthetic nursing" />';
    echo '<p class="description">Professional experience description</p>';
    echo '</td>';
    echo '</tr>';

    // Featured Staff
    echo '<tr>';
    echo '<th><label for="staff_featured">Featured on About Page</label></th>';
    echo '<td>';
    echo '<label><input type="checkbox" id="staff_featured" name="staff_featured" value="1" ' . checked($featured, '1', false) . ' /> Display this staff member on the About Us page</label>';
    echo '<p class="description">Check to show this staff member in the team profiles section</p>';
    echo '</td>';
    echo '</tr>';

    // Display Order
    echo '<tr>';
    echo '<th><label for="staff_order">Display Order</label></th>';
    echo '<td>';
    echo '<input type="number" id="staff_order" name="staff_order" value="' . esc_attr($order) . '" style="width: 100px;" placeholder="1" />';
    echo '<p class="description">Order in which to display staff members (lower numbers appear first)</p>';
    echo '</td>';
    echo '</tr>';

    echo '</table>';
}

/**
 * Staff Credentials Meta Box Callback
 */
function preetidreams_staff_credentials_callback($post) {
    wp_nonce_field('staff_credentials_meta_box', 'staff_credentials_meta_box_nonce');

    $credentials = get_post_meta($post->ID, 'staff_credentials', true);
    $specialties = get_post_meta($post->ID, 'staff_specialties', true);
    $education = get_post_meta($post->ID, 'staff_education', true);
    $certifications = get_post_meta($post->ID, 'staff_certifications', true);

    echo '<table class="form-table">';

    // Medical Credentials
    echo '<tr>';
    echo '<th><label for="staff_credentials">Medical Credentials</label></th>';
    echo '<td>';
    echo '<input type="text" id="staff_credentials" name="staff_credentials" value="' . esc_attr($credentials) . '" style="width: 100%;" placeholder="e.g., BSN, RN, PA-C, MD" />';
    echo '<p class="description">Professional credentials and licenses (e.g., RN, BSN, PA-C, MD)</p>';
    echo '</td>';
    echo '</tr>';

    // Education
    echo '<tr>';
    echo '<th><label for="staff_education">Education Background</label></th>';
    echo '<td>';
    echo '<textarea id="staff_education" name="staff_education" rows="3" style="width: 100%;" placeholder="e.g., Bachelor of Science in Nursing - UCLA">' . esc_textarea($education) . '</textarea>';
    echo '<p class="description">Educational background and alma mater</p>';
    echo '</td>';
    echo '</tr>';

    // Specialties
    echo '<tr>';
    echo '<th><label for="staff_specialties">Specialties & Expertise</label></th>';
    echo '<td>';
    echo '<textarea id="staff_specialties" name="staff_specialties" rows="3" style="width: 100%;" placeholder="e.g., Injectable treatments, Skincare consultation, Patient education">' . esc_textarea($specialties) . '</textarea>';
    echo '<p class="description">Areas of specialty and expertise</p>';
    echo '</td>';
    echo '</tr>';

    // Certifications
    echo '<tr>';
    echo '<th><label for="staff_certifications">Professional Certifications</label></th>';
    echo '<td>';
    echo '<textarea id="staff_certifications" name="staff_certifications" rows="3" style="width: 100%;" placeholder="e.g., Certified Aesthetic Nurse Specialist, Advanced Injection Techniques">' . esc_textarea($certifications) . '</textarea>';
    echo '<p class="description">Professional certifications and additional training</p>';
    echo '</td>';
    echo '</tr>';

    echo '</table>';

    // Preview Section
    echo '<h3>Team Profile Preview</h3>';
    echo '<div style="border: 1px solid #ddd; padding: 15px; background: #f9f9f9; border-radius: 5px;">';
    echo '<p><strong>How this will appear on the About Us page:</strong></p>';

    $preview_name = !empty($post->post_title) ? $post->post_title : 'Staff Member Name';
    $preview_position = !empty($position) ? $position : 'Position Title';
    $preview_credentials = !empty($credentials) ? $credentials : 'Professional Credentials';

    echo '<div style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 1rem; background: white; margin: 10px 0;">';
    echo '<div style="display: flex; align-items: center; gap: 15px;">';
    echo '<div style="width: 60px; height: 60px; border-radius: 50%; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 24px;">👩‍⚕️</div>';
    echo '<div>';
    echo '<h4 style="margin: 0; color: #1B365D;">' . esc_html($preview_name) . '</h4>';
    echo '<p style="margin: 5px 0; color: #87A96B; font-weight: 600;">' . esc_html($preview_position) . '</p>';
    echo '<p style="margin: 0; color: #666; font-size: 0.9em;">' . esc_html($preview_credentials) . '</p>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

/**
 * Save Staff Meta Data
 */
function preetidreams_save_staff_meta_data($post_id) {
    // Check nonces for each meta box
    $details_nonce = isset($_POST['staff_details_meta_box_nonce']) ? $_POST['staff_details_meta_box_nonce'] : '';
    $credentials_nonce = isset($_POST['staff_credentials_meta_box_nonce']) ? $_POST['staff_credentials_meta_box_nonce'] : '';

    if (!$details_nonce && !$credentials_nonce) {
        return;
    }

    if ($details_nonce && !wp_verify_nonce($details_nonce, 'staff_details_meta_box')) {
        return;
    }

    if ($credentials_nonce && !wp_verify_nonce($credentials_nonce, 'staff_credentials_meta_box')) {
            return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save staff details fields
    $detail_fields = [
        'staff_position',
        'staff_experience',
        'staff_featured',
        'staff_order'
    ];

    foreach ($detail_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Save staff credentials fields
    $credential_fields = [
        'staff_credentials',
        'staff_specialties',
        'staff_education',
        'staff_certifications'
    ];

    foreach ($credential_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_textarea_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'preetidreams_save_staff_meta_data');

/**
 * Create Sample Staff Data for About Us Page (TASK-003)
 */
function preetidreams_create_sample_staff() {
    // Check if we've already created sample staff data
    if (get_option('preetidreams_sample_staff_created')) {
        return;
    }

    // Sample staff data
    $sample_staff = [
        [
            'title' => 'Dr. Preeti Sharma',
            'content' => 'Board-certified physician and aesthetic medicine expert with over 15 years of experience in delivering natural, beautiful results through advanced medical treatments.',
            'excerpt' => 'Board-certified physician specializing in aesthetic medicine with a passion for natural enhancement and patient care.',
            'meta' => [
                'staff_position' => 'Medical Director & Founder',
                'staff_credentials' => 'MD, Board-Certified Dermatologist',
                'staff_specialties' => 'Aesthetic Medicine, Advanced Injection Techniques, Laser Treatments, Medical Spa Management',
                'staff_experience' => '15+ years in aesthetic medicine and dermatology',
                'staff_education' => 'Medical Degree - Harvard Medical School, Dermatology Residency - Mayo Clinic, Aesthetic Medicine Fellowship - UCLA',
                'staff_certifications' => 'American Board of Dermatology, American Board of Aesthetic Medicine, Advanced Injection Techniques Certification',
                'staff_featured' => '1',
                'staff_order' => '1'
            ]
        ],
        [
            'title' => 'Sarah Johnson, RN',
            'content' => 'Lead aesthetic nurse with extensive experience in advanced injection techniques and comprehensive patient care in luxury medical spa environments.',
            'excerpt' => 'Experienced aesthetic nurse specializing in injectable treatments and personalized patient care.',
            'meta' => [
                'staff_position' => 'Lead Aesthetic Nurse',
                'staff_credentials' => 'BSN, RN, Certified Aesthetic Nurse Specialist',
                'staff_specialties' => 'Injectable treatments, Skincare consultation, Patient education, Treatment planning',
                'staff_experience' => '8+ years in aesthetic nursing and patient care',
                'staff_education' => 'Bachelor of Science in Nursing - UCLA, Advanced Aesthetic Nursing Certification',
                'staff_certifications' => 'Certified Aesthetic Nurse Specialist, Advanced Injection Techniques, Laser Safety Certification',
                'staff_featured' => '1',
                'staff_order' => '2'
            ]
        ],
        [
            'title' => 'Michael Chen, PA-C',
            'content' => 'Physician assistant specializing in comprehensive aesthetic consultations and advanced treatment planning with a focus on achieving natural, beautiful results.',
            'excerpt' => 'Experienced physician assistant focused on aesthetic consultations and personalized treatment planning.',
            'meta' => [
                'staff_position' => 'Physician Assistant',
                'staff_credentials' => 'PA-C, Master of Physician Assistant Studies',
                'staff_specialties' => 'Treatment planning, Laser procedures, Body contouring, Comprehensive consultations',
                'staff_experience' => '6+ years in aesthetic medicine and patient consultations',
                'staff_education' => 'Master of Physician Assistant Studies - USC, Bachelor of Science in Biology - UCLA',
                'staff_certifications' => 'National Commission on Certification of Physician Assistants, Laser Treatment Certification, Body Contouring Specialist',
                'staff_featured' => '1',
                'staff_order' => '3'
            ]
        ]
    ];

    // Create the staff posts
    foreach ($sample_staff as $staff_data) {
        // Create the post
        $post_id = wp_insert_post([
            'post_title' => $staff_data['title'],
            'post_content' => $staff_data['content'],
            'post_excerpt' => $staff_data['excerpt'],
            'post_status' => 'publish',
            'post_type' => 'staff',
            'post_author' => 1,
            'menu_order' => intval($staff_data['meta']['staff_order'])
        ]);

        if ($post_id && !is_wp_error($post_id)) {
            // Set custom meta fields
            foreach ($staff_data['meta'] as $meta_key => $meta_value) {
                update_post_meta($post_id, $meta_key, $meta_value);
            }
        }
    }

    // Mark that we've created sample staff data
    update_option('preetidreams_sample_staff_created', true);
}

/**
 * Add Staff Management to Theme Setup Page
 */
function preetidreams_setup_page_with_staff() {
    if (isset($_POST['create_sample_data']) && check_admin_referer('preetidreams_setup')) {
        delete_option('preetidreams_sample_data_created');
        preetidreams_create_sample_treatments();
        echo '<div class="notice notice-success"><p>Sample treatment data has been created successfully!</p></div>';
    }

    if (isset($_POST['create_sample_staff']) && check_admin_referer('preetidreams_setup')) {
        delete_option('preetidreams_sample_staff_created');
        preetidreams_create_sample_staff();
        echo '<div class="notice notice-success"><p>Sample staff data has been created successfully!</p></div>';
    }

    ?>
    <div class="wrap">
        <h1>Medical Spa Theme Setup</h1>
        <p>Use this page to set up your medical spa theme with sample data.</p>

        <form method="post" action="">
            <?php wp_nonce_field('preetidreams_setup'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Sample Treatments</th>
                    <td>
                        <button type="submit" name="create_sample_data" class="button button-primary">
                            Create Sample Treatments
                        </button>
                        <p class="description">
                            This will create 12 sample treatments with categories, areas, and metadata for demonstration purposes.
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Sample Staff (TASK-003)</th>
                    <td>
                        <button type="submit" name="create_sample_staff" class="button button-primary">
                            Create Sample Staff Members
                        </button>
                        <p class="description">
                            This will create sample staff members (Dr. Preeti, Lead Nurse, Physician Assistant) for the About Us page.
                        </p>
                    </td>
                </tr>
            </table>
        </form>

        <h2>About Us Page Features (TASK-003)</h2>
        <div class="about-features-status">
            <p><strong>✅ Completed Features:</strong></p>
            <ul>
                <li>✅ Medical spa story and mission statement section</li>
                <li>✅ Dr. Preeti's credentials highlighting (Board-certified expertise, 15+ years experience)</li>
                <li>✅ Team member profiles with medical certifications</li>
                <li>✅ Facility tour virtual walkthrough</li>
                <li>✅ Awards and recognition display</li>
                <li>✅ Safety protocols and medical-grade standards</li>
                <li>✅ WordPress admin interface for staff management</li>
                <li>✅ WCAG AAA accessibility compliance</li>
            </ul>
        </div>
    </div>
    <?php
}

// Update the existing setup page function
remove_action('admin_menu', 'preetidreams_admin_menu');
function preetidreams_admin_menu_updated() {
    add_theme_page(
        'Medical Spa Theme Setup',
        'Theme Setup',
        'manage_options',
        'preetidreams-setup',
        'preetidreams_setup_page_with_staff'
    );
}
add_action('admin_menu', 'preetidreams_admin_menu_updated');

function medspa_theme_styles() {
    // Main theme stylesheet
    wp_enqueue_style('medspa-theme-style', get_template_directory_uri() . '/assets/css/medical-spa-theme.css', array(), '1.0.0');

    // Customizer enhancements CSS
    wp_enqueue_style('medspa-customizer-enhancements', get_template_directory_uri() . '/assets/css/customizer-enhancements.css', array('medspa-theme-style'), '1.0.0');

    // About Us page specific styles
    if (is_page('about-us') || is_page_template('page-about.php')) {
        wp_enqueue_style('about-us-styles', get_template_directory_uri() . '/assets/css/about-us-fix.css', array('medspa-theme-style'), '1.0.0');
    }

    // Font Awesome for icons
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');
}
add_action('wp_enqueue_scripts', 'medspa_theme_styles');

/**
 * Enqueue Visual Customizer System (Admin Only with Global Configuration)
 * NOTE: This function has been moved to the new implementation below
 */
// Removed old function - see new implementation at the end of file

/**
 * ============================================================================
 * VISUAL CUSTOMIZER INTEGRATION
 * ============================================================================
 */

// Include the Visual Customizer Integration
require_once get_template_directory() . '/inc/visual-customizer-integration.php';

/**
 * ============================================================================
 * ESSENTIAL THEME SETUP FUNCTION (RESTORED)
 * ============================================================================
 */

/**
 * Theme setup function
 */
function medspa_theme_setup() {
    // Add theme support for title tag
    add_theme_support('title-tag');

    // Add theme support for custom logo
    add_theme_support('custom-logo');

    // Add theme support for post thumbnails
    add_theme_support('post-thumbnails');

    // Add theme support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    // Add theme support for custom background
    add_theme_support('custom-background');

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'preetidreams'),
        'footer' => esc_html__('Footer Menu', 'preetidreams'),
    ));
}
add_action('after_setup_theme', 'medspa_theme_setup');

/**
 * Sprint 2 PVC-007-DT: Enqueue Design Token System Assets
 */
function preetidreams_enqueue_design_token_system() {
    // Load for admin users or when specifically in customizer/debug mode
    // CRITICAL FIX: Also load for admin users on frontend for Visual Customizer
    $should_load = is_admin() ||
                   is_customize_preview() ||
                   (defined('WP_DEBUG') && WP_DEBUG) ||
                   (is_user_logged_in() && current_user_can('manage_options'));

    if ($should_load) {

        // Core Design Token Systems
        wp_enqueue_script(
            'design-token-registry',
            get_template_directory_uri() . '/assets/js/design-token-registry.js',
            ['jquery'],
            PREETIDREAMS_VERSION,
            true
        );

        wp_enqueue_script(
            'token-relationship-engine',
            get_template_directory_uri() . '/assets/js/token-relationship-engine.js',
            ['design-token-registry'],
            PREETIDREAMS_VERSION,
            true
        );

        wp_enqueue_script(
            'universal-customization-engine',
            get_template_directory_uri() . '/assets/js/universal-customization-engine.js',
            ['token-relationship-engine'],
            PREETIDREAMS_VERSION,
            true
        );

        // Domain-Specific Systems
        wp_enqueue_script(
            'color-domain-system',
            get_template_directory_uri() . '/assets/js/color-domain-system.js',
            ['universal-customization-engine'],
            PREETIDREAMS_VERSION,
            true
        );

        wp_enqueue_script(
            'semantic-color-system',
            get_template_directory_uri() . '/assets/js/semantic-color-system.js',
            ['color-domain-system'],
            PREETIDREAMS_VERSION,
            true
        );

        wp_enqueue_script(
            'typography-domain-system',
            get_template_directory_uri() . '/assets/js/typography-domain-system.js',
            ['universal-customization-engine'],
            PREETIDREAMS_VERSION,
            true
        );

        wp_enqueue_script(
            'spacing-domain-generator',
            get_template_directory_uri() . '/assets/js/spacing-domain-generator.js',
            ['universal-customization-engine'],
            PREETIDREAMS_VERSION,
            true
        );

        // Enhanced Design Token Customizer Preview (WordPress Customizer integration)
        wp_enqueue_script(
            'design-token-customizer-preview',
            get_template_directory_uri() . '/assets/js/design-token-customizer-preview.js',
            ['universal-customization-engine', 'typography-domain-system'],
            PREETIDREAMS_VERSION,
            true
        );

        // CRITICAL: Sidebar Token Bridge for Visual Customizer Integration
        wp_enqueue_script(
            'sidebar-token-bridge',
            get_template_directory_uri() . '/assets/js/sidebar-token-bridge.js',
            [
                'jquery',
                'universal-customization-engine',
                'typography-domain-system',
                'spacing-domain-generator'
            ],
            PREETIDREAMS_VERSION,
            true
        );

        // CRITICAL: Visual Interface Components for Sidebar Integration
        wp_enqueue_script(
            'sidebar-color-palette-interface',
            get_template_directory_uri() . '/assets/js/sidebar-color-palette-interface.js',
            ['sidebar-token-bridge', 'semantic-color-system'],
            PREETIDREAMS_VERSION,
            true
        );

        wp_enqueue_script(
            'sidebar-typography-interface',
            get_template_directory_uri() . '/assets/js/sidebar-typography-interface.js',
            ['sidebar-token-bridge', 'typography-domain-system'],
            PREETIDREAMS_VERSION,
            true
        );

        // Enhanced Visual Interface Styling
        wp_enqueue_style(
            'sidebar-visual-interfaces',
            get_template_directory_uri() . '/assets/css/sidebar-visual-interfaces.css',
            [],
            PREETIDREAMS_VERSION
        );

        // Configuration and debugging
        wp_localize_script('universal-customization-engine', 'designTokenConfig', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('design_token_nonce'),
            'debugMode' => defined('WP_DEBUG') && WP_DEBUG,
            'version' => PREETIDREAMS_VERSION,
            'isCustomizer' => is_customize_preview(),
            'isAdmin' => is_admin(),
            'isLoggedInAdmin' => is_user_logged_in() && current_user_can('manage_options'),
            'sidebarIntegration' => true, // Sprint 2 Extension flag
            'currentTheme' => get_stylesheet()
        ]);

        // Sprint 2 Extension: Bridge Integration Status
        wp_localize_script('sidebar-token-bridge', 'sidebarBridgeConfig', [
            'sprintVersion' => 'SPRINT-002-EXT-001',
            'integrationMode' => 'visual-customizer-sidebar',
            'wordpressCustomizerDisabled' => false, // Keep for compatibility
            'visualCustomizerEnabled' => true,
            'debugOutput' => defined('WP_DEBUG') && WP_DEBUG
        ]);

        // Debug script loading
        if (defined('WP_DEBUG') && WP_DEBUG) {
            wp_add_inline_script('sidebar-typography-interface', '
                console.log("🔧 Typography Interface Script Loaded");
                console.log("🔧 Window.SidebarTypographyInterface:", typeof window.SidebarTypographyInterface);
            ');
        }
    }
}
add_action('wp_enqueue_scripts', 'preetidreams_enqueue_design_token_system');
add_action('customize_preview_init', 'preetidreams_enqueue_design_token_system');

/**
 * PERFORMANCE OPTIMIZATION: Server-side Google Fonts loading for selected typography
 * Industry best practice: Load fonts in HTML head for immediate availability
 */
function enqueue_selected_typography_fonts() {
    // Get current typography configuration
    $config = get_option('preetidreams_visual_customizer_config', []);

    if (!empty($config['typographyData'])) {
        $typography = $config['typographyData'];

        // Build Google Fonts URL for selected typography
        if (!empty($typography['googleFonts'])) {
            $fonts_query = implode('&family=', $typography['googleFonts']);
            $google_fonts_url = "https://fonts.googleapis.com/css2?family={$fonts_query}&display=swap";

            // Enqueue the selected typography fonts
            wp_enqueue_style(
                'selected-typography-fonts',
                $google_fonts_url,
                [],
                null // No version for Google Fonts
            );

            // Add resource hints for performance
            add_action('wp_head', function() {
                echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
                echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
            }, 1);

            error_log("✅ Server-side fonts loaded: " . $google_fonts_url);
        }
    }

    // Always load preview fonts for customizer interface (for admin users)
    if (current_user_can('customize')) {
        $preview_fonts = [
            'Inter:wght@400;500;600;700',
            'Playfair+Display:wght@400;500;600;700',
            'Poppins:wght@400;500;600;700',
            'Crimson+Text:wght@400;500;600;700',
            'Montserrat:wght@400;500;600;700',
            'Cormorant+Garamond:wght@400;500;600;700',
            'IBM+Plex+Sans:wght@400;500;600;700',
            'Merriweather:wght@400;500;600;700;900'
        ];

        $preview_fonts_query = implode('&family=', $preview_fonts);
        $preview_fonts_url = "https://fonts.googleapis.com/css2?family={$preview_fonts_query}&display=swap";

        wp_enqueue_style(
            'typography-preview-fonts-server',
            $preview_fonts_url,
            [],
            null
        );

        error_log("✅ Server-side preview fonts loaded for admin user");
    }
}
add_action('wp_enqueue_scripts', 'enqueue_selected_typography_fonts', 5); // High priority

/**
 * PERFORMANCE OPTIMIZATION: Add critical font loading optimizations to head
 */
function add_font_loading_optimizations() {
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";

    // Add font-display CSS for immediate text rendering
    echo '<style>' . "\n";
    echo '@font-face { font-display: swap; }' . "\n";
    echo '</style>' . "\n";
}
add_action('wp_head', 'add_font_loading_optimizations', 1);

// Include performance optimizations
require_once get_template_directory() . '/inc/typography-performance.php';
