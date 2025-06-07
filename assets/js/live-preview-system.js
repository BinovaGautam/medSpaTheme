/**
 * Live Preview System - PVC-005 Implementation
 * Real-time color application and preview functionality
 *
 * Technical Specifications:
 * - T5.1: CSS Variable Injection
 * - T5.2: Preview Window Communication
 * - T5.3: Color Computation Engine
 * - T5.4: WordPress Preview Integration
 */

class LivePreviewSystem {
    constructor(options = {}) {
        this.options = {
            updateDelay: 50, // < 100ms requirement
            enableDebug: options.debug || false,
            cssVariablePrefix: '--color-',
            previewContainerId: 'live-preview-container',
            ...options
        };

        this.isPreviewMode = false;
        this.currentPalette = null;
        this.originalStyles = new Map();
        this.performanceMonitor = new PerformanceMonitor();

        this.init();
    }

    /**
     * T5.1: CSS Variable Injection - Initialize system
     */
    init() {
        this.log('🎨 Live Preview System: Initializing...');

        // Setup CSS injection container
        this.setupCSSContainer();

        // Initialize performance monitoring
        this.performanceMonitor.start();

        // Setup event listeners
        this.setupEventListeners();

        // WordPress integration hooks
        this.setupWordPressIntegration();

        this.log('✅ Live Preview System: Ready');
    }

    /**
     * T5.1: Dynamic CSS custom property updates
     */
    setupCSSContainer() {
        // Remove ALL existing preview styles (fix for persistence issue)
        const existingStyles = document.querySelectorAll('style[data-preview-system], style[id*="preview"], style[id*="emergency"]');
        existingStyles.forEach(style => style.remove());

        // Create new style container
        this.styleElement = document.createElement('style');
        this.styleElement.id = 'live-preview-styles';
        this.styleElement.setAttribute('data-preview-system', 'live-preview-system');
        this.styleElement.setAttribute('data-timestamp', Date.now());
        document.head.appendChild(this.styleElement);
    }

    /**
     * T5.2: Preview Window Communication - PostMessage API
     */
    setupEventListeners() {
        // Listen for palette changes
        document.addEventListener('paletteInterface:paletteSelected', (event) => {
            this.handlePaletteChange(event.detail);
        });

        // Listen for preview mode toggle
        document.addEventListener('previewSystem:togglePreview', (event) => {
            this.togglePreviewMode(event.detail);
        });

        // PostMessage API for cross-frame communication
        window.addEventListener('message', (event) => {
            this.handlePreviewMessage(event);
        });

        // Performance monitoring
        document.addEventListener('visibilitychange', () => {
            this.performanceMonitor.handleVisibilityChange();
        });
    }

    /**
     * T5.4: WordPress Preview Integration
     */
    setupWordPressIntegration() {
        // WordPress Customizer integration
        if (typeof wp !== 'undefined' && wp.customize) {
            this.setupWordPressCustomizer();
        }

        // WordPress preview window integration
        if (window.parent !== window) {
            this.setupWordPressPreviewWindow();
        }
    }

    /**
     * WordPress Customizer API integration
     */
    setupWordPressCustomizer() {
        this.log('🔄 WordPress Customizer: Integrating...');

        // Bind to WordPress customizer events
        wp.customize.bind('ready', () => {
            this.log('✅ WordPress Customizer: Ready');
        });

        // Listen for preview refresh
        wp.customize.preview.bind('active', () => {
            this.enablePreviewMode();
        });
    }

    /**
     * WordPress preview window setup
     */
    setupWordPressPreviewWindow() {
        this.log('🖼️ WordPress Preview Window: Setting up communication...');

        // Send ready message to parent
        window.parent.postMessage({
            type: 'preview-ready',
            source: 'live-preview-system'
        }, '*');
    }

    /**
     * T5.1: Real-time palette application
     */
    async applyPalette(paletteData, options = {}) {
        const startTime = performance.now();

        try {
            this.log(`🎨 Applying palette: ${paletteData.name || paletteData.id || 'Unknown'}`);

            // ENHANCED DEBUG: Log detailed palette information
            this.log(`📊 Palette Details:`, {
                id: paletteData.id,
                name: paletteData.name,
                hasColors: !!paletteData.colors,
                colorCount: paletteData.colors ? Object.keys(paletteData.colors).length : 0,
                colors: paletteData.colors
            });

            // Store current palette
            this.currentPalette = paletteData;

            // CRITICAL: Ensure preview mode is active for CSS to take effect
            if (!this.isPreviewMode) {
                this.log('🔄 Enabling preview mode for palette application...');
                this.enablePreviewMode();
            } else {
                this.log('✅ Preview mode already active');
            }

            // Generate CSS variables
            const cssVariables = this.generateCSSVariables(paletteData);
            this.log(`🎯 Generated ${cssVariables.size} CSS variables:`, Array.from(cssVariables.entries()));

            // T5.3: Color computation for derived colors
            const computedColors = await this.computeDerivedColors(paletteData);
            this.log(`🧮 Computed ${computedColors.size} derived colors:`, Array.from(computedColors.entries()));

            // Apply CSS injection with forced preview mode
            this.injectCSS(cssVariables, computedColors, {
                ...options,
                forcePreviewMode: true
            });

            // Update performance metrics
            const duration = performance.now() - startTime;
            this.performanceMonitor.recordUpdate(duration);

            // Dispatch success event
            this.dispatchEvent('preview:paletteApplied', {
                palette: paletteData,
                duration: duration,
                performance: this.performanceMonitor.getMetrics()
            });

            this.log(`✅ Palette applied in ${duration.toFixed(2)}ms`);

        } catch (error) {
            this.handleError('Palette application failed', error);
        }
    }

    /**
     * T5.3: Color Computation Engine - Generate CSS variables mapped to actual theme
     */
    generateCSSVariables(paletteData) {
        const variables = new Map();

        if (!paletteData.colors) {
            this.log('⚠️ No colors in palette data');
            return variables;
        }

        // CRITICAL FIX: Map semantic color roles to ACTUAL theme variables used by medical-spa-theme.css
        const actualThemeMapping = {
            // Primary mapping to actual theme variables (lines 25-28 in medical-spa-theme.css)
            'primary': '--color-primary-navy',        // Maps to actual --color-primary-navy: #2c3e50
            'secondary': '--color-primary-teal',      // Maps to actual --color-primary-teal: #16a085
            'accent': '--color-secondary-peach',      // Maps to actual --color-secondary-peach: #f39c12
            'surface': '--color-neutral-white',       // Maps to actual --color-neutral-white: #ffffff
            'background': '--color-neutral-light',    // Maps to actual --color-neutral-light: #ecf0f1
            'text': '--color-neutral-dark'            // Maps to actual --color-neutral-dark: #34495e
        };

        // Legacy compatibility mapping for older theme references
        const legacyThemeMapping = {
            'primary': '--color-primary-forest',      // Legacy mapping (line 42)
            'secondary': '--color-primary-sage',      // Legacy mapping (line 43)
            'accent': '--color-primary-gold',         // Legacy mapping (line 44)
            'surface': '--color-white',               // Legacy mapping (line 45)
            'background': '--color-soft-gray',        // Legacy mapping (line 46)
            'text': '--color-charcoal'                // Legacy mapping (line 47)
        };

        // Additional theme variables that actually exist in the CSS
        const additionalThemeVars = {
            'primary': [
                '--color-primary-blue',     // #3498db
                '--color-accent-coral'      // #e74c3c
            ],
            'secondary': [
                '--color-secondary-sage',   // #95a5a6
                '--color-secondary-mint',   // #1abc9c
                '--color-secondary-lavender' // #9b59b6
            ],
            'accent': [
                '--color-accent-success',   // #27ae60
                '--color-accent-warning',   // #f1c40f
                '--color-accent-error',     // #e67e22
                '--color-accent-info'       // #3498db
            ]
        };

        // Generate mapped variables for ACTUAL theme system
        Object.entries(paletteData.colors).forEach(([role, colorData]) => {
            const colorValue = colorData.hex || colorData;

            // 1. Map to PRIMARY actual theme variables
            const primaryVar = actualThemeMapping[role];
            if (primaryVar) {
                variables.set(primaryVar, colorValue);
                // Generate RGB versions that theme expects
                variables.set(`${primaryVar.replace('--color-', '--color-')}-rgb`, this.hexToRgb(colorValue));
                this.log(`🎨 PRIMARY MAPPING: ${role} (${colorValue}) -> ${primaryVar}`);
            }

            // 2. Map to LEGACY compatibility variables
            const legacyVar = legacyThemeMapping[role];
            if (legacyVar) {
                variables.set(legacyVar, colorValue);
                this.log(`🎨 LEGACY MAPPING: ${role} (${colorValue}) -> ${legacyVar}`);
            }

            // 3. Map to additional theme variables for comprehensive coverage
            const additionalVars = additionalThemeVars[role];
            if (additionalVars) {
                additionalVars.forEach(varName => {
                    variables.set(varName, colorValue);
                    this.log(`🎨 ADDITIONAL MAPPING: ${role} (${colorValue}) -> ${varName}`);
                });
            }
        });

        // CRITICAL: Map to gradient variables that theme actually uses (lines 48-52)
        const gradientMapping = {
            'primary': '--gradient-primary',          // linear-gradient(135deg, var(--color-primary-teal) 0%, var(--color-primary-blue) 100%)
            'accent': '--gradient-accent',            // linear-gradient(135deg, var(--color-secondary-peach) 0%, var(--color-accent-coral) 100%)
            'luxury': '--gradient-luxury',            // linear-gradient(135deg, var(--color-secondary-lavender) 0%, var(--color-primary-navy) 100%)
            'fresh': '--gradient-fresh',              // linear-gradient(135deg, var(--color-secondary-mint) 0%, var(--color-primary-teal) 100%)
            'gold': '--gradient-gold',                // Alias for gradient-accent
            'trust': '--gradient-trust'               // Alias for gradient-fresh
        };

        // Generate gradients using palette colors
        Object.entries(paletteData.colors).forEach(([role, colorData]) => {
            const colorValue = colorData.hex || colorData;

            if (role === 'primary') {
                const primaryGradient = `linear-gradient(135deg, ${colorValue} 0%, ${this.adjustColorBrightness(colorValue, -0.15)} 100%)`;
                variables.set('--gradient-primary', primaryGradient);
                variables.set('--gradient-trust', primaryGradient); // Alias
                this.log(`🌈 PRIMARY GRADIENT: --gradient-primary = ${primaryGradient}`);
            }

            if (role === 'accent') {
                const accentGradient = `linear-gradient(135deg, ${colorValue} 0%, ${this.adjustColorBrightness(colorValue, 0.1)} 100%)`;
                variables.set('--gradient-accent', accentGradient);
                variables.set('--gradient-gold', accentGradient); // Alias
                this.log(`🌈 ACCENT GRADIENT: --gradient-accent = ${accentGradient}`);
            }

            if (role === 'secondary') {
                const luxuryGradient = `linear-gradient(135deg, ${colorValue} 0%, ${variables.get('--color-primary-navy') || colorValue} 100%)`;
                variables.set('--gradient-luxury', luxuryGradient);
                this.log(`🌈 LUXURY GRADIENT: --gradient-luxury = ${luxuryGradient}`);

                const freshGradient = `linear-gradient(135deg, ${colorValue} 0%, ${variables.get('--color-primary-teal') || colorValue} 100%)`;
                variables.set('--gradient-fresh', freshGradient);
                this.log(`🌈 FRESH GRADIENT: --gradient-fresh = ${freshGradient}`);
            }
        });

        // CRITICAL: Add overlay variables that theme uses (lines 54-57)
        if (paletteData.colors.surface) {
            const surfaceColor = paletteData.colors.surface.hex || paletteData.colors.surface;
            variables.set('--overlay-light', `rgba(${this.hexToRgb(surfaceColor)}, 0.95)`);
        }

        if (paletteData.colors.text) {
            const textColor = paletteData.colors.text.hex || paletteData.colors.text;
            variables.set('--overlay-dark', `rgba(${this.hexToRgb(textColor)}, 0.8)`);
        }

        if (paletteData.colors.secondary) {
            const secondaryColor = paletteData.colors.secondary.hex || paletteData.colors.secondary;
            variables.set('--overlay-teal', `rgba(${this.hexToRgb(secondaryColor)}, 0.1)`);
        }

        if (paletteData.colors.primary) {
            const primaryColor = paletteData.colors.primary.hex || paletteData.colors.primary;
            variables.set('--overlay-blue', `rgba(${this.hexToRgb(primaryColor)}, 0.1)`);
        }

        return variables;
    }

    /**
     * T5.3: Advanced color computation for derived colors
     */
    async computeDerivedColors(paletteData) {
        const computed = new Map();

        if (!paletteData.colors) return computed;

        // Generate hover states, focus states, and variants
        Object.entries(paletteData.colors).forEach(([role, colorData]) => {
            const baseColor = colorData.hex || colorData;

            // Hover states (darker)
            computed.set(`${this.options.cssVariablePrefix}${role}-hover`,
                this.adjustColorBrightness(baseColor, -0.1));

            // Focus states (lighter)
            computed.set(`${this.options.cssVariablePrefix}${role}-focus`,
                this.adjustColorBrightness(baseColor, 0.1));

            // Subtle variants
            computed.set(`${this.options.cssVariablePrefix}${role}-subtle`,
                this.adjustColorOpacity(baseColor, 0.1));
        });

        return computed;
    }

    /**
     * T5.1: CSS injection with efficient element targeting and MAXIMUM SPECIFICITY to override theme CSS
     */
    injectCSS(variables, computedColors, options = {}) {
        const injectionStart = performance.now();

        // CRITICAL FIX: Complete style cleanup before injection
        this.log('🧹 Clearing existing styles before new injection...');

        // 1. Clear our own style element completely
        if (this.styleElement) {
            this.styleElement.textContent = '';
            this.log('✅ Cleared existing styleElement content');
        }

        // 2. Remove ALL other preview/emergency styles
        const existingEmergencyStyles = document.querySelectorAll('style[id*="emergency"], style[id*="live-preview"], style[data-preview-system]');
        existingEmergencyStyles.forEach(style => {
            if (style !== this.styleElement) {
                style.remove();
                this.log(`🗑️ Removed external style: ${style.id}`);
            }
        });

        // 3. Force a clean DOM state
        document.body.offsetHeight; // Trigger reflow to ensure styles are cleared

        // Generate CSS with timestamp for cache busting
        const timestamp = Date.now();
        let css = `/* Live Preview System - Applied: ${new Date().toLocaleTimeString()} - Timestamp: ${timestamp} */\n`;

        // ENHANCED DEBUG: Log variables being applied
        this.log(`🎯 CSS Variables being applied (${variables.size} total):`);
        variables.forEach((value, name) => {
            this.log(`  ${name}: ${value}`);
        });

        // CRITICAL: Add MAXIMUM SPECIFICITY base variables first
        css += 'html body.live-preview-active {\n';

        // Add base variables with maximum specificity
        variables.forEach((value, name) => {
            css += `  ${name}: ${value} !important;\n`;
        });

        // Add computed colors with maximum specificity
        computedColors.forEach((value, name) => {
            css += `  ${name}: ${value} !important;\n`;
        });

        css += '}\n\n';

        // CRITICAL: Force :root variables with maximum specificity
        css += 'html body:root {\n';
        variables.forEach((value, name) => {
            css += `  ${name}: ${value} !important;\n`;
        });
        computedColors.forEach((value, name) => {
            css += `  ${name}: ${value} !important;\n`;
        });
        css += '}\n\n';

        // Add medical spa theme integration CSS with ULTRA HIGH SPECIFICITY
        css += this.generateThemeIntegrationCSS();

        // CRITICAL: Ensure style element exists and apply new content
        if (!this.styleElement) {
            this.log('⚠️ Style element missing, recreating...');
            this.setupCSSContainer();
        }

        // ENHANCED DEBUG: Log CSS length and key content
        this.log(`📝 Generated CSS length: ${css.length} characters`);
        this.log(`🔍 CSS preview (first 200 chars): ${css.substring(0, 200)}...`);

        // Apply new CSS content
        this.styleElement.textContent = css;
        this.styleElement.setAttribute('data-updated', timestamp);
        this.styleElement.setAttribute('data-palette', this.currentPalette?.name || 'unknown');

        // ULTRA CRITICAL: Force DOM reflow and style recalculation multiple times
        this.log('🔄 Forcing DOM reflows...');
        document.body.offsetHeight; // Trigger reflow

        // Force style recalculation on all affected elements
        const affectedElements = document.querySelectorAll('.site-header, .professional-header, .btn-consultation, .nav-menu, .main-navigation, .preview-element, .cta-button, .nav-item');
        affectedElements.forEach(element => {
            // Force recomputation by temporarily changing a style property
            const originalDisplay = element.style.display;
            element.style.display = 'none';
            element.offsetHeight; // Trigger reflow
            element.style.display = originalDisplay;
        });

        // Ensure preview mode class is active
        if (this.isPreviewMode || options.forcePreviewMode) {
            document.body.classList.add('live-preview-active');
            this.log('✅ Added live-preview-active class');
        }

        // FINAL: Force another reflow after class addition
        document.body.offsetHeight;

        // Enhanced logging for debugging
        const injectionDuration = performance.now() - injectionStart;
        this.log(`✅ CSS injected successfully at ${new Date().toLocaleTimeString()} (${injectionDuration.toFixed(2)}ms)`);
        this.log(`📊 Applied ${variables.size} variables and ${computedColors.size} computed colors`);
        this.log(`🎯 Forced reflow on ${affectedElements.length} elements`);
        this.log(`🏷️ Style element data-updated: ${timestamp}`);

        // Log some key variables for verification
        const keyVariables = ['--color-primary-navy', '--color-primary-teal', '--color-secondary-peach'];
        keyVariables.forEach(varName => {
            if (variables.has(varName)) {
                this.log(`🔍 ${varName}: ${variables.get(varName)}`);
            }
        });

        // ENHANCED DEBUG: Verify CSS was actually applied
        const verifyElement = document.querySelector('.preview-element');
        if (verifyElement) {
            const computedStyle = window.getComputedStyle(verifyElement);
            const actualBgColor = computedStyle.backgroundColor;
            this.log(`🔬 Verification - Preview element background: ${actualBgColor}`);
        }
    }

    /**
     * Medical spa theme integration CSS - ULTRA HIGH SPECIFICITY to override header-fix.css
     */
    generateThemeIntegrationCSS() {
        return `
/* Live Preview System - ULTRA HIGH SPECIFICITY - Override header-fix.css */
/* Fighting against rules like "html body.transparent-header .professional-header" */

/* MAXIMUM SPECIFICITY HEADER OVERRIDES */
html body.live-preview-active .site-header.professional-header,
html body.live-preview-active.transparent-header .professional-header,
html body.live-preview-active.has-hero-section .professional-header,
html body.live-preview-active.solid-header .professional-header,
html body.live-preview-active.no-hero-section .professional-header {
    background: var(--gradient-primary, var(--color-primary-navy, #2c3e50)) !important;
    background-color: var(--color-primary-navy, #2c3e50) !important;
    transition: all 0.3s ease !important;
    border-bottom-color: var(--color-primary-navy, #2c3e50) !important;
    box-shadow: 0 2px 12px rgba(var(--color-primary-navy-rgb, 44, 62, 80), 0.2) !important;
}

/* SCROLLED STATE - Higher specificity than header-fix.css */
html body.live-preview-active .professional-header.scrolled,
html body.live-preview-active.transparent-header .professional-header.scrolled,
html body.live-preview-active.has-hero-section .professional-header.scrolled {
    background: var(--color-primary-navy, #2c3e50) !important;
    background-color: var(--color-primary-navy, #2c3e50) !important;
    border-bottom-color: var(--color-primary-teal, #16a085) !important;
    backdrop-filter: blur(10px) !important;
}

/* SCROLL OPACITY OVERRIDES - Beat header-fix.css specificity */
html body.live-preview-active.transparent-header .professional-header.scroll-opacity-10,
html body.live-preview-active.transparent-header .professional-header.scroll-opacity-25,
html body.live-preview-active.transparent-header .professional-header.scroll-opacity-50,
html body.live-preview-active.transparent-header .professional-header.scroll-opacity-75,
html body.live-preview-active.transparent-header .professional-header.scroll-opacity-90,
html body.live-preview-active.transparent-header .professional-header.scroll-opacity-100,
html body.live-preview-active.has-hero-section .professional-header.scroll-opacity-10,
html body.live-preview-active.has-hero-section .professional-header.scroll-opacity-25,
html body.live-preview-active.has-hero-section .professional-header.scroll-opacity-50,
html body.live-preview-active.has-hero-section .professional-header.scroll-opacity-75,
html body.live-preview-active.has-hero-section .professional-header.scroll-opacity-90,
html body.live-preview-active.has-hero-section .professional-header.scroll-opacity-100 {
    background: var(--color-primary-navy, #2c3e50) !important;
    background-color: var(--color-primary-navy, #2c3e50) !important;
    border-bottom-color: var(--color-primary-teal, #16a085) !important;
}

/* SITE BRANDING - Ultra high specificity */
html body.live-preview-active .site-branding .site-title,
html body.live-preview-active .site-branding .site-title a,
html body.live-preview-active .site-header .site-title,
html body.live-preview-active .site-header .site-title a {
    color: var(--color-neutral-white, #ffffff) !important;
}

html body.live-preview-active .site-branding .site-title a:hover,
html body.live-preview-active .site-header .site-title a:hover {
    color: var(--color-primary-teal, #16a085) !important;
}

/* LOGO - Ultra high specificity */
html body.live-preview-active .logo-medical-cross {
    background: var(--gradient-primary, var(--color-primary-navy, #2c3e50)) !important;
    background-color: var(--color-primary-navy, #2c3e50) !important;
    color: var(--color-neutral-white, #ffffff) !important;
}

/* NAVIGATION - Ultra high specificity */
html body.live-preview-active .main-navigation .nav-menu .menu-item a,
html body.live-preview-active .site-header .main-navigation .nav-menu .menu-item a,
html body.live-preview-active .professional-header .main-navigation .nav-menu .menu-item a {
    color: var(--color-neutral-white, #ffffff) !important;
    transition: all 0.3s ease !important;
}

html body.live-preview-active .main-navigation .nav-menu .menu-item a:hover,
html body.live-preview-active .main-navigation .nav-menu .menu-item.current-menu-item a,
html body.live-preview-active .site-header .main-navigation .nav-menu .menu-item a:hover,
html body.live-preview-active .site-header .main-navigation .nav-menu .menu-item.current-menu-item a {
    color: var(--color-primary-teal, #16a085) !important;
    background: rgba(var(--color-primary-teal-rgb, 22, 160, 133), 0.15) !important;
}

/* HEADER ACTIONS - Ultra high specificity */
html body.live-preview-active .header-actions .btn-consultation,
html body.live-preview-active .site-header .header-actions .btn-consultation,
html body.live-preview-active .professional-header .header-actions .btn-consultation {
    background: var(--gradient-accent, var(--color-secondary-peach, #f39c12)) !important;
    background-color: var(--color-secondary-peach, #f39c12) !important;
    color: var(--color-neutral-white, #ffffff) !important;
    border-color: var(--color-secondary-peach, #f39c12) !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 12px rgba(var(--color-secondary-peach-rgb, 243, 156, 18), 0.3) !important;
}

html body.live-preview-active .header-actions .btn-consultation:hover,
html body.live-preview-active .site-header .header-actions .btn-consultation:hover,
html body.live-preview-active .professional-header .header-actions .btn-consultation:hover {
    background: var(--color-primary-teal, #16a085) !important;
    background-color: var(--color-primary-teal, #16a085) !important;
    border-color: var(--color-primary-teal, #16a085) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 25px rgba(var(--color-primary-teal-rgb, 22, 160, 133), 0.4) !important;
}

/* PHONE LINKS - Ultra high specificity */
html body.live-preview-active .header-phone .phone-link,
html body.live-preview-active .header-phone a,
html body.live-preview-active .site-header .header-phone .phone-link,
html body.live-preview-active .site-header .header-phone a {
    color: var(--color-primary-teal, #16a085) !important;
}

html body.live-preview-active .header-phone .phone-link:hover,
html body.live-preview-active .header-phone a:hover,
html body.live-preview-active .site-header .header-phone .phone-link:hover,
html body.live-preview-active .site-header .header-phone a:hover {
    color: var(--color-neutral-white, #ffffff) !important;
}

/* MOBILE MENU - Ultra high specificity */
html body.live-preview-active .mobile-menu-header,
html body.live-preview-active .mobile-nav-list a {
    color: var(--color-primary-navy, #2c3e50) !important;
}

html body.live-preview-active .mobile-nav-list a:hover,
html body.live-preview-active .mobile-nav-list .current a {
    color: var(--color-primary-teal, #16a085) !important;
    background: rgba(var(--color-primary-teal-rgb, 22, 160, 133), 0.1) !important;
}

/* HERO SECTIONS - Ultra high specificity */
html body.live-preview-active .modern-hero,
html body.live-preview-active .premium-hero,
html body.live-preview-active .hero-section {
    background: var(--gradient-primary, var(--color-primary-navy, #2c3e50)) !important;
    background-color: var(--color-primary-navy, #2c3e50) !important;
}

html body.live-preview-active .hero-title {
    color: var(--color-neutral-white, #ffffff) !important;
}

html body.live-preview-active .hero-subtitle {
    color: rgba(var(--color-neutral-white-rgb, 255, 255, 255), 0.9) !important;
}

/* CTA BUTTONS - Ultra high specificity */
html body.live-preview-active .cta-primary,
html body.live-preview-active .btn-primary,
html body.live-preview-active button.btn-primary {
    background: var(--gradient-accent, var(--color-secondary-peach, #f39c12)) !important;
    background-color: var(--color-secondary-peach, #f39c12) !important;
    color: var(--color-neutral-white, #ffffff) !important;
    border-color: var(--color-secondary-peach, #f39c12) !important;
}

html body.live-preview-active .cta-primary:hover,
html body.live-preview-active .btn-primary:hover,
html body.live-preview-active button.btn-primary:hover {
    background: var(--color-primary-teal, #16a085) !important;
    background-color: var(--color-primary-teal, #16a085) !important;
    border-color: var(--color-primary-teal, #16a085) !important;
}

html body.live-preview-active .cta-secondary,
html body.live-preview-active .btn-secondary,
html body.live-preview-active button.btn-secondary {
    background: var(--color-primary-teal, #16a085) !important;
    background-color: var(--color-primary-teal, #16a085) !important;
    color: var(--color-neutral-white, #ffffff) !important;
    border-color: var(--color-primary-teal, #16a085) !important;
}

html body.live-preview-active .cta-secondary:hover,
html body.live-preview-active .btn-secondary:hover,
html body.live-preview-active button.btn-secondary:hover {
    background: var(--color-primary-navy, #2c3e50) !important;
    background-color: var(--color-primary-navy, #2c3e50) !important;
    border-color: var(--color-primary-navy, #2c3e50) !important;
}

/* TREATMENT CARDS - Ultra high specificity */
html body.live-preview-active .treatment-card,
html body.live-preview-active .modern-card {
    background: var(--color-neutral-white, #ffffff) !important;
    background-color: var(--color-neutral-white, #ffffff) !important;
    border-color: var(--color-neutral-light, #ecf0f1) !important;
    transition: all 0.3s ease !important;
}

html body.live-preview-active .treatment-card:hover,
html body.live-preview-active .modern-card:hover {
    box-shadow: 0 8px 25px rgba(var(--color-primary-teal-rgb, 22, 160, 133), 0.15) !important;
    border-color: var(--color-primary-teal, #16a085) !important;
}

/* TEXT AND LINKS - Ultra high specificity */
html body.live-preview-active .treatment-title,
html body.live-preview-active .treatment-title a,
html body.live-preview-active .section-title,
html body.live-preview-active h1,
html body.live-preview-active h2,
html body.live-preview-active h3,
html body.live-preview-active h4,
html body.live-preview-active h5,
html body.live-preview-active h6 {
    color: var(--color-primary-navy, #2c3e50) !important;
}

html body.live-preview-active .treatment-title a:hover,
html body.live-preview-active a:hover {
    color: var(--color-primary-teal, #16a085) !important;
}

html body.live-preview-active .treatment-description,
html body.live-preview-active .section-subtitle,
html body.live-preview-active p {
    color: var(--color-neutral-dark, #34495e) !important;
}

html body.live-preview-active a {
    color: var(--color-primary-teal, #16a085) !important;
}

/* BADGES AND CATEGORIES - Ultra high specificity */
html body.live-preview-active .treatment-badge,
html body.live-preview-active .treatment-category {
    background: var(--color-primary-teal, #16a085) !important;
    background-color: var(--color-primary-teal, #16a085) !important;
    color: var(--color-neutral-white, #ffffff) !important;
}

html body.live-preview-active .treatment-badge.popular {
    background: var(--gradient-accent, var(--color-secondary-peach, #f39c12)) !important;
    background-color: var(--color-secondary-peach, #f39c12) !important;
}

/* FORMS - Ultra high specificity */
html body.live-preview-active input.form-control:focus,
html body.live-preview-active textarea.form-control:focus,
html body.live-preview-active select.form-control:focus {
    border-color: var(--color-primary-teal, #16a085) !important;
    box-shadow: 0 0 0 3px rgba(var(--color-primary-teal-rgb, 22, 160, 133), 0.1) !important;
}

/* SECTION BACKGROUNDS - Ultra high specificity */
html body.live-preview-active .bg-primary {
    background: var(--color-primary-navy, #2c3e50) !important;
    background-color: var(--color-primary-navy, #2c3e50) !important;
}

html body.live-preview-active .bg-soft {
    background: var(--color-neutral-light, #ecf0f1) !important;
    background-color: var(--color-neutral-light, #ecf0f1) !important;
}

/* FOOTER - Ultra high specificity */
html body.live-preview-active .luxury-footer,
html body.live-preview-active .site-footer {
    background: var(--color-primary-navy, #2c3e50) !important;
    background-color: var(--color-primary-navy, #2c3e50) !important;
    color: var(--color-neutral-white, #ffffff) !important;
}

html body.live-preview-active .luxury-footer a,
html body.live-preview-active .site-footer a {
    color: var(--color-primary-teal, #16a085) !important;
}

html body.live-preview-active .luxury-footer a:hover,
html body.live-preview-active .site-footer a:hover {
    color: var(--color-secondary-peach, #f39c12) !important;
}

/* PREVIEW MODE INDICATOR with theme colors */
html body.live-preview-active::before {
    content: "🎨 Live Preview Active - ULTRA HIGH SPECIFICITY";
    position: fixed;
    top: 10px;
    right: 10px;
    background: var(--color-primary-navy, #2c3e50);
    color: var(--color-neutral-white, #ffffff);
    padding: 8px 12px;
    border-radius: 4px;
    font-size: 12px;
    z-index: 999999;
    animation: pulse 2s infinite;
    border: 2px solid var(--color-primary-teal, #16a085);
    box-shadow: 0 4px 12px rgba(var(--color-primary-navy-rgb, 44, 62, 80), 0.3);
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

/* FORCE UPDATE: Immediate variable application with MAXIMUM specificity */
html body.live-preview-active:root {
    /* Ensure core variables exist with current palette values */
    --color-primary-navy: var(--color-primary-navy, #2c3e50) !important;
    --color-primary-teal: var(--color-primary-teal, #16a085) !important;
    --color-secondary-peach: var(--color-secondary-peach, #f39c12) !important;
    --color-neutral-white: var(--color-neutral-white, #ffffff) !important;
    --color-neutral-light: var(--color-neutral-light, #ecf0f1) !important;
    --color-neutral-dark: var(--color-neutral-dark, #34495e) !important;

    /* RGB versions for transparency effects */
    --color-primary-navy-rgb: var(--color-primary-navy-rgb, 44, 62, 80);
    --color-primary-teal-rgb: var(--color-primary-teal-rgb, 22, 160, 133);
    --color-secondary-peach-rgb: var(--color-secondary-peach-rgb, 243, 156, 18);
    --color-neutral-white-rgb: var(--color-neutral-white-rgb, 255, 255, 255);

    /* Gradients */
    --gradient-primary: var(--gradient-primary, linear-gradient(135deg, var(--color-primary-teal) 0%, var(--color-primary-navy) 100%));
    --gradient-accent: var(--gradient-accent, linear-gradient(135deg, var(--color-secondary-peach) 0%, var(--color-accent-coral, #e74c3c) 100%));
}
        `;
    }

    /**
     * T5.2: Handle preview messages from parent frame
     */
    handlePreviewMessage(event) {
        if (event.origin !== window.location.origin &&
            !this.isTrustedOrigin(event.origin)) {
            return;
        }

        const { type, data } = event.data;

        switch (type) {
            case 'preview:applyPalette':
                this.applyPalette(data.palette);
                break;
            case 'preview:toggleMode':
                this.togglePreviewMode(data.enabled);
                break;
            case 'preview:reset':
                this.resetPreview();
                break;
        }
    }

    /**
     * Handle palette change events
     */
    handlePaletteChange(detail) {
        const { paletteId, paletteData } = detail;
        this.log(`📡 Palette change received: ${paletteId}`);

        if (paletteData) {
            this.applyPalette(paletteData);
        }
    }

    /**
     * Toggle preview mode
     */
    togglePreviewMode(enabled = null) {
        this.isPreviewMode = enabled !== null ? enabled : !this.isPreviewMode;

        if (this.isPreviewMode) {
            this.enablePreviewMode();
        } else {
            this.disablePreviewMode();
        }
    }

    /**
     * Enable preview mode
     */
    enablePreviewMode() {
        this.isPreviewMode = true;
        document.body.classList.add('live-preview-active');

        this.dispatchEvent('preview:modeEnabled', {
            timestamp: performance.now()
        });

        this.log('🎨 Preview mode: ENABLED');
    }

    /**
     * Disable preview mode
     */
    disablePreviewMode() {
        this.isPreviewMode = false;
        document.body.classList.remove('live-preview-active');

        this.dispatchEvent('preview:modeDisabled', {
            timestamp: performance.now()
        });

        this.log('🎨 Preview mode: DISABLED');
    }

    /**
     * Reset preview to original state with complete cleanup
     */
    resetPreview() {
        // Remove ALL preview and emergency styles
        const allPreviewStyles = document.querySelectorAll('style[data-preview-system], style[id*="preview"], style[id*="emergency"], style[id*="live-preview"]');
        allPreviewStyles.forEach(style => style.remove());

        // Clear our style element
        if (this.styleElement) {
            this.styleElement.textContent = '';
            this.styleElement.remove();
            this.styleElement = null;
        }

        // Recreate clean style container
        this.setupCSSContainer();

        // Clean up state
        this.disablePreviewMode();
        this.currentPalette = null;

        // Force DOM reflow to ensure changes take effect
        document.body.offsetHeight;

        this.log('🔄 Preview reset - all styles cleared');

        // Dispatch reset event
        this.dispatchEvent('preview:reset', {
            timestamp: performance.now()
        });
    }

    /**
     * Color utility functions
     */
    hexToRgb(hex) {
        const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        if (!result) return '0, 0, 0';

        const r = parseInt(result[1], 16);
        const g = parseInt(result[2], 16);
        const b = parseInt(result[3], 16);
        return `${r}, ${g}, ${b}`;
    }

    adjustColorBrightness(hex, amount) {
        const col = parseInt(hex.slice(1), 16);
        const amt = Math.round(2.55 * amount * 100);
        const R = (col >> 16) + amt;
        const G = (col >> 8 & 0x00FF) + amt;
        const B = (col & 0x0000FF) + amt;
        return "#" + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
            (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
            (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
    }

    adjustColorOpacity(hex, opacity) {
        const rgb = this.hexToRgb(hex);
        return `rgba(${rgb}, ${opacity})`;
    }

    /**
     * Utility functions
     */
    isTrustedOrigin(origin) {
        const trustedOrigins = [
            window.location.origin,
            'http://localhost',
            'https://localhost'
        ];
        return trustedOrigins.some(trusted => origin.startsWith(trusted));
    }

    dispatchEvent(eventName, detail) {
        document.dispatchEvent(new CustomEvent(eventName, { detail }));
    }

    handleError(message, error) {
        this.log(`❌ ${message}:`, error);

        this.dispatchEvent('preview:error', {
            message,
            error: error.message,
            stack: error.stack
        });
    }

    log(...args) {
        if (this.options.enableDebug) {
            console.log('[LivePreviewSystem]', ...args);
        }
    }

    /**
     * Get system status for monitoring
     */
    getStatus() {
        return {
            isPreviewMode: this.isPreviewMode,
            currentPalette: this.currentPalette?.name || null,
            performance: this.performanceMonitor.getMetrics(),
            isWordPressIntegrated: typeof wp !== 'undefined'
        };
    }
}

/**
 * Performance Monitor for preview updates
 */
class PerformanceMonitor {
    constructor() {
        this.updateTimes = [];
        this.maxSamples = 50;
        this.startTime = null;
    }

    start() {
        this.startTime = performance.now();
    }

    recordUpdate(duration) {
        this.updateTimes.push(duration);

        // Keep only recent samples
        if (this.updateTimes.length > this.maxSamples) {
            this.updateTimes.shift();
        }
    }

    getMetrics() {
        if (this.updateTimes.length === 0) {
            return {
                avgUpdateTime: 0,
                maxUpdateTime: 0,
                minUpdateTime: 0,
                totalUpdates: 0
            };
        }

        return {
            avgUpdateTime: this.updateTimes.reduce((a, b) => a + b) / this.updateTimes.length,
            maxUpdateTime: Math.max(...this.updateTimes),
            minUpdateTime: Math.min(...this.updateTimes),
            totalUpdates: this.updateTimes.length
        };
    }

    handleVisibilityChange() {
        if (document.hidden) {
            // Page hidden - pause monitoring
        } else {
            // Page visible - resume monitoring
        }
    }
}

// Export for both module and global usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = LivePreviewSystem;
} else {
    window.LivePreviewSystem = LivePreviewSystem;
}
