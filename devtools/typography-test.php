<?php
/**
 * Typography Persistence Test
 *
 * Test if typography settings are being saved and loaded correctly
 */

// Include WordPress
if (!defined('ABSPATH')) {
    require_once('../../../wp-config.php');
}

echo "<h1>🎨 Typography Persistence Test</h1>";

// Test 1: Check if saved typography exists
echo "<h2>📋 Test 1: Check Saved Typography</h2>";
$saved_typography = get_option('preetidreams_active_typography');
$typography_config = get_option('preetidreams_visual_customizer_config');
$typography_css_file = get_option('preetidreams_typography_css_file');

echo "<strong>Active Typography:</strong> " . ($saved_typography ?: 'Not set') . "<br>";
echo "<strong>Config Has Typography:</strong> " . (isset($typography_config['typographyPairing']) ? 'Yes - ' . $typography_config['typographyPairing'] : 'No') . "<br>";
echo "<strong>Typography CSS File:</strong> " . ($typography_css_file ? 'Yes' : 'No') . "<br>";

if ($typography_css_file) {
    echo "<pre>" . print_r($typography_css_file, true) . "</pre>";

    // Check if file exists
    if (isset($typography_css_file['path']) && file_exists($typography_css_file['path'])) {
        echo "<strong>✅ CSS File Exists:</strong> " . $typography_css_file['path'] . "<br>";
        echo "<strong>File Size:</strong> " . filesize($typography_css_file['path']) . " bytes<br>";
    } else {
        echo "<strong>❌ CSS File Missing</strong><br>";
    }
}

// Test 2: Manual Typography CSS Generation
echo "<h2>🔧 Test 2: Manual Typography CSS Generation</h2>";

$test_typography_data = [
    'id' => 'tech-minimal',
    'name' => 'Tech Minimal Test',
    'headingFont' => [
        'family' => 'IBM Plex Sans',
        'fallback' => '-apple-system, BlinkMacSystemFont, sans-serif',
        'weights' => ['400', '500', '600', '700']
    ],
    'bodyFont' => [
        'family' => 'IBM Plex Sans',
        'fallback' => '-apple-system, BlinkMacSystemFont, sans-serif',
        'weights' => ['400', '500']
    ],
    'googleFonts' => ['IBM+Plex+Sans:wght@400;500;600;700']
];

echo "<strong>Test Typography Data:</strong><br>";
echo "<pre>" . print_r($test_typography_data, true) . "</pre>";

// Test CSS generation
if (function_exists('generate_typography_css_file')) {
    echo "<strong>Testing generate_typography_css_file()...</strong><br>";
    $result = generate_typography_css_file($test_typography_data);
    echo "Result: " . ($result ? '✅ Success' : '❌ Failed') . "<br>";
} else {
    echo "❌ generate_typography_css_file() function not found<br>";
}

// Test 3: Load Current Typography from Server
echo "<h2>📡 Test 3: Load Current Typography</h2>";

$current = get_option('preetidreams_active_typography', 'medical-professional');
echo "<strong>Current Typography:</strong> $current<br>";

// Test 4: Check Generated CSS Content
echo "<h2>📝 Test 4: Check Generated CSS Content</h2>";

if (function_exists('generate_css_from_typography_data')) {
    $css_content = generate_css_from_typography_data($test_typography_data);
    if ($css_content) {
        echo "<strong>✅ CSS Generated Successfully</strong><br>";
        echo "<strong>CSS Length:</strong> " . strlen($css_content) . " characters<br>";
        echo "<strong>CSS Preview (first 500 chars):</strong><br>";
        echo "<pre style='background:#f0f0f0;padding:10px;'>" . htmlspecialchars(substr($css_content, 0, 500)) . "...</pre>";
    } else {
        echo "❌ CSS generation failed<br>";
    }
} else {
    echo "❌ generate_css_from_typography_data() function not found<br>";
}

// Test 5: Test Setting Typography
echo "<h2>🎯 Test 5: Set Typography (Manual)</h2>";

if (isset($_GET['set_typography'])) {
    $test_config = [
        'typographyPairing' => 'tech-minimal',
        'typographyData' => $test_typography_data,
        'timestamp' => time()
    ];

    update_option('preetidreams_visual_customizer_config', $test_config);
    update_option('preetidreams_active_typography', 'tech-minimal');

    if (function_exists('generate_typography_css_file')) {
        $css_generated = generate_typography_css_file($test_typography_data);
        echo "Manual Typography Set Result: " . ($css_generated ? '✅ Success' : '❌ Failed') . "<br>";
    }

    echo "<a href='?'>Refresh to see results</a><br>";
} else {
    echo "<a href='?set_typography=1'>Click to set typography manually</a><br>";
}

// Test 6: Check if CSS file is being enqueued
echo "<h2>🔗 Test 6: CSS File Enqueuing</h2>";

// Check if the function exists
if (function_exists('enqueue_typography_css')) {
    echo "✅ enqueue_typography_css() function exists<br>";
} else {
    echo "❌ enqueue_typography_css() function not found<br>";
}

// Check WordPress hooks
$hooks_info = [];
if (has_action('wp_enqueue_scripts', 'enqueue_typography_css')) {
    echo "✅ enqueue_typography_css is hooked to wp_enqueue_scripts<br>";
} else {
    echo "❌ enqueue_typography_css is NOT hooked to wp_enqueue_scripts<br>";
}

echo "<h2>🔗 Test URLs</h2>";
echo "<a href='" . home_url() . "' target='_blank'>Visit Website</a> | ";
echo "<a href='" . admin_url('admin.php?page=theme-customizer') . "' target='_blank'>Customizer</a><br>";

echo "<style>
body { font-family: -apple-system, BlinkMacSystemFont, sans-serif; margin: 20px; }
h1, h2 { color: #333; }
pre { background: #f5f5f5; padding: 10px; border-radius: 4px; }
</style>";
?>
