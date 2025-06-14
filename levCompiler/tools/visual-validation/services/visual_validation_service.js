const { chromium } = require('playwright');
const puppeteer = require('puppeteer');
const { PNG } = require('pngjs');
const pixelmatch = require('pixelmatch');
const sharp = require('sharp');
const fs = require('fs').promises;
const path = require('path');
const OpenAI = require('openai');
const fetch = require('node-fetch');
const TempScreenshotManager = require('../utils/temp_screenshot_manager');

class VisualValidationService {
  constructor(config) {
    this.config = config;
    this.openai = process.env.OPENAI_API_KEY ? new OpenAI({ apiKey: process.env.OPENAI_API_KEY }) : null;
    this.browsers = new Map();
    this.validationResults = new Map();
    this.tempScreenshotManager = config?.tempScreenshotManager || new TempScreenshotManager();
  }

  /**
   * Main validation orchestrator
   */
  async validateVisually(options) {
    const {
      targetUrl,
      targetDesignPath,
      projectPath,
      validationId = Date.now().toString()
    } = options;

    console.log(`🎯 Starting visual validation: ${validationId}`);

    try {
      // Step 1: Setup validation environment
      const serverInfo = await this.setupValidationEnvironment(projectPath, targetUrl);

      // Step 2: Capture screenshots
      const screenshots = await this.captureScreenshots(serverInfo.url, validationId);

      // Step 3: Load target design
      const targetAssets = await this.loadTargetDesign(targetDesignPath);

      // Step 4: Perform comparisons
      const comparisonResults = await this.performComparisons(screenshots, targetAssets);

      // Step 5: AI-powered analysis
      const aiAnalysis = await this.performAIAnalysis(screenshots, targetAssets, comparisonResults);

      // Step 6: Generate comprehensive report
      const report = await this.generateValidationReport({
        validationId,
        screenshots,
        comparisonResults,
        aiAnalysis,
        timestamp: new Date().toISOString()
      });

      // Step 7: Send to chat with visual assets
      await this.sendToChatWithAssets(report);

      return report;

    } catch (error) {
      console.error(`❌ Visual validation failed: ${error.message}`);
      throw error;
    } finally {
      await this.cleanup();
    }
  }

  /**
   * Setup validation environment
   */
  async setupValidationEnvironment(projectPath, targetUrl) {
    console.log('🔧 Setting up validation environment...');

    // Try to connect to existing server first
    if (targetUrl && await this.checkServerHealth(targetUrl)) {
      return { url: targetUrl, isExisting: true };
    }

    // Auto-detect and start development server
    const serverInfo = await this.startDevelopmentServer(projectPath);

    // Wait for server to be fully ready
    await this.waitForServerReady(serverInfo.url);

    return serverInfo;
  }

  /**
   * Capture screenshots across multiple viewports
   */
  async captureScreenshots(url, validationId) {
    console.log('📸 Capturing screenshots across viewports...');

    const screenshots = {};
    const viewports = this.config.screenshot_service.viewports;

    // Use Playwright for primary capture
    const browser = await chromium.launch({ headless: true });
    this.browsers.set('playwright', browser);

    try {
      for (const viewport of viewports) {
        console.log(`📱 Capturing ${viewport.name} viewport...`);

        const context = await browser.newContext({
          viewport: { width: viewport.width, height: viewport.height },
          deviceScaleFactor: 1
        });

        const page = await context.newPage();

        // Configure page for consistent screenshots
        await page.addStyleTag({
          content: `
            *, *::before, *::after {
              animation-duration: 0.01ms !important;
              animation-delay: -0.01ms !important;
              animation-iteration-count: 1 !important;
              background-attachment: initial !important;
              scroll-behavior: auto !important;
            }
          `
        });

        await page.goto(url, { waitUntil: 'networkidle', timeout: 30000 });

        // Capture full page screenshot
        const screenshotBuffer = await page.screenshot({
          fullPage: true,
          type: 'png'
        });

        const filename = `${validationId}_${viewport.name}_${Date.now()}.png`;
        const filepath = path.join(process.cwd(), 'validation_results', 'screenshots', filename);

        await fs.mkdir(path.dirname(filepath), { recursive: true });
        await fs.writeFile(filepath, screenshotBuffer);

        // Also save to temp storage for easy access
        const tempResult = await this.tempScreenshotManager.processAndStoreScreenshot(
          screenshotBuffer,
          { viewport: viewport.name, url, validationId, timestamp: Date.now() }
        );

        screenshots[viewport.name] = {
          filepath,
          buffer: screenshotBuffer,
          viewport,
          timestamp: new Date().toISOString(),
          tempScreenshot: tempResult.success ? tempResult.tempScreenshot : null
        };

        if (tempResult.success) {
          console.log(`📁 Temp screenshot: ${tempResult.tempScreenshot.url}`);
        }

        await context.close();
      }

      return screenshots;

    } catch (error) {
      console.error('Screenshot capture failed, trying fallback...', error);
      return await this.captureScreenshotsFallback(url, validationId);
    }
  }

  /**
   * Fallback screenshot capture using Puppeteer
   */
  async captureScreenshotsFallback(url, validationId) {
    console.log('🔄 Using Puppeteer fallback for screenshot capture...');

    const browser = await puppeteer.launch({ headless: true });
    this.browsers.set('puppeteer', browser);

    const screenshots = {};
    const viewports = this.config.screenshot_service.viewports;

    try {
      const page = await browser.newPage();

      for (const viewport of viewports) {
        await page.setViewport({
          width: viewport.width,
          height: viewport.height,
          deviceScaleFactor: 1
        });

        await page.goto(url, { waitUntil: 'networkidle0', timeout: 30000 });

        const screenshotBuffer = await page.screenshot({
          fullPage: true,
          type: 'png'
        });

        const filename = `${validationId}_${viewport.name}_fallback_${Date.now()}.png`;
        const filepath = path.join(process.cwd(), 'validation_results', 'screenshots', filename);

        await fs.mkdir(path.dirname(filepath), { recursive: true });
        await fs.writeFile(filepath, screenshotBuffer);

        screenshots[viewport.name] = {
          filepath,
          buffer: screenshotBuffer,
          viewport,
          timestamp: new Date().toISOString()
        };
      }

      return screenshots;
    } finally {
      await browser.close();
    }
  }

  /**
   * Load and prepare target design assets
   */
  async loadTargetDesign(targetDesignPath) {
    console.log('🎨 Loading target design assets...');

    const targetAssets = {};

    if (!targetDesignPath) {
      console.warn('⚠️ No target design provided, skipping visual comparison');
      return targetAssets;
    }

    try {
      const stats = await fs.stat(targetDesignPath);

      if (stats.isDirectory()) {
        // Load multiple design files from directory
        const files = await fs.readdir(targetDesignPath);
        const imageFiles = files.filter(file =>
          /\.(png|jpg|jpeg|gif|webp)$/i.test(file)
        );

        for (const file of imageFiles) {
          const filePath = path.join(targetDesignPath, file);
          const buffer = await fs.readFile(filePath);
          const filename = path.parse(file).name;

          targetAssets[filename] = {
            filepath: filePath,
            buffer,
            type: 'reference_design'
          };
        }
      } else {
        // Single design file
        const buffer = await fs.readFile(targetDesignPath);
        const filename = path.parse(targetDesignPath).name;

        targetAssets[filename] = {
          filepath: targetDesignPath,
          buffer,
          type: 'reference_design'
        };
      }

      return targetAssets;
    } catch (error) {
      console.error('Failed to load target design:', error);
      return targetAssets;
    }
  }

  /**
   * Perform pixel-level comparisons
   */
  async performComparisons(screenshots, targetAssets) {
    console.log('🔍 Performing visual comparisons...');

    const comparisonResults = {};

    for (const [viewportName, screenshot] of Object.entries(screenshots)) {
      comparisonResults[viewportName] = {};

      // Find matching target design (by viewport name or general match)
      const targetAsset = targetAssets[viewportName] ||
                         targetAssets['desktop'] ||
                         Object.values(targetAssets)[0];

      if (!targetAsset) {
        console.warn(`⚠️ No target design found for ${viewportName}`);
        continue;
      }

      try {
        // Primary comparison with ResembleJS
        const resembleResult = await this.compareWithPixelmatch(
          screenshot.buffer,
          targetAsset.buffer
        );

        // Fallback comparison with Pixelmatch if needed
        let pixelmatchResult = null;
        if (resembleResult.similarity < 0.7) {
          pixelmatchResult = await this.compareWithPixelmatch(
            screenshot.buffer,
            targetAsset.buffer
          );
        }

        comparisonResults[viewportName] = {
          resemble: resembleResult,
          pixelmatch: pixelmatchResult,
          primary_similarity: resembleResult.similarity,
          diff_image_path: resembleResult.diffImagePath,
          analysis_timestamp: new Date().toISOString()
        };

      } catch (error) {
        console.error(`Comparison failed for ${viewportName}:`, error);
        comparisonResults[viewportName] = {
          error: error.message,
          similarity: 0
        };
      }
    }

    return comparisonResults;
  }

  /**
   * ResembleJS comparison
   */
  async compareWithPixelmatch(actualBuffer, expectedBuffer) {
    try {
      // Parse PNG buffers
      const img1 = PNG.sync.read(actualBuffer);
      const img2 = PNG.sync.read(expectedBuffer);

      const { width, height } = img1;
      const diff = new PNG({ width, height });

      // Perform pixel-level comparison
      const startTime = Date.now();
      const numDiffPixels = pixelmatch(
        img1.data,
        img2.data,
        diff.data,
        width,
        height,
        {
          threshold: 0.1,
          includeAA: false,
          alpha: 0.1
        }
      );

      const totalPixels = width * height;
      const misMatchPercentage = (numDiffPixels / totalPixels) * 100;
      const similarity = 1 - (misMatchPercentage / 100);

      // Save diff image
      const diffImagePath = path.join(
        process.cwd(),
        'validation_results',
        'comparisons',
        `diff_${Date.now()}.png`
      );

      await fs.mkdir(path.dirname(diffImagePath), { recursive: true });
      await fs.writeFile(diffImagePath, PNG.sync.write(diff));

      return {
        similarity,
        misMatchPercentage,
        analysisTime: Date.now() - startTime,
        diffImagePath,
        dimensions: {
          width,
          height,
          totalPixels,
          diffPixels: numDiffPixels
        }
      };
    } catch (error) {
      console.error('Pixelmatch comparison failed:', error);
      throw error;
    }
  }

  /**
   * AI-powered visual analysis
   */
  async performAIAnalysis(screenshots, targetAssets, comparisonResults) {
    console.log('🤖 Performing AI-powered visual analysis...');

    if (!this.openai) {
      console.log('⚠️ OpenAI API key not available - skipping AI analysis');
      return this.generateFallbackAnalysisForAll(screenshots, comparisonResults);
    }

    const aiAnalysis = {};

    for (const [viewportName, screenshot] of Object.entries(screenshots)) {
      try {
        const base64Image = screenshot.buffer.toString('base64');

        const analysisPrompts = this.config.ai_analysis.analysis_prompts;
        const analysis = {};

        // Perform different types of analysis
        for (const [analysisType, prompt] of Object.entries(analysisPrompts)) {
          console.log(`🔬 Running ${analysisType} analysis for ${viewportName}...`);

          const response = await this.openai.chat.completions.create({
            model: "gpt-4-vision-preview",
            messages: [
              {
                role: "user",
                content: [
                  {
                    type: "text",
                    text: `${prompt}\n\nProvide a detailed analysis in JSON format with scores (0-1) and specific recommendations.`
                  },
                  {
                    type: "image_url",
                    image_url: {
                      url: `data:image/png;base64,${base64Image}`
                    }
                  }
                ]
              }
            ],
            max_tokens: 1000
          });

          try {
            analysis[analysisType] = JSON.parse(response.choices[0].message.content);
          } catch (parseError) {
            analysis[analysisType] = {
              raw_response: response.choices[0].message.content,
              score: 0.5,
              recommendations: ["AI analysis parsing failed"]
            };
          }
        }

        aiAnalysis[viewportName] = analysis;

      } catch (error) {
        console.error(`AI analysis failed for ${viewportName}:`, error);
        aiAnalysis[viewportName] = {
          error: error.message,
          fallback_analysis: await this.generateFallbackAnalysis(comparisonResults[viewportName])
        };
      }
    }

    return aiAnalysis;
  }

  /**
   * Generate comprehensive validation report
   */
  async generateValidationReport(data) {
    console.log('📄 Generating validation report...');

    const { validationId, screenshots, comparisonResults, aiAnalysis, timestamp } = data;

    // Calculate overall scores
    const overallScores = this.calculateOverallScores(comparisonResults, aiAnalysis);

    // Generate recommendations
    const recommendations = this.generateRecommendations(comparisonResults, aiAnalysis);

    const report = {
      validation_id: validationId,
      timestamp,
      summary: {
        total_viewports: Object.keys(screenshots).length,
        average_similarity: overallScores.averageSimilarity,
        overall_score: overallScores.overallScore,
        status: overallScores.overallScore >= 0.85 ? 'PASSED' : 'NEEDS_IMPROVEMENT'
      },
      detailed_results: {
        screenshots: Object.keys(screenshots).reduce((acc, viewport) => {
          acc[viewport] = {
            image_path: screenshots[viewport].filepath,
            viewport_info: screenshots[viewport].viewport,
            comparison: comparisonResults[viewport],
            ai_analysis: aiAnalysis[viewport]
          };
          return acc;
        }, {}),
        comparison_results: comparisonResults,
        ai_analysis: aiAnalysis
      },
      recommendations,
      assets: {
        screenshots: Object.values(screenshots).map(s => s.filepath),
        diff_images: Object.values(comparisonResults)
          .map(r => r.diff_image_path)
          .filter(Boolean)
      }
    };

    // Save report
    const reportPath = path.join(
      process.cwd(),
      'validation_results',
      `validation_report_${validationId}.json`
    );
    await fs.mkdir(path.dirname(reportPath), { recursive: true });
    await fs.writeFile(reportPath, JSON.stringify(report, null, 2));

    report.report_path = reportPath;
    return report;
  }

  /**
   * Send results to chat with visual assets
   */
  async sendToChatWithAssets(report) {
    console.log('💬 Sending validation results to chat...');

    // This would integrate with your chat system
    // For now, we'll create a formatted message
    const chatMessage = this.formatChatMessage(report);

    // Save formatted message for chat integration
    const messagePath = path.join(
      process.cwd(),
      'validation_results',
      `chat_message_${report.validation_id}.md`
    );

    await fs.writeFile(messagePath, chatMessage);

    console.log('📤 Chat message prepared:', messagePath);
    return messagePath;
  }

  /**
   * Format results for chat display
   */
  formatChatMessage(report) {
    const { summary, detailed_results, recommendations, assets } = report;

    let message = `# 🎯 Visual Validation Results\n\n`;
    message += `**Status:** ${summary.status} ${summary.status === 'PASSED' ? '✅' : '⚠️'}\n`;
    message += `**Overall Score:** ${(summary.overall_score * 100).toFixed(1)}%\n`;
    message += `**Average Similarity:** ${(summary.average_similarity * 100).toFixed(1)}%\n`;
    message += `**Viewports Tested:** ${summary.total_viewports}\n\n`;

    // Viewport results
    message += `## 📱 Viewport Results\n\n`;
    for (const [viewport, result] of Object.entries(detailed_results.screenshots)) {
      const similarity = result.comparison?.primary_similarity || 0;
      const status = similarity >= 0.85 ? '✅' : similarity >= 0.7 ? '⚠️' : '❌';

      message += `### ${viewport.toUpperCase()} ${status}\n`;
      message += `- **Similarity:** ${(similarity * 100).toFixed(1)}%\n`;
      message += `- **Screenshot:** \`${path.basename(result.image_path)}\`\n`;

      if (result.comparison?.diff_image_path) {
        message += `- **Diff Image:** \`${path.basename(result.comparison.diff_image_path)}\`\n`;
      }

      message += `\n`;
    }

    // Recommendations
    if (recommendations.length > 0) {
      message += `## 🔧 Recommendations\n\n`;
      recommendations.forEach((rec, index) => {
        message += `${index + 1}. **${rec.category}:** ${rec.description}\n`;
        if (rec.priority === 'high') message += `   ⚡ **High Priority**\n`;
        message += `\n`;
      });
    }

    // Assets
    message += `## 📎 Generated Assets\n\n`;
    message += `- Screenshots: ${assets.screenshots.length} files\n`;
    message += `- Diff Images: ${assets.diff_images.length} files\n`;
    message += `- Full Report: \`validation_report_${report.validation_id}.json\`\n`;

    return message;
  }

  /**
   * Helper methods
   */
  calculateOverallScores(comparisonResults, aiAnalysis) {
    const similarities = Object.values(comparisonResults)
      .map(result => result.primary_similarity || 0)
      .filter(s => s > 0);

    const averageSimilarity = similarities.length > 0
      ? similarities.reduce((a, b) => a + b, 0) / similarities.length
      : 0;

    // Calculate overall score (weighted combination)
    const overallScore = averageSimilarity * 0.7 +
      (this.getAverageAIScore(aiAnalysis) * 0.3);

    return { averageSimilarity, overallScore };
  }

  getAverageAIScore(aiAnalysis) {
    const allScores = [];

    Object.values(aiAnalysis).forEach(viewport => {
      Object.values(viewport).forEach(analysis => {
        if (analysis.score) allScores.push(analysis.score);
      });
    });

    return allScores.length > 0
      ? allScores.reduce((a, b) => a + b, 0) / allScores.length
      : 0.5;
  }

  generateRecommendations(comparisonResults, aiAnalysis) {
    const recommendations = [];

    // Generate recommendations based on comparison results and AI analysis
    Object.entries(comparisonResults).forEach(([viewport, result]) => {
      if (result.primary_similarity < 0.85) {
        recommendations.push({
          category: 'Visual Differences',
          description: `${viewport} viewport has ${((1 - result.primary_similarity) * 100).toFixed(1)}% visual differences from target design`,
          priority: result.primary_similarity < 0.7 ? 'high' : 'medium',
          viewport
        });
      }
    });

    // Add AI-generated recommendations
    Object.entries(aiAnalysis).forEach(([viewport, analysis]) => {
      Object.entries(analysis).forEach(([type, result]) => {
        if (result.recommendations) {
          result.recommendations.forEach(rec => {
            recommendations.push({
              category: type.replace('_', ' ').toUpperCase(),
              description: rec,
              priority: result.score < 0.7 ? 'high' : 'medium',
              viewport
            });
          });
        }
      });
    });

    return recommendations;
  }

  async cleanup() {
    console.log('🧹 Cleaning up validation resources...');

    // Close all browsers
    for (const [name, browser] of this.browsers) {
      try {
        await browser.close();
        console.log(`✅ Closed ${name} browser`);
      } catch (error) {
        console.error(`Failed to close ${name} browser:`, error);
      }
    }

    this.browsers.clear();
  }

  // Additional helper methods
  async checkServerHealth(url) {
    try {
      const response = await fetch(url, { method: 'HEAD', timeout: 5000 });
      return response.ok;
    } catch (error) {
      console.log(`URL ${url} is not accessible:`, error.message);
      return false;
    }
  }

  async startDevelopmentServer(projectPath) {
    // For now, throw an error since server detection is not implemented
    throw new Error('Development server auto-start not implemented. Please provide a running server URL.');
  }

  async waitForServerReady(url) {
    console.log(`Waiting for server to be ready at ${url}...`);
    // Simple implementation - just check if accessible
    const maxAttempts = 30;
    for (let i = 0; i < maxAttempts; i++) {
      if (await this.checkServerHealth(url)) {
        console.log('✅ Server is ready');
        return;
      }
      await new Promise(resolve => setTimeout(resolve, 1000));
    }
    throw new Error(`Server at ${url} did not become ready within 30 seconds`);
  }
  async compareWithPixelmatch(actualBuffer, expectedBuffer) { /* ... */ }
  async generateFallbackAnalysisForAll(screenshots, comparisonResults) {
    const analysis = {};
    for (const [viewportName, screenshot] of Object.entries(screenshots)) {
      analysis[viewportName] = await this.generateFallbackAnalysis(comparisonResults[viewportName] || {});
    }
    return analysis;
  }

  async generateFallbackAnalysis(comparisonResult) {
    const similarity = comparisonResult.similarity || 0;

    return {
      layout_check: {
        score: similarity,
        recommendations: similarity < 0.85 ? ['Visual differences detected - manual review recommended'] : ['Layout appears consistent'],
        details: 'Basic similarity analysis without AI vision'
      },
      color_scheme: {
        score: similarity,
        recommendations: similarity < 0.8 ? ['Color differences may be present'] : ['Color scheme appears consistent'],
        details: 'Pixel-level comparison analysis'
      },
      typography: {
        score: similarity,
        recommendations: similarity < 0.9 ? ['Text rendering differences possible'] : ['Typography appears consistent'],
        details: 'Basic visual comparison'
      },
      responsive_behavior: {
        score: similarity,
        recommendations: ['Manual testing recommended for responsive behavior validation'],
        details: 'Automated responsive testing not available without AI analysis'
      }
    };
  }
}

module.exports = VisualValidationService;
