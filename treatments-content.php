<?php
/**
 * Treatments Content Template
 * WordPress-compatible version of treatments.html
 */
?>

<style>
/* CSS Custom Properties for Medical Spa Theme */
:root {
    --primary-sage: #8BA888;
    --primary-navy: #2C3E50;
    --accent-gold: #D4AF37;
    --neutral-cream: #F8F6F3;
    --neutral-white: #FFFFFF;
    --neutral-gray: #6B7280;
    --neutral-dark: #1F2937;
    --success-green: #10B981;
    --warning-amber: #F59E0B;
    --error-red: #EF4444;
    --info-blue: #3B82F6;

    /* Typography */
    --font-primary: 'Playfair Display', serif;
    --font-secondary: 'Inter', sans-serif;

    /* Accessibility - High Contrast Ratios */
    --contrast-ratio-primary: 11.5; /* Exceeds WCAG AAA requirement */

    /* Spacing and Layout */
    --container-max-width: 1200px;
    --section-padding: 4rem 1rem;
    --card-border-radius: 12px;
    --transition-smooth: all 0.3s ease;
}

/* Treatments Page Specific Styles */
.treatments-page {
    background-color: var(--neutral-cream);
}

.treatments-page .container {
    max-width: var(--container-max-width);
}

/* Page Header */
.treatments-page-header {
    background: linear-gradient(135deg, var(--primary-navy) 0%, var(--primary-sage) 100%);
    color: var(--neutral-white);
    padding: var(--section-padding);
    text-align: center;
    margin: -2rem -1rem 3rem -1rem;
}

.treatments-page-header h1 {
    font-family: var(--font-primary);
    font-size: 3rem;
    font-weight: 600;
    margin-bottom: 1rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.treatments-page-header .subtitle {
    font-size: 1.2rem;
    font-weight: 300;
    opacity: 0.95;
}

/* Search and Filter Section */
.search-filter-section {
    background: var(--neutral-white);
    border-radius: var(--card-border-radius);
    padding: 2rem;
    margin-bottom: 3rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.search-filter-header {
    margin-bottom: 2rem;
}

.search-filter-header h2 {
    font-family: var(--font-primary);
    font-size: 1.8rem;
    color: var(--primary-navy);
    margin-bottom: 0.5rem;
}

.search-filter-header p {
    color: var(--neutral-gray);
    font-size: 1rem;
}

/* Search Bar */
.search-container {
    position: relative;
    margin-bottom: 2rem;
}

.search-input {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    border: 2px solid #E5E7EB;
    border-radius: 8px;
    font-size: 1rem;
    transition: var(--transition-smooth);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: 12px center;
    background-size: 20px;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary-sage);
    box-shadow: 0 0 0 3px rgba(139, 168, 136, 0.1);
}

.search-results-count {
    margin-top: 0.5rem;
    font-size: 0.9rem;
    color: var(--neutral-gray);
}

/* Filter Section */
.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-label {
    font-weight: 600;
    color: var(--primary-navy);
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.filter-select {
    padding: 0.75rem;
    border: 2px solid #E5E7EB;
    border-radius: 6px;
    font-size: 0.95rem;
    background-color: var(--neutral-white);
    transition: var(--transition-smooth);
}

.filter-select:focus {
    outline: none;
    border-color: var(--primary-sage);
    box-shadow: 0 0 0 3px rgba(139, 168, 136, 0.1);
}

/* Filter Actions */
.filter-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-smooth);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 44px; /* Accessibility: Touch target size */
}

.btn-primary {
    background-color: var(--primary-sage);
    color: var(--neutral-white);
}

.btn-primary:hover {
    background-color: #7A9A77;
    transform: translateY(-1px);
}

.btn-secondary {
    background-color: transparent;
    color: var(--primary-navy);
    border: 2px solid var(--primary-navy);
}

.btn-secondary:hover {
    background-color: var(--primary-navy);
    color: var(--neutral-white);
}

/* Treatment Comparison Bar */
.comparison-bar {
    background: var(--neutral-white);
    border: 2px solid var(--primary-sage);
    border-radius: var(--card-border-radius);
    padding: 1rem 1.5rem;
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    opacity: 0;
    transform: translateY(-10px);
    transition: var(--transition-smooth);
}

.comparison-bar.active {
    opacity: 1;
    transform: translateY(0);
}

.comparison-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--primary-navy);
    font-weight: 600;
}

.comparison-count {
    background: var(--primary-sage);
    color: var(--neutral-white);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.9rem;
    min-width: 24px;
    text-align: center;
}

/* Treatments Grid */
.treatments-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

/* Treatment Card */
.treatment-card {
    background: var(--neutral-white);
    border-radius: var(--card-border-radius);
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    transition: var(--transition-smooth);
    position: relative;
    border: 2px solid transparent;
}

.treatment-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
}

.treatment-card.comparing {
    border-color: var(--primary-sage);
    box-shadow: 0 0 0 3px rgba(139, 168, 136, 0.1);
}

/* Compare Button */
.btn-compare {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 50%;
    background: var(--neutral-white);
    color: var(--primary-sage);
    font-size: 1.2rem;
    font-weight: bold;
    cursor: pointer;
    z-index: 2;
    transition: var(--transition-smooth);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.btn-compare:hover {
    background: var(--primary-sage);
    color: var(--neutral-white);
    transform: scale(1.1);
}

.btn-compare.active {
    background: var(--primary-sage);
    color: var(--neutral-white);
}

/* Treatment Image */
.treatment-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: var(--transition-smooth);
}

.treatment-card:hover .treatment-image {
    transform: scale(1.05);
}

/* Treatment Content */
.treatment-content {
    padding: 1.5rem;
}

.treatment-category {
    color: var(--primary-sage);
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.treatment-title {
    font-family: var(--font-primary);
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--primary-navy);
    margin-bottom: 0.75rem;
    line-height: 1.3;
}

.treatment-description {
    color: var(--neutral-gray);
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

/* Treatment Details Grid */
.treatment-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: var(--neutral-cream);
    border-radius: 8px;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.detail-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--primary-navy);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value {
    font-size: 0.9rem;
    color: var(--neutral-dark);
    font-weight: 500;
}

/* Treatment Price */
.treatment-price {
    margin-bottom: 1.5rem;
    text-align: center;
}

.price-main {
    font-family: var(--font-primary);
    font-size: 1.8rem;
    font-weight: 600;
    color: var(--primary-navy);
    display: block;
}

.price-financing {
    font-size: 0.85rem;
    color: var(--neutral-gray);
    margin-top: 0.25rem;
}

/* Treatment Actions */
.treatment-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.btn-treatment {
    flex: 1;
    min-width: 120px;
    padding: 0.75rem 1rem;
    border: none;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-smooth);
    text-decoration: none;
    text-align: center;
    min-height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-book {
    background: var(--primary-sage);
    color: var(--neutral-white);
}

.btn-book:hover {
    background: #7A9A77;
    transform: translateY(-1px);
}

.btn-learn {
    background: transparent;
    color: var(--primary-navy);
    border: 2px solid var(--primary-navy);
}

.btn-learn:hover {
    background: var(--primary-navy);
    color: var(--neutral-white);
}

/* Screen Reader Only */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .treatments-page-header h1 {
        font-size: 2.2rem;
    }

    .treatments-page-header .subtitle {
        font-size: 1rem;
    }

    .filters-grid {
        grid-template-columns: 1fr;
    }

    .treatments-grid {
        grid-template-columns: 1fr;
    }

    .treatment-details {
        grid-template-columns: 1fr;
    }

    .treatment-actions {
        flex-direction: column;
    }

    .comparison-bar {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .search-filter-section {
        padding: 1.5rem;
    }

    .treatment-content {
        padding: 1rem;
    }

    .filter-actions {
        flex-direction: column;
    }
}
</style>

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
            Showing all treatments
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-grid">
        <div class="filter-group">
            <label for="category-filter" class="filter-label">Treatment Category</label>
            <select id="category-filter" class="filter-select">
                <option value="">All Categories</option>
                <option value="facial">Facial Treatments</option>
                <option value="body">Body Treatments</option>
                <option value="laser">Laser Treatments</option>
                <option value="injectables">Injectables</option>
                <option value="wellness">Wellness & Recovery</option>
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
        <!-- Treatment cards will be populated here -->

        <!-- Sample Treatment Card 1 -->
        <article class="treatment-card" data-category="facial" data-price="350" data-duration="60" data-concern="aging">
            <button type="button" class="btn-compare" aria-label="Add HydraFacial to comparison" data-treatment="hydrafacial">
                +
            </button>
            <img src="https://via.placeholder.com/350x200/8BA888/FFFFFF?text=HydraFacial" alt="HydraFacial treatment room with advanced equipment" class="treatment-image" loading="lazy">
            <div class="treatment-content">
                <div class="treatment-category">Facial Treatment</div>
                <h3 class="treatment-title">HydraFacial MD</h3>
                <p class="treatment-description">
                    The ultimate 3-in-1 treatment that cleanses, extracts, and hydrates your skin with patented Vortex-Fusion technology for immediate results.
                </p>
                <div class="treatment-details">
                    <div class="detail-item">
                        <span class="detail-label">Duration</span>
                        <span class="detail-value">60 minutes</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Downtime</span>
                        <span class="detail-value">None</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Results</span>
                        <span class="detail-value">Immediate</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Sessions</span>
                        <span class="detail-value">1-6 recommended</span>
                    </div>
                </div>
                <div class="treatment-price">
                    <span class="price-main">$350</span>
                    <span class="price-financing">or $29/month</span>
                </div>
                <div class="treatment-actions">
                    <button type="button" class="btn-treatment btn-book">Book Consultation</button>
                    <a href="<?php echo home_url('/treatments/hydrafacial/'); ?>" class="btn-treatment btn-learn">Learn More</a>
                </div>
            </div>
        </article>

        <!-- Sample Treatment Card 2 -->
        <article class="treatment-card" data-category="laser" data-price="450" data-duration="45" data-concern="pigmentation">
            <button type="button" class="btn-compare" aria-label="Add IPL Photofacial to comparison" data-treatment="ipl">
                +
            </button>
            <img src="https://via.placeholder.com/350x200/2C3E50/FFFFFF?text=IPL+Photofacial" alt="IPL Photofacial laser treatment setup" class="treatment-image" loading="lazy">
            <div class="treatment-content">
                <div class="treatment-category">Laser Treatment</div>
                <h3 class="treatment-title">IPL Photofacial</h3>
                <p class="treatment-description">
                    Advanced light therapy that targets pigmentation, sun damage, and vascular lesions for clearer, more even-toned skin.
                </p>
                <div class="treatment-details">
                    <div class="detail-item">
                        <span class="detail-label">Duration</span>
                        <span class="detail-value">45 minutes</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Downtime</span>
                        <span class="detail-value">Minimal</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Results</span>
                        <span class="detail-value">2-4 weeks</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Sessions</span>
                        <span class="detail-value">3-5 recommended</span>
                    </div>
                </div>
                <div class="treatment-price">
                    <span class="price-main">$450</span>
                    <span class="price-financing">or $38/month</span>
                </div>
                <div class="treatment-actions">
                    <button type="button" class="btn-treatment btn-book">Book Consultation</button>
                    <a href="<?php echo home_url('/treatments/ipl-photofacial/'); ?>" class="btn-treatment btn-learn">Learn More</a>
                </div>
            </div>
        </article>

        <!-- Sample Treatment Card 3 -->
        <article class="treatment-card" data-category="injectables" data-price="600" data-duration="30" data-concern="aging">
            <button type="button" class="btn-compare" aria-label="Add Botox to comparison" data-treatment="botox">
                +
            </button>
            <img src="https://via.placeholder.com/350x200/D4AF37/FFFFFF?text=Botox+Treatment" alt="Botox injection treatment consultation" class="treatment-image" loading="lazy">
            <div class="treatment-content">
                <div class="treatment-category">Injectable Treatment</div>
                <h3 class="treatment-title">Botox Cosmetic</h3>
                <p class="treatment-description">
                    FDA-approved neurotoxin that smooths dynamic wrinkles and fine lines for a refreshed, youthful appearance.
                </p>
                <div class="treatment-details">
                    <div class="detail-item">
                        <span class="detail-label">Duration</span>
                        <span class="detail-value">30 minutes</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Downtime</span>
                        <span class="detail-value">None</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Results</span>
                        <span class="detail-value">3-7 days</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Duration</span>
                        <span class="detail-value">3-4 months</span>
                    </div>
                </div>
                <div class="treatment-price">
                    <span class="price-main">$600</span>
                    <span class="price-financing">or $50/month</span>
                </div>
                <div class="treatment-actions">
                    <button type="button" class="btn-treatment btn-book">Book Consultation</button>
                    <a href="<?php echo home_url('/treatments/botox/'); ?>" class="btn-treatment btn-learn">Learn More</a>
                </div>
            </div>
        </article>

        <!-- Sample Treatment Card 4 -->
        <article class="treatment-card" data-category="body" data-price="800" data-duration="90" data-concern="body-contouring">
            <button type="button" class="btn-compare" aria-label="Add CoolSculpting to comparison" data-treatment="coolsculpting">
                +
            </button>
            <img src="https://via.placeholder.com/350x200/8BA888/FFFFFF?text=CoolSculpting" alt="CoolSculpting body contouring treatment" class="treatment-image" loading="lazy">
            <div class="treatment-content">
                <div class="treatment-category">Body Treatment</div>
                <h3 class="treatment-title">CoolSculpting</h3>
                <p class="treatment-description">
                    Non-invasive fat reduction technology that freezes and eliminates stubborn fat cells for permanent body contouring results.
                </p>
                <div class="treatment-details">
                    <div class="detail-item">
                        <span class="detail-label">Duration</span>
                        <span class="detail-value">90 minutes</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Downtime</span>
                        <span class="detail-value">Minimal</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Results</span>
                        <span class="detail-value">2-3 months</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Sessions</span>
                        <span class="detail-value">1-3 per area</span>
                    </div>
                </div>
                <div class="treatment-price">
                    <span class="price-main">$800</span>
                    <span class="price-financing">or $67/month</span>
                </div>
                <div class="treatment-actions">
                    <button type="button" class="btn-treatment btn-book">Book Consultation</button>
                    <a href="<?php echo home_url('/treatments/coolsculpting/'); ?>" class="btn-treatment btn-learn">Learn More</a>
                </div>
            </div>
        </article>

        <!-- Sample Treatment Card 5 -->
        <article class="treatment-card" data-category="laser" data-price="300" data-duration="30" data-concern="hair-removal">
            <button type="button" class="btn-compare" aria-label="Add Laser Hair Removal to comparison" data-treatment="laser-hair">
                +
            </button>
            <img src="https://via.placeholder.com/350x200/2C3E50/FFFFFF?text=Laser+Hair+Removal" alt="Laser hair removal treatment session" class="treatment-image" loading="lazy">
            <div class="treatment-content">
                <div class="treatment-category">Laser Treatment</div>
                <h3 class="treatment-title">Laser Hair Removal</h3>
                <p class="treatment-description">
                    Advanced laser technology that permanently reduces unwanted hair growth for smooth, hair-free skin.
                </p>
                <div class="treatment-details">
                    <div class="detail-item">
                        <span class="detail-label">Duration</span>
                        <span class="detail-value">30 minutes</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Downtime</span>
                        <span class="detail-value">None</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Results</span>
                        <span class="detail-value">Immediate</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Sessions</span>
                        <span class="detail-value">6-8 recommended</span>
                    </div>
                </div>
                <div class="treatment-price">
                    <span class="price-main">$300</span>
                    <span class="price-financing">or $25/month</span>
                </div>
                <div class="treatment-actions">
                    <button type="button" class="btn-treatment btn-book">Book Consultation</button>
                    <a href="<?php echo home_url('/treatments/laser-hair-removal/'); ?>" class="btn-treatment btn-learn">Learn More</a>
                </div>
            </div>
        </article>

        <!-- Sample Treatment Card 6 -->
        <article class="treatment-card" data-category="wellness" data-price="200" data-duration="60" data-concern="wellness">
            <button type="button" class="btn-compare" aria-label="Add IV Therapy to comparison" data-treatment="iv-therapy">
                +
            </button>
            <img src="https://via.placeholder.com/350x200/D4AF37/FFFFFF?text=IV+Therapy" alt="IV therapy wellness treatment" class="treatment-image" loading="lazy">
            <div class="treatment-content">
                <div class="treatment-category">Wellness Treatment</div>
                <h3 class="treatment-title">IV Vitamin Therapy</h3>
                <p class="treatment-description">
                    Customized vitamin and nutrient infusions delivered directly to your bloodstream for optimal wellness and energy.
                </p>
                <div class="treatment-details">
                    <div class="detail-item">
                        <span class="detail-label">Duration</span>
                        <span class="detail-value">60 minutes</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Downtime</span>
                        <span class="detail-value">None</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Results</span>
                        <span class="detail-value">Immediate</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Frequency</span>
                        <span class="detail-value">Weekly/Monthly</span>
                    </div>
                </div>
                <div class="treatment-price">
                    <span class="price-main">$200</span>
                    <span class="price-financing">or $17/month</span>
                </div>
                <div class="treatment-actions">
                    <button type="button" class="btn-treatment btn-book">Book Consultation</button>
                    <a href="<?php echo home_url('/treatments/iv-therapy/'); ?>" class="btn-treatment btn-learn">Learn More</a>
                </div>
            </div>
        </article>
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
            title: card.querySelector('.treatment-title').textContent,
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

            // Concern filter
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
        const card = button.closest('.treatment-card');
        const treatmentTitle = card.querySelector('.treatment-title').textContent;

        // Here you would typically integrate with your booking system
        // For now, we'll show an alert
        alert(`Booking consultation for ${treatmentTitle}. This would integrate with your booking system.`);

        // You could also redirect to a booking page:
        // window.location.href = '/book-consultation/?treatment=' + encodeURIComponent(treatmentTitle);
    }
}

// Initialize the treatment manager when the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new TreatmentManager();
});
</script>
