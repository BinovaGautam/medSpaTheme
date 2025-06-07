<?php
/**
 * Visual Customizer Debug Test
 * This file helps debug the 200vw layout issue
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
    <title>Visual Customizer Debug Test</title>
    <style>
        body { margin: 20px; font-family: Arial, sans-serif; }
        .debug-section { margin: 20px 0; padding: 15px; border: 1px solid #ccc; background: #f9f9f9; }
        .debug-title { font-weight: bold; color: #333; margin-bottom: 10px; }
        .debug-info { margin: 5px 0; }
        .error { color: red; }
        .success { color: green; }
        .warning { color: orange; }
    </style>
</head>
<body <?php body_class(); ?>>

<h1>Visual Customizer Debug Test</h1>

<div class="debug-section">
    <div class="debug-title">Body Classes Check</div>
    <div class="debug-info">
        <strong>Current body classes:</strong>
        <script>
            document.write(document.body.className || 'No classes');
        </script>
    </div>
</div>

<div class="debug-section">
    <div class="debug-title">Existing Elements Check</div>
    <div class="debug-info">
        <strong>Elements with 'customizer-main-content' class:</strong>
        <script>
            const elements = document.querySelectorAll('.customizer-main-content');
            document.write(elements.length + ' found');
            if (elements.length > 0) {
                document.write('<br>Elements: ');
                elements.forEach((el, i) => {
                    document.write(`${i+1}. ${el.tagName} `);
                });
            }
        </script>
    </div>
</div>

<div class="debug-section">
    <div class="debug-title">Visual Customizer Status</div>
    <div class="debug-info">
        <strong>Visual Customizer Active:</strong>
        <?php echo function_exists('preetidreams_is_visual_customizer_active') ? 'Yes' : 'No'; ?>
    </div>
    <div class="debug-info">
        <strong>Current User Can Manage Options:</strong>
        <?php echo current_user_can('manage_options') ? 'Yes' : 'No'; ?>
    </div>
</div>

<div class="debug-section">
    <div class="debug-title">CSS Files Check</div>
    <div class="debug-info">
        <strong>Visual Customizer CSS Loaded:</strong>
        <script>
            const cssFiles = Array.from(document.styleSheets);
            const customizerCSS = cssFiles.find(sheet =>
                sheet.href && sheet.href.includes('visual-customizer')
            );
            document.write(customizerCSS ? 'Yes' : 'No');
            if (customizerCSS) {
                document.write(`<br>File: ${customizerCSS.href}`);
            }
        </script>
    </div>
</div>

<div class="debug-section">
    <div class="debug-title">JavaScript Status</div>
    <div class="debug-info">
        <strong>Visual Customizer JS Object:</strong>
        <script>
            document.write(typeof window.visualCustomizer !== 'undefined' ? 'Loaded' : 'Not Loaded');
        </script>
    </div>
</div>

<div class="debug-section">
    <div class="debug-title">Page Width Test</div>
    <div class="debug-info">
        <strong>Document Width:</strong>
        <script>document.write(document.documentElement.scrollWidth + 'px');</script>
    </div>
    <div class="debug-info">
        <strong>Viewport Width:</strong>
        <script>document.write(window.innerWidth + 'px');</script>
    </div>
    <div class="debug-info">
        <strong>Body Width:</strong>
        <script>document.write(document.body.scrollWidth + 'px');</script>
    </div>
</div>

<script>
// Monitor for any automatic class additions
const originalClassName = document.body.className;
setTimeout(() => {
    if (document.body.className !== originalClassName) {
        console.log('BODY CLASSES CHANGED!');
        console.log('Original:', originalClassName);
        console.log('Current:', document.body.className);
    }
}, 2000);

// Monitor for automatic element creation
const originalElementCount = document.querySelectorAll('.customizer-main-content').length;
setTimeout(() => {
    const currentElementCount = document.querySelectorAll('.customizer-main-content').length;
    if (currentElementCount !== originalElementCount) {
        console.log('CUSTOMIZER ELEMENTS CREATED!');
        console.log('Original count:', originalElementCount);
        console.log('Current count:', currentElementCount);
        document.querySelectorAll('.customizer-main-content').forEach((el, i) => {
            console.log(`Element ${i+1}:`, el);
        });
    }
}, 2000);
</script>

<?php wp_footer(); ?>
</body>
</html>
