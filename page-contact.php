<?php
/**
 * Template Name: Contact & Component Playground
 * Description: Contact page for PreetiDreams Medical Spa + Design System Playground
 * Sprint 3 - Design System Foundation Testing Environment
 */

get_header(); ?>

<main id="main" class="site-main contact-page component-playground" role="main">

    <!-- Component Playground Sections -->
    <div class="container">

        <!-- Typography Playground -->
        <section class="playground-section">
            <h2>Typography System</h2>

            <div class="component-demo-grid">
                <div class="component-demo">
                    <div class="demo-label">Headings</div>
                    <h1>H1 - Main Heading</h1>
                    <h2>H2 - Section Heading</h2>
                    <h3>H3 - Subsection</h3>
                    <h4>H4 - Component Title</h4>
                    <h5>H5 - Card Title</h5>
                    <h6>H6 - Small Heading</h6>
                </div>

                <div class="component-demo">
                    <div class="demo-label">Text Sizes</div>
                    <p class="text-xs">Extra Small Text (12px)</p>
                    <p class="text-sm">Small Text (14px)</p>
                    <p class="text-base">Base Text (16px)</p>
                    <p class="text-lg">Large Text (18px)</p>
                    <p class="text-xl">Extra Large Text (20px)</p>
                    <p class="text-2xl">2XL Text (24px)</p>
                </div>

                <div class="component-demo">
                    <div class="demo-label">Text Colors</div>
                    <p class="text-primary">Primary Text Color</p>
                    <p class="text-secondary">Secondary Text Color</p>
                    <p class="text-muted">Muted Text Color</p>
                    <p style="color: var(--color-primary);">Brand Primary</p>
                    <p style="color: var(--color-accent);">Brand Accent</p>
                </div>
            </div>
        </section>

        <!-- Button Playground -->
        <section class="playground-section">
            <h2>Button System</h2>

            <div class="component-demo-grid">
                <div class="component-demo">
                    <div class="demo-label">Button Variants</div>
                    <div class="flex flex-col" style="gap: var(--space-3);">
                        <button class="btn btn--primary">Primary Button</button>
                        <button class="btn btn--secondary">Secondary Button</button>
                        <button class="btn btn--accent">Accent Button</button>
                        <button class="btn btn--outline">Outline Button</button>
                        <button class="btn btn--ghost">Ghost Button</button>
                    </div>
                </div>

                <div class="component-demo">
                    <div class="demo-label">Button Sizes</div>
                    <div class="flex flex-col" style="gap: var(--space-3);">
                        <button class="btn btn--primary btn--sm">Small Button</button>
                        <button class="btn btn--primary">Regular Button</button>
                        <button class="btn btn--primary btn--lg">Large Button</button>
                        <button class="btn btn--primary btn--full">Full Width Button</button>
                    </div>
                </div>

                <div class="component-demo">
                    <div class="demo-label">Button States</div>
                    <div class="flex flex-col" style="gap: var(--space-3);">
                        <button class="btn btn--primary success">Success State</button>
                        <button class="btn btn--primary warning">Warning State</button>
                        <button class="btn btn--primary error">Error State</button>
                        <button class="btn btn--primary" disabled>Disabled State</button>
                        <button class="btn btn--primary loading" data-loading="true">Loading State</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Card Playground -->
        <section class="playground-section">
            <h2>Card System</h2>

            <div class="component-demo-grid">
                <div class="component-demo">
                    <div class="demo-label">Basic Cards</div>
                    <div class="card">
                        <div class="card__header">
                            <h5>Card Header</h5>
                        </div>
                        <div class="card__body">
                            <p>This is the card body content. It demonstrates the card layout and spacing.</p>
                        </div>
                        <div class="card__footer">
                            <button class="btn btn--primary btn--sm">Action</button>
                        </div>
                    </div>
                </div>

                <div class="component-demo">
                    <div class="demo-label">Elevated Cards</div>
                    <div class="card card--elevated">
                        <div class="card__body">
                            <h5>Elevated Card</h5>
                            <p>This card has enhanced shadow and elevation effects.</p>
                            <button class="btn btn--outline btn--sm">Learn More</button>
                        </div>
                    </div>
                </div>

                <div class="component-demo">
                    <div class="demo-label">Interactive Cards</div>
                    <div class="card card--interactive card--bordered">
                        <div class="card__body">
                            <h5>Interactive Card</h5>
                            <p>Hover me to see interaction effects!</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Form Playground -->
        <section class="playground-section">
            <h2>Form System</h2>

            <div class="component-demo-grid">
                <div class="component-demo">
                    <div class="demo-label">Form Elements</div>
                    <form>
                        <div class="form-group">
                            <label class="form-label" for="demo-name">Full Name</label>
                            <input type="text" id="demo-name" class="form-input" placeholder="Enter your name">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="demo-email">Email Address</label>
                            <input type="email" id="demo-email" class="form-input" placeholder="your@email.com">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="demo-select">Treatment Interest</label>
                            <select id="demo-select" class="form-select">
                                <option>Select a treatment</option>
                                <option>Facial Treatments</option>
                                <option>Body Treatments</option>
                                <option>Laser Treatments</option>
                                <option>Injectable Treatments</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="demo-message">Message</label>
                            <textarea id="demo-message" class="form-textarea" placeholder="Tell us about your needs..."></textarea>
                        </div>

                        <button type="submit" class="btn btn--primary">Submit Form</button>
                    </form>
                </div>

                <div class="component-demo">
                    <div class="demo-label">Form Sizes</div>
                    <div class="form-group">
                        <label class="form-label">Small Input</label>
                        <input type="text" class="form-input form-input--sm" placeholder="Small input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Regular Input</label>
                        <input type="text" class="form-input" placeholder="Regular input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Large Input</label>
                        <input type="text" class="form-input form-input--lg" placeholder="Large input">
                    </div>
                </div>

                <div class="component-demo">
                    <div class="demo-label">Form States</div>
                    <div class="form-group">
                        <label class="form-label">Success State</label>
                        <input type="text" class="form-input success" value="Valid input">
                        <div class="success-text">This field is valid</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Error State</label>
                        <input type="text" class="form-input error" value="Invalid input">
                        <div class="error-text">This field has an error</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Warning State</label>
                        <input type="text" class="form-input warning" value="Warning input">
                        <div class="warning-text">Please check this field</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Medical Spa Specific Components -->
        <section class="playground-section">
            <h2>Medical Spa Components</h2>

            <div class="component-demo-grid">
                <div class="component-demo">
                    <div class="demo-label">Treatment Cards</div>
                    <div class="treatment-card treatment-card--facial">
                        <div class="card__body">
                            <h5>Facial Treatment</h5>
                            <p>Rejuvenating facial treatments for glowing skin.</p>
                            <button class="btn btn--primary btn--sm">Book Now</button>
                        </div>
                    </div>
                    <div class="treatment-card treatment-card--laser" style="margin-top: var(--space-4);">
                        <div class="card__body">
                            <h5>Laser Treatment</h5>
                            <p>Advanced laser therapy for skin resurfacing.</p>
                            <button class="btn btn--accent btn--sm">Learn More</button>
                        </div>
                    </div>
                </div>

                <div class="component-demo">
                    <div class="demo-label">Professional Trust Indicators</div>
                    <div class="trust-indicator trust-indicator--certification">
                        Board Certified Dermatologist
                    </div>
                    <div class="trust-indicator trust-indicator--safety" style="margin-top: var(--space-2);">
                        FDA Approved Treatments
                    </div>
                    <div class="trust-indicator trust-indicator--luxury" style="margin-top: var(--space-2);">
                        Luxury Spa Experience
                    </div>
                    <div class="trust-indicator trust-indicator--technology" style="margin-top: var(--space-2);">
                        Latest Technology
                    </div>
                </div>

                <div class="component-demo">
                    <div class="demo-label">Professional Badges</div>
                    <div class="flex flex-col" style="gap: var(--space-2);">
                        <span class="badge badge--certification">Certified</span>
                        <span class="badge badge--safety">Safe Treatment</span>
                        <span class="badge badge--luxury">Premium</span>
                        <span class="badge badge--technology">Advanced Tech</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Layout & Spacing Playground -->
        <section class="playground-section">
            <h2>Layout & Spacing System</h2>

            <div class="component-demo-grid">
                <div class="component-demo">
                    <div class="demo-label">Grid System</div>
                    <div class="grid grid-3" style="gap: var(--space-2);">
                        <div style="background: var(--color-surface-secondary); padding: var(--space-3); border-radius: var(--radius-md);">Grid Item 1</div>
                        <div style="background: var(--color-surface-secondary); padding: var(--space-3); border-radius: var(--radius-md);">Grid Item 2</div>
                        <div style="background: var(--color-surface-secondary); padding: var(--space-3); border-radius: var(--radius-md);">Grid Item 3</div>
                    </div>
                </div>

                <div class="component-demo">
                    <div class="demo-label">Flexbox System</div>
                    <div class="flex flex-between" style="background: var(--color-surface-secondary); padding: var(--space-4); border-radius: var(--radius-md);">
                        <span>Left</span>
                        <span>Center</span>
                        <span>Right</span>
                    </div>
                </div>

                <div class="component-demo">
                    <div class="demo-label">Spacing Scale</div>
                    <div style="background: var(--color-surface-secondary); border-radius: var(--radius-md); overflow: hidden;">
                        <div style="background: var(--color-primary); height: var(--space-1); margin-bottom: var(--space-1);"></div>
                        <div style="background: var(--color-primary); height: var(--space-2); margin-bottom: var(--space-1);"></div>
                        <div style="background: var(--color-primary); height: var(--space-4); margin-bottom: var(--space-1);"></div>
                        <div style="background: var(--color-primary); height: var(--space-6);"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Actual Contact Page Content -->
        <section class="playground-section">
            <h2>Contact PreetiDreams Medical Spa</h2>

            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('consultation-form'); ?>>
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>

                    <!-- Enhanced Contact Form -->
                    <div class="form-section">
                        <h3 class="section-title">Book Your Consultation</h3>
                        <form class="consultation-flow">
                            <div class="grid grid-2">
                                <div class="form-group">
                                    <label class="form-label" for="contact-name">Full Name *</label>
                                    <input type="text" id="contact-name" name="name" class="form-input" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="contact-phone">Phone Number *</label>
                                    <input type="tel" id="contact-phone" name="phone" class="form-input" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="contact-email">Email Address *</label>
                                    <input type="email" id="contact-email" name="email" class="form-input" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="contact-treatment">Treatment Interest</label>
                                    <select id="contact-treatment" name="treatment" class="form-select">
                                        <option value="">Select a treatment</option>
                                        <option value="facial">Facial Treatments</option>
                                        <option value="body">Body Treatments</option>
                                        <option value="laser">Laser Treatments</option>
                                        <option value="injectable">Injectable Treatments</option>
                                        <option value="skincare">Medical Skincare</option>
                                        <option value="consultation">General Consultation</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="contact-message">Tell us about your goals and concerns</label>
                                <textarea id="contact-message" name="message" class="form-textarea" rows="5" placeholder="Share your skincare goals, concerns, or questions..."></textarea>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn--primary btn--lg">Book Consultation</button>
                                <button type="button" class="btn btn--outline">Call Instead</button>
                            </div>
                        </form>
                    </div>
                </article>
            <?php endwhile; ?>
        </section>

    </div>

    <!-- Design System Status Indicator -->
    <div class="design-system-status" data-status="healthy"></div>

</main>

<?php get_footer(); ?>
