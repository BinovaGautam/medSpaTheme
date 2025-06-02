<?php
/**
 * Template Name: Treatments - Luxury Medical Spa Experience
 * Description: Sophisticated aesthetic medicine artistry showcase
 *
 * Following TREATMENTS_PAGE_DESIGN.md v4.0 specifications:
 * - Luxury medical spa positioning with consultation-focused journey
 * - Complete elimination of ecommerce patterns
 * - Artistic category presentation (Injectable, Facial, Laser, Body, Wellness)
 * - WCAG AAA accessibility compliance
 * - Responsive luxury design (320px-767px mobile, 1024px+ desktop)
 */

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

                        <!-- Sophisticated Discovery Invitation -->
                        <div class="hero-discovery-invitation">
                            <a href="#treatment-artistry" class="hero-discovery-btn"
                               aria-label="Discover our treatment artistry">
                                <span class="discovery-text">Discover Your Journey</span>
                                <span class="discovery-icon" aria-hidden="true">✨</span>
                            </a>
                        </div>

                        <!-- Luxury Credibility Markers -->
                        <div class="hero-credibility-luxury">
                            <div class="credibility-item">
                                <span class="credibility-icon" aria-hidden="true">✨</span>
                                <span class="credibility-text">15+ Years Medical Excellence</span>
                            </div>
                            <div class="credibility-item">
                                <span class="credibility-icon" aria-hidden="true">🏥</span>
                                <span class="credibility-text">Board-Certified Artistry</span>
                            </div>
                            <div class="credibility-item">
                                <span class="credibility-icon" aria-hidden="true">🎯</span>
                                <span class="credibility-text">Personalized Consultations</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Artistic Category Discovery Section -->
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

            <!-- Artistic Category Grid -->
            <div class="artistry-categories-grid">

                <!-- Injectable Artistry -->
                <article class="artistry-category injectable-artistry"
                         aria-labelledby="injectable-title">
                    <div class="category-visual-container">
                        <div class="category-image injectable-image"
                             role="img"
                             aria-label="Sophisticated injectable treatment environment"></div>
                        <div class="category-visual-overlay">
                            <span class="category-artistic-icon" aria-hidden="true">💎</span>
                        </div>
                    </div>

                    <div class="category-content-luxury">
                        <header class="category-header">
                            <h3 id="injectable-title" class="category-title">Injectable Artistry</h3>
                            <p class="category-essence">
                                The subtle enhancement of natural beauty through precise
                                neuromodulator and filler artistry by board-certified
                                medical professionals
                            </p>
                        </header>

                        <div class="category-description-luxury">
                            <p>
                                Where medical precision meets artistic vision in the sophisticated
                                enhancement of facial harmony. Our board-certified approach ensures
                                natural-looking results that honor your unique beauty.
                            </p>
                        </div>

                        <footer class="category-action-luxury">
                            <a href="#consultation-invitation"
                               class="category-explore-btn"
                               aria-label="Explore injectable artistry consultation">
                                <span class="explore-text">Explore This Art Form</span>
                                <span class="explore-icon" aria-hidden="true">→</span>
                            </a>
                        </footer>
                    </div>
                </article>

                <!-- Facial Renaissance -->
                <article class="artistry-category facial-renaissance"
                         aria-labelledby="facial-title">
                    <div class="category-visual-container">
                        <div class="category-image facial-image"
                             role="img"
                             aria-label="Advanced facial treatment artistry"></div>
                        <div class="category-visual-overlay">
                            <span class="category-artistic-icon" aria-hidden="true">✨</span>
                        </div>
                    </div>

                    <div class="category-content-luxury">
                        <header class="category-header">
                            <h3 id="facial-title" class="category-title">Facial Renaissance</h3>
                            <p class="category-essence">
                                Advanced skincare treatments that rejuvenate and restore
                                your skin's natural radiance through medical-grade
                                technology and expertise
                            </p>
                        </header>

                        <div class="category-description-luxury">
                            <p>
                                Transformative facial treatments combining cutting-edge technology
                                with artistic technique to reveal your skin's natural luminosity
                                and youthful vitality.
                            </p>
                        </div>

                        <footer class="category-action-luxury">
                            <a href="#consultation-invitation"
                               class="category-explore-btn"
                               aria-label="Begin facial renaissance consultation">
                                <span class="explore-text">Begin Your Renaissance</span>
                                <span class="explore-icon" aria-hidden="true">→</span>
                            </a>
                        </footer>
                    </div>
                </article>

                <!-- Laser Precision -->
                <article class="artistry-category laser-precision"
                         aria-labelledby="laser-title">
                    <div class="category-visual-container">
                        <div class="category-image laser-image"
                             role="img"
                             aria-label="Advanced laser treatment technology"></div>
                        <div class="category-visual-overlay">
                            <span class="category-artistic-icon" aria-hidden="true">⚡</span>
                        </div>
                    </div>

                    <div class="category-content-luxury">
                        <header class="category-header">
                            <h3 id="laser-title" class="category-title">Laser Precision</h3>
                            <p class="category-essence">
                                Technology-driven treatments for lasting results with
                                medical precision and safety
                            </p>
                        </header>

                        <div class="category-description-luxury">
                            <p>
                                State-of-the-art laser technologies delivering transformative
                                results for skin rejuvenation, hair removal, and pigmentation
                                correction with uncompromising safety standards.
                            </p>
                        </div>

                        <footer class="category-action-luxury">
                            <a href="#consultation-invitation"
                               class="category-explore-btn"
                               aria-label="Discover laser precision consultation">
                                <span class="explore-text">Discover Precision</span>
                                <span class="explore-icon" aria-hidden="true">→</span>
                            </a>
                        </footer>
                    </div>
                </article>

                <!-- Body Artistry -->
                <article class="artistry-category body-artistry"
                         aria-labelledby="body-title">
                    <div class="category-visual-container">
                        <div class="category-image body-image"
                             role="img"
                             aria-label="Advanced body contouring treatments"></div>
                        <div class="category-visual-overlay">
                            <span class="category-artistic-icon" aria-hidden="true">🌿</span>
                        </div>
                    </div>

                    <div class="category-content-luxury">
                        <header class="category-header">
                            <h3 id="body-title" class="category-title">Body Artistry</h3>
                            <p class="category-essence">
                                Advanced body contouring and enhancement treatments
                                for sculpted results and confidence
                            </p>
                        </header>

                        <div class="category-description-luxury">
                            <p>
                                Non-invasive body enhancement treatments that sculpt and refine
                                your natural contours with cutting-edge technology and
                                medical expertise.
                            </p>
                        </div>

                        <footer class="category-action-luxury">
                            <a href="#consultation-invitation"
                               class="category-explore-btn"
                               aria-label="Shape your vision consultation">
                                <span class="explore-text">Shape Your Vision</span>
                                <span class="explore-icon" aria-hidden="true">→</span>
                            </a>
                        </footer>
                    </div>
                </article>

                <!-- Wellness Sanctuary -->
                <article class="artistry-category wellness-sanctuary"
                         aria-labelledby="wellness-title">
                    <div class="category-visual-container">
                        <div class="category-image wellness-image"
                             role="img"
                             aria-label="Holistic wellness treatments"></div>
                        <div class="category-visual-overlay">
                            <span class="category-artistic-icon" aria-hidden="true">🧘</span>
                        </div>
                    </div>

                    <div class="category-content-luxury">
                        <header class="category-header">
                            <h3 id="wellness-title" class="category-title">Wellness Sanctuary</h3>
                            <p class="category-essence">
                                Holistic wellness treatments that complement
                                aesthetic enhancements
                            </p>
                        </header>

                        <div class="category-description-luxury">
                            <p>
                                Comprehensive wellness treatments that enhance your overall
                                vitality and complement your aesthetic journey through
                                holistic medical approaches.
                            </p>
                        </div>

                        <footer class="category-action-luxury">
                            <a href="#consultation-invitation"
                               class="category-explore-btn"
                               aria-label="Enter wellness sanctuary consultation">
                                <span class="explore-text">Enter Sanctuary</span>
                                <span class="explore-icon" aria-hidden="true">→</span>
                            </a>
                        </footer>
                    </div>
                </article>

            </div>
        </div>
    </section>

    <!-- Medical Artistry Philosophy Section -->
    <section class="medical-artistry-philosophy" aria-labelledby="philosophy-title">
        <div class="container">
            <div class="philosophy-content-luxury">

                <!-- Provider Portrait Section -->
                <div class="provider-portrait-section">
                    <div class="provider-portrait-container">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/dr-preeti-portrait.jpg"
                             alt="Dr. Preeti Sharma, Board-certified physician in professional medical setting"
                             class="provider-portrait-image"
                             loading="lazy">
                        <div class="portrait-artistic-frame"></div>
                    </div>
                </div>

                <!-- Philosophy Narrative Section -->
                <div class="philosophy-narrative-section">
                    <header class="philosophy-header">
                        <h2 id="philosophy-title" class="philosophy-title">Our Philosophy</h2>
                    </header>

                    <blockquote class="philosophy-quote">
                        <p class="quote-text">
                            "Aesthetic medicine is the intersection of medical science and artistic vision.
                            Every treatment is personalized to enhance your unique beauty while
                            maintaining natural harmony."
                        </p>
                        <footer class="quote-attribution">
                            <cite class="quote-author">Dr. Preeti Sharma, MD</cite>
                            <p class="quote-credentials">
                                Board-Certified in Aesthetic Medicine<br>
                                15+ Years of Artistic Excellence
                            </p>
                        </footer>
                    </blockquote>
                </div>

            </div>
        </div>
    </section>

    <!-- Personalized Consultation Invitation -->
    <section id="consultation-invitation" class="consultation-invitation-luxury"
             aria-labelledby="consultation-title">
        <div class="container">
            <div class="consultation-content-luxury">

                <!-- Consultation Header -->
                <header class="consultation-header">
                    <h2 id="consultation-title" class="consultation-title">
                        Begin Your Aesthetic Journey
                    </h2>
                    <p class="consultation-subtitle">
                        Every transformation begins with understanding your unique beauty
                        and aesthetic goals
                    </p>
                </header>

                <!-- Consultation Benefits -->
                <div class="consultation-benefits-luxury">
                    <div class="consultation-card">
                        <h3 class="consultation-card-title">Your Consultation Includes</h3>

                        <ul class="consultation-benefits-list">
                            <li class="benefit-item">
                                <span class="benefit-icon" aria-hidden="true">✓</span>
                                <span class="benefit-text">
                                    Personalized aesthetic assessment by board-certified physician
                                </span>
                            </li>
                            <li class="benefit-item">
                                <span class="benefit-icon" aria-hidden="true">✓</span>
                                <span class="benefit-text">
                                    Discussion of your goals and treatment options
                                </span>
                            </li>
                            <li class="benefit-item">
                                <span class="benefit-icon" aria-hidden="true">✓</span>
                                <span class="benefit-text">
                                    Customized treatment plan designed for your unique needs
                                </span>
                            </li>
                            <li class="benefit-item">
                                <span class="benefit-icon" aria-hidden="true">✓</span>
                                <span class="benefit-text">
                                    Complete privacy and discretion throughout your journey
                                </span>
                            </li>
                        </ul>

                        <!-- Primary Consultation CTA -->
                        <div class="consultation-cta-primary">
                            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>"
                               class="consultation-schedule-btn"
                               aria-label="Schedule your consultation">
                                <span class="schedule-text">Schedule Your Consultation</span>
                                <span class="schedule-icon" aria-hidden="true">📅</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Alternative Contact Methods -->
                <div class="consultation-contact-methods">
                    <div class="contact-method">
                        <a href="tel:<?php echo esc_attr(get_theme_mod('spa_phone', '(555) 123-4567')); ?>"
                           class="contact-method-link phone-contact"
                           aria-label="Call us for consultation">
                            <span class="contact-icon" aria-hidden="true">📞</span>
                            <span class="contact-text"><?php echo esc_html(get_theme_mod('spa_phone', '(555) 123-4567')); ?></span>
                        </a>
                    </div>

                    <div class="contact-method">
                        <a href="mailto:<?php echo esc_attr(get_theme_mod('spa_email', 'consultations@preetidreams.com')); ?>"
                           class="contact-method-link email-contact"
                           aria-label="Email us for consultation">
                            <span class="contact-icon" aria-hidden="true">✉️</span>
                            <span class="contact-text"><?php echo esc_html(get_theme_mod('spa_email', 'consultations@preetidreams.com')); ?></span>
                        </a>
                    </div>

                    <div class="contact-method">
                        <span class="contact-method-info virtual-contact">
                            <span class="contact-icon" aria-hidden="true">💬</span>
                            <span class="contact-text">Virtual Options Available</span>
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </section>

</main>

<?php
/**
 * Enhanced Structured Data for Luxury Medical Spa (SEO)
 */
$spa_name = get_bloginfo('name');
$structured_data = array(
    '@context' => 'https://schema.org',
    '@type' => 'MedicalBusiness',
    'name' => $spa_name . ' - Luxury Medical Spa',
    'description' => 'Premier luxury medical spa offering sophisticated aesthetic medicine treatments including injectable artistry, facial renaissance, laser precision, body artistry, and wellness sanctuary services with board-certified physicians.',
    'url' => get_permalink(),
    'telephone' => get_theme_mod('spa_phone', '(555) 123-4567'),
    'email' => get_theme_mod('spa_email', 'consultations@preetidreams.com'),
    'address' => array(
        '@type' => 'PostalAddress',
        'streetAddress' => get_theme_mod('spa_address_street', '123 Luxury Medical Plaza'),
        'addressLocality' => get_theme_mod('spa_address_city', 'Beverly Hills'),
        'addressRegion' => get_theme_mod('spa_address_state', 'CA'),
        'postalCode' => get_theme_mod('spa_address_zip', '90210')
    ),
    'medicalSpecialty' => array(
        'Aesthetic Medicine',
        'Injectable Artistry',
        'Facial Renaissance Treatments',
        'Laser Precision Procedures',
        'Body Artistry Contouring',
        'Wellness Sanctuary Services'
    ),
    'hasCredential' => 'Board Certified Medical Professionals',
    'priceRange' => 'Premium Luxury Medical Spa Services',
    'paymentAccepted' => array('Cash', 'Credit Card', 'Financing Available'),
    'currenciesAccepted' => 'USD',
    'amenityFeature' => array(
        'Private Treatment Rooms',
        'Luxury Consultation Suites',
        'Complimentary Consultations',
        'Medical-Grade Technology',
        'Board-Certified Physicians',
        'Personalized Treatment Plans',
        'Discretion and Privacy Assured'
    ),
    'availableService' => array(
        array(
            '@type' => 'MedicalProcedure',
            'name' => 'Injectable Artistry',
            'description' => 'Sophisticated enhancement through precise neuromodulator and filler artistry'
        ),
        array(
            '@type' => 'MedicalProcedure',
            'name' => 'Facial Renaissance',
            'description' => 'Advanced skincare treatments for natural radiance restoration'
        ),
        array(
            '@type' => 'MedicalProcedure',
            'name' => 'Laser Precision',
            'description' => 'Technology-driven treatments with medical precision and safety'
        ),
        array(
            '@type' => 'MedicalProcedure',
            'name' => 'Body Artistry',
            'description' => 'Advanced body contouring and enhancement treatments'
        ),
        array(
            '@type' => 'MedicalProcedure',
            'name' => 'Wellness Sanctuary',
            'description' => 'Holistic wellness treatments complementing aesthetic enhancements'
        )
    )
);
?>

<script type="application/ld+json">
    <?php echo wp_json_encode($structured_data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
</script>

<?php get_footer(); ?>
