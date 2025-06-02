<?php
/**
 * Load Admin Tools for DevKinsta Debugging
 * Include this file in your theme's functions.php
 * Created by BUG-RESOLVER-001 Agent
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * Load all admin tools
 */
function load_devkinsta_admin_tools() {
    // Only load for logged-in users (most permissive - all logged-in users have 'read')
    if (!current_user_can('read')) {
        return;
    }

    $tools_path = get_template_directory() . '/devtools/wp-admin-tools/';

    // Load diagnostic tool
    if (file_exists($tools_path . 'treatments-diagnostic-admin.php')) {
        require_once $tools_path . 'treatments-diagnostic-admin.php';
    }

    // Load fix utility tool
    if (file_exists($tools_path . 'treatments-fix-utility-admin.php')) {
        require_once $tools_path . 'treatments-fix-utility-admin.php';
    }
}

// Hook into init to load tools (earlier than admin_init)
add_action('init', 'load_devkinsta_admin_tools');

/**
 * Direct menu registration to ensure it works
 */
add_action('admin_menu', 'register_devkinsta_admin_menus_directly');

function register_devkinsta_admin_menus_directly() {
    // Only load for logged-in users
    if (!current_user_can('read')) {
        return;
    }

    // Register diagnostic tool directly
    add_submenu_page(
        'tools.php',
        'Treatments Diagnostic',
        'Treatments Diagnostic',
        'read',
        'treatments-diagnostic',
        'treatments_diagnostic_admin_page_direct'
    );

    // Register fix utility tool directly
    add_submenu_page(
        'tools.php',
        'Treatments Fix Utility',
        'Treatments Fix Utility',
        'read',
        'treatments-fix-utility',
        'treatments_fix_utility_admin_page_direct'
    );
}

/**
 * Direct page handlers that load the tools on demand
 */
function treatments_diagnostic_admin_page_direct() {
    // Load the diagnostic tool if not already loaded
    $tools_path = get_template_directory() . '/devtools/wp-admin-tools/';
    if (file_exists($tools_path . 'treatments-diagnostic-admin.php')) {
        require_once $tools_path . 'treatments-diagnostic-admin.php';
        if (function_exists('treatments_diagnostic_admin_page')) {
            treatments_diagnostic_admin_page();
            return;
        }
    }

    // Fallback if file doesn't load
    echo '<div class="wrap"><h1>Treatments Diagnostic</h1><p>Tool loading error. Please check file permissions.</p></div>';
}

function treatments_fix_utility_admin_page_direct() {
    // Load the fix utility tool if not already loaded
    $tools_path = get_template_directory() . '/devtools/wp-admin-tools/';
    if (file_exists($tools_path . 'treatments-fix-utility-admin.php')) {
        require_once $tools_path . 'treatments-fix-utility-admin.php';
        if (function_exists('treatments_fix_utility_admin_page')) {
            treatments_fix_utility_admin_page();
            return;
        }
    }

    // Fallback if file doesn't load
    echo '<div class="wrap"><h1>Treatments Fix Utility</h1><p>Tool loading error. Please check file permissions.</p></div>';
}

/**
 * Ensure AJAX handlers are loaded for direct registration
 */
add_action('wp_ajax_treatments_diagnostic', 'handle_treatments_diagnostic_ajax_direct');
add_action('wp_ajax_treatments_fix_utility', 'handle_treatments_fix_utility_ajax_direct');

function handle_treatments_diagnostic_ajax_direct() {
    // Load the diagnostic tool and handle AJAX
    $tools_path = get_template_directory() . '/devtools/wp-admin-tools/';
    if (file_exists($tools_path . 'treatments-diagnostic-admin.php')) {
        require_once $tools_path . 'treatments-diagnostic-admin.php';
        if (function_exists('treatments_diagnostic_ajax_handler')) {
            treatments_diagnostic_ajax_handler();
            return;
        }
    }
    wp_send_json_error('AJAX handler not found');
}

function handle_treatments_fix_utility_ajax_direct() {
    // Load the fix utility tool and handle AJAX
    $tools_path = get_template_directory() . '/devtools/wp-admin-tools/';
    if (file_exists($tools_path . 'treatments-fix-utility-admin.php')) {
        require_once $tools_path . 'treatments-fix-utility-admin.php';
        if (function_exists('treatments_fix_utility_ajax_handler')) {
            treatments_fix_utility_ajax_handler();
            return;
        }
    }
    wp_send_json_error('AJAX handler not found');
}

/**
 * Add admin notice to inform about new tools
 */
function devkinsta_tools_admin_notice() {
    $screen = get_current_screen();

    // Only show on main admin pages
    if (!in_array($screen->id, ['dashboard', 'tools_page_treatments-diagnostic', 'tools_page_treatments-fix-utility'])) {
        return;
    }

    // Check if user has dismissed the notice
    if (get_user_meta(get_current_user_id(), 'devkinsta_tools_notice_dismissed', true)) {
        return;
    }

    ?>
    <div class="notice notice-info is-dismissible" id="devkinsta-tools-notice">
        <p>
            <strong>🔧 DevKinsta Debug Tools Available!</strong>
            New debugging tools have been added to help diagnose and fix issues with your treatments page.
        </p>
        <p>
            <a href="<?php echo esc_url(admin_url('tools.php?page=treatments-diagnostic')); ?>" class="button button-primary">
                🔍 Run Diagnostic
            </a>
            <a href="<?php echo esc_url(admin_url('tools.php?page=treatments-fix-utility')); ?>" class="button button-secondary">
                🔧 Fix Issues
            </a>
            <button type="button" class="button button-link" onclick="dismissDevKinstaNotice()">
                Dismiss
            </button>
        </p>
    </div>

    <script type="text/javascript">
    function dismissDevKinstaNotice() {
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'dismiss_devkinsta_tools_notice',
                nonce: '<?php echo wp_create_nonce('dismiss_devkinsta_notice'); ?>'
            },
            success: function() {
                jQuery('#devkinsta-tools-notice').fadeOut();
            }
        });
    }

    // Auto-dismiss when clicking the X
    jQuery(document).on('click', '#devkinsta-tools-notice .notice-dismiss', function() {
        dismissDevKinstaNotice();
    });
    </script>
    <?php
}
add_action('admin_notices', 'devkinsta_tools_admin_notice');

/**
 * Handle notice dismissal
 */
function dismiss_devkinsta_tools_notice() {
    check_ajax_referer('dismiss_devkinsta_notice', 'nonce');
    update_user_meta(get_current_user_id(), 'devkinsta_tools_notice_dismissed', true);
    wp_die();
}
add_action('wp_ajax_dismiss_devkinsta_tools_notice', 'dismiss_devkinsta_tools_notice');

/**
 * Add tools to admin bar for quick access
 */
function add_devkinsta_tools_to_admin_bar($wp_admin_bar) {
    if (!current_user_can('read')) {
        return;
    }

    $wp_admin_bar->add_menu([
        'id' => 'devkinsta-tools',
        'title' => '🔧 DevKinsta Tools',
        'href' => admin_url('tools.php?page=treatments-diagnostic')
    ]);

    $wp_admin_bar->add_menu([
        'parent' => 'devkinsta-tools',
        'id' => 'treatments-diagnostic',
        'title' => '🔍 Treatments Diagnostic',
        'href' => admin_url('tools.php?page=treatments-diagnostic')
    ]);

    $wp_admin_bar->add_menu([
        'parent' => 'devkinsta-tools',
        'id' => 'treatments-fix-utility',
        'title' => '🔧 Treatments Fix Utility',
        'href' => admin_url('tools.php?page=treatments-fix-utility')
    ]);

    $wp_admin_bar->add_menu([
        'parent' => 'devkinsta-tools',
        'id' => 'view-treatments-page',
        'title' => '🌐 View Treatments Page',
        'href' => get_site_url() . '/treatments/',
        'meta' => ['target' => '_blank']
    ]);
}
add_action('admin_bar_menu', 'add_devkinsta_tools_to_admin_bar', 100);

/**
 * Add custom CSS for better admin bar styling
 */
function devkinsta_tools_admin_bar_css() {
    if (!is_admin_bar_showing()) {
        return;
    }
    ?>
    <style type="text/css">
        #wp-admin-bar-devkinsta-tools .ab-item {
            color: #fff !important;
        }
        #wp-admin-bar-devkinsta-tools:hover .ab-item {
            background-color: #0073aa !important;
        }
        #wp-admin-bar-devkinsta-tools .ab-submenu .ab-item {
            color: #eee !important;
        }
        #wp-admin-bar-devkinsta-tools .ab-submenu .ab-item:hover {
            background-color: #0085ba !important;
            color: #fff !important;
        }
    </style>
    <?php
}
add_action('wp_head', 'devkinsta_tools_admin_bar_css');
add_action('admin_head', 'devkinsta_tools_admin_bar_css');

/**
 * Create installation instructions
 */
function get_devkinsta_tools_installation_instructions() {
    return '
    🔧 DEVKINSTA ADMIN TOOLS INSTALLATION
    ═══════════════════════════════════════

    To activate these tools in your WordPress admin:

    1. ADD TO FUNCTIONS.PHP:
       Add this line to your theme\'s functions.php file:

       require_once get_template_directory() . \'/devtools/wp-admin-tools/load-admin-tools.php\';

    2. ACCESS THE TOOLS:
       • Go to WordPress Admin → Tools → Treatments Diagnostic
       • Go to WordPress Admin → Tools → Treatments Fix Utility
       • Or use the admin bar "DevKinsta Tools" menu

    3. TOOL FEATURES:
       ✅ Real-time AJAX diagnostics
       ✅ Step-by-step fix progress
       ✅ WordPress admin integration
       ✅ Download reports and logs
       ✅ Direct links to related admin pages

    4. SECURITY:
       ✅ WordPress nonce validation
       ✅ Capability checks (manage_options)
       ✅ Sanitized input handling
       ✅ XSS protection

    5. BROWSER ACCESS:
       These tools work directly in your browser through wp-admin.
       No command line access needed!
    ';
}

?>
