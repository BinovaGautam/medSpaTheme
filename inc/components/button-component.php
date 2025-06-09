<?php
/**
 * Button Component
 *
 * Unified button component with variants, sizes, and design token integration
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
 * ButtonComponent Class
 *
 * Provides unified button functionality with multiple variants,
 * sizes, and full integration with the design token system.
 */
class ButtonComponent extends BaseComponent {

    /**
     * Available button variants
     * @var array
     */
    protected $button_variants = ['primary', 'secondary', 'outline', 'ghost'];

    /**
     * Available button sizes
     * @var array
     */
    protected $button_sizes = ['small', 'medium', 'large'];

    /**
     * Available icon positions
     * @var array
     */
    protected $icon_positions = ['left', 'right', 'only'];

    /**
     * Render the button component
     *
     * @param array $args Component arguments
     * @return string HTML output
     */
    public function render($args = []) {
        $config = wp_parse_args($args, $this->config);

        // Validate and sanitize inputs
        $config = $this->sanitize_config($config);

        // Generate button classes
        $classes = $this->generate_button_classes($config);

        // Generate button attributes
        $attributes = $this->generate_button_attributes($config);

        // Generate button content
        $content = $this->generate_button_content($config);

        // Render button HTML
        $html = $this->render_button_html($classes, $attributes, $content, $config);

        return $this->validate_performance($html);
    }

    /**
     * Get WordPress Customizer controls
     *
     * @return array Customizer controls configuration
     */
    public function get_customizer_controls() {
        return [
            // Button Border Radius
            'border_radius' => [
                'type' => 'range',
                'label' => 'Button Border Radius',
                'description' => 'Adjust the roundness of button corners',
                'default' => '8',
                'input_attrs' => [
                    'min' => '0',
                    'max' => '20',
                    'step' => '1'
                ],
                'sanitize_callback' => 'absint'
            ],

            // Button Padding Horizontal
            'padding_horizontal' => [
                'type' => 'range',
                'label' => 'Button Horizontal Padding',
                'description' => 'Adjust button left and right padding',
                'default' => '16',
                'input_attrs' => [
                    'min' => '8',
                    'max' => '48',
                    'step' => '4'
                ],
                'sanitize_callback' => 'absint'
            ],

            // Button Padding Vertical
            'padding_vertical' => [
                'type' => 'range',
                'label' => 'Button Vertical Padding',
                'description' => 'Adjust button top and bottom padding',
                'default' => '12',
                'input_attrs' => [
                    'min' => '4',
                    'max' => '24',
                    'step' => '2'
                ],
                'sanitize_callback' => 'absint'
            ],

            // Button Font Weight
            'font_weight' => [
                'type' => 'select',
                'label' => 'Button Font Weight',
                'description' => 'Choose button text weight',
                'default' => '600',
                'choices' => [
                    '400' => 'Normal (400)',
                    '500' => 'Medium (500)',
                    '600' => 'Semi Bold (600)',
                    '700' => 'Bold (700)'
                ],
                'sanitize_callback' => 'sanitize_text_field'
            ],

            // Button Transition Duration
            'transition_duration' => [
                'type' => 'range',
                'label' => 'Button Transition Duration',
                'description' => 'Button hover animation speed (milliseconds)',
                'default' => '200',
                'input_attrs' => [
                    'min' => '0',
                    'max' => '500',
                    'step' => '50'
                ],
                'sanitize_callback' => 'absint'
            ],

            // Primary Button Colors
            'primary_background_color' => [
                'type' => 'color',
                'label' => 'Primary Button Background',
                'description' => 'Background color for primary buttons',
                'default' => '#2563eb'
            ],

            'primary_text_color' => [
                'type' => 'color',
                'label' => 'Primary Button Text',
                'description' => 'Text color for primary buttons',
                'default' => '#ffffff'
            ],

            'primary_hover_background_color' => [
                'type' => 'color',
                'label' => 'Primary Button Hover Background',
                'description' => 'Background color when hovering primary buttons',
                'default' => '#1d4ed8'
            ],

            // Secondary Button Colors
            'secondary_background_color' => [
                'type' => 'color',
                'label' => 'Secondary Button Background',
                'description' => 'Background color for secondary buttons',
                'default' => '#64748b'
            ],

            'secondary_text_color' => [
                'type' => 'color',
                'label' => 'Secondary Button Text',
                'description' => 'Text color for secondary buttons',
                'default' => '#ffffff'
            ],

            'secondary_hover_background_color' => [
                'type' => 'color',
                'label' => 'Secondary Button Hover Background',
                'description' => 'Background color when hovering secondary buttons',
                'default' => '#475569'
            ],

            // Outline Button Colors
            'outline_border_color' => [
                'type' => 'color',
                'label' => 'Outline Button Border',
                'description' => 'Border color for outline buttons',
                'default' => '#2563eb'
            ],

            'outline_text_color' => [
                'type' => 'color',
                'label' => 'Outline Button Text',
                'description' => 'Text color for outline buttons',
                'default' => '#2563eb'
            ],

            'outline_hover_background_color' => [
                'type' => 'color',
                'label' => 'Outline Button Hover Background',
                'description' => 'Background color when hovering outline buttons',
                'default' => '#2563eb'
            ],

            // Ghost Button Colors
            'ghost_text_color' => [
                'type' => 'color',
                'label' => 'Ghost Button Text',
                'description' => 'Text color for ghost buttons',
                'default' => '#1f2937'
            ],

            'ghost_hover_background_color' => [
                'type' => 'color',
                'label' => 'Ghost Button Hover Background',
                'description' => 'Background color when hovering ghost buttons',
                'default' => '#f3f4f6'
            ]
        ];
    }

    /**
     * Get default design tokens for buttons
     *
     * @return array Design tokens
     */
    public function get_default_tokens() {
        return [
            // Button Foundation
            'border_radius' => '8px',
            'padding_horizontal' => '16px',
            'padding_vertical' => '12px',
            'font_weight' => '600',
            'font_family' => 'var(--font-family-primary)',
            'transition_duration' => '200ms',
            'focus_outline_offset' => '2px',
            'focus_outline_width' => '2px',

            // Primary Button
            'primary_background_color' => '#2563eb',
            'primary_text_color' => '#ffffff',
            'primary_hover_background_color' => '#1d4ed8',
            'primary_focus_background_color' => '#1e40af',

            // Secondary Button
            'secondary_background_color' => '#64748b',
            'secondary_text_color' => '#ffffff',
            'secondary_hover_background_color' => '#475569',
            'secondary_focus_background_color' => '#374151',

            // Outline Button
            'outline_border_color' => '#2563eb',
            'outline_text_color' => '#2563eb',
            'outline_hover_background_color' => '#2563eb',
            'outline_hover_text_color' => '#ffffff',
            'outline_focus_border_color' => '#1e40af',

            // Ghost Button
            'ghost_text_color' => '#1f2937',
            'ghost_hover_background_color' => '#f3f4f6',
            'ghost_focus_background_color' => '#e5e7eb',

            // Size Variations
            'small_padding_horizontal' => '12px',
            'small_padding_vertical' => '8px',
            'small_font_size' => '0.875rem',

            'medium_padding_horizontal' => '16px',
            'medium_padding_vertical' => '12px',
            'medium_font_size' => '1rem',

            'large_padding_horizontal' => '24px',
            'large_padding_vertical' => '16px',
            'large_font_size' => '1.125rem',

            // Icon Spacing
            'icon_spacing' => '8px',
            'icon_only_padding' => '12px'
        ];
    }

    /**
     * Get component default configuration
     *
     * @return array Default configuration
     */
    public function get_defaults() {
        return [
            'text' => 'Button',
            'url' => '#',
            'variant' => 'primary',
            'size' => 'medium',
            'icon' => null,
            'icon_position' => 'left',
            'target' => '_self',
            'rel' => '',
            'disabled' => false,
            'loading' => false,
            'type' => 'link', // 'link', 'button', 'submit'
            'onclick' => null,
            'css_class' => '',
            'id' => null,
            'data_attributes' => [],
            'aria_label' => null,
            'aria_describedby' => null
        ];
    }

    /**
     * Get component-specific accessibility configuration
     *
     * @return array Accessibility configuration
     */
    protected function get_accessibility_config() {
        return [
            'aria_labels' => true,
            'keyboard_navigation' => true,
            'screen_reader_support' => true,
            'color_contrast_validation' => true,
            'focus_management' => true,
            'semantic_markup' => true,
            'high_contrast_support' => true
        ];
    }

    /**
     * Sanitize and validate button configuration
     *
     * @param array $config Button configuration
     * @return array Sanitized configuration
     */
    protected function sanitize_config($config) {
        return [
            'text' => sanitize_text_field($config['text']),
            'url' => esc_url($config['url']),
            'variant' => in_array($config['variant'], $this->button_variants) ? $config['variant'] : 'primary',
            'size' => in_array($config['size'], $this->button_sizes) ? $config['size'] : 'medium',
            'icon' => sanitize_text_field($config['icon']),
            'icon_position' => in_array($config['icon_position'], $this->icon_positions) ? $config['icon_position'] : 'left',
            'target' => in_array($config['target'], ['_self', '_blank', '_parent', '_top']) ? $config['target'] : '_self',
            'rel' => sanitize_text_field($config['rel']),
            'disabled' => (bool) $config['disabled'],
            'loading' => (bool) $config['loading'],
            'type' => in_array($config['type'], ['link', 'button', 'submit']) ? $config['type'] : 'link',
            'onclick' => sanitize_text_field($config['onclick']),
            'css_class' => sanitize_html_class($config['css_class']),
            'id' => sanitize_html_class($config['id']),
            'data_attributes' => is_array($config['data_attributes']) ? $config['data_attributes'] : [],
            'aria_label' => sanitize_text_field($config['aria_label']),
            'aria_describedby' => sanitize_text_field($config['aria_describedby'])
        ];
    }

    /**
     * Generate button CSS classes
     *
     * @param array $config Button configuration
     * @return string CSS classes
     */
    protected function generate_button_classes($config) {
        $classes = [
            'medspa-button',
            'medspa-button--' . $config['variant'],
            'medspa-button--' . $config['size']
        ];

        if ($config['icon'] && $config['icon_position'] === 'only') {
            $classes[] = 'medspa-button--icon-only';
        }

        if ($config['disabled']) {
            $classes[] = 'medspa-button--disabled';
        }

        if ($config['loading']) {
            $classes[] = 'medspa-button--loading';
        }

        if (!empty($config['css_class'])) {
            $classes[] = $config['css_class'];
        }

        return implode(' ', $classes);
    }

    /**
     * Generate button attributes
     *
     * @param array $config Button configuration
     * @return string Button attributes
     */
    protected function generate_button_attributes($config) {
        $attributes = [];

        // Basic attributes
        if (!empty($config['id'])) {
            $attributes['id'] = $config['id'];
        }

        // Type-specific attributes
        if ($config['type'] === 'link') {
            $attributes['href'] = $config['url'];
            if ($config['target'] !== '_self') {
                $attributes['target'] = $config['target'];
            }
            if (!empty($config['rel']) || $config['target'] === '_blank') {
                $rel_parts = [];
                if (!empty($config['rel'])) {
                    $rel_parts[] = $config['rel'];
                }
                if ($config['target'] === '_blank') {
                    $rel_parts[] = 'noopener noreferrer';
                }
                $attributes['rel'] = implode(' ', array_unique($rel_parts));
            }
        } else {
            $attributes['type'] = $config['type'];
        }

        // JavaScript onclick
        if (!empty($config['onclick'])) {
            $attributes['onclick'] = $config['onclick'];
        }

        // Disabled state
        if ($config['disabled']) {
            $attributes['disabled'] = 'disabled';
            $attributes['aria-disabled'] = 'true';
        }

        // Loading state
        if ($config['loading']) {
            $attributes['aria-busy'] = 'true';
        }

        // Accessibility attributes
        if (!empty($config['aria_label'])) {
            $attributes['aria-label'] = $config['aria_label'];
        } elseif ($config['icon'] && $config['icon_position'] === 'only') {
            $attributes['aria-label'] = $config['text'];
        }

        if (!empty($config['aria_describedby'])) {
            $attributes['aria-describedby'] = $config['aria_describedby'];
        }

        // Data attributes
        foreach ($config['data_attributes'] as $key => $value) {
            $attributes['data-' . sanitize_key($key)] = esc_attr($value);
        }

        // Convert to string
        $attr_string = '';
        foreach ($attributes as $key => $value) {
            $attr_string .= ' ' . esc_attr($key) . '="' . esc_attr($value) . '"';
        }

        return $attr_string;
    }

    /**
     * Generate button content (text and icons)
     *
     * @param array $config Button configuration
     * @return string Button content HTML
     */
    protected function generate_button_content($config) {
        $content = '';

        // Loading state content
        if ($config['loading']) {
            $content .= '<span class="medspa-button__loading" aria-hidden="true">';
            $content .= '<svg class="medspa-button__spinner" viewBox="0 0 24 24">';
            $content .= '<circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" stroke-dasharray="62.83" stroke-dashoffset="62.83"></circle>';
            $content .= '</svg>';
            $content .= '</span>';
        }

        // Icon-only button
        if ($config['icon'] && $config['icon_position'] === 'only') {
            $content .= $this->render_icon($config['icon']);
            return $content;
        }

        // Left icon
        if ($config['icon'] && $config['icon_position'] === 'left') {
            $content .= $this->render_icon($config['icon']);
        }

        // Button text
        if (!empty($config['text'])) {
            $content .= '<span class="medspa-button__text">' . esc_html($config['text']) . '</span>';
        }

        // Right icon
        if ($config['icon'] && $config['icon_position'] === 'right') {
            $content .= $this->render_icon($config['icon']);
        }

        return $content;
    }

    /**
     * Render icon HTML
     *
     * @param string $icon Icon identifier
     * @return string Icon HTML
     */
    protected function render_icon($icon) {
        if (empty($icon)) {
            return '';
        }

        $icon_html = '<span class="medspa-button__icon" aria-hidden="true">';

        // Handle different icon types
        if (strpos($icon, 'fa-') === 0) {
            // Font Awesome icon
            $icon_html .= '<i class="fas ' . esc_attr($icon) . '"></i>';
        } elseif (strpos($icon, '<svg') === 0) {
            // SVG icon
            $icon_html .= $icon;
        } else {
            // Simple text icon or emoji
            $icon_html .= esc_html($icon);
        }

        $icon_html .= '</span>';

        return $icon_html;
    }

    /**
     * Render complete button HTML
     *
     * @param string $classes CSS classes
     * @param string $attributes HTML attributes
     * @param string $content Button content
     * @param array $config Button configuration
     * @return string Complete button HTML
     */
    protected function render_button_html($classes, $attributes, $content, $config) {
        $tag = $config['type'] === 'link' ? 'a' : 'button';

        $html = "<{$tag} class=\"{$classes}\"{$attributes}>";
        $html .= $content;
        $html .= "</{$tag}>";

        return $html;
    }

    /**
     * Get component-specific design tokens for CSS output
     *
     * @return array Component-specific tokens
     */
    protected function get_component_specific_tokens() {
        $customizer_tokens = $this->get_customizer_tokens();
        $component_tokens = [];

        // Map customizer values to CSS custom properties
        foreach ($customizer_tokens as $key => $value) {
            if (!empty($value)) {
                $component_tokens[$key] = $value;
            }
        }

        return $component_tokens;
    }
}

// Auto-register component if ComponentRegistry is available
if (class_exists('ComponentRegistry') && !ComponentRegistry::is_registered('button')) {
    ComponentRegistry::register('button', 'ButtonComponent', [
        'auto_discovered' => true,
        'priority' => 10
    ]);
}
