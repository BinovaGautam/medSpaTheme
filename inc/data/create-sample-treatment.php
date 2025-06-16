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

/**
 * Create additional sample treatments for testing related treatments
 */
function create_additional_sample_treatments() {
    // Sample treatments data
    $treatments = [
        [
            'title' => 'HydraFacial MD',
            'slug' => 'hydrafacial-md',
            'category' => 'facial-treatments',
            'short_description' => 'Advanced multi-step facial treatment that cleanses, extracts, and hydrates skin.',
            'duration' => '60 minutes',
            'icon' => '💧',
            'type' => 'Facial Treatment',
            'body_location' => 'Face',
            'benefits' => [
                'Deep cleansing and exfoliation',
                'Improved skin texture and tone',
                'Reduced appearance of fine lines',
                'Instant hydration boost'
            ]
        ],
        [
            'title' => 'Dermaplaning',
            'slug' => 'dermaplaning',
            'category' => 'facial-treatments',
            'short_description' => 'Gentle exfoliation treatment that removes dead skin cells and peach fuzz.',
            'duration' => '45 minutes',
            'icon' => '✨',
            'type' => 'Exfoliation Treatment',
            'body_location' => 'Face',
            'benefits' => [
                'Smoother skin texture',
                'Better product absorption',
                'Brighter complexion',
                'Makeup application improvement'
            ]
        ],
        [
            'title' => 'Microneedling with PRP',
            'slug' => 'microneedling-prp',
            'category' => 'regenerative-treatments',
            'short_description' => 'Collagen induction therapy combined with platelet-rich plasma for skin rejuvenation.',
            'duration' => '90 minutes',
            'icon' => '🩸',
            'type' => 'Regenerative Treatment',
            'body_location' => 'Face, Neck',
            'benefits' => [
                'Stimulates collagen production',
                'Reduces acne scars',
                'Improves skin texture',
                'Natural healing enhancement'
            ]
        ],
        [
            'title' => 'IV Vitamin Therapy',
            'slug' => 'iv-vitamin-therapy',
            'category' => 'wellness-treatments',
            'short_description' => 'Intravenous vitamin and mineral infusions for optimal health and wellness.',
            'duration' => '45-60 minutes',
            'icon' => '💉',
            'type' => 'Wellness Treatment',
            'body_location' => 'Systemic',
            'benefits' => [
                'Boosts energy levels',
                'Enhances immune system',
                'Improves hydration',
                'Supports overall wellness'
            ]
        ]
    ];

    foreach ($treatments as $treatment_data) {
        // Check if treatment already exists
        $existing = get_page_by_path($treatment_data['slug'], OBJECT, 'treatment');
        if ($existing) {
            continue; // Skip if already exists
        }

        // Create the treatment post
        $treatment_id = wp_insert_post([
            'post_title' => $treatment_data['title'],
            'post_name' => $treatment_data['slug'],
            'post_type' => 'treatment',
            'post_status' => 'publish',
            'post_content' => 'Sample treatment content for ' . $treatment_data['title'],
            'post_excerpt' => $treatment_data['short_description']
        ]);

        if ($treatment_id && !is_wp_error($treatment_id)) {
            // Set treatment category
            wp_set_object_terms($treatment_id, $treatment_data['category'], 'treatment_category');

            // Add custom fields (simulating ACF fields)
            update_post_meta($treatment_id, 'treatment_short_description', $treatment_data['short_description']);
            update_post_meta($treatment_id, 'treatment_duration', $treatment_data['duration']);
            update_post_meta($treatment_id, 'treatment_icon', $treatment_data['icon']);
            update_post_meta($treatment_id, 'treatment_type', $treatment_data['type']);
            update_post_meta($treatment_id, 'treatment_body_location', $treatment_data['body_location']);
            update_post_meta($treatment_id, 'treatment_benefits', $treatment_data['benefits']);
            update_post_meta($treatment_id, 'treatment_featured', rand(1, 10)); // Random featured score
            update_post_meta($treatment_id, 'treatment_popularity_score', rand(50, 100)); // Random popularity

            error_log("Created sample treatment: {$treatment_data['title']} (ID: {$treatment_id})");
        }
    }
}

/**
 * Create treatment categories if they don't exist
 */
function create_treatment_categories() {
    $categories = [
        'injectable-treatments' => 'Injectable Treatments',
        'facial-treatments' => 'Facial Treatments',
        'regenerative-treatments' => 'Regenerative Treatments',
        'wellness-treatments' => 'Wellness Treatments',
        'body-treatments' => 'Body Treatments'
    ];

    foreach ($categories as $slug => $name) {
        if (!term_exists($slug, 'treatment_category')) {
            wp_insert_term($name, 'treatment_category', [
                'slug' => $slug
            ]);
        }
    }
}

// Hook to create additional treatments and categories
add_action('init', function() {
    // Only run once or when specifically triggered
    if (get_option('sample_treatments_created') !== 'yes') {
        create_treatment_categories();
        create_additional_sample_treatments();
        update_option('sample_treatments_created', 'yes');
    }
});

/**
 * Admin function to recreate sample treatments
 */
function recreate_sample_treatments() {
    delete_option('sample_treatments_created');
    create_treatment_categories();
    create_additional_sample_treatments();
    update_option('sample_treatments_created', 'yes');
}

// Add admin menu item for testing
add_action('admin_menu', function() {
    add_submenu_page(
        'edit.php?post_type=treatment',
        'Create Sample Data',
        'Sample Data',
        'manage_options',
        'create-sample-treatments',
        function() {
            if (isset($_POST['create_samples'])) {
                recreate_sample_treatments();
                echo '<div class="notice notice-success"><p>Sample treatments created successfully!</p></div>';
            }
            ?>
            <div class="wrap">
                <h1>Create Sample Treatment Data</h1>
                <p>This will create sample treatments for testing the related treatments functionality.</p>
                <form method="post">
                    <input type="submit" name="create_samples" class="button button-primary" value="Create Sample Treatments">
                </form>
            </div>
            <?php
        }
    );
});
