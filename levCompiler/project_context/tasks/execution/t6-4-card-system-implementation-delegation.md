# T6.4: Card System Implementation - Task Delegation

**Task ID**: T6.4-CARD-SYSTEM-IMPLEMENTATION  
**Sprint**: Sprint 6 - Customizable Components Implementation  
**Story Points**: 8 SP  
**Priority**: HIGH - Content Component Foundation  
**Created**: {CURRENT_DATE}  
**Delegated To**: **CODE-GEN-WF-001**  
**Primary Agent**: **CODE-GEN-001**  
**Supporting Agents**: **CODE-REVIEW-001**, **DRY-RUN-001**, **GATE-KEEP-001**

## 📋 **FUNDAMENTALS COMPLIANCE**

**✅ FUNDAMENTALS**: Read and validated from `fundamentals.json`  
**✅ WORKFLOW VERIFICATION**: CODE-GEN-WF-001 exists and is active  
**✅ AGENT VERIFICATION**: CODE-GEN-001 confirmed as primary agent  
**✅ FILE ORGANIZATION**: Task delegation in proper location `levCompiler/project_context/tasks/execution/`  
**✅ HUMAN SUPERVISION**: Task delegation approved for execution

## 🎯 **Task Overview**

### **Objective**
Implement a comprehensive card component system that extends the BaseComponent architecture established in T6.1-T6.3, providing reusable, customizable card components for treatment displays, testimonials, and feature content.

### **Context & Dependencies**
- **Foundation Complete**: T6.1 (BaseComponent), T6.2 (ComponentRegistry), T6.3 (ButtonComponent)
- **System Status**: All critical errors resolved via BUG-RESOLUTION-WF-001
- **Architecture**: Component system operational with <100ms render requirements
- **Integration**: Universal Design Token System from previous sprints

## 📊 **Technical Requirements**

### **Core Deliverables**
1. **CardComponent Base Class** (`inc/components/card-component.php`)
2. **Specialized Card Types** (Treatment, Testimonial, Feature)
3. **Card Component Styles** (`assets/css/components/card.css`)
4. **WordPress Customizer Integration** (15+ styling controls)
5. **Demo Templates** (`template-parts/component-demos/card-demo.php`)

### **Component Architecture Requirements**

#### **1. CardComponent Base Class**
```php
<?php
/**
 * Card Component Class
 * 
 * @package MedSpaTheme
 * @extends BaseComponent
 */
class CardComponent extends BaseComponent {
    
    /**
     * Card variants
     */
    const VARIANT_DEFAULT = 'default';
    const VARIANT_ELEVATED = 'elevated';
    const VARIANT_OUTLINED = 'outlined';
    const VARIANT_FILLED = 'filled';
    
    /**
     * Card sizes
     */
    const SIZE_SMALL = 'small';
    const SIZE_MEDIUM = 'medium';
    const SIZE_LARGE = 'large';
    
    /**
     * Render card component
     */
    public function render($args = []) {
        // Card rendering with design token integration
        // Support for image, title, content, meta, actions
        // Responsive design and accessibility
    }
    
    /**
     * Get WordPress Customizer controls
     */
    public function get_customizer_controls() {
        return [
            'border_radius' => [
                'label' => 'Card Border Radius',
                'type' => 'range',
                'default' => '8px',
                'input_attrs' => ['min' => 0, 'max' => 30, 'step' => 1]
            ],
            'padding' => [
                'label' => 'Card Padding',
                'type' => 'range', 
                'default' => '24px',
                'input_attrs' => ['min' => 8, 'max' => 48, 'step' => 4]
            ],
            'shadow' => [
                'label' => 'Card Shadow',
                'type' => 'select',
                'default' => 'medium',
                'choices' => [
                    'none' => 'No Shadow',
                    'small' => 'Small Shadow',
                    'medium' => 'Medium Shadow',
                    'large' => 'Large Shadow'
                ]
            ],
            'border_width' => [
                'label' => 'Border Width',
                'type' => 'range',
                'default' => '0px',
                'input_attrs' => ['min' => 0, 'max' => 5, 'step' => 1]
            ],
            'border_color' => [
                'label' => 'Border Color',
                'type' => 'color',
                'default' => '#e5e7eb'
            ],
            'background_color' => [
                'label' => 'Background Color',
                'type' => 'color',
                'default' => '#ffffff'
            ],
            'hover_transform' => [
                'label' => 'Hover Transform',
                'type' => 'select',
                'default' => 'lift',
                'choices' => [
                    'none' => 'No Transform',
                    'lift' => 'Lift Up',
                    'scale' => 'Scale Up',
                    'shadow' => 'Shadow Increase'
                ]
            ]
            // ... additional controls for spacing, typography, etc.
        ];
    }
    
    /**
     * Get default design tokens
     */
    public function get_default_tokens() {
        return [
            'border_radius' => '8px',
            'padding' => '24px',
            'shadow' => '0 4px 6px -1px rgba(0, 0, 0, 0.1)',
            'border_width' => '0px',
            'border_color' => '#e5e7eb',
            'background_color' => '#ffffff',
            'transition' => '0.3s ease',
            'hover_transform' => 'translateY(-2px)'
        ];
    }
}
```

#### **2. Specialized Card Types**

**Treatment Card Component**
```php
class TreatmentCard extends CardComponent {
    
    public function render($args = []) {
        $defaults = [
            'image' => '',
            'title' => '',
            'duration' => '',
            'price' => '',
            'description' => '',
            'benefits' => [],
            'cta_text' => 'Learn More',
            'cta_url' => '#',
            'book_url' => '#consultation'
        ];
        
        $args = wp_parse_args($args, $defaults);
        
        // Render treatment-specific card layout
        // Include pricing, duration, benefits
        // CTA buttons integration
    }
}
```

**Testimonial Card Component**
```php
class TestimonialCard extends CardComponent {
    
    public function render($args = []) {
        $defaults = [
            'content' => '',
            'author_name' => '',
            'author_title' => '',
            'author_image' => '',
            'rating' => 5,
            'treatment' => '',
            'date' => ''
        ];
        
        $args = wp_parse_args($args, $defaults);
        
        // Render testimonial-specific layout
        // Star rating integration
        // Author information display
    }
}
```

**Feature Card Component**
```php
class FeatureCard extends CardComponent {
    
    public function render($args = []) {
        $defaults = [
            'icon' => '',
            'title' => '',
            'description' => '',
            'link_text' => '',
            'link_url' => '',
            'variant' => 'icon-top'
        ];
        
        $args = wp_parse_args($args, $defaults);
        
        // Render feature-specific layout
        // Icon positioning options
        // Link integration
    }
}
```

### **WordPress Customizer Integration**

#### **Required Customizer Controls** (15+ controls)
1. **Card Border Radius** - Range slider (0-30px)
2. **Card Padding** - Range slider (8-48px)  
3. **Card Shadow** - Select dropdown (none/small/medium/large)
4. **Border Width** - Range slider (0-5px)
5. **Border Color** - Color picker
6. **Background Color** - Color picker
7. **Hover Transform** - Select dropdown (none/lift/scale/shadow)
8. **Image Border Radius** - Range slider (0-20px)
9. **Title Font Size** - Range slider (16-32px)
10. **Title Font Weight** - Select dropdown (400/500/600/700)
11. **Content Font Size** - Range slider (14-18px)
12. **Content Line Height** - Range slider (1.2-2.0)
13. **Meta Text Color** - Color picker
14. **Action Button Spacing** - Range slider (8-32px)
15. **Card Gap in Grids** - Range slider (16-48px)

### **CSS Architecture Requirements**

#### **Design Token Integration**
```css
.card-component {
    border-radius: var(--card-border-radius, 8px);
    padding: var(--card-padding, 24px);
    box-shadow: var(--card-shadow, 0 4px 6px -1px rgba(0, 0, 0, 0.1));
    border: var(--card-border-width, 0) solid var(--card-border-color, #e5e7eb);
    background-color: var(--card-background-color, #ffffff);
    transition: var(--card-transition, 0.3s ease);
}

.card-component:hover {
    transform: var(--card-hover-transform, translateY(-2px));
}

/* Treatment Card Specific */
.treatment-card {
    --card-image-aspect-ratio: 4/3;
    --card-price-color: var(--primary-color);
    --card-duration-color: var(--text-color-secondary);
}

/* Testimonial Card Specific */  
.testimonial-card {
    --card-quote-color: var(--text-color-muted);
    --card-author-color: var(--text-color);
    --card-rating-color: var(--accent-color);
}

/* Feature Card Specific */
.feature-card {
    --card-icon-size: 48px;
    --card-icon-color: var(--primary-color);
    --card-text-align: center;
}
```

#### **Responsive Design Requirements**
```css
/* Mobile First Approach */
.card-component {
    width: 100%;
    margin-bottom: var(--card-gap, 24px);
}

/* Tablet - 2 columns */
@media (min-width: 768px) {
    .card-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: var(--card-gap, 24px);
    }
}

/* Desktop - 3 columns */
@media (min-width: 1024px) {
    .card-grid {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .card-grid.large {
        grid-template-columns: repeat(4, 1fr);
    }
}
```

### **Accessibility Requirements**

#### **WCAG 2.1 AA Compliance**
- **Semantic HTML**: Proper heading hierarchy, article/section elements
- **Keyboard Navigation**: Full keyboard accessibility for interactive elements  
- **Screen Reader Support**: Proper ARIA labels and descriptions
- **Color Contrast**: 4.5:1 minimum contrast ratio for text
- **Focus Management**: Visible focus indicators
- **Image Alt Text**: Descriptive alt text for all images

#### **Accessibility Implementation**
```php
// In CardComponent render method
$accessibility_attrs = $this->get_accessibility_attributes([
    'role' => 'article',
    'aria-label' => $args['title'] ? 'Card: ' . $args['title'] : 'Content card',
    'tabindex' => '0'
]);

// Semantic markup
echo '<article class="card-component" ' . $accessibility_attrs . '>';
```

### **Performance Requirements**

#### **Render Performance**
- **Target**: <100ms per card component render
- **Caching**: Component instance caching for repeated renders
- **Lazy Loading**: Image lazy loading for improved page load
- **CSS Optimization**: Minimal CSS footprint with token inheritance

#### **Image Optimization**
```php
// In card image rendering
public function render_card_image($image_url, $alt_text, $sizes = '(max-width: 768px) 100vw, 33vw') {
    if (empty($image_url)) return '';
    
    return sprintf(
        '<img src="%s" alt="%s" sizes="%s" loading="lazy" class="card-image">',
        esc_url($image_url),
        esc_attr($alt_text),
        esc_attr($sizes)
    );
}
```

## 🧪 **Testing Requirements**

### **Component Testing**
1. **Render Testing**: All card variants render without errors
2. **Design Token Integration**: Customizer changes apply correctly
3. **Responsive Testing**: Cards work across all device sizes
4. **Accessibility Testing**: Full WCAG 2.1 AA compliance
5. **Performance Testing**: <100ms render time validation

### **Integration Testing**
1. **ComponentRegistry Integration**: Cards register and render via registry
2. **WordPress Customizer**: All controls function correctly
3. **Theme Integration**: Cards integrate with existing design system
4. **Browser Compatibility**: Chrome, Firefox, Safari, Edge testing

## 📁 **File Structure Requirements**

### **Required Files**
```
inc/components/
├── card-component.php          (Base CardComponent class)
├── treatment-card.php          (TreatmentCard specialization)
├── testimonial-card.php        (TestimonialCard specialization)
└── feature-card.php           (FeatureCard specialization)

assets/css/components/
└── card.css                   (Card component styles)

template-parts/component-demos/
└── card-demo.php              (Demo template with examples)

template-parts/cards/
├── treatment-card.php          (Treatment card template)
├── testimonial-card.php        (Testimonial card template)
└── feature-card.php           (Feature card template)
```

## ⚡ **Quality Gates**

### **CODE-GEN-001 Quality Gates**
- ✅ All components extend BaseComponent properly
- ✅ WordPress Customizer integration functional
- ✅ Design token inheritance working
- ✅ Accessibility attributes implemented
- ✅ Performance <100ms requirement met

### **CODE-REVIEW-001 Quality Gates**
- ✅ Code follows established patterns from T6.1-T6.3
- ✅ Security best practices implemented
- ✅ Documentation complete and accurate
- ✅ Error handling and fallbacks included

### **DRY-RUN-001 Quality Gates**
- ✅ All card variants render correctly
- ✅ Customizer controls apply changes
- ✅ Responsive design functions properly
- ✅ No JavaScript errors or conflicts

### **GATE-KEEP-001 Quality Gates**
- ✅ Integration with existing component system
- ✅ Performance requirements satisfied
- ✅ Accessibility compliance verified
- ✅ Ready for theme integration

## 🚀 **Delegation Status**

**Status**: ✅ **DELEGATED TO CODE-GEN-WF-001**  
**Workflow**: Initiated  
**Primary Agent**: CODE-GEN-001  
**Expected Duration**: 2-3 days  
**Sprint Impact**: 8 SP toward 55 SP total  

**Next Steps**:
1. CODE-GEN-001 begins requirement analysis
2. Implementation of CardComponent base class
3. Specialized card type development
4. WordPress Customizer integration
5. Testing and quality assurance
6. Final approval and delivery

---

**Task Delegation Complete**: {CURRENT_DATE}  
**Delegated By**: TASK-PLANNER-001  
**Received By**: CODE-GEN-WF-001  
**Workflow Status**: ✅ **ACTIVE** 
