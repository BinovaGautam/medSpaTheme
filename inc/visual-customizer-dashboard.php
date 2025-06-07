<?php
/**
 * Visual Customizer Dashboard Interface
 *
 * Creates a very visible admin interface for the Visual Customizer
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add admin notice to make sure users see the Visual Customizer
 */
function visual_customizer_admin_notice() {
    $screen = get_current_screen();

    // Show notice on dashboard and other key pages
    if ($screen && in_array($screen->id, ['dashboard', 'themes', 'appearance_page_visual-customizer-dashboard'])) {
        ?>
        <div class="notice notice-info is-dismissible">
            <h3>🎨 Visual Customizer System Active</h3>
            <p><strong>New Professional Interface Available!</strong>
               <a href="<?php echo admin_url('admin.php?page=visual-customizer-dashboard'); ?>" class="button button-primary">
                   Open Visual Customizer Dashboard
               </a>
            </p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'visual_customizer_admin_notice');

/**
 * Add Visual Customizer to top-level admin menu
 */
function add_visual_customizer_dashboard_menu() {
    // Main dashboard page
    add_menu_page(
        'Visual Customizer Dashboard',    // Page title
        'Visual Customizer',              // Menu title
        'manage_options',                 // Capability
        'visual-customizer-dashboard',    // Menu slug
        'render_visual_customizer_dashboard', // Callback
        'dashicons-art',                  // Icon
        3                                 // Position (high priority)
    );

    // Add submenu items
    add_submenu_page(
        'visual-customizer-dashboard',
        'Color Palettes',
        'Color Palettes',
        'manage_options',
        'visual-customizer-dashboard',
        'render_visual_customizer_dashboard'
    );

    add_submenu_page(
        'visual-customizer-dashboard',
        'Live Preview',
        'Live Preview',
        'manage_options',
        'visual-customizer-live',
        'render_live_preview_page'
    );

    add_submenu_page(
        'visual-customizer-dashboard',
        'Typography',
        'Typography',
        'manage_options',
        'visual-customizer-typography',
        'render_typography_dashboard'
    );
}
add_action('admin_menu', 'add_visual_customizer_dashboard_menu');

/**
 * Render Visual Customizer Dashboard
 */
function render_visual_customizer_dashboard() {
    ?>
    <div class="wrap">
        <h1>🎨 Visual Customizer Professional Dashboard</h1>

        <div class="notice notice-success">
            <p><strong>SUCCESS!</strong> You're now in the Visual Customizer professional interface!</p>
        </div>

        <!-- Quick Actions -->
        <div class="card" style="max-width: none; margin-bottom: 20px;">
            <h2>Quick Actions</h2>
            <p>Professional color palette management for your medical spa theme.</p>

            <div style="display: flex; gap: 15px; margin: 20px 0;">
                <a href="<?php echo admin_url('customize.php'); ?>" class="button button-primary button-large">
                    <span class="dashicons dashicons-visibility"></span>
                    Live Preview (Customizer)
                </a>
                <button type="button" class="button button-secondary button-large" id="load-color-interface">
                    <span class="dashicons dashicons-art"></span>
                    Load Color Palette Interface
                </button>
                <button type="button" class="button button-secondary button-large" id="export-palette">
                    <span class="dashicons dashicons-download"></span>
                    Export Current Palette
                </button>
                <button type="button" class="button button-secondary button-large" id="import-palette">
                    <span class="dashicons dashicons-upload"></span>
                    Import Palette
                </button>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">

            <!-- Left Column: Main Interface -->
            <div class="card" style="max-width: none;">
                <h2>Color Palette Management</h2>
                <div id="visual-customizer-main-interface">
                    <div id="color-palette-container" style="min-height: 400px; border: 2px dashed #ccc; padding: 20px; text-align: center;">
                        <div class="loading-message">
                            <span class="spinner is-active" style="float: none; margin: 0 auto 10px;"></span>
                            <p>Click "Load Color Palette Interface" to initialize the Sprint 1 components...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings & Info -->
            <div>
                <!-- Current Settings -->
                <div class="card">
                    <h3>Current Settings</h3>
                    <?php
                    $current_palette = get_theme_mod('visual_customizer_active_palette', 'medical-clean');
                    echo "<p><strong>Active Palette:</strong> " . esc_html($current_palette) . "</p>";

                    if (class_exists('ColorSystemManager')) {
                        $colorSystem = ColorSystemManager::get_instance();
                        $palettes = $colorSystem->get_available_palettes();
                        echo "<p><strong>Available Palettes:</strong> " . count($palettes) . "</p>";
                    }
                    ?>

                    <form method="post" action="">
                        <?php wp_nonce_field('visual_customizer_dashboard'); ?>
                        <table class="form-table">
                            <tr>
                                <th><label for="active_palette">Switch Palette:</label></th>
                                <td>
                                    <select name="active_palette" id="active_palette">
                                        <?php
                                        if (class_exists('ColorSystemManager')) {
                                            $colorSystem = ColorSystemManager::get_instance();
                                            $palettes = $colorSystem->get_available_palettes();
                                            foreach ($palettes as $palette_id => $palette_data) {
                                                printf(
                                                    '<option value="%s" %s>%s</option>',
                                                    esc_attr($palette_id),
                                                    selected($current_palette, $palette_id, false),
                                                    esc_html($palette_data['name'])
                                                );
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <p class="submit">
                            <input type="submit" name="save_palette" class="button button-primary" value="Apply Palette">
                        </p>
                    </form>
                </div>

                <!-- Component Status -->
                <div class="card">
                    <h3>Component Status</h3>
                    <div id="component-status">
                        <p id="color-system-status">🔄 ColorSystemManager: <span class="status">Checking...</span></p>
                        <p id="palette-interface-status">🔄 ColorPaletteInterface: <span class="status">Checking...</span></p>
                        <p id="semantic-system-status">🔄 SemanticColorSystem: <span class="status">Checking...</span></p>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="card">
                    <h3>What This Does</h3>
                    <ul>
                        <li>✅ Professional color palette management</li>
                        <li>✅ Real-time preview integration</li>
                        <li>✅ Import/Export functionality</li>
                        <li>✅ Sprint 1 component integration</li>
                        <li>✅ Medical spa theme optimization</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            background: white;
            border: 1px solid #ccd0d4;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
        .card h2, .card h3 {
            margin-top: 0;
            color: #23282d;
        }
        #component-status p {
            margin: 8px 0;
            font-family: monospace;
        }
        .status.success { color: green; font-weight: bold; }
        .status.error { color: red; font-weight: bold; }
        .status.loading { color: orange; font-weight: bold; }
    </style>

    <script>
    jQuery(document).ready(function($) {
        console.log('Visual Customizer Dashboard Loaded');

        // Check component status
        function updateComponentStatus() {
            // Check ColorSystemManager
            if (typeof ColorSystemManager !== 'undefined') {
                $('#color-system-status .status').text('✅ Loaded').addClass('success');
            } else {
                $('#color-system-status .status').text('❌ Not Found').addClass('error');
            }

            // Check ColorPaletteInterface
            if (typeof ColorPaletteInterface !== 'undefined') {
                $('#palette-interface-status .status').text('✅ Available').addClass('success');
            } else {
                $('#palette-interface-status .status').text('❌ Not Found').addClass('error');
            }

            // Check SemanticColorSystem
            if (typeof SemanticColorSystem !== 'undefined') {
                $('#semantic-system-status .status').text('✅ Available').addClass('success');
            } else {
                $('#semantic-system-status .status').text('❌ Not Found').addClass('error');
            }
        }

        // Load Color Interface
        $('#load-color-interface').on('click', function() {
            var $container = $('#color-palette-container');
            $container.html('<p>Loading Color Palette Interface...</p><span class="spinner is-active"></span>');

            setTimeout(function() {
                if (typeof ColorPaletteInterface !== 'undefined') {
                    try {
                        var colorInterface = new ColorPaletteInterface({
                            container: '#color-palette-container',
                            mode: 'admin',
                            enablePreview: true
                        });
                        console.log('ColorPaletteInterface loaded successfully');
                    } catch (error) {
                        console.error('Error loading ColorPaletteInterface:', error);
                        $container.html('<p style="color: red;">Error loading interface: ' + error.message + '</p>');
                    }
                } else {
                    $container.html('<p style="color: orange;">ColorPaletteInterface not available. Please check JavaScript files.</p>');
                }
            }, 500);
        });

        // Export functionality
        $('#export-palette').on('click', function() {
            alert('Export functionality - This would export the current palette configuration');
        });

        // Import functionality
        $('#import-palette').on('click', function() {
            var importData = prompt('Paste palette JSON data:');
            if (importData) {
                alert('Import functionality - This would import: ' + importData.substring(0, 50) + '...');
            }
        });

        // Update component status after scripts load
        setTimeout(updateComponentStatus, 1000);
    });
    </script>
    <?php
}

/**
 * Render Live Preview Page
 */
function render_live_preview_page() {
    ?>
    <div class="wrap">
        <h1>Live Preview Interface</h1>
        <div class="notice notice-info">
            <p>This page integrates with the WordPress Customizer for live preview functionality.</p>
        </div>

        <div class="card" style="max-width: none;">
            <h2>Preview Options</h2>
            <p>
                <a href="<?php echo admin_url('customize.php'); ?>" class="button button-primary button-large">
                    Open WordPress Customizer
                </a>
                <a href="<?php echo home_url(); ?>" target="_blank" class="button button-secondary button-large">
                    View Frontend
                </a>
            </p>
        </div>
    </div>
    <?php
}

/**
 * Render Typography Dashboard
 */
function render_typography_dashboard() {
    ?>
    <div class="wrap">
        <h1>Typography System</h1>
        <div class="card" style="max-width: none;">
            <h2>Typography Controls</h2>
            <div id="typography-interface" style="min-height: 300px; border: 2px dashed #ccc; padding: 20px;">
                <p>Typography System interface will load here.</p>
                <button type="button" class="button button-primary" id="load-typography">Load Typography System</button>
            </div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('#load-typography').on('click', function() {
            if (typeof TypographySystem !== 'undefined') {
                new TypographySystem({
                    container: '#typography-interface',
                    mode: 'admin'
                });
            } else {
                $('#typography-interface').html('<p style="color: orange;">TypographySystem not available.</p>');
            }
        });
    });
    </script>
    <?php
}

/**
 * Handle dashboard form submissions
 */
function handle_visual_customizer_dashboard_actions() {
    if (isset($_POST['save_palette']) && check_admin_referer('visual_customizer_dashboard')) {
        $active_palette = sanitize_text_field($_POST['active_palette']);
        set_theme_mod('visual_customizer_active_palette', $active_palette);

        add_settings_error(
            'visual_customizer_dashboard',
            'palette_saved',
            'Palette updated successfully!',
            'updated'
        );
    }
}
add_action('admin_init', 'handle_visual_customizer_dashboard_actions');

/**
 * Enqueue dashboard assets
 */
function enqueue_visual_customizer_dashboard_assets($hook) {
    if (strpos($hook, 'visual-customizer') === false) {
        return;
    }

    wp_enqueue_script('jquery');

    // Enqueue Sprint 1 components
    wp_enqueue_script(
        'color-system-manager',
        get_template_directory_uri() . '/assets/js/color-system-manager.js',
        array('jquery'),
        PREETIDREAMS_VERSION,
        true
    );

    wp_enqueue_script(
        'color-palette-interface',
        get_template_directory_uri() . '/assets/js/color-palette-interface.js',
        array('color-system-manager'),
        PREETIDREAMS_VERSION,
        true
    );

    wp_enqueue_script(
        'semantic-color-system',
        get_template_directory_uri() . '/assets/js/semantic-color-system.js',
        array('color-system-manager'),
        PREETIDREAMS_VERSION,
        true
    );

    wp_enqueue_script(
        'typography-system',
        get_template_directory_uri() . '/assets/js/typography-system.js',
        array('color-system-manager'),
        PREETIDREAMS_VERSION,
        true
    );
}
add_action('admin_enqueue_scripts', 'enqueue_visual_customizer_dashboard_assets');

/**
 * Add dashboard widget
 */
function add_visual_customizer_dashboard_widget() {
    wp_add_dashboard_widget(
        'visual_customizer_widget',
        '🎨 Visual Customizer System',
        'visual_customizer_dashboard_widget_content'
    );
}
add_action('wp_dashboard_setup', 'add_visual_customizer_dashboard_widget');

/**
 * Dashboard widget content
 */
function visual_customizer_dashboard_widget_content() {
    $current_palette = get_theme_mod('visual_customizer_active_palette', 'medical-clean');
    ?>
    <div style="text-align: center;">
        <h4>Professional Color Palette System</h4>
        <p><strong>Current Palette:</strong> <?php echo esc_html($current_palette); ?></p>
        <p>
            <a href="<?php echo admin_url('admin.php?page=visual-customizer-dashboard'); ?>" class="button button-primary">
                Open Visual Customizer
            </a>
        </p>
    </div>
    <?php
}
