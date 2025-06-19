<?php
/**
 * Service Section Component
 *
 * Implements grouped service sections for homepage with semantic tokenization
 *
 * @package MedSpaTheme
 * @since 1.0.0
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 * @task T11.2 Services Overview - Grouped Sections Implementation
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/components/base-component.php';

/**
 * Service Section Component Class
 *
 * Creates grouped service sections with semantic design tokens,
 * WCAG AAA accessibility, and mobile-first responsive design.
 */
class ServiceSectionComponent extends BaseComponent {

    /**
     * Component name identifier
     * @var string
     */
    protected $component_name = 'service-section';

    /**
     * Component version
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Service sections data structure
     * @var array
     */
    private $service_sections = [];

    /**
     * Constructor
     *
     * @param array $args Component arguments
     */
    public function __construct($args = []) {
        parent::__construct($args);
        $this->init_service_sections();
    }

    /**
     * Initialize service sections data
     * Following HOMEPAGE_DESIGN.md alternating layout pattern
     */
    private function init_service_sections() {
        $this->service_sections = [
            'injectable_artistry' => [
                'id' => 'injectable-artistry',
                'title' => get_theme_mod('service_injectable_title', 'Injectable Artistry'),
                'subtitle' => get_theme_mod('service_injectable_subtitle', 'Precision Enhancement & Natural Beauty'),
                'description' => get_theme_mod('service_injectable_description', 'Expert injectable treatments designed to enhance your natural beauty with precision and artistry. Our board-certified practitioners deliver subtle, natural-looking results.'),
                'icon' => '💉',
                'layout' => 'text-left', // Text Left / Visual Right
                'visual_type' => 'before_after_gallery',
                'treatments' => [
                    [
                        'name' => 'Botox',
                        'slug' => 'botox',
                        'description' => 'Smooth wrinkles and fine lines'
                    ],
                    [
                        'name' => 'Dermal Fillers',
                        'slug' => 'dermal-fillers',
                        'description' => 'Restore volume and contour'
                    ],
                    [
                        'name' => 'Lip Enhancement',
                        'slug' => 'lip-enhancement',
                        'description' => 'Natural lip volume and definition'
                    ],
                    [
                        'name' => 'Facial Contouring',
                        'slug' => 'facial-contouring',
                        'description' => 'Sculpt and define facial features'
                    ]
                ],
                'gallery_images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=600&h=400&fit=crop&crop=face',
                        'alt' => 'Before Botox treatment - natural expression lines',
                        'type' => 'before'
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1594824369039-a8c2e8d3c8c4?w=600&h=400&fit=crop&crop=face',
                        'alt' => 'After Botox treatment - smooth, natural results',
                        'type' => 'after'
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=600&h=400&fit=crop&crop=face',
                        'alt' => 'Before dermal filler treatment - loss of volume',
                        'type' => 'before'
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=600&h=400&fit=crop&crop=face',
                        'alt' => 'After dermal filler treatment - restored youthful contours',
                        'type' => 'after'
                    ]
                ]
            ],
            'laser_services' => [
                'id' => 'laser-services',
                'title' => get_theme_mod('service_laser_title', 'Laser Precision'),
                'subtitle' => get_theme_mod('service_laser_subtitle', 'Advanced Technology & Expert Care'),
                'description' => get_theme_mod('service_laser_description', 'State-of-the-art laser treatments for hair removal, skin resurfacing, and pigmentation correction. Safe, effective, and delivered with precision by our certified specialists.'),
                'icon' => '🔥',
                'layout' => 'visual-left', // Visual Left / Text Right
                'visual_type' => 'treatment_video',
                'treatments' => [
                    [
                        'name' => 'Laser Hair Removal',
                        'slug' => 'laser-hair-removal',
                        'description' => 'Permanent hair reduction'
                    ],
                    [
                        'name' => 'Skin Resurfacing',
                        'slug' => 'skin-resurfacing',
                        'description' => 'Improve texture and tone'
                    ],
                    [
                        'name' => 'Pigmentation Treatment',
                        'slug' => 'pigmentation-treatment',
                        'description' => 'Even skin tone and clarity'
                    ],
                    [
                        'name' => 'Laser Genesis',
                        'slug' => 'laser-genesis',
                        'description' => 'Collagen stimulation therapy'
                    ]
                ],
                'video_data' => [
                    'poster' => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=800&h=600&fit=crop',
                    'poster_alt' => 'Advanced laser treatment technology in modern medical spa',
                    'video_url' => '#', // Placeholder for video URL
                    'video_description' => 'Watch our advanced laser treatment process'
                ]
            ],
            'facial_renaissance' => [
                'id' => 'facial-renaissance',
                'title' => get_theme_mod('service_facial_title', 'Facial Renaissance'),
                'subtitle' => get_theme_mod('service_facial_subtitle', 'Rejuvenation & Renewal'),
                'description' => get_theme_mod('service_facial_description', 'Comprehensive facial treatments combining advanced techniques with luxurious care. From HydraFacials to chemical peels, discover your skin\'s potential.'),
                'icon' => '🌊',
                'layout' => 'text-left', // Text Left / Visual Right
                'visual_type' => 'treatment_results_gallery',
                'treatments' => [
                    [
                        'name' => 'HydraFacial',
                        'slug' => 'hydrafacial',
                        'description' => 'Deep cleansing and hydration'
                    ],
                    [
                        'name' => 'Chemical Peels',
                        'slug' => 'chemical-peels',
                        'description' => 'Skin renewal and rejuvenation'
                    ],
                    [
                        'name' => 'Microneedling',
                        'slug' => 'microneedling',
                        'description' => 'Collagen induction therapy'
                    ],
                    [
                        'name' => 'LED Therapy',
                        'slug' => 'led-therapy',
                        'description' => 'Light-based skin healing'
                    ]
                ],
                'gallery_images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1560750588-73207b1ef5b8?w=600&h=400&fit=crop',
                        'alt' => 'HydraFacial treatment results - glowing, hydrated skin',
                        'treatment' => 'HydraFacial'
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1515377905703-c4788e51af15?w=600&h=400&fit=crop',
                        'alt' => 'Chemical peel results - smooth, renewed skin texture',
                        'treatment' => 'Chemical Peels'
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&h=400&fit=crop',
                        'alt' => 'Microneedling results - improved skin firmness and texture',
                        'treatment' => 'Microneedling'
                    ]
                ]
            ],
            'body_sculpting' => [
                'id' => 'body-sculpting',
                'title' => get_theme_mod('service_body_title', 'Body Sculpting'),
                'subtitle' => get_theme_mod('service_body_subtitle', 'Transformation & Confidence'),
                'description' => get_theme_mod('service_body_description', 'Non-invasive body contouring treatments designed to sculpt and refine your silhouette. Achieve your body goals with our advanced technology and expert care.'),
                'icon' => '💪',
                'layout' => 'visual-left', // Visual Left / Text Right
                'visual_type' => 'transformation_gallery',
                'treatments' => [
                    [
                        'name' => 'CoolSculpting',
                        'slug' => 'coolsculpting',
                        'description' => 'Non-invasive fat reduction'
                    ],
                    [
                        'name' => 'Body Contouring',
                        'slug' => 'body-contouring',
                        'description' => 'Sculpt and define body shape'
                    ],
                    [
                        'name' => 'Cellulite Treatment',
                        'slug' => 'cellulite-treatment',
                        'description' => 'Smooth skin texture improvement'
                    ],
                    [
                        'name' => 'Skin Tightening',
                        'slug' => 'skin-tightening',
                        'description' => 'Firm and tone loose skin'
                    ]
                ],
                'gallery_images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&h=400&fit=crop',
                        'alt' => 'Before CoolSculpting treatment - targeted area assessment',
                        'type' => 'before',
                        'treatment' => 'CoolSculpting'
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=600&h=400&fit=crop',
                        'alt' => 'After CoolSculpting treatment - contoured silhouette results',
                        'type' => 'after',
                        'treatment' => 'CoolSculpting'
                    ]
                ]
            ],
            'wellness_sanctuary' => [
                'id' => 'wellness-sanctuary',
                'title' => get_theme_mod('service_wellness_title', 'Wellness Sanctuary'),
                'subtitle' => get_theme_mod('service_wellness_subtitle', 'Holistic Health & Vitality'),
                'description' => get_theme_mod('service_wellness_description', 'Comprehensive wellness treatments focusing on your overall health and vitality. From IV therapy to hormone optimization, nurture your body from within.'),
                'icon' => '💊',
                'layout' => 'text-left', // Text Left / Visual Right
                'visual_type' => 'experience_gallery',
                'treatments' => [
                    [
                        'name' => 'IV Therapy',
                        'slug' => 'iv-therapy',
                        'description' => 'Nutrient infusion therapy'
                    ],
                    [
                        'name' => 'Hormone Optimization',
                        'slug' => 'hormone-optimization',
                        'description' => 'Balance and vitality restoration'
                    ],
                    [
                        'name' => 'Weight Management',
                        'slug' => 'weight-management',
                        'description' => 'Comprehensive wellness approach'
                    ],
                    [
                        'name' => 'Vitamin Injections',
                        'slug' => 'vitamin-injections',
                        'description' => 'Targeted nutrient delivery'
                    ]
                ],
                'gallery_images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1559757175-0eb30cd8c063?w=600&h=400&fit=crop',
                        'alt' => 'Luxury IV therapy room - comfortable wellness environment',
                        'space' => 'IV Therapy Suite'
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&h=400&fit=crop',
                        'alt' => 'Private consultation room - personalized wellness planning',
                        'space' => 'Consultation Room'
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=600&h=400&fit=crop',
                        'alt' => 'Wellness relaxation area - peaceful recovery space',
                        'space' => 'Recovery Lounge'
                    ]
                ]
            ]
        ];
    }

    /**
     * Render the service sections
     *
     * @param array $args Component arguments
     * @return string HTML output
     */
    public function render($args = []) {
        $start_time = microtime(true);

        // Merge arguments with defaults
        $args = wp_parse_args($args, $this->get_defaults());

        // Get sections to display
        $sections_to_show = $args['sections'] ?? array_keys($this->service_sections);

        // Build HTML output
        $html = $this->render_services_overview_section($sections_to_show, $args);

        // Performance validation (<100ms requirement)
        $render_time = microtime(true) - $start_time;
        if ($render_time > 0.1) {
            error_log("ServiceSectionComponent: Render time {$render_time}s exceeds 100ms requirement");
        }

        return $html;
    }

    /**
     * Render the complete services overview section
     *
     * @param array $sections_to_show Sections to display
     * @param array $args Component arguments
     * @return string HTML output
     */
    protected function render_services_overview_section($sections_to_show, $args) {
        $section_header = $this->render_section_header($args);
        $service_sections = $this->render_service_sections($sections_to_show, $args);

        $accessibility_attrs = $this->get_accessibility_attributes([
            'aria-labelledby' => 'services-overview-title',
            'role' => 'region'
        ]);

        return sprintf(
            '<section class="services-overview" %s>
                <div class="container">
                    %s
                    <div class="services-grid">
                        %s
                    </div>
                </div>
            </section>',
            $accessibility_attrs,
            $section_header,
            $service_sections
        );
    }

    /**
     * Render section header
     *
     * @param array $args Component arguments
     * @return string HTML output
     */
    protected function render_section_header($args) {
        $title = $args['section_title'] ?? 'Our Treatment Artistry';
        $subtitle = $args['section_subtitle'] ?? 'Discover Personalized Medical Aesthetics';
        $description = $args['section_description'] ?? 'Each treatment is carefully curated to enhance your natural beauty with precision, artistry, and innovation.';

        return sprintf(
            '<div class="section__header text-center">
                <h2 id="services-overview-title" class="section__title">%s</h2>
                <p class="section__subtitle">%s</p>
                <p class="section__description">%s</p>
            </div>',
            esc_html($title),
            esc_html($subtitle),
            esc_html($description)
        );
    }

    /**
     * Render all service sections
     *
     * @param array $sections_to_show Sections to display
     * @param array $args Component arguments
     * @return string HTML output
     */
    protected function render_service_sections($sections_to_show, $args) {
        $html = '';

        foreach ($sections_to_show as $section_key) {
            if (!isset($this->service_sections[$section_key])) {
                continue;
            }

            $section_data = $this->service_sections[$section_key];
            $html .= $this->render_single_service_section($section_key, $section_data, $args);
        }

        return $html;
    }

    /**
     * Render a single service section
     *
     * @param string $section_key Section identifier
     * @param array $section_data Section configuration
     * @param array $args Component arguments
     * @return string HTML output
     */
    protected function render_single_service_section($section_key, $section_data, $args) {
        $treatments_html = $this->render_treatment_list($section_data['treatments']);
        $cta_html = $this->render_section_cta($section_data);

        $accessibility_attrs = $this->get_accessibility_attributes([
            'aria-labelledby' => "service-{$section_key}-title",
            'role' => 'article'
        ]);

        return sprintf(
            '<div class="service-section service-section--%s" %s>
                <div class="service-section__icon" aria-hidden="true">%s</div>
                <div class="service-section__content">
                    <h3 id="service-%s-title" class="service-section__title">%s</h3>
                    <p class="service-section__description">%s</p>
                    <div class="service-section__treatments">
                        %s
                    </div>
                    %s
                </div>
            </div>',
            esc_attr($section_key),
            $accessibility_attrs,
            $section_data['icon'],
            esc_attr($section_key),
            esc_html($section_data['title']),
            esc_html($section_data['description']),
            $treatments_html,
            $cta_html
        );
    }

    /**
     * Render treatment list for a section
     *
     * @param array $treatments Treatment list with icons
     * @return string HTML output
     */
    protected function render_treatment_list($treatments) {
        $html = '<ul class="treatment-list" role="list">';

        foreach ($treatments as $treatment_name => $treatment_icon) {
            $html .= sprintf(
                '<li class="treatment-item" role="listitem">
                    <span class="treatment-icon" aria-hidden="true">%s</span>
                    <span class="treatment-name">%s</span>
                </li>',
                $treatment_icon,
                esc_html($treatment_name)
            );
        }

        $html .= '</ul>';
        return $html;
    }

    /**
     * Render section call-to-action
     *
     * @param array $section_data Section configuration
     * @return string HTML output
     */
    protected function render_section_cta($section_data) {
        $accessibility_attrs = $this->get_accessibility_attributes([
            'aria-describedby' => 'service-' . sanitize_title($section_data['title']) . '-description'
        ]);

        return sprintf(
            '<div class="service-section__cta">
                <a href="%s" class="button button--outline service-cta-button" %s>
                    %s
                </a>
            </div>',
            esc_url($section_data['cta_url']),
            $accessibility_attrs,
            esc_html($section_data['cta_text'])
        );
    }

    /**
     * Render visual content for service category - T11.7/T11.9 Integration
     *
     * @param string $category_key Service category key
     * @return string HTML output
     */
    protected function render_visual_content($category_key) {
        // T11.7 Integration: Visual Content with Unsplash placeholders
        // T11.9 Implementation: Performance optimization for visual content

        // Initialize Visual Content Component if available
        if (class_exists('VisualContentComponent')) {
            $visual_content_component = new VisualContentComponent();
            $rendered_content = $visual_content_component->render_collection($category_key);

            if (!empty($rendered_content)) {
                return sprintf(
                    '<div class="service-visual-content integrated-visual-content" data-content-type="%s">
                        %s
                    </div>',
                    esc_attr($category_key),
                    $rendered_content
                );
            }
        }

        // Fallback visual content with performance optimization
        $visual_content_map = [
            'injectable_artistry' => [
                'type' => 'before_after_gallery',
                'images' => [
                    [
                        'before' => 'photo-1570172619644-dfd03ed5d881',
                        'after' => 'photo-1594824369039-a8c2e8d3c8c4',
                        'alt_before' => 'Before Botox treatment - natural expression lines visible',
                        'alt_after' => 'After Botox treatment - smooth, natural-looking results',
                        'treatment' => 'Botox'
                    ]
                ]
            ],
            'laser_precision' => [
                'type' => 'treatment_video',
                'poster' => 'photo-1559757148-5c350d0d3c56',
                'alt' => 'Advanced laser treatment technology in modern medical spa setting',
                'video_url' => get_theme_mod('visual_laser_video_url', '#')
            ],
            'facial_renaissance' => [
                'type' => 'treatment_results',
                'images' => [
                    [
                        'id' => 'photo-1560750588-73207b1ef5b8',
                        'alt' => 'HydraFacial treatment results - glowing, hydrated skin',
                        'treatment' => 'HydraFacial'
                    ],
                    [
                        'id' => 'photo-1515377905703-c4788e51af15',
                        'alt' => 'Chemical peel results - smooth, renewed skin texture',
                        'treatment' => 'Chemical Peels'
                    ]
                ]
            ],
            'body_sculpting' => [
                'type' => 'transformation_gallery',
                'transformations' => [
                    [
                        'before' => 'photo-1571019613454-1cb2f99b2d8b',
                        'after' => 'photo-1544367567-0f2fcb009e0b',
                        'alt_before' => 'Before CoolSculpting treatment - targeted area assessment',
                        'alt_after' => 'After CoolSculpting treatment - contoured silhouette results',
                        'treatment' => 'CoolSculpting'
                    ]
                ]
            ],
            'wellness_sanctuary' => [
                'type' => 'experience_gallery',
                'spaces' => [
                    [
                        'id' => 'photo-1559757175-0eb30cd8c063',
                        'alt' => 'Luxury IV therapy room - comfortable wellness environment',
                        'name' => 'IV Therapy Suite'
                    ],
                    [
                        'id' => 'photo-1571019613454-1cb2f99b2d8b',
                        'alt' => 'Private consultation room - personalized wellness planning',
                        'name' => 'Consultation Room'
                    ]
                ]
            ]
        ];

        $content_info = isset($visual_content_map[$category_key]) ? $visual_content_map[$category_key] : null;

        if (!$content_info) {
            return sprintf(
                '<div class="service-visual-content placeholder-content">
                    <div class="visual-placeholder">
                        <p>%s</p>
                    </div>
                </div>',
                esc_html__('Visual content coming soon', 'medspatheme')
            );
        }

        // Render based on content type with performance optimization
        switch ($content_info['type']) {
            case 'before_after_gallery':
                return $this->render_before_after_fallback($content_info, $category_key);
            case 'treatment_video':
                return $this->render_video_fallback($content_info, $category_key);
            case 'treatment_results':
                return $this->render_results_fallback($content_info, $category_key);
            case 'transformation_gallery':
                return $this->render_transformation_fallback($content_info, $category_key);
            case 'experience_gallery':
                return $this->render_experience_fallback($content_info, $category_key);
            default:
                return $this->render_placeholder_content($category_key);
        }
    }

    /**
     * Render before/after gallery fallback with performance optimization
     */
    private function render_before_after_fallback($content_info, $category_key) {
        $output = sprintf(
            '<div class="service-visual-content before-after-gallery-fallback" data-content-type="%s">',
            esc_attr($category_key)
        );

        $output .= '<div class="before-after-grid">';

        foreach ($content_info['images'] as $index => $image_pair) {
            $before_url = $this->generate_optimized_unsplash_url($image_pair['before'], ['w' => 400, 'h' => 300]);
            $after_url = $this->generate_optimized_unsplash_url($image_pair['after'], ['w' => 400, 'h' => 300]);

            // T11.9: Critical image loading for first image
            $loading_attr = $index === 0 ? 'eager' : 'lazy';
            $critical_class = $index === 0 ? ' critical-image' : '';

            $output .= '<div class="before-after-comparison">';
            $output .= sprintf(
                '<div class="before-image-container">
                    <img src="%s" alt="%s" class="before-image%s" loading="%s" />
                    <div class="image-overlay">
                        <span class="image-label">%s</span>
                    </div>
                </div>',
                esc_url($before_url),
                esc_attr($image_pair['alt_before']),
                $critical_class,
                $loading_attr,
                esc_html__('Before', 'medspatheme')
            );

            $output .= sprintf(
                '<div class="after-image-container">
                    <img src="%s" alt="%s" class="after-image%s" loading="%s" />
                    <div class="image-overlay">
                        <span class="image-label">%s</span>
                    </div>
                </div>',
                esc_url($after_url),
                esc_attr($image_pair['alt_after']),
                $critical_class,
                $loading_attr,
                esc_html__('After', 'medspatheme')
            );
            $output .= '</div>';
        }

        $output .= '</div></div>';
        return $output;
    }

    /**
     * Generate optimized Unsplash URL with T11.9 performance parameters
     */
    private function generate_optimized_unsplash_url($unsplash_id, $params = []) {
        $base_url = 'https://images.unsplash.com';

        // T11.9: Default optimization parameters
        $default_params = [
            'auto' => 'format,compress',
            'fit' => 'crop',
            'crop' => 'center',
            'q' => get_theme_mod('visual_content_quality', 85)
        ];

        // Merge with provided parameters
        $params = array_merge($default_params, $params);

        // T11.9: WebP support detection
        if (get_theme_mod('visual_content_webp_support', true) && $this->supports_webp()) {
            $params['fm'] = 'webp';
        }

        $query_string = http_build_query($params);
        return "{$base_url}/{$unsplash_id}?{$query_string}";
    }

    /**
     * Check WebP support for T11.9 optimization
     */
    private function supports_webp() {
        // Simple WebP support detection
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $accept = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';

        return (
            strpos($accept, 'image/webp') !== false ||
            strpos($user_agent, 'Chrome') !== false ||
            strpos($user_agent, 'Firefox') !== false ||
            strpos($user_agent, 'Edge') !== false
        );
    }

    /**
     * Get WordPress Customizer controls
     *
     * @return array Customizer controls configuration
     */
    public function get_customizer_controls() {
        return [
            'section_title' => [
                'type' => 'text',
                'label' => 'Section Title',
                'default' => 'Our Treatment Artistry',
                'sanitize_callback' => 'sanitize_text_field'
            ],
            'section_subtitle' => [
                'type' => 'text',
                'label' => 'Section Subtitle',
                'default' => 'Discover Personalized Medical Aesthetics',
                'sanitize_callback' => 'sanitize_text_field'
            ],
            'section_description' => [
                'type' => 'textarea',
                'label' => 'Section Description',
                'default' => 'Each treatment is carefully curated to enhance your natural beauty with precision, artistry, and innovation.',
                'sanitize_callback' => 'sanitize_textarea_field'
            ],
            'show_injectable_artistry' => [
                'type' => 'checkbox',
                'label' => 'Show Injectable Artistry Section',
                'default' => true,
                'sanitize_callback' => 'wp_validate_boolean'
            ],
            'show_facial_renaissance' => [
                'type' => 'checkbox',
                'label' => 'Show Facial Renaissance Section',
                'default' => true,
                'sanitize_callback' => 'wp_validate_boolean'
            ],
            'show_laser_precision' => [
                'type' => 'checkbox',
                'label' => 'Show Laser Precision Section',
                'default' => true,
                'sanitize_callback' => 'wp_validate_boolean'
            ],
            'show_body_sculpting' => [
                'type' => 'checkbox',
                'label' => 'Show Body Sculpting Section',
                'default' => true,
                'sanitize_callback' => 'wp_validate_boolean'
            ],
            'show_wellness_sanctuary' => [
                'type' => 'checkbox',
                'label' => 'Show Wellness Sanctuary Section',
                'default' => true,
                'sanitize_callback' => 'wp_validate_boolean'
            ]
        ];
    }

    /**
     * Get default design tokens for this component
     *
     * @return array Design tokens
     */
    public function get_default_tokens() {
        return [
            // Service Section Container
            'service-section-bg' => 'var(--color-surface)',
            'service-section-border' => 'var(--color-surface-tertiary)',
            'service-section-hover-bg' => 'var(--color-surface-hover)',
            'service-section-padding' => 'var(--space-xl)',
            'service-section-border-radius' => 'var(--radius-lg)',
            'service-section-shadow' => 'var(--shadow-sm)',
            'service-section-hover-shadow' => 'var(--shadow-md)',

            // Typography
            'service-title-color' => 'var(--color-text-primary)',
            'service-title-font-size' => 'var(--text-lg)',
            'service-title-font-weight' => 'var(--font-weight-semibold)',
            'service-description-color' => 'var(--color-text-secondary)',
            'service-description-font-size' => 'var(--text-base)',

            // Icon Styling
            'service-icon-size' => 'var(--text-xl)',
            'service-icon-margin' => 'var(--space-md)',

            // Treatment List
            'treatment-item-padding' => 'var(--space-sm)',
            'treatment-icon-margin' => 'var(--space-xs)',
            'treatment-text-color' => 'var(--color-text-secondary)',

            // CTA Button
            'service-cta-margin-top' => 'var(--space-lg)',
            'service-cta-padding' => 'var(--space-md) var(--space-lg)',

            // Grid Layout
            'services-grid-gap' => 'var(--space-xl)',
            'services-grid-columns-mobile' => '1',
            'services-grid-columns-tablet' => '1',
            'services-grid-columns-desktop' => '2',

            // Transitions
            'service-transition' => 'var(--transition-base)',
            'service-hover-transform' => 'translateY(-2px)'
        ];
    }

    /**
     * Get component default configuration
     *
     * @return array Default configuration
     */
    public function get_defaults() {
        return [
            'section_title' => 'Our Treatment Artistry',
            'section_subtitle' => 'Discover Personalized Medical Aesthetics',
            'section_description' => 'Each treatment is carefully curated to enhance your natural beauty with precision, artistry, and innovation.',
            'sections' => array_keys($this->service_sections),
            'show_section_header' => true,
            'enable_hover_effects' => true,
            'enable_animations' => true,
            'schema_markup' => true
        ];
    }

    /**
     * Setup component-specific hooks
     */
    protected function setup_hooks() {
        parent::setup_hooks();

        // Add schema markup for medical procedures
        add_action('wp_head', [$this, 'add_service_schema_markup']);

        // Enqueue component-specific styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_service_section_styles']);
    }

    /**
     * Add structured data for medical services
     */
    public function add_service_schema_markup() {
        if (!is_front_page()) {
            return;
        }

        $schema_data = [
            '@context' => 'https://schema.org',
            '@type' => 'MedicalBusiness',
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'medicalSpecialty' => 'Cosmetic Surgery',
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => 'Medical Spa Services',
                'itemListElement' => []
            ]
        ];

        foreach ($this->service_sections as $section_key => $section_data) {
            $schema_data['hasOfferCatalog']['itemListElement'][] = [
                '@type' => 'Offer',
                'name' => $section_data['title'],
                'description' => $section_data['description'],
                'category' => 'Medical Aesthetics',
                'url' => home_url($section_data['cta_url'])
            ];
        }

        echo '<script type="application/ld+json">' . wp_json_encode($schema_data) . '</script>';
    }

    /**
     * Enqueue component-specific styles
     */
    public function enqueue_service_section_styles() {
        // Styles are included in semantic-components.css
        // This method is available for future component-specific styling needs
    }
}

// Register the component
if (class_exists('ComponentRegistry')) {
    ComponentRegistry::register('service-section', 'ServiceSectionComponent', [
        'priority' => 10,
        'cacheable' => true,
        'lazy_load' => false,
        'accessibility_required' => true
    ]);
}
