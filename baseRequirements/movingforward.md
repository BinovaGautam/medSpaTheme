# PreetiDreams - WordPress Theme Development Requirements for Coding Agent

This document contains the distilled technical and plugin bundling requirements derived from the full specifications of the PreetiDreams - Luxury Medical Spa WordPress theme. The coding agent must follow these requirements to ensure the theme is functional, modular, scalable, and professional.

---

## 📁 File Structure & Base Setup

- Base on `Underscores` (`_s`) or `Sage` starter theme (recommended: `Sage` for Blade templating and modern tooling).
- Use Tailwind CSS for design consistency, installed via theme's build pipeline (Laravel Mix/Vite).
- Modular file architecture:
  - `/template-parts`
  - `/components`
  - `/acf-blocks` (for Gutenberg block registration)
  - `/assets` (JS/CSS/images)
  - `/inc` (core PHP logic)
- Theme setup must include WordPress customizer & Gutenberg block editor support.

---

## 🧱 Core WordPress Integrations

- **Custom Post Types (CPTs)**:
  - `treatment`
  - `before_after`
  - `testimonial`
  - `staff`
- **Custom Taxonomies**:
  - `treatment_category` (hierarchical)
- Use **ACF Pro** for all meta fields and custom content control.
- Include a `Theme Options` panel using ACF Pro options page.

---

## 🔌 Required Plugins to Bundle (Install Automatically with TGMPA or similar)

| Plugin | Purpose |
|--------|---------|
| Advanced Custom Fields Pro | Meta fields, blocks, flexible content |
| Custom Post Type UI (optional if not hardcoded) | CPT creation (dev/debug phase) |
| WPForms Lite (or Gravity Forms if licensed) | Consultation forms |
| Smash Balloon Instagram Feed | Embed Instagram showcase |
| Complianz | GDPR cookie and privacy compliance |
| Kirki Customizer Framework (optional) | Extended Customizer options |
| Safe SVG | Allow SVG uploads |
| WP Rocket (optional) | Performance optimization |
| Regenerate Thumbnails | Image regeneration |
| Redux Framework (optional) | Theme settings panel (alternative to ACF) |

---

## 🎨 Design Consistency

- Tailwind-based utility-first design system.
- Typography system configured in `tailwind.config.js`:
  - `heading`: elegant serif (e.g., Playfair Display)
  - `body`: clean sans-serif (e.g., Inter or Open Sans)
  - `accent`: script font (optional, loaded via Google Fonts or local)
- Layout Grid System: Responsive 12-column grid via Tailwind plugins.
- WCAG AAA-compliant color scheme and contrast.

---

## 🚀 Functional Components

- Treatment Grid and Detail Templates
- Interactive Quiz (via Vue/React in shortcode or Gutenberg block)
- Before/After Gallery with filtering and lightbox
- Testimonial slider (Swiper.js)
- Virtual consultation multi-step form
- Hero section with video/image background
- Admin dashboard enhancements via ACF fields
- Sticky nav, mega menu, scroll animations (AOS or custom Intersection Observer)

---

## 📱 Mobile & Performance

- Mobile-first layout and touch interaction standards
- Image optimization via `srcset` and lazy loading (native or Lozad.js)
- Lighthouse score 90+ target on all pages
- JS should be modularized and only load conditionally

---

## 🛡️ Accessibility & Compliance

- Keyboard navigation, ARIA labels, semantic HTML5
- ACF-based consent toggle for image privacy (Before/After gallery)
- All forms must include GDPR + optional HIPAA notice checkbox
- Respect OS-level `prefers-reduced-motion`

---

## 🌐 SEO & Analytics

- JSON-LD structured data for:
  - LocalBusiness > MedicalBusiness > HealthAndBeautyBusiness > DaySpa
- Integration hook for GA4 / Meta Pixel via Theme Options
- Meta tags and Open Graph support via Yoast SEO compatibility

---

## 📊 Admin UX

- Admin columns for CPTs (e.g., Service Category, Price)
- Dashboard widget for recent consultation requests
- ACF repeater fields for gallery uploads
- Admin notices for missing plugin dependencies

---

## 📦 Export Deliverables

- `preetidreams.zip` - final theme zip
- `/documentation/` - full installation and setup guide
- `theme-demo-content.xml` - optional demo import XML
- `theme-config.json` - ACF field groups and theme settings
- Plugin Bundles (TGMPA or custom) for required plugins above

---

## 📌 Notes for Coding Agent

- All plugin dependencies must be declared in `functions.php` using [TGMPA](http://tgmpluginactivation.com/).
- ACF field exports and `theme options` setup must be included in the theme.
- Ensure reusable template partials for CPT loops and hero blocks.
- Include script enqueueing strategy for optimal performance.

---

**Please start by scaffolding the theme with ACF Pro, Tailwind CSS, CPTs, and core design system. All plugin dependencies must be bundled or required for install after theme activation.**