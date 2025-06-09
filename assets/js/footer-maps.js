/**
 * Footer Google Maps Integration
 *
 * Interactive Google Maps integration for the modern footer component
 * with Beverly Hills clinic location and customizable styling.
 *
 * @package MedSpaTheme
 * @since 2.0.0
 * @author CODE-GEN-001
 * @workflow CODE-GEN-WF-001
 */

(function($) {
    'use strict';

    let footerMap = null;
    let clinicMarker = null;

    // Beverly Hills clinic location
    const CLINIC_LOCATION = {
        lat: 34.0736,
        lng: -118.4004,
        address: "123 Rodeo Drive, Beverly Hills, CA 90210",
        name: "Dr. Preeti Sharma Medical Spa"
    };

    /**
     * Initialize Google Maps for Footer
     */
    function initFooterMap() {
        console.log('🗺️ Initializing footer Google Maps...');

        const mapContainer = document.getElementById('footer-google-map');
        if (!mapContainer) {
            console.warn('⚠️ Footer map container not found');
            return;
        }

        // Check if Google Maps API is loaded
        if (typeof google === 'undefined' || !google.maps) {
            console.log('📡 Loading Google Maps API...');
            loadGoogleMapsAPI();
            return;
        }

        // Initialize map
        const mapOptions = {
            center: CLINIC_LOCATION,
            zoom: 16,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true,
            zoomControl: true,
            mapTypeControl: false,
            scaleControl: false,
            streetViewControl: false,
            rotateControl: false,
            fullscreenControl: true,
            styles: getCustomMapStyles()
        };

        footerMap = new google.maps.Map(mapContainer, mapOptions);

        // Add clinic marker
        addClinicMarker();

        // Add info window
        addInfoWindow();

        console.log('✅ Footer Google Maps initialized successfully');
    }

    /**
     * Load Google Maps API dynamically
     */
    function loadGoogleMapsAPI() {
        const apiKey = 'YOUR_GOOGLE_MAPS_API_KEY'; // Replace with actual API key
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&callback=initFooterMap`;
        script.async = true;
        script.defer = true;

        // Set global callback
        window.initFooterMap = initFooterMap;

        document.head.appendChild(script);

        console.log('📡 Google Maps API script loaded');
    }

    /**
     * Add clinic marker to map
     */
    function addClinicMarker() {
        const markerIcon = {
            url: 'data:image/svg+xml;base64,' + btoa(`
                <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20" cy="20" r="18" fill="#1B365D" stroke="#D4AF37" stroke-width="2"/>
                    <text x="20" y="26" text-anchor="middle" fill="white" font-family="Arial" font-size="14" font-weight="bold">+</text>
                </svg>
            `),
            scaledSize: new google.maps.Size(40, 40),
            anchor: new google.maps.Point(20, 20)
        };

        clinicMarker = new google.maps.Marker({
            position: CLINIC_LOCATION,
            map: footerMap,
            icon: markerIcon,
            title: CLINIC_LOCATION.name,
            animation: google.maps.Animation.DROP
        });

        console.log('✅ Clinic marker added to footer map');
    }

    /**
     * Add info window for clinic marker
     */
    function addInfoWindow() {
        const infoWindowContent = `
            <div class="map-info-window">
                <h4 style="margin: 0 0 8px 0; color: #1B365D; font-size: 16px; font-weight: 600;">
                    ${CLINIC_LOCATION.name}
                </h4>
                <p style="margin: 0 0 8px 0; color: #666; font-size: 14px; line-height: 1.4;">
                    ${CLINIC_LOCATION.address}
                </p>
                <div class="info-actions" style="display: flex; gap: 8px; margin-top: 12px;">
                    <a href="https://maps.google.com/?q=${CLINIC_LOCATION.lat},${CLINIC_LOCATION.lng}"
                       target="_blank"
                       style="background: #D4AF37; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 500;">
                        Get Directions
                    </a>
                    <a href="tel:+1-555-MEDSPA"
                       style="background: #1B365D; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 500;">
                        Call Now
                    </a>
                </div>
            </div>
        `;

        const infoWindow = new google.maps.InfoWindow({
            content: infoWindowContent,
            maxWidth: 280
        });

        // Show info window on marker click
        clinicMarker.addListener('click', function() {
            infoWindow.open(footerMap, clinicMarker);
        });

        // Auto-open info window after 2 seconds
        setTimeout(() => {
            infoWindow.open(footerMap, clinicMarker);
        }, 2000);

        console.log('✅ Info window added to clinic marker');
    }

    /**
     * Get custom map styles for luxury medical spa theme
     */
    function getCustomMapStyles() {
        return [
            {
                featureType: "all",
                elementType: "geometry.fill",
                stylers: [
                    { saturation: -15 },
                    { lightness: 5 }
                ]
            },
            {
                featureType: "water",
                elementType: "all",
                stylers: [
                    { color: "#a7c6d4" },
                    { lightness: 15 }
                ]
            },
            {
                featureType: "road.highway",
                elementType: "geometry.fill",
                stylers: [
                    { color: "#d4af37" },
                    { lightness: 60 }
                ]
            },
            {
                featureType: "road.arterial",
                elementType: "geometry.fill",
                stylers: [
                    { color: "#1b365d" },
                    { lightness: 80 }
                ]
            },
            {
                featureType: "poi.business",
                elementType: "labels",
                stylers: [
                    { visibility: "simplified" }
                ]
            },
            {
                featureType: "poi.medical",
                elementType: "all",
                stylers: [
                    { visibility: "on" },
                    { color: "#d4af37" }
                ]
            }
        ];
    }

    /**
     * Resize map when footer is visible (for responsive design)
     */
    function resizeFooterMap() {
        if (footerMap) {
            google.maps.event.trigger(footerMap, 'resize');
            footerMap.setCenter(CLINIC_LOCATION);
        }
    }

    /**
     * Update map styling based on Visual Customizer changes
     */
    function updateMapStyling(customizations) {
        if (!footerMap) return;

        console.log('🗺️ Updating map styling with customizations:', customizations);

        // Update map height if specified
        if (customizations.height) {
            $('#footer-google-map').css('height', customizations.height);
            resizeFooterMap();
        }

        // Update custom styles based on color scheme
        if (customizations.primaryColor || customizations.accentColor) {
            const customStyles = getCustomMapStyles();

            // Override colors if provided
            if (customizations.primaryColor) {
                customStyles.push({
                    featureType: "road.arterial",
                    elementType: "geometry.fill",
                    stylers: [{ color: customizations.primaryColor }, { lightness: 80 }]
                });
            }

            if (customizations.accentColor) {
                customStyles.push({
                    featureType: "road.highway",
                    elementType: "geometry.fill",
                    stylers: [{ color: customizations.accentColor }, { lightness: 60 }]
                });
            }

            footerMap.setOptions({ styles: customStyles });
        }

        console.log('✅ Map styling updated');
    }

    /**
     * Initialize footer map when DOM is ready
     */
    $(document).ready(function() {
        // Check if footer map container exists
        if ($('#footer-google-map').length > 0) {
            console.log('🗺️ Footer map container found, initializing...');

            // Initialize map after a slight delay to ensure container is visible
            setTimeout(initFooterMap, 500);

            // Handle window resize
            $(window).on('resize', resizeFooterMap);
        } else {
            console.log('ℹ️ Footer map container not found on this page');
        }
    });

    /**
     * Expose functions for Visual Customizer integration
     */
    window.FooterMaps = {
        updateStyling: updateMapStyling,
        resize: resizeFooterMap,
        init: initFooterMap
    };

    console.log('✅ Footer Maps integration loaded successfully');

})(jQuery);
