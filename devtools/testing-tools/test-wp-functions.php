<?php
// Test WordPress function availability
require_once('../../../wp-config.php');
require_once('../../../wp-load.php');

echo "WordPress Function Test\n";
echo "======================\n";
echo "Function exists: " . (function_exists('simple_visual_customizer_enqueue_assets') ? 'YES' : 'NO') . "\n";
echo "User capability: " . (current_user_can('manage_options') ? 'YES' : 'NO') . "\n";
echo "PREETIDREAMS_VERSION: " . (defined('PREETIDREAMS_VERSION') ? PREETIDREAMS_VERSION : 'NOT DEFINED') . "\n";
echo "Admin user: " . (is_admin() ? 'YES' : 'NO') . "\n";
echo "Current user ID: " . get_current_user_id() . "\n";
?>
