/**
 * Enhanced Premium Hero System - 4-Step Treatment Quiz
 * Interactive treatment selection with demographic collection and lead scoring
 *
 * @package PreetiDreams
 * @version 2.0.0
 */

class EnhancedPremiumHeroSystem {
  constructor() {
    this.currentStep = 1;
    this.totalSteps = 4;
    this.selectedCategory = null;
    this.selectedTreatment = null;
    this.selectedTreatmentData = null;
    this.demographics = {
      age_range: '',
      gender: '',
      experience_level: 'some-experience',
      treatment_timing: '1-3-months'
    };
    this.contactInfo = {
      full_name: '',
      email: '',
      phone: '',
      contact_preference: 'email',
      marketing_consent: false
    };

    this.treatmentData = {};
    this.categoriesData = [];
    this.formSubmissionInProgress = false;
    this.startTime = Date.now();
    this.stepTimestamps = [];

    this.init();
  }

  init() {
    this.setupElements();
    this.setupEventListeners();
    this.loadCategoriesAndTreatments();
    this.initializeAccessibility();
    this.trackAnalytics('quiz_started');
    this.recordStepTimestamp(1);
  }

  setupElements() {
    this.progressIndicator = document.querySelector('.progress-indicator');
    this.progressBar = document.querySelector('.progress-fill');
    this.stepDescription = document.querySelector('.step-description');
    this.currentStepSpan = document.querySelector('.current-step');

    this.steps = {
      1: document.querySelector('[data-step="1"]'),
      2: document.querySelector('[data-step="2"]'),
      3: document.querySelector('[data-step="3"]'),
      4: document.querySelector('[data-step="4"]'),
      success: document.querySelector('[data-step="success"]')
    };

    this.categoryButtons = document.querySelectorAll('.category-btn');
    this.treatmentContainer = document.querySelector('.specific-treatments');
    this.form = document.getElementById('hero-consultation-form');
    this.quizSummary = document.getElementById('quiz-summary');
  }

  setupEventListeners() {
    // Category selection
    this.categoryButtons.forEach(btn => {
      btn.addEventListener('click', (e) => this.selectCategory(e.target.closest('.category-btn')));
      btn.addEventListener('keydown', this.handleKeyboard.bind(this));
    });

    // Back buttons
    document.querySelectorAll('.step-back').forEach(btn => {
      btn.addEventListener('click', () => this.goToPreviousStep());
    });

    // Continue button for demographics step
    const continueBtn = document.querySelector('.step-continue');
    if (continueBtn) {
      continueBtn.addEventListener('click', () => this.proceedToContactForm());
    }

    // Form submission
    if (this.form) {
      this.form.addEventListener('submit', (e) => this.handleFormSubmission(e));
    }

    // Real-time form validation
    this.setupFormValidation();
  }

  async loadCategoriesAndTreatments() {
    try {
      // Load categories first
      const categoriesResponse = await fetch(`${ajax_object.ajax_url}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          action: 'get_quiz_categories',
          nonce: ajax_object.nonce
        })
      });

      const categoriesResult = await categoriesResponse.json();

      if (categoriesResult.success) {
        this.categoriesData = categoriesResult.data.categories;
        this.updateCategoryButtons();
      }

    } catch (error) {
      console.error('❌ Error loading quiz data:', error);
      this.useDefaults();
    }
  }

  updateCategoryButtons() {
    this.categoryButtons.forEach(btn => {
      const category = btn.dataset.category;
      const categoryData = this.categoriesData.find(c => c.slug === category);

      if (categoryData) {
        const iconSpan = btn.querySelector('.category-icon');
        const descSpan = btn.querySelector('.category-description');

        if (iconSpan && categoryData.icon) {
          iconSpan.textContent = categoryData.icon;
        }
        if (descSpan && categoryData.description) {
          descSpan.textContent = categoryData.description;
        }
      }
    });
  }

  async selectCategory(button) {
    if (!button) return;

    const category = button.dataset.category;
    this.selectedCategory = category;

    // Visual feedback
    this.categoryButtons.forEach(btn => btn.classList.remove('selected'));
    button.classList.add('selected');

    // Load treatments for this category
    await this.loadTreatmentsForCategory(category);

    // Move to next step after short delay
    setTimeout(() => {
      this.goToNextStep();
    }, 300);

    this.trackAnalytics('category_selected', { category });
  }

  async loadTreatmentsForCategory(category) {
    try {
      this.treatmentContainer.innerHTML = '<div class="treatments-loading"><div class="loading-spinner"></div><p>Loading treatments...</p></div>';

      const response = await fetch(`${ajax_object.ajax_url}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          action: 'get_hero_treatments_quiz',
          category: category,
          nonce: ajax_object.nonce
        })
      });

      const result = await response.json();

      if (result.success) {
        this.treatmentData[category] = result.data.treatments;
        this.renderTreatments(result.data.treatments);
      } else {
        throw new Error(result.data || 'Failed to load treatments');
      }

    } catch (error) {
      console.error('❌ Error loading treatments:', error);
      this.treatmentContainer.innerHTML = '<p class="error-message">Error loading treatments. Please try again.</p>';
    }
  }

  renderTreatments(treatments) {
    if (!treatments || treatments.length === 0) {
      this.treatmentContainer.innerHTML = '<p class="no-treatments">No treatments available in this category.</p>';
      return;
    }

    // Sort treatments: featured first, then by order
    treatments.sort((a, b) => {
      if (a.featured && !b.featured) return -1;
      if (!a.featured && b.featured) return 1;
      return 0;
    });

    const treatmentsHTML = treatments.map(treatment => `
      <button class="treatment-btn"
              data-treatment="${treatment.slug}"
              data-pricing-tier="${treatment.pricing_tier}"
              data-treatment-id="${treatment.id}">
        <div class="treatment-icon">${treatment.icon || '💫'}</div>
        <div class="treatment-content">
          <h4 class="treatment-name">${treatment.title}</h4>
          <div class="treatment-details">
            <span class="treatment-price">${treatment.pricing}</span>
            ${treatment.duration ? `<span class="treatment-duration">${treatment.duration}</span>` : ''}
          </div>
          ${treatment.featured ? '<span class="featured-badge">Popular</span>' : ''}
        </div>
      </button>
    `).join('');

    this.treatmentContainer.innerHTML = treatmentsHTML;

    // Add event listeners to treatment buttons
    this.treatmentContainer.querySelectorAll('.treatment-btn').forEach(btn => {
      btn.addEventListener('click', () => this.selectTreatment(btn));
      btn.addEventListener('keydown', this.handleKeyboard.bind(this));
    });
  }

  selectTreatment(button) {
    const treatmentSlug = button.dataset.treatment;
    const pricingTier = button.dataset.pricingTier;
    const treatmentId = button.dataset.treatmentId;

    this.selectedTreatment = treatmentSlug;
    this.selectedTreatmentData = {
      slug: treatmentSlug,
      pricing_tier: pricingTier,
      id: treatmentId,
      name: button.querySelector('.treatment-name').textContent,
      price: button.querySelector('.treatment-price').textContent
    };

    // Visual feedback
    this.treatmentContainer.querySelectorAll('.treatment-btn').forEach(btn => btn.classList.remove('selected'));
    button.classList.add('selected');

    // Move to demographics step
    setTimeout(() => {
      this.goToNextStep();
    }, 300);

    this.trackAnalytics('treatment_selected', {
      treatment: treatmentSlug,
      category: this.selectedCategory,
      pricing_tier: pricingTier
    });
  }

  proceedToContactForm() {
    // Collect demographics
    this.demographics.age_range = document.getElementById('age-range').value;
    this.demographics.gender = document.querySelector('input[name="gender"]:checked')?.value || '';
    this.demographics.experience_level = document.getElementById('experience-level').value;
    this.demographics.treatment_timing = document.getElementById('treatment-timing').value;

    // Update quiz summary
    this.updateQuizSummary();

    // Move to contact form
    this.goToNextStep();

    this.trackAnalytics('demographics_completed', this.demographics);
  }

  updateQuizSummary() {
    if (!this.quizSummary) return;

    const summaryHTML = `
      <div class="quiz-summary-content">
        <h4>Your Selection Summary:</h4>
        <div class="summary-item">
          <strong>Treatment:</strong> ${this.selectedTreatmentData?.name || this.selectedTreatment}
        </div>
        <div class="summary-item">
          <strong>Category:</strong> ${this.selectedCategory}
        </div>
        ${this.demographics.age_range ? `<div class="summary-item"><strong>Age Range:</strong> ${this.demographics.age_range}</div>` : ''}
        ${this.demographics.gender && this.demographics.gender !== '' ? `<div class="summary-item"><strong>Gender:</strong> ${this.demographics.gender}</div>` : ''}
        <div class="summary-item">
          <strong>Experience:</strong> ${this.demographics.experience_level.replace('-', ' ')}
        </div>
        <div class="summary-item">
          <strong>Timeline:</strong> ${this.demographics.treatment_timing.replace('-', ' ')}
        </div>
      </div>
    `;

    this.quizSummary.innerHTML = summaryHTML;
  }

  goToNextStep() {
    if (this.currentStep >= this.totalSteps) return;

    this.currentStep++;
    this.recordStepTimestamp(this.currentStep);
    this.updateUI();
    this.announceToScreenReader(`Step ${this.currentStep} of ${this.totalSteps}`);
  }

  goToPreviousStep() {
    if (this.currentStep <= 1) return;

    this.currentStep--;
    this.updateUI();
    this.announceToScreenReader(`Step ${this.currentStep} of ${this.totalSteps}`);
  }

  updateUI() {
    // Update progress indicator
    const progressPercentage = (this.currentStep / this.totalSteps) * 100;
    this.progressBar.style.width = `${progressPercentage}%`;
    this.currentStepSpan.textContent = this.currentStep;

    // Update step numbers
    document.querySelectorAll('.step-number').forEach((step, index) => {
      const stepNum = index + 1;
      step.classList.toggle('active', stepNum === this.currentStep);
      step.classList.toggle('completed', stepNum < this.currentStep);
    });

    // Show/hide steps
    Object.keys(this.steps).forEach(step => {
      if (this.steps[step]) {
        this.steps[step].classList.toggle('active', parseInt(step) === this.currentStep || step === 'success');
      }
    });

    // Update step descriptions
    const stepDescriptions = {
      1: 'Select treatment category',
      2: 'Choose specific treatment',
      3: 'Personalize your experience',
      4: 'Contact information'
    };

    if (this.stepDescription && stepDescriptions[this.currentStep]) {
      this.stepDescription.querySelector('.current-step').textContent = this.currentStep;
    }
  }

  recordStepTimestamp(step) {
    this.stepTimestamps[step] = Date.now();
  }

  setupFormValidation() {
    if (!this.form) return;

    const inputs = this.form.querySelectorAll('input[required]');

    inputs.forEach(input => {
      input.addEventListener('blur', () => this.validateField(input));
      input.addEventListener('input', () => this.clearFieldError(input));
    });
  }

  validateField(field) {
    const value = field.value.trim();
    let isValid = true;
    let errorMessage = '';

    switch (field.type) {
      case 'email':
        isValid = this.isValidEmail(value);
        errorMessage = 'Please enter a valid email address';
        break;
      case 'tel':
        isValid = this.isValidPhone(value);
        errorMessage = 'Please enter a valid phone number';
        break;
      default:
        isValid = value.length >= 2;
        errorMessage = 'This field is required';
    }

    this.showFieldValidation(field, isValid, errorMessage);
    return isValid;
  }

  showFieldValidation(field, isValid, errorMessage) {
    const formGroup = field.closest('.form-group');
    const existingError = formGroup.querySelector('.field-error');

    if (existingError) {
      existingError.remove();
    }

    field.classList.toggle('error', !isValid);

    if (!isValid) {
      const errorElement = document.createElement('div');
      errorElement.className = 'field-error';
      errorElement.textContent = errorMessage;
      formGroup.appendChild(errorElement);
    }
  }

  clearFieldError(field) {
    field.classList.remove('error');
    const formGroup = field.closest('.form-group');
    const existingError = formGroup.querySelector('.field-error');
    if (existingError) {
      existingError.remove();
    }
  }

  async handleFormSubmission(e) {
    e.preventDefault();

    if (this.formSubmissionInProgress) return;

    // Validate all fields
    const formData = new FormData(this.form);
    const isValid = this.validateForm(formData);

    if (!isValid) {
      this.announceToScreenReader('Please correct the errors in the form');
      return;
    }

    this.formSubmissionInProgress = true;
    this.showSubmissionLoader();

    try {
      // Prepare submission data
      const submissionData = this.prepareSubmissionData(formData);

      const response = await fetch(`${ajax_object.ajax_url}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(submissionData)
      });

      const result = await response.json();

      if (result.success) {
        this.showSuccessMessage(result.data);
        this.trackAnalytics('quiz_completed', {
          lead_score: result.data.lead_score,
          lead_temperature: result.data.lead_temperature,
          completion_time: Date.now() - this.startTime
        });
      } else {
        throw new Error(result.data || 'Submission failed');
      }

    } catch (error) {
      console.error('❌ Form submission error:', error);
      this.showErrorMessage('Failed to submit. Please try again.');
    } finally {
      this.formSubmissionInProgress = false;
      this.hideSubmissionLoader();
    }
  }

  prepareSubmissionData(formData) {
    const data = {
      action: 'submit_hero_consultation',
      nonce: ajax_object.nonce,

      // Contact information
      full_name: formData.get('full_name'),
      email: formData.get('email'),
      phone: formData.get('phone'),
      contact_preference: formData.get('contact_preference'),
      marketing_consent: formData.get('marketing_consent') || '',

      // Treatment selection
      selected_category: this.selectedCategory,
      selected_treatment: this.selectedTreatmentData?.name || this.selectedTreatment,
      treatment_pricing_tier: this.selectedTreatmentData?.pricing_tier || 'medium',

      // Demographics
      age_range: this.demographics.age_range,
      gender: this.demographics.gender,
      experience_level: this.demographics.experience_level,
      treatment_timing: this.demographics.treatment_timing,

      // Quiz metadata
      source: 'hero_treatment_quiz',
      completion_time: Date.now() - this.startTime,
      step_timestamps: JSON.stringify(this.stepTimestamps)
    };

    return data;
  }

  validateForm(formData) {
    let isValid = true;

    // Required fields validation
    const requiredFields = ['full_name', 'email', 'phone'];

    requiredFields.forEach(fieldName => {
      const field = this.form.querySelector(`[name="${fieldName}"]`);
      if (field && !this.validateField(field)) {
        isValid = false;
      }
    });

    return isValid;
  }

  showSubmissionLoader() {
    const submitBtn = this.form.querySelector('button[type="submit"]');
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<div class="loading-spinner"></div> Submitting...';
    }
  }

  hideSubmissionLoader() {
    const submitBtn = this.form.querySelector('button[type="submit"]');
    if (submitBtn) {
      submitBtn.disabled = false;
      submitBtn.innerHTML = '<span class="btn-icon">📞</span> Get My Personalized Consultation';
    }
  }

  showSuccessMessage(data) {
    // Hide all other steps
    Object.values(this.steps).forEach(step => {
      if (step) step.classList.remove('active');
    });

    // Show success step
    this.steps.success.style.display = 'block';
    this.steps.success.classList.add('active');

    // Update progress to 100%
    this.progressBar.style.width = '100%';

    this.announceToScreenReader('Form submitted successfully! Thank you for your submission.');
  }

  showErrorMessage(message) {
    // Create or update error message
    let errorDiv = document.querySelector('.submission-error');

    if (!errorDiv) {
      errorDiv = document.createElement('div');
      errorDiv.className = 'submission-error';
      this.form.insertBefore(errorDiv, this.form.firstChild);
    }

    errorDiv.innerHTML = `
      <div class="error-content">
        <span class="error-icon">⚠️</span>
        <span class="error-text">${message}</span>
      </div>
    `;

    // Auto-hide after 5 seconds
    setTimeout(() => {
      if (errorDiv) {
        errorDiv.remove();
      }
    }, 5000);
  }

  // Utility methods
  isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  isValidPhone(phone) {
    const cleanPhone = phone.replace(/\D/g, '');
    return cleanPhone.length >= 10;
  }

  handleKeyboard(e) {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      e.target.click();
    }
  }

  initializeAccessibility() {
    const treatmentInterface = document.querySelector('.treatment-selection-interface');
    if (treatmentInterface) {
      treatmentInterface.setAttribute('role', 'application');
      treatmentInterface.setAttribute('aria-label', 'Treatment selection quiz');
    }

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
      setTimeout(() => {
        liveRegion.textContent = '';
      }, 1000);
    }
  }

  trackAnalytics(event, data = {}) {
    const eventData = {
      event_category: 'quiz_interaction',
      event_label: JSON.stringify(data),
      value: this.currentStep,
      custom_parameters: {
        quiz_step: this.currentStep,
        selected_category: this.selectedCategory,
        selected_treatment: this.selectedTreatment,
        ...data
      }
    };

    // Google Analytics 4
    if (typeof gtag !== 'undefined') {
      gtag('event', event, eventData);
    }

    // Custom analytics
    if (window.medSpaAnalytics) {
      window.medSpaAnalytics.track(event, eventData);
    }

    // Debug logging
    if (window.location.hostname === 'localhost' || window.location.hostname.includes('dev')) {
      console.log('📊 Analytics Event:', event, eventData);
    }
  }

  useDefaults() {
    // Fallback to hardcoded data if WordPress fails
    console.warn('Using fallback quiz data');
  }
}

// Initialize the enhanced quiz system when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
  if (document.querySelector('.hero-section')) {
    window.heroQuiz = new EnhancedPremiumHeroSystem();
  }
});

// Handle page visibility changes for analytics
document.addEventListener('visibilitychange', function() {
  if (document.visibilityState === 'hidden' && window.heroQuiz) {
    window.heroQuiz.trackAnalytics('quiz_abandoned', {
      step: window.heroQuiz.currentStep,
      time_spent: Date.now() - window.heroQuiz.startTime
    });
  }
});

// Export for global access
window.EnhancedPremiumHeroSystem = EnhancedPremiumHeroSystem;
