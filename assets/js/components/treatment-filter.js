/**
 * Treatment Filter Component
 *
 * Advanced filtering and search system for medical spa treatments
 * Features category filtering, search functionality, price/duration filters, and smooth animations
 *
 * @author PreetiDreams Development Team
 * @version 2.0.0
 */

'use strict';

class TreatmentFilter {
    constructor(selector = '.treatment-filters') {
        this.container = document.querySelector(selector);
        this.treatmentGrid = null;
        this.treatmentCards = [];
        this.filters = new Map();
        this.searchTerm = '';
        this.sortBy = 'default';
        this.isAnimating = false;

        // Configuration
        this.config = {
            animationDuration: 300,
            searchDebounceDelay: 300,
            enableUrlState: true,
            enableAnalytics: true,
            showResultCount: true,
            noResultsMessage: 'No treatments found matching your criteria.'
        };

        // Filter state
        this.state = {
            activeFilters: {
                category: 'all',
                duration: 'all',
                priceRange: 'all',
                popularity: 'all'
            },
            searchQuery: '',
            sortOrder: 'default',
            totalResults: 0,
            isLoading: false
        };

        // Bind methods
        this.init = this.init.bind(this);
        this.handleFilterChange = this.handleFilterChange.bind(this);
        this.handleSearch = this.handleSearch.bind(this);
        this.handleSort = this.handleSort.bind(this);
        this.handleClearFilters = this.handleClearFilters.bind(this);
        this.updateResults = this.updateResults.bind(this);
        this.debouncedSearch = this.debounce(this.performSearch.bind(this), this.config.searchDebounceDelay);
    }

    /**
     * Initialize the treatment filter component
     */
    async init() {
        try {
            if (!this.container) {
                console.warn('Treatment filter container not found');
                return;
            }

            // Find treatment grid and cards
            this.treatmentGrid = document.querySelector('.treatment-grid, .treatments-container, .treatment-archive');
            if (!this.treatmentGrid) {
                console.warn('Treatment grid not found');
                return;
            }

            this.treatmentCards = Array.from(this.treatmentGrid.querySelectorAll('.treatment-card, .treatment-item'));

            // Build filter interface
            this.buildFilterInterface();

            // Setup event listeners
            this.setupEventListeners();

            // Setup URL state management
            if (this.config.enableUrlState) {
                this.setupUrlState();
            }

            // Initialize from URL parameters
            this.initializeFromUrl();

            // Setup analytics
            if (this.config.enableAnalytics) {
                this.setupAnalytics();
            }

            // Initial filter application
            this.updateResults();

            console.log('Treatment filter initialized');

        } catch (error) {
            console.error('Treatment filter initialization failed:', error);
        }
    }

    /**
     * Build the filter interface
     */
    buildFilterInterface() {
        // Extract available filter options from treatment cards
        const categories = this.extractFilterOptions('data-category');
        const durations = this.extractFilterOptions('data-duration');
        const priceRanges = this.extractFilterOptions('data-price-range');

        // Create filter HTML
        this.container.innerHTML = `
            <div class="treatment-filters-wrapper">
                <div class="filter-header">
                    <h3 class="filter-title">Find Your Treatment</h3>
                    <button class="clear-filters" type="button">
                        <span>Clear All</span>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M8 16A8 8 0 1 1 8 0a8 8 0 0 1 0 16zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                        </svg>
                    </button>
                </div>

                <div class="filter-controls">
                    <!-- Search Bar -->
                    <div class="filter-group search-group">
                        <label for="treatment-search" class="sr-only">Search treatments</label>
                        <div class="search-input-wrapper">
                            <input
                                type="search"
                                id="treatment-search"
                                class="treatment-search"
                                placeholder="Search treatments..."
                                aria-label="Search treatments"
                            >
                            <svg class="search-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="filter-group">
                        <label for="category-filter">Category</label>
                        <select id="category-filter" class="filter-select" data-filter="category">
                            <option value="all">All Categories</option>
                            ${categories.map(cat => `<option value="${cat.value}">${cat.label}</option>`).join('')}
                        </select>
                    </div>

                    <!-- Duration Filter -->
                    <div class="filter-group">
                        <label for="duration-filter">Duration</label>
                        <select id="duration-filter" class="filter-select" data-filter="duration">
                            <option value="all">Any Duration</option>
                            ${durations.map(dur => `<option value="${dur.value}">${dur.label}</option>`).join('')}
                        </select>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="filter-group">
                        <label for="price-filter">Price Range</label>
                        <select id="price-filter" class="filter-select" data-filter="priceRange">
                            <option value="all">Any Price</option>
                            ${priceRanges.map(price => `<option value="${price.value}">${price.label}</option>`).join('')}
                        </select>
                    </div>

                    <!-- Sort Options -->
                    <div class="filter-group">
                        <label for="sort-filter">Sort By</label>
                        <select id="sort-filter" class="filter-select sort-select">
                            <option value="default">Default</option>
                            <option value="name-asc">Name (A-Z)</option>
                            <option value="name-desc">Name (Z-A)</option>
                            <option value="price-asc">Price (Low to High)</option>
                            <option value="price-desc">Price (High to Low)</option>
                            <option value="duration-asc">Duration (Short to Long)</option>
                            <option value="duration-desc">Duration (Long to Short)</option>
                            <option value="popularity">Most Popular</option>
                        </select>
                    </div>
                </div>

                <div class="filter-results">
                    <div class="results-count" role="status" aria-live="polite">
                        <span class="count-text">Showing all treatments</span>
                    </div>
                </div>
            </div>
        `;

        // Add loading indicator
        this.createLoadingIndicator();

        // Add no results message
        this.createNoResultsMessage();
    }

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        // Search input
        const searchInput = this.container.querySelector('.treatment-search');
        if (searchInput) {
            searchInput.addEventListener('input', this.handleSearch);
            searchInput.addEventListener('keydown', this.handleSearchKeydown.bind(this));
        }

        // Filter selects
        const filterSelects = this.container.querySelectorAll('.filter-select:not(.sort-select)');
        filterSelects.forEach(select => {
            select.addEventListener('change', this.handleFilterChange);
        });

        // Sort select
        const sortSelect = this.container.querySelector('.sort-select');
        if (sortSelect) {
            sortSelect.addEventListener('change', this.handleSort);
        }

        // Clear filters button
        const clearButton = this.container.querySelector('.clear-filters');
        if (clearButton) {
            clearButton.addEventListener('click', this.handleClearFilters);
        }

        // Custom events
        document.addEventListener('treatmentFilterUpdate', this.handleCustomFilterUpdate.bind(this));
    }

    /**
     * Handle filter changes
     */
    handleFilterChange(event) {
        const filterType = event.target.getAttribute('data-filter');
        const filterValue = event.target.value;

        if (filterType) {
            this.state.activeFilters[filterType] = filterValue;
            this.updateResults();
            this.trackFilterUsage(filterType, filterValue);
        }
    }

    /**
     * Handle search input
     */
    handleSearch(event) {
        this.state.searchQuery = event.target.value.trim();
        this.debouncedSearch();
    }

    /**
     * Handle search keydown events
     */
    handleSearchKeydown(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            this.performSearch();
        }

        if (event.key === 'Escape') {
            event.target.value = '';
            this.state.searchQuery = '';
            this.updateResults();
        }
    }

    /**
     * Perform search with current query
     */
    performSearch() {
        this.updateResults();
        this.trackSearchUsage(this.state.searchQuery);
    }

    /**
     * Handle sort changes
     */
    handleSort(event) {
        this.state.sortOrder = event.target.value;
        this.updateResults();
        this.trackSortUsage(this.state.sortOrder);
    }

    /**
     * Handle clear filters
     */
    handleClearFilters() {
        // Reset all filters
        this.state.activeFilters = {
            category: 'all',
            duration: 'all',
            priceRange: 'all',
            popularity: 'all'
        };
        this.state.searchQuery = '';
        this.state.sortOrder = 'default';

        // Reset UI
        this.container.querySelector('.treatment-search').value = '';
        this.container.querySelectorAll('.filter-select').forEach(select => {
            if (select.classList.contains('sort-select')) {
                select.value = 'default';
            } else {
                select.value = 'all';
            }
        });

        // Update results
        this.updateResults();

        // Track analytics
        this.trackEvent('filters_cleared');
    }

    /**
     * Update results based on current filters
     */
    async updateResults() {
        if (this.isAnimating) return;

        this.isAnimating = true;
        this.state.isLoading = true;

        // Show loading state
        this.showLoading();

        try {
            // Get filtered and sorted cards
            const filteredCards = this.filterTreatments();
            const sortedCards = this.sortTreatments(filteredCards);

            // Animate out current cards
            await this.animateOut();

            // Update grid with new cards
            this.updateGrid(sortedCards);

            // Update results count
            this.updateResultsCount(sortedCards.length);

            // Animate in new cards
            await this.animateIn(sortedCards);

            // Update URL state
            if (this.config.enableUrlState) {
                this.updateUrlState();
            }

            // Update accessibility
            this.updateAccessibility();

        } catch (error) {
            console.error('Error updating filter results:', error);
        } finally {
            this.hideLoading();
            this.state.isLoading = false;
            this.isAnimating = false;
        }
    }

    /**
     * Filter treatments based on current state
     */
    filterTreatments() {
        return this.treatmentCards.filter(card => {
            // Search filter
            if (this.state.searchQuery) {
                const searchText = this.getCardSearchText(card);
                if (!searchText.toLowerCase().includes(this.state.searchQuery.toLowerCase())) {
                    return false;
                }
            }

            // Category filter
            if (this.state.activeFilters.category !== 'all') {
                const cardCategory = card.getAttribute('data-category');
                if (cardCategory !== this.state.activeFilters.category) {
                    return false;
                }
            }

            // Duration filter
            if (this.state.activeFilters.duration !== 'all') {
                const cardDuration = card.getAttribute('data-duration');
                if (cardDuration !== this.state.activeFilters.duration) {
                    return false;
                }
            }

            // Price range filter
            if (this.state.activeFilters.priceRange !== 'all') {
                const cardPriceRange = card.getAttribute('data-price-range');
                if (cardPriceRange !== this.state.activeFilters.priceRange) {
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * Sort treatments based on current sort order
     */
    sortTreatments(cards) {
        const sortedCards = [...cards];

        switch (this.state.sortOrder) {
            case 'name-asc':
                sortedCards.sort((a, b) => this.getCardTitle(a).localeCompare(this.getCardTitle(b)));
                break;

            case 'name-desc':
                sortedCards.sort((a, b) => this.getCardTitle(b).localeCompare(this.getCardTitle(a)));
                break;

            case 'price-asc':
                sortedCards.sort((a, b) => this.getCardPrice(a) - this.getCardPrice(b));
                break;

            case 'price-desc':
                sortedCards.sort((a, b) => this.getCardPrice(b) - this.getCardPrice(a));
                break;

            case 'duration-asc':
                sortedCards.sort((a, b) => this.getCardDuration(a) - this.getCardDuration(b));
                break;

            case 'duration-desc':
                sortedCards.sort((a, b) => this.getCardDuration(b) - this.getCardDuration(a));
                break;

            case 'popularity':
                sortedCards.sort((a, b) => this.getCardPopularity(b) - this.getCardPopularity(a));
                break;

            default:
                // Keep original order
                break;
        }

        return sortedCards;
    }

    /**
     * Animate cards out
     */
    animateOut() {
        return new Promise(resolve => {
            const visibleCards = this.treatmentGrid.querySelectorAll('.treatment-card:not(.hidden)');

            if (visibleCards.length === 0) {
                resolve();
                return;
            }

            visibleCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(-20px)';
                }, index * 50);
            });

            setTimeout(resolve, visibleCards.length * 50 + this.config.animationDuration);
        });
    }

    /**
     * Update grid with filtered cards
     */
    updateGrid(filteredCards) {
        // Hide all cards
        this.treatmentCards.forEach(card => {
            card.classList.add('hidden');
            card.style.display = 'none';
        });

        // Show filtered cards
        filteredCards.forEach(card => {
            card.classList.remove('hidden');
            card.style.display = '';
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
        });

        // Show/hide no results message
        if (filteredCards.length === 0) {
            this.showNoResults();
        } else {
            this.hideNoResults();
        }
    }

    /**
     * Animate cards in
     */
    animateIn(cards) {
        return new Promise(resolve => {
            if (cards.length === 0) {
                resolve();
                return;
            }

            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            setTimeout(resolve, cards.length * 100 + this.config.animationDuration);
        });
    }

    /**
     * Helper methods for extracting card data
     */

    getCardSearchText(card) {
        const title = card.querySelector('.treatment-title, .card-title, h3, h4')?.textContent || '';
        const description = card.querySelector('.treatment-description, .card-description, p')?.textContent || '';
        const category = card.getAttribute('data-category') || '';
        return `${title} ${description} ${category}`;
    }

    getCardTitle(card) {
        return card.querySelector('.treatment-title, .card-title, h3, h4')?.textContent?.trim() || '';
    }

    getCardPrice(card) {
        const priceText = card.getAttribute('data-price') || card.querySelector('.price')?.textContent || '0';
        return parseFloat(priceText.replace(/[^0-9.]/g, '')) || 0;
    }

    getCardDuration(card) {
        const durationText = card.getAttribute('data-duration-minutes') || '0';
        return parseInt(durationText) || 0;
    }

    getCardPopularity(card) {
        const popularityText = card.getAttribute('data-popularity') || '0';
        return parseInt(popularityText) || 0;
    }

    /**
     * Extract filter options from treatment cards
     */
    extractFilterOptions(attribute) {
        const options = new Set();

        this.treatmentCards.forEach(card => {
            const value = card.getAttribute(attribute);
            if (value && value !== 'undefined') {
                options.add(value);
            }
        });

        return Array.from(options).map(value => ({
            value,
            label: this.formatOptionLabel(value)
        })).sort((a, b) => a.label.localeCompare(b.label));
    }

    /**
     * Format option labels for display
     */
    formatOptionLabel(value) {
        return value
            .split('-')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
    }

    /**
     * Update results count display
     */
    updateResultsCount(count) {
        const countElement = this.container.querySelector('.count-text');
        if (countElement) {
            const totalTreatments = this.treatmentCards.length;

            if (count === totalTreatments) {
                countElement.textContent = `Showing all ${totalTreatments} treatments`;
            } else {
                countElement.textContent = `Showing ${count} of ${totalTreatments} treatments`;
            }
        }

        this.state.totalResults = count;
    }

    /**
     * Loading state methods
     */
    createLoadingIndicator() {
        const loadingHTML = `
            <div class="filter-loading hidden" role="status" aria-label="Loading treatments">
                <div class="loading-spinner"></div>
                <span class="loading-text">Filtering treatments...</span>
            </div>
        `;
        this.container.insertAdjacentHTML('beforeend', loadingHTML);
    }

    showLoading() {
        const loading = this.container.querySelector('.filter-loading');
        if (loading) {
            loading.classList.remove('hidden');
        }
    }

    hideLoading() {
        const loading = this.container.querySelector('.filter-loading');
        if (loading) {
            loading.classList.add('hidden');
        }
    }

    /**
     * No results methods
     */
    createNoResultsMessage() {
        const noResultsHTML = `
            <div class="no-results hidden" role="alert">
                <div class="no-results-icon">🔍</div>
                <h4 class="no-results-title">No treatments found</h4>
                <p class="no-results-message">${this.config.noResultsMessage}</p>
                <button class="btn btn-outline clear-search-btn" type="button">
                    Clear Search & Filters
                </button>
            </div>
        `;

        if (this.treatmentGrid) {
            this.treatmentGrid.insertAdjacentHTML('afterend', noResultsHTML);

            // Add event listener to clear button
            const clearBtn = document.querySelector('.clear-search-btn');
            if (clearBtn) {
                clearBtn.addEventListener('click', this.handleClearFilters);
            }
        }
    }

    showNoResults() {
        const noResults = document.querySelector('.no-results');
        if (noResults) {
            noResults.classList.remove('hidden');
        }
    }

    hideNoResults() {
        const noResults = document.querySelector('.no-results');
        if (noResults) {
            noResults.classList.add('hidden');
        }
    }

    /**
     * URL state management
     */
    setupUrlState() {
        window.addEventListener('popstate', this.handlePopState.bind(this));
    }

    updateUrlState() {
        const params = new URLSearchParams();

        // Add active filters to URL
        Object.keys(this.state.activeFilters).forEach(key => {
            if (this.state.activeFilters[key] !== 'all') {
                params.set(key, this.state.activeFilters[key]);
            }
        });

        // Add search query
        if (this.state.searchQuery) {
            params.set('search', this.state.searchQuery);
        }

        // Add sort order
        if (this.state.sortOrder !== 'default') {
            params.set('sort', this.state.sortOrder);
        }

        // Update URL without reload
        const newUrl = params.toString() ? `${window.location.pathname}?${params.toString()}` : window.location.pathname;
        window.history.replaceState(this.state, '', newUrl);
    }

    initializeFromUrl() {
        const params = new URLSearchParams(window.location.search);

        // Set filters from URL
        Object.keys(this.state.activeFilters).forEach(key => {
            if (params.has(key)) {
                this.state.activeFilters[key] = params.get(key);
            }
        });

        // Set search query from URL
        if (params.has('search')) {
            this.state.searchQuery = params.get('search');
            const searchInput = this.container.querySelector('.treatment-search');
            if (searchInput) {
                searchInput.value = this.state.searchQuery;
            }
        }

        // Set sort order from URL
        if (params.has('sort')) {
            this.state.sortOrder = params.get('sort');
        }

        // Update UI to match state
        this.syncUIWithState();
    }

    syncUIWithState() {
        // Update filter selects
        Object.keys(this.state.activeFilters).forEach(key => {
            const select = this.container.querySelector(`[data-filter="${key}"]`);
            if (select) {
                select.value = this.state.activeFilters[key];
            }
        });

        // Update sort select
        const sortSelect = this.container.querySelector('.sort-select');
        if (sortSelect) {
            sortSelect.value = this.state.sortOrder;
        }
    }

    handlePopState(event) {
        if (event.state) {
            this.state = { ...this.state, ...event.state };
            this.syncUIWithState();
            this.updateResults();
        }
    }

    /**
     * Analytics tracking
     */
    setupAnalytics() {
        this.analytics = window.MedicalSpaApp?.getModule('analytics');
    }

    trackFilterUsage(filterType, filterValue) {
        this.trackEvent('treatment_filter_used', {
            filter_type: filterType,
            filter_value: filterValue,
            total_results: this.state.totalResults
        });
    }

    trackSearchUsage(searchQuery) {
        this.trackEvent('treatment_search_performed', {
            search_query: searchQuery,
            results_count: this.state.totalResults
        });
    }

    trackSortUsage(sortOrder) {
        this.trackEvent('treatment_sort_changed', {
            sort_order: sortOrder,
            total_results: this.state.totalResults
        });
    }

    trackEvent(eventName, properties = {}) {
        if (this.analytics && typeof this.analytics.track === 'function') {
            this.analytics.track(eventName, {
                ...properties,
                component: 'TreatmentFilter',
                location: window.location.pathname
            });
        }
    }

    /**
     * Accessibility methods
     */
    updateAccessibility() {
        // Update live region with results
        const resultsCount = this.container.querySelector('.results-count');
        if (resultsCount) {
            resultsCount.setAttribute('aria-live', 'polite');
        }

        // Update ARIA labels
        const searchInput = this.container.querySelector('.treatment-search');
        if (searchInput && this.state.totalResults !== null) {
            searchInput.setAttribute('aria-describedby', 'search-results-count');
        }
    }

    /**
     * Utility methods
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

    handleCustomFilterUpdate(event) {
        const { filterType, filterValue } = event.detail;
        if (filterType && this.state.activeFilters.hasOwnProperty(filterType)) {
            this.state.activeFilters[filterType] = filterValue;
            this.syncUIWithState();
            this.updateResults();
        }
    }

    /**
     * Public API methods
     */
    getState() {
        return { ...this.state };
    }

    setFilter(filterType, filterValue) {
        if (this.state.activeFilters.hasOwnProperty(filterType)) {
            this.state.activeFilters[filterType] = filterValue;
            this.syncUIWithState();
            this.updateResults();
        }
    }

    setSearch(searchQuery) {
        this.state.searchQuery = searchQuery;
        const searchInput = this.container.querySelector('.treatment-search');
        if (searchInput) {
            searchInput.value = searchQuery;
        }
        this.updateResults();
    }

    clearAllFilters() {
        this.handleClearFilters();
    }

    destroy() {
        // Remove event listeners
        const searchInput = this.container.querySelector('.treatment-search');
        if (searchInput) {
            searchInput.removeEventListener('input', this.handleSearch);
        }

        const filterSelects = this.container.querySelectorAll('.filter-select');
        filterSelects.forEach(select => {
            select.removeEventListener('change', this.handleFilterChange);
        });

        const clearButton = this.container.querySelector('.clear-filters');
        if (clearButton) {
            clearButton.removeEventListener('click', this.handleClearFilters);
        }

        window.removeEventListener('popstate', this.handlePopState.bind(this));
        document.removeEventListener('treatmentFilterUpdate', this.handleCustomFilterUpdate.bind(this));

        console.log('Treatment filter destroyed');
    }
}

// Make TreatmentFilter globally available
window.TreatmentFilter = TreatmentFilter;

// Export for module usage (if needed)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = TreatmentFilter;
}
