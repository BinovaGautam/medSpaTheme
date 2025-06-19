<?php get_header(); ?>

<main id="main" class="site-main homepage">

    <!-- Luxury Hero Section with Integrated Quiz -->
    <section class="hero-section with-quiz" id="hero">
        <!-- Hero Content Section -->
        <div class="hero-content">
            <div class="hero-content-inner">
                <!-- Brand Introduction -->
                <div class="hero-brand-intro">
                    <span class="hero-brand-tagline">
                        <?php esc_html_e('Where Medical Artistry Meets Luxury', 'medspa-theme'); ?>
                    </span>
                </div>

                <!-- Main Hero Content -->
                <h1 class="hero-title">
                    <?php echo get_theme_mod('hero_title', __('Transform Your Beauty with Precision & Artistry', 'medspa-theme')); ?>
                </h1>

                <p class="hero-subtitle">
                    <?php echo get_theme_mod('hero_subtitle', __('Discover your perfect aesthetic journey with our board-certified professionals, cutting-edge treatments, and personalized approach to medical beauty.', 'medspa-theme')); ?>
                </p>

                <!-- Trust Indicators -->
                <div class="hero-trust-indicators">
                    <div class="hero-trust-item">
                        <span class="hero-trust-icon" aria-hidden="true">🏆</span>
                        <span class="hero-trust-text"><?php esc_html_e('Board Certified', 'medspa-theme'); ?></span>
                    </div>
                    <div class="hero-trust-item">
                        <span class="hero-trust-icon" aria-hidden="true">⭐</span>
                        <span class="hero-trust-text"><?php esc_html_e('Award Winning', 'medspa-theme'); ?></span>
                    </div>
                    <div class="hero-trust-item">
                        <span class="hero-trust-icon" aria-hidden="true">👥</span>
                        <span class="hero-trust-text"><?php esc_html_e('2000+ Happy Patients', 'medspa-theme'); ?></span>
                    </div>
                    <div class="hero-trust-item">
                        <span class="hero-trust-icon" aria-hidden="true">🔒</span>
                        <span class="hero-trust-text"><?php esc_html_e('HIPAA Compliant', 'medspa-theme'); ?></span>
                    </div>
                </div>

                <!-- Hero Features -->
                <div class="hero-features">
                    <div class="hero-feature">
                        <span class="hero-feature-icon" aria-hidden="true">✨</span>
                        <span class="hero-feature-text"><?php esc_html_e('Personalized Treatment Plans', 'medspa-theme'); ?></span>
                    </div>
                    <div class="hero-feature">
                        <span class="hero-feature-icon" aria-hidden="true">🔬</span>
                        <span class="hero-feature-text"><?php esc_html_e('Latest Medical Technology', 'medspa-theme'); ?></span>
                    </div>
                    <div class="hero-feature">
                        <span class="hero-feature-icon" aria-hidden="true">💎</span>
                        <span class="hero-feature-text"><?php esc_html_e('Luxury Spa Experience', 'medspa-theme'); ?></span>
                    </div>
                </div>

                <!-- Primary CTA -->
                <div class="hero-primary-cta">
                    <a href="#consultation" class="hero-cta-button" aria-describedby="hero-cta-description">
                        <span class="hero-cta-icon" aria-hidden="true">📅</span>
                        <span class="hero-cta-text"><?php esc_html_e('Start Your Journey Today', 'medspa-theme'); ?></span>
                        <span class="hero-cta-arrow" aria-hidden="true">→</span>
                    </a>
                    <p id="hero-cta-description" class="hero-cta-description">
                        <?php esc_html_e('Free consultation • No obligation • Personalized recommendations', 'medspa-theme'); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Interactive Quiz Section -->
        <div class="hero-quiz-container">
            <div class="hero-quiz-wrapper">
                <?php
                // Include the elegant quiz component with hero-specific configuration
                get_template_part('template-parts/components/elegant-quiz', null, array(
                    'title' => __('Find Your Perfect Treatment', 'medspa-theme'),
                    'subtitle' => __('Answer a few questions to get personalized recommendations', 'medspa-theme'),
                    'css_class' => 'hero-integrated-quiz',
                    'show_progress' => true
                ));
                ?>
            </div>
        </div>
    </section>

    <!-- Services Overview Section - HOMEPAGE_VISUAL_DESIGN.md Alternating Layout Implementation -->
    <?php if (get_theme_mod('show_services_overview_section', true)) : ?>
    <section class="services-overview-grouped" aria-labelledby="services-overview-heading">
        <div class="services-container">
            <!-- Services Header -->
            <header class="services-header">
                <h2 id="services-overview-heading" class="services-main-title">
                    <?php echo get_theme_mod('services_overview_title', 'Our Treatment Artistry'); ?>
                </h2>
                <p class="services-main-subtitle">
                    <?php echo get_theme_mod('services_overview_subtitle', 'Discover Personalized Medical Aesthetics'); ?>
                </p>
                <p class="services-main-description">
                    <?php echo get_theme_mod('services_overview_description', 'Each treatment is carefully curated to enhance your natural beauty with precision, artistry, and innovation.'); ?>
                </p>
            </header>

            <!-- Injectable Artistry Section - TEXT LEFT | VISUAL RIGHT -->
            <div class="service-section layout-text-left" aria-labelledby="injectable-artistry-heading">
                <div class="service-text-content">
                    <div class="service-header">
                        <span class="service-icon" aria-hidden="true">💉</span>
                        <h3 id="injectable-artistry-heading" class="service-title">Injectable Artistry</h3>
                        <p class="service-subtitle">Precision Enhancement & Natural Beauty</p>
                    </div>
                    <p class="service-description">
                        Enhance your natural beauty with precision injectable treatments for wrinkle reduction and volume restoration. Our advanced injectable services include expert administration and natural results.
                    </p>

                    <div class="service-treatments">
                        <h4 class="treatments-heading">Our Injectable Services</h4>
                        <div class="treatment-buttons">
                            <a href="/treatments/botox-dysport" class="treatment-button">
                                <span class="treatment-name">Botox & Dysport</span>
                                <span class="treatment-description">Smooth wrinkles and fine lines</span>
                            </a>
                            <a href="/treatments/facial-fillers" class="treatment-button">
                                <span class="treatment-name">Facial Fillers</span>
                                <span class="treatment-description">Restore volume and contour</span>
                            </a>
                            <a href="/treatments/pdo-thread-lifts" class="treatment-button">
                                <span class="treatment-name">PDO Thread Lifts</span>
                                <span class="treatment-description">Lift and tighten skin naturally</span>
                            </a>
                            <a href="/treatments/sculptra" class="treatment-button">
                                <span class="treatment-name">Sculptra®</span>
                                <span class="treatment-description">Stimulate natural collagen production</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="service-visual-content">
                    <div class="before-after-gallery">
                        <h4 class="gallery-title">Injectable Results Gallery</h4>
                        <div class="gallery-grid">
                            <div class="before-after-pair">
                                <div class="before-image">
                                    <img src="https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=300&h=300&fit=crop&crop=face" alt="Before Botox treatment - natural expression lines" loading="lazy">
                                    <span class="image-label">Before</span>
                                </div>
                                <div class="after-image">
                                    <img src="https://images.unsplash.com/photo-1594824369039-a8c2e8d3c8c4?w=300&h=300&fit=crop&crop=face" alt="After Botox treatment - smooth, natural results" loading="lazy">
                                    <span class="image-label">After</span>
                                </div>
                            </div>
                            <div class="before-after-pair">
                                <div class="before-image">
                                    <img src="https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=300&h=300&fit=crop&crop=face" alt="Before dermal filler treatment - loss of volume" loading="lazy">
                                    <span class="image-label">Before</span>
                                </div>
                                <div class="after-image">
                                    <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=300&h=300&fit=crop&crop=face" alt="After dermal filler treatment - restored youthful contours" loading="lazy">
                                    <span class="image-label">After</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laser Services Section - VISUAL LEFT | TEXT RIGHT -->
            <div class="service-section layout-visual-left" aria-labelledby="laser-services-heading">
                <div class="service-visual-content">
                    <div class="treatment-video">
                        <h4 class="video-title">CO2 Laser Treatment</h4>
                        <div class="video-container">
                            <div class="video-poster">
                                <img src="https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=600&h=400&fit=crop" alt="Advanced laser treatment technology in modern medical spa" loading="lazy">
                                <button class="video-play-button" aria-label="Play CO2 laser treatment video">
                                    <span class="play-icon" aria-hidden="true">▶️</span>
                                </button>
                            </div>
                            <p class="video-description">Watch our advanced CO2 laser treatment process</p>
                        </div>
                    </div>
                </div>

                <div class="service-text-content">
                    <div class="service-header">
                        <span class="service-icon" aria-hidden="true">🔥</span>
                        <h3 id="laser-services-heading" class="service-title">Laser Services</h3>
                        <p class="service-subtitle">Advanced Technology & Expert Care</p>
                    </div>
                    <p class="service-description">
                        Reverse sun damage, remove sun spots, lines, and wrinkles, and tighten your skin with our advanced laser services. Award-winning laser hair removal voted 'Best in Scottsdale' three years running.
                    </p>

                    <div class="service-treatments">
                        <h4 class="treatments-heading">Our Laser Treatments</h4>
                        <div class="treatment-buttons">
                            <a href="/treatments/non-ablative-resurfacing" class="treatment-button">
                                <span class="treatment-name">Non-Ablative Resurfacing</span>
                                <span class="treatment-description">Gentle skin renewal</span>
                            </a>
                            <a href="/treatments/co2-resurfacing" class="treatment-button">
                                <span class="treatment-name">CO2 Resurfacing</span>
                                <span class="treatment-description">Advanced skin rejuvenation</span>
                            </a>
                            <a href="/treatments/ipl-photofacials" class="treatment-button">
                                <span class="treatment-name">IPL Photofacials</span>
                                <span class="treatment-description">Light-based skin improvement</span>
                            </a>
                            <a href="/treatments/laser-hair-removal" class="treatment-button">
                                <span class="treatment-name">Laser Hair Removal</span>
                                <span class="treatment-description">Permanent hair reduction</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Facial Renaissance Section - TEXT LEFT | VISUAL RIGHT -->
            <div class="service-section layout-text-left" aria-labelledby="facial-renaissance-heading">
                <div class="service-text-content">
                    <div class="service-header">
                        <span class="service-icon" aria-hidden="true">🌊</span>
                        <h3 id="facial-renaissance-heading" class="service-title">Facial Renaissance</h3>
                        <p class="service-subtitle">Rejuvenation & Renewal</p>
                    </div>
                    <p class="service-description">
                        Advanced skincare treatments for deep cleansing, hydration, and skin renewal with immediate results. Experience the difference of medical-grade facial treatments.
                    </p>

                    <div class="service-treatments">
                        <h4 class="treatments-heading">Our Facial Treatments</h4>
                        <div class="treatment-buttons">
                            <a href="/treatments/hydrafacial" class="treatment-button">
                                <span class="treatment-name">HydraFacial</span>
                                <span class="treatment-description">Deep cleansing and hydration</span>
                            </a>
                            <a href="/treatments/chemical-peels" class="treatment-button">
                                <span class="treatment-name">Chemical Peels</span>
                                <span class="treatment-description">Skin renewal and rejuvenation</span>
                            </a>
                            <a href="/treatments/microneedling" class="treatment-button">
                                <span class="treatment-name">Microneedling</span>
                                <span class="treatment-description">Collagen induction therapy</span>
                            </a>
                            <a href="/treatments/led-therapy" class="treatment-button">
                                <span class="treatment-name">LED Therapy</span>
                                <span class="treatment-description">Light-based skin healing</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="service-visual-content">
                    <div class="treatment-results-gallery">
                        <h4 class="gallery-title">Treatment Results Gallery</h4>
                        <div class="results-grid">
                            <div class="result-item">
                                <img src="https://images.unsplash.com/photo-1560750588-73207b1ef5b8?w=300&h=200&fit=crop" alt="HydraFacial treatment results - glowing, hydrated skin" loading="lazy">
                                <span class="result-label">HydraFacial Results</span>
                            </div>
                            <div class="result-item">
                                <img src="https://images.unsplash.com/photo-1515377905703-c4788e51af15?w=300&h=200&fit=crop" alt="Chemical peel results - smooth, renewed skin texture" loading="lazy">
                                <span class="result-label">Chemical Peel Results</span>
                            </div>
                            <div class="result-item">
                                <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=300&h=200&fit=crop" alt="Microneedling results - improved skin firmness and texture" loading="lazy">
                                <span class="result-label">Microneedling Results</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Body Sculpting Section - VISUAL LEFT | TEXT RIGHT -->
            <div class="service-section layout-visual-left" aria-labelledby="body-sculpting-heading">
                <div class="service-visual-content">
                    <div class="transformation-gallery">
                        <h4 class="gallery-title">Body Transformation Gallery</h4>
                        <div class="transformation-grid">
                            <div class="transformation-item">
                                <div class="transformation-before">
                                    <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=200&h=300&fit=crop" alt="Before CoolSculpting treatment" loading="lazy">
                                    <span class="transformation-label">Before</span>
                                </div>
                                <div class="transformation-after">
                                    <img src="https://images.unsplash.com/photo-1594824369039-a8c2e8d3c8c4?w=200&h=300&fit=crop" alt="After CoolSculpting treatment" loading="lazy">
                                    <span class="transformation-label">After</span>
                                </div>
                                <div class="transformation-overlay">
                                    <span class="treatment-name">CoolSculpting Results</span>
                                </div>
                            </div>
                            <div class="transformation-item">
                                <div class="transformation-before">
                                    <img src="https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=200&h=300&fit=crop" alt="Before body contouring treatment" loading="lazy">
                                    <span class="transformation-label">Before</span>
                                </div>
                                <div class="transformation-after">
                                    <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=200&h=300&fit=crop" alt="After body contouring treatment" loading="lazy">
                                    <span class="transformation-label">After</span>
                                </div>
                                <div class="transformation-overlay">
                                    <span class="treatment-name">Body Contouring Results</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="service-text-content">
                    <div class="service-header">
                        <span class="service-icon" aria-hidden="true">💪</span>
                        <h3 id="body-sculpting-heading" class="service-title">Body Sculpting</h3>
                        <p class="service-subtitle">Transformation & Confidence</p>
                    </div>
                    <p class="service-description">
                        Body contouring and muscle building technology for complete transformation and wellness enhancement. Achieve your body goals with advanced technology.
                    </p>

                    <div class="service-treatments">
                        <h4 class="treatments-heading">Our Body Treatments</h4>
                        <div class="treatment-buttons">
                            <a href="/treatments/coolsculpting" class="treatment-button">
                                <span class="treatment-name">CoolSculpting</span>
                                <span class="treatment-description">Non-invasive fat reduction</span>
                            </a>
                            <a href="/treatments/body-contouring" class="treatment-button">
                                <span class="treatment-name">Body Contouring</span>
                                <span class="treatment-description">Sculpt and define body shape</span>
                            </a>
                            <a href="/treatments/cellulite-treatment" class="treatment-button">
                                <span class="treatment-name">Cellulite Treatment</span>
                                <span class="treatment-description">Smooth skin texture improvement</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wellness Sanctuary Section - TEXT LEFT | VISUAL RIGHT -->
            <div class="service-section layout-text-left" aria-labelledby="wellness-sanctuary-heading">
                <div class="service-text-content">
                    <div class="service-header">
                        <span class="service-icon" aria-hidden="true">💊</span>
                        <h3 id="wellness-sanctuary-heading" class="service-title">Wellness Sanctuary</h3>
                        <p class="service-subtitle">Holistic Health & Vitality</p>
                    </div>
                    <p class="service-description">
                        Holistic wellness treatments for enhanced health, energy, and complete rejuvenation. Personalized wellness programs designed for your optimal health and vitality.
                    </p>

                    <div class="service-treatments">
                        <h4 class="treatments-heading">Our Wellness Services</h4>
                        <div class="treatment-buttons">
                            <a href="/treatments/iv-therapy" class="treatment-button">
                                <span class="treatment-name">IV Therapy</span>
                                <span class="treatment-description">Nutrient infusion therapy</span>
                            </a>
                            <a href="/treatments/hormone-optimization" class="treatment-button">
                                <span class="treatment-name">Hormone Optimization</span>
                                <span class="treatment-description">Balance and restore hormones</span>
                            </a>
                            <a href="/treatments/weight-management" class="treatment-button">
                                <span class="treatment-name">Weight Management</span>
                                <span class="treatment-description">Comprehensive wellness programs</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="service-visual-content">
                    <div class="wellness-experience-gallery">
                        <h4 class="gallery-title">Wellness Experience Gallery</h4>
                        <div class="experience-grid">
                            <div class="experience-item">
                                <img src="https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=300&h=200&fit=crop" alt="IV Therapy treatment room - comfortable and modern" loading="lazy">
                                <span class="experience-label">IV Therapy Treatment Room</span>
                            </div>
                            <div class="experience-item">
                                <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=300&h=200&fit=crop" alt="Hormone consultation room - private and professional" loading="lazy">
                                <span class="experience-label">Hormone Consultation Room</span>
                            </div>
                            <div class="experience-item">
                                <img src="https://images.unsplash.com/photo-1594824369039-a8c2e8d3c8c4?w=300&h=200&fit=crop" alt="Weight management lifestyle plan consultation" loading="lazy">
                                <span class="experience-label">Weight Management Consultation</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Trust Indicators Section - Sprint 11 Implementation -->
    <?php if (get_theme_mod('show_trust_indicators_section', true)) : ?>
        <?php
        // Render Trust Indicators Component via ComponentRegistry
        if (class_exists('ComponentRegistry') && ComponentRegistry::is_registered('trust-indicators')) {
            echo ComponentRegistry::render('trust-indicators', [
                'section_title' => get_theme_mod('trust_indicators_title', 'Why Choose PreetiDreams'),
                'section_subtitle' => get_theme_mod('trust_indicators_subtitle', 'Experience the difference of medical artistry combined with luxury care'),
                'active_indicators' => function_exists('medspa_get_active_trust_indicators') ? medspa_get_active_trust_indicators() : [
                    'board-certified', 'award-winning', 'happy-patients', 'hipaa-compliant'
                ],
                'trust_content' => [
                    'board_certified' => [
                        'title' => get_theme_mod('trust_board_certified_title', 'Board Certified'),
                        'description' => get_theme_mod('trust_board_certified_description', 'Expert medical professionals with advanced training in aesthetic medicine and patient safety.')
                    ],
                    'award_winning' => [
                        'title' => get_theme_mod('trust_award_winning_title', 'Award Winning'),
                        'description' => get_theme_mod('trust_award_winning_description', 'Recognized excellence in medical aesthetics with industry awards and patient satisfaction.')
                    ],
                    'happy_patients' => [
                        'title' => get_theme_mod('trust_happy_patients_title', '2000+ Happy Patients'),
                        'description' => get_theme_mod('trust_happy_patients_description', 'Trusted by thousands of patients for natural-looking results and exceptional care.')
                    ],
                    'hipaa_compliant' => [
                        'title' => get_theme_mod('trust_hipaa_compliant_title', 'HIPAA Compliant'),
                        'description' => get_theme_mod('trust_hipaa_compliant_description', 'Your privacy and medical information are protected with the highest security standards.')
                    ]
                ]
            ]);
        }
        ?>
    <?php endif; ?>

    <!-- Featured Treatments Section - HOMEPAGE_DESIGN.md v6.0 Implementation -->
    <?php if (get_theme_mod('show_featured_treatments_section', true)) : ?>
    <section class="featured-treatments" aria-labelledby="featured-treatments-heading">
        <div class="container">
            <header class="featured-header">
                <h2 id="featured-treatments-heading" class="featured-title">
                    <?php echo get_theme_mod('featured_treatments_title', 'Signature Treatments'); ?>
                </h2>
                <p class="featured-subtitle">
                    <?php echo get_theme_mod('featured_treatments_subtitle', 'Discover our most popular and transformative treatments'); ?>
                </p>
            </header>

            <div class="featured-grid">
                <!-- HydraFacial Treatment -->
                <article class="featured-treatment">
                    <div class="featured-treatment-content">
                        <span class="featured-treatment-badge">Most Popular</span>
                        <h3 class="featured-treatment-title">HydraFacial</h3>
                        <p class="featured-treatment-description">
                            The ultimate skin resurfacing treatment that combines cleansing, exfoliation, extraction, hydration, and antioxidant protection.
                        </p>
                        <ul class="featured-treatment-benefits">
                            <li class="featured-treatment-benefit">
                                <span class="featured-treatment-benefit-icon" aria-hidden="true">✓</span>
                                Immediate visible results
                            </li>
                            <li class="featured-treatment-benefit">
                                <span class="featured-treatment-benefit-icon" aria-hidden="true">✓</span>
                                No downtime required
                            </li>
                            <li class="featured-treatment-benefit">
                                <span class="featured-treatment-benefit-icon" aria-hidden="true">✓</span>
                                Suitable for all skin types
                            </li>
                        </ul>
                        <a href="/treatments/hydrafacial" class="featured-treatment-cta">
                            Learn More
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </article>

                <!-- Botox & Dysport Treatment -->
                <article class="featured-treatment">
                    <div class="featured-treatment-content">
                        <span class="featured-treatment-badge">Precision Artistry</span>
                        <h3 class="featured-treatment-title">Botox & Dysport</h3>
                        <p class="featured-treatment-description">
                            Expert injectable treatments to smooth wrinkles and prevent signs of aging with natural-looking results.
                        </p>
                        <ul class="featured-treatment-benefits">
                            <li class="featured-treatment-benefit">
                                <span class="featured-treatment-benefit-icon" aria-hidden="true">✓</span>
                                Quick 15-minute treatment
                            </li>
                            <li class="featured-treatment-benefit">
                                <span class="featured-treatment-benefit-icon" aria-hidden="true">✓</span>
                                Results last 3-4 months
                            </li>
                            <li class="featured-treatment-benefit">
                                <span class="featured-treatment-benefit-icon" aria-hidden="true">✓</span>
                                Board-certified administration
                            </li>
                        </ul>
                        <a href="/treatments/botox-dysport" class="featured-treatment-cta">
                            Learn More
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </article>

                <!-- Laser Hair Removal Treatment -->
                <article class="featured-treatment">
                    <div class="featured-treatment-content">
                        <span class="featured-treatment-badge">Award Winning</span>
                        <h3 class="featured-treatment-title">Laser Hair Removal</h3>
                        <p class="featured-treatment-description">
                            Voted "Best in Scottsdale" three years running. Permanent hair reduction with the latest laser technology.
                        </p>
                        <ul class="featured-treatment-benefits">
                            <li class="featured-treatment-benefit">
                                <span class="featured-treatment-benefit-icon" aria-hidden="true">✓</span>
                                Latest laser technology
                            </li>
                            <li class="featured-treatment-benefit">
                                <span class="featured-treatment-benefit-icon" aria-hidden="true">✓</span>
                                All skin types welcome
                            </li>
                            <li class="featured-treatment-benefit">
                                <span class="featured-treatment-benefit-icon" aria-hidden="true">✓</span>
                                Permanent results
                            </li>
                        </ul>
                        <a href="/treatments/laser-hair-removal" class="featured-treatment-cta">
                            Learn More
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Medical Excellence Section - HOMEPAGE_DESIGN.md v6.0 Implementation -->
    <?php if (get_theme_mod('show_medical_excellence_section', true)) : ?>
    <section class="medical-excellence" aria-labelledby="medical-excellence-heading">
        <div class="container">
            <div class="excellence-content">
                <header class="excellence-header">
                    <h2 id="medical-excellence-heading" class="excellence-title">
                        <?php echo get_theme_mod('medical_excellence_title', 'Medical Excellence & Safety'); ?>
                    </h2>
                    <p class="excellence-subtitle">
                        <?php echo get_theme_mod('medical_excellence_subtitle', 'Your safety and satisfaction are our highest priorities'); ?>
                    </p>
                </header>

                <div class="excellence-grid">
                    <article class="excellence-item">
                        <h3 class="excellence-item-title">Board-Certified Professionals</h3>
                        <p class="excellence-item-description">
                            Our medical team consists of board-certified physicians and licensed practitioners with extensive training in aesthetic medicine.
                        </p>
                    </article>

                    <article class="excellence-item">
                        <h3 class="excellence-item-title">State-of-the-Art Facility</h3>
                        <p class="excellence-item-description">
                            Our modern medical spa features the latest technology and equipment in a luxurious, sterile environment designed for your comfort.
                        </p>
                    </article>

                    <article class="excellence-item">
                        <h3 class="excellence-item-title">Rigorous Safety Protocols</h3>
                        <p class="excellence-item-description">
                            We maintain the highest safety standards with comprehensive protocols, sterile procedures, and continuous staff training.
                        </p>
                    </article>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Patient Testimonials Section - HOMEPAGE_DESIGN.md v6.0 Implementation -->
    <?php if (get_theme_mod('show_testimonials_section', true)) : ?>
    <section class="testimonials" aria-labelledby="testimonials-heading">
        <div class="container">
            <header class="testimonials-header">
                <h2 id="testimonials-heading" class="testimonials-title">
                    <?php echo get_theme_mod('testimonials_title', 'Patient Stories'); ?>
                </h2>
                <p class="testimonials-subtitle">
                    <?php echo get_theme_mod('testimonials_subtitle', 'Real results from real patients who trusted us with their aesthetic journey'); ?>
                </p>
            </header>

            <div class="testimonials-grid">
                <article class="testimonial">
                    <div class="testimonial-content">
                        <p class="testimonial-text">
                            The HydraFacial completely transformed my skin! The results were immediate and my skin has never looked better. The team was professional and made me feel so comfortable throughout the entire process.
                        </p>
                        <div class="testimonial-author">
                            <div class="testimonial-author-info">
                                <p class="testimonial-author-name">Sarah M.</p>
                                <p class="testimonial-author-treatment">HydraFacial Patient</p>
                            </div>
                            <div class="testimonial-rating" aria-label="5 out of 5 stars">
                                ⭐⭐⭐⭐⭐
                            </div>
                        </div>
                    </div>
                </article>

                <article class="testimonial">
                    <div class="testimonial-content">
                        <p class="testimonial-text">
                            I was nervous about Botox, but Dr. Preeti made me feel at ease. The results are so natural - people keep asking if I've been on vacation! I couldn't be happier with my decision.
                        </p>
                        <div class="testimonial-author">
                            <div class="testimonial-author-info">
                                <p class="testimonial-author-name">Jennifer L.</p>
                                <p class="testimonial-author-treatment">Botox Patient</p>
                            </div>
                            <div class="testimonial-rating" aria-label="5 out of 5 stars">
                                ⭐⭐⭐⭐⭐
                            </div>
                        </div>
                    </div>
                </article>

                <article class="testimonial">
                    <div class="testimonial-content">
                        <p class="testimonial-text">
                            After years of shaving, laser hair removal has been life-changing! The process was comfortable and the results exceeded my expectations. I wish I had done this sooner.
                        </p>
                        <div class="testimonial-author">
                            <div class="testimonial-author-info">
                                <p class="testimonial-author-name">Michael R.</p>
                                <p class="testimonial-author-treatment">Laser Hair Removal Patient</p>
                            </div>
                            <div class="testimonial-rating" aria-label="5 out of 5 stars">
                                ⭐⭐⭐⭐⭐
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Consultation Invitation Section - HOMEPAGE_DESIGN.md v6.0 Implementation -->
    <?php if (get_theme_mod('show_consultation_invitation_section', true)) : ?>
    <section class="consultation-invitation" aria-labelledby="consultation-heading">
        <div class="container">
            <div class="consultation-content">
                <h2 id="consultation-heading" class="consultation-title">
                    <?php echo get_theme_mod('consultation_title', 'Begin Your Transformation Journey'); ?>
                </h2>
                <p class="consultation-description">
                    <?php echo get_theme_mod('consultation_description', 'Schedule your complimentary consultation and discover how we can help you achieve your aesthetic goals with personalized treatment plans.'); ?>
                </p>

                <div class="consultation-cta-group">
                    <a href="#consultation" class="consultation-cta-primary">
                        <span aria-hidden="true">📅</span>
                        Schedule Free Consultation
                    </a>
                    <a href="tel:<?php echo esc_attr(preetidreams_get_phone()); ?>" class="consultation-cta-secondary">
                        <span aria-hidden="true">📞</span>
                        Call Now
                    </a>
                </div>

                <ul class="consultation-features">
                    <li class="consultation-feature">
                        <span class="consultation-feature-icon" aria-hidden="true">✓</span>
                        Complimentary consultation
                    </li>
                    <li class="consultation-feature">
                        <span class="consultation-feature-icon" aria-hidden="true">✓</span>
                        Personalized treatment plan
                    </li>
                    <li class="consultation-feature">
                        <span class="consultation-feature-icon" aria-hidden="true">✓</span>
                        No obligation required
                    </li>
                </ul>
            </div>
        </div>
    </section>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
