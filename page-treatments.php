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

<!-- Load consolidated treatments styles -->
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/treatments-consolidated.css" />

<?php get_footer(); ?>

