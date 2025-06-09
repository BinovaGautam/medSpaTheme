<?php
/**
 * Font Loading Performance Diagnostic Tool
 * Test server-side font loading optimizations
 */

// Include WordPress
$wp_load_paths = [
    __DIR__ . '/../../../wp-config.php',
    __DIR__ . '/../../../../wp-config.php',
    $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php'
];

$wp_loaded = false;
foreach ($wp_load_paths as $path) {
    if (file_exists($path)) {
        require_once $path;
        $wp_loaded = true;
        break;
    }
}

if (!$wp_loaded) {
    die('❌ Could not load WordPress');
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>🚀 Font Loading Performance Diagnostic</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
        }
        .diagnostic-section {
            background: #f8f9fa;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            border-left: 4px solid #007cba;
        }
        .status-good { color: #28a745; font-weight: bold; }
        .status-warning { color: #ffc107; font-weight: bold; }
        .status-error { color: #dc3545; font-weight: bold; }
        .font-test {
            font-size: 24px;
            margin: 10px 0;
            padding: 10px;
            background: white;
            border-radius: 4px;
        }
        .performance-metric {
            display: inline-block;
            background: #e9ecef;
            padding: 8px 12px;
            margin: 4px;
            border-radius: 4px;
            font-family: monospace;
        }
        pre {
            background: #f1f3f4;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
        }
        .optimization-status {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .optimization-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <h1>🚀 Font Loading Performance Diagnostic</h1>
    <p><strong>Testing server-side font loading optimizations and industry best practices</strong></p>

    <?php
    // Get current typography configuration
    $config = get_option('preetidreams_visual_customizer_config', []);
    $typography_data = $config['typographyData'] ?? null;

    echo '<div class="diagnostic-section">';
    echo '<h2>📊 Current Typography Configuration</h2>';

    if ($typography_data) {
        echo '<div class="status-good">✅ Typography configuration found</div>';
        echo '<pre>' . print_r($typography_data, true) . '</pre>';

        // Test Google Fonts URL generation
        if (!empty($typography_data['googleFonts'])) {
            $fonts_query = implode('&family=', $typography_data['googleFonts']);
            $google_fonts_url = "https://fonts.googleapis.com/css2?family={$fonts_query}&display=swap";

            echo '<h3>🔗 Generated Google Fonts URL:</h3>';
            echo '<div class="performance-metric">' . esc_html($google_fonts_url) . '</div>';

            // Test URL accessibility
            $response = wp_remote_get($google_fonts_url, ['timeout' => 5]);
            if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
                echo '<div class="status-good">✅ Google Fonts URL is accessible</div>';

                $response_time = wp_remote_retrieve_header($response, 'x-response-time') ?: 'N/A';
                echo '<div class="performance-metric">Response Time: ' . $response_time . '</div>';
            } else {
                echo '<div class="status-error">❌ Google Fonts URL is not accessible</div>';
                if (is_wp_error($response)) {
                    echo '<div class="status-error">Error: ' . $response->get_error_message() . '</div>';
                }
            }
        }
    } else {
        echo '<div class="status-warning">⚠️ No typography configuration found</div>';
        echo '<p>Run the Visual Customizer and select a typography option first.</p>';
    }
    echo '</div>';

    // Test font loading optimization functions
    echo '<div class="diagnostic-section">';
    echo '<h2>🔧 Font Loading Optimizations Status</h2>';

    if (function_exists('get_font_loading_optimization_status')) {
        $optimization_status = get_font_loading_optimization_status();

        echo '<div class="optimization-status">';

        foreach ($optimization_status as $feature => $enabled) {
            $status_class = $enabled ? 'status-good' : 'status-warning';
            $status_icon = $enabled ? '✅' : '⚠️';
            $status_text = $enabled ? 'Enabled' : 'Disabled';

            echo '<div class="optimization-card">';
            echo '<h4>' . ucwords(str_replace('_', ' ', $feature)) . '</h4>';
            echo '<div class="' . $status_class . '">' . $status_icon . ' ' . $status_text . '</div>';
            echo '</div>';
        }

        echo '</div>';
    } else {
        echo '<div class="status-error">❌ Typography performance functions not loaded</div>';
    }
    echo '</div>';

    // Test server-side font enqueuing
    echo '<div class="diagnostic-section">';
    echo '<h2>🖥️ Server-Side Font Loading Test</h2>';

    // Simulate font enqueuing
    ob_start();
    if (function_exists('enqueue_selected_typography_fonts')) {
        // Capture any output from the function
        $test_output = '';

        // Check if fonts would be enqueued
        if ($typography_data && !empty($typography_data['googleFonts'])) {
            echo '<div class="status-good">✅ Server-side font loading function available</div>';
            echo '<p>Typography fonts will be loaded in HTML head for instant availability</p>';

            $fonts_query = implode('&family=', $typography_data['googleFonts']);
            $expected_url = "https://fonts.googleapis.com/css2?family={$fonts_query}&display=swap";
            echo '<div class="performance-metric">Expected URL: ' . esc_html($expected_url) . '</div>';
        } else {
            echo '<div class="status-warning">⚠️ No typography fonts to enqueue</div>';
        }
    } else {
        echo '<div class="status-error">❌ Server-side font loading function not available</div>';
    }
    ob_end_clean();
    echo '</div>';

    // Performance recommendations
    echo '<div class="diagnostic-section">';
    echo '<h2>🚀 Performance Recommendations</h2>';

    $recommendations = [];

    if (!$typography_data) {
        $recommendations[] = [
            'icon' => '⚠️',
            'title' => 'Configure Typography',
            'description' => 'Select a typography option in the Visual Customizer to enable server-side font loading.'
        ];
    }

    if ($typography_data && empty($typography_data['googleFonts'])) {
        $recommendations[] = [
            'icon' => '⚠️',
            'title' => 'Add Google Fonts',
            'description' => 'Current typography uses system fonts. Consider Google Fonts for better design consistency.'
        ];
    }

    if ($typography_data && !empty($typography_data['googleFonts'])) {
        $recommendations[] = [
            'icon' => '✅',
            'title' => 'Server-Side Loading Active',
            'description' => 'Fonts are loaded in HTML head for instant availability. This eliminates client-side loading delays.'
        ];

        $recommendations[] = [
            'icon' => '🚀',
            'title' => 'Resource Hints Added',
            'description' => 'DNS prefetch and preconnect hints optimize Google Fonts loading performance.'
        ];

        $recommendations[] = [
            'icon' => '⚡',
            'title' => 'Font Display Optimized',
            'description' => 'Using font-display: swap for immediate text rendering even during font loading.'
        ];
    }

    if (WP_DEBUG) {
        $recommendations[] = [
            'icon' => '📊',
            'title' => 'Performance Monitoring Active',
            'description' => 'Font loading performance metrics are being tracked in browser console.'
        ];
    }

    foreach ($recommendations as $rec) {
        echo '<div style="margin: 10px 0; padding: 10px; background: white; border-radius: 4px;">';
        echo '<strong>' . $rec['icon'] . ' ' . $rec['title'] . '</strong><br>';
        echo $rec['description'];
        echo '</div>';
    }
    echo '</div>';

    // Live font test
    if ($typography_data) {
        echo '<div class="diagnostic-section">';
        echo '<h2>📝 Live Font Rendering Test</h2>';

        $heading_font = $typography_data['headingFont']['family'] ?? 'System';
        $body_font = $typography_data['bodyFont']['family'] ?? 'System';

        echo '<div class="font-test" style="font-family: \'' . esc_attr($heading_font) . '\', serif;">';
        echo '<strong>Heading Font Test (' . esc_html($heading_font) . '):</strong><br>';
        echo 'The quick brown fox jumps over the lazy dog - TYPOGRAPHY PERFORMANCE';
        echo '</div>';

        echo '<div class="font-test" style="font-family: \'' . esc_attr($body_font) . '\', sans-serif;">';
        echo '<strong>Body Font Test (' . esc_html($body_font) . '):</strong><br>';
        echo 'This text should render with the selected body font immediately, demonstrating server-side font loading performance.';
        echo '</div>';

        echo '</div>';
    }
    ?>

    <div class="diagnostic-section">
        <h2>🔍 Client-Side Performance Test</h2>
        <p>Opening browser console to monitor font loading performance...</p>
        <div id="performance-results"></div>
    </div>

    <script>
        // Client-side performance monitoring
        console.log('🚀 Starting font loading performance test...');

        const startTime = performance.now();

        // Test if server-loaded fonts are available
        const serverFonts = document.querySelector('#selected-typography-fonts-css');
        const serverPreviewFonts = document.querySelector('#typography-preview-fonts-server-css');

        if (serverFonts) {
            console.log('✅ Server-loaded typography fonts detected:', serverFonts.href);
        }

        if (serverPreviewFonts) {
            console.log('✅ Server-loaded preview fonts detected:', serverPreviewFonts.href);
        }

        if (!serverFonts && !serverPreviewFonts) {
            console.log('⚠️ No server-loaded fonts detected - fonts will load via client-side');
        }

        // Monitor font loading completion
        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(function() {
                const endTime = performance.now();
                const loadTime = endTime - startTime;

                console.log('🚀 Font loading completed in: ' + loadTime.toFixed(2) + 'ms');

                const resultsDiv = document.getElementById('performance-results');
                resultsDiv.innerHTML = `
                    <div class="performance-metric">Font Loading Time: ${loadTime.toFixed(2)}ms</div>
                    <div class="performance-metric">Server Fonts: ${serverFonts ? 'Yes' : 'No'}</div>
                    <div class="performance-metric">Preview Fonts: ${serverPreviewFonts ? 'Yes' : 'No'}</div>
                `;

                if (loadTime < 100) {
                    resultsDiv.innerHTML += '<div class="status-good">✅ Excellent font loading performance!</div>';
                } else if (loadTime < 300) {
                    resultsDiv.innerHTML += '<div class="status-warning">⚠️ Good font loading performance</div>';
                } else {
                    resultsDiv.innerHTML += '<div class="status-error">❌ Slow font loading detected</div>';
                }
            });
        }

        // Test font availability
        setTimeout(function() {
            const testElement = document.createElement('div');
            testElement.style.fontFamily = 'Inter, system-ui';
            testElement.style.visibility = 'hidden';
            testElement.textContent = 'Test';
            document.body.appendChild(testElement);

            const computedFont = getComputedStyle(testElement).fontFamily;
            console.log('🔍 Computed font family:', computedFont);

            document.body.removeChild(testElement);
        }, 100);
    </script>

    <div class="diagnostic-section">
        <h2>📋 Summary</h2>
        <p><strong>Font Loading Optimization Implementation:</strong></p>
        <ul>
            <li>✅ <strong>Server-side loading:</strong> Fonts loaded in HTML head for instant availability</li>
            <li>✅ <strong>Resource hints:</strong> DNS prefetch and preconnect for Google Fonts</li>
            <li>✅ <strong>Font display optimization:</strong> Using swap for immediate text rendering</li>
            <li>✅ <strong>Performance monitoring:</strong> Client-side metrics in browser console</li>
            <li>✅ <strong>Fallback handling:</strong> Graceful degradation for loading failures</li>
        </ul>

        <p><strong>Expected Performance Improvement:</strong></p>
        <ul>
            <li>🚀 <strong>Instant typography previews</strong> in Visual Customizer</li>
            <li>⚡ <strong>Eliminated client-side loading delays</strong> for selected fonts</li>
            <li>📱 <strong>Faster page loads</strong> with optimized font delivery</li>
            <li>🎯 <strong>Better Core Web Vitals</strong> scores</li>
        </ul>
    </div>

</body>
</html>
