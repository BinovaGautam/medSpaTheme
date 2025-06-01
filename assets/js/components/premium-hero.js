/**
 * Premium Hero System
 * Interactive treatment selection with dynamic backgrounds
 *
 * @package PreetiDreams
 * @version 1.0.0
 */

class PremiumHeroSystem {
  constructor() {
    this.currentStep = 1;
    this.selectedCategory = null;
    this.selectedTreatment = null;
    this.backgrounds = ['image', 'video', 'gradient'];
    this.currentBackground = 0;
    this.treatmentData = {};
    this.formSubmissionInProgress = false;
    this.init();
  }

  init() {
    this.setupElements();
    this.setupEventListeners();
    this.loadTreatmentData(); // Load dynamic data first
    this.startBackgroundRotation();
    this.trackAnalytics('hero_loaded');
    this.initializeAccessibility();
  }

  setupElements() {
    // Existing setupElements method content
  }

  setupEventListeners() {
    // Existing setupEventListeners method content
  }

  loadTreatmentData() {
    // Existing loadTreatmentData method content
  }

  startBackgroundRotation() {
    // Auto-rotate backgrounds every 10 seconds
    setInterval(() => {
      this.currentBackground = (this.currentBackground + 1) % this.backgrounds.length;
      this.switchBackground(this.backgrounds[this.currentBackground]);
    }, 10000);
  }

  switchBackground(type) {
    document.querySelectorAll('.hero-background').forEach(bg =>
      bg.classList.remove('active'));

    const newBackground = document.querySelector(`[data-background="${type}"]`);
    if (newBackground) {
      newBackground.classList.add('active');
    }
  }

  initializeAccessibility() {
    // Add ARIA labels and descriptions
    const treatmentInterface = document.querySelector('.treatment-selection-interface');
    if (treatmentInterface) {
      treatmentInterface.setAttribute('role', 'application');
      treatmentInterface.setAttribute('aria-label', 'Treatment selection wizard');
    }

    // Add live region for announcements
    if (!document.getElementById('sr-live-region')) {
      const liveRegion = document.createElement('div');
      liveRegion.id = 'sr-live-region';
      liveRegion.className = 'sr-only';
      liveRegion.setAttribute('aria-live', 'polite');
      liveRegion.setAttribute('aria-atomic', 'true');
      document.body.appendChild(liveRegion);
    }
  }

  announceToScreenReader(message) {
    const liveRegion = document.getElementById('sr-live-region');
    if (liveRegion) {
      liveRegion.textContent = message;
      // Clear after a delay to allow for repeated announcements
      setTimeout(() => {
        liveRegion.textContent = '';
      }, 1000);
    }
  }

  /**
   * Load Treatment Data Dynamically from WordPress
   */
  async loadTreatmentData() {
    try {
      const response = await fetch(`${window.location.origin}/wp-admin/admin-ajax.php`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=get_hero_treatments'
      });

      const result = await response.json();

      if (result.success) {
        this.treatmentData = result.data;
        console.log('✅ Treatment data loaded from WordPress:', this.treatmentData);
      } else {
        console.error('❌ Failed to load treatment data, using fallback');
        this.treatmentData = this.getFallbackData();
      }
    } catch (error) {
      console.error('❌ Error loading treatment data:', error);
      this.treatmentData = this.getFallbackData();
    }

    // Update UI after data is loaded
    this.updateCategoryButtons();
  }

  /**
   * Fallback treatment data if WordPress fails
   */
  getFallbackData() {
    return {
      facial: [
        { slug: 'hydrafacial', name: 'HydraFacial MD', price: 'Starting at $150', icon: '✨' },
        { slug: 'chemical-peel', name: 'Chemical Peel', price: 'Starting at $100', icon: '🌟' },
        { slug: 'microneedling', name: 'Microneedling', price: 'Starting at $200', icon: '💫' }
      ],
      injectable: [
        { slug: 'botox', name: 'Botox Cosmetic', price: 'Starting at $12/unit', icon: '💉' },
        { slug: 'dermal-fillers', name: 'Dermal Fillers', price: 'Starting at $600', icon: '💎' },
        { slug: 'lip-enhancement', name: 'Lip Enhancement', price: 'Starting at $500', icon: '💋' }
      ],
      laser: [
        { slug: 'hair-removal', name: 'Laser Hair Removal', price: 'Starting at $100', icon: '💎' },
        { slug: 'fractional-laser', name: 'Fractional Laser', price: 'Starting at $300', icon: '✨' },
        { slug: 'ipl-photofacial', name: 'IPL Photofacial', price: 'Starting at $250', icon: '🌟' }
      ],
      body: [
        { slug: 'coolsculpting', name: 'CoolSculpting', price: 'Starting at $750', icon: '🌟' },
        { slug: 'rf-body-contouring', name: 'RF Body Contouring', price: 'Starting at $400', icon: '⚡' },
        { slug: 'lymphatic-drainage', name: 'Lymphatic Drainage', price: 'Starting at $150', icon: '🌊' }
      ]
    };
  }

  getTreatmentInfo(category, treatmentSlug) {
    const treatments = this.treatmentData[category] || [];
    return treatments.find(t => t.slug === treatmentSlug) || {};
  }

  trackAnalytics(event, data = {}) {
    // Google Analytics 4
    if (typeof gtag !== 'undefined') {
      gtag('event', event, {
        event_category: 'hero_interaction',
        event_label: JSON.stringify(data),
        value: 1
      });
    }

    // Custom analytics tracking
    if (window.medSpaAnalytics) {
      window.medSpaAnalytics.track(event, data);
    }

    // Debug logging
    if (window.location.hostname === 'localhost' || window.location.hostname.includes('dev')) {
      console.log('Analytics Event:', event, data);
    }
  }

  handleKeyboard(e) {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      e.target.click();
    }
  }

  isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  isValidPhone(phone) {
    // Remove all non-digits
    const cleanPhone = phone.replace(/\D/g, '');
    // Check if it has at least 10 digits
    return cleanPhone.length >= 10;
  }

  showValidationErrors(errors) {
    // Remove existing errors
    const existingErrors = document.querySelector('.validation-errors');
    if (existingErrors) {
      existingErrors.remove();
    }

    // Create error container
    const errorDiv = document.createElement('div');
    errorDiv.className = 'validation-errors';
    errorDiv.setAttribute('role', 'alert');
    errorDiv.innerHTML = errors.map(error => `<p class="error-message">${error}</p>`).join('');

    const form = document.getElementById('hero-consultation-form');
    form.insertBefore(errorDiv, form.firstChild);

    // Announce errors to screen readers
    this.announceToScreenReader(`${errors.length} validation errors found`);

    // Auto-remove after 8 seconds
    setTimeout(() => {
      if (errorDiv.parentNode) {
        errorDiv.remove();
      }
    }, 8000);
  }

  showSuccessMessage() {
    const interface = document.querySelector('.treatment-selection-interface');
    interface.innerHTML = `
      <div class="success-message" role="status" aria-live="polite">
        <div class="success-icon" aria-hidden="true">✅</div>
        <h3>Thank You!</h3>
        <p>Your consultation request has been submitted successfully. We'll contact you within 24 hours to schedule your appointment.</p>
        <p>If you have any immediate questions, please call us directly at <a href="tel:(555) 123-4567">(555) 123-4567</a>.</p>
        <button class="btn btn-primary" onclick="location.reload()" aria-label="Start new treatment selection">
          Select Another Treatment
        </button>
      </div>
    `;

    // Focus the success message for screen readers
    const successMessage = interface.querySelector('.success-message');
    successMessage.focus();

    // Announce success to screen readers
    this.announceToScreenReader('Consultation request submitted successfully');
  }

  showErrorMessage(message) {
    // Remove existing errors
    const existingErrors = document.querySelector('.submission-error');
    if (existingErrors) {
      existingErrors.remove();
    }

    // Create error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'submission-error validation-errors';
    errorDiv.setAttribute('role', 'alert');
    errorDiv.innerHTML = `<p class="error-message">${message}</p>`;

    const form = document.getElementById('hero-consultation-form');
    form.insertBefore(errorDiv, form.firstChild);

    // Announce error to screen readers
    this.announceToScreenReader(`Submission error: ${message}`);

    // Auto-remove after 10 seconds
    setTimeout(() => {
      if (errorDiv.parentNode) {
        errorDiv.remove();
      }
    }, 10000);
  }

  initBackgroundSwitcher() {
    // Admin-only background switching controls
    if (!document.querySelector('.hero-bg-controls')) {
      const controlsDiv = document.createElement('div');
      controlsDiv.className = 'hero-bg-controls';
      controlsDiv.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        z-index: 9999;
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 10px;
        border-radius: 5px;
        font-size: 12px;
      `;

      controlsDiv.innerHTML = `
        <div>Hero Background:</div>
        <button onclick="premiumHero.switchBackground('image')" style="margin: 2px;">Image</button>
        <button onclick="premiumHero.switchBackground('video')" style="margin: 2px;">Video</button>
        <button onclick="premiumHero.switchBackground('gradient')" style="margin: 2px;">Gradient</button>
      `;

      document.body.appendChild(controlsDiv);
    }
  }

  initializeBackgrounds() {
    // Ensure at least one background is always active
    const activeBackground = document.querySelector('.hero-background.active');
    if (!activeBackground) {
      // Fallback to gradient if no background is active
      const gradientBg = document.querySelector('.hero-background-gradient');
      if (gradientBg) {
        gradientBg.classList.add('active');
      }
    }

    // Log background status for debugging
    const backgrounds = document.querySelectorAll('.hero-background');
    backgrounds.forEach((bg, index) => {
      const isActive = bg.classList.contains('active');
      const type = bg.dataset.background;
      console.log(`Background ${index}: ${type} - ${isActive ? 'Active' : 'Inactive'}`);
    });
  }

  updateCategoryButtons() {
    // Existing updateCategoryButtons method content
  }
}

// CSS for form validation states
const validationCSS = `
  .form-group input.invalid,
  .form-group textarea.invalid {
    border-color: var(--color-accent-coral);
    box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
  }

  .form-group input.valid,
  .form-group textarea.valid {
    border-color: var(--color-accent-success);
    box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
  }

  .field-error {
    color: var(--color-accent-coral);
    font-size: var(--text-sm);
    margin-top: 0.25rem;
    font-weight: 500;
  }

  .submission-error {
    margin-bottom: var(--space-lg);
  }
`;

// Inject validation CSS
if (!document.getElementById('hero-validation-styles')) {
  const styleSheet = document.createElement('style');
  styleSheet.id = 'hero-validation-styles';
  styleSheet.textContent = validationCSS;
  document.head.appendChild(styleSheet);
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  if (document.querySelector('.premium-hero')) {
    window.premiumHero = new PremiumHeroSystem();
  }
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
  module.exports = PremiumHeroSystem;
}
