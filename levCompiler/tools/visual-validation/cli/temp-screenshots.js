#!/usr/bin/env node

const { Command } = require('commander');
const TempScreenshotManager = require('../utils/temp_screenshot_manager');

const program = new Command();

program
  .name('temp-screenshots')
  .description('CLI for managing temporary screenshots')
  .version('1.0.0');

// List temp screenshots
program
  .command('list')
  .description('List all temporary screenshots')
  .option('-v, --verbose', 'Show detailed information')
  .action(async (options) => {
    const tempManager = new TempScreenshotManager();
    const result = await tempManager.listTempScreenshots();

    if (!result.success) {
      console.error('❌ Error listing screenshots:', result.error);
      process.exit(1);
    }

    console.log(`\n📁 Temp Screenshots Directory: ${result.tempDirectory}`);
    console.log(`📊 Usage: ${result.totalFiles}/${result.maxFiles} files\n`);

    if (result.screenshots.length === 0) {
      console.log('📭 No temporary screenshots found');
      return;
    }

    console.log('📸 Recent Screenshots:');
    console.log('─'.repeat(80));

    result.screenshots.forEach((screenshot, index) => {
      const timeAgo = getTimeAgo(screenshot.created);

      if (options.verbose) {
        console.log(`${index + 1}. ${screenshot.filename}`);
        console.log(`   💾 Size: ${screenshot.size}`);
        console.log(`   🕐 Created: ${screenshot.created.toLocaleString()} (${timeAgo})`);
        console.log(`   🔗 URL: ${screenshot.url}`);
        console.log(`   📂 Path: ${screenshot.relativePath}`);
        console.log('');
      } else {
        console.log(`${index + 1}. ${screenshot.filename} (${screenshot.size}) - ${timeAgo}`);
        console.log(`   🔗 ${screenshot.url}`);
      }
    });
  });

// Clear temp screenshots
program
  .command('clear')
  .description('Clear all temporary screenshots')
  .option('-f, --force', 'Force clear without confirmation')
  .action(async (options) => {
    const tempManager = new TempScreenshotManager();

    if (!options.force) {
      const { default: inquirer } = await import('inquirer');
      const { confirm } = await inquirer.prompt([
        {
          type: 'confirm',
          name: 'confirm',
          message: '🗑️ Are you sure you want to clear all temporary screenshots?',
          default: false
        }
      ]);

      if (!confirm) {
        console.log('❌ Clear operation cancelled');
        return;
      }
    }

    const result = await tempManager.clearAllTempScreenshots();

    if (!result.success) {
      console.error('❌ Error clearing screenshots:', result.error);
      process.exit(1);
    }

    console.log(`✅ Cleared ${result.deletedCount} temporary screenshots`);
  });

// Storage info
program
  .command('info')
  .description('Show storage information')
  .action(async () => {
    const tempManager = new TempScreenshotManager();
    const result = await tempManager.getStorageInfo();

    if (!result.success) {
      console.error('❌ Error getting storage info:', result.error);
      process.exit(1);
    }

    const info = result.storageInfo;

    console.log('\n📊 Temp Screenshots Storage Info');
    console.log('═'.repeat(40));
    console.log(`📁 Directory: ${info.tempDirectory}`);
    console.log(`📄 Files: ${info.totalFiles}/${info.maxFiles} (${info.usagePercentage}% used)`);
    console.log(`💾 Total Size: ${info.totalSize}`);
    console.log(`📏 Average File Size: ${info.averageFileSize}`);

    if (info.totalFiles > 0) {
      const threshold = info.maxFiles * 0.8; // 80% threshold
      if (info.totalFiles >= threshold) {
        console.log(`\n⚠️  Storage is ${info.usagePercentage}% full. Consider clearing old screenshots.`);
      }
    }
  });

// Test screenshot capture
program
  .command('test-capture <url>')
  .description('Test screenshot capture and save to temp')
  .option('-v, --viewport <viewport>', 'Viewport to capture (desktop, tablet, mobile)', 'desktop')
  .action(async (url, options) => {
    console.log(`📸 Testing screenshot capture for: ${url}`);
    console.log(`🖥️ Viewport: ${options.viewport}`);

    try {
      // Use visual validation service to capture screenshot
      const VisualValidationService = require('../services/visual_validation_service');
      const tempManager = new TempScreenshotManager();

      const service = new VisualValidationService({
        tempScreenshotManager: tempManager
      });

      // Capture single screenshot
      const result = await service.captureScreenshot(url, options.viewport);

      if (result.success) {
        console.log(`✅ Screenshot captured successfully!`);
        console.log(`🔗 URL: ${result.tempScreenshot.url}`);
        console.log(`📂 Path: ${result.tempScreenshot.relativePath}`);
        console.log(`💾 Size: ${result.tempScreenshot.size}`);
      } else {
        console.error('❌ Screenshot capture failed:', result.error);
        process.exit(1);
      }

    } catch (error) {
      console.error('❌ Test capture error:', error.message);
      process.exit(1);
    }
  });

// Cleanup command (for maintenance)
program
  .command('cleanup')
  .description('Run cleanup to remove old screenshots if over limit')
  .action(async () => {
    const tempManager = new TempScreenshotManager();
    const result = await tempManager.cleanupOldScreenshots();

    if (result.error) {
      console.error('❌ Cleanup error:', result.error);
      process.exit(1);
    }

    if (result.deletedFiles > 0) {
      console.log(`🧹 Cleanup completed: ${result.deletedFiles} files deleted`);
      console.log(`📄 Remaining files: ${result.remainingFiles}`);
    } else {
      console.log('✅ No cleanup needed - under file limit');
    }
  });

function getTimeAgo(date) {
  const now = new Date();
  const diffMs = now - date;
  const diffMins = Math.floor(diffMs / 60000);
  const diffHours = Math.floor(diffMins / 60);
  const diffDays = Math.floor(diffHours / 24);

  if (diffMins < 1) return 'just now';
  if (diffMins < 60) return `${diffMins}m ago`;
  if (diffHours < 24) return `${diffHours}h ago`;
  if (diffDays < 30) return `${diffDays}d ago`;
  return date.toLocaleDateString();
}

program.parse();
