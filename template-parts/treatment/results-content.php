<?php
/**
 * Treatment Results Content
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get results data
$treatment_results = get_field('treatment_results') ?: '';
$results_timeline = get_field('results_timeline') ?: [];
$before_after_gallery = get_field('before_after_gallery') ?: [];
$treatment_testimonials = get_field('treatment_testimonials') ?: [];
$results_duration = get_field('results_duration') ?: '';
$results_maintenance = get_field('results_maintenance') ?: '';

// Default timeline if none configured
if (empty($results_timeline)) {
    $results_timeline = [
        [
            'timeframe' => 'Immediately',
            'description' => 'Initial results may be visible right after treatment.',
            'percentage' => '20'
        ],
        [
            'timeframe' => '1-2 Weeks',
            'description' => 'Noticeable improvements become more apparent.',
            'percentage' => '60'
        ],
        [
            'timeframe' => '4-6 Weeks',
            'description' => 'Full results are typically achieved.',
            'percentage' => '100'
        ]
    ];
}
?>

<div class="treatment-results" id="results-content" role="tabpanel" aria-labelledby="tab-results">

    <div class="treatment-results__header">
        <h3 class="treatment-results__heading">Expected Results</h3>
        <p class="treatment-results__description">
            See what you can expect from your treatment and how results develop over time.
        </p>
    </div>

    <?php if ($treatment_results): ?>
        <div class="treatment-results__overview">
            <?php
            echo ComponentRegistry::render('card', [
                'variant' => 'elevated',
                'size' => 'large',
                'title' => 'What to Expect',
                'icon' => '✨',
                'content' => wp_kses_post($treatment_results),
                'classes' => ['treatment-results__overview-card']
            ]);
            ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($results_timeline)): ?>
        <div class="treatment-results__timeline">
            <h4 class="treatment-results__timeline-heading">Results Timeline</h4>
            <div class="treatment-results__timeline-grid">
                <?php foreach ($results_timeline as $index => $milestone): ?>
                    <?php
                    echo ComponentRegistry::render('card', [
                        'variant' => 'outlined',
                        'size' => 'medium',
                        'title' => $milestone['timeframe'] ?? '',
                        'icon' => '📅',
                        'content' => '<div class="timeline-milestone">' .
                            '<p class="milestone-description">' . esc_html($milestone['description'] ?? '') . '</p>' .
                            ($milestone['percentage'] ? '<div class="milestone-progress"><div class="progress-bar"><div class="progress-fill" style="width: ' . esc_attr($milestone['percentage']) . '%"></div></div><span class="progress-text">' . esc_html($milestone['percentage']) . '% Results</span></div>' : '') .
                            '</div>',
                        'classes' => ['treatment-results__timeline-card'],
                        'attributes' => [
                            'data-timeline-step' => $index + 1
                        ]
                    ]);
                    ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($before_after_gallery)): ?>
        <div class="treatment-results__gallery">
            <h4 class="treatment-results__gallery-heading">Before & After Gallery</h4>
            <p class="treatment-results__gallery-description">
                Real results from our patients. Individual results may vary.
            </p>

            <div class="treatment-results__gallery-grid">
                <?php foreach ($before_after_gallery as $index => $gallery_item): ?>
                    <div class="treatment-results__gallery-item">
                        <?php
                        $gallery_content = '<div class="before-after-comparison">';

                        if ($gallery_item['before_image']) {
                            $gallery_content .= '<div class="before-image">';
                            $gallery_content .= '<img src="' . esc_url($gallery_item['before_image']['url']) . '" alt="Before treatment" loading="lazy">';
                            $gallery_content .= '<span class="image-label">Before</span>';
                            $gallery_content .= '</div>';
                        }

                        if ($gallery_item['after_image']) {
                            $gallery_content .= '<div class="after-image">';
                            $gallery_content .= '<img src="' . esc_url($gallery_item['after_image']['url']) . '" alt="After treatment" loading="lazy">';
                            $gallery_content .= '<span class="image-label">After</span>';
                            $gallery_content .= '</div>';
                        }

                        if ($gallery_item['description']) {
                            $gallery_content .= '<p class="comparison-description">' . esc_html($gallery_item['description']) . '</p>';
                        }

                        $gallery_content .= '</div>';

                        echo ComponentRegistry::render('card', [
                            'variant' => 'default',
                            'size' => 'medium',
                            'content' => $gallery_content,
                            'classes' => ['treatment-results__gallery-card'],
                            'attributes' => [
                                'data-gallery-item' => $index + 1
                            ]
                        ]);
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="treatment-results__gallery-disclaimer">
                <p class="disclaimer-text">
                    <em>Results shown are not guaranteed and may vary between individuals.
                    These photos are of actual patients who have provided consent for their use.</em>
                </p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($treatment_testimonials)): ?>
        <div class="treatment-results__testimonials">
            <h4 class="treatment-results__testimonials-heading">Patient Experiences</h4>
            <div class="treatment-results__testimonials-grid">
                <?php foreach ($treatment_testimonials as $testimonial): ?>
                    <?php
                    $testimonial_content = '<blockquote class="testimonial-quote">';
                    $testimonial_content .= '<p>"' . esc_html($testimonial['testimonial_text'] ?? '') . '"</p>';
                    $testimonial_content .= '</blockquote>';
                    $testimonial_content .= '<div class="testimonial-author">';
                    $testimonial_content .= '<strong>' . esc_html($testimonial['patient_name'] ?? 'Anonymous') . '</strong>';
                    if ($testimonial['treatment_date']) {
                        $testimonial_content .= '<span class="testimonial-date">' . esc_html($testimonial['treatment_date']) . '</span>';
                    }
                    $testimonial_content .= '</div>';

                    echo ComponentRegistry::render('card', [
                        'variant' => 'filled',
                        'size' => 'medium',
                        'icon' => '💬',
                        'content' => $testimonial_content,
                        'classes' => ['treatment-results__testimonial-card']
                    ]);
                    ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="treatment-results__details">
        <div class="treatment-results__details-grid">

            <?php if ($results_duration): ?>
                <?php
                echo ComponentRegistry::render('card', [
                    'variant' => 'outlined',
                    'size' => 'medium',
                    'title' => 'Results Duration',
                    'icon' => '⏰',
                    'content' => wp_kses_post($results_duration),
                    'classes' => ['treatment-results__duration-card']
                ]);
                ?>
            <?php endif; ?>

            <?php if ($results_maintenance): ?>
                <?php
                echo ComponentRegistry::render('card', [
                    'variant' => 'outlined',
                    'size' => 'medium',
                    'title' => 'Maintenance',
                    'icon' => '🔄',
                    'content' => wp_kses_post($results_maintenance),
                    'classes' => ['treatment-results__maintenance-card']
                ]);
                ?>
            <?php endif; ?>

        </div>
    </div>

    <div class="treatment-results__cta">
        <?php
        echo ComponentRegistry::render('card', [
            'variant' => 'borderless',
            'size' => 'large',
            'title' => 'Ready for Your Transformation?',
            'icon' => '🌟',
            'content' => '<p>Join the many satisfied patients who have achieved their aesthetic goals with our professional treatments.</p>',
            'classes' => ['treatment-results__cta-card']
        ]);
        ?>

        <div class="treatment-results__cta-actions">
            <?php
            echo ComponentRegistry::render('button', [
                'text' => 'Book Your Treatment',
                'url' => '#booking',
                'variant' => 'primary',
                'size' => 'large',
                'icon' => '📅',
                'icon_position' => 'left',
                'classes' => ['treatment-results__book-button'],
                'attributes' => [
                    'data-scroll-to' => 'booking-content'
                ]
            ]);

            echo ComponentRegistry::render('button', [
                'text' => 'Schedule Consultation',
                'url' => get_permalink(get_page_by_path('contact')),
                'variant' => 'outline',
                'size' => 'large',
                'icon' => '👨‍⚕️',
                'icon_position' => 'left',
                'classes' => ['treatment-results__consult-button']
            ]);
            ?>
        </div>
    </div>

</div>
