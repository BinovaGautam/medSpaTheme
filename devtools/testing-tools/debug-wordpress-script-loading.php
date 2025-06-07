<?php
/**
 * WordPress Script Loading Debug - PVC-009 Integration Issue
 *
 * This script runs within WordPress to diagnose script loading and Visual Customizer integration
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WordPress Script Loading Debug - PVC-009</title>
    <?php wp_head(); ?>
    <style>
        .debug-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .debug-section {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: #f9f9f9;
        }
        .debug-section h3 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #0073aa;
            padding-bottom: 8px;
        }
        .status-good { color: #0073aa; font-weight: bold; }
        .status-bad { color: #d63384; font-weight: bold; }
        .status-warning { color: #f57c00; font-weight: bold; }
        .code-block {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 12px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            overflow-x: auto;
            white-space: pre-wrap;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin: 15px 0;
        }
        .info-item {
            background: white;
            padding: 12px;
            border-radius: 4px;
            border-left: 3px solid #0073aa;
        }
        .info-item strong {
            display: block;
            color: #0073aa;
            margin-bottom: 5px;
        }
        .test-btn {
            background: #0073aa;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }
        .test-btn:hover { background: #005a87; }
    </style>
</head>
<body <?php body_class('debug-environment'); ?>>

<div class="debug-container">
    <h1>🔧 WordPress Script Loading Debug - PVC-009</h1>
    <p><strong>Environment:</strong> WordPress <?php echo get_bloginfo('version'); ?> | Theme: <?php echo wp_get_theme()->get('Name'); ?></p>

    <!-- WordPress Environment Check -->
    <div class="debug-section">
        <h3>🏠 WordPress Environment</h3>
        <div class="info-grid">
            <div class="info-item">
                <strong>Current User ID:</strong>
                <?php echo get_current_user_id(); ?>
            </div>
            <div class="info-item">
                <strong>Can Manage Options:</strong>
                <span class="<?php echo current_user_can('manage_options') ? 'status-good' : 'status-bad'; ?>">
                    <?php echo current_user_can('manage_options') ? '✅ Yes' : '❌ No'; ?>
                </span>
            </div>
            <div class="info-item">
                <strong>Is Admin:</strong>
                <span class="<?php echo is_admin() ? 'status-good' : 'status-warning'; ?>">
                    <?php echo is_admin() ? '✅ Yes' : '⚠️ No (Frontend)'; ?>
                </span>
            </div>
            <div class="info-item">
                <strong>Admin Bar Showing:</strong>
                <span class="<?php echo is_admin_bar_showing() ? 'status-good' : 'status-warning'; ?>">
                    <?php echo is_admin_bar_showing() ? '✅ Yes' : '⚠️ No'; ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Script Registration Check -->
    <div class="debug-section">
        <h3>📋 Script Registration Status</h3>
        <div class="code-block" id="script-registration-status">
<?php
$scripts_to_check = [
    'live-preview-system',
    'simple-visual-customizer',
    'color-palette-interface',
    'semantic-color-system',
    'color-system-manager',
    'preview-messenger',
    'wp-customizer-bridge'
];

global $wp_scripts;
echo "WordPress Scripts Object Status:\n";
echo "Scripts Registered: " . count($wp_scripts->registered) . "\n\n";

echo "Visual Customizer Scripts Check:\n";
echo str_repeat("=", 40) . "\n";

foreach ($scripts_to_check as $script_handle) {
    $registered = isset($wp_scripts->registered[$script_handle]);
    $enqueued = in_array($script_handle, $wp_scripts->queue);

    echo "• {$script_handle}:\n";
    echo "  Registered: " . ($registered ? "✅ YES" : "❌ NO") . "\n";
    echo "  Enqueued: " . ($enqueued ? "✅ YES" : "❌ NO") . "\n";

    if ($registered) {
        $script = $wp_scripts->registered[$script_handle];
        echo "  URL: " . $script->src . "\n";
        echo "  Dependencies: " . implode(', ', $script->deps) . "\n";
        echo "  Version: " . $script->ver . "\n";
    }
    echo "\n";
}
?>
        </div>
    </div>

    <!-- Visual Customizer Constants and Files -->
    <div class="debug-section">
        <h3>📁 File and Constant Check</h3>
        <div class="info-grid">
            <div class="info-item">
                <strong>PREETIDREAMS_VERSION:</strong>
                <?php echo defined('PREETIDREAMS_VERSION') ? PREETIDREAMS_VERSION : 'Not defined'; ?>
            </div>
            <div class="info-item">
                <strong>Theme Directory:</strong>
                <?php echo get_template_directory(); ?>
            </div>
            <div class="info-item">
                <strong>Theme URI:</strong>
                <?php echo get_template_directory_uri(); ?>
            </div>
        </div>

        <div class="code-block">
<?php
echo "Visual Customizer File Existence Check:\n";
echo str_repeat("=", 40) . "\n";

$js_files_to_check = [
    '/assets/js/live-preview-system.js',
    '/assets/js/simple-visual-customizer.js',
    '/assets/js/color-palette-interface.js',
    '/assets/js/semantic-color-system.js',
    '/assets/js/color-system-manager.js',
    '/assets/css/simple-visual-customizer.css'
];

foreach ($js_files_to_check as $file_path) {
    $full_path = get_template_directory() . $file_path;
    $exists = file_exists($full_path);
    $readable = $exists ? is_readable($full_path) : false;
    $size = $exists ? filesize($full_path) : 0;

    echo "• {$file_path}:\n";
    echo "  Exists: " . ($exists ? "✅ YES" : "❌ NO") . "\n";
    echo "  Readable: " . ($readable ? "✅ YES" : "❌ NO") . "\n";
    echo "  Size: " . ($size > 0 ? number_format($size) . " bytes" : "0 bytes") . "\n";
    echo "  URL: " . get_template_directory_uri() . $file_path . "\n\n";
}
?>
        </div>
    </div>

    <!-- Action Hook Check -->
    <div class="debug-section">
        <h3>🔗 Action Hook Status</h3>
        <div class="code-block">
<?php
echo "WordPress Action Hooks Analysis:\n";
echo str_repeat("=", 40) . "\n";

// Check if our enqueue function is hooked
$wp_enqueue_hooks = $GLOBALS['wp_filter']['wp_enqueue_scripts'] ?? null;
$admin_enqueue_hooks = $GLOBALS['wp_filter']['admin_enqueue_scripts'] ?? null;

echo "wp_enqueue_scripts hooks:\n";
if ($wp_enqueue_hooks) {
    foreach ($wp_enqueue_hooks->callbacks as $priority => $callbacks) {
        echo "  Priority {$priority}:\n";
        foreach ($callbacks as $callback_id => $callback_data) {
            if (is_array($callback_data['function'])) {
                $function_name = get_class($callback_data['function'][0]) . '::' . $callback_data['function'][1];
            } else {
                $function_name = $callback_data['function'];
            }

            if (strpos($function_name, 'visual_customizer') !== false ||
                strpos($function_name, 'simple_visual_customizer') !== false) {
                echo "    ✅ {$function_name}\n";
            }
        }
    }
} else {
    echo "  ❌ No wp_enqueue_scripts hooks found\n";
}

echo "\nadmin_enqueue_scripts hooks:\n";
if ($admin_enqueue_hooks) {
    $visual_customizer_hooks = 0;
    foreach ($admin_enqueue_hooks->callbacks as $priority => $callbacks) {
        foreach ($callbacks as $callback_id => $callback_data) {
            if (is_array($callback_data['function'])) {
                $function_name = get_class($callback_data['function'][0]) . '::' . $callback_data['function'][1];
            } else {
                $function_name = $callback_data['function'];
            }

            if (strpos($function_name, 'visual_customizer') !== false ||
                strpos($function_name, 'simple_visual_customizer') !== false) {
                echo "    ✅ Priority {$priority}: {$function_name}\n";
                $visual_customizer_hooks++;
            }
        }
    }
    echo "  Total Visual Customizer hooks: {$visual_customizer_hooks}\n";
} else {
    echo "  ❌ No admin_enqueue_scripts hooks found\n";
}
?>
        </div>
    </div>

    <!-- Live Test Buttons -->
    <div class="debug-section">
        <h3>🧪 Live Test Functions</h3>
        <button class="test-btn" onclick="testScriptEnqueue()">Test Script Enqueue</button>
        <button class="test-btn" onclick="testFileUrls()">Test File URLs</button>
        <button class="test-btn" onclick="forceEnqueueScripts()">Force Enqueue Scripts</button>

        <div id="test-results" class="code-block" style="margin-top: 15px; display: none;">
            Test results will appear here...
        </div>
    </div>

    <!-- Visual Customizer Integration Test -->
    <div class="debug-section">
        <h3>🎨 Visual Customizer Integration Test</h3>
        <div class="info-grid">
            <div class="info-item">
                <strong>Function Exists - simple_visual_customizer_enqueue_assets:</strong>
                <span class="<?php echo function_exists('simple_visual_customizer_enqueue_assets') ? 'status-good' : 'status-bad'; ?>">
                    <?php echo function_exists('simple_visual_customizer_enqueue_assets') ? '✅ Yes' : '❌ No'; ?>
                </span>
            </div>
            <div class="info-item">
                <strong>File Included - visual-customizer-simple.php:</strong>
                <span class="<?php echo file_exists(get_template_directory() . '/inc/visual-customizer-simple.php') ? 'status-good' : 'status-bad'; ?>">
                    <?php echo file_exists(get_template_directory() . '/inc/visual-customizer-simple.php') ? '✅ Yes' : '❌ No'; ?>
                </span>
            </div>
        </div>

        <button class="test-btn" onclick="manuallyEnqueueVisualCustomizer()">Manually Enqueue Visual Customizer</button>
    </div>

    <!-- Browser Console Log -->
    <div class="debug-section">
        <h3>🖥️ Browser Console Integration Check</h3>
        <p>Check browser console for JavaScript object availability after page load.</p>
        <button class="test-btn" onclick="checkJavaScriptObjects()">Check JavaScript Objects</button>
        <div id="js-object-results"></div>
    </div>
</div>

<script>
// Test functions
function testScriptEnqueue() {
    const results = document.getElementById('test-results');
    results.style.display = 'block';
    results.innerHTML = 'Testing script enqueue...\n';

    // Check if scripts are loaded in DOM
    const scripts = Array.from(document.querySelectorAll('script[src]'));
    const customizerScripts = scripts.filter(script =>
        script.src.includes('visual-customizer') ||
        script.src.includes('live-preview') ||
        script.src.includes('color-palette') ||
        script.src.includes('semantic-color')
    );

    results.innerHTML += `Found ${customizerScripts.length} Visual Customizer scripts in DOM:\n`;
    customizerScripts.forEach(script => {
        results.innerHTML += `• ${script.src}\n`;
    });

    if (customizerScripts.length === 0) {
        results.innerHTML += '\n❌ No Visual Customizer scripts found in DOM!\n';
        results.innerHTML += 'This indicates scripts are not being enqueued properly.\n';
    }
}

function testFileUrls() {
    const results = document.getElementById('test-results');
    results.style.display = 'block';
    results.innerHTML = 'Testing file URL accessibility...\n';

    const testUrls = [
        '<?php echo get_template_directory_uri(); ?>/assets/js/live-preview-system.js',
        '<?php echo get_template_directory_uri(); ?>/assets/js/simple-visual-customizer.js',
        '<?php echo get_template_directory_uri(); ?>/assets/js/color-palette-interface.js'
    ];

    testUrls.forEach(url => {
        fetch(url, { method: 'HEAD' })
            .then(response => {
                const status = response.ok ? '✅ OK' : '❌ FAILED';
                results.innerHTML += `${status} ${url} (${response.status})\n`;
            })
            .catch(error => {
                results.innerHTML += `❌ ERROR ${url}: ${error.message}\n`;
            });
    });
}

function forceEnqueueScripts() {
    const results = document.getElementById('test-results');
    results.style.display = 'block';
    results.innerHTML = 'Attempting to force enqueue scripts via AJAX...\n';

    // Make AJAX call to WordPress to force enqueue
    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=debug_force_enqueue_visual_customizer&nonce=<?php echo wp_create_nonce('debug_force_enqueue'); ?>'
    })
    .then(response => response.json())
    .then(data => {
        results.innerHTML += `AJAX Response: ${JSON.stringify(data, null, 2)}\n`;
    })
    .catch(error => {
        results.innerHTML += `AJAX Error: ${error.message}\n`;
    });
}

function manuallyEnqueueVisualCustomizer() {
    // Manually create script elements to test if files work
    const results = document.getElementById('test-results');
    results.style.display = 'block';
    results.innerHTML = 'Manually loading Visual Customizer scripts...\n';

    const scriptsToLoad = [
        '<?php echo get_template_directory_uri(); ?>/assets/js/live-preview-system.js',
        '<?php echo get_template_directory_uri(); ?>/assets/js/semantic-color-system.js',
        '<?php echo get_template_directory_uri(); ?>/assets/js/color-system-manager.js',
        '<?php echo get_template_directory_uri(); ?>/assets/js/color-palette-interface.js',
        '<?php echo get_template_directory_uri(); ?>/assets/js/simple-visual-customizer.js'
    ];

    let loadedCount = 0;

    scriptsToLoad.forEach((src, index) => {
        const script = document.createElement('script');
        script.src = src;
        script.onload = () => {
            loadedCount++;
            results.innerHTML += `✅ Loaded: ${src.split('/').pop()}\n`;

            if (loadedCount === scriptsToLoad.length) {
                results.innerHTML += '\n🎉 All scripts loaded! Checking for objects...\n';
                setTimeout(() => {
                    checkJavaScriptObjects();
                }, 1000);
            }
        };
        script.onerror = () => {
            results.innerHTML += `❌ Failed to load: ${src.split('/').pop()}\n`;
        };
        document.head.appendChild(script);
    });
}

function checkJavaScriptObjects() {
    const resultsDiv = document.getElementById('js-object-results');

    const objectsToCheck = [
        'LivePreviewSystem',
        'ColorSystemManager',
        'ColorPaletteInterface',
        'SemanticColorSystem',
        'simpleVisualCustomizer',
        'window.livePreviewSystem'
    ];

    let html = '<div class="code-block" style="margin-top: 10px;">JavaScript Objects Check:\n';
    html += '='.repeat(30) + '\n';

    objectsToCheck.forEach(objName => {
        let exists = false;
        try {
            if (objName.startsWith('window.')) {
                exists = eval(objName) !== undefined;
            } else {
                exists = window[objName] !== undefined || eval(`typeof ${objName}`) !== 'undefined';
            }
        } catch (e) {
            exists = false;
        }

        html += `• ${objName}: ${exists ? '✅ Available' : '❌ Not found'}\n`;
    });

    html += '\nConsole Commands to Try:\n';
    html += '• new LivePreviewSystem()\n';
    html += '• ColorSystemManager.getInstance()\n';
    html += '• window.openThemeCustomizer()\n';
    html += '</div>';

    resultsDiv.innerHTML = html;
}

// Auto-run checks on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 WordPress Script Loading Debug initialized');

    // Log current user capabilities
    console.log('Current user can manage_options:', <?php echo current_user_can('manage_options') ? 'true' : 'false'; ?>);
    console.log('Is admin page:', <?php echo is_admin() ? 'true' : 'false'; ?>);
    console.log('Admin bar showing:', <?php echo is_admin_bar_showing() ? 'true' : 'false'; ?>);

    // Auto-check JavaScript objects
    setTimeout(checkJavaScriptObjects, 2000);
});
</script>

<?php wp_footer(); ?>
</body>
</html>
