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
define('PREETIDREAMS_VERSION', '1.0.11.' . time());

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

// T11.6: Include Homepage Sections Customizer Controls
require_once get_template_directory() . '/inc/customizer/homepage-sections-customizer.php';
require_once get_template_directory() . '/inc/customizer/visual-content-customizer.php';

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
    // Semantic Tokens CSS - Core semantic token definitions (HIGHEST PRIORITY)
    wp_enqueue_style(
        'semantic-tokens',
        get_template_directory_uri() . '/assets/css/semantic-tokens.css',
        array(), // No dependencies - must load first
        PREETIDREAMS_VERSION,
        'all'
    );

    // Semantic Components CSS - All component styling with semantic tokens
    wp_enqueue_style(
        'semantic-components',
        get_template_directory_uri() . '/assets/css/semantic-components.css',
        array('semantic-tokens'),
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

    // Hero component (removed - using hero-section-component instead)

    // Hero Section component (luxury hero with quiz integration)
    wp_enqueue_style(
        'hero-section-component',
        get_template_directory_uri() . '/assets/css/components/hero-section.css',
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

    // Hero component scripts (removed - using hero-section-component-scripts instead)

    // Hero Section component scripts (luxury hero with quiz integration)
    wp_enqueue_script(
        'hero-section-component-scripts',
        get_template_directory_uri() . '/assets/js/components/hero-section.js',
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

    // Patient Stories Slider Component - Auto-timer horizontal slider
    wp_enqueue_script(
        'patient-stories-slider',
        get_template_directory_uri() . '/assets/js/patient-stories-slider.js',
        array(),
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

// Register Testimonials Custom Post Type and Meta Fields
function register_testimonials_post_type() {
    $labels = array(
        'name'                  => _x('Testimonials', 'Post type general name', 'textdomain'),
        'singular_name'         => _x('Testimonial', 'Post type singular name', 'textdomain'),
        'menu_name'             => _x('Testimonials', 'Admin Menu text', 'textdomain'),
        'name_admin_bar'        => _x('Testimonial', 'Add New on Toolbar', 'textdomain'),
        'add_new'               => __('Add New', 'textdomain'),
        'add_new_item'          => __('Add New Testimonial', 'textdomain'),
        'new_item'              => __('New Testimonial', 'textdomain'),
        'edit_item'             => __('Edit Testimonial', 'textdomain'),
        'view_item'             => __('View Testimonial', 'textdomain'),
        'all_items'             => __('All Testimonials', 'textdomain'),
        'search_items'          => __('Search Testimonials', 'textdomain'),
        'parent_item_colon'     => __('Parent Testimonials:', 'textdomain'),
        'not_found'             => __('No testimonials found.', 'textdomain'),
        'not_found_in_trash'    => __('No testimonials found in Trash.', 'textdomain'),
        'featured_image'        => _x('Patient Photo', 'Overrides the "Featured Image" phrase', 'textdomain'),
        'set_featured_image'    => _x('Set patient photo', 'Overrides the "Set featured image" phrase', 'textdomain'),
        'remove_featured_image' => _x('Remove patient photo', 'Overrides the "Remove featured image" phrase', 'textdomain'),
        'use_featured_image'    => _x('Use as patient photo', 'Overrides the "Use as featured image" phrase', 'textdomain'),
        'archives'              => _x('Testimonial archives', 'The post type archive label', 'textdomain'),
        'insert_into_item'      => _x('Insert into testimonial', 'Overrides the "Insert into post" phrase', 'textdomain'),
        'uploaded_to_this_item' => _x('Uploaded to this testimonial', 'Overrides the "Uploaded to this post" phrase', 'textdomain'),
        'filter_items_list'     => _x('Filter testimonials list', 'Screen reader text for the filter links', 'textdomain'),
        'items_list_navigation' => _x('Testimonials list navigation', 'Screen reader text for the pagination', 'textdomain'),
        'items_list'            => _x('Testimonials list', 'Screen reader text for the items list', 'textdomain'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'testimonial'),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-format-quote',
        'supports'           => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'show_in_rest'       => true,
    );

    register_post_type('testimonial', $args);
}
add_action('init', 'register_testimonials_post_type');

// Add custom meta boxes for testimonial fields
function add_testimonial_meta_boxes() {
    add_meta_box(
        'testimonial-details',
        __('Testimonial Details', 'textdomain'),
        'testimonial_details_callback',
        'testimonial',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_testimonial_meta_boxes');

// Meta box callback function
function testimonial_details_callback($post) {
    wp_nonce_field('testimonial_details_nonce', 'testimonial_details_nonce');

    $patient_name = get_post_meta($post->ID, '_testimonial_patient_name', true);
    $treatment_type = get_post_meta($post->ID, '_testimonial_treatment_type', true);
    $rating = get_post_meta($post->ID, '_testimonial_rating', true);
    $bubble_color = get_post_meta($post->ID, '_testimonial_bubble_color', true);
    $display_order = get_post_meta($post->ID, '_testimonial_display_order', true);
    $is_featured = get_post_meta($post->ID, '_testimonial_is_featured', true);

    // Set defaults
    if (empty($rating)) $rating = 5;
    if (empty($bubble_color)) $bubble_color = 'primary';
    if (empty($display_order)) $display_order = 0;

    echo '<table class="form-table">';

    // Patient Name
    echo '<tr>';
    echo '<th scope="row"><label for="testimonial_patient_name">' . __('Patient Name', 'textdomain') . '</label></th>';
    echo '<td><input type="text" id="testimonial_patient_name" name="testimonial_patient_name" value="' . esc_attr($patient_name) . '" class="regular-text" /></td>';
    echo '</tr>';

    // Treatment Type
    echo '<tr>';
    echo '<th scope="row"><label for="testimonial_treatment_type">' . __('Treatment Type', 'textdomain') . '</label></th>';
    echo '<td><input type="text" id="testimonial_treatment_type" name="testimonial_treatment_type" value="' . esc_attr($treatment_type) . '" class="regular-text" placeholder="e.g., Botox & Fillers Patient" /></td>';
    echo '</tr>';

    // Rating
    echo '<tr>';
    echo '<th scope="row"><label for="testimonial_rating">' . __('Rating', 'textdomain') . '</label></th>';
    echo '<td>';
    echo '<select id="testimonial_rating" name="testimonial_rating">';
    for ($i = 1; $i <= 5; $i++) {
        echo '<option value="' . $i . '"' . selected($rating, $i, false) . '>' . $i . ' Star' . ($i > 1 ? 's' : '') . '</option>';
    }
    echo '</select>';
    echo '</td>';
    echo '</tr>';

    // Bubble Color
    echo '<tr>';
    echo '<th scope="row"><label for="testimonial_bubble_color">' . __('Speech Bubble Color', 'textdomain') . '</label></th>';
    echo '<td>';
    echo '<select id="testimonial_bubble_color" name="testimonial_bubble_color">';
    echo '<option value="primary"' . selected($bubble_color, 'primary', false) . '>' . __('Primary (Blue)', 'textdomain') . '</option>';
    echo '<option value="secondary"' . selected($bubble_color, 'secondary', false) . '>' . __('Secondary (Purple)', 'textdomain') . '</option>';
    echo '<option value="accent"' . selected($bubble_color, 'accent', false) . '>' . __('Accent (Gold)', 'textdomain') . '</option>';
    echo '</select>';
    echo '</td>';
    echo '</tr>';

    // Display Order
    echo '<tr>';
    echo '<th scope="row"><label for="testimonial_display_order">' . __('Display Order', 'textdomain') . '</label></th>';
    echo '<td><input type="number" id="testimonial_display_order" name="testimonial_display_order" value="' . esc_attr($display_order) . '" min="0" class="small-text" /></td>';
    echo '</tr>';

    // Featured
    echo '<tr>';
    echo '<th scope="row"><label for="testimonial_is_featured">' . __('Featured in Slider', 'textdomain') . '</label></th>';
    echo '<td><input type="checkbox" id="testimonial_is_featured" name="testimonial_is_featured" value="1"' . checked($is_featured, 1, false) . ' /> ' . __('Show this testimonial in the homepage slider', 'textdomain') . '</td>';
    echo '</tr>';

    echo '</table>';

    echo '<p><strong>' . __('Instructions:', 'textdomain') . '</strong></p>';
    echo '<ul>';
    echo '<li>' . __('Use the main content area above to enter the testimonial quote.', 'textdomain') . '</li>';
    echo '<li>' . __('Set a featured image to use as the patient photo.', 'textdomain') . '</li>';
    echo '<li>' . __('Only testimonials marked as "Featured" will appear in the homepage slider.', 'textdomain') . '</li>';
    echo '<li>' . __('Display order determines the sequence in the slider (0 = first).', 'textdomain') . '</li>';
    echo '</ul>';
}

// Save meta box data
function save_testimonial_meta_box_data($post_id) {
    if (!isset($_POST['testimonial_details_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['testimonial_details_nonce'], 'testimonial_details_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['post_type']) && 'testimonial' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Save the data
    if (isset($_POST['testimonial_patient_name'])) {
        update_post_meta($post_id, '_testimonial_patient_name', sanitize_text_field($_POST['testimonial_patient_name']));
    }

    if (isset($_POST['testimonial_treatment_type'])) {
        update_post_meta($post_id, '_testimonial_treatment_type', sanitize_text_field($_POST['testimonial_treatment_type']));
    }

    if (isset($_POST['testimonial_rating'])) {
        update_post_meta($post_id, '_testimonial_rating', intval($_POST['testimonial_rating']));
    }

    if (isset($_POST['testimonial_bubble_color'])) {
        update_post_meta($post_id, '_testimonial_bubble_color', sanitize_text_field($_POST['testimonial_bubble_color']));
    }

    if (isset($_POST['testimonial_display_order'])) {
        update_post_meta($post_id, '_testimonial_display_order', intval($_POST['testimonial_display_order']));
    }

    // Handle checkbox
    if (isset($_POST['testimonial_is_featured'])) {
        update_post_meta($post_id, '_testimonial_is_featured', 1);
    } else {
        update_post_meta($post_id, '_testimonial_is_featured', 0);
    }
}
add_action('save_post', 'save_testimonial_meta_box_data');

// Create default testimonials on theme activation
function create_default_testimonials() {
    // Check if testimonials already exist
    $existing_testimonials = get_posts(array(
        'post_type' => 'testimonial',
        'numberposts' => 1,
        'post_status' => 'any'
    ));

    if (!empty($existing_testimonials)) {
        return; // Testimonials already exist
    }

    // Default testimonials data
    $default_testimonials = array(
        array(
            'title' => 'Jennifer L. - Fountain of Youth Experience',
            'content' => 'I have found my Fountain of Youth. The staff is professional, knowledgeable, and truly cares about their patients. My results exceeded my expectations!',
            'patient_name' => 'Jennifer L.',
            'treatment_type' => 'Botox & Fillers Patient',
            'rating' => 5,
            'bubble_color' => 'primary',
            'display_order' => 1,
            'featured_image_url' => 'https://images.unsplash.com/photo-1494790108755-2616b612b647?w=150&h=150&fit=crop&crop=face'
        ),
        array(
            'title' => 'Sarah M. - HydraFacial Transformation',
            'content' => 'The HydraFacial completely transformed my skin! The results were immediate and my skin has never looked better. The team was professional and made me feel so comfortable throughout the entire process.',
            'patient_name' => 'Sarah M.',
            'treatment_type' => 'HydraFacial Patient',
            'rating' => 5,
            'bubble_color' => 'secondary',
            'display_order' => 2,
            'featured_image_url' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150&h=150&fit=crop&crop=face'
        ),
        array(
            'title' => 'Michael R. - Laser Hair Removal Success',
            'content' => 'After years of shaving, laser hair removal has been life-changing! The process was comfortable and the results exceeded my expectations. I wish I had done this sooner.',
            'patient_name' => 'Michael R.',
            'treatment_type' => 'Laser Hair Removal Patient',
            'rating' => 5,
            'bubble_color' => 'accent',
            'display_order' => 3,
            'featured_image_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face'
        )
    );

    foreach ($default_testimonials as $testimonial_data) {
        // Create the post
        $post_id = wp_insert_post(array(
            'post_title' => $testimonial_data['title'],
            'post_content' => $testimonial_data['content'],
            'post_status' => 'publish',
            'post_type' => 'testimonial'
        ));

        if ($post_id && !is_wp_error($post_id)) {
            // Add meta data
            update_post_meta($post_id, '_testimonial_patient_name', $testimonial_data['patient_name']);
            update_post_meta($post_id, '_testimonial_treatment_type', $testimonial_data['treatment_type']);
            update_post_meta($post_id, '_testimonial_rating', $testimonial_data['rating']);
            update_post_meta($post_id, '_testimonial_bubble_color', $testimonial_data['bubble_color']);
            update_post_meta($post_id, '_testimonial_display_order', $testimonial_data['display_order']);
            update_post_meta($post_id, '_testimonial_is_featured', 1);

            // Set featured image from URL (optional - requires WordPress to download the image)
            if (!empty($testimonial_data['featured_image_url'])) {
                $image_id = media_sideload_image($testimonial_data['featured_image_url'], $post_id, $testimonial_data['patient_name'] . ' photo', 'id');
                if (!is_wp_error($image_id)) {
                    set_post_thumbnail($post_id, $image_id);
                }
            }
        }
    }
}

// Hook to run on theme activation
add_action('after_switch_theme', 'create_default_testimonials');

// Function to get featured testimonials for the slider
function get_featured_testimonials() {
    $testimonials = get_posts(array(
        'post_type' => 'testimonial',
        'numberposts' => -1,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => '_testimonial_is_featured',
                'value' => '1',
                'compare' => '='
            )
        ),
        'meta_key' => '_testimonial_display_order',
        'orderby' => 'meta_value_num',
        'order' => 'ASC'
    ));

    return $testimonials;
}

// Add admin notice for testimonials
function testimonials_admin_notice() {
    $screen = get_current_screen();
    if ($screen->post_type == 'testimonial') {
        echo '<div class="notice notice-info"><p>';
        echo '<strong>' . __('Testimonials Help:', 'textdomain') . '</strong> ';
        echo __('Create compelling patient testimonials with photos and treatment details. Mark testimonials as "Featured" to include them in the homepage slider.', 'textdomain');
        echo '</p></div>';
    }
}
add_action('admin_notices', 'testimonials_admin_notice');

// Add admin styles for testimonials
function testimonials_admin_styles() {
    $screen = get_current_screen();
    if ($screen->post_type == 'testimonial') {
        ?>
        <style>
        .testimonial-meta-preview {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
        }
        .testimonial-bubble-preview {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 20px;
            margin: 10px 0;
            color: white;
            font-weight: 500;
        }
        .testimonial-bubble-preview.primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .testimonial-bubble-preview.secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .testimonial-bubble-preview.accent {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            color: #333;
        }
        .testimonials-help-box {
            background: #e7f3ff;
            border-left: 4px solid #0073aa;
            padding: 12px;
            margin: 15px 0;
        }
        .testimonials-help-box h4 {
            margin-top: 0;
            color: #0073aa;
        }
        .testimonial-quick-stats {
            display: flex;
            gap: 20px;
            margin: 15px 0;
        }
        .testimonial-stat {
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            text-align: center;
            min-width: 80px;
        }
        .testimonial-stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #0073aa;
        }
        .testimonial-stat-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }
        </style>
        <?php
    }
}
add_action('admin_head', 'testimonials_admin_styles');

// Enhanced admin notice with stats
function testimonials_admin_notice_enhanced() {
    $screen = get_current_screen();
    if ($screen->post_type == 'testimonial') {
        // Get testimonial stats
        $total_testimonials = wp_count_posts('testimonial');
        $featured_count = get_posts(array(
            'post_type' => 'testimonial',
            'numberposts' => -1,
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_testimonial_is_featured',
                    'value' => '1',
                    'compare' => '='
                )
            ),
            'fields' => 'ids'
        ));

        echo '<div class="notice notice-info">';
        echo '<div class="testimonials-help-box">';
        echo '<h4>' . __('Testimonials Management', 'textdomain') . '</h4>';
        echo '<p>' . __('Create compelling patient testimonials with photos and treatment details. Mark testimonials as "Featured" to include them in the homepage slider.', 'textdomain') . '</p>';

        echo '<div class="testimonial-quick-stats">';
        echo '<div class="testimonial-stat">';
        echo '<div class="testimonial-stat-number">' . intval($total_testimonials->publish) . '</div>';
        echo '<div class="testimonial-stat-label">Total</div>';
        echo '</div>';
        echo '<div class="testimonial-stat">';
        echo '<div class="testimonial-stat-number">' . count($featured_count) . '</div>';
        echo '<div class="testimonial-stat-label">Featured</div>';
        echo '</div>';
        echo '<div class="testimonial-stat">';
        echo '<div class="testimonial-stat-number">' . (count($featured_count) > 0 ? '✓' : '!') . '</div>';
        echo '<div class="testimonial-stat-label">Slider</div>';
        echo '</div>';
        echo '</div>';

        if (count($featured_count) === 0) {
            echo '<p style="color: #d63638;"><strong>' . __('Notice:', 'textdomain') . '</strong> ' . __('No testimonials are currently featured in the homepage slider. Mark at least one testimonial as "Featured" to display them.', 'textdomain') . '</p>';
        }

        echo '</div>';
        echo '</div>';
    }
}
// Replace the previous admin notice
remove_action('admin_notices', 'testimonials_admin_notice');
add_action('admin_notices', 'testimonials_admin_notice_enhanced');

// Add custom columns to testimonials list
function testimonials_custom_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['patient_name'] = __('Patient Name', 'textdomain');
    $new_columns['treatment'] = __('Treatment', 'textdomain');
    $new_columns['rating'] = __('Rating', 'textdomain');
    $new_columns['bubble_color'] = __('Bubble Color', 'textdomain');
    $new_columns['featured'] = __('Featured', 'textdomain');
    $new_columns['order'] = __('Order', 'textdomain');
    $new_columns['date'] = $columns['date'];

    return $new_columns;
}
add_filter('manage_testimonial_posts_columns', 'testimonials_custom_columns');

// Fill custom columns with data
function testimonials_custom_column_data($column, $post_id) {
    switch ($column) {
        case 'patient_name':
            $patient_name = get_post_meta($post_id, '_testimonial_patient_name', true);
            echo $patient_name ? esc_html($patient_name) : '—';
            break;

        case 'treatment':
            $treatment = get_post_meta($post_id, '_testimonial_treatment_type', true);
            echo $treatment ? esc_html($treatment) : '—';
            break;

        case 'rating':
            $rating = get_post_meta($post_id, '_testimonial_rating', true);
            if ($rating) {
                echo str_repeat('⭐', intval($rating)) . ' (' . $rating . ')';
            } else {
                echo '—';
            }
            break;

        case 'bubble_color':
            $color = get_post_meta($post_id, '_testimonial_bubble_color', true);
            $color_labels = array(
                'primary' => __('Primary (Blue)', 'textdomain'),
                'secondary' => __('Secondary (Purple)', 'textdomain'),
                'accent' => __('Accent (Gold)', 'textdomain')
            );
            echo '<span class="testimonial-bubble-preview ' . esc_attr($color) . '">' . ($color_labels[$color] ?? '—') . '</span>';
            break;

        case 'featured':
            $featured = get_post_meta($post_id, '_testimonial_is_featured', true);
            if ($featured) {
                echo '<span style="color: #00a32a;">✓ ' . __('Yes', 'textdomain') . '</span>';
            } else {
                echo '<span style="color: #d63638;">✗ ' . __('No', 'textdomain') . '</span>';
            }
            break;

        case 'order':
            $order = get_post_meta($post_id, '_testimonial_display_order', true);
            echo $order !== '' ? intval($order) : '0';
            break;
    }
}
add_action('manage_testimonial_posts_custom_column', 'testimonials_custom_column_data', 10, 2);

// Make custom columns sortable
function testimonials_sortable_columns($columns) {
    $columns['patient_name'] = 'patient_name';
    $columns['rating'] = 'rating';
    $columns['featured'] = 'featured';
    $columns['order'] = 'order';
    return $columns;
}
add_filter('manage_edit-testimonial_sortable_columns', 'testimonials_sortable_columns');

// Handle sorting for custom columns
function testimonials_column_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $orderby = $query->get('orderby');

    switch ($orderby) {
        case 'patient_name':
            $query->set('meta_key', '_testimonial_patient_name');
            $query->set('orderby', 'meta_value');
            break;

        case 'rating':
            $query->set('meta_key', '_testimonial_rating');
            $query->set('orderby', 'meta_value_num');
            break;

        case 'featured':
            $query->set('meta_key', '_testimonial_is_featured');
            $query->set('orderby', 'meta_value_num');
            break;

        case 'order':
            $query->set('meta_key', '_testimonial_display_order');
            $query->set('orderby', 'meta_value_num');
            break;
    }
}
add_action('pre_get_posts', 'testimonials_column_orderby');
