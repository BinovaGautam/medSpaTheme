<?php
/**
 * Visual Customizer Integration
 * Provides WordPress integration for the new visual customizer system
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Visual Customizer Admin Notice
 */
function preetidreams_visual_customizer_admin_notice() {
    $screen = get_current_screen();
    if ($screen && $screen->base === 'themes') {
        echo '<div class="notice notice-info is-dismissible">';
        echo '<p><strong>🎨 Visual Customizer Active:</strong> ';
        echo 'The new visual customizer is now available on your website. Look for the floating customizer button on the right side of any page. ';
        echo 'WordPress Appearance → Customize has been replaced with this enhanced system.';
        echo '</p></div>';
    }
}
add_action('admin_notices', 'preetidreams_visual_customizer_admin_notice');

/**
 * Hide WordPress Customizer Menu Item
 */
function preetidreams_hide_wordpress_customizer() {
    global $submenu;
    if (isset($submenu['themes.php'])) {
        foreach ($submenu['themes.php'] as $key => $menu_item) {
            if ($menu_item[0] === 'Customize') {
                unset($submenu['themes.php'][$key]);
                break;
            }
        }
    }
}
add_action('admin_menu', 'preetidreams_hide_wordpress_customizer', 999);

/**
 * Add Visual Customizer Settings Page
 */
function preetidreams_add_visual_customizer_admin_page() {
    add_theme_page(
        'Visual Customizer Settings',
        'Visual Customizer',
        'manage_options',
        'visual-customizer-settings',
        'preetidreams_visual_customizer_admin_page'
    );
}
add_action('admin_menu', 'preetidreams_add_visual_customizer_admin_page');

/**
 * Visual Customizer Admin Page Content
 */
function preetidreams_visual_customizer_admin_page() {
    ?>
    <div class="wrap">
        <h1>🎨 Visual Customizer Settings</h1>
        <p>Manage the enhanced visual customizer system that has replaced the standard WordPress customizer.</p>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-top: 20px;">
            <div class="card">
                <h2>How to Use the Visual Customizer</h2>
                <ol>
                    <li><strong>Visit your website:</strong> Navigate to any page on your site</li>
                    <li><strong>Find the floating button:</strong> Look for the 🎨 button on the right side of the screen</li>
                    <li><strong>Click to open:</strong> Click the button to open the customizer drawer</li>
                    <li><strong>Customize visually:</strong> Select from:
                        <ul>
                            <li>🎨 <strong>Color Palettes:</strong> 7 professional color schemes</li>
                            <li>📝 <strong>Typography:</strong> Premium Google Fonts with live preview</li>
                            <li>⚙️ <strong>Style Controls:</strong> Header styles, button shapes, animations</li>
                        </ul>
                    </li>
                    <li><strong>Real-time preview:</strong> Changes apply instantly as you select them</li>
                    <li><strong>Auto-save:</strong> Your preferences are saved automatically</li>
                </ol>

                <h3>Available Features</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
                    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 8px;">
                        <h4>🎨 Color Palettes</h4>
                        <ul style="margin: 0; padding-left: 20px;">
                            <li>Classic Forest (Default)</li>
                            <li>Ocean Blue</li>
                            <li>Rose Gold</li>
                            <li>Sage Mint</li>
                            <li>Lavender Grey</li>
                            <li>Warm Earth</li>
                            <li>Modern Monochrome</li>
                        </ul>
                    </div>

                    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 8px;">
                        <h4>📝 Typography</h4>
                        <ul style="margin: 0; padding-left: 20px;">
                            <li>Heading Fonts (Serif)</li>
                            <li>Body Fonts (Sans-serif)</li>
                            <li>Font Size Controls</li>
                            <li>Live Font Previews</li>
                        </ul>
                    </div>

                    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 8px;">
                        <h4>⚙️ Style Controls</h4>
                        <ul style="margin: 0; padding-left: 20px;">
                            <li>Header Styles</li>
                            <li>Button Shapes</li>
                            <li>Animation Controls</li>
                            <li>Accessibility Options</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div>
                <div class="card">
                    <h3>Quick Access</h3>
                    <p><a href="<?php echo home_url(); ?>" class="button button-primary" target="_blank">
                        🌐 View Website with Customizer
                    </a></p>
                    <p><small>Opens your website in a new tab where you can use the visual customizer.</small></p>
                </div>

                <div class="card">
                    <h3>Technical Details</h3>
                    <table class="form-table">
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td><span style="color: green;">✅ Active</span></td>
                        </tr>
                        <tr>
                            <td><strong>Storage:</strong></td>
                            <td>Browser localStorage</td>
                        </tr>
                        <tr>
                            <td><strong>Compatibility:</strong></td>
                            <td>Modern browsers</td>
                        </tr>
                        <tr>
                            <td><strong>Accessibility:</strong></td>
                            <td>WCAG 2.1 AA compliant</td>
                        </tr>
                    </table>
                </div>

                <div class="card">
                    <h3>Reset Options</h3>
                    <p>Users can reset their customizations using the "Reset to Default" button in the customizer drawer.</p>
                    <p><small>Note: This affects only the user's browser settings, not global theme settings.</small></p>
                </div>
            </div>
        </div>

        <div class="card" style="margin-top: 20px;">
            <h3>Advantages Over WordPress Customizer</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div>
                    <h4>✨ Enhanced User Experience</h4>
                    <ul>
                        <li>Floating access from any page</li>
                        <li>No admin area navigation needed</li>
                        <li>Modern drawer interface</li>
                        <li>Mobile-optimized design</li>
                    </ul>
                </div>

                <div>
                    <h4>🎨 Visual Design</h4>
                    <ul>
                        <li>Visual color palette selection</li>
                        <li>Live font previews</li>
                        <li>Real-time changes</li>
                        <li>Professional design options</li>
                    </ul>
                </div>

                <div>
                    <h4>⚡ Performance</h4>
                    <ul>
                        <li>Client-side processing</li>
                        <li>No page reloads</li>
                        <li>Instant feedback</li>
                        <li>Lightweight implementation</li>
                    </ul>
                </div>

                <div>
                    <h4>♿ Accessibility</h4>
                    <ul>
                        <li>Keyboard navigation</li>
                        <li>Screen reader support</li>
                        <li>Reduced motion options</li>
                        <li>High contrast support</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            background: white;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            padding: 20px;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }

        .card h2, .card h3 {
            margin-top: 0;
        }

        .card ul {
            margin-bottom: 0;
        }

        .form-table td {
            padding: 8px 0;
        }
    </style>
    <?php
}

/**
 * AJAX Handler for Visual Customizer Settings
 */
function preetidreams_handle_visual_customizer_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'visual_customizer_nonce')) {
        wp_send_json_error('Security check failed');
    }

    $action_type = sanitize_text_field($_POST['action_type']);

    switch ($action_type) {
        case 'save_settings':
            $settings = wp_unslash($_POST['settings']);
            // Log settings for analytics (optional)
            do_action('preetidreams_visual_customizer_settings_saved', $settings);
            wp_send_json_success(['message' => 'Settings saved successfully']);
            break;

        case 'reset_settings':
            // Log reset action (optional)
            do_action('preetidreams_visual_customizer_reset');
            wp_send_json_success(['message' => 'Settings reset successfully']);
            break;

        default:
            wp_send_json_error('Invalid action');
    }
}
add_action('wp_ajax_visual_customizer_action', 'preetidreams_handle_visual_customizer_ajax');
add_action('wp_ajax_nopriv_visual_customizer_action', 'preetidreams_handle_visual_customizer_ajax');

/**
 * Add Customizer Button to Admin Bar
 */
function preetidreams_add_customizer_admin_bar($wp_admin_bar) {
    if (!is_admin()) {
        $wp_admin_bar->add_node([
            'id' => 'visual-customizer',
            'title' => '🎨 Visual Customizer',
            'href' => '#',
            'meta' => [
                'class' => 'visual-customizer-admin-bar-trigger',
                'onclick' => 'if(window.visualCustomizer) window.visualCustomizer.openDrawer(); return false;'
            ]
        ]);
    }
}
add_action('admin_bar_menu', 'preetidreams_add_customizer_admin_bar', 100);

/**
 * Disable WordPress Customizer Preview
 */
function preetidreams_disable_customizer_preview() {
    global $wp_customize;
    if (isset($wp_customize)) {
        remove_action('wp_head', [$wp_customize, 'customize_preview_init']);
    }
}
add_action('init', 'preetidreams_disable_customizer_preview');

/**
 * Add Visual Customizer Body Classes
 */
function preetidreams_visual_customizer_body_classes($classes) {
    $classes[] = 'has-visual-customizer';

    // Add class to indicate customizer availability
    if (!is_admin()) {
        $classes[] = 'visual-customizer-enabled';
    }

    return $classes;
}
add_filter('body_class', 'preetidreams_visual_customizer_body_classes');

/**
 * Enqueue Admin Styles for Visual Customizer
 */
function preetidreams_visual_customizer_admin_styles() {
    wp_add_inline_style('wp-admin', '
        .visual-customizer-admin-bar-trigger {
            background: linear-gradient(135deg, #1B365D 0%, #87A96B 100%) !important;
        }
        .visual-customizer-admin-bar-trigger:hover {
            background: linear-gradient(135deg, #87A96B 0%, #D4AF37 100%) !important;
        }
        .visual-customizer-admin-bar-trigger a {
            color: white !important;
        }
    ');
}
add_action('admin_enqueue_scripts', 'preetidreams_visual_customizer_admin_styles');
add_action('wp_enqueue_scripts', 'preetidreams_visual_customizer_admin_styles');

/**
 * Add Visual Customizer Meta Tags
 */
function preetidreams_visual_customizer_meta_tags() {
    echo '<meta name="visual-customizer" content="enabled">' . "\n";
    echo '<meta name="visual-customizer-version" content="' . PREETIDREAMS_VERSION . '">' . "\n";
}
add_action('wp_head', 'preetidreams_visual_customizer_meta_tags');

/**
 * Analytics Hooks for Visual Customizer Usage
 */
add_action('preetidreams_visual_customizer_settings_saved', function($settings) {
    // Log customizer usage for analytics
    error_log('Visual Customizer: Settings saved - ' . json_encode($settings));
});

add_action('preetidreams_visual_customizer_reset', function() {
    // Log customizer reset for analytics
    error_log('Visual Customizer: Settings reset by user');
});

/**
 * Check if Visual Customizer is Active
 */
function preetidreams_is_visual_customizer_active() {
    return true; // Always active when this file is loaded
}

/**
 * Get Visual Customizer Default Settings
 */
function preetidreams_get_visual_customizer_defaults() {
    return [
        'colorPalette' => 'classic-forest',
        'fontHeading' => 'playfair-display',
        'fontBody' => 'inter',
        'fontSize' => 'normal',
        'headerStyle' => 'transparent',
        'buttonStyle' => 'rounded',
        'animations' => true
    ];
}
