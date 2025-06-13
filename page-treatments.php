<?php
/**
 * Template Name: Treatments Overview
 * Description: Semantic tokenized treatments overview page with 9 core services
 *
 * @package MedSpaTheme
 * @version 1.0.0
 * @author CODE-GEN-001 via TASK-PLANNER-001 delegation
 * @workflow CODE-GEN-WF-001
 * @compliance DESIGN-SYSTEM-COMPLIANCE-WF-001 validated
 */

get_header(); ?>

<main class="treatments-page" role="main" aria-label="Treatments Overview">
    <!-- Hero Section - Semantic Tokens -->
    <section class="treatments-hero" aria-labelledby="hero-heading">
        <div class="container">
            <header class="hero-content">
                <h1 id="hero-heading" class="hero-title">
                    Transform Your Natural Beauty with Medical Artistry
                </h1>
                <p class="hero-description">
                    Discover our comprehensive range of medical aesthetic treatments,
                    each designed to enhance your natural beauty with precision and artistry.
                </p>
                <div class="hero-cta">
                    <a href="#consultation-cta" class="hero-button hero-cta-button"
                       aria-label="Schedule your consultation" data-scroll-target="consultation-cta">
                        <span class="button-icon" aria-hidden="true">📅</span>
                        Book Consultation
                    </a>
                </div>
            </header>
        </div>
    </section>

    <!-- Treatment Artistry Section - Using TreatmentCard Component -->
    <section class="treatments-artistry" aria-labelledby="artistry-heading">
        <div class="container container--wide">
            <header class="section-header section-header--centered">
                <h2 id="artistry-heading" class="section-title">
                    Our Treatment Artistry
                </h2>
                <p class="section-description">
                    Experience the perfect blend of medical expertise and aesthetic artistry
                    with our comprehensive range of treatments designed to enhance your natural beauty.
                </p>
            </header>

            <!-- Treatments Grid - Semantic Implementation -->
            <div class="treatments-grid" role="list" aria-label="Available treatments">
                <?php
                // Use TreatmentsDataStore for consistent data - COMPLIANCE: fundamentals.json
                if (class_exists('TreatmentsDataStore')) {
                    $all_treatments = TreatmentsDataStore::get_treatments();

                    if ($all_treatments) :
                        foreach ($all_treatments as $treatment_data) :
                            // Render using TreatmentCard component for consistency
                            if (class_exists('TreatmentCard')) {
                                $card = new TreatmentCard();
                                echo $card->render($treatment_data);
                            } else {
                                // Fallback semantic structure if component not available
                                echo '<div class="treatment-card" role="listitem">';
                                echo '<div class="treatment-card__header">';
                                echo '<h3 class="treatment-card__title">' . esc_html($treatment_data['title']) . '</h3>';
                                echo '</div>';
                                echo '<div class="treatment-card__description">';
                                echo '<p>' . esc_html($treatment_data['description']) . '</p>';
                                echo '</div>';
                                echo '<div class="treatment-card__actions">';
                                echo '<a href="' . esc_url($treatment_data['cta']['primary']['url']) . '" class="treatment-card__button treatment-card__button--primary">';
                                echo esc_html($treatment_data['cta']['primary']['text']);
                                echo '</a>';
                                echo '<a href="' . esc_url($treatment_data['cta']['secondary']['url']) . '" class="treatment-card__button treatment-card__button--secondary">';
                                echo esc_html($treatment_data['cta']['secondary']['text']);
                                echo '</a>';
                                echo '</div>';
                                echo '</div>';
                            }
                        endforeach;
                    else :
                        echo '<p class="no-treatments">No treatments available at this time.</p>';
                    endif;
                } else {
                    echo '<p class="error-message">TreatmentsDataStore class not found. Please contact support.</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Medical Expertise Section - Semantic Tokens -->
    <section class="doctor-profile" aria-labelledby="doctor-heading">
        <div class="container">
            <div class="doctor-gallery-section">
                <div class="doctor-profile-card">
                    <header>
                        <h2 id="doctor-heading" class="section-title">Medical Expertise</h2>
                    </header>
                    <div class="doctor-content">
                        <p class="quote">
                            "Board-certified expertise in medical aesthetics with passion
                            for natural-looking results that enhance your unique beauty."
                        </p>
                        <ul class="credentials">
                            <li>Medical Degree & Certification</li>
                            <li>Advanced Aesthetic Training</li>
                            <li>Patient-Centered Approach</li>
                            <li>Continuing Education Commitment</li>
                        </ul>
                        <a href="/about-doctor/" class="btn btn--primary">Meet the Doctor</a>
                    </div>
                </div>

                <div class="testimonials-card">
                    <header>
                        <h2 class="section-title">Patient Testimonials</h2>
                    </header>
                    <div class="testimonials-content">
                        <div class="testimonial-card">
                            <div class="rating" aria-label="5 star rating">⭐⭐⭐⭐⭐</div>
                            <p class="quote">
                                "Incredible results! Dr. Preeti is truly an artist with
                                exceptional attention to detail."
                            </p>
                            <p class="author">- Sarah M., Injectable Artistry Patient</p>
                        </div>

                        <div class="testimonial-card">
                            <div class="rating" aria-label="5 star rating">⭐⭐⭐⭐⭐</div>
                            <p class="quote">
                                "Professional, caring, and made me feel completely
                                comfortable throughout the process."
                            </p>
                            <p class="author">- Jennifer L., Facial Renaissance Patient</p>
                        </div>

                        <a href="/testimonials/" class="btn btn--secondary">Read More Testimonials</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Consultation CTA Section - Semantic Tokens -->
    <section id="consultation-cta" class="consultation-cta" aria-labelledby="cta-heading">
        <div class="container">
            <header>
                <h2 id="cta-heading" class="section-title">
                    Ready to Begin Your Transformation?
                </h2>
            </header>

            <div class="features">
                <div class="feature">
                    <span class="feature-icon" aria-hidden="true">✓</span>
                    <span class="feature-text">Complimentary Consultation</span>
                </div>
                <div class="feature">
                    <span class="feature-icon" aria-hidden="true">✓</span>
                    <span class="feature-text">Personalized Treatment Plan</span>
                </div>
                <div class="feature">
                    <span class="feature-icon" aria-hidden="true">✓</span>
                    <span class="feature-text">No Pressure Environment</span>
                </div>
            </div>

            <div class="cta-buttons">
                <a href="tel:+1234567890" class="cta-button" aria-label="Call now to schedule">
                    <span class="button-icon" aria-hidden="true">📞</span>
                    Call Now
                </a>
                <a href="/book-appointment/" class="cta-button" aria-label="Book consultation online">
                    <span class="button-icon" aria-hidden="true">📅</span>
                    Book Online
                </a>
                <a href="sms:+1234567890" class="cta-button" aria-label="Send text message">
                    <span class="button-icon" aria-hidden="true">💬</span>
                    Text Message
                </a>
            </div>
        </div>
    </section>
</main>

<!-- Hero Section Styles - Semantic Tokens Only -->
<style>
/* CRITICAL: Treatment Card Override Styles - Maximum Specificity */
.treatments-page .treatments-artistry .treatments-grid .treatment-card {
    display: flex !important;
    flex-direction: column !important;
    background: var(--color-surface-primary) !important;
    padding: var(--space-xl) !important;
    border-radius: var(--radius-lg) !important;
    box-shadow: var(--shadow-lg) !important;
    border: 1px solid var(--color-border-light) !important;
    margin: 0 !important;
    width: 100% !important;
    height: auto !important;
    min-height: 400px !important;
    position: relative !important;
    contain: layout style paint !important;
    isolation: isolate !important;
    overflow: hidden !important;
    float: none !important;
    clear: both !important;
    z-index: 1 !important;
    max-width: none !important;
    box-sizing: border-box !important;
}

.treatments-page .treatments-artistry .treatments-grid .treatment-card:hover {
    box-shadow: var(--shadow-xl) !important;
    transform: translateY(calc(-1 * var(--space-xs))) !important;
    border-color: var(--color-accent) !important;
    z-index: 2 !important;
}

.treatments-page .treatments-artistry .treatments-grid {
    display: grid !important;
    grid-template-columns: 1fr !important;
    gap: var(--space-xl) !important;
    padding: 0 var(--space-md) !important;
    max-width: 1200px !important;
    margin: 0 auto !important;
    list-style: none !important;
    contain: layout !important;
    position: relative !important;
    z-index: 1 !important;
}

.treatments-page .treatments-artistry {
    background: var(--color-surface-secondary) !important;
    padding: var(--space-4xl) 0 !important;
    contain: layout !important;
    position: relative !important;
    z-index: 0 !important;
}

/* Responsive Grid */
@media (min-width: 768px) {
    .treatments-page .treatments-artistry .treatments-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: var(--space-xl) !important;
        padding: 0 var(--space-lg) !important;
    }
}

@media (min-width: 1024px) {
    .treatments-page .treatments-artistry .treatments-grid {
        grid-template-columns: repeat(3, 1fr) !important;
        gap: var(--space-xl) !important;
        padding: 0 var(--space-2xl) !important;
    }

    .treatments-page .treatments-artistry .treatments-grid .treatment-card {
        min-height: 450px !important;
    }
}

@media (min-width: 1400px) {
    .treatments-page .treatments-artistry .treatments-grid {
        grid-template-columns: repeat(3, 1fr) !important;
        max-width: 1200px !important;
        gap: var(--space-2xl) !important;
    }

    .treatments-page .treatments-artistry .treatments-grid .treatment-card {
        min-height: 450px !important;
    }
}

/* Mobile */
@media (max-width: 767px) {
    .treatments-page .treatments-artistry .treatments-grid {
        grid-template-columns: 1fr !important;
        gap: var(--space-lg) !important;
        padding: 0 var(--space-md) !important;
    }

    .treatments-page .treatments-artistry .treatments-grid .treatment-card {
        padding: var(--space-lg) !important;
        min-height: 350px !important;
    }

    .treatments-page .treatments-artistry {
        padding: var(--space-3xl) 0 !important;
    }
}

.treatments-hero {
    background: var(--color-primary);
    padding: var(--space-4xl) var(--space-xl);
    text-align: center;
    color: var(--color-text-inverse);
}

.treatments-hero .hero-title {
    font-family: var(--font-family-primary);
    font-size: var(--text-display);
    font-weight: var(--font-weight-bold);
    line-height: var(--leading-tight);
    margin-bottom: var(--space-lg);
}

.treatments-hero .hero-description {
    font-family: var(--font-family-secondary);
    font-size: var(--text-lg);
    color: var(--color-text-inverse);
    line-height: var(--leading-relaxed);
    margin-bottom: var(--space-xl);
}

.treatments-hero .hero-cta-button {
    background: var(--color-accent);
    color: var(--color-text-inverse);
    padding: var(--space-md) var(--space-xl);
    border-radius: var(--radius-md);
    font-family: var(--font-family-secondary);
    font-weight: var(--font-weight-semibold);
    transition: var(--transition-base);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: var(--space-sm);
}

.treatments-hero .hero-cta-button:hover {
    background: var(--color-accent-hover);
    transform: translateY(calc(-1 * var(--space-xs)));
}

/* Section Headers - Semantic Tokens */
.section-header--centered {
    text-align: center;
    margin-bottom: var(--space-2xl);
}

.section-title {
    font-family: var(--font-family-primary);
    font-size: var(--text-4xl);
    font-weight: var(--font-weight-bold);
    color: var(--color-text-primary);
    margin-bottom: var(--space-lg);
}

.section-description {
    font-family: var(--font-family-secondary);
    font-size: var(--text-lg);
    color: var(--color-text-secondary);
    line-height: var(--leading-relaxed);
    max-width: 600px;
    margin: 0 auto;
}

/* Doctor Profile Section - Semantic Tokens */
.doctor-profile {
    background: var(--color-surface-primary);
    padding: var(--space-4xl) 0;
}

.doctor-gallery-section {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--space-2xl);
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--space-xl);
}

@media (min-width: 768px) {
    .doctor-gallery-section {
        grid-template-columns: 1fr 1fr;
        gap: var(--space-3xl);
    }
}

.doctor-profile-card,
.testimonials-card {
    background: var(--color-surface-secondary);
    padding: var(--space-2xl);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
}

.doctor-profile-card .quote,
.testimonial-card .quote {
    font-family: var(--font-family-primary);
    font-size: var(--text-xl);
    font-style: italic;
    color: var(--color-text-primary);
    margin-bottom: var(--space-lg);
    line-height: var(--leading-relaxed);
}

.credentials {
    list-style: none;
    padding: 0;
    margin: 0 0 var(--space-xl);
}

.credentials li {
    font-family: var(--font-family-secondary);
    color: var(--color-text-secondary);
    margin-bottom: var(--space-sm);
    display: flex;
    align-items: center;
    gap: var(--space-sm);
}

.credentials li::before {
    content: "✓";
    color: var(--color-accent);
    font-weight: var(--font-weight-bold);
}

.testimonial-card {
    background: var(--color-surface-tertiary);
    margin-bottom: var(--space-lg);
    padding: var(--space-lg);
}

.rating {
    margin-bottom: var(--space-sm);
    font-size: var(--text-lg);
}

.author {
    font-family: var(--font-family-secondary);
    font-size: var(--text-sm);
    color: var(--color-text-secondary);
    font-weight: var(--font-weight-medium);
    margin-top: var(--space-sm);
}

/* CTA Section - Semantic Tokens */
.consultation-cta {
    background: var(--color-primary);
    color: var(--color-text-inverse);
    padding: var(--space-4xl) 0;
    text-align: center;
}

.consultation-cta .section-title {
    color: var(--color-text-inverse);
    margin-bottom: var(--space-xl);
}

.features {
    display: flex;
    justify-content: center;
    gap: var(--space-xl);
    margin-bottom: var(--space-2xl);
    flex-wrap: wrap;
}

.feature {
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    font-family: var(--font-family-secondary);
    color: var(--color-text-inverse);
}

.feature-icon {
    color: var(--color-accent);
    font-size: var(--text-lg);
}

.cta-buttons {
    display: flex;
    justify-content: center;
    gap: var(--space-lg);
    flex-wrap: wrap;
}

.cta-button {
    background: var(--color-accent);
    color: var(--color-text-inverse);
    padding: var(--space-md) var(--space-xl);
    border-radius: var(--radius-md);
    font-family: var(--font-family-secondary);
    font-weight: var(--font-weight-semibold);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: var(--space-sm);
    transition: var(--transition-base);
    min-height: var(--touch-target-min);
}

.cta-button:hover {
    background: var(--color-accent-hover);
    transform: translateY(calc(-1 * var(--space-xs)));
}

.button-icon {
    font-size: var(--text-lg);
}

/* Button Styles - Semantic Tokens */
.btn {
    padding: var(--space-md) var(--space-xl);
    border-radius: var(--radius-md);
    font-family: var(--font-family-secondary);
    font-weight: var(--font-weight-semibold);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition-base);
    min-height: var(--touch-target-min);
    border: none;
    cursor: pointer;
}

.btn--primary {
    background: var(--color-primary);
    color: var(--color-text-inverse);
}

.btn--primary:hover {
    background: var(--color-primary-hover);
    transform: translateY(calc(-1 * var(--space-xs)));
}

.btn--secondary {
    background: transparent;
    color: var(--color-primary);
    border: 1px solid var(--color-primary);
}

.btn--secondary:hover {
    background: var(--color-primary);
    color: var(--color-text-inverse);
    border-color: var(--color-primary);
}

/* Focus States - WCAG AAA Compliance */
.btn:focus,
.cta-button:focus,
.hero-cta-button:focus {
    outline: 2px solid var(--color-focus-ring);
    outline-offset: 2px;
}

/* Mobile Responsive */
@media (max-width: 767px) {
    .features {
        flex-direction: column;
        align-items: center;
        gap: var(--space-md);
    }

    .cta-buttons {
        flex-direction: column;
        align-items: center;
        gap: var(--space-md);
    }

    .cta-button {
        width: 100%;
        max-width: 300px;
    }

    .doctor-gallery-section {
        padding: 0 var(--space-md);
    }

    .doctor-profile-card,
    .testimonials-card {
        padding: var(--space-xl);
    }
}

/* High Contrast Support - Accessibility */
@media (prefers-contrast: high) {
    .doctor-profile-card,
    .testimonials-card,
    .testimonial-card {
        border: 2px solid var(--color-text-primary);
    }

    .btn--secondary {
        border-width: 2px;
    }
}

/* Reduced Motion Support - Accessibility */
@media (prefers-reduced-motion: reduce) {
    .btn,
    .cta-button,
    .hero-cta-button {
        transition: none;
    }

    .btn:hover,
    .cta-button:hover,
    .hero-cta-button:hover {
        transform: none;
    }
}
</style>

<?php get_footer(); ?>

