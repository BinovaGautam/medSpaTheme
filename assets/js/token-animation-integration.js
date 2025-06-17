/**
 * T8.5 Token Animation Integration
 *
 * Integration layer connecting Token Animation System with live preview
 * for seamless animated transitions and enhanced user experience
 *
 * @package MedSpaTheme
 * @version 1.0.0 - T8.5 Implementation
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 * @sprint SPRINT-8-EXTENSIBLE-SEMANTIC-INTEGRATION
 */

(function($) {
    'use strict';

    // Ensure dependencies are available
    if (typeof window.TokenAnimationSystem === 'undefined') {
        console.warn('TokenAnimationIntegration: TokenAnimationSystem not available');
        return;
    }

    /**
     * Token Animation Integration Class
     */
    class TokenAnimationIntegration {
        constructor() {
            this.animationSystem = window.tokenAnimationSystem;
            this.previewSystem = window.semanticTokenCustomizerPreviewEnhanced;
            this.isIntegrated = false;
            this.animationQueue = [];
            this.lastPaletteChange = null;

            // Integration settings
            this.settings = {
                enableAnimations: true,
                coordinatedPaletteChanges: true,
                staggerDelay: 50,
                defaultDuration: 300,
                defaultEasing: 'easeInOut',
                performanceMode: 'balanced' // 'performance', 'balanced', 'quality'
            };

            this.init();
        }

        /**
         * Initialize the integration system
         */
        init() {
            console.log('TokenAnimationIntegration: Initializing animation integration');

            this.setupIntegration();
            this.enhancePreviewSystem();
            this.setupAnimationControls();
            this.bindEvents();

            this.isIntegrated = true;
            console.log('TokenAnimationIntegration: Integration complete');
        }

        /**
         * Setup integration with existing systems
         */
        setupIntegration() {
            // Enhance the preview system with animation capabilities
            if (this.previewSystem) {
                this.integrateWithPreviewSystem();
            }

            // Listen for animation system events
            if (this.animationSystem) {
                this.integrateWithAnimationSystem();
            }

            // Setup WordPress Customizer integration
            if (typeof wp !== 'undefined' && wp.customize) {
                this.integrateWithCustomizer();
            }
        }

        /**
         * Integrate with existing preview system
         */
        integrateWithPreviewSystem() {
            // Override palette change handler to include animations
            const originalHandlePaletteChange = this.previewSystem.handleEnhancedPaletteChange;

            this.previewSystem.handleEnhancedPaletteChange = (newPalette) => {
                console.log('TokenAnimationIntegration: Intercepting palette change for animation');

                if (this.settings.enableAnimations) {
                    this.handleAnimatedPaletteChange(newPalette);
                } else {
                    // Fall back to original behavior
                    originalHandlePaletteChange.call(this.previewSystem, newPalette);
                }
            };

            // Enhance CSS application with animation support
            const originalApplyCSS = this.previewSystem.applyEnhancedCSSToPreview;

            this.previewSystem.applyEnhancedCSSToPreview = async (css) => {
                if (this.settings.enableAnimations) {
                    await this.applyAnimatedCSS(css);
                } else {
                    return originalApplyCSS.call(this.previewSystem, css);
                }
            };
        }

        /**
         * Integrate with animation system
         */
        integrateWithAnimationSystem() {
            // Listen for animation completion events
            document.addEventListener('paletteAnimationComplete', (event) => {
                this.handleAnimationComplete(event.detail);
            });

            // Monitor animation performance
            this.startAnimationPerformanceMonitoring();
        }

        /**
         * Integrate with WordPress Customizer
         */
        integrateWithCustomizer() {
            // Add animation controls to customizer
            wp.customize.bind('ready', () => {
                this.addCustomizerAnimationControls();
            });

            // Listen for customizer settings changes
            wp.customize('enable_token_animations', (value) => {
                value.bind((newValue) => {
                    this.settings.enableAnimations = newValue;
                });
            });

            wp.customize('animation_duration', (value) => {
                value.bind((newValue) => {
                    this.settings.defaultDuration = parseInt(newValue) || 300;
                });
            });

            wp.customize('animation_easing', (value) => {
                value.bind((newValue) => {
                    this.settings.defaultEasing = newValue || 'easeInOut';
                });
            });
        }

        /**
         * Handle animated palette change
         */
        async handleAnimatedPaletteChange(newPalette) {
            console.log('TokenAnimationIntegration: Starting animated palette change to:', newPalette);

            // Prevent rapid successive changes
            if (this.lastPaletteChange && Date.now() - this.lastPaletteChange < 100) {
                console.log('TokenAnimationIntegration: Throttling rapid palette change');
                return;
            }

            this.lastPaletteChange = Date.now();

            try {
                // Show loading state with animation
                this.showAnimatedLoadingState();

                // Get palette data
                const paletteData = this.getPaletteData(newPalette);
                if (!paletteData) {
                    console.warn('TokenAnimationIntegration: No palette data found');
                    return;
                }

                // Create coordinated animation sequence
                await this.createPaletteAnimationSequence(paletteData);

                // Update backend if needed
                if (this.previewSystem.updateBackendPalette) {
                    await this.previewSystem.updateBackendPalette(newPalette);
                }

                // Show success state
                this.showAnimatedSuccessState();

            } catch (error) {
                console.error('TokenAnimationIntegration: Animation error:', error);
                this.showAnimatedErrorState(error.message);
            }
        }

        /**
         * Create coordinated palette animation sequence
         */
        async createPaletteAnimationSequence(paletteData) {
            const { palette, paletteId } = paletteData;

            if (!palette || !palette.colors) {
                throw new Error('Invalid palette data');
            }

            // Generate CSS for the new palette
            const newCSS = await this.generateAnimatedPaletteCSS(paletteData);

            // Create animation sequence
            const animations = [];
            let delay = 0;

            // Color animations with staggered timing
            Object.entries(palette.colors).forEach(([role, colorData], index) => {
                const color = colorData.hex || colorData;
                const currentColor = this.getCurrentTokenValue(`--color-${role}`);

                if (currentColor !== color) {
                    animations.push({
                        type: 'color',
                        tokenName: `--color-${role}`,
                        fromValue: currentColor,
                        toValue: color,
                        duration: this.settings.defaultDuration,
                        easing: this.settings.defaultEasing,
                        priority: role === 'primary' ? 4 : 3,
                        delay: this.settings.coordinatedPaletteChanges ? delay : 0,
                        metadata: { role, paletteId }
                    });

                    delay += this.settings.staggerDelay;
                }
            });

            // Execute animations
            if (animations.length > 0) {
                await this.executeAnimationSequence(animations, {
                    coordinated: this.settings.coordinatedPaletteChanges,
                    onProgress: (progress) => this.updateAnimationProgress(progress),
                    onComplete: () => this.onPaletteAnimationComplete(paletteId)
                });
            }

            // Apply any remaining CSS changes
            await this.applyRemainingCSS(newCSS);
        }

        /**
         * Generate animated palette CSS
         */
        async generateAnimatedPaletteCSS(paletteData) {
            // Use the existing CSS generation from preview system
            if (this.previewSystem && this.previewSystem.generateEnhancedPaletteCSS) {
                return await this.previewSystem.generateEnhancedPaletteCSS(paletteData.paletteId);
            }

            // Fallback CSS generation
            const { palette } = paletteData;
            const cssRules = [];

            Object.entries(palette.colors).forEach(([role, colorData]) => {
                const color = colorData.hex || colorData;
                cssRules.push(`--color-${role}: ${color};`);
            });

            return `:root {\n${cssRules.join('\n')}\n}`;
        }

        /**
         * Execute animation sequence
         */
        async executeAnimationSequence(animations, options = {}) {
            return new Promise((resolve, reject) => {
                let completedAnimations = 0;
                const totalAnimations = animations.length;

                const onAnimationComplete = () => {
                    completedAnimations++;

                    if (options.onProgress) {
                        options.onProgress(completedAnimations / totalAnimations);
                    }

                    if (completedAnimations === totalAnimations) {
                        if (options.onComplete) {
                            options.onComplete();
                        }
                        resolve();
                    }
                };

                // Queue all animations
                animations.forEach(animation => {
                    const animationId = this.animationSystem.queueAnimation(animation, {
                        onComplete: onAnimationComplete
                    });

                    if (!animationId) {
                        onAnimationComplete(); // Count as completed if failed to queue
                    }
                });

                // Fallback timeout
                setTimeout(() => {
                    if (completedAnimations < totalAnimations) {
                        console.warn('TokenAnimationIntegration: Animation timeout, resolving anyway');
                        resolve();
                    }
                }, (this.settings.defaultDuration * 2) + 1000);
            });
        }

        /**
         * Apply animated CSS changes
         */
        async applyAnimatedCSS(css) {
            // Parse CSS to extract token changes
            const tokenChanges = this.parseTokenChanges(css);

            if (tokenChanges.length === 0) {
                // No token changes, apply directly
                return this.applyCSSDirect(css);
            }

            // Create animations for token changes
            const animations = tokenChanges.map((change, index) => ({
                type: this.detectTokenType(change.property),
                tokenName: change.property,
                fromValue: this.getCurrentTokenValue(change.property),
                toValue: change.value,
                duration: this.settings.defaultDuration,
                easing: this.settings.defaultEasing,
                priority: 2,
                delay: index * 25 // Slight stagger
            }));

            // Execute animations
            await this.executeAnimationSequence(animations);
        }

        /**
         * Parse CSS to extract token changes
         */
        parseTokenChanges(css) {
            const changes = [];
            const customPropertyRegex = /--([\w-]+):\s*([^;]+);/g;
            let match;

            while ((match = customPropertyRegex.exec(css)) !== null) {
                changes.push({
                    property: `--${match[1]}`,
                    value: match[2].trim()
                });
            }

            return changes;
        }

        /**
         * Apply CSS directly without animation
         */
        applyCSSDirect(css) {
            const styleId = 'semantic-token-animated-styles';
            let styleElement = document.getElementById(styleId);

            if (!styleElement) {
                styleElement = document.createElement('style');
                styleElement.id = styleId;
                document.head.appendChild(styleElement);
            }

            styleElement.textContent = css;
        }

        /**
         * Apply remaining CSS after animations
         */
        async applyRemainingCSS(css) {
            // Apply any non-token CSS rules
            const nonTokenCSS = this.extractNonTokenCSS(css);
            if (nonTokenCSS) {
                this.applyCSSDirect(nonTokenCSS);
            }
        }

        /**
         * Extract non-token CSS rules
         */
        extractNonTokenCSS(css) {
            // Remove custom property declarations, keep other rules
            return css.replace(/--[\w-]+:\s*[^;]+;/g, '').trim();
        }

        /**
         * Show animated loading state
         */
        showAnimatedLoadingState() {
            $('body').addClass('semantic-preview-loading token-animating');

            // Add shimmer effect to key elements
            $('.btn, .treatment-card, .staff-card').addClass('token-shimmer');

            // Show progress indicator
            this.showAnimationProgress(0);
        }

        /**
         * Show animated success state
         */
        showAnimatedSuccessState() {
            $('body').removeClass('semantic-preview-loading').addClass('palette-changing');
            $('.btn, .treatment-card, .staff-card').removeClass('token-shimmer').addClass('token-pulse');

            // Remove effects after animation
            setTimeout(() => {
                $('body').removeClass('palette-changing token-animating');
                $('.btn, .treatment-card, .staff-card').removeClass('token-pulse');
                this.hideAnimationProgress();
            }, 600);
        }

        /**
         * Show animated error state
         */
        showAnimatedErrorState(message) {
            $('body').removeClass('semantic-preview-loading palette-changing token-animating');
            $('.btn, .treatment-card, .staff-card').removeClass('token-shimmer token-pulse');

            // Show error notification
            this.showNotification('error', `Animation failed: ${message}`);
            this.hideAnimationProgress();
        }

        /**
         * Show animation progress
         */
        showAnimationProgress(progress) {
            let progressBar = $('#token-animation-progress');

            if (progressBar.length === 0) {
                progressBar = $(`
                    <div id="token-animation-progress" class="token-animation-progress">
                        <div class="progress-bar">
                            <div class="progress-fill"></div>
                        </div>
                        <div class="progress-text">Animating palette...</div>
                    </div>
                `);

                $('body').append(progressBar);

                // Add styles
                if (!$('#token-animation-progress-styles').length) {
                    $('head').append(`
                        <style id="token-animation-progress-styles">
                            .token-animation-progress {
                                position: fixed;
                                top: 20px;
                                right: 20px;
                                background: rgba(255, 255, 255, 0.95);
                                border: 1px solid #e2e8f0;
                                border-radius: 8px;
                                padding: 15px;
                                z-index: 10000;
                                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                                min-width: 200px;
                            }

                            .progress-bar {
                                height: 4px;
                                background: #f1f5f9;
                                border-radius: 2px;
                                overflow: hidden;
                                margin-bottom: 8px;
                            }

                            .progress-fill {
                                height: 100%;
                                background: linear-gradient(90deg, #3b82f6, #1d4ed8);
                                border-radius: 2px;
                                transition: width 0.3s ease;
                                width: 0%;
                            }

                            .progress-text {
                                font-size: 12px;
                                color: #64748b;
                                text-align: center;
                            }
                        </style>
                    `);
                }
            }

            progressBar.find('.progress-fill').css('width', `${progress * 100}%`);
        }

        /**
         * Update animation progress
         */
        updateAnimationProgress(progress) {
            this.showAnimationProgress(progress);
        }

        /**
         * Hide animation progress
         */
        hideAnimationProgress() {
            $('#token-animation-progress').fadeOut(300, function() {
                $(this).remove();
            });
        }

        /**
         * Handle animation completion
         */
        handleAnimationComplete(data) {
            console.log('TokenAnimationIntegration: Animation sequence complete', data);

            // Update performance metrics
            this.updatePerformanceMetrics(data);

            // Trigger custom event
            document.dispatchEvent(new CustomEvent('tokenAnimationIntegrationComplete', {
                detail: data
            }));
        }

        /**
         * Handle palette animation completion
         */
        onPaletteAnimationComplete(paletteId) {
            console.log('TokenAnimationIntegration: Palette animation complete:', paletteId);

            // Update component coverage if available
            if (this.previewSystem && this.previewSystem.updateComponentCoverage) {
                this.previewSystem.updateComponentCoverage(paletteId);
            }
        }

        /**
         * Setup animation controls
         */
        setupAnimationControls() {
            // Add animation control panel for debugging
            if (window.location.search.includes('debug=animation')) {
                this.createAnimationControlPanel();
            }
        }

        /**
         * Create animation control panel
         */
        createAnimationControlPanel() {
            const controlPanel = $(`
                <div id="animation-control-panel" style="
                    position: fixed;
                    bottom: 20px;
                    left: 20px;
                    background: rgba(0, 0, 0, 0.8);
                    color: white;
                    padding: 15px;
                    border-radius: 8px;
                    z-index: 10001;
                    font-family: monospace;
                    font-size: 12px;
                ">
                    <h4>Animation Controls</h4>
                    <label>
                        <input type="checkbox" id="enable-animations" ${this.settings.enableAnimations ? 'checked' : ''}>
                        Enable Animations
                    </label><br>
                    <label>
                        Duration: <input type="range" id="animation-duration" min="100" max="1000" value="${this.settings.defaultDuration}">
                        <span id="duration-value">${this.settings.defaultDuration}ms</span>
                    </label><br>
                    <label>
                        Easing:
                        <select id="animation-easing">
                            <option value="linear">Linear</option>
                            <option value="easeIn">Ease In</option>
                            <option value="easeOut">Ease Out</option>
                            <option value="easeInOut" selected>Ease In Out</option>
                            <option value="bounce">Bounce</option>
                            <option value="elastic">Elastic</option>
                        </select>
                    </label><br>
                    <button id="test-animation">Test Animation</button>
                </div>
            `);

            $('body').append(controlPanel);

            // Bind control events
            this.bindControlPanelEvents();
        }

        /**
         * Bind control panel events
         */
        bindControlPanelEvents() {
            $('#enable-animations').on('change', (e) => {
                this.settings.enableAnimations = e.target.checked;
            });

            $('#animation-duration').on('input', (e) => {
                this.settings.defaultDuration = parseInt(e.target.value);
                $('#duration-value').text(`${e.target.value}ms`);
            });

            $('#animation-easing').on('change', (e) => {
                this.settings.defaultEasing = e.target.value;
            });

            $('#test-animation').on('click', () => {
                this.testAnimation();
            });
        }

        /**
         * Test animation
         */
        testAnimation() {
            const testTokens = [
                { name: '--color-primary', value: '#' + Math.floor(Math.random()*16777215).toString(16) },
                { name: '--color-secondary', value: '#' + Math.floor(Math.random()*16777215).toString(16) }
            ];

            this.animationSystem.animateTokens(testTokens, {
                duration: this.settings.defaultDuration,
                easing: this.settings.defaultEasing,
                stagger: true,
                staggerDelay: 100
            });
        }

        /**
         * Add customizer animation controls
         */
        addCustomizerAnimationControls() {
            // This would add controls to the WordPress Customizer
            // Implementation depends on specific customizer setup
            console.log('TokenAnimationIntegration: Adding customizer animation controls');
        }

        /**
         * Start animation performance monitoring
         */
        startAnimationPerformanceMonitoring() {
            setInterval(() => {
                if (this.animationSystem) {
                    const metrics = this.animationSystem.getPerformanceMetrics();

                    // Adjust performance mode based on metrics
                    if (metrics.currentFPS < 30) {
                        this.adjustPerformanceMode('performance');
                    } else if (metrics.currentFPS > 55) {
                        this.adjustPerformanceMode('quality');
                    }
                }
            }, 5000);
        }

        /**
         * Adjust performance mode
         */
        adjustPerformanceMode(mode) {
            if (this.settings.performanceMode === mode) return;

            this.settings.performanceMode = mode;

            switch (mode) {
                case 'performance':
                    this.settings.defaultDuration = 150;
                    this.settings.staggerDelay = 25;
                    break;
                case 'balanced':
                    this.settings.defaultDuration = 300;
                    this.settings.staggerDelay = 50;
                    break;
                case 'quality':
                    this.settings.defaultDuration = 500;
                    this.settings.staggerDelay = 75;
                    break;
            }

            console.log(`TokenAnimationIntegration: Adjusted performance mode to ${mode}`);
        }

        /**
         * Update performance metrics
         */
        updatePerformanceMetrics(data) {
            // Track integration-specific metrics
            if (!this.performanceMetrics) {
                this.performanceMetrics = {
                    totalIntegrations: 0,
                    successfulAnimations: 0,
                    failedAnimations: 0,
                    averageAnimationTime: 0
                };
            }

            this.performanceMetrics.totalIntegrations++;

            if (data.performanceMetrics) {
                this.performanceMetrics.successfulAnimations++;

                const avgTime = this.performanceMetrics.averageAnimationTime;
                const newTime = data.performanceMetrics.averageAnimationTime;
                this.performanceMetrics.averageAnimationTime =
                    (avgTime * (this.performanceMetrics.totalIntegrations - 1) + newTime) / this.performanceMetrics.totalIntegrations;
            } else {
                this.performanceMetrics.failedAnimations++;
            }
        }

        /**
         * Utility methods
         */

        getPaletteData(paletteId) {
            if (window.semanticColorSystem) {
                return {
                    paletteId,
                    palette: window.semanticColorSystem.getPalette(paletteId)
                };
            }
            return null;
        }

        getCurrentTokenValue(tokenName) {
            return getComputedStyle(document.documentElement).getPropertyValue(tokenName).trim();
        }

        detectTokenType(tokenName) {
            return this.animationSystem.detectTokenType(tokenName);
        }

        showNotification(type, message) {
            // Simple notification system
            const notification = $(`
                <div class="token-animation-notification ${type}" style="
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: ${type === 'error' ? '#ef4444' : '#10b981'};
                    color: white;
                    padding: 12px 16px;
                    border-radius: 6px;
                    z-index: 10002;
                    font-size: 14px;
                ">${message}</div>
            `);

            $('body').append(notification);

            setTimeout(() => {
                notification.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }

        /**
         * Bind integration events
         */
        bindEvents() {
            // Listen for system events
            document.addEventListener('paletteChanged', (event) => {
                if (this.settings.enableAnimations) {
                    // Event will be handled by integrated preview system
                    console.log('TokenAnimationIntegration: Palette change event intercepted');
                }
            });
        }

        /**
         * Get integration status
         */
        getIntegrationStatus() {
            return {
                isIntegrated: this.isIntegrated,
                animationSystemAvailable: !!this.animationSystem,
                previewSystemAvailable: !!this.previewSystem,
                settings: this.settings,
                performanceMetrics: this.performanceMetrics
            };
        }

        /**
         * Destroy integration
         */
        destroy() {
            // Remove control panel
            $('#animation-control-panel').remove();
            $('#token-animation-progress').remove();
            $('#token-animation-progress-styles').remove();

            // Reset preview system methods
            if (this.previewSystem && this.previewSystem.originalHandlePaletteChange) {
                this.previewSystem.handleEnhancedPaletteChange = this.previewSystem.originalHandlePaletteChange;
            }

            this.isIntegrated = false;
        }
    }

    // Export to global scope
    window.TokenAnimationIntegration = TokenAnimationIntegration;

    // Auto-initialize when dependencies are ready
    $(document).ready(function() {
        // Wait for dependencies
        const initIntegration = () => {
            if (window.tokenAnimationSystem &&
                (window.semanticTokenCustomizerPreviewEnhanced || window.SemanticTokenCustomizerPreviewEnhanced)) {

                window.tokenAnimationIntegration = new TokenAnimationIntegration();
                console.log('TokenAnimationIntegration: Initialized and ready');
            } else {
                // Retry after a short delay
                setTimeout(initIntegration, 500);
            }
        };

        initIntegration();
    });

})(jQuery);
