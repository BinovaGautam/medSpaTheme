<?php
/**
 * Minimal PHP Test - Isolate 500 error
 */

echo "<!DOCTYPE html><html><head><title>PHP Test</title></head><body>";
echo "<h1>✅ PHP Test Working</h1>";
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "</p>";

// Test WordPress loading with correct paths
echo "<h2>WordPress Loading Test</h2>";

// From web context, we need to go to document root
$wp_config_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
$wp_load_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

echo "<p>Looking for wp-config.php at: $wp_config_path</p>";
if (file_exists($wp_config_path)) {
    echo "<p>✅ wp-config.php found</p>";
    try {
        require_once($wp_config_path);
        echo "<p>✅ wp-config.php loaded</p>";
    } catch (Exception $e) {
        echo "<p>❌ wp-config.php error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>❌ wp-config.php not found</p>";
}

echo "<p>Looking for wp-load.php at: $wp_load_path</p>";
if (file_exists($wp_load_path)) {
    echo "<p>✅ wp-load.php found</p>";
    try {
        require_once($wp_load_path);
        echo "<p>✅ wp-load.php loaded</p>";
    } catch (Exception $e) {
        echo "<p>❌ wp-load.php error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>❌ wp-load.php not found</p>";
}

// Test WordPress functions
if (function_exists('get_bloginfo')) {
    echo "<p>✅ WordPress functions available</p>";
    echo "<p>Site URL: " . get_bloginfo('url') . "</p>";
} else {
    echo "<p>❌ WordPress functions not available</p>";
}

echo "</body></html>";
?>
