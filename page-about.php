<?php
/**
 * Template Name: About Us - Dr. Preeti Sharma
 * Description: About Us page for Dr. Preeti Sharma's PreetiDreams Medical Spa
 * Solo practitioner with 4 expert staff serving 400+ clients in Arizona
 */

get_header(); ?>

<main id="main" class="site-main about-page">
    <?php while (have_posts()) : the_post(); ?>

        <!-- Dr. Preeti Hero Section -->
        <section class="dr-preeti-hero" role="banner">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1 class="hero-title"><?php esc_html_e('Meet Dr. Preeti Sharma, MD', 'preetidreams'); ?></h1>
                        <p class="hero-subtitle"><?php esc_html_e('Your Trusted Aesthetic Partner', 'preetidreams'); ?></p>
                        <div class="hero-quote">
                            <blockquote>
                                <?php esc_html_e('"For over 15 years, I\'ve dedicated my practice to helping Arizona clients achieve their aesthetic goals through personalized, physician-led treatments."', 'preetidreams'); ?>
                            </blockquote>
                        </div>
                        <div class="credentials-highlights">
                            <div class="credential-item">
                                <span class="credential-icon">✨</span>
                                <span class="credential-text"><?php esc_html_e('400+ Happy Clients Served', 'preetidreams'); ?></span>
                            </div>
                            <div class="credential-item">
                                <span class="credential-icon">🏥</span>
                                <span class="credential-text"><?php esc_html_e('Board-Certified Physician', 'preetidreams'); ?></span>
                            </div>
                            <div class="credential-item">
                                <span class="credential-icon">📍</span>
                                <span class="credential-text"><?php esc_html_e('Serving Glendale, Peoria & Scottsdale Areas', 'preetidreams'); ?></span>
                            </div>
                        </div>
                        <div class="hero-cta">
                            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-primary">
                                <?php esc_html_e('Schedule Your Consultation', 'preetidreams'); ?>
                            </a>
                        </div>
                    </div>
                    <div class="hero-image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('hero-banner', ['alt' => __('Dr. Preeti Sharma', 'preetidreams'), 'class' => 'hero-portrait']); ?>
                        <?php else : ?>
                            <div class="placeholder-image">
                                <div class="placeholder-content">
                                    <span class="placeholder-icon">👩‍⚕️</span>
                                    <p><?php esc_html_e('Professional Dr. Preeti Portrait', 'preetidreams'); ?></p>
                                    <small><?php esc_html_e('Medical coat, confident smile, Arizona medical spa setting', 'preetidreams'); ?></small>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Practice Story & Philosophy Section -->
        <section class="practice-story" role="region" aria-label="<?php esc_attr_e('Practice Story and Philosophy', 'preetidreams'); ?>">
            <div class="container">
                <div class="story-content">
                    <div class="story-header">
                        <h2><?php esc_html_e('My Practice Journey', 'preetidreams'); ?></h2>
                    </div>
                    <div class="story-text">
                        <p><?php esc_html_e('After years of medical training and experience, I founded PreetiDreams to provide Arizona clients with personalized aesthetic medicine care.', 'preetidreams'); ?></p>

                        <h3><?php esc_html_e('What sets my practice apart:', 'preetidreams'); ?></h3>

                        <div class="practice-differentiators">
                            <div class="differentiator-item">
                                <span class="differentiator-icon">👩‍⚕️</span>
                                <div class="differentiator-content">
                                    <h4><?php esc_html_e('Personal Physician Care', 'preetidreams'); ?></h4>
                                    <p><?php esc_html_e('Every treatment personally administered or supervised by me', 'preetidreams'); ?></p>
                                </div>
                            </div>

                            <div class="differentiator-item">
                                <span class="differentiator-icon">🤝</span>
                                <div class="differentiator-content">
                                    <h4><?php esc_html_e('Intimate Practice Setting', 'preetidreams'); ?></h4>
                                    <p><?php esc_html_e('Small practice means dedicated attention and personalized care', 'preetidreams'); ?></p>
                                </div>
                            </div>

                            <div class="differentiator-item">
                                <span class="differentiator-icon">📊</span>
                                <div class="differentiator-content">
                                    <h4><?php esc_html_e('Proven Results', 'preetidreams'); ?></h4>
                                    <p><?php esc_html_e('400+ satisfied clients trust our expertise and approach', 'preetidreams'); ?></p>
                                </div>
                            </div>

                            <div class="differentiator-item">
                                <span class="differentiator-icon">🏡</span>
                                <div class="differentiator-content">
                                    <h4><?php esc_html_e('Arizona Community Focus', 'preetidreams'); ?></h4>
                                    <p><?php esc_html_e('Proud to serve Glendale, Peoria, and Scottsdale communities', 'preetidreams'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Signature Treatments Section -->
        <section class="signature-treatments" role="region" aria-label="<?php esc_attr_e('Dr. Preeti\'s Signature Treatments', 'preetidreams'); ?>">
            <div class="container">
                <div class="section-header">
                    <h2><?php esc_html_e('My Specialty Services', 'preetidreams'); ?></h2>
                </div>

                <div class="treatments-grid">
                    <div class="treatment-card">
                        <div class="treatment-icon">💉</div>
                        <h3><?php esc_html_e('PRP Hair Restoration', 'preetidreams'); ?></h3>
                        <p><?php esc_html_e('Advanced hair growth stimulation and strand thickening treatments', 'preetidreams'); ?></p>
                        <a href="<?php echo esc_url(home_url('/services/prp-hair-restoration')); ?>" class="btn btn-outline">
                            <?php esc_html_e('Learn More', 'preetidreams'); ?>
                        </a>
                    </div>

                    <div class="treatment-card">
                        <div class="treatment-icon">✨</div>
                        <h3><?php esc_html_e('Injectable Artistry', 'preetidreams'); ?></h3>
                        <p><?php esc_html_e('Botox and dermal fillers to erase lines and restore volume', 'preetidreams'); ?></p>
                        <a href="<?php echo esc_url(home_url('/services/injectables')); ?>" class="btn btn-outline">
                            <?php esc_html_e('View Results', 'preetidreams'); ?>
                        </a>
                    </div>

                    <div class="treatment-card">
                        <div class="treatment-icon">💧</div>
                        <h3><?php esc_html_e('IV Therapy Wellness', 'preetidreams'); ?></h3>
                        <p><?php esc_html_e('Bounce Back Drips, Migraine Relief, Seasonal Allergy Boost', 'preetidreams'); ?></p>
                        <a href="<?php echo esc_url(home_url('/services/iv-therapy')); ?>" class="btn btn-outline">
                            <?php esc_html_e('Book Session', 'preetidreams'); ?>
                        </a>
                    </div>

                    <div class="treatment-card">
                        <div class="treatment-icon">🕸️</div>
                        <h3><?php esc_html_e('Sclerotherapy Solutions', 'preetidreams'); ?></h3>
                        <p><?php esc_html_e('Effective spider vein treatment for confident, clear skin', 'preetidreams'); ?></p>
                        <a href="<?php echo esc_url(home_url('/services/sclerotherapy')); ?>" class="btn btn-outline">
                            <?php esc_html_e('Schedule Treatment', 'preetidreams'); ?>
                        </a>
                    </div>
                </div>

                <div class="additional-treatments">
                    <h4><?php esc_html_e('Plus:', 'preetidreams'); ?></h4>
                    <p><?php esc_html_e('HydraFacial, Microneedling, PDO Thread Lifts & Laser Treatments', 'preetidreams'); ?></p>
                    <a href="<?php echo esc_url(home_url('/services')); ?>" class="btn btn-primary">
                        <?php esc_html_e('View All Services', 'preetidreams'); ?>
                    </a>
                </div>
            </div>
        </section>

        <!-- Expert Team Section -->
        <section class="expert-team" role="region" aria-label="<?php esc_attr_e('Our Expert Team of 4', 'preetidreams'); ?>">
            <div class="container">
                <div class="section-header">
                    <h2><?php esc_html_e('Our Expert Team', 'preetidreams'); ?></h2>
                    <p><?php esc_html_e('While I personally oversee every treatment, I\'m supported by 4 highly trained professionals who share my commitment to excellence:', 'preetidreams'); ?></p>
                </div>

                <div class="team-grid">
                    <div class="team-member">
                        <div class="member-icon">🎓</div>
                        <h3><?php esc_html_e('Medical Assistant Team', 'preetidreams'); ?></h3>
                        <p><?php esc_html_e('Specially trained in aesthetic procedures and patient care', 'preetidreams'); ?></p>
                        <div class="member-details">
                            <ul>
                                <li><?php esc_html_e('Expert in medical safety protocols', 'preetidreams'); ?></li>
                                <li><?php esc_html_e('Patient comfort optimization', 'preetidreams'); ?></li>
                                <li><?php esc_html_e('Post-treatment care specialists', 'preetidreams'); ?></li>
                            </ul>
                        </div>
                    </div>

                    <div class="team-member">
                        <div class="member-icon">💼</div>
                        <h3><?php esc_html_e('Practice Manager', 'preetidreams'); ?></h3>
                        <p><?php esc_html_e('Ensures seamless experience from consultation to follow-up', 'preetidreams'); ?></p>
                        <div class="member-details">
                            <ul>
                                <li><?php esc_html_e('10+ years practice management experience', 'preetidreams'); ?></li>
                                <li><?php esc_html_e('Streamlined operations expert', 'preetidreams'); ?></li>
                                <li><?php esc_html_e('Patient satisfaction specialist', 'preetidreams'); ?></li>
                            </ul>
                        </div>
                    </div>

                    <div class="team-member">
                        <div class="member-icon">📞</div>
                        <h3><?php esc_html_e('Patient Coordinator', 'preetidreams'); ?></h3>
                        <p><?php esc_html_e('Your personal guide through scheduling and treatment planning', 'preetidreams'); ?></p>
                        <div class="member-details">
                            <ul>
                                <li><?php esc_html_e('Bilingual support (English/Spanish)', 'preetidreams'); ?></li>
                                <li><?php esc_html_e('Scheduling optimization expert', 'preetidreams'); ?></li>
                                <li><?php esc_html_e('Ongoing support coordination', 'preetidreams'); ?></li>
                            </ul>
                        </div>
                    </div>

                    <div class="team-member">
                        <div class="member-icon">🧴</div>
                        <h3><?php esc_html_e('Aesthetician Specialist', 'preetidreams'); ?></h3>
                        <p><?php esc_html_e('Expert in advanced skincare and complementary treatments', 'preetidreams'); ?></p>
                        <div class="member-details">
                            <ul>
                                <li><?php esc_html_e('Licensed Master Aesthetician', 'preetidreams'); ?></li>
                                <li><?php esc_html_e('Advanced Chemical Peel Certification', 'preetidreams'); ?></li>
                                <li><?php esc_html_e('Skin Analysis Specialist', 'preetidreams'); ?></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="team-summary">
                    <p><?php esc_html_e('Together, we provide the personalized care that has earned the trust of 400+ clients.', 'preetidreams'); ?></p>
                </div>
            </div>
        </section>

        <!-- Arizona Locations Section -->
        <section class="arizona-locations" role="region" aria-label="<?php esc_attr_e('Arizona Locations and Contact', 'preetidreams'); ?>">
            <div class="container">
                <div class="section-header">
                    <h2><?php esc_html_e('Serving Arizona', 'preetidreams'); ?></h2>
                </div>

                <div class="locations-grid">
                    <div class="location-info">
                        <h3><?php esc_html_e('Primary Location', 'preetidreams'); ?> 🏢</h3>
                        <div class="location-details">
                            <p><strong><?php esc_html_e('PreetiDreams Medical Spa', 'preetidreams'); ?></strong></p>
                            <p>19420 N. 59th Ave<br>Glendale, AZ 85308</p>

                            <h4><?php esc_html_e('Hours:', 'preetidreams'); ?> ⏰</h4>
                            <ul>
                                <li><?php esc_html_e('Mon-Fri: 9AM-7PM', 'preetidreams'); ?></li>
                                <li><?php esc_html_e('Sat: 9AM-5PM', 'preetidreams'); ?></li>
                                <li><?php esc_html_e('Sun: By appointment', 'preetidreams'); ?></li>
                            </ul>

                            <div class="facility-features">
                                <span class="feature">🚗 <?php esc_html_e('Ample parking', 'preetidreams'); ?></span>
                                <span class="feature">🏥 <?php esc_html_e('Modern medical facility', 'preetidreams'); ?></span>
                                <span class="feature">♿ <?php esc_html_e('ADA accessible', 'preetidreams'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="contact-info">
                        <h3><?php esc_html_e('Contact Information', 'preetidreams'); ?> 📞</h3>
                        <div class="contact-details">
                            <div class="contact-item">
                                <strong><?php esc_html_e('Office:', 'preetidreams'); ?></strong>
                                <a href="tel:+14804694249">(480) 469-4249</a>
                            </div>
                            <div class="contact-item">
                                <strong><?php esc_html_e('Call/Text:', 'preetidreams'); ?></strong>
                                <a href="tel:+12485953987">(248) 595-3987</a>
                            </div>
                            <div class="contact-item">
                                <strong><?php esc_html_e('Email:', 'preetidreams'); ?></strong>
                                <a href="mailto:infusepreetidreams@gmail.com">infusepreetidreams@gmail.com</a>
                            </div>
                        </div>

                        <div class="social-media">
                            <h4><?php esc_html_e('Social Media:', 'preetidreams'); ?> 🌐</h4>
                            <p><?php esc_html_e('Follow us for tips, before/after results, and special offers', 'preetidreams'); ?></p>
                        </div>

                        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-primary">
                            <?php esc_html_e('Get Directions', 'preetidreams'); ?>
                        </a>
                    </div>

                    <div class="service-areas">
                        <h3><?php esc_html_e('Service Areas', 'preetidreams'); ?> 🌎</h3>
                        <div class="areas-list">
                            <div class="area-item">
                                <h4>📍 <?php esc_html_e('Glendale', 'preetidreams'); ?></h4>
                                <p><?php esc_html_e('Main location with full service menu', 'preetidreams'); ?></p>
                            </div>
                            <div class="area-item">
                                <h4>📍 <?php esc_html_e('Peoria', 'preetidreams'); ?></h4>
                                <p><?php esc_html_e('Convenient access for west valley residents', 'preetidreams'); ?></p>
                            </div>
                            <div class="area-item">
                                <h4>📍 <?php esc_html_e('Scottsdale', 'preetidreams'); ?></h4>
                                <p><?php esc_html_e('Premium services for north valley clientele', 'preetidreams'); ?></p>
                            </div>
                        </div>
                        <p class="service-note"><?php esc_html_e('All locations offer the same personalized, physician-led care that defines PreetiDreams.', 'preetidreams'); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Success Metrics Section -->
        <section class="success-metrics" role="region" aria-label="<?php esc_attr_e('Practice Success Metrics', 'preetidreams'); ?>">
            <div class="container">
                <div class="section-header">
                    <h2><?php esc_html_e('Our Track Record', 'preetidreams'); ?></h2>
                </div>

                <div class="metrics-grid">
                    <div class="metric-item">
                        <div class="metric-number">400+</div>
                        <h3><?php esc_html_e('Happy Clients', 'preetidreams'); ?></h3>
                        <p><?php esc_html_e('Successful aesthetic transformations achieved through personalized physician care', 'preetidreams'); ?></p>
                    </div>

                    <div class="metric-item">
                        <div class="metric-number">4</div>
                        <h3><?php esc_html_e('Expert Staff', 'preetidreams'); ?></h3>
                        <p><?php esc_html_e('Highly trained professionals supporting your personalized care journey', 'preetidreams'); ?></p>
                    </div>

                    <div class="metric-item">
                        <div class="metric-number">15+</div>
                        <h3><?php esc_html_e('Years of Excellence', 'preetidreams'); ?></h3>
                        <p><?php esc_html_e('In aesthetic medicine and patient care excellence', 'preetidreams'); ?></p>
                    </div>

                    <div class="metric-item">
                        <div class="metric-number">3</div>
                        <h3><?php esc_html_e('Arizona Locations', 'preetidreams'); ?></h3>
                        <p><?php esc_html_e('Glendale, Peoria & Scottsdale service areas', 'preetidreams'); ?></p>
                    </div>
                </div>

                <div class="testimonial-quote">
                    <blockquote>
                        <p><?php esc_html_e('"Your satisfaction is my specialty"', 'preetidreams'); ?></p>
                        <cite><?php esc_html_e('- Dr. Preeti Sharma', 'preetidreams'); ?></cite>
                    </blockquote>
                    <p class="testimonial-note"><?php esc_html_e('Every one of our 400+ clients receives the same personalized attention that has made PreetiDreams Arizona\'s trusted name in physician-led aesthetic care.', 'preetidreams'); ?></p>
                </div>

                <div class="success-cta">
                    <a href="<?php echo esc_url(home_url('/testimonials')); ?>" class="btn btn-outline">
                        <?php esc_html_e('Read Client Success Stories', 'preetidreams'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/before-after')); ?>" class="btn btn-outline">
                        <?php esc_html_e('View Before & After Gallery', 'preetidreams'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-primary">
                        <?php esc_html_e('Schedule Your Personal Consultation', 'preetidreams'); ?>
                    </a>
                    <a href="tel:+12485953987" class="btn btn-secondary">
                        <?php esc_html_e('Contact Dr. Preeti', 'preetidreams'); ?>
                    </a>
                </div>
            </div>
        </section>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
