<?php
/**
 * Treatments Data Store
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class TreatmentsDataStore {
    /**
     * Get all available treatment categories
     *
     * @return array Treatment categories
     */
    public static function get_categories(): array {
        return [
            'facial' => __('Facial Treatments', 'medspa'),
            'injectable' => __('Injectable Treatments', 'medspa'),
            'body' => __('Body Treatments', 'medspa'),
            'laser' => __('Laser Treatments', 'medspa'),
            'wellness' => __('Wellness Treatments', 'medspa'),
        ];
    }

    /**
     * Get treatment data schema
     *
     * @return array Treatment data schema
     */
    public static function get_schema(): array {
        return [
            'id' => [
                'type' => 'string',
                'required' => true,
                'description' => 'Unique identifier for the treatment',
            ],
            'title' => [
                'type' => 'string',
                'required' => true,
                'description' => 'Treatment title',
            ],
            'subtitle' => [
                'type' => 'string',
                'required' => false,
                'description' => 'Treatment subtitle',
            ],
            'description' => [
                'type' => 'string',
                'required' => true,
                'description' => 'Treatment description',
            ],
            'category' => [
                'type' => 'string',
                'required' => true,
                'description' => 'Treatment category',
                'enum' => array_keys(self::get_categories()),
            ],
            'image' => [
                'type' => 'string',
                'required' => false,
                'description' => 'Treatment image URL',
            ],
            'benefits' => [
                'type' => 'array',
                'required' => false,
                'description' => 'Treatment benefits',
                'items' => [
                    'type' => 'string',
                ],
            ],
            'features' => [
                'type' => 'array',
                'required' => false,
                'description' => 'Treatment features',
                'items' => [
                    'type' => 'string',
                ],
            ],
            'duration' => [
                'type' => 'string',
                'required' => true,
                'description' => 'Treatment duration',
            ],
            'price' => [
                'type' => 'string',
                'required' => true,
                'description' => 'Treatment price',
            ],
            'price_from' => [
                'type' => 'boolean',
                'required' => false,
                'description' => 'Whether the price is a starting price',
                'default' => false,
            ],
            'pain_level' => [
                'type' => 'string',
                'required' => false,
                'description' => 'Treatment pain level',
                'enum' => ['minimal', 'mild', 'moderate'],
            ],
            'downtime' => [
                'type' => 'string',
                'required' => false,
                'description' => 'Treatment downtime',
            ],
            'results_timeline' => [
                'type' => 'string',
                'required' => false,
                'description' => 'Treatment results timeline',
            ],
            'popularity' => [
                'type' => 'string',
                'required' => false,
                'description' => 'Treatment popularity',
                'enum' => ['popular', 'trending', 'new'],
            ],
            'cta_text' => [
                'type' => 'string',
                'required' => false,
                'description' => 'Call to action text',
                'default' => 'Learn More',
            ],
            'cta_url' => [
                'type' => 'string',
                'required' => false,
                'description' => 'Call to action URL',
            ],
            'book_text' => [
                'type' => 'string',
                'required' => false,
                'description' => 'Booking button text',
                'default' => 'Book Consultation',
            ],
            'book_url' => [
                'type' => 'string',
                'required' => false,
                'description' => 'Booking URL',
            ],
        ];
    }

    /**
     * Get all treatments
     *
     * @return array Array of treatments
     */
    public static function get_treatments(): array {
        // This is a temporary implementation that returns hardcoded data
        // In production, this should be connected to WordPress custom post types
        return [
            [
                'id' => 'injectable-artistry',
                'title' => 'Injectable Artistry',
                'subtitle' => 'Botox & Dermal Fillers',
                'description' => 'Enhance your natural beauty with precision injectable treatments. Our expert practitioners use advanced techniques for natural-looking results that restore youthful vitality while maintaining your unique character.',
                'category' => 'injectable',
                'image' => get_template_directory_uri() . '/assets/images/treatments/injectable-artistry.jpg',
                'benefits' => [
                    'Natural-looking results',
                    'Minimal downtime',
                    'Long-lasting effects',
                ],
                'features' => [
                    'FDA-approved products',
                    'Expert practitioners',
                    'Customized treatment plans',
                ],
                'duration' => '30-45 minutes',
                'price' => '$599',
                'price_from' => true,
                'pain_level' => 'mild',
                'downtime' => '24-48 hours',
                'results_timeline' => '7-14 days',
                'popularity' => 'popular',
            ],
            [
                'id' => 'facial-renaissance',
                'title' => 'Facial Renaissance',
                'subtitle' => 'HydraFacial Treatment',
                'description' => 'Experience the ultimate skin rejuvenation with our signature HydraFacial treatment. This multi-step process cleanses, extracts, and hydrates your skin for an immediate glow and long-lasting radiance.',
                'category' => 'facial',
                'image' => get_template_directory_uri() . '/assets/images/treatments/facial-renaissance.jpg',
                'benefits' => [
                    'Deep cleansing',
                    'Instant hydration',
                    'Improved skin texture',
                ],
                'features' => [
                    'No downtime',
                    'Suitable for all skin types',
                    'Immediate results',
                ],
                'duration' => '45-60 minutes',
                'price' => '$299',
                'pain_level' => 'minimal',
                'downtime' => 'None',
                'results_timeline' => 'Immediate',
                'popularity' => 'trending',
            ],
            // Add more treatments here...
        ];
    }

    /**
     * Validate treatment data against schema
     *
     * @param array $treatment Treatment data to validate
     * @return bool|WP_Error True if valid, WP_Error if invalid
     */
    public static function validate_treatment($treatment) {
        $schema = self::get_schema();
        $errors = [];

        // Check required fields
        foreach ($schema as $field => $rules) {
            if ($rules['required'] && empty($treatment[$field])) {
                $errors[] = sprintf(
                    /* translators: %s: field name */
                    __('Field "%s" is required', 'medspa'),
                    $field
                );
            }
        }

        // Check enum values
        foreach ($schema as $field => $rules) {
            if (!empty($treatment[$field]) && !empty($rules['enum'])) {
                if (!in_array($treatment[$field], $rules['enum'])) {
                    $errors[] = sprintf(
                        /* translators: 1: field name, 2: allowed values */
                        __('Field "%1$s" must be one of: %2$s', 'medspa'),
                        $field,
                        implode(', ', $rules['enum'])
                    );
                }
            }
        }

        if (!empty($errors)) {
            return new WP_Error('invalid_treatment', implode('. ', $errors));
        }

        return true;
    }
}
