<?php
/**
 * Simple Visual Customizer - PVC-005 Enhanced Implementation
 * Admin bar integration with Live Preview System, Preview Messenger, and WordPress Customizer Bridge
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Visual Customizer to Admin Bar (Admins Only)
 */
function simple_visual_customizer_admin_bar($wp_admin_bar) {
    // Only show for administrators
    if (!current_user_can('manage_options')) {
        return;
    }

    $wp_admin_bar->add_node([
        'id'    => 'visual-customizer',
        'title' => '🎨 Visual Customizer',
        'href'  => '#',
        'meta'  => [
            'class' => 'visual-customizer-trigger',
            'title' => 'Open Visual Customizer with Live Preview'
        ]
    ]);
}
add_action('admin_bar_menu', 'simple_visual_customizer_admin_bar', 100);

/**
 * Enqueue Visual Customizer Scripts - Enhanced Approach
 */
function enqueue_simple_visual_customizer_scripts() {
    // Only load for users who can manage options or when explicitly enabled
    if (!current_user_can('manage_options') && !apply_filters('enable_visual_customizer_for_all', false)) {
        return;
    }

    $script_path = get_template_directory() . '/assets/js/';
    $script_url = get_template_directory_uri() . '/assets/js/';

    // Core color system dependencies
    $dependencies = array('jquery');

    if (file_exists($script_path . 'semantic-color-system.js')) {
        wp_enqueue_script(
            'semantic-color-system',
            $script_url . 'semantic-color-system.js',
            array('jquery'),
            filemtime($script_path . 'semantic-color-system.js'),
            true
        );
        $dependencies[] = 'semantic-color-system';
    }

    if (file_exists($script_path . 'color-system-manager.js')) {
        wp_enqueue_script(
            'color-system-manager',
            $script_url . 'color-system-manager.js',
            array('jquery', 'semantic-color-system'),
            filemtime($script_path . 'color-system-manager.js'),
            true
        );
        $dependencies[] = 'color-system-manager';
    }

    if (file_exists($script_path . 'color-palette-interface.js')) {
        wp_enqueue_script(
            'color-palette-interface',
            $script_url . 'color-palette-interface.js',
            array('jquery', 'semantic-color-system', 'color-system-manager'),
            filemtime($script_path . 'color-palette-interface.js'),
            true
        );
        $dependencies[] = 'color-palette-interface';
    }

    // Main Simple Visual Customizer (loads last)
    if (file_exists($script_path . 'simple-visual-customizer.js')) {
        wp_enqueue_script(
            'simple-visual-customizer',
            $script_url . 'simple-visual-customizer.js',
            $dependencies,
            filemtime($script_path . 'simple-visual-customizer.js'),
            true
        );

        // Pass configuration to JavaScript
        wp_localize_script('simple-visual-customizer', 'simpleCustomizer', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('simple_visual_customizer'),
            'debug' => defined('WP_DEBUG') && WP_DEBUG,
            'currentUserId' => get_current_user_id(),
            'canManageOptions' => current_user_can('manage_options')
        ));
    }

    // Enqueue styles
    $style_path = get_template_directory() . '/assets/css/';
    $style_url = get_template_directory_uri() . '/assets/css/';

    if (file_exists($style_path . 'simple-visual-customizer.css')) {
        wp_enqueue_style(
            'simple-visual-customizer',
            $style_url . 'simple-visual-customizer.css',
            array(),
            filemtime($style_path . 'simple-visual-customizer.css')
        );
    }
}

// Hook the enhanced approach
add_action('wp_enqueue_scripts', 'enqueue_simple_visual_customizer_scripts');
add_action('admin_enqueue_scripts', 'enqueue_simple_visual_customizer_scripts');

/**
 * Enhanced AJAX Handler - Apply Changes with Live Preview Integration
 */
function handle_simple_visual_customizer_apply() {
    // Security check
    if (!wp_verify_nonce($_POST['nonce'], 'simple_visual_customizer')) {
        wp_send_json_error(['message' => 'Security check failed']);
    }

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Insufficient permissions']);
    }

    // Get configuration from POST data
    $config_json = stripslashes($_POST['config'] ?? '{}');
    $config = json_decode($config_json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        wp_send_json_error(['message' => 'Invalid configuration data']);
    }

    try {
        // Save configuration to database
        update_option('preetidreams_visual_customizer_config', $config);

        // Set active palette for quick access
        if (isset($config['activePalette'])) {
            update_option('preetidreams_active_palette', $config['activePalette']);
        }

        // PVC-005: Generate CSS file for performance (optional)
        $css_generated = generate_global_css_from_config($config);

        // PVC-005: Clear any caching
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }

        // Log the change for debugging
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('Visual Customizer: Applied palette - ' . ($config['activePalette'] ?? 'unknown'));
        }

        wp_send_json_success([
            'message' => 'Visual customizer settings applied globally!',
            'config' => $config,
            'css_generated' => $css_generated,
            'timestamp' => current_time('mysql'),
            'performance' => [
                'save_time' => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']
            ]
        ]);

    } catch (Exception $e) {
        wp_send_json_error([
            'message' => 'Error saving configuration: ' . $e->getMessage()
        ]);
    }
}
add_action('wp_ajax_simple_visual_customizer_apply', 'handle_simple_visual_customizer_apply');

/**
 * Enhanced AJAX Handler - Reset Changes
 */
function handle_simple_visual_customizer_reset() {
    // Security check
    if (!wp_verify_nonce($_POST['nonce'], 'simple_visual_customizer')) {
        wp_send_json_error(['message' => 'Security check failed']);
    }

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Insufficient permissions']);
    }

    try {
        // Reset to defaults
        delete_option('preetidreams_visual_customizer_config');
        update_option('preetidreams_active_palette', 'medical-clean');

        // Clear generated CSS
        delete_option('preetidreams_generated_css');

        // Clear caching
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }

        wp_send_json_success([
            'message' => 'Visual customizer settings reset to defaults!',
            'config' => [],
            'default_palette' => 'medical-clean'
        ]);

    } catch (Exception $e) {
        wp_send_json_error([
            'message' => 'Error resetting configuration: ' . $e->getMessage()
        ]);
    }
}
add_action('wp_ajax_simple_visual_customizer_reset', 'handle_simple_visual_customizer_reset');

/**
 * PVC-005: Output Global Visual Customizer CSS for All Users
 * This ensures that applied palettes are visible to all visitors
 */
function output_visual_customizer_global_css() {
    // Get saved configuration
    $config = get_option('preetidreams_visual_customizer_config', []);

    if (empty($config) || !isset($config['paletteData'])) {
        return;
    }

    // Generate CSS from saved palette
    $css = generate_css_from_palette_data($config['paletteData']);

    if (!empty($css)) {
        echo "<style id='visual-customizer-global-css' data-generated='" . current_time('c') . "'>\n";
        echo "/* Visual Customizer Global CSS - Applied Palette: " . esc_attr($config['activePalette'] ?? 'unknown') . " */\n";
        echo $css;
        echo "\n</style>\n";
    }
}
add_action('wp_head', 'output_visual_customizer_global_css', 100);

/**
 * PVC-005: Generate CSS from palette data with proper theme variable mapping
 */
function generate_css_from_palette_data($palette_data) {
    if (!is_array($palette_data) || !isset($palette_data['colors'])) {
        return '';
    }

    // Map semantic color roles to ACTUAL theme variables - SURGICAL FIX
    $actual_theme_mapping = [
        'primary' => '--color-primary-navy',        // Primary brand -> actual navy variable
        'secondary' => '--color-primary-teal',      // Secondary -> actual teal variable
        'accent' => '--color-secondary-peach',      // Accent -> actual peach for gradients
        'surface' => '--color-neutral-white',       // Surface -> actual white variable
        'background' => '--color-soft-cream',       // Background -> actual cream variable
        'text' => '--color-charcoal'                // Text -> actual charcoal variable
    ];

    // Map to gradient variables that the theme actually uses
    $gradient_mapping = [
        'primary' => '--gradient-primary',          // Main gradient used in header
        'accent' => '--gradient-accent',            // Accent gradient used in buttons
        'luxury' => '--gradient-luxury',            // Luxury gradient option
        'fresh' => '--gradient-fresh'               // Fresh gradient option
    ];

    $css = ":root {\n";

    // Generate CSS custom properties with SURGICAL FIX mapping
    foreach ($palette_data['colors'] as $role => $color_data) {
        $color_value = is_array($color_data) ? ($color_data['hex'] ?? $color_data) : $color_data;

        if (!empty($color_value)) {
            // Map to actual theme variables
            if (isset($actual_theme_mapping[$role])) {
                $theme_var = $actual_theme_mapping[$role];
                $css .= "    {$theme_var}: {$color_value};\n";
                $css .= "    {$theme_var}-rgb: " . hex_to_rgb($color_value) . ";\n";

                // Log mapping for debugging
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log("Visual Customizer SURGICAL FIX: Mapping {$role} ({$color_value}) -> {$theme_var}");
                }
            }

            // Map to gradient variables if applicable
            if (($role === 'primary' || $role === 'accent') && isset($gradient_mapping[$role])) {
                $gradient_var = $gradient_mapping[$role];
                if ($role === 'primary') {
                    // Create gradient from primary color variations
                    $darker_color = adjust_color_brightness($color_value, -0.2);
                    $css .= "    {$gradient_var}: linear-gradient(135deg, {$color_value} 0%, {$darker_color} 100%);\n";
                } else if ($role === 'accent') {
                    // Create gradient from accent color variations
                    $lighter_color = adjust_color_brightness($color_value, 0.1);
                    $css .= "    {$gradient_var}: linear-gradient(135deg, {$color_value} 0%, {$lighter_color} 100%);\n";
                }
            }

            // Add legacy support for any remaining references
            if ($role === 'primary') {
                $css .= "    --color-primary: {$color_value};\n";
                $css .= "    --color-primary-rgb: " . hex_to_rgb($color_value) . ";\n";
            }
            if ($role === 'secondary') {
                $css .= "    --color-secondary: {$color_value};\n";
                $css .= "    --color-secondary-rgb: " . hex_to_rgb($color_value) . ";\n";
            }
            if ($role === 'accent') {
                $css .= "    --color-accent: {$color_value};\n";
                $css .= "    --color-accent-rgb: " . hex_to_rgb($color_value) . ";\n";
            }
        }
    }

    $css .= "}\n\n";

    // Add medical spa theme integration using actual theme classes
    $css .= generate_medical_spa_integration_css();

    return $css;
}

/**
 * PVC-005: Generate CSS file for performance (optional)
 */
function generate_global_css_from_config($config) {
    if (!isset($config['paletteData'])) {
        return false;
    }

    $css = generate_css_from_palette_data($config['paletteData']);

    if (!empty($css)) {
        // Store generated CSS in option for quick access
        update_option('preetidreams_generated_css', $css);
        return true;
    }

    return false;
}

/**
 * Medical spa theme integration CSS - SURGICAL FIX: Using ACTUAL theme variables and classes
 */
function generate_medical_spa_integration_css() {
    return "
/* Live Preview System - SURGICAL FIX - Medical Spa Theme Integration */

/* Professional Header - REAL CLASS with ACTUAL VARIABLES */
.professional-header {
    background-color: var(--color-primary-navy) !important;
    background: var(--gradient-primary) !important;
    transition: background-color 0.3s ease !important;
}

.professional-header.scrolled {
    background: rgba(var(--color-primary-navy-rgb), 0.95) !important;
    background-color: var(--color-primary-navy) !important;
    backdrop-filter: blur(10px) !important;
}

/* Navigation Menu - REAL CLASS with ACTUAL VARIABLES */
.nav-menu .menu-item a,
.site-title a,
.logo-text {
    color: var(--color-neutral-white) !important;
    text-shadow: 0 1px 3px rgba(0,0,0,0.3) !important;
}

.nav-menu .menu-item a:hover,
.nav-menu .menu-item.current-menu-item a {
    color: var(--color-primary-teal) !important;
    background: rgba(var(--color-primary-teal-rgb), 0.1) !important;
}

/* Header Actions and CTA - REAL CLASS with ACTUAL VARIABLES */
.btn-consultation {
    background: var(--gradient-accent) !important;
    border-color: var(--color-secondary-peach) !important;
    color: var(--color-neutral-white) !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 12px rgba(var(--color-secondary-peach-rgb), 0.3) !important;
}

.btn-consultation:hover {
    background: var(--color-primary-teal) !important;
    border-color: var(--color-primary-teal) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 25px rgba(var(--color-primary-teal-rgb), 0.4) !important;
}

/* Phone Links - ACTUAL VARIABLES */
.phone-link,
.mobile-phone-btn {
    color: var(--color-primary-teal) !important;
}

.phone-link:hover,
.mobile-phone-btn:hover {
    color: var(--color-primary-navy) !important;
}

/* Site Branding - ACTUAL VARIABLES */
.site-title,
.site-title a {
    color: var(--color-primary-navy) !important;
}

.logo-medical-cross {
    background: var(--gradient-primary) !important;
    color: var(--color-neutral-white) !important;
}

/* Mobile Menu Elements - ACTUAL VARIABLES */
.mobile-consultation-btn {
    background: var(--gradient-accent) !important;
    color: var(--color-neutral-white) !important;
}

.mobile-consultation-btn:hover {
    background: var(--color-primary-teal) !important;
}

/* Mobile Navigation - ACTUAL VARIABLES */
.mobile-nav-list a {
    color: var(--color-primary-navy) !important;
}

.mobile-nav-list a:hover,
.mobile-nav-list .current a {
    color: var(--color-primary-teal) !important;
}

/* General Button Styles - ACTUAL VARIABLES */
button,
.button,
input[type='submit'] {
    background: var(--gradient-accent) !important;
    color: var(--color-neutral-white) !important;
    border-color: var(--color-secondary-peach) !important;
}

button:hover,
.button:hover,
input[type='submit']:hover {
    background: var(--color-primary-teal) !important;
    border-color: var(--color-primary-teal) !important;
}

/* Footer Elements - ACTUAL VARIABLES */
.luxury-footer {
    background: var(--color-primary-navy) !important;
    color: var(--color-neutral-white) !important;
}

.cta-primary {
    background: var(--gradient-accent) !important;
    color: var(--color-neutral-white) !important;
}

.cta-secondary {
    background: var(--color-primary-teal) !important;
    color: var(--color-neutral-white) !important;
}

/* Article and Content Elements - ACTUAL VARIABLES */
article {
    background-color: var(--color-neutral-white) !important;
    border-color: var(--color-primary-teal) !important;
}

/* Form Elements - ACTUAL VARIABLES */
input:focus,
textarea:focus,
select:focus {
    border-color: var(--color-primary-teal) !important;
    box-shadow: 0 0 0 3px rgba(var(--color-primary-teal-rgb), 0.1) !important;
}

/* Text Colors - ACTUAL VARIABLES */
.text-primary,
h1, h2, h3, h4, h5, h6 {
    color: var(--color-primary-navy) !important;
}

/* High specificity for global changes - TARGET ACTUAL CLASSES */
.professional-header,
.btn-consultation,
.nav-menu,
.luxury-footer,
article,
button {
    transition: all 0.3s ease !important;
}

/* Immediate color variable overrides - SURGICAL FIX */
:root {
    /* Ensure variables exist with fallbacks to theme defaults */
    --color-primary-navy: var(--color-primary-navy, #2c3e50);
    --color-primary-teal: var(--color-primary-teal, #16a085);
    --color-secondary-peach: var(--color-secondary-peach, #f39c12);
    --color-neutral-white: var(--color-neutral-white, #ffffff);
    --color-soft-cream: var(--color-soft-cream, #fefcf8);
    --color-charcoal: var(--color-charcoal, #2c2c2c);

    /* RGB versions for transparency effects */
    --color-primary-navy-rgb: 44, 62, 80;
    --color-primary-teal-rgb: 22, 160, 133;
    --color-secondary-peach-rgb: 243, 156, 18;
}
    ";
}

/**
 * Convert hex to RGB values
 */
function hex_to_rgb($hex) {
    $hex = ltrim($hex, '#');

    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }

    if (strlen($hex) !== 6) {
        return '0, 0, 0';
    }

    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    return "{$r}, {$g}, {$b}";
}

/**
 * Adjust color brightness for gradient generation
 */
function adjust_color_brightness($hex, $amount) {
    $hex = ltrim($hex, '#');

    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }

    if (strlen($hex) !== 6) {
        return $hex;
    }

    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    $r = max(0, min(255, $r + ($amount * 255)));
    $g = max(0, min(255, $g + ($amount * 255)));
    $b = max(0, min(255, $b + ($amount * 255)));

    return sprintf("#%02x%02x%02x", $r, $g, $b);
}

/**
 * PVC-005: Get current visual customizer status
 */
function get_visual_customizer_status() {
    $config = get_option('preetidreams_visual_customizer_config', []);
    $active_palette = get_option('preetidreams_active_palette', 'medical-clean');

    return [
        'is_configured' => !empty($config),
        'active_palette' => $active_palette,
        'config' => $config,
        'has_global_css' => !empty(get_option('preetidreams_generated_css')),
        'last_updated' => get_option('preetidreams_vc_last_updated', 'never'),
        'pvc005_enabled' => true
    ];
}

/**
 * PVC-005: WordPress Customizer Integration Hook
 */
function visual_customizer_customize_register($wp_customize) {
    // Add Visual Customizer section
    $wp_customize->add_section('visual_customizer', [
        'title' => __('Visual Customizer (PVC-005)', 'medspatheme'),
        'priority' => 30,
        'description' => __('Real-time color palette customization with live preview', 'medspatheme')
    ]);

    // Active palette setting
    $wp_customize->add_setting('visual_customizer_active_palette', [
        'default' => 'medical-clean',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage' // Live preview
    ]);

    $wp_customize->add_control('visual_customizer_active_palette', [
        'label' => __('Active Color Palette', 'medspatheme'),
        'section' => 'visual_customizer',
        'type' => 'select',
        'choices' => [
            'medical-clean' => __('Medical Clean', 'medspatheme'),
            'luxury-spa' => __('Luxury Spa', 'medspatheme'),
            'professional' => __('Professional', 'medspatheme'),
            'calming-oasis' => __('Calming Oasis', 'medspatheme'),
            'earth-tones' => __('Earth Tones', 'medspatheme'),
            'wellness-center' => __('Wellness Center', 'medspatheme'),
            'modern-clinic' => __('Modern Clinic', 'medspatheme')
        ]
    ]);

    // Preview mode setting
    $wp_customize->add_setting('visual_customizer_preview_mode', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport' => 'postMessage'
    ]);

    $wp_customize->add_control('visual_customizer_preview_mode', [
        'label' => __('Enable Live Preview', 'medspatheme'),
        'section' => 'visual_customizer',
        'type' => 'checkbox',
        'description' => __('Real-time color updates with < 100ms response time', 'medspatheme')
    ]);
}
add_action('customize_register', 'visual_customizer_customize_register');

/**
 * PVC-005: Add admin body class for enhanced styling
 */
function visual_customizer_admin_body_class($classes) {
    if (current_user_can('manage_options')) {
        $classes .= ' visual-customizer-enabled pvc-005-active';
    }
    return $classes;
}
add_filter('admin_body_class', 'visual_customizer_admin_body_class');

/**
 * PVC-005: Performance monitoring hook
 */
function visual_customizer_performance_monitor() {
    if (!current_user_can('manage_options') || !defined('WP_DEBUG') || !WP_DEBUG) {
        return;
    }

    $performance_data = [
        'page_load_time' => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],
        'memory_usage' => memory_get_peak_usage(true),
        'db_queries' => get_num_queries(),
        'timestamp' => current_time('c')
    ];

    echo "<script>console.log('PVC-005 Performance:', " . json_encode($performance_data) . ");</script>";
}
add_action('wp_footer', 'visual_customizer_performance_monitor');

/**
 * Sanitize configuration data
 */
function sanitize_visual_customizer_config($config) {
    if (!is_array($config)) {
        return [];
    }

    $sanitized = [];

    if (isset($config['activePalette'])) {
        $sanitized['activePalette'] = sanitize_text_field($config['activePalette']);
    }

    if (isset($config['paletteData']) && is_array($config['paletteData'])) {
        $sanitized['paletteData'] = array_map(function($value) {
            return is_array($value) ? array_map('sanitize_text_field', $value) : sanitize_text_field($value);
        }, $config['paletteData']);
    }

    if (isset($config['timestamp'])) {
        $sanitized['timestamp'] = floatval($config['timestamp']);
    }

    return $sanitized;
}
