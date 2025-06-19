/**
 * Customizer Preview JavaScript
 *
 * Real-time preview functionality for Sprint 11 homepage sections
 *
 * @package MedSpaTheme
 * @since 1.0.0
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 * @task T11.6 WordPress Integration & Customizer Controls
 */

(function($) {
    'use strict';

    // Wait for DOM to be ready
    $(document).ready(function() {

        // =====================================================================
        // SERVICES OVERVIEW SECTION PREVIEW UPDATES
        // =====================================================================

        // Services Overview Title
        wp.customize('services_overview_title', function(value) {
            value.bind(function(newval) {
                $('.services-overview .section-title').text(newval);
            });
        });

        // Services Overview Subtitle
        wp.customize('services_overview_subtitle', function(value) {
            value.bind(function(newval) {
                $('.services-overview .section-subtitle').text(newval);
            });
        });

        // Services Overview Description
        wp.customize('services_overview_description', function(value) {
            value.bind(function(newval) {
                $('.services-overview .section-description').text(newval);
            });
        });

        // Services Overview Section Visibility
        wp.customize('show_services_overview_section', function(value) {
            value.bind(function(newval) {
                if (newval) {
                    $('.services-overview').show();
                } else {
                    $('.services-overview').hide();
                }
            });
        });

        // Individual Service Section Visibility
        const serviceSections = [
            'injectable_artistry',
            'facial_renaissance',
            'laser_precision',
            'body_sculpting',
            'wellness_sanctuary'
        ];

        serviceSections.forEach(function(sectionKey) {
            wp.customize('show_service_' + sectionKey, function(value) {
                value.bind(function(newval) {
                    const sectionClass = '.' + sectionKey.replace('_', '-');
                    if (newval) {
                        $(sectionClass).show().addClass('animate-in');
                    } else {
                        $(sectionClass).hide().removeClass('animate-in');
                    }
                });
            });
        });

        // =====================================================================
        // TRUST INDICATORS SECTION PREVIEW UPDATES
        // =====================================================================

        // Trust Indicators Title
        wp.customize('trust_indicators_title', function(value) {
            value.bind(function(newval) {
                $('.trust-indicators-section .section-title').text(newval);
            });
        });

        // Trust Indicators Subtitle
        wp.customize('trust_indicators_subtitle', function(value) {
            value.bind(function(newval) {
                $('.trust-indicators-section .section-subtitle').text(newval);
            });
        });

        // Trust Indicators Section Visibility
        wp.customize('show_trust_indicators_section', function(value) {
            value.bind(function(newval) {
                if (newval) {
                    $('.trust-indicators-section').show();
                } else {
                    $('.trust-indicators-section').hide();
                }
            });
        });

        // Individual Trust Indicator Visibility
        const trustIndicators = [
            'board_certified',
            'award_winning',
            'happy_patients',
            'hipaa_compliant'
        ];

        trustIndicators.forEach(function(indicatorKey) {
            wp.customize('show_trust_' + indicatorKey, function(value) {
                value.bind(function(newval) {
                    const indicatorClass = '.' + indicatorKey.replace('_', '-');
                    if (newval) {
                        $(indicatorClass).show().addClass('animate-in');
                    } else {
                        $(indicatorClass).hide().removeClass('animate-in');
                    }
                });
            });
        });

        // =====================================================================
        // CUSTOMIZABLE TRUST INDICATOR CONTENT UPDATES
        // =====================================================================

        // Board Certified Content Updates
        wp.customize('trust_board_certified_title', function(value) {
            value.bind(function(newval) {
                $('.trust-indicator.board-certified .trust-title').text(newval);
            });
        });

        wp.customize('trust_board_certified_description', function(value) {
            value.bind(function(newval) {
                $('.trust-indicator.board-certified .trust-description').text(newval);
            });
        });

        // Award Winning Content Updates
        wp.customize('trust_award_winning_title', function(value) {
            value.bind(function(newval) {
                $('.trust-indicator.award-winning .trust-title').text(newval);
            });
        });

        wp.customize('trust_award_winning_description', function(value) {
            value.bind(function(newval) {
                $('.trust-indicator.award-winning .trust-description').text(newval);
            });
        });

        // Happy Patients Content Updates
        wp.customize('trust_happy_patients_title', function(value) {
            value.bind(function(newval) {
                $('.trust-indicator.happy-patients .trust-title').text(newval);
            });
        });

        wp.customize('trust_happy_patients_description', function(value) {
            value.bind(function(newval) {
                $('.trust-indicator.happy-patients .trust-description').text(newval);
            });
        });

        // HIPAA Compliant Content Updates
        wp.customize('trust_hipaa_compliant_title', function(value) {
            value.bind(function(newval) {
                $('.trust-indicator.hipaa-compliant .trust-title').text(newval);
            });
        });

        wp.customize('trust_hipaa_compliant_description', function(value) {
            value.bind(function(newval) {
                $('.trust-indicator.hipaa-compliant .trust-description').text(newval);
            });
        });

        // =====================================================================
        // ENHANCED PREVIEW ANIMATIONS
        // =====================================================================

        /**
         * Add smooth animations for section visibility changes
         */
        function addPreviewAnimations() {
            const style = document.createElement('style');
            style.textContent = `
                .animate-in {
                    animation: customizer-fade-in 0.3s ease-in-out;
                }

                @keyframes customizer-fade-in {
                    from {
                        opacity: 0;
                        transform: translateY(10px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .customizer-preview-highlight {
                    box-shadow: 0 0 0 2px #0073aa;
                    transition: box-shadow 0.2s ease-in-out;
                }
            `;
            document.head.appendChild(style);
        }

        // Initialize preview animations
        addPreviewAnimations();

        /**
         * Highlight elements when they're being customized
         */
        function highlightElement(selector, duration = 1000) {
            const $element = $(selector);
            $element.addClass('customizer-preview-highlight');

            setTimeout(function() {
                $element.removeClass('customizer-preview-highlight');
            }, duration);
        }

        // Add highlighting for focused elements
        wp.customize.bind('ready', function() {
            wp.customize.control.each(function(control) {
                control.container.on('focus', function() {
                    const settingId = control.id;

                    // Map setting IDs to selectors for highlighting
                    const highlightMap = {
                        'services_overview_title': '.services-overview .section-title',
                        'services_overview_subtitle': '.services-overview .section-subtitle',
                        'services_overview_description': '.services-overview .section-description',
                        'trust_indicators_title': '.trust-indicators-section .section-title',
                        'trust_indicators_subtitle': '.trust-indicators-section .section-subtitle',
                        'trust_board_certified_title': '.trust-indicator.board-certified .trust-title',
                        'trust_award_winning_title': '.trust-indicator.award-winning .trust-title',
                        'trust_happy_patients_title': '.trust-indicator.happy-patients .trust-title',
                        'trust_hipaa_compliant_title': '.trust-indicator.hipaa-compliant .trust-title'
                    };

                    if (highlightMap[settingId]) {
                        highlightElement(highlightMap[settingId]);
                    }
                });
            });
        });

        // =====================================================================
        // RESPONSIVE PREVIEW ENHANCEMENTS
        // =====================================================================

        /**
         * Ensure responsive behavior works in customizer preview
         */
        function enhanceResponsivePreview() {
            // Listen for device preview changes
            wp.customize.previewedDevice.bind(function(device) {
                // Add device-specific classes for enhanced preview
                $('body').removeClass('customizer-preview-desktop customizer-preview-tablet customizer-preview-mobile');
                $('body').addClass('customizer-preview-' + device);

                // Trigger resize event to ensure proper layout
                $(window).trigger('resize');
            });
        }

        // Initialize responsive preview enhancements
        enhanceResponsivePreview();

        // =====================================================================
        // PERFORMANCE OPTIMIZATIONS
        // =====================================================================

        /**
         * Debounce function to limit rapid updates
         */
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        /**
         * Optimized text updates with debouncing
         */
        function createDebouncedTextUpdate(setting, selector, delay = 300) {
            wp.customize(setting, function(value) {
                value.bind(debounce(function(newval) {
                    $(selector).text(newval);
                }, delay));
            });
        }

        // Apply debounced updates for better performance
        const textUpdates = [
            ['services_overview_title', '.services-overview .section-title'],
            ['services_overview_subtitle', '.services-overview .section-subtitle'],
            ['services_overview_description', '.services-overview .section-description'],
            ['trust_indicators_title', '.trust-indicators-section .section-title'],
            ['trust_indicators_subtitle', '.trust-indicators-section .section-subtitle']
        ];

        textUpdates.forEach(function([setting, selector]) {
            createDebouncedTextUpdate(setting, selector);
        });

        // =====================================================================
        // ACCESSIBILITY ENHANCEMENTS
        // =====================================================================

        /**
         * Ensure accessibility is maintained during preview updates
         */
        function maintainAccessibility() {
            // Update ARIA labels when content changes
            wp.customize('services_overview_title', function(value) {
                value.bind(function(newval) {
                    $('.services-overview').attr('aria-label', 'Services section: ' + newval);
                });
            });

            wp.customize('trust_indicators_title', function(value) {
                value.bind(function(newval) {
                    $('.trust-indicators-section').attr('aria-label', 'Trust indicators section: ' + newval);
                });
            });
        }

        // Initialize accessibility enhancements
        maintainAccessibility();

        // =====================================================================
        // CONSOLE LOGGING FOR DEBUGGING
        // =====================================================================

        if (window.console && window.console.log) {
            console.log('🎨 MedSpa Customizer Preview: Initialized successfully');
            console.log('📱 Responsive preview enhancements: Active');
            console.log('♿ Accessibility enhancements: Active');
            console.log('⚡ Performance optimizations: Active');
        }
    });

})(jQuery);
