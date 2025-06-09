<?php
/**
 * Test Visual Customizer Loading
 * Access via: http://localhost:10004/medspaa/wp-content/themes/medSpaTheme/test-visual-customizer-loading.php
 */

// Load WordPress
require_once('../../../wp-load.php');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Visual Customizer Loading Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f0f0f0; }
        .test-section { background: white; padding: 20px; margin: 10px 0; border-radius: 8px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
    </style>
</head>
<body>

<h1>🧪 Visual Customizer Loading Test</h1>

<div class="test-section">
    <h2>Function Existence Test</h2>

    <p><strong>enqueue_simple_visual_customizer_scripts:</strong>
        <span class="<?php echo function_exists('enqueue_simple_visual_customizer_scripts') ? 'success' : 'error'; ?>">
            <?php echo function_exists('enqueue_simple_visual_customizer_scripts') ? '✅ EXISTS' : '❌ NOT FOUND'; ?>
        </span>
    </p>

    <p><strong>simple_visual_customizer_admin_bar:</strong>
        <span class="<?php echo function_exists('simple_visual_customizer_admin_bar') ? 'success' : 'error'; ?>">
            <?php echo function_exists('simple_visual_customizer_admin_bar') ? '✅ EXISTS' : '❌ NOT FOUND'; ?>
        </span>
    </p>

    <p><strong>add_floating_visual_customizer_button:</strong>
        <span class="<?php echo function_exists('add_floating_visual_customizer_button') ? 'success' : 'error'; ?>">
            <?php echo function_exists('add_floating_visual_customizer_button') ? '✅ EXISTS' : '❌ NOT FOUND'; ?>
        </span>
    </p>

    <p><em>Note: Cleaned up duplicate floating button implementations - now using Design Token System v1.0.0 only</em></p>
</div>

<div class="test-section">
    <h2>File Inclusion Test</h2>

    <p><strong>visual-customizer-simple.php included:</strong>
        <span class="<?php echo function_exists('enqueue_simple_visual_customizer_scripts') ? 'success' : 'error'; ?>">
            <?php echo function_exists('enqueue_simple_visual_customizer_scripts') ? '✅ YES' : '❌ NO'; ?>
        </span>
    </p>

    <p><strong>File exists:</strong>
        <span class="<?php echo file_exists(get_template_directory() . '/inc/visual-customizer-simple.php') ? 'success' : 'error'; ?>">
            <?php echo file_exists(get_template_directory() . '/inc/visual-customizer-simple.php') ? '✅ YES' : '❌ NO'; ?>
        </span>
    </p>
</div>

<div class="test-section">
    <h2>Manual Function Call Test</h2>

    <?php if (function_exists('enqueue_simple_visual_customizer_scripts')): ?>
        <p class="success">✅ Manually calling enqueue_simple_visual_customizer_scripts()...</p>
        <?php
        // Manually call the function
        enqueue_simple_visual_customizer_scripts();
        ?>
        <p class="success">✅ Function called successfully!</p>
    <?php else: ?>
        <p class="error">❌ Cannot call function - it doesn't exist</p>
    <?php endif; ?>
</div>

<div class="test-section">
    <h2>WordPress Hooks Test</h2>

    <p><strong>wp_enqueue_scripts hook:</strong>
        <?php
        $wp_enqueue_hooks = $GLOBALS['wp_filter']['wp_enqueue_scripts'] ?? null;
        $has_our_hook = false;
        if ($wp_enqueue_hooks) {
            foreach ($wp_enqueue_hooks->callbacks as $priority => $callbacks) {
                foreach ($callbacks as $callback) {
                    if (isset($callback['function']) && is_string($callback['function']) && $callback['function'] === 'enqueue_simple_visual_customizer_scripts') {
                        $has_our_hook = true;
                        break 2;
                    }
                }
            }
        }
        ?>
        <span class="<?php echo $has_our_hook ? 'success' : 'error'; ?>">
            <?php echo $has_our_hook ? '✅ HOOKED' : '❌ NOT HOOKED'; ?>
        </span>
    </p>

    <p><strong>admin_bar_menu hook:</strong>
        <?php
        $admin_bar_hooks = $GLOBALS['wp_filter']['admin_bar_menu'] ?? null;
        $has_admin_bar_hook = false;
        if ($admin_bar_hooks) {
            foreach ($admin_bar_hooks->callbacks as $priority => $callbacks) {
                foreach ($callbacks as $callback) {
                    if (isset($callback['function']) && is_string($callback['function']) && $callback['function'] === 'simple_visual_customizer_admin_bar') {
                        $has_admin_bar_hook = true;
                        break 2;
                    }
                }
            }
        }
        ?>
        <span class="<?php echo $has_admin_bar_hook ? 'success' : 'error'; ?>">
            <?php echo $has_admin_bar_hook ? '✅ HOOKED' : '❌ NOT HOOKED'; ?>
        </span>
    </p>
</div>

<div class="test-section">
    <h2>Admin Bar Integration Test</h2>

    <p><strong>Access Method:</strong> Admin bar → 🎨 Visual Customizer button → Sidebar opens</p>
    <p><strong>Design Token System:</strong> v1.0.0 (Sprint 2 Extension ACTIVE)</p>
    <p><em>Note: All floating button implementations removed - using clean admin bar integration only</em></p>
</div>

</body>
</html>
