# TASK-FUNC-005-01: Implement Premium Hero System

**Task ID**: TASK-FUNC-005-01  
**Related Requirement**: REQ-FUNC-005-REFINED  
**Category**: Implementation - Core User Experience  
**Priority**: High  
**Iteration Target**: iteration-4  
**Status**: Ready for Implementation  
**Created**: 2025-01-28  
**Assigned To**: AI-Supervised Implementation  

## 📋 **Task Summary**

**Title**: Interactive Premium Hero System Implementation with Treatment Selection Interface  
**Description**: Implement the complete premium hero system featuring dynamic backgrounds, interactive treatment selection interface, seamless header integration, and conversion-optimized CTA strategy according to REQ-FUNC-005-REFINED specifications and UI-UX-ARCHITECTURE.md design guidelines.

## 🎯 **Implementation Objectives**

**Primary Goals**:
- ✅ Implement interactive treatment selection interface (LaserAway-inspired)
- ✅ Create dynamic background system (video, image, gradient)
- ✅ Achieve seamless header integration with smart scroll behavior
- ✅ Optimize conversion funnel with strategic CTA placement
- ✅ Ensure WCAG AAA accessibility compliance across all interactions

**Success Criteria**:
- Hero section loads in <1.5 seconds
- Treatment selection interface achieves 80% completion rate
- Mobile performance score 95+ on PageSpeed Insights
- 100% keyboard navigation accessibility
- Seamless visual integration with existing header system

## 📊 **Technical Implementation Plan**

### **Phase 1: Foundation & Structure (12 hours)**

#### **HTML Structure Implementation**
```html
<!-- front-page.php Hero Section Update -->
<section class="hero-section premium-hero" id="hero">
  <!-- Dynamic Background System -->
  <div class="hero-background-system">
    <div class="hero-background hero-background-image active" data-background="image">
      <?php 
      $hero_image = get_theme_mod('hero_background_image', get_template_directory_uri() . '/assets/images/hero-medical-spa.jpg');
      ?>
      <img src="<?php echo esc_url($hero_image); ?>" alt="<?php esc_attr_e('Luxury Medical Spa Environment', 'preetidreams'); ?>" loading="eager">
    </div>
    
    <div class="hero-background hero-background-video" data-background="video">
      <?php $hero_video = get_theme_mod('hero_background_video'); ?>
      <?php if ($hero_video) : ?>
        <video autoplay muted loop playsinline>
          <source src="<?php echo esc_url($hero_video); ?>" type="video/mp4">
        </video>
      <?php endif; ?>
    </div>
    
    <div class="hero-background hero-background-gradient" data-background="gradient">
      <!-- CSS gradient backgrounds -->
    </div>
    
    <!-- Hero Content -->
    <div class="container">
      <div class="hero-layout">
        <!-- Left: Content Section -->
        <div class="hero-content-section">
          <div class="hero-text-content">
            <h1 class="hero-title" data-aos="fade-up">
              <?php echo esc_html(get_theme_mod('hero_title', __('Transform Your Beauty with Advanced Medical Spa Treatments', 'preetidreams'))); ?>
            </h1>
            
            <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
              <?php echo esc_html(get_theme_mod('hero_subtitle', __('Experience the latest in non-surgical aesthetic treatments performed by board-certified professionals in a luxurious, comfortable environment.', 'preetidreams'))); ?>
            </p>
            
            <!-- Trust Indicators -->
            <div class="trust-indicators" data-aos="fade-up" data-aos-delay="400">
              <div class="trust-item">
                <span class="trust-icon">✅</span>
                <span class="trust-text"><?php esc_html_e('Board Certified', 'preetidreams'); ?></span>
              </div>
              <div class="trust-item">
                <span class="trust-icon">🏆</span>
                <span class="trust-text"><?php esc_html_e('Award Winning', 'preetidreams'); ?></span>
              </div>
              <div class="trust-item">
                <span class="trust-icon">💯</span>
                <span class="trust-text"><?php esc_html_e('2000+ Happy Patients', 'preetidreams'); ?></span>
              </div>
              <div class="trust-item">
                <span class="trust-icon">🔒</span>
                <span class="trust-text"><?php esc_html_e('HIPAA Compliant', 'preetidreams'); ?></span>
              </div>
            </div>
            
            <!-- Primary CTAs -->
            <div class="hero-actions" data-aos="fade-up" data-aos-delay="600">
              <a href="#consultation" class="btn btn-primary btn-large cta-primary">
                <span class="btn-icon">📞</span>
                <?php esc_html_e('Free Consultation', 'preetidreams'); ?>
              </a>
              <?php 
              $phone = preetidreams_get_phone();
              if ($phone) : ?>
                <a href="tel:<?php echo esc_attr($phone); ?>" class="btn btn-secondary btn-large cta-secondary">
                  <span class="btn-icon">📱</span>
                  <?php echo esc_html($phone); ?>
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
        
        <!-- Right: Interactive Treatment Selection -->
        <div class="hero-interactive-section">
          <div class="treatment-selection-interface" data-aos="fade-left">
            <div class="selection-progress">
              <div class="progress-indicator">
                <span class="step-number active" data-step="1">1</span>
                <span class="step-number" data-step="2">2</span>
                <span class="step-number" data-step="3">3</span>
              </div>
            </div>
            
            <!-- Step 1: Treatment Categories -->
            <div class="selection-step active" data-step="1">
              <h3 class="step-title"><?php esc_html_e('Which treatment are you interested in?', 'preetidreams'); ?></h3>
              <div class="treatment-categories">
                <button class="category-btn" data-category="facial" tabindex="0">
                  <span class="category-icon">✨</span>
                  <span class="category-name"><?php esc_html_e('Facial Treatments', 'preetidreams'); ?></span>
                </button>
                <button class="category-btn" data-category="injectable" tabindex="0">
                  <span class="category-icon">💉</span>
                  <span class="category-name"><?php esc_html_e('Injectables', 'preetidreams'); ?></span>
                </button>
                <button class="category-btn" data-category="laser" tabindex="0">
                  <span class="category-icon">💎</span>
                  <span class="category-name"><?php esc_html_e('Laser Treatments', 'preetidreams'); ?></span>
                </button>
                <button class="category-btn" data-category="body" tabindex="0">
                  <span class="category-icon">🌟</span>
                  <span class="category-name"><?php esc_html_e('Body Contouring', 'preetidreams'); ?></span>
                </button>
              </div>
            </div>
            
            <!-- Step 2: Specific Treatments -->
            <div class="selection-step" data-step="2">
              <h3 class="step-title"><?php esc_html_e('Select your specific treatment:', 'preetidreams'); ?></h3>
              <div class="specific-treatments">
                <!-- Dynamically populated via JavaScript -->
              </div>
              <button class="btn btn-outline step-back" type="button">
                <span class="btn-icon">←</span>
                <?php esc_html_e('Back', 'preetidreams'); ?>
              </button>
            </div>
            
            <!-- Step 3: Consultation Form -->
            <div class="selection-step" data-step="3">
              <h3 class="step-title"><?php esc_html_e('Book your consultation:', 'preetidreams'); ?></h3>
              <form class="consultation-form" id="hero-consultation-form">
                <div class="form-group">
                  <input type="text" name="full_name" placeholder="<?php esc_attr_e('Your Full Name', 'preetidreams'); ?>" required>
                </div>
                <div class="form-group">
                  <input type="email" name="email" placeholder="<?php esc_attr_e('Email Address', 'preetidreams'); ?>" required>
                </div>
                <div class="form-group">
                  <input type="tel" name="phone" placeholder="<?php esc_attr_e('Phone Number', 'preetidreams'); ?>" required>
                </div>
                <div class="form-group">
                  <textarea name="message" placeholder="<?php esc_attr_e('Tell us about your goals (optional)', 'preetidreams'); ?>" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-large">
                  <span class="btn-icon">📅</span>
                  <?php esc_html_e('Schedule Free Consultation', 'preetidreams'); ?>
                </button>
                <button class="btn btn-outline step-back" type="button">
                  <span class="btn-icon">←</span>
                  <?php esc_html_e('Back', 'preetidreams'); ?>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Seamless Header Integration -->
  <div class="hero-header-connector"></div>
</section>
```

#### **CSS Implementation (medical-spa-theme.css)**
```css
/* =============================================================================
   PREMIUM HERO SYSTEM
   ============================================================================= */

/* Hero Section Foundation */
.premium-hero {
  position: relative;
  min-height: 100vh;
  display: flex;
  align-items: center;
  margin-top: -80px; /* Seamless header integration */
  padding-top: 80px;
  overflow: hidden;
}

/* Dynamic Background System */
.hero-background-system {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 1;
}

.hero-background {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  opacity: 0;
  transition: opacity 1s ease-in-out;
}

.hero-background.active {
  opacity: 1;
}

/* Video Background */
.hero-background-video video {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Image Background */
.hero-background-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Gradient Background */
.hero-background-gradient {
  background: linear-gradient(135deg, var(--color-primary-teal) 0%, var(--color-primary-blue) 100%);
}

/* Hero Content Layer */
.hero-layout {
  position: relative;
  z-index: 2;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 4rem;
  align-items: center;
  min-height: calc(100vh - 80px);
  padding: 2rem 0;
}

/* Left Content Section */
.hero-content-section {
  padding-right: 2rem;
}

.hero-title {
  font-size: var(--text-4xl);
  font-family: var(--font-primary);
  color: var(--color-white);
  margin-bottom: var(--space-lg);
  line-height: 1.2;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.text-gradient {
  background: linear-gradient(135deg, var(--color-accent-gold) 0%, var(--color-accent-coral) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.hero-subtitle {
  font-size: var(--text-lg);
  color: rgba(255, 255, 255, 0.9);
  margin-bottom: var(--space-2xl);
  line-height: 1.6;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

/* Trust Indicators */
.trust-indicators {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: var(--space-md);
  margin-bottom: var(--space-2xl);
  padding: var(--space-xl);
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border-radius: var(--radius-lg);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.trust-item {
  display: flex;
  align-items: center;
  gap: var(--space-sm);
  padding: var(--space-sm);
}

.trust-icon {
  font-size: 1.25rem;
  flex-shrink: 0;
}

.trust-text {
  font-size: var(--text-sm);
  font-weight: 600;
  color: var(--color-white);
}

/* Hero Actions */
.hero-actions {
  display: flex;
  gap: var(--space-lg);
  flex-wrap: wrap;
}

/* Right Interactive Section */
.hero-interactive-section {
  padding-left: 2rem;
}

.treatment-selection-interface {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border-radius: 20px;
  padding: 2rem;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(22, 160, 133, 0.1);
  position: relative;
}

/* Progress Indicator */
.selection-progress {
  margin-bottom: var(--space-xl);
}

.progress-indicator {
  display: flex;
  justify-content: center;
  gap: var(--space-md);
}

.step-number {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--color-neutral-light);
  color: var(--color-neutral-dark);
  font-weight: 600;
  transition: all 0.3s ease;
}

.step-number.active {
  background: var(--color-primary-teal);
  color: var(--color-white);
  transform: scale(1.1);
}

/* Selection Steps */
.selection-step {
  display: none;
  opacity: 0;
  transform: translateX(20px);
  transition: all 0.3s ease;
}

.selection-step.active {
  display: block;
  opacity: 1;
  transform: translateX(0);
}

.step-title {
  font-size: var(--text-xl);
  color: var(--color-primary-navy);
  margin-bottom: var(--space-lg);
  text-align: center;
  font-family: var(--font-primary);
}

/* Treatment Categories */
.treatment-categories {
  display: grid;
  gap: var(--space-md);
}

.category-btn {
  display: flex;
  align-items: center;
  gap: var(--space-md);
  padding: var(--space-lg);
  background: var(--color-white);
  border: 2px solid var(--color-neutral-light);
  border-radius: 12px;
  transition: all 0.3s ease;
  cursor: pointer;
  width: 100%;
  text-align: left;
  font-family: var(--font-secondary);
  font-size: var(--text-base);
}

.category-btn:hover,
.category-btn:focus {
  border-color: var(--color-primary-teal);
  background: var(--overlay-teal);
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(22, 160, 133, 0.15);
  outline: none;
}

.category-icon {
  font-size: 1.5rem;
  flex-shrink: 0;
}

.category-name {
  font-weight: 500;
  color: var(--color-primary-navy);
}

/* Consultation Form */
.consultation-form {
  display: grid;
  gap: var(--space-md);
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: var(--space-md);
  border: 2px solid var(--color-neutral-light);
  border-radius: 8px;
  font-family: var(--font-secondary);
  font-size: var(--text-base);
  transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: var(--color-primary-teal);
  box-shadow: 0 0 0 3px rgba(22, 160, 133, 0.1);
}

/* Step Navigation */
.step-back {
  margin-top: var(--space-md);
  width: auto;
  align-self: flex-start;
}

/* Responsive Design */
@media (max-width: 991px) {
  .hero-layout {
    grid-template-columns: 1fr;
    gap: 3rem;
    text-align: center;
  }
  
  .hero-interactive-section {
    order: -1;
    padding-left: 0;
  }
  
  .hero-content-section {
    padding-right: 0;
  }
  
  .trust-indicators {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 767px) {
  .premium-hero {
    margin-top: -70px;
    padding-top: 70px;
  }
  
  .hero-layout {
    padding: 1rem 0;
    gap: 2rem;
  }
  
  .treatment-selection-interface {
    padding: 1.5rem;
    margin: 0 1rem;
    border-radius: 16px;
  }
  
  .category-btn {
    padding: var(--space-md);
    font-size: var(--text-sm);
  }
  
  .hero-title {
    font-size: var(--text-3xl);
  }
  
  .hero-actions {
    flex-direction: column;
    align-items: center;
  }
  
  .hero-actions .btn {
    width: 100%;
    max-width: 300px;
  }
}

/* Animation Support */
@media (prefers-reduced-motion: reduce) {
  .hero-background,
  .selection-step,
  .category-btn {
    transition: none;
  }
}

/* High Contrast Mode */
@media (prefers-contrast: high) {
  .treatment-selection-interface {
    border: 3px solid var(--color-primary-navy);
  }
  
  .category-btn:focus {
    outline: 3px solid var(--color-primary-teal);
    outline-offset: 2px;
  }
}
```

### **Phase 2: JavaScript Implementation (16 hours)**

#### **Premium Hero System JavaScript (assets/js/components/premium-hero.js)**
```javascript
/**
 * Premium Hero System
 * Interactive treatment selection with dynamic backgrounds
 */
class PremiumHeroSystem {
  constructor() {
    this.currentStep = 1;
    this.selectedCategory = null;
    this.selectedTreatment = null;
    this.backgrounds = ['image', 'video', 'gradient'];
    this.currentBackground = 0;
    this.treatmentData = this.loadTreatmentData();
    this.init();
  }
  
  init() {
    this.bindEvents();
    this.initAOS();
    this.startBackgroundRotation();
    this.trackAnalytics('hero_loaded');
  }
  
  bindEvents() {
    // Category selection
    document.querySelectorAll('.category-btn').forEach(btn => {
      btn.addEventListener('click', (e) => this.handleCategorySelection(e));
      btn.addEventListener('keydown', (e) => this.handleKeyboard(e));
    });
    
    // Step navigation
    document.querySelectorAll('.step-back').forEach(btn => {
      btn.addEventListener('click', () => this.goToStep(this.currentStep - 1));
    });
    
    // Form submission
    const form = document.getElementById('hero-consultation-form');
    if (form) {
      form.addEventListener('submit', (e) => this.handleFormSubmission(e));
    }
    
    // Background switching (admin only)
    if (document.body.classList.contains('admin-bar')) {
      this.initBackgroundSwitcher();
    }
  }
  
  handleCategorySelection(e) {
    e.preventDefault();
    const category = e.currentTarget.dataset.category;
    this.selectedCategory = category;
    
    // Visual feedback
    document.querySelectorAll('.category-btn').forEach(btn => 
      btn.classList.remove('selected'));
    e.currentTarget.classList.add('selected');
    
    // Load specific treatments
    setTimeout(() => {
      this.loadSpecificTreatments(category);
      this.goToStep(2);
    }, 300);
    
    this.trackAnalytics('category_selected', { category });
  }
  
  loadSpecificTreatments(category) {
    const container = document.querySelector('.specific-treatments');
    const treatments = this.treatmentData[category] || [];
    
    container.innerHTML = treatments.map(treatment => `
      <button class="treatment-btn" data-treatment="${treatment.slug}">
        <span class="treatment-icon">${treatment.icon}</span>
        <div class="treatment-info">
          <h4 class="treatment-name">${treatment.name}</h4>
          <p class="treatment-price">${treatment.price}</p>
        </div>
      </button>
    `).join('');
    
    // Bind events for specific treatments
    container.querySelectorAll('.treatment-btn').forEach(btn => {
      btn.addEventListener('click', (e) => this.handleTreatmentSelection(e));
    });
  }
  
  handleTreatmentSelection(e) {
    e.preventDefault();
    const treatment = e.currentTarget.dataset.treatment;
    this.selectedTreatment = treatment;
    
    // Visual feedback
    document.querySelectorAll('.treatment-btn').forEach(btn => 
      btn.classList.remove('selected'));
    e.currentTarget.classList.add('selected');
    
    // Pre-fill form data
    this.prefillForm();
    
    setTimeout(() => {
      this.goToStep(3);
    }, 300);
    
    this.trackAnalytics('treatment_selected', { 
      category: this.selectedCategory,
      treatment 
    });
  }
  
  prefillForm() {
    const form = document.getElementById('hero-consultation-form');
    const messageField = form.querySelector('textarea[name="message"]');
    
    if (messageField && this.selectedCategory && this.selectedTreatment) {
      const treatmentInfo = this.getTreatmentInfo(this.selectedCategory, this.selectedTreatment);
      messageField.placeholder = `I'm interested in ${treatmentInfo.name}. Please provide more information.`;
    }
  }
  
  goToStep(step) {
    if (step < 1 || step > 3) return;
    
    // Update step indicators
    document.querySelectorAll('.step-number').forEach((indicator, index) => {
      indicator.classList.toggle('active', index + 1 <= step);
    });
    
    // Show/hide steps
    document.querySelectorAll('.selection-step').forEach((stepEl, index) => {
      stepEl.classList.toggle('active', index + 1 === step);
    });
    
    this.currentStep = step;
    this.trackAnalytics('step_changed', { step });
  }
  
  handleFormSubmission(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = {
      full_name: formData.get('full_name'),
      email: formData.get('email'),
      phone: formData.get('phone'),
      message: formData.get('message'),
      selected_category: this.selectedCategory,
      selected_treatment: this.selectedTreatment,
      source: 'hero_treatment_selection'
    };
    
    // Validate form
    if (!this.validateForm(data)) {
      return;
    }
    
    // Submit to WordPress
    this.submitConsultation(data);
  }
  
  validateForm(data) {
    const errors = [];
    
    if (!data.full_name || data.full_name.length < 2) {
      errors.push('Please enter your full name');
    }
    
    if (!data.email || !this.isValidEmail(data.email)) {
      errors.push('Please enter a valid email address');
    }
    
    if (!data.phone || data.phone.length < 10) {
      errors.push('Please enter a valid phone number');
    }
    
    if (errors.length > 0) {
      this.showValidationErrors(errors);
      return false;
    }
    
    return true;
  }
  
  submitConsultation(data) {
    const submitBtn = document.querySelector('#hero-consultation-form button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Loading state
    submitBtn.innerHTML = '<span class="loading-spinner"></span> Submitting...';
    submitBtn.disabled = true;
    
    fetch(ajax_object.ajax_url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams({
        action: 'submit_hero_consultation',
        nonce: ajax_object.nonce,
        ...data
      })
    })
    .then(response => response.json())
    .then(result => {
      if (result.success) {
        this.showSuccessMessage();
        this.trackAnalytics('consultation_submitted', data);
      } else {
        this.showErrorMessage(result.data);
      }
    })
    .catch(error => {
      this.showErrorMessage('Something went wrong. Please try again.');
      console.error('Submission error:', error);
    })
    .finally(() => {
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
    });
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
  
  initAOS() {
    if (typeof AOS !== 'undefined') {
      AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true,
        offset: 100
      });
    }
  }
  
  loadTreatmentData() {
    // Treatment data structure
    return {
      facial: [
        { slug: 'hydrafacial', name: 'HydraFacial', price: 'Starting at $150', icon: '✨' },
        { slug: 'chemical-peel', name: 'Chemical Peel', price: 'Starting at $100', icon: '🌟' },
        { slug: 'microneedling', name: 'Microneedling', price: 'Starting at $200', icon: '💫' }
      ],
      injectable: [
        { slug: 'botox', name: 'Botox', price: 'Starting at $12/unit', icon: '💉' },
        { slug: 'dermal-fillers', name: 'Dermal Fillers', price: 'Starting at $600', icon: '💎' },
        { slug: 'lip-fillers', name: 'Lip Fillers', price: 'Starting at $500', icon: '💋' }
      ],
      laser: [
        { slug: 'laser-hair-removal', name: 'Laser Hair Removal', price: 'Starting at $100', icon: '💎' },
        { slug: 'laser-resurfacing', name: 'Laser Resurfacing', price: 'Starting at $300', icon: '✨' },
        { slug: 'ipl-photofacial', name: 'IPL Photofacial', price: 'Starting at $250', icon: '🌟' }
      ],
      body: [
        { slug: 'coolsculpting', name: 'CoolSculpting', price: 'Starting at $750', icon: '🌟' },
        { slug: 'radiofrequency', name: 'RF Body Contouring', price: 'Starting at $400', icon: '⚡' },
        { slug: 'lymphatic-drainage', name: 'Lymphatic Drainage', price: 'Starting at $150', icon: '🌊' }
      ]
    };
  }
  
  getTreatmentInfo(category, treatmentSlug) {
    const treatments = this.treatmentData[category] || [];
    return treatments.find(t => t.slug === treatmentSlug) || {};
  }
  
  trackAnalytics(event, data = {}) {
    if (typeof gtag !== 'undefined') {
      gtag('event', event, {
        event_category: 'hero_interaction',
        event_label: JSON.stringify(data),
        value: 1
      });
    }
  }
  
  handleKeyboard(e) {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      e.target.click();
    }
  }
  
  isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }
  
  showValidationErrors(errors) {
    // Implementation for showing validation errors
    const errorDiv = document.createElement('div');
    errorDiv.className = 'validation-errors';
    errorDiv.innerHTML = errors.map(error => `<p class="error-message">${error}</p>`).join('');
    
    const form = document.getElementById('hero-consultation-form');
    const existingErrors = form.querySelector('.validation-errors');
    if (existingErrors) {
      existingErrors.remove();
    }
    
    form.insertBefore(errorDiv, form.firstChild);
    
    setTimeout(() => {
      errorDiv.remove();
    }, 5000);
  }
  
  showSuccessMessage() {
    const interface = document.querySelector('.treatment-selection-interface');
    interface.innerHTML = `
      <div class="success-message">
        <div class="success-icon">✅</div>
        <h3>Thank You!</h3>
        <p>Your consultation request has been submitted. We'll contact you within 24 hours to schedule your appointment.</p>
        <button class="btn btn-primary" onclick="location.reload()">
          Select Another Treatment
        </button>
      </div>
    `;
  }
  
  showErrorMessage(message) {
    // Implementation for showing error messages
    console.error('Form submission error:', message);
  }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  if (document.querySelector('.premium-hero')) {
    new PremiumHeroSystem();
  }
});
```

### **Phase 3: WordPress Integration (8 hours)**

#### **Functions.php Updates**
```php
// Add to functions.php

/**
 * Enqueue Premium Hero System Assets
 */
function preetidreams_enqueue_hero_assets() {
    if (is_front_page()) {
        // AOS Library for animations
        wp_enqueue_style('aos-css', 'https://unpkg.com/aos@2.3.1/dist/aos.css', [], '2.3.1');
        wp_enqueue_script('aos-js', 'https://unpkg.com/aos@2.3.1/dist/aos.js', [], '2.3.1', true);
        
        // Premium Hero System
        wp_enqueue_script(
            'premium-hero',
            get_template_directory_uri() . '/assets/js/components/premium-hero.js',
            ['jquery'],
            PREETIDREAMS_VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('premium-hero', 'ajax_object', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('hero_consultation_nonce')
        ]);
    }
}
add_action('wp_enqueue_scripts', 'preetidreams_enqueue_hero_assets');

/**
 * Handle Hero Consultation Form Submission
 */
function handle_hero_consultation_submission() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'hero_consultation_nonce')) {
        wp_die('Security check failed');
    }
    
    // Sanitize form data
    $data = [
        'full_name' => sanitize_text_field($_POST['full_name']),
        'email' => sanitize_email($_POST['email']),
        'phone' => sanitize_text_field($_POST['phone']),
        'message' => sanitize_textarea_field($_POST['message']),
        'selected_category' => sanitize_text_field($_POST['selected_category']),
        'selected_treatment' => sanitize_text_field($_POST['selected_treatment']),
        'source' => sanitize_text_field($_POST['source'])
    ];
    
    // Validate required fields
    if (empty($data['full_name']) || empty($data['email']) || empty($data['phone'])) {
        wp_send_json_error('Please fill in all required fields');
    }
    
    // Store consultation request
    $consultation_id = wp_insert_post([
        'post_type' => 'consultation_request',
        'post_status' => 'private',
        'post_title' => 'Hero Consultation: ' . $data['full_name'],
        'post_content' => $data['message'],
        'meta_input' => [
            'contact_email' => $data['email'],
            'contact_phone' => $data['phone'],
            'selected_category' => $data['selected_category'],
            'selected_treatment' => $data['selected_treatment'],
            'source' => $data['source'],
            'submission_date' => current_time('mysql')
        ]
    ]);
    
    if ($consultation_id) {
        // Send notification email
        $admin_email = get_option('admin_email');
        $subject = 'New Hero Consultation Request from ' . $data['full_name'];
        $message = "New consultation request received:\n\n";
        $message .= "Name: " . $data['full_name'] . "\n";
        $message .= "Email: " . $data['email'] . "\n";
        $message .= "Phone: " . $data['phone'] . "\n";
        $message .= "Category: " . $data['selected_category'] . "\n";
        $message .= "Treatment: " . $data['selected_treatment'] . "\n";
        $message .= "Message: " . $data['message'] . "\n";
        
        wp_mail($admin_email, $subject, $message);
        
        wp_send_json_success('Consultation request submitted successfully');
    } else {
        wp_send_json_error('Failed to submit consultation request');
    }
}
add_action('wp_ajax_submit_hero_consultation', 'handle_hero_consultation_submission');
add_action('wp_ajax_nopriv_submit_hero_consultation', 'handle_hero_consultation_submission');

/**
 * Register Consultation Request Custom Post Type
 */
function register_consultation_request_post_type() {
    register_post_type('consultation_request', [
        'labels' => [
            'name' => 'Consultation Requests',
            'singular_name' => 'Consultation Request'
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_admin_bar' => false,
        'capability_type' => 'post',
        'supports' => ['title', 'editor'],
        'menu_icon' => 'dashicons-calendar-alt'
    ]);
}
add_action('init', 'register_consultation_request_post_type');

/**
 * Add Hero Customizer Options
 */
function preetidreams_hero_customizer($wp_customize) {
    // Hero Section
    $wp_customize->add_section('hero_section', [
        'title' => 'Premium Hero Section',
        'priority' => 30
    ]);
    
    // Hero Title
    $wp_customize->add_setting('hero_title', [
        'default' => 'Transform Your Beauty with Advanced Medical Spa Treatments',
        'sanitize_callback' => 'sanitize_text_field'
    ]);
    
    $wp_customize->add_control('hero_title', [
        'label' => 'Hero Title',
        'section' => 'hero_section',
        'type' => 'text'
    ]);
    
    // Hero Subtitle
    $wp_customize->add_setting('hero_subtitle', [
        'default' => 'Experience the latest in non-surgical aesthetic treatments performed by board-certified professionals in a luxurious, comfortable environment.',
        'sanitize_callback' => 'sanitize_textarea_field'
    ]);
    
    $wp_customize->add_control('hero_subtitle', [
        'label' => 'Hero Subtitle',
        'section' => 'hero_section',
        'type' => 'textarea'
    ]);
    
    // Background Image
    $wp_customize->add_setting('hero_background_image');
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_background_image', [
        'label' => 'Hero Background Image',
        'section' => 'hero_section'
    ]));
    
    // Background Video
    $wp_customize->add_setting('hero_background_video', [
        'sanitize_callback' => 'esc_url_raw'
    ]);
    
    $wp_customize->add_control('hero_background_video', [
        'label' => 'Hero Background Video URL',
        'section' => 'hero_section',
        'type' => 'url'
    ]);
}
add_action('customize_register', 'preetidreams_hero_customizer');
```

## ✅ **Implementation Checklist**

### **Phase 1: Foundation (✅ Ready)**
- [ ] Update front-page.php with premium hero HTML structure
- [ ] Implement CSS grid layout with responsive breakpoints
- [ ] Add seamless header integration (negative margin/positive padding)
- [ ] Create dynamic background system (image, video, gradient)
- [ ] Implement trust indicators and CTA buttons
- [ ] Add accessibility features (ARIA labels, keyboard navigation)

### **Phase 2: Interactive Interface (✅ Ready)**
- [ ] Develop treatment selection interface with categories
- [ ] Implement multi-step progression with visual indicators
- [ ] Create dynamic treatment loading based on category
- [ ] Add consultation form with real-time validation
- [ ] Implement smooth animations and transitions
- [ ] Add mobile-optimized touch interface

### **Phase 3: WordPress Integration (✅ Ready)**
- [ ] Create consultation request custom post type
- [ ] Implement AJAX form submission handler
- [ ] Add email notifications for new consultations
- [ ] Create customizer options for hero content
- [ ] Integrate with existing theme systems
- [ ] Add analytics tracking for user interactions

### **Phase 4: Testing & Optimization (✅ Ready)**
- [ ] Cross-browser compatibility testing
- [ ] Mobile device testing (iOS, Android)
- [ ] Accessibility compliance validation (WCAG AAA)
- [ ] Performance optimization (Core Web Vitals)
- [ ] A/B testing framework setup
- [ ] Analytics integration and event tracking

## 📊 **Testing Requirements**

### **Functional Testing**
- ✅ Treatment category selection works correctly
- ✅ Step progression flows smoothly
- ✅ Form validation prevents invalid submissions
- ✅ AJAX submission works without page reload
- ✅ Email notifications are sent to admin
- ✅ Background rotation functions properly

### **Accessibility Testing**
- ✅ Keyboard navigation works for all interactive elements
- ✅ Screen reader can access all content
- ✅ Focus management works correctly in multi-step interface
- ✅ Color contrast meets WCAG AAA standards
- ✅ Reduced motion preferences are respected

### **Performance Testing**
- ✅ Hero section loads in <1.5 seconds
- ✅ Interactive elements respond in <100ms
- ✅ Mobile performance score 95+ on PageSpeed
- ✅ Video background doesn't impact mobile performance
- ✅ Core Web Vitals are optimized

### **Responsive Testing**
- ✅ Layout works on all breakpoints (320px - 2560px)
- ✅ Touch targets are 44px+ on mobile
- ✅ Content is readable on all screen sizes
- ✅ Interface reorders correctly on mobile
- ✅ Forms are usable on touch devices

## 🔗 **Dependencies & Integration**

**Required Dependencies**:
- ✅ REQ-ARCH-001-REFINED: WordPress theme foundation
- ✅ REQ-FUNC-001-REFINED: Treatment management system
- ✅ Modern header navigation system
- ✅ UI-UX-ARCHITECTURE.md design system

**Optional Enhancements**:
- ✅ Google Analytics 4 for event tracking
- ✅ AOS library for scroll animations
- ✅ Contact Form 7 for alternative form handling
- ✅ ACF Pro for advanced customization options

**Integration Points**:
- ✅ Header navigation for seamless visual connection
- ✅ Treatment custom post type for dynamic content
- ✅ Consultation booking system for conversion funnel
- ✅ Email notification system for lead management

## 📈 **Success Metrics & KPIs**

### **Technical Metrics**
- Hero section load time: Target <1.5s
- Interactive response time: Target <100ms
- Mobile performance score: Target 95+
- Accessibility score: Target 100% WCAG AAA

### **Business Metrics**
- Consultation conversion rate: Target 60% increase
- User engagement time: Target 85% increase
- Treatment page traffic: Target 40% increase
- Bounce rate: Target 75% reduction

### **User Experience Metrics**
- Treatment selection completion: Target 80%
- Form submission completion: Target 65%
- Mobile usability score: Target 95+
- User satisfaction: Target 4.5/5

## 🚀 **Deployment Plan**

### **Pre-Deployment**
1. Complete all implementation phases
2. Run comprehensive testing suite
3. Validate with stakeholders
4. Backup existing hero section
5. Prepare rollback plan

### **Deployment**
1. Deploy to staging environment
2. Conduct final testing
3. Deploy to production during low-traffic hours
4. Monitor performance metrics
5. Collect user feedback

### **Post-Deployment**
1. Monitor analytics for 48 hours
2. Address any performance issues
3. A/B test different variations
4. Optimize based on user behavior
5. Document lessons learned

---

**Status**: ✅ Ready for Implementation  
**Next Action**: Begin Phase 1 Foundation Setup  
**Estimated Completion**: End of iteration-4 (54 hours total)  
**Human Review Required**: Medical content accuracy and conversion strategy validation  
**Last Updated**: 2025-01-28  
**Implementation Priority**: High 
