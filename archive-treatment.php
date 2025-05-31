<?php get_header(); ?>

<main id="main" class="site-main">
    <div class="container">

        <!-- Page Header -->
        <header class="page-header treatment-archive-header">
            <h1 class="page-title"><?php esc_html_e('Our Treatments', 'preetidreams'); ?></h1>
            <p class="page-description">
                <?php esc_html_e('Discover our comprehensive range of advanced medical spa treatments designed to enhance your natural beauty and well-being.', 'preetidreams'); ?>
            </p>
        </header>

        <?php if (have_posts()) : ?>

            <!-- Treatment Filters -->
            <div class="treatment-filters">
                <!-- This container will be populated by JavaScript -->
                <div class="filter-loading-placeholder" style="padding: 2rem; text-align: center; background: #f8f9fa; border-radius: 8px; margin-bottom: 2rem; border: 2px dashed #d4af37;">
                    <p style="color: #2d5a27; font-weight: 600;">🔍 Treatment Filter Loading...</p>
                    <p style="font-size: 0.9rem; color: #87a96b;">If this message persists, JavaScript may not be loading properly.</p>
                </div>
            </div>

            <!-- Treatments Grid -->
            <div class="treatments-grid treatment-grid">
                <?php while (have_posts()) : the_post(); ?>

                    <?php
                    // Get treatment metadata for filtering
                    $categories = get_the_terms(get_the_ID(), 'treatment_category');
                    $primary_category = $categories && !is_wp_error($categories) ? $categories[0]->slug : '';
                    $duration = get_post_meta(get_the_ID(), 'treatment_duration', true);
                    $duration_minutes = get_post_meta(get_the_ID(), 'treatment_duration_minutes', true);
                    $price_range = get_post_meta(get_the_ID(), 'treatment_price_range', true);
                    $price = get_post_meta(get_the_ID(), 'treatment_price', true);
                    $popularity = get_post_meta(get_the_ID(), 'treatment_popularity', true);
                    ?>

                    <article id="treatment-<?php the_ID(); ?>"
                             <?php post_class('treatment-card'); ?>
                             data-category="<?php echo esc_attr($primary_category); ?>"
                             data-duration="<?php echo esc_attr($duration); ?>"
                             data-duration-minutes="<?php echo esc_attr($duration_minutes ?: '30'); ?>"
                             data-price-range="<?php echo esc_attr($price_range); ?>"
                             data-price="<?php echo esc_attr($price ?: '0'); ?>"
                             data-popularity="<?php echo esc_attr($popularity ?: '0'); ?>">

                        <!-- Treatment Image -->
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="treatment-image">
                                <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(sprintf(__('Learn more about %s', 'preetidreams'), get_the_title())); ?>">
                                    <?php the_post_thumbnail('treatment-card', [
                                        'alt' => get_the_title(),
                                        'loading' => 'lazy'
                                    ]); ?>
                                </a>

                                <!-- Treatment Category Badge -->
                                <?php if ($categories && !is_wp_error($categories)) : ?>
                                    <span class="treatment-category"><?php echo esc_html($categories[0]->name); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Treatment Content -->
                        <div class="treatment-content">
                            <header class="treatment-header">
                                <h2 class="treatment-title">
                                    <a href="<?php the_permalink(); ?>" rel="bookmark">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <!-- Treatment Meta -->
                                <div class="treatment-meta">
                                    <?php if ($duration) : ?>
                                        <span class="treatment-duration">
                                            <span class="icon">⏱️</span>
                                            <?php echo esc_html($duration); ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if ($price_range) : ?>
                                        <span class="treatment-price">
                                            <span class="icon">💰</span>
                                            <?php echo esc_html($price_range); ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if ($popularity === 'popular') : ?>
                                        <span class="treatment-badge popular">
                                            <span class="icon">⭐</span>
                                            <?php esc_html_e('Popular', 'preetidreams'); ?>
                                        </span>
                                    <?php elseif ($popularity === 'new') : ?>
                                        <span class="treatment-badge new">
                                            <span class="icon">✨</span>
                                            <?php esc_html_e('New', 'preetidreams'); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </header>

                            <!-- Treatment Description -->
                            <div class="treatment-description">
                                <?php
                                if (has_excerpt()) {
                                    the_excerpt();
                                } else {
                                    echo wp_trim_words(get_the_content(), 25, '...');
                                }
                                ?>
                            </div>

                            <!-- Treatment Actions -->
                            <div class="treatment-actions">
                                <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                                    <?php esc_html_e('Learn More', 'preetidreams'); ?>
                                </a>

                                <a href="#consultation" class="btn btn-secondary consultation-link"
                                   data-treatment="<?php echo esc_attr(get_the_title()); ?>">
                                    <?php esc_html_e('Book Consultation', 'preetidreams'); ?>
                                </a>
                            </div>
                        </div>

                    </article>

                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <?php
            the_posts_pagination([
                'prev_text' => __('&laquo; Previous Treatments', 'preetidreams'),
                'next_text' => __('More Treatments &raquo;', 'preetidreams'),
                'class'     => 'treatments-pagination',
            ]);
            ?>

        <?php else : ?>

            <!-- No Treatments Found -->
            <div class="no-treatments">
                <h2><?php esc_html_e('No Treatments Available', 'preetidreams'); ?></h2>
                <p><?php esc_html_e('We are currently updating our treatment offerings. Please check back soon or contact us for more information.', 'preetidreams'); ?></p>

                <div class="contact-actions">
                    <?php
                    $phone = preetidreams_get_phone();
                    if ($phone) : ?>
                        <a href="tel:<?php echo esc_attr($phone); ?>" class="btn btn-primary">
                            <?php esc_html_e('Call Us', 'preetidreams'); ?>
                        </a>
                    <?php endif; ?>

                    <a href="#consultation" class="btn btn-secondary">
                        <?php esc_html_e('Request Information', 'preetidreams'); ?>
                    </a>
                </div>
            </div>

        <?php endif; ?>

        <!-- Call-to-Action Section -->
        <section class="treatments-cta">
            <div class="cta-content">
                <h2><?php esc_html_e('Ready to Get Started?', 'preetidreams'); ?></h2>
                <p><?php esc_html_e('Schedule a consultation with our expert team to discuss which treatments are right for you.', 'preetidreams'); ?></p>

                <div class="cta-actions">
                    <a href="#consultation" class="btn btn-primary btn-large">
                        <?php esc_html_e('Free Consultation', 'preetidreams'); ?>
                    </a>

                    <?php
                    $phone = preetidreams_get_phone();
                    if ($phone) : ?>
                        <a href="tel:<?php echo esc_attr($phone); ?>" class="btn btn-outline btn-large">
                            <?php echo esc_html($phone); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

    </div>
</main>

<script>
// Initialize Treatment Filter when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('🏥 DOM Ready - Initializing Treatment Filter...');

    // Remove loading placeholder
    const placeholder = document.querySelector('.filter-loading-placeholder');
    if (placeholder) {
        placeholder.remove();
    }

    if (typeof TreatmentFilter !== 'undefined') {
        const treatmentFilter = new TreatmentFilter('.treatment-filters');
        treatmentFilter.init();

        // Store reference globally for debugging
        window.treatmentFilterInstance = treatmentFilter;

        console.log('✅ Treatment Filter initialized successfully');

        // Add success indicator for visual confirmation
        const filterContainer = document.querySelector('.treatment-filters');
        if (filterContainer && filterContainer.children.length > 0) {
            console.log('🎯 Filter interface rendered with', filterContainer.children.length, 'elements');
        }
    } else {
        console.error('❌ TreatmentFilter class not loaded - Check if JavaScript files are properly enqueued');

        // Show error message to user in debug mode
        const filterContainer = document.querySelector('.treatment-filters');
        if (filterContainer && window.location.hostname.includes('localhost')) {
            filterContainer.innerHTML = '<div style="padding: 1rem; background: #f8d7da; color: #721c24; border-radius: 4px; border: 1px solid #f5c6cb;"><strong>Debug Mode:</strong> TreatmentFilter JavaScript not loaded. Check console for details.</div>';
        }
    }

    // Debug information
    console.log('📊 Medical Spa Theme Debug Info:');
    console.log('- medicalSpaTheme config:', window.medicalSpaTheme);
    console.log('- MedicalSpaApp available:', typeof window.MedicalSpaApp !== 'undefined');
    console.log('- TreatmentFilter available:', typeof TreatmentFilter !== 'undefined');
});
</script>

<?php get_footer(); ?>
