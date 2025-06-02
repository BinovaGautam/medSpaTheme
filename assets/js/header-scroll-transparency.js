/**
 * Header Scroll Transparency Controller
 *
 * Implements dynamic opacity changes for the header based on scroll position
 * on pages with hero sections. Provides smooth transitions from completely
 * transparent to fully opaque as user scrolls down.
 */

(function() {
  'use strict';

  // Configuration
  const CONFIG = {
    headerSelector: '.professional-header',
    heroSections: ['.premium-hero', '.modern-hero', '.hero-section'],
    maxScrollDistance: 300, // Distance to reach full opacity
    throttleDelay: 16, // ~60fps throttling
    opacitySteps: [
      { threshold: 0, className: '', opacity: 0 },
      { threshold: 10, className: 'scroll-opacity-10', opacity: 0.1 },
      { threshold: 100, className: 'scroll-opacity-25', opacity: 0.25 },
      { threshold: 150, className: 'scroll-opacity-50', opacity: 0.5 },
      { threshold: 200, className: 'scroll-opacity-75', opacity: 0.75 },
      { threshold: 250, className: 'scroll-opacity-90', opacity: 0.9 },
      { threshold: 300, className: 'scroll-opacity-100', opacity: 0.95 }
    ]
  };

  // State management
  let isHeroPage = false;
  let header = null;
  let currentOpacityClass = '';
  let ticking = false;

  /**
   * Initialize the scroll transparency system
   */
  function init() {
    // Check if this is a hero page
    isHeroPage = checkIfHeroPage();

    if (!isHeroPage) {
      return; // Exit early if not a hero page
    }

    // Get header element
    header = document.querySelector(CONFIG.headerSelector);
    if (!header) {
      console.warn('Header element not found:', CONFIG.headerSelector);
      return;
    }

    // Bind scroll event with throttling
    window.addEventListener('scroll', throttledScrollHandler, { passive: true });

    // Set initial state
    updateHeaderOpacity(0);

    console.log('Header scroll transparency initialized');
  }

  /**
   * Check if current page has hero sections
   */
  function checkIfHeroPage() {
    const body = document.body;

    // Check for hero page body classes
    if (body.classList.contains('transparent-header') ||
        body.classList.contains('has-hero-section')) {
      return true;
    }

    // Check for hero section elements
    for (const heroSelector of CONFIG.heroSections) {
      if (document.querySelector(heroSelector)) {
        return true;
      }
    }

    return false;
  }

  /**
   * Throttled scroll event handler
   */
  function throttledScrollHandler() {
    if (!ticking) {
      requestAnimationFrame(handleScroll);
      ticking = true;
    }
  }

  /**
   * Handle scroll events and update header opacity
   */
  function handleScroll() {
    ticking = false;

    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    updateHeaderOpacity(scrollTop);
  }

  /**
   * Update header opacity based on scroll position
   */
  function updateHeaderOpacity(scrollTop) {
    if (!header || !isHeroPage) return;

    // Calculate progress (0 to 1)
    const progress = Math.min(scrollTop / CONFIG.maxScrollDistance, 1);

    // Find appropriate opacity step
    let targetStep = CONFIG.opacitySteps[0];

    for (const step of CONFIG.opacitySteps) {
      if (scrollTop >= step.threshold) {
        targetStep = step;
      } else {
        break;
      }
    }

    // Apply opacity class if it changed
    if (targetStep.className !== currentOpacityClass) {
      // Remove previous opacity class
      if (currentOpacityClass) {
        header.classList.remove(currentOpacityClass);
      }

      // Add new opacity class
      if (targetStep.className) {
        header.classList.add(targetStep.className);
      }

      currentOpacityClass = targetStep.className;

      // Debug logging (remove in production)
      console.log(`Header opacity updated: ${targetStep.opacity} (${targetStep.className})`);
    }
  }

  /**
   * Handle window resize events
   */
  function handleResize() {
    // Recalculate if needed
    if (isHeroPage) {
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      updateHeaderOpacity(scrollTop);
    }
  }

  /**
   * Cleanup function
   */
  function destroy() {
    window.removeEventListener('scroll', throttledScrollHandler);
    window.removeEventListener('resize', handleResize);

    if (header && currentOpacityClass) {
      header.classList.remove(currentOpacityClass);
    }
  }

  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  // Handle window resize
  window.addEventListener('resize', handleResize, { passive: true });

  // Expose cleanup function for debugging
  window.headerScrollTransparency = {
    destroy: destroy,
    init: init,
    isHeroPage: function() { return isHeroPage; },
    getCurrentOpacity: function() { return currentOpacityClass; }
  };

})();
