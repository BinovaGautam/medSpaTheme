<?php
/**
 * Component Factory
 *
 * Utility functions for component creation and rendering
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
 * Component Factory Class
 *
 * Provides simplified methods for component creation, registration,
 * and rendering with performance optimization and error handling.
 */
class ComponentFactory {

    /**
     * Component creation cache
     * @var array
     */
    private static $creation_cache = [];

    /**
     * Factory performance metrics
     * @var array
     */
    private static $factory_metrics = [];

    /**
     * Create and render a component instance
     *
     * @param string $name Component name
     * @param array $args Component arguments
     * @return string HTML output
     */
    public static function create($name, $args = []) {
        $start_time = microtime(true);

        try {
            // Check if ComponentRegistry is available
            if (!class_exists('ComponentRegistry')) {
                error_log('ComponentFactory: ComponentRegistry not found');
                return self::render_error($name, 'ComponentRegistry not available');
            }

            // Use ComponentRegistry to render
            $output = ComponentRegistry::render($name, $args);

            // Track factory performance
            self::track_factory_performance($name, microtime(true) - $start_time);

            return $output;

        } catch (Exception $e) {
            error_log("ComponentFactory: Error creating component '{$name}': " . $e->getMessage());
            return self::render_error($name, $e->getMessage());
        }
    }

    /**
     * Quick render function (echoes output directly)
     *
     * @param string $name Component name
     * @param array $args Component arguments
     * @return void
     */
    public static function component($name, $args = []) {
        echo self::create($name, $args);
    }

    /**
     * Create multiple components in sequence
     *
     * @param array $components Array of component configurations
     * @return string Combined HTML output
     */
    public static function create_multiple($components) {
        $output = '';

        foreach ($components as $component) {
            if (!is_array($component) || !isset($component['name'])) {
                continue;
            }

            $name = $component['name'];
            $args = $component['args'] ?? [];
            $wrapper = $component['wrapper'] ?? null;

            $component_output = self::create($name, $args);

            if ($wrapper && !empty($component_output)) {
                $wrapper_class = $wrapper['class'] ?? 'component-wrapper';
                $wrapper_attrs = $wrapper['attrs'] ?? '';
                $component_output = "<div class=\"{$wrapper_class}\" {$wrapper_attrs}>\n{$component_output}\n</div>";
            }

            $output .= $component_output;
        }

        return $output;
    }

    /**
     * Register a new component with the registry
     *
     * @param string $name Component name
     * @param string $class Component class
     * @param array $config Component configuration
     * @return bool True on success, false on failure
     */
    public static function register($name, $class, $config = []) {
        if (!class_exists('ComponentRegistry')) {
            error_log('ComponentFactory: ComponentRegistry not found for registration');
            return false;
        }

        return ComponentRegistry::register($name, $class, $config);
    }

    /**
     * Create a component with caching support
     *
     * @param string $name Component name
     * @param array $args Component arguments
     * @param int $cache_duration Cache duration in seconds (default: 1 hour)
     * @return string HTML output
     */
    public static function create_cached($name, $args = [], $cache_duration = HOUR_IN_SECONDS) {
        $cache_key = 'component_' . $name . '_' . md5(serialize($args));

        // Try to get from cache
        $cached_output = get_transient($cache_key);
        if ($cached_output !== false) {
            return $cached_output;
        }

        // Create component
        $output = self::create($name, $args);

        // Cache the output if not empty
        if (!empty($output)) {
            set_transient($cache_key, $output, $cache_duration);
        }

        return $output;
    }

    /**
     * Create a component with lazy loading support
     *
     * @param string $name Component name
     * @param array $args Component arguments
     * @param array $lazy_config Lazy loading configuration
     * @return string HTML output with lazy loading wrapper
     */
    public static function create_lazy($name, $args = [], $lazy_config = []) {
        $lazy_id = 'lazy-component-' . uniqid();
        $placeholder = $lazy_config['placeholder'] ?? '<div class="component-loading">Loading...</div>';

        // Create lazy loading wrapper
        $lazy_wrapper = "<div id=\"{$lazy_id}\" class=\"lazy-component\" data-component=\"{$name}\" data-args=\"" .
                       esc_attr(json_encode($args)) . "\">\n{$placeholder}\n</div>";

        // Add lazy loading script
        add_action('wp_footer', function() use ($lazy_id, $name, $args) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            var element = entry.target;
                            var componentName = element.dataset.component;
                            var componentArgs = JSON.parse(element.dataset.args || '{}');

                            // Load component via AJAX
                            var xhr = new XMLHttpRequest();
                            xhr.open('POST', '" . admin_url('admin-ajax.php') . "');
                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                            xhr.onload = function() {
                                if (xhr.status === 200) {
                                    element.innerHTML = xhr.responseText;
                                }
                            };
                            xhr.send('action=load_lazy_component&component=' + encodeURIComponent(componentName) + '&args=' + encodeURIComponent(JSON.stringify(componentArgs)));

                            observer.unobserve(element);
                        }
                    });
                });

                var element = document.getElementById('{$lazy_id}');
                if (element) {
                    observer.observe(element);
                }
            });
            </script>";
        });

        return $lazy_wrapper;
    }

    /**
     * Create a component with conditional rendering
     *
     * @param string $name Component name
     * @param array $args Component arguments
     * @param callable $condition Condition function that returns boolean
     * @param string $fallback Fallback content if condition fails
     * @return string HTML output
     */
    public static function create_conditional($name, $args = [], $condition = null, $fallback = '') {
        // If no condition provided, always render
        if (!$condition || !is_callable($condition)) {
            return self::create($name, $args);
        }

        try {
            $should_render = call_user_func($condition, $name, $args);

            if ($should_render) {
                return self::create($name, $args);
            } else {
                return $fallback;
            }
        } catch (Exception $e) {
            error_log("ComponentFactory: Error in conditional rendering for '{$name}': " . $e->getMessage());
            return $fallback;
        }
    }

    /**
     * Create a component with A/B testing support
     *
     * @param string $name Component name
     * @param array $variant_a Arguments for variant A
     * @param array $variant_b Arguments for variant B
     * @param float $split_ratio Percentage for variant A (0.0 to 1.0)
     * @return string HTML output
     */
    public static function create_ab_test($name, $variant_a = [], $variant_b = [], $split_ratio = 0.5) {
        // Generate or get user identifier for consistent experience
        $user_id = get_current_user_id();
        if (!$user_id) {
            // Use session or cookie for anonymous users
            if (!session_id()) {
                session_start();
            }
            $user_id = $_SESSION['anonymous_id'] ?? ($_SESSION['anonymous_id'] = uniqid());
        }

        // Determine variant based on user ID hash
        $hash = md5($name . $user_id);
        $hash_value = hexdec(substr($hash, 0, 8)) / 0xffffffff;

        $use_variant_a = $hash_value < $split_ratio;
        $variant = $use_variant_a ? 'a' : 'b';
        $args = $use_variant_a ? $variant_a : $variant_b;

        // Track A/B test
        do_action('component_ab_test', $name, $variant, $user_id);

        return self::create($name, $args);
    }

    /**
     * Create component with accessibility enhancements
     *
     * @param string $name Component name
     * @param array $args Component arguments
     * @param array $accessibility_config Accessibility configuration
     * @return string HTML output with accessibility enhancements
     */
    public static function create_accessible($name, $args = [], $accessibility_config = []) {
        // Merge accessibility configuration
        $args = array_merge($args, [
            'accessibility' => array_merge([
                'aria_labels' => true,
                'keyboard_navigation' => true,
                'screen_reader_support' => true,
                'high_contrast_support' => true,
                'focus_indicators' => true
            ], $accessibility_config)
        ]);

        $output = self::create($name, $args);

        // Add accessibility stylesheet if needed
        if (!empty($accessibility_config)) {
            wp_enqueue_style('component-accessibility',
                get_template_directory_uri() . '/assets/css/component-accessibility.css',
                [], '1.0.0'
            );
        }

        return $output;
    }

    /**
     * Track factory performance metrics
     *
     * @param string $name Component name
     * @param float $execution_time Execution time in seconds
     */
    private static function track_factory_performance($name, $execution_time) {
        if (!isset(self::$factory_metrics[$name])) {
            self::$factory_metrics[$name] = [
                'total_creations' => 0,
                'total_time' => 0,
                'max_time' => 0,
                'min_time' => PHP_FLOAT_MAX
            ];
        }

        $metrics = &self::$factory_metrics[$name];
        $metrics['total_creations']++;
        $metrics['total_time'] += $execution_time;
        $metrics['max_time'] = max($metrics['max_time'], $execution_time);
        $metrics['min_time'] = min($metrics['min_time'], $execution_time);
    }

    /**
     * Get factory performance metrics
     *
     * @return array Performance metrics
     */
    public static function get_factory_metrics() {
        return self::$factory_metrics;
    }

    /**
     * Render error message for failed component creation
     *
     * @param string $name Component name
     * @param string $error Error message
     * @return string Error HTML
     */
    private static function render_error($name, $error) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            return "<!-- ComponentFactory Error: Component '{$name}' failed - {$error} -->";
        }

        return '';
    }

    /**
     * Clear component creation cache
     *
     * @param string $pattern Cache key pattern (optional)
     */
    public static function clear_cache($pattern = null) {
        if ($pattern) {
            // Clear specific pattern
            global $wpdb;
            $wpdb->query($wpdb->prepare(
                "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
                '_transient_component_' . $pattern . '%'
            ));
        } else {
            // Clear all component cache
            global $wpdb;
            $wpdb->query(
                "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_component_%'"
            );
        }
    }

    /**
     * Batch create components with performance optimization
     *
     * @param array $components Array of component configurations
     * @param array $options Batch creation options
     * @return array Array of rendered components
     */
    public static function batch_create($components, $options = []) {
        $start_time = microtime(true);
        $results = [];

        $use_cache = $options['use_cache'] ?? true;
        $parallel_processing = $options['parallel_processing'] ?? false;

        foreach ($components as $index => $component) {
            if (!isset($component['name'])) {
                continue;
            }

            $name = $component['name'];
            $args = $component['args'] ?? [];

            if ($use_cache) {
                $results[$index] = self::create_cached($name, $args);
            } else {
                $results[$index] = self::create($name, $args);
            }
        }

        // Track batch performance
        $total_time = microtime(true) - $start_time;
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log("ComponentFactory: Batch created " . count($components) . " components in {$total_time}s");
        }

        return $results;
    }
}

// Global helper functions for easy component usage
if (!function_exists('render_component')) {
    /**
     * Render a component and return HTML
     *
     * @param string $name Component name
     * @param array $args Component arguments
     * @return string HTML output
     */
    function render_component($name, $args = []) {
        return ComponentFactory::create($name, $args);
    }
}

if (!function_exists('component')) {
    /**
     * Render a component directly (echo output)
     *
     * @param string $name Component name
     * @param array $args Component arguments
     * @return void
     */
    function component($name, $args = []) {
        ComponentFactory::component($name, $args);
    }
}

if (!function_exists('cached_component')) {
    /**
     * Render a cached component
     *
     * @param string $name Component name
     * @param array $args Component arguments
     * @param int $cache_duration Cache duration in seconds
     * @return string HTML output
     */
    function cached_component($name, $args = [], $cache_duration = HOUR_IN_SECONDS) {
        return ComponentFactory::create_cached($name, $args, $cache_duration);
    }
}

if (!function_exists('lazy_component')) {
    /**
     * Render a lazy-loaded component
     *
     * @param string $name Component name
     * @param array $args Component arguments
     * @param array $lazy_config Lazy loading configuration
     * @return string HTML output with lazy loading
     */
    function lazy_component($name, $args = [], $lazy_config = []) {
        return ComponentFactory::create_lazy($name, $args, $lazy_config);
    }
}

if (!function_exists('conditional_component')) {
    /**
     * Render a component conditionally
     *
     * @param string $name Component name
     * @param array $args Component arguments
     * @param callable $condition Condition function
     * @param string $fallback Fallback content
     * @return string HTML output
     */
    function conditional_component($name, $args = [], $condition = null, $fallback = '') {
        return ComponentFactory::create_conditional($name, $args, $condition, $fallback);
    }
}

// AJAX handler for lazy loading components
add_action('wp_ajax_load_lazy_component', 'handle_lazy_component_load');
add_action('wp_ajax_nopriv_load_lazy_component', 'handle_lazy_component_load');

function handle_lazy_component_load() {
    $component_name = $_POST['component'] ?? '';
    $component_args = json_decode($_POST['args'] ?? '{}', true);

    if (empty($component_name)) {
        wp_die('Invalid component name');
    }

    // Security check - ensure component is registered
    if (!ComponentRegistry::is_registered($component_name)) {
        wp_die('Component not found');
    }

    echo ComponentFactory::create($component_name, $component_args);
    wp_die();
}
