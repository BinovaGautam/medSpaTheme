/**
 * Modal Component JavaScript
 *
 * Interactive functionality for modal components including focus management,
 * keyboard navigation, accessibility compliance, and animation coordination.
 *
 * @package MedSpaTheme
 * @since 1.0.0
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 */

(function() {
    'use strict';

    /**
     * ModalManager - Main modal management class
     */
    class ModalManager {
        constructor() {
            this.activeModal = null;
            this.previouslyFocusedElement = null;
            this.isAnimating = false;
            this.scrollPosition = 0;
            this.modalStack = [];

            // Configuration
            this.config = {
                animationDuration: 300,
                backdrop: {
                    closeOnClick: true,
                    blur: true
                },
                keyboard: {
                    closeOnEscape: true,
                    trapFocus: true
                },
                accessibility: {
                    announceStateChanges: true,
                    restoreFocus: true
                }
            };

            this.init();
        }

        /**
         * Initialize modal manager
         */
        init() {
            this.bindGlobalEvents();
            this.setupKeyboardNavigation();
            this.setupAccessibilityFeatures();

            // Auto-initialize existing modals
            this.initializeModals();

            console.log('ModalManager initialized');
        }

        /**
         * Initialize all existing modals on page
         */
        initializeModals() {
            const modals = document.querySelectorAll('.modal-component');
            modals.forEach(modal => {
                this.setupModal(modal);
            });
        }

        /**
         * Setup individual modal
         */
        setupModal(modalElement) {
            if (modalElement.getAttribute('data-modal-initialized')) {
                return;
            }

            const modalId = modalElement.id || 'modal-' + Math.random().toString(36).substr(2, 9);
            modalElement.id = modalId;
            modalElement.setAttribute('data-modal-initialized', 'true');

            // Setup modal configuration from data attributes
            const modalConfig = this.getModalConfig(modalElement);
            modalElement.modalConfig = modalConfig;

            // Setup event listeners
            this.setupModalEvents(modalElement);

            // Setup triggers
            this.setupModalTriggers(modalElement);

            console.log(`Modal ${modalId} initialized`);
        }

        /**
         * Get modal configuration from data attributes
         */
        getModalConfig(modalElement) {
            return {
                id: modalElement.id,
                type: modalElement.getAttribute('data-modal-type') || 'basic',
                size: modalElement.getAttribute('data-modal-size') || 'medium',
                position: modalElement.getAttribute('data-modal-position') || 'center',
                closeOnBackdrop: modalElement.getAttribute('data-close-on-backdrop') !== 'false',
                closeOnEscape: modalElement.getAttribute('data-close-on-escape') !== 'false',
                trapFocus: modalElement.getAttribute('data-trap-focus') !== 'false',
                animationDuration: parseInt(modalElement.getAttribute('data-animation-duration')) || this.config.animationDuration,
                animationEasing: modalElement.getAttribute('data-animation-easing') || 'cubic-bezier(0.4, 0, 0.2, 1)'
            };
        }

        /**
         * Setup event listeners for modal
         */
        setupModalEvents(modalElement) {
            const closeButtons = modalElement.querySelectorAll('[data-modal-close]');
            const backdrop = modalElement.querySelector('.modal-backdrop');

            // Close button events
            closeButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.closeModal(modalElement.id);
                });
            });

            // Backdrop click events
            if (backdrop && modalElement.modalConfig.closeOnBackdrop) {
                backdrop.addEventListener('click', (e) => {
                    if (e.target === backdrop) {
                        this.closeModal(modalElement.id);
                    }
                });
            }

            // Prevent dialog content clicks from closing modal
            const modalDialog = modalElement.querySelector('.modal-dialog');
            if (modalDialog) {
                modalDialog.addEventListener('click', (e) => {
                    e.stopPropagation();
                });
            }
        }

        /**
         * Setup modal triggers
         */
        setupModalTriggers(modalElement) {
            const modalId = modalElement.id;
            const triggers = document.querySelectorAll(`[data-modal-target="${modalId}"]`);

            triggers.forEach(trigger => {
                trigger.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.openModal(modalId);
                });
            });
        }

        /**
         * Open modal by ID
         */
        openModal(modalId, options = {}) {
            const modalElement = document.getElementById(modalId);
            if (!modalElement) {
                console.error(`Modal with ID "${modalId}" not found`);
                return false;
            }

            if (this.isAnimating) {
                return false;
            }

            // If modal is already open, don't open again
            if (modalElement.classList.contains('modal-open')) {
                return false;
            }

            // Store currently focused element for restoration
            this.previouslyFocusedElement = document.activeElement;

            // Add to modal stack
            this.modalStack.push(modalElement);
            this.activeModal = modalElement;

            // Prevent body scroll
            this.preventBodyScroll();

            // Show modal
            this.showModal(modalElement, options);

            // Setup focus management
            this.setupFocusManagement(modalElement);

            // Announce to screen readers
            this.announceModalState(modalElement, 'opened');

            // Trigger custom event
            this.triggerEvent(modalElement, 'modal:opened');

            console.log(`Modal ${modalId} opened`);
            return true;
        }

        /**
         * Close modal by ID
         */
        closeModal(modalId, options = {}) {
            const modalElement = modalId ? document.getElementById(modalId) : this.activeModal;
            if (!modalElement) {
                return false;
            }

            if (this.isAnimating) {
                return false;
            }

            // Hide modal
            this.hideModal(modalElement, options);

            // Remove from modal stack
            this.modalStack = this.modalStack.filter(modal => modal !== modalElement);
            this.activeModal = this.modalStack[this.modalStack.length - 1] || null;

            // Restore body scroll if no modals open
            if (this.modalStack.length === 0) {
                this.restoreBodyScroll();
            }

            // Restore focus
            this.restoreFocus();

            // Announce to screen readers
            this.announceModalState(modalElement, 'closed');

            // Trigger custom event
            this.triggerEvent(modalElement, 'modal:closed');

            console.log(`Modal ${modalElement.id} closed`);
            return true;
        }

        /**
         * Show modal with animation
         */
        showModal(modalElement, options = {}) {
            this.isAnimating = true;

            // Set initial state
            modalElement.style.display = 'flex';
            modalElement.setAttribute('aria-hidden', 'false');

            // Force reflow
            modalElement.offsetHeight;

            // Add open class for CSS animation
            modalElement.classList.add('modal-open');

            // Animation complete
            setTimeout(() => {
                this.isAnimating = false;
                this.focusModal(modalElement);
            }, modalElement.modalConfig.animationDuration);
        }

        /**
         * Hide modal with animation
         */
        hideModal(modalElement, options = {}) {
            this.isAnimating = true;

            // Remove open class for CSS animation
            modalElement.classList.remove('modal-open');

            // Animation complete
            setTimeout(() => {
                modalElement.style.display = 'none';
                modalElement.setAttribute('aria-hidden', 'true');
                this.isAnimating = false;
            }, modalElement.modalConfig.animationDuration);
        }

        /**
         * Setup focus management for modal
         */
        setupFocusManagement(modalElement) {
            if (!modalElement.modalConfig.trapFocus) {
                return;
            }

            const focusableElements = this.getFocusableElements(modalElement);

            if (focusableElements.length === 0) {
                return;
            }

            const firstFocusable = focusableElements[0];
            const lastFocusable = focusableElements[focusableElements.length - 1];

            // Focus trap handler
            const handleFocusTrap = (e) => {
                if (e.key !== 'Tab') {
                    return;
                }

                if (e.shiftKey) {
                    // Shift + Tab
                    if (document.activeElement === firstFocusable) {
                        e.preventDefault();
                        lastFocusable.focus();
                    }
                } else {
                    // Tab
                    if (document.activeElement === lastFocusable) {
                        e.preventDefault();
                        firstFocusable.focus();
                    }
                }
            };

            modalElement.addEventListener('keydown', handleFocusTrap);

            // Store handler for cleanup
            modalElement._focusTrapHandler = handleFocusTrap;
        }

        /**
         * Focus modal element
         */
        focusModal(modalElement) {
            const focusableElements = this.getFocusableElements(modalElement);

            if (focusableElements.length > 0) {
                focusableElements[0].focus();
            } else {
                modalElement.focus();
            }
        }

        /**
         * Get focusable elements within modal
         */
        getFocusableElements(modalElement) {
            const focusableSelectors = [
                'button:not([disabled])',
                'input:not([disabled])',
                'select:not([disabled])',
                'textarea:not([disabled])',
                'a[href]',
                '[tabindex]:not([tabindex="-1"])',
                '[contenteditable]'
            ].join(', ');

            return Array.from(modalElement.querySelectorAll(focusableSelectors))
                .filter(el => this.isElementVisible(el));
        }

        /**
         * Check if element is visible
         */
        isElementVisible(element) {
            const style = window.getComputedStyle(element);
            return style.display !== 'none' &&
                   style.visibility !== 'hidden' &&
                   style.opacity !== '0';
        }

        /**
         * Restore focus to previously focused element
         */
        restoreFocus() {
            if (this.previouslyFocusedElement && this.config.accessibility.restoreFocus) {
                this.previouslyFocusedElement.focus();
                this.previouslyFocusedElement = null;
            }
        }

        /**
         * Prevent body scroll
         */
        preventBodyScroll() {
            this.scrollPosition = window.pageYOffset;
            document.body.style.overflow = 'hidden';
            document.body.style.position = 'fixed';
            document.body.style.top = `-${this.scrollPosition}px`;
            document.body.style.width = '100%';
        }

        /**
         * Restore body scroll
         */
        restoreBodyScroll() {
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('position');
            document.body.style.removeProperty('top');
            document.body.style.removeProperty('width');
            window.scrollTo(0, this.scrollPosition);
        }

        /**
         * Setup global keyboard navigation
         */
        setupKeyboardNavigation() {
            document.addEventListener('keydown', (e) => {
                if (!this.activeModal) {
                    return;
                }

                // Escape key
                if (e.key === 'Escape' && this.activeModal.modalConfig.closeOnEscape) {
                    e.preventDefault();
                    this.closeModal(this.activeModal.id);
                }
            });
        }

        /**
         * Setup accessibility features
         */
        setupAccessibilityFeatures() {
            // Create live region for announcements
            if (!document.getElementById('modal-live-region')) {
                const liveRegion = document.createElement('div');
                liveRegion.id = 'modal-live-region';
                liveRegion.setAttribute('aria-live', 'polite');
                liveRegion.setAttribute('aria-atomic', 'true');
                liveRegion.className = 'sr-only';
                document.body.appendChild(liveRegion);
            }
        }

        /**
         * Announce modal state to screen readers
         */
        announceModalState(modalElement, state) {
            if (!this.config.accessibility.announceStateChanges) {
                return;
            }

            const liveRegion = document.getElementById('modal-live-region');
            if (!liveRegion) {
                return;
            }

            const modalTitle = modalElement.querySelector('.modal-title');
            const title = modalTitle ? modalTitle.textContent : 'Modal';
            const message = `${title} dialog ${state}`;

            liveRegion.textContent = message;
        }

        /**
         * Bind global events
         */
        bindGlobalEvents() {
            // Handle dynamic modal creation
            document.addEventListener('DOMContentLoaded', () => {
                this.initializeModals();
            });

            // Handle window resize
            window.addEventListener('resize', this.debounce(() => {
                if (this.activeModal) {
                    this.adjustModalPosition(this.activeModal);
                }
            }, 250));

            // Handle page visibility change
            document.addEventListener('visibilitychange', () => {
                if (document.hidden && this.activeModal) {
                    // Page is hidden, pause any animations
                    this.pauseAnimations();
                } else if (!document.hidden && this.activeModal) {
                    // Page is visible, resume animations
                    this.resumeAnimations();
                }
            });
        }

        /**
         * Adjust modal position on resize
         */
        adjustModalPosition(modalElement) {
            // Implementation for responsive positioning
            const dialog = modalElement.querySelector('.modal-dialog');
            if (!dialog) return;

            const windowHeight = window.innerHeight;
            const dialogHeight = dialog.offsetHeight;

            if (dialogHeight > windowHeight * 0.9) {
                modalElement.classList.add('modal-scroll-inside');
            } else {
                modalElement.classList.remove('modal-scroll-inside');
            }
        }

        /**
         * Pause animations
         */
        pauseAnimations() {
            if (this.activeModal) {
                this.activeModal.style.animationPlayState = 'paused';
            }
        }

        /**
         * Resume animations
         */
        resumeAnimations() {
            if (this.activeModal) {
                this.activeModal.style.animationPlayState = 'running';
            }
        }

        /**
         * Trigger custom event
         */
        triggerEvent(element, eventName, detail = {}) {
            const event = new CustomEvent(eventName, {
                detail: detail,
                bubbles: true,
                cancelable: true
            });
            element.dispatchEvent(event);
        }

        /**
         * Debounce utility function
         */
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

        /**
         * Public API methods
         */

        /**
         * Create and open a new modal
         */
        createModal(config) {
            const modalId = config.id || 'modal-' + Math.random().toString(36).substr(2, 9);

            const modalHtml = this.generateModalHTML(modalId, config);
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            const modalElement = document.getElementById(modalId);
            this.setupModal(modalElement);

            if (config.autoOpen !== false) {
                this.openModal(modalId);
            }

            return modalId;
        }

        /**
         * Generate modal HTML
         */
        generateModalHTML(modalId, config) {
            const {
                title = '',
                content = '',
                footer = '',
                size = 'medium',
                type = 'basic',
                position = 'center',
                closeButton = true
            } = config;

            return `
                <div id="${modalId}"
                     class="modal-component modal-${type} modal-size-${size} modal-position-${position}"
                     role="dialog"
                     aria-modal="true"
                     aria-hidden="true"
                     tabindex="-1">
                    <div class="modal-backdrop" data-modal-backdrop></div>
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            ${title || closeButton ? `
                                <div class="modal-header">
                                    ${title ? `<h2 class="modal-title">${title}</h2>` : ''}
                                    ${closeButton ? '<button type="button" class="modal-close" aria-label="Close modal" data-modal-close>×</button>' : ''}
                                </div>
                            ` : ''}
                            <div class="modal-body">
                                ${content}
                            </div>
                            ${footer ? `
                                <div class="modal-footer">
                                    ${footer}
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        }

        /**
         * Destroy modal
         */
        destroyModal(modalId) {
            const modalElement = document.getElementById(modalId);
            if (!modalElement) {
                return false;
            }

            // Close if open
            if (modalElement.classList.contains('modal-open')) {
                this.closeModal(modalId);
            }

            // Remove event listeners
            if (modalElement._focusTrapHandler) {
                modalElement.removeEventListener('keydown', modalElement._focusTrapHandler);
            }

            // Remove from DOM
            modalElement.remove();

            return true;
        }

        /**
         * Get active modal
         */
        getActiveModal() {
            return this.activeModal;
        }

        /**
         * Check if any modal is open
         */
        isModalOpen() {
            return this.activeModal !== null;
        }

        /**
         * Close all modals
         */
        closeAllModals() {
            while (this.modalStack.length > 0) {
                const modal = this.modalStack[this.modalStack.length - 1];
                this.closeModal(modal.id);
            }
        }
    }

    /**
     * Specialized Modal Types
     */

    /**
     * Confirmation Modal Helper
     */
    class ConfirmationModal {
        static create(options = {}) {
            const {
                title = 'Confirm Action',
                message = 'Are you sure you want to continue?',
                confirmText = 'Confirm',
                cancelText = 'Cancel',
                confirmAction = null,
                cancelAction = null,
                type = 'warning'
            } = options;

            const modalId = `confirmation-${Date.now()}`;

            const config = {
                id: modalId,
                title: title,
                size: 'small',
                type: 'confirmation',
                content: `<p>${message}</p>`,
                footer: `
                    <button type="button" class="button button-secondary" data-modal-close>
                        ${cancelText}
                    </button>
                    <button type="button" class="button button-primary" id="${modalId}-confirm">
                        ${confirmText}
                    </button>
                `,
                closeButton: true
            };

            const createdModalId = window.modalManager.createModal(config);

            // Setup action handlers
            const confirmButton = document.getElementById(`${modalId}-confirm`);
            if (confirmButton) {
                confirmButton.addEventListener('click', () => {
                    if (confirmAction) {
                        confirmAction();
                    }
                    window.modalManager.closeModal(createdModalId);
                });
            }

            return createdModalId;
        }
    }

    /**
     * Gallery Modal Helper
     */
    class GalleryModal {
        static create(images = [], startIndex = 0) {
            const modalId = `gallery-${Date.now()}`;

            let currentIndex = startIndex;
            const totalImages = images.length;

            const generateImageHTML = (index) => {
                const image = images[index];
                return `
                    <div class="gallery-modal-content">
                        <img src="${image.src}" alt="${image.alt || ''}"
                             class="gallery-modal-image"
                             style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        ${image.caption ? `<div class="gallery-modal-caption">${image.caption}</div>` : ''}
                        <div class="gallery-modal-counter">${index + 1} / ${totalImages}</div>
                        ${totalImages > 1 ? `
                            <button type="button" class="gallery-modal-prev" aria-label="Previous image">‹</button>
                            <button type="button" class="gallery-modal-next" aria-label="Next image">›</button>
                        ` : ''}
                    </div>
                `;
            };

            const config = {
                id: modalId,
                size: 'fullscreen',
                type: 'gallery',
                content: generateImageHTML(currentIndex),
                closeButton: true
            };

            const createdModalId = window.modalManager.createModal(config);

            // Setup navigation if multiple images
            if (totalImages > 1) {
                const modal = document.getElementById(createdModalId);

                const updateImage = (newIndex) => {
                    currentIndex = newIndex;
                    const body = modal.querySelector('.modal-body');
                    body.innerHTML = generateImageHTML(currentIndex);
                    setupNavigation();
                };

                const setupNavigation = () => {
                    const prevBtn = modal.querySelector('.gallery-modal-prev');
                    const nextBtn = modal.querySelector('.gallery-modal-next');

                    if (prevBtn) {
                        prevBtn.addEventListener('click', () => {
                            updateImage(currentIndex > 0 ? currentIndex - 1 : totalImages - 1);
                        });
                    }

                    if (nextBtn) {
                        nextBtn.addEventListener('click', () => {
                            updateImage(currentIndex < totalImages - 1 ? currentIndex + 1 : 0);
                        });
                    }
                };

                setupNavigation();

                // Keyboard navigation
                modal.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowLeft') {
                        updateImage(currentIndex > 0 ? currentIndex - 1 : totalImages - 1);
                    } else if (e.key === 'ArrowRight') {
                        updateImage(currentIndex < totalImages - 1 ? currentIndex + 1 : 0);
                    }
                });
            }

            return createdModalId;
        }
    }

    // Initialize global modal manager
    window.modalManager = new ModalManager();

    // Expose helper classes
    window.ConfirmationModal = ConfirmationModal;
    window.GalleryModal = GalleryModal;

    // jQuery integration if available
    if (typeof $ !== 'undefined') {
        $.fn.modal = function(action, options) {
            return this.each(function() {
                const modalId = this.id;

                switch (action) {
                    case 'open':
                        window.modalManager.openModal(modalId, options);
                        break;
                    case 'close':
                        window.modalManager.closeModal(modalId, options);
                        break;
                    case 'destroy':
                        window.modalManager.destroyModal(modalId);
                        break;
                    default:
                        console.error('Invalid modal action:', action);
                }
            });
        };
    }

    console.log('Modal system loaded successfully');

})();
