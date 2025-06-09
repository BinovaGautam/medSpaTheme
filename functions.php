<?php

/**
 * PreetiDreams Medical Spa Theme
 *
 * @package PreetiDreams
 * @author PreetiDreams Development Team
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme version
define('PREETIDREAMS_VERSION', '1.0.0');

// =============================================================================
// SPRINT 6: CUSTOMIZABLE COMPONENT ARCHITECTURE SYSTEM
// =============================================================================

// Load Component Architecture Foundation (T6.1 - Component Base Architecture)
require_once get_template_directory() . '/inc/components/base-component.php';
require_once get_template_directory() . '/inc/components/component-registry.php';
require_once get_template_directory() . '/inc/components/component-factory.php';
require_once get_template_directory() . '/inc/components/component-auto-loader.php';
require_once get_template_directory() . '/inc/components/button-component.php';

// Load demo component for testing (will be replaced with production components)
require_once get_template_directory() . '/inc/components/demo-button-component.php';

// Initialize Component System
add_action('after_setup_theme', function() {
    // Component Registry is auto-initialized
    // Component Factory helper functions are available globally

    // Enable component performance tracking in debug mode
    if (defined('WP_DEBUG') && WP_DEBUG) {
        add_action('wp_footer', function() {
            $metrics = ComponentRegistry::get_performance_metrics();
            if (!empty($metrics)) {
                echo "<!-- Component Performance Summary -->\n";
            }
        });
    }

    // Hook for future component auto-registration
    do_action('medspa_components_init');
}, 5); // Priority 5 to load before other theme setup

// Component management dashboard
if (is_admin()) {
    require_once get_template_directory() . '/inc/admin/component-dashboard.php';
}

// Initialize component system with auto-discovery
add_action('after_setup_theme', 'medspa_components_init', 5);
function medspa_components_init() {
    // Component system is automatically initialized by the auto-loader

    // Register demo button component for testing
    if (!ComponentRegistry::is_registered('demo-button')) {
        require_once get_template_directory() . '/inc/components/demo-button-component.php';
    }

    // Register button component
    if (!ComponentRegistry::is_registered('button')) {
        ComponentRegistry::register('button', 'ButtonComponent', [
            'auto_discovered' => true,
            'priority' => 10,
            'css_dependencies' => ['button-component-styles'],
            'customizer_section' => 'buttons'
        ]);
    }

    // Auto-discover and register all components
    if (class_exists('ComponentAutoLoader')) {
        ComponentAutoLoader::discover_and_register_components();
    }
}

/**
 * ============================================================================
 * VISUAL CUSTOMIZER INTEGRATION
 * ============================================================================
 */

// Include the Visual Customizer Integration
require_once get_template_directory() . '/inc/visual-customizer-integration.php';

// Include the Simple Visual Customizer (Admin Bar Integration)
require_once get_template_directory() . '/inc/visual-customizer-simple.php';

/**
 * ============================================================================
 * ESSENTIAL THEME SETUP FUNCTION (RESTORED)
 * ============================================================================
 */

/**
 * Theme setup function
 */
function medspa_theme_setup() {
    // Add theme support for title tag
    add_theme_support('title-tag');

    // Add theme support for custom logo
    add_theme_support('custom-logo');

    // Add theme support for post thumbnails
    add_theme_support('post-thumbnails');

    // Add theme support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    // Add theme support for custom background
    add_theme_support('custom-background');

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'preetidreams'),
        'footer' => esc_html__('Footer Menu', 'preetidreams'),
    ));
}
add_action('after_setup_theme', 'medspa_theme_setup');

/**
 * Sprint 2 PVC-007-DT: Enqueue Design Token System Assets
 */
function preetidreams_enqueue_design_token_system() {
    // Load for admin users or when specifically in customizer/debug mode
    // CRITICAL FIX: Also load for admin users on frontend for Visual Customizer
    $should_load = is_admin() ||
                   is_customize_preview() ||
                   (defined('WP_DEBUG') && WP_DEBUG) ||
                   (is_user_logged_in() && current_user_can('manage_options'));

    if ($should_load) {

        // Core Design Token Systems
        wp_enqueue_script(
            'design-token-registry',
            get_template_directory_uri() . '/assets/js/design-token-registry.js',
            ['jquery'],
            PREETIDREAMS_VERSION,
            true
        );

        wp_enqueue_script(
            'token-relationship-engine',
            get_template_directory_uri() . '/assets/js/token-relationship-engine.js',
            ['design-token-registry'],
            PREETIDREAMS_VERSION,
            true
        );

        wp_enqueue_script(
            'universal-customization-engine',
            get_template_directory_uri() . '/assets/js/universal-customization-engine.js',
            ['token-relationship-engine'],
            PREETIDREAMS_VERSION,
            true
        );

        // Domain-Specific Systems
        wp_enqueue_script(
            'color-domain-system',
            get_template_directory_uri() . '/assets/js/color-domain-system.js',
            ['universal-customization-engine'],
            PREETIDREAMS_VERSION,
            true
        );

        wp_enqueue_script(
            'semantic-color-system',
            get_template_directory_uri() . '/assets/js/semantic-color-system.js',
            ['color-domain-system'],
            PREETIDREAMS_VERSION,
            true
        );

        wp_enqueue_script(
            'typography-domain-system',
            get_template_directory_uri() . '/assets/js/typography-domain-system.js',
            ['universal-customization-engine'],
            PREETIDREAMS_VERSION,
            true
        );

        wp_enqueue_script(
            'spacing-domain-generator',
            get_template_directory_uri() . '/assets/js/spacing-domain-generator.js',
            ['universal-customization-engine'],
            PREETIDREAMS_VERSION,
            true
        );

        // Enhanced Design Token Customizer Preview (WordPress Customizer integration)
        wp_enqueue_script(
            'design-token-customizer-preview',
            get_template_directory_uri() . '/assets/js/design-token-customizer-preview.js',
            ['universal-customization-engine', 'typography-domain-system'],
            PREETIDREAMS_VERSION,
            true
        );

        // CRITICAL: Sidebar Token Bridge for Visual Customizer Integration
        wp_enqueue_script(
            'sidebar-token-bridge',
            get_template_directory_uri() . '/assets/js/sidebar-token-bridge.js',
            [
                'jquery',
                'universal-customization-engine',
                'typography-domain-system',
                'spacing-domain-generator'
            ],
            PREETIDREAMS_VERSION,
            true
        );

        // CRITICAL: Visual Interface Components for Sidebar Integration
        wp_enqueue_script(
            'sidebar-color-palette-interface',
            get_template_directory_uri() . '/assets/js/sidebar-color-palette-interface.js',
            ['sidebar-token-bridge', 'semantic-color-system'],
            PREETIDREAMS_VERSION,
            true
        );

        wp_enqueue_script(
            'sidebar-typography-interface',
            get_template_directory_uri() . '/assets/js/sidebar-typography-interface.js',
            ['sidebar-token-bridge', 'typography-domain-system'],
            PREETIDREAMS_VERSION,
            true
        );

        // Enhanced Visual Interface Styling
        wp_enqueue_style(
            'sidebar-visual-interfaces',
            get_template_directory_uri() . '/assets/css/sidebar-visual-interfaces.css',
            [],
            PREETIDREAMS_VERSION
        );

        // Configuration and debugging
        wp_localize_script('universal-customization-engine', 'designTokenConfig', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('design_token_nonce'),
            'debugMode' => defined('WP_DEBUG') && WP_DEBUG,
            'version' => PREETIDREAMS_VERSION,
            'isCustomizer' => is_customize_preview(),
            'isAdmin' => is_admin(),
            'isLoggedInAdmin' => is_user_logged_in() && current_user_can('manage_options'),
            'sidebarIntegration' => true, // Sprint 2 Extension flag
            'currentTheme' => get_stylesheet()
        ]);

        // Sprint 2 Extension: Bridge Integration Status
        wp_localize_script('sidebar-token-bridge', 'sidebarBridgeConfig', [
            'sprintVersion' => 'SPRINT-002-EXT-001',
            'integrationMode' => 'visual-customizer-sidebar',
            'wordpressCustomizerDisabled' => false, // Keep for compatibility
            'visualCustomizerEnabled' => true,
            'debugOutput' => defined('WP_DEBUG') && WP_DEBUG
        ]);

        // Debug script loading
        if (defined('WP_DEBUG') && WP_DEBUG) {
            wp_add_inline_script('sidebar-typography-interface', '
                console.log("🔧 Typography Interface Script Loaded");
                console.log("🔧 Window.SidebarTypographyInterface:", typeof window.SidebarTypographyInterface);
            ');
        }
    }
}
add_action('wp_enqueue_scripts', 'preetidreams_enqueue_design_token_system');
add_action('customize_preview_init', 'preetidreams_enqueue_design_token_system');

/**
 * PERFORMANCE OPTIMIZATION: Server-side Google Fonts loading for selected typography
 * Industry best practice: Load fonts in HTML head for immediate availability
 */
function enqueue_selected_typography_fonts() {
    // Get current typography configuration
    $config = get_option('preetidreams_visual_customizer_config', []);

    if (!empty($config['typographyData'])) {
        $typography = $config['typographyData'];

        // Build Google Fonts URL for selected typography
        if (!empty($typography['googleFonts'])) {
            $fonts_query = implode('&family=', $typography['googleFonts']);
            $google_fonts_url = "https://fonts.googleapis.com/css2?family={$fonts_query}&display=swap";

            // Enqueue the selected typography fonts
            wp_enqueue_style(
                'selected-typography-fonts',
                $google_fonts_url,
                [],
                null // No version for Google Fonts
            );

            // Add resource hints for performance
            add_action('wp_head', function() {
                echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
                echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
            }, 1);

            error_log("✅ Server-side fonts loaded: " . $google_fonts_url);
        }
    }

    // Always load preview fonts for customizer interface (for admin users)
    if (current_user_can('customize')) {
        $preview_fonts = [
            'Inter:wght@400;500;600;700',
            'Playfair+Display:wght@400;500;600;700',
            'Poppins:wght@400;500;600;700',
            'Crimson+Text:wght@400;500;600;700',
            'Montserrat:wght@400;500;600;700',
            'Cormorant+Garamond:wght@400;500;600;700',
            'IBM+Plex+Sans:wght@400;500;600;700',
            'Merriweather:wght@400;500;600;700;900'
        ];

        $preview_fonts_query = implode('&family=', $preview_fonts);
        $preview_fonts_url = "https://fonts.googleapis.com/css2?family={$preview_fonts_query}&display=swap";

        wp_enqueue_style(
            'typography-preview-fonts-server',
            $preview_fonts_url,
            [],
            null
        );

        error_log("✅ Server-side preview fonts loaded for admin user");
    }
}
add_action('wp_enqueue_scripts', 'enqueue_selected_typography_fonts', 5); // High priority

/**
 * PERFORMANCE OPTIMIZATION: Add critical font loading optimizations to head
 */
function add_font_loading_optimizations() {
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";

    // Add font-display CSS for immediate text rendering
    echo '<style>' . "\n";
    echo '@font-face { font-display: swap; }' . "\n";
    echo '</style>' . "\n";
}
add_action('wp_head', 'add_font_loading_optimizations', 1);

// Include performance optimizations
require_once get_template_directory() . '/inc/typography-performance.php';

/**
 * Enqueue theme styles and scripts
 */
function medspa_theme_styles() {
    // Main theme stylesheet
    wp_enqueue_style('preetidreams-style', get_stylesheet_uri(), [], PREETIDREAMS_VERSION);

    // Button component styles
    wp_enqueue_style(
        'button-component-styles',
        get_template_directory_uri() . '/assets/css/components/button.css',
        [],
        PREETIDREAMS_VERSION
    );

    // Card component styles (T6.4 Implementation)
    wp_enqueue_style(
        'card-component-styles',
        get_template_directory_uri() . '/assets/css/components/card.css',
        [],
        PREETIDREAMS_VERSION
    );

    // Component system styles (if needed for other components)
    if (class_exists('ComponentRegistry')) {
        $registered_components = ComponentRegistry::get_registered_components();

        foreach ($registered_components as $name => $component) {
            if (isset($component['config']['css_dependencies'])) {
                foreach ($component['config']['css_dependencies'] as $css_handle) {
                    if (!wp_style_is($css_handle, 'enqueued')) {
                        // CSS dependencies are handled above for specific components
                        // This loop ensures future components can define their own CSS dependencies
                    }
                }
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'medspa_theme_styles');

/**
 * Get phone number for theme
 *
 * @return string Phone number
 */
function preetidreams_get_phone() {
    // Return default phone number or get from customizer
    $phone = get_theme_mod('contact_phone', '+1 (555) 123-4567');
    return $phone;
}

/**
 * Get address for theme
 *
 * @return string Address
 */
function preetidreams_get_address() {
    // Return default address or get from customizer
    $address = get_theme_mod('contact_address', '123 Medical Plaza\nBeverly Hills, CA 90210');
    return $address;
}

/**
 * Get hours for theme
 *
 * @return string Hours
 */
function preetidreams_get_hours() {
    // Return default hours or get from customizer
    $hours = get_theme_mod('contact_hours', 'Mon-Fri: 9:00 AM - 6:00 PM\nSat: 10:00 AM - 4:00 PM\nSun: By Appointment');
    return $hours;
}

/**
 * Get email for theme
 *
 * @return string Email
 */
function preetidreams_get_email() {
    // Return default email or get from customizer
    $email = get_theme_mod('contact_email', 'info@preetidreams.com');
    return $email;
}

/**
 * Get social link for theme
 *
 * @param string $platform Social platform
 * @return string Social link
 */
function preetidreams_get_social_link($platform) {
    // Return social link from customizer
    $link = get_theme_mod('social_' . $platform, '');
    return $link;
}
