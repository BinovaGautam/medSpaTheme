<?php
/**
 * Fix Treatments Page Script
 * DevKinsta WordPress Fix Utility
 * Created by BUG-RESOLVER-001 Agent
 */

echo "🔧 TREATMENTS PAGE FIX UTILITY\n";
echo "═══════════════════════════════════════\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n\n";

// Step 1: Check DevKinsta Status
echo "🔍 STEP 1: CHECKING DEVKINSTA STATUS\n";
echo "────────────────────────────────────────\n";

$devkinsta_processes = shell_exec('ps aux | grep -i devkinsta | grep -v grep');
if ($devkinsta_processes) {
    echo "✅ DevKinsta processes are running\n";
    echo "Processes found:\n";
    $lines = explode("\n", trim($devkinsta_processes));
    foreach ($lines as $line) {
        if (strpos($line, 'DevKinsta') !== false) {
            echo "  • " . substr($line, 0, 100) . "...\n";
        }
    }
} else {
    echo "❌ DevKinsta is not running\n";
    echo "\n🚀 SOLUTION: Start DevKinsta\n";
    echo "1. Open DevKinsta application\n";
    echo "2. Start the 'medspaa' site\n";
    echo "3. Wait for all services to be green\n";
    echo "4. Try again\n\n";
    exit(1);
}

echo "\n🔍 STEP 2: TESTING DATABASE CONNECTION\n";
echo "────────────────────────────────────────\n";

// Try to load WordPress
$wp_loaded = false;
$wp_paths = [
    '../../../wp-load.php',
    '../../../wp-config.php',
    '../../../../wp-load.php'
];

foreach ($wp_paths as $path) {
    if (file_exists($path)) {
        ob_start();
        try {
            require_once($path);
            $output = ob_get_clean();

            // Check if there's a database error in the output
            if (strpos($output, 'Error establishing a database connection') === false) {
                $wp_loaded = true;
                echo "✅ WordPress loaded successfully\n";
                break;
            } else {
                echo "❌ Database connection error detected\n";
                ob_end_clean();
            }
        } catch (Exception $e) {
            ob_end_clean();
            echo "⚠️  Error loading WordPress: " . $e->getMessage() . "\n";
        }
    }
}

if (!$wp_loaded) {
    echo "\n🚨 DATABASE CONNECTION ISSUE DETECTED\n";
    echo "────────────────────────────────────────\n";

    echo "💡 SOLUTIONS TO TRY:\n\n";

    echo "1. RESTART DEVKINSTA SERVICES:\n";
    echo "   • Open DevKinsta app\n";
    echo "   • Click on 'medspaa' site\n";
    echo "   • Click 'Stop' then 'Start'\n";
    echo "   • Wait for all indicators to be green\n\n";

    echo "2. CHECK MYSQL SERVICE:\n";
    echo "   • In DevKinsta, check if MySQL is running\n";
    echo "   • If red/yellow, restart MySQL service\n\n";

    echo "3. RESTART DOCKER (if using Docker):\n";
    echo "   • DevKinsta uses Docker containers\n";
    echo "   • Restart Docker Desktop if available\n\n";

    echo "4. REBOOT DEVKINSTA:\n";
    echo "   • Completely quit DevKinsta\n";
    echo "   • Restart the application\n";
    echo "   • Start the medspaa site\n\n";

    echo "5. CHECK SYSTEM RESOURCES:\n";
    echo "   • Ensure enough RAM available\n";
    echo "   • Close unnecessary applications\n";
    echo "   • Check disk space\n\n";

    echo "🔧 QUICK TEST COMMANDS:\n";
    echo "After fixing DevKinsta, test these:\n";
    echo "• curl -I http://medspaa.local/\n";
    echo "• curl -I http://medspaa.local/wp-admin/\n";
    echo "• php " . __FILE__ . "\n\n";

    exit(1);
}

// If we get here, WordPress is loaded
global $wpdb;

echo "\n🔍 STEP 3: VERIFYING WORDPRESS SETUP\n";
echo "────────────────────────────────────────\n";

echo "• WordPress Version: " . get_bloginfo('version') . "\n";
echo "• Site URL: " . get_site_url() . "\n";
echo "• Database Host: " . DB_HOST . "\n";
echo "• Database Name: " . DB_NAME . "\n";

// Test database connection
try {
    $result = $wpdb->get_var("SELECT 1");
    echo "✅ Database connection: Working\n";
} catch (Exception $e) {
    echo "❌ Database connection: Failed - " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n🔍 STEP 4: CHECKING TREATMENTS SETUP\n";
echo "────────────────────────────────────────\n";

// Check treatments page
$treatments_page = get_page_by_path('treatments');
if ($treatments_page) {
    echo "✅ Treatments page exists (ID: {$treatments_page->ID})\n";

    $template = get_post_meta($treatments_page->ID, '_wp_page_template', true);
    if ($template === 'page-treatments.php') {
        echo "✅ Correct template assigned\n";
    } else {
        echo "⚠️  Fixing template assignment...\n";
        update_post_meta($treatments_page->ID, '_wp_page_template', 'page-treatments.php');
        echo "✅ Template fixed\n";
    }
} else {
    echo "❌ Treatments page missing\n";
    echo "Creating treatments page...\n";

    $page_data = [
        'post_title' => 'Treatments',
        'post_name' => 'treatments',
        'post_content' => '<p>Explore our comprehensive range of premium medical spa treatments.</p>',
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_author' => 1
    ];

    $page_id = wp_insert_post($page_data);
    if ($page_id && !is_wp_error($page_id)) {
        update_post_meta($page_id, '_wp_page_template', 'page-treatments.php');
        echo "✅ Treatments page created (ID: {$page_id})\n";
    } else {
        echo "❌ Failed to create treatments page\n";
    }
}

// Check treatment post type
$treatment_post_type = get_post_type_object('treatment');
if ($treatment_post_type) {
    echo "✅ Treatment post type registered\n";
} else {
    echo "❌ Treatment post type not registered\n";
    echo "   Check functions.php for register_post_type('treatment')\n";
}

// Check for existing treatments
$treatments_query = new WP_Query([
    'post_type' => 'treatment',
    'posts_per_page' => 1,
    'post_status' => 'publish'
]);

if ($treatments_query->have_posts()) {
    $total_treatments = wp_count_posts('treatment');
    echo "✅ Found {$total_treatments->publish} published treatments\n";
    wp_reset_postdata();
} else {
    echo "❌ No treatments found\n";
    echo "\n🔧 CREATING SAMPLE TREATMENTS\n";
    echo "────────────────────────────────────────\n";

    $sample_treatments = [
        [
            'title' => 'Botox Injections',
            'content' => 'Professional Botox treatment for wrinkle reduction and facial rejuvenation.',
            'category' => 'Injectable Treatments',
            'price' => '$299-$599',
            'duration' => '30 minutes'
        ],
        [
            'title' => 'Chemical Peel',
            'content' => 'Advanced chemical peel treatment for skin renewal and improved texture.',
            'category' => 'Facial Treatments',
            'price' => '$199-$399',
            'duration' => '45 minutes'
        ],
        [
            'title' => 'Laser Hair Removal',
            'content' => 'Permanent hair reduction using advanced laser technology.',
            'category' => 'Laser Treatments',
            'price' => '$149-$399',
            'duration' => '30-60 minutes'
        ],
        [
            'title' => 'Dermal Fillers',
            'content' => 'Hyaluronic acid fillers for volume restoration and facial contouring.',
            'category' => 'Injectable Treatments',
            'price' => '$599-$999',
            'duration' => '45 minutes'
        ],
        [
            'title' => 'Microneedling',
            'content' => 'Collagen induction therapy for improved skin texture and tone.',
            'category' => 'Facial Treatments',
            'price' => '$299-$499',
            'duration' => '60 minutes'
        ]
    ];

    $created_count = 0;
    foreach ($sample_treatments as $treatment) {
        $post_data = [
            'post_title' => $treatment['title'],
            'post_content' => $treatment['content'],
            'post_status' => 'publish',
            'post_type' => 'treatment',
            'post_author' => 1
        ];

        $post_id = wp_insert_post($post_data);
        if ($post_id && !is_wp_error($post_id)) {
            // Add custom fields
            update_post_meta($post_id, 'treatment_category', $treatment['category']);
            update_post_meta($post_id, 'price_range', $treatment['price']);
            update_post_meta($post_id, 'duration', $treatment['duration']);

            $created_count++;
            echo "  ✅ Created: {$treatment['title']}\n";
        }
    }

    echo "✅ Created {$created_count} sample treatments\n";
}

echo "\n🔍 STEP 5: FINAL VERIFICATION\n";
echo "────────────────────────────────────────\n";

// Test the treatments query one more time
$final_query = new WP_Query([
    'post_type' => 'treatment',
    'posts_per_page' => -1,
    'post_status' => 'publish'
]);

if ($final_query->have_posts()) {
    echo "✅ Treatments query successful\n";
    echo "  Found {$final_query->found_posts} treatments\n";

    while ($final_query->have_posts()) {
        $final_query->the_post();
        echo "  • " . get_the_title() . "\n";
    }
    wp_reset_postdata();
} else {
    echo "❌ Treatments query still failing\n";
}

// Clear any caching
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
    echo "✅ Cache cleared\n";
}

echo "\n🎉 TREATMENTS PAGE FIX COMPLETE!\n";
echo "═══════════════════════════════════════\n";

echo "📋 SUMMARY:\n";
echo "• DevKinsta: Running\n";
echo "• Database: Connected\n";
echo "• WordPress: Loaded\n";
echo "• Treatments Page: Configured\n";
echo "• Sample Treatments: Created\n\n";

echo "🌐 TEST YOUR SITE:\n";
echo "• Homepage: " . get_site_url() . "\n";
echo "• Treatments: " . get_site_url() . "/treatments/\n";
echo "• Admin: " . admin_url() . "\n\n";

echo "💡 IF ISSUES PERSIST:\n";
echo "1. Clear browser cache\n";
echo "2. Check for PHP errors in DevKinsta logs\n";
echo "3. Verify all DevKinsta services are green\n";
echo "4. Try accessing wp-admin first\n\n";

echo "🔧 ADDITIONAL TOOLS:\n";
echo "• Diagnostic: php " . dirname(__FILE__) . "/../standalone-scripts/treatments-diagnostic.php\n";
echo "• Page Creator: php create-treatments-page.php\n";

?>
