<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Skip Link for Accessibility -->
<a class="skip-link sr-only" href="#main"><?php esc_html_e('Skip to main content', 'preetidreams'); ?></a>

<!-- Header -->
<header class="site-header" role="banner">
    <div class="container">
        <div class="header-content">

            <!-- Site Branding -->
            <div class="site-branding">
                <?php if (has_custom_logo()) : ?>
                    <div class="site-logo">
                        <?php the_custom_logo(); ?>
                    </div>
                <?php endif; ?>

                <div class="site-identity">
                    <h1 class="site-title">
                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                            <?php bloginfo('name'); ?>
                        </a>
                    </h1>

                    <?php
                    $description = get_bloginfo('description', 'display');
                    if ($description || is_customize_preview()) : ?>
                        <p class="site-description"><?php echo $description; ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Medical Spa Contact Info -->
            <div class="header-contact">
                <?php
                $phone = preetidreams_get_phone();
                $address = preetidreams_get_address();
                $hours = preetidreams_get_hours();
                ?>

                <?php if ($phone) : ?>
                    <div class="contact-item">
                        <a href="tel:<?php echo esc_attr($phone); ?>" class="phone-link">
                            <span class="icon">📞</span>
                            <span class="text"><?php echo esc_html($phone); ?></span>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if ($hours) : ?>
                    <div class="contact-item">
                        <span class="icon">🕒</span>
                        <span class="text"><?php echo esc_html($hours); ?></span>
                    </div>
                <?php endif; ?>

                <div class="header-cta">
                    <a href="#consultation" class="consultation-btn">
                        <?php esc_html_e('Book Consultation', 'preetidreams'); ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <nav class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary menu', 'preetidreams'); ?>">
            <?php if (has_nav_menu('primary')) : ?>
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'menu_class'     => 'primary-menu',
                    'container'      => false,
                    'depth'          => 2,
                ]);
                ?>
            <?php else : ?>
                <!-- Fallback menu for setup -->
                <ul class="primary-menu">
                    <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'preetidreams'); ?></a></li>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('treatment')); ?>"><?php esc_html_e('Treatments', 'preetidreams'); ?></a></li>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('staff')); ?>"><?php esc_html_e('Our Team', 'preetidreams'); ?></a></li>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('testimonial')); ?>"><?php esc_html_e('Testimonials', 'preetidreams'); ?></a></li>
                    <li><a href="#contact"><?php esc_html_e('Contact', 'preetidreams'); ?></a></li>
                </ul>
            <?php endif; ?>

            <!-- Mobile Menu Toggle -->
            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                <span class="sr-only"><?php esc_html_e('Menu', 'preetidreams'); ?></span>
                <span class="menu-icon"></span>
            </button>
        </nav>
    </div>
</header>

<!-- Main Content Area -->
