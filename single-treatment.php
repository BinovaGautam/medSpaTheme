<?php
/**
 * Template for displaying single treatment pages
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

get_header();

// Get treatment data
$treatment_id = get_the_ID();
$treatment_title = get_the_title();
$treatment_description = get_field('treatment_short_description') ?: wp_trim_words(get_the_excerpt(), 30);
$treatment_duration = get_field('treatment_duration') ?: '';
$treatment_type = get_field('treatment_type') ?: 'Cosmetic Treatment';
$treatment_benefits = get_field('treatment_benefits') ?: [];
$treatment_process = get_field('treatment_process') ?: [];
$treatment_faqs = get_field('treatment_faqs') ?: [];

// SEO and Schema Data
$schema_data = [
    '@context' => 'https://schema.org',
    '@type' => 'MedicalProcedure',
    'name' => $treatment_title,
    'description' => $treatment_description,
    'procedureType' => $treatment_type,
    'bodyLocation' => get_field('treatment_body_location') ?: '',
    'preparation' => get_field('treatment_preparation') ?: '',
    'followup' => get_field('treatment_aftercare') ?: '',
    'howPerformed' => get_field('treatment_how_performed') ?: '',
    'provider' => [
        '@type' => 'MedicalOrganization',
        'name' => get_bloginfo('name'),
        'url' => home_url(),
        'telephone' => get_theme_mod('contact_phone', ''),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => get_theme_mod('contact_address', ''),
            'addressLocality' => get_theme_mod('contact_city', ''),
            'addressRegion' => get_theme_mod('contact_state', ''),
            'postalCode' => get_theme_mod('contact_zip', ''),
            'addressCountry' => 'US'
        ]
    ]
];

// Add duration if available
if ($treatment_duration) {
    $schema_data['duration'] = $treatment_duration;
}

// Add benefits as medical indications
if (!empty($treatment_benefits)) {
    $schema_data['indication'] = $treatment_benefits;
}

// Add FAQ schema if available
if (!empty($treatment_faqs)) {
    $faq_schema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => []
    ];

    foreach ($treatment_faqs as $faq) {
        if (isset($faq['question']) && isset($faq['answer'])) {
            $faq_schema['mainEntity'][] = [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer']
                ]
            ];
        }
    }
}
?>

<!-- Structured Data -->
<script type="application/ld+json">
<?php echo json_encode($schema_data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
</script>

<?php if (isset($faq_schema)): ?>
<script type="application/ld+json">
<?php echo json_encode($faq_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
</script>
<?php endif; ?>

<!-- Open Graph Meta Tags -->
<meta property="og:title" content="<?php echo esc_attr($treatment_title . ' - ' . get_bloginfo('name')); ?>">
<meta property="og:description" content="<?php echo esc_attr($treatment_description); ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>">
<meta property="og:site_name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">

<?php if (has_post_thumbnail()): ?>
<meta property="og:image" content="<?php echo esc_url(get_the_post_thumbnail_url($treatment_id, 'large')); ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="<?php echo esc_attr($treatment_title . ' treatment'); ?>">
<?php endif; ?>

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo esc_attr($treatment_title . ' - ' . get_bloginfo('name')); ?>">
<meta name="twitter:description" content="<?php echo esc_attr($treatment_description); ?>">
<?php if (has_post_thumbnail()): ?>
<meta name="twitter:image" content="<?php echo esc_url(get_the_post_thumbnail_url($treatment_id, 'large')); ?>">
<meta name="twitter:image:alt" content="<?php echo esc_attr($treatment_title . ' treatment'); ?>">
<?php endif; ?>

<!-- Additional SEO Meta Tags -->
<meta name="description" content="<?php echo esc_attr($treatment_description); ?>">
<meta name="keywords" content="<?php echo esc_attr($treatment_type . ', ' . implode(', ', array_slice($treatment_benefits, 0, 5))); ?>">
<link rel="canonical" href="<?php echo esc_url(get_permalink()); ?>">

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
