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
    // DEBUGGING: Show for everyone temporarily to test functionality
    // TODO: Restore admin-only check after testing
    // if (!current_user_can('manage_options')) {
    //     return;
    // }

    $wp_admin_bar->add_node([
        'id'    => 'visual-customizer', // WordPress will make this 'wp-admin-bar-visual-customizer'
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
    // DEBUGGING: Load for everyone temporarily to test functionality
    // TODO: Restore user capability check after testing

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
            'debug' => true, // Force debug mode
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

    // Initialize variables
    $active_palette = null;
    $active_typography = null;
    $typography_data = null;
    $config_saved = false;
    $palette_saved = false;
    $typography_saved = false;
    $typography_data_saved = false;
    $verify_config = null;
    $verify_palette = null;
    $verify_typography = null;

    try {
        // ENHANCED: Support both palette and typography configurations
        $active_palette = $config['activePalette'] ?? null;
        $active_typography = $config['activeTypography'] ?? null;
        $typography_data = $config['typographyData'] ?? null;

        // Validate that at least one configuration type is provided
        if (!$active_palette && !$active_typography) {
            wp_send_json_error(['message' => 'No active palette or typography specified']);
        }

        // PRODUCTION FIX: Clear any stale options first to prevent second-to-last issue
        delete_option('preetidreams_visual_customizer_config');
        if ($active_palette) {
            delete_option('preetidreams_active_palette');
        }
        if ($active_typography) {
            delete_option('preetidreams_active_typography');
        }

        // PRODUCTION FIX: Add small delay to ensure DB write completion
        usleep(50000); // 50ms delay

        // Save configuration to database with timestamp
        $config['saved_at'] = current_time('mysql');
        $config['saved_timestamp'] = time();

        $config_saved = update_option('preetidreams_visual_customizer_config', $config);

        // Save active palette if provided
        $palette_saved = true;
        if ($active_palette) {
            $palette_saved = update_option('preetidreams_active_palette', $active_palette);
            // PRODUCTION FIX: Also save to theme_mods for WordPress Customizer compatibility
            set_theme_mod('visual_customizer_active_palette', $active_palette);
        }

        // Save active typography if provided
        $typography_saved = true;
        if ($active_typography && $typography_data) {
            $typography_saved = update_option('preetidreams_active_typography', $active_typography);
            $typography_data_saved = update_option('preetidreams_typography_data', $typography_data);
            // Also save to theme_mods for consistency
            set_theme_mod('visual_customizer_active_typography', $active_typography);
        }

        // ENHANCED: Save typography in main config as fallback (for CSS output function)
        if ($active_typography && $typography_data) {
            $config['activeTypography'] = $active_typography;
            $config['typographyData'] = $typography_data;
            // Re-save config with typography data included
            update_option('preetidreams_visual_customizer_config', $config);

            // Debug logging
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log("Visual Customizer: Typography saved - Active: {$active_typography}, Data keys: " . implode(', ', array_keys($typography_data)));
            }
        }

        // PRODUCTION FIX: Force database flush to ensure write completion
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }

        // Verify the save was successful
        $verify_config = get_option('preetidreams_visual_customizer_config');
        $verify_palette = $active_palette ? get_option('preetidreams_active_palette') : null;
        $verify_typography = $active_typography ? get_option('preetidreams_active_typography') : null;

        // Enhanced verification
        $verification_failed = false;
        if (!$verify_config) {
            $verification_failed = true;
        }
        if ($active_palette && (!$verify_palette || $verify_palette !== $active_palette)) {
            $verification_failed = true;
        }
        if ($active_typography && (!$verify_typography || $verify_typography !== $active_typography)) {
            $verification_failed = true;
        }

        if ($verification_failed) {
            wp_send_json_error([
                'message' => 'Database save verification failed',
                'debug' => [
                    'config_saved' => $config_saved,
                    'palette_saved' => $palette_saved,
                    'typography_saved' => $typography_saved ?? false,
                    'verify_config' => !empty($verify_config),
                    'verify_palette' => $verify_palette,
                    'verify_typography' => $verify_typography,
                    'expected_palette' => $active_palette,
                    'expected_typography' => $active_typography
                ]
            ]);
        }

        // PVC-005: Generate CSS file for performance (optional)
        $css_generated = generate_global_css_from_config($config);

        // Log the change for debugging
        if (defined('WP_DEBUG') && WP_DEBUG) {
            if ($active_palette) {
                error_log("Visual Customizer: Applied palette - {$active_palette} at " . current_time('mysql'));
            }
            if ($active_typography) {
                error_log("Visual Customizer: Applied typography - {$active_typography} at " . current_time('mysql'));
            }
            error_log("Visual Customizer: Config verified - " . ($verify_config ? 'YES' : 'NO'));
        }

        $success_message = 'Visual customizer settings applied globally!';
        if ($active_palette && $active_typography) {
            $success_message = 'Visual customizer palette and typography applied globally!';
        } elseif ($active_typography) {
            $success_message = 'Typography settings applied globally!';
        }

        wp_send_json_success([
            'message' => $success_message,
            'config' => $config,
            'css_generated' => $css_generated,
            'timestamp' => current_time('mysql'),
            'applied_palette' => $active_palette,
            'applied_typography' => $active_typography,
            'verification' => [
                'config_saved' => $config_saved,
                'palette_saved' => $palette_saved,
                'typography_saved' => $typography_saved ?? false,
                'verified_palette' => $verify_palette,
                'verified_typography' => $verify_typography
            ],
            'performance' => [
                'save_time' => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']
            ]
        ]);

    } catch (Exception $e) {
        wp_send_json_error([
            'message' => 'Error saving configuration: ' . $e->getMessage(),
            'debug' => [
                'active_palette' => $active_palette ?? 'not set',
                'active_typography' => $active_typography ?? 'not set',
                'config_keys' => array_keys($config ?? [])
            ]
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
 * ENHANCED: Supports both palette and typography configurations
 */
function output_visual_customizer_global_css() {
    // CONFLICT RESOLUTION: Disable other CSS output functions that interfere
    remove_action('wp_head', 'medspaa_output_dynamic_css');

    // Get saved configuration
    $config = get_option('preetidreams_visual_customizer_config', []);
    $typography_data = get_option('preetidreams_typography_data', null);
    $active_typography = get_option('preetidreams_active_typography', null);

    $has_palette = !empty($config) && isset($config['paletteData']);

    // FIXED: Improved typography detection logic
    $has_typography = false;
    if (!empty($typography_data) && !empty($active_typography)) {
        $has_typography = true;
    } elseif (!empty($config) && isset($config['typographyData']) && isset($config['activeTypography'])) {
        // Fallback: get typography from main config if not in separate options
        $typography_data = $config['typographyData'];
        $active_typography = $config['activeTypography'];
        $has_typography = true;

        // Debug: Log fallback usage
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log("Visual Customizer: Using typography from main config - {$active_typography}");
        }
    }

    if (!$has_palette && !$has_typography) {
        echo "<!-- Visual Customizer: No palette or typography data available -->\n";

        // Debug output for troubleshooting
        if (defined('WP_DEBUG') && WP_DEBUG) {
            echo "<!-- Debug: config keys = " . implode(', ', array_keys($config)) . " -->\n";
            echo "<!-- Debug: typography_data exists = " . (empty($typography_data) ? 'no' : 'yes') . " -->\n";
            echo "<!-- Debug: active_typography = " . var_export($active_typography, true) . " -->\n";
        }
        return;
    }

    echo "<style id='visual-customizer-global-css' data-generated='" . current_time('c') . "' data-palette='" . esc_attr($config['activePalette'] ?? 'none') . "' data-typography='" . esc_attr($active_typography ?? 'none') . "'>\n";
    echo "/* Visual Customizer Global CSS */\n";

    // Generate palette CSS if available
    if ($has_palette) {
        $palette_css = generate_css_from_palette_data($config['paletteData']);
        if (!empty($palette_css)) {
            echo "/* Applied Palette: " . esc_attr($config['activePalette'] ?? 'unknown') . " */\n";
            echo "/* PURE TOKENIZATION: Semantic component tokens only */\n";
            echo $palette_css;
            echo "\n";
        }
    }

    // Generate typography CSS if available
    if ($has_typography) {
        $typography_css = generate_css_from_typography_data($typography_data);
        if (!empty($typography_css)) {
            echo "/* Applied Typography: " . esc_attr($active_typography) . " */\n";
            echo "/* TYPOGRAPHY TOKENIZATION: Component font tokens */\n";
            echo $typography_css;
            echo "\n";
        } else {
            // Debug: Typography data structure issue
            if (defined('WP_DEBUG') && WP_DEBUG) {
                echo "/* Debug: Typography CSS generation failed */\n";
                echo "/* Typography data structure: " . wp_json_encode($typography_data) . " */\n";
            }
        }
    }

    $total_length = 0;
    if ($has_palette && isset($palette_css)) $total_length += strlen($palette_css);
    if ($has_typography && isset($typography_css)) $total_length += strlen($typography_css);

    echo "/* Total CSS Length: " . $total_length . " characters */\n";
    echo "/* Generated at: " . current_time('c') . " */\n";
    echo "</style>\n";
}
// HIGHEST PRIORITY to override other CSS output functions
add_action('wp_head', 'output_visual_customizer_global_css', 50);

/**
 * PVC-005: Generate CSS from palette data with PURE TOKENIZATION approach
 */
function generate_css_from_palette_data($palette_data) {
    if (!is_array($palette_data) || !isset($palette_data['colors'])) {
        return '';
    }

    // SPECIFICITY BOOST: Add body selector for higher specificity
    $css = "/* SERVER CSS OVERRIDE - Priority 50 with specificity boost */\n";
    $css .= "body:root, html:root {\n";

    // PURE TOKENIZATION: Generate ONLY semantic component tokens
    // Map palette colors to semantic roles (NO hardcoded theme names)
    $primaryColor = isset($palette_data['colors']['primary']) ?
        ($palette_data['colors']['primary']['hex'] ?? $palette_data['colors']['primary']) : '#1B365D';
    $secondaryColor = isset($palette_data['colors']['secondary']) ?
        ($palette_data['colors']['secondary']['hex'] ?? $palette_data['colors']['secondary']) : '#E4A853';
    $accentColor = isset($palette_data['colors']['accent']) ?
        ($palette_data['colors']['accent']['hex'] ?? $palette_data['colors']['accent']) : '#C2847A';
    $surfaceColor = isset($palette_data['colors']['surface']) ?
        ($palette_data['colors']['surface']['hex'] ?? $palette_data['colors']['surface']) : '#FDF2F8';
    $neutralColor = isset($palette_data['colors']['neutral']) ?
        ($palette_data['colors']['neutral']['hex'] ?? $palette_data['colors']['neutral']) : '#ffffff';
    $darkColor = isset($palette_data['colors']['dark']) ?
        ($palette_data['colors']['dark']['hex'] ?? $palette_data['colors']['dark']) : '#34495e';

    // COMPONENT TOKENS - The core of tokenization (works with ANY palette)
    $css .= "    --component-bg-color-primary: {$primaryColor} !important;\n";
    $css .= "    --component-text-color-primary: {$surfaceColor} !important;\n";
    $css .= "    --component-border-color-primary: {$primaryColor} !important;\n";
    $css .= "    --component-bg-color-secondary: {$secondaryColor} !important;\n";
    $css .= "    --component-text-color-secondary: {$surfaceColor} !important;\n";
    $css .= "    --component-border-color-secondary: {$secondaryColor} !important;\n";
    $css .= "    --component-bg-color-accent: {$accentColor} !important;\n";
    $css .= "    --component-text-color-accent: {$surfaceColor} !important;\n";
    $css .= "    --component-border-color-accent: {$accentColor} !important;\n";
    $css .= "    --component-surface-color: {$surfaceColor} !important;\n";
    $css .= "    --component-neutral-color: {$neutralColor} !important;\n";
    $css .= "    --component-dark-color: {$darkColor} !important;\n";

    // SEMANTIC COLOR ROLES (palette-agnostic)
    $css .= "    --color-primary: {$primaryColor} !important;\n";
    $css .= "    --color-primary-rgb: " . hex_to_rgb($primaryColor) . " !important;\n";
    $css .= "    --color-secondary: {$secondaryColor} !important;\n";
    $css .= "    --color-secondary-rgb: " . hex_to_rgb($secondaryColor) . " !important;\n";
    $css .= "    --color-accent: {$accentColor} !important;\n";
    $css .= "    --color-accent-rgb: " . hex_to_rgb($accentColor) . " !important;\n";
    $css .= "    --color-surface: {$surfaceColor} !important;\n";
    $css .= "    --color-neutral: {$neutralColor} !important;\n";
    $css .= "    --color-dark: {$darkColor} !important;\n";

    // PALETTE TOKENS (bridge between component and foundation)
    $css .= "    --palette-primary: {$primaryColor} !important;\n";
    $css .= "    --palette-primary-contrast: {$surfaceColor} !important;\n";
    $css .= "    --palette-primary-hover: " . adjust_color_brightness($primaryColor, -0.15) . " !important;\n";
    $css .= "    --palette-secondary: {$secondaryColor} !important;\n";
    $css .= "    --palette-secondary-contrast: {$surfaceColor} !important;\n";
    $css .= "    --palette-secondary-hover: " . adjust_color_brightness($secondaryColor, -0.15) . " !important;\n";
    $css .= "    --palette-accent: {$accentColor} !important;\n";
    $css .= "    --palette-accent-contrast: {$surfaceColor} !important;\n";
    $css .= "    --palette-accent-hover: " . adjust_color_brightness($accentColor, -0.15) . " !important;\n";

    // INTERACTION STATES (hover, focus, active)
    $css .= "    --color-primary-hover: " . adjust_color_brightness($primaryColor, -0.15) . " !important;\n";
    $css .= "    --color-primary-focus: " . adjust_color_brightness($primaryColor, -0.1) . " !important;\n";
    $css .= "    --color-primary-active: " . adjust_color_brightness($primaryColor, -0.2) . " !important;\n";
    $css .= "    --color-secondary-hover: " . adjust_color_brightness($secondaryColor, -0.15) . " !important;\n";
    $css .= "    --color-secondary-focus: " . adjust_color_brightness($secondaryColor, -0.1) . " !important;\n";
    $css .= "    --color-secondary-active: " . adjust_color_brightness($secondaryColor, -0.2) . " !important;\n";
    $css .= "    --color-accent-hover: " . adjust_color_brightness($accentColor, -0.15) . " !important;\n";
    $css .= "    --color-accent-focus: " . adjust_color_brightness($accentColor, -0.1) . " !important;\n";
    $css .= "    --color-accent-active: " . adjust_color_brightness($accentColor, -0.2) . " !important;\n";

    // GRADIENTS (semantic, not hardcoded)
    $css .= "    --gradient-primary: linear-gradient(135deg, {$primaryColor} 0%, {$secondaryColor} 100%) !important;\n";
    $css .= "    --gradient-accent: linear-gradient(135deg, {$accentColor} 0%, " . adjust_color_brightness($accentColor, -0.1) . " 100%) !important;\n";
    $css .= "    --gradient-surface: linear-gradient(135deg, {$surfaceColor} 0%, " . adjust_color_brightness($surfaceColor, -0.05) . " 100%) !important;\n";

    // STATUS COLORS (semantic system colors)
    $statusColors = [
        'success' => '#10b981',
        'warning' => '#f59e0b',
        'error' => '#ef4444',
        'info' => '#3b82f6'
    ];

    foreach ($statusColors as $status => $color) {
        $css .= "    --color-{$status}: {$color} !important;\n";
        $css .= "    --color-{$status}-hover: " . adjust_color_brightness($color, -0.15) . " !important;\n";
        $css .= "    --color-{$status}-light: " . adjust_color_brightness($color, 0.15) . " !important;\n";
        $css .= "    --color-{$status}-rgb: " . hex_to_rgb($color) . " !important;\n";
    }

    $css .= "}\n\n";

    // Add comprehensive button CSS rules using SEMANTIC tokens
    $css .= generate_semantic_button_css();

    return $css;
}

/**
 * Generate semantic button CSS using component tokens
 */
function generate_semantic_button_css() {
    $css = "/* SEMANTIC BUTTON TOKENIZATION - Works with ANY palette */\n\n";

    // PRIMARY BUTTONS - Use semantic component tokens
    $css .= "
/* PRIMARY BUTTONS - Semantic Tokenization */
html body button.btn.btn--primary[class],
html body button.cta-primary[class],
html body a.btn-consultation[class],
html body button.btn--consultation[class] {
    background-color: var(--component-bg-color-primary) !important;
    color: var(--component-text-color-primary) !important;
    border-color: var(--component-border-color-primary) !important;
    transition: all 0.3s ease !important;
}

html body button.btn.btn--primary[class]:hover,
html body button.cta-primary[class]:hover,
html body a.btn-consultation[class]:hover,
html body button.btn--consultation[class]:hover {
    background-color: var(--color-primary-hover) !important;
    border-color: var(--color-primary-hover) !important;
    color: var(--component-text-color-primary) !important;
}

/* SECONDARY BUTTONS - Semantic Tokenization */
html body button.btn.btn--secondary[class],
html body button.cta-secondary[class] {
    background-color: var(--component-bg-color-secondary) !important;
    color: var(--component-text-color-secondary) !important;
    border-color: var(--component-border-color-secondary) !important;
    transition: all 0.3s ease !important;
}

html body button.btn.btn--secondary[class]:hover,
html body button.cta-secondary[class]:hover {
    background-color: var(--color-secondary-hover) !important;
    border-color: var(--color-secondary-hover) !important;
}

/* ACCENT BUTTONS - Semantic Tokenization */
html body button.btn.btn--accent[class],
html body button.btn.btn--accent.btn--sm[class] {
    background-color: var(--component-bg-color-accent) !important;
    color: var(--component-text-color-accent) !important;
    border-color: var(--component-border-color-accent) !important;
    transition: all 0.3s ease !important;
}

html body button.btn.btn--accent[class]:hover,
html body button.btn.btn--accent.btn--sm[class]:hover {
    background-color: var(--color-accent-hover) !important;
    border-color: var(--color-accent-hover) !important;
}

/* OUTLINE BUTTONS - Semantic Tokenization */
html body button.btn.btn--outline[class],
html body button.btn.btn--outline.btn--sm[class] {
    background-color: transparent !important;
    background: transparent !important;
    color: var(--component-bg-color-primary) !important;
    border: 2px solid var(--component-bg-color-primary) !important;
    transition: all 0.3s ease !important;
}

html body button.btn.btn--outline[class]:hover,
html body button.btn.btn--outline.btn--sm[class]:hover {
    background-color: var(--component-bg-color-primary) !important;
    color: var(--component-text-color-primary) !important;
}

/* GHOST BUTTONS - Semantic Tokenization */
html body button.btn.btn--ghost[class] {
    background-color: transparent !important;
    color: var(--component-bg-color-primary) !important;
    border: none !important;
    transition: all 0.3s ease !important;
}

html body button.btn.btn--ghost[class]:hover {
    background-color: var(--color-primary-focus) !important;
    color: var(--component-text-color-primary) !important;
}

/* GENERIC BUTTON FALLBACKS - Semantic Tokenization */
button:not([class*='wp-']):not([class*='admin']),
.btn:not([class*='wp-']),
input[type='submit']:not([class*='wp-']) {
    background-color: var(--component-bg-color-primary) !important;
    color: var(--component-text-color-primary) !important;
    border-color: var(--component-border-color-primary) !important;
}

button:not([class*='wp-']):not([class*='admin']):hover,
.btn:not([class*='wp-']):hover,
input[type='submit']:not([class*='wp-']):hover {
    background-color: var(--color-primary-hover) !important;
    border-color: var(--color-primary-hover) !important;
}
";

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
 * Generate CSS from typography data with DESIGN TOKEN SYSTEM
 */
function generate_css_from_typography_data($typography_data) {
    if (!is_array($typography_data) || !isset($typography_data['headingFont']) || !isset($typography_data['bodyFont'])) {
        return '';
    }

    $heading_family = '"' . $typography_data['headingFont']['family'] . '", ' . $typography_data['headingFont']['fallback'];
    $body_family = '"' . $typography_data['bodyFont']['family'] . '", ' . $typography_data['bodyFont']['fallback'];

    $heading_weights = $typography_data['headingFont']['weights'] ?? [400, 500, 600, 700];
    $body_weights = $typography_data['bodyFont']['weights'] ?? [400, 500, 600];

    $css = "/* SERVER-SIDE TYPOGRAPHY TOKENIZATION - {$typography_data['name']} */\n";
    $css .= "body:root, html:root {\n";

    // Foundation Typography Tokens
    $css .= "    --typography-heading-family: {$heading_family} !important;\n";
    $css .= "    --typography-body-family: {$body_family} !important;\n";
    $css .= "    --typography-heading-weight-normal: " . ($heading_weights[0] ?? 400) . " !important;\n";
    $css .= "    --typography-heading-weight-medium: " . ($heading_weights[1] ?? 500) . " !important;\n";
    $css .= "    --typography-heading-weight-bold: " . ($heading_weights[2] ?? 600) . " !important;\n";
    $css .= "    --typography-body-weight-normal: " . ($body_weights[0] ?? 400) . " !important;\n";
    $css .= "    --typography-body-weight-medium: " . ($body_weights[1] ?? 500) . " !important;\n";

    // Component Tokens - Typography (for system consistency)
    $css .= "    --component-font-family-primary: var(--typography-heading-family) !important;\n";
    $css .= "    --component-font-family-secondary: var(--typography-body-family) !important;\n";
    $css .= "    --component-font-weight-heading: var(--typography-heading-weight-bold) !important;\n";
    $css .= "    --component-font-weight-subheading: var(--typography-heading-weight-medium) !important;\n";
    $css .= "    --component-font-weight-body: var(--typography-body-weight-normal) !important;\n";
    $css .= "    --component-font-weight-accent: var(--typography-body-weight-medium) !important;\n";

    // Semantic Typography Roles
    $css .= "    --font-family-primary: var(--typography-heading-family) !important;\n";
    $css .= "    --font-family-secondary: var(--typography-body-family) !important;\n";
    $css .= "    --font-weight-heading: var(--typography-heading-weight-bold) !important;\n";
    $css .= "    --font-weight-subheading: var(--typography-heading-weight-medium) !important;\n";
    $css .= "    --font-weight-body: var(--typography-body-weight-normal) !important;\n";

    $css .= "}\n\n";

    // HIGH SPECIFICITY APPLICATION RULES - Ensures persistence after reload
    $css .= "/* HIGH SPECIFICITY TYPOGRAPHY RULES - Server-side enforcement */\n";

    // Headings with maximum specificity
    $css .= "html body h1, html body h2, html body h3, html body h4, html body h5, html body h6,\n";
    $css .= "html body .heading[class], html body .title[class], html body .site-title[class],\n";
    $css .= "html body .hero-title[class], html body .section-title[class] {\n";
    $css .= "    font-family: var(--component-font-family-primary) !important;\n";
    $css .= "}\n\n";

    // Body text with maximum specificity
    $css .= "html body, html body p, html body div, html body span, html body a, html body li, html body td, html body th,\n";
    $css .= "html body .body-text[class], html body .content[class], html body .description[class] {\n";
    $css .= "    font-family: var(--component-font-family-secondary) !important;\n";
    $css .= "}\n\n";

    // Specific weight applications with high specificity
    $css .= "html body h1[class], html body .hero-title[class] {\n";
    $css .= "    font-family: var(--component-font-family-primary) !important;\n";
    $css .= "    font-weight: var(--component-font-weight-heading) !important;\n";
    $css .= "}\n\n";

    $css .= "html body h2[class], html body h3[class], html body .section-title[class] {\n";
    $css .= "    font-family: var(--component-font-family-primary) !important;\n";
    $css .= "    font-weight: var(--component-font-weight-subheading) !important;\n";
    $css .= "}\n\n";

    $css .= "html body h4[class], html body h5[class], html body h6[class] {\n";
    $css .= "    font-family: var(--component-font-family-primary) !important;\n";
    $css .= "    font-weight: var(--component-font-weight-heading) !important;\n";
    $css .= "}\n\n";

    $css .= "html body, html body p[class], html body div:not([class*='wp-']), html body span:not([class*='wp-']) {\n";
    $css .= "    font-family: var(--component-font-family-secondary) !important;\n";
    $css .= "    font-weight: var(--component-font-weight-body) !important;\n";
    $css .= "}\n\n";

    // Navigation and UI Elements with server-side enforcement
    $css .= "html body .nav-menu[class], html body .menu-item[class] a {\n";
    $css .= "    font-family: var(--component-font-family-primary) !important;\n";
    $css .= "    font-weight: var(--component-font-weight-subheading) !important;\n";
    $css .= "}\n\n";

    $css .= "html body button[class], html body .btn[class], html body input[type=\"submit\"][class] {\n";
    $css .= "    font-family: var(--component-font-family-primary) !important;\n";
    $css .= "    font-weight: var(--component-font-weight-accent) !important;\n";
    $css .= "}\n\n";

    // Medical Spa Theme Specific Elements
    $css .= "/* Medical Spa Theme Specific Typography */\n";
    $css .= "html body .professional-header[class] .site-title[class],\n";
    $css .= "html body .professional-header[class] .nav-menu[class] a {\n";
    $css .= "    font-family: var(--component-font-family-primary) !important;\n";
    $css .= "    font-weight: var(--component-font-weight-subheading) !important;\n";
    $css .= "}\n\n";

    $css .= "html body .luxury-footer[class] {\n";
    $css .= "    font-family: var(--component-font-family-secondary) !important;\n";
    $css .= "}\n\n";

    // Treatment and Content Areas
    $css .= "html body .treatment-card[class] h3,\n";
    $css .= "html body .treatment-card[class] .treatment-title[class] {\n";
    $css .= "    font-family: var(--component-font-family-primary) !important;\n";
    $css .= "    font-weight: var(--component-font-weight-heading) !important;\n";
    $css .= "}\n\n";

    $css .= "html body .treatment-card[class] p,\n";
    $css .= "html body .treatment-card[class] .treatment-description[class] {\n";
    $css .= "    font-family: var(--component-font-family-secondary) !important;\n";
    $css .= "    font-weight: var(--component-font-weight-body) !important;\n";
    $css .= "}\n\n";

    return $css;
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

/**
 * PRODUCTION FIX: AJAX Handler - Get Current Palette
 */
function handle_get_current_palette() {
    // Security check
    if (!wp_verify_nonce($_POST['nonce'], 'simple_visual_customizer')) {
        wp_send_json_error(['message' => 'Security check failed']);
    }

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Insufficient permissions']);
    }

    try {
        // Get current palette from database
        $current_palette = get_option('preetidreams_active_palette', 'modern-clinical');

        // Also check theme mods
        $theme_mod_palette = get_theme_mod('visual_customizer_active_palette', '');

        // Use theme mod if available, otherwise use option
        $palette_to_return = !empty($theme_mod_palette) ? $theme_mod_palette : $current_palette;

        wp_send_json_success($palette_to_return);

    } catch (Exception $e) {
        wp_send_json_error([
            'message' => 'Error getting current palette: ' . $e->getMessage()
        ]);
    }
}
add_action('wp_ajax_get_current_palette', 'handle_get_current_palette');

/**
 * URL Parameter Test - Verify JavaScript Respects Server CSS
 */
function handle_javascript_server_respect_test() {
    if (!isset($_GET['test_js_server_respect']) || !current_user_can('manage_options')) {
        return;
    }

    add_action('wp_head', function() {
        ?>
        <script>
        console.log('🧪 TESTING: JavaScript Server CSS Respect');

        // Wait for page load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                console.log('🔍 Checking server vs client CSS status...');

                const rootStyle = getComputedStyle(document.documentElement);

                // Check server CSS variables
                const serverVars = {
                    '--component-bg-color-primary': rootStyle.getPropertyValue('--component-bg-color-primary').trim(),
                    '--color-primary': rootStyle.getPropertyValue('--color-primary').trim(),
                    '--component-bg-color-secondary': rootStyle.getPropertyValue('--component-bg-color-secondary').trim(),
                    '--color-secondary': rootStyle.getPropertyValue('--color-secondary').trim()
                };

                console.log('🖥️ Server CSS Variables:', serverVars);

                // Check if any are set
                const serverHasVars = Object.values(serverVars).some(val => val !== '');

                if (serverHasVars) {
                    console.log('✅ SERVER CSS VARIABLES ACTIVE');
                    console.log('✅ JavaScript should respect these and NOT override');

                    // Test if JavaScript tries to override
                    const originalPrimary = serverVars['--component-bg-color-primary'];

                    // Store original
                    console.log('🎯 Original server primary:', originalPrimary);

                    // Check if it gets overridden by JavaScript
                    setTimeout(() => {
                        const afterJSPrimary = getComputedStyle(document.documentElement).getPropertyValue('--component-bg-color-primary').trim();

                        if (originalPrimary === afterJSPrimary) {
                            console.log('✅ SUCCESS: JavaScript RESPECTED server CSS variables');
                            console.log('✅ No override occurred - fix is working!');

                            // Show success message
                            document.body.innerHTML += `
                                <div style="position: fixed; top: 20px; left: 20px; background: #d4af37; color: #000; padding: 15px; border-radius: 8px; z-index: 9999; font-family: Arial;">
                                    ✅ SUCCESS: JavaScript respects server CSS!<br>
                                    Server color preserved: ${originalPrimary}
                                </div>
                            `;
                        } else {
                            console.log('❌ FAILURE: JavaScript OVERRODE server CSS variables');
                            console.log(`❌ ${originalPrimary} → ${afterJSPrimary}`);

                            // Show failure message
                            document.body.innerHTML += `
                                <div style="position: fixed; top: 20px; left: 20px; background: #ef4444; color: #fff; padding: 15px; border-radius: 8px; z-index: 9999; font-family: Arial;">
                                    ❌ FAILURE: JavaScript overrode server CSS<br>
                                    ${originalPrimary} → ${afterJSPrimary}
                                </div>
                            `;
                        }
                    }, 2000);

                } else {
                    console.log('⚠️ NO SERVER CSS VARIABLES DETECTED');
                    console.log('⚠️ JavaScript fallbacks should activate');

                    document.body.innerHTML += `
                        <div style="position: fixed; top: 20px; left: 20px; background: #f59e0b; color: #000; padding: 15px; border-radius: 8px; z-index: 9999; font-family: Arial;">
                            ⚠️ NO SERVER CSS DETECTED<br>
                            JavaScript fallbacks should activate
                        </div>
                    `;
                }

            }, 1000);
        });
        </script>
        <?php
    });
}
add_action('init', 'handle_javascript_server_respect_test');

/**
 * URL Parameter Handler for Cache Clear and Sync
 */
function handle_cache_clear_and_sync() {
    if (!isset($_GET['clear_cache_sync']) || !current_user_can('manage_options')) {
        return;
    }

    // Clear all potential localStorage conflicts
    add_action('wp_head', function() {
        ?>
        <script>
        console.log('🧹 CACHE CLEAR & SYNC: Starting localStorage cleanup...');

        // Clear ALL conflicting localStorage keys
        const conflictingKeys = [
            'visual_customizer_current_palette',
            'visual_customizer_settings',
            'medspaa_visual_customizer',
            'medSpa_colorSystem_settings',
            'preetidreams_visual_customizer_settings',
            'visual_customizer_temp_settings'
        ];

        conflictingKeys.forEach(key => {
            localStorage.removeItem(key);
            console.log(`🗑️ Removed localStorage: ${key}`);
        });

        // Get server palette and force sync
        const serverPalette = '<?php echo esc_js(get_option('preetidreams_active_palette', 'medical-clean')); ?>';
        console.log('🖥️ Server palette:', serverPalette);

        // Set the correct palette in localStorage
        const consolidatedSettings = {
            currentPalette: serverPalette,
            timestamp: Date.now(),
            source: 'cache_clear_sync',
            forcedSync: true
        };

        localStorage.setItem('visual_customizer_settings', JSON.stringify(consolidatedSettings));
        console.log('✅ Set consolidated localStorage:', consolidatedSettings);

        // Force reload after 2 seconds to apply changes
        setTimeout(() => {
            console.log('🔄 Reloading page to apply synced settings...');
            window.location.href = window.location.pathname;
        }, 2000);

        // Show user feedback
        document.body.innerHTML = `
            <div style="padding: 40px; font-family: Arial, sans-serif; text-align: center; background: #f0f0f0;">
                <h1>🧹 Cache Clear & Sync</h1>
                <p>✅ localStorage cleared</p>
                <p>✅ Server palette: <strong>${serverPalette}</strong></p>
                <p>🔄 Page will reload automatically...</p>
            </div>
        `;
        </script>
        <?php
    });
}
add_action('init', 'handle_cache_clear_and_sync');

/**
 * NUCLEAR Cache Clear - Complete Reset
 */
function handle_nuclear_cache_clear() {
    if (!isset($_GET['nuclear_cache_clear']) || !current_user_can('manage_options')) {
        return;
    }

    // Override the template completely
    add_filter('template_include', function() {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>🧨 Nuclear Cache Clear</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 40px; text-align: center; background: #f0f0f0; }
                .step { margin: 20px 0; padding: 15px; background: #fff; border-radius: 8px; }
                .success { color: green; }
                .error { color: red; }
                .warning { color: orange; }
            </style>
        </head>
        <body>

        <h1>🧨 Nuclear Cache Clear in Progress</h1>

        <div class="step">
            <h3>Step 1: Complete localStorage Wipe</h3>
            <p id="localStorage-status">Processing...</p>
        </div>

        <div class="step">
            <h3>Step 2: Server Palette Sync</h3>
            <p><strong>Server Palette:</strong> <?php echo esc_html(get_option('preetidreams_active_palette', 'NOT SET')); ?></p>
            <p><strong>Server Config Exists:</strong> <?php echo !empty(get_option('preetidreams_visual_customizer_config')) ? 'YES' : 'NO'; ?></p>
        </div>

        <div class="step">
            <h3>Step 3: Force Hard Reload</h3>
            <p id="reload-status">Preparing...</p>
        </div>

        <script>
        console.log('🧨 NUCLEAR CACHE CLEAR: Starting complete reset...');

        // STEP 1: Completely wipe ALL browser storage
        try {
            // Clear localStorage completely
            localStorage.clear();
            console.log('✅ localStorage cleared');

            // Clear sessionStorage
            sessionStorage.clear();
            console.log('✅ sessionStorage cleared');

            // Clear any cached data
            if ('caches' in window) {
                caches.keys().then(cacheNames => {
                    cacheNames.forEach(cacheName => {
                        caches.delete(cacheName);
                    });
                    console.log('✅ Browser caches cleared');
                });
            }

            document.getElementById('localStorage-status').innerHTML = '<span class="success">✅ All browser storage cleared</span>';
        } catch (e) {
            console.error('❌ Error clearing storage:', e);
            document.getElementById('localStorage-status').innerHTML = '<span class="error">❌ Storage clear failed</span>';
        }

        // STEP 2: Force set correct server palette
        const serverPalette = '<?php echo esc_js(get_option('preetidreams_active_palette', 'medical-clean')); ?>';

        // Set the ONLY localStorage entry we want
        const nuclearSyncSettings = {
            currentPalette: serverPalette,
            timestamp: Date.now(),
            source: 'nuclear_cache_clear',
            nuclearSync: true,
            clearAttempt: Date.now()
        };

        localStorage.setItem('visual_customizer_settings', JSON.stringify(nuclearSyncSettings));
        console.log('✅ Nuclear sync settings:', nuclearSyncSettings);

        // STEP 3: Force hard reload with cache bypass
        document.getElementById('reload-status').innerHTML = '<span class="warning">🔄 Forcing hard reload in 3 seconds...</span>';

        setTimeout(() => {
            console.log('🔄 Executing nuclear reload...');

            // Force hard reload with cache bypass
            window.location.href = window.location.origin + window.location.pathname + '?t=' + Date.now() + '&cache_bust=' + Math.random();
        }, 3000);

        // Visual countdown
        let countdown = 3;
        const countdownInterval = setInterval(() => {
            countdown--;
            if (countdown > 0) {
                document.getElementById('reload-status').innerHTML = `<span class="warning">🔄 Reloading in ${countdown} seconds...</span>`;
            } else {
                document.getElementById('reload-status').innerHTML = '<span class="success">🚀 Reloading now...</span>';
                clearInterval(countdownInterval);
            }
        }, 1000);
        </script>

        </body>
        </html>
        <?php
        exit;
    });
}
add_action('init', 'handle_nuclear_cache_clear');

/**
 * COMPREHENSIVE DIAGNOSTIC - Find ALL sources of CSS variable conflicts
 */
function handle_comprehensive_css_diagnostic() {
    if (!isset($_GET['comprehensive_css_diagnostic']) || !current_user_can('manage_options')) {
        return;
    }

    add_action('wp_head', function() {
        ?>
        <script>
        console.log('🔍 COMPREHENSIVE CSS DIAGNOSTIC STARTING...');

        // 1. CAPTURE INITIAL SERVER STATE
        document.addEventListener('DOMContentLoaded', function() {
            const initialDiagnostic = {
                timestamp: Date.now(),
                serverVars: {},
                appliedVars: {},
                loadedStylesheets: [],
                loadedScripts: [],
                conflicts: []
            };

            const rootStyle = getComputedStyle(document.documentElement);

            // Capture server CSS variables
            const criticalVars = [
                '--component-bg-color-primary',
                '--color-primary',
                '--component-bg-color-secondary',
                '--color-secondary',
                '--component-bg-color-accent',
                '--color-accent'
            ];

            criticalVars.forEach(varName => {
                initialDiagnostic.serverVars[varName] = rootStyle.getPropertyValue(varName).trim();
            });

            console.log('🖥️ INITIAL SERVER STATE:', initialDiagnostic.serverVars);

            // 2. CAPTURE ALL LOADED STYLESHEETS
            Array.from(document.styleSheets).forEach((sheet, index) => {
                try {
                    initialDiagnostic.loadedStylesheets.push({
                        index: index,
                        href: sheet.href || 'inline',
                        title: sheet.title || 'untitled',
                        disabled: sheet.disabled,
                        type: sheet.type,
                        media: sheet.media ? Array.from(sheet.media) : []
                    });
                } catch (e) {
                    initialDiagnostic.loadedStylesheets.push({
                        index: index,
                        href: 'CORS_BLOCKED',
                        error: e.message
                    });
                }
            });

            console.log('📋 LOADED STYLESHEETS:', initialDiagnostic.loadedStylesheets);

            // 3. CAPTURE ALL LOADED SCRIPTS
            Array.from(document.scripts).forEach((script, index) => {
                initialDiagnostic.loadedScripts.push({
                    index: index,
                    src: script.src || 'inline',
                    id: script.id || 'no-id',
                    type: script.type || 'text/javascript',
                    async: script.async,
                    defer: script.defer
                });
            });

            console.log('📜 LOADED SCRIPTS:', initialDiagnostic.loadedScripts);

            // 4. MONITOR CSS VARIABLE CHANGES IN REAL-TIME
            let changeCount = 0;
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' &&
                        (mutation.attributeName === 'style' || mutation.attributeName === 'class')) {

                        changeCount++;
                        const currentVars = {};
                        const currentStyle = getComputedStyle(document.documentElement);

                        criticalVars.forEach(varName => {
                            currentVars[varName] = currentStyle.getPropertyValue(varName).trim();
                        });

                        // Check for changes
                        let hasChanges = false;
                        criticalVars.forEach(varName => {
                            if (currentVars[varName] !== initialDiagnostic.serverVars[varName]) {
                                hasChanges = true;
                                console.log(`🔄 CSS VARIABLE CHANGED: ${varName}`);
                                console.log(`   Server: ${initialDiagnostic.serverVars[varName]}`);
                                console.log(`   Current: ${currentVars[varName]}`);
                                console.log(`   Change #${changeCount} at:`, Date.now());
                                console.log(`   Stack trace:`, new Error().stack);
                            }
                        });

                        if (hasChanges) {
                            initialDiagnostic.conflicts.push({
                                changeNumber: changeCount,
                                timestamp: Date.now(),
                                target: mutation.target.tagName + (mutation.target.id ? '#' + mutation.target.id : ''),
                                attributeName: mutation.attributeName,
                                beforeVars: initialDiagnostic.serverVars,
                                afterVars: currentVars,
                                stackTrace: new Error().stack
                            });
                        }
                    }
                });
            });

            // Start observing
            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['style', 'class'],
                subtree: true
            });

            console.log('👁️ CSS MUTATION OBSERVER STARTED');

            // 5. CHECK FOR SPECIFIC JAVASCRIPT INTERFERENCE
            setTimeout(() => {
                console.log('🔍 CHECKING FOR JAVASCRIPT INTERFERENCE...');

                // Check window object for color-related properties
                const windowColorProps = [];
                for (let prop in window) {
                    if (prop.includes('color') || prop.includes('Color') || prop.includes('palette') || prop.includes('Palette')) {
                        windowColorProps.push({
                            property: prop,
                            type: typeof window[prop],
                            value: window[prop]
                        });
                    }
                }

                console.log('🔍 Window color properties:', windowColorProps);

                // Check for common color system objects
                const colorSystems = [
                    'SemanticColorSystem',
                    'ColorSystemManager',
                    'ColorPaletteInterface',
                    'simpleVisualCustomizer',
                    'livePreviewSystem'
                ];

                colorSystems.forEach(systemName => {
                    if (window[systemName]) {
                        console.log(`🔍 Found color system: ${systemName}`, window[systemName]);

                        // Check if it has a current palette
                        if (typeof window[systemName].getCurrentPalette === 'function') {
                            try {
                                const currentPalette = window[systemName].getCurrentPalette();
                                console.log(`🔍 ${systemName} current palette:`, currentPalette);
                            } catch (e) {
                                console.log(`🔍 ${systemName} getCurrentPalette error:`, e);
                            }
                        }
                    }
                });

                // 6. CHECK LOCALSTORAGE FOR CONFLICTS
                console.log('🔍 CHECKING LOCALSTORAGE...');
                for (let i = 0; i < localStorage.length; i++) {
                    const key = localStorage.key(i);
                    if (key.includes('color') || key.includes('palette') || key.includes('customizer')) {
                        try {
                            const value = localStorage.getItem(key);
                            const parsed = JSON.parse(value);
                            console.log(`🔍 localStorage[${key}]:`, parsed);
                        } catch (e) {
                            console.log(`🔍 localStorage[${key}]:`, localStorage.getItem(key));
                        }
                    }
                }

            }, 2000);

            // 7. FINAL DIAGNOSTIC AFTER EVERYTHING LOADS
            setTimeout(() => {
                console.log('📊 FINAL DIAGNOSTIC SUMMARY:');

                const finalVars = {};
                const finalStyle = getComputedStyle(document.documentElement);

                criticalVars.forEach(varName => {
                    finalVars[varName] = finalStyle.getPropertyValue(varName).trim();
                });

                console.log('🖥️ Server CSS (initial):', initialDiagnostic.serverVars);
                console.log('🎨 Applied CSS (final):', finalVars);
                console.log('⚠️ Total conflicts detected:', initialDiagnostic.conflicts.length);
                console.log('📋 All conflicts:', initialDiagnostic.conflicts);

                // Show visual summary
                const summaryDiv = document.createElement('div');
                summaryDiv.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: #000;
                    color: #fff;
                    padding: 20px;
                    border-radius: 8px;
                    z-index: 999999;
                    font-family: monospace;
                    font-size: 12px;
                    max-width: 400px;
                    max-height: 80vh;
                    overflow-y: auto;
                `;

                let summaryHTML = '<h3>🔍 CSS Diagnostic Summary</h3>';
                summaryHTML += `<p><strong>Conflicts:</strong> ${initialDiagnostic.conflicts.length}</p>`;
                summaryHTML += `<p><strong>Stylesheets:</strong> ${initialDiagnostic.loadedStylesheets.length}</p>`;
                summaryHTML += `<p><strong>Scripts:</strong> ${initialDiagnostic.loadedScripts.length}</p>`;

                summaryHTML += '<h4>Server vs Applied:</h4>';
                criticalVars.forEach(varName => {
                    const server = initialDiagnostic.serverVars[varName];
                    const applied = finalVars[varName];
                    const match = server === applied;
                    summaryHTML += `<div style="color: ${match ? '#0f0' : '#f00'}">
                        ${varName}:<br>
                        Server: ${server}<br>
                        Applied: ${applied}
                    </div><br>`;
                });

                summaryDiv.innerHTML = summaryHTML;
                document.body.appendChild(summaryDiv);

                // Store for external access
                window.cssExtraDiagnostic = {
                    initial: initialDiagnostic,
                    final: finalVars,
                    conflicts: initialDiagnostic.conflicts
                };

            }, 5000);

        });
        </script>
        <?php
    });
}
add_action('init', 'handle_comprehensive_css_diagnostic');

/**
 * AJAX handler to get current typography
 */
function handle_get_current_typography() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'simple_visual_customizer_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }

    // Get current typography from WordPress options
    $current_typography = get_option('preetidreams_active_typography', '');

    // If no typography is set, try to detect from configuration
    if (empty($current_typography)) {
        $config = get_option('preetidreams_visual_customizer_config', '{}');
        if (!empty($config)) {
            $config_data = json_decode($config, true);
            if (isset($config_data['activeTypography'])) {
                $current_typography = $config_data['activeTypography'];
            } elseif (isset($config_data['typographyPairing'])) {
                $current_typography = $config_data['typographyPairing'];
            }
        }
    }

    // If still no typography, try legacy options
    if (empty($current_typography)) {
        $current_typography = get_option('medspaa_current_typography', '');
    }

    // Return the current typography
    if (!empty($current_typography)) {
        wp_send_json_success($current_typography);
    } else {
        wp_send_json_success('medical-professional'); // Default fallback
    }
}
add_action('wp_ajax_get_current_typography', 'handle_get_current_typography');
add_action('wp_ajax_nopriv_get_current_typography', 'handle_get_current_typography');
