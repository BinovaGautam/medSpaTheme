/**
 * Enhanced Booking Integration JavaScript
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Booking Integration Class
    class BookingIntegration {
        constructor() {
            this.currentStep = 1;
            this.maxSteps = 3;
            this.selectedDate = '';
            this.selectedTime = '';
            this.formData = {};
            this.isSubmitting = false;

            this.init();
        }

        init() {
            this.bindEvents();
            this.initializeForm();
            this.setupValidation();
        }

        bindEvents() {
            // Date selection
            $(document).on('change', '#booking-date', (e) => {
                this.handleDateSelection(e);
            });

            // Time slot selection
            $(document).on('click', '.time-slot-btn', (e) => {
                this.handleTimeSelection(e);
            });

            // Form submission
            $(document).on('submit', '#treatment-booking-form', (e) => {
                this.handleFormSubmission(e);
            });

            // Step navigation
            $(document).on('click', '#edit-booking', (e) => {
                this.goToStep(1);
            });

            $(document).on('click', '#confirm-booking', (e) => {
                this.confirmBooking(e);
            });

            // Real-time validation
            $(document).on('blur', '#treatment-booking-form input, #treatment-booking-form textarea', (e) => {
                this.validateField(e.target);
            });

            // Accessibility: keyboard navigation for time slots
            $(document).on('keydown', '.time-slot-btn', (e) => {
                this.handleTimeSlotKeyboard(e);
            });
        }

        initializeForm() {
            // Set minimum date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const minDate = tomorrow.toISOString().split('T')[0];
            $('#booking-date').attr('min', minDate);

            // Set maximum date to 3 months from now
            const maxDate = new Date();
            maxDate.setMonth(maxDate.getMonth() + 3);
            $('#booking-date').attr('max', maxDate.toISOString().split('T')[0]);

            // Initialize progress
            this.updateProgress();
        }

        handleDateSelection(e) {
            const selectedDate = e.target.value;

            if (!selectedDate) {
                this.hideTimeSlots();
                return;
            }

            // Validate date
            if (!this.isValidDate(selectedDate)) {
                this.showError('Please select a valid weekday within the next 3 months.');
                e.target.value = '';
                return;
            }

            this.selectedDate = selectedDate;
            this.updateHiddenField('selected_date', selectedDate);

            // Show loading state
            this.showTimeSlotLoading();

            // Fetch available time slots
            this.fetchAvailableTimeSlots(selectedDate);
        }

        isValidDate(dateString) {
            const date = new Date(dateString);
            const today = new Date();
            const maxDate = new Date();
            maxDate.setMonth(maxDate.getMonth() + 3);

            // Check if date is in the future
            if (date <= today) return false;

            // Check if date is within 3 months
            if (date > maxDate) return false;

            // Check if it's a weekday (Monday = 1, Sunday = 0)
            const dayOfWeek = date.getDay();
            if (dayOfWeek === 0 || dayOfWeek === 6) return false;

            return true;
        }

        fetchAvailableTimeSlots(date) {
            const treatmentId = $('input[name="treatment_id"]').val();

            $.ajax({
                url: bookingAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'get_available_time_slots',
                    date: date,
                    treatment_id: treatmentId,
                    nonce: bookingAjax.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.displayTimeSlots(response.data.time_slots);
                    } else {
                        this.showError('Failed to load available time slots. Please try again.');
                        this.hideTimeSlots();
                    }
                },
                error: () => {
                    this.showError('Network error. Please check your connection and try again.');
                    this.hideTimeSlots();
                }
            });
        }

        displayTimeSlots(timeSlots) {
            const container = $('#time-slots-grid');
            let html = '';

            Object.keys(timeSlots).forEach(period => {
                if (timeSlots[period].length > 0) {
                    html += `<div class="time-period">`;
                    html += `<h5 class="period-title">${this.capitalizeFirst(period)}</h5>`;
                    html += `<div class="time-buttons">`;

                    timeSlots[period].forEach(time => {
                        html += `<button type="button" class="time-slot-btn" data-time="${time}" aria-label="Select ${time}">${time}</button>`;
                    });

                    html += `</div></div>`;
                }
            });

            if (html === '') {
                html = '<p class="no-slots-message">No available time slots for this date. Please select another date.</p>';
            }

            container.html(html);
            this.showTimeSlots();
        }

        showTimeSlotLoading() {
            const container = $('#time-slots-grid');
            container.html('<div class="loading-spinner">Loading available times...</div>');
            this.showTimeSlots();
        }

        showTimeSlots() {
            $('#time-slots-container').slideDown(300);
        }

        hideTimeSlots() {
            $('#time-slots-container').slideUp(300);
            this.selectedTime = '';
            this.updateHiddenField('selected_time', '');
        }

        handleTimeSelection(e) {
            e.preventDefault();

            const button = $(e.target);
            const time = button.data('time');

            // Remove active class from all buttons
            $('.time-slot-btn').removeClass('active').attr('aria-pressed', 'false');

            // Add active class to selected button
            button.addClass('active').attr('aria-pressed', 'true');

            this.selectedTime = time;
            this.updateHiddenField('selected_time', time);

            // Auto-advance to next step after a short delay
            setTimeout(() => {
                this.goToStep(2);
            }, 500);
        }

        handleTimeSlotKeyboard(e) {
            const button = $(e.target);

            switch(e.key) {
                case 'Enter':
                case ' ':
                    e.preventDefault();
                    button.click();
                    break;
                case 'ArrowRight':
                case 'ArrowDown':
                    e.preventDefault();
                    this.focusNextTimeSlot(button);
                    break;
                case 'ArrowLeft':
                case 'ArrowUp':
                    e.preventDefault();
                    this.focusPrevTimeSlot(button);
                    break;
            }
        }

        focusNextTimeSlot(current) {
            const timeSlots = $('.time-slot-btn');
            const currentIndex = timeSlots.index(current);
            const nextIndex = (currentIndex + 1) % timeSlots.length;
            timeSlots.eq(nextIndex).focus();
        }

        focusPrevTimeSlot(current) {
            const timeSlots = $('.time-slot-btn');
            const currentIndex = timeSlots.index(current);
            const prevIndex = currentIndex === 0 ? timeSlots.length - 1 : currentIndex - 1;
            timeSlots.eq(prevIndex).focus();
        }

        goToStep(stepNumber) {
            if (stepNumber < 1 || stepNumber > this.maxSteps) return;

            // Validate step requirements
            if (stepNumber === 2 && (!this.selectedDate || !this.selectedTime)) {
                this.showError(bookingAjax.messages.select_date);
                return;
            }

            if (stepNumber === 3 && !this.validatePersonalInfo()) {
                return;
            }

            // Hide all steps
            $('.booking-step').hide();

            // Show target step
            $(`.booking-step[data-step="${stepNumber}"]`).fadeIn(300);

            this.currentStep = stepNumber;
            this.updateProgress();

            // Populate confirmation if going to step 3
            if (stepNumber === 3) {
                this.populateConfirmation();
            }

            // Scroll to top of booking section
            $('html, body').animate({
                scrollTop: $('#booking-content').offset().top - 100
            }, 300);
        }

        validatePersonalInfo() {
            const form = $('#treatment-booking-form')[0];
            const requiredFields = form.querySelectorAll('input[required], textarea[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!this.validateField(field)) {
                    isValid = false;
                }
            });

            return isValid;
        }

        validateField(field) {
            const $field = $(field);
            const value = $field.val().trim();
            const fieldName = $field.attr('name');
            let isValid = true;
            let errorMessage = '';

            // Remove existing error styling
            $field.removeClass('error');
            $field.siblings('.error-message').remove();

            // Required field validation
            if ($field.prop('required') && !value) {
                isValid = false;
                errorMessage = 'This field is required.';
            }

            // Specific field validations
            switch(fieldName) {
                case 'full_name':
                    if (value && value.length < 2) {
                        isValid = false;
                        errorMessage = 'Name must be at least 2 characters long.';
                    }
                    break;

                case 'email':
                    if (value && !this.isValidEmail(value)) {
                        isValid = false;
                        errorMessage = 'Please enter a valid email address.';
                    }
                    break;

                case 'phone':
                    if (value && !this.isValidPhone(value)) {
                        isValid = false;
                        errorMessage = 'Please enter a valid phone number.';
                    }
                    break;
            }

            // Show error if invalid
            if (!isValid) {
                $field.addClass('error');
                $field.after(`<span class="error-message" role="alert">${errorMessage}</span>`);
            }

            return isValid;
        }

        isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        isValidPhone(phone) {
            const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
            const cleanPhone = phone.replace(/[^\d\+]/g, '');
            return phoneRegex.test(cleanPhone);
        }

        populateConfirmation() {
            const formData = this.getFormData();
            const confirmationDetails = $('#confirmation-details');

            const html = `
                <div class="confirmation-item">
                    <strong>Treatment:</strong> <span>${formData.treatment_name}</span>
                </div>
                <div class="confirmation-item">
                    <strong>Date:</strong> <span>${this.formatDate(this.selectedDate)}</span>
                </div>
                <div class="confirmation-item">
                    <strong>Time:</strong> <span>${this.selectedTime}</span>
                </div>
                <div class="confirmation-item">
                    <strong>Name:</strong> <span>${formData.full_name}</span>
                </div>
                <div class="confirmation-item">
                    <strong>Email:</strong> <span>${formData.email}</span>
                </div>
                <div class="confirmation-item">
                    <strong>Phone:</strong> <span>${formData.phone}</span>
                </div>
            `;

            confirmationDetails.html(html);
        }

        getFormData() {
            const form = $('#treatment-booking-form')[0];
            const formData = new FormData(form);
            const data = {};

            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }

            return data;
        }

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        handleFormSubmission(e) {
            e.preventDefault();

            if (this.currentStep !== 3) {
                // If not on confirmation step, validate and advance
                if (this.currentStep === 1 && this.selectedDate && this.selectedTime) {
                    this.goToStep(2);
                } else if (this.currentStep === 2 && this.validatePersonalInfo()) {
                    this.goToStep(3);
                }
                return;
            }

            // On confirmation step, submit the form
            this.confirmBooking(e);
        }

        confirmBooking(e) {
            e.preventDefault();

            if (this.isSubmitting) return;

            if (!confirm(bookingAjax.messages.confirm_booking)) {
                return;
            }

            this.isSubmitting = true;
            this.showSubmissionLoading();

            const formData = this.getFormData();
            formData.action = 'handle_treatment_booking';
            formData.nonce = bookingAjax.nonce;
            formData.selected_date = this.selectedDate;
            formData.selected_time = this.selectedTime;

            $.ajax({
                url: bookingAjax.ajaxurl,
                type: 'POST',
                data: formData,
                success: (response) => {
                    this.handleSubmissionResponse(response);
                },
                error: () => {
                    this.handleSubmissionError();
                },
                complete: () => {
                    this.isSubmitting = false;
                    this.hideSubmissionLoading();
                }
            });
        }

        handleSubmissionResponse(response) {
            if (response.success) {
                this.showSuccessMessage(response.data);
                this.resetForm();

                // Optionally redirect or show calendar integration
                if (response.data.calendar_event) {
                    this.offerCalendarDownload(response.data.calendar_event);
                }
            } else {
                this.showSubmissionError(response.data);
            }
        }

        handleSubmissionError() {
            this.showError(bookingAjax.messages.error);
        }

        showSubmissionLoading() {
            const button = $('#confirm-booking');
            button.prop('disabled', true).html('<span class="loading-spinner"></span> ' + bookingAjax.messages.loading);
        }

        hideSubmissionLoading() {
            const button = $('#confirm-booking');
            button.prop('disabled', false).html('Confirm Booking');
        }

        showSuccessMessage(data) {
            const message = `
                <div class="booking-success-message" role="alert" aria-live="polite">
                    <div class="success-icon">✅</div>
                    <h3>Booking Confirmed!</h3>
                    <p><strong>Reference:</strong> ${data.booking_reference}</p>
                    <p>${data.message}</p>
                    <div class="next-steps">
                        <h4>What's Next:</h4>
                        <ul>
                            ${data.next_steps.map(step => `<li>${step}</li>`).join('')}
                        </ul>
                    </div>
                </div>
            `;

            $('.treatment-booking__content').html(message);
        }

        showSubmissionError(data) {
            let errorHtml = `<div class="booking-error-message" role="alert">`;
            errorHtml += `<div class="error-icon">❌</div>`;
            errorHtml += `<h3>Booking Error</h3>`;
            errorHtml += `<p>${data.message || bookingAjax.messages.error}</p>`;

            if (data.errors) {
                errorHtml += `<ul class="error-list">`;
                Object.keys(data.errors).forEach(field => {
                    errorHtml += `<li>${data.errors[field]}</li>`;
                });
                errorHtml += `</ul>`;
            }

            if (data.suggested_times && data.suggested_times.length > 0) {
                errorHtml += `<div class="suggested-times">`;
                errorHtml += `<h4>Suggested Alternative Times:</h4>`;
                errorHtml += `<ul>`;
                data.suggested_times.forEach(slot => {
                    errorHtml += `<li>${this.formatDate(slot.date)} at ${slot.time}</li>`;
                });
                errorHtml += `</ul>`;
                errorHtml += `</div>`;
            }

            errorHtml += `</div>`;

            $('.booking-step[data-step="3"]').prepend(errorHtml);

            // Auto-remove error after 10 seconds
            setTimeout(() => {
                $('.booking-error-message').fadeOut();
            }, 10000);
        }

        offerCalendarDownload(eventData) {
            // Create ICS file content
            const icsContent = this.generateICSContent(eventData);
            const blob = new Blob([icsContent], { type: 'text/calendar' });
            const url = URL.createObjectURL(blob);

            const downloadLink = `<a href="${url}" download="appointment.ics" class="btn btn-secondary">📅 Add to Calendar</a>`;
            $('.booking-success-message').append(`<div class="calendar-download">${downloadLink}</div>`);
        }

        generateICSContent(eventData) {
            return `BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//MedSpa Theme//Booking System//EN
BEGIN:VEVENT
UID:${Date.now()}@medspatheme.com
DTSTAMP:${new Date().toISOString().replace(/[-:]/g, '').split('.')[0]}Z
DTSTART:${eventData.start}
DTEND:${eventData.end}
SUMMARY:${eventData.title}
DESCRIPTION:${eventData.description}
LOCATION:${eventData.location}
URL:${eventData.url}
END:VEVENT
END:VCALENDAR`;
        }

        resetForm() {
            $('#treatment-booking-form')[0].reset();
            this.currentStep = 1;
            this.selectedDate = '';
            this.selectedTime = '';
            $('.booking-step').hide();
            $('.booking-step[data-step="1"]').show();
            this.updateProgress();
            this.hideTimeSlots();
        }

        updateProgress() {
            // Update step indicators
            $('.progress-step').removeClass('active');
            for (let i = 1; i <= this.currentStep; i++) {
                $(`.progress-step[data-step="${i}"]`).addClass('active');
            }

            // Update progress bar
            const progressPercentage = (this.currentStep / this.maxSteps) * 100;
            $('.progress-fill').css('width', progressPercentage + '%');
        }

        updateHiddenField(name, value) {
            $(`input[name="${name}"]`).val(value);
        }

        showError(message) {
            // Remove existing error messages
            $('.booking-error-alert').remove();

            // Show new error message
            const errorHtml = `<div class="booking-error-alert" role="alert" aria-live="assertive">${message}</div>`;
            $('.treatment-booking__form-section').prepend(errorHtml);

            // Auto-remove after 5 seconds
            setTimeout(() => {
                $('.booking-error-alert').fadeOut();
            }, 5000);
        }

        capitalizeFirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    }

    // Initialize booking integration when DOM is ready
    $(document).ready(function() {
        if ($('#booking-content').length) {
            new BookingIntegration();
        }
    });

})(jQuery);
