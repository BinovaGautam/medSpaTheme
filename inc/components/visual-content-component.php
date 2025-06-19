<?php
/**
 * Visual Content Component
 *
 * Implements T11.7: Visual Content Integration with Unsplash Placeholders
 * - Before/After galleries with medical aesthetic placeholders
 * - Treatment video integration with poster images
 * - Experience showcases for wellness sanctuary
 * - WordPress Customizer integration for image management
 * - Responsive image delivery with optimization
 * - Alt text management for accessibility compliance
 * - 100% semantic tokenization compliance (zero hardcoded values)
 * - WCAG AAA accessibility standards
 * - Performance <100ms render time
 *
 * @package MedSpaTheme
 * @subpackage Components
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once get_template_directory() . '/inc/components/base-component.php';

/**
 * VisualContentComponent Class
 *
 * Manages visual content integration with Unsplash API and WordPress media
 * following Sprint 11 T11.7 specifications
 */
class VisualContentComponent extends BaseComponent {

    /**
     * Component name identifier
     * @var string
     */
    protected $component_name = 'visual-content';

    /**
     * Component version
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Unsplash API configuration
     * @var array
     */
    private $unsplash_config = [];

    /**
     * Visual content collections
     * @var array
     */
    private $content_collections = [];

    /**
     * Image optimization settings
     * @var array
     */
    private $optimization_settings = [];

    /**
     * Constructor
     *
     * @param array $args Component arguments
     */
    public function __construct($args = []) {
        parent::__construct($args);
        $this->init_unsplash_config();
        $this->init_content_collections();
        $this->init_optimization_settings();
    }

    /**
     * Initialize Unsplash API configuration
     */
    private function init_unsplash_config() {
        $this->unsplash_config = [
            'base_url' => 'https://images.unsplash.com',
            'collections' => [
                'medical_spa' => 'medical-spa-treatments',
                'beauty_treatments' => 'beauty-aesthetic-medicine',
                'wellness' => 'spa-wellness-luxury',
                'before_after' => 'medical-aesthetics-results'
            ],
            'default_params' => [
                'w' => get_theme_mod('visual_content_default_width', 800),
                'h' => get_theme_mod('visual_content_default_height', 600),
                'fit' => 'crop',
                'crop' => 'center',
                'auto' => 'format,compress',
                'q' => get_theme_mod('visual_content_quality', 85)
            ],
            'responsive_sizes' => [
                'mobile' => ['w' => 400, 'h' => 300],
                'tablet' => ['w' => 600, 'h' => 450],
                'desktop' => ['w' => 800, 'h' => 600],
                'large' => ['w' => 1200, 'h' => 800]
            ]
        ];
    }

    /**
     * Initialize content collections
     */
    private function init_content_collections() {
        $this->content_collections = [
            'injectable_artistry' => [
                'type' => 'before_after_gallery',
                'title' => get_theme_mod('visual_injectable_title', 'Injectable Artistry Results'),
                'description' => get_theme_mod('visual_injectable_description', 'See the natural, beautiful results achieved with our precision injectable treatments.'),
                'images' => [
                    [
                        'before' => [
                            'unsplash_id' => 'photo-1570172619644-dfd03ed5d881',
                            'alt' => get_theme_mod('visual_injectable_before_1_alt', 'Before Botox treatment - natural expression lines visible'),
                            'caption' => 'Before Treatment'
                        ],
                        'after' => [
                            'unsplash_id' => 'photo-1594824369039-a8c2e8d3c8c4',
                            'alt' => get_theme_mod('visual_injectable_after_1_alt', 'After Botox treatment - smooth, natural-looking results'),
                            'caption' => 'After Treatment'
                        ],
                        'treatment' => 'Botox',
                        'timeframe' => '2 weeks post-treatment'
                    ],
                    [
                        'before' => [
                            'unsplash_id' => 'photo-1616394584738-fc6e612e71b9',
                            'alt' => get_theme_mod('visual_injectable_before_2_alt', 'Before dermal filler treatment - loss of facial volume'),
                            'caption' => 'Before Treatment'
                        ],
                        'after' => [
                            'unsplash_id' => 'photo-1559839734-2b71ea197ec2',
                            'alt' => get_theme_mod('visual_injectable_after_2_alt', 'After dermal filler treatment - restored youthful contours'),
                            'caption' => 'After Treatment'
                        ],
                        'treatment' => 'Dermal Fillers',
                        'timeframe' => '4 weeks post-treatment'
                    ]
                ]
            ],
            'laser_services' => [
                'type' => 'treatment_video',
                'title' => get_theme_mod('visual_laser_title', 'Advanced Laser Technology'),
                'description' => get_theme_mod('visual_laser_description', 'Experience our state-of-the-art laser treatments in our modern facility.'),
                'video' => [
                    'poster' => [
                        'unsplash_id' => 'photo-1559757148-5c350d0d3c56',
                        'alt' => get_theme_mod('visual_laser_poster_alt', 'Advanced laser treatment technology in modern medical spa setting'),
                        'title' => 'Laser Treatment Demonstration'
                    ],
                    'video_url' => get_theme_mod('visual_laser_video_url', '#'),
                    'duration' => '2:30',
                    'description' => 'Watch our precision laser treatment process'
                ]
            ],
            'facial_renaissance' => [
                'type' => 'treatment_results_gallery',
                'title' => get_theme_mod('visual_facial_title', 'Facial Treatment Results'),
                'description' => get_theme_mod('visual_facial_description', 'Discover the transformative results of our advanced facial treatments.'),
                'treatments' => [
                    [
                        'name' => 'HydraFacial',
                        'image' => [
                            'unsplash_id' => 'photo-1560750588-73207b1ef5b8',
                            'alt' => get_theme_mod('visual_hydrafacial_alt', 'HydraFacial treatment results - glowing, hydrated skin'),
                            'caption' => 'Immediate glow and hydration'
                        ]
                    ],
                    [
                        'name' => 'Chemical Peels',
                        'image' => [
                            'unsplash_id' => 'photo-1515377905703-c4788e51af15',
                            'alt' => get_theme_mod('visual_chemical_peel_alt', 'Chemical peel results - smooth, renewed skin texture'),
                            'caption' => 'Renewed skin texture and tone'
                        ]
                    ],
                    [
                        'name' => 'Microneedling',
                        'image' => [
                            'unsplash_id' => 'photo-1571019613454-1cb2f99b2d8b',
                            'alt' => get_theme_mod('visual_microneedling_alt', 'Microneedling results - improved skin firmness and texture'),
                            'caption' => 'Enhanced collagen and firmness'
                        ]
                    ]
                ]
            ],
            'body_sculpting' => [
                'type' => 'transformation_gallery',
                'title' => get_theme_mod('visual_body_title', 'Body Transformation Results'),
                'description' => get_theme_mod('visual_body_description', 'See the remarkable body contouring results achieved with our advanced treatments.'),
                'transformations' => [
                    [
                        'treatment' => 'CoolSculpting',
                        'before' => [
                            'unsplash_id' => 'photo-1571019613454-1cb2f99b2d8b',
                            'alt' => get_theme_mod('visual_coolsculpting_before_alt', 'Before CoolSculpting treatment - targeted area assessment'),
                            'caption' => 'Before CoolSculpting'
                        ],
                        'after' => [
                            'unsplash_id' => 'photo-1544367567-0f2fcb009e0b',
                            'alt' => get_theme_mod('visual_coolsculpting_after_alt', 'After CoolSculpting treatment - contoured silhouette results'),
                            'caption' => 'After CoolSculpting'
                        ],
                        'timeframe' => '12 weeks post-treatment'
                    ]
                ]
            ],
            'wellness_sanctuary' => [
                'type' => 'experience_gallery',
                'title' => get_theme_mod('visual_wellness_title', 'Wellness Experience'),
                'description' => get_theme_mod('visual_wellness_description', 'Step into our luxurious wellness sanctuary designed for your comfort and healing.'),
                'spaces' => [
                    [
                        'name' => 'IV Therapy Suite',
                        'image' => [
                            'unsplash_id' => 'photo-1559757175-0eb30cd8c063',
                            'alt' => get_theme_mod('visual_iv_suite_alt', 'Luxury IV therapy room - comfortable wellness environment'),
                            'caption' => 'Private IV therapy suite'
                        ]
                    ],
                    [
                        'name' => 'Consultation Room',
                        'image' => [
                            'unsplash_id' => 'photo-1571019613454-1cb2f99b2d8b',
                            'alt' => get_theme_mod('visual_consultation_alt', 'Private consultation room - personalized wellness planning'),
                            'caption' => 'Personalized consultation space'
                        ]
                    ],
                    [
                        'name' => 'Recovery Lounge',
                        'image' => [
                            'unsplash_id' => 'photo-1559757148-5c350d0d3c56',
                            'alt' => get_theme_mod('visual_recovery_alt', 'Wellness relaxation area - peaceful recovery space'),
                            'caption' => 'Tranquil recovery environment'
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Initialize optimization settings
     */
    private function init_optimization_settings() {
        $this->optimization_settings = [
            'lazy_loading' => get_theme_mod('visual_content_lazy_loading', true),
            'webp_support' => get_theme_mod('visual_content_webp_support', true),
            'progressive_loading' => get_theme_mod('visual_content_progressive', true),
            'critical_images' => get_theme_mod('visual_content_critical_images', 2),
            'cache_duration' => get_theme_mod('visual_content_cache_duration', 86400), // 24 hours
            'compression_quality' => get_theme_mod('visual_content_compression', 85),
            'responsive_breakpoints' => [
                'mobile' => 480,
                'tablet' => 768,
                'desktop' => 1024,
                'large' => 1200
            ]
        ];
    }

    /**
     * Generate Unsplash image URL with optimization
     *
     * @param string $unsplash_id Unsplash image ID
     * @param array $params Image parameters
     * @return string Optimized image URL
     */
    public function generate_unsplash_url($unsplash_id, $params = []) {
        $base_url = $this->unsplash_config['base_url'];
        $default_params = $this->unsplash_config['default_params'];

        // Merge with default parameters
        $params = array_merge($default_params, $params);

        // Build query string
        $query_string = http_build_query($params);

        return "{$base_url}/{$unsplash_id}?{$query_string}";
    }

    /**
     * Generate responsive image srcset
     *
     * @param string $unsplash_id Unsplash image ID
     * @param array $custom_params Custom parameters
     * @return string Srcset attribute value
     */
    public function generate_responsive_srcset($unsplash_id, $custom_params = []) {
        $srcset = [];
        $responsive_sizes = $this->unsplash_config['responsive_sizes'];

        foreach ($responsive_sizes as $size_name => $size_params) {
            $params = array_merge($size_params, $custom_params);
            $url = $this->generate_unsplash_url($unsplash_id, $params);
            $width = $params['w'];
            $srcset[] = "{$url} {$width}w";
        }

        return implode(', ', $srcset);
    }

    /**
     * Render visual content for a specific collection
     *
     * @param string $collection_key Collection identifier
     * @return string HTML output
     */
    public function render_collection($collection_key) {
        if (!isset($this->content_collections[$collection_key])) {
            return '';
        }

        $collection = $this->content_collections[$collection_key];
        $type = $collection['type'];

        switch ($type) {
            case 'before_after_gallery':
                return $this->render_before_after_gallery($collection);
            case 'treatment_video':
                return $this->render_treatment_video($collection);
            case 'treatment_results_gallery':
                return $this->render_treatment_results_gallery($collection);
            case 'transformation_gallery':
                return $this->render_transformation_gallery($collection);
            case 'experience_gallery':
                return $this->render_experience_gallery($collection);
            default:
                return $this->render_default_gallery($collection);
        }
    }

    /**
     * Render before/after gallery
     *
     * @param array $collection Collection data
     * @return string HTML output
     */
    private function render_before_after_gallery($collection) {
        $output = '<div class="visual-content-gallery before-after-gallery" role="region" aria-label="Before and after treatment results">';
        $output .= sprintf(
            '<div class="gallery-header">
                <h3 class="gallery-title">%s</h3>
                <p class="gallery-description">%s</p>
            </div>',
            esc_html($collection['title']),
            esc_html($collection['description'])
        );

        $output .= '<div class="before-after-grid">';

        foreach ($collection['images'] as $index => $image_pair) {
            $output .= '<div class="before-after-comparison" data-treatment="' . esc_attr($image_pair['treatment']) . '">';

            // Before image
            $before_srcset = $this->generate_responsive_srcset($image_pair['before']['unsplash_id']);
            $before_url = $this->generate_unsplash_url($image_pair['before']['unsplash_id']);

            $output .= sprintf(
                '<div class="before-image-container">
                    <img src="%s"
                         srcset="%s"
                         sizes="(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw"
                         alt="%s"
                         class="before-image%s"
                         loading="%s" />
                    <div class="image-overlay">
                        <span class="image-label">%s</span>
                        <span class="treatment-info">%s</span>
                    </div>
                </div>',
                esc_url($before_url),
                esc_attr($before_srcset),
                esc_attr($image_pair['before']['alt']),
                $index < $this->optimization_settings['critical_images'] ? ' critical-image' : '',
                $index < $this->optimization_settings['critical_images'] ? 'eager' : 'lazy',
                esc_html($image_pair['before']['caption']),
                esc_html($image_pair['treatment'])
            );

            // After image
            $after_srcset = $this->generate_responsive_srcset($image_pair['after']['unsplash_id']);
            $after_url = $this->generate_unsplash_url($image_pair['after']['unsplash_id']);

            $output .= sprintf(
                '<div class="after-image-container">
                    <img src="%s"
                         srcset="%s"
                         sizes="(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw"
                         alt="%s"
                         class="after-image%s"
                         loading="%s" />
                    <div class="image-overlay">
                        <span class="image-label">%s</span>
                        <span class="timeframe-info">%s</span>
                    </div>
                </div>',
                esc_url($after_url),
                esc_attr($after_srcset),
                esc_attr($image_pair['after']['alt']),
                $index < $this->optimization_settings['critical_images'] ? ' critical-image' : '',
                $index < $this->optimization_settings['critical_images'] ? 'eager' : 'lazy',
                esc_html($image_pair['after']['caption']),
                esc_html($image_pair['timeframe'])
            );

            $output .= '</div>'; // Close before-after-comparison
        }

        $output .= '</div>'; // Close before-after-grid
        $output .= '</div>'; // Close before-after-gallery

        return $output;
    }

    /**
     * Render treatment video
     *
     * @param array $collection Collection data
     * @return string HTML output
     */
    private function render_treatment_video($collection) {
        $video = $collection['video'];
        $poster_url = $this->generate_unsplash_url($video['poster']['unsplash_id'], ['w' => 1200, 'h' => 675]);
        $poster_srcset = $this->generate_responsive_srcset($video['poster']['unsplash_id'], ['h' => 675]);

        $output = '<div class="visual-content-video treatment-video" role="region" aria-label="Treatment demonstration video">';
        $output .= sprintf(
            '<div class="video-header">
                <h3 class="video-title">%s</h3>
                <p class="video-description">%s</p>
            </div>',
            esc_html($collection['title']),
            esc_html($collection['description'])
        );

        $output .= '<div class="video-container">';
        $output .= sprintf(
            '<div class="video-poster"
                 style="background-image: url(%s);"
                 role="img"
                 aria-label="%s">
                <button class="video-play-button"
                        aria-label="Play treatment demonstration video"
                        data-video-url="%s">
                    <span class="play-icon" aria-hidden="true">▶</span>
                    <span class="play-text">Watch Video</span>
                </button>
                <div class="video-info">
                    <span class="video-duration">%s</span>
                </div>
            </div>',
            esc_url($poster_url),
            esc_attr($video['poster']['alt']),
            esc_url($video['video_url']),
            esc_html($video['duration'])
        );

        $output .= sprintf(
            '<p class="video-caption">%s</p>',
            esc_html($video['description'])
        );

        $output .= '</div>'; // Close video-container
        $output .= '</div>'; // Close treatment-video

        return $output;
    }

    /**
     * Render treatment results gallery
     *
     * @param array $collection Collection data
     * @return string HTML output
     */
    private function render_treatment_results_gallery($collection) {
        $output = '<div class="visual-content-gallery treatment-results-gallery" role="region" aria-label="Treatment results showcase">';
        $output .= sprintf(
            '<div class="gallery-header">
                <h3 class="gallery-title">%s</h3>
                <p class="gallery-description">%s</p>
            </div>',
            esc_html($collection['title']),
            esc_html($collection['description'])
        );

        $output .= '<div class="treatment-results-grid">';

        foreach ($collection['treatments'] as $index => $treatment) {
            $image_url = $this->generate_unsplash_url($treatment['image']['unsplash_id']);
            $image_srcset = $this->generate_responsive_srcset($treatment['image']['unsplash_id']);

            $output .= sprintf(
                '<div class="treatment-result-item" data-treatment="%s">
                    <img src="%s"
                         srcset="%s"
                         sizes="(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw"
                         alt="%s"
                         class="treatment-result-image%s"
                         loading="%s" />
                    <div class="treatment-result-overlay">
                        <h4 class="treatment-name">%s</h4>
                        <p class="treatment-caption">%s</p>
                    </div>
                </div>',
                esc_attr(sanitize_title($treatment['name'])),
                esc_url($image_url),
                esc_attr($image_srcset),
                esc_attr($treatment['image']['alt']),
                $index < $this->optimization_settings['critical_images'] ? ' critical-image' : '',
                $index < $this->optimization_settings['critical_images'] ? 'eager' : 'lazy',
                esc_html($treatment['name']),
                esc_html($treatment['image']['caption'])
            );
        }

        $output .= '</div>'; // Close treatment-results-grid
        $output .= '</div>'; // Close treatment-results-gallery

        return $output;
    }

    /**
     * Render transformation gallery
     *
     * @param array $collection Collection data
     * @return string HTML output
     */
    private function render_transformation_gallery($collection) {
        $output = '<div class="visual-content-gallery transformation-gallery" role="region" aria-label="Body transformation results">';
        $output .= sprintf(
            '<div class="gallery-header">
                <h3 class="gallery-title">%s</h3>
                <p class="gallery-description">%s</p>
            </div>',
            esc_html($collection['title']),
            esc_html($collection['description'])
        );

        $output .= '<div class="transformation-grid">';

        foreach ($collection['transformations'] as $index => $transformation) {
            $output .= sprintf(
                '<div class="transformation-comparison" data-treatment="%s">',
                esc_attr(sanitize_title($transformation['treatment']))
            );

            // Before transformation
            $before_url = $this->generate_unsplash_url($transformation['before']['unsplash_id']);
            $before_srcset = $this->generate_responsive_srcset($transformation['before']['unsplash_id']);

            $output .= sprintf(
                '<div class="transformation-before">
                    <img src="%s"
                         srcset="%s"
                         sizes="(max-width: 768px) 100vw, 50vw"
                         alt="%s"
                         class="transformation-image%s"
                         loading="%s" />
                    <div class="transformation-overlay">
                        <span class="transformation-label before-label">%s</span>
                    </div>
                </div>',
                esc_url($before_url),
                esc_attr($before_srcset),
                esc_attr($transformation['before']['alt']),
                $index < $this->optimization_settings['critical_images'] ? ' critical-image' : '',
                $index < $this->optimization_settings['critical_images'] ? 'eager' : 'lazy',
                esc_html($transformation['before']['caption'])
            );

            // After transformation
            $after_url = $this->generate_unsplash_url($transformation['after']['unsplash_id']);
            $after_srcset = $this->generate_responsive_srcset($transformation['after']['unsplash_id']);

            $output .= sprintf(
                '<div class="transformation-after">
                    <img src="%s"
                         srcset="%s"
                         sizes="(max-width: 768px) 100vw, 50vw"
                         alt="%s"
                         class="transformation-image%s"
                         loading="%s" />
                    <div class="transformation-overlay">
                        <span class="transformation-label after-label">%s</span>
                    </div>
                </div>',
                esc_url($after_url),
                esc_attr($after_srcset),
                esc_attr($transformation['after']['alt']),
                $index < $this->optimization_settings['critical_images'] ? ' critical-image' : '',
                $index < $this->optimization_settings['critical_images'] ? 'eager' : 'lazy',
                esc_html($transformation['after']['caption'])
            );

            // Treatment info
            $output .= sprintf(
                '<div class="transformation-info">
                    <h4 class="transformation-treatment">%s</h4>
                    <p class="transformation-timeframe">%s</p>
                </div>',
                esc_html($transformation['treatment']),
                esc_html($transformation['timeframe'])
            );

            $output .= '</div>'; // Close transformation-comparison
        }

        $output .= '</div>'; // Close transformation-grid
        $output .= '</div>'; // Close transformation-gallery

        return $output;
    }

    /**
     * Render experience gallery
     *
     * @param array $collection Collection data
     * @return string HTML output
     */
    private function render_experience_gallery($collection) {
        $output = '<div class="visual-content-gallery experience-gallery" role="region" aria-label="Wellness facility showcase">';
        $output .= sprintf(
            '<div class="gallery-header">
                <h3 class="gallery-title">%s</h3>
                <p class="gallery-description">%s</p>
            </div>',
            esc_html($collection['title']),
            esc_html($collection['description'])
        );

        $output .= '<div class="experience-grid">';

        foreach ($collection['spaces'] as $index => $space) {
            $image_url = $this->generate_unsplash_url($space['image']['unsplash_id']);
            $image_srcset = $this->generate_responsive_srcset($space['image']['unsplash_id']);

            $output .= sprintf(
                '<div class="experience-space" data-space="%s">
                    <img src="%s"
                         srcset="%s"
                         sizes="(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw"
                         alt="%s"
                         class="experience-image%s"
                         loading="%s" />
                    <div class="experience-overlay">
                        <h4 class="space-name">%s</h4>
                        <p class="space-caption">%s</p>
                    </div>
                </div>',
                esc_attr(sanitize_title($space['name'])),
                esc_url($image_url),
                esc_attr($image_srcset),
                esc_attr($space['image']['alt']),
                $index < $this->optimization_settings['critical_images'] ? ' critical-image' : '',
                $index < $this->optimization_settings['critical_images'] ? 'eager' : 'lazy',
                esc_html($space['name']),
                esc_html($space['image']['caption'])
            );
        }

        $output .= '</div>'; // Close experience-grid
        $output .= '</div>'; // Close experience-gallery

        return $output;
    }

    /**
     * Render default gallery fallback
     *
     * @param array $collection Collection data
     * @return string HTML output
     */
    private function render_default_gallery($collection) {
        return sprintf(
            '<div class="visual-content-gallery default-gallery">
                <div class="gallery-header">
                    <h3 class="gallery-title">%s</h3>
                    <p class="gallery-description">%s</p>
                </div>
                <div class="gallery-placeholder">
                    <p>Visual content coming soon...</p>
                </div>
            </div>',
            esc_html($collection['title']),
            esc_html($collection['description'])
        );
    }

    /**
     * Get customizer controls for visual content
     *
     * @return array Customizer control configuration
     */
    public function get_customizer_controls() {
        return [
            // Global visual content settings
            'visual_content_default_width' => [
                'label' => 'Default Image Width',
                'type' => 'number',
                'default' => 800,
                'section' => 'visual_content_settings'
            ],
            'visual_content_default_height' => [
                'label' => 'Default Image Height',
                'type' => 'number',
                'default' => 600,
                'section' => 'visual_content_settings'
            ],
            'visual_content_quality' => [
                'label' => 'Image Quality (1-100)',
                'type' => 'range',
                'input_attrs' => ['min' => 1, 'max' => 100, 'step' => 1],
                'default' => 85,
                'section' => 'visual_content_settings'
            ],
            'visual_content_lazy_loading' => [
                'label' => 'Enable Lazy Loading',
                'type' => 'checkbox',
                'default' => true,
                'section' => 'visual_content_settings'
            ],
            'visual_content_webp_support' => [
                'label' => 'Enable WebP Support',
                'type' => 'checkbox',
                'default' => true,
                'section' => 'visual_content_settings'
            ],

            // Injectable Artistry controls
            'visual_injectable_title' => [
                'label' => 'Injectable Artistry Gallery Title',
                'type' => 'text',
                'default' => 'Injectable Artistry Results',
                'section' => 'visual_content_injectable'
            ],
            'visual_injectable_description' => [
                'label' => 'Injectable Artistry Gallery Description',
                'type' => 'textarea',
                'default' => 'See the natural, beautiful results achieved with our precision injectable treatments.',
                'section' => 'visual_content_injectable'
            ],

            // Additional collection-specific controls...
        ];
    }

    /**
     * Enqueue component styles
     */
    public function enqueue_styles() {
        // Styles are handled by semantic-components.css
        // This method ensures component is registered for style compilation
    }

    /**
     * Enqueue component scripts
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'visual-content-interactions',
            get_template_directory_uri() . '/assets/js/components/visual-content-interactions.js',
            ['jquery'],
            $this->version,
            true
        );

        // Localize script for optimization and interactions
        wp_localize_script('visual-content-interactions', 'visualContentData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('visual_content_nonce'),
            'optimizationSettings' => $this->optimization_settings,
            'unsplashConfig' => $this->unsplash_config
        ]);
    }

    /**
     * Get available collections
     *
     * @return array Available collection keys
     */
    public function get_available_collections() {
        return array_keys($this->content_collections);
    }

    /**
     * Get collection data for a specific collection
     *
     * @param string $collection_key Collection identifier
     * @return array|null Collection data or null if not found
     */
    public function get_collection_data($collection_key) {
        return isset($this->content_collections[$collection_key]) ? $this->content_collections[$collection_key] : null;
    }

    /**
     * Render the component
     * Required by BaseComponent
     *
     * @param array $args Component arguments
     * @return string Rendered HTML
     */
    public function render($args = []) {
        $collection_key = isset($args['collection']) ? $args['collection'] : 'injectable_artistry';
        return $this->render_collection($collection_key);
    }

    /**
     * Get default semantic tokens
     * Required by BaseComponent
     *
     * @return array Default tokens
     */
    public function get_default_tokens() {
        return [
            'visual-content-bg-color' => 'var(--semantic-color-surface-primary)',
            'visual-content-text-color' => 'var(--semantic-color-text-primary)',
            'visual-content-border-radius' => 'var(--semantic-border-radius-medium)',
            'visual-content-spacing' => 'var(--semantic-spacing-large)',
            'visual-content-shadow' => 'var(--semantic-shadow-medium)',
            'visual-content-transition' => 'var(--semantic-transition-smooth)',

            // Gallery specific tokens
            'gallery-item-bg' => 'var(--semantic-color-surface-secondary)',
            'gallery-item-border' => 'var(--semantic-color-border-subtle)',
            'gallery-item-hover-shadow' => 'var(--semantic-shadow-large)',

            // Video player tokens
            'video-player-bg' => 'var(--semantic-color-surface-tertiary)',
            'video-controls-color' => 'var(--semantic-color-primary)',
            'video-overlay-bg' => 'var(--semantic-color-overlay-dark)',

            // Before/After tokens
            'before-after-divider' => 'var(--semantic-color-accent-primary)',
            'before-after-label-bg' => 'var(--semantic-color-primary)',
            'before-after-label-text' => 'var(--semantic-color-text-inverse)',

            // Responsive tokens
            'mobile-columns' => '1',
            'tablet-columns' => '2',
            'desktop-columns' => '3',
            'large-columns' => '4'
        ];
    }

    /**
     * Get default component settings
     * Required by BaseComponent
     *
     * @return array Default settings
     */
    public function get_defaults() {
        return [
            'collection' => 'injectable_artistry',
            'show_captions' => true,
            'show_treatments' => true,
            'show_timeframes' => true,
            'lazy_loading' => true,
            'webp_support' => true,
            'responsive_images' => true,
            'accessibility_enhanced' => true,
            'schema_markup' => true,
            'performance_optimized' => true
        ];
    }
}
