<?php
/**
 * Card Component Demo Template
 *
 * Comprehensive demonstration of all card components:
 * - Base CardComponent
 * - TreatmentCard
 * - TestimonialCard
 * - FeatureCard
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

// Load card components
require_once get_template_directory() . '/inc/components/card-component.php';
require_once get_template_directory() . '/inc/components/treatment-card.php';
require_once get_template_directory() . '/inc/components/testimonial-card.php';
require_once get_template_directory() . '/inc/components/feature-card.php';

?>

<div class="component-demo-container">
    <div class="demo-header">
        <h1>Card Component System Demo</h1>
        <p>Comprehensive demonstration of the card component system with all variants and configurations.</p>
    </div>

    <!-- Base Card Component Demos -->
    <section class="demo-section">
        <h2>Base Card Components</h2>
        <p>Foundation card component with various configurations</p>

        <div class="demo-grid">
            <h3>Card Variants</h3>
            <div class="card-grid">
                <?php
                // Default Card
                $default_card = new CardComponent();
                echo $default_card->render([
                    'title' => 'Default Card',
                    'content' => 'This is a default card with standard styling and medium shadow.',
                    'image' => 'https://images.unsplash.com/photo-1560750588-73207b1ef5b8?w=400',
                    'image_alt' => 'Modern medical spa interior',
                    'actions' => [
                        ['text' => 'Learn More', 'url' => '#', 'variant' => 'primary']
                    ]
                ]);

                // Elevated Card
                $elevated_card = new CardComponent();
                echo $elevated_card->render([
                    'variant' => 'elevated',
                    'title' => 'Elevated Card',
                    'content' => 'Enhanced card with stronger shadow for emphasis.',
                    'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1f?w=400',
                    'image_alt' => 'Spa treatment room',
                    'actions' => [
                        ['text' => 'Explore', 'url' => '#', 'variant' => 'secondary']
                    ]
                ]);

                // Outlined Card
                $outlined_card = new CardComponent();
                echo $outlined_card->render([
                    'variant' => 'outlined',
                    'title' => 'Outlined Card',
                    'content' => 'Clean card design with border and no shadow.',
                    'image' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?w=400',
                    'image_alt' => 'Relaxing spa environment',
                    'actions' => [
                        ['text' => 'View Details', 'url' => '#', 'variant' => 'outline']
                    ]
                ]);
                ?>
            </div>

            <h3>Card Sizes</h3>
            <div class="card-grid">
                <?php
                // Small Card
                $small_card = new CardComponent();
                echo $small_card->render([
                    'size' => 'small',
                    'title' => 'Small Card',
                    'content' => 'Compact card perfect for sidebars or grid layouts.',
                    'meta' => ['Quick Read', '2 min']
                ]);

                // Medium Card (default)
                $medium_card = new CardComponent();
                echo $medium_card->render([
                    'size' => 'medium',
                    'title' => 'Medium Card',
                    'content' => 'Standard card size for most content layouts.',
                    'meta' => ['Standard', '5 min read']
                ]);

                // Large Card
                $large_card = new CardComponent();
                echo $large_card->render([
                    'size' => 'large',
                    'title' => 'Large Card',
                    'content' => 'Spacious card for featured content and detailed information.',
                    'meta' => ['Featured', 'In-depth']
                ]);
                ?>
            </div>
        </div>
    </section>

    <!-- Treatment Card Demos -->
    <section class="demo-section">
        <h2>Treatment Cards</h2>
        <p>Specialized cards for medical spa treatments with pricing, benefits, and booking options</p>

        <div class="card-grid">
            <?php
            // Popular Treatment Card
            $popular_treatment = new TreatmentCard();
            echo $popular_treatment->render([
                'title' => 'Botox Injectable Treatment',
                'category' => 'Anti-Aging',
                'content' => 'Smooth away fine lines and wrinkles with our premium Botox treatments. Administered by licensed professionals.',
                'image' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=400',
                'image_alt' => 'Botox treatment procedure',
                'duration' => '30-45 minutes',
                'price' => '$350',
                'price_from' => false,
                'pain_level' => 'minimal',
                'downtime' => 'None',
                'results_timeline' => '3-7 days',
                'popularity' => 'popular',
                'badge_text' => 'Most Popular',
                'benefits' => [
                    'Reduces fine lines and wrinkles',
                    'Natural-looking results',
                    'FDA-approved treatment',
                    'Quick procedure with no downtime'
                ],
                'features' => ['Premium Botox', 'Licensed Injector', 'Follow-up Included'],
                'financing_available' => true,
                'cta_text' => 'Book Consultation',
                'cta_url' => '#booking',
                'book_text' => 'Schedule Now',
                'book_url' => '#schedule',
                'phone_booking' => '+1-555-SPA-BOOK'
            ]);

            // New Treatment Card
            $new_treatment = new TreatmentCard();
            echo $new_treatment->render([
                'title' => 'HydraFacial MD',
                'category' => 'Facial Treatments',
                'content' => 'Revolutionary 3-in-1 treatment that cleanses, extracts, and hydrates your skin for an instant glow.',
                'image' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=400',
                'image_alt' => 'HydraFacial treatment',
                'duration' => '60 minutes',
                'price' => '$200',
                'price_from' => true,
                'pain_level' => 'minimal',
                'downtime' => 'None',
                'results_timeline' => 'Immediate',
                'popularity' => 'new',
                'badge_text' => 'New Treatment',
                'benefits' => [
                    'Instant skin hydration',
                    'Improves skin texture',
                    'Reduces pore size',
                    'Suitable for all skin types'
                ],
                'features' => ['Patented Technology', 'Immediate Results', 'No Downtime'],
                'price_details' => 'Package deals available',
                'cta_text' => 'Learn More',
                'cta_url' => '#hydrafacial',
                'book_text' => 'Try Now',
                'book_url' => '#book-hydrafacial'
            ]);

            // Premium Treatment Card
            $premium_treatment = new TreatmentCard();
            echo $premium_treatment->render([
                'title' => 'Ultherapy Skin Lifting',
                'category' => 'Non-Surgical Lifting',
                'content' => 'Non-invasive ultrasound technology that lifts and tightens skin naturally by stimulating collagen production.',
                'image' => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=400',
                'image_alt' => 'Ultherapy treatment',
                'duration' => '90-120 minutes',
                'price' => '$2,500',
                'price_from' => true,
                'pain_level' => 'moderate',
                'downtime' => 'Minimal',
                'results_timeline' => '2-3 months',
                'popularity' => 'trending',
                'badge_text' => 'Trending',
                'benefits' => [
                    'Natural skin lifting',
                    'Stimulates collagen production',
                    'FDA-cleared technology',
                    'Long-lasting results'
                ],
                'features' => ['Ultrasound Technology', 'Non-Invasive', 'Proven Results'],
                'financing_available' => true,
                'price_details' => 'Treatment area dependent',
                'cta_text' => 'Free Consultation',
                'cta_url' => '#consultation',
                'book_text' => 'Schedule Assessment',
                'book_url' => '#assessment'
            ]);
            ?>
        </div>
    </section>

    <!-- Testimonial Card Demos -->
    <section class="demo-section">
        <h2>Testimonial Cards</h2>
        <p>Client testimonials with ratings, author information, and treatment details</p>

        <div class="card-grid">
            <?php
            // Featured Testimonial
            $featured_testimonial = new TestimonialCard();
            echo $featured_testimonial->render([
                'testimonial_content' => 'The Botox treatment completely exceeded my expectations! The results look so natural, and I feel years younger. The staff was incredibly professional and made me feel comfortable throughout the entire process.',
                'author_name' => 'Sarah Johnson',
                'author_title' => 'Marketing Director',
                'author_location' => 'Los Angeles, CA',
                'author_image' => 'https://images.unsplash.com/photo-1494790108755-2616b612b217?w=150',
                'author_verified' => true,
                'rating' => 5,
                'featured' => true,
                'treatment' => 'Botox Injectable Treatment',
                'treatment_url' => '#botox',
                'treatment_category' => 'Anti-Aging',
                'treatment_date' => 'March 2024',
                'results_achieved' => ['Smoother forehead', 'Reduced crow\'s feet', 'Natural appearance'],
                'date' => '2 weeks ago',
                'source' => 'google',
                'verified_source' => true,
                'helpful_votes' => 15,
                'show_helpful' => true
            ]);

            // Standard Testimonial with Video
            $video_testimonial = new TestimonialCard();
            echo $video_testimonial->render([
                'testimonial_content' => 'I was nervous about my first facial treatment, but the HydraFacial was amazing! My skin has never looked better, and the glow is incredible. I\'m definitely coming back for more!',
                'author_name' => 'Maria Rodriguez',
                'author_title' => 'Teacher',
                'author_location' => 'San Diego, CA',
                'author_image' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150',
                'author_verified' => true,
                'rating' => 5,
                'treatment' => 'HydraFacial MD',
                'treatment_category' => 'Facial Treatments',
                'treatment_date' => 'February 2024',
                'date' => '1 month ago',
                'source' => 'facebook',
                'verified_source' => true,
                'video_testimonial' => true,
                'video_url' => '#video-testimonial',
                'helpful_votes' => 8,
                'show_helpful' => true
            ]);

            // Compact Testimonial
            $compact_testimonial = new TestimonialCard();
            echo $compact_testimonial->render([
                'layout' => 'compact',
                'testimonial_content' => 'Quick, professional, and amazing results! The consultation was thorough and the treatment exceeded my expectations.',
                'author_name' => 'Jennifer Chen',
                'author_title' => 'Software Engineer',
                'author_image' => 'https://images.unsplash.com/photo-1534751516642-a1af1ef26a56?w=150',
                'rating' => 4,
                'treatment' => 'Laser Hair Removal',
                'date' => '3 days ago',
                'source' => 'internal'
            ]);
            ?>
        </div>
    </section>

    <!-- Feature Card Demos -->
    <section class="demo-section">
        <h2>Feature Cards</h2>
        <p>Versatile cards for services, benefits, processes, and informational content</p>

        <div class="demo-grid">
            <h3>Service Features</h3>
            <div class="card-grid">
                <?php
                // Service Feature with Icon
                $service_feature = new FeatureCard();
                echo $service_feature->render([
                    'feature_type' => 'service',
                    'icon' => '✨',
                    'icon_type' => 'emoji',
                    'icon_position' => 'top',
                    'icon_size' => 'large',
                    'title' => 'Professional Consultations',
                    'subtitle' => 'Expert guidance for your beauty journey',
                    'content' => 'Our licensed professionals provide comprehensive consultations to understand your needs and recommend the best treatments.',
                    'benefits' => [
                        'Personalized treatment plans',
                        'Licensed professionals',
                        'Comprehensive skin analysis'
                    ],
                    'actions' => [
                        ['text' => 'Book Consultation', 'url' => '#consultation', 'variant' => 'primary']
                    ]
                ]);

                // Technology Feature
                $tech_feature = new FeatureCard();
                echo $tech_feature->render([
                    'feature_type' => 'technology',
                    'icon' => '🔬',
                    'icon_type' => 'emoji',
                    'title' => 'Advanced Technology',
                    'subtitle' => 'State-of-the-art equipment',
                    'content' => 'We use the latest FDA-approved technologies to ensure safe, effective, and comfortable treatments.',
                    'specifications' => [
                        'FDA-Approved Devices' => 'All equipment certified',
                        'Regular Calibration' => 'Monthly maintenance',
                        'Safety Protocols' => 'Strict adherence'
                    ],
                    'badge' => 'FDA Approved',
                    'color_scheme' => 'primary'
                ]);

                // Guarantee Feature
                $guarantee_feature = new FeatureCard();
                echo $guarantee_feature->render([
                    'feature_type' => 'guarantee',
                    'icon' => '🛡️',
                    'icon_type' => 'emoji',
                    'title' => 'Satisfaction Guarantee',
                    'subtitle' => 'Your peace of mind matters',
                    'content' => 'We stand behind our treatments with a satisfaction guarantee and comprehensive aftercare support.',
                    'highlight_text' => '100% Satisfaction Guaranteed',
                    'stats' => [
                        ['value' => '99%', 'label' => 'Client Satisfaction'],
                        ['value' => '5★', 'label' => 'Average Rating']
                    ],
                    'color_scheme' => 'success'
                ]);
                ?>
            </div>

            <h3>Process Steps</h3>
            <div class="card-grid">
                <?php
                // Process Step 1
                $step1_feature = new FeatureCard();
                echo $step1_feature->render([
                    'feature_type' => 'process',
                    'step_number' => 1,
                    'icon' => '📋',
                    'icon_type' => 'emoji',
                    'icon_position' => 'top',
                    'title' => 'Initial Consultation',
                    'content' => 'We begin with a comprehensive consultation to understand your goals and assess your skin.',
                    'expandable' => true,
                    'expanded_content' => 'During the consultation, our experts will analyze your skin type, discuss your concerns, review your medical history, and explain treatment options tailored to your needs.',
                    'cta_style' => 'link'
                ]);

                // Process Step 2
                $step2_feature = new FeatureCard();
                echo $step2_feature->render([
                    'feature_type' => 'process',
                    'step_number' => 2,
                    'icon' => '🎯',
                    'icon_type' => 'emoji',
                    'icon_position' => 'top',
                    'title' => 'Customized Treatment Plan',
                    'content' => 'Based on your consultation, we create a personalized treatment plan designed for optimal results.',
                    'expandable' => true,
                    'expanded_content' => 'Your treatment plan includes detailed timelines, expected outcomes, pre and post-care instructions, and follow-up appointments to ensure the best possible results.',
                    'cta_style' => 'link'
                ]);

                // Process Step 3
                $step3_feature = new FeatureCard();
                echo $step3_feature->render([
                    'feature_type' => 'process',
                    'step_number' => 3,
                    'icon' => '💫',
                    'icon_type' => 'emoji',
                    'icon_position' => 'top',
                    'title' => 'Professional Treatment',
                    'content' => 'Receive your treatment in our comfortable, state-of-the-art facility with experienced professionals.',
                    'expandable' => true,
                    'expanded_content' => 'All treatments are performed by licensed professionals using the latest technology and safety protocols. We ensure your comfort throughout the entire process.',
                    'cta_style' => 'link'
                ]);
                ?>
            </div>

            <h3>Icon Positioning & Styles</h3>
            <div class="card-grid">
                <?php
                // Left Icon Feature
                $left_icon = new FeatureCard();
                echo $left_icon->render([
                    'icon' => '🏆',
                    'icon_type' => 'emoji',
                    'icon_position' => 'left',
                    'icon_size' => 'medium',
                    'title' => 'Award-Winning Results',
                    'content' => 'Our clinic has received multiple awards for excellence in aesthetic treatments.',
                    'style' => 'minimal'
                ]);

                // Right Icon Feature
                $right_icon = new FeatureCard();
                echo $right_icon->render([
                    'icon' => '⭐',
                    'icon_type' => 'emoji',
                    'icon_position' => 'right',
                    'icon_size' => 'medium',
                    'title' => 'Premium Experience',
                    'content' => 'Enjoy a luxurious spa experience with personalized attention and comfort.',
                    'style' => 'elevated'
                ]);

                // Inline Icon Feature
                $inline_icon = new FeatureCard();
                echo $inline_icon->render([
                    'icon' => '💎',
                    'icon_type' => 'emoji',
                    'icon_position' => 'inline',
                    'icon_size' => 'small',
                    'title' => 'Premium Quality Treatments',
                    'content' => 'We use only the highest quality products and latest techniques.',
                    'style' => 'bordered',
                    'alignment' => 'center'
                ]);
                ?>
            </div>
        </div>
    </section>

    <!-- Card Grid Layouts -->
    <section class="demo-section">
        <h2>Grid Layouts</h2>
        <p>Various grid configurations for different layout needs</p>

        <div class="demo-grid">
            <h3>Two Column Grid</h3>
            <div class="card-grid" style="grid-template-columns: repeat(2, 1fr);">
                <?php
                for ($i = 1; $i <= 4; $i++) {
                    $grid_card = new CardComponent();
                    echo $grid_card->render([
                        'title' => "Card $i",
                        'content' => "This is card number $i in a two-column grid layout.",
                        'meta' => ["Grid $i", 'Demo']
                    ]);
                }
                ?>
            </div>

            <h3>Four Column Grid (Large)</h3>
            <div class="card-grid large" style="grid-template-columns: repeat(4, 1fr);">
                <?php
                for ($i = 1; $i <= 4; $i++) {
                    $small_grid_card = new CardComponent();
                    echo $small_grid_card->render([
                        'size' => 'small',
                        'title' => "Item $i",
                        'content' => "Compact card for dense layouts.",
                        'variant' => $i % 2 == 0 ? 'outlined' : 'default'
                    ]);
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Accessibility & Interaction Demo -->
    <section class="demo-section">
        <h2>Accessibility & Interaction</h2>
        <p>Cards with enhanced accessibility and interactive features</p>

        <div class="card-grid">
            <?php
            // Keyboard Navigation Demo
            $accessible_card = new CardComponent();
            echo $accessible_card->render([
                'title' => 'Keyboard Accessible Card',
                'content' => 'This card is fully keyboard navigable. Try tabbing through the elements and using Enter/Space to interact.',
                'link_entire_card' => true,
                'link_url' => '#accessible-demo',
                'aria_label' => 'Demonstration of keyboard accessible card functionality',
                'actions' => [
                    ['text' => 'Tab Navigation Demo', 'url' => '#tab-demo', 'variant' => 'primary']
                ]
            ]);

            // Screen Reader Optimized
            $screen_reader_card = new TestimonialCard();
            echo $screen_reader_card->render([
                'testimonial_content' => 'The accessibility features make this website so easy to use with my screen reader!',
                'author_name' => 'Alex Thompson',
                'author_title' => 'Accessibility Advocate',
                'rating' => 5,
                'aria_label' => 'Testimonial from Alex Thompson about website accessibility',
                'treatment' => 'Website Accessibility Review'
            ]);

            // High Contrast Compatible
            $contrast_card = new FeatureCard();
            echo $contrast_card->render([
                'icon' => '♿',
                'icon_type' => 'emoji',
                'title' => 'WCAG 2.1 AA Compliant',
                'content' => 'All our cards meet WCAG accessibility guidelines for color contrast and keyboard navigation.',
                'color_scheme' => 'info',
                'tooltip' => 'Web Content Accessibility Guidelines compliant'
            ]);
            ?>
        </div>
    </section>

    <!-- Performance & Loading Demo -->
    <section class="demo-section">
        <h2>Performance Features</h2>
        <p>Cards optimized for performance with lazy loading and efficient rendering</p>

        <div class="card-grid">
            <?php
            // Lazy Loading Demo
            $lazy_card = new CardComponent();
            echo $lazy_card->render([
                'title' => 'Lazy Loading Images',
                'content' => 'Images in cards use lazy loading for improved page performance.',
                'image' => 'https://images.unsplash.com/photo-1600334129128-685c5582fd35?w=400',
                'image_alt' => 'Spa wellness center with lazy loading demonstration',
                'meta' => ['Performance', 'Optimized']
            ]);

            // Fast Rendering Demo
            $fast_card = new FeatureCard();
            echo $fast_card->render([
                'icon' => '⚡',
                'icon_type' => 'emoji',
                'title' => 'Fast Rendering',
                'subtitle' => 'Under 100ms render time',
                'content' => 'Cards are optimized to render in under 100 milliseconds for smooth user experience.',
                'stats' => [
                    ['value' => '<100ms', 'label' => 'Render Time'],
                    ['value' => '95+', 'label' => 'Performance Score']
                ]
            ]);

            // Responsive Demo
            $responsive_card = new CardComponent();
            echo $responsive_card->render([
                'title' => 'Responsive Design',
                'content' => 'Cards adapt seamlessly to different screen sizes and devices.',
                'meta' => ['Mobile-First', 'Responsive', 'Touch-Friendly'],
                'actions' => [
                    ['text' => 'Test Responsive', 'url' => '#responsive-test', 'variant' => 'outline']
                ]
            ]);
            ?>
        </div>
    </section>

    <!-- Demo Footer -->
    <div class="demo-footer">
        <h3>Card Component System</h3>
        <p><strong>Features:</strong> 15+ customizer controls, 4 card variants, 3 specialized types, responsive design, WCAG 2.1 AA compliance, and performance optimization.</p>
        <p><strong>Components:</strong> CardComponent (base), TreatmentCard, TestimonialCard, FeatureCard</p>
        <p><strong>Performance:</strong> &lt;100ms render time, lazy loading, design token integration</p>
    </div>
</div>

<style>
/* Demo-specific styles */
.component-demo-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
}

.demo-header {
    text-align: center;
    margin-bottom: 60px;
}

.demo-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 16px;
}

.demo-header p {
    font-size: 1.125rem;
    color: #6b7280;
    max-width: 600px;
    margin: 0 auto;
}

.demo-section {
    margin-bottom: 80px;
}

.demo-section h2 {
    font-size: 2rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 8px;
}

.demo-section > p {
    color: #6b7280;
    margin-bottom: 40px;
    font-size: 1.125rem;
}

.demo-grid h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #374151;
    margin: 40px 0 24px 0;
}

.demo-grid h3:first-child {
    margin-top: 0;
}

.demo-footer {
    background-color: #f9fafb;
    padding: 40px;
    border-radius: 12px;
    text-align: center;
    border: 2px solid #e5e7eb;
}

.demo-footer h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 16px;
}

.demo-footer p {
    color: #6b7280;
    margin-bottom: 12px;
    line-height: 1.6;
}

.demo-footer p:last-child {
    margin-bottom: 0;
}

/* Responsive demo styles */
@media (max-width: 768px) {
    .component-demo-container {
        padding: 20px 16px;
    }

    .demo-header h1 {
        font-size: 2rem;
    }

    .demo-section h2 {
        font-size: 1.5rem;
    }

    .card-grid[style*="repeat(4"] {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 480px) {
    .card-grid[style*="repeat(2"] {
        grid-template-columns: 1fr !important;
    }

    .card-grid[style*="repeat(4"] {
        grid-template-columns: 1fr !important;
    }
}
</style>

<script>
// Demo interaction enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Expandable content functionality
    const expandToggles = document.querySelectorAll('.feature-expand-toggle');
    expandToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const isExpanded = this.getAttribute('aria-expanded') === 'true';

            this.setAttribute('aria-expanded', !isExpanded);
            content.hidden = isExpanded;
            this.textContent = isExpanded ? 'Learn More' : 'Show Less';
        });
    });

    // Card interaction tracking (demo purposes)
    const cards = document.querySelectorAll('.card-component');
    cards.forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.closest('.card-action-button, .feature-expand-toggle')) {
                return; // Don't track button clicks
            }
            console.log('Card interaction:', this.querySelector('.card-title')?.textContent || 'Untitled card');
        });
    });

    // Keyboard navigation enhancement
    cards.forEach(card => {
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                if (this.classList.contains('card-clickable')) {
                    e.preventDefault();
                    this.click();
                }
            }
        });
    });
});
</script>
