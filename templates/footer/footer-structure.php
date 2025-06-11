<?php
/**
 * Footer Structure Template
 *
 * Provides the structural foundation for the luxury medical spa footer
 * Sprint: SPRINT-6-EXT | Task: T6.8-EXT-3 (Revised) - Simplified Elegant Design
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

    <!-- Section 1: Hero Call-to-Action Section - DISABLED for Simplified Design -->
    <?php if (get_theme_mod('footer_enable_hero', false)) : ?>
        <?php render_footer_hero_section(); ?>
    <?php endif; ?>

    <!-- Section 1: Four-Column Information Grid -->
    <?php render_footer_info_grid(); ?>

    <!-- Section 2: Interactive Google Maps Integration -->
    <?php if (get_theme_mod('footer_enable_map', true)) : ?>
        <?php get_template_part('templates/footer/footer-map'); ?>
    <?php endif; ?>

    <!-- Section 3: Newsletter & Social Engagement -->
    <?php if (get_theme_mod('footer_enable_newsletter', true)) : ?>
        <?php render_footer_newsletter_section(); ?>
    <?php endif; ?>

    <!-- Section 4: Footer Navigation & Legal -->
    <?php render_footer_legal_section(); ?>

</div><!-- .footer-structure-wrapper -->
