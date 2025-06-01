/**
 * Premium Hero Quiz System - Scoped and Conflict-Free
 * Handles 4-step treatment selection quiz
 *
 * @version 2.1.1
 */

class PremiumHeroQuizSystem {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 4;
        this.selectedCategory = null;
        this.selectedTreatment = null;
        this.formData = {};
        this.startTime = Date.now();

        this.init();
    }

    init() {
        console.log('🎯 Initializing Premium Hero Quiz System...');

        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }

    setup() {
        console.log('⚙️ Setting up quiz system...');
        this.bindEvents();
        this.updateProgressIndicator();
        this.showCurrentStep();
        this.updateNavigationButtons();
        console.log('✅ Quiz system ready!');
    }

    bindEvents() {
        // Category selection
        document.addEventListener('click', (e) => {
            if (e.target.closest('.premium-hero-quiz .category-btn')) {
                e.preventDefault();
                this.selectCategory(e.target.closest('.category-btn'));
            }
        });

        // Treatment selection
        document.addEventListener('click', (e) => {
            if (e.target.closest('.premium-hero-quiz .treatment-btn')) {
                e.preventDefault();
                this.selectTreatment(e.target.closest('.treatment-btn'));
            }
        });

        // Navigation buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.premium-hero-quiz .step-back')) {
                e.preventDefault();
                this.goToPreviousStep();
            }
            if (e.target.closest('.premium-hero-quiz .step-continue')) {
                e.preventDefault();
                this.goToNextStep();
            }
        });

        // Form submission
        const consultationForm = document.querySelector('.premium-hero-quiz .consultation-form-quiz');
        if (consultationForm) {
            consultationForm.addEventListener('submit', (e) => this.handleFormSubmission(e));
        }

        // Demographics form validation
        document.addEventListener('change', (e) => {
            if (e.target.closest('.premium-hero-quiz .demographics-form')) {
                this.validateDemographicsStep();
            }
        });

        console.log('🔗 Events bound successfully');
    }

    async selectCategory(button) {
        if (!button) return;

        console.log('📁 Selecting category:', button);

        // Remove previous selections
        document.querySelectorAll('.premium-hero-quiz .category-btn').forEach(btn => {
            btn.classList.remove('selected');
        });

        // Mark as selected
        button.classList.add('selected');

        // Store selection
        this.selectedCategory = button.dataset.category;
        this.formData.category = this.selectedCategory;

        console.log('✅ Category selected:', this.selectedCategory);

        // Load treatments for this category
        await this.loadTreatmentsForCategory(this.selectedCategory);

        // Auto-advance to step 2 after short delay
        setTimeout(() => {
            this.goToNextStep();
        }, 800);
    }

    async loadTreatmentsForCategory(category) {
        console.log('🔄 Loading treatments for category:', category);

        const treatmentsContainer = document.querySelector('.premium-hero-quiz .specific-treatments');
        const loadingContainer = document.querySelector('.premium-hero-quiz .treatments-loading');

        if (!treatmentsContainer) {
            console.error('❌ Treatments container not found');
            return;
        }

        // Show loading state
        if (loadingContainer) {
            loadingContainer.style.display = 'block';
        }
        treatmentsContainer.innerHTML = '<div class="loading">Loading treatments...</div>';

        try {
            const response = await fetch(premiumHeroAjax.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'get_enhanced_treatments_by_category',
                    category: category,
                    nonce: premiumHeroAjax.nonce
                })
            });

            const data = await response.json();
            console.log('📦 Treatment data received:', data);

            if (data.success && data.data.treatments) {
                this.renderTreatments(data.data.treatments);
            } else {
                console.error('❌ Failed to load treatments:', data);
                treatmentsContainer.innerHTML = `
                    <div class="error-message" style="text-align: center; color: #dc3545; padding: 2rem;">
                        <p><strong>Error loading treatments.</strong></p>
                        <p>Please try again or contact support.</p>
                        <button type="button" class="btn btn-secondary" onclick="location.reload()">Reload Page</button>
                    </div>
                `;
            }
        } catch (error) {
            console.error('❌ Error loading treatments:', error);
            treatmentsContainer.innerHTML = `
                <div class="error-message" style="text-align: center; color: #dc3545; padding: 2rem;">
                    <p><strong>Connection error.</strong></p>
                    <p>Please check your internet connection and try again.</p>
                    <button type="button" class="btn btn-secondary" onclick="location.reload()">Reload Page</button>
                </div>
            `;
        } finally {
            if (loadingContainer) {
                loadingContainer.style.display = 'none';
            }
        }
    }

    renderTreatments(treatments) {
        const container = document.querySelector('.premium-hero-quiz .specific-treatments');
        if (!container) return;

        console.log('🎨 Rendering treatments:', treatments);

        if (!treatments || treatments.length === 0) {
            container.innerHTML = `
                <div class="no-treatments" style="text-align: center; padding: 2rem;">
                    <p>No treatments available for this category.</p>
                    <button type="button" class="btn btn-secondary" onclick="location.reload()">Try Again</button>
                </div>
            `;
            return;
        }

        const treatmentButtons = treatments.map(treatment => `
            <button type="button" class="treatment-btn"
                    data-treatment="${treatment.slug}"
                    data-pricing="${treatment.pricing_tier || 'standard'}">
                <div class="treatment-icon">💉</div>
                <div class="treatment-content">
                    <div class="treatment-name">${treatment.title}</div>
                    <div class="treatment-details">
                        ${treatment.duration ? `<span>${treatment.duration} mins</span>` : ''}
                        ${treatment.price_range ? `<span>$${treatment.price_range}</span>` : ''}
                    </div>
                </div>
                ${treatment.is_featured ? '<div class="featured-badge">Popular</div>' : ''}
            </button>
        `).join('');

        container.innerHTML = `
            <div class="treatments-grid">
                ${treatmentButtons}
            </div>
        `;

        console.log('✅ Treatments rendered successfully');
    }

    selectTreatment(button) {
        if (!button) return;

        console.log('💉 Selecting treatment:', button);

        // Remove previous selections
        document.querySelectorAll('.premium-hero-quiz .treatment-btn').forEach(btn => {
            btn.classList.remove('selected');
        });

        // Mark as selected
        button.classList.add('selected');

        // Store selection
        this.selectedTreatment = button.dataset.treatment;
        this.formData.treatment = this.selectedTreatment;
        this.formData.treatmentPricingTier = button.dataset.pricing || 'standard';

        console.log('✅ Treatment selected:', this.selectedTreatment);

        // Auto-advance to step 3 after short delay
        setTimeout(() => {
            this.goToNextStep();
        }, 800);
    }

    goToNextStep() {
        if (this.currentStep < this.totalSteps) {
            this.currentStep++;
            console.log('➡️ Moving to step:', this.currentStep);
            this.updateUI();
            this.showCurrentStep();
            this.updateNavigationButtons();

            // Update summary if we're on step 4
            if (this.currentStep === 4) {
                this.updateQuizSummary();
            }
        }
    }

    goToPreviousStep() {
        if (this.currentStep > 1) {
            this.currentStep--;
            console.log('⬅️ Moving back to step:', this.currentStep);
            this.updateUI();
            this.showCurrentStep();
            this.updateNavigationButtons();
        }
    }

    updateUI() {
        this.updateProgressIndicator();
    }

    updateProgressIndicator() {
        console.log('📊 Updating progress indicator for step:', this.currentStep);

        // Update step counter
        const currentStepSpan = document.querySelector('.premium-hero-quiz .current-step');
        if (currentStepSpan) {
            currentStepSpan.textContent = this.currentStep;
        }

        // Update step numbers
        const stepNumbers = document.querySelectorAll('.premium-hero-quiz .step-number');
        stepNumbers.forEach((step, index) => {
            step.classList.remove('active', 'completed');
            if (index + 1 === this.currentStep) {
                step.classList.add('active');
            } else if (index + 1 < this.currentStep) {
                step.classList.add('completed');
            }
        });

        // Update progress bar
        const progressFill = document.querySelector('.premium-hero-quiz .progress-fill');
        if (progressFill) {
            const progressPercentage = (this.currentStep / this.totalSteps) * 100;
            progressFill.style.width = progressPercentage + '%';
            console.log('📈 Progress bar updated to:', progressPercentage + '%');
        } else {
            console.warn('⚠️ Progress fill element not found');
        }

        // Update step descriptions
        const descriptions = {
            1: 'Select the treatment category that interests you most',
            2: 'Choose your specific treatment',
            3: 'Tell us about yourself',
            4: 'Complete your consultation request'
        };

        const stepDesc = document.querySelector('.premium-hero-quiz .step-description');
        if (stepDesc) {
            stepDesc.textContent = descriptions[this.currentStep] || '';
        }
    }

    showCurrentStep() {
        console.log('👁️ Showing step:', this.currentStep);

        // Hide all steps
        const steps = document.querySelectorAll('.premium-hero-quiz .selection-step');
        steps.forEach(step => {
            step.classList.remove('active');
        });

        // Show current step
        const currentStepElement = document.querySelector(`.premium-hero-quiz .selection-step[data-step="${this.currentStep}"]`);
        if (currentStepElement) {
            currentStepElement.classList.add('active');
            console.log('✅ Step shown:', this.currentStep);
        } else {
            console.error('❌ Step element not found for step:', this.currentStep);
        }
    }

    updateNavigationButtons() {
        const navigation = document.querySelector('.premium-hero-quiz .step-navigation');
        const backButton = document.querySelector('.premium-hero-quiz .step-back');
        const continueButton = document.querySelector('.premium-hero-quiz .step-continue');
        const submitButton = document.querySelector('.premium-hero-quiz .submit-consultation');

        console.log('🔲 Updating navigation buttons for step:', this.currentStep);

        if (!navigation) {
            console.warn('⚠️ Navigation container not found');
            return;
        }

        // Show navigation for steps 3 and 4
        if (this.currentStep >= 3) {
            navigation.classList.add('show');
            navigation.style.display = 'flex';

            // Show/hide back button
            if (backButton) {
                backButton.style.display = this.currentStep > 1 ? 'block' : 'none';
            }

            // Show/hide continue button and submit button
            if (this.currentStep === 4) {
                // Step 4: Show submit button, hide continue
                if (continueButton) continueButton.style.display = 'none';
                if (submitButton) submitButton.style.display = 'block';
            } else {
                // Step 3: Show continue button, hide submit
                if (continueButton) continueButton.style.display = 'block';
                if (submitButton) submitButton.style.display = 'none';
            }
        } else {
            // Steps 1 and 2: Hide navigation (auto-advance)
            navigation.classList.remove('show');
            navigation.style.display = 'none';
        }

        console.log('✅ Navigation buttons updated');
    }

    validateDemographicsStep() {
        const demographicsForm = document.querySelector('.premium-hero-quiz .demographics-form');
        if (!demographicsForm) return false;

        const ageRange = demographicsForm.querySelector('select[name="age_range"]')?.value;
        const gender = demographicsForm.querySelector('input[name="gender"]:checked')?.value;
        const experience = demographicsForm.querySelector('select[name="experience_level"]')?.value;
        const timing = demographicsForm.querySelector('select[name="treatment_timing"]')?.value;

        const isValid = ageRange && gender && experience && timing;

        // Enable/disable continue button
        const continueButton = document.querySelector('.premium-hero-quiz .step-continue');
        if (continueButton) {
            continueButton.disabled = !isValid;
            continueButton.style.opacity = isValid ? '1' : '0.5';
        }

        return isValid;
    }

    updateQuizSummary() {
        const summaryContainer = document.querySelector('.premium-hero-quiz .quiz-summary-content');
        if (!summaryContainer) return;

        console.log('📋 Updating quiz summary');

        const demographicsForm = document.querySelector('.premium-hero-quiz .demographics-form');
        const ageRange = demographicsForm?.querySelector('select[name="age_range"]')?.selectedOptions[0]?.text || 'Not specified';
        const gender = demographicsForm?.querySelector('input[name="gender"]:checked')?.nextElementSibling?.textContent || 'Not specified';
        const experience = demographicsForm?.querySelector('select[name="experience_level"]')?.selectedOptions[0]?.text || 'Not specified';
        const timing = demographicsForm?.querySelector('select[name="treatment_timing"]')?.selectedOptions[0]?.text || 'Not specified';

        summaryContainer.innerHTML = `
            <div class="summary-item">
                <strong>Category:</strong>
                <span>${this.selectedCategory || 'Not selected'}</span>
            </div>
            <div class="summary-item">
                <strong>Treatment:</strong>
                <span>${this.selectedTreatment || 'Not selected'}</span>
            </div>
            <div class="summary-item">
                <strong>Age Range:</strong>
                <span>${ageRange}</span>
            </div>
            <div class="summary-item">
                <strong>Gender:</strong>
                <span>${gender}</span>
            </div>
            <div class="summary-item">
                <strong>Experience:</strong>
                <span>${experience}</span>
            </div>
            <div class="summary-item">
                <strong>Timeline:</strong>
                <span>${timing}</span>
            </div>
        `;
    }

    collectFormData() {
        const form = document.querySelector('.premium-hero-quiz .consultation-form-quiz');
        if (!form) return {};

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        // Add quiz selections
        data.category = this.selectedCategory;
        data.treatment = this.selectedTreatment;

        return data;
    }

    async handleFormSubmission(e) {
        e.preventDefault();
        console.log('📤 Handling form submission');

        const formData = this.collectFormData();
        console.log('📋 Form data collected:', formData);

        // Validate required fields
        const requiredFields = ['first_name', 'last_name', 'email', 'phone'];
        const missingFields = requiredFields.filter(field => !formData[field]);

        if (missingFields.length > 0) {
            this.showErrorMessage(`Please fill in all required fields: ${missingFields.join(', ')}`);
            return;
        }

        this.showSubmissionLoader();

        try {
            const response = await fetch(premiumHeroAjax.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'handle_enhanced_consultation_request',
                    ...formData,
                    nonce: premiumHeroAjax.nonce
                })
            });

            const result = await response.json();
            console.log('📨 Submission result:', result);

            this.hideSubmissionLoader();

            if (result.success) {
                this.showSuccessMessage(result.data);
            } else {
                this.showErrorMessage(result.data.message || 'Submission failed. Please try again.');
            }
        } catch (error) {
            console.error('❌ Submission error:', error);
            this.hideSubmissionLoader();
            this.showErrorMessage('Network error. Please check your connection and try again.');
        }
    }

    prepareSubmissionData(formData) {
        // Add quiz data to form submission
        return {
            ...formData,
            category: this.selectedCategory,
            treatment: this.selectedTreatment,
            quiz_completed: true,
            quiz_start_time: this.startTime,
            quiz_completion_time: Date.now()
        };
    }

    showSubmissionLoader() {
        const submitButton = document.querySelector('.premium-hero-quiz .submit-consultation');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="loading-spinner"></span> Submitting...';
        }
    }

    hideSubmissionLoader() {
        const submitButton = document.querySelector('.premium-hero-quiz .submit-consultation');
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.innerHTML = '<span>📧</span> Submit Consultation Request';
        }
    }

    showSuccessMessage(data) {
        const quizContainer = document.querySelector('.premium-hero-quiz');
        if (!quizContainer) return;

        quizContainer.innerHTML = `
            <div class="success-message">
                <div class="success-icon">✅</div>
                <h3 class="success-title">Request Submitted Successfully!</h3>
                <p>Thank you for your interest! We'll contact you within 24 hours to schedule your consultation.</p>
                <div class="success-actions">
                    <button type="button" class="btn btn-primary" onclick="location.reload()">
                        Start New Quiz
                    </button>
                    <a href="/treatments" class="btn btn-secondary">
                        Browse Treatments
                    </a>
                </div>
            </div>
        `;

        this.trackEvent('quiz_completed', {
            category: this.selectedCategory,
            treatment: this.selectedTreatment,
            completion_time: Date.now() - this.startTime
        });
    }

    showErrorMessage(message) {
        const errorContainer = document.querySelector('.premium-hero-quiz .submission-error');
        if (errorContainer) {
            errorContainer.style.display = 'block';
            errorContainer.innerHTML = `
                <div class="error-content">
                    <div class="error-icon">❌</div>
                    <p><strong>Submission Error:</strong></p>
                    <p>${message}</p>
                    <button type="button" class="btn btn-secondary" onclick="this.closest('.submission-error').style.display='none'">
                        Try Again
                    </button>
                </div>
            `;
        } else {
            // Fallback: show alert
            alert('Error: ' + message);
        }
    }

    trackEvent(event, data = {}) {
        // Google Analytics tracking
        if (typeof gtag !== 'undefined') {
            gtag('event', event, {
                event_category: 'Quiz',
                event_label: data.category || '',
                custom_map: data
            });
        }

        console.log('📊 Event tracked:', event, data);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 DOM ready, initializing Premium Hero Quiz System...');
    window.premiumHeroQuiz = new PremiumHeroQuizSystem();
});

// Fallback initialization if DOM is already loaded
if (document.readyState !== 'loading') {
    console.log('🚀 DOM already loaded, initializing Premium Hero Quiz System...');
    window.premiumHeroQuiz = new PremiumHeroQuizSystem();
}
