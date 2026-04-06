/**
 *  SDWeddingDirectory - Vendor Singular Page
 *  ---------------------------------
 */
(function($) {

    "use strict";

    var SDWeddingDirectory_Vendor_Singular_Page = {

        /**
         *  Vendor's venue Map with marker
         *  --------------------------------
         */
        vendor_business_map: function(){

            /**
             *  Map Data : Collection
             *  ---------------------
             */
            var SDWeddingDirectory_Map_Data = new Array();

            /**
             *  Load Map ID
             *  -----------
             */
            SDWeddingDirectory_Map_Data[ 'sdweddingdirectory_map_id' ]     =  $( '#map_handler' ).attr( 'data-map-id' );

            /**
             *  Map Zoom Level
             *  --------------
             */
            SDWeddingDirectory_Map_Data[ 'sdweddingdirectory_map_zoom_level' ]  =  parseInt( SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_map_zoom_level );

            /**
             *  1. Defult Map : Latitude
             *  ------------------------
             */
            SDWeddingDirectory_Map_Data[ 'sdweddingdirectory_map_latitude' ]    = SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_map_latitude;

            /**
             *  2. Defult Map : Longitude
             *  -------------------------
             */
            SDWeddingDirectory_Map_Data[ 'sdweddingdirectory_map_longitude' ]   = SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_map_longitude;

            /**
             *  InfoBox Parent Class
             *  --------------------
             */
            SDWeddingDirectory_Map_Data[ 'sdweddingdirectory_info_window_parent_class' ] = 'sdweddingdirectory-map-venue-overview';  

            /**
             *  Have venue on page ?
             *  ----------------------
             */
            if( $( '.sdweddingdirectory_venue' ).length ){

                /**
                 *  Map Data
                 *  --------
                 */
                SDWeddingDirectory_Map_Data[ 'sdweddingdirectory_map_data' ]   = SDWeddingDirectory_Vendor_Singular_Page.sdweddingdirectory_venue_map_data();
            }

            var     map_id      =   SDWeddingDirectory_Map_Data.sdweddingdirectory_map_id;

            /**
             *  Make sure : Map is first time load on document
             *  ----------------------------------------------
             */
            if( !  $( '#' + map_id ).hasClass( 'map-load-done' ) ){

                /**
                 *  Hidden Div Click to Show Map
                 *  ----------------------------
                 */
                if( $( '#' + map_id ).hasClass( 'is-hidden-map' ) ){

                    var tab_id  =  $( '#' + map_id ).closest( '.tab-pane' ).attr( 'aria-labelledby' );

                    /**
                     *  Click on Tab
                     *  ------------
                     */
                    $( '#' + tab_id ).one( 'click', function(){

                        $( '#' + map_id ).removeClass( 'is-hidden-map' );

                        setTimeout( function(){

                            SDWeddingDirectory_Vendor_Singular_Page.load_map_with_marker( SDWeddingDirectory_Map_Data );

                        }, 200 );

                    } );
                }

                /**
                 *  Map not in hidden now
                 *  ---------------------
                 */
                else{

                    SDWeddingDirectory_Vendor_Singular_Page.load_map_with_marker( SDWeddingDirectory_Map_Data );
                }
            }
        },

        /**
         *  Load Map
         *  --------
         */
        load_map_with_marker: function( SDWeddingDirectory_Map_Data ){

            /**
             *  1. Google Map
             *  -------------
             */
            if( typeof SDWeddingDirectory_Google_Map === 'object' ){

                SDWeddingDirectory_Google_Map.google_map_load_venues( SDWeddingDirectory_Map_Data );
            }

            /**
             *  2. Leaflet Map
             *  --------------
             */
            if( typeof SDWeddingDirectory_Leaflet_Map === 'object' ){

                SDWeddingDirectory_Leaflet_Map.leaflet_map_load_venues( SDWeddingDirectory_Map_Data );
            }
        },

        /**
         *  If Have Map to load this function : Venue Map Information
         *  -----------------------------------------------------------
         */
        sdweddingdirectory_venue_map_data : function(){

            var locations = new Array();

            $( '.sdweddingdirectory_venue' ).map(function( index, value ) {

                locations.push({

                    'id'                        : $(this).attr('id'),

                    'lat'                       : parseFloat( $(this).find('.venue_latitude').val() )

                                                ? parseFloat( $(this).find('.venue_latitude').val() )

                                                : SDWeddingDirectory_Google_Map.default.lat,

                    'lng'                       : parseFloat( $(this).find('.venue_longitude').val() )

                                                ? parseFloat( $(this).find('.venue_longitude').val() )

                                                : SDWeddingDirectory_Google_Map.default.lng,

                    'url'                       : $(this).find('.venue_single_link').val(),

                    'title'                     : $(this).find('.venue_title').val(),

                    'image'                     : $(this).find('.venue_image').val(),

                    'address'                   : $(this).find('.venue_address').val(),

                    'icon'                      : $(this).find('.venue_category_icon').val(),

                    'category_marker'           : $(this).find('.venue_category_marker').val(),

                    'venue_rating'            : $(this).find('.reviews').html(),

                    'get_popup_data'            : $(this).find( '.get_popup_data' ).val(),

                    // 'venue_review_count'      : $(this).find( '.venue_review_count' ).val(),

                    // 'venue_review_average'    : $(this).find( '.venue_review_average' ).val()

                });
            });

            return locations;
        },

        /**
         *  Run Login User Script
         *  ---------------------
         */
        init: function() {

            this.vendor_business_map();
        },
    };

    $(document).ready( function(){ SDWeddingDirectory_Vendor_Singular_Page.init(); });

})(jQuery);