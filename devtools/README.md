# DevTools - DevKinsta WordPress Development Tools

## Overview

This directory contains specialized development tools created by the **BUG-RESOLVER-001** agent for efficient DevKinsta/WordPress debugging and development workflow enhancement.

## Directory Structure

```
devtools/
├── README.md                    # This file
├── wp-admin-tools/             # WordPress admin panel integrated tools
├── standalone-scripts/         # Independent PHP diagnostic scripts
├── automated-checks/           # Automated monitoring and health tools
└── dev-utilities/             # Development workflow utilities
```

## Tool Categories

### 1. WordPress Admin Tools (`wp-admin-tools/`)
- **Purpose:** Tools integrated with WordPress admin dashboard
- **Access:** Via WordPress admin panel at `/wp-admin/`
- **Examples:**
  - Page creation validators
  - Theme template debuggers
  - Permalink structure fixers
  - Database connection testers

### 2. Standalone Scripts (`standalone-scripts/`)
- **Purpose:** Independent PHP scripts for environment testing
- **Access:** Direct execution via command line or browser
- **Examples:**
  - DevKinsta health checkers
  - WordPress installation validators
  - Theme compatibility testers
  - Database query analyzers

### 3. Automated Checks (`automated-checks/`)
- **Purpose:** Automated monitoring and proactive issue detection
- **Access:** Scheduled execution or triggered monitoring
- **Examples:**
  - Continuous health monitors
  - Performance regression checkers
  - Security vulnerability scanners
  - Code quality analyzers

### 4. Development Utilities (`dev-utilities/`)
- **Purpose:** Development workflow enhancement tools
- **Access:** Command line or integrated development workflows
- **Examples:**
  - Rapid page creators
  - Sample data installers
  - Development environment resetters
  - Debugging log analyzers

## Usage Guidelines

### Prerequisites
- **DevKinsta:** Must be running and accessible
- **WordPress:** Must be properly installed and configured
- **Theme:** medSpaTheme must be active
- **PHP:** Version 7.4+ recommended

### Running Tools

#### WordPress Admin Tools
1. Access WordPress admin: `http://your-site.local/wp-admin/`
2. Navigate to the appropriate admin section
3. Use integrated debugging panels and validators

#### Standalone Scripts
```bash
# From project root
cd devtools/standalone-scripts/
php script-name.php

# Or via browser
http://your-site.local/wp-content/themes/medSpaTheme/devtools/standalone-scripts/script-name.php
```

#### Automated Checks
```bash
# Run health check
cd devtools/automated-checks/
php health-monitor.php

# Schedule monitoring (if available)
crontab -e
# Add: */5 * * * * cd /path/to/devtools/automated-checks && php continuous-monitor.php
```

## Tool Development Standards

### File Naming Convention
- Use descriptive, hyphenated names
- Include category prefix when helpful
- Example: `theme-template-debugger.php`

### Documentation Requirements
- Each tool MUST include usage instructions
- Include purpose, prerequisites, and examples
- Document expected outputs and error handling

### Error Handling
- All tools should gracefully handle DevKinsta connection issues
- Provide clear error messages and troubleshooting steps
- Include fallback procedures when possible

### DevKinsta Integration
- Test database connectivity before executing database operations
- Verify WordPress installation integrity
- Check theme activation status
- Validate admin panel accessibility

## Created by BUG-RESOLVER-001

These tools are automatically generated and maintained by the **BUG-RESOLVER-001** agent as part of the bug resolution workflow. Each tool is created to address specific debugging needs and enhance the DevKinsta development experience.

### Tool Creation Process
1. **Issue Analysis:** BUG-RESOLVER-001 identifies debugging needs
2. **Tool Design:** Custom tools designed for specific problems
3. **Implementation:** CODE-GEN-001 generates functional tools
4. **Deployment:** Tools deployed to appropriate devtools subdirectories
5. **Documentation:** Comprehensive usage documentation created
6. **Validation:** Tools tested for functionality and reliability

## Support and Maintenance

For issues with devtools:
1. Check DevKinsta service status
2. Verify WordPress admin accessibility
3. Review tool-specific documentation
4. Contact development team if issues persist

## Version Information

- **Created by:** BUG-RESOLVER-001 Agent
- **Workflow:** BUG-RESOLUTION-WF-001
- **Project:** medSpaTheme DevKinsta Development
- **Last Updated:** {CURRENT_DATE} 
