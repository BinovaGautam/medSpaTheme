<?php
/**
 * Simple Visual Customizer Admin Page
 *
 * Fallback version to ensure the admin interface loads
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Visual Customizer admin menu
 */
function add_visual_customizer_menu() {
    add_menu_page(
        'Visual Customizer',           // Page title
        'Visual Customizer',           // Menu title
        'manage_options',              // Capability
        'visual-customizer-simple',    // Menu slug
        'render_visual_customizer_page', // Callback function
        'dashicons-art',               // Icon
        25                            // Position
    );

    // Add submenu pages
    add_submenu_page(
        'visual-customizer-simple',
        'Color Palettes',
        'Color Palettes',
        'manage_options',
        'visual-customizer-simple',
        'render_visual_customizer_page'
    );

    add_submenu_page(
        'visual-customizer-simple',
        'Typography',
        'Typography',
        'manage_options',
        'visual-customizer-typography',
        'render_typography_page'
    );
}
add_action('admin_menu', 'add_visual_customizer_menu');

/**
 * Render the main Visual Customizer page
 */
function render_visual_customizer_page() {
    ?>
    <div class="wrap visual-customizer-admin">
        <h1>🎨 Visual Customizer (Professional)</h1>

        <div class="notice notice-success">
            <p><strong>Success!</strong> The Visual Customizer admin interface is now loading!</p>
        </div>

        <div class="visual-customizer-container">
            <!-- Header Section -->
            <div class="vc-header">
                <div class="vc-header-content">
                    <h2>Professional Color Palette Management</h2>
                    <p>Manage your medical spa's color palettes with professional-grade tools and real-time preview.</p>
                </div>
                <div class="vc-header-actions">
                    <button type="button" class="button button-secondary" id="import-palette">
                        <span class="dashicons dashicons-upload"></span>
                        Import Palette
                    </button>
                    <button type="button" class="button button-secondary" id="export-palette">
                        <span class="dashicons dashicons-download"></span>
                        Export Palette
                    </button>
                    <a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button button-primary">
                        <span class="dashicons dashicons-visibility"></span>
                        Live Preview
                    </a>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="vc-main-content">
                <div class="vc-left-panel">
                    <!-- Color Palette Interface Container -->
                    <div id="color-palette-interface-container">
                        <h3>Color Palette Interface</h3>
                        <p>Loading Sprint 1 components...</p>
                        <div class="loading-state">
                            <span class="spinner is-active"></span>
                            <p>Initializing Visual Customizer components...</p>
                        </div>
                    </div>
                </div>

                <div class="vc-right-panel">
                    <!-- Semantic Color System Container -->
                    <div id="semantic-color-system-container">
                        <h3>Semantic Color System</h3>
                        <div class="loading-state">
                            <span class="spinner is-active"></span>
                            <p>Loading semantic colors...</p>
                        </div>
                    </div>

                    <!-- Settings Panel -->
                    <div class="vc-settings-panel">
                        <h3>Settings</h3>
                        <?php
                        // Load ColorSystemManager to get palettes
                        if (class_exists('ColorSystemManager')) {
                            $colorSystem = ColorSystemManager::get_instance();
                            $palettes = $colorSystem->get_available_palettes();
                            $current_palette = get_theme_mod('visual_customizer_active_palette', 'medical-clean');
                        ?>
                        <form method="post" action="">
                            <?php wp_nonce_field('visual_customizer_settings'); ?>
                            <table class="form-table">
                                <tr>
                                    <th scope="row">
                                        <label for="active_palette">Active Palette</label>
                                    </th>
                                    <td>
                                        <select name="active_palette" id="active_palette">
                                            <?php foreach ($palettes as $palette_id => $palette_data): ?>
                                                <option value="<?php echo esc_attr($palette_id); ?>" <?php selected($current_palette, $palette_id); ?>>
                                                    <?php echo esc_html($palette_data['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                            <p class="submit">
                                <input type="submit" name="save_settings" class="button-primary" value="Save Settings">
                            </p>
                        </form>
                        <?php
                        } else {
                            echo '<p style="color: red;">ColorSystemManager not found. Please check the implementation.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        console.log('Visual Customizer Admin Page Loaded');

        // Try to initialize Sprint 1 components
        setTimeout(function() {
            if (typeof ColorPaletteInterface !== 'undefined') {
                console.log('Initializing ColorPaletteInterface...');
                new ColorPaletteInterface({
                    container: '#color-palette-interface-container',
                    mode: 'admin'
                });
                $('#color-palette-interface-container .loading-state').hide();
            } else {
                console.log('ColorPaletteInterface not found');
                $('#color-palette-interface-container .loading-state').html('<p style="color: orange;">Sprint 1 components not loaded. Please check JavaScript files.</p>');
            }

            if (typeof SemanticColorSystem !== 'undefined') {
                console.log('Initializing SemanticColorSystem...');
                new SemanticColorSystem({
                    container: '#semantic-color-system-container',
                    mode: 'admin'
                });
                $('#semantic-color-system-container .loading-state').hide();
            } else {
                console.log('SemanticColorSystem not found');
                $('#semantic-color-system-container .loading-state').html('<p style="color: orange;">Semantic Color System not loaded.</p>');
            }
        }, 1000);
    });
    </script>
    <?php
}

/**
 * Render typography page
 */
function render_typography_page() {
    ?>
    <div class="wrap visual-customizer-admin">
        <h1>Typography System</h1>
        <div id="typography-system-container">
            <p>Typography System interface will load here.</p>
        </div>
    </div>
    <?php
}

/**
 * Handle settings save
 */
function handle_visual_customizer_settings() {
    if (isset($_POST['save_settings']) && check_admin_referer('visual_customizer_settings')) {
        $active_palette = sanitize_text_field($_POST['active_palette']);
        set_theme_mod('visual_customizer_active_palette', $active_palette);

        add_settings_error(
            'visual_customizer',
            'settings_saved',
            'Visual Customizer settings saved successfully.',
            'updated'
        );
    }
}
add_action('admin_init', 'handle_visual_customizer_settings');

/**
 * Enqueue admin assets for simple version
 */
function enqueue_visual_customizer_simple_assets($hook) {
    if (strpos($hook, 'visual-customizer') === false) {
        return;
    }

    // Enqueue Sprint 1 components
    wp_enqueue_script('jquery');

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

    // Enqueue admin styles
    wp_enqueue_style(
        'visual-customizer-admin',
        get_template_directory_uri() . '/assets/css/visual-customizer-admin.css',
        array(),
        PREETIDREAMS_VERSION
    );
}
add_action('admin_enqueue_scripts', 'enqueue_visual_customizer_simple_assets');
