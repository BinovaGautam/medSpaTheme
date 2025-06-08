<?php
/**
 * MedSpa Migration Handler
 *
 * Handles migration from legacy Visual Customizer to new system
 * Sprint 4 - Industry-Standard WordPress Customizer Architecture
 * Epic 1: WordPress Core Customizer Integration
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
 * MedSpa Migration Handler Class
 *
 * Handles migration of settings from legacy system to new WordPress customizer.
 */
class MedSpa_Migration_Handler {

    /**
     * Error handler instance
     * @var MedSpa_Error_Handler|null
     */
    private $error_handler;

    /**
     * Migration log
     * @var array
     */
    private $migration_log = array();

    /**
     * Constructor
     */
    public function __construct() {
        if (class_exists('MedSpa_Error_Handler')) {
            $this->error_handler = new MedSpa_Error_Handler();
        }
    }

    /**
     * Migrate existing settings from legacy system
     */
    public function migrate_existing_settings() {
        // Check if migration is needed
        if (get_option('medspa_customizer_migrated', false)) {
            return; // Already migrated
        }

        $this->log_migration('start', 'Beginning migration from legacy Visual Customizer');

        // Migration strategies in order of preference
        $migration_strategies = array(
            'migrate_from_theme_mod',
            'migrate_from_visual_customizer_option',
            'migrate_from_simple_customizer',
            'migrate_from_color_system_manager',
        );

        $migrated = false;

        foreach ($migration_strategies as $strategy) {
            if (method_exists($this, $strategy)) {
                $result = $this->$strategy();
                if ($result) {
                    $this->log_migration('success', "Successfully migrated using strategy: {$strategy}");
                    $migrated = true;
                    break;
                }
            }
        }

        if ($migrated) {
            // Generate CSS file for migrated settings
            $current_palette = get_theme_mod('visual_color_palette', 'professional-trust');
            if (class_exists('MedSpa_CSS_Generator')) {
                $css_generator = new MedSpa_CSS_Generator();
                $css_generator->generate_palette_css($current_palette);
            }

            // Mark as migrated
            update_option('medspa_customizer_migrated', true);
            update_option('medspa_migration_date', current_time('mysql'));
            update_option('medspa_migration_log', $this->migration_log);

            $this->log_migration('complete', 'Migration completed successfully');
        } else {
            $this->log_migration('no_migration', 'No legacy settings found to migrate');
        }

        // Cleanup legacy actions and hooks
        $this->cleanup_legacy_system();
    }

    /**
     * Migrate from existing theme_mod settings
     *
     * @return bool Success status
     */
    private function migrate_from_theme_mod() {
        $existing_palette = get_theme_mod('visual_customizer_active_palette');

        if ($existing_palette && !get_theme_mod('visual_color_palette')) {
            set_theme_mod('visual_color_palette', $existing_palette);
            $this->log_migration('theme_mod', "Migrated palette from theme_mod: {$existing_palette}");
            return true;
        }

        return false;
    }

    /**
     * Migrate from Visual Customizer option
     *
     * @return bool Success status
     */
    private function migrate_from_visual_customizer_option() {
        $legacy_palette = get_option('medspa_visual_customizer_palette');

        if ($legacy_palette && !get_theme_mod('visual_color_palette')) {
            // Map legacy palette names to new names if needed
            $palette_mapping = array(
                'medical-clean' => 'professional-trust',
                'luxury-spa' => 'rose-gold-elegance',
                'modern-minimal' => 'modern-clinical',
            );

            $new_palette = isset($palette_mapping[$legacy_palette]) ? $palette_mapping[$legacy_palette] : $legacy_palette;

            set_theme_mod('visual_color_palette', $new_palette);
            $this->log_migration('option', "Migrated palette from option: {$legacy_palette} -> {$new_palette}");
            return true;
        }

        return false;
    }

    /**
     * Migrate from Simple Visual Customizer
     *
     * @return bool Success status
     */
    private function migrate_from_simple_customizer() {
        $config = get_option('preetidreams_visual_customizer_config', array());
        $active_palette = get_option('preetidreams_active_palette');

        if ($active_palette && !get_theme_mod('visual_color_palette')) {
            set_theme_mod('visual_color_palette', $active_palette);
            $this->log_migration('simple_customizer', "Migrated from simple customizer: {$active_palette}");
            return true;
        }

        return false;
    }

    /**
     * Migrate from Color System Manager
     *
     * @return bool Success status
     */
    private function migrate_from_color_system_manager() {
        if (class_exists('ColorSystemManager')) {
            $color_system = ColorSystemManager::get_instance();
            $palettes = $color_system->get_available_palettes();

            if (!empty($palettes) && !get_theme_mod('visual_color_palette')) {
                // Use first available palette as default
                $first_palette = array_key_first($palettes);
                set_theme_mod('visual_color_palette', $first_palette);
                $this->log_migration('color_system', "Migrated from ColorSystemManager: {$first_palette}");
                return true;
            }
        }

        return false;
    }

    /**
     * Cleanup legacy system options and hooks
     */
    public function cleanup_legacy_system() {
        // List of legacy options to clean up
        $legacy_options = array(
            'medspa_visual_customizer_palette',
            'medspa_visual_customizer_config',
            'preetidreams_visual_customizer_config',
            'preetidreams_active_palette',
            'preetidreams_generated_css',
            'preetidreams_vc_last_updated',
        );

        $cleaned_options = array();

        foreach ($legacy_options as $option) {
            if (get_option($option) !== false) {
                delete_option($option);
                $cleaned_options[] = $option;
            }
        }

        if (!empty($cleaned_options)) {
            $this->log_migration('cleanup', 'Cleaned up legacy options: ' . implode(', ', $cleaned_options));
        }

        // Remove legacy CSS output hooks
        remove_action('wp_head', 'medspa_output_custom_css');
        remove_action('wp_head', 'visual_customizer_output_css');

        $this->log_migration('cleanup', 'Removed legacy action hooks');
    }

    /**
     * Log migration activity
     *
     * @param string $type Migration type/step
     * @param string $message Migration message
     */
    private function log_migration($type, $message) {
        $log_entry = array(
            'timestamp' => current_time('Y-m-d H:i:s'),
            'type' => $type,
            'message' => $message,
        );

        $this->migration_log[] = $log_entry;

        if ($this->error_handler) {
            $this->error_handler->log_css_generation_error("Migration: {$message}", array('type' => $type));
        }
    }

    /**
     * Get migration log
     *
     * @return array Migration log entries
     */
    public function get_migration_log() {
        return $this->migration_log;
    }

    /**
     * Force re-migration (for development/testing)
     */
    public function force_remigration() {
        delete_option('medspa_customizer_migrated');
        delete_option('medspa_migration_date');
        delete_option('medspa_migration_log');

        $this->migration_log = array();
        $this->migrate_existing_settings();
    }

    /**
     * Check if system has been migrated
     *
     * @return bool Migration status
     */
    public function is_migrated() {
        return get_option('medspa_customizer_migrated', false);
    }

    /**
     * Get migration date
     *
     * @return string|false Migration date or false if not migrated
     */
    public function get_migration_date() {
        return get_option('medspa_migration_date', false);
    }

    /**
     * Validate migration integrity
     *
     * @return array Validation results
     */
    public function validate_migration() {
        $validation = array(
            'status' => 'unknown',
            'palette_set' => false,
            'css_generated' => false,
            'legacy_cleaned' => true,
            'errors' => array(),
        );

        // Check if palette is set
        $current_palette = get_theme_mod('visual_color_palette');
        if ($current_palette) {
            $validation['palette_set'] = true;
        } else {
            $validation['errors'][] = 'No color palette is set in theme_mod';
        }

        // Check if CSS file exists
        $file_info = get_option('medspa_dynamic_css_file');
        if ($file_info && file_exists($file_info['path'])) {
            $validation['css_generated'] = true;
        } else {
            $validation['errors'][] = 'Dynamic CSS file not found';
        }

        // Check for legacy options
        $legacy_options = array(
            'medspa_visual_customizer_palette',
            'preetidreams_active_palette',
        );

        foreach ($legacy_options as $option) {
            if (get_option($option) !== false) {
                $validation['legacy_cleaned'] = false;
                $validation['errors'][] = "Legacy option still exists: {$option}";
            }
        }

        // Determine overall status
        if (empty($validation['errors'])) {
            $validation['status'] = 'success';
        } elseif ($validation['palette_set']) {
            $validation['status'] = 'partial';
        } else {
            $validation['status'] = 'failed';
        }

        return $validation;
    }
}
