<?php
/**
 * Component Management Dashboard
 *
 * WordPress admin interface for component management
 *
 * @package MedSpaTheme
 * @since 1.0.0
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Component Dashboard Class
 *
 * Provides admin interface for managing theme components
 */
class ComponentDashboard {

    /**
     * Dashboard initialization status
     * @var bool
     */
    private static $initialized = false;

    /**
     * Admin page hook
     * @var string
     */
    private static $admin_page_hook;

    /**
     * Initialize the dashboard
     */
    public static function init() {
        if (self::$initialized) {
            return;
        }

        // Add admin menu and pages
        add_action('admin_menu', [__CLASS__, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_admin_assets']);

        // AJAX handlers
        add_action('wp_ajax_toggle_component_status', [__CLASS__, 'handle_toggle_component_status']);
        add_action('wp_ajax_refresh_component_discovery', [__CLASS__, 'handle_refresh_component_discovery']);
        add_action('wp_ajax_get_component_performance', [__CLASS__, 'handle_get_component_performance']);

        // Admin notices
        add_action('admin_notices', [__CLASS__, 'display_admin_notices']);

        self::$initialized = true;
    }

    /**
     * Add admin menu for component management
     */
    public static function add_admin_menu() {
        self::$admin_page_hook = add_theme_page(
            'Component Management',
            'Components',
            'manage_options',
            'component-management',
            [__CLASS__, 'render_dashboard_page']
        );

        // Add submenu pages
        add_submenu_page(
            'component-management',
            'Component Performance',
            'Performance',
            'manage_options',
            'component-performance',
            [__CLASS__, 'render_performance_page']
        );

        add_submenu_page(
            'component-management',
            'Component Discovery',
            'Discovery',
            'manage_options',
            'component-discovery',
            [__CLASS__, 'render_discovery_page']
        );
    }

    /**
     * Enqueue admin assets
     *
     * @param string $hook Current admin page hook
     */
    public static function enqueue_admin_assets($hook) {
        if (strpos($hook, 'component-') === false) {
            return;
        }

        // Enqueue admin CSS
        wp_enqueue_style(
            'component-dashboard-admin',
            get_template_directory_uri() . '/assets/css/admin/component-dashboard.css',
            [],
            '1.0.0'
        );

        // Enqueue admin JS
        wp_enqueue_script(
            'component-dashboard-admin',
            get_template_directory_uri() . '/assets/js/admin/component-dashboard.js',
            ['jquery'],
            '1.0.0',
            true
        );

        // Localize script data
        wp_localize_script('component-dashboard-admin', 'componentDashboard', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('component_dashboard_nonce'),
            'strings' => [
                'confirmDisable' => 'Are you sure you want to disable this component?',
                'confirmEnable' => 'Are you sure you want to enable this component?',
                'refreshing' => 'Refreshing component discovery...',
                'refreshComplete' => 'Component discovery refreshed successfully!'
            ]
        ]);
    }

    /**
     * Render main dashboard page
     */
    public static function render_dashboard_page() {
        $registered_components = ComponentRegistry::get_registered_components();
        $discovery_stats = class_exists('ComponentAutoLoader') ? ComponentAutoLoader::get_discovery_stats() : [];
        $performance_metrics = ComponentRegistry::get_performance_metrics();

        ?>
        <div class="wrap">
            <h1>Component Management</h1>

            <div class="component-dashboard-header">
                <div class="dashboard-stats">
                    <div class="stat-card">
                        <h3><?php echo count($registered_components); ?></h3>
                        <p>Registered Components</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $discovery_stats['valid_components_found'] ?? 0; ?></h3>
                        <p>Auto-Discovered</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo count($performance_metrics); ?></h3>
                        <p>Performance Tracked</p>
                    </div>
                </div>

                <div class="dashboard-actions">
                    <button id="refresh-discovery" class="button button-secondary">
                        Refresh Discovery
                    </button>
                    <a href="<?php echo admin_url('themes.php?page=component-performance'); ?>" class="button">
                        View Performance
                    </a>
                </div>
            </div>

            <div class="component-list-container">
                <h2>Registered Components</h2>

                <?php if (empty($registered_components)): ?>
                    <div class="no-components-message">
                        <p>No components are currently registered.</p>
                        <p><a href="<?php echo admin_url('themes.php?page=component-discovery'); ?>">Run Component Discovery</a></p>
                    </div>
                <?php else: ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>Component Name</th>
                                <th>Class</th>
                                <th>Status</th>
                                <th>Performance</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($registered_components as $name => $component): ?>
                                <tr data-component="<?php echo esc_attr($name); ?>">
                                    <td>
                                        <strong><?php echo esc_html(ucfirst($name)); ?></strong>
                                        <?php if (isset($component['config']['auto_discovered']) && $component['config']['auto_discovered']): ?>
                                            <span class="auto-discovered-badge">Auto-Discovered</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo esc_html($component['class']); ?></td>
                                    <td>
                                        <?php
                                        $is_enabled = self::is_component_enabled($name);
                                        $status_class = $is_enabled ? 'enabled' : 'disabled';
                                        $status_text = $is_enabled ? 'Enabled' : 'Disabled';
                                        ?>
                                        <span class="component-status <?php echo $status_class; ?>">
                                            <?php echo $status_text; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (isset($performance_metrics[$name])): ?>
                                            <?php
                                            $metrics = $performance_metrics[$name];
                                            $avg_time = $metrics['total_time'] / $metrics['total_renders'];
                                            ?>
                                            <span class="performance-metric">
                                                <?php echo number_format($avg_time * 1000, 2); ?>ms avg
                                            </span>
                                            <small>(<?php echo $metrics['total_renders']; ?> renders)</small>
                                        <?php else: ?>
                                            <span class="no-performance-data">No data</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="button toggle-component-status"
                                                data-component="<?php echo esc_attr($name); ?>"
                                                data-current-status="<?php echo $is_enabled ? 'enabled' : 'disabled'; ?>">
                                            <?php echo $is_enabled ? 'Disable' : 'Enable'; ?>
                                        </button>

                                        <?php if (isset($component['config']['file_path'])): ?>
                                            <a href="<?php echo esc_url(add_query_arg(['file' => $component['config']['file_path']], admin_url('theme-editor.php'))); ?>"
                                               class="button button-small">Edit</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <div class="component-system-info">
                <h2>System Information</h2>
                <table class="form-table">
                    <tr>
                        <th>Component Directory</th>
                        <td><?php echo esc_html($discovery_stats['component_directory'] ?? 'Not available'); ?></td>
                    </tr>
                    <tr>
                        <th>Auto-Discovery Status</th>
                        <td>
                            <?php if (class_exists('ComponentAutoLoader')): ?>
                                <span class="status-enabled">Active</span>
                            <?php else: ?>
                                <span class="status-disabled">Inactive</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Cache Status</th>
                        <td>
                            <?php if ($discovery_stats['cache_active'] ?? false): ?>
                                <span class="status-enabled">Active</span>
                                <a href="<?php echo esc_url(add_query_arg('clear_component_cache', '1')); ?>" class="button button-small">Clear Cache</a>
                            <?php else: ?>
                                <span class="status-disabled">Inactive</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <style>
        .component-dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
            padding: 20px;
            background: #fff;
            border: 1px solid #c3c4c7;
            border-radius: 4px;
        }

        .dashboard-stats {
            display: flex;
            gap: 20px;
        }

        .stat-card {
            text-align: center;
            padding: 15px;
            background: #f6f7f7;
            border-radius: 4px;
            min-width: 100px;
        }

        .stat-card h3 {
            margin: 0;
            font-size: 24px;
            color: #1d2327;
        }

        .stat-card p {
            margin: 5px 0 0;
            color: #646970;
            font-size: 13px;
        }

        .dashboard-actions {
            display: flex;
            gap: 10px;
        }

        .component-list-container {
            background: #fff;
            border: 1px solid #c3c4c7;
            border-radius: 4px;
            margin: 20px 0;
        }

        .component-list-container h2 {
            margin: 0;
            padding: 15px 20px;
            border-bottom: 1px solid #c3c4c7;
            background: #f6f7f7;
        }

        .component-status.enabled {
            color: #00a32a;
            font-weight: 600;
        }

        .component-status.disabled {
            color: #d63638;
            font-weight: 600;
        }

        .auto-discovered-badge {
            background: #2271b1;
            color: #fff;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            margin-left: 8px;
        }

        .performance-metric {
            color: #2271b1;
            font-weight: 600;
        }

        .no-performance-data {
            color: #646970;
            font-style: italic;
        }

        .no-components-message {
            padding: 40px;
            text-align: center;
            color: #646970;
        }

        .component-system-info {
            background: #fff;
            border: 1px solid #c3c4c7;
            border-radius: 4px;
            margin: 20px 0;
            padding: 20px;
        }

        .status-enabled {
            color: #00a32a;
            font-weight: 600;
        }

        .status-disabled {
            color: #d63638;
            font-weight: 600;
        }
        </style>
        <?php
    }

    /**
     * Render performance page
     */
    public static function render_performance_page() {
        $performance_metrics = ComponentRegistry::get_performance_metrics();
        $registry_metrics = ComponentFactory::get_factory_metrics();

        ?>
        <div class="wrap">
            <h1>Component Performance</h1>

            <div class="performance-overview">
                <h2>Registry Performance</h2>
                <?php if (empty($performance_metrics)): ?>
                    <p>No performance data available. Components need to be rendered to generate performance metrics.</p>
                <?php else: ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>Component</th>
                                <th>Total Renders</th>
                                <th>Average Time</th>
                                <th>Min Time</th>
                                <th>Max Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($performance_metrics as $component => $metrics): ?>
                                <?php
                                $avg_time = $metrics['total_time'] / $metrics['total_renders'];
                                $status_class = $avg_time > 0.1 ? 'performance-warning' : 'performance-good';
                                ?>
                                <tr>
                                    <td><strong><?php echo esc_html(ucfirst($component)); ?></strong></td>
                                    <td><?php echo number_format($metrics['total_renders']); ?></td>
                                    <td><?php echo number_format($avg_time * 1000, 2); ?>ms</td>
                                    <td><?php echo number_format($metrics['min_time'] * 1000, 2); ?>ms</td>
                                    <td><?php echo number_format($metrics['max_time'] * 1000, 2); ?>ms</td>
                                    <td>
                                        <span class="<?php echo $status_class; ?>">
                                            <?php echo $avg_time > 0.1 ? 'Slow' : 'Good'; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <div class="factory-performance">
                <h2>Factory Performance</h2>
                <?php if (empty($registry_metrics)): ?>
                    <p>No factory performance data available.</p>
                <?php else: ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>Component</th>
                                <th>Total Creations</th>
                                <th>Average Time</th>
                                <th>Min Time</th>
                                <th>Max Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($registry_metrics as $component => $metrics): ?>
                                <?php $avg_time = $metrics['total_time'] / $metrics['total_creations']; ?>
                                <tr>
                                    <td><strong><?php echo esc_html(ucfirst($component)); ?></strong></td>
                                    <td><?php echo number_format($metrics['total_creations']); ?></td>
                                    <td><?php echo number_format($avg_time * 1000, 2); ?>ms</td>
                                    <td><?php echo number_format($metrics['min_time'] * 1000, 2); ?>ms</td>
                                    <td><?php echo number_format($metrics['max_time'] * 1000, 2); ?>ms</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <style>
        .performance-overview,
        .factory-performance {
            background: #fff;
            border: 1px solid #c3c4c7;
            border-radius: 4px;
            margin: 20px 0;
            padding: 20px;
        }

        .performance-good {
            color: #00a32a;
            font-weight: 600;
        }

        .performance-warning {
            color: #dba617;
            font-weight: 600;
        }
        </style>
        <?php
    }

    /**
     * Render discovery page
     */
    public static function render_discovery_page() {
        $discovery_stats = class_exists('ComponentAutoLoader') ? ComponentAutoLoader::get_discovery_stats() : [];
        $discovered_components = class_exists('ComponentAutoLoader') ? ComponentAutoLoader::get_discovered_components() : [];

        ?>
        <div class="wrap">
            <h1>Component Discovery</h1>

            <div class="discovery-stats">
                <h2>Discovery Statistics</h2>
                <table class="form-table">
                    <tr>
                        <th>Component Directory</th>
                        <td><?php echo esc_html($discovery_stats['component_directory'] ?? 'Not available'); ?></td>
                    </tr>
                    <tr>
                        <th>Files Scanned</th>
                        <td><?php echo esc_html($discovery_stats['total_files_scanned'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Valid Components Found</th>
                        <td><?php echo esc_html($discovery_stats['valid_components_found'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Cache Status</th>
                        <td>
                            <?php if ($discovery_stats['cache_active'] ?? false): ?>
                                <span class="status-enabled">Active</span>
                            <?php else: ?>
                                <span class="status-disabled">Inactive</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="discovered-components">
                <h2>Discovered Components</h2>
                <?php if (empty($discovered_components)): ?>
                    <p>No components have been discovered yet. Make sure you have component files in the components directory.</p>
                <?php else: ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>Component Name</th>
                                <th>Class</th>
                                <th>File Path</th>
                                <th>File Size</th>
                                <th>Last Modified</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($discovered_components as $name => $component): ?>
                                <tr>
                                    <td><strong><?php echo esc_html(ucfirst($name)); ?></strong></td>
                                    <td><?php echo esc_html($component['class']); ?></td>
                                    <td>
                                        <code><?php echo esc_html(str_replace(get_template_directory(), '...', $component['file_path'])); ?></code>
                                    </td>
                                    <td><?php echo esc_html(size_format($component['file_size'])); ?></td>
                                    <td><?php echo esc_html(date('Y-m-d H:i:s', $component['file_modified'])); ?></td>
                                    <td>
                                        <?php if (ComponentRegistry::is_registered($name)): ?>
                                            <span class="status-enabled">Registered</span>
                                        <?php else: ?>
                                            <span class="status-disabled">Not Registered</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Handle toggle component status AJAX request
     */
    public static function handle_toggle_component_status() {
        check_ajax_referer('component_dashboard_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }

        $component_name = sanitize_text_field($_POST['component'] ?? '');
        $current_status = sanitize_text_field($_POST['current_status'] ?? '');

        if (empty($component_name)) {
            wp_send_json_error('Invalid component name');
        }

        $new_status = $current_status === 'enabled' ? 'disabled' : 'enabled';

        // Update component status
        $disabled_components = get_option('disabled_components', []);

        if ($new_status === 'disabled') {
            $disabled_components[] = $component_name;
        } else {
            $disabled_components = array_diff($disabled_components, [$component_name]);
        }

        update_option('disabled_components', array_unique($disabled_components));

        wp_send_json_success([
            'component' => $component_name,
            'new_status' => $new_status,
            'message' => "Component {$component_name} " . ($new_status === 'enabled' ? 'enabled' : 'disabled') . ' successfully.'
        ]);
    }

    /**
     * Handle refresh component discovery AJAX request
     */
    public static function handle_refresh_component_discovery() {
        check_ajax_referer('component_dashboard_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }

        if (class_exists('ComponentAutoLoader')) {
            ComponentAutoLoader::clear_discovery_cache();
            ComponentAutoLoader::discover_and_register_components();
            wp_send_json_success(['message' => 'Component discovery refreshed successfully!']);
        } else {
            wp_send_json_error('ComponentAutoLoader not available');
        }
    }

    /**
     * Handle get component performance AJAX request
     */
    public static function handle_get_component_performance() {
        check_ajax_referer('component_dashboard_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }

        $component_name = sanitize_text_field($_POST['component'] ?? '');

        if (empty($component_name)) {
            wp_send_json_error('Invalid component name');
        }

        $metrics = ComponentRegistry::get_performance_metrics($component_name);
        wp_send_json_success($metrics);
    }

    /**
     * Check if component is enabled
     *
     * @param string $component_name Component name
     * @return bool True if enabled, false if disabled
     */
    private static function is_component_enabled($component_name) {
        $disabled_components = get_option('disabled_components', []);
        return !in_array($component_name, $disabled_components);
    }

    /**
     * Display admin notices
     */
    public static function display_admin_notices() {
        if (isset($_GET['clear_component_cache']) && current_user_can('manage_options')) {
            if (class_exists('ComponentAutoLoader')) {
                ComponentAutoLoader::clear_discovery_cache();
                echo '<div class="notice notice-success is-dismissible"><p>Component discovery cache cleared successfully!</p></div>';
            }
        }
    }
}

// Initialize the dashboard
ComponentDashboard::init();
