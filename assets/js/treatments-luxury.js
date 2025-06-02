/**
 * Treatments Page - Luxury Medical Spa JavaScript
 * Following TREATMENTS_PAGE_DESIGN.md v4.0 specifications
 *
 * Features:
 * - Smooth parallax hero animations
 * - Sophisticated category interactions
 * - Consultation-focused journey enhancement
 * - WCAG AAA accessibility compliance
 * - Mobile-first responsive behavior
 * - Performance optimized animations
 */

(function() {
    'use strict';

    // ================================
    // LUXURY TREATMENTS PAGE CONTROLLER
    // ================================
    class LuxuryTreatmentsPage {
        constructor() {
            this.isReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            this.isTouch = window.matchMedia('(hover: none) and (pointer: coarse)').matches;
            this.scrollPosition = 0;
            this.isHeroVisible = true;

            this.init();
        }

        init() {
            this.setupAccessibility();
            this.setupHeroExperience();
            this.setupCategoryInteractions();
            this.setupConsultationTracking();
            this.setupResponsiveHandlers();
            this.setupPerformanceOptimization();
            this.initializeAnimations();
        }

        // ================================
        // ACCESSIBILITY ENHANCEMENTS
        // ================================
        setupAccessibility() {
            // Enhanced keyboard navigation
            this.setupKeyboardNavigation();

            // Screen reader optimizations
            this.setupScreenReaderSupport();

            // Focus management
            this.setupFocusManagement();

            // High contrast mode detection
            this.setupContrastModeDetection();
        }

        setupKeyboardNavigation() {
            // Smooth scrolling for discovery button
            const discoveryBtn = document.querySelector('.hero-discovery-btn');
            if (discoveryBtn) {
                discoveryBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const target = document.querySelector('#treatment-artistry');
                    if (target) {
                        this.smoothScrollTo(target, 80); // Offset for luxury spacing
                    }
                });
            }

            // Category exploration with Enter/Space
            const categoryBtns = document.querySelectorAll('.category-explore-btn');
            categoryBtns.forEach(btn => {
                btn.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        btn.click();
                    }
                });
            });

            // Enhanced tab navigation
            this.setupEnhancedTabNavigation();
        }

        setupEnhancedTabNavigation() {
            // Add luxury focus indicators
            const focusableElements = document.querySelectorAll(
                'a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );

            focusableElements.forEach(element => {
                element.addEventListener('focus', () => {
                    element.style.outline = '3px solid var(--premium-gold)';
                    element.style.outlineOffset = '2px';
                    element.style.borderRadius = '4px';
                });

                element.addEventListener('blur', () => {
                    element.style.outline = '';
                    element.style.outlineOffset = '';
                });
            });
        }

        setupScreenReaderSupport() {
            // Dynamic aria-live regions for category interactions
            const liveRegion = document.createElement('div');
            liveRegion.setAttribute('aria-live', 'polite');
            liveRegion.setAttribute('aria-atomic', 'true');
            liveRegion.className = 'sr-only';
            liveRegion.id = 'luxury-announcements';
            document.body.appendChild(liveRegion);

            // Announce category focus
            const categories = document.querySelectorAll('.artistry-category');
            categories.forEach((category, index) => {
                const title = category.querySelector('.category-title')?.textContent;
                const description = category.querySelector('.category-essence')?.textContent;

                category.addEventListener('mouseenter', () => {
                    if (!this.isTouch) {
                        this.announceToScreenReader(`Exploring ${title}: ${description}`);
                    }
                });
            });
        }

        setupFocusManagement() {
            // Consultation button focus enhancement
            const consultationBtn = document.querySelector('.consultation-schedule-btn');
            if (consultationBtn) {
                consultationBtn.addEventListener('focus', () => {
                    this.announceToScreenReader('Schedule your personalized consultation with our board-certified physician');
                });
            }

            // Category button focus enhancement
            const categoryBtns = document.querySelectorAll('.category-explore-btn');
            categoryBtns.forEach(btn => {
                btn.addEventListener('focus', () => {
                    const categoryName = btn.closest('.artistry-category')?.querySelector('.category-title')?.textContent;
                    this.announceToScreenReader(`Learn more about ${categoryName} consultation options`);
                });
            });
        }

        setupContrastModeDetection() {
            const highContrastQuery = window.matchMedia('(prefers-contrast: high)');

            const handleContrastChange = (e) => {
                if (e.matches) {
                    document.body.classList.add('high-contrast-mode');
                    this.enhanceContrastElements();
                } else {
                    document.body.classList.remove('high-contrast-mode');
                }
            };

            highContrastQuery.addEventListener('change', handleContrastChange);
            handleContrastChange(highContrastQuery);
        }

        enhanceContrastElements() {
            // Enhance border visibility in high contrast mode
            const categories = document.querySelectorAll('.artistry-category');
            categories.forEach(category => {
                category.style.border = '2px solid #000000';
            });

            const buttons = document.querySelectorAll('.category-explore-btn, .consultation-schedule-btn');
            buttons.forEach(btn => {
                btn.style.border = '2px solid currentColor';
            });
        }

        // ================================
        // HERO EXPERIENCE ENHANCEMENTS
        // ================================
        setupHeroExperience() {
            if (this.isReducedMotion) return;

            this.setupParallaxEffect();
            this.setupHeroVideoManagement();
            this.setupCredibilityAnimations();
        }

        setupParallaxEffect() {
            const hero = document.querySelector('.treatments-hero-luxury');
            const heroContent = document.querySelector('.hero-content-luxury');

            if (!hero || !heroContent) return;

            let ticking = false;

            const updateParallax = () => {
                const scrollTop = window.pageYOffset;
                const rate = scrollTop * -0.5;
                const opacity = Math.max(0, 1 - (scrollTop / window.innerHeight));

                if (heroContent) {
                    heroContent.style.transform = `translate3d(0, ${rate}px, 0)`;
                    heroContent.style.opacity = opacity;
                }

                ticking = false;
            };

            const requestParallaxUpdate = () => {
                if (!ticking) {
                    requestAnimationFrame(updateParallax);
                    ticking = true;
                }
            };

            // Throttled scroll listener
            window.addEventListener('scroll', requestParallaxUpdate, { passive: true });
        }

        setupHeroVideoManagement() {
            const video = document.querySelector('.hero-video');
            if (!video) return;

            // Intersection Observer for performance
            const videoObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        video.play();
                        this.isHeroVisible = true;
                    } else {
                        video.pause();
                        this.isHeroVisible = false;
                    }
                });
            }, { threshold: 0.1 });

            videoObserver.observe(video);

            // Handle video loading states
            video.addEventListener('loadstart', () => {
                video.style.opacity = '0';
            });

            video.addEventListener('canplay', () => {
                video.style.opacity = '0.3';
                video.style.transition = 'opacity 1s ease-in-out';
            });
        }

        setupCredibilityAnimations() {
            const credibilityItems = document.querySelectorAll('.credibility-item');

            const credibilityObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }, index * 200); // Staggered animation
                    }
                });
            }, { threshold: 0.5 });

            credibilityItems.forEach(item => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                item.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                credibilityObserver.observe(item);
            });
        }

        // ================================
        // CATEGORY INTERACTIONS
        // ================================
        setupCategoryInteractions() {
            this.setupCategoryHoverEffects();
            this.setupCategoryNavigation();
            this.setupCategoryAccessibility();
        }

        setupCategoryHoverEffects() {
            if (this.isTouch) return; // Skip hover effects on touch devices

            const categories = document.querySelectorAll('.artistry-category');

            categories.forEach(category => {
                const image = category.querySelector('.category-image');
                const overlay = category.querySelector('.category-visual-overlay');
                const icon = category.querySelector('.category-artistic-icon');

                category.addEventListener('mouseenter', () => {
                    if (image) {
                        image.style.transform = 'scale(1.05)';
                    }
                    if (overlay) {
                        overlay.style.opacity = '1';
                    }
                    if (icon) {
                        icon.style.transform = 'scale(1)';
                    }

                    // Add luxury elevation
                    category.style.transform = 'translateY(-4px)';
                    category.style.boxShadow = '0 12px 40px rgba(27, 54, 93, 0.15)';
                });

                category.addEventListener('mouseleave', () => {
                    if (image) {
                        image.style.transform = 'scale(1)';
                    }
                    if (overlay) {
                        overlay.style.opacity = '0';
                    }
                    if (icon) {
                        icon.style.transform = 'scale(0.8)';
                    }

                    // Reset elevation
                    category.style.transform = 'translateY(0)';
                    category.style.boxShadow = '0 4px 20px rgba(27, 54, 93, 0.08)';
                });
            });
        }

        setupCategoryNavigation() {
            const exploreButtons = document.querySelectorAll('.category-explore-btn');

            exploreButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();

                    // Get category information
                    const category = btn.closest('.artistry-category');
                    const categoryTitle = category?.querySelector('.category-title')?.textContent;

                    // Track consultation interest
                    this.trackConsultationInterest(categoryTitle);

                    // Smooth scroll to consultation section
                    const consultationSection = document.querySelector('#consultation-invitation');
                    if (consultationSection) {
                        this.smoothScrollTo(consultationSection, 120);

                        // Highlight consultation section
                        this.highlightConsultationSection(categoryTitle);
                    }
                });
            });
        }

        setupCategoryAccessibility() {
            const categories = document.querySelectorAll('.artistry-category');

            categories.forEach(category => {
                // Add tabindex for keyboard navigation
                category.setAttribute('tabindex', '0');

                // Add keyboard interaction
                category.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        const exploreBtn = category.querySelector('.category-explore-btn');
                        if (exploreBtn) {
                            exploreBtn.click();
                        }
                    }
                });

                // Add focus styling
                category.addEventListener('focus', () => {
                    category.style.outline = '3px solid var(--premium-gold)';
                    category.style.outlineOffset = '4px';
                });

                category.addEventListener('blur', () => {
                    category.style.outline = '';
                    category.style.outlineOffset = '';
                });
            });
        }

        // ================================
        // CONSULTATION TRACKING & ENHANCEMENT
        // ================================
        setupConsultationTracking() {
            this.setupConsultationButtons();
            this.setupContactMethodTracking();
            this.setupPhilosophyInteraction();
        }

        setupConsultationButtons() {
            const scheduleBtn = document.querySelector('.consultation-schedule-btn');
            if (scheduleBtn) {
                scheduleBtn.addEventListener('click', () => {
                    this.trackConsultationAction('primary_schedule_button');
                    this.enhanceConsultationExperience();
                });
            }

            // Track alternative contact methods
            const contactLinks = document.querySelectorAll('.contact-method-link');
            contactLinks.forEach(link => {
                link.addEventListener('click', () => {
                    const method = link.classList.contains('phone-contact') ? 'phone' : 'email';
                    this.trackConsultationAction(`contact_${method}`);
                });
            });
        }

        setupContactMethodTracking() {
            // Phone number click tracking
            const phoneLinks = document.querySelectorAll('a[href^="tel:"]');
            phoneLinks.forEach(link => {
                link.addEventListener('click', () => {
                    this.announceToScreenReader('Calling for consultation. Your call will be answered by our professional consultation coordinator.');
                });
            });

            // Email link tracking
            const emailLinks = document.querySelectorAll('a[href^="mailto:"]');
            emailLinks.forEach(link => {
                link.addEventListener('click', () => {
                    this.announceToScreenReader('Opening email for consultation inquiry. We respond within 24 hours with discretion and care.');
                });
            });
        }

        setupPhilosophyInteraction() {
            const philosophySection = document.querySelector('.medical-artistry-philosophy');
            const portraitContainer = document.querySelector('.provider-portrait-container');

            if (philosophySection && portraitContainer) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            // Animate portrait entrance
                            this.animatePhilosophySection();
                        }
                    });
                }, { threshold: 0.3 });

                observer.observe(philosophySection);
            }
        }

        animatePhilosophySection() {
            if (this.isReducedMotion) return;

            const portrait = document.querySelector('.provider-portrait-image');
            const quote = document.querySelector('.philosophy-quote');

            if (portrait) {
                portrait.style.opacity = '0';
                portrait.style.transform = 'scale(0.95)';

                setTimeout(() => {
                    portrait.style.transition = 'opacity 0.8s ease-out, transform 0.8s ease-out';
                    portrait.style.opacity = '1';
                    portrait.style.transform = 'scale(1)';
                }, 300);
            }

            if (quote) {
                quote.style.opacity = '0';
                quote.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    quote.style.transition = 'opacity 0.8s ease-out, transform 0.8s ease-out';
                    quote.style.opacity = '1';
                    quote.style.transform = 'translateY(0)';
                }, 600);
            }
        }

        // ================================
        // RESPONSIVE ENHANCEMENTS
        // ================================
        setupResponsiveHandlers() {
            this.setupMobileOptimizations();
            this.setupTabletOptimizations();
            this.setupDesktopEnhancements();
        }

        setupMobileOptimizations() {
            if (window.innerWidth <= 767) {
                // Optimize touch interactions
                this.optimizeTouchInteractions();

                // Adjust spacing for mobile
                this.adjustMobileSpacing();

                // Optimize mobile navigation
                this.optimizeMobileNavigation();
            }
        }

        optimizeTouchInteractions() {
            const touchTargets = document.querySelectorAll('.category-explore-btn, .consultation-schedule-btn, .contact-method-link');

            touchTargets.forEach(target => {
                // Ensure minimum 56px touch target (WCAG AAA)
                const rect = target.getBoundingClientRect();
                if (rect.height < 56) {
                    target.style.minHeight = '56px';
                }

                // Add touch feedback
                target.addEventListener('touchstart', () => {
                    target.style.transform = 'scale(0.98)';
                }, { passive: true });

                target.addEventListener('touchend', () => {
                    target.style.transform = '';
                }, { passive: true });
            });
        }

        adjustMobileSpacing() {
            const categories = document.querySelectorAll('.artistry-category');
            categories.forEach(category => {
                const content = category.querySelector('.category-content-luxury');
                if (content) {
                    content.style.padding = 'var(--space-lg)';
                }
            });
        }

        optimizeMobileNavigation() {
            // Optimize scroll behavior for mobile
            this.setupMobileScrollOptimization();
        }

        setupMobileScrollOptimization() {
            let isScrolling = false;

            window.addEventListener('scroll', () => {
                if (!isScrolling) {
                    window.requestAnimationFrame(() => {
                        this.updateScrollProgress();
                        isScrolling = false;
                    });
                    isScrolling = true;
                }
            }, { passive: true });
        }

        setupTabletOptimizations() {
            if (window.innerWidth >= 768 && window.innerWidth < 1024) {
                // Tablet-specific optimizations
                this.optimizeTabletLayout();
            }
        }

        optimizeTabletLayout() {
            const categoriesGrid = document.querySelector('.artistry-categories-grid');
            if (categoriesGrid && window.innerWidth >= 768) {
                categoriesGrid.style.gridTemplateColumns = 'repeat(2, 1fr)';
            }
        }

        setupDesktopEnhancements() {
            if (window.innerWidth >= 1024) {
                this.setupDesktopInteractions();
                this.setupDesktopParallax();
            }
        }

        setupDesktopInteractions() {
            // Enhanced desktop hover effects
            const categories = document.querySelectorAll('.artistry-category');

            categories.forEach(category => {
                category.addEventListener('mouseenter', () => {
                    // Add subtle glow effect
                    category.style.boxShadow = '0 12px 40px rgba(27, 54, 93, 0.15), 0 0 0 1px rgba(135, 169, 107, 0.2)';
                });

                category.addEventListener('mouseleave', () => {
                    category.style.boxShadow = '0 4px 20px rgba(27, 54, 93, 0.08)';
                });
            });
        }

        setupDesktopParallax() {
            // Enhanced parallax for desktop
            if (this.isReducedMotion) return;

            // Add subtle parallax to category images
            const categoryImages = document.querySelectorAll('.category-image');

            window.addEventListener('scroll', () => {
                const scrollTop = window.pageYOffset;

                categoryImages.forEach((image, index) => {
                    const rect = image.getBoundingClientRect();
                    const isVisible = rect.top < window.innerHeight && rect.bottom > 0;

                    if (isVisible) {
                        const parallaxSpeed = 0.1;
                        const yPos = -(scrollTop * parallaxSpeed);
                        image.style.transform = `translateY(${yPos}px)`;
                    }
                });
            }, { passive: true });
        }

        // ================================
        // PERFORMANCE OPTIMIZATION
        // ================================
        setupPerformanceOptimization() {
            this.setupLazyLoading();
            this.setupImageOptimization();
            this.setupAnimationOptimization();
        }

        setupLazyLoading() {
            // Lazy load category images
            const categoryImages = document.querySelectorAll('.category-image');

            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const image = entry.target;

                        // Load high-quality image
                        this.loadHighQualityImage(image);

                        imageObserver.unobserve(image);
                    }
                });
            }, { threshold: 0.1 });

            categoryImages.forEach(image => {
                imageObserver.observe(image);
            });
        }

        loadHighQualityImage(element) {
            // Progressive image enhancement
            const backgroundImage = window.getComputedStyle(element).backgroundImage;
            if (backgroundImage && backgroundImage !== 'none') {
                element.style.transition = 'opacity 0.3s ease-in-out';
                element.style.opacity = '1';
            }
        }

        setupImageOptimization() {
            // Preload critical images
            const criticalImages = [
                '/assets/images/dr-preeti-portrait.jpg'
            ];

            criticalImages.forEach(src => {
                const link = document.createElement('link');
                link.rel = 'preload';
                link.as = 'image';
                link.href = src;
                document.head.appendChild(link);
            });
        }

        setupAnimationOptimization() {
            // Use GPU acceleration for animations
            const animatedElements = document.querySelectorAll(
                '.artistry-category, .category-image, .hero-discovery-btn, .category-explore-btn, .consultation-schedule-btn'
            );

            animatedElements.forEach(element => {
                element.style.willChange = 'transform';
                element.style.transform = 'translateZ(0)';
            });
        }

        // ================================
        // ANIMATION INITIALIZATION
        // ================================
        initializeAnimations() {
            if (this.isReducedMotion) {
                this.disableAnimations();
                return;
            }

            this.setupScrollAnimations();
            this.setupEntryAnimations();
        }

        setupScrollAnimations() {
            const animatedElements = document.querySelectorAll('.artistry-category, .consultation-card');

            const scrollObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }, index * 150); // Staggered entrance
                    }
                });
            }, { threshold: 0.2 });

            animatedElements.forEach(element => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(30px)';
                element.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                scrollObserver.observe(element);
            });
        }

        setupEntryAnimations() {
            // Hero content entrance animation
            const heroContent = document.querySelector('.hero-content-luxury');
            if (heroContent) {
                setTimeout(() => {
                    heroContent.style.opacity = '1';
                    heroContent.style.transform = 'translateY(0)';
                }, 500);
            }
        }

        disableAnimations() {
            // Remove all transitions and animations for reduced motion
            const style = document.createElement('style');
            style.textContent = `
                *, *::before, *::after {
                    animation-duration: 0.01ms !important;
                    animation-iteration-count: 1 !important;
                    transition-duration: 0.01ms !important;
                    scroll-behavior: auto !important;
                }
            `;
            document.head.appendChild(style);
        }

        // ================================
        // UTILITY METHODS
        // ================================
        smoothScrollTo(target, offset = 0) {
            const targetPosition = target.offsetTop - offset;

            if (this.isReducedMotion) {
                window.scrollTo(0, targetPosition);
                return;
            }

            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
        }

        announceToScreenReader(message) {
            const liveRegion = document.getElementById('luxury-announcements');
            if (liveRegion) {
                liveRegion.textContent = message;

                // Clear after announcement
                setTimeout(() => {
                    liveRegion.textContent = '';
                }, 1000);
            }
        }

        trackConsultationInterest(categoryTitle) {
            // Analytics tracking for consultation interest
            if (typeof gtag !== 'undefined') {
                gtag('event', 'consultation_interest', {
                    'category': categoryTitle,
                    'source': 'treatments_page'
                });
            }

            console.log(`Consultation interest tracked: ${categoryTitle}`);
        }

        trackConsultationAction(action) {
            // Analytics tracking for consultation actions
            if (typeof gtag !== 'undefined') {
                gtag('event', 'consultation_action', {
                    'action': action,
                    'source': 'treatments_page'
                });
            }

            console.log(`Consultation action tracked: ${action}`);
        }

        highlightConsultationSection(categoryTitle) {
            const consultationCard = document.querySelector('.consultation-card');
            if (consultationCard) {
                // Add subtle highlighting
                consultationCard.style.borderLeft = '4px solid var(--premium-gold)';
                consultationCard.style.boxShadow = '0 8px 30px rgba(212, 175, 55, 0.15)';

                // Update consultation card title to reflect interest
                const cardTitle = consultationCard.querySelector('.consultation-card-title');
                if (cardTitle) {
                    const originalTitle = cardTitle.textContent;
                    cardTitle.textContent = `Your ${categoryTitle} Consultation Includes`;

                    // Reset after 5 seconds
                    setTimeout(() => {
                        cardTitle.textContent = originalTitle;
                        consultationCard.style.borderLeft = '';
                        consultationCard.style.boxShadow = '0 4px 20px rgba(27, 54, 93, 0.08)';
                    }, 5000);
                }

                this.announceToScreenReader(`${categoryTitle} consultation information highlighted. Schedule your personalized consultation today.`);
            }
        }

        updateScrollProgress() {
            // Update scroll-based animations and effects
            this.scrollPosition = window.pageYOffset;

            // Update hero video playback based on scroll
            const video = document.querySelector('.hero-video');
            if (video && this.isHeroVisible) {
                const heroHeight = window.innerHeight;
                const scrollProgress = Math.min(this.scrollPosition / heroHeight, 1);
                video.style.opacity = 0.3 * (1 - scrollProgress);
            }
        }
    }

    // ================================
    // INITIALIZATION
    // ================================

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            new LuxuryTreatmentsPage();
        });
    } else {
        new LuxuryTreatmentsPage();
    }

    // Handle window resize
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            // Reinitialize responsive features
            if (window.luxuryTreatmentsPage) {
                window.luxuryTreatmentsPage.setupResponsiveHandlers();
            }
        }, 250);
    });

})();
