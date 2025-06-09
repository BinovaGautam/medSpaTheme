<?php
/**
 * Footer Component Base Class
 *
 * Extends BaseComponent with footer-specific functionality including
 * medical spa contact information, social media integration, navigation, and customization.
 *
 * @package MedSpaTheme
 * @since 1.0.0
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * FooterComponent Class
 *
 * Base class for footer components with medical spa specializations,
 * contact information management, and responsive design.
 */
class FooterComponent extends BaseComponent {

    /**
     * Available footer types
     * @var array
     */
    protected $footer_types = ['basic', 'luxury', 'medical', 'consultation', 'minimal'];

    /**
     * Available footer layouts
     * @var array
     */
    protected $layout_types = ['columns', 'centered', 'split', 'stacked'];

    /**
     * Footer sections configuration
     * @var array
     */
    protected $footer_sections = [
        'contact' => true,
        'navigation' => true,
        'social' => true,
        'credentials' => true,
        'consultation_cta' => true,
        'copyright' => true,
        'back_to_top' => true
    ];

    /**
     * Medical spa contact information
     * @var array
     */
    protected $contact_info = [];

    /**
     * Social media platforms
     * @var array
     */
    protected $social_platforms = [
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'linkedin' => 'LinkedIn',
        'youtube' => 'YouTube',
        'twitter' => 'Twitter'
    ];

    /**
     * Render the footer component
     *
     * @param array $args Component arguments
     * @return string HTML output
     */
    public function render($args = []) {
        $config = wp_parse_args($args, $this->config);

        // Validate and sanitize inputs
        $config = $this->sanitize_config($config);

        // Load contact information
        $this->load_contact_information();

        // Generate footer content
        $footer_content = $this->generate_footer_content($config);

        // Add structured data
        $structured_data = $this->generate_structured_data();

        return $footer_content . $structured_data;
    }

    /**
     * Get WordPress Customizer controls for footer
     *
     * @return array Customizer controls configuration
     */
    public function get_customizer_controls() {
        return [
            // Footer Layout & Type
            'footer_type' => [
                'type' => 'select',
                'label' => 'Footer Type',
                'description' => 'Choose the footer design style',
                'default' => 'luxury',
                'choices' => [
                    'basic' => 'Basic Footer',
                    'luxury' => 'Luxury Medical Spa',
                    'medical' => 'Medical Professional',
                    'consultation' => 'Consultation Focused',
                    'minimal' => 'Minimal Design'
                ],
                'sanitize_callback' => 'sanitize_text_field'
            ],

            'footer_layout' => [
                'type' => 'select',
                'label' => 'Footer Layout',
                'description' => 'Choose the footer layout structure',
                'default' => 'columns',
                'choices' => [
                    'columns' => 'Multi-Column Layout',
                    'centered' => 'Centered Layout',
                    'split' => 'Split Layout',
                    'stacked' => 'Stacked Layout'
                ],
                'sanitize_callback' => 'sanitize_text_field'
            ],

            // Footer Background & Colors
            'footer_background_color' => [
                'type' => 'color',
                'label' => 'Footer Background Color',
                'description' => 'Primary background color for footer',
                'default' => '#1B365D',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            'footer_text_color' => [
                'type' => 'color',
                'label' => 'Footer Text Color',
                'description' => 'Primary text color for footer content',
                'default' => '#FDFCFA',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            'footer_accent_color' => [
                'type' => 'color',
                'label' => 'Footer Accent Color',
                'description' => 'Accent color for links and highlights',
                'default' => '#D4AF37',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            // Consultation CTA Section
            'consultation_cta_enabled' => [
                'type' => 'checkbox',
                'label' => 'Enable Consultation CTA',
                'description' => 'Show consultation call-to-action section',
                'default' => true
            ],

            'consultation_headline' => [
                'type' => 'text',
                'label' => 'Consultation Headline',
                'description' => 'Main headline for consultation section',
                'default' => 'Ready to Begin Your Aesthetic Journey?',
                'sanitize_callback' => 'sanitize_text_field'
            ],

            'consultation_subtext' => [
                'type' => 'textarea',
                'label' => 'Consultation Subtext',
                'description' => 'Supporting text for consultation section',
                'default' => 'Experience the artistry of medical aesthetics with personalized treatments in our luxury clinic.',
                'sanitize_callback' => 'sanitize_textarea_field'
            ],

            'consultation_button_text' => [
                'type' => 'text',
                'label' => 'Primary CTA Button Text',
                'description' => 'Text for primary consultation button',
                'default' => 'Schedule Your Consultation',
                'sanitize_callback' => 'sanitize_text_field'
            ],

            'consultation_button_url' => [
                'type' => 'url',
                'label' => 'Primary CTA Button URL',
                'description' => 'Link for primary consultation button',
                'default' => '/contact/',
                'sanitize_callback' => 'esc_url_raw'
            ],

            'phone_button_text' => [
                'type' => 'text',
                'label' => 'Phone CTA Button Text',
                'description' => 'Text for phone call button',
                'default' => 'Call Our Practice',
                'sanitize_callback' => 'sanitize_text_field'
            ],

            // Contact Information
            'contact_section_enabled' => [
                'type' => 'checkbox',
                'label' => 'Enable Contact Section',
                'description' => 'Show contact information section',
                'default' => true
            ],

            'contact_section_title' => [
                'type' => 'text',
                'label' => 'Contact Section Title',
                'description' => 'Title for contact information section',
                'default' => 'Visit Our Clinic',
                'sanitize_callback' => 'sanitize_text_field'
            ],

            // Medical Credentials
            'credentials_section_enabled' => [
                'type' => 'checkbox',
                'label' => 'Enable Credentials Section',
                'description' => 'Show medical credentials and experience',
                'default' => true
            ],

            'credentials_section_title' => [
                'type' => 'text',
                'label' => 'Credentials Section Title',
                'description' => 'Title for credentials section',
                'default' => 'Medical Excellence',
                'sanitize_callback' => 'sanitize_text_field'
            ],

            'board_certification' => [
                'type' => 'text',
                'label' => 'Board Certification',
                'description' => 'Board certification information',
                'default' => 'Board-Certified Physician',
                'sanitize_callback' => 'sanitize_text_field'
            ],

            'certification_details' => [
                'type' => 'text',
                'label' => 'Certification Details',
                'description' => 'Additional certification details',
                'default' => 'American Board of Dermatology',
                'sanitize_callback' => 'sanitize_text_field'
            ],

            'experience_years' => [
                'type' => 'text',
                'label' => 'Years of Experience',
                'description' => 'Professional experience information',
                'default' => '15+ Years of Excellence',
                'sanitize_callback' => 'sanitize_text_field'
            ],

            'expertise_area' => [
                'type' => 'text',
                'label' => 'Area of Expertise',
                'description' => 'Specialization area',
                'default' => 'Aesthetic Medicine Expertise',
                'sanitize_callback' => 'sanitize_text_field'
            ],

            // Social Media
            'social_section_enabled' => [
                'type' => 'checkbox',
                'label' => 'Enable Social Media Section',
                'description' => 'Show social media links',
                'default' => true
            ],

            'social_section_title' => [
                'type' => 'text',
                'label' => 'Social Section Title',
                'description' => 'Title for social media section',
                'default' => 'Connect With Us',
                'sanitize_callback' => 'sanitize_text_field'
            ],

            // Navigation
            'footer_navigation_enabled' => [
                'type' => 'checkbox',
                'label' => 'Enable Footer Navigation',
                'description' => 'Show navigation menu in footer',
                'default' => true
            ],

            // Copyright
            'copyright_text' => [
                'type' => 'text',
                'label' => 'Copyright Text',
                'description' => 'Custom copyright text (optional)',
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field'
            ],

            // Back to Top
            'back_to_top_enabled' => [
                'type' => 'checkbox',
                'label' => 'Enable Back to Top Button',
                'description' => 'Show back to top button',
                'default' => true
            ],

            'back_to_top_text' => [
                'type' => 'text',
                'label' => 'Back to Top Text',
                'description' => 'Text for back to top button',
                'default' => '↑',
                'sanitize_callback' => 'sanitize_text_field'
            ]
        ];
    }

    /**
     * Get default design tokens for footer
     *
     * @return array Design tokens
     */
    public function get_default_tokens() {
        return [
            // Footer Background & Spacing
            'footer_background' => 'var(--color-navy-primary, #1B365D)',
            'footer_background_gradient' => 'linear-gradient(135deg, var(--color-navy-primary, #1B365D) 0%, var(--color-navy-dark, #152B4F) 100%)',
            'footer_padding' => 'var(--spacing-12, 48px) var(--spacing-6, 24px)',
            'footer_margin' => '0',
            'footer_border_top' => '1px solid rgba(212, 175, 55, 0.2)',

            // Footer Typography
            'footer_text_color' => 'var(--color-cream-base, #FDFCFA)',
            'footer_text_size' => 'var(--font-size-sm, 14px)',
            'footer_text_line_height' => 'var(--line-height-relaxed, 1.6)',
            'footer_link_color' => 'var(--color-sage-enhanced, #98BA7F)',
            'footer_link_hover_color' => 'var(--color-gold-enhanced, #E0B83E)',

            // Section Titles
            'section_title_color' => 'var(--color-cream-base, #FDFCFA)',
            'section_title_size' => 'var(--font-size-lg, 18px)',
            'section_title_weight' => 'var(--font-weight-semibold, 600)',
            'section_title_margin' => '0 0 var(--spacing-4, 16px) 0',

            // Consultation CTA Section
            'cta_background' => 'linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(135, 169, 107, 0.05) 100%)',
            'cta_padding' => 'var(--spacing-12, 48px) var(--spacing-6, 24px)',
            'cta_border_bottom' => '1px solid rgba(212, 175, 55, 0.2)',
            'cta_headline_size' => 'var(--font-size-3xl, 30px)',
            'cta_headline_weight' => 'var(--font-weight-semibold, 600)',
            'cta_subtext_size' => 'var(--font-size-base, 16px)',
            'cta_subtext_opacity' => '0.8',

            // CTA Buttons
            'cta_button_primary_bg' => 'linear-gradient(135deg, var(--color-gold-primary, #D4AF37) 0%, #B8941F 100%)',
            'cta_button_primary_color' => 'var(--color-navy-primary, #1B365D)',
            'cta_button_primary_padding' => 'var(--spacing-5, 20px) var(--spacing-8, 32px)',
            'cta_button_primary_radius' => 'var(--border-radius-lg, 12px)',
            'cta_button_primary_shadow' => '0 8px 25px rgba(212, 175, 55, 0.25)',

            'cta_button_secondary_bg' => 'transparent',
            'cta_button_secondary_color' => 'var(--color-sage-enhanced, #98BA7F)',
            'cta_button_secondary_border' => '2px solid var(--color-sage-enhanced, #98BA7F)',
            'cta_button_secondary_padding' => 'var(--spacing-4, 16px) var(--spacing-7, 28px)',

            // Footer Columns
            'column_gap' => 'var(--spacing-8, 32px)',
            'column_padding' => 'var(--spacing-6, 24px) 0',

            // Contact Information
            'contact_item_margin' => '0 0 var(--spacing-4, 16px) 0',
            'contact_icon_size' => 'var(--font-size-lg, 18px)',
            'contact_icon_color' => 'var(--color-sage-enhanced, #98BA7F)',

            // Social Media
            'social_link_padding' => 'var(--spacing-2, 8px) 0',
            'social_link_gap' => 'var(--spacing-2, 8px)',
            'social_icon_size' => 'var(--font-size-base, 16px)',

            // Footer Navigation
            'nav_link_padding' => 'var(--spacing-2, 8px)',
            'nav_link_margin' => '0 var(--spacing-4, 16px) 0 0',
            'nav_gap' => 'var(--spacing-4, 16px)',

            // Copyright Section
            'copyright_padding' => 'var(--spacing-6, 24px) 0',
            'copyright_border_top' => '1px solid rgba(212, 175, 55, 0.2)',
            'copyright_text_size' => 'var(--font-size-sm, 14px)',
            'copyright_text_opacity' => '0.6',

            // Back to Top Button
            'back_to_top_size' => '56px',
            'back_to_top_bg' => 'var(--color-gold-primary, #D4AF37)',
            'back_to_top_color' => 'var(--color-navy-primary, #1B365D)',
            'back_to_top_radius' => '50%',
            'back_to_top_shadow' => '0 4px 20px rgba(27, 54, 93, 0.08)',
            'back_to_top_position' => 'absolute',
            'back_to_top_top' => '-28px',
            'back_to_top_right' => 'var(--spacing-6, 24px)',

            // Responsive Design
            'mobile_padding' => 'var(--spacing-8, 32px) var(--spacing-4, 16px)',
            'mobile_column_gap' => 'var(--spacing-6, 24px)',
            'tablet_max_width' => '768px',
            'desktop_max_width' => '1200px'
        ];
    }

    /**
     * Get component default configuration
     *
     * @return array Default configuration
     */
    public function get_defaults() {
        return [
            // Footer Type & Layout
            'footer_type' => 'luxury',
            'footer_layout' => 'columns',
            'footer_class' => 'footer-component',

            // Footer Sections
            'enable_consultation_cta' => true,
            'enable_contact_section' => true,
            'enable_credentials_section' => true,
            'enable_social_section' => true,
            'enable_footer_navigation' => true,
            'enable_copyright' => true,
            'enable_back_to_top' => true,

            // Content Configuration
            'consultation_headline' => 'Ready to Begin Your Aesthetic Journey?',
            'consultation_subtext' => 'Experience the artistry of medical aesthetics with personalized treatments in our luxury clinic.',
            'consultation_button_text' => 'Schedule Your Consultation',
            'consultation_button_url' => '/contact/',
            'phone_button_text' => 'Call Our Practice',

            // Section Titles
            'contact_section_title' => 'Visit Our Clinic',
            'credentials_section_title' => 'Medical Excellence',
            'social_section_title' => 'Connect With Us',

            // Credentials
            'board_certification' => 'Board-Certified Physician',
            'certification_details' => 'American Board of Dermatology',
            'experience_years' => '15+ Years of Excellence',
            'expertise_area' => 'Aesthetic Medicine Expertise',

            // Back to Top
            'back_to_top_text' => '↑',

            // Accessibility
            'aria_label' => 'Footer',
            'role' => 'contentinfo',

            // Custom attributes
            'custom_attributes' => []
        ];
    }

    /**
     * Load contact information from theme settings
     */
    protected function load_contact_information() {
        $this->contact_info = [
            'phone' => preetidreams_get_phone(),
            'email' => preetidreams_get_email(),
            'address' => preetidreams_get_address(),
            'hours' => preetidreams_get_hours()
        ];
    }

    /**
     * Generate footer content based on configuration
     *
     * @param array $config Footer configuration
     * @return string Footer HTML
     */
    protected function generate_footer_content($config) {
        $footer_classes = $this->get_footer_classes($config);
        $footer_attributes = $this->generate_footer_attributes($config);

        ob_start();
        ?>
        <footer <?php echo $footer_attributes; ?>>
            <?php if ($config['enable_consultation_cta']): ?>
                <?php echo $this->render_consultation_cta_section($config); ?>
            <?php endif; ?>

            <?php if ($this->has_main_footer_content($config)): ?>
                <section class="footer-main">
                    <div class="footer-container">
                        <div class="footer-grid">
                            <?php echo $this->render_footer_columns($config); ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($config['enable_footer_navigation'] || $config['enable_copyright']): ?>
                <section class="footer-bottom">
                    <div class="footer-container">
                        <div class="footer-bottom-content">
                            <?php if ($config['enable_footer_navigation']): ?>
                                <?php echo $this->render_footer_navigation(); ?>
                            <?php endif; ?>

                            <?php if ($config['enable_copyright']): ?>
                                <?php echo $this->render_copyright_section($config); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($config['enable_back_to_top']): ?>
                <?php echo $this->render_back_to_top_button($config); ?>
            <?php endif; ?>
        </footer>
        <?php
        return ob_get_clean();
    }

    /**
     * Render consultation CTA section
     *
     * @param array $config Configuration
     * @return string HTML
     */
    protected function render_consultation_cta_section($config) {
        $phone = $this->contact_info['phone'];

        ob_start();
        ?>
        <section class="consultation-invitation" aria-labelledby="consultation-headline">
            <div class="footer-container">
                <div class="invitation-content">
                    <h2 id="consultation-headline" class="invitation-headline">
                        <?php echo esc_html(get_theme_mod('consultation_headline', $config['consultation_headline'])); ?>
                    </h2>
                    <p class="invitation-subtext">
                        <?php echo esc_html(get_theme_mod('consultation_subtext', $config['consultation_subtext'])); ?>
                    </p>

                    <div class="cta-group" role="group" aria-label="Consultation booking options">
                        <a href="<?php echo esc_url(get_theme_mod('consultation_button_url', $config['consultation_button_url'])); ?>"
                           class="cta-primary"
                           aria-describedby="cta-primary-desc">
                            <span class="cta-icon" aria-hidden="true">📅</span>
                            <span><?php echo esc_html(get_theme_mod('consultation_button_text', $config['consultation_button_text'])); ?></span>
                        </a>
                        <div id="cta-primary-desc" class="sr-only">Opens consultation booking page</div>

                        <?php if ($phone): ?>
                        <a href="tel:<?php echo esc_attr($phone); ?>"
                           class="cta-secondary"
                           aria-describedby="cta-secondary-desc">
                            <span class="cta-icon" aria-hidden="true">📞</span>
                            <span><?php echo esc_html(get_theme_mod('phone_button_text', $config['phone_button_text'])); ?></span>
                        </a>
                        <div id="cta-secondary-desc" class="sr-only">Call <?php echo esc_html($phone); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Check if main footer content should be displayed
     */
    protected function has_main_footer_content($config) {
        return $config['enable_contact_section'] ||
               $config['enable_credentials_section'] ||
               $config['enable_social_section'];
    }

    /**
     * Render footer columns based on layout
     *
     * @param array $config Configuration
     * @return string HTML
     */
    protected function render_footer_columns($config) {
        $columns = [];

        if ($config['enable_contact_section']) {
            $columns['contact'] = $this->render_contact_column($config);
        }

        if ($config['enable_credentials_section']) {
            $columns['credentials'] = $this->render_credentials_column($config);
        }

        if ($config['enable_social_section']) {
            $columns['social'] = $this->render_social_column($config);
        }

        $layout = $config['footer_layout'];
        return $this->arrange_columns_by_layout($columns, $layout);
    }

    /**
     * Render contact information column
     */
    protected function render_contact_column($config) {
        $contact = $this->contact_info;

        ob_start();
        ?>
        <div class="footer-column contact-column">
            <h3 class="column-title">
                <?php echo esc_html(get_theme_mod('contact_section_title', $config['contact_section_title'])); ?>
            </h3>
            <div class="contact-info">
                <?php if ($contact['address']): ?>
                <div class="contact-item">
                    <span class="contact-icon" aria-hidden="true">📍</span>
                    <div class="contact-details">
                        <strong>Address</strong>
                        <span><?php echo wp_kses_post(nl2br(esc_html($contact['address']))); ?></span>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($contact['phone']): ?>
                <div class="contact-item">
                    <span class="contact-icon" aria-hidden="true">📞</span>
                    <div class="contact-details">
                        <strong>Phone</strong>
                        <a href="tel:<?php echo esc_attr($contact['phone']); ?>" class="contact-link">
                            <?php echo esc_html($contact['phone']); ?>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($contact['email']): ?>
                <div class="contact-item">
                    <span class="contact-icon" aria-hidden="true">✉️</span>
                    <div class="contact-details">
                        <strong>Email</strong>
                        <a href="mailto:<?php echo esc_attr($contact['email']); ?>" class="contact-link">
                            <?php echo esc_html($contact['email']); ?>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($contact['hours']): ?>
                <div class="contact-item">
                    <span class="contact-icon" aria-hidden="true">🕒</span>
                    <div class="contact-details">
                        <strong>Hours</strong>
                        <span><?php echo wp_kses_post(nl2br(esc_html($contact['hours']))); ?></span>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render credentials column
     */
    protected function render_credentials_column($config) {
        ob_start();
        ?>
        <div class="footer-column credentials-column">
            <h3 class="column-title">
                <?php echo esc_html(get_theme_mod('credentials_section_title', $config['credentials_section_title'])); ?>
            </h3>
            <div class="credentials-list">
                <div class="credential-item">
                    <span class="credential-icon" aria-hidden="true">🏥</span>
                    <div class="credential-details">
                        <strong><?php echo esc_html(get_theme_mod('board_certification', $config['board_certification'])); ?></strong>
                        <span><?php echo esc_html(get_theme_mod('certification_details', $config['certification_details'])); ?></span>
                    </div>
                </div>

                <div class="credential-item">
                    <span class="credential-icon" aria-hidden="true">⭐</span>
                    <div class="credential-details">
                        <strong><?php echo esc_html(get_theme_mod('experience_years', $config['experience_years'])); ?></strong>
                        <span><?php echo esc_html(get_theme_mod('expertise_area', $config['expertise_area'])); ?></span>
                    </div>
                </div>

                <?php if (get_theme_mod('recognition_title')): ?>
                <div class="credential-item">
                    <span class="credential-icon" aria-hidden="true">🏆</span>
                    <div class="credential-details">
                        <strong><?php echo esc_html(get_theme_mod('recognition_title', 'Recognition & Awards')); ?></strong>
                        <span><?php echo esc_html(get_theme_mod('recognition_details', 'Top Medical Spa')); ?></span>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render social media column
     */
    protected function render_social_column($config) {
        ob_start();
        ?>
        <div class="footer-column social-column">
            <h3 class="column-title">
                <?php echo esc_html(get_theme_mod('social_section_title', $config['social_section_title'])); ?>
            </h3>
            <div class="social-links">
                <?php foreach ($this->social_platforms as $platform => $label): ?>
                    <?php $social_url = preetidreams_get_social_link($platform); ?>
                    <?php if ($social_url): ?>
                    <a href="<?php echo esc_url($social_url); ?>"
                       class="social-link"
                       aria-label="<?php echo esc_attr(sprintf('%s profile', $label)); ?>"
                       target="_blank"
                       rel="noopener noreferrer">
                        <span class="social-icon" aria-hidden="true"><?php echo $this->get_social_icon($platform); ?></span>
                        <span><?php echo esc_html($label); ?></span>
                    </a>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if (get_theme_mod('educational_resources_link')): ?>
                <a href="<?php echo esc_url(get_theme_mod('educational_resources_link')); ?>"
                   class="social-link"
                   aria-label="Educational content and resources">
                    <span class="social-icon" aria-hidden="true">📚</span>
                    <span>Educational Resources</span>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Arrange columns based on layout type
     */
    protected function arrange_columns_by_layout($columns, $layout) {
        $column_count = count($columns);
        $grid_class = "footer-grid-{$column_count}";

        switch ($layout) {
            case 'centered':
                $grid_class .= ' footer-layout-centered';
                break;
            case 'split':
                $grid_class .= ' footer-layout-split';
                break;
            case 'stacked':
                $grid_class .= ' footer-layout-stacked';
                break;
            default:
                $grid_class .= ' footer-layout-columns';
        }

        return '<div class="' . esc_attr($grid_class) . '">' . implode('', $columns) . '</div>';
    }

    /**
     * Render footer navigation
     */
    protected function render_footer_navigation() {
        ob_start();
        ?>
        <nav class="footer-nav" role="navigation" aria-label="Footer navigation">
            <?php if (has_nav_menu('footer')): ?>
                <?php wp_nav_menu([
                    'theme_location' => 'footer',
                    'menu_class' => 'footer-menu',
                    'container' => false,
                    'depth' => 1,
                ]); ?>
            <?php else: ?>
                <ul class="footer-menu">
                    <li><a href="<?php echo esc_url(home_url('/treatments/')); ?>">Treatments</a></li>
                    <li><a href="<?php echo esc_url(home_url('/about/')); ?>">About</a></li>
                    <li><a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a></li>
                    <li><a href="<?php echo esc_url(get_privacy_policy_url()); ?>">Privacy</a></li>
                    <li><a href="<?php echo esc_url(home_url('/terms/')); ?>">Terms</a></li>
                </ul>
            <?php endif; ?>
        </nav>
        <?php
        return ob_get_clean();
    }

    /**
     * Render copyright section
     */
    protected function render_copyright_section($config) {
        $copyright_text = get_theme_mod('copyright_text', '');
        $site_name = get_bloginfo('name');
        $current_year = date('Y');

        if (empty($copyright_text)) {
            $copyright_text = "Medical Spa. All rights reserved.";
        }

        ob_start();
        ?>
        <div class="site-info">
            <p>
                &copy; <?php echo esc_html($current_year); ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="brand-link">
                    <?php echo esc_html($site_name); ?>
                </a>
                <?php echo esc_html($copyright_text); ?>
            </p>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render back to top button
     */
    protected function render_back_to_top_button($config) {
        ob_start();
        ?>
        <button class="back-to-top"
                aria-label="Back to top"
                onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
            <?php echo esc_html(get_theme_mod('back_to_top_text', $config['back_to_top_text'])); ?>
        </button>
        <?php
        return ob_get_clean();
    }

    /**
     * Get social media icon
     */
    protected function get_social_icon($platform) {
        $icons = [
            'facebook' => '📘',
            'instagram' => '📷',
            'linkedin' => '💼',
            'youtube' => '📺',
            'twitter' => '🐦'
        ];

        return $icons[$platform] ?? '🔗';
    }

    /**
     * Generate footer attributes
     */
    protected function generate_footer_attributes($config) {
        $attributes = [
            'class' => $this->get_footer_classes($config),
            'role' => $config['role']
        ];

        if (!empty($config['aria_label'])) {
            $attributes['aria-label'] = $config['aria_label'];
        }

        // Add custom attributes
        if (!empty($config['custom_attributes'])) {
            $attributes = array_merge($attributes, $config['custom_attributes']);
        }

        $attributes_string = '';
        foreach ($attributes as $key => $value) {
            $attributes_string .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
        }

        return $attributes_string;
    }

    /**
     * Get footer CSS classes
     */
    protected function get_footer_classes($config) {
        $classes = ['footer-component'];

        // Add type class
        $classes[] = 'footer-' . $config['footer_type'];

        // Add layout class
        $classes[] = 'footer-layout-' . $config['footer_layout'];

        // Add custom class
        if (!empty($config['footer_class'])) {
            $classes[] = $config['footer_class'];
        }

        return implode(' ', $classes);
    }

    /**
     * Generate structured data for organization
     */
    protected function generate_structured_data() {
        $contact = $this->contact_info;
        $site_name = get_bloginfo('name');
        $site_url = home_url('/');

        $organization = [
            '@context' => 'https://schema.org',
            '@type' => 'MedicalBusiness',
            'name' => $site_name,
            'url' => $site_url,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $contact['address'] ?? '',
            ],
            'telephone' => $contact['phone'] ?? '',
            'email' => $contact['email'] ?? '',
            'openingHours' => $this->format_opening_hours($contact['hours'] ?? ''),
            'medicalSpecialty' => 'Aesthetic Medicine'
        ];

        // Add social media profiles
        $social_profiles = [];
        foreach ($this->social_platforms as $platform => $label) {
            $url = preetidreams_get_social_link($platform);
            if ($url) {
                $social_profiles[] = $url;
            }
        }

        if (!empty($social_profiles)) {
            $organization['sameAs'] = $social_profiles;
        }

        ob_start();
        ?>
        <script type="application/ld+json">
        <?php echo wp_json_encode($organization, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
        </script>
        <?php
        return ob_get_clean();
    }

    /**
     * Format opening hours for structured data
     */
    protected function format_opening_hours($hours) {
        // This is a simplified implementation
        // In a real scenario, you would parse the hours format
        return !empty($hours) ? ['Mo-Fr 09:00-17:00'] : [];
    }

    /**
     * Sanitize footer configuration
     */
    protected function sanitize_config($config) {
        $config = parent::sanitize_config($config);

        // Sanitize footer-specific fields
        $config['footer_type'] = in_array($config['footer_type'], $this->footer_types)
            ? $config['footer_type'] : 'luxury';
        $config['footer_layout'] = in_array($config['footer_layout'], $this->layout_types)
            ? $config['footer_layout'] : 'columns';

        // Sanitize URLs
        $config['consultation_button_url'] = esc_url_raw($config['consultation_button_url']);

        // Sanitize text fields
        $config['consultation_headline'] = sanitize_text_field($config['consultation_headline']);
        $config['consultation_subtext'] = sanitize_textarea_field($config['consultation_subtext']);
        $config['consultation_button_text'] = sanitize_text_field($config['consultation_button_text']);
        $config['phone_button_text'] = sanitize_text_field($config['phone_button_text']);

        // Sanitize section titles
        $config['contact_section_title'] = sanitize_text_field($config['contact_section_title']);
        $config['credentials_section_title'] = sanitize_text_field($config['credentials_section_title']);
        $config['social_section_title'] = sanitize_text_field($config['social_section_title']);

        // Sanitize credentials
        $config['board_certification'] = sanitize_text_field($config['board_certification']);
        $config['certification_details'] = sanitize_text_field($config['certification_details']);
        $config['experience_years'] = sanitize_text_field($config['experience_years']);
        $config['expertise_area'] = sanitize_text_field($config['expertise_area']);

        return $config;
    }
}
