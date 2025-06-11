<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 * Simple semantic footer following design system patterns
 *
 * @package MedSpaTheme
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="footer-container">

			<!-- Main Footer Content -->
			<div class="footer-main">
				<div class="container">
					<div class="footer-grid">

						<!-- Brand Section -->
						<div class="footer-brand">
							<?php if (has_custom_logo()) : ?>
								<div class="footer-logo">
									<?php the_custom_logo(); ?>
								</div>
							<?php else : ?>
								<div class="footer-brand-text">
									<h2 class="footer-site-title">
										<a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
											<?php bloginfo('name'); ?>
										</a>
									</h2>
									<?php
									$description = get_bloginfo('description', 'display');
									if ($description || is_customize_preview()) :
									?>
										<p class="footer-site-description"><?php echo $description; ?></p>
									<?php endif; ?>
								</div>
							<?php endif; ?>

							<!-- Social Media Links -->
							<div class="footer-social" role="navigation" aria-label="<?php esc_attr_e('Social Media Links', 'preetidreams'); ?>">
								<?php
								// Get social media links from customizer or define defaults
								$social_links = array(
									'instagram' => get_theme_mod('social_instagram', '#'),
									'facebook' => get_theme_mod('social_facebook', '#'),
									'youtube' => get_theme_mod('social_youtube', '#'),
									'tiktok' => get_theme_mod('social_tiktok', '#')
								);

								foreach ($social_links as $platform => $url) :
									if ($url && $url !== '#') :
								?>
									<a href="<?php echo esc_url($url); ?>"
									   class="social-link social-<?php echo esc_attr($platform); ?>"
									   aria-label="<?php echo sprintf(esc_attr__('Follow us on %s', 'preetidreams'), ucfirst($platform)); ?>"
									   target="_blank"
									   rel="noopener noreferrer">
										<span class="social-icon" aria-hidden="true">
											<?php
											switch ($platform) {
												case 'instagram':
													echo '📷';
													break;
												case 'facebook':
													echo '📘';
													break;
												case 'youtube':
													echo '📹';
													break;
												case 'tiktok':
													echo '🎵';
													break;
											}
											?>
										</span>
									</a>
								<?php
									endif;
								endforeach;
								?>
							</div>
						</div>

						<!-- Company Links -->
						<div class="footer-section footer-company">
							<h3 class="footer-section-title"><?php esc_html_e('Company', 'preetidreams'); ?></h3>
							<nav class="footer-nav" role="navigation" aria-label="<?php esc_attr_e('Company Links', 'preetidreams'); ?>">
								<ul class="footer-nav-list">
									<li><a href="<?php echo esc_url(home_url('/about')); ?>"><?php esc_html_e('About Us', 'preetidreams'); ?></a></li>
									<li><a href="<?php echo esc_url(home_url('/treatments')); ?>"><?php esc_html_e('Our Treatments', 'preetidreams'); ?></a></li>
									<li><a href="<?php echo esc_url(home_url('/staff')); ?>"><?php esc_html_e('Our Team', 'preetidreams'); ?></a></li>
									<li><a href="<?php echo esc_url(home_url('/testimonials')); ?>"><?php esc_html_e('Patient Reviews', 'preetidreams'); ?></a></li>
									<li><a href="<?php echo esc_url(home_url('/before-after')); ?>"><?php esc_html_e('Before & After', 'preetidreams'); ?></a></li>
									<li><a href="<?php echo esc_url(home_url('/contact')); ?>"><?php esc_html_e('Contact Us', 'preetidreams'); ?></a></li>
								</ul>
							</nav>
						</div>

						<!-- Resources Links -->
						<div class="footer-section footer-resources">
							<h3 class="footer-section-title"><?php esc_html_e('Resources', 'preetidreams'); ?></h3>
							<nav class="footer-nav" role="navigation" aria-label="<?php esc_attr_e('Resources Links', 'preetidreams'); ?>">
								<ul class="footer-nav-list">
									<li><a href="<?php echo esc_url(home_url('/pre-post-care')); ?>"><?php esc_html_e('Pre & Post-Treatment Care', 'preetidreams'); ?></a></li>
									<li><a href="<?php echo esc_url(home_url('/treatment-guide')); ?>"><?php esc_html_e('Treatment Guide', 'preetidreams'); ?></a></li>
									<li><a href="<?php echo esc_url(home_url('/blog')); ?>"><?php esc_html_e('Articles', 'preetidreams'); ?></a></li>
									<li><a href="<?php echo esc_url(home_url('/faq')); ?>"><?php esc_html_e('FAQs', 'preetidreams'); ?></a></li>
									<li><a href="<?php echo esc_url(home_url('/accessibility')); ?>"><?php esc_html_e('Accessibility', 'preetidreams'); ?></a></li>
									<li><a href="<?php echo esc_url(home_url('/financing')); ?>"><?php esc_html_e('Financing Options', 'preetidreams'); ?></a></li>
								</ul>
							</nav>
						</div>

						<!-- Newsletter Signup -->
						<div class="footer-section footer-newsletter">
							<h3 class="footer-section-title"><?php esc_html_e('Keep Up With Us', 'preetidreams'); ?></h3>
							<p class="newsletter-description">
								<?php esc_html_e('Stay informed about our latest treatments, special offers, and wellness tips.', 'preetidreams'); ?>
							</p>

							<form class="newsletter-form" action="#" method="post" aria-label="<?php esc_attr_e('Newsletter Signup', 'preetidreams'); ?>">
								<div class="newsletter-input-group">
									<label for="footer-email" class="screen-reader-text"><?php esc_html_e('Email Address', 'preetidreams'); ?></label>
									<input type="email"
										   id="footer-email"
										   name="email"
										   placeholder="<?php esc_attr_e('Email*', 'preetidreams'); ?>"
										   required
										   class="newsletter-input"
										   aria-describedby="newsletter-privacy-note">
									<button type="submit" class="newsletter-submit" aria-label="<?php esc_attr_e('Subscribe to Newsletter', 'preetidreams'); ?>">
										<span class="submit-icon" aria-hidden="true">→</span>
									</button>
								</div>

								<p id="newsletter-privacy-note" class="newsletter-privacy">
									<?php
									printf(
										esc_html__('By providing this information you agree to the %1$s and acknowledge that the collection and use of your data will be governed by the %2$s.', 'preetidreams'),
										'<a href="' . esc_url(home_url('/terms-conditions')) . '">' . esc_html__('Terms & Conditions', 'preetidreams') . '</a>',
										'<a href="' . esc_url(home_url('/privacy-policy')) . '">' . esc_html__('Privacy Policy', 'preetidreams') . '</a>'
									);
									?>
								</p>
							</form>
						</div>

					</div>
				</div>
			</div>

			<!-- Footer Bottom -->
			<div class="footer-bottom">
				<div class="container">
					<div class="footer-bottom-content">

						<!-- Copyright -->
						<div class="footer-copyright">
							<p>&copy; {CURRENT_YEAR} <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'preetidreams'); ?></p>
						</div>

						<!-- Legal Links -->
						<nav class="footer-legal-nav" role="navigation" aria-label="<?php esc_attr_e('Legal Links', 'preetidreams'); ?>">
							<ul class="footer-legal-list">
								<li><a href="<?php echo esc_url(home_url('/terms-conditions')); ?>"><?php esc_html_e('Terms & Conditions', 'preetidreams'); ?></a></li>
								<li><a href="<?php echo esc_url(home_url('/privacy-policy')); ?>"><?php esc_html_e('Privacy Policy', 'preetidreams'); ?></a></li>
								<li><a href="<?php echo esc_url(home_url('/hipaa-notice')); ?>"><?php esc_html_e('HIPAA Notice', 'preetidreams'); ?></a></li>
								<li><a href="<?php echo esc_url(home_url('/accessibility')); ?>"><?php esc_html_e('Accessibility Statement', 'preetidreams'); ?></a></li>
							</ul>
						</nav>

						<!-- Privacy Controls -->
						<div class="footer-privacy-controls">
							<button type="button" class="privacy-control-btn" id="cookie-preferences">
								<?php esc_html_e('Cookie Preferences', 'preetidreams'); ?>
							</button>
							<button type="button" class="privacy-control-btn" id="privacy-choices">
								<?php esc_html_e('Your Privacy Choices', 'preetidreams'); ?>
								<span class="privacy-icon" aria-hidden="true">🔒</span>
							</button>
						</div>

					</div>
				</div>
			</div>

		</div>
	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
