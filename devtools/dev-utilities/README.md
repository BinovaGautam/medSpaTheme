# Development Utilities

## Overview

Development workflow enhancement utilities designed to streamline DevKinsta WordPress development processes and increase productivity.

## Purpose

These utilities automate common development tasks and provide shortcuts for:
- Rapid content creation
- Development environment management
- Code generation and scaffolding
- Data management and seeding
- Debugging and analysis

## Access Methods

### Command Line
```bash
cd devtools/dev-utilities/
php utility-name.php [arguments]
```

### WordPress Integration
- Some utilities integrate with WordPress admin
- Available through custom admin pages
- Accessible via WP-CLI commands

## Tool Categories

### Content Creation
- Rapid page creators
- Post type generators
- Sample content installers
- Menu builders

### Development Environment
- Environment resetters
- Configuration managers
- Cache clearers
- Database cleaners

### Code Generation
- Template generators
- Component scaffolders
- Hook and filter generators
- Custom post type creators

### Data Management
- Sample data installers
- Database seeders
- Import/export tools
- Data migration utilities

### Analysis Tools
- Debugging log analyzers
- Performance profilers
- Code complexity analyzers
- Dependency mappers

## Utility Development Standards

### Command Line Interface
```php
// Example CLI argument handling
if (defined('ABSPATH')) {
    // Running within WordPress
    $context = 'wordpress';
} else {
    // Running standalone
    $context = 'cli';
    $args = $_SERVER['argv'] ?? [];
}

// Handle arguments appropriately for context
```

### Progress Indication
```php
// Example progress indication
function show_progress($current, $total, $message = '') {
    $percent = round(($current / $total) * 100);
    $bar = str_repeat('█', $percent / 5) . str_repeat('░', 20 - ($percent / 5));
    echo "\r[{$bar}] {$percent}% {$message}";
    if ($current >= $total) echo "\n";
}
```

### Configuration Management
```php
// Example configuration handling
$config = [
    'sample_posts' => 10,
    'sample_pages' => 5,
    'sample_treatments' => 15,
    'include_media' => true,
    'reset_existing' => false
];

// Allow override via command line or file
if (file_exists('dev-config.json')) {
    $user_config = json_decode(file_get_contents('dev-config.json'), true);
    $config = array_merge($config, $user_config);
}
```

## Utility Categories Detail

### Rapid Page Creator
```bash
# Create multiple pages quickly
php rapid-page-creator.php --count=5 --template=default
php rapid-page-creator.php --from-list=page-list.txt
```

### Sample Data Installer
```bash
# Install comprehensive sample data
php sample-data-installer.php --type=all
php sample-data-installer.php --type=treatments --count=20
```

### Environment Resetter
```bash
# Reset development environment
php environment-resetter.php --full-reset
php environment-resetter.php --preserve-content
```

### Log Analyzer
```bash
# Analyze debugging logs
php log-analyzer.php --file=debug.log --filter=errors
php log-analyzer.php --last=24h --summary
```

## Integration Patterns

### WordPress Admin Integration
```php
// Add utility to admin menu
add_action('admin_menu', 'dev_utilities_menu');
function dev_utilities_menu() {
    add_submenu_page(
        'tools.php',
        'Dev Utilities',
        'Dev Utilities',
        'manage_options',
        'dev-utilities',
        'dev_utilities_page'
    );
}
```

### AJAX Support
```php
// AJAX handler for real-time feedback
add_action('wp_ajax_run_dev_utility', 'handle_dev_utility_ajax');
function handle_dev_utility_ajax() {
    $utility = sanitize_text_field($_POST['utility']);
    $result = run_utility($utility);
    wp_send_json_success($result);
}
```

## Safety Measures

### Backup Creation
- Always create backups before destructive operations
- Provide rollback capabilities
- Warn users about data loss potential

### Environment Detection
```php
// Detect environment to prevent production accidents
function is_development_environment() {
    $dev_indicators = [
        'localhost',
        '.local',
        'dev.',
        'staging.',
        '.test'
    ];
    
    $host = $_SERVER['HTTP_HOST'] ?? '';
    foreach ($dev_indicators as $indicator) {
        if (strpos($host, $indicator) !== false) {
            return true;
        }
    }
    return false;
}
```

### Confirmation Prompts
```php
// Require confirmation for destructive operations
function confirm_action($message) {
    echo "{$message} Are you sure? (yes/no): ";
    $handle = fopen("php://stdin", "r");
    $response = trim(fgets($handle));
    fclose($handle);
    return strtolower($response) === 'yes';
}
```

## Configuration Files

### dev-config.json Example
```json
{
  "environment": "development",
  "sample_data": {
    "posts": 10,
    "pages": 5,
    "treatments": 15,
    "staff": 8
  },
  "backup": {
    "auto_backup": true,
    "backup_location": "/backups/"
  },
  "logging": {
    "level": "debug",
    "file": "dev-utilities.log"
  }
}
```

## Created by BUG-RESOLVER-001

Generated as part of the bug resolution workflow to enhance DevKinsta WordPress development productivity and streamline common development tasks. 
