<?php
/**
 * Button Component Demo Template
 *
 * Showcases all ButtonComponent variants and features
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
?>

<div class="button-demo-container" style="padding: 2rem; background: #f9fafb;">
    <h2>Button Component Demo - T6.3 Implementation</h2>

    <div class="button-demo-section">
        <h3>Button Variants</h3>
        <div class="medspa-button-group">
            <?php
            // Primary Button
            component('button', [
                'text' => 'Book Consultation',
                'url' => '#consultation',
                'variant' => 'primary',
                'size' => 'medium'
            ]);

            // Secondary Button
            component('button', [
                'text' => 'Learn More',
                'url' => '/treatments/',
                'variant' => 'secondary',
                'size' => 'medium'
            ]);

            // Outline Button
            component('button', [
                'text' => 'View Gallery',
                'url' => '/gallery/',
                'variant' => 'outline',
                'size' => 'medium'
            ]);

            // Ghost Button
            component('button', [
                'text' => 'Contact Us',
                'url' => '/contact/',
                'variant' => 'ghost',
                'size' => 'medium'
            ]);
            ?>
        </div>
    </div>

    <div class="button-demo-section">
        <h3>Button Sizes</h3>
        <div class="medspa-button-group">
            <?php
            // Small Button
            component('button', [
                'text' => 'Small Button',
                'url' => '#small',
                'variant' => 'primary',
                'size' => 'small'
            ]);

            // Medium Button
            component('button', [
                'text' => 'Medium Button',
                'url' => '#medium',
                'variant' => 'primary',
                'size' => 'medium'
            ]);

            // Large Button
            component('button', [
                'text' => 'Large Button',
                'url' => '#large',
                'variant' => 'primary',
                'size' => 'large'
            ]);
            ?>
        </div>
    </div>

    <div class="button-demo-section">
        <h3>Buttons with Icons</h3>
        <div class="medspa-button-group">
            <?php
            // Left Icon
            component('button', [
                'text' => 'Book Now',
                'url' => '#book',
                'variant' => 'primary',
                'icon' => '📅',
                'icon_position' => 'left'
            ]);

            // Right Icon
            component('button', [
                'text' => 'Learn More',
                'url' => '#learn',
                'variant' => 'secondary',
                'icon' => '→',
                'icon_position' => 'right'
            ]);

            // Icon Only
            component('button', [
                'text' => 'Call Us',
                'url' => 'tel:+1234567890',
                'variant' => 'outline',
                'icon' => '📞',
                'icon_position' => 'only',
                'aria_label' => 'Call Us'
            ]);
            ?>
        </div>
    </div>

    <div class="button-demo-section">
        <h3>Button States</h3>
        <div class="medspa-button-group">
            <?php
            // Normal Button
            component('button', [
                'text' => 'Normal Button',
                'url' => '#normal',
                'variant' => 'primary'
            ]);

            // Disabled Button
            component('button', [
                'text' => 'Disabled Button',
                'url' => '#disabled',
                'variant' => 'primary',
                'disabled' => true
            ]);

            // Loading Button
            component('button', [
                'text' => 'Loading Button',
                'url' => '#loading',
                'variant' => 'primary',
                'loading' => true
            ]);
            ?>
        </div>
    </div>

    <div class="button-demo-section">
        <h3>Form Buttons</h3>
        <div class="medspa-button-group">
            <?php
            // Submit Button
            component('button', [
                'text' => 'Submit Form',
                'type' => 'submit',
                'variant' => 'primary'
            ]);

            // Regular Button
            component('button', [
                'text' => 'Reset Form',
                'type' => 'button',
                'variant' => 'secondary',
                'onclick' => 'resetForm()'
            ]);
            ?>
        </div>
    </div>

    <div class="button-demo-section">
        <h3>Accessibility Features</h3>
        <div class="medspa-button-group">
            <?php
            // Button with ARIA label
            component('button', [
                'text' => 'Download',
                'url' => '/brochure.pdf',
                'variant' => 'outline',
                'icon' => '⬇',
                'aria_label' => 'Download Treatment Brochure PDF',
                'target' => '_blank'
            ]);

            // Button with data attributes
            component('button', [
                'text' => 'Track Click',
                'url' => '#tracked',
                'variant' => 'ghost',
                'data_attributes' => [
                    'tracking' => 'button-click',
                    'category' => 'demo',
                    'action' => 'test'
                ]
            ]);
            ?>
        </div>
    </div>

    <p><strong>Note:</strong> This demo showcases the ButtonComponent system implemented in T6.3.
    All buttons are generated using the unified component system with design token integration.</p>
</div>

<style>
.button-demo-container {
    max-width: 800px;
    margin: 2rem auto;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

.button-demo-section {
    margin: 2rem 0;
    padding: 1.5rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.button-demo-section h3 {
    margin: 0 0 1rem 0;
    color: #1f2937;
    font-size: 1.25rem;
    font-weight: 600;
}

.medspa-button-group {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .medspa-button-group {
        flex-direction: column;
        align-items: stretch;
    }

    .medspa-button-group .medspa-button {
        width: 100%;
        justify-content: center;
    }
}
</style>
