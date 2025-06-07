/**
 * Preview Messenger - PVC-005 T5.2 Implementation
 * PostMessage API for cross-frame communication and preview window state management
 *
 * Handles communication between:
 * - WordPress Customizer and Preview Window
 * - Admin Interface and Live Preview
 * - Parent Frame and Child Frame
 */

class PreviewMessenger {
    constructor(options = {}) {
        this.options = {
            targetOrigin: window.location.origin,
            messagePrefix: 'visual-customizer',
            timeout: 5000, // 5 second timeout for responses
            enableDebug: options.debug || false,
            ...options
        };

        this.messageQueue = new Map();
        this.messageId = 0;
        this.listeners = new Map();
        this.isConnected = false;
        this.targetWindow = null;

        this.init();
    }

    /**
     * Initialize the messenger system
     */
    init() {
        this.log('📡 Preview Messenger: Initializing...');

        // Setup message listener
        this.setupMessageListener();

        // Detect context (parent/child frame)
        this.detectContext();

        // Setup connection
        this.establishConnection();

        this.log('✅ Preview Messenger: Ready');
    }

    /**
     * Setup the main message listener
     */
    setupMessageListener() {
        window.addEventListener('message', (event) => {
            this.handleIncomingMessage(event);
        });
    }

    /**
     * Detect if we're in parent or child frame context
     */
    detectContext() {
        this.isChildFrame = window.parent !== window;
        this.isParentFrame = !this.isChildFrame;

        if (this.isChildFrame) {
            this.targetWindow = window.parent;
            this.log('🖼️ Context: Child Frame (Preview Window)');
        } else {
            this.log('🖥️ Context: Parent Frame (Admin Interface)');
        }
    }

    /**
     * Establish connection with target window
     */
    establishConnection() {
        if (this.isChildFrame) {
            // Child frame - send ready message to parent
            this.sendHandshake();
        } else {
            // Parent frame - wait for child ready message
            this.waitForChildReady();
        }
    }

    /**
     * Send handshake message from child to parent
     */
    sendHandshake() {
        const handshakeMessage = {
            type: 'handshake',
            source: 'preview-window',
            capabilities: this.getCapabilities(),
            timestamp: performance.now()
        };

        this.sendToParent(handshakeMessage);
        this.log('🤝 Handshake sent to parent');
    }

    /**
     * Wait for child ready message (parent frame)
     */
    waitForChildReady() {
        this.on('handshake', (data, source) => {
            this.isConnected = true;
            this.targetWindow = source;

            this.log('🤝 Handshake received from child', data);

            // Send acknowledgment
            this.sendToChild({
                type: 'handshake-ack',
                source: 'admin-interface',
                capabilities: this.getCapabilities()
            });

            // Dispatch connection event
            this.dispatchEvent('messenger:connected', {
                targetCapabilities: data.capabilities
            });
        });
    }

    /**
     * Get system capabilities
     */
    getCapabilities() {
        return {
            livePreview: typeof LivePreviewSystem !== 'undefined',
            colorPalettes: typeof ColorPaletteInterface !== 'undefined',
            wordPressCustomizer: typeof wp !== 'undefined' && wp.customize,
            semanticColors: typeof SemanticColorSystem !== 'undefined'
        };
    }

    /**
     * Send message to parent window
     */
    sendToParent(data) {
        if (!this.isChildFrame) {
            this.log('⚠️ Cannot send to parent - not in child frame');
            return;
        }

        const message = this.wrapMessage(data);
        window.parent.postMessage(message, this.options.targetOrigin);

        this.log('📤 Message sent to parent:', data.type);
    }

    /**
     * Send message to child window
     */
    sendToChild(data, targetWindow = null) {
        const target = targetWindow || this.targetWindow;

        if (!target) {
            this.log('⚠️ No target window available for child message');
            return;
        }

        const message = this.wrapMessage(data);
        target.postMessage(message, this.options.targetOrigin);

        this.log('📤 Message sent to child:', data.type);
    }

    /**
     * Send message with response handling
     */
    async sendWithResponse(data, timeout = null) {
        return new Promise((resolve, reject) => {
            const messageId = this.generateMessageId();
            const timeoutMs = timeout || this.options.timeout;

            // Add response handler
            this.messageQueue.set(messageId, { resolve, reject });

            // Add message ID to data
            const messageData = {
                ...data,
                messageId: messageId,
                expectsResponse: true
            };

            // Send message
            if (this.isChildFrame) {
                this.sendToParent(messageData);
            } else {
                this.sendToChild(messageData);
            }

            // Setup timeout
            setTimeout(() => {
                if (this.messageQueue.has(messageId)) {
                    this.messageQueue.delete(messageId);
                    reject(new Error(`Message timeout: ${data.type}`));
                }
            }, timeoutMs);
        });
    }

    /**
     * Handle incoming messages
     */
    handleIncomingMessage(event) {
        // Verify origin
        if (!this.isValidOrigin(event.origin)) {
            this.log('⚠️ Invalid origin:', event.origin);
            return;
        }

        // Parse message
        const message = this.unwrapMessage(event.data);
        if (!message) {
            return;
        }

        this.log('📥 Message received:', message.type, message);

        // Handle response messages
        if (message.isResponse && message.responseToId) {
            this.handleResponse(message);
            return;
        }

        // Handle regular messages
        this.handleMessage(message, event.source);
    }

    /**
     * Handle response messages
     */
    handleResponse(message) {
        const { responseToId, data, error } = message;

        if (this.messageQueue.has(responseToId)) {
            const { resolve, reject } = this.messageQueue.get(responseToId);
            this.messageQueue.delete(responseToId);

            if (error) {
                reject(new Error(error));
            } else {
                resolve(data);
            }
        }
    }

    /**
     * Handle regular messages
     */
    handleMessage(message, source) {
        const { type, data, messageId, expectsResponse } = message;

        // Emit to listeners
        this.emit(type, data, source);

        // Send response if expected
        if (expectsResponse && messageId) {
            // Response will be sent by the listener
            // if they call respondTo()
        }
    }

    /**
     * Respond to a message
     */
    respondTo(messageId, data = null, error = null) {
        const response = {
            type: 'response',
            isResponse: true,
            responseToId: messageId,
            data: data,
            error: error
        };

        if (this.isChildFrame) {
            this.sendToParent(response);
        } else {
            this.sendToChild(response);
        }
    }

    /**
     * Wrap message with metadata
     */
    wrapMessage(data) {
        return {
            source: this.options.messagePrefix,
            timestamp: performance.now(),
            ...data
        };
    }

    /**
     * Unwrap and validate message
     */
    unwrapMessage(rawData) {
        if (typeof rawData !== 'object' || !rawData.source) {
            return null;
        }

        if (rawData.source !== this.options.messagePrefix) {
            return null;
        }

        return rawData;
    }

    /**
     * Validate message origin
     */
    isValidOrigin(origin) {
        const validOrigins = [
            window.location.origin,
            'http://localhost',
            'https://localhost'
        ];

        return validOrigins.some(valid => origin.startsWith(valid));
    }

    /**
     * Generate unique message ID
     */
    generateMessageId() {
        return `msg_${++this.messageId}_${Date.now()}`;
    }

    /**
     * Event listener management
     */
    on(eventType, callback) {
        if (!this.listeners.has(eventType)) {
            this.listeners.set(eventType, []);
        }
        this.listeners.get(eventType).push(callback);
    }

    off(eventType, callback) {
        if (this.listeners.has(eventType)) {
            const callbacks = this.listeners.get(eventType);
            const index = callbacks.indexOf(callback);
            if (index > -1) {
                callbacks.splice(index, 1);
            }
        }
    }

    emit(eventType, data, source = null) {
        if (this.listeners.has(eventType)) {
            this.listeners.get(eventType).forEach(callback => {
                try {
                    callback(data, source);
                } catch (error) {
                    this.log('❌ Listener error:', error);
                }
            });
        }
    }

    /**
     * Common message methods for easy API
     */

    // Palette-related messages
    async applyPalette(paletteData) {
        return this.sendWithResponse({
            type: 'apply-palette',
            palette: paletteData
        });
    }

    async previewPalette(paletteData) {
        return this.sendWithResponse({
            type: 'preview-palette',
            palette: paletteData
        });
    }

    async resetPreview() {
        return this.sendWithResponse({
            type: 'reset-preview'
        });
    }

    // Mode control messages
    async enablePreviewMode() {
        return this.sendWithResponse({
            type: 'enable-preview-mode'
        });
    }

    async disablePreviewMode() {
        return this.sendWithResponse({
            type: 'disable-preview-mode'
        });
    }

    // Status messages
    async getPreviewStatus() {
        return this.sendWithResponse({
            type: 'get-preview-status'
        });
    }

    /**
     * Setup common message handlers for preview window
     */
    setupPreviewHandlers(livePreviewSystem) {
        if (!livePreviewSystem) {
            this.log('⚠️ No LivePreviewSystem provided');
            return;
        }

        // Apply palette handler
        this.on('apply-palette', async (data, source) => {
            try {
                await livePreviewSystem.applyPalette(data.palette);
                // Response sent automatically
            } catch (error) {
                this.log('❌ Apply palette error:', error);
            }
        });

        // Preview palette handler
        this.on('preview-palette', async (data, source) => {
            try {
                livePreviewSystem.enablePreviewMode();
                await livePreviewSystem.applyPalette(data.palette);
            } catch (error) {
                this.log('❌ Preview palette error:', error);
            }
        });

        // Reset preview handler
        this.on('reset-preview', (data, source) => {
            try {
                livePreviewSystem.resetPreview();
            } catch (error) {
                this.log('❌ Reset preview error:', error);
            }
        });

        // Preview mode handlers
        this.on('enable-preview-mode', (data, source) => {
            livePreviewSystem.enablePreviewMode();
        });

        this.on('disable-preview-mode', (data, source) => {
            livePreviewSystem.disablePreviewMode();
        });

        // Status handler
        this.on('get-preview-status', (data, source) => {
            return livePreviewSystem.getStatus();
        });

        this.log('✅ Preview handlers setup complete');
    }

    /**
     * Utility methods
     */
    dispatchEvent(eventName, detail) {
        document.dispatchEvent(new CustomEvent(eventName, { detail }));
    }

    log(...args) {
        if (this.options.enableDebug) {
            console.log('[PreviewMessenger]', ...args);
        }
    }

    /**
     * Get connection status
     */
    getConnectionStatus() {
        return {
            isConnected: this.isConnected,
            isChildFrame: this.isChildFrame,
            isParentFrame: this.isParentFrame,
            hasTargetWindow: !!this.targetWindow,
            queuedMessages: this.messageQueue.size
        };
    }

    /**
     * Cleanup method
     */
    destroy() {
        this.listeners.clear();
        this.messageQueue.clear();
        this.isConnected = false;
        this.targetWindow = null;

        this.log('🧹 Preview Messenger: Destroyed');
    }
}

// Export for both module and global usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PreviewMessenger;
} else {
    window.PreviewMessenger = PreviewMessenger;
}
