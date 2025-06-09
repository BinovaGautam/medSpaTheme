<?php
/**
 * Treatment Card Component
 *
 * Specialized card component for displaying medical spa treatments
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
 * TreatmentCard Class
 *
 * Extends CardComponent to provide specialized functionality
 * for displaying medical spa treatments with pricing, duration,
 * benefits, and booking CTAs.
 */
class TreatmentCard extends CardComponent {

    /**
     * Get component default configuration for treatments
     *
     * @return array Default configuration
     */
    public function get_defaults() {
        return array_merge(parent::get_defaults(), [
            // Treatment-specific content
            'duration' => '',
            'price' => '',
            'price_from' => false,
            'benefits' => [],
            'features' => [],
            'suitability' => '',

            // Treatment actions
            'cta_text' => 'Learn More',
            'cta_url' => '#',
            'book_text' => 'Book Consultation',
            'book_url' => '#consultation',
            'phone_booking' => '',

            // Treatment meta information
            'category' => '',
            'popularity' => '', // popular, trending, new
            'pain_level' => '', // minimal, mild, moderate
            'downtime' => '',
            'results_timeline' => '',

            // Pricing display
            'show_price_range' => false,
            'price_details' => '',
            'financing_available' => false,

            // Visual customization
            'show_badge' => true,
            'badge_text' => '',
            'highlight_popular' => true
        ]);
    }

    /**
     * Render the treatment card
     *
     * @param array $args Component arguments
     * @return string HTML output
     */
    public function render($args = []) {
        $config = wp_parse_args($args, $this->get_defaults());

        // Sanitize treatment-specific fields
        $config = $this->sanitize_treatment_config($config);

        // Add treatment-specific classes
        $config['custom_classes'] = array_merge(
            $config['custom_classes'] ?? [],
            ['treatment-card']
        );

        // Add popular/trending badge classes
        if (!empty($config['popularity'])) {
            $config['custom_classes'][] = 'treatment-' . $config['popularity'];
        }

        // Override meta with treatment-specific information
        $config['meta'] = $this->generate_treatment_meta($config);

        // Override actions with treatment-specific CTAs
        $config['actions'] = $this->generate_treatment_actions($config);

        // Add treatment badge if enabled
        if ($config['show_badge'] && !empty($config['badge_text'])) {
            $config['badge'] = $config['badge_text'];
        }

        return parent::render($config);
    }

    /**
     * Sanitize treatment-specific configuration
     *
     * @param array $config Configuration to sanitize
     * @return array Sanitized configuration
     */
    protected function sanitize_treatment_config($config) {
        // Sanitize treatment fields
        $config['duration'] = sanitize_text_field($config['duration']);
        $config['price'] = sanitize_text_field($config['price']);
        $config['suitability'] = sanitize_text_field($config['suitability']);
        $config['category'] = sanitize_text_field($config['category']);
        $config['popularity'] = sanitize_text_field($config['popularity']);
        $config['pain_level'] = sanitize_text_field($config['pain_level']);
        $config['downtime'] = sanitize_text_field($config['downtime']);
        $config['results_timeline'] = sanitize_text_field($config['results_timeline']);
        $config['price_details'] = sanitize_text_field($config['price_details']);
        $config['badge_text'] = sanitize_text_field($config['badge_text']);

        // Sanitize URLs
        $config['cta_url'] = esc_url($config['cta_url']);
        $config['book_url'] = esc_url($config['book_url']);

        // Sanitize CTA text
        $config['cta_text'] = sanitize_text_field($config['cta_text']);
        $config['book_text'] = sanitize_text_field($config['book_text']);

        // Sanitize phone
        $config['phone_booking'] = sanitize_text_field($config['phone_booking']);

        // Sanitize arrays
        if (is_array($config['benefits'])) {
            $config['benefits'] = array_map('sanitize_text_field', $config['benefits']);
        }

        if (is_array($config['features'])) {
            $config['features'] = array_map('sanitize_text_field', $config['features']);
        }

        return $config;
    }

    /**
     * Generate treatment-specific meta information
     *
     * @param array $config Component configuration
     * @return array Meta information
     */
    protected function generate_treatment_meta($config) {
        $meta_items = [];

        // Duration
        if (!empty($config['duration'])) {
            $meta_items[] = '⏱️ ' . $config['duration'];
        }

        // Price
        if (!empty($config['price'])) {
            $price_text = $config['price_from'] ? 'From ' . $config['price'] : $config['price'];
            $meta_items[] = '💰 ' . $price_text;
        }

        // Pain level
        if (!empty($config['pain_level'])) {
            $pain_icons = [
                'minimal' => '😌',
                'mild' => '😐',
                'moderate' => '😬'
            ];
            $icon = $pain_icons[$config['pain_level']] ?? '😐';
            $meta_items[] = $icon . ' ' . ucfirst($config['pain_level']) . ' discomfort';
        }

        // Downtime
        if (!empty($config['downtime'])) {
            $meta_items[] = '🏠 ' . $config['downtime'] . ' downtime';
        }

        // Results timeline
        if (!empty($config['results_timeline'])) {
            $meta_items[] = '📈 Results in ' . $config['results_timeline'];
        }

        return $meta_items;
    }

    /**
     * Generate treatment-specific action buttons
     *
     * @param array $config Component configuration
     * @return array Action buttons
     */
    protected function generate_treatment_actions($config) {
        $actions = [];

        // Primary CTA (Learn More)
        if (!empty($config['cta_text']) && !empty($config['cta_url'])) {
            $actions[] = [
                'text' => $config['cta_text'],
                'url' => $config['cta_url'],
                'variant' => 'primary',
                'target' => '_self'
            ];
        }

        // Booking CTA
        if (!empty($config['book_text']) && !empty($config['book_url'])) {
            $actions[] = [
                'text' => $config['book_text'],
                'url' => $config['book_url'],
                'variant' => 'secondary',
                'target' => '_self'
            ];
        }

        // Phone booking (if provided)
        if (!empty($config['phone_booking'])) {
            $actions[] = [
                'text' => '📞 Call Now',
                'url' => 'tel:' . $config['phone_booking'],
                'variant' => 'outline',
                'target' => '_self'
            ];
        }

        return $actions;
    }

    /**
     * Override card content generation to include treatment-specific sections
     *
     * @param array $config Component configuration
     * @return string Card content HTML
     */
    protected function generate_card_content($config) {
        $content_parts = [];

        // Add treatment badge if enabled
        if ($config['show_badge'] && !empty($config['badge_text'])) {
            $content_parts['badge'] = $this->render_treatment_badge($config);
        }

        // Card image with overlay for popular treatments
        if (!empty($config['image'])) {
            $content_parts['image'] = $this->render_treatment_image($config);
        }

        // Card header (title + category)
        if (!empty($config['title']) || !empty($config['category'])) {
            $content_parts['header'] = $this->render_treatment_header($config);
        }

        // Treatment benefits section
        if (!empty($config['benefits'])) {
            $content_parts['benefits'] = $this->render_treatment_benefits($config);
        }

        // Card content (description)
        if (!empty($config['content'])) {
            $content_parts['content'] = $this->render_card_content_section($config);
        }

        // Treatment features section
        if (!empty($config['features'])) {
            $content_parts['features'] = $this->render_treatment_features($config);
        }

        // Treatment meta information
        if (!empty($config['meta'])) {
            $content_parts['meta'] = $this->render_treatment_meta_section($config);
        }

        // Price section with details
        if (!empty($config['price'])) {
            $content_parts['pricing'] = $this->render_treatment_pricing($config);
        }

        // Card actions
        if (!empty($config['actions'])) {
            $content_parts['actions'] = $this->render_card_actions($config);
        }

        return implode('', $content_parts);
    }

    /**
     * Render treatment badge
     *
     * @param array $config Component configuration
     * @return string Badge HTML
     */
    protected function render_treatment_badge($config) {
        $badge_class = 'treatment-badge';

        if (!empty($config['popularity'])) {
            $badge_class .= ' badge-' . $config['popularity'];
        }

        return sprintf(
            '<div class="%s">%s</div>',
            esc_attr($badge_class),
            esc_html($config['badge_text'])
        );
    }

    /**
     * Render treatment image with potential overlay
     *
     * @param array $config Component configuration
     * @return string Image HTML
     */
    protected function render_treatment_image($config) {
        $image_html = parent::render_card_image($config);

        // Add popular overlay if treatment is popular
        if ($config['highlight_popular'] && $config['popularity'] === 'popular') {
            $overlay = '<div class="treatment-popular-overlay">⭐ Most Popular</div>';
            $image_html = str_replace('</div>', $overlay . '</div>', $image_html);
        }

        return $image_html;
    }

    /**
     * Render treatment header with category
     *
     * @param array $config Component configuration
     * @return string Header HTML
     */
    protected function render_treatment_header($config) {
        $header_content = '';

        // Category
        if (!empty($config['category'])) {
            $header_content .= sprintf(
                '<div class="treatment-category">%s</div>',
                esc_html($config['category'])
            );
        }

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
                '<%s class="card-title treatment-title"%s>%s</%s>',
                $title_tag,
                $title_attrs,
                esc_html($config['title']),
                $title_tag
            );
        }

        return $header_content ? '<div class="card-header treatment-header">' . $header_content . '</div>' : '';
    }

    /**
     * Render treatment benefits list
     *
     * @param array $config Component configuration
     * @return string Benefits HTML
     */
    protected function render_treatment_benefits($config) {
        if (empty($config['benefits']) || !is_array($config['benefits'])) {
            return '';
        }

        $benefit_items = [];
        foreach ($config['benefits'] as $benefit) {
            $benefit_items[] = sprintf(
                '<li class="treatment-benefit-item">✓ %s</li>',
                esc_html($benefit)
            );
        }

        return sprintf(
            '<div class="treatment-benefits"><h4>Key Benefits:</h4><ul class="treatment-benefits-list">%s</ul></div>',
            implode('', $benefit_items)
        );
    }

    /**
     * Render treatment features list
     *
     * @param array $config Component configuration
     * @return string Features HTML
     */
    protected function render_treatment_features($config) {
        if (empty($config['features']) || !is_array($config['features'])) {
            return '';
        }

        $feature_items = [];
        foreach ($config['features'] as $feature) {
            $feature_items[] = sprintf(
                '<span class="treatment-feature-item">%s</span>',
                esc_html($feature)
            );
        }

        return sprintf(
            '<div class="treatment-features">%s</div>',
            implode(' • ', $feature_items)
        );
    }

    /**
     * Render treatment meta information section
     *
     * @param array $config Component configuration
     * @return string Meta section HTML
     */
    protected function render_treatment_meta_section($config) {
        if (empty($config['meta']) || !is_array($config['meta'])) {
            return '';
        }

        $meta_items = [];
        foreach ($config['meta'] as $meta_item) {
            $meta_items[] = sprintf(
                '<div class="treatment-meta-item">%s</div>',
                esc_html($meta_item)
            );
        }

        return sprintf(
            '<div class="treatment-meta">%s</div>',
            implode('', $meta_items)
        );
    }

    /**
     * Render treatment pricing section with details
     *
     * @param array $config Component configuration
     * @return string Pricing HTML
     */
    protected function render_treatment_pricing($config) {
        $pricing_content = '';

        // Main price
        $price_text = $config['price_from'] ? 'From ' . $config['price'] : $config['price'];
        $pricing_content .= sprintf(
            '<div class="treatment-price">%s</div>',
            esc_html($price_text)
        );

        // Price details
        if (!empty($config['price_details'])) {
            $pricing_content .= sprintf(
                '<div class="treatment-price-details">%s</div>',
                esc_html($config['price_details'])
            );
        }

        // Financing note
        if ($config['financing_available']) {
            $pricing_content .= '<div class="treatment-financing">💳 Financing Available</div>';
        }

        return sprintf(
            '<div class="treatment-pricing">%s</div>',
            $pricing_content
        );
    }

    /**
     * Get additional design tokens for treatment cards
     *
     * @return array Additional design tokens
     */
    public function get_default_tokens() {
        return array_merge(parent::get_default_tokens(), [
            // Treatment-specific tokens
            'badge_background_color' => '#f59e0b',
            'badge_text_color' => '#ffffff',
            'badge_border_radius' => '16px',
            'badge_padding' => '4px 12px',
            'badge_font_size' => '12px',
            'badge_font_weight' => '600',

            // Category styling
            'category_color' => '#6b7280',
            'category_font_size' => '14px',
            'category_font_weight' => '500',
            'category_margin_bottom' => '4px',

            // Benefits styling
            'benefits_title_color' => '#374151',
            'benefits_title_font_size' => '16px',
            'benefits_title_font_weight' => '600',
            'benefits_item_color' => '#10b981',
            'benefits_item_font_size' => '14px',

            // Features styling
            'features_color' => '#6b7280',
            'features_font_size' => '14px',
            'features_separator_color' => '#d1d5db',

            // Meta styling
            'meta_item_background' => '#f3f4f6',
            'meta_item_padding' => '6px 12px',
            'meta_item_border_radius' => '12px',
            'meta_item_font_size' => '13px',
            'meta_item_margin' => '4px',

            // Pricing styling
            'price_color' => '#059669',
            'price_font_size' => '24px',
            'price_font_weight' => '700',
            'price_details_color' => '#6b7280',
            'price_details_font_size' => '12px',
            'financing_color' => '#3b82f6',
            'financing_font_size' => '13px',

            // Popular overlay
            'popular_overlay_background' => 'linear-gradient(45deg, #f59e0b, #d97706)',
            'popular_overlay_color' => '#ffffff',
            'popular_overlay_font_size' => '12px',
            'popular_overlay_font_weight' => '600'
        ]);
    }
}
