<?php
/**
 * Treatment Quick Info Bar Template Part
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

// Get ACF fields for treatment info
$treatment_duration = get_field('treatment_duration');
$treatment_downtime = get_field('treatment_downtime');
$results_timeline = get_field('results_timeline');
$results_duration = get_field('results_duration');

// Check if we have any info to display
$has_info = $treatment_duration || $treatment_downtime || $results_timeline || $results_duration;

if (!$has_info) {
    return;
}
?>

<section class="treatment-info-bar-section">
    <div class="container">
        <div class="treatment-info-bar">

            <?php if ($treatment_duration) : ?>
                <div class="info-item">
                    <div class="info-icon" aria-hidden="true">⏱️</div>
                    <div class="info-content">
                        <span class="info-label"><?php esc_html_e('Duration', 'medspatheme'); ?></span>
                        <span class="info-value"><?php echo esc_html($treatment_duration); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($treatment_downtime) : ?>
                <div class="info-item">
                    <div class="info-icon" aria-hidden="true">💤</div>
                    <div class="info-content">
                        <span class="info-label"><?php esc_html_e('Downtime', 'medspatheme'); ?></span>
                        <span class="info-value"><?php echo esc_html($treatment_downtime); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($results_timeline) : ?>
                <div class="info-item">
                    <div class="info-icon" aria-hidden="true">📅</div>
                    <div class="info-content">
                        <span class="info-label"><?php esc_html_e('Results', 'medspatheme'); ?></span>
                        <span class="info-value"><?php echo esc_html($results_timeline); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($results_duration) : ?>
                <div class="info-item">
                    <div class="info-icon" aria-hidden="true">🔄</div>
                    <div class="info-content">
                        <span class="info-label"><?php esc_html_e('Lasts', 'medspatheme'); ?></span>
                        <span class="info-value"><?php echo esc_html($results_duration); ?></span>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>
