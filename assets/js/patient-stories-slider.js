/**
 * Patient Stories Slider Component
 * Handles horizontal slider with auto-timer functionality
 * Follows semantic design principles and accessibility standards
 */

class PatientStoriesSlider {
    constructor(element) {
        // Prevent duplicate initialization
        if (element.hasAttribute('data-slider-initialized')) {
            console.log('Slider already initialized, skipping...');
            return;
        }

        this.slider = element;
        this.slidesContainer = this.slider.querySelector('.patient-stories-slides');
        this.slides = this.slider.querySelectorAll('.patient-story-slide');
        this.indicators = this.slider.querySelectorAll('.patient-stories-indicator');
        this.prevBtn = this.slider.querySelector('.patient-stories-prev');
        this.nextBtn = this.slider.querySelector('.patient-stories-next');

        this.currentSlide = 0;
        this.totalSlides = this.slides.length;
        this.autoTimer = null;
        this.autoTimerDelay = parseInt(this.slider.dataset.autoTimer) || 5000;
        this.isPlaying = true;
        this.isPaused = false;

        // Mark as initialized
        this.slider.setAttribute('data-slider-initialized', 'true');

        // Debug logging
        console.log('PatientStoriesSlider initialized:', {
            slider: this.slider,
            slidesContainer: this.slidesContainer,
            slides: this.slides.length,
            indicators: this.indicators.length,
            prevBtn: this.prevBtn,
            nextBtn: this.nextBtn
        });

        this.init();
    }

    init() {
        if (!this.slidesContainer || this.slides.length === 0) {
            console.error('Patient Stories Slider: Required elements not found');
            return;
        }

        this.setupEventListeners();
        this.setupTouchEvents();
        this.updateSlider();
        this.startAutoTimer();

        // Add hover pause/resume
        this.slider.addEventListener('mouseenter', () => this.pauseAutoTimer());
        this.slider.addEventListener('mouseleave', () => this.resumeAutoTimer());

        // Add focus pause/resume for accessibility
        this.slider.addEventListener('focusin', () => this.pauseAutoTimer());
        this.slider.addEventListener('focusout', () => this.resumeAutoTimer());

        // Add keyboard navigation
        this.slider.addEventListener('keydown', (e) => this.handleKeydown(e));

        console.log('Patient Stories Slider: Initialization complete');
    }

    setupEventListeners() {
        // Previous button
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => {
                console.log('Previous button clicked');
                this.prevSlide();
            });
        }

        // Next button
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => {
                console.log('Next button clicked');
                this.nextSlide();
            });
        }

        // Indicator buttons
        this.indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                console.log('Indicator clicked:', index);
                this.goToSlide(index);
            });
        });
    }

    setupTouchEvents() {
        let startX = 0;
        let startY = 0;
        let endX = 0;
        let endY = 0;

        this.slidesContainer.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
            this.pauseAutoTimer();
        });

        this.slidesContainer.addEventListener('touchend', (e) => {
            endX = e.changedTouches[0].clientX;
            endY = e.changedTouches[0].clientY;

            const deltaX = startX - endX;
            const deltaY = Math.abs(startY - endY);

            // Only trigger swipe if horizontal movement is greater than vertical
            if (Math.abs(deltaX) > 50 && Math.abs(deltaX) > deltaY) {
                if (deltaX > 0) {
                    this.nextSlide();
                } else {
                    this.prevSlide();
                }
            }

            this.resumeAutoTimer();
        });
    }

    handleKeydown(e) {
        switch (e.key) {
            case 'ArrowLeft':
                e.preventDefault();
                this.prevSlide();
                break;
            case 'ArrowRight':
                e.preventDefault();
                this.nextSlide();
                break;
            case ' ':
                e.preventDefault();
                this.toggleAutoTimer();
                break;
            case 'Home':
                e.preventDefault();
                this.goToSlide(0);
                break;
            case 'End':
                e.preventDefault();
                this.goToSlide(this.totalSlides - 1);
                break;
        }
    }

    nextSlide() {
        this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
        console.log('Next slide:', this.currentSlide);
        this.updateSlider();
        this.restartAutoTimer();
    }

    prevSlide() {
        this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
        console.log('Previous slide:', this.currentSlide);
        this.updateSlider();
        this.restartAutoTimer();
    }

    goToSlide(index) {
        if (index >= 0 && index < this.totalSlides) {
            this.currentSlide = index;
            console.log('Go to slide:', this.currentSlide);
            this.updateSlider();
            this.restartAutoTimer();
        }
    }

    updateSlider() {
        // Update slides with transform positioning for horizontal sliding
        const translateX = -this.currentSlide * 33.333; // Move by 1/3 for each slide
        this.slidesContainer.style.transform = `translateX(${translateX}%)`;

        console.log('Slider updated:', {
            currentSlide: this.currentSlide,
            translateX: translateX,
            transform: this.slidesContainer.style.transform
        });

        // Update indicators
        this.indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === this.currentSlide);
            indicator.setAttribute('aria-pressed', index === this.currentSlide);
        });

        // Update slides for accessibility
        this.slides.forEach((slide, index) => {
            slide.setAttribute('aria-hidden', index !== this.currentSlide);
        });

        // Update navigation buttons
        if (this.prevBtn) {
            this.prevBtn.disabled = false; // Always enabled for infinite loop
        }

        if (this.nextBtn) {
            this.nextBtn.disabled = false; // Always enabled for infinite loop
        }

        // Update ARIA live region for screen readers
        this.announceSlideChange();
    }

    announceSlideChange() {
        const currentSlideElement = this.slides[this.currentSlide];
        const quote = currentSlideElement.querySelector('.patient-story-quote');
        const author = currentSlideElement.querySelector('.author-name');

        if (quote && author) {
            const announcement = `Patient story ${this.currentSlide + 1} of ${this.totalSlides}: ${quote.textContent} - ${author.textContent}`;
            this.announceToScreenReader(announcement);
        }
    }

    announceToScreenReader(message) {
        const announcement = document.createElement('div');
        announcement.setAttribute('aria-live', 'polite');
        announcement.setAttribute('aria-atomic', 'true');
        announcement.className = 'sr-only';
        announcement.textContent = message;

        document.body.appendChild(announcement);

        setTimeout(() => {
            document.body.removeChild(announcement);
        }, 1000);
    }

    startAutoTimer() {
        if (!this.isPlaying) return;

        console.log('Starting auto timer with delay:', this.autoTimerDelay);

        this.autoTimer = setInterval(() => {
            if (!this.isPaused) {
                console.log('Auto advancing slide');
                this.nextSlide();
            }
        }, this.autoTimerDelay);

        this.slider.classList.remove('paused');
    }

    stopAutoTimer() {
        if (this.autoTimer) {
            console.log('Stopping auto timer');
            clearInterval(this.autoTimer);
            this.autoTimer = null;
        }
        this.slider.classList.add('paused');
    }

    restartAutoTimer() {
        this.stopAutoTimer();
        this.startAutoTimer();
    }

    pauseAutoTimer() {
        this.isPaused = true;
        this.slider.classList.add('paused');
        console.log('Auto timer paused');
    }

    resumeAutoTimer() {
        this.isPaused = false;
        this.slider.classList.remove('paused');
        console.log('Auto timer resumed');
    }

    toggleAutoTimer() {
        if (this.isPlaying) {
            this.stopAutoTimer();
            this.isPlaying = false;
        } else {
            this.startAutoTimer();
            this.isPlaying = true;
        }
    }

    destroy() {
        this.stopAutoTimer();
        // Remove event listeners would go here if needed
    }
}

// Multiple initialization strategies to ensure it works in WordPress
function initializePatientStoriesSliders() {
    console.log('Initializing Patient Stories Sliders...');
    const sliders = document.querySelectorAll('.patient-stories-slider-wrapper');
    console.log('Found sliders:', sliders.length);

    sliders.forEach((slider, index) => {
        console.log('Initializing slider', index + 1);
        new PatientStoriesSlider(slider);
    });
}

// Strategy 1: DOMContentLoaded
document.addEventListener('DOMContentLoaded', initializePatientStoriesSliders);

// Strategy 2: Window load (fallback)
window.addEventListener('load', function() {
    // Only initialize if not already done
    const sliders = document.querySelectorAll('.patient-stories-slider-wrapper');
    let hasInitialized = false;

    sliders.forEach(slider => {
        if (slider.hasAttribute('data-slider-initialized')) {
            hasInitialized = true;
        }
    });

    if (!hasInitialized) {
        console.log('Fallback initialization on window load');
        initializePatientStoriesSliders();
    }
});

// Strategy 3: jQuery ready (if jQuery is available)
if (typeof jQuery !== 'undefined') {
    jQuery(document).ready(function($) {
        console.log('jQuery ready - checking for sliders');
        setTimeout(initializePatientStoriesSliders, 100);
    });
}

// Strategy 4: Immediate execution with timeout (last resort)
setTimeout(function() {
    const sliders = document.querySelectorAll('.patient-stories-slider-wrapper');
    let hasInitialized = false;

    sliders.forEach(slider => {
        if (slider.hasAttribute('data-slider-initialized')) {
            hasInitialized = true;
        }
    });

    if (!hasInitialized && sliders.length > 0) {
        console.log('Timeout fallback initialization');
        initializePatientStoriesSliders();
    }
}, 1000);

// Handle reduced motion preference
if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.addEventListener('DOMContentLoaded', function() {
        const sliders = document.querySelectorAll('.patient-stories-slider-wrapper');
        sliders.forEach(slider => {
            slider.classList.add('reduced-motion');
            // Disable auto-timer for users who prefer reduced motion
            const sliderInstance = slider.patientStoriesSlider;
            if (sliderInstance) {
                sliderInstance.stopAutoTimer();
                sliderInstance.isPlaying = false;
            }
        });
    });
}

// Export for potential external use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PatientStoriesSlider;
}

// Global access for debugging
window.PatientStoriesSlider = PatientStoriesSlider;
