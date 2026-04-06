(function($) {

    'use strict';

    /**
     *  Term Page Object
     *  ----------------
     */
    var SDWeddingDirectory_Venue_Term_Page = {

        /**
         *  Pagination wise venue load
         *  ----------------------------
         */
        pagination_item_load: function(){

            /**
             *  Have Pagination ?
             *  -----------------
             */
            if( $( '.sdweddingdirectory_venue_pagination_number .pagination_number' ).length ){

                /**
                 *  When Click
                 *  ----------
                 */
                $( '.sdweddingdirectory_venue_pagination_number .pagination_number' ).on( 'click', function(){

                    /**
                     *  Hidden Current Paged Updatd
                     *  ---------------------------
                     */
                    $( 'input[name=paged]' ).val( $( this ).attr( 'data-value' ) );

                    /**
                     *  Load Venue
                     *  ------------
                     */
                    SDWeddingDirectory_Venue_Term_Page.find_venue();

                } );
            }
        },

        /**
         *  3. Search Venue Handler
         *  -------------------------
         */
        find_venue: function(){

            /**
             *  1. Empty Result Page
             *  --------------------
             */
            if( $( '#venue_search_result' ).length ){

                /**
                 *  Empty Result
                 *  ------------
                 */
                $( '#venue_search_result' ).empty();

                /**
                 *  Update HTML
                 *  -----------
                 */
                $( '<div class="loader-ajax-container-wrap"><div class="loader-ajax-container"><div class="loader-ajax"></div></div></div>' ).insertBefore( $( '#venue_search_result' ) );
            }

            /**
             *  4. AJAX Start
             *  -------------
             */
            $.ajax({

                type: 'POST',

                dataType: 'json',

                url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                
                data: {

                    /**
                     *  Action of Function memeber
                     *  --------------------------
                     */
                    'action'                    :   'sdweddingdirectory_venue_term_page',

                    /**
                     *  Venue Location Data
                     *  ---------------------
                     */
                    'term_id'                   :   $('input[name=term_id]').length

                                                ?   $('input[name=term_id]').val()

                                                :   '',
                    /**
                     *  Current page
                     *  ------------
                     */
                    'paged'                     :   $('input[name=paged]').val(),

                    /**
                     *  Venue Layout
                     *  --------------
                     */
                    'layout'                    :   parseInt( $( ".switch_layout.active" ).attr( 'data-layout' ) ),
                },

                beforeSend: function(){

                    /**
                     *  Animation
                     *  ---------
                     */
                    $('html, body').animate( { 

                        scrollTop: 0

                    }, 'slow' );

                    /**
                     *  Before AJAX to find venue in backend removed map + venue data on search result page.
                     *  ----------------------------------------------------------------------------------------
                     */
                    var map_id          =   $( '#map_handler' ).attr( 'data-map-id' ),

                        map_class       =   $( '#map_handler' ).attr( 'data-map-class' );

                    /**
                     *  Venue Search Result Handling Section ID to targe elements
                     *  -----------------------------------------------------------
                     */
                    $( '#map_handler' ).html( '' );

                    $( '#map_handler' ).append( '<div id="'+map_id+'" class="'+map_class+'"></div>' );

                    /**
                     *  Before load the pagination data removed inner data
                     *  --------------------------------------------------
                     */
                    $( '#venue_have_pagination' ).html( '' );

                },
                success: function( PHP_RESPONSE ){

                    /**
                     *  1. Loader Removed
                     *  -----------------
                     */
                    $('.loader-ajax-container-wrap').remove();

                    /**
                     *  2. Venue Data HTML Update in Document
                     *  ---------------------------------------
                     */
                    $( '#venue_search_result' ).html( PHP_RESPONSE.venue_html_data );

                    /**
                     *  Have Found Result ?
                     *  -------------------
                     */
                    if( _is_empty( PHP_RESPONSE.found_result ) ){

                        $( '#found_venues' ).find( 'span' ).html( PHP_RESPONSE.found_result );

                    }else{

                        $( '#found_venues' ).find( 'span' ).html( PHP_RESPONSE.found_result );
                    }

                    /**
                     *  3. Load the Pagination Data
                     *  ---------------------------
                     */
                    if( PHP_RESPONSE.have_pagination !== '' ){

                        $( '#venue_have_pagination' ).html( PHP_RESPONSE.pagination );
                    }

                    /**
                     *  Load Default Script
                     *  -------------------
                     */
                    SDWeddingDirectory_Venue_Term_Page.default_script();
                },

                error: function (xhr, ajaxOptions, thrownError) {

                    console.log( 'SDWeddingDirectory Find Venue Error..' );

                    console.log(xhr.status);

                    console.log(thrownError);

                    console.log(xhr.responseText);
                },

            });
        },

        /**
         *  If Have Map to load this function : Venue Map Information
         *  -----------------------------------------------------------
         */
        sdweddingdirectory_venue_map_data : function(){

            var locations = new Array();

            $('#sdweddingdirectory-find-venue-tab-content .active.show .sdweddingdirectory_venue').map(function( index, value ) {

                locations.push({

                    'id'                        : $(this).attr('id'),

                    'lat'                       : parseFloat( $(this).find('.venue_latitude').val() )

                                                ? parseFloat( $(this).find('.venue_latitude').val() )

                                                : SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_map_latitude,

                    'lng'                       : parseFloat( $(this).find('.venue_longitude').val() )

                                                ? parseFloat( $(this).find('.venue_longitude').val() )

                                                : SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_map_longitude,

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
         *  Default Run Script
         *  ------------------
         */
        default_script: function(){

            /**
             *  Have Pagination ?
             *  -----------------
             */
            SDWeddingDirectory_Venue_Term_Page.pagination_item_load();

            /**
             *  Have SDWeddingDirectory - Review Object ?
             *  ---------------------------------
             */
            if ( typeof SDWeddingDirectory_Review === 'object' ){

                SDWeddingDirectory_Review.get_review();
            }

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
            if( $( '#sdweddingdirectory-find-venue-tab-content .active.show .sdweddingdirectory_venue' ).length ){

                /**
                 *  Map Data
                 *  --------
                 */
                SDWeddingDirectory_Map_Data[ 'sdweddingdirectory_map_data' ]   = SDWeddingDirectory_Venue_Term_Page.sdweddingdirectory_venue_map_data();
            }

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

            /**
             *  Pagination Object Call
             *  ----------------------
             */
            SDWeddingDirectory_Pagination.sdweddingdirectory_pagination_call();

            /**
             *  Have SDWeddingDirectory Core Object ?
             *  -----------------------------
             */
            if ( typeof SDWeddingDirectory_Elements === 'object' ){

                SDWeddingDirectory_Elements.sdweddingdirectory_select_option();
            }

            /**
             *  Removed map location information on front page after document load
             *  ------------------------------------------------------------------
             */
            if( $('.sdweddingdirectory_venue .d-none').length ){

                $('.sdweddingdirectory_venue .d-none').remove();
            }

            /**
             *  Have Verify Badge ?
             *  -------------------
             */
            if ( typeof SDWeddingDirectory_Elements === 'object' ){

                SDWeddingDirectory_Elements.tooltip();
            }
        },

        /**
         *  1. Load Search Venue Script
         *  -----------------------------
         */
        init: function() {

            /**
             *  Run Default
             *  -----------
             */
            this.default_script();
        },
    }

    /**
     *  Document is ready to load the script
     *  ------------------------------------
     */
    $(document).ready( function() {  SDWeddingDirectory_Venue_Term_Page.init();  } );

})(jQuery);