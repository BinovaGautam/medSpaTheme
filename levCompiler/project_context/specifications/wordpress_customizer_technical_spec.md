# WordPress Customizer Technical Specification
**Document Version:** 1.0  
**Created:** 2025-06-08  
**Sprint:** 4 - Industry-Standard WordPress Customizer Architecture  
**Workflow Integration:** CODE-GEN-WF-001, BUG-RESOLUTION-WF-001  

---

## 🎯 **Implementation Specification**

### **Primary Objective**
Transform the current Visual Customizer from runtime CSS generation to enterprise-grade WordPress customizer architecture following industry standards (Astra, GeneratePress, OceanWP patterns).

### **Success Criteria**
- ✅ Zero CSS delivery conflicts between server and client
- ✅ Real-time preview updates without page refresh  
- ✅ File-based CSS caching with 50%+ performance improvement
- ✅ WordPress coding standards compliance
- ✅ Backward compatibility with existing configurations

---

## 🏗️ **Architecture Specification**

### **1. WordPress Core Integration Layer**

#### **Class Structure**
```php
class MedSpa_Customizer {
    private static $instance = null;
    private $css_generator;
    private $file_manager;
    private $preview_handler;
    
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __construct() {
        add_action( 'customize_register', array( $this, 'register_customizer' ), 10 );
        add_action( 'customize_preview_init', array( $this, 'preview_init' ) );
        add_action( 'customize_save_after', array( $this, 'generate_css_file' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_dynamic_css' ), 15 );
    }
}
```

#### **wp_customize_register Implementation**
```php
public function register_customizer( $wp_customize ) {
    // Color Palette Panel
    $wp_customize->add_panel( 'medspa_color_palette', array(
        'title'    => __( 'Visual Color Palette', 'medspatheme' ),
        'priority' => 30,
    ) );
    
    // Palette Selection Section
    $wp_customize->add_section( 'medspa_palette_selection', array(
        'title'  => __( 'Palette Selection', 'medspatheme' ),
        'panel'  => 'medspa_color_palette',
    ) );
    
    // Palette Setting with postMessage transport
    $wp_customize->add_setting( 'visual_color_palette', array(
        'default'    => 'professional-trust',
        'transport'  => 'postMessage',
        'sanitize_callback' => array( $this, 'sanitize_palette_choice' ),
        'type'       => 'theme_mod',
    ) );
    
    // Palette Control
    $wp_customize->add_control( 'visual_color_palette', array(
        'type'    => 'select',
        'section' => 'medspa_palette_selection',
        'label'   => __( 'Choose Color Palette', 'medspatheme' ),
        'choices' => $this->get_palette_choices(),
    ) );
}
```

#### **Sanitization Framework**
```php
public function sanitize_palette_choice( $input ) {
    $valid_palettes = array_keys( $this->get_palette_choices() );
    return in_array( $input, $valid_palettes, true ) ? $input : 'professional-trust';
}

public function sanitize_color( $input ) {
    return sanitize_hex_color( $input );
}

public function sanitize_css_unit( $input ) {
    return preg_match( '/^[0-9]+(?:px|em|rem|%|vh|vw)?$/', $input ) ? $input : '';
}
```

---

### **2. File-Based CSS Architecture**

#### **Directory Structure**
```
/wp-content/uploads/
└── medspatheme/
    ├── dynamic-style.css
    ├── dynamic-style-v{timestamp}.css
    └── cache/
        ├── palette-professional-trust.css
        ├── palette-sage-tranquility.css
        └── cleanup.log
```

#### **CSS File Generator Class**
```php
class MedSpa_CSS_Generator {
    private $upload_dir;
    private $css_dir;
    
    public function __construct() {
        $upload = wp_upload_dir();
        $this->upload_dir = $upload['basedir'];
        $this->css_dir = $this->upload_dir . '/medspatheme/';
        
        $this->ensure_directory_exists();
    }
    
    public function generate_palette_css( $palette_name ) {
        $palette_config = $this->get_palette_config( $palette_name );
        $css_content = $this->build_css_from_palette( $palette_config );
        
        return $this->write_css_file( $css_content, $palette_name );
    }
    
    private function build_css_from_palette( $config ) {
        $css = ":root {\n";
        foreach ( $config as $property => $value ) {
            $css .= "    --{$property}: {$value};\n";
        }
        $css .= "}\n\n";
        
        // Component-specific CSS
        $css .= $this->generate_component_css( $config );
        
        return $css;
    }
    
    private function write_css_file( $content, $palette_name ) {
        $timestamp = time();
        $filename = "dynamic-style-v{$timestamp}.css";
        $filepath = $this->css_dir . $filename;
        
        $result = file_put_contents( $filepath, $content );
        
        if ( $result ) {
            // Update option with current file info
            update_option( 'medspa_dynamic_css_file', array(
                'path' => $filepath,
                'url'  => str_replace( ABSPATH, home_url( '/' ), $filepath ),
                'version' => $timestamp,
                'palette' => $palette_name,
            ) );
            
            // Cleanup old files
            $this->cleanup_old_files();
            
            return true;
        }
        
        return false;
    }
}
```

#### **Cache Busting Implementation**
```php
public function get_css_file_url() {
    $file_info = get_option( 'medspa_dynamic_css_file', array() );
    
    if ( empty( $file_info ) || ! file_exists( $file_info['path'] ) ) {
        return false;
    }
    
    // Add filemtime for additional cache busting
    $version = filemtime( $file_info['path'] );
    return add_query_arg( 'ver', $version, $file_info['url'] );
}
```

---

### **3. wp_enqueue Integration System**

#### **Dynamic CSS Enqueuing**
```php
public function enqueue_dynamic_css() {
    $css_url = $this->get_css_file_url();
    
    if ( $css_url ) {
        // File-based CSS loading
        wp_enqueue_style(
            'medspa-dynamic-style',
            $css_url,
            array( 'medspatheme-style' ),
            null // Version handled by query string
        );
    } else {
        // Fallback to inline CSS
        $this->enqueue_inline_css_fallback();
    }
}

private function enqueue_inline_css_fallback() {
    $current_palette = get_theme_mod( 'visual_color_palette', 'professional-trust' );
    $css_content = $this->generate_inline_css( $current_palette );
    
    wp_add_inline_style( 'medspatheme-style', $css_content );
    
    // Log fallback usage for debugging
    error_log( 'MedSpa Theme: Using inline CSS fallback due to file generation failure' );
}
```

#### **Dependency Management**
```php
public function register_dependencies() {
    // Ensure main theme style is loaded first
    wp_enqueue_style( 'medspatheme-style' );
    
    // Dynamic CSS depends on main style
    wp_enqueue_style(
        'medspa-dynamic-style',
        $this->get_css_file_url(),
        array( 'medspatheme-style' ),
        $this->get_css_version()
    );
}
```

---

### **4. Real-Time Preview System**

#### **JavaScript Preview Handler**
```javascript
// customizer-preview.js
(function( $ ) {
    'use strict';
    
    // Palette change handler
    wp.customize( 'visual_color_palette', function( value ) {
        value.bind( function( newPalette ) {
            // Get palette configuration
            const paletteConfig = medSpaCustomizer.palettes[newPalette];
            
            if ( paletteConfig ) {
                // Update CSS custom properties
                updateCSSProperties( paletteConfig );
                
                // Update specific components
                updateComponentColors( paletteConfig );
                
                // Trigger custom event for other scripts
                $( document ).trigger( 'medSpaColorPaletteChanged', [newPalette, paletteConfig] );
            }
        });
    });
    
    function updateCSSProperties( config ) {
        const root = document.documentElement;
        
        Object.keys( config ).forEach( function( property ) {
            root.style.setProperty( '--' + property, config[property] );
        });
    }
    
    function updateComponentColors( config ) {
        // Update buttons
        $( '.btn-primary' ).css( 'background-color', config['color-primary-navy'] );
        
        // Update links
        $( 'a' ).css( 'color', config['color-primary-navy'] );
        
        // Update text colors
        $( '.secondary-text' ).css( 'color', config['color-secondary-text'] );
        $( '.muted-text' ).css( 'color', config['color-muted-text'] );
    }
    
})( jQuery );
```

#### **Customizer Control Enhancements**
```php
public function enqueue_customizer_scripts() {
    wp_enqueue_script(
        'medspa-customizer-controls',
        get_template_directory_uri() . '/assets/js/customizer-controls.js',
        array( 'jquery', 'customize-controls' ),
        MEDSPATHEME_VERSION,
        true
    );
    
    // Localize palette data for JavaScript
    wp_localize_script( 'medspa-customizer-controls', 'medSpaCustomizer', array(
        'palettes' => $this->get_all_palette_configs(),
        'previewUrl' => home_url( '/' ),
        'nonce' => wp_create_nonce( 'medspa_customizer_nonce' ),
    ) );
}
```

---

### **5. Performance Optimization Layer**

#### **Caching Strategy**
```php
class MedSpa_Cache_Manager {
    private $cache_group = 'medspa_customizer';
    
    public function get_cached_css( $palette_name ) {
        return wp_cache_get( "css_{$palette_name}", $this->cache_group );
    }
    
    public function cache_css( $palette_name, $css_content ) {
        wp_cache_set( "css_{$palette_name}", $css_content, $this->cache_group, HOUR_IN_SECONDS );
    }
    
    public function flush_palette_cache( $palette_name = null ) {
        if ( $palette_name ) {
            wp_cache_delete( "css_{$palette_name}", $this->cache_group );
        } else {
            wp_cache_flush_group( $this->cache_group );
        }
    }
}
```

#### **Critical CSS Loading**
```php
public function inline_critical_css() {
    $current_palette = get_theme_mod( 'visual_color_palette', 'professional-trust' );
    $critical_css = $this->get_critical_css( $current_palette );
    
    if ( $critical_css ) {
        echo "<style id='medspa-critical-css'>{$critical_css}</style>";
    }
}

private function get_critical_css( $palette_name ) {
    // Only include above-the-fold CSS
    $palette_config = $this->get_palette_config( $palette_name );
    
    return ":root {
        --color-primary-navy: {$palette_config['color-primary-navy']};
        --color-brand-primary: {$palette_config['color-brand-primary']};
    }
    .header { background: var(--color-primary-navy); }
    .hero-section { color: var(--color-brand-primary); }";
}
```

---

### **6. Error Handling & Debugging**

#### **Comprehensive Error Management**
```php
class MedSpa_Error_Handler {
    private $log_file;
    
    public function __construct() {
        $upload_dir = wp_upload_dir();
        $this->log_file = $upload_dir['basedir'] . '/medspatheme/debug.log';
    }
    
    public function log_css_generation_error( $error_message, $context = array() ) {
        $log_entry = array(
            'timestamp' => current_time( 'Y-m-d H:i:s' ),
            'type' => 'CSS_GENERATION_ERROR',
            'message' => $error_message,
            'context' => $context,
            'user_id' => get_current_user_id(),
            'url' => $_SERVER['REQUEST_URI'] ?? '',
        );
        
        error_log( json_encode( $log_entry ), 3, $this->log_file );
    }
    
    public function handle_file_write_failure( $filepath, $content_length ) {
        $this->log_css_generation_error( 'CSS file write failed', array(
            'filepath' => $filepath,
            'content_length' => $content_length,
            'directory_writable' => is_writable( dirname( $filepath ) ),
            'disk_space' => disk_free_space( dirname( $filepath ) ),
        ) );
        
        // Attempt fallback strategies
        return $this->attempt_fallback_write( $filepath, $content_length );
    }
}
```

#### **Permission Validation**
```php
public function validate_environment() {
    $checks = array();
    
    // Check upload directory permissions
    $upload_dir = wp_upload_dir();
    $checks['upload_writable'] = wp_is_writable( $upload_dir['basedir'] );
    
    // Check theme directory structure
    $theme_upload_dir = $upload_dir['basedir'] . '/medspatheme/';
    $checks['theme_dir_exists'] = file_exists( $theme_upload_dir );
    $checks['theme_dir_writable'] = wp_is_writable( $theme_upload_dir );
    
    // Test file creation
    $test_file = $theme_upload_dir . 'write-test-' . time() . '.tmp';
    $checks['can_create_files'] = @file_put_contents( $test_file, 'test' ) !== false;
    
    if ( $checks['can_create_files'] ) {
        @unlink( $test_file );
    }
    
    return $checks;
}
```

---

### **7. Migration Strategy**

#### **Data Migration from Legacy System**
```php
class MedSpa_Migration_Handler {
    public function migrate_existing_settings() {
        $legacy_palette = get_option( 'medspa_visual_customizer_palette' );
        
        if ( $legacy_palette && ! get_theme_mod( 'visual_color_palette' ) ) {
            // Migrate to theme_mod
            set_theme_mod( 'visual_color_palette', $legacy_palette );
            
            // Generate CSS file for migrated setting
            $css_generator = new MedSpa_CSS_Generator();
            $css_generator->generate_palette_css( $legacy_palette );
            
            // Log successful migration
            $this->log_migration( 'palette_setting', $legacy_palette );
        }
    }
    
    public function cleanup_legacy_system() {
        // Remove old options
        delete_option( 'medspa_visual_customizer_palette' );
        delete_option( 'medspa_visual_customizer_config' );
        
        // Remove legacy CSS output actions
        remove_action( 'wp_head', 'medspa_output_custom_css' );
    }
}
```

---

### **8. Quality Assurance Specifications**

#### **Unit Testing Requirements**
```php
class MedSpa_Customizer_Tests extends WP_UnitTestCase {
    public function test_palette_sanitization() {
        $customizer = new MedSpa_Customizer();
        
        // Valid palette
        $this->assertEquals( 
            'professional-trust', 
            $customizer->sanitize_palette_choice( 'professional-trust' ) 
        );
        
        // Invalid palette should return default
        $this->assertEquals( 
            'professional-trust', 
            $customizer->sanitize_palette_choice( 'invalid-palette' ) 
        );
    }
    
    public function test_css_file_generation() {
        $generator = new MedSpa_CSS_Generator();
        $result = $generator->generate_palette_css( 'professional-trust' );
        
        $this->assertTrue( $result );
        
        // Verify file exists
        $file_info = get_option( 'medspa_dynamic_css_file' );
        $this->assertFileExists( $file_info['path'] );
    }
}
```

#### **Performance Benchmarks**
- **File Generation Time**: < 200ms
- **CSS File Size**: < 50KB
- **Cache Hit Ratio**: > 85%
- **Customizer Load Time**: < 500ms

---

## 🔧 **Development Workflow Integration**

### **CODE-GEN-WF-001 Implementation**
1. **Requirement Analysis**: Technical specification review and validation
2. **Code Implementation**: Systematic development of each layer
3. **Parallel Review**: CODE-REVIEW-001 validation during development
4. **Testing**: DRY-RUN-001 comprehensive testing protocols
5. **Optimization**: Performance optimization and security review
6. **Final Approval**: GATE-KEEP-001 quality certification

### **BUG-RESOLUTION-WF-001 Integration**
1. **Issue Context**: Customizer conflicts and CSS delivery problems
2. **Environment Validation**: DevKinsta performance and compatibility
3. **Systematic Debugging**: Root cause analysis for customizer issues
4. **DevTools Creation**: Debugging utilities for customizer development
5. **Solution Implementation**: Fix implementation via CODE-GEN-WF-001

---

## 📋 **Acceptance Criteria**

### **Functional Requirements**
- ✅ WordPress customizer integration with real-time preview
- ✅ File-based CSS generation with automatic cache busting
- ✅ Backward compatibility with existing palette configurations
- ✅ Error handling with graceful fallbacks
- ✅ Performance improvements of 50%+ over current implementation

### **Non-Functional Requirements**
- ✅ WordPress coding standards compliance
- ✅ Mobile-responsive customizer interface
- ✅ Cross-browser compatibility (Chrome, Firefox, Safari, Edge)
- ✅ PHP 7.4+ and WordPress 5.8+ compatibility
- ✅ Security best practices implementation

### **Integration Requirements**
- ✅ Theme mod storage for WordPress export/import compatibility
- ✅ Plugin compatibility with popular caching solutions
- ✅ Multi-site network compatibility
- ✅ Child theme support and inheritance
- ✅ Developer API for custom palette additions

---

**Status**: Ready for CODE-GEN-WF-001 implementation  
**Next Phase**: Begin systematic development of WordPress core integration layer 
