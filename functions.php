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

// CRITICAL FIX: Define _S_VERSION constant to prevent undefined constant error
if (!defined('_S_VERSION')) {
    define('_S_VERSION', PREETIDREAMS_VERSION);
}

// =============================================================================
// SPRINT 6: CUSTOMIZABLE COMPONENT ARCHITECTURE SYSTEM
// =============================================================================

// Load Component Architecture Foundation (T6.1 - Component Base Architecture)
require_once get_template_directory() . '/inc/components/base-component.php';
require_once get_template_directory() . '/inc/components/component-registry.php';
require_once get_template_directory() . '/inc/components/component-factory.php';
require_once get_template_directory() . '/inc/components/component-auto-loader.php';

// Load Core Components (Sprint 6 Implementation)
require_once get_template_directory() . '/inc/components/button-component.php';
require_once get_template_directory() . '/inc/components/card-component.php';
require_once get_template_directory() . '/inc/components/treatment-card.php';
require_once get_template_directory() . '/inc/components/testimonial-card.php';
require_once get_template_directory() . '/inc/components/feature-card.php';
require_once get_template_directory() . '/inc/components/form-component.php';
require_once get_template_directory() . '/inc/components/modal-component.php';
require_once get_template_directory() . '/inc/components/accordion-component.php';

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
add_action('after_setup_theme', 'medspa_components_init', 10);
function medspa_components_init() {
    // Ensure ComponentRegistry is available
    if (!class_exists('ComponentRegistry')) {
        return;
    }

    // Register core components
    $components = [
        'button' => 'ButtonComponent',
        'card' => 'CardComponent',
        'treatment-card' => 'TreatmentCard',
        'testimonial-card' => 'TestimonialCard',
        'feature-card' => 'FeatureCard',
        'form' => 'FormComponent',
        'modal' => 'ModalComponent',
        'accordion' => 'AccordionComponent',
        'demo-button' => 'DemoButtonComponent'
    ];

    foreach ($components as $component_id => $component_class) {
        if (class_exists($component_class) && !ComponentRegistry::is_registered($component_id)) {
            ComponentRegistry::register($component_id, $component_class, [
                'auto_discovered' => true,
                'priority' => 10,
                'cacheable' => true,
                'accessibility_required' => true
            ]);
        }
    }

    // Auto-discover and register any additional components
    if (class_exists('ComponentAutoLoader')) {
        ComponentAutoLoader::discover_and_register_components();
    }

    // Trigger component initialization hook
    do_action('medspa_components_registered');
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

// T8.1: Include Semantic Token Bridge (JavaScript to PHP Integration)
require_once get_template_directory() . '/inc/semantic-token-bridge.php';

// T8.4: Include Palette Validation System
require_once get_template_directory() . '/inc/palette-validation-system.php';

// T8.5: Token Animation System
wp_enqueue_script(
    'token-animation-system',
    get_template_directory_uri() . '/assets/js/token-animation-system.js',
    array(),
    wp_get_theme()->get('Version'),
    true
);

wp_enqueue_script(
    'token-animation-integration',
    get_template_directory_uri() . '/assets/js/token-animation-integration.js',
    array('jquery', 'token-animation-system'),
    wp_get_theme()->get('Version'),
    true
);

// T8.6: Sprint 8 Integration Validator (Development/Testing)
if (defined('WP_DEBUG') && WP_DEBUG) {
    wp_enqueue_script(
        'sprint8-integration-validator',
        get_template_directory_uri() . '/assets/js/sprint8-integration-validator.js',
        array('jquery', 'token-animation-system', 'token-animation-integration'),
        wp_get_theme()->get('Version'),
        true
    );
}

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
    ));
}
add_action('after_setup_theme', 'medspa_theme_setup');

// Load and initialize treatment post type
require_once get_template_directory() . '/inc/post-types/treatment.php';
require_once get_template_directory() . '/inc/data/treatments-adapter.php';
require_once get_template_directory() . '/inc/data/treatments.php';
add_action('init', ['TreatmentPostType', 'register']);
add_action('add_meta_boxes', ['TreatmentPostType', 'register_meta_boxes']);
add_action('save_post_treatment', ['TreatmentPostType', 'save_details']);

/**
 * CRITICAL: Header body class function for spacing fix
 * Determines header transparency and content spacing based on page type
 */
function add_header_body_classes($classes) {
    // Check if this is the homepage with hero section
    if (is_front_page() || is_home()) {
        // Homepage should have transparent header
        $classes[] = 'transparent-header';
        $classes[] = 'has-hero-section';
    } else {
        // All other pages should have solid header with proper spacing
        $classes[] = 'solid-header';
        $classes[] = 'no-hero-section';
    }

    return $classes;
}
add_filter('body_class', 'add_header_body_classes');

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
            get_template_directory_uri() . '/assets/js/color-domain-generator.js',
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
        // Only load in customizer context where wp.customize is available
        if (is_customize_preview()) {
            wp_enqueue_script(
                'design-token-customizer-preview',
                get_template_directory_uri() . '/assets/js/design-token-customizer-preview.js',
                ['universal-customization-engine', 'typography-domain-system'],
                PREETIDREAMS_VERSION,
                true
            );
        }

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

        // CRITICAL: Customizer Enhancements CSS - Foundation for Visual Customizer
        wp_enqueue_style(
            'customizer-enhancements',
            get_template_directory_uri() . '/assets/css/customizer-enhancements.css',
            ['sidebar-visual-interfaces'],
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
    // CRITICAL: Design System Foundation CSS - HIGHEST PRIORITY
    wp_enqueue_style(
        'design-system-foundation',
        get_template_directory_uri() . '/assets/css/design-system-compiled.css',
        array(), // No dependencies - must load first
        wp_get_theme()->get('Version'),
        'all'
    );

    // Semantic Tokens CSS - Core semantic token definitions
    wp_enqueue_style(
        'semantic-tokens',
        get_template_directory_uri() . '/assets/css/semantic-tokens.css',
        array('design-system-foundation'),
        PREETIDREAMS_VERSION,
        'all'
    );

    // Semantic Design Tokens CSS - Extended token system
    wp_enqueue_style(
        'semantic-design-tokens',
        get_template_directory_uri() . '/assets/css/semantic-design-tokens.css',
        array('semantic-tokens'),
        PREETIDREAMS_VERSION,
        'all'
    );

    // Semantic Components CSS - All component styling with semantic tokens
    wp_enqueue_style(
        'semantic-components',
        get_template_directory_uri() . '/assets/css/semantic-components.css',
        array('semantic-design-tokens'),
        PREETIDREAMS_VERSION,
        'all'
    );

    // Main theme styles
    wp_enqueue_style(
        'medical-spa-theme',
        get_stylesheet_uri(),
        array('semantic-components'), // Depends on semantic components
        PREETIDREAMS_VERSION
    );

    // Component styles
    wp_enqueue_style(
        'component-system-styles',
        get_template_directory_uri() . '/assets/css/components/components.css',
        array('medical-spa-theme'),
        PREETIDREAMS_VERSION
    );

    // Hero component
    wp_enqueue_style(
        'hero-component-styles',
        get_template_directory_uri() . '/assets/css/components/hero.css',
        array('medical-spa-theme'),
        PREETIDREAMS_VERSION
    );

    // Header fix styles - CRITICAL: Content spacing to prevent hiding behind fixed header
    wp_enqueue_style(
        'header-fix-styles',
        get_template_directory_uri() . '/assets/css/header-fix.css',
        array('medical-spa-theme'),
        PREETIDREAMS_VERSION
    );

    // CRITICAL PRIORITY FIX: Text alignment fixes for treatments page
    if (is_page_template('page-treatments.php') || is_page('treatments')) {
        wp_enqueue_style(
            'treatments-alignment-fixes',
            get_template_directory_uri() . '/assets/css/treatments-alignment-fixes.css',
            array('medical-spa-theme'),
            PREETIDREAMS_VERSION . '-alignment-fix'
        );
    }

    wp_enqueue_script(
        'hero-component-scripts',
        get_template_directory_uri() . '/assets/js/components/premium-hero.js',
        array('jquery', 'wp-util'),
        PREETIDREAMS_VERSION,
        true
    );

    // Header scroll transparency script
    wp_enqueue_script(
        'header-scroll-transparency',
        get_template_directory_uri() . '/assets/js/header-scroll-transparency.js',
        array(),
        PREETIDREAMS_VERSION,
        true
    );

    // Modal component
    wp_enqueue_style(
        'modal-component-styles',
        get_template_directory_uri() . '/assets/css/components/modal.css',
        array('medical-spa-theme'),
        filemtime(get_template_directory() . '/assets/css/components/modal.css')
    );

    wp_enqueue_script(
        'modal-component-scripts',
        get_template_directory_uri() . '/assets/js/components/modal.js',
        array('jquery', 'wp-util'),
        filemtime(get_template_directory() . '/assets/js/components/modal.js'),
        true
    );

    // Localize modal scripts
    wp_localize_script('modal-component-scripts', 'modalSettings', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('modal_nonce'),
        'closeText' => __('Close', 'medspatheme'),
        'loadingText' => __('Loading...', 'medspatheme'),
        'errorText' => __('An error occurred. Please try again.', 'medspatheme'),
        'confirmText' => __('Are you sure?', 'medspatheme'),
        'cancelText' => __('Cancel', 'medspatheme'),
        'okText' => __('OK', 'medspatheme')
    ));

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

    // Enqueue accessibility compliance CSS
    wp_enqueue_style(
        'medspatheme-accessibility-wcag',
        get_template_directory_uri() . '/assets/css/accessibility-wcag-compliance.css',
        array('medical-spa-theme'),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'medspa_theme_styles');

/**
 * CRITICAL FIX: Combined and improved script enqueue function
 * Removed duplicate function and consolidated functionality
 */
function medspatheme_scripts() {
    // Navigation script
    if (file_exists(get_template_directory() . '/js/navigation.js')) {
        wp_enqueue_script('medspatheme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), PREETIDREAMS_VERSION, true);
    }

    // CRITICAL: Treatment Filter Component - Required for homepage and archive pages
    wp_enqueue_script(
        'treatment-filter-component',
        get_template_directory_uri() . '/assets/js/components/treatment-filter.js',
        array('jquery'),
        PREETIDREAMS_VERSION,
        true
    );

    // Header functionality JavaScript - Mobile menu and interactions
    wp_enqueue_script(
        'header-functionality',
        get_template_directory_uri() . '/assets/js/header-functionality.js',
        array(),
        PREETIDREAMS_VERSION,
        true
    );

    // Footer component JavaScript
    wp_enqueue_script(
        'footer-component',
        get_template_directory_uri() . '/assets/js/components/footer.js',
        array('jquery'),
        PREETIDREAMS_VERSION,
        true
    );

    // Comment reply script
    if (is_singular() && comments_open() && get_option('comment_registration')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'medspatheme_scripts');

/**
 * Get phone number for theme
 */
function preetidreams_get_phone() {
    $phone = get_theme_mod('contact_phone', '+1 (555) 123-4567');
    return $phone;
}

/**
 * Get address for theme
 */
function preetidreams_get_address() {
    $address = get_theme_mod('contact_address', '123 Medical Plaza\nBeverly Hills, CA 90210');
    return $address;
}

/**
 * Get hours for theme
 */
function preetidreams_get_hours() {
    $hours = get_theme_mod('contact_hours', 'Mon-Fri: 9:00 AM - 6:00 PM\nSat: 10:00 AM - 4:00 PM\nSun: By Appointment');
    return $hours;
}

/**
 * Get email for theme
 */
function preetidreams_get_email() {
    $email = get_theme_mod('contact_email', 'info@preetidreams.com');
    return $email;
}

/**
 * Get social link for theme
 */
function preetidreams_get_social_link($platform) {
    $link = get_theme_mod('social_' . $platform, '');
    return $link;
}

// CRITICAL FIX: Include DevTools with proper error handling
if (current_user_can('manage_options')) {
    $layout_debugger_path = get_template_directory() . '/devtools/wp-admin-tools/layout-stack-debugger.php';
    if (file_exists($layout_debugger_path)) {
        require_once $layout_debugger_path;
    }
}
