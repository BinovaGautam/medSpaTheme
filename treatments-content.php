<?php
/**
 * Treatments Content Template
 * Dynamic WordPress template that displays treatments from the database
 */

// Query all treatments from the database
$treatments_query = new WP_Query([
    'post_type' => 'treatment',
    'posts_per_page' => -1, // Get all treatments
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'post_status' => 'publish'
]);
?>

<?php
// Enqueue consolidated treatments CSS
wp_enqueue_style('treatments-consolidated', get_template_directory_uri() . '/assets/css/treatments-consolidated.css', array(), '2.0.0');
?>

<!-- Page Header -->
<header class="treatments-page-header">
    <div class="container">
        <h1>Our Treatments</h1>
        <p class="subtitle">Discover our comprehensive range of premium medical spa treatments designed to enhance your natural beauty and wellness</p>
    </div>
</header>

<!-- Search and Filter Section -->
<section class="search-filter-section" aria-labelledby="search-filter-heading">
    <div class="search-filter-header">
        <h2 id="search-filter-heading">Find Your Perfect Treatment</h2>
        <p>Use our advanced search and filtering options to discover treatments tailored to your needs</p>
    </div>

    <!-- Search Bar -->
    <div class="search-container">
        <label for="treatment-search" class="sr-only">Search treatments</label>
        <input
            type="search"
            id="treatment-search"
            class="search-input"
            placeholder="Search treatments, concerns, or body areas..."
            autocomplete="off"
            role="searchbox"
            aria-describedby="search-results-count"
        >
        <div id="search-results-count" class="search-results-count" aria-live="polite">
            Showing <?php echo $treatments_query->found_posts; ?> treatment<?php echo $treatments_query->found_posts !== 1 ? 's' : ''; ?>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-grid">
        <div class="filter-group">
            <label for="category-filter" class="filter-label">Treatment Category</label>
            <select id="category-filter" class="filter-select">
                <option value="">All Categories</option>
                <?php
                // Get all treatment categories
                $categories = get_terms(['taxonomy' => 'treatment_category', 'hide_empty' => true]);
                if ($categories && !is_wp_error($categories)) :
                    foreach ($categories as $category) : ?>
                        <option value="<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></option>
                    <?php endforeach;
                endif; ?>
            </select>
        </div>

        <div class="filter-group">
            <label for="price-filter" class="filter-label">Price Range</label>
            <select id="price-filter" class="filter-select">
                <option value="">All Prices</option>
                <option value="0-200">Under $200</option>
                <option value="200-500">$200 - $500</option>
                <option value="500-1000">$500 - $1,000</option>
                <option value="1000+">$1,000+</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="duration-filter" class="filter-label">Treatment Duration</label>
            <select id="duration-filter" class="filter-select">
                <option value="">Any Duration</option>
                <option value="0-30">Under 30 minutes</option>
                <option value="30-60">30-60 minutes</option>
                <option value="60-90">1-1.5 hours</option>
                <option value="90+">Over 1.5 hours</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="concern-filter" class="filter-label">Primary Concern</label>
            <select id="concern-filter" class="filter-select">
                <option value="">All Concerns</option>
                <option value="aging">Anti-Aging</option>
                <option value="acne">Acne & Scarring</option>
                <option value="pigmentation">Pigmentation</option>
                <option value="texture">Skin Texture</option>
                <option value="body-contouring">Body Contouring</option>
                <option value="hair-removal">Hair Removal</option>
            </select>
        </div>
    </div>

    <!-- Filter Actions -->
    <div class="filter-actions">
        <button type="button" class="btn btn-primary" id="apply-filters">
            Apply Filters
        </button>
        <button type="button" class="btn btn-secondary" id="reset-filters">
            Reset All
        </button>
    </div>
</section>

<!-- Treatment Comparison Bar -->
<div class="comparison-bar" id="comparison-bar" aria-live="polite">
    <div class="comparison-info">
        <span class="comparison-count" id="comparison-count">0</span>
        <span>treatments selected for comparison</span>
    </div>
    <button type="button" class="btn btn-primary" id="compare-treatments" disabled>
        Compare Selected
    </button>
</div>

<!-- Treatments Grid -->
<section aria-labelledby="treatments-heading">
    <h2 id="treatments-heading" class="sr-only">Available Treatments</h2>
    <div class="treatments-grid" id="treatments-grid">

        <?php if ($treatments_query->have_posts()) : ?>
            <?php while ($treatments_query->have_posts()) : $treatments_query->the_post();
                // Get treatment metadata for filtering
                $categories = get_the_terms(get_the_ID(), 'treatment_category');
                $primary_category = $categories && !is_wp_error($categories) ? $categories[0]->slug : '';
                $primary_category_name = $categories && !is_wp_error($categories) ? $categories[0]->name : '';
                $duration = get_post_meta(get_the_ID(), 'treatment_duration', true);
                $duration_minutes = get_post_meta(get_the_ID(), 'treatment_duration_minutes', true);
                $price_range = get_post_meta(get_the_ID(), 'treatment_price_range', true);
                $price = get_post_meta(get_the_ID(), 'treatment_price', true);
                $popularity = get_post_meta(get_the_ID(), 'treatment_popularity', true);
                $featured = get_post_meta(get_the_ID(), 'treatment_featured', true);
                $downtime = get_post_meta(get_the_ID(), 'treatment_downtime', true);
                $results_timeline = get_post_meta(get_the_ID(), 'treatment_results_timeline', true);
                $sessions_needed = get_post_meta(get_the_ID(), 'treatment_sessions_needed', true);

                // Convert price to numeric for filtering
                $numeric_price = 0;
                if ($price) {
                    $numeric_price = intval(preg_replace('/[^0-9]/', '', $price));
                } elseif ($price_range) {
                    // Extract first number from range
                    preg_match('/\d+/', $price_range, $matches);
                    $numeric_price = $matches ? intval($matches[0]) : 0;
                }

                // Convert duration to minutes for filtering
                $duration_in_minutes = $duration_minutes ?: 60;
                if (!$duration_minutes && $duration) {
                    if (strpos($duration, 'hour') !== false) {
                        preg_match('/(\d+)/', $duration, $matches);
                        $duration_in_minutes = $matches ? intval($matches[0]) * 60 : 60;
                    } elseif (strpos($duration, 'min') !== false) {
                        preg_match('/(\d+)/', $duration, $matches);
                        $duration_in_minutes = $matches ? intval($matches[0]) : 30;
                    }
                }
            ?>

            <article class="treatment-card"
                     data-category="<?php echo esc_attr($primary_category); ?>"
                     data-price="<?php echo esc_attr($numeric_price); ?>"
                     data-duration="<?php echo esc_attr($duration_in_minutes); ?>"
                     data-concern="<?php echo esc_attr($primary_category); ?>"
                     data-popularity="<?php echo esc_attr($popularity ?: ($featured ? '5' : '1')); ?>">

                <button type="button" class="btn-compare"
                        aria-label="Add <?php echo esc_attr(get_the_title()); ?> to comparison"
                        data-treatment="<?php echo esc_attr(sanitize_title(get_the_title())); ?>">
                    +
                </button>

                <div class="treatment-image">
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('treatment-card', ['alt' => get_the_title()]); ?>
                        </a>
                    <?php else : ?>
                        <a href="<?php the_permalink(); ?>" class="treatment-placeholder">
                            <div class="placeholder-icon">💉</div>
                        </a>
                    <?php endif; ?>

                    <!-- Treatment Category Badge -->
                    <?php if ($primary_category_name) : ?>
                        <span class="treatment-category-badge"><?php echo esc_html($primary_category_name); ?></span>
                    <?php endif; ?>

                    <?php if ($featured) : ?>
                        <span class="treatment-badge popular">
                            <span class="icon">⭐</span>
                            Popular
                        </span>
                    <?php endif; ?>
                </div>

                <div class="treatment-content">
                    <header class="treatment-header">
                        <h3 class="treatment-title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>

                        <div class="treatment-meta">
                            <?php if ($duration) : ?>
                                <span class="meta-item treatment-duration">
                                    <span class="icon">⏱️</span>
                                    <?php echo esc_html($duration); ?>
                                </span>
                            <?php endif; ?>

                            <?php if ($price_range) : ?>
                                <span class="meta-item treatment-price">
                                    <span class="icon">💰</span>
                                    <?php echo esc_html($price_range); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </header>

                    <div class="treatment-description">
                        <?php echo wp_trim_words(get_the_excerpt() ?: get_the_content(), 20, '...'); ?>
                    </div>

                    <!-- Treatment Details Grid -->
                    <div class="treatment-details">
                        <div class="detail-item">
                            <span class="detail-label">Duration</span>
                            <span class="detail-value"><?php echo esc_html($duration ?: '60 minutes'); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Downtime</span>
                            <span class="detail-value"><?php echo esc_html($downtime ?: 'Minimal'); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Results</span>
                            <span class="detail-value"><?php echo esc_html($results_timeline ?: 'Immediate'); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Sessions</span>
                            <span class="detail-value"><?php echo esc_html($sessions_needed ?: '1-3 recommended'); ?></span>
                        </div>
                    </div>

                    <?php if ($price_range || $price) : ?>
                        <div class="treatment-price">
                            <span class="price-main"><?php echo esc_html($price ?: $price_range); ?></span>
                            <?php if ($numeric_price > 100) : ?>
                                <span class="price-financing">or $<?php echo esc_html(ceil($numeric_price / 12)); ?>/month</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="treatment-actions">
                        <button type="button" class="btn-treatment btn-book"
                                data-treatment="<?php echo esc_attr(get_the_title()); ?>">
                            Book Consultation
                        </button>
                        <a href="<?php the_permalink(); ?>" class="btn-treatment btn-learn">
                            Learn More
                        </a>
                    </div>
                </div>
            </article>

            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>

        <?php else : ?>
            <!-- No treatments found message -->
            <div class="no-treatments-message">
                <h3>No Treatments Found</h3>
                <p>We're currently updating our treatment database. Please check back soon or contact us to learn about our available services.</p>
                <div class="no-treatments-actions">
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary">
                        Contact Us
                    </a>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-secondary">
                        Return Home
                    </a>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>

<script>
/**
 * Treatment Manager - Handles search, filtering, and comparison functionality
 */
class TreatmentManager {
    constructor() {
        this.treatments = [];
        this.filteredTreatments = [];
        this.comparedTreatments = new Set();
        this.maxComparisons = 3;

        this.init();
    }

    init() {
        this.loadTreatments();
        this.bindEvents();
        this.updateDisplay();
    }

    loadTreatments() {
        // Get all treatment cards from the DOM
        const treatmentCards = document.querySelectorAll('.treatment-card');
        this.treatments = Array.from(treatmentCards).map(card => ({
            element: card,
            category: card.dataset.category,
            price: parseInt(card.dataset.price),
            duration: parseInt(card.dataset.duration),
            concern: card.dataset.concern,
            title: card.querySelector('.treatment-title a').textContent,
            description: card.querySelector('.treatment-description').textContent
        }));

        this.filteredTreatments = [...this.treatments];
    }

    bindEvents() {
        // Search functionality
        const searchInput = document.getElementById('treatment-search');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.handleSearch(e.target.value);
            });
        }

        // Filter functionality
        const filters = ['category-filter', 'price-filter', 'duration-filter', 'concern-filter'];
        filters.forEach(filterId => {
            const filter = document.getElementById(filterId);
            if (filter) {
                filter.addEventListener('change', () => this.applyFilters());
            }
        });

        // Filter buttons
        const applyBtn = document.getElementById('apply-filters');
        const resetBtn = document.getElementById('reset-filters');

        if (applyBtn) {
            applyBtn.addEventListener('click', () => this.applyFilters());
        }

        if (resetBtn) {
            resetBtn.addEventListener('click', () => this.resetFilters());
        }

        // Comparison functionality
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('btn-compare')) {
                this.toggleComparison(e.target);
            }

            if (e.target.classList.contains('btn-book')) {
                this.handleBooking(e.target);
            }
        });

        const compareBtn = document.getElementById('compare-treatments');
        if (compareBtn) {
            compareBtn.addEventListener('click', () => this.showComparison());
        }
    }

    handleSearch(query) {
        const searchTerm = query.toLowerCase().trim();

        if (!searchTerm) {
            this.filteredTreatments = [...this.treatments];
        } else {
            this.filteredTreatments = this.treatments.filter(treatment =>
                treatment.title.toLowerCase().includes(searchTerm) ||
                treatment.description.toLowerCase().includes(searchTerm) ||
                treatment.category.toLowerCase().includes(searchTerm) ||
                treatment.concern.toLowerCase().includes(searchTerm)
            );
        }

        this.updateDisplay();
    }

    applyFilters() {
        const categoryFilter = document.getElementById('category-filter').value;
        const priceFilter = document.getElementById('price-filter').value;
        const durationFilter = document.getElementById('duration-filter').value;
        const concernFilter = document.getElementById('concern-filter').value;

        this.filteredTreatments = this.treatments.filter(treatment => {
            // Category filter
            if (categoryFilter && treatment.category !== categoryFilter) {
                return false;
            }

            // Price filter
            if (priceFilter) {
                const [min, max] = priceFilter.split('-').map(p => p === '+' ? Infinity : parseInt(p));
                if (treatment.price < min || (max && treatment.price > max)) {
                    return false;
                }
            }

            // Duration filter
            if (durationFilter) {
                const [min, max] = durationFilter.split('-').map(d => d === '+' ? Infinity : parseInt(d));
                if (treatment.duration < min || (max && treatment.duration > max)) {
                    return false;
                }
            }

            // Concern filter (same as category for now)
            if (concernFilter && treatment.concern !== concernFilter) {
                return false;
            }

            return true;
        });

        this.updateDisplay();
    }

    resetFilters() {
        // Reset all filter selects
        document.getElementById('category-filter').value = '';
        document.getElementById('price-filter').value = '';
        document.getElementById('duration-filter').value = '';
        document.getElementById('concern-filter').value = '';
        document.getElementById('treatment-search').value = '';

        this.filteredTreatments = [...this.treatments];
        this.updateDisplay();
    }

    updateDisplay() {
        // Hide all treatments first
        this.treatments.forEach(treatment => {
            treatment.element.style.display = 'none';
        });

        // Show filtered treatments
        this.filteredTreatments.forEach(treatment => {
            treatment.element.style.display = 'block';
        });

        // Update results count
        const resultsCount = document.getElementById('search-results-count');
        if (resultsCount) {
            const count = this.filteredTreatments.length;
            resultsCount.textContent = `Showing ${count} treatment${count !== 1 ? 's' : ''}`;
        }
    }

    toggleComparison(button) {
        const treatmentId = button.dataset.treatment;
        const card = button.closest('.treatment-card');

        if (this.comparedTreatments.has(treatmentId)) {
            // Remove from comparison
            this.comparedTreatments.delete(treatmentId);
            button.classList.remove('active');
            card.classList.remove('comparing');
            button.textContent = '+';
            button.setAttribute('aria-label', button.getAttribute('aria-label').replace('Remove', 'Add'));
        } else {
            // Add to comparison (if under limit)
            if (this.comparedTreatments.size < this.maxComparisons) {
                this.comparedTreatments.add(treatmentId);
                button.classList.add('active');
                card.classList.add('comparing');
                button.textContent = '✓';
                button.setAttribute('aria-label', button.getAttribute('aria-label').replace('Add', 'Remove'));
            } else {
                alert(`You can only compare up to ${this.maxComparisons} treatments at once.`);
            }
        }

        this.updateComparisonBar();
    }

    updateComparisonBar() {
        const comparisonBar = document.getElementById('comparison-bar');
        const comparisonCount = document.getElementById('comparison-count');
        const compareBtn = document.getElementById('compare-treatments');

        const count = this.comparedTreatments.size;

        if (count > 0) {
            comparisonBar.classList.add('active');
            comparisonCount.textContent = count;
            compareBtn.disabled = count < 2;
        } else {
            comparisonBar.classList.remove('active');
            compareBtn.disabled = true;
        }
    }

    showComparison() {
        if (this.comparedTreatments.size < 2) {
            alert('Please select at least 2 treatments to compare.');
            return;
        }

        // Here you would typically open a modal or navigate to a comparison page
        alert(`Comparing ${this.comparedTreatments.size} treatments. This would open a detailed comparison view.`);
    }

    handleBooking(button) {
        const treatmentTitle = button.dataset.treatment;

        // Redirect to contact page with treatment pre-filled
        window.location.href = '<?php echo esc_url(home_url('/contact/')); ?>?treatment=' + encodeURIComponent(treatmentTitle);
    }
}

// Initialize the treatment manager when the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new TreatmentManager();
    console.log('✅ Dynamic Treatments Page loaded with', document.querySelectorAll('.treatment-card').length, 'treatments');
});
</script>
