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
                'onclick' => 'if(window.openThemeCustomizer) window.openThemeCustomizer(); else if(window.visualCustomizer) window.visualCustomizer.openPanel(); return false;'
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
 * Add Body Classes for Visual Customizer (Only when panel is actually open)
 */
function preetidreams_visual_customizer_body_classes($classes) {
    // Only add class if explicitly requested (when panel is open)
    // This prevents automatic layout changes when customizer is just available
    // The class will be added via JavaScript when panel actually opens

    // REMOVED: Automatic class addition that caused layout issues
    // if (current_user_can('manage_options') && !is_admin()) {
    //     $classes[] = 'has-visual-customizer';
    // }

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
        'headerStyle' => 'solid',
        'buttonStyle' => 'rounded',
        'animations' => true
    ];
}

/**
 * Enqueue Visual Customizer Assets (Admin Only with Global Configuration)
 */
function preetidreams_enqueue_visual_customizer() {
    // Only enqueue for admin users
    if (!current_user_can('manage_options')) {
        return;
    }

    // Don't load in admin area
    if (is_admin()) {
        return;
    }

    // Enqueue Visual Customizer Redesign JavaScript (standalone, no dependencies)
    wp_enqueue_script(
        'visual-customizer-redesign',
        get_template_directory_uri() . '/assets/js/visual-customizer-redesign.js',
        [], // No dependencies - standalone implementation
        PREETIDREAMS_VERSION,
        true
    );

    // Enqueue Visual Customizer Redesign CSS ONLY
    wp_enqueue_style(
        'visual-customizer-redesign',
        get_template_directory_uri() . '/assets/css/visual-customizer-redesign.css',
        [],
        PREETIDREAMS_VERSION
    );

    // Color palette data
    $color_palettes = [
        'classic-forest' => [
            'name' => 'Classic Forest',
            'primary' => '#1B365D',
            'secondary' => '#87A96B',
            'accent' => '#D4AF37',
            'light' => '#FDFCFA',
            'dark' => '#2C3E50'
        ],
        'ocean-blue' => [
            'name' => 'Ocean Blue',
            'primary' => '#2E5984',
            'secondary' => '#6B9BD1',
            'accent' => '#F4A261',
            'light' => '#F8FAFC',
            'dark' => '#1E293B'
        ],
        'rose-gold' => [
            'name' => 'Rose Gold',
            'primary' => '#8B4B7A',
            'secondary' => '#E4A853',
            'accent' => '#C2847A',
            'light' => '#FDF2F8',
            'dark' => '#374151'
        ],
        'sage-mint' => [
            'name' => 'Sage Mint',
            'primary' => '#5F7A61',
            'secondary' => '#A8C4A2',
            'accent' => '#F7E7CE',
            'light' => '#F9FDF9',
            'dark' => '#1F2937'
        ],
        'lavender-grey' => [
            'name' => 'Lavender Grey',
            'primary' => '#6B7280',
            'secondary' => '#A78BFA',
            'accent' => '#F3E8FF',
            'light' => '#FAFAFA',
            'dark' => '#111827'
        ],
        'warm-earth' => [
            'name' => 'Warm Earth',
            'primary' => '#92400E',
            'secondary' => '#D97706',
            'accent' => '#FED7AA',
            'light' => '#FFFBEB',
            'dark' => '#1F2937'
        ],
        'modern-monochrome' => [
            'name' => 'Modern Monochrome',
            'primary' => '#1F2937',
            'secondary' => '#6B7280',
            'accent' => '#F3F4F6',
            'light' => '#FFFFFF',
            'dark' => '#000000'
        ]
    ];

    // Font options
    $font_options = [
        'heading_fonts' => [
            'playfair-display' => ['name' => 'Playfair Display', 'url' => 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap'],
            'crimson-text' => ['name' => 'Crimson Text', 'url' => 'https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600;700&display=swap'],
            'libre-baskerville' => ['name' => 'Libre Baskerville', 'url' => 'https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap'],
            'cormorant-garamond' => ['name' => 'Cormorant Garamond', 'url' => 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&display=swap']
        ],
        'body_fonts' => [
            'inter' => ['name' => 'Inter', 'url' => 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap'],
            'source-sans-pro' => ['name' => 'Source Sans Pro', 'url' => 'https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap'],
            'nunito-sans' => ['name' => 'Nunito Sans', 'url' => 'https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700&display=swap'],
            'open-sans' => ['name' => 'Open Sans', 'url' => 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap']
        ]
    ];

    // Tooltips for educational purpose
    $tooltips = [
        'primary' => [
            'title' => 'Primary Brand Color',
            'usage' => 'Used for navigation bars, main buttons, and header elements',
            'examples' => ['Navigation backgrounds', 'Call-to-action buttons', 'Header elements', 'Important links']
        ],
        'secondary' => [
            'title' => 'Secondary Support Color',
            'usage' => 'Perfect for accent elements and supporting content areas',
            'examples' => ['Section backgrounds', 'Hover effects', 'Accent borders', 'Icon highlights']
        ],
        'accent' => [
            'title' => 'Accent Highlight Color',
            'usage' => 'Great for special highlights and premium features',
            'examples' => ['Special offers', 'Premium badges', 'Success messages', 'Featured content']
        ],
        'light' => [
            'title' => 'Light Background',
            'usage' => 'Main content areas and card backgrounds for easy reading',
            'examples' => ['Page backgrounds', 'Content cards', 'Form backgrounds', 'Modal windows']
        ],
        'dark' => [
            'title' => 'Dark Text & Elements',
            'usage' => 'Text content and darker UI elements for contrast',
            'examples' => ['Body text', 'Headings', 'Form labels', 'Footer content']
        ]
    ];

    // Localize script data for Redesigned Customizer
    wp_localize_script('visual-customizer-redesign', 'visualCustomizerData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('visual_customizer_global_config'),
        'isAdmin' => current_user_can('manage_options'),
        'colorPalettes' => $color_palettes,
        'fontOptions' => $font_options,
        'tooltips' => $tooltips,
        'themeVersion' => PREETIDREAMS_VERSION,
        'implementation' => 'redesign', // Indicate this is the redesigned version
        'i18n' => [
            'title' => __('Theme Customizer', 'preetidreams'),
            'subtitle' => __('Customize your website appearance', 'preetidreams'),
            'colorScheme' => __('Color Scheme', 'preetidreams'),
            'typography' => __('Typography', 'preetidreams'),
            'layoutControls' => __('Layout Controls', 'preetidreams'),
            'livePreview' => __('Live Preview', 'preetidreams'),
            'applyGlobally' => __('Apply Globally', 'preetidreams'),
            'loading' => __('Loading...', 'preetidreams'),
            'applied' => __('Configuration Applied Successfully', 'preetidreams'),
            'error' => __('Error applying configuration', 'preetidreams'),
            'adminOnly' => __('Admin Only: Changes will be applied globally for all visitors', 'preetidreams'),
            'educationalIntro' => __('Choose colors that create the perfect medical spa atmosphere. Each color has been carefully selected for professionalism and trust.', 'preetidreams')
        ]
    ]);
}
add_action('wp_enqueue_scripts', 'preetidreams_enqueue_visual_customizer');

/**
 * AJAX Handler for Global Visual Customizer Configuration - FIXED
 */
function preetidreams_save_visual_customizer_global_config() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'visual_customizer_global_config')) {
        wp_send_json_error('Security check failed');
    }

    // Verify admin permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Insufficient permissions');
    }

    // Parse JSON configuration data
    $config_json = wp_unslash($_POST['config']);
    $config = json_decode($config_json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        wp_send_json_error('Invalid JSON configuration data');
    }

    // Sanitize configuration data
    $sanitized_config = [
        'colorPalette' => sanitize_text_field($config['colorPalette'] ?? 'classic-forest'),
        'fontHeading' => sanitize_text_field($config['fontHeading'] ?? 'playfair-display'),
        'fontBody' => sanitize_text_field($config['fontBody'] ?? 'inter'),
        'fontSize' => sanitize_text_field($config['fontSize'] ?? 'normal'),
        'headerStyle' => sanitize_text_field($config['headerStyle'] ?? 'solid'),
        'buttonStyle' => sanitize_text_field($config['buttonStyle'] ?? 'rounded'),
        'animations' => wp_validate_boolean($config['animations'] ?? true)
    ];

    // Get current config to compare
    $current_config = get_option('preetidreams_visual_customizer_global_config', []);

    // Save to WordPress options
    $saved = update_option('preetidreams_visual_customizer_global_config', $sanitized_config);

    // FIXED: Handle the case where update_option returns false if value is unchanged
    if ($saved !== false || $current_config === $sanitized_config) {
        // Log the configuration change
        do_action('preetidreams_visual_customizer_global_config_saved', $sanitized_config, get_current_user_id());

        wp_send_json_success([
            'message' => 'Global configuration saved successfully',
            'config' => $sanitized_config,
            'timestamp' => current_time('mysql'),
            'status' => $saved ? 'updated' : 'unchanged'
        ]);
    } else {
        // Only error if there was an actual database error
        wp_send_json_error('Database error occurred while saving configuration');
    }
}
add_action('wp_ajax_save_visual_customizer_global_config', 'preetidreams_save_visual_customizer_global_config');

/**
 * Get Global Visual Customizer Configuration
 */
function preetidreams_get_visual_customizer_global_config() {
    $default_config = [
        'colorPalette' => 'classic-forest',
        'fontHeading' => 'playfair-display',
        'fontBody' => 'inter',
        'fontSize' => 'normal',
        'headerStyle' => 'solid',
        'buttonStyle' => 'rounded',
        'animations' => true
    ];

    return get_option('preetidreams_visual_customizer_global_config', $default_config);
}

/**
 * Output Global Configuration JavaScript
 */
function preetidreams_output_visual_customizer_global_config_js() {
    $global_config = preetidreams_get_visual_customizer_global_config();
    ?>
    <script>
    // Global configuration data for JavaScript
    window.preetidreamsGlobalConfig = <?php echo wp_json_encode($global_config); ?>;
    </script>
    <?php
}

/**
 * Initialize Visual Customizer on Frontend
 */
function preetidreams_init_visual_customizer() {
    // Load global CSS for all users
    add_action('wp_head', 'preetidreams_add_visual_customizer_global_css');

    // Output global configuration JavaScript for all users
    add_action('wp_head', 'preetidreams_output_visual_customizer_global_config_js');

    // Only add trigger and admin features for admin users
    if (current_user_can('manage_options') && !is_admin()) {
        add_action('wp_footer', 'preetidreams_add_visual_customizer_trigger');
    }
}
add_action('init', 'preetidreams_init_visual_customizer');

/**
 * Add Visual Customizer Trigger Button to Footer
 */
function preetidreams_add_visual_customizer_trigger() {
    ?>
    <script>
    // Initialize Visual Customizer Redesign when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Create global function for admin bar trigger
        window.openThemeCustomizer = function() {
            if (window.VisualCustomizerRedesign && window.visualCustomizer) {
                window.visualCustomizer.openPanel();
            } else if (window.visualCustomizer) {
                window.visualCustomizer.openPanel();
            } else {
                console.log('Visual Customizer Redesign loading...');
                setTimeout(window.openThemeCustomizer, 100);
            }
        };

        // Auto-initialize redesigned customizer
        if (window.VisualCustomizerRedesign && window.visualCustomizerData) {
            window.visualCustomizer = new VisualCustomizerRedesign();
        }
    });
    </script>
    <?php
}

/**
 * Add Global CSS for Visual Customizer - ENHANCED WITH FACTORY PATTERN
 */
function preetidreams_add_visual_customizer_global_css() {
    // Load global configuration
    $global_config = preetidreams_get_visual_customizer_global_config();

    // Factory Pattern: Create customizer based on configuration
    $customizer_factory = new PreetiDreamsCustomizerFactory();
    $color_customizer = $customizer_factory->createColorCustomizer($global_config['colorPalette']);
    $typography_customizer = $customizer_factory->createTypographyCustomizer($global_config);
    $layout_customizer = $customizer_factory->createLayoutCustomizer($global_config);

    // Get color palette
    $selected_palette = $color_customizer->getPalette();

    ?>
    <style id="visual-customizer-global-css">
    /* Visual Customizer Admin Bar Styling */
    #wp-admin-bar-visual-customizer {
        background: linear-gradient(135deg, #1B365D 0%, #87A96B 100%);
    }

    #wp-admin-bar-visual-customizer:hover {
        background: linear-gradient(135deg, #87A96B 0%, #D4AF37 100%);
    }

    #wp-admin-bar-visual-customizer a {
        color: white !important;
        text-decoration: none !important;
    }

    #wp-admin-bar-visual-customizer a:hover {
        color: white !important;
    }

    /* Ensure admin bar is visible */
    #wpadminbar {
        z-index: 99999;
    }

    /* Visual Customizer accessibility */
    .visual-customizer-admin-bar-trigger {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .visual-customizer-admin-bar-trigger:focus {
        outline: 2px solid #D4AF37;
        outline-offset: 2px;
    }

    /* Hide standard customizer if it exists */
    #wp-admin-bar-customize {
        display: none !important;
    }

    /* Admin notice styling */
    body.admin-bar {
        --admin-bar-height: 32px;
    }

    @media screen and (max-width: 782px) {
        body.admin-bar {
            --admin-bar-height: 46px;
        }
    }

    /* ============================================================================
       GLOBAL VISUAL CUSTOMIZER CONFIGURATION STYLES - FACTORY PATTERN
       ============================================================================ */

    :root {
        /* Applied Color Palette: <?php echo esc_html($selected_palette['name']); ?> */
        --color-primary: <?php echo esc_html($selected_palette['primary']); ?>;
        --color-secondary: <?php echo esc_html($selected_palette['secondary']); ?>;
        --color-accent: <?php echo esc_html($selected_palette['accent']); ?>;
        --color-light: <?php echo esc_html($selected_palette['light']); ?>;
        --color-dark: <?php echo esc_html($selected_palette['dark']); ?>;

        /* Typography Settings */
        --font-heading: '<?php echo esc_html($global_config['fontHeading']); ?>';
        --font-body: '<?php echo esc_html($global_config['fontBody']); ?>';
        --font-size-base: <?php echo $typography_customizer->getFontSize(); ?>;
    }

    /* ============================================================================
       COMPREHENSIVE THEME ELEMENT STYLING - ENHANCED COVERAGE
       ============================================================================ */

    /* BODY AND BACKGROUND */
    body {
        background-color: var(--color-light) !important;
        color: var(--color-dark) !important;
        font-family: var(--font-body), sans-serif !important;
        font-size: var(--font-size-base) !important;
    }

    /* HEADERS AND NAVIGATION */
    <?php echo $layout_customizer->getHeaderStyles(); ?>

    /* TYPOGRAPHY - HEADINGS */
    h1, h2, h3, h4, h5, h6,
    .page-title, .post-title, .section-title, .widget-title,
    .entry-title, .hero-title, .treatment-title,
    .site-title, .professional-header h1, .professional-header h2 {
        font-family: var(--font-heading), serif !important;
        color: var(--color-primary) !important;
    }

    /* TYPOGRAPHY - BODY TEXT */
    p, span, div, li, a, label, input, textarea, select,
    .entry-content, .page-content, .post-content,
    .widget-content, .description, .excerpt {
        font-family: var(--font-body), sans-serif !important;
        color: var(--color-dark) !important;
    }

    /* BUTTONS - COMPREHENSIVE COVERAGE */
    <?php echo $layout_customizer->getButtonStyles(); ?>

    /* LINKS AND NAVIGATION */
    a, .nav-link, .menu-item a, .navigation a {
        color: var(--color-primary) !important;
        transition: color 0.3s ease !important;
    }

    a:hover, .nav-link:hover, .menu-item a:hover, .navigation a:hover {
        color: var(--color-secondary) !important;
    }

    /* MAIN NAVIGATION */
    .main-navigation, .primary-navigation, .navbar, .nav-menu,
    .site-navigation, .professional-header nav {
        font-family: var(--font-body), sans-serif !important;
    }

    .main-navigation a, .primary-navigation a, .navbar a, .nav-menu a,
    .site-navigation a, .professional-header nav a {
        color: var(--color-primary) !important;
    }

    .main-navigation a:hover, .primary-navigation a:hover, .navbar a:hover, .nav-menu a:hover,
    .site-navigation a:hover, .professional-header nav a:hover {
        color: var(--color-secondary) !important;
    }

    /* CARDS AND CONTENT BLOCKS */
    .card, .treatment-card, .service-card, .post-card,
    .content-block, .widget, .sidebar-widget,
    .feature-box, .info-box, .testimonial {
        background-color: var(--color-light) !important;
        border-color: rgba(<?php echo $color_customizer->hexToRgb($selected_palette['primary']); ?>, 0.1) !important;
    }

    /* FORMS */
    input[type="text"], input[type="email"], input[type="tel"],
    input[type="password"], textarea, select,
    .form-control, .input-field {
        font-family: var(--font-body), sans-serif !important;
        border-color: rgba(<?php echo $color_customizer->hexToRgb($selected_palette['primary']); ?>, 0.3) !important;
        background-color: var(--color-light) !important;
        color: var(--color-dark) !important;
    }

    input[type="text"]:focus, input[type="email"]:focus, input[type="tel"]:focus,
    input[type="password"]:focus, textarea:focus, select:focus,
    .form-control:focus, .input-field:focus {
        border-color: var(--color-secondary) !important;
        box-shadow: 0 0 0 2px rgba(<?php echo $color_customizer->hexToRgb($selected_palette['secondary']); ?>, 0.2) !important;
    }

    /* SECTIONS AND CONTAINERS */
    .hero-section, .about-section, .services-section, .treatments-section,
    .contact-section, .footer-section, .testimonials-section {
        font-family: var(--font-body), sans-serif !important;
    }

    /* ACCENT ELEMENTS */
    .accent, .highlight, .featured, .special-offer,
    .badge, .tag, .label, .price, .call-to-action {
        background-color: var(--color-accent) !important;
        color: var(--color-dark) !important;
    }

    /* FOOTER */
    .site-footer, .footer, .page-footer {
        background-color: var(--color-primary) !important;
        color: var(--color-light) !important;
    }

    .site-footer a, .footer a, .page-footer a {
        color: var(--color-light) !important;
    }

    .site-footer a:hover, .footer a:hover, .page-footer a:hover {
        color: var(--color-accent) !important;
    }

    /* MEDICAL SPA SPECIFIC ELEMENTS */
    .treatment-grid, .service-grid, .doctor-profile,
    .appointment-form, .consultation-form, .booking-form,
    .price-list, .procedure-info, .before-after {
        font-family: var(--font-body), sans-serif !important;
    }

    .treatment-price, .service-price, .consultation-price {
        color: var(--color-accent) !important;
        font-weight: 600 !important;
    }

    /* ANIMATION CONTROLS */
    <?php if (!$global_config['animations']): ?>
    * {
        animation: none !important;
        transition: none !important;
    }
    <?php endif; ?>

    /* RESPONSIVE TYPOGRAPHY */
    @media (max-width: 768px) {
        body {
            font-size: calc(var(--font-size-base) * 0.9) !important;
        }

        h1, h2, h3, h4, h5, h6 {
            font-size: calc(1em * 0.9) !important;
        }
    }

    /* THEME-SPECIFIC OVERRIDES */
    .elementor-widget-heading .elementor-heading-title {
        font-family: var(--font-heading), serif !important;
        color: var(--color-primary) !important;
    }

    .elementor-widget-text-editor {
        font-family: var(--font-body), sans-serif !important;
        color: var(--color-dark) !important;
    }

    .elementor-button {
        background-color: var(--color-primary) !important;
        color: var(--color-light) !important;
        border-radius: <?php echo $layout_customizer->getButtonRadius(); ?> !important;
    }

    .elementor-button:hover {
        background-color: var(--color-secondary) !important;
    }
    </style>
    <?php
}

/**
 * Factory Pattern Implementation for Visual Customizer
 */
class PreetiDreamsCustomizerFactory {

    public function createColorCustomizer($palette_key) {
        return new PreetiDreamsColorCustomizer($palette_key);
    }

    public function createTypographyCustomizer($config) {
        return new PreetiDreamsTypographyCustomizer($config);
    }

    public function createLayoutCustomizer($config) {
        return new PreetiDreamsLayoutCustomizer($config);
    }
}

/**
 * Color Customizer Class
 */
class PreetiDreamsColorCustomizer {
    private $palette_key;
    private $palettes;

    public function __construct($palette_key) {
        $this->palette_key = $palette_key;
        $this->palettes = [
            'classic-forest' => [
                'name' => 'Classic Forest',
                'primary' => '#1B365D',
                'secondary' => '#87A96B',
                'accent' => '#D4AF37',
                'light' => '#FDFCFA',
                'dark' => '#2C3E50'
            ],
            'ocean-blue' => [
                'name' => 'Ocean Blue',
                'primary' => '#2E5984',
                'secondary' => '#6B9BD1',
                'accent' => '#F4A261',
                'light' => '#F8FAFC',
                'dark' => '#1E293B'
            ],
            'rose-gold' => [
                'name' => 'Rose Gold',
                'primary' => '#8B4B7A',
                'secondary' => '#E4A853',
                'accent' => '#C2847A',
                'light' => '#FDF2F8',
                'dark' => '#374151'
            ],
            'sage-mint' => [
                'name' => 'Sage Mint',
                'primary' => '#5F7A61',
                'secondary' => '#A8C4A2',
                'accent' => '#F7E7CE',
                'light' => '#F9FDF9',
                'dark' => '#1F2937'
            ],
            'lavender-grey' => [
                'name' => 'Lavender Grey',
                'primary' => '#6B7280',
                'secondary' => '#A78BFA',
                'accent' => '#F3E8FF',
                'light' => '#FAFAFA',
                'dark' => '#111827'
            ],
            'warm-earth' => [
                'name' => 'Warm Earth',
                'primary' => '#92400E',
                'secondary' => '#D97706',
                'accent' => '#FED7AA',
                'light' => '#FFFBEB',
                'dark' => '#1F2937'
            ],
            'modern-monochrome' => [
                'name' => 'Modern Monochrome',
                'primary' => '#1F2937',
                'secondary' => '#6B7280',
                'accent' => '#F3F4F6',
                'light' => '#FFFFFF',
                'dark' => '#000000'
            ]
        ];
    }

    public function getPalette() {
        return $this->palettes[$this->palette_key] ?? $this->palettes['classic-forest'];
    }

    public function hexToRgb($hex) {
        $hex = ltrim($hex, '#');
        return implode(', ', [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        ]);
    }
}

/**
 * Typography Customizer Class
 */
class PreetiDreamsTypographyCustomizer {
    private $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function getFontSize() {
        switch ($this->config['fontSize']) {
            case 'small': return '14px';
            case 'large': return '18px';
            default: return '16px';
        }
    }
}

/**
 * Layout Customizer Class
 */
class PreetiDreamsLayoutCustomizer {
    private $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function getHeaderStyles() {
        if ($this->config['headerStyle'] === 'transparent') {
            return '
            .site-header, header, .professional-header,
            .main-navigation, .primary-navigation, .navbar {
                background: rgba(var(--color-primary-rgb), 0.9) !important;
                backdrop-filter: blur(10px) !important;
                color: var(--color-light) !important;
            }

            .site-header a, header a, .professional-header a {
                color: var(--color-light) !important;
            }';
        } else {
            return '
            .site-header, header, .professional-header,
            .main-navigation, .primary-navigation, .navbar {
                background: var(--color-light) !important;
                border-bottom: 1px solid rgba(var(--color-primary-rgb), 0.1) !important;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
                color: var(--color-dark) !important;
            }';
        }
    }

    public function getButtonStyles() {
        $radius = $this->getButtonRadius();
        return '
        button, .button, .btn, input[type="submit"], input[type="button"],
        .wp-block-button__link, .elementor-button,
        .btn-primary, .btn-secondary, .btn-consultation,
        .book-appointment, .contact-button, .cta-button {
            background-color: var(--color-primary) !important;
            color: var(--color-light) !important;
            border: none !important;
            border-radius: ' . $radius . ' !important;
            font-family: var(--font-body), sans-serif !important;
            transition: all 0.3s ease !important;
        }

        button:hover, .button:hover, .btn:hover, input[type="submit"]:hover, input[type="button"]:hover,
        .wp-block-button__link:hover, .elementor-button:hover,
        .btn-primary:hover, .btn-secondary:hover, .btn-consultation:hover,
        .book-appointment:hover, .contact-button:hover, .cta-button:hover {
            background-color: var(--color-secondary) !important;
            transform: translateY(-1px) !important;
        }';
    }

    public function getButtonRadius() {
        return $this->config['buttonStyle'] === 'sharp' ? '2px' : '6px';
    }
}
