<?php
/**
 * Modal Component Demo Template
 *
 * Comprehensive demonstration of modal components including
 * basic modals, specialized types, and interactive features.
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

// Initialize modal components
$modal_component = new ModalComponent();
$booking_modal = new BookingModal();

?>

<div class="modal-demo-container">
    <div class="demo-header">
        <h1 class="demo-title">Modal/Dialog System Demo</h1>
        <p class="demo-description">
            Interactive demonstration of the modal component system including basic modals,
            specialized medical spa modals, and accessibility features.
        </p>

        <div class="demo-metrics">
            <div class="metric-item">
                <span class="metric-label">Performance Target:</span>
                <span class="metric-value">&lt;50ms open time</span>
            </div>
            <div class="metric-item">
                <span class="metric-label">Accessibility:</span>
                <span class="metric-value">WCAG 2.1 AA</span>
            </div>
            <div class="metric-item">
                <span class="metric-label">Animation:</span>
                <span class="metric-value">60fps smooth</span>
            </div>
        </div>
    </div>

    <div class="demo-sections">

        <!-- Basic Modal Examples -->
        <section class="demo-section">
            <h2 class="section-title">Basic Modal Components</h2>
            <p class="section-description">Standard modal variations with different sizes and positions.</p>

            <div class="demo-group">
                <h3>Modal Sizes</h3>
                <div class="button-group">
                    <button class="button button-primary" data-modal-target="demo-modal-small">
                        Small Modal
                    </button>
                    <button class="button button-primary" data-modal-target="demo-modal-medium">
                        Medium Modal
                    </button>
                    <button class="button button-primary" data-modal-target="demo-modal-large">
                        Large Modal
                    </button>
                    <button class="button button-primary" data-modal-target="demo-modal-fullscreen">
                        Fullscreen Modal
                    </button>
                </div>
            </div>

            <div class="demo-group">
                <h3>Modal Positions</h3>
                <div class="button-group">
                    <button class="button button-secondary" data-modal-target="demo-modal-center">
                        Center
                    </button>
                    <button class="button button-secondary" data-modal-target="demo-modal-top">
                        Top
                    </button>
                    <button class="button button-secondary" data-modal-target="demo-modal-bottom">
                        Bottom
                    </button>
                    <button class="button button-secondary" data-modal-target="demo-modal-left">
                        Left
                    </button>
                    <button class="button button-secondary" data-modal-target="demo-modal-right">
                        Right
                    </button>
                </div>
            </div>
        </section>

        <!-- Medical Spa Specialized Modals -->
        <section class="demo-section">
            <h2 class="section-title">Medical Spa Specialized Modals</h2>
            <p class="section-description">Purpose-built modals for medical spa functionality.</p>

            <div class="demo-group">
                <h3>Consultation Booking</h3>
                <button class="button button-primary button-large" data-modal-target="demo-booking-modal">
                    <span class="button-icon">📅</span>
                    Book Consultation
                </button>
                <p class="demo-note">
                    Complete booking form with medical history, treatment selection, and appointment scheduling.
                </p>
            </div>

            <div class="demo-group">
                <h3>Treatment Information</h3>
                <div class="treatment-buttons">
                    <button class="button button-outline" data-modal-target="demo-treatment-botox">
                        Botox Information
                    </button>
                    <button class="button button-outline" data-modal-target="demo-treatment-fillers">
                        Dermal Fillers
                    </button>
                    <button class="button button-outline" data-modal-target="demo-treatment-laser">
                        Laser Resurfacing
                    </button>
                </div>
            </div>

            <div class="demo-group">
                <h3>Before/After Gallery</h3>
                <button class="button button-secondary" id="demo-gallery-trigger">
                    <span class="button-icon">🖼️</span>
                    View Gallery
                </button>
                <p class="demo-note">
                    Image gallery with navigation, zoom, and treatment categorization.
                </p>
            </div>
        </section>

        <!-- Interactive Features -->
        <section class="demo-section">
            <h2 class="section-title">Interactive Features</h2>
            <p class="section-description">Advanced modal functionality and user interactions.</p>

            <div class="demo-group">
                <h3>Confirmation Dialogs</h3>
                <div class="button-group">
                    <button class="button button-danger" id="demo-delete-confirmation">
                        Delete Item
                    </button>
                    <button class="button button-warning" id="demo-cancel-appointment">
                        Cancel Appointment
                    </button>
                    <button class="button button-info" id="demo-save-confirmation">
                        Save Changes
                    </button>
                </div>
            </div>

            <div class="demo-group">
                <h3>Dynamic Content</h3>
                <button class="button button-secondary" id="demo-dynamic-content">
                    Load Dynamic Content
                </button>
                <p class="demo-note">
                    Modal with content loaded via AJAX with loading states.
                </p>
            </div>

            <div class="demo-group">
                <h3>Nested Modals</h3>
                <button class="button button-secondary" data-modal-target="demo-modal-nested-parent">
                    Open Nested Modal
                </button>
                <p class="demo-note">
                    Demonstrates modal stacking and focus management.
                </p>
            </div>
        </section>

        <!-- Accessibility Testing -->
        <section class="demo-section">
            <h2 class="section-title">Accessibility Features</h2>
            <p class="section-description">WCAG 2.1 AA compliance testing and keyboard navigation.</p>

            <div class="demo-group">
                <h3>Keyboard Navigation Test</h3>
                <button class="button button-outline" data-modal-target="demo-accessibility-modal">
                    Test Keyboard Navigation
                </button>
                <div class="accessibility-notes">
                    <ul>
                        <li><kbd>Tab</kbd> - Navigate forward through focusable elements</li>
                        <li><kbd>Shift + Tab</kbd> - Navigate backward</li>
                        <li><kbd>Escape</kbd> - Close modal</li>
                        <li><kbd>Enter/Space</kbd> - Activate buttons</li>
                    </ul>
                </div>
            </div>

            <div class="demo-group">
                <h3>Screen Reader Test</h3>
                <button class="button button-outline" data-modal-target="demo-screenreader-modal">
                    Test Screen Reader Support
                </button>
                <p class="demo-note">
                    Modal with comprehensive ARIA labels and live regions.
                </p>
            </div>
        </section>

        <!-- Performance Testing -->
        <section class="demo-section">
            <h2 class="section-title">Performance Testing</h2>
            <p class="section-description">Animation performance and load testing capabilities.</p>

            <div class="demo-group">
                <h3>Animation Performance</h3>
                <button class="button button-secondary" id="demo-animation-test">
                    Test Animation Performance
                </button>
                <div id="performance-results" class="performance-results" style="display: none;">
                    <div class="metric">
                        <span class="metric-name">Open Time:</span>
                        <span class="metric-value" id="open-time">-</span>
                    </div>
                    <div class="metric">
                        <span class="metric-name">Close Time:</span>
                        <span class="metric-value" id="close-time">-</span>
                    </div>
                    <div class="metric">
                        <span class="metric-name">Frame Rate:</span>
                        <span class="metric-value" id="frame-rate">-</span>
                    </div>
                </div>
            </div>

            <div class="demo-group">
                <h3>Multiple Modal Test</h3>
                <button class="button button-secondary" id="demo-multiple-modals">
                    Open 5 Modals
                </button>
                <p class="demo-note">
                    Tests memory usage and performance with multiple modal instances.
                </p>
            </div>
        </section>
    </div>
</div>

<!-- Modal Definitions -->
<?php
// Generate basic modal variations
$modal_variations = [
    'small' => ['size' => 'small', 'title' => 'Small Modal', 'content' => 'This is a small modal example with minimal content.'],
    'medium' => ['size' => 'medium', 'title' => 'Medium Modal', 'content' => 'This is a medium-sized modal with moderate content. It demonstrates the default modal size and styling.'],
    'large' => ['size' => 'large', 'title' => 'Large Modal', 'content' => 'This is a large modal with extensive content. It provides more space for complex forms, detailed information, or rich media content. Large modals are ideal for comprehensive interfaces that require more screen real estate.'],
    'fullscreen' => ['size' => 'fullscreen', 'title' => 'Fullscreen Modal', 'content' => 'This fullscreen modal takes up the entire viewport. It\'s perfect for immersive experiences, complex workflows, or when you need maximum available space.']
];

foreach ($modal_variations as $key => $config) {
    echo $modal_component->render([
        'modal_id' => "demo-modal-{$key}",
        'modal_size' => $config['size'],
        'title' => $config['title'],
        'content' => $config['content'],
        'footer_content' => '<button type="button" class="button button-secondary" data-modal-close>Close</button>'
    ]);
}

// Generate position variations
$position_variations = [
    'center' => ['position' => 'center', 'title' => 'Center Position'],
    'top' => ['position' => 'top', 'title' => 'Top Position'],
    'bottom' => ['position' => 'bottom', 'title' => 'Bottom Position'],
    'left' => ['position' => 'left', 'title' => 'Left Position'],
    'right' => ['position' => 'right', 'title' => 'Right Position']
];

foreach ($position_variations as $key => $config) {
    echo $modal_component->render([
        'modal_id' => "demo-modal-{$key}",
        'modal_position' => $config['position'],
        'title' => $config['title'],
        'content' => "This modal is positioned at the {$config['position']} of the viewport.",
        'footer_content' => '<button type="button" class="button button-secondary" data-modal-close">Close</button>'
    ]);
}

// Booking Modal
echo $booking_modal->render([
    'modal_id' => 'demo-booking-modal',
    'treatment_type' => 'consultation'
]);

// Treatment Information Modals
$treatments = [
    'botox' => [
        'title' => 'Botox Treatment Information',
        'content' => '
            <div class="treatment-info">
                <div class="treatment-overview">
                    <h4>What is Botox?</h4>
                    <p>Botox is a purified protein that temporarily relaxes facial muscles to reduce the appearance of fine lines and wrinkles.</p>
                </div>
                <div class="treatment-benefits">
                    <h4>Benefits</h4>
                    <ul>
                        <li>Reduces forehead lines and crow\'s feet</li>
                        <li>Minimally invasive procedure</li>
                        <li>Quick treatment (15-30 minutes)</li>
                        <li>No downtime required</li>
                    </ul>
                </div>
                <div class="treatment-process">
                    <h4>Treatment Process</h4>
                    <ol>
                        <li>Consultation and assessment</li>
                        <li>Marking of injection sites</li>
                        <li>Application of topical anesthetic (if needed)</li>
                        <li>Precise injections using fine needles</li>
                        <li>Post-treatment care instructions</li>
                    </ol>
                </div>
                <div class="treatment-results">
                    <h4>Expected Results</h4>
                    <p>Results typically appear within 3-7 days and last 3-4 months. Most patients see a significant reduction in targeted wrinkles.</p>
                </div>
            </div>
        '
    ],
    'fillers' => [
        'title' => 'Dermal Fillers Information',
        'content' => '
            <div class="treatment-info">
                <div class="treatment-overview">
                    <h4>What are Dermal Fillers?</h4>
                    <p>Dermal fillers are injectable gels that restore volume, smooth wrinkles, and enhance facial contours.</p>
                </div>
                <div class="treatment-types">
                    <h4>Types Available</h4>
                    <ul>
                        <li>Hyaluronic Acid Fillers (Juvederm, Restylane)</li>
                        <li>Calcium Hydroxylapatite (Radiesse)</li>
                        <li>Poly-L-lactic Acid (Sculptra)</li>
                    </ul>
                </div>
                <div class="treatment-areas">
                    <h4>Treatment Areas</h4>
                    <ul>
                        <li>Nasolabial folds (smile lines)</li>
                        <li>Marionette lines</li>
                        <li>Lip enhancement</li>
                        <li>Cheek augmentation</li>
                        <li>Under-eye hollows</li>
                    </ul>
                </div>
            </div>
        '
    ],
    'laser' => [
        'title' => 'Laser Resurfacing Information',
        'content' => '
            <div class="treatment-info">
                <div class="treatment-overview">
                    <h4>What is Laser Resurfacing?</h4>
                    <p>Laser resurfacing uses focused light energy to improve skin texture, reduce wrinkles, and address various skin concerns.</p>
                </div>
                <div class="laser-types">
                    <h4>Types of Laser Treatments</h4>
                    <ul>
                        <li>Fractional CO2 Laser</li>
                        <li>Erbium Laser</li>
                        <li>IPL (Intense Pulsed Light)</li>
                        <li>Nd:YAG Laser</li>
                    </ul>
                </div>
                <div class="treatment-conditions">
                    <h4>Conditions Treated</h4>
                    <ul>
                        <li>Fine lines and wrinkles</li>
                        <li>Age spots and sun damage</li>
                        <li>Acne scars</li>
                        <li>Uneven skin tone</li>
                        <li>Large pores</li>
                    </ul>
                </div>
            </div>
        '
    ]
];

foreach ($treatments as $key => $treatment) {
    echo $modal_component->render([
        'modal_id' => "demo-treatment-{$key}",
        'modal_type' => 'treatment',
        'modal_class' => 'modal-treatment-info',
        'modal_size' => 'large',
        'title' => $treatment['title'],
        'content' => $treatment['content'],
        'footer_content' => '
            <button type="button" class="button button-primary" data-modal-target="demo-booking-modal">
                Book Consultation
            </button>
            <button type="button" class="button button-secondary" data-modal-close>
                Close
            </button>
        '
    ]);
}

// Accessibility Test Modal
echo $modal_component->render([
    'modal_id' => 'demo-accessibility-modal',
    'title' => 'Accessibility Test Modal',
    'content' => '
        <div class="accessibility-test-content">
            <p>This modal demonstrates keyboard navigation and screen reader support:</p>
            <div class="form-group">
                <label for="test-input-1">First Input:</label>
                <input type="text" id="test-input-1" placeholder="Tab to this field">
            </div>
            <div class="form-group">
                <label for="test-select">Select Option:</label>
                <select id="test-select">
                    <option>Option 1</option>
                    <option>Option 2</option>
                    <option>Option 3</option>
                </select>
            </div>
            <div class="form-group">
                <label for="test-textarea">Text Area:</label>
                <textarea id="test-textarea" rows="3" placeholder="Focus will cycle through all elements"></textarea>
            </div>
            <div class="checkbox-group">
                <label>
                    <input type="checkbox"> Test checkbox
                </label>
            </div>
        </div>
    ',
    'footer_content' => '
        <button type="button" class="button button-primary">Save</button>
        <button type="button" class="button button-secondary" data-modal-close>Cancel</button>
    '
]);

// Screen Reader Test Modal
echo $modal_component->render([
    'modal_id' => 'demo-screenreader-modal',
    'title' => 'Screen Reader Support Test',
    'aria_label' => 'Screen reader test dialog',
    'content' => '
        <div class="screenreader-test-content">
            <div role="alert" class="sr-announcement">
                This modal demonstrates screen reader announcements and ARIA support.
            </div>
            <p>Screen reader users will hear:</p>
            <ul>
                <li>Modal opening and closing announcements</li>
                <li>Proper heading structure</li>
                <li>Form labels and descriptions</li>
                <li>Button roles and states</li>
            </ul>
            <div aria-live="polite" id="status-region">
                Status updates will be announced here.
            </div>
        </div>
    ',
    'footer_content' => '
        <button type="button" class="button button-primary" onclick="document.getElementById(\'status-region\').textContent = \'Button activated successfully\'">
            Test Status Update
        </button>
        <button type="button" class="button button-secondary" data-modal-close>Close</button>
    '
]);

// Nested Modal Parent
echo $modal_component->render([
    'modal_id' => 'demo-modal-nested-parent',
    'title' => 'Parent Modal',
    'content' => '
        <p>This is the parent modal. Click the button below to open a child modal:</p>
        <div class="nested-demo-content">
            <p>Parent modal content remains accessible in the background.</p>
        </div>
    ',
    'footer_content' => '
        <button type="button" class="button button-primary" data-modal-target="demo-modal-nested-child">
            Open Child Modal
        </button>
        <button type="button" class="button button-secondary" data-modal-close>Close Parent</button>
    '
]);

// Nested Modal Child
echo $modal_component->render([
    'modal_id' => 'demo-modal-nested-child',
    'modal_size' => 'small',
    'title' => 'Child Modal',
    'content' => '
        <p>This is a child modal opened from the parent modal.</p>
        <p>Focus management ensures proper keyboard navigation between nested modals.</p>
    ',
    'footer_content' => '
        <button type="button" class="button button-secondary" data-modal-close>Close Child</button>
    '
]);
?>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    // Gallery Modal Demo
    document.getElementById('demo-gallery-trigger').addEventListener('click', function() {
        const galleryImages = [
            {
                src: 'https://via.placeholder.com/800x600/4f46e5/ffffff?text=Before+Treatment',
                alt: 'Before treatment image',
                caption: 'Before Treatment - Patient showing signs of aging'
            },
            {
                src: 'https://via.placeholder.com/800x600/059669/ffffff?text=After+Treatment',
                alt: 'After treatment image',
                caption: 'After Treatment - Visible improvement in skin texture'
            },
            {
                src: 'https://via.placeholder.com/800x600/dc2626/ffffff?text=Treatment+Process',
                alt: 'Treatment process image',
                caption: 'During Treatment - Professional application'
            }
        ];

        if (window.GalleryModal) {
            window.GalleryModal.create(galleryImages, 0);
        }
    });

    // Confirmation Modal Demos
    document.getElementById('demo-delete-confirmation').addEventListener('click', function() {
        if (window.ConfirmationModal) {
            window.ConfirmationModal.create({
                title: 'Delete Item',
                message: 'Are you sure you want to delete this item? This action cannot be undone.',
                confirmText: 'Delete',
                cancelText: 'Cancel',
                confirmAction: function() {
                    alert('Item deleted successfully!');
                }
            });
        }
    });

    document.getElementById('demo-cancel-appointment').addEventListener('click', function() {
        if (window.ConfirmationModal) {
            window.ConfirmationModal.create({
                title: 'Cancel Appointment',
                message: 'Are you sure you want to cancel your appointment? You may be charged a cancellation fee.',
                confirmText: 'Yes, Cancel',
                cancelText: 'Keep Appointment',
                confirmAction: function() {
                    alert('Appointment cancelled.');
                }
            });
        }
    });

    document.getElementById('demo-save-confirmation').addEventListener('click', function() {
        if (window.ConfirmationModal) {
            window.ConfirmationModal.create({
                title: 'Save Changes',
                message: 'Do you want to save your changes before leaving?',
                confirmText: 'Save',
                cancelText: 'Don\'t Save',
                confirmAction: function() {
                    alert('Changes saved successfully!');
                }
            });
        }
    });

    // Dynamic Content Modal
    document.getElementById('demo-dynamic-content').addEventListener('click', function() {
        if (window.modalManager) {
            const modalId = window.modalManager.createModal({
                title: 'Loading Content...',
                content: '<div class="loading-spinner">Loading...</div>',
                size: 'medium'
            });

            // Simulate AJAX load
            setTimeout(() => {
                const modal = document.getElementById(modalId);
                const body = modal.querySelector('.modal-body');
                body.innerHTML = `
                    <h4>Dynamic Content Loaded</h4>
                    <p>This content was loaded dynamically after the modal opened.</p>
                    <div class="dynamic-data">
                        <ul>
                            <li>Load time: 2 seconds</li>
                            <li>Content type: HTML</li>
                            <li>Status: Success</li>
                        </ul>
                    </div>
                `;

                const title = modal.querySelector('.modal-title');
                if (title) {
                    title.textContent = 'Dynamic Content';
                }
            }, 2000);
        }
    });

    // Performance Testing
    document.getElementById('demo-animation-test').addEventListener('click', function() {
        const resultsDiv = document.getElementById('performance-results');
        resultsDiv.style.display = 'block';

        let frameCount = 0;
        let startTime;
        let openTime, closeTime;

        // Performance monitoring
        function countFrames() {
            frameCount++;
            requestAnimationFrame(countFrames);
        }
        countFrames();

        // Test modal performance
        if (window.modalManager) {
            startTime = performance.now();

            const modalId = window.modalManager.createModal({
                title: 'Performance Test Modal',
                content: 'Testing animation performance...',
                size: 'medium'
            });

            // Measure open time
            setTimeout(() => {
                openTime = performance.now() - startTime;
                document.getElementById('open-time').textContent = openTime.toFixed(2) + 'ms';

                // Close and measure close time
                setTimeout(() => {
                    const closeStart = performance.now();
                    window.modalManager.closeModal(modalId);

                    setTimeout(() => {
                        closeTime = performance.now() - closeStart;
                        document.getElementById('close-time').textContent = closeTime.toFixed(2) + 'ms';

                        // Calculate frame rate
                        const frameRate = Math.round(frameCount / ((performance.now() - startTime) / 1000));
                        document.getElementById('frame-rate').textContent = frameRate + ' fps';
                    }, 300);
                }, 1000);
            }, 300);
        }
    });

    // Multiple Modals Test
    document.getElementById('demo-multiple-modals').addEventListener('click', function() {
        if (window.modalManager) {
            for (let i = 1; i <= 5; i++) {
                setTimeout(() => {
                    window.modalManager.createModal({
                        title: `Modal ${i}`,
                        content: `This is modal number ${i} of 5. Testing multiple modal performance.`,
                        size: 'small',
                        position: i % 2 === 0 ? 'left' : 'right'
                    });
                }, i * 200);
            }
        }
    });
});
</script>

<style>
.modal-demo-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: var(--spacing-8);
}

.demo-header {
    text-align: center;
    margin-bottom: var(--spacing-12);
}

.demo-title {
    font-size: 2.5rem;
    color: var(--color-primary-700);
    margin-bottom: var(--spacing-4);
}

.demo-description {
    font-size: 1.125rem;
    color: var(--color-text-secondary);
    max-width: 600px;
    margin: 0 auto var(--spacing-8);
}

.demo-metrics {
    display: flex;
    justify-content: center;
    gap: var(--spacing-6);
    flex-wrap: wrap;
}

.metric-item {
    padding: var(--spacing-3) var(--spacing-4);
    background: var(--color-surface-secondary);
    border-radius: var(--border-radius-md);
    font-size: 0.875rem;
}

.metric-label {
    font-weight: 600;
    color: var(--color-text-tertiary);
}

.metric-value {
    color: var(--color-primary-600);
    font-weight: 700;
}

.demo-sections {
    display: grid;
    gap: var(--spacing-12);
}

.demo-section {
    padding: var(--spacing-8);
    background: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
}

.section-title {
    color: var(--color-primary-700);
    margin-bottom: var(--spacing-4);
    border-bottom: 2px solid var(--color-primary-100);
    padding-bottom: var(--spacing-2);
}

.section-description {
    color: var(--color-text-secondary);
    margin-bottom: var(--spacing-6);
}

.demo-group {
    margin-bottom: var(--spacing-8);
}

.demo-group:last-child {
    margin-bottom: 0;
}

.demo-group h3 {
    color: var(--color-text-primary);
    margin-bottom: var(--spacing-4);
}

.button-group {
    display: flex;
    gap: var(--spacing-3);
    flex-wrap: wrap;
}

.treatment-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-3);
}

.demo-note {
    margin-top: var(--spacing-3);
    font-size: 0.875rem;
    color: var(--color-text-tertiary);
    font-style: italic;
}

.accessibility-notes ul {
    list-style: none;
    padding: 0;
}

.accessibility-notes li {
    margin-bottom: var(--spacing-2);
    padding: var(--spacing-2);
    background: var(--color-surface-secondary);
    border-radius: var(--border-radius-sm);
}

.accessibility-notes kbd {
    background: var(--color-text-primary);
    color: white;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 0.75rem;
    font-weight: 600;
}

.performance-results {
    margin-top: var(--spacing-4);
    padding: var(--spacing-4);
    background: var(--color-surface-secondary);
    border-radius: var(--border-radius-md);
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: var(--spacing-3);
}

.metric {
    text-align: center;
}

.metric-name {
    display: block;
    font-size: 0.875rem;
    color: var(--color-text-tertiary);
    margin-bottom: var(--spacing-1);
}

.metric-value {
    display: block;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--color-primary-600);
}

.treatment-info h4 {
    color: var(--color-primary-600);
    margin-top: var(--spacing-6);
    margin-bottom: var(--spacing-3);
}

.treatment-info h4:first-child {
    margin-top: 0;
}

.treatment-info ul,
.treatment-info ol {
    margin-bottom: var(--spacing-4);
}

@media (max-width: 768px) {
    .demo-metrics {
        flex-direction: column;
        align-items: center;
    }

    .button-group {
        justify-content: center;
    }

    .treatment-buttons {
        grid-template-columns: 1fr;
    }

    .performance-results {
        grid-template-columns: 1fr;
    }
}
</style>
