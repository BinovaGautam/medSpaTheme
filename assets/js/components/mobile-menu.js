/**
 * Mobile Menu Component
 *
 * Responsive mobile menu with smooth animations, touch support, and accessibility
 * Designed for medical spa websites with professional UX
 *
 * @author PreetiDreams Development Team
 * @version 2.0.0
 */

'use strict';

class MobileMenu {
    constructor(selector = '.mobile-menu-toggle') {
        this.toggleSelector = selector;
        this.toggle = null;
        this.menu = null;
        this.overlay = null;
        this.menuItems = [];

        this.isOpen = false;
        this.isAnimating = false;
        this.touchStartY = 0;
        this.touchStartX = 0;

        // Configuration
        this.config = {
            animationDuration: 350,
            swipeThreshold: 50,
            breakpoint: 768,
            closeOnOutsideClick: true,
            closeOnEscape: true,
            trapFocus: true
        };

        // Bind methods
        this.init = this.init.bind(this);
        this.handleToggleClick = this.handleToggleClick.bind(this);
        this.handleKeydown = this.handleKeydown.bind(this);
        this.handleOutsideClick = this.handleOutsideClick.bind(this);
        this.handleTouchStart = this.handleTouchStart.bind(this);
        this.handleTouchMove = this.handleTouchMove.bind(this);
        this.handleTouchEnd = this.handleTouchEnd.bind(this);
        this.handleResize = this.handleResize.bind(this);
    }

    /**
     * Initialize the mobile menu
     */
    async init() {
        try {
            // Find elements
            this.toggle = document.querySelector(this.toggleSelector);
            if (!this.toggle) {
                console.warn('Mobile menu toggle not found');
                return;
            }

            this.menu = document.querySelector('#mobile-menu, .mobile-menu');
            if (!this.menu) {
                this.createMobileMenu();
            }

            // Setup menu structure
            this.setupMenuStructure();

            // Setup event listeners
            this.setupEventListeners();

            // Setup accessibility
            this.setupAccessibility();

            // Setup touch gestures
            this.setupTouchGestures();

            console.log('Mobile menu initialized');

        } catch (error) {
            console.error('Mobile menu initialization failed:', error);
        }
    }

    /**
     * Create mobile menu if it doesn't exist
     */
    createMobileMenu() {
        // Find main navigation
        const mainNav = document.querySelector('#main-navigation, .main-navigation, nav');
        if (!mainNav) return;

        // Create mobile menu container
        this.menu = document.createElement('div');
        this.menu.id = 'mobile-menu';
        this.menu.className = 'mobile-menu';
        this.menu.setAttribute('role', 'navigation');
        this.menu.setAttribute('aria-label', 'Mobile navigation');
        this.menu.setAttribute('aria-hidden', 'true');

        // Clone navigation items
        const navClone = mainNav.cloneNode(true);
        navClone.className = 'mobile-nav-list';

        // Create menu structure
        this.menu.innerHTML = `
            <div class="mobile-menu-header">
                <button class="mobile-menu-close" aria-label="Close menu">
                    <span class="sr-only">Close menu</span>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="mobile-menu-content">
                ${navClone.outerHTML}
                <div class="mobile-menu-actions">
                    <a href="tel:+1234567890" class="btn btn-phone mobile-menu-phone">
                        Call Now
                    </a>
                    <a href="#consultation" class="btn btn-primary mobile-menu-booking">
                        Book Consultation
                    </a>
                </div>
            </div>
        `;

        // Create overlay
        this.overlay = document.createElement('div');
        this.overlay.className = 'mobile-menu-overlay';
        this.overlay.setAttribute('aria-hidden', 'true');

        // Insert into DOM
        document.body.appendChild(this.overlay);
        document.body.appendChild(this.menu);
    }

    /**
     * Setup menu structure and styling
     */
    setupMenuStructure() {
        // Get menu items
        this.menuItems = Array.from(this.menu.querySelectorAll('a, button'));

        // Add close button if not exists
        let closeButton = this.menu.querySelector('.mobile-menu-close');
        if (!closeButton) {
            closeButton = document.createElement('button');
            closeButton.className = 'mobile-menu-close';
            closeButton.setAttribute('aria-label', 'Close menu');
            closeButton.innerHTML = '×';
            this.menu.insertBefore(closeButton, this.menu.firstChild);
        }

        // Ensure overlay exists
        if (!this.overlay) {
            this.overlay = document.querySelector('.mobile-menu-overlay');
            if (!this.overlay) {
                this.overlay = document.createElement('div');
                this.overlay.className = 'mobile-menu-overlay';
                document.body.appendChild(this.overlay);
            }
        }

        // Add CSS classes for styling
        document.documentElement.classList.add('mobile-menu-available');
    }

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        // Toggle button
        this.toggle.addEventListener('click', this.handleToggleClick);

        // Close button
        const closeButton = this.menu.querySelector('.mobile-menu-close');
        if (closeButton) {
            closeButton.addEventListener('click', this.close.bind(this));
        }

        // Overlay click
        if (this.config.closeOnOutsideClick) {
            this.overlay.addEventListener('click', this.close.bind(this));
        }

        // Keyboard events
        document.addEventListener('keydown', this.handleKeydown);

        // Menu item clicks
        this.menuItems.forEach(item => {
            item.addEventListener('click', this.handleMenuItemClick.bind(this));
        });

        // Resize handler
        window.addEventListener('resize', this.handleResize);

        // Custom events
        document.addEventListener('medSpaResize', this.handleCustomResize.bind(this));
    }

    /**
     * Setup accessibility features
     */
    setupAccessibility() {
        // Set initial ARIA states
        this.toggle.setAttribute('aria-expanded', 'false');
        this.toggle.setAttribute('aria-controls', 'mobile-menu');
        this.toggle.setAttribute('aria-label', 'Toggle mobile menu');

        // Add role and labels to menu
        this.menu.setAttribute('role', 'navigation');
        this.menu.setAttribute('aria-label', 'Mobile navigation menu');

        // Make menu items focusable
        this.menuItems.forEach((item, index) => {
            item.setAttribute('tabindex', '-1');
            item.setAttribute('data-menu-index', index);
        });
    }

    /**
     * Setup touch gestures
     */
    setupTouchGestures() {
        this.menu.addEventListener('touchstart', this.handleTouchStart, { passive: true });
        this.menu.addEventListener('touchmove', this.handleTouchMove, { passive: false });
        this.menu.addEventListener('touchend', this.handleTouchEnd, { passive: true });
    }

    /**
     * Handle toggle button click
     */
    handleToggleClick(event) {
        event.preventDefault();
        event.stopPropagation();

        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }

    /**
     * Handle keyboard events
     */
    handleKeydown(event) {
        if (!this.isOpen) return;

        switch (event.key) {
            case 'Escape':
                if (this.config.closeOnEscape) {
                    this.close();
                    this.toggle.focus();
                }
                break;

            case 'Tab':
                this.handleTabKey(event);
                break;

            case 'ArrowDown':
                event.preventDefault();
                this.focusNextItem();
                break;

            case 'ArrowUp':
                event.preventDefault();
                this.focusPreviousItem();
                break;

            case 'Home':
                event.preventDefault();
                this.focusFirstItem();
                break;

            case 'End':
                event.preventDefault();
                this.focusLastItem();
                break;
        }
    }

    /**
     * Handle tab key for focus trapping
     */
    handleTabKey(event) {
        if (!this.config.trapFocus) return;

        const focusableElements = this.getFocusableElements();
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        if (event.shiftKey) {
            // Shift + Tab
            if (document.activeElement === firstElement) {
                event.preventDefault();
                lastElement.focus();
            }
        } else {
            // Tab
            if (document.activeElement === lastElement) {
                event.preventDefault();
                firstElement.focus();
            }
        }
    }

    /**
     * Handle outside click
     */
    handleOutsideClick(event) {
        if (!this.isOpen) return;

        if (!this.menu.contains(event.target) && !this.toggle.contains(event.target)) {
            this.close();
        }
    }

    /**
     * Handle touch start
     */
    handleTouchStart(event) {
        this.touchStartY = event.touches[0].clientY;
        this.touchStartX = event.touches[0].clientX;
    }

    /**
     * Handle touch move
     */
    handleTouchMove(event) {
        if (!this.isOpen) return;

        const touchY = event.touches[0].clientY;
        const touchX = event.touches[0].clientX;
        const deltaY = touchY - this.touchStartY;
        const deltaX = touchX - this.touchStartX;

        // Prevent vertical scroll when menu is open
        if (Math.abs(deltaY) > Math.abs(deltaX)) {
            event.preventDefault();
        }

        // Close on swipe left
        if (deltaX < -this.config.swipeThreshold && Math.abs(deltaY) < 100) {
            this.close();
        }
    }

    /**
     * Handle touch end
     */
    handleTouchEnd(event) {
        this.touchStartY = 0;
        this.touchStartX = 0;
    }

    /**
     * Handle window resize
     */
    handleResize() {
        if (window.innerWidth >= this.config.breakpoint && this.isOpen) {
            this.close();
        }
    }

    /**
     * Handle custom resize event
     */
    handleCustomResize(event) {
        const { isMobile } = event.detail;
        if (!isMobile && this.isOpen) {
            this.close();
        }
    }

    /**
     * Handle menu item click
     */
    handleMenuItemClick(event) {
        const target = event.target;

        // Close menu after navigation (unless it's a dropdown)
        if (target.tagName === 'A' && !target.hasAttribute('data-dropdown')) {
            setTimeout(() => {
                this.close();
            }, 100);
        }

        // Track analytics
        this.trackMenuItemClick(target);
    }

    /**
     * Open the mobile menu
     */
    async open() {
        if (this.isOpen || this.isAnimating) return;

        this.isAnimating = true;

        // Prevent body scroll
        document.body.classList.add('mobile-menu-open');
        document.documentElement.classList.add('mobile-menu-open');

        // Show menu and overlay
        this.menu.classList.add('opening');
        this.overlay.classList.add('opening');

        // Update ARIA states
        this.toggle.setAttribute('aria-expanded', 'true');
        this.menu.setAttribute('aria-hidden', 'false');
        this.overlay.setAttribute('aria-hidden', 'false');

        // Animate menu
        await this.animateOpen();

        // Focus first menu item
        this.focusFirstItem();

        // Setup outside click handler
        if (this.config.closeOnOutsideClick) {
            setTimeout(() => {
                document.addEventListener('click', this.handleOutsideClick);
            }, 100);
        }

        this.isOpen = true;
        this.isAnimating = false;

        // Dispatch custom event
        this.dispatchEvent('mobileMenuOpened');

        console.log('Mobile menu opened');
    }

    /**
     * Close the mobile menu
     */
    async close() {
        if (!this.isOpen || this.isAnimating) return;

        this.isAnimating = true;

        // Remove outside click handler
        document.removeEventListener('click', this.handleOutsideClick);

        // Animate menu close
        await this.animateClose();

        // Hide menu and overlay
        this.menu.classList.remove('opening', 'open');
        this.overlay.classList.remove('opening', 'open');

        // Restore body scroll
        document.body.classList.remove('mobile-menu-open');
        document.documentElement.classList.remove('mobile-menu-open');

        // Update ARIA states
        this.toggle.setAttribute('aria-expanded', 'false');
        this.menu.setAttribute('aria-hidden', 'true');
        this.overlay.setAttribute('aria-hidden', 'true');

        this.isOpen = false;
        this.isAnimating = false;

        // Dispatch custom event
        this.dispatchEvent('mobileMenuClosed');

        console.log('Mobile menu closed');
    }

    /**
     * Animate menu open
     */
    animateOpen() {
        return new Promise(resolve => {
            // Force reflow
            this.menu.offsetHeight;

            // Add open class
            this.menu.classList.add('open');
            this.overlay.classList.add('open');

            // Wait for animation
            setTimeout(resolve, this.config.animationDuration);
        });
    }

    /**
     * Animate menu close
     */
    animateClose() {
        return new Promise(resolve => {
            // Remove open class
            this.menu.classList.remove('open');
            this.overlay.classList.remove('open');

            // Wait for animation
            setTimeout(resolve, this.config.animationDuration);
        });
    }

    /**
     * Focus management methods
     */

    getFocusableElements() {
        return Array.from(this.menu.querySelectorAll(
            'a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])'
        )).filter(el => !el.disabled && el.offsetParent !== null);
    }

    focusFirstItem() {
        const focusableElements = this.getFocusableElements();
        if (focusableElements.length > 0) {
            focusableElements[0].focus();
        }
    }

    focusLastItem() {
        const focusableElements = this.getFocusableElements();
        if (focusableElements.length > 0) {
            focusableElements[focusableElements.length - 1].focus();
        }
    }

    focusNextItem() {
        const focusableElements = this.getFocusableElements();
        const currentIndex = focusableElements.indexOf(document.activeElement);
        const nextIndex = (currentIndex + 1) % focusableElements.length;
        focusableElements[nextIndex].focus();
    }

    focusPreviousItem() {
        const focusableElements = this.getFocusableElements();
        const currentIndex = focusableElements.indexOf(document.activeElement);
        const previousIndex = currentIndex === 0 ? focusableElements.length - 1 : currentIndex - 1;
        focusableElements[previousIndex].focus();
    }

    /**
     * Analytics tracking
     */
    trackMenuItemClick(target) {
        const analytics = window.MedicalSpaApp?.getModule('analytics');
        if (analytics) {
            analytics.track('mobile_menu_click', {
                text: target.textContent.trim(),
                href: target.href || '',
                location: window.location.pathname
            });
        }
    }

    /**
     * Dispatch custom event
     */
    dispatchEvent(eventName, detail = {}) {
        const event = new CustomEvent(eventName, {
            detail: {
                ...detail,
                component: 'MobileMenu'
            }
        });
        document.dispatchEvent(event);
    }

    /**
     * Public API methods
     */

    toggle() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }

    isMenuOpen() {
        return this.isOpen;
    }

    destroy() {
        // Remove event listeners
        this.toggle.removeEventListener('click', this.handleToggleClick);
        document.removeEventListener('keydown', this.handleKeydown);
        document.removeEventListener('click', this.handleOutsideClick);
        window.removeEventListener('resize', this.handleResize);

        // Remove menu and overlay
        if (this.menu && this.menu.parentNode) {
            this.menu.parentNode.removeChild(this.menu);
        }
        if (this.overlay && this.overlay.parentNode) {
            this.overlay.parentNode.removeChild(this.overlay);
        }

        // Cleanup classes
        document.body.classList.remove('mobile-menu-open');
        document.documentElement.classList.remove('mobile-menu-open', 'mobile-menu-available');

        console.log('Mobile menu destroyed');
    }
}

// Export for module usage
export default MobileMenu;
