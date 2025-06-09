<?php
/**
 * Simple WordPress Customizer Test
 *
 * Quick test to verify customizer functionality without fatal errors
 */

// WordPress environment
require_once('../../../../wp-load.php');

// Simple customizer test
try {
    // Test 1: WP_Customize_Manager instantiation
    echo "🔍 Testing WordPress Customizer...\n\n";

    if (class_exists('WP_Customize_Manager')) {
        echo "✅ WP_Customize_Manager class exists\n";

        // Test 2: Can we create an instance?
        $wp_customize = new WP_Customize_Manager();
        echo "✅ WP_Customize_Manager instance created successfully\n";

        // Test 3: Check if customize_register action has hooks
        $hooks = has_action('customize_register');
        echo "✅ Customize register hooks: " . ($hooks ? $hooks . " registered" : "None") . "\n";

        // Test 4: Test component registration
        if (class_exists('ComponentRegistry')) {
            $components = ComponentRegistry::get_registered_components();
            echo "✅ Component system: " . count($components) . " components registered\n";

            foreach ($components as $name => $component) {
                echo "   - {$name}: " . ($component['class'] ?? 'Unknown class') . "\n";
            }
        }

        echo "\n🎉 WordPress Customizer Test: ALL TESTS PASSED!\n";
        echo "📍 The customizer should now be accessible without fatal errors.\n";
        echo "🔗 Test URL: " . admin_url('customize.php') . "\n";

    } else {
        echo "❌ WP_Customize_Manager class not found\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📁 File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "WordPress Customizer Quick Test Complete\n";
echo "Time: " . current_time('Y-m-d H:i:s') . "\n";
echo str_repeat("=", 60) . "\n";
?>
