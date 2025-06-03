/**
 * Visual Customizer System
 * Modern floating customizer with real-time visual options
 */

class VisualCustomizer {
    constructor() {
        console.log('VisualCustomizer: Initializing...');

        this.settings = {
            colorPalette: 'classic-forest',
            fontHeading: 'playfair-display',
            fontBody: 'inter',
            fontSize: 'normal',
            headerStyle: 'transparent',
            buttonStyle: 'rounded',
            animations: true
        };

        this.palettes = {
            'classic-forest': {
                name: 'Classic Forest',
                description: 'Sophisticated sage and navy palette',
                colors: {
                    primary: '#1B365D',
                    secondary: '#87A96B',
                    accent: '#D4AF37',
                    light: '#F8F9FA',
                    dark: '#2C3E50'
                }
            },
            'ocean-blue': {
                name: 'Ocean Blue',
                description: 'Calming blues with gold accents',
                colors: {
                    primary: '#0A4D68',
                    secondary: '#4A90A4',
                    accent: '#F4A261',
                    light: '#F7FAFC',
                    dark: '#2D3748'
                }
            },
            'rose-gold': {
                name: 'Rose Gold',
                description: 'Warm rose with elegant gold',
                colors: {
                    primary: '#8B4B72',
                    secondary: '#C4998D',
                    accent: '#E8B4B8',
                    light: '#FDF7F7',
                    dark: '#744C62'
                }
            },
            'sage-mint': {
                name: 'Sage Mint',
                description: 'Fresh sage with mint undertones',
                colors: {
                    primary: '#5D6A52',
                    secondary: '#9CAF88',
                    accent: '#B8C5A6',
                    light: '#F8FBF6',
                    dark: '#4A5441'
                }
            },
            'lavender-grey': {
                name: 'Lavender Grey',
                description: 'Soft lavender with sophisticated grey',
                colors: {
                    primary: '#6B5B73',
                    secondary: '#A69CAC',
                    accent: '#D6C9DC',
                    light: '#FAFBFC',
                    dark: '#5A4F61'
                }
            },
            'warm-earth': {
                name: 'Warm Earth',
                description: 'Rich earthy tones with copper',
                colors: {
                    primary: '#8B4513',
                    secondary: '#CD853F',
                    accent: '#DAA520',
                    light: '#FDF8F0',
                    dark: '#654321'
                }
            },
            'modern-monochrome': {
                name: 'Modern Monochrome',
                description: 'Sophisticated blacks and greys',
                colors: {
                    primary: '#2D3748',
                    secondary: '#4A5568',
                    accent: '#A0AEC0',
                    light: '#F7FAFC',
                    dark: '#1A202C'
                }
            }
        };

        this.fonts = {
            'playfair-display': {
                name: 'Playfair Display',
                family: '"Playfair Display", serif',
                category: 'serif',
                preview: 'Elegant & Sophisticated',
                sample: 'Perfect for luxury medical spa headings'
            },
            'inter': {
                name: 'Inter',
                family: '"Inter", sans-serif',
                category: 'sans-serif',
                preview: 'Modern & Clean',
                sample: 'Ideal for body text and interfaces'
            },
            'cormorant-garamond': {
                name: 'Cormorant Garamond',
                family: '"Cormorant Garamond", serif',
                category: 'serif',
                preview: 'Classic & Refined',
                sample: 'Timeless elegance for medical content'
            },
            'poppins': {
                name: 'Poppins',
                family: '"Poppins", sans-serif',
                category: 'sans-serif',
                preview: 'Friendly & Professional',
                sample: 'Approachable yet professional appearance'
            },
            'lora': {
                name: 'Lora',
                family: '"Lora", serif',
                category: 'serif',
                preview: 'Readable & Warm',
                sample: 'Comfortable reading for longer content'
            },
            'source-sans-pro': {
                name: 'Source Sans Pro',
                family: '"Source Sans Pro", sans-serif',
                category: 'sans-serif',
                preview: 'Professional & Clear',
                sample: 'Technical precision meets readability'
            }
        };

        this.init();
    }

    init() {
        console.log('VisualCustomizer: Starting initialization...');
        this.loadSettings();
        this.createTriggerButton();
        this.createDrawer();
        this.bindEvents();
        this.applyCurrentSettings();
        this.loadGoogleFonts();
        console.log('VisualCustomizer: Initialization complete');
    }

    loadSettings() {
        const saved = localStorage.getItem('medspaa_visual_customizer');
        if (saved) {
            this.settings = { ...this.settings, ...JSON.parse(saved) };
        }
        console.log('VisualCustomizer: Settings loaded', this.settings);
    }

    saveSettings() {
        localStorage.setItem('medspaa_visual_customizer', JSON.stringify(this.settings));
        this.showPreviewIndicator('Settings Saved');
        console.log('VisualCustomizer: Settings saved', this.settings);
    }

    createTriggerButton() {
        console.log('VisualCustomizer: Creating trigger button...');
        const button = document.createElement('button');
        button.className = 'visual-customizer-trigger';
        button.innerHTML = '🎨';
        button.setAttribute('aria-label', 'Open Visual Customizer');
        button.setAttribute('title', 'Customize Theme Appearance');

        // Append to body for absolute positioning
        document.body.appendChild(button);

        this.triggerButton = button;
        console.log('VisualCustomizer: Trigger button created and added to body');
    }

    createDrawer() {
        console.log('VisualCustomizer: Creating drawer...');
        const drawer = document.createElement('div');
        drawer.className = 'visual-customizer-drawer';
        drawer.innerHTML = this.getDrawerHTML();

        // Append to body for absolute positioning
        document.body.appendChild(drawer);

        this.drawer = drawer;
        console.log('VisualCustomizer: Drawer created and added to body');
    }

    getDrawerHTML() {
        return `
            <div class="customizer-header">
                <h2>Theme Customizer</h2>
                <p>Personalize your viewing experience</p>
                <button class="customizer-close" aria-label="Close Customizer">×</button>
            </div>

            <div class="customizer-content">
                ${this.getColorPalettesSection()}
                ${this.getFontsSection()}
                ${this.getStyleControlsSection()}
            </div>

            <button class="customizer-reset">Reset to Default</button>
        `;
    }

    getColorPalettesSection() {
        return `
            <div class="customizer-section">
                <button class="section-header" data-section="colors">
                    <h3 class="section-title">
                        <span class="section-icon">🎨</span>
                        Color Palettes
                    </h3>
                    <span class="section-arrow">▼</span>
                </button>
                <div class="section-content" data-content="colors">
                    <div class="section-body">
                        <div class="color-palettes-grid">
                            ${Object.entries(this.palettes).map(([key, palette]) => `
                                <div class="color-palette-option ${this.settings.colorPalette === key ? 'active' : ''}"
                                     data-palette="${key}">
                                    <div class="palette-name">${palette.name}</div>
                                    <div class="palette-description">${palette.description}</div>
                                    <div class="palette-colors">
                                        <div class="palette-color" style="background-color: ${palette.colors.primary}"></div>
                                        <div class="palette-color" style="background-color: ${palette.colors.secondary}"></div>
                                        <div class="palette-color" style="background-color: ${palette.colors.accent}"></div>
                                        <div class="palette-color" style="background-color: ${palette.colors.light}"></div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    getFontsSection() {
        return `
            <div class="customizer-section">
                <button class="section-header" data-section="fonts">
                    <h3 class="section-title">
                        <span class="section-icon">📝</span>
                        Typography
                    </h3>
                    <span class="section-arrow">▼</span>
                </button>
                <div class="section-content" data-content="fonts">
                    <div class="section-body">
                        <div class="style-control">
                            <label class="control-label">Heading Font</label>
                            <div class="font-options-grid">
                                ${Object.entries(this.fonts).filter(([_, font]) => font.category === 'serif').map(([key, font]) => `
                                    <div class="font-option ${this.settings.fontHeading === key ? 'active' : ''}"
                                         data-font="heading" data-value="${key}">
                                        <div class="font-preview" style="font-family: ${font.family}">${font.preview}</div>
                                        <div class="font-name">${font.name}</div>
                                        <div class="font-sample" style="font-family: ${font.family}">${font.sample}</div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>

                        <div class="style-control">
                            <label class="control-label">Body Font</label>
                            <div class="font-options-grid">
                                ${Object.entries(this.fonts).filter(([_, font]) => font.category === 'sans-serif').map(([key, font]) => `
                                    <div class="font-option ${this.settings.fontBody === key ? 'active' : ''}"
                                         data-font="body" data-value="${key}">
                                        <div class="font-preview" style="font-family: ${font.family}">${font.preview}</div>
                                        <div class="font-name">${font.name}</div>
                                        <div class="font-sample" style="font-family: ${font.family}">${font.sample}</div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>

                        <div class="style-control">
                            <label class="control-label">Font Size</label>
                            <div class="style-options">
                                <div class="style-option ${this.settings.fontSize === 'small' ? 'active' : ''}" data-setting="fontSize" data-value="small">Small</div>
                                <div class="style-option ${this.settings.fontSize === 'normal' ? 'active' : ''}" data-setting="fontSize" data-value="normal">Normal</div>
                                <div class="style-option ${this.settings.fontSize === 'large' ? 'active' : ''}" data-setting="fontSize" data-value="large">Large</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    getStyleControlsSection() {
        return `
            <div class="customizer-section">
                <button class="section-header" data-section="styles">
                    <h3 class="section-title">
                        <span class="section-icon">⚙️</span>
                        Style Controls
                    </h3>
                    <span class="section-arrow">▼</span>
                </button>
                <div class="section-content" data-content="styles">
                    <div class="section-body">
                        <div class="style-control">
                            <label class="control-label">Header Style</label>
                            <div class="style-options">
                                <div class="style-option ${this.settings.headerStyle === 'transparent' ? 'active' : ''}" data-setting="headerStyle" data-value="transparent">Transparent</div>
                                <div class="style-option ${this.settings.headerStyle === 'solid' ? 'active' : ''}" data-setting="headerStyle" data-value="solid">Solid</div>
                                <div class="style-option ${this.settings.headerStyle === 'gradient' ? 'active' : ''}" data-setting="headerStyle" data-value="gradient">Gradient</div>
                            </div>
                        </div>

                        <div class="style-control">
                            <label class="control-label">Button Style</label>
                            <div class="style-options">
                                <div class="style-option ${this.settings.buttonStyle === 'rounded' ? 'active' : ''}" data-setting="buttonStyle" data-value="rounded">Rounded</div>
                                <div class="style-option ${this.settings.buttonStyle === 'sharp' ? 'active' : ''}" data-setting="buttonStyle" data-value="sharp">Sharp</div>
                                <div class="style-option ${this.settings.buttonStyle === 'pill' ? 'active' : ''}" data-setting="buttonStyle" data-value="pill">Pill</div>
                            </div>
                        </div>

                        <div class="style-control">
                            <label class="control-label">Animations</label>
                            <div class="style-options">
                                <div class="style-option ${this.settings.animations ? 'active' : ''}" data-setting="animations" data-value="true">Enabled</div>
                                <div class="style-option ${!this.settings.animations ? 'active' : ''}" data-setting="animations" data-value="false">Disabled</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    bindEvents() {
        console.log('VisualCustomizer: Binding events...');

        // Trigger button
        this.triggerButton.addEventListener('click', () => {
            console.log('VisualCustomizer: Trigger button clicked');
            this.toggleDrawer();
        });

        // Close button
        this.drawer.querySelector('.customizer-close').addEventListener('click', () => {
            console.log('VisualCustomizer: Close button clicked');
            this.closeDrawer();
        });

        // Section headers
        this.drawer.querySelectorAll('.section-header').forEach(header => {
            header.addEventListener('click', (e) => this.toggleSection(e.target.closest('.section-header')));
        });

        // Color palette options
        this.drawer.querySelectorAll('.color-palette-option').forEach(option => {
            option.addEventListener('click', (e) => this.selectColorPalette(e.target.closest('.color-palette-option')));
        });

        // Font options
        this.drawer.querySelectorAll('.font-option').forEach(option => {
            option.addEventListener('click', (e) => this.selectFont(e.target.closest('.font-option')));
        });

        // Style options
        this.drawer.querySelectorAll('.style-option').forEach(option => {
            option.addEventListener('click', (e) => this.selectStyleOption(e.target.closest('.style-option')));
        });

        // Reset button
        this.drawer.querySelector('.customizer-reset').addEventListener('click', () => this.resetToDefaults());

        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!this.drawer.contains(e.target) && !this.triggerButton.contains(e.target) && this.drawer.classList.contains('open')) {
                this.closeDrawer();
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.drawer.classList.contains('open')) {
                this.closeDrawer();
            }
        });

        console.log('VisualCustomizer: Events bound successfully');
    }

    toggleDrawer() {
        const isOpen = this.drawer.classList.contains('open');
        console.log('VisualCustomizer: Toggle drawer called, currently open:', isOpen);
        if (isOpen) {
            this.closeDrawer();
        } else {
            this.openDrawer();
        }
    }

    openDrawer() {
        console.log('VisualCustomizer: Opening drawer...');
        this.drawer.classList.add('open');
        this.triggerButton.classList.add('active');
        document.body.style.overflow = 'hidden';

        // Open first section by default
        const firstSection = this.drawer.querySelector('.section-header');
        if (firstSection && !firstSection.classList.contains('active')) {
            this.toggleSection(firstSection);
        }
        console.log('VisualCustomizer: Drawer opened');
    }

    closeDrawer() {
        console.log('VisualCustomizer: Closing drawer...');
        this.drawer.classList.remove('open');
        this.triggerButton.classList.remove('active');
        document.body.style.overflow = '';
        console.log('VisualCustomizer: Drawer closed');
    }

    toggleSection(header) {
        const section = header.dataset.section;
        const content = this.drawer.querySelector(`[data-content="${section}"]`);
        const isActive = header.classList.contains('active');

        // Close all sections
        this.drawer.querySelectorAll('.section-header').forEach(h => h.classList.remove('active'));
        this.drawer.querySelectorAll('.section-content').forEach(c => c.classList.remove('active'));

        // Open clicked section if it wasn't active
        if (!isActive) {
            header.classList.add('active');
            content.classList.add('active');
        }
    }

    selectColorPalette(option) {
        const palette = option.dataset.palette;

        // Update UI
        this.drawer.querySelectorAll('.color-palette-option').forEach(opt => opt.classList.remove('active'));
        option.classList.add('active');

        // Update settings
        this.settings.colorPalette = palette;
        this.applyColorPalette();
        this.saveSettings();
        this.showPreviewIndicator(`Applied ${this.palettes[palette].name} palette`);
    }

    selectFont(option) {
        const fontType = option.dataset.font;
        const fontValue = option.dataset.value;
        const settingKey = fontType === 'heading' ? 'fontHeading' : 'fontBody';

        // Update UI
        option.parentElement.querySelectorAll('.font-option').forEach(opt => opt.classList.remove('active'));
        option.classList.add('active');

        // Update settings
        this.settings[settingKey] = fontValue;
        this.applyFonts();
        this.saveSettings();
        this.showPreviewIndicator(`Applied ${this.fonts[fontValue].name} font`);
    }

    selectStyleOption(option) {
        const setting = option.dataset.setting;
        const value = option.dataset.value;

        // Update UI
        option.parentElement.querySelectorAll('.style-option').forEach(opt => opt.classList.remove('active'));
        option.classList.add('active');

        // Update settings
        this.settings[setting] = value === 'true' ? true : value === 'false' ? false : value;
        this.applyStyleSettings();
        this.saveSettings();
        this.showPreviewIndicator(`Updated ${setting} setting`);
    }

    applyCurrentSettings() {
        this.applyColorPalette();
        this.applyFonts();
        this.applyStyleSettings();
    }

    applyColorPalette() {
        const palette = this.palettes[this.settings.colorPalette];
        const root = document.documentElement;

        Object.entries(palette.colors).forEach(([key, value]) => {
            root.style.setProperty(`--color-${key}`, value);
        });

        // Update CSS custom properties
        root.style.setProperty('--medspaa-primary', palette.colors.primary);
        root.style.setProperty('--medspaa-secondary', palette.colors.secondary);
        root.style.setProperty('--medspaa-accent', palette.colors.accent);
        root.style.setProperty('--medspaa-light', palette.colors.light);
        root.style.setProperty('--medspaa-dark', palette.colors.dark);
    }

    applyFonts() {
        const headingFont = this.fonts[this.settings.fontHeading];
        const bodyFont = this.fonts[this.settings.fontBody];
        const root = document.documentElement;

        root.style.setProperty('--font-heading', headingFont.family);
        root.style.setProperty('--font-body', bodyFont.family);

        // Apply font size scale
        const sizeMultiplier = {
            small: 0.9,
            normal: 1,
            large: 1.1
        }[this.settings.fontSize];

        root.style.setProperty('--font-size-multiplier', sizeMultiplier);
    }

    applyStyleSettings() {
        const root = document.documentElement;
        const body = document.body;

        // Header style
        body.classList.remove('header-transparent', 'header-solid', 'header-gradient');
        body.classList.add(`header-${this.settings.headerStyle}`);

        // Button style
        body.classList.remove('buttons-rounded', 'buttons-sharp', 'buttons-pill');
        body.classList.add(`buttons-${this.settings.buttonStyle}`);

        // Animations
        if (this.settings.animations) {
            body.classList.remove('no-animations');
        } else {
            body.classList.add('no-animations');
        }
    }

    loadGoogleFonts() {
        const fontsToLoad = new Set();
        Object.values(this.fonts).forEach(font => {
            if (font.family.includes('Playfair Display')) fontsToLoad.add('Playfair+Display:400,600,700');
            if (font.family.includes('Inter')) fontsToLoad.add('Inter:400,500,600,700');
            if (font.family.includes('Cormorant Garamond')) fontsToLoad.add('Cormorant+Garamond:400,600,700');
            if (font.family.includes('Poppins')) fontsToLoad.add('Poppins:400,500,600,700');
            if (font.family.includes('Lora')) fontsToLoad.add('Lora:400,600,700');
            if (font.family.includes('Source Sans Pro')) fontsToLoad.add('Source+Sans+Pro:400,600,700');
        });

        if (fontsToLoad.size > 0) {
            const link = document.createElement('link');
            link.href = `https://fonts.googleapis.com/css2?${Array.from(fontsToLoad).map(f => `family=${f}`).join('&')}&display=swap`;
            link.rel = 'stylesheet';
            document.head.appendChild(link);
        }
    }

    showPreviewIndicator(message) {
        let indicator = document.querySelector('.preview-indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.className = 'preview-indicator';
            document.body.appendChild(indicator);
        }

        indicator.textContent = message;
        indicator.classList.add('show');

        setTimeout(() => {
            indicator.classList.remove('show');
        }, 2000);
    }

    resetToDefaults() {
        this.settings = {
            colorPalette: 'classic-forest',
            fontHeading: 'playfair-display',
            fontBody: 'inter',
            fontSize: 'normal',
            headerStyle: 'transparent',
            buttonStyle: 'rounded',
            animations: true
        };

        // Update drawer UI
        this.drawer.innerHTML = this.getDrawerHTML();
        this.bindDrawerEvents();

        this.applyCurrentSettings();
        this.saveSettings();
        this.showPreviewIndicator('Reset to default settings');
    }

    bindDrawerEvents() {
        // Re-bind events for new content
        this.drawer.querySelectorAll('.section-header').forEach(header => {
            header.addEventListener('click', (e) => this.toggleSection(e.target.closest('.section-header')));
        });

        this.drawer.querySelectorAll('.color-palette-option').forEach(option => {
            option.addEventListener('click', (e) => this.selectColorPalette(e.target.closest('.color-palette-option')));
        });

        this.drawer.querySelectorAll('.font-option').forEach(option => {
            option.addEventListener('click', (e) => this.selectFont(e.target.closest('.font-option')));
        });

        this.drawer.querySelectorAll('.style-option').forEach(option => {
            option.addEventListener('click', (e) => this.selectStyleOption(e.target.closest('.style-option')));
        });

        this.drawer.querySelector('.customizer-reset').addEventListener('click', () => this.resetToDefaults());
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    console.log('VisualCustomizer: DOM Content Loaded, initializing...');

    // Check if reduced motion is preferred
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion) {
        document.body.classList.add('no-animations');
    }

    // Initialize visual customizer
    console.log('VisualCustomizer: Creating new instance...');
    window.visualCustomizer = new VisualCustomizer();
    console.log('VisualCustomizer: Instance created and assigned to window.visualCustomizer');

    // Add global CSS for customizer variables
    const style = document.createElement('style');
    style.textContent = `
        :root {
            --customizer-z-index: 9999;
            --customizer-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .no-animations * {
            animation-duration: 0s !important;
            transition-duration: 0s !important;
        }

        .header-transparent .professional-header {
            background: transparent;
        }

        .header-solid .professional-header {
            background: var(--color-primary, #1B365D);
        }

        .header-gradient .professional-header {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
        }

        .buttons-rounded button,
        .buttons-rounded .btn {
            border-radius: 8px;
        }

        .buttons-sharp button,
        .buttons-sharp .btn {
            border-radius: 0;
        }

        .buttons-pill button,
        .buttons-pill .btn {
            border-radius: 50px;
        }

        body {
            font-size: calc(1rem * var(--font-size-multiplier, 1));
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-heading, 'Playfair Display', serif);
        }

        body, p, span, div, li, td {
            font-family: var(--font-body, 'Inter', sans-serif);
        }
    `;
    document.head.appendChild(style);
    console.log('VisualCustomizer: Global styles added');
});

// Export for potential external use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = VisualCustomizer;
}
