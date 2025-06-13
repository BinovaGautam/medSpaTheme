# Treatment Data Adapter Pattern Implementation

## Overview
This document outlines the adapter pattern implementation for flexible treatment data sources in the MedSpa theme.

## Architecture

### Interface
- `TreatmentDataSourceInterface` - Defines contract for data sources

### Implementations
- `HardcodedTreatmentDataSource` - Static PHP array data
- `CMSTreatmentDataSource` - WordPress database integration

### Adapter
- `TreatmentsAdapter` - Main orchestrator with automatic source selection

## Usage
```php
$adapter = new TreatmentsAdapter();
$treatments = $adapter->getAllTreatments();
```

## Data Structure
Each treatment contains:
- id, title, description, icon
- benefits array
- duration, category
- metadata for CMS integration

## Benefits
- Flexible data sources
- Easy migration path
- Maintainable architecture
- Future-proof design 
