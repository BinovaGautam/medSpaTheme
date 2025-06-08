<?php
/**
 * MedSpa File Manager
 *
 * Manages CSS file operations with cache busting and cleanup
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
 * MedSpa File Manager Class
 *
 * Handles file operations for dynamic CSS with proper cache busting
 * and cleanup following industry best practices.
 */
class MedSpa_File_Manager {

    /**
     * Constructor
     */
    public function __construct() {
        // Initialize if needed
    }

    /**
     * Get CSS file URL with cache busting
     * Following GeneratePress cache busting pattern
     *
     * @return string|false CSS file URL or false if not available
     */
    public function get_css_file_url() {
        $file_info = get_option('medspa_dynamic_css_file', array());

        if (empty($file_info) || !isset($file_info['path']) || !file_exists($file_info['path'])) {
            return false;
        }

        // Add filemtime for additional cache busting
        $version = filemtime($file_info['path']);
        return add_query_arg('ver', $version, $file_info['url']);
    }

    /**
     * Get CSS file path
     *
     * @return string|false CSS file path or false if not available
     */
    public function get_css_file_path() {
        $file_info = get_option('medspa_dynamic_css_file', array());

        if (empty($file_info) || !isset($file_info['path'])) {
            return false;
        }

        return $file_info['path'];
    }

    /**
     * Get CSS file information
     *
     * @return array CSS file information
     */
    public function get_css_file_info() {
        return get_option('medspa_dynamic_css_file', array());
    }

    /**
     * Cleanup old CSS files
     * Keep only the latest 5 files for performance
     */
    public function cleanup_old_css_files() {
        $upload_dir = wp_upload_dir();
        $css_dir = $upload_dir['basedir'] . '/medspatheme/';

        if (!file_exists($css_dir)) {
            return;
        }

        $files = glob($css_dir . 'dynamic-style-v*.css');

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

    /**
     * Cleanup all theme files
     * Used when switching themes
     */
    public function cleanup_all_files() {
        $upload_dir = wp_upload_dir();
        $css_dir = $upload_dir['basedir'] . '/medspatheme/';

        if (!file_exists($css_dir)) {
            return;
        }

        // Remove all CSS files
        $files = glob($css_dir . '*.css');
        foreach ($files as $file) {
            @unlink($file);
        }

        // Remove directory if empty
        @rmdir($css_dir);

        // Clear options
        delete_option('medspa_dynamic_css_file');
    }

    /**
     * Validate write permissions
     *
     * @return bool Whether directory is writable
     */
    public function validate_write_permissions() {
        $upload_dir = wp_upload_dir();
        $css_dir = $upload_dir['basedir'] . '/medspatheme/';

        // Ensure directory exists
        if (!file_exists($css_dir)) {
            wp_mkdir_p($css_dir);
        }

        // Test file creation
        $test_file = $css_dir . 'write-test-' . time() . '.tmp';
        $can_write = @file_put_contents($test_file, 'test') !== false;

        if ($can_write) {
            @unlink($test_file);
        }

        return $can_write;
    }

    /**
     * Get directory size
     *
     * @return int Directory size in bytes
     */
    public function get_directory_size() {
        $upload_dir = wp_upload_dir();
        $css_dir = $upload_dir['basedir'] . '/medspatheme/';

        if (!file_exists($css_dir)) {
            return 0;
        }

        $size = 0;
        $files = glob($css_dir . '*');

        foreach ($files as $file) {
            if (is_file($file)) {
                $size += filesize($file);
            }
        }

        return $size;
    }

    /**
     * Get file statistics
     *
     * @return array File statistics
     */
    public function get_file_statistics() {
        $upload_dir = wp_upload_dir();
        $css_dir = $upload_dir['basedir'] . '/medspatheme/';

        $stats = array(
            'directory_exists' => file_exists($css_dir),
            'is_writable' => is_writable($css_dir),
            'total_files' => 0,
            'total_size' => 0,
            'latest_file' => null,
        );

        if (!$stats['directory_exists']) {
            return $stats;
        }

        $files = glob($css_dir . 'dynamic-style-v*.css');
        $stats['total_files'] = count($files);

        if ($files) {
            // Get latest file
            usort($files, function($a, $b) {
                return filemtime($b) - filemtime($a);
            });

            $stats['latest_file'] = basename($files[0]);

            // Calculate total size
            foreach ($files as $file) {
                $stats['total_size'] += filesize($file);
            }
        }

        return $stats;
    }
}
