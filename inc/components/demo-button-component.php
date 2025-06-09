<?php
/**
 * Demo Button Component
 *
 * Demonstration component for testing the Component Architecture System
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
 * Demo Button Component Class
 *
 * Example implementation of the BaseComponent for testing
 * the component architecture system.
 */
class DemoButtonComponent extends BaseComponent {

    /**
     * Render the button component
     *
     * @param array $args Component arguments
     * @return string HTML output
     */
    public function render($args = []) {
        $config = wp_parse_args($args, $this->config);

        $text = $config['text'] ?? 'Click Me';
        $url = $config['url'] ?? '#';
        $variant = $config['variant'] ?? 'primary';
        $size = $config['size'] ?? 'medium';
        $icon = $config['icon'] ?? '';
        $attrs = $config['attrs'] ?? [];

        // Build CSS classes
        $classes = [
            'demo-button',
            'demo-button--' . $variant,
            'demo-button--' . $size
        ];

        if (!empty($icon)) {
            $classes[] = 'demo-button--has-icon';
        }

        $class_string = implode(' ', $classes);

        // Build additional attributes
        $attr_string = '';
        foreach ($attrs as $key => $value) {
            $attr_string .= " {$key}=\"" . esc_attr($value) . "\"";
        }

        // Build button HTML
        $button_html = "<a href=\"{$url}\" class=\"{$class_string}\"{$attr_string}>";

        if (!empty($icon)) {
            $button_html .= "<span class=\"demo-button__icon\">{$icon}</span>";
        }

        $button_html .= "<span class=\"demo-button__text\">{$text}</span>";
        $button_html .= "</a>";

        // Apply component wrapper with accessibility
        return $this->render_component_wrapper($button_html, [
            'class' => 'demo-button-component',
            'attrs' => [
                'role' => 'region',
                'aria-label' => 'Button component'
            ]
        ]);
    }

    /**
     * Get WordPress Customizer controls
     *
     * @return array Customizer controls
     */
    public function get_customizer_controls() {
        return [
            'primary_color' => [
                'type' => 'color',
                'label' => 'Primary Button Color',
                'default' => '#007cba',
                'description' => 'Color for primary buttons'
            ],
            'secondary_color' => [
                'type' => 'color',
                'label' => 'Secondary Button Color',
                'default' => '#6c757d',
                'description' => 'Color for secondary buttons'
            ],
            'border_radius' => [
                'type' => 'range',
                'label' => 'Button Border Radius',
                'default' => '4',
                'input_attrs' => [
                    'min' => '0',
                    'max' => '20',
                    'step' => '1'
                ],
                'description' => 'Border radius in pixels'
            ],
            'font_weight' => [
                'type' => 'select',
                'label' => 'Button Font Weight',
                'default' => '600',
                'choices' => [
                    '400' => 'Normal',
                    '500' => 'Medium',
                    '600' => 'Semi Bold',
                    '700' => 'Bold'
                ],
                'description' => 'Font weight for button text'
            ],
            'hover_effect' => [
                'type' => 'checkbox',
                'label' => 'Enable Hover Effects',
                'default' => true,
                'description' => 'Enable hover and focus effects'
            ]
        ];
    }

    /**
     * Get default design tokens
     *
     * @return array Design tokens
     */
    public function get_default_tokens() {
        return [
            'primary_color' => '#007cba',
            'secondary_color' => '#6c757d',
            'text_color' => '#ffffff',
            'border_radius' => '4px',
            'padding_x' => '1rem',
            'padding_y' => '0.5rem',
            'font_weight' => '600',
            'font_size' => '1rem',
            'transition' => 'all 0.3s ease',
            'hover_transform' => 'translateY(-2px)',
            'focus_outline' => '2px solid #005a87'
        ];
    }

    /**
     * Get component defaults
     *
     * @return array Default configuration
     */
    public function get_defaults() {
        return [
            'text' => 'Button',
            'url' => '#',
            'variant' => 'primary',
            'size' => 'medium',
            'icon' => '',
            'attrs' => []
        ];
    }

    /**
     * Get component-specific accessibility configuration
     *
     * @return array Accessibility configuration
     */
    protected function get_accessibility_config() {
        return [
            'keyboard_navigation' => true,
            'focus_management' => true,
            'screen_reader_support' => true,
            'color_contrast_validation' => true
        ];
    }
}

// Auto-register the demo component when the component system initializes
add_action('medspa_components_init', function() {
    ComponentRegistry::register('demo-button', 'DemoButtonComponent', [
        'priority' => 10,
        'cacheable' => true,
        'accessibility_required' => true
    ]);
});
