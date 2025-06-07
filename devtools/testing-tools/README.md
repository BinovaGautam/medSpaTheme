# Testing Tools

## Overview

This directory contains comprehensive testing utilities for the Medical Spa Theme, migrated from the theme root directory as part of the **DEVTOOLS-ORG-WF-001** workflow. All tools have been updated for their new location and maintain full functionality.

## Migrated Tools

### 🧪 Visual Customizer Testing

#### test-surgical-fix.html
**Purpose**: Test the PVC-005 Live Preview System surgical fix implementation

**Features**:
- Real-time color palette testing
- Visual feedback for surgical fix mapping
- Simulated theme structure testing
- Immediate visual change validation

**Usage**:
```
http://your-site.local/wp-content/themes/medSpaTheme/devtools/testing-tools/test-surgical-fix.html
```

**What it tests**:
- Live preview system functionality
- Color variable mapping accuracy
- Gradient generation and application
- Theme element responsiveness to palette changes

#### test-visual-customizer-debug.php
**Purpose**: Debug visual customizer functionality and configuration

**Features**:
- Visual customizer system status validation
- Color palette configuration testing
- WordPress integration verification
- Admin interface debugging

**Usage**:
```
http://your-site.local/wp-content/themes/medSpaTheme/devtools/testing-tools/test-visual-customizer-debug.php
```

**Requirements**:
- WordPress environment active
- Visual customizer system loaded
- Admin user capabilities

#### debug-visual-customizer.php
**Purpose**: Admin panel debug tool for visual customizer components

**Features**:
- Class existence validation
- Instance creation testing
- Configuration file verification
- WordPress admin menu integration testing

**Usage**:
```
http://your-site.local/wp-content/themes/medSpaTheme/devtools/testing-tools/debug-visual-customizer.php
```

**Path Updates Applied**:
- WordPress config path updated to: `../../../../wp-config.php`
- Relative path adjustments for new location

### 🏗️ Component Testing

#### test-luxury-footer.php
**Purpose**: Test luxury footer implementation and styling

**Features**:
- File structure validation
- WordPress integration testing
- Function existence verification
- Asset enqueuing validation
- CSS/JS loading verification

**Usage**:
```
http://your-site.local/wp-content/themes/medSpaTheme/devtools/testing-tools/test-luxury-footer.php
```

**Test Coverage**:
- Footer template file existence
- CSS style compilation
- JavaScript functionality
- WordPress hook integration
- Customizer integration

#### debug-routing.php
**Purpose**: WordPress routing debug utility for theme pages

**Features**:
- URL routing analysis
- Page template validation
- Query variable inspection
- Rewrite rule verification

**Usage**:
```
http://your-site.local/wp-content/themes/medSpaTheme/devtools/testing-tools/debug-routing.php
```

**Debugging Capabilities**:
- Current page analysis
- Template hierarchy inspection
- Query string parsing
- WordPress routing diagnostics

## Common Usage Patterns

### Browser-Based Testing
All tools support direct browser access for visual testing and debugging:

```
# Base URL pattern
http://your-site.local/wp-content/themes/medSpaTheme/devtools/testing-tools/[tool-name]

# Examples
http://your-site.local/wp-content/themes/medSpaTheme/devtools/testing-tools/test-surgical-fix.html
http://your-site.local/wp-content/themes/medSpaTheme/devtools/testing-tools/debug-routing.php
```

### WordPress Integration Testing
PHP-based tools integrate with WordPress for comprehensive testing:

- **Admin Context**: Tools check admin capabilities and WordPress state
- **Theme Integration**: Validate theme function and class availability
- **Database Access**: Test database connections and query functionality
- **Hook Verification**: Confirm WordPress action and filter registration

### Development Environment Requirements
- **DevKinsta**: Active development environment
- **WordPress**: Functional WordPress installation
- **PHP**: PHP 7.4+ with error reporting enabled
- **Browser**: Modern browser for HTML testing tools

## Migration Details

### Path Updates Applied
All migrated tools have been updated for their new location:

- **WordPress Configuration**: Updated relative paths to wp-config.php
- **Asset References**: Maintained proper asset linking
- **Documentation Links**: Updated internal references

### Functionality Preservation
- **Full Feature Set**: All original functionality preserved
- **WordPress Integration**: Admin and frontend integration maintained
- **Error Handling**: Graceful error handling for missing dependencies
- **Security**: Development environment restrictions maintained

## Testing Workflow

### 1. Environment Validation
Before using testing tools, verify:
- DevKinsta services are running
- WordPress is accessible
- Theme is active and functional

### 2. Tool Selection
Choose appropriate tool based on testing needs:
- **Visual System**: Use visual customizer testing tools
- **Component Validation**: Use component-specific testing tools
- **Integration Testing**: Use WordPress integration tools

### 3. Result Analysis
Testing tools provide comprehensive feedback:
- **Status Indicators**: Visual pass/fail indicators
- **Detailed Logs**: Comprehensive diagnostic information
- **Actionable Feedback**: Clear next steps for issue resolution

### 4. Issue Resolution
Use testing results to identify and resolve issues:
- **Configuration Problems**: Update theme configuration
- **Integration Issues**: Verify WordPress setup
- **Asset Problems**: Check file permissions and paths

## Integration with Other DevTools

### WordPress Admin Tools
Testing tools integrate with admin interface tools:
- **Diagnostic Tools**: Cross-reference with admin diagnostic results
- **Fix Utilities**: Use testing validation to confirm fixes
- **Loading Systems**: Verify tool loading and initialization

### Standalone Scripts
Command-line scripts complement browser-based testing:
- **Comprehensive Diagnostics**: CLI tools provide detailed system analysis
- **Automated Testing**: Scripts enable automated testing workflows
- **Environment Validation**: CLI tools verify development environment

### Quality Assurance
Testing tools support QA processes:
- **Contrast Validation**: Verify accessibility compliance
- **Performance Testing**: Validate loading and execution performance
- **Regression Testing**: Ensure changes don't break existing functionality

## Troubleshooting

### Common Issues

#### Tool Not Loading
- **Symptom**: Tool page returns 404 or access denied
- **Solution**: Verify file permissions and DevKinsta status
- **Check**: Confirm tool exists at new location

#### WordPress Integration Errors
- **Symptom**: PHP errors or missing WordPress functions
- **Solution**: Verify WordPress configuration path
- **Check**: Confirm WordPress is properly loaded

#### Visual Testing Issues
- **Symptom**: HTML tools display incorrectly
- **Solution**: Check browser compatibility and clear cache
- **Check**: Verify asset loading and CSS compilation

### Support Resources
- **Main DevTools Index**: `../index.html`
- **Organization Documentation**: `../DEVTOOLS-ORGANIZATION.md`
- **WordPress Admin Tools**: `../wp-admin-tools/`
- **Development Utilities**: `../dev-utilities/`

---

**Migrated by DEVTOOLS-ORG-WF-001** | All tools tested and validated for new location 
