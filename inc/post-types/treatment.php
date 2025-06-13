<?php
/**
 * Treatment Custom Post Type
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class TreatmentPostType {
    /**
     * Register the custom post type
     */
    public static function register() {
        register_post_type('treatment', [
            'labels' => [
                'name' => __('Treatments', 'medspa'),
                'singular_name' => __('Treatment', 'medspa'),
                'add_new' => __('Add New', 'medspa'),
                'add_new_item' => __('Add New Treatment', 'medspa'),
                'edit_item' => __('Edit Treatment', 'medspa'),
                'new_item' => __('New Treatment', 'medspa'),
                'view_item' => __('View Treatment', 'medspa'),
                'view_items' => __('View Treatments', 'medspa'),
                'search_items' => __('Search Treatments', 'medspa'),
                'not_found' => __('No treatments found', 'medspa'),
                'not_found_in_trash' => __('No treatments found in trash', 'medspa'),
                'all_items' => __('All Treatments', 'medspa'),
                'archives' => __('Treatment Archives', 'medspa'),
                'attributes' => __('Treatment Attributes', 'medspa'),
                'insert_into_item' => __('Insert into treatment', 'medspa'),
                'uploaded_to_this_item' => __('Uploaded to this treatment', 'medspa'),
                'featured_image' => __('Treatment Image', 'medspa'),
                'set_featured_image' => __('Set treatment image', 'medspa'),
                'remove_featured_image' => __('Remove treatment image', 'medspa'),
                'use_featured_image' => __('Use as treatment image', 'medspa'),
                'filter_items_list' => __('Filter treatments list', 'medspa'),
                'items_list_navigation' => __('Treatments list navigation', 'medspa'),
                'items_list' => __('Treatments list', 'medspa'),
                'item_published' => __('Treatment published', 'medspa'),
                'item_published_privately' => __('Treatment published privately', 'medspa'),
                'item_reverted_to_draft' => __('Treatment reverted to draft', 'medspa'),
                'item_scheduled' => __('Treatment scheduled', 'medspa'),
                'item_updated' => __('Treatment updated', 'medspa'),
            ],
            'public' => true,
            'hierarchical' => false,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'show_in_rest' => true,
            'menu_position' => 20,
            'menu_icon' => 'dashicons-heart',
            'capability_type' => 'post',
            'supports' => [
                'title',
                'editor',
                'thumbnail',
                'excerpt',
                'custom-fields',
                'revisions',
            ],
            'has_archive' => true,
            'rewrite' => [
                'slug' => 'treatments',
                'with_front' => true,
                'pages' => true,
                'feeds' => true,
            ],
            'delete_with_user' => false,
        ]);

        // Register treatment category taxonomy
        register_taxonomy('treatment_category', ['treatment'], [
            'labels' => [
                'name' => __('Treatment Categories', 'medspa'),
                'singular_name' => __('Treatment Category', 'medspa'),
                'search_items' => __('Search Treatment Categories', 'medspa'),
                'all_items' => __('All Treatment Categories', 'medspa'),
                'parent_item' => __('Parent Treatment Category', 'medspa'),
                'parent_item_colon' => __('Parent Treatment Category:', 'medspa'),
                'edit_item' => __('Edit Treatment Category', 'medspa'),
                'update_item' => __('Update Treatment Category', 'medspa'),
                'add_new_item' => __('Add New Treatment Category', 'medspa'),
                'new_item_name' => __('New Treatment Category Name', 'medspa'),
                'menu_name' => __('Categories', 'medspa'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'treatment-category'],
            'show_in_rest' => true,
        ]);

        // Register treatment features taxonomy
        register_taxonomy('treatment_feature', ['treatment'], [
            'labels' => [
                'name' => __('Treatment Features', 'medspa'),
                'singular_name' => __('Treatment Feature', 'medspa'),
                'search_items' => __('Search Treatment Features', 'medspa'),
                'all_items' => __('All Treatment Features', 'medspa'),
                'edit_item' => __('Edit Treatment Feature', 'medspa'),
                'update_item' => __('Update Treatment Feature', 'medspa'),
                'add_new_item' => __('Add New Treatment Feature', 'medspa'),
                'new_item_name' => __('New Treatment Feature Name', 'medspa'),
                'menu_name' => __('Features', 'medspa'),
            ],
            'hierarchical' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'treatment-feature'],
            'show_in_rest' => true,
        ]);

        // Register treatment benefits taxonomy
        register_taxonomy('treatment_benefit', ['treatment'], [
            'labels' => [
                'name' => __('Treatment Benefits', 'medspa'),
                'singular_name' => __('Treatment Benefit', 'medspa'),
                'search_items' => __('Search Treatment Benefits', 'medspa'),
                'all_items' => __('All Treatment Benefits', 'medspa'),
                'edit_item' => __('Edit Treatment Benefit', 'medspa'),
                'update_item' => __('Update Treatment Benefit', 'medspa'),
                'add_new_item' => __('Add New Treatment Benefit', 'medspa'),
                'new_item_name' => __('New Treatment Benefit Name', 'medspa'),
                'menu_name' => __('Benefits', 'medspa'),
            ],
            'hierarchical' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'treatment-benefit'],
            'show_in_rest' => true,
        ]);
    }

    /**
     * Register meta boxes for the treatment post type
     */
    public static function register_meta_boxes() {
        add_meta_box(
            'treatment_details',
            __('Treatment Details', 'medspa'),
            [self::class, 'render_details_meta_box'],
            'treatment',
            'normal',
            'high'
        );
    }

    /**
     * Render the treatment details meta box
     *
     * @param WP_Post $post The post object
     */
    public static function render_details_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('treatment_details', 'treatment_details_nonce');

        // Get saved values
        $subtitle = get_post_meta($post->ID, '_treatment_subtitle', true);
        $duration = get_post_meta($post->ID, '_treatment_duration', true);
        $price = get_post_meta($post->ID, '_treatment_price', true);
        $price_from = get_post_meta($post->ID, '_treatment_price_from', true);
        $pain_level = get_post_meta($post->ID, '_treatment_pain_level', true);
        $downtime = get_post_meta($post->ID, '_treatment_downtime', true);
        $results_timeline = get_post_meta($post->ID, '_treatment_results_timeline', true);
        $popularity = get_post_meta($post->ID, '_treatment_popularity', true);
        $cta_text = get_post_meta($post->ID, '_treatment_cta_text', true);
        $cta_url = get_post_meta($post->ID, '_treatment_cta_url', true);
        $book_text = get_post_meta($post->ID, '_treatment_book_text', true);
        $book_url = get_post_meta($post->ID, '_treatment_book_url', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="treatment_subtitle"><?php _e('Subtitle', 'medspa'); ?></label></th>
                <td>
                    <input type="text"
                           id="treatment_subtitle"
                           name="treatment_subtitle"
                           value="<?php echo esc_attr($subtitle); ?>"
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th><label for="treatment_duration"><?php _e('Duration', 'medspa'); ?></label></th>
                <td>
                    <input type="text"
                           id="treatment_duration"
                           name="treatment_duration"
                           value="<?php echo esc_attr($duration); ?>"
                           class="regular-text"
                           required>
                    <p class="description"><?php _e('e.g., "30-45 minutes"', 'medspa'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="treatment_price"><?php _e('Price', 'medspa'); ?></label></th>
                <td>
                    <input type="text"
                           id="treatment_price"
                           name="treatment_price"
                           value="<?php echo esc_attr($price); ?>"
                           class="regular-text"
                           required>
                    <label>
                        <input type="checkbox"
                               name="treatment_price_from"
                               value="1"
                               <?php checked($price_from, '1'); ?>>
                        <?php _e('Starting price', 'medspa'); ?>
                    </label>
                    <p class="description"><?php _e('e.g., "$599"', 'medspa'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="treatment_pain_level"><?php _e('Pain Level', 'medspa'); ?></label></th>
                <td>
                    <select id="treatment_pain_level" name="treatment_pain_level">
                        <option value=""><?php _e('Select pain level', 'medspa'); ?></option>
                        <option value="minimal" <?php selected($pain_level, 'minimal'); ?>><?php _e('Minimal', 'medspa'); ?></option>
                        <option value="mild" <?php selected($pain_level, 'mild'); ?>><?php _e('Mild', 'medspa'); ?></option>
                        <option value="moderate" <?php selected($pain_level, 'moderate'); ?>><?php _e('Moderate', 'medspa'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="treatment_downtime"><?php _e('Downtime', 'medspa'); ?></label></th>
                <td>
                    <input type="text"
                           id="treatment_downtime"
                           name="treatment_downtime"
                           value="<?php echo esc_attr($downtime); ?>"
                           class="regular-text">
                    <p class="description"><?php _e('e.g., "24-48 hours"', 'medspa'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="treatment_results_timeline"><?php _e('Results Timeline', 'medspa'); ?></label></th>
                <td>
                    <input type="text"
                           id="treatment_results_timeline"
                           name="treatment_results_timeline"
                           value="<?php echo esc_attr($results_timeline); ?>"
                           class="regular-text">
                    <p class="description"><?php _e('e.g., "7-14 days"', 'medspa'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="treatment_popularity"><?php _e('Popularity', 'medspa'); ?></label></th>
                <td>
                    <select id="treatment_popularity" name="treatment_popularity">
                        <option value=""><?php _e('Select popularity', 'medspa'); ?></option>
                        <option value="popular" <?php selected($popularity, 'popular'); ?>><?php _e('Popular', 'medspa'); ?></option>
                        <option value="trending" <?php selected($popularity, 'trending'); ?>><?php _e('Trending', 'medspa'); ?></option>
                        <option value="new" <?php selected($popularity, 'new'); ?>><?php _e('New', 'medspa'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="treatment_cta_text"><?php _e('CTA Text', 'medspa'); ?></label></th>
                <td>
                    <input type="text"
                           id="treatment_cta_text"
                           name="treatment_cta_text"
                           value="<?php echo esc_attr($cta_text); ?>"
                           class="regular-text">
                    <p class="description"><?php _e('e.g., "Learn More"', 'medspa'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="treatment_cta_url"><?php _e('CTA URL', 'medspa'); ?></label></th>
                <td>
                    <input type="url"
                           id="treatment_cta_url"
                           name="treatment_cta_url"
                           value="<?php echo esc_url($cta_url); ?>"
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th><label for="treatment_book_text"><?php _e('Book Button Text', 'medspa'); ?></label></th>
                <td>
                    <input type="text"
                           id="treatment_book_text"
                           name="treatment_book_text"
                           value="<?php echo esc_attr($book_text); ?>"
                           class="regular-text">
                    <p class="description"><?php _e('e.g., "Book Consultation"', 'medspa'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="treatment_book_url"><?php _e('Book URL', 'medspa'); ?></label></th>
                <td>
                    <input type="url"
                           id="treatment_book_url"
                           name="treatment_book_url"
                           value="<?php echo esc_url($book_url); ?>"
                           class="regular-text">
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save treatment details when the post is saved
     *
     * @param int $post_id The post ID
     */
    public static function save_details($post_id) {
        // Check if our nonce is set
        if (!isset($_POST['treatment_details_nonce'])) {
            return;
        }

        // Verify that the nonce is valid
        if (!wp_verify_nonce($_POST['treatment_details_nonce'], 'treatment_details')) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check the user's permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save the meta fields
        $meta_fields = [
            '_treatment_subtitle' => 'treatment_subtitle',
            '_treatment_duration' => 'treatment_duration',
            '_treatment_price' => 'treatment_price',
            '_treatment_price_from' => 'treatment_price_from',
            '_treatment_pain_level' => 'treatment_pain_level',
            '_treatment_downtime' => 'treatment_downtime',
            '_treatment_results_timeline' => 'treatment_results_timeline',
            '_treatment_popularity' => 'treatment_popularity',
            '_treatment_cta_text' => 'treatment_cta_text',
            '_treatment_cta_url' => 'treatment_cta_url',
            '_treatment_book_text' => 'treatment_book_text',
            '_treatment_book_url' => 'treatment_book_url',
        ];

        foreach ($meta_fields as $meta_key => $post_key) {
            if (isset($_POST[$post_key])) {
                $value = sanitize_text_field($_POST[$post_key]);
                update_post_meta($post_id, $meta_key, $value);
            }
        }
    }

    /**
     * Initialize the class
     */
    public static function init() {
        add_action('init', [self::class, 'register']);
        add_action('add_meta_boxes', [self::class, 'register_meta_boxes']);
        add_action('save_post_treatment', [self::class, 'save_details']);
    }
}
