/**
 * Visual Customizer Phase 2 - Advanced Component Theming
 * Provides granular control over individual website components with real-time preview
 */

class VisualCustomizerPhase2 {
    constructor() {
        this.isOpen = false;
        this.currentTab = 'colors';
        this.currentPreviewMode = 'desktop';
        this.expandedComponent = null;
        this.settings = this.loadSettings();
        this.componentSettings = this.loadComponentSettings();

        this.init();
    }

    init() {
        this.createPanel();
        this.bindEvents();
        this.setupKeyboardNavigation();
        this.applySettings();
        this.setupAccessibilityFeatures();
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
                        <h3>${visualCustomizerData.i18n.title || 'Visual Theme Customizer'}</h3>
                        <p class="customizer-subtitle">${visualCustomizerData.i18n.subtitle || 'Customize your website appearance'}</p>
                    </div>
                </div>
                <button class="customizer-close-btn" aria-label="Close customizer">
                    <span>×</span>
                </button>
            </div>

            <div class="customizer-content">
                ${this.generateTabsHTML()}
                ${this.generateTabContentsHTML()}
            </div>

            <div class="customizer-footer">
                ${this.generateFooterHTML()}
            </div>
        `;
    }

    generateTabsHTML() {
        const tabs = [
            { id: 'colors', icon: '🎨', label: 'Colors' },
            { id: 'components', icon: '🧩', label: 'Components' },
            { id: 'typography', icon: '📝', label: 'Typography' },
            { id: 'settings', icon: '⚙️', label: 'Settings' }
        ];

        return `
            <div class="customizer-tabs">
                ${tabs.map(tab => `
                    <button class="tab-button ${tab.id === this.currentTab ? 'active' : ''}"
                            data-tab="${tab.id}"
                            aria-selected="${tab.id === this.currentTab}"
                            role="tab">
                        <span class="tab-icon">${tab.icon}</span>
                        <span>${tab.label}</span>
                    </button>
                `).join('')}
            </div>
        `;
    }

    generateTabContentsHTML() {
        return `
            <div class="tab-content ${this.currentTab === 'colors' ? 'active' : ''}" data-tab="colors">
                ${this.generateColorsTabHTML()}
            </div>
            <div class="tab-content ${this.currentTab === 'components' ? 'active' : ''}" data-tab="components">
                ${this.generateComponentsTabHTML()}
            </div>
            <div class="tab-content ${this.currentTab === 'typography' ? 'active' : ''}" data-tab="typography">
                ${this.generateTypographyTabHTML()}
            </div>
            <div class="tab-content ${this.currentTab === 'settings' ? 'active' : ''}" data-tab="settings">
                ${this.generateSettingsTabHTML()}
            </div>
        `;
    }

    generateColorsTabHTML() {
        return `
            <div class="educational-intro">
                <p>Choose colors that create the perfect medical spa atmosphere. Each color has been carefully selected for professionalism and trust.</p>
            </div>
            ${this.generateColorSystemHTML()}
            ${this.generateContrastPairsHTML()}
        `;
    }

    generateComponentsTabHTML() {
        return `
            <div class="component-theming-section">
                <div class="component-grid">
                    ${this.generateComponentCardsHTML()}
                </div>
                <div class="component-preview-area">
                    ${this.generatePreviewAreaHTML()}
                </div>
            </div>
        `;
    }

    generateComponentCardsHTML() {
        const components = this.getComponentDefinitions();

        return Object.entries(components).map(([key, component]) => `
            <div class="component-card" data-component="${key}">
                <div class="component-header" onclick="this.parentElement.classList.toggle('selected')">
                    <span class="component-icon">${component.icon}</span>
                    <div class="component-info">
                        <h4 class="component-name">${component.name}</h4>
                        <p class="component-description">${component.description}</p>
                    </div>
                    <button class="component-expand" onclick="this.closest('.component-card').querySelector('.component-controls').classList.toggle('expanded'); this.classList.toggle('active')">
                        ⚙️
                    </button>
                </div>
                <div class="component-controls">
                    ${this.generateComponentControlsHTML(key, component)}
                </div>
            </div>
        `).join('');
    }

    generateComponentControlsHTML(componentKey, component) {
        const settings = this.componentSettings[componentKey] || {};

        return Object.entries(component.options).map(([optionKey, option]) => `
            <div class="component-option">
                <div class="option-label">${option.label}</div>
                <div class="option-controls">
                    ${option.choices.map(choice => `
                        <button class="option-button ${settings[optionKey] === choice.value ? 'active' : ''}"
                                data-component="${componentKey}"
                                data-option="${optionKey}"
                                data-value="${choice.value}"
                                title="${choice.description || choice.label}">
                            ${choice.label}
                        </button>
                    `).join('')}
                </div>
            </div>
        `).join('');
    }

    generatePreviewAreaHTML() {
        return `
            <div class="preview-header">
                <h4>Component Preview</h4>
                <div class="preview-mode-selector">
                    <button class="preview-mode active" data-mode="desktop" title="Desktop Preview">
                        <span class="mode-icon">🖥️</span>
                    </button>
                    <button class="preview-mode" data-mode="tablet" title="Tablet Preview">
                        <span class="mode-icon">📱</span>
                    </button>
                    <button class="preview-mode" data-mode="mobile" title="Mobile Preview">
                        <span class="mode-icon">📱</span>
                    </button>
                </div>
            </div>
            <div class="component-preview-frame desktop-mode">
                <div class="preview-placeholder">
                    <div class="preview-icon">🧩</div>
                    <h5>Select a Component</h5>
                    <p>Click on a component card and expand its settings to see a live preview</p>
                </div>
            </div>
        `;
    }

    generateColorSystemHTML() {
        const palettes = visualCustomizerData.colorPalettes || {};

        return `
            <div class="customizer-section">
                <h3 class="section-title">
                    <span class="section-icon">🎨</span>
                    Medical Spa Color Palettes
                </h3>
                <div class="color-palettes-grid">
                    ${Object.entries(palettes).map(([key, palette]) => `
                        <div class="color-palette-option ${this.settings.colorPalette === key ? 'active' : ''}"
                             data-palette="${key}">
                            <div class="palette-header">
                                <h4 class="palette-name">${palette.name}</h4>
                                <div class="palette-thumbnail">
                                    ${Object.entries(palette).filter(([k, v]) => k !== 'name').map(([colorKey, color]) => `
                                        <div class="color-swatch" style="background-color: ${color}" title="${colorKey}: ${color}"></div>
                                    `).join('')}
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }

    generateContrastPairsHTML() {
        const pairs = visualCustomizerData.contrastPairs || [];

        return `
            <div class="customizer-section">
                <h3 class="subsection-title">
                    <span class="subsection-icon">👁️</span>
                    Smart Contrast Combinations
                </h3>
                <div class="contrast-pairs">
                    ${pairs.map(pair => `
                        <div class="contrast-pair">
                            <div class="contrast-demo" style="background-color: ${pair.background}; color: ${pair.text};">
                                ${pair.label}
                            </div>
                            <div class="contrast-info">
                                <div class="contrast-ratio">${pair.ratio}</div>
                                <div class="contrast-description">${pair.usage}</div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }

    generateTypographyTabHTML() {
        return `
            <div class="educational-intro">
                <p>Select premium typography combinations that enhance readability and reflect medical professionalism.</p>
            </div>
            ${this.generateTypographyHTML()}
        `;
    }

    generateTypographyHTML() {
        const fonts = visualCustomizerData.fontOptions || {};

        return `
            <div class="customizer-section">
                <h3 class="section-title">
                    <span class="section-icon">📝</span>
                    Typography System
                </h3>
                <div class="typography-controls">
                    <div class="font-selector">
                        <label for="heading-font">Heading Font</label>
                        <select id="heading-font" data-setting="fontHeading">
                            ${Object.entries(fonts.heading_fonts || {}).map(([key, font]) => `
                                <option value="${key}" ${this.settings.fontHeading === key ? 'selected' : ''}>${font.name}</option>
                            `).join('')}
                        </select>
                    </div>
                    <div class="font-selector">
                        <label for="body-font">Body Font</label>
                        <select id="body-font" data-setting="fontBody">
                            ${Object.entries(fonts.body_fonts || {}).map(([key, font]) => `
                                <option value="${key}" ${this.settings.fontBody === key ? 'selected' : ''}>${font.name}</option>
                            `).join('')}
                        </select>
                    </div>
                </div>
            </div>
        `;
    }

    generateSettingsTabHTML() {
        return `
            <div class="customizer-section">
                <h3 class="section-title">
                    <span class="section-icon">⚙️</span>
                    Global Settings
                </h3>
                <div class="settings-controls">
                    <div class="setting-group">
                        <label>Animation Level</label>
                        <div class="setting-options">
                            <button class="setting-option ${this.settings.animations ? 'active' : ''}" data-setting="animations" data-value="true">Enabled</button>
                            <button class="setting-option ${!this.settings.animations ? 'active' : ''}" data-setting="animations" data-value="false">Disabled</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    generateFooterHTML() {
        if (visualCustomizerData.isAdmin) {
            return `
                <button class="apply-globally-btn" onclick="this.disabled=true; window.visualCustomizer.applyConfigurationGlobally()">
                    <span class="btn-icon">🌐</span>
                    Apply Configuration Globally
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

    getComponentDefinitions() {
        return {
            header: {
                name: 'Header',
                icon: '📄',
                description: 'Navigation and site header styling',
                options: {
                    style: {
                        label: 'Header Style',
                        choices: [
                            { value: 'transparent', label: 'Transparent', description: 'See-through header over content' },
                            { value: 'solid', label: 'Solid', description: 'Opaque background' },
                            { value: 'gradient', label: 'Gradient', description: 'Gradient background' },
                            { value: 'blur', label: 'Glass Effect', description: 'Blurred background effect' }
                        ]
                    },
                    background: {
                        label: 'Background',
                        choices: [
                            { value: 'default', label: 'Default' },
                            { value: 'custom', label: 'Custom Color' },
                            { value: 'pattern', label: 'Pattern Overlay' }
                        ]
                    }
                }
            },
            buttons: {
                name: 'Buttons',
                icon: '🔘',
                description: 'Button styles and interactions',
                options: {
                    style: {
                        label: 'Button Style',
                        choices: [
                            { value: 'rounded', label: 'Rounded' },
                            { value: 'sharp', label: 'Sharp' },
                            { value: 'pill', label: 'Pill' },
                            { value: 'outlined', label: 'Outlined' }
                        ]
                    },
                    effect: {
                        label: 'Visual Effect',
                        choices: [
                            { value: 'none', label: 'None' },
                            { value: 'shadow', label: 'Shadow' },
                            { value: 'glow', label: 'Glow' },
                            { value: 'gradient', label: 'Gradient' }
                        ]
                    }
                }
            },
            cards: {
                name: 'Cards',
                icon: '🃏',
                description: 'Content card design and layout',
                options: {
                    design: {
                        label: 'Card Design',
                        choices: [
                            { value: 'modern', label: 'Modern' },
                            { value: 'elegant', label: 'Elegant' },
                            { value: 'minimal', label: 'Minimal' },
                            { value: 'luxury', label: 'Luxury' }
                        ]
                    },
                    shadow: {
                        label: 'Shadow Level',
                        choices: [
                            { value: 'none', label: 'None' },
                            { value: 'light', label: 'Light' },
                            { value: 'medium', label: 'Medium' },
                            { value: 'heavy', label: 'Heavy' }
                        ]
                    }
                }
            }
        };
    }

    bindEvents() {
        // Close button
        this.panel.querySelector('.customizer-close-btn').addEventListener('click', () => this.closePanel());

        // Backdrop click
        this.backdrop.addEventListener('click', (e) => {
            if (e.target === this.backdrop) this.closePanel();
        });

        // Tab navigation
        this.panel.addEventListener('click', (e) => {
            if (e.target.closest('.tab-button')) {
                const tabId = e.target.closest('.tab-button').dataset.tab;
                this.switchTab(tabId);
            }
        });

        // Color palette selection
        this.panel.addEventListener('click', (e) => {
            if (e.target.closest('.color-palette-option')) {
                const palette = e.target.closest('.color-palette-option').dataset.palette;
                this.selectColorPalette(palette);
            }
        });

        // Component option selection
        this.panel.addEventListener('click', (e) => {
            if (e.target.closest('.option-button')) {
                const button = e.target.closest('.option-button');
                const component = button.dataset.component;
                const option = button.dataset.option;
                const value = button.dataset.value;
                this.updateComponentSetting(component, option, value);
            }
        });

        // Preview mode selection
        this.panel.addEventListener('click', (e) => {
            if (e.target.closest('.preview-mode')) {
                const mode = e.target.closest('.preview-mode').dataset.mode;
                this.switchPreviewMode(mode);
            }
        });

        // Typography controls
        this.panel.addEventListener('change', (e) => {
            if (e.target.dataset.setting) {
                this.updateSetting(e.target.dataset.setting, e.target.value);
            }
        });
    }

    switchTab(tabId) {
        this.currentTab = tabId;

        // Update tab buttons
        this.panel.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.tab === tabId);
            btn.setAttribute('aria-selected', btn.dataset.tab === tabId);
        });

        // Update tab content
        this.panel.querySelectorAll('.tab-content').forEach(content => {
            content.classList.toggle('active', content.dataset.tab === tabId);
        });

        this.announceToScreenReader(`Switched to ${tabId} tab`);
    }

    selectColorPalette(paletteKey) {
        this.settings.colorPalette = paletteKey;
        this.saveSettings();
        this.applyColorPalette(paletteKey);

        // Update UI
        this.panel.querySelectorAll('.color-palette-option').forEach(option => {
            option.classList.toggle('active', option.dataset.palette === paletteKey);
        });

        this.announceToScreenReader(`Applied ${paletteKey} color palette`);
    }

    updateComponentSetting(component, option, value) {
        if (!this.componentSettings[component]) {
            this.componentSettings[component] = {};
        }

        this.componentSettings[component][option] = value;
        this.saveComponentSettings();
        this.applyComponentTheming(component);
        this.updateComponentPreview(component);

        // Update UI
        const card = this.panel.querySelector(`[data-component="${component}"]`);
        if (card) {
            card.querySelectorAll(`[data-option="${option}"] .option-button`).forEach(btn => {
                btn.classList.toggle('active', btn.dataset.value === value);
            });
        }

        this.announceToScreenReader(`Updated ${component} ${option} to ${value}`);
    }

    switchPreviewMode(mode) {
        this.currentPreviewMode = mode;

        // Update mode buttons
        this.panel.querySelectorAll('.preview-mode').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.mode === mode);
        });

        // Update preview frame
        const frame = this.panel.querySelector('.component-preview-frame');
        if (frame) {
            frame.className = `component-preview-frame ${mode}-mode`;
        }
    }

    updateComponentPreview(component) {
        const previewFrame = this.panel.querySelector('.component-preview-frame');
        if (!previewFrame) return;

        const settings = this.componentSettings[component] || {};
        const previewHTML = this.generateComponentPreview(component, settings);

        previewFrame.innerHTML = `
            <div class="component-preview-content">
                <h5>${component.charAt(0).toUpperCase() + component.slice(1)} Preview</h5>
                ${previewHTML}
            </div>
        `;
    }

    generateComponentPreview(component, settings) {
        switch (component) {
            case 'header':
                return this.generateHeaderPreview(settings);
            case 'buttons':
                return this.generateButtonPreview(settings);
            case 'cards':
                return this.generateCardPreview(settings);
            default:
                return '<p>Preview will appear here when you select component options</p>';
        }
    }

    generateHeaderPreview(settings) {
        return `
            <div class="header-preview header-${settings.style || 'transparent'} bg-${settings.background || 'default'}">
                <div class="header-content">
                    <div class="logo">
                        <span class="logo-icon">🏥</span>
                        <span>Medical Spa</span>
                    </div>
                    <nav class="header-nav">
                        <a href="#" class="nav-link">Home</a>
                        <a href="#" class="nav-link">Treatments</a>
                        <a href="#" class="nav-link">About</a>
                        <a href="#" class="nav-link">Contact</a>
                    </nav>
                </div>
            </div>
        `;
    }

    generateButtonPreview(settings) {
        return `
            <div class="button-preview">
                <button class="btn-preview btn-${settings.style || 'rounded'} effect-${settings.effect || 'none'}">
                    Book Consultation
                </button>
                <button class="btn-preview btn-secondary btn-${settings.style || 'rounded'} effect-${settings.effect || 'none'}">
                    Learn More
                </button>
            </div>
        `;
    }

    generateCardPreview(settings) {
        return `
            <div class="card-preview card-${settings.design || 'modern'} shadow-${settings.shadow || 'medium'} radius-${settings.radius || 'rounded'}">
                <div class="card-image">
                    <div class="placeholder-image">📸</div>
                </div>
                <div class="card-content">
                    <h6>Treatment Card</h6>
                    <p>Professional treatment description with pricing and benefits.</p>
                    <button class="card-button">Learn More</button>
                </div>
            </div>
        `;
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

    applyComponentTheming(component) {
        const settings = this.componentSettings[component] || {};
        const root = document.documentElement;

        Object.entries(settings).forEach(([option, value]) => {
            root.style.setProperty(`--${component}-${option}`, value);

            // Apply to body classes for CSS targeting
            document.body.classList.forEach(className => {
                if (className.startsWith(`${component}-`)) {
                    document.body.classList.remove(className);
                }
            });

            Object.entries(settings).forEach(([opt, val]) => {
                document.body.classList.add(`${component}-${val}`);
            });
        });
    }

    updateSetting(setting, value) {
        this.settings[setting] = setting === 'animations' ? value === 'true' : value;
        this.saveSettings();
        this.applySettings();
    }

    applySettings() {
        this.applyColorPalette(this.settings.colorPalette);

        // Apply all component settings
        Object.keys(this.componentSettings).forEach(component => {
            this.applyComponentTheming(component);
        });
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
                    config: JSON.stringify({
                        ...this.settings,
                        componentSettings: this.componentSettings
                    })
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
        this.backdrop.classList.add('visible');
        this.panel.classList.add('open');
        this.isOpen = true;
        document.body.style.overflow = 'hidden';

        // Focus management
        setTimeout(() => {
            const firstFocusable = this.panel.querySelector('.tab-button');
            if (firstFocusable) firstFocusable.focus();
        }, 300);

        this.announceToScreenReader('Visual customizer opened');
    }

    closePanel() {
        this.backdrop.classList.remove('visible');
        this.panel.classList.remove('open');
        this.isOpen = false;
        document.body.style.overflow = '';
        this.announceToScreenReader('Visual customizer closed');
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
        const stored = localStorage.getItem('preetidreams_visual_customizer_settings');
        const defaults = {
            colorPalette: 'classic-forest',
            fontHeading: 'playfair-display',
            fontBody: 'inter',
            fontSize: 'normal',
            animations: true
        };

        return stored ? { ...defaults, ...JSON.parse(stored) } : defaults;
    }

    loadComponentSettings() {
        const stored = localStorage.getItem('preetidreams_visual_customizer_components');
        return stored ? JSON.parse(stored) : {};
    }

    saveSettings() {
        localStorage.setItem('preetidreams_visual_customizer_settings', JSON.stringify(this.settings));
    }

    saveComponentSettings() {
        localStorage.setItem('preetidreams_visual_customizer_components', JSON.stringify(this.componentSettings));
    }
}

// Initialize Phase 2 Visual Customizer
document.addEventListener('DOMContentLoaded', function() {
    // Create global function for admin bar trigger
    window.openThemeCustomizer = function() {
        if (window.visualCustomizer) {
            window.visualCustomizer.openPanel();
        } else {
            setTimeout(window.openThemeCustomizer, 100);
        }
    };

    // Initialize Phase 2 customizer
    if (window.visualCustomizerData) {
        window.visualCustomizer = new VisualCustomizerPhase2();
        window.VisualCustomizerPhase2 = VisualCustomizerPhase2;
    }
});
