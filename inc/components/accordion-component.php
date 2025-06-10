<?php
/**
 * Accordion Component Base Class
 *
 * Provides comprehensive accordion/toggle functionality with accessibility compliance,
 * smooth animations, and seamless integration with the design token system.
 * Specialized for medical spa content including FAQ, treatment info, and pricing.
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
 * AccordionComponent Class
 *
 * Base class for all accordion/collapse components with WCAG 2.1 AA compliance,
 * smooth animations, and comprehensive interaction management.
 */
class AccordionComponent extends BaseComponent {

    /**
     * Available accordion types
     * @var array
     */
    protected $accordion_types = ['basic', 'faq', 'treatment', 'pricing', 'multi-step'];

    /**
     * Available layout variations
     * @var array
     */
    protected $layouts = ['basic', 'boxed', 'borderless', 'flush'];

    /**
     * Available animation types
     * @var array
     */
    protected $animation_types = ['slide', 'fade', 'scale'];

    /**
     * Icon positions
     * @var array
     */
    protected $icon_positions = ['left', 'right'];

    /**
     * Accordion state management
     * @var array
     */
    protected $accordion_state = [
        'allow_multiple' => false,
        'default_open' => [],
        'persistent_state' => false
    ];

    /**
     * Render the accordion component
     *
     * @param array $args Component arguments
     * @return string HTML output
     */
    public function render($args = []) {
        $config = wp_parse_args($args, $this->get_defaults());

        // Validate and sanitize inputs
        $config = $this->sanitize_config($config);

        // Generate accordion structure
        $accordion_html = '';

        // Accordion container
        $accordion_html .= $this->render_accordion_container($config);

        return $this->validate_performance($accordion_html);
    }

    /**
     * Get WordPress Customizer controls
     *
     * @return array Customizer controls configuration
     */
    public function get_customizer_controls() {
        return [
            // Container Styling
            'container_background' => [
                'type' => 'color',
                'label' => 'Accordion Background Color',
                'description' => 'Background color for accordion container',
                'default' => '#ffffff'
            ],

            'container_border_color' => [
                'type' => 'color',
                'label' => 'Container Border Color',
                'description' => 'Border color for accordion container',
                'default' => '#e2e8f0'
            ],

            'container_border_radius' => [
                'type' => 'range',
                'label' => 'Container Border Radius',
                'description' => 'Roundness of accordion corners',
                'default' => '8',
                'input_attrs' => [
                    'min' => '0',
                    'max' => '20',
                    'step' => '1'
                ],
                'sanitize_callback' => 'absint'
            ],

            'container_spacing' => [
                'type' => 'range',
                'label' => 'Item Spacing',
                'description' => 'Space between accordion items',
                'default' => '4',
                'input_attrs' => [
                    'min' => '0',
                    'max' => '20',
                    'step' => '1'
                ],
                'sanitize_callback' => 'absint'
            ],

            // Header Styling
            'header_background' => [
                'type' => 'color',
                'label' => 'Header Background Color',
                'description' => 'Background color for accordion headers',
                'default' => '#f8fafc'
            ],

            'header_hover_background' => [
                'type' => 'color',
                'label' => 'Header Hover Background',
                'description' => 'Background color when hovering headers',
                'default' => '#e2e8f0'
            ],

            'header_text_color' => [
                'type' => 'color',
                'label' => 'Header Text Color',
                'description' => 'Color of header text',
                'default' => '#1e293b'
            ],

            'header_padding' => [
                'type' => 'range',
                'label' => 'Header Padding',
                'description' => 'Internal padding for accordion headers',
                'default' => '16',
                'input_attrs' => [
                    'min' => '8',
                    'max' => '32',
                    'step' => '2'
                ],
                'sanitize_callback' => 'absint'
            ],

            'header_font_size' => [
                'type' => 'range',
                'label' => 'Header Font Size',
                'description' => 'Size of header text',
                'default' => '16',
                'input_attrs' => [
                    'min' => '14',
                    'max' => '24',
                    'step' => '1'
                ],
                'sanitize_callback' => 'absint'
            ],

            'header_font_weight' => [
                'type' => 'select',
                'label' => 'Header Font Weight',
                'description' => 'Weight of header text',
                'default' => '500',
                'choices' => [
                    '400' => 'Normal (400)',
                    '500' => 'Medium (500)',
                    '600' => 'Semi Bold (600)',
                    '700' => 'Bold (700)'
                ],
                'sanitize_callback' => 'sanitize_text_field'
            ],

            // Content Styling
            'content_background' => [
                'type' => 'color',
                'label' => 'Content Background Color',
                'description' => 'Background color for accordion content',
                'default' => '#ffffff'
            ],

            'content_text_color' => [
                'type' => 'color',
                'label' => 'Content Text Color',
                'description' => 'Color of content text',
                'default' => '#475569'
            ],

            'content_padding' => [
                'type' => 'range',
                'label' => 'Content Padding',
                'description' => 'Internal padding for accordion content',
                'default' => '16',
                'input_attrs' => [
                    'min' => '8',
                    'max' => '32',
                    'step' => '2'
                ],
                'sanitize_callback' => 'absint'
            ],

            'content_border_top' => [
                'type' => 'checkbox',
                'label' => 'Show Content Border Top',
                'description' => 'Display border between header and content',
                'default' => true
            ],

            // Icon Styling
            'icon_size' => [
                'type' => 'range',
                'label' => 'Icon Size',
                'description' => 'Size of accordion toggle icons',
                'default' => '20',
                'input_attrs' => [
                    'min' => '16',
                    'max' => '32',
                    'step' => '2'
                ],
                'sanitize_callback' => 'absint'
            ],

            'icon_color' => [
                'type' => 'color',
                'label' => 'Icon Color',
                'description' => 'Color of toggle icons',
                'default' => '#64748b'
            ],

            'icon_position' => [
                'type' => 'select',
                'label' => 'Icon Position',
                'description' => 'Position of toggle icon',
                'default' => 'right',
                'choices' => [
                    'left' => 'Left Side',
                    'right' => 'Right Side'
                ],
                'sanitize_callback' => 'sanitize_text_field'
            ],

            // Animation Settings
            'animation_type' => [
                'type' => 'select',
                'label' => 'Animation Type',
                'description' => 'Type of expand/collapse animation',
                'default' => 'slide',
                'choices' => [
                    'slide' => 'Slide Down/Up',
                    'fade' => 'Fade In/Out',
                    'scale' => 'Scale In/Out'
                ],
                'sanitize_callback' => 'sanitize_text_field'
            ],

            'animation_duration' => [
                'type' => 'range',
                'label' => 'Animation Duration',
                'description' => 'Speed of animations (milliseconds)',
                'default' => '300',
                'input_attrs' => [
                    'min' => '100',
                    'max' => '800',
                    'step' => '50'
                ],
                'sanitize_callback' => 'absint'
            ],

            // Behavior Settings
            'allow_multiple_open' => [
                'type' => 'checkbox',
                'label' => 'Allow Multiple Open',
                'description' => 'Allow multiple accordion items to be open simultaneously',
                'default' => false
            ],

            'close_others_on_open' => [
                'type' => 'checkbox',
                'label' => 'Close Others on Open',
                'description' => 'Close other items when opening a new one',
                'default' => true
            ]
        ];
    }

    /**
     * Get default design tokens for accordions
     *
     * @return array Design tokens
     */
    public function get_default_tokens() {
        return [
            // Container styling
            'container_background' => '#ffffff',
            'container_border' => '1px solid #e2e8f0',
            'container_border_radius' => '8px',
            'container_spacing' => '4px',
            'container_shadow' => '0 1px 3px rgba(0, 0, 0, 0.1)',

            // Header styling
            'header_background' => '#f8fafc',
            'header_hover_background' => '#e2e8f0',
            'header_active_background' => '#e2e8f0',
            'header_text_color' => '#1e293b',
            'header_hover_text_color' => '#0f172a',
            'header_padding' => '16px',
            'header_font_size' => '16px',
            'header_font_weight' => '500',
            'header_line_height' => '1.5',
            'header_border_bottom' => '1px solid #e2e8f0',

            // Content styling
            'content_background' => '#ffffff',
            'content_text_color' => '#475569',
            'content_padding' => '16px',
            'content_border_top' => '1px solid #f1f5f9',
            'content_line_height' => '1.6',

            // Icon styling
            'icon_size' => '20px',
            'icon_color' => '#64748b',
            'icon_hover_color' => '#475569',
            'icon_transition' => 'transform 0.3s ease',

            // Animation settings
            'animation_duration' => '300ms',
            'animation_timing' => 'cubic-bezier(0.4, 0, 0.2, 1)',
            'animation_slide_distance' => '10px',

            // Focus styling
            'focus_outline_color' => '#3b82f6',
            'focus_outline_width' => '2px',
            'focus_outline_offset' => '2px',

            // Medical spa specific
            'medical_accent_color' => '#059669',
            'medical_secondary_color' => '#0d9488',
            'medical_background' => '#ecfdf5',
            'medical_border_color' => '#a7f3d0'
        ];
    }

    /**
     * Get component default configuration
     *
     * @return array Default configuration
     */
    public function get_defaults() {
        return [
            // Accordion identification
            'accordion_id' => 'accordion-' . uniqid(),
            'accordion_type' => 'basic',
            'layout' => 'basic',

            // Items configuration
            'items' => [],
            'default_open' => [],

            // Behavior configuration
            'allow_multiple_open' => false,
            'close_others_on_open' => true,
            'persistent_state' => false,
            'auto_scroll_to_opened' => false,

            // Animation configuration
            'animation_type' => 'slide',
            'animation_duration' => 300,
            'animation_easing' => 'cubic-bezier(0.4, 0, 0.2, 1)',

            // Icon configuration
            'show_icons' => true,
            'icon_position' => 'right',
            'icon_type' => 'chevron',
            'custom_icon_open' => '',
            'custom_icon_closed' => '',

            // Accessibility
            'aria_label' => '',
            'aria_describedby' => '',
            'role' => 'group',

            // Medical spa specific
            'medical_context' => false,
            'faq_schema' => false,
            'search_enabled' => false,

            // Custom attributes
            'custom_classes' => [],
            'custom_attributes' => [],
            'data_attributes' => []
        ];
    }

    /**
     * Sanitize accordion configuration
     *
     * @param array $config Configuration to sanitize
     * @return array Sanitized configuration
     */
    protected function sanitize_config($config) {
        // Sanitize basic fields
        $config['accordion_id'] = sanitize_html_class($config['accordion_id']);
        $config['accordion_type'] = sanitize_text_field($config['accordion_type']);
        $config['layout'] = sanitize_text_field($config['layout']);
        $config['animation_type'] = sanitize_text_field($config['animation_type']);
        $config['icon_type'] = sanitize_text_field($config['icon_type']);
        $config['icon_position'] = sanitize_text_field($config['icon_position']);

        // Validate accordion type
        if (!in_array($config['accordion_type'], $this->accordion_types)) {
            $config['accordion_type'] = 'basic';
        }

        // Validate layout
        if (!in_array($config['layout'], $this->layouts)) {
            $config['layout'] = 'basic';
        }

        // Validate animation type
        if (!in_array($config['animation_type'], $this->animation_types)) {
            $config['animation_type'] = 'slide';
        }

        // Validate icon position
        if (!in_array($config['icon_position'], $this->icon_positions)) {
            $config['icon_position'] = 'right';
        }

        // Sanitize boolean values
        $boolean_fields = [
            'allow_multiple_open', 'close_others_on_open', 'persistent_state',
            'auto_scroll_to_opened', 'show_icons', 'medical_context',
            'faq_schema', 'search_enabled'
        ];

        foreach ($boolean_fields as $field) {
            $config[$field] = (bool) $config[$field];
        }

        // Sanitize numeric values
        $config['animation_duration'] = absint($config['animation_duration']);
        if ($config['animation_duration'] < 100) {
            $config['animation_duration'] = 300;
        }

        // Sanitize items array
        if (is_array($config['items'])) {
            $config['items'] = array_map([$this, 'sanitize_accordion_item'], $config['items']);
        }

        // Sanitize arrays
        if (is_array($config['custom_classes'])) {
            $config['custom_classes'] = array_map('sanitize_html_class', $config['custom_classes']);
        }

        if (is_array($config['default_open'])) {
            $config['default_open'] = array_map('absint', $config['default_open']);
        }

        // Sanitize accessibility attributes
        $config['aria_label'] = sanitize_text_field($config['aria_label']);
        $config['aria_describedby'] = sanitize_text_field($config['aria_describedby']);
        $config['role'] = sanitize_text_field($config['role']);

        return $config;
    }

    /**
     * Sanitize individual accordion item
     *
     * @param array $item Item to sanitize
     * @return array Sanitized item
     */
    protected function sanitize_accordion_item($item) {
        $sanitized = [
            'title' => sanitize_text_field($item['title'] ?? ''),
            'content' => wp_kses_post($item['content'] ?? ''),
            'id' => sanitize_html_class($item['id'] ?? ''),
            'icon' => sanitize_text_field($item['icon'] ?? ''),
            'disabled' => (bool) ($item['disabled'] ?? false),
            'open' => (bool) ($item['open'] ?? false),
            'classes' => []
        ];

        if (isset($item['classes']) && is_array($item['classes'])) {
            $sanitized['classes'] = array_map('sanitize_html_class', $item['classes']);
        }

        return $sanitized;
    }

    /**
     * Render accordion container
     *
     * @param array $config Accordion configuration
     * @return string Container HTML
     */
    protected function render_accordion_container($config) {
        $container_classes = [
            'accordion-container',
            'accordion-' . $config['accordion_type'],
            'accordion-layout-' . $config['layout'],
            'accordion-animation-' . $config['animation_type']
        ];

        // Add custom classes
        if (!empty($config['custom_classes'])) {
            $container_classes = array_merge($container_classes, $config['custom_classes']);
        }

        // Add medical spa classes
        if ($config['medical_context']) {
            $container_classes[] = 'accordion-medical-context';
        }

        // Add search class if enabled
        if ($config['search_enabled']) {
            $container_classes[] = 'accordion-searchable';
        }

        $container_attrs = [
            'id' => $config['accordion_id'],
            'class' => implode(' ', $container_classes),
            'role' => $config['role'],
            'data-accordion-type' => $config['accordion_type'],
            'data-animation-type' => $config['animation_type'],
            'data-animation-duration' => $config['animation_duration'],
            'data-allow-multiple' => $config['allow_multiple_open'] ? 'true' : 'false',
            'data-close-others' => $config['close_others_on_open'] ? 'true' : 'false'
        ];

        // Add accessibility attributes
        if (!empty($config['aria_label'])) {
            $container_attrs['aria-label'] = $config['aria_label'];
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

        // Build accordion content
        $accordion_content = '';

        // Search functionality if enabled
        if ($config['search_enabled']) {
            $accordion_content .= $this->render_search_box($config);
        }

        // Render accordion items
        if (!empty($config['items'])) {
            foreach ($config['items'] as $index => $item) {
                $accordion_content .= $this->render_accordion_item($item, $index, $config);
            }
        }

        return sprintf(
            '<div %s>%s</div>',
            $this->build_attributes($container_attrs),
            $accordion_content
        );
    }

    /**
     * Render search box for searchable accordions
     *
     * @param array $config Accordion configuration
     * @return string Search box HTML
     */
    protected function render_search_box($config) {
        $search_id = $config['accordion_id'] . '-search';

        return sprintf(
            '<div class="accordion-search">
                <label for="%s" class="sr-only">Search accordion items</label>
                <input type="text" id="%s" class="accordion-search-input" placeholder="Search..." aria-label="Search accordion items" />
                <div class="accordion-search-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="21 21l-4.35-4.35"></path>
                    </svg>
                </div>
            </div>',
            esc_attr($search_id),
            esc_attr($search_id)
        );
    }

    /**
     * Render individual accordion item
     *
     * @param array $item Item configuration
     * @param int $index Item index
     * @param array $config Accordion configuration
     * @return string Item HTML
     */
    protected function render_accordion_item($item, $index, $config) {
        $item_id = !empty($item['id']) ? $item['id'] : $config['accordion_id'] . '-item-' . $index;
        $header_id = $item_id . '-header';
        $content_id = $item_id . '-content';

        $item_classes = ['accordion-item'];

        // Add custom item classes
        if (!empty($item['classes'])) {
            $item_classes = array_merge($item_classes, $item['classes']);
        }

        // Add state classes
        if ($item['disabled']) {
            $item_classes[] = 'accordion-item-disabled';
        }

        if ($item['open'] || in_array($index, $config['default_open'])) {
            $item_classes[] = 'accordion-item-open';
        }

        $item_html = sprintf('<div class="%s" data-accordion-item="%d">', implode(' ', $item_classes), $index);

        // Render header
        $item_html .= $this->render_accordion_header($item, $header_id, $content_id, $config);

        // Render content
        $item_html .= $this->render_accordion_content($item, $content_id, $header_id, $config);

        $item_html .= '</div>';

        return $item_html;
    }

    /**
     * Render accordion item header
     *
     * @param array $item Item configuration
     * @param string $header_id Header ID
     * @param string $content_id Content ID
     * @param array $config Accordion configuration
     * @return string Header HTML
     */
    protected function render_accordion_header($item, $header_id, $content_id, $config) {
        $is_open = $item['open'] || in_array(array_search($item, $config['items']), $config['default_open']);

        $header_attrs = [
            'id' => $header_id,
            'class' => 'accordion-header',
            'role' => 'button',
            'tabindex' => $item['disabled'] ? '-1' : '0',
            'aria-expanded' => $is_open ? 'true' : 'false',
            'aria-controls' => $content_id,
            'aria-disabled' => $item['disabled'] ? 'true' : 'false',
            'data-accordion-header' => 'true'
        ];

        if ($item['disabled']) {
            $header_attrs['class'] .= ' accordion-header-disabled';
        }

        $header_content = '';

        // Icon on left
        if ($config['show_icons'] && $config['icon_position'] === 'left') {
            $header_content .= $this->render_accordion_icon($item, $config);
        }

        // Header title
        $header_content .= sprintf(
            '<span class="accordion-title">%s</span>',
            esc_html($item['title'])
        );

        // Icon on right
        if ($config['show_icons'] && $config['icon_position'] === 'right') {
            $header_content .= $this->render_accordion_icon($item, $config);
        }

        return sprintf(
            '<div %s>%s</div>',
            $this->build_attributes($header_attrs),
            $header_content
        );
    }

    /**
     * Render accordion item content
     *
     * @param array $item Item configuration
     * @param string $content_id Content ID
     * @param string $header_id Header ID
     * @param array $config Accordion configuration
     * @return string Content HTML
     */
    protected function render_accordion_content($item, $content_id, $header_id, $config) {
        $is_open = $item['open'] || in_array(array_search($item, $config['items']), $config['default_open']);

        $content_attrs = [
            'id' => $content_id,
            'class' => 'accordion-content',
            'role' => 'region',
            'aria-labelledby' => $header_id,
            'aria-hidden' => $is_open ? 'false' : 'true',
            'data-accordion-content' => 'true'
        ];

        if (!$is_open) {
            $content_attrs['style'] = 'display: none;';
        }

        return sprintf(
            '<div %s>
                <div class="accordion-content-inner">%s</div>
            </div>',
            $this->build_attributes($content_attrs),
            $item['content']
        );
    }

    /**
     * Render accordion toggle icon
     *
     * @param array $item Item configuration
     * @param array $config Accordion configuration
     * @return string Icon HTML
     */
    protected function render_accordion_icon($item, $config) {
        $icon_classes = ['accordion-icon'];

        if (!empty($item['icon'])) {
            // Custom icon for this item
            return sprintf(
                '<span class="%s" aria-hidden="true">%s</span>',
                implode(' ', $icon_classes),
                esc_html($item['icon'])
            );
        }

        // Default chevron icon
        if ($config['icon_type'] === 'chevron') {
            return sprintf(
                '<span class="%s" aria-hidden="true">
                    <svg class="accordion-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6,9 12,15 18,9"></polyline>
                    </svg>
                </span>',
                implode(' ', $icon_classes)
            );
        }

        // Plus/minus icon
        if ($config['icon_type'] === 'plus-minus') {
            return sprintf(
                '<span class="%s" aria-hidden="true">
                    <svg class="accordion-icon-svg accordion-icon-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <svg class="accordion-icon-svg accordion-icon-minus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </span>',
                implode(' ', $icon_classes)
            );
        }

        return '';
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
            'aria_atomic' => false,
            'keyboard_navigation' => true,
            'focus_management' => true,
            'skip_links' => false,
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

        // Get accordion customizer values
        $accordion_controls = $this->get_customizer_controls();

        foreach ($accordion_controls as $control_id => $control_config) {
            $setting_id = $this->component_name . '_' . $control_id;
            $value = get_theme_mod($setting_id, $control_config['default'] ?? '');

            if (!empty($value)) {
                $customizer_values['accordion_' . $control_id] = $value;
            }
        }

        return $customizer_values;
    }

    /**
     * Generate FAQ schema markup
     *
     * @param array $config Accordion configuration
     * @return string Schema markup
     */
    protected function generate_faq_schema($config) {
        if (!$config['faq_schema'] || empty($config['items'])) {
            return '';
        }

        $faq_items = [];

        foreach ($config['items'] as $item) {
            if (!empty($item['title']) && !empty($item['content'])) {
                $faq_items[] = [
                    '@type' => 'Question',
                    'name' => $item['title'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => wp_strip_all_tags($item['content'])
                    ]
                ];
            }
        }

        if (empty($faq_items)) {
            return '';
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faq_items
        ];

        return sprintf(
            '<script type="application/ld+json">%s</script>',
            wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
    }
}
