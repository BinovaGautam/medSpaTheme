/**
 * Visual Customizer System - Elementor Style (Admin Only)
 * Medical Spa Theme Customizer with Educational Interface
 * Version 2.0 - WordPress Admin Bar Integration
 */

class ElementorStyleCustomizer {
    constructor() {
        console.log('ElementorStyleCustomizer: Initializing Admin-Only Version 2.0...');

        // Check admin access
        this.isAdmin = visualCustomizerData?.isAdmin || false;
        if (!this.isAdmin) {
            console.log('ElementorStyleCustomizer: Access denied - admin privileges required');
            return;
        }

        // Configuration
        this.config = {
            panelWidth: visualCustomizerData?.settings?.panelWidth || 320,
            animationDuration: visualCustomizerData?.settings?.animationDuration || 300,
            mobileBreakpoint: visualCustomizerData?.settings?.mobileBreakpoint || 768,
            slideDirection: visualCustomizerData?.settings?.slideDirection || 'right'
        };

        // Current settings
        this.settings = {
            colorPalette: 'classic-forest',
            fontHeading: 'playfair-display',
            fontBody: 'inter',
            fontSize: 'normal',
            headerStyle: 'transparent',
            buttonStyle: 'rounded'
        };

        // Color palettes from localized data
        this.colorPalettes = visualCustomizerData?.colorPalettes || {};
        this.fontOptions = visualCustomizerData?.fontOptions || {};
        this.tooltips = visualCustomizerData?.tooltips || {};
        this.contrastPairs = visualCustomizerData?.contrastPairs || {};

        // UI state
        this.isOpen = false;
        this.currentSection = 'colorScheme';
        this.globalConfig = visualCustomizerData?.globalConfig || {};

        // Initialize
        this.init();
    }

    /**
     * Initialize the customizer system
     */
    init() {
        // Wait for DOM and contrast calculator
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }

    /**
     * Setup the customizer interface
     */
    setup() {
        console.log('ElementorStyleCustomizer: Setting up interface...');

        this.createPanel();
        this.bindEvents();
        this.loadGlobalConfiguration();
        this.setupKeyboardNavigation();
        this.setupAccessibilityFeatures();

        // Announce to WordPress admin bar integration
        window.openThemeCustomizer = () => this.openPanel();

        console.log('ElementorStyleCustomizer: Setup complete');
    }

    /**
     * Create the main customizer panel
     */
    createPanel() {
        // Remove any existing panel
        const existingPanel = document.getElementById('elementor-style-customizer');
        if (existingPanel) {
            existingPanel.remove();
        }

        // Create panel HTML
        const panelHTML = this.generatePanelHTML();

        // Insert into DOM
        document.body.insertAdjacentHTML('beforeend', panelHTML);

        // Get panel reference
        this.panel = document.getElementById('elementor-style-customizer');
        this.backdrop = document.getElementById('customizer-backdrop');

        console.log('ElementorStyleCustomizer: Panel created');
    }

    /**
     * Generate the complete panel HTML structure
     */
    generatePanelHTML() {
        return `
        <!-- Customizer Backdrop -->
        <div id="customizer-backdrop" class="customizer-backdrop" aria-hidden="true"></div>

        <!-- Main Customizer Panel -->
        <aside id="elementor-style-customizer"
               class="elementor-customizer-panel"
               role="dialog"
               aria-modal="true"
               aria-labelledby="customizer-title"
               aria-hidden="true">

            <!-- Panel Header -->
            <header class="customizer-header">
                <h2 id="customizer-title" class="customizer-title">
                    <span class="customizer-icon">🎨</span>
                    ${visualCustomizerData.i18n.customizeTheme}
                </h2>
                <p class="customizer-subtitle">
                    ${visualCustomizerData.i18n.adminOnlyNotice}
                </p>
                <button type="button"
                        class="customizer-close-btn"
                        aria-label="Close customizer"
                        data-action="close">
                    <span aria-hidden="true">×</span>
                </button>
            </header>

            <!-- Panel Content -->
            <div class="customizer-content">

                <!-- Section: Color Scheme -->
                <section class="customizer-section active" data-section="colorScheme">
                    <h3 class="section-title">
                        <span class="section-icon">🎨</span>
                        ${visualCustomizerData.i18n.colorScheme}
                    </h3>

                    <div class="section-content">
                        <div class="educational-intro">
                            <p>Choose colors that create the perfect medical spa atmosphere. Each color has been carefully selected for professionalism and trust.</p>
                        </div>

                        <div class="color-system">
                            ${this.generateColorSystemHTML()}
                        </div>

                        <div class="smart-combinations">
                            <h4 class="subsection-title">
                                <span class="subsection-icon">🧠</span>
                                ${visualCustomizerData.i18n.smartCombinations}
                            </h4>
                            ${this.generateContrastPairsHTML()}
                        </div>
                    </div>
                </section>

                <!-- Section: Typography -->
                <section class="customizer-section" data-section="typography">
                    <h3 class="section-title">
                        <span class="section-icon">📝</span>
                        ${visualCustomizerData.i18n.typography}
                    </h3>

                    <div class="section-content">
                        ${this.generateTypographyHTML()}
                    </div>
                </section>

                <!-- Section: Layout Controls -->
                <section class="customizer-section" data-section="layoutControls">
                    <h3 class="section-title">
                        <span class="section-icon">📐</span>
                        ${visualCustomizerData.i18n.layoutControls}
                    </h3>

                    <div class="section-content">
                        ${this.generateLayoutControlsHTML()}
                    </div>
                </section>

                <!-- Section: Live Preview -->
                <section class="customizer-section" data-section="livePreview">
                    <h3 class="section-title">
                        <span class="section-icon">👁️</span>
                        ${visualCustomizerData.i18n.livePreview}
                    </h3>

                    <div class="section-content">
                        ${this.generateLivePreviewHTML()}
                    </div>
                </section>

            </div>

            <!-- Panel Footer -->
            <footer class="customizer-footer">
                <button type="button"
                        class="apply-globally-btn primary-btn"
                        data-action="apply-globally">
                    <span class="btn-icon">🌍</span>
                    ${visualCustomizerData.i18n.applyGlobally}
                </button>

                <div class="footer-info">
                    <small>Changes are visible to you only until applied globally</small>
                </div>
            </footer>

        </aside>

        <!-- Accessibility Announcements -->
        <div id="customizer-announcements"
             class="sr-only"
             aria-live="polite"
             aria-atomic="true">
        </div>
        `;
    }

    /**
     * Generate educational color system HTML
     */
    generateColorSystemHTML() {
        const colors = this.colorPalettes['classic-forest']?.colors || {};

        return `
        <div class="color-squares-container">
            ${Object.entries(colors).map(([key, color]) => {
                const tooltip = this.tooltips[key] || {};
                return `
                <div class="color-square-wrapper" data-color-key="${key}">
                    <div class="color-square"
                         style="background-color: ${color}"
                         data-color="${color}"
                         role="button"
                         tabindex="0"
                         aria-label="${tooltip.title || key}"
                         data-tooltip-key="${key}">
                    </div>
                    <span class="color-label">${tooltip.title || key}</span>
                </div>
                `;
            }).join('')}
        </div>

        <!-- Tooltip Container -->
        <div id="color-tooltip" class="educational-tooltip" role="tooltip">
            <div class="tooltip-content">
                <h4 class="tooltip-title"></h4>
                <div class="tooltip-usage">
                    <h5>Perfect for:</h5>
                    <ul class="usage-list"></ul>
                </div>
                <div class="tooltip-visibility">
                    <h5>Visibility:</h5>
                    <p class="visibility-text"></p>
                </div>
                <div class="tooltip-purpose">
                    <h5>Purpose:</h5>
                    <p class="purpose-text"></p>
                </div>
            </div>
        </div>
        `;
    }

    /**
     * Generate contrast pairs HTML
     */
    generateContrastPairsHTML() {
        return `
        <div class="contrast-pairs">
            ${Object.entries(this.contrastPairs).map(([key, pair]) => `
                <div class="contrast-pair" data-pair-key="${key}">
                    <div class="contrast-demo"
                         style="background: ${pair.background}; color: ${pair.text}">
                        <span class="demo-text">${pair.label}</span>
                        <span class="contrast-ratio">${pair.ratio}</span>
                    </div>
                    <p class="contrast-description">
                        ${this.getContrastDescription(pair.ratio)}
                    </p>
                </div>
            `).join('')}
        </div>
        `;
    }

    /**
     * Generate typography controls HTML
     */
    generateTypographyHTML() {
        return `
        <div class="typography-controls">
            <div class="font-selector">
                <label for="heading-font-select">${visualCustomizerData.i18n.headingFont}</label>
                <select id="heading-font-select" data-setting="fontHeading">
                    ${Object.entries(this.fontOptions).map(([key, font]) => `
                        <option value="${key}">${font.name}</option>
                    `).join('')}
                </select>
            </div>

            <div class="font-selector">
                <label for="body-font-select">${visualCustomizerData.i18n.bodyFont}</label>
                <select id="body-font-select" data-setting="fontBody">
                    ${Object.entries(this.fontOptions).map(([key, font]) => `
                        <option value="${key}">${font.name}</option>
                    `).join('')}
                </select>
            </div>

            <div class="font-size-controls">
                <label>${visualCustomizerData.i18n.fontSize}</label>
                <div class="size-options">
                    <button type="button" class="size-option" data-size="small">Small</button>
                    <button type="button" class="size-option active" data-size="normal">Normal</button>
                    <button type="button" class="size-option" data-size="large">Large</button>
                </div>
            </div>
        </div>
        `;
    }

    /**
     * Generate layout controls HTML
     */
    generateLayoutControlsHTML() {
        return `
        <div class="layout-controls">
            <div class="control-group">
                <label>${visualCustomizerData.i18n.headerStyle}</label>
                <div class="style-options">
                    <button type="button" class="style-option active" data-style="transparent">Transparent</button>
                    <button type="button" class="style-option" data-style="solid">Solid</button>
                </div>
            </div>

            <div class="control-group">
                <label>${visualCustomizerData.i18n.buttonStyle}</label>
                <div class="style-options">
                    <button type="button" class="style-option active" data-style="rounded">Rounded</button>
                    <button type="button" class="style-option" data-style="square">Square</button>
                </div>
            </div>
        </div>
        `;
    }

    /**
     * Generate live preview HTML
     */
    generateLivePreviewHTML() {
        return `
        <div class="live-preview-container">
            <div class="preview-components">
                <div class="preview-component" data-component="button">
                    <h4>Button Preview</h4>
                    <button class="preview-btn">Book Consultation</button>
                </div>

                <div class="preview-component" data-component="card">
                    <h4>Card Preview</h4>
                    <div class="preview-card">
                        <h5>Facial Treatment</h5>
                        <p>Rejuvenating facial therapy...</p>
                    </div>
                </div>

                <div class="preview-component" data-component="nav">
                    <h4>Navigation Preview</h4>
                    <nav class="preview-nav">
                        <a href="#">Home</a>
                        <a href="#">Treatments</a>
                        <a href="#">About</a>
                    </nav>
                </div>
            </div>
        </div>
        `;
    }

    /**
     * Bind all event listeners
     */
    bindEvents() {
        // Close button
        this.panel?.addEventListener('click', (e) => {
            if (e.target.closest('[data-action="close"]')) {
                this.closePanel();
            }
        });

        // Backdrop click
        this.backdrop?.addEventListener('click', () => {
            this.closePanel();
        });

        // Color square interactions
        this.panel?.addEventListener('click', (e) => {
            const colorSquare = e.target.closest('.color-square');
            if (colorSquare) {
                this.handleColorSelection(colorSquare);
            }
        });

        // Color square hover for tooltips
        this.panel?.addEventListener('mouseenter', (e) => {
            const colorSquare = e.target.closest('.color-square');
            if (colorSquare) {
                this.showTooltip(colorSquare);
            }
        }, true);

        this.panel?.addEventListener('mouseleave', (e) => {
            const colorSquare = e.target.closest('.color-square');
            if (colorSquare) {
                this.hideTooltip();
            }
        }, true);

        // Apply globally button
        this.panel?.addEventListener('click', (e) => {
            if (e.target.closest('[data-action="apply-globally"]')) {
                this.applyConfigurationGlobally();
            }
        });

        // Settings changes
        this.panel?.addEventListener('change', (e) => {
            if (e.target.dataset.setting) {
                this.updateSetting(e.target.dataset.setting, e.target.value);
            }
        });

        // Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.closePanel();
            }
        });

        console.log('ElementorStyleCustomizer: Events bound');
    }

    /**
     * Open the customizer panel
     */
    openPanel() {
        console.log('ElementorStyleCustomizer: Opening panel...');

        this.isOpen = true;
        this.panel.classList.add('open');
        this.backdrop.classList.add('visible');

        // Update ARIA
        this.panel.setAttribute('aria-hidden', 'false');
        this.backdrop.setAttribute('aria-hidden', 'false');

        // Focus management
        const firstFocusable = this.panel.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (firstFocusable) {
            firstFocusable.focus();
        }

        // Announce to screen readers
        this.announceToScreenReader('Theme customizer opened');

        console.log('ElementorStyleCustomizer: Panel opened');
    }

    /**
     * Close the customizer panel
     */
    closePanel() {
        console.log('ElementorStyleCustomizer: Closing panel...');

        this.isOpen = false;
        this.panel.classList.remove('open');
        this.backdrop.classList.remove('visible');

        // Update ARIA
        this.panel.setAttribute('aria-hidden', 'true');
        this.backdrop.setAttribute('aria-hidden', 'true');

        // Hide tooltip
        this.hideTooltip();

        // Announce to screen readers
        this.announceToScreenReader('Theme customizer closed');

        console.log('ElementorStyleCustomizer: Panel closed');
    }

    /**
     * Handle color square selection
     */
    handleColorSelection(colorSquare) {
        const colorKey = colorSquare.closest('.color-square-wrapper').dataset.colorKey;
        const color = colorSquare.dataset.color;

        console.log('ElementorStyleCustomizer: Color selected', colorKey, color);

        // Apply the color immediately for preview
        this.applyColorPreview(colorKey, color);

        // Announce change
        this.announceToScreenReader(`${colorKey} color selected: ${color}`);
    }

    /**
     * Show educational tooltip
     */
    showTooltip(colorSquare) {
        const tooltipKey = colorSquare.dataset.tooltipKey;
        const tooltip = this.tooltips[tooltipKey];

        if (!tooltip) return;

        const tooltipElement = document.getElementById('color-tooltip');
        if (!tooltipElement) return;

        // Update tooltip content
        tooltipElement.querySelector('.tooltip-title').textContent = tooltip.title;

        const usageList = tooltipElement.querySelector('.usage-list');
        usageList.innerHTML = tooltip.usage.map(use => `<li>${use}</li>`).join('');

        tooltipElement.querySelector('.visibility-text').textContent = tooltip.visibility;
        tooltipElement.querySelector('.purpose-text').textContent = tooltip.purpose;

        // Position tooltip
        const rect = colorSquare.getBoundingClientRect();
        const panelRect = this.panel.getBoundingClientRect();

        tooltipElement.style.left = `${rect.right - panelRect.left + 10}px`;
        tooltipElement.style.top = `${rect.top - panelRect.top}px`;

        // Show tooltip
        tooltipElement.classList.add('visible');
        tooltipElement.setAttribute('aria-hidden', 'false');
    }

    /**
     * Hide tooltip
     */
    hideTooltip() {
        const tooltipElement = document.getElementById('color-tooltip');
        if (tooltipElement) {
            tooltipElement.classList.remove('visible');
            tooltipElement.setAttribute('aria-hidden', 'true');
        }
    }

    /**
     * Apply color preview
     */
    applyColorPreview(colorKey, color) {
        document.documentElement.style.setProperty(`--preview-${colorKey}`, color);
        document.documentElement.style.setProperty(`--color-${colorKey}`, color);
    }

    /**
     * Update a setting
     */
    updateSetting(setting, value) {
        this.settings[setting] = value;
        console.log('ElementorStyleCustomizer: Setting updated', setting, value);

        // Apply the change immediately
        this.applySettingPreview(setting, value);

        // Announce change
        this.announceToScreenReader(`${setting} updated to ${value}`);
    }

    /**
     * Apply setting preview
     */
    applySettingPreview(setting, value) {
        switch(setting) {
            case 'fontHeading':
                const headingFont = this.fontOptions[value];
                if (headingFont) {
                    document.documentElement.style.setProperty('--font-heading', headingFont.family);
                }
                break;

            case 'fontBody':
                const bodyFont = this.fontOptions[value];
                if (bodyFont) {
                    document.documentElement.style.setProperty('--font-body', bodyFont.family);
                }
                break;
        }
    }

    /**
     * Apply configuration globally via AJAX
     */
    async applyConfigurationGlobally() {
        console.log('ElementorStyleCustomizer: Applying configuration globally...');

        const button = this.panel.querySelector('[data-action="apply-globally"]');
        const originalText = button.innerHTML;

        // Update button state
        button.innerHTML = '<span class="btn-icon">⏳</span> Applying...';
        button.disabled = true;

        try {
            const response = await fetch(visualCustomizerData.ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'save_visual_customizer_global_config',
                    nonce: visualCustomizerData.nonce,
                    settings: JSON.stringify(this.settings)
                })
            });

            const result = await response.json();

            if (result.success) {
                // Success feedback
                button.innerHTML = '<span class="btn-icon">✅</span> Applied Successfully!';
                this.announceToScreenReader(visualCustomizerData.i18n.configApplied);

                // Reset button after delay
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 3000);

                console.log('ElementorStyleCustomizer: Configuration applied globally');
            } else {
                throw new Error(result.data || 'Failed to apply configuration');
            }

        } catch (error) {
            console.error('ElementorStyleCustomizer: Error applying configuration', error);

            // Error feedback
            button.innerHTML = '<span class="btn-icon">❌</span> Error occurred';
            this.announceToScreenReader('Error applying configuration');

            // Reset button after delay
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 3000);
        }
    }

    /**
     * Load global configuration
     */
    loadGlobalConfiguration() {
        if (this.globalConfig && Object.keys(this.globalConfig).length > 0) {
            Object.assign(this.settings, this.globalConfig);
            console.log('ElementorStyleCustomizer: Global configuration loaded', this.globalConfig);
        }
    }

    /**
     * Setup keyboard navigation
     */
    setupKeyboardNavigation() {
        // Tab management within panel
        this.panel?.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                this.handleTabNavigation(e);
            }
        });

        // Color square keyboard activation
        this.panel?.addEventListener('keydown', (e) => {
            const colorSquare = e.target.closest('.color-square');
            if (colorSquare && (e.key === 'Enter' || e.key === ' ')) {
                e.preventDefault();
                this.handleColorSelection(colorSquare);
            }
        });
    }

    /**
     * Handle tab navigation within panel
     */
    handleTabNavigation(e) {
        const focusableElements = this.panel.querySelectorAll(
            'button:not([disabled]), [href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
        );

        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        if (e.shiftKey && document.activeElement === firstElement) {
            e.preventDefault();
            lastElement.focus();
        } else if (!e.shiftKey && document.activeElement === lastElement) {
            e.preventDefault();
            firstElement.focus();
        }
    }

    /**
     * Setup accessibility features
     */
    setupAccessibilityFeatures() {
        // Add ARIA labels where missing
        const colorSquares = this.panel?.querySelectorAll('.color-square');
        colorSquares?.forEach(square => {
            if (!square.getAttribute('aria-label')) {
                const wrapper = square.closest('.color-square-wrapper');
                const colorKey = wrapper?.dataset.colorKey;
                if (colorKey) {
                    square.setAttribute('aria-label', `Select ${colorKey} color`);
                }
            }
        });

        console.log('ElementorStyleCustomizer: Accessibility features setup complete');
    }

    /**
     * Announce to screen readers
     */
    announceToScreenReader(message) {
        const announcer = document.getElementById('customizer-announcements');
        if (announcer) {
            announcer.textContent = message;

            // Clear after announcement
            setTimeout(() => {
                announcer.textContent = '';
            }, 1000);
        }
    }

    /**
     * Get contrast description for educational purposes
     */
    getContrastDescription(ratioString) {
        const ratio = parseFloat(ratioString.split(':')[0]);

        if (window.contrastCalculator) {
            return window.contrastCalculator.getContrastDescription(ratio);
        }

        // Fallback descriptions
        if (ratio >= 7) {
            return 'Excellent contrast - meets highest accessibility standards';
        } else if (ratio >= 4.5) {
            return 'Good contrast - meets standard accessibility requirements';
        } else {
            return 'Limited contrast - may be difficult for some users to read';
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Only initialize for admin users
    if (window.visualCustomizerData?.isAdmin) {
        window.elementorStyleCustomizer = new ElementorStyleCustomizer();
        console.log('ElementorStyleCustomizer: Initialized for admin user');
    } else {
        console.log('ElementorStyleCustomizer: Skipped initialization - admin access required');
    }
});
