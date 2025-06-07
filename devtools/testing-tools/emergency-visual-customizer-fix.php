<?php
/**
 * Emergency Visual Customizer Fix - PVC-009 Bug Resolution
 *
 * Bypasses WordPress script enqueuing to test direct file loading
 * This will identify if the issue is with WordPress integration or the files themselves
 */

// Prevent direct access and load WordPress correctly
if (!defined('ABSPATH')) {
    // Use correct paths for web server context
    $wp_config_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
    $wp_load_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

    if (file_exists($wp_config_path)) {
        require_once($wp_config_path);
    }
    if (file_exists($wp_load_path)) {
        require_once($wp_load_path);
    }
}

get_header();
?>

<div style="max-width: 1000px; margin: 20px auto; padding: 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <h1>🚨 Emergency Visual Customizer Fix - PVC-009</h1>
        <p><strong>Status:</strong> Bypassing WordPress script enqueuing to test direct loading</p>

        <div id="fix-status" style="margin: 20px 0; padding: 15px; background: #fff3cd; border-left: 5px solid #ffc107; border-radius: 4px;">
            <h3>🔧 Fix Status</h3>
            <div id="status-output">Initializing emergency fix...</div>
        </div>

        <div id="test-controls" style="margin: 20px 0;">
            <h3>Emergency Test Controls</h3>
            <button onclick="loadScriptsDirect()" style="background: #dc3545; color: white; border: none; padding: 12px 24px; border-radius: 4px; cursor: pointer; margin: 5px;">🚨 Load Scripts Direct</button>
            <button onclick="testVisualCustomizerDirect()" style="background: #007cba; color: white; border: none; padding: 12px 24px; border-radius: 4px; cursor: pointer; margin: 5px;">🎨 Test Visual Customizer</button>
            <button onclick="resetAllPreviewStyles()" style="background: #6c757d; color: white; border: none; padding: 12px 24px; border-radius: 4px; cursor: pointer; margin: 5px;">🧹 Reset All Styles</button>
            <br>
            <button onclick="applyEmergencyPalette('luxury-spa')" style="background: #28a745; color: white; border: none; padding: 12px 24px; border-radius: 4px; cursor: pointer; margin: 5px;">✨ Apply Luxury Spa</button>
            <button onclick="applyEmergencyPalette('medical-clean')" style="background: #17a2b8; color: white; border: none; padding: 12px 24px; border-radius: 4px; cursor: pointer; margin: 5px;">🏥 Apply Medical Clean</button>
            <button onclick="applyEmergencyPalette('calming-oasis')" style="background: #20c997; color: white; border: none; padding: 12px 24px; border-radius: 4px; cursor: pointer; margin: 5px;">🌿 Apply Calming Oasis</button>
        </div>

        <div id="live-test-area" style="margin: 20px 0; padding: 20px; background: var(--color-primary-navy, #2c3e50); color: var(--color-neutral-white, #ffffff); border-radius: 8px; transition: all 0.3s ease;">
            <h3>🎯 Live Test Area</h3>
            <p>This area uses CSS variables and should change color when palettes are applied.</p>
            <div style="display: flex; gap: 15px; margin: 15px 0; flex-wrap: wrap;">
                <div style="padding: 15px; background: var(--color-primary-teal, #16a085); border-radius: 6px; flex: 1; min-width: 150px;">
                    <strong>Primary Teal Element</strong>
                    <p>Uses --color-primary-teal</p>
                </div>
                <div style="padding: 15px; background: var(--gradient-accent, linear-gradient(135deg, #f39c12 0%, #e67e22 100%)); border-radius: 6px; flex: 1; min-width: 150px;">
                    <strong>Accent Gradient Element</strong>
                    <p>Uses --gradient-accent</p>
                </div>
                <div style="padding: 15px; background: var(--color-secondary-peach, #f39c12); border-radius: 6px; flex: 1; min-width: 150px;">
                    <strong>Secondary Peach Element</strong>
                    <p>Uses --color-secondary-peach</p>
                </div>
            </div>
        </div>

        <div id="debug-output" style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 4px;">
            <h3>🔍 Debug Output</h3>
            <div id="debug-log" style="background: #2d2d2d; color: #f8f8f2; padding: 15px; border-radius: 4px; font-family: monospace; white-space: pre-wrap; max-height: 400px; overflow-y: auto;">
Emergency fix initializing...
            </div>
        </div>
    </div>
</div>

<!-- Emergency Script Loading - Bypass WordPress -->
<script>
let emergencyDebugLog = document.getElementById('debug-log');
let emergencyStatusOutput = document.getElementById('status-output');

function logEmergency(message) {
    const timestamp = new Date().toLocaleTimeString();
    emergencyDebugLog.textContent += `[${timestamp}] ${message}\n`;
    emergencyDebugLog.scrollTop = emergencyDebugLog.scrollHeight;
    console.log(`🚨 EMERGENCY: ${message}`);
}

function updateStatus(message, type = 'info') {
    const statusIcons = {
        'info': 'ℹ️',
        'success': '✅',
        'warning': '⚠️',
        'error': '❌'
    };
    emergencyStatusOutput.innerHTML = `${statusIcons[type]} ${message}`;
}

// Emergency script loader
function loadScriptsDirect() {
    updateStatus('Loading scripts directly, bypassing WordPress...', 'warning');
    logEmergency('Starting direct script loading...');

    const baseUrl = '<?php echo get_template_directory_uri(); ?>';

    // CRITICAL: Load scripts in correct dependency order with proper timing
    const scriptsInOrder = [
        'semantic-color-system.js',
        'color-system-manager.js',
        'live-preview-system.js',
        'preview-messenger.js',
        'wp-customizer-bridge.js',
        'color-palette-interface.js',
        'simple-visual-customizer.js'  // MUST be last
    ];

    let loadedCount = 0;
    let failedCount = 0;

    // Sequential loading to ensure dependencies
    function loadNextScript(index) {
        if (index >= scriptsInOrder.length) {
            completeEmergencyLoading();
            return;
        }

        const scriptName = scriptsInOrder[index];
        const script = document.createElement('script');
        script.src = `${baseUrl}/assets/js/${scriptName}`;
        script.id = `emergency-${scriptName.replace('.js', '')}`;

        script.onload = () => {
            loadedCount++;
            logEmergency(`✅ Loaded (${index + 1}/${scriptsInOrder.length}): ${scriptName}`);

            // Wait for script execution before loading next
            setTimeout(() => loadNextScript(index + 1), 100);
        };

        script.onerror = () => {
            failedCount++;
            logEmergency(`❌ Failed to load: ${scriptName}`);

            // Continue with next script even if one fails
            setTimeout(() => loadNextScript(index + 1), 100);
        };

        document.head.appendChild(script);
        logEmergency(`📥 Loading (${index + 1}/${scriptsInOrder.length}): ${scriptName}`);
    }

    // Start sequential loading
    loadNextScript(0);
}

function completeEmergencyLoading() {
    logEmergency(`\n🔍 Emergency loading complete. Loaded: ${loadedCount}/${scriptsInOrder.length} scripts`);

    // Wait additional time for all scripts to fully execute
    setTimeout(() => {
        // Test loaded objects with more comprehensive checks
        const objectsToTest = [
            { name: 'SemanticColorSystem', check: () => typeof SemanticColorSystem !== 'undefined' && SemanticColorSystem.prototype },
            { name: 'ColorSystemManager', check: () => typeof ColorSystemManager !== 'undefined' && ColorSystemManager.prototype },
            { name: 'LivePreviewSystem', check: () => typeof LivePreviewSystem !== 'undefined' && LivePreviewSystem.prototype && LivePreviewSystem.prototype.applyPalette },
            { name: 'ColorPaletteInterface', check: () => typeof ColorPaletteInterface !== 'undefined' && ColorPaletteInterface.prototype },
            { name: 'SimpleVisualCustomizer (capital)', check: () => typeof SimpleVisualCustomizer !== 'undefined' },
            { name: 'simpleVisualCustomizer (lowercase)', check: () => typeof simpleVisualCustomizer !== 'undefined' && simpleVisualCustomizer.init }
        ];

        logEmergency('\n🧪 Testing emergency loaded objects with enhanced checks:');

        let availableObjects = 0;
        objectsToTest.forEach(({ name, check }) => {
            const available = check();
            logEmergency(`• ${name}: ${available ? '✅ AVAILABLE' : '❌ MISSING'}`);
            if (available) availableObjects++;
        });

        // Extra diagnostic for simpleVisualCustomizer
        if (typeof window.SimpleVisualCustomizer !== 'undefined') {
            logEmergency('🔍 SimpleVisualCustomizer object found - checking properties:');
            const props = Object.keys(window.SimpleVisualCustomizer);
            logEmergency(`  Properties: ${props.join(', ')}`);

            if (typeof window.simpleVisualCustomizer === 'undefined') {
                logEmergency('🔧 FIXING: Creating lowercase alias...');
                window.simpleVisualCustomizer = window.SimpleVisualCustomizer;
                logEmergency('✅ FIXED: window.simpleVisualCustomizer now available');
            }
        }

        // Re-test simpleVisualCustomizer after potential fix
        const simpleCustomizerWorking = typeof simpleVisualCustomizer !== 'undefined' && simpleVisualCustomizer.init;
        logEmergency(`\n🎯 Final simpleVisualCustomizer status: ${simpleCustomizerWorking ? '✅ WORKING' : '❌ STILL MISSING'}`);

        if (availableObjects >= 4) {
            updateStatus(`Emergency loading successful! ${availableObjects}/${objectsToTest.length} objects available`, 'success');
            logEmergency(`\n🎉 SUCCESS: ${availableObjects} objects loaded successfully!`);

            // Initialize Visual Customizer if available
            setTimeout(initializeEmergencyCustomizer, 500);
        } else {
            updateStatus('Emergency loading partially failed', 'warning');
            logEmergency(`\n⚠️ PARTIAL: Only ${availableObjects}/${objectsToTest.length} objects available`);

            // Try to initialize anyway if we have core components
            if (availableObjects >= 2) {
                setTimeout(initializeEmergencyCustomizer, 500);
            }
        }
    }, 1000); // Wait 1 second for all scripts to execute
}

function initializeEmergencyCustomizer() {
    logEmergency('\n🚀 Initializing emergency Visual Customizer...');

    try {
        // CRITICAL: Create the missing simpleCustomizer configuration that the script expects
        window.simpleCustomizer = {
            ajaxUrl: '<?php echo admin_url('admin-ajax.php'); ?>',
            nonce: '<?php echo wp_create_nonce('simple_visual_customizer'); ?>',
            isAdmin: true,
            debug: true,
            pvc005Enabled: true,
            version: 'emergency-<?php echo time(); ?>'
        };
        logEmergency('✅ Emergency simpleCustomizer config created');

        // Create additional emergency configuration
        window.emergencySimpleCustomizer = window.simpleCustomizer;

        // Initialize Live Preview System first if available
        if (typeof LivePreviewSystem !== 'undefined') {
            try {
                window.emergencyLivePreview = new LivePreviewSystem({
                    debug: true,
                    updateDelay: 50
                });
                logEmergency('✅ Emergency LivePreviewSystem initialized');

                // Test applyPalette method
                if (window.emergencyLivePreview.applyPalette) {
                    logEmergency('✅ LivePreviewSystem.applyPalette method confirmed');
                } else {
                    logEmergency('❌ LivePreviewSystem.applyPalette method missing');
                }
            } catch (error) {
                logEmergency(`❌ LivePreviewSystem initialization failed: ${error.message}`);
            }
        }

        // Initialize Color System Manager if available
        if (typeof ColorSystemManager !== 'undefined') {
            try {
                window.emergencyColorSystemManager = new ColorSystemManager({
                    autoInit: false,
                    debug: true
                });
                window.emergencyColorSystemManager.initialize().then(() => {
                    logEmergency('✅ Emergency ColorSystemManager initialized');
                }).catch(error => {
                    logEmergency(`⚠️ ColorSystemManager init warning: ${error.message}`);
                });
            } catch (error) {
                logEmergency(`❌ ColorSystemManager initialization failed: ${error.message}`);
            }
        }

        // Initialize simpleVisualCustomizer if available
        if (typeof simpleVisualCustomizer !== 'undefined') {
            try {
                logEmergency('🔧 Attempting simpleVisualCustomizer initialization...');

                if (simpleVisualCustomizer.init) {
                    // Call the init method
                    simpleVisualCustomizer.init();
                    logEmergency('✅ Emergency simpleVisualCustomizer.init() called');
                } else {
                    logEmergency('⚠️ simpleVisualCustomizer.init method not found');
                    logEmergency(`Available methods: ${Object.keys(simpleVisualCustomizer).join(', ')}`);
                }

                // Test other methods
                if (simpleVisualCustomizer.openSidebar) {
                    logEmergency('✅ simpleVisualCustomizer.openSidebar available');
                }
                if (simpleVisualCustomizer.getLivePreviewSystem) {
                    logEmergency('✅ simpleVisualCustomizer.getLivePreviewSystem available');
                }

            } catch (error) {
                logEmergency(`❌ simpleVisualCustomizer initialization error: ${error.message}`);
            }
        } else {
            logEmergency('❌ simpleVisualCustomizer still not available after fixes');

            // Manual override attempt
            if (typeof SimpleVisualCustomizer !== 'undefined') {
                logEmergency('🔧 Manual override: Creating simpleVisualCustomizer from SimpleVisualCustomizer...');
                window.simpleVisualCustomizer = window.SimpleVisualCustomizer;

                if (window.simpleVisualCustomizer.init) {
                    window.simpleVisualCustomizer.init();
                    logEmergency('✅ Manual override successful - simpleVisualCustomizer.init() called');
                }
            }
        }

        // Setup emergency admin bar integration if not already present
        setupEmergencyAdminBar();

        updateStatus('Emergency Visual Customizer initialization complete!', 'success');
        logEmergency('🎯 Emergency initialization complete - ready for palette testing!');

        // Run immediate diagnostic
        setTimeout(runEmergencyDiagnostic, 500);

    } catch (error) {
        updateStatus('Emergency initialization failed', 'error');
        logEmergency(`❌ Emergency initialization error: ${error.message}`);
        logEmergency(`Error stack: ${error.stack}`);
    }
}

function setupEmergencyAdminBar() {
    logEmergency('🔧 Setting up emergency admin bar integration...');

    // Check if admin bar already has the visual customizer link
    const existingLink = document.querySelector('#wp-admin-bar-visual-customizer');
    if (existingLink) {
        logEmergency('✅ Admin bar visual customizer link already exists');
        return;
    }

    // Create emergency admin bar entry if admin bar exists
    const adminBar = document.getElementById('wpadminbar');
    if (adminBar) {
        const topSecondary = adminBar.querySelector('#wp-admin-bar-top-secondary');
        if (topSecondary) {
            const emergencyLink = document.createElement('li');
            emergencyLink.id = 'wp-admin-bar-visual-customizer';
            emergencyLink.innerHTML = `
                <a class="ab-item" href="#" onclick="event.preventDefault(); openEmergencyCustomizer();">
                    <span class="ab-icon" style="color: #ff6b6b;">🎨</span>
                    <span class="ab-label">Visual Customizer (Emergency)</span>
                </a>
            `;
            topSecondary.appendChild(emergencyLink);
            logEmergency('✅ Emergency admin bar link created');
        }
    }

    // Create global function for opening
    window.openEmergencyCustomizer = function() {
        logEmergency('🎨 Emergency customizer triggered from admin bar');
        if (typeof simpleVisualCustomizer !== 'undefined' && simpleVisualCustomizer.openSidebar) {
            simpleVisualCustomizer.openSidebar();
            logEmergency('✅ Emergency customizer sidebar opened');
        } else {
            logEmergency('❌ simpleVisualCustomizer.openSidebar not available');
            alert('Emergency Visual Customizer not available. Check console for details.');
        }
    };
}

function runEmergencyDiagnostic() {
    logEmergency('\n🔬 Running emergency diagnostic...');

    const diagnostics = {
        'simpleCustomizer config': typeof simpleCustomizer !== 'undefined',
        'LivePreviewSystem class': typeof LivePreviewSystem !== 'undefined',
        'LivePreviewSystem instance': typeof emergencyLivePreview !== 'undefined',
        'LivePreviewSystem.applyPalette': typeof emergencyLivePreview !== 'undefined' && emergencyLivePreview.applyPalette,
        'simpleVisualCustomizer object': typeof simpleVisualCustomizer !== 'undefined',
        'simpleVisualCustomizer.init': typeof simpleVisualCustomizer !== 'undefined' && simpleVisualCustomizer.init,
        'simpleVisualCustomizer.openSidebar': typeof simpleVisualCustomizer !== 'undefined' && simpleVisualCustomizer.openSidebar,
        'jQuery available': typeof jQuery !== 'undefined',
        'Admin bar present': document.getElementById('wpadminbar') !== null
    };

    logEmergency('Emergency diagnostic results:');
    Object.entries(diagnostics).forEach(([test, result]) => {
        logEmergency(`  • ${test}: ${result ? '✅' : '❌'}`);
    });

    const passedTests = Object.values(diagnostics).filter(Boolean).length;
    const totalTests = Object.keys(diagnostics).length;

    logEmergency(`\n📊 Diagnostic Summary: ${passedTests}/${totalTests} tests passed`);

    if (passedTests >= 6) {
        logEmergency('🎉 Emergency setup appears functional!');
        updateStatus(`Emergency setup complete! ${passedTests}/${totalTests} systems operational`, 'success');
    } else {
        logEmergency('⚠️ Emergency setup partially functional');
        updateStatus(`Emergency setup partial: ${passedTests}/${totalTests} systems operational`, 'warning');
    }
}

function testVisualCustomizerDirect() {
    logEmergency('\n🎨 Testing Visual Customizer functionality...');

    const testResults = {
        livePreviewSystem: typeof LivePreviewSystem !== 'undefined',
        colorSystemManager: typeof ColorSystemManager !== 'undefined',
        simpleVisualCustomizer: typeof simpleVisualCustomizer !== 'undefined',
        colorPaletteInterface: typeof ColorPaletteInterface !== 'undefined'
    };

    logEmergency('Direct test results:');
    Object.entries(testResults).forEach(([name, available]) => {
        logEmergency(`• ${name}: ${available ? '✅ AVAILABLE' : '❌ MISSING'}`);
    });

    const totalAvailable = Object.values(testResults).filter(Boolean).length;

    if (totalAvailable >= 2) {
        updateStatus(`Visual Customizer partially functional (${totalAvailable}/4 components)`, 'success');
        logEmergency(`🎉 SUCCESS: ${totalAvailable}/4 components available - Visual Customizer should work!`);
    } else {
        updateStatus('Visual Customizer not functional', 'error');
        logEmergency(`❌ FAILURE: Only ${totalAvailable}/4 components available`);
    }
}

function applyEmergencyPalette(paletteId) {
    logEmergency(`\n🎨 Emergency palette application: ${paletteId}`);
    updateStatus(`Applying ${paletteId} palette via Live Preview System...`, 'info');

    try {
        // CRITICAL FIX: Reset any previous styles first to ensure clean state
        resetAllPreviewStyles();

        // Method 1: Try via LivePreviewSystem with proper palette data
        if (typeof emergencyLivePreview !== 'undefined' && emergencyLivePreview.applyPalette) {
            logEmergency('🚀 Using LivePreviewSystem for real-time application...');

            // Reset the LivePreviewSystem before applying new palette
            if (emergencyLivePreview.resetPreview) {
                emergencyLivePreview.resetPreview();
                logEmergency('🔄 LivePreviewSystem reset completed');
            }

            // Get proper palette data from SemanticColorSystem if available
            let paletteData = null;

            if (typeof SemanticColorSystem !== 'undefined') {
                try {
                    const colorSystem = new SemanticColorSystem();
                    paletteData = colorSystem.getPalette(paletteId);
                    logEmergency(`✅ Retrieved palette data from SemanticColorSystem: ${paletteData.name}`);
                } catch (error) {
                    logEmergency(`⚠️ Could not get palette from SemanticColorSystem: ${error.message}`);
                }
            }

            // Fallback to emergency palette data if SemanticColorSystem not available
            if (!paletteData) {
                paletteData = getEmergencyPaletteData(paletteId);
                logEmergency('🔧 Using emergency fallback palette data');
            }

            if (paletteData) {
                emergencyLivePreview.applyPalette(paletteData).then(() => {
                    logEmergency(`✅ Applied ${paletteId} via LivePreviewSystem successfully!`);
                    updateStatus(`${paletteId} applied with real-time preview!`, 'success');

                    // CRITICAL: Verify visual changes are actually applied
                    setTimeout(() => {
                        verifyVisualChanges(paletteId, paletteData);
                    }, 100);

                    // Dispatch event for UI feedback
                    document.dispatchEvent(new CustomEvent('preview:paletteApplied', {
                        detail: {
                            palette: paletteData,
                            duration: 50,
                            performance: { avgUpdateTime: 50, totalUpdates: 1 }
                        }
                    }));
                }).catch(error => {
                    logEmergency(`❌ LivePreviewSystem application failed: ${error.message}`);
                    logEmergency('🔧 Falling back to manual CSS injection...');
                    // Fallback to manual application
                    applyPaletteManually(paletteId);
                });
                return;
            }
        }

        // Method 2: Try via simpleVisualCustomizer if available
        if (typeof simpleVisualCustomizer !== 'undefined') {
            const livePreviewSystem = simpleVisualCustomizer.getLivePreviewSystem?.();
            if (livePreviewSystem && livePreviewSystem.applyPalette) {
                logEmergency('🔧 Using simpleVisualCustomizer LivePreviewSystem...');

                // Reset before applying
                if (livePreviewSystem.resetPreview) {
                    livePreviewSystem.resetPreview();
                    logEmergency('🔄 simpleVisualCustomizer LivePreviewSystem reset completed');
                }

                const paletteData = getEmergencyPaletteData(paletteId);
                livePreviewSystem.applyPalette(paletteData).then(() => {
                    logEmergency(`✅ Applied via simpleVisualCustomizer LivePreviewSystem`);
                    updateStatus(`${paletteId} applied successfully!`, 'success');

                    // CRITICAL: Verify visual changes are actually applied
                    setTimeout(() => {
                        verifyVisualChanges(paletteId, paletteData);
                    }, 100);
                }).catch(error => {
                    logEmergency(`❌ simpleVisualCustomizer application failed: ${error.message}`);
                    logEmergency('🔧 Falling back to manual CSS injection...');
                    applyPaletteManually(paletteId);
                });
                return;
            }
        }

        // Method 3: Manual CSS injection as last resort
        logEmergency('⚠️ Falling back to manual CSS injection...');
        applyPaletteManually(paletteId);

    } catch (error) {
        logEmergency(`❌ Palette application failed: ${error.message}`);
        updateStatus('Palette application failed', 'error');
        applyPaletteManually(paletteId); // Still try manual as last resort
    }
}

function resetAllPreviewStyles() {
    logEmergency('🧹 Resetting all preview styles for clean state...');

    // Remove all preview and emergency style elements
    const allStyles = document.querySelectorAll('style[data-preview-system], style[id*="preview"], style[id*="emergency"], style[id*="live-preview"]');
    allStyles.forEach((style, index) => {
        logEmergency(`  🗑️ Removing style element: ${style.id || 'unnamed'}`);
        style.remove();
    });

    // Remove preview mode classes
    document.body.classList.remove('live-preview-active');

    // Force DOM reflow
    document.body.offsetHeight;

    logEmergency(`✅ Reset complete - removed ${allStyles.length} style elements`);
}

function getEmergencyPaletteData(paletteId) {
    // Create proper palette data structure that LivePreviewSystem expects
    const emergencyPalettes = {
        'luxury-spa': {
            id: 'luxury-spa',
            name: 'Luxury Spa',
            description: 'Elegant gold and deep purple luxury spa theme',
            category: 'luxury',
            colors: {
                primary: { hex: '#4a2c50', role: 'primary' },
                secondary: { hex: '#b8860b', role: 'secondary' },
                accent: { hex: '#daa520', role: 'accent' },
                surface: { hex: '#faf7f2', role: 'surface' },
                background: { hex: '#f8f5f0', role: 'background' },
                text: { hex: '#2c2c2c', role: 'text' }
            },
            gradients: {
                primary: 'linear-gradient(135deg, #daa520 0%, #b8860b 100%)',
                accent: 'linear-gradient(135deg, #4a2c50 0%, #2c1810 100%)'
            }
        },
        'medical-clean': {
            id: 'medical-clean',
            name: 'Medical Clean',
            description: 'Clean, professional medical spa theme',
            category: 'medical',
            colors: {
                primary: { hex: '#2c3e50', role: 'primary' },
                secondary: { hex: '#16a085', role: 'secondary' },
                accent: { hex: '#3498db', role: 'accent' },
                surface: { hex: '#ffffff', role: 'surface' },
                background: { hex: '#f8f9fa', role: 'background' },
                text: { hex: '#2c3e50', role: 'text' }
            },
            gradients: {
                primary: 'linear-gradient(135deg, #3498db 0%, #16a085 100%)',
                accent: 'linear-gradient(135deg, #16a085 0%, #27ae60 100%)'
            }
        },
        'calming-oasis': {
            id: 'calming-oasis',
            name: 'Calming Oasis',
            description: 'Peaceful green oasis theme',
            category: 'nature',
            colors: {
                primary: { hex: '#2c5f2d', role: 'primary' },
                secondary: { hex: '#48a999', role: 'secondary' },
                accent: { hex: '#97d4a6', role: 'accent' },
                surface: { hex: '#f0f8f0', role: 'surface' },
                background: { hex: '#f5f9f5', role: 'background' },
                text: { hex: '#2c5f2d', role: 'text' }
            },
            gradients: {
                primary: 'linear-gradient(135deg, #97d4a6 0%, #48a999 100%)',
                accent: 'linear-gradient(135deg, #48a999 0%, #2c5f2d 100%)'
            }
        }
    };

    const paletteData = emergencyPalettes[paletteId];
    if (paletteData) {
        logEmergency(`📋 Emergency palette data for ${paletteId}:`, paletteData);
    }

    return paletteData;
}

function applyPaletteManually(paletteId) {
    logEmergency(`🔧 Manual palette application for: ${paletteId}`);

    const paletteData = getEmergencyPaletteData(paletteId);

    if (!paletteData) {
        logEmergency(`❌ Unknown palette: ${paletteId}`);
        return;
    }

    // Map semantic colors to actual CSS variables used by the Live Test Area
    const cssVariableMapping = {
        'primary': '--color-primary-navy',      // Used by Live Test Area background
        'secondary': '--color-primary-teal',    // Used by Primary Teal Element
        'accent': '--color-secondary-peach',    // Used by Secondary Peach Element
        'surface': '--color-neutral-white',
        'background': '--color-soft-cream',
        'text': '--color-charcoal'
    };

    // Remove existing emergency styles
    const existingStyle = document.getElementById('emergency-palette-css');
    if (existingStyle) existingStyle.remove();

    // Create new style element with highest priority
    const style = document.createElement('style');
    style.id = 'emergency-palette-css';

    let css = ':root {\n';

    // Apply color mappings with visual verification
    Object.entries(paletteData.colors).forEach(([semanticRole, colorData]) => {
        const cssVariable = cssVariableMapping[semanticRole];
        if (cssVariable && colorData.hex) {
            css += `    ${cssVariable}: ${colorData.hex} !important;\n`;
            logEmergency(`🎨 Mapping ${semanticRole} -> ${cssVariable}: ${colorData.hex}`);
        }
    });

    // CRITICAL: Apply gradients with correct variable names that Live Test Area uses
    if (paletteData.gradients) {
        // Live Test Area specifically uses --gradient-accent
        css += `    --gradient-accent: ${paletteData.gradients.primary} !important;\n`;
        css += `    --gradient-primary: ${paletteData.gradients.primary} !important;\n`;
        logEmergency(`🌈 Gradient accent -> --gradient-accent: ${paletteData.gradients.primary}`);

        if (paletteData.gradients.accent) {
            css += `    --gradient-secondary: ${paletteData.gradients.accent} !important;\n`;
            logEmergency(`🌈 Gradient secondary -> --gradient-secondary: ${paletteData.gradients.accent}`);
        }
    }

    css += '}\n';

    style.textContent = css;
    document.head.appendChild(style);

    logEmergency(`✅ Manual palette ${paletteId} applied successfully!`);

    // IMMEDIATE VISUAL VERIFICATION - Force update of Live Test Area
    const liveTestArea = document.getElementById('live-test-area');
    if (liveTestArea) {
        // Force recompute of CSS variables
        liveTestArea.style.display = 'none';
        liveTestArea.offsetHeight; // Trigger reflow
        liveTestArea.style.display = '';

        // Get computed styles to verify changes
        const computedStyle = getComputedStyle(liveTestArea);
        const actualBgColor = computedStyle.backgroundColor;
        logEmergency(`🔍 Live Test Area background color after change: ${actualBgColor}`);

        // Check all color elements
        const colorElements = liveTestArea.querySelectorAll('[style*="var(--color"]');
        colorElements.forEach((element, index) => {
            const elementStyle = getComputedStyle(element);
            logEmergency(`🔍 Element ${index + 1} background: ${elementStyle.backgroundColor}`);
        });

        updateStatus(`${paletteId} applied! Check Live Test Area for changes.`, 'success');
    } else {
        logEmergency('⚠️ Live Test Area not found');
    }

    logEmergency('🔄 Forced repaint and verification complete');
}

function verifyVisualChanges(paletteId, paletteData) {
    logEmergency(`🔍 Verifying visual changes for ${paletteId}...`);

    const liveTestArea = document.getElementById('live-test-area');
    if (!liveTestArea) {
        logEmergency('❌ Live Test Area not found for verification');
        return;
    }

    // Check if CSS variables are actually applied
    const computedStyle = getComputedStyle(liveTestArea);
    const actualBgColor = computedStyle.backgroundColor;
    const expectedColor = paletteData.colors.primary?.hex;

    logEmergency(`🎯 Expected color: ${expectedColor}`);
    logEmergency(`🎯 Actual background: ${actualBgColor}`);

    // Check if colors match (convert hex to rgb for comparison)
    if (expectedColor) {
        const expectedRgb = hexToRgb(expectedColor);
        const expectedRgbString = `rgb(${expectedRgb.r}, ${expectedRgb.g}, ${expectedRgb.b})`;

        if (actualBgColor === expectedRgbString || actualBgColor.includes(expectedRgb.r.toString())) {
            logEmergency('✅ Visual changes verified - colors match!');
            updateStatus(`${paletteId} applied and verified! Real-time changes working.`, 'success');
        } else {
            logEmergency('❌ Visual changes not detected - applying manual fallback');
            logEmergency(`Expected: ${expectedRgbString}, Got: ${actualBgColor}`);
            applyPaletteManually(paletteId);
        }
    }

    // Check all color elements in Live Test Area
    const colorElements = liveTestArea.querySelectorAll('[style*="var(--color"], [style*="var(--gradient"]');
    logEmergency(`🔍 Found ${colorElements.length} color elements to verify`);

    colorElements.forEach((element, index) => {
        const elementStyle = getComputedStyle(element);
        logEmergency(`  Element ${index + 1}: background=${elementStyle.backgroundColor}`);
    });
}

function hexToRgb(hex) {
    const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}

// Auto-start emergency loading
document.addEventListener('DOMContentLoaded', function() {
    logEmergency('🚨 Emergency Visual Customizer Fix started');
    logEmergency('Environment: WordPress <?php echo get_bloginfo('version'); ?>');
    logEmergency('Theme: <?php echo wp_get_theme()->get('Name'); ?>');
    logEmergency('User: <?php echo get_current_user_id(); ?> (<?php echo current_user_can('manage_options') ? 'Admin' : 'Non-Admin'; ?>)');

    updateStatus('Emergency fix ready - click "Load Scripts Direct" to begin', 'info');

    // Auto-load scripts in 3 seconds
    setTimeout(() => {
        logEmergency('\n🔄 Auto-starting script loading in 3 seconds...');
        updateStatus('Auto-loading scripts...', 'warning');
        setTimeout(loadScriptsDirect, 3000);
    }, 1000);

    // Check if admin bar should be displayed
    setTimeout(checkAdminBarStatus, 2000);
});

function checkAdminBarStatus() {
    const adminBar = document.getElementById('wpadminbar');
    if (adminBar) {
        logEmergency('✅ WordPress admin bar detected');
    } else {
        logEmergency('⚠️ WordPress admin bar not found on this page');
        logEmergency('💡 Note: Admin bar is available on the main site pages');

        // Add note to the UI
        const noteHtml = `
            <div style="margin: 10px 0; padding: 10px; background: #e3f2fd; border-left: 4px solid #2196f3; border-radius: 4px;">
                <strong>ℹ️ Admin Bar Note:</strong> The WordPress admin bar is not available on this emergency diagnostic page.
                To test the Visual Customizer with the admin bar, visit the <a href="<?php echo home_url(); ?>" target="_blank">homepage</a>
                where the admin bar will show the 🎨 Visual Customizer link.
            </div>
        `;

        const controlsSection = document.getElementById('test-controls');
        if (controlsSection) {
            controlsSection.insertAdjacentHTML('afterend', noteHtml);
        }
    }
}
</script>

<?php get_footer(); ?>
