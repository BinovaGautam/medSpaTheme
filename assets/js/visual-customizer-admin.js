/**
 * Visual Customizer Admin Interface
 *
 * Integrates Sprint 1 components into WordPress admin interface
 * Sprint 2 - PVC-004: WordPress Admin Integration Panel
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Visual Customizer Admin Class
     */
    class VisualCustomizerAdmin {
        constructor() {
            this.colorPaletteInterface = null;
            this.semanticColorSystem = null;
            this.typographySystem = null;
            this.isInitialized = false;

            this.init();
        }

        /**
         * Initialize the admin interface
         */
        async init() {
            console.log('Initializing Visual Customizer Admin...');

            // Wait for DOM to be ready
            $(document).ready(() => {
                this.initializeComponents();
                this.bindEvents();
            });
        }

        /**
         * Initialize Sprint 1 components
         */
        async initializeComponents() {
            try {
                // Wait a moment for all scripts to load
                await this.delay(500);

                // Initialize Color Palette Interface
                if (typeof ColorPaletteInterface !== 'undefined') {
                    this.colorPaletteInterface = new ColorPaletteInterface({
                        container: '#color-palette-interface-container',
                        mode: 'admin',
                        enablePreview: true,
                        onPaletteChange: (paletteId) => {
                            this.handlePaletteChange(paletteId);
                        }
                    });

                    // Hide loading state
                    $('#color-palette-interface-container .loading-state').hide();
                    console.log('Color Palette Interface initialized');
                }

                // Initialize Semantic Color System
                if (typeof SemanticColorSystem !== 'undefined') {
                    this.semanticColorSystem = new SemanticColorSystem({
                        container: '#semantic-color-system-container',
                        mode: 'admin',
                        showAdvanced: true,
                        onColorUpdate: (colorData) => {
                            this.handleSemanticColorUpdate(colorData);
                        }
                    });

                    $('#semantic-color-system-container .loading-state').hide();
                    console.log('Semantic Color System initialized');
                }

                // Initialize Typography System
                if (typeof TypographySystem !== 'undefined') {
                    this.typographySystem = new TypographySystem({
                        container: '#typography-system-container',
                        mode: 'admin',
                        enableColorIntegration: true,
                        onTypographyChange: (typographyData) => {
                            this.handleTypographyChange(typographyData);
                        }
                    });

                    $('#typography-system-container .loading-state').hide();
                    console.log('Typography System initialized');
                }

                this.isInitialized = true;
                console.log('All Visual Customizer components initialized successfully');

            } catch (error) {
                console.error('Error initializing Visual Customizer components:', error);
                this.showError('Failed to initialize Visual Customizer components. Please refresh the page.');
            }
        }

        /**
         * Bind event handlers
         */
        bindEvents() {
            // Import/Export functionality
            $('#import-palette').on('click', () => this.showImportModal());
            $('#export-palette').on('click', () => this.showExportModal());

            // Modal handling
            $('.vc-modal-close').on('click', (e) => this.closeModal($(e.target).closest('.vc-modal')));

            // Import form
            $('#import-form').on('submit', (e) => {
                e.preventDefault();
                this.handleImport();
            });

            // Export button
            $('#export-button').on('click', () => this.handleExport());

            // Palette selection change
            $('#active_palette').on('change', (e) => {
                const paletteId = $(e.target).val();
                if (this.colorPaletteInterface) {
                    this.colorPaletteInterface.selectPalette(paletteId);
                }
            });

            // Auto-save functionality
            this.setupAutoSave();
        }

        /**
         * Handle palette change
         */
        handlePaletteChange(paletteId) {
            console.log('Palette changed to:', paletteId);

            // Update form selection
            $('#active_palette').val(paletteId);

            // Update semantic color system
            if (this.semanticColorSystem) {
                this.semanticColorSystem.updatePalette(paletteId);
            }

            // Save configuration automatically
            this.saveConfiguration({
                activePalette: paletteId,
                timestamp: Date.now()
            });
        }

        /**
         * Handle semantic color update
         */
        handleSemanticColorUpdate(colorData) {
            console.log('Semantic colors updated:', colorData);

            // Update color palette interface if needed
            if (this.colorPaletteInterface) {
                this.colorPaletteInterface.updateSemanticColors(colorData);
            }

            // Auto-save
            this.saveConfiguration({
                semanticColors: colorData,
                timestamp: Date.now()
            });
        }

        /**
         * Handle typography change
         */
        handleTypographyChange(typographyData) {
            console.log('Typography updated:', typographyData);

            // Auto-save
            this.saveConfiguration({
                typography: typographyData,
                timestamp: Date.now()
            });
        }

        /**
         * Setup auto-save functionality
         */
        setupAutoSave() {
            let saveTimeout;

            // Debounced save function
            const debouncedSave = () => {
                clearTimeout(saveTimeout);
                saveTimeout = setTimeout(() => {
                    this.saveCurrentState();
                }, 2000); // Save 2 seconds after last change
            };

            // Listen to various change events
            $(document).on('change input', '.visual-customizer-admin input, .visual-customizer-admin select', debouncedSave);
        }

        /**
         * Save current state
         */
        async saveCurrentState() {
            const config = {
                activePalette: $('#active_palette').val(),
                colorPaletteInterface: this.colorPaletteInterface ? this.colorPaletteInterface.getConfig() : null,
                semanticColorSystem: this.semanticColorSystem ? this.semanticColorSystem.getConfig() : null,
                typographySystem: this.typographySystem ? this.typographySystem.getConfig() : null,
                lastSaved: Date.now()
            };

            this.saveConfiguration(config);
        }

        /**
         * Save configuration via AJAX
         */
        async saveConfiguration(config) {
            try {
                const response = await $.ajax({
                    url: visualCustomizerAdmin.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'visual_customizer_save_config',
                        nonce: visualCustomizerAdmin.nonce,
                        config: JSON.stringify(config)
                    }
                });

                if (response.success) {
                    this.showNotice('Configuration saved', 'success');
                } else {
                    this.showNotice('Error saving configuration: ' + response.data, 'error');
                }
            } catch (error) {
                console.error('Error saving configuration:', error);
                this.showNotice('Error saving configuration', 'error');
            }
        }

        /**
         * Show import modal
         */
        showImportModal() {
            $('#import-modal').show();
            $('#import-data').focus();
        }

        /**
         * Show export modal
         */
        showExportModal() {
            $('#export-modal').show();
        }

        /**
         * Close modal
         */
        closeModal($modal) {
            $modal.hide();
            $('#export-result').hide();
            $('#import-data').val('');
            $('#export-data').val('');
        }

        /**
         * Handle import
         */
        async handleImport() {
            const importData = $('#import-data').val().trim();

            if (!importData) {
                this.showNotice('Please enter import data', 'error');
                return;
            }

            try {
                const response = await $.ajax({
                    url: visualCustomizerAdmin.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'visual_customizer_import_palette',
                        nonce: visualCustomizerAdmin.nonce,
                        import_data: importData
                    }
                });

                if (response.success) {
                    this.showNotice(visualCustomizerAdmin.strings.palette_imported, 'success');
                    this.closeModal($('#import-modal'));

                    // Refresh the page to show new palette
                    setTimeout(() => location.reload(), 1500);
                } else {
                    this.showNotice('Import error: ' + response.data, 'error');
                }
            } catch (error) {
                console.error('Error importing palette:', error);
                this.showNotice('Error importing palette', 'error');
            }
        }

        /**
         * Handle export
         */
        async handleExport() {
            const paletteId = $('#export-palette-select').val();

            if (!paletteId) {
                this.showNotice('Please select a palette to export', 'error');
                return;
            }

            try {
                const response = await $.ajax({
                    url: visualCustomizerAdmin.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'visual_customizer_export_palette',
                        nonce: visualCustomizerAdmin.nonce,
                        palette_id: paletteId
                    }
                });

                if (response.success) {
                    $('#export-data').val(JSON.stringify(response.data, null, 2));
                    $('#export-result').show();
                    $('#export-data').select();
                    this.showNotice(visualCustomizerAdmin.strings.palette_exported, 'success');
                } else {
                    this.showNotice('Export error: ' + response.data, 'error');
                }
            } catch (error) {
                console.error('Error exporting palette:', error);
                this.showNotice('Error exporting palette', 'error');
            }
        }

        /**
         * Show notice message
         */
        showNotice(message, type = 'info') {
            const noticeClass = type === 'error' ? 'notice-error' : 'notice-success';
            const $notice = $(`
                <div class="notice ${noticeClass} is-dismissible">
                    <p>${message}</p>
                    <button type="button" class="notice-dismiss">
                        <span class="screen-reader-text">Dismiss this notice.</span>
                    </button>
                </div>
            `);

            $('.visual-customizer-admin h1').after($notice);

            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                $notice.fadeOut();
            }, 5000);

            // Handle dismiss button
            $notice.find('.notice-dismiss').on('click', () => {
                $notice.fadeOut();
            });
        }

        /**
         * Show error message
         */
        showError(message) {
            this.showNotice(message, 'error');
        }

        /**
         * Utility: Delay function
         */
        delay(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        /**
         * Get current configuration
         */
        getCurrentConfig() {
            return {
                activePalette: $('#active_palette').val(),
                colorPaletteInterface: this.colorPaletteInterface ? this.colorPaletteInterface.getConfig() : null,
                semanticColorSystem: this.semanticColorSystem ? this.semanticColorSystem.getConfig() : null,
                typographySystem: this.typographySystem ? this.typographySystem.getConfig() : null,
                isInitialized: this.isInitialized
            };
        }

        /**
         * Refresh components
         */
        async refreshComponents() {
            if (this.colorPaletteInterface) {
                await this.colorPaletteInterface.refresh();
            }
            if (this.semanticColorSystem) {
                await this.semanticColorSystem.refresh();
            }
            if (this.typographySystem) {
                await this.typographySystem.refresh();
            }
        }
    }

    // Initialize when document is ready
    $(document).ready(function() {
        // Only initialize on Visual Customizer admin pages
        if ($('.visual-customizer-admin').length > 0) {
            window.visualCustomizerAdmin = new VisualCustomizerAdmin();
        }
    });

    // Export for external access
    window.VisualCustomizerAdmin = VisualCustomizerAdmin;

})(jQuery);
