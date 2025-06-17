<?php
/**
 * Component Registry System
 *
 * Manages component registration and rendering for the MedSpa theme
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
 * Component Registry Class
 *
 * Central registry for all theme components with automatic
 * registration, validation, and rendering capabilities.
 */
class ComponentRegistry {

    /**
     * Registered components
     * @var array
     */
    private static $components = [];

    /**
     * Component instances cache
     * @var array
     */
    private static $instances = [];

    /**
     * Component performance metrics
     * @var array
     */
    private static $performance_metrics = [];

    /**
     * Registry initialization status
     * @var bool
     */
    private static $initialized = false;

    /**
     * Initialize the component registry
     */
    public static function init() {
        if (self::$initialized) {
            return;
        }

        // Load base component class
        self::load_base_component();

        // Setup hooks
        add_action('init', [__CLASS__, 'register_core_components']);
        add_action('wp_footer', [__CLASS__, 'output_performance_metrics']);

        // Customizer integration
        add_action('customize_register', [__CLASS__, 'register_component_customizer_controls']);

        self::$initialized = true;
    }

    /**
     * Load the base component class
     */
    private static function load_base_component() {
        $base_component_path = get_template_directory() . '/inc/components/base-component.php';

        if (file_exists($base_component_path)) {
            require_once $base_component_path;
        } else {
            wp_die('BaseComponent class not found. Please ensure base-component.php exists.');
        }
    }

    /**
     * Register a component in the registry
     *
     * @param string $name Component name (unique identifier)
     * @param string $class Component class name
     * @param array $config Component configuration
     * @return bool True on success, false on failure
     */
    public static function register($name, $class, $config = []) {
        // Validation checks
        if (empty($name) || empty($class)) {
            error_log("ComponentRegistry: Component name and class are required");
            return false;
        }

        if (isset(self::$components[$name])) {
            error_log("ComponentRegistry: Component '{$name}' is already registered");
            return false;
        }

        if (!class_exists($class)) {
            error_log("ComponentRegistry: Component class '{$class}' does not exist");
            return false;
        }

        if (!is_subclass_of($class, 'BaseComponent')) {
            error_log("ComponentRegistry: Component class '{$class}' must extend BaseComponent");
            return false;
        }

        // Register the component
        self::$components[$name] = [
            'class' => $class,
            'config' => wp_parse_args($config, [
                'priority' => 10,
                'cacheable' => true,
                'lazy_load' => false,
                'accessibility_required' => true
            ]),
            'registered_at' => current_time('timestamp'),
            'registration_source' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0]['file'] ?? 'unknown'
        ];

        // Auto-generate WordPress Customizer controls
        self::register_customizer_controls($name, $class);

        // Setup design token inheritance
        self::setup_token_inheritance($name, $class);

        // Log successful registration
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log("ComponentRegistry: Successfully registered component '{$name}' with class '{$class}'");
        }

        return true;
    }

    /**
     * Render a component by name
     *
     * @param string $name Component name
     * @param array $args Component arguments
     * @return string HTML output or empty string on failure
     */
    public static function render($name, $args = []) {
        // Performance tracking start
        $start_time = microtime(true);

        // Check if component is registered
        if (!isset(self::$components[$name])) {
            error_log("ComponentRegistry: Component '{$name}' not found");
            return self::render_fallback($name, $args);
        }

        try {
            // Get component instance
            $instance = self::get_instance($name, $args);

            if (!$instance) {
                return self::render_fallback($name, $args);
            }

            // Render the component
            $output = $instance->render($args);

            // Track performance
            $render_time = microtime(true) - $start_time;
            self::track_performance($name, $render_time);

            // Validate performance requirement (<100ms)
            if ($render_time > 0.1) {
                error_log("ComponentRegistry: Component '{$name}' render time {$render_time}s exceeds 100ms requirement");
            }

            return $output;

        } catch (Exception $e) {
            error_log("ComponentRegistry: Error rendering component '{$name}': " . $e->getMessage());
            return self::render_fallback($name, $args);
        }
    }

    /**
     * Get component instance (with caching)
     *
     * @param string $name Component name
     * @param array $args Component arguments
     * @return BaseComponent|null Component instance or null on failure
     */
    private static function get_instance($name, $args = []) {
        $component_config = self::$components[$name];

        // Generate cache key
        $cache_key = $name . '_' . md5(serialize(array_merge($component_config['config'], $args)));

        // Return cached instance if available and cacheable
        if ($component_config['config']['cacheable'] && isset(self::$instances[$cache_key])) {
            return self::$instances[$cache_key];
        }

        try {
            $class = $component_config['class'];
            $merged_config = array_merge($component_config['config'], $args);

            // Create new instance
            $instance = new $class($merged_config);

            // Cache instance if cacheable
            if ($component_config['config']['cacheable']) {
                self::$instances[$cache_key] = $instance;
            }

            return $instance;

        } catch (Exception $e) {
            error_log("ComponentRegistry: Failed to create instance of '{$name}': " . $e->getMessage());
            return null;
        }
    }

    /**
     * Render fallback content for failed components
     *
     * @param string $name Component name
     * @param array $args Component arguments
     * @return string Fallback HTML
     */
    private static function render_fallback($name, $args) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            return "<!-- Component '{$name}' failed to render -->";
        }

        // Return empty string in production
        return '';
    }

    /**
     * Register WordPress Customizer controls for a component
     *
     * @param string $name Component name
     * @param string $class Component class
     */
    private static function register_customizer_controls($name, $class) {
        if (!method_exists($class, 'get_customizer_controls')) {
            return;
        }

        add_action('customize_register', function($wp_customize) use ($name, $class) {
            // Create component section
            $section_id = $name . '_component';

            if (!$wp_customize->get_section($section_id)) {
                $wp_customize->add_section($section_id, [
                    'title' => ucfirst($name) . ' Component',
                    'panel' => 'design_tokens',
                    'priority' => 100,
                    'description' => "Customize the {$name} component appearance and behavior."
                ]);
            }

            // Get component controls
            try {
                // Create instance to call non-static method
                $instance = new $class();
                $controls = $instance->get_customizer_controls();

                foreach ($controls as $control_id => $control_config) {
                    self::register_single_customizer_control(
                        $wp_customize,
                        $name,
                        $control_id,
                        $control_config,
                        $section_id
                    );
                }
            } catch (Exception $e) {
                error_log("ComponentRegistry: Failed to register customizer controls for '{$name}': " . $e->getMessage());
            }
        });
    }

    /**
     * Register a single customizer control
     *
     * @param WP_Customize_Manager $wp_customize
     * @param string $component_name
     * @param string $control_id
     * @param array $control_config
     * @param string $section_id
     */
    private static function register_single_customizer_control($wp_customize, $component_name, $control_id, $control_config, $section_id) {
        $setting_id = $component_name . '_' . $control_id;

        // Add setting
        $wp_customize->add_setting($setting_id, [
            'default' => $control_config['default'] ?? '',
            'sanitize_callback' => $control_config['sanitize_callback'] ?? 'sanitize_text_field',
            'transport' => 'postMessage'
        ]);

        // Prepare control arguments
        $control_args = array_merge([
            'label' => $control_config['label'] ?? ucfirst(str_replace('_', ' ', $control_id)),
            'description' => $control_config['description'] ?? '',
            'section' => $section_id,
            'settings' => $setting_id
        ], $control_config['control_args'] ?? []);

        // Register control based on type
        $control_type = $control_config['type'] ?? 'text';

        switch ($control_type) {
            case 'color':
                $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $setting_id, $control_args));
                break;

            case 'range':
                $control_args['type'] = 'range';
                $control_args['input_attrs'] = $control_config['input_attrs'] ?? [];
                $wp_customize->add_control($setting_id, $control_args);
                break;

            case 'select':
                $control_args['type'] = 'select';
                $control_args['choices'] = $control_config['choices'] ?? [];
                $wp_customize->add_control($setting_id, $control_args);
                break;

            case 'checkbox':
                $control_args['type'] = 'checkbox';
                $wp_customize->add_control($setting_id, $control_args);
                break;

            case 'textarea':
                $control_args['type'] = 'textarea';
                $wp_customize->add_control($setting_id, $control_args);
                break;

            default:
                $control_args['type'] = $control_type;
                $wp_customize->add_control($setting_id, $control_args);
                break;
        }
    }

    /**
     * Output nested tokens as flattened CSS variables
     *
     * @param string $component_name Component name
     * @param string $parent_key Parent token key
     * @param array $tokens Nested token array
     * @param string $prefix Current prefix for nesting
     */
    private static function output_nested_tokens($component_name, $parent_key, $tokens, $prefix = '') {
        foreach ($tokens as $key => $value) {
            $full_key = $prefix ? "{$prefix}_{$key}" : "{$parent_key}_{$key}";

            if (is_array($value)) {
                // Recursively handle nested arrays
                self::output_nested_tokens($component_name, $parent_key, $value, $full_key);
            } else if (!empty($value)) {

                echo "  --{$component_name}-{$full_key}: {$value};\n";
            }
        }
    }

    /**
     * Setup design token inheritance for a component
     *
     * @param string $name Component name
     * @param string $class Component class
     */
    private static function setup_token_inheritance($name, $class) {
        // Connect to Universal Design Token System
        add_action('wp_head', function() use ($name, $class) {
            if (method_exists($class, 'get_default_tokens')) {
                try {
                    // Create instance to call non-static method
                    $instance = new $class();
                    $tokens = $instance->get_default_tokens();



                    if (!empty($tokens)) {
                        echo "<style id='{$name}-component-tokens'>\n";
                        echo ":root {\n";

                        // First pass: collect all nested token keys to avoid conflicts
                        $nested_token_keys = [];
                        foreach ($tokens as $token_name => $token_value) {
                            if (is_array($token_value)) {
                                $nested_token_keys[] = $token_name;
                            }
                        }

                        // Second pass: output tokens, prioritizing nested over flat
                        foreach ($tokens as $token_name => $token_value) {
                            if (!empty($token_value)) {
                                // Handle nested arrays by flattening them
                                if (is_array($token_value)) {
                                    self::output_nested_tokens($name, $token_name, $token_value);
                                } else {
                                    // Skip flat tokens that have nested equivalents
                                    $has_nested_equivalent = false;
                                    foreach ($nested_token_keys as $nested_key) {
                                        // Check if this flat token conflicts with any nested token structure
                                        // For example: 'padding' conflicts with 'card' -> 'padding'
                                        if (strpos($token_name, $nested_key) === 0 || strpos($nested_key, $token_name) === 0) {
                                            $has_nested_equivalent = true;
                                            break;
                                        }

                                        // Also check if the flat token name appears as a nested property
                                        // This handles cases like 'padding' vs 'card' -> 'padding'
                                        if (isset($tokens[$nested_key]) && is_array($tokens[$nested_key])) {
                                            if (array_key_exists($token_name, $tokens[$nested_key])) {
                                                $has_nested_equivalent = true;
                                                break;
                                            }

                                            // Check for semantic equivalents (e.g., 'background_color' vs 'background')
                                            $token_base = str_replace(['_color', '_size', '_weight'], '', $token_name);
                                            if (array_key_exists($token_base, $tokens[$nested_key])) {
                                                $has_nested_equivalent = true;
                                                break;
                                            }
                                        }
                                    }

                                    if (!$has_nested_equivalent) {
                                        echo "  --{$name}-{$token_name}: {$token_value};\n";
                                    }
                                }
                            }
                        }

                        echo "}\n</style>\n";
                    }
                } catch (Exception $e) {
                    error_log("ComponentRegistry: Failed to setup token inheritance for '{$name}': " . $e->getMessage());
                }
            }
        });
    }

    /**
     * Track component performance metrics
     *
     * @param string $name Component name
     * @param float $render_time Render time in seconds
     */
    private static function track_performance($name, $render_time) {
        if (!isset(self::$performance_metrics[$name])) {
            self::$performance_metrics[$name] = [
                'total_renders' => 0,
                'total_time' => 0,
                'max_time' => 0,
                'min_time' => PHP_FLOAT_MAX
            ];
        }

        $metrics = &self::$performance_metrics[$name];
        $metrics['total_renders']++;
        $metrics['total_time'] += $render_time;
        $metrics['max_time'] = max($metrics['max_time'], $render_time);
        $metrics['min_time'] = min($metrics['min_time'], $render_time);
    }

    /**
     * Get component performance metrics
     *
     * @param string $name Component name (optional)
     * @return array Performance metrics
     */
    public static function get_performance_metrics($name = null) {
        if ($name) {
            return self::$performance_metrics[$name] ?? [];
        }

        return self::$performance_metrics;
    }

    /**
     * Output performance metrics for debugging
     */
    public static function output_performance_metrics() {
        if (!defined('WP_DEBUG') || !WP_DEBUG || empty(self::$performance_metrics)) {
            return;
        }

        echo "<!-- Component Performance Metrics\n";
        foreach (self::$performance_metrics as $name => $metrics) {
            $avg_time = $metrics['total_time'] / $metrics['total_renders'];
            echo "Component: {$name}\n";
            echo "  Renders: {$metrics['total_renders']}\n";
            echo "  Avg Time: " . number_format($avg_time * 1000, 2) . "ms\n";
            echo "  Max Time: " . number_format($metrics['max_time'] * 1000, 2) . "ms\n";
            echo "  Min Time: " . number_format($metrics['min_time'] * 1000, 2) . "ms\n";
            echo "\n";
        }
        echo "-->\n";
    }

    /**
     * Register core theme components
     */
    public static function register_core_components() {
        // Load component files
        $component_dir = get_template_directory() . '/inc/components/';

        // Button Component (already implemented)
        if (file_exists($component_dir . 'button-component.php')) {
            require_once $component_dir . 'button-component.php';
            self::register('button', 'ButtonComponent', [
                'priority' => 10,
                'cacheable' => true,
                'lazy_load' => false,
                'accessibility_required' => true
            ]);
        }

        // Card Components (T6.4 Implementation)
        if (file_exists($component_dir . 'card-component.php')) {
            require_once $component_dir . 'card-component.php';
            self::register('card', 'CardComponent', [
                'priority' => 20,
                'cacheable' => true,
                'lazy_load' => false,
                'accessibility_required' => true
            ]);
        }

        if (file_exists($component_dir . 'treatment-card.php')) {
            require_once $component_dir . 'treatment-card.php';
            self::register('treatment-card', 'TreatmentCard', [
                'priority' => 21,
                'cacheable' => true,
                'lazy_load' => false,
                'accessibility_required' => true
            ]);
        }

        if (file_exists($component_dir . 'testimonial-card.php')) {
            require_once $component_dir . 'testimonial-card.php';
            self::register('testimonial-card', 'TestimonialCard', [
                'priority' => 22,
                'cacheable' => true,
                'lazy_load' => false,
                'accessibility_required' => true
            ]);
        }

        if (file_exists($component_dir . 'feature-card.php')) {
            require_once $component_dir . 'feature-card.php';
            self::register('feature-card', 'FeatureCard', [
                'priority' => 23,
                'cacheable' => true,
                'lazy_load' => false,
                'accessibility_required' => true
            ]);
        }

        // Form Components (T6.5 Implementation)
        if (file_exists($component_dir . 'form-component.php')) {
            require_once $component_dir . 'form-component.php';
            self::register('form', 'FormComponent', [
                'priority' => 30,
                'cacheable' => false, // Forms typically shouldn't be cached
                'lazy_load' => false,
                'accessibility_required' => true,
                'security_required' => true
            ]);
        }

        // Specialized Form Components
        if (file_exists($component_dir . 'forms/consultation-form.php')) {
            require_once $component_dir . 'forms/consultation-form.php';
            self::register('consultation-form', 'ConsultationForm', [
                'priority' => 31,
                'cacheable' => false,
                'lazy_load' => false,
                'accessibility_required' => true,
                'security_required' => true
            ]);
        }

        // Register Modal Components (T6.6 Modal/Dialog System Implementation)
        self::register('modal', 'ModalComponent', [
            'priority' => 40,
            'cacheable' => false, // Modals are dynamic and shouldn't be cached
            'lazy_load' => true,
            'accessibility_required' => true,
            'javascript_required' => true,
            'css_dependencies' => ['button', 'form'], // Dependencies on existing components
            'js_dependencies' => [],
            'performance_critical' => true,
            'security_level' => 'medium'
        ]);

        // Register Specialized Modal Components
        self::register('booking-modal', 'BookingModal', [
            'priority' => 45,
            'cacheable' => false,
            'lazy_load' => true,
            'accessibility_required' => true,
            'javascript_required' => true,
            'css_dependencies' => ['modal', 'form'],
            'js_dependencies' => ['modal'],
            'performance_critical' => true,
            'security_level' => 'high', // Booking forms require high security
            'requires_nonce' => true,
            'form_component' => true
        ]);

        self::register('confirmation-modal', 'ConfirmationModal', [
            'priority' => 42,
            'cacheable' => false,
            'lazy_load' => true,
            'accessibility_required' => true,
            'javascript_required' => true,
            'css_dependencies' => ['modal'],
            'js_dependencies' => ['modal'],
            'performance_critical' => false,
            'security_level' => 'medium',
            'extends' => 'modal'
        ]);

        self::register('gallery-modal', 'GalleryModal', [
            'priority' => 43,
            'cacheable' => false,
            'lazy_load' => true,
            'accessibility_required' => true,
            'javascript_required' => true,
            'css_dependencies' => ['modal'],
            'js_dependencies' => ['modal'],
            'performance_critical' => true,
            'security_level' => 'low',
            'extends' => 'modal'
        ]);

        self::register('treatment-info-modal', 'TreatmentInfoModal', [
            'priority' => 44,
            'cacheable' => true, // Treatment info can be cached
            'lazy_load' => true,
            'accessibility_required' => true,
            'javascript_required' => true,
            'css_dependencies' => ['modal'],
            'js_dependencies' => ['modal'],
            'performance_critical' => false,
            'security_level' => 'low',
            'extends' => 'modal'
        ]);

        // T6.8 Footer Components Implementation
        self::register('footer', 'FooterComponent', [
            'priority' => 70,
            'cacheable' => true,
            'lazy_load' => false,
            'accessibility_required' => true,
            'css_dependencies' => ['components/footer.css'],
            'js_dependencies' => ['components/footer.js'],
            'customizer_section' => 'footer_settings',
            'security_level' => 'low'
        ]);

        // Log registration completion
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('ComponentRegistry: Core components registration completed - ' . count(self::$components) . ' components registered');
        }
    }

    /**
     * Register component customizer controls (callback for customize_register action)
     *
     * @param WP_Customize_Manager $wp_customize
     */
    public static function register_component_customizer_controls($wp_customize) {
        // Create main components panel if it doesn't exist
        if (!$wp_customize->get_panel('design_tokens')) {
            $wp_customize->add_panel('design_tokens', [
                'title' => 'Design Tokens & Components',
                'description' => 'Customize design tokens and component appearance',
                'priority' => 30
            ]);
        }
    }

    /**
     * Get all registered components
     *
     * @return array Registered components
     */
    public static function get_registered_components() {
        return self::$components;
    }

    /**
     * Check if a component is registered
     *
     * @param string $name Component name
     * @return bool True if registered, false otherwise
     */
    public static function is_registered($name) {
        return isset(self::$components[$name]);
    }

    /**
     * Unregister a component
     *
     * @param string $name Component name
     * @return bool True on success, false on failure
     */
    public static function unregister($name) {
        if (!isset(self::$components[$name])) {
            return false;
        }

        unset(self::$components[$name]);

        // Clear related instances from cache
        foreach (self::$instances as $cache_key => $instance) {
            if (strpos($cache_key, $name . '_') === 0) {
                unset(self::$instances[$cache_key]);
            }
        }

        return true;
    }

    /**
     * Clear component instance cache
     *
     * @param string $name Component name (optional, clears all if not provided)
     */
    public static function clear_cache($name = null) {
        if ($name) {
            foreach (self::$instances as $cache_key => $instance) {
                if (strpos($cache_key, $name . '_') === 0) {
                    unset(self::$instances[$cache_key]);
                }
            }
        } else {
            self::$instances = [];
        }
    }
}

// Initialize the component registry
ComponentRegistry::init();
