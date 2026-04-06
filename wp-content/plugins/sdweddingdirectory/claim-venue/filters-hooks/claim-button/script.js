(function($) {

  'use strict';

    var SDWeddingDirectory_Claim_Venue =  {

        /**
         *  1. Claim Venue Script
         *  -----------------------
         */
        claim_venue: function(){

            /**
             *  Have Form ?
             *  -----------
             */
            if( $('form#sdweddingdirectory_claim_venue_form').length ){

                $('form#sdweddingdirectory_claim_venue_form').one( 'submit', function(e){

                    var form_id     =   '#' +   $(this).attr('id');

                    /**
                     *  Button Disable When Click
                     *  -------------------------
                     */
                    $( '#sdweddingdirectory_claim_venue_submit_button' ).addClass( 'disabled' ).attr( 'disabled' );

                    /**
                     *  AJAX 
                     *  ----
                     */
                    $.ajax({

                        type: 'POST',

                        dataType: 'json',

                        url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data: {

                            /**
                             *  1. Action + Security
                             *  --------------------
                             */
                            'action'                        : 'sdweddingdirectory_claim_request_action',

                            'security'                      : $( form_id +' #sdweddingdirectory_claim_venue_security').val(),

                            /**
                             *  2. Claim Form Submit
                             *  --------------------
                             */
                            'first_name'            : $( form_id +' #sdweddingdirectory_claim_venue_first_name').val(),

                            'last_name'             : $( form_id +' #sdweddingdirectory_claim_venue_last_name').val(),

                            'contact_number'        : $( form_id +' #sdweddingdirectory_claim_venue_contact_number').val(),

                            'message'               : $( form_id +' #sdweddingdirectory_claim_venue_message').val(),

                            'venue_id'            : $( form_id +' #sdweddingdirectory_claim_venue_id').val(),

                            'vendor_user_id'        : $( form_id +' #sdweddingdirectory_claim_vendor_user_id').val(),
                        },
                        success: function( PHP_RESPONSE ){

                            /**
                             *  Alert
                             *  -----
                             */
                            sdweddingdirectory_alert( PHP_RESPONSE );

                            /**
                             *  Close Model
                             *  -----------
                             */
                            $( '#sdweddingdirectory_claim_venue' ).modal( "hide" );
                        }
                    });

                    e.preventDefault();
                });
            }
        },

        /**
         *  Read Script
         *  -----------
         */
        init: function(){

            /**
             *  1. Claim Venue Script
             *  -----------------------
             */
            this.claim_venue();
        }
    };

    $( document ).ready( function(){ SDWeddingDirectory_Claim_Venue.init(); } );

})(jQuery);