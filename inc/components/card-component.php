<?php
/**
 * Card Component
 *
 * Base card component with variants, sizes, and design token integration
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
 * CardComponent Class
 *
 * Provides unified card functionality with multiple variants,
 * sizes, and full integration with the design token system.
 */
class CardComponent extends BaseComponent {

    /**
     * Available card variants
     * @var array
     */
    protected $card_variants = ['default', 'elevated', 'outlined', 'filled'];

    /**
     * Available card sizes
     * @var array
     */
    protected $card_sizes = ['small', 'medium', 'large'];

    /**
     * Available hover transforms
     * @var array
     */
    protected $hover_transforms = ['none', 'lift', 'scale', 'shadow'];

    /**
     * Render the card component
     *
     * @param array $args Component arguments
     * @return string HTML output
     */
    public function render($args = []) {
        $config = wp_parse_args($args, $this->config);

        // Validate and sanitize inputs
        $config = $this->sanitize_config($config);

        // Generate card classes
        $classes = $this->generate_card_classes($config);

        // Generate card attributes
        $attributes = $this->generate_card_attributes($config);

        // Generate card content
        $content = $this->generate_card_content($config);

        // Render card HTML
        $html = $this->render_card_html($classes, $attributes, $content, $config);

        return $this->validate_performance($html);
    }

    /**
     * Get WordPress Customizer controls
     *
     * @return array Customizer controls configuration
     */
    public function get_customizer_controls() {
        return [
            // Card Border Radius
            'border_radius' => [
                'type' => 'range',
                'label' => 'Card Border Radius',
                'description' => 'Adjust the roundness of card corners',
                'default' => '8',
                'input_attrs' => [
                    'min' => '0',
                    'max' => '30',
                    'step' => '1'
                ],
                'sanitize_callback' => 'absint'
            ],

            // Card Padding
            'padding' => [
                'type' => 'range',
                'label' => 'Card Padding',
                'description' => 'Adjust card internal padding',
                'default' => '24',
                'input_attrs' => [
                    'min' => '8',
                    'max' => '48',
                    'step' => '4'
                ],
                'sanitize_callback' => 'absint'
            ],

            // Card Shadow
            'shadow' => [
                'type' => 'select',
                'label' => 'Card Shadow',
                'description' => 'Choose card drop shadow intensity',
                'default' => 'medium',
                'choices' => [
                    'none' => 'No Shadow',
                    'small' => 'Small Shadow',
                    'medium' => 'Medium Shadow',
                    'large' => 'Large Shadow'
                ],
                'sanitize_callback' => 'sanitize_text_field'
            ],

            // Border Width
            'border_width' => [
                'type' => 'range',
                'label' => 'Border Width',
                'description' => 'Card border thickness',
                'default' => '0',
                'input_attrs' => [
                    'min' => '0',
                    'max' => '5',
                    'step' => '1'
                ],
                'sanitize_callback' => 'absint'
            ],

            // Border Color
            'border_color' => [
                'type' => 'color',
                'label' => 'Border Color',
                'description' => 'Card border color',
                'default' => '#e5e7eb'
            ],

            // Background Color
            'background_color' => [
                'type' => 'color',
                'label' => 'Background Color',
                'description' => 'Card background color',
                'default' => '#ffffff'
            ],

            // Hover Transform
            'hover_transform' => [
                'type' => 'select',
                'label' => 'Hover Transform',
                'description' => 'Card hover animation effect',
                'default' => 'lift',
                'choices' => [
                    'none' => 'No Transform',
                    'lift' => 'Lift Up',
                    'scale' => 'Scale Up',
                    'shadow' => 'Shadow Increase'
                ],
                'sanitize_callback' => 'sanitize_text_field'
            ],

            // Image Border Radius
            'image_border_radius' => [
                'type' => 'range',
                'label' => 'Image Border Radius',
                'description' => 'Card image corner roundness',
                'default' => '4',
                'input_attrs' => [
                    'min' => '0',
                    'max' => '20',
                    'step' => '1'
                ],
                'sanitize_callback' => 'absint'
            ],

            // Title Font Size
            'title_font_size' => [
                'type' => 'range',
                'label' => 'Title Font Size',
                'description' => 'Card title text size',
                'default' => '20',
                'input_attrs' => [
                    'min' => '16',
                    'max' => '32',
                    'step' => '2'
                ],
                'sanitize_callback' => 'absint'
            ],

            // Title Font Weight
            'title_font_weight' => [
                'type' => 'select',
                'label' => 'Title Font Weight',
                'description' => 'Card title text weight',
                'default' => '600',
                'choices' => [
                    '400' => 'Normal (400)',
                    '500' => 'Medium (500)',
                    '600' => 'Semi Bold (600)',
                    '700' => 'Bold (700)'
                ],
                'sanitize_callback' => 'sanitize_text_field'
            ],

            // Content Font Size
            'content_font_size' => [
                'type' => 'range',
                'label' => 'Content Font Size',
                'description' => 'Card content text size',
                'default' => '16',
                'input_attrs' => [
                    'min' => '14',
                    'max' => '18',
                    'step' => '1'
                ],
                'sanitize_callback' => 'absint'
            ],

            // Content Line Height
            'content_line_height' => [
                'type' => 'range',
                'label' => 'Content Line Height',
                'description' => 'Card content line spacing',
                'default' => '1.6',
                'input_attrs' => [
                    'min' => '1.2',
                    'max' => '2.0',
                    'step' => '0.1'
                ],
                'sanitize_callback' => function($value) {
                    return floatval($value);
                }
            ],

            // Meta Text Color
            'meta_text_color' => [
                'type' => 'color',
                'label' => 'Meta Text Color',
                'description' => 'Color for meta information text',
                'default' => '#6b7280'
            ],

            // Action Button Spacing
            'action_button_spacing' => [
                'type' => 'range',
                'label' => 'Action Button Spacing',
                'description' => 'Space above action buttons',
                'default' => '16',
                'input_attrs' => [
                    'min' => '8',
                    'max' => '32',
                    'step' => '4'
                ],
                'sanitize_callback' => 'absint'
            ],

            // Card Gap in Grids
            'card_gap' => [
                'type' => 'range',
                'label' => 'Card Gap in Grids',
                'description' => 'Space between cards in grid layouts',
                'default' => '24',
                'input_attrs' => [
                    'min' => '16',
                    'max' => '48',
                    'step' => '4'
                ],
                'sanitize_callback' => 'absint'
            ]
        ];
    }

    /**
     * Get default design tokens for this component
     *
     * @return array Design tokens
     */
    public function get_default_tokens() {
        return [
            // Layout tokens
            'border_radius' => '8px',
            'padding' => '24px',
            'card_gap' => '24px',

            // Visual tokens
            'shadow' => '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
            'border_width' => '0px',
            'border_color' => '#e5e7eb',
            'background_color' => '#ffffff',

            // Animation tokens
            'transition' => '0.3s ease',
            'hover_transform' => 'translateY(-2px)',

            // Typography tokens
            'title_font_size' => '20px',
            'title_font_weight' => '600',
            'title_line_height' => '1.3',
            'content_font_size' => '16px',
            'content_line_height' => '1.6',
            'meta_font_size' => '14px',
            'meta_text_color' => '#6b7280',

            // Image tokens
            'image_border_radius' => '4px',
            'image_aspect_ratio' => '16/9',

            // Spacing tokens
            'title_margin_bottom' => '8px',
            'content_margin_bottom' => '16px',
            'action_button_spacing' => '16px',

            // Shadow variants
            'shadow_small' => '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
            'shadow_medium' => '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
            'shadow_large' => '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',

            // Hover transforms
            'hover_lift' => 'translateY(-2px)',
            'hover_scale' => 'scale(1.02)',
            'hover_shadow_increase' => '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)'
        ];
    }

    /**
     * Get component default configuration
     *
     * @return array Default configuration
     */
    public function get_defaults() {
        return [
            // Display options
            'variant' => 'default',
            'size' => 'medium',
            'hover_transform' => 'lift',

            // Content
            'title' => '',
            'content' => '',
            'image' => '',
            'image_alt' => '',
            'meta' => [],
            'actions' => [],

            // Layout
            'image_position' => 'top', // top, left, right, bottom
            'content_alignment' => 'left', // left, center, right

            // Links
            'link_url' => '',
            'link_target' => '_self',
            'link_entire_card' => false,

            // Accessibility
            'aria_label' => '',
            'role' => 'article',

            // Custom attributes
            'custom_classes' => [],
            'custom_attributes' => []
        ];
    }

    /**
     * Get accessibility configuration
     *
     * @return array Accessibility features
     */
    protected function get_accessibility_config() {
        return [
            'keyboard_navigation' => true,
            'screen_reader_support' => true,
            'focus_management' => true,
            'aria_labels' => true,
            'semantic_markup' => true,
            'color_contrast_compliance' => true
        ];
    }

    /**
     * Sanitize component configuration
     *
     * @param array $config Configuration to sanitize
     * @return array Sanitized configuration
     */
    protected function sanitize_config($config) {
        // Sanitize variant
        if (!in_array($config['variant'], $this->card_variants)) {
            $config['variant'] = 'default';
        }

        // Sanitize size
        if (!in_array($config['size'], $this->card_sizes)) {
            $config['size'] = 'medium';
        }

        // Sanitize hover transform
        if (!in_array($config['hover_transform'], $this->hover_transforms)) {
            $config['hover_transform'] = 'lift';
        }

        // Sanitize content
        $config['title'] = sanitize_text_field($config['title']);
        $config['content'] = wp_kses_post($config['content']);
        $config['image'] = esc_url($config['image']);
        $config['image_alt'] = sanitize_text_field($config['image_alt']);
        $config['link_url'] = esc_url($config['link_url']);
        $config['aria_label'] = sanitize_text_field($config['aria_label']);

        // Sanitize arrays
        if (is_array($config['meta'])) {
            $config['meta'] = array_map('sanitize_text_field', $config['meta']);
        }

        if (is_array($config['custom_classes'])) {
            $config['custom_classes'] = array_map('sanitize_html_class', $config['custom_classes']);
        }

        return $config;
    }

    /**
     * Generate card CSS classes
     *
     * @param array $config Component configuration
     * @return string CSS classes
     */
    protected function generate_card_classes($config) {
        $classes = [
            'card-component',
            'card-' . $config['variant'],
            'card-size-' . $config['size'],
            'card-hover-' . $config['hover_transform']
        ];

        // Add image position class
        if (!empty($config['image'])) {
            $classes[] = 'card-image-' . $config['image_position'];
        }

        // Add content alignment class
        $classes[] = 'card-content-' . $config['content_alignment'];

        // Add clickable class if entire card is linked
        if (!empty($config['link_url']) && $config['link_entire_card']) {
            $classes[] = 'card-clickable';
        }

        // Add custom classes
        if (!empty($config['custom_classes'])) {
            $classes = array_merge($classes, $config['custom_classes']);
        }

        return implode(' ', array_filter($classes));
    }

    /**
     * Generate card HTML attributes
     *
     * @param array $config Component configuration
     * @return string HTML attributes
     */
    protected function generate_card_attributes($config) {
        $attributes = [];

        // Accessibility attributes
        $accessibility_attrs = $this->get_accessibility_attributes([
            'role' => $config['role'],
            'aria-label' => $config['aria_label'] ?: ($config['title'] ? 'Card: ' . $config['title'] : 'Content card'),
            'tabindex' => '0'
        ]);

        $attributes = array_merge($attributes, $accessibility_attrs);

        // Link attributes for entire card
        if (!empty($config['link_url']) && $config['link_entire_card']) {
            $attributes['onclick'] = sprintf("window.%s('%s', '%s')",
                $config['link_target'] === '_blank' ? 'open' : 'location.href',
                esc_js($config['link_url']),
                $config['link_target']
            );
            $attributes['style'] = (isset($attributes['style']) ? $attributes['style'] . '; ' : '') . 'cursor: pointer;';
        }

        // Custom attributes
        if (!empty($config['custom_attributes'])) {
            $attributes = array_merge($attributes, $config['custom_attributes']);
        }

        // Convert to string
        $attr_string = '';
        foreach ($attributes as $key => $value) {
            if (is_bool($value)) {
                if ($value) {
                    $attr_string .= ' ' . esc_attr($key);
                }
            } else {
                $attr_string .= ' ' . esc_attr($key) . '="' . esc_attr($value) . '"';
            }
        }

        return $attr_string;
    }

    /**
     * Generate card content HTML
     *
     * @param array $config Component configuration
     * @return string Card content HTML
     */
    protected function generate_card_content($config) {
        $content_parts = [];

        // Card image
        if (!empty($config['image'])) {
            $content_parts['image'] = $this->render_card_image($config);
        }

        // Card header (title + meta)
        if (!empty($config['title']) || !empty($config['meta'])) {
            $content_parts['header'] = $this->render_card_header($config);
        }

        // Card content
        if (!empty($config['content'])) {
            $content_parts['content'] = $this->render_card_content_section($config);
        }

        // Card actions
        if (!empty($config['actions'])) {
            $content_parts['actions'] = $this->render_card_actions($config);
        }

        return implode('', $content_parts);
    }

    /**
     * Render card image
     *
     * @param array $config Component configuration
     * @return string Image HTML
     */
    protected function render_card_image($config) {
        if (empty($config['image'])) {
            return '';
        }

        $alt_text = $config['image_alt'] ?: $config['title'] ?: 'Card image';
        $sizes = '(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw';

        return sprintf(
            '<div class="card-image-wrapper"><img src="%s" alt="%s" sizes="%s" loading="lazy" class="card-image"></div>',
            esc_url($config['image']),
            esc_attr($alt_text),
            esc_attr($sizes)
        );
    }

    /**
     * Render card header (title + meta)
     *
     * @param array $config Component configuration
     * @return string Header HTML
     */
    protected function render_card_header($config) {
        $header_content = '';

        // Title
        if (!empty($config['title'])) {
            $title_tag = !empty($config['link_url']) && !$config['link_entire_card'] ? 'a' : 'h3';
            $title_attrs = '';

            if ($title_tag === 'a') {
                $title_attrs = sprintf(' href="%s" target="%s"',
                    esc_url($config['link_url']),
                    esc_attr($config['link_target'])
                );
            }

            $header_content .= sprintf(
                '<%s class="card-title"%s>%s</%s>',
                $title_tag,
                $title_attrs,
                esc_html($config['title']),
                $title_tag
            );
        }

        // Meta information
        if (!empty($config['meta']) && is_array($config['meta'])) {
            $meta_items = [];
            foreach ($config['meta'] as $meta_item) {
                $meta_items[] = '<span class="card-meta-item">' . esc_html($meta_item) . '</span>';
            }
            $header_content .= '<div class="card-meta">' . implode('', $meta_items) . '</div>';
        }

        return $header_content ? '<div class="card-header">' . $header_content . '</div>' : '';
    }

    /**
     * Render card content section
     *
     * @param array $config Component configuration
     * @return string Content HTML
     */
    protected function render_card_content_section($config) {
        return sprintf(
            '<div class="card-content">%s</div>',
            wp_kses_post($config['content'])
        );
    }

    /**
     * Render card actions
     *
     * @param array $config Component configuration
     * @return string Actions HTML
     */
    protected function render_card_actions($config) {
        if (empty($config['actions']) || !is_array($config['actions'])) {
            return '';
        }

        $action_buttons = [];
        foreach ($config['actions'] as $action) {
            if (is_array($action) && !empty($action['text']) && !empty($action['url'])) {
                $button_class = 'card-action-button';
                if (!empty($action['variant'])) {
                    $button_class .= ' button-' . $action['variant'];
                }

                $action_buttons[] = sprintf(
                    '<a href="%s" class="%s" target="%s">%s</a>',
                    esc_url($action['url']),
                    esc_attr($button_class),
                    esc_attr($action['target'] ?? '_self'),
                    esc_html($action['text'])
                );
            }
        }

        return $action_buttons ? '<div class="card-actions">' . implode('', $action_buttons) . '</div>' : '';
    }

    /**
     * Render complete card HTML
     *
     * @param string $classes CSS classes
     * @param string $attributes HTML attributes
     * @param string $content Card content
     * @param array $config Component configuration
     * @return string Complete card HTML
     */
    protected function render_card_html($classes, $attributes, $content, $config) {
        return sprintf(
            '<article class="%s"%s>%s</article>',
            esc_attr($classes),
            $attributes,
            $content
        );
    }

    /**
     * Get component-specific design tokens
     *
     * @return array Component-specific tokens
     */
    protected function get_component_specific_tokens() {
        $tokens = [];

        // Get customizer values and convert to tokens
        $customizer_options = [
            'border_radius' => 'px',
            'padding' => 'px',
            'border_width' => 'px',
            'title_font_size' => 'px',
            'content_font_size' => 'px',
            'action_button_spacing' => 'px',
            'card_gap' => 'px',
            'image_border_radius' => 'px'
        ];

        foreach ($customizer_options as $option => $unit) {
            $value = get_theme_mod('card_' . $option, '');
            if (!empty($value)) {
                $tokens[$option] = $value . $unit;
            }
        }

        // Color options (no unit)
        $color_options = ['border_color', 'background_color', 'meta_text_color'];
        foreach ($color_options as $option) {
            $value = get_theme_mod('card_' . $option, '');
            if (!empty($value)) {
                $tokens[$option] = $value;
            }
        }

        // Special handling for shadow
        $shadow_setting = get_theme_mod('card_shadow', 'medium');
        if ($shadow_setting && $shadow_setting !== 'medium') {
            $shadow_map = [
                'none' => 'none',
                'small' => $this->design_tokens['shadow_small'] ?? '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                'large' => $this->design_tokens['shadow_large'] ?? '0 10px 15px -3px rgba(0, 0, 0, 0.1)'
            ];
            if (isset($shadow_map[$shadow_setting])) {
                $tokens['shadow'] = $shadow_map[$shadow_setting];
            }
        }

        // Special handling for hover transform
        $hover_setting = get_theme_mod('card_hover_transform', 'lift');
        if ($hover_setting && $hover_setting !== 'lift') {
            $transform_map = [
                'none' => 'none',
                'scale' => $this->design_tokens['hover_scale'] ?? 'scale(1.02)',
                'shadow' => $this->design_tokens['hover_shadow_increase'] ?? '0 20px 25px -5px rgba(0, 0, 0, 0.1)'
            ];
            if (isset($transform_map[$hover_setting])) {
                $tokens['hover_transform'] = $transform_map[$hover_setting];
            }
        }

        return $tokens;
    }
}
