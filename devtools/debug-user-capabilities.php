<?php
/**
 * Debug User Capabilities and Tool Loading
 * Temporary debug file to check user permissions
 */

// Add this to wp-admin to check user capabilities
add_action('admin_notices', 'debug_user_capabilities_notice');

function debug_user_capabilities_notice() {
    if (!is_admin()) return;

    $current_user = wp_get_current_user();

    echo '<div class="notice notice-info">';
    echo '<p><strong>🔧 Debug: User Capabilities Check</strong></p>';
    echo '<p><strong>User:</strong> ' . $current_user->user_login . '</p>';
    echo '<p><strong>User ID:</strong> ' . $current_user->ID . '</p>';
    echo '<p><strong>Roles:</strong> ' . implode(', ', $current_user->roles) . '</p>';

    echo '<p><strong>Key Capabilities:</strong></p>';
    echo '<ul style="margin-left: 20px;">';

    $caps_to_check = [
        'read',
        'edit_posts',
        'edit_pages',
        'publish_posts',
        'manage_options',
        'edit_theme_options',
        'administrator'
    ];

    foreach ($caps_to_check as $cap) {
        $has_cap = current_user_can($cap);
        echo '<li>' . $cap . ': ' . ($has_cap ? '✅ YES' : '❌ NO') . '</li>';
    }

    echo '</ul>';

    // Check if tools functions exist
    echo '<p><strong>Tool Functions:</strong></p>';
    echo '<ul style="margin-left: 20px;">';
    echo '<li>load_devkinsta_admin_tools: ' . (function_exists('load_devkinsta_admin_tools') ? '✅ Loaded' : '❌ Missing') . '</li>';
    echo '<li>treatments_diagnostic_admin_menu: ' . (function_exists('treatments_diagnostic_admin_menu') ? '✅ Loaded' : '❌ Missing') . '</li>';
    echo '<li>treatments_fix_utility_admin_menu: ' . (function_exists('treatments_fix_utility_admin_menu') ? '✅ Loaded' : '❌ Missing') . '</li>';
    echo '</ul>';

    echo '</div>';
}
