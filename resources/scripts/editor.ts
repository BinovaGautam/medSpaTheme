/**
 * PreetiDreams Medical Spa Theme - Block Editor Integration
 *
 * Gutenberg block editor styles and functionality for medical spa content creation
 *
 * @version 1.0.0
 * @author PreetiDreams Development Team
 */

// ==================================================
// EDITOR IMPORTS
// ==================================================

import type { MedicalSpaConfig } from './types/Config';

// ==================================================
// EDITOR CONFIGURATION
// ==================================================

const EDITOR_CONFIG: MedicalSpaConfig = {
  version: __MEDICAL_SPA_VERSION__ || '1.0.0',
  hipaaMode: __HIPAA_MODE__ || false,
  accessibilityMode: __ACCESSIBILITY_MODE__ !== false,
  performanceMode: true,
  debugMode: process.env.NODE_ENV === 'development',
};

// ==================================================
// MEDICAL SPA BLOCK EDITOR ENHANCEMENTS
// ==================================================

class MedicalSpaEditor {
  constructor() {
    this.initializeEditor();
  }

  /**
   * Initialize editor enhancements
   */
  private initializeEditor(): void {
    // Wait for WordPress editor to be ready
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => this.setupEditor());
    } else {
      this.setupEditor();
    }
  }

  /**
   * Setup editor functionality
   */
  private setupEditor(): void {
    // Add medical spa specific classes to editor
    this.addEditorClasses();

    // Initialize HIPAA-conscious editing features
    if (EDITOR_CONFIG.hipaaMode) {
      this.initializeHipaaFeatures();
    }

    // Add accessibility tools
    if (EDITOR_CONFIG.accessibilityMode) {
      this.initializeAccessibilityTools();
    }

    // Add medical spa color palette
    this.addMedicalSpaColorPalette();

    // Add treatment-specific block patterns
    this.registerBlockPatterns();

    console.log('🏥 Medical Spa Editor initialized');
  }

  /**
   * Add medical spa specific CSS classes to editor
   */
  private addEditorClasses(): void {
    const editor = document.querySelector('.editor-styles-wrapper');

    if (editor) {
      editor.classList.add('medical-spa-editor');

      if (EDITOR_CONFIG.hipaaMode) {
        editor.classList.add('hipaa-mode');
      }

      if (EDITOR_CONFIG.accessibilityMode) {
        editor.classList.add('accessibility-mode');
      }
    }
  }

  /**
   * Initialize HIPAA-conscious editing features
   */
  private initializeHipaaFeatures(): void {
    // Add HIPAA warning for sensitive content
    const addHipaaWarning = () => {
      const blocks = document.querySelectorAll('[data-type="core/paragraph"], [data-type="core/heading"]');

      blocks.forEach((block) => {
        const content = block.textContent?.toLowerCase() || '';

        // Check for potential PHI keywords
        const phiKeywords = ['patient', 'diagnosis', 'treatment plan', 'medical history', 'consultation'];
        const containsPhi = phiKeywords.some(keyword => content.includes(keyword));

        if (containsPhi && !block.querySelector('.hipaa-warning')) {
          const warning = document.createElement('div');
          warning.className = 'hipaa-warning';
          warning.innerHTML = '🔒 Potential PHI - Review for HIPAA compliance';
          warning.style.cssText = `
            background: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: 8px;
            border-radius: 4px;
            font-size: 12px;
            margin: 4px 0;
          `;

          block.appendChild(warning);
        }
      });
    };

    // Monitor for content changes
    const observer = new MutationObserver(addHipaaWarning);
    observer.observe(document.body, {
      childList: true,
      subtree: true,
      characterData: true
    });
  }

  /**
   * Initialize accessibility tools for content creators
   */
  private initializeAccessibilityTools(): void {
    // Add accessibility checklist panel
    const addA11yPanel = () => {
      const existingPanel = document.querySelector('.medical-spa-a11y-panel');
      if (existingPanel) return;

      const panel = document.createElement('div');
      panel.className = 'medical-spa-a11y-panel';
      panel.innerHTML = `
        <div style="position: fixed; top: 100px; right: 20px; width: 280px; background: white; border: 1px solid #ddd; border-radius: 8px; padding: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); z-index: 9999;">
          <h4 style="margin: 0 0 12px 0; color: #1e40af;">♿ Accessibility Checklist</h4>
          <ul style="list-style: none; padding: 0; margin: 0; font-size: 14px;">
            <li style="margin: 8px 0;"><input type="checkbox" id="alt-text"> <label for="alt-text">Images have alt text</label></li>
            <li style="margin: 8px 0;"><input type="checkbox" id="headings"> <label for="headings">Proper heading hierarchy</label></li>
            <li style="margin: 8px 0;"><input type="checkbox" id="contrast"> <label for="contrast">Sufficient color contrast</label></li>
            <li style="margin: 8px 0;"><input type="checkbox" id="links"> <label for="links">Descriptive link text</label></li>
          </ul>
          <button onclick="this.parentElement.parentElement.remove()" style="background: #ef4444; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 12px; margin-top: 12px;">Close</button>
        </div>
      `;

      document.body.appendChild(panel);
    };

    // Add accessibility button to toolbar
    setTimeout(addA11yPanel, 2000);
  }

  /**
   * Add medical spa color palette to editor
   */
  private addMedicalSpaColorPalette(): void {
    // This would typically be done via PHP filters, but we can add CSS overrides
    const style = document.createElement('style');
    style.textContent = `
      .components-color-palette__custom-color {
        /* Medical spa color swatches in editor */
      }

      .medical-spa-editor .has-sage-color {
        color: #8ba18b !important;
      }

      .medical-spa-editor .has-sage-background-color {
        background-color: #8ba18b !important;
      }

      .medical-spa-editor .has-gold-color {
        color: #e6c954 !important;
      }

      .medical-spa-editor .has-gold-background-color {
        background-color: #e6c954 !important;
      }
    `;

    document.head.appendChild(style);
  }

  /**
   * Register medical spa block patterns
   */
  private registerBlockPatterns(): void {
    // Treatment card pattern
    const treatmentPattern = {
      title: 'Treatment Card',
      description: 'A card layout for showcasing medical spa treatments',
      categories: ['medical-spa'],
      content: `
        <!-- wp:group {"className":"treatment-card"} -->
        <div class="wp-block-group treatment-card">
          <!-- wp:image -->
          <figure class="wp-block-image"><img alt="Treatment image"/></figure>
          <!-- /wp:image -->

          <!-- wp:heading {"level":3} -->
          <h3>Treatment Name</h3>
          <!-- /wp:heading -->

          <!-- wp:paragraph -->
          <p>Treatment description highlighting benefits and procedure details.</p>
          <!-- /wp:paragraph -->

          <!-- wp:buttons -->
          <div class="wp-block-buttons">
            <!-- wp:button {"className":"consultation-cta"} -->
            <div class="wp-block-button consultation-cta"><a class="wp-block-button__link">Book Consultation</a></div>
            <!-- /wp:button -->
          </div>
          <!-- /wp:buttons -->
        </div>
        <!-- /wp:group -->
      `
    };

    // Store pattern for potential registration via PHP
    (window as any).medicalSpaPatterns = (window as any).medicalSpaPatterns || [];
    (window as any).medicalSpaPatterns.push(treatmentPattern);
  }
}

// ==================================================
// EDITOR STYLES INTEGRATION
// ==================================================

/**
 * Add medical spa specific editor styles
 */
function addEditorStyles(): void {
  const editorStyle = document.createElement('style');
  editorStyle.id = 'medical-spa-editor-styles';
  editorStyle.textContent = `
    /* Medical Spa Editor Styles */
    .medical-spa-editor {
      font-family: 'Inter', system-ui, sans-serif;
    }

    .medical-spa-editor h1,
    .medical-spa-editor h2,
    .medical-spa-editor h3,
    .medical-spa-editor h4,
    .medical-spa-editor h5,
    .medical-spa-editor h6 {
      font-family: 'Playfair Display', Georgia, serif;
      color: #4d554d;
    }

    .medical-spa-editor .treatment-card {
      border: 1px solid #dde6dd;
      border-radius: 16px;
      padding: 24px;
      background: white;
      box-shadow: 0 8px 25px -5px rgba(139, 161, 139, 0.2);
    }

    .medical-spa-editor .consultation-cta {
      background: linear-gradient(135deg, #e6c954 0%, #b29237 100%);
      border-radius: 12px;
    }

    .medical-spa-editor .hipaa-secure {
      border-left: 4px solid #1e40af;
      background-color: #eff6ff;
      padding: 16px;
      border-radius: 8px;
      position: relative;
    }

    .medical-spa-editor .hipaa-secure::before {
      content: "🔒 HIPAA Secure Content";
      display: block;
      font-weight: 600;
      color: #1e40af;
      margin-bottom: 8px;
      font-size: 14px;
    }

    /* Accessibility indicators */
    .medical-spa-editor img:not([alt]) {
      outline: 2px solid #ef4444 !important;
      outline-offset: 2px;
    }

    .medical-spa-editor img:not([alt])::after {
      content: "Missing alt text";
      position: absolute;
      background: #ef4444;
      color: white;
      padding: 4px 8px;
      font-size: 12px;
      border-radius: 4px;
      top: 0;
      left: 0;
    }
  `;

  document.head.appendChild(editorStyle);
}

// ==================================================
// INITIALIZATION
// ==================================================

// Initialize editor when ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    new MedicalSpaEditor();
    addEditorStyles();
  });
} else {
  new MedicalSpaEditor();
  addEditorStyles();
}

// Export for module systems
export { MedicalSpaEditor, EDITOR_CONFIG };
