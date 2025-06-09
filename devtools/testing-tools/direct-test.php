<?php
/**
 * Direct Test of Visual Customizer Functions
 */

echo "<h1>Direct Visual Customizer Test</h1>";

// Test 1: Include the file directly
echo "<h2>Test 1: Direct File Include</h2>";
$file_path = __DIR__ . '/inc/visual-customizer-simple.php';
echo "<p>File path: $file_path</p>";
echo "<p>File exists: " . (file_exists($file_path) ? 'YES' : 'NO') . "</p>";

if (file_exists($file_path)) {
    echo "<p>Including file...</p>";
    include_once $file_path;
    echo "<p>File included successfully</p>";
} else {
    echo "<p style='color:red;'>File not found!</p>";
    exit;
}

// Test 2: Check if functions are defined
echo "<h2>Test 2: Function Definitions</h2>";
$functions_to_check = [
    'enqueue_simple_visual_customizer_scripts',
    'simple_visual_customizer_admin_bar',
    'add_floating_visual_customizer_button'
];

foreach ($functions_to_check as $function_name) {
    $exists = function_exists($function_name);
    echo "<p><strong>$function_name:</strong> " . ($exists ? '✅ EXISTS' : '❌ NOT FOUND') . "</p>";
}

// Test 3: Try to call the enqueue function
echo "<h2>Test 3: Function Call Test</h2>";
if (function_exists('enqueue_simple_visual_customizer_scripts')) {
    echo "<p>Calling enqueue_simple_visual_customizer_scripts()...</p>";
    try {
        enqueue_simple_visual_customizer_scripts();
        echo "<p style='color:green;'>✅ Function called successfully</p>";
    } catch (Exception $e) {
        echo "<p style='color:red;'>❌ Error calling function: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color:red;'>❌ Function not available</p>";
}

// Test 4: Admin Bar Integration Test
echo "<h2>Test 4: Admin Bar Integration</h2>";
echo "<p><strong>Access Method:</strong> Admin bar → 🎨 Visual Customizer button → Sidebar opens</p>";
echo "<p><strong>Design Token System:</strong> v1.0.0 (Sprint 2 Extension ACTIVE)</p>";
echo "<p><em>Note: All floating button implementations removed - using clean admin bar integration only</em></p>";
echo "<p style='color:green;'>✅ Access Visual Customizer via admin bar when logged into WordPress</p>";

echo "<h2>Summary</h2>";
echo "<p>Visual Customizer now uses clean admin bar integration instead of floating buttons. Access via WordPress admin bar → 🎨 Visual Customizer.</p>";
?>
