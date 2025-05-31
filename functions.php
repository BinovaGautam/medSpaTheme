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

/**
 * Register The Auto Loader
 */
if (file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
    require $composer;
}

/**
 * Register The Bootloader
 */
if (class_exists('Roots\Acorn\Application')) {
    try {
        \Roots\Acorn\Application::configure()
            ->withProviders([
                \App\Providers\ThemeServiceProvider::class,
            ])
            ->boot();

        // Acorn is loaded successfully
        define('PREETIDREAMS_ACORN_LOADED', true);

    } catch (Exception $e) {
        // Log error and fall back to basic functionality
        error_log('PreetiDreams Theme: Acorn boot failed - ' . $e->getMessage());
        define('PREETIDREAMS_ACORN_LOADED', false);
        preetidreams_fallback_setup();
    }
} else {
    // No Acorn available, use fallback
    define('PREETIDREAMS_ACORN_LOADED', false);
    preetidreams_fallback_setup();
}

/**
 * Register Sage Theme Files
 */
if (defined('PREETIDREAMS_ACORN_LOADED') && PREETIDREAMS_ACORN_LOADED) {
    collect(['setup', 'filters'])
        ->each(function ($file) {
            $path = locate_template($file = "app/{$file}.php", true, true);
            if (!$path) {
                // If Sage files don't exist, create basic setup
                if ($file === 'app/setup.php') {
                    preetidreams_sage_setup();
                }
            }
        });
}

/**
 * Sage-compatible setup function
 */
function preetidreams_sage_setup() {
    add_action('after_setup_theme', function () {
        // Theme supports
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', [
            'comment-list',
            'comment-form',
            'search-form',
            'gallery',
            'caption'
        ]);
        add_theme_support('customize-selective-refresh-widgets');

        // Navigation menus
        register_nav_menus([
            'primary' => __('Primary Menu', 'preetidreams'),
            'footer' => __('Footer Menu', 'preetidreams'),
        ]);

        // Custom image sizes for medical spa
        add_image_size('treatment-card', 400, 300, true);
        add_image_size('before-after', 600, 400, true);
        add_image_size('hero-banner', 1920, 800, true);
    });
}

/**
 * Fallback theme setup without Composer dependencies
 */
function preetidreams_fallback_setup() {
    add_action('after_setup_theme', 'preetidreams_theme_support');
    add_action('wp_enqueue_scripts', 'preetidreams_enqueue_scripts');
    add_action('admin_notices', 'preetidreams_admin_notice');
}

/**
 * Basic theme support
 */
function preetidreams_theme_support() {
    // Theme supports
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', [
        'comment-list',
        'comment-form',
        'search-form',
        'gallery',
        'caption'
    ]);
    add_theme_support('customize-selective-refresh-widgets');

    // Navigation menus
    register_nav_menus([
        'primary' => __('Primary Menu', 'preetidreams'),
        'footer' => __('Footer Menu', 'preetidreams'),
    ]);

    // Custom image sizes for medical spa
    add_image_size('treatment-card', 400, 300, true);
    add_image_size('before-after', 600, 400, true);
}

/**
 * Enqueue scripts and styles
 */
function preetidreams_enqueue_scripts() {
    // Basic styling
    wp_enqueue_style(
        'preetidreams-style',
        get_stylesheet_uri(),
        [],
        PREETIDREAMS_VERSION
    );

    // Check if Vite build exists
    $vite_manifest = get_template_directory() . '/public/build/manifest.json';

    if (file_exists($vite_manifest)) {
        // Use Vite-built assets
        $manifest = json_decode(file_get_contents($vite_manifest), true);

        if (isset($manifest['resources/styles/app.scss'])) {
            wp_enqueue_style(
                'preetidreams-app',
                get_template_directory_uri() . '/public/build/' . $manifest['resources/styles/app.scss']['file'],
                [],
                PREETIDREAMS_VERSION
            );
        }

        if (isset($manifest['resources/scripts/app.ts'])) {
            wp_enqueue_script(
                'preetidreams-app',
                get_template_directory_uri() . '/public/build/' . $manifest['resources/scripts/app.ts']['file'],
                [],
                PREETIDREAMS_VERSION,
                true
            );
        }
    }
}

// Enqueue scripts even when Acorn is loaded
add_action('wp_enqueue_scripts', 'preetidreams_enqueue_scripts');

/**
 * Admin notice for setup information
 */
function preetidreams_admin_notice() {
    $screen = get_current_screen();
    if ($screen && $screen->base !== 'themes') {
        return;
    }

    if (defined('PREETIDREAMS_ACORN_LOADED') && PREETIDREAMS_ACORN_LOADED) {
        echo '<div class="notice notice-success"><p>';
        echo '<strong>🎉 PreetiDreams Medical Spa Theme:</strong> ';
        echo 'Advanced features are active with Sage/Acorn integration! Full medical spa functionality is available.';
        echo '</p></div>';
    } else {
        echo '<div class="notice notice-warning"><p>';
        echo '<strong>PreetiDreams Medical Spa Theme:</strong> ';
        echo 'Running in basic mode. Run <code>composer run install-sage</code> for advanced features.';
        echo '</p></div>';
    }
}

// Show admin notice only on themes page
add_action('admin_notices', 'preetidreams_admin_notice');

/**
 * Theme activation hook
 */
function preetidreams_theme_activation() {
    // Flush rewrite rules
    flush_rewrite_rules();

    // Set default options
    if (!get_option('preetidreams_setup_complete')) {
        update_option('preetidreams_setup_complete', true);

        // Set default menu locations if menus exist
        $locations = get_theme_mod('nav_menu_locations');
        set_theme_mod('nav_menu_locations', $locations);
    }
}
add_action('after_switch_theme', 'preetidreams_theme_activation');

/**
 * Check PHP version and show admin notice if needed
 */
function preetidreams_php_version_check() {
    if (version_compare(PHP_VERSION, '8.0', '<')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>';
            echo '<strong>PreetiDreams Medical Spa Theme:</strong> ';
            echo 'This theme requires PHP 8.0 or higher. You are running PHP ' . PHP_VERSION . '. ';
            echo 'Please contact your hosting provider to upgrade PHP.';
            echo '</p></div>';
        });

        return false;
    }

    return true;
}

// Check PHP version
preetidreams_php_version_check();

/**
 * Customizer additions
 */
function preetidreams_customize_register($wp_customize) {
    // Medical Spa Settings
    $wp_customize->add_section('preetidreams_medical_spa', [
        'title' => __('Medical Spa Settings', 'preetidreams'),
        'priority' => 30,
    ]);

    // Phone number
    $wp_customize->add_setting('preetidreams_phone', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('preetidreams_phone', [
        'label' => __('Phone Number', 'preetidreams'),
        'section' => 'preetidreams_medical_spa',
        'type' => 'text',
    ]);

    // Address
    $wp_customize->add_setting('preetidreams_address', [
        'default' => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ]);

    $wp_customize->add_control('preetidreams_address', [
        'label' => __('Address', 'preetidreams'),
        'section' => 'preetidreams_medical_spa',
        'type' => 'textarea',
    ]);

    // Email
    $wp_customize->add_setting('preetidreams_email', [
        'default' => '',
        'sanitize_callback' => 'sanitize_email',
    ]);

    $wp_customize->add_control('preetidreams_email', [
        'label' => __('Email Address', 'preetidreams'),
        'section' => 'preetidreams_medical_spa',
        'type' => 'email',
    ]);
}
add_action('customize_register', 'preetidreams_customize_register');

/**
 * Widget areas
 */
function preetidreams_widgets_init() {
    register_sidebar([
        'name' => __('Footer Widgets', 'preetidreams'),
        'id' => 'footer-widgets',
        'description' => __('Add widgets here to appear in your footer.', 'preetidreams'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ]);
}
add_action('widgets_init', 'preetidreams_widgets_init');

/**
 * Medical Spa Custom Post Types
 */
function preetidreams_custom_post_types() {
    // Treatments post type
    register_post_type('treatment', [
        'labels' => [
            'name' => __('Treatments', 'preetidreams'),
            'singular_name' => __('Treatment', 'preetidreams'),
            'add_new' => __('Add New Treatment', 'preetidreams'),
            'add_new_item' => __('Add New Treatment', 'preetidreams'),
            'edit_item' => __('Edit Treatment', 'preetidreams'),
        ],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'menu_icon' => 'dashicons-heart',
        'rewrite' => ['slug' => 'treatments'],
        'show_in_rest' => true,
    ]);

    // Staff post type
    register_post_type('staff', [
        'labels' => [
            'name' => __('Staff', 'preetidreams'),
            'singular_name' => __('Staff Member', 'preetidreams'),
            'add_new' => __('Add Staff Member', 'preetidreams'),
            'add_new_item' => __('Add New Staff Member', 'preetidreams'),
            'edit_item' => __('Edit Staff Member', 'preetidreams'),
        ],
        'public' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
        'menu_icon' => 'dashicons-businessperson',
        'rewrite' => ['slug' => 'staff'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'preetidreams_custom_post_types');
