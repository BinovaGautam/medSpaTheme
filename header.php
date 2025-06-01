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
<a class="skip-link sr-only sr-only-focusable" href="#main"><?php esc_html_e('Skip to main content', 'preetidreams'); ?></a>

<!-- Professional Header -->
<header class="site-header professional-header" role="banner">
    <div class="container">
        <div class="header-inner">

            <!-- Logo Section -->
            <div class="site-branding">
                <?php if (has_custom_logo()) : ?>
                    <div class="custom-logo">
                        <?php the_custom_logo(); ?>
                    </div>
                <?php else : ?>
                    <div class="logo-fallback">
                        <div class="logo-medical-cross">✚</div>
                        <div class="logo-text">
                            <h1 class="site-title">
                                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                    <?php bloginfo('name'); ?>
                                </a>
                            </h1>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Main Navigation -->
            <nav class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary navigation', 'preetidreams'); ?>">
                <?php if (has_nav_menu('primary')) : ?>
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'menu_class'     => 'nav-menu',
                        'container'      => false,
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ]);
                    ?>
                <?php else : ?>
                    <!-- Clean fallback menu -->
                    <ul class="nav-menu">
                        <li class="menu-item <?php echo is_home() || is_front_page() ? 'current-menu-item' : ''; ?>">
                            <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                        </li>
                        <li class="menu-item <?php echo is_post_type_archive('treatment') || is_singular('treatment') ? 'current-menu-item' : ''; ?>">
                            <a href="<?php echo esc_url(get_post_type_archive_link('treatment')); ?>">Treatments</a>
                        </li>
                        <li class="menu-item <?php echo is_post_type_archive('staff') || is_singular('staff') ? 'current-menu-item' : ''; ?>">
                            <a href="<?php echo esc_url(get_post_type_archive_link('staff')); ?>">Our Team</a>
                        </li>
                        <li class="menu-item <?php echo is_post_type_archive('testimonial') || is_singular('testimonial') ? 'current-menu-item' : ''; ?>">
                            <a href="<?php echo esc_url(get_post_type_archive_link('testimonial')); ?>">Testimonials</a>
                        </li>
                        <li class="menu-item">
                            <a href="#contact">Contact</a>
                        </li>
                    </ul>
                <?php endif; ?>
            </nav>

            <!-- Header Actions -->
            <div class="header-actions">
                <!-- Phone Number (Desktop Only) -->
                <?php
                $phone = preetidreams_get_phone();
                if ($phone) : ?>
                    <div class="header-phone desktop-only">
                        <a href="tel:<?php echo esc_attr($phone); ?>" class="phone-link">
                            <?php echo esc_html($phone); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Book Consultation Button -->
                <div class="header-cta">
                    <a href="#consultation" class="btn-consultation">
                        <span class="btn-icon">📋</span>
                        <span class="btn-text">Book Consultation</span>
                    </a>
                </div>

                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle"
                        type="button"
                        aria-controls="mobile-menu"
                        aria-expanded="false"
                        aria-label="<?php esc_attr_e('Toggle mobile menu', 'preetidreams'); ?>">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
            </div>

        </div>
    </div>
</header>

<!-- Mobile Menu -->
<div class="mobile-menu-overlay" aria-hidden="true"></div>
<nav class="mobile-menu" id="mobile-menu" aria-hidden="true" role="navigation">
    <div class="mobile-menu-header">
        <div class="mobile-logo">
            <div class="logo-medical-cross">✚</div>
            <span class="mobile-site-name"><?php bloginfo('name'); ?></span>
        </div>
        <button class="mobile-menu-close" aria-label="<?php esc_attr_e('Close menu', 'preetidreams'); ?>">
            ✕
        </button>
    </div>

    <div class="mobile-menu-content">
        <ul class="mobile-nav-list">
            <li class="<?php echo is_home() || is_front_page() ? 'current' : ''; ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <span class="nav-icon">🏠</span>
                    Home
                </a>
            </li>
            <li class="<?php echo is_post_type_archive('treatment') || is_singular('treatment') ? 'current' : ''; ?>">
                <a href="<?php echo esc_url(get_post_type_archive_link('treatment')); ?>">
                    <span class="nav-icon">💉</span>
                    Treatments
                </a>
            </li>
            <li class="<?php echo is_post_type_archive('staff') || is_singular('staff') ? 'current' : ''; ?>">
                <a href="<?php echo esc_url(get_post_type_archive_link('staff')); ?>">
                    <span class="nav-icon">👥</span>
                    Our Team
                </a>
            </li>
            <li class="<?php echo is_post_type_archive('testimonial') || is_singular('testimonial') ? 'current' : ''; ?>">
                <a href="<?php echo esc_url(get_post_type_archive_link('testimonial')); ?>">
                    <span class="nav-icon">💬</span>
                    Testimonials
                </a>
            </li>
            <li>
                <a href="#contact">
                    <span class="nav-icon">📞</span>
                    Contact
                </a>
            </li>
        </ul>

        <div class="mobile-menu-actions">
            <?php if ($phone) : ?>
                <a href="tel:<?php echo esc_attr($phone); ?>" class="mobile-phone-btn">
                    📞 Call <?php echo esc_html($phone); ?>
                </a>
            <?php endif; ?>
            <a href="#consultation" class="mobile-consultation-btn">
                📋 Book Free Consultation
            </a>
        </div>
    </div>
</nav>

<!-- Main Content -->
