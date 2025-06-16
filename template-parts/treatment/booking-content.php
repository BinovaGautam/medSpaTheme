<?php
/**
 * Treatment Booking Content - Enhanced Integration
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get booking configuration
$booking_enabled = get_field('enable_online_booking') ?? true;
$consultation_required = get_field('consultation_required') ?? true;
$booking_notice = get_field('booking_notice') ?: '';
$treatment_title = get_the_title();
$treatment_duration = get_field('treatment_duration') ?: '30-45 minutes';
$treatment_price = get_field('treatment_price') ?: '';

// Get available time slots (this would typically come from a booking system API)
$available_time_slots = [
    'morning' => ['9:00 AM', '9:30 AM', '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM'],
    'afternoon' => ['12:00 PM', '12:30 PM', '1:00 PM', '1:30 PM', '2:00 PM', '2:30 PM', '3:00 PM', '3:30 PM', '4:00 PM', '4:30 PM'],
    'evening' => ['5:00 PM', '5:30 PM', '6:00 PM', '6:30 PM', '7:00 PM', '7:30 PM']
];
?>

<div class="treatment-booking" id="booking-content" role="tabpanel" aria-labelledby="tab-book">

    <div class="treatment-booking__header">
        <h3 class="treatment-booking__heading">Book Your <?php echo esc_html($treatment_title); ?></h3>
        <p class="treatment-booking__description">
            Ready to begin your transformation? Schedule your appointment with our experienced team.
        </p>

        <?php if ($treatment_duration || $treatment_price): ?>
            <div class="treatment-booking__summary">
                <?php
                echo ComponentRegistry::render('card', [
                    'variant' => 'outlined',
                    'size' => 'small',
                    'content' => '<div class="booking-summary">' .
                        ($treatment_duration ? '<div class="summary-item"><span class="summary-icon">⏱️</span><span class="summary-text">Duration: ' . esc_html($treatment_duration) . '</span></div>' : '') .
                        ($treatment_price ? '<div class="summary-item"><span class="summary-icon">💰</span><span class="summary-text">Starting at: ' . esc_html($treatment_price) . '</span></div>' : '') .
                        '<div class="summary-item"><span class="summary-icon">👨‍⚕️</span><span class="summary-text">Professional consultation included</span></div>' .
                        '</div>',
                    'classes' => ['treatment-booking__summary-card']
                ]);
                ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($booking_notice): ?>
        <div class="treatment-booking__notice">
            <?php
            echo ComponentRegistry::render('card', [
                'variant' => 'outlined',
                'size' => 'medium',
                'title' => 'Important Notice',
                'icon' => 'ℹ️',
                'content' => wp_kses_post($booking_notice),
                'classes' => ['treatment-booking__notice-card'],
                'attributes' => [
                    'role' => 'note',
                    'aria-live' => 'polite'
                ]
            ]);
            ?>
        </div>
    <?php endif; ?>

    <div class="treatment-booking__content">

        <?php if ($booking_enabled): ?>

            <div class="treatment-booking__form-section">

                <!-- Step 1: Date & Time Selection -->
                <div class="booking-step" id="step-datetime" data-step="1">
                    <?php
                    echo ComponentRegistry::render('card', [
                        'variant' => 'elevated',
                        'size' => 'large',
                        'title' => 'Step 1: Choose Date & Time',
                        'icon' => '📅',
                        'content' => '<div class="datetime-selection">' .
                            '<div class="date-picker-container">' .
                                '<label for="booking-date" class="date-label">Select Date:</label>' .
                                '<input type="date" id="booking-date" name="booking_date" class="date-picker" min="' . date('Y-m-d', strtotime('+1 day')) . '" max="' . date('Y-m-d', strtotime('+3 months')) . '" required>' .
                            '</div>' .
                            '<div class="time-slots-container" id="time-slots-container" style="display: none;">' .
                                '<label class="time-label">Available Times:</label>' .
                                '<div class="time-slots-grid" id="time-slots-grid">' .
                                    '<!-- Time slots will be populated via JavaScript -->' .
                                '</div>' .
                            '</div>' .
                        '</div>',
                        'classes' => ['treatment-booking__datetime-card']
                    ]);
                    ?>
                </div>

                <!-- Step 2: Personal Information -->
                <div class="booking-step" id="step-personal" data-step="2" style="display: none;">
                    <?php
                    echo ComponentRegistry::render('form', [
                        'id' => 'treatment-booking-form',
                        'title' => 'Step 2: Your Information',
                        'description' => 'Please provide your contact details for appointment confirmation.',
                        'fields' => [
                            [
                                'type' => 'text',
                                'name' => 'full_name',
                                'label' => 'Full Name',
                                'required' => true,
                                'placeholder' => 'Enter your full name',
                                'validation' => 'required|min:2',
                                'icon' => '👤'
                            ],
                            [
                                'type' => 'email',
                                'name' => 'email',
                                'label' => 'Email Address',
                                'required' => true,
                                'placeholder' => 'Enter your email address',
                                'validation' => 'required|email',
                                'icon' => '📧'
                            ],
                            [
                                'type' => 'tel',
                                'name' => 'phone',
                                'label' => 'Phone Number',
                                'required' => true,
                                'placeholder' => 'Enter your phone number',
                                'validation' => 'required|phone',
                                'icon' => '📞'
                            ],
                            [
                                'type' => 'select',
                                'name' => 'how_did_you_hear',
                                'label' => 'How did you hear about us?',
                                'required' => false,
                                'options' => [
                                    '' => 'Please select...',
                                    'google' => 'Google Search',
                                    'social_media' => 'Social Media',
                                    'referral' => 'Friend/Family Referral',
                                    'advertisement' => 'Advertisement',
                                    'other' => 'Other'
                                ]
                            ],
                            [
                                'type' => 'textarea',
                                'name' => 'message',
                                'label' => 'Additional Information',
                                'required' => false,
                                'placeholder' => 'Any questions, concerns, or special requests?',
                                'rows' => 4
                            ],
                            [
                                'type' => 'hidden',
                                'name' => 'treatment_id',
                                'value' => get_the_ID()
                            ],
                            [
                                'type' => 'hidden',
                                'name' => 'treatment_name',
                                'value' => $treatment_title
                            ],
                            [
                                'type' => 'hidden',
                                'name' => 'selected_date',
                                'value' => ''
                            ],
                            [
                                'type' => 'hidden',
                                'name' => 'selected_time',
                                'value' => ''
                            ]
                        ],
                        'submit_button' => [
                            'text' => $consultation_required ? 'Request Consultation' : 'Book Appointment',
                            'variant' => 'primary',
                            'size' => 'large',
                            'icon' => '📅'
                        ],
                        'ajax_enabled' => true,
                        'success_message' => 'Thank you! We\'ll contact you within 24 hours to confirm your appointment.',
                        'classes' => ['treatment-booking__form'],
                        'attributes' => [
                            'data-treatment-id' => get_the_ID(),
                            'data-consultation-required' => $consultation_required ? 'true' : 'false'
                        ]
                    ]);
                    ?>
                </div>

                <!-- Step 3: Confirmation -->
                <div class="booking-step" id="step-confirmation" data-step="3" style="display: none;">
                    <?php
                    echo ComponentRegistry::render('card', [
                        'variant' => 'filled',
                        'size' => 'large',
                        'title' => 'Step 3: Confirm Your Appointment',
                        'icon' => '✅',
                        'content' => '<div class="booking-confirmation">' .
                            '<div class="confirmation-details" id="confirmation-details">' .
                                '<!-- Confirmation details will be populated via JavaScript -->' .
                            '</div>' .
                            '<div class="confirmation-actions">' .
                                '<button type="button" class="btn btn-secondary" id="edit-booking">Edit Details</button>' .
                                '<button type="submit" class="btn btn-primary" id="confirm-booking">Confirm Booking</button>' .
                            '</div>' .
                        '</div>',
                        'classes' => ['treatment-booking__confirmation-card']
                    ]);
                    ?>
                </div>

                <!-- Booking Progress Indicator -->
                <div class="booking-progress">
                    <div class="progress-steps">
                        <div class="progress-step active" data-step="1">
                            <span class="step-number">1</span>
                            <span class="step-label">Date & Time</span>
                        </div>
                        <div class="progress-step" data-step="2">
                            <span class="step-number">2</span>
                            <span class="step-label">Your Info</span>
                        </div>
                        <div class="progress-step" data-step="3">
                            <span class="step-number">3</span>
                            <span class="step-label">Confirm</span>
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 33.33%;"></div>
                    </div>
                </div>

            </div>

            <div class="treatment-booking__alternative">
                <div class="treatment-booking__divider">
                    <span class="treatment-booking__divider-text">Or</span>
                </div>

                <div class="treatment-booking__contact-options">
                    <h4 class="treatment-booking__contact-heading">Prefer to Call?</h4>
                    <p class="treatment-booking__contact-text">
                        Speak directly with our booking specialists for immediate assistance.
                    </p>

                    <div class="treatment-booking__contact-actions">
                        <?php
                        $phone = get_theme_mod('contact_phone', '');
                        if ($phone):
                            echo ComponentRegistry::render('button', [
                                'text' => 'Call Now: ' . $phone,
                                'url' => 'tel:' . preg_replace('/[^0-9+]/', '', $phone),
                                'variant' => 'outline',
                                'size' => 'large',
                                'icon' => '📞',
                                'icon_position' => 'left',
                                'classes' => ['treatment-booking__call-button']
                            ]);
                        endif;

                        echo ComponentRegistry::render('button', [
                            'text' => 'Live Chat',
                            'url' => '#',
                            'variant' => 'ghost',
                            'size' => 'large',
                            'icon' => '💬',
                            'icon_position' => 'left',
                            'classes' => ['treatment-booking__chat-button'],
                            'attributes' => [
                                'data-action' => 'open-chat'
                            ]
                        ]);
                        ?>
                    </div>
                </div>
            </div>

        <?php else: ?>

            <div class="treatment-booking__disabled">
                <?php
                echo ComponentRegistry::render('card', [
                    'variant' => 'filled',
                    'size' => 'large',
                    'title' => 'Consultation Required',
                    'icon' => '👨‍⚕️',
                    'content' => '<p>This treatment requires an in-person consultation to ensure it\'s right for you. Please contact us to schedule your consultation.</p>',
                    'classes' => ['treatment-booking__consultation-card']
                ]);
                ?>

                <div class="treatment-booking__contact-actions">
                    <?php
                    echo ComponentRegistry::render('button', [
                        'text' => 'Schedule Consultation',
                        'url' => get_permalink(get_page_by_path('contact')),
                        'variant' => 'primary',
                        'size' => 'large',
                        'icon' => '📅',
                        'icon_position' => 'left',
                        'classes' => ['treatment-booking__consultation-button']
                    ]);

                    $phone = get_theme_mod('contact_phone', '');
                    if ($phone):
                        echo ComponentRegistry::render('button', [
                            'text' => 'Call Us',
                            'url' => 'tel:' . preg_replace('/[^0-9+]/', '', $phone),
                            'variant' => 'outline',
                            'size' => 'large',
                            'icon' => '📞',
                            'icon_position' => 'left',
                            'classes' => ['treatment-booking__call-button']
                        ]);
                    endif;
                    ?>
                </div>
            </div>

        <?php endif; ?>

    </div>

    <div class="treatment-booking__footer">
        <div class="treatment-booking__guarantee">
            <?php
            echo ComponentRegistry::render('card', [
                'variant' => 'borderless',
                'size' => 'small',
                'title' => 'Our Promise',
                'icon' => '🛡️',
                'content' => '<p>Professional service, safe procedures, and your satisfaction guaranteed. Licensed practitioners with years of experience.</p>',
                'classes' => ['treatment-booking__guarantee-card']
            ]);
            ?>
        </div>

        <div class="treatment-booking__policies">
            <div class="policies-grid">
                <div class="policy-item">
                    <span class="policy-icon">🔒</span>
                    <span class="policy-text">HIPAA Compliant</span>
                </div>
                <div class="policy-item">
                    <span class="policy-icon">📋</span>
                    <span class="policy-text">Free Consultation</span>
                </div>
                <div class="policy-item">
                    <span class="policy-icon">🔄</span>
                    <span class="policy-text">Easy Rescheduling</span>
                </div>
                <div class="policy-item">
                    <span class="policy-icon">💯</span>
                    <span class="policy-text">Satisfaction Guaranteed</span>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Booking JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingSteps = document.querySelectorAll('.booking-step');
    const progressSteps = document.querySelectorAll('.progress-step');
    const progressFill = document.querySelector('.progress-fill');
    const dateInput = document.getElementById('booking-date');
    const timeSlotsContainer = document.getElementById('time-slots-container');
    const timeSlotsGrid = document.getElementById('time-slots-grid');

    let currentStep = 1;
    let selectedDate = '';
    let selectedTime = '';

    // Available time slots data
    const timeSlots = <?php echo json_encode($available_time_slots); ?>;

    // Date selection handler
    if (dateInput) {
        dateInput.addEventListener('change', function() {
            selectedDate = this.value;
            if (selectedDate) {
                showTimeSlots();
                updateHiddenField('selected_date', selectedDate);
            }
        });
    }

    // Show time slots
    function showTimeSlots() {
        timeSlotsContainer.style.display = 'block';
        populateTimeSlots();
    }

    // Populate time slots
    function populateTimeSlots() {
        let slotsHTML = '';

        Object.keys(timeSlots).forEach(period => {
            slotsHTML += `<div class="time-period">`;
            slotsHTML += `<h5 class="period-title">${period.charAt(0).toUpperCase() + period.slice(1)}</h5>`;
            slotsHTML += `<div class="time-buttons">`;

            timeSlots[period].forEach(time => {
                slotsHTML += `<button type="button" class="time-slot-btn" data-time="${time}">${time}</button>`;
            });

            slotsHTML += `</div></div>`;
        });

        timeSlotsGrid.innerHTML = slotsHTML;

        // Add click handlers to time slot buttons
        document.querySelectorAll('.time-slot-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.time-slot-btn').forEach(b => b.classList.remove('active'));

                // Add active class to clicked button
                this.classList.add('active');

                selectedTime = this.dataset.time;
                updateHiddenField('selected_time', selectedTime);

                // Enable next step
                enableNextStep();
            });
        });
    }

    // Enable next step button
    function enableNextStep() {
        if (selectedDate && selectedTime) {
            // Show next step button or automatically advance
            setTimeout(() => {
                nextStep();
            }, 500);
        }
    }

    // Navigate to next step
    function nextStep() {
        if (currentStep < 3) {
            // Hide current step
            bookingSteps[currentStep - 1].style.display = 'none';

            // Show next step
            currentStep++;
            bookingSteps[currentStep - 1].style.display = 'block';

            // Update progress
            updateProgress();

            // If step 3, populate confirmation
            if (currentStep === 3) {
                populateConfirmation();
            }
        }
    }

    // Update progress indicator
    function updateProgress() {
        progressSteps.forEach((step, index) => {
            if (index < currentStep) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });

        const progressPercentage = (currentStep / 3) * 100;
        progressFill.style.width = progressPercentage + '%';
    }

    // Populate confirmation details
    function populateConfirmation() {
        const confirmationDetails = document.getElementById('confirmation-details');
        const fullName = document.querySelector('input[name="full_name"]')?.value || '';
        const email = document.querySelector('input[name="email"]')?.value || '';
        const phone = document.querySelector('input[name="phone"]')?.value || '';

        const confirmationHTML = `
            <div class="confirmation-item">
                <strong>Treatment:</strong> <?php echo esc_js($treatment_title); ?>
            </div>
            <div class="confirmation-item">
                <strong>Date:</strong> ${formatDate(selectedDate)}
            </div>
            <div class="confirmation-item">
                <strong>Time:</strong> ${selectedTime}
            </div>
            <div class="confirmation-item">
                <strong>Name:</strong> ${fullName}
            </div>
            <div class="confirmation-item">
                <strong>Email:</strong> ${email}
            </div>
            <div class="confirmation-item">
                <strong>Phone:</strong> ${phone}
            </div>
        `;

        confirmationDetails.innerHTML = confirmationHTML;
    }

    // Format date for display
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    // Update hidden form fields
    function updateHiddenField(name, value) {
        const field = document.querySelector(`input[name="${name}"]`);
        if (field) {
            field.value = value;
        }
    }

    // Edit booking handler
    const editButton = document.getElementById('edit-booking');
    if (editButton) {
        editButton.addEventListener('click', function() {
            currentStep = 1;
            bookingSteps.forEach((step, index) => {
                step.style.display = index === 0 ? 'block' : 'none';
            });
            updateProgress();
        });
    }
});
</script>
