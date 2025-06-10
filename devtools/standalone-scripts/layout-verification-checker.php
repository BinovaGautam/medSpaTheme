<?php
/**
 * Layout Verification Checker
 *
 * Standalone diagnostic script to verify layout is working correctly
 * Can be run via CLI or browser
 *
 * @package MedSpaTheme
 * @subpackage DevTools
 * @since 2.0.0
 * @author BUG-RESOLVER-001
 * @workflow BUG-RESOLUTION-WF-001
 */

// Security check for browser access
if (isset($_SERVER['HTTP_HOST']) && !isset($_GET['allow'])) {
    die('Access denied. Add ?allow=1 to run in browser.');
}

// Detect CLI vs browser environment
$is_cli = php_sapi_name() === 'cli';

if (!$is_cli) {
    echo "<html><head><title>Layout Verification Checker</title>";
    echo "<style>body{font-family: monospace; margin: 20px; line-height: 1.4;} .good{color: green;} .warning{color: orange;} .error{color: red;} .info{color: blue;}</style>";
    echo "</head><body><h1>🔧 Layout Verification Checker</h1>";
}

echo $is_cli ? "🔧 Layout Verification Checker\n" : "<h2>Checking Layout Issues...</h2>";
echo $is_cli ? "=" . str_repeat("=", 40) . "\n\n" : "<hr>";

// Define paths
$theme_dir = __DIR__ . '/../../';
$css_files_to_check = [
    'assets/css/customizer-enhancements.css',
    'assets/css/simple-visual-customizer.css',
    'assets/css/medical-spa-theme.css',
    'assets/css/components/footer.css'
];

// Test functions
function print_result($message, $status, $is_cli) {
    $symbols = ['good' => '✅', 'warning' => '⚠️', 'error' => '❌', 'info' => 'ℹ️'];
    $symbol = $symbols[$status] ?? 'ℹ️';

    if ($is_cli) {
        echo $symbol . " " . $message . "\n";
    } else {
        echo "<p class='{$status}'>{$symbol} {$message}</p>";
    }
}

function check_css_display_issues($theme_dir, $css_files, $is_cli) {
    print_result("Checking CSS display and layout issues...", 'info', $is_cli);

    $issues_found = 0;

    foreach ($css_files as $css_file) {
        $file_path = $theme_dir . $css_file;

        if (!file_exists($file_path)) {
            print_result("File not found: {$css_file}", 'warning', $is_cli);
            continue;
        }

        $content = file_get_contents($file_path);

        // Check for problematic display rules
        if (preg_match('/display:\s*block\s*!important/', $content)) {
            print_result("Found aggressive display:block !important in {$css_file}", 'warning', $is_cli);
            $issues_found++;
        }

        // Check for global * selectors
        if (preg_match('/^\s*\*\s*\{[^}]*display:/m', $content)) {
            print_result("Found global * selector with display property in {$css_file}", 'error', $is_cli);
            $issues_found++;
        }

        // Check for width: 100% on everything
        if (preg_match('/\*\s*\{[^}]*width:\s*100%/', $content)) {
            print_result("Found global width: 100% in {$css_file}", 'error', $is_cli);
            $issues_found++;
        }
    }

    if ($issues_found === 0) {
        print_result("No major CSS layout issues detected", 'good', $is_cli);
    }

    return $issues_found;
}

function check_visual_customizer_overlays($theme_dir, $is_cli) {
    print_result("Checking Visual Customizer overlay rules...", 'info', $is_cli);

    $customizer_css = $theme_dir . 'assets/css/customizer-enhancements.css';

    if (!file_exists($customizer_css)) {
        print_result("Customizer CSS file not found", 'error', $is_cli);
        return false;
    }

    $content = file_get_contents($customizer_css);

    // Check if emergency fix is still too broad
    if (preg_match('/\.simple-vc-sidebar\s*\{\s*display:\s*none\s*!important/', $content)) {
        print_result("Found overly broad sidebar hiding rule", 'error', $is_cli);
        return false;
    }

    // Check if specific selectors are used
    if (preg_match('/\.simple-vc-sidebar:not\(\.open\):not\(\.show\)/', $content)) {
        print_result("Found proper specific sidebar selectors", 'good', $is_cli);
        return true;
    }

    print_result("Visual Customizer rules need review", 'warning', $is_cli);
    return false;
}

function test_site_connectivity($is_cli) {
    print_result("Testing site connectivity...", 'info', $is_cli);

    $site_url = 'http://medspaa.local/treatments/';

    // Simple connectivity test
    $context = stream_context_create([
        'http' => [
            'timeout' => 10,
            'method' => 'HEAD'
        ]
    ]);

    $headers = @get_headers($site_url, 0, $context);

    if ($headers && strpos($headers[0], '200') !== false) {
        print_result("Site is accessible: {$site_url}", 'good', $is_cli);
        return true;
    } else {
        print_result("Site connectivity issue: {$site_url}", 'error', $is_cli);
        return false;
    }
}

function check_layout_critical_files($theme_dir, $is_cli) {
    print_result("Checking critical layout files...", 'info', $is_cli);

    $critical_files = [
        'footer.php',
        'assets/css/medical-spa-theme.css',
        'assets/css/components/footer.css',
        'style.css'
    ];

    $missing_files = 0;

    foreach ($critical_files as $file) {
        $file_path = $theme_dir . $file;

        if (file_exists($file_path)) {
            $size = number_format(filesize($file_path) / 1024, 1);
            print_result("Found {$file} ({$size} KB)", 'good', $is_cli);
        } else {
            print_result("Missing critical file: {$file}", 'error', $is_cli);
            $missing_files++;
        }
    }

    return $missing_files === 0;
}

// Run all checks
echo $is_cli ? "\n" : "<hr>";

$css_issues = check_css_display_issues($theme_dir, $css_files_to_check, $is_cli);
echo $is_cli ? "\n" : "<br>";

$overlay_ok = check_visual_customizer_overlays($theme_dir, $is_cli);
echo $is_cli ? "\n" : "<br>";

$site_ok = test_site_connectivity($is_cli);
echo $is_cli ? "\n" : "<br>";

$files_ok = check_layout_critical_files($theme_dir, $is_cli);
echo $is_cli ? "\n" : "<br>";

// Summary
echo $is_cli ? str_repeat("=", 50) . "\n" : "<hr>";
echo $is_cli ? "SUMMARY:\n" : "<h2>Summary:</h2>";

$total_issues = $css_issues;
if (!$overlay_ok) $total_issues++;
if (!$site_ok) $total_issues++;
if (!$files_ok) $total_issues++;

if ($total_issues === 0) {
    print_result("✅ ALL CHECKS PASSED - Layout should be working correctly!", 'good', $is_cli);
} else {
    print_result("⚠️ {$total_issues} issues found - Layout may have problems", 'warning', $is_cli);
}

echo $is_cli ? "\n" : "<br>";
print_result("Test completed at " . date('Y-m-d H:i:s'), 'info', $is_cli);

// Browser instructions
if (!$is_cli) {
    echo "<hr><h3>🌐 Test URLs:</h3>";
    echo "<ul>";
    echo "<li><a href='http://medspaa.local/' target='_blank'>Homepage</a></li>";
    echo "<li><a href='http://medspaa.local/treatments/' target='_blank'>Treatments Page</a></li>";
    echo "<li><a href='http://medspaa.local/contact/' target='_blank'>Contact Page</a></li>";
    echo "</ul>";
    echo "<p><em>If elements are still stacking vertically, check browser developer tools for CSS conflicts.</em></p>";
    echo "</body></html>";
} else {
    echo "\nTo test in browser, visit:\n";
    echo "- http://medspaa.local/\n";
    echo "- http://medspaa.local/treatments/\n";
    echo "- http://medspaa.local/contact/\n\n";
    echo "If elements are stacking vertically, check browser developer tools.\n";
}
?>
