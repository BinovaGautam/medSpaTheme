<?php
/**
 * Footer Component Demo Template
 *
 * Comprehensive demonstration of FooterComponent features including
 * all footer types, layouts, and medical spa specializations.
 *
 * @package MedSpaTheme
 * @since 1.0.0
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<div class="footer-demo-container">
    <div class="demo-header">
        <h1>Footer Component Demo</h1>
        <p>Comprehensive demonstration of FooterComponent with medical spa specializations, accessibility features, and responsive design.</p>
    </div>

    <!-- Demo Controls -->
    <div class="demo-controls">
        <h2>Footer Configuration</h2>
        <div class="control-grid">
            <div class="control-group">
                <label for="footer-type">Footer Type:</label>
                <select id="footer-type">
                    <option value="luxury">Luxury Medical Spa</option>
                    <option value="medical">Medical Professional</option>
                    <option value="consultation">Consultation Focused</option>
                    <option value="minimal">Minimal Design</option>
                    <option value="basic">Basic Footer</option>
                </select>
            </div>

            <div class="control-group">
                <label for="footer-layout">Layout:</label>
                <select id="footer-layout">
                    <option value="columns">Multi-Column</option>
                    <option value="centered">Centered</option>
                    <option value="split">Split Layout</option>
                    <option value="stacked">Stacked</option>
                </select>
            </div>

            <div class="control-group">
                <label>
                    <input type="checkbox" id="enable-cta" checked>
                    Consultation CTA Section
                </label>
            </div>

            <div class="control-group">
                <label>
                    <input type="checkbox" id="enable-contact" checked>
                    Contact Information
                </label>
            </div>

            <div class="control-group">
                <label>
                    <input type="checkbox" id="enable-credentials" checked>
                    Medical Credentials
                </label>
            </div>

            <div class="control-group">
                <label>
                    <input type="checkbox" id="enable-social" checked>
                    Social Media Links
                </label>
            </div>

            <div class="control-group">
                <label>
                    <input type="checkbox" id="enable-back-to-top" checked>
                    Back to Top Button
                </label>
            </div>
        </div>

        <button id="update-footer" class="demo-button">Update Footer</button>
        <button id="reset-footer" class="demo-button secondary">Reset to Default</button>
    </div>

    <!-- Demo Content (to create scroll space) -->
    <div class="demo-content">
        <h2>Demo Page Content</h2>
        <p>This content creates scroll space to demonstrate the back-to-top button functionality and footer interactions.</p>

        <?php for ($i = 1; $i <= 20; $i++): ?>
        <div class="content-section">
            <h3>Section <?php echo $i; ?>: Medical Spa Services</h3>
            <p>Experience premium aesthetic medicine with our comprehensive range of treatments. Our board-certified physicians provide personalized consultations to help you achieve your aesthetic goals safely and effectively.</p>

            <div class="service-grid">
                <div class="service-item">
                    <h4>Botox & Fillers</h4>
                    <p>Non-surgical facial rejuvenation with FDA-approved neurotoxins and dermal fillers.</p>
                </div>
                <div class="service-item">
                    <h4>Laser Treatments</h4>
                    <p>Advanced laser technology for skin resurfacing, hair removal, and pigmentation correction.</p>
                </div>
                <div class="service-item">
                    <h4>Chemical Peels</h4>
                    <p>Professional-grade chemical peels for improved skin texture and tone.</p>
                </div>
                <div class="service-item">
                    <h4>Body Contouring</h4>
                    <p>Non-invasive body sculpting treatments for targeted fat reduction.</p>
                </div>
            </div>
        </div>
        <?php endfor; ?>
    </div>
</div>

<!-- Dynamic Footer Rendering -->
<div id="footer-demo-output">
    <?php
    // Initialize footer component
    $footer_component = new FooterComponent();

    // Default configuration
    $default_config = [
        'footer_type' => 'luxury',
        'footer_layout' => 'columns',
        'enable_consultation_cta' => true,
        'enable_contact_section' => true,
        'enable_credentials_section' => true,
        'enable_social_section' => true,
        'enable_back_to_top' => true,
        'consultation_headline' => 'Ready to Begin Your Aesthetic Journey?',
        'consultation_subtext' => 'Experience the artistry of medical aesthetics with personalized treatments in our luxury clinic.',
    ];

    echo $footer_component->render($default_config);
    ?>
</div>

<style>
/* Demo-specific styles */
.footer-demo-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
    font-family: 'Inter', sans-serif;
}

.demo-header {
    text-align: center;
    margin-bottom: 3rem;
    padding: 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-radius: 12px;
}

.demo-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1rem;
}

.demo-header p {
    font-size: 1.125rem;
    color: #64748b;
    max-width: 600px;
    margin: 0 auto;
}

.demo-controls {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    margin-bottom: 3rem;
}

.demo-controls h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 1.5rem;
}

.control-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.control-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.control-group label {
    font-weight: 500;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.control-group select,
.control-group input[type="text"] {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.875rem;
    transition: border-color 0.2s ease;
}

.control-group select:focus,
.control-group input[type="text"]:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.control-group input[type="checkbox"] {
    width: 16px;
    height: 16px;
    margin: 0;
}

.demo-button {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    margin-right: 1rem;
}

.demo-button:not(.secondary) {
    background: #3b82f6;
    color: white;
}

.demo-button:not(.secondary):hover {
    background: #2563eb;
}

.demo-button.secondary {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

.demo-button.secondary:hover {
    background: #e5e7eb;
}

.demo-content {
    margin-bottom: 4rem;
}

.demo-content h2 {
    font-size: 2rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 1rem;
}

.content-section {
    margin-bottom: 3rem;
    padding: 2rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.content-section h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 1rem;
}

.content-section p {
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.service-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.service-item {
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 8px;
    border-left: 4px solid #3b82f6;
}

.service-item h4 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.service-item p {
    color: #64748b;
    font-size: 0.875rem;
    margin: 0;
}

/* Responsive design */
@media (max-width: 768px) {
    .footer-demo-container {
        padding: 1rem;
    }

    .demo-header h1 {
        font-size: 2rem;
    }

    .control-grid {
        grid-template-columns: 1fr;
    }

    .service-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Footer Demo Functionality
(function($) {
    'use strict';

    class FooterDemo {
        constructor() {
            this.init();
        }

        init() {
            this.bindEvents();
        }

        bindEvents() {
            $('#update-footer').on('click', () => this.updateFooter());
            $('#reset-footer').on('click', () => this.resetFooter());

            // Real-time updates for checkboxes
            $('#footer-demo-output').on('change', 'input[type="checkbox"]', () => {
                setTimeout(() => this.updateFooter(), 100);
            });
        }

        updateFooter() {
            const config = this.getConfiguration();

            // Update footer classes
            const $footer = $('#footer-demo-output .footer-component');
            $footer.removeClass('footer-luxury footer-medical footer-consultation footer-minimal footer-basic');
            $footer.removeClass('footer-layout-columns footer-layout-centered footer-layout-split footer-layout-stacked');

            $footer.addClass(`footer-${config.footer_type}`);
            $footer.addClass(`footer-layout-${config.footer_layout}`);

            // Toggle sections
            this.toggleSection('.consultation-invitation', config.enable_cta);
            this.toggleSection('.contact-column', config.enable_contact);
            this.toggleSection('.credentials-column', config.enable_credentials);
            this.toggleSection('.social-column', config.enable_social);
            this.toggleSection('.back-to-top', config.enable_back_to_top);

            // Update grid layout
            this.updateGridLayout();

            this.showNotification('Footer updated successfully!', 'success');
        }

        getConfiguration() {
            return {
                footer_type: $('#footer-type').val(),
                footer_layout: $('#footer-layout').val(),
                enable_cta: $('#enable-cta').is(':checked'),
                enable_contact: $('#enable-contact').is(':checked'),
                enable_credentials: $('#enable-credentials').is(':checked'),
                enable_social: $('#enable-social').is(':checked'),
                enable_back_to_top: $('#enable-back-to-top').is(':checked')
            };
        }

        toggleSection(selector, show) {
            const $section = $('#footer-demo-output').find(selector);
            if (show) {
                $section.show();
            } else {
                $section.hide();
            }
        }

        updateGridLayout() {
            const $grid = $('#footer-demo-output .footer-grid');
            const visibleColumns = $grid.find('.footer-column:visible').length;

            $grid.removeClass('footer-grid-1 footer-grid-2 footer-grid-3');
            $grid.addClass(`footer-grid-${visibleColumns}`);
        }

        resetFooter() {
            // Reset all controls to default
            $('#footer-type').val('luxury');
            $('#footer-layout').val('columns');
            $('#enable-cta, #enable-contact, #enable-credentials, #enable-social, #enable-back-to-top').prop('checked', true);

            this.updateFooter();
            this.showNotification('Footer reset to default configuration', 'info');
        }

        showNotification(message, type = 'info') {
            const notification = $('<div>', {
                class: `demo-notification notification-${type}`,
                text: message
            });

            $('body').append(notification);

            // Position and show
            notification.css({
                position: 'fixed',
                top: '20px',
                right: '20px',
                padding: '12px 16px',
                background: type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6',
                color: 'white',
                borderRadius: '8px',
                boxShadow: '0 4px 12px rgba(0, 0, 0, 0.15)',
                zIndex: 10000,
                fontSize: '14px',
                maxWidth: '300px'
            }).fadeIn(300);

            // Auto remove
            setTimeout(() => {
                notification.fadeOut(300, () => {
                    notification.remove();
                });
            }, 3000);
        }
    }

    // Initialize when DOM is ready
    $(document).ready(() => {
        new FooterDemo();
    });

})(jQuery);
</script>

<?php get_footer(); ?>
