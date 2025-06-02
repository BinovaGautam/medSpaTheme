# Standalone Scripts

## Overview

Independent PHP scripts for DevKinsta environment testing and WordPress diagnostics that can be executed outside of the WordPress admin interface.

## Purpose

These scripts provide direct access to debugging functionality without requiring WordPress admin access, making them ideal for situations where:
- WordPress admin is inaccessible
- Database connection issues exist
- Environment validation is needed
- Command-line debugging is preferred

## Access Methods

### Command Line Execution
```bash
# From project root
cd devtools/standalone-scripts/
php script-name.php
```

### Browser Access
```
http://your-site.local/wp-content/themes/medSpaTheme/devtools/standalone-scripts/script-name.php
```

## Tool Categories

### Environment Validation
- DevKinsta service status checkers
- PHP environment analyzers
- Server configuration validators

### WordPress Diagnostics
- WordPress installation validators
- Core file integrity checkers
- Configuration analyzers

### Database Tools
- Connection testers
- Query performance analyzers
- Data integrity validators

### Theme Analysis
- Theme compatibility testers
- Template validation tools
- Asset dependency analyzers

## Script Development Standards

### Error Handling
```php
// Example error handling pattern
try {
    // Script logic here
    echo "✅ Operation successful\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Please check DevKinsta status and try again.\n";
}
```

### Output Formatting
- Use clear status indicators (✅ ❌ ⚠️)
- Provide actionable error messages
- Include troubleshooting suggestions
- Format output for both CLI and browser

### WordPress Loading
```php
// Safe WordPress loading pattern
$wp_load_paths = ['../../../wp-load.php', '../../wp-load.php'];
$wp_loaded = false;

foreach ($wp_load_paths as $path) {
    if (file_exists($path)) {
        try {
            require_once($path);
            $wp_loaded = true;
            break;
        } catch (Exception $e) {
            continue;
        }
    }
}

if (!$wp_loaded) {
    echo "❌ WordPress not accessible. Check DevKinsta status.\n";
    exit;
}
```

## Security Considerations

- Scripts should include basic security checks
- Avoid exposing sensitive information
- Implement rate limiting where appropriate
- Validate all inputs thoroughly

## Created by BUG-RESOLVER-001

Generated as part of the bug resolution workflow to provide independent diagnostic capabilities for DevKinsta WordPress development. 
