<?php
/**
 * Simple Treatment Hero Section Template Part (No ACF)
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */
?>

<section class="treatment-hero-section">
    <div class="container">
        <div class="hero-content-wrapper">

            <!-- Hero Image -->
            <div class="hero-image-container">
                <?php if (has_post_thumbnail()) : ?>
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

                <!-- Treatment Title -->
                <h1 class="treatment-hero-title">
                    <?php the_title(); ?>
                </h1>

                <!-- Treatment Subtitle -->
                <?php if (has_excerpt()) : ?>
                    <p class="treatment-hero-subtitle">
                        <?php echo get_the_excerpt(); ?>
                    </p>
                <?php endif; ?>

                <!-- Hero Description -->
                <div class="treatment-hero-description">
                    <p>Professional treatment with experienced practitioners. Book your consultation today.</p>
                </div>

                <!-- Hero CTAs -->
                <div class="hero-cta-buttons">
                    <a href="#book-panel"
                       class="btn btn-primary btn-large hero-cta-primary"
                       data-treatment="<?php echo esc_attr(get_the_title()); ?>"
                       data-tab-trigger="book">
                        <span class="btn-text"><?php esc_html_e('Book Consultation', 'medspatheme'); ?></span>
                        <span class="btn-icon" aria-hidden="true">📅</span>
                    </a>

                    <a href="tel:+15551234567"
                       class="btn btn-secondary btn-large hero-cta-secondary">
                        <span class="btn-text"><?php esc_html_e('Call Now', 'medspatheme'); ?></span>
                        <span class="btn-icon" aria-hidden="true">📞</span>
                    </a>
                </div>

            </div>

        </div>
    </div>
</section>
