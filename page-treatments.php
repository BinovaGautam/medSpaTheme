<?php
/**
 * Template Name: Treatments - Luxury Medical Spa Experience
 * Description: Sophisticated aesthetic medicine artistry showcase using semantic components
 *
 * Following TREATMENTS_PAGE_DESIGN.md v4.0 specifications with Sprint 6 semantic components:
 * - Luxury medical spa positioning with consultation-focused journey
 * - Complete elimination of ecommerce patterns
 * - Semantic component integration (CardComponent, TreatmentCard, FeatureCard, ButtonComponent)
 * - WCAG AAA accessibility compliance
 * - Responsive luxury design (320px-767px mobile, 1024px+ desktop)
 * - Design token inheritance from design-system-compiled.css
 */

// DEBUG: Component loading status
if (defined('WP_DEBUG') && WP_DEBUG) {
    error_log('=== TREATMENTS PAGE DEBUG ===');
    error_log('ButtonComponent exists: ' . (class_exists('ButtonComponent') ? 'YES' : 'NO'));
    error_log('TreatmentCard exists: ' . (class_exists('TreatmentCard') ? 'YES' : 'NO'));
    error_log('FeatureCard exists: ' . (class_exists('FeatureCard') ? 'YES' : 'NO'));
    error_log('CardComponent exists: ' . (class_exists('CardComponent') ? 'YES' : 'NO'));
    error_log('ComponentRegistry exists: ' . (class_exists('ComponentRegistry') ? 'YES' : 'NO'));
}

get_header(); ?>



<main id="main" class="treatments-luxury-main" role="main">

    <!-- Immersive Luxury Hero Experience -->
    <section class="treatments-hero-luxury" aria-labelledby="treatments-hero-title">
        <div class="hero-parallax-container">
            <!-- Parallax Video Background -->
            <div class="hero-video-background">
                <video autoplay muted loop playsinline class="hero-video" aria-hidden="true">
                    <source src="<?php echo get_template_directory_uri(); ?>/assets/media/medical-spa-luxury.mp4" type="video/mp4">
                </video>
                <div class="hero-video-overlay"></div>
            </div>

            <!-- Hero Content Container -->
            <div class="hero-content-wrapper">
                <div class="container">
                    <div class="hero-content-luxury">

                        <!-- Sophisticated Title Hierarchy -->
                        <header class="hero-header">
                            <h1 id="treatments-hero-title" class="hero-title-main">
                                The Art of
                                <span class="hero-title-accent">Aesthetic Medicine</span>
                            </h1>
                            <p class="hero-subtitle-luxury">
                                Where board-certified expertise meets sophisticated artistry
                                in personalized aesthetic enhancement
                            </p>
                        </header>

                        <!-- Sophisticated Discovery Invitation using ButtonComponent -->
                        <div class="hero-discovery-invitation">
                            <?php
                            // Use semantic ButtonComponent for hero CTA
                            if (class_exists('ButtonComponent')) {
                                try {
                                    $button_component = new ButtonComponent();
                                    $button_output = $button_component->render([
                                        'text' => 'Discover Your Journey',
                                        'url' => '#treatment-artistry',
                                        'variant' => 'primary',
                                        'size' => 'large',
                                        'icon' => '✨',
                                        'icon_position' => 'right',
                                        'aria_label' => 'Discover our treatment artistry',
                                        'css_class' => 'hero-discovery-btn'
                                    ]);
                                    echo $button_output;

                                    if (defined('WP_DEBUG') && WP_DEBUG) {
                                        error_log('ButtonComponent rendered successfully: ' . strlen($button_output) . ' characters');
                                    }
                                } catch (Exception $e) {
                                    if (defined('WP_DEBUG') && WP_DEBUG) {
                                        error_log('ButtonComponent error: ' . $e->getMessage());
                                    }
                                    // Fallback button
                                    echo '<a href="#treatment-artistry" class="btn btn-primary hero-discovery-btn">Discover Your Journey ✨</a>';
                                }
                            } else {
                                if (defined('WP_DEBUG') && WP_DEBUG) {
                                    error_log('ButtonComponent class not found');
                                }
                                // Fallback button
                                echo '<a href="#treatment-artistry" class="btn btn-primary hero-discovery-btn">Discover Your Journey ✨</a>';
                            }
                            ?>
                        </div>

                        <!-- Luxury Credibility Markers using FeatureCard -->
                        <div class="hero-credibility-luxury">
                            <?php
                            if (class_exists('FeatureCard')) {
                                $feature_card = new FeatureCard();

                                $credibility_features = [
                                    [
                                        'icon' => '✨',
                                        'title' => '15+ Years Medical Excellence',
                                        'feature_type' => 'guarantee',
                                        'style' => 'minimal',
                                        'alignment' => 'center',
                                        'icon_position' => 'left'
                                    ],
                                    [
                                        'icon' => '🏥',
                                        'title' => 'Board-Certified Artistry',
                                        'feature_type' => 'guarantee',
                                        'style' => 'minimal',
                                        'alignment' => 'center',
                                        'icon_position' => 'left'
                                    ],
                                    [
                                        'icon' => '🎯',
                                        'title' => 'Personalized Consultations',
                                        'feature_type' => 'service',
                                        'style' => 'minimal',
                                        'alignment' => 'center',
                                        'icon_position' => 'left'
                                    ]
                                ];

                                foreach ($credibility_features as $feature) {
                                    echo $feature_card->render($feature);
                                }
                            }
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Artistic Category Discovery Section using TreatmentCard -->
    <section id="treatment-artistry" class="treatment-artistry-discovery" aria-labelledby="artistry-title">
        <div class="container">

            <!-- Section Header -->
            <header class="artistry-header">
                <h2 id="artistry-title" class="artistry-title">Treatment Artistry</h2>
                <p class="artistry-subtitle">
                    Explore our specialized categories of aesthetic medicine,
                    each crafted with precision and artistic vision
                </p>
            </header>

            <!-- Artistic Category Grid using TreatmentCard Components -->
            <div class="artistry-categories-grid">

                <?php
                if (class_exists('TreatmentCard')) {
                    try {
                        $treatment_card = new TreatmentCard();

                        if (defined('WP_DEBUG') && WP_DEBUG) {
                            error_log('TreatmentCard instantiated successfully');
                        }

                    $treatment_categories = [
                        [
                            'title' => 'Injectable Artistry',
                            'content' => 'The subtle enhancement of natural beauty through precise neuromodulator and filler artistry by board-certified medical professionals.',
                            'image' => get_template_directory_uri() . '/assets/images/treatments/injectable-artistry.jpg',
                            'image_alt' => 'Sophisticated injectable treatment environment',
                            'category' => 'Injectable',
                            'icon' => '💎',
                            'description' => 'Where medical precision meets artistic vision in the sophisticated enhancement of facial harmony. Our board-certified approach ensures natural-looking results that honor your unique beauty.',
                            'benefits' => [
                                'Board-certified medical professionals',
                                'Natural-looking results',
                                'Personalized treatment plans',
                                'Advanced injection techniques'
                            ],
                            'actions' => [
                                [
                                    'text' => 'Explore This Art Form',
                                    'url' => '#consultation-invitation',
                                    'variant' => 'primary'
                                ]
                            ],
                            'variant' => 'elevated',
                            'size' => 'large'
                        ],
                        [
                            'title' => 'Facial Renaissance',
                            'content' => 'Advanced skincare treatments that rejuvenate and restore your skin\'s natural radiance through medical-grade technology and expertise.',
                            'image' => get_template_directory_uri() . '/assets/images/treatments/facial-renaissance.jpg',
                            'image_alt' => 'Advanced facial treatment artistry',
                            'category' => 'Facial',
                            'icon' => '✨',
                            'description' => 'Transformative facial treatments combining cutting-edge technology with artistic technique to reveal your skin\'s natural luminosity and youthful vitality.',
                            'benefits' => [
                                'Medical-grade technology',
                                'Customized treatment protocols',
                                'Visible results',
                                'Skin health optimization'
                            ],
                            'actions' => [
                                [
                                    'text' => 'Begin Your Renaissance',
                                    'url' => '#consultation-invitation',
                                    'variant' => 'primary'
                                ]
                            ],
                            'variant' => 'elevated',
                            'size' => 'large'
                        ],
                        [
                            'title' => 'Laser Precision',
                            'content' => 'Technology-driven treatments for lasting results with medical precision and safety.',
                            'image' => get_template_directory_uri() . '/assets/images/treatments/laser-precision.jpg',
                            'image_alt' => 'Advanced laser treatment technology',
                            'category' => 'Laser',
                            'icon' => '⚡',
                            'description' => 'State-of-the-art laser technologies delivering transformative results for skin rejuvenation, hair removal, and pigmentation correction with uncompromising safety standards.',
                            'benefits' => [
                                'FDA-approved technologies',
                                'Minimal downtime',
                                'Long-lasting results',
                                'Comprehensive safety protocols'
                            ],
                            'actions' => [
                                [
                                    'text' => 'Discover Precision',
                                    'url' => '#consultation-invitation',
                                    'variant' => 'primary'
                                ]
                            ],
                            'variant' => 'elevated',
                            'size' => 'large'
                        ],
                        [
                            'title' => 'Body Artistry',
                            'content' => 'Advanced body contouring and enhancement treatments for sculpted results and confidence.',
                            'image' => get_template_directory_uri() . '/assets/images/treatments/body-artistry.jpg',
                            'image_alt' => 'Body contouring treatment environment',
                            'category' => 'Body',
                            'icon' => '🎨',
                            'description' => 'Comprehensive body enhancement treatments using advanced technologies to sculpt, tone, and refine your natural contours with artistic precision.',
                            'benefits' => [
                                'Non-invasive options available',
                                'Customized treatment plans',
                                'Natural-looking results',
                                'Confidence enhancement'
                            ],
                            'actions' => [
                                [
                                    'text' => 'Shape Your Vision',
                                    'url' => '#consultation-invitation',
                                    'variant' => 'primary'
                                ]
                            ],
                            'variant' => 'elevated',
                            'size' => 'large'
                        ],
                        [
                            'title' => 'Wellness Sanctuary',
                            'content' => 'Holistic wellness treatments that complement aesthetic enhancements.',
                            'image' => get_template_directory_uri() . '/assets/images/treatments/wellness-sanctuary.jpg',
                            'image_alt' => 'Wellness treatment sanctuary',
                            'category' => 'Wellness',
                            'icon' => '🌿',
                            'description' => 'Integrative wellness treatments designed to enhance your overall well-being while supporting your aesthetic journey through holistic approaches.',
                            'benefits' => [
                                'Holistic approach',
                                'Stress reduction',
                                'Enhanced well-being',
                                'Complementary therapies'
                            ],
                            'actions' => [
                                [
                                    'text' => 'Enter Sanctuary',
                                    'url' => '#consultation-invitation',
                                    'variant' => 'primary'
                                ]
                            ],
                            'variant' => 'elevated',
                            'size' => 'large'
                        ]
                    ];

                    foreach ($treatment_categories as $treatment) {
                        try {
                            echo '<div class="treatment-category-wrapper">';
                            $treatment_output = $treatment_card->render($treatment);
                            echo $treatment_output;
                            echo '</div>';

                            if (defined('WP_DEBUG') && WP_DEBUG) {
                                error_log('TreatmentCard rendered successfully: ' . strlen($treatment_output) . ' characters');
                            }
                        } catch (Exception $e) {
                            if (defined('WP_DEBUG') && WP_DEBUG) {
                                error_log('TreatmentCard render error: ' . $e->getMessage());
                            }
                            // Fallback treatment card
                            echo '<div class="treatment-category-wrapper">';
                            echo '<div class="fallback-treatment-card">';
                            echo '<h3>' . esc_html($treatment['title']) . '</h3>';
                            echo '<p>' . esc_html($treatment['content']) . '</p>';
                            echo '<a href="#consultation-invitation" class="btn btn-primary">Learn More</a>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }

                    } catch (Exception $e) {
                        if (defined('WP_DEBUG') && WP_DEBUG) {
                            error_log('TreatmentCard instantiation error: ' . $e->getMessage());
                        }
                        // Fallback message
                        echo '<div class="component-error">Treatment cards are temporarily unavailable. Please contact us directly.</div>';
                    }
                } else {
                    if (defined('WP_DEBUG') && WP_DEBUG) {
                        error_log('TreatmentCard class not found');
                    }
                    // Fallback message
                    echo '<div class="component-error">Treatment information is loading...</div>';
                }
                ?>

            </div>
        </div>
    </section>

    <!-- Medical Artistry Philosophy Section using CardComponent -->
    <section class="medical-philosophy-section" aria-labelledby="philosophy-title">
        <div class="container">
            <div class="philosophy-content-grid">

                <?php
                if (class_exists('CardComponent')) {
                    $card_component = new CardComponent();

                    // Provider Profile Card
                    echo $card_component->render([
                        'title' => 'Our Medical Philosophy',
                        'content' => '"Aesthetic medicine is the intersection of medical science and artistic vision. Every treatment is personalized to enhance your unique beauty while maintaining natural harmony."',
                        'image' => get_template_directory_uri() . '/assets/images/dr-preeti-portrait.jpg',
                        'image_alt' => 'Dr. Preeti Sharma, Board-certified physician in professional setting',
                        'meta' => [
                            'Dr. Preeti Sharma, MD',
                            'Board-Certified in Aesthetic Medicine',
                            '15+ Years of Artistic Excellence'
                        ],
                        'variant' => 'elevated',
                        'size' => 'large',
                        'image_position' => 'left',
                        'content_alignment' => 'left'
                    ]);
                }
                ?>

            </div>
        </div>
    </section>

    <!-- Personalized Consultation Invitation using FormComponent -->
    <section id="consultation-invitation" class="consultation-invitation-section" aria-labelledby="consultation-title">
        <div class="container">

            <header class="consultation-header">
                <h2 id="consultation-title" class="consultation-title">Begin Your Aesthetic Journey</h2>
                <p class="consultation-subtitle">
                    Every transformation begins with understanding your unique beauty and aesthetic goals
                </p>
            </header>

            <div class="consultation-content-wrapper">

                <?php
                if (class_exists('CardComponent')) {
                    $card_component = new CardComponent();

                    // Consultation Benefits Card
                    echo $card_component->render([
                        'title' => 'Complimentary Consultation Includes:',
                        'content' => '',
                        'variant' => 'filled',
                        'size' => 'large',
                        'custom_classes' => ['consultation-benefits-card']
                    ]);
                }

                // Consultation Benefits using FeatureCard
                if (class_exists('FeatureCard')) {
                    $feature_card = new FeatureCard();

                    $consultation_benefits = [
                        [
                            'icon' => '🔍',
                            'title' => 'Personalized Aesthetic Assessment',
                            'content' => 'Comprehensive evaluation by board-certified physician',
                            'feature_type' => 'service',
                            'style' => 'minimal',
                            'icon_position' => 'left'
                        ],
                        [
                            'icon' => '💬',
                            'title' => 'Goal Discussion & Treatment Options',
                            'content' => 'Detailed conversation about your aesthetic goals and available treatments',
                            'feature_type' => 'service',
                            'style' => 'minimal',
                            'icon_position' => 'left'
                        ],
                        [
                            'icon' => '📋',
                            'title' => 'Customized Treatment Plan',
                            'content' => 'Personalized treatment plan designed for your unique needs',
                            'feature_type' => 'service',
                            'style' => 'minimal',
                            'icon_position' => 'left'
                        ],
                        [
                            'icon' => '🔒',
                            'title' => 'Complete Privacy & Discretion',
                            'content' => 'Confidential consultation in a comfortable, private environment',
                            'feature_type' => 'guarantee',
                            'style' => 'minimal',
                            'icon_position' => 'left'
                        ]
                    ];

                    echo '<div class="consultation-benefits-grid">';
                    foreach ($consultation_benefits as $benefit) {
                        echo $feature_card->render($benefit);
                    }
                    echo '</div>';
                }
                ?>

                <!-- Consultation CTA using ButtonComponent -->
                <div class="consultation-cta-wrapper">
                    <?php
                    if (class_exists('ButtonComponent')) {
                        $button_component = new ButtonComponent();
                        echo $button_component->render([
                            'text' => 'Schedule Your Consultation',
                            'url' => '/contact',
                            'variant' => 'primary',
                            'size' => 'large',
                            'icon' => '📅',
                            'icon_position' => 'left',
                            'aria_label' => 'Schedule your complimentary consultation',
                            'css_class' => 'consultation-primary-cta'
                        ]);
                    }
                    ?>
                </div>

            </div>
        </div>
    </section>

</main>

<style>
/* Treatments Page Semantic Component Styling */
/* Using design tokens from design-system-compiled.css */

.treatments-luxury-main {
    background-color: var(--color-surface-primary);
    color: var(--color-text-primary);
}

/* Hero Section */
.treatments-hero-luxury {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    /* overflow: hidden; - REMOVED: This was preventing page scrolling */
}

.hero-parallax-container {
    position: relative;
    width: 100%;
    height: 100%;
}

.hero-video-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -2;
}

.hero-video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hero-video-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(45, 90, 39, 0.8) 0%, rgba(139, 75, 122, 0.6) 100%);
    z-index: -1;
}

.hero-content-wrapper {
    position: relative;
    z-index: 1;
    padding: var(--space-16) 0;
}

.hero-content-luxury {
    text-align: center;
    color: var(--color-text-inverse);
}

.hero-title-main {
    font-family: var(--font-family-secondary);
    font-size: var(--font-size-4xl);
    font-weight: var(--font-weight-bold);
    line-height: var(--line-height-tight);
    margin-bottom: var(--space-6);
}

.hero-title-accent {
    color: var(--color-accent);
}

.hero-subtitle-luxury {
    font-size: var(--font-size-xl);
    font-weight: var(--font-weight-normal);
    line-height: var(--line-height-relaxed);
    margin-bottom: var(--space-8);
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.hero-discovery-invitation {
    margin-bottom: var(--space-12);
}

.hero-credibility-luxury {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--space-6);
    max-width: 800px;
    margin: 0 auto;
}

/* Treatment Artistry Section */
.treatment-artistry-discovery {
    padding: var(--space-20) 0;
    background-color: var(--color-surface-secondary);
}

.artistry-header {
    text-align: center;
    margin-bottom: var(--space-16);
}

.artistry-title {
    font-family: var(--font-family-secondary);
    font-size: var(--font-size-3xl);
    font-weight: var(--font-weight-semibold);
    color: var(--color-text-primary);
    margin-bottom: var(--space-4);
}

.artistry-subtitle {
    font-size: var(--font-size-lg);
    color: var(--color-text-secondary);
    max-width: 600px;
    margin: 0 auto;
    line-height: var(--line-height-relaxed);
}

.artistry-categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: var(--space-8);
    margin-top: var(--space-12);
}

.treatment-category-wrapper {
    height: 100%;
}

/* Medical Philosophy Section */
.medical-philosophy-section {
    padding: var(--space-20) 0;
    background-color: var(--color-surface-primary);
}

.philosophy-content-grid {
    max-width: 1000px;
    margin: 0 auto;
}

/* Consultation Section */
.consultation-invitation-section {
    padding: var(--space-20) 0;
    background: linear-gradient(135deg, var(--color-surface-secondary) 0%, var(--color-surface-tertiary) 100%);
}

.consultation-header {
    text-align: center;
    margin-bottom: var(--space-16);
}

.consultation-title {
    font-family: var(--font-family-secondary);
    font-size: var(--font-size-3xl);
    font-weight: var(--font-weight-semibold);
    color: var(--color-text-primary);
    margin-bottom: var(--space-4);
}

.consultation-subtitle {
    font-size: var(--font-size-lg);
    color: var(--color-text-secondary);
    max-width: 600px;
    margin: 0 auto;
    line-height: var(--line-height-relaxed);
}

.consultation-benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--space-6);
    margin: var(--space-12) 0;
}

.consultation-cta-wrapper {
    text-align: center;
    margin-top: var(--space-12);
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title-main {
        font-size: var(--font-size-3xl);
    }

    .hero-subtitle-luxury {
        font-size: var(--font-size-lg);
    }

    .artistry-categories-grid {
        grid-template-columns: 1fr;
        gap: var(--space-6);
    }

    .consultation-benefits-grid {
        grid-template-columns: 1fr;
        gap: var(--space-4);
    }
}

/* Accessibility Enhancements */
@media (prefers-reduced-motion: reduce) {
    .hero-video {
        display: none;
    }

    .hero-video-background::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/hero-static-bg.jpg');
        background-size: cover;
        background-position: center;
    }
}

/* High Contrast Mode Support */
@media (prefers-contrast: high) {
    .hero-video-overlay {
        background: rgba(0, 0, 0, 0.8);
    }

    .hero-title-accent {
        color: var(--color-accent-light);
    }
}
</style>

<?php get_footer(); ?>
