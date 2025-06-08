<?php
/**
 * MedSpa Error Handler
 *
 * Comprehensive error handling and debugging system
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
 * MedSpa Error Handler Class
 *
 * Handles error logging, validation, and debugging for the customizer system.
 */
class MedSpa_Error_Handler {

    /**
     * Log file path
     * @var string
     */
    private $log_file;

    /**
     * Constructor
     */
    public function __construct() {
        $upload_dir = wp_upload_dir();
        $this->log_file = $upload_dir['basedir'] . '/medspatheme/debug.log';
        $this->ensure_log_directory();
    }

    /**
     * Ensure log directory exists
     */
    private function ensure_log_directory() {
        $log_dir = dirname($this->log_file);
        if (!file_exists($log_dir)) {
            wp_mkdir_p($log_dir);
        }
    }

    /**
     * Log CSS generation error with context
     *
     * @param string $error_message Error message
     * @param array $context Additional context data
     */
    public function log_css_generation_error($error_message, $context = array()) {
        $log_entry = array(
            'timestamp' => current_time('Y-m-d H:i:s'),
            'type' => 'CSS_GENERATION_ERROR',
            'message' => $error_message,
            'context' => $context,
            'user_id' => get_current_user_id(),
            'url' => $_SERVER['REQUEST_URI'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'memory_usage' => memory_get_peak_usage(true),
        );

        $this->write_log_entry($log_entry);

        // Also log to WordPress error log if debug is enabled
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('MedSpa CSS Generation Error: ' . $error_message . ' | Context: ' . json_encode($context));
        }
    }

    /**
     * Handle file write failure with detailed diagnostics
     *
     * @param string $filepath File path that failed
     * @param int $content_length Content length
     * @return bool Success status of fallback attempts
     */
    public function handle_file_write_failure($filepath, $content_length) {
        $context = array(
            'filepath' => $filepath,
            'content_length' => $content_length,
            'directory_writable' => is_writable(dirname($filepath)),
            'directory_exists' => file_exists(dirname($filepath)),
            'disk_space' => disk_free_space(dirname($filepath)),
            'file_exists' => file_exists($filepath),
        );

        $this->log_css_generation_error('CSS file write failed', $context);

        // Attempt fallback strategies
        return $this->attempt_fallback_write($filepath, $content_length);
    }

    /**
     * Attempt fallback write strategies
     *
     * @param string $filepath File path
     * @param int $content_length Content length
     * @return bool Success status
     */
    private function attempt_fallback_write($filepath, $content_length) {
        // Strategy 1: Try different filename
        $fallback_path = dirname($filepath) . '/fallback-' . time() . '.css';
        $result = @file_put_contents($fallback_path, '/* Fallback CSS file */');

        if ($result !== false) {
            $this->log_css_generation_error('Fallback file write successful', array(
                'original_path' => $filepath,
                'fallback_path' => $fallback_path,
            ));
            return true;
        }

        // Strategy 2: Create minimal directory structure
        $minimal_dir = wp_upload_dir()['basedir'] . '/medspa-minimal/';
        if (!file_exists($minimal_dir)) {
            wp_mkdir_p($minimal_dir);
        }

        $minimal_path = $minimal_dir . 'style.css';
        $result = @file_put_contents($minimal_path, '/* Minimal CSS fallback */');

        if ($result !== false) {
            $this->log_css_generation_error('Minimal fallback successful', array(
                'minimal_path' => $minimal_path,
            ));
            return true;
        }

        return false;
    }

    /**
     * Validate environment for CSS generation
     *
     * @return array Environment validation results
     */
    public function validate_environment() {
        $checks = array();

        // Check upload directory permissions
        $upload_dir = wp_upload_dir();
        $checks['upload_dir_exists'] = file_exists($upload_dir['basedir']);
        $checks['upload_dir_writable'] = wp_is_writable($upload_dir['basedir']);

        // Check theme directory structure
        $theme_upload_dir = $upload_dir['basedir'] . '/medspatheme/';
        $checks['theme_dir_exists'] = file_exists($theme_upload_dir);
        $checks['theme_dir_writable'] = wp_is_writable($theme_upload_dir);

        // Test file creation
        $test_file = $theme_upload_dir . 'write-test-' . time() . '.tmp';
        $checks['can_create_files'] = @file_put_contents($test_file, 'test') !== false;

        if ($checks['can_create_files']) {
            @unlink($test_file);
        }

        // Check memory limits
        $checks['memory_limit'] = ini_get('memory_limit');
        $checks['memory_usage'] = memory_get_usage(true);
        $checks['memory_peak'] = memory_get_peak_usage(true);

        // Check disk space
        $checks['disk_free'] = disk_free_space($upload_dir['basedir']);

        // WordPress environment
        $checks['wp_debug'] = defined('WP_DEBUG') && WP_DEBUG;
        $checks['wp_version'] = get_bloginfo('version');
        $checks['php_version'] = PHP_VERSION;

        // Theme information
        $theme = wp_get_theme();
        $checks['theme_name'] = $theme->get('Name');
        $checks['theme_version'] = $theme->get('Version');

        return $checks;
    }

    /**
     * Write log entry to file
     *
     * @param array $log_entry Log entry data
     */
    private function write_log_entry($log_entry) {
        $log_line = json_encode($log_entry) . "\n";

        // Try to write to log file
        if (@file_put_contents($this->log_file, $log_line, FILE_APPEND | LOCK_EX) === false) {
            // Fallback to WordPress error log
            error_log('MedSpa Error Handler: Failed to write to log file. Entry: ' . $log_line);
        }
    }

    /**
     * Get recent log entries
     *
     * @param int $limit Number of entries to retrieve
     * @return array Recent log entries
     */
    public function get_recent_log_entries($limit = 50) {
        if (!file_exists($this->log_file)) {
            return array();
        }

        $lines = file($this->log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!$lines) {
            return array();
        }

        // Get last N lines
        $recent_lines = array_slice($lines, -$limit);
        $entries = array();

        foreach ($recent_lines as $line) {
            $entry = json_decode($line, true);
            if ($entry) {
                $entries[] = $entry;
            }
        }

        return array_reverse($entries); // Most recent first
    }

    /**
     * Clear log file
     *
     * @return bool Success status
     */
    public function clear_log() {
        return @file_put_contents($this->log_file, '') !== false;
    }

    /**
     * Get log file size
     *
     * @return int Log file size in bytes
     */
    public function get_log_size() {
        return file_exists($this->log_file) ? filesize($this->log_file) : 0;
    }

    /**
     * Check if debugging is enabled
     *
     * @return bool Debug status
     */
    public function is_debug_enabled() {
        return defined('WP_DEBUG') && WP_DEBUG;
    }

    /**
     * Log system information for debugging
     */
    public function log_system_info() {
        $system_info = array(
            'wordpress_version' => get_bloginfo('version'),
            'php_version' => PHP_VERSION,
            'theme_version' => wp_get_theme()->get('Version'),
            'active_plugins' => get_option('active_plugins'),
            'upload_dir_info' => wp_upload_dir(),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
        );

        $this->log_css_generation_error('System information logged', $system_info);
    }

    /**
     * Validate CSS generation requirements
     *
     * @return array Validation results
     */
    public function validate_css_requirements() {
        $validation = array(
            'passed' => true,
            'errors' => array(),
            'warnings' => array(),
        );

        // Check required PHP functions
        $required_functions = array('file_put_contents', 'file_get_contents', 'json_encode', 'json_decode');
        foreach ($required_functions as $function) {
            if (!function_exists($function)) {
                $validation['errors'][] = "Required PHP function '{$function}' is not available";
                $validation['passed'] = false;
            }
        }

        // Check file permissions
        $upload_dir = wp_upload_dir();
        if (!wp_is_writable($upload_dir['basedir'])) {
            $validation['errors'][] = "Upload directory is not writable: {$upload_dir['basedir']}";
            $validation['passed'] = false;
        }

        // Check memory limit
        $memory_limit = wp_convert_hr_to_bytes(ini_get('memory_limit'));
        if ($memory_limit < (64 * 1024 * 1024)) { // 64MB
            $validation['warnings'][] = "Memory limit is low: " . ini_get('memory_limit');
        }

        // Check execution time
        $max_execution_time = ini_get('max_execution_time');
        if ($max_execution_time > 0 && $max_execution_time < 30) {
            $validation['warnings'][] = "Max execution time is low: {$max_execution_time} seconds";
        }

        return $validation;
    }
}
