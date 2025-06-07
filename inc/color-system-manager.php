<?php
/**
 * Color System Manager (PHP Backend)
 *
 * Manages color palettes and themes for the Visual Customizer
 * Sprint 2 - PVC-004: WordPress Admin Integration Panel
 *
 * @package MedSpaTheme
 * @subpackage VisualCustomizer
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Color System Manager Class
 */
class ColorSystemManager {

    /**
     * Singleton instance
     * @var ColorSystemManager|null
     */
    private static $instance = null;

    /**
     * Available palettes
     * @var array
     */
    private $palettes = array();

    /**
     * Get singleton instance
     * @return ColorSystemManager
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->load_default_palettes();
    }

    /**
     * Load default color palettes
     */
    private function load_default_palettes() {
        $this->palettes = array(
            'medical-clean' => array(
                'name' => 'Medical Clean',
                'description' => 'Clean, professional medical spa palette',
                'category' => 'medical',
                'colors' => array(
                    'primary' => array('hex' => '#87A96B', 'name' => 'Sage Green'),
                    'secondary' => array('hex' => '#D4AF37', 'name' => 'Premium Gold'),
                    'accent' => array('hex' => '#1B365D', 'name' => 'Medical Navy'),
                    'background' => array('hex' => '#FDFCFA', 'name' => 'Cream Base'),
                    'text' => array('hex' => '#2D2D2D', 'name' => 'Charcoal'),
                    'heading' => array('hex' => '#1B365D', 'name' => 'Medical Navy')
                ),
                'accessibility' => array(
                    'wcag_compliant' => true,
                    'colorblind_friendly' => true,
                    'high_contrast_mode' => false
                )
            ),
            'luxury-spa' => array(
                'name' => 'Luxury Spa',
                'description' => 'Elegant luxury spa environment',
                'category' => 'luxury',
                'colors' => array(
                    'primary' => array('hex' => '#A8C487', 'name' => 'Sage Light'),
                    'secondary' => array('hex' => '#E8C962', 'name' => 'Gold Light'),
                    'accent' => array('hex' => '#2B4A78', 'name' => 'Navy Light'),
                    'background' => array('hex' => '#FBF9F4', 'name' => 'Cream Warm'),
                    'text' => array('hex' => '#333333', 'name' => 'Dark Gray'),
                    'heading' => array('hex' => '#2B4A78', 'name' => 'Navy Light')
                ),
                'accessibility' => array(
                    'wcag_compliant' => true,
                    'colorblind_friendly' => true,
                    'high_contrast_mode' => false
                )
            ),
            'modern-minimal' => array(
                'name' => 'Modern Minimal',
                'description' => 'Clean, minimal modern aesthetic',
                'category' => 'modern',
                'colors' => array(
                    'primary' => array('hex' => '#6B8552', 'name' => 'Sage Dark'),
                    'secondary' => array('hex' => '#C9A96E', 'name' => 'Warm Gold'),
                    'accent' => array('hex' => '#152B4F', 'name' => 'Navy Deep'),
                    'background' => array('hex' => '#FFFFFF', 'name' => 'Pure White'),
                    'text' => array('hex' => '#1A1A1A', 'name' => 'Near Black'),
                    'heading' => array('hex' => '#152B4F', 'name' => 'Navy Deep')
                ),
                'accessibility' => array(
                    'wcag_compliant' => true,
                    'colorblind_friendly' => true,
                    'high_contrast_mode' => true
                )
            )
        );
    }

    /**
     * Get all available palettes
     *
     * @return array Available palettes
     */
    public function get_available_palettes() {
        return $this->palettes;
    }

    /**
     * Get specific palette
     *
     * @param string $palette_id Palette ID
     * @return array|null Palette data or null if not found
     */
    public function get_palette($palette_id) {
        return isset($this->palettes[$palette_id]) ? $this->palettes[$palette_id] : null;
    }

    /**
     * Check if palette exists
     *
     * @param string $palette_id Palette ID
     * @return bool True if palette exists
     */
    public function palette_exists($palette_id) {
        return isset($this->palettes[$palette_id]);
    }

    /**
     * Generate CSS for a specific palette
     *
     * @param string $palette_id Palette ID
     * @return string CSS rules
     */
    public function generate_css_for_palette($palette_id) {
        $palette = $this->get_palette($palette_id);
        if (!$palette) {
            return '';
        }

        $css = ":root {\n";

        // Generate CSS custom properties
        foreach ($palette['colors'] as $role => $color) {
            $css .= "    --color-{$role}: {$color['hex']};\n";
        }

        $css .= "}\n\n";

        // Add utility classes
        $css .= "/* Color Palette Utilities */\n";
        foreach ($palette['colors'] as $role => $color) {
            $css .= ".text-{$role} { color: var(--color-{$role}); }\n";
            $css .= ".bg-{$role} { background-color: var(--color-{$role}); }\n";
            $css .= ".border-{$role} { border-color: var(--color-{$role}); }\n";
        }

        return $css;
    }

    /**
     * Get palettes by category
     *
     * @param string $category Category name
     * @return array Filtered palettes
     */
    public function get_palettes_by_category($category) {
        return array_filter($this->palettes, function($palette) use ($category) {
            return isset($palette['category']) && $palette['category'] === $category;
        });
    }

    /**
     * Add custom palette
     *
     * @param string $palette_id Palette ID
     * @param array $palette_data Palette data
     * @return bool Success
     */
    public function add_palette($palette_id, $palette_data) {
        if (empty($palette_id) || !is_array($palette_data)) {
            return false;
        }

        $this->palettes[$palette_id] = $palette_data;
        return true;
    }

    /**
     * Remove palette
     *
     * @param string $palette_id Palette ID
     * @return bool Success
     */
    public function remove_palette($palette_id) {
        if (!$this->palette_exists($palette_id)) {
            return false;
        }

        unset($this->palettes[$palette_id]);
        return true;
    }

    /**
     * Get palette categories
     *
     * @return array Available categories
     */
    public function get_categories() {
        $categories = array();
        foreach ($this->palettes as $palette) {
            if (isset($palette['category'])) {
                $categories[$palette['category']] = true;
            }
        }
        return array_keys($categories);
    }

    /**
     * Export palette data
     *
     * @param string $palette_id Palette ID
     * @return array|null Exportable palette data
     */
    public function export_palette($palette_id) {
        $palette = $this->get_palette($palette_id);
        if (!$palette) {
            return null;
        }

        return array(
            'version' => '1.0',
            'id' => $palette_id,
            'data' => $palette,
            'exported_at' => current_time('mysql')
        );
    }

    /**
     * Import palette data
     *
     * @param array $import_data Import data
     * @return bool|string Success or error message
     */
    public function import_palette($import_data) {
        if (!is_array($import_data) || !isset($import_data['id'], $import_data['data'])) {
            return 'Invalid import data format';
        }

        $palette_id = sanitize_key($import_data['id']);
        $palette_data = $import_data['data'];

        // Basic validation
        if (empty($palette_id) || !isset($palette_data['colors'])) {
            return 'Invalid palette structure';
        }

        return $this->add_palette($palette_id, $palette_data);
    }

    /**
     * Validate palette data structure
     *
     * @param array $palette_data Palette data
     * @return bool|array True if valid, or array of errors
     */
    public function validate_palette($palette_data) {
        $errors = array();

        if (!isset($palette_data['name'])) {
            $errors[] = 'Palette name is required';
        }

        if (!isset($palette_data['colors']) || !is_array($palette_data['colors'])) {
            $errors[] = 'Colors array is required';
        } else {
            $required_colors = array('primary', 'secondary', 'background', 'text');
            foreach ($required_colors as $color_role) {
                if (!isset($palette_data['colors'][$color_role])) {
                    $errors[] = "Color role '{$color_role}' is required";
                }
            }
        }

        return empty($errors) ? true : $errors;
    }
}
