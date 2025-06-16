<?php
/**
 * Treatment FAQs Content
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get FAQ data
$treatment_faqs = get_field('treatment_faqs') ?: [];
$general_faqs = get_field('general_treatment_faqs') ?: [];

// Combine treatment-specific and general FAQs
$all_faqs = array_merge($treatment_faqs, $general_faqs);
?>

<div class="treatment-faqs" id="faqs-content" role="tabpanel" aria-labelledby="tab-faqs">

    <?php if (!empty($all_faqs)): ?>

        <div class="treatment-faqs__header">
            <h3 class="treatment-faqs__heading">Frequently Asked Questions</h3>
            <p class="treatment-faqs__description">
                Find answers to common questions about this treatment. If you have additional questions,
                please don't hesitate to contact our team.
            </p>
        </div>

        <?php
        // Prepare FAQ data for AccordionComponent
        $faq_items = [];
        foreach ($all_faqs as $index => $faq) {
            $faq_items[] = [
                'id' => 'faq-' . ($index + 1),
                'title' => $faq['question'] ?? '',
                'content' => $faq['answer'] ?? '',
                'icon' => '❓',
                'default_open' => $index === 0 // First FAQ open by default
            ];
        }

        // Render FAQ accordion using AccordionComponent
        echo ComponentRegistry::render('accordion', [
            'type' => 'faq',
            'layout' => 'boxed',
            'animation' => 'slide',
            'allow_multiple' => true,
            'items' => $faq_items,
            'classes' => ['treatment-faqs__accordion'],
            'search_enabled' => count($faq_items) > 5, // Enable search if more than 5 FAQs
            'schema_enabled' => true, // Enable FAQ schema markup
            'attributes' => [
                'aria-label' => 'Treatment Frequently Asked Questions'
            ]
        ]);
        ?>

        <div class="treatment-faqs__contact">
            <div class="treatment-faqs__contact-card">
                <h4 class="treatment-faqs__contact-heading">Still Have Questions?</h4>
                <p class="treatment-faqs__contact-text">
                    Our experienced team is here to help you make an informed decision about your treatment.
                </p>
                <div class="treatment-faqs__contact-actions">
                    <?php
                    // Use ButtonComponent for contact actions
                    echo ComponentRegistry::render('button', [
                        'text' => 'Schedule Consultation',
                        'url' => '#booking',
                        'variant' => 'primary',
                        'size' => 'medium',
                        'icon' => '📅',
                        'icon_position' => 'left',
                        'classes' => ['treatment-faqs__contact-button']
                    ]);

                    echo ComponentRegistry::render('button', [
                        'text' => 'Call Us Now',
                        'url' => 'tel:' . get_theme_mod('contact_phone', ''),
                        'variant' => 'outline',
                        'size' => 'medium',
                        'icon' => '📞',
                        'icon_position' => 'left',
                        'classes' => ['treatment-faqs__contact-button']
                    ]);
                    ?>
                </div>
            </div>
        </div>

    <?php else: ?>

        <div class="treatment-faqs__empty">
            <div class="treatment-faqs__empty-content">
                <span class="treatment-faqs__empty-icon" aria-hidden="true">❓</span>
                <h3 class="treatment-faqs__empty-heading">No FAQs Available</h3>
                <p class="treatment-faqs__empty-text">
                    FAQs for this treatment are being updated. Please contact us directly for any questions.
                </p>
                <?php
                echo ComponentRegistry::render('button', [
                    'text' => 'Contact Us',
                    'url' => get_permalink(get_page_by_path('contact')),
                    'variant' => 'primary',
                    'size' => 'medium',
                    'classes' => ['treatment-faqs__empty-button']
                ]);
                ?>
            </div>
        </div>

    <?php endif; ?>

</div>
