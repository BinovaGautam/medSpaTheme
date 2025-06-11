<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 * Updated for Luxury Medical Spa Footer Implementation - Simplified Elegant Design
 * Sprint: SPRINT-6-EXT | Task: T6.8-EXT-3 (Revised)
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package MedSpaTheme
 */

// Enqueue simplified elegant footer styles
wp_enqueue_style('footer-luxury', get_template_directory_uri() . '/assets/css/components/footer-luxury.css', array(), wp_get_theme()->get('Version'));
wp_enqueue_style('footer-enhanced-spacing', get_template_directory_uri() . '/assets/css/components/footer-enhanced-spacing.css', array('footer-luxury'), wp_get_theme()->get('Version'));
wp_enqueue_style('footer-simplified-elegant', get_template_directory_uri() . '/assets/css/components/footer-simplified-elegant.css', array('footer-enhanced-spacing'), wp_get_theme()->get('Version'));
?>

	</div><!-- #content -->

	<footer id="colophon"
	        class="site-footer simplified-elegant"
	        role="contentinfo"
	        aria-label="<?php esc_attr_e('Site Footer', 'medspatheme'); ?>">

		<div class="footer-wrapper">

			<?php
			// Load structured footer content
			get_template_part('templates/footer/footer-structure');
			?>

		</div><!-- .footer-wrapper -->

	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
