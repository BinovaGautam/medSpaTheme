<?php
/**
 * Feature Card Component
 *
 * Specialized card component for displaying service features and benefits
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
 * FeatureCard Class
 *
 * Extends CardComponent to provide specialized functionality
 * for displaying service features, benefits, and informational
 * content with icon support and various layout options.
 */
class FeatureCard extends CardComponent {

    /**
     * Available icon positions
     * @var array
     */
    protected $icon_positions = ['top', 'left', 'right', 'inline'];

    /**
     * Available feature types
     * @var array
     */
    protected $feature_types = ['service', 'benefit', 'process', 'technology', 'guarantee'];

    /**
     * Get component default configuration for features
     *
     * @return array Default configuration
     */
    public function get_defaults() {
        return array_merge(parent::get_defaults(), [
            // Feature-specific content
            'icon' => '',
            'icon_type' => 'emoji', // emoji, fontawesome, svg, image
            'icon_position' => 'top',
            'icon_color' => '',
            'icon_background' => '',

            // Feature information
            'feature_type' => 'service',
            'subtitle' => '',
            'benefits' => [],
            'specifications' => [],
            'step_number' => 0, // For process cards

            // Content enhancement
            'highlight_text' => '',
            'tag' => '',
            'badge' => '',
            'stats' => [],

            // Interactive elements
            'expandable' => false,
            'expanded_content' => '',
            'modal_content' => '',
            'tooltip' => '',

            // Visual customization
            'icon_size' => 'medium', // small, medium, large
            'alignment' => 'center', // left, center, right
            'style' => 'standard', // standard, minimal, elevated, bordered
            'color_scheme' => 'default', // default, primary, secondary, success, warning, info

            // Animation
            'hover_effect' => 'lift', // none, lift, scale, glow, pulse
            'entrance_animation' => '', // fadeIn, slideUp, zoomIn, etc.

            // CTA override for features
            'cta_style' => 'link' // button, link, none
        ]);
    }

    /**
     * Render the feature card
     *
     * @param array $args Component arguments
     * @return string HTML output
     */
    public function render($args = []) {
        $config = wp_parse_args($args, $this->get_defaults());

        // Sanitize feature-specific fields
        $config = $this->sanitize_feature_config($config);

        // Add feature-specific classes
        $config['custom_classes'] = array_merge(
            $config['custom_classes'] ?? [],
            [
                'feature-card',
                'feature-type-' . $config['feature_type'],
                'feature-icon-' . $config['icon_position'],
                'feature-style-' . $config['style'],
                'feature-align-' . $config['alignment']
            ]
        );

        // Add color scheme class
        if ($config['color_scheme'] !== 'default') {
            $config['custom_classes'][] = 'feature-color-' . $config['color_scheme'];
        }

        // Add step number class for process cards
        if ($config['step_number'] > 0) {
            $config['custom_classes'][] = 'feature-step';
        }

        // Add expandable class
        if ($config['expandable']) {
            $config['custom_classes'][] = 'feature-expandable';
        }

        // Override hover transform with feature-specific effect
        $config['hover_transform'] = $config['hover_effect'];

        // Set appropriate ARIA role
        $config['role'] = 'article';
        $config['aria_label'] = 'Feature: ' . $config['title'];

        return parent::render($config);
    }

    /**
     * Sanitize feature-specific configuration
     *
     * @param array $config Configuration to sanitize
     * @return array Sanitized configuration
     */
    protected function sanitize_feature_config($config) {
        // Sanitize text fields
        $config['icon'] = sanitize_text_field($config['icon']);
        $config['icon_type'] = sanitize_text_field($config['icon_type']);
        $config['icon_position'] = sanitize_text_field($config['icon_position']);
        $config['feature_type'] = sanitize_text_field($config['feature_type']);
        $config['subtitle'] = sanitize_text_field($config['subtitle']);
        $config['highlight_text'] = sanitize_text_field($config['highlight_text']);
        $config['tag'] = sanitize_text_field($config['tag']);
        $config['badge'] = sanitize_text_field($config['badge']);
        $config['tooltip'] = sanitize_text_field($config['tooltip']);
        $config['icon_size'] = sanitize_text_field($config['icon_size']);
        $config['alignment'] = sanitize_text_field($config['alignment']);
        $config['style'] = sanitize_text_field($config['style']);
        $config['color_scheme'] = sanitize_text_field($config['color_scheme']);
        $config['hover_effect'] = sanitize_text_field($config['hover_effect']);
        $config['entrance_animation'] = sanitize_text_field($config['entrance_animation']);
        $config['cta_style'] = sanitize_text_field($config['cta_style']);

        // Sanitize content
        $config['expanded_content'] = wp_kses_post($config['expanded_content']);
        $config['modal_content'] = wp_kses_post($config['modal_content']);

        // Sanitize colors
        $config['icon_color'] = sanitize_hex_color($config['icon_color']);
        $config['icon_background'] = sanitize_hex_color($config['icon_background']);

        // Sanitize numbers
        $config['step_number'] = absint($config['step_number']);

        // Sanitize arrays
        if (is_array($config['benefits'])) {
            $config['benefits'] = array_map('sanitize_text_field', $config['benefits']);
        }

        if (is_array($config['specifications'])) {
            $config['specifications'] = array_map('sanitize_text_field', $config['specifications']);
        }

        if (is_array($config['stats'])) {
            foreach ($config['stats'] as $key => $stat) {
                if (is_array($stat)) {
                    $config['stats'][$key] = array_map('sanitize_text_field', $stat);
                } else {
                    $config['stats'][$key] = sanitize_text_field($stat);
                }
            }
        }

        // Validate icon position
        if (!in_array($config['icon_position'], $this->icon_positions)) {
            $config['icon_position'] = 'top';
        }

        // Validate feature type
        if (!in_array($config['feature_type'], $this->feature_types)) {
            $config['feature_type'] = 'service';
        }

        return $config;
    }

    /**
     * Override card content generation to include feature-specific sections
     *
     * @param array $config Component configuration
     * @return string Card content HTML
     */
    protected function generate_card_content($config) {
        $content_parts = [];

        // Feature badge/tag
        if (!empty($config['badge']) || !empty($config['tag'])) {
            $content_parts['badge'] = $this->render_feature_badge($config);
        }

        // Step number for process cards
        if ($config['step_number'] > 0) {
            $content_parts['step'] = $this->render_step_number($config);
        }

        // Icon section (position: top or separate)
        if (!empty($config['icon']) && in_array($config['icon_position'], ['top', 'inline'])) {
            $content_parts['icon'] = $this->render_feature_icon($config);
        }

        // Content container with icon positioning
        $content_container_start = $this->get_content_container_start($config);
        $content_parts['content_start'] = $content_container_start;

        // Icon for left/right positions
        if (!empty($config['icon']) && in_array($config['icon_position'], ['left', 'right'])) {
            $content_parts['side_icon'] = $this->render_feature_icon($config);
        }

        // Header section
        $content_parts['header'] = $this->render_feature_header($config);

        // Subtitle
        if (!empty($config['subtitle'])) {
            $content_parts['subtitle'] = $this->render_feature_subtitle($config);
        }

        // Main content
        if (!empty($config['content'])) {
            $content_parts['content'] = $this->render_card_content_section($config);
        }

        // Highlight text
        if (!empty($config['highlight_text'])) {
            $content_parts['highlight'] = $this->render_highlight_text($config);
        }

        // Benefits list
        if (!empty($config['benefits'])) {
            $content_parts['benefits'] = $this->render_feature_benefits($config);
        }

        // Specifications
        if (!empty($config['specifications'])) {
            $content_parts['specs'] = $this->render_feature_specifications($config);
        }

        // Statistics
        if (!empty($config['stats'])) {
            $content_parts['stats'] = $this->render_feature_stats($config);
        }

        // Expandable content
        if ($config['expandable'] && !empty($config['expanded_content'])) {
            $content_parts['expandable'] = $this->render_expandable_content($config);
        }

        // Actions (with feature-specific styling)
        if (!empty($config['actions'])) {
            $content_parts['actions'] = $this->render_feature_actions($config);
        }

        // Close content container
        $content_parts['content_end'] = '</div>';

        return implode('', $content_parts);
    }

    /**
     * Get content container start with positioning classes
     *
     * @param array $config Component configuration
     * @return string Container start HTML
     */
    protected function get_content_container_start($config) {
        $container_classes = ['feature-content-container'];

        if (!empty($config['icon']) && in_array($config['icon_position'], ['left', 'right'])) {
            $container_classes[] = 'feature-content-with-side-icon';
        }

        return sprintf('<div class="%s">', implode(' ', $container_classes));
    }

    /**
     * Render feature badge/tag
     *
     * @param array $config Component configuration
     * @return string Badge HTML
     */
    protected function render_feature_badge($config) {
        $badge_content = '';

        if (!empty($config['badge'])) {
            $badge_content = sprintf(
                '<span class="feature-badge">%s</span>',
                esc_html($config['badge'])
            );
        }

        if (!empty($config['tag'])) {
            $badge_content .= sprintf(
                '<span class="feature-tag">%s</span>',
                esc_html($config['tag'])
            );
        }

        return $badge_content ? sprintf('<div class="feature-badges">%s</div>', $badge_content) : '';
    }

    /**
     * Render step number for process cards
     *
     * @param array $config Component configuration
     * @return string Step number HTML
     */
    protected function render_step_number($config) {
        return sprintf(
            '<div class="feature-step-number" aria-label="Step %d">%d</div>',
            $config['step_number'],
            $config['step_number']
        );
    }

    /**
     * Render feature icon
     *
     * @param array $config Component configuration
     * @return string Icon HTML
     */
    protected function render_feature_icon($config) {
        if (empty($config['icon'])) {
            return '';
        }

        $icon_classes = [
            'feature-icon',
            'feature-icon-' . $config['icon_type'],
            'feature-icon-size-' . $config['icon_size']
        ];

        $icon_styles = [];
        if (!empty($config['icon_color'])) {
            $icon_styles[] = 'color: ' . $config['icon_color'];
        }
        if (!empty($config['icon_background'])) {
            $icon_styles[] = 'background-color: ' . $config['icon_background'];
        }

        $style_attr = !empty($icon_styles) ? ' style="' . implode('; ', $icon_styles) . '"' : '';

        $icon_html = '';
        switch ($config['icon_type']) {
            case 'emoji':
                $icon_html = esc_html($config['icon']);
                break;
            case 'fontawesome':
                $icon_html = sprintf('<i class="%s" aria-hidden="true"></i>', esc_attr($config['icon']));
                break;
            case 'svg':
                $icon_html = wp_kses($config['icon'], [
                    'svg' => ['width' => [], 'height' => [], 'viewBox' => [], 'fill' => [], 'class' => []],
                    'path' => ['d' => [], 'fill' => [], 'stroke' => [], 'stroke-width' => []],
                    'circle' => ['cx' => [], 'cy' => [], 'r' => [], 'fill' => [], 'stroke' => []],
                    'rect' => ['x' => [], 'y' => [], 'width' => [], 'height' => [], 'fill' => []]
                ]);
                break;
            case 'image':
                $icon_html = sprintf(
                    '<img src="%s" alt="Feature icon" loading="lazy">',
                    esc_url($config['icon'])
                );
                break;
            default:
                $icon_html = esc_html($config['icon']);
        }

        return sprintf(
            '<div class="%s"%s>%s</div>',
            esc_attr(implode(' ', $icon_classes)),
            $style_attr,
            $icon_html
        );
    }

    /**
     * Render feature header
     *
     * @param array $config Component configuration
     * @return string Header HTML
     */
    protected function render_feature_header($config) {
        if (empty($config['title'])) {
            return '';
        }

        $title_tag = !empty($config['link_url']) && !$config['link_entire_card'] ? 'a' : 'h3';
        $title_attrs = '';

        if ($title_tag === 'a') {
            $title_attrs = sprintf(' href="%s" target="%s"',
                esc_url($config['link_url']),
                esc_attr($config['link_target'])
            );
        }

        // Add tooltip if provided
        $tooltip_attr = '';
        if (!empty($config['tooltip'])) {
            $tooltip_attr = sprintf(' title="%s"', esc_attr($config['tooltip']));
        }

        return sprintf(
            '<%s class="card-title feature-title"%s%s>%s</%s>',
            $title_tag,
            $title_attrs,
            $tooltip_attr,
            esc_html($config['title']),
            $title_tag
        );
    }

    /**
     * Render feature subtitle
     *
     * @param array $config Component configuration
     * @return string Subtitle HTML
     */
    protected function render_feature_subtitle($config) {
        return sprintf(
            '<div class="feature-subtitle">%s</div>',
            esc_html($config['subtitle'])
        );
    }

    /**
     * Render highlight text
     *
     * @param array $config Component configuration
     * @return string Highlight HTML
     */
    protected function render_highlight_text($config) {
        return sprintf(
            '<div class="feature-highlight">%s</div>',
            esc_html($config['highlight_text'])
        );
    }

    /**
     * Render feature benefits list
     *
     * @param array $config Component configuration
     * @return string Benefits HTML
     */
    protected function render_feature_benefits($config) {
        if (empty($config['benefits']) || !is_array($config['benefits'])) {
            return '';
        }

        $benefit_items = [];
        foreach ($config['benefits'] as $benefit) {
            $benefit_items[] = sprintf(
                '<li class="feature-benefit-item">✓ %s</li>',
                esc_html($benefit)
            );
        }

        return sprintf(
            '<div class="feature-benefits"><ul class="feature-benefits-list">%s</ul></div>',
            implode('', $benefit_items)
        );
    }

    /**
     * Render feature specifications
     *
     * @param array $config Component configuration
     * @return string Specifications HTML
     */
    protected function render_feature_specifications($config) {
        if (empty($config['specifications']) || !is_array($config['specifications'])) {
            return '';
        }

        $spec_items = [];
        foreach ($config['specifications'] as $spec_key => $spec_value) {
            if (is_numeric($spec_key)) {
                // Simple list item
                $spec_items[] = sprintf(
                    '<li class="feature-spec-item">%s</li>',
                    esc_html($spec_value)
                );
            } else {
                // Key-value pair
                $spec_items[] = sprintf(
                    '<li class="feature-spec-item"><strong>%s:</strong> %s</li>',
                    esc_html($spec_key),
                    esc_html($spec_value)
                );
            }
        }

        return sprintf(
            '<div class="feature-specifications"><h4>Specifications:</h4><ul class="feature-spec-list">%s</ul></div>',
            implode('', $spec_items)
        );
    }

    /**
     * Render feature statistics
     *
     * @param array $config Component configuration
     * @return string Stats HTML
     */
    protected function render_feature_stats($config) {
        if (empty($config['stats']) || !is_array($config['stats'])) {
            return '';
        }

        $stat_items = [];
        foreach ($config['stats'] as $stat) {
            if (is_array($stat) && isset($stat['value']) && isset($stat['label'])) {
                $stat_items[] = sprintf(
                    '<div class="feature-stat-item"><div class="feature-stat-value">%s</div><div class="feature-stat-label">%s</div></div>',
                    esc_html($stat['value']),
                    esc_html($stat['label'])
                );
            }
        }

        return $stat_items ? sprintf(
            '<div class="feature-stats">%s</div>',
            implode('', $stat_items)
        ) : '';
    }

    /**
     * Render expandable content section
     *
     * @param array $config Component configuration
     * @return string Expandable content HTML
     */
    protected function render_expandable_content($config) {
        return sprintf(
            '<div class="feature-expandable-section">
                <button class="feature-expand-toggle" aria-expanded="false">Learn More</button>
                <div class="feature-expanded-content" hidden>%s</div>
            </div>',
            wp_kses_post($config['expanded_content'])
        );
    }

    /**
     * Render feature actions with custom styling
     *
     * @param array $config Component configuration
     * @return string Actions HTML
     */
    protected function render_feature_actions($config) {
        if (empty($config['actions']) || !is_array($config['actions'])) {
            return '';
        }

        $action_buttons = [];
        foreach ($config['actions'] as $action) {
            if (is_array($action) && !empty($action['text']) && !empty($action['url'])) {
                $button_class = 'feature-action-button';

                // Apply CTA style
                if ($config['cta_style'] === 'link') {
                    $button_class .= ' feature-action-link';
                } else {
                    $button_class .= ' feature-action-button';
                }

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

        return $action_buttons ? sprintf(
            '<div class="feature-actions">%s</div>',
            implode('', $action_buttons)
        ) : '';
    }

    /**
     * Get additional design tokens for feature cards
     *
     * @return array Additional design tokens
     */
    public function get_default_tokens() {
        return array_merge(parent::get_default_tokens(), [
            // Icon styling
            'icon_size_small' => '32px',
            'icon_size_medium' => '48px',
            'icon_size_large' => '64px',
            'icon_background_color' => '#f3f4f6',
            'icon_color' => '#374151',
            'icon_border_radius' => '50%',
            'icon_padding' => '16px',

            // Badge styling
            'badge_background' => '#3b82f6',
            'badge_color' => '#ffffff',
            'badge_padding' => '4px 8px',
            'badge_border_radius' => '12px',
            'badge_font_size' => '11px',
            'badge_font_weight' => '600',

            // Tag styling
            'tag_background' => '#f3f4f6',
            'tag_color' => '#374151',
            'tag_padding' => '2px 8px',
            'tag_border_radius' => '4px',
            'tag_font_size' => '12px',

            // Step number styling
            'step_number_background' => '#3b82f6',
            'step_number_color' => '#ffffff',
            'step_number_size' => '32px',
            'step_number_border_radius' => '50%',
            'step_number_font_size' => '14px',
            'step_number_font_weight' => '700',

            // Subtitle styling
            'subtitle_color' => '#6b7280',
            'subtitle_font_size' => '14px',
            'subtitle_margin_bottom' => '12px',

            // Highlight styling
            'highlight_background' => '#fef3c7',
            'highlight_color' => '#92400e',
            'highlight_padding' => '8px 12px',
            'highlight_border_radius' => '6px',
            'highlight_font_weight' => '500',

            // Benefits styling
            'benefits_item_color' => '#059669',
            'benefits_item_font_size' => '14px',
            'benefits_list_margin' => '12px 0',

            // Specifications styling
            'spec_title_color' => '#374151',
            'spec_title_font_size' => '14px',
            'spec_title_font_weight' => '600',
            'spec_item_color' => '#6b7280',
            'spec_item_font_size' => '13px',
            'spec_item_margin' => '4px 0',

            // Stats styling
            'stat_value_color' => '#111827',
            'stat_value_font_size' => '24px',
            'stat_value_font_weight' => '700',
            'stat_label_color' => '#6b7280',
            'stat_label_font_size' => '12px',
            'stat_item_text_align' => 'center',

            // Expandable content
            'expand_toggle_background' => '#f9fafb',
            'expand_toggle_color' => '#374151',
            'expand_toggle_border' => '1px solid #d1d5db',
            'expand_toggle_padding' => '8px 16px',
            'expand_toggle_border_radius' => '6px',
            'expand_toggle_font_size' => '14px',
            'expanded_content_margin_top' => '16px',
            'expanded_content_padding' => '16px',
            'expanded_content_background' => '#f9fafb',
            'expanded_content_border_radius' => '8px',

            // Color schemes
            'primary_accent_color' => '#3b82f6',
            'secondary_accent_color' => '#6b7280',
            'success_accent_color' => '#10b981',
            'warning_accent_color' => '#f59e0b',
            'info_accent_color' => '#06b6d4',

            // Layout styles
            'minimal_padding' => '16px',
            'elevated_shadow' => '0 10px 15px -3px rgba(0, 0, 0, 0.1)',
            'bordered_border' => '2px solid #e5e7eb',

            // Hover effects
            'glow_shadow' => '0 0 20px rgba(59, 130, 246, 0.3)',
            'pulse_animation_duration' => '2s'
        ]);
    }
}
