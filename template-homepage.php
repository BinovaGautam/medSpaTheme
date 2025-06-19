<?php
/**
 * Template Name: Homepage
 *
 * Custom homepage template integrating Sprint 11 components
 *
 * @package MedSpaTheme
 * @since 1.0.0
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 * @task T11.2, T11.3 Homepage Implementation
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<main id="main" class="site-main" role="main">

    <?php
    // T11.1 Hero Section (marked as completed - no changes needed)
    // Hero section is handled by existing hero component
    ?>

    <?php
    // T11.2 Services Overview - Grouped Sections Implementation
    if (class_exists('ComponentRegistry') && ComponentRegistry::is_registered('service-section')) {
        echo ComponentRegistry::render('service-section', [
            'section_title' => get_theme_mod('service_section_title', 'Our Treatment Artistry'),
            'section_subtitle' => get_theme_mod('service_section_subtitle', 'Discover Personalized Medical Aesthetics'),
            'section_description' => get_theme_mod('service_section_description', 'Each treatment is carefully curated to enhance your natural beauty with precision, artistry, and innovation.'),
            'sections' => [
                'injectable-artistry',
                'facial-renaissance',
                'laser-precision',
                'body-sculpting',
                'wellness-sanctuary'
            ]
        ]);
    }
    ?>

    <?php
    // T11.3 Why Choose Us Section - Trust Indicators Implementation
    if (class_exists('ComponentRegistry') && ComponentRegistry::is_registered('trust-indicators')) {
        echo ComponentRegistry::render('trust-indicators', [
            'section_title' => get_theme_mod('trust_indicators_title', 'Why Choose PreetiDreams'),
            'section_subtitle' => get_theme_mod('trust_indicators_subtitle', 'Experience the difference of medical artistry combined with luxury care'),
            'indicators' => [
                'board-certified',
                'award-winning',
                'happy-patients',
                'hipaa-compliant'
            ]
        ]);
    }
    ?>

    <?php
    // Additional homepage content can be added here
    // This template maintains flexibility for future enhancements

    // Check if page has content
    if (have_posts()) :
        while (have_posts()) :
            the_post();

            // Only show page content if it exists and is not empty
            $content = get_the_content();
            if (!empty(trim($content))) :
    ?>
                <section class="page-content-section">
                    <div class="container">
                        <div class="page-content">
                            <?php
                            the_content();

                            wp_link_pages([
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'medspa-theme'),
                                'after'  => '</div>',
                            ]);
                            ?>
                        </div>
                    </div>
                </section>
    <?php
            endif;
        endwhile;
    endif;
    ?>

</main><!-- #main -->

<?php
get_footer();
?>
