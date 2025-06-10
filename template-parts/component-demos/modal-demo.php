<?php
/**
 * Modal Component Demo Template
 *
 * Comprehensive demonstration of all modal/dialog components including
 * booking modals, treatment info modals, confirmation modals, and gallery modals.
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

// Load modal component
require_once get_template_directory() . '/inc/components/modal-component.php';

?>

<div class="component-demo-container">
    <div class="demo-header">
        <h1>Modal Component System Demo</h1>
        <p>Comprehensive demonstration of modal/dialog components with medical spa specializations, accessibility compliance, and interactive functionality.</p>
    </div>

    <!-- Modal Triggers Section -->
    <section class="demo-section">
        <h2>Modal Types & Triggers</h2>
        <p>Click the buttons below to test different modal types and configurations.</p>

        <div class="demo-grid">
            <!-- Basic Modal Triggers -->
            <div class="demo-group">
                <h3>Basic Modal Types</h3>
                <div class="button-group">
                    <button type="button" class="btn btn-primary" data-modal-trigger="modal-demo-basic">
                        Basic Modal
                    </button>
                    <button type="button" class="btn btn-secondary" data-modal-trigger="modal-demo-confirmation">
                        Confirmation Modal
                    </button>
                    <button type="button" class="btn btn-info" data-modal-trigger="modal-demo-alert">
                        Alert Modal
                    </button>
                </div>
            </div>

            <!-- Size Variations -->
            <div class="demo-group">
                <h3>Modal Sizes</h3>
                <div class="button-group">
                    <button type="button" class="btn btn-outline" data-modal-trigger="modal-demo-small">
                        Small Modal
                    </button>
                    <button type="button" class="btn btn-outline" data-modal-trigger="modal-demo-medium">
                        Medium Modal
                    </button>
                    <button type="button" class="btn btn-outline" data-modal-trigger="modal-demo-large">
                        Large Modal
                    </button>
                    <button type="button" class="btn btn-outline" data-modal-trigger="modal-demo-fullscreen">
                        Fullscreen Modal
                    </button>
                </div>
            </div>

            <!-- Medical Spa Specializations -->
            <div class="demo-group">
                <h3>Medical Spa Modals</h3>
                <div class="button-group">
                    <button type="button" class="btn btn-primary" data-modal-trigger="modal-demo-booking">
                        📅 Book Consultation
                    </button>
                    <button type="button" class="btn btn-secondary" data-modal-trigger="modal-demo-treatment-info">
                        💉 Treatment Information
                    </button>
                    <button type="button" class="btn btn-info" data-modal-trigger="modal-demo-gallery">
                        🖼️ Before/After Gallery
                    </button>
                    <button type="button" class="btn btn-success" data-modal-trigger="modal-demo-consent">
                        📋 Consent Form
                    </button>
                </div>
            </div>

            <!-- Animation Types -->
            <div class="demo-group">
                <h3>Animation Styles</h3>
                <div class="button-group">
                    <button type="button" class="btn btn-outline" data-modal-trigger="modal-demo-fade">
                        Fade Animation
                    </button>
                    <button type="button" class="btn btn-outline" data-modal-trigger="modal-demo-scale">
                        Scale Animation
                    </button>
                    <button type="button" class="btn btn-outline" data-modal-trigger="modal-demo-slide">
                        Slide Animation
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Configuration Section -->
    <section class="demo-section">
        <h2>Modal Configuration Options</h2>
        <div class="demo-grid">
            <div class="config-group">
                <h3>Accessibility Features</h3>
                <ul>
                    <li>✅ Keyboard navigation (Tab, Shift+Tab, Escape)</li>
                    <li>✅ Focus trapping within modal</li>
                    <li>✅ Screen reader announcements</li>
                    <li>✅ ARIA labels and descriptions</li>
                    <li>✅ High contrast mode support</li>
                    <li>✅ Reduced motion preference respect</li>
                </ul>
            </div>

            <div class="config-group">
                <h3>Interaction Options</h3>
                <ul>
                    <li>🖱️ Close on backdrop click</li>
                    <li>⌨️ Close on Escape key</li>
                    <li>❌ Close button in header</li>
                    <li>🔄 Focus restoration</li>
                    <li>📱 Touch-friendly mobile design</li>
                    <li>⚡ Smooth animations</li>
                </ul>
            </div>

            <div class="config-group">
                <h3>Medical Spa Features</h3>
                <ul>
                    <li>🏥 HIPAA compliance ready</li>
                    <li>📅 Appointment booking integration</li>
                    <li>💉 Treatment information display</li>
                    <li>🖼️ Before/after gallery with zoom</li>
                    <li>📋 Consent form management</li>
                    <li>🔒 Secure form submissions</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- JavaScript API Section -->
    <section class="demo-section">
        <h2>JavaScript API Demonstration</h2>
        <div class="demo-grid">
            <div class="api-group">
                <h3>Programmatic Control</h3>
                <div class="button-group">
                    <button type="button" class="btn btn-primary" onclick="MedSpaModal.open('modal-demo-basic')">
                        Open via API
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="MedSpaModal.closeAll()">
                        Close All Modals
                    </button>
                    <button type="button" class="btn btn-info" onclick="checkModalStatus()">
                        Check Modal Status
                    </button>
                </div>
                <div id="api-output" class="api-output">
                    <p>Click buttons above to see API responses...</p>
                </div>
            </div>

            <div class="api-group">
                <h3>Callback Demonstration</h3>
                <div class="button-group">
                    <button type="button" class="btn btn-primary" onclick="openModalWithCallbacks()">
                        Modal with Callbacks
                    </button>
                </div>
                <div id="callback-output" class="api-output">
                    <p>Callback events will appear here...</p>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Definitions -->

<!-- Basic Modal -->
<?php
$basic_modal = new ModalComponent();
echo $basic_modal->render([
    'modal_id' => 'modal-demo-basic',
    'modal_type' => 'default',
    'modal_size' => 'medium',
    'title' => 'Basic Modal Example',
    'content' => '
        <p>This is a basic modal with default styling and behavior. It demonstrates the fundamental modal functionality including:</p>
        <ul>
            <li>Backdrop overlay with blur effect</li>
            <li>Centered positioning</li>
            <li>Smooth scale animation</li>
            <li>Keyboard accessibility</li>
            <li>Focus management</li>
        </ul>
        <p>You can close this modal by clicking the X button, pressing Escape, or clicking outside the modal area.</p>
    ',
    'show_header' => true,
    'show_footer' => false,
    'show_close_button' => true,
    'close_on_backdrop' => true,
    'close_on_escape' => true
]);
?>

<!-- Confirmation Modal -->
<?php
$confirmation_modal = new ModalComponent();
echo $confirmation_modal->render([
    'modal_id' => 'modal-demo-confirmation',
    'modal_type' => 'confirmation',
    'modal_size' => 'small',
    'title' => 'Confirm Your Action',
    'content' => '
        <p>Are you sure you want to proceed with this action? This change cannot be undone.</p>
        <p><strong>Warning:</strong> This is a demonstration confirmation modal showing how user confirmations should be handled in medical spa applications.</p>
    ',
    'show_header' => true,
    'show_footer' => true,
    'show_close_button' => true
]);
?>

<!-- Alert Modal -->
<?php
$alert_modal = new ModalComponent();
echo $alert_modal->render([
    'modal_id' => 'modal-demo-alert',
    'modal_type' => 'alert',
    'modal_size' => 'small',
    'title' => 'Important Notice',
    'content' => '
        <p><strong>Alert:</strong> This is an important message that requires your attention.</p>
        <p>Alert modals are used for critical information, error messages, or important notifications that users must acknowledge.</p>
    ',
    'show_header' => true,
    'show_footer' => true,
    'show_close_button' => true,
    'close_on_backdrop' => false,
    'close_on_escape' => true
]);
?>

<!-- Size Variation Modals -->
<?php
// Small Modal
$small_modal = new ModalComponent();
echo $small_modal->render([
    'modal_id' => 'modal-demo-small',
    'modal_size' => 'small',
    'title' => 'Small Modal',
    'content' => '<p>This is a small modal perfect for quick confirmations, alerts, or simple forms.</p>'
]);

// Medium Modal
$medium_modal = new ModalComponent();
echo $medium_modal->render([
    'modal_id' => 'modal-demo-medium',
    'modal_size' => 'medium',
    'title' => 'Medium Modal',
    'content' => '<p>This is a medium modal suitable for most content types including forms, detailed information, and moderate amounts of text.</p>'
]);

// Large Modal
$large_modal = new ModalComponent();
echo $large_modal->render([
    'modal_id' => 'modal-demo-large',
    'modal_size' => 'large',
    'title' => 'Large Modal',
    'content' => '
        <p>This is a large modal designed for extensive content, complex forms, or detailed information displays.</p>
        <div class="content-sections">
            <h4>Section 1: Treatment Overview</h4>
            <p>Large modals are perfect for displaying comprehensive treatment information, including procedures, benefits, risks, and recovery information.</p>

            <h4>Section 2: Before and After</h4>
            <p>They can accommodate multiple images, detailed descriptions, and comparison content.</p>

            <h4>Section 3: Pricing Information</h4>
            <p>Complete pricing breakdowns, package options, and financing information can be displayed clearly.</p>
        </div>
    '
]);

// Fullscreen Modal
$fullscreen_modal = new ModalComponent();
echo $fullscreen_modal->render([
    'modal_id' => 'modal-demo-fullscreen',
    'modal_size' => 'fullscreen',
    'title' => 'Fullscreen Modal',
    'content' => '
        <div class="fullscreen-content">
            <h3>Fullscreen Experience</h3>
            <p>Fullscreen modals provide an immersive experience perfect for:</p>
            <ul>
                <li>Image galleries and virtual tours</li>
                <li>Video consultations</li>
                <li>Comprehensive forms and questionnaires</li>
                <li>Educational content and presentations</li>
            </ul>
            <div class="fullscreen-demo-content">
                <h4>Virtual Spa Tour</h4>
                <p>This space could contain a virtual tour of your medical spa facilities, allowing potential clients to explore your environment before their visit.</p>
            </div>
        </div>
    '
]);
?>

<!-- Medical Spa Specialized Modals -->

<!-- Booking Modal -->
<?php
$booking_modal = new ModalComponent();
echo $booking_modal->render([
    'modal_id' => 'modal-demo-booking',
    'modal_type' => 'form',
    'modal_size' => 'large',
    'title' => 'Book Your Consultation',
    'medical_context' => true,
    'treatment_related' => true,
    'content' => '
        <div class="booking-form-container">
            <div class="booking-intro">
                <p>Ready to start your aesthetic journey? Book a personalized consultation with our expert practitioners.</p>
            </div>

            <form class="consultation-booking-form" method="post" action="#" data-form-type="consultation">
                <div class="form-row">
                    <div class="form-group">
                        <label for="booking-first-name">First Name *</label>
                        <input type="text" id="booking-first-name" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="booking-last-name">Last Name *</label>
                        <input type="text" id="booking-last-name" name="last_name" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="booking-email">Email Address *</label>
                        <input type="email" id="booking-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="booking-phone">Phone Number *</label>
                        <input type="tel" id="booking-phone" name="phone" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="booking-treatment">Treatment Interest</label>
                    <select id="booking-treatment" name="treatment_interest">
                        <option value="">Select a treatment...</option>
                        <option value="botox">Botox Injections</option>
                        <option value="dermal-fillers">Dermal Fillers</option>
                        <option value="laser-hair-removal">Laser Hair Removal</option>
                        <option value="chemical-peel">Chemical Peels</option>
                        <option value="microneedling">Microneedling</option>
                        <option value="consultation">General Consultation</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="booking-concerns">Areas of Concern</label>
                    <textarea id="booking-concerns" name="concerns" rows="3" placeholder="Tell us about your aesthetic goals and any specific concerns..."></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="booking-preferred-date">Preferred Date</label>
                        <input type="date" id="booking-preferred-date" name="preferred_date">
                    </div>
                    <div class="form-group">
                        <label for="booking-preferred-time">Preferred Time</label>
                        <select id="booking-preferred-time" name="preferred_time">
                            <option value="">Select time...</option>
                            <option value="morning">Morning (9 AM - 12 PM)</option>
                            <option value="afternoon">Afternoon (12 PM - 4 PM)</option>
                            <option value="evening">Evening (4 PM - 7 PM)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms_accepted" required>
                        I acknowledge that I have read and agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a>
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Request Consultation</button>
                    <button type="button" class="btn btn-secondary" data-modal-close="modal-demo-booking">Cancel</button>
                </div>
            </form>
        </div>
    ',
    'show_header' => true,
    'show_footer' => false,
    'custom_classes' => ['modal-booking'],
    'aria_label' => 'Consultation booking form'
]);
?>

<!-- Treatment Info Modal -->
<?php
$treatment_modal = new ModalComponent();
echo $treatment_modal->render([
    'modal_id' => 'modal-demo-treatment-info',
    'modal_type' => 'default',
    'modal_size' => 'large',
    'title' => 'Botox Injectable Treatment',
    'medical_context' => true,
    'treatment_related' => true,
    'content' => '
        <div class="treatment-info-container">
            <div class="treatment-overview">
                <div class="treatment-image">
                    <img src="https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=600&h=400&fit=crop" alt="Botox treatment procedure" />
                </div>
                <div class="treatment-details">
                    <div class="treatment-meta">
                        <span class="treatment-category">Anti-Aging</span>
                        <span class="treatment-duration">30-45 minutes</span>
                        <span class="treatment-price">Starting at $350</span>
                    </div>
                </div>
            </div>

            <div class="treatment-content">
                <div class="treatment-section">
                    <h4>Overview</h4>
                    <p>Botox is a popular cosmetic treatment that temporarily reduces the appearance of facial wrinkles and fine lines. Our experienced practitioners use FDA-approved Botox to help you achieve natural-looking results.</p>
                </div>

                <div class="treatment-section">
                    <h4>Benefits</h4>
                    <ul>
                        <li>Reduces fine lines and wrinkles</li>
                        <li>Prevents new wrinkles from forming</li>
                        <li>Quick, minimally invasive procedure</li>
                        <li>No downtime required</li>
                        <li>Natural-looking results</li>
                        <li>FDA-approved treatment</li>
                    </ul>
                </div>

                <div class="treatment-section">
                    <h4>Treatment Process</h4>
                    <ol>
                        <li><strong>Consultation:</strong> Discuss your goals and assess treatment areas</li>
                        <li><strong>Preparation:</strong> Clean and mark injection sites</li>
                        <li><strong>Injection:</strong> Precise placement of Botox units</li>
                        <li><strong>Recovery:</strong> Immediate return to normal activities</li>
                    </ol>
                </div>

                <div class="treatment-section">
                    <h4>Expected Results</h4>
                    <p>Results typically become visible within 3-7 days, with full effects appearing within 2 weeks. The treatment lasts 3-4 months, after which touch-up treatments can maintain your results.</p>
                </div>

                <div class="treatment-section">
                    <h4>Safety Information</h4>
                    <p>Botox is considered very safe when administered by qualified professionals. Minor side effects may include temporary redness or slight swelling at injection sites.</p>
                </div>
            </div>

            <div class="treatment-actions">
                <button type="button" class="btn btn-primary" onclick="MedSpaModal.close(\'modal-demo-treatment-info\'); MedSpaModal.open(\'modal-demo-booking\');">
                    Book This Treatment
                </button>
                <button type="button" class="btn btn-secondary" data-modal-trigger="modal-demo-gallery">
                    View Before & After
                </button>
            </div>
        </div>
    ',
    'show_header' => true,
    'show_footer' => false,
    'custom_classes' => ['modal-treatment-info'],
    'data_attributes' => ['treatment-id' => 'botox-injectable']
]);
?>

<!-- Gallery Modal -->
<?php
$gallery_modal = new ModalComponent();
echo $gallery_modal->render([
    'modal_id' => 'modal-demo-gallery',
    'modal_type' => 'gallery',
    'modal_size' => 'fullscreen',
    'title' => 'Before & After Gallery',
    'content' => '
        <div class="gallery-container">
            <div class="gallery-controls">
                <button type="button" class="gallery-prev" aria-label="Previous image">‹</button>
                <button type="button" class="gallery-next" aria-label="Next image">›</button>
                <div class="gallery-counter">1 / 4</div>
            </div>

            <div class="gallery-main">
                <div class="before-after-container">
                    <div class="before-image">
                        <h4>Before</h4>
                        <img src="https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=400&h=400&fit=crop" alt="Before treatment" />
                    </div>
                    <div class="after-image">
                        <h4>After</h4>
                        <img src="https://images.unsplash.com/photo-1560750588-73207b1ef5b8?w=400&h=400&fit=crop" alt="After treatment" />
                    </div>
                </div>

                <div class="gallery-info">
                    <h3>Botox Forehead Treatment</h3>
                    <p>Results after 2 weeks, showing significant reduction in forehead lines and wrinkles.</p>
                    <div class="gallery-meta">
                        <span>Treatment: Botox Injectable</span>
                        <span>Units Used: 20</span>
                        <span>Age: 42</span>
                    </div>
                </div>
            </div>

            <div class="gallery-thumbnails">
                <button class="thumbnail active" data-image="1">
                    <img src="https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=100&h=100&fit=crop" alt="Thumbnail 1" />
                </button>
                <button class="thumbnail" data-image="2">
                    <img src="https://images.unsplash.com/photo-1560750588-73207b1ef5b8?w=100&h=100&fit=crop" alt="Thumbnail 2" />
                </button>
                <button class="thumbnail" data-image="3">
                    <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1f?w=100&h=100&fit=crop" alt="Thumbnail 3" />
                </button>
                <button class="thumbnail" data-image="4">
                    <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?w=100&h=100&fit=crop" alt="Thumbnail 4" />
                </button>
            </div>
        </div>
    ',
    'show_header' => false,
    'show_footer' => false,
    'show_close_button' => true,
    'custom_classes' => ['modal-gallery'],
    'close_on_backdrop' => true
]);
?>

<!-- Animation Demonstration Modals -->
<?php
// Fade Animation Modal
$fade_modal = new ModalComponent();
echo $fade_modal->render([
    'modal_id' => 'modal-demo-fade',
    'animation_type' => 'fade',
    'title' => 'Fade Animation',
    'content' => '<p>This modal uses a fade animation effect. The modal gradually appears and disappears with opacity transitions.</p>',
    'custom_classes' => ['modal-animation-fade']
]);

// Scale Animation Modal
$scale_modal = new ModalComponent();
echo $scale_modal->render([
    'modal_id' => 'modal-demo-scale',
    'animation_type' => 'scale',
    'title' => 'Scale Animation',
    'content' => '<p>This modal uses a scale animation effect. The modal grows from 90% to 100% size when opening.</p>',
    'custom_classes' => ['modal-animation-scale']
]);

// Slide Animation Modal
$slide_modal = new ModalComponent();
echo $slide_modal->render([
    'modal_id' => 'modal-demo-slide',
    'animation_type' => 'slide',
    'title' => 'Slide Animation',
    'content' => '<p>This modal uses a slide animation effect. The modal slides down from above when opening.</p>',
    'custom_classes' => ['modal-animation-slide']
]);
?>

<!-- Consent Modal -->
<?php
$consent_modal = new ModalComponent();
echo $consent_modal->render([
    'modal_id' => 'modal-demo-consent',
    'modal_type' => 'form',
    'modal_size' => 'large',
    'title' => 'Treatment Consent Form',
    'medical_context' => true,
    'hipaa_compliant' => true,
    'content' => '
        <div class="consent-form-container">
            <div class="consent-notice">
                <p><strong>Important:</strong> Please read and acknowledge the following information before proceeding with your treatment.</p>
            </div>

            <form class="consent-form" method="post" action="#">
                <div class="consent-section">
                    <h4>Treatment Understanding</h4>
                    <label class="checkbox-label">
                        <input type="checkbox" name="understanding" required>
                        I understand the nature of the proposed treatment and have had all my questions answered.
                    </label>
                </div>

                <div class="consent-section">
                    <h4>Risks and Complications</h4>
                    <label class="checkbox-label">
                        <input type="checkbox" name="risks" required>
                        I understand the potential risks and complications associated with this treatment.
                    </label>
                </div>

                <div class="consent-section">
                    <h4>Alternative Treatments</h4>
                    <label class="checkbox-label">
                        <input type="checkbox" name="alternatives" required>
                        I have been informed about alternative treatment options.
                    </label>
                </div>

                <div class="consent-section">
                    <h4>Photography Consent</h4>
                    <label class="checkbox-label">
                        <input type="checkbox" name="photography">
                        I consent to before and after photographs being taken for medical records and potential marketing use.
                    </label>
                </div>

                <div class="consent-section">
                    <h4>HIPAA Privacy</h4>
                    <label class="checkbox-label">
                        <input type="checkbox" name="hipaa" required>
                        I acknowledge receipt of the HIPAA Privacy Notice and consent to treatment.
                    </label>
                </div>

                <div class="form-group">
                    <label for="patient-signature">Digital Signature (Type your full name)</label>
                    <input type="text" id="patient-signature" name="patient_signature" placeholder="Type your full legal name" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Submit Consent</button>
                    <button type="button" class="btn btn-secondary" data-modal-close="modal-demo-consent">Cancel</button>
                </div>
            </form>
        </div>
    ',
    'show_header' => true,
    'show_footer' => false,
    'custom_classes' => ['modal-consent'],
    'close_on_backdrop' => false,
    'close_on_escape' => false,
    'persistent' => true
]);
?>

<!-- Footer Modal Adds (to prevent backdrop clicks from closing modals) -->
<?php
// Add backdrop elements for each modal
$modal_ids = [
    'modal-demo-basic', 'modal-demo-confirmation', 'modal-demo-alert',
    'modal-demo-small', 'modal-demo-medium', 'modal-demo-large', 'modal-demo-fullscreen',
    'modal-demo-booking', 'modal-demo-treatment-info', 'modal-demo-gallery', 'modal-demo-consent',
    'modal-demo-fade', 'modal-demo-scale', 'modal-demo-slide'
];

foreach ($modal_ids as $modal_id) {
    echo '<div class="modal-backdrop modal-backdrop-clickable" data-modal-backdrop="' . esc_attr($modal_id) . '"></div>';
}
?>

<script>
// Demo JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    // API demonstration functions
    window.checkModalStatus = function() {
        const output = document.getElementById('api-output');
        const openModals = [];

        // Check each modal
        ['modal-demo-basic', 'modal-demo-confirmation', 'modal-demo-booking'].forEach(modalId => {
            if (window.MedSpaModal && window.MedSpaModal.isOpen(modalId)) {
                openModals.push(modalId);
            }
        });

        if (openModals.length > 0) {
            output.innerHTML = '<p><strong>Open modals:</strong> ' + openModals.join(', ') + '</p>';
        } else {
            output.innerHTML = '<p><strong>Status:</strong> No modals are currently open</p>';
        }
    };

    window.openModalWithCallbacks = function() {
        const output = document.getElementById('callback-output');
        output.innerHTML = '<p>Setting up callbacks...</p>';

        // Set callbacks for the basic modal
        if (window.modalManager) {
            window.modalManager.setCallback('modal-demo-basic', 'onOpen', function(config) {
                output.innerHTML += '<p>✅ <strong>onOpen:</strong> Modal ' + config.id + ' opened</p>';
            });

            window.modalManager.setCallback('modal-demo-basic', 'onClose', function(config) {
                output.innerHTML += '<p>❌ <strong>onClose:</strong> Modal ' + config.id + ' closed</p>';
            });
        }

        // Open the modal
        window.MedSpaModal.open('modal-demo-basic');
    };

    // Gallery modal navigation (demo functionality)
    let currentGalleryImage = 1;
    const totalGalleryImages = 4;

    function updateGalleryImage(imageIndex) {
        const counter = document.querySelector('.gallery-counter');
        if (counter) {
            counter.textContent = imageIndex + ' / ' + totalGalleryImages;
        }

        // Update active thumbnail
        document.querySelectorAll('.thumbnail').forEach((thumb, index) => {
            thumb.classList.toggle('active', index === imageIndex - 1);
        });
    }

    // Gallery controls
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('gallery-prev')) {
            currentGalleryImage = currentGalleryImage > 1 ? currentGalleryImage - 1 : totalGalleryImages;
            updateGalleryImage(currentGalleryImage);
        }

        if (event.target.classList.contains('gallery-next')) {
            currentGalleryImage = currentGalleryImage < totalGalleryImages ? currentGalleryImage + 1 : 1;
            updateGalleryImage(currentGalleryImage);
        }

        if (event.target.closest('.thumbnail')) {
            const imageIndex = parseInt(event.target.closest('.thumbnail').dataset.image);
            currentGalleryImage = imageIndex;
            updateGalleryImage(currentGalleryImage);
        }
    });

    // Form submission demo
    document.addEventListener('submit', function(event) {
        if (event.target.closest('.modal-container')) {
            event.preventDefault();
            alert('Form submission demo - In a real application, this would submit to your server.');
        }
    });

    console.log('Modal demo functionality initialized');
});
</script>

<style>
/* Demo-specific styles */
.component-demo-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.demo-header {
    text-align: center;
    margin-bottom: 3rem;
}

.demo-section {
    margin-bottom: 3rem;
    padding: 2rem;
    background: #f8fafc;
    border-radius: 8px;
}

.demo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.demo-group, .config-group, .api-group {
    background: white;
    padding: 1.5rem;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.button-group {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 1rem;
}

.api-output {
    margin-top: 1rem;
    padding: 1rem;
    background: #f3f4f6;
    border-radius: 4px;
    border-left: 4px solid #3b82f6;
    font-family: monospace;
    font-size: 0.875rem;
}

/* Modal content styling */
.booking-form-container .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.treatment-info-container .treatment-overview {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.treatment-meta {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

.treatment-meta span {
    padding: 0.25rem 0.75rem;
    background: #e5e7eb;
    border-radius: 4px;
    font-size: 0.875rem;
}

.treatment-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    justify-content: center;
}

.gallery-container {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.gallery-controls {
    position: absolute;
    top: 1rem;
    right: 1rem;
    z-index: 10;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.gallery-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.before-after-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.before-after-container img {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 8px;
}

.gallery-thumbnails {
    display: flex;
    gap: 1rem;
    justify-content: center;
    padding: 1rem;
}

.thumbnail {
    border: 2px solid transparent;
    border-radius: 4px;
    overflow: hidden;
    background: none;
    padding: 0;
    cursor: pointer;
}

.thumbnail.active {
    border-color: #3b82f6;
}

.thumbnail img {
    width: 60px;
    height: 60px;
    object-fit: cover;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .booking-form-container .form-row {
        grid-template-columns: 1fr;
    }

    .treatment-info-container .treatment-overview {
        grid-template-columns: 1fr;
    }

    .before-after-container {
        grid-template-columns: 1fr;
    }

    .treatment-actions {
        flex-direction: column;
    }
}
</style>
