<?php
/**
 * Elegant Quiz Component
 *
 * A reusable, configurable quiz component for treatment selection
 *
 * @package MedSpaTheme
 * @since 1.0.0
 *
 * @param array $args {
 *     Optional. Configuration arguments.
 *     @type string $title       Quiz main title. Default 'Find Your Perfect Treatment'.
 *     @type string $subtitle    Quiz subtitle. Default 'Answer a few questions...'.
 *     @type string $css_class   Additional CSS classes. Default ''.
 *     @type bool   $show_progress Show progress indicators. Default false.
 *     @type array  $treatments  Custom treatment options. Default null (uses built-in).
 * }
 */

// Set default arguments
$defaults = array(
    'title'        => __('Find Your Perfect Treatment', 'medspa-theme'),
    'subtitle'     => __('Answer a few questions to get personalized recommendations', 'medspa-theme'),
    'css_class'    => '',
    'show_progress' => false,
    'treatments'   => [
        array('value' => 'injectable-artistry', 'text' => __('Botox / Fillers', 'medspa-theme'), 'icon' => '��'),
        array('value' => 'facial-renaissance', 'text' => __('HydraFacial', 'medspa-theme'), 'icon' => '🌊'),
        array('value' => 'precision-dermaplanning', 'text' => __('Dermaplanning', 'medspa-theme'), 'icon' => '✨'),
        array('value' => 'regenerative-prp', 'text' => __('Microneedling PRP', 'medspa-theme'), 'icon' => '🩸'),
        array('value' => 'wellness-infusions', 'text' => __('IV Vitamins', 'medspa-theme'), 'icon' => '💊'),
        array('value' => 'artistry-enhancement', 'text' => __('Permanent Makeup', 'medspa-theme'), 'icon' => '💄'),
        array('value' => 'laser-precision', 'text' => __('Laser Hair Reduction', 'medspa-theme'), 'icon' => '🔥'),
        array('value' => 'carbon-rejuvenation', 'text' => __('Carbon Peel Laser', 'medspa-theme'), 'icon' => '🌟'),
        array('value' => 'body-sculpting', 'text' => __('EMSCULPT Muscle Builder', 'medspa-theme'), 'icon' => '💪'),
    ],
    'quiz_id'      => 'elegant-quiz-' . uniqid(),
);

$args = wp_parse_args($args ?? array(), $defaults);

// Generate secure nonce for this quiz instance
$quiz_nonce = wp_create_nonce('elegant_quiz_' . $args['quiz_id']);

// Component CSS classes
$component_classes = array(
    'elegant-quiz',
    'treatment-selection-interface'
);

if (!empty($args['css_class'])) {
    $component_classes[] = $args['css_class'];
}

$component_class_string = implode(' ', array_map('sanitize_html_class', $component_classes));
?>

<div class="<?php echo esc_attr($component_class_string); ?>"
     id="<?php echo esc_attr($args['quiz_id']); ?>"
     data-quiz-nonce="<?php echo esc_attr($quiz_nonce); ?>"
     role="application"
     aria-label="<?php esc_attr_e('Treatment Selection Quiz', 'medspa-theme'); ?>">

    <!-- Quiz Header -->
    <header class="quiz-header">
        <h3 class="quiz-main-title"><?php echo esc_html($args['title']); ?></h3>
        <p class="quiz-subtitle"><?php echo esc_html($args['subtitle']); ?></p>
    </header>

    <!-- Progress Indicator (if enabled) -->
    <?php if ($args['show_progress']) : ?>
    <div class="quiz-progress" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="5">
        <div class="progress-steps">
            <?php for ($i = 1; $i <= 5; $i++) : ?>
            <span class="progress-step <?php echo $i === 1 ? 'active' : ''; ?>"
                  aria-label="<?php printf(__('Step %d of 5', 'medspa-theme'), $i); ?>"></span>
            <?php endfor; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Quiz Steps Container -->
    <div class="quiz-steps-container" role="main">

        <!-- Step 1: Treatment Categories -->
        <section class="quiz-step active"
                 data-step="1"
                 id="quiz-step-1"
                 aria-live="polite">
            <h4 class="quiz-question"><?php esc_html_e('What are you interested in?', 'medspa-theme'); ?></h4>
            <div class="quiz-grid" role="group" aria-label="<?php esc_attr_e('Treatment options', 'medspa-theme'); ?>">
                <?php foreach ($args['treatments'] as $treatment) :
                    $is_wide = strlen($treatment['text']) > 20;
                    $pill_classes = array('quiz-pill');
                    if ($is_wide) $pill_classes[] = 'quiz-pill-wide';
                ?>
                <button class="<?php echo esc_attr(implode(' ', $pill_classes)); ?>"
                        data-category="<?php echo esc_attr($treatment['value']); ?>"
                        data-step="category"
                        type="button"
                        aria-describedby="quiz-step-1-desc">
                    <span class="quiz-icon" aria-hidden="true"><?php echo esc_html($treatment['icon']); ?></span>
                    <span class="quiz-pill-text"><?php echo esc_html($treatment['text']); ?></span>
                </button>
                <?php endforeach; ?>
            </div>
            <div id="quiz-step-1-desc" class="sr-only">
                <?php esc_html_e('Select the treatment you are most interested in to continue to the next step.', 'medspa-theme'); ?>
            </div>
        </section>

        <!-- Step 2: Specific Treatment Areas -->
        <section class="quiz-step"
                 data-step="2"
                 id="quiz-step-2"
                 aria-live="polite">
            <nav class="quiz-navigation">
                <button class="quiz-back-btn" type="button" aria-label="<?php esc_attr_e('Go back to previous step', 'medspa-theme'); ?>">
                    <span class="quiz-back-icon" aria-hidden="true">←</span>
                    <?php esc_html_e('Back', 'medspa-theme'); ?>
                </button>
            </nav>
            <h4 class="quiz-question"><?php esc_html_e('Where do you want treatment?', 'medspa-theme'); ?></h4>
            <div class="quiz-grid"
                 id="treatment-areas-grid"
                 role="group"
                 aria-label="<?php esc_attr_e('Treatment areas', 'medspa-theme'); ?>">
                <!-- Dynamically populated via JavaScript -->
            </div>
        </section>

        <!-- Step 3: Experience Level -->
        <section class="quiz-step"
                 data-step="3"
                 id="quiz-step-3"
                 aria-live="polite">
            <nav class="quiz-navigation">
                <button class="quiz-back-btn" type="button" aria-label="<?php esc_attr_e('Go back to previous step', 'medspa-theme'); ?>">
                    <span class="quiz-back-icon" aria-hidden="true">←</span>
                    <?php esc_html_e('Back', 'medspa-theme'); ?>
                </button>
            </nav>
            <h4 class="quiz-question"><?php esc_html_e('How many times have you had this treatment?', 'medspa-theme'); ?></h4>
            <div class="quiz-grid" role="group" aria-label="<?php esc_attr_e('Experience level', 'medspa-theme'); ?>">
                <?php
                $experience_options = array(
                    array('value' => '0', 'text' => __('0', 'medspa-theme')),
                    array('value' => '1-2', 'text' => __('1-2', 'medspa-theme')),
                    array('value' => '3-4', 'text' => __('3-4', 'medspa-theme')),
                    array('value' => '5-6', 'text' => __('5-6', 'medspa-theme')),
                    array('value' => '7-8', 'text' => __('7-8', 'medspa-theme')),
                    array('value' => '9+', 'text' => __('9+', 'medspa-theme')),
                );
                foreach ($experience_options as $option) : ?>
                <button class="quiz-pill"
                        data-experience="<?php echo esc_attr($option['value']); ?>"
                        data-step="experience"
                        type="button">
                    <span class="quiz-pill-text"><?php echo esc_html($option['text']); ?></span>
                </button>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Step 4: Demographics -->
        <section class="quiz-step"
                 data-step="4"
                 id="quiz-step-4"
                 aria-live="polite">
            <nav class="quiz-navigation">
                <button class="quiz-back-btn" type="button" aria-label="<?php esc_attr_e('Go back to previous step', 'medspa-theme'); ?>">
                    <span class="quiz-back-icon" aria-hidden="true">←</span>
                    <?php esc_html_e('Back', 'medspa-theme'); ?>
                </button>
            </nav>
            <h4 class="quiz-question"><?php esc_html_e('What age group are you in?', 'medspa-theme'); ?></h4>
            <div class="quiz-grid" role="group" aria-label="<?php esc_attr_e('Age group', 'medspa-theme'); ?>">
                <?php
                $age_options = array(
                    array('value' => 'under-18', 'text' => __('< 18', 'medspa-theme')),
                    array('value' => '18-24', 'text' => __('18-24', 'medspa-theme')),
                    array('value' => '25-34', 'text' => __('25-34', 'medspa-theme')),
                    array('value' => '35-44', 'text' => __('35-44', 'medspa-theme')),
                    array('value' => '45-54', 'text' => __('45-54', 'medspa-theme')),
                    array('value' => '55-64', 'text' => __('55-64', 'medspa-theme')),
                    array('value' => '65+', 'text' => __('65+', 'medspa-theme')),
                );
                foreach ($age_options as $option) : ?>
                <button class="quiz-pill"
                        data-age="<?php echo esc_attr($option['value']); ?>"
                        data-step="age"
                        type="button">
                    <span class="quiz-pill-text"><?php echo esc_html($option['text']); ?></span>
                </button>
                <?php endforeach; ?>
            </div>
            <div class="quiz-step-actions">
                <button class="quiz-continue-btn" type="button">
                    <?php esc_html_e('Next', 'medspa-theme'); ?>
                    <span class="quiz-continue-icon" aria-hidden="true">→</span>
                </button>
            </div>
        </section>

        <!-- Step 5: Contact Information -->
        <section class="quiz-step"
                 data-step="5"
                 id="quiz-step-5"
                 aria-live="polite">
            <nav class="quiz-navigation">
                <button class="quiz-back-btn" type="button" aria-label="<?php esc_attr_e('Go back to previous step', 'medspa-theme'); ?>">
                    <span class="quiz-back-icon" aria-hidden="true">←</span>
                    <?php esc_html_e('Back', 'medspa-theme'); ?>
                </button>
            </nav>
            <h4 class="quiz-question"><?php esc_html_e('What is your full name?', 'medspa-theme'); ?></h4>
            <form class="quiz-contact-form"
                  id="elegant-quiz-form"
                  method="post"
                  novalidate>

                <?php wp_nonce_field('elegant_quiz_' . $args['quiz_id'], 'quiz_nonce'); ?>
                <input type="hidden" name="action" value="submit_elegant_quiz">
                <input type="hidden" name="quiz_id" value="<?php echo esc_attr($args['quiz_id']); ?>">

                <div class="quiz-form-field" data-field="name">
                    <label for="quiz-name" class="sr-only"><?php esc_html_e('Full Name', 'medspa-theme'); ?></label>
                    <input type="text"
                           id="quiz-name"
                           name="full_name"
                           placeholder="<?php esc_attr_e('Enter your full name', 'medspa-theme'); ?>"
                           autocomplete="name"
                           required
                           aria-describedby="quiz-name-error">
                    <div id="quiz-name-error" class="field-error" role="alert" aria-live="polite"></div>
                </div>

                <div class="quiz-form-field quiz-form-field-hidden" data-field="email">
                    <label for="quiz-email" class="sr-only"><?php esc_html_e('Email Address', 'medspa-theme'); ?></label>
                    <input type="email"
                           id="quiz-email"
                           name="email"
                           placeholder="<?php esc_attr_e('Enter your email address', 'medspa-theme'); ?>"
                           autocomplete="email"
                           required
                           aria-describedby="quiz-email-error">
                    <div id="quiz-email-error" class="field-error" role="alert" aria-live="polite"></div>
                </div>

                <div class="quiz-form-field quiz-form-field-hidden" data-field="phone">
                    <label for="quiz-phone" class="sr-only"><?php esc_html_e('Phone Number', 'medspa-theme'); ?></label>
                    <input type="tel"
                           id="quiz-phone"
                           name="phone"
                           placeholder="<?php esc_attr_e('Enter your phone number', 'medspa-theme'); ?>"
                           autocomplete="tel"
                           required
                           aria-describedby="quiz-phone-error">
                    <div id="quiz-phone-error" class="field-error" role="alert" aria-live="polite"></div>
                </div>

                <div class="quiz-form-actions quiz-form-actions-hidden">
                    <button type="submit" class="quiz-submit-btn">
                        <span class="btn-text"><?php esc_html_e('Get My Personalized Plan', 'medspa-theme'); ?></span>
                        <span class="quiz-submit-icon" aria-hidden="true">📧</span>
                        <span class="loading-spinner" aria-hidden="true"></span>
                    </button>
                </div>

                <div class="quiz-error-message" role="alert" aria-live="assertive"></div>
            </form>
        </section>

        <!-- Success State -->
        <section class="quiz-step quiz-success"
                 data-step="success"
                 role="status"
                 aria-live="polite">
            <div class="quiz-success-content">
                <div class="quiz-success-icon" aria-hidden="true">✅</div>
                <h4 class="quiz-success-title"><?php esc_html_e('Thank You!', 'medspa-theme'); ?></h4>
                <p class="quiz-success-message">
                    <?php esc_html_e('We\'ve received your personalized treatment quiz. You\'ll receive a confirmation email shortly, and we\'ll contact you within 24 hours with customized recommendations based on your preferences.', 'medspa-theme'); ?>
                </p>
                <div class="quiz-success-actions">
                    <?php if (function_exists('preetidreams_get_phone')) : ?>
                    <a href="tel:<?php echo esc_attr(preetidreams_get_phone()); ?>"
                       class="btn btn-primary">
                        <span class="btn-icon" aria-hidden="true">📞</span>
                        <?php esc_html_e('Call Us Now', 'medspa-theme'); ?>
                    </a>
                    <?php endif; ?>
                    <button class="btn btn-outline" onclick="location.reload()">
                        <?php esc_html_e('Take Quiz Again', 'medspa-theme'); ?>
                    </button>
                </div>
            </div>
        </section>

    </div><!-- .quiz-steps-container -->

    <!-- Screen Reader Status Updates -->
    <div class="sr-only" role="status" aria-live="polite" id="quiz-status-<?php echo esc_attr($args['quiz_id']); ?>"></div>

</div><!-- .elegant-quiz -->

<?php
// Enqueue component-specific assets
wp_enqueue_style('elegant-quiz-component', get_template_directory_uri() . '/assets/css/components/elegant-quiz.css', array(), '1.0.0');
wp_enqueue_script('elegant-quiz-component', get_template_directory_uri() . '/assets/js/components/elegant-quiz.js', array('jquery'), '1.0.0', true);

// Localize script with configuration and nonce
wp_localize_script('elegant-quiz-component', 'elegantQuizConfig', array(
    'ajaxUrl' => admin_url('admin-ajax.php'),
    'nonce' => $quiz_nonce,
    'quizId' => $args['quiz_id'],
    'i18n' => array(
        'loading' => __('Loading...', 'medspa-theme'),
        'error' => __('An error occurred. Please try again.', 'medspa-theme'),
        'required' => __('This field is required.', 'medspa-theme'),
        'invalidEmail' => __('Please enter a valid email address.', 'medspa-theme'),
        'stepOf' => __('Step %1$d of %2$d', 'medspa-theme'),
    )
));
?>
