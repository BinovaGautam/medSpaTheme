<?php
/**
 * Visual Customizer AJAX Handlers
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Visual Customizer AJAX Handler Class
 */
class VisualCustomizerAJAXHandlers {

    private static $instance = null;
    private $color_system;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->init_hooks();
        $this->load_dependencies();
    }

    private function init_hooks() {
        // AJAX handlers for logged-in users
        add_action('wp_ajax_visual_customizer_update_palette', array($this, 'handle_update_palette'));
        add_action('wp_ajax_visual_customizer_preview_palette', array($this, 'handle_preview_palette'));
        add_action('wp_ajax_visual_customizer_get_palettes', array($this, 'handle_get_palettes'));
        add_action('wp_ajax_visual_customizer_validate_accessibility', array($this, 'handle_validate_accessibility'));

        // AJAX handlers for preview (may include non-logged-in users in customizer)
        add_action('wp_ajax_nopriv_visual_customizer_preview_palette', array($this, 'handle_preview_palette'));
    }

    private function load_dependencies() {
        if (!class_exists('ColorSystemManager')) {
            require_once get_template_directory() . '/inc/color-system-manager.php';
        }
        $this->color_system = ColorSystemManager::get_instance();
    }

    /**
     * Handle palette update request
     */
    public function handle_update_palette() {
        // Verify nonce and capabilities
        if (!$this->verify_request('visual_customizer_update', 'edit_theme_options')) {
            wp_die();
        }

        $palette_id = sanitize_text_field($_POST['palette_id'] ?? '');

        if (empty($palette_id)) {
            wp_send_json_error(array(
                'message' => __('Invalid palette ID', 'medspa-theme')
            ));
        }

        // Validate palette exists
        if (!$this->color_system || !$this->color_system->palette_exists($palette_id)) {
            wp_send_json_error(array(
                'message' => __('Palette not found', 'medspa-theme')
            ));
        }

        // Update theme mod
        set_theme_mod('visual_customizer_active_palette', $palette_id);

        // Get updated palette data
        $palette_data = $this->color_system->get_palette($palette_id);
        $css = $this->color_system->generate_css_for_palette($palette_id);

        wp_send_json_success(array(
            'message' => __('Palette updated successfully', 'medspa-theme'),
            'palette' => $palette_data,
            'css' => $css,
            'palette_id' => $palette_id
        ));
    }

    /**
     * Handle palette preview request
     */
    public function handle_preview_palette() {
        // Verify nonce for preview
        if (!$this->verify_preview_request()) {
            wp_die();
        }

        $palette_id = sanitize_text_field($_POST['palette_id'] ?? '');

        if (empty($palette_id)) {
            wp_send_json_error(array(
                'message' => __('Invalid palette ID', 'medspa-theme')
            ));
        }

        if (!$this->color_system || !$this->color_system->palette_exists($palette_id)) {
            wp_send_json_error(array(
                'message' => __('Palette not found', 'medspa-theme')
            ));
        }

        // Generate preview data
        $palette_data = $this->color_system->get_palette($palette_id);
        $css = $this->color_system->generate_css_for_palette($palette_id);
        $accessibility_score = $this->calculate_accessibility_score($palette_data);

        wp_send_json_success(array(
            'palette' => $palette_data,
            'css' => $css,
            'accessibility_score' => $accessibility_score,
            'contrast_ratios' => $this->get_contrast_ratios($palette_data),
            'preview_mode' => true
        ));
    }

    /**
     * Handle get palettes request
     */
    public function handle_get_palettes() {
        // Verify nonce and capabilities
        if (!$this->verify_request('visual_customizer_get_palettes', 'edit_theme_options')) {
            wp_die();
        }

        $category = sanitize_text_field($_POST['category'] ?? 'all');
        $search = sanitize_text_field($_POST['search'] ?? '');

        if (!$this->color_system) {
            wp_send_json_error(array(
                'message' => __('Color system not available', 'medspa-theme')
            ));
        }

        $palettes = $this->color_system->get_available_palettes();

        // Filter by category if specified
        if ($category !== 'all') {
            $palettes = array_filter($palettes, function($palette) use ($category) {
                return isset($palette['category']) && $palette['category'] === $category;
            });
        }

        // Filter by search if specified
        if (!empty($search)) {
            $palettes = array_filter($palettes, function($palette) use ($search) {
                $name = isset($palette['name']) ? $palette['name'] : '';
                $description = isset($palette['description']) ? $palette['description'] : '';
                return stripos($name, $search) !== false || stripos($description, $search) !== false;
            });
        }

        wp_send_json_success(array(
            'palettes' => $palettes,
            'total' => count($palettes),
            'category' => $category,
            'search' => $search
        ));
    }

    /**
     * Handle accessibility validation request
     */
    public function handle_validate_accessibility() {
        // Verify nonce and capabilities
        if (!$this->verify_request('visual_customizer_accessibility', 'edit_theme_options')) {
            wp_die();
        }

        $palette_id = sanitize_text_field($_POST['palette_id'] ?? '');

        if (empty($palette_id)) {
            wp_send_json_error(array(
                'message' => __('Invalid palette ID', 'medspa-theme')
            ));
        }

        if (!$this->color_system || !$this->color_system->palette_exists($palette_id)) {
            wp_send_json_error(array(
                'message' => __('Palette not found', 'medspa-theme')
            ));
        }

        $palette_data = $this->color_system->get_palette($palette_id);
        $accessibility_report = $this->generate_accessibility_report($palette_data);

        wp_send_json_success(array(
            'report' => $accessibility_report,
            'score' => $accessibility_report['overall_score'],
            'recommendations' => $accessibility_report['recommendations'],
            'compliance_level' => $this->get_compliance_level($accessibility_report['overall_score'])
        ));
    }

    /**
     * Verify AJAX request with nonce and capability check
     *
     * @param string $action Nonce action
     * @param string $capability Required capability
     * @return bool
     */
    private function verify_request($action, $capability = 'edit_theme_options') {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], $action)) {
            return false;
        }

        // Check capability
        if (!current_user_can($capability)) {
            return false;
        }

        return true;
    }

    /**
     * Verify preview request (less strict for customizer preview)
     *
     * @return bool
     */
    private function verify_preview_request() {
        // Check if this is a customizer preview request
        if (isset($_POST['customize_preview_nonce'])) {
            return wp_verify_nonce($_POST['customize_preview_nonce'], 'preview-customize_' . wp_get_theme()->get_stylesheet());
        }

        // Standard nonce check
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'visual_customizer_preview')) {
            return false;
        }

        return true;
    }

    /**
     * Calculate accessibility score for a palette
     *
     * @param array $palette_data Palette data
     * @return int Accessibility score (0-100)
     */
    private function calculate_accessibility_score($palette_data) {
        if (!isset($palette_data['colors']) || !is_array($palette_data['colors'])) {
            return 0;
        }

        $score = 0;
        $max_score = 100;

        // Check contrast ratios (40 points)
        $contrast_score = $this->calculate_contrast_score($palette_data);
        $score += ($contrast_score / 100) * 40;

        // Check color diversity (20 points)
        $diversity_score = $this->calculate_color_diversity_score($palette_data);
        $score += ($diversity_score / 100) * 20;

        // Check accessibility features (40 points)
        $features_score = $this->calculate_accessibility_features_score($palette_data);
        $score += ($features_score / 100) * 40;

        return min(100, max(0, round($score)));
    }

    /**
     * Calculate contrast score for palette
     *
     * @param array $palette_data Palette data
     * @return int Score (0-100)
     */
    private function calculate_contrast_score($palette_data) {
        $colors = $palette_data['colors'];
        $total_checks = 0;
        $passed_checks = 0;

        // Check common color combinations
        $combinations = array(
            array('primary', 'background'),
            array('secondary', 'background'),
            array('text', 'background'),
            array('heading', 'background'),
            array('accent', 'background')
        );

        foreach ($combinations as $combo) {
            if (isset($colors[$combo[0]]) && isset($colors[$combo[1]])) {
                $total_checks++;
                $contrast = $this->calculate_contrast_ratio($colors[$combo[0]]['hex'], $colors[$combo[1]]['hex']);
                if ($contrast >= 4.5) {
                    $passed_checks++;
                }
            }
        }

        return $total_checks > 0 ? round(($passed_checks / $total_checks) * 100) : 0;
    }

    /**
     * Calculate color diversity score
     *
     * @param array $palette_data Palette data
     * @return int Score (0-100)
     */
    private function calculate_color_diversity_score($palette_data) {
        $colors = $palette_data['colors'];
        $unique_hues = array();

        foreach ($colors as $color) {
            if (isset($color['hex'])) {
                $hsl = $this->hex_to_hsl($color['hex']);
                $hue_range = floor($hsl[0] / 30); // Group hues into 30-degree ranges
                $unique_hues[$hue_range] = true;
            }
        }

        $diversity_count = count($unique_hues);
        return min(100, ($diversity_count / 6) * 100); // Max score for 6+ different hue ranges
    }

    /**
     * Calculate accessibility features score
     *
     * @param array $palette_data Palette data
     * @return int Score (0-100)
     */
    private function calculate_accessibility_features_score($palette_data) {
        $score = 0;

        // Check if palette has accessibility metadata
        if (isset($palette_data['accessibility'])) {
            $score += 25;

            if (isset($palette_data['accessibility']['wcag_compliant']) && $palette_data['accessibility']['wcag_compliant']) {
                $score += 25;
            }

            if (isset($palette_data['accessibility']['colorblind_friendly']) && $palette_data['accessibility']['colorblind_friendly']) {
                $score += 25;
            }

            if (isset($palette_data['accessibility']['high_contrast_mode']) && $palette_data['accessibility']['high_contrast_mode']) {
                $score += 25;
            }
        }

        return $score;
    }

    /**
     * Get contrast ratios for palette
     *
     * @param array $palette_data Palette data
     * @return array Contrast ratios
     */
    private function get_contrast_ratios($palette_data) {
        $colors = $palette_data['colors'];
        $ratios = array();

        if (isset($colors['background'])) {
            $bg_color = $colors['background']['hex'];

            foreach ($colors as $role => $color) {
                if ($role !== 'background' && isset($color['hex'])) {
                    $ratios[$role] = $this->calculate_contrast_ratio($color['hex'], $bg_color);
                }
            }
        }

        return $ratios;
    }

    /**
     * Generate accessibility report
     *
     * @param array $palette_data Palette data
     * @return array Accessibility report
     */
    private function generate_accessibility_report($palette_data) {
        $score = $this->calculate_accessibility_score($palette_data);
        $contrast_ratios = $this->get_contrast_ratios($palette_data);
        $recommendations = array();

        // Generate recommendations based on contrast ratios
        foreach ($contrast_ratios as $role => $ratio) {
            if ($ratio < 4.5) {
                $recommendations[] = sprintf(
                    __('Improve contrast for %s text (current: %.1f:1, recommended: 4.5:1)', 'medspa-theme'),
                    ucfirst($role),
                    $ratio
                );
            }
        }

        if (empty($recommendations)) {
            $recommendations[] = __('All contrast ratios meet WCAG AA standards', 'medspa-theme');
        }

        return array(
            'overall_score' => $score,
            'contrast_ratios' => $contrast_ratios,
            'recommendations' => $recommendations,
            'wcag_aa_compliant' => $score >= 70,
            'wcag_aaa_compliant' => $score >= 85,
            'generated_at' => current_time('mysql')
        );
    }

    /**
     * Get compliance level based on score
     *
     * @param int $score Accessibility score
     * @return string Compliance level
     */
    private function get_compliance_level($score) {
        if ($score >= 85) {
            return 'WCAG AAA';
        } elseif ($score >= 70) {
            return 'WCAG AA';
        } elseif ($score >= 50) {
            return 'WCAG A';
        } else {
            return 'Non-compliant';
        }
    }

    /**
     * Calculate contrast ratio between two colors
     *
     * @param string $color1 Hex color
     * @param string $color2 Hex color
     * @return float Contrast ratio
     */
    private function calculate_contrast_ratio($color1, $color2) {
        $l1 = $this->get_relative_luminance($color1);
        $l2 = $this->get_relative_luminance($color2);

        $lighter = max($l1, $l2);
        $darker = min($l1, $l2);

        return ($lighter + 0.05) / ($darker + 0.05);
    }

    /**
     * Get relative luminance of a color
     *
     * @param string $hex_color Hex color
     * @return float Relative luminance
     */
    private function get_relative_luminance($hex_color) {
        $rgb = $this->hex_to_rgb($hex_color);

        $r = $rgb[0] / 255;
        $g = $rgb[1] / 255;
        $b = $rgb[2] / 255;

        $r = ($r <= 0.03928) ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
        $g = ($g <= 0.03928) ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
        $b = ($b <= 0.03928) ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);

        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }

    /**
     * Convert hex color to RGB
     *
     * @param string $hex_color Hex color
     * @return array RGB values
     */
    private function hex_to_rgb($hex_color) {
        $hex = ltrim($hex_color, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        return array(
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        );
    }

    /**
     * Convert hex color to HSL
     *
     * @param string $hex_color Hex color
     * @return array HSL values
     */
    private function hex_to_hsl($hex_color) {
        $rgb = $this->hex_to_rgb($hex_color);
        $r = $rgb[0] / 255;
        $g = $rgb[1] / 255;
        $b = $rgb[2] / 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $diff = $max - $min;

        $l = ($max + $min) / 2;

        if ($diff === 0) {
            $h = $s = 0;
        } else {
            $s = $l > 0.5 ? $diff / (2 - $max - $min) : $diff / ($max + $min);

            switch ($max) {
                case $r:
                    $h = (($g - $b) / $diff) + ($g < $b ? 6 : 0);
                    break;
                case $g:
                    $h = ($b - $r) / $diff + 2;
                    break;
                case $b:
                    $h = ($r - $g) / $diff + 4;
                    break;
            }
            $h /= 6;
        }

        return array($h * 360, $s * 100, $l * 100);
    }
}

// Initialize AJAX handlers
function initialize_visual_customizer_ajax_handlers() {
    return VisualCustomizerAJAXHandlers::get_instance();
}
add_action('init', 'initialize_visual_customizer_ajax_handlers');
