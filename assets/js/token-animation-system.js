/**
 * T8.5 Token Animation System
 *
 * Sophisticated animation system for smooth transitions between semantic token changes
 * with performance optimization, customizable easing, and seamless live preview integration
 *
 * @package MedSpaTheme
 * @version 1.0.0 - T8.5 Implementation
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 * @sprint SPRINT-8-EXTENSIBLE-SEMANTIC-INTEGRATION
 */

(function(window) {
    'use strict';

    /**
     * Token Animation System Class
     */
    class TokenAnimationSystem {
        constructor() {
            this.animationQueue = [];
            this.activeAnimations = new Map();
            this.isProcessing = false;
            this.frameRequestId = null;

            // Performance settings
            this.targetFPS = 60;
            this.frameInterval = 1000 / this.targetFPS;
            this.lastFrameTime = 0;

            // Animation configuration
            this.config = {
                duration: {
                    fast: 150,
                    normal: 300,
                    slow: 500,
                    custom: 300
                },
                easing: {
                    linear: t => t,
                    easeInOut: t => t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t,
                    easeOut: t => 1 - Math.pow(1 - t, 3),
                    easeIn: t => t * t * t,
                    bounce: t => {
                        if (t < 1/2.75) return 7.5625 * t * t;
                        if (t < 2/2.75) return 7.5625 * (t -= 1.5/2.75) * t + 0.75;
                        if (t < 2.5/2.75) return 7.5625 * (t -= 2.25/2.75) * t + 0.9375;
                        return 7.5625 * (t -= 2.625/2.75) * t + 0.984375;
                    },
                    elastic: t => {
                        if (t === 0 || t === 1) return t;
                        const p = 0.3;
                        const s = p / 4;
                        return Math.pow(2, -10 * t) * Math.sin((t - s) * (2 * Math.PI) / p) + 1;
                    }
                },
                priority: {
                    low: 1,
                    normal: 2,
                    high: 3,
                    critical: 4
                }
            };

            // Performance metrics
            this.performanceMetrics = {
                animationsCompleted: 0,
                totalAnimationTime: 0,
                averageAnimationTime: 0,
                droppedFrames: 0,
                currentFPS: 60,
                memoryUsage: 0
            };

            // Token type handlers
            this.tokenHandlers = {
                color: this.animateColorToken.bind(this),
                typography: this.animateTypographyToken.bind(this),
                spacing: this.animateSpacingToken.bind(this),
                shadow: this.animateShadowToken.bind(this),
                border: this.animateBorderToken.bind(this),
                opacity: this.animateOpacityToken.bind(this)
            };

            this.init();
        }

        /**
         * Initialize the animation system
         */
        init() {
            console.log('TokenAnimationSystem: Initializing advanced animation system');

            this.setupEventListeners();
            this.initializeAnimationStyles();
            this.startPerformanceMonitoring();
            this.setupRAFLoop();

            console.log('TokenAnimationSystem: Animation system ready');
        }

        /**
         * Setup event listeners for token changes
         */
        setupEventListeners() {
            // Listen for palette changes
            document.addEventListener('paletteChanged', (event) => {
                this.handlePaletteChange(event.detail);
            });

            // Listen for individual token changes
            document.addEventListener('tokenChanged', (event) => {
                this.handleTokenChange(event.detail);
            });

            // Listen for bulk token updates
            document.addEventListener('tokensChanged', (event) => {
                this.handleBulkTokenChange(event.detail);
            });

            // Performance monitoring events
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    this.pauseAnimations();
                } else {
                    this.resumeAnimations();
                }
            });
        }

        /**
         * Initialize CSS animation styles
         */
        initializeAnimationStyles() {
            const animationCSS = `
                <style id="token-animation-system-styles">
                    /* Token Animation System Styles */
                    .token-animating {
                        will-change: background-color, color, border-color, box-shadow, transform, opacity;
                    }

                    .token-animation-container {
                        position: relative;
                        overflow: hidden;
                    }

                    /* Color animation classes */
                    .animate-color-fast { transition: background-color 150ms cubic-bezier(0.4, 0, 0.2, 1), color 150ms cubic-bezier(0.4, 0, 0.2, 1), border-color 150ms cubic-bezier(0.4, 0, 0.2, 1); }
                    .animate-color-normal { transition: background-color 300ms cubic-bezier(0.4, 0, 0.2, 1), color 300ms cubic-bezier(0.4, 0, 0.2, 1), border-color 300ms cubic-bezier(0.4, 0, 0.2, 1); }
                    .animate-color-slow { transition: background-color 500ms cubic-bezier(0.4, 0, 0.2, 1), color 500ms cubic-bezier(0.4, 0, 0.2, 1), border-color 500ms cubic-bezier(0.4, 0, 0.2, 1); }

                    /* Typography animation classes */
                    .animate-typography-fast { transition: font-size 150ms cubic-bezier(0.4, 0, 0.2, 1), font-weight 150ms cubic-bezier(0.4, 0, 0.2, 1), line-height 150ms cubic-bezier(0.4, 0, 0.2, 1); }
                    .animate-typography-normal { transition: font-size 300ms cubic-bezier(0.4, 0, 0.2, 1), font-weight 300ms cubic-bezier(0.4, 0, 0.2, 1), line-height 300ms cubic-bezier(0.4, 0, 0.2, 1); }
                    .animate-typography-slow { transition: font-size 500ms cubic-bezier(0.4, 0, 0.2, 1), font-weight 500ms cubic-bezier(0.4, 0, 0.2, 1), line-height 500ms cubic-bezier(0.4, 0, 0.2, 1); }

                    /* Spacing animation classes */
                    .animate-spacing-fast { transition: padding 150ms cubic-bezier(0.4, 0, 0.2, 1), margin 150ms cubic-bezier(0.4, 0, 0.2, 1), gap 150ms cubic-bezier(0.4, 0, 0.2, 1); }
                    .animate-spacing-normal { transition: padding 300ms cubic-bezier(0.4, 0, 0.2, 1), margin 300ms cubic-bezier(0.4, 0, 0.2, 1), gap 300ms cubic-bezier(0.4, 0, 0.2, 1); }
                    .animate-spacing-slow { transition: padding 500ms cubic-bezier(0.4, 0, 0.2, 1), margin 500ms cubic-bezier(0.4, 0, 0.2, 1), gap 500ms cubic-bezier(0.4, 0, 0.2, 1); }

                    /* Shadow animation classes */
                    .animate-shadow-fast { transition: box-shadow 150ms cubic-bezier(0.4, 0, 0.2, 1), text-shadow 150ms cubic-bezier(0.4, 0, 0.2, 1); }
                    .animate-shadow-normal { transition: box-shadow 300ms cubic-bezier(0.4, 0, 0.2, 1), text-shadow 300ms cubic-bezier(0.4, 0, 0.2, 1); }
                    .animate-shadow-slow { transition: box-shadow 500ms cubic-bezier(0.4, 0, 0.2, 1), text-shadow 500ms cubic-bezier(0.4, 0, 0.2, 1); }

                    /* Special animation effects */
                    .token-pulse {
                        animation: tokenPulse 0.6s ease-in-out;
                    }

                    .token-glow {
                        animation: tokenGlow 0.8s ease-in-out;
                    }

                    .token-shimmer {
                        position: relative;
                        overflow: hidden;
                    }

                    .token-shimmer::before {
                        content: '';
                        position: absolute;
                        top: 0;
                        left: -100%;
                        width: 100%;
                        height: 100%;
                        background: linear-gradient(
                            90deg,
                            transparent,
                            rgba(255, 255, 255, 0.3),
                            transparent
                        );
                        animation: shimmer 1.2s ease-in-out;
                        z-index: 1;
                    }

                    /* Keyframe animations */
                    @keyframes tokenPulse {
                        0%, 100% { transform: scale(1); opacity: 1; }
                        50% { transform: scale(1.02); opacity: 0.95; }
                    }

                    @keyframes tokenGlow {
                        0%, 100% { box-shadow: 0 0 0 rgba(59, 130, 246, 0); }
                        50% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.3); }
                    }

                    @keyframes shimmer {
                        0% { left: -100%; }
                        100% { left: 100%; }
                    }

                    /* Performance optimizations */
                    .token-animating * {
                        backface-visibility: hidden;
                        perspective: 1000px;
                    }

                    /* Reduced motion support */
                    @media (prefers-reduced-motion: reduce) {
                        .animate-color-fast, .animate-color-normal, .animate-color-slow,
                        .animate-typography-fast, .animate-typography-normal, .animate-typography-slow,
                        .animate-spacing-fast, .animate-spacing-normal, .animate-spacing-slow,
                        .animate-shadow-fast, .animate-shadow-normal, .animate-shadow-slow {
                            transition: none;
                        }

                        .token-pulse, .token-glow, .token-shimmer::before {
                            animation: none;
                        }
                    }
                </style>
            `;

            document.head.insertAdjacentHTML('beforeend', animationCSS);
        }

        /**
         * Setup RequestAnimationFrame loop for performance monitoring
         */
        setupRAFLoop() {
            const loop = (currentTime) => {
                // Calculate FPS
                if (this.lastFrameTime) {
                    const deltaTime = currentTime - this.lastFrameTime;
                    this.performanceMetrics.currentFPS = Math.round(1000 / deltaTime);

                    // Track dropped frames
                    if (deltaTime > this.frameInterval * 1.5) {
                        this.performanceMetrics.droppedFrames++;
                    }
                }

                this.lastFrameTime = currentTime;

                // Process animation queue
                if (this.animationQueue.length > 0 && !this.isProcessing) {
                    this.processAnimationQueue();
                }

                // Continue loop
                this.frameRequestId = requestAnimationFrame(loop);
            };

            this.frameRequestId = requestAnimationFrame(loop);
        }

        /**
         * Handle palette change with coordinated animations
         */
        handlePaletteChange(paletteData) {
            console.log('TokenAnimationSystem: Handling palette change', paletteData.paletteId);

            const palette = paletteData.palette;
            if (!palette || !palette.colors) {
                console.warn('TokenAnimationSystem: Invalid palette data');
                return;
            }

            // Create coordinated animation sequence
            const animations = [];

            // Color token animations
            Object.entries(palette.colors).forEach(([role, colorData]) => {
                const color = colorData.hex || colorData;
                animations.push({
                    type: 'color',
                    tokenName: `--color-${role}`,
                    fromValue: this.getCurrentTokenValue(`--color-${role}`),
                    toValue: color,
                    duration: this.config.duration.normal,
                    easing: 'easeInOut',
                    priority: this.config.priority.high,
                    delay: role === 'primary' ? 0 : 50 // Stagger animations
                });
            });

            // Queue all animations
            this.queueAnimations(animations, {
                coordinated: true,
                onComplete: () => {
                    this.triggerPaletteChangeComplete(paletteData.paletteId);
                }
            });
        }

        /**
         * Handle individual token change
         */
        handleTokenChange(tokenData) {
            const { tokenType, tokenName, fromValue, toValue, options = {} } = tokenData;

            const animation = {
                type: tokenType,
                tokenName,
                fromValue,
                toValue,
                duration: options.duration || this.config.duration.normal,
                easing: options.easing || 'easeInOut',
                priority: options.priority || this.config.priority.normal,
                delay: options.delay || 0
            };

            this.queueAnimation(animation, options);
        }

        /**
         * Handle bulk token changes
         */
        handleBulkTokenChange(tokensData) {
            const { tokens, options = {} } = tokensData;
            const animations = [];

            tokens.forEach((token, index) => {
                animations.push({
                    ...token,
                    duration: options.duration || this.config.duration.normal,
                    easing: options.easing || 'easeInOut',
                    priority: options.priority || this.config.priority.normal,
                    delay: options.stagger ? index * (options.staggerDelay || 50) : 0
                });
            });

            this.queueAnimations(animations, options);
        }

        /**
         * Queue single animation
         */
        queueAnimation(animation, options = {}) {
            const animationId = this.generateAnimationId();

            const queueItem = {
                id: animationId,
                ...animation,
                timestamp: performance.now(),
                options
            };

            // Insert based on priority
            this.insertByPriority(queueItem);

            return animationId;
        }

        /**
         * Queue multiple animations
         */
        queueAnimations(animations, options = {}) {
            const animationIds = [];

            animations.forEach(animation => {
                const id = this.queueAnimation(animation, options);
                animationIds.push(id);
            });

            return animationIds;
        }

        /**
         * Process animation queue
         */
        async processAnimationQueue() {
            if (this.isProcessing || this.animationQueue.length === 0) return;

            this.isProcessing = true;

            try {
                // Group animations by priority and coordination
                const animationGroups = this.groupAnimations();

                // Process each group
                for (const group of animationGroups) {
                    await this.processAnimationGroup(group);
                }

            } catch (error) {
                console.error('TokenAnimationSystem: Error processing animations:', error);
            } finally {
                this.isProcessing = false;
            }
        }

        /**
         * Process a group of animations
         */
        async processAnimationGroup(group) {
            const promises = group.map(animation => this.executeAnimation(animation));

            if (group.coordinated) {
                // Wait for all animations in group to complete
                await Promise.all(promises);
            } else {
                // Fire and forget individual animations
                promises.forEach(promise => promise.catch(console.error));
            }
        }

        /**
         * Execute individual animation
         */
        async executeAnimation(animation) {
            const startTime = performance.now();

            try {
                // Add to active animations
                this.activeAnimations.set(animation.id, animation);

                // Apply delay if specified
                if (animation.delay > 0) {
                    await this.delay(animation.delay);
                }

                // Execute animation based on type
                const handler = this.tokenHandlers[animation.type];
                if (handler) {
                    await handler(animation);
                } else {
                    console.warn(`TokenAnimationSystem: No handler for animation type: ${animation.type}`);
                }

                // Update performance metrics
                const duration = performance.now() - startTime;
                this.updatePerformanceMetrics(duration);

                // Trigger completion callback
                if (animation.options.onComplete) {
                    animation.options.onComplete(animation);
                }

            } catch (error) {
                console.error('TokenAnimationSystem: Animation execution error:', error);
            } finally {
                // Remove from active animations
                this.activeAnimations.delete(animation.id);
            }
        }

        /**
         * Animate color token
         */
        async animateColorToken(animation) {
            const { tokenName, fromValue, toValue, duration, easing } = animation;

            // Find elements that use this token
            const elements = this.findElementsUsingToken(tokenName);

            if (elements.length === 0) {
                // Update CSS custom property directly
                document.documentElement.style.setProperty(tokenName, toValue);
                return;
            }

            // Add animation classes
            elements.forEach(el => {
                el.classList.add('token-animating');
                const speed = duration <= 200 ? 'fast' : duration <= 400 ? 'normal' : 'slow';
                el.classList.add(`animate-color-${speed}`);
            });

            // Animate using CSS custom properties
            return new Promise(resolve => {
                // Set up transition end listener
                const handleTransitionEnd = () => {
                    elements.forEach(el => {
                        el.classList.remove('token-animating', 'animate-color-fast', 'animate-color-normal', 'animate-color-slow');
                        el.removeEventListener('transitionend', handleTransitionEnd);
                    });
                    resolve();
                };

                elements.forEach(el => {
                    el.addEventListener('transitionend', handleTransitionEnd, { once: true });
                });

                // Apply the new value
                document.documentElement.style.setProperty(tokenName, toValue);

                // Fallback timeout
                setTimeout(() => {
                    handleTransitionEnd();
                }, duration + 100);
            });
        }

        /**
         * Animate typography token
         */
        async animateTypographyToken(animation) {
            const { tokenName, fromValue, toValue, duration, easing } = animation;

            const elements = this.findElementsUsingToken(tokenName);

            // Add animation classes
            elements.forEach(el => {
                el.classList.add('token-animating');
                const speed = duration <= 200 ? 'fast' : duration <= 400 ? 'normal' : 'slow';
                el.classList.add(`animate-typography-${speed}`);
            });

            return new Promise(resolve => {
                const handleTransitionEnd = () => {
                    elements.forEach(el => {
                        el.classList.remove('token-animating', 'animate-typography-fast', 'animate-typography-normal', 'animate-typography-slow');
                        el.removeEventListener('transitionend', handleTransitionEnd);
                    });
                    resolve();
                };

                elements.forEach(el => {
                    el.addEventListener('transitionend', handleTransitionEnd, { once: true });
                });

                // Apply the new value
                document.documentElement.style.setProperty(tokenName, toValue);

                setTimeout(() => {
                    handleTransitionEnd();
                }, duration + 100);
            });
        }

        /**
         * Animate spacing token
         */
        async animateSpacingToken(animation) {
            const { tokenName, fromValue, toValue, duration, easing } = animation;

            const elements = this.findElementsUsingToken(tokenName);

            elements.forEach(el => {
                el.classList.add('token-animating');
                const speed = duration <= 200 ? 'fast' : duration <= 400 ? 'normal' : 'slow';
                el.classList.add(`animate-spacing-${speed}`);
            });

            return new Promise(resolve => {
                const handleTransitionEnd = () => {
                    elements.forEach(el => {
                        el.classList.remove('token-animating', 'animate-spacing-fast', 'animate-spacing-normal', 'animate-spacing-slow');
                        el.removeEventListener('transitionend', handleTransitionEnd);
                    });
                    resolve();
                };

                elements.forEach(el => {
                    el.addEventListener('transitionend', handleTransitionEnd, { once: true });
                });

                document.documentElement.style.setProperty(tokenName, toValue);

                setTimeout(() => {
                    handleTransitionEnd();
                }, duration + 100);
            });
        }

        /**
         * Animate shadow token
         */
        async animateShadowToken(animation) {
            const { tokenName, fromValue, toValue, duration, easing } = animation;

            const elements = this.findElementsUsingToken(tokenName);

            elements.forEach(el => {
                el.classList.add('token-animating');
                const speed = duration <= 200 ? 'fast' : duration <= 400 ? 'normal' : 'slow';
                el.classList.add(`animate-shadow-${speed}`);
            });

            return new Promise(resolve => {
                const handleTransitionEnd = () => {
                    elements.forEach(el => {
                        el.classList.remove('token-animating', 'animate-shadow-fast', 'animate-shadow-normal', 'animate-shadow-slow');
                        el.removeEventListener('transitionend', handleTransitionEnd);
                    });
                    resolve();
                };

                elements.forEach(el => {
                    el.addEventListener('transitionend', handleTransitionEnd, { once: true });
                });

                document.documentElement.style.setProperty(tokenName, toValue);

                setTimeout(() => {
                    handleTransitionEnd();
                }, duration + 100);
            });
        }

        /**
         * Animate border token
         */
        async animateBorderToken(animation) {
            return this.animateColorToken(animation); // Similar to color animation
        }

        /**
         * Animate opacity token
         */
        async animateOpacityToken(animation) {
            const { tokenName, fromValue, toValue, duration, easing } = animation;

            const elements = this.findElementsUsingToken(tokenName);

            return new Promise(resolve => {
                const startTime = performance.now();
                const from = parseFloat(fromValue) || 0;
                const to = parseFloat(toValue) || 1;
                const easingFunc = this.config.easing[easing] || this.config.easing.easeInOut;

                const animate = () => {
                    const elapsed = performance.now() - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const easedProgress = easingFunc(progress);
                    const currentValue = from + (to - from) * easedProgress;

                    document.documentElement.style.setProperty(tokenName, currentValue.toString());

                    if (progress < 1) {
                        requestAnimationFrame(animate);
                    } else {
                        resolve();
                    }
                };

                animate();
            });
        }

        /**
         * Find elements that use a specific CSS custom property
         */
        findElementsUsingToken(tokenName) {
            const elements = [];
            const allElements = document.querySelectorAll('*');

            for (const element of allElements) {
                const computedStyle = getComputedStyle(element);
                const styles = [
                    'background-color', 'color', 'border-color', 'box-shadow',
                    'font-size', 'font-weight', 'line-height',
                    'padding', 'margin', 'gap'
                ];

                for (const style of styles) {
                    const value = computedStyle.getPropertyValue(style);
                    if (value && value.includes(tokenName)) {
                        elements.push(element);
                        break;
                    }
                }
            }

            return elements;
        }

        /**
         * Get current value of a CSS custom property
         */
        getCurrentTokenValue(tokenName) {
            return getComputedStyle(document.documentElement).getPropertyValue(tokenName).trim();
        }

        /**
         * Group animations by priority and coordination requirements
         */
        groupAnimations() {
            const groups = [];
            const priorityGroups = {};

            // Group by priority
            this.animationQueue.forEach(animation => {
                const priority = animation.priority || this.config.priority.normal;
                if (!priorityGroups[priority]) {
                    priorityGroups[priority] = [];
                }
                priorityGroups[priority].push(animation);
            });

            // Sort by priority (highest first)
            const sortedPriorities = Object.keys(priorityGroups).sort((a, b) => b - a);

            sortedPriorities.forEach(priority => {
                groups.push(priorityGroups[priority]);
            });

            // Clear the queue
            this.animationQueue = [];

            return groups;
        }

        /**
         * Insert animation by priority
         */
        insertByPriority(animation) {
            const priority = animation.priority || this.config.priority.normal;

            let insertIndex = this.animationQueue.length;
            for (let i = 0; i < this.animationQueue.length; i++) {
                const queuePriority = this.animationQueue[i].priority || this.config.priority.normal;
                if (priority > queuePriority) {
                    insertIndex = i;
                    break;
                }
            }

            this.animationQueue.splice(insertIndex, 0, animation);
        }

        /**
         * Generate unique animation ID
         */
        generateAnimationId() {
            return `anim_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
        }

        /**
         * Utility delay function
         */
        delay(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        /**
         * Update performance metrics
         */
        updatePerformanceMetrics(duration) {
            this.performanceMetrics.animationsCompleted++;
            this.performanceMetrics.totalAnimationTime += duration;
            this.performanceMetrics.averageAnimationTime =
                this.performanceMetrics.totalAnimationTime / this.performanceMetrics.animationsCompleted;
        }

        /**
         * Start performance monitoring
         */
        startPerformanceMonitoring() {
            setInterval(() => {
                this.performanceMetrics.memoryUsage = this.getMemoryUsage();

                // Log performance if in debug mode
                if (window.location.search.includes('debug=animation')) {
                    console.log('Animation Performance:', this.performanceMetrics);
                }
            }, 5000);
        }

        /**
         * Get memory usage estimate
         */
        getMemoryUsage() {
            if (performance.memory) {
                return {
                    used: Math.round(performance.memory.usedJSHeapSize / 1048576),
                    total: Math.round(performance.memory.totalJSHeapSize / 1048576),
                    limit: Math.round(performance.memory.jsHeapSizeLimit / 1048576)
                };
            }
            return null;
        }

        /**
         * Pause all animations
         */
        pauseAnimations() {
            if (this.frameRequestId) {
                cancelAnimationFrame(this.frameRequestId);
                this.frameRequestId = null;
            }
        }

        /**
         * Resume animations
         */
        resumeAnimations() {
            if (!this.frameRequestId) {
                this.setupRAFLoop();
            }
        }

        /**
         * Trigger palette change complete event
         */
        triggerPaletteChangeComplete(paletteId) {
            document.dispatchEvent(new CustomEvent('paletteAnimationComplete', {
                detail: {
                    paletteId,
                    timestamp: Date.now(),
                    performanceMetrics: this.performanceMetrics
                }
            }));
        }

        /**
         * Public API methods
         */

        /**
         * Animate single token
         */
        animateToken(tokenName, toValue, options = {}) {
            const fromValue = this.getCurrentTokenValue(tokenName);
            const tokenType = this.detectTokenType(tokenName);

            return this.queueAnimation({
                type: tokenType,
                tokenName,
                fromValue,
                toValue,
                duration: options.duration || this.config.duration.normal,
                easing: options.easing || 'easeInOut',
                priority: options.priority || this.config.priority.normal,
                delay: options.delay || 0
            }, options);
        }

        /**
         * Animate multiple tokens
         */
        animateTokens(tokens, options = {}) {
            const animations = tokens.map(token => ({
                type: this.detectTokenType(token.name),
                tokenName: token.name,
                fromValue: this.getCurrentTokenValue(token.name),
                toValue: token.value,
                duration: token.duration || options.duration || this.config.duration.normal,
                easing: token.easing || options.easing || 'easeInOut',
                priority: token.priority || options.priority || this.config.priority.normal,
                delay: token.delay || 0
            }));

            return this.queueAnimations(animations, options);
        }

        /**
         * Detect token type from token name
         */
        detectTokenType(tokenName) {
            if (tokenName.includes('color') || tokenName.includes('bg') || tokenName.includes('border')) {
                return 'color';
            } else if (tokenName.includes('font') || tokenName.includes('text')) {
                return 'typography';
            } else if (tokenName.includes('padding') || tokenName.includes('margin') || tokenName.includes('gap')) {
                return 'spacing';
            } else if (tokenName.includes('shadow')) {
                return 'shadow';
            } else if (tokenName.includes('opacity')) {
                return 'opacity';
            }
            return 'color'; // Default fallback
        }

        /**
         * Get performance metrics
         */
        getPerformanceMetrics() {
            return {
                ...this.performanceMetrics,
                activeAnimations: this.activeAnimations.size,
                queuedAnimations: this.animationQueue.length
            };
        }

        /**
         * Clear all animations
         */
        clearAnimations() {
            this.animationQueue = [];
            this.activeAnimations.clear();
        }

        /**
         * Destroy animation system
         */
        destroy() {
            this.pauseAnimations();
            this.clearAnimations();

            // Remove event listeners
            document.removeEventListener('paletteChanged', this.handlePaletteChange);
            document.removeEventListener('tokenChanged', this.handleTokenChange);
            document.removeEventListener('tokensChanged', this.handleBulkTokenChange);

            // Remove styles
            const styles = document.getElementById('token-animation-system-styles');
            if (styles) {
                styles.remove();
            }
        }
    }

    // Export to global scope
    window.TokenAnimationSystem = TokenAnimationSystem;

    // Auto-initialize if in browser environment
    if (typeof window !== 'undefined') {
        window.tokenAnimationSystem = new TokenAnimationSystem();
        console.log('TokenAnimationSystem: Initialized and ready');
    }

})(window);
