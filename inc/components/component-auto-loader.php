<?php
/**
 * Component Auto-Loader System
 *
 * Automatically discovers and registers components in the theme
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
 * Component Auto-Loader Class
 *
 * Scans component directory and automatically registers valid components
 */
class ComponentAutoLoader {

    /**
     * Component directory path
     * @var string
     */
    private static $component_dir;

    /**
     * Discovered components cache
     * @var array
     */
    private static $discovered_components = [];

    /**
     * Auto-loader initialization status
     * @var bool
     */
    private static $initialized = false;

    /**
     * Component naming patterns
     * @var array
     */
    private static $naming_patterns = [
        'component' => '*-component.php',
        'class' => '*Component.php'
    ];

    /**
     * Initialize the auto-loader
     */
    public static function init() {
        if (self::$initialized) {
            return;
        }

        self::$component_dir = get_template_directory() . '/inc/components/';

        // Hook into WordPress initialization
        add_action('init', [__CLASS__, 'discover_and_register_components'], 5);
        add_action('admin_init', [__CLASS__, 'clear_discovery_cache_if_needed']);

        // Development hooks for cache clearing
        if (defined('WP_DEBUG') && WP_DEBUG) {
            add_action('switch_theme', [__CLASS__, 'clear_discovery_cache']);
            add_action('wp_loaded', [__CLASS__, 'validate_discovered_components']);
        }

        self::$initialized = true;
    }

    /**
     * Discover and register all components
     */
    public static function discover_and_register_components() {
        // Check if ComponentRegistry is available
        if (!class_exists('ComponentRegistry')) {
            error_log('ComponentAutoLoader: ComponentRegistry not found');
            return;
        }

        // Get cached discovery results
        $cache_key = 'component_auto_discovery_' . get_template_directory();
        $cached_components = get_transient($cache_key);

        if ($cached_components !== false && !self::should_refresh_cache()) {
            self::$discovered_components = $cached_components;
            self::register_cached_components();
            return;
        }

        // Perform fresh discovery
        self::perform_component_discovery();

        // Cache the results
        set_transient($cache_key, self::$discovered_components, HOUR_IN_SECONDS);

        // Register discovered components
        self::register_discovered_components();
    }

    /**
     * Perform component discovery scan
     */
    private static function perform_component_discovery() {
        self::$discovered_components = [];

        if (!is_dir(self::$component_dir)) {
            error_log('ComponentAutoLoader: Component directory not found: ' . self::$component_dir);
            return;
        }

        // Scan for component files
        $component_files = self::scan_component_files();

        foreach ($component_files as $file_path) {
            $component_info = self::analyze_component_file($file_path);

            if ($component_info && self::validate_component_class($component_info)) {
                self::$discovered_components[$component_info['name']] = $component_info;
            }
        }

        // Log discovery results
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('ComponentAutoLoader: Discovered ' . count(self::$discovered_components) . ' components');
        }
    }

    /**
     * Scan component directory for component files
     *
     * @return array Array of file paths
     */
    private static function scan_component_files() {
        $files = [];

        foreach (self::$naming_patterns as $pattern_name => $pattern) {
            $matched_files = glob(self::$component_dir . $pattern);
            $files = array_merge($files, $matched_files);
        }

        // Remove duplicates and sort
        $files = array_unique($files);
        sort($files);

        return $files;
    }

    /**
     * Analyze a component file to extract information
     *
     * @param string $file_path Path to component file
     * @return array|false Component information or false on failure
     */
    private static function analyze_component_file($file_path) {
        $file_name = basename($file_path, '.php');

        // Skip base component and system files
        if (in_array($file_name, ['base-component', 'component-registry', 'component-factory', 'component-auto-loader'])) {
            return false;
        }

        // Extract component name
        $component_name = self::extract_component_name($file_name);

        if (!$component_name) {
            return false;
        }

        // Read file contents
        $file_contents = file_get_contents($file_path);
        if (!$file_contents) {
            return false;
        }

        // Extract class name
        $class_name = self::extract_class_name($file_contents);

        if (!$class_name) {
            return false;
        }

        return [
            'name' => $component_name,
            'class' => $class_name,
            'file_path' => $file_path,
            'file_name' => $file_name,
            'file_modified' => filemtime($file_path),
            'file_size' => filesize($file_path)
        ];
    }

    /**
     * Extract component name from file name
     *
     * @param string $file_name File name without extension
     * @return string|false Component name or false on failure
     */
    private static function extract_component_name($file_name) {
        // Handle pattern: {name}-component
        if (preg_match('/^(.+)-component$/', $file_name, $matches)) {
            return sanitize_key($matches[1]);
        }

        // Handle pattern: {Name}Component
        if (preg_match('/^(.+)Component$/', $file_name, $matches)) {
            return sanitize_key(strtolower($matches[1]));
        }

        return false;
    }

    /**
     * Extract class name from file contents
     *
     * @param string $file_contents File contents
     * @return string|false Class name or false on failure
     */
    private static function extract_class_name($file_contents) {
        // Look for class declaration
        if (preg_match('/class\s+([A-Za-z_][A-Za-z0-9_]*)\s+extends\s+BaseComponent/i', $file_contents, $matches)) {
            return $matches[1];
        }

        return false;
    }

    /**
     * Validate component class
     *
     * @param array $component_info Component information
     * @return bool True if valid, false otherwise
     */
    private static function validate_component_class($component_info) {
        // Include the component file
        if (!file_exists($component_info['file_path'])) {
            return false;
        }

        require_once $component_info['file_path'];

        // Check if class exists
        if (!class_exists($component_info['class'])) {
            error_log("ComponentAutoLoader: Class {$component_info['class']} not found in {$component_info['file_path']}");
            return false;
        }

        // Check if class extends BaseComponent
        if (!is_subclass_of($component_info['class'], 'BaseComponent')) {
            error_log("ComponentAutoLoader: Class {$component_info['class']} does not extend BaseComponent");
            return false;
        }

        // Check required methods exist
        $required_methods = ['render', 'get_customizer_controls', 'get_default_tokens', 'get_defaults'];
        foreach ($required_methods as $method) {
            if (!method_exists($component_info['class'], $method)) {
                error_log("ComponentAutoLoader: Class {$component_info['class']} missing required method: {$method}");
                return false;
            }
        }

        return true;
    }

    /**
     * Register cached components
     */
    private static function register_cached_components() {
        foreach (self::$discovered_components as $component_name => $component_info) {
            // Include component file if not already included
            if (!class_exists($component_info['class'])) {
                require_once $component_info['file_path'];
            }

            // Register with ComponentRegistry
            ComponentRegistry::register($component_name, $component_info['class'], [
                'auto_discovered' => true,
                'file_path' => $component_info['file_path'],
                'discovery_time' => current_time('timestamp')
            ]);
        }
    }

    /**
     * Register discovered components
     */
    private static function register_discovered_components() {
        $registered_count = 0;

        foreach (self::$discovered_components as $component_name => $component_info) {
            $success = ComponentRegistry::register($component_name, $component_info['class'], [
                'auto_discovered' => true,
                'file_path' => $component_info['file_path'],
                'file_modified' => $component_info['file_modified'],
                'file_size' => $component_info['file_size'],
                'discovery_time' => current_time('timestamp')
            ]);

            if ($success) {
                $registered_count++;
            }
        }

        // Log registration results
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log("ComponentAutoLoader: Registered {$registered_count} of " . count(self::$discovered_components) . " discovered components");
        }
    }

    /**
     * Check if cache should be refreshed
     *
     * @return bool True if cache should be refreshed
     */
    private static function should_refresh_cache() {
        // Always refresh in debug mode
        if (defined('WP_DEBUG') && WP_DEBUG) {
            return true;
        }

        // Check if any component files have been modified
        $cache_key = 'component_file_timestamps_' . get_template_directory();
        $cached_timestamps = get_transient($cache_key);

        if ($cached_timestamps === false) {
            return true;
        }

        $current_timestamps = self::get_component_file_timestamps();

        return $cached_timestamps !== $current_timestamps;
    }

    /**
     * Get timestamps of all component files
     *
     * @return array Array of file => timestamp pairs
     */
    private static function get_component_file_timestamps() {
        $timestamps = [];
        $component_files = self::scan_component_files();

        foreach ($component_files as $file) {
            $timestamps[$file] = filemtime($file);
        }

        return $timestamps;
    }

    /**
     * Clear discovery cache if needed
     */
    public static function clear_discovery_cache_if_needed() {
        if (current_user_can('manage_options') && isset($_GET['clear_component_cache'])) {
            self::clear_discovery_cache();
            add_action('admin_notices', function() {
                echo '<div class="notice notice-success is-dismissible"><p>Component discovery cache cleared.</p></div>';
            });
        }
    }

    /**
     * Clear discovery cache
     */
    public static function clear_discovery_cache() {
        $cache_key = 'component_auto_discovery_' . get_template_directory();
        delete_transient($cache_key);

        $timestamp_cache_key = 'component_file_timestamps_' . get_template_directory();
        delete_transient($timestamp_cache_key);

        self::$discovered_components = [];

        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('ComponentAutoLoader: Discovery cache cleared');
        }
    }

    /**
     * Validate discovered components (development mode)
     */
    public static function validate_discovered_components() {
        if (!defined('WP_DEBUG') || !WP_DEBUG) {
            return;
        }

        foreach (self::$discovered_components as $component_name => $component_info) {
            if (!ComponentRegistry::is_registered($component_name)) {
                error_log("ComponentAutoLoader: Component '{$component_name}' discovered but not registered");
            }
        }
    }

    /**
     * Get discovered components
     *
     * @return array Discovered components
     */
    public static function get_discovered_components() {
        return self::$discovered_components;
    }

    /**
     * Get component discovery statistics
     *
     * @return array Discovery statistics
     */
    public static function get_discovery_stats() {
        $component_files = self::scan_component_files();

        return [
            'total_files_scanned' => count($component_files),
            'valid_components_found' => count(self::$discovered_components),
            'component_directory' => self::$component_dir,
            'naming_patterns' => self::$naming_patterns,
            'cache_active' => get_transient('component_auto_discovery_' . get_template_directory()) !== false
        ];
    }

    /**
     * Manual component registration (for components that can't be auto-discovered)
     *
     * @param string $name Component name
     * @param string $class Component class
     * @param string $file_path Component file path
     * @return bool True on success, false on failure
     */
    public static function register_manual_component($name, $class, $file_path) {
        if (!file_exists($file_path)) {
            error_log("ComponentAutoLoader: Manual component file not found: {$file_path}");
            return false;
        }

        require_once $file_path;

        $component_info = [
            'name' => $name,
            'class' => $class,
            'file_path' => $file_path,
            'file_name' => basename($file_path, '.php'),
            'file_modified' => filemtime($file_path),
            'file_size' => filesize($file_path),
            'manually_registered' => true
        ];

        if (self::validate_component_class($component_info)) {
            self::$discovered_components[$name] = $component_info;

            return ComponentRegistry::register($name, $class, [
                'auto_discovered' => false,
                'manually_registered' => true,
                'file_path' => $file_path,
                'discovery_time' => current_time('timestamp')
            ]);
        }

        return false;
    }
}

// Initialize the auto-loader
ComponentAutoLoader::init();
