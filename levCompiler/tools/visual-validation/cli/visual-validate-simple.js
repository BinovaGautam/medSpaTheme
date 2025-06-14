#!/usr/bin/env node

const { Command } = require('commander');
const VisualValidationService = require('../services/visual_validation_service');
const TempScreenshotManager = require('../utils/temp_screenshot_manager');

const program = new Command();

program
  .name('visual-validate-simple')
  .description('Simple CLI for visual validation without spinners')
  .version('1.0.0');

program
  .command('validate')
  .description('Validate visual implementation against design specifications')
  .requiredOption('-u, --url <url>', 'URL to validate')
  .option('-t, --target <path>', 'Path to target design file/directory')
  .option('--auto', 'Run in automated mode without prompts')
  .option('--chat', 'Generate chat-ready formatted output')
  .action(async (options) => {
    console.log('🚀 Starting Visual Validation...');
    console.log(`📍 URL: ${options.url}`);
    console.log(`🎯 Target: ${options.target || 'No target design provided'}`);
    console.log('');

    try {
      // Initialize services
      const tempManager = new TempScreenshotManager();
      const service = new VisualValidationService({
        tempScreenshotManager: tempManager,
        screenshot_service: {
          viewports: [
            { name: 'desktop', width: 1920, height: 1080 },
            { name: 'tablet', width: 768, height: 1024 },
            { name: 'mobile', width: 375, height: 667 }
          ]
        }
      });

      console.log('📸 Capturing screenshots...');

      // Run validation
      const result = await service.validateVisually({
        targetUrl: options.url,
        targetDesignPath: options.target,
        outputFormat: options.chat ? 'chat' : 'standard',
        mode: options.auto ? 'automated' : 'interactive'
      });

      if (result.success) {
        console.log('\n✅ Visual validation completed successfully!');
        console.log(`📊 Overall Score: ${result.overallScore}`);
        console.log(`📁 Report: ${result.reportPath}`);

        if (result.chatMessage) {
          console.log(`💬 Chat Message: ${result.chatMessagePath}`);
        }

        // Show temp screenshot URLs
        if (result.tempScreenshots && result.tempScreenshots.length > 0) {
          console.log('\n📸 Temp Screenshots:');
          result.tempScreenshots.forEach((screenshot, index) => {
            console.log(`${index + 1}. ${screenshot.viewport} (${screenshot.size})`);
            console.log(`   🔗 ${screenshot.url}`);
            console.log(`   📂 ${screenshot.relativePath}`);
          });
        }

      } else {
        console.error('\n❌ Visual validation failed:', result.error);
        process.exit(1);
      }

    } catch (error) {
      console.error('\n❌ Validation error:', error.message);
      console.error(error.stack);
      process.exit(1);
    }
  });

program
  .command('temp-info')
  .description('Show temp screenshot storage info')
  .action(async () => {
    const tempManager = new TempScreenshotManager();
    const info = await tempManager.getStorageInfo();

    if (info.success) {
      console.log('\n📊 Temp Screenshots Storage Info');
      console.log('═'.repeat(40));
      console.log(`📁 Directory: ${info.storageInfo.tempDirectory}`);
      console.log(`📄 Files: ${info.storageInfo.totalFiles}/${info.storageInfo.maxFiles} (${info.storageInfo.usagePercentage}% used)`);
      console.log(`💾 Total Size: ${info.storageInfo.totalSize}`);
      console.log(`📏 Average File Size: ${info.storageInfo.averageFileSize}`);
    }
  });

program.parse();
