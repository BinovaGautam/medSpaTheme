<?php
/**
 * Debug Visual Customizer Admin
 * Updated for devtools/testing-tools/ location
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    require_once('../../../../wp-config.php');
}

echo "<h1>Visual Customizer Debug</h1>";

// Check if the admin page class exists
if (class_exists('VisualCustomizerAdminPage')) {
    echo "<p style='color: green;'>✅ VisualCustomizerAdminPage class exists</p>";

    // Try to get instance
    try {
        $instance = VisualCustomizerAdminPage::get_instance();
        echo "<p style='color: green;'>✅ VisualCustomizerAdminPage instance created successfully</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error creating instance: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: red;'>❌ VisualCustomizerAdminPage class does not exist</p>";
}

// Check if the color system manager exists
if (class_exists('ColorSystemManager')) {
    echo "<p style='color: green;'>✅ ColorSystemManager class exists</p>";

    try {
        $colorSystem = ColorSystemManager::get_instance();
        echo "<p style='color: green;'>✅ ColorSystemManager instance created successfully</p>";

        $palettes = $colorSystem->get_available_palettes();
        echo "<p style='color: green;'>✅ Found " . count($palettes) . " palettes:</p>";
        foreach ($palettes as $id => $palette) {
            echo "<li>{$id}: {$palette['name']}</li>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error with ColorSystemManager: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: red;'>❌ ColorSystemManager class does not exist</p>";
}

// Check if files exist
$files_to_check = [
    'inc/visual-customizer-admin-page.php',
    'inc/color-system-manager.php',
    'assets/js/visual-customizer-admin.js',
    'assets/css/visual-customizer-admin.css'
];

echo "<h2>File Existence Check:</h2>";
foreach ($files_to_check as $file) {
    $full_path = get_template_directory() . '/' . $file;
    if (file_exists($full_path)) {
        echo "<p style='color: green;'>✅ {$file} exists</p>";
    } else {
        echo "<p style='color: red;'>❌ {$file} missing</p>";
    }
}

// Check for PHP errors
echo "<h2>Error Log Check:</h2>";
if (ini_get('log_errors')) {
    $error_log = ini_get('error_log');
    echo "<p>Error log location: {$error_log}</p>";
} else {
    echo "<p>Error logging is disabled</p>";
}

// Check WordPress admin menus (if in admin context)
if (is_admin()) {
    global $menu, $submenu;
    echo "<h2>WordPress Admin Menus:</h2>";
    foreach ($menu as $menu_item) {
        if (strpos($menu_item[2], 'visual-customizer') !== false) {
            echo "<p style='color: green;'>✅ Found Visual Customizer menu: " . print_r($menu_item, true) . "</p>";
        }
    }
}
