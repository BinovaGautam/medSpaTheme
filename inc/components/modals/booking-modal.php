<?php
/**
 * Booking Modal Component
 *
 * Specialized modal for medical spa consultation booking with
 * form integration and medical spa specific features.
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
 * BookingModal Class
 *
 * Extends ModalComponent for consultation booking functionality
 */
class BookingModal extends ModalComponent {

    /**
     * Available treatment types for booking
     * @var array
     */
    protected $treatment_types = [
        'botox' => 'Botox Treatment',
        'dermal_fillers' => 'Dermal Fillers',
        'chemical_peels' => 'Chemical Peels',
        'microneedling' => 'Microneedling',
        'laser_resurfacing' => 'Laser Resurfacing',
        'ipl_photofacial' => 'IPL Photofacial',
        'coolsculpting' => 'CoolSculpting',
        'hydrafacial' => 'HydraFacial',
        'prp_facial' => 'PRP Facial',
        'consultation' => 'Initial Consultation'
    ];

    /**
     * Available time slots
     * @var array
     */
    protected $time_slots = [
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

    /**
     * Get component default configuration
     *
     * @return array Default configuration
     */
    public function get_defaults() {
        return array_merge(parent::get_defaults(), [
            'modal_type' => 'booking',
            'modal_class' => 'modal-booking',
            'modal_size' => 'large',
            'title' => 'Book Your Consultation',
            'aria_label' => 'Consultation booking modal',

            // Booking specific settings
            'treatment_type' => 'consultation',
            'preferred_date' => '',
            'preferred_time' => '',
            'practitioner_preference' => '',
            'enable_calendar' => true,
            'enable_treatment_selection' => true,
            'enable_practitioner_selection' => true,
            'auto_confirm' => false,
            'send_confirmation_email' => true,

            // Form integration
            'form_id' => 'booking-form',
            'form_action' => admin_url('admin-ajax.php'),
            'form_method' => 'POST',
            'ajax_submission' => true,

            // Medical spa settings
            'require_insurance' => false,
            'collect_medical_history' => true,
            'privacy_consent_required' => true,
            'marketing_consent_optional' => true
        ]);
    }

    /**
     * Render booking modal
     *
     * @param array $args Component arguments
     * @return string HTML output
     */
    public function render($args = []) {
        $config = wp_parse_args($args, $this->get_defaults());
        $config = $this->sanitize_booking_config($config);

        // Generate booking form content
        $booking_form = $this->generate_booking_form($config);
        $config['content'] = $booking_form;

        // Generate modal footer with actions
        $config['footer_content'] = $this->generate_booking_footer($config);

        return parent::render($config);
    }

    /**
     * Generate booking form HTML
     *
     * @param array $config Booking configuration
     * @return string Form HTML
     */
    protected function generate_booking_form($config) {
        $form_id = $config['form_id'];

        ob_start();
        ?>
        <form id="<?php echo esc_attr($form_id); ?>"
              class="booking-modal-form"
              action="<?php echo esc_url($config['form_action']); ?>"
              method="<?php echo esc_attr($config['form_method']); ?>"
              <?php if ($config['ajax_submission']): ?>data-ajax-form="true"<?php endif; ?>>

            <?php wp_nonce_field('booking_modal_submit', 'booking_nonce'); ?>
            <input type="hidden" name="action" value="submit_booking_modal">

            <div class="booking-form-sections">

                <!-- Personal Information Section -->
                <div class="booking-section booking-personal-info">
                    <h3 class="booking-section-title">
                        <span class="booking-section-number">1</span>
                        Personal Information
                    </h3>

                    <div class="booking-form-row">
                        <div class="booking-form-field">
                            <label for="booking-first-name" class="required">First Name</label>
                            <input type="text"
                                   id="booking-first-name"
                                   name="first_name"
                                   required
                                   autocomplete="given-name"
                                   aria-describedby="booking-first-name-help">
                            <div id="booking-first-name-help" class="field-help">Enter your first name</div>
                        </div>

                        <div class="booking-form-field">
                            <label for="booking-last-name" class="required">Last Name</label>
                            <input type="text"
                                   id="booking-last-name"
                                   name="last_name"
                                   required
                                   autocomplete="family-name"
                                   aria-describedby="booking-last-name-help">
                            <div id="booking-last-name-help" class="field-help">Enter your last name</div>
                        </div>
                    </div>

                    <div class="booking-form-row">
                        <div class="booking-form-field">
                            <label for="booking-email" class="required">Email Address</label>
                            <input type="email"
                                   id="booking-email"
                                   name="email"
                                   required
                                   autocomplete="email"
                                   aria-describedby="booking-email-help">
                            <div id="booking-email-help" class="field-help">We'll send your confirmation here</div>
                        </div>

                        <div class="booking-form-field">
                            <label for="booking-phone" class="required">Phone Number</label>
                            <input type="tel"
                                   id="booking-phone"
                                   name="phone"
                                   required
                                   autocomplete="tel"
                                   aria-describedby="booking-phone-help">
                            <div id="booking-phone-help" class="field-help">For appointment confirmations</div>
                        </div>
                    </div>
                </div>

                <!-- Treatment Selection Section -->
                <?php if ($config['enable_treatment_selection']): ?>
                <div class="booking-section booking-treatment-selection">
                    <h3 class="booking-section-title">
                        <span class="booking-section-number">2</span>
                        Treatment Selection
                    </h3>

                    <div class="booking-form-field">
                        <label for="booking-treatment-type" class="required">Treatment of Interest</label>
                        <select id="booking-treatment-type"
                                name="treatment_type"
                                required
                                aria-describedby="booking-treatment-help">
                            <option value="">Select a treatment...</option>
                            <?php foreach ($this->treatment_types as $value => $label): ?>
                                <option value="<?php echo esc_attr($value); ?>"
                                        <?php selected($config['treatment_type'], $value); ?>>
                                    <?php echo esc_html($label); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div id="booking-treatment-help" class="field-help">Choose the treatment you're interested in</div>
                    </div>

                    <div class="booking-form-field">
                        <label for="booking-treatment-notes">Additional Details</label>
                        <textarea id="booking-treatment-notes"
                                  name="treatment_notes"
                                  rows="3"
                                  aria-describedby="booking-treatment-notes-help"
                                  placeholder="Tell us about your goals, concerns, or questions..."></textarea>
                        <div id="booking-treatment-notes-help" class="field-help">Share any specific concerns or goals</div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Appointment Scheduling Section -->
                <div class="booking-section booking-appointment-scheduling">
                    <h3 class="booking-section-title">
                        <span class="booking-section-number">3</span>
                        Preferred Appointment Time
                    </h3>

                    <div class="booking-form-row">
                        <div class="booking-form-field">
                            <label for="booking-preferred-date" class="required">Preferred Date</label>
                            <input type="date"
                                   id="booking-preferred-date"
                                   name="preferred_date"
                                   required
                                   min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                                   max="<?php echo date('Y-m-d', strtotime('+90 days')); ?>"
                                   value="<?php echo esc_attr($config['preferred_date']); ?>"
                                   aria-describedby="booking-date-help">
                            <div id="booking-date-help" class="field-help">Select your preferred appointment date</div>
                        </div>

                        <div class="booking-form-field">
                            <label for="booking-preferred-time" class="required">Preferred Time</label>
                            <select id="booking-preferred-time"
                                    name="preferred_time"
                                    required
                                    aria-describedby="booking-time-help">
                                <option value="">Select time...</option>
                                <?php foreach ($this->time_slots as $value => $label): ?>
                                    <option value="<?php echo esc_attr($value); ?>"
                                            <?php selected($config['preferred_time'], $value); ?>>
                                        <?php echo esc_html($label); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div id="booking-time-help" class="field-help">Choose your preferred time slot</div>
                        </div>
                    </div>

                    <div class="booking-form-field">
                        <label for="booking-alternative-times">Alternative Times</label>
                        <textarea id="booking-alternative-times"
                                  name="alternative_times"
                                  rows="2"
                                  aria-describedby="booking-alternative-help"
                                  placeholder="If your preferred time isn't available, what other times work for you?"></textarea>
                        <div id="booking-alternative-help" class="field-help">Provide backup options to help us schedule you sooner</div>
                    </div>
                </div>

                <!-- Practitioner Selection Section -->
                <?php if ($config['enable_practitioner_selection']): ?>
                <div class="booking-section booking-practitioner-selection">
                    <h3 class="booking-section-title">
                        <span class="booking-section-number">4</span>
                        Practitioner Preference
                    </h3>

                    <div class="booking-form-field">
                        <label for="booking-practitioner">Preferred Practitioner</label>
                        <select id="booking-practitioner"
                                name="practitioner_preference"
                                aria-describedby="booking-practitioner-help">
                            <option value="">No preference</option>
                            <?php echo $this->get_practitioner_options($config['practitioner_preference']); ?>
                        </select>
                        <div id="booking-practitioner-help" class="field-help">Choose a specific practitioner or leave blank for the next available</div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Medical Information Section -->
                <?php if ($config['collect_medical_history']): ?>
                <div class="booking-section booking-medical-info">
                    <h3 class="booking-section-title">
                        <span class="booking-section-number">5</span>
                        Medical Information
                    </h3>

                    <div class="booking-form-field">
                        <fieldset>
                            <legend>Do you have any of the following? (Check all that apply)</legend>
                            <div class="booking-checkbox-group">
                                <label class="booking-checkbox-label">
                                    <input type="checkbox" name="medical_conditions[]" value="allergies">
                                    <span class="checkbox-text">Known allergies</span>
                                </label>
                                <label class="booking-checkbox-label">
                                    <input type="checkbox" name="medical_conditions[]" value="pregnancy">
                                    <span class="checkbox-text">Currently pregnant or nursing</span>
                                </label>
                                <label class="booking-checkbox-label">
                                    <input type="checkbox" name="medical_conditions[]" value="medications">
                                    <span class="checkbox-text">Taking medications</span>
                                </label>
                                <label class="booking-checkbox-label">
                                    <input type="checkbox" name="medical_conditions[]" value="previous_treatments">
                                    <span class="checkbox-text">Previous cosmetic treatments</span>
                                </label>
                                <label class="booking-checkbox-label">
                                    <input type="checkbox" name="medical_conditions[]" value="none">
                                    <span class="checkbox-text">None of the above</span>
                                </label>
                            </div>
                        </fieldset>
                    </div>

                    <div class="booking-form-field">
                        <label for="booking-medical-details">Medical Details</label>
                        <textarea id="booking-medical-details"
                                  name="medical_details"
                                  rows="3"
                                  aria-describedby="booking-medical-details-help"
                                  placeholder="Please provide details about any checked items above..."></textarea>
                        <div id="booking-medical-details-help" class="field-help">Provide additional details to help us prepare for your visit</div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Consent and Privacy Section -->
                <div class="booking-section booking-consent">
                    <h3 class="booking-section-title">
                        <span class="booking-section-number">6</span>
                        Consent &amp; Privacy
                    </h3>

                    <?php if ($config['privacy_consent_required']): ?>
                    <div class="booking-form-field">
                        <label class="booking-checkbox-label required">
                            <input type="checkbox"
                                   name="privacy_consent"
                                   value="1"
                                   required
                                   aria-describedby="privacy-consent-help">
                            <span class="checkbox-text">
                                I consent to the collection and use of my personal information as described in the
                                <a href="<?php echo esc_url(get_privacy_policy_url()); ?>" target="_blank" rel="noopener">Privacy Policy</a>
                            </span>
                        </label>
                        <div id="privacy-consent-help" class="field-help">Required for appointment booking</div>
                    </div>
                    <?php endif; ?>

                    <?php if ($config['marketing_consent_optional']): ?>
                    <div class="booking-form-field">
                        <label class="booking-checkbox-label">
                            <input type="checkbox"
                                   name="marketing_consent"
                                   value="1"
                                   aria-describedby="marketing-consent-help">
                            <span class="checkbox-text">
                                I'd like to receive special offers and updates about treatments and services
                            </span>
                        </label>
                        <div id="marketing-consent-help" class="field-help">Optional - you can unsubscribe anytime</div>
                    </div>
                    <?php endif; ?>

                    <div class="booking-form-field">
                        <label class="booking-checkbox-label required">
                            <input type="checkbox"
                                   name="appointment_policy_consent"
                                   value="1"
                                   required
                                   aria-describedby="appointment-policy-help">
                            <span class="checkbox-text">
                                I understand the appointment booking and cancellation policy
                            </span>
                        </label>
                        <div id="appointment-policy-help" class="field-help">Required to complete booking</div>
                    </div>
                </div>
            </div>
        </form>
        <?php
        return ob_get_clean();
    }

    /**
     * Generate booking modal footer
     *
     * @param array $config Booking configuration
     * @return string Footer HTML
     */
    protected function generate_booking_footer($config) {
        ob_start();
        ?>
        <div class="booking-modal-actions">
            <button type="button"
                    class="button button-secondary"
                    data-modal-close>
                Cancel
            </button>
            <button type="submit"
                    class="button button-primary"
                    form="<?php echo esc_attr($config['form_id']); ?>"
                    id="booking-submit-btn">
                <span class="button-text">Book Consultation</span>
                <span class="button-loading" style="display: none;">
                    <span class="loading-spinner"></span>
                    Processing...
                </span>
            </button>
        </div>

        <div class="booking-modal-footer-note">
            <p class="booking-disclaimer">
                <strong>Note:</strong> This is a request for an appointment. We'll contact you within 24 hours to confirm your booking.
            </p>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Get practitioner options
     *
     * @param string $selected Selected practitioner
     * @return string Options HTML
     */
    protected function get_practitioner_options($selected = '') {
        // In a real implementation, this would query the database for practitioners
        $practitioners = [
            'dr-smith' => 'Dr. Sarah Smith',
            'dr-johnson' => 'Dr. Michael Johnson',
            'nurse-wilson' => 'Nurse Practitioner Lisa Wilson',
            'aesthetician-brown' => 'Licensed Aesthetician Emma Brown'
        ];

        $options = '';
        foreach ($practitioners as $value => $name) {
            $options .= sprintf(
                '<option value="%s" %s>%s</option>',
                esc_attr($value),
                selected($selected, $value, false),
                esc_html($name)
            );
        }

        return $options;
    }

    /**
     * Sanitize booking configuration
     *
     * @param array $config Configuration to sanitize
     * @return array Sanitized configuration
     */
    protected function sanitize_booking_config($config) {
        $config = parent::sanitize_config($config);

        // Sanitize booking-specific fields
        $config['treatment_type'] = sanitize_text_field($config['treatment_type']);
        $config['preferred_date'] = sanitize_text_field($config['preferred_date']);
        $config['preferred_time'] = sanitize_text_field($config['preferred_time']);
        $config['practitioner_preference'] = sanitize_text_field($config['practitioner_preference']);
        $config['form_id'] = sanitize_text_field($config['form_id']);
        $config['form_action'] = esc_url_raw($config['form_action']);
        $config['form_method'] = in_array($config['form_method'], ['GET', 'POST']) ? $config['form_method'] : 'POST';

        // Validate boolean fields
        $config['enable_calendar'] = (bool) $config['enable_calendar'];
        $config['enable_treatment_selection'] = (bool) $config['enable_treatment_selection'];
        $config['enable_practitioner_selection'] = (bool) $config['enable_practitioner_selection'];
        $config['auto_confirm'] = (bool) $config['auto_confirm'];
        $config['send_confirmation_email'] = (bool) $config['send_confirmation_email'];
        $config['ajax_submission'] = (bool) $config['ajax_submission'];
        $config['require_insurance'] = (bool) $config['require_insurance'];
        $config['collect_medical_history'] = (bool) $config['collect_medical_history'];
        $config['privacy_consent_required'] = (bool) $config['privacy_consent_required'];
        $config['marketing_consent_optional'] = (bool) $config['marketing_consent_optional'];

        return $config;
    }

    /**
     * Handle booking form submission
     *
     * @param array $form_data Submitted form data
     * @return array Response data
     */
    public static function handle_booking_submission($form_data) {
        // Verify nonce
        if (!wp_verify_nonce($form_data['booking_nonce'], 'booking_modal_submit')) {
            return [
                'success' => false,
                'message' => 'Security check failed. Please try again.'
            ];
        }

        // Sanitize and validate form data
        $booking_data = [
            'first_name' => sanitize_text_field($form_data['first_name']),
            'last_name' => sanitize_text_field($form_data['last_name']),
            'email' => sanitize_email($form_data['email']),
            'phone' => sanitize_text_field($form_data['phone']),
            'treatment_type' => sanitize_text_field($form_data['treatment_type']),
            'treatment_notes' => sanitize_textarea_field($form_data['treatment_notes']),
            'preferred_date' => sanitize_text_field($form_data['preferred_date']),
            'preferred_time' => sanitize_text_field($form_data['preferred_time']),
            'alternative_times' => sanitize_textarea_field($form_data['alternative_times']),
            'practitioner_preference' => sanitize_text_field($form_data['practitioner_preference']),
            'medical_conditions' => array_map('sanitize_text_field', (array) $form_data['medical_conditions']),
            'medical_details' => sanitize_textarea_field($form_data['medical_details']),
            'privacy_consent' => !empty($form_data['privacy_consent']),
            'marketing_consent' => !empty($form_data['marketing_consent']),
            'appointment_policy_consent' => !empty($form_data['appointment_policy_consent']),
            'submission_date' => current_time('mysql'),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        ];

        // Validate required fields
        $required_fields = ['first_name', 'last_name', 'email', 'phone', 'treatment_type', 'preferred_date', 'preferred_time'];
        foreach ($required_fields as $field) {
            if (empty($booking_data[$field])) {
                return [
                    'success' => false,
                    'message' => 'Please fill in all required fields.'
                ];
            }
        }

        // Validate email
        if (!is_email($booking_data['email'])) {
            return [
                'success' => false,
                'message' => 'Please enter a valid email address.'
            ];
        }

        // Validate date
        $preferred_date = strtotime($booking_data['preferred_date']);
        if (!$preferred_date || $preferred_date < strtotime('+1 day')) {
            return [
                'success' => false,
                'message' => 'Please select a valid future date.'
            ];
        }

        // Check consent requirements
        if (!$booking_data['privacy_consent'] || !$booking_data['appointment_policy_consent']) {
            return [
                'success' => false,
                'message' => 'Please accept all required consent agreements.'
            ];
        }

        // Save booking to database
        $booking_id = self::save_booking($booking_data);

        if (!$booking_id) {
            return [
                'success' => false,
                'message' => 'There was an error saving your booking. Please try again.'
            ];
        }

        // Send confirmation email
        self::send_booking_confirmation($booking_data, $booking_id);

        // Send admin notification
        self::send_admin_notification($booking_data, $booking_id);

        return [
            'success' => true,
            'message' => 'Your consultation request has been submitted successfully! We\'ll contact you within 24 hours to confirm your appointment.',
            'booking_id' => $booking_id
        ];
    }

    /**
     * Save booking to database
     *
     * @param array $booking_data Booking data
     * @return int|false Booking ID or false on failure
     */
    protected static function save_booking($booking_data) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'medspa_bookings';

        $result = $wpdb->insert(
            $table_name,
            [
                'first_name' => $booking_data['first_name'],
                'last_name' => $booking_data['last_name'],
                'email' => $booking_data['email'],
                'phone' => $booking_data['phone'],
                'treatment_type' => $booking_data['treatment_type'],
                'treatment_notes' => $booking_data['treatment_notes'],
                'preferred_date' => $booking_data['preferred_date'],
                'preferred_time' => $booking_data['preferred_time'],
                'alternative_times' => $booking_data['alternative_times'],
                'practitioner_preference' => $booking_data['practitioner_preference'],
                'medical_conditions' => json_encode($booking_data['medical_conditions']),
                'medical_details' => $booking_data['medical_details'],
                'privacy_consent' => $booking_data['privacy_consent'] ? 1 : 0,
                'marketing_consent' => $booking_data['marketing_consent'] ? 1 : 0,
                'appointment_policy_consent' => $booking_data['appointment_policy_consent'] ? 1 : 0,
                'status' => 'pending',
                'submission_date' => $booking_data['submission_date'],
                'ip_address' => $booking_data['ip_address'],
                'user_agent' => $booking_data['user_agent']
            ],
            [
                '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
                '%s', '%s', '%d', '%d', '%d', '%s', '%s', '%s', '%s'
            ]
        );

        return $result ? $wpdb->insert_id : false;
    }

    /**
     * Send booking confirmation email
     *
     * @param array $booking_data Booking data
     * @param int $booking_id Booking ID
     */
    protected static function send_booking_confirmation($booking_data, $booking_id) {
        $to = $booking_data['email'];
        $subject = 'Consultation Request Confirmation - ' . get_bloginfo('name');

        $message = "Dear {$booking_data['first_name']},\n\n";
        $message .= "Thank you for your consultation request! We've received the following information:\n\n";
        $message .= "Treatment of Interest: {$booking_data['treatment_type']}\n";
        $message .= "Preferred Date: " . date('F j, Y', strtotime($booking_data['preferred_date'])) . "\n";
        $message .= "Preferred Time: {$booking_data['preferred_time']}\n\n";
        $message .= "We will contact you within 24 hours to confirm your appointment.\n\n";
        $message .= "If you have any questions, please call us at " . get_option('medspa_phone', '(555) 123-4567') . "\n\n";
        $message .= "Best regards,\n";
        $message .= get_bloginfo('name');

        $headers = ['Content-Type: text/plain; charset=UTF-8'];

        wp_mail($to, $subject, $message, $headers);
    }

    /**
     * Send admin notification email
     *
     * @param array $booking_data Booking data
     * @param int $booking_id Booking ID
     */
    protected static function send_admin_notification($booking_data, $booking_id) {
        $admin_email = get_option('admin_email');
        $subject = 'New Consultation Request - Booking #' . $booking_id;

        $message = "A new consultation request has been submitted:\n\n";
        $message .= "Booking ID: {$booking_id}\n";
        $message .= "Name: {$booking_data['first_name']} {$booking_data['last_name']}\n";
        $message .= "Email: {$booking_data['email']}\n";
        $message .= "Phone: {$booking_data['phone']}\n";
        $message .= "Treatment: {$booking_data['treatment_type']}\n";
        $message .= "Preferred Date: " . date('F j, Y', strtotime($booking_data['preferred_date'])) . "\n";
        $message .= "Preferred Time: {$booking_data['preferred_time']}\n";

        if (!empty($booking_data['treatment_notes'])) {
            $message .= "Notes: {$booking_data['treatment_notes']}\n";
        }

        if (!empty($booking_data['medical_details'])) {
            $message .= "Medical Details: {$booking_data['medical_details']}\n";
        }

        $message .= "\nSubmitted: {$booking_data['submission_date']}\n";
        $message .= "View in admin: " . admin_url('admin.php?page=medspa-bookings&booking_id=' . $booking_id);

        $headers = ['Content-Type: text/plain; charset=UTF-8'];

        wp_mail($admin_email, $subject, $message, $headers);
    }

    /**
     * Get booking modal JavaScript for enhanced functionality
     *
     * @return string JavaScript code
     */
    public function get_booking_modal_script() {
        ob_start();
        ?>
        <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Booking modal specific JavaScript
            const bookingForm = document.querySelector('.booking-modal-form');
            if (!bookingForm) return;

            // Handle form submission
            bookingForm.addEventListener('submit', function(e) {
                if (bookingForm.dataset.ajaxForm === 'true') {
                    e.preventDefault();
                    handleBookingSubmission(this);
                }
            });

            // Handle treatment type change
            const treatmentSelect = document.getElementById('booking-treatment-type');
            if (treatmentSelect) {
                treatmentSelect.addEventListener('change', function() {
                    updateTreatmentInfo(this.value);
                });
            }

            // Handle medical conditions "none" checkbox
            const noneCheckbox = document.querySelector('input[name="medical_conditions[]"][value="none"]');
            const otherCheckboxes = document.querySelectorAll('input[name="medical_conditions[]"]:not([value="none"])');

            if (noneCheckbox) {
                noneCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        otherCheckboxes.forEach(cb => cb.checked = false);
                    }
                });

                otherCheckboxes.forEach(cb => {
                    cb.addEventListener('change', function() {
                        if (this.checked) {
                            noneCheckbox.checked = false;
                        }
                    });
                });
            }

            function handleBookingSubmission(form) {
                const submitBtn = document.getElementById('booking-submit-btn');
                const buttonText = submitBtn.querySelector('.button-text');
                const buttonLoading = submitBtn.querySelector('.button-loading');

                // Show loading state
                submitBtn.disabled = true;
                buttonText.style.display = 'none';
                buttonLoading.style.display = 'inline-flex';

                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showBookingSuccess(data.message);
                        form.reset();
                    } else {
                        showBookingError(data.message);
                    }
                })
                .catch(error => {
                    showBookingError('An error occurred. Please try again.');
                })
                .finally(() => {
                    // Reset button state
                    submitBtn.disabled = false;
                    buttonText.style.display = 'inline';
                    buttonLoading.style.display = 'none';
                });
            }

            function updateTreatmentInfo(treatmentType) {
                // Could add treatment-specific information or form fields
                console.log('Treatment type selected:', treatmentType);
            }

            function showBookingSuccess(message) {
                // Create success notification
                const notification = document.createElement('div');
                notification.className = 'booking-notification booking-success';
                notification.innerHTML = `
                    <div class="notification-icon">✓</div>
                    <div class="notification-message">${message}</div>
                `;

                const modalBody = document.querySelector('.modal-booking .modal-body');
                modalBody.innerHTML = '';
                modalBody.appendChild(notification);

                // Auto-close modal after 3 seconds
                setTimeout(() => {
                    if (window.modalManager) {
                        window.modalManager.closeModal(document.querySelector('.modal-booking').id);
                    }
                }, 3000);
            }

            function showBookingError(message) {
                // Show error message
                let errorDiv = document.querySelector('.booking-error-message');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'booking-error-message';
                    bookingForm.insertBefore(errorDiv, bookingForm.firstChild);
                }

                errorDiv.innerHTML = `
                    <div class="error-icon">⚠</div>
                    <div class="error-text">${message}</div>
                `;

                // Scroll to error
                errorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });
        </script>
        <?php
        return ob_get_clean();
    }
}
