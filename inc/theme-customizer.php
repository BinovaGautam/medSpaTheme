<?php
/**
 * Medical Spa Theme Customizer
 * Advanced customization options for colors, fonts, and layouts
 */

/**
 * Add theme customizer settings
 */
function medspaa_theme_customizer_register($wp_customize) {

    // =============================================================================
    // THEME COLORS PANEL
    // =============================================================================

    $wp_customize->add_panel('medspaa_theme_colors', array(
        'title' => '🎨 Theme Colors & Palettes',
        'description' => 'Customize your medical spa theme colors and choose from professional palettes',
        'priority' => 10,
    ));

    // Color Palette Section
    $wp_customize->add_section('medspaa_color_palette', array(
        'title' => 'Color Palette Presets',
        'panel' => 'medspaa_theme_colors',
        'priority' => 10,
    ));

    // Color Palette Selection
    $wp_customize->add_setting('medspaa_color_palette', array(
        'default' => 'classic_forest',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('medspaa_color_palette', array(
        'label' => 'Choose Color Palette',
        'section' => 'medspaa_color_palette',
        'type' => 'radio',
        'choices' => array(
            'classic_forest' => 'Classic Forest (Default) - Deep forest green with gold accents',
            'ocean_blue' => 'Ocean Blue - Calming blues with silver accents',
            'rose_gold' => 'Rose Gold - Warm rose with copper accents',
            'sage_mint' => 'Sage Mint - Fresh sage with mint accents',
            'lavender_grey' => 'Lavender Grey - Soft lavender with charcoal',
            'warm_earth' => 'Warm Earth - Rich browns with terracotta',
            'modern_mono' => 'Modern Monochrome - Black, white, and grey tones',
        ),
    ));

    // Custom Primary Color
    $wp_customize->add_setting('medspaa_primary_color', array(
        'default' => '#1B365D',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'medspaa_primary_color', array(
        'label' => 'Custom Primary Color',
        'section' => 'medspaa_color_palette',
        'description' => 'Override palette primary color with custom color',
    )));

    // Custom Secondary Color
    $wp_customize->add_setting('medspaa_secondary_color', array(
        'default' => '#87A96B',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'medspaa_secondary_color', array(
        'label' => 'Custom Secondary Color',
        'section' => 'medspaa_color_palette',
        'description' => 'Override palette secondary color with custom color',
    )));

    // Custom Accent Color
    $wp_customize->add_setting('medspaa_accent_color', array(
        'default' => '#D4AF37',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'medspaa_accent_color', array(
        'label' => 'Custom Accent Color',
        'section' => 'medspaa_color_palette',
        'description' => 'Override palette accent color with custom color',
    )));

    // =============================================================================
    // TYPOGRAPHY PANEL
    // =============================================================================

    $wp_customize->add_panel('medspaa_typography', array(
        'title' => '📝 Typography & Fonts',
        'description' => 'Customize fonts and typography throughout your site',
        'priority' => 20,
    ));

    // Primary Font Section
    $wp_customize->add_section('medspaa_primary_font', array(
        'title' => 'Primary Font (Headings)',
        'panel' => 'medspaa_typography',
        'priority' => 10,
    ));

    $wp_customize->add_setting('medspaa_heading_font', array(
        'default' => 'inter',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('medspaa_heading_font', array(
        'label' => 'Heading Font Family',
        'section' => 'medspaa_primary_font',
        'type' => 'select',
        'choices' => array(
            'inter' => 'Inter (Default) - Modern, professional',
            'playfair' => 'Playfair Display - Elegant, luxury serif',
            'montserrat' => 'Montserrat - Clean, geometric sans-serif',
            'lora' => 'Lora - Readable, friendly serif',
            'open_sans' => 'Open Sans - Popular, versatile sans-serif',
            'roboto' => 'Roboto - Google\'s modern sans-serif',
            'source_serif' => 'Source Serif Pro - Professional serif',
            'poppins' => 'Poppins - Rounded, friendly sans-serif',
        ),
    ));

    // Body Font Section
    $wp_customize->add_section('medspaa_body_font', array(
        'title' => 'Body Font (Content)',
        'panel' => 'medspaa_typography',
        'priority' => 20,
    ));

    $wp_customize->add_setting('medspaa_body_font', array(
        'default' => 'inter',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('medspaa_body_font', array(
        'label' => 'Body Font Family',
        'section' => 'medspaa_body_font',
        'type' => 'select',
        'choices' => array(
            'inter' => 'Inter (Default) - Modern, professional',
            'open_sans' => 'Open Sans - Popular, versatile',
            'roboto' => 'Roboto - Google\'s modern sans-serif',
            'lato' => 'Lato - Humanist, friendly',
            'source_sans' => 'Source Sans Pro - Professional sans-serif',
            'nunito' => 'Nunito - Rounded, approachable',
            'system' => 'System Fonts - Fast-loading native fonts',
        ),
    ));

    // Font Size Scale
    $wp_customize->add_setting('medspaa_font_scale', array(
        'default' => 'normal',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('medspaa_font_scale', array(
        'label' => 'Font Size Scale',
        'section' => 'medspaa_body_font',
        'type' => 'radio',
        'choices' => array(
            'small' => 'Small - Compact, more content visible',
            'normal' => 'Normal - Balanced readability',
            'large' => 'Large - Enhanced accessibility',
        ),
    ));

    // =============================================================================
    // LAYOUT & STYLING PANEL
    // =============================================================================

    $wp_customize->add_panel('medspaa_layout', array(
        'title' => '🏗️ Layout & Styling',
        'description' => 'Customize layout options and visual styling',
        'priority' => 30,
    ));

    // Header Style Section
    $wp_customize->add_section('medspaa_header_style', array(
        'title' => 'Header Styling',
        'panel' => 'medspaa_layout',
        'priority' => 10,
    ));

    $wp_customize->add_setting('medspaa_header_style', array(
        'default' => 'transparent',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('medspaa_header_style', array(
        'label' => 'Header Style',
        'section' => 'medspaa_header_style',
        'type' => 'radio',
        'choices' => array(
            'transparent' => 'Transparent (Default) - Overlays hero content',
            'solid' => 'Solid Background - Traditional header bar',
            'gradient' => 'Gradient Background - Modern gradient effect',
        ),
    ));

    // Button Style Section
    $wp_customize->add_section('medspaa_button_style', array(
        'title' => 'Button Styling',
        'panel' => 'medspaa_layout',
        'priority' => 20,
    ));

    $wp_customize->add_setting('medspaa_button_style', array(
        'default' => 'rounded',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('medspaa_button_style', array(
        'label' => 'Button Style',
        'section' => 'medspaa_button_style',
        'type' => 'radio',
        'choices' => array(
            'rounded' => 'Rounded (Default) - Friendly, approachable',
            'sharp' => 'Sharp Corners - Modern, professional',
            'pill' => 'Pill Shape - Fully rounded ends',
        ),
    ));

    // Animation Preferences
    $wp_customize->add_setting('medspaa_animations', array(
        'default' => 'enabled',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('medspaa_animations', array(
        'label' => 'Page Animations',
        'section' => 'medspaa_button_style',
        'type' => 'radio',
        'choices' => array(
            'enabled' => 'Enabled - Smooth hover and transition effects',
            'reduced' => 'Reduced - Minimal animations for accessibility',
            'disabled' => 'Disabled - No animations',
        ),
    ));

    // =============================================================================
    // ADVANCED OPTIONS PANEL
    // =============================================================================

    $wp_customize->add_panel('medspaa_advanced', array(
        'title' => '⚙️ Advanced Options',
        'description' => 'Advanced customization and performance options',
        'priority' => 40,
    ));

    // Performance Section
    $wp_customize->add_section('medspaa_performance', array(
        'title' => 'Performance Options',
        'panel' => 'medspaa_advanced',
        'priority' => 10,
    ));

    $wp_customize->add_setting('medspaa_font_loading', array(
        'default' => 'swap',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('medspaa_font_loading', array(
        'label' => 'Font Loading Strategy',
        'section' => 'medspaa_performance',
        'type' => 'radio',
        'choices' => array(
            'swap' => 'Font Swap (Default) - Fast loading with fallback',
            'block' => 'Font Block - Wait for font to load',
            'fallback' => 'Fallback Only - Use system fonts for speed',
        ),
    ));
}
add_action('customize_register', 'medspaa_theme_customizer_register');

/**
 * Get color palette based on selection
 */
function medspaa_get_color_palette($palette_name = null) {
    if (!$palette_name) {
        $palette_name = get_theme_mod('medspaa_color_palette', 'classic_forest');
    }

    $palettes = array(
        'classic_forest' => array(
            'primary' => '#1B365D',
            'secondary' => '#87A96B',
            'accent' => '#D4AF37',
            'light' => '#F8F9FA',
            'dark' => '#2C3E50',
        ),
        'ocean_blue' => array(
            'primary' => '#2E5266',
            'secondary' => '#52B3D9',
            'accent' => '#C0C0C0',
            'light' => '#F0F8FF',
            'dark' => '#1A3A52',
        ),
        'rose_gold' => array(
            'primary' => '#8B4B6B',
            'secondary' => '#E8B4B8',
            'accent' => '#D4A574',
            'light' => '#FDF2F8',
            'dark' => '#5A2A42',
        ),
        'sage_mint' => array(
            'primary' => '#6B8E6B',
            'secondary' => '#A8C8A8',
            'accent' => '#7FB069',
            'light' => '#F0F8F0',
            'dark' => '#4A634A',
        ),
        'lavender_grey' => array(
            'primary' => '#6B5B95',
            'secondary' => '#B19CD9',
            'accent' => '#C3B1E1',
            'light' => '#F8F7FF',
            'dark' => '#4A4063',
        ),
        'warm_earth' => array(
            'primary' => '#8B5A2B',
            'secondary' => '#D4A574',
            'accent' => '#E76F51',
            'light' => '#FBF7F0',
            'dark' => '#5D3A1A',
        ),
        'modern_mono' => array(
            'primary' => '#2C2C2C',
            'secondary' => '#6B6B6B',
            'accent' => '#E0E0E0',
            'light' => '#FFFFFF',
            'dark' => '#1A1A1A',
        ),
    );

    $palette = $palettes[$palette_name] ?? $palettes['classic_forest'];

    // Allow custom color overrides
    $custom_primary = get_theme_mod('medspaa_primary_color');
    $custom_secondary = get_theme_mod('medspaa_secondary_color');
    $custom_accent = get_theme_mod('medspaa_accent_color');

    if ($custom_primary) $palette['primary'] = $custom_primary;
    if ($custom_secondary) $palette['secondary'] = $custom_secondary;
    if ($custom_accent) $palette['accent'] = $custom_accent;

    return $palette;
}

/**
 * Get font families based on selection
 */
function medspaa_get_font_families() {
    $fonts = array(
        'inter' => "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif",
        'playfair' => "'Playfair Display', 'Times New Roman', serif",
        'montserrat' => "'Montserrat', 'Helvetica Neue', sans-serif",
        'lora' => "'Lora', 'Georgia', serif",
        'open_sans' => "'Open Sans', 'Helvetica', sans-serif",
        'roboto' => "'Roboto', 'Arial', sans-serif",
        'source_serif' => "'Source Serif Pro', 'Times', serif",
        'poppins' => "'Poppins', 'Arial', sans-serif",
        'lato' => "'Lato', 'Helvetica', sans-serif",
        'source_sans' => "'Source Sans Pro', 'Arial', sans-serif",
        'nunito' => "'Nunito', 'Helvetica', sans-serif",
        'system' => "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif",
    );

    return array(
        'heading' => $fonts[get_theme_mod('medspaa_heading_font', 'inter')] ?? $fonts['inter'],
        'body' => $fonts[get_theme_mod('medspaa_body_font', 'inter')] ?? $fonts['inter'],
    );
}

/**
 * Generate Google Fonts URL
 */
function medspaa_get_google_fonts_url() {
    $heading_font = get_theme_mod('medspaa_heading_font', 'inter');
    $body_font = get_theme_mod('medspaa_body_font', 'inter');

    $google_fonts = array(
        'inter' => 'Inter:400,500,600,700',
        'playfair' => 'Playfair+Display:400,500,600,700',
        'montserrat' => 'Montserrat:400,500,600,700',
        'lora' => 'Lora:400,500,600,700',
        'open_sans' => 'Open+Sans:400,500,600,700',
        'roboto' => 'Roboto:400,500,700',
        'source_serif' => 'Source+Serif+Pro:400,600,700',
        'poppins' => 'Poppins:400,500,600,700',
        'lato' => 'Lato:400,700',
        'source_sans' => 'Source+Sans+Pro:400,600,700',
        'nunito' => 'Nunito:400,600,700',
    );

    $fonts_to_load = array();

    if (isset($google_fonts[$heading_font])) {
        $fonts_to_load[] = $google_fonts[$heading_font];
    }

    if (isset($google_fonts[$body_font]) && $body_font !== $heading_font) {
        $fonts_to_load[] = $google_fonts[$body_font];
    }

    if (empty($fonts_to_load)) {
        return '';
    }

    $font_loading = get_theme_mod('medspaa_font_loading', 'swap');
    $display = $font_loading === 'fallback' ? '' : '&display=' . $font_loading;

    return 'https://fonts.googleapis.com/css2?family=' . implode('&family=', $fonts_to_load) . $display;
}

/**
 * Enqueue customizer preview scripts
 */
function medspaa_customizer_preview_scripts() {
    wp_enqueue_script(
        'medspaa-customizer-preview',
        get_template_directory_uri() . '/assets/js/customizer-preview.js',
        array('customize-preview'),
        '1.0.0',
        true
    );
}
add_action('customize_preview_init', 'medspaa_customizer_preview_scripts');

/**
 * Generate dynamic CSS based on customizer settings
 */
function medspaa_generate_dynamic_css() {
    $colors = medspaa_get_color_palette();
    $fonts = medspaa_get_font_families();
    $font_scale = get_theme_mod('medspaa_font_scale', 'normal');
    $button_style = get_theme_mod('medspaa_button_style', 'rounded');
    $header_style = get_theme_mod('medspaa_header_style', 'transparent');
    $animations = get_theme_mod('medspaa_animations', 'enabled');

    // Font scale multipliers
    $scale_multipliers = array(
        'small' => 0.9,
        'normal' => 1.0,
        'large' => 1.1,
    );
    $scale = $scale_multipliers[$font_scale] ?? 1.0;

    // Button border radius
    $button_radius = array(
        'rounded' => '8px',
        'sharp' => '0px',
        'pill' => '50px',
    );
    $radius = $button_radius[$button_style] ?? '8px';

    // Generate CSS
    $css = "
    :root {
        --color-primary: {$colors['primary']};
        --color-secondary: {$colors['secondary']};
        --color-accent: {$colors['accent']};
        --color-light: {$colors['light']};
        --color-dark: {$colors['dark']};

        --font-heading: {$fonts['heading']};
        --font-body: {$fonts['body']};

        --font-scale: {$scale};
        --button-radius: {$radius};
    }

    body {
        font-family: var(--font-body);
        font-size: calc(16px * var(--font-scale));
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: var(--font-heading);
    }

    .btn, button, input[type='submit'], .button {
        border-radius: var(--button-radius);
        background-color: var(--color-primary);
        border-color: var(--color-primary);
    }

    .btn:hover, button:hover, input[type='submit']:hover, .button:hover {
        background-color: var(--color-secondary);
        border-color: var(--color-secondary);
    }
    ";

    // Header styles
    if ($header_style === 'solid') {
        $css .= "
        .site-header {
            background-color: var(--color-primary);
            backdrop-filter: none;
        }
        ";
    } elseif ($header_style === 'gradient') {
        $css .= "
        .site-header {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            backdrop-filter: none;
        }
        ";
    }

    // Animation preferences
    if ($animations === 'disabled') {
        $css .= "
        *, *::before, *::after {
            transition: none !important;
            animation: none !important;
        }
        ";
    } elseif ($animations === 'reduced') {
        $css .= "
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                transition: none !important;
                animation: none !important;
            }
        }
        ";
    }

    return $css;
}

/**
 * Output dynamic CSS
 */
function medspaa_output_dynamic_css() {
    $css = medspaa_generate_dynamic_css();
    if (!empty($css)) {
        echo '<style id="medspaa-dynamic-css">' . $css . '</style>';
    }
}
add_action('wp_head', 'medspaa_output_dynamic_css');

/**
 * Enqueue Google Fonts
 */
function medspaa_enqueue_google_fonts() {
    $font_loading = get_theme_mod('medspaa_font_loading', 'swap');

    if ($font_loading !== 'fallback') {
        $google_fonts_url = medspaa_get_google_fonts_url();
        if (!empty($google_fonts_url)) {
            wp_enqueue_style('medspaa-google-fonts', $google_fonts_url, array(), null);
        }
    }
}
add_action('wp_enqueue_scripts', 'medspaa_enqueue_google_fonts');
