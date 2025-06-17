# SYSTEM CONVENTIONS - Single Source of Truth

**Version:** 1.0.0  
**Last Updated:** {CURRENT_DATE}  
**Status:** Active  
**Scope:** All project development, documentation, and workflows  

## 📋 OVERVIEW

This document serves as the **single source of truth** for all conventions, standards, and practices followed across the entire project. All agents, workflows, and developers must adhere to these conventions without exception.

## 🏗️ FILE ORGANIZATION CONVENTIONS

### Project Structure Standards
```
levCompiler/
├── .control/
│   ├── agents/                 # Agent configurations
│   ├── workflows/             # Workflow definitions
│   └── constants/             # System constants
├── project_context/
│   ├── documentation/         # All documentation
│   ├── designs/              # Design specifications
│   ├── analysis/             # Project analysis
│   ├── tasks/                # Task management
│   ├── plans/                # Project plans
│   └── [other contexts]/     # Additional contexts
├── tools/
│   ├── {tool-name}/          # Individual tools
│   │   ├── services/         # Tool services
│   │   ├── cli/              # CLI interfaces
│   │   ├── config/           # Tool configuration
│   │   └── utils/            # Tool utilities
│   └── shared/               # Shared utilities
assets/
├── css/                      # Theme stylesheets
├── js/                       # JavaScript files
└── images/                   # Image assets
```

### File Location Rules
- **MANDATORY:** All files must go to designated project_context locations
- **FORBIDDEN:** Standalone folders outside established structure
- **REQUIRED:** All files must be registered in appropriate index.json files
- **CRITICAL:** No tools outside levCompiler/tools/ directory

## 🔤 NAMING CONVENTIONS

### File Naming Standards
```
Design Files:        {COMPONENT}_{TYPE}_DESIGN.md
Agent Files:         {agent_name}_agent.json
Workflow Files:      {workflow_name}_workflow.json
Task Files:          sprint-{number}-{descriptive-name}.md
Documentation:       {TOPIC}_DOCUMENTATION.md
CSS Files:           semantic-{purpose}.css
```

### Variable Naming Standards
```
CSS Variables:       --{semantic-name}-{property}
JS Variables:        camelCase
PHP Variables:       $snake_case
Agent IDs:           {PURPOSE}-{TYPE}-{NUMBER}
Workflow IDs:        {PURPOSE}-{TYPE}-WF-{NUMBER}
```

### Branch Naming Standards
```
Feature Branches:    feature/{feature-name}
Bug Fix Branches:    fix/{bug-description}
Release Branches:    release/{version}
Hotfix Branches:     hotfix/{issue-description}
```

## 🎨 DESIGN SYSTEM CONVENTIONS

### Semantic Tokenization Requirements
- **ZERO TOLERANCE:** No hardcoded values in any design output
- **MANDATORY:** All colors must use semantic tokens (e.g., `var(--color-primary)`)
- **REQUIRED:** All typography must use semantic tokens (e.g., `var(--text-xl)`)
- **ENFORCED:** All spacing must use semantic tokens (e.g., `var(--space-md)`)

### Allowed Design References
```
✅ ALLOWED:
- var(--color-primary)
- var(--font-family-primary)
- var(--space-lg)
- 'primary brand color'
- 'larger than base'

❌ FORBIDDEN:
- #87A96B
- 'Playfair Display'
- 24px
- rgba(135, 169, 107, 0.8)
```

### CSS Architecture Standards
- **MAXIMUM:** 2 CSS files allowed (semantic-tokens.css, semantic-components.css)
- **SOURCE:** All CSS generated from PROFESSIONAL_VISUAL_CUSTOMIZER_DESIGN.md
- **SYSTEM:** semantic-color-system.js as single source of truth
- **CLEANUP:** Automatic deletion of old CSS files when new config applied

## 💻 CODING CONVENTIONS

### CSS Standards
```css
/* Semantic token usage */
.component {
  color: var(--color-text-primary);
  font-size: var(--text-base);
  padding: var(--space-md);
  border-radius: var(--radius-sm);
}

/* Component naming */
.{component-name}__{element} {
  /* styles */
}

.{component-name}--{modifier} {
  /* styles */
}
```

### JavaScript Standards
```javascript
// Semantic color system integration
const colors = {
  primary: 'var(--color-primary)',
  secondary: 'var(--color-secondary)',
  accent: 'var(--color-accent)'
};

// Function naming
function getSemanticColor(tokenName) {
  return `var(--color-${tokenName})`;
}
```

### PHP Standards
```php
// WordPress theme integration
function enqueue_semantic_styles() {
    wp_enqueue_style(
        'semantic-tokens',
        get_template_directory_uri() . '/assets/css/semantic-tokens.css',
        array(),
        wp_get_theme()->get('Version')
    );
}
```

## 🔄 WORKFLOW CONVENTIONS

### Agent Usage Standards
- **Design Tasks:** MUST use UI-UX-DESIGN-001 agent
- **Code Generation:** MUST use CODE-GEN-001 agent
- **Task Management:** MUST use TASK-PLANNER-001 agent
- **Version Control:** MUST use VERSION-TRACK-001 agent
- **Workflow Creation:** MUST use WORKFLOW-CREATE-001 agent

### Quality Gate Standards
```
Completion Criteria:
✅ All deliverables created and verified
✅ Proper agent delegation documented
✅ Quality gates passed with evidence
✅ VERSION-TRACK-001 handoff completed
✅ Fundamentals.json compliance verified
```

### Workflow Status Reporting
```
Format: 🔄 WORKFLOW: [workflow-name] | STAGE: [current-stage] | AGENT: [agent-id]
Example: 🔄 WORKFLOW: CSS-SEMANTIC-CLEANUP-WF-001 | STAGE: generation | AGENT: CSS-SEMANTIC-GEN-001
```

## 📝 DOCUMENTATION CONVENTIONS

### Document Structure Standards
```markdown
# DOCUMENT TITLE

**Version:** X.X.X
**Last Updated:** {CURRENT_DATE}
**Status:** [Active|Draft|Archived]
**Scope:** [Brief description]

## 📋 OVERVIEW
[Executive summary]

## 🎯 OBJECTIVES
[Primary, secondary, tertiary goals]

## 📊 IMPLEMENTATION
[Detailed implementation details]

## ✅ COMPLIANCE
[Compliance requirements and validation]
```

### Metadata Requirements
- **Version:** Semantic versioning (X.X.X)
- **Status:** Active, Draft, Archived, Deprecated
- **Scope:** Clear scope definition
- **Update Tracking:** {CURRENT_DATE} placeholder usage

## 🔐 VERSION CONTROL CONVENTIONS

### Commit Message Standards
```
Format: {type}({scope}): {description}

Types:
- feat: New feature
- fix: Bug fix
- docs: Documentation
- style: Code style
- refactor: Code refactoring
- test: Testing
- chore: Maintenance

Examples:
- feat(treatments): add semantic token system
- fix(navigation): resolve mobile menu issue
- docs: update system conventions
```

### Branch Protection Rules
- **main:** Production-ready code, protected
- **develop:** Integration branch for features
- **feature/*:** Feature development branches
- **release/*:** Release preparation branches
- **hotfix/*:** Emergency production fixes

### Semantic Versioning
```
MAJOR.MINOR.PATCH
- MAJOR: Breaking changes
- MINOR: New features (backwards compatible)
- PATCH: Bug fixes
```

## 🚨 VIOLATION HANDLING

### Critical Violations
1. **Hardcoded Values in Design:** Immediate regeneration required
2. **Wrong File Location:** Move to correct location immediately
3. **Missing Agent Usage:** Use designated agent
4. **Version Tracking Bypass:** Invoke VERSION-TRACK-001 mandatory

### Enforcement Mechanisms
- **Automated Scanning:** Continuous monitoring for violations
- **Quality Gates:** Prevent progression with violations
- **Human Escalation:** Critical violations require human attention
- **Rollback Procedures:** Automatic rollback on critical failures

## 🔄 TEMPORAL CONVENTIONS

### Temporal Limitations
- **FORBIDDEN:** Specific dates (e.g., 2025-01-15)
- **FORBIDDEN:** Specific times (e.g., 14:30:00)
- **FORBIDDEN:** Timestamps (e.g., 2025-01-15T14:30:00Z)

### Temporal Placeholders
```
Date: {CURRENT_DATE}
Time: {CURRENT_TIME}
Timestamp: {CURRENT_TIMESTAMP}
Relative: {DATE_RELATIVE: +7_days}
Duration: {DURATION: 2_weeks}
```

## 📊 QUALITY ASSURANCE CONVENTIONS

### Testing Standards
- **Unit Tests:** Required for all new functions
- **Integration Tests:** Required for component interactions
- **Visual Tests:** Required for design system changes
- **Performance Tests:** Required for optimization claims

### Accessibility Standards
- **WCAG 2.1 AA:** Minimum compliance level
- **Touch Targets:** Minimum 44px for interactive elements
- **Color Contrast:** Minimum 4.5:1 for normal text
- **Focus Indicators:** Required for all interactive elements

## 🎯 PERFORMANCE CONVENTIONS

### CSS Performance
```css
/* Efficient selectors */
.component { /* Good */ }
.component .element { /* Good */ }
div > div > div { /* Avoid */ }

/* Semantic tokens for consistency */
.component {
  color: var(--color-text-primary); /* Good */
  color: #333333; /* Forbidden */
}
```

### JavaScript Performance
```javascript
// Semantic token access
const getPrimaryColor = () => 
  getComputedStyle(document.documentElement)
    .getPropertyValue('--color-primary');

// Avoid hardcoded values
const primaryColor = '#1B365D'; // Forbidden
```

## 🔧 TOOL CONVENTIONS

### Tool Organization
```
levCompiler/tools/
├── {tool-name}/
│   ├── package.json          # Tool-specific dependencies
│   ├── services/             # Core tool services
│   ├── cli/                  # Command line interfaces
│   ├── config/               # Tool configuration
│   ├── utils/                # Utility functions
│   └── README.md             # Tool documentation
├── shared/                   # Shared utilities
├── temp/                     # Temporary files
├── logs/                     # Tool logs
└── tools-registry.json       # Tool registry
```

### Tool Registration Requirements
- **Registry:** All tools must be in tools-registry.json
- **Documentation:** README.md required for each tool
- **Dependencies:** Isolated in tool-specific package.json
- **Structure:** Must follow standard directory structure

## 📈 MONITORING CONVENTIONS

### Status Reporting
```
🔄 WORKFLOW: [workflow-name] | STAGE: [stage] | AGENT: [agent-id]
📊 PROGRESS: [stage] ([X]/[Y]) | STATUS: [status] | ETA: [estimate]
🤖 AGENT: [agent-id] | ROLE: [role] | TASK: [task]
📁 FILE OP: [operation] | FILE: [filename] | LOCATION: [path]
✅ COMPLETION → 🔄 VERSION-TRACK-001 | CHANGE: [description]
```

### Escalation Procedures
1. **Automated Detection:** System detects violations
2. **Immediate Halt:** Operations stopped on critical violations
3. **Human Notification:** Escalation to human supervisor
4. **Resolution Required:** Must fix before proceeding
5. **Prevention Update:** Strengthen safeguards

## 🔒 SECURITY CONVENTIONS

### Code Security
- **Input Validation:** All user inputs must be validated
- **Output Sanitization:** All outputs must be sanitized
- **Authentication:** Required for admin functions
- **Authorization:** Role-based access control

### Version Control Security
- **Protected Branches:** Main and develop branches protected
- **Signed Commits:** Required for production releases
- **Access Control:** Repository access managed properly
- **Audit Trail:** All changes tracked with proper attribution

## 📋 COMPLIANCE VERIFICATION

### Automated Checks
- **File Organization:** Scan for files in wrong locations
- **Naming Conventions:** Validate file and variable names
- **Semantic Tokens:** Scan for hardcoded values
- **Agent Usage:** Verify proper agent delegation
- **Version Tracking:** Ensure VERSION-TRACK-001 involvement

### Manual Reviews
- **Design System:** Visual review of design consistency
- **Code Quality:** Peer review of code changes
- **Documentation:** Review of documentation completeness
- **Workflow Compliance:** Audit of workflow execution

## 🔄 MAINTENANCE CONVENTIONS

### Regular Maintenance Tasks
- **Weekly:** Update dependencies and security patches
- **Monthly:** Review and update conventions document
- **Quarterly:** Comprehensive system audit
- **Annually:** Major convention review and updates

### Version Control Maintenance
- **Daily:** Commit active work with proper messages
- **Weekly:** Clean up merged branches
- **Monthly:** Review and update .gitignore
- **Quarterly:** Audit repository health

## 📞 ESCALATION PROCEDURES

### Violation Escalation
1. **Level 1:** Automated correction attempt
2. **Level 2:** Agent notification and retry
3. **Level 3:** Human supervisor notification
4. **Level 4:** System halt and manual intervention

### Human Interaction Points
- **Missing Agent:** Human approval for agent creation
- **Missing Workflow:** Human approval for workflow creation
- **Quality Gate Failure:** Human review required
- **Fundamental Violation:** Immediate human attention

---

## 📚 REFERENCES

- **fundamentals.json:** Core system requirements
- **version_tracking_agent.json:** Version control procedures
- **PROFESSIONAL_VISUAL_CUSTOMIZER_DESIGN.md:** Design system source
- **semantic-color-system.js:** Token system implementation

---

**IMPORTANT:** This document is the authoritative source for all conventions. Any conflicts between this document and other sources should be resolved in favor of this document. All updates to conventions must be reflected here first, then propagated to other system components.

**VERSION CONTROL:** All changes to this document must be committed through VERSION-TRACK-001 agent with proper semantic versioning and change documentation. 
