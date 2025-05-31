/**
 * PreetiDreams Medical Spa Theme - Type Definitions
 *
 * TypeScript interfaces and types for medical spa theme configuration
 *
 * @version 1.0.0
 * @author PreetiDreams Development Team
 */

// ==================================================
// CORE CONFIGURATION TYPES
// ==================================================

export interface MedicalSpaConfig {
  version: string;
  hipaaMode: boolean;
  accessibilityMode: boolean;
  performanceMode: boolean;
  debugMode: boolean;
}

export interface HipaaSettings {
  enableAuditTrail: boolean;
  enableDataEncryption: boolean;
  enableConsentTracking: boolean;
  enablePrivacyControls: boolean;
  auditLogLevel: 'basic' | 'detailed' | 'comprehensive';
}

export interface AccessibilitySettings {
  enableScreenReader: boolean;
  enableKeyboardNavigation: boolean;
  enableHighContrast: boolean;
  enableReducedMotion: boolean;
  wcagLevel: 'A' | 'AA' | 'AAA';
  colorContrastRatio: number;
}

// ==================================================
// MEDICAL SPA SPECIFIC TYPES
// ==================================================

export interface TreatmentData {
  id: string;
  name: string;
  description: string;
  category: TreatmentCategory;
  price: {
    starting: number;
    currency: string;
  };
  duration: number; // in minutes
  image?: string;
  beforeAfterImages?: BeforeAfterImage[];
  hipaaLevel: HipaaLevel;
  featured: boolean;
}

export interface ConsultationData {
  id: string;
  patientName: string;
  email: string;
  phone: string;
  treatmentInterest: string[];
  preferredDate?: Date;
  message?: string;
  hipaaConsent: boolean;
  marketingConsent: boolean;
  status: ConsultationStatus;
  createdAt: Date;
  updatedAt?: Date;
}

export interface BeforeAfterImage {
  before: string;
  after: string;
  alt: string;
  treatmentId: string;
  patientConsent: boolean;
}

// ==================================================
// ENUMS AND UNION TYPES
// ==================================================

export type TreatmentCategory =
  | 'facial'
  | 'body'
  | 'medical'
  | 'wellness'
  | 'anti-aging'
  | 'skin-care';

export type HipaaLevel =
  | 'public'
  | 'restricted'
  | 'confidential'
  | 'hipaa';

export type ConsultationStatus =
  | 'pending'
  | 'confirmed'
  | 'completed'
  | 'cancelled'
  | 'rescheduled';

export type PrivacyLevel =
  | 'public'
  | 'restricted'
  | 'confidential'
  | 'hipaa';

// ==================================================
// COMPONENT CONFIGURATION TYPES
// ==================================================

export interface ComponentConfig {
  hipaaMode: boolean;
  accessibilityMode: boolean;
  debugMode?: boolean;
}

export interface TreatmentGridConfig extends ComponentConfig {
  columns: number;
  showPricing: boolean;
  enableFiltering: boolean;
  categories: TreatmentCategory[];
}

export interface ConsultationFormConfig extends ComponentConfig {
  requiredFields: string[];
  enableMarketing: boolean;
  autoSubmit: boolean;
  successRedirect?: string;
}

export interface GalleryConfig extends ComponentConfig {
  layout: 'grid' | 'masonry' | 'carousel';
  enableLightbox: boolean;
  lazyLoad: boolean;
  requireConsent: boolean;
}

// ==================================================
// API AND SERVICE TYPES
// ==================================================

export interface ApiResponse<T = any> {
  success: boolean;
  data?: T;
  error?: string;
  message?: string;
  meta?: {
    total?: number;
    page?: number;
    perPage?: number;
  };
}

export interface ValidationResult {
  isValid: boolean;
  errors: Array<{
    field: string;
    message: string;
    code?: string;
  }>;
  warnings?: string[];
}

export interface EnvironmentValidation {
  isValid: boolean;
  errors: string[];
  warnings: string[];
  capabilities: {
    serviceWorker: boolean;
    intersectionObserver: boolean;
    localStorage: boolean;
    webp: boolean;
  };
}

export interface ConsentData {
  granted: boolean;
  timestamp: Date;
  level: PrivacyLevel;
  ipAddress?: string;
  userAgent?: string;
}

// ==================================================
// ANALYTICS AND TRACKING TYPES
// ==================================================

export interface AnalyticsEvent {
  event: string;
  category: string;
  action: string;
  label?: string;
  value?: number;
  customDimensions?: Record<string, string | number>;
  hipaaCompliant: boolean;
}

export interface PerformanceMetric {
  name: string;
  value: number;
  timestamp: Date;
  url: string;
  connection?: string;
}

// ==================================================
// FORM AND INPUT TYPES
// ==================================================

export interface FormFieldConfig {
  name: string;
  type: 'text' | 'email' | 'tel' | 'textarea' | 'select' | 'checkbox' | 'radio';
  label: string;
  placeholder?: string;
  required: boolean;
  validation?: {
    pattern?: string;
    minLength?: number;
    maxLength?: number;
    custom?: (value: string) => boolean;
  };
  hipaaField: boolean;
  accessibility?: {
    describedBy?: string;
    ariaLabel?: string;
  };
}

export interface FormSubmissionData {
  formId: string;
  data: Record<string, any>;
  timestamp: Date;
  hipaaConsent: boolean;
  validation: ValidationResult;
  source: 'web' | 'mobile' | 'email';
}

// ==================================================
// WORDPRESS INTEGRATION TYPES
// ==================================================

export interface WordPressConfig {
  restUrl: string;
  nonce: string;
  userId?: number;
  siteName: string;
  themeUrl: string;
  uploadsUrl: string;
}

export interface BlockEditorConfig {
  patterns: BlockPattern[];
  colorPalette: ColorDefinition[];
  fontSizes: FontSizeDefinition[];
  gradients: GradientDefinition[];
}

export interface BlockPattern {
  name: string;
  title: string;
  description: string;
  content: string;
  categories: string[];
  keywords: string[];
  medical?: boolean;
  hipaaLevel?: HipaaLevel;
}

export interface ColorDefinition {
  name: string;
  slug: string;
  color: string;
  medical?: boolean;
}

export interface FontSizeDefinition {
  name: string;
  slug: string;
  size: string;
  accessible: boolean;
}

export interface GradientDefinition {
  name: string;
  slug: string;
  gradient: string;
  medical?: boolean;
}

// ==================================================
// ERROR AND EXCEPTION TYPES
// ==================================================

export interface ApplicationError {
  code: string;
  message: string;
  severity: 'low' | 'medium' | 'high' | 'critical';
  category: 'validation' | 'network' | 'hipaa' | 'accessibility' | 'system';
  timestamp: Date;
  stack?: string;
  context?: Record<string, any>;
}

export interface HipaaViolation extends ApplicationError {
  category: 'hipaa';
  violationType: 'data-exposure' | 'unauthorized-access' | 'consent-missing' | 'audit-failure';
  dataType: 'phi' | 'pii' | 'medical-record' | 'treatment-data';
  severity: 'high' | 'critical';
}

// ==================================================
// UTILITY TYPES
// ==================================================

export type DeepPartial<T> = {
  [P in keyof T]?: T[P] extends object ? DeepPartial<T[P]> : T[P];
};

export type RequiredField<T, K extends keyof T> = T & Required<Pick<T, K>>;

export type WithTimestamps<T> = T & {
  createdAt: Date;
  updatedAt?: Date;
};

export type HipaaCompliant<T> = T & {
  hipaaLevel: HipaaLevel;
  patientConsent: boolean;
  auditTrail: Array<{
    action: string;
    timestamp: Date;
    user?: string;
  }>;
};

// ==================================================
// GLOBAL DECLARATIONS
// ==================================================

declare global {
  interface Window {
    medicalSpaApp?: any;
    medicalSpaPatterns?: BlockPattern[];
    wp?: any;
  }

  // Environment variables
  const __MEDICAL_SPA_VERSION__: string;
  const __HIPAA_MODE__: boolean;
  const __ACCESSIBILITY_MODE__: boolean;
}
