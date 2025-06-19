<?php
/**
 * Unsplash Browser Control - Custom WordPress Customizer Control
 *
 * Provides a custom control for browsing and selecting Unsplash images
 * with drag & drop functionality, alt text editing, and bulk management.
 *
 * @package MedSpaTheme
 * @subpackage Customizer
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Unsplash Browser Control Class
 */
class Unsplash_Browser_Control extends WP_Customize_Control {

    /**
     * Control type
     * @var string
     */
    public $type = 'unsplash_browser';

    /**
     * Gallery type (before_after_gallery, single_image, etc.)
     * @var string
     */
    public $gallery_type = 'single_image';

    /**
     * Enqueue control scripts and styles
     */
    public function enqueue() {
        wp_enqueue_script(
            'unsplash-browser-control',
            get_template_directory_uri() . '/assets/js/customizer/unsplash-browser-control.js',
            ['jquery', 'jquery-ui-sortable', 'customize-controls'],
            '1.0.0',
            true
        );

        wp_enqueue_style(
            'unsplash-browser-control',
            get_template_directory_uri() . '/assets/css/customizer/unsplash-browser-control.css',
            ['customize-controls'],
            '1.0.0'
        );
    }

    /**
     * Render the control's content
     */
    public function render_content() {
        $value = $this->value();
        $gallery_type = isset($this->input_attrs['type']) ? $this->input_attrs['type'] : $this->gallery_type;

        ?>
        <label>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php if (!empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
            <?php endif; ?>
        </label>

        <div class="unsplash-browser-wrapper" data-gallery-type="<?php echo esc_attr($gallery_type); ?>">

            <!-- Search Interface -->
            <div class="unsplash-search-section">
                <div class="search-controls">
                    <input type="text"
                           class="unsplash-search-input"
                           placeholder="<?php esc_attr_e('Search Unsplash images...', 'medspatheme'); ?>" />
                    <button type="button" class="button unsplash-search-btn">
                        <?php esc_html_e('Search', 'medspatheme'); ?>
                    </button>
                    <button type="button" class="button unsplash-browse-btn">
                        <?php esc_html_e('Browse Collections', 'medspatheme'); ?>
                    </button>
                </div>

                <div class="collection-filters">
                    <button type="button" class="collection-filter active" data-collection="medical_spa">
                        <?php esc_html_e('Medical Spa', 'medspatheme'); ?>
                    </button>
                    <button type="button" class="collection-filter" data-collection="beauty_treatments">
                        <?php esc_html_e('Beauty Treatments', 'medspatheme'); ?>
                    </button>
                    <button type="button" class="collection-filter" data-collection="wellness">
                        <?php esc_html_e('Wellness', 'medspatheme'); ?>
                    </button>
                    <button type="button" class="collection-filter" data-collection="before_after">
                        <?php esc_html_e('Before/After', 'medspatheme'); ?>
                    </button>
                </div>
            </div>

            <!-- Search Results -->
            <div class="unsplash-results-section">
                <div class="results-header">
                    <span class="results-count"></span>
                    <div class="results-pagination">
                        <button type="button" class="button prev-page" disabled>&laquo;</button>
                        <span class="page-info">1 / 1</span>
                        <button type="button" class="button next-page" disabled>&raquo;</button>
                    </div>
                </div>

                <div class="unsplash-results-grid">
                    <div class="loading-placeholder">
                        <span class="spinner is-active"></span>
                        <p><?php esc_html_e('Loading images...', 'medspatheme'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Selected Images Management -->
            <div class="selected-images-section">
                <h4><?php esc_html_e('Selected Images', 'medspatheme'); ?></h4>
                <p class="drag-drop-info">
                    <?php esc_html_e('Drag and drop to reorder. Click to edit details.', 'medspatheme'); ?>
                </p>

                <div class="selected-images-container" data-gallery-type="<?php echo esc_attr($gallery_type); ?>">
                    <?php $this->render_selected_images($value, $gallery_type); ?>
                </div>

                <div class="bulk-actions">
                    <button type="button" class="button remove-all-btn">
                        <?php esc_html_e('Remove All', 'medspatheme'); ?>
                    </button>
                    <button type="button" class="button optimize-all-btn">
                        <?php esc_html_e('Optimize All', 'medspatheme'); ?>
                    </button>
                </div>
            </div>

        </div>

        <!-- Hidden input for storing the value -->
        <input type="hidden"
               class="unsplash-browser-value"
               <?php $this->link(); ?>
               value="<?php echo esc_attr($value); ?>" />

        <!-- Image Edit Modal -->
        <div class="image-edit-modal" style="display: none;">
            <div class="modal-backdrop"></div>
            <div class="modal-content">
                <div class="modal-header">
                    <h3><?php esc_html_e('Edit Image Details', 'medspatheme'); ?></h3>
                    <button type="button" class="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="image-preview">
                        <img src="" alt="" class="preview-image" />
                    </div>
                    <div class="image-fields">
                        <label>
                            <?php esc_html_e('Alt Text (Required)', 'medspatheme'); ?>
                            <textarea class="alt-text-field" rows="3" required></textarea>
                            <span class="field-description">
                                <?php esc_html_e('Describe the image for accessibility and SEO', 'medspatheme'); ?>
                            </span>
                        </label>

                        <div class="conditional-fields">
                            <!-- Fields that appear based on gallery type -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button button-secondary modal-cancel">
                        <?php esc_html_e('Cancel', 'medspatheme'); ?>
                    </button>
                    <button type="button" class="button button-primary modal-save">
                        <?php esc_html_e('Save Changes', 'medspatheme'); ?>
                    </button>
                </div>
            </div>
        </div>

        <?php
    }

    /**
     * Render selected images based on gallery type
     */
    private function render_selected_images($value, $gallery_type) {
        $data = json_decode($value, true);
        if (!$data) $data = [];

        switch ($gallery_type) {
            case 'before_after_gallery':
                $this->render_before_after_images($data);
                break;
            case 'single_image':
                $this->render_single_image($data);
                break;
            case 'treatment_gallery':
                $this->render_treatment_gallery($data);
                break;
            case 'transformation_gallery':
                $this->render_transformation_gallery($data);
                break;
            case 'experience_gallery':
                $this->render_experience_gallery($data);
                break;
            default:
                $this->render_default_gallery($data);
                break;
        }
    }

    /**
     * Render before/after gallery images
     */
    private function render_before_after_images($data) {
        if (empty($data)) {
            echo '<div class="no-images-placeholder">';
            echo '<p>' . esc_html__('No before/after images selected. Use the search above to add images.', 'medspatheme') . '</p>';
            echo '</div>';
            return;
        }

        echo '<div class="before-after-pairs sortable-container">';
        foreach ($data as $index => $pair) {
            $before_url = 'https://images.unsplash.com/' . $pair['before']['unsplash_id'] . '?w=150&h=100&fit=crop';
            $after_url = 'https://images.unsplash.com/' . $pair['after']['unsplash_id'] . '?w=150&h=100&fit=crop';

            echo '<div class="before-after-pair sortable-item" data-index="' . esc_attr($index) . '">';
            echo '<div class="pair-header">';
            echo '<span class="pair-title">' . esc_html($pair['treatment']) . '</span>';
            echo '<div class="pair-actions">';
            echo '<button type="button" class="edit-pair-btn" title="' . esc_attr__('Edit', 'medspatheme') . '">✏️</button>';
            echo '<button type="button" class="remove-pair-btn" title="' . esc_attr__('Remove', 'medspatheme') . '">🗑️</button>';
            echo '<span class="drag-handle" title="' . esc_attr__('Drag to reorder', 'medspatheme') . '">⋮⋮</span>';
            echo '</div>';
            echo '</div>';

            echo '<div class="pair-images">';
            echo '<div class="before-container">';
            echo '<img src="' . esc_url($before_url) . '" alt="' . esc_attr($pair['before']['alt']) . '" />';
            echo '<span class="image-label">' . esc_html__('Before', 'medspatheme') . '</span>';
            echo '</div>';
            echo '<div class="after-container">';
            echo '<img src="' . esc_url($after_url) . '" alt="' . esc_attr($pair['after']['alt']) . '" />';
            echo '<span class="image-label">' . esc_html__('After', 'medspatheme') . '</span>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }

    /**
     * Render single image
     */
    private function render_single_image($data) {
        if (empty($data)) {
            echo '<div class="no-images-placeholder">';
            echo '<p>' . esc_html__('No image selected. Use the search above to select an image.', 'medspatheme') . '</p>';
            echo '</div>';
            return;
        }

        $image_url = 'https://images.unsplash.com/' . $data . '?w=300&h=200&fit=crop';

        echo '<div class="single-image-container">';
        echo '<div class="single-image-item">';
        echo '<img src="' . esc_url($image_url) . '" alt="" />';
        echo '<div class="image-actions">';
        echo '<button type="button" class="button edit-image-btn">' . esc_html__('Edit Details', 'medspatheme') . '</button>';
        echo '<button type="button" class="button remove-image-btn">' . esc_html__('Remove', 'medspatheme') . '</button>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    /**
     * Render treatment gallery
     */
    private function render_treatment_gallery($data) {
        if (empty($data)) {
            echo '<div class="no-images-placeholder">';
            echo '<p>' . esc_html__('No treatment images selected. Use the search above to add images.', 'medspatheme') . '</p>';
            echo '</div>';
            return;
        }

        echo '<div class="treatment-gallery sortable-container">';
        foreach ($data as $index => $treatment) {
            $image_url = 'https://images.unsplash.com/' . $treatment['image_id'] . '?w=150&h=100&fit=crop';

            echo '<div class="treatment-item sortable-item" data-index="' . esc_attr($index) . '">';
            echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($treatment['alt']) . '" />';
            echo '<div class="treatment-info">';
            echo '<h5>' . esc_html($treatment['name']) . '</h5>';
            echo '<p>' . esc_html($treatment['caption']) . '</p>';
            echo '</div>';
            echo '<div class="treatment-actions">';
            echo '<button type="button" class="edit-treatment-btn" title="' . esc_attr__('Edit', 'medspatheme') . '">✏️</button>';
            echo '<button type="button" class="remove-treatment-btn" title="' . esc_attr__('Remove', 'medspatheme') . '">🗑️</button>';
            echo '<span class="drag-handle" title="' . esc_attr__('Drag to reorder', 'medspatheme') . '">⋮⋮</span>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }

    /**
     * Render transformation gallery
     */
    private function render_transformation_gallery($data) {
        if (empty($data)) {
            echo '<div class="no-images-placeholder">';
            echo '<p>' . esc_html__('No transformation images selected. Use the search above to add images.', 'medspatheme') . '</p>';
            echo '</div>';
            return;
        }

        echo '<div class="transformation-gallery sortable-container">';
        foreach ($data as $index => $transformation) {
            $before_url = 'https://images.unsplash.com/' . $transformation['before_id'] . '?w=120&h=80&fit=crop';
            $after_url = 'https://images.unsplash.com/' . $transformation['after_id'] . '?w=120&h=80&fit=crop';

            echo '<div class="transformation-item sortable-item" data-index="' . esc_attr($index) . '">';
            echo '<div class="transformation-header">';
            echo '<span class="transformation-title">' . esc_html($transformation['treatment']) . '</span>';
            echo '<div class="transformation-actions">';
            echo '<button type="button" class="edit-transformation-btn" title="' . esc_attr__('Edit', 'medspatheme') . '">✏️</button>';
            echo '<button type="button" class="remove-transformation-btn" title="' . esc_attr__('Remove', 'medspatheme') . '">🗑️</button>';
            echo '<span class="drag-handle" title="' . esc_attr__('Drag to reorder', 'medspatheme') . '">⋮⋮</span>';
            echo '</div>';
            echo '</div>';

            echo '<div class="transformation-images">';
            echo '<div class="before-container">';
            echo '<img src="' . esc_url($before_url) . '" alt="' . esc_attr($transformation['before_alt']) . '" />';
            echo '<span class="image-label">' . esc_html__('Before', 'medspatheme') . '</span>';
            echo '</div>';
            echo '<div class="after-container">';
            echo '<img src="' . esc_url($after_url) . '" alt="' . esc_attr($transformation['after_alt']) . '" />';
            echo '<span class="image-label">' . esc_html__('After', 'medspatheme') . '</span>';
            echo '</div>';
            echo '</div>';
            echo '<div class="transformation-timeframe">';
            echo '<span>' . esc_html($transformation['timeframe']) . '</span>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }

    /**
     * Render experience gallery
     */
    private function render_experience_gallery($data) {
        if (empty($data)) {
            echo '<div class="no-images-placeholder">';
            echo '<p>' . esc_html__('No experience images selected. Use the search above to add images.', 'medspatheme') . '</p>';
            echo '</div>';
            return;
        }

        echo '<div class="experience-gallery sortable-container">';
        foreach ($data as $index => $space) {
            $image_url = 'https://images.unsplash.com/' . $space['image_id'] . '?w=150&h=100&fit=crop';

            echo '<div class="experience-item sortable-item" data-index="' . esc_attr($index) . '">';
            echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($space['alt']) . '" />';
            echo '<div class="experience-info">';
            echo '<h5>' . esc_html($space['name']) . '</h5>';
            echo '<p>' . esc_html($space['caption']) . '</p>';
            echo '</div>';
            echo '<div class="experience-actions">';
            echo '<button type="button" class="edit-experience-btn" title="' . esc_attr__('Edit', 'medspatheme') . '">✏️</button>';
            echo '<button type="button" class="remove-experience-btn" title="' . esc_attr__('Remove', 'medspatheme') . '">🗑️</button>';
            echo '<span class="drag-handle" title="' . esc_attr__('Drag to reorder', 'medspatheme') . '">⋮⋮</span>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }

    /**
     * Render default gallery fallback
     */
    private function render_default_gallery($data) {
        echo '<div class="no-images-placeholder">';
        echo '<p>' . esc_html__('Gallery type not recognized. Please check the control configuration.', 'medspatheme') . '</p>';
        echo '</div>';
    }
}
