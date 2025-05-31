# PreetiDreams Medical Spa Theme

Premium WordPress theme for luxury medical spas with HIPAA-conscious design, treatment management, and accessibility compliance.

## ✨ Features

- **Medical Spa Focused**: Designed specifically for aesthetic clinics, medical spas, and wellness centers
- **HIPAA-Conscious Design**: Privacy controls and secure content handling
- **WCAG AAA Accessibility**: Full compliance with accessibility standards
- **Modern Tech Stack**: Built with Tailwind CSS v4, TypeScript, and modern build tools
- **Performance Optimized**: <2s load times with Core Web Vitals optimization
- **Responsive Design**: Mobile-first approach for all device types

## 🚀 Quick Installation

### Method 1: Basic Installation (Recommended)

1. **Upload Theme Files**
   ```bash
   # Navigate to your WordPress themes directory
   cd /path/to/wordpress/wp-content/themes/
   
   # Copy or clone the theme
   cp -r medSpaTheme/ your-site-themes/
   ```

2. **Activate in WordPress**
   - Go to WordPress Admin → Appearance → Themes
   - Find "PreetiDreams Medical Spa Theme"
   - Click "Activate"

3. **Done!** The theme will work with fallback functionality

### Method 2: Full Development Setup

For developers who want full functionality:

```bash
# Navigate to theme directory
cd wp-content/themes/medSpaTheme/

# Install development dependencies (requires PHP 8.1+)
composer run install-dev

# Install Sage/Acorn for advanced features (requires PHP 8.2+)
composer run install-sage

# Install Node.js dependencies
yarn install

# Build assets
yarn build
```

## 📋 Requirements

### Minimum Requirements
- **WordPress**: 6.0+
- **PHP**: 8.0+
- **Web Server**: Apache/Nginx with mod_rewrite

### Recommended for Full Features
- **PHP**: 8.2+ (for Sage/Acorn integration)
- **Node.js**: 18+ (for development)
- **Yarn**: Latest (NPM is not supported)

## 🔧 Troubleshooting

### "Composer detected issues in your platform" Error

This error occurs when trying to install advanced dependencies. **The theme will work without them!**

**Solution 1 - Use Basic Mode (Recommended)**:
- Simply activate the theme in WordPress
- The theme includes fallback functionality
- All core features work without Composer dependencies

**Solution 2 - Install Dependencies (Optional)**:
```bash
# For PHP 8.1+ systems
composer run install-dev

# For PHP 8.2+ systems  
composer run install-sage
```

**Solution 3 - Ignore the Error**:
- The theme is designed to work with or without Composer
- Advanced features will be disabled but core functionality remains

### Theme Doesn't Appear in WordPress

1. **Check File Permissions**:
   ```bash
   chmod -R 755 wp-content/themes/medSpaTheme/
   ```

2. **Verify Required Files**:
   - `style.css` (with theme header)
   - `index.php`
   - `functions.php`

3. **Check PHP Version**:
   ```bash
   php -v  # Should be 8.0+
   ```

## 🎨 Customization

### WordPress Customizer

1. Go to **Appearance → Customize**
2. Navigate to **Medical Spa Settings**:
   - Set phone number
   - Add clinic address
   - Configure contact information

### Menu Setup

1. **Create Menus**:
   - Go to **Appearance → Menus**
   - Create "Primary Menu" and "Footer Menu"
   - Assign to theme locations

2. **Recommended Menu Structure**:
   ```
   Primary Menu:
   - Home
   - Treatments
   - About
   - Before & After
   - Contact
   
   Footer Menu:
   - Privacy Policy
   - Terms of Service
   - Accessibility Statement
   ```

### Widget Areas

The theme includes footer widget areas:
- Go to **Appearance → Widgets**
- Add widgets to "Footer Widgets"

## 🏥 Medical Spa Features

### Treatment Management
- Custom post types for treatments
- Before/after gallery support
- HIPAA-compliant image handling

### Consultation Booking
- Contact forms with privacy controls
- HIPAA consent management
- Medical disclaimer support

### Accessibility Features
- WCAG AAA compliance
- Screen reader optimization
- Keyboard navigation
- High contrast support
- Reduced motion support

## 🔨 Development

### Build System

```bash
# Development mode with hot reload
yarn dev

# Production build
yarn build

# Preview build
yarn preview
```

### Code Standards

The theme follows industry best practices:
- PSR-12 PHP standards
- ES2022+ JavaScript
- BEM CSS methodology
- SOLID design principles

### Testing

```bash
# Run PHP tests (requires dev dependencies)
composer test

# Run linting
composer lint

# Fix code style
composer format
```

## 📚 Documentation

### Coding Standards
- See `rules/code-standards.mdc` for complete guidelines
- All code generation must follow medical spa compliance
- HIPAA-conscious development patterns required

### Package Management
- See `rules/package-management.mdc` for dependency guidelines
- Yarn only - NPM is forbidden
- Security-first approach to dependencies

## 🆘 Support

### Getting Help

1. **Check Documentation**: Review files in `rules/` directory
2. **WordPress Forums**: Search WordPress.org support forums
3. **GitHub Issues**: Report bugs on the theme repository
4. **Email Support**: support@preetidreams.com

### Common Issues

**Theme appears broken after activation**:
- Clear any caching plugins
- Check PHP error logs
- Verify file permissions

**Styles not loading**:
- Run `yarn build` if using development setup
- Check if web server serves static files
- Verify file paths in browser developer tools

**Advanced features missing**:
- Install Composer dependencies: `composer run install-sage`
- Requires PHP 8.2+ for full functionality
- Basic features work without dependencies

## 📄 License

MIT License - see LICENSE file for details.

## 🏆 Credits

- **Theme**: PreetiDreams Development Team
- **Framework**: WordPress + Sage (optional)
- **Styling**: Tailwind CSS v4
- **Icons**: Medical spa icon set
- **Typography**: System fonts + Google Fonts

---

**Ready to create your luxury medical spa website?** 

Activate the theme and start customizing your medical spa's online presence with our HIPAA-conscious, accessibility-first design system. 
