<?php
/**
 * Trust Indicators Component
 *
 * Implements "Why Choose Us" trust indicators section with semantic tokenization
 *
 * @package MedSpaTheme
 * @since 1.0.0
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 * @task T11.3 Why Choose Us Section Implementation
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/components/base-component.php';

/**
 * TrustIndicatorsComponent Class
 *
 * Creates trust indicator cards with semantic design tokens,
 * WCAG AAA accessibility, and mobile-first responsive design.
 */
class TrustIndicatorsComponent extends BaseComponent {

    /**
     * Component name identifier
     * @var string
     */
    protected $component_name = 'trust-indicators';

    /**
     * Component version
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Trust indicators configuration
     * @var array
     */
    protected $trust_indicators = [
        'board-certified' => [
            'icon' => '🏆',
            'title' => 'Board Certified',
            'description' => 'Expert medical professionals with advanced training in aesthetic medicine and patient safety.',
            'order' => 1,
            'color_variant' => 'primary'
        ],
        'award-winning' => [
            'icon' => '⭐',
            'title' => 'Award Winning',
            'description' => 'Recognized excellence in medical aesthetics with industry awards and patient satisfaction.',
            'order' => 2,
            'color_variant' => 'secondary'
        ],
        'happy-patients' => [
            'icon' => '👥',
            'title' => '2000+ Happy Patients',
            'description' => 'Trusted by thousands of patients for natural-looking results and exceptional care.',
            'order' => 3,
            'color_variant' => 'accent'
        ],
        'hipaa-compliant' => [
            'icon' => '🔒',
            'title' => 'HIPAA Compliant',
            'description' => 'Your privacy and medical information are protected with the highest security standards.',
            'order' => 4,
            'color_variant' => 'primary'
        ]
    ];

    /**
     * Constructor
     *
     * @param array $args Component arguments
     */
    public function __construct($args = []) {
        parent::__construct($args);
        $this->init_trust_indicators();
    }

    /**
     * Initialize trust indicators data
     * Following Sprint 11 T11.3 specifications
     */
    private function init_trust_indicators() {
        $this->trust_indicators = [
            'board_certified' => [
                'id' => 'board-certified',
                'title' => get_theme_mod('trust_board_certified_title', 'Board Certified'),
                'subtitle' => get_theme_mod('trust_board_certified_subtitle', 'Medical Expertise'),
                'description' => get_theme_mod('trust_board_certified_description', 'Our practitioners are board-certified medical professionals with extensive training in aesthetic medicine and patient safety.'),
                'icon' => '🏆',
                'icon_class' => 'trust-icon-board-certified',
                'stat_number' => get_theme_mod('trust_board_certified_stat', '15+'),
                'stat_label' => get_theme_mod('trust_board_certified_stat_label', 'Years Experience'),
                'verification' => [
                    'text' => 'American Board of Dermatology',
                    'url' => get_theme_mod('trust_board_certified_verification_url', '#')
                ],
                'priority' => 1,
                'color_scheme' => 'primary'
            ],
            'award_winning' => [
                'id' => 'award-winning',
                'title' => get_theme_mod('trust_award_winning_title', 'Award Winning'),
                'subtitle' => get_theme_mod('trust_award_winning_subtitle', 'Industry Recognition'),
                'description' => get_theme_mod('trust_award_winning_description', 'Recognized by industry leaders for excellence in patient care, innovative treatments, and outstanding results.'),
                'icon' => '⭐',
                'icon_class' => 'trust-icon-award-winning',
                'stat_number' => get_theme_mod('trust_award_winning_stat', '12'),
                'stat_label' => get_theme_mod('trust_award_winning_stat_label', 'Industry Awards'),
                'verification' => [
                    'text' => 'View Awards & Recognition',
                    'url' => get_theme_mod('trust_award_winning_verification_url', '/about/awards/')
                ],
                'priority' => 2,
                'color_scheme' => 'secondary'
            ],
            'happy_patients' => [
                'id' => 'happy-patients',
                'title' => get_theme_mod('trust_happy_patients_title', '2000+ Happy Patients'),
                'subtitle' => get_theme_mod('trust_happy_patients_subtitle', 'Proven Results'),
                'description' => get_theme_mod('trust_happy_patients_description', 'Over 2000 satisfied patients have trusted us with their aesthetic goals and achieved remarkable, natural-looking results.'),
                'icon' => '👥',
                'icon_class' => 'trust-icon-happy-patients',
                'stat_number' => get_theme_mod('trust_happy_patients_stat', '98%'),
                'stat_label' => get_theme_mod('trust_happy_patients_stat_label', 'Satisfaction Rate'),
                'verification' => [
                    'text' => 'Read Patient Reviews',
                    'url' => get_theme_mod('trust_happy_patients_verification_url', '/reviews/')
                ],
                'priority' => 3,
                'color_scheme' => 'accent'
            ],
            'hipaa_compliant' => [
                'id' => 'hipaa-compliant',
                'title' => get_theme_mod('trust_hipaa_compliant_title', 'HIPAA Compliant'),
                'subtitle' => get_theme_mod('trust_hipaa_compliant_subtitle', 'Privacy Protected'),
                'description' => get_theme_mod('trust_hipaa_compliant_description', 'Your privacy and medical information are protected with the highest security standards and HIPAA compliance protocols.'),
                'icon' => '🔒',
                'icon_class' => 'trust-icon-hipaa-compliant',
                'stat_number' => get_theme_mod('trust_hipaa_compliant_stat', '100%'),
                'stat_label' => get_theme_mod('trust_hipaa_compliant_stat_label', 'Privacy Protected'),
                'verification' => [
                    'text' => 'Privacy Policy',
                    'url' => get_theme_mod('trust_hipaa_compliant_verification_url', '/privacy-policy/')
                ],
                'priority' => 4,
                'color_scheme' => 'neutral'
            ]
        ];

        // Sort by priority
        uasort($this->trust_indicators, function($a, $b) {
            return $a['priority'] <=> $b['priority'];
        });
    }

    /**
     * Render the trust indicators section
     *
     * @param array $args Component arguments
     * @return string HTML output
     */
    public function render($args = []) {
        $start_time = microtime(true);

        // Merge arguments with defaults
        $args = wp_parse_args($args, $this->get_defaults());

        // Get indicators to display
        $indicators_to_show = $args['indicators'] ?? array_keys($this->trust_indicators);

        // Build HTML output
        $html = $this->render_trust_indicators_section($indicators_to_show, $args);

        // Performance validation (<100ms requirement)
        $render_time = microtime(true) - $start_time;
        if ($render_time > 0.1) {
            error_log("TrustIndicatorsComponent: Render time {$render_time}s exceeds 100ms requirement");
        }

        return $html;
    }

    /**
     * Render the complete trust indicators section
     *
     * @param array $indicators_to_show Indicators to display
     * @param array $args Component arguments
     * @return string HTML output
     */
    protected function render_trust_indicators_section($indicators_to_show, $args) {
        $section_header = $this->render_section_header($args);
        $trust_indicators = $this->render_trust_indicators($indicators_to_show, $args);

        $accessibility_attrs = $this->get_accessibility_attributes([
            'aria-labelledby' => 'trust-indicators-title',
            'role' => 'region'
        ]);

        return sprintf(
            '<section class="trust-indicators-section" %s>
                <div class="container">
                    %s
                    <div class="trust-indicators-grid">
                        %s
                    </div>
                </div>
            </section>',
            $accessibility_attrs,
            $section_header,
            $trust_indicators
        );
    }

    /**
     * Render section header
     *
     * @param array $args Component arguments
     * @return string HTML output
     */
    protected function render_section_header($args) {
        $title = $args['section_title'] ?? 'Why Choose PreetiDreams';
        $subtitle = $args['section_subtitle'] ?? 'Experience the difference of medical artistry combined with luxury care';

        return sprintf(
            '<div class="section__header text-center">
                <h2 id="trust-indicators-title" class="section__title">%s</h2>
                <p class="section__subtitle">%s</p>
            </div>',
            esc_html($title),
            esc_html($subtitle)
        );
    }

    /**
     * Render all trust indicators
     *
     * @param array $indicators_to_show Indicators to display
     * @param array $args Component arguments
     * @return string HTML output
     */
    protected function render_trust_indicators($indicators_to_show, $args) {
        $html = '';

        foreach ($indicators_to_show as $indicator_key) {
            if (!isset($this->trust_indicators[$indicator_key])) {
                continue;
            }

            $indicator_data = $this->trust_indicators[$indicator_key];
            $html .= $this->render_single_trust_indicator($indicator_key, $indicator_data, $args);
        }

        return $html;
    }

    /**
     * Render a single trust indicator card
     *
     * @param string $indicator_key Indicator identifier
     * @param array $indicator_data Indicator configuration
     * @param array $args Component arguments
     * @return string HTML output
     */
    protected function render_single_trust_indicator($indicator_key, $indicator_data, $args) {
        $accessibility_attrs = $this->get_accessibility_attributes([
            'aria-labelledby' => "trust-{$indicator_key}-title",
            'role' => 'article'
        ]);

        $color_class = 'trust-indicator--' . $indicator_data['color_variant'];

        return sprintf(
            '<div class="trust-indicator-card trust-indicator--%s %s" %s>
                <div class="trust-indicator__icon" aria-hidden="true">%s</div>
                <div class="trust-indicator__content">
                    <h3 id="trust-%s-title" class="trust-indicator__title">%s</h3>
                    <p class="trust-indicator__description">%s</p>
                </div>
            </div>',
            esc_attr($indicator_key),
            esc_attr($color_class),
            $accessibility_attrs,
            $indicator_data['icon'],
            esc_attr($indicator_key),
            esc_html($indicator_data['title']),
            esc_html($indicator_data['description'])
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
                'default' => 'Why Choose PreetiDreams',
                'sanitize_callback' => 'sanitize_text_field'
            ],
            'section_subtitle' => [
                'type' => 'text',
                'label' => 'Section Subtitle',
                'default' => 'Experience the difference of medical artistry combined with luxury care',
                'sanitize_callback' => 'sanitize_text_field'
            ],
            'show_board_certified' => [
                'type' => 'checkbox',
                'label' => 'Show Board Certified Indicator',
                'default' => true,
                'sanitize_callback' => 'wp_validate_boolean'
            ],
            'show_award_winning' => [
                'type' => 'checkbox',
                'label' => 'Show Award Winning Indicator',
                'default' => true,
                'sanitize_callback' => 'wp_validate_boolean'
            ],
            'show_happy_patients' => [
                'type' => 'checkbox',
                'label' => 'Show Happy Patients Indicator',
                'default' => true,
                'sanitize_callback' => 'wp_validate_boolean'
            ],
            'show_hipaa_compliant' => [
                'type' => 'checkbox',
                'label' => 'Show HIPAA Compliant Indicator',
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
            // Trust Indicators Section
            'trust-section-bg' => 'var(--color-background-secondary)',
            'trust-section-padding' => 'var(--space-4xl) 0',

            // Trust Indicator Cards
            'trust-card-bg' => 'var(--color-surface)',
            'trust-card-border' => 'var(--color-surface-tertiary)',
            'trust-card-hover-bg' => 'var(--color-surface-hover)',
            'trust-card-padding' => 'var(--space-xl)',
            'trust-card-border-radius' => 'var(--radius-lg)',
            'trust-card-shadow' => 'var(--shadow-sm)',
            'trust-card-hover-shadow' => 'var(--shadow-lg)',

            // Typography
            'trust-title-color' => 'var(--color-text-primary)',
            'trust-title-font-size' => 'var(--text-lg)',
            'trust-title-font-weight' => 'var(--font-weight-semibold)',
            'trust-description-color' => 'var(--color-text-secondary)',
            'trust-description-font-size' => 'var(--text-base)',

            // Icon Styling
            'trust-icon-size' => 'var(--text-3xl)',
            'trust-icon-margin' => 'var(--space-lg)',
            'trust-icon-primary-color' => 'var(--color-primary)',
            'trust-icon-secondary-color' => 'var(--color-secondary)',
            'trust-icon-accent-color' => 'var(--color-accent)',

            // Grid Layout
            'trust-grid-gap' => 'var(--space-xl)',
            'trust-grid-columns-mobile' => '1',
            'trust-grid-columns-tablet' => '2',
            'trust-grid-columns-desktop' => '4',

            // Hover Effects
            'trust-transition' => 'var(--transition-base)',
            'trust-hover-transform' => 'translateY(-4px)'
        ];
    }

    /**
     * Get component default configuration
     *
     * @return array Default configuration
     */
    public function get_defaults() {
        return [
            'section_title' => 'Why Choose PreetiDreams',
            'section_subtitle' => 'Experience the difference of medical artistry combined with luxury care',
            'indicators' => array_keys($this->trust_indicators),
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

        // Add schema markup for organization credentials
        add_action('wp_head', [$this, 'add_trust_schema_markup']);
    }

    /**
     * Add structured data for organization credentials
     */
    public function add_trust_schema_markup() {
        if (!is_front_page()) {
            return;
        }

        $schema_data = [
            '@context' => 'https://schema.org',
            '@type' => 'MedicalOrganization',
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'medicalSpecialty' => 'Cosmetic Surgery',
            'hasCredential' => [
                [
                    '@type' => 'EducationalOccupationalCredential',
                    'name' => 'Board Certification',
                    'description' => 'Expert medical professionals with advanced training in aesthetic medicine and patient safety.',
                    'credentialCategory' => 'Professional Certification'
                ]
            ],
            'award' => [
                [
                    '@type' => 'Award',
                    'name' => 'Excellence in Medical Aesthetics',
                    'description' => 'Recognized excellence in medical aesthetics with industry awards and patient satisfaction.'
                ]
            ],
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => '4.9',
                'reviewCount' => '2000',
                'bestRating' => '5'
            ]
        ];

        echo '<script type="application/ld+json">' . wp_json_encode($schema_data) . '</script>';
    }

    /**
     * Get trust indicators data (for external access)
     *
     * @return array Trust indicators data
     */
    public function get_trust_indicators() {
        return $this->trust_indicators;
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
            'trust-indicators-interactions',
            get_template_directory_uri() . '/assets/js/components/trust-indicators-interactions.js',
            ['jquery'],
            $this->version,
            true
        );

        // Localize script for interactions
        wp_localize_script('trust-indicators-interactions', 'trustIndicatorsData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('trust_indicators_nonce'),
            'indicators' => $this->trust_indicators
        ]);
    }
}

// Register the component
if (class_exists('ComponentRegistry')) {
    ComponentRegistry::register('trust-indicators', 'TrustIndicatorsComponent', [
        'priority' => 10,
        'cacheable' => true,
        'lazy_load' => false,
        'accessibility_required' => true
    ]);
}
