/**
 * Footer Customizer Live Preview
 * Sprint: SPRINT-FOOTER-IMPL-001 | Task: T-FOOTER-003
 *
 * Real-time preview functionality for footer customizer controls
 * Enables live editing without page refresh for postMessage transport
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Wait for customizer to be ready
    wp.customize('footer_hero_title', function(value) {
        value.bind(function(newval) {
            $('#footer-hero-heading').text(newval);
        });
    });

    wp.customize('footer_hero_subtitle', function(value) {
        value.bind(function(newval) {
            $('.footer-hero-subtitle').text(newval);
        });
    });

    // Hero CTA Controls
    wp.customize('footer_primary_cta_text', function(value) {
        value.bind(function(newval) {
            $('.footer-cta-primary').text(newval);
        });
    });

    wp.customize('footer_secondary_cta_text', function(value) {
        value.bind(function(newval) {
            $('.footer-cta-secondary').text(newval);
        });
    });

    // Badge Controls
    wp.customize('footer_badge_1', function(value) {
        value.bind(function(newval) {
            $('.footer-hero-badges .badge-item:nth-child(1) .badge-text').text(newval);
        });
    });

    wp.customize('footer_badge_2', function(value) {
        value.bind(function(newval) {
            $('.footer-hero-badges .badge-item:nth-child(2) .badge-text').text(newval);
        });
    });

    wp.customize('footer_badge_3', function(value) {
        value.bind(function(newval) {
            $('.footer-hero-badges .badge-item:nth-child(3) .badge-text').text(newval);
        });
    });

    // Contact Information Controls
    wp.customize('footer_phone', function(value) {
        value.bind(function(newval) {
            $('.contact-info .contact-link .contact-value').first().text(newval);
            $('.contact-info .contact-link').first().attr('href', 'tel:' + newval.replace(/[^0-9+]/g, ''));
        });
    });

    wp.customize('footer_email', function(value) {
        value.bind(function(newval) {
            $('.contact-info .contact-item:nth-child(2) .contact-value').text(newval);
            $('.contact-info .contact-item:nth-child(2) .contact-link').attr('href', 'mailto:' + newval);
        });
    });

    wp.customize('footer_address', function(value) {
        value.bind(function(newval) {
            $('.contact-info .contact-address .contact-value').text(newval);
        });
    });

    // Services Controls
    wp.customize('footer_service_1', function(value) {
        value.bind(function(newval) {
            $('.services-list .service-item:nth-child(1) .service-link').contents().last()[0].textContent = newval;
        });
    });

    wp.customize('footer_service_2', function(value) {
        value.bind(function(newval) {
            $('.services-list .service-item:nth-child(2) .service-link').contents().last()[0].textContent = newval;
        });
    });

    wp.customize('footer_service_3', function(value) {
        value.bind(function(newval) {
            $('.services-list .service-item:nth-child(3) .service-link').contents().last()[0].textContent = newval;
        });
    });

    wp.customize('footer_service_4', function(value) {
        value.bind(function(newval) {
            $('.services-list .service-item:nth-child(4) .service-link').contents().last()[0].textContent = newval;
        });
    });

    // Hours Controls
    wp.customize('footer_hours_weekday', function(value) {
        value.bind(function(newval) {
            $('.hours-list .hours-item:nth-child(1) .hours-time').text(newval);
        });
    });

    wp.customize('footer_hours_saturday', function(value) {
        value.bind(function(newval) {
            $('.hours-list .hours-item:nth-child(2) .hours-time').text(newval);
        });
    });

    wp.customize('footer_hours_sunday', function(value) {
        value.bind(function(newval) {
            $('.hours-list .hours-item:nth-child(3) .hours-time').text(newval);
        });
    });

    wp.customize('footer_hours_note', function(value) {
        value.bind(function(newval) {
            $('.hours-note').text(newval);
        });
    });

    // Doctor/About Controls
    wp.customize('footer_doctor_name', function(value) {
        value.bind(function(newval) {
            $('.doctor-name').text(newval);
            $('.doctor-photo').attr('alt', newval);
        });
    });

    wp.customize('footer_doctor_credentials', function(value) {
        value.bind(function(newval) {
            $('.doctor-credentials').text(newval);
        });
    });

    wp.customize('footer_doctor_bio', function(value) {
        value.bind(function(newval) {
            $('.doctor-bio').text(newval);
        });
    });

    wp.customize('footer_doctor_image', function(value) {
        value.bind(function(newval) {
            $('.doctor-photo').attr('src', newval);
        });
    });

    // Map Section Controls
    wp.customize('footer_clinic_name', function(value) {
        value.bind(function(newval) {
            $('.clinic-name').text(newval);
        });
    });

    wp.customize('footer_clinic_tagline', function(value) {
        value.bind(function(newval) {
            $('.clinic-tagline').text(newval);
        });
    });

    // Newsletter Controls
    wp.customize('footer_newsletter_title', function(value) {
        value.bind(function(newval) {
            $('#newsletter-heading').text(newval);
        });
    });

    wp.customize('footer_newsletter_subtitle', function(value) {
        value.bind(function(newval) {
            $('.newsletter-subtitle').text(newval);
        });
    });

    wp.customize('footer_newsletter_privacy', function(value) {
        value.bind(function(newval) {
            $('.newsletter-privacy').text(newval);
        });
    });

    // Legal Section Controls
    wp.customize('footer_copyright_name', function(value) {
        value.bind(function(newval) {
            $('.copyright-text').html('© ' + new Date().getFullYear() + ' ' + newval + '. All Rights Reserved.');
        });
    });

    wp.customize('footer_licensing_text', function(value) {
        value.bind(function(newval) {
            $('.licensing-text').text(newval);
        });
    });

    // Color Controls with CSS Custom Properties
    wp.customize('footer_background_color', function(value) {
        value.bind(function(newval) {
            $('head').find('#footer-bg-color-custom').remove();
            $('head').append('<style id="footer-bg-color-custom">.site-footer.luxury-footer { --footer-background-primary: ' + newval + ' !important; }</style>');
        });
    });

    wp.customize('footer_text_color', function(value) {
        value.bind(function(newval) {
            $('head').find('#footer-text-color-custom').remove();
            $('head').append('<style id="footer-text-color-custom">.site-footer.luxury-footer { --footer-text-primary: ' + newval + ' !important; }</style>');
        });
    });

    wp.customize('footer_cta_primary_color', function(value) {
        value.bind(function(newval) {
            $('head').find('#footer-cta-color-custom').remove();
            $('head').append('<style id="footer-cta-color-custom">.site-footer.luxury-footer { --footer-cta-primary-background: ' + newval + ' !important; --footer-cta-primary-border: ' + newval + ' !important; }</style>');
        });
    });

    // Advanced Controls for Section Visibility
    function toggleSectionVisibility(sectionClass, isVisible) {
        if (isVisible) {
            $(sectionClass).show();
        } else {
            $(sectionClass).hide();
        }
    }

    wp.customize('footer_enable_hero', function(value) {
        value.bind(function(newval) {
            toggleSectionVisibility('.footer-hero-section', newval);
        });
    });

    wp.customize('footer_enable_map', function(value) {
        value.bind(function(newval) {
            toggleSectionVisibility('.footer-map-section', newval);
        });
    });

    wp.customize('footer_enable_newsletter', function(value) {
        value.bind(function(newval) {
            toggleSectionVisibility('.footer-newsletter-section', newval);
        });
    });

    // Grid Layout Controls
    wp.customize('footer_column_layout', function(value) {
        value.bind(function(newval) {
            $('head').find('#footer-grid-layout-custom').remove();
            $('head').append('<style id="footer-grid-layout-custom">.info-grid-wrapper { --footer-info-grid-columns: ' + newval + ' !important; }</style>');
        });
    });

    // Animation Enhancements for Real-time Preview
    function animateChange(element, property, newValue) {
        $(element).css('transition', 'all 0.3s ease');
        setTimeout(function() {
            $(element).css(property, newValue);
        }, 50);
    }

    // Enhanced Text Updates with Animation
    function updateTextWithAnimation(selector, newText) {
        var $element = $(selector);
        $element.fadeOut(150, function() {
            $element.text(newText).fadeIn(150);
        });
    }

    // Social Media Controls (with visibility logic)
    function updateSocialLinks() {
        var instagramUrl = wp.customize('footer_instagram_url')();
        var facebookUrl = wp.customize('footer_facebook_url')();
        var twitterUrl = wp.customize('footer_twitter_url')();
        var youtubeUrl = wp.customize('footer_youtube_url')();
        var linkedinUrl = wp.customize('footer_linkedin_url')();

        // Hide/show social links based on URLs
        $('.social-link.instagram').toggle(!!instagramUrl);
        $('.social-link.facebook').toggle(!!facebookUrl);
        $('.social-link.twitter').toggle(!!twitterUrl);
        $('.social-link.youtube').toggle(!!youtubeUrl);
        $('.social-link.linkedin').toggle(!!linkedinUrl);
    }

    // Initialize social links visibility
    wp.customize.bind('ready', updateSocialLinks);

    // Form Validation Preview (Newsletter)
    function validateEmailPreview() {
        var $emailInput = $('.newsletter-input');
        var $submitButton = $('.newsletter-submit');

        $emailInput.on('input', function() {
            var email = $(this).val();
            var isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

            if (isValid || email === '') {
                $(this).removeClass('error');
                $submitButton.prop('disabled', false);
            } else {
                $(this).addClass('error');
                $submitButton.prop('disabled', true);
            }
        });
    }

    // Initialize form validation
    wp.customize.bind('ready', validateEmailPreview);

    // Accessibility Enhancements for Customizer Preview
    function enhanceAccessibility() {
        // Ensure focus management during live preview
        $('.site-footer.luxury-footer').find('a, button, input').each(function() {
            if (!$(this).attr('tabindex')) {
                $(this).attr('tabindex', '0');
            }
        });

        // Update ARIA labels when content changes
        wp.customize.bind('change', function(setting) {
            if (setting.id.indexOf('footer_') === 0) {
                // Update screen reader announcements for changed content
                var $changedElement = $('[data-customize-setting-link="' + setting.id + '"]');
                if ($changedElement.length) {
                    $changedElement.attr('aria-live', 'polite');
                    setTimeout(function() {
                        $changedElement.removeAttr('aria-live');
                    }, 1000);
                }
            }
        });
    }

    // Initialize accessibility enhancements
    wp.customize.bind('ready', enhanceAccessibility);

    // Performance Optimization for Live Preview
    var previewUpdateQueue = [];
    var isProcessingQueue = false;

    function queuePreviewUpdate(callback) {
        previewUpdateQueue.push(callback);
        processPreviewQueue();
    }

    function processPreviewQueue() {
        if (isProcessingQueue || previewUpdateQueue.length === 0) {
            return;
        }

        isProcessingQueue = true;
        var callback = previewUpdateQueue.shift();

        requestAnimationFrame(function() {
            callback();
            isProcessingQueue = false;
            processPreviewQueue();
        });
    }

    // Debounced Updates for Better Performance
    var updateTimers = {};

    function debounceUpdate(settingId, callback, delay) {
        delay = delay || 150;

        if (updateTimers[settingId]) {
            clearTimeout(updateTimers[settingId]);
        }

        updateTimers[settingId] = setTimeout(callback, delay);
    }

    // Enhanced Color Picker Preview
    function updateColorWithTransition(cssProperty, newColor, duration) {
        duration = duration || 300;

        var $style = $('<style>');
        $style.text('.site-footer.luxury-footer { ' + cssProperty + ': ' + newColor + ' !important; transition: ' + cssProperty.replace('--', '') + ' ' + duration + 'ms ease; }');

        $('head').find('#temp-color-transition').remove();
        $style.attr('id', 'temp-color-transition').appendTo('head');

        setTimeout(function() {
            $style.remove();
        }, duration + 100);
    }

    // Initialize Enhanced Customizer Features
    wp.customize.bind('ready', function() {
        // Add custom CSS for smooth transitions
        $('<style>').text(`
            .site-footer.luxury-footer * {
                transition: color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease;
            }
            .newsletter-input.error {
                border-color: #e74c3c !important;
                box-shadow: 0 0 5px rgba(231, 76, 60, 0.3) !important;
            }
        `).appendTo('head');

        // Initialize all components
        console.log('Footer Customizer Preview: Initialized successfully');
    });

    // Error Handling for Preview Updates
    window.footerCustomizerPreview = {
        handleError: function(error, settingId) {
            console.warn('Footer Customizer Preview Error for ' + settingId + ':', error);
            // Graceful degradation - continue functioning even if one control fails
        },

        updateSetting: function(settingId, value, callback) {
            try {
                callback(value);
            } catch (error) {
                this.handleError(error, settingId);
            }
        }
    };

})(jQuery);
