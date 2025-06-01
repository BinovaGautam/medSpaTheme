/**
 * Elegant Quiz System - TASK-UX-QUIZ-003-01 Implementation
 * Premium Hero Component with Streamlined 5-Step Quiz
 *
 * Features:
 * - Single-step display with smooth transitions
 * - Horizontal pill layout with flex-wrap
 * - Theme color integration
 * - Working form submission
 * - Auto-advance after selection (800ms)
 * - Progressive form disclosure
 * - Responsive design for all devices
 */

class ElegantQuizSystem {
    constructor() {
        this.currentStep = 1;
        this.maxSteps = 5;
        this.selections = {};
        this.stepHistory = [];
        this.autoAdvanceTimer = null;
        this.isSubmitting = false;

        // Quiz questions and options
        this.quizData = {
            1: {
                question: "What treatment are you most interested in?",
                type: "category",
                options: [
                    { value: 'botox', text: 'Botox & Xeomin', icon: '💉' },
                    { value: 'dermal-fillers', text: 'Dermal Fillers', icon: '✨' },
                    { value: 'laser-hair-removal', text: 'Laser Hair Removal', icon: '🔥' },
                    { value: 'coolsculpting', text: 'CoolSculpting', icon: '❄️' },
                    { value: 'clear-brilliant', text: 'Clear & Brilliant', icon: '💎' },
                    { value: 'ipl-photofacials', text: 'IPL Photofacials', icon: '🌟' },
                    { value: 'skin-rejuvenation', text: 'Skin Rejuvenation', icon: '🌸' },
                    { value: 'tattoo-removal', text: 'Tattoo Removal', icon: '🎨' },
                    { value: 'thermage', text: 'Thermage', icon: '⚡' },
                    { value: 'hydrafacial', text: 'HydraFacial', icon: '💧' },
                    { value: 'potenza-rf', text: 'Potenza RF Microneedling', icon: '⭐' }
                ]
            },
            2: {
                question: "Which area would you like to focus on?",
                type: "area",
                options: this.getTreatmentAreas
            },
            3: {
                question: "How many times have you had this treatment before?",
                type: "experience",
                options: [
                    { value: '0', text: 'This would be my first time', icon: '🌱' },
                    { value: '1-2', text: '1-2 times previously', icon: '🌿' },
                    { value: '3-4', text: '3-4 times previously', icon: '🌳' },
                    { value: '5+', text: '5+ times previously', icon: '🏆' }
                ]
            },
            4: {
                question: "What's your age range?",
                type: "age",
                options: [
                    { value: '18-24', text: '18-24 years', icon: '🌼' },
                    { value: '25-34', text: '25-34 years', icon: '🌻' },
                    { value: '35-44', text: '35-44 years', icon: '🌺' },
                    { value: '45-54', text: '45-54 years', icon: '🌹' },
                    { value: '55+', text: '55+ years', icon: '🌷' }
                ]
            },
            5: {
                question: "Great! Let's schedule your consultation.",
                type: "contact",
                isForm: true
            }
        };

        this.init();
    }

    init() {
        this.setupEventListeners();
        this.showStep(1);
        this.initializeAOS();
    }

    setupEventListeners() {
        // Pill selection listeners
        document.addEventListener('click', (e) => {
            if (e.target.closest('.quiz-pill')) {
                e.preventDefault();
                this.handlePillSelection(e.target.closest('.quiz-pill'));
            }
        });

        // Navigation listeners
        document.addEventListener('click', (e) => {
            if (e.target.closest('.quiz-back-btn')) {
                e.preventDefault();
                this.goToPreviousStep();
            }

            if (e.target.closest('.quiz-submit-btn')) {
                e.preventDefault();
                this.handleFormSubmission();
            }
        });

        // Form input listeners for progressive disclosure
        document.addEventListener('input', (e) => {
            if (e.target.closest('.quiz-form-field input')) {
                this.handleFormInput(e.target);
            }
        });
    }

    showStep(stepNumber) {
        // Hide all steps
        document.querySelectorAll('.quiz-step').forEach(step => {
            step.classList.remove('active');
            step.classList.add('exiting');
        });

        // Clear any existing auto-advance timer
        if (this.autoAdvanceTimer) {
            clearTimeout(this.autoAdvanceTimer);
            this.autoAdvanceTimer = null;
        }

        // Show target step after brief delay for exit animation
        setTimeout(() => {
            document.querySelectorAll('.quiz-step').forEach(step => {
                step.style.display = 'none';
                step.classList.remove('exiting');
            });

            const targetStep = document.querySelector(`#quiz-step-${stepNumber}`);
            if (targetStep) {
                targetStep.style.display = 'block';
                requestAnimationFrame(() => {
                    targetStep.classList.add('active');
                });
            }

            this.currentStep = stepNumber;
            this.renderStepContent(stepNumber);
        }, 200);
    }

    renderStepContent(stepNumber) {
        const stepData = this.quizData[stepNumber];
        const stepElement = document.querySelector(`#quiz-step-${stepNumber}`);

        if (!stepElement || !stepData) return;

        // Update question
        const questionElement = stepElement.querySelector('.quiz-question');
        if (questionElement) {
            questionElement.textContent = stepData.question;
        }

        // Render based on step type
        if (stepData.isForm) {
            this.renderContactForm(stepElement);
        } else {
            this.renderPillOptions(stepElement, stepData);
        }

        // Update navigation
        this.updateNavigation(stepElement, stepNumber);
    }

    renderPillOptions(stepElement, stepData) {
        const container = stepElement.querySelector('.quiz-grid');
        if (!container) return;

        let options = stepData.options;

        // Handle dynamic options (like treatment areas)
        if (typeof options === 'function') {
            options = options.call(this, this.selections.category);
        }

        // Clear existing content
        container.innerHTML = '';

        // Create pills with horizontal layout
        options.forEach(option => {
            const pill = document.createElement('div');
            pill.className = 'quiz-pill';
            pill.setAttribute('data-value', option.value);
            pill.setAttribute('data-step', stepData.type);

            // Determine if this is a wide pill (longer text)
            const isWide = option.text.length > 20;
            if (isWide) {
                pill.classList.add('quiz-pill-wide');
            }

            pill.innerHTML = `
                <span class="quiz-icon">${option.icon || '•'}</span>
                <span class="quiz-pill-text">${option.text}</span>
            `;

            // Check if this option is already selected
            if (this.selections[stepData.type] === option.value) {
                pill.classList.add('selected');
            }

            container.appendChild(pill);
        });
    }

    renderContactForm(stepElement) {
        const container = stepElement.querySelector('.quiz-contact-form');
        if (!container) return;

        container.innerHTML = `
            <div class="quiz-form-field" data-field="full_name">
                <input type="text"
                       id="quiz_full_name"
                       name="full_name"
                       placeholder="Your full name *"
                       required
                       value="${this.selections.contact?.full_name || ''}">
            </div>

            <div class="quiz-form-field quiz-form-field-hidden" data-field="email">
                <input type="email"
                       id="quiz_email"
                       name="email"
                       placeholder="Your email address *"
                       required
                       value="${this.selections.contact?.email || ''}">
            </div>

            <div class="quiz-form-field quiz-form-field-hidden" data-field="phone">
                <input type="tel"
                       id="quiz_phone"
                       name="phone"
                       placeholder="Your phone number *"
                       required
                       value="${this.selections.contact?.phone || ''}">
            </div>

            <div class="quiz-form-actions quiz-form-actions-hidden">
                <button type="submit" class="quiz-submit-btn" disabled>
                    <span class="quiz-submit-text">Get My Personalized Consultation</span>
                    <span class="quiz-submit-icon">→</span>
                </button>
            </div>
        `;

        // Initialize progressive disclosure
        this.updateFormProgression();
    }

    handlePillSelection(pill) {
        const value = pill.getAttribute('data-value');
        const stepType = pill.getAttribute('data-step');

        if (!value || !stepType) return;

        // Visual feedback
        pill.classList.add('selecting');
        setTimeout(() => pill.classList.remove('selecting'), 300);

        // Update selection
        this.selections[stepType] = value;

        // Update visual state
        const container = pill.closest('.quiz-grid');
        container.querySelectorAll('.quiz-pill').forEach(p => {
            p.classList.remove('selected');
        });
        pill.classList.add('selected');

        // Auto-advance after delay
        this.autoAdvanceTimer = setTimeout(() => {
            this.goToNextStep();
        }, 800);
    }

    handleFormInput(input) {
        const fieldName = input.name;
        const value = input.value.trim();

        // Store the value
        if (!this.selections.contact) {
            this.selections.contact = {};
        }
        this.selections.contact[fieldName] = value;

        // Update form progression
        this.updateFormProgression();
    }

    updateFormProgression() {
        const container = document.querySelector('.quiz-contact-form');
        if (!container) return;

        const nameField = container.querySelector('[data-field="full_name"] input');
        const emailField = container.querySelector('[data-field="email"]');
        const phoneField = container.querySelector('[data-field="phone"]');
        const submitActions = container.querySelector('.quiz-form-actions');
        const submitBtn = container.querySelector('.quiz-submit-btn');

        // Progressive field revelation
        if (nameField && nameField.value.trim().length >= 2) {
            emailField.classList.remove('quiz-form-field-hidden');

            const emailInput = emailField.querySelector('input');
            if (emailInput && this.isValidEmail(emailInput.value)) {
                phoneField.classList.remove('quiz-form-field-hidden');

                const phoneInput = phoneField.querySelector('input');
                if (phoneInput && phoneInput.value.trim().length >= 10) {
                    submitActions.classList.remove('quiz-form-actions-hidden');
                    submitBtn.disabled = false;
                }
            }
        }
    }

    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    async handleFormSubmission() {
        if (this.isSubmitting) return;

        // Validate all required fields are filled
        const contact = this.selections.contact;
        if (!contact || !contact.full_name || !contact.email || !contact.phone) {
            this.showError('Please fill in all required fields.');
            return;
        }

        if (!this.isValidEmail(contact.email)) {
            this.showError('Please enter a valid email address.');
            return;
        }

        this.isSubmitting = true;
        this.updateSubmitButton(true);

        try {
            const response = await fetch(premiumHeroAjax.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'submit_elegant_quiz',
                    nonce: premiumHeroAjax.nonce,
                    selections: JSON.stringify(this.selections)
                })
            });

            const result = await response.json();

            if (result.success) {
                this.showSuccessState(result.data);
            } else {
                throw new Error(result.data || 'Submission failed');
            }

        } catch (error) {
            console.error('Quiz submission error:', error);
            this.showError(error.message || 'There was an error submitting your quiz. Please try again.');
        } finally {
            this.isSubmitting = false;
            this.updateSubmitButton(false);
        }
    }

    updateSubmitButton(isLoading) {
        const submitBtn = document.querySelector('.quiz-submit-btn');
        const submitText = document.querySelector('.quiz-submit-text');
        const submitIcon = document.querySelector('.quiz-submit-icon');

        if (!submitBtn) return;

        if (isLoading) {
            submitBtn.disabled = true;
            submitText.textContent = 'Submitting...';
            submitIcon.innerHTML = '<span class="loading-spinner"></span>';
        } else {
            submitBtn.disabled = false;
            submitText.textContent = 'Get My Personalized Consultation';
            submitIcon.textContent = '→';
        }
    }

    showSuccessState(data) {
        const currentStepElement = document.querySelector(`#quiz-step-${this.currentStep}`);
        if (!currentStepElement) return;

        currentStepElement.innerHTML = `
            <div class="quiz-success">
                <div class="quiz-success-icon">✅</div>
                <h3 class="quiz-success-title">Thank You!</h3>
                <p class="quiz-success-message">
                    ${data.message}
                </p>
                <div class="quiz-success-actions">
                    <a href="tel:${this.getPhoneNumber()}" class="quiz-submit-btn">
                        Call Us Now
                    </a>
                    <button class="quiz-back-btn" onclick="location.reload()">
                        Take Quiz Again
                    </button>
                </div>
            </div>
        `;
    }

    showError(message) {
        // Remove any existing error messages
        const existingError = document.querySelector('.quiz-error-message');
        if (existingError) {
            existingError.remove();
        }

        // Create new error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'quiz-error-message';
        errorDiv.textContent = message;

        // Insert before the form actions
        const formActions = document.querySelector('.quiz-form-actions');
        if (formActions) {
            formActions.parentNode.insertBefore(errorDiv, formActions);
        }

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.remove();
            }
        }, 5000);
    }

    goToNextStep() {
        if (this.currentStep < this.maxSteps) {
            this.stepHistory.push(this.currentStep);
            this.showStep(this.currentStep + 1);
        }
    }

    goToPreviousStep() {
        if (this.stepHistory.length > 0) {
            const previousStep = this.stepHistory.pop();
            this.showStep(previousStep);
        }
    }

    updateNavigation(stepElement, stepNumber) {
        let navigationHtml = '';

        if (stepNumber > 1) {
            navigationHtml += `
                <button class="quiz-back-btn">
                    ← Back
                </button>
            `;
        }

        const navContainer = stepElement.querySelector('.quiz-navigation');
        if (navContainer) {
            navContainer.innerHTML = navigationHtml;
        }
    }

    getTreatmentAreas(category) {
        const areaMap = {
            'botox': [
                { value: 'forehead-lines', text: 'Forehead Lines', icon: '🧠' },
                { value: 'frown-lines', text: 'Frown Lines', icon: '😤' },
                { value: 'crows-feet', text: "Crow's Feet", icon: '👁️' },
                { value: 'lip-lines', text: 'Lip Lines', icon: '💋' },
                { value: 'neck-bands', text: 'Neck Bands', icon: '🦢' },
                { value: 'jawline', text: 'Jawline Slimming', icon: '💪' }
            ],
            'dermal-fillers': [
                { value: 'lips', text: 'Lips', icon: '💋' },
                { value: 'cheeks', text: 'Cheeks', icon: '😊' },
                { value: 'under-eyes', text: 'Under Eyes', icon: '👁️' },
                { value: 'nasolabial-folds', text: 'Smile Lines', icon: '😄' },
                { value: 'chin', text: 'Chin', icon: '🤏' },
                { value: 'temples', text: 'Temples', icon: '⏳' }
            ],
            'laser-hair-removal': [
                { value: 'legs', text: 'Legs', icon: '🦵' },
                { value: 'underarms', text: 'Underarms', icon: '💪' },
                { value: 'bikini', text: 'Bikini Area', icon: '👙' },
                { value: 'face', text: 'Face', icon: '😊' },
                { value: 'arms', text: 'Arms', icon: '💪' },
                { value: 'back', text: 'Back', icon: '🔙' }
            ],
            'coolsculpting': [
                { value: 'abdomen', text: 'Abdomen', icon: '🏃' },
                { value: 'love-handles', text: 'Love Handles', icon: '💕' },
                { value: 'thighs', text: 'Thighs', icon: '🦵' },
                { value: 'double-chin', text: 'Double Chin', icon: '🤏' },
                { value: 'arms', text: 'Arms', icon: '💪' },
                { value: 'back-fat', text: 'Back Fat', icon: '🔙' }
            ]
        };

        return areaMap[category] || [
            { value: 'face', text: 'Face', icon: '😊' },
            { value: 'body', text: 'Body', icon: '🏃' },
            { value: 'other', text: 'Other Area', icon: '📍' }
        ];
    }

    getPhoneNumber() {
        // This would typically come from theme settings
        return document.querySelector('[data-phone]')?.getAttribute('data-phone') || '';
    }

    initializeAOS() {
        // Initialize AOS (Animate On Scroll) if available
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 600,
                easing: 'ease-in-out',
                once: true,
                offset: 100
            });
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if we're on a page with the elegant quiz
    if (document.querySelector('.elegant-quiz')) {
        window.elegantQuizSystem = new ElegantQuizSystem();
    }
});

// Legacy compatibility - keep existing PremiumHeroQuizSystem class name for any external references
window.PremiumHeroQuizSystem = ElegantQuizSystem;
