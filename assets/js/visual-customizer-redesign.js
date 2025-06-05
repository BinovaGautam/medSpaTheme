/**
 * Visual Customizer - Redesigned Implementation
 * Based on visual-customizer-redesign-specs.md
 * Simple, focused interface with collapsible sections and educational tooltips
 * Elementor-Style: Panel pushes content left with proportional scaling
 */

class VisualCustomizerRedesign {
    constructor() {
        this.isOpen = false;
        this.settings = this.loadSettings();
        this.expandedSections = new Set(['colors']); // Color section expanded by default
        this.currentDevice = 'desktop'; // desktop, tablet, mobile

        this.init();
    }

    init() {
        // Force close mobile menu on initialization
        this.forceMobileMenuCloseOnInit();

        // Don't create wrapper on init - only when panel opens
        // this.setupMainContentWrapper(); MOVED TO openPanel()
        this.createPanel();
        this.bindEvents();
        this.setupKeyboardNavigation();
        this.setupAccessibilityFeatures();
        this.applySettings();
    }

    setupMainContentWrapper() {
        // Skip if wrapper already exists
        if (document.querySelector('.customizer-main-content')) {
            this.mainWrapper = document.querySelector('.customizer-main-content');
            return;
        }

        // Wrap all page content except admin bar in a scaling container
        const body = document.body;
        const adminBar = document.getElementById('wpadminbar');

        // Create main content wrapper
        const mainWrapper = document.createElement('div');
        mainWrapper.className = 'customizer-main-content';

        // Move all content except admin bar into wrapper
        const elementsToWrap = Array.from(body.children).filter(child =>
            child !== adminBar &&
            !child.classList.contains('customizer-backdrop') &&
            !child.classList.contains('elementor-customizer-panel') &&
            !child.classList.contains('customizer-main-content') && // Avoid wrapping existing wrapper
            child.tagName !== 'SCRIPT' && // Skip script tags
            child.tagName !== 'STYLE' // Skip style tags
        );

        // Only proceed if we have elements to wrap
        if (elementsToWrap.length === 0) {
            console.warn('Visual Customizer: No content elements found to wrap');
            return;
        }

        elementsToWrap.forEach(element => {
            mainWrapper.appendChild(element);
        });

        // Insert wrapper after admin bar or at beginning
        if (adminBar) {
            body.insertBefore(mainWrapper, adminBar.nextSibling);
        } else {
            body.insertBefore(mainWrapper, body.firstChild);
        }

        this.mainWrapper = mainWrapper;
        console.log('Visual Customizer: Content wrapper created successfully');
    }

    createPanel() {
        const backdrop = document.createElement('div');
        backdrop.className = 'customizer-backdrop';

        const panel = document.createElement('div');
        panel.className = 'elementor-customizer-panel';
        panel.innerHTML = this.generatePanelHTML();

        backdrop.appendChild(panel);
        document.body.appendChild(backdrop);

        this.backdrop = backdrop;
        this.panel = panel;
    }

    generatePanelHTML() {
        return `
            <div class="customizer-header">
                <div class="customizer-title">
                    <span class="customizer-icon">🎨</span>
                    <div>
                        <h3>Theme Customizer</h3>
                        <p class="customizer-subtitle">Customize your website appearance</p>
                    </div>
                </div>
                <button class="customizer-close-btn" aria-label="Close customizer">
                    <span>×</span>
                </button>
            </div>

            <div class="customizer-content">
                ${this.generateColorSchemeSection()}
                ${this.generateTypographySection()}
                ${this.generateLayoutControlsSection()}
                ${this.generateLivePreviewSection()}
            </div>

            ${this.generateDeviceControlsHTML()}

            <div class="customizer-footer">
                ${this.generateFooterHTML()}
            </div>
        `;
    }

    generateDeviceControlsHTML() {
        return `
            <div class="device-preview-controls">
                <button class="device-btn ${this.currentDevice === 'desktop' ? 'active' : ''}"
                        data-device="desktop"
                        onclick="window.visualCustomizer.switchDevice('desktop')"
                        title="Desktop Preview">
                    <span class="device-icon">🖥️</span>
                    <span>Desktop</span>
                </button>
                <button class="device-btn ${this.currentDevice === 'tablet' ? 'active' : ''}"
                        data-device="tablet"
                        onclick="window.visualCustomizer.switchDevice('tablet')"
                        title="Tablet Preview">
                    <span class="device-icon">📱</span>
                    <span>Tablet</span>
                </button>
                <button class="device-btn ${this.currentDevice === 'mobile' ? 'active' : ''}"
                        data-device="mobile"
                        onclick="window.visualCustomizer.switchDevice('mobile')"
                        title="Mobile Preview">
                    <span class="device-icon">📱</span>
                    <span>Mobile</span>
                </button>
            </div>
        `;
    }

    generateColorSchemeSection() {
        const isExpanded = this.expandedSections.has('colors');
        const palettes = visualCustomizerData.colorPalettes || {};

        return `
            <div class="customizer-section ${isExpanded ? 'expanded' : ''}" data-section="colors">
                <div class="section-header" onclick="window.visualCustomizer.toggleSection('colors')">
                    <span class="section-icon">${isExpanded ? '▼' : '▶'}</span>
                    <h3 class="section-title">Color Scheme</h3>
                </div>
                <div class="section-content" style="${isExpanded ? 'display: block;' : 'display: none;'}">
                    <div class="educational-intro">
                        <p>Choose colors that create the perfect medical spa atmosphere. Each color has been carefully selected for professionalism and trust.</p>
                    </div>

                    <div class="color-palettes-grid">
                        ${Object.entries(palettes).map(([key, palette]) => `
                            <div class="color-palette-option ${this.settings.colorPalette === key ? 'active' : ''}"
                                 data-palette="${key}">
                                <div class="palette-info">
                                    <h4 class="palette-name">${palette.name}</h4>
                                    <div class="color-squares-row">
                                        ${Object.entries(palette).filter(([k, v]) => k !== 'name').map(([colorKey, color]) =>
                                            this.generateColorSquare(colorKey, color, palette.name)
                                        ).join('')}
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>

                    ${this.generateContrastPairsHTML()}
                </div>
            </div>
        `;
    }

    generateColorSquare(colorKey, color, paletteName) {
        const tooltipContent = this.getColorTooltip(colorKey);

        return `
            <div class="color-square"
                 style="background-color: ${color}"
                 data-color="${color}"
                 data-color-key="${colorKey}"
                 title="${colorKey}: ${color}">
                <div class="color-tooltip">
                    <div class="tooltip-header">
                        <span class="tooltip-icon">${tooltipContent.icon}</span>
                        <strong>${tooltipContent.title}</strong>
                    </div>
                    <div class="tooltip-usage">${tooltipContent.usage}</div>
                    <div class="tooltip-examples">
                        ${tooltipContent.examples.map(example => `<span>▸ ${example}</span>`).join('')}
                    </div>
                </div>
            </div>
        `;
    }

    getColorTooltip(colorKey) {
        const tooltips = {
            'primary': {
                icon: '🎨',
                title: 'Primary Color',
                usage: 'Navigation bars, main buttons, and header elements',
                examples: ['Navigation backgrounds', 'Main buttons', 'Website header', 'Professional elements']
            },
            'secondary': {
                icon: '🌿',
                title: 'Secondary Color',
                usage: 'Accent highlights, hover effects, and supporting elements',
                examples: ['Accent highlights', 'Hover effects', 'Supporting elements', 'Brand touches']
            },
            'accent': {
                icon: '✨',
                title: 'Accent Color',
                usage: 'Special highlights and premium features',
                examples: ['Special offers', 'Premium badges', 'Success messages', 'Featured content']
            },
            'light': {
                icon: '☀️',
                title: 'Light Background',
                usage: 'Page backgrounds, card backgrounds, clean open spaces',
                examples: ['Page backgrounds', 'Card backgrounds', 'Clean open spaces', 'Easy on the eyes']
            },
            'dark': {
                icon: '🌙',
                title: 'Dark Text & Elements',
                usage: 'Text content and darker UI elements for contrast',
                examples: ['Body text', 'Headings', 'Form labels', 'Footer content']
            }
        };

        return tooltips[colorKey] || {
            icon: '🎨',
            title: colorKey.charAt(0).toUpperCase() + colorKey.slice(1),
            usage: 'Custom color for design elements',
            examples: ['Design elements', 'Visual accents']
        };
    }

    generateContrastPairsHTML() {
        // Simplified contrast pairs without complex calculations
        return `
            <div class="contrast-pairs-section">
                <h4 class="subsection-title">
                    <span class="subsection-icon">👁️</span>
                    Color Accessibility Information
                </h4>
                <div class="contrast-info-box">
                    <p><strong>✅ WCAG Compliant:</strong> All color combinations in our palettes meet accessibility standards for easy reading.</p>
                    <p><strong>📱 Responsive:</strong> Colors adapt beautifully across all devices and screen sizes.</p>
                    <p><strong>🎨 Professional:</strong> Carefully chosen colors for medical spa branding and trust.</p>
                </div>
            </div>
        `;
    }

    generateTypographySection() {
        const isExpanded = this.expandedSections.has('typography');
        const fonts = visualCustomizerData.fontOptions || {};

        return `
            <div class="customizer-section ${isExpanded ? 'expanded' : ''}" data-section="typography">
                <div class="section-header" onclick="window.visualCustomizer.toggleSection('typography')">
                    <span class="section-icon">${isExpanded ? '▼' : '▶'}</span>
                    <h3 class="section-title">Typography</h3>
                </div>
                <div class="section-content">
                    <div class="typography-controls">
                        <div class="font-control">
                            <label for="heading-font">Heading Font</label>
                            <select id="heading-font" data-setting="fontHeading" class="font-dropdown">
                                ${Object.entries(fonts.heading_fonts || {}).map(([key, font]) => `
                                    <option value="${key}" ${this.settings.fontHeading === key ? 'selected' : ''}>${font.name}</option>
                                `).join('')}
                            </select>
                        </div>

                        <div class="font-control">
                            <label for="body-font">Body Font</label>
                            <select id="body-font" data-setting="fontBody" class="font-dropdown">
                                ${Object.entries(fonts.body_fonts || {}).map(([key, font]) => `
                                    <option value="${key}" ${this.settings.fontBody === key ? 'selected' : ''}>${font.name}</option>
                                `).join('')}
                            </select>
                        </div>

                        <div class="font-control">
                            <label>Font Size</label>
                            <div class="font-size-options">
                                <button class="size-option ${this.settings.fontSize === 'small' ? 'active' : ''}" data-setting="fontSize" data-value="small">S</button>
                                <button class="size-option ${this.settings.fontSize === 'normal' ? 'active' : ''}" data-setting="fontSize" data-value="normal">M</button>
                                <button class="size-option ${this.settings.fontSize === 'large' ? 'active' : ''}" data-setting="fontSize" data-value="large">L</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    generateLayoutControlsSection() {
        const isExpanded = this.expandedSections.has('layout');

        return `
            <div class="customizer-section ${isExpanded ? 'expanded' : ''}" data-section="layout">
                <div class="section-header" onclick="window.visualCustomizer.toggleSection('layout')">
                    <span class="section-icon">${isExpanded ? '▼' : '▶'}</span>
                    <h3 class="section-title">Layout Controls</h3>
                </div>
                <div class="section-content">
                    <div class="layout-controls">
                        <div class="layout-control">
                            <label>Header Style</label>
                            <div class="style-options">
                                <button class="style-option ${this.settings.headerStyle === 'solid' ? 'active' : ''}" data-setting="headerStyle" data-value="solid">Solid</button>
                                <button class="style-option ${this.settings.headerStyle === 'transparent' ? 'active' : ''}" data-setting="headerStyle" data-value="transparent">Transparent</button>
                            </div>
                        </div>

                        <div class="layout-control">
                            <label>Button Style</label>
                            <div class="style-options">
                                <button class="style-option ${this.settings.buttonStyle === 'rounded' ? 'active' : ''}" data-setting="buttonStyle" data-value="rounded">Round</button>
                                <button class="style-option ${this.settings.buttonStyle === 'sharp' ? 'active' : ''}" data-setting="buttonStyle" data-value="sharp">Sharp</button>
                            </div>
                        </div>

                        <div class="layout-control">
                            <label>Animations</label>
                            <div class="style-options">
                                <button class="style-option ${this.settings.animations ? 'active' : ''}" data-setting="animations" data-value="true">Enabled</button>
                                <button class="style-option ${!this.settings.animations ? 'active' : ''}" data-setting="animations" data-value="false">Disabled</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    generateLivePreviewSection() {
        const isExpanded = this.expandedSections.has('preview');

        return `
            <div class="customizer-section ${isExpanded ? 'expanded' : ''}" data-section="preview">
                <div class="section-header" onclick="window.visualCustomizer.toggleSection('preview')">
                    <span class="section-icon">${isExpanded ? '▼' : '▶'}</span>
                    <h3 class="section-title">Live Preview</h3>
                </div>
                <div class="section-content">
                    <div class="live-preview-area">
                        ${this.generatePreviewContent()}
                    </div>
                </div>
            </div>
        `;
    }

    generatePreviewContent() {
        return `
            <div class="preview-demo">
                <div class="demo-header" style="background-color: var(--color-primary, #1B365D); color: white;">
                    <span class="demo-logo">🏥 Medical Spa</span>
                    <nav class="demo-nav">
                        <a href="#" style="color: white;">Home</a>
                        <a href="#" style="color: white;">Treatments</a>
                        <a href="#" style="color: white;">Contact</a>
                    </nav>
                </div>

                <div class="demo-content" style="background-color: var(--color-light, #FDFCFA); color: var(--color-dark, #2C3E50); padding: 15px;">
                    <h4 style="color: var(--color-primary, #1B365D); margin: 0 0 8px 0;">Sample Content</h4>
                    <p style="margin: 0 0 12px 0; font-size: 13px;">Experience our professional medical spa treatments in a comfortable environment.</p>

                    <button class="demo-button" style="
                        background-color: var(--color-secondary, #87A96B);
                        color: white;
                        border: none;
                        padding: 8px 16px;
                        border-radius: ${this.settings.buttonStyle === 'sharp' ? '2px' : '6px'};
                        font-size: 12px;
                        cursor: pointer;
                    ">Book Consultation</button>
                </div>
            </div>
        `;
    }

    generateFooterHTML() {
        if (visualCustomizerData.isAdmin) {
            return `
                <button class="apply-globally-btn" onclick="window.visualCustomizer.applyConfigurationGlobally()">
                    <span class="btn-icon">🌐</span>
                    Apply Globally
                </button>
                <div class="footer-info">
                    <small>Admin Only: Changes will be applied globally for all visitors</small>
                </div>
            `;
        } else {
            return `
                <div class="footer-info">
                    <small>Changes are visible to you only until applied globally</small>
                </div>
            `;
        }
    }

    bindEvents() {
        // Close button
        this.panel.querySelector('.customizer-close-btn').addEventListener('click', () => this.closePanel());

        // NO backdrop click - main content should remain interactive
        // Removed: this.backdrop.addEventListener('click', (e) => { ... });

        // Color palette selection
        this.panel.addEventListener('click', (e) => {
            if (e.target.closest('.color-palette-option')) {
                const palette = e.target.closest('.color-palette-option').dataset.palette;
                this.selectColorPalette(palette);
            }
        });

        // Typography controls
        this.panel.addEventListener('change', (e) => {
            if (e.target.dataset.setting) {
                this.updateSetting(e.target.dataset.setting, e.target.value);
            }
        });

        // Button controls
        this.panel.addEventListener('click', (e) => {
            if (e.target.dataset.setting && e.target.dataset.value) {
                this.updateSetting(e.target.dataset.setting, e.target.dataset.value);
                this.updateButtonStates(e.target);
            }
        });

        // Device controls
        this.panel.addEventListener('click', (e) => {
            if (e.target.closest('.device-btn')) {
                const device = e.target.closest('.device-btn').dataset.device;
                this.switchDevice(device);
            }
        });
    }

    toggleSection(sectionId) {
        if (this.expandedSections.has(sectionId)) {
            this.expandedSections.delete(sectionId);
        } else {
            this.expandedSections.add(sectionId);
        }

        const section = this.panel.querySelector(`[data-section="${sectionId}"]`);
        const icon = section.querySelector('.section-icon');

        section.classList.toggle('expanded');
        icon.textContent = section.classList.contains('expanded') ? '▼' : '▶';

        this.announceToScreenReader(`${sectionId} section ${section.classList.contains('expanded') ? 'expanded' : 'collapsed'}`);
    }

    selectColorPalette(paletteKey) {
        this.settings.colorPalette = paletteKey;
        this.saveSettings();
        this.applyColorPalette(paletteKey);

        // Update UI
        this.panel.querySelectorAll('.color-palette-option').forEach(option => {
            option.classList.toggle('active', option.dataset.palette === paletteKey);
        });

        // Update live preview
        this.updateLivePreview();

        this.announceToScreenReader(`Applied ${paletteKey} color palette`);
    }

    updateSetting(setting, value) {
        this.settings[setting] = setting === 'animations' ? value === 'true' : value;
        this.saveSettings();
        this.applySettings();
        this.updateLivePreview();
    }

    updateButtonStates(clickedButton) {
        const setting = clickedButton.dataset.setting;
        const container = clickedButton.closest('.style-options') || clickedButton.closest('.font-size-options');

        if (container) {
            container.querySelectorAll('button').forEach(btn => {
                btn.classList.toggle('active', btn === clickedButton);
            });
        }
    }

    updateLivePreview() {
        const previewArea = this.panel.querySelector('.live-preview-area');
        if (previewArea) {
            previewArea.innerHTML = this.generatePreviewContent();
        }
    }

    applyColorPalette(paletteKey) {
        const palette = visualCustomizerData.colorPalettes[paletteKey];
        if (!palette) return;

        const root = document.documentElement;
        Object.entries(palette).forEach(([colorKey, colorValue]) => {
            if (colorKey !== 'name') {
                root.style.setProperty(`--color-${colorKey}`, colorValue);
            }
        });
    }

    applySettings() {
        this.applyColorPalette(this.settings.colorPalette);

        // Apply typography settings
        const root = document.documentElement;
        root.style.setProperty('--font-heading', this.settings.fontHeading);
        root.style.setProperty('--font-body', this.settings.fontBody);
        root.style.setProperty('--font-size-base', this.settings.fontSize === 'small' ? '14px' : this.settings.fontSize === 'large' ? '18px' : '16px');

        // Apply layout settings
        document.body.classList.remove('header-transparent', 'header-solid');
        document.body.classList.add(`header-${this.settings.headerStyle}`);

        document.body.classList.toggle('buttons-sharp', this.settings.buttonStyle === 'sharp');
        document.body.classList.toggle('animations-disabled', !this.settings.animations);
    }

    async applyConfigurationGlobally() {
        if (!visualCustomizerData.isAdmin) {
            alert('Only administrators can apply global configurations');
            return;
        }

        const button = this.panel.querySelector('.apply-globally-btn');
        const originalText = button.innerHTML;

        button.innerHTML = '<span class="btn-icon spin">⏳</span> Applying...';
        button.disabled = true;

        try {
            const response = await fetch(visualCustomizerData.ajaxUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'save_visual_customizer_global_config',
                    nonce: visualCustomizerData.nonce,
                    config: JSON.stringify(this.settings)
                })
            });

            const result = await response.json();

            if (result.success) {
                button.innerHTML = '<span class="btn-icon">✅</span> Applied Successfully!';
                button.classList.add('success');
                this.announceToScreenReader('Configuration applied globally for all visitors');
            } else {
                throw new Error(result.data || 'Failed to save configuration');
            }
        } catch (error) {
            button.innerHTML = '<span class="btn-icon">❌</span> Error - Try Again';
            button.classList.add('error');
            console.error('Failed to apply global configuration:', error);
        }

        setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
            button.classList.remove('success', 'error');
        }, 3000);
    }

    openPanel() {
        // STEP 1: Force close any open mobile menu before opening customizer
        this.forceMobileMenuClose();

        // STEP 2: Setup content wrapper only when panel opens
        this.setupMainContentWrapper();

        this.backdrop.classList.add('visible');
        this.panel.classList.add('open');
        this.isOpen = true;

        // Add active class to body for content scaling
        document.body.classList.add('visual-customizer-active');
        // Don't set overflow hidden to allow main content interaction
        // document.body.style.overflow = 'hidden'; // REMOVED

        // Ensure main content stays clear and interactive
        this.ensureMainContentClarity();

        // Apply initial device scaling
        this.applyDeviceScaling();

        // Focus management
        setTimeout(() => {
            const firstFocusable = this.panel.querySelector('.section-header');
            if (firstFocusable) firstFocusable.focus();
        }, 300);

        this.announceToScreenReader('Theme customizer opened');
    }

    forceMobileMenuClose() {
        // Force close mobile menu using multiple methods
        const mobileMenu = document.querySelector('.mobile-menu');
        const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');

        // Remove all mobile menu open classes
        document.body.classList.remove('mobile-menu-open');
        document.documentElement.classList.remove('mobile-menu-open');

        // Close mobile menu panel
        if (mobileMenu) {
            mobileMenu.classList.remove('open', 'opening');
            mobileMenu.style.right = '-100%';
            mobileMenu.style.transform = 'translateX(100%)';
            mobileMenu.style.visibility = 'hidden';
            mobileMenu.style.pointerEvents = 'none';
        }

        // Close mobile menu overlay
        if (mobileMenuOverlay) {
            mobileMenuOverlay.classList.remove('open', 'opening');
            mobileMenuOverlay.style.opacity = '0';
            mobileMenuOverlay.style.visibility = 'hidden';
            mobileMenuOverlay.style.pointerEvents = 'none';
            mobileMenuOverlay.style.backdropFilter = 'none';
            mobileMenuOverlay.style.webkitBackdropFilter = 'none';
            mobileMenuOverlay.style.filter = 'none';
            mobileMenuOverlay.style.webkitFilter = 'none';
            mobileMenuOverlay.style.background = 'transparent';
        }

        // Reset mobile menu toggle state
        if (mobileMenuToggle) {
            mobileMenuToggle.setAttribute('aria-expanded', 'false');
            mobileMenuToggle.classList.remove('active');
        }

        // Try to call mobile menu close method if available
        if (window.mobileMenu && typeof window.mobileMenu.close === 'function') {
            try {
                window.mobileMenu.close();
            } catch (e) {
                console.log('Mobile menu close method not available:', e);
            }
        }

        // Dispatch custom event to close mobile menu
        document.dispatchEvent(new CustomEvent('closeMobileMenu'));

        console.log('Visual Customizer: Mobile menu forcefully closed');
    }

    ensureMainContentClarity() {
        if (this.mainWrapper) {
            // Force clear rendering on main content
            this.mainWrapper.style.filter = 'none';
            this.mainWrapper.style.backdropFilter = 'none';
            this.mainWrapper.style.opacity = '1';
            this.mainWrapper.style.webkitFilter = 'none';

            // Ensure all child elements are also clear
            const allElements = this.mainWrapper.querySelectorAll('*');
            allElements.forEach(el => {
                if (el.style.filter && el.style.filter !== 'none') {
                    console.warn('Clearing filter from element:', el);
                    el.style.filter = 'none';
                }
                if (el.style.backdropFilter && el.style.backdropFilter !== 'none') {
                    console.warn('Clearing backdrop-filter from element:', el);
                    el.style.backdropFilter = 'none';
                }
            });
        }
    }

    closePanel() {
        this.backdrop.classList.remove('visible');
        this.panel.classList.remove('open');
        this.isOpen = false;

        // Remove active class to restore normal content
        document.body.classList.remove('visual-customizer-active');
        // No need to reset overflow since we don't set it
        // document.body.style.overflow = ''; // REMOVED

        // Remove content wrapper to restore normal layout
        this.removeMainContentWrapper();

        this.announceToScreenReader('Theme customizer closed');
    }

    removeMainContentWrapper() {
        if (this.mainWrapper) {
            // Move all content back out of the wrapper
            const parent = this.mainWrapper.parentNode;
            while (this.mainWrapper.firstChild) {
                parent.insertBefore(this.mainWrapper.firstChild, this.mainWrapper);
            }

            // Remove the wrapper
            this.mainWrapper.remove();
            this.mainWrapper = null;

            console.log('Visual Customizer: Content wrapper removed');
        }
    }

    setupKeyboardNavigation() {
        document.addEventListener('keydown', (e) => {
            if (!this.isOpen) return;

            if (e.key === 'Escape') {
                this.closePanel();
            }
        });
    }

    setupAccessibilityFeatures() {
        // Create screen reader announcements container
        if (!document.getElementById('customizer-announcements')) {
            const announcements = document.createElement('div');
            announcements.id = 'customizer-announcements';
            announcements.setAttribute('aria-live', 'polite');
            announcements.setAttribute('aria-atomic', 'true');
            announcements.className = 'sr-only';
            document.body.appendChild(announcements);
        }
    }

    announceToScreenReader(message) {
        const announcements = document.getElementById('customizer-announcements');
        if (announcements) {
            announcements.textContent = message;
            setTimeout(() => announcements.textContent = '', 1000);
        }
    }

    loadSettings() {
        // For admin users, use localStorage settings for live preview
        if (visualCustomizerData.isAdmin) {
            const stored = localStorage.getItem('preetidreams_visual_customizer_settings');
            const defaults = {
                colorPalette: 'classic-forest',
                fontHeading: 'playfair-display',
                fontBody: 'inter',
                fontSize: 'normal',
                headerStyle: 'solid',
                buttonStyle: 'rounded',
                animations: true
            };
            return stored ? { ...defaults, ...JSON.parse(stored) } : defaults;
        } else {
            // For regular users, try to load from global configuration data
            // This will be populated by PHP in the head section
            const globalConfig = window.preetidreamsGlobalConfig || {};
            return {
                colorPalette: globalConfig.colorPalette || 'classic-forest',
                fontHeading: globalConfig.fontHeading || 'playfair-display',
                fontBody: globalConfig.fontBody || 'inter',
                fontSize: globalConfig.fontSize || 'normal',
                headerStyle: globalConfig.headerStyle || 'solid',
                buttonStyle: globalConfig.buttonStyle || 'rounded',
                animations: globalConfig.animations !== false
            };
        }
    }

    saveSettings() {
        localStorage.setItem('preetidreams_visual_customizer_settings', JSON.stringify(this.settings));
    }

    switchDevice(device) {
        this.currentDevice = device;

        // Update device button states
        this.panel.querySelectorAll('.device-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.device === device);
        });

        // Apply device-specific scaling to main content
        this.applyDeviceScaling();

        this.announceToScreenReader(`Switched to ${device} preview mode`);
    }

    applyDeviceScaling() {
        const wrapper = this.mainWrapper;
        if (!wrapper) return;

        // Remove previous device classes
        wrapper.classList.remove('device-desktop', 'device-tablet', 'device-mobile');

        // Add current device class
        wrapper.classList.add(`device-${this.currentDevice}`);

        // Apply CSS custom properties for device-specific scaling
        const root = document.documentElement;

        switch (this.currentDevice) {
            case 'desktop':
                root.style.setProperty('--device-scale', '1');
                root.style.setProperty('--device-width', '100%');
                break;
            case 'tablet':
                root.style.setProperty('--device-scale', '0.75');
                root.style.setProperty('--device-width', '768px');
                wrapper.style.maxWidth = '768px';
                wrapper.style.margin = '0 auto';
                break;
            case 'mobile':
                root.style.setProperty('--device-scale', '0.5');
                root.style.setProperty('--device-width', '375px');
                wrapper.style.maxWidth = '375px';
                wrapper.style.margin = '0 auto';
                break;
        }
    }

    forceMobileMenuCloseOnInit() {
        // Wait a bit for page to load completely
        setTimeout(() => {
            const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
            const mobileMenu = document.querySelector('.mobile-menu');

            // Check if mobile menu is open and close it
            if (mobileMenuOverlay && (mobileMenuOverlay.classList.contains('open') || mobileMenuOverlay.classList.contains('opening'))) {
                console.log('Visual Customizer: Detected open mobile menu on init, closing...');
                this.forceMobileMenuClose();
            }

            if (mobileMenu && (mobileMenu.classList.contains('open') || mobileMenu.classList.contains('opening'))) {
                console.log('Visual Customizer: Detected open mobile menu panel on init, closing...');
                this.forceMobileMenuClose();
            }

            // Also close if body has mobile menu open class
            if (document.body.classList.contains('mobile-menu-open') || document.documentElement.classList.contains('mobile-menu-open')) {
                console.log('Visual Customizer: Detected mobile menu open body class on init, closing...');
                this.forceMobileMenuClose();
            }
        }, 100);
    }
}

// Initialize Redesigned Visual Customizer
document.addEventListener('DOMContentLoaded', function() {
    // Create global function for admin bar trigger
    window.openThemeCustomizer = function() {
        if (window.visualCustomizer) {
            window.visualCustomizer.openPanel();
        } else {
            setTimeout(window.openThemeCustomizer, 100);
        }
    };

    // Initialize redesigned customizer
    if (window.visualCustomizerData) {
        window.visualCustomizer = new VisualCustomizerRedesign();
        window.VisualCustomizerRedesign = VisualCustomizerRedesign;
    }
});
