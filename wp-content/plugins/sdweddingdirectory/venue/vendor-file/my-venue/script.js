/**
 *  Vendor Venue Dashboard
 *  ------------------------
 */
(function($) {

    "use strict";

    var SDWeddingDirectory_Vendor_My_Venue = {

        /**
         *  1. Publish Venue
         *  ------------------
         */
        publish_venue: function(){

            if( $( '.publish' ).length ){

                $( document ).on( 'click', '.publish', function(e){

                    var     _this           =   $(this),

                            icon_class      =   $(this).find( 'i' ).attr( 'class' ),

                            post_id         =   $(this).attr( 'data-post-id' ),

                            security        =   $(this).attr( 'data-security' ),

                            status          =   '#' + $(this).closest( '.dashboard-list-btn' ).attr( 'id' );


                    /**
                     *  Wait for event
                     *  --------------
                     */
                    e.preventDefault();

                    /**
                     *  AJAX Start
                     *  ----------
                     */
                    $.ajax( {

                        type        : 'POST',

                        dataType    : 'json',

                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data        : {

                            /**
                             *  Action + Security
                             *  -----------------
                             */
                            'action'       :   'sdweddingdirectory_publish_venue_action',

                            'security'     :   security,

                            /**
                             *  Venue Post ID
                             *  ---------------
                             */
                            'venue_id'   :   post_id
                        },

                        success: function( PHP_RESPONSE ){

                            sdweddingdirectory_alert(  PHP_RESPONSE  );

                            $( status ).find( '.badge' ).removeClass( 'badge-warning' ).removeClass( 'badge-info' ).removeClass( 'badge-danger' );

                            $( status ).find( '.badge' ).addClass( 'badge-warning' ).text( 'Waiting for approval' );

                            window.location.reload();
                        },

                        beforeSend: function(){

                            $( _this ).find( 'i' ).attr( 'class', 'fa fa-spinner' );
                        },

                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'Publish Venue Error' );

                            console.log( xhr.status );

                            console.log( thrownError );

                            console.log( xhr.responseText );
                        },

                        complete: function( event, request, settings ){

                            $( _this ).find( 'i' ).attr( 'class', icon_class );
                        }

                    } );

                } );
            }
        },

        /**
         *  2. Venue Removed
         *  ------------------
         */
        venue_removed: function(){

            if( $('.delete').length ){

                $(document).on('click', '.delete', function(e){

                    /**
                     *  Before Removed Post Confirm it.
                     *  -------------------------------
                     */
                    if ( ! confirm( $(this).attr( 'data-alert' ) ) ){

                        return false;
                    }

                    var     _this           =   $(this),

                            icon_class      =   $(this).find( 'i' ).attr( 'class' ),

                            section         =   '#' + $(this).closest( ".dashboard-list-block" ).prop( 'id' ),

                            security        =   $(this).attr( 'data-security' ),

                            venue         =   $(this).attr( 'data-post-id' );

                    /**
                     *  Wait for event
                     *  --------------
                     */
                    e.preventDefault();

                    /**
                     *  AJAX Start
                     *  ----------
                     */
                    $.ajax( {

                        type        : 'POST',

                        dataType    : 'json',

                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data        : {

                            /**
                             *  Action + Security
                             *  -----------------
                             */
                            'action'       :   'sdweddingdirectory_remove_venue_action',

                            'security'     :   security,

                            /**
                             *  Venue Post ID
                             *  ---------------
                             */
                            'venue_id'   :   venue
                        },

                        success: function( PHP_RESPONSE ){

                            sdweddingdirectory_alert( PHP_RESPONSE ); 

                            $( section ).remove();

                            setTimeout( window.location.reload.bind(location), 2000 );
                        },

                        beforeSend: function(){

                            $( _this ).find( 'i' ).attr( 'class', 'fa fa-spinner' );
                        },

                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'Delete Venue Error' );

                            console.log( xhr.status );

                            console.log( thrownError );

                            console.log( xhr.responseText );
                        },

                        complete: function( event, request, settings ){

                            $( _this ).find( 'i' ).attr( 'class', icon_class );
                        }

                    } );
                });
            }
        },

        /**
         *  3. Deactivate Venue
         *  ---------------------
         */
        deactivate_venue: function(){

            if( $( '.deactivate' ).length ){

                $( document ).on( 'click', '.deactivate', function(e){

                    var     _this           =   $(this),

                            icon_class      =   $(this).find( 'i' ).attr( 'class' ),

                            post_id         =   $(this).attr( 'data-post-id' ),

                            security        =   $(this).attr( 'data-security' ),

                            status          =   '#' + $(this).closest( '.dashboard-list-btn' ).attr( 'id' );

                    /**
                     *  Wait for event
                     *  --------------
                     */
                    e.preventDefault();

                    /**
                     *  AJAX Start
                     *  ----------
                     */
                    $.ajax( {

                        type        : 'POST',

                        dataType    : 'json',

                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data        : {

                            /**
                             *  Action + Security
                             *  -----------------
                             */
                            'action'       :   'sdweddingdirectory_deactivate_venue_action',

                            'security'     :   security,

                            /**
                             *  Venue Post ID
                             *  ---------------
                             */
                            'venue_id'   :   post_id
                        },
                        success: function( PHP_RESPONSE ){

                            sdweddingdirectory_alert( PHP_RESPONSE );

                            $( status ).find( '.badge' ).removeClass( 'badge-success' ).removeClass( 'badge-warning' ).removeClass( 'badge-info' );

                            $( status ).find( '.badge' ).addClass( 'badge-danger' ).text( 'Remove' );

                            window.location.reload();
                        },

                        beforeSend: function(){

                            $( _this ).find( 'i' ).attr( 'class', 'fa fa-spinner' );
                        },

                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'Deactivate Venue Error' );

                            console.log( xhr.status );

                            console.log( thrownError );

                            console.log( xhr.responseText );
                        },

                        complete: function( event, request, settings ){

                            $( _this ).find( 'i' ).attr( 'class', icon_class );
                        }

                    } );

                } );
            }
        },

        /**
         *  4. Duplicate Venue
         *  --------------------
         *  @link : https://rudrastyh.com/wordpress/duplicate-post.html
         *  -----------------------------------------------------------
         */
        duplicate_venue: function(){

            /**
             *  Have Length
             *  -----------
             */
            if( $( '.duplicate' ).length ){

                /**
                 *  When Click
                 *  ----------
                 */
                $( document ).on( 'click', '.duplicate', function( e ){

                    /**
                     *  Var
                     *  ---
                     */
                    var     _this           =   $(this),

                            icon_class      =   $(this).find( 'i' ).attr( 'class' ),

                            post_id         =   $(this).attr( 'data-post-id' ),

                            security        =   $(this).attr( 'data-security' );

                    /**
                     *  Wait for event
                     *  --------------
                     */
                    e.preventDefault();

                    /**
                     *  AJAX Start
                     *  ----------
                     */
                    $.ajax( {

                        type                :   'POST',

                        dataType            :   'json',

                        url                 :   SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data                :   {

                            /**
                             *  Action + Security
                             *  -----------------
                             */
                            'action'       :   'sdweddingdirectory_duplicate_venue_action',

                            'security'     :   security,

                            /**
                             *  Venue Post ID
                             *  ---------------
                             */
                            'venue_id'   :   post_id
                        },

                        success: function(data){

                            sdweddingdirectory_alert( data );

                            window.location.reload();
                        },

                        beforeSend: function(){

                            $( _this ).find( 'i' ).attr( 'class', 'fa fa-spinner' );
                        },

                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'Duplicate Venue Error' );

                            console.log( xhr.status );

                            console.log( thrownError );

                            console.log( xhr.responseText );
                        },

                        complete: function( event, request, settings ){

                            $( _this ).find( 'i' ).attr( 'class', icon_class );
                        }

                    } );

                } );
            }
        },

        /**
         *  5. Restore Venue
         *  ------------------
         */
        restore_venue: function(){

            if( $( '.restore' ).length ){

                $( document ).on( 'click', '.restore', function(e){

                    /**
                     *  Var
                     *  ---
                     */
                    var     _this           =   $(this),

                            icon_class      =   $(this).find( 'i' ).attr( 'class' ),

                            post_id         =   $(this).attr( 'data-post-id' ),

                            security        =   $(this).attr( 'data-security' ),

                            status          =   '#' + $(this).closest( '.dashboard-list-btn' ).attr( 'id' );

                    /**
                     *  Wait for event
                     *  --------------
                     */
                    e.preventDefault();

                    /**
                     *  AJAX Start
                     *  ----------
                     */
                    $.ajax( {

                        type        : 'POST',

                        dataType    : 'json',

                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data        : {

                            /**
                             *  Action + Security
                             *  -----------------
                             */
                            'action'       :   'sdweddingdirectory_restore_venue_action',

                            'security'     :   security,

                            /**
                             *  Venue Post ID
                             *  ---------------
                             */
                            'venue_id'   :   post_id
                        },
                        success: function(data){

                            sdweddingdirectory_alert( data );

                            $( status ).find( '.badge' ).removeClass( 'badge-warning' ).removeClass( 'badge-danger' ).removeClass( 'badge-success' );

                            $( status ).find( '.badge' ).addClass( 'badge-info' ).text( 'In Process' );

                            window.location.reload();
                        },

                        beforeSend: function(){

                            $( _this ).find( 'i' ).attr( 'class', 'fa fa-spinner' );
                        },

                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'Restore Venue Error' );

                            console.log( xhr.status );

                            console.log( thrownError );

                            console.log( xhr.responseText );
                        },

                        complete: function( event, request, settings ){

                            $( _this ).find( 'i' ).attr( 'class', icon_class );
                        }
                    });

                    e.preventDefault();

                } );
            }
        },

        /**
         *  6. Spotlight Venues
         *  ---------------------
         */
        spotlight_venue: function(){

            /**
             *  Have Spotlight Badge
             *  --------------------
             */
            if( $( '#sdweddingdirectory_spotlight_badge_assign' ).length ){

                /**
                 *  When Submit
                 *  -----------
                 */
                $( 'form#sdweddingdirectory_spotlight_badge_assign' ).on( 'submit', function(e){

                    /** 
                     *  Have Event ?
                     *  ------------
                     */
                    e.preventDefault();

                    /**
                     *  AJAX
                     *  ----
                     */
                    $.ajax( {

                        type                :       'POST',

                        dataType            :       'json',

                        url                 :       SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data                :       {

                            /**
                             *  Action + Security
                             *  -----------------
                             */
                            'action'       :   'sdweddingdirectory_spotlight_badge_venues',

                            'security'     :   $( '#spotlight_badge_security' ).val(),

                            /**
                             *  Venue Post ID
                             *  ---------------
                             */
                            'venue_ids'  :   $( '#spotlight_venues' ).val()
                        },

                        success: function( PHP_RESPONSE ){

                            /**
                             *  Alert
                             *  -----
                             */
                            sdweddingdirectory_alert( PHP_RESPONSE );

                            /**
                             *  Start Process
                             *  -------------
                             */
                            $( '#spotlight_badge' ).find( 'i' ).remove();

                            /**
                             *  Refresh Page
                             *  ------------
                             */
                            window.location.reload();
                        },

                        beforeSend: function(){

                            /**
                             *  Start Process
                             *  -------------
                             */
                            $( '#spotlight_badge' ).append( '<i class="ms-2 fa fa-spinner fa-spin"></i>' );
                        },

                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Venue_Removed' );
                            console.log(xhr.status);
                            console.log(thrownError);
                            console.log(xhr.responseText);
                        },

                        complete: function( event, request, settings ){

                        }

                    } );

                } );
            }
        },

        /**
         *  7. Featured Venues
         *  --------------------
         */
        featured_venue: function(){

            /**
             *  Have Featured Badge
             *  -------------------
             */
            if( $( '#sdweddingdirectory_featured_badge_assign' ).length ){

                /**
                 *  When Submit
                 *  -----------
                 */
                $( 'form#sdweddingdirectory_featured_badge_assign' ).on( 'submit', function(e){

                    /** 
                     *  Have Event ?
                     *  ------------
                     */
                    e.preventDefault();

                    /**
                     *  AJAX
                     *  ----
                     */
                    $.ajax( {

                        type                :       'POST',

                        dataType            :       'json',

                        url                 :       SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data                :       {

                            /**
                             *  Action + Security
                             *  -----------------
                             */
                            'action'       :   'sdweddingdirectory_featured_badge_venues',

                            'security'     :   $( '#featured_badge_security' ).val(),

                            /**
                             *  Venue Post ID
                             *  ---------------
                             */
                            'venue_ids'  :   $( '#featured_venues' ).val()
                        },

                        success: function( PHP_RESPONSE ){

                            /**
                             *  Alert
                             *  -----
                             */
                            sdweddingdirectory_alert( PHP_RESPONSE );

                            /**
                             *  Start Process
                             *  -------------
                             */
                            $( '#featured_badge' ).find( 'i' ).remove();

                            /**
                             *  Refresh Page
                             *  ------------
                             */
                            window.location.reload();
                        },

                        beforeSend: function(){

                            /**
                             *  Start Process
                             *  -------------
                             */
                            $( '#featured_badge' ).append( '<i class="ms-2 fa fa-spinner fa-spin"></i>' );
                        },

                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Venue_Removed' );
                            console.log(xhr.status);
                            console.log(thrownError);
                            console.log(xhr.responseText);
                        },

                        complete: function( event, request, settings ){

                        }

                    } );

                } );
            }
        },

        /**
         *  8. Pro Venues
         *  ---------------
         */
        pro_venue: function(){

            /**
             *  Have Professional Badge
             *  -----------------------
             */
            if( $( '#sdweddingdirectory_professional_badge_assign' ).length ){

                /**
                 *  When Submit
                 *  -----------
                 */
                $( 'form#sdweddingdirectory_professional_badge_assign' ).on( 'submit', function(e){

                    /** 
                     *  Have Event ?
                     *  ------------
                     */
                    e.preventDefault();

                    /**
                     *  AJAX
                     *  ----
                     */
                    $.ajax( {

                        type                :       'POST',

                        dataType            :       'json',

                        url                 :       SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data                :       {

                            /**
                             *  Action + Security
                             *  -----------------
                             */
                            'action'       :   'sdweddingdirectory_professional_badge_venues',

                            'security'     :   $( '#professional_badge_security' ).val(),

                            /**
                             *  Venue Post ID
                             *  ---------------
                             */
                            'venue_ids'  :   $( '#professional_venues' ).val()
                        },

                        success: function( PHP_RESPONSE ){

                            /**
                             *  Alert
                             *  -----
                             */
                            sdweddingdirectory_alert( PHP_RESPONSE );

                            /**
                             *  Start Process
                             *  -------------
                             */
                            $( '#professional_badge' ).find( 'i' ).remove();

                            /**
                             *  Refresh Page
                             *  ------------
                             */
                            window.location.reload();
                        },

                        beforeSend: function(){

                            /**
                             *  Start Process
                             *  -------------
                             */
                            $( '#professional_badge' ).append( '<i class="ms-2 fa fa-spinner fa-spin"></i>' );
                        },

                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Venue_Removed' );
                            console.log(xhr.status);
                            console.log(thrownError);
                            console.log(xhr.responseText);
                        },

                        complete: function( event, request, settings ){

                        }

                    } );

                } );
            }
        },

        /**
         *  1. Load vendor venue scripts.
         *  -------------------------------
         */
        init: function() {

            /**
             *  1. Publish Venue
             *  ------------------
             */
            this.publish_venue();

            /**
             *  2. Venue Removed
             *  ------------------
             */
            this.venue_removed();

            /**
             *  3. Deactivate Venue
             *  ---------------------
             */
            this.deactivate_venue();

            /**
             *  4. Duplicate Venue
             *  --------------------
             */
            this.duplicate_venue();

            /**
             *  5. Restore Venue
             *  ------------------
             */
            this.restore_venue();

            /**
             *  6. Spotlight Venues
             *  ---------------------
             */
            this.spotlight_venue();

            /**
             *  7. Featured Venues
             *  --------------------
             */
            this.featured_venue();

            /**
             *  8. Pro Venues
             *  ---------------
             */
            this.pro_venue();
        },
    };

    $(document).ready( function() {  SDWeddingDirectory_Vendor_My_Venue.init(); });

})(jQuery);