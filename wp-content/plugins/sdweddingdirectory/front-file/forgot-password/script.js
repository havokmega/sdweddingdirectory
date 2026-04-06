/**
 *  Forgot Password script here
 */

(function($) {

    "use strict";

    var SDWeddingDirectory_Forgot_Password = {

        /**
         *  1. Forgot Password Script Running
         *  ---------------------------------
         */
        forgot_password: function(){

            if( $('form#sdweddingdirectory-forgot-password-form').length ){

                $('form#sdweddingdirectory-forgot-password-form').on('submit', function (e){

                    var form_id     =   '#' + $( this ).attr( 'id' );

                    $.ajax({

                        type: 'POST',

                        dataType: 'json',

                        url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data: {

                            /**
                             *  Action and Security
                             *  -------------------
                             */
                            'action'     :   'sdweddingdirectory_forgot_password_form_action',

                            'security'   :   $( form_id + ' #sdweddingdirectory_forgot_password_security').val(),

                            'email'      :   $( form_id + ' #sdweddingdirectory_forgot_password_email').val(),
                        },

                        beforeSend: function(){

                            SDWeddingDirectory_Elements.button_loader_start( form_id );
                        },

                        complete: function(){

                            SDWeddingDirectory_Elements.button_loader_end( form_id );
                        },

                        success: function (PHP_RESPONSE) {

                            /**
                             *  Empty Fields
                             *  ------------
                             */
                            $( form_id + ' #sdweddingdirectory_forgot_password_email').val('');

                            sdweddingdirectory_alert( PHP_RESPONSE );

                            if( PHP_RESPONSE.register == true ){

                                setTimeout(function(){ $("#forgot_password_popup").modal('hide'); }, 500 );
                            }
                        },
                    });

                    e.preventDefault();
                    return false;
                });
            }
        },

        /**
         *  Forgot Password Object
         *  ----------------------
         */
        init: function() {

            /**
             *  1. Load Script
             *  --------------
             */
            this.forgot_password();
        }
    };

    $(document).ready( function(){ SDWeddingDirectory_Forgot_Password.init(); });

})(jQuery);