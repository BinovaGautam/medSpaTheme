<?php
/**
 * Visual Content Customizer Controls - T11.8 Implementation
 *
 * Advanced customizer controls for visual content management including:
 * - Custom Unsplash browser within WordPress Customizer
 * - Drag & Drop interface for image management
 * - Bulk image management capabilities
 * - Alt text editor with accessibility focus
 * - Image optimization controls
 * - Real-time preview integration
 *
 * @package MedSpaTheme
 * @subpackage Customizer
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Visual Content Customizer Class
 */
class VisualContentCustomizer {

    /**
     * Initialize customizer controls
     */
    public function __construct() {
        add_action('customize_register', [$this, 'register_controls']);
        add_action('customize_controls_enqueue_scripts', [$this, 'enqueue_customizer_scripts']);
        add_action('customize_preview_init', [$this, 'enqueue_preview_scripts']);
        add_action('wp_ajax_unsplash_search', [$this, 'handle_unsplash_search']);
        add_action('wp_ajax_save_visual_content', [$this, 'handle_save_visual_content']);
    }

    /**
     * Register customizer controls
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager instance
     */
    public function register_controls($wp_customize) {

        // Add Visual Content Panel
        $wp_customize->add_panel('visual_content_panel', [
            'title' => __('Visual Content Management', 'medspatheme'),
            'description' => __('Manage visual content for service sections with Unsplash integration', 'medspatheme'),
            'priority' => 160,
            'capability' => 'edit_theme_options'
        ]);

        // Global Visual Content Settings Section
        $wp_customize->add_section('visual_content_global', [
            'title' => __('Global Settings', 'medspatheme'),
            'panel' => 'visual_content_panel',
            'priority' => 10
        ]);

        // Default Image Dimensions
        $wp_customize->add_setting('visual_content_default_width', [
            'default' => 800,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('visual_content_default_width', [
            'label' => __('Default Image Width (px)', 'medspatheme'),
            'section' => 'visual_content_global',
            'type' => 'number',
            'input_attrs' => [
                'min' => 200,
                'max' => 2000,
                'step' => 50
            ]
        ]);

        $wp_customize->add_setting('visual_content_default_height', [
            'default' => 600,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('visual_content_default_height', [
            'label' => __('Default Image Height (px)', 'medspatheme'),
            'section' => 'visual_content_global',
            'type' => 'number',
            'input_attrs' => [
                'min' => 200,
                'max' => 2000,
                'step' => 50
            ]
        ]);

        // Image Quality Control
        $wp_customize->add_setting('visual_content_quality', [
            'default' => 85,
            'sanitize_callback' => [$this, 'sanitize_range'],
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('visual_content_quality', [
            'label' => __('Image Quality (1-100)', 'medspatheme'),
            'section' => 'visual_content_global',
            'type' => 'range',
            'input_attrs' => [
                'min' => 1,
                'max' => 100,
                'step' => 1
            ]
        ]);

        // Performance Settings
        $wp_customize->add_setting('visual_content_lazy_loading', [
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('visual_content_lazy_loading', [
            'label' => __('Enable Lazy Loading', 'medspatheme'),
            'section' => 'visual_content_global',
            'type' => 'checkbox'
        ]);

        $wp_customize->add_setting('visual_content_webp_support', [
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('visual_content_webp_support', [
            'label' => __('Enable WebP Support', 'medspatheme'),
            'section' => 'visual_content_global',
            'type' => 'checkbox'
        ]);

        // Injectable Artistry Section
        $this->register_injectable_artistry_controls($wp_customize);

        // Laser Services Section
        $this->register_laser_services_controls($wp_customize);

        // Facial Renaissance Section
        $this->register_facial_renaissance_controls($wp_customize);

        // Body Sculpting Section
        $this->register_body_sculpting_controls($wp_customize);

        // Wellness Sanctuary Section
        $this->register_wellness_sanctuary_controls($wp_customize);
    }

    /**
     * Register Injectable Artistry controls
     */
    private function register_injectable_artistry_controls($wp_customize) {
        // Injectable Artistry Section
        $wp_customize->add_section('visual_content_injectable', [
            'title' => __('Injectable Artistry Gallery', 'medspatheme'),
            'panel' => 'visual_content_panel',
            'priority' => 20
        ]);

        // Gallery Title
        $wp_customize->add_setting('visual_injectable_title', [
            'default' => 'Injectable Artistry Results',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('visual_injectable_title', [
            'label' => __('Gallery Title', 'medspatheme'),
            'section' => 'visual_content_injectable',
            'type' => 'text'
        ]);

        // Gallery Description
        $wp_customize->add_setting('visual_injectable_description', [
            'default' => 'See the natural, beautiful results achieved with our precision injectable treatments.',
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('visual_injectable_description', [
            'label' => __('Gallery Description', 'medspatheme'),
            'section' => 'visual_content_injectable',
            'type' => 'textarea'
        ]);

        // Custom Unsplash Browser Control
        $wp_customize->add_setting('visual_injectable_images', [
            'default' => json_encode([
                [
                    'before' => [
                        'unsplash_id' => 'photo-1570172619644-dfd03ed5d881',
                        'alt' => 'Before Botox treatment - natural expression lines visible'
                    ],
                    'after' => [
                        'unsplash_id' => 'photo-1594824369039-a8c2e8d3c8c4',
                        'alt' => 'After Botox treatment - smooth, natural-looking results'
                    ],
                    'treatment' => 'Botox',
                    'timeframe' => '2 weeks post-treatment'
                ]
            ]),
            'sanitize_callback' => [$this, 'sanitize_json'],
            'transport' => 'postMessage'
        ]);

        // Register custom control for Unsplash browser - conditional include
        $unsplash_control_file = get_template_directory() . '/inc/customizer/controls/class-unsplash-browser-control.php';
        if (file_exists($unsplash_control_file)) {
            require_once $unsplash_control_file;

            if (class_exists('Unsplash_Browser_Control')) {
                $wp_customize->add_control(new Unsplash_Browser_Control($wp_customize, 'visual_injectable_images', [
                    'label' => __('Before/After Images', 'medspatheme'),
                    'section' => 'visual_content_injectable',
                    'settings' => 'visual_injectable_images',
                    'type' => 'before_after_gallery',
                    'description' => __('Drag and drop to reorder images. Click to edit alt text and treatment details.', 'medspatheme')
                ]));
            } else {
                // Fallback to textarea if custom control class doesn't exist
                $wp_customize->add_control('visual_injectable_images', [
                    'label' => __('Before/After Images (JSON)', 'medspatheme'),
                    'section' => 'visual_content_injectable',
                    'type' => 'textarea',
                    'description' => __('JSON configuration for before/after images', 'medspatheme')
                ]);
            }
        } else {
            // Fallback to textarea if control file doesn't exist
            $wp_customize->add_control('visual_injectable_images', [
                'label' => __('Before/After Images (JSON)', 'medspatheme'),
                'section' => 'visual_content_injectable',
                'type' => 'textarea',
                'description' => __('JSON configuration for before/after images', 'medspatheme')
            ]);
        }
    }

    /**
     * Register Laser Services controls
     */
    private function register_laser_services_controls($wp_customize) {
        // Define Unsplash control file path
        $unsplash_control_file = get_template_directory() . '/inc/customizer/controls/class-unsplash-browser-control.php';

        // Laser Services Section
        $wp_customize->add_section('visual_content_laser', [
            'title' => __('Laser Services Video', 'medspatheme'),
            'panel' => 'visual_content_panel',
            'priority' => 30
        ]);

        // Video Title
        $wp_customize->add_setting('visual_laser_title', [
            'default' => 'Advanced Laser Technology',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('visual_laser_title', [
            'label' => __('Video Section Title', 'medspatheme'),
            'section' => 'visual_content_laser',
            'type' => 'text'
        ]);

        // Video Description
        $wp_customize->add_setting('visual_laser_description', [
            'default' => 'Experience our state-of-the-art laser treatments in our modern facility.',
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('visual_laser_description', [
            'label' => __('Video Description', 'medspatheme'),
            'section' => 'visual_content_laser',
            'type' => 'textarea'
        ]);

        // Video Poster Image
        $wp_customize->add_setting('visual_laser_poster_id', [
            'default' => 'photo-1559757148-5c350d0d3c56',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage'
        ]);

        // Conditional Unsplash Browser Control for poster image
        if (file_exists($unsplash_control_file) && class_exists('Unsplash_Browser_Control')) {
            $wp_customize->add_control(new Unsplash_Browser_Control($wp_customize, 'visual_laser_poster_id', [
                'label' => __('Video Poster Image', 'medspatheme'),
                'section' => 'visual_content_laser',
                'settings' => 'visual_laser_poster_id',
                'type' => 'single_image',
                'description' => __('Select poster image for video player', 'medspatheme')
            ]));
        } else {
            $wp_customize->add_control('visual_laser_poster_id', [
                'label' => __('Video Poster Image ID', 'medspatheme'),
                'section' => 'visual_content_laser',
                'type' => 'text',
                'description' => __('Unsplash image ID for video poster', 'medspatheme')
            ]);
        }

        // Video URL
        $wp_customize->add_setting('visual_laser_video_url', [
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('visual_laser_video_url', [
            'label' => __('Video URL', 'medspatheme'),
            'section' => 'visual_content_laser',
            'type' => 'url',
            'description' => __('Leave empty to show placeholder', 'medspatheme')
        ]);

        // Poster Alt Text
        $wp_customize->add_setting('visual_laser_poster_alt', [
            'default' => 'Advanced laser treatment technology in modern medical spa setting',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('visual_laser_poster_alt', [
            'label' => __('Poster Image Alt Text', 'medspatheme'),
            'section' => 'visual_content_laser',
            'type' => 'text',
            'description' => __('Describe the poster image for accessibility', 'medspatheme')
        ]);
    }

    /**
     * Register Facial Renaissance controls
     */
    private function register_facial_renaissance_controls($wp_customize) {
        // Define Unsplash control file path
        $unsplash_control_file = get_template_directory() . '/inc/customizer/controls/class-unsplash-browser-control.php';

        // Facial Renaissance Section
        $wp_customize->add_section('visual_content_facial', [
            'title' => __('Facial Renaissance Gallery', 'medspatheme'),
            'panel' => 'visual_content_panel',
            'priority' => 40
        ]);

        // Gallery Title
        $wp_customize->add_setting('visual_facial_title', [
            'default' => 'Facial Treatment Results',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('visual_facial_title', [
            'label' => __('Gallery Title', 'medspatheme'),
            'section' => 'visual_content_facial',
            'type' => 'text'
        ]);

        // Gallery Description
        $wp_customize->add_setting('visual_facial_description', [
            'default' => 'Discover the transformative results of our advanced facial treatments.',
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('visual_facial_description', [
            'label' => __('Gallery Description', 'medspatheme'),
            'section' => 'visual_content_facial',
            'type' => 'textarea'
        ]);

        // Treatment Results Gallery
        $wp_customize->add_setting('visual_facial_treatments', [
            'default' => json_encode([
                [
                    'name' => 'HydraFacial',
                    'image_id' => 'photo-1560750588-73207b1ef5b8',
                    'alt' => 'HydraFacial treatment results - glowing, hydrated skin',
                    'caption' => 'Immediate glow and hydration'
                ],
                [
                    'name' => 'Chemical Peels',
                    'image_id' => 'photo-1515377905703-c4788e51af15',
                    'alt' => 'Chemical peel results - smooth, renewed skin texture',
                    'caption' => 'Renewed skin texture and tone'
                ],
                [
                    'name' => 'Microneedling',
                    'image_id' => 'photo-1571019613454-1cb2f99b2d8b',
                    'alt' => 'Microneedling results - improved skin firmness and texture',
                    'caption' => 'Enhanced collagen and firmness'
                ]
            ]),
            'sanitize_callback' => [$this, 'sanitize_json'],
            'transport' => 'postMessage'
        ]);

        // Conditional Unsplash Browser Control for treatment gallery
        if (file_exists($unsplash_control_file) && class_exists('Unsplash_Browser_Control')) {
            $wp_customize->add_control(new Unsplash_Browser_Control($wp_customize, 'visual_facial_treatments', [
                'label' => __('Treatment Results', 'medspatheme'),
                'section' => 'visual_content_facial',
                'settings' => 'visual_facial_treatments',
                'type' => 'treatment_gallery',
                'description' => __('Manage treatment result images with drag & drop', 'medspatheme')
            ]));
        } else {
            $wp_customize->add_control('visual_facial_treatments', [
                'label' => __('Treatment Results (JSON)', 'medspatheme'),
                'section' => 'visual_content_facial',
                'type' => 'textarea',
                'description' => __('JSON configuration for treatment results', 'medspatheme')
            ]);
        }
    }

    /**
     * Register Body Sculpting controls
     */
    private function register_body_sculpting_controls($wp_customize) {
        // Define Unsplash control file path
        $unsplash_control_file = get_template_directory() . '/inc/customizer/controls/class-unsplash-browser-control.php';

        // Body Sculpting Section
        $wp_customize->add_section('visual_content_body', [
            'title' => __('Body Sculpting Gallery', 'medspatheme'),
            'panel' => 'visual_content_panel',
            'priority' => 50
        ]);

        // Gallery Title
        $wp_customize->add_setting('visual_body_title', [
            'default' => 'Body Transformation Results',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('visual_body_title', [
            'label' => __('Gallery Title', 'medspatheme'),
            'section' => 'visual_content_body',
            'type' => 'text'
        ]);

        // Gallery Description
        $wp_customize->add_setting('visual_body_description', [
            'default' => 'See the remarkable body contouring results achieved with our advanced treatments.',
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('visual_body_description', [
            'label' => __('Gallery Description', 'medspatheme'),
            'section' => 'visual_content_body',
            'type' => 'textarea'
        ]);

        // Transformation Gallery
        $wp_customize->add_setting('visual_body_transformations', [
            'default' => json_encode([
                [
                    'treatment' => 'CoolSculpting',
                    'before_id' => 'photo-1571019613454-1cb2f99b2d8b',
                    'after_id' => 'photo-1544367567-0f2fcb009e0b',
                    'before_alt' => 'Before CoolSculpting treatment - targeted area assessment',
                    'after_alt' => 'After CoolSculpting treatment - contoured silhouette results',
                    'timeframe' => '12 weeks post-treatment'
                ]
            ]),
            'sanitize_callback' => [$this, 'sanitize_json'],
            'transport' => 'postMessage'
        ]);

        // Conditional Unsplash Browser Control for transformation gallery
        if (file_exists($unsplash_control_file) && class_exists('Unsplash_Browser_Control')) {
            $wp_customize->add_control(new Unsplash_Browser_Control($wp_customize, 'visual_body_transformations', [
                'label' => __('Body Transformations', 'medspatheme'),
                'section' => 'visual_content_body',
                'settings' => 'visual_body_transformations',
                'type' => 'transformation_gallery',
                'description' => __('Before/after transformation images', 'medspatheme')
            ]));
        } else {
            $wp_customize->add_control('visual_body_transformations', [
                'label' => __('Body Transformations (JSON)', 'medspatheme'),
                'section' => 'visual_content_body',
                'type' => 'textarea',
                'description' => __('JSON configuration for transformations', 'medspatheme')
            ]);
        }
    }

    /**
     * Register Wellness Sanctuary controls
     */
    private function register_wellness_sanctuary_controls($wp_customize) {
        // Define Unsplash control file path
        $unsplash_control_file = get_template_directory() . '/inc/customizer/controls/class-unsplash-browser-control.php';

        // Wellness Sanctuary Section
        $wp_customize->add_section('visual_content_wellness', [
            'title' => __('Wellness Sanctuary Gallery', 'medspatheme'),
            'panel' => 'visual_content_panel',
            'priority' => 60
        ]);

        // Gallery Title
        $wp_customize->add_setting('visual_wellness_title', [
            'default' => 'Wellness Experience',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('visual_wellness_title', [
            'label' => __('Gallery Title', 'medspatheme'),
            'section' => 'visual_content_wellness',
            'type' => 'text'
        ]);

        // Gallery Description
        $wp_customize->add_setting('visual_wellness_description', [
            'default' => 'Step into our luxurious wellness sanctuary designed for your comfort and healing.',
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('visual_wellness_description', [
            'label' => __('Gallery Description', 'medspatheme'),
            'section' => 'visual_content_wellness',
            'type' => 'textarea'
        ]);

        // Experience Gallery
        $wp_customize->add_setting('visual_wellness_spaces', [
            'default' => json_encode([
                [
                    'name' => 'IV Therapy Suite',
                    'image_id' => 'photo-1559757175-0eb30cd8c063',
                    'alt' => 'Luxury IV therapy room - comfortable wellness environment',
                    'caption' => 'Private IV therapy suite'
                ],
                [
                    'name' => 'Consultation Room',
                    'image_id' => 'photo-1571019613454-1cb2f99b2d8b',
                    'alt' => 'Private consultation room - personalized wellness planning',
                    'caption' => 'Personalized consultation space'
                ],
                [
                    'name' => 'Recovery Lounge',
                    'image_id' => 'photo-1559757148-5c350d0d3c56',
                    'alt' => 'Wellness relaxation area - peaceful recovery space',
                    'caption' => 'Tranquil recovery environment'
                ]
            ]),
            'sanitize_callback' => [$this, 'sanitize_json'],
            'transport' => 'postMessage'
        ]);

        // Conditional Unsplash Browser Control for experience gallery
        if (file_exists($unsplash_control_file) && class_exists('Unsplash_Browser_Control')) {
            $wp_customize->add_control(new Unsplash_Browser_Control($wp_customize, 'visual_wellness_spaces', [
                'label' => __('Wellness Spaces', 'medspatheme'),
                'section' => 'visual_content_wellness',
                'settings' => 'visual_wellness_spaces',
                'type' => 'experience_gallery',
                'description' => __('Showcase your wellness facility spaces', 'medspatheme')
            ]));
        } else {
            $wp_customize->add_control('visual_wellness_spaces', [
                'label' => __('Wellness Spaces (JSON)', 'medspatheme'),
                'section' => 'visual_content_wellness',
                'type' => 'textarea',
                'description' => __('JSON configuration for wellness spaces', 'medspatheme')
            ]);
        }
    }

    /**
     * Enqueue customizer control scripts
     */
    public function enqueue_customizer_scripts() {
        wp_enqueue_script(
            'visual-content-customizer',
            get_template_directory_uri() . '/assets/js/customizer/visual-content-customizer.js',
            ['jquery', 'customize-controls'],
            '1.0.0',
            true
        );

        wp_enqueue_style(
            'visual-content-customizer',
            get_template_directory_uri() . '/assets/css/customizer/visual-content-customizer.css',
            ['customize-controls'],
            '1.0.0'
        );

        // Localize script with API data
        wp_localize_script('visual-content-customizer', 'visualContentCustomizer', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('visual_content_customizer'),
            'unsplashCollections' => [
                'medical_spa' => 'medical-spa-treatments',
                'beauty_treatments' => 'beauty-aesthetic-medicine',
                'wellness' => 'spa-wellness-luxury',
                'before_after' => 'medical-aesthetics-results'
            ],
            'strings' => [
                'searchPlaceholder' => __('Search Unsplash images...', 'medspatheme'),
                'loading' => __('Loading...', 'medspatheme'),
                'selectImage' => __('Select Image', 'medspatheme'),
                'editAltText' => __('Edit Alt Text', 'medspatheme'),
                'dragToReorder' => __('Drag to reorder', 'medspatheme'),
                'removeImage' => __('Remove Image', 'medspatheme')
            ]
        ]);
    }

    /**
     * Enqueue preview scripts
     */
    public function enqueue_preview_scripts() {
        wp_enqueue_script(
            'visual-content-preview',
            get_template_directory_uri() . '/assets/js/customizer/visual-content-preview.js',
            ['jquery', 'customize-preview'],
            '1.0.0',
            true
        );
    }

    /**
     * Handle Unsplash search AJAX request
     */
    public function handle_unsplash_search() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'visual_content_customizer')) {
            wp_die('Security check failed');
        }

        $search_term = sanitize_text_field($_POST['search_term']);
        $collection = sanitize_text_field($_POST['collection']);
        $page = intval($_POST['page']);

        // Mock Unsplash API response for development
        $mock_results = $this->get_mock_unsplash_results($search_term, $collection, $page);

        wp_send_json_success($mock_results);
    }

    /**
     * Handle save visual content AJAX request
     */
    public function handle_save_visual_content() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'visual_content_customizer')) {
            wp_die('Security check failed');
        }

        $setting_id = sanitize_text_field($_POST['setting_id']);
        $content_data = $this->sanitize_json($_POST['content_data']);

        // Save to theme mod
        set_theme_mod($setting_id, $content_data);

        wp_send_json_success([
            'message' => __('Visual content saved successfully', 'medspatheme')
        ]);
    }

    /**
     * Get mock Unsplash results for development
     */
    private function get_mock_unsplash_results($search_term, $collection, $page) {
        $mock_images = [
            [
                'id' => 'photo-1570172619644-dfd03ed5d881',
                'urls' => [
                    'thumb' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=200&h=200&fit=crop',
                    'small' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=400&h=300&fit=crop',
                    'regular' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=800&h=600&fit=crop'
                ],
                'alt_description' => 'Medical spa treatment room with modern equipment',
                'description' => 'Professional medical spa environment'
            ],
            [
                'id' => 'photo-1594824369039-a8c2e8d3c8c4',
                'urls' => [
                    'thumb' => 'https://images.unsplash.com/photo-1594824369039-a8c2e8d3c8c4?w=200&h=200&fit=crop',
                    'small' => 'https://images.unsplash.com/photo-1594824369039-a8c2e8d3c8c4?w=400&h=300&fit=crop',
                    'regular' => 'https://images.unsplash.com/photo-1594824369039-a8c2e8d3c8c4?w=800&h=600&fit=crop'
                ],
                'alt_description' => 'Beautiful skin after facial treatment',
                'description' => 'Glowing skin results from professional treatment'
            ],
            [
                'id' => 'photo-1559757148-5c350d0d3c56',
                'urls' => [
                    'thumb' => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=200&h=200&fit=crop',
                    'small' => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=400&h=300&fit=crop',
                    'regular' => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=800&h=600&fit=crop'
                ],
                'alt_description' => 'Advanced laser treatment technology',
                'description' => 'State-of-the-art laser equipment in medical spa'
            ]
        ];

        return [
            'results' => $mock_images,
            'total' => 50,
            'total_pages' => 5
        ];
    }

    /**
     * Sanitize range input
     */
    public function sanitize_range($input) {
        $input = intval($input);
        return ($input >= 1 && $input <= 100) ? $input : 85;
    }

    /**
     * Sanitize JSON input
     */
    public function sanitize_json($input) {
        $decoded = json_decode($input, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $input;
        }
        return '[]';
    }
}

// Initialize Visual Content Customizer
new VisualContentCustomizer();
