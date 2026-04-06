/**
 *  SDWeddingDirectory Couple Register Script Here
 *  --------------------------------------
 */

(function($) {

    "use strict";

    var SDWeddingDirectory_Couple_Registration = {

        /**
         *  1. Couple Registration Form
         *  ---------------------------
         */
        couple_register_form: function(){

            if( $( 'form#sdweddingdirectory_couple_registration_form' ).length ){

                $( 'form#sdweddingdirectory_couple_registration_form' ).on( 'submit', function( e ){

                    /**
                     *  Wait for event
                     *  --------------
                     */
                    e.preventDefault();

                    /**
                     *  Variable
                     *  --------
                     */
                    var     _this           =   $( this ),

                            form_id         =   '#'     +   $( _this ).attr( 'id' ),

                            button_id       =   '#'     +   $( _this ).find( 'button[type=submit]' ).attr( 'id' ),

                            wait_message    =   $( button_id ).attr( 'data-message-wait' );

                    /**
                     *  AJAX
                     *  ----
                     */
                    $.ajax( {

                        type        :   'POST',

                        dataType    :   'json',

                        url         :   SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data        :   {

                            /**
                             *  Action + Security
                             *  -----------------
                             */
                            'action'        :   'sdweddingdirectory_couple_register_form_action',

                            'security'      :   $( form_id + ' #sdweddingdirectory_couple_registration_security').val(),

                            /**
                             *  Registration Fields here
                             *  ------------------------
                             */
                            'sdweddingdirectory_couple_register_last_name'    : $( form_id + ' #sdweddingdirectory_couple_register_last_name').val(),

                            'sdweddingdirectory_couple_register_first_name'   : $( form_id + ' #sdweddingdirectory_couple_register_first_name').val(),

                            'sdweddingdirectory_couple_register_username'     : $( form_id + ' #sdweddingdirectory_couple_register_username').val(),

                            'sdweddingdirectory_couple_register_email'        : $( form_id + ' #sdweddingdirectory_couple_register_email').val(),
                            
                            'sdweddingdirectory_couple_register_wedding_date' : $( form_id + ' #sdweddingdirectory_couple_register_wedding_date').val(),

                            /**
                             *  Password information
                             *  --------------------
                             */
                            'sdweddingdirectory_couple_register_password'     : $( form_id + ' #sdweddingdirectory_couple_register_password').val(),

                            /**
                             *  Register Couple Person [ WHO ]
                             *  ------------------------------
                             */
                            'sdweddingdirectory_register_couple_person'       : $( form_id + ' input[name=sdweddingdirectory_register_couple_person]').val(),

                            /**
                             *  Redirection
                             *  -----------
                             */
                            'redirect_link'                             :   $( '#' + 'couple_register' + '_redirect_link' ).val(),
                        },

                        beforeSend: function(){

                            /**
                             *  Start Loader
                             *  ------------
                             */
                            SDWeddingDirectory_Elements.button_loader_start( form_id );

                            /**
                             *  Disable Button
                             *  --------------
                             */
                            $( button_id ).addClass( 'disabled' );

                            /**
                             *  Have Wait Message ?
                             *  -------------------
                             */
                            if( _is_empty( wait_message ) ){

                                sdweddingdirectory_alert( {

                                    'notice'    :       2,

                                    'message'   :       wait_message

                                } );
                            }
                        },

                        complete: function(){

                            /**
                             *  Stop Loader
                             *  -----------
                             */
                            SDWeddingDirectory_Elements.button_loader_end( form_id );

                            /**
                             *  Disable Button
                             *  --------------
                             */
                            $( button_id ).removeClass( 'disabled' );
                        },

                        success: function( PHP_RESPONSE ){

                            /**
                             *  Have Alert ?
                             *  ------------
                             */
                            sdweddingdirectory_alert( PHP_RESPONSE );

                            /**
                             *  Have Redirection ?
                             *  ------------------
                             */
                            if( PHP_RESPONSE.modal_close == true ){

                                /**
                                 *  Hide Any Modal
                                 *  --------------
                                 */
                                $( '#' + $( 'body .modal.show' ).attr( 'id' ) ).modal( 'hide' );
                            }

                            /**
                             *  Have Redirection ?
                             *  ------------------
                             */
                            if( PHP_RESPONSE.redirect == true ){
                                
                                /**
                                 *  Wait 2 sec then redirection on link
                                 *  -----------------------------------
                                 */
                                setTimeout(function(){ document.location.href = PHP_RESPONSE.redirect_link; }, 2000 );
                            }
                        },

                        error: function(jqXHR, textStatus, errorThrown) {
                          
                            console.log( jqXHR );

                            console.log( textStatus );

                            console.log( errorThrown );
                        },
                    });

                });
            }
        },

        /**
         *  SDWeddingDirectory - Couple Login Script
         *  --------------------------------
         */
        couple_login: function(){

            if( $('form#sdweddingdirectory-couple-login-form').length ){

                $('form#sdweddingdirectory-couple-login-form').on('submit', function(e){

                    /**
                     *  Wait for event
                     *  --------------
                     */
                    e.preventDefault();

                    /**
                     *  Variable
                     *  --------
                     */
                    var     _this           =   $( this ),

                            form_id         =   '#'     +   $( _this ).attr('id'),

                            button_id       =   '#'     +   $( _this ).find( 'button[type=submit]' ).attr( 'id' ),

                            wait_message    =   $( button_id ).attr( 'data-message-wait' );

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
                             *  Action and Security
                             *  -------------------
                             */
                            'action'                            :   'sdweddingdirectory_couple_login_form_action',

                            'security'                          :   $( form_id + ' #sdweddingdirectory_couple_login_security').val(),

                            /**
                             *  Args
                             *  ----
                             */
                            'sdweddingdirectory_couple_login_username'  :   $( form_id + ' #sdweddingdirectory_couple_login_username').val(),

                            'sdweddingdirectory_couple_login_password'  :   $( form_id + ' #sdweddingdirectory_couple_login_password').val(), 

                            /**
                             *  Redirection
                             *  -----------
                             */
                            'redirect_link'                    :   $( '#' + 'couple_login' + '_redirect_link' ).val(),
                        },

                        /**
                         *  Before AJAX
                         *  -----------
                         */
                        beforeSend: function(){

                            /**
                             *  Loader
                             *  ------
                             */
                            SDWeddingDirectory_Elements.button_loader_start( form_id );

                            /**
                             *  Disable Button
                             *  --------------
                             */
                            $( button_id ).addClass( 'disabled' );

                            /**
                             *  Have Wait Message ?
                             *  -------------------
                             */
                            if( _is_empty( wait_message ) ){

                                sdweddingdirectory_alert( {

                                    'notice'    :       2,

                                    'message'   :       wait_message

                                } );
                            }
                        },

                        /**
                         *  After AJAX
                         *  ----------
                         */
                        complete: function(){

                            SDWeddingDirectory_Elements.button_loader_end( form_id );

                            /**
                             *  Disable Button
                             *  --------------
                             */
                            $( button_id ).removeClass( 'disabled' );
                        },

                        /**
                         *  Success AJAX
                         *  ------------
                         */
                        success: function( PHP_RESPONSE ){

                            /**
                             *  Have Alert ?
                             *  ------------
                             */
                            sdweddingdirectory_alert( PHP_RESPONSE );

                            /**
                             *  Have Redirection ?
                             *  ------------------
                             */
                            if( PHP_RESPONSE.modal_close == true ){

                                /**
                                 *  Hide Any Modal
                                 *  --------------
                                 */
                                $( '#' + $( 'body .modal.show' ).attr( 'id' ) ).modal( 'hide' );
                            }


                            /**
                             *  Button String Change ?
                             *  ----------------------
                             */
                            if( _is_empty( PHP_RESPONSE.button_string ) ){

                                $( button_id ).html( PHP_RESPONSE.button_string  );
                            }

                            /**
                             *  Button Class Change ?
                             *  ---------------------
                             */
                            if( _is_empty( PHP_RESPONSE.button_class ) ){

                                $( button_id ).addClass( PHP_RESPONSE.button_class );
                            }

                            /**
                             *  Have Redirection ?
                             *  ------------------
                             */
                            if( PHP_RESPONSE.redirect == true ){

                                /**
                                 *  Wait 2 sec then redirection on link
                                 *  -----------------------------------
                                 */
                                setTimeout(function(){  document.location.href = PHP_RESPONSE.redirect_link;  }, 2000 );
                            }
                        },

                    });
                });
            }
        },

        /**
         *  Load the functions
         *  ------------------
         */
        init: function() {

            /**
             *  1. Registration Form
             *  --------------------
             */
            this.couple_register_form();

            /**
             *  2. Couple Login
             *  ---------------
             */
            this.couple_login();
        }
    };

    $(document).ready( function(){ SDWeddingDirectory_Couple_Registration.init(); });

})(jQuery);