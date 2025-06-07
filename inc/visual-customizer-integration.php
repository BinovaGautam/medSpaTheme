<?php
/**
 * Visual Customizer WordPress Integration
 *
 * Integrates the Professional Visual Customizer with WordPress Customizer API
 * Sprint 2 - PVC-004: WordPress Admin Integration Panel
 *
 * @package MedSpaTheme
 * @subpackage VisualCustomizer
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Visual Customizer WordPress Integration Class
 */
class VisualCustomizerIntegration {

    /**
     * Singleton instance
     * @var VisualCustomizerIntegration|null
     */
    private static $instance = null;

    /**
     * Color System Manager instance
     * @var ColorSystemManager|null
     */
    private $color_system;

    /**
     * Get singleton instance
     * @return VisualCustomizerIntegration
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
        $this->load_dependencies();
    }

    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        // WordPress Customizer integration
        add_action('customize_register', array($this, 'register_customizer_components'));
        add_action('customize_preview_init', array($this, 'customize_preview_init'));
        add_action('customize_controls_enqueue_scripts', array($this, 'enqueue_customizer_scripts'));
        add_action('customize_controls_print_styles', array($this, 'customizer_styles'));

        // Frontend integration
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
        add_action('wp_head', array($this, 'output_custom_css'));

        // Admin integration
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        // Settings validation and sanitization
        add_filter('theme_mod_visual_customizer_active_palette', array($this, 'sanitize_palette_selection'));
        add_filter('theme_mod_visual_customizer_settings', array($this, 'sanitize_customizer_settings'));
    }

    /**
     * Load required dependencies
     */
    private function load_dependencies() {
        // Load Color System Manager if not already loaded
        if (!class_exists('ColorSystemManager')) {
            require_once get_template_directory() . '/inc/color-system-manager.php';
        }

        // Initialize color system
        $this->color_system = ColorSystemManager::get_instance();
    }

    /**
     * Register WordPress Customizer components
     *
     * @param WP_Customize_Manager $wp_customize WordPress Customizer Manager
     */
    public function register_customizer_components($wp_customize) {
        // Register main panel
        $wp_customize->add_panel('visual_customizer', array(
            'title'       => __('Visual Customizer', 'medspa-theme'),
            'description' => __('Professional color palette management', 'medspa-theme'),
            'priority'    => 30,
            'capability'  => 'edit_theme_options',
        ));

        // Register palette section
        $wp_customize->add_section('visual_customizer_palettes', array(
            'title'    => __('Color Palettes', 'medspa-theme'),
            'panel'    => 'visual_customizer',
            'priority' => 10,
        ));

        // Active palette setting and control
        $wp_customize->add_setting('visual_customizer_active_palette', array(
            'default'           => 'medical-clean',
            'sanitize_callback' => array($this, 'sanitize_palette_selection'),
            'transport'         => 'postMessage',
        ));

        $wp_customize->add_control('visual_customizer_active_palette', array(
            'label'       => __('Active Color Palette', 'medspa-theme'),
            'description' => __('Select the color palette for your site', 'medspa-theme'),
            'section'     => 'visual_customizer_palettes',
            'type'        => 'select',
            'choices'     => $this->get_available_palettes(),
        ));
    }

    /**
     * Get available color palettes
     *
     * @return array Available palettes
     */
    private function get_available_palettes() {
        if (!$this->color_system) {
            return array('medical-clean' => __('Medical Clean', 'medspa-theme'));
        }

        $palettes = $this->color_system->get_available_palettes();
        $choices = array();

        foreach ($palettes as $palette_id => $palette_data) {
            $name = isset($palette_data['name']) ? $palette_data['name'] : ucwords(str_replace('-', ' ', $palette_id));
            $choices[$palette_id] = $name;
        }

        return $choices;
    }

    /**
     * Sanitize palette selection
     *
     * @param string $palette_id Palette ID to sanitize
     * @return string Sanitized palette ID
     */
    public function sanitize_palette_selection($palette_id) {
        $available_palettes = array_keys($this->get_available_palettes());
        return in_array($palette_id, $available_palettes, true) ? $palette_id : 'medical-clean';
    }

    /**
     * Initialize customizer preview
     */
    public function customize_preview_init() {
        wp_enqueue_script(
            'visual-customizer-preview',
            get_template_directory_uri() . '/assets/js/wp-customizer-bridge.js',
            array('jquery', 'customize-preview'),
            wp_get_theme()->get('Version'),
            true
        );

        wp_localize_script('visual-customizer-preview', 'visualCustomizerPreview', array(
            'ajaxUrl'    => admin_url('admin-ajax.php'),
            'nonce'      => wp_create_nonce('visual_customizer_preview'),
            'palettes'   => $this->color_system ? $this->color_system->get_available_palettes() : array(),
        ));
    }

    /**
     * Enqueue customizer control scripts
     */
    public function enqueue_customizer_scripts() {
        wp_enqueue_script(
            'visual-customizer-controls',
            get_template_directory_uri() . '/assets/js/customizer-controls.js',
            array('jquery', 'customize-controls'),
            wp_get_theme()->get('Version'),
            true
        );
    }

    /**
     * Output customizer styles
     */
    public function customizer_styles() {
        ?>
        <style>
        .customize-control-visual-customizer {
            margin-bottom: 20px;
        }

        .visual-customizer-palette-preview {
            display: flex;
            gap: 5px;
            margin-top: 8px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 4px;
        }

        .visual-customizer-color-swatch {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 1px rgba(0,0,0,0.1);
        }

        .visual-customizer-accessibility-warning {
            padding: 8px 12px;
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            color: #856404;
            font-size: 12px;
            margin-top: 8px;
        }

        .visual-customizer-performance-indicator {
            display: inline-block;
            padding: 2px 6px;
            background: #28a745;
            color: white;
            border-radius: 3px;
            font-size: 10px;
            margin-left: 8px;
        }

        .visual-customizer-performance-indicator.warning {
            background: #ffc107;
            color: #212529;
        }

        .visual-customizer-performance-indicator.danger {
            background: #dc3545;
        }
        </style>
        <?php
    }

    /**
     * Enqueue frontend scripts
     */
    public function enqueue_frontend_scripts() {
        if (is_customize_preview()) {
            wp_enqueue_script(
                'visual-customizer-frontend',
                get_template_directory_uri() . '/assets/js/live-preview-system.js',
                array('jquery'),
                wp_get_theme()->get('Version'),
                true
            );
        }
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ('appearance_page_custom-header' === $hook || 'customize.php' === $hook) {
            wp_enqueue_script(
                'visual-customizer-admin',
                get_template_directory_uri() . '/assets/js/admin-integration.js',
                array('jquery'),
                wp_get_theme()->get('Version'),
                true
            );
        }
    }

    /**
     * Output custom CSS based on selected palette
     */
    public function output_custom_css() {
        $active_palette = get_theme_mod('visual_customizer_active_palette', 'medical-clean');

        if ($this->color_system) {
            $palette_css = $this->color_system->generate_css_for_palette($active_palette);
            if (!empty($palette_css)) {
                echo '<style id="visual-customizer-css">' . wp_strip_all_tags($palette_css) . '</style>';
            }
        }
    }

    /**
     * Sanitize customizer settings array
     *
     * @param mixed $settings Settings to sanitize
     * @return array Sanitized settings
     */
    public function sanitize_customizer_settings($settings) {
        if (!is_array($settings)) {
            return array();
        }

        $sanitized = array();

        foreach ($settings as $key => $value) {
            $sanitized_key = sanitize_key($key);

            if (is_array($value)) {
                $sanitized[$sanitized_key] = $this->sanitize_customizer_settings($value);
            } elseif (is_bool($value)) {
                $sanitized[$sanitized_key] = wp_validate_boolean($value);
            } else {
                $sanitized[$sanitized_key] = sanitize_text_field($value);
            }
        }

        return $sanitized;
    }
}

// Initialize the integration
function initialize_visual_customizer_integration() {
    return VisualCustomizerIntegration::get_instance();
}
add_action('after_setup_theme', 'initialize_visual_customizer_integration');
