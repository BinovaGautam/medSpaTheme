/**
 * Medical Spa Treatments Page JavaScript
 * Following UI/UX Design Agent specifications for luxury medical spa
 *
 * Features:
 * - Category-based treatment organization
 * - Simple search functionality
 * - Professional medical spa presentation
 * - Accessibility compliance (WCAG AAA)
 * - Performance optimized
 */

class MedicalSpaTreatments {
    constructor() {
        this.treatments = [];
        this.searchQuery = '';
        this.searchDebounceTimer = null;

        // Treatment categories based on medical spa design
        this.categories = {
            'injectable': {
                name: 'Injectable Treatments',
                icon: '💉',
                description: 'Botox, fillers, and other injectable solutions',
                treatments: []
            },
            'facial': {
                name: 'Facial Treatments',
                icon: '✨',
                description: 'Advanced skincare and facial rejuvenation treatments',
                treatments: []
            },
            'laser': {
                name: 'Laser Treatments',
                icon: '⚡',
                description: 'Advanced laser technology for hair removal and skin rejuvenation',
                treatments: []
            },
            'body': {
                name: 'Body Treatments',
                icon: '🌿',
                description: 'Body contouring and skin enhancement treatments',
                treatments: []
            },
            'wellness': {
                name: 'Wellness & Spa',
                icon: '🧘',
                description: 'Relaxation and wellness treatments for complete rejuvenation',
                treatments: []
            }
        };

        this.init();
    }

    /**
     * Initialize the medical spa treatments functionality
     */
    init() {
        this.bindEvents();
        this.loadTreatments();
        this.setupAccessibility();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Search functionality
        const searchInput = document.getElementById('treatments-search');
        if (searchInput) {
            searchInput.addEventListener('input', this.handleSearch.bind(this));
            searchInput.addEventListener('keydown', this.handleSearchKeydown.bind(this));
        }

        // Search clear button
        const searchClear = document.getElementById('search-clear');
        if (searchClear) {
            searchClear.addEventListener('click', this.clearSearch.bind(this));
        }

        // Clear search button in no results
        const clearSearchBtn = document.getElementById('clear-search-btn');
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', this.clearSearch.bind(this));
        }

        // Global keyboard shortcuts
        document.addEventListener('keydown', this.handleGlobalKeydown.bind(this));

        // Window events
        window.addEventListener('resize', this.debounce(this.handleResize.bind(this), 250));
    }

    /**
     * Load treatments from WordPress or sample data
     */
    async loadTreatments() {
        this.showLoading(true);

        try {
            // Try to load from WordPress API first
            if (typeof treatmentsSpaData !== 'undefined' && treatmentsSpaData.restUrl) {
                await this.loadTreatmentsFromAPI();
            } else {
                // Load sample treatments for demonstration
                this.loadSampleTreatments();
            }

            this.organizeTreatmentsByCategory();
            this.renderAllCategories();
            this.updateTotalCount();

        } catch (error) {
            console.error('Error loading treatments:', error);
            this.loadSampleTreatments();
            this.organizeTreatmentsByCategory();
            this.renderAllCategories();
        } finally {
            this.showLoading(false);
        }
    }

    /**
     * Load treatments from WordPress API
     */
    async loadTreatmentsFromAPI() {
        const response = await fetch(`${treatmentsSpaData.restUrl}treatment?per_page=100&_embed`);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const treatmentsData = await response.json();
        this.treatments = this.processTreatmentsData(treatmentsData);
    }

    /**
     * Process raw treatments data from API
     */
    processTreatmentsData(rawData) {
        return rawData.map(treatment => ({
            id: treatment.id,
            title: treatment.title.rendered,
            description: treatment.excerpt.rendered.replace(/<[^>]*>/g, '').trim(),
            category: this.extractMeta(treatment, 'category', 'facial'),
            duration: this.extractMeta(treatment, 'duration', '30-45 minutes'),
            price: this.extractMeta(treatment, 'price_range', 'Starting at $200'),
            image: treatment._embedded && treatment._embedded['wp:featuredmedia']
                ? treatment._embedded['wp:featuredmedia'][0].source_url
                : null,
            featured: this.extractMeta(treatment, 'featured', false),
            slug: treatment.slug
        }));
    }

    /**
     * Extract meta field with fallback
     */
    extractMeta(treatment, field, defaultValue) {
        return treatment.meta && treatment.meta[`treatment_${field}`]
            ? treatment.meta[`treatment_${field}`]
            : defaultValue;
    }

    /**
     * Load sample treatments for demonstration
     */
    loadSampleTreatments() {
        this.treatments = [
            // Injectable Treatments
            {
                id: 1,
                title: 'Botox® Cosmetic',
                description: 'Smooth fine lines and wrinkles with precision injections by our board-certified team',
                category: 'injectable',
                duration: '15-30 minutes',
                price: 'Starting at $12/unit',
                subtitle: 'FDA-Approved Treatment',
                featured: true
            },
            {
                id: 2,
                title: 'Dermal Fillers',
                description: 'Restore volume and enhance facial contours with FDA-approved hyaluronic acid fillers',
                category: 'injectable',
                duration: '30-45 minutes',
                price: 'Starting at $650/syringe',
                subtitle: 'Juvederm®, Restylane®'
            },
            {
                id: 3,
                title: 'Sculptra®',
                description: 'Gradual volume restoration with natural collagen stimulation for lasting results',
                category: 'injectable',
                duration: '30-45 minutes',
                price: 'Starting at $800/vial',
                subtitle: 'Collagen Stimulator'
            },

            // Facial Treatments
            {
                id: 4,
                title: 'HydraFacial MD®',
                description: 'Deep cleanse, extract, and hydrate your skin with patented Vortex technology',
                category: 'facial',
                duration: '30-45 minutes',
                price: '$175 per treatment',
                subtitle: 'Medical-Grade Treatment'
            },
            {
                id: 5,
                title: 'Chemical Peels',
                description: 'Improve skin texture, reduce pigmentation and reveal smoother, brighter skin',
                category: 'facial',
                duration: '30-60 minutes',
                price: 'Starting at $150',
                subtitle: 'Glycolic, TCA Peels'
            },
            {
                id: 6,
                title: 'Microneedling RF',
                description: 'Fractional radiofrequency for skin tightening and texture improvement',
                category: 'facial',
                duration: '45-60 minutes',
                price: 'Starting at $800',
                subtitle: 'Morpheus8®'
            },

            // Laser Treatments
            {
                id: 7,
                title: 'Laser Hair Removal',
                description: 'Permanent hair reduction using advanced laser technology for all skin types',
                category: 'laser',
                duration: '15-60 minutes',
                price: 'Starting at $199',
                subtitle: 'FDA-Approved Technology'
            },
            {
                id: 8,
                title: 'IPL Photofacial',
                description: 'Improve skin tone, reduce sun damage and minimize pigmentation',
                category: 'laser',
                duration: '30-45 minutes',
                price: 'Starting at $350',
                subtitle: 'Intense Pulsed Light'
            },

            // Body Treatments
            {
                id: 9,
                title: 'CoolSculpting®',
                description: 'Non-invasive fat reduction through controlled cooling technology',
                category: 'body',
                duration: '35-60 minutes',
                price: 'Starting at $750',
                subtitle: 'FDA-Cleared Fat Reduction'
            },
            {
                id: 10,
                title: 'Body Contouring RF',
                description: 'Skin tightening and cellulite reduction for improved body contours',
                category: 'body',
                duration: '45-90 minutes',
                price: 'Starting at $500',
                subtitle: 'Radiofrequency Technology'
            },

            // Wellness & Spa
            {
                id: 11,
                title: 'Therapeutic Massage',
                description: 'Relaxation and wellness treatments for complete rejuvenation and stress relief',
                category: 'wellness',
                duration: '60-90 minutes',
                price: 'Starting at $120',
                subtitle: 'Licensed Massage Therapists'
            },
            {
                id: 12,
                title: 'IV Vitamin Therapy',
                description: 'Customized vitamin infusions for energy, immunity, and wellness support',
                category: 'wellness',
                duration: '30-60 minutes',
                price: 'Starting at $175',
                subtitle: 'Medical Supervision'
            }
        ];
    }

    /**
     * Organize treatments by category
     */
    organizeTreatmentsByCategory() {
        // Reset category treatments
        Object.keys(this.categories).forEach(categoryKey => {
            this.categories[categoryKey].treatments = [];
        });

        // Organize treatments into categories
        this.treatments.forEach(treatment => {
            const category = treatment.category || 'wellness';
            if (this.categories[category]) {
                this.categories[category].treatments.push(treatment);
            }
        });
    }

    /**
     * Handle search input with debouncing
     */
    handleSearch(event) {
        const query = event.target.value.trim();

        // Clear previous timer
        if (this.searchDebounceTimer) {
            clearTimeout(this.searchDebounceTimer);
        }

        // Show/hide clear button
        const clearBtn = document.getElementById('search-clear');
        if (clearBtn) {
            clearBtn.style.display = query ? 'block' : 'none';
        }

        // Debounce search
        this.searchDebounceTimer = setTimeout(() => {
            this.searchQuery = query;
            this.performSearch();
        }, 300);
    }

    /**
     * Handle search keydown events
     */
    handleSearchKeydown(event) {
        if (event.key === 'Escape') {
            this.clearSearch();
        }
    }

    /**
     * Perform search and filter treatments
     */
    performSearch() {
        if (!this.searchQuery) {
            this.renderAllCategories();
            this.updateSearchResults('Showing all treatments');
            this.showNoResults(false);
            return;
        }

        const query = this.searchQuery.toLowerCase();
        let totalResults = 0;
        let hasResults = false;

        // Filter treatments in each category
        Object.keys(this.categories).forEach(categoryKey => {
            const category = this.categories[categoryKey];
            const filteredTreatments = category.treatments.filter(treatment =>
                treatment.title.toLowerCase().includes(query) ||
                treatment.description.toLowerCase().includes(query) ||
                (treatment.subtitle && treatment.subtitle.toLowerCase().includes(query))
            );

            totalResults += filteredTreatments.length;

            if (filteredTreatments.length > 0) {
                hasResults = true;
                this.renderCategoryTreatments(categoryKey, filteredTreatments);
                this.showCategory(categoryKey);
            } else {
                this.hideCategory(categoryKey);
            }
        });

        // Update search results message
        if (hasResults) {
            this.updateSearchResults(`Found ${totalResults} treatment${totalResults !== 1 ? 's' : ''} matching "${this.searchQuery}"`);
            this.showNoResults(false);
        } else {
            this.hideAllCategories();
            this.updateSearchResults(`No treatments found for "${this.searchQuery}"`);
            this.showNoResults(true);
        }
    }

    /**
     * Clear search
     */
    clearSearch() {
        const searchInput = document.getElementById('treatments-search');
        if (searchInput) {
            searchInput.value = '';
        }

        const clearBtn = document.getElementById('search-clear');
        if (clearBtn) {
            clearBtn.style.display = 'none';
        }

        this.searchQuery = '';
        this.renderAllCategories();
        this.updateSearchResults('Showing all treatments');
        this.showNoResults(false);

        // Focus back to search input
        if (searchInput) {
            searchInput.focus();
        }
    }

    /**
     * Render all treatment categories
     */
    renderAllCategories() {
        Object.keys(this.categories).forEach(categoryKey => {
            const category = this.categories[categoryKey];
            if (category.treatments.length > 0) {
                this.renderCategoryTreatments(categoryKey, category.treatments);
                this.showCategory(categoryKey);
            } else {
                this.hideCategory(categoryKey);
            }
        });
    }

    /**
     * Render treatments for a specific category
     */
    renderCategoryTreatments(categoryKey, treatments) {
        const container = document.getElementById(`${categoryKey}-treatments`);
        if (!container) return;

        container.innerHTML = treatments.map(treatment =>
            this.renderTreatmentCard(treatment)
        ).join('');
    }

    /**
     * Render individual treatment card
     */
    renderTreatmentCard(treatment) {
        return `
            <div class="treatment-card" data-treatment-id="${treatment.id}">
                ${treatment.featured ? '<div class="treatment-badge">Featured</div>' : ''}

                <div class="treatment-image">
                    ${treatment.image
                        ? `<img src="${treatment.image}" alt="${treatment.title}" loading="lazy">`
                        : ''
                    }
                </div>

                <div class="treatment-content">
                    <h3 class="treatment-title">${treatment.title}</h3>
                    ${treatment.subtitle ? `<div class="treatment-subtitle">${treatment.subtitle}</div>` : ''}

                    <p class="treatment-description">${treatment.description}</p>

                    <div class="treatment-meta">
                        <div class="treatment-meta-item">
                            <span class="treatment-meta-icon">⏱️</span>
                            <span>${treatment.duration}</span>
                        </div>
                        <div class="treatment-meta-item">
                            <span class="treatment-meta-icon">📅</span>
                            <span>Results vary</span>
                        </div>
                    </div>

                    <div class="treatment-price">${treatment.price}</div>

                    <div class="treatment-actions">
                        <button type="button" class="btn btn-primary"
                                onclick="medicalSpaTreatments.bookTreatment('${treatment.id}')">
                            Book Now
                        </button>
                        <button type="button" class="btn btn-secondary"
                                onclick="medicalSpaTreatments.learnMore('${treatment.id}')">
                            Learn More
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Show category section
     */
    showCategory(categoryKey) {
        const categoryElement = document.getElementById(`category-${categoryKey}`);
        if (categoryElement) {
            categoryElement.style.display = 'block';
        }
    }

    /**
     * Hide category section
     */
    hideCategory(categoryKey) {
        const categoryElement = document.getElementById(`category-${categoryKey}`);
        if (categoryElement) {
            categoryElement.style.display = 'none';
        }
    }

    /**
     * Hide all category sections
     */
    hideAllCategories() {
        Object.keys(this.categories).forEach(categoryKey => {
            this.hideCategory(categoryKey);
        });
    }

    /**
     * Update search results message
     */
    updateSearchResults(message) {
        const searchResults = document.getElementById('search-results');
        if (searchResults) {
            searchResults.textContent = message;
        }
    }

    /**
     * Update total treatments count
     */
    updateTotalCount() {
        const totalDisplay = document.getElementById('total-treatments-display');
        if (totalDisplay) {
            totalDisplay.textContent = `${this.treatments.length}+`;
        }
    }

    /**
     * Show/hide loading state
     */
    showLoading(show) {
        const loadingEl = document.getElementById('treatments-loading');
        const categoriesEl = document.querySelector('.treatments-categories-section');

        if (loadingEl) {
            loadingEl.style.display = show ? 'block' : 'none';
        }

        if (categoriesEl) {
            categoriesEl.style.display = show ? 'none' : 'block';
        }
    }

    /**
     * Show/hide no results state
     */
    showNoResults(show) {
        const noResultsEl = document.getElementById('no-results');
        const categoriesEl = document.querySelector('.treatments-categories-section');

        if (noResultsEl) {
            noResultsEl.style.display = show ? 'block' : 'none';
        }

        if (categoriesEl) {
            categoriesEl.style.display = show ? 'none' : 'block';
        }
    }

    /**
     * Book treatment action
     */
    bookTreatment(treatmentId) {
        // In real implementation, redirect to booking system
        console.log('Booking treatment:', treatmentId);

        // Show confirmation and redirect
        this.showToast('Redirecting to booking system...', 'info');

        setTimeout(() => {
            // Try to redirect to booking page if available
            if (typeof treatmentsSpaData !== 'undefined' && treatmentsSpaData.bookingUrl) {
                window.location.href = `${treatmentsSpaData.bookingUrl}?treatment=${treatmentId}`;
            } else {
                window.location.href = `/book-consultation?treatment=${treatmentId}`;
            }
        }, 1000);
    }

    /**
     * Learn more about treatment
     */
    learnMore(treatmentId) {
        const treatment = this.treatments.find(t => t.id == treatmentId);
        if (treatment && treatment.slug) {
            window.location.href = `/treatment/${treatment.slug}`;
        } else {
            // Fallback to general treatments page
            window.location.href = `/treatments/${treatmentId}`;
        }
    }

    /**
     * Show toast notification
     */
    showToast(message, type = 'success') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#10B981' : type === 'info' ? '#3B82F6' : '#EF4444'};
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            z-index: 1000;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            max-width: 300px;
        `;
        toast.textContent = message;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'polite');

        // Add to page
        document.body.appendChild(toast);

        // Animate in
        requestAnimationFrame(() => {
            toast.style.transform = 'translateX(0)';
        });

        // Remove after delay
        setTimeout(() => {
            toast.style.transform = 'translateX(400px)';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }

    /**
     * Debounce function for performance
     */
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    /**
     * Handle global keyboard shortcuts
     */
    handleGlobalKeydown(event) {
        // Escape key clears search
        if (event.key === 'Escape') {
            this.clearSearch();
        }

        // Ctrl/Cmd + K opens search
        if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
            event.preventDefault();
            const searchInput = document.getElementById('treatments-search');
            if (searchInput) {
                searchInput.focus();
            }
        }
    }

    /**
     * Handle window resize
     */
    handleResize() {
        // Could add responsive adjustments here if needed
    }

    /**
     * Setup accessibility features
     */
    setupAccessibility() {
        // ARIA live regions for search results
        const searchResults = document.getElementById('search-results');
        if (searchResults) {
            searchResults.setAttribute('aria-live', 'polite');
        }

        // Ensure proper focus management
        const searchInput = document.getElementById('treatments-search');
        if (searchInput) {
            searchInput.setAttribute('aria-describedby', 'search-results');
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.medicalSpaTreatments = new MedicalSpaTreatments();
});

// Export for potential module use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = MedicalSpaTreatments;
}
