<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 * Updated for Luxury Medical Spa Footer Implementation
 * Sprint: SPRINT-FOOTER-IMPL-001 | Task: T-FOOTER-004 (Google Maps Integration)
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package MedSpaTheme
 */

// Enqueue the tokenized CSS files
wp_enqueue_style('footer-tokens', get_template_directory_uri() . '/assets/css/tokens/footer-tokens.css', array(), wp_get_theme()->get('Version'));
wp_enqueue_style('footer-tokenized', get_template_directory_uri() . '/assets/css/components/footer-tokenized.css', array('footer-tokens'), wp_get_theme()->get('Version'));
?>

	</div><!-- #content -->

	<footer id="colophon"
	        class="site-footer luxury-footer"
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
