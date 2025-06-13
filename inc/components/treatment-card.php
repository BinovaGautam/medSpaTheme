<?php
/**
 * Treatment Card Component
 *
 * Specialized card component for displaying medical spa treatments
 * following semantic design system and WCAG AAA compliance
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
 * for displaying medical spa treatments with semantic tokens
 * and accessibility features.
 */
class TreatmentCard extends CardComponent {
    /**
     * Get component default configuration
     *
     * @return array Default configuration
     */
    public function get_defaults() {
        return [
            'id' => '',
            'title' => '',
            'icon' => '',
            'description' => '',
            'benefits' => [],
            'duration' => '',
            'cta' => [
                'primary' => [
                    'text' => 'Book Now',
                    'url' => '#'
                ],
                'secondary' => [
                    'text' => 'Learn More',
                    'url' => '#'
                ]
            ],
            'schema' => [
                '@type' => 'MedicalProcedure',
                'name' => '',
                'description' => '',
                'bodyLocation' => '',
                'procedureType' => ''
            ],
            'image' => [
                'src' => '',
                'alt' => ''
            ]
        ];
    }

    /**
     * Render the treatment card
     *
     * @param array $args Component arguments
     * @return string HTML output
     */
    public function render($args = []) {
        $config = wp_parse_args($args, $this->get_defaults());
        $config = $this->sanitize_config($config);

        ob_start();
        ?>
        <article class="treatment-card" id="treatment-<?php echo esc_attr($config['id']); ?>"
                <?php if (!empty($config['schema'])) : ?>
                    itemscope itemtype="https://schema.org/<?php echo esc_attr($config['schema']['@type']); ?>"
                <?php endif; ?>>

            <header class="treatment-card__header">
                <?php if ($config['icon']) : ?>
                    <span class="treatment-card__icon" aria-hidden="true"><?php echo $config['icon']; ?></span>
                <?php endif; ?>

                <h3 class="treatment-card__title" <?php if (!empty($config['schema'])) echo 'itemprop="name"'; ?>>
                    <?php echo esc_html($config['title']); ?>
                </h3>
            </header>

            <div class="treatment-card__description" <?php if (!empty($config['schema'])) echo 'itemprop="description"'; ?>>
                <?php echo esc_html($config['description']); ?>
            </div>

            <?php if (!empty($config['benefits'])) : ?>
                <ul class="treatment-card__benefits">
                    <?php foreach ($config['benefits'] as $benefit) : ?>
                        <li class="treatment-card__benefit"><?php echo esc_html($benefit); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <div class="treatment-card__meta">
                <?php if ($config['duration']) : ?>
                    <div class="treatment-card__duration">
                        <span class="treatment-card__duration-icon" aria-hidden="true">⏱</span>
                        <span class="treatment-card__duration-text"><?php echo esc_html($config['duration']); ?></span>
                    </div>
                <?php endif; ?>

                <div class="treatment-card__consultation">
                    <span class="treatment-card__consultation-icon" aria-hidden="true">💬</span>
                    <span class="treatment-card__consultation-text">Consultation Required</span>
                </div>
            </div>

            <div class="treatment-card__actions">
                <?php if ($config['cta']['primary']) : ?>
                    <a href="<?php echo esc_url($config['cta']['primary']['url']); ?>"
                       class="treatment-card__button treatment-card__button--primary">
                        <?php echo esc_html($config['cta']['primary']['text']); ?>
                    </a>
                <?php endif; ?>

                <?php if ($config['cta']['secondary']) : ?>
                    <a href="<?php echo esc_url($config['cta']['secondary']['url']); ?>"
                       class="treatment-card__button treatment-card__button--secondary">
                        <?php echo esc_html($config['cta']['secondary']['text']); ?>
                    </a>
                <?php endif; ?>
            </div>

            <?php if (!empty($config['schema'])) : ?>
                <script type="application/ld+json">
                    <?php echo wp_json_encode($config['schema']); ?>
                </script>
            <?php endif; ?>
        </article>
        <?php
        return ob_get_clean();
    }

    /**
     * Sanitize configuration
     *
     * @param array $config Configuration to sanitize
     * @return array Sanitized configuration
     */
    protected function sanitize_config($config) {
        $config['id'] = sanitize_title($config['id']);
        $config['title'] = sanitize_text_field($config['title']);
        $config['icon'] = sanitize_text_field($config['icon']);
        $config['description'] = sanitize_text_field($config['description']);
        $config['duration'] = sanitize_text_field($config['duration']);

        if (is_array($config['benefits'])) {
            $config['benefits'] = array_map('sanitize_text_field', $config['benefits']);
        }

        if (is_array($config['cta'])) {
            foreach (['primary', 'secondary'] as $type) {
                if (isset($config['cta'][$type])) {
                    $config['cta'][$type]['text'] = sanitize_text_field($config['cta'][$type]['text']);
                    $config['cta'][$type]['url'] = esc_url_raw($config['cta'][$type]['url']);
                }
            }
        }

        if (is_array($config['schema'])) {
            $config['schema'] = array_map('sanitize_text_field', $config['schema']);
        }

        if (is_array($config['image'])) {
            $config['image']['src'] = esc_url_raw($config['image']['src']);
            $config['image']['alt'] = sanitize_text_field($config['image']['alt']);
        }

        return $config;
    }

    /**
     * Render a grid of treatment cards
     *
     * @param array $treatments Array of treatment data
     * @param array $args Additional arguments
     * @return string HTML output
     */
    public static function render_grid($treatments, $args = []) {
        $output = '<div class="treatments-grid">';

        foreach ($treatments as $treatment) {
            $card = new self();
            $output .= $card->render($treatment);
        }

        $output .= '</div>';

        return $output;
    }

    /**
     * Get default design tokens for treatment cards
     *
     * @return array Default design tokens
     */
    public function get_default_tokens() {
        return array_merge(parent::get_default_tokens(), [
            'card' => [
                'background' => 'var(--color-surface)',
                'padding' => 'var(--space-xl)',
                'border-radius' => 'var(--radius-lg)',
                'box-shadow' => 'var(--shadow-md)',
                'transition' => 'var(--transition-base)',
            ],
            'header' => [
                'margin-bottom' => 'var(--space-md)',
                'gap' => 'var(--space-sm)',
            ],
            'icon' => [
                'font-size' => 'var(--text-2xl)',
                'line-height' => '1',
            ],
            'title' => [
                'font-family' => 'var(--font-family-primary)',
                'font-size' => 'var(--text-2xl)',
                'font-weight' => 'var(--font-weight-semibold)',
                'color' => 'var(--color-text-primary)',
                'line-height' => 'var(--leading-tight)',
            ],
            'description' => [
                'font-family' => 'var(--font-family-secondary)',
                'font-size' => 'var(--text-base)',
                'color' => 'var(--color-text-secondary)',
                'line-height' => 'var(--leading-normal)',
                'margin-bottom' => 'var(--space-lg)',
            ],
            'benefits' => [
                'margin-bottom' => 'var(--space-lg)',
                'gap' => 'var(--space-sm)',
                'item' => [
                    'font-family' => 'var(--font-family-secondary)',
                    'font-size' => 'var(--text-sm)',
                    'color' => 'var(--color-text-secondary)',
                    'gap' => 'var(--space-xs)',
                ],
                'icon' => [
                    'color' => 'var(--color-accent)',
                    'font-weight' => 'var(--font-weight-bold)',
                ],
            ],
            'meta' => [
                'margin-bottom' => 'var(--space-lg)',
                'padding-top' => 'var(--space-md)',
                'border-top' => '1px solid var(--color-border)',
            ],
            'duration' => [
                'font-family' => 'var(--font-family-secondary)',
                'font-size' => 'var(--text-sm)',
                'color' => 'var(--color-text-secondary)',
                'gap' => 'var(--space-xs)',
            ],
            'consultation' => [
                'font-family' => 'var(--font-family-secondary)',
                'font-size' => 'var(--text-sm)',
                'font-weight' => 'var(--font-weight-medium)',
                'color' => 'var(--color-accent)',
                'gap' => 'var(--space-xs)',
            ],
            'actions' => [
                'gap' => 'var(--space-sm)',
                'margin-top' => 'auto',
            ],
            'button' => [
                'padding' => 'var(--space-sm) var(--space-md)',
                'border-radius' => 'var(--radius-md)',
                'font-family' => 'var(--font-family-secondary)',
                'font-weight' => 'var(--font-weight-medium)',
                'transition' => 'var(--transition-base)',
                'min-height' => 'var(--touch-target-min)',
                'primary' => [
                    'background' => 'var(--color-interactive-primary)',
                    'color' => 'var(--color-text-inverse)',
                    'hover' => [
                        'background' => 'var(--color-interactive-hover)',
                    ],
                ],
                'secondary' => [
                    'background' => 'transparent',
                    'color' => 'var(--color-interactive-secondary)',
                    'border' => '1px solid var(--color-interactive-secondary)',
                    'hover' => [
                        'background' => 'var(--color-interactive-hover)',
                        'color' => 'var(--color-text-inverse)',
                        'border-color' => 'var(--color-interactive-hover)',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Get component specific tokens
     *
     * @return array Component specific tokens
     */
    protected function get_component_specific_tokens() {
        return [
            'treatment-card' => [
                'icon-size' => 'var(--text-2xl)',
                'benefit-icon-color' => 'var(--color-accent)',
                'meta-border-color' => 'var(--color-border)',
                'consultation-color' => 'var(--color-accent)',
                'consultation-font-size' => 'var(--text-sm)',
            ],
        ];
    }

    /**
     * Get accessibility configuration
     *
     * @return array Accessibility configuration
     */
    protected function get_accessibility_config() {
        return array_merge(parent::get_accessibility_config(), [
            'aria-labels' => [
                'duration' => __('Treatment duration', 'medspa'),
                'consultation' => __('Consultation required', 'medspa'),
                'benefits' => __('Treatment benefits', 'medspa'),
            ],
            'roles' => [
                'article' => 'article',
                'list' => 'list',
                'listitem' => 'listitem',
            ],
        ]);
    }
}
