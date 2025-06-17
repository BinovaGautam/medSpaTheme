/**
 * Directory Configuration for Visual Validation Tool
 * COMPLIANCE: fundamentals.json TOOLS_ORGANIZATION_REQUIREMENTS
 *
 * This file ensures all visual validation directories follow the mandated structure:
 * - NO temp/ directory at project root
 * - NO validation_results/ directory at project root
 * - ALL temp files in levCompiler/tools/temp/
 * - ALL validation results in levCompiler/tools/visual-validation/results/
 */

const path = require('path');

// FIXED: Ensure we use the actual project root, not the current working directory
// which could be inside the tools directory when running CLI commands
const PROJECT_ROOT = path.resolve(__dirname, '../../../..');

// MANDATORY DIRECTORY STRUCTURE per fundamentals.json
const DIRECTORY_PATHS = {
  // Tool-specific results directory
  RESULTS_BASE: path.join(PROJECT_ROOT, 'levCompiler/tools/visual-validation/results'),
  SCREENSHOTS: path.join(PROJECT_ROOT, 'levCompiler/tools/visual-validation/results/screenshots'),
  COMPARISONS: path.join(PROJECT_ROOT, 'levCompiler/tools/visual-validation/results/comparisons'),
  REPORTS: path.join(PROJECT_ROOT, 'levCompiler/tools/visual-validation/results/reports'),

  // Shared temp directory per fundamentals.json
  TEMP_BASE: path.join(PROJECT_ROOT, 'levCompiler/tools/temp'),
  TEMP_SCREENSHOTS: path.join(PROJECT_ROOT, 'levCompiler/tools/temp/screenshots'),

  // Logs directory per fundamentals.json
  LOGS: path.join(PROJECT_ROOT, 'levCompiler/tools/logs/visual-validation'),

  // Archive for completed validations
  ARCHIVE: path.join(PROJECT_ROOT, 'levCompiler/project_context/archive/visual-validation')
};

// Validate that we're not creating prohibited directories
const PROHIBITED_PATHS = [
  path.join(PROJECT_ROOT, 'temp'),
  path.join(PROJECT_ROOT, 'validation_results'),
  path.join(PROJECT_ROOT, 'levCompiler/temp'),
  path.join(PROJECT_ROOT, 'levCompiler/validation_results')
];

/**
 * Get the configured path for a specific directory type
 * @param {string} type - The directory type (SCREENSHOTS, TEMP_SCREENSHOTS, etc.)
 * @returns {string} The full path to the directory
 */
function getDirectoryPath(type) {
  if (!DIRECTORY_PATHS[type]) {
    throw new Error(`Unknown directory type: ${type}. Available types: ${Object.keys(DIRECTORY_PATHS).join(', ')}`);
  }
  return DIRECTORY_PATHS[type];
}

/**
 * Check if a path violates fundamentals.json directory rules
 * @param {string} checkPath - Path to validate
 * @returns {boolean} True if path violates rules
 */
function isProhibitedPath(checkPath) {
  const normalizedPath = path.resolve(checkPath);
  return PROHIBITED_PATHS.some(prohibited =>
    normalizedPath.startsWith(path.resolve(prohibited))
  );
}

/**
 * Get configuration for TempScreenshotManager
 * @returns {object} Configuration object
 */
function getTempScreenshotConfig() {
  return {
    tempDir: DIRECTORY_PATHS.TEMP_SCREENSHOTS,
    maxFiles: 100,
    filePrefix: 'temp_screenshot_',
    cleanupEnabled: true
  };
}

/**
 * Get configuration for VisualValidationService
 * @returns {object} Configuration object
 */
function getVisualValidationConfig() {
  return {
    directories: {
      screenshots: DIRECTORY_PATHS.SCREENSHOTS,
      comparisons: DIRECTORY_PATHS.COMPARISONS,
      reports: DIRECTORY_PATHS.REPORTS,
      temp: DIRECTORY_PATHS.TEMP_SCREENSHOTS,
      logs: DIRECTORY_PATHS.LOGS,
      archive: DIRECTORY_PATHS.ARCHIVE
    }
  };
}

module.exports = {
  DIRECTORY_PATHS,
  PROHIBITED_PATHS,
  getDirectoryPath,
  isProhibitedPath,
  getTempScreenshotConfig,
  getVisualValidationConfig
};
