/**
 * Medical Spa Theme Customizer Preview
 * Live preview functionality for theme customization
 */

(function($) {
    'use strict';

    // Color palette changes
    wp.customize('medspaa_color_palette', function(value) {
        value.bind(function(newval) {
            // Trigger a refresh since we need to regenerate CSS variables
            wp.customize.preview.send('refresh');
        });
    });

    // Custom color changes
    wp.customize('medspaa_primary_color', function(value) {
        value.bind(function(newval) {
            updateCSSVariable('--color-primary', newval);
        });
    });

    wp.customize('medspaa_secondary_color', function(value) {
        value.bind(function(newval) {
            updateCSSVariable('--color-secondary', newval);
        });
    });

    wp.customize('medspaa_accent_color', function(value) {
        value.bind(function(newval) {
            updateCSSVariable('--color-accent', newval);
        });
    });

    // Font changes
    wp.customize('medspaa_heading_font', function(value) {
        value.bind(function(newval) {
            wp.customize.preview.send('refresh');
        });
    });

    wp.customize('medspaa_body_font', function(value) {
        value.bind(function(newval) {
            wp.customize.preview.send('refresh');
        });
    });

    // Font scale changes
    wp.customize('medspaa_font_scale', function(value) {
        value.bind(function(newval) {
            let scale = 1.0;
            switch(newval) {
                case 'small': scale = 0.9; break;
                case 'large': scale = 1.1; break;
                default: scale = 1.0;
            }
            updateCSSVariable('--font-scale', scale);
            $('body').css('font-size', 'calc(16px * ' + scale + ')');
        });
    });

    // Button style changes
    wp.customize('medspaa_button_style', function(value) {
        value.bind(function(newval) {
            let radius = '8px';
            switch(newval) {
                case 'sharp': radius = '0px'; break;
                case 'pill': radius = '50px'; break;
                default: radius = '8px';
            }
            updateCSSVariable('--button-radius', radius);
            $('.btn, button, input[type="submit"], .button').css('border-radius', radius);
        });
    });

    // Header style changes
    wp.customize('medspaa_header_style', function(value) {
        value.bind(function(newval) {
            const $header = $('.site-header');
            $header.removeClass('header-transparent header-solid header-gradient');

            switch(newval) {
                case 'solid':
                    $header.addClass('header-solid');
                    break;
                case 'gradient':
                    $header.addClass('header-gradient');
                    break;
                default:
                    $header.addClass('header-transparent');
            }
        });
    });

    // Animation preferences
    wp.customize('medspaa_animations', function(value) {
        value.bind(function(newval) {
            const $body = $('body');
            $body.removeClass('animations-enabled animations-reduced animations-disabled');
            $body.addClass('animations-' + newval);

            if (newval === 'disabled') {
                $('*').css({
                    'transition': 'none !important',
                    'animation': 'none !important'
                });
            } else {
                // Remove inline styles to restore CSS animations
                $('*').css({
                    'transition': '',
                    'animation': ''
                });
            }
        });
    });

    /**
     * Update CSS custom property
     */
    function updateCSSVariable(property, value) {
        $(':root').css(property, value);

        // If no native support, update via style element
        if (!CSS.supports('color', 'var(--test)')) {
            updateDynamicStyles();
        }
    }

    /**
     * Update dynamic styles for browsers without CSS custom properties support
     */
    function updateDynamicStyles() {
        const colors = {
            primary: wp.customize('medspaa_primary_color')() || '#1B365D',
            secondary: wp.customize('medspaa_secondary_color')() || '#87A96B',
            accent: wp.customize('medspaa_accent_color')() || '#D4AF37'
        };

        const fontScale = wp.customize('medspaa_font_scale')() || 'normal';
        const scale = fontScale === 'small' ? 0.9 : fontScale === 'large' ? 1.1 : 1.0;

        const buttonStyle = wp.customize('medspaa_button_style')() || 'rounded';
        const radius = buttonStyle === 'sharp' ? '0px' : buttonStyle === 'pill' ? '50px' : '8px';

        // Generate fallback CSS
        const fallbackCSS = `
            .btn, button, input[type='submit'], .button {
                background-color: ${colors.primary};
                border-color: ${colors.primary};
                border-radius: ${radius};
            }
            .btn:hover, button:hover, input[type='submit']:hover, .button:hover {
                background-color: ${colors.secondary};
                border-color: ${colors.secondary};
            }
            body {
                font-size: ${16 * scale}px;
            }
        `;

        // Update or create fallback style element
        let $fallbackStyles = $('#medspaa-customizer-fallback');
        if ($fallbackStyles.length === 0) {
            $fallbackStyles = $('<style id="medspaa-customizer-fallback"></style>');
            $('head').append($fallbackStyles);
        }
        $fallbackStyles.text(fallbackCSS);
    }

    /**
     * Initialize preview enhancements
     */
    function initPreview() {
        // Add customizer body class
        $('body').addClass('customizer-preview');

        // Add visual indicators for customizable elements
        if (wp.customize.settings.preview.activeControls) {
            addCustomizerHighlights();
        }
    }

    /**
     * Add visual highlights for customizable elements
     */
    function addCustomizerHighlights() {
        // Highlight elements when corresponding controls are focused
        const highlights = {
            'medspaa_primary_color': '.btn, .site-header, h1, h2, h3',
            'medspaa_secondary_color': '.btn:hover, .secondary-elements',
            'medspaa_accent_color': '.accent-elements, .highlight',
            'medspaa_heading_font': 'h1, h2, h3, h4, h5, h6',
            'medspaa_body_font': 'body, p, div',
            'medspaa_button_style': '.btn, button, input[type="submit"]'
        };

        $.each(highlights, function(control, selector) {
            wp.customize(control, function(value) {
                value.bind(function() {
                    // Add temporary highlight
                    $(selector).addClass('customizer-highlight');
                    setTimeout(function() {
                        $(selector).removeClass('customizer-highlight');
                    }, 1000);
                });
            });
        });
    }

    // Initialize when document is ready
    $(document).ready(function() {
        initPreview();
    });

})(jQuery);
