/**
 * Elegant Quiz Component - Modular Implementation
 *
 * @package MedSpaTheme
 * @since 1.0.0
 *
 * Features:
 * - Component-based architecture with proper cleanup
 * - Enhanced accessibility with screen reader support
 * - Progressive enhancement (works without JS)
 * - Memory leak prevention
 * - Error boundaries and graceful degradation
 * - Performance optimizations with debouncing/throttling
 */

class ElegantQuizComponent {
    constructor(containerElement, config = {}) {
        // Validate requirements
        if (!containerElement || !containerElement.nodeType) {
            throw new Error('ElegantQuizComponent requires a valid DOM element');
        }

        // Core properties
        this.container = containerElement;
        this.config = {
            autoAdvanceDelay: 800,
            animationDuration: 300,
            enableAnimations: !window.matchMedia('(prefers-reduced-motion: reduce)').matches,
            ...config
        };

        // State management
        this.state = {
            currentStep: 1,
            maxSteps: 5,
            selections: new Map(),
            stepHistory: [],
            isSubmitting: false,
            isInitialized: false
        };

        // Event handlers (bound for proper cleanup)
        this.boundHandlers = {
            pillClick: this.handlePillSelection.bind(this),
            backClick: this.handleBackNavigation.bind(this),
            formSubmit: this.handleFormSubmission.bind(this),
            formInput: this.handleFormInput.bind(this),
            keydown: this.handleKeydown.bind(this),
            visibilityChange: this.handleVisibilityChange.bind(this)
        };

        // References to timers and async operations
        this.timers = new Set();
        this.abortController = new AbortController();

        // Treatment area mappings
        this.treatmentAreas = {
            'botox': [
                {value: 'forehead', text: 'Forehead Lines', icon: '👁️'},
                {value: 'crow-feet', text: 'Crow\'s Feet', icon: '👀'},
                {value: 'frown-lines', text: 'Frown Lines', icon: '😤'},
                {value: 'bunny-lines', text: 'Bunny Lines', icon: '🐰'}
            ],
            'dermal-fillers': [
                {value: 'lips', text: 'Lips', icon: '💋'},
                {value: 'cheeks', text: 'Cheeks', icon: '🌹'},
                {value: 'nasolabial', text: 'Nasolabial Folds', icon: '📐'},
                {value: 'under-eyes', text: 'Under Eyes', icon: '👁️'}
            ],
            'laser-hair-removal': [
                {value: 'legs', text: 'Legs', icon: '🦵'},
                {value: 'underarms', text: 'Underarms', icon: '🙋'},
                {value: 'bikini', text: 'Bikini Area', icon: '👙'},
                {value: 'face', text: 'Face', icon: '👤'}
            ],
            'coolsculpting': [
                {value: 'abdomen', text: 'Abdomen', icon: '💪'},
                {value: 'flanks', text: 'Love Handles', icon: '🤗'},
                {value: 'thighs', text: 'Thighs', icon: '🦵'},
                {value: 'arms', text: 'Arms', icon: '💪'}
            ],
            // Default for other treatments
            'default': [
                {value: 'face', text: 'Face', icon: '👤'},
                {value: 'neck', text: 'Neck', icon: '🦢'},
                {value: 'body', text: 'Body', icon: '🏃'},
                {value: 'hands', text: 'Hands', icon: '🤲'}
            ]
        };

        this.init();
    }

    /**
     * Initialize the component
     */
    async init() {
        try {
            this.setupEventListeners();
            this.initializeAccessibility();
            await this.showStep(1);
            this.state.isInitialized = true;
            this.announceToScreenReader('Quiz loaded successfully');
        } catch (error) {
            this.handleError('Failed to initialize quiz', error);
        }
    }

    /**
     * Setup event listeners with proper cleanup tracking
     */
    setupEventListeners() {
        const { signal } = this.abortController;

        // Pill selection (event delegation for performance)
        this.container.addEventListener('click', (e) => {
            const pill = e.target.closest('.quiz-pill');
            if (pill) {
                e.preventDefault();
                this.boundHandlers.pillClick(pill);
            }
        }, { signal });

        // Navigation
        this.container.addEventListener('click', (e) => {
            if (e.target.closest('.quiz-back-btn')) {
                e.preventDefault();
                this.boundHandlers.backClick();
            }
        }, { signal });

        // Form submission
        this.container.addEventListener('submit', this.boundHandlers.formSubmit, { signal });

        // Form input (debounced for performance)
        this.container.addEventListener('input',
            this.debounce(this.boundHandlers.formInput, 300),
            { signal }
        );

        // Keyboard navigation
        this.container.addEventListener('keydown', this.boundHandlers.keydown, { signal });

        // Handle visibility changes (pause animations when hidden)
        document.addEventListener('visibilitychange', this.boundHandlers.visibilityChange, { signal });
    }

    /**
     * Initialize accessibility features
     */
    initializeAccessibility() {
        // Set up ARIA live region for announcements
        this.liveRegion = this.container.querySelector('[role="status"][aria-live="polite"]');

        // Set initial focus management
        this.container.setAttribute('aria-busy', 'false');

        // Set up keyboard navigation hints
        this.addKeyboardInstructions();
    }

    /**
     * Handle pill selection with improved UX
     */
    async handlePillSelection(pill) {
        if (this.state.isSubmitting || pill.classList.contains('selected')) {
            return;
        }

        try {
            // Visual feedback
            this.clearSelections();
            pill.classList.add('selecting');

            // Store selection
            const stepType = pill.dataset.step;
            const value = pill.dataset[stepType] || pill.dataset.value;
            this.state.selections.set(stepType, value);

            // Accessibility announcement
            const selectedText = pill.querySelector('.quiz-pill-text')?.textContent;
            this.announceToScreenReader(`Selected: ${selectedText}`);

            // Animation feedback
            if (this.config.enableAnimations) {
                await this.animateSelection(pill);
            }

            // Auto-advance with configurable delay
            this.scheduleAutoAdvance();

        } catch (error) {
            this.handleError('Selection failed', error);
        }
    }

    /**
     * Animate selection with performance considerations
     */
    async animateSelection(pill) {
        return new Promise((resolve) => {
            pill.classList.add('selected');

            const timer = setTimeout(() => {
                pill.classList.remove('selecting');
                resolve();
            }, this.config.animationDuration);

            this.timers.add(timer);
        });
    }

    /**
     * Schedule auto-advance with proper cleanup
     */
    scheduleAutoAdvance() {
        const timer = setTimeout(() => {
            if (this.state.isInitialized && !this.state.isSubmitting) {
                this.goToNextStep();
            }
        }, this.config.autoAdvanceDelay);

        this.timers.add(timer);
    }

    /**
     * Show step with enhanced transitions and accessibility
     */
    async showStep(stepNumber) {
        if (stepNumber < 1 || stepNumber > this.state.maxSteps) {
            throw new Error(`Invalid step number: ${stepNumber}`);
        }

        try {
            // Update ARIA busy state
            this.container.setAttribute('aria-busy', 'true');

            // Hide current steps
            const currentSteps = this.container.querySelectorAll('.quiz-step.active');
            await this.hideSteps(currentSteps);

            // Show target step
            const targetStep = this.container.querySelector(`[data-step="${stepNumber}"]`);
            if (!targetStep) {
                throw new Error(`Step ${stepNumber} not found`);
            }

            await this.showStepElement(targetStep, stepNumber);

            // Update state
            this.state.currentStep = stepNumber;

            // Update accessibility
            this.updateProgressAnnouncement(stepNumber);
            this.manageFocus(targetStep);

        } catch (error) {
            this.handleError(`Failed to show step ${stepNumber}`, error);
        } finally {
            this.container.setAttribute('aria-busy', 'false');
        }
    }

    /**
     * Hide steps with smooth animation
     */
    async hideSteps(steps) {
        if (!this.config.enableAnimations) {
            steps.forEach(step => {
                step.classList.remove('active');
                step.style.display = 'none';
            });
            return;
        }

        return Promise.all(Array.from(steps).map(step => {
            return new Promise(resolve => {
                step.classList.add('exiting');

                const timer = setTimeout(() => {
                    step.classList.remove('active', 'exiting');
                    step.style.display = 'none';
                    resolve();
                }, this.config.animationDuration);

                this.timers.add(timer);
            });
        }));
    }

    /**
     * Show step element with animation
     */
    async showStepElement(stepElement, stepNumber) {
        // Prepare content
        await this.renderStepContent(stepElement, stepNumber);

        // Show with animation
        stepElement.style.display = 'block';

        if (this.config.enableAnimations) {
            // Force reflow for animation
            stepElement.offsetHeight;
        }

        stepElement.classList.add('active');
    }

    /**
     * Render step content based on type
     */
    async renderStepContent(stepElement, stepNumber) {
        switch (stepNumber) {
            case 2:
                await this.renderTreatmentAreas(stepElement);
                break;
            case 5:
                this.initializeContactForm(stepElement);
                break;
        }
    }

    /**
     * Render treatment areas based on selection
     */
    async renderTreatmentAreas(stepElement) {
        const container = stepElement.querySelector('.quiz-grid');
        if (!container) return;

        const category = this.state.selections.get('category');
        const areas = this.treatmentAreas[category] || this.treatmentAreas.default;

        // Clear existing content
        container.innerHTML = '';

        // Add loading state for better UX
        container.innerHTML = '<div class="quiz-loading">Loading options...</div>';

        // Simulate async loading (replace with actual API call if needed)
        await this.delay(200);

        // Render areas
        container.innerHTML = areas.map(area => `
            <button class="quiz-pill"
                    data-area="${this.escapeHtml(area.value)}"
                    data-step="area"
                    type="button">
                <span class="quiz-icon" aria-hidden="true">${this.escapeHtml(area.icon)}</span>
                <span class="quiz-pill-text">${this.escapeHtml(area.text)}</span>
            </button>
        `).join('');
    }

    /**
     * Initialize contact form with progressive disclosure
     */
    initializeContactForm(stepElement) {
        const form = stepElement.querySelector('.quiz-contact-form');
        if (!form) return;

        // Reset form state
        form.reset();
        this.hideAllFormFields();
        this.showField('name');
    }

    /**
     * Handle form input with validation
     */
    handleFormInput(e) {
        const input = e.target;
        if (!input.closest('.quiz-form-field')) return;

        const fieldName = input.name;
        const value = input.value.trim();

        // Clear previous errors
        this.clearFieldError(input);

        // Validate field
        const isValid = this.validateField(fieldName, value);

        if (isValid && value.length > 0) {
            this.markFieldValid(input);
            this.progressFormFlow(fieldName);
        } else if (value.length > 0) {
            this.markFieldInvalid(input);
        }
    }

    /**
     * Progress through form fields
     */
    progressFormFlow(completedField) {
        const flowOrder = ['full_name', 'email', 'phone'];
        const currentIndex = flowOrder.indexOf(completedField);

        if (currentIndex !== -1 && currentIndex < flowOrder.length - 1) {
            const nextField = flowOrder[currentIndex + 1];
            this.showField(nextField === 'full_name' ? 'name' : nextField);
        } else if (currentIndex === flowOrder.length - 1) {
            this.showSubmitButton();
        }
    }

    /**
     * Validate individual field
     */
    validateField(fieldName, value) {
        switch (fieldName) {
            case 'full_name':
                return value.length >= 2 && /^[a-zA-Z\s\-'\.]+$/.test(value);
            case 'email':
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
            case 'phone':
                return /^[\+]?[1-9][\d]{0,15}$/.test(value.replace(/[\s\-\(\)]/g, ''));
            default:
                return value.length > 0;
        }
    }

    /**
     * Handle form submission with comprehensive error handling
     */
    async handleFormSubmission(e) {
        e.preventDefault();

        if (this.state.isSubmitting) return;

        try {
            this.state.isSubmitting = true;
            this.updateSubmitButton(true);

            const formData = new FormData(e.target);

            // Add selections to form data
            this.state.selections.forEach((value, key) => {
                formData.append(`quiz_${key}`, value);
            });

            // Add configuration
            const config = window.elegantQuizConfig || {};
            formData.append('action', 'submit_elegant_quiz');
            formData.append('nonce', config.nonce || '');
            formData.append('quiz_id', config.quizId || '');

            const response = await this.submitToServer(formData);

            if (response.success) {
                await this.showSuccessState(response.data);
            } else {
                throw new Error(response.data?.message || 'Submission failed');
            }

        } catch (error) {
            this.handleError('Form submission failed', error);
            this.showFormError(error.message);
        } finally {
            this.state.isSubmitting = false;
            this.updateSubmitButton(false);
        }
    }

    /**
     * Submit form data to server
     */
    async submitToServer(formData) {
        const config = window.elegantQuizConfig || {};
        const ajaxUrl = config.ajaxUrl || '/wp-admin/admin-ajax.php';

        const response = await fetch(ajaxUrl, {
            method: 'POST',
            body: formData,
            signal: this.abortController.signal
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return await response.json();
    }

    /**
     * Show success state
     */
    async showSuccessState(data) {
        await this.showStep('success');
        this.announceToScreenReader('Form submitted successfully! Thank you for your interest.');

        // Track conversion event
        this.trackConversion(data);
    }

    /**
     * Handle errors gracefully
     */
    handleError(message, error) {
        console.error(`ElegantQuiz Error: ${message}`, error);

        // Don't break the user experience
        this.announceToScreenReader('An error occurred. Please try again or contact us directly.');
    }

    /**
     * Utility methods
     */

    clearSelections() {
        this.container.querySelectorAll('.quiz-pill.selected, .quiz-pill.selecting')
            .forEach(pill => pill.classList.remove('selected', 'selecting'));
    }

    announceToScreenReader(message) {
        if (this.liveRegion) {
            this.liveRegion.textContent = message;
        }
    }

    updateProgressAnnouncement(stepNumber) {
        const config = window.elegantQuizConfig || {};
        const message = config.i18n?.stepOf?.replace('%1$d', stepNumber).replace('%2$d', this.state.maxSteps)
                       || `Step ${stepNumber} of ${this.state.maxSteps}`;
        this.announceToScreenReader(message);
    }

    manageFocus(stepElement) {
        // Focus first interactive element
        const firstInteractive = stepElement.querySelector('button, input, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (firstInteractive) {
            firstInteractive.focus();
        }
    }

    showField(fieldName) {
        const fieldElement = this.container.querySelector(`[data-field="${fieldName}"]`);
        if (fieldElement) {
            fieldElement.classList.remove('quiz-form-field-hidden');
            const input = fieldElement.querySelector('input');
            if (input) input.focus();
        }
    }

    hideAllFormFields() {
        this.container.querySelectorAll('.quiz-form-field').forEach(field => {
            field.classList.add('quiz-form-field-hidden');
        });
        this.container.querySelector('.quiz-form-actions')?.classList.add('quiz-form-actions-hidden');
    }

    showSubmitButton() {
        const actions = this.container.querySelector('.quiz-form-actions');
        if (actions) {
            actions.classList.remove('quiz-form-actions-hidden');
        }
    }

    updateSubmitButton(isLoading) {
        const button = this.container.querySelector('.quiz-submit-btn');
        if (!button) return;

        button.disabled = isLoading;
        button.classList.toggle('loading', isLoading);

        const text = button.querySelector('.btn-text');
        const spinner = button.querySelector('.loading-spinner');

        if (text) text.style.display = isLoading ? 'none' : '';
        if (spinner) spinner.style.display = isLoading ? 'inline-block' : 'none';
    }

    markFieldValid(input) {
        const field = input.closest('.quiz-form-field');
        if (field) {
            field.classList.remove('error');
            field.classList.add('valid');
        }
    }

    markFieldInvalid(input) {
        const field = input.closest('.quiz-form-field');
        if (field) {
            field.classList.remove('valid');
            field.classList.add('error');
        }
    }

    clearFieldError(input) {
        const field = input.closest('.quiz-form-field');
        if (field) {
            field.classList.remove('error', 'valid');
            const errorElement = field.querySelector('.field-error');
            if (errorElement) errorElement.textContent = '';
        }
    }

    showFormError(message) {
        const errorContainer = this.container.querySelector('.quiz-error-message');
        if (errorContainer) {
            errorContainer.textContent = message;
            errorContainer.style.display = 'block';
        }
    }

    goToNextStep() {
        if (this.state.currentStep < this.state.maxSteps) {
            this.state.stepHistory.push(this.state.currentStep);
            this.showStep(this.state.currentStep + 1);
        }
    }

    handleBackNavigation() {
        if (this.state.stepHistory.length > 0) {
            const previousStep = this.state.stepHistory.pop();
            this.showStep(previousStep);
        }
    }

    handleKeydown(e) {
        // ESC key to close/go back
        if (e.key === 'Escape' && this.state.currentStep > 1) {
            e.preventDefault();
            this.handleBackNavigation();
        }
    }

    handleVisibilityChange() {
        // Pause animations when page is hidden
        if (document.hidden) {
            this.config.enableAnimations = false;
        } else {
            this.config.enableAnimations = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        }
    }

    addKeyboardInstructions() {
        // Add keyboard navigation hints for screen readers
        const instructions = document.createElement('div');
        instructions.className = 'sr-only';
        instructions.innerHTML = 'Use Tab to navigate, Enter to select, and Escape to go back.';
        this.container.appendChild(instructions);
    }

    trackConversion(data) {
        // Analytics tracking
        if (typeof gtag !== 'undefined') {
            gtag('event', 'quiz_completion', {
                'event_category': 'engagement',
                'event_label': 'elegant_quiz',
                'value': data.lead_score || 0
            });
        }
    }

    // Utility functions
    delay(ms) {
        return new Promise(resolve => {
            const timer = setTimeout(resolve, ms);
            this.timers.add(timer);
        });
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Cleanup method for proper memory management
     */
    destroy() {
        // Abort any pending requests
        this.abortController.abort();

        // Clear all timers
        this.timers.forEach(timer => clearTimeout(timer));
        this.timers.clear();

        // Remove references
        this.container = null;
        this.liveRegion = null;
        this.state.selections.clear();

        console.log('ElegantQuizComponent destroyed');
    }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    const quizContainers = document.querySelectorAll('.elegant-quiz');

    quizContainers.forEach(container => {
        try {
            // Initialize with global config
            const config = window.elegantQuizConfig || {};
            new ElegantQuizComponent(container, config);
        } catch (error) {
            console.error('Failed to initialize ElegantQuizComponent:', error);
        }
    });
});

// Export for manual initialization if needed
window.ElegantQuizComponent = ElegantQuizComponent;
