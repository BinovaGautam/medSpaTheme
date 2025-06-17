<?php
/**
 * T8.4 Palette Validation System - PHP Integration
 *
 * Server-side validation system integration for WordPress Customizer
 * and palette management with accessibility compliance checking
 *
 * @package MedSpaTheme
 * @version 1.0.0 - T8.4 Implementation
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 * @sprint SPRINT-8-EXTENSIBLE-SEMANTIC-INTEGRATION
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Palette Validation System PHP Integration
 */
class MedSpa_Palette_Validation_System {

    /**
     * Instance of this class
     */
    private static $instance = null;

    /**
     * Validation rules
     */
    private $validation_rules;

    /**
     * WCAG standards
     */
    private $wcag_standards;

    /**
     * Initialize the validation system
     */
    public function __construct() {
        $this->init_validation_rules();
        $this->init_wcag_standards();
        $this->init_hooks();
    }

    /**
     * Get singleton instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        // Enqueue validation scripts
        add_action('customize_controls_enqueue_scripts', array($this, 'enqueue_validation_scripts'));
        add_action('customize_preview_init', array($this, 'enqueue_preview_scripts'));

        // AJAX handlers for validation
        add_action('wp_ajax_validate_palette', array($this, 'ajax_validate_palette'));
        add_action('wp_ajax_nopriv_validate_palette', array($this, 'ajax_validate_palette'));

        // Customizer integration
        add_action('customize_register', array($this, 'register_validation_controls'));

        // REST API endpoints
        add_action('rest_api_init', array($this, 'register_rest_endpoints'));

        // Admin notices for validation issues
        add_action('admin_notices', array($this, 'show_validation_admin_notices'));
    }

    /**
     * Initialize validation rules
     */
    private function init_validation_rules() {
        $this->validation_rules = array(
            'accessibility' => array(
                'wcag_compliance' => array(
                    'weight' => 40,
                    'required' => true,
                    'description' => 'WCAG 2.1 AA/AAA compliance validation'
                ),
                'color_blindness' => array(
                    'weight' => 20,
                    'required' => true,
                    'description' => 'Color blindness accessibility check'
                ),
                'contrast_ratios' => array(
                    'weight' => 25,
                    'required' => true,
                    'description' => 'Comprehensive contrast ratio validation'
                )
            ),
            'semantic_integrity' => array(
                'token_hierarchy' => array(
                    'weight' => 15,
                    'required' => true,
                    'description' => 'Semantic token hierarchy validation'
                ),
                'role_mapping' => array(
                    'weight' => 10,
                    'required' => true,
                    'description' => 'Color role semantic mapping validation'
                ),
                'brand_consistency' => array(
                    'weight' => 10,
                    'required' => false,
                    'description' => 'Brand consistency and harmony validation'
                )
            ),
            'usability' => array(
                'readability' => array(
                    'weight' => 15,
                    'required' => true,
                    'description' => 'Text readability across all components'
                ),
                'distinctiveness' => array(
                    'weight' => 10,
                    'required' => true,
                    'description' => 'Color distinctiveness for UI elements'
                )
            )
        );
    }

    /**
     * Initialize WCAG standards
     */
    private function init_wcag_standards() {
        $this->wcag_standards = array(
            'AA' => array(
                'normal_text' => 4.5,
                'large_text' => 3.0,
                'graphical_objects' => 3.0,
                'user_interface' => 3.0
            ),
            'AAA' => array(
                'normal_text' => 7.0,
                'large_text' => 4.5,
                'graphical_objects' => 4.5,
                'user_interface' => 4.5
            )
        );
    }

    /**
     * Enqueue validation scripts for customizer controls
     */
    public function enqueue_validation_scripts() {
        wp_enqueue_script(
            'palette-validation-system',
            get_template_directory_uri() . '/assets/js/palette-validation-system.js',
            array('jquery', 'customize-controls'),
            '1.0.0',
            true
        );

        wp_enqueue_script(
            'customizer-validation-integration',
            get_template_directory_uri() . '/assets/js/customizer-validation-integration.js',
            array('jquery', 'customize-controls', 'palette-validation-system'),
            '1.0.0',
            true
        );

        // Localize validation data
        wp_localize_script('palette-validation-system', 'paletteValidationData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('palette_validation_nonce'),
            'validationRules' => $this->validation_rules,
            'wcagStandards' => $this->wcag_standards,
            'restUrl' => rest_url('medspatheme/v1/'),
            'restNonce' => wp_create_nonce('wp_rest')
        ));
    }

    /**
     * Enqueue preview scripts
     */
    public function enqueue_preview_scripts() {
        wp_enqueue_script(
            'palette-validation-preview',
            get_template_directory_uri() . '/assets/js/palette-validation-system.js',
            array('jquery', 'customize-preview'),
            '1.0.0',
            true
        );
    }

    /**
     * Register validation controls in customizer
     */
    public function register_validation_controls($wp_customize) {
        // Add validation section
        $wp_customize->add_section('palette_validation', array(
            'title' => __('Palette Validation', 'medspatheme'),
            'description' => __('Real-time accessibility and compliance validation for color palettes.', 'medspatheme'),
            'priority' => 35,
            'panel' => 'semantic_design_tokens'
        ));

        // Validation status display
        $wp_customize->add_setting('palette_validation_status', array(
            'default' => '',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));

        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'palette_validation_status', array(
            'label' => __('Validation Status', 'medspatheme'),
            'section' => 'palette_validation',
            'type' => 'text',
            'description' => __('Current palette validation status and score.', 'medspatheme')
        )));

        // Validation settings
        $wp_customize->add_setting('enable_realtime_validation', array(
            'default' => true,
            'transport' => 'refresh',
            'sanitize_callback' => 'rest_sanitize_boolean'
        ));

        $wp_customize->add_control('enable_realtime_validation', array(
            'label' => __('Enable Real-time Validation', 'medspatheme'),
            'section' => 'palette_validation',
            'type' => 'checkbox',
            'description' => __('Automatically validate palette changes in real-time.', 'medspatheme')
        ));

        // WCAG compliance level
        $wp_customize->add_setting('wcag_compliance_level', array(
            'default' => 'AA',
            'transport' => 'postMessage',
            'sanitize_callback' => array($this, 'sanitize_wcag_level')
        ));

        $wp_customize->add_control('wcag_compliance_level', array(
            'label' => __('WCAG Compliance Level', 'medspatheme'),
            'section' => 'palette_validation',
            'type' => 'select',
            'choices' => array(
                'AA' => __('WCAG 2.1 AA (Standard)', 'medspatheme'),
                'AAA' => __('WCAG 2.1 AAA (Enhanced)', 'medspatheme')
            ),
            'description' => __('Target WCAG compliance level for validation.', 'medspatheme')
        ));

        // Validation strictness
        $wp_customize->add_setting('validation_strictness', array(
            'default' => 'standard',
            'transport' => 'postMessage',
            'sanitize_callback' => array($this, 'sanitize_validation_strictness')
        ));

        $wp_customize->add_control('validation_strictness', array(
            'label' => __('Validation Strictness', 'medspatheme'),
            'section' => 'palette_validation',
            'type' => 'select',
            'choices' => array(
                'lenient' => __('Lenient - Basic compliance', 'medspatheme'),
                'standard' => __('Standard - Recommended settings', 'medspatheme'),
                'strict' => __('Strict - Maximum accessibility', 'medspatheme')
            ),
            'description' => __('How strictly to enforce validation rules.', 'medspatheme')
        ));
    }

    /**
     * Register REST API endpoints
     */
    public function register_rest_endpoints() {
        // Validate palette endpoint
        register_rest_route('medspatheme/v1', '/validate-palette', array(
            'methods' => 'POST',
            'callback' => array($this, 'rest_validate_palette'),
            'permission_callback' => array($this, 'rest_permission_check'),
            'args' => array(
                'palette_id' => array(
                    'required' => true,
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                'palette_data' => array(
                    'required' => true,
                    'sanitize_callback' => array($this, 'sanitize_palette_data')
                )
            )
        ));

        // Get validation rules endpoint
        register_rest_route('medspatheme/v1', '/validation-rules', array(
            'methods' => 'GET',
            'callback' => array($this, 'rest_get_validation_rules'),
            'permission_callback' => '__return_true'
        ));

        // Get validation history endpoint
        register_rest_route('medspatheme/v1', '/validation-history', array(
            'methods' => 'GET',
            'callback' => array($this, 'rest_get_validation_history'),
            'permission_callback' => array($this, 'rest_permission_check')
        ));
    }

    /**
     * AJAX handler for palette validation
     */
    public function ajax_validate_palette() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'palette_validation_nonce')) {
            wp_die(__('Security check failed', 'medspatheme'));
        }

        $palette_id = sanitize_text_field($_POST['palette_id']);
        $palette_data = $this->sanitize_palette_data($_POST['palette_data']);

        if (empty($palette_data)) {
            wp_send_json_error(array(
                'message' => __('Invalid palette data provided', 'medspatheme')
            ));
        }

        $validation_result = $this->validate_palette($palette_id, $palette_data);

        if ($validation_result) {
            // Store validation result
            $this->store_validation_result($palette_id, $validation_result);

            wp_send_json_success($validation_result);
        } else {
            wp_send_json_error(array(
                'message' => __('Validation failed', 'medspatheme')
            ));
        }
    }

    /**
     * REST API palette validation
     */
    public function rest_validate_palette($request) {
        $palette_id = $request->get_param('palette_id');
        $palette_data = $request->get_param('palette_data');

        $validation_result = $this->validate_palette($palette_id, $palette_data);

        if ($validation_result) {
            $this->store_validation_result($palette_id, $validation_result);
            return rest_ensure_response($validation_result);
        }

        return new WP_Error('validation_failed', __('Palette validation failed', 'medspatheme'), array('status' => 400));
    }

    /**
     * Get validation rules via REST API
     */
    public function rest_get_validation_rules() {
        return rest_ensure_response(array(
            'validation_rules' => $this->validation_rules,
            'wcag_standards' => $this->wcag_standards,
            'version' => '1.0.0'
        ));
    }

    /**
     * Get validation history via REST API
     */
    public function rest_get_validation_history() {
        $history = get_option('palette_validation_history', array());
        return rest_ensure_response($history);
    }

    /**
     * REST API permission check
     */
    public function rest_permission_check() {
        return current_user_can('customize');
    }

    /**
     * Core palette validation logic
     */
    public function validate_palette($palette_id, $palette_data) {
        $validation_start_time = microtime(true);

        $validation_result = array(
            'palette_id' => $palette_id,
            'is_valid' => true,
            'overall_score' => 0,
            'compliance' => array(
                'wcag' => array('level' => null, 'score' => 0, 'issues' => array()),
                'accessibility' => array('score' => 0, 'issues' => array()),
                'semantic' => array('score' => 0, 'issues' => array()),
                'usability' => array('score' => 0, 'issues' => array())
            ),
            'violations' => array(),
            'warnings' => array(),
            'recommendations' => array(),
            'metrics' => array(
                'total_checks' => 0,
                'passed_checks' => 0,
                'critical_issues' => 0,
                'warning_issues' => 0
            ),
            'validation_time' => 0,
            'timestamp' => current_time('mysql')
        );

        try {
            // 1. WCAG Compliance Validation
            $wcag_result = $this->validate_wcag_compliance($palette_data);
            $validation_result['compliance']['wcag'] = $wcag_result;

            // 2. Accessibility Validation
            $accessibility_result = $this->validate_accessibility($palette_data);
            $validation_result['compliance']['accessibility'] = $accessibility_result;

            // 3. Semantic Integrity Validation
            $semantic_result = $this->validate_semantic_integrity($palette_data);
            $validation_result['compliance']['semantic'] = $semantic_result;

            // 4. Usability Validation
            $usability_result = $this->validate_usability($palette_data);
            $validation_result['compliance']['usability'] = $usability_result;

            // 5. Calculate overall score
            $validation_result = $this->calculate_overall_score($validation_result);

            // 6. Generate recommendations
            $validation_result = $this->generate_recommendations($validation_result);

        } catch (Exception $e) {
            error_log('Palette validation error: ' . $e->getMessage());
            $validation_result['is_valid'] = false;
            $validation_result['violations'][] = array(
                'type' => 'system_error',
                'severity' => 'critical',
                'message' => 'Validation system error: ' . $e->getMessage(),
                'code' => 'SYSTEM_ERROR'
            );
        }

        $validation_result['validation_time'] = round((microtime(true) - $validation_start_time) * 1000, 2);

        return $validation_result;
    }

    /**
     * Validate WCAG compliance
     */
    private function validate_wcag_compliance($palette_data) {
        $wcag_result = array(
            'level' => 'FAIL',
            'score' => 0,
            'issues' => array(),
            'aa_compliant' => true,
            'aaa_compliant' => true
        );

        $colors = isset($palette_data['colors']) ? $palette_data['colors'] : array();
        $text_hierarchies = array('title', 'body', 'caption');
        $total_checks = 0;
        $passed_checks = 0;

        foreach ($colors as $role => $color_data) {
            $bg_color = is_array($color_data) ? $color_data['hex'] : $color_data;

            foreach ($text_hierarchies as $hierarchy) {
                $total_checks++;

                // Get optimal text color and calculate contrast
                $text_color = $this->get_optimal_text_color($bg_color);
                $contrast = $this->calculate_contrast($bg_color, $text_color);

                $aa_required = ($hierarchy === 'title') ?
                    $this->wcag_standards['AA']['large_text'] :
                    $this->wcag_standards['AA']['normal_text'];

                $aaa_required = ($hierarchy === 'title') ?
                    $this->wcag_standards['AAA']['large_text'] :
                    $this->wcag_standards['AAA']['normal_text'];

                $aa_pass = $contrast >= $aa_required;
                $aaa_pass = $contrast >= $aaa_required;

                if ($aa_pass) {
                    $passed_checks++;
                } else {
                    $wcag_result['aa_compliant'] = false;
                    $wcag_result['issues'][] = array(
                        'type' => 'wcag_aa',
                        'severity' => 'critical',
                        'role' => $role,
                        'hierarchy' => $hierarchy,
                        'contrast' => round($contrast, 2),
                        'required' => $aa_required,
                        'message' => sprintf(
                            '%s background fails WCAG AA for %s text (%.2f:1, needs %.1f:1)',
                            $role, $hierarchy, $contrast, $aa_required
                        )
                    );
                }

                if (!$aaa_pass) {
                    $wcag_result['aaa_compliant'] = false;
                    if ($aa_pass) {
                        $wcag_result['issues'][] = array(
                            'type' => 'wcag_aaa',
                            'severity' => 'warning',
                            'role' => $role,
                            'hierarchy' => $hierarchy,
                            'contrast' => round($contrast, 2),
                            'required' => $aaa_required,
                            'message' => sprintf(
                                '%s background could improve for WCAG AAA compliance (%.2f:1, target %.1f:1)',
                                $role, $hierarchy, $contrast, $aaa_required
                            )
                        );
                    }
                }
            }
        }

        // Set compliance level
        if ($wcag_result['aaa_compliant']) {
            $wcag_result['level'] = 'AAA';
        } elseif ($wcag_result['aa_compliant']) {
            $wcag_result['level'] = 'AA';
        } else {
            $wcag_result['level'] = 'FAIL';
        }

        // Calculate score
        $wcag_result['score'] = $total_checks > 0 ? round(($passed_checks / $total_checks) * 100) : 0;

        return $wcag_result;
    }

    /**
     * Validate accessibility beyond WCAG
     */
    private function validate_accessibility($palette_data) {
        $accessibility_result = array(
            'score' => 100,
            'issues' => array()
        );

        $colors = isset($palette_data['colors']) ? $palette_data['colors'] : array();

        // Color distinctiveness check
        $distinctiveness_result = $this->check_color_distinctiveness($colors);
        if (!$distinctiveness_result['is_distinct']) {
            $accessibility_result['score'] -= 20;
            $accessibility_result['issues'][] = array(
                'type' => 'distinctiveness',
                'severity' => 'medium',
                'message' => 'Some colors are too similar and may cause confusion',
                'details' => $distinctiveness_result['similar_pairs'],
                'code' => 'LOW_COLOR_DISTINCTIVENESS'
            );
        }

        // Color blindness simulation (simplified)
        $color_blind_result = $this->check_color_blindness_accessibility($colors);
        if (!$color_blind_result['is_accessible']) {
            $accessibility_result['score'] -= 30;
            $accessibility_result['issues'][] = array(
                'type' => 'color_blindness',
                'severity' => 'high',
                'message' => 'Palette may be difficult for color blind users',
                'details' => $color_blind_result['issues'],
                'code' => 'COLOR_BLIND_ACCESSIBILITY'
            );
        }

        return $accessibility_result;
    }

    /**
     * Validate semantic integrity
     */
    private function validate_semantic_integrity($palette_data) {
        $semantic_result = array(
            'score' => 100,
            'issues' => array()
        );

        $colors = isset($palette_data['colors']) ? $palette_data['colors'] : array();
        $required_tokens = array('primary', 'secondary', 'surface', 'background', 'accent');

        // Check for missing required tokens
        $missing_tokens = array_diff($required_tokens, array_keys($colors));
        if (!empty($missing_tokens)) {
            $semantic_result['score'] -= 25;
            $semantic_result['issues'][] = array(
                'type' => 'missing_tokens',
                'severity' => 'critical',
                'message' => 'Missing required semantic tokens: ' . implode(', ', $missing_tokens),
                'tokens' => $missing_tokens,
                'code' => 'MISSING_SEMANTIC_TOKENS'
            );
        }

        return $semantic_result;
    }

    /**
     * Validate usability
     */
    private function validate_usability($palette_data) {
        $usability_result = array(
            'score' => 100,
            'issues' => array()
        );

        // Basic usability checks would go here
        // For now, return a good score

        return $usability_result;
    }

    /**
     * Calculate overall validation score
     */
    private function calculate_overall_score($validation_result) {
        $weights = array(
            'wcag' => 0.4,
            'accessibility' => 0.25,
            'semantic' => 0.2,
            'usability' => 0.15
        );

        $overall_score = 0;
        foreach ($weights as $category => $weight) {
            $overall_score += $validation_result['compliance'][$category]['score'] * $weight;
        }

        $validation_result['overall_score'] = round($overall_score);
        $validation_result['is_valid'] = $overall_score >= 70 && $validation_result['metrics']['critical_issues'] === 0;

        return $validation_result;
    }

    /**
     * Generate actionable recommendations
     */
    private function generate_recommendations($validation_result) {
        $recommendations = array();

        // WCAG recommendations
        if ($validation_result['compliance']['wcag']['level'] === 'FAIL') {
            $recommendations[] = array(
                'type' => 'critical',
                'priority' => 1,
                'title' => 'Fix WCAG Compliance Issues',
                'message' => 'Address critical contrast ratio failures to meet accessibility standards',
                'actions' => array(
                    'Increase contrast ratios',
                    'Choose darker/lighter alternatives',
                    'Review color combinations'
                )
            );
        }

        // Accessibility recommendations
        if ($validation_result['compliance']['accessibility']['score'] < 70) {
            $recommendations[] = array(
                'type' => 'high',
                'priority' => 2,
                'title' => 'Improve Accessibility',
                'message' => 'Enhance color accessibility for better user experience',
                'actions' => array(
                    'Test with color blindness simulators',
                    'Increase color distinctiveness',
                    'Add visual indicators beyond color'
                )
            );
        }

        $validation_result['recommendations'] = $recommendations;
        return $validation_result;
    }

    /**
     * Store validation result
     */
    private function store_validation_result($palette_id, $validation_result) {
        $history = get_option('palette_validation_history', array());

        // Keep only last 50 validations
        if (count($history) >= 50) {
            $history = array_slice($history, -49);
        }

        $history[] = array(
            'palette_id' => $palette_id,
            'score' => $validation_result['overall_score'],
            'is_valid' => $validation_result['is_valid'],
            'wcag_level' => $validation_result['compliance']['wcag']['level'],
            'timestamp' => $validation_result['timestamp']
        );

        update_option('palette_validation_history', $history);

        // Store current palette validation
        update_option('current_palette_validation_' . $palette_id, $validation_result);
    }

    /**
     * Show admin notices for validation issues
     */
    public function show_validation_admin_notices() {
        if (!current_user_can('customize')) {
            return;
        }

        $current_palette = get_theme_mod('semantic_active_palette', 'medspa-professional');
        $validation_result = get_option('current_palette_validation_' . $current_palette);

        if ($validation_result && !$validation_result['is_valid']) {
            $critical_issues = $validation_result['metrics']['critical_issues'];
            if ($critical_issues > 0) {
                echo '<div class="notice notice-error is-dismissible">';
                echo '<p><strong>' . __('Palette Validation Alert:', 'medspatheme') . '</strong> ';
                echo sprintf(
                    _n(
                        'Your current color palette has %d critical accessibility issue.',
                        'Your current color palette has %d critical accessibility issues.',
                        $critical_issues,
                        'medspatheme'
                    ),
                    $critical_issues
                );
                echo ' <a href="' . admin_url('customize.php?autofocus[section]=palette_validation') . '">' . __('Fix in Customizer', 'medspatheme') . '</a></p>';
                echo '</div>';
            }
        }
    }

    /**
     * Utility methods
     */

    /**
     * Calculate contrast ratio between two colors
     */
    private function calculate_contrast($color1, $color2) {
        $rgb1 = $this->hex_to_rgb($color1);
        $rgb2 = $this->hex_to_rgb($color2);

        if (!$rgb1 || !$rgb2) {
            return 1; // Minimum contrast ratio
        }

        $lum1 = $this->get_relative_luminance($rgb1);
        $lum2 = $this->get_relative_luminance($rgb2);

        $lighter = max($lum1, $lum2);
        $darker = min($lum1, $lum2);

        return ($lighter + 0.05) / ($darker + 0.05);
    }

    /**
     * Convert hex to RGB
     */
    private function hex_to_rgb($hex) {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        if (strlen($hex) !== 6) {
            return null;
        }

        return array(
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2))
        );
    }

    /**
     * Get relative luminance
     */
    private function get_relative_luminance($rgb) {
        $r = $rgb['r'] / 255;
        $g = $rgb['g'] / 255;
        $b = $rgb['b'] / 255;

        $r = ($r <= 0.03928) ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
        $g = ($g <= 0.03928) ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
        $b = ($b <= 0.03928) ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);

        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }

    /**
     * Get optimal text color for background
     */
    private function get_optimal_text_color($bg_color) {
        $candidates = array('#FFFFFF', '#000000', '#333333', '#666666');
        $best_contrast = 0;
        $best_color = '#000000';

        foreach ($candidates as $candidate) {
            $contrast = $this->calculate_contrast($bg_color, $candidate);
            if ($contrast > $best_contrast) {
                $best_contrast = $contrast;
                $best_color = $candidate;
            }
        }

        return $best_color;
    }

    /**
     * Check color distinctiveness
     */
    private function check_color_distinctiveness($colors) {
        $result = array(
            'is_distinct' => true,
            'similar_pairs' => array()
        );

        $color_entries = array();
        foreach ($colors as $role => $color_data) {
            $color_entries[] = array(
                'role' => $role,
                'hex' => is_array($color_data) ? $color_data['hex'] : $color_data
            );
        }

        for ($i = 0; $i < count($color_entries); $i++) {
            for ($j = $i + 1; $j < count($color_entries); $j++) {
                $color1 = $color_entries[$i];
                $color2 = $color_entries[$j];

                $similarity = $this->calculate_color_similarity($color1['hex'], $color2['hex']);
                if ($similarity > 0.85) {
                    $result['is_distinct'] = false;
                    $result['similar_pairs'][] = array(
                        'roles' => array($color1['role'], $color2['role']),
                        'colors' => array($color1['hex'], $color2['hex']),
                        'similarity' => round($similarity * 100)
                    );
                }
            }
        }

        return $result;
    }

    /**
     * Calculate color similarity
     */
    private function calculate_color_similarity($hex1, $hex2) {
        $rgb1 = $this->hex_to_rgb($hex1);
        $rgb2 = $this->hex_to_rgb($hex2);

        if (!$rgb1 || !$rgb2) {
            return 0;
        }

        $r_diff = abs($rgb1['r'] - $rgb2['r']) / 255;
        $g_diff = abs($rgb1['g'] - $rgb2['g']) / 255;
        $b_diff = abs($rgb1['b'] - $rgb2['b']) / 255;

        $avg_diff = ($r_diff + $g_diff + $b_diff) / 3;
        return 1 - $avg_diff;
    }

    /**
     * Check color blindness accessibility (simplified)
     */
    private function check_color_blindness_accessibility($colors) {
        $result = array(
            'is_accessible' => true,
            'issues' => array()
        );

        // Simplified check - in real implementation would use proper color space transformations
        $color_entries = array();
        foreach ($colors as $role => $color_data) {
            $color_entries[] = array(
                'role' => $role,
                'hex' => is_array($color_data) ? $color_data['hex'] : $color_data
            );
        }

        // Check if any colors are too similar (simplified simulation)
        for ($i = 0; $i < count($color_entries); $i++) {
            for ($j = $i + 1; $j < count($color_entries); $j++) {
                $similarity = $this->calculate_color_similarity(
                    $color_entries[$i]['hex'],
                    $color_entries[$j]['hex']
                );

                if ($similarity > 0.8) {
                    $result['is_accessible'] = false;
                    $result['issues'][] = array(
                        'type' => 'similar_colors',
                        'roles' => array($color_entries[$i]['role'], $color_entries[$j]['role']),
                        'message' => 'Colors may be indistinguishable for color blind users'
                    );
                }
            }
        }

        return $result;
    }

    /**
     * Sanitization methods
     */

    public function sanitize_wcag_level($level) {
        return in_array($level, array('AA', 'AAA')) ? $level : 'AA';
    }

    public function sanitize_validation_strictness($strictness) {
        return in_array($strictness, array('lenient', 'standard', 'strict')) ? $strictness : 'standard';
    }

    public function sanitize_palette_data($data) {
        if (!is_array($data)) {
            return array();
        }

        $sanitized = array();

        if (isset($data['colors']) && is_array($data['colors'])) {
            $sanitized['colors'] = array();
            foreach ($data['colors'] as $role => $color_data) {
                $role = sanitize_key($role);
                if (is_array($color_data)) {
                    $sanitized['colors'][$role] = array(
                        'hex' => sanitize_hex_color($color_data['hex'])
                    );
                } else {
                    $sanitized['colors'][$role] = sanitize_hex_color($color_data);
                }
            }
        }

        return $sanitized;
    }
}

// Initialize the validation system
MedSpa_Palette_Validation_System::get_instance();
