(function($) {

    "use strict";

    var SDWeddingDirectory_Missing_Guest_Info = {

        /**
         *  1. Button Click to Open Sidebar Form
         *  ------------------------------------
         */
        missing_guest_info: function(){

            /**
             *  Have Form ?
             *  -----------
             */
            if( $( '#sdweddingdirectory_missing_guest_info_form' ).length ){

                $( '#sdweddingdirectory_missing_guest_info_form' ).on( 'submit', function(e){

                    /**
                     *  Event Start
                     *  -----------
                     */
                    e.preventDefault();

                    var     _form       =       $( this ).attr( 'id' ),

                            _form_id    =       '#' +   _form;

                    /**
                     *  AJAX
                     *  ----
                     */
                    $.ajax( {

                        type        :   'POST',

                        url         :   SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        dataType    :   'json',

                        data:       {

                            /**
                             *  1. Action & Security
                             *  --------------------
                             */
                            'action'                    :   'sdweddingdirectory_missing_guest_info',

                            'security'                  :   $( _form_id + ' #sdweddingdirectory_missing_info_security'  ).val(),

                            'guest_unique_id'           :   $( _form_id + ' #guest_unique_id'  ).val(),

                            'couple_post_id'            :   $( _form_id + ' #couple_post_id'  ).val(),

                            /**
                             *  Update Input
                             *  ------------
                             */
                            'input_group'               :   {

                                'first_name'                :   $( _form_id + ' #first_name'  ).val(),

                                'last_name'                 :   $( _form_id + ' #last_name'  ).val(),

                                'guest_need_hotel'          :   $( _form_id + ' input[name=guest_need_hotel]:checked'  ).val(),

                                'guest_age'                 :   $( _form_id + ' input[name=guest_age]:checked'  ).val(),

                                'guest_email'               :   $( _form_id + ' #guest_email'  ).val(),

                                'guest_contact'             :   $( _form_id + ' #guest_contact'  ).val(),

                                'guest_address'             :   $( _form_id + ' #guest_address'  ).val(),

                                'guest_city'                :   $( _form_id + ' #guest_city'  ).val(),

                                'guest_state'               :   $( _form_id + ' #guest_state'  ).val(),

                                'guest_zip_code'            :   $( _form_id + ' #guest_zip_code'  ).val(),

                                'request_missing_info'      :   'yes'
                            }
                        },

                        beforeSend: function(){

                            /**
                             *  Start Loader
                             *  ------------
                             */
                            SDWeddingDirectory_Elements.button_loader_start( _form_id );
                        },

                        success: function ( PHP_RESPONSE ) {

                            /**
                             *  Success
                             *  -------
                             */
                            sdweddingdirectory_alert( PHP_RESPONSE );

                            /**
                             *  Have Thank you message ?
                             *  ------------------------
                             */
                            if( PHP_RESPONSE.html != '' ){

                                /**
                                 *  Thank you Note
                                 *  --------------
                                 */
                                $( PHP_RESPONSE.html ).insertBefore( _form_id );

                                /**
                                 *  Remove Form
                                 *  -----------
                                 */
                                $( _form_id ).remove();
                            }
                        },

                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory / Couple / Guest / Missing Guest Info' );

                            console.log(xhr.status);

                            console.log(thrownError);

                            console.log(xhr.responseText);
                        },

                        complete: function( event, request, settings ){

                            /**
                             *  Start Loader
                             *  ------------
                             */
                            SDWeddingDirectory_Elements.button_loader_end( _form_id );
                        }
                    });

                } );
            }

        },

        /**
         *  Missing Guest Info
         *  ------------------
         */
        init: function() {

            /**
             *  1. Missing Guest Info
             *  ---------------------
             */
            this.missing_guest_info();
        },
    };

    /**
     *  Missing Guest Info
     *  ------------------
     */
    $(document).ready( function() { SDWeddingDirectory_Missing_Guest_Info.init(); });

})(jQuery);