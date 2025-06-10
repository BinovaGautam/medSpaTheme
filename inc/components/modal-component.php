<?php
/**
 * Modal Component Base Class
 *
 * Provides comprehensive modal/dialog functionality with accessibility compliance,
 * animation support, and seamless integration with the design token system.
 *
 * @package MedSpaTheme
 * @since 1.0.0
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * ModalComponent Class
 *
 * Base class for all modal/dialog components with WCAG 2.1 AA compliance,
 * smooth animations, and comprehensive interaction management.
 */
class ModalComponent extends BaseComponent {

    /**
     * Available modal sizes
     * @var array
     */
    protected $modal_sizes = ['small', 'medium', 'large', 'fullscreen'];

    /**
     * Available modal positions
     * @var array
     */
    protected $modal_positions = ['center', 'top', 'bottom'];

    /**
     * Available modal types
     * @var array
     */
    protected $modal_types = ['default', 'confirmation', 'alert', 'form', 'gallery'];

    /**
     * Animation configurations
     * @var array
     */
    protected $animations = [
        'fade' => ['enter' => 'fadeIn', 'exit' => 'fadeOut'],
        'scale' => ['enter' => 'scaleIn', 'exit' => 'scaleOut'],
        'slide' => ['enter' => 'slideDown', 'exit' => 'slideUp']
    ];

    /**
     * Modal state management
     * @var array
     */
    protected $modal_state = [
        'is_open' => false,
        'trigger_element' => null,
        'focus_restore_element' => null
    ];

    /**
     * Render the modal component
     *
     * @param array $args Component arguments
     * @return string HTML output
     */
    public function render($args = []) {
        $config = wp_parse_args($args, $this->config);

        // Validate and sanitize inputs
        $config = $this->sanitize_config($config);

        // Generate modal structure
        $modal_html = '';

        // Modal backdrop
        $modal_html .= $this->render_modal_backdrop($config);

        // Modal container
        $modal_html .= $this->render_modal_container($config);

        return $this->validate_performance($modal_html);
    }

    /**
     * Get WordPress Customizer controls
     *
     * @return array Customizer controls configuration
     */
    public function get_customizer_controls() {
        return [
            // Modal Container Styling
            'backdrop_color' => [
                'type' => 'color',
                'label' => 'Modal Backdrop Color',
                'description' => 'Color overlay behind modal',
                'default' => 'rgba(0, 0, 0, 0.6)'
            ],

            'backdrop_blur' => [
                'type' => 'range',
                'label' => 'Backdrop Blur Effect',
                'description' => 'Blur strength for backdrop',
                'default' => '4',
                'input_attrs' => [
                    'min' => '0',
                    'max' => '10',
                    'step' => '1'
                ],
                'sanitize_callback' => 'absint'
            ],

            'container_background' => [
                'type' => 'color',
                'label' => 'Modal Background Color',
                'description' => 'Modal container background',
                'default' => '#ffffff'
            ],

            'container_border_radius' => [
                'type' => 'range',
                'label' => 'Modal Border Radius',
                'description' => 'Roundness of modal corners',
                'default' => '12',
                'input_attrs' => [
                    'min' => '0',
                    'max' => '30',
                    'step' => '2'
                ],
                'sanitize_callback' => 'absint'
            ],

            'container_shadow' => [
                'type' => 'select',
                'label' => 'Modal Shadow',
                'description' => 'Drop shadow intensity',
                'default' => 'large',
                'choices' => [
                    'none' => 'No Shadow',
                    'small' => 'Small Shadow',
                    'medium' => 'Medium Shadow',
                    'large' => 'Large Shadow',
                    'extra-large' => 'Extra Large Shadow'
                ],
                'sanitize_callback' => 'sanitize_text_field'
            ],

            'container_padding' => [
                'type' => 'range',
                'label' => 'Modal Content Padding',
                'description' => 'Internal padding for modal content',
                'default' => '32',
                'input_attrs' => [
                    'min' => '16',
                    'max' => '64',
                    'step' => '4'
                ],
                'sanitize_callback' => 'absint'
            ],

            // Animation Settings
            'animation_type' => [
                'type' => 'select',
                'label' => 'Modal Animation',
                'description' => 'Modal entrance/exit animation',
                'default' => 'scale',
                'choices' => [
                    'fade' => 'Fade In/Out',
                    'scale' => 'Scale In/Out',
                    'slide' => 'Slide In/Out'
                ],
                'sanitize_callback' => 'sanitize_text_field'
            ],

            'animation_duration' => [
                'type' => 'range',
                'label' => 'Animation Duration',
                'description' => 'Speed of modal animations (milliseconds)',
                'default' => '300',
                'input_attrs' => [
                    'min' => '100',
                    'max' => '800',
                    'step' => '50'
                ],
                'sanitize_callback' => 'absint'
            ],

            // Header Styling
            'header_background' => [
                'type' => 'color',
                'label' => 'Modal Header Background',
                'description' => 'Header section background color',
                'default' => '#f8fafc'
            ],

            'header_border_color' => [
                'type' => 'color',
                'label' => 'Header Border Color',
                'description' => 'Color of header bottom border',
                'default' => '#e2e8f0'
            ],

            'title_font_size' => [
                'type' => 'range',
                'label' => 'Modal Title Font Size',
                'description' => 'Size of modal title text',
                'default' => '24',
                'input_attrs' => [
                    'min' => '18',
                    'max' => '36',
                    'step' => '2'
                ],
                'sanitize_callback' => 'absint'
            ],

            'title_font_weight' => [
                'type' => 'select',
                'label' => 'Title Font Weight',
                'description' => 'Weight of modal title text',
                'default' => '600',
                'choices' => [
                    '400' => 'Normal (400)',
                    '500' => 'Medium (500)',
                    '600' => 'Semi Bold (600)',
                    '700' => 'Bold (700)',
                    '800' => 'Extra Bold (800)'
                ],
                'sanitize_callback' => 'sanitize_text_field'
            ],

            // Close Button Styling
            'close_button_color' => [
                'type' => 'color',
                'label' => 'Close Button Color',
                'description' => 'Color of the close button',
                'default' => '#64748b'
            ],

            'close_button_hover_color' => [
                'type' => 'color',
                'label' => 'Close Button Hover Color',
                'description' => 'Close button color on hover',
                'default' => '#334155'
            ],

            'close_button_size' => [
                'type' => 'range',
                'label' => 'Close Button Size',
                'description' => 'Size of the close button',
                'default' => '24',
                'input_attrs' => [
                    'min' => '16',
                    'max' => '40',
                    'step' => '2'
                ],
                'sanitize_callback' => 'absint'
            ]
        ];
    }

    /**
     * Get default design tokens for modals
     *
     * @return array Design tokens
     */
    public function get_default_tokens() {
        return [
            // Backdrop styling
            'backdrop_color' => 'rgba(0, 0, 0, 0.6)',
            'backdrop_blur' => '4px',
            'backdrop_z_index' => '9998',

            // Container styling
            'container_background' => '#ffffff',
            'container_border_radius' => '12px',
            'container_shadow' => '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
            'container_border' => '1px solid #e2e8f0',
            'container_padding' => '32px',
            'container_max_width' => '600px',
            'container_max_height' => '90vh',
            'container_z_index' => '9999',

            // Animation settings
            'animation_duration' => '300ms',
            'animation_timing' => 'cubic-bezier(0.4, 0, 0.2, 1)',
            'animation_scale_enter' => '0.9',
            'animation_scale_exit' => '0.95',

            // Header styling
            'header_background' => '#f8fafc',
            'header_border_bottom' => '1px solid #e2e8f0',
            'header_padding' => '24px',
            'title_font_size' => '24px',
            'title_font_weight' => '600',
            'title_line_height' => '1.2',
            'title_color' => '#1e293b',

            // Content styling
            'content_padding' => '32px',
            'content_max_height' => '60vh',
            'content_overflow' => 'auto',

            // Footer styling
            'footer_background' => '#f8fafc',
            'footer_border_top' => '1px solid #e2e8f0',
            'footer_padding' => '24px',

            // Close button styling
            'close_button_size' => '24px',
            'close_button_color' => '#64748b',
            'close_button_hover_color' => '#334155',
            'close_button_background' => 'transparent',
            'close_button_hover_background' => '#f1f5f9',
            'close_button_border_radius' => '6px',
            'close_button_padding' => '8px',

            // Focus styling
            'focus_outline_color' => '#3b82f6',
            'focus_outline_width' => '3px',
            'focus_outline_offset' => '2px',

            // Medical spa specific
            'medical_accent_color' => '#059669',
            'medical_secondary_color' => '#0d9488',
            'medical_background' => '#ecfdf5'
        ];
    }

    /**
     * Get component default configuration
     *
     * @return array Default configuration
     */
    public function get_defaults() {
        return [
            // Modal identification
            'modal_id' => 'modal-' . uniqid(),
            'modal_type' => 'default',
            'modal_size' => 'medium',
            'modal_position' => 'center',

            // Content configuration
            'title' => '',
            'content' => '',
            'show_header' => true,
            'show_footer' => false,
            'show_close_button' => true,

            // Behavior configuration
            'close_on_backdrop' => true,
            'close_on_escape' => true,
            'trap_focus' => true,
            'restore_focus' => true,
            'auto_open' => false,
            'persistent' => false,

            // Animation configuration
            'animation_type' => 'scale',
            'animation_duration' => 300,
            'animation_easing' => 'cubic-bezier(0.4, 0, 0.2, 1)',

            // Trigger configuration
            'trigger_selector' => '',
            'trigger_event' => 'click',

            // Callback functions
            'on_open' => null,
            'on_close' => null,
            'on_confirm' => null,
            'on_cancel' => null,

            // Accessibility
            'aria_label' => '',
            'aria_describedby' => '',
            'aria_labelledby' => '',
            'role' => 'dialog',

            // Medical spa specific
            'medical_context' => false,
            'treatment_related' => false,
            'hipaa_compliant' => false,

            // Custom attributes
            'custom_classes' => [],
            'custom_attributes' => [],
            'data_attributes' => []
        ];
    }

    /**
     * Sanitize modal configuration
     *
     * @param array $config Configuration to sanitize
     * @return array Sanitized configuration
     */
    protected function sanitize_config($config) {
        // Sanitize basic fields
        $config['modal_id'] = sanitize_html_class($config['modal_id']);
        $config['modal_type'] = sanitize_text_field($config['modal_type']);
        $config['modal_size'] = sanitize_text_field($config['modal_size']);
        $config['modal_position'] = sanitize_text_field($config['modal_position']);
        $config['title'] = sanitize_text_field($config['title']);
        $config['animation_type'] = sanitize_text_field($config['animation_type']);

        // Sanitize content (allow some HTML)
        $allowed_html = [
            'p' => [],
            'br' => [],
            'strong' => [],
            'em' => [],
            'span' => ['class' => []],
            'div' => ['class' => []],
            'ul' => ['class' => []],
            'ol' => ['class' => []],
            'li' => ['class' => []],
            'a' => ['href' => [], 'class' => [], 'target' => []],
            'img' => ['src' => [], 'alt' => [], 'class' => [], 'width' => [], 'height' => []]
        ];
        $config['content'] = wp_kses($config['content'], $allowed_html);

        // Validate modal size
        if (!in_array($config['modal_size'], $this->modal_sizes)) {
            $config['modal_size'] = 'medium';
        }

        // Validate modal position
        if (!in_array($config['modal_position'], $this->modal_positions)) {
            $config['modal_position'] = 'center';
        }

        // Validate animation type
        if (!array_key_exists($config['animation_type'], $this->animations)) {
            $config['animation_type'] = 'scale';
        }

        // Sanitize boolean values
        $boolean_fields = [
            'show_header', 'show_footer', 'show_close_button',
            'close_on_backdrop', 'close_on_escape', 'trap_focus',
            'restore_focus', 'auto_open', 'persistent',
            'medical_context', 'treatment_related', 'hipaa_compliant'
        ];

        foreach ($boolean_fields as $field) {
            $config[$field] = (bool) $config[$field];
        }

        // Sanitize numeric values
        $config['animation_duration'] = absint($config['animation_duration']);
        if ($config['animation_duration'] < 100) {
            $config['animation_duration'] = 300;
        }

        // Sanitize arrays
        if (is_array($config['custom_classes'])) {
            $config['custom_classes'] = array_map('sanitize_html_class', $config['custom_classes']);
        }

        // Sanitize accessibility attributes
        $config['aria_label'] = sanitize_text_field($config['aria_label']);
        $config['aria_describedby'] = sanitize_text_field($config['aria_describedby']);
        $config['aria_labelledby'] = sanitize_text_field($config['aria_labelledby']);
        $config['role'] = sanitize_text_field($config['role']);

        return $config;
    }

    /**
     * Render modal backdrop
     *
     * @param array $config Modal configuration
     * @return string Backdrop HTML
     */
    protected function render_modal_backdrop($config) {
        $backdrop_classes = ['modal-backdrop'];
        if ($config['close_on_backdrop']) {
            $backdrop_classes[] = 'modal-backdrop-clickable';
        }

        $backdrop_attrs = [
            'class' => implode(' ', $backdrop_classes),
            'data-modal-backdrop' => $config['modal_id'],
            'aria-hidden' => 'true'
        ];

        return sprintf(
            '<div %s></div>',
            $this->build_attributes($backdrop_attrs)
        );
    }

    /**
     * Render modal container
     *
     * @param array $config Modal configuration
     * @return string Modal container HTML
     */
    protected function render_modal_container($config) {
        $container_classes = [
            'modal-container',
            'modal-' . $config['modal_type'],
            'modal-size-' . $config['modal_size'],
            'modal-position-' . $config['modal_position'],
            'modal-animation-' . $config['animation_type']
        ];

        // Add custom classes
        if (!empty($config['custom_classes'])) {
            $container_classes = array_merge($container_classes, $config['custom_classes']);
        }

        // Add medical spa classes
        if ($config['medical_context']) {
            $container_classes[] = 'modal-medical-context';
        }

        if ($config['treatment_related']) {
            $container_classes[] = 'modal-treatment-related';
        }

        $container_attrs = [
            'id' => $config['modal_id'],
            'class' => implode(' ', $container_classes),
            'role' => $config['role'],
            'aria-modal' => 'true',
            'aria-hidden' => 'true',
            'tabindex' => '-1',
            'data-modal-type' => $config['modal_type'],
            'data-animation-duration' => $config['animation_duration']
        ];

        // Add accessibility attributes
        if (!empty($config['aria_label'])) {
            $container_attrs['aria-label'] = $config['aria_label'];
        }

        if (!empty($config['aria_labelledby'])) {
            $container_attrs['aria-labelledby'] = $config['aria_labelledby'];
        }

        if (!empty($config['aria_describedby'])) {
            $container_attrs['aria-describedby'] = $config['aria_describedby'];
        }

        // Add custom attributes
        if (!empty($config['custom_attributes'])) {
            $container_attrs = array_merge($container_attrs, $config['custom_attributes']);
        }

        // Add data attributes
        if (!empty($config['data_attributes'])) {
            foreach ($config['data_attributes'] as $key => $value) {
                $container_attrs['data-' . $key] = $value;
            }
        }

        // Build modal content
        $modal_content = '';

        // Modal dialog wrapper
        $modal_content .= '<div class="modal-dialog" role="document">';

        // Modal header
        if ($config['show_header']) {
            $modal_content .= $this->render_modal_header($config);
        }

        // Modal body
        $modal_content .= $this->render_modal_body($config);

        // Modal footer
        if ($config['show_footer']) {
            $modal_content .= $this->render_modal_footer($config);
        }

        $modal_content .= '</div>'; // Close modal-dialog

        return sprintf(
            '<div %s>%s</div>',
            $this->build_attributes($container_attrs),
            $modal_content
        );
    }

    /**
     * Render modal header
     *
     * @param array $config Modal configuration
     * @return string Header HTML
     */
    protected function render_modal_header($config) {
        $header_html = '<div class="modal-header">';

        // Modal title
        if (!empty($config['title'])) {
            $title_id = $config['modal_id'] . '-title';
            $header_html .= sprintf(
                '<h3 class="modal-title" id="%s">%s</h3>',
                esc_attr($title_id),
                esc_html($config['title'])
            );

            // Update aria-labelledby if not set
            if (empty($config['aria_labelledby'])) {
                $config['aria_labelledby'] = $title_id;
            }
        }

        // Close button
        if ($config['show_close_button']) {
            $header_html .= $this->render_close_button($config);
        }

        $header_html .= '</div>';

        return $header_html;
    }

    /**
     * Render modal body/content
     *
     * @param array $config Modal configuration
     * @return string Body HTML
     */
    protected function render_modal_body($config) {
        $body_html = '<div class="modal-body">';

        if (!empty($config['content'])) {
            $body_html .= '<div class="modal-content-wrapper">';
            $body_html .= $config['content'];
            $body_html .= '</div>';
        }

        $body_html .= '</div>';

        return $body_html;
    }

    /**
     * Render modal footer
     *
     * @param array $config Modal configuration
     * @return string Footer HTML
     */
    protected function render_modal_footer($config) {
        $footer_html = '<div class="modal-footer">';

        // Add footer content hook for specialized modals
        $footer_content = apply_filters('modal_footer_content', '', $config);
        $footer_html .= $footer_content;

        $footer_html .= '</div>';

        return $footer_html;
    }

    /**
     * Render close button
     *
     * @param array $config Modal configuration
     * @return string Close button HTML
     */
    protected function render_close_button($config) {
        $close_button_attrs = [
            'type' => 'button',
            'class' => 'modal-close-button',
            'data-modal-close' => $config['modal_id'],
            'aria-label' => 'Close modal'
        ];

        return sprintf(
            '<button %s>
                <svg class="modal-close-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>',
            $this->build_attributes($close_button_attrs)
        );
    }

    /**
     * Build HTML attributes string
     *
     * @param array $attributes Attributes array
     * @return string Attributes string
     */
    protected function build_attributes($attributes) {
        $attr_strings = [];

        foreach ($attributes as $key => $value) {
            if ($value === null || $value === false) {
                continue;
            }

            if ($value === true || $value === '') {
                $attr_strings[] = esc_attr($key);
            } else {
                $attr_strings[] = sprintf('%s="%s"', esc_attr($key), esc_attr($value));
            }
        }

        return implode(' ', $attr_strings);
    }

    /**
     * Get accessibility configuration
     *
     * @return array Accessibility configuration
     */
    protected function get_accessibility_config() {
        return [
            'aria_live' => 'polite',
            'aria_atomic' => true,
            'keyboard_navigation' => true,
            'focus_trap' => true,
            'skip_links' => true,
            'high_contrast' => true,
            'reduced_motion' => true,
            'screen_reader_support' => true
        ];
    }

    /**
     * Get component-specific tokens from customizer
     *
     * @return array Component tokens
     */
    protected function get_component_specific_tokens() {
        $customizer_values = [];

        // Get modal customizer values
        $modal_controls = $this->get_customizer_controls();

        foreach ($modal_controls as $control_id => $control_config) {
            $setting_id = $this->component_name . '_' . $control_id;
            $value = get_theme_mod($setting_id, $control_config['default'] ?? '');

            if (!empty($value)) {
                $customizer_values['modal_' . $control_id] = $value;
            }
        }

        return $customizer_values;
    }
}
