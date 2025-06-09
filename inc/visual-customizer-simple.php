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
    check_ajax_referer('simple_visual_customizer', 'nonce');

    if (!current_user_can('edit_theme_options')) {
        wp_send_json_error(['message' => 'Insufficient permissions']);
        return;
    }

    $config = json_decode(stripslashes($_POST['config']), true);
    if (!$config) {
        wp_send_json_error(['message' => 'Invalid configuration data']);
        return;
    }

    // Save configuration globally
    update_option('preetidreams_visual_customizer_config', $config);

    $css_generated = false;
    $typography_css_generated = false;

    // Generate color palette CSS file (existing)
    if (isset($config['activePalette']) && isset($config['paletteData'])) {
        $active_palette = sanitize_text_field($config['activePalette']);
        update_option('preetidreams_active_palette', $active_palette);

        $css_generated = generate_global_css_from_config($config);
    }

    // CRITICAL FIX: Generate typography CSS file (NEW)
    if (isset($config['typographyPairing']) && isset($config['typographyData'])) {
        $active_typography = sanitize_text_field($config['typographyPairing']);
        update_option('preetidreams_active_typography', $active_typography);

        $typography_css_generated = generate_typography_css_file($config['typographyData']);
    }

    if ($css_generated || $typography_css_generated) {
        $response_data = [
            'message' => 'Settings applied successfully!',
            'saved_palette' => $config['activePalette'] ?? null,
            'saved_typography' => $config['typographyPairing'] ?? null,
            'css_generated' => $css_generated,
            'typography_css_generated' => $typography_css_generated,
            'timestamp' => current_time('mysql')
        ];

        wp_send_json_success($response_data);
    } else {
        wp_send_json_error(['message' => 'Failed to generate CSS files']);
    }
}

/**
 * NEW: Generate Typography CSS File (Similar to Color System)
 */
function generate_typography_css_file($typography_data) {
    if (!$typography_data || !isset($typography_data['id'])) {
        error_log('Typography CSS Generation: No typography data provided');
        return false;
    }

    try {
        // Create upload directory structure
        $upload_dir = wp_upload_dir();
        $css_dir = $upload_dir['basedir'] . '/medspatheme/typography/';

        if (!file_exists($css_dir)) {
            wp_mkdir_p($css_dir);
        }

        // Generate CSS content
        $css_content = generate_css_from_typography_data($typography_data);

        if (empty($css_content)) {
            error_log('Typography CSS Generation: Empty CSS content generated');
            return false;
        }

        // Create filename with timestamp for cache busting
        $timestamp = time();
        $filename = "typography-{$typography_data['id']}-v{$timestamp}.css";
        $filepath = $css_dir . $filename;

        // Write CSS file
        $result = file_put_contents($filepath, $css_content);

        if ($result === false) {
            error_log('Typography CSS Generation: Failed to write file to ' . $filepath);
            return false;
        }

        // Update option with file info (for enqueueing)
        $file_url = str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $filepath);

        update_option('preetidreams_typography_css_file', [
            'path' => $filepath,
            'url' => $file_url,
            'version' => $timestamp,
            'typography' => $typography_data['id'],
            'generated_at' => current_time('mysql'),
            'file_size' => filesize($filepath)
        ]);

        // Cleanup old typography files (keep only latest 3)
        cleanup_old_typography_files($css_dir);

        error_log('Typography CSS Generation: Successfully generated ' . $filename . ' (' . filesize($filepath) . ' bytes)');
        return true;

    } catch (Exception $e) {
        error_log('Typography CSS Generation Error: ' . $e->getMessage());
        return false;
    }
}

/**
 * NEW: Generate CSS from Typography Data
 */
function generate_css_from_typography_data($typography_data) {
    if (!$typography_data || !isset($typography_data['headingFont']) || !isset($typography_data['bodyFont'])) {
        return '';
    }

    $heading_family = '"' . $typography_data['headingFont']['family'] . '", ' . $typography_data['headingFont']['fallback'];
    $body_family = '"' . $typography_data['bodyFont']['family'] . '", ' . $typography_data['bodyFont']['fallback'];

    // Start with CSS variables
    $css = "/* Typography CSS File - {$typography_data['name']} */\n";
    $css .= "/* Generated: " . current_time('mysql') . " */\n\n";

    // CSS Custom Properties
    $css .= ":root {\n";
    $css .= "    --typography-heading-family: {$heading_family};\n";
    $css .= "    --typography-body-family: {$body_family};\n";
    $css .= "    --component-font-family-primary: {$heading_family};\n";
    $css .= "    --component-font-family-secondary: {$body_family};\n";
    $css .= "    --font-family-primary: {$heading_family};\n";
    $css .= "    --font-family-secondary: {$body_family};\n";

    // Font weights
    if (isset($typography_data['headingFont']['weights'])) {
        $weights = $typography_data['headingFont']['weights'];
        $css .= "    --font-weight-normal: " . ($weights[0] ?? '400') . ";\n";
        $css .= "    --font-weight-medium: " . ($weights[1] ?? '500') . ";\n";
        $css .= "    --font-weight-semibold: " . ($weights[2] ?? '600') . ";\n";
        $css .= "    --font-weight-bold: " . ($weights[3] ?? '700') . ";\n";
    }

    $css .= "}\n\n";

    // Import Google Fonts if available
    if (isset($typography_data['googleFonts']) && !empty($typography_data['googleFonts'])) {
        $fonts_query = implode('&family=', $typography_data['googleFonts']);
        $css .= "@import url('https://fonts.googleapis.com/css2?family={$fonts_query}&display=swap');\n\n";
    }

    // High specificity typography rules
    $css .= "/* Typography Rules with High Specificity */\n\n";

    // Heading elements
    $css .= "html body h1, html body h1[class],\n";
    $css .= "html body h2, html body h2[class],\n";
    $css .= "html body h3, html body h3[class],\n";
    $css .= "html body h4, html body h4[class],\n";
    $css .= "html body h5, html body h5[class],\n";
    $css .= "html body h6, html body h6[class],\n";
    $css .= "html body .heading, html body .heading[class],\n";
    $css .= "html body .title, html body .title[class],\n";
    $css .= "html body .site-title, html body .site-title[class],\n";
    $css .= "html body .hero-title, html body .hero-title[class],\n";
    $css .= "html body .section-title, html body .section-title[class] {\n";
    $css .= "    font-family: {$heading_family} !important;\n";
    $css .= "    transition: font-family 0.3s ease !important;\n";
    $css .= "}\n\n";

    // Body elements
    $css .= "html body, html body[class],\n";
    $css .= "html body p, html body p[class],\n";
    $css .= "html body div:not([class*='wp-']), html body div[class]:not([class*='wp-']),\n";
    $css .= "html body span:not([class*='wp-']), html body span[class]:not([class*='wp-']),\n";
    $css .= "html body a:not([class*='wp-']), html body a[class]:not([class*='wp-']),\n";
    $css .= "html body li, html body li[class],\n";
    $css .= "html body td, html body td[class],\n";
    $css .= "html body th, html body th[class],\n";
    $css .= "html body .body-text, html body .body-text[class],\n";
    $css .= "html body .content, html body .content[class],\n";
    $css .= "html body .description, html body .description[class] {\n";
    $css .= "    font-family: {$body_family} !important;\n";
    $css .= "    transition: font-family 0.3s ease !important;\n";
    $css .= "}\n\n";

    // Navigation and buttons
    $css .= "html body .nav-menu a, html body .nav-menu[class] a,\n";
    $css .= "html body .menu-item a, html body .menu-item[class] a,\n";
    $css .= "html body button, html body button[class],\n";
    $css .= "html body .btn, html body .btn[class],\n";
    $css .= "html body input[type=\"submit\"], html body input[type=\"submit\"][class] {\n";
    $css .= "    font-family: {$heading_family} !important;\n";
    $css .= "    transition: font-family 0.3s ease !important;\n";
    $css .= "}\n\n";

    // Medical spa specific elements
    $css .= "/* Medical Spa Theme Specific */\n";
    $css .= "html body .professional-header .site-title,\n";
    $css .= "html body .professional-header[class] .site-title[class],\n";
    $css .= "html body .professional-header .nav-menu a,\n";
    $css .= "html body .professional-header[class] .nav-menu[class] a {\n";
    $css .= "    font-family: {$heading_family} !important;\n";
    $css .= "}\n\n";

    $css .= "html body .luxury-footer, html body .luxury-footer[class] {\n";
    $css .= "    font-family: {$body_family} !important;\n";
    $css .= "}\n\n";

    // Treatment cards
    $css .= "html body .treatment-card h3, html body .treatment-card[class] h3,\n";
    $css .= "html body .treatment-card .treatment-title, html body .treatment-card[class] .treatment-title[class] {\n";
    $css .= "    font-family: {$heading_family} !important;\n";
    $css .= "}\n\n";

    $css .= "html body .treatment-card p, html body .treatment-card[class] p,\n";
    $css .= "html body .treatment-card .treatment-description, html body .treatment-card[class] .treatment-description[class] {\n";
    $css .= "    font-family: {$body_family} !important;\n";
    $css .= "}\n\n";

    // Force override common theme elements
    $css .= "html body .entry-title, html body .entry-title[class],\n";
    $css .= "html body .post-title, html body .post-title[class],\n";
    $css .= "html body .page-title, html body .page-title[class] {\n";
    $css .= "    font-family: {$heading_family} !important;\n";
    $css .= "}\n\n";

    $css .= "html body .entry-content, html body .entry-content[class],\n";
    $css .= "html body .post-content, html body .post-content[class],\n";
    $css .= "html body .page-content, html body .page-content[class] {\n";
    $css .= "    font-family: {$body_family} !important;\n";
    $css .= "}\n\n";

    return $css;
}

/**
 * NEW: Cleanup old typography files
 */
function cleanup_old_typography_files($css_dir) {
    if (!is_dir($css_dir)) {
        return;
    }

    $files = glob($css_dir . 'typography-*.css');
    if (count($files) > 3) {
        // Sort by modification time (oldest first)
        usort($files, function($a, $b) {
            return filemtime($a) - filemtime($b);
        });

        // Remove all but the latest 3
        $files_to_remove = array_slice($files, 0, -3);
        foreach ($files_to_remove as $file) {
            unlink($file);
            error_log('Cleanup: Removed old typography file ' . basename($file));
        }
    }
}

/**
 * NEW: Enqueue typography CSS file
 */
function enqueue_typography_css() {
    $typography_file = get_option('preetidreams_typography_css_file');

    if ($typography_file && isset($typography_file['url']) && file_exists($typography_file['path'])) {
        wp_enqueue_style(
            'preetidreams-typography',
            $typography_file['url'],
            ['medspatheme-style'],
            $typography_file['version']
        );

        error_log('Typography CSS: Enqueued ' . $typography_file['url']);
    } else {
        // Fallback to inline typography if file doesn't exist
        enqueue_inline_typography_fallback();
    }
}

/**
 * NEW: Fallback inline typography
 */
function enqueue_inline_typography_fallback() {
    $active_typography = get_option('preetidreams_active_typography');
    if ($active_typography) {
        error_log('Typography CSS: Using inline fallback for ' . $active_typography);

        // You could add basic inline typography here as fallback
        $basic_css = "/* Typography fallback */";
        wp_add_inline_style('medspatheme-style', $basic_css);
    }
}

// Hook typography CSS enqueuing
add_action('wp_enqueue_scripts', 'enqueue_typography_css', 15);

/**
 * NEW: AJAX handler for getting current typography
 */
function handle_get_current_typography() {
    check_ajax_referer('simple_visual_customizer', 'nonce');

    $current_typography = get_option('preetidreams_active_typography', 'medical-professional');
    wp_send_json_success($current_typography);
}
add_action('wp_ajax_get_current_typography', 'handle_get_current_typography');
add_action('wp_ajax_nopriv_get_current_typography', 'handle_get_current_typography');

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

    // CRITICAL FIX: Improved typography detection logic
    $has_typography = false;

    // FIXED: Load typography from main config (like colors do)
    if (!empty($config) && isset($config['typographyData']) && isset($config['typographyPairing'])) {
        $typography_data = $config['typographyData'];  // ← From main config
        $active_typography = $config['typographyPairing'];
        $has_typography = true;

        // Debug: Log successful typography loading from main config
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log("Visual Customizer: Typography loaded from main config - {$active_typography}");
        }
    } elseif (!empty($typography_data) && !empty($active_typography)) {
        // Fallback: separate options (legacy support)
        $has_typography = true;

        // Debug: Log fallback usage
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log("Visual Customizer: Using typography from separate options - {$active_typography}");
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
