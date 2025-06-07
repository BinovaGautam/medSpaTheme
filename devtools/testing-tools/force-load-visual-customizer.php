<?php
/**
 * Emergency Visual Customizer Force Loader
 *
 * Add this to functions.php temporarily to force load Visual Customizer scripts
 * This bypasses permission checks and hook issues
 */

// Force load Visual Customizer scripts for testing (EMERGENCY FIX)
add_action('wp_enqueue_scripts', 'emergency_force_visual_customizer_scripts', 999);
add_action('admin_enqueue_scripts', 'emergency_force_visual_customizer_scripts', 999);

function emergency_force_visual_customizer_scripts() {
    // Skip permission check - EMERGENCY TESTING ONLY

    error_log('🚨 EMERGENCY: Force loading Visual Customizer scripts');

    // Core Live Preview System
    wp_enqueue_script(
        'emergency-live-preview-system',
        get_template_directory_uri() . '/assets/js/live-preview-system.js',
        ['jquery'],
        '1.0.0-emergency',
        true
    );

    // Semantic Color System
    wp_enqueue_script(
        'emergency-semantic-color-system',
        get_template_directory_uri() . '/assets/js/semantic-color-system.js',
        ['emergency-live-preview-system'],
        '1.0.0-emergency',
        true
    );

    // Color System Manager
    wp_enqueue_script(
        'emergency-color-system-manager',
        get_template_directory_uri() . '/assets/js/color-system-manager.js',
        ['emergency-semantic-color-system'],
        '1.0.0-emergency',
        true
    );

    // Color Palette Interface
    wp_enqueue_script(
        'emergency-color-palette-interface',
        get_template_directory_uri() . '/assets/js/color-palette-interface.js',
        ['emergency-color-system-manager'],
        '1.0.0-emergency',
        true
    );

    // Simple Visual Customizer
    wp_enqueue_script(
        'emergency-simple-visual-customizer',
        get_template_directory_uri() . '/assets/js/simple-visual-customizer.js',
        ['emergency-color-palette-interface'],
        '1.0.0-emergency',
        true
    );

    // CSS
    wp_enqueue_style(
        'emergency-simple-visual-customizer-css',
        get_template_directory_uri() . '/assets/css/simple-visual-customizer.css',
        [],
        '1.0.0-emergency'
    );

    // Localize script data
    wp_localize_script('emergency-simple-visual-customizer', 'simpleCustomizer', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('simple_visual_customizer'),
        'isAdmin' => true, // Force true for testing
        'debug' => true,
        'pvc005Enabled' => true,
        'capabilities' => [
            'livePreview' => true,
            'previewMessenger' => true,
            'wpCustomizerBridge' => true,
            'performanceMonitoring' => true
        ],
        'config' => [
            'updateDelay' => 50,
            'previewMode' => 'live',
            'cssVariablePrefix' => '--color-',
            'enableDebug' => true
        ],
        'strings' => [
            'loading' => 'Emergency loading...',
            'applying' => 'Emergency applying palette...',
            'applied' => 'Emergency palette applied!',
            'error' => 'Emergency error',
            'reset' => 'Emergency reset',
            'performance' => 'Emergency performance'
        ]
    ]);

    error_log('🚨 EMERGENCY: Visual Customizer scripts force loaded');
}

// Force add admin bar Visual Customizer for ALL users (TESTING ONLY)
add_action('admin_bar_menu', 'emergency_visual_customizer_admin_bar', 999);
function emergency_visual_customizer_admin_bar($wp_admin_bar) {
    $wp_admin_bar->add_node([
        'id'    => 'emergency-visual-customizer',
        'title' => '🚨 Emergency Visual Customizer',
        'href'  => '#',
        'meta'  => [
            'class' => 'visual-customizer-trigger',
            'title' => 'Emergency Visual Customizer - Force Loaded'
        ]
    ]);
}

// Add emergency CSS to make Visual Customizer visible
add_action('wp_head', 'emergency_visual_customizer_css');
add_action('admin_head', 'emergency_visual_customizer_css');
function emergency_visual_customizer_css() {
    ?>
    <style>
    /* Emergency Visual Customizer CSS */
    #wpadminbar #wp-admin-bar-emergency-visual-customizer {
        background: #dc3545 !important;
    }
    #wpadminbar #wp-admin-bar-emergency-visual-customizer > .ab-item {
        background: #dc3545 !important;
        color: white !important;
        font-weight: bold !important;
    }
    #wpadminbar #wp-admin-bar-emergency-visual-customizer:hover > .ab-item {
        background: #c82333 !important;
    }
    </style>
    <?php
}

// Emergency console logging
add_action('wp_footer', 'emergency_visual_customizer_console_debug');
add_action('admin_footer', 'emergency_visual_customizer_console_debug');
function emergency_visual_customizer_console_debug() {
    ?>
    <script>
    console.log('🚨 EMERGENCY VISUAL CUSTOMIZER DEBUG');
    console.log('Emergency scripts loaded:', {
        'live-preview': typeof LivePreviewSystem !== 'undefined',
        'color-system': typeof ColorSystemManager !== 'undefined',
        'palette-interface': typeof ColorPaletteInterface !== 'undefined',
        'simple-customizer': typeof simpleVisualCustomizer !== 'undefined'
    });

    // Auto-test in 3 seconds
    setTimeout(() => {
        console.log('🚨 Running emergency auto-test...');
        if (typeof LivePreviewSystem !== 'undefined') {
            try {
                window.emergencyLivePreview = new LivePreviewSystem();
                console.log('✅ Emergency LivePreviewSystem created successfully');
            } catch (e) {
                console.error('❌ Emergency LivePreviewSystem failed:', e);
            }
        }
    }, 3000);
    </script>
    <?php
}
?>
