<?php
/**
 * Typography Debug Test - Check if typography persistence is working
 *
 * This file helps debug typography save/load issues
 *
 * Access via: /wp-content/themes/medSpaTheme/test-typography-debug.php
 */

// Include WordPress
require_once('../../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied. Admin privileges required.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Typography Debug Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .debug-section { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .debug-title { color: #2271b1; border-bottom: 2px solid #2271b1; padding-bottom: 10px; margin-bottom: 15px; }
        .debug-data { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .debug-code { background: #2d3748; color: #e2e8f0; padding: 15px; border-radius: 5px; font-family: monospace; white-space: pre-wrap; }
        .status-good { color: #28a745; font-weight: bold; }
        .status-bad { color: #dc3545; font-weight: bold; }
        .status-warning { color: #ffc107; font-weight: bold; }
    </style>
</head>
<body>

<div class="debug-section">
    <h1 class="debug-title">🔍 Typography Debug Test</h1>
    <p>Testing typography persistence and database storage</p>
    <p><strong>Timestamp:</strong> <?php echo current_time('Y-m-d H:i:s'); ?></p>
</div>

<div class="debug-section">
    <h2 class="debug-title">📊 Database Status Check</h2>

    <?php
    // Check typography data in database
    $config = get_option('preetidreams_visual_customizer_config', []);
    $typography_data = get_option('preetidreams_typography_data', null);
    $active_typography = get_option('preetidreams_active_typography', null);

    echo "<h3>Main Configuration:</h3>";
    if (!empty($config)) {
        echo "<div class='status-good'>✅ Main config exists</div>";
        echo "<div class='debug-data'>";
        echo "<strong>Config keys:</strong> " . implode(', ', array_keys($config)) . "<br>";
        if (isset($config['activeTypography'])) {
            echo "<strong>Config Typography:</strong> " . $config['activeTypography'] . "<br>";
        }
        if (isset($config['typographyData'])) {
            echo "<strong>Config Typography Data:</strong> " . (is_array($config['typographyData']) ? 'Array with ' . count($config['typographyData']) . ' keys' : 'Not array') . "<br>";
        }
        echo "</div>";
    } else {
        echo "<div class='status-bad'>❌ No main config found</div>";
    }

    echo "<h3>Separate Typography Options:</h3>";
    if (!empty($active_typography)) {
        echo "<div class='status-good'>✅ Active typography: {$active_typography}</div>";
    } else {
        echo "<div class='status-bad'>❌ No active typography found</div>";
    }

    if (!empty($typography_data)) {
        echo "<div class='status-good'>✅ Typography data exists</div>";
        echo "<div class='debug-data'>";
        if (is_array($typography_data)) {
            echo "<strong>Typography data keys:</strong> " . implode(', ', array_keys($typography_data)) . "<br>";
            if (isset($typography_data['name'])) {
                echo "<strong>Typography name:</strong> " . $typography_data['name'] . "<br>";
            }
            if (isset($typography_data['headingFont']['family'])) {
                echo "<strong>Heading font:</strong> " . $typography_data['headingFont']['family'] . "<br>";
            }
            if (isset($typography_data['bodyFont']['family'])) {
                echo "<strong>Body font:</strong> " . $typography_data['bodyFont']['family'] . "<br>";
            }
        } else {
            echo "<strong>Typography data type:</strong> " . gettype($typography_data) . "<br>";
        }
        echo "</div>";
    } else {
        echo "<div class='status-bad'>❌ No typography data found</div>";
    }
    ?>
</div>

<div class="debug-section">
    <h2 class="debug-title">🎨 CSS Output Test</h2>

    <?php
    // Test CSS output function
    ob_start();

    // Mock the function logic
    $has_palette = !empty($config) && isset($config['paletteData']);

    $has_typography = false;
    if (!empty($typography_data) && !empty($active_typography)) {
        $has_typography = true;
        $typography_source = "separate options";
    } elseif (!empty($config) && isset($config['typographyData']) && isset($config['activeTypography'])) {
        $typography_data = $config['typographyData'];
        $active_typography = $config['activeTypography'];
        $has_typography = true;
        $typography_source = "main config";
    }

    echo "<h3>CSS Output Function Logic:</h3>";
    echo "<div class='debug-data'>";
    echo "<strong>Has palette:</strong> " . ($has_palette ? '<span class="status-good">Yes</span>' : '<span class="status-bad">No</span>') . "<br>";
    echo "<strong>Has typography:</strong> " . ($has_typography ? '<span class="status-good">Yes</span>' : '<span class="status-bad">No</span>') . "<br>";
    if ($has_typography) {
        echo "<strong>Typography source:</strong> {$typography_source}<br>";
    }
    echo "</div>";

    if ($has_typography) {
        echo "<h3>Typography CSS Generation Test:</h3>";

        // Test the CSS generation function
        include_once('../../inc/visual-customizer-simple.php');
        $generated_css = generate_css_from_typography_data($typography_data);

        if (!empty($generated_css)) {
            echo "<div class='status-good'>✅ Typography CSS generated successfully</div>";
            echo "<div class='debug-data'>";
            echo "<strong>CSS length:</strong> " . strlen($generated_css) . " characters<br>";
            echo "<strong>Preview (first 200 chars):</strong><br>";
            echo "<div class='debug-code'>" . htmlspecialchars(substr($generated_css, 0, 200)) . "...</div>";
            echo "</div>";
        } else {
            echo "<div class='status-bad'>❌ Typography CSS generation failed</div>";
            echo "<div class='debug-data'>";
            echo "<strong>Typography data structure:</strong><br>";
            echo "<div class='debug-code'>" . htmlspecialchars(json_encode($typography_data, JSON_PRETTY_PRINT)) . "</div>";
            echo "</div>";
        }
    }
    ?>
</div>

<div class="debug-section">
    <h2 class="debug-title">🔧 Fix Recommendations</h2>

    <?php
    $issues_found = [];
    $fixes_needed = [];

    if (empty($config)) {
        $issues_found[] = "No main configuration found";
        $fixes_needed[] = "Apply a typography setting from the Visual Customizer";
    }

    if (empty($active_typography) && empty($config['activeTypography'])) {
        $issues_found[] = "No active typography setting found";
        $fixes_needed[] = "Select and apply a typography from the customizer";
    }

    if (empty($typography_data) && empty($config['typographyData'])) {
        $issues_found[] = "No typography data found";
        $fixes_needed[] = "Typography data may not be saving correctly";
    }

    if (!$has_typography) {
        $issues_found[] = "Typography CSS won't be output";
        $fixes_needed[] = "Ensure typography is properly saved to database";
    }

    if (empty($issues_found)) {
        echo "<div class='status-good'>✅ No issues found! Typography should be working correctly.</div>";
    } else {
        echo "<h3>Issues Found:</h3>";
        echo "<ul>";
        foreach ($issues_found as $issue) {
            echo "<li class='status-bad'>❌ {$issue}</li>";
        }
        echo "</ul>";

        echo "<h3>Recommended Fixes:</h3>";
        echo "<ul>";
        foreach ($fixes_needed as $fix) {
            echo "<li class='status-warning'>🔧 {$fix}</li>";
        }
        echo "</ul>";
    }
    ?>
</div>

<div class="debug-section">
    <h2 class="debug-title">🚀 Quick Test</h2>
    <p>
        <a href="<?php echo home_url(); ?>" target="_blank" style="background: #2271b1; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            🌐 View Frontend (Check Typography)
        </a>

        <a href="<?php echo admin_url('admin-bar.php'); ?>" target="_blank" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px;">
            🎨 Open Visual Customizer
        </a>
    </p>
</div>

</body>
</html>
