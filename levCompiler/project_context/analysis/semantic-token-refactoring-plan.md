# SEMANTIC TOKEN REFACTORING PLAN: --color-charcoal Violations

**Plan ID**: SEMANTIC-TOKEN-REFACTOR-{CURRENT_TIMESTAMP}  
**Agent**: CODE-GEN-001  
**Workflow**: DESIGN-SYSTEM-COMPLIANCE-WF-001  
**Priority**: CRITICAL  
**Status**: ACTIVE EXECUTION  

## 🚨 VIOLATION SUMMARY

**Target**: `--color-charcoal` non-semantic token usage  
**Scope**: 100+ instances across medical-spa-theme.css (14,507 lines)  
**Violation Type**: CRITICAL - Non-semantic naming + hardcoded values  

## 📊 REFACTORING STRATEGY

### Phase 1: Token Definition Cleanup
**Target Lines**: 55, 889, 2568, 4247, 5926, 7605, 8708, 9542  
**Action**: Replace hardcoded `#000000` definitions with semantic references

### Phase 2: Usage Context Analysis & Replacement
**Text Color Usage** (90+ instances):
- `color: var(--color-charcoal);` → `color: var(--color-text-primary);`

**Border Color Usage** (20+ instances):
- `border: 2px solid var(--color-charcoal);` → `border: 2px solid var(--color-text-primary);`

### Phase 3: Semantic Token Mapping
```css
/* BEFORE (Non-semantic) */
--color-charcoal: #000000;
color: var(--color-charcoal);

/* AFTER (Semantic) */
/* Use existing semantic tokens */
color: var(--color-text-primary);    /* Primary text content */
color: var(--color-text-secondary);  /* Secondary text content */
border-color: var(--color-text-primary); /* High contrast borders */
```

## 🎯 EXECUTION PLAN

1. **Eliminate hardcoded definitions** - Replace `#000000` with semantic references
2. **Context-aware replacement** - Map usage to appropriate semantic tokens
3. **Validation** - Ensure visual consistency maintained
4. **Quality gate** - Verify 100% semantic token compliance

## 📋 SUCCESS CRITERIA

- ✅ Zero `--color-charcoal` instances remaining
- ✅ Zero hardcoded color values
- ✅ 100% semantic token usage
- ✅ Visual design integrity maintained
- ✅ WCAG contrast compliance preserved 
