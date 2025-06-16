<?php
/**
 * Treatment Process Content
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get process data
$treatment_process = get_field('treatment_process') ?: [];
$process_duration = get_field('process_total_duration') ?: '';
$process_preparation = get_field('process_preparation') ?: '';
$process_aftercare = get_field('process_aftercare') ?: '';

// Default process steps if none configured
if (empty($treatment_process)) {
    $treatment_process = [
        [
            'step_title' => 'Consultation',
            'step_description' => 'We discuss your goals and assess your suitability for the treatment.',
            'step_duration' => '15-20 minutes',
            'step_icon' => '👨‍⚕️'
        ],
        [
            'step_title' => 'Preparation',
            'step_description' => 'The treatment area is cleaned and prepared for the procedure.',
            'step_duration' => '5-10 minutes',
            'step_icon' => '🧼'
        ],
        [
            'step_title' => 'Treatment',
            'step_description' => 'The main treatment is performed with precision and care.',
            'step_duration' => '30-45 minutes',
            'step_icon' => '✨'
        ],
        [
            'step_title' => 'Recovery',
            'step_description' => 'Post-treatment care instructions and follow-up scheduling.',
            'step_duration' => '10-15 minutes',
            'step_icon' => '🌟'
        ]
    ];
}
?>

<div class="treatment-process" id="process-content" role="tabpanel" aria-labelledby="tab-process">

    <div class="treatment-process__header">
        <h3 class="treatment-process__heading">Treatment Process</h3>
        <p class="treatment-process__description">
            Here's what you can expect during your treatment session, step by step.
        </p>
        <?php if ($process_duration): ?>
            <div class="treatment-process__duration">
                <span class="treatment-process__duration-icon" aria-hidden="true">⏱️</span>
                <span class="treatment-process__duration-text">Total Duration: <?php echo esc_html($process_duration); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($process_preparation): ?>
        <div class="treatment-process__preparation">
            <?php
            echo ComponentRegistry::render('card', [
                'variant' => 'outlined',
                'size' => 'medium',
                'title' => 'Before Your Treatment',
                'icon' => '📋',
                'content' => wp_kses_post($process_preparation),
                'classes' => ['treatment-process__prep-card']
            ]);
            ?>
        </div>
    <?php endif; ?>

    <div class="treatment-process__steps">
        <div class="treatment-process__timeline">
            <?php foreach ($treatment_process as $index => $step): ?>
                <div class="treatment-process__step" data-step="<?php echo $index + 1; ?>">

                    <div class="treatment-process__step-number">
                        <span class="treatment-process__step-counter"><?php echo $index + 1; ?></span>
                    </div>

                    <div class="treatment-process__step-content">
                        <?php
                        echo ComponentRegistry::render('card', [
                            'variant' => 'elevated',
                            'size' => 'medium',
                            'title' => $step['step_title'] ?? 'Step ' . ($index + 1),
                            'icon' => $step['step_icon'] ?? '•',
                            'content' => '<div class="step-details">' .
                                '<p class="step-description">' . esc_html($step['step_description'] ?? '') . '</p>' .
                                ($step['step_duration'] ? '<div class="step-duration"><span class="step-duration-icon" aria-hidden="true">⏱️</span><span class="step-duration-text">' . esc_html($step['step_duration']) . '</span></div>' : '') .
                                '</div>',
                            'classes' => ['treatment-process__step-card'],
                            'attributes' => [
                                'data-step-number' => $index + 1
                            ]
                        ]);
                        ?>
                    </div>

                    <?php if ($index < count($treatment_process) - 1): ?>
                        <div class="treatment-process__step-connector" aria-hidden="true">
                            <div class="treatment-process__step-line"></div>
                            <div class="treatment-process__step-arrow">↓</div>
                        </div>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($process_aftercare): ?>
        <div class="treatment-process__aftercare">
            <?php
            echo ComponentRegistry::render('card', [
                'variant' => 'filled',
                'size' => 'medium',
                'title' => 'After Your Treatment',
                'icon' => '🌟',
                'content' => wp_kses_post($process_aftercare),
                'classes' => ['treatment-process__aftercare-card']
            ]);
            ?>
        </div>
    <?php endif; ?>

    <div class="treatment-process__summary">
        <?php
        echo ComponentRegistry::render('card', [
            'variant' => 'borderless',
            'size' => 'large',
            'title' => 'Professional Care Every Step',
            'icon' => '🏥',
            'content' => '<p>Our experienced practitioners guide you through each step of the process, ensuring your comfort, safety, and satisfaction throughout your treatment journey.</p>',
            'classes' => ['treatment-process__summary-card']
        ]);
        ?>

        <div class="treatment-process__cta">
            <?php
            echo ComponentRegistry::render('button', [
                'text' => 'Ready to Begin?',
                'url' => '#booking',
                'variant' => 'primary',
                'size' => 'large',
                'icon' => '🚀',
                'icon_position' => 'left',
                'classes' => ['treatment-process__book-button'],
                'attributes' => [
                    'data-scroll-to' => 'booking-content'
                ]
            ]);
            ?>
        </div>
    </div>

</div>
