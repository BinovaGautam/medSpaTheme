/**
 * Premium Hero System
 * Interactive treatment selection with dynamic backgrounds
 *
 * @package PreetiDreams
 * @version 1.0.0
 */

class PremiumHeroSystem {
  constructor() {
    this.currentStep = 1;
    this.selectedCategory = null;
    this.selectedTreatment = null;
    this.backgrounds = ['image', 'video', 'gradient'];
    this.currentBackground = 0;
    this.treatmentData = this.loadTreatmentData();
    this.formSubmissionInProgress = false;
    this.init();
  }

  init() {
    this.bindEvents();
    this.initAOS();
    this.initializeBackgrounds();
    this.startBackgroundRotation();
    this.trackAnalytics('hero_loaded');
    this.initializeAccessibility();
  }

  bindEvents() {
    // Category selection
    document.querySelectorAll('.category-btn').forEach(btn => {
      btn.addEventListener('click', (e) => this.handleCategorySelection(e));
      btn.addEventListener('keydown', (e) => this.handleKeyboard(e));
    });

    // Step navigation
    document.querySelectorAll('.step-back').forEach(btn => {
      btn.addEventListener('click', () => this.goToStep(this.currentStep - 1));
    });

    // Form submission
    const form = document.getElementById('hero-consultation-form');
    if (form) {
      form.addEventListener('submit', (e) => this.handleFormSubmission(e));

      // Real-time validation
      const inputs = form.querySelectorAll('input, textarea');
      inputs.forEach(input => {
        input.addEventListener('blur', () => this.validateField(input));
        input.addEventListener('input', () => this.clearFieldError(input));
      });
    }

    // Background switching (admin only)
    if (document.body.classList.contains('admin-bar')) {
      this.initBackgroundSwitcher();
    }

    // Escape key handling
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.currentStep > 1) {
        this.goToStep(this.currentStep - 1);
      }
    });
  }

  handleCategorySelection(e) {
    e.preventDefault();
    const category = e.currentTarget.dataset.category;
    this.selectedCategory = category;

    // Visual feedback
    document.querySelectorAll('.category-btn').forEach(btn =>
      btn.classList.remove('selected'));
    e.currentTarget.classList.add('selected');

    // Announce to screen readers
    this.announceToScreenReader(`Selected ${e.currentTarget.textContent.trim()}`);

    // Load specific treatments
    setTimeout(() => {
      this.loadSpecificTreatments(category);
      this.goToStep(2);
    }, 300);

    this.trackAnalytics('category_selected', { category });
  }

  loadSpecificTreatments(category) {
    const container = document.querySelector('.specific-treatments');
    const treatments = this.treatmentData[category] || [];

    container.innerHTML = treatments.map((treatment, index) => `
      <button class="treatment-btn" data-treatment="${treatment.slug}"
              aria-describedby="treatment-${index}-desc" tabindex="0">
        <span class="treatment-icon" aria-hidden="true">${treatment.icon}</span>
        <div class="treatment-info">
          <h4 class="treatment-name">${treatment.name}</h4>
          <p class="treatment-price" id="treatment-${index}-desc">${treatment.price}</p>
        </div>
      </button>
    `).join('');

    // Bind events for specific treatments
    container.querySelectorAll('.treatment-btn').forEach(btn => {
      btn.addEventListener('click', (e) => this.handleTreatmentSelection(e));
      btn.addEventListener('keydown', (e) => this.handleKeyboard(e));
    });

    // Focus first treatment for accessibility
    const firstTreatment = container.querySelector('.treatment-btn');
    if (firstTreatment) {
      firstTreatment.focus();
    }
  }

  handleTreatmentSelection(e) {
    e.preventDefault();
    const treatment = e.currentTarget.dataset.treatment;
    this.selectedTreatment = treatment;

    // Visual feedback
    document.querySelectorAll('.treatment-btn').forEach(btn =>
      btn.classList.remove('selected'));
    e.currentTarget.classList.add('selected');

    // Announce to screen readers
    const treatmentName = e.currentTarget.querySelector('.treatment-name').textContent;
    this.announceToScreenReader(`Selected ${treatmentName}`);

    // Pre-fill form data
    this.prefillForm();

    setTimeout(() => {
      this.goToStep(3);
    }, 300);

    this.trackAnalytics('treatment_selected', {
      category: this.selectedCategory,
      treatment
    });
  }

  prefillForm() {
    const form = document.getElementById('hero-consultation-form');
    const messageField = form.querySelector('textarea[name="message"]');

    if (messageField && this.selectedCategory && this.selectedTreatment) {
      const treatmentInfo = this.getTreatmentInfo(this.selectedCategory, this.selectedTreatment);
      messageField.placeholder = `I'm interested in ${treatmentInfo.name}. Please provide more information about pricing, scheduling, and what to expect.`;
    }
  }

  goToStep(step) {
    if (step < 1 || step > 3) return;

    const previousStep = this.currentStep;

    // Update step indicators
    document.querySelectorAll('.step-number').forEach((indicator, index) => {
      indicator.classList.toggle('active', index + 1 <= step);
    });

    // Show/hide steps
    document.querySelectorAll('.selection-step').forEach((stepEl, index) => {
      stepEl.classList.toggle('active', index + 1 === step);
    });

    this.currentStep = step;

    // Focus management for accessibility
    this.manageFocus(step, previousStep);

    // Announce step change to screen readers
    this.announceToScreenReader(`Step ${step} of 3`);

    this.trackAnalytics('step_changed', { step, previousStep });
  }

  manageFocus(step, previousStep) {
    setTimeout(() => {
      let focusTarget;

      switch (step) {
        case 1:
          focusTarget = document.querySelector('.category-btn');
          break;
        case 2:
          focusTarget = document.querySelector('.treatment-btn');
          break;
        case 3:
          focusTarget = document.querySelector('#hero-consultation-form input[name="full_name"]');
          break;
      }

      if (focusTarget) {
        focusTarget.focus();
      }
    }, 100);
  }

  handleFormSubmission(e) {
    e.preventDefault();

    if (this.formSubmissionInProgress) {
      return;
    }

    const formData = new FormData(e.target);
    const data = {
      full_name: formData.get('full_name'),
      email: formData.get('email'),
      phone: formData.get('phone'),
      message: formData.get('message'),
      selected_category: this.selectedCategory,
      selected_treatment: this.selectedTreatment,
      source: 'hero_treatment_selection'
    };

    // Validate form
    if (!this.validateForm(data)) {
      return;
    }

    // Submit to WordPress
    this.submitConsultation(data);
  }

  validateForm(data) {
    const errors = [];

    if (!data.full_name || data.full_name.trim().length < 2) {
      errors.push('Please enter your full name (minimum 2 characters)');
    }

    if (!data.email || !this.isValidEmail(data.email)) {
      errors.push('Please enter a valid email address');
    }

    if (!data.phone || !this.isValidPhone(data.phone)) {
      errors.push('Please enter a valid phone number (minimum 10 digits)');
    }

    if (errors.length > 0) {
      this.showValidationErrors(errors);
      // Focus first error field
      this.focusFirstErrorField();
      return false;
    }

    return true;
  }

  validateField(field) {
    const value = field.value.trim();
    const name = field.name;
    let isValid = true;
    let errorMessage = '';

    switch (name) {
      case 'full_name':
        if (!value || value.length < 2) {
          isValid = false;
          errorMessage = 'Please enter your full name (minimum 2 characters)';
        }
        break;
      case 'email':
        if (!value || !this.isValidEmail(value)) {
          isValid = false;
          errorMessage = 'Please enter a valid email address';
        }
        break;
      case 'phone':
        if (!value || !this.isValidPhone(value)) {
          isValid = false;
          errorMessage = 'Please enter a valid phone number';
        }
        break;
    }

    this.setFieldValidation(field, isValid, errorMessage);
    return isValid;
  }

  setFieldValidation(field, isValid, errorMessage) {
    field.classList.toggle('invalid', !isValid);
    field.classList.toggle('valid', isValid);

    // Remove existing error message
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
      existingError.remove();
    }

    // Add error message if invalid
    if (!isValid && errorMessage) {
      const errorElement = document.createElement('div');
      errorElement.className = 'field-error';
      errorElement.textContent = errorMessage;
      errorElement.setAttribute('role', 'alert');
      field.parentNode.appendChild(errorElement);
    }
  }

  clearFieldError(field) {
    field.classList.remove('invalid');
    const errorElement = field.parentNode.querySelector('.field-error');
    if (errorElement) {
      errorElement.remove();
    }
  }

  focusFirstErrorField() {
    const firstInvalidField = document.querySelector('#hero-consultation-form .invalid');
    if (firstInvalidField) {
      firstInvalidField.focus();
    }
  }

  submitConsultation(data) {
    const submitBtn = document.querySelector('#hero-consultation-form button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    this.formSubmissionInProgress = true;

    // Loading state
    submitBtn.innerHTML = '<span class="loading-spinner"></span> Submitting...';
    submitBtn.disabled = true;
    submitBtn.setAttribute('aria-busy', 'true');

    // Check if ajax_object is available
    if (typeof ajax_object === 'undefined') {
      console.error('AJAX object not available');
      this.showErrorMessage('Configuration error. Please try again or contact us directly.');
      this.resetSubmitButton(submitBtn, originalText);
      return;
    }

    fetch(ajax_object.ajax_url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams({
        action: 'submit_hero_consultation',
        nonce: ajax_object.nonce,
        ...data
      })
    })
    .then(response => response.json())
    .then(result => {
      if (result.success) {
        this.showSuccessMessage();
        this.trackAnalytics('consultation_submitted', data);
      } else {
        this.showErrorMessage(result.data || 'Something went wrong. Please try again.');
      }
    })
    .catch(error => {
      console.error('Submission error:', error);
      this.showErrorMessage('Network error. Please check your connection and try again.');
    })
    .finally(() => {
      this.resetSubmitButton(submitBtn, originalText);
    });
  }

  resetSubmitButton(submitBtn, originalText) {
    this.formSubmissionInProgress = false;
    submitBtn.innerHTML = originalText;
    submitBtn.disabled = false;
    submitBtn.removeAttribute('aria-busy');
  }

  startBackgroundRotation() {
    // Auto-rotate backgrounds every 10 seconds
    setInterval(() => {
      this.currentBackground = (this.currentBackground + 1) % this.backgrounds.length;
      this.switchBackground(this.backgrounds[this.currentBackground]);
    }, 10000);
  }

  switchBackground(type) {
    document.querySelectorAll('.hero-background').forEach(bg =>
      bg.classList.remove('active'));

    const newBackground = document.querySelector(`[data-background="${type}"]`);
    if (newBackground) {
      newBackground.classList.add('active');
    }
  }

  initAOS() {
    if (typeof AOS !== 'undefined') {
      AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true,
        offset: 100,
        disable: window.matchMedia('(prefers-reduced-motion: reduce)').matches
      });
    }
  }

  initializeAccessibility() {
    // Add ARIA labels and descriptions
    const treatmentInterface = document.querySelector('.treatment-selection-interface');
    if (treatmentInterface) {
      treatmentInterface.setAttribute('role', 'application');
      treatmentInterface.setAttribute('aria-label', 'Treatment selection wizard');
    }

    // Add live region for announcements
    if (!document.getElementById('sr-live-region')) {
      const liveRegion = document.createElement('div');
      liveRegion.id = 'sr-live-region';
      liveRegion.className = 'sr-only';
      liveRegion.setAttribute('aria-live', 'polite');
      liveRegion.setAttribute('aria-atomic', 'true');
      document.body.appendChild(liveRegion);
    }
  }

  announceToScreenReader(message) {
    const liveRegion = document.getElementById('sr-live-region');
    if (liveRegion) {
      liveRegion.textContent = message;
      // Clear after a delay to allow for repeated announcements
      setTimeout(() => {
        liveRegion.textContent = '';
      }, 1000);
    }
  }

  loadTreatmentData() {
    // Treatment data structure
    return {
      facial: [
        { slug: 'hydrafacial', name: 'HydraFacial MD', price: 'Starting at $150', icon: '✨' },
        { slug: 'chemical-peel', name: 'Chemical Peel', price: 'Starting at $100', icon: '🌟' },
        { slug: 'microneedling', name: 'Microneedling', price: 'Starting at $200', icon: '💫' },
        { slug: 'dermaplaning', name: 'Dermaplaning', price: 'Starting at $80', icon: '✨' }
      ],
      injectable: [
        { slug: 'botox', name: 'Botox Cosmetic', price: 'Starting at $12/unit', icon: '💉' },
        { slug: 'dermal-fillers', name: 'Dermal Fillers', price: 'Starting at $600', icon: '💎' },
        { slug: 'lip-fillers', name: 'Lip Enhancement', price: 'Starting at $500', icon: '💋' },
        { slug: 'sculptra', name: 'Sculptra', price: 'Starting at $800', icon: '✨' }
      ],
      laser: [
        { slug: 'laser-hair-removal', name: 'Laser Hair Removal', price: 'Starting at $100', icon: '💎' },
        { slug: 'laser-resurfacing', name: 'Fractional Laser', price: 'Starting at $300', icon: '✨' },
        { slug: 'ipl-photofacial', name: 'IPL Photofacial', price: 'Starting at $250', icon: '🌟' },
        { slug: 'co2-laser', name: 'CO2 Laser Resurfacing', price: 'Starting at $500', icon: '💫' }
      ],
      body: [
        { slug: 'coolsculpting', name: 'CoolSculpting', price: 'Starting at $750', icon: '🌟' },
        { slug: 'radiofrequency', name: 'RF Body Contouring', price: 'Starting at $400', icon: '⚡' },
        { slug: 'lymphatic-drainage', name: 'Lymphatic Drainage', price: 'Starting at $150', icon: '🌊' },
        { slug: 'body-sculpting', name: 'Non-Invasive Body Sculpting', price: 'Starting at $300', icon: '💪' }
      ]
    };
  }

  getTreatmentInfo(category, treatmentSlug) {
    const treatments = this.treatmentData[category] || [];
    return treatments.find(t => t.slug === treatmentSlug) || {};
  }

  trackAnalytics(event, data = {}) {
    // Google Analytics 4
    if (typeof gtag !== 'undefined') {
      gtag('event', event, {
        event_category: 'hero_interaction',
        event_label: JSON.stringify(data),
        value: 1
      });
    }

    // Custom analytics tracking
    if (window.medSpaAnalytics) {
      window.medSpaAnalytics.track(event, data);
    }

    // Debug logging
    if (window.location.hostname === 'localhost' || window.location.hostname.includes('dev')) {
      console.log('Analytics Event:', event, data);
    }
  }

  handleKeyboard(e) {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      e.target.click();
    }
  }

  isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  isValidPhone(phone) {
    // Remove all non-digits
    const cleanPhone = phone.replace(/\D/g, '');
    // Check if it has at least 10 digits
    return cleanPhone.length >= 10;
  }

  showValidationErrors(errors) {
    // Remove existing errors
    const existingErrors = document.querySelector('.validation-errors');
    if (existingErrors) {
      existingErrors.remove();
    }

    // Create error container
    const errorDiv = document.createElement('div');
    errorDiv.className = 'validation-errors';
    errorDiv.setAttribute('role', 'alert');
    errorDiv.innerHTML = errors.map(error => `<p class="error-message">${error}</p>`).join('');

    const form = document.getElementById('hero-consultation-form');
    form.insertBefore(errorDiv, form.firstChild);

    // Announce errors to screen readers
    this.announceToScreenReader(`${errors.length} validation errors found`);

    // Auto-remove after 8 seconds
    setTimeout(() => {
      if (errorDiv.parentNode) {
        errorDiv.remove();
      }
    }, 8000);
  }

  showSuccessMessage() {
    const interface = document.querySelector('.treatment-selection-interface');
    interface.innerHTML = `
      <div class="success-message" role="status" aria-live="polite">
        <div class="success-icon" aria-hidden="true">✅</div>
        <h3>Thank You!</h3>
        <p>Your consultation request has been submitted successfully. We'll contact you within 24 hours to schedule your appointment.</p>
        <p>If you have any immediate questions, please call us directly at <a href="tel:(555) 123-4567">(555) 123-4567</a>.</p>
        <button class="btn btn-primary" onclick="location.reload()" aria-label="Start new treatment selection">
          Select Another Treatment
        </button>
      </div>
    `;

    // Focus the success message for screen readers
    const successMessage = interface.querySelector('.success-message');
    successMessage.focus();

    // Announce success to screen readers
    this.announceToScreenReader('Consultation request submitted successfully');
  }

  showErrorMessage(message) {
    // Remove existing errors
    const existingErrors = document.querySelector('.submission-error');
    if (existingErrors) {
      existingErrors.remove();
    }

    // Create error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'submission-error validation-errors';
    errorDiv.setAttribute('role', 'alert');
    errorDiv.innerHTML = `<p class="error-message">${message}</p>`;

    const form = document.getElementById('hero-consultation-form');
    form.insertBefore(errorDiv, form.firstChild);

    // Announce error to screen readers
    this.announceToScreenReader(`Submission error: ${message}`);

    // Auto-remove after 10 seconds
    setTimeout(() => {
      if (errorDiv.parentNode) {
        errorDiv.remove();
      }
    }, 10000);
  }

  initBackgroundSwitcher() {
    // Admin-only background switching controls
    if (!document.querySelector('.hero-bg-controls')) {
      const controlsDiv = document.createElement('div');
      controlsDiv.className = 'hero-bg-controls';
      controlsDiv.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        z-index: 9999;
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 10px;
        border-radius: 5px;
        font-size: 12px;
      `;

      controlsDiv.innerHTML = `
        <div>Hero Background:</div>
        <button onclick="premiumHero.switchBackground('image')" style="margin: 2px;">Image</button>
        <button onclick="premiumHero.switchBackground('video')" style="margin: 2px;">Video</button>
        <button onclick="premiumHero.switchBackground('gradient')" style="margin: 2px;">Gradient</button>
      `;

      document.body.appendChild(controlsDiv);
    }
  }

  initializeBackgrounds() {
    // Ensure at least one background is always active
    const activeBackground = document.querySelector('.hero-background.active');
    if (!activeBackground) {
      // Fallback to gradient if no background is active
      const gradientBg = document.querySelector('.hero-background-gradient');
      if (gradientBg) {
        gradientBg.classList.add('active');
      }
    }

    // Log background status for debugging
    const backgrounds = document.querySelectorAll('.hero-background');
    backgrounds.forEach((bg, index) => {
      const isActive = bg.classList.contains('active');
      const type = bg.dataset.background;
      console.log(`Background ${index}: ${type} - ${isActive ? 'Active' : 'Inactive'}`);
    });
  }
}

// CSS for form validation states
const validationCSS = `
  .form-group input.invalid,
  .form-group textarea.invalid {
    border-color: var(--color-accent-coral);
    box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
  }

  .form-group input.valid,
  .form-group textarea.valid {
    border-color: var(--color-accent-success);
    box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
  }

  .field-error {
    color: var(--color-accent-coral);
    font-size: var(--text-sm);
    margin-top: 0.25rem;
    font-weight: 500;
  }

  .submission-error {
    margin-bottom: var(--space-lg);
  }
`;

// Inject validation CSS
if (!document.getElementById('hero-validation-styles')) {
  const styleSheet = document.createElement('style');
  styleSheet.id = 'hero-validation-styles';
  styleSheet.textContent = validationCSS;
  document.head.appendChild(styleSheet);
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  if (document.querySelector('.premium-hero')) {
    window.premiumHero = new PremiumHeroSystem();
  }
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
  module.exports = PremiumHeroSystem;
}
