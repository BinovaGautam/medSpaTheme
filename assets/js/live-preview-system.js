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
        // Remove existing preview styles
        const existingStyle = document.getElementById('live-preview-styles');
        if (existingStyle) {
            existingStyle.remove();
        }

        // Create new style container
        this.styleElement = document.createElement('style');
        this.styleElement.id = 'live-preview-styles';
        this.styleElement.setAttribute('data-preview-system', 'live-preview-system');
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
            this.log(`🎨 Applying palette: ${paletteData.name}`);

            // Store current palette
            this.currentPalette = paletteData;

            // Generate CSS variables
            const cssVariables = this.generateCSSVariables(paletteData);

            // T5.3: Color computation for derived colors
            const computedColors = await this.computeDerivedColors(paletteData);

            // Apply CSS injection
            this.injectCSS(cssVariables, computedColors, options);

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

        // Map semantic color roles to ACTUAL theme variables - SURGICAL FIX
        const actualThemeMapping = {
            'primary': '--color-primary-navy',        // Primary brand -> actual navy variable
            'secondary': '--color-primary-teal',      // Secondary -> actual teal variable
            'accent': '--color-secondary-peach',      // Accent -> actual peach for gradients
            'surface': '--color-neutral-white',       // Surface -> actual white variable
            'background': '--color-soft-cream',       // Background -> actual cream variable
            'text': '--color-charcoal'                // Text -> actual charcoal variable
        };

        // Map to gradient variables that the theme actually uses
        const gradientMapping = {
            'primary': '--gradient-primary',          // Main gradient used in header
            'accent': '--gradient-accent',            // Accent gradient used in buttons
            'luxury': '--gradient-luxury',            // Luxury gradient option
            'fresh': '--gradient-fresh'               // Fresh gradient option
        };

        // Generate mapped variables for actual theme system
        Object.entries(paletteData.colors).forEach(([role, colorData]) => {
            const colorValue = colorData.hex || colorData;

            // Map to actual theme variables
            const themeVar = actualThemeMapping[role];
            if (themeVar) {
                variables.set(themeVar, colorValue);
                variables.set(`${themeVar}-rgb`, this.hexToRgb(colorValue));
                this.log(`🎨 SURGICAL FIX: Mapping ${role} (${colorValue}) -> ${themeVar}`);
            }

            // Map to gradient variables if applicable
            if (role === 'primary' || role === 'accent') {
                const gradientVar = gradientMapping[role];
                if (gradientVar && role === 'primary') {
                    // Create gradient from primary color variations
                    variables.set(gradientVar,
                        `linear-gradient(135deg, ${colorValue} 0%, ${this.adjustColorBrightness(colorValue, -0.2)} 100%)`);
                } else if (gradientVar && role === 'accent') {
                    // Create gradient from accent color variations
                    variables.set(gradientVar,
                        `linear-gradient(135deg, ${colorValue} 0%, ${this.adjustColorBrightness(colorValue, 0.1)} 100%)`);
                }
            }
        });

        // Add legacy support for any remaining references
        Object.entries(paletteData.colors).forEach(([role, colorData]) => {
            const colorValue = colorData.hex || colorData;

            // Legacy traditional fallbacks
            if (role === 'primary') {
                variables.set('--color-primary', colorValue);
                variables.set('--color-primary-rgb', this.hexToRgb(colorValue));
            }
            if (role === 'secondary') {
                variables.set('--color-secondary', colorValue);
                variables.set('--color-secondary-rgb', this.hexToRgb(colorValue));
            }
            if (role === 'accent') {
                variables.set('--color-accent', colorValue);
                variables.set('--color-accent-rgb', this.hexToRgb(colorValue));
            }
        });

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
     * T5.1: CSS injection with efficient element targeting
     */
    injectCSS(variables, computedColors, options = {}) {
        let css = ':root {\n';

        // Add base variables
        variables.forEach((value, name) => {
            css += `  ${name}: ${value};\n`;
        });

        // Add computed colors
        computedColors.forEach((value, name) => {
            css += `  ${name}: ${value};\n`;
        });

        css += '}\n\n';

        // Add medical spa theme integration CSS
        css += this.generateThemeIntegrationCSS();

        // Apply to style element
        this.styleElement.textContent = css;

        // Add preview mode indicator if needed
        if (this.isPreviewMode) {
            document.body.classList.add('live-preview-active');
        }
    }

    /**
     * Medical spa theme integration CSS - SURGICAL FIX: Using ACTUAL theme variables and classes
     */
    generateThemeIntegrationCSS() {
        return `
/* Live Preview System - SURGICAL FIX - Medical Spa Theme Integration */

/* Professional Header - REAL CLASS with ACTUAL VARIABLES */
.professional-header {
    background-color: var(--color-primary-navy) !important;
    background: var(--gradient-primary) !important;
    transition: background-color 0.3s ease !important;
}

.professional-header.scrolled {
    background: rgba(var(--color-primary-navy-rgb), 0.95) !important;
    background-color: var(--color-primary-navy) !important;
    backdrop-filter: blur(10px) !important;
}

/* Navigation Menu - REAL CLASS with ACTUAL VARIABLES */
.nav-menu .menu-item a,
.site-title a,
.logo-text {
    color: var(--color-neutral-white) !important;
    text-shadow: 0 1px 3px rgba(0,0,0,0.3) !important;
}

.nav-menu .menu-item a:hover,
.nav-menu .menu-item.current-menu-item a {
    color: var(--color-primary-teal) !important;
    background: rgba(var(--color-primary-teal-rgb), 0.1) !important;
}

/* Header Actions and CTA - REAL CLASS with ACTUAL VARIABLES */
.btn-consultation {
    background: var(--gradient-accent) !important;
    border-color: var(--color-secondary-peach) !important;
    color: var(--color-neutral-white) !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 12px rgba(var(--color-secondary-peach-rgb), 0.3) !important;
}

.btn-consultation:hover {
    background: var(--color-primary-teal) !important;
    border-color: var(--color-primary-teal) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 25px rgba(var(--color-primary-teal-rgb), 0.4) !important;
}

/* Phone Links - ACTUAL VARIABLES */
.phone-link,
.mobile-phone-btn {
    color: var(--color-primary-teal) !important;
}

.phone-link:hover,
.mobile-phone-btn:hover {
    color: var(--color-primary-navy) !important;
}

/* Site Branding - ACTUAL VARIABLES */
.site-title,
.site-title a {
    color: var(--color-primary-navy) !important;
}

.logo-medical-cross {
    background: var(--gradient-primary) !important;
    color: var(--color-neutral-white) !important;
}

/* Mobile Menu Elements - ACTUAL VARIABLES */
.mobile-consultation-btn {
    background: var(--gradient-accent) !important;
    color: var(--color-neutral-white) !important;
}

.mobile-consultation-btn:hover {
    background: var(--color-primary-teal) !important;
}

/* Mobile Navigation - ACTUAL VARIABLES */
.mobile-nav-list a {
    color: var(--color-primary-navy) !important;
}

.mobile-nav-list a:hover,
.mobile-nav-list .current a {
    color: var(--color-primary-teal) !important;
}

/* General Button Styles - ACTUAL VARIABLES */
button,
.button,
input[type="submit"] {
    background: var(--gradient-accent) !important;
    color: var(--color-neutral-white) !important;
    border-color: var(--color-secondary-peach) !important;
}

button:hover,
.button:hover,
input[type="submit"]:hover {
    background: var(--color-primary-teal) !important;
    border-color: var(--color-primary-teal) !important;
}

/* Footer Elements - ACTUAL VARIABLES */
.luxury-footer {
    background: var(--color-primary-navy) !important;
    color: var(--color-neutral-white) !important;
}

.cta-primary {
    background: var(--gradient-accent) !important;
    color: var(--color-neutral-white) !important;
}

.cta-secondary {
    background: var(--color-primary-teal) !important;
    color: var(--color-neutral-white) !important;
}

/* Article and Content Elements - ACTUAL VARIABLES */
article {
    background-color: var(--color-neutral-white) !important;
    border-color: var(--color-primary-teal) !important;
}

/* Form Elements - ACTUAL VARIABLES */
input:focus,
textarea:focus,
select:focus {
    border-color: var(--color-primary-teal) !important;
    box-shadow: 0 0 0 3px rgba(var(--color-primary-teal-rgb), 0.1) !important;
}

/* Text Colors - ACTUAL VARIABLES */
.text-primary,
h1, h2, h3, h4, h5, h6 {
    color: var(--color-primary-navy) !important;
}

/* Preview mode indicator with actual theme colors */
body.live-preview-active::before {
    content: "🎨 Live Preview Active - SURGICAL FIX";
    position: fixed;
    top: 10px;
    right: 10px;
    background: var(--color-primary-navy);
    color: var(--color-neutral-white);
    padding: 8px 12px;
    border-radius: 4px;
    font-size: 12px;
    z-index: 999999;
    animation: pulse 2s infinite;
    border: 2px solid var(--color-primary-teal);
    box-shadow: 0 4px 12px rgba(var(--color-primary-navy-rgb), 0.3);
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

/* High specificity for live preview changes - TARGET ACTUAL CLASSES */
body.live-preview-active .professional-header,
body.live-preview-active .btn-consultation,
body.live-preview-active .nav-menu,
body.live-preview-active .luxury-footer,
body.live-preview-active article,
body.live-preview-active button {
    transition: all 0.3s ease !important;
}

/* Immediate color variable overrides - SURGICAL FIX */
:root {
    /* Ensure variables exist with fallbacks to theme defaults */
    --color-primary-navy: var(--color-primary-navy, #2c3e50);
    --color-primary-teal: var(--color-primary-teal, #16a085);
    --color-secondary-peach: var(--color-secondary-peach, #f39c12);
    --color-neutral-white: var(--color-neutral-white, #ffffff);
    --color-soft-cream: var(--color-soft-cream, #fefcf8);
    --color-charcoal: var(--color-charcoal, #2c2c2c);

    /* RGB versions for transparency effects */
    --color-primary-navy-rgb: 44, 62, 80;
    --color-primary-teal-rgb: 22, 160, 133;
    --color-secondary-peach-rgb: 243, 156, 18;
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
     * Reset preview to original state
     */
    resetPreview() {
        this.styleElement.textContent = '';
        this.disablePreviewMode();
        this.currentPalette = null;

        this.log('🔄 Preview reset');
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
