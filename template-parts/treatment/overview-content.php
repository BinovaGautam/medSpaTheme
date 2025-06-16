<?php
/**
 * Treatment Overview Content
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get treatment data
$treatment_overview = get_field('treatment_overview') ?: '';
$treatment_benefits = get_field('treatment_benefits') ?: [];
$treatment_ideal_for = get_field('treatment_ideal_for') ?: [];
$treatment_what_to_expect = get_field('treatment_what_to_expect') ?: '';
?>

<div class="treatment-overview" id="overview-content" role="tabpanel" aria-labelledby="tab-overview">

    <?php if ($treatment_overview): ?>
        <div class="treatment-overview__description">
            <h3 class="treatment-overview__heading">About This Treatment</h3>
            <div class="treatment-overview__text">
                <?php echo wp_kses_post($treatment_overview); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="treatment-overview__cards">

        <?php if (!empty($treatment_benefits)): ?>
            <?php
            // Use CardComponent for benefits
            echo ComponentRegistry::render('card', [
                'variant' => 'elevated',
                'size' => 'medium',
                'title' => 'Key Benefits',
                'icon' => '✨',
                'content' => '<ul class="benefits-list">' .
                    implode('', array_map(function($benefit) {
                        return '<li class="benefits-list__item">' . esc_html($benefit) . '</li>';
                    }, $treatment_benefits)) .
                    '</ul>',
                'classes' => ['treatment-overview__benefits-card']
            ]);
            ?>
        <?php endif; ?>

        <?php if (!empty($treatment_ideal_for)): ?>
            <?php
            // Use CardComponent for ideal candidates
            echo ComponentRegistry::render('card', [
                'variant' => 'outlined',
                'size' => 'medium',
                'title' => 'Ideal For',
                'icon' => '👤',
                'content' => '<ul class="ideal-for-list">' .
                    implode('', array_map(function($candidate) {
                        return '<li class="ideal-for-list__item">' . esc_html($candidate) . '</li>';
                    }, $treatment_ideal_for)) .
                    '</ul>',
                'classes' => ['treatment-overview__ideal-card']
            ]);
            ?>
        <?php endif; ?>

        <?php if ($treatment_what_to_expect): ?>
            <?php
            // Use CardComponent for what to expect
            echo ComponentRegistry::render('card', [
                'variant' => 'filled',
                'size' => 'medium',
                'title' => 'What to Expect',
                'icon' => '📋',
                'content' => wp_kses_post($treatment_what_to_expect),
                'classes' => ['treatment-overview__expect-card']
            ]);
            ?>
        <?php endif; ?>

    </div>

    <?php
    // Safety information card if available
    $safety_info = get_field('treatment_safety_info');
    if ($safety_info):
    ?>
        <div class="treatment-overview__safety">
            <?php
            echo ComponentRegistry::render('card', [
                'variant' => 'outlined',
                'size' => 'large',
                'title' => 'Important Safety Information',
                'icon' => '⚠️',
                'content' => wp_kses_post($safety_info),
                'classes' => ['treatment-overview__safety-card', 'treatment-overview__safety-card--important'],
                'attributes' => [
                    'role' => 'alert',
                    'aria-live' => 'polite'
                ]
            ]);
            ?>
        </div>
    <?php endif; ?>

</div>
