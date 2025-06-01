# Elegant Quiz Component

A modern, accessible, and reusable quiz component for WordPress themes.

## Features

- ✅ **Reusable**: Use anywhere in your theme with simple include
- ✅ **Configurable**: Customizable titles, styles, and options
- ✅ **Accessible**: WCAG 2.1 AA compliant with screen reader support
- ✅ **Responsive**: Mobile-first design that works on all devices
- ✅ **Performance**: Lazy-loaded assets and optimized animations
- ✅ **Secure**: Nonce-protected form submissions
- ✅ **Internationalized**: Ready for translation

## Usage

### Basic Usage

```php
<?php get_template_part('template-parts/components/elegant-quiz'); ?>
```

### Advanced Configuration

```php
<?php 
get_template_part('template-parts/components/elegant-quiz', null, array(
    'title' => __('Find Your Perfect Treatment', 'your-textdomain'),
    'subtitle' => __('Answer a few questions to get personalized recommendations', 'your-textdomain'),
    'css_class' => 'my-custom-class',
    'show_progress' => true,
    'treatments' => array(
        array('value' => 'custom', 'text' => 'Custom Treatment', 'icon' => '🎯'),
        // ... more treatments
    )
));
?>
```

## Configuration Options

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `title` | string | `'Find Your Perfect Treatment'` | Main quiz title |
| `subtitle` | string | `'Answer a few questions...'` | Quiz subtitle/description |
| `css_class` | string | `''` | Additional CSS classes |
| `show_progress` | boolean | `false` | Show progress indicators |
| `treatments` | array | `null` | Custom treatment options |

## Custom Treatment Format

```php
$treatments = array(
    array(
        'value' => 'treatment-slug',
        'text' => 'Treatment Display Name',
        'icon' => '🎯' // Emoji or HTML
    ),
    // ... more treatments
);
```

## Styling

The component uses CSS custom properties for easy theming:

```css
.elegant-quiz {
    --quiz-border-radius: 12px;
    --quiz-spacing: 1.5rem;
    --quiz-pill-bg: #f8f9fa;
    --quiz-pill-hover-bg: #2c3e50;
    /* ... more variables */
}
```

## JavaScript API

### Manual Initialization

```javascript
const quizElement = document.getElementById('my-quiz');
const quiz = new ElegantQuizComponent(quizElement, {
    autoAdvanceDelay: 800,
    enableAnimations: true
});
```

### Events

```javascript
// Quiz completion
document.addEventListener('quiz:completed', (e) => {
    console.log('Quiz completed:', e.detail);
});

// Step change
document.addEventListener('quiz:step-changed', (e) => {
    console.log('Step changed to:', e.detail.step);
});
```

## Accessibility Features

- **Keyboard Navigation**: Full keyboard support with Tab/Enter/Escape
- **Screen Reader Support**: ARIA labels, live regions, and semantic HTML
- **Focus Management**: Proper focus handling during step transitions
- **High Contrast Mode**: Supports Windows high contrast mode
- **Reduced Motion**: Respects `prefers-reduced-motion` setting

## Browser Support

- **Modern Browsers**: Chrome 60+, Firefox 60+, Safari 12+, Edge 79+
- **Progressive Enhancement**: Works without JavaScript
- **Mobile**: iOS Safari 12+, Chrome Mobile 60+

## Performance

- **Lazy Loading**: Scripts only load when component is used
- **Code Splitting**: Component styles separate from main bundle
- **Optimized Animations**: Hardware-accelerated CSS transforms
- **Memory Management**: Proper cleanup to prevent leaks

## Backend Integration

The component automatically handles form submission via AJAX to the `submit_elegant_quiz` action. Make sure your backend handler is properly configured in `functions.php`.

## Troubleshooting

### Common Issues

**Quiz not loading:**
- Check if JavaScript errors in console
- Ensure `elegant-quiz.js` is enqueued
- Verify AJAX URL is correct

**Styling issues:**
- Ensure component CSS is enqueued after main theme styles
- Check for CSS specificity conflicts
- Verify CSS custom properties are supported

**Form submission fails:**
- Check nonce validation
- Ensure AJAX action is registered
- Verify server PHP errors

### Debug Mode

Enable debug mode by adding to your `wp-config.php`:

```php
define('ELEGANT_QUIZ_DEBUG', true);
```

This will log additional information to the browser console.

## Extending

### Custom Steps

You can add custom steps by extending the component:

```javascript
class CustomQuizComponent extends ElegantQuizComponent {
    async renderStepContent(stepElement, stepNumber) {
        if (stepNumber === 6) {
            // Custom step logic
            return;
        }
        return super.renderStepContent(stepElement, stepNumber);
    }
}
```

### Custom Validation

```javascript
validateField(fieldName, value) {
    if (fieldName === 'custom_field') {
        return this.customValidation(value);
    }
    return super.validateField(fieldName, value);
}
```

## Migration from Old Quiz

If migrating from the embedded quiz in `front-page.php`:

1. Replace the HTML block with component include
2. Move any custom treatments to the `treatments` parameter
3. Update CSS selectors if needed
4. Test functionality thoroughly

## Contributing

When contributing to this component:

1. Follow WordPress coding standards
2. Ensure accessibility compliance
3. Test across different devices
4. Update documentation
5. Add unit tests for new features

## License

This component is part of the MedSpa theme and follows the same license terms. 
