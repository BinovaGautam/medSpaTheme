<?php
/**
 * Treatments Data Adapter Pattern
 *
 * Provides abstraction layer for treatment data sources
 * Allows switching between hardcoded, CMS, or external API data
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Interface for treatment data sources
 */
interface TreatmentDataSourceInterface {
    /**
     * Get all treatments
     *
     * @return array Array of treatment data
     */
    public function get_treatments(): array;

    /**
     * Get single treatment by ID
     *
     * @param string $id Treatment ID
     * @return array|null Treatment data or null if not found
     */
    public function get_treatment($id): ?array;

    /**
     * Get treatments by category
     *
     * @param string $category Category slug
     * @return array Array of treatment data
     */
    public function get_treatments_by_category($category): array;

    /**
     * Check if data source is available
     *
     * @return bool True if data source is available
     */
    public function is_available(): bool;
}

/**
 * Hardcoded data source implementation
 */
class HardcodedTreatmentDataSource implements TreatmentDataSourceInterface {

    /**
     * Raw treatment data (simulating database structure)
     * UPDATED: Aligned with TREATMENTS_OVERVIEW_PAGE_DESIGN.md specifications
     * Total Services: 9 (as per design document)
     */
    private static function get_raw_data(): array {
        return [
            [
                'id' => 'injectable-artistry',
                'post_title' => 'Botox / Fillers',
                'post_content' => '💉 Smooth wrinkles & restore volume with precision artistry. Experience natural-looking results with FDA-approved Botox and dermal fillers.',
                'post_excerpt' => 'Premium injectable treatments for natural facial enhancement',
                'post_status' => 'publish',
                'meta_fields' => [
                    '_treatment_icon' => '💉',
                    '_treatment_category' => 'injectable',
                    '_treatment_duration' => '30-60 minutes',
                    '_treatment_benefits' => ['FDA-approved treatments', 'Natural-looking results', 'Minimal downtime', 'Long-lasting effects'],
                    '_treatment_cta_primary_text' => 'Book Now',
                    '_treatment_cta_primary_url' => '/book-appointment/?treatment=injectable-artistry',
                    '_treatment_cta_secondary_text' => 'Learn More',
                    '_treatment_cta_secondary_url' => '/treatments/injectable-artistry/',
                    '_treatment_featured_image' => get_template_directory_uri() . '/assets/images/treatments/injectable-artistry.jpg',
                    '_treatment_featured_image_alt' => 'Medical spa injectable treatment procedure',
                    '_treatment_schema_type' => 'MedicalProcedure',
                    '_treatment_schema_body_location' => 'Face',
                    '_treatment_schema_procedure_type' => 'Non-surgical procedure'
                ],
                'taxonomies' => [
                    'treatment_category' => ['injectable'],
                    'treatment_feature' => ['fda-approved', 'minimal-downtime'],
                    'treatment_benefit' => ['natural-results', 'long-lasting']
                ]
            ],
            [
                'id' => 'facial-renaissance',
                'post_title' => 'HydraFacial',
                'post_content' => '🌊 Deep cleanse & hydrate for radiant skin with our advanced HydraFacial treatment. Experience instant glow and skin renewal.',
                'post_excerpt' => 'Advanced HydraFacial treatment for skin rejuvenation',
                'post_status' => 'publish',
                'meta_fields' => [
                    '_treatment_icon' => '🌊',
                    '_treatment_category' => 'facial',
                    '_treatment_duration' => '60 minutes',
                    '_treatment_benefits' => ['Deep cleansing', 'Instant hydration', 'Immediate glow', 'Customized treatment'],
                    '_treatment_cta_primary_text' => 'Book Now',
                    '_treatment_cta_primary_url' => '/book-appointment/?treatment=facial-renaissance',
                    '_treatment_cta_secondary_text' => 'Learn More',
                    '_treatment_cta_secondary_url' => '/treatments/facial-renaissance/',
                    '_treatment_featured_image' => get_template_directory_uri() . '/assets/images/treatments/facial-renaissance.jpg',
                    '_treatment_featured_image_alt' => 'Medical spa HydraFacial treatment procedure',
                    '_treatment_schema_type' => 'MedicalProcedure',
                    '_treatment_schema_body_location' => 'Face',
                    '_treatment_schema_procedure_type' => 'Cosmetic procedure'
                ],
                'taxonomies' => [
                    'treatment_category' => ['facial'],
                    'treatment_feature' => ['deep-cleansing', 'instant-results'],
                    'treatment_benefit' => ['rejuvenation', 'customized']
                ]
            ],
            [
                'id' => 'precision-dermaplanning',
                'post_title' => 'Dermaplanning',
                'post_content' => '✨ Gentle exfoliation for smooth, glowing skin. Achieve luminous texture and enhanced product absorption with professional dermaplanning.',
                'post_excerpt' => 'Professional dermaplanning for smooth, radiant skin',
                'post_status' => 'publish',
                'meta_fields' => [
                    '_treatment_icon' => '✨',
                    '_treatment_category' => 'precision',
                    '_treatment_duration' => '45 minutes',
                    '_treatment_benefits' => ['Smooth texture', 'Enhanced product absorption', 'No downtime', 'Immediate results'],
                    '_treatment_cta_primary_text' => 'Book Now',
                    '_treatment_cta_primary_url' => '/book-appointment/?treatment=precision-dermaplanning',
                    '_treatment_cta_secondary_text' => 'Learn More',
                    '_treatment_cta_secondary_url' => '/treatments/precision-dermaplanning/',
                    '_treatment_featured_image' => get_template_directory_uri() . '/assets/images/treatments/precision-dermaplanning.jpg',
                    '_treatment_featured_image_alt' => 'Medical spa dermaplanning treatment',
                    '_treatment_schema_type' => 'MedicalProcedure',
                    '_treatment_schema_body_location' => 'Face',
                    '_treatment_schema_procedure_type' => 'Exfoliation procedure'
                ],
                'taxonomies' => [
                    'treatment_category' => ['precision'],
                    'treatment_feature' => ['no-downtime', 'immediate-results'],
                    'treatment_benefit' => ['smooth-texture', 'enhanced-absorption']
                ]
            ],
            [
                'id' => 'regenerative-prp',
                'post_title' => 'Microneedling PRP',
                'post_content' => '🩸 Stimulate natural collagen production with microneedling PRP therapy. Experience the power of your body\'s own healing factors.',
                'post_excerpt' => 'Advanced microneedling PRP treatments for natural regeneration',
                'post_status' => 'publish',
                'meta_fields' => [
                    '_treatment_icon' => '🩸',
                    '_treatment_category' => 'regenerative',
                    '_treatment_duration' => '60 minutes',
                    '_treatment_benefits' => ['Natural healing', 'Collagen stimulation', 'Multiple applications', 'Progressive improvement'],
                    '_treatment_cta_primary_text' => 'Book Now',
                    '_treatment_cta_primary_url' => '/book-appointment/?treatment=regenerative-prp',
                    '_treatment_cta_secondary_text' => 'Learn More',
                    '_treatment_cta_secondary_url' => '/treatments/regenerative-prp/',
                    '_treatment_featured_image' => get_template_directory_uri() . '/assets/images/treatments/regenerative-prp.jpg',
                    '_treatment_featured_image_alt' => 'Medical spa microneedling PRP treatment procedure',
                    '_treatment_schema_type' => 'MedicalProcedure',
                    '_treatment_schema_body_location' => 'Face',
                    '_treatment_schema_procedure_type' => 'Regenerative procedure'
                ],
                'taxonomies' => [
                    'treatment_category' => ['regenerative'],
                    'treatment_feature' => ['natural-healing', 'progressive'],
                    'treatment_benefit' => ['collagen-stimulation', 'multiple-applications']
                ]
            ],
            [
                'id' => 'wellness-infusions',
                'post_title' => 'IV Vitamins',
                'post_content' => '💊 Boost wellness & energy from within with our premium IV vitamin therapy treatments. Experience enhanced vitality and improved immunity.',
                'post_excerpt' => 'Premium IV vitamin therapy for enhanced wellness and vitality',
                'post_status' => 'publish',
                'meta_fields' => [
                    '_treatment_icon' => '💊',
                    '_treatment_category' => 'wellness',
                    '_treatment_duration' => '45 minutes',
                    '_treatment_benefits' => ['Enhanced wellness', 'Improved immunity', 'Increased energy', 'Vitamin optimization'],
                    '_treatment_cta_primary_text' => 'Book Now',
                    '_treatment_cta_primary_url' => '/book-appointment/?treatment=wellness-infusions',
                    '_treatment_cta_secondary_text' => 'Learn More',
                    '_treatment_cta_secondary_url' => '/treatments/wellness-infusions/',
                    '_treatment_featured_image' => get_template_directory_uri() . '/assets/images/treatments/wellness-infusions.jpg',
                    '_treatment_featured_image_alt' => 'Medical spa IV vitamin therapy treatment',
                    '_treatment_schema_type' => 'MedicalProcedure',
                    '_treatment_schema_body_location' => 'Systemic',
                    '_treatment_schema_procedure_type' => 'Wellness procedure'
                ],
                'taxonomies' => [
                    'treatment_category' => ['wellness'],
                    'treatment_feature' => ['iv-therapy', 'vitamin-optimization'],
                    'treatment_benefit' => ['enhanced-wellness', 'improved-immunity']
                ]
            ],
            [
                'id' => 'artistry-enhancement',
                'post_title' => 'Permanent Makeup',
                'post_content' => '💄 Wake up beautiful every day with our permanent makeup artistry. Achieve perfectly defined brows, lips, and eyes with precision enhancement techniques.',
                'post_excerpt' => 'Professional permanent makeup and cosmetic tattooing services',
                'post_status' => 'publish',
                'meta_fields' => [
                    '_treatment_icon' => '💄',
                    '_treatment_category' => 'artistry',
                    '_treatment_duration' => '90-120 minutes',
                    '_treatment_benefits' => ['Permanent results', 'Time-saving beauty', 'Natural enhancement', 'Customized approach'],
                    '_treatment_cta_primary_text' => 'Book Now',
                    '_treatment_cta_primary_url' => '/book-appointment/?treatment=artistry-enhancement',
                    '_treatment_cta_secondary_text' => 'Learn More',
                    '_treatment_cta_secondary_url' => '/treatments/artistry-enhancement/',
                    '_treatment_featured_image' => get_template_directory_uri() . '/assets/images/treatments/artistry-enhancement.jpg',
                    '_treatment_featured_image_alt' => 'Medical spa permanent makeup treatment',
                    '_treatment_schema_type' => 'MedicalProcedure',
                    '_treatment_schema_body_location' => 'Face',
                    '_treatment_schema_procedure_type' => 'Cosmetic tattooing procedure'
                ],
                'taxonomies' => [
                    'treatment_category' => ['artistry'],
                    'treatment_feature' => ['permanent-results', 'customized'],
                    'treatment_benefit' => ['time-saving', 'natural-enhancement']
                ]
            ],
            [
                'id' => 'laser-precision',
                'post_title' => 'Laser Hair Reduction',
                'post_content' => '🔥 Permanent hair removal technology with advanced laser systems. Experience precise, controlled treatment for smooth, hair-free skin.',
                'post_excerpt' => 'Advanced laser hair removal treatments for permanent results',
                'post_status' => 'publish',
                'meta_fields' => [
                    '_treatment_icon' => '🔥',
                    '_treatment_category' => 'laser',
                    '_treatment_duration' => '30-90 minutes',
                    '_treatment_benefits' => ['Permanent hair removal', 'Advanced technology', 'Precise treatment', 'All skin types'],
                    '_treatment_cta_primary_text' => 'Book Now',
                    '_treatment_cta_primary_url' => '/book-appointment/?treatment=laser-precision',
                    '_treatment_cta_secondary_text' => 'Learn More',
                    '_treatment_cta_secondary_url' => '/treatments/laser-precision/',
                    '_treatment_featured_image' => get_template_directory_uri() . '/assets/images/treatments/laser-precision.jpg',
                    '_treatment_featured_image_alt' => 'Medical spa laser hair removal treatment',
                    '_treatment_schema_type' => 'MedicalProcedure',
                    '_treatment_schema_body_location' => 'Multiple',
                    '_treatment_schema_procedure_type' => 'Laser procedure'
                ],
                'taxonomies' => [
                    'treatment_category' => ['laser'],
                    'treatment_feature' => ['advanced-technology', 'permanent-results'],
                    'treatment_benefit' => ['hair-removal', 'all-skin-types']
                ]
            ],
            [
                'id' => 'carbon-rejuvenation',
                'post_title' => 'Carbon Peel Laser',
                'post_content' => '🌟 Resurface & rejuvenate your skin with revolutionary carbon peel laser treatment. Experience deep pore cleansing and immediate glow.',
                'post_excerpt' => 'Revolutionary carbon peel laser for skin resurfacing and renewal',
                'post_status' => 'publish',
                'meta_fields' => [
                    '_treatment_icon' => '🌟',
                    '_treatment_category' => 'carbon',
                    '_treatment_duration' => '45 minutes',
                    '_treatment_benefits' => ['Deep pore cleansing', 'Immediate glow', 'Skin resurfacing', 'Revolutionary technology'],
                    '_treatment_cta_primary_text' => 'Book Now',
                    '_treatment_cta_primary_url' => '/book-appointment/?treatment=carbon-rejuvenation',
                    '_treatment_cta_secondary_text' => 'Learn More',
                    '_treatment_cta_secondary_url' => '/treatments/carbon-rejuvenation/',
                    '_treatment_featured_image' => get_template_directory_uri() . '/assets/images/treatments/carbon-rejuvenation.jpg',
                    '_treatment_featured_image_alt' => 'Medical spa carbon peel laser treatment',
                    '_treatment_schema_type' => 'MedicalProcedure',
                    '_treatment_schema_body_location' => 'Face',
                    '_treatment_schema_procedure_type' => 'Carbon laser procedure'
                ],
                'taxonomies' => [
                    'treatment_category' => ['carbon'],
                    'treatment_feature' => ['revolutionary-technology', 'immediate-results'],
                    'treatment_benefit' => ['deep-cleansing', 'skin-resurfacing']
                ]
            ],
            [
                'id' => 'body-sculpting',
                'post_title' => 'EMSCULPT Muscle Builder',
                'post_content' => '💪 Build & tone muscles without exercise using EMSCULPT muscle building technology. Achieve your ideal silhouette with advanced body contouring.',
                'post_excerpt' => 'EMSCULPT muscle building and non-invasive body contouring',
                'post_status' => 'publish',
                'meta_fields' => [
                    '_treatment_icon' => '💪',
                    '_treatment_category' => 'body',
                    '_treatment_duration' => '30 minutes',
                    '_treatment_benefits' => ['Muscle building', 'Fat reduction', 'No exercise required', 'Advanced technology'],
                    '_treatment_cta_primary_text' => 'Book Now',
                    '_treatment_cta_primary_url' => '/book-appointment/?treatment=body-sculpting',
                    '_treatment_cta_secondary_text' => 'Learn More',
                    '_treatment_cta_secondary_url' => '/treatments/body-sculpting/',
                    '_treatment_featured_image' => get_template_directory_uri() . '/assets/images/treatments/body-sculpting.jpg',
                    '_treatment_featured_image_alt' => 'Medical spa EMSCULPT body sculpting treatment',
                    '_treatment_schema_type' => 'MedicalProcedure',
                    '_treatment_schema_body_location' => 'Body',
                    '_treatment_schema_procedure_type' => 'Body contouring procedure'
                ],
                'taxonomies' => [
                    'treatment_category' => ['body'],
                    'treatment_feature' => ['muscle-building', 'advanced-technology'],
                    'treatment_benefit' => ['fat-reduction', 'no-exercise']
                ]
            ]
        ];
    }

    /**
     * Transform raw data to component format
     */
    private function transform_to_component_format($raw_data): array {
        return [
            'id' => $raw_data['id'],
            'title' => $raw_data['post_title'],
            'icon' => $raw_data['meta_fields']['_treatment_icon'] ?? '',
            'description' => $raw_data['post_content'],
            'benefits' => $raw_data['meta_fields']['_treatment_benefits'] ?? [],
            'duration' => $raw_data['meta_fields']['_treatment_duration'] ?? '',
            'cta' => [
                'primary' => [
                    'text' => $raw_data['meta_fields']['_treatment_cta_primary_text'] ?? 'Book Now',
                    'url' => $raw_data['meta_fields']['_treatment_cta_primary_url'] ?? '#'
                ],
                'secondary' => [
                    'text' => $raw_data['meta_fields']['_treatment_cta_secondary_text'] ?? 'Learn More',
                    'url' => $raw_data['meta_fields']['_treatment_cta_secondary_url'] ?? '#'
                ]
            ],
            'image' => [
                'src' => $raw_data['meta_fields']['_treatment_featured_image'] ?? '',
                'alt' => $raw_data['meta_fields']['_treatment_featured_image_alt'] ?? ''
            ],
            'schema' => [
                '@type' => $raw_data['meta_fields']['_treatment_schema_type'] ?? 'MedicalProcedure',
                'name' => $raw_data['post_title'],
                'description' => $raw_data['post_excerpt'],
                'bodyLocation' => $raw_data['meta_fields']['_treatment_schema_body_location'] ?? '',
                'procedureType' => $raw_data['meta_fields']['_treatment_schema_procedure_type'] ?? ''
            ]
        ];
    }

    public function get_treatments(): array {
        $raw_data = self::get_raw_data();
        $treatments = [];

        foreach ($raw_data as $treatment) {
            if ($treatment['post_status'] === 'publish') {
                $treatments[] = $this->transform_to_component_format($treatment);
            }
        }

        return $treatments;
    }

    public function get_treatment($id): ?array {
        $raw_data = self::get_raw_data();

        foreach ($raw_data as $treatment) {
            if ($treatment['id'] === $id && $treatment['post_status'] === 'publish') {
                return $this->transform_to_component_format($treatment);
            }
        }

        return null;
    }

    public function get_treatments_by_category($category): array {
        $raw_data = self::get_raw_data();
        $treatments = [];

        foreach ($raw_data as $treatment) {
            if ($treatment['post_status'] === 'publish' &&
                $treatment['meta_fields']['_treatment_category'] === $category) {
                $treatments[] = $this->transform_to_component_format($treatment);
            }
        }

        return $treatments;
    }

    public function is_available(): bool {
        return true; // Hardcoded data is always available
    }
}

/**
 * WordPress CMS data source implementation (for future use)
 */
class CMSTreatmentDataSource implements TreatmentDataSourceInterface {

    public function get_treatments(): array {
        $posts = get_posts([
            'post_type' => 'treatment',
            'post_status' => 'publish',
            'numberposts' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC'
        ]);

        $treatments = [];
        foreach ($posts as $post) {
            $treatments[] = $this->transform_post_to_component_format($post);
        }

        return $treatments;
    }

    public function get_treatment($id): ?array {
        $post = get_page_by_path($id, OBJECT, 'treatment');

        if (!$post || $post->post_status !== 'publish') {
            return null;
        }

        return $this->transform_post_to_component_format($post);
    }

    public function get_treatments_by_category($category): array {
        $posts = get_posts([
            'post_type' => 'treatment',
            'post_status' => 'publish',
            'numberposts' => -1,
            'tax_query' => [
                [
                    'taxonomy' => 'treatment_category',
                    'field' => 'slug',
                    'terms' => $category
                ]
            ]
        ]);

        $treatments = [];
        foreach ($posts as $post) {
            $treatments[] = $this->transform_post_to_component_format($post);
        }

        return $treatments;
    }

    public function is_available(): bool {
        return post_type_exists('treatment');
    }

    private function transform_post_to_component_format($post): array {
        return [
            'id' => $post->post_name,
            'title' => $post->post_title,
            'icon' => get_post_meta($post->ID, '_treatment_icon', true) ?: '',
            'description' => $post->post_content,
            'benefits' => get_post_meta($post->ID, '_treatment_benefits', true) ?: [],
            'duration' => get_post_meta($post->ID, '_treatment_duration', true) ?: '',
            'cta' => [
                'primary' => [
                    'text' => get_post_meta($post->ID, '_treatment_cta_primary_text', true) ?: 'Book Now',
                    'url' => get_post_meta($post->ID, '_treatment_cta_primary_url', true) ?: '#'
                ],
                'secondary' => [
                    'text' => get_post_meta($post->ID, '_treatment_cta_secondary_text', true) ?: 'Learn More',
                    'url' => get_post_meta($post->ID, '_treatment_cta_secondary_url', true) ?: '#'
                ]
            ],
            'image' => [
                'src' => get_the_post_thumbnail_url($post->ID, 'large') ?: '',
                'alt' => get_post_meta(get_post_thumbnail_id($post->ID), '_wp_attachment_image_alt', true) ?: ''
            ],
            'schema' => [
                '@type' => get_post_meta($post->ID, '_treatment_schema_type', true) ?: 'MedicalProcedure',
                'name' => $post->post_title,
                'description' => $post->post_excerpt,
                'bodyLocation' => get_post_meta($post->ID, '_treatment_schema_body_location', true) ?: '',
                'procedureType' => get_post_meta($post->ID, '_treatment_schema_procedure_type', true) ?: ''
            ]
        ];
    }
}

/**
 * Main adapter class that manages data sources
 */
class TreatmentsAdapter {
    private $data_source;

    public function __construct() {
        $this->initialize_data_source();
    }

    /**
     * Initialize the appropriate data source
     */
    private function initialize_data_source() {
        // Priority order: CMS -> Hardcoded
        $cms_source = new CMSTreatmentDataSource();

        if ($cms_source->is_available() && $this->has_cms_data()) {
            $this->data_source = $cms_source;
        } else {
            $this->data_source = new HardcodedTreatmentDataSource();
        }
    }

    /**
     * Check if CMS has treatment data
     */
    private function has_cms_data(): bool {
        $posts = get_posts([
            'post_type' => 'treatment',
            'post_status' => 'publish',
            'numberposts' => 1
        ]);

        return !empty($posts);
    }

    /**
     * Get all treatments
     */
    public function get_treatments(): array {
        return $this->data_source->get_treatments();
    }

    /**
     * Get single treatment
     */
    public function get_treatment($id): ?array {
        return $this->data_source->get_treatment($id);
    }

    /**
     * Get treatments by category
     */
    public function get_treatments_by_category($category): array {
        return $this->data_source->get_treatments_by_category($category);
    }

    /**
     * Get current data source type
     */
    public function get_data_source_type(): string {
        return get_class($this->data_source);
    }

    /**
     * Force refresh data source
     */
    public function refresh_data_source() {
        $this->initialize_data_source();
    }
}
