<?php
/**
 * Simple Working Visual Customizer
 * Add this to functions.php: require_once get_template_directory() . '/simple-working-customizer.php';
 */

// Add floating button to all pages
function add_simple_working_customizer() {
    ?>
    <style>
    #simple-working-customizer-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 999999;
        background: #007cba;
        color: white;
        padding: 15px 20px;
        border-radius: 50px;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        font-family: Arial, sans-serif;
        font-size: 14px;
        font-weight: bold;
        border: none;
        transition: all 0.3s ease;
    }

    #simple-working-customizer-btn:hover {
        background: #005a87;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.4);
    }

    .swc-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 999998;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .swc-overlay.show {
        opacity: 1;
    }

    .swc-sidebar {
        position: fixed;
        top: 0;
        right: 0;
        width: 400px;
        height: 100%;
        background: white;
        z-index: 999999;
        box-shadow: -4px 0 12px rgba(0,0,0,0.3);
        transform: translateX(100%);
        transition: transform 0.3s ease;
        overflow-y: auto;
    }

    .swc-sidebar.show {
        transform: translateX(0);
    }

    .swc-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        border-bottom: 2px solid #eee;
        background: #f8f9fa;
    }

    .swc-header h3 {
        margin: 0;
        color: #333;
        font-size: 18px;
    }

    .swc-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #666;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background 0.2s ease;
    }

    .swc-close:hover {
        background: #e9ecef;
    }

    .swc-content {
        padding: 20px;
    }

    .swc-section {
        margin-bottom: 30px;
    }

    .swc-section h4 {
        color: #007cba;
        margin: 0 0 10px 0;
        font-size: 16px;
    }

    .swc-section p {
        color: #666;
        margin: 0 0 15px 0;
        font-size: 14px;
    }

    .swc-palette-grid {
        display: grid;
        gap: 10px;
    }

    .swc-palette-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .swc-palette-card:hover {
        border-color: #007cba;
        box-shadow: 0 2px 8px rgba(0,124,186,0.1);
    }

    .swc-palette-card.active {
        border-color: #007cba;
        background: #f0f8ff;
    }

    .swc-palette-name {
        font-weight: bold;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .swc-palette-desc {
        font-size: 12px;
        color: #666;
        margin-bottom: 8px;
    }

    .swc-color-dots {
        display: flex;
        gap: 4px;
    }

    .swc-color-dot {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 1px solid #ddd;
    }

    .swc-actions {
        border-top: 2px solid #eee;
        padding-top: 15px;
        display: flex;
        gap: 10px;
    }

    .swc-btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .swc-btn-primary {
        background: #007cba;
        color: white;
    }

    .swc-btn-primary:hover {
        background: #005a87;
    }

    .swc-btn-secondary {
        background: #666;
        color: white;
    }

    .swc-btn-secondary:hover {
        background: #555;
    }

    .swc-typography-grid {
        display: grid;
        gap: 10px;
    }

    .swc-typography-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .swc-typography-card:hover {
        border-color: #007cba;
        box-shadow: 0 2px 8px rgba(0,124,186,0.1);
    }

    .swc-typography-card.active {
        border-color: #007cba;
        background: #f0f8ff;
    }

    .swc-font-preview h5 {
        margin: 0 0 5px 0;
        font-size: 16px;
        color: #333;
    }

    .swc-font-preview p {
        margin: 0;
        font-size: 14px;
        color: #666;
    }
    </style>

    <button id="simple-working-customizer-btn">🎨 Visual Customizer</button>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('simple-working-customizer-btn');
        let isOpen = false;

        // Color palettes data
        const colorPalettes = [
            {
                id: 'medical-clean',
                name: '🏥 Medical Clean',
                description: 'Professional and trustworthy',
                colors: ['#1B365D', '#87A96B', '#E4A853', '#FDF2F8']
            },
            {
                id: 'luxury-spa',
                name: '✨ Luxury Spa',
                description: 'Elegant and sophisticated',
                colors: ['#8B4B7A', '#642453', '#C2847A', '#FDF2F8']
            },
            {
                id: 'modern-wellness',
                name: '🌿 Modern Wellness',
                description: 'Fresh and contemporary',
                colors: ['#2D5A27', '#7FB069', '#F4A261', '#F8F9FA']
            },
            {
                id: 'serene-blue',
                name: '💙 Serene Blue',
                description: 'Calming and peaceful',
                colors: ['#1E3A8A', '#3B82F6', '#93C5FD', '#F0F9FF']
            }
        ];

        // Typography options
        const typographyOptions = [
            {
                id: 'medical-professional',
                name: 'Medical Professional',
                description: 'Clean and authoritative',
                headingFont: 'Inter, sans-serif',
                bodyFont: 'Source Sans Pro, sans-serif'
            },
            {
                id: 'luxury-modern',
                name: 'Luxury Modern',
                description: 'Elegant and sophisticated',
                headingFont: 'Playfair Display, serif',
                bodyFont: 'Lato, sans-serif'
            },
            {
                id: 'contemporary-clean',
                name: 'Contemporary Clean',
                description: 'Modern and minimal',
                headingFont: 'Poppins, sans-serif',
                bodyFont: 'Open Sans, sans-serif'
            }
        ];

        btn.addEventListener('click', function() {
            if (!isOpen) {
                openCustomizer();
            }
        });

        function openCustomizer() {
            // Create overlay
            const overlay = document.createElement('div');
            overlay.className = 'swc-overlay';
            overlay.addEventListener('click', closeCustomizer);

            // Create sidebar
            const sidebar = document.createElement('div');
            sidebar.className = 'swc-sidebar';
            sidebar.innerHTML = createSidebarHTML();

            document.body.appendChild(overlay);
            document.body.appendChild(sidebar);

            // Trigger animations
            setTimeout(() => {
                overlay.classList.add('show');
                sidebar.classList.add('show');
            }, 10);

            isOpen = true;

            // Add event listeners
            setupEventListeners();
        }

        function closeCustomizer() {
            const overlay = document.querySelector('.swc-overlay');
            const sidebar = document.querySelector('.swc-sidebar');

            if (overlay && sidebar) {
                overlay.classList.remove('show');
                sidebar.classList.remove('show');

                setTimeout(() => {
                    overlay.remove();
                    sidebar.remove();
                }, 300);
            }

            isOpen = false;
        }

        function createSidebarHTML() {
            return `
                <div class="swc-header">
                    <h3>🎨 Visual Customizer</h3>
                    <button class="swc-close" onclick="document.querySelector('.swc-overlay').click()">×</button>
                </div>

                <div class="swc-content">
                    <div class="swc-section">
                        <h4>🎨 Color Palettes</h4>
                        <p>Professional medical spa color schemes 12</p>
                        <div class="swc-palette-grid">
                            ${colorPalettes.map(palette => `
                                <div class="swc-palette-card" data-palette="${palette.id}">
                                    <div class="swc-palette-name">${palette.name}</div>
                                    <div class="swc-palette-desc">${palette.description}</div>
                                    <div class="swc-color-dots">
                                        ${palette.colors.map(color => `
                                            <div class="swc-color-dot" style="background: ${color};"></div>
                                        `).join('')}
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>

                    <div class="swc-section">
                        <h4>📝 Typography</h4>
                        <p>Professional font pairings</p>
                        <div class="swc-typography-grid">
                            ${typographyOptions.map(typography => `
                                <div class="swc-typography-card" data-typography="${typography.id}">
                                    <div class="swc-font-preview">
                                        <h5 style="font-family: ${typography.headingFont};">${typography.name}</h5>
                                        <p style="font-family: ${typography.bodyFont};">${typography.description}</p>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>

                    <div class="swc-actions">
                        <button class="swc-btn swc-btn-primary" onclick="applyChanges()">Apply Changes</button>
                        <button class="swc-btn swc-btn-secondary" onclick="resetChanges()">Reset</button>
                    </div>
                </div>
            `;
        }

        function setupEventListeners() {
            // Palette selection
            document.querySelectorAll('.swc-palette-card').forEach(card => {
                card.addEventListener('click', function() {
                    document.querySelectorAll('.swc-palette-card').forEach(c => c.classList.remove('active'));
                    this.classList.add('active');

                    const paletteId = this.dataset.palette;
                    console.log('Palette selected:', paletteId);

                    // Apply palette preview
                    const palette = colorPalettes.find(p => p.id === paletteId);
                    if (palette) {
                        applyPalettePreview(palette);
                    }
                });
            });

            // Typography selection
            document.querySelectorAll('.swc-typography-card').forEach(card => {
                card.addEventListener('click', function() {
                    document.querySelectorAll('.swc-typography-card').forEach(c => c.classList.remove('active'));
                    this.classList.add('active');

                    const typographyId = this.dataset.typography;
                    console.log('Typography selected:', typographyId);

                    // Apply typography preview
                    const typography = typographyOptions.find(t => t.id === typographyId);
                    if (typography) {
                        applyTypographyPreview(typography);
                    }
                });
            });
        }

        function applyPalettePreview(palette) {
            // Create or update preview styles
            let previewStyle = document.getElementById('swc-palette-preview');
            if (!previewStyle) {
                previewStyle = document.createElement('style');
                previewStyle.id = 'swc-palette-preview';
                document.head.appendChild(previewStyle);
            }

            previewStyle.textContent = `
                :root {
                    --primary-color: ${palette.colors[0]};
                    --secondary-color: ${palette.colors[1]};
                    --accent-color: ${palette.colors[2]};
                    --background-color: ${palette.colors[3]};
                }

                .site-header {
                    background-color: var(--primary-color) !important;
                }

                .btn-primary, .button-primary {
                    background-color: var(--secondary-color) !important;
                    border-color: var(--secondary-color) !important;
                }

                .btn-primary:hover, .button-primary:hover {
                    background-color: var(--primary-color) !important;
                    border-color: var(--primary-color) !important;
                }

                .accent-color {
                    color: var(--accent-color) !important;
                }

                .hero-section {
                    background-color: var(--background-color) !important;
                }
            `;
        }

        function applyTypographyPreview(typography) {
            // Create or update typography preview styles
            let previewStyle = document.getElementById('swc-typography-preview');
            if (!previewStyle) {
                previewStyle = document.createElement('style');
                previewStyle.id = 'swc-typography-preview';
                document.head.appendChild(previewStyle);
            }

            previewStyle.textContent = `
                h1, h2, h3, h4, h5, h6, .heading-font {
                    font-family: ${typography.headingFont} !important;
                }

                body, p, .body-font {
                    font-family: ${typography.bodyFont} !important;
                }
            `;
        }

        // Global functions for buttons
        window.applyChanges = function() {
            alert('Changes applied! (In a real implementation, this would save to WordPress customizer)');
        };

        window.resetChanges = function() {
            // Remove preview styles
            const palettePreview = document.getElementById('swc-palette-preview');
            const typographyPreview = document.getElementById('swc-typography-preview');

            if (palettePreview) palettePreview.remove();
            if (typographyPreview) typographyPreview.remove();

            // Remove active states
            document.querySelectorAll('.swc-palette-card.active, .swc-typography-card.active').forEach(card => {
                card.classList.remove('active');
            });

            alert('Settings reset!');
        };
    });
    </script>
    <?php
}

// Hook to add the customizer to all pages
add_action('wp_footer', 'add_simple_working_customizer');

?>
