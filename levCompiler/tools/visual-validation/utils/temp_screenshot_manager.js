const fs = require('fs').promises;
const path = require('path');

class TempScreenshotManager {
  constructor(config = {}) {
    this.tempDir = config.tempDir || path.join(process.cwd(), 'temp', 'screenshots');
    this.maxFiles = config.maxFiles || 100;
    this.filePrefix = config.filePrefix || 'temp_screenshot_';
    this.cleanupEnabled = config.cleanupEnabled !== false;

    // Ensure temp directory exists
    this.initializeTempDirectory();
  }

  async initializeTempDirectory() {
    try {
      await fs.mkdir(this.tempDir, { recursive: true });
      console.log(`📁 Temp screenshot directory initialized: ${this.tempDir}`);
    } catch (error) {
      console.error('❌ Failed to initialize temp directory:', error);
    }
  }

  async cleanupOldScreenshots() {
    if (!this.cleanupEnabled) return;

    try {
      const files = await fs.readdir(this.tempDir);
      const screenshotFiles = files
        .filter(file => file.startsWith(this.filePrefix) && file.endsWith('.png'))
        .map(file => ({
          name: file,
          path: path.join(this.tempDir, file),
          stats: null
        }));

      // Get file stats for sorting by creation time
      for (const file of screenshotFiles) {
        try {
          file.stats = await fs.stat(file.path);
        } catch (error) {
          console.warn(`⚠️ Could not get stats for ${file.name}:`, error.message);
        }
      }

      // Filter out files without stats and sort by creation time (oldest first)
      const validFiles = screenshotFiles
        .filter(file => file.stats)
        .sort((a, b) => a.stats.birthtime - b.stats.birthtime);

      // If we exceed max files, delete oldest ones
      if (validFiles.length >= this.maxFiles) {
        const filesToDelete = validFiles.slice(0, validFiles.length - this.maxFiles + 1);

        console.log(`🧹 Cleaning up ${filesToDelete.length} old screenshots (keeping max ${this.maxFiles})`);

        for (const file of filesToDelete) {
          try {
            await fs.unlink(file.path);
            console.log(`🗑️ Deleted old screenshot: ${file.name}`);
          } catch (error) {
            console.warn(`⚠️ Could not delete ${file.name}:`, error.message);
          }
        }
      }

      return {
        totalFiles: validFiles.length,
        deletedFiles: Math.max(0, validFiles.length - this.maxFiles + 1),
        remainingFiles: Math.min(validFiles.length, this.maxFiles - 1)
      };

    } catch (error) {
      console.error('❌ Error during cleanup:', error);
      return { error: error.message };
    }
  }

  async saveScreenshot(screenshotBuffer, metadata = {}) {
    try {
      // Cleanup before saving new screenshot
      await this.cleanupOldScreenshots();

      // Generate unique filename
      const timestamp = Date.now();
      const viewport = metadata.viewport || 'unknown';
      const hash = this.generateHash(8);
      const filename = `${this.filePrefix}${timestamp}_${viewport}_${hash}.png`;
      const filepath = path.join(this.tempDir, filename);

      // Save screenshot
      await fs.writeFile(filepath, screenshotBuffer);

      const fileStats = await fs.stat(filepath);
      const fileSize = (fileStats.size / 1024).toFixed(2); // KB

      console.log(`📸 Screenshot saved to temp: ${filename} (${fileSize}KB)`);

      return {
        success: true,
        filepath,
        filename,
        relativePath: path.relative(process.cwd(), filepath),
        url: this.getScreenshotUrl(filename),
        size: fileSize + 'KB',
        timestamp,
        viewport,
        metadata
      };

    } catch (error) {
      console.error('❌ Error saving screenshot:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  getScreenshotUrl(filename) {
    // Generate a local file URL for easy access
    const filepath = path.join(this.tempDir, filename);
    return `file://${filepath}`;
  }

  async listTempScreenshots() {
    try {
      const files = await fs.readdir(this.tempDir);
      const screenshotFiles = [];

      for (const file of files) {
        if (file.startsWith(this.filePrefix) && file.endsWith('.png')) {
          const filepath = path.join(this.tempDir, file);
          try {
            const stats = await fs.stat(filepath);
            screenshotFiles.push({
              filename: file,
              filepath,
              relativePath: path.relative(process.cwd(), filepath),
              url: this.getScreenshotUrl(file),
              size: (stats.size / 1024).toFixed(2) + 'KB',
              created: stats.birthtime,
              modified: stats.mtime
            });
          } catch (error) {
            console.warn(`⚠️ Could not get stats for ${file}:`, error.message);
          }
        }
      }

      // Sort by creation time (newest first)
      screenshotFiles.sort((a, b) => b.created - a.created);

      return {
        success: true,
        totalFiles: screenshotFiles.length,
        maxFiles: this.maxFiles,
        screenshots: screenshotFiles,
        tempDirectory: this.tempDir
      };

    } catch (error) {
      console.error('❌ Error listing screenshots:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  async clearAllTempScreenshots() {
    try {
      const files = await fs.readdir(this.tempDir);
      const screenshotFiles = files.filter(file =>
        file.startsWith(this.filePrefix) && file.endsWith('.png')
      );

      let deletedCount = 0;
      for (const file of screenshotFiles) {
        try {
          await fs.unlink(path.join(this.tempDir, file));
          deletedCount++;
        } catch (error) {
          console.warn(`⚠️ Could not delete ${file}:`, error.message);
        }
      }

      console.log(`🧹 Cleared ${deletedCount} temp screenshots`);

      return {
        success: true,
        deletedCount,
        totalFiles: screenshotFiles.length
      };

    } catch (error) {
      console.error('❌ Error clearing temp screenshots:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  generateHash(length = 8) {
    const chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';
    for (let i = 0; i < length; i++) {
      result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return result;
  }

  async getStorageInfo() {
    try {
      const list = await this.listTempScreenshots();
      if (!list.success) return list;

      const totalSizeBytes = list.screenshots.reduce((total, screenshot) => {
        return total + parseFloat(screenshot.size) * 1024; // Convert back to bytes
      }, 0);

      const totalSizeMB = (totalSizeBytes / (1024 * 1024)).toFixed(2);

      return {
        success: true,
        storageInfo: {
          totalFiles: list.totalFiles,
          maxFiles: this.maxFiles,
          usagePercentage: ((list.totalFiles / this.maxFiles) * 100).toFixed(1),
          totalSize: totalSizeMB + 'MB',
          averageFileSize: list.totalFiles > 0 ?
            (totalSizeBytes / list.totalFiles / 1024).toFixed(2) + 'KB' : '0KB',
          tempDirectory: this.tempDir
        }
      };

    } catch (error) {
      console.error('❌ Error getting storage info:', error);
      return {
        success: false,
        error: error.message
      };
    }
  }

  // Integration method for visual validation service
  async processAndStoreScreenshot(screenshotBuffer, metadata = {}) {
    const result = await this.saveScreenshot(screenshotBuffer, metadata);

    if (result.success) {
      console.log(`✅ Temp screenshot stored: ${result.url}`);
      console.log(`📂 Local path: ${result.relativePath}`);

      // Return info needed for reports and integration
      return {
        success: true,
        tempScreenshot: {
          url: result.url,
          filepath: result.filepath,
          relativePath: result.relativePath,
          filename: result.filename,
          size: result.size,
          viewport: result.viewport,
          timestamp: result.timestamp
        }
      };
    }

    return result;
  }
}

module.exports = TempScreenshotManager;
