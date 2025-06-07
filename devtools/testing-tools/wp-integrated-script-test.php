<?php
/**
 * WordPress Integrated Script Test - PVC-009 Environment Validation
 *
 * This runs within WordPress context to verify script loading
 */

// Prevent direct access and load WordPress correctly
if (!defined('ABSPATH')) {
    // Use correct paths for web server context
    $wp_config_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
    $wp_load_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

    if (file_exists($wp_config_path)) {
        require_once($wp_config_path);
    }
    if (file_exists($wp_load_path)) {
        require_once($wp_load_path);
    }
}

// Force load Visual Customizer for this test
if (function_exists('simple_visual_customizer_enqueue_assets')) {
    simple_visual_customizer_enqueue_assets();
}

get_header(); // This will load WordPress head and enqueued scripts
?>

<div style="max-width: 800px; margin: 20px auto; padding: 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <h1>🔧 WordPress Integrated Script Test - PVC-009</h1>
        <p><strong>Environment:</strong> WordPress <?php echo get_bloginfo('version'); ?> | Theme: <?php echo wp_get_theme()->get('Name'); ?></p>
        <p><strong>Context:</strong> Running within WordPress with wp_head() and proper script loading</p>

        <div id="environment-check" style="margin: 20px 0; padding: 15px; background: #f9f9f9; border-radius: 4px;">
            <h3>Environment Status</h3>
            <div id="env-status">Checking WordPress environment...</div>
        </div>

        <div id="script-loading-test" style="margin: 20px 0; padding: 15px; background: #f9f9f9; border-radius: 4px;">
            <h3>Script Loading Test</h3>
            <div id="script-status" style="background: #2d2d2d; color: #f8f8f2; padding: 15px; border-radius: 4px; font-family: monospace; white-space: pre-wrap;">
Loading scripts in WordPress context...
            </div>
        </div>

        <div id="manual-tests" style="margin: 20px 0;">
            <h3>Manual Tests</h3>
            <button onclick="testWordPressIntegration()" style="background: #007cba; color: white; border: none; padding: 12px 24px; border-radius: 4px; cursor: pointer; margin: 5px;">Test WordPress Integration</button>
            <button onclick="testScriptURLs()" style="background: #007cba; color: white; border: none; padding: 12px 24px; border-radius: 4px; cursor: pointer; margin: 5px;">Test Script URLs</button>
            <button onclick="manuallyLoadScripts()" style="background: #dc3545; color: white; border: none; padding: 12px 24px; border-radius: 4px; cursor: pointer; margin: 5px;">Force Load Scripts</button>
        </div>

        <div id="test-results" style="margin: 20px 0; padding: 15px; background: #f9f9f9; border-radius: 4px;">
            <h3>Test Results</h3>
            <div id="results-output" style="background: #2d2d2d; color: #f8f8f2; padding: 15px; border-radius: 4px; font-family: monospace; white-space: pre-wrap;">
Click test buttons to run diagnostics...
            </div>
        </div>

        <div id="visual-test" style="margin: 20px 0; padding: 20px; background: var(--color-primary-navy, #2c3e50); color: var(--color-neutral-white, #ffffff); border-radius: 8px; transition: all 0.3s ease;">
            <h3>Visual CSS Test Element</h3>
            <p>This element uses CSS variables. If Visual Customizer works, clicking palette buttons should change colors instantly.</p>

            <div style="margin: 15px 0;">
                <button onclick="applyPalette('luxury-spa')" style="background: var(--gradient-accent, linear-gradient(135deg, #f39c12 0%, #e67e22 100%)); color: white; border: none; padding: 10px 20px; border-radius: 4px; margin: 5px; cursor: pointer;">Luxury Spa</button>
                <button onclick="applyPalette('medical-clean')" style="background: var(--color-primary-teal, #16a085); color: white; border: none; padding: 10px 20px; border-radius: 4px; margin: 5px; cursor: pointer;">Medical Clean</button>
                <button onclick="applyPalette('calming-oasis')" style="background: var(--color-secondary-peach, #f39c12); color: white; border: none; padding: 10px 20px; border-radius: 4px; margin: 5px; cursor: pointer;">Calming Oasis</button>
            </div>
        </div>
    </div>
</div>

<script>
// Environment check
function updateEnvironmentStatus() {
    const envDiv = document.getElementById('env-status');

    envDiv.innerHTML = `
        <strong>WordPress Environment:</strong><br>
        • WordPress Version: <?php echo get_bloginfo('version'); ?><br>
        • Theme: <?php echo wp_get_theme()->get('Name'); ?><br>
        • User ID: <?php echo get_current_user_id(); ?><br>
        • Can Manage Options: <?php echo current_user_can('manage_options') ? 'YES' : 'NO'; ?><br>
        • Admin Context: <?php echo is_admin() ? 'YES' : 'NO'; ?><br>
        • Script Context: WordPress wp_head() executed
    `;
}

// Test WordPress integration
function testWordPressIntegration() {
    const resultsDiv = document.getElementById('results-output');
    resultsDiv.textContent = 'Testing WordPress Integration:\n';
    resultsDiv.textContent += '='.repeat(40) + '\n';

    // Check WordPress globals
    const wpGlobals = {
        'jQuery': typeof jQuery !== 'undefined',
        'wp object': typeof wp !== 'undefined',
        'simpleCustomizer data': typeof simpleCustomizer !== 'undefined'
    };

    Object.entries(wpGlobals).forEach(([name, exists]) => {
        resultsDiv.textContent += `• ${name}: ${exists ? '✅ Available' : '❌ Missing'}\n`;
    });

    // Check script enqueue data
    if (typeof simpleCustomizer !== 'undefined') {
        resultsDiv.textContent += '\n✅ simpleCustomizer configuration found:\n';
        resultsDiv.textContent += `• AJAX URL: ${simpleCustomizer.ajaxUrl}\n`;
        resultsDiv.textContent += `• Is Admin: ${simpleCustomizer.isAdmin}\n`;
        resultsDiv.textContent += `• PVC-005 Enabled: ${simpleCustomizer.pvc005Enabled}\n`;
        resultsDiv.textContent += `• Debug Mode: ${simpleCustomizer.debug}\n`;
    } else {
        resultsDiv.textContent += '\n❌ simpleCustomizer configuration missing\n';
        resultsDiv.textContent += 'This indicates script localization failed\n';
    }
}

// Test script URLs
function testScriptURLs() {
    const resultsDiv = document.getElementById('results-output');
    resultsDiv.textContent += '\nTesting Script URL Accessibility:\n';
    resultsDiv.textContent += '='.repeat(40) + '\n';

    const scriptUrls = [
        '<?php echo get_template_directory_uri(); ?>/assets/js/semantic-color-system.js',
        '<?php echo get_template_directory_uri(); ?>/assets/js/color-system-manager.js',
        '<?php echo get_template_directory_uri(); ?>/assets/js/live-preview-system.js',
        '<?php echo get_template_directory_uri(); ?>/assets/js/simple-visual-customizer.js'
    ];

    let testCount = 0;

    scriptUrls.forEach(url => {
        fetch(url, { method: 'HEAD' })
            .then(response => {
                testCount++;
                const status = response.ok ? '✅ ACCESSIBLE' : '❌ FAILED';
                resultsDiv.textContent += `${status} ${url.split('/').pop()} (${response.status})\n`;

                if (testCount === scriptUrls.length) {
                    resultsDiv.textContent += '\n🔍 URL accessibility test complete\n';
                }
            })
            .catch(error => {
                testCount++;
                resultsDiv.textContent += `❌ ERROR ${url.split('/').pop()}: ${error.message}\n`;
            });
    });
}

// Manually load scripts
function manuallyLoadScripts() {
    const resultsDiv = document.getElementById('results-output');
    resultsDiv.textContent += '\nForce Loading Scripts Manually:\n';
    resultsDiv.textContent += '='.repeat(40) + '\n';

    const scriptsToLoad = [
        {
            id: 'semantic-color-system',
            src: '<?php echo get_template_directory_uri(); ?>/assets/js/semantic-color-system.js'
        },
        {
            id: 'color-system-manager',
            src: '<?php echo get_template_directory_uri(); ?>/assets/js/color-system-manager.js'
        },
        {
            id: 'live-preview-system',
            src: '<?php echo get_template_directory_uri(); ?>/assets/js/live-preview-system.js'
        },
        {
            id: 'simple-visual-customizer',
            src: '<?php echo get_template_directory_uri(); ?>/assets/js/simple-visual-customizer.js'
        }
    ];

    let loadedCount = 0;

    scriptsToLoad.forEach((script, index) => {
        // Remove existing script if present
        const existing = document.getElementById(script.id);
        if (existing) existing.remove();

        const scriptElement = document.createElement('script');
        scriptElement.id = script.id;
        scriptElement.src = script.src;

        scriptElement.onload = () => {
            loadedCount++;
            resultsDiv.textContent += `✅ Loaded: ${script.id}\n`;

            if (loadedCount === scriptsToLoad.length) {
                setTimeout(() => {
                    resultsDiv.textContent += '\n🎉 All scripts force loaded! Testing objects...\n';
                    testLoadedObjects();
                }, 1000);
            }
        };

        scriptElement.onerror = () => {
            loadedCount++;
            resultsDiv.textContent += `❌ Failed: ${script.id}\n`;
        };

        document.head.appendChild(scriptElement);
    });
}

// Test loaded objects
function testLoadedObjects() {
    const resultsDiv = document.getElementById('results-output');

    const objectsToTest = [
        'SemanticColorSystem',
        'ColorSystemManager',
        'LivePreviewSystem',
        'simpleVisualCustomizer'
    ];

    resultsDiv.textContent += '\nTesting JavaScript Objects After Force Load:\n';
    resultsDiv.textContent += '='.repeat(50) + '\n';

    objectsToTest.forEach(objName => {
        try {
            const exists = window[objName] !== undefined;
            resultsDiv.textContent += `• ${objName}: ${exists ? '✅ AVAILABLE' : '❌ MISSING'}\n`;

            if (exists && typeof window[objName] === 'function') {
                try {
                    if (objName === 'LivePreviewSystem') {
                        const instance = new window[objName]();
                        resultsDiv.textContent += `  ↳ Instantiation: ✅ SUCCESS\n`;
                    }
                } catch (e) {
                    resultsDiv.textContent += `  ↳ Instantiation: ❌ FAILED (${e.message})\n`;
                }
            }
        } catch (e) {
            resultsDiv.textContent += `• ${objName}: ❌ ERROR (${e.message})\n`;
        }
    });
}

// Apply palette function
function applyPalette(paletteId) {
    const resultsDiv = document.getElementById('results-output');
    resultsDiv.textContent += `\n🎨 Attempting to apply palette: ${paletteId}\n`;

    try {
        if (typeof simpleVisualCustomizer !== 'undefined' && simpleVisualCustomizer.applyPalette) {
            simpleVisualCustomizer.applyPalette(paletteId);
            resultsDiv.textContent += `✅ Palette ${paletteId} applied via simpleVisualCustomizer\n`;
        } else if (typeof LivePreviewSystem !== 'undefined') {
            const livePreview = new LivePreviewSystem();
            livePreview.applyPalette(paletteId);
            resultsDiv.textContent += `✅ Palette ${paletteId} applied via LivePreviewSystem\n`;
        } else {
            resultsDiv.textContent += `❌ No Visual Customizer available for palette application\n`;
        }
    } catch (e) {
        resultsDiv.textContent += `❌ Palette application failed: ${e.message}\n`;
    }
}

// Auto-run tests on load
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 WordPress Integrated Script Test - PVC-009');

    updateEnvironmentStatus();

    // Check scripts status
    const statusDiv = document.getElementById('script-status');
    statusDiv.textContent = 'WordPress Script Loading Status:\n';
    statusDiv.textContent += '='.repeat(35) + '\n';

    const scripts = Array.from(document.querySelectorAll('script[src]'));
    const vcScripts = scripts.filter(script =>
        script.src.includes('visual-customizer') ||
        script.src.includes('live-preview') ||
        script.src.includes('color-system') ||
        script.src.includes('semantic-color')
    );

    statusDiv.textContent += `Total <script> tags: ${scripts.length}\n`;
    statusDiv.textContent += `Visual Customizer scripts: ${vcScripts.length}\n\n`;

    if (vcScripts.length > 0) {
        statusDiv.textContent += 'Found Visual Customizer scripts:\n';
        vcScripts.forEach(script => {
            statusDiv.textContent += `• ${script.src.split('/').pop()}\n`;
        });
    } else {
        statusDiv.textContent += '❌ No Visual Customizer scripts found in DOM\n';
        statusDiv.textContent += 'This indicates WordPress script enqueue failed\n';
    }

    // Auto-run WordPress integration test
    setTimeout(testWordPressIntegration, 2000);
});
</script>

<?php get_footer(); ?>
