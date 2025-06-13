<?php get_header(); ?>

<main id="main" class="site-main homepage">

    <!-- Premium Hero Section -->
    <section class="hero-section premium-hero" id="hero">
        <!-- Hero Content -->
        <div class="container">
            <div class="hero-layout">
                <!-- Left: Content Section -->
                <div class="hero-content-section">
                    <div class="hero-text-content">
                        <h1 class="hero-title">
                            <?php echo get_theme_mod('hero_title', 'Transform Your Beauty with Advanced Medical Spa Treatments'); ?>
                        </h1>

                        <p class="hero-subtitle">
                            <?php echo get_theme_mod('hero_subtitle', 'Experience the latest in non-surgical aesthetic treatments performed by board-certified professionals in a luxurious, comfortable environment.'); ?>
                        </p>

                        <!-- Trust Indicators -->
                        <div class="trust-indicators">
                            <div class="trust-item">
                                <span class="trust-icon">✅</span>
                                <span class="trust-text">Board Certified</span>
                            </div>
                            <div class="trust-item">
                                <span class="trust-icon">🏆</span>
                                <span class="trust-text">Award Winning</span>
                            </div>
                            <div class="trust-item">
                                <span class="trust-icon">💯</span>
                                <span class="trust-text">2000+ Happy Patients</span>
                            </div>
                            <div class="trust-item">
                                <span class="trust-icon">🔒</span>
                                <span class="trust-text">HIPAA Compliant</span>
                            </div>
                        </div>

                        <!-- Hero Actions -->
                        <div class="hero-actions">
                            <a href="#consultation" class="btn btn-primary btn-lg cta-primary">
                                <span class="btn-icon">📅</span>
                                <span class="btn-text">Book Free Consultation</span>
                            </a>
                            <a href="tel:+1234567890" class="btn btn-secondary btn-lg cta-secondary">
                                <span class="btn-icon">📞</span>
                                <span class="btn-text">Call Now</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right: Interactive Treatment Selection -->
                <div class="hero-interactive-section">
                    <?php
                    // Include the elegant quiz component
                    get_template_part('template-parts/components/elegant-quiz', null, array(
                        'title' => __('Find Your Perfect Treatment', 'medspa-theme'),
                        'subtitle' => __('Answer a few questions to get personalized recommendations', 'medspa-theme'),
                        'css_class' => 'hero-quiz-instance',
                        'show_progress' => false
                    ));
                    ?>
                </div>

            </div>
        </div>
    </section>

    <!-- Featured Treatments Section -->
    <section class="featured-treatments modern-section">
        <div class="container">
            <header class="section-header text-center">
                <h2 class="section-title"><?php esc_html_e('Popular Treatments', 'preetidreams'); ?></h2>
                <p class="section-subtitle"><?php esc_html_e('Discover our most sought-after aesthetic treatments designed to enhance your natural beauty.', 'preetidreams'); ?></p>
            </header>

            <!-- Treatment Filters for Homepage -->
            <div class="treatment-filters">
                <!-- This container will be populated by JavaScript -->
                <div class="filter-loading-placeholder loading-container">
                    <div class="loading-content">
                        <div class="loading-spinner"></div>
                        <p class="loading-primary-text">🔍 Treatment Filter Loading...</p>
                        <p class="loading-secondary-text">Enhancing your browsing experience...</p>
                    </div>
                </div>
            </div>

            <div class="treatments-showcase treatment-grid modern-grid">
                <?php
                // Use our TreatmentsDataStore to get all treatment data
                if (class_exists('TreatmentsDataStore')) {
                    $all_treatments = TreatmentsDataStore::get_treatments();

                    if ($all_treatments) :
                        foreach ($all_treatments as $treatment_data) :
                            // Render the treatment card using our component
                            if (class_exists('TreatmentCard')) {
                                $card = new TreatmentCard($treatment_data);
                                echo $card->render();
                            }
                        endforeach;
                    else : ?>
                        <div class="no-treatments-message">
                            <p><?php esc_html_e('No treatments found.', 'preetidreams'); ?></p>
                        </div>
                    <?php endif;
                } else {
                    // Fallback to WordPress posts if TreatmentsDataStore is not available
                    $all_treatments = get_posts([
                        'post_type' => 'treatment',
                        'posts_per_page' => 12,
                        'orderby' => 'menu_order',
                        'order' => 'ASC'
                    ]);

                    if ($all_treatments) :
                        foreach ($all_treatments as $treatment) : setup_postdata($treatment);
                            // Get treatment metadata
                            $treatment_data = [
                                'id' => $treatment->ID,
                                'title' => get_the_title($treatment->ID),
                                'description' => get_the_excerpt($treatment->ID),
                                'duration' => get_post_meta($treatment->ID, '_treatment_duration', true),
                                'price' => [
                                    'from' => get_post_meta($treatment->ID, '_treatment_price_from', true),
                                    'amount' => get_post_meta($treatment->ID, '_treatment_price', true),
                                    'currency' => 'USD'
                                ],
                                'cta' => [
                                    'primary' => [
                                        'text' => __('Learn More', 'preetidreams'),
                                        'url' => get_permalink($treatment->ID)
                                    ],
                                    'secondary' => [
                                        'text' => __('Book Consultation', 'preetidreams'),
                                        'url' => '#consultation'
                                    ]
                                ]
                            ];

                            // Get treatment categories
                            $categories = get_the_terms($treatment->ID, 'treatment_category');
                            if ($categories && !is_wp_error($categories)) {
                                $treatment_data['category'] = $categories[0]->name;
                            }

                            // Get treatment features
                            $features = get_the_terms($treatment->ID, 'treatment_feature');
                            if ($features && !is_wp_error($features)) {
                                $treatment_data['features'] = array_map(function($feature) {
                                    return $feature->name;
                                }, $features);
                            }

                            // Get treatment benefits
                            $benefits = get_the_terms($treatment->ID, 'treatment_benefit');
                            if ($benefits && !is_wp_error($benefits)) {
                                $treatment_data['benefits'] = array_map(function($benefit) {
                                    return $benefit->name;
                                }, $benefits);
                            }

                            // Render the treatment card using our component
                            if (class_exists('TreatmentCard')) {
                                $card = new TreatmentCard($treatment_data);
                                echo $card->render();
                            }
                        endforeach;
                        wp_reset_postdata();
                    else : ?>
                        <div class="no-treatments-message">
                            <p><?php esc_html_e('No treatments found.', 'preetidreams'); ?></p>
                        </div>
                    <?php endif;
                } ?>
            </div>

            <div class="section-cta text-center">
                <a href="<?php echo esc_url(home_url('/treatments/')); ?>" class="btn btn-outline btn-large">
                    <?php esc_html_e('View All Treatments', 'preetidreams'); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section modern-section bg-soft">
        <div class="container">
            <div class="about-layout">
                <div class="about-content">
                    <h2 class="about-title section-title"><?php esc_html_e('Why Choose Our Medical Spa?', 'preetidreams'); ?></h2>

                    <div class="about-text">
                        <p class="lead-text"><?php esc_html_e('We combine advanced medical expertise with luxurious spa comfort to deliver exceptional aesthetic results. Our board-certified professionals use the latest technology and techniques to help you achieve your beauty goals safely and effectively.', 'preetidreams'); ?></p>
                    </div>

                    <div class="features-grid modern-features">
                        <div class="feature-item">
                            <div class="feature-icon">👨‍⚕️</div>
                            <h3><?php esc_html_e('Expert Professionals', 'preetidreams'); ?></h3>
                            <p><?php esc_html_e('Board-certified doctors and experienced aestheticians.', 'preetidreams'); ?></p>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">🔬</div>
                            <h3><?php esc_html_e('Advanced Technology', 'preetidreams'); ?></h3>
                            <p><?php esc_html_e('State-of-the-art equipment for optimal results.', 'preetidreams'); ?></p>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">🏥</div>
                            <h3><?php esc_html_e('Medical-Grade Safety', 'preetidreams'); ?></h3>
                            <p><?php esc_html_e('Highest standards of safety and cleanliness.', 'preetidreams'); ?></p>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">💎</div>
                            <h3><?php esc_html_e('Luxury Experience', 'preetidreams'); ?></h3>
                            <p><?php esc_html_e('Comfortable, spa-like environment for relaxation.', 'preetidreams'); ?></p>
                        </div>
                    </div>

                    <div class="about-cta">
                        <a href="<?php echo esc_url(get_post_type_archive_link('staff')); ?>" class="btn btn-outline btn-large">
                            <?php esc_html_e('Meet Our Team', 'preetidreams'); ?>
                        </a>
                    </div>
                </div>

                <div class="about-image">
                    <?php
                    $about_image = get_theme_mod('about_image');
                    if ($about_image) : ?>
                        <img src="<?php echo esc_url($about_image); ?>" alt="<?php esc_attr_e('Medical Spa Interior', 'preetidreams'); ?>" loading="lazy">
                    <?php else : ?>
                        <!-- Placeholder about image -->
                        <div class="about-placeholder">
                            <div class="placeholder-content">
                                <div class="placeholder-icon">🏥</div>
                                <p><?php esc_html_e('Professional Medical Spa Interior', 'preetidreams'); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section modern-section">
        <div class="container">
            <header class="section-header text-center">
                <h2 class="section-title"><?php esc_html_e('What Our Patients Say', 'preetidreams'); ?></h2>
                <p class="section-subtitle"><?php esc_html_e('Real stories from real patients who have transformed their confidence with our treatments.', 'preetidreams'); ?></p>
            </header>

            <div class="testimonials-grid modern-testimonials">
                <?php
                // Get featured testimonials
                $testimonials = get_posts([
                    'post_type' => 'testimonial',
                    'posts_per_page' => 3,
                    'orderby' => 'rand'
                ]);

                if ($testimonials) :
                    foreach ($testimonials as $testimonial) : setup_postdata($testimonial); ?>

                        <div class="testimonial-card modern-testimonial">
                            <?php if (has_post_thumbnail($testimonial->ID)) : ?>
                                <div class="testimonial-photo">
                                    <?php echo get_the_post_thumbnail($testimonial->ID, 'thumbnail', ['alt' => get_the_title($testimonial->ID)]); ?>
                                </div>
                            <?php else : ?>
                                <div class="testimonial-photo testimonial-placeholder">
                                    <div class="placeholder-avatar">👤</div>
                                </div>
                            <?php endif; ?>

                            <div class="testimonial-content">
                                <div class="testimonial-text">
                                    <?php echo wp_trim_words(get_the_content(null, false, $testimonial->ID), 30); ?>
                                </div>

                                <div class="testimonial-author">
                                    <h4 class="author-name"><?php echo get_the_title($testimonial->ID); ?></h4>
                                    <?php
                                    $treatment = get_post_meta($testimonial->ID, 'testimonial_treatment', true);
                                    if ($treatment) : ?>
                                        <p class="treatment-received"><?php echo esc_html($treatment); ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="testimonial-rating">
                                    <span class="stars">⭐⭐⭐⭐⭐</span>
                                </div>
                            </div>
                        </div>

                    <?php endforeach;
                    wp_reset_postdata();
                else : ?>
                    <!-- Sample testimonials placeholder -->
                    <div class="sample-testimonials">
                        <div class="testimonial-card modern-testimonial">
                            <div class="testimonial-photo testimonial-placeholder">
                                <div class="placeholder-avatar">👤</div>
                            </div>
                            <div class="testimonial-content">
                                <div class="testimonial-text">
                                    "Amazing results from my Botox treatment! The staff is professional and the environment is so relaxing. I highly recommend this medical spa."
                                </div>
                                <div class="testimonial-author">
                                    <h4 class="author-name">Sarah M.</h4>
                                    <p class="treatment-received">Botox Treatment</p>
                                </div>
                                <div class="testimonial-rating">
                                    <span class="stars">⭐⭐⭐⭐⭐</span>
                                </div>
                            </div>
                        </div>

                        <div class="testimonial-card modern-testimonial">
                            <div class="testimonial-photo testimonial-placeholder">
                                <div class="placeholder-avatar">👤</div>
                            </div>
                            <div class="testimonial-content">
                                <div class="testimonial-text">
                                    "The HydraFacial was incredible! My skin looks and feels years younger. The entire team made me feel comfortable throughout the process."
                                </div>
                                <div class="testimonial-author">
                                    <h4 class="author-name">Jennifer L.</h4>
                                    <p class="treatment-received">HydraFacial</p>
                                </div>
                                <div class="testimonial-rating">
                                    <span class="stars">⭐⭐⭐⭐⭐</span>
                                </div>
                            </div>
                        </div>

                        <div class="testimonial-card modern-testimonial">
                            <div class="testimonial-photo testimonial-placeholder">
                                <div class="placeholder-avatar">👤</div>
                            </div>
                            <div class="testimonial-content">
                                <div class="testimonial-text">
                                    "Exceptional service and results! The laser treatment exceeded my expectations. I'll definitely be back for more treatments."
                                </div>
                                <div class="testimonial-author">
                                    <h4 class="author-name">Michael K.</h4>
                                    <p class="treatment-received">Laser Treatment</p>
                                </div>
                                <div class="testimonial-rating">
                                    <span class="stars">⭐⭐⭐⭐⭐</span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="section-cta text-center">
                <a href="<?php echo esc_url(get_post_type_archive_link('testimonial')); ?>" class="btn btn-outline btn-large">
                    <?php esc_html_e('Read More Reviews', 'preetidreams'); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- Consultation CTA Section -->
    <section id="consultation" class="consultation-section modern-section bg-primary">
        <div class="container">
            <div class="consultation-content consultation-layout">
                <div class="consultation-text">
                    <h2 class="consultation-title"><?php esc_html_e('Ready to Start Your Transformation?', 'preetidreams'); ?></h2>
                    <p class="consultation-subtitle"><?php esc_html_e('Book your complimentary consultation today and discover how our personalized treatments can help you achieve your aesthetic goals.', 'preetidreams'); ?></p>

                    <div class="consultation-benefits">
                        <div class="benefit-item">
                            <span class="icon">✅</span>
                            <span class="text"><?php esc_html_e('Complimentary consultation', 'preetidreams'); ?></span>
                        </div>
                        <div class="benefit-item">
                            <span class="icon">✅</span>
                            <span class="text"><?php esc_html_e('Personalized treatment plan', 'preetidreams'); ?></span>
                        </div>
                        <div class="benefit-item">
                            <span class="icon">✅</span>
                            <span class="text"><?php esc_html_e('No pressure, just expert advice', 'preetidreams'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="consultation-form-wrapper">
                    <form class="consultation-form modern-form" action="#" method="post">
                        <h3 class="form-title"><?php esc_html_e('Book Your Free Consultation', 'preetidreams'); ?></h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="consultation-name"><?php esc_html_e('Full Name', 'preetidreams'); ?></label>
                                <input type="text" id="consultation-name" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="consultation-phone"><?php esc_html_e('Phone Number', 'preetidreams'); ?></label>
                                <input type="tel" id="consultation-phone" name="phone" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="consultation-email"><?php esc_html_e('Email Address', 'preetidreams'); ?></label>
                            <input type="email" id="consultation-email" name="email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="consultation-treatment"><?php esc_html_e('Treatment Interest', 'preetidreams'); ?></label>
                            <select id="consultation-treatment" name="treatment" class="form-control">
                                <option value=""><?php esc_html_e('Select a treatment (optional)', 'preetidreams'); ?></option>
                                <option value="botox"><?php esc_html_e('Botox', 'preetidreams'); ?></option>
                                <option value="fillers"><?php esc_html_e('Dermal Fillers', 'preetidreams'); ?></option>
                                <option value="hydrafacial"><?php esc_html_e('HydraFacial', 'preetidreams'); ?></option>
                                <option value="laser"><?php esc_html_e('Laser Treatments', 'preetidreams'); ?></option>
                                <option value="other"><?php esc_html_e('Other', 'preetidreams'); ?></option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="consultation-message"><?php esc_html_e('Message (Optional)', 'preetidreams'); ?></label>
                            <textarea id="consultation-message" name="message" class="form-control" rows="3" placeholder="<?php esc_attr_e('Tell us about your aesthetic goals...', 'preetidreams'); ?>"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="consent" required>
                                <span class="checkmark"></span>
                                <?php esc_html_e('I consent to receive communications about my consultation and treatment options.', 'preetidreams'); ?>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-large">
                            <?php esc_html_e('Schedule My Consultation', 'preetidreams'); ?>
                        </button>

                        <p class="form-privacy">
                            <?php esc_html_e('Your information is secure and confidential. We follow HIPAA guidelines.', 'preetidreams'); ?>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

</main>

<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
// Initialize Treatment Filter on Homepage when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('🏠 Homepage Ready - Initializing Treatment Filter...');

    // Remove loading placeholder
    const placeholder = document.querySelector('.filter-loading-placeholder');
    if (placeholder) {
        setTimeout(() => {
            placeholder.remove();
        }, 1000); // Show loading for 1 second for better UX
    }

    if (typeof TreatmentFilter !== 'undefined') {
        const treatmentFilter = new TreatmentFilter('.treatment-filters');
        treatmentFilter.init();

        // Store reference globally for debugging
        window.homePageFilterInstance = treatmentFilter;

        console.log('✅ Homepage Treatment Filter initialized successfully');

        // Add success indicator for visual confirmation
        const filterContainer = document.querySelector('.treatment-filters');
        if (filterContainer && filterContainer.children.length > 0) {
            console.log('🎯 Homepage filter interface rendered with', filterContainer.children.length, 'elements');
        }

        // Track homepage filter initialization
        if (window.MedicalSpaApp) {
            window.MedicalSpaApp.getModule('analytics')?.track('homepage_filter_initialized', {
                treatments_count: document.querySelectorAll('.treatment-card').length,
                page_location: 'homepage'
            });
        }
    } else {
        console.error('❌ TreatmentFilter class not loaded on homepage - Check if JavaScript files are properly enqueued');

        // Show error message to user in debug mode
        const filterContainer = document.querySelector('.treatment-filters');
        if (filterContainer && window.location.hostname.includes('localhost')) {
                            filterContainer.innerHTML = '<div class="debug-message error-message"><strong>Debug Mode:</strong> TreatmentFilter JavaScript not loaded on homepage. Check console for details.</div>';
        }
    }

    // Debug information for homepage
    console.log('📊 Homepage Medical Spa Theme Debug Info:');
    console.log('- medicalSpaTheme config:', window.medicalSpaTheme);
    console.log('- MedicalSpaApp available:', typeof window.MedicalSpaApp !== 'undefined');
    console.log('- TreatmentFilter available:', typeof TreatmentFilter !== 'undefined');
    console.log('- Treatment cards found:', document.querySelectorAll('.treatment-card').length);
});
</script>

<?php get_footer(); ?>
