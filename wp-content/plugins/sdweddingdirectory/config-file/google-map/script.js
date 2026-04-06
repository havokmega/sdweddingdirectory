(function($) {

    'use strict';

    var SDWEDDINGDIRECTORY_GOOGLE_MAP_OBJ = window.SDWEDDINGDIRECTORY_GOOGLE_MAP_OBJ || {};

    var SDWeddingDirectory_Google_Map = {

        default: {

            map: SDWEDDINGDIRECTORY_GOOGLE_MAP_OBJ.map_provider || 'google',

            zoom: parseInt( SDWEDDINGDIRECTORY_GOOGLE_MAP_OBJ.zoom_level, 10 ) || 9,

            marker: SDWEDDINGDIRECTORY_GOOGLE_MAP_OBJ.marker || '',

            lat: parseFloat( SDWEDDINGDIRECTORY_GOOGLE_MAP_OBJ.latitude ) || 23.019469943904543,

            lng: parseFloat( SDWEDDINGDIRECTORY_GOOGLE_MAP_OBJ.longitude ) || 72.5730813242451,

            cluster: SDWEDDINGDIRECTORY_GOOGLE_MAP_OBJ.cluster || '',

            close: SDWEDDINGDIRECTORY_GOOGLE_MAP_OBJ.close_icon || '',

            have_api: SDWEDDINGDIRECTORY_GOOGLE_MAP_OBJ.google_map_api || ''
        },

        /**
         *  Google Map loaded in browser ?
         *  ------------------------------
         */
        can_use_google_map: function(){

            return typeof window.google === 'object' &&
                   typeof window.google.maps === 'object';
        },

        /**
         *  Safe float converter
         *  --------------------
         */
        safe_float: function( value, fallback ){

            var parsed = parseFloat( value );

            if( ! isNaN( parsed ) ){

                return parsed;
            }

            return parseFloat( fallback );
        },

        /**
         *  Normalize map data for associative array style payloads
         *  -------------------------------------------------------
         */
        normalize_map_data: function( map_data ){

            map_data = map_data || {};

            if( $.isArray( map_data ) ){

                var collection = {};

                $.each( map_data, function( key, value ){

                    collection[ key ] = value;
                } );

                map_data = collection;
            }

            return map_data;
        },

        /**
         *  Marker icon helper
         *  ------------------
         */
        marker_icon: function( icon ){

            if( icon && typeof icon === 'string' && icon !== '' ){

                return icon;
            }

            if( this.default.marker && this.default.marker !== '' ){

                return this.default.marker;
            }

            return null;
        },

        /**
         *  1. Marker Show on Map
         *  ---------------------
         */
        marker_show_on_map: function(){

            var self = this;

            if( ! self.can_use_google_map() || ! $( '.marker_show_on_map' ).length ){

                return;
            }

            $( '.marker_show_on_map' ).each( function(){

                var $map = $( this ),
                    map_id = $map.attr( 'id' );

                if( ! map_id ){

                    return;
                }

                if( $map.hasClass( 'is-hidden-map' ) ){

                    var tab_id = $map.closest( '.tab-pane' ).attr( 'aria-labelledby' );

                    if( tab_id ){

                        $( '#' + tab_id ).one( 'click', function(){

                            $map.removeClass( 'is-hidden-map' );

                            setTimeout( function(){

                                self.marker_show_on_map();

                            }, 200 );
                        } );
                    }

                    return;
                }

                var position = {
                    lat: self.safe_float( $map.attr( 'data-latitude' ), self.default.lat ),
                    lng: self.safe_float( $map.attr( 'data-longitude' ), self.default.lng )
                };

                var map_zoom = parseInt( $map.attr( 'data-zoom' ), 10 ) || 13;

                var map = new google.maps.Map( document.getElementById( map_id ), {
                    zoom: map_zoom,
                    center: position
                } );

                var marker_args = {
                    position: position,
                    map: map
                };

                var marker_icon = self.marker_icon( $map.attr( 'data-marker' ) );

                if( marker_icon ){

                    marker_args.icon = marker_icon;
                }

                new google.maps.Marker( marker_args );
            } );
        },

        /**
         *  2. Find Map Location : Click to get Lat / Log
         *  ---------------------------------------------
         */
        find_map_location: function(){

            var self = this;

            if( ! self.can_use_google_map() || ! $( '.find_map_location' ).length ){

                return;
            }

            $( '.find_map_location' ).each( function(){

                var map_id = $( this ).attr( 'id' );

                if( ! map_id ){

                    return;
                }

                var lat = self.safe_float( $( '.MapLat' ).val(), self.default.lat ),
                    lng = self.safe_float( $( '.MapLon' ).val(), self.default.lng ),
                    position = { lat: lat, lng: lng };

                var map = new google.maps.Map( document.getElementById( map_id ), {
                    zoom: parseInt( self.default.zoom, 10 ) || 9,
                    center: position
                } );

                var marker_args = {
                    position: position,
                    map: map
                };

                var marker_icon = self.marker_icon( '' );

                if( marker_icon ){

                    marker_args.icon = marker_icon;
                }

                var marker = new google.maps.Marker( marker_args );

                map.addListener( 'click', function( event ) {

                    $( '.MapLat' ).val( event.latLng.lat() );
                    $( '.MapLon' ).val( event.latLng.lng() );

                    marker.setPosition( event.latLng );
                } );
            } );
        },

        /**
         *  Info window HTML for venue map marker
         *  ---------------------------------------
         */
        venue_info_window_html: function( venue ){

            venue = venue || {};

            var image_html = '',
                rating_html = '',
                popup_html = '',
                title = venue.title || '';

            if( venue.image ){

                image_html = '<img src="' + venue.image + '" class="rounded mb-3" alt="' + title + '" />';
            }

            if( venue.venue_rating ){

                rating_html = '<div class="mb-3">' + venue.venue_rating + '</div>';
            }

            if( venue.get_popup_data ){

                popup_html = venue.get_popup_data;
            }

            return  '<div class="weddingdir-google-map-info-window">' +
                        image_html +
                        '<div><h4 class="mb-2"><a href="' + ( venue.url || '#' ) + '" class="title">' + title + '</a></h4></div>' +
                        rating_html +
                        popup_html +
                    '</div>' +
                    '<div class="google-info-window-arrow-container"><div class="google-info-window-arrow"></div></div>';
        },

        /**
         *  3. Find Venue With Map
         *  ------------------------
         */
        google_map_load_venues: function( SDWeddingDirectory_Map_Data ){

            var self = this;

            if( ! self.can_use_google_map() ){

                return;
            }

            SDWeddingDirectory_Map_Data = self.normalize_map_data( SDWeddingDirectory_Map_Data );

            var map_id = SDWeddingDirectory_Map_Data.sdweddingdirectory_map_id,
                map_el = map_id ? document.getElementById( map_id ) : null;

            if( ! map_el ){

                return;
            }

            var google_map_lat = self.safe_float( SDWeddingDirectory_Map_Data.sdweddingdirectory_map_latitude, self.default.lat ),
                google_map_lng = self.safe_float( SDWeddingDirectory_Map_Data.sdweddingdirectory_map_longitude, self.default.lng ),
                map_zoom = parseInt( SDWeddingDirectory_Map_Data.sdweddingdirectory_map_zoom_level, 10 ) || parseInt( self.default.zoom, 10 ) || 9;

            var map = new google.maps.Map( map_el, {
                zoom: map_zoom,
                center: {
                    lat: google_map_lat,
                    lng: google_map_lng
                }
            } );

            var venues = $.isArray( SDWeddingDirectory_Map_Data.sdweddingdirectory_map_data )
                            ? SDWeddingDirectory_Map_Data.sdweddingdirectory_map_data
                            : [];

            if( ! venues.length ){

                return;
            }

            var bounds = new google.maps.LatLngBounds(),
                info_window = new google.maps.InfoWindow(),
                markers = [];

            $.each( venues, function( index, venue ){

                venue = venue || {};

                var point = {
                    lat: self.safe_float( venue.lat, google_map_lat ),
                    lng: self.safe_float( venue.lng, google_map_lng )
                };

                var marker_args = {
                    position: point,
                    map: map,
                    animation: google.maps.Animation.DROP,
                    id: venue.id || ''
                };

                var marker_icon = self.marker_icon( venue.category_marker );

                if( marker_icon ){

                    marker_args.icon = marker_icon;
                }

                var marker = new google.maps.Marker( marker_args );

                marker.addListener( 'click', function(){

                    info_window.setContent( self.venue_info_window_html( venue ) );
                    info_window.open( map, marker );
                } );

                markers.push( marker );
                bounds.extend( marker.getPosition() );
            } );

            if( markers.length > 1 ){

                map.fitBounds( bounds );

                if( typeof window.MarkerClusterer === 'function' ){

                    new window.MarkerClusterer( map, markers, {
                        styles: [ {
                            height: 55,
                            width: 55,
                            textColor: '#FFF',
                            textSize: 14,
                            url: self.default.cluster
                        } ]
                    } );
                } else if( window.markerClusterer && typeof window.markerClusterer.MarkerClusterer === 'function' ){

                    new window.markerClusterer.MarkerClusterer( { map: map, markers: markers } );
                }
            } else {

                map.setCenter( markers[ 0 ].getPosition() );
                map.setZoom( map_zoom );
            }
        },

        /**
         *  4. Website Event Data
         *  ---------------------
         */
        google_map_load_website_event_data: function( SDWeddingDirectory_Map_Data ){

            var self = this;

            if( ! self.can_use_google_map() ){

                return;
            }

            SDWeddingDirectory_Map_Data = self.normalize_map_data( SDWeddingDirectory_Map_Data );

            var map_id = SDWeddingDirectory_Map_Data.sdweddingdirectory_map_id,
                map_el = map_id ? document.getElementById( map_id ) : null;

            if( ! map_el ){

                return;
            }

            var google_map_lat = self.safe_float( SDWeddingDirectory_Map_Data.sdweddingdirectory_map_latitude, self.default.lat ),
                google_map_lng = self.safe_float( SDWeddingDirectory_Map_Data.sdweddingdirectory_map_longitude, self.default.lng ),
                map_zoom = parseInt( SDWeddingDirectory_Map_Data.sdweddingdirectory_map_zoom_level, 10 ) || parseInt( self.default.zoom, 10 ) || 9;

            var map = new google.maps.Map( map_el, {
                zoom: map_zoom,
                center: {
                    lat: google_map_lat,
                    lng: google_map_lng
                }
            } );

            var locations = $.isArray( SDWeddingDirectory_Map_Data.sdweddingdirectory_map_data )
                            ? SDWeddingDirectory_Map_Data.sdweddingdirectory_map_data
                            : [];

            if( ! locations.length ){

                return;
            }

            var bounds = new google.maps.LatLngBounds(),
                info_window = new google.maps.InfoWindow(),
                markers = [];

            $.each( locations, function( index, location ){

                location = location || {};

                var point = {
                    lat: self.safe_float( location.lat, google_map_lat ),
                    lng: self.safe_float( location.lng, google_map_lng )
                };

                var marker_args = {
                    position: point,
                    map: map,
                    animation: google.maps.Animation.DROP,
                    id: location.id || ''
                };

                var marker_icon = self.marker_icon( '' );

                if( marker_icon ){

                    marker_args.icon = marker_icon;
                }

                var marker = new google.maps.Marker( marker_args );

                marker.addListener( 'click', function(){

                    var icon_html = location.icon ? '<i class="p-3 ' + location.icon + '"></i>' : '';

                    var info_html = '<div class="weddingdir-google-map-info-window">' +
                                        '<div class="weddingdir-infowindow-venue-title location-map">' + icon_html + '<h5 class="mb-0">' + ( location.title || '' ) + '</h5></div>' +
                                    '</div>' +
                                    '<div class="google-info-window-arrow-container"><div class="google-info-window-arrow"></div></div>';

                    info_window.setContent( info_html );
                    info_window.open( map, marker );
                } );

                markers.push( marker );
                bounds.extend( marker.getPosition() );
            } );

            if( markers.length > 1 ){

                map.fitBounds( bounds );
            } else {

                map.setCenter( markers[ 0 ].getPosition() );
                map.setZoom( map_zoom );
            }
        },

        /**
         *  Dynamic Search Address Map
         *  --------------------------
         */
        dynamic_search_address_map: function(){

            var self = this;

            if( ! self.can_use_google_map() ){

                return;
            }

            if( ! $( '.sdweddingdirectory_map_handler, .weddingdir_map_handler' ).length ){

                return;
            }

            $( '.sdweddingdirectory_map_handler, .weddingdir_map_handler' ).each( function(){

                var $handler = $( this ),
                    target = $handler.attr( 'id' );

                if( ! target || $handler.hasClass( 'map-load-done' ) ){

                    return;
                }

                var map_id = 'map_' + target,
                    lat_id = 'map_latitude_' + target,
                    lon_id = 'map_longitude_' + target,
                    address_id = 'map_address_' + target;

                var $map = $( '#' + map_id ),
                    $lat = $( '#' + lat_id ),
                    $lon = $( '#' + lon_id ),
                    $address = $( '#' + address_id );

                if( ! $map.length ){

                    return;
                }

                if( $handler.hasClass( 'is-hidden-map' ) || $map.hasClass( 'is-hidden-map' ) ){

                    var tab_id = $handler.closest( '.tab-pane' ).attr( 'aria-labelledby' );

                    if( tab_id ){

                        $( '#' + tab_id ).one( 'click', function(){

                            $handler.removeClass( 'is-hidden-map' );
                            $map.removeClass( 'is-hidden-map' );

                            setTimeout( function(){

                                self.dynamic_search_address_map();

                            }, 200 );
                        } );
                    }

                    return;
                }

                var lat = self.safe_float( $lat.val(), self.default.lat ),
                    lng = self.safe_float( $lon.val(), self.default.lng ),
                    zoom_level = parseInt( self.default.zoom, 10 ) || 9,
                    lat_lng = new google.maps.LatLng( lat, lng );

                var map = new google.maps.Map( document.getElementById( map_id ), {
                    center: lat_lng,
                    zoom: zoom_level,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    panControl: true,
                    zoomControl: true
                } );

                var marker_args = {
                    position: lat_lng,
                    map: map,
                    draggable: true
                };

                var marker_icon = self.marker_icon( '' );

                if( marker_icon ){

                    marker_args.icon = marker_icon;
                }

                var marker = new google.maps.Marker( marker_args );

                var geocoder = ( typeof google.maps.Geocoder === 'function' ) ? new google.maps.Geocoder() : null;
                var info_window = new google.maps.InfoWindow();

                var set_coordinates = function( latlng ){

                    if( $lat.length ){

                        $lat.val( latlng.lat() );
                    }

                    if( $lon.length ){

                        $lon.val( latlng.lng() );
                    }
                };

                var set_address = function( latlng ){

                    if( ! geocoder || ! $address.length ){

                        return;
                    }

                    geocoder.geocode( { location: latlng }, function( results, status ){

                        if( status === 'OK' && $.isArray( results ) && results.length ){

                            $address.val( results[ 0 ].formatted_address );
                        }
                    } );
                };

                if( $address.length && typeof google.maps.places === 'object' && SDWEDDINGDIRECTORY_GOOGLE_MAP_OBJ.place_service ){

                    var autocomplete = new google.maps.places.Autocomplete( $address.get( 0 ), { types: [ 'geocode' ] } );

                    autocomplete.bindTo( 'bounds', map );

                    autocomplete.addListener( 'place_changed', function(){

                        info_window.close();

                        var place = autocomplete.getPlace();

                        if( ! place || ! place.geometry || ! place.geometry.location ){

                            return;
                        }

                        if( place.geometry.viewport ){

                            map.fitBounds( place.geometry.viewport );

                        } else {

                            map.setCenter( place.geometry.location );
                            map.setZoom( 17 );
                        }

                        marker.setPosition( place.geometry.location );
                        set_coordinates( place.geometry.location );

                        if( place.formatted_address ){

                            $address.val( place.formatted_address );

                        } else {

                            set_address( place.geometry.location );
                        }
                    } );
                }

                marker.addListener( 'dragend', function( event ){

                    set_coordinates( event.latLng );
                    map.setCenter( event.latLng );
                    set_address( event.latLng );
                } );

                map.addListener( 'click', function( event ){

                    marker.setPosition( event.latLng );
                    set_coordinates( event.latLng );
                    set_address( event.latLng );
                } );

                $handler.addClass( 'map-load-done' );
            } );
        },

        /**
         *  Load Object Script
         *  ------------------
         */
        init: function() {

            this.marker_show_on_map();
            this.find_map_location();
            this.dynamic_search_address_map();
        }
    };

    window.SDWeddingDirectory_Google_Map = SDWeddingDirectory_Google_Map;

    $( document ).ready( function(){

        SDWeddingDirectory_Google_Map.init();
    } );

})(jQuery);
