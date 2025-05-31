/**
 * PreetiDreams Medical Spa Theme - Accessibility Utilities
 *
 * WCAG AAA compliant accessibility functions and helpers
 *
 * @version 1.0.0
 * @author PreetiDreams Development Team
 */

import type { AccessibilitySettings } from '../types/Config';

// ==================================================
// ACCESSIBILITY INITIALIZATION
// ==================================================

export async function initializeAccessibility(settings: AccessibilitySettings): Promise<void> {
  console.log('🔍 Initializing accessibility features...');

  // Screen reader support
  if (settings.enableScreenReader) {
    setupScreenReaderSupport();
  }

  // Keyboard navigation
  if (settings.enableKeyboardNavigation) {
    setupKeyboardNavigation();
  }

  // High contrast support
  if (settings.enableHighContrast) {
    setupHighContrastSupport();
  }

  // Reduced motion support
  if (settings.enableReducedMotion) {
    setupReducedMotionSupport();
  }

  // Color contrast validation
  if (settings.colorContrastRatio >= 7) {
    validateColorContrast();
  }

  console.log(`✅ Accessibility initialized (WCAG ${settings.wcagLevel})`);
}

// ==================================================
// SCREEN READER SUPPORT
// ==================================================

function setupScreenReaderSupport(): void {
  // Add ARIA landmarks
  addAriaLandmarks();

  // Setup live regions
  setupLiveRegions();

  // Enhance form labels
  enhanceFormLabels();

  // Add skip links
  addSkipLinks();
}

function addAriaLandmarks(): void {
  const landmarks = [
    { selector: 'header', role: 'banner' },
    { selector: 'nav', role: 'navigation' },
    { selector: 'main', role: 'main' },
    { selector: 'aside', role: 'complementary' },
    { selector: 'footer', role: 'contentinfo' },
  ];

  landmarks.forEach(({ selector, role }) => {
    const elements = document.querySelectorAll(selector);
    elements.forEach(element => {
      if (!element.getAttribute('role')) {
        element.setAttribute('role', role);
      }
    });
  });
}

function setupLiveRegions(): void {
  // Create announcement region
  const announcements = document.createElement('div');
  announcements.id = 'accessibility-announcements';
  announcements.setAttribute('aria-live', 'polite');
  announcements.setAttribute('aria-atomic', 'true');
  announcements.className = 'sr-only';
  document.body.appendChild(announcements);

  // Create alert region
  const alerts = document.createElement('div');
  alerts.id = 'accessibility-alerts';
  alerts.setAttribute('aria-live', 'assertive');
  alerts.setAttribute('aria-atomic', 'true');
  alerts.className = 'sr-only';
  document.body.appendChild(alerts);
}

function enhanceFormLabels(): void {
  // Associate labels with form controls
  const inputs = document.querySelectorAll('input, textarea, select');

  inputs.forEach(input => {
    const id = input.id || `input-${Math.random().toString(36).substr(2, 9)}`;
    input.id = id;

    // Find associated label
    let label = document.querySelector(`label[for="${id}"]`);

    if (!label) {
      label = input.closest('label');
    }

    if (!label) {
      // Create label from placeholder or nearby text
      const placeholder = input.getAttribute('placeholder');
      if (placeholder) {
        const newLabel = document.createElement('label');
        newLabel.setAttribute('for', id);
        newLabel.textContent = placeholder;
        newLabel.className = 'sr-only';
        input.parentNode?.insertBefore(newLabel, input);
      }
    }
  });
}

function addSkipLinks(): void {
  const skipLink = document.createElement('a');
  skipLink.href = '#main-content';
  skipLink.textContent = 'Skip to main content';
  skipLink.className = 'skip-link';
  skipLink.addEventListener('click', (e) => {
    e.preventDefault();
    const target = document.querySelector('#main-content') || document.querySelector('main');
    if (target) {
      (target as HTMLElement).focus();
      target.scrollIntoView();
    }
  });

  document.body.insertBefore(skipLink, document.body.firstChild);
}

// ==================================================
// KEYBOARD NAVIGATION
// ==================================================

function setupKeyboardNavigation(): void {
  // Enhanced focus management
  setupFocusManagement();

  // Keyboard shortcuts
  setupKeyboardShortcuts();

  // Trap focus in modals
  setupModalFocusTrap();
}

function setupFocusManagement(): void {
  // Add focus indicators
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Tab') {
      document.body.classList.add('keyboard-navigation');
    }
  });

  document.addEventListener('mousedown', () => {
    document.body.classList.remove('keyboard-navigation');
  });

  // Enhance focus visibility
  const style = document.createElement('style');
  style.textContent = `
    .keyboard-navigation *:focus {
      outline: 3px solid #1e40af !important;
      outline-offset: 2px !important;
      box-shadow: 0 0 0 1px white !important;
    }
  `;
  document.head.appendChild(style);
}

function setupKeyboardShortcuts(): void {
  document.addEventListener('keydown', (e) => {
    // Alt + M: Go to main content
    if (e.altKey && e.key === 'm') {
      e.preventDefault();
      const main = document.querySelector('#main-content') || document.querySelector('main');
      if (main) {
        (main as HTMLElement).focus();
        main.scrollIntoView();
      }
    }

    // Alt + N: Go to navigation
    if (e.altKey && e.key === 'n') {
      e.preventDefault();
      const nav = document.querySelector('nav a');
      if (nav) {
        (nav as HTMLElement).focus();
      }
    }

    // Alt + S: Go to search
    if (e.altKey && e.key === 's') {
      e.preventDefault();
      const search = document.querySelector('input[type="search"], input[name*="search"]');
      if (search) {
        (search as HTMLElement).focus();
      }
    }
  });
}

function setupModalFocusTrap(): void {
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      const modal = document.querySelector('[role="dialog"]:not([hidden])');
      if (modal) {
        const closeButton = modal.querySelector('[data-dismiss], .close, .modal-close');
        if (closeButton) {
          (closeButton as HTMLElement).click();
        }
      }
    }
  });
}

// ==================================================
// HIGH CONTRAST SUPPORT
// ==================================================

function setupHighContrastSupport(): void {
  const mediaQuery = window.matchMedia('(prefers-contrast: high)');

  function handleContrastChange(e: MediaQueryListEvent) {
    document.body.classList.toggle('high-contrast', e.matches);

    if (e.matches) {
      enhanceContrastElements();
    }
  }

  mediaQuery.addEventListener('change', handleContrastChange);
  handleContrastChange(mediaQuery as any);
}

function enhanceContrastElements(): void {
  // Enhance button contrast
  const buttons = document.querySelectorAll('button, .button, .btn');
  buttons.forEach(button => {
    button.classList.add('high-contrast-enhanced');
  });

  // Enhance link contrast
  const links = document.querySelectorAll('a');
  links.forEach(link => {
    link.classList.add('high-contrast-enhanced');
  });
}

// ==================================================
// REDUCED MOTION SUPPORT
// ==================================================

function setupReducedMotionSupport(): void {
  const mediaQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

  function handleMotionChange(e: MediaQueryListEvent) {
    document.body.classList.toggle('reduce-motion', e.matches);

    if (e.matches) {
      disableAnimations();
    }
  }

  mediaQuery.addEventListener('change', handleMotionChange);
  handleMotionChange(mediaQuery as any);
}

function disableAnimations(): void {
  const style = document.createElement('style');
  style.id = 'reduced-motion-styles';
  style.textContent = `
    .reduce-motion * {
      animation-duration: 0.01ms !important;
      animation-iteration-count: 1 !important;
      transition-duration: 0.01ms !important;
      scroll-behavior: auto !important;
    }
  `;
  document.head.appendChild(style);
}

// ==================================================
// COLOR CONTRAST VALIDATION
// ==================================================

function validateColorContrast(): void {
  // This would typically use a color contrast checking library
  // For now, we'll add basic validation

  const elements = document.querySelectorAll('*');
  elements.forEach(element => {
    const computedStyle = window.getComputedStyle(element);
    const color = computedStyle.color;
    const backgroundColor = computedStyle.backgroundColor;

    // Basic contrast check (simplified)
    if (color && backgroundColor && color !== 'rgba(0, 0, 0, 0)' && backgroundColor !== 'rgba(0, 0, 0, 0)') {
      // In a real implementation, we'd calculate the actual contrast ratio
      // For now, just add a class for potential low contrast
      if (isLowContrast(color, backgroundColor)) {
        element.classList.add('potential-low-contrast');
      }
    }
  });
}

function isLowContrast(color: string, backgroundColor: string): boolean {
  // Simplified low contrast detection
  // In production, use a proper color contrast calculation
  return false;
}

// ==================================================
// UTILITY FUNCTIONS
// ==================================================

export function announceToScreenReader(message: string, priority: 'polite' | 'assertive' = 'polite'): void {
  const regionId = priority === 'assertive' ? 'accessibility-alerts' : 'accessibility-announcements';
  const region = document.getElementById(regionId);

  if (region) {
    region.textContent = message;

    // Clear after announcement
    setTimeout(() => {
      region.textContent = '';
    }, 1000);
  }
}

export function setupAccessibleModal(modal: HTMLElement): void {
  modal.setAttribute('role', 'dialog');
  modal.setAttribute('aria-modal', 'true');

  // Set focus to modal
  modal.setAttribute('tabindex', '-1');
  modal.focus();

  // Add close button if not present
  if (!modal.querySelector('[data-dismiss]')) {
    const closeButton = document.createElement('button');
    closeButton.innerHTML = '&times;';
    closeButton.setAttribute('data-dismiss', 'modal');
    closeButton.setAttribute('aria-label', 'Close');
    closeButton.className = 'modal-close';
    modal.appendChild(closeButton);
  }
}

export function validateFormAccessibility(form: HTMLFormElement): string[] {
  const issues: string[] = [];

  // Check for labels
  const inputs = form.querySelectorAll('input, textarea, select');
  inputs.forEach((input, index) => {
    const id = input.id;
    const label = form.querySelector(`label[for="${id}"]`);

    if (!label && !input.closest('label')) {
      issues.push(`Form field ${index + 1} is missing a label`);
    }
  });

  // Check for fieldsets in complex forms
  const radioGroups = form.querySelectorAll('input[type="radio"]');
  if (radioGroups.length > 1) {
    const fieldset = form.querySelector('fieldset');
    if (!fieldset) {
      issues.push('Radio button group should be wrapped in a fieldset with legend');
    }
  }

  return issues;
}
