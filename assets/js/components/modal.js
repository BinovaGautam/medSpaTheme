/**
 * Modal Component JavaScript
 *
 * Comprehensive modal interaction system with accessibility compliance,
 * keyboard navigation, focus management, and medical spa specializations.
 *
 * @package MedSpaTheme
 * @since 1.0.0
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 */

(function() {
    'use strict';

    /**
     * ModalManager Class
     * Handles all modal functionality and interactions
     */
    class ModalManager {
        constructor() {
            this.modals = new Map();
            this.activeModals = [];
            this.originalFocus = null;
            this.isAnimating = false;
            this.config = {
                bodyScrollLockClass: 'modal-scroll-lock',
                animationDuration: 300,
                focusableSelectors: [
                    'button:not([disabled])',
                    'input:not([disabled])',
                    'select:not([disabled])',
                    'textarea:not([disabled])',
                    'a[href]',
                    '[tabindex]:not([tabindex="-1"])',
                    '[contenteditable="true"]'
                ].join(', ')
            };

            this.init();
        }

        /**
         * Initialize modal manager
         */
        init() {
            this.bindEvents();
            this.setupAccessibility();
            this.discoverModals();

            // Performance optimization
            this.throttledResize = this.throttle(this.handleResize.bind(this), 250);
            window.addEventListener('resize', this.throttledResize);
        }

        /**
         * Bind global event listeners
         */
        bindEvents() {
            // Delegate click events for modal triggers
            document.addEventListener('click', (event) => {
                const trigger = event.target.closest('[data-modal-trigger]');
                if (trigger) {
                    event.preventDefault();
                    const modalId = trigger.getAttribute('data-modal-trigger');
                    this.openModal(modalId);
                }

                // Handle close button clicks
                const closeButton = event.target.closest('[data-modal-close]');
                if (closeButton) {
                    event.preventDefault();
                    const modalId = closeButton.getAttribute('data-modal-close');
                    this.closeModal(modalId);
                }

                // Handle backdrop clicks
                const backdrop = event.target.closest('.modal-backdrop-clickable');
                if (backdrop && event.target === backdrop) {
                    const modalId = backdrop.getAttribute('data-modal-backdrop');
                    this.closeModal(modalId);
                }
            });

            // Global keyboard handling
            document.addEventListener('keydown', (event) => {
                if (this.activeModals.length === 0) return;

                const activeModal = this.getActiveModal();
                if (!activeModal) return;

                switch (event.key) {
                    case 'Escape':
                        if (activeModal.config.closeOnEscape) {
                            event.preventDefault();
                            this.closeModal(activeModal.id);
                        }
                        break;

                    case 'Tab':
                        if (activeModal.config.trapFocus) {
                            this.handleTabKeyPress(event, activeModal);
                        }
                        break;
                }
            });

            // Handle page unload
            window.addEventListener('beforeunload', () => {
                this.closeAllModals();
            });
        }

        /**
         * Setup accessibility features
         */
        setupAccessibility() {
            // Create live region for announcements
            if (!document.getElementById('modal-live-region')) {
                const liveRegion = document.createElement('div');
                liveRegion.id = 'modal-live-region';
                liveRegion.setAttribute('aria-live', 'polite');
                liveRegion.setAttribute('aria-atomic', 'true');
                liveRegion.style.cssText = `
                    position: absolute;
                    left: -10000px;
                    width: 1px;
                    height: 1px;
                    overflow: hidden;
                `;
                document.body.appendChild(liveRegion);
            }
        }

        /**
         * Discover and register all modals on the page
         */
        discoverModals() {
            const modalElements = document.querySelectorAll('.modal-container');
            modalElements.forEach(modal => {
                this.registerModal(modal);
            });
        }

        /**
         * Register a modal element
         * @param {HTMLElement} modalElement - Modal container element
         */
        registerModal(modalElement) {
            const modalId = modalElement.id;
            if (!modalId) {
                console.warn('Modal element found without ID:', modalElement);
                return;
            }

            const config = {
                id: modalId,
                element: modalElement,
                dialog: modalElement.querySelector('.modal-dialog'),
                closeButton: modalElement.querySelector('.modal-close-button'),
                backdrop: document.querySelector(`[data-modal-backdrop="${modalId}"]`),
                closeOnEscape: modalElement.getAttribute('data-close-on-escape') !== 'false',
                closeOnBackdrop: modalElement.getAttribute('data-close-on-backdrop') !== 'false',
                trapFocus: modalElement.getAttribute('data-trap-focus') !== 'false',
                restoreFocus: modalElement.getAttribute('data-restore-focus') !== 'false',
                animationDuration: parseInt(modalElement.getAttribute('data-animation-duration')) || this.config.animationDuration,
                callbacks: {
                    onOpen: null,
                    onClose: null,
                    onConfirm: null,
                    onCancel: null
                }
            };

            this.modals.set(modalId, config);
            this.initializeModalElement(config);
        }

        /**
         * Initialize modal element properties
         * @param {Object} config - Modal configuration
         */
        initializeModalElement(config) {
            // Ensure proper ARIA attributes
            config.element.setAttribute('aria-hidden', 'true');
            config.element.setAttribute('aria-modal', 'true');
            config.element.setAttribute('role', 'dialog');

            // Set initial state
            config.element.style.display = 'none';

            // Setup backdrop if it exists
            if (config.backdrop) {
                config.backdrop.style.display = 'none';
            }
        }

        /**
         * Open a modal
         * @param {string} modalId - Modal ID to open
         * @param {Object} options - Additional options
         */
        openModal(modalId, options = {}) {
            if (this.isAnimating) return;

            const config = this.modals.get(modalId);
            if (!config) {
                console.error(`Modal with ID '${modalId}' not found`);
                return;
            }

            // Store original focus
            if (this.activeModals.length === 0) {
                this.originalFocus = document.activeElement;
            }

            // Add to active modals stack
            this.activeModals.push(config);

            // Lock body scroll
            this.lockBodyScroll();

            // Show modal
            this.showModalElement(config);

            // Handle focus
            this.setInitialFocus(config);

            // Announce to screen readers
            this.announceModalState('opened', config);

            // Call onOpen callback
            if (config.callbacks.onOpen) {
                config.callbacks.onOpen(config);
            }

            // Trigger custom event
            this.triggerEvent('modalOpened', { modalId, config });
        }

        /**
         * Close a modal
         * @param {string} modalId - Modal ID to close
         * @param {Object} options - Additional options
         */
        closeModal(modalId, options = {}) {
            if (this.isAnimating) return;

            const config = this.modals.get(modalId);
            if (!config) return;

            const modalIndex = this.activeModals.findIndex(m => m.id === modalId);
            if (modalIndex === -1) return;

            // Remove from active modals stack
            this.activeModals.splice(modalIndex, 1);

            // Hide modal
            this.hideModalElement(config);

            // Restore focus if this was the last modal
            if (this.activeModals.length === 0) {
                this.unlockBodyScroll();
                this.restoreFocus(config);
            } else {
                // Focus the next modal in stack
                const nextModal = this.getActiveModal();
                if (nextModal) {
                    this.setInitialFocus(nextModal);
                }
            }

            // Announce to screen readers
            this.announceModalState('closed', config);

            // Call onClose callback
            if (config.callbacks.onClose) {
                config.callbacks.onClose(config);
            }

            // Trigger custom event
            this.triggerEvent('modalClosed', { modalId, config });
        }

        /**
         * Close all open modals
         */
        closeAllModals() {
            const modalsToClose = [...this.activeModals];
            modalsToClose.forEach(config => {
                this.closeModal(config.id);
            });
        }

        /**
         * Show modal element with animation
         * @param {Object} config - Modal configuration
         */
        showModalElement(config) {
            this.isAnimating = true;

            // Show backdrop first
            if (config.backdrop) {
                config.backdrop.style.display = 'block';
                requestAnimationFrame(() => {
                    config.backdrop.classList.add('modal-backdrop-visible');
                });
            }

            // Show modal
            config.element.style.display = 'flex';
            config.element.setAttribute('aria-hidden', 'false');

            requestAnimationFrame(() => {
                config.element.classList.add('modal-open');
                config.element.classList.add('modal-opening');

                // End animation
                setTimeout(() => {
                    config.element.classList.remove('modal-opening');
                    this.isAnimating = false;
                }, config.animationDuration);
            });
        }

        /**
         * Hide modal element with animation
         * @param {Object} config - Modal configuration
         */
        hideModalElement(config) {
            this.isAnimating = true;

            config.element.classList.add('modal-closing');
            config.element.classList.remove('modal-open');

            // Hide backdrop
            if (config.backdrop) {
                config.backdrop.classList.remove('modal-backdrop-visible');
            }

            setTimeout(() => {
                config.element.style.display = 'none';
                config.element.setAttribute('aria-hidden', 'true');
                config.element.classList.remove('modal-closing');

                if (config.backdrop) {
                    config.backdrop.style.display = 'none';
                }

                this.isAnimating = false;
            }, config.animationDuration);
        }

        /**
         * Set initial focus in modal
         * @param {Object} config - Modal configuration
         */
        setInitialFocus(config) {
            // Try to focus the first focusable element
            const focusableElements = this.getFocusableElements(config.element);

            if (focusableElements.length > 0) {
                // Focus first input or first focusable element
                const firstInput = focusableElements.find(el =>
                    el.tagName === 'INPUT' || el.tagName === 'TEXTAREA' || el.tagName === 'SELECT'
                );

                if (firstInput) {
                    firstInput.focus();
                } else {
                    focusableElements[0].focus();
                }
            } else {
                // Fallback: focus the modal itself
                config.element.focus();
            }
        }

        /**
         * Restore focus to original element
         * @param {Object} config - Modal configuration
         */
        restoreFocus(config) {
            if (config.restoreFocus && this.originalFocus) {
                // Ensure the element is still in the DOM and focusable
                if (document.contains(this.originalFocus)) {
                    this.originalFocus.focus();
                }
            }
            this.originalFocus = null;
        }

        /**
         * Handle Tab key press for focus trapping
         * @param {KeyboardEvent} event - Keyboard event
         * @param {Object} config - Modal configuration
         */
        handleTabKeyPress(event, config) {
            const focusableElements = this.getFocusableElements(config.element);

            if (focusableElements.length === 0) {
                event.preventDefault();
                return;
            }

            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];

            if (event.shiftKey) {
                // Shift + Tab: moving backwards
                if (document.activeElement === firstElement) {
                    event.preventDefault();
                    lastElement.focus();
                }
            } else {
                // Tab: moving forwards
                if (document.activeElement === lastElement) {
                    event.preventDefault();
                    firstElement.focus();
                }
            }
        }

        /**
         * Get focusable elements within a container
         * @param {HTMLElement} container - Container element
         * @returns {HTMLElement[]} Array of focusable elements
         */
        getFocusableElements(container) {
            const elements = container.querySelectorAll(this.config.focusableSelectors);
            return Array.from(elements).filter(element => {
                return element.offsetWidth > 0 || element.offsetHeight > 0 || element.getClientRects().length > 0;
            });
        }

        /**
         * Lock body scroll
         */
        lockBodyScroll() {
            if (!document.body.classList.contains(this.config.bodyScrollLockClass)) {
                // Store current scroll position
                this.scrollPosition = window.pageYOffset;

                // Apply scroll lock
                document.body.classList.add(this.config.bodyScrollLockClass);
                document.body.style.top = `-${this.scrollPosition}px`;
            }
        }

        /**
         * Unlock body scroll
         */
        unlockBodyScroll() {
            if (document.body.classList.contains(this.config.bodyScrollLockClass)) {
                document.body.classList.remove(this.config.bodyScrollLockClass);
                document.body.style.top = '';

                // Restore scroll position
                if (this.scrollPosition !== undefined) {
                    window.scrollTo(0, this.scrollPosition);
                    this.scrollPosition = undefined;
                }
            }
        }

        /**
         * Get currently active modal
         * @returns {Object|null} Active modal config or null
         */
        getActiveModal() {
            return this.activeModals.length > 0 ? this.activeModals[this.activeModals.length - 1] : null;
        }

        /**
         * Announce modal state to screen readers
         * @param {string} action - Action performed (opened/closed)
         * @param {Object} config - Modal configuration
         */
        announceModalState(action, config) {
            const liveRegion = document.getElementById('modal-live-region');
            if (liveRegion) {
                const title = config.element.querySelector('.modal-title')?.textContent || 'Modal';
                liveRegion.textContent = `${title} dialog ${action}`;

                // Clear after announcement
                setTimeout(() => {
                    liveRegion.textContent = '';
                }, 1000);
            }
        }

        /**
         * Handle window resize
         */
        handleResize() {
            // Recalculate modal positions if needed
            this.activeModals.forEach(config => {
                // Custom resize handling can be added here
            });
        }

        /**
         * Set modal callback
         * @param {string} modalId - Modal ID
         * @param {string} callbackType - Callback type (onOpen, onClose, etc.)
         * @param {Function} callback - Callback function
         */
        setCallback(modalId, callbackType, callback) {
            const config = this.modals.get(modalId);
            if (config && config.callbacks.hasOwnProperty(callbackType)) {
                config.callbacks[callbackType] = callback;
            }
        }

        /**
         * Check if modal is open
         * @param {string} modalId - Modal ID
         * @returns {boolean} True if modal is open
         */
        isOpen(modalId) {
            return this.activeModals.some(config => config.id === modalId);
        }

        /**
         * Trigger custom event
         * @param {string} eventName - Event name
         * @param {Object} detail - Event detail
         */
        triggerEvent(eventName, detail) {
            const event = new CustomEvent(eventName, {
                detail,
                bubbles: true,
                cancelable: true
            });
            document.dispatchEvent(event);
        }

        /**
         * Throttle function calls
         * @param {Function} func - Function to throttle
         * @param {number} limit - Throttle limit in milliseconds
         * @returns {Function} Throttled function
         */
        throttle(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        }

        /**
         * Destroy modal manager
         */
        destroy() {
            this.closeAllModals();
            window.removeEventListener('resize', this.throttledResize);

            // Remove live region
            const liveRegion = document.getElementById('modal-live-region');
            if (liveRegion) {
                liveRegion.remove();
            }

            // Clear modals
            this.modals.clear();
            this.activeModals = [];
        }
    }

    /**
     * Medical Spa Modal Extensions
     * Specialized functionality for medical spa modals
     */
    class MedicalSpaModalExtensions {
        constructor(modalManager) {
            this.modalManager = modalManager;
            this.init();
        }

        init() {
            this.setupBookingModal();
            this.setupTreatmentInfoModal();
            this.setupGalleryModal();
            this.setupConsentModals();
        }

        /**
         * Setup booking modal functionality
         */
        setupBookingModal() {
            document.addEventListener('modalOpened', (event) => {
                const { modalId, config } = event.detail;

                if (config.element.classList.contains('modal-booking')) {
                    this.initializeBookingForm(config);
                }
            });
        }

        /**
         * Initialize booking form in modal
         * @param {Object} config - Modal configuration
         */
        initializeBookingForm(config) {
            const form = config.element.querySelector('form');
            if (!form) return;

            // Setup form validation
            this.setupFormValidation(form);

            // Setup date/time picker integration
            this.setupDateTimePicker(form);

            // Setup treatment selection
            this.setupTreatmentSelection(form);
        }

        /**
         * Setup treatment info modal
         */
        setupTreatmentInfoModal() {
            document.addEventListener('modalOpened', (event) => {
                const { modalId, config } = event.detail;

                if (config.element.classList.contains('modal-treatment-info')) {
                    this.loadTreatmentInfo(config);
                }
            });
        }

        /**
         * Load treatment information
         * @param {Object} config - Modal configuration
         */
        loadTreatmentInfo(config) {
            // Load treatment data if needed
            const treatmentId = config.element.getAttribute('data-treatment-id');
            if (treatmentId) {
                this.fetchTreatmentData(treatmentId, config);
            }
        }

        /**
         * Setup gallery modal functionality
         */
        setupGalleryModal() {
            document.addEventListener('modalOpened', (event) => {
                const { modalId, config } = event.detail;

                if (config.element.classList.contains('modal-gallery')) {
                    this.initializeGallery(config);
                }
            });
        }

        /**
         * Initialize gallery functionality
         * @param {Object} config - Modal configuration
         */
        initializeGallery(config) {
            // Setup image navigation
            this.setupImageNavigation(config);

            // Setup zoom functionality
            this.setupImageZoom(config);

            // Setup keyboard navigation
            this.setupGalleryKeyboard(config);
        }

        /**
         * Setup consent modals for HIPAA compliance
         */
        setupConsentModals() {
            // Implementation for consent management
            document.addEventListener('modalOpened', (event) => {
                const { modalId, config } = event.detail;

                if (config.element.classList.contains('modal-consent')) {
                    this.handleConsentModal(config);
                }
            });
        }

        /**
         * Setup form validation
         * @param {HTMLElement} form - Form element
         */
        setupFormValidation(form) {
            // Basic form validation implementation
            form.addEventListener('submit', (event) => {
                if (!this.validateForm(form)) {
                    event.preventDefault();
                }
            });
        }

        /**
         * Validate form
         * @param {HTMLElement} form - Form element
         * @returns {boolean} True if form is valid
         */
        validateForm(form) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    this.showFieldError(field, 'This field is required');
                    isValid = false;
                } else {
                    this.clearFieldError(field);
                }
            });

            return isValid;
        }

        /**
         * Show field error
         * @param {HTMLElement} field - Field element
         * @param {string} message - Error message
         */
        showFieldError(field, message) {
            field.classList.add('field-error');

            let errorElement = field.parentNode.querySelector('.field-error-message');
            if (!errorElement) {
                errorElement = document.createElement('div');
                errorElement.className = 'field-error-message';
                field.parentNode.appendChild(errorElement);
            }

            errorElement.textContent = message;
        }

        /**
         * Clear field error
         * @param {HTMLElement} field - Field element
         */
        clearFieldError(field) {
            field.classList.remove('field-error');

            const errorElement = field.parentNode.querySelector('.field-error-message');
            if (errorElement) {
                errorElement.remove();
            }
        }

        /**
         * Setup date/time picker (placeholder)
         * @param {HTMLElement} form - Form element
         */
        setupDateTimePicker(form) {
            // Integration with date/time picker library would go here
        }

        /**
         * Setup treatment selection (placeholder)
         * @param {HTMLElement} form - Form element
         */
        setupTreatmentSelection(form) {
            // Treatment selection logic would go here
        }

        /**
         * Fetch treatment data (placeholder)
         * @param {string} treatmentId - Treatment ID
         * @param {Object} config - Modal configuration
         */
        fetchTreatmentData(treatmentId, config) {
            // AJAX request to fetch treatment data would go here
        }

        /**
         * Setup image navigation (placeholder)
         * @param {Object} config - Modal configuration
         */
        setupImageNavigation(config) {
            // Image navigation logic would go here
        }

        /**
         * Setup image zoom (placeholder)
         * @param {Object} config - Modal configuration
         */
        setupImageZoom(config) {
            // Image zoom logic would go here
        }

        /**
         * Setup gallery keyboard navigation (placeholder)
         * @param {Object} config - Modal configuration
         */
        setupGalleryKeyboard(config) {
            // Gallery keyboard navigation would go here
        }

        /**
         * Handle consent modal (placeholder)
         * @param {Object} config - Modal configuration
         */
        handleConsentModal(config) {
            // Consent handling logic would go here
        }
    }

    /**
     * Initialize modal system when DOM is ready
     */
    function initializeModalSystem() {
        if (window.modalManager) {
            console.warn('Modal system already initialized');
            return;
        }

        // Create global modal manager instance
        window.modalManager = new ModalManager();

        // Initialize medical spa extensions
        window.medicalSpaModals = new MedicalSpaModalExtensions(window.modalManager);

        // Add CSS for scroll lock
        if (!document.getElementById('modal-scroll-lock-styles')) {
            const style = document.createElement('style');
            style.id = 'modal-scroll-lock-styles';
            style.textContent = `
                .modal-scroll-lock {
                    position: fixed;
                    width: 100%;
                    overflow: hidden;
                }
            `;
            document.head.appendChild(style);
        }

        console.log('Modal system initialized successfully');
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeModalSystem);
    } else {
        initializeModalSystem();
    }

    // Global API exposure
    window.MedSpaModal = {
        open: (modalId, options) => window.modalManager?.openModal(modalId, options),
        close: (modalId, options) => window.modalManager?.closeModal(modalId, options),
        closeAll: () => window.modalManager?.closeAllModals(),
        isOpen: (modalId) => window.modalManager?.isOpen(modalId),
        setCallback: (modalId, type, callback) => window.modalManager?.setCallback(modalId, type, callback)
    };

})();
