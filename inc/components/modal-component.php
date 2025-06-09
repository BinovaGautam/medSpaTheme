<?php
/**
 * Modal Component Base Class
 *
 * Extends BaseComponent with modal-specific functionality including
 * backdrop management, keyboard navigation, accessibility compliance, and animation system.
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
 * Base class for all modal components with comprehensive functionality
 * for accessibility, keyboard navigation, and medical spa specializations.
 */
class ModalComponent extends BaseComponent {

    /**
     * Available modal types
     * @var array
     */
    protected $modal_types = ['basic', 'confirmation', 'form', 'gallery', 'info', 'booking', 'treatment'];

    /**
     * Available modal sizes
     * @var array
     */
    protected $sizes = ['small', 'medium', 'large', 'fullscreen'];

    /**
     * Available modal positions
     * @var array
     */
    protected $positions = ['center', 'top', 'bottom', 'left', 'right'];

    /**
     * Modal content data
     * @var array
     */
    protected $modal_content = [];

    /**
     * Modal state management
     * @var array
     */
    protected $modal_state = [
        'is_open' => false,
        'has_backdrop' => true,
        'close_on_backdrop' => true,
        'close_on_escape' => true,
        'trap_focus' => true
    ];

    /**
     * Animation settings
     * @var array
     */
    protected $animation_config = [
        'duration' => 300,
        'easing' => 'cubic-bezier(0.4, 0, 0.2, 1)',
        'scale_enter' => 0.9,
        'scale_exit' => 0.95
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

        // Generate modal attributes
        $modal_attributes = $this->generate_modal_attributes($config);

        // Generate modal content
        $modal_content = $this->generate_modal_content($config);

        // Render complete modal HTML
        $html = $this->render_modal_html($modal_attributes, $modal_content, $config);

        return $this->validate_performance($html);
    }

    /**
     * Get WordPress Customizer controls for modals
     *
     * @return array Customizer controls configuration
     */
    public function get_customizer_controls() {
        return [
            // Modal backdrop styling
            'backdrop_color' => [
                'type' => 'color',
                'label' => 'Modal Backdrop Color',
                'description' => 'Background overlay color for modals',
                'default' => 'rgba(0, 0, 0, 0.6)',
                'sanitize_callback' => 'sanitize_text_field'
            ],

            'backdrop_blur' => [
                'type' => 'range',
                'label' => 'Backdrop Blur Amount',
                'description' => 'Blur effect strength for modal backdrop',
                'default' => '4',
                'input_attrs' => [
                    'min' => '0',
                    'max' => '10',
                    'step' => '1'
                ],
                'sanitize_callback' => 'absint'
            ],

            // Modal container styling
            'container_background_color' => [
                'type' => 'color',
                'label' => 'Modal Background Color',
                'description' => 'Background color for modal container',
                'default' => '#ffffff',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            'container_border_radius' => [
                'type' => 'range',
                'label' => 'Modal Border Radius',
                'description' => 'Roundness of modal container corners',
                'default' => '12',
                'input_attrs' => [
                    'min' => '0',
                    'max' => '30',
                    'step' => '2'
                ],
                'sanitize_callback' => 'absint'
            ],

            'container_padding' => [
                'type' => 'range',
                'label' => 'Modal Padding',
                'description' => 'Internal spacing within modal container',
                'default' => '32',
                'input_attrs' => [
                    'min' => '16',
                    'max' => '64',
                    'step' => '4'
                ],
                'sanitize_callback' => 'absint'
            ],

            'container_shadow' => [
                'type' => 'select',
                'label' => 'Modal Shadow',
                'description' => 'Shadow effect for modal container',
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

            // Modal sizing
            'small_width' => [
                'type' => 'range',
                'label' => 'Small Modal Width',
                'description' => 'Width for small modal size',
                'default' => '400',
                'input_attrs' => [
                    'min' => '300',
                    'max' => '500',
                    'step' => '10'
                ],
                'sanitize_callback' => 'absint'
            ],

            'medium_width' => [
                'type' => 'range',
                'label' => 'Medium Modal Width',
                'description' => 'Width for medium modal size',
                'default' => '600',
                'input_attrs' => [
                    'min' => '500',
                    'max' => '800',
                    'step' => '25'
                ],
                'sanitize_callback' => 'absint'
            ],

            'large_width' => [
                'type' => 'range',
                'label' => 'Large Modal Width',
                'description' => 'Width for large modal size',
                'default' => '900',
                'input_attrs' => [
                    'min' => '700',
                    'max' => '1200',
                    'step' => '50'
                ],
                'sanitize_callback' => 'absint'
            ],

            // Animation settings
            'animation_duration' => [
                'type' => 'range',
                'label' => 'Animation Duration',
                'description' => 'Speed of modal open/close animations',
                'default' => '300',
                'input_attrs' => [
                    'min' => '100',
                    'max' => '800',
                    'step' => '50'
                ],
                'sanitize_callback' => 'absint'
            ],

            'animation_easing' => [
                'type' => 'select',
                'label' => 'Animation Easing',
                'description' => 'Easing function for modal animations',
                'default' => 'ease-out',
                'choices' => [
                    'ease' => 'Ease',
                    'ease-in' => 'Ease In',
                    'ease-out' => 'Ease Out',
                    'ease-in-out' => 'Ease In Out',
                    'cubic-bezier' => 'Custom (Cubic Bezier)'
                ],
                'sanitize_callback' => 'sanitize_text_field'
            ],

            // Modal header styling
            'header_background_color' => [
                'type' => 'color',
                'label' => 'Header Background Color',
                'description' => 'Background color for modal header',
                'default' => '#f9fafb',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            'header_text_color' => [
                'type' => 'color',
                'label' => 'Header Text Color',
                'description' => 'Text color for modal header',
                'default' => '#111827',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            'header_border_color' => [
                'type' => 'color',
                'label' => 'Header Border Color',
                'description' => 'Border color for modal header',
                'default' => '#e5e7eb',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            // Close button styling
            'close_button_color' => [
                'type' => 'color',
                'label' => 'Close Button Color',
                'description' => 'Color for modal close button',
                'default' => '#6b7280',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            'close_button_hover_color' => [
                'type' => 'color',
                'label' => 'Close Button Hover Color',
                'description' => 'Hover color for modal close button',
                'default' => '#374151',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            // Behavior settings
            'close_on_backdrop' => [
                'type' => 'checkbox',
                'label' => 'Close on Backdrop Click',
                'description' => 'Allow closing modal by clicking backdrop',
                'default' => true
            ],

            'close_on_escape' => [
                'type' => 'checkbox',
                'label' => 'Close on Escape Key',
                'description' => 'Allow closing modal with Escape key',
                'default' => true
            ],

            'trap_focus' => [
                'type' => 'checkbox',
                'label' => 'Focus Trapping',
                'description' => 'Trap keyboard focus within modal',
                'default' => true
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
            // Modal backdrop
            'backdrop_color' => 'rgba(0, 0, 0, 0.6)',
            'backdrop_blur' => '4px',
            'backdrop_z_index' => '1000',

            // Modal container
            'container_background' => 'var(--color-surface-primary, #ffffff)',
            'container_border' => 'var(--border-subtle, 1px solid #e5e7eb)',
            'container_border_radius' => 'var(--border-radius-lg, 12px)',
            'container_shadow' => 'var(--shadow-2xl, 0 25px 50px -12px rgba(0, 0, 0, 0.25))',
            'container_padding' => 'var(--spacing-8, 32px)',
            'container_max_width' => '90vw',
            'container_max_height' => '90vh',

            // Modal sizing
            'small_width' => '400px',
            'medium_width' => '600px',
            'large_width' => '900px',
            'fullscreen_width' => '100vw',
            'fullscreen_height' => '100vh',

            // Modal animation
            'transition_duration' => 'var(--transition-duration-default, 0.3s)',
            'animation_easing' => 'cubic-bezier(0.4, 0, 0.2, 1)',
            'scale_enter' => '0.9',
            'scale_exit' => '0.95',
            'opacity_enter' => '0',
            'opacity_exit' => '1',

            // Modal header
            'header_background' => 'var(--color-surface-secondary, #f9fafb)',
            'header_border' => 'var(--border-subtle, 1px solid #e5e7eb)',
            'header_padding' => 'var(--spacing-6, 24px)',
            'header_text_color' => 'var(--color-text-primary, #111827)',
            'header_font_size' => 'var(--font-size-lg, 18px)',
            'header_font_weight' => 'var(--font-weight-semibold, 600)',

            // Modal content
            'content_padding' => 'var(--spacing-6, 24px)',
            'content_max_height' => '70vh',
            'content_overflow' => 'auto',
            'content_text_color' => 'var(--color-text-primary, #111827)',

            // Modal footer
            'footer_background' => 'var(--color-surface-tertiary, #f3f4f6)',
            'footer_border' => 'var(--border-subtle, 1px solid #e5e7eb)',
            'footer_padding' => 'var(--spacing-6, 24px)',
            'footer_text_align' => 'right',

            // Close button
            'close_button_size' => '24px',
            'close_button_color' => 'var(--color-text-tertiary, #6b7280)',
            'close_button_hover_color' => 'var(--color-text-secondary, #374151)',
            'close_button_focus_color' => 'var(--color-primary-500, #3b82f6)',

            // Responsive breakpoints
            'mobile_padding' => 'var(--spacing-4, 16px)',
            'mobile_border_radius' => 'var(--border-radius-md, 8px)',
            'tablet_max_width' => '85vw',
            'desktop_max_width' => '80vw'
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
            'modal_id' => '',
            'modal_class' => '',
            'modal_type' => 'basic',
            'modal_size' => 'medium',
            'modal_position' => 'center',

            // Modal content
            'title' => '',
            'content' => '',
            'footer_content' => '',
            'close_button_text' => '×',

            // Modal behavior
            'is_open' => false,
            'has_backdrop' => true,
            'close_on_backdrop' => true,
            'close_on_escape' => true,
            'trap_focus' => true,
            'auto_close_delay' => 0,

            // Animation settings
            'animation_enabled' => true,
            'animation_duration' => 300,
            'animation_easing' => 'cubic-bezier(0.4, 0, 0.2, 1)',

            // Accessibility
            'aria_label' => '',
            'aria_describedby' => '',
            'role' => 'dialog',
            'focus_on_open' => true,
            'restore_focus' => true,

            // JavaScript settings
            'auto_init' => true,
            'trigger_selector' => '',
            'close_callback' => '',
            'open_callback' => '',

            // Custom attributes
            'custom_attributes' => []
        ];
    }

    /**
     * Render modal component with configuration
     *
     * @param array $args Component arguments
     * @return string Modal HTML
     */
    public function render_modal($args = []) {
        $config = wp_parse_args($args, $this->get_defaults());
        return $this->render($config);
    }

    /**
     * Create specialized booking modal
     *
     * @param array $args Booking modal arguments
     * @return string Modal HTML
     */
    public function create_booking_modal($args = []) {
        $booking_config = array_merge($args, [
            'modal_type' => 'booking',
            'modal_class' => 'modal-booking',
            'modal_size' => 'large',
            'title' => $args['title'] ?? 'Book Consultation',
            'aria_label' => 'Consultation booking modal'
        ]);

        return $this->render_modal($booking_config);
    }

    /**
     * Create specialized confirmation modal
     *
     * @param array $args Confirmation modal arguments
     * @return string Modal HTML
     */
    public function create_confirmation_modal($args = []) {
        $confirmation_config = array_merge($args, [
            'modal_type' => 'confirmation',
            'modal_class' => 'modal-confirmation',
            'modal_size' => 'small',
            'title' => $args['title'] ?? 'Confirm Action',
            'aria_label' => 'Confirmation dialog'
        ]);

        return $this->render_modal($confirmation_config);
    }

    /**
     * Create specialized gallery modal
     *
     * @param array $args Gallery modal arguments
     * @return string Modal HTML
     */
    public function create_gallery_modal($args = []) {
        $gallery_config = array_merge($args, [
            'modal_type' => 'gallery',
            'modal_class' => 'modal-gallery',
            'modal_size' => 'fullscreen',
            'has_backdrop' => true,
            'close_on_backdrop' => true,
            'aria_label' => 'Image gallery modal'
        ]);

        return $this->render_modal($gallery_config);
    }

    /**
     * Create specialized treatment info modal
     *
     * @param array $args Treatment info modal arguments
     * @return string Modal HTML
     */
    public function create_treatment_info_modal($args = []) {
        $treatment_config = array_merge($args, [
            'modal_type' => 'treatment',
            'modal_class' => 'modal-treatment-info',
            'modal_size' => 'large',
            'title' => $args['title'] ?? 'Treatment Information',
            'aria_label' => 'Treatment information modal'
        ]);

        return $this->render_modal($treatment_config);
    }

    // Private helper methods

    /**
     * Sanitize modal configuration
     *
     * @param array $config Configuration to sanitize
     * @return array Sanitized configuration
     */
    protected function sanitize_config($config) {
        // Sanitize basic modal settings
        $config['modal_id'] = sanitize_text_field($config['modal_id']);
        $config['modal_class'] = sanitize_text_field($config['modal_class']);
        $config['modal_type'] = in_array($config['modal_type'], $this->modal_types)
            ? $config['modal_type'] : 'basic';
        $config['modal_size'] = in_array($config['modal_size'], $this->sizes)
            ? $config['modal_size'] : 'medium';
        $config['modal_position'] = in_array($config['modal_position'], $this->positions)
            ? $config['modal_position'] : 'center';

        // Sanitize content
        $config['title'] = sanitize_text_field($config['title']);
        $config['content'] = wp_kses_post($config['content']);
        $config['footer_content'] = wp_kses_post($config['footer_content']);
        $config['close_button_text'] = sanitize_text_field($config['close_button_text']);

        // Sanitize accessibility
        $config['aria_label'] = sanitize_text_field($config['aria_label']);
        $config['aria_describedby'] = sanitize_text_field($config['aria_describedby']);
        $config['role'] = sanitize_text_field($config['role']);

        return $config;
    }

    /**
     * Generate modal HTML attributes
     *
     * @param array $config Modal configuration
     * @return array Modal attributes
     */
    protected function generate_modal_attributes($config) {
        $attributes = [
            'id' => $config['modal_id'] ?: 'modal-' . uniqid(),
            'class' => $this->get_modal_classes($config),
            'role' => $config['role'],
            'aria-modal' => 'true',
            'aria-hidden' => $config['is_open'] ? 'false' : 'true',
            'tabindex' => '-1'
        ];

        // Add ARIA labels
        if (!empty($config['aria_label'])) {
            $attributes['aria-label'] = $config['aria_label'];
        }

        if (!empty($config['aria_describedby'])) {
            $attributes['aria-describedby'] = $config['aria_describedby'];
        }

        // Add data attributes for JavaScript
        $attributes['data-modal-type'] = $config['modal_type'];
        $attributes['data-modal-size'] = $config['modal_size'];
        $attributes['data-modal-position'] = $config['modal_position'];
        $attributes['data-close-on-backdrop'] = $config['close_on_backdrop'] ? 'true' : 'false';
        $attributes['data-close-on-escape'] = $config['close_on_escape'] ? 'true' : 'false';
        $attributes['data-trap-focus'] = $config['trap_focus'] ? 'true' : 'false';

        // Add animation data
        if ($config['animation_enabled']) {
            $attributes['data-animation-duration'] = $config['animation_duration'];
            $attributes['data-animation-easing'] = $config['animation_easing'];
        }

        // Add custom attributes
        if (!empty($config['custom_attributes'])) {
            $attributes = array_merge($attributes, $config['custom_attributes']);
        }

        return $attributes;
    }

    /**
     * Get modal CSS classes
     *
     * @param array $config Modal configuration
     * @return string CSS classes
     */
    protected function get_modal_classes($config) {
        $classes = ['modal-component'];

        // Add type class
        $classes[] = 'modal-' . $config['modal_type'];

        // Add size class
        $classes[] = 'modal-size-' . $config['modal_size'];

        // Add position class
        $classes[] = 'modal-position-' . $config['modal_position'];

        // Add state classes
        if ($config['is_open']) {
            $classes[] = 'modal-open';
        }

        if ($config['has_backdrop']) {
            $classes[] = 'modal-has-backdrop';
        }

        // Add custom class
        if (!empty($config['modal_class'])) {
            $classes[] = $config['modal_class'];
        }

        return implode(' ', $classes);
    }

    /**
     * Generate modal content
     *
     * @param array $config Modal configuration
     * @return string Modal content HTML
     */
    protected function generate_modal_content($config) {
        $content = '';

        // Modal header
        if (!empty($config['title']) || $config['close_button_text']) {
            $content .= $this->render_modal_header($config);
        }

        // Modal body
        $content .= $this->render_modal_body($config);

        // Modal footer
        if (!empty($config['footer_content'])) {
            $content .= $this->render_modal_footer($config);
        }

        return $content;
    }

    /**
     * Render modal header
     *
     * @param array $config Modal configuration
     * @return string Header HTML
     */
    protected function render_modal_header($config) {
        $header = '<div class="modal-header">';

        if (!empty($config['title'])) {
            $header .= sprintf(
                '<h2 class="modal-title" id="%s-title">%s</h2>',
                esc_attr($config['modal_id']),
                esc_html($config['title'])
            );
        }

        if ($config['close_button_text']) {
            $header .= sprintf(
                '<button type="button" class="modal-close" aria-label="Close modal" data-modal-close>%s</button>',
                esc_html($config['close_button_text'])
            );
        }

        $header .= '</div>';

        return $header;
    }

    /**
     * Render modal body
     *
     * @param array $config Modal configuration
     * @return string Body HTML
     */
    protected function render_modal_body($config) {
        $body = '<div class="modal-body"';

        if (!empty($config['title'])) {
            $body .= ' aria-labelledby="' . esc_attr($config['modal_id']) . '-title"';
        }

        $body .= '>';
        $body .= $config['content'];
        $body .= '</div>';

        return $body;
    }

    /**
     * Render modal footer
     *
     * @param array $config Modal configuration
     * @return string Footer HTML
     */
    protected function render_modal_footer($config) {
        return '<div class="modal-footer">' . $config['footer_content'] . '</div>';
    }

    /**
     * Render complete modal HTML
     *
     * @param array $modal_attributes Modal attributes
     * @param string $modal_content Modal content
     * @param array $config Modal configuration
     * @return string Complete modal HTML
     */
    protected function render_modal_html($modal_attributes, $modal_content, $config) {
        $attributes_string = '';
        foreach ($modal_attributes as $key => $value) {
            $attributes_string .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
        }

        $html = '<div' . $attributes_string . '>';

        // Add backdrop if enabled
        if ($config['has_backdrop']) {
            $html .= '<div class="modal-backdrop" data-modal-backdrop></div>';
        }

        // Add modal dialog container
        $html .= '<div class="modal-dialog" role="document">';
        $html .= '<div class="modal-content">';
        $html .= $modal_content;
        $html .= '</div>';
        $html .= '</div>';

        $html .= '</div>';

        return $html;
    }

    /**
     * Generate JavaScript initialization code
     *
     * @param array $config Modal configuration
     * @return string JavaScript code
     */
    public function get_modal_script($config) {
        if (!$config['auto_init']) {
            return '';
        }

        $script = '<script type="text/javascript">';
        $script .= 'document.addEventListener("DOMContentLoaded", function() {';
        $script .= 'if (typeof ModalManager !== "undefined") {';
        $script .= sprintf('ModalManager.init("%s");', esc_js($config['modal_id']));
        $script .= '}';
        $script .= '});';
        $script .= '</script>';

        return $script;
    }
}
