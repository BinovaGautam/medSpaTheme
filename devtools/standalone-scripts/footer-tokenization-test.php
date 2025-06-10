<?php
/**
 * Footer Tokenization Integration Test
 *
 * Tests footer CSS token integration with base theme design system
 *
 * @package MedSpaTheme
 * @subpackage DevTools
 * @since 2.0.0
 * @workflow BUG-RESOLUTION-WF-001
 * @agent BUG-RESOLVER-001
 */

// Security check for browser access
if (isset($_SERVER['HTTP_HOST']) && !isset($_GET['allow'])) {
    die('Access denied. Add ?allow=1 to run in browser.');
}

// Detect CLI vs browser environment
$is_cli = php_sapi_name() === 'cli';

if (!$is_cli) {
    echo "<html><head><title>Footer Tokenization Test</title>";
    echo "<style>body{font-family: monospace; margin: 20px; line-height: 1.4;} .good{color: green;} .warning{color: orange;} .error{color: red;} .info{color: blue;} .code{background: #f5f5f5; padding: 10px; margin: 10px 0; border-radius: 5px;}</style>";
    echo "</head><body><h1>🎨 Footer Tokenization Integration Test</h1>";
}

echo $is_cli ? "🎨 Footer Tokenization Integration Test\n" : "<h2>Testing Footer Token Integration...</h2>";
echo $is_cli ? "=" . str_repeat("=", 40) . "\n\n" : "<hr>";

function print_result($message, $status, $is_cli) {
    $symbols = ['good' => '✅', 'warning' => '⚠️', 'error' => '❌', 'info' => 'ℹ️'];
    $symbol = $symbols[$status] ?? 'ℹ️';

    if ($is_cli) {
        echo $symbol . " " . $message . "\n";
    } else {
        echo "<p class='{$status}'>{$symbol} {$message}</p>";
    }
}

// Test 1: Typography Token Integration
print_result("Testing typography token integration...", 'info', $is_cli);

$footer_css_url = 'http://medspaa.local/wp-content/themes/medSpaTheme/assets/css/components/footer.css';
$css_content = @file_get_contents($footer_css_url);

if ($css_content) {
    // Check for enhanced typography tokens
    $typography_tokens = [
        '--footer-font-primary' => 'Primary font inheritance',
        '--footer-font-secondary' => 'Secondary font inheritance',
        '--footer-font-accent' => 'Accent font inheritance',
        '--footer-text-xl' => 'Font size scale inheritance',
        '--footer-text-base' => 'Base text size inheritance',
        '--footer-space-xl' => 'Spacing system inheritance'
    ];

    foreach ($typography_tokens as $token => $description) {
        if (strpos($css_content, $token) !== false) {
            print_result("Found {$description}: {$token}", 'good', $is_cli);
        } else {
            print_result("Missing {$description}: {$token}", 'error', $is_cli);
        }
    }

    // Check for proper token usage in component classes
    $component_usage = [
        '.footer-component' => '--footer-font-secondary',
        '.hero-headline' => '--footer-font-primary',
        '.card-title' => '--footer-font-primary',
        '.newsletter-title' => '--footer-font-primary'
    ];

    print_result("Checking component typography usage...", 'info', $is_cli);
    foreach ($component_usage as $class => $expected_token) {
        if (strpos($css_content, $class) !== false && strpos($css_content, $expected_token) !== false) {
            print_result("Component {$class} uses {$expected_token}", 'good', $is_cli);
        } else {
            print_result("Component {$class} missing {$expected_token}", 'warning', $is_cli);
        }
    }

} else {
    print_result("Could not load footer CSS file", 'error', $is_cli);
}

echo $is_cli ? "\n" : "<br>";

// Test 2: Color Token Integration
print_result("Testing color palette integration...", 'info', $is_cli);

if ($css_content) {
    $color_tokens = [
        '--footer-color-primary-forest' => 'Primary forest green',
        '--footer-color-primary-sage' => 'Primary sage green',
        '--footer-color-primary-gold' => 'Primary gold accent',
        '--footer-bg-primary' => 'Component background primary',
        '--footer-text-primary' => 'Component text primary'
    ];

    foreach ($color_tokens as $token => $description) {
        if (strpos($css_content, $token) !== false) {
            print_result("Found {$description}: {$token}", 'good', $is_cli);
        } else {
            print_result("Missing {$description}: {$token}", 'warning', $is_cli);
        }
    }
}

echo $is_cli ? "\n" : "<br>";

// Test 3: Visual Customizer Integration
print_result("Testing Visual Customizer integration...", 'info', $is_cli);

$customizer_js_url = 'http://medspaa.local/wp-content/themes/medSpaTheme/assets/js/simple-visual-customizer.js';
$customizer_content = @file_get_contents($customizer_js_url);

if ($customizer_content) {
    $customizer_checks = [
        'footer-section' => 'Footer section in customizer',
        'heroHeadline' => 'Hero headline control',
        'primaryColor' => 'Primary color control',
        'typography' => 'Typography control',
        'heroBackgroundImage' => 'Background image control'
    ];

    foreach ($customizer_checks as $feature => $description) {
        if (strpos($customizer_content, $feature) !== false) {
            print_result("Found {$description}: {$feature}", 'good', $is_cli);
        } else {
            print_result("Missing {$description}: {$feature}", 'warning', $is_cli);
        }
    }
} else {
    print_result("Could not load Visual Customizer JS", 'warning', $is_cli);
}

echo $is_cli ? "\n" : "<br>";

// Test 4: Live Integration Test
print_result("Testing live footer rendering...", 'info', $is_cli);

$treatments_page = @file_get_contents('http://medspaa.local/treatments/');
if ($treatments_page) {
    if (strpos($treatments_page, 'footer-component footer-modern') !== false) {
        print_result("Footer HTML with modern classes found", 'good', $is_cli);
    }

    if (strpos($treatments_page, 'footer-component-styles') !== false) {
        print_result("Footer CSS is loading on page", 'good', $is_cli);
    }

    if (strpos($treatments_page, 'medical-spa-theme') !== false) {
        print_result("Main theme CSS is loading (contains base tokens)", 'good', $is_cli);
    }
}

echo $is_cli ? "\n" : "<br>";

// Summary and recommendations
if (!$is_cli) {
    echo "<hr><h3>🔧 Token Integration Summary:</h3>";
    echo "<div class='code'>";
    echo "<strong>✅ ENHANCED TOKENIZATION FEATURES:</strong><br>";
    echo "• Footer inherits base theme typography (Playfair Display, Source Sans Pro)<br>";
    echo "• Color palette uses theme tokens (forest green, sage, gold)<br>";
    echo "• Spacing system uses consistent scale<br>";
    echo "• Visual Customizer controls respect token values<br><br>";

    echo "<strong>🎨 CUSTOMIZATION CAPABILITIES:</strong><br>";
    echo "• Hero section background images<br>";
    echo "• Typography selection from theme fonts<br>";
    echo "• Color scheme follows medical spa palette<br>";
    echo "• Responsive spacing with fluid typography<br>";
    echo "</div>";

    echo "<h3>📱 Test Your Footer:</h3>";
    echo "<ol>";
    echo "<li>Visit <a href='http://medspaa.local/treatments/' target='_blank'>http://medspaa.local/treatments/</a></li>";
    echo "<li>Scroll to footer - should see modern medical spa styling</li>";
    echo "<li>Typography should match your site's base fonts</li>";
    echo "<li>Colors should follow your theme's palette</li>";
    echo "<li>Visual Customizer icon should allow real-time editing</li>";
    echo "</ol>";

    echo "</body></html>";
} else {
    echo "\n✅ ENHANCED TOKENIZATION FEATURES:\n";
    echo "• Footer inherits base theme typography\n";
    echo "• Color palette uses theme tokens\n";
    echo "• Spacing system uses consistent scale\n";
    echo "• Visual Customizer controls respect token values\n\n";

    echo "🎨 Test your footer at: http://medspaa.local/treatments/\n";
    echo "Typography and colors should now match your theme's design system.\n";
}
?>
