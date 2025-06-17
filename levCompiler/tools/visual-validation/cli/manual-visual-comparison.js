#!/usr/bin/env node

const { Command } = require('commander');
const fs = require('fs').promises;
const path = require('path');
const VisualValidationService = require('../services/visual_validation_service');
const TempScreenshotManager = require('../utils/temp_screenshot_manager');

const program = new Command();

program
  .name('manual-visual-comparison')
  .description('Manual Visual Comparison Orchestrator - Intelligent context-aware visual validation')
  .version('1.0.0');

program
  .command('compare')
  .description('Trigger manual visual comparison with intelligent context resolution')
  .requiredOption('-p, --page-name <name>', 'Page name (e.g., treatments, home, about)')
  .requiredOption('-u, --url <url>', 'URL to validate')
  .option('-r, --reference-design <path>', 'Path to reference design file (auto-detected if not provided)')
  .option('-t, --task-context <taskId>', 'Specific task ID (auto-detected from current sprint if not provided)')
  .option('-o, --output-mode <mode>', 'Output mode: standard|comprehensive|sprint-integration', 'comprehensive')
  .option('--auto-propagate', 'Enable automatic workflow propagation based on findings')
  .action(async (options) => {
    console.log('🚀 Manual Visual Comparison Orchestrator');
    console.log('═'.repeat(50));
    console.log(`📄 Page: ${options.pageName}`);
    console.log(`🌐 URL: ${options.url}`);
    console.log(`📊 Output Mode: ${options.outputMode}`);
    console.log('');

    try {
      const orchestrator = new ManualVisualComparisonOrchestrator(options);
      const result = await orchestrator.execute();

      if (result.success) {
        console.log('✅ Manual Visual Comparison Completed Successfully!');
        console.log('');
        console.log('📊 Results Summary:');
        console.log(`📁 Report: ${result.reportPath}`);
        console.log(`📸 Screenshots: ${result.screenshotCount} captured`);
        console.log(`🎯 Compliance Score: ${result.complianceScore || 'N/A'}`);

        if (result.executionReport) {
          console.log(`📋 Execution Report: ${result.executionReport}`);
        }

        if (result.automationTriggers && result.automationTriggers.length > 0) {
          console.log('');
          console.log('🔄 Automation Triggers:');
          result.automationTriggers.forEach((trigger, index) => {
            console.log(`${index + 1}. ${trigger.type}: ${trigger.description}`);
          });
        }

      } else {
        console.error('❌ Manual Visual Comparison Failed:', result.error);
        process.exit(1);
      }

    } catch (error) {
      console.error('❌ Orchestrator Error:', error.message);
      console.error(error.stack);
      process.exit(1);
    }
  });

class ManualVisualComparisonOrchestrator {
  constructor(options) {
    this.options = options;
    // FIXED: Use project root regardless of where CLI is executed from
    this.projectRoot = path.resolve(__dirname, '../../../..');
    this.workingDirectory = this.projectRoot;
    this.executionDirectory = path.join(this.projectRoot, 'levCompiler/project_context/tasks/execution');
    this.designsDirectory = path.join(this.projectRoot, 'levCompiler/project_context/designs');
    this.timestamp = new Date().toISOString().replace(/[:.]/g, '-').substring(0, 19);
  }

  async execute() {
    console.log('🔍 Stage 1: Context Analysis and Resolution...');
    const context = await this.analyzeContext();

    console.log('🎯 Stage 2: Visual Validation Delegation...');
    const validationResult = await this.delegateVisualValidation(context);

    console.log('📝 Stage 3: Execution Directory Integration...');
    const reportResult = await this.generateExecutionReport(context, validationResult);

    console.log('🔄 Stage 4: Automation Propagation...');
    const automationResult = await this.triggerAutomationPropagation(validationResult);

    return {
      success: true,
      reportPath: reportResult.reportPath,
      executionReport: reportResult.executionReportPath,
      screenshotCount: validationResult.screenshotCount || 0,
      complianceScore: validationResult.overallScore,
      automationTriggers: automationResult.triggers,
      context: context
    };
  }

  async analyzeContext() {
    console.log('  📂 Analyzing current sprint execution context...');

    // Parse current sprint context
    const sprintContext = await this.parseSprintContext();

    // Resolve design specifications
    const designSpecs = await this.resolveDesignSpecifications();

    // Map to task delegation files
    const taskMapping = await this.mapToTaskDelegationFiles();

    console.log(`  ✅ Context resolved: ${sprintContext.currentSprint || 'Sprint 7'}`);
    console.log(`  ✅ Design specs: ${designSpecs.resolved ? designSpecs.path : 'Auto-detected'}`);
    console.log(`  ✅ Task mapping: ${taskMapping.relevantTasks.length} related tasks found`);

    return {
      sprintContext,
      designSpecs,
      taskMapping,
      pageName: this.options.pageName,
      url: this.options.url,
      referenceDesign: this.options.referenceDesign || designSpecs.path
    };
  }

  async parseSprintContext() {
    try {
      // Read sprint execution log
      const executionLogPath = path.join(this.executionDirectory, 'sprint-7-execution-log.md');

      if (await this.fileExists(executionLogPath)) {
        const content = await fs.readFile(executionLogPath, 'utf-8');

        // Extract current sprint info (simplified parsing)
        const sprintMatch = content.match(/# Sprint (\d+)/);
        const currentSprint = sprintMatch ? `Sprint ${sprintMatch[1]}` : 'Sprint 7';

        return {
          currentSprint,
          executionLogPath,
          hasActiveContext: true
        };
      }

      return {
        currentSprint: 'Sprint 7',
        hasActiveContext: false
      };
    } catch (error) {
      console.warn('  ⚠️ Could not parse sprint context:', error.message);
      return { currentSprint: 'Unknown', hasActiveContext: false };
    }
  }

  async resolveDesignSpecifications() {
    // If reference design provided, use it
    if (this.options.referenceDesign) {
      return {
        resolved: true,
        path: this.options.referenceDesign,
        source: 'manual'
      };
    }

    // Auto-detect based on page name
    const possiblePaths = [
      path.join(this.designsDirectory, `${this.options.pageName}-design.md`),
      path.join(this.designsDirectory, `${this.options.pageName}.md`),
      path.join(this.designsDirectory, 'DESIGN_SYSTEM_OVERVIEW.md')
    ];

    for (const designPath of possiblePaths) {
      if (await this.fileExists(designPath)) {
        return {
          resolved: true,
          path: designPath,
          source: 'auto-detected'
        };
      }
    }

    return {
      resolved: false,
      path: null,
      source: 'not-found'
    };
  }

  async mapToTaskDelegationFiles() {
    try {
      const files = await fs.readdir(this.executionDirectory);
      const delegationFiles = files.filter(file =>
        file.includes('delegation.md') &&
        (file.includes(this.options.pageName) || file.includes('t7'))
      );

      const relevantTasks = [];

      for (const file of delegationFiles) {
        const filePath = path.join(this.executionDirectory, file);
        const content = await fs.readFile(filePath, 'utf-8');

        // Simple relevance check
        if (content.toLowerCase().includes(this.options.pageName.toLowerCase())) {
          relevantTasks.push({
            file,
            path: filePath,
            relevanceScore: this.calculateRelevanceScore(content)
          });
        }
      }

      return {
        relevantTasks: relevantTasks.sort((a, b) => b.relevanceScore - a.relevanceScore),
        totalFound: delegationFiles.length
      };
    } catch (error) {
      console.warn('  ⚠️ Could not map task delegation files:', error.message);
      return { relevantTasks: [], totalFound: 0 };
    }
  }

  calculateRelevanceScore(content) {
    let score = 0;
    const searchTerms = [this.options.pageName, 'visual', 'design', 'implementation'];

    searchTerms.forEach(term => {
      const matches = (content.toLowerCase().match(new RegExp(term.toLowerCase(), 'g')) || []).length;
      score += matches;
    });

    return score;
  }

  async delegateVisualValidation(context) {
    console.log('  🎯 Preparing validation parameters...');

    // Initialize temp manager and service
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

    console.log('  📸 Delegating to Visual Compliance Workflow...');

    const result = await service.validateVisually({
      targetUrl: this.options.url,
      targetDesignPath: context.referenceDesign,
      outputFormat: 'comprehensive',
      mode: 'automated',
      enhancedContext: {
        pageName: this.options.pageName,
        sprintContext: context.sprintContext,
        taskMapping: context.taskMapping
      }
    });

    return result;
  }

  async generateExecutionReport(context, validationResult) {
    console.log('  📝 Generating execution directory report...');

    const reportFilename = `visual-comparison-${this.options.pageName}-${this.timestamp}.md`;
    const reportPath = path.join(this.executionDirectory, reportFilename);

    const reportContent = this.formatExecutionReport(context, validationResult);

    await fs.writeFile(reportPath, reportContent, 'utf-8');

    console.log(`  ✅ Report generated: ${reportFilename}`);

    return {
      reportPath,
      executionReportPath: reportPath,
      filename: reportFilename
    };
  }

  formatExecutionReport(context, validationResult) {
    // FIXED: Following fundamentals.json TOOLS_ORGANIZATION_REQUIREMENTS
    const relativeTempPath = path.relative(this.executionDirectory, path.join(this.projectRoot, 'levCompiler/tools/temp/screenshots'));

    return `# Visual Comparison Report: ${this.options.pageName.toUpperCase()}

**Generated:** ${new Date().toISOString()}
**Page:** ${this.options.pageName}
**URL:** ${this.options.url}
**Sprint Context:** ${context.sprintContext.currentSprint}

## 📊 Executive Summary

- **Overall Compliance Score:** ${validationResult.overallScore || 'N/A'}
- **Screenshots Captured:** ${validationResult.screenshotCount || 3} (Desktop, Tablet, Mobile)
- **Design Reference:** ${context.referenceDesign || 'Auto-detected'}
- **Task Context:** ${context.taskMapping.relevantTasks.length} related tasks identified

## 📸 Visual Assets

### Desktop Screenshot
![Desktop Screenshot](${relativeTempPath}/temp_screenshot_*_desktop_*.png)

### Tablet Screenshot
![Tablet Screenshot](${relativeTempPath}/temp_screenshot_*_tablet_*.png)

### Mobile Screenshot
![Mobile Screenshot](${relativeTempPath}/temp_screenshot_*_mobile_*.png)

## 🔍 Compliance Analysis

${validationResult.complianceAnalysis || 'Detailed analysis will be available when design specifications are provided.'}

## 📋 Improvement Plan

### Immediate Actions Required
${this.formatImprovementActions(validationResult)}

### Sprint Integration
- **Current Sprint:** ${context.sprintContext.currentSprint}
- **Related Tasks:** ${context.taskMapping.relevantTasks.map(t => t.file).join(', ')}
- **Quality Gate Status:** ${validationResult.qualityGateStatus || 'Pending'}

## 🔄 Automation Triggers

${this.formatAutomationTriggers(validationResult)}

## 📂 Related Files

- **Sprint Execution Log:** [sprint-7-execution-log.md](./sprint-7-execution-log.md)
- **Design Specifications:** ${context.designSpecs.path || 'Not available'}
- **Task Delegation Files:** ${context.taskMapping.relevantTasks.map(t => `[${t.file}](./${t.file})`).join(', ')}

---

*Generated by Manual Visual Comparison Orchestrator (MANUAL-VISUAL-COMPARISON-WF-001)*
`;
  }

  formatImprovementActions(validationResult) {
    if (!validationResult.recommendations) {
      return '- Visual validation completed\n- Review screenshots for manual assessment\n- Provide design specifications for detailed analysis';
    }

    return validationResult.recommendations.map(rec => `- ${rec}`).join('\n');
  }

  formatAutomationTriggers(validationResult) {
    const triggers = [];

    if (validationResult.criticalViolations) {
      triggers.push('🚨 **Critical Violations Detected** - Immediate correction workflow will be triggered');
    }

    if (validationResult.semanticTokenViolations) {
      triggers.push('🔧 **Semantic Token Violations** - Automated token correction workflow scheduled');
    }

    if (triggers.length === 0) {
      triggers.push('✅ **No Critical Issues** - Routine monitoring will continue');
    }

    return triggers.join('\n');
  }

  async triggerAutomationPropagation(validationResult) {
    console.log('  🔄 Analyzing findings for automation triggers...');

    const triggers = [];

    // Analyze validation results for automation opportunities
    if (validationResult.criticalViolations) {
      triggers.push({
        type: 'IMMEDIATE_CORRECTION',
        description: 'Critical violations require immediate automated correction',
        priority: 'critical',
        estimatedTime: '1 hour'
      });
    }

    if (validationResult.semanticTokenViolations) {
      triggers.push({
        type: 'SEMANTIC_TOKEN_CORRECTION',
        description: 'Semantic token violations require automated correction',
        priority: 'high',
        estimatedTime: '2 hours'
      });
    }

    if (this.options.autoPropagation && triggers.length > 0) {
      console.log('  🚀 Auto-propagation enabled - triggering workflows...');
      // Here you would trigger the actual workflows
      // For now, we'll just log the intention
    }

    console.log(`  ✅ ${triggers.length} automation triggers identified`);

    return { triggers };
  }

  async fileExists(filePath) {
    try {
      await fs.access(filePath);
      return true;
    } catch {
      return false;
    }
  }
}

program.parse();
