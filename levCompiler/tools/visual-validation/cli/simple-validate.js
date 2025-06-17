#!/usr/bin/env node

const chalk = require('chalk');
const fs = require('fs').promises;
const path = require('path');
const VisualValidationService = require('../services/visual_validation_service');

// Load agent configuration
const agentConfig = require('../../../.control/agents/visual_validation_agent.json');

async function runValidation() {
  const args = process.argv.slice(2);

  // Parse basic arguments
  const urlIndex = args.indexOf('-u') + 1;
  const designIndex = args.indexOf('-d') + 1;
  const outputIndex = args.indexOf('-o') + 1;

  const options = {
    url: urlIndex > 0 ? args[urlIndex] : '',
    design: designIndex > 0 ? args[designIndex] : '',
    // FIXED: Following fundamentals.json TOOLS_ORGANIZATION_REQUIREMENTS
    output: outputIndex > 0 ? args[outputIndex] : './levCompiler/tools/visual-validation/results',
    viewports: 'desktop,tablet,mobile',
    threshold: 0.85,
    auto: true,
    chat: args.includes('--chat')
  };

  console.log(chalk.blue('🚀 Starting Visual Validation...'));
  console.log(chalk.gray(`URL: ${options.url}`));
  console.log(chalk.gray(`Design: ${options.design}`));
  console.log(chalk.gray(`Output: ${options.output}`));

  try {
    console.log(chalk.yellow('⚙️  Setting up visual validation service...'));

    // Initialize validation service
    const validationService = new VisualValidationService(agentConfig.configuration);

    console.log(chalk.yellow('📸 Running visual validation...'));
    const report = await validationService.validateVisually({
      targetUrl: options.url,
      targetDesignPath: options.design,
      projectPath: process.cwd(),
      validationId: `cli_${Date.now()}`
    });

    console.log(chalk.green('✅ Visual validation completed!'));

    // Display results
    displayResults(report);

    // Save results
    await saveResults(report, options.output);

    // Send to chat if requested
    if (options.chat) {
      await sendToChat(report);
    }

  } catch (error) {
    console.error(chalk.red('❌ Visual validation failed:'), error.message);
    console.error(chalk.red('Error details:'), error);
    process.exit(1);
  }
}

function displayResults(report) {
  console.log(chalk.blue('\n🎯 Visual Validation Results'));
  console.log(chalk.blue('================================'));

  const statusColor = report.summary.status === 'PASSED' ? 'green' : 'yellow';
  console.log(`Status: ${chalk[statusColor](report.summary.status)}`);
  console.log(`Overall Score: ${chalk.cyan((report.summary.overall_score * 100).toFixed(1))}%`);
  console.log(`Average Similarity: ${chalk.cyan((report.summary.average_similarity * 100).toFixed(1))}%`);

  if (report.validations && report.validations.length > 0) {
    console.log(chalk.blue('\n📊 Validation Details:'));
    report.validations.forEach((validation, index) => {
      const score = (validation.similarity_score * 100).toFixed(1);
      const color = validation.similarity_score > 0.8 ? 'green' : 'yellow';
      console.log(`  ${index + 1}. ${validation.viewport}: ${chalk[color](score + '%')}`);
    });
  }

  if (report.analysis && report.analysis.critical_issues && report.analysis.critical_issues.length > 0) {
    console.log(chalk.blue('\n🔍 Critical Issues:'));
    report.analysis.critical_issues.forEach((issue, index) => {
      console.log(`  ${index + 1}. ${chalk.red(issue.category)}: ${issue.description}`);
    });
  }

  console.log(chalk.blue(`\n📄 Full report: ${report.report_path}`));
}

async function saveResults(report, outputDir) {
  try {
    await fs.mkdir(outputDir, { recursive: true });
    const reportPath = path.join(outputDir, `validation_report_${Date.now()}.json`);
    await fs.writeFile(reportPath, JSON.stringify(report, null, 2));
    console.log(chalk.green(`💾 Report saved to: ${reportPath}`));
  } catch (error) {
    console.error(chalk.yellow(`Warning: Could not save report - ${error.message}`));
  }
}

async function sendToChat(report) {
  console.log(chalk.blue('\n💬 Sending results to chat...'));

  const message = formatChatMessage(report);
  console.log(chalk.gray('Chat message:'));
  console.log(message);

  // In a real implementation, this would send to the actual chat system
  console.log(chalk.green('✅ Results sent to chat (simulated)'));
}

function formatChatMessage(report) {
  const status = report.summary.status === 'PASSED' ? '✅' : '⚠️';
  const score = (report.summary.overall_score * 100).toFixed(1);

  let message = `${status} **Visual Validation Complete**\n\n`;
  message += `**Status:** ${report.summary.status}\n`;
  message += `**Overall Score:** ${score}%\n`;
  message += `**Average Similarity:** ${(report.summary.average_similarity * 100).toFixed(1)}%\n\n`;

  if (report.validations && report.validations.length > 0) {
    message += `**Viewport Results:**\n`;
    report.validations.forEach(validation => {
      const viewportScore = (validation.similarity_score * 100).toFixed(1);
      const emoji = validation.similarity_score > 0.8 ? '✅' : '⚠️';
      message += `${emoji} ${validation.viewport}: ${viewportScore}%\n`;
    });
    message += '\n';
  }

  if (report.analysis && report.analysis.critical_issues && report.analysis.critical_issues.length > 0) {
    message += `**Critical Issues Found:**\n`;
    report.analysis.critical_issues.slice(0, 3).forEach(issue => {
      message += `🔸 **${issue.category}**: ${issue.description}\n`;
    });

    if (report.analysis.critical_issues.length > 3) {
      message += `... and ${report.analysis.critical_issues.length - 3} more issues\n`;
    }
    message += '\n';
  }

  message += `📄 **Full Report:** ${report.report_path}`;

  return message;
}

// Run the validation
runValidation().catch(console.error);
