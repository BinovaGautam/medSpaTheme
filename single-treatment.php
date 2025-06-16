<?php
/**
 * Template for displaying single treatment pages
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main treatment-page">
    <?php while (have_posts()) : the_post(); ?>

        <!-- Breadcrumb Navigation -->
        <nav class="breadcrumb-nav" aria-label="Breadcrumb">
            <div class="container">
                <ol class="breadcrumb-list">
                    <li class="breadcrumb-item">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="breadcrumb-link">
                            <?php esc_html_e('Home', 'medspatheme'); ?>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?php echo esc_url(get_post_type_archive_link('treatment')); ?>" class="breadcrumb-link">
                            <?php esc_html_e('Treatments', 'medspatheme'); ?>
                        </a>
                    </li>
                    <li class="breadcrumb-item breadcrumb-current" aria-current="page">
                        <?php the_title(); ?>
                    </li>
                </ol>
            </div>
        </nav>

        <!-- Hero Section -->
        <?php get_template_part('template-parts/treatment/hero-section-simple'); ?>

        <!-- Treatment Quick Info Bar -->
        <?php get_template_part('template-parts/treatment/info-bar-simple'); ?>

        <!-- Tabbed Content Navigation -->
        <section class="treatment-tabs-section">
            <div class="container">
                <nav class="treatment-tabs-nav" role="tablist" aria-label="Treatment Information">
                    <button class="tab-button active"
                            role="tab"
                            aria-selected="true"
                            aria-controls="overview-panel"
                            id="overview-tab"
                            data-tab="overview">
                        <?php esc_html_e('Overview', 'medspatheme'); ?>
                    </button>
                    <button class="tab-button"
                            role="tab"
                            aria-selected="false"
                            aria-controls="process-panel"
                            id="process-tab"
                            data-tab="process">
                        <?php esc_html_e('Process', 'medspatheme'); ?>
                    </button>
                    <button class="tab-button"
                            role="tab"
                            aria-selected="false"
                            aria-controls="results-panel"
                            id="results-tab"
                            data-tab="results">
                        <?php esc_html_e('Results', 'medspatheme'); ?>
                    </button>
                    <button class="tab-button"
                            role="tab"
                            aria-selected="false"
                            aria-controls="faqs-panel"
                            id="faqs-tab"
                            data-tab="faqs">
                        <?php esc_html_e('FAQs', 'medspatheme'); ?>
                    </button>
                    <?php if (get_field('show_pricing_section')) : ?>
                        <button class="tab-button"
                                role="tab"
                                aria-selected="false"
                                aria-controls="pricing-panel"
                                id="pricing-tab"
                                data-tab="pricing">
                            <?php esc_html_e('Pricing', 'medspatheme'); ?>
                        </button>
                    <?php endif; ?>
                    <button class="tab-button tab-button-cta"
                            role="tab"
                            aria-selected="false"
                            aria-controls="book-panel"
                            id="book-tab"
                            data-tab="book">
                        <?php esc_html_e('Book', 'medspatheme'); ?>
                    </button>
                </nav>

                <!-- Tab Content Panels -->
                <div class="treatment-tabs-content">

                    <!-- Overview Panel -->
                    <div class="tab-panel active"
                         role="tabpanel"
                         id="overview-panel"
                         aria-labelledby="overview-tab">
                        <?php get_template_part('template-parts/treatment/overview-content'); ?>
                    </div>

                    <!-- Process Panel -->
                    <div class="tab-panel"
                         role="tabpanel"
                         id="process-panel"
                         aria-labelledby="process-tab">
                        <?php get_template_part('template-parts/treatment/process-content'); ?>
                    </div>

                    <!-- Results Panel -->
                    <div class="tab-panel"
                         role="tabpanel"
                         id="results-panel"
                         aria-labelledby="results-tab">
                        <?php get_template_part('template-parts/treatment/results-content'); ?>
                    </div>

                    <!-- FAQs Panel -->
                    <div class="tab-panel"
                         role="tabpanel"
                         id="faqs-panel"
                         aria-labelledby="faqs-tab">
                        <?php get_template_part('template-parts/treatment/faqs-content'); ?>
                    </div>

                    <!-- Conditional Pricing Panel -->
                    <?php if (get_field('show_pricing_section')) : ?>
                        <div class="tab-panel"
                             role="tabpanel"
                             id="pricing-panel"
                             aria-labelledby="pricing-tab">
                            <?php get_template_part('template-parts/treatment/pricing-content'); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Book Panel -->
                    <div class="tab-panel"
                         role="tabpanel"
                         id="book-panel"
                         aria-labelledby="book-tab">
                        <?php get_template_part('template-parts/treatment/booking-content'); ?>
                    </div>

                </div>
            </div>
        </section>

        <!-- Related Treatments Section -->
        <?php get_template_part('template-parts/treatment/related-treatments'); ?>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
