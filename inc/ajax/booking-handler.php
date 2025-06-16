<?php
/**
 * Enhanced Booking Form Handler
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle treatment booking form submission
 */
function handle_treatment_booking() {
    // Verify nonce for security
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'treatment_booking_nonce')) {
        wp_send_json_error([
            'message' => 'Security verification failed. Please refresh the page and try again.',
            'code' => 'INVALID_NONCE'
        ]);
    }

    // Sanitize and validate input data
    $booking_data = sanitize_booking_data($_POST);

    // Validate required fields
    $validation_result = validate_booking_data($booking_data);
    if (!$validation_result['valid']) {
        wp_send_json_error([
            'message' => 'Please correct the following errors:',
            'errors' => $validation_result['errors'],
            'code' => 'VALIDATION_ERROR'
        ]);
    }

    // Check time slot availability
    $availability_check = check_time_slot_availability(
        $booking_data['selected_date'],
        $booking_data['selected_time'],
        $booking_data['treatment_id']
    );

    if (!$availability_check['available']) {
        wp_send_json_error([
            'message' => 'Sorry, the selected time slot is no longer available. Please choose another time.',
            'code' => 'SLOT_UNAVAILABLE',
            'suggested_times' => $availability_check['alternatives'] ?? []
        ]);
    }

    // Create booking record
    $booking_id = create_booking_record($booking_data);

    if (!$booking_id) {
        wp_send_json_error([
            'message' => 'Failed to create booking record. Please try again or contact us directly.',
            'code' => 'BOOKING_CREATION_FAILED'
        ]);
    }

    // Send confirmation emails
    $email_result = send_booking_emails($booking_id, $booking_data);

    // Log booking activity
    log_booking_activity($booking_id, 'created', $booking_data);

    // Prepare success response
    $response_data = [
        'message' => 'Your booking request has been submitted successfully!',
        'booking_id' => $booking_id,
        'booking_reference' => generate_booking_reference($booking_id),
        'confirmation_details' => format_booking_confirmation($booking_data),
        'next_steps' => get_booking_next_steps($booking_data),
        'email_sent' => $email_result['success'] ?? false
    ];

    // Add calendar event data for user's calendar
    $response_data['calendar_event'] = generate_calendar_event_data($booking_data);

    wp_send_json_success($response_data);
}

/**
 * Sanitize booking form data
 */
function sanitize_booking_data($raw_data) {
    return [
        'full_name' => sanitize_text_field($raw_data['full_name'] ?? ''),
        'email' => sanitize_email($raw_data['email'] ?? ''),
        'phone' => sanitize_text_field($raw_data['phone'] ?? ''),
        'selected_date' => sanitize_text_field($raw_data['selected_date'] ?? ''),
        'selected_time' => sanitize_text_field($raw_data['selected_time'] ?? ''),
        'treatment_id' => absint($raw_data['treatment_id'] ?? 0),
        'treatment_name' => sanitize_text_field($raw_data['treatment_name'] ?? ''),
        'how_did_you_hear' => sanitize_text_field($raw_data['how_did_you_hear'] ?? ''),
        'message' => sanitize_textarea_field($raw_data['message'] ?? ''),
        'consultation_required' => filter_var($raw_data['consultation_required'] ?? false, FILTER_VALIDATE_BOOLEAN),
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        'referrer' => $_SERVER['HTTP_REFERER'] ?? '',
        'submitted_at' => current_time('mysql')
    ];
}

/**
 * Validate booking data
 */
function validate_booking_data($data) {
    $errors = [];

    // Required field validation
    if (empty($data['full_name'])) {
        $errors['full_name'] = 'Full name is required.';
    } elseif (strlen($data['full_name']) < 2) {
        $errors['full_name'] = 'Full name must be at least 2 characters long.';
    }

    if (empty($data['email'])) {
        $errors['email'] = 'Email address is required.';
    } elseif (!is_email($data['email'])) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if (empty($data['phone'])) {
        $errors['phone'] = 'Phone number is required.';
    } elseif (!preg_match('/^[\+]?[1-9][\d]{0,15}$/', preg_replace('/[^\d\+]/', '', $data['phone']))) {
        $errors['phone'] = 'Please enter a valid phone number.';
    }

    if (empty($data['selected_date'])) {
        $errors['selected_date'] = 'Please select a date for your appointment.';
    } elseif (strtotime($data['selected_date']) <= strtotime('today')) {
        $errors['selected_date'] = 'Please select a future date.';
    } elseif (strtotime($data['selected_date']) > strtotime('+3 months')) {
        $errors['selected_date'] = 'Please select a date within the next 3 months.';
    }

    if (empty($data['selected_time'])) {
        $errors['selected_time'] = 'Please select a time for your appointment.';
    }

    if (empty($data['treatment_id']) || !get_post($data['treatment_id'])) {
        $errors['treatment_id'] = 'Invalid treatment selected.';
    }

    // Check for weekend dates (optional business rule)
    if (!empty($data['selected_date'])) {
        $day_of_week = date('N', strtotime($data['selected_date']));
        if ($day_of_week >= 6) { // Saturday = 6, Sunday = 7
            $errors['selected_date'] = 'We are closed on weekends. Please select a weekday.';
        }
    }

    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}

/**
 * Check time slot availability
 */
function check_time_slot_availability($date, $time, $treatment_id) {
    global $wpdb;

    // Check existing bookings for the same date/time
    $existing_bookings = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->prefix}treatment_bookings
         WHERE booking_date = %s
         AND booking_time = %s
         AND status NOT IN ('cancelled', 'rejected')
         AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)",
        $date,
        $time
    ));

    // Business rules for availability
    $max_concurrent_bookings = 2; // Allow 2 bookings per time slot
    $available = $existing_bookings < $max_concurrent_bookings;

    $response = ['available' => $available];

    // If not available, suggest alternative times
    if (!$available) {
        $response['alternatives'] = get_alternative_time_slots($date, $treatment_id);
    }

    return $response;
}

/**
 * Get alternative time slots
 */
function get_alternative_time_slots($preferred_date, $treatment_id) {
    $alternatives = [];
    $time_slots = [
        'morning' => ['9:00 AM', '9:30 AM', '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM'],
        'afternoon' => ['12:00 PM', '12:30 PM', '1:00 PM', '1:30 PM', '2:00 PM', '2:30 PM', '3:00 PM', '3:30 PM', '4:00 PM', '4:30 PM'],
        'evening' => ['5:00 PM', '5:30 PM', '6:00 PM', '6:30 PM', '7:00 PM', '7:30 PM']
    ];

    // Check same day alternatives
    foreach ($time_slots as $period => $times) {
        foreach ($times as $time) {
            $check = check_time_slot_availability($preferred_date, $time, $treatment_id);
            if ($check['available']) {
                $alternatives[] = [
                    'date' => $preferred_date,
                    'time' => $time,
                    'period' => $period
                ];
                if (count($alternatives) >= 3) break 2; // Limit to 3 alternatives
            }
        }
    }

    // If no same-day alternatives, check next few days
    if (empty($alternatives)) {
        for ($i = 1; $i <= 7; $i++) {
            $check_date = date('Y-m-d', strtotime($preferred_date . " +{$i} days"));
            $day_of_week = date('N', strtotime($check_date));

            // Skip weekends
            if ($day_of_week >= 6) continue;

            foreach ($time_slots['morning'] as $time) {
                $check = check_time_slot_availability($check_date, $time, $treatment_id);
                if ($check['available']) {
                    $alternatives[] = [
                        'date' => $check_date,
                        'time' => $time,
                        'period' => 'morning'
                    ];
                    if (count($alternatives) >= 3) break 2;
                }
            }
        }
    }

    return $alternatives;
}

/**
 * Create booking record in database
 */
function create_booking_record($data) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'treatment_bookings';

    // Create table if it doesn't exist
    create_bookings_table_if_not_exists();

    $result = $wpdb->insert(
        $table_name,
        [
            'treatment_id' => $data['treatment_id'],
            'treatment_name' => $data['treatment_name'],
            'customer_name' => $data['full_name'],
            'customer_email' => $data['email'],
            'customer_phone' => $data['phone'],
            'booking_date' => $data['selected_date'],
            'booking_time' => $data['selected_time'],
            'how_did_you_hear' => $data['how_did_you_hear'],
            'message' => $data['message'],
            'consultation_required' => $data['consultation_required'] ? 1 : 0,
            'status' => 'pending',
            'ip_address' => $data['ip_address'],
            'user_agent' => $data['user_agent'],
            'referrer' => $data['referrer'],
            'created_at' => $data['submitted_at'],
            'updated_at' => $data['submitted_at']
        ],
        [
            '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s'
        ]
    );

    return $result ? $wpdb->insert_id : false;
}

/**
 * Create bookings table if it doesn't exist
 */
function create_bookings_table_if_not_exists() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'treatment_bookings';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        treatment_id bigint(20) unsigned NOT NULL,
        treatment_name varchar(255) NOT NULL,
        customer_name varchar(255) NOT NULL,
        customer_email varchar(255) NOT NULL,
        customer_phone varchar(50) NOT NULL,
        booking_date date NOT NULL,
        booking_time varchar(20) NOT NULL,
        how_did_you_hear varchar(100) DEFAULT NULL,
        message text DEFAULT NULL,
        consultation_required tinyint(1) DEFAULT 0,
        status varchar(20) DEFAULT 'pending',
        booking_reference varchar(50) DEFAULT NULL,
        ip_address varchar(45) DEFAULT NULL,
        user_agent text DEFAULT NULL,
        referrer text DEFAULT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY idx_booking_date (booking_date),
        KEY idx_booking_time (booking_time),
        KEY idx_status (status),
        KEY idx_treatment_id (treatment_id),
        KEY idx_customer_email (customer_email)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

/**
 * Send booking confirmation emails
 */
function send_booking_emails($booking_id, $data) {
    $results = [];

    // Send customer confirmation email
    $results['customer'] = send_customer_confirmation_email($booking_id, $data);

    // Send admin notification email
    $results['admin'] = send_admin_notification_email($booking_id, $data);

    return [
        'success' => $results['customer'] && $results['admin'],
        'details' => $results
    ];
}

/**
 * Send customer confirmation email
 */
function send_customer_confirmation_email($booking_id, $data) {
    $subject = sprintf(
        'Booking Confirmation - %s at %s',
        $data['treatment_name'],
        get_bloginfo('name')
    );

    $booking_reference = generate_booking_reference($booking_id);
    $formatted_date = date('l, F j, Y', strtotime($data['selected_date']));

    $message = sprintf(
        "Dear %s,\n\n" .
        "Thank you for booking your %s appointment with us!\n\n" .
        "BOOKING DETAILS:\n" .
        "Reference: %s\n" .
        "Treatment: %s\n" .
        "Date: %s\n" .
        "Time: %s\n" .
        "Status: %s\n\n" .
        "WHAT'S NEXT:\n" .
        "• We'll contact you within 24 hours to confirm your appointment\n" .
        "• Please arrive 15 minutes early for your appointment\n" .
        "• Bring a valid ID and any relevant medical information\n" .
        "• If you need to reschedule, please call us at least 24 hours in advance\n\n" .
        "CONTACT INFORMATION:\n" .
        "Phone: %s\n" .
        "Email: %s\n" .
        "Address: %s\n\n" .
        "We look forward to seeing you!\n\n" .
        "Best regards,\n" .
        "The %s Team",
        $data['full_name'],
        $data['treatment_name'],
        $booking_reference,
        $data['treatment_name'],
        $formatted_date,
        $data['selected_time'],
        $data['consultation_required'] ? 'Consultation Requested' : 'Appointment Requested',
        get_theme_mod('contact_phone', ''),
        get_theme_mod('contact_email', get_option('admin_email')),
        get_theme_mod('contact_address', ''),
        get_bloginfo('name')
    );

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . get_bloginfo('name') . ' <' . get_theme_mod('contact_email', get_option('admin_email')) . '>'
    ];

    return wp_mail($data['email'], $subject, $message, $headers);
}

/**
 * Send admin notification email
 */
function send_admin_notification_email($booking_id, $data) {
    $admin_email = get_theme_mod('booking_notification_email', get_option('admin_email'));
    $booking_reference = generate_booking_reference($booking_id);
    $formatted_date = date('l, F j, Y', strtotime($data['selected_date']));

    $subject = sprintf(
        'New Booking Request - %s on %s',
        $data['treatment_name'],
        $formatted_date
    );

    $message = sprintf(
        "New booking request received:\n\n" .
        "BOOKING DETAILS:\n" .
        "Reference: %s\n" .
        "Treatment: %s\n" .
        "Date: %s\n" .
        "Time: %s\n" .
        "Consultation Required: %s\n\n" .
        "CUSTOMER DETAILS:\n" .
        "Name: %s\n" .
        "Email: %s\n" .
        "Phone: %s\n" .
        "How they heard about us: %s\n\n" .
        "MESSAGE:\n" .
        "%s\n\n" .
        "TECHNICAL DETAILS:\n" .
        "IP Address: %s\n" .
        "Submitted: %s\n" .
        "Referrer: %s\n\n" .
        "Please contact the customer within 24 hours to confirm the appointment.\n\n" .
        "Admin Panel: %s",
        $booking_reference,
        $data['treatment_name'],
        $formatted_date,
        $data['selected_time'],
        $data['consultation_required'] ? 'Yes' : 'No',
        $data['full_name'],
        $data['email'],
        $data['phone'],
        $data['how_did_you_hear'] ?: 'Not specified',
        $data['message'] ?: 'No additional message',
        $data['ip_address'],
        $data['submitted_at'],
        $data['referrer'] ?: 'Direct visit',
        admin_url('admin.php?page=treatment-bookings')
    );

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $data['full_name'] . ' <' . $data['email'] . '>'
    ];

    return wp_mail($admin_email, $subject, $message, $headers);
}

/**
 * Generate booking reference
 */
function generate_booking_reference($booking_id) {
    return 'MST-' . date('Y') . '-' . str_pad($booking_id, 6, '0', STR_PAD_LEFT);
}

/**
 * Format booking confirmation details
 */
function format_booking_confirmation($data) {
    return [
        'treatment' => $data['treatment_name'],
        'date' => date('l, F j, Y', strtotime($data['selected_date'])),
        'time' => $data['selected_time'],
        'customer' => $data['full_name'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'type' => $data['consultation_required'] ? 'Consultation' : 'Treatment'
    ];
}

/**
 * Get booking next steps
 */
function get_booking_next_steps($data) {
    $steps = [
        'We\'ll contact you within 24 hours to confirm your appointment',
        'Please arrive 15 minutes early for your appointment',
        'Bring a valid ID and any relevant medical information'
    ];

    if ($data['consultation_required']) {
        array_unshift($steps, 'Your consultation will help us determine the best treatment plan for you');
    }

    return $steps;
}

/**
 * Generate calendar event data
 */
function generate_calendar_event_data($data) {
    $start_datetime = date('Ymd\THis', strtotime($data['selected_date'] . ' ' . $data['selected_time']));
    $end_datetime = date('Ymd\THis', strtotime($data['selected_date'] . ' ' . $data['selected_time'] . ' +1 hour'));

    return [
        'title' => $data['treatment_name'] . ' at ' . get_bloginfo('name'),
        'start' => $start_datetime,
        'end' => $end_datetime,
        'description' => sprintf(
            '%s appointment at %s. Reference: %s',
            $data['treatment_name'],
            get_bloginfo('name'),
            generate_booking_reference(0) // Placeholder, will be updated with actual ID
        ),
        'location' => get_theme_mod('contact_address', ''),
        'url' => get_permalink($data['treatment_id'])
    ];
}

/**
 * Log booking activity
 */
function log_booking_activity($booking_id, $action, $data = []) {
    error_log(sprintf(
        'Booking Activity - ID: %d, Action: %s, Customer: %s, Treatment: %s, Date: %s',
        $booking_id,
        $action,
        $data['full_name'] ?? 'Unknown',
        $data['treatment_name'] ?? 'Unknown',
        $data['selected_date'] ?? 'Unknown'
    ));
}

// Register AJAX handlers
add_action('wp_ajax_handle_treatment_booking', 'handle_treatment_booking');
add_action('wp_ajax_nopriv_handle_treatment_booking', 'handle_treatment_booking');

/**
 * Get available time slots for a specific date (AJAX endpoint)
 */
function get_available_time_slots_ajax() {
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'treatment_booking_nonce')) {
        wp_send_json_error(['message' => 'Security verification failed.']);
    }

    $date = sanitize_text_field($_POST['date'] ?? '');
    $treatment_id = absint($_POST['treatment_id'] ?? 0);

    if (empty($date) || empty($treatment_id)) {
        wp_send_json_error(['message' => 'Invalid parameters.']);
    }

    $time_slots = [
        'morning' => ['9:00 AM', '9:30 AM', '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM'],
        'afternoon' => ['12:00 PM', '12:30 PM', '1:00 PM', '1:30 PM', '2:00 PM', '2:30 PM', '3:00 PM', '3:30 PM', '4:00 PM', '4:30 PM'],
        'evening' => ['5:00 PM', '5:30 PM', '6:00 PM', '6:30 PM', '7:00 PM', '7:30 PM']
    ];

    $available_slots = [];

    foreach ($time_slots as $period => $times) {
        $available_slots[$period] = [];
        foreach ($times as $time) {
            $availability = check_time_slot_availability($date, $time, $treatment_id);
            if ($availability['available']) {
                $available_slots[$period][] = $time;
            }
        }
    }

    wp_send_json_success(['time_slots' => $available_slots]);
}

add_action('wp_ajax_get_available_time_slots', 'get_available_time_slots_ajax');
add_action('wp_ajax_nopriv_get_available_time_slots', 'get_available_time_slots_ajax');

/**
 * AJAX Handler for Related Treatments
 * Dynamically loads related treatments based on current treatment
 */
add_action('wp_ajax_get_related_treatments', 'handle_get_related_treatments');
add_action('wp_ajax_nopriv_get_related_treatments', 'handle_get_related_treatments');

function handle_get_related_treatments() {
    // Verify request
    if (!isset($_GET['treatment_id'])) {
        wp_send_json_error('Treatment ID is required');
        return;
    }

    $treatment_id = intval($_GET['treatment_id']);

    if (!$treatment_id || get_post_type($treatment_id) !== 'treatment') {
        wp_send_json_error('Invalid treatment ID');
        return;
    }

    try {
        // Get related treatments
        $related_treatments = get_related_treatments($treatment_id, 3);

        if (empty($related_treatments)) {
            wp_send_json_success([
                'html' => '<div class="related-treatments__empty"><p>No related treatments found.</p></div>',
                'count' => 0
            ]);
            return;
        }

        // Generate HTML for related treatments
        ob_start();
        foreach ($related_treatments as $treatment) {
            echo ComponentRegistry::render('treatment-card', $treatment);
        }
        $html = ob_get_clean();

        wp_send_json_success([
            'html' => $html,
            'count' => count($related_treatments),
            'treatments' => $related_treatments
        ]);

    } catch (Exception $e) {
        error_log('Related treatments AJAX error: ' . $e->getMessage());
        wp_send_json_error('Failed to load related treatments');
    }
}

/**
 * Get related treatments for a given treatment
 *
 * @param int $treatment_id Current treatment ID
 * @param int $limit Number of treatments to return
 * @return array Array of treatment data
 */
function get_related_treatments($treatment_id, $limit = 3) {
    $related_treatments = [];

    // Get current treatment categories
    $treatment_categories = get_the_terms($treatment_id, 'treatment_category');

    if ($treatment_categories && !is_wp_error($treatment_categories)) {
        $category_ids = wp_list_pluck($treatment_categories, 'term_id');

        // Query for related treatments in same category
        $related_query = new WP_Query([
            'post_type' => 'treatment',
            'posts_per_page' => $limit,
            'post__not_in' => [$treatment_id],
            'tax_query' => [
                [
                    'taxonomy' => 'treatment_category',
                    'field' => 'term_id',
                    'terms' => $category_ids,
                    'operator' => 'IN'
                ]
            ],
            'meta_key' => 'treatment_featured',
            'orderby' => 'meta_value_num',
            'order' => 'DESC'
        ]);

        if ($related_query->have_posts()) {
            while ($related_query->have_posts()) {
                $related_query->the_post();
                $related_treatments[] = format_treatment_data(get_the_ID());
            }
            wp_reset_postdata();
        }
    }

    // If no related treatments found, get popular treatments
    if (empty($related_treatments)) {
        $popular_query = new WP_Query([
            'post_type' => 'treatment',
            'posts_per_page' => $limit,
            'post__not_in' => [$treatment_id],
            'meta_key' => 'treatment_popularity_score',
            'orderby' => 'meta_value_num',
            'order' => 'DESC'
        ]);

        if ($popular_query->have_posts()) {
            while ($popular_query->have_posts()) {
                $popular_query->the_post();
                $related_treatments[] = format_treatment_data(get_the_ID());
            }
            wp_reset_postdata();
        }
    }

    return $related_treatments;
}

/**
 * Format treatment data for consistent output
 *
 * @param int $treatment_id Treatment post ID
 * @return array Formatted treatment data
 */
function format_treatment_data($treatment_id) {
    return [
        'id' => $treatment_id,
        'title' => get_the_title($treatment_id),
        'description' => get_field('treatment_short_description', $treatment_id) ?: wp_trim_words(get_post_field('post_excerpt', $treatment_id), 20),
        'duration' => get_field('treatment_duration', $treatment_id) ?: '',
        'icon' => get_field('treatment_icon', $treatment_id) ?: '✨',
        'benefits' => array_slice(get_field('treatment_benefits', $treatment_id) ?: [], 0, 3),
        'image' => [
            'src' => get_the_post_thumbnail_url($treatment_id, 'medium') ?: '',
            'alt' => get_the_title($treatment_id) . ' treatment'
        ],
        'cta' => [
            'primary' => [
                'text' => 'Learn More',
                'url' => get_permalink($treatment_id)
            ],
            'secondary' => [
                'text' => 'Book Now',
                'url' => '#booking'
            ]
        ],
        'schema' => [
            '@type' => 'MedicalProcedure',
            'name' => get_the_title($treatment_id),
            'description' => get_field('treatment_short_description', $treatment_id) ?: wp_trim_words(get_post_field('post_excerpt', $treatment_id), 20),
            'bodyLocation' => get_field('treatment_body_location', $treatment_id) ?: '',
            'procedureType' => get_field('treatment_type', $treatment_id) ?: 'Cosmetic'
        ]
    ];
}
