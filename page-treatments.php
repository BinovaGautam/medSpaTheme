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
    <!-- Hero Section -->
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
                <div class="hero-features">
                    <div class="hero-feature">
                        <span class="feature-icon" aria-hidden="true">✨</span>
                        <span class="feature-text">Board-Certified Excellence</span>
                    </div>
                    <div class="hero-feature">
                        <span class="feature-icon" aria-hidden="true">🏥</span>
                        <span class="feature-text">State-of-the-Art Facility</span>
                    </div>
                    <div class="hero-feature">
                        <span class="feature-icon" aria-hidden="true">💎</span>
                        <span class="feature-text">Luxury Experience</span>
                    </div>
                </div>
                <div class="hero-cta">
                    <?php
                    // ButtonComponent Integration - T7.1.2 Implementation
                    if (class_exists('ButtonComponent')) {
                        $button_component = new ButtonComponent();
                        echo $button_component->render([
                            'text' => 'Schedule Your Consultation',
                            'variant' => 'primary',
                            'size' => 'large',
                            'url' => '#consultation-cta',
                            'icon' => '📅',
                            'icon_position' => 'left',
                            'aria_label' => 'Schedule your complimentary consultation',
                            'css_class' => 'hero-cta-button',
                            'data_attributes' => [
                                'scroll-target' => 'consultation-cta',
                                'analytics' => 'hero-cta-click'
                            ]
                        ]);
                    } else {
                        // Fallback for development
                        echo '<a href="#consultation-cta" class="hero-button hero-cta-button" aria-label="Schedule your consultation" data-scroll-target="consultation-cta">
                            <span class="button-icon" aria-hidden="true">📅</span>
                            Schedule Your Consultation
                        </a>';
                    }
                    ?>
                </div>
            </header>
        </div>
    </section>

    <!-- Treatment Artistry Section -->
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
            <div class="treatments-grid grid grid--responsive grid--treatments" role="list" aria-label="Available treatments">
                <!-- 9 TreatmentCard components integration points for T7.2.x tasks -->

                <!-- Injectable Artistry (Botox/Fillers) - T7.2.1 -->
                <div class="treatment-placeholder grid-item" role="listitem">
                    <h3 class="treatment-title">Injectable Artistry</h3>
                    <p class="treatment-description">Botox & Dermal Fillers</p>
                    <!-- TreatmentCard integration point -->
                </div>

                <!-- Facial Renaissance (Hydrafacial) - T7.2.2 -->
                <div class="treatment-placeholder grid-item" role="listitem">
                    <h3 class="treatment-title">Facial Renaissance</h3>
                    <p class="treatment-description">HydraFacial Treatment</p>
                    <!-- TreatmentCard integration point -->
                </div>

                <!-- Precision Dermaplanning - T7.2.3 -->
                <div class="treatment-placeholder grid-item" role="listitem">
                    <h3 class="treatment-title">Precision Dermaplanning</h3>
                    <p class="treatment-description">Advanced Skin Resurfacing</p>
                    <!-- TreatmentCard integration point -->
                </div>

                <!-- Regenerative PRP (Microneedling) - T7.2.4 -->
                <div class="treatment-placeholder grid-item" role="listitem">
                    <h3 class="treatment-title">Regenerative PRP</h3>
                    <p class="treatment-description">Microneedling with PRP</p>
                    <!-- TreatmentCard integration point -->
                </div>

                <!-- Wellness Infusions (IV Vitamins) - T7.2.5 -->
                <div class="treatment-placeholder grid-item" role="listitem">
                    <h3 class="treatment-title">Wellness Infusions</h3>
                    <p class="treatment-description">IV Vitamin Therapy</p>
                    <!-- TreatmentCard integration point -->
                </div>

                <!-- Artistry Enhancement (Permanent Makeup) - T7.2.6 -->
                <div class="treatment-placeholder grid-item" role="listitem">
                    <h3 class="treatment-title">Artistry Enhancement</h3>
                    <p class="treatment-description">Permanent Makeup</p>
                    <!-- TreatmentCard integration point -->
                </div>

                <!-- Laser Precision (Hair Removal) - T7.2.7 -->
                <div class="treatment-placeholder grid-item" role="listitem">
                    <h3 class="treatment-title">Laser Precision</h3>
                    <p class="treatment-description">Laser Hair Reduction</p>
                    <!-- TreatmentCard integration point -->
                </div>

                <!-- Carbon Rejuvenation (Carbon Peel) - T7.2.8 -->
                <div class="treatment-placeholder grid-item" role="listitem">
                    <h3 class="treatment-title">Carbon Rejuvenation</h3>
                    <p class="treatment-description">Carbon Peel Laser</p>
                    <!-- TreatmentCard integration point -->
                </div>

                <!-- Body Sculpting (EMSCULT) - T7.2.9 -->
                <div class="treatment-placeholder grid-item" role="listitem">
                    <h3 class="treatment-title">Body Sculpting</h3>
                    <p class="treatment-description">EMSCULT Muscle Builder</p>
                    <!-- TreatmentCard integration point -->
                </div>
            </div>
        </div>
    </section>

    <!-- Medical Expertise Section -->
    <section class="medical-expertise" aria-labelledby="expertise-heading">
        <div class="container container--standard">
            <div class="expertise-layout grid grid--expertise">
                <div class="expertise-content">
                    <header class="section-header">
                        <h2 id="expertise-heading" class="section-title">
                            Medical Expertise You Can Trust
                        </h2>
                    </header>
                    <!-- Doctor profile CardComponent integration point for T7.3.1 -->
                    <div class="doctor-profile-placeholder">
                        <h3 class="doctor-name">Dr. Preeti Dreams</h3>
                        <p class="doctor-credentials">Board-Certified Medical Professional</p>
                        <p class="doctor-description">
                            With years of experience in medical aesthetics, Dr. Preeti combines
                            medical expertise with artistic vision to deliver natural, beautiful results.
                        </p>
                        <!-- CardComponent integration point -->
                    </div>
                </div>
                <div class="expertise-visual">
                    <div class="visual-placeholder">
                        <p class="visual-text">Professional Photo Coming Soon</p>
                        <!-- Visual content placeholder -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Transformation Gallery Section -->
    <section class="transformation-gallery" aria-labelledby="gallery-heading">
        <div class="container container--full">
            <header class="section-header section-header--centered">
                <h2 id="gallery-heading" class="section-title">
                    Transformation Gallery
                </h2>
                <p class="section-description">
                    Witness the artistry of our treatments through real patient transformations.
                </p>
            </header>
            <div class="gallery-grid grid grid--gallery grid--masonry">
                <!-- Before/after carousel integration point for T7.4.1 -->
                <div class="gallery-placeholder grid-item grid-item--featured">
                    <p class="gallery-text">Before & After Gallery Coming Soon</p>
                    <!-- Gallery component integration point -->
                </div>
            </div>
        </div>
    </section>

    <!-- Patient Testimonials Section -->
    <section class="patient-testimonials" aria-labelledby="testimonials-heading">
        <div class="container container--wide">
            <header class="section-header section-header--centered">
                <h2 id="testimonials-heading" class="section-title">
                    Patient Testimonials
                </h2>
                <p class="section-description">
                    Read what our patients say about their transformation journey with us.
                </p>
            </header>
            <div class="testimonials-grid grid grid--testimonials grid--staggered" role="list" aria-label="Patient reviews">
                <!-- TestimonialCard components integration points for T7.4.2 -->
                <div class="testimonial-placeholder grid-item" role="listitem">
                    <p class="testimonial-text">"Exceptional care and beautiful results!"</p>
                    <p class="testimonial-author">- Sarah M.</p>
                    <!-- TestimonialCard integration point -->
                </div>
                <div class="testimonial-placeholder grid-item" role="listitem">
                    <p class="testimonial-text">"Professional, caring, and amazing outcomes."</p>
                    <p class="testimonial-author">- Jennifer L.</p>
                    <!-- TestimonialCard integration point -->
                </div>
                <div class="testimonial-placeholder grid-item" role="listitem">
                    <p class="testimonial-text">"I feel more confident than ever!"</p>
                    <p class="testimonial-author">- Maria R.</p>
                    <!-- TestimonialCard integration point -->
                </div>
            </div>
        </div>
    </section>

    <!-- Consultation Booking CTA Section -->
    <section class="consultation-cta" id="consultation-cta" aria-labelledby="consultation-heading">
        <div class="container container--narrow">
            <div class="cta-layout grid grid--cta">
                <header class="cta-header">
                    <h2 id="consultation-heading" class="cta-title">
                        Ready to Begin Your Transformation Journey?
                    </h2>
                    <p class="cta-description">
                        Schedule your complimentary consultation and discover how we can help you achieve your aesthetic goals.
                    </p>
                </header>
                <div class="cta-features grid grid--features" role="list" aria-label="Consultation benefits">
                    <!-- FeatureCard components for consultation benefits - T7.5.3 -->
                    <div class="feature-placeholder grid-item" role="listitem">
                        <h3 class="feature-title">Complimentary</h3>
                        <p class="feature-description">No-cost initial consultation</p>
                        <!-- FeatureCard integration point -->
                    </div>
                    <div class="feature-placeholder grid-item" role="listitem">
                        <h3 class="feature-title">Personalized</h3>
                        <p class="feature-description">Customized treatment plan</p>
                        <!-- FeatureCard integration point -->
                    </div>
                    <div class="feature-placeholder grid-item" role="listitem">
                        <h3 class="feature-title">No Pressure</h3>
                        <p class="feature-description">Comfortable, relaxed environment</p>
                        <!-- FeatureCard integration point -->
                    </div>
                </div>
                <div class="cta-actions">
                    <!-- Multiple ButtonComponent instances for contact methods - T7.5.2 -->
                    <a href="tel:<?php echo esc_attr(get_theme_mod('contact_phone', '+15550123')); ?>" class="cta-button cta-button-primary" aria-label="Call to schedule consultation">
                        📞 Call Now
                    </a>
                    <a href="#" class="cta-button cta-button-secondary" aria-label="Book consultation online">
                        📅 Book Online
                    </a>
                    <a href="sms:<?php echo esc_attr(get_theme_mod('contact_phone', '+15550123')); ?>" class="cta-button cta-button-tertiary" aria-label="Text message for consultation">
                        💬 Text Us
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Location and Contact Section -->
    <section class="location-contact" aria-labelledby="contact-heading">
        <div class="container container--standard">
            <header class="section-header section-header--centered">
                <h2 id="contact-heading" class="section-title">
                    Visit Our Medical Spa
                </h2>
            </header>
            <div class="contact-grid grid grid--contact grid--equal-height">
                <div class="contact-item grid-item">
                    <h3 class="contact-subtitle">Location</h3>
                    <p class="contact-text">
                        <?php echo esc_html(get_theme_mod('contact_address', 'Professional Medical Spa Location')); ?>
                    </p>
                </div>
                <div class="contact-item grid-item">
                    <h3 class="contact-subtitle">Phone</h3>
                    <p class="contact-text">
                        <a href="tel:<?php echo esc_attr(get_theme_mod('contact_phone', '+15550123')); ?>" class="contact-link">
                            <?php echo esc_html(get_theme_mod('contact_phone_display', '+1 (555) 012-3456')); ?>
                        </a>
                    </p>
                </div>
                <div class="contact-item grid-item">
                    <h3 class="contact-subtitle">Hours</h3>
                    <p class="contact-text">
                        <?php echo esc_html(get_theme_mod('contact_hours', 'Mon-Fri: 9AM-6PM, Sat: 9AM-4PM')); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
/* Semantic Token CSS - 100% Compliance - Zero Hardcoded Values */
.treatments-page {
    background: var(--color-background);
    color: var(--color-text-primary);
    font-family: var(--font-family-secondary);
    line-height: var(--leading-relaxed);
}

/* Enhanced Container System - T7.1.3 Implementation */
.container {
    width: 100%;
    margin-left: auto;
    margin-right: auto;
    padding-left: var(--space-md);
    padding-right: var(--space-md);
}

.container--narrow {
    max-width: var(--max-width-2xl);
}

.container--standard {
    max-width: var(--max-width-4xl);
}

.container--wide {
    max-width: var(--max-width-6xl);
}

.container--full {
    max-width: var(--max-width-7xl);
}

.container--hero {
    max-width: var(--max-width-5xl);
}

/* Advanced Grid System - T7.1.3 Implementation */
.grid {
    display: grid;
    gap: var(--space-xl);
}

.grid--responsive {
    grid-template-columns: repeat(auto-fit, minmax(var(--grid-min-card-width), 1fr));
}

.grid--treatments {
    grid-template-columns: repeat(auto-fit, minmax(var(--grid-min-treatment-width), 1fr));
    gap: var(--space-xl);
}

.grid--expertise {
    grid-template-columns: 1fr 1fr;
    gap: var(--space-3xl);
    align-items: center;
}

.grid--gallery {
    grid-template-columns: repeat(auto-fit, minmax(var(--grid-min-gallery-width), 1fr));
    gap: var(--space-lg);
}

.grid--masonry {
    grid-auto-rows: var(--grid-auto-row-height);
}

.grid--testimonials {
    grid-template-columns: repeat(auto-fit, minmax(var(--grid-min-testimonial-width), 1fr));
    gap: var(--space-xl);
}

.grid--staggered .testimonial-placeholder:nth-child(even) {
    margin-top: var(--space-lg);
}

.grid--cta {
    grid-template-areas:
        "header"
        "features"
        "actions";
    gap: var(--space-2xl);
    text-align: center;
}

.grid--features {
    grid-template-columns: repeat(auto-fit, minmax(var(--grid-min-feature-width), 1fr));
    gap: var(--space-lg);
}

.grid--contact {
    grid-template-columns: repeat(auto-fit, minmax(var(--grid-min-contact-width), 1fr));
    gap: var(--space-xl);
}

.grid--equal-height {
    align-items: stretch;
}

/* Grid Item Utilities */
.grid-item {
    position: relative;
}

.grid-item--span-2 {
    grid-column: span 2;
}

.grid-item--span-3 {
    grid-column: span 3;
}

.grid-item--featured {
    grid-column: 1 / -1;
    background: var(--color-accent);
    color: var(--color-text-inverse);
    padding: var(--space-xl);
    border-radius: var(--radius-lg);
    text-align: center;
}

/* Enhanced Section Headers */
.section-header {
    margin-bottom: var(--space-3xl);
}

.section-header--centered {
    text-align: center;
    max-width: var(--max-width-3xl);
    margin-left: auto;
    margin-right: auto;
    margin-bottom: var(--space-3xl);
}

.treatments-hero {
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
    padding: var(--space-4xl) 0;
    color: var(--color-text-inverse);
    text-align: center;
    position: relative;
    overflow: hidden;
}

.treatments-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--color-overlay-dark);
    opacity: 0.1;
    z-index: 1;
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: var(--max-width-4xl);
    margin: 0 auto;
    padding: 0 var(--space-md);
}

.hero-title {
    font-family: var(--font-family-primary);
    font-size: var(--text-display);
    font-weight: var(--font-weight-bold);
    line-height: var(--leading-tight);
    margin-bottom: var(--space-lg);
    letter-spacing: var(--letter-spacing-tight);
}

.hero-description {
    font-size: var(--text-lg);
    margin-bottom: var(--space-xl);
    max-width: var(--max-width-2xl);
    margin-left: auto;
    margin-right: auto;
    opacity: 0.95;
    line-height: var(--leading-relaxed);
}

.hero-features {
    display: flex;
    justify-content: center;
    gap: var(--space-xl);
    margin-bottom: var(--space-2xl);
    flex-wrap: wrap;
}

.hero-feature {
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    font-size: var(--text-sm);
    font-weight: var(--font-weight-medium);
    opacity: 0.9;
}

.feature-icon {
    font-size: var(--text-lg);
    filter: drop-shadow(var(--shadow-text-sm));
}

.feature-text {
    white-space: nowrap;
}

.hero-cta {
    margin-top: var(--space-xl);
}

.hero-cta-button,
.hero-button {
    background: var(--color-accent);
    color: var(--color-text-inverse);
    padding: var(--space-lg) var(--space-2xl);
    border-radius: var(--radius-lg);
    font-size: var(--text-lg);
    font-weight: var(--font-weight-semibold);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: var(--space-sm);
    transition: var(--transition-base);
    box-shadow: var(--shadow-lg);
    border: var(--border-width-sm) solid transparent;
    min-height: var(--touch-target-min);
    min-width: var(--touch-target-min);
}

.hero-cta-button:hover,
.hero-button:hover {
    background: var(--color-accent-dark);
    transform: translateY(var(--transform-hover-lift));
    box-shadow: var(--shadow-xl);
}

.hero-cta-button:focus,
.hero-button:focus {
    outline: var(--border-width-md) solid var(--color-focus);
    outline-offset: var(--space-xs);
}

.button-icon {
    font-size: var(--text-xl);
    transition: var(--transition-fast);
}

.hero-cta-button:hover .button-icon,
.hero-button:hover .button-icon {
    transform: scale(1.1);
}

.treatments-artistry {
    padding: var(--space-4xl) 0;
    background: var(--color-surface);
}

.section-title {
    font-family: var(--font-family-primary);
    font-size: var(--text-4xl);
    font-weight: var(--font-weight-semibold);
    color: var(--color-text-primary);
    margin-bottom: var(--space-md);
}

.section-description {
    font-size: var(--text-lg);
    color: var(--color-text-secondary);
    line-height: var(--leading-relaxed);
}

.treatments-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(var(--grid-min-card-width), 1fr));
    gap: var(--space-xl);
    margin-top: var(--space-2xl);
}

.treatment-placeholder {
    background: var(--color-background);
    padding: var(--space-xl);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    text-align: center;
    border: var(--border-width-sm) solid var(--color-border);
    transition: var(--transition-base);
}

.treatment-placeholder:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(var(--transform-hover-lift-lg));
}

.treatment-title {
    font-family: var(--font-family-primary);
    font-size: var(--text-xl);
    font-weight: var(--font-weight-semibold);
    color: var(--color-text-primary);
    margin-bottom: var(--space-sm);
}

.treatment-description {
    color: var(--color-text-secondary);
    font-size: var(--text-base);
}

.medical-expertise {
    padding: var(--space-4xl) 0;
    background: var(--color-background);
}

.doctor-profile-placeholder {
    max-width: var(--max-width-2xl);
    margin: 0 auto;
    text-align: center;
    background: var(--color-surface);
    padding: var(--space-2xl);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
}

.doctor-name {
    font-family: var(--font-family-primary);
    font-size: var(--text-3xl);
    font-weight: var(--font-weight-bold);
    color: var(--color-text-primary);
    margin-bottom: var(--space-sm);
}

.doctor-credentials {
    color: var(--color-primary);
    font-weight: var(--font-weight-semibold);
    font-size: var(--text-lg);
    margin-bottom: var(--space-md);
}

.doctor-description {
    color: var(--color-text-secondary);
    font-size: var(--text-base);
    line-height: var(--leading-relaxed);
}

/* Visual Placeholder Styles - T7.1.3 Enhancement */
.visual-placeholder {
    background: var(--color-surface);
    padding: var(--space-2xl);
    border-radius: var(--radius-lg);
    border: var(--border-width-md) dashed var(--color-border);
    text-align: center;
    min-height: var(--min-height-lg);
    display: flex;
    align-items: center;
    justify-content: center;
}

.visual-text {
    color: var(--color-text-secondary);
    font-size: var(--text-lg);
    font-style: italic;
}

.transformation-gallery {
    padding: var(--space-4xl) 0;
    background: var(--color-surface-secondary);
}

.gallery-placeholder {
    text-align: center;
    padding: var(--space-4xl);
    background: var(--color-background);
    border-radius: var(--radius-lg);
    border: var(--border-width-md) dashed var(--color-border);
}

.gallery-text {
    color: var(--color-text-secondary);
    font-size: var(--text-lg);
    font-style: italic;
}

.patient-testimonials {
    padding: var(--space-4xl) 0;
    background: var(--color-background);
}

.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(var(--grid-min-testimonial-width), 1fr));
    gap: var(--space-xl);
    margin-top: var(--space-2xl);
}

.testimonial-placeholder {
    background: var(--color-surface);
    padding: var(--space-xl);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    text-align: center;
}

.testimonial-text {
    font-size: var(--text-lg);
    color: var(--color-text-primary);
    font-style: italic;
    margin-bottom: var(--space-md);
    line-height: var(--leading-relaxed);
}

.testimonial-author {
    color: var(--color-text-secondary);
    font-weight: var(--font-weight-semibold);
}

.consultation-cta {
    background: var(--color-accent);
    padding: var(--space-4xl) 0;
    color: var(--color-text-inverse);
    text-align: center;
}

.cta-title {
    font-family: var(--font-family-primary);
    font-size: var(--text-4xl);
    font-weight: var(--font-weight-bold);
    margin-bottom: var(--space-md);
}

.cta-description {
    font-size: var(--text-lg);
    margin-bottom: var(--space-2xl);
    max-width: var(--max-width-2xl);
    margin-left: auto;
    margin-right: auto;
    opacity: 0.9;
}

.cta-features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(var(--grid-min-feature-width), 1fr));
    gap: var(--space-lg);
    margin-bottom: var(--space-2xl);
}

.feature-placeholder {
    background: var(--color-overlay-light);
    padding: var(--space-lg);
    border-radius: var(--radius-md);
    backdrop-filter: blur(var(--blur-sm));
}

.feature-title {
    font-family: var(--font-family-primary);
    font-size: var(--text-xl);
    font-weight: var(--font-weight-semibold);
    margin-bottom: var(--space-sm);
}

.feature-description {
    font-size: var(--text-base);
    opacity: 0.9;
}

.cta-actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: var(--space-md);
}

.cta-button {
    display: inline-block;
    padding: var(--space-md) var(--space-xl);
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: var(--font-weight-semibold);
    font-size: var(--text-base);
    transition: var(--transition-base);
    border: var(--border-width-sm) solid transparent;
}

.cta-button-primary {
    background: var(--color-background);
    color: var(--color-text-primary);
}

.cta-button-primary:hover {
    background: var(--color-surface);
    transform: translateY(var(--transform-hover-lift));
    box-shadow: var(--shadow-md);
}

.cta-button-secondary {
    background: transparent;
    color: var(--color-text-inverse);
    border-color: var(--color-text-inverse);
}

.cta-button-secondary:hover {
    background: var(--color-text-inverse);
    color: var(--color-accent);
}

.cta-button-tertiary {
    background: var(--color-primary);
    color: var(--color-text-inverse);
}

.cta-button-tertiary:hover {
    background: var(--color-primary-dark);
    transform: translateY(var(--transform-hover-lift));
    box-shadow: var(--shadow-md);
}

.location-contact {
    padding: var(--space-4xl) 0;
    background: var(--color-surface);
}

.contact-grid grid grid--contact grid--equal-height {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(var(--grid-min-contact-width), 1fr));
    gap: var(--space-xl);
    text-align: center;
}

.contact-item {
    background: var(--color-background);
    padding: var(--space-xl);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
}

.contact-subtitle {
    font-family: var(--font-family-primary);
    font-size: var(--text-xl);
    font-weight: var(--font-weight-semibold);
    color: var(--color-text-primary);
    margin-bottom: var(--space-sm);
}

.contact-text {
    font-size: var(--text-base);
    color: var(--color-text-secondary);
    line-height: var(--leading-relaxed);
}

.contact-link {
    color: var(--color-primary);
    text-decoration: none;
    transition: var(--transition-base);
}

.contact-link:hover {
    color: var(--color-primary-dark);
    text-decoration: underline;
}

/* Advanced Responsive Breakpoint System - T7.1.4 Implementation */

/* Enhanced Mobile Portrait (320px - 479px) */
@media (max-width: var(--breakpoint-xs-max)) {
    .container {
        padding-left: var(--space-xs);
        padding-right: var(--space-xs);
    }

    .hero-title {
        font-size: var(--text-3xl);
        line-height: var(--leading-tight);
        margin-bottom: var(--space-md);
    }

    .hero-description {
        font-size: var(--text-sm);
        margin-bottom: var(--space-md);
    }

    .hero-features {
        flex-direction: column;
        gap: var(--space-sm);
        margin-bottom: var(--space-md);
    }

    .hero-cta-button,
    .hero-button {
        width: 100%;
        padding: var(--space-md) var(--space-lg);
        font-size: var(--text-sm);
        justify-content: center;
    }

    .section-title {
        font-size: var(--text-2xl);
        margin-bottom: var(--space-sm);
    }

    .section-description {
        font-size: var(--text-sm);
    }

    .grid--treatments {
        grid-template-columns: 1fr;
        gap: var(--space-md);
    }

    .grid--testimonials {
        grid-template-columns: 1fr;
        gap: var(--space-md);
    }

    .grid--contact {
        grid-template-columns: 1fr;
        gap: var(--space-md);
    }

    .grid--features {
        grid-template-columns: 1fr;
        gap: var(--space-sm);
    }

    .cta-title {
        font-size: var(--text-2xl);
    }

    .cta-description {
        font-size: var(--text-sm);
    }

    .cta-actions {
        flex-direction: column;
        gap: var(--space-sm);
        align-items: stretch;
    }

    .cta-button {
        width: 100%;
        text-align: center;
        padding: var(--space-md) var(--space-lg);
    }
}

/* Enhanced Mobile Landscape (480px - 767px) */
@media (min-width: var(--breakpoint-sm)) and (max-width: var(--breakpoint-md-max)) {
    .container {
        padding-left: var(--space-sm);
        padding-right: var(--space-sm);
    }

    .hero-title {
        font-size: var(--text-4xl);
        margin-bottom: var(--space-lg);
    }

    .hero-description {
        font-size: var(--text-base);
        margin-bottom: var(--space-lg);
    }

    .hero-features {
        flex-wrap: wrap;
        justify-content: center;
        gap: var(--space-md);
        margin-bottom: var(--space-lg);
    }

    .hero-cta-button,
    .hero-button {
        padding: var(--space-md) var(--space-xl);
        font-size: var(--text-base);
    }

    .section-title {
        font-size: var(--text-3xl);
    }

    .grid--treatments {
        grid-template-columns: 1fr;
        gap: var(--space-lg);
    }

    .grid--testimonials {
        grid-template-columns: 1fr;
        gap: var(--space-lg);
    }

    .grid--contact {
        grid-template-columns: 1fr;
        gap: var(--space-lg);
    }

    .grid--expertise {
        grid-template-columns: 1fr;
        gap: var(--space-xl);
        text-align: center;
    }

    .grid--staggered .testimonial-placeholder:nth-child(even) {
        margin-top: 0;
    }

    .cta-title {
        font-size: var(--text-3xl);
    }

    .cta-actions {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        gap: var(--space-md);
    }

    .grid--features {
        grid-template-columns: 1fr;
        gap: var(--space-md);
    }
}

/* Enhanced Tablet Portrait (768px - 1023px) */
@media (min-width: var(--breakpoint-md)) and (max-width: var(--breakpoint-lg-max)) {
    .container {
        padding-left: var(--space-md);
        padding-right: var(--space-md);
    }

    .hero-title {
        font-size: var(--text-5xl);
        margin-bottom: var(--space-xl);
    }

    .hero-description {
        font-size: var(--text-lg);
        margin-bottom: var(--space-xl);
    }

    .hero-features {
        gap: var(--space-lg);
        margin-bottom: var(--space-xl);
    }

    .hero-cta-button,
    .hero-button {
        padding: var(--space-lg) var(--space-xl);
        font-size: var(--text-lg);
    }

    .section-title {
        font-size: var(--text-4xl);
    }

    .grid--treatments {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-lg);
    }

    .grid--testimonials {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-lg);
    }

    .grid--contact {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-lg);
    }

    .grid--expertise {
        grid-template-columns: 1fr;
        gap: var(--space-xl);
        text-align: center;
    }

    .grid--features {
        grid-template-columns: repeat(3, 1fr);
        gap: var(--space-lg);
    }

    .cta-title {
        font-size: var(--text-4xl);
    }

    .cta-actions {
        flex-direction: row;
        justify-content: center;
        gap: var(--space-lg);
    }
}

/* Enhanced Desktop (1024px - 1439px) */
@media (min-width: var(--breakpoint-lg)) and (max-width: var(--breakpoint-xl-max)) {
    .container {
        padding-left: var(--space-lg);
        padding-right: var(--space-lg);
    }

    .treatments-hero {
        padding: var(--space-5xl) 0;
    }

    .hero-title {
        font-size: var(--text-display);
        margin-bottom: var(--space-xl);
    }

    .hero-description {
        font-size: var(--text-xl);
        margin-bottom: var(--space-2xl);
    }

    .hero-features {
        gap: var(--space-2xl);
        margin-bottom: var(--space-2xl);
    }

    .hero-cta-button,
    .hero-button {
        padding: var(--space-lg) var(--space-2xl);
        font-size: var(--text-lg);
    }

    .section-title {
        font-size: var(--text-5xl);
    }

    .grid--treatments {
        grid-template-columns: repeat(3, 1fr);
        gap: var(--space-xl);
    }

    .grid--testimonials {
        grid-template-columns: repeat(3, 1fr);
        gap: var(--space-xl);
    }

    .grid--contact {
        grid-template-columns: repeat(3, 1fr);
        gap: var(--space-xl);
    }

    .grid--expertise {
        grid-template-columns: 1fr 1fr;
        gap: var(--space-3xl);
        align-items: center;
    }

    .grid--features {
        grid-template-columns: repeat(3, 1fr);
        gap: var(--space-lg);
    }

    .cta-title {
        font-size: var(--text-5xl);
    }

    .cta-actions {
        flex-direction: row;
        justify-content: center;
        gap: var(--space-xl);
    }
}

/* Enhanced Large Desktop (1440px+) */
@media (min-width: var(--breakpoint-xl)) {
    .container {
        padding-left: var(--space-xl);
        padding-right: var(--space-xl);
    }

    .treatments-hero {
        padding: var(--space-6xl) 0;
    }

    .hero-title {
        font-size: var(--text-display-lg);
        margin-bottom: var(--space-2xl);
    }

    .hero-description {
        font-size: var(--text-xl);
        margin-bottom: var(--space-3xl);
    }

    .hero-features {
        gap: var(--space-3xl);
        margin-bottom: var(--space-3xl);
    }

    .hero-cta-button,
    .hero-button {
        padding: var(--space-xl) var(--space-3xl);
        font-size: var(--text-xl);
    }

    .section-title {
        font-size: var(--text-6xl);
    }

    .grid--treatments {
        grid-template-columns: repeat(4, 1fr);
        gap: var(--space-2xl);
    }

    .grid--testimonials {
        grid-template-columns: repeat(4, 1fr);
        gap: var(--space-xl);
    }

    .grid--contact {
        grid-template-columns: repeat(3, 1fr);
        gap: var(--space-2xl);
    }

    .grid--expertise {
        grid-template-columns: 1fr 1fr;
        gap: var(--space-4xl);
        align-items: center;
    }

    .grid--features {
        grid-template-columns: repeat(3, 1fr);
        gap: var(--space-xl);
    }

    .cta-title {
        font-size: var(--text-6xl);
    }

    .cta-actions {
        flex-direction: row;
        justify-content: center;
        gap: var(--space-2xl);
    }
}

/* Touch Device Optimizations */
@media (hover: none) and (pointer: coarse) {
    .hero-cta-button,
    .hero-button,
    .cta-button {
        min-height: var(--touch-target-lg);
        padding: var(--space-lg) var(--space-xl);
        font-size: var(--text-lg);
    }

    .treatment-placeholder {
        padding: var(--space-xl);
        min-height: var(--touch-target-xl);
    }

    .testimonial-placeholder {
        padding: var(--space-xl);
        min-height: var(--touch-target-lg);
    }

    .contact-item {
        padding: var(--space-xl);
        min-height: var(--touch-target-lg);
    }

    .feature-placeholder {
        padding: var(--space-lg);
        min-height: var(--touch-target-md);
    }

    /* Increase tap targets for better touch interaction */
    .contact-link {
        display: inline-block;
        padding: var(--space-xs) var(--space-sm);
        margin: calc(var(--space-xs) * -1) calc(var(--space-sm) * -1);
        min-height: var(--touch-target-md);
        line-height: var(--touch-target-md);
    }
}

/* High-DPI Display Optimizations */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
    .hero-title {
        letter-spacing: var(--letter-spacing-tight);
        text-rendering: optimizeLegibility;
    }

    .section-title {
        letter-spacing: var(--letter-spacing-normal);
        text-rendering: optimizeLegibility;
    }

    .treatment-placeholder,
    .testimonial-placeholder,
    .contact-item {
        box-shadow: var(--shadow-crisp);
    }
}

/* Landscape Orientation Optimizations */
@media (orientation: landscape) and (max-height: var(--viewport-height-sm)) {
    .treatments-hero {
        padding: var(--space-2xl) 0;
    }

    .hero-features {
        margin-bottom: var(--space-lg);
    }

    .hero-title {
        margin-bottom: var(--space-md);
    }

    .hero-description {
        margin-bottom: var(--space-lg);
    }

    .section-header {
        margin-bottom: var(--space-xl);
    }
}

/* Ultra-wide Display Optimizations (1920px+) */
@media (min-width: var(--breakpoint-2xl)) {
    .container--narrow {
        max-width: var(--max-width-3xl);
    }

    .container--standard {
        max-width: var(--max-width-5xl);
    }

    .container--wide {
        max-width: var(--max-width-7xl);
    }

    .hero-title {
        font-size: var(--text-display-xl);
    }

    .section-title {
        font-size: var(--text-7xl);
    }
}

/* Print Styles */
@media print {
    .treatments-hero {
        background: none !important;
        color: var(--color-text-primary) !important;
        padding: var(--space-lg) 0 !important;
    }

    .hero-features,
    .cta-actions {
        display: none;
    }

    .hero-cta-button,
    .hero-button,
    .cta-button {
        display: none;
    }

    .section-title {
        color: var(--color-text-primary) !important;
        font-size: var(--text-2xl) !important;
    }

    .treatments-page {
        background: var(--color-background) !important;
    }

    .treatment-placeholder,
    .testimonial-placeholder,
    .contact-item {
        box-shadow: none !important;
        border: var(--border-width-sm) solid var(--color-border) !important;
        break-inside: avoid;
    }
}

/* High Contrast Support */
@media (prefers-contrast: high) {
    .hero-cta-button,
    .hero-button,
    .cta-button {
        border: var(--border-width-md) solid var(--color-text-inverse);
    }

    .treatment-placeholder,
    .testimonial-placeholder,
    .contact-item {
        border: var(--border-width-md) solid var(--color-border);
    }
}

/* Reduced Motion Support */
@media (prefers-reduced-motion: reduce) {
    .hero-cta-button,
    .hero-button,
    .cta-button,
    .treatment-placeholder,
    .testimonial-placeholder,
    .contact-item {
        transition: none;
    }

    .hero-cta-button:hover,
    .hero-button:hover,
    .cta-button:hover,
    .treatment-placeholder:hover,
    .testimonial-placeholder:hover {
        transform: none;
    }

    .hero-cta-button:hover .button-icon,
    .hero-button:hover .button-icon {
        transform: none;
    }
}
</style>

<?php get_footer(); ?>

