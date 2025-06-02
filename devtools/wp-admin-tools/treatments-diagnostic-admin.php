<?php
/**
 * Treatments Diagnostic Admin Page
 * WordPress Admin Integration for DevKinsta Debugging
 * Created by BUG-RESOLVER-001 Agent
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * Register the admin menu and page
 */
function treatments_diagnostic_admin_menu() {
    add_submenu_page(
        'tools.php',
        'Treatments Diagnostic',
        'Treatments Diagnostic',
        'read',
        'treatments-diagnostic',
        'treatments_diagnostic_admin_page'
    );
}
add_action('admin_menu', 'treatments_diagnostic_admin_menu');

/**
 * Enqueue admin styles and scripts
 */
function treatments_diagnostic_admin_scripts($hook) {
    if ($hook !== 'tools_page_treatments-diagnostic') {
        return;
    }

    wp_enqueue_script('jquery');
    wp_enqueue_script(
        'treatments-diagnostic-admin',
        get_template_directory_uri() . '/devtools/wp-admin-tools/assets/treatments-diagnostic.js',
        ['jquery'],
        '1.0.0',
        true
    );

    wp_localize_script('treatments-diagnostic-admin', 'treatmentsDiagnostic', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('treatments_diagnostic_nonce'),
        'strings' => [
            'running' => __('Running diagnostic...', 'medspatheme'),
            'complete' => __('Diagnostic complete!', 'medspatheme'),
            'error' => __('Error occurred during diagnostic.', 'medspatheme')
        ]
    ]);

    // Inline styles for better presentation
    wp_add_inline_style('wp-admin', '
        .treatments-diagnostic-container {
            max-width: 1200px;
            margin: 20px 0;
        }
        .diagnostic-section {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            margin-bottom: 20px;
            padding: 20px;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
        .diagnostic-section h3 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .diagnostic-results {
            font-family: "Courier New", monospace;
            background: #f6f7f7;
            border: 1px solid #ddd;
            border-radius: 3px;
            padding: 15px;
            white-space: pre-wrap;
            max-height: 400px;
            overflow-y: auto;
        }
        .diagnostic-status {
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .status-success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .status-warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; }
        .status-error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .diagnostic-progress {
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
            background: #0073aa;
            transition: width 0.3s ease;
        }
        .quick-actions {
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
            background: #0073aa;
            color: white;
        }
        .action-secondary {
            background: #f6f7f7;
            color: #555;
            border: 1px solid #ddd;
        }
        .action-button:hover {
            opacity: 0.9;
        }
    ');
}
add_action('admin_enqueue_scripts', 'treatments_diagnostic_admin_scripts');

/**
 * Handle AJAX diagnostic requests
 */
function treatments_diagnostic_ajax_handler() {
    // Security check
    check_ajax_referer('treatments_diagnostic_nonce', 'nonce');

    if (!current_user_can('read')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    $action = sanitize_text_field($_POST['diagnostic_action'] ?? 'full');

    ob_start();

    switch ($action) {
        case 'environment':
            run_environment_check();
            break;
        case 'database':
            run_database_check();
            break;
        case 'treatments':
            run_treatments_check();
            break;
        case 'templates':
            run_templates_check();
            break;
        case 'full':
        default:
            run_full_diagnostic();
            break;
    }

    $output = ob_get_clean();

    wp_send_json_success([
        'output' => $output,
        'timestamp' => current_time('mysql')
    ]);
}
add_action('wp_ajax_treatments_diagnostic', 'treatments_diagnostic_ajax_handler');

/**
 * Main admin page content
 */
function treatments_diagnostic_admin_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

        <div class="treatments-diagnostic-container">

            <!-- Introduction Section -->
            <div class="diagnostic-section">
                <h3>🔧 DevKinsta Treatments Page Diagnostic Tool</h3>
                <p>This tool helps diagnose and resolve issues with the treatments page in your DevKinsta WordPress environment. It performs comprehensive checks on your WordPress installation, database connectivity, treatment post types, and template files.</p>

                <div class="quick-actions">
                    <button type="button" class="action-button action-primary" id="run-full-diagnostic">
                        🔍 Run Full Diagnostic
                    </button>
                    <button type="button" class="action-button action-secondary" id="run-environment-check">
                        🌐 Environment Check
                    </button>
                    <button type="button" class="action-button action-secondary" id="run-database-check">
                        🗄️ Database Check
                    </button>
                    <button type="button" class="action-button action-secondary" id="run-treatments-check">
                        💉 Treatments Check
                    </button>
                    <button type="button" class="action-button action-secondary" id="run-templates-check">
                        📄 Templates Check
                    </button>
                </div>
            </div>

            <!-- Progress Section -->
            <div class="diagnostic-progress" id="diagnostic-progress">
                <h4>Running Diagnostic...</h4>
                <div class="progress-bar">
                    <div class="progress-fill" id="progress-fill"></div>
                </div>
                <p id="progress-status">Initializing...</p>
            </div>

            <!-- Results Section -->
            <div class="diagnostic-section" id="results-section" style="display: none;">
                <h3>🔍 Diagnostic Results</h3>
                <div class="diagnostic-status" id="diagnostic-status"></div>
                <div class="diagnostic-results" id="diagnostic-output"></div>

                <div class="quick-actions">
                    <a href="<?php echo esc_url(admin_url('tools.php?page=treatments-fix-utility')); ?>" class="action-button action-primary">
                        🔧 Run Fix Utility
                    </a>
                    <a href="<?php echo esc_url(get_site_url() . '/treatments/'); ?>" class="action-button action-secondary" target="_blank">
                        🌐 View Treatments Page
                    </a>
                    <a href="<?php echo esc_url(admin_url('post-new.php?post_type=treatment')); ?>" class="action-button action-secondary">
                        ➕ Add Treatment
                    </a>
                    <button type="button" class="action-button action-secondary" id="download-results">
                        💾 Download Results
                    </button>
                </div>
            </div>

            <!-- System Information -->
            <div class="diagnostic-section">
                <h3>📊 System Information</h3>
                <table class="wp-list-table widefat striped">
                    <tbody>
                        <tr>
                            <td><strong>WordPress Version:</strong></td>
                            <td><?php echo esc_html(get_bloginfo('version')); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Site URL:</strong></td>
                            <td><?php echo esc_html(get_site_url()); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Active Theme:</strong></td>
                            <td><?php echo esc_html(get_template()); ?></td>
                        </tr>
                        <tr>
                            <td><strong>PHP Version:</strong></td>
                            <td><?php echo esc_html(PHP_VERSION); ?></td>
                        </tr>
                        <tr>
                            <td><strong>MySQL Version:</strong></td>
                            <td><?php
                                global $wpdb;
                                echo esc_html($wpdb->db_version());
                            ?></td>
                        </tr>
                        <tr>
                            <td><strong>DevKinsta Environment:</strong></td>
                            <td><?php echo defined('DEVKINSTA_ENV') ? '✅ Detected' : '⚠️ Not Detected'; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Handle diagnostic button clicks
        $('.action-button[id^="run-"]').on('click', function() {
            var action = $(this).attr('id').replace('run-', '').replace('-check', '').replace('-diagnostic', 'full');
            runDiagnostic(action);
        });

        // Download results functionality
        $('#download-results').on('click', function() {
            var results = $('#diagnostic-output').text();
            var blob = new Blob([results], { type: 'text/plain' });
            var url = window.URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = 'treatments-diagnostic-' + new Date().toISOString().slice(0,10) + '.txt';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        });

        function runDiagnostic(action) {
            // Show progress
            $('#diagnostic-progress').show();
            $('#results-section').hide();

            // Simulate progress updates
            var progress = 0;
            var progressInterval = setInterval(function() {
                progress += Math.random() * 20;
                if (progress > 90) progress = 90;
                $('#progress-fill').css('width', progress + '%');

                var messages = [
                    'Checking WordPress environment...',
                    'Testing database connectivity...',
                    'Validating treatment post types...',
                    'Examining template files...',
                    'Analyzing query results...',
                    'Generating recommendations...'
                ];
                $('#progress-status').text(messages[Math.floor(Math.random() * messages.length)]);
            }, 500);

            // Run actual diagnostic
            $.ajax({
                url: treatmentsDiagnostic.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'treatments_diagnostic',
                    diagnostic_action: action,
                    nonce: treatmentsDiagnostic.nonce
                },
                success: function(response) {
                    clearInterval(progressInterval);
                    $('#progress-fill').css('width', '100%');
                    $('#progress-status').text('Complete!');

                    setTimeout(function() {
                        $('#diagnostic-progress').hide();
                        $('#results-section').show();
                        $('#diagnostic-output').text(response.data.output);

                        // Determine status based on output
                        var output = response.data.output;
                        var statusClass = 'status-success';
                        var statusText = '✅ Diagnostic completed successfully';

                        if (output.includes('❌') || output.includes('ERROR')) {
                            statusClass = 'status-error';
                            statusText = '❌ Issues detected - review results below';
                        } else if (output.includes('⚠️') || output.includes('WARNING')) {
                            statusClass = 'status-warning';
                            statusText = '⚠️ Warnings found - check recommendations';
                        }

                        $('#diagnostic-status')
                            .removeClass('status-success status-warning status-error')
                            .addClass(statusClass)
                            .text(statusText);
                    }, 1000);
                },
                error: function() {
                    clearInterval(progressInterval);
                    $('#diagnostic-progress').hide();
                    $('#results-section').show();
                    $('#diagnostic-status')
                        .removeClass('status-success status-warning')
                        .addClass('status-error')
                        .text('❌ Error occurred during diagnostic');
                    $('#diagnostic-output').text('Failed to run diagnostic. Please check your permissions and try again.');
                }
            });
        }
    });
    </script>

    <?php
}

/**
 * Individual diagnostic functions
 */
function run_environment_check() {
    echo "🔍 WORDPRESS ENVIRONMENT CHECK\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "• WordPress Version: " . get_bloginfo('version') . "\n";
    echo "• Site URL: " . get_site_url() . "\n";
    echo "• Admin URL: " . admin_url() . "\n";
    echo "• Theme: " . get_template() . "\n";
    echo "• Active Theme: " . (get_template_directory() ? '✅ Found' : '❌ Missing') . "\n";
    echo "• PHP Version: " . PHP_VERSION . "\n";
    echo "• Memory Limit: " . ini_get('memory_limit') . "\n";
    echo "• Max Execution Time: " . ini_get('max_execution_time') . "s\n";
}

function run_database_check() {
    echo "🔍 DATABASE CONNECTION CHECK\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    global $wpdb;
    try {
        $db_check = $wpdb->get_var("SELECT 1");
        if ($db_check === '1') {
            echo "✅ Database connection: Working\n";
            echo "• Database Host: " . DB_HOST . "\n";
            echo "• Database Name: " . DB_NAME . "\n";
            echo "• Database Version: " . $wpdb->db_version() . "\n";
            echo "• Table Prefix: " . $wpdb->prefix . "\n";
        } else {
            echo "❌ Database connection: Failed\n";
        }
    } catch (Exception $e) {
        echo "❌ Database error: " . $e->getMessage() . "\n";
    }
}

function run_treatments_check() {
    echo "🔍 TREATMENTS CHECK\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

    // Check treatments page
    $treatments_page = get_page_by_path('treatments');
    if ($treatments_page) {
        echo "✅ Treatments page exists (ID: {$treatments_page->ID})\n";
        $template = get_post_meta($treatments_page->ID, '_wp_page_template', true);
        echo "• Template: " . ($template ?: 'default') . "\n";
    } else {
        echo "❌ Treatments page does not exist\n";
    }

    // Check treatment post type
    $treatment_post_type = get_post_type_object('treatment');
    if ($treatment_post_type) {
        echo "✅ Treatment post type registered\n";
        echo "• Label: {$treatment_post_type->label}\n";
        echo "• Public: " . ($treatment_post_type->public ? 'Yes' : 'No') . "\n";
    } else {
        echo "❌ Treatment post type not registered\n";
    }

    // Check for treatments
    $treatments_query = new WP_Query([
        'post_type' => 'treatment',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ]);

    if ($treatments_query->have_posts()) {
        echo "✅ Found {$treatments_query->found_posts} published treatments\n";
        while ($treatments_query->have_posts()) {
            $treatments_query->the_post();
            echo "  • " . get_the_title() . "\n";
        }
        wp_reset_postdata();
    } else {
        echo "❌ No published treatments found\n";
    }
}

function run_templates_check() {
    echo "🔍 TEMPLATE FILES CHECK\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

    $template_files = [
        'page-treatments.php',
        'treatments-content.php',
        'single-treatment.php',
        'archive-treatment.php'
    ];

    foreach ($template_files as $file) {
        $path = get_template_directory() . '/' . $file;
        if (file_exists($path)) {
            $size = filesize($path);
            echo "✅ {$file}: Found ({$size} bytes)\n";
        } else {
            echo "❌ {$file}: Missing\n";
        }
    }
}

function run_full_diagnostic() {
    echo "=== TREATMENTS PAGE DIAGNOSTIC TOOL ===\n";
    echo "Time: " . current_time('Y-m-d H:i:s') . "\n\n";

    run_environment_check();
    echo "\n";
    run_database_check();
    echo "\n";
    run_treatments_check();
    echo "\n";
    run_templates_check();
    echo "\n";

    echo "📊 DIAGNOSTIC COMPLETE\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "For additional help:\n";
    echo "• WordPress Admin: " . admin_url() . "\n";
    echo "• Treatments Page: " . get_site_url() . "/treatments/\n";
    echo "• Add Treatment: " . admin_url('post-new.php?post_type=treatment') . "\n";
}

?>
