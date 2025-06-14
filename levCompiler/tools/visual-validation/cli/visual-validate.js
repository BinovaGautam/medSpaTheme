#!/usr/bin/env node

const { program } = require('commander');
const chalk = require('chalk');
const ora = require('ora');
const inquirer = require('inquirer');
const fs = require('fs').promises;
const path = require('path');
const VisualValidationService = require('../services/visual_validation_service');

// Load agent configuration
const agentConfig = require('../../../.control/agents/visual_validation_agent.json');

program
  .name('levCompiler visual')
  .description('Automated visual validation and comparison system')
  .version('1.0.0');

// Main validate command
program
  .command('validate')
  .description('Run visual validation on current project')
  .option('-u, --url <url>', 'Target URL (if server is already running)')
  .option('-d, --design <path>', 'Path to target design file(s)')
  .option('-p, --project <path>', 'Project root directory', process.cwd())
  .option('-o, --output <path>', 'Output directory for results', './validation_results')
  .option('--auto', 'Run in automatic mode without prompts')
  .option('--viewports <list>', 'Comma-separated list of viewports to test', 'desktop,tablet,mobile')
  .option('--threshold <number>', 'Minimum similarity threshold (0-1)', '0.85')
  .option('--chat', 'Send results to active chat session')
  .action(async (options) => {
    const spinner = ora('Initializing visual validation...').start();

    try {
      // Interactive setup if not in auto mode
      if (!options.auto) {
        const answers = await inquirer.prompt([
          {
            type: 'input',
            name: 'url',
            message: 'Development server URL (leave empty to auto-detect):',
            default: options.url || ''
          },
          {
            type: 'input',
            name: 'design',
            message: 'Path to target design file(s):',
            default: options.design || './designs'
          },
          {
            type: 'checkbox',
            name: 'viewports',
            message: 'Select viewports to test:',
            choices: [
              { name: 'Desktop (1920x1080)', value: 'desktop', checked: true },
              { name: 'Tablet (768x1024)', value: 'tablet', checked: true },
              { name: 'Mobile (375x667)', value: 'mobile', checked: true }
            ]
          },
          {
            type: 'number',
            name: 'threshold',
            message: 'Minimum similarity threshold (0-1):',
            default: parseFloat(options.threshold)
          },
          {
            type: 'confirm',
            name: 'chat',
            message: 'Send results to active chat session?',
            default: options.chat || false
          }
        ]);

        Object.assign(options, answers);
      }

      spinner.text = 'Setting up visual validation service...';

      // Initialize validation service
      const validationService = new VisualValidationService(agentConfig.configuration);

      // Run validation
      spinner.text = 'Running visual validation...';
      const report = await validationService.validateVisually({
        targetUrl: options.url,
        targetDesignPath: options.design,
        projectPath: options.project,
        validationId: `cli_${Date.now()}`
      });

      spinner.succeed('Visual validation completed!');

      // Display results
      displayResults(report);

      // Save results
      await saveResults(report, options.output);

      // Send to chat if requested
      if (options.chat) {
        await sendToChat(report);
      }

    } catch (error) {
      spinner.fail(`Visual validation failed: ${error.message}`);
      console.error(chalk.red('Error details:'), error);
      process.exit(1);
    }
  });

// Continuous validation mode
program
  .command('watch')
  .description('Watch for changes and run validation automatically')
  .option('-i, --interval <seconds>', 'Check interval in seconds', '30')
  .option('-d, --design <path>', 'Path to target design file(s)')
  .option('--threshold <number>', 'Minimum similarity threshold (0-1)', '0.85')
  .action(async (options) => {
    console.log(chalk.blue('🔄 Starting continuous visual validation...'));

    const validationService = new VisualValidationService(agentConfig.configuration);
    const interval = parseInt(options.interval) * 1000;

    setInterval(async () => {
      try {
        const spinner = ora('Running periodic validation...').start();

        const report = await validationService.validateVisually({
          targetDesignPath: options.design,
          projectPath: process.cwd(),
          validationId: `watch_${Date.now()}`
        });

        if (report.summary.status === 'PASSED') {
          spinner.succeed(`Validation passed (${(report.summary.overall_score * 100).toFixed(1)}%)`);
        } else {
          spinner.warn(`Validation needs improvement (${(report.summary.overall_score * 100).toFixed(1)}%)`);
          console.log(chalk.yellow(`📊 View detailed report: ${report.report_path}`));
        }

      } catch (error) {
        console.error(chalk.red('Validation error:'), error.message);
      }
    }, interval);
  });

// Configuration management
program
  .command('config')
  .description('Manage visual validation configuration')
  .option('-s, --show', 'Show current configuration')
  .option('-e, --edit', 'Edit configuration interactively')
  .option('-r, --reset', 'Reset to default configuration')
  .action(async (options) => {
    if (options.show) {
      console.log(chalk.blue('📋 Current Configuration:'));
      console.log(JSON.stringify(agentConfig.configuration, null, 2));
    } else if (options.edit) {
      await editConfiguration();
    } else if (options.reset) {
      await resetConfiguration();
    } else {
      console.log(chalk.yellow('Use --show, --edit, or --reset options'));
    }
  });

// Status and health check
program
  .command('status')
  .description('Check system status and health')
  .action(async () => {
    const spinner = ora('Checking system status...').start();

    try {
      // Check dependencies
      const checks = await performHealthChecks();

      spinner.succeed('System status check complete');

      console.log(chalk.blue('\n📊 System Health Report:'));
      checks.forEach(check => {
        const icon = check.status === 'ok' ? '✅' : '❌';
        console.log(`${icon} ${check.name}: ${check.message}`);
      });

    } catch (error) {
      spinner.fail('System status check failed');
      console.error(chalk.red('Error:'), error.message);
    }
  });

// Helper functions
function displayResults(report) {
  console.log(chalk.blue('\n🎯 Visual Validation Results'));
  console.log(chalk.blue('================================'));

  const statusColor = report.summary.status === 'PASSED' ? 'green' : 'yellow';
  console.log(`Status: ${chalk[statusColor](report.summary.status)}`);
  console.log(`Overall Score: ${chalk.cyan((report.summary.overall_score * 100).toFixed(1))}%`);
  console.log(`Average Similarity: ${chalk.cyan((report.summary.average_similarity * 100).toFixed(1))}%`);
  console.log(`Viewports Tested: ${chalk.cyan(report.summary.total_viewports)}`);

  console.log(chalk.blue('\n📱 Viewport Results:'));
  Object.entries(report.detailed_results.screenshots).forEach(([viewport, result]) => {
    const similarity = result.comparison?.primary_similarity || 0;
    const status = similarity >= 0.85 ? '✅' : similarity >= 0.7 ? '⚠️' : '❌';
    console.log(`${status} ${viewport.toUpperCase()}: ${(similarity * 100).toFixed(1)}%`);
  });

  if (report.recommendations.length > 0) {
    console.log(chalk.blue('\n🔧 Recommendations:'));
    report.recommendations.slice(0, 5).forEach((rec, index) => {
      const priority = rec.priority === 'high' ? chalk.red('[HIGH]') : chalk.yellow('[MED]');
      console.log(`${index + 1}. ${priority} ${rec.description}`);
    });

    if (report.recommendations.length > 5) {
      console.log(chalk.gray(`... and ${report.recommendations.length - 5} more recommendations`));
    }
  }

  console.log(chalk.blue(`\n📁 Report saved to: ${report.report_path}`));
}

async function saveResults(report, outputDir) {
  const reportPath = path.join(outputDir, `validation_report_${report.validation_id}.json`);
  await fs.mkdir(path.dirname(reportPath), { recursive: true });
  await fs.writeFile(reportPath, JSON.stringify(report, null, 2));

  console.log(chalk.green(`📄 Results saved to: ${reportPath}`));
}

async function sendToChat(report) {
  const spinner = ora('Sending results to chat...').start();

  try {
    // This would integrate with your existing chat system
    // For now, we'll create a chat-ready message
    const chatMessage = formatChatMessage(report);

    const chatPath = path.join(process.cwd(), 'validation_results', `chat_message_${report.validation_id}.md`);
    await fs.writeFile(chatPath, chatMessage);

    spinner.succeed(`Chat message prepared: ${chatPath}`);

    // TODO: Integrate with actual chat API
    console.log(chalk.blue('📤 Ready to send to chat - integrate with your chat system'));

  } catch (error) {
    spinner.fail('Failed to prepare chat message');
    console.error(chalk.red('Error:'), error.message);
  }
}

function formatChatMessage(report) {
  const { summary, detailed_results, recommendations, assets } = report;

  let message = `# 🎯 Visual Validation Results\n\n`;
  message += `**Status:** ${summary.status} ${summary.status === 'PASSED' ? '✅' : '⚠️'}\n`;
  message += `**Overall Score:** ${(summary.overall_score * 100).toFixed(1)}%\n`;
  message += `**Average Similarity:** ${(summary.average_similarity * 100).toFixed(1)}%\n`;
  message += `**Viewports Tested:** ${summary.total_viewports}\n\n`;

  // Viewport results
  message += `## 📱 Viewport Results\n\n`;
  Object.entries(detailed_results.screenshots).forEach(([viewport, result]) => {
    const similarity = result.comparison?.primary_similarity || 0;
    const status = similarity >= 0.85 ? '✅' : similarity >= 0.7 ? '⚠️' : '❌';

    message += `### ${viewport.toUpperCase()} ${status}\n`;
    message += `- **Similarity:** ${(similarity * 100).toFixed(1)}%\n`;
    message += `- **Screenshot:** \`${path.basename(result.image_path)}\`\n`;

    if (result.comparison?.diff_image_path) {
      message += `- **Diff Image:** \`${path.basename(result.comparison.diff_image_path)}\`\n`;
    }

    message += `\n`;
  });

  // Recommendations
  if (recommendations.length > 0) {
    message += `## 🔧 Top Recommendations\n\n`;
    recommendations.slice(0, 5).forEach((rec, index) => {
      message += `${index + 1}. **${rec.category}:** ${rec.description}\n`;
      if (rec.priority === 'high') message += `   ⚡ **High Priority**\n`;
      message += `\n`;
    });
  }

  return message;
}

async function performHealthChecks() {
  const checks = [];

  // Check Playwright
  try {
    const { chromium } = require('playwright');
    checks.push({
      name: 'Playwright',
      status: 'ok',
      message: 'Available and ready'
    });
  } catch (error) {
    checks.push({
      name: 'Playwright',
      status: 'error',
      message: 'Not installed or not accessible'
    });
  }

  // Check Puppeteer
  try {
    const puppeteer = require('puppeteer');
    checks.push({
      name: 'Puppeteer',
      status: 'ok',
      message: 'Available as fallback'
    });
  } catch (error) {
    checks.push({
      name: 'Puppeteer',
      status: 'error',
      message: 'Not installed'
    });
  }

  // Check OpenAI API
  const openaiKey = process.env.OPENAI_API_KEY;
  checks.push({
    name: 'OpenAI API',
    status: openaiKey ? 'ok' : 'warning',
    message: openaiKey ? 'API key configured' : 'API key not set (AI analysis disabled)'
  });

  // Check output directory
  try {
    await fs.mkdir('./validation_results', { recursive: true });
    checks.push({
      name: 'Output Directory',
      status: 'ok',
      message: 'Ready for results'
    });
  } catch (error) {
    checks.push({
      name: 'Output Directory',
      status: 'error',
      message: 'Cannot create output directory'
    });
  }

  return checks;
}

async function editConfiguration() {
  console.log(chalk.blue('🔧 Configuration Editor'));
  console.log(chalk.gray('Current configuration will be modified interactively'));

  // This would provide an interactive configuration editor
  // For now, just show the current config
  console.log(JSON.stringify(agentConfig.configuration, null, 2));
  console.log(chalk.yellow('Interactive configuration editor - Coming soon!'));
}

async function resetConfiguration() {
  console.log(chalk.blue('🔄 Resetting configuration to defaults...'));
  console.log(chalk.yellow('Configuration reset - Coming soon!'));
}

// Error handling
process.on('unhandledRejection', (reason, promise) => {
  console.error(chalk.red('Unhandled Rejection at:'), promise, chalk.red('reason:'), reason);
  process.exit(1);
});

process.on('uncaughtException', (error) => {
  console.error(chalk.red('Uncaught Exception:'), error);
  process.exit(1);
});

program.parse();
