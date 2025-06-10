/**
 * Accordion Component JavaScript
 *
 * Comprehensive accordion/toggle functionality with accessibility compliance,
 * smooth animations, keyboard navigation, and medical spa specializations.
 *
 * @package MedSpaTheme
 * @since 1.0.0
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 */

(function() {
    'use strict';

    /**
     * Accordion Manager Class
     * Handles accordion initialization, interaction, and state management
     */
    class AccordionManager {
        constructor() {
            this.accordions = new Map();
            this.initialized = false;
            this.animationFrameId = null;
            this.resizeTimeout = null;

            // Configuration defaults
            this.defaults = {
                animationDuration: 300,
                animationEasing: 'cubic-bezier(0.4, 0, 0.2, 1)',
                allowMultiple: false,
                closeOthers: true,
                persistState: false,
                searchEnabled: false,
                autoScrollToOpened: false,
                scrollOffset: 100
            };

            // Accessibility configuration
            this.a11y = {
                announceChanges: true,
                focusOnOpen: false,
                trapFocus: false,
                announceDelay: 100
            };

            this.init();
        }

        /**
         * Initialize accordion manager
         */
        init() {
            if (this.initialized) return;

            this.bindEvents();
            this.discoverAccordions();
            this.setupAccessibility();
            this.initialized = true;

            // Announce initialization for screen readers
            this.announceToScreenReader('Accordion components initialized');
        }

        /**
         * Bind global events
         */
        bindEvents() {
            // DOM content loaded
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    this.discoverAccordions();
                });
            }

            // Window resize handling
            window.addEventListener('resize', this.debounce(() => {
                this.handleResize();
            }, 250));

            // Keyboard navigation
            document.addEventListener('keydown', (event) => {
                this.handleGlobalKeydown(event);
            });

            // Focus management
            document.addEventListener('focusin', (event) => {
                this.handleFocusIn(event);
            });

            // Click delegation
            document.addEventListener('click', (event) => {
                this.handleGlobalClick(event);
            });

            // Search input handling
            document.addEventListener('input', (event) => {
                if (event.target.classList.contains('accordion-search-input')) {
                    this.handleSearch(event);
                }
            });
        }

        /**
         * Discover and initialize accordion components
         */
        discoverAccordions() {
            const accordionContainers = document.querySelectorAll('.accordion-container');

            accordionContainers.forEach(container => {
                if (!container.hasAttribute('data-accordion-initialized')) {
                    this.initializeAccordion(container);
                }
            });
        }

        /**
         * Initialize individual accordion
         * @param {HTMLElement} container - Accordion container element
         */
        initializeAccordion(container) {
            const accordionId = container.id || `accordion-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
            container.id = accordionId;

            // Get configuration
            const config = this.getAccordionConfig(container);

            // Create accordion instance
            const accordion = new AccordionInstance(container, config, this);
            this.accordions.set(accordionId, accordion);

            // Mark as initialized
            container.setAttribute('data-accordion-initialized', 'true');

            // Initialize accordion items
            this.initializeAccordionItems(container, accordion);

            // Setup search if enabled
            if (config.searchEnabled) {
                this.setupSearch(container, accordion);
            }

            // Restore state if persistence enabled
            if (config.persistState) {
                this.restoreAccordionState(accordion);
            }

            // Trigger initialized event
            this.triggerEvent(container, 'accordion:initialized', { accordion });
        }

        /**
         * Get accordion configuration from DOM attributes
         * @param {HTMLElement} container - Accordion container
         * @returns {Object} Configuration object
         */
        getAccordionConfig(container) {
            const config = { ...this.defaults };

            // Read data attributes
            if (container.dataset.animationDuration) {
                config.animationDuration = parseInt(container.dataset.animationDuration);
            }
            if (container.dataset.allowMultiple !== undefined) {
                config.allowMultiple = container.dataset.allowMultiple === 'true';
            }
            if (container.dataset.closeOthers !== undefined) {
                config.closeOthers = container.dataset.closeOthers === 'true';
            }
            if (container.dataset.persistState !== undefined) {
                config.persistState = container.dataset.persistState === 'true';
            }
            if (container.dataset.autoScrollToOpened !== undefined) {
                config.autoScrollToOpened = container.dataset.autoScrollToOpened === 'true';
            }

            // Check for search functionality
            config.searchEnabled = container.querySelector('.accordion-search') !== null;

            // Read animation type
            config.animationType = container.dataset.animationType || 'slide';

            return config;
        }

        /**
         * Initialize accordion items
         * @param {HTMLElement} container - Accordion container
         * @param {AccordionInstance} accordion - Accordion instance
         */
        initializeAccordionItems(container, accordion) {
            const items = container.querySelectorAll('.accordion-item');

            items.forEach((item, index) => {
                const header = item.querySelector('.accordion-header');
                const content = item.querySelector('.accordion-content');

                if (!header || !content) return;

                // Set up IDs if not present
                if (!header.id) {
                    header.id = `${container.id}-header-${index}`;
                }
                if (!content.id) {
                    content.id = `${container.id}-content-${index}`;
                }

                // Set ARIA relationships
                header.setAttribute('aria-controls', content.id);
                content.setAttribute('aria-labelledby', header.id);

                // Store item index
                item.setAttribute('data-accordion-item', index);
                header.setAttribute('data-accordion-header', 'true');
                content.setAttribute('data-accordion-content', 'true');

                // Add to accordion instance
                accordion.addItem({
                    index,
                    element: item,
                    header,
                    content,
                    isOpen: item.classList.contains('accordion-item-open'),
                    isDisabled: item.classList.contains('accordion-item-disabled')
                });
            });
        }

        /**
         * Setup search functionality
         * @param {HTMLElement} container - Accordion container
         * @param {AccordionInstance} accordion - Accordion instance
         */
        setupSearch(container, accordion) {
            const searchInput = container.querySelector('.accordion-search-input');
            if (!searchInput) return;

            searchInput.addEventListener('input', this.debounce((event) => {
                this.performSearch(accordion, event.target.value);
            }, 300));

            searchInput.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    event.target.value = '';
                    this.performSearch(accordion, '');
                    event.target.blur();
                }
            });
        }

        /**
         * Perform search within accordion
         * @param {AccordionInstance} accordion - Accordion instance
         * @param {string} query - Search query
         */
        performSearch(accordion, query) {
            const searchTerm = query.toLowerCase().trim();

            accordion.items.forEach(item => {
                const title = item.header.textContent.toLowerCase();
                const content = item.content.textContent.toLowerCase();
                const matches = searchTerm === '' || title.includes(searchTerm) || content.includes(searchTerm);

                item.element.style.display = matches ? '' : 'none';

                if (matches && searchTerm !== '') {
                    this.highlightSearchTerm(item.element, searchTerm);
                } else {
                    this.clearHighlights(item.element);
                }
            });

            // Announce search results
            if (searchTerm) {
                const visibleCount = accordion.items.filter(item =>
                    item.element.style.display !== 'none'
                ).length;

                this.announceToScreenReader(
                    `Search results: ${visibleCount} accordion items found for "${query}"`
                );
            }

            // Trigger search event
            this.triggerEvent(accordion.container, 'accordion:search', {
                query: searchTerm,
                accordion
            });
        }

        /**
         * Highlight search terms in content
         * @param {HTMLElement} element - Element to highlight in
         * @param {string} term - Search term
         */
        highlightSearchTerm(element, term) {
            // Implementation for highlighting search terms
            // This would involve wrapping matching text in highlight spans
            // For brevity, we'll just add a CSS class to indicate matches
            element.classList.add('accordion-item-search-match');
        }

        /**
         * Clear search highlights
         * @param {HTMLElement} element - Element to clear highlights from
         */
        clearHighlights(element) {
            element.classList.remove('accordion-item-search-match');
            // Additional cleanup for text highlighting would go here
        }

        /**
         * Handle global click events
         * @param {Event} event - Click event
         */
        handleGlobalClick(event) {
            const header = event.target.closest('.accordion-header');
            if (!header) return;

            const container = header.closest('.accordion-container');
            if (!container) return;

            const accordion = this.accordions.get(container.id);
            if (!accordion) return;

            event.preventDefault();

            const item = header.closest('.accordion-item');
            const itemIndex = parseInt(item.dataset.accordionItem);

            if (item.classList.contains('accordion-item-disabled')) {
                return;
            }

            this.toggleAccordionItem(accordion, itemIndex);
        }

        /**
         * Handle global keydown events
         * @param {Event} event - Keydown event
         */
        handleGlobalKeydown(event) {
            const header = event.target.closest('.accordion-header');
            if (!header) return;

            const container = header.closest('.accordion-container');
            if (!container) return;

            const accordion = this.accordions.get(container.id);
            if (!accordion) return;

            this.handleAccordionKeydown(event, accordion, header);
        }

        /**
         * Handle accordion-specific keyboard navigation
         * @param {Event} event - Keydown event
         * @param {AccordionInstance} accordion - Accordion instance
         * @param {HTMLElement} header - Current header element
         */
        handleAccordionKeydown(event, accordion, header) {
            const item = header.closest('.accordion-item');
            const itemIndex = parseInt(item.dataset.accordionItem);

            switch (event.key) {
                case 'Enter':
                case ' ':
                    event.preventDefault();
                    if (!item.classList.contains('accordion-item-disabled')) {
                        this.toggleAccordionItem(accordion, itemIndex);
                    }
                    break;

                case 'ArrowDown':
                    event.preventDefault();
                    this.focusNextHeader(accordion, itemIndex);
                    break;

                case 'ArrowUp':
                    event.preventDefault();
                    this.focusPrevHeader(accordion, itemIndex);
                    break;

                case 'Home':
                    event.preventDefault();
                    this.focusFirstHeader(accordion);
                    break;

                case 'End':
                    event.preventDefault();
                    this.focusLastHeader(accordion);
                    break;

                case 'Escape':
                    event.preventDefault();
                    if (accordion.config.allowMultiple) {
                        this.closeAllItems(accordion);
                    } else {
                        this.closeAccordionItem(accordion, itemIndex);
                    }
                    break;
            }
        }

        /**
         * Toggle accordion item
         * @param {AccordionInstance} accordion - Accordion instance
         * @param {number} itemIndex - Item index to toggle
         */
        toggleAccordionItem(accordion, itemIndex) {
            const item = accordion.items[itemIndex];
            if (!item || item.isDisabled) return;

            if (item.isOpen) {
                this.closeAccordionItem(accordion, itemIndex);
            } else {
                this.openAccordionItem(accordion, itemIndex);
            }
        }

        /**
         * Open accordion item
         * @param {AccordionInstance} accordion - Accordion instance
         * @param {number} itemIndex - Item index to open
         */
        openAccordionItem(accordion, itemIndex) {
            const item = accordion.items[itemIndex];
            if (!item || item.isDisabled || item.isOpen) return;

            // Close others if configured
            if (!accordion.config.allowMultiple || accordion.config.closeOthers) {
                accordion.items.forEach((otherItem, otherIndex) => {
                    if (otherIndex !== itemIndex && otherItem.isOpen) {
                        this.closeAccordionItem(accordion, otherIndex, true);
                    }
                });
            }

            // Open the item
            item.isOpen = true;
            item.element.classList.add('accordion-item-open');
            item.header.setAttribute('aria-expanded', 'true');
            item.content.setAttribute('aria-hidden', 'false');

            // Animate opening
            this.animateAccordionContent(item.content, 'open', accordion.config);

            // Auto-scroll if configured
            if (accordion.config.autoScrollToOpened && !this.isElementInViewport(item.header)) {
                this.scrollToElement(item.header, accordion.config.scrollOffset);
            }

            // Announce change
            this.announceToScreenReader(`${item.header.textContent} expanded`);

            // Persist state
            if (accordion.config.persistState) {
                this.saveAccordionState(accordion);
            }

            // Trigger events
            this.triggerEvent(accordion.container, 'accordion:item:opened', {
                accordion,
                item,
                index: itemIndex
            });
        }

        /**
         * Close accordion item
         * @param {AccordionInstance} accordion - Accordion instance
         * @param {number} itemIndex - Item index to close
         * @param {boolean} silent - Don't announce or trigger events
         */
        closeAccordionItem(accordion, itemIndex, silent = false) {
            const item = accordion.items[itemIndex];
            if (!item || item.isDisabled || !item.isOpen) return;

            // Close the item
            item.isOpen = false;
            item.element.classList.remove('accordion-item-open');
            item.header.setAttribute('aria-expanded', 'false');
            item.content.setAttribute('aria-hidden', 'true');

            // Animate closing
            this.animateAccordionContent(item.content, 'close', accordion.config);

            if (!silent) {
                // Announce change
                this.announceToScreenReader(`${item.header.textContent} collapsed`);

                // Persist state
                if (accordion.config.persistState) {
                    this.saveAccordionState(accordion);
                }

                // Trigger events
                this.triggerEvent(accordion.container, 'accordion:item:closed', {
                    accordion,
                    item,
                    index: itemIndex
                });
            }
        }

        /**
         * Animate accordion content
         * @param {HTMLElement} content - Content element
         * @param {string} direction - 'open' or 'close'
         * @param {Object} config - Animation configuration
         */
        animateAccordionContent(content, direction, config) {
            // Cancel any existing animation
            if (this.animationFrameId) {
                cancelAnimationFrame(this.animationFrameId);
            }

            const inner = content.querySelector('.accordion-content-inner');
            if (!inner) return;

            if (direction === 'open') {
                // Measure content height
                content.style.display = 'block';
                content.style.height = 'auto';
                const targetHeight = inner.scrollHeight;

                // Reset for animation
                content.style.height = '0px';
                content.style.opacity = '0';

                // Animate to target height
                this.animationFrameId = requestAnimationFrame(() => {
                    content.style.transition = `height ${config.animationDuration}ms ${config.animationEasing}, opacity ${config.animationDuration}ms ${config.animationEasing}`;
                    content.style.height = `${targetHeight}px`;
                    content.style.opacity = '1';

                    // Clean up after animation
                    setTimeout(() => {
                        content.style.height = 'auto';
                        content.style.transition = '';
                    }, config.animationDuration);
                });
            } else {
                // Get current height
                const currentHeight = content.scrollHeight;
                content.style.height = `${currentHeight}px`;

                // Animate to closed
                this.animationFrameId = requestAnimationFrame(() => {
                    content.style.transition = `height ${config.animationDuration}ms ${config.animationEasing}, opacity ${config.animationDuration}ms ${config.animationEasing}`;
                    content.style.height = '0px';
                    content.style.opacity = '0';

                    // Hide after animation
                    setTimeout(() => {
                        content.style.display = 'none';
                        content.style.transition = '';
                    }, config.animationDuration);
                });
            }
        }

        /**
         * Focus management methods
         */
        focusNextHeader(accordion, currentIndex) {
            const nextIndex = this.findNextEnabledItem(accordion, currentIndex, 1);
            if (nextIndex !== -1) {
                accordion.items[nextIndex].header.focus();
            }
        }

        focusPrevHeader(accordion, currentIndex) {
            const prevIndex = this.findNextEnabledItem(accordion, currentIndex, -1);
            if (prevIndex !== -1) {
                accordion.items[prevIndex].header.focus();
            }
        }

        focusFirstHeader(accordion) {
            const firstIndex = this.findNextEnabledItem(accordion, -1, 1);
            if (firstIndex !== -1) {
                accordion.items[firstIndex].header.focus();
            }
        }

        focusLastHeader(accordion) {
            const lastIndex = this.findNextEnabledItem(accordion, accordion.items.length, -1);
            if (lastIndex !== -1) {
                accordion.items[lastIndex].header.focus();
            }
        }

        /**
         * Find next enabled accordion item
         * @param {AccordionInstance} accordion - Accordion instance
         * @param {number} startIndex - Starting index
         * @param {number} direction - Direction (1 or -1)
         * @returns {number} Next enabled item index or -1
         */
        findNextEnabledItem(accordion, startIndex, direction) {
            const items = accordion.items;
            let index = startIndex + direction;

            while (index >= 0 && index < items.length) {
                if (!items[index].isDisabled && items[index].element.style.display !== 'none') {
                    return index;
                }
                index += direction;
            }

            // Wrap around
            if (direction === 1) {
                index = 0;
                while (index < startIndex) {
                    if (!items[index].isDisabled && items[index].element.style.display !== 'none') {
                        return index;
                    }
                    index++;
                }
            } else {
                index = items.length - 1;
                while (index > startIndex) {
                    if (!items[index].isDisabled && items[index].element.style.display !== 'none') {
                        return index;
                    }
                    index--;
                }
            }

            return -1;
        }

        /**
         * Close all accordion items
         * @param {AccordionInstance} accordion - Accordion instance
         */
        closeAllItems(accordion) {
            accordion.items.forEach((item, index) => {
                if (item.isOpen) {
                    this.closeAccordionItem(accordion, index, true);
                }
            });

            // Announce change
            this.announceToScreenReader('All accordion items collapsed');
        }

        /**
         * Accessibility and utility methods
         */
        announceToScreenReader(message) {
            if (!this.a11y.announceChanges) return;

            setTimeout(() => {
                const announcement = document.createElement('div');
                announcement.setAttribute('aria-live', 'polite');
                announcement.setAttribute('aria-atomic', 'true');
                announcement.className = 'sr-only';
                announcement.textContent = message;

                document.body.appendChild(announcement);

                setTimeout(() => {
                    document.body.removeChild(announcement);
                }, 1000);
            }, this.a11y.announceDelay);
        }

        /**
         * Setup global accessibility features
         */
        setupAccessibility() {
            // Add skip links for accordions if needed
            // Set up live regions for announcements
            // Configure focus management
        }

        /**
         * Handle focus management
         * @param {Event} event - Focus event
         */
        handleFocusIn(event) {
            // Focus management logic
            const header = event.target.closest('.accordion-header');
            if (header) {
                header.classList.add('accordion-header-focused');
            }
        }

        /**
         * Handle window resize
         */
        handleResize() {
            this.accordions.forEach(accordion => {
                // Recalculate content heights if needed
                accordion.items.forEach(item => {
                    if (item.isOpen) {
                        const content = item.content;
                        content.style.height = 'auto';
                    }
                });
            });
        }

        /**
         * State persistence methods
         */
        saveAccordionState(accordion) {
            const state = accordion.items.map(item => ({
                index: item.index,
                isOpen: item.isOpen
            }));

            localStorage.setItem(`accordion-${accordion.container.id}`, JSON.stringify(state));
        }

        restoreAccordionState(accordion) {
            const savedState = localStorage.getItem(`accordion-${accordion.container.id}`);
            if (!savedState) return;

            try {
                const state = JSON.parse(savedState);
                state.forEach(itemState => {
                    const item = accordion.items[itemState.index];
                    if (item && itemState.isOpen) {
                        this.openAccordionItem(accordion, itemState.index);
                    }
                });
            } catch (error) {
                console.warn('Failed to restore accordion state:', error);
            }
        }

        /**
         * Utility methods
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

        isElementInViewport(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }

        scrollToElement(element, offset = 0) {
            const elementTop = element.offsetTop - offset;
            window.scrollTo({
                top: elementTop,
                behavior: 'smooth'
            });
        }

        triggerEvent(element, eventName, detail = {}) {
            const event = new CustomEvent(eventName, {
                detail,
                bubbles: true,
                cancelable: true
            });
            element.dispatchEvent(event);
        }

        /**
         * Public API methods
         */
        openItem(accordionId, itemIndex) {
            const accordion = this.accordions.get(accordionId);
            if (accordion) {
                this.openAccordionItem(accordion, itemIndex);
            }
        }

        closeItem(accordionId, itemIndex) {
            const accordion = this.accordions.get(accordionId);
            if (accordion) {
                this.closeAccordionItem(accordion, itemIndex);
            }
        }

        toggleItem(accordionId, itemIndex) {
            const accordion = this.accordions.get(accordionId);
            if (accordion) {
                this.toggleAccordionItem(accordion, itemIndex);
            }
        }

        getAccordion(accordionId) {
            return this.accordions.get(accordionId);
        }

        closeAll(accordionId) {
            const accordion = this.accordions.get(accordionId);
            if (accordion) {
                this.closeAllItems(accordion);
            }
        }
    }

    /**
     * Accordion Instance Class
     * Represents individual accordion component
     */
    class AccordionInstance {
        constructor(container, config, manager) {
            this.container = container;
            this.config = config;
            this.manager = manager;
            this.items = [];
            this.id = container.id;
        }

        addItem(itemData) {
            this.items.push(itemData);
        }

        getItem(index) {
            return this.items[index];
        }

        getOpenItems() {
            return this.items.filter(item => item.isOpen);
        }

        getEnabledItems() {
            return this.items.filter(item => !item.isDisabled);
        }
    }

    /**
     * Medical Spa Accordion Extensions
     * Specialized functionality for medical spa content
     */
    class MedicalSpaAccordionExtensions {
        constructor(manager) {
            this.manager = manager;
            this.setupMedicalFeatures();
        }

        setupMedicalFeatures() {
            // FAQ Schema generation
            this.setupFAQSchema();

            // Treatment information display
            this.setupTreatmentDisplay();

            // Pricing calculator integration
            this.setupPricingCalculator();
        }

        setupFAQSchema() {
            // Generate FAQ schema markup for SEO
            document.querySelectorAll('.accordion-faq').forEach(faqAccordion => {
                this.generateFAQSchema(faqAccordion);
            });
        }

        generateFAQSchema(faqAccordion) {
            const items = faqAccordion.querySelectorAll('.accordion-item');
            const faqData = Array.from(items).map(item => {
                const question = item.querySelector('.accordion-title')?.textContent?.trim();
                const answer = item.querySelector('.accordion-content-inner')?.textContent?.trim();

                if (question && answer) {
                    return {
                        '@type': 'Question',
                        'name': question,
                        'acceptedAnswer': {
                            '@type': 'Answer',
                            'text': answer
                        }
                    };
                }
            }).filter(Boolean);

            if (faqData.length > 0) {
                const schema = {
                    '@context': 'https://schema.org',
                    '@type': 'FAQPage',
                    'mainEntity': faqData
                };

                // Create and append schema script
                const schemaScript = document.createElement('script');
                schemaScript.type = 'application/ld+json';
                schemaScript.textContent = JSON.stringify(schema);
                document.head.appendChild(schemaScript);
            }
        }

        setupTreatmentDisplay() {
            // Enhanced treatment information display
        }

        setupPricingCalculator() {
            // Pricing calculator integration
        }
    }

    /**
     * Initialize accordion system
     */
    function initializeAccordionSystem() {
        // Create global accordion manager
        window.MedSpaAccordion = new AccordionManager();

        // Add medical spa extensions
        window.MedSpaAccordion.medicalExtensions = new MedicalSpaAccordionExtensions(window.MedSpaAccordion);

        // Expose public API
        window.MedSpaAccordion.api = {
            open: (accordionId, itemIndex) => window.MedSpaAccordion.openItem(accordionId, itemIndex),
            close: (accordionId, itemIndex) => window.MedSpaAccordion.closeItem(accordionId, itemIndex),
            toggle: (accordionId, itemIndex) => window.MedSpaAccordion.toggleItem(accordionId, itemIndex),
            closeAll: (accordionId) => window.MedSpaAccordion.closeAll(accordionId),
            get: (accordionId) => window.MedSpaAccordion.getAccordion(accordionId)
        };
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeAccordionSystem);
    } else {
        initializeAccordionSystem();
    }

})();
