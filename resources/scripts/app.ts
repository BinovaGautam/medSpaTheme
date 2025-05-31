/**
 * PreetiDreams Medical Spa Theme - Main Application Entry Point
 *
 * HIPAA-conscious, WCAG AAA compliant, performance-optimized
 * medical spa WordPress theme application
 *
 * @version 1.0.0
 * @author PreetiDreams Development Team
 */

// ==================================================
// MEDICAL SPA IMPORTS
// ==================================================

// Core utilities
import { initializeAccessibility } from './utils/accessibility';
import { initializeHipaaCompliance } from './utils/hipaa-helpers';
import { validateEnvironment } from './utils/validation';

// Medical spa services
import { TreatmentService } from './services/TreatmentService';
import { ConsultationService } from './services/ConsultationService';
import { AnalyticsService } from './services/AnalyticsService';

// Medical spa components
import { TreatmentGrid } from './components/TreatmentGrid';
import { ConsultationForm } from './components/ConsultationForm';
import { BeforeAfterGallery } from './components/BeforeAfterGallery';

// Type definitions
import type { MedicalSpaConfig, HipaaSettings, AccessibilitySettings } from './types/Config';

// ==================================================
// GLOBAL CONFIGURATION
// ==================================================

const MEDICAL_SPA_CONFIG: MedicalSpaConfig = {
  version: __MEDICAL_SPA_VERSION__ || '1.0.0',
  hipaaMode: __HIPAA_MODE__ || false,
  accessibilityMode: __ACCESSIBILITY_MODE__ !== false,
  performanceMode: true,
  debugMode: process.env.NODE_ENV === 'development',
};

const HIPAA_SETTINGS: HipaaSettings = {
  enableAuditTrail: true,
  enableDataEncryption: true,
  enableConsentTracking: true,
  enablePrivacyControls: true,
  auditLogLevel: 'detailed',
};

const ACCESSIBILITY_SETTINGS: AccessibilitySettings = {
  enableScreenReader: true,
  enableKeyboardNavigation: true,
  enableHighContrast: true,
  enableReducedMotion: true,
  wcagLevel: 'AAA',
  colorContrastRatio: 7, // WCAG AAA standard
};

// ==================================================
// APPLICATION CLASS
// ==================================================

class MedicalSpaApp {
  private treatmentService: TreatmentService;
  private consultationService: ConsultationService;
  private analyticsService: AnalyticsService;
  private initialized = false;

  constructor(config: MedicalSpaConfig) {
    this.validateConfiguration(config);
    this.initializeServices(config);
    this.bindEventListeners();
  }

  /**
   * Initialize the medical spa application
   */
  public async initialize(): Promise<void> {
    try {
      console.log('🏥 Initializing PreetiDreams Medical Spa Theme...');

      // Environment validation
      await this.validateEnvironment();

      // Core initialization
      await this.initializeCore();

      // Medical spa specific initialization
      await this.initializeMedicalSpaFeatures();

      // Component initialization
      await this.initializeComponents();

      // Performance monitoring
      this.initializePerformanceMonitoring();

      this.initialized = true;
      console.log('✅ PreetiDreams Medical Spa Theme initialized successfully');

      // Track initialization
      this.analyticsService.track('medical_spa_app_initialized', {
        version: MEDICAL_SPA_CONFIG.version,
        hipaaMode: MEDICAL_SPA_CONFIG.hipaaMode,
        accessibilityMode: MEDICAL_SPA_CONFIG.accessibilityMode,
      });

    } catch (error) {
      console.error('❌ Failed to initialize Medical Spa Theme:', error);
      this.handleInitializationError(error as Error);
    }
  }

  /**
   * Validate application configuration
   */
  private validateConfiguration(config: MedicalSpaConfig): void {
    if (!config.version) {
      throw new Error('Medical Spa Theme version is required');
    }

    if (config.hipaaMode && !config.accessibilityMode) {
      console.warn('⚠️ HIPAA mode enabled without accessibility mode. Enabling accessibility for compliance.');
      config.accessibilityMode = true;
    }
  }

  /**
   * Initialize core services
   */
  private initializeServices(config: MedicalSpaConfig): void {
    this.treatmentService = new TreatmentService();
    this.consultationService = new ConsultationService({
      hipaaMode: config.hipaaMode,
      auditTrail: HIPAA_SETTINGS.enableAuditTrail,
    });
    this.analyticsService = new AnalyticsService({
      hipaaCompliant: config.hipaaMode,
      anonymizeData: true,
    });
  }

  /**
   * Validate environment and dependencies
   */
  private async validateEnvironment(): Promise<void> {
    const validation = await validateEnvironment();

    if (!validation.isValid) {
      throw new Error(`Environment validation failed: ${validation.errors.join(', ')}`);
    }

    if (validation.warnings.length > 0) {
      validation.warnings.forEach(warning => {
        console.warn('⚠️', warning);
      });
    }
  }

  /**
   * Initialize core application features
   */
  private async initializeCore(): Promise<void> {
    // Accessibility initialization
    if (MEDICAL_SPA_CONFIG.accessibilityMode) {
      await initializeAccessibility(ACCESSIBILITY_SETTINGS);
      console.log('♿ Accessibility features initialized (WCAG AAA)');
    }

    // HIPAA compliance initialization
    if (MEDICAL_SPA_CONFIG.hipaaMode) {
      await initializeHipaaCompliance(HIPAA_SETTINGS);
      console.log('🔒 HIPAA compliance features initialized');
    }

    // Service worker for offline functionality
    if ('serviceWorker' in navigator && MEDICAL_SPA_CONFIG.performanceMode) {
      try {
        await navigator.serviceWorker.register('/service-worker.js');
        console.log('📱 Service worker registered for offline functionality');
      } catch (error) {
        console.warn('Service worker registration failed:', error);
      }
    }
  }

  /**
   * Initialize medical spa specific features
   */
  private async initializeMedicalSpaFeatures(): Promise<void> {
    // Patient privacy controls
    this.initializePrivacyControls();

    // Medical spa navigation enhancements
    this.initializeNavigation();

    // Form validation with medical requirements
    this.initializeMedicalFormValidation();

    // Image lazy loading for treatment galleries
    this.initializeLazyLoading();

    console.log('🏥 Medical spa features initialized');
  }

  /**
   * Initialize interactive components
   */
  private async initializeComponents(): Promise<void> {
    const components = [
      { selector: '[data-treatment-grid]', component: TreatmentGrid },
      { selector: '[data-consultation-form]', component: ConsultationForm },
      { selector: '[data-before-after-gallery]', component: BeforeAfterGallery },
    ];

    for (const { selector, component: Component } of components) {
      const elements = document.querySelectorAll(selector);

      elements.forEach((element) => {
        try {
          new Component(element as HTMLElement, {
            hipaaMode: MEDICAL_SPA_CONFIG.hipaaMode,
            accessibilityMode: MEDICAL_SPA_CONFIG.accessibilityMode,
          });
        } catch (error) {
          console.error(`Failed to initialize component ${Component.name}:`, error);
        }
      });
    }

    console.log('🧩 Interactive components initialized');
  }

  /**
   * Initialize patient privacy controls
   */
  private initializePrivacyControls(): void {
    // HIPAA-conscious image blurring
    const privacyElements = document.querySelectorAll('[data-privacy-level]');

    privacyElements.forEach((element) => {
      const privacyLevel = element.getAttribute('data-privacy-level');
      const hipaaElement = element as HTMLElement;

      if (privacyLevel && ['restricted', 'confidential', 'hipaa'].includes(privacyLevel)) {
        hipaaElement.classList.add('patient-consent-required');

        // Add consent click handler
        hipaaElement.addEventListener('click', (e) => {
          if (!hipaaElement.classList.contains('consented')) {
            e.preventDefault();
            this.handlePrivacyConsent(hipaaElement, privacyLevel);
          }
        });
      }
    });
  }

  /**
   * Handle patient privacy consent
   */
  private async handlePrivacyConsent(element: HTMLElement, privacyLevel: string): Promise<void> {
    const consent = await this.consultationService.requestPrivacyConsent(privacyLevel);

    if (consent.granted) {
      element.classList.add('consented');

      // Track consent for audit trail
      this.analyticsService.track('privacy_consent_granted', {
        privacyLevel,
        timestamp: new Date().toISOString(),
        elementId: element.id || 'unknown',
      });
    }
  }

  /**
   * Initialize enhanced navigation
   */
  private initializeNavigation(): void {
    // Skip links for accessibility
    const skipLink = document.createElement('a');
    skipLink.href = '#main-content';
    skipLink.textContent = 'Skip to main content';
    skipLink.className = 'skip-link';
    document.body.insertBefore(skipLink, document.body.firstChild);

    // Mobile menu accessibility
    const mobileToggle = document.querySelector('[data-mobile-toggle]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');

    if (mobileToggle && mobileMenu) {
      mobileToggle.addEventListener('click', () => {
        const isExpanded = mobileToggle.getAttribute('aria-expanded') === 'true';
        mobileToggle.setAttribute('aria-expanded', String(!isExpanded));
        mobileMenu.classList.toggle('hidden');
      });
    }
  }

  /**
   * Initialize medical form validation
   */
  private initializeMedicalFormValidation(): void {
    const forms = document.querySelectorAll('form[data-medical-form]');

    forms.forEach((form) => {
      const formElement = form as HTMLFormElement;

      formElement.addEventListener('submit', async (e) => {
        e.preventDefault();

        try {
          const validation = await this.consultationService.validateForm(formElement);

          if (validation.isValid) {
            formElement.submit();
          } else {
            this.displayValidationErrors(formElement, validation.errors);
          }
        } catch (error) {
          console.error('Form validation failed:', error);
        }
      });
    });
  }

  /**
   * Display form validation errors
   */
  private displayValidationErrors(form: HTMLFormElement, errors: Array<{ field: string; message: string }>): void {
    // Clear existing errors
    form.querySelectorAll('.consultation-form__error-message').forEach(el => el.remove());
    form.querySelectorAll('.consultation-form__field-error').forEach(el => {
      el.classList.remove('consultation-form__field-error');
    });

    // Display new errors
    errors.forEach(({ field, message }) => {
      const fieldElement = form.querySelector(`[name="${field}"]`) as HTMLElement;

      if (fieldElement) {
        fieldElement.classList.add('consultation-form__field-error');

        const errorElement = document.createElement('div');
        errorElement.className = 'consultation-form__error-message';
        errorElement.textContent = message;
        errorElement.setAttribute('role', 'alert');

        fieldElement.parentNode?.insertBefore(errorElement, fieldElement.nextSibling);
      }
    });
  }

  /**
   * Initialize lazy loading for treatment images
   */
  private initializeLazyLoading(): void {
    if ('IntersectionObserver' in window) {
      const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const img = entry.target as HTMLImageElement;
            const src = img.getAttribute('data-src');

            if (src) {
              img.src = src;
              img.removeAttribute('data-src');
              img.classList.remove('lazy-load');
              imageObserver.unobserve(img);
            }
          }
        });
      });

      document.querySelectorAll('img[data-src]').forEach((img) => {
        imageObserver.observe(img);
      });
    }
  }

  /**
   * Initialize performance monitoring
   */
  private initializePerformanceMonitoring(): void {
    if (MEDICAL_SPA_CONFIG.performanceMode) {
      // Core Web Vitals monitoring
      const observer = new PerformanceObserver((list) => {
        list.getEntries().forEach((entry) => {
          if (entry.entryType === 'largest-contentful-paint') {
            this.analyticsService.track('core_web_vitals', {
              metric: 'LCP',
              value: entry.startTime,
              target: 1500, // Medical spa target: <1.5s
            });
          }
        });
      });

      observer.observe({ entryTypes: ['largest-contentful-paint'] });
    }
  }

  /**
   * Bind global event listeners
   */
  private bindEventListeners(): void {
    // Accessibility: Reduced motion support
    const mediaQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
    mediaQuery.addEventListener('change', () => {
      document.body.classList.toggle('reduce-motion', mediaQuery.matches);
    });

    // High contrast support
    const contrastQuery = window.matchMedia('(prefers-contrast: high)');
    contrastQuery.addEventListener('change', () => {
      document.body.classList.toggle('high-contrast', contrastQuery.matches);
    });

    // Window resize handler for responsive features
    let resizeTimeout: NodeJS.Timeout;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(() => {
        this.handleWindowResize();
      }, 150);
    });
  }

  /**
   * Handle window resize events
   */
  private handleWindowResize(): void {
    // Update viewport height for mobile browsers
    const vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);

    // Recalculate grid layouts
    document.querySelectorAll('[data-treatment-grid]').forEach((grid) => {
      (grid as HTMLElement).dispatchEvent(new CustomEvent('resize'));
    });
  }

  /**
   * Handle initialization errors
   */
  private handleInitializationError(error: Error): void {
    // Log error for debugging
    console.error('Medical Spa Theme initialization failed:', error);

    // Track error for analytics
    this.analyticsService?.track('initialization_error', {
      error: error.message,
      stack: error.stack,
      userAgent: navigator.userAgent,
      timestamp: new Date().toISOString(),
    });

    // Show user-friendly error message
    const errorBanner = document.createElement('div');
    errorBanner.className = 'medical-spa-error-banner';
    errorBanner.innerHTML = `
      <div class="luxury-container">
        <div class="hipaa-secure">
          <strong>⚠️ Service Temporarily Unavailable</strong>
          <p>We're experiencing technical difficulties. Please refresh the page or contact us directly for assistance.</p>
        </div>
      </div>
    `;
    errorBanner.setAttribute('role', 'alert');
    document.body.insertBefore(errorBanner, document.body.firstChild);
  }

  /**
   * Check if application is initialized
   */
  public isInitialized(): boolean {
    return this.initialized;
  }

  /**
   * Get application configuration
   */
  public getConfig(): MedicalSpaConfig {
    return { ...MEDICAL_SPA_CONFIG };
  }
}

// ==================================================
// APPLICATION INITIALIZATION
// ==================================================

// Initialize the medical spa application when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializeMedicalSpa);
} else {
  initializeMedicalSpa();
}

async function initializeMedicalSpa(): Promise<void> {
  try {
    const app = new MedicalSpaApp(MEDICAL_SPA_CONFIG);
    await app.initialize();

    // Make app globally available for debugging
    if (MEDICAL_SPA_CONFIG.debugMode) {
      (window as any).medicalSpaApp = app;
    }

  } catch (error) {
    console.error('Failed to initialize PreetiDreams Medical Spa Theme:', error);
  }
}

// Export for module systems
export { MedicalSpaApp, MEDICAL_SPA_CONFIG, HIPAA_SETTINGS, ACCESSIBILITY_SETTINGS };
