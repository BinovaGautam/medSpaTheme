/**
 * Add admin page for fixing routing issues
 */
function preetidreams_add_routing_fix_admin_page() {
    add_theme_page(
        'Fix Navigation Routing',
        'Fix Navigation',
        'manage_options',
        'preetidreams-fix-routing',
        'preetidreams_routing_fix_page'
    );
}
add_action('admin_menu', 'preetidreams_add_routing_fix_admin_page');

/**
 * Routing fix admin page
 */
function preetidreams_routing_fix_page() {
    $messages = array();

    if (isset($_POST['fix_routing']) && check_admin_referer('preetidreams_fix_routing')) {
        // 1. Check and fix permalink structure
        $permalink_structure = get_option('permalink_structure');
        if (empty($permalink_structure)) {
            update_option('permalink_structure', '/%postname%/');
            $messages[] = '✅ Fixed permalink structure (set to Post name)';
        } else {
            $messages[] = '✅ Permalink structure is already correct';
        }

        // 2. Create essential pages
        preetidreams_create_essential_pages();
        $messages[] = '✅ Essential pages checked/created';

        // 3. Force rewrite rules flush
        delete_option('rewrite_rules');
        flush_rewrite_rules(true);
        $messages[] = '✅ Rewrite rules flushed completely';

        // 4. Update the option to force flush on next load
        update_option('preetidreams_force_rewrite_flush', true);
        $messages[] = '✅ Forced rewrite refresh scheduled';

        $messages[] = '<strong>🎉 Routing fix complete! Please test your navigation now.</strong>';
    }

    // Check current status
    $permalink_structure = get_option('permalink_structure');
    $pages_exist = array();
    $essential_pages = array('treatments', 'about', 'contact', 'testimonials');

    foreach ($essential_pages as $page_slug) {
        $page = get_page_by_path($page_slug);
        $pages_exist[$page_slug] = $page ? $page->ID : false;
    }

    ?>
    <div class="wrap">
        <h1>🔧 Fix Navigation Routing</h1>

        <?php if (!empty($messages)): ?>
            <div class="notice notice-success">
                <?php foreach ($messages as $message): ?>
                    <p><?php echo $message; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <h2>Current Status</h2>

            <h3>📋 Permalink Structure</h3>
            <p><strong>Current:</strong> <?php echo $permalink_structure ?: 'Default (⚠️ This causes routing issues!)'; ?></p>

            <h3>📄 Essential Pages Status</h3>
            <ul>
                <?php foreach ($pages_exist as $slug => $page_id): ?>
                    <li>
                        <strong><?php echo ucfirst($slug); ?>:</strong>
                        <?php if ($page_id): ?>
                            ✅ Exists (ID: <?php echo $page_id; ?>) -
                            <a href="<?php echo home_url('/' . $slug . '/'); ?>" target="_blank">View Page</a> |
                            <a href="<?php echo admin_url('post.php?post=' . $page_id . '&action=edit'); ?>">Edit</a>
                        <?php else: ?>
                            ❌ Missing
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <h3>🔄 Rewrite Rules</h3>
            <?php
            $rewrite_rules = get_option('rewrite_rules');
            if ($rewrite_rules && is_array($rewrite_rules)):
            ?>
                <p>✅ Rewrite rules exist (<?php echo count($rewrite_rules); ?> total rules)</p>
            <?php else: ?>
                <p>❌ No rewrite rules found</p>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2>🛠️ Fix Routing Issues</h2>
            <p>If your navigation links are showing the home page instead of the correct pages, click the button below to fix all routing issues automatically.</p>

            <form method="post" action="">
                <?php wp_nonce_field('preetidreams_fix_routing'); ?>
                <p>
                    <input type="submit" name="fix_routing" class="button button-primary button-large"
                           value="🔧 Fix All Routing Issues"
                           onclick="return confirm('This will fix permalink structure, create missing pages, and flush rewrite rules. Continue?')">
                </p>
            </form>

            <h3>📝 Manual Steps (Alternative)</h3>
            <ol>
                <li><strong>Go to <a href="<?php echo admin_url('options-permalink.php'); ?>">Settings > Permalinks</a></strong></li>
                <li><strong>Select "Post name" permalink structure</strong> (if not already selected)</li>
                <li><strong>Click "Save Changes"</strong> (this flushes rewrite rules)</li>
                <li><strong>Test your navigation links</strong></li>
            </ol>
        </div>

        <div class="card">
            <h2>🧪 Quick Tests</h2>
            <p>After fixing, test these links:</p>
            <ul>
                <li><a href="<?php echo home_url('/treatments/'); ?>" target="_blank">Treatments Page</a></li>
                <li><a href="<?php echo home_url('/about/'); ?>" target="_blank">About Page</a></li>
                <li><a href="<?php echo home_url('/contact/'); ?>" target="_blank">Contact Page</a></li>
                <li><a href="<?php echo home_url('/testimonials/'); ?>" target="_blank">Testimonials Page</a></li>
            </ul>
        </div>
    </div>

    <style>
    .card {
        background: #fff;
        border: 1px solid #ccd0d4;
        border-radius: 4px;
        padding: 20px;
        margin: 20px 0;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
    }
    .card h2 {
        margin-top: 0;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }
    </style>
    <?php
}

/**
 * Force rewrite rules flush on next page load if needed
 */
function preetidreams_maybe_force_rewrite_flush() {
    if (get_option('preetidreams_force_rewrite_flush')) {
        flush_rewrite_rules(true);
        delete_option('preetidreams_force_rewrite_flush');
    }
}
add_action('init', 'preetidreams_maybe_force_rewrite_flush', 99);
