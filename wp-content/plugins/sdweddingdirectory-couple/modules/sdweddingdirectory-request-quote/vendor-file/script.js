/**
 *  Vendor Request Quote Dashboard Script
 *  -------------------------------------
 */
(function($) {

    'use strict';

    var SDWeddingDirectory_Vendor_Request_Quote = {

        /**
         *  1. Removed venue request via dashboard
         *  ----------------------------------------
         */
        removed_request: function(){

            if( $('.sdweddingdirectory_remove_request_quote').length ){

                $('.sdweddingdirectory_remove_request_quote').on('click', function(e){

                    /**
                     *  Confirm to start removeing process
                     *  ----------------------------------
                     */
                    if ( ! confirm( $(this).attr( 'data-alert' ) ) ){

                        return false;
                    }

                    /**
                     *  Request Removed Script
                     *  ----------------------
                     */
                    var section_id              =   '#' + $(this).closest( 'tr' ).attr( 'id' ),

                        request_security        =   $(this).attr( 'data-request-security' ),

                        venue_security        =   $(this).attr( 'data-venue-security' ),

                        request_id              =   $(this).attr( 'data-request-id' ),

                        venue_id              =   $(this).attr( 'data-venue-id' ),

                        tab_id                  =   '#' + $(this).closest( '.tab-pane' ).attr( 'id' ),

                        tab_content_id          =   '#' + $(this).closest( '.tab-pane' ).attr( 'aria-labelledby' );


                    $.ajax({

                        type: 'POST',

                        dataType: 'json',

                        url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data: {

                            /**
                             *  1. SDWeddingDirectory - Action + Security
                             *  ---------------------------------
                             */
                            'action'                :   'sdweddingdirectory_vendor_remove_request_quote',

                            'venue_security'      :   venue_security,

                            'request_security'      :   request_security,

                            /**
                             *  2. Form Data ( Request ID )
                             *  ---------------------------
                             */
                            'request_id'    :   request_id,

                            'venue_id'    :   venue_id
                        },
                        success: function( PHP_RESPONSE ){

                            /**
                             *  Successfully Removed Request Data
                             *  ---------------------------------
                             */
                            sdweddingdirectory_alert( PHP_RESPONSE );

                            /**
                             *  Remove Tab
                             *  ----------
                             */
                            if( $( tab_id ).length ){

                                $( tab_id ).remove();
                            }

                            /**
                             *  Remove Tab Content
                             *  ------------------
                             */
                            if( $( tab_content_id ).length ){

                                $( tab_content_id ).remove();
                            }
                        },
                        beforeSend: function(){

                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory - Vendor Dashboard Request Quote Plugin Script Issue' );

                            console.log(xhr.status);

                            console.log(thrownError);

                            console.log(xhr.responseText);
                        },
                        complete: function( event, request, settings ){

                        }
                    });

                    e.preventDefault();

                });
            }
        },

        /**
         *  2. Select Option to open Tab Content ( Request Quote )
         *  ------------------------------------------------------
         */
        select_venue_show_request_quote: function(){

            /**
             *  Select Option To Show Tab Overview + Tab Content too
             *  ----------------------------------------------------
             */
            if( $( '.sdweddingdirectory-select-tab' ).length ){

                $( '.sdweddingdirectory-select-tab' ).map( function(){

                    $( this ).on( 'change', function(){

                        $('a[id="'+ $(this).val() +'"]').tab('show');

                    } );

                } );
            }
        },

        /**
         *  3. Find Request
         *  ---------------
         */
        find_request: function(){

            if( $("#sdweddingdirectory-request-search").length ){

                $("#sdweddingdirectory-request-search").on( "keyup", function() {

                    var value = $(this).val().toLowerCase();

                    $("#sdweddingdirectory-request-showcase .tab-pane.active .theme-tabbing-vertical a").filter( function() {

                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                });
            }
        },

        /**
         *  4. Lead Status Change
         *  ---------------------
         */
        lead_status_change: function(){

            $( document ).on( 'change', '.sdwd-lead-status', function(){

                var $select   = $( this ),
                    requestId = $select.data( 'request-id' ),
                    newStatus = $select.val(),
                    $panel    = $select.closest( '.sdwd-lead-panel' ),
                    nonce     = $panel.data( 'nonce' );

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                    data: {
                        'action'     : 'sdwd_update_lead_status',
                        'request_id' : requestId,
                        'status'     : newStatus,
                        'nonce'      : nonce
                    },
                    beforeSend: function(){
                        $select.prop( 'disabled', true );
                    },
                    success: function( response ){
                        sdweddingdirectory_alert( response );

                        if( response.notice == 1 ){
                            var $badge = $panel.find( '.sdwd-status-badge' );
                            $badge.attr( 'class', 'sdwd-status-badge sdwd-status-' + response.status );
                            $badge.text( response.status_label );
                        }
                    },
                    complete: function(){
                        $select.prop( 'disabled', false );
                    },
                    error: function( xhr, ajaxOptions, thrownError ){
                        console.log( 'Lead status update error:', xhr.status, thrownError );
                    }
                });
            });
        },

        /**
         *  5. Activity Tag Click
         *  ---------------------
         */
        activity_tag_click: function(){

            $( document ).on( 'click', '.sdwd-activity-tag', function(){

                var $btn      = $( this ),
                    tag       = $btn.data( 'tag' ),
                    requestId = $btn.data( 'request-id' ),
                    nonce     = $btn.data( 'nonce' );

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                    data: {
                        'action'     : 'sdwd_add_activity_tag',
                        'request_id' : requestId,
                        'tag'        : tag,
                        'nonce'      : nonce
                    },
                    beforeSend: function(){
                        $btn.prop( 'disabled', true ).addClass( 'btn-secondary text-white' ).removeClass( 'btn-outline-secondary' );
                    },
                    success: function( response ){
                        sdweddingdirectory_alert( response );
                    },
                    complete: function(){
                        setTimeout( function(){
                            $btn.prop( 'disabled', false ).removeClass( 'btn-secondary text-white' ).addClass( 'btn-outline-secondary' );
                        }, 1500 );
                    },
                    error: function( xhr, ajaxOptions, thrownError ){
                        console.log( 'Activity tag error:', xhr.status, thrownError );
                    }
                });
            });
        },

        /**
         *  6. Add Note
         *  -----------
         */
        add_note: function(){

            $( document ).on( 'click', '.sdwd-add-note', function(){

                var $btn      = $( this ),
                    requestId = $btn.data( 'request-id' ),
                    $panel    = $btn.closest( '.sdwd-lead-panel' ),
                    nonce     = $panel.data( 'nonce' ),
                    $textarea = $panel.find( '.sdwd-note-input' ),
                    note      = $textarea.val().trim();

                if( ! note ){
                    $textarea.focus();
                    return;
                }

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                    data: {
                        'action'     : 'sdwd_add_lead_note',
                        'request_id' : requestId,
                        'note'       : note,
                        'nonce'      : nonce
                    },
                    beforeSend: function(){
                        $btn.prop( 'disabled', true );
                    },
                    success: function( response ){
                        sdweddingdirectory_alert( response );

                        if( response.notice == 1 ){
                            $textarea.val( '' );

                            var $history = $( '#sdwd-history-' + requestId );
                            if( $history.is( ':visible' ) ){
                                SDWeddingDirectory_Vendor_Request_Quote.load_history( requestId, nonce );
                            }
                        }
                    },
                    complete: function(){
                        $btn.prop( 'disabled', false );
                    },
                    error: function( xhr, ajaxOptions, thrownError ){
                        console.log( 'Add note error:', xhr.status, thrownError );
                    }
                });
            });
        },

        /**
         *  7. Toggle History
         *  -----------------
         */
        toggle_history: function(){

            $( document ).on( 'click', '.sdwd-toggle-history', function( e ){

                e.preventDefault();

                var $link     = $( this ),
                    requestId = $link.data( 'request-id' ),
                    $panel    = $link.closest( '.sdwd-lead-panel' ),
                    nonce     = $panel.data( 'nonce' ),
                    $timeline = $( '#sdwd-history-' + requestId );

                if( $timeline.is( ':visible' ) ){
                    $timeline.slideUp( 200 );
                    $link.find( 'i.fa' ).removeClass( 'fa-chevron-up' ).addClass( 'fa-history' );
                    return;
                }

                SDWeddingDirectory_Vendor_Request_Quote.load_history( requestId, nonce );
                $link.find( 'i.fa' ).removeClass( 'fa-history' ).addClass( 'fa-chevron-up' );
            });
        },

        /**
         *  Load History via AJAX
         *  ---------------------
         */
        load_history: function( requestId, nonce ){

            var $timeline = $( '#sdwd-history-' + requestId );

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                data: {
                    'action'     : 'sdwd_get_lead_history',
                    'request_id' : requestId,
                    'nonce'      : nonce
                },
                beforeSend: function(){
                    $timeline.html( '<p class="text-muted small"><i class="fa fa-spinner fa-spin"></i> Loading...</p>' ).slideDown( 200 );
                },
                success: function( response ){
                    if( response.notice == 1 ){
                        $timeline.html( response.html );
                    }
                },
                error: function( xhr, ajaxOptions, thrownError ){
                    console.log( 'Load history error:', xhr.status, thrownError );
                    $timeline.html( '<p class="text-muted small">Error loading history.</p>' );
                }
            });
        },

        /**
         *  8. Save Booking Capacity
         *  ------------------------
         */
        save_capacity: function(){

            $( document ).on( 'click', '#sdwd-save-capacity', function(){

                var $btn      = $( this ),
                    $input    = $( '#sdwd-booking-capacity' ),
                    vendorId  = $input.data( 'vendor-id' ),
                    nonce     = $input.data( 'nonce' ),
                    capacity  = parseInt( $input.val(), 10 ) || 1;

                if( capacity < 1 ) capacity = 1;

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                    data: {
                        'action'    : 'sdwd_update_booking_capacity',
                        'vendor_id' : vendorId,
                        'capacity'  : capacity,
                        'nonce'     : nonce
                    },
                    beforeSend: function(){
                        $btn.prop( 'disabled', true ).find( 'i' ).attr( 'class', 'fa fa-spinner fa-spin' );
                    },
                    success: function( response ){
                        sdweddingdirectory_alert( response );
                    },
                    complete: function(){
                        $btn.prop( 'disabled', false ).find( 'i' ).attr( 'class', 'fa fa-check' );
                    },
                    error: function( xhr, ajaxOptions, thrownError ){
                        console.log( 'Save capacity error:', xhr.status, thrownError );
                    }
                });
            });
        },

        /**
         *  9. Status Filter
         *  ----------------
         */
        status_filter: function(){

            $( document ).on( 'change', '#sdwd-status-filter', function(){

                var filterVal = $( this ).val();

                $( '#sdweddingdirectory-request-showcase .tab-pane.active .theme-tabbing-vertical a' ).each( function(){

                    var $tab      = $( this ),
                        tabStatus = $tab.data( 'lead-status' ) || 'new';

                    if( filterVal === '' || tabStatus === filterVal ){
                        $tab.show();
                    } else {
                        $tab.hide();
                    }
                });
            });
        },

        /**
         *  Load Script
         *  -----------
         */
        init: function(){

            /**
             *  1. Removed venue request via dashboard
             *  ----------------------------------------
             */
            this.removed_request();

            /**
             *  2. Show Request Quote
             *  ---------------------
             */
            this.select_venue_show_request_quote();

            /**
             *  3. Find Request
             *  ---------------
             */
            this.find_request();

            /**
             *  4. Lead Status Change
             *  ---------------------
             */
            this.lead_status_change();

            /**
             *  5. Activity Tag Click
             *  ---------------------
             */
            this.activity_tag_click();

            /**
             *  6. Add Note
             *  -----------
             */
            this.add_note();

            /**
             *  7. Toggle History
             *  -----------------
             */
            this.toggle_history();

            /**
             *  8. Save Booking Capacity
             *  ------------------------
             */
            this.save_capacity();

            /**
             *  9. Status Filter
             *  ----------------
             */
            this.status_filter();
        }
    }

    $( document ).ready( function(){ SDWeddingDirectory_Vendor_Request_Quote.init(); } );

})(jQuery);
