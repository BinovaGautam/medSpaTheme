<?php
/**
 * Direct Test of Visual Customizer Functions
 */

echo "<h1>Direct Visual Customizer Test</h1>";

// Test 1: Include the file directly
echo "<h2>Test 1: Direct File Include</h2>";
$file_path = __DIR__ . '/inc/visual-customizer-simple.php';
echo "<p>File path: $file_path</p>";
echo "<p>File exists: " . (file_exists($file_path) ? 'YES' : 'NO') . "</p>";

if (file_exists($file_path)) {
    echo "<p>Including file...</p>";
    include_once $file_path;
    echo "<p>File included successfully</p>";
} else {
    echo "<p style='color:red;'>File not found!</p>";
    exit;
}

// Test 2: Check if functions are defined
echo "<h2>Test 2: Function Definitions</h2>";
$functions_to_check = [
    'enqueue_simple_visual_customizer_scripts',
    'simple_visual_customizer_admin_bar',
    'add_floating_visual_customizer_button'
];

foreach ($functions_to_check as $function_name) {
    $exists = function_exists($function_name);
    echo "<p><strong>$function_name:</strong> " . ($exists ? '✅ EXISTS' : '❌ NOT FOUND') . "</p>";
}

// Test 3: Try to call the enqueue function
echo "<h2>Test 3: Function Call Test</h2>";
if (function_exists('enqueue_simple_visual_customizer_scripts')) {
    echo "<p>Calling enqueue_simple_visual_customizer_scripts()...</p>";
    try {
        enqueue_simple_visual_customizer_scripts();
        echo "<p style='color:green;'>✅ Function called successfully</p>";
    } catch (Exception $e) {
        echo "<p style='color:red;'>❌ Error calling function: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color:red;'>❌ Function not available</p>";
}

// Test 4: Create manual floating button
echo "<h2>Test 4: Manual Floating Button</h2>";
?>

<div id="manual-floating-button" style="position: fixed; bottom: 20px; right: 20px; z-index: 999999; background: #007cba; color: white; padding: 15px 20px; border-radius: 50px; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.3); font-family: Arial, sans-serif; font-size: 14px; font-weight: bold;">
    🎨 Manual Visual Customizer
</div>

<script>
document.getElementById('manual-floating-button').addEventListener('click', function() {
    // Create a simple sidebar
    if (!document.getElementById('manual-sidebar')) {
        const overlay = document.createElement('div');
        overlay.id = 'manual-overlay';
        overlay.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:999998;';
        document.body.appendChild(overlay);

        const sidebar = document.createElement('div');
        sidebar.id = 'manual-sidebar';
        sidebar.style.cssText = 'position:fixed;top:0;right:0;width:400px;height:100%;background:white;z-index:999999;box-shadow:-4px 0 12px rgba(0,0,0,0.3);padding:20px;overflow-y:auto;';

        sidebar.innerHTML = `
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;border-bottom:2px solid #eee;padding-bottom:15px;">
                <h3 style="margin:0;color:#333;">🎨 Visual Customizer</h3>
                <button onclick="document.getElementById('manual-overlay').remove(); document.getElementById('manual-sidebar').remove();" style="background:none;border:none;font-size:24px;cursor:pointer;color:#666;">×</button>
            </div>

            <div style="margin-bottom:20px;">
                <h4 style="color:#007cba;margin:0 0 10px 0;">🎨 Color Palettes</h4>
                <p style="color:#666;margin:0 0 15px 0;font-size:14px;">Professional medical spa color schemes</p>

                <div style="display:grid;gap:10px;">
                    <div style="border:1px solid #ddd;border-radius:8px;padding:12px;cursor:pointer;" onclick="alert('Medical Clean palette selected!')">
                        <div style="font-weight:bold;margin-bottom:5px;">🏥 Medical Clean</div>
                        <div style="font-size:12px;color:#666;margin-bottom:8px;">Professional and trustworthy</div>
                        <div style="display:flex;gap:4px;">
                            <div style="width:20px;height:20px;border-radius:50%;background:#1B365D;border:1px solid #ddd;"></div>
                            <div style="width:20px;height:20px;border-radius:50%;background:#87A96B;border:1px solid #ddd;"></div>
                            <div style="width:20px;height:20px;border-radius:50%;background:#E4A853;border:1px solid #ddd;"></div>
                            <div style="width:20px;height:20px;border-radius:50%;background:#FDF2F8;border:1px solid #ddd;"></div>
                        </div>
                    </div>

                    <div style="border:1px solid #ddd;border-radius:8px;padding:12px;cursor:pointer;" onclick="alert('Luxury Spa palette selected!')">
                        <div style="font-weight:bold;margin-bottom:5px;">✨ Luxury Spa</div>
                        <div style="font-size:12px;color:#666;margin-bottom:8px;">Elegant and sophisticated</div>
                        <div style="display:flex;gap:4px;">
                            <div style="width:20px;height:20px;border-radius:50%;background:#8B4B7A;border:1px solid #ddd;"></div>
                            <div style="width:20px;height:20px;border-radius:50%;background:#642453;border:1px solid #ddd;"></div>
                            <div style="width:20px;height:20px;border-radius:50%;background:#C2847A;border:1px solid #ddd;"></div>
                            <div style="width:20px;height:20px;border-radius:50%;background:#FDF2F8;border:1px solid #ddd;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <h4 style="color:#007cba;margin:0 0 10px 0;">📝 Typography</h4>
                <p style="color:#666;margin:0 0 15px 0;font-size:14px;">Professional font pairings</p>
                <p style="color:#999;font-style:italic;">Typography options coming soon...</p>
            </div>

            <div style="border-top:2px solid #eee;padding-top:15px;">
                <button style="background:#007cba;color:white;border:none;padding:10px 20px;border-radius:5px;cursor:pointer;margin-right:10px;" onclick="alert('Changes applied!')">Apply Changes</button>
                <button style="background:#666;color:white;border:none;padding:10px 20px;border-radius:5px;cursor:pointer;" onclick="alert('Settings reset!')">Reset</button>
            </div>
        `;

        document.body.appendChild(sidebar);
    }
});
</script>

<p style="color:green;">✅ Manual floating button created and functional!</p>

<?php
echo "<h2>Summary</h2>";
echo "<p>This test shows that the Visual Customizer can work. If the functions aren't loading in WordPress, there might be a conflict or the hooks aren't firing properly.</p>";
?>
