<?php
/**
 * MedSpa CSS Generator
 *
 * Generates dynamic CSS for color palettes
 * Sprint 4 - Industry-Standard WordPress Customizer Architecture
 * Epic 2: File-Based CSS Architecture
 *
 * @package MedSpaTheme
 * @subpackage Customizer
 * @since 2.0.0
 * @author Sprint 4 Implementation Team
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * MedSpa CSS Generator Class
 *
 * Generates CSS files for color palettes with proper versioning and cache busting.
 */
class MedSpa_CSS_Generator {

    /**
     * Upload directory
     * @var string
     */
    private $upload_dir;

    /**
     * CSS directory
     * @var string
     */
    private $css_dir;

    /**
     * Constructor
     */
    public function __construct() {
        $upload = wp_upload_dir();
        $this->upload_dir = $upload['basedir'];
        $this->css_dir = $this->upload_dir . '/medspatheme/';

        $this->ensure_directory_exists();
    }

    /**
     * Ensure CSS directory exists
     */
    private function ensure_directory_exists() {
        if (!file_exists($this->css_dir)) {
            wp_mkdir_p($this->css_dir);
        }
    }

    /**
     * Generate CSS file for palette
     *
     * @param string $palette_name Palette name
     * @return bool Success status
     */
    public function generate_palette_css($palette_name) {
        $palette_config = $this->get_palette_config($palette_name);
        if (!$palette_config) {
            return false;
        }

        $css_content = $this->build_css_from_palette($palette_config);
        return $this->write_css_file($css_content, $palette_name);
    }

    /**
     * Generate inline CSS for fallback
     *
     * @param string $palette_name Palette name
     * @return string CSS content
     */
    public function generate_inline_css($palette_name) {
        $palette_config = $this->get_palette_config($palette_name);
        if (!$palette_config) {
            return '';
        }

        return $this->build_css_from_palette($palette_config);
    }

    /**
     * Get critical CSS for above-fold content
     *
     * @param string $palette_name Palette name
     * @return string Critical CSS
     */
    public function get_critical_css($palette_name) {
        $palette_config = $this->get_palette_config($palette_name);
        if (!$palette_config || !isset($palette_config['colors'])) {
            return '';
        }

        $colors = $palette_config['colors'];

        $css = ":root {\n";
        foreach ($colors as $property => $value) {
            $css .= "    --{$property}: {$value};\n";
        }
        $css .= "}\n";

        return $css;
    }

    /**
     * Get palette configuration
     *
     * @param string $palette_name Palette name
     * @return array|false Palette configuration or false
     */
    private function get_palette_config($palette_name) {
        // Try to get from Color System Manager first
        if (class_exists('ColorSystemManager')) {
            $color_system = ColorSystemManager::get_instance();
            $palettes = $color_system->get_available_palettes();

            if (isset($palettes[$palette_name])) {
                return $palettes[$palette_name];
            }
        }

        // Fallback to default configurations
        return $this->get_default_palette_config($palette_name);
    }

    /**
     * Get default palette configuration
     *
     * @param string $palette_name Palette name
     * @return array|false Default palette configuration
     */
    private function get_default_palette_config($palette_name) {
        $default_palettes = array(
            'professional-trust' => array(
                'name' => 'Professional Trust',
                'colors' => array(
                    'color-primary-navy'    => '#1B365D',
                    'color-brand-primary'   => '#87A96B',
                    'color-brand-accent'    => '#D4AF37',
                    'color-secondary-text'  => '#666666',
                    'color-muted-text'      => '#888888',
                ),
            ),
            'modern-clinical' => array(
                'name' => 'Modern Clinical',
                'colors' => array(
                    'color-primary-navy'    => '#2C3E50',
                    'color-brand-primary'   => '#3498DB',
                    'color-brand-accent'    => '#E74C3C',
                    'color-secondary-text'  => '#7F8C8D',
                    'color-muted-text'      => '#95A5A6',
                ),
            ),
        );

        return isset($default_palettes[$palette_name]) ? $default_palettes[$palette_name] : false;
    }

    /**
     * Build CSS from palette configuration
     *
     * @param array $palette_config Palette configuration
     * @return string CSS content
     */
    private function build_css_from_palette($palette_config) {
        if (!isset($palette_config['colors'])) {
            return '';
        }

        $css = ":root {\n";
        foreach ($palette_config['colors'] as $property => $value) {
            $css .= "    --{$property}: {$value};\n";
        }
        $css .= "}\n\n";

        // Add component-specific CSS
        $css .= $this->generate_component_css($palette_config['colors']);

        return $css;
    }

    /**
     * Generate component-specific CSS
     *
     * @param array $colors Color configuration
     * @return string Component CSS
     */
    private function generate_component_css($colors) {
        $css = "/* Component Styles */\n";

        // Button styles
        if (isset($colors['color-primary-navy'])) {
            $css .= ".btn-primary { background-color: var(--color-primary-navy); }\n";
        }

        // Link styles
        if (isset($colors['color-brand-primary'])) {
            $css .= "a { color: var(--color-brand-primary); }\n";
        }

        // Text styles
        if (isset($colors['color-secondary-text'])) {
            $css .= ".secondary-text { color: var(--color-secondary-text); }\n";
        }

        if (isset($colors['color-muted-text'])) {
            $css .= ".muted-text { color: var(--color-muted-text); }\n";
        }

        return $css;
    }

    /**
     * Write CSS file with versioning
     *
     * @param string $content CSS content
     * @param string $palette_name Palette name
     * @return bool Success status
     */
    private function write_css_file($content, $palette_name) {
        $timestamp = time();
        $filename = "dynamic-style-v{$timestamp}.css";
        $filepath = $this->css_dir . $filename;

        $result = file_put_contents($filepath, $content);

        if ($result !== false) {
            // Update option with current file info
            update_option('medspa_dynamic_css_file', array(
                'path' => $filepath,
                'url'  => str_replace(ABSPATH, home_url('/'), $filepath),
                'version' => $timestamp,
                'palette' => $palette_name,
            ));

            // Cleanup old files (keep only latest 5)
            $this->cleanup_old_files();

            return true;
        }

        return false;
    }

    /**
     * Cleanup old CSS files
     */
    private function cleanup_old_files() {
        $files = glob($this->css_dir . 'dynamic-style-v*.css');

        if (!$files || count($files) <= 5) {
            return;
        }

        // Sort by modification time (newest first)
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        // Keep only the latest 5 files, delete the rest
        $files_to_delete = array_slice($files, 5);

        foreach ($files_to_delete as $file) {
            @unlink($file);
        }
    }
}
