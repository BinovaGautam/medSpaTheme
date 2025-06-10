<?php
/**
 * Footer Structure Template
 *
 * Provides the structural foundation for the luxury medical spa footer
 * Sprint: SPRINT-FOOTER-IMPL-001 | Task: T-FOOTER-001
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<footer id="colophon" class="site-footer luxury-footer" role="contentinfo" aria-label="Site Footer">

    <?php
    /**
     * Hook: footer_before_content
     *
     * @since 1.0.0
     */
    do_action('footer_before_content');
    ?>

    <!-- Section 1: Hero Call-to-Action Section -->
    <?php get_template_part('templates/footer/sections/hero-section'); ?>

    <!-- Section 2: Four-Column Information Grid -->
    <?php get_template_part('templates/footer/sections/info-grid'); ?>

    <!-- Section 3: Interactive Map Section -->
    <?php get_template_part('templates/footer/sections/map-section'); ?>

    <!-- Section 4: Newsletter & Social Engagement -->
    <?php get_template_part('templates/footer/sections/newsletter-section'); ?>

    <!-- Section 5: Footer Navigation & Legal -->
    <?php get_template_part('templates/footer/sections/legal-section'); ?>

    <?php
    /**
     * Hook: footer_after_content
     *
     * @since 1.0.0
     */
    do_action('footer_after_content');
    ?>

</footer><!-- #colophon -->
