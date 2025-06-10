/**
 * Footer Maps Integration
 * Sprint: SPRINT-FOOTER-IMPL-001 | Task: T-FOOTER-004
 *
 * Google Maps integration with Beverly Hills clinic location
 * Floating clinic info overlay and mobile-responsive behavior
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Footer Maps Main Class
     */
    class FooterMaps {

        constructor() {
            this.map = null;
            this.marker = null;
            this.infoWindow = null;
            this.isFullscreen = false;
            this.isMobile = window.innerWidth <= 768;
            this.isOverlayOpen = false;

            // Default clinic location (Beverly Hills)
            this.clinicLocation = {
                lat: 34.0736,
                lng: -118.4004
            };

            // Initialize the maps system
            this.init();
        }

        /**
         * Initialize the footer maps system
         */
        init() {
            this.bindEvents();
            this.initializeMap();
            this.setupAccessibility();
            this.handleMobileOptimizations();

            console.log('Footer Maps: Initialized successfully');
        }

        /**
         * Bind event listeners
         */
        bindEvents() {

            // Map control buttons
            $(document).on('click', '[data-action="toggle-overlay"]', (e) => {
                e.preventDefault();
                this.toggleClinicOverlay();
            });

            $(document).on('click', '[data-action="close-overlay"]', (e) => {
                e.preventDefault();
                this.closeClinicOverlay();
            });

            $(document).on('click', '[data-action="toggle-fullscreen"]', (e) => {
                e.preventDefault();
                this.toggleFullscreen();
            });

            // Mobile touch handling
            $(document).on('touchstart', '#footer-interactive-map', (e) => {
                this.handleMobileTouch(e);
            });

            // Window resize handling
            $(window).on('resize', this.debounce(() => {
                this.handleResize();
            }, 250));

            // Keyboard navigation
            $(document).on('keydown', '#clinic-info-overlay', (e) => {
                this.handleOverlayKeyboard(e);
            });

            // Analytics tracking
            $(document).on('click', '[data-analytics]', (e) => {
                this.trackAnalytics(e);
            });
        }

        /**
         * Initialize Google Maps
         */
        initializeMap() {
            const mapContainer = $('#google-maps-default');

            if (mapContainer.length === 0) {
                console.log('Footer Maps: Custom embed detected, skipping Google Maps initialization');
                return;
            }

            // Get map configuration from data attributes
            const config = this.getMapConfig(mapContainer);

            // Check if Google Maps API is available
            if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
                console.log('Footer Maps: Google Maps API not available, loading...');
                this.loadGoogleMapsAPI(config);
                return;
            }

            this.createMap(config);
        }

        /**
         * Get map configuration from DOM
         */
        getMapConfig(mapContainer) {
            return {
                lat: parseFloat(mapContainer.data('lat')) || this.clinicLocation.lat,
                lng: parseFloat(mapContainer.data('lng')) || this.clinicLocation.lng,
                zoom: parseInt(mapContainer.data('zoom')) || 15,
                markerTitle: mapContainer.data('marker-title') || 'Beverly Hills Medical Spa',
                markerAddress: mapContainer.data('marker-address') || '123 Beverly Drive, Beverly Hills, CA 90210'
            };
        }

        /**
         * Load Google Maps API dynamically
         */
        loadGoogleMapsAPI(config) {
            // Show loading state
            this.showLoadingState();

            // Create script element for Google Maps API
            const script = document.createElement('script');
            script.src = 'https://maps.googleapis.com/maps/api/js?key=' + this.getGoogleMapsApiKey() + '&callback=initFooterMap';
            script.async = true;
            script.defer = true;

            // Global callback for Google Maps
            window.initFooterMap = () => {
                this.createMap(config);
            };

            // Error handling
            script.onerror = () => {
                console.error('Footer Maps: Failed to load Google Maps API');
                this.showFallbackMap();
            };

            document.head.appendChild(script);
        }

        /**
         * Get Google Maps API key (you'll need to add this)
         */
        getGoogleMapsApiKey() {
            // TODO: Add your Google Maps API key here
            // For development, you can return an empty string to test without API key
            return '';
        }

        /**
         * Create the Google Map
         */
        createMap(config) {
            const mapContainer = document.getElementById('google-maps-default');

            if (!mapContainer) {
                console.error('Footer Maps: Map container not found');
                return;
            }

            // Hide loading state
            this.hideLoadingState();

            // Map options
            const mapOptions = {
                center: { lat: config.lat, lng: config.lng },
                zoom: config.zoom,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: false,
                zoomControl: true,
                mapTypeControl: false,
                scaleControl: true,
                streetViewControl: true,
                rotateControl: false,
                fullscreenControl: false, // We handle fullscreen ourselves
                gestureHandling: this.isMobile ? 'cooperative' : 'auto',
                styles: this.getMapStyles()
            };

            // Create map
            this.map = new google.maps.Map(mapContainer, mapOptions);

            // Create marker
            this.createMarker(config);

            // Create info window
            this.createInfoWindow(config);

            // Setup map event listeners
            this.setupMapEvents();

            console.log('Footer Maps: Google Map created successfully');
        }

        /**
         * Create map marker
         */
        createMarker(config) {
            this.marker = new google.maps.Marker({
                position: { lat: config.lat, lng: config.lng },
                map: this.map,
                title: config.markerTitle,
                animation: google.maps.Animation.DROP,
                icon: {
                    url: this.getCustomMarkerIcon(),
                    scaledSize: new google.maps.Size(40, 40),
                    anchor: new google.maps.Point(20, 40)
                }
            });

            // Marker click event
            this.marker.addListener('click', () => {
                this.openClinicOverlay();
            });
        }

        /**
         * Get custom marker icon
         */
        getCustomMarkerIcon() {
            // Return path to custom marker icon or use default
            return 'data:image/svg+xml;base64,' + btoa(`
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20" cy="20" r="18" fill="#D4AF37" stroke="#1B365D" stroke-width="2"/>
                    <path d="M20 10C15.5817 10 12 13.5817 12 18C12 24 20 30 20 30S28 24 28 18C28 13.5817 24.4183 10 20 10Z" fill="#1B365D"/>
                    <circle cx="20" cy="18" r="3" fill="#FFFFFF"/>
                </svg>
            `);
        }

        /**
         * Create info window
         */
        createInfoWindow(config) {
            const infoContent = `
                <div class="map-info-window">
                    <h4>${config.markerTitle}</h4>
                    <p>${config.markerAddress}</p>
                    <button onclick="footerMapsInstance.openClinicOverlay()" class="info-window-btn">
                        View Details
                    </button>
                </div>
            `;

            this.infoWindow = new google.maps.InfoWindow({
                content: infoContent
            });
        }

        /**
         * Setup map event listeners
         */
        setupMapEvents() {
            // Map click events
            this.map.addListener('click', () => {
                if (this.infoWindow) {
                    this.infoWindow.close();
                }
            });

            // Map bounds change
            this.map.addListener('bounds_changed', () => {
                this.handleMapBoundsChange();
            });
        }

        /**
         * Get custom map styles
         */
        getMapStyles() {
            return [
                {
                    "featureType": "all",
                    "elementType": "geometry.fill",
                    "stylers": [
                        { "weight": "2.00" }
                    ]
                },
                {
                    "featureType": "all",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        { "color": "#9c9c9c" }
                    ]
                },
                {
                    "featureType": "all",
                    "elementType": "labels.text",
                    "stylers": [
                        { "visibility": "on" }
                    ]
                },
                {
                    "featureType": "landscape",
                    "elementType": "all",
                    "stylers": [
                        { "color": "#f2f2f2" }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "all",
                    "stylers": [
                        { "color": "#90cdf4" }
                    ]
                }
            ];
        }

        /**
         * Toggle clinic info overlay
         */
        toggleClinicOverlay() {
            if (this.isOverlayOpen) {
                this.closeClinicOverlay();
            } else {
                this.openClinicOverlay();
            }
        }

        /**
         * Open clinic info overlay
         */
        openClinicOverlay() {
            const overlay = $('#clinic-info-overlay');
            const toggleBtn = $('#info-toggle-btn');

            overlay.addClass('active').attr('aria-hidden', 'false');
            toggleBtn.attr('aria-pressed', 'true');

            this.isOverlayOpen = true;

            // Focus management
            setTimeout(() => {
                overlay.find('#clinic-overlay-title').focus();
            }, 100);

            // Track analytics
            this.trackEvent('clinic_overlay_opened', {
                source: 'footer_map'
            });
        }

        /**
         * Close clinic info overlay
         */
        closeClinicOverlay() {
            const overlay = $('#clinic-info-overlay');
            const toggleBtn = $('#info-toggle-btn');

            overlay.removeClass('active').attr('aria-hidden', 'true');
            toggleBtn.attr('aria-pressed', 'false');

            this.isOverlayOpen = false;

            // Return focus to toggle button
            toggleBtn.focus();
        }

        /**
         * Toggle fullscreen mode
         */
        toggleFullscreen() {
            const mapContainer = $('#footer-interactive-map');
            const toggleBtn = $('#fullscreen-toggle-btn');

            if (this.isFullscreen) {
                // Exit fullscreen
                mapContainer.removeClass('fullscreen-active');
                toggleBtn.attr('aria-pressed', 'false');
                $('body').removeClass('map-fullscreen-active');

                this.isFullscreen = false;
            } else {
                // Enter fullscreen
                mapContainer.addClass('fullscreen-active');
                toggleBtn.attr('aria-pressed', 'true');
                $('body').addClass('map-fullscreen-active');

                this.isFullscreen = true;
            }

            // Resize map if Google Maps is available
            if (this.map) {
                setTimeout(() => {
                    google.maps.event.trigger(this.map, 'resize');
                }, 300);
            }

            // Track analytics
            this.trackEvent('map_fullscreen_toggled', {
                mode: this.isFullscreen ? 'enter' : 'exit'
            });
        }

        /**
         * Handle mobile touch interactions
         */
        handleMobileTouch(e) {
            if (!this.isMobile) return;

            const touchHint = $('#mobile-touch-hint');

            // Show touch instructions temporarily
            touchHint.addClass('visible');

            setTimeout(() => {
                touchHint.removeClass('visible');
            }, 2000);
        }

        /**
         * Handle window resize
         */
        handleResize() {
            const newIsMobile = window.innerWidth <= 768;

            if (newIsMobile !== this.isMobile) {
                this.isMobile = newIsMobile;
                this.handleMobileOptimizations();
            }

            // Resize map
            if (this.map) {
                google.maps.event.trigger(this.map, 'resize');
            }
        }

        /**
         * Handle mobile-specific optimizations
         */
        handleMobileOptimizations() {
            const mapContainer = $('#footer-interactive-map');

            if (this.isMobile) {
                mapContainer.addClass('mobile-optimized');

                // Update map options for mobile
                if (this.map) {
                    this.map.setOptions({
                        gestureHandling: 'cooperative',
                        zoomControl: true,
                        streetViewControl: false
                    });
                }
            } else {
                mapContainer.removeClass('mobile-optimized');

                // Update map options for desktop
                if (this.map) {
                    this.map.setOptions({
                        gestureHandling: 'auto',
                        zoomControl: true,
                        streetViewControl: true
                    });
                }
            }
        }

        /**
         * Handle overlay keyboard navigation
         */
        handleOverlayKeyboard(e) {
            if (e.key === 'Escape') {
                this.closeClinicOverlay();
            }
        }

        /**
         * Show loading state
         */
        showLoadingState() {
            $('#map-loading-state').show();
            $('#map-fallback').hide();
        }

        /**
         * Hide loading state
         */
        hideLoadingState() {
            $('#map-loading-state').hide();
        }

        /**
         * Show fallback map
         */
        showFallbackMap() {
            $('#map-loading-state').hide();
            $('#map-fallback').show();

            console.log('Footer Maps: Showing fallback static map');
        }

        /**
         * Handle map bounds change
         */
        handleMapBoundsChange() {
            // Custom logic for bounds changes if needed
        }

        /**
         * Setup accessibility features
         */
        setupAccessibility() {
            // Ensure proper ARIA labels
            const mapContainer = $('#footer-interactive-map');

            if (!mapContainer.attr('aria-label')) {
                mapContainer.attr('aria-label', 'Interactive map showing clinic location');
            }

            // Setup focus management
            this.setupFocusManagement();
        }

        /**
         * Setup focus management
         */
        setupFocusManagement() {
            // Tab trapping for overlay
            $(document).on('keydown', '#clinic-info-overlay', (e) => {
                if (e.key === 'Tab') {
                    this.handleTabTrap(e);
                }
            });
        }

        /**
         * Handle tab trapping in overlay
         */
        handleTabTrap(e) {
            const overlay = $('#clinic-info-overlay');
            const focusableElements = overlay.find('button, a, input, select, textarea, [tabindex]:not([tabindex="-1"])');
            const firstElement = focusableElements.first();
            const lastElement = focusableElements.last();

            if (e.shiftKey && document.activeElement === firstElement[0]) {
                e.preventDefault();
                lastElement.focus();
            } else if (!e.shiftKey && document.activeElement === lastElement[0]) {
                e.preventDefault();
                firstElement.focus();
            }
        }

        /**
         * Track analytics events
         */
        trackAnalytics(e) {
            const analyticsData = $(e.currentTarget).data('analytics');

            if (analyticsData) {
                this.trackEvent('footer_map_interaction', {
                    action: analyticsData,
                    element: e.currentTarget.tagName.toLowerCase()
                });
            }
        }

        /**
         * Track custom events
         */
        trackEvent(eventName, parameters = {}) {
            // Google Analytics 4 tracking
            if (typeof gtag !== 'undefined') {
                gtag('event', eventName, parameters);
            }

            // WordPress analytics hook
            if (typeof wp !== 'undefined' && wp.hooks) {
                wp.hooks.doAction('footer_maps_event', eventName, parameters);
            }

            console.log('Footer Maps Event:', eventName, parameters);
        }

        /**
         * Utility: Debounce function
         */
        debounce(func, wait) {
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

    }

    /**
     * Initialize Footer Maps when DOM is ready
     */
    $(document).ready(function() {

        // Check if footer map section exists
        if ($('#footer-map-section').length > 0) {

            // Create global instance
            window.footerMapsInstance = new FooterMaps();

            console.log('Footer Maps: System initialized');

        } else {
            console.log('Footer Maps: No map section found, skipping initialization');
        }

    });

    /**
     * Error handling for Google Maps API failures
     */
    window.addEventListener('error', function(e) {
        if (e.message && e.message.includes('Google Maps')) {
            console.error('Footer Maps: Google Maps API error detected');

            if (window.footerMapsInstance) {
                window.footerMapsInstance.showFallbackMap();
            }
        }
    });

})(jQuery);
