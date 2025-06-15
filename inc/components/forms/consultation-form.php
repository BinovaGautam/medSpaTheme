<?php
/**
 * Consultation Form Component
 *
 * Specialized form for medical spa consultation bookings
 * with treatment-specific fields and booking functionality.
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
 * ConsultationForm Class
 *
 * Specialized form component for consultation bookings
 */
class ConsultationForm extends FormComponent {

    /**
     * Get component default configuration
     *
     * @return array Default configuration
     */
    public function get_defaults() {
        return array_merge(parent::get_defaults(), [
            // Form identification
            'form_id' => 'consultation-form',
            'form_class' => 'form-consultation',

            // Email settings
            'email_subject' => 'New Consultation Request',
            'success_message' => 'Thank you! Your consultation request has been submitted. We will contact you within 24 hours to confirm your appointment.',
            'auto_reply' => true,

            // Consultation specific settings
            'enable_scheduling' => true,
            'available_treatments' => $this->get_available_treatments(),
            'time_slots' => $this->get_available_time_slots(),
            'consultation_types' => [
                'initial' => 'Initial Consultation (Free)',
                'follow_up' => 'Follow-up Consultation',
                'treatment_planning' => 'Treatment Planning Session'
            ],

            // Form fields
            'fields' => [
                'consultation_type' => [
                    'type' => 'select',
                    'label' => 'Consultation Type',
                    'required' => true,
                    'options' => [
                        'initial' => 'Initial Consultation (Free)',
                        'follow_up' => 'Follow-up Consultation',
                        'treatment_planning' => 'Treatment Planning Session'
                    ],
                    'description' => 'Select the type of consultation you need'
                ],

                'first_name' => [
                    'type' => 'text',
                    'label' => 'First Name',
                    'required' => true,
                    'placeholder' => 'Enter your first name',
                    'validation' => [
                        'minlength' => 2,
                        'maxlength' => 50
                    ]
                ],

                'last_name' => [
                    'type' => 'text',
                    'label' => 'Last Name',
                    'required' => true,
                    'placeholder' => 'Enter your last name',
                    'validation' => [
                        'minlength' => 2,
                        'maxlength' => 50
                    ]
                ],

                'email' => [
                    'type' => 'email',
                    'label' => 'Email Address',
                    'required' => true,
                    'placeholder' => 'your.email@example.com',
                    'description' => 'We will send appointment confirmation to this email'
                ],

                'phone' => [
                    'type' => 'phone',
                    'label' => 'Phone Number',
                    'required' => true,
                    'placeholder' => '(555) 123-4567',
                    'description' => 'For appointment confirmations and reminders'
                ],

                'age' => [
                    'type' => 'number',
                    'label' => 'Age',
                    'required' => true,
                    'attributes' => [
                        'min' => '18',
                        'max' => '100'
                    ],
                    'description' => 'Must be 18 or older for treatments'
                ],

                'treatment_interest' => [
                    'type' => 'select',
                    'label' => 'Treatment of Interest',
                    'required' => true,
                    'options' => [], // Will be populated with available treatments
                    'description' => 'Primary treatment you are interested in discussing'
                ],

                'skin_concerns' => [
                    'type' => 'checkbox',
                    'label' => 'Skin Concerns',
                    'options' => [
                        'acne' => 'Acne/Breakouts',
                        'aging' => 'Signs of Aging',
                        'hyperpigmentation' => 'Dark Spots/Hyperpigmentation',
                        'scarring' => 'Acne Scars/Texture',
                        'rosacea' => 'Rosacea/Redness',
                        'sun_damage' => 'Sun Damage',
                        'enlarged_pores' => 'Enlarged Pores',
                        'dullness' => 'Dull/Uneven Skin Tone',
                        'wrinkles' => 'Fine Lines/Wrinkles',
                        'other' => 'Other (please specify in comments)'
                    ],
                    'description' => 'Select all that apply'
                ],

                'previous_treatments' => [
                    'type' => 'textarea',
                    'label' => 'Previous Treatments',
                    'placeholder' => 'List any previous cosmetic or dermatological treatments you have had...',
                    'rows' => 3,
                    'description' => 'Help us understand your treatment history'
                ],

                'current_skincare' => [
                    'type' => 'textarea',
                    'label' => 'Current Skincare Routine',
                    'placeholder' => 'Describe your current skincare products and routine...',
                    'rows' => 3,
                    'description' => 'Include any prescription medications or retinoids'
                ],

                'allergies_medications' => [
                    'type' => 'textarea',
                    'label' => 'Allergies & Medications',
                    'placeholder' => 'List any allergies, medications, or medical conditions...',
                    'rows' => 2,
                    'description' => 'Important for treatment safety'
                ],

                'preferred_date' => [
                    'type' => 'date',
                    'label' => 'Preferred Date',
                    'required' => true,
                    'attributes' => [
                        'min' => date('Y-m-d', strtotime('+1 day')),
                        'max' => date('Y-m-d', strtotime('+3 months'))
                    ],
                    'description' => 'Select your preferred appointment date'
                ],

                'preferred_time' => [
                    'type' => 'select',
                    'label' => 'Preferred Time',
                    'required' => true,
                    'options' => [], // Will be populated with time slots
                    'description' => 'Select your preferred time slot'
                ],

                'budget_range' => [
                    'type' => 'select',
                    'label' => 'Budget Range',
                    'options' => [
                        '' => 'Select budget range...',
                        'under_500' => 'Under $500',
                        '500_1000' => '$500 - $1,000',
                        '1000_2500' => '$1,000 - $2,500',
                        '2500_5000' => '$2,500 - $5,000',
                        'over_5000' => 'Over $5,000',
                        'discuss' => 'Prefer to discuss'
                    ],
                    'description' => 'Help us recommend appropriate treatments'
                ],

                'how_heard' => [
                    'type' => 'select',
                    'label' => 'How Did You Hear About Us?',
                    'options' => [
                        '' => 'Select an option...',
                        'google' => 'Google Search',
                        'social_media' => 'Social Media',
                        'referral' => 'Friend/Family Referral',
                        'doctor_referral' => 'Doctor Referral',
                        'advertisement' => 'Advertisement',
                        'website' => 'Website',
                        'other' => 'Other'
                    ]
                ],

                'special_requests' => [
                    'type' => 'textarea',
                    'label' => 'Special Requests or Questions',
                    'placeholder' => 'Any specific questions or special accommodations needed...',
                    'rows' => 3,
                    'description' => 'Optional - any additional information you would like us to know'
                ],

                'consent_privacy' => [
                    'type' => 'checkbox',
                    'label' => 'I consent to the collection and use of my personal information as described in the Privacy Policy',
                    'required' => true,
                    'options' => [
                        'agreed' => 'I agree to the Privacy Policy'
                    ]
                ],

                'consent_marketing' => [
                    'type' => 'checkbox',
                    'label' => 'I would like to receive marketing communications and special offers',
                    'options' => [
                        'agreed' => 'Yes, send me promotions and updates'
                    ],
                    'description' => 'You can unsubscribe at any time'
                ]
            ]
        ]);
    }

    /**
     * Get WordPress Customizer controls for consultation forms
     *
     * @return array Customizer controls configuration
     */
    public function get_customizer_controls() {
        return array_merge(parent::get_customizer_controls(), [
            // Consultation form specific controls
            'consultation_header_color' => [
                'type' => 'color',
                'label' => 'Consultation Form Header Color',
                'description' => 'Color for consultation form headers and accents',
                'default' => '#1d4ed8',
                'sanitize_callback' => 'sanitize_hex_color'
            ],

            'treatment_selection_style' => [
                'type' => 'select',
                'label' => 'Treatment Selection Style',
                'description' => 'How treatment options are displayed',
                'default' => 'dropdown',
                'choices' => [
                    'dropdown' => 'Dropdown Select',
                    'radio' => 'Radio Buttons',
                    'cards' => 'Treatment Cards'
                ],
                'sanitize_callback' => 'sanitize_text_field'
            ],

            'show_pricing_info' => [
                'type' => 'checkbox',
                'label' => 'Show Pricing Information',
                'description' => 'Display estimated pricing for treatments',
                'default' => false
            ],

            'enable_online_scheduling' => [
                'type' => 'checkbox',
                'label' => 'Enable Online Scheduling',
                'description' => 'Allow patients to select specific appointment times',
                'default' => true
            ],

            'require_medical_history' => [
                'type' => 'checkbox',
                'label' => 'Require Medical History',
                'description' => 'Make medical history fields required',
                'default' => true
            ]
        ]);
    }

    /**
     * Get available treatments for selection
     *
     * @return array Available treatments
     */
    protected function get_available_treatments() {
        $treatment_options = [
            'injectable-artistry' => 'Botox / Fillers',
            'facial-renaissance' => 'HydraFacial',
            'precision-dermaplanning' => 'Dermaplanning',
            'regenerative-prp' => 'Microneedling PRP',
            'wellness-infusions' => 'IV Vitamins',
            'artistry-enhancement' => 'Permanent Makeup',
            'laser-precision' => 'Laser Hair Reduction',
            'carbon-rejuvenation' => 'Carbon Peel Laser',
            'body-sculpting' => 'EMSCULPT Muscle Builder',
        ];
        return $treatment_options;
    }

    /**
     * Get available time slots
     *
     * @return array Available time slots
     */
    protected function get_available_time_slots() {
        return [
            '' => 'Select preferred time...',
            '09:00' => '9:00 AM',
            '09:30' => '9:30 AM',
            '10:00' => '10:00 AM',
            '10:30' => '10:30 AM',
            '11:00' => '11:00 AM',
            '11:30' => '11:30 AM',
            '12:00' => '12:00 PM',
            '12:30' => '12:30 PM',
            '13:00' => '1:00 PM',
            '13:30' => '1:30 PM',
            '14:00' => '2:00 PM',
            '14:30' => '2:30 PM',
            '15:00' => '3:00 PM',
            '15:30' => '3:30 PM',
            '16:00' => '4:00 PM',
            '16:30' => '4:30 PM',
            '17:00' => '5:00 PM'
        ];
    }

    /**
     * Constructor - Initialize consultation form with populated options
     *
     * @param array $args Component arguments
     */
    public function __construct($args = []) {
        // Populate treatment options before parent constructor
        $defaults = $this->get_defaults();
        $defaults['fields']['treatment_interest']['options'] = $this->get_available_treatments();
        $defaults['fields']['preferred_time']['options'] = $this->get_available_time_slots();

        // Merge with provided args
        $args = wp_parse_args($args, $defaults);

        parent::__construct($args);
    }

    /**
     * Process consultation form submission
     *
     * @param array $data Form submission data
     * @return array Response array with success status and message
     */
    public function process_submission($data) {
        // Validate consultation-specific requirements
        if (!$this->validate_consultation_data($data)) {
            return [
                'success' => false,
                'message' => 'Please correct the errors below.',
                'errors' => $this->validation_errors
            ];
        }

        // Process base form validation and submission
        $result = parent::process_submission($data);

        // If successful, handle consultation-specific actions
        if ($result['success']) {
            $this->schedule_consultation_reminder($data);
            $this->notify_staff($data);
            $this->create_patient_record($data);
        }

        return $result;
    }

    /**
     * Validate consultation-specific data
     *
     * @param array $data Form data
     * @return bool Validation result
     */
    protected function validate_consultation_data($data) {
        $valid = true;

        // Age validation
        if (!empty($data['age']) && $data['age'] < 18) {
            $this->validation_errors['age'] = 'You must be 18 or older to book a consultation.';
            $valid = false;
        }

        // Date validation (not in the past)
        if (!empty($data['preferred_date'])) {
            $selected_date = strtotime($data['preferred_date']);
            $tomorrow = strtotime('+1 day');

            if ($selected_date < $tomorrow) {
                $this->validation_errors['preferred_date'] = 'Please select a date at least one day in advance.';
                $valid = false;
            }
        }

        // Privacy consent validation
        if (empty($data['consent_privacy'])) {
            $this->validation_errors['consent_privacy'] = 'Privacy policy consent is required.';
            $valid = false;
        }

        return $valid;
    }

    /**
     * Schedule consultation reminder email
     *
     * @param array $data Form data
     */
    protected function schedule_consultation_reminder($data) {
        if (empty($data['preferred_date']) || empty($data['email'])) {
            return;
        }

        // Schedule reminder for 1 day before appointment
        $reminder_time = strtotime($data['preferred_date'] . ' -1 day');

        // Use WordPress cron to schedule reminder
        wp_schedule_single_event($reminder_time, 'consultation_reminder', [
            'email' => $data['email'],
            'first_name' => $data['first_name'],
            'appointment_date' => $data['preferred_date'],
            'appointment_time' => $data['preferred_time']
        ]);
    }

    /**
     * Notify staff of new consultation request
     *
     * @param array $data Form data
     */
    protected function notify_staff($data) {
        $staff_email = get_option('consultation_notification_email', get_option('admin_email'));

        $subject = 'New Consultation Request - ' . $data['first_name'] . ' ' . $data['last_name'];

        $message = $this->format_staff_notification($data);

        wp_mail($staff_email, $subject, $message, ['Content-Type: text/html; charset=UTF-8']);
    }

    /**
     * Format staff notification email
     *
     * @param array $data Form data
     * @return string Formatted email content
     */
    protected function format_staff_notification($data) {
        $message = '<h2>New Consultation Request</h2>';

        $message .= '<h3>Patient Information</h3>';
        $message .= '<p><strong>Name:</strong> ' . esc_html($data['first_name'] . ' ' . $data['last_name']) . '</p>';
        $message .= '<p><strong>Email:</strong> ' . esc_html($data['email']) . '</p>';
        $message .= '<p><strong>Phone:</strong> ' . esc_html($data['phone']) . '</p>';
        $message .= '<p><strong>Age:</strong> ' . esc_html($data['age']) . '</p>';

        $message .= '<h3>Consultation Details</h3>';
        $message .= '<p><strong>Type:</strong> ' . esc_html($data['consultation_type']) . '</p>';
        $message .= '<p><strong>Treatment Interest:</strong> ' . esc_html($data['treatment_interest']) . '</p>';
        $message .= '<p><strong>Preferred Date:</strong> ' . esc_html($data['preferred_date']) . '</p>';
        $message .= '<p><strong>Preferred Time:</strong> ' . esc_html($data['preferred_time']) . '</p>';

        if (!empty($data['skin_concerns'])) {
            $concerns = is_array($data['skin_concerns']) ? implode(', ', $data['skin_concerns']) : $data['skin_concerns'];
            $message .= '<p><strong>Skin Concerns:</strong> ' . esc_html($concerns) . '</p>';
        }

        if (!empty($data['budget_range'])) {
            $message .= '<p><strong>Budget Range:</strong> ' . esc_html($data['budget_range']) . '</p>';
        }

        if (!empty($data['previous_treatments'])) {
            $message .= '<p><strong>Previous Treatments:</strong><br>' . nl2br(esc_html($data['previous_treatments'])) . '</p>';
        }

        if (!empty($data['current_skincare'])) {
            $message .= '<p><strong>Current Skincare:</strong><br>' . nl2br(esc_html($data['current_skincare'])) . '</p>';
        }

        if (!empty($data['allergies_medications'])) {
            $message .= '<p><strong>Allergies/Medications:</strong><br>' . nl2br(esc_html($data['allergies_medications'])) . '</p>';
        }

        if (!empty($data['special_requests'])) {
            $message .= '<p><strong>Special Requests:</strong><br>' . nl2br(esc_html($data['special_requests'])) . '</p>';
        }

        if (!empty($data['how_heard'])) {
            $message .= '<p><strong>How They Heard About Us:</strong> ' . esc_html($data['how_heard']) . '</p>';
        }

        $message .= '<hr>';
        $message .= '<p><em>Please contact the patient within 24 hours to confirm the appointment.</em></p>';

        return $message;
    }

    /**
     * Create patient record (if database storage is enabled)
     *
     * @param array $data Form data
     */
    protected function create_patient_record($data) {
        if (!$this->config['save_to_database']) {
            return;
        }

        global $wpdb;

        $table_name = $wpdb->prefix . 'consultation_requests';

        // Create table if it doesn't exist
        $this->create_consultation_table();

        // Insert consultation record
        $wpdb->insert(
            $table_name,
            [
                'first_name' => sanitize_text_field($data['first_name']),
                'last_name' => sanitize_text_field($data['last_name']),
                'email' => sanitize_email($data['email']),
                'phone' => sanitize_text_field($data['phone']),
                'age' => intval($data['age']),
                'consultation_type' => sanitize_text_field($data['consultation_type']),
                'treatment_interest' => sanitize_text_field($data['treatment_interest']),
                'preferred_date' => sanitize_text_field($data['preferred_date']),
                'preferred_time' => sanitize_text_field($data['preferred_time']),
                'skin_concerns' => is_array($data['skin_concerns']) ? implode(',', $data['skin_concerns']) : sanitize_text_field($data['skin_concerns']),
                'previous_treatments' => sanitize_textarea_field($data['previous_treatments']),
                'current_skincare' => sanitize_textarea_field($data['current_skincare']),
                'allergies_medications' => sanitize_textarea_field($data['allergies_medications']),
                'budget_range' => sanitize_text_field($data['budget_range']),
                'how_heard' => sanitize_text_field($data['how_heard']),
                'special_requests' => sanitize_textarea_field($data['special_requests']),
                'consent_privacy' => !empty($data['consent_privacy']) ? 1 : 0,
                'consent_marketing' => !empty($data['consent_marketing']) ? 1 : 0,
                'status' => 'pending',
                'created_at' => current_time('mysql')
            ],
            [
                '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s',
                '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s'
            ]
        );
    }

    /**
     * Create consultation requests table
     */
    protected function create_consultation_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'consultation_requests';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            first_name varchar(50) NOT NULL,
            last_name varchar(50) NOT NULL,
            email varchar(100) NOT NULL,
            phone varchar(20) NOT NULL,
            age int(3) NOT NULL,
            consultation_type varchar(50) NOT NULL,
            treatment_interest varchar(100) NOT NULL,
            preferred_date date NOT NULL,
            preferred_time varchar(10) NOT NULL,
            skin_concerns text,
            previous_treatments text,
            current_skincare text,
            allergies_medications text,
            budget_range varchar(50),
            how_heard varchar(50),
            special_requests text,
            consent_privacy tinyint(1) NOT NULL DEFAULT 0,
            consent_marketing tinyint(1) NOT NULL DEFAULT 0,
            status varchar(20) NOT NULL DEFAULT 'pending',
            created_at datetime NOT NULL,
            updated_at datetime DEFAULT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Send auto-reply email to patient
     *
     * @param array $data Form data
     */
    protected function send_auto_reply($data) {
        if (empty($data['email'])) {
            return false;
        }

        $subject = 'Consultation Request Received - ' . get_bloginfo('name');

        $message = $this->format_auto_reply_message($data);

        $headers = ['Content-Type: text/html; charset=UTF-8'];

        return wp_mail($data['email'], $subject, $message, $headers);
    }

    /**
     * Format auto-reply email message
     *
     * @param array $data Form data
     * @return string Formatted email content
     */
    protected function format_auto_reply_message($data) {
        $message = '<h2>Thank You for Your Consultation Request</h2>';

        $message .= '<p>Dear ' . esc_html($data['first_name']) . ',</p>';

        $message .= '<p>Thank you for your interest in our services. We have received your consultation request and will contact you within 24 hours to confirm your appointment.</p>';

        $message .= '<h3>Your Request Details:</h3>';
        $message .= '<ul>';
        $message .= '<li><strong>Consultation Type:</strong> ' . esc_html($data['consultation_type']) . '</li>';
        $message .= '<li><strong>Treatment Interest:</strong> ' . esc_html($data['treatment_interest']) . '</li>';
        $message .= '<li><strong>Preferred Date:</strong> ' . esc_html($data['preferred_date']) . '</li>';
        $message .= '<li><strong>Preferred Time:</strong> ' . esc_html($data['preferred_time']) . '</li>';
        $message .= '</ul>';

        $message .= '<h3>What to Expect:</h3>';
        $message .= '<ul>';
        $message .= '<li>A member of our team will call you to confirm your appointment</li>';
        $message .= '<li>We will discuss your goals and answer any initial questions</li>';
        $message .= '<li>Please arrive 15 minutes early for your appointment</li>';
        $message .= '<li>Bring a list of current medications and skincare products</li>';
        $message .= '</ul>';

        $message .= '<p>If you need to make changes to your request or have urgent questions, please call us at <strong>' . get_option('consultation_phone', '(555) 123-4567') . '</strong>.</p>';

        $message .= '<p>We look forward to meeting you!</p>';

        $message .= '<p>Best regards,<br>';
        $message .= get_bloginfo('name') . ' Team</p>';

        return $message;
    }
}
