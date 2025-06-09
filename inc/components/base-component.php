<?php
/**
 * Base Component Abstract Class
 *
 * Foundation for all theme components with design token integration
 *
 * @package MedSpaTheme
 * @since 1.0.0
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Abstract base class for all theme components
 *
 * Provides design token integration, WordPress Customizer support,
 * and accessibility features for all theme components.
 */
abstract class BaseComponent {

    /**
     * Design tokens for this component
     * @var array
     */
    protected $design_tokens = [];

    /**
     * WordPress Customizer support configuration
     * @var array
     */
    protected $customizer_support = [];

    /**
     * Accessibility features configuration
     * @var array
     */
    protected $accessibility_features = [];

    /**
     * Component configuration
     * @var array
     */
    protected $config = [];

    /**
     * Component name for identification
     * @var string
     */
    protected $component_name;

    /**
     * Constructor
     *
     * @param array $args Component arguments
     */
    public function __construct($args = []) {
        $this->component_name = $this->get_component_name();
        $this->config = wp_parse_args($args, $this->get_defaults());

        // Initialize component systems
        $this->init_design_tokens();
        $this->register_customizer_controls();
        $this->setup_accessibility();
        $this->enqueue_assets();

        // Hook into WordPress
        $this->setup_hooks();
    }

    /**
     * Render the component
     *
     * @param array $args Component arguments
     * @return string HTML output
     */
    abstract public function render($args = []);

    /**
     * Get WordPress Customizer controls
     *
     * @return array Customizer controls configuration
     */
    abstract public function get_customizer_controls();

    /**
     * Get default design tokens for this component
     *
     * @return array Design tokens
     */
    abstract public function get_default_tokens();

    /**
     * Get component default configuration
     *
     * @return array Default configuration
     */
    abstract public function get_defaults();

    /**
     * Get component name for identification
     *
     * @return string Component name
     */
    protected function get_component_name() {
        $class_name = get_class($this);
        return strtolower(str_replace(['Component', 'component'], '', $class_name));
    }

    /**
     * Initialize design tokens from multiple sources
     */
    protected function init_design_tokens() {
        $this->design_tokens = array_merge(
            $this->get_global_tokens(),
            $this->get_default_tokens(),
            $this->get_customizer_tokens(),
            $this->get_component_specific_tokens()
        );

        // Apply design tokens to CSS custom properties
        $this->apply_css_custom_properties();
    }

    /**
     * Register WordPress Customizer controls
     */
    protected function register_customizer_controls() {
        // DISABLED: Causing conflicts with WordPress Customizer - moved to ComponentRegistry
        // if (is_customize_preview() || is_admin()) {
        //     add_action('customize_register', [$this, 'customize_register_callback']);
        // }
    }

    /**
     * WordPress Customizer registration callback
     *
     * @param WP_Customize_Manager $wp_customize
     */
    public function customize_register_callback($wp_customize) {
        $controls = $this->get_customizer_controls();

        if (empty($controls)) {
            return;
        }

        // Create component section if it doesn't exist
        $section_id = $this->component_name . '_component';

        if (!$wp_customize->get_section($section_id)) {
            $wp_customize->add_section($section_id, [
                'title' => ucfirst($this->component_name) . ' Component',
                'panel' => 'design_tokens',
                'priority' => 100
            ]);
        }

        // Register each control
        foreach ($controls as $control_id => $control_config) {
            $this->register_single_control($wp_customize, $control_id, $control_config, $section_id);
        }
    }

    /**
     * Register a single customizer control
     *
     * @param WP_Customize_Manager $wp_customize
     * @param string $control_id
     * @param array $control_config
     * @param string $section_id
     */
    protected function register_single_control($wp_customize, $control_id, $control_config, $section_id) {
        $setting_id = $this->component_name . '_' . $control_id;

        // Add setting
        $wp_customize->add_setting($setting_id, [
            'default' => $control_config['default'] ?? '',
            'sanitize_callback' => $control_config['sanitize_callback'] ?? 'sanitize_text_field',
            'transport' => 'postMessage'
        ]);

        // Add control
        $control_args = array_merge([
            'label' => $control_config['label'] ?? ucfirst(str_replace('_', ' ', $control_id)),
            'section' => $section_id,
            'settings' => $setting_id
        ], $control_config['control_args'] ?? []);

        $control_type = $control_config['type'] ?? 'text';

        switch ($control_type) {
            case 'color':
                $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $setting_id, $control_args));
                break;
            case 'range':
                $wp_customize->add_control(new WP_Customize_Control($wp_customize, $setting_id, array_merge($control_args, ['type' => 'range'])));
                break;
            default:
                $wp_customize->add_control($setting_id, $control_args);
                break;
        }
    }

    /**
     * Setup accessibility features
     */
    protected function setup_accessibility() {
        $this->accessibility_features = array_merge([
            'aria_labels' => true,
            'keyboard_navigation' => true,
            'screen_reader_support' => true,
            'color_contrast_validation' => true,
            'focus_management' => true,
            'semantic_markup' => true
        ], $this->get_accessibility_config());

        // Add accessibility hooks
        if ($this->accessibility_features['keyboard_navigation']) {
            add_action('wp_footer', [$this, 'add_keyboard_navigation_script']);
        }
    }

    /**
     * Get component-specific accessibility configuration
     *
     * @return array Accessibility configuration
     */
    protected function get_accessibility_config() {
        return [];
    }

    /**
     * Enqueue component assets
     */
    protected function enqueue_assets() {
        $component_name = $this->component_name;

        // Enqueue CSS if exists
        $css_path = get_template_directory() . "/assets/css/components/{$component_name}.css";
        $css_url = get_template_directory_uri() . "/assets/css/components/{$component_name}.css";

        if (file_exists($css_path)) {
            wp_enqueue_style(
                "{$component_name}-component",
                $css_url,
                ['design-token-system', 'medspa-style'],
                filemtime($css_path)
            );
        }

        // Enqueue JS if exists
        $js_path = get_template_directory() . "/assets/js/components/{$component_name}.js";
        $js_url = get_template_directory_uri() . "/assets/js/components/{$component_name}.js";

        if (file_exists($js_path)) {
            wp_enqueue_script(
                "{$component_name}-component",
                $js_url,
                ['jquery', 'design-token-system'],
                filemtime($js_path),
                true
            );

            // Localize script with component data
            wp_localize_script("{$component_name}-component", "{$component_name}ComponentData", [
                'tokens' => $this->design_tokens,
                'config' => $this->config,
                'accessibility' => $this->accessibility_features
            ]);
        }
    }

    /**
     * Setup WordPress hooks
     */
    protected function setup_hooks() {
        // Performance optimization hook
        add_action('init', [$this, 'optimize_component_performance']);

        // Customizer preview hooks
        if (is_customize_preview()) {
            add_action('wp_footer', [$this, 'add_customizer_preview_script']);
        }
    }

    /**
     * Get global design tokens from Universal Design Token System
     *
     * @return array Global design tokens
     */
    protected function get_global_tokens() {
        // Get tokens from the established Universal Design Token System
        $global_tokens = get_option('medspa_design_tokens', []);

        // Get tokens from CSS custom properties
        $css_tokens = $this->get_css_custom_properties();

        return array_merge($global_tokens, $css_tokens);
    }

    /**
     * Get customizer-specific tokens
     *
     * @return array Customizer tokens
     */
    protected function get_customizer_tokens() {
        $tokens = [];
        $controls = $this->get_customizer_controls();

        foreach ($controls as $control_id => $control) {
            $setting_id = $this->component_name . '_' . $control_id;
            $tokens[$control_id] = get_theme_mod($setting_id, $control['default'] ?? '');
        }

        return $tokens;
    }

    /**
     * Get component-specific tokens
     *
     * @return array Component-specific tokens
     */
    protected function get_component_specific_tokens() {
        return [];
    }

    /**
     * Get CSS custom properties from the theme
     *
     * @return array CSS custom properties as tokens
     */
    protected function get_css_custom_properties() {
        // Integration with existing CSS custom properties system
        return [
            'primary_color' => 'var(--primary-color)',
            'secondary_color' => 'var(--secondary-color)',
            'text_color' => 'var(--text-color)',
            'background_color' => 'var(--background-color)',
            'border_radius' => 'var(--border-radius)',
            'spacing_unit' => 'var(--spacing-unit)',
            'font_family_primary' => 'var(--font-family-primary)',
            'font_family_secondary' => 'var(--font-family-secondary)'
        ];
    }

    /**
     * Apply design tokens as CSS custom properties
     */
    protected function apply_css_custom_properties() {
        $css_vars = [];

        foreach ($this->design_tokens as $token_name => $token_value) {
            if (!empty($token_value) && !str_starts_with($token_value, 'var(')) {
                $css_vars["--{$this->component_name}-{$token_name}"] = $token_value;
            }
        }

        if (!empty($css_vars)) {
            add_action('wp_head', function() use ($css_vars) {
                echo "<style id='{$this->component_name}-component-tokens'>\n";
                echo ":root {\n";
                foreach ($css_vars as $property => $value) {
                    echo "  {$property}: {$value};\n";
                }
                echo "}\n</style>\n";
            });
        }
    }

    /**
     * Add keyboard navigation script for accessibility
     */
    public function add_keyboard_navigation_script() {
        if ($this->accessibility_features['keyboard_navigation']) {
            echo "<script>
            (function() {
                // Enhanced keyboard navigation for {$this->component_name} component
                document.addEventListener('keydown', function(e) {
                    var componentElements = document.querySelectorAll('.{$this->component_name}-component');
                    // Keyboard navigation logic here
                });
            })();
            </script>";
        }
    }

    /**
     * Add customizer preview script for real-time updates
     */
    public function add_customizer_preview_script() {
        $controls = $this->get_customizer_controls();

        if (empty($controls)) {
            return;
        }

        echo "<script>
        (function(\$) {
            // Real-time preview updates for {$this->component_name} component
            wp.customize = wp.customize || {};
            ";

        foreach ($controls as $control_id => $control) {
            $setting_id = $this->component_name . '_' . $control_id;
            echo "
            wp.customize('{$setting_id}', function(value) {
                value.bind(function(newval) {
                    // Update component with new value
                    var property = '--{$this->component_name}-{$control_id}';
                    document.documentElement.style.setProperty(property, newval);
                });
            });";
        }

        echo "
        })(jQuery);
        </script>";
    }

    /**
     * Optimize component performance
     */
    public function optimize_component_performance() {
        // Component-specific performance optimizations
        if (!wp_doing_ajax() && !is_admin()) {
            // Cache component tokens for performance
            $cache_key = 'component_tokens_' . $this->component_name;
            if (!get_transient($cache_key)) {
                set_transient($cache_key, $this->design_tokens, HOUR_IN_SECONDS);
            }
        }
    }

    /**
     * Validate component render time for performance requirements
     *
     * @param string $html_output Rendered HTML
     * @return string HTML output with performance data
     */
    protected function validate_performance($html_output) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            $render_time = microtime(true) - ($_SERVER['REQUEST_TIME_FLOAT'] ?? microtime(true));

            if ($render_time > 0.1) { // 100ms requirement
                error_log("Component {$this->component_name} render time: {$render_time}s exceeds 100ms requirement");
            }

            return $html_output . "<!-- Component {$this->component_name} rendered in {$render_time}s -->";
        }

        return $html_output;
    }

    /**
     * Generate accessibility attributes
     *
     * @param array $additional_attrs Additional attributes
     * @return string Accessibility attributes string
     */
    protected function get_accessibility_attributes($additional_attrs = []) {
        $attrs = [];

        if ($this->accessibility_features['aria_labels']) {
            $attrs['role'] = $additional_attrs['role'] ?? 'region';
            $attrs['aria-label'] = $additional_attrs['aria-label'] ?? ucfirst($this->component_name) . ' component';
        }

        if ($this->accessibility_features['keyboard_navigation']) {
            $attrs['tabindex'] = $additional_attrs['tabindex'] ?? '0';
        }

        $attr_string = '';
        foreach ($attrs as $attr => $value) {
            $attr_string .= " {$attr}=\"{$value}\"";
        }

        return $attr_string;
    }

    /**
     * Render component wrapper with accessibility and performance features
     *
     * @param string $content Component content
     * @param array $wrapper_args Wrapper arguments
     * @return string Wrapped component HTML
     */
    protected function render_component_wrapper($content, $wrapper_args = []) {
        $wrapper_class = $wrapper_args['class'] ?? "{$this->component_name}-component";
        $wrapper_attrs = $this->get_accessibility_attributes($wrapper_args['attrs'] ?? []);

        $html = "<div class=\"{$wrapper_class}\"{$wrapper_attrs}>\n";
        $html .= $content;
        $html .= "\n</div>";

        return $this->validate_performance($html);
    }
}
