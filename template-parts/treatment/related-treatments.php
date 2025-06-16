<?php
/**
 * Related Treatments Section
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get current treatment ID and category
$current_treatment_id = get_the_ID();
$treatment_categories = get_the_terms($current_treatment_id, 'treatment_category');
$related_treatments = [];

// Get related treatments from same category
if ($treatment_categories && !is_wp_error($treatment_categories)) {
    $category_ids = wp_list_pluck($treatment_categories, 'term_id');

    $related_query = new WP_Query([
        'post_type' => 'treatment',
        'posts_per_page' => 3,
        'post__not_in' => [$current_treatment_id],
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

            // Prepare treatment data for TreatmentCard component
            $related_treatments[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'description' => get_field('treatment_short_description') ?: wp_trim_words(get_the_excerpt(), 20),
                'duration' => get_field('treatment_duration') ?: '',
                'icon' => get_field('treatment_icon') ?: '✨',
                'benefits' => array_slice(get_field('treatment_benefits') ?: [], 0, 3), // Top 3 benefits
                'image' => [
                    'src' => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
                    'alt' => get_the_title() . ' treatment'
                ],
                'cta' => [
                    'primary' => [
                        'text' => 'Learn More',
                        'url' => get_permalink()
                    ],
                    'secondary' => [
                        'text' => 'Book Now',
                        'url' => '#booking'
                    ]
                ],
                'schema' => [
                    '@type' => 'MedicalProcedure',
                    'name' => get_the_title(),
                    'description' => get_field('treatment_short_description') ?: wp_trim_words(get_the_excerpt(), 20),
                    'bodyLocation' => get_field('treatment_body_location') ?: '',
                    'procedureType' => get_field('treatment_type') ?: 'Cosmetic'
                ]
            ];
        }
        wp_reset_postdata();
    }
}

// If no related treatments found, get popular treatments
if (empty($related_treatments)) {
    $popular_query = new WP_Query([
        'post_type' => 'treatment',
        'posts_per_page' => 3,
        'post__not_in' => [$current_treatment_id],
        'meta_key' => 'treatment_popularity_score',
        'orderby' => 'meta_value_num',
        'order' => 'DESC'
    ]);

    if ($popular_query->have_posts()) {
        while ($popular_query->have_posts()) {
            $popular_query->the_post();

            $related_treatments[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'description' => get_field('treatment_short_description') ?: wp_trim_words(get_the_excerpt(), 20),
                'duration' => get_field('treatment_duration') ?: '',
                'icon' => get_field('treatment_icon') ?: '✨',
                'benefits' => array_slice(get_field('treatment_benefits') ?: [], 0, 3),
                'image' => [
                    'src' => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
                    'alt' => get_the_title() . ' treatment'
                ],
                'cta' => [
                    'primary' => [
                        'text' => 'Learn More',
                        'url' => get_permalink()
                    ],
                    'secondary' => [
                        'text' => 'Book Now',
                        'url' => '#booking'
                    ]
                ],
                'schema' => [
                    '@type' => 'MedicalProcedure',
                    'name' => get_the_title(),
                    'description' => get_field('treatment_short_description') ?: wp_trim_words(get_the_excerpt(), 20),
                    'bodyLocation' => get_field('treatment_body_location') ?: '',
                    'procedureType' => get_field('treatment_type') ?: 'Cosmetic'
                ]
            ];
        }
        wp_reset_postdata();
    }
}
?>

<div class="related-treatments">

    <?php if (!empty($related_treatments)): ?>

        <div class="related-treatments__header">
            <h3 class="related-treatments__heading">You Might Also Like</h3>
            <p class="related-treatments__description">
                Explore other popular treatments that complement your wellness journey.
            </p>
        </div>

        <div class="related-treatments__grid">
            <?php
            // Use TreatmentCard component to render each related treatment
            foreach ($related_treatments as $treatment) {
                echo ComponentRegistry::render('treatment-card', $treatment);
            }
            ?>
        </div>

        <div class="related-treatments__actions">
            <?php
            // Use ButtonComponent for view all treatments
            echo ComponentRegistry::render('button', [
                'text' => 'View All Treatments',
                'url' => get_post_type_archive_link('treatment'),
                'variant' => 'outline',
                'size' => 'large',
                'icon' => '👁️',
                'icon_position' => 'left',
                'classes' => ['related-treatments__view-all']
            ]);
            ?>
        </div>

    <?php else: ?>

        <div class="related-treatments__empty">
            <div class="related-treatments__empty-content">
                <span class="related-treatments__empty-icon" aria-hidden="true">🔍</span>
                <h3 class="related-treatments__empty-heading">Explore Our Treatments</h3>
                <p class="related-treatments__empty-text">
                    Discover our full range of medical spa treatments designed to help you look and feel your best.
                </p>
                <?php
                echo ComponentRegistry::render('button', [
                    'text' => 'Browse All Treatments',
                    'url' => get_post_type_archive_link('treatment'),
                    'variant' => 'primary',
                    'size' => 'large',
                    'classes' => ['related-treatments__browse-button']
                ]);
                ?>
            </div>
        </div>

    <?php endif; ?>

</div>
