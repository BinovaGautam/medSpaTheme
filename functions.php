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
 * 🚨 EMERGENCY FIX: Force-Enable Missing Footer Sections
 *
 * FOOTER-RESTORE-001: WordPress Customizer database settings may be overriding
 * code defaults. These filters ensure all footer sections render regardless
 * of database settings.
 *
 * Based on systematic analysis: Only 1 of 5 footer sections showing (20% completion)
 * This fix restores missing 80% of footer functionality immediately.
 */
add_filter('theme_mod_footer_enable_hero', '__return_true', 999);
add_filter('theme_mod_footer_enable_map', '__return_true', 999);
add_filter('theme_mod_footer_enable_newsletter', '__return_true', 999);

// Add debug information for footer section rendering
if (defined('WP_DEBUG') && WP_DEBUG) {
    add_action('wp_footer', function() {
        if (current_user_can('manage_options')) {
            echo "<!-- Footer Section Debug Info -->\n";
            echo "<!-- Hero Enabled: " . (get_theme_mod('footer_enable_hero', true) ? 'TRUE' : 'FALSE') . " -->\n";
            echo "<!-- Map Enabled: " . (get_theme_mod('footer_enable_map', true) ? 'TRUE' : 'FALSE') . " -->\n";
            echo "<!-- Newsletter Enabled: " . (get_theme_mod('footer_enable_newsletter', true) ? 'TRUE' : 'FALSE') . " -->\n";
            echo "<!-- Footer Restore Fix: ACTIVE -->\n";
        }
    });
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
    // CRITICAL: Main medical spa theme stylesheet - MUST LOAD FIRST
    wp_enqueue_style(
        'medical-spa-theme',
        get_template_directory_uri() . '/assets/css/medical-spa-theme.css',
        [],
        time(), // Force cache refresh
        'all'
    );

    // Main theme stylesheet (contains imports and fallbacks)
    wp_enqueue_style('preetidreams-style', get_stylesheet_uri(), ['medical-spa-theme'], PREETIDREAMS_VERSION);

    // Button component styles
    wp_enqueue_style(
        'button-component-styles',
        get_template_directory_uri() . '/assets/css/components/button.css',
        ['medical-spa-theme'],
        PREETIDREAMS_VERSION
    );

    // Card component styles (T6.4 Implementation)
    wp_enqueue_style(
        'card-component-styles',
        get_template_directory_uri() . '/assets/css/components/card.css',
        ['medical-spa-theme'],
        PREETIDREAMS_VERSION
    );

    // Form component styles (T6.5 Implementation)
    wp_enqueue_style(
        'form-component-styles',
        get_template_directory_uri() . '/assets/css/components/form.css',
        ['medical-spa-theme'],
        PREETIDREAMS_VERSION
    );

    // CRITICAL: Visual Customizer Enhancement Styles
    wp_enqueue_style(
        'customizer-enhancements',
        get_template_directory_uri() . '/assets/css/customizer-enhancements.css',
        ['medical-spa-theme'],
        PREETIDREAMS_VERSION
    );

    // 🚨 URGENT: Hero Contrast Fixes for WCAG 2.1 AA Compliance
    wp_enqueue_style(
        'hero-contrast-fixes',
        get_template_directory_uri() . '/assets/css/components/hero-contrast-fixes.css',
        ['medical-spa-theme'],
        time(), // Force cache refresh for urgent fix
        'all'
    );

    // 🚨 DISABLED: Footer Emergency CSS (was causing destruction)
    /*
    wp_enqueue_style(
        'footer-emergency-fixes',
        get_template_directory_uri() . '/assets/css/components/footer-emergency-fixes.css',
        ['medical-spa-theme', 'footer-component-styles', 'footer-luxury-styles'],
        time(), // Force cache refresh for critical fix
        'all'
    );
    */

    // T6.6 Modal System Scripts and Styles
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

    // T6.8 Footer Component Scripts and Styles
    wp_enqueue_style(
        'footer-component-styles',
        get_template_directory_uri() . '/assets/css/components/footer.css',
        array('medical-spa-theme'),
        time() // Force cache refresh
    );

    wp_enqueue_script(
        'footer-component-scripts',
        get_template_directory_uri() . '/assets/js/components/footer.js',
        array('jquery', 'wp-util'),
        filemtime(get_template_directory() . '/assets/js/components/footer.js'),
        true
    );

    // T6.8.2 Footer Google Maps Integration
    wp_enqueue_script(
        'footer-maps-scripts',
        get_template_directory_uri() . '/assets/js/footer-maps.js',
        array('jquery'),
        filemtime(get_template_directory() . '/assets/js/footer-maps.js'),
        true
    );

    // T-FOOTER-005: Luxury Visual Design Implementation
    wp_enqueue_style(
        'footer-luxury-styles',
        get_template_directory_uri() . '/assets/css/components/footer-luxury.css',
        array('footer-component-styles', 'footer-tokenized'),
        PREETIDREAMS_VERSION
    );

    wp_enqueue_style(
        'footer-interactions-styles',
        get_template_directory_uri() . '/assets/css/animations/footer-interactions.css',
        array('footer-luxury-styles'),
        PREETIDREAMS_VERSION
    );

    // T-FOOTER-006: Responsive Design Implementation
    wp_enqueue_style(
        'footer-responsive-styles',
        get_template_directory_uri() . '/assets/css/responsive/footer-responsive.css',
        array('footer-luxury-styles', 'footer-interactions-styles'),
        PREETIDREAMS_VERSION
    );

    // T6.8-EXT-2: Enhanced Spacing Implementation
    wp_enqueue_style(
        'footer-enhanced-spacing',
        get_template_directory_uri() . '/assets/css/components/footer-enhanced-spacing.css',
        array('footer-luxury-styles'),
        PREETIDREAMS_VERSION
    );

    // T-FOOTER-007: Newsletter & Social Engagement JavaScript
    wp_enqueue_script(
        'footer-newsletter-scripts',
        get_template_directory_uri() . '/assets/js/footer-newsletter.js',
        array('jquery', 'footer-component-scripts'),
        PREETIDREAMS_VERSION,
        true
    );

    // Localize footer scripts
    wp_localize_script('footer-component-scripts', 'footerSettings', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('footer_nonce'),
        'backToTopText' => __('Back to top', 'medspatheme'),
        'phoneCallText' => __('Initiating phone call...', 'medspatheme'),
        'emailText' => __('Opening email client...', 'medspatheme'),
        'socialMediaText' => __('Opening social media...', 'medspatheme'),
        'scrollToTopText' => __('Scrolled to top of page', 'medspatheme'),
        'loadingText' => __('Loading...', 'medspatheme'),
        'analytics' => array(
            'enabled' => true,
            'trackCTA' => true,
            'trackSocial' => true,
            'trackContact' => true
        )
    ));

    // CRITICAL FIX: Moved footer maps script inside the function where it belongs
    // Footer Maps JavaScript
    wp_enqueue_script(
        'footer-maps',
        get_template_directory_uri() . '/assets/js/footer-maps.js',
        ['jquery'], // Removed 'google-maps-api' dependency that might not exist
        PREETIDREAMS_VERSION, // Use defined constant instead of filemtime
        true
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
 * CRITICAL FIX: Combined and improved script enqueue function
 * Removed duplicate function and consolidated functionality
 */
function medspatheme_scripts() {
    // Navigation script
    if (file_exists(get_template_directory() . '/js/navigation.js')) {
        wp_enqueue_script('medspatheme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), PREETIDREAMS_VERSION, true);
    }

    // Comment reply script
    if (is_singular() && comments_open() && get_option('comment_registration')) {
        wp_enqueue_script('comment-reply');
    }

    // Enqueue footer customizer preview script in customizer
    if (is_customize_preview()) {
        wp_enqueue_script(
            'footer-customizer-preview',
            get_template_directory_uri() . '/assets/js/footer-customizer-preview.js',
            array('jquery', 'customize-preview'),
            PREETIDREAMS_VERSION,
            true
        );
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

    // BUG-RESOLVER-001: Footer Debug Tool for systematic issue resolution
    $footer_debug_path = get_template_directory() . '/devtools/wp-admin-tools/footer-debug-tool.php';
    if (file_exists($footer_debug_path)) {
        require_once $footer_debug_path;
    }
}

/**
 * Include footer customizer with error handling
 */
$footer_customizer_path = get_template_directory() . '/inc/customizer/footer-customizer.php';
if (file_exists($footer_customizer_path)) {
    require $footer_customizer_path;
}

/**
 * T-FOOTER-007: Newsletter Subscription AJAX Handler
 * Handle newsletter subscription submissions
 */
function footer_newsletter_subscribe_handler() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'footer_nonce')) {
        wp_send_json_error(array(
            'message' => 'Security verification failed. Please refresh and try again.'
        ));
        return;
    }

    // Sanitize email
    $email = sanitize_email($_POST['email']);

    if (!is_email($email)) {
        wp_send_json_error(array(
            'message' => 'Please enter a valid email address.'
        ));
        return;
    }

    // Check if email already exists (if using WordPress users or custom table)
    if (email_exists($email)) {
        wp_send_json_success(array(
            'message' => 'You are already subscribed to our newsletter!'
        ));
        return;
    }

    try {
        // Store subscription (customize based on your newsletter service)
        $subscription_data = array(
            'email' => $email,
            'source' => 'footer',
            'date_subscribed' => current_time('mysql'),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        );

        // Option 1: Store in WordPress options (simple approach)
        $existing_subscribers = get_option('footer_newsletter_subscribers', array());
        if (!in_array($email, $existing_subscribers)) {
            $existing_subscribers[] = $email;
            update_option('footer_newsletter_subscribers', $existing_subscribers);
        }

        // Option 2: Store in custom database table (commented out - implement if needed)
        /*
        global $wpdb;
        $table_name = $wpdb->prefix . 'newsletter_subscribers';

        $result = $wpdb->insert(
            $table_name,
            $subscription_data,
            array('%s', '%s', '%s', '%s', '%s')
        );
        */

        // Option 3: Integrate with external newsletter service (MailChimp, ConvertKit, etc.)
        // Add your integration code here

        // Send confirmation email (optional)
        $subject = 'Welcome to ' . get_bloginfo('name') . ' Newsletter';
        $message = 'Thank you for subscribing to our newsletter! We\'ll keep you updated with the latest news and exclusive offers.';
        $headers = array('Content-Type: text/html; charset=UTF-8');

        wp_mail($email, $subject, $message, $headers);

        // Success response
        wp_send_json_success(array(
            'message' => 'Thank you for subscribing! Please check your email for confirmation.'
        ));

    } catch (Exception $e) {
        wp_send_json_error(array(
            'message' => 'Subscription failed. Please try again later.'
        ));
    }
}

// Register AJAX handlers
add_action('wp_ajax_footer_newsletter_subscribe', 'footer_newsletter_subscribe_handler');
add_action('wp_ajax_nopriv_footer_newsletter_subscribe', 'footer_newsletter_subscribe_handler');

/**
 * Create newsletter subscribers table (run once)
 * Uncomment and run if you want to use database storage
 */
/*
function create_newsletter_subscribers_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'newsletter_subscribers';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id int(11) NOT NULL AUTO_INCREMENT,
        email varchar(255) NOT NULL,
        source varchar(50) DEFAULT 'footer',
        date_subscribed datetime DEFAULT CURRENT_TIMESTAMP,
        ip_address varchar(45),
        user_agent text,
        status varchar(20) DEFAULT 'active',
        PRIMARY KEY (id),
        UNIQUE KEY email (email)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
// register_activation_hook(__FILE__, 'create_newsletter_subscribers_table');
*/
