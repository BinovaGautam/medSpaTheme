<?php
/**
 * Footer Structure Template
 *
 * Provides the structural foundation for the luxury medical spa footer
 * Sprint: SPRINT-FOOTER-IMPL-001 | Task: T-FOOTER-004 (Google Maps Integration)
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Load footer sections from templates/footer/footer-sections.php
require_once get_template_directory() . '/templates/footer/footer-sections.php';
?>

<div class="footer-structure-wrapper">

    <!-- Section 1: Hero Call-to-Action Section -->
    <?php if (get_theme_mod('footer_enable_hero', true)) : ?>
        <?php render_footer_hero_section(); ?>
    <?php endif; ?>

    <!-- Section 2: Four-Column Information Grid -->
    <?php render_footer_info_grid(); ?>

    <!-- Section 3: Interactive Google Maps Integration -->
    <?php if (get_theme_mod('footer_enable_map', true)) : ?>
        <?php get_template_part('templates/footer/footer-map'); ?>
    <?php endif; ?>

    <!-- Section 4: Newsletter & Social Engagement -->
    <?php if (get_theme_mod('footer_enable_newsletter', true)) : ?>
        <?php render_footer_newsletter_section(); ?>
    <?php endif; ?>

    <!-- Section 5: Footer Navigation & Legal -->
    <?php render_footer_legal_section(); ?>

</div><!-- .footer-structure-wrapper -->
