/**
 * Visual Customizer System - Phase 2 Advanced
 * Modern floating customizer with real-time visual options
 * Phase 1: Medical Spa color palettes, preview thumbnails, save/load functionality
 * Phase 2: Component-level theming, advanced color science, gradient systems
 */

class VisualCustomizer {
    constructor() {
        console.log('VisualCustomizer: Initializing Phase 2 Advanced Version...');

        // Check if user has admin access
        this.isAdmin = visualCustomizerData.isAdmin || false;
        this.adminOnlyMode = visualCustomizerData.settings.adminOnlyMode || false;
        this.globalConfig = visualCustomizerData.globalConfig || {};

        // If not admin and in admin-only mode, don't initialize
        if (this.adminOnlyMode && !this.isAdmin) {
            console.log('VisualCustomizer: Admin-only mode - not initializing for non-admin user');
            return;
        }

        this.settings = {
            colorPalette: 'classic-forest',
            fontHeading: 'playfair-display',
            fontBody: 'inter',
            fontSize: 'normal',
            headerStyle: 'transparent',
            buttonStyle: 'rounded',
            animations: true,
            // NEW PHASE 2: Component-Level Settings
            componentSettings: {
                header: {
                    style: 'transparent',
                    background: 'default',
                    pattern: 'none',
                    opacity: 1.0
                },
                footer: {
                    layout: 'default',
                    background: 'default',
                    contentArrangement: 'standard'
                },
                buttons: {
                    style: 'rounded',
                    effect: 'none',
                    interaction: 'hover'
                },
                cards: {
                    design: 'modern',
                    shadow: 'medium',
                    borderRadius: 'rounded'
                },
                navigation: {
                    mobileStyle: 'hamburger',
                    desktopStyle: 'inline',
                    position: 'top'
                },
                forms: {
                    style: 'modern',
                    fieldStyle: 'outlined',
                    buttonAlignment: 'center'
                }
            }
        };

        // Enhanced medical spa color palettes
        this.palettes = {
            'classic-forest': {
                name: 'Classic Forest',
                description: 'Sophisticated sage and navy palette',
                category: 'classic',
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
                category: 'classic',
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
                category: 'classic',
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
                category: 'classic',
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
                category: 'classic',
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
                category: 'classic',
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
                category: 'classic',
                colors: {
                    primary: '#2D3748',
                    secondary: '#4A5568',
                    accent: '#A0AEC0',
                    light: '#F7FAFC',
                    dark: '#1A202C'
                }
            },
            // NEW MEDICAL SPA PALETTES - Phase 1
            'spa-serenity': {
                name: 'Spa Serenity',
                description: 'Calming spa blues with pearl accents',
                category: 'medical-spa',
                colors: {
                    primary: '#2E5266',
                    secondary: '#7FB3D3',
                    accent: '#E8F4F8',
                    light: '#F9FDFF',
                    dark: '#1C3D4A'
                }
            },
            'wellness-green': {
                name: 'Wellness Green',
                description: 'Natural healing greens with gold',
                category: 'medical-spa',
                colors: {
                    primary: '#2D5016',
                    secondary: '#6B8E23',
                    accent: '#C7B377',
                    light: '#F7F9F4',
                    dark: '#1A2F0C'
                }
            },
            'clinical-elegance': {
                name: 'Clinical Elegance',
                description: 'Medical white with trust-building blue',
                category: 'medical-spa',
                colors: {
                    primary: '#1E3A8A',
                    secondary: '#60A5FA',
                    accent: '#F1F5F9',
                    light: '#FFFFFF',
                    dark: '#0F172A'
                }
            },
            'therapeutic-rose': {
                name: 'Therapeutic Rose',
                description: 'Soft healing rose with cream',
                category: 'medical-spa',
                colors: {
                    primary: '#9F1239',
                    secondary: '#F43F5E',
                    accent: '#FDF2F8',
                    light: '#FFFBFE',
                    dark: '#4C0519'
                }
            },
            'rejuvenation-purple': {
                name: 'Rejuvenation Purple',
                description: 'Luxurious purple with silver accents',
                category: 'medical-spa',
                colors: {
                    primary: '#581C87',
                    secondary: '#A855F7',
                    accent: '#F3E8FF',
                    light: '#FEFAFF',
                    dark: '#2E1065'
                }
            },
            'holistic-teal': {
                name: 'Holistic Teal',
                description: 'Balanced teal with warm undertones',
                category: 'medical-spa',
                colors: {
                    primary: '#134E4A',
                    secondary: '#14B8A6',
                    accent: '#CCFBF1',
                    light: '#F0FDFA',
                    dark: '#042F2E'
                }
            },
            'premium-bronze': {
                name: 'Premium Bronze',
                description: 'Sophisticated bronze with cream luxury',
                category: 'medical-spa',
                colors: {
                    primary: '#92400E',
                    secondary: '#D97706',
                    accent: '#FEF3C7',
                    light: '#FFFBEB',
                    dark: '#451A03'
                }
            },
            'platinum-spa': {
                name: 'Platinum Spa',
                description: 'High-end platinum with pearl finishes',
                category: 'medical-spa',
                colors: {
                    primary: '#374151',
                    secondary: '#9CA3AF',
                    accent: '#F9FAFB',
                    light: '#FFFFFF',
                    dark: '#111827'
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

        // NEW PHASE 2: Component Theming Definitions
        this.componentDefinitions = {
            header: {
                name: 'Header',
                icon: '🏠',
                description: 'Main navigation and site header',
                options: {
                    style: {
                        name: 'Header Style',
                        options: {
                            'transparent': 'Transparent',
                            'solid': 'Solid Color',
                            'gradient': 'Gradient',
                            'blur': 'Glass Effect'
                        }
                    },
                    background: {
                        name: 'Background',
                        options: {
                            'default': 'Theme Default',
                            'custom': 'Custom Color',
                            'pattern': 'Pattern Overlay'
                        }
                    },
                    pattern: {
                        name: 'Pattern',
                        options: {
                            'none': 'None',
                            'dots': 'Subtle Dots',
                            'lines': 'Fine Lines',
                            'waves': 'Wave Pattern'
                        }
                    }
                }
            },
            footer: {
                name: 'Footer',
                icon: '📍',
                description: 'Site footer with contact and links',
                options: {
                    layout: {
                        name: 'Layout Style',
                        options: {
                            'default': 'Standard Layout',
                            'centered': 'Centered Content',
                            'split': 'Split Columns',
                            'minimal': 'Minimal Design'
                        }
                    },
                    background: {
                        name: 'Background Style',
                        options: {
                            'default': 'Theme Default',
                            'dark': 'Dark Mode',
                            'gradient': 'Gradient Background',
                            'textured': 'Textured Surface'
                        }
                    }
                }
            },
            buttons: {
                name: 'Buttons',
                icon: '🔘',
                description: 'Site-wide button styling',
                options: {
                    style: {
                        name: 'Button Style',
                        options: {
                            'rounded': 'Rounded Corners',
                            'sharp': 'Sharp Edges',
                            'pill': 'Pill Shape',
                            'outlined': 'Outlined Style'
                        }
                    },
                    effect: {
                        name: 'Visual Effects',
                        options: {
                            'none': 'None',
                            'shadow': 'Drop Shadow',
                            'glow': 'Soft Glow',
                            'gradient': 'Gradient Fill'
                        }
                    },
                    interaction: {
                        name: 'Hover Interaction',
                        options: {
                            'hover': 'Color Change',
                            'lift': 'Lift Effect',
                            'scale': 'Scale Transform',
                            'slide': 'Slide Animation'
                        }
                    }
                }
            },
            cards: {
                name: 'Cards',
                icon: '🃏',
                description: 'Treatment and content cards',
                options: {
                    design: {
                        name: 'Card Design',
                        options: {
                            'modern': 'Modern Clean',
                            'elegant': 'Elegant Classic',
                            'minimal': 'Minimal Style',
                            'luxury': 'Luxury Premium'
                        }
                    },
                    shadow: {
                        name: 'Shadow Effect',
                        options: {
                            'none': 'No Shadow',
                            'light': 'Light Shadow',
                            'medium': 'Medium Shadow',
                            'heavy': 'Heavy Shadow'
                        }
                    },
                    borderRadius: {
                        name: 'Corner Radius',
                        options: {
                            'none': 'Sharp Corners',
                            'small': 'Slightly Rounded',
                            'rounded': 'Rounded',
                            'large': 'Very Rounded'
                        }
                    }
                }
            },
            navigation: {
                name: 'Navigation',
                icon: '🧭',
                description: 'Site navigation menu',
                options: {
                    mobileStyle: {
                        name: 'Mobile Menu',
                        options: {
                            'hamburger': 'Hamburger Menu',
                            'slide': 'Slide-out Drawer',
                            'overlay': 'Full Overlay',
                            'tabs': 'Tab Navigation'
                        }
                    },
                    desktopStyle: {
                        name: 'Desktop Menu',
                        options: {
                            'inline': 'Inline Menu',
                            'dropdown': 'Dropdown Style',
                            'mega': 'Mega Menu',
                            'sidebar': 'Sidebar Menu'
                        }
                    }
                }
            },
            forms: {
                name: 'Forms',
                icon: '📝',
                description: 'Contact and consultation forms',
                options: {
                    style: {
                        name: 'Form Style',
                        options: {
                            'modern': 'Modern Design',
                            'classic': 'Classic Style',
                            'minimal': 'Minimal Clean',
                            'medical': 'Medical Professional'
                        }
                    },
                    fieldStyle: {
                        name: 'Field Style',
                        options: {
                            'outlined': 'Outlined Fields',
                            'filled': 'Filled Background',
                            'underlined': 'Underlined Only',
                            'floating': 'Floating Labels'
                        }
                    }
                }
            }
        };

        this.init();
    }

    init() {
        console.log('VisualCustomizer: Initializing Phase 2 system...');

        // Load Phase 2 CSS
        this.loadPhase2Styles();

        // Load existing settings
        this.loadSettings();

        // Create UI elements
        this.createTriggerButton();
        this.createDrawer();

        // Setup event handlers
        this.bindEvents();

        // Apply current settings
        this.applyCurrentSettings();

        // Load Google Fonts
        this.loadGoogleFonts();

        // Initialize component preview
        this.initializeComponentPreview();

        // NEW: Load Global Configuration on Init (Admin Only)
        this.loadGlobalConfiguration();

        console.log('VisualCustomizer: Phase 2 initialization complete');
    }

    // NEW PHASE 2: Load Phase 2 Stylesheet
    loadPhase2Styles() {
        const existingPhase2 = document.querySelector('link[data-customizer-phase2]');
        if (!existingPhase2) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = '/wp-content/themes/medSpaTheme/assets/css/customizer-phase2.css';
            link.setAttribute('data-customizer-phase2', 'true');
            document.head.appendChild(link);
            console.log('VisualCustomizer: Phase 2 CSS loaded');
        }
    }

    loadSettings() {
        // In admin-only mode, don't load from localStorage
        if (this.adminOnlyMode && this.isAdmin) {
            console.log('VisualCustomizer: Admin-only mode - skipping localStorage load');
            return;
        }

        const saved = localStorage.getItem('medspaa_visual_customizer');
        if (saved) {
            this.settings = { ...this.settings, ...JSON.parse(saved) };
        }
        console.log('VisualCustomizer: Settings loaded', this.settings);
    }

    saveSettings() {
        // In admin-only mode, don't save to localStorage (only preview changes)
        if (this.adminOnlyMode && this.isAdmin) {
            console.log('VisualCustomizer: Admin-only mode - settings not saved to localStorage (preview only)');
            this.showPreviewIndicator('Preview Mode - Use "Apply Globally" to save');
            return;
        }

        localStorage.setItem('medspaa_visual_customizer', JSON.stringify(this.settings));
        this.showPreviewIndicator('Settings Saved');
        console.log('VisualCustomizer: Settings saved', this.settings);
    }

    // NEW: Save/Load Configuration Methods
    exportConfiguration() {
        const config = {
            settings: this.settings,
            timestamp: new Date().toISOString(),
            version: '1.1.0-phase1'
        };

        const blob = new Blob([JSON.stringify(config, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `medspaa-customizer-config-${Date.now()}.json`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);

        this.showPreviewIndicator('Configuration Exported');
    }

    importConfiguration(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            try {
                const config = JSON.parse(e.target.result);
                if (config.settings && this.validateConfiguration(config.settings)) {
                    this.settings = { ...this.settings, ...config.settings };
                    this.saveSettings();
                    this.applyCurrentSettings();
                    this.updateUI();
                    this.showPreviewIndicator('Configuration Imported Successfully');
                } else {
                    throw new Error('Invalid configuration format');
                }
            } catch (error) {
                console.error('Configuration import failed:', error);
                this.showPreviewIndicator('Configuration Import Failed', 'error');
            }
        };
        reader.readAsText(file);
    }

    validateConfiguration(settings) {
        const requiredKeys = ['colorPalette', 'fontHeading', 'fontBody'];
        return requiredKeys.every(key => settings.hasOwnProperty(key));
    }

    // NEW: Generate Preview Thumbnail
    generatePaletteThumbnail(paletteKey) {
        const palette = this.palettes[paletteKey];
        if (!palette) return '';

        return `
            <div class="palette-thumbnail">
                <div class="color-swatch" style="background-color: ${palette.colors.primary}"></div>
                <div class="color-swatch" style="background-color: ${palette.colors.secondary}"></div>
                <div class="color-swatch" style="background-color: ${palette.colors.accent}"></div>
                <div class="color-swatch" style="background-color: ${palette.colors.light}"></div>
            </div>
        `;
    }

    createTriggerButton() {
        console.log('VisualCustomizer: Creating trigger button...');
        const button = document.createElement('button');
        button.className = 'visual-customizer-trigger';

        // Add admin-mode class if user is admin
        if (this.isAdmin) {
            button.classList.add('admin-mode');
        }

        button.innerHTML = '🎨';
        button.setAttribute('aria-label', this.isAdmin ? 'Open Visual Customizer (Admin Mode)' : 'Open Visual Customizer');
        button.setAttribute('title', this.isAdmin ? 'Customize Theme Appearance (Admin Only)' : 'Customize Theme Appearance');

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
        // Default open section for admin users should be Configuration
        const defaultOpenSection = this.isAdmin ? 'config' : 'colors';

        return `
            <div class="visual-customizer-drawer">
                <div class="customizer-header">
                    <h2 class="customizer-title">${this.isAdmin ? '👑 Admin Visual Customizer' : '🎨 Visual Customizer'}</h2>
                    <button class="customizer-close" aria-label="Close customizer">&times;</button>
                </div>
                <div class="customizer-content">
                    ${this.getConfigurationSection()}
                    ${this.getColorPalettesSection()}
                    ${this.getFontsSection()}
                    ${this.getStyleControlsSection()}
                </div>
                <div class="customizer-footer">
                    <button class="customizer-reset" data-confirm="true">Reset to Default</button>
                    <div class="version-badge">v2.0</div>
                </div>
            </div>
            <script>
                // Auto-open the default section for admin users
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(() => {
                        const defaultHeader = document.querySelector('[data-section="${defaultOpenSection}"]');
                        if (defaultHeader && !defaultHeader.classList.contains('active')) {
                            defaultHeader.click();
                        }
                    }, 100);
                });
            </script>
        `;
    }

    // NEW: Configuration Save/Load Section
    getConfigurationSection() {
        const adminControls = this.isAdmin ? `
            <div class="admin-controls">
                <div class="admin-notice">
                    <span class="admin-icon">👑</span>
                    ${visualCustomizerData.i18n.adminOnlyNotice}
                </div>
                <button class="config-btn apply-global-config" type="button">
                    <span class="btn-icon">🌍</span>
                    ${visualCustomizerData.i18n.applyGlobally}
                </button>
                <div class="global-config-info">
                    <small>Apply current settings for all website visitors</small>
                </div>
            </div>
        ` : '';

        return `
            <div class="customizer-section">
                <button class="section-header" data-section="config">
                    <h3 class="section-title">
                        <span class="section-icon">💾</span>
                        Configuration ${this.isAdmin ? '<span class="admin-badge">Admin</span>' : ''}
                    </h3>
                    <span class="section-arrow">▼</span>
                </button>
                <div class="section-content" data-content="config">
                    <div class="section-body">
                        ${adminControls}
                        <div class="config-controls">
                            <button class="config-btn export-config" type="button">
                                <span class="btn-icon">📥</span>
                                Export Settings
                            </button>
                            <div class="import-config-wrapper">
                                <input type="file" id="import-config" accept=".json" class="import-config-input" hidden>
                                <button class="config-btn import-config-btn" type="button">
                                    <span class="btn-icon">📤</span>
                                    Import Settings
                                </button>
                            </div>
                        </div>
                        <div class="config-info">
                            <small>Save and share your customization preferences</small>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    getColorPalettesSection() {
        // Group palettes by category
        const classicPalettes = Object.entries(this.palettes).filter(([_, palette]) => palette.category === 'classic');
        const medicalSpaPalettes = Object.entries(this.palettes).filter(([_, palette]) => palette.category === 'medical-spa');

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
                        <!-- Medical Spa Palettes -->
                        <div class="palette-category">
                            <h4 class="category-title">
                                <span class="category-icon">🏥</span>
                                Medical Spa Professional
                            </h4>
                            <div class="color-palettes-grid">
                                ${medicalSpaPalettes.map(([key, palette]) => `
                                    <div class="color-palette-option ${this.settings.colorPalette === key ? 'active' : ''}"
                                         data-palette="${key}">
                                        <div class="palette-header">
                                            <div class="palette-name">${palette.name}</div>
                                            ${this.generatePaletteThumbnail(key)}
                                        </div>
                                        <div class="palette-description">${palette.description}</div>
                                        <div class="palette-colors">
                                            <div class="palette-color" style="background-color: ${palette.colors.primary}" title="Primary"></div>
                                            <div class="palette-color" style="background-color: ${palette.colors.secondary}" title="Secondary"></div>
                                            <div class="palette-color" style="background-color: ${palette.colors.accent}" title="Accent"></div>
                                            <div class="palette-color" style="background-color: ${palette.colors.light}" title="Light"></div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>

                        <!-- Classic Palettes -->
                        <div class="palette-category">
                            <h4 class="category-title">
                                <span class="category-icon">🎭</span>
                                Classic Collection
                            </h4>
                            <div class="color-palettes-grid">
                                ${classicPalettes.map(([key, palette]) => `
                                    <div class="color-palette-option ${this.settings.colorPalette === key ? 'active' : ''}"
                                         data-palette="${key}">
                                        <div class="palette-header">
                                            <div class="palette-name">${palette.name}</div>
                                            ${this.generatePaletteThumbnail(key)}
                                        </div>
                                        <div class="palette-description">${palette.description}</div>
                                        <div class="palette-colors">
                                            <div class="palette-color" style="background-color: ${palette.colors.primary}" title="Primary"></div>
                                            <div class="palette-color" style="background-color: ${palette.colors.secondary}" title="Secondary"></div>
                                            <div class="palette-color" style="background-color: ${palette.colors.accent}" title="Accent"></div>
                                            <div class="palette-color" style="background-color: ${palette.colors.light}" title="Light"></div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
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

    // NEW PHASE 2: Component Theming Section
    getComponentThemingSection() {
        return `
            <div class="component-theming-section">
                <div class="component-grid">
                    ${Object.entries(this.componentDefinitions).map(([componentKey, component]) => `
                        <div class="component-card" data-component="${componentKey}">
                            <div class="component-header">
                                <span class="component-icon">${component.icon}</span>
                                <div class="component-info">
                                    <h4 class="component-name">${component.name}</h4>
                                    <p class="component-description">${component.description}</p>
                                </div>
                                <button class="component-expand" data-component="${componentKey}">
                                    <span class="expand-icon">⚙️</span>
                                </button>
                            </div>

                            <div class="component-controls" data-component-controls="${componentKey}">
                                ${Object.entries(component.options).map(([optionKey, option]) => `
                                    <div class="component-option">
                                        <label class="option-label">${option.name}</label>
                                        <div class="option-controls">
                                            ${Object.entries(option.options).map(([valueKey, valueName]) => `
                                                <button class="option-button ${this.settings.componentSettings[componentKey]?.[optionKey] === valueKey ? 'active' : ''}"
                                                        data-component="${componentKey}"
                                                        data-option="${optionKey}"
                                                        data-value="${valueKey}">
                                                    ${valueName}
                                                </button>
                                            `).join('')}
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `).join('')}
                </div>

                <!-- Component Preview Area -->
                <div class="component-preview-area">
                    <div class="preview-header">
                        <h4>Component Preview</h4>
                        <div class="preview-mode-selector">
                            <button class="preview-mode active" data-mode="desktop">
                                <span class="mode-icon">🖥️</span>
                                Desktop
                            </button>
                            <button class="preview-mode" data-mode="tablet">
                                <span class="mode-icon">📱</span>
                                Tablet
                            </button>
                            <button class="preview-mode" data-mode="mobile">
                                <span class="mode-icon">📱</span>
                                Mobile
                            </button>
                        </div>
                    </div>

                    <div class="component-preview-frame" id="component-preview">
                        <div class="preview-loading">
                            <span class="loading-icon">⏳</span>
                            Select a component to preview
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    bindEvents() {
        console.log('VisualCustomizer: Binding Phase 2 events...');

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

        // NEW PHASE 2: Tab Navigation
        this.drawer.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', (e) => {
                const tabName = e.target.closest('.tab-button').dataset.tab;
                this.switchTab(tabName);
            });
        });

        // NEW PHASE 2: Component Theming Events
        this.drawer.querySelectorAll('.component-expand').forEach(button => {
            button.addEventListener('click', (e) => {
                const componentKey = e.target.closest('.component-expand').dataset.component;
                this.toggleComponentControls(componentKey);
            });
        });

        this.drawer.querySelectorAll('.option-button').forEach(button => {
            button.addEventListener('click', (e) => this.selectComponentOption(e.target.closest('.option-button')));
        });

        // NEW PHASE 2: Preview Mode Selection
        this.drawer.querySelectorAll('.preview-mode').forEach(button => {
            button.addEventListener('click', (e) => {
                const mode = e.target.closest('.preview-mode').dataset.mode;
                this.setPreviewMode(mode);
            });
        });

        // Section headers (for backward compatibility)
        this.drawer.querySelectorAll('.section-header').forEach(header => {
            header.addEventListener('click', (e) => this.toggleSection(e.target.closest('.section-header')));
        });

        // Configuration export/import handlers
        const exportBtn = this.drawer.querySelector('.export-config');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => {
                console.log('VisualCustomizer: Export configuration clicked');
                this.exportConfiguration();
            });
        }

        const importBtn = this.drawer.querySelector('.import-config-btn');
        const importInput = this.drawer.querySelector('.import-config-input');
        if (importBtn && importInput) {
            importBtn.addEventListener('click', () => {
                console.log('VisualCustomizer: Import configuration clicked');
                importInput.click();
            });

            importInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    console.log('VisualCustomizer: Configuration file selected', file.name);
                    this.importConfiguration(file);
                    e.target.value = '';
                }
            });
        }

        // Admin-only Apply Globally handler
        const applyGlobalBtn = this.drawer.querySelector('.apply-global-config');
        if (applyGlobalBtn && this.isAdmin) {
            applyGlobalBtn.addEventListener('click', () => {
                console.log('VisualCustomizer: Apply globally clicked');
                this.applyConfigurationGlobally();
            });
        }

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

        console.log('VisualCustomizer: Phase 2 events bound successfully');
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

        // Auto-open Configuration section for admin users, Colors for others
        const defaultSection = this.isAdmin ? 'config' : 'colors';
        const defaultHeader = this.drawer.querySelector(`[data-section="${defaultSection}"]`);
        if (defaultHeader && !defaultHeader.classList.contains('active')) {
            setTimeout(() => {
                this.toggleSection(defaultHeader);
            }, 100);
        }

        console.log('VisualCustomizer: Drawer opened' + (this.isAdmin ? ' (Admin mode - Configuration section auto-opened)' : ''));
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
        console.log('VisualCustomizer: Applying current settings (Phase 2)...', this.settings);

        // Apply color palette
        this.applyColorPalette();

        // Apply fonts
        this.applyFonts();

        // Apply style settings
        this.applyStyleSettings();

        // NEW PHASE 2: Apply component theming
        this.applyAllComponentTheming();

        console.log('VisualCustomizer: All Phase 2 settings applied successfully');
    }

    // NEW PHASE 2: Apply All Component Theming
    applyAllComponentTheming() {
        if (this.settings.componentSettings) {
            Object.keys(this.settings.componentSettings).forEach(componentKey => {
                this.applyComponentTheming(componentKey);
            });
            console.log('VisualCustomizer: All component theming applied');
        }
    }

    applyColorPalette() {
        const palette = this.palettes[this.settings.colorPalette];
        const root = document.documentElement;

        // Apply visual customizer variables (for visual-customizer.css)
        Object.entries(palette.colors).forEach(([key, value]) => {
            root.style.setProperty(`--color-${key}`, value);
        });

        // Map to existing theme CSS custom properties
        root.style.setProperty('--color-primary-forest', palette.colors.primary);
        root.style.setProperty('--color-primary-navy', palette.colors.primary);
        root.style.setProperty('--color-primary-teal', palette.colors.primary);
        root.style.setProperty('--color-primary-blue', palette.colors.primary);

        root.style.setProperty('--color-primary-sage', palette.colors.secondary);
        root.style.setProperty('--color-secondary-sage', palette.colors.secondary);
        root.style.setProperty('--color-secondary-mint', palette.colors.secondary);

        root.style.setProperty('--color-primary-gold', palette.colors.accent);
        root.style.setProperty('--color-secondary-peach', palette.colors.accent);
        root.style.setProperty('--color-accent-coral', palette.colors.accent);

        root.style.setProperty('--color-neutral-white', palette.colors.light);
        root.style.setProperty('--color-white', palette.colors.light);
        root.style.setProperty('--color-soft-gray', palette.colors.light);
        root.style.setProperty('--color-neutral-light', palette.colors.light);

        root.style.setProperty('--color-neutral-dark', palette.colors.dark);
        root.style.setProperty('--color-charcoal', palette.colors.dark);

        // Footer-specific variables
        root.style.setProperty('--sage-green', palette.colors.secondary);
        root.style.setProperty('--premium-gold', palette.colors.accent);
        root.style.setProperty('--medical-navy', palette.colors.primary);
        root.style.setProperty('--cream-base', palette.colors.light);
        root.style.setProperty('--sage-light', palette.colors.secondary);
        root.style.setProperty('--sage-dark', palette.colors.secondary);
        root.style.setProperty('--gold-light', palette.colors.accent);
        root.style.setProperty('--navy-light', palette.colors.primary);
        root.style.setProperty('--cream-warm', palette.colors.light);
        root.style.setProperty('--navy-deep', palette.colors.primary);

        // RGB values for transparent backgrounds
        const primaryRgb = this.hexToRgb(palette.colors.primary);
        const secondaryRgb = this.hexToRgb(palette.colors.secondary);
        const accentRgb = this.hexToRgb(palette.colors.accent);

        if (primaryRgb) {
            root.style.setProperty('--color-primary-rgb', `${primaryRgb.r}, ${primaryRgb.g}, ${primaryRgb.b}`);
        }
        if (secondaryRgb) {
            root.style.setProperty('--color-secondary-rgb', `${secondaryRgb.r}, ${secondaryRgb.g}, ${secondaryRgb.b}`);
        }
        if (accentRgb) {
            root.style.setProperty('--color-accent-rgb', `${accentRgb.r}, ${accentRgb.g}, ${accentRgb.b}`);
        }

        // Update gradient combinations to use new colors
        root.style.setProperty('--gradient-primary', `linear-gradient(135deg, ${palette.colors.primary} 0%, ${palette.colors.secondary} 100%)`);
        root.style.setProperty('--gradient-accent', `linear-gradient(135deg, ${palette.colors.accent} 0%, ${palette.colors.primary} 100%)`);
        root.style.setProperty('--gradient-luxury', `linear-gradient(135deg, ${palette.colors.secondary} 0%, ${palette.colors.primary} 100%)`);
        root.style.setProperty('--gradient-fresh', `linear-gradient(135deg, ${palette.colors.secondary} 0%, ${palette.colors.primary} 100%)`);

        // Legacy compatibility variables
        root.style.setProperty('--medspaa-primary', palette.colors.primary);
        root.style.setProperty('--medspaa-secondary', palette.colors.secondary);
        root.style.setProperty('--medspaa-accent', palette.colors.accent);
        root.style.setProperty('--medspaa-light', palette.colors.light);
        root.style.setProperty('--medspaa-dark', palette.colors.dark);

        console.log('VisualCustomizer: Color palette applied', palette.name, palette.colors);
    }

    // Helper function to convert hex to RGB
    hexToRgb(hex) {
        const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }

    applyFonts() {
        const headingFont = this.fonts[this.settings.fontHeading];
        const bodyFont = this.fonts[this.settings.fontBody];
        const root = document.documentElement;

        // Apply to visual customizer variables
        root.style.setProperty('--font-heading', headingFont.family);
        root.style.setProperty('--font-body', bodyFont.family);

        // Map to existing theme CSS custom properties
        root.style.setProperty('--font-primary', headingFont.family);
        root.style.setProperty('--font-secondary', bodyFont.family);
        root.style.setProperty('--font-accent', headingFont.family);

        // Apply font size scale
        const sizeMultiplier = {
            small: 0.9,
            normal: 1,
            large: 1.1
        }[this.settings.fontSize];

        root.style.setProperty('--font-size-multiplier', sizeMultiplier);

        console.log('VisualCustomizer: Fonts applied', {
            heading: headingFont.name,
            body: bodyFont.name,
            sizeMultiplier
        });
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

    showPreviewIndicator(message, type = 'info') {
        let indicator = document.querySelector('.customizer-preview-indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.className = 'customizer-preview-indicator';

            // Add admin-mode class if user is admin
            if (this.isAdmin) {
                indicator.classList.add('admin-mode');
            }

            document.body.appendChild(indicator);
        }

        // Remove existing type classes
        indicator.classList.remove('success', 'error', 'warning');

        // Add type class
        if (type !== 'info') {
            indicator.classList.add(type);
        }

        indicator.textContent = message;
        indicator.classList.add('show');

        // Auto-hide after delay (longer for errors)
        const hideDelay = type === 'error' ? 4000 : type === 'success' ? 3000 : 2000;
        setTimeout(() => {
            indicator.classList.remove('show');
        }, hideDelay);
    }

    resetToDefaults() {
        this.settings = {
            colorPalette: 'classic-forest',
            fontHeading: 'playfair-display',
            fontBody: 'inter',
            fontSize: 'normal',
            headerStyle: 'transparent',
            buttonStyle: 'rounded',
            animations: true,
            // NEW PHASE 2: Component-Level Settings
            componentSettings: {
                header: {
                    style: 'transparent',
                    background: 'default',
                    pattern: 'none',
                    opacity: 1.0
                },
                footer: {
                    layout: 'default',
                    background: 'default',
                    contentArrangement: 'standard'
                },
                buttons: {
                    style: 'rounded',
                    effect: 'none',
                    interaction: 'hover'
                },
                cards: {
                    design: 'modern',
                    shadow: 'medium',
                    borderRadius: 'rounded'
                },
                navigation: {
                    mobileStyle: 'hamburger',
                    desktopStyle: 'inline',
                    position: 'top'
                },
                forms: {
                    style: 'modern',
                    fieldStyle: 'outlined',
                    buttonAlignment: 'center'
                }
            }
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

    // NEW: Update UI elements to reflect current settings
    updateUI() {
        console.log('VisualCustomizer: Updating UI elements...');

        // Update active palette
        this.drawer.querySelectorAll('.color-palette-option').forEach(option => {
            option.classList.toggle('active', option.dataset.palette === this.settings.colorPalette);
        });

        // Update active fonts
        this.drawer.querySelectorAll('.font-option').forEach(option => {
            const fontType = option.dataset.font;
            const fontValue = option.dataset.value;
            const settingKey = fontType === 'heading' ? 'fontHeading' : 'fontBody';
            option.classList.toggle('active', this.settings[settingKey] === fontValue);
        });

        // Update active style options
        this.drawer.querySelectorAll('.style-option').forEach(option => {
            const setting = option.dataset.setting;
            const value = option.dataset.value;
            const isActive = value === 'true' ? this.settings[setting] === true :
                            value === 'false' ? this.settings[setting] === false :
                            this.settings[setting] === value;
            option.classList.toggle('active', isActive);
        });

        console.log('VisualCustomizer: UI updated successfully');
    }

    // NEW PHASE 2: Tab Navigation
    switchTab(tabName) {
        console.log('VisualCustomizer: Switching to tab:', tabName);

        // Update tab buttons
        this.drawer.querySelectorAll('.tab-button').forEach(button => {
            button.classList.toggle('active', button.dataset.tab === tabName);
        });

        // Update tab content
        this.drawer.querySelectorAll('.tab-content').forEach(content => {
            content.classList.toggle('active', content.dataset.tabContent === tabName);
        });
    }

    // NEW PHASE 2: Component Controls Toggle
    toggleComponentControls(componentKey) {
        const controls = this.drawer.querySelector(`[data-component-controls="${componentKey}"]`);
        const expandButton = this.drawer.querySelector(`[data-component="${componentKey}"] .component-expand`);

        if (controls && expandButton) {
            const isExpanded = controls.classList.contains('expanded');

            // Close all other component controls
            this.drawer.querySelectorAll('.component-controls').forEach(ctrl => ctrl.classList.remove('expanded'));
            this.drawer.querySelectorAll('.component-expand').forEach(btn => btn.classList.remove('active'));

            if (!isExpanded) {
                controls.classList.add('expanded');
                expandButton.classList.add('active');
                this.generateComponentPreview(componentKey);
            } else {
                this.clearComponentPreview();
            }
        }
    }

    // NEW PHASE 2: Component Option Selection
    selectComponentOption(button) {
        const component = button.dataset.component;
        const option = button.dataset.option;
        const value = button.dataset.value;

        // Update UI
        const optionGroup = button.parentElement;
        optionGroup.querySelectorAll('.option-button').forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        // Update settings
        if (!this.settings.componentSettings[component]) {
            this.settings.componentSettings[component] = {};
        }
        this.settings.componentSettings[component][option] = value;

        // Apply component styling
        this.applyComponentTheming(component);
        this.saveSettings();
        this.showPreviewIndicator(`Updated ${component} ${option}`);

        // Update preview if component is active
        if (button.closest('.component-controls').classList.contains('expanded')) {
            this.generateComponentPreview(component);
        }
    }

    // NEW PHASE 2: Preview Mode Selection
    setPreviewMode(mode) {
        console.log('VisualCustomizer: Setting preview mode:', mode);

        // Update preview mode buttons
        this.drawer.querySelectorAll('.preview-mode').forEach(button => {
            button.classList.toggle('active', button.dataset.mode === mode);
        });

        // Update preview frame
        const previewFrame = this.drawer.querySelector('#component-preview');
        if (previewFrame) {
            previewFrame.className = `component-preview-frame ${mode}-mode`;
        }
    }

    // NEW PHASE 2: Component Preview Generation
    generateComponentPreview(componentKey) {
        const previewFrame = this.drawer.querySelector('#component-preview');
        if (!previewFrame) return;

        const component = this.componentDefinitions[componentKey];
        const settings = this.settings.componentSettings[componentKey] || {};

        let previewHTML = '';

        switch (componentKey) {
            case 'header':
                previewHTML = this.generateHeaderPreview(settings);
                break;
            case 'footer':
                previewHTML = this.generateFooterPreview(settings);
                break;
            case 'buttons':
                previewHTML = this.generateButtonPreview(settings);
                break;
            case 'cards':
                previewHTML = this.generateCardPreview(settings);
                break;
            case 'navigation':
                previewHTML = this.generateNavigationPreview(settings);
                break;
            case 'forms':
                previewHTML = this.generateFormPreview(settings);
                break;
            default:
                previewHTML = `<div class="preview-placeholder">Preview for ${component.name}</div>`;
        }

        previewFrame.innerHTML = `
            <div class="component-preview-content">
                <h5>${component.icon} ${component.name} Preview</h5>
                ${previewHTML}
            </div>
        `;
    }

    // NEW PHASE 2: Clear Component Preview
    clearComponentPreview() {
        const previewFrame = this.drawer.querySelector('#component-preview');
        if (previewFrame) {
            previewFrame.innerHTML = `
                <div class="preview-loading">
                    <span class="loading-icon">⏳</span>
                    Select a component to preview
                </div>
            `;
        }
    }

    // NEW PHASE 2: Generate Specific Component Previews
    generateHeaderPreview(settings) {
        const style = settings.style || 'transparent';
        const background = settings.background || 'default';
        const pattern = settings.pattern || 'none';

        return `
            <div class="header-preview header-${style} bg-${background} pattern-${pattern}">
                <div class="header-content">
                    <div class="logo">
                        <span class="logo-icon">🏥</span>
                        MedSpa
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
        const style = settings.style || 'rounded';
        const effect = settings.effect || 'none';
        const interaction = settings.interaction || 'hover';

        return `
            <div class="button-preview">
                <button class="btn-preview btn-${style} effect-${effect} interaction-${interaction}">
                    Book Consultation
                </button>
                <button class="btn-preview btn-${style} effect-${effect} interaction-${interaction} btn-secondary">
                    Learn More
                </button>
            </div>
        `;
    }

    generateCardPreview(settings) {
        const design = settings.design || 'modern';
        const shadow = settings.shadow || 'medium';
        const borderRadius = settings.borderRadius || 'rounded';

        return `
            <div class="card-preview card-${design} shadow-${shadow} radius-${borderRadius}">
                <div class="card-image">
                    <div class="placeholder-image">🌿</div>
                </div>
                <div class="card-content">
                    <h6>Facial Treatment</h6>
                    <p>Rejuvenating facial therapy for glowing skin</p>
                    <button class="card-button">Learn More</button>
                </div>
            </div>
        `;
    }

    generateFormPreview(settings) {
        const style = settings.style || 'modern';
        const fieldStyle = settings.fieldStyle || 'outlined';

        return `
            <div class="form-preview form-${style}">
                <div class="form-field field-${fieldStyle}">
                    <label>Name</label>
                    <input type="text" placeholder="Your Name" />
                </div>
                <div class="form-field field-${fieldStyle}">
                    <label>Email</label>
                    <input type="email" placeholder="your@email.com" />
                </div>
                <button class="form-submit">Submit</button>
            </div>
        `;
    }

    generateFooterPreview(settings) {
        const layout = settings.layout || 'default';
        const background = settings.background || 'default';

        return `
            <div class="footer-preview footer-${layout} bg-${background}">
                <div class="footer-content">
                    <div class="footer-section">
                        <h6>Contact</h6>
                        <p>📍 123 Wellness St</p>
                        <p>📞 (555) 123-4567</p>
                    </div>
                    <div class="footer-section">
                        <h6>Services</h6>
                        <p>Facial Treatments</p>
                        <p>Body Wellness</p>
                    </div>
                </div>
            </div>
        `;
    }

    generateNavigationPreview(settings) {
        const mobileStyle = settings.mobileStyle || 'hamburger';
        const desktopStyle = settings.desktopStyle || 'inline';

        return `
            <div class="navigation-preview">
                <div class="nav-desktop nav-${desktopStyle}">
                    <span>Desktop: ${desktopStyle}</span>
                    <div class="nav-items">
                        <span class="nav-item">Home</span>
                        <span class="nav-item">Services</span>
                        <span class="nav-item">About</span>
                    </div>
                </div>
                <div class="nav-mobile nav-${mobileStyle}">
                    <span>Mobile: ${mobileStyle}</span>
                    <div class="mobile-trigger">☰</div>
                </div>
            </div>
        `;
    }

    // NEW PHASE 2: Apply Component Theming
    applyComponentTheming(componentKey) {
        const settings = this.settings.componentSettings[componentKey];
        if (!settings) return;

        const root = document.documentElement;

        switch (componentKey) {
            case 'header':
                this.applyHeaderTheming(settings, root);
                break;
            case 'footer':
                this.applyFooterTheming(settings, root);
                break;
            case 'buttons':
                this.applyButtonTheming(settings, root);
                break;
            case 'cards':
                this.applyCardTheming(settings, root);
                break;
            case 'navigation':
                this.applyNavigationTheming(settings, root);
                break;
            case 'forms':
                this.applyFormTheming(settings, root);
                break;
        }

        console.log(`VisualCustomizer: Applied ${componentKey} theming`, settings);
    }

    applyHeaderTheming(settings, root) {
        root.style.setProperty('--header-style', settings.style || 'transparent');
        root.style.setProperty('--header-background', settings.background || 'default');
        root.style.setProperty('--header-pattern', settings.pattern || 'none');
        root.style.setProperty('--header-opacity', settings.opacity || '1.0');

        // Apply to body classes for global effect
        document.body.className = document.body.className.replace(/header-\w+/g, '');
        document.body.classList.add(`header-${settings.style || 'transparent'}`);
    }

    applyButtonTheming(settings, root) {
        root.style.setProperty('--button-style', settings.style || 'rounded');
        root.style.setProperty('--button-effect', settings.effect || 'none');
        root.style.setProperty('--button-interaction', settings.interaction || 'hover');

        document.body.className = document.body.className.replace(/buttons?-\w+/g, '');
        document.body.classList.add(`buttons-${settings.style || 'rounded'}`);
    }

    applyCardTheming(settings, root) {
        root.style.setProperty('--card-design', settings.design || 'modern');
        root.style.setProperty('--card-shadow', settings.shadow || 'medium');
        root.style.setProperty('--card-border-radius', settings.borderRadius || 'rounded');
    }

    applyFormTheming(settings, root) {
        root.style.setProperty('--form-style', settings.style || 'modern');
        root.style.setProperty('--form-field-style', settings.fieldStyle || 'outlined');
    }

    applyFooterTheming(settings, root) {
        root.style.setProperty('--footer-layout', settings.layout || 'default');
        root.style.setProperty('--footer-background', settings.background || 'default');
    }

    applyNavigationTheming(settings, root) {
        root.style.setProperty('--nav-mobile-style', settings.mobileStyle || 'hamburger');
        root.style.setProperty('--nav-desktop-style', settings.desktopStyle || 'inline');
    }

    // Initialize component preview
    initializeComponentPreview() {
        const previewFrame = document.querySelector('.component-preview-frame');
        if (previewFrame && !previewFrame.innerHTML.trim()) {
            previewFrame.innerHTML = `
                <div class="preview-placeholder">
                    <div class="preview-icon">🧩</div>
                    <h5>Select a Component</h5>
                    <p>Choose a component from the list to see a live preview of your customization options.</p>
                </div>
            `;
        }
    }

    // NEW: Apply Configuration Globally (Admin Only)
    applyConfigurationGlobally() {
        if (!this.isAdmin) {
            console.warn('VisualCustomizer: Apply globally attempted by non-admin user');
            return;
        }

        console.log('VisualCustomizer: Applying configuration globally');

        // Show loading state
        const applyBtn = this.drawer.querySelector('.apply-global-config');
        const originalText = applyBtn.innerHTML;
        applyBtn.innerHTML = '<span class="btn-icon">⏳</span> Applying...';
        applyBtn.disabled = true;

        // Prepare settings data
        const settingsData = {
            ...this.settings,
            timestamp: new Date().toISOString(),
            appliedBy: 'admin'
        };

        // Send AJAX request to save global configuration
        const formData = new FormData();
        formData.append('action', 'visual_customizer_admin_action');
        formData.append('action_type', 'apply_global_config');
        formData.append('settings', JSON.stringify(settingsData));
        formData.append('nonce', visualCustomizerData.nonce);

        fetch(visualCustomizerData.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('VisualCustomizer: Configuration applied globally successfully', data.data);
                this.showPreviewIndicator(visualCustomizerData.i18n.configApplied, 'success');

                // Update global config reference
                this.globalConfig = data.data.appliedSettings;

                // Show success state
                applyBtn.innerHTML = '<span class="btn-icon">✅</span> Applied Successfully';
                setTimeout(() => {
                    applyBtn.innerHTML = originalText;
                    applyBtn.disabled = false;
                }, 3000);

            } else {
                console.error('VisualCustomizer: Failed to apply configuration globally', data.data);
                this.showPreviewIndicator('Failed to apply configuration: ' + data.data, 'error');

                // Restore button state
                applyBtn.innerHTML = originalText;
                applyBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('VisualCustomizer: AJAX error applying configuration globally', error);
            this.showPreviewIndicator('Network error applying configuration', 'error');

            // Restore button state
            applyBtn.innerHTML = originalText;
            applyBtn.disabled = false;
        });
    }

    // NEW: Load Global Configuration on Init (Admin Only)
    loadGlobalConfiguration() {
        if (!this.isAdmin || !this.globalConfig || Object.keys(this.globalConfig).length === 0) {
            return;
        }

        console.log('VisualCustomizer: Loading global configuration', this.globalConfig);

        // Merge global config with current settings
        this.settings = { ...this.settings, ...this.globalConfig };

        // Apply the loaded settings
        this.applyCurrentSettings();
        this.updateUI();

        console.log('VisualCustomizer: Global configuration loaded and applied');
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
