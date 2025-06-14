#!/usr/bin/env node

const { program } = require('commander');
const chalk = require('chalk');
const fs = require('fs').promises;
const path = require('path');
const { spawn } = require('child_process');

// Load tools registry
const TOOLS_REGISTRY_PATH = path.join(__dirname, 'tools-registry.json');

program
  .name('levCompiler tools')
  .description('Central tool management for levCompiler')
  .version('1.0.0');

// List available tools
program
  .command('list')
  .description('List all available tools')
  .option('-c, --category <category>', 'Filter by category')
  .option('-s, --status <status>', 'Filter by status')
  .action(async (options) => {
    try {
      const registry = await loadRegistry();
      const tools = registry.tools_registry.tools;

      console.log(chalk.blue('\n🛠️  Available Tools:'));
      console.log(chalk.blue('=================='));

      Object.entries(tools).forEach(([toolKey, tool]) => {
        if (options.category && tool.category !== options.category) return;
        if (options.status && tool.status !== options.status) return;

        const statusIcon = tool.installation_status === 'installed' ? '✅' : '⚠️';
        const categoryBadge = chalk.gray(`[${tool.category}]`);

        console.log(`${statusIcon} ${chalk.cyan(tool.name)} ${categoryBadge}`);
        console.log(`   ${chalk.gray(tool.description)}`);
        console.log(`   ${chalk.gray('Path:')} ${tool.path}`);
        console.log(`   ${chalk.gray('Version:')} ${tool.version}`);
        console.log('');
      });
    } catch (error) {
      console.error(chalk.red('Error listing tools:'), error.message);
    }
  });

// Install a tool
program
  .command('install <tool-name>')
  .description('Install a specific tool')
  .option('--force', 'Force reinstall even if already installed')
  .action(async (toolName, options) => {
    try {
      const registry = await loadRegistry();
      const tool = registry.tools_registry.tools[toolName];

      if (!tool) {
        console.error(chalk.red(`Tool "${toolName}" not found in registry`));
        process.exit(1);
      }

      if (tool.installation_status === 'installed' && !options.force) {
        console.log(chalk.yellow(`Tool "${toolName}" is already installed`));
        return;
      }

      console.log(chalk.blue(`🔧 Installing tool: ${tool.name}`));

      const toolPath = path.join(__dirname, toolName);
      const packageJsonPath = path.join(toolPath, 'package.json');

      // Check if package.json exists
      try {
        await fs.access(packageJsonPath);
      } catch (error) {
        console.error(chalk.red(`Package.json not found at: ${packageJsonPath}`));
        process.exit(1);
      }

      // Install dependencies
      console.log(chalk.gray('Installing dependencies...'));
      await runCommand('yarn', ['install'], { cwd: toolPath });

      // Install browsers if needed
      if (tool.dependencies.playwright) {
        console.log(chalk.gray('Installing browser engines...'));
        await runCommand('yarn', ['run', 'install-browsers'], { cwd: toolPath });
      }

      // Update registry
      tool.installation_status = 'installed';
      tool.last_updated = new Date().toISOString();
      await saveRegistry(registry);

      console.log(chalk.green(`✅ Tool "${toolName}" installed successfully!`));

    } catch (error) {
      console.error(chalk.red('Installation failed:'), error.message);
      process.exit(1);
    }
  });

// Run a tool command
program
  .command('run <tool-name> [args...]')
  .description('Run a tool command')
  .action(async (toolName, args) => {
    try {
      const registry = await loadRegistry();
      const tool = registry.tools_registry.tools[toolName];

      if (!tool) {
        console.error(chalk.red(`Tool "${toolName}" not found in registry`));
        process.exit(1);
      }

      if (tool.installation_status !== 'installed') {
        console.error(chalk.red(`Tool "${toolName}" is not installed. Run: levCompiler tools install ${toolName}`));
        process.exit(1);
      }

      const toolPath = path.join(__dirname, toolName);
      const cliPath = path.join(toolPath, tool.cli_entry);

      console.log(chalk.blue(`🚀 Running: ${tool.name}`));

      // Update last used
      tool.last_used = new Date().toISOString();
      await saveRegistry(registry);

      // Run the tool
      await runCommand('node', [cliPath, ...args], {
        cwd: toolPath,
        stdio: 'inherit'
      });

    } catch (error) {
      console.error(chalk.red('Tool execution failed:'), error.message);
      process.exit(1);
    }
  });

// Tool status
program
  .command('status [tool-name]')
  .description('Check tool status')
  .action(async (toolName) => {
    try {
      const registry = await loadRegistry();

      if (toolName) {
        const tool = registry.tools_registry.tools[toolName];
        if (!tool) {
          console.error(chalk.red(`Tool "${toolName}" not found`));
          process.exit(1);
        }

        displayToolStatus(tool);
      } else {
        console.log(chalk.blue('\n📊 Tools Status Overview:'));
        console.log(chalk.blue('========================='));

        Object.entries(registry.tools_registry.tools).forEach(([key, tool]) => {
          displayToolStatus(tool, true);
        });
      }
    } catch (error) {
      console.error(chalk.red('Error checking status:'), error.message);
    }
  });

// Validate tools organization
program
  .command('validate')
  .description('Validate tools organization against fundamentals')
  .action(async () => {
    try {
      console.log(chalk.blue('🔍 Validating tools organization...'));

      const registry = await loadRegistry();
      const violations = [];

      // Check registry compliance
      for (const [toolKey, tool] of Object.entries(registry.tools_registry.tools)) {
        const toolPath = path.join(__dirname, toolKey);

        // Check if tool directory exists
        try {
          await fs.access(toolPath);
        } catch (error) {
          violations.push(`Tool directory not found: ${toolPath}`);
          continue;
        }

        // Check required files
        const requiredFiles = ['package.json'];
        for (const file of requiredFiles) {
          try {
            await fs.access(path.join(toolPath, file));
          } catch (error) {
            violations.push(`Missing required file: ${toolKey}/${file}`);
          }
        }

        // Check required directories
        const requiredDirs = ['services', 'cli'];
        for (const dir of requiredDirs) {
          try {
            await fs.access(path.join(toolPath, dir));
          } catch (error) {
            violations.push(`Missing required directory: ${toolKey}/${dir}`);
          }
        }
      }

      if (violations.length === 0) {
        console.log(chalk.green('✅ All tools comply with organization requirements'));
      } else {
        console.log(chalk.red('❌ Violations found:'));
        violations.forEach(violation => {
          console.log(chalk.red(`  - ${violation}`));
        });
      }

    } catch (error) {
      console.error(chalk.red('Validation failed:'), error.message);
    }
  });

// Helper functions
async function loadRegistry() {
  try {
    const data = await fs.readFile(TOOLS_REGISTRY_PATH, 'utf8');
    return JSON.parse(data);
  } catch (error) {
    throw new Error(`Failed to load tools registry: ${error.message}`);
  }
}

async function saveRegistry(registry) {
  try {
    await fs.writeFile(TOOLS_REGISTRY_PATH, JSON.stringify(registry, null, 2));
  } catch (error) {
    throw new Error(`Failed to save tools registry: ${error.message}`);
  }
}

function displayToolStatus(tool, compact = false) {
  const statusIcon = tool.installation_status === 'installed' ? '✅' : '⚠️';
  const activeIcon = tool.status === 'active' ? '🟢' : '🔴';

  if (compact) {
    console.log(`${statusIcon}${activeIcon} ${chalk.cyan(tool.name)} - ${tool.installation_status}`);
  } else {
    console.log(chalk.blue(`\n📊 ${tool.name} Status:`));
    console.log(`${statusIcon} Installation: ${tool.installation_status}`);
    console.log(`${activeIcon} Status: ${tool.status}`);
    console.log(`📁 Path: ${tool.path}`);
    console.log(`🏷️  Version: ${tool.version}`);
    console.log(`📂 Category: ${tool.category}`);
    console.log(`🕒 Last Used: ${tool.last_used || 'Never'}`);
  }
}

function runCommand(command, args, options = {}) {
  return new Promise((resolve, reject) => {
    const child = spawn(command, args, {
      stdio: options.stdio || 'pipe',
      cwd: options.cwd || process.cwd()
    });

    if (options.stdio !== 'inherit') {
      child.stdout?.on('data', (data) => {
        console.log(data.toString());
      });

      child.stderr?.on('data', (data) => {
        console.error(data.toString());
      });
    }

    child.on('close', (code) => {
      if (code === 0) {
        resolve();
      } else {
        reject(new Error(`Command failed with exit code ${code}`));
      }
    });

    child.on('error', (error) => {
      reject(error);
    });
  });
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
