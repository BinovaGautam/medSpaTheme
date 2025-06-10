<?php
/**
 * Footer CSS Test Script
 *
 * Tests if footer CSS is properly applied and styled
 *
 * @package MedSpaTheme
 * @subpackage DevTools
 * @since 2.0.0
 */

// Security check for browser access
if (isset($_SERVER['HTTP_HOST']) && !isset($_GET['allow'])) {
    die('Access denied. Add ?allow=1 to run in browser.');
}

// Detect CLI vs browser environment
$is_cli = php_sapi_name() === 'cli';

if (!$is_cli) {
    echo "<html><head><title>Footer CSS Test</title>";
    echo "<style>body{font-family: monospace; margin: 20px; line-height: 1.4;} .good{color: green;} .warning{color: orange;} .error{color: red;} .info{color: blue;}</style>";
    echo "</head><body><h1>🎨 Footer CSS Test</h1>";
}

echo $is_cli ? "🎨 Footer CSS Test\n" : "<h2>Testing Footer CSS...</h2>";
echo $is_cli ? "=" . str_repeat("=", 30) . "\n\n" : "<hr>";

function print_result($message, $status, $is_cli) {
    $symbols = ['good' => '✅', 'warning' => '⚠️', 'error' => '❌', 'info' => 'ℹ️'];
    $symbol = $symbols[$status] ?? 'ℹ️';

    if ($is_cli) {
        echo $symbol . " " . $message . "\n";
    } else {
        echo "<p class='{$status}'>{$symbol} {$message}</p>";
    }
}

// Test 1: Check if footer CSS file exists and is accessible
print_result("Testing footer CSS file accessibility...", 'info', $is_cli);

$footer_css_url = 'http://medspaa.local/wp-content/themes/medSpaTheme/assets/css/components/footer.css';
$css_content = @file_get_contents($footer_css_url);

if ($css_content) {
    $size = strlen($css_content) / 1024;
    print_result("Footer CSS file accessible (" . round($size, 1) . " KB)", 'good', $is_cli);

    // Check for key CSS rules
    if (strpos($css_content, '.footer-component') !== false) {
        print_result("Found .footer-component CSS class", 'good', $is_cli);
    } else {
        print_result("Missing .footer-component CSS class", 'error', $is_cli);
    }

    if (strpos($css_content, '.footer-hero-cta') !== false) {
        print_result("Found .footer-hero-cta CSS class", 'good', $is_cli);
    } else {
        print_result("Missing .footer-hero-cta CSS class", 'warning', $is_cli);
    }

    if (strpos($css_content, 'display: grid') !== false || strpos($css_content, 'display: flex') !== false) {
        print_result("Found modern layout CSS (grid/flex)", 'good', $is_cli);
    } else {
        print_result("Missing modern layout CSS", 'error', $is_cli);
    }

} else {
    print_result("Footer CSS file not accessible", 'error', $is_cli);
}

echo $is_cli ? "\n" : "<br>";

// Test 2: Check if CSS is being loaded on the page
print_result("Testing if footer CSS loads on treatments page...", 'info', $is_cli);

$page_source = @file_get_contents('http://medspaa.local/treatments/');
if ($page_source) {
    if (strpos($page_source, 'footer-component-styles-css') !== false) {
        print_result("Footer CSS link found in page source", 'good', $is_cli);
    } else {
        print_result("Footer CSS link NOT found in page source", 'error', $is_cli);
    }

    if (strpos($page_source, 'footer-component footer-modern') !== false) {
        print_result("Footer HTML with correct classes found", 'good', $is_cli);
    } else {
        print_result("Footer HTML with correct classes NOT found", 'error', $is_cli);
    }

} else {
    print_result("Could not load treatments page", 'error', $is_cli);
}

echo $is_cli ? "\n" : "<br>";

// Test 3: Sample HTML to demonstrate expected footer styling
if (!$is_cli) {
    echo "<hr><h3>Expected Footer Styling Demo:</h3>";
    echo "<div style='border: 2px solid #ccc; padding: 20px; margin: 20px 0; background: #f9f9f9;'>";
    echo "<p><strong>Your footer should have:</strong></p>";
    echo "<ul>";
    echo "<li>🎨 Modern card-based layout</li>";
    echo "<li>🗺️ Full-width Google Maps integration</li>";
    echo "<li>🎯 Hero CTA section with background</li>";
    echo "<li>📱 4-column responsive grid</li>";
    echo "<li>✨ Professional styling with colors</li>";
    echo "</ul>";
    echo "<p><em>If you see plain text instead, try clearing your browser cache or force-refresh (Ctrl+F5 / Cmd+Shift+R)</em></p>";
    echo "</div>";

    echo "<h3>🔧 Manual Debug Steps:</h3>";
    echo "<ol>";
    echo "<li>Press F12 to open Developer Tools</li>";
    echo "<li>Go to Network tab and refresh page</li>";
    echo "<li>Look for footer.css in the loaded files</li>";
    echo "<li>Check if CSS rules are being applied to footer elements</li>";
    echo "<li>Clear browser cache completely</li>";
    echo "</ol>";
    echo "</body></html>";
} else {
    echo "\nExpected footer styling:\n";
    echo "- Modern card-based layout\n";
    echo "- Full-width Google Maps integration\n";
    echo "- Hero CTA section with background\n";
    echo "- 4-column responsive grid\n";
    echo "- Professional styling with colors\n\n";
    echo "If you see plain text, try clearing browser cache.\n";
}
?>
