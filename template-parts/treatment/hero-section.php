<?php
/**
 * Treatment Hero Section Template Part
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

// Get ACF fields for dynamic content
$hero_image = get_field('hero_image');
$treatment_subtitle = get_field('treatment_subtitle');
$hero_description = get_field('hero_description');
?>

<section class="treatment-hero-section">
    <div class="container">
        <div class="hero-content-wrapper">

            <!-- Hero Image -->
            <div class="hero-image-container">
                <?php if ($hero_image) : ?>
                    <img src="<?php echo esc_url($hero_image['url']); ?>"
                         alt="<?php echo esc_attr($hero_image['alt'] ?: get_the_title()); ?>"
                         class="hero-image"
                         loading="eager">
                <?php elseif (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('large', [
                        'class' => 'hero-image',
                        'alt' => get_the_title(),
                        'loading' => 'eager'
                    ]); ?>
                <?php else : ?>
                    <div class="hero-image-placeholder">
                        <span class="placeholder-icon" aria-hidden="true">🌟</span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Hero Text Content -->
            <div class="hero-text-content">

                <!-- Treatment Category -->
                <?php
                $categories = get_the_terms(get_the_ID(), 'treatment_category');
                if ($categories && !is_wp_error($categories)) :
                    $primary_category = $categories[0];
                ?>
                    <span class="treatment-category-badge">
                        <?php echo esc_html($primary_category->name); ?>
                    </span>
                <?php endif; ?>

                <!-- Treatment Title -->
                <h1 class="treatment-hero-title">
                    <?php the_title(); ?>
                </h1>

                <!-- Treatment Subtitle -->
                <?php if ($treatment_subtitle) : ?>
                    <p class="treatment-hero-subtitle">
                        <?php echo esc_html($treatment_subtitle); ?>
                    </p>
                <?php elseif (has_excerpt()) : ?>
                    <p class="treatment-hero-subtitle">
                        <?php echo get_the_excerpt(); ?>
                    </p>
                <?php endif; ?>

                <!-- Hero Description -->
                <?php if ($hero_description) : ?>
                    <div class="treatment-hero-description">
                        <?php echo wp_kses_post($hero_description); ?>
                    </div>
                <?php endif; ?>

                <!-- Hero CTAs -->
                <div class="hero-cta-buttons">
                    <a href="#book-panel"
                       class="btn btn-primary btn-large hero-cta-primary"
                       data-treatment="<?php echo esc_attr(get_the_title()); ?>"
                       data-tab-trigger="book">
                        <span class="btn-text"><?php esc_html_e('Book Consultation', 'medspatheme'); ?></span>
                        <span class="btn-icon" aria-hidden="true">📅</span>
                    </a>

                    <?php
                    // Get phone number from theme options or customizer
                    $phone_number = get_theme_mod('contact_phone', '');
                    if ($phone_number) :
                    ?>
                        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone_number)); ?>"
                           class="btn btn-secondary btn-large hero-cta-secondary">
                            <span class="btn-text"><?php esc_html_e('Call Now', 'medspatheme'); ?></span>
                            <span class="btn-icon" aria-hidden="true">📞</span>
                        </a>
                    <?php endif; ?>
                </div>

            </div>

        </div>
    </div>
</section>
