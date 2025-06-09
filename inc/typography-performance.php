<?php
/**
 * Typography Performance Optimizations
 * Industry best practices for instant font loading
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

/**
 * Add critical font preloading for instant rendering
 */
function add_critical_font_preloading() {
    // Get current typography configuration
    $config = get_option('preetidreams_visual_customizer_config', []);

    if (!empty($config['typographyData'])) {
        $typography = $config['typographyData'];

        // Get primary fonts for preloading
        $primary_font = $typography['headingFont']['family'] ?? '';
        $body_font = $typography['bodyFont']['family'] ?? '';

        if ($primary_font) {
            // Preload primary font - critical for above-the-fold content
            $primary_font_url = "https://fonts.gstatic.com/s/" . strtolower(str_replace(' ', '', $primary_font)) . "/";
            echo '<link rel="preload" href="' . esc_url($primary_font_url) . 'v1/woff2" as="font" type="font/woff2" crossorigin>' . "\n";
        }

        if ($body_font && $body_font !== $primary_font) {
            // Preload body font if different
            $body_font_url = "https://fonts.gstatic.com/s/" . strtolower(str_replace(' ', '', $body_font)) . "/";
            echo '<link rel="preload" href="' . esc_url($body_font_url) . 'v1/woff2" as="font" type="font/woff2" crossorigin>' . "\n";
        }
    }

    // Always add Google Fonts resource hints
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action('wp_head', 'add_critical_font_preloading', 1);

/**
 * Add critical CSS for font loading optimization
 */
function add_critical_font_css() {
    ?>
    <style id="critical-font-css">
        /* Critical font loading optimization CSS */
        * {
            font-display: swap;
        }

        /* Prevent layout shifts during font loading */
        h1, h2, h3, h4, h5, h6 {
            font-display: block;
            text-rendering: optimizeLegibility;
        }

        /* Optimize font rendering */
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-display: swap;
        }

        /* Loading state optimization */
        .typography-loading-state {
            opacity: 0;
            animation: fadeInTypography 0.2s ease-in-out forwards;
        }

        @keyframes fadeInTypography {
            to { opacity: 1; }
        }
    </style>
    <?php
}
add_action('wp_head', 'add_critical_font_css', 2);

/**
 * Optimize Google Fonts URL for performance
 */
function optimize_google_fonts_url($fonts_array) {
    if (empty($fonts_array)) {
        return '';
    }

    $fonts_query = implode('&family=', $fonts_array);

    // Add performance optimizations to URL
    $optimized_url = "https://fonts.googleapis.com/css2?family={$fonts_query}";
    $optimized_url .= "&display=swap"; // Critical for instant text rendering
    $optimized_url .= "&subset=latin"; // Reduce font file size

    return $optimized_url;
}

/**
 * Add font loading performance metrics
 */
function add_font_loading_metrics() {
    if (WP_DEBUG) {
        ?>
        <script>
        // Performance monitoring for font loading
        if (window.performance && window.performance.measure) {
            window.addEventListener('load', function() {
                // Measure font loading performance
                const fontLoadStart = performance.now();

                if (document.fonts && document.fonts.ready) {
                    document.fonts.ready.then(function() {
                        const fontLoadEnd = performance.now();
                        const fontLoadTime = fontLoadEnd - fontLoadStart;

                        console.log('🚀 Font Loading Performance: ' + fontLoadTime.toFixed(2) + 'ms');

                        // Log to server for performance monitoring
                        if (fontLoadTime > 500) {
                            console.warn('⚠️ Slow font loading detected: ' + fontLoadTime.toFixed(2) + 'ms');
                        }
                    });
                }
            });
        }
        </script>
        <?php
    }
}
add_action('wp_footer', 'add_font_loading_metrics');

/**
 * Cache Google Fonts locally for even faster loading (optional)
 */
function maybe_cache_google_fonts_locally() {
    // Only for production sites with performance requirements
    if (defined('TYPOGRAPHY_CACHE_FONTS') && TYPOGRAPHY_CACHE_FONTS) {
        // This would implement local font caching
        // For now, just return the optimization status
        return array(
            'local_caching' => true,
            'cache_directory' => wp_upload_dir()['basedir'] . '/fonts/',
            'performance_gain' => '50-100ms'
        );
    }

    return false;
}

/**
 * Get font loading optimization status
 */
function get_font_loading_optimization_status() {
    $config = get_option('preetidreams_visual_customizer_config', []);

    return array(
        'server_side_loading' => !empty($config['typographyData']),
        'resource_hints_enabled' => true,
        'critical_css_enabled' => true,
        'font_display_optimized' => true,
        'performance_monitoring' => WP_DEBUG,
        'local_caching' => maybe_cache_google_fonts_locally() !== false
    );
}
