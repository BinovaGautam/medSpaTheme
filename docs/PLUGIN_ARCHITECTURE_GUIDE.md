# Plugin Architecture Guide
## Extensible Customization Engine Documentation

### Overview

The Universal Customization Engine provides a plugin-based architecture that allows developers to add new customization domains (spacing, animations, dark mode, etc.) in **under 1 hour**. This guide shows you how to create and register plugins following industry standards.

## Quick Start: Create a Plugin in 1 Hour

### Step 1: Create Your Plugin Class (15 minutes)

```javascript
class MyCustomizationPlugin extends CustomizationPlugin {
    constructor() {
        super({
            name: 'My Customization',
            version: '1.0.0',
            description: 'Description of what this plugin customizes',
            author: 'Your Name',
            dependencies: [], // Other domains this depends on
            priority: 10      // Loading priority (lower = earlier)
        });
    }
}
```

### Step 2: Define Your Tokens (20 minutes)

```javascript
setupTokenDefinitions() {
    // Define your design tokens
    this.tokens.set('my-token', {
        description: 'Description of this token',
        cssVariable: '--my-token',
        defaultValue: 'default-value',
        type: 'color|dimension|duration|etc'
    });
}
```

### Step 3: Implement Token Generation (20 minutes)

```javascript
async generateTokens(changes, options = {}) {
    const tokens = {};
    
    // Your token generation logic here
    tokens['my-token'] = {
        value: changes.myValue || 'default',
        cssVariable: '--my-token',
        description: 'Generated token description'
    };
    
    return tokens;
}
```

### Step 4: Register Your Plugin (5 minutes)

```javascript
// Register with the Universal Customization Engine
const myPlugin = new MyCustomizationPlugin();
myPlugin.register(window.universalCustomizationEngine);
```

## Complete Plugin Architecture

### Plugin Base Class: `CustomizationPlugin`

All plugins extend the base `CustomizationPlugin` class which provides:

- ✅ **Automatic Registration** with the Universal Customization Engine
- ✅ **WordPress Integration** with auto-generated Customizer controls
- ✅ **Live Preview** integration
- ✅ **Cross-Domain Coordination** with other plugins
- ✅ **Performance Optimization** with caching and lazy loading
- ✅ **Error Handling** and graceful degradation

### Core Plugin Methods

#### Required Methods

```javascript
class YourPlugin extends CustomizationPlugin {
    // REQUIRED: Setup token definitions
    setupTokenDefinitions() {
        this.tokens.set('token-name', {
            description: 'Token description',
            cssVariable: '--css-variable-name',
            defaultValue: 'default-value',
            type: 'color|dimension|duration|etc'
        });
    }
    
    // REQUIRED: Generate tokens from user input
    async generateTokens(changes, options = {}) {
        return {
            'token-name': {
                value: 'computed-value',
                cssVariable: '--css-variable-name',
                description: 'Generated token'
            }
        };
    }
}
```

#### Optional Methods

```javascript
// WordPress Customizer controls
getWordPressControls() {
    return [
        {
            id: 'control_id',
            label: 'Control Label',
            type: 'color|range|select|text|checkbox',
            section: 'your_section',
            transport: 'postMessage',
            default: 'default-value'
        }
    ];
}

// Custom CSS generation
async generateCSS(tokens, options = {}) {
    let css = ':root {\n';
    Object.values(tokens).forEach(token => {
        css += `  ${token.cssVariable}: ${token.value};\n`;
    });
    css += '}\n';
    return css;
}

// Custom token processing
async processTokens(tokens, changes, options = {}) {
    // Apply additional processing to generated tokens
    return tokens;
}

// Custom validation
validateChanges(changes) {
    // Validate user input
    if (!changes.requiredField) {
        throw new Error('Required field missing');
    }
    return changes;
}
```

## Plugin Examples

### 1. Spacing Plugin (Complete Example)

```javascript
class SpacingCustomizationPlugin extends CustomizationPlugin {
    constructor() {
        super({
            name: 'Spacing Customization',
            version: '1.0.0',
            description: 'Provides spacing scale customization',
            author: 'Theme Developer',
            dependencies: [],
            priority: 5
        });
    }

    setupTokenDefinitions() {
        ['xs', 'sm', 'md', 'lg', 'xl'].forEach(size => {
            this.tokens.set(`spacing-${size}`, {
                description: `${size.toUpperCase()} spacing`,
                cssVariable: `--spacing-${size}`,
                type: 'dimension'
            });
        });
    }

    async generateTokens(changes, options = {}) {
        const baseSpacing = changes.baseSpacing || 16;
        const ratio = changes.ratio || 1.5;
        const tokens = {};

        ['xs', 'sm', 'md', 'lg', 'xl'].forEach((size, index) => {
            const value = Math.round(baseSpacing * Math.pow(ratio, index - 2));
            tokens[`spacing-${size}`] = {
                value: `${value}px`,
                cssVariable: `--spacing-${size}`,
                description: `${size.toUpperCase()} spacing`
            };
        });

        return tokens;
    }

    getWordPressControls() {
        return [
            {
                id: 'spacing_base',
                label: 'Base Spacing (px)',
                type: 'range',
                section: 'spacing_customization',
                default: 16,
                input_attrs: { min: 8, max: 32, step: 1 }
            },
            {
                id: 'spacing_ratio',
                label: 'Scale Ratio',
                type: 'range',
                section: 'spacing_customization',
                default: 1.5,
                input_attrs: { min: 1.2, max: 2.0, step: 0.1 }
            }
        ];
    }
}
```

### 2. Animation Plugin (Minimal Example)

```javascript
class AnimationPlugin extends CustomizationPlugin {
    constructor() {
        super({
            name: 'Animation Customization',
            version: '1.0.0',
            description: 'Animation timing and easing',
            dependencies: []
        });
    }

    setupTokenDefinitions() {
        this.tokens.set('duration-fast', {
            description: 'Fast animation duration',
            cssVariable: '--duration-fast',
            type: 'duration'
        });
    }

    async generateTokens(changes) {
        const speed = changes.speedMultiplier || 1.0;
        return {
            'duration-fast': {
                value: `${Math.round(150 * speed)}ms`,
                cssVariable: '--duration-fast',
                description: 'Fast animation duration'
            }
        };
    }
}
```

## Cross-Domain Integration

### Depending on Other Domains

```javascript
class MyPlugin extends CustomizationPlugin {
    constructor() {
        super({
            name: 'My Plugin',
            dependencies: ['color', 'typography'], // Requires color and typography domains
            priority: 10
        });
    }

    async generateTokens(changes, options = {}) {
        // Access other domain data
        const colorDomain = this.engine.getDomain('color');
        const typographyDomain = this.engine.getDomain('typography');
        
        // Use tokens from other domains
        const primaryColor = changes.colorTokens?.primary || '#3b82f6';
        
        return {
            'my-token': {
                value: `based-on-${primaryColor}`,
                cssVariable: '--my-token',
                description: 'Token based on primary color'
            }
        };
    }
}
```

### Automatic Cross-Domain Updates

The engine automatically propagates changes across domains based on dependencies:

```javascript
// When color domain changes → typography domain gets updated → component domain gets updated
engine.applyCustomization('color', { primary: '#ff0000' });
// Automatically triggers updates in dependent domains
```

## WordPress Integration

### Automatic Customizer Controls

The engine automatically generates WordPress Customizer controls based on your `getWordPressControls()` method:

```javascript
getWordPressControls() {
    return [
        {
            id: 'my_setting',           // WordPress setting ID
            label: 'My Setting',        // Control label
            type: 'color',              // Control type
            section: 'my_section',      // Customizer section
            transport: 'postMessage',   // Live preview
            default: '#ffffff',         // Default value
            description: 'Optional help text',
            input_attrs: {              // Additional attributes
                placeholder: 'Enter value...'
            }
        }
    ];
}
```

### Available Control Types

- `color` - Color picker
- `range` - Slider control
- `select` - Dropdown select
- `text` - Text input
- `textarea` - Multi-line text
- `checkbox` - Checkbox control
- `radio` - Radio buttons

## Performance Optimization

### Automatic Caching

The plugin system automatically caches generated tokens:

```javascript
// First call - generates tokens
const result1 = await plugin.apply(changes);

// Second call with same changes - returns cached result
const result2 = await plugin.apply(changes); // ⚡ From cache
```

### Lazy Loading

Plugins are only loaded when needed:

```javascript
// Plugin is registered but not loaded until first use
engine.registerDomain('my-domain', myPluginConfig);

// Plugin is loaded and initialized on first apply()
engine.applyCustomization('my-domain', changes); // Loads plugin now
```

### Performance Monitoring

Built-in performance tracking:

```javascript
const metrics = plugin.getPerformanceMetrics();
console.log('Average generation time:', metrics.averageApplicationTime);
console.log('Cache hit rate:', metrics.cacheHits);
```

## Error Handling

### Validation

```javascript
validateChanges(changes) {
    // Validate required fields
    if (!changes.requiredField) {
        throw new Error('Required field is missing');
    }
    
    // Validate ranges
    if (changes.value < 0 || changes.value > 100) {
        throw new Error('Value must be between 0 and 100');
    }
    
    return changes;
}
```

### Graceful Degradation

```javascript
async generateTokens(changes) {
    try {
        return this.generateComplexTokens(changes);
    } catch (error) {
        console.warn('Falling back to simple tokens:', error);
        return this.getDefaultTokens();
    }
}
```

## Best Practices

### 1. Token Naming Conventions

```javascript
// ✅ Good: Semantic naming
'spacing-md'
'color-primary'
'duration-fast'

// ❌ Bad: Implementation-specific naming
'spacing-16px'
'color-blue'
'duration-300ms'
```

### 2. CSS Variable Naming

```javascript
// ✅ Good: Consistent prefixing
cssVariable: '--spacing-md'
cssVariable: '--color-primary'
cssVariable: '--duration-fast'

// ❌ Bad: Inconsistent naming
cssVariable: '--md-spacing'
cssVariable: '--primaryColor'
cssVariable: '--fastDuration'
```

### 3. Performance Considerations

```javascript
// ✅ Good: Cache expensive operations
async generateTokens(changes) {
    const cacheKey = JSON.stringify(changes);
    if (this.cache.has(cacheKey)) {
        return this.cache.get(cacheKey);
    }
    
    const tokens = this.expensiveGeneration(changes);
    this.cache.set(cacheKey, tokens);
    return tokens;
}

// ✅ Good: Minimize DOM updates
async generateCSS(tokens) {
    // Generate all CSS at once rather than individual updates
    return this.batchGenerateCSS(tokens);
}
```

### 4. Accessibility

```javascript
// ✅ Good: Include accessibility considerations
async generateTokens(changes) {
    const tokens = {};
    
    // Ensure minimum contrast ratios
    if (this.calculateContrast(color1, color2) < 4.5) {
        color2 = this.adjustForContrast(color1, color2);
    }
    
    // Ensure minimum touch target sizes
    if (size < 44) {
        size = 44; // 44px minimum for accessibility
    }
    
    return tokens;
}
```

## Testing Your Plugin

### Manual Testing

```javascript
// 1. Create plugin instance
const plugin = new MyCustomizationPlugin();

// 2. Register with engine
await plugin.register(window.universalCustomizationEngine);

// 3. Test token generation
const result = await plugin.apply({
    myValue: 'test-value'
});

console.log('Generated tokens:', result.tokens);
console.log('Generated CSS:', result.css);
```

### Automated Testing

```javascript
// Test suite example
describe('MyCustomizationPlugin', () => {
    let plugin;
    
    beforeEach(() => {
        plugin = new MyCustomizationPlugin();
    });
    
    test('generates correct tokens', async () => {
        const result = await plugin.apply({ myValue: 'test' });
        expect(result.tokens['my-token'].value).toBe('test');
    });
    
    test('validates input correctly', () => {
        expect(() => plugin.validateChanges({}))
            .toThrow('Required field missing');
    });
});
```

## Debugging

### Debug Mode

Enable debug logging:

```javascript
const plugin = new MyCustomizationPlugin();
plugin.debug = true; // Enables detailed logging
```

### Performance Debugging

```javascript
// Monitor performance
const metrics = plugin.getPerformanceMetrics();
console.table(metrics);

// Analyze cross-domain relationships
const engine = window.universalCustomizationEngine;
console.log('Registered domains:', engine.getDomains());
console.log('Cross-domain relationships:', engine.getPerformanceMetrics());
```

## Deployment

### Production Checklist

- ✅ Plugin is tested with various input combinations
- ✅ Error handling covers edge cases
- ✅ Performance is optimized (caching, lazy loading)
- ✅ WordPress controls are user-friendly
- ✅ Accessibility requirements are met
- ✅ Documentation is complete

### File Structure

```
assets/js/
├── universal-customization-engine.js  # Core engine
├── customization-plugin.js            # Base plugin class
├── example-plugins.js                 # Example implementations
└── your-custom-plugin.js              # Your plugin
```

## Support & Community

- **Documentation**: Full API reference available
- **Examples**: Multiple working examples provided
- **Performance**: Built-in monitoring and optimization
- **Extensibility**: Unlimited domain support
- **Standards**: W3C Design Tokens compliance

---

*This plugin architecture enables infinite extensibility while maintaining performance and WordPress integration. Create new customization domains in under 1 hour with this powerful, future-ready foundation.* 
