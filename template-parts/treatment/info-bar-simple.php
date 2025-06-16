<?php
/**
 * Simple Treatment Quick Info Bar Template Part (No ACF)
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */
?>

<section class="treatment-info-bar-section">
    <div class="container">
        <div class="treatment-info-bar">

            <div class="info-item">
                <div class="info-icon" aria-hidden="true">⏱️</div>
                <div class="info-content">
                    <span class="info-label"><?php esc_html_e('Duration', 'medspatheme'); ?></span>
                    <span class="info-value">30-45 minutes</span>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon" aria-hidden="true">💤</div>
                <div class="info-content">
                    <span class="info-label"><?php esc_html_e('Downtime', 'medspatheme'); ?></span>
                    <span class="info-value">24-48 hours</span>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon" aria-hidden="true">📅</div>
                <div class="info-content">
                    <span class="info-label"><?php esc_html_e('Results', 'medspatheme'); ?></span>
                    <span class="info-value">7-14 days</span>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon" aria-hidden="true">🔄</div>
                <div class="info-content">
                    <span class="info-label"><?php esc_html_e('Lasts', 'medspatheme'); ?></span>
                    <span class="info-value">3-6 months</span>
                </div>
            </div>

        </div>
    </div>
</section>
