<?php
/**
 * Simple Visual Customizer - Admin Bar Implementation
 * Adds a clean admin bar menu that opens a sidebar with color customization
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
            'title' => 'Open Visual Customizer'
        ]
    ]);
}
add_action('admin_bar_menu', 'simple_visual_customizer_admin_bar', 100);

/**
 * Enqueue Simple Visual Customizer Assets (Frontend + Admin)
 */
function enqueue_simple_visual_customizer_assets() {
    // Only for admins
    if (!current_user_can('manage_options')) {
        return;
    }

    // Simple CSS
    wp_enqueue_style(
        'simple-visual-customizer',
        get_template_directory_uri() . '/assets/css/simple-visual-customizer.css',
        [],
        PREETIDREAMS_VERSION
    );

    // Simple JavaScript
    wp_enqueue_script(
        'simple-visual-customizer',
        get_template_directory_uri() . '/assets/js/simple-visual-customizer.js',
        ['jquery'],
        PREETIDREAMS_VERSION,
        true
    );

    // Existing color system components
    wp_enqueue_script(
        'semantic-color-system',
        get_template_directory_uri() . '/assets/js/semantic-color-system.js',
        ['jquery'],
        PREETIDREAMS_VERSION,
        true
    );

    wp_enqueue_script(
        'color-system-manager',
        get_template_directory_uri() . '/assets/js/color-system-manager.js',
        ['semantic-color-system'],
        PREETIDREAMS_VERSION,
        true
    );

    wp_enqueue_script(
        'color-palette-interface',
        get_template_directory_uri() . '/assets/js/color-palette-interface.js',
        ['color-system-manager'],
        PREETIDREAMS_VERSION,
        true
    );

    // Localize script
    wp_localize_script('simple-visual-customizer', 'simpleCustomizer', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('simple_visual_customizer'),
        'isAdmin' => current_user_can('manage_options'),
        'currentPalette' => get_option('preetidreams_active_palette', 'medical-clean')
    ]);
}
add_action('wp_enqueue_scripts', 'enqueue_simple_visual_customizer_assets');
add_action('admin_enqueue_scripts', 'enqueue_simple_visual_customizer_assets');

/**
 * Add Simple Visual Customizer HTML to Footer (Admins Only)
 */
function simple_visual_customizer_html() {
    // Only for admins
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <!-- Simple Visual Customizer Sidebar -->
    <div id="simple-visual-customizer-sidebar" class="simple-vc-sidebar">
        <div class="simple-vc-header">
            <h3>🎨 Visual Customizer</h3>
            <button id="simple-vc-close" class="simple-vc-close">&times;</button>
        </div>

        <div class="simple-vc-content">
            <div class="simple-vc-section">
                <h4>Color Palette</h4>
                <div id="simple-color-palette-container">
                    <p class="loading">Loading color system...</p>
                </div>
            </div>

            <div class="simple-vc-section">
                <h4>Quick Actions</h4>
                <button id="simple-vc-apply" class="simple-vc-btn primary">Apply Changes</button>
                <button id="simple-vc-reset" class="simple-vc-btn secondary">Reset</button>
            </div>
        </div>

        <div class="simple-vc-footer">
            <small>Admin-only visual customization</small>
        </div>
    </div>

    <!-- Overlay -->
    <div id="simple-vc-overlay" class="simple-vc-overlay"></div>
    <?php
}
add_action('wp_footer', 'simple_visual_customizer_html');
add_action('admin_footer', 'simple_visual_customizer_html');

/**
 * Handle AJAX Apply Changes
 */
function handle_simple_visual_customizer_apply() {
    // Security check
    if (!wp_verify_nonce($_POST['nonce'], 'simple_visual_customizer')) {
        wp_send_json_error('Security check failed');
    }

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Insufficient permissions');
    }

    // Get the configuration data
    $config = json_decode(stripslashes($_POST['config']), true);

    if (!$config) {
        wp_send_json_error('Invalid configuration data');
    }

    // Save the configuration
    update_option('preetidreams_visual_customizer_config', $config);
    update_option('preetidreams_active_palette', $config['activePalette'] ?? 'medical-clean');

    wp_send_json_success([
        'message' => 'Visual customizer settings applied successfully!',
        'config' => $config
    ]);
}
add_action('wp_ajax_simple_visual_customizer_apply', 'handle_simple_visual_customizer_apply');

/**
 * Handle AJAX Reset
 */
function handle_simple_visual_customizer_reset() {
    // Security check
    if (!wp_verify_nonce($_POST['nonce'], 'simple_visual_customizer')) {
        wp_send_json_error('Security check failed');
    }

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Insufficient permissions');
    }

    // Reset to defaults
    delete_option('preetidreams_visual_customizer_config');
    update_option('preetidreams_active_palette', 'medical-clean');

    wp_send_json_success([
        'message' => 'Visual customizer settings reset to defaults!',
        'config' => []
    ]);
}
add_action('wp_ajax_simple_visual_customizer_reset', 'handle_simple_visual_customizer_reset');

/**
 * Output Global Visual Customizer CSS for ALL Users
 * This ensures applied palette changes are visible to everyone, not just admins
 */
function output_global_visual_customizer_css() {
    // Get saved configuration
    $config = get_option('preetidreams_visual_customizer_config', null);

    if (!$config || !isset($config['paletteData'])) {
        // No custom palette applied, use default
        return;
    }

    $paletteData = $config['paletteData'];

    if (!isset($paletteData['colors']) || empty($paletteData['colors'])) {
        // No color data available
        return;
    }

    echo "\n<!-- Simple Visual Customizer Global CSS -->\n";
    echo '<style id="simple-vc-global-styles">';
    echo generate_palette_css_for_output($paletteData);
    echo '</style>';
    echo "\n<!-- End Simple Visual Customizer Global CSS -->\n";
}
add_action('wp_head', 'output_global_visual_customizer_css', 99);

/**
 * Generate CSS for Global Output (PHP version)
 */
function generate_palette_css_for_output($paletteData) {
    if (!isset($paletteData['colors']) || empty($paletteData['colors'])) {
        return '';
    }

    $css = "\n/* Simple Visual Customizer - Applied Palette: " . esc_html($paletteData['name'] ?? 'Custom') . " */\n";
    $css .= ":root {\n";

    // Generate CSS custom properties from palette colors
    foreach ($paletteData['colors'] as $role => $colorData) {
        $colorValue = isset($colorData['hex']) ? $colorData['hex'] : $colorData;
        $colorValue = sanitize_hex_color($colorValue);

        if ($colorValue) {
            $css .= "    --color-{$role}: {$colorValue};\n";
            $css .= "    --color-{$role}-rgb: " . hex_to_rgb_string($colorValue) . ";\n";
        }
    }

    $css .= "}\n\n";

    // Apply to medical spa theme elements
    $primaryColor = get_palette_color($paletteData, 'primary', '#87A96B');
    $secondaryColor = get_palette_color($paletteData, 'secondary', '#1B365D');
    $accentColor = get_palette_color($paletteData, 'accent', '#D4AF37');
    $surfaceColor = get_palette_color($paletteData, 'surface', '#FFFFFF');
    $backgroundColor = get_palette_color($paletteData, 'background', '#FDFCFA');

    $css .= "/* ===== MEDICAL SPA THEME INTEGRATION ===== */\n\n";

    $css .= "/* Professional Header */\n";
    $css .= ".professional-header {\n";
    $css .= "    background-color: {$primaryColor} !important;\n";
    $css .= "}\n\n";

    $css .= ".professional-header.scrolled {\n";
    $css .= "    background: " . hex_to_rgba($primaryColor, 0.95) . " !important;\n";
    $css .= "}\n\n";

    $css .= ".professional-header .nav-link,\n";
    $css .= ".professional-header .logo-fallback {\n";
    $css .= "    color: {$surfaceColor} !important;\n";
    $css .= "}\n\n";

    $css .= "/* Buttons */\n";
    $css .= ".btn-primary, .button-primary,\n";
    $css .= ".hero-discovery-btn,\n";
    $css .= ".consultation-booking-btn {\n";
    $css .= "    background-color: {$primaryColor} !important;\n";
    $css .= "    border-color: {$primaryColor} !important;\n";
    $css .= "    color: {$surfaceColor} !important;\n";
    $css .= "}\n\n";

    $css .= ".btn-secondary, .button-secondary {\n";
    $css .= "    background-color: {$secondaryColor} !important;\n";
    $css .= "    border-color: {$secondaryColor} !important;\n";
    $css .= "    color: {$surfaceColor} !important;\n";
    $css .= "}\n\n";

    $css .= "/* Hero Sections */\n";
    $css .= ".treatments-hero-luxury,\n";
    $css .= ".hero-content-luxury,\n";
    $css .= ".premium-hero {\n";
    $css .= "    background: linear-gradient(135deg, {$secondaryColor}, {$primaryColor}) !important;\n";
    $css .= "}\n\n";

    $css .= ".hero-title-main,\n";
    $css .= ".premium-hero .hero-title {\n";
    $css .= "    color: {$surfaceColor} !important;\n";
    $css .= "}\n\n";

    $css .= ".hero-title-accent {\n";
    $css .= "    color: {$accentColor} !important;\n";
    $css .= "}\n\n";

    $css .= "/* Treatment Cards */\n";
    $css .= ".modern-card {\n";
    $css .= "    border-color: {$primaryColor} !important;\n";
    $css .= "}\n\n";

    $css .= ".modern-card:hover {\n";
    $css .= "    border-color: {$accentColor} !important;\n";
    $css .= "    box-shadow: 0 8px 25px " . hex_to_rgba($primaryColor, 0.3) . " !important;\n";
    $css .= "}\n\n";

    $css .= "/* Typography */\n";
    $css .= ".section-title,\n";
    $css .= ".artistry-title {\n";
    $css .= "    color: {$secondaryColor} !important;\n";
    $css .= "}\n\n";

    $css .= ".text-primary {\n";
    $css .= "    color: {$primaryColor} !important;\n";
    $css .= "}\n\n";

    $css .= "/* Footer */\n";
    $css .= ".site-footer,\n";
    $css .= ".luxury-footer {\n";
    $css .= "    background-color: {$secondaryColor} !important;\n";
    $css .= "    color: {$surfaceColor} !important;\n";
    $css .= "}\n\n";

    return $css;
}

/**
 * Helper Functions for CSS Generation
 */
function get_palette_color($paletteData, $role, $default) {
    if (isset($paletteData['colors'][$role])) {
        $color = $paletteData['colors'][$role];
        return isset($color['hex']) ? $color['hex'] : $color;
    }
    return $default;
}

function hex_to_rgb_string($hex) {
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

function hex_to_rgba($hex, $alpha = 1) {
    $rgb = hex_to_rgb_string($hex);
    return "rgba({$rgb}, {$alpha})";
}
