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
