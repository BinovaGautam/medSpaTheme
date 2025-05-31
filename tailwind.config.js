/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './app/**/*.php',
    './resources/**/*.php',
    './resources/**/*.js',
    './resources/**/*.ts',
    './resources/**/*.jsx',
    './resources/**/*.tsx',
    './resources/**/*.vue',
    './resources/**/*.css',
    './resources/**/*.scss',
    './patterns/**/*.php',
    './theme.json',
  ],
  theme: {
    extend: {
      // Medical Spa Color Palette - WCAG AAA Compliant
      colors: {
        // Primary Brand Colors
        sage: {
          50: '#f7f9f7',
          100: '#eff3ef',
          200: '#dde6dd',
          300: '#c6d4c6',
          400: '#a8bca8',
          500: '#8ba18b',  // Primary sage
          600: '#708070',
          700: '#5d675d',
          800: '#4d554d',
          900: '#3f453f',
          950: '#232623',
        },
        gold: {
          50: '#fefdf6',
          100: '#fdfaeb',
          200: '#faf3c7',
          300: '#f5e89f',
          400: '#efd975',
          500: '#e6c954',  // Primary gold
          600: '#d4b142',
          700: '#b29237',
          800: '#91752f',
          900: '#765f29',
          950: '#443516',
        },
        // Medical Spa Neutral Palette
        cream: {
          50: '#fefefe',
          100: '#fdfdfc',
          200: '#faf9f7',
          300: '#f5f4f1',
          400: '#efede8',
          500: '#e6e3dc',  // Primary cream
          600: '#d1cdc2',
          700: '#b4afa0',
          800: '#928d7c',
          900: '#767064',
          950: '#3d3c34',
        },
        // Medical Professional Colors
        medical: {
          blue: '#003366',    // Deep medical blue
          white: '#ffffff',   // Pure medical white
          gray: '#6b7280',    // Professional gray
          success: '#065f46', // Medical success green
          warning: '#92400e', // Medical warning amber
          error: '#991b1b',   // Medical error red
        },
        // Accessibility & Trust Colors
        trust: {
          primary: '#1e40af',   // Trustworthy blue
          secondary: '#059669', // Reliable green
          accent: '#7c3aed',    // Premium purple
        },
      },

      // Typography System for Luxury Medical Spa
      fontFamily: {
        'heading': ['Playfair Display', 'Georgia', 'serif'],
        'body': ['Inter', 'system-ui', 'sans-serif'],
        'accent': ['Dancing Script', 'cursive'],
        'medical': ['Source Sans Pro', 'system-ui', 'sans-serif'],
      },

      fontSize: {
        // Accessibility-focused sizing (minimum 16px base)
        'xs': ['0.75rem', { lineHeight: '1rem' }],     // 12px
        'sm': ['0.875rem', { lineHeight: '1.25rem' }], // 14px
        'base': ['1rem', { lineHeight: '1.5rem' }],    // 16px - minimum
        'lg': ['1.125rem', { lineHeight: '1.75rem' }], // 18px
        'xl': ['1.25rem', { lineHeight: '1.75rem' }],  // 20px
        '2xl': ['1.5rem', { lineHeight: '2rem' }],     // 24px
        '3xl': ['1.875rem', { lineHeight: '2.25rem' }], // 30px
        '4xl': ['2.25rem', { lineHeight: '2.5rem' }],   // 36px
        '5xl': ['3rem', { lineHeight: '1' }],           // 48px
        '6xl': ['3.75rem', { lineHeight: '1' }],        // 60px

        // Medical Spa Specific Sizes
        'display': ['4rem', { lineHeight: '1.1' }],     // 64px - Hero
        'headline': ['2.5rem', { lineHeight: '1.2' }],   // 40px - Page titles
        'subhead': ['1.5rem', { lineHeight: '1.4' }],    // 24px - Section titles
        'disclaimer': ['0.875rem', { lineHeight: '1.4' }], // 14px - Legal text
      },

      // Luxury Spacing System
      spacing: {
        '18': '4.5rem',   // 72px
        '88': '22rem',    // 352px
        '104': '26rem',   // 416px
        '112': '28rem',   // 448px
        '128': '32rem',   // 512px
        '144': '36rem',   // 576px
      },

      // Medical Spa Layout & Containers
      maxWidth: {
        'container': '1200px',
        'content': '800px',
        'narrow': '600px',
        'treatment-card': '350px',
        'consultation-form': '500px',
      },

      // Animation & Transitions for Luxury Feel
      animation: {
        'fade-in': 'fadeIn 0.6s ease-in-out',
        'slide-up': 'slideUp 0.8s ease-out',
        'scale-in': 'scaleIn 0.4s ease-out',
        'pulse-slow': 'pulse 3s infinite',
        'float': 'float 6s ease-in-out infinite',
      },

      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(20px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        scaleIn: {
          '0%': { transform: 'scale(0.95)', opacity: '0' },
          '100%': { transform: 'scale(1)', opacity: '1' },
        },
        float: {
          '0%, 100%': { transform: 'translateY(0px)' },
          '50%': { transform: 'translateY(-10px)' },
        },
      },

      // Medical Spa Shadows & Effects
      boxShadow: {
        'luxury': '0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
        'medical': '0 4px 14px 0 rgba(0, 0, 0, 0.1)',
        'consultation': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
        'treatment-card': '0 8px 25px -5px rgba(139, 161, 139, 0.2)',
        'elevated': '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
      },

      // Border Radius for Medical Spa Aesthetic
      borderRadius: {
        'luxury': '12px',
        'treatment': '16px',
        'consultation': '20px',
      },

      // Backdrop Blur for Modern Effects
      backdropBlur: {
        'medical': '12px',
      },

      // Medical Spa Specific Gradients
      backgroundImage: {
        'sage-gradient': 'linear-gradient(135deg, #8ba18b 0%, #5d675d 100%)',
        'gold-gradient': 'linear-gradient(135deg, #e6c954 0%, #b29237 100%)',
        'luxury-gradient': 'linear-gradient(135deg, #8ba18b 0%, #e6c954 50%, #d4b142 100%)',
        'medical-gradient': 'linear-gradient(135deg, #f7f9f7 0%, #eff3ef 100%)',
        'trust-gradient': 'linear-gradient(135deg, #1e40af 0%, #059669 100%)',
      },
    },
  },

  plugins: [
    // Medical Spa Custom Utilities
    function({ addUtilities, addComponents, theme }) {
      // HIPAA Compliance Utilities
      addUtilities({
        '.hipaa-secure': {
          borderLeft: `4px solid ${theme('colors.trust.primary')}`,
          backgroundColor: theme('colors.blue.50'),
          padding: theme('spacing.4'),
          borderRadius: theme('borderRadius.md'),
        },
        '.patient-privacy': {
          backgroundColor: theme('colors.cream.100'),
          border: `1px solid ${theme('colors.cream.300')}`,
          borderRadius: theme('borderRadius.luxury'),
          padding: theme('spacing.3'),
        },
      });

      // Medical Spa Components
      addComponents({
        '.treatment-card': {
          backgroundColor: theme('colors.white'),
          borderRadius: theme('borderRadius.treatment'),
          boxShadow: theme('boxShadow.treatment-card'),
          overflow: 'hidden',
          transition: 'all 0.3s ease-in-out',
          '&:hover': {
            transform: 'translateY(-4px)',
            boxShadow: theme('boxShadow.luxury'),
          },
        },
        '.consultation-cta': {
          background: theme('backgroundImage.gold-gradient'),
          color: theme('colors.white'),
          fontWeight: theme('fontWeight.semibold'),
          padding: `${theme('spacing.3')} ${theme('spacing.8')}`,
          borderRadius: theme('borderRadius.luxury'),
          boxShadow: theme('boxShadow.medical'),
          transition: 'all 0.3s ease-in-out',
          '&:hover': {
            boxShadow: theme('boxShadow.elevated'),
            transform: 'translateY(-2px)',
          },
        },
        '.luxury-container': {
          maxWidth: theme('maxWidth.container'),
          marginLeft: 'auto',
          marginRight: 'auto',
          paddingLeft: theme('spacing.4'),
          paddingRight: theme('spacing.4'),
          '@media (min-width: 640px)': {
            paddingLeft: theme('spacing.6'),
            paddingRight: theme('spacing.6'),
          },
          '@media (min-width: 1024px)': {
            paddingLeft: theme('spacing.8'),
            paddingRight: theme('spacing.8'),
          },
        },
        '.medical-form': {
          backgroundColor: theme('colors.white'),
          borderRadius: theme('borderRadius.consultation'),
          boxShadow: theme('boxShadow.consultation'),
          padding: theme('spacing.8'),
          border: `1px solid ${theme('colors.cream.200')}`,
        },
        '.accessibility-focus': {
          '&:focus': {
            outline: `3px solid ${theme('colors.trust.primary')}`,
            outlineOffset: '2px',
            boxShadow: `0 0 0 1px ${theme('colors.white')}`,
          },
        },
      });

      // Performance & Accessibility Utilities
      addUtilities({
        '.reduce-motion': {
          '@media (prefers-reduced-motion: reduce)': {
            animation: 'none !important',
            transition: 'none !important',
          },
        },
        '.high-contrast': {
          '@media (prefers-contrast: high)': {
            borderWidth: '2px',
            fontWeight: theme('fontWeight.semibold'),
          },
        },
        '.lazy-load': {
          loading: 'lazy',
          decoding: 'async',
        },
      });
    },
  ],

  // Performance Optimizations
  corePlugins: {
    container: false, // Using custom luxury-container
  },

  // Medical Spa Specific Safelist
  safelist: [
    'hipaa-secure',
    'patient-privacy',
    'treatment-card',
    'consultation-cta',
    'medical-form',
    'accessibility-focus',
    'reduce-motion',
    'high-contrast',
    // Ensure medical critical classes are never purged
    'text-medical-blue',
    'bg-medical-white',
    'border-trust-primary',
    'shadow-medical',
    'rounded-luxury',
  ],
}
