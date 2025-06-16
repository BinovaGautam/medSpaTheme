<?php
/**
 * Create Sample Treatment Post
 *
 * Helper file to create sample treatment data for testing
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create sample Injectable Artistry treatment post
 */
function create_sample_injectable_artistry_treatment() {
    // Check if post already exists
    $existing_post = get_page_by_path('injectable-artistry', OBJECT, 'treatment');
    if ($existing_post) {
        return $existing_post->ID;
    }

    // Create the treatment post
    $post_data = [
        'post_title' => 'Injectable Artistry',
        'post_content' => 'Enhance your natural beauty with precision injectable treatments. Our expert practitioners use advanced techniques for natural-looking results that restore youthful vitality while maintaining your unique character.',
        'post_status' => 'publish',
        'post_type' => 'treatment',
        'post_name' => 'injectable-artistry',
        'post_excerpt' => 'Botox & Dermal Fillers for natural-looking results with minimal downtime.',
    ];

    $post_id = wp_insert_post($post_data);

    if (!is_wp_error($post_id)) {
        // Add basic custom fields (non-ACF)
        update_post_meta($post_id, '_treatment_subtitle', 'Botox & Dermal Fillers');
        update_post_meta($post_id, '_treatment_duration', '30-45 minutes');
        update_post_meta($post_id, '_treatment_price', '$599');
        update_post_meta($post_id, '_treatment_price_from', true);
        update_post_meta($post_id, '_treatment_pain_level', 'mild');
        update_post_meta($post_id, '_treatment_downtime', '24-48 hours');
        update_post_meta($post_id, '_treatment_results_timeline', '7-14 days');
        update_post_meta($post_id, '_treatment_popularity', 'popular');

        // Set treatment category if taxonomy exists
        if (taxonomy_exists('treatment_category')) {
            wp_set_object_terms($post_id, 'injectable', 'treatment_category');
        }

        return $post_id;
    }

    return false;
}

// Auto-create sample treatment if none exists (only in development)
add_action('init', function() {
    if (post_type_exists('treatment') && (defined('WP_DEBUG') && WP_DEBUG)) {
        create_sample_injectable_artistry_treatment();
    }
}, 999);
