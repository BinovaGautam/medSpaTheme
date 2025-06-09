# Task Delegation: T6.1 Component Base Architecture

**📋 FUNDAMENTALS**: Read and validated | **COMPLIANCE**: Following fundamentals.json requirements

**Task ID**: T6.1-COMPONENT-BASE-ARCHITECTURE  
**Sprint**: SPRINT-006-CUSTOMIZABLE-COMPONENTS  
**Delegated From**: TASK-PLANNER-001  
**Delegated To**: CODE-GEN-WF-001  
**Primary Agent**: CODE-GEN-001  
**Story Points**: 3 SP  
**Status**: ✅ ACTIVE  

---

## 🎯 **Task Requirements**

### **Objective**
Create the foundation component architecture that integrates with the Universal Design Token System established in previous sprints.

### **Deliverables**
1. **BaseComponent Abstract Class** - `inc/components/base-component.php`
2. **Component Registry System** - `inc/components/component-registry.php`
3. **Component Development CLI** - WP-CLI integration
4. **Documentation** - Implementation guide

---

## 🔄 **Workflow Delegation Evidence**

**✅ DELEGATED TO**: CODE-GEN-WF-001  
**✅ PRIMARY AGENT**: CODE-GEN-001  
**✅ SUPPORTING AGENTS**: CODE-REVIEW-001, DRY-RUN-001  
**✅ QUALITY GATES**: GATE-KEEP-001 approval required  

### **Implementation Requirements**

#### **File Locations (per fundamentals.json)**
```
inc/components/base-component.php          - BaseComponent abstract class
inc/components/component-registry.php      - Component registration system
inc/components/component-factory.php       - Component creation factory
inc/components/design-token-mixin.php      - Design token integration
inc/components/accessibility-mixin.php     - Accessibility enforcement
```

#### **Integration Points**
- **Design Token System**: Inherit from established tokens in previous sprints
- **WordPress Customizer**: Auto-register component controls
- **Accessibility**: WCAG 2.1 AA compliance enforcement
- **Performance**: <100ms component render requirement

---

## 📊 **Acceptance Criteria**

### **AC-6.1: Component Base Class** ✅ Ready to Start
- **GIVEN** I create a new component
- **WHEN** I extend the base component class  
- **THEN** it automatically inherits design token support
- **AND** WordPress Customizer integration works
- **AND** accessibility standards are enforced

### **AC-6.2: Component Registration System** ✅ Ready to Start
- **GIVEN** I create a component
- **WHEN** it's registered in the system
- **THEN** it's available globally throughout the theme
- **AND** it appears in WordPress Customizer
- **AND** design tokens apply automatically

### **AC-6.3: Development Workflow** ✅ Ready to Start
- **GIVEN** I need to create a new component
- **WHEN** I follow the component creation workflow
- **THEN** I can build it in under 30 minutes
- **AND** all standards are automatically enforced
- **AND** documentation is generated

---

## 🏗️ **Technical Implementation Plan**

### **Phase 1: BaseComponent Class** *(1.5 SP)*
```php
<?php
/**
 * Base Component Abstract Class
 * 
 * Foundation for all theme components with design token integration
 * 
 * @package MedSpaTheme
 * @since 1.0.0
 */

abstract class BaseComponent {
    
    /**
     * Design tokens for this component
     * @var array
     */
    protected $design_tokens = [];
    
    /**
     * WordPress Customizer support
     * @var array
     */
    protected $customizer_support = [];
    
    /**
     * Accessibility features
     * @var array
     */
    protected $accessibility_features = [];
    
    /**
     * Component configuration
     * @var array
     */
    protected $config = [];
    
    /**
     * Constructor
     */
    public function __construct($args = []) {
        $this->config = wp_parse_args($args, $this->get_defaults());
        $this->init_design_tokens();
        $this->register_customizer_controls();
        $this->setup_accessibility();
        $this->enqueue_assets();
    }
    
    /**
     * Render the component
     * 
     * @param array $args Component arguments
     * @return string HTML output
     */
    abstract public function render($args = []);
    
    /**
     * Get WordPress Customizer controls
     * 
     * @return array Customizer controls
     */
    abstract public function get_customizer_controls();
    
    /**
     * Get default design tokens
     * 
     * @return array Design tokens
     */
    abstract public function get_default_tokens();
    
    /**
     * Get component defaults
     * 
     * @return array Default configuration
     */
    abstract public function get_defaults();
    
    /**
     * Initialize design tokens
     */
    protected function init_design_tokens() {
        $this->design_tokens = array_merge(
            $this->get_global_tokens(),
            $this->get_default_tokens(),
            $this->get_customizer_tokens()
        );
    }
    
    /**
     * Register WordPress Customizer controls
     */
    protected function register_customizer_controls() {
        if (is_customize_preview()) {
            add_action('customize_register', [$this, 'customize_register']);
        }
    }
    
    /**
     * Setup accessibility features
     */
    protected function setup_accessibility() {
        $this->accessibility_features = [
            'aria_labels' => true,
            'keyboard_navigation' => true,
            'screen_reader_support' => true,
            'color_contrast_validation' => true
        ];
    }
    
    /**
     * Enqueue component assets
     */
    protected function enqueue_assets() {
        $component_name = strtolower(get_class($this));
        
        // Enqueue CSS if exists
        $css_path = get_template_directory() . "/assets/css/components/{$component_name}.css";
        if (file_exists($css_path)) {
            wp_enqueue_style(
                "{$component_name}-component",
                get_template_directory_uri() . "/assets/css/components/{$component_name}.css",
                ['design-token-system'],
                '1.0.0'
            );
        }
        
        // Enqueue JS if exists
        $js_path = get_template_directory() . "/assets/js/components/{$component_name}.js";
        if (file_exists($js_path)) {
            wp_enqueue_script(
                "{$component_name}-component",
                get_template_directory_uri() . "/assets/js/components/{$component_name}.js",
                ['jquery', 'design-token-system'],
                '1.0.0',
                true
            );
        }
    }
    
    /**
     * Get global design tokens
     */
    protected function get_global_tokens() {
        return get_option('medspa_design_tokens', []);
    }
    
    /**
     * Get customizer-specific tokens
     */
    protected function get_customizer_tokens() {
        $tokens = [];
        $controls = $this->get_customizer_controls();
        
        foreach ($controls as $control_id => $control) {
            $tokens[$control_id] = get_theme_mod($control_id, $control['default'] ?? '');
        }
        
        return $tokens;
    }
}
```

### **Phase 2: Component Registry** *(1 SP)*
```php
<?php
/**
 * Component Registry System
 * 
 * Manages component registration and rendering
 * 
 * @package MedSpaTheme
 * @since 1.0.0
 */

class ComponentRegistry {
    
    /**
     * Registered components
     * @var array
     */
    private static $components = [];
    
    /**
     * Component instances cache
     * @var array
     */
    private static $instances = [];
    
    /**
     * Register a component
     * 
     * @param string $name Component name
     * @param string $class Component class
     * @param array $config Component configuration
     */
    public static function register($name, $class, $config = []) {
        // Validation
        if (!class_exists($class)) {
            wp_die("Component class {$class} does not exist");
        }
        
        if (!is_subclass_of($class, 'BaseComponent')) {
            wp_die("Component class {$class} must extend BaseComponent");
        }
        
        // Register component
        self::$components[$name] = [
            'class' => $class,
            'config' => $config,
            'registered_at' => current_time('timestamp')
        ];
        
        // Auto-generate WordPress Customizer controls
        self::register_customizer_controls($name, $class);
        
        // Setup design token inheritance
        self::setup_token_inheritance($name, $class);
    }
    
    /**
     * Render a component
     * 
     * @param string $name Component name
     * @param array $args Component arguments
     * @return string HTML output
     */
    public static function render($name, $args = []) {
        if (!isset(self::$components[$name])) {
            return self::render_fallback($name, $args);
        }
        
        try {
            $instance = self::get_instance($name, $args);
            return $instance->render($args);
        } catch (Exception $e) {
            error_log("Component render error: {$e->getMessage()}");
            return self::render_fallback($name, $args);
        }
    }
    
    /**
     * Get component instance
     * 
     * @param string $name Component name
     * @param array $args Component arguments
     * @return BaseComponent Component instance
     */
    private static function get_instance($name, $args = []) {
        $cache_key = $name . '_' . md5(serialize($args));
        
        if (!isset(self::$instances[$cache_key])) {
            $class = self::$components[$name]['class'];
            $config = array_merge(self::$components[$name]['config'], $args);
            self::$instances[$cache_key] = new $class($config);
        }
        
        return self::$instances[$cache_key];
    }
    
    /**
     * Render fallback content
     */
    private static function render_fallback($name, $args) {
        if (WP_DEBUG) {
            return "<!-- Component '{$name}' not found -->";
        }
        return '';
    }
    
    /**
     * Register customizer controls for component
     */
    private static function register_customizer_controls($name, $class) {
        if (method_exists($class, 'get_customizer_controls')) {
            add_action('customize_register', function($wp_customize) use ($name, $class) {
                $controls = call_user_func([$class, 'get_customizer_controls']);
                // Register controls with WordPress Customizer
                // Implementation details...
            });
        }
    }
    
    /**
     * Setup design token inheritance
     */
    private static function setup_token_inheritance($name, $class) {
        // Connect to Universal Design Token System
        // Implementation details...
    }
}
```

### **Phase 3: Component Factory & Utilities** *(0.5 SP)*
```php
<?php
/**
 * Component Factory
 * 
 * Utility functions for component creation
 * 
 * @package MedSpaTheme
 * @since 1.0.0
 */

class ComponentFactory {
    
    /**
     * Create component instance
     */
    public static function create($name, $args = []) {
        return ComponentRegistry::render($name, $args);
    }
    
    /**
     * Quick render function
     */
    public static function component($name, $args = []) {
        echo ComponentRegistry::render($name, $args);
    }
}

// Global helper functions
if (!function_exists('render_component')) {
    function render_component($name, $args = []) {
        return ComponentRegistry::render($name, $args);
    }
}

if (!function_exists('component')) {
    function component($name, $args = []) {
        echo ComponentRegistry::render($name, $args);
    }
}
```

---

## 📊 **Quality Gates**

### **Development Quality Gates**
- [ ] **Code Review**: CODE-REVIEW-001 approval
- [ ] **Testing**: DRY-RUN-001 validation
- [ ] **Performance**: <100ms component render time
- [ ] **Accessibility**: WCAG 2.1 AA compliance
- [ ] **Integration**: Design token system connection verified

### **Final Approval Gates**
- [ ] **GATE-KEEP-001**: Final quality validation
- [ ] **VERSION-TRACK-001**: Change documentation
- [ ] **Documentation**: Complete implementation guide
- [ ] **Testing**: Cross-browser compatibility verified

---

## 🔄 **Workflow Integration Points**

### **Design Token Integration**
- Connect to established Universal Design Token System
- Inherit color, typography, spacing tokens
- Support real-time customizer updates

### **WordPress Integration**
- Auto-register Customizer controls
- Hook into WordPress actions/filters
- Support WordPress coding standards

### **Performance Integration**
- Component caching system
- Asset optimization
- Lazy loading support

---

**✅ TASK DELEGATION COMPLETE**  
**🤖 PRIMARY AGENT**: CODE-GEN-001  
**🔄 WORKFLOW**: CODE-GEN-WF-001  
**📋 COMPLIANCE**: fundamentals.json requirements followed  

This task is now properly delegated and ready for execution by CODE-GEN-WF-001. 
