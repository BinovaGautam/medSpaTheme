<?php
/**
 * Visual Customizer Admin Page
 *
 * Professional WordPress admin interface for the Visual Customizer
 * Sprint 2 - PVC-004: WordPress Admin Integration Panel
 *
 * @package MedSpaTheme
 * @subpackage VisualCustomizer
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Visual Customizer Admin Page Class
 */
class VisualCustomizerAdminPage {

    /**
     * Singleton instance
     * @var VisualCustomizerAdminPage|null
     */
    private static $instance = null;

    /**
     * Page slug
     * @var string
     */
    private $page_slug = 'visual-customizer';

    /**
     * Color System Manager instance
     * @var ColorSystemManager|null
     */
    private $color_system;

    /**
     * Get singleton instance
     * @return VisualCustomizerAdminPage
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }

    /**
     * Load dependencies
     */
    private function load_dependencies() {
        if (!class_exists('ColorSystemManager')) {
            require_once get_template_directory() . '/inc/color-system-manager.php';
        }
        $this->color_system = ColorSystemManager::get_instance();
    }

    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('admin_init', array($this, 'handle_admin_actions'));
        add_action('wp_ajax_visual_customizer_save_config', array($this, 'handle_save_config'));
        add_action('wp_ajax_visual_customizer_export_palette', array($this, 'handle_export_palette'));
        add_action('wp_ajax_visual_customizer_import_palette', array($this, 'handle_import_palette'));

        // Add admin notice to confirm loading
        add_action('admin_notices', array($this, 'admin_notice_loaded'));
    }

    /**
     * Admin notice to confirm the Visual Customizer is loaded
     */
    public function admin_notice_loaded() {
        $screen = get_current_screen();
        if ($screen && strpos($screen->id, 'visual-customizer') !== false) {
            echo '<div class="notice notice-success is-dismissible">';
            echo '<p><strong>Visual Customizer Admin:</strong> Professional interface loaded successfully!</p>';
            echo '</div>';
        }
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        $page_title = __('Visual Customizer', 'medspa-theme');
        $menu_title = __('Visual Customizer', 'medspa-theme');
        $capability = 'manage_options';
        $menu_slug = $this->page_slug;
        $callback = array($this, 'render_admin_page');
        $icon_url = 'dashicons-art';
        $position = 25;

        add_menu_page(
            $page_title,
            $menu_title,
            $capability,
            $menu_slug,
            $callback,
            $icon_url,
            $position
        );

        // Add submenu pages
        add_submenu_page(
            $menu_slug,
            __('Color Palettes', 'medspa-theme'),
            __('Color Palettes', 'medspa-theme'),
            $capability,
            $menu_slug,
            $callback
        );

        add_submenu_page(
            $menu_slug,
            __('Typography', 'medspa-theme'),
            __('Typography', 'medspa-theme'),
            $capability,
            $menu_slug . '-typography',
            array($this, 'render_typography_page')
        );

        add_submenu_page(
            $menu_slug,
            __('Accessibility', 'medspa-theme'),
            __('Accessibility', 'medspa-theme'),
            $capability,
            $menu_slug . '-accessibility',
            array($this, 'render_accessibility_page')
        );
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        if (strpos($hook, $this->page_slug) === false) {
            return;
        }

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

        // Enqueue admin interface
        wp_enqueue_script(
            'visual-customizer-admin',
            get_template_directory_uri() . '/assets/js/visual-customizer-admin.js',
            array('color-palette-interface', 'semantic-color-system', 'typography-system'),
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

        // Localize script
        wp_localize_script('visual-customizer-admin', 'visualCustomizerAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('visual_customizer_admin'),
            'currentPalette' => get_theme_mod('visual_customizer_active_palette', 'medical-clean'),
            'palettes' => $this->color_system->get_available_palettes(),
            'strings' => array(
                'saved' => __('Settings saved successfully', 'medspa-theme'),
                'error' => __('Error saving settings', 'medspa-theme'),
                'confirm_reset' => __('Are you sure you want to reset all settings?', 'medspa-theme'),
                'palette_imported' => __('Palette imported successfully', 'medspa-theme'),
                'palette_exported' => __('Palette exported successfully', 'medspa-theme'),
            )
        ));
    }

    /**
     * Handle admin actions
     */
    public function handle_admin_actions() {
        if (!isset($_POST['visual_customizer_action']) || !current_user_can('manage_options')) {
            return;
        }

        check_admin_referer('visual_customizer_admin');

        $action = sanitize_text_field($_POST['visual_customizer_action']);

        switch ($action) {
            case 'save_settings':
                $this->save_settings();
                break;
            case 'reset_settings':
                $this->reset_settings();
                break;
        }
    }

    /**
     * Save settings
     */
    private function save_settings() {
        $active_palette = sanitize_text_field($_POST['active_palette'] ?? 'medical-clean');
        $custom_settings = array();

        if (isset($_POST['custom_settings'])) {
            $custom_settings = array_map('sanitize_text_field', $_POST['custom_settings']);
        }

        set_theme_mod('visual_customizer_active_palette', $active_palette);
        set_theme_mod('visual_customizer_custom_settings', $custom_settings);

        add_settings_error(
            'visual_customizer',
            'settings_saved',
            __('Visual Customizer settings saved successfully.', 'medspa-theme'),
            'updated'
        );
    }

    /**
     * Reset settings
     */
    private function reset_settings() {
        remove_theme_mod('visual_customizer_active_palette');
        remove_theme_mod('visual_customizer_custom_settings');

        add_settings_error(
            'visual_customizer',
            'settings_reset',
            __('Visual Customizer settings have been reset to defaults.', 'medspa-theme'),
            'updated'
        );
    }

    /**
     * Handle save config AJAX
     */
    public function handle_save_config() {
        check_ajax_referer('visual_customizer_admin', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }

        $config = json_decode(stripslashes($_POST['config']), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            wp_send_json_error('Invalid JSON data');
        }

        // Save configuration
        update_option('visual_customizer_config', $config);

        wp_send_json_success(array(
            'message' => __('Configuration saved successfully', 'medspa-theme')
        ));
    }

    /**
     * Handle export palette AJAX
     */
    public function handle_export_palette() {
        check_ajax_referer('visual_customizer_admin', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }

        $palette_id = sanitize_text_field($_POST['palette_id']);
        $export_data = $this->color_system->export_palette($palette_id);

        if (!$export_data) {
            wp_send_json_error('Palette not found');
        }

        wp_send_json_success($export_data);
    }

    /**
     * Handle import palette AJAX
     */
    public function handle_import_palette() {
        check_ajax_referer('visual_customizer_admin', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }

        $import_data = json_decode(stripslashes($_POST['import_data']), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            wp_send_json_error('Invalid import data');
        }

        $result = $this->color_system->import_palette($import_data);

        if ($result === true) {
            wp_send_json_success(array(
                'message' => __('Palette imported successfully', 'medspa-theme')
            ));
        } else {
            wp_send_json_error($result);
        }
    }

    /**
     * Render main admin page
     */
    public function render_admin_page() {
        ?>
        <div class="wrap visual-customizer-admin">
            <h1><?php echo esc_html__('Visual Customizer', 'medspa-theme'); ?></h1>

            <?php settings_errors('visual_customizer'); ?>

            <div class="visual-customizer-container">
                <!-- Header Section -->
                <div class="vc-header">
                    <div class="vc-header-content">
                        <h2><?php esc_html_e('Professional Color Palette Management', 'medspa-theme'); ?></h2>
                        <p><?php esc_html_e('Manage your medical spa\'s color palettes with professional-grade tools and real-time preview.', 'medspa-theme'); ?></p>
                    </div>
                    <div class="vc-header-actions">
                        <button type="button" class="button button-secondary" id="import-palette">
                            <span class="dashicons dashicons-upload"></span>
                            <?php esc_html_e('Import Palette', 'medspa-theme'); ?>
                        </button>
                        <button type="button" class="button button-secondary" id="export-palette">
                            <span class="dashicons dashicons-download"></span>
                            <?php esc_html_e('Export Palette', 'medspa-theme'); ?>
                        </button>
                        <a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button button-primary">
                            <span class="dashicons dashicons-visibility"></span>
                            <?php esc_html_e('Live Preview', 'medspa-theme'); ?>
                        </a>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="vc-main-content">
                    <div class="vc-left-panel">
                        <!-- Color Palette Interface Container -->
                        <div id="color-palette-interface-container">
                            <div class="loading-state">
                                <span class="spinner is-active"></span>
                                <p><?php esc_html_e('Loading Visual Customizer...', 'medspa-theme'); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="vc-right-panel">
                        <!-- Semantic Color System Container -->
                        <div id="semantic-color-system-container">
                            <h3><?php esc_html_e('Semantic Color System', 'medspa-theme'); ?></h3>
                            <div class="loading-state">
                                <span class="spinner is-active"></span>
                                <p><?php esc_html_e('Loading semantic colors...', 'medspa-theme'); ?></p>
                            </div>
                        </div>

                        <!-- Settings Panel -->
                        <div class="vc-settings-panel">
                            <h3><?php esc_html_e('Settings', 'medspa-theme'); ?></h3>
                            <form method="post" action="">
                                <?php wp_nonce_field('visual_customizer_admin'); ?>
                                <input type="hidden" name="visual_customizer_action" value="save_settings">

                                <table class="form-table">
                                    <tr>
                                        <th scope="row">
                                            <label for="active_palette"><?php esc_html_e('Active Palette', 'medspa-theme'); ?></label>
                                        </th>
                                        <td>
                                            <select name="active_palette" id="active_palette">
                                                <?php
                                                $current_palette = get_theme_mod('visual_customizer_active_palette', 'medical-clean');
                                                $palettes = $this->color_system->get_available_palettes();
                                                foreach ($palettes as $palette_id => $palette_data) {
                                                    printf(
                                                        '<option value="%s" %s>%s</option>',
                                                        esc_attr($palette_id),
                                                        selected($current_palette, $palette_id, false),
                                                        esc_html($palette_data['name'])
                                                    );
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                </table>

                                <p class="submit">
                                    <input type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings', 'medspa-theme'); ?>">
                                    <button type="submit" class="button-secondary" name="visual_customizer_action" value="reset_settings"
                                            onclick="return confirm('<?php esc_attr_e('Are you sure you want to reset all settings?', 'medspa-theme'); ?>')">
                                        <?php esc_html_e('Reset to Defaults', 'medspa-theme'); ?>
                                    </button>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Import/Export Modals -->
        <div id="import-modal" class="vc-modal" style="display: none;">
            <div class="vc-modal-content">
                <span class="vc-modal-close">&times;</span>
                <h3><?php esc_html_e('Import Color Palette', 'medspa-theme'); ?></h3>
                <form id="import-form">
                    <p>
                        <label for="import-data"><?php esc_html_e('Paste palette JSON data:', 'medspa-theme'); ?></label>
                        <textarea id="import-data" rows="10" cols="50" placeholder='{"version": "1.0", "id": "custom-palette", "data": {...}}'></textarea>
                    </p>
                    <p>
                        <button type="submit" class="button-primary"><?php esc_html_e('Import', 'medspa-theme'); ?></button>
                        <button type="button" class="button-secondary vc-modal-close"><?php esc_html_e('Cancel', 'medspa-theme'); ?></button>
                    </p>
                </form>
            </div>
        </div>

        <div id="export-modal" class="vc-modal" style="display: none;">
            <div class="vc-modal-content">
                <span class="vc-modal-close">&times;</span>
                <h3><?php esc_html_e('Export Color Palette', 'medspa-theme'); ?></h3>
                <p>
                    <label for="export-palette-select"><?php esc_html_e('Select palette to export:', 'medspa-theme'); ?></label>
                    <select id="export-palette-select">
                        <?php
                        foreach ($palettes as $palette_id => $palette_data) {
                            printf(
                                '<option value="%s">%s</option>',
                                esc_attr($palette_id),
                                esc_html($palette_data['name'])
                            );
                        }
                        ?>
                    </select>
                </p>
                <p>
                    <button type="button" class="button-primary" id="export-button"><?php esc_html_e('Export', 'medspa-theme'); ?></button>
                    <button type="button" class="button-secondary vc-modal-close"><?php esc_html_e('Cancel', 'medspa-theme'); ?></button>
                </p>
                <div id="export-result" style="display: none;">
                    <label for="export-data"><?php esc_html_e('Copy this JSON data:', 'medspa-theme'); ?></label>
                    <textarea id="export-data" rows="10" cols="50" readonly></textarea>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render typography page
     */
    public function render_typography_page() {
        ?>
        <div class="wrap visual-customizer-admin">
            <h1><?php echo esc_html__('Typography System', 'medspa-theme'); ?></h1>

            <div class="visual-customizer-container">
                <div id="typography-system-container">
                    <div class="loading-state">
                        <span class="spinner is-active"></span>
                        <p><?php esc_html_e('Loading Typography System...', 'medspa-theme'); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render accessibility page
     */
    public function render_accessibility_page() {
        ?>
        <div class="wrap visual-customizer-admin">
            <h1><?php echo esc_html__('Accessibility Tools', 'medspa-theme'); ?></h1>

            <div class="visual-customizer-container">
                <div class="vc-accessibility-content">
                    <h2><?php esc_html_e('WCAG Compliance Dashboard', 'medspa-theme'); ?></h2>
                    <div id="accessibility-dashboard">
                        <div class="loading-state">
                            <span class="spinner is-active"></span>
                            <p><?php esc_html_e('Loading accessibility analysis...', 'medspa-theme'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

// Initialize the admin page
function initialize_visual_customizer_admin() {
    if (is_admin()) {
        // Add some debug output
        error_log('Visual Customizer Admin: Initializing...');

        try {
            $instance = VisualCustomizerAdminPage::get_instance();
            error_log('Visual Customizer Admin: Instance created successfully');
            return $instance;
        } catch (Exception $e) {
            error_log('Visual Customizer Admin Error: ' . $e->getMessage());
            return false;
        }
    }
    return false;
}

// Initialize on plugins_loaded to ensure all WordPress functionality is available
add_action('plugins_loaded', 'initialize_visual_customizer_admin');

// Also add a backup initialization on admin_init
function backup_initialize_visual_customizer_admin() {
    if (is_admin() && !class_exists('VisualCustomizerAdminPage')) {
        error_log('Visual Customizer Admin: Backup initialization triggered');
        initialize_visual_customizer_admin();
    }
}
add_action('admin_init', 'backup_initialize_visual_customizer_admin');
