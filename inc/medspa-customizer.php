<?php
/**
 * MedSpa WordPress Customizer Integration
 *
 * Industry-standard WordPress customizer implementation following enterprise patterns
 * Sprint 4 - Industry-Standard WordPress Customizer Architecture
 * Epic 1: WordPress Core Customizer Integration
 *
 * @package MedSpaTheme
 * @subpackage Customizer
 * @since 2.0.0
 * @author Sprint 4 Implementation Team
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * MedSpa WordPress Customizer Class
 *
 * Implements industry-standard WordPress customizer architecture following
 * patterns from Astra, GeneratePress, and OceanWP themes.
 */
class MedSpa_Customizer {

    /**
     * Singleton instance
     * @var MedSpa_Customizer|null
     */
    private static $instance = null;

    /**
     * CSS Generator instance
     * @var MedSpa_CSS_Generator|null
     */
    private $css_generator;

    /**
     * File Manager instance
     * @var MedSpa_File_Manager|null
     */
    private $file_manager;

    /**
     * Preview Handler instance
     * @var MedSpa_Preview_Handler|null
     */
    private $preview_handler;

    /**
     * Error Handler instance
     * @var MedSpa_Error_Handler|null
     */
    private $error_handler;

    /**
     * Migration Handler instance
     * @var MedSpa_Migration_Handler|null
     */
    private $migration_handler;

    /**
     * Available palettes cache
     * @var array|null
     */
    private $palette_cache = null;

    /**
     * Get singleton instance
     *
     * @return MedSpa_Customizer
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor - Initialize the customizer system
     */
    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
        $this->migrate_legacy_settings();
    }

    /**
     * Load required dependencies
     */
    private function load_dependencies() {
        // Load CSS Generator
        require_once get_template_directory() . '/inc/medspa-css-generator.php';
        $this->css_generator = new MedSpa_CSS_Generator();

        // Load File Manager
        require_once get_template_directory() . '/inc/medspa-file-manager.php';
        $this->file_manager = new MedSpa_File_Manager();

        // Load Preview Handler
        require_once get_template_directory() . '/inc/medspa-preview-handler.php';
        $this->preview_handler = new MedSpa_Preview_Handler();

        // Load Error Handler
        require_once get_template_directory() . '/inc/medspa-error-handler.php';
        $this->error_handler = new MedSpa_Error_Handler();

        // Load Migration Handler
        require_once get_template_directory() . '/inc/medspa-migration-handler.php';
        $this->migration_handler = new MedSpa_Migration_Handler();
    }

    /**
     * Initialize WordPress hooks following industry standards
     */
    private function init_hooks() {
        // WordPress Core Customizer Integration
        add_action('customize_register', array($this, 'register_customizer'), 10);
        add_action('customize_preview_init', array($this, 'preview_init'));
        add_action('customize_save_after', array($this, 'generate_css_file'));
        add_action('customize_controls_enqueue_scripts', array($this, 'enqueue_customizer_scripts'));

        // Frontend CSS Integration
        add_action('wp_enqueue_scripts', array($this, 'enqueue_dynamic_css'), 15);
        add_action('wp_head', array($this, 'output_critical_css'), 1);

        // Performance & Caching
        add_action('customize_save_after', array($this, 'flush_customizer_cache'));
        add_action('switch_theme', array($this, 'cleanup_theme_files'));
    }

    /**
     * Register WordPress Customizer components
     * Following industry-standard wp_customize_register pattern
     *
     * @param WP_Customize_Manager $wp_customize WordPress Customizer Manager
     */
    public function register_customizer($wp_customize) {
        // Remove old Visual Customizer panel if it exists
        if ($wp_customize->get_panel('visual_customizer')) {
            $wp_customize->remove_panel('visual_customizer');
        }

        // Create main Color Palette Panel
        $wp_customize->add_panel('medspa_color_palette', array(
            'title'       => __('Visual Color Palette', 'medspatheme'),
            'description' => __('Professional color palette management system with real-time preview', 'medspatheme'),
            'priority'    => 30,
            'capability'  => 'edit_theme_options',
        ));

        // Palette Selection Section
        $wp_customize->add_section('medspa_palette_selection', array(
            'title'    => __('Palette Selection', 'medspatheme'),
            'panel'    => 'medspa_color_palette',
            'priority' => 10,
        ));

        // Main Palette Setting with postMessage transport for real-time updates
        $wp_customize->add_setting('visual_color_palette', array(
            'default'           => 'professional-trust',
            'transport'         => 'postMessage',
            'sanitize_callback' => array($this, 'sanitize_palette_choice'),
            'type'              => 'theme_mod', // WordPress native storage
        ));

        // Palette Control with enhanced choices
        $wp_customize->add_control('visual_color_palette', array(
            'type'        => 'select',
            'section'     => 'medspa_palette_selection',
            'label'       => __('Choose Color Palette', 'medspatheme'),
            'description' => __('Select a professional color palette for your medical spa', 'medspatheme'),
            'choices'     => $this->get_palette_choices(),
        ));

        // Advanced Options Section
        $wp_customize->add_section('medspa_palette_advanced', array(
            'title'    => __('Advanced Options', 'medspatheme'),
            'panel'    => 'medspa_color_palette',
            'priority' => 20,
        ));

        // Enable Real-time Preview
        $wp_customize->add_setting('medspa_realtime_preview', array(
            'default'           => true,
            'transport'         => 'postMessage',
            'sanitize_callback' => 'wp_validate_boolean',
            'type'              => 'theme_mod',
        ));

        $wp_customize->add_control('medspa_realtime_preview', array(
            'type'        => 'checkbox',
            'section'     => 'medspa_palette_advanced',
            'label'       => __('Enable Real-time Preview', 'medspatheme'),
            'description' => __('See color changes instantly without refreshing the page', 'medspatheme'),
        ));

        // CSS Cache Management
        $wp_customize->add_setting('medspa_css_cache_bust', array(
            'default'           => false,
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_validate_boolean',
            'type'              => 'theme_mod',
        ));

        $wp_customize->add_control('medspa_css_cache_bust', array(
            'type'        => 'checkbox',
            'section'     => 'medspa_palette_advanced',
            'label'       => __('Clear CSS Cache', 'medspatheme'),
            'description' => __('Force regeneration of CSS files (use if colors are not updating)', 'medspatheme'),
        ));

        // Performance Mode
        $wp_customize->add_setting('medspa_performance_mode', array(
            'default'           => 'balanced',
            'transport'         => 'refresh',
            'sanitize_callback' => array($this, 'sanitize_performance_mode'),
            'type'              => 'theme_mod',
        ));

        $wp_customize->add_control('medspa_performance_mode', array(
            'type'        => 'select',
            'section'     => 'medspa_palette_advanced',
            'label'       => __('Performance Mode', 'medspatheme'),
            'description' => __('Choose between performance and visual quality', 'medspatheme'),
            'choices'     => array(
                'maximum'  => __('Maximum Performance', 'medspatheme'),
                'balanced' => __('Balanced (Recommended)', 'medspatheme'),
                'quality'  => __('Maximum Quality', 'medspatheme'),
            ),
        ));
    }

    /**
     * Get available palette choices for customizer
     *
     * @return array Palette choices array
     */
    public function get_palette_choices() {
        if (null !== $this->palette_cache) {
            return $this->palette_cache;
        }

        // Load from legacy color system manager if available
        if (class_exists('ColorSystemManager')) {
            $color_system = ColorSystemManager::get_instance();
            $palettes = $color_system->get_available_palettes();

            $choices = array();
            foreach ($palettes as $palette_id => $palette_data) {
                $name = isset($palette_data['name']) ? $palette_data['name'] : ucwords(str_replace('-', ' ', $palette_id));
                $choices[$palette_id] = $name;
            }

            $this->palette_cache = $choices;
            return $choices;
        }

        // Default palette choices if no color system available
        $this->palette_cache = array(
            'professional-trust' => __('Professional Trust', 'medspatheme'),
            'modern-clinical'    => __('Modern Clinical', 'medspatheme'),
            'rose-gold-elegance' => __('Rose Gold Elegance', 'medspatheme'),
            'sage-tranquility'   => __('Sage Tranquility', 'medspatheme'),
            'lavender-calm'      => __('Lavender Calm', 'medspatheme'),
            'warm-earth'         => __('Warm Earth', 'medspatheme'),
            'modern-monochrome'  => __('Modern Monochrome', 'medspatheme'),
        );

        return $this->palette_cache;
    }

    /**
     * Sanitize palette choice selection
     *
     * @param string $input Palette choice to sanitize
     * @return string Sanitized palette choice
     */
    public function sanitize_palette_choice($input) {
        $valid_palettes = array_keys($this->get_palette_choices());
        return in_array($input, $valid_palettes, true) ? $input : 'professional-trust';
    }

    /**
     * Sanitize performance mode selection
     *
     * @param string $input Performance mode to sanitize
     * @return string Sanitized performance mode
     */
    public function sanitize_performance_mode($input) {
        $valid_modes = array('maximum', 'balanced', 'quality');
        return in_array($input, $valid_modes, true) ? $input : 'balanced';
    }

    /**
     * Initialize customizer preview with real-time updates
     */
    public function preview_init() {
        // Enqueue preview JavaScript for real-time updates
        wp_enqueue_script(
            'medspa-customizer-preview',
            get_template_directory_uri() . '/assets/js/medspa-customizer-preview.js',
            array('jquery', 'customize-preview'),
            wp_get_theme()->get('Version'),
            true
        );

        // Localize palette data for JavaScript
        wp_localize_script('medspa-customizer-preview', 'medSpaCustomizer', array(
            'palettes'    => $this->get_all_palette_configs(),
            'previewUrl'  => home_url('/'),
            'nonce'       => wp_create_nonce('medspa_customizer_nonce'),
            'ajaxUrl'     => admin_url('admin-ajax.php'),
            'isPreview'   => true,
        ));
    }

    /**
     * Enqueue customizer control scripts
     */
    public function enqueue_customizer_scripts() {
        wp_enqueue_script(
            'medspa-customizer-controls',
            get_template_directory_uri() . '/assets/js/medspa-customizer-controls.js',
            array('jquery', 'customize-controls'),
            wp_get_theme()->get('Version'),
            true
        );

        // Localize for control panel
        wp_localize_script('medspa-customizer-controls', 'medSpaCustomizerControls', array(
            'palettes' => $this->get_all_palette_configs(),
            'nonce'    => wp_create_nonce('medspa_customizer_controls_nonce'),
        ));
    }

    /**
     * Generate CSS file after customizer save
     * Following GeneratePress file-based caching pattern
     *
     * @param WP_Customize_Manager $wp_customize Customizer instance
     */
    public function generate_css_file($wp_customize) {
        try {
            $current_palette = get_theme_mod('visual_color_palette', 'professional-trust');

            // Generate CSS file using industry-standard approach
            $css_generated = $this->css_generator->generate_palette_css($current_palette);

            if (!$css_generated) {
                $this->error_handler->log_css_generation_error(
                    'Failed to generate CSS file after customizer save',
                    array('palette' => $current_palette)
                );
            }

            // Clear cache if requested
            if (get_theme_mod('medspa_css_cache_bust', false)) {
                $this->flush_customizer_cache();
                set_theme_mod('medspa_css_cache_bust', false); // Reset flag
            }

        } catch (Exception $e) {
            $this->error_handler->log_css_generation_error(
                'Exception during CSS generation: ' . $e->getMessage(),
                array('palette' => $current_palette ?? 'unknown')
            );
        }
    }

    /**
     * Enqueue dynamic CSS using wp_enqueue_style
     * Following WordPress coding standards
     */
    public function enqueue_dynamic_css() {
        $css_url = $this->file_manager->get_css_file_url();

        if ($css_url) {
            // File-based CSS loading with proper versioning
            wp_enqueue_style(
                'medspa-dynamic-style',
                $css_url,
                array('medspatheme-style'), // Depend on main theme style
                null // Version handled by query string
            );
        } else {
            // Fallback to inline CSS with error logging
            $this->enqueue_inline_css_fallback();
        }
    }

    /**
     * Enqueue inline CSS fallback when file generation fails
     */
    private function enqueue_inline_css_fallback() {
        $current_palette = get_theme_mod('visual_color_palette', 'professional-trust');
        $css_content = $this->css_generator->generate_inline_css($current_palette);

        if ($css_content) {
            wp_add_inline_style('medspatheme-style', $css_content);
        }

        // Log fallback usage for debugging
        $this->error_handler->log_css_generation_error(
            'Using inline CSS fallback due to file generation failure',
            array('palette' => $current_palette)
        );
    }

    /**
     * Output critical CSS for above-the-fold content
     * Following performance optimization patterns
     */
    public function output_critical_css() {
        $current_palette = get_theme_mod('visual_color_palette', 'professional-trust');
        $critical_css = $this->css_generator->get_critical_css($current_palette);

        if ($critical_css) {
            echo "<style id='medspa-critical-css'>{$critical_css}</style>\n";
        }
    }

    /**
     * Get all palette configurations for JavaScript
     *
     * @return array All palette configurations
     */
    private function get_all_palette_configs() {
        // Load from legacy color system if available
        if (class_exists('ColorSystemManager')) {
            $color_system = ColorSystemManager::get_instance();
            return $color_system->get_available_palettes();
        }

        // Return default configurations
        return $this->get_default_palette_configs();
    }

    /**
     * Get default palette configurations
     *
     * @return array Default palette configurations
     */
    private function get_default_palette_configs() {
        return array(
            'professional-trust' => array(
                'name' => 'Professional Trust',
                'colors' => array(
                    'color-primary-navy'    => '#1B365D',
                    'color-brand-primary'   => '#87A96B',
                    'color-brand-accent'    => '#D4AF37',
                    'color-secondary-text'  => '#666666',
                    'color-muted-text'      => '#888888',
                ),
            ),
            'modern-clinical' => array(
                'name' => 'Modern Clinical',
                'colors' => array(
                    'color-primary-navy'    => '#2C3E50',
                    'color-brand-primary'   => '#3498DB',
                    'color-brand-accent'    => '#E74C3C',
                    'color-secondary-text'  => '#7F8C8D',
                    'color-muted-text'      => '#95A5A6',
                ),
            ),
        );
    }

    /**
     * Flush customizer cache
     */
    public function flush_customizer_cache() {
        // Clear WordPress object cache
        wp_cache_flush_group('medspa_customizer');

        // Clear file cache
        $this->file_manager->cleanup_old_css_files();

        // Reset palette cache
        $this->palette_cache = null;
    }

    /**
     * Cleanup theme files on theme switch
     */
    public function cleanup_theme_files() {
        $this->file_manager->cleanup_all_files();
    }

    /**
     * Migrate legacy settings to new system
     */
    private function migrate_legacy_settings() {
        $this->migration_handler->migrate_existing_settings();
    }

    /**
     * Validate environment for CSS generation
     *
     * @return array Environment validation results
     */
    public function validate_environment() {
        return $this->error_handler->validate_environment();
    }
}

// Initialize the MedSpa Customizer
function medspa_customizer_init() {
    return MedSpa_Customizer::get_instance();
}
add_action('after_setup_theme', 'medspa_customizer_init');

// Export for external access
global $medspa_customizer;
$medspa_customizer = MedSpa_Customizer::get_instance();
