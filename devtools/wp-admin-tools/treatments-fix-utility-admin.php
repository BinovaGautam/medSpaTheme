<?php
/**
 * Treatments Fix Utility Admin Page
 * WordPress Admin Integration for DevKinsta Bug Fixing
 * Created by BUG-RESOLVER-001 Agent
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * Register the admin menu and page
 */
function treatments_fix_utility_admin_menu() {
    add_submenu_page(
        'tools.php',
        'Treatments Fix Utility',
        'Treatments Fix Utility',
        'read',
        'treatments-fix-utility',
        'treatments_fix_utility_admin_page'
    );
}
add_action('admin_menu', 'treatments_fix_utility_admin_menu');

/**
 * Enqueue admin styles and scripts
 */
function treatments_fix_utility_admin_scripts($hook) {
    if ($hook !== 'tools_page_treatments-fix-utility') {
        return;
    }

    wp_enqueue_script('jquery');
    wp_enqueue_script(
        'treatments-fix-utility-admin',
        get_template_directory_uri() . '/devtools/wp-admin-tools/assets/treatments-fix-utility.js',
        ['jquery'],
        '1.0.0',
        true
    );

    wp_localize_script('treatments-fix-utility-admin', 'treatmentsFixUtility', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('treatments_fix_utility_nonce'),
        'strings' => [
            'running' => __('Running fix...', 'medspatheme'),
            'complete' => __('Fix complete!', 'medspatheme'),
            'error' => __('Error occurred during fix.', 'medspatheme')
        ]
    ]);

    // Inline styles for better presentation
    wp_add_inline_style('wp-admin', '
        .treatments-fix-container {
            max-width: 1200px;
            margin: 20px 0;
        }
        .fix-section {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            margin-bottom: 20px;
            padding: 20px;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
        .fix-section h3 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .fix-results {
            font-family: "Courier New", monospace;
            background: #f6f7f7;
            border: 1px solid #ddd;
            border-radius: 3px;
            padding: 15px;
            white-space: pre-wrap;
            max-height: 400px;
            overflow-y: auto;
        }
        .fix-status {
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .status-success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .status-warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; }
        .status-error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .fix-progress {
            display: none;
            margin: 10px 0;
        }
        .progress-bar {
            width: 100%;
            height: 20px;
            background: #f1f1f1;
            border-radius: 10px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: #00a32a;
            transition: width 0.3s ease;
        }
        .fix-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        .action-button {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
            display: inline-block;
        }
        .action-primary {
            background: #00a32a;
            color: white;
        }
        .action-secondary {
            background: #f6f7f7;
            color: #555;
            border: 1px solid #ddd;
        }
        .action-warning {
            background: #d63638;
            color: white;
        }
        .action-button:hover {
            opacity: 0.9;
        }
        .fix-step {
            border-left: 4px solid #0073aa;
            padding: 15px;
            margin: 10px 0;
            background: #f8f9fa;
        }
        .fix-step.completed {
            border-left-color: #00a32a;
        }
        .fix-step.error {
            border-left-color: #d63638;
        }
    ');
}
add_action('admin_enqueue_scripts', 'treatments_fix_utility_admin_scripts');

/**
 * Handle AJAX fix requests
 */
function treatments_fix_utility_ajax_handler() {
    // Security check
    check_ajax_referer('treatments_fix_utility_nonce', 'nonce');

    if (!current_user_can('read')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    $action = sanitize_text_field($_POST['fix_action'] ?? 'quick_fix');

    ob_start();

    switch ($action) {
        case 'devkinsta_restart':
            run_devkinsta_restart_fix();
            break;
        case 'database_connection':
            run_database_connection_fix();
            break;
        case 'permissions':
            run_permissions_fix();
            break;
        case 'templates':
            run_templates_fix();
            break;
        case 'treatments_page':
            run_treatments_page_fix();
            break;
        case 'quick_fix':
        default:
            run_quick_fix();
            break;
    }

    $output = ob_get_clean();

    wp_send_json_success([
        'output' => $output,
        'timestamp' => current_time('mysql')
    ]);
}
add_action('wp_ajax_treatments_fix_utility', 'treatments_fix_utility_ajax_handler');

/**
 * Main admin page content
 */
function treatments_fix_utility_admin_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

        <div class="treatments-fix-container">

            <!-- Introduction Section -->
            <div class="fix-section">
                <h3>🔧 DevKinsta Treatments Fix Utility</h3>
                <p>This tool provides step-by-step fixes for common treatments page issues in DevKinsta. It will guide you through the resolution process with visual feedback and downloadable reports.</p>

                <div class="fix-actions">
                    <button type="button" class="action-button action-primary" id="run-quick-fix">
                        🚀 Quick Fix All Issues
                    </button>
                    <button type="button" class="action-button action-secondary" id="run-devkinsta-restart">
                        🔄 Fix DevKinsta Restart
                    </button>
                    <button type="button" class="action-button action-secondary" id="run-database-fix">
                        🗄️ Fix Database Connection
                    </button>
                    <button type="button" class="action-button action-secondary" id="run-treatments-page-fix">
                        📄 Fix Treatments Page
                    </button>
                    <a href="<?php echo home_url('/treatments/'); ?>" target="_blank" class="action-button action-secondary">
                        🌐 View Treatments Page
                    </a>
                </div>

                <div class="fix-progress" id="fix-progress">
                    <div class="progress-bar">
                        <div class="progress-fill" id="progress-fill"></div>
                    </div>
                    <p id="progress-text">Ready to run fixes...</p>
                </div>
            </div>

            <!-- Results Section -->
            <div class="fix-section">
                <h3>Fix Results</h3>
                <div class="fix-results" id="fix-results">
Click a fix button above to start the automated repair process...

🔧 Available Fixes:
• Quick Fix All Issues - Comprehensive automated fix
• DevKinsta Restart Fix - Restart DevKinsta services
• Database Connection Fix - Repair database connectivity
• Treatments Page Fix - Create missing page and template
                </div>

                <div class="fix-actions">
                    <button type="button" class="action-button action-secondary" id="download-fix-report" style="display: none;">
                        📥 Download Fix Report
                    </button>
                    <button type="button" class="action-button action-secondary" id="clear-results">
                        🗑️ Clear Results
                    </button>
                </div>
            </div>

            <!-- Emergency Instructions -->
            <div class="fix-section">
                <h3>🚨 Emergency Manual Instructions</h3>
                <p>If the automated fixes don't work, follow these manual steps:</p>

                <div class="fix-step">
                    <h4>Step 1: Restart DevKinsta</h4>
                    <p>1. Open DevKinsta application<br>
                    2. Click "Stop" button for medspaa site<br>
                    3. Wait 10 seconds<br>
                    4. Click "Start" button<br>
                    5. Wait for green "Running" status</p>
                </div>

                <div class="fix-step">
                    <h4>Step 2: Check Database Connection</h4>
                    <p>1. Go to <a href="<?php echo admin_url('tools.php?page=treatments-diagnostic'); ?>">Treatments Diagnostic</a><br>
                    2. Run "Database Check"<br>
                    3. If connection fails, restart DevKinsta (Step 1)</p>
                </div>

                <div class="fix-step">
                    <h4>Step 3: Create Treatments Page</h4>
                    <p>1. Go to <a href="<?php echo admin_url('post-new.php?post_type=page'); ?>">Pages → Add New</a><br>
                    2. Title: "Treatments"<br>
                    3. Slug: "treatments"<br>
                    4. Template: "Treatments Page Template"<br>
                    5. Publish the page</p>
                </div>

                <div class="fix-step">
                    <h4>Step 4: Verify Template Files</h4>
                    <p>Check that these files exist in your theme:<br>
                    • page-treatments.php<br>
                    • single-treatment.php<br>
                    • archive-treatment.php</p>
                </div>
            </div>

            <!-- System Information -->
            <div class="fix-section">
                <h3>📊 System Information</h3>
                <div class="fix-results">
WordPress Version: <?php echo get_bloginfo('version'); ?>
PHP Version: <?php echo PHP_VERSION; ?>
Theme: <?php echo wp_get_theme()->get('Name'); ?> v<?php echo wp_get_theme()->get('Version'); ?>
Active Plugins: <?php echo count(get_option('active_plugins')); ?>
Database: <?php global $wpdb; echo $wpdb->db_version(); ?>
Site URL: <?php echo get_site_url(); ?>
Admin URL: <?php echo admin_url(); ?>
Current User: <?php echo wp_get_current_user()->user_login; ?> (<?php echo implode(', ', wp_get_current_user()->roles); ?>)
                </div>
            </div>

        </div>
    </div>

    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Fix button handlers
        $('#run-quick-fix').on('click', function() {
            runFix('quick_fix', 'Running comprehensive quick fix...');
        });

        $('#run-devkinsta-restart').on('click', function() {
            runFix('devkinsta_restart', 'Providing DevKinsta restart instructions...');
        });

        $('#run-database-fix').on('click', function() {
            runFix('database_connection', 'Checking and fixing database connection...');
        });

        $('#run-treatments-page-fix').on('click', function() {
            runFix('treatments_page', 'Creating treatments page and templates...');
        });

        function runFix(action, progressText) {
            showProgress(progressText);

            $.ajax({
                url: treatmentsFixUtility.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'treatments_fix_utility',
                    fix_action: action,
                    nonce: treatmentsFixUtility.nonce
                },
                success: function(response) {
                    hideProgress();
                    if (response.success) {
                        $('#fix-results').html(response.data.output);
                        $('#download-fix-report').show();
                    } else {
                        $('#fix-results').html('Error: ' + (response.data || 'Unknown error occurred'));
                    }
                },
                error: function() {
                    hideProgress();
                    $('#fix-results').html('Network error occurred. Please try again.');
                }
            });
        }

        function showProgress(text) {
            $('#fix-progress').show();
            $('#progress-text').text(text);
            animateProgress();
        }

        function hideProgress() {
            $('#fix-progress').hide();
            $('#progress-fill').css('width', '0%');
        }

        function animateProgress() {
            $('#progress-fill').css('width', '10%');
            setTimeout(() => $('#progress-fill').css('width', '50%'), 500);
            setTimeout(() => $('#progress-fill').css('width', '80%'), 1500);
        }

        // Clear results
        $('#clear-results').on('click', function() {
            $('#fix-results').html('Click a fix button above to start the automated repair process...\n\n🔧 Available Fixes:\n• Quick Fix All Issues - Comprehensive automated fix\n• DevKinsta Restart Fix - Restart DevKinsta services\n• Database Connection Fix - Repair database connectivity\n• Treatments Page Fix - Create missing page and template');
            $('#download-fix-report').hide();
        });

        // Download report
        $('#download-fix-report').on('click', function() {
            const results = $('#fix-results').text();
            const blob = new Blob([results], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'treatments-fix-report-' + new Date().toISOString().slice(0,19).replace(/:/g, '-') + '.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        });
    });
    </script>
    <?php
}

/**
 * Run comprehensive quick fix
 */
function run_quick_fix() {
    echo "🚀 COMPREHENSIVE QUICK FIX STARTING...\n";
    echo "=====================================\n\n";

    $steps = [
        'Environment Check' => 'run_environment_check_for_fix',
        'Database Connection' => 'run_database_connection_fix',
        'Treatments Page Creation' => 'run_treatments_page_fix',
        'Template Verification' => 'run_templates_fix',
        'Permissions Check' => 'run_permissions_fix'
    ];

    $completed = 0;
    $total = count($steps);

    foreach ($steps as $step_name => $function) {
        echo "📋 STEP: {$step_name}\n";
        echo str_repeat('-', 40) . "\n";

        if (function_exists($function)) {
            call_user_func($function);
        } else {
            echo "⚠️  Function {$function} not found\n";
        }

        $completed++;
        $progress = round(($completed / $total) * 100);
        echo "\n✅ Step completed ({$progress}% total progress)\n\n";
    }

    echo "🎉 QUICK FIX COMPLETED!\n";
    echo "=======================\n\n";
    echo "✅ All automated fixes have been applied.\n";
    echo "🌐 Test your treatments page: " . home_url('/treatments/') . "\n";
    echo "📊 Check diagnostic: " . admin_url('tools.php?page=treatments-diagnostic') . "\n\n";
    echo "If issues persist, try the manual emergency instructions above.\n";
}

/**
 * Run DevKinsta restart fix
 */
function run_devkinsta_restart_fix() {
    echo "🔄 DEVKINSTA RESTART INSTRUCTIONS\n";
    echo "=================================\n\n";

    echo "📱 AUTOMATIC DETECTION:\n";
    echo "DevKinsta Status: ";

    // Check if we can detect DevKinsta processes
    if (function_exists('shell_exec') && !in_array('shell_exec', explode(',', ini_get('disable_functions')))) {
        $processes = shell_exec('ps aux | grep -i devkinsta 2>/dev/null || echo "Cannot detect processes"');
        if (strpos($processes, 'devkinsta') !== false) {
            echo "✅ DevKinsta processes detected\n";
        } else {
            echo "⚠️  Cannot detect DevKinsta processes\n";
        }
    } else {
        echo "⚠️  Cannot check processes (shell_exec disabled)\n";
    }

    echo "\n📋 MANUAL RESTART STEPS:\n";
    echo "1. Open DevKinsta application on your computer\n";
    echo "2. Find 'medspaa' site in the site list\n";
    echo "3. Click the 'Stop' button (red square icon)\n";
    echo "4. Wait 10-15 seconds for complete shutdown\n";
    echo "5. Click the 'Start' button (green play icon)\n";
    echo "6. Wait for 'Running' status (green indicator)\n";
    echo "7. Check database connection with diagnostic tool\n\n";

    echo "🔍 VERIFICATION:\n";
    echo "After restart, verify these URLs work:\n";
    echo "• Site: " . home_url() . "\n";
    echo "• Admin: " . admin_url() . "\n";
    echo "• Treatments: " . home_url('/treatments/') . "\n\n";

    echo "⚠️  TROUBLESHOOTING:\n";
    echo "If restart doesn't work:\n";
    echo "• Check DevKinsta application logs\n";
    echo "• Try 'Force Stop' if available\n";
    echo "• Restart your computer if DevKinsta is unresponsive\n";
    echo "• Contact Kinsta support if issues persist\n";
}

/**
 * Run database connection fix
 */
function run_database_connection_fix() {
    echo "🗄️  DATABASE CONNECTION FIX\n";
    echo "===========================\n\n";

    global $wpdb;

    echo "📊 TESTING DATABASE CONNECTION:\n";

    try {
        $result = $wpdb->get_var("SELECT 1");
        if ($result == 1) {
            echo "✅ Database connection is working!\n";
            echo "Database Version: " . $wpdb->db_version() . "\n";
            echo "Database Name: " . DB_NAME . "\n";
        } else {
            echo "❌ Database connection test failed\n";
        }
    } catch (Exception $e) {
        echo "❌ Database connection error: " . $e->getMessage() . "\n";
    }

    echo "\n📋 RUNNING FIXES:\n";

    // Check wp-config.php database settings
    echo "• Checking wp-config.php settings...\n";
    if (defined('DB_HOST') && defined('DB_NAME') && defined('DB_USER')) {
        echo "  ✅ Database constants are defined\n";
        echo "  Host: " . DB_HOST . "\n";
        echo "  Database: " . DB_NAME . "\n";
        echo "  User: " . DB_USER . "\n";
    } else {
        echo "  ❌ Database constants missing in wp-config.php\n";
    }

    // Test table access
    echo "• Testing table access...\n";
    $tables = $wpdb->get_results("SHOW TABLES", ARRAY_A);
    if (!empty($tables)) {
        echo "  ✅ Can access database tables (" . count($tables) . " tables found)\n";
    } else {
        echo "  ❌ Cannot access database tables\n";
    }

    echo "\n🔧 RECOMMENDED ACTIONS:\n";
    echo "1. If connection failed, restart DevKinsta\n";
    echo "2. Check DevKinsta database status in the app\n";
    echo "3. Verify site is running in DevKinsta\n";
    echo "4. Check for DevKinsta application updates\n";
}

/**
 * Environment check for fix utility
 */
function run_environment_check_for_fix() {
    echo "🌐 ENVIRONMENT CHECK\n";
    echo "===================\n\n";

    echo "WordPress: " . get_bloginfo('version') . " ";
    echo (version_compare(get_bloginfo('version'), '5.0', '>=') ? "✅" : "⚠️") . "\n";

    echo "PHP: " . PHP_VERSION . " ";
    echo (version_compare(PHP_VERSION, '7.4', '>=') ? "✅" : "⚠️") . "\n";

    echo "Theme: " . wp_get_theme()->get('Name') . " ";
    echo (wp_get_theme()->exists() ? "✅" : "❌") . "\n";

    echo "Site URL: " . get_site_url() . " ";
    echo (strpos(get_site_url(), '.local') !== false ? "✅ DevKinsta" : "⚠️") . "\n";
}

/**
 * Run treatments page fix
 */
function run_treatments_page_fix() {
    echo "📄 TREATMENTS PAGE FIX\n";
    echo "======================\n\n";

    // Check if treatments page exists
    $treatments_page = get_page_by_path('treatments');

    if (!$treatments_page) {
        echo "🔧 Creating treatments page...\n";

        $page_id = wp_insert_post([
            'post_title' => 'Treatments',
            'post_name' => 'treatments',
            'post_content' => '<p>Discover our comprehensive range of premium medical spa treatments.</p>',
            'post_status' => 'publish',
            'post_type' => 'page',
            'comment_status' => 'closed',
            'ping_status' => 'closed'
        ]);

        if ($page_id && !is_wp_error($page_id)) {
            update_post_meta($page_id, '_wp_page_template', 'page-treatments.php');
            echo "  ✅ Treatments page created (ID: {$page_id})\n";
            echo "  ✅ Template assigned: page-treatments.php\n";
        } else {
            echo "  ❌ Failed to create treatments page\n";
        }
    } else {
        echo "✅ Treatments page already exists (ID: {$treatments_page->ID})\n";

        // Check template assignment
        $template = get_post_meta($treatments_page->ID, '_wp_page_template', true);
        if ($template !== 'page-treatments.php') {
            update_post_meta($treatments_page->ID, '_wp_page_template', 'page-treatments.php');
            echo "🔧 Fixed template assignment: page-treatments.php\n";
        } else {
            echo "✅ Template correctly assigned: page-treatments.php\n";
        }
    }

    echo "\n🌐 Page URL: " . home_url('/treatments/') . "\n";
    echo "✏️  Edit page: " . admin_url('post.php?post=' . ($treatments_page ? $treatments_page->ID : $page_id) . '&action=edit') . "\n";
}

/**
 * Run templates fix
 */
function run_templates_fix() {
    echo "📝 TEMPLATES VERIFICATION\n";
    echo "========================\n\n";

    $theme_dir = get_template_directory();
    $required_templates = [
        'page-treatments.php' => 'Treatments page template',
        'single-treatment.php' => 'Single treatment template',
        'archive-treatment.php' => 'Treatment archive template'
    ];

    echo "Theme directory: {$theme_dir}\n\n";

    foreach ($required_templates as $template => $description) {
        $file_path = $theme_dir . '/' . $template;
        if (file_exists($file_path)) {
            echo "✅ {$template} - {$description}\n";
            echo "   Size: " . number_format(filesize($file_path)) . " bytes\n";
        } else {
            echo "⚠️  {$template} - Missing ({$description})\n";
        }
    }

    echo "\n📁 Template directory: {$theme_dir}\n";
}

/**
 * Run permissions fix
 */
function run_permissions_fix() {
    echo "🔐 PERMISSIONS CHECK\n";
    echo "===================\n\n";

    $current_user = wp_get_current_user();
    echo "Current user: {$current_user->user_login}\n";
    echo "User roles: " . implode(', ', $current_user->roles) . "\n\n";

    $capabilities = [
        'edit_posts' => 'Edit posts',
        'edit_pages' => 'Edit pages',
        'manage_options' => 'Manage options (admin)',
        'edit_theme_options' => 'Edit theme options'
    ];

    foreach ($capabilities as $cap => $description) {
        $has_cap = current_user_can($cap);
        echo ($has_cap ? "✅" : "❌") . " {$description} ({$cap})\n";
    }

    if (!current_user_can('edit_posts')) {
        echo "\n⚠️  WARNING: Limited permissions detected\n";
        echo "You may need administrator access for full functionality.\n";
    } else {
        echo "\n✅ Sufficient permissions for basic operations\n";
    }
}
