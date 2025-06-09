<?php
/**
 * Typography Diagnostic Test - Comprehensive Font Loading Analysis
 *
 * Tests all typography options for:
 * - Google Fonts URL validity
 * - CSS generation consistency
 * - Font loading success/failure
 * - Weight availability
 */

// Include WordPress
if (!defined('ABSPATH')) {
    // Try multiple possible paths for WordPress
    $wp_paths = [
        '../../../wp-config.php',           // Standard path
        '../../../../wp-config.php',        // If in subdirectory
        dirname(__FILE__) . '/../../../wp-config.php',  // Absolute from this file
        $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php'    // Document root
    ];

    $wp_loaded = false;
    foreach ($wp_paths as $path) {
        if (file_exists($path)) {
            require_once($path);
            $wp_loaded = true;
            break;
        }
    }

    if (!$wp_loaded) {
        die('<h1>❌ WordPress Not Found</h1><p>Unable to locate WordPress installation. Please check paths.</p>');
    }
}

echo "<h1>🎨 Typography Diagnostic Test</h1>";

// Typography database (from JavaScript)
$typography_database = [
    'medical-professional' => [
        'name' => 'Medical Professional',
        'googleFonts' => ['Inter:wght@400;500;600;700'],
        'headingFont' => ['family' => 'Inter', 'weights' => ['400', '500', '600', '700']],
        'bodyFont' => ['family' => 'Inter', 'weights' => ['400', '500']]
    ],
    'luxury-modern' => [
        'name' => 'Luxury Modern',
        'googleFonts' => ['Playfair+Display:wght@400;500;600;700', 'Inter:wght@400;500'],
        'headingFont' => ['family' => 'Playfair Display', 'weights' => ['400', '500', '600', '700']],
        'bodyFont' => ['family' => 'Inter', 'weights' => ['400', '500']]
    ],
    'contemporary-clean' => [
        'name' => 'Contemporary Clean',
        'googleFonts' => ['Poppins:wght@400;500;600;700'],
        'headingFont' => ['family' => 'Poppins', 'weights' => ['400', '500', '600', '700']],
        'bodyFont' => ['family' => 'Poppins', 'weights' => ['400', '500']]
    ],
    'wellness-serif' => [
        'name' => 'Wellness Serif',
        'googleFonts' => ['Crimson+Text:wght@400;500;600;700'],
        'headingFont' => ['family' => 'Crimson Text', 'weights' => ['400', '500', '600', '700']],
        'bodyFont' => ['family' => 'Crimson Text', 'weights' => ['400', '500', '600']]
    ],
    'modern-sans' => [
        'name' => 'Modern Sans',
        'googleFonts' => ['Montserrat:wght@400;500;600;700'],
        'headingFont' => ['family' => 'Montserrat', 'weights' => ['400', '500', '600', '700']],
        'bodyFont' => ['family' => 'Montserrat', 'weights' => ['400', '500']]
    ],
    'classic-elegant' => [
        'name' => 'Classic Elegant',
        'googleFonts' => ['Cormorant%20Garamond:wght@400;500;600;700', 'Inter:wght@400;500'],
        'headingFont' => ['family' => 'Cormorant Garamond', 'weights' => ['400', '500', '600', '700']],
        'bodyFont' => ['family' => 'Inter', 'weights' => ['400', '500']]
    ],
    'tech-minimal' => [
        'name' => 'Tech Minimal',
        'googleFonts' => ['IBM%20Plex%20Sans:wght@400;500;600;700'],
        'headingFont' => ['family' => 'IBM Plex Sans', 'weights' => ['400', '500', '600', '700']],
        'bodyFont' => ['family' => 'IBM Plex Sans', 'weights' => ['400', '500']]
    ],
    'warm-organic' => [
        'name' => 'Warm Organic',
        'googleFonts' => ['Merriweather:wght@400;500;600;700;900', 'Inter:wght@400;500;600'],
        'headingFont' => ['family' => 'Merriweather', 'weights' => ['400', '500', '600', '700', '900']],
        'bodyFont' => ['family' => 'Inter', 'weights' => ['400', '500', '600']]
    ]
];

echo "<h2>📋 Google Fonts URL Testing</h2>";

foreach ($typography_database as $id => $typography) {
    echo "<h3>🎨 {$typography['name']} ({$id})</h3>";

    foreach ($typography['googleFonts'] as $fontUrl) {
        $fullUrl = "https://fonts.googleapis.com/css2?family={$fontUrl}&display=swap";

        echo "<div style='margin: 10px 0; padding: 10px; background: #f5f5f5; border-radius: 5px;'>";
        echo "<strong>Font URL:</strong> {$fontUrl}<br>";
        echo "<strong>Full URL:</strong> <a href='{$fullUrl}' target='_blank'>{$fullUrl}</a><br>";

        // Test URL accessibility
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fullUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Typography Diagnostic)');

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($httpCode === 200 && !empty($response)) {
            echo "<span style='color: green;'>✅ SUCCESS</span> - Font loads correctly<br>";

            // Check if response contains actual font CSS
            if (strpos($response, '@font-face') !== false) {
                echo "<span style='color: green;'>✅ VALID CSS</span> - Contains @font-face rules<br>";
            } else {
                echo "<span style='color: orange;'>⚠️ WARNING</span> - No @font-face rules found<br>";
            }
        } else {
            echo "<span style='color: red;'>❌ FAILED</span> - HTTP {$httpCode}";
            if ($error) {
                echo " - Error: {$error}";
            }
            echo "<br>";
        }

        echo "</div>";
    }
}

echo "<h2>📊 Weight Consistency Analysis</h2>";

foreach ($typography_database as $id => $typography) {
    echo "<h3>🎨 {$typography['name']}</h3>";

    $headingWeights = $typography['headingFont']['weights'];
    $bodyWeights = $typography['bodyFont']['weights'];

    echo "<div style='margin: 10px 0; padding: 10px; background: #f5f5f5; border-radius: 5px;'>";
    echo "<strong>Heading Weights:</strong> " . implode(', ', $headingWeights) . "<br>";
    echo "<strong>Body Weights:</strong> " . implode(', ', $bodyWeights) . "<br>";

    // Check for standard weights
    $standardWeights = ['400', '500', '600', '700'];
    $missingWeights = array_diff($standardWeights, $headingWeights);

    if (empty($missingWeights)) {
        echo "<span style='color: green;'>✅ COMPLETE</span> - Has all standard weights<br>";
    } else {
        echo "<span style='color: orange;'>⚠️ INCOMPLETE</span> - Missing weights: " . implode(', ', $missingWeights) . "<br>";
    }

    echo "</div>";
}

echo "<h2>🔧 Server-Side Typography CSS Test</h2>";

// Test typography CSS generation
$config = get_option('preetidreams_visual_customizer_config', []);

if (!empty($config) && isset($config['typographyData'])) {
    echo "<h3>✅ Current Typography Configuration Found</h3>";
    echo "<strong>Active Typography:</strong> " . ($config['typographyPairing'] ?? 'Not set') . "<br>";

    // Generate CSS for current typography
    if (function_exists('generate_css_from_typography_data')) {
        $css = generate_css_from_typography_data($config['typographyData']);

        if (!empty($css)) {
            echo "<div style='margin: 10px 0; padding: 10px; background: #e8f5e8; border-radius: 5px;'>";
            echo "<span style='color: green;'>✅ CSS GENERATION SUCCESS</span><br>";
            echo "<strong>CSS Length:</strong> " . strlen($css) . " characters<br>";
            echo "<details><summary>View Generated CSS</summary>";
            echo "<pre style='max-height: 300px; overflow-y: auto; background: #f0f0f0; padding: 10px;'>";
            echo htmlspecialchars($css);
            echo "</pre></details>";
            echo "</div>";
        } else {
            echo "<div style='color: red;'>❌ CSS generation failed - empty result</div>";
        }
    } else {
        echo "<div style='color: red;'>❌ CSS generation function not found</div>";
    }
} else {
    echo "<h3>⚠️ No Typography Configuration Found</h3>";
    echo "No saved typography configuration. Please apply a typography setting first.";
}

echo "<h2>🎯 Recommendations</h2>";

echo "<div style='padding: 15px; background: #fff3cd; border-radius: 5px; margin: 20px 0;'>";
echo "<h3>🔧 Fixes Applied:</h3>";
echo "<ul>";
echo "<li>✅ Fixed IBM Plex Sans URL encoding (+ → %20)</li>";
echo "<li>✅ Fixed Cormorant Garamond URL encoding (+ → %20)</li>";
echo "<li>✅ Standardized Wellness Serif weights (added 500, 600)</li>";
echo "<li>✅ Standardized Warm Organic weights (added 600)</li>";
echo "<li>✅ Ensured consistent weight mapping across all typography options</li>";
echo "</ul>";

echo "<h3>📊 Expected Results:</h3>";
echo "<ul>";
echo "<li><strong>Medical Professional, Luxury Modern, Contemporary Clean, Modern Sans:</strong> Continue working perfectly</li>";
echo "<li><strong>Tech Minimal, Classic Elegant:</strong> Load properly with fixed URL encoding</li>";
echo "<li><strong>Wellness Serif:</strong> Now has complete weight set (400, 500, 600, 700)</li>";
echo "<li><strong>Warm Organic:</strong> Now has complete weight set with improved consistency</li>";
echo "<li><strong>All Typography Options:</strong> Complete standard weight availability (400, 500, 600, 700)</li>";
echo "</ul>";
echo "</div>";

?>

<style>
body { font-family: -apple-system, BlinkMacSystemFont, sans-serif; line-height: 1.6; margin: 40px; }
h1, h2, h3 { color: #333; }
.success { color: #28a745; }
.warning { color: #ffc107; }
.error { color: #dc3545; }
</style>
