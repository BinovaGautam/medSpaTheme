<?php
/**
 * Testimonial Card Component
 *
 * Specialized card component for displaying client testimonials
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
 * TestimonialCard Class
 *
 * Extends CardComponent to provide specialized functionality
 * for displaying client testimonials with star ratings,
 * author information, and treatment details.
 */
class TestimonialCard extends CardComponent {

    /**
     * Get component default configuration for testimonials
     *
     * @return array Default configuration
     */
    public function get_defaults() {
        return array_merge(parent::get_defaults(), [
            // Testimonial-specific content
            'testimonial_content' => '',
            'author_name' => '',
            'author_title' => '',
            'author_location' => '',
            'author_image' => '',
            'author_verified' => false,

            // Rating system
            'rating' => 5,
            'max_rating' => 5,
            'show_rating' => true,
            'rating_text' => '',

            // Treatment information
            'treatment' => '',
            'treatment_url' => '',
            'treatment_category' => '',
            'treatment_date' => '',
            'results_achieved' => [],

            // Testimonial meta
            'date' => '',
            'source' => '', // google, facebook, internal, etc.
            'verified_source' => false,
            'featured' => false,
            'video_testimonial' => false,
            'video_url' => '',

            // Display options
            'show_quote_marks' => true,
            'show_author_image' => true,
            'show_treatment_info' => true,
            'layout' => 'standard', // standard, compact, featured

            // Social proof
            'helpful_votes' => 0,
            'show_helpful' => false,
            'social_share' => false
        ]);
    }

    /**
     * Render the testimonial card
     *
     * @param array $args Component arguments
     * @return string HTML output
     */
    public function render($args = []) {
        $config = wp_parse_args($args, $this->get_defaults());

        // Sanitize testimonial-specific fields
        $config = $this->sanitize_testimonial_config($config);

        // Add testimonial-specific classes
        $config['custom_classes'] = array_merge(
            $config['custom_classes'] ?? [],
            ['testimonial-card', 'testimonial-layout-' . $config['layout']]
        );

        // Add featured class if featured
        if ($config['featured']) {
            $config['custom_classes'][] = 'testimonial-featured';
        }

        // Add verified class if verified
        if ($config['author_verified']) {
            $config['custom_classes'][] = 'testimonial-verified';
        }

        // Override content with testimonial content
        $config['content'] = $config['testimonial_content'];

        // Set role for testimonials
        $config['role'] = 'article';
        $config['aria_label'] = 'Testimonial from ' . $config['author_name'];

        return parent::render($config);
    }

    /**
     * Sanitize testimonial-specific configuration
     *
     * @param array $config Configuration to sanitize
     * @return array Sanitized configuration
     */
    protected function sanitize_testimonial_config($config) {
        // Sanitize text fields
        $config['testimonial_content'] = wp_kses_post($config['testimonial_content']);
        $config['author_name'] = sanitize_text_field($config['author_name']);
        $config['author_title'] = sanitize_text_field($config['author_title']);
        $config['author_location'] = sanitize_text_field($config['author_location']);
        $config['treatment'] = sanitize_text_field($config['treatment']);
        $config['treatment_category'] = sanitize_text_field($config['treatment_category']);
        $config['source'] = sanitize_text_field($config['source']);
        $config['layout'] = sanitize_text_field($config['layout']);
        $config['rating_text'] = sanitize_text_field($config['rating_text']);

        // Sanitize URLs
        $config['author_image'] = esc_url($config['author_image']);
        $config['treatment_url'] = esc_url($config['treatment_url']);
        $config['video_url'] = esc_url($config['video_url']);

        // Sanitize numbers
        $config['rating'] = absint($config['rating']);
        $config['max_rating'] = absint($config['max_rating']);
        $config['helpful_votes'] = absint($config['helpful_votes']);

        // Ensure rating is within bounds
        if ($config['rating'] > $config['max_rating']) {
            $config['rating'] = $config['max_rating'];
        }

        // Sanitize dates
        $config['date'] = sanitize_text_field($config['date']);
        $config['treatment_date'] = sanitize_text_field($config['treatment_date']);

        // Sanitize arrays
        if (is_array($config['results_achieved'])) {
            $config['results_achieved'] = array_map('sanitize_text_field', $config['results_achieved']);
        }

        return $config;
    }

    /**
     * Override card content generation to include testimonial-specific sections
     *
     * @param array $config Component configuration
     * @return string Card content HTML
     */
    protected function generate_card_content($config) {
        $content_parts = [];

        // Featured badge for featured testimonials
        if ($config['featured']) {
            $content_parts['featured_badge'] = $this->render_featured_badge();
        }

        // Rating section
        if ($config['show_rating'] && $config['rating'] > 0) {
            $content_parts['rating'] = $this->render_rating_section($config);
        }

        // Quote marks (if enabled)
        if ($config['show_quote_marks']) {
            $content_parts['quote_start'] = '<div class="testimonial-quote-start">"</div>';
        }

        // Testimonial content
        if (!empty($config['testimonial_content'])) {
            $content_parts['content'] = $this->render_testimonial_content($config);
        }

        // Quote marks end
        if ($config['show_quote_marks']) {
            $content_parts['quote_end'] = '<div class="testimonial-quote-end">"</div>';
        }

        // Results achieved section
        if (!empty($config['results_achieved'])) {
            $content_parts['results'] = $this->render_results_section($config);
        }

        // Author information
        $content_parts['author'] = $this->render_author_section($config);

        // Treatment information
        if ($config['show_treatment_info'] && !empty($config['treatment'])) {
            $content_parts['treatment'] = $this->render_treatment_section($config);
        }

        // Source and verification
        $content_parts['meta'] = $this->render_testimonial_meta($config);

        // Video testimonial button
        if ($config['video_testimonial'] && !empty($config['video_url'])) {
            $content_parts['video'] = $this->render_video_button($config);
        }

        // Helpful votes section
        if ($config['show_helpful'] && $config['helpful_votes'] > 0) {
            $content_parts['helpful'] = $this->render_helpful_section($config);
        }

        return implode('', $content_parts);
    }

    /**
     * Render featured badge
     *
     * @return string Featured badge HTML
     */
    protected function render_featured_badge() {
        return '<div class="testimonial-featured-badge">⭐ Featured Review</div>';
    }

    /**
     * Render rating section with stars
     *
     * @param array $config Component configuration
     * @return string Rating HTML
     */
    protected function render_rating_section($config) {
        $stars_html = '';

        // Generate star icons
        for ($i = 1; $i <= $config['max_rating']; $i++) {
            $star_class = $i <= $config['rating'] ? 'star-filled' : 'star-empty';
            $stars_html .= sprintf('<span class="testimonial-star %s">★</span>', $star_class);
        }

        $rating_content = sprintf(
            '<div class="testimonial-stars">%s</div>',
            $stars_html
        );

        // Add rating text if provided
        if (!empty($config['rating_text'])) {
            $rating_content .= sprintf(
                '<div class="testimonial-rating-text">%s</div>',
                esc_html($config['rating_text'])
            );
        }

        // Add numerical rating
        $rating_content .= sprintf(
            '<div class="testimonial-rating-number">%d/%d stars</div>',
            $config['rating'],
            $config['max_rating']
        );

        return sprintf(
            '<div class="testimonial-rating">%s</div>',
            $rating_content
        );
    }

    /**
     * Render testimonial content with proper formatting
     *
     * @param array $config Component configuration
     * @return string Content HTML
     */
    protected function render_testimonial_content($config) {
        return sprintf(
            '<div class="testimonial-content">%s</div>',
            wp_kses_post($config['testimonial_content'])
        );
    }

    /**
     * Render results achieved section
     *
     * @param array $config Component configuration
     * @return string Results HTML
     */
    protected function render_results_section($config) {
        if (empty($config['results_achieved']) || !is_array($config['results_achieved'])) {
            return '';
        }

        $results_items = [];
        foreach ($config['results_achieved'] as $result) {
            $results_items[] = sprintf(
                '<span class="testimonial-result-item">✓ %s</span>',
                esc_html($result)
            );
        }

        return sprintf(
            '<div class="testimonial-results"><strong>Results Achieved:</strong> %s</div>',
            implode(', ', $results_items)
        );
    }

    /**
     * Render author information section
     *
     * @param array $config Component configuration
     * @return string Author HTML
     */
    protected function render_author_section($config) {
        $author_content = '';

        // Author image
        if ($config['show_author_image'] && !empty($config['author_image'])) {
            $author_content .= sprintf(
                '<div class="testimonial-author-image"><img src="%s" alt="%s" loading="lazy"></div>',
                esc_url($config['author_image']),
                esc_attr($config['author_name'] . ' photo')
            );
        }

        // Author details container
        $author_details = '';

        // Author name with verification
        if (!empty($config['author_name'])) {
            $verified_icon = $config['author_verified'] ? ' <span class="verified-icon">✓</span>' : '';
            $author_details .= sprintf(
                '<div class="testimonial-author-name">%s%s</div>',
                esc_html($config['author_name']),
                $verified_icon
            );
        }

        // Author title/role
        if (!empty($config['author_title'])) {
            $author_details .= sprintf(
                '<div class="testimonial-author-title">%s</div>',
                esc_html($config['author_title'])
            );
        }

        // Author location
        if (!empty($config['author_location'])) {
            $author_details .= sprintf(
                '<div class="testimonial-author-location">📍 %s</div>',
                esc_html($config['author_location'])
            );
        }

        if ($author_details) {
            $author_content .= sprintf(
                '<div class="testimonial-author-details">%s</div>',
                $author_details
            );
        }

        return sprintf(
            '<div class="testimonial-author">%s</div>',
            $author_content
        );
    }

    /**
     * Render treatment information section
     *
     * @param array $config Component configuration
     * @return string Treatment HTML
     */
    protected function render_treatment_section($config) {
        $treatment_content = '';

        // Treatment name (with link if URL provided)
        $treatment_name = $config['treatment'];
        if (!empty($config['treatment_url'])) {
            $treatment_name = sprintf(
                '<a href="%s" class="testimonial-treatment-link">%s</a>',
                esc_url($config['treatment_url']),
                esc_html($config['treatment'])
            );
        }

        $treatment_content .= sprintf(
            '<div class="testimonial-treatment-name">Treatment: %s</div>',
            $treatment_name
        );

        // Treatment category
        if (!empty($config['treatment_category'])) {
            $treatment_content .= sprintf(
                '<div class="testimonial-treatment-category">Category: %s</div>',
                esc_html($config['treatment_category'])
            );
        }

        // Treatment date
        if (!empty($config['treatment_date'])) {
            $treatment_content .= sprintf(
                '<div class="testimonial-treatment-date">Completed: %s</div>',
                esc_html($config['treatment_date'])
            );
        }

        return sprintf(
            '<div class="testimonial-treatment">%s</div>',
            $treatment_content
        );
    }

    /**
     * Render testimonial meta information (source, date, verification)
     *
     * @param array $config Component configuration
     * @return string Meta HTML
     */
    protected function render_testimonial_meta($config) {
        $meta_items = [];

        // Source with verification
        if (!empty($config['source'])) {
            $source_text = ucfirst($config['source']);
            if ($config['verified_source']) {
                $source_text .= ' ✓';
            }
            $meta_items[] = sprintf('Source: %s', $source_text);
        }

        // Date
        if (!empty($config['date'])) {
            $meta_items[] = esc_html($config['date']);
        }

        if (empty($meta_items)) {
            return '';
        }

        return sprintf(
            '<div class="testimonial-meta">%s</div>',
            implode(' • ', $meta_items)
        );
    }

    /**
     * Render video testimonial button
     *
     * @param array $config Component configuration
     * @return string Video button HTML
     */
    protected function render_video_button($config) {
        return sprintf(
            '<div class="testimonial-video"><a href="%s" class="testimonial-video-button" target="_blank">🎥 Watch Video Testimonial</a></div>',
            esc_url($config['video_url'])
        );
    }

    /**
     * Render helpful votes section
     *
     * @param array $config Component configuration
     * @return string Helpful section HTML
     */
    protected function render_helpful_section($config) {
        return sprintf(
            '<div class="testimonial-helpful">👍 %d people found this helpful</div>',
            $config['helpful_votes']
        );
    }

    /**
     * Get additional design tokens for testimonial cards
     *
     * @return array Additional design tokens
     */
    public function get_default_tokens() {
        return array_merge(parent::get_default_tokens(), [
            // Testimonial-specific tokens
            'quote_font_size' => '48px',
            'quote_color' => '#d1d5db',
            'quote_font_weight' => '700',

            // Rating styling
            'star_color_filled' => '#fbbf24',
            'star_color_empty' => '#e5e7eb',
            'star_size' => '20px',
            'rating_margin_bottom' => '16px',

            // Author styling
            'author_image_size' => '60px',
            'author_image_border_radius' => '50%',
            'author_name_font_size' => '16px',
            'author_name_font_weight' => '600',
            'author_name_color' => '#111827',
            'author_title_font_size' => '14px',
            'author_title_color' => '#6b7280',
            'author_location_font_size' => '13px',
            'author_location_color' => '#9ca3af',

            // Verification styling
            'verified_icon_color' => '#10b981',
            'verified_icon_size' => '16px',

            // Treatment styling
            'treatment_background' => '#f9fafb',
            'treatment_padding' => '12px',
            'treatment_border_radius' => '8px',
            'treatment_border' => '1px solid #e5e7eb',
            'treatment_link_color' => '#3b82f6',

            // Results styling
            'results_background' => '#ecfdf5',
            'results_border' => '1px solid #10b981',
            'results_padding' => '12px',
            'results_border_radius' => '8px',
            'results_item_color' => '#059669',

            // Featured badge styling
            'featured_badge_background' => 'linear-gradient(45deg, #f59e0b, #d97706)',
            'featured_badge_color' => '#ffffff',
            'featured_badge_padding' => '4px 12px',
            'featured_badge_border_radius' => '16px',
            'featured_badge_font_size' => '12px',
            'featured_badge_font_weight' => '600',

            // Meta styling
            'meta_font_size' => '12px',
            'meta_color' => '#9ca3af',
            'meta_margin_top' => '16px',

            // Video button styling
            'video_button_background' => '#ef4444',
            'video_button_color' => '#ffffff',
            'video_button_padding' => '8px 16px',
            'video_button_border_radius' => '20px',
            'video_button_font_size' => '14px',
            'video_button_hover_background' => '#dc2626',

            // Helpful section styling
            'helpful_background' => '#f3f4f6',
            'helpful_color' => '#374151',
            'helpful_padding' => '8px 12px',
            'helpful_border_radius' => '16px',
            'helpful_font_size' => '13px',

            // Layout variants
            'compact_padding' => '16px',
            'featured_border' => '2px solid #f59e0b',
            'featured_shadow' => '0 8px 25px -5px rgba(245, 158, 11, 0.2)'
        ]);
    }
}
