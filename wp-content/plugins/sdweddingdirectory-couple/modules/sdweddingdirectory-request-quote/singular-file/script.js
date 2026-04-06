/**
 *  SDWeddingDirectory - Request Quote Script
 *  ---------------------------------
 */
(function($) {

    'use strict';

    var SDWeddingDirectory_Request_Quote = {

        /**
         *  1. Sending Reqeust Quote Process
         *  --------------------------------
         */
        send_request_quote: function(){

            /**
             *  Make sure document have this id / class
             *  ---------------------------------------
             */
            if(  $( 'form.sdweddingdirectory_venue_request_quote' ).length  ){

                /**
                 *  When Submit Form
                 *  ----------------
                 */
                $( 'form.sdweddingdirectory_venue_request_quote' ).on( 'submit', function(e){

                    /**
                     *  Event Start
                     *  -----------
                     */
                    e.preventDefault();

                    /**
                     *  Get Form ID
                     *  -----------
                     */
                    var     _this           =       $(this).attr( 'id' ),

                            form_id         =       '#'     +   _this;

                    /**
                     *  Ajax
                     *  ----
                     */
                    $.ajax({

                        type        :   'POST',

                        dataType    :   'json',

                        url         :   SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data        :   {   /** 
                                             *  1. Security + Action
                                             *  --------------------
                                             */
                                            'action'                         :  'sdweddingdirectory_venue_request_form_action',

                                            'security'                       :  $( form_id + ' input[name=sdweddingdirectory_request_quote_security]' ).val(),

                                            'couple_id_security'             :  $( form_id + ' input[name=sdweddingdirectory_request_quote_couple_id_security]' ).val(),

                                            /**
                                             *  2. Form Fields
                                             *  --------------
                                             */
                                            'request_quote_guest'            :  $( form_id + ' input[name=sdweddingdirectory_request_quote_guest]:checked').val(),

                                            'request_quote_budget'           :  $( form_id + ' input[name=sdweddingdirectory_request_quote_budget]').val(),

                                            'request_quote_name'             :  $( form_id + ' input[name=sdweddingdirectory_request_quote_full_name]').val(),

                                            'request_quote_email'            :  $( form_id + ' input[name=sdweddingdirectory_request_quote_email]').val(),

                                            'request_quote_contact'          :  $( form_id + ' input[name=sdweddingdirectory_request_quote_contact]').val(),

                                            'request_quote_wedding_date'     :  $( form_id + ' input[name=sdweddingdirectory_request_quote_wedding_date]').val(),

                                            'request_quote_select_year'      :  $( form_id + ' select[name=request_quote_select_year]').val(),

                                            'request_quote_select_month'     :  $( form_id + ' select[name=request_quote_select_month]').val(),

                                            'request_quote_flexible_date'    :  $( form_id + ' input[name=sdweddingdirectory-request-quote-flexible-date').is(':checked') ? 1 : 0,

                                            'request_quote_message'          :  $( form_id + ' textarea[name=sdweddingdirectory_request_quote_message]').val(),

                                            'request_quote_contact_option'   :  $('input[name=sdweddingdirectory_request_quote_contact_option]:checked', form_id ).val(),

                                            'request_quote_venue_id'       :  $( form_id + ' input[name=sdweddingdirectory_request_quote_venue_post_id]').val(),

                                            'request_quote_couple_id'        :  $( form_id + ' input[name=sdweddingdirectory_request_quote_couple_post_id]').val(),
                                        },

                        beforeSend  :   function(){

                                            /**
                                             *  Loader Start
                                             *  ------------
                                             */
                                            $( '#sdweddingdirectory_request_quote_submit_button' )

                                            .addClass( 'disabled' )

                                            .append( '<i class="ms-2 fa fa-spinner fa-spin"></i>' );
                                        },

                        success     :   function( PHP_RESPONSE ){

                                            /**
                                             *  Remove Loader
                                             *  -------------
                                             */
                                            $( '#sdweddingdirectory_request_quote_submit_button' )

                                            .removeClass( 'disabled' )

                                            .find( 'i' ).remove();

                                            /**
                                             *  Showing Alert Success / Fail
                                             *  ----------------------------
                                             */
                                            sdweddingdirectory_alert( PHP_RESPONSE );

                                            /**
                                             *  Empty Form Fields Values
                                             *  ------------------------
                                             */
                                            $( form_id + ' input[type=text]').val('');

                                            $( form_id + ' input[type=email]').val('');

                                            $( form_id + ' input[type=number]').val('');

                                            $( form_id + ' input[type=date]').val('');

                                            $( form_id + ' textarea').val('');

                                            $( form_id + ' select').val('0');

                                            $( form_id + ' input[type=checkbox]').removeAttr( 'checked' );

                                            $( form_id + ' input[type=radio]').removeAttr( 'checked' );

                                            /**
                                             *  Hide Model Popup
                                             *  ----------------
                                             */
                                            if( $( '.sdweddingdirectory_request_quote_popup_handler' ).length ){

                                                var _model_id   =   '#' + $( '.sdweddingdirectory_request_quote_popup_handler' ).attr( 'id' );

                                                $( _model_id ).modal( 'hide' );
                                            }
                                        },

                        error       :   function (xhr, ajaxOptions, thrownError) {

                                            console.log( 'SDWeddingDirectory - Request Quote Plugin' );

                                            console.log( xhr.status );

                                            console.log( thrownError );

                                            console.log( xhr.responseText );
                                        },

                        complete    :   function( event, request, settings ){}

                    } );

                } );
            }
        },

        /**
         *  2. Request Quote Popup show to update venue id
         *  ------------------------------------------------
         */
        request_quote_popup: function(){

            /**
             *  Have lengh ?
             *  ------------
             */
            $( document ).on( 'click', '.sdweddingdirectory-request-quote-popup', function( event ){

                /**
                 *  Event Start
                 *  -----------
                 */
                event.preventDefault();

                /**
                 *  Variable
                 *  --------
                 */
                var     _id             =       '#' + $(this).attr( 'id' ),

                        venue_id      =       $( this ).attr( 'data-venue-id' );

                /**
                 *  AJAX Request Start
                 *  ------------------
                 */
                $.ajax({

                    type        :   'POST',

                    dataType    :   'json',

                    url         :   SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                    data        :   {
                                        'action'                        : 'sdweddingdirectory_venue_request_form_fields_action',

                                        'venue_id'                    : venue_id,
                                    },

                    beforeSend  :   function(){

                                        /**
                                         *  Loader Start
                                         *  ------------
                                         */
                                        $( _id ).addClass( 'disabled' ).append( '<i class="ms-2 fa fa-spinner fa-spin"></i>' );

                                        /**
                                         *  Hide Model Popup
                                         *  ----------------
                                         */
                                        if( $( '.sdweddingdirectory_request_quote_popup_handler' ).length ){

                                            $( '.sdweddingdirectory_request_quote_popup_handler' ).remove();
                                        }
                                    },

                    success     :   function( PHP_RESPONSE ){

                                        /**
                                         *  Removed Loader
                                         *  --------------
                                         */
                                        $( _id ).removeClass( 'disabled' ).find( 'i.fa-spinner' ).remove();

                                        /**
                                         *  Hide Model Popup
                                         *  ----------------
                                         */
                                        if( $( 'body' ).length ){

                                            $( 'body' ).append( PHP_RESPONSE.request_quote_popup_data );

                                            $( '#' + PHP_RESPONSE.request_quote_popup_id ).modal( 'show' );
                                        }
                                    },

                    error       :   function (xhr, ajaxOptions, thrownError) {

                                        console.log( 'SDWeddingDirectory - Request Quote Plugin' );

                                        console.log( xhr.status );

                                        console.log( thrownError );

                                        console.log( xhr.responseText );
                                    },

                    complete    :   function( event, request, settings ){

                                        /**
                                         *  Make sure, form submit script loaded
                                         *  ------------------------------------
                                         */
                                        SDWeddingDirectory_Request_Quote.send_request_quote();
                                    }
                } );

            } );
        },

        /**
         *  Load Request Quote Script
         *  -------------------------
         */
        init: function(){

            /**
             *  1. Sending Reqeust Quote Process
             *  --------------------------------
             */
            this.send_request_quote();

            /**
             *  2. Request Quote Popup show to update venue id
             *  ------------------------------------------------
             */
            this.request_quote_popup();

            /**
             *  3. Click to Got Section ID
             *  --------------------------
             */
            SDWeddingDirectory_Elements.click_to_go( 'request_quote_mobile_button', $( '.sidebar-widgets .sdweddingdirectory_venue_request_quote' ).attr( 'id' ) );
        }
    }

    /**
     *  Set Global Variable
     *  -------------------
     */
    window.SDWeddingDirectory_Request_Quote = SDWeddingDirectory_Request_Quote;

    /**
     *  Document Ready to Load Obj
     *  --------------------------
     */
    $( document ).ready( function(){ SDWeddingDirectory_Request_Quote.init(); } );

})(jQuery);