/**
 * Medical Spa Booking Widget
 *
 * Handles consultation booking with HIPAA-conscious design
 * and accessibility compliance for medical spa appointments.
 *
 * @package PreetiDreams
 * @since 1.0.0
 */

import { announceToScreenReader } from '../utils/accessibility.js';

declare global {
  interface Window {
    preetidreamsBooking?: BookingWidget;
    gtag?: (...args: any[]) => void;
  }

  function gtag(...args: any[]): void;
}

interface BookingData {
  name: string;
  email: string;
  phone: string;
  treatment: string;
  preferredDate: string;
  preferredTime: string;
  message?: string;
  hipaaConsent: boolean;
  marketingConsent: boolean;
}

interface BookingWidgetConfig {
  element: HTMLElement;
  apiEndpoint?: string;
  treatments?: string[];
  requiredFields?: string[];
  hipaaRequired?: boolean;
  enableAnalytics?: boolean;
}

export class BookingWidget {
  private element: HTMLElement;
  private config: BookingWidgetConfig;
  private form: HTMLFormElement | null = null;
  private submitButton: HTMLButtonElement | null = null;
  private isSubmitting = false;

  constructor(config: BookingWidgetConfig) {
    this.element = config.element;
    this.config = {
      apiEndpoint: '/wp-json/preetidreams/v1/booking',
      treatments: [
        'Botox',
        'Dermal Fillers',
        'Laser Hair Removal',
        'HydraFacial',
        'Chemical Peels',
        'Microneedling',
        'CoolSculpting',
        'Consultation'
      ],
      requiredFields: ['name', 'email', 'phone', 'treatment', 'hipaaConsent'],
      hipaaRequired: true,
      enableAnalytics: true,
      ...config
    };

    this.init();
  }

  private init(): void {
    try {
      this.render();
      this.bindEvents();
      this.setupAccessibility();

      if (this.config.enableAnalytics) {
        this.trackWidgetLoad();
      }
    } catch (error) {
      console.error('BookingWidget initialization failed:', error);
      this.showError('Booking widget failed to load. Please try refreshing the page.');
    }
  }

  private render(): void {
    const widgetHTML = `
      <div class="booking-widget" role="region" aria-labelledby="booking-title">
        <div class="booking-header">
          <h3 id="booking-title" class="booking-title">
            <span class="booking-icon" aria-hidden="true">📅</span>
            Schedule Your Consultation
          </h3>
          <p class="booking-subtitle">
            Book your complimentary consultation with our medical aesthetics experts.
          </p>
        </div>

        <form class="booking-form" novalidate>
          <div class="form-row">
            <div class="form-group">
              <label for="booking-name" class="form-label required">
                Full Name
                <span class="required-indicator" aria-label="required">*</span>
              </label>
              <input
                type="text"
                id="booking-name"
                name="name"
                class="form-control"
                required
                aria-describedby="name-error"
                autocomplete="name"
              >
              <div id="name-error" class="error-message" role="alert" aria-live="polite"></div>
            </div>

            <div class="form-group">
              <label for="booking-email" class="form-label required">
                Email Address
                <span class="required-indicator" aria-label="required">*</span>
              </label>
              <input
                type="email"
                id="booking-email"
                name="email"
                class="form-control"
                required
                aria-describedby="email-error"
                autocomplete="email"
              >
              <div id="email-error" class="error-message" role="alert" aria-live="polite"></div>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="booking-phone" class="form-label required">
                Phone Number
                <span class="required-indicator" aria-label="required">*</span>
              </label>
              <input
                type="tel"
                id="booking-phone"
                name="phone"
                class="form-control"
                required
                aria-describedby="phone-error"
                autocomplete="tel"
              >
              <div id="phone-error" class="error-message" role="alert" aria-live="polite"></div>
            </div>

            <div class="form-group">
              <label for="booking-treatment" class="form-label required">
                Treatment Interest
                <span class="required-indicator" aria-label="required">*</span>
              </label>
              <select
                id="booking-treatment"
                name="treatment"
                class="form-control"
                required
                aria-describedby="treatment-error"
              >
                <option value="">Select a treatment...</option>
                ${this.config.treatments?.map(treatment =>
                  `<option value="${treatment}">${treatment}</option>`
                ).join('')}
              </select>
              <div id="treatment-error" class="error-message" role="alert" aria-live="polite"></div>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="booking-date" class="form-label">
                Preferred Date
              </label>
              <input
                type="date"
                id="booking-date"
                name="preferredDate"
                class="form-control"
                min="${new Date().toISOString().split('T')[0]}"
                aria-describedby="date-help"
              >
              <div id="date-help" class="form-help">
                We'll contact you to confirm your appointment time.
              </div>
            </div>

            <div class="form-group">
              <label for="booking-time" class="form-label">
                Preferred Time
              </label>
              <select id="booking-time" name="preferredTime" class="form-control">
                <option value="">Any time</option>
                <option value="morning">Morning (9AM - 12PM)</option>
                <option value="afternoon">Afternoon (12PM - 5PM)</option>
                <option value="evening">Evening (5PM - 7PM)</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="booking-message" class="form-label">
              Additional Information
            </label>
            <textarea
              id="booking-message"
              name="message"
              class="form-control"
              rows="3"
              placeholder="Tell us about your goals or any questions you have..."
              maxlength="500"
              aria-describedby="message-count"
            ></textarea>
            <div id="message-count" class="form-help">
              <span class="char-count">0</span>/500 characters
            </div>
          </div>

          ${this.config.hipaaRequired ? `
          <div class="consent-section">
            <div class="form-group">
              <div class="checkbox-group">
                <input
                  type="checkbox"
                  id="hipaa-consent"
                  name="hipaaConsent"
                  required
                  class="form-checkbox"
                  aria-describedby="hipaa-error"
                >
                <label for="hipaa-consent" class="checkbox-label required">
                  <span class="checkbox-text">
                    I acknowledge that I have read and understand the
                    <a href="/privacy-policy" target="_blank" class="policy-link">
                      Privacy Policy
                      <span class="sr-only">(opens in new window)</span>
                    </a>
                    and consent to the collection and use of my health information
                    for consultation purposes.
                    <span class="required-indicator" aria-label="required">*</span>
                  </span>
                </label>
              </div>
              <div id="hipaa-error" class="error-message" role="alert" aria-live="polite"></div>
            </div>

            <div class="form-group">
              <div class="checkbox-group">
                <input
                  type="checkbox"
                  id="marketing-consent"
                  name="marketingConsent"
                  class="form-checkbox"
                >
                <label for="marketing-consent" class="checkbox-label">
                  <span class="checkbox-text">
                    I would like to receive promotional offers and updates about treatments.
                    <span class="form-help-inline">(Optional)</span>
                  </span>
                </label>
              </div>
            </div>
          </div>
          ` : ''}

          <div class="form-actions">
            <button
              type="submit"
              class="btn-submit consultation-cta"
              aria-describedby="submit-help"
            >
              <span class="btn-text">Book Consultation</span>
              <span class="btn-loading" aria-hidden="true">
                <span class="loading-spinner"></span>
                Booking...
              </span>
            </button>
            <div id="submit-help" class="form-help">
              Free consultation • No obligation • HIPAA compliant
            </div>
          </div>

          <div class="form-status" role="status" aria-live="polite"></div>
        </form>
      </div>
    `;

    this.element.innerHTML = widgetHTML;
    this.form = this.element.querySelector('.booking-form') as HTMLFormElement;
    this.submitButton = this.element.querySelector('.btn-submit') as HTMLButtonElement;
  }

  private bindEvents(): void {
    if (!this.form) return;

    // Form submission
    this.form.addEventListener('submit', this.handleSubmit.bind(this));

    // Real-time validation
    const inputs = this.form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
      input.addEventListener('blur', this.validateField.bind(this));
      input.addEventListener('input', this.clearFieldError.bind(this));
    });

    // Character counter for message
    const messageField = this.form.querySelector('[name="message"]') as HTMLTextAreaElement;
    if (messageField) {
      messageField.addEventListener('input', this.updateCharacterCount.bind(this));
    }

    // Phone number formatting
    const phoneField = this.form.querySelector('[name="phone"]') as HTMLInputElement;
    if (phoneField) {
      phoneField.addEventListener('input', this.formatPhoneNumber.bind(this));
    }
  }

  private setupAccessibility(): void {
    // Set up basic keyboard navigation
    this.setupKeyboardNavigation();

    // Add ARIA labels and descriptions
    const requiredFields = this.element.querySelectorAll('[required]');
    requiredFields.forEach(field => {
      const label = this.element.querySelector(`label[for="${field.id}"]`);
      if (label && !label.querySelector('.required-indicator')) {
        label.insertAdjacentHTML('beforeend', ' <span class="required-indicator" aria-label="required">*</span>');
      }
    });

    // Set up form validation announcements
    this.form?.setAttribute('aria-label', 'Medical spa consultation booking form');
  }

  private setupKeyboardNavigation(): void {
    // Add keyboard event listeners for accessibility
    this.element.addEventListener('keydown', (e) => {
      if (e.key === 'Tab') {
        this.element.classList.add('keyboard-navigation');
      }
    });

    this.element.addEventListener('mousedown', () => {
      this.element.classList.remove('keyboard-navigation');
    });

    // Ensure proper tab order
    const focusableElements = this.element.querySelectorAll(
      'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
    );

    focusableElements.forEach((element, index) => {
      if (!element.hasAttribute('tabindex')) {
        element.setAttribute('tabindex', '0');
      }
    });
  }

  private async handleSubmit(event: Event): Promise<void> {
    event.preventDefault();

    if (this.isSubmitting) return;

    const formData = this.getFormData();
    const validation = this.validateForm(formData);

    if (!validation.isValid) {
      this.showValidationErrors(validation.errors);
      announceToScreenReader('Please correct the errors in the form');
      return;
    }

    this.setSubmittingState(true);

    try {
      await this.submitBooking(formData);
      this.showSuccess();

      if (this.config.enableAnalytics) {
        this.trackBookingSubmission(formData);
      }

    } catch (error) {
      console.error('Booking submission failed:', error);
      this.showError('Failed to submit booking. Please try again or call us directly.');
    } finally {
      this.setSubmittingState(false);
    }
  }

  private getFormData(): BookingData {
    if (!this.form) throw new Error('Form not found');

    const formData = new FormData(this.form);
    return {
      name: formData.get('name') as string,
      email: formData.get('email') as string,
      phone: formData.get('phone') as string,
      treatment: formData.get('treatment') as string,
      preferredDate: formData.get('preferredDate') as string,
      preferredTime: formData.get('preferredTime') as string,
      message: formData.get('message') as string,
      hipaaConsent: formData.get('hipaaConsent') === 'on',
      marketingConsent: formData.get('marketingConsent') === 'on'
    };
  }

  private validateForm(data: BookingData): { isValid: boolean; errors: Record<string, string> } {
    const errors: Record<string, string> = {};

    // Required field validation
    if (!data.name?.trim()) {
      errors.name = 'Name is required';
    }

    if (!data.email?.trim()) {
      errors.email = 'Email is required';
    } else if (!this.isValidEmail(data.email)) {
      errors.email = 'Please enter a valid email address';
    }

    if (!data.phone?.trim()) {
      errors.phone = 'Phone number is required';
    } else if (!this.isValidPhone(data.phone)) {
      errors.phone = 'Please enter a valid phone number';
    }

    if (!data.treatment) {
      errors.treatment = 'Please select a treatment';
    }

    if (this.config.hipaaRequired && !data.hipaaConsent) {
      errors.hipaaConsent = 'HIPAA consent is required to proceed';
    }

    return {
      isValid: Object.keys(errors).length === 0,
      errors
    };
  }

  private isValidEmail(email: string): boolean {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  private isValidPhone(phone: string): boolean {
    const phoneRegex = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
    return phoneRegex.test(phone.replace(/\D/g, ''));
  }

  private validateField(event: Event): void {
    const field = event.target as HTMLInputElement;
    const fieldName = field.name;
    const value = field.value.trim();

    this.clearFieldError(event);

    let errorMessage = '';

    switch (fieldName) {
      case 'email':
        if (value && !this.isValidEmail(value)) {
          errorMessage = 'Please enter a valid email address';
        }
        break;
      case 'phone':
        if (value && !this.isValidPhone(value)) {
          errorMessage = 'Please enter a valid phone number';
        }
        break;
    }

    if (errorMessage) {
      this.showFieldError(fieldName, errorMessage);
    }
  }

  private clearFieldError(event: Event): void {
    const field = event.target as HTMLInputElement;
    const errorElement = this.element.querySelector(`#${field.name}-error`);

    if (errorElement) {
      errorElement.textContent = '';
      field.setAttribute('aria-invalid', 'false');
    }
  }

  private showFieldError(fieldName: string, message: string): void {
    const field = this.element.querySelector(`[name="${fieldName}"]`) as HTMLInputElement;
    const errorElement = this.element.querySelector(`#${fieldName}-error`);

    if (errorElement) {
      errorElement.textContent = message;
      field?.setAttribute('aria-invalid', 'true');
    }
  }

  private showValidationErrors(errors: Record<string, string>): void {
    Object.entries(errors).forEach(([fieldName, message]) => {
      this.showFieldError(fieldName, message);
    });

    // Focus first error field
    const firstErrorField = Object.keys(errors)[0];
    const field = this.element.querySelector(`[name="${firstErrorField}"]`) as HTMLInputElement;
    field?.focus();
  }

  private async submitBooking(data: BookingData): Promise<void> {
    const response = await fetch(this.config.apiEndpoint!, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': (window as any).preetidreamsBooking?.nonce || ''
      },
      body: JSON.stringify({
        ...data,
        source: 'booking-widget',
        timestamp: new Date().toISOString()
      })
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const result = await response.json();
    if (!result.success) {
      throw new Error(result.message || 'Booking submission failed');
    }
  }

  private showSuccess(): void {
    const statusElement = this.element.querySelector('.form-status');
    if (statusElement) {
      statusElement.innerHTML = `
        <div class="success-message" role="alert">
          <div class="success-icon" aria-hidden="true">✅</div>
          <div class="success-content">
            <h4>Consultation Booked Successfully!</h4>
            <p>Thank you for your interest. We'll contact you within 24 hours to confirm your appointment.</p>
          </div>
        </div>
      `;
    }

    // Reset form
    this.form?.reset();

    // Announce to screen readers
    announceToScreenReader('Consultation booking submitted successfully');
  }

  private showError(message: string): void {
    const statusElement = this.element.querySelector('.form-status');
    if (statusElement) {
      statusElement.innerHTML = `
        <div class="error-message" role="alert">
          <div class="error-icon" aria-hidden="true">⚠️</div>
          <div class="error-content">
            <h4>Booking Error</h4>
            <p>${message}</p>
          </div>
        </div>
      `;
    }
  }

  private setSubmittingState(isSubmitting: boolean): void {
    this.isSubmitting = isSubmitting;

    if (this.submitButton) {
      this.submitButton.disabled = isSubmitting;
      this.submitButton.setAttribute('aria-busy', isSubmitting.toString());

      const btnText = this.submitButton.querySelector('.btn-text') as HTMLElement;
      const btnLoading = this.submitButton.querySelector('.btn-loading') as HTMLElement;

      if (btnText && btnLoading) {
        btnText.style.display = isSubmitting ? 'none' : 'inline';
        btnLoading.style.display = isSubmitting ? 'inline' : 'none';
      }
    }

    // Disable all form inputs during submission
    const inputs = this.form?.querySelectorAll('input, select, textarea');
    inputs?.forEach(input => {
      (input as HTMLInputElement).disabled = isSubmitting;
    });
  }

  private updateCharacterCount(event: Event): void {
    const textarea = event.target as HTMLTextAreaElement;
    const counter = this.element.querySelector('.char-count');

    if (counter) {
      counter.textContent = textarea.value.length.toString();
    }
  }

  private formatPhoneNumber(event: Event): void {
    const input = event.target as HTMLInputElement;
    const value = input.value.replace(/\D/g, '');

    if (value.length >= 6) {
      input.value = `(${value.slice(0, 3)}) ${value.slice(3, 6)}-${value.slice(6, 10)}`;
    } else if (value.length >= 3) {
      input.value = `(${value.slice(0, 3)}) ${value.slice(3)}`;
    } else {
      input.value = value;
    }
  }

  private trackWidgetLoad(): void {
    // Analytics tracking for widget load
    if (typeof window !== 'undefined' && window.gtag) {
      window.gtag('event', 'booking_widget_load', {
        event_category: 'Medical Spa',
        event_label: 'Booking Widget'
      });
    }
  }

  private trackBookingSubmission(data: BookingData): void {
    // Analytics tracking for successful booking
    if (typeof window !== 'undefined' && window.gtag) {
      window.gtag('event', 'booking_submission', {
        event_category: 'Medical Spa',
        event_label: data.treatment,
        treatment_type: data.treatment
      });
    }
  }

  // Public API methods
  public destroy(): void {
    this.element.innerHTML = '';
  }

  public reset(): void {
    this.form?.reset();
    this.clearAllErrors();
  }

  private clearAllErrors(): void {
    const errorElements = this.element.querySelectorAll('.error-message');
    errorElements.forEach(element => {
      element.textContent = '';
    });

    const invalidFields = this.element.querySelectorAll('[aria-invalid="true"]');
    invalidFields.forEach(field => {
      field.setAttribute('aria-invalid', 'false');
    });
  }
}

// Auto-initialize booking widgets
document.addEventListener('DOMContentLoaded', () => {
  const bookingElements = document.querySelectorAll('[data-booking-widget]');

  bookingElements.forEach((element) => {
    try {
      const config = {
        element: element as HTMLElement,
        ...JSON.parse(element.getAttribute('data-config') || '{}')
      };

      new BookingWidget(config);
    } catch (error) {
      console.error('Failed to initialize booking widget:', error);
    }
  });
});

// Export for global access
if (typeof window !== 'undefined') {
  window.preetidreamsBooking = window.preetidreamsBooking || {} as any;
  (window.preetidreamsBooking as any).BookingWidget = BookingWidget;
}
