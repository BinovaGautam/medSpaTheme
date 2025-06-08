<?php
/**
 * Pure Tokenization Test
 * Test if the CSS output function is working with pure semantic tokens
 */

// Proper WordPress inclusion with error handling
$wp_paths = [
    // Try different potential WordPress root paths
    dirname(dirname(dirname(dirname(dirname(__FILE__))))), // 5 levels up
    dirname(dirname(dirname(dirname(__FILE__)))) . '/../../../', // Alternative path
    '/Users/ysingh/DevKinsta/public/medspaa', // Absolute path
    $_SERVER['DOCUMENT_ROOT'], // Document root
];

$wp_loaded = false;
foreach ($wp_paths as $wp_path) {
    $wp_config_path = $wp_path . '/wp-config.php';
    $wp_load_path = $wp_path . '/wp-load.php';

    if (file_exists($wp_config_path) && file_exists($wp_load_path)) {
        try {
            require_once($wp_load_path);
            $wp_loaded = true;
            break;
        } catch (Exception $e) {
            // Continue to next path
            continue;
        }
    }
}

if (!$wp_loaded) {
    die('❌ Could not load WordPress. Please access this file through: http://medspaa.local/?pure_tokenization_test=1');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Pure Tokenization Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f0f0f0; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ccc; background: #fff; border-radius: 8px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .debug-output { background: #000; color: #0f0; padding: 10px; font-family: monospace; font-size: 12px; max-height: 300px; overflow-y: auto; border-radius: 4px; }
    </style>
</head>
<body>

<h1>🎯 Pure Tokenization Test</h1>

<?php if ($wp_loaded): ?>
<div class="test-section">
    <h3>✅ WordPress Status</h3>
    <p><strong>WordPress Loaded:</strong> ✅ SUCCESS</p>
    <p><strong>WordPress Version:</strong> <?php echo get_bloginfo('version'); ?></p>
    <p><strong>Theme:</strong> <?php echo get_stylesheet(); ?></p>
</div>
<?php endif; ?>

<div class="test-section">
    <h3>📊 Configuration Status</h3>
    <?php
    try {
        $config = get_option('preetidreams_visual_customizer_config', []);
        echo '<p><strong>Config Exists:</strong> ' . (!empty($config) ? '✅ YES' : '❌ NO') . '</p>';
        echo '<p><strong>Active Palette:</strong> ' . ($config['activePalette'] ?? 'NOT SET') . '</p>';
        echo '<p><strong>Palette Data:</strong> ' . (isset($config['paletteData']) ? '✅ PRESENT' : '❌ MISSING') . '</p>';

        if (!empty($config)) {
            echo '<p><strong>Config Keys:</strong> ' . implode(', ', array_keys($config)) . '</p>';
        }
    } catch (Exception $e) {
        echo '<p class="error">❌ Error reading config: ' . esc_html($e->getMessage()) . '</p>';
    }
    ?>
</div>

<div class="test-section">
    <h3>🔧 Function Testing</h3>
    <?php
    try {
        echo '<p><strong>Function Exists:</strong> ' . (function_exists('generate_css_from_palette_data') ? '✅ YES' : '❌ NO') . '</p>';
        echo '<p><strong>Output Function Exists:</strong> ' . (function_exists('output_visual_customizer_global_css') ? '✅ YES' : '❌ NO') . '</p>';

        if (!empty($config['paletteData'])) {
            $test_css = generate_css_from_palette_data($config['paletteData']);
            echo '<p><strong>CSS Generation:</strong> ' . (!empty($test_css) ? '✅ SUCCESS' : '❌ FAILED') . '</p>';
            echo '<p><strong>CSS Length:</strong> ' . strlen($test_css) . ' characters</p>';

            // Check for legacy variables (should be NONE)
            $legacy_check = [
                'navy' => strpos($test_css, 'navy') !== false,
                'teal' => strpos($test_css, 'teal') !== false,
                'peach' => strpos($test_css, 'peach') !== false
            ];

            echo '<p><strong>🚫 Legacy Variables Check (should all be NOT FOUND):</strong></p>';
            foreach ($legacy_check as $term => $found) {
                echo '<p style="margin-left: 20px;">- ' . $term . ': ' . ($found ? '❌ FOUND (BAD)' : '✅ NOT FOUND (GOOD)') . '</p>';
            }

            // Check for semantic tokens
            $semantic_check = [
                'component-bg-color-primary' => strpos($test_css, '--component-bg-color-primary') !== false,
                'color-primary' => strpos($test_css, '--color-primary:') !== false,
                'palette-primary' => strpos($test_css, '--palette-primary') !== false
            ];

            echo '<p><strong>✅ Semantic Tokens Check (should all be FOUND):</strong></p>';
            foreach ($semantic_check as $token => $found) {
                echo '<p style="margin-left: 20px;">- ' . $token . ': ' . ($found ? '✅ FOUND (GOOD)' : '❌ NOT FOUND (BAD)') . '</p>';
            }

        } else {
            echo '<p class="error">❌ Cannot test - no palette data</p>';
        }
    } catch (Exception $e) {
        echo '<p class="error">❌ Error in function testing: ' . esc_html($e->getMessage()) . '</p>';
    }
    ?>
</div>

<div class="test-section">
    <h3>🎨 Live CSS Output Test</h3>
    <?php
    try {
        echo '<p><strong>CSS Output Function Result:</strong></p>';

        // Capture the CSS output
        ob_start();
        if (function_exists('output_visual_customizer_global_css')) {
            output_visual_customizer_global_css();
        } else {
            echo "<!-- Function does not exist -->";
        }
        $css_output = ob_get_clean();

        if (!empty($css_output)) {
            echo '<div class="debug-output">' . esc_html($css_output) . '</div>';

            // Quick analysis
            $contains_style_tag = strpos($css_output, '<style') !== false;
            $contains_css_vars = strpos($css_output, '--component-') !== false || strpos($css_output, '--color-') !== false;

            echo '<p><strong>Analysis:</strong></p>';
            echo '<p style="margin-left: 20px;">- Contains &lt;style&gt; tag: ' . ($contains_style_tag ? '✅ YES' : '❌ NO') . '</p>';
            echo '<p style="margin-left: 20px;">- Contains CSS variables: ' . ($contains_css_vars ? '✅ YES' : '❌ NO') . '</p>';

        } else {
            echo '<div class="debug-output"><span style="color: #f00;">❌ NO CSS OUTPUT GENERATED</span></div>';
        }
    } catch (Exception $e) {
        echo '<p class="error">❌ Error in CSS output test: ' . esc_html($e->getMessage()) . '</p>';
    }
    ?>
</div>

<div class="test-section">
    <h3>🔍 Additional Debug Info</h3>
    <p><strong>Current User ID:</strong> <?php echo get_current_user_id(); ?></p>
    <p><strong>Can Manage Options:</strong> <?php echo current_user_can('manage_options') ? '✅ YES' : '❌ NO'; ?></p>
    <p><strong>File Path:</strong> <?php echo __FILE__; ?></p>
    <p><strong>WordPress Path:</strong> <?php echo ABSPATH; ?></p>
</div>

</body>
</html>