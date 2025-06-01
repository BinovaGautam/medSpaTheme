# TASK-UX-QUIZ-003-01: Core Structure Implementation

**Document Type:** Implementation Task  
**Created:** 2025-01-28  
**Status:** ⏳ PENDING  
**Priority:** High  
**Related Plan:** PLAN-UX-QUIZ-002-elegant-redesign.md  
**Phase:** 1 of 4 (Core Structure)  
**Estimated Time:** 1 week  

## Task Overview

Implement the core structural changes for the elegant quiz redesign, removing existing progress bars and implementing the single-step display logic with organic grid layout system.

## Success Criteria

### Phase 1 Deliverables (Week 1):
- ✅ Remove existing progress bar/counter systems
- ✅ Implement single-step display logic  
- ✅ Create organic grid layout system
- ✅ Basic pill selection functionality
- ✅ Step transition animations
- ✅ Back navigation implementation

### Technical Requirements

#### 1. Remove Existing Systems
```javascript
// Remove from premium-hero.js:
- Progress bar indicators
- Step counters
- Multi-step visibility logic
- Complex navigation controls
```

#### 2. New Single-Step Logic
```javascript
class ElegantQuizSystem {
  constructor() {
    this.currentStep = 1;
    this.totalSteps = 5;
    this.selections = {};
    this.stepHistory = [];
  }

  showStep(stepNumber) {
    // Hide all steps
    // Show only current step with fade transition
    // Update question text
  }

  selectOption(option) {
    // Add selection animation
    // Store selection
    // Auto-advance after 800ms delay
  }
}
```

#### 3. Organic Grid CSS System
```css
.quiz-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: var(--quiz-space-md);
  max-width: 600px;
  margin: 0 auto;
}

.quiz-pill {
  background: var(--quiz-pill-bg);
  border: 2px solid transparent;
  border-radius: 12px;
  padding: var(--quiz-space-lg);
  text-align: center;
  cursor: pointer;
  transition: all 0.2s ease;
  min-height: 80px;
}
```

## Implementation Steps

### Step 1: HTML Structure Update (front-page.php)
- Remove progress indicator elements
- Simplify step containers
- Add quiz-grid containers
- Remove navigation button complexity

### Step 2: CSS Design System (medical-spa-theme.css)
- Add CSS custom properties for quiz
- Implement organic grid layout
- Create pill selection styles
- Add transition animations

### Step 3: JavaScript Rewrite (premium-hero.js)
- Replace existing quiz system class
- Implement single-step logic
- Add auto-advance functionality
- Create smooth transitions

### Step 4: Integration Testing
- Test step transitions
- Verify pill selection
- Check responsive behavior
- Validate auto-advance timing

## File Changes Required

### Primary Files:
- `front-page.php` - HTML structure simplification
- `assets/css/medical-spa-theme.css` - New quiz design system
- `assets/js/components/premium-hero.js` - Complete rewrite

### Validation:
- Run `./projManager/projectDocs/ai-governance/validate-file-placement.sh`
- Test on multiple devices
- Verify accessibility compliance

## Quality Gates

### Functionality:
- ✅ Single step display working
- ✅ Pill selection responsive
- ✅ Auto-advance functioning (800ms delay)
- ✅ Back navigation working
- ✅ Smooth transitions

### Performance:
- ✅ CSS < 15KB additional
- ✅ JavaScript < 10KB
- ✅ 60fps animations
- ✅ No layout shifts

### Design:
- ✅ Organic grid responsive
- ✅ Pills have proper hover states
- ✅ Clean, minimal interface
- ✅ Mobile-first design

## Acceptance Criteria

1. **Core Functionality**
   - All 5 steps display individually
   - Selection triggers auto-advance
   - Back button navigates properly
   - No progress bars visible

2. **Visual Design**
   - Organic grid layout works on all screens
   - Pills have elegant hover/selected states
   - Typography follows design system
   - Animations are smooth (60fps)

3. **User Experience**
   - One question at a time
   - Clear visual hierarchy
   - Intuitive navigation
   - Fast, responsive interactions

## Dependencies

- PLAN-UX-QUIZ-002-elegant-redesign.md (✅ Complete)
- Existing quiz system understanding
- CSS Grid browser support analysis

## Risks & Mitigation

### Technical Risks:
- **CSS Grid Support**: Implement flexbox fallback for older browsers
- **Animation Performance**: Use only transform/opacity for smooth animations
- **Touch Interactions**: Ensure larger touch targets on mobile

### UX Risks:
- **Auto-advance Timing**: Test optimal delay (currently 800ms)
- **Back Navigation**: Ensure clear back button visibility
- **Step Context**: Use question text to indicate progress

## Success Metrics

- Quiz displays single step at a time ✅
- Auto-advance works smoothly ✅
- Grid layout responsive on all devices ✅
- User testing shows improved clarity ✅

## Next Phase

After completion, move to:
**TASK-UX-QUIZ-003-02: Visual Polish Implementation**

---

**Task Owner:** Development Team  
**Reviewer:** UX/UI Lead  
**Estimated Completion:** End of Week 1  
**Dependencies Check:** ✅ All dependencies met 
