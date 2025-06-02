<?php
/**
 * Template Name: About Us
 * Description: About Us page for PreetiDreams Medical Spa
 */

get_header(); ?>

<main id="main" class="site-main about-page">
    <?php while (have_posts()) : the_post(); ?>

        <!-- About Hero Section -->
        <section class="about-hero" role="banner">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1 class="hero-title"><?php esc_html_e('Meet Dr. Preeti Sharma', 'preetidreams'); ?></h1>
                        <p class="hero-subtitle"><?php esc_html_e('Board-Certified Physician & Aesthetic Medicine Expert', 'preetidreams'); ?></p>
                        <div class="credentials-highlights">
                            <div class="credential-item">
                                <span class="credential-icon">🏥</span>
                                <span class="credential-text"><?php esc_html_e('Board Certified Physician', 'preetidreams'); ?></span>
                            </div>
                            <div class="credential-item">
                                <span class="credential-icon">⭐</span>
                                <span class="credential-text"><?php esc_html_e('15+ Years Experience', 'preetidreams'); ?></span>
                            </div>
                            <div class="credential-item">
                                <span class="credential-icon">🎯</span>
                                <span class="credential-text"><?php esc_html_e('2000+ Satisfied Patients', 'preetidreams'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="hero-image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('hero-banner', ['alt' => __('Dr. Preeti Sharma', 'preetidreams')]); ?>
                        <?php else : ?>
                            <div class="placeholder-image">
                                <div class="placeholder-content">
                                    <span class="placeholder-icon">👩‍⚕️</span>
                                    <p><?php esc_html_e('Dr. Preeti Sharma Photo', 'preetidreams'); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Medical Excellence Section -->
        <section class="medical-excellence" role="region" aria-label="<?php esc_attr_e('Medical Excellence and Credentials', 'preetidreams'); ?>">
            <div class="container">
                <div class="section-header">
                    <h2><?php esc_html_e('Medical Excellence & Credentials', 'preetidreams'); ?></h2>
                    <p><?php esc_html_e('Discover the expertise and qualifications that make Dr. Preeti Sharma a trusted leader in aesthetic medicine.', 'preetidreams'); ?></p>
                </div>

                <div class="excellence-grid">
                    <div class="excellence-card">
                        <div class="card-icon">🎓</div>
                        <h3><?php esc_html_e('Board Certification', 'preetidreams'); ?></h3>
                        <div class="card-content">
                            <p><?php esc_html_e('American Board of Dermatology', 'preetidreams'); ?></p>
                            <p><?php esc_html_e('American Board of Aesthetic Medicine', 'preetidreams'); ?></p>
                            <p><?php esc_html_e('Certified in Advanced Injection Techniques', 'preetidreams'); ?></p>
                        </div>
                    </div>

                    <div class="excellence-card">
                        <div class="card-icon">🏆</div>
                        <h3><?php esc_html_e('Awards & Recognition', 'preetidreams'); ?></h3>
                        <div class="card-content">
                            <p><?php esc_html_e('Top Medical Spa - Beverly Hills', 'preetidreams'); ?></p>
                            <p><?php esc_html_e('Excellence in Aesthetic Medicine Award', 'preetidreams'); ?></p>
                            <p><?php esc_html_e('Patient Choice Award - 5 Consecutive Years', 'preetidreams'); ?></p>
                        </div>
                    </div>

                    <div class="excellence-card">
                        <div class="card-icon">📚</div>
                        <h3><?php esc_html_e('Education & Training', 'preetidreams'); ?></h3>
                        <div class="card-content">
                            <p><?php esc_html_e('Medical Degree - Harvard Medical School', 'preetidreams'); ?></p>
                            <p><?php esc_html_e('Dermatology Residency - Mayo Clinic', 'preetidreams'); ?></p>
                            <p><?php esc_html_e('Aesthetic Medicine Fellowship - UCLA', 'preetidreams'); ?></p>
                        </div>
                    </div>

                    <div class="excellence-card">
                        <div class="card-icon">🔬</div>
                        <h3><?php esc_html_e('Research & Innovation', 'preetidreams'); ?></h3>
                        <div class="card-content">
                            <p><?php esc_html_e('Published Research in Aesthetic Medicine', 'preetidreams'); ?></p>
                            <p><?php esc_html_e('Speaker at International Conferences', 'preetidreams'); ?></p>
                            <p><?php esc_html_e('Pioneer in Advanced Treatment Techniques', 'preetidreams'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Medical Philosophy Section -->
        <section class="medical-philosophy" role="region" aria-label="<?php esc_attr_e('Medical Philosophy and Approach', 'preetidreams'); ?>">
            <div class="container">
                <div class="philosophy-content">
                    <div class="philosophy-text">
                        <h2><?php esc_html_e('Medical Artistry Philosophy', 'preetidreams'); ?></h2>
                        <div class="philosophy-quote">
                            <blockquote>
                                <?php esc_html_e('"True beauty enhancement comes from understanding each patient\'s unique anatomy, aesthetic goals, and lifestyle. My approach combines medical precision with artistic vision to achieve natural, beautiful results that enhance your confidence."', 'preetidreams'); ?>
                            </blockquote>
                            <cite><?php esc_html_e('— Dr. Preeti Sharma, MD', 'preetidreams'); ?></cite>
                        </div>
                        <div class="philosophy-principles">
                            <h3><?php esc_html_e('Core Treatment Principles', 'preetidreams'); ?></h3>
                            <ul class="principles-list">
                                <li>
                                    <span class="principle-icon">🎯</span>
                                    <div class="principle-content">
                                        <strong><?php esc_html_e('Personalized Approach', 'preetidreams'); ?></strong>
                                        <p><?php esc_html_e('Every treatment plan is customized to your unique facial structure and aesthetic goals', 'preetidreams'); ?></p>
                                    </div>
                                </li>
                                <li>
                                    <span class="principle-icon">⚕️</span>
                                    <div class="principle-content">
                                        <strong><?php esc_html_e('Medical Safety First', 'preetidreams'); ?></strong>
                                        <p><?php esc_html_e('Comprehensive medical evaluation and safety protocols for every procedure', 'preetidreams'); ?></p>
                                    </div>
                                </li>
                                <li>
                                    <span class="principle-icon">✨</span>
                                    <div class="principle-content">
                                        <strong><?php esc_html_e('Natural Enhancement', 'preetidreams'); ?></strong>
                                        <p><?php esc_html_e('Subtle improvements that enhance your natural beauty without overdoing it', 'preetidreams'); ?></p>
                                    </div>
                                </li>
                                <li>
                                    <span class="principle-icon">🤝</span>
                                    <div class="principle-content">
                                        <strong><?php esc_html_e('Patient Partnership', 'preetidreams'); ?></strong>
                                        <p><?php esc_html_e('Collaborative approach with thorough consultation and ongoing support', 'preetidreams'); ?></p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="philosophy-image">
                        <div class="image-container">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/dr-preeti-consultation.jpg'); ?>"
                                 alt="<?php esc_attr_e('Dr. Preeti during patient consultation', 'preetidreams'); ?>"
                                 loading="lazy">
                            <div class="image-overlay">
                                <div class="overlay-content">
                                    <span class="overlay-icon">💎</span>
                                    <p><?php esc_html_e('Personalized consultation approach', 'preetidreams'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Team Profiles Section -->
        <section class="team-profiles" role="region" aria-label="<?php esc_attr_e('Medical Team Profiles', 'preetidreams'); ?>">
            <div class="container">
                <div class="section-header">
                    <h2><?php esc_html_e('Meet Our Expert Team', 'preetidreams'); ?></h2>
                    <p><?php esc_html_e('Our carefully selected team of medical professionals and aesthetic specialists is dedicated to providing you with exceptional care and beautiful results.', 'preetidreams'); ?></p>
                </div>

                <div class="team-grid">
                    <?php
                    // Get staff members from custom post type
                    $staff_query = new WP_Query([
                        'post_type' => 'staff',
                        'posts_per_page' => -1,
                        'orderby' => 'menu_order',
                        'order' => 'ASC',
                        'meta_query' => [
                            [
                                'key' => 'staff_featured',
                                'value' => '1',
                                'compare' => '='
                            ]
                        ]
                    ]);

                    if ($staff_query->have_posts()) :
                        while ($staff_query->have_posts()) : $staff_query->the_post();
                            $position = get_post_meta(get_the_ID(), 'staff_position', true);
                            $credentials = get_post_meta(get_the_ID(), 'staff_credentials', true);
                            $specialties = get_post_meta(get_the_ID(), 'staff_specialties', true);
                            $experience = get_post_meta(get_the_ID(), 'staff_experience', true);
                    ?>
                            <div class="team-member">
                                <div class="member-image">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('staff-photo', ['alt' => get_the_title()]); ?>
                                    <?php else : ?>
                                        <div class="placeholder-staff-image">
                                            <span class="placeholder-icon">👩‍⚕️</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="member-info">
                                    <h3 class="member-name"><?php the_title(); ?></h3>
                                    <?php if ($position) : ?>
                                        <p class="member-position"><?php echo esc_html($position); ?></p>
                                    <?php endif; ?>
                                    <?php if ($credentials) : ?>
                                        <p class="member-credentials"><?php echo esc_html($credentials); ?></p>
                                    <?php endif; ?>
                                    <div class="member-description">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    <?php if ($specialties) : ?>
                                        <div class="member-specialties">
                                            <strong><?php esc_html_e('Specialties:', 'preetidreams'); ?></strong>
                                            <p><?php echo esc_html($specialties); ?></p>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($experience) : ?>
                                        <div class="member-experience">
                                            <strong><?php esc_html_e('Experience:', 'preetidreams'); ?></strong>
                                            <p><?php echo esc_html($experience); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        // Default team members if no staff posts exist
                    ?>
                        <div class="team-member">
                            <div class="member-image">
                                <div class="placeholder-staff-image">
                                    <span class="placeholder-icon">👩‍⚕️</span>
                                </div>
                            </div>
                            <div class="member-info">
                                <h3 class="member-name"><?php esc_html_e('Sarah Johnson, RN', 'preetidreams'); ?></h3>
                                <p class="member-position"><?php esc_html_e('Lead Aesthetic Nurse', 'preetidreams'); ?></p>
                                <p class="member-credentials"><?php esc_html_e('BSN, Certified Aesthetic Nurse Specialist', 'preetidreams'); ?></p>
                                <div class="member-description">
                                    <p><?php esc_html_e('Sarah brings 8+ years of experience in aesthetic nursing and is specialized in advanced injection techniques and patient care.', 'preetidreams'); ?></p>
                                </div>
                                <div class="member-specialties">
                                    <strong><?php esc_html_e('Specialties:', 'preetidreams'); ?></strong>
                                    <p><?php esc_html_e('Injectable treatments, Skincare consultation, Patient education', 'preetidreams'); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="team-member">
                            <div class="member-image">
                                <div class="placeholder-staff-image">
                                    <span class="placeholder-icon">👨‍⚕️</span>
                                </div>
                            </div>
                            <div class="member-info">
                                <h3 class="member-name"><?php esc_html_e('Michael Chen, PA-C', 'preetidreams'); ?></h3>
                                <p class="member-position"><?php esc_html_e('Physician Assistant', 'preetidreams'); ?></p>
                                <p class="member-credentials"><?php esc_html_e('Master of Physician Assistant Studies', 'preetidreams'); ?></p>
                                <div class="member-description">
                                    <p><?php esc_html_e('Michael specializes in comprehensive aesthetic consultations and advanced treatment planning with a focus on natural results.', 'preetidreams'); ?></p>
                                </div>
                                <div class="member-specialties">
                                    <strong><?php esc_html_e('Specialties:', 'preetidreams'); ?></strong>
                                    <p><?php esc_html_e('Treatment planning, Laser procedures, Body contouring', 'preetidreams'); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Facility Excellence Section -->
        <section class="facility-excellence" role="region" aria-label="<?php esc_attr_e('Facility and Safety Standards', 'preetidreams'); ?>">
            <div class="container">
                <div class="section-header">
                    <h2><?php esc_html_e('State-of-the-Art Facility', 'preetidreams'); ?></h2>
                    <p><?php esc_html_e('Experience the perfect blend of luxury comfort and medical-grade safety in our thoughtfully designed clinic.', 'preetidreams'); ?></p>
                </div>

                <div class="facility-grid">
                    <div class="facility-feature">
                        <div class="feature-icon">🏥</div>
                        <h3><?php esc_html_e('Medical-Grade Safety', 'preetidreams'); ?></h3>
                        <p><?php esc_html_e('Our facility meets and exceeds all medical safety standards with hospital-grade sterilization and safety protocols.', 'preetidreams'); ?></p>
                        <ul class="feature-list">
                            <li><?php esc_html_e('OSHA compliant procedures', 'preetidreams'); ?></li>
                            <li><?php esc_html_e('Medical-grade sterilization', 'preetidreams'); ?></li>
                            <li><?php esc_html_e('Emergency preparedness protocols', 'preetidreams'); ?></li>
                            <li><?php esc_html_e('Ongoing safety training', 'preetidreams'); ?></li>
                        </ul>
                    </div>

                    <div class="facility-feature">
                        <div class="feature-icon">💎</div>
                        <h3><?php esc_html_e('Luxury Experience', 'preetidreams'); ?></h3>
                        <p><?php esc_html_e('Relax in our beautifully appointed private consultation suites designed for your comfort and privacy.', 'preetidreams'); ?></p>
                        <ul class="feature-list">
                            <li><?php esc_html_e('Private consultation rooms', 'preetidreams'); ?></li>
                            <li><?php esc_html_e('Luxury amenities and comfort items', 'preetidreams'); ?></li>
                            <li><?php esc_html_e('Discrete and confidential environment', 'preetidreams'); ?></li>
                            <li><?php esc_html_e('Personalized service experience', 'preetidreams'); ?></li>
                        </ul>
                    </div>

                    <div class="facility-feature">
                        <div class="feature-icon">🔬</div>
                        <h3><?php esc_html_e('Advanced Technology', 'preetidreams'); ?></h3>
                        <p><?php esc_html_e('We invest in the latest technology and equipment to provide you with the most effective treatments available.', 'preetidreams'); ?></p>
                        <ul class="feature-list">
                            <li><?php esc_html_e('Latest laser and energy devices', 'preetidreams'); ?></li>
                            <li><?php esc_html_e('Digital imaging and analysis tools', 'preetidreams'); ?></li>
                            <li><?php esc_html_e('Advanced monitoring equipment', 'preetidreams'); ?></li>
                            <li><?php esc_html_e('Precision injection systems', 'preetidreams'); ?></li>
                        </ul>
                    </div>

                    <div class="facility-feature">
                        <div class="feature-icon">🌿</div>
                        <h3><?php esc_html_e('Wellness Environment', 'preetidreams'); ?></h3>
                        <p><?php esc_html_e('Our spa-like atmosphere promotes relaxation and healing while maintaining the highest medical standards.', 'preetidreams'); ?></p>
                        <ul class="feature-list">
                            <li><?php esc_html_e('Calming, spa-like atmosphere', 'preetidreams'); ?></li>
                            <li><?php esc_html_e('Natural lighting and air filtration', 'preetidreams'); ?></li>
                            <li><?php esc_html_e('Quiet, peaceful treatment rooms', 'preetidreams'); ?></li>
                            <li><?php esc_html_e('Wellness-focused amenities', 'preetidreams'); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Virtual Tour Section -->
        <section class="virtual-tour" role="region" aria-label="<?php esc_attr_e('Virtual Facility Tour', 'preetidreams'); ?>">
            <div class="container">
                <div class="tour-content">
                    <div class="tour-text">
                        <h2><?php esc_html_e('Virtual Facility Tour', 'preetidreams'); ?></h2>
                        <p><?php esc_html_e('Take a virtual walk through our beautiful facility and see where your transformation journey will take place.', 'preetidreams'); ?></p>

                        <div class="tour-highlights">
                            <div class="highlight-item">
                                <span class="highlight-icon">🚪</span>
                                <span class="highlight-text"><?php esc_html_e('Private entrance and reception', 'preetidreams'); ?></span>
                            </div>
                            <div class="highlight-item">
                                <span class="highlight-icon">💺</span>
                                <span class="highlight-text"><?php esc_html_e('Luxury consultation suites', 'preetidreams'); ?></span>
                            </div>
                            <div class="highlight-item">
                                <span class="highlight-icon">⚕️</span>
                                <span class="highlight-text"><?php esc_html_e('Advanced treatment rooms', 'preetidreams'); ?></span>
                            </div>
                            <div class="highlight-item">
                                <span class="highlight-icon">🌸</span>
                                <span class="highlight-text"><?php esc_html_e('Recovery and relaxation areas', 'preetidreams'); ?></span>
                            </div>
                        </div>

                        <div class="tour-actions">
                            <a href="#virtual-tour-modal" class="btn btn-primary tour-cta" data-tour="virtual">
                                <?php esc_html_e('Start Virtual Tour', 'preetidreams'); ?>
                            </a>
                            <a href="#consultation" class="btn btn-outline consultation-cta" data-source="about-virtual-tour">
                                <?php esc_html_e('Schedule In-Person Visit', 'preetidreams'); ?>
                            </a>
                        </div>
                    </div>
                    <div class="tour-preview">
                        <div class="preview-image">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/facility-reception.jpg'); ?>"
                                 alt="<?php esc_attr_e('Luxury medical spa reception area', 'preetidreams'); ?>"
                                 loading="lazy">
                            <div class="preview-overlay">
                                <button class="play-button" aria-label="<?php esc_attr_e('Start virtual tour', 'preetidreams'); ?>">
                                    <span class="play-icon">▶️</span>
                                    <span class="play-text"><?php esc_html_e('Virtual Tour', 'preetidreams'); ?></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Patient Commitment Section -->
        <section class="patient-commitment" role="region" aria-label="<?php esc_attr_e('Our Commitment to Patients', 'preetidreams'); ?>">
            <div class="container">
                <div class="commitment-content">
                    <div class="commitment-header">
                        <h2><?php esc_html_e('Our Commitment to You', 'preetidreams'); ?></h2>
                        <p><?php esc_html_e('Your trust is the foundation of our practice. We are committed to providing you with exceptional care, beautiful results, and complete peace of mind.', 'preetidreams'); ?></p>
                    </div>

                    <div class="commitment-promises">
                        <div class="promise-item">
                            <div class="promise-icon">🤝</div>
                            <h3><?php esc_html_e('Honest Consultation', 'preetidreams'); ?></h3>
                            <p><?php esc_html_e('We provide honest, realistic expectations and will never recommend unnecessary treatments.', 'preetidreams'); ?></p>
                        </div>

                        <div class="promise-item">
                            <div class="promise-icon">🛡️</div>
                            <h3><?php esc_html_e('Complete Privacy', 'preetidreams'); ?></h3>
                            <p><?php esc_html_e('Your privacy and confidentiality are absolutely protected throughout your entire experience.', 'preetidreams'); ?></p>
                        </div>

                        <div class="promise-item">
                            <div class="promise-icon">💚</div>
                            <h3><?php esc_html_e('Ongoing Support', 'preetidreams'); ?></h3>
                            <p><?php esc_html_e('We provide comprehensive aftercare and are always available for your questions and concerns.', 'preetidreams'); ?></p>
                        </div>

                        <div class="promise-item">
                            <div class="promise-icon">✨</div>
                            <h3><?php esc_html_e('Beautiful Results', 'preetidreams'); ?></h3>
                            <p><?php esc_html_e('Our goal is natural, beautiful enhancement that boosts your confidence and makes you feel amazing.', 'preetidreams'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Consultation CTA Section -->
        <section class="about-consultation-cta">
            <div class="container">
                <div class="cta-content">
                    <h2><?php esc_html_e('Ready to Begin Your Journey?', 'preetidreams'); ?></h2>
                    <p><?php esc_html_e('Schedule your personalized consultation with Dr. Preeti Sharma and discover how we can help you achieve your aesthetic goals.', 'preetidreams'); ?></p>

                    <div class="cta-actions">
                        <a href="#consultation" class="btn btn-primary btn-large consultation-cta" data-source="about-page-bottom">
                            <?php esc_html_e('Schedule Your Consultation', 'preetidreams'); ?>
                        </a>

                        <div class="cta-contact-options">
                            <?php
                            $phone = preetidreams_get_phone();
                            if ($phone) : ?>
                                <a href="tel:<?php echo esc_attr($phone); ?>" class="contact-option">
                                    <span class="contact-icon">📞</span>
                                    <span class="contact-text"><?php echo esc_html($phone); ?></span>
                                </a>
                            <?php endif; ?>

                            <?php
                            $email = preetidreams_get_email();
                            if ($email) : ?>
                                <a href="mailto:<?php echo esc_attr($email); ?>" class="contact-option">
                                    <span class="contact-icon">✉️</span>
                                    <span class="contact-text"><?php echo esc_html($email); ?></span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
