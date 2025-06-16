/**
 * Treatment Page Tab Functionality
 *
 * @package MedSpaTheme
 * @since 1.0.0
 *
 * Accessibility: WCAG AAA compliant
 * Progressive Enhancement: Works without JavaScript
 * Keyboard Navigation: Full support
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTreatmentTabs);
    } else {
        initTreatmentTabs();
    }

    function initTreatmentTabs() {
        const tabsContainer = document.querySelector('.treatment-tabs-nav');
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabPanels = document.querySelectorAll('.tab-panel');

        if (!tabsContainer || tabButtons.length === 0 || tabPanels.length === 0) {
            return; // Exit if required elements not found
        }

        // Initialize tabs
        setupTabFunctionality();
        setupKeyboardNavigation();
        setupCTATabTriggers();

        // Handle URL hash on page load
        handleInitialHash();
    }

    function setupTabFunctionality() {
        const tabButtons = document.querySelectorAll('.tab-button');

        tabButtons.forEach(button => {
            button.addEventListener('click', handleTabClick);
        });
    }

    function handleTabClick(event) {
        event.preventDefault();

        const clickedButton = event.currentTarget;
        const targetTab = clickedButton.getAttribute('data-tab');

        if (!targetTab) return;

        // Update active states
        setActiveTab(targetTab);

        // Update URL hash without scrolling
        updateURLHash(targetTab);

        // Announce change to screen readers
        announceTabChange(clickedButton);
    }

    function setActiveTab(targetTab) {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabPanels = document.querySelectorAll('.tab-panel');

        // Remove active states
        tabButtons.forEach(button => {
            button.classList.remove('active');
            button.setAttribute('aria-selected', 'false');
            button.setAttribute('tabindex', '-1');
        });

        tabPanels.forEach(panel => {
            panel.classList.remove('active');
        });

        // Set active states
        const activeButton = document.querySelector(`[data-tab="${targetTab}"]`);
        const activePanel = document.getElementById(`${targetTab}-panel`);

        if (activeButton && activePanel) {
            activeButton.classList.add('active');
            activeButton.setAttribute('aria-selected', 'true');
            activeButton.setAttribute('tabindex', '0');

            activePanel.classList.add('active');

            // Focus the active button for keyboard users
            if (document.activeElement !== activeButton) {
                activeButton.focus();
            }
        }
    }

    function setupKeyboardNavigation() {
        const tabsContainer = document.querySelector('.treatment-tabs-nav');

        tabsContainer.addEventListener('keydown', handleKeyboardNavigation);
    }

    function handleKeyboardNavigation(event) {
        const tabButtons = Array.from(document.querySelectorAll('.tab-button'));
        const currentIndex = tabButtons.findIndex(button => button === event.target);

        if (currentIndex === -1) return;

        let newIndex;

        switch (event.key) {
            case 'ArrowLeft':
            case 'ArrowUp':
                event.preventDefault();
                newIndex = currentIndex > 0 ? currentIndex - 1 : tabButtons.length - 1;
                break;

            case 'ArrowRight':
            case 'ArrowDown':
                event.preventDefault();
                newIndex = currentIndex < tabButtons.length - 1 ? currentIndex + 1 : 0;
                break;

            case 'Home':
                event.preventDefault();
                newIndex = 0;
                break;

            case 'End':
                event.preventDefault();
                newIndex = tabButtons.length - 1;
                break;

            case 'Enter':
            case ' ':
                event.preventDefault();
                event.target.click();
                return;

            default:
                return;
        }

        // Focus and activate the new tab
        const newButton = tabButtons[newIndex];
        if (newButton) {
            newButton.focus();
            const targetTab = newButton.getAttribute('data-tab');
            if (targetTab) {
                setActiveTab(targetTab);
                updateURLHash(targetTab);
            }
        }
    }

    function setupCTATabTriggers() {
        // Handle CTA buttons that should trigger specific tabs
        const ctaTriggers = document.querySelectorAll('[data-tab-trigger]');

        ctaTriggers.forEach(trigger => {
            trigger.addEventListener('click', function(event) {
                const targetTab = this.getAttribute('data-tab-trigger');
                if (targetTab) {
                    event.preventDefault();

                    // Scroll to tabs section
                    const tabsSection = document.querySelector('.treatment-tabs-section');
                    if (tabsSection) {
                        tabsSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });

                        // Set active tab after scroll
                        setTimeout(() => {
                            setActiveTab(targetTab);
                            updateURLHash(targetTab);
                        }, 500);
                    }
                }
            });
        });
    }

    function handleInitialHash() {
        const hash = window.location.hash.substring(1);

        if (hash) {
            // Check if hash corresponds to a tab
            const tabButton = document.querySelector(`[data-tab="${hash}"]`);
            if (tabButton) {
                setActiveTab(hash);
                return;
            }

            // Check if hash corresponds to a panel ID
            const panelMatch = hash.match(/^(.+)-panel$/);
            if (panelMatch) {
                const tabName = panelMatch[1];
                const tabButton = document.querySelector(`[data-tab="${tabName}"]`);
                if (tabButton) {
                    setActiveTab(tabName);
                    return;
                }
            }
        }

        // Default to first tab if no valid hash
        const firstTab = document.querySelector('.tab-button');
        if (firstTab) {
            const firstTabName = firstTab.getAttribute('data-tab');
            if (firstTabName) {
                setActiveTab(firstTabName);
            }
        }
    }

    function updateURLHash(tabName) {
        if (history.replaceState) {
            history.replaceState(null, null, `#${tabName}`);
        } else {
            // Fallback for older browsers
            window.location.hash = tabName;
        }
    }

    function announceTabChange(button) {
        // Create or update live region for screen reader announcements
        let liveRegion = document.getElementById('tab-announcements');

        if (!liveRegion) {
            liveRegion = document.createElement('div');
            liveRegion.id = 'tab-announcements';
            liveRegion.setAttribute('aria-live', 'polite');
            liveRegion.setAttribute('aria-atomic', 'true');
            liveRegion.className = 'sr-only';
            document.body.appendChild(liveRegion);
        }

        const tabText = button.textContent.trim();
        liveRegion.textContent = `${tabText} tab selected`;
    }

    // Handle browser back/forward buttons
    window.addEventListener('hashchange', function() {
        handleInitialHash();
    });

    // Smooth scrolling for anchor links within tabs
    document.addEventListener('click', function(event) {
        const link = event.target.closest('a[href^="#"]');
        if (!link) return;

        const href = link.getAttribute('href');
        const target = document.querySelector(href);

        if (target && href !== '#') {
            event.preventDefault();
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });

})();

/**
 * Related Treatments Enhancement
 * Handles animations, lazy loading, and interactions for related treatments section
 */
class RelatedTreatmentsManager {
    constructor() {
        this.container = document.querySelector('.related-treatments');
        this.cards = document.querySelectorAll('.related-treatments .treatment-card');
        this.viewAllButton = document.querySelector('.related-treatments__view-all');

        if (this.container) {
            this.init();
        }
    }

    init() {
        this.setupIntersectionObserver();
        this.setupLazyLoading();
        this.setupCardInteractions();
        this.setupAccessibility();
        this.setupAnalytics();
    }

    /**
     * Setup intersection observer for staggered animations
     */
    setupIntersectionObserver() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    // Add staggered delay for animation
                    setTimeout(() => {
                        entry.target.classList.add('animate-in');
                    }, index * 100);

                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        this.cards.forEach(card => {
            observer.observe(card);
        });
    }

    /**
     * Setup lazy loading for treatment card images
     */
    setupLazyLoading() {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    const src = img.dataset.src;

                    if (src) {
                        img.src = src;
                        img.removeAttribute('data-src');
                        img.classList.add('loaded');
                    }

                    imageObserver.unobserve(img);
                }
            });
        });

        const lazyImages = this.container.querySelectorAll('img[data-src]');
        lazyImages.forEach(img => {
            imageObserver.observe(img);
        });
    }

    /**
     * Setup card interactions and hover effects
     */
    setupCardInteractions() {
        this.cards.forEach(card => {
            // Enhanced hover effects
            card.addEventListener('mouseenter', (e) => {
                this.handleCardHover(e.currentTarget, true);
            });

            card.addEventListener('mouseleave', (e) => {
                this.handleCardHover(e.currentTarget, false);
            });

            // Click tracking for analytics
            const ctaButtons = card.querySelectorAll('.treatment-card__cta');
            ctaButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    this.trackCTAClick(e.currentTarget, card);
                });
            });

            // Keyboard navigation enhancement
            card.addEventListener('keydown', (e) => {
                this.handleCardKeydown(e, card);
            });
        });
    }

    /**
     * Handle card hover effects
     */
    handleCardHover(card, isHovering) {
        const image = card.querySelector('.treatment-card__image img');
        const title = card.querySelector('.treatment-card__title');

        if (isHovering) {
            // Add hover class for additional styling
            card.classList.add('is-hovered');

            // Announce to screen readers
            if (title) {
                title.setAttribute('aria-live', 'polite');
            }
        } else {
            card.classList.remove('is-hovered');

            if (title) {
                title.removeAttribute('aria-live');
            }
        }
    }

    /**
     * Handle keyboard navigation for cards
     */
    handleCardKeydown(e, card) {
        const focusableElements = card.querySelectorAll(
            'a, button, [tabindex]:not([tabindex="-1"])'
        );

        if (e.key === 'Enter' || e.key === ' ') {
            const firstCTA = card.querySelector('.treatment-card__cta--primary');
            if (firstCTA && e.target === card) {
                e.preventDefault();
                firstCTA.click();
            }
        }

        // Arrow key navigation within card
        if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {
            const currentIndex = Array.from(focusableElements).indexOf(e.target);
            let nextIndex;

            if (e.key === 'ArrowRight') {
                nextIndex = (currentIndex + 1) % focusableElements.length;
            } else {
                nextIndex = currentIndex === 0 ? focusableElements.length - 1 : currentIndex - 1;
            }

            focusableElements[nextIndex]?.focus();
            e.preventDefault();
        }
    }

    /**
     * Setup accessibility enhancements
     */
    setupAccessibility() {
        // Add ARIA labels and descriptions
        this.cards.forEach((card, index) => {
            const title = card.querySelector('.treatment-card__title');
            const description = card.querySelector('.treatment-card__description');

            if (title) {
                const titleId = `related-treatment-title-${index}`;
                title.id = titleId;
                card.setAttribute('aria-labelledby', titleId);
            }

            if (description) {
                const descId = `related-treatment-desc-${index}`;
                description.id = descId;
                card.setAttribute('aria-describedby', descId);
            }

            // Make cards focusable
            card.setAttribute('tabindex', '0');
            card.setAttribute('role', 'article');
        });

        // Announce section to screen readers
        if (this.container) {
            this.container.setAttribute('aria-label', 'Related treatments you might be interested in');
        }
    }

    /**
     * Setup analytics tracking
     */
    setupAnalytics() {
        // Track section visibility
        const sectionObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.trackEvent('related_treatments_viewed', {
                        section: 'related-treatments',
                        cards_count: this.cards.length
                    });
                    sectionObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        sectionObserver.observe(this.container);

        // Track view all button
        if (this.viewAllButton) {
            this.viewAllButton.addEventListener('click', () => {
                this.trackEvent('view_all_treatments_clicked', {
                    source: 'related-treatments-section'
                });
            });
        }
    }

    /**
     * Track CTA button clicks
     */
    trackCTAClick(button, card) {
        const treatmentTitle = card.querySelector('.treatment-card__title')?.textContent;
        const ctaType = button.classList.contains('treatment-card__cta--primary') ? 'primary' : 'secondary';
        const ctaText = button.textContent.trim();

        this.trackEvent('related_treatment_cta_clicked', {
            treatment_name: treatmentTitle,
            cta_type: ctaType,
            cta_text: ctaText,
            card_position: Array.from(this.cards).indexOf(card) + 1
        });
    }

    /**
     * Generic event tracking
     */
    trackEvent(eventName, properties = {}) {
        // Google Analytics 4
        if (typeof gtag !== 'undefined') {
            gtag('event', eventName, properties);
        }

        // Facebook Pixel
        if (typeof fbq !== 'undefined') {
            fbq('track', 'CustomEvent', {
                event_name: eventName,
                ...properties
            });
        }

        // Console log for debugging
        if (window.location.hostname === 'localhost' || window.location.hostname.includes('local')) {
            console.log('Analytics Event:', eventName, properties);
        }
    }

    /**
     * Handle loading states
     */
    setLoadingState(isLoading) {
        if (isLoading) {
            this.container.classList.add('related-treatments--loading');
        } else {
            this.container.classList.remove('related-treatments--loading');
        }
    }

    /**
     * Refresh related treatments (for dynamic loading)
     */
    async refreshTreatments(treatmentId) {
        this.setLoadingState(true);

        try {
            const response = await fetch(`${window.ajaxurl}?action=get_related_treatments&treatment_id=${treatmentId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.updateTreatmentsHTML(data.html);
            }
        } catch (error) {
            console.error('Error refreshing related treatments:', error);
        } finally {
            this.setLoadingState(false);
        }
    }

    /**
     * Update treatments HTML
     */
    updateTreatmentsHTML(html) {
        const grid = this.container.querySelector('.related-treatments__grid');
        if (grid) {
            grid.innerHTML = html;
            // Reinitialize for new cards
            this.cards = document.querySelectorAll('.related-treatments .treatment-card');
            this.setupCardInteractions();
            this.setupAccessibility();
        }
    }
}

/**
 * Related Treatments Fallback for older browsers
 */
class RelatedTreatmentsFallback {
    constructor() {
        this.container = document.querySelector('.related-treatments');
        this.cards = document.querySelectorAll('.related-treatments .treatment-card');

        if (this.container && !window.IntersectionObserver) {
            this.init();
        }
    }

    init() {
        // Simple fade-in for cards
        this.cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });

        // Basic click tracking
        this.cards.forEach(card => {
            const ctaButtons = card.querySelectorAll('.treatment-card__cta');
            ctaButtons.forEach(button => {
                button.addEventListener('click', () => {
                    console.log('Treatment CTA clicked:', button.textContent);
                });
            });
        });
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Initialize related treatments manager
    if (window.IntersectionObserver) {
        new RelatedTreatmentsManager();
    } else {
        new RelatedTreatmentsFallback();
    }
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { RelatedTreatmentsManager, RelatedTreatmentsFallback };
}
