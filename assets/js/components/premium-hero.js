/**
 * Elegant Quiz System - Single Step Display with Auto-Advance
 * Implements the redesigned treatment selection quiz
 *
 * @version 3.0.0
 */

class ElegantQuizSystem {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 5;
        this.selections = {
            category: null,
            area: null,
            experience: null,
            age: null,
            contact: {}
        };
        this.stepHistory = [];
        this.autoAdvanceTimer = null;
        this.autoAdvanceDelay = 800; // 800ms as specified
        this.currentFormField = 'name';

        this.init();
    }

    init() {
        console.log('🎯 Initializing Elegant Quiz System v3.0...');

        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }

    setup() {
        console.log('⚙️ Setting up elegant quiz system...');

        // Verify quiz container exists
        this.quizContainer = document.querySelector('.elegant-quiz');
        if (!this.quizContainer) {
            console.error('❌ Elegant quiz container not found');
            return;
        }

        this.bindEvents();
        this.showStep(1);
        this.updateStepDisplay();

        console.log('✅ Elegant quiz system ready!');
    }

    bindEvents() {
        // Pill selection (category, areas, experience, age)
        document.addEventListener('click', (e) => {
            const pill = e.target.closest('.quiz-pill');
            if (pill && this.quizContainer.contains(pill)) {
                e.preventDefault();
                this.selectPill(pill);
            }
        });

        // Back navigation
        document.addEventListener('click', (e) => {
            const backBtn = e.target.closest('.quiz-back-btn');
            if (backBtn && this.quizContainer.contains(backBtn)) {
                e.preventDefault();
                this.goBack();
            }
        });

        // Continue button (for demographics step)
        document.addEventListener('click', (e) => {
            const continueBtn = e.target.closest('.quiz-continue-btn');
            if (continueBtn && this.quizContainer.contains(continueBtn)) {
                e.preventDefault();
                this.goToNextStep();
            }
        });

        // Form field interactions
        document.addEventListener('input', (e) => {
            const input = e.target.closest('.quiz-form-field input');
            if (input && this.quizContainer.contains(input)) {
                this.handleFormInput(e);
            }
        });

        // Form submission
        const form = document.querySelector('.quiz-contact-form');
        if (form) {
            form.addEventListener('submit', (e) => this.handleFormSubmission(e));
        }

        console.log('🔗 Events bound successfully');
    }

    selectPill(pill) {
        const step = this.currentStep;

        // Clear any existing auto-advance timer
        this.clearAutoAdvance();

        // Add selection animation
        pill.classList.add('selecting');

        // Clear previous selections in current step
        const currentStepEl = document.querySelector(`.quiz-step[data-step="${step}"]`);
        currentStepEl.querySelectorAll('.quiz-pill').forEach(p => {
            p.classList.remove('selected');
        });

        // Mark as selected
        pill.classList.add('selected');

        // Store selection based on current step
        this.storeSelection(step, pill);

        // Schedule auto-advance
        this.scheduleAutoAdvance(() => {
            this.goToNextStep();
        });

        // Remove animation class after animation completes
        setTimeout(() => {
            pill.classList.remove('selecting');
        }, 300);
    }

    storeSelection(step, pill) {
        switch(step) {
            case 1: // Category
                this.selections.category = pill.dataset.category;
                console.log('📁 Category selected:', this.selections.category);
                this.loadTreatmentAreas(this.selections.category);
                break;

            case 2: // Treatment area
                this.selections.area = pill.dataset.area;
                console.log('🎯 Area selected:', this.selections.area);
                break;

            case 3: // Experience
                this.selections.experience = pill.dataset.experience;
                console.log('📈 Experience selected:', this.selections.experience);
                break;

            case 4: // Age
                this.selections.age = pill.dataset.age;
                console.log('👤 Age selected:', this.selections.age);
                break;
        }
    }

    loadTreatmentAreas(category) {
        const areasGrid = document.getElementById('treatment-areas-grid');
        if (!areasGrid) return;

        // Treatment areas mapping
        const treatmentAreas = {
            'botox': [
                { area: 'forehead', icon: '👤', text: 'Forehead' },
                { area: 'crows-feet', icon: '👁️', text: 'Crow\'s Feet' },
                { area: 'frown-lines', icon: '😐', text: 'Frown Lines' },
                { area: 'bunny-lines', icon: '👃', text: 'Bunny Lines' },
                { area: 'neck-bands', icon: '🦒', text: 'Neck Bands' },
                { area: 'jaw-clenching', icon: '😬', text: 'Jaw Clenching' }
            ],
            'dermal-fillers': [
                { area: 'lips', icon: '💋', text: 'Lips' },
                { area: 'cheeks', icon: '😊', text: 'Cheeks' },
                { area: 'nasolabial', icon: '😌', text: 'Smile Lines' },
                { area: 'chin', icon: '🤔', text: 'Chin' },
                { area: 'under-eye', icon: '👁️', text: 'Under Eyes' },
                { area: 'temples', icon: '🧠', text: 'Temples' }
            ],
            'laser-hair-removal': [
                { area: 'face', icon: '😊', text: 'Face' },
                { area: 'underarms', icon: '💪', text: 'Underarms' },
                { area: 'legs', icon: '🦵', text: 'Legs' },
                { area: 'bikini', icon: '👙', text: 'Bikini' },
                { area: 'back', icon: '🔙', text: 'Back' },
                { area: 'chest', icon: '👕', text: 'Chest' }
            ],
            'coolsculpting': [
                { area: 'abdomen', icon: '🤰', text: 'Abdomen' },
                { area: 'love-handles', icon: '🤗', text: 'Love Handles' },
                { area: 'thighs', icon: '🦵', text: 'Thighs' },
                { area: 'arms', icon: '💪', text: 'Arms' },
                { area: 'double-chin', icon: '🤔', text: 'Double Chin' },
                { area: 'back-fat', icon: '🔙', text: 'Back Fat' }
            ],
            'clear-brilliant': [
                { area: 'full-face', icon: '😊', text: 'Full Face' },
                { area: 'spot-treatment', icon: '🎯', text: 'Spot Treatment' },
                { area: 'neck', icon: '🦒', text: 'Neck' },
                { area: 'décolletage', icon: '👗', text: 'Décolletage' }
            ],
            'ipl-photofacials': [
                { area: 'full-face', icon: '😊', text: 'Full Face' },
                { area: 'sun-damage', icon: '☀️', text: 'Sun Damage' },
                { area: 'rosacea', icon: '🌹', text: 'Rosacea' },
                { area: 'age-spots', icon: '🟤', text: 'Age Spots' }
            ],
            'skin-rejuvenation': [
                { area: 'anti-aging', icon: '⏰', text: 'Anti-Aging' },
                { area: 'acne-scars', icon: '🔴', text: 'Acne Scars' },
                { area: 'texture', icon: '✨', text: 'Skin Texture' },
                { area: 'pores', icon: '🕳️', text: 'Large Pores' }
            ],
            'tattoo-removal': [
                { area: 'small', icon: '🔸', text: 'Small Tattoo' },
                { area: 'medium', icon: '🔷', text: 'Medium Tattoo' },
                { area: 'large', icon: '🔵', text: 'Large Tattoo' },
                { area: 'cover-up', icon: '🎨', text: 'Cover-up Prep' }
            ],
            'thermage': [
                { area: 'face-lift', icon: '⬆️', text: 'Face Tightening' },
                { area: 'eye-lift', icon: '👁️', text: 'Eye Tightening' },
                { area: 'body-contouring', icon: '🏃', text: 'Body Contouring' }
            ],
            'hydrafacial': [
                { area: 'signature', icon: '✨', text: 'Signature HydraFacial' },
                { area: 'deluxe', icon: '💎', text: 'Deluxe HydraFacial' },
                { area: 'platinum', icon: '🏆', text: 'Platinum HydraFacial' }
            ],
            'potenza-rf': [
                { area: 'face', icon: '😊', text: 'Face Treatment' },
                { area: 'neck', icon: '🦒', text: 'Neck Treatment' },
                { area: 'body', icon: '🏃', text: 'Body Treatment' },
                { area: 'acne-scars', icon: '🔴', text: 'Acne Scars' }
            ]
        };

        const areas = treatmentAreas[category] || [];

        areasGrid.innerHTML = areas.map(area => `
            <button class="quiz-pill" data-area="${area.area}" tabindex="0">
                <span class="quiz-icon">${area.icon}</span>
                <span class="quiz-pill-text">${area.text}</span>
            </button>
        `).join('');
    }

    scheduleAutoAdvance(callback) {
        this.autoAdvanceTimer = setTimeout(() => {
            callback();
        }, this.autoAdvanceDelay);
    }

    clearAutoAdvance() {
        if (this.autoAdvanceTimer) {
            clearTimeout(this.autoAdvanceTimer);
            this.autoAdvanceTimer = null;
        }
    }

    goToNextStep() {
        this.clearAutoAdvance();

        const nextStep = this.currentStep + 1;

        if (nextStep <= this.totalSteps) {
            this.stepHistory.push(this.currentStep);
            this.currentStep = nextStep;
            this.showStep(this.currentStep);
            this.updateStepDisplay();
        }
    }

    goBack() {
        this.clearAutoAdvance();

        if (this.stepHistory.length > 0) {
            this.currentStep = this.stepHistory.pop();
            this.showStep(this.currentStep);
            this.updateStepDisplay();
        }
    }

    showStep(stepNumber) {
        // Hide all steps
        document.querySelectorAll('.quiz-step').forEach(step => {
            step.classList.remove('active');
            step.classList.add('exiting');
        });

        // Show current step after brief delay for smooth transition
        setTimeout(() => {
            document.querySelectorAll('.quiz-step').forEach(step => {
                step.classList.remove('exiting');
            });

            const currentStepEl = document.querySelector(`.quiz-step[data-step="${stepNumber}"]`);
            if (currentStepEl) {
                currentStepEl.classList.add('active');

                // Focus management for accessibility
                const firstInteractiveElement = currentStepEl.querySelector('button, input');
                if (firstInteractiveElement) {
                    firstInteractiveElement.focus();
                }
            }
        }, 200);
    }

    updateStepDisplay() {
        // Update question text based on step and previous selections
        const questionEl = document.querySelector(`.quiz-step[data-step="${this.currentStep}"] .quiz-question`);
        if (questionEl && this.currentStep === 5) {
            this.updateContactStepQuestion();
        }
    }

    updateContactStepQuestion() {
        const questionEl = document.querySelector('.quiz-step[data-step="5"] .quiz-question');

        if (this.currentFormField === 'name') {
            questionEl.textContent = 'What is your full name?';
        } else if (this.currentFormField === 'email') {
            questionEl.textContent = 'What is your email address?';
        } else if (this.currentFormField === 'phone') {
            questionEl.textContent = 'What is your phone number?';
        }
    }

    handleFormInput(e) {
        const input = e.target;
        const field = input.closest('.quiz-form-field');
        const fieldType = field.dataset.field;

        // Store contact information
        this.selections.contact[fieldType] = input.value;

        // Progressive disclosure logic
        if (fieldType === 'name' && input.value.trim().length >= 2) {
            this.revealNextFormField('email');
        } else if (fieldType === 'email' && this.isValidEmail(input.value)) {
            this.revealNextFormField('phone');
        } else if (fieldType === 'phone' && input.value.trim().length >= 10) {
            this.revealFormActions();
        }

        // Update question text
        if (fieldType === 'name' && input.value.trim().length >= 2 && this.currentFormField === 'name') {
            this.currentFormField = 'email';
            this.updateContactStepQuestion();
        } else if (fieldType === 'email' && this.isValidEmail(input.value) && this.currentFormField === 'email') {
            this.currentFormField = 'phone';
            this.updateContactStepQuestion();
        }
    }

    revealNextFormField(fieldType) {
        const field = document.querySelector(`.quiz-form-field[data-field="${fieldType}"]`);
        if (field && field.classList.contains('quiz-form-field-hidden')) {
            field.classList.remove('quiz-form-field-hidden');

            // Focus the newly revealed field
            setTimeout(() => {
                const input = field.querySelector('input');
                if (input) input.focus();
            }, 300);
        }
    }

    revealFormActions() {
        const actions = document.querySelector('.quiz-form-actions');
        if (actions && actions.classList.contains('quiz-form-actions-hidden')) {
            actions.classList.remove('quiz-form-actions-hidden');
        }
    }

    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    async handleFormSubmission(e) {
        e.preventDefault();

        // Validate all required fields
        if (!this.validateContactForm()) {
            return;
        }

        console.log('📧 Submitting quiz data:', this.selections);

        try {
            this.showSubmissionLoader();

            const response = await fetch(premiumHeroAjax.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'submit_elegant_quiz',
                    selections: JSON.stringify(this.selections),
                    nonce: premiumHeroAjax.nonce
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showSuccessMessage();
            } else {
                throw new Error(data.data || 'Submission failed');
            }

        } catch (error) {
            console.error('❌ Form submission error:', error);
            this.showErrorMessage('There was an error submitting your quiz. Please try again.');
        } finally {
            this.hideSubmissionLoader();
        }
    }

    validateContactForm() {
        const { contact } = this.selections;

        if (!contact.full_name || contact.full_name.trim().length < 2) {
            this.showErrorMessage('Please enter your full name');
            return false;
        }

        if (!contact.email || !this.isValidEmail(contact.email)) {
            this.showErrorMessage('Please enter a valid email address');
            return false;
        }

        if (!contact.phone || contact.phone.trim().length < 10) {
            this.showErrorMessage('Please enter a valid phone number');
            return false;
        }

        return true;
    }

    showSubmissionLoader() {
        const submitBtn = document.querySelector('.quiz-submit-btn');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <div class="loading-spinner"></div>
                Submitting...
            `;
        }
    }

    hideSubmissionLoader() {
        const submitBtn = document.querySelector('.quiz-submit-btn');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `
                Get My Personalized Plan
                <span class="quiz-submit-icon">📧</span>
            `;
        }
    }

    showSuccessMessage() {
        // Hide current step
        document.querySelectorAll('.quiz-step').forEach(step => {
            step.classList.remove('active');
        });

        // Show success step
        const successStep = document.querySelector('.quiz-success');
        if (successStep) {
            successStep.classList.add('active');
        }
    }

    showErrorMessage(message) {
        // Create or update error message
        let errorDiv = document.querySelector('.quiz-error-message');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'quiz-error-message';
            errorDiv.style.cssText = `
                background: #fef2f2;
                border: 1px solid #fca5a5;
                border-radius: 8px;
                padding: 1rem;
                margin-bottom: 1rem;
                color: #dc2626;
                text-align: center;
            `;

            const form = document.querySelector('.quiz-contact-form');
            if (form) {
                form.insertBefore(errorDiv, form.firstChild);
            }
        }

        errorDiv.textContent = message;

        // Auto-hide after 5 seconds
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.parentNode.removeChild(errorDiv);
            }
        }, 5000);
    }
}

// Initialize the elegant quiz system when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Only initialize if the elegant quiz container exists
    if (document.querySelector('.elegant-quiz')) {
        window.elegantQuiz = new ElegantQuizSystem();
    }
});

// Export for testing/debugging
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ElegantQuizSystem;
}
