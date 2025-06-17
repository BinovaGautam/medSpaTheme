# COMPREHENSIVE SEMANTIC TOKENIZATION PLAN
## DESIGN-SYSTEM-COMPLIANCE-WF-001 | CODE-GEN-001

### 🎯 **OBJECTIVE**
Replace ALL 51 hardcoded hex color values with semantic design tokens to achieve 100% fundamentals.json compliance.

### 📊 **VIOLATION INVENTORY**

#### **ROOT TOKEN DEFINITIONS** (Lines 26-47)
```css
/* CURRENT VIOLATIONS */
--color-primary-teal: #16a085;      → --color-primary-teal: var(--color-brand-primary);
--color-primary-blue: #3498db;      → --color-primary-blue: var(--color-brand-secondary);
--color-primary-navy: #2c3e50;      → --color-primary-navy: var(--color-brand-dark);
--color-accent-coral: #e74c3c;      → --color-accent-coral: var(--color-accent-primary);

--color-secondary-sage: #95a5a6;    → --color-secondary-sage: var(--color-neutral-medium);
--color-secondary-lavender: #9b59b6; → --color-secondary-lavender: var(--color-accent-secondary);
--color-secondary-mint: #1abc9c;    → --color-secondary-mint: var(--color-accent-success);
--color-secondary-peach: #f39c12;   → --color-secondary-peach: var(--color-accent-warning);

--color-neutral-white: #ffffff;     → --color-neutral-white: var(--color-surface-primary);
--color-neutral-light: #ecf0f1;     → --color-neutral-light: var(--color-surface-secondary);
--color-neutral-medium: #bdc3c7;    → --color-neutral-medium: var(--color-border-primary);
--color-neutral-dark: #34495e;      → --color-neutral-dark: var(--color-text-secondary);

--color-accent-success: #27ae60;    → --color-accent-success: var(--color-feedback-success);
--color-accent-warning: #f1c40f;    → --color-accent-warning: var(--color-feedback-warning);
--color-accent-error: #e67e22;      → --color-accent-error: var(--color-feedback-error);
--color-accent-info: #3498db;       → --color-accent-info: var(--color-feedback-info);
```

#### **QUIZ COMPONENT TOKENS** (Lines 135-143)
```css
/* CURRENT VIOLATIONS */
--quiz-primary: #6366f1;            → --quiz-primary: var(--color-interactive-primary);
--quiz-accent: #10b981;             → --quiz-accent: var(--color-interactive-accent);
--quiz-neutral-bg: #f8fafc;         → --quiz-neutral-bg: var(--color-surface-tertiary);
--quiz-text-primary: #1e293b;       → --quiz-text-primary: var(--color-text-primary);
--quiz-progress-bar: #e2e8f0;       → --quiz-progress-bar: var(--color-border-secondary);
--quiz-hover-state: #4f46e5;        → --quiz-hover-state: var(--color-interactive-hover);
--quiz-success: #059669;            → --quiz-success: var(--color-feedback-success);
--quiz-error: #dc2626;              → --quiz-error: var(--color-feedback-error);
--quiz-warning: #d97706;            → --quiz-warning: var(--color-feedback-warning);
```

#### **DUPLICATE DEFINITIONS** (Lines 8679-8796)
- Identical violations repeated in second CSS block
- Same semantic token replacements apply

#### **INLINE HARDCODED VALUE** (Line 14345)
```css
background: #fef2f2;                → background: var(--color-surface-error-light);
```

### 🔄 **SEMANTIC TOKEN HIERARCHY**

#### **FOUNDATION TOKENS** (New semantic base)
```css
/* SEMANTIC FOUNDATION - BRAND */
--color-brand-primary: #16a085;     /* Teal - Primary brand color */
--color-brand-secondary: #3498db;   /* Blue - Secondary brand color */
--color-brand-dark: #2c3e50;        /* Navy - Dark brand variant */

/* SEMANTIC FOUNDATION - SURFACES */
--color-surface-primary: #ffffff;   /* White - Primary surface */
--color-surface-secondary: #ecf0f1; /* Light gray - Secondary surface */
--color-surface-tertiary: #f8fafc;  /* Very light - Tertiary surface */
--color-surface-error-light: #fef2f2; /* Light error background */

/* SEMANTIC FOUNDATION - TEXT */
--color-text-primary: #1e293b;      /* Dark - Primary text */
--color-text-secondary: #34495e;    /* Medium dark - Secondary text */

/* SEMANTIC FOUNDATION - BORDERS */
--color-border-primary: #bdc3c7;    /* Medium gray - Primary borders */
--color-border-secondary: #e2e8f0;  /* Light gray - Secondary borders */

/* SEMANTIC FOUNDATION - ACCENTS */
--color-accent-primary: #e74c3c;    /* Coral - Primary accent */
--color-accent-secondary: #9b59b6;  /* Lavender - Secondary accent */

/* SEMANTIC FOUNDATION - FEEDBACK */
--color-feedback-success: #27ae60;  /* Green - Success states */
--color-feedback-warning: #f39c12;  /* Orange - Warning states */
--color-feedback-error: #e67e22;    /* Red-orange - Error states */
--color-feedback-info: #3498db;     /* Blue - Info states */

/* SEMANTIC FOUNDATION - INTERACTIVE */
--color-interactive-primary: #6366f1; /* Indigo - Primary interactive */
--color-interactive-accent: #10b981;  /* Emerald - Accent interactive */
--color-interactive-hover: #4f46e5;   /* Dark indigo - Hover states */
```

### 📋 **IMPLEMENTATION PHASES**

#### **PHASE 1**: Foundation Token Creation
- Add semantic foundation tokens to root
- Maintain visual consistency during transition

#### **PHASE 2**: Root Token Conversion  
- Replace hardcoded values in existing tokens with semantic references
- Update both primary and duplicate definitions

#### **PHASE 3**: Component Token Conversion
- Convert quiz component tokens to semantic references
- Ensure component-specific naming remains clear

#### **PHASE 4**: Inline Value Conversion
- Replace remaining inline hardcoded values
- Add semantic comments for clarity

#### **PHASE 5**: Validation & Compliance
- Verify zero hardcoded hex values remain
- Confirm visual design integrity maintained
- Document semantic token usage

### 🛡️ **QUALITY GATES**
- ✅ Zero hardcoded hex color values
- ✅ All tokens use semantic references
- ✅ Visual design integrity maintained
- ✅ Semantic comments added
- ✅ fundamentals.json compliance achieved

### 🔄 **VERSION TRACKING HANDOFF**
Upon completion: **VERSION-TRACK-001** | CHANGE: Complete semantic tokenization - 51 hardcoded values converted to semantic design tokens 
