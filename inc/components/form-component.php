<?php
/**
 * Form Component Base Class
 *
 * Extends BaseComponent with form-specific functionality including
 * validation, error handling, security, and accessibility features.
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
 * FormComponent Class
 *
 * Base class for all form components with comprehensive validation,
 * security features, and accessibility compliance.
 */
class FormComponent extends BaseComponent {

    /**
     * Form fields configuration
     * @var array
     */
    protected $form_fields = [];

    /**
     * Validation rules for form fields
     * @var array
     */
    protected $validation_rules = [];

    /**
     * Error messages for validation failures
     * @var array
     */
    protected $error_messages = [];

    /**
     * Form action URL
     * @var string
     */
    protected $form_action = '';

    /**
     * Form HTTP method
     * @var string
     */
    protected $form_method = 'POST';

    /**
     * CSRF protection enabled
     * @var bool
     */
    protected $csrf_protection = true;

    /**
     * Honeypot protection enabled
     * @var bool
     */
    protected $honeypot_protection = true;

    /**
     * Rate limiting configuration
     * @var array
     */
    protected $rate_limit = [
        'enabled' => true,
        'attempts' => 5,
        'window' => 300 // 5 minutes
    ];

    /**
     * Form submission data
     * @var array
     */
    protected $form_data = [];

    /**
     * Validation errors
     * @var array
     */
    protected $validation_errors = [];

    /**
     * Available input types
     * @var array
     */
    protected $input_types = [
        'text', 'email', 'phone', 'textarea', 'select',
        'checkbox', 'radio', 'hidden', 'number', 'date'
    ];

    /**
     * Render the form component
     *
     * @param array $args Component arguments
     * @return string HTML output
     */
    public function render($args = []) {
        $config = wp_parse_args($args, $this->config);

        // Validate and sanitize inputs
        $config = $this->sanitize_config($config);

        // Generate form attributes
        $form_attributes = $this->generate_form_attributes($config);

        // Generate form fields HTML
        $form_content = $this->generate_form_content($config);

        // Add security fields
        $security_fields = $this->generate_security_fields($config);

        // Render complete form HTML
        $html = $this->render_form_html($form_attributes, $form_content, $security_fields, $config);

        return $this->validate_performance($html);
    }

    /**
     * Get WordPress Customizer controls for forms
     *
     * @return array Customizer controls configuration
     */
    public function get_customizer_controls() {
        return [
            // Form container styling
            'container_background_color' => [
                'type' => 'color',
                'label' => 'Form Background Color',
                'description' => 'Background color for form container',
                'default' => '#ffffff',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            'container_border_radius' => [
                'type' => 'range',
                'label' => 'Form Border Radius',
                'description' => 'Roundness of form container corners',
                'default' => '8',
                'input_attrs' => [
                    'min' => '0',
                    'max' => '30',
                    'step' => '1'
                ],
                'sanitize_callback' => 'absint'
            ],

            'container_padding' => [
                'type' => 'range',
                'label' => 'Form Padding',
                'description' => 'Internal spacing within form container',
                'default' => '32',
                'input_attrs' => [
                    'min' => '16',
                    'max' => '64',
                    'step' => '4'
                ],
                'sanitize_callback' => 'absint'
            ],

            // Input field styling
            'input_background_color' => [
                'type' => 'color',
                'label' => 'Input Background Color',
                'description' => 'Background color for input fields',
                'default' => '#f9fafb',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            'input_border_color' => [
                'type' => 'color',
                'label' => 'Input Border Color',
                'description' => 'Border color for input fields',
                'default' => '#d1d5db',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            'input_border_width' => [
                'type' => 'range',
                'label' => 'Input Border Width',
                'description' => 'Thickness of input field borders',
                'default' => '1',
                'input_attrs' => [
                    'min' => '0',
                    'max' => '4',
                    'step' => '1'
                ],
                'sanitize_callback' => 'absint'
            ],

            'input_focus_color' => [
                'type' => 'color',
                'label' => 'Input Focus Color',
                'description' => 'Border color when input is focused',
                'default' => '#3b82f6',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            'input_text_color' => [
                'type' => 'color',
                'label' => 'Input Text Color',
                'description' => 'Text color for input fields',
                'default' => '#111827',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            'input_font_size' => [
                'type' => 'range',
                'label' => 'Input Font Size',
                'description' => 'Text size for input fields',
                'default' => '16',
                'input_attrs' => [
                    'min' => '14',
                    'max' => '20',
                    'step' => '1'
                ],
                'sanitize_callback' => 'absint'
            ],

            // Label styling
            'label_text_color' => [
                'type' => 'color',
                'label' => 'Label Text Color',
                'description' => 'Color for field labels',
                'default' => '#374151',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            'label_font_weight' => [
                'type' => 'select',
                'label' => 'Label Font Weight',
                'description' => 'Font weight for field labels',
                'default' => '500',
                'choices' => [
                    '400' => 'Normal (400)',
                    '500' => 'Medium (500)',
                    '600' => 'Semi Bold (600)',
                    '700' => 'Bold (700)'
                ],
                'sanitize_callback' => 'sanitize_text_field'
            ],

            // Error message styling
            'error_message_color' => [
                'type' => 'color',
                'label' => 'Error Message Color',
                'description' => 'Color for validation error messages',
                'default' => '#ef4444',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            'error_background_color' => [
                'type' => 'color',
                'label' => 'Error Background Color',
                'description' => 'Background color for error states',
                'default' => '#fef2f2',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            // Success message styling
            'success_message_color' => [
                'type' => 'color',
                'label' => 'Success Message Color',
                'description' => 'Color for success messages',
                'default' => '#10b981',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            'success_background_color' => [
                'type' => 'color',
                'label' => 'Success Background Color',
                'description' => 'Background color for success states',
                'default' => '#ecfdf5',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            // Placeholder text styling
            'placeholder_text_color' => [
                'type' => 'color',
                'label' => 'Placeholder Text Color',
                'description' => 'Color for input placeholder text',
                'default' => '#9ca3af',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            // Required field indicator
            'required_indicator_color' => [
                'type' => 'color',
                'label' => 'Required Field Indicator Color',
                'description' => 'Color for required field asterisk',
                'default' => '#ef4444',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            // Form validation timing
            'validation_timing' => [
                'type' => 'select',
                'label' => 'Validation Timing',
                'description' => 'When to trigger form validation',
                'default' => 'blur',
                'choices' => [
                    'realtime' => 'Real-time (as you type)',
                    'blur' => 'On field blur (recommended)',
                    'submit' => 'On form submission only'
                ],
                'sanitize_callback' => 'sanitize_text_field'
            ],

            // Animation speed
            'animation_speed' => [
                'type' => 'range',
                'label' => 'Animation Speed',
                'description' => 'Speed of form animations in milliseconds',
                'default' => '300',
                'input_attrs' => [
                    'min' => '100',
                    'max' => '800',
                    'step' => '50'
                ],
                'sanitize_callback' => 'absint'
            ]
        ];
    }

    /**
     * Get default design tokens for forms
     *
     * @return array Design tokens
     */
    public function get_default_tokens() {
        return [
            // Container styling
            'container_background_color' => '#ffffff',
            'container_border_radius' => '8px',
            'container_padding' => '32px',
            'container_shadow' => '0 4px 6px -1px rgba(0, 0, 0, 0.1)',
            'container_border' => '1px solid #e5e7eb',

            // Input field styling
            'input_background_color' => '#f9fafb',
            'input_border_color' => '#d1d5db',
            'input_border_width' => '1px',
            'input_border_radius' => '6px',
            'input_padding' => '12px 16px',
            'input_font_size' => '16px',
            'input_line_height' => '1.5',
            'input_text_color' => '#111827',
            'input_focus_color' => '#3b82f6',
            'input_focus_shadow' => '0 0 0 3px rgba(59, 130, 246, 0.1)',
            'input_transition' => '0.15s ease-in-out',

            // Label styling
            'label_font_size' => '14px',
            'label_font_weight' => '500',
            'label_text_color' => '#374151',
            'label_margin_bottom' => '6px',

            // Error styling
            'error_message_color' => '#ef4444',
            'error_background_color' => '#fef2f2',
            'error_border_color' => '#fca5a5',
            'error_font_size' => '14px',
            'error_margin_top' => '4px',

            // Success styling
            'success_message_color' => '#10b981',
            'success_background_color' => '#ecfdf5',
            'success_border_color' => '#6ee7b7',
            'success_font_size' => '14px',

            // Placeholder styling
            'placeholder_text_color' => '#9ca3af',
            'placeholder_font_style' => 'italic',

            // Required indicator
            'required_indicator_color' => '#ef4444',
            'required_indicator_font_size' => '14px',

            // Spacing
            'field_spacing' => '20px',
            'group_spacing' => '32px',

            // Animation
            'transition_duration' => '0.15s',
            'animation_speed' => '300ms'
        ];
    }

    /**
     * Get component default configuration
     *
     * @return array Default configuration
     */
    public function get_defaults() {
        return [
            // Form identification
            'form_id' => '',
            'form_class' => '',
            'form_action' => '',
            'form_method' => 'POST',

            // Form behavior
            'ajax_submission' => true,
            'show_loading' => true,
            'redirect_after_submit' => '',
            'success_message' => 'Thank you! Your message has been sent successfully.',
            'error_message' => 'There was an error sending your message. Please try again.',

            // Security settings
            'csrf_protection' => true,
            'honeypot_protection' => true,
            'rate_limiting' => true,

            // Validation settings
            'client_side_validation' => true,
            'server_side_validation' => true,
            'validation_timing' => 'blur',
            'show_validation_icons' => true,

            // Email settings
            'send_email' => true,
            'email_to' => get_option('admin_email'),
            'email_subject' => 'New Form Submission',
            'email_template' => 'default',
            'auto_reply' => false,

            // Database settings
            'save_to_database' => false,
            'database_table' => 'form_submissions',

            // Accessibility
            'aria_labels' => true,
            'error_announcements' => true,
            'keyboard_navigation' => true,

            // Form fields (empty by default, should be overridden)
            'fields' => [],

            // Custom attributes
            'custom_attributes' => []
        ];
    }

    /**
     * Render form fields based on configuration
     *
     * @param array $config Form configuration
     * @return string Form fields HTML
     */
    public function render_form($args = []) {
        $config = wp_parse_args($args, $this->get_defaults());
        return $this->render($config);
    }

    /**
     * Validate form data
     *
     * @param array $data Form data to validate
     * @return bool True if valid, false otherwise
     */
    public function validate_form($data) {
        $this->validation_errors = [];
        $this->form_data = $data;

        // Check CSRF protection
        if ($this->csrf_protection && !$this->validate_csrf($data)) {
            $this->validation_errors['csrf'] = 'Security validation failed. Please try again.';
            return false;
        }

        // Check honeypot protection
        if ($this->honeypot_protection && !$this->validate_honeypot($data)) {
            $this->validation_errors['honeypot'] = 'Spam protection triggered.';
            return false;
        }

        // Check rate limiting
        if ($this->rate_limit['enabled'] && !$this->validate_rate_limit()) {
            $this->validation_errors['rate_limit'] = 'Too many attempts. Please try again later.';
            return false;
        }

        // Validate individual fields
        foreach ($this->form_fields as $field_name => $field_config) {
            $this->validate_field($field_name, $field_config, $data);
        }

        return empty($this->validation_errors);
    }

    /**
     * Process form submission
     *
     * @param array $data Form submission data
     * @return array Response array with success status and message
     */
    public function process_submission($data) {
        // Validate form data
        if (!$this->validate_form($data)) {
            return [
                'success' => false,
                'message' => 'Please correct the errors below.',
                'errors' => $this->validation_errors
            ];
        }

        // Sanitize data
        $sanitized_data = $this->sanitize_form_data($data);

        try {
            // Send email if configured
            if ($this->config['send_email']) {
                $this->send_email_notification($sanitized_data);
            }

            // Save to database if configured
            if ($this->config['save_to_database']) {
                $this->save_to_database($sanitized_data);
            }

            // Send auto-reply if configured
            if ($this->config['auto_reply'] && !empty($sanitized_data['email'])) {
                $this->send_auto_reply($sanitized_data);
            }

            return [
                'success' => true,
                'message' => $this->config['success_message'],
                'redirect' => $this->config['redirect_after_submit']
            ];

        } catch (Exception $e) {
            error_log('Form submission error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => $this->config['error_message']
            ];
        }
    }

    /**
     * Render individual form field
     *
     * @param string $field_name Field name
     * @param array $field_config Field configuration
     * @return string Field HTML
     */
    public function render_field($field_name, $field_config) {
        $field_config = wp_parse_args($field_config, [
            'type' => 'text',
            'label' => ucfirst(str_replace('_', ' ', $field_name)),
            'placeholder' => '',
            'required' => false,
            'value' => '',
            'options' => [], // For select, radio, checkbox
            'attributes' => [],
            'wrapper_class' => '',
            'label_class' => '',
            'input_class' => '',
            'description' => '',
            'validation' => []
        ]);

        $field_html = '';
        $field_id = $this->get_field_id($field_name);
        $wrapper_classes = ['form-field', 'form-field-' . $field_config['type']];

        if (!empty($field_config['wrapper_class'])) {
            $wrapper_classes[] = $field_config['wrapper_class'];
        }

        if ($field_config['required']) {
            $wrapper_classes[] = 'form-field-required';
        }

        // Start field wrapper
        $field_html .= sprintf(
            '<div class="%s" data-field="%s">',
            esc_attr(implode(' ', $wrapper_classes)),
            esc_attr($field_name)
        );

        // Render label
        if (!empty($field_config['label']) && $field_config['type'] !== 'hidden') {
            $field_html .= $this->render_field_label($field_id, $field_config);
        }

        // Render input based on type
        switch ($field_config['type']) {
            case 'textarea':
                $field_html .= $this->render_textarea($field_id, $field_name, $field_config);
                break;
            case 'select':
                $field_html .= $this->render_select($field_id, $field_name, $field_config);
                break;
            case 'checkbox':
                $field_html .= $this->render_checkbox($field_id, $field_name, $field_config);
                break;
            case 'radio':
                $field_html .= $this->render_radio($field_id, $field_name, $field_config);
                break;
            default:
                $field_html .= $this->render_input($field_id, $field_name, $field_config);
        }

        // Render field description
        if (!empty($field_config['description'])) {
            $field_html .= sprintf(
                '<div class="form-field-description" id="%s-description">%s</div>',
                esc_attr($field_id),
                esc_html($field_config['description'])
            );
        }

        // Render error message placeholder
        $field_html .= sprintf(
            '<div class="form-field-error" id="%s-error" role="alert" aria-live="polite"></div>',
            esc_attr($field_id)
        );

        // End field wrapper
        $field_html .= '</div>';

        return $field_html;
    }

    /**
     * Get validation errors
     *
     * @return array Validation errors
     */
    public function get_validation_errors() {
        return $this->validation_errors;
    }

    // Private helper methods continue below...

    /**
     * Sanitize form configuration
     *
     * @param array $config Configuration to sanitize
     * @return array Sanitized configuration
     */
    protected function sanitize_config($config) {
        // Sanitize basic form settings
        $config['form_id'] = sanitize_text_field($config['form_id']);
        $config['form_class'] = sanitize_text_field($config['form_class']);
        $config['form_action'] = esc_url($config['form_action']);
        $config['form_method'] = in_array(strtoupper($config['form_method']), ['GET', 'POST'])
            ? strtoupper($config['form_method']) : 'POST';

        // Sanitize messages
        $config['success_message'] = wp_kses_post($config['success_message']);
        $config['error_message'] = wp_kses_post($config['error_message']);

        // Sanitize email settings
        $config['email_to'] = sanitize_email($config['email_to']);
        $config['email_subject'] = sanitize_text_field($config['email_subject']);

        // Validate fields array
        if (is_array($config['fields'])) {
            foreach ($config['fields'] as $field_name => $field_config) {
                $config['fields'][$field_name] = $this->sanitize_field_config($field_config);
            }
        }

        return $config;
    }

    /**
     * Sanitize individual field configuration
     *
     * @param array $field_config Field configuration
     * @return array Sanitized field configuration
     */
    protected function sanitize_field_config($field_config) {
        $field_config['type'] = in_array($field_config['type'], $this->input_types)
            ? $field_config['type'] : 'text';
        $field_config['label'] = sanitize_text_field($field_config['label']);
        $field_config['placeholder'] = sanitize_text_field($field_config['placeholder']);
        $field_config['value'] = sanitize_text_field($field_config['value']);
        $field_config['description'] = sanitize_text_field($field_config['description']);

        return $field_config;
    }

    /**
     * Generate form HTML attributes
     *
     * @param array $config Form configuration
     * @return array Form attributes
     */
    protected function generate_form_attributes($config) {
        $attributes = [
            'id' => $config['form_id'] ?: 'form-' . uniqid(),
            'class' => $this->get_form_classes($config),
            'method' => $config['form_method'],
            'action' => $config['form_action'] ?: '#',
            'novalidate' => $config['client_side_validation'] ? '' : 'novalidate'
        ];

        // Add AJAX attributes
        if ($config['ajax_submission']) {
            $attributes['class'] .= ' form-ajax';
            $attributes['data-ajax'] = 'true';
        }

        // Add validation timing
        $attributes['data-validation-timing'] = $config['validation_timing'];

        // Add custom attributes
        if (!empty($config['custom_attributes'])) {
            $attributes = array_merge($attributes, $config['custom_attributes']);
        }

        return $attributes;
    }

    /**
     * Get form CSS classes
     *
     * @param array $config Form configuration
     * @return string CSS classes
     */
    protected function get_form_classes($config) {
        $classes = ['form-component'];

        if (!empty($config['form_class'])) {
            $classes[] = $config['form_class'];
        }

        if ($config['ajax_submission']) {
            $classes[] = 'form-ajax';
        }

        if ($config['client_side_validation']) {
            $classes[] = 'form-client-validation';
        }

        return implode(' ', $classes);
    }

    /**
     * Generate form content (fields)
     *
     * @param array $config Form configuration
     * @return string Form content HTML
     */
    protected function generate_form_content($config) {
        $content = '';

        if (empty($config['fields'])) {
            return '<p>No form fields configured.</p>';
        }

        foreach ($config['fields'] as $field_name => $field_config) {
            $this->form_fields[$field_name] = $field_config;
            $content .= $this->render_field($field_name, $field_config);
        }

        return $content;
    }

    /**
     * Generate security fields (CSRF, honeypot)
     *
     * @param array $config Form configuration
     * @return string Security fields HTML
     */
    protected function generate_security_fields($config) {
        $security_fields = '';

        // CSRF protection
        if ($config['csrf_protection']) {
            $security_fields .= wp_nonce_field('form_submission', '_form_nonce', true, false);
        }

        // Honeypot protection
        if ($config['honeypot_protection']) {
            $security_fields .= '<div style="position:absolute;left:-9999px;" aria-hidden="true">';
            $security_fields .= '<input type="text" name="honeypot_field" tabindex="-1" autocomplete="off">';
            $security_fields .= '</div>';
        }

        return $security_fields;
    }

    /**
     * Render complete form HTML
     *
     * @param array $form_attributes Form attributes
     * @param string $form_content Form fields HTML
     * @param string $security_fields Security fields HTML
     * @param array $config Form configuration
     * @return string Complete form HTML
     */
    protected function render_form_html($form_attributes, $form_content, $security_fields, $config) {
        $attributes_string = '';
        foreach ($form_attributes as $key => $value) {
            if ($value !== '') {
                $attributes_string .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
            } else {
                $attributes_string .= ' ' . esc_attr($key);
            }
        }

        $html = '<form' . $attributes_string . '>';
        $html .= $security_fields;
        $html .= '<div class="form-content">';
        $html .= $form_content;
        $html .= '</div>';

        // Add loading indicator
        if ($config['show_loading']) {
            $html .= '<div class="form-loading" style="display:none;">Processing...</div>';
        }

        // Add messages container
        $html .= '<div class="form-messages" role="status" aria-live="polite"></div>';

        $html .= '</form>';

        return $html;
    }

    /**
     * Additional helper methods for field rendering, validation, etc.
     * (Implementation continues with specific field rendering methods)
     */

    protected function get_field_id($field_name) {
        return 'field_' . str_replace(['[', ']'], ['_', ''], $field_name);
    }

    protected function render_field_label($field_id, $field_config) {
        $label_classes = ['form-field-label'];
        if (!empty($field_config['label_class'])) {
            $label_classes[] = $field_config['label_class'];
        }

        $required_indicator = $field_config['required'] ? ' <span class="required-indicator">*</span>' : '';

        return sprintf(
            '<label for="%s" class="%s">%s%s</label>',
            esc_attr($field_id),
            esc_attr(implode(' ', $label_classes)),
            esc_html($field_config['label']),
            $required_indicator
        );
    }

    protected function render_input($field_id, $field_name, $field_config) {
        $input_classes = ['form-field-input'];
        if (!empty($field_config['input_class'])) {
            $input_classes[] = $field_config['input_class'];
        }

        $attributes = [
            'type' => $field_config['type'],
            'id' => $field_id,
            'name' => $field_name,
            'class' => implode(' ', $input_classes),
            'value' => $field_config['value']
        ];

        if (!empty($field_config['placeholder'])) {
            $attributes['placeholder'] = $field_config['placeholder'];
        }

        if ($field_config['required']) {
            $attributes['required'] = 'required';
            $attributes['aria-required'] = 'true';
        }

        if (!empty($field_config['description'])) {
            $attributes['aria-describedby'] = $field_id . '-description';
        }

        // Add validation attributes
        if (!empty($field_config['validation'])) {
            foreach ($field_config['validation'] as $rule => $value) {
                switch ($rule) {
                    case 'minlength':
                        $attributes['minlength'] = $value;
                        break;
                    case 'maxlength':
                        $attributes['maxlength'] = $value;
                        break;
                    case 'pattern':
                        $attributes['pattern'] = $value;
                        break;
                }
            }
        }

        // Add custom attributes
        if (!empty($field_config['attributes'])) {
            $attributes = array_merge($attributes, $field_config['attributes']);
        }

        $attributes_string = '';
        foreach ($attributes as $key => $value) {
            $attributes_string .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
        }

        return '<input' . $attributes_string . '>';
    }

    protected function render_textarea($field_id, $field_name, $field_config) {
        $input_classes = ['form-field-input', 'form-field-textarea'];
        if (!empty($field_config['input_class'])) {
            $input_classes[] = $field_config['input_class'];
        }

        $attributes = [
            'id' => $field_id,
            'name' => $field_name,
            'class' => implode(' ', $input_classes),
            'rows' => $field_config['rows'] ?? 4
        ];

        if (!empty($field_config['placeholder'])) {
            $attributes['placeholder'] = $field_config['placeholder'];
        }

        if ($field_config['required']) {
            $attributes['required'] = 'required';
            $attributes['aria-required'] = 'true';
        }

        if (!empty($field_config['description'])) {
            $attributes['aria-describedby'] = $field_id . '-description';
        }

        $attributes_string = '';
        foreach ($attributes as $key => $value) {
            $attributes_string .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
        }

        return sprintf(
            '<textarea%s>%s</textarea>',
            $attributes_string,
            esc_textarea($field_config['value'])
        );
    }

    protected function render_select($field_id, $field_name, $field_config) {
        $input_classes = ['form-field-input', 'form-field-select'];
        if (!empty($field_config['input_class'])) {
            $input_classes[] = $field_config['input_class'];
        }

        $attributes = [
            'id' => $field_id,
            'name' => $field_name,
            'class' => implode(' ', $input_classes)
        ];

        if ($field_config['required']) {
            $attributes['required'] = 'required';
            $attributes['aria-required'] = 'true';
        }

        $attributes_string = '';
        foreach ($attributes as $key => $value) {
            $attributes_string .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
        }

        $html = '<select' . $attributes_string . '>';

        // Add placeholder option
        if (!empty($field_config['placeholder'])) {
            $html .= sprintf(
                '<option value="">%s</option>',
                esc_html($field_config['placeholder'])
            );
        }

        // Add options
        if (!empty($field_config['options'])) {
            foreach ($field_config['options'] as $value => $label) {
                $selected = ($field_config['value'] == $value) ? ' selected' : '';
                $html .= sprintf(
                    '<option value="%s"%s>%s</option>',
                    esc_attr($value),
                    $selected,
                    esc_html($label)
                );
            }
        }

        $html .= '</select>';

        return $html;
    }

    protected function render_checkbox($field_id, $field_name, $field_config) {
        // Implementation for checkbox field rendering
        // Similar pattern to other field types
        return '<input type="checkbox" id="' . esc_attr($field_id) . '" name="' . esc_attr($field_name) . '">';
    }

    protected function render_radio($field_id, $field_name, $field_config) {
        // Implementation for radio field rendering
        // Similar pattern to other field types
        return '<input type="radio" id="' . esc_attr($field_id) . '" name="' . esc_attr($field_name) . '">';
    }

    // Validation helper methods
    protected function validate_csrf($data) {
        return isset($data['_form_nonce']) && wp_verify_nonce($data['_form_nonce'], 'form_submission');
    }

    protected function validate_honeypot($data) {
        return empty($data['honeypot_field']);
    }

    protected function validate_rate_limit() {
        // Basic rate limiting implementation
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $cache_key = 'form_rate_limit_' . md5($ip);
        $attempts = get_transient($cache_key) ?: 0;

        if ($attempts >= $this->rate_limit['attempts']) {
            return false;
        }

        set_transient($cache_key, $attempts + 1, $this->rate_limit['window']);
        return true;
    }

    protected function validate_field($field_name, $field_config, $data) {
        $value = $data[$field_name] ?? '';

        // Required field validation
        if ($field_config['required'] && empty($value)) {
            $this->validation_errors[$field_name] = 'This field is required.';
            return;
        }

        // Type-specific validation
        switch ($field_config['type']) {
            case 'email':
                if (!empty($value) && !is_email($value)) {
                    $this->validation_errors[$field_name] = 'Please enter a valid email address.';
                }
                break;
            case 'phone':
                if (!empty($value) && !preg_match('/^[\d\s\-\+\(\)\.]+$/', $value)) {
                    $this->validation_errors[$field_name] = 'Please enter a valid phone number.';
                }
                break;
        }

        // Custom validation rules
        if (!empty($field_config['validation'])) {
            foreach ($field_config['validation'] as $rule => $rule_value) {
                $this->apply_validation_rule($field_name, $value, $rule, $rule_value);
            }
        }
    }

    protected function apply_validation_rule($field_name, $value, $rule, $rule_value) {
        switch ($rule) {
            case 'minlength':
                if (strlen($value) < $rule_value) {
                    $this->validation_errors[$field_name] = "Minimum length is {$rule_value} characters.";
                }
                break;
            case 'maxlength':
                if (strlen($value) > $rule_value) {
                    $this->validation_errors[$field_name] = "Maximum length is {$rule_value} characters.";
                }
                break;
            case 'pattern':
                if (!preg_match($rule_value, $value)) {
                    $this->validation_errors[$field_name] = 'Please enter a valid value.';
                }
                break;
        }
    }

    protected function sanitize_form_data($data) {
        $sanitized = [];

        foreach ($this->form_fields as $field_name => $field_config) {
            if (!isset($data[$field_name])) {
                continue;
            }

            $value = $data[$field_name];

            switch ($field_config['type']) {
                case 'email':
                    $sanitized[$field_name] = sanitize_email($value);
                    break;
                case 'textarea':
                    $sanitized[$field_name] = sanitize_textarea_field($value);
                    break;
                default:
                    $sanitized[$field_name] = sanitize_text_field($value);
            }
        }

        return $sanitized;
    }

    protected function send_email_notification($data) {
        $to = $this->config['email_to'];
        $subject = $this->config['email_subject'];
        $message = $this->format_email_message($data);
        $headers = ['Content-Type: text/html; charset=UTF-8'];

        return wp_mail($to, $subject, $message, $headers);
    }

    protected function format_email_message($data) {
        $message = '<h2>New Form Submission</h2>';

        foreach ($data as $field_name => $value) {
            $label = $this->form_fields[$field_name]['label'] ?? ucfirst(str_replace('_', ' ', $field_name));
            $message .= '<p><strong>' . esc_html($label) . ':</strong> ' . esc_html($value) . '</p>';
        }

        return $message;
    }

    protected function save_to_database($data) {
        // Implementation for saving form data to database
        // This would typically involve creating a custom table or using WordPress options
        return true;
    }

    protected function send_auto_reply($data) {
        // Implementation for sending auto-reply email
        if (empty($data['email'])) {
            return false;
        }

        $subject = 'Thank you for your message';
        $message = 'Thank you for contacting us. We will get back to you soon.';
        $headers = ['Content-Type: text/html; charset=UTF-8'];

        return wp_mail($data['email'], $subject, $message, $headers);
    }
}
