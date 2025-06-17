<?php
/**
 * Semantic Token Bridge - JavaScript to PHP Integration
 *
 * T8.1 Semantic Token Bridge Implementation
 * Bridges JavaScript palette data from semantic-color-system.js to PHP backend
 * for dynamic CSS custom property generation and WordPress Customizer integration
 *
 * @package MedSpaTheme
 * @version 1.0.0
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 * @sprint SPRINT-8-EXTENSIBLE-SEMANTIC-INTEGRATION
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Semantic Token Bridge Class
 *
 * Bridges JavaScript semantic color system with PHP backend for:
 * - Dynamic CSS custom property generation
 * - WordPress Customizer integration
 * - Static CSS file generation for performance
 * - Real-time preview updates
 */
class SemanticTokenBridge {

    /**
     * Singleton instance
     * @var SemanticTokenBridge|null
     */
    private static $instance = null;

    /**
     * JavaScript palette data cache
     * @var array
     */
    private $js_palette_cache = array();

    /**
     * Current active palette ID
     * @var string
     */
    private $active_palette_id = 'professional-trust';

    /**
     * CSS generation cache
     * @var array
     */
    private $css_cache = array();

    /**
     * Performance metrics
     * @var array
     */
    private $performance_metrics = array();

    /**
     * Semantic role mapping
     * @var array
     */
    private $semantic_role_mapping = array(
        'primary' => 'color-brand-primary',
        'secondary' => 'color-brand-secondary',
        'surface' => 'color-surface-primary',
        'background' => 'color-surface-background',
        'accent' => 'color-accent-primary'
    );

    /**
     * Extended semantic token definitions
     * @var array
     */
    private $extended_semantic_tokens = array(
        // Text tokens
        'color-text-primary' => '#1e293b',     // Dark primary text
        'color-text-secondary' => '#64748b',   // Medium secondary text
        'color-text-muted' => '#94a3b8',       // Light muted text
        'color-text-inverse' => '#ffffff',     // White text for dark backgrounds

        // Border tokens
        'color-border-primary' => '#d1d5db',   // Primary border color
        'color-border-secondary' => '#e5e7eb', // Secondary border color
        'color-border-light' => '#f3f4f6',     // Light border color

        // Feedback tokens
        'color-feedback-success' => '#10b981', // Success green
        'color-feedback-warning' => '#f59e0b', // Warning orange
        'color-feedback-error' => '#ef4444',   // Error red
        'color-feedback-info' => '#3b82f6',    // Info blue

        // Interactive tokens
        'color-interactive-hover' => '#4f46e5', // Hover state
        'color-interactive-focus' => '#6366f1', // Focus state
        'color-interactive-active' => '#4338ca' // Active state
    );

    /**
     * Get singleton instance
     * @return SemanticTokenBridge
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor - Initialize the bridge
     */
    private function __construct() {
        $this->init_performance_tracking();
        $this->load_javascript_palette_data();
        $this->init_wordpress_hooks();
    }

    /**
     * Initialize performance tracking
     */
    private function init_performance_tracking() {
        $this->performance_metrics = array(
            'bridge_init_time' => microtime(true),
            'js_parse_time' => 0,
            'css_generation_time' => 0,
            'cache_hits' => 0,
            'cache_misses' => 0,
            'total_palettes_loaded' => 0
        );
    }

    /**
     * Load JavaScript palette data from semantic-color-system.js
     *
     * Parses the JavaScript file to extract SEMANTIC_PALETTES data
     * and converts it to PHP-compatible format
     */
    private function load_javascript_palette_data() {
        $start_time = microtime(true);

        // Path to the JavaScript semantic color system file
        $js_file_path = get_template_directory() . '/assets/js/semantic-color-system.js';

        if (!file_exists($js_file_path)) {
            error_log('SemanticTokenBridge: semantic-color-system.js not found at ' . $js_file_path);
            return false;
        }

        // Read and parse JavaScript file
        $js_content = file_get_contents($js_file_path);

        if ($js_content === false) {
            error_log('SemanticTokenBridge: Failed to read semantic-color-system.js');
            return false;
        }

        // Extract SEMANTIC_PALETTES object using regex
        $palette_data = $this->extract_palette_data_from_js($js_content);

        if ($palette_data) {
            $this->js_palette_cache = $palette_data;
            $this->performance_metrics['total_palettes_loaded'] = count($palette_data);
            $this->performance_metrics['js_parse_time'] = microtime(true) - $start_time;

            // Cache the parsed data for performance
            $this->cache_palette_data($palette_data);

            return true;
        }

        return false;
    }

    /**
     * Extract palette data from JavaScript content using regex
     *
     * @param string $js_content JavaScript file content
     * @return array|false Parsed palette data or false on failure
     */
    private function extract_palette_data_from_js($js_content) {
        // Remove comments and normalize whitespace
        $js_content = preg_replace('/\/\*[\s\S]*?\*\//', '', $js_content);
        $js_content = preg_replace('/\/\/.*$/m', '', $js_content);

        // Extract SEMANTIC_PALETTES object
        $pattern = '/const\s+SEMANTIC_PALETTES\s*=\s*\{([\s\S]*?)\};/';

        if (preg_match($pattern, $js_content, $matches)) {
            $palette_content = $matches[1];

            // Parse individual palettes
            return $this->parse_palette_objects($palette_content);
        }

        return false;
    }

    /**
     * Parse individual palette objects from JavaScript
     *
     * @param string $palette_content Palette object content
     * @return array Parsed palettes
     */
    private function parse_palette_objects($palette_content) {
        $palettes = array();

        // Extract individual palette definitions
        $pattern = '/\'([^\']+)\'\s*:\s*\{([\s\S]*?)\n\s*\},?\s*(?=\'[^\']+\'\s*:|$)/';

        if (preg_match_all($pattern, $palette_content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $palette_id = $match[1];
                $palette_data = $match[2];

                $parsed_palette = $this->parse_single_palette($palette_data);
                if ($parsed_palette) {
                    $palettes[$palette_id] = $parsed_palette;
                }
            }
        }

        return $palettes;
    }

    /**
     * Parse a single palette object
     *
     * @param string $palette_data Single palette object content
     * @return array|false Parsed palette or false on failure
     */
    private function parse_single_palette($palette_data) {
        $palette = array();

        // Extract basic properties
        if (preg_match('/name:\s*[\'"]([^\'"]+)[\'"]/', $palette_data, $matches)) {
            $palette['name'] = $matches[1];
        }

        if (preg_match('/category:\s*[\'"]([^\'"]+)[\'"]/', $palette_data, $matches)) {
            $palette['category'] = $matches[1];
        }

        if (preg_match('/description:\s*[\'"]([^\'"]+)[\'"]/', $palette_data, $matches)) {
            $palette['description'] = $matches[1];
        }

        // Extract colors object - handle nested braces properly
        if (preg_match('/colors:\s*\{/', $palette_data, $start_match, PREG_OFFSET_CAPTURE)) {
            $start_pos = $start_match[0][1] + strlen($start_match[0][0]);
            $brace_count = 1;
            $pos = $start_pos;

            // Find the matching closing brace
            while ($pos < strlen($palette_data) && $brace_count > 0) {
                if ($palette_data[$pos] === '{') {
                    $brace_count++;
                } elseif ($palette_data[$pos] === '}') {
                    $brace_count--;
                }
                $pos++;
            }

            if ($brace_count === 0) {
                $colors_content = substr($palette_data, $start_pos, $pos - $start_pos - 1);
                $palette['colors'] = $this->parse_colors_object($colors_content);
            }
        }

        // Extract metadata if present
        if (preg_match('/metadata:\s*\{([\s\S]*?)\n\s*\}/', $palette_data, $matches)) {
            $metadata_content = $matches[1];
            $palette['metadata'] = $this->parse_metadata_object($metadata_content);
        }

        return !empty($palette['colors']) ? $palette : false;
    }

    /**
     * Parse colors object from palette
     *
     * @param string $colors_content Colors object content
     * @return array Parsed colors
     */
            private function parse_colors_object($colors_content) {
        $colors = array();

        // Use a simpler approach: extract each color role by finding hex values
        $color_roles = array('primary', 'secondary', 'surface', 'background', 'accent');

        foreach ($color_roles as $role) {
            // Extract hex value for this role
            $hex_pattern = "/{$role}:\s*\{[\s\S]*?hex:\s*['\"]([^'\"]+)['\"]/";
            if (preg_match($hex_pattern, $colors_content, $hex_match)) {
                $hex = $hex_match[1];

                // Extract name for this role
                $name_pattern = "/{$role}:\s*\{[\s\S]*?name:\s*['\"]([^'\"]+)['\"]/";
                $name = 'Unknown';
                if (preg_match($name_pattern, $colors_content, $name_match)) {
                    $name = $name_match[1];
                }

                // Extract description for this role
                $desc_pattern = "/{$role}:\s*\{[\s\S]*?description:\s*['\"]([^'\"]+)['\"]/";
                $description = '';
                if (preg_match($desc_pattern, $colors_content, $desc_match)) {
                    $description = $desc_match[1];
                }

                $colors[$role] = array(
                    'hex' => $hex,
                    'name' => $name,
                    'description' => $description
                );
            }
        }

        return $colors;
    }

    /**
     * Parse a single color definition
     *
     * @param string $color_data Single color object content
     * @return array|false Parsed color or false on failure
     */
    private function parse_single_color($color_data) {
        $color = array();

        // Extract hex value
        if (preg_match('/hex:\s*[\'"]([^\'"]+)[\'"]/', $color_data, $matches)) {
            $color['hex'] = $matches[1];
        }

        // Extract name
        if (preg_match('/name:\s*[\'"]([^\'"]+)[\'"]/', $color_data, $matches)) {
            $color['name'] = $matches[1];
        }

        // Extract description
        if (preg_match('/description:\s*[\'"]([^\'"]+)[\'"]/', $color_data, $matches)) {
            $color['description'] = $matches[1];
        }

        // Extract role
        if (preg_match('/role:\s*[\'"]([^\'"]+)[\'"]/', $color_data, $matches)) {
            $color['role'] = $matches[1];
        }

        return !empty($color['hex']) ? $color : false;
    }

    /**
     * Parse metadata object
     *
     * @param string $metadata_content Metadata object content
     * @return array Parsed metadata
     */
    private function parse_metadata_object($metadata_content) {
        $metadata = array();

        // Extract design principles array
        if (preg_match('/designPrinciples:\s*\[([\s\S]*?)\]/', $metadata_content, $matches)) {
            $principles_content = $matches[1];
            $metadata['designPrinciples'] = $this->parse_string_array($principles_content);
        }

        // Extract target facilities array
        if (preg_match('/targetFacilities:\s*\[([\s\S]*?)\]/', $metadata_content, $matches)) {
            $facilities_content = $matches[1];
            $metadata['targetFacilities'] = $this->parse_string_array($facilities_content);
        }

        // Extract psychological impact
        if (preg_match('/psychologicalImpact:\s*[\'"]([^\'"]+)[\'"]/', $metadata_content, $matches)) {
            $metadata['psychologicalImpact'] = $matches[1];
        }

        return $metadata;
    }

    /**
     * Parse string array from JavaScript
     *
     * @param string $array_content Array content
     * @return array Parsed string array
     */
    private function parse_string_array($array_content) {
        $items = array();

        if (preg_match_all('/[\'"]([^\'"]+)[\'"]/', $array_content, $matches)) {
            $items = $matches[1];
        }

        return $items;
    }

    /**
     * Cache parsed palette data for performance
     *
     * @param array $palette_data Parsed palette data
     */
    private function cache_palette_data($palette_data) {
        $cache_key = 'semantic_palette_cache_' . md5(serialize($palette_data));
        set_transient($cache_key, $palette_data, HOUR_IN_SECONDS);
    }

    /**
     * Generate CSS custom properties for a specific palette
     *
     * @param string $palette_id Palette ID
     * @return string Generated CSS
     */
    public function generate_css_properties($palette_id) {
        $start_time = microtime(true);

        // Check cache first
        $cache_key = 'css_properties_' . $palette_id;
        if (isset($this->css_cache[$cache_key])) {
            $this->performance_metrics['cache_hits']++;
            return $this->css_cache[$cache_key];
        }

        $this->performance_metrics['cache_misses']++;

        // Get palette data
        $palette = $this->get_palette($palette_id);
        if (!$palette || !isset($palette['colors'])) {
            return '';
        }

        $css = ":root {\n";
        $css .= "  /* Semantic Color Tokens - Generated from {$palette_id} */\n";

        // Generate semantic foundation tokens
        foreach ($palette['colors'] as $role => $color_data) {
            if (isset($color_data['hex'])) {
                $semantic_token = $this->map_role_to_semantic_token($role);
                $css .= "  --{$semantic_token}: {$color_data['hex']}; /* {$color_data['name']} */\n";
            }
        }

        $css .= "\n  /* Extended Semantic Tokens */\n";

        // Generate extended semantic tokens (text, borders, feedback, etc.)
        foreach ($this->extended_semantic_tokens as $token_name => $default_value) {
            $css .= "  --{$token_name}: {$default_value};\n";
        }

        $css .= "\n  /* Component-specific tokens */\n";

        // Generate component tokens based on semantic roles
        $css .= $this->generate_component_tokens($palette['colors']);

        $css .= "}\n";

        // Cache the generated CSS
        $this->css_cache[$cache_key] = $css;
        $this->performance_metrics['css_generation_time'] += microtime(true) - $start_time;

        return $css;
    }

    /**
     * Map color role to semantic token name
     *
     * @param string $role Color role
     * @return string Semantic token name
     */
    private function map_role_to_semantic_token($role) {
        return isset($this->semantic_role_mapping[$role])
            ? $this->semantic_role_mapping[$role]
            : 'color-' . str_replace('_', '-', $role);
    }

    /**
     * Generate component-specific tokens
     *
     * @param array $colors Palette colors
     * @return string Component tokens CSS
     */
    private function generate_component_tokens($colors) {
        $css = '';

        // Button tokens
        if (isset($colors['primary']['hex'])) {
            $css .= "  --btn-primary-bg: var(--color-brand-primary);\n";
            $css .= "  --btn-primary-text: var(--color-text-inverse);\n";
            $css .= "  --btn-primary-border: var(--color-brand-primary);\n";
        }

        if (isset($colors['secondary']['hex'])) {
            $css .= "  --btn-secondary-bg: var(--color-brand-secondary);\n";
            $css .= "  --btn-secondary-text: var(--color-text-inverse);\n";
            $css .= "  --btn-secondary-border: var(--color-brand-secondary);\n";
        }

        if (isset($colors['accent']['hex'])) {
            $css .= "  --btn-accent-bg: var(--color-accent-primary);\n";
            $css .= "  --btn-accent-text: var(--color-text-primary);\n";
            $css .= "  --btn-accent-border: var(--color-accent-primary);\n";
        }

        // Surface tokens
        if (isset($colors['surface']['hex'])) {
            $css .= "  --card-bg: var(--color-surface-primary);\n";
            $css .= "  --modal-bg: var(--color-surface-primary);\n";
            $css .= "  --form-bg: var(--color-surface-primary);\n";
        }

        if (isset($colors['background']['hex'])) {
            $css .= "  --page-bg: var(--color-surface-background);\n";
            $css .= "  --section-bg: var(--color-surface-background);\n";
        }

        // Form component tokens
        $css .= "  --input-bg: var(--color-surface-primary);\n";
        $css .= "  --input-border: var(--color-border-secondary);\n";
        $css .= "  --input-border-focus: var(--color-interactive-focus);\n";
        $css .= "  --input-text: var(--color-text-primary);\n";
        $css .= "  --input-placeholder: var(--color-text-muted);\n";

        // Link tokens
        $css .= "  --link-color: var(--color-brand-primary);\n";
        $css .= "  --link-hover: var(--color-interactive-hover);\n";
        $css .= "  --link-active: var(--color-interactive-active);\n";

        // Border tokens for components
        $css .= "  --card-border: var(--color-border-light);\n";
        $css .= "  --section-border: var(--color-border-secondary);\n";

        return $css;
    }

    /**
     * Get palette data by ID
     *
     * @param string $palette_id Palette ID
     * @return array|false Palette data or false if not found
     */
    public function get_palette($palette_id) {
        return isset($this->js_palette_cache[$palette_id])
            ? $this->js_palette_cache[$palette_id]
            : false;
    }

    /**
     * Get all available palettes
     *
     * @return array All palette data
     */
    public function get_all_palettes() {
        return $this->js_palette_cache;
    }

    /**
     * Set active palette
     *
     * @param string $palette_id Palette ID
     * @return bool Success
     */
    public function set_active_palette($palette_id) {
        if (isset($this->js_palette_cache[$palette_id])) {
            $this->active_palette_id = $palette_id;

            // Update WordPress option
            update_option('semantic_active_palette', $palette_id);

            // Generate and cache CSS for the active palette
            $this->generate_static_css_file($palette_id);

            return true;
        }

        return false;
    }

    /**
     * Get active palette ID
     *
     * @return string Active palette ID
     */
    public function get_active_palette_id() {
        return get_option('semantic_active_palette', $this->active_palette_id);
    }

    /**
     * Generate static CSS file for performance optimization
     *
     * @param string $palette_id Palette ID
     * @return bool Success
     */
    public function generate_static_css_file($palette_id) {
        $css_content = $this->generate_css_properties($palette_id);

        if (empty($css_content)) {
            return false;
        }

        // Create CSS file path
        $upload_dir = wp_upload_dir();
        $css_dir = $upload_dir['basedir'] . '/semantic-tokens/';

        // Create directory if it doesn't exist
        if (!file_exists($css_dir)) {
            wp_mkdir_p($css_dir);
        }

        $css_file = $css_dir . 'palette-' . $palette_id . '.css';

        // Write CSS to file
        $result = file_put_contents($css_file, $css_content);

        if ($result !== false) {
            // Update option with file path for enqueueing
            update_option('semantic_active_css_file', $css_file);
            return true;
        }

        return false;
    }

    /**
     * Initialize WordPress hooks
     */
    private function init_wordpress_hooks() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_semantic_css'));
        add_action('customize_register', array($this, 'register_customizer_controls'));
        add_action('customize_preview_init', array($this, 'enqueue_preview_scripts'));
        add_action('wp_ajax_update_semantic_palette', array($this, 'ajax_update_palette'));
        add_action('wp_ajax_nopriv_update_semantic_palette', array($this, 'ajax_update_palette'));
    }

    /**
     * Enqueue semantic CSS
     */
    public function enqueue_semantic_css() {
        $active_palette = $this->get_active_palette_id();
        $css_file = get_option('semantic_active_css_file');

        if ($css_file && file_exists($css_file)) {
            $upload_dir = wp_upload_dir();
            $css_url = str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $css_file);

            wp_enqueue_style(
                'semantic-tokens',
                $css_url,
                array(),
                filemtime($css_file)
            );
        } else {
            // Fallback: inline CSS
            $css_content = $this->generate_css_properties($active_palette);
            if (!empty($css_content)) {
                wp_add_inline_style('medical-spa-theme', $css_content);
            }
        }
    }

    /**
     * Setup WordPress Customizer integration
     */
    private function setup_customizer_integration() {
        // Integration will be handled by register_customizer_controls
    }

    /**
     * Register WordPress Customizer controls
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     */
    public function register_customizer_controls($wp_customize) {
        // Add semantic tokens section
        $wp_customize->add_section('semantic_tokens', array(
            'title' => __('Semantic Color Palettes', 'medspatheme'),
            'description' => __('Choose from professional medical spa color palettes with semantic token integration.', 'medspatheme'),
            'priority' => 30,
        ));

        // Add palette selection control
        $wp_customize->add_setting('semantic_active_palette', array(
            'default' => 'professional-trust',
            'sanitize_callback' => array($this, 'sanitize_palette_choice'),
            'transport' => 'postMessage',
        ));

        $palette_choices = array();
        foreach ($this->js_palette_cache as $id => $palette) {
            $palette_choices[$id] = $palette['name'] . ' - ' . $palette['description'];
        }

        $wp_customize->add_control('semantic_active_palette', array(
            'label' => __('Color Palette', 'medspatheme'),
            'description' => __('Select a professional color palette for your medical spa.', 'medspatheme'),
            'section' => 'semantic_tokens',
            'type' => 'select',
            'choices' => $palette_choices,
        ));
    }

    /**
     * Sanitize palette choice
     *
     * @param string $palette_id Palette ID
     * @return string Sanitized palette ID
     */
    public function sanitize_palette_choice($palette_id) {
        return isset($this->js_palette_cache[$palette_id]) ? $palette_id : 'professional-trust';
    }

    /**
     * Enqueue preview scripts for live customizer preview
     */
    public function enqueue_preview_scripts() {
        wp_enqueue_script(
            'semantic-token-preview-enhanced',
            get_template_directory_uri() . '/assets/js/semantic-token-customizer-preview-enhanced.js',
            array('jquery', 'customize-preview'),
            '2.0.0',
            true
        );

        // Localize script with palette data
        wp_localize_script('semantic-token-preview-enhanced', 'semanticTokenData', array(
            'palettes' => $this->js_palette_cache,
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('semantic_token_nonce'),
        ));
    }

    /**
     * AJAX handler for palette updates
     */
    public function ajax_update_palette() {
        check_ajax_referer('semantic_token_nonce', 'nonce');

        $palette_id = sanitize_text_field($_POST['palette_id']);

        if ($this->set_active_palette($palette_id)) {
            wp_send_json_success(array(
                'message' => 'Palette updated successfully',
                'css' => $this->generate_css_properties($palette_id),
            ));
        } else {
            wp_send_json_error(array(
                'message' => 'Failed to update palette',
            ));
        }
    }

    /**
     * Get performance metrics
     *
     * @return array Performance metrics
     */
    public function get_performance_metrics() {
        $this->performance_metrics['total_execution_time'] = microtime(true) - $this->performance_metrics['bridge_init_time'];
        return $this->performance_metrics;
    }
}

/**
 * Initialize the Semantic Token Bridge
 */
function init_semantic_token_bridge() {
    return SemanticTokenBridge::get_instance();
}

// Initialize on WordPress init
add_action('init', 'init_semantic_token_bridge');

/**
 * Helper function to get the bridge instance
 *
 * @return SemanticTokenBridge
 */
function get_semantic_token_bridge() {
    return SemanticTokenBridge::get_instance();
}
