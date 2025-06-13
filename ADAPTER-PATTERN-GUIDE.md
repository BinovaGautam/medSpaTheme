# Treatments Data Adapter Pattern Guide

## Overview

The Treatments Adapter Pattern provides a flexible abstraction layer for treatment data sources, allowing seamless switching between hardcoded data, WordPress CMS, or external APIs without changing the consuming code.

## Architecture

### Interface Layer
```php
interface TreatmentDataSourceInterface {
    public function get_treatments(): array;
    public function get_treatment($id): ?array;
    public function get_treatments_by_category($category): array;
    public function is_available(): bool;
}
```

### Data Source Implementations

#### 1. HardcodedTreatmentDataSource
- **Purpose**: Provides static treatment data for immediate use
- **Use Case**: Development, testing, or when CMS data is not available
- **Data Format**: Simulates database structure with complete metadata

#### 2. CMSTreatmentDataSource
- **Purpose**: Retrieves data from WordPress custom post types
- **Use Case**: Production environment with admin-managed content
- **Requirements**: Treatment custom post type must be registered

### Main Adapter Class
```php
class TreatmentsAdapter {
    private $data_source;
    
    public function __construct() {
        $this->initialize_data_source();
    }
}
```

## Data Source Priority

The adapter automatically selects the best available data source:

1. **CMS Data Source** (if available and has data)
2. **Hardcoded Data Source** (fallback)

## Usage Examples

### Basic Usage
```php
// Create adapter instance
$adapter = new TreatmentsAdapter();

// Get all treatments
$treatments = $adapter->get_treatments();

// Get single treatment
$treatment = $adapter->get_treatment('injectable-artistry');

// Get treatments by category
$injectable_treatments = $adapter->get_treatments_by_category('injectable');

// Check current data source
$source_type = $adapter->get_data_source_type();
```

### Using Through TreatmentsDataStore
```php
// Recommended approach - uses adapter internally
$treatments = TreatmentsDataStore::get_treatments();
$treatment = TreatmentsDataStore::get_treatment('injectable-artistry');
$source = TreatmentsDataStore::get_data_source_type();
```

## Data Structure

Each treatment follows this standardized format:

```php
[
    'id' => 'injectable-artistry',
    'title' => 'Injectable Artistry',
    'icon' => '💉',
    'description' => 'Treatment description...',
    'benefits' => ['Benefit 1', 'Benefit 2'],
    'duration' => '30-60 minutes',
    'cta' => [
        'primary' => [
            'text' => 'Book Now',
            'url' => '/book-appointment/?treatment=injectable-artistry'
        ],
        'secondary' => [
            'text' => 'Learn More',
            'url' => '/treatments/injectable-artistry/'
        ]
    ],
    'image' => [
        'src' => 'path/to/image.jpg',
        'alt' => 'Alt text'
    ],
    'schema' => [
        '@type' => 'MedicalProcedure',
        'name' => 'Injectable Artistry',
        'description' => 'Description...',
        'bodyLocation' => 'Face',
        'procedureType' => 'Non-surgical procedure'
    ]
]
```

## Switching to CMS Data

To switch from hardcoded to CMS data:

1. **Add Treatment Posts**: Create treatment posts in WordPress admin
2. **Automatic Detection**: The adapter will automatically detect and use CMS data
3. **Verification**: Check `get_data_source_type()` returns `CMSTreatmentDataSource`

### Adding CMS Data Example
```php
// Create a new treatment post
$treatment_post = [
    'post_title' => 'New Treatment',
    'post_content' => 'Treatment description',
    'post_type' => 'treatment',
    'post_status' => 'publish',
    'meta_input' => [
        '_treatment_icon' => '✨',
        '_treatment_duration' => '45 minutes',
        '_treatment_benefits' => ['Benefit 1', 'Benefit 2']
    ]
];

wp_insert_post($treatment_post);
```

## Current Hardcoded Treatments

The system includes 9 pre-configured treatments:

1. **Injectable Artistry** (`injectable-artistry`)
2. **Facial Renaissance** (`facial-renaissance`)
3. **Precision Dermaplanning** (`precision-dermaplanning`)
4. **Regenerative PRP** (`regenerative-prp`)
5. **Wellness Infusions** (`wellness-infusions`)
6. **Artistry Enhancement** (`artistry-enhancement`)
7. **Laser Precision** (`laser-precision`)
8. **Carbon Rejuvenation** (`carbon-rejuvenation`)
9. **Body Sculpting** (`body-sculpting`)

## Benefits of This Pattern

### 1. **Flexibility**
- Easy switching between data sources
- No code changes required in consuming components

### 2. **Maintainability**
- Single point of data access
- Consistent data format across sources

### 3. **Scalability**
- Easy to add new data sources (API, external services)
- Supports caching and performance optimizations

### 4. **Testing**
- Hardcoded data perfect for development/testing
- Predictable data structure

## Performance Considerations

- **Static Caching**: Adapter instances use static variables for performance
- **Lazy Loading**: Data sources are only initialized when needed
- **Query Optimization**: CMS source uses optimized WordPress queries

## Migration Path

### Phase 1: Current (Hardcoded)
```php
// Uses HardcodedTreatmentDataSource
$treatments = TreatmentsDataStore::get_treatments();
```

### Phase 2: CMS Migration
1. Add treatment posts via WordPress admin
2. System automatically switches to CMSTreatmentDataSource
3. No code changes required

### Phase 3: External API (Future)
1. Implement new data source class
2. Add to adapter priority logic
3. Seamless integration

## Testing

Use the included test file to verify the adapter:

```bash
# Navigate to theme directory
cd wp-content/themes/medSpaTheme

# Run test (via browser)
# Visit: your-site.com/wp-content/themes/medSpaTheme/test-adapter.php
```

## Troubleshooting

### Issue: Always using hardcoded data
**Solution**: Ensure treatment posts exist and are published

### Issue: Missing treatment data
**Solution**: Check data source availability with `is_available()`

### Issue: Performance concerns
**Solution**: Implement caching in data source classes

## File Structure

```
inc/data/
├── treatments-adapter.php     # Main adapter implementation
├── treatments.php            # DataStore wrapper (uses adapter)
└── test-adapter.php         # Testing utilities
```

## Future Enhancements

1. **Caching Layer**: Add Redis/Memcached support
2. **API Integration**: External treatment databases
3. **Hybrid Sources**: Combine multiple data sources
4. **Real-time Sync**: Live data synchronization
5. **Performance Metrics**: Data source performance tracking

This adapter pattern ensures your treatment data system is flexible, maintainable, and ready for future growth while providing immediate functionality with hardcoded data. 
