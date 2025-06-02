<?php
/**
 * Test DevKinsta Admin Tools Loading
 * Quick test to verify tools are working
 */

// Test if we're in WordPress
if (!defined('ABSPATH')) {
    die('WordPress not detected');
}

// Test basic functionality
add_action('admin_notices', 'test_devkinsta_tools_loading');

function test_devkinsta_tools_loading() {
    // Only show on dashboard
    $screen = get_current_screen();
    if ($screen->id !== 'dashboard') {
        return;
    }

    echo '<div class="notice notice-success">';
    echo '<p><strong>🧪 DevKinsta Tools Test Results:</strong></p>';

    // Test file existence
    $tools_dir = get_template_directory() . '/devtools/wp-admin-tools/';
    echo '<p><strong>Files:</strong></p>';
    echo '<ul style="margin-left: 20px;">';

    $files_to_check = [
        'load-admin-tools.php',
        'treatments-diagnostic-admin.php',
        'treatments-fix-utility-admin.php'
    ];

    foreach ($files_to_check as $file) {
        $exists = file_exists($tools_dir . $file);
        echo '<li>' . $file . ': ' . ($exists ? '✅ Found' : '❌ Missing') . '</li>';
    }

    echo '</ul>';

    // Test function loading
    echo '<p><strong>Functions:</strong></p>';
    echo '<ul style="margin-left: 20px;">';

    $functions_to_check = [
        'load_devkinsta_admin_tools',
        'treatments_diagnostic_admin_menu',
        'treatments_fix_utility_admin_menu'
    ];

    foreach ($functions_to_check as $func) {
        $exists = function_exists($func);
        echo '<li>' . $func . ': ' . ($exists ? '✅ Loaded' : '❌ Missing') . '</li>';
    }

    echo '</ul>';

    // Test current user
    $current_user = wp_get_current_user();
    echo '<p><strong>Current User:</strong> ' . $current_user->user_login . ' (' . implode(', ', $current_user->roles) . ')</p>';
    echo '<p><strong>Can read:</strong> ' . (current_user_can('read') ? '✅ YES' : '❌ NO') . '</p>';
    echo '<p><strong>Can edit posts:</strong> ' . (current_user_can('edit_posts') ? '✅ YES' : '❌ NO') . '</p>';
    echo '<p><strong>Can manage options:</strong> ' . (current_user_can('manage_options') ? '✅ YES' : '❌ NO') . '</p>';

    // Direct links for testing
    echo '<p><strong>Test Links:</strong></p>';
    echo '<p>';
    echo '<a href="' . admin_url('tools.php?page=treatments-diagnostic') . '" class="button">🔍 Test Diagnostic Tool</a> ';
    echo '<a href="' . admin_url('tools.php?page=treatments-fix-utility') . '" class="button">🔧 Test Fix Utility</a>';
    echo '</p>';

    echo '</div>';
}

// Force menu registration for testing
add_action('admin_menu', 'force_test_menu_registration', 99);

function force_test_menu_registration() {
    // Force add test menu items with minimal permissions
    add_submenu_page(
        'tools.php',
        'Test Diagnostic',
        'Test Diagnostic',
        'read',
        'test-treatments-diagnostic',
        'test_diagnostic_page'
    );

    add_submenu_page(
        'tools.php',
        'Test Fix Utility',
        'Test Fix Utility',
        'read',
        'test-treatments-fix',
        'test_fix_page'
    );
}

function test_diagnostic_page() {
    echo '<div class="wrap">';
    echo '<h1>🧪 Test Diagnostic Page</h1>';
    echo '<p><strong>SUCCESS!</strong> This test page loaded successfully with "read" permissions.</p>';
    echo '<p>If you can see this, the permission system is working.</p>';
    echo '<p>Current user: ' . wp_get_current_user()->user_login . '</p>';
    echo '<p>User roles: ' . implode(', ', wp_get_current_user()->roles) . '</p>';
    echo '</div>';
}

function test_fix_page() {
    echo '<div class="wrap">';
    echo '<h1>🧪 Test Fix Utility Page</h1>';
    echo '<p><strong>SUCCESS!</strong> This test page loaded successfully with "read" permissions.</p>';
    echo '<p>If you can see this, the permission system is working.</p>';
    echo '<p>Current user: ' . wp_get_current_user()->user_login . '</p>';
    echo '<p>User roles: ' . implode(', ', wp_get_current_user()->roles) . '</p>';
    echo '</div>';
}
