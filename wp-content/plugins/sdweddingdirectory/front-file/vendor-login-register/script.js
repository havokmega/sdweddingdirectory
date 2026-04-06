/**
 *  SDWeddingDirectory Vendor Register Script Here
 *  --------------------------------------
 */

(function($) {

    "use strict";

    var SDWeddingDirectory_Vendor_Registration = {

        /**
         *  1. Vendor Registration Form
         *  ---------------------------
         */
        vendor_register_form: function(){

            if( $( 'form#sdweddingdirectory_vendor_registration_form' ).length ){

                $( 'form#sdweddingdirectory_vendor_registration_form' ).on( 'submit', function( e ){

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
                    $.ajax( {

                        type: 'POST',

                        dataType: 'json',

                        url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data: {

                            /**
                             *  Action + Security
                             *  -----------------
                             */
                            'action'                    :   'sdweddingdirectory_vendor_register_form_action',

                            'security'                  :   $( form_id + ' #sdweddingdirectory_vendor_registration_form_security').val(),

                            /**
                             *  Registration Fields here
                             *  ------------------------
                             */
                            'first_name'                :   $( form_id + ' #sdweddingdirectory_vendor_register_first_name').val(),

                            'last_name'                 :   $( form_id + ' #sdweddingdirectory_vendor_register_last_name').val(),

                            'username'                  :   $( form_id + ' #sdweddingdirectory_vendor_register_username').val(),

                            'email'                     :   $( form_id + ' #sdweddingdirectory_vendor_register_email').val(),

                            /**
                             *  Password information
                             *  --------------------
                             */
                            'password'                  :   $( form_id + ' #sdweddingdirectory_vendor_register_password').val(),

                            /**
                             *  Company Information
                             *  -------------------
                             */
                            'company_name'              :   $( form_id + ' #sdweddingdirectory_vendor_register_company_name').val(),

                            'company_website'           :   $( form_id + ' #sdweddingdirectory_vendor_register_company_website').val(),

                            'contact_number'            :   $( form_id + ' #sdweddingdirectory_vendor_register_contact_number').val(),

                            'account_type'              :   $( form_id + ' input[name=sdweddingdirectory_vendor_register_account_type]:checked' ).val() || 'vendor',

                            /**
                             *  Vendor Category
                             *  ---------------
                             */
                            'vendor_category'           :   ( ( $( form_id + ' input[name=sdweddingdirectory_vendor_register_account_type]:checked' ).val() || 'vendor' ) === 'venue' )

                                                            ? '0'

                                                            : $( form_id + ' #sdweddingdirectory_vendor_register_category').val(),

                            /**
                             *  Redirection
                             *  -----------
                             */
                            'redirect_link'             :   $( '#' + 'vendor_register' + '_redirect_link' ).val(),
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
                             *  Alert
                             *  -----
                             */
                            sdweddingdirectory_alert( PHP_RESPONSE );

                            if( PHP_RESPONSE.claim_required === true && PHP_RESPONSE.claim ){

                                SDWeddingDirectory_Vendor_Registration.open_claim_modal( PHP_RESPONSE.claim );

                                return;
                            }

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
                             *  Have Any Redirection ?
                             *  ----------------------
                             */
                            if( PHP_RESPONSE.redirect == true ){
                                
                                setTimeout(function(){ document.location.href = PHP_RESPONSE.redirect_link; }, 3000 );
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                          
                            console.log( jqXHR );

                            console.log( textStatus );

                            console.log( errorThrown );
                        },
                    });

                    e.preventDefault();
                });
            }
        },

        /**
         *  Account Type Toggle
         *  -------------------
         */
        account_type_toggle: function(){

            if( ! $('form#sdweddingdirectory_vendor_registration_form').length ){
                return;
            }

            var toggleCategory = function(){

                var accountType = $('input[name=sdweddingdirectory_vendor_register_account_type]:checked').val() || 'vendor';

                var isVendor = accountType === 'vendor';

                $('#sdwd_vendor_category_wrap').toggle( isVendor );

                $('#sdweddingdirectory_vendor_register_category').prop( 'disabled', ! isVendor );
            };

            $(document).on( 'change', 'input[name=sdweddingdirectory_vendor_register_account_type]', toggleCategory );

            toggleCategory();
        },

        /**
         *  Open Claim Modal
         *  ----------------
         */
        open_claim_modal: function( claim_data ){

            if( ! $('#sdwd_profile_claim_modal').length ){
                return;
            }

            if( $('body .modal.show').length ){
                $('body .modal.show').not('#sdwd_profile_claim_modal').modal('hide');
            }

            $('#sdwd_claimant_name').val( claim_data.claimant_name || '' );
            $('#sdwd_claimant_phone').val( claim_data.claimant_phone || '' );
            $('#sdwd_claimant_email').val( claim_data.claimant_email || '' );
            $('#sdwd_target_post_id').val( claim_data.target_post_id || '' );
            $('#sdwd_target_post_type').val( claim_data.target_post_type || '' );
            $('#sdwd_target_slug').val( claim_data.target_slug || '' );

            setTimeout(function(){
                $('#sdwd_profile_claim_modal').modal( 'show' );
            }, 200);
        },

        /**
         *  Claim Submit
         *  ------------
         */
        submit_claim_request: function(){

            if( ! $('#sdwd_profile_claim_form').length ){
                return;
            }

            $(document).on( 'submit', '#sdwd_profile_claim_form', function( e ){

                e.preventDefault();

                $.ajax({

                    type: 'POST',

                    dataType: 'json',

                    url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                    data: {

                        'action'            : 'sdwd_profile_claim_submit_action',
                        'security'          : $('#sdwd_profile_claim_form #sdwd_profile_claim_submit_nonce').val(),
                        'claimant_name'     : $('#sdwd_claimant_name').val(),
                        'claimant_phone'    : $('#sdwd_claimant_phone').val(),
                        'claimant_email'    : $('#sdwd_claimant_email').val(),
                        'target_post_id'    : $('#sdwd_target_post_id').val(),
                        'target_post_type'  : $('#sdwd_target_post_type').val(),
                        'target_slug'       : $('#sdwd_target_slug').val()
                    },

                    success: function( PHP_RESPONSE ){

                        sdweddingdirectory_alert( PHP_RESPONSE );

                        if( PHP_RESPONSE.notice == 1 ){

                            $('#sdwd_profile_claim_modal').modal( 'hide' );

                            setTimeout(function(){

                                var redirect_link = $('#vendor_register_redirect_link').val();

                                if( redirect_link ){
                                    window.location.href = redirect_link;
                                }else{
                                    window.location.reload();
                                }

                            }, 1200 );
                        }
                    }
                });
            } );
        },

        /**
         *  SDWeddingDirectory - Vendor Login Script
         *  --------------------------------
         */
        vendor_login: function(){

            if( $('form#sdweddingdirectory-vendor-login-form').length ){

                $('form#sdweddingdirectory-vendor-login-form').on('submit', function(e){

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
                     *  AJAX Start
                     *  ----------
                     */
                    $.ajax({

                        type: 'POST',

                        dataType: 'json',

                        url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data: { 

                            /**
                             *  Action & Security
                             *  -----------------
                             */
                            'action'                            : 'sdweddingdirectory_vendor_login_action',

                            'security'                          : $( form_id + ' #sdweddingdirectory_vendor_login_security').val(),

                            /**
                             *  Fields
                             *  ------
                             */
                            'sdweddingdirectory_vendor_login_username'  : $( form_id + ' #sdweddingdirectory_vendor_login_username').val(),

                            'sdweddingdirectory_vendor_login_password'  : $( form_id + ' #sdweddingdirectory_vendor_login_password').val(),

                            'vendor_login_redirection'          : $( form_id + ' #vendor_login_redirection').val(),

                            /**
                             *  Redirection
                             *  -----------
                             */
                            'redirect_link'                     :   $( '#' + 'vendor_login' + '_redirect_link' ).val(),
                        },

                        beforeSend: function(){

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

                            SDWeddingDirectory_Elements.button_loader_end( form_id );

                            /**
                             *  Disable Button
                             *  --------------
                             */
                            $( button_id ).removeClass( 'disabled' );
                        },

                        success: function( PHP_RESPONSE ){

                            /**
                             *  Have Any Alert ?
                             *  ----------------
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
                                setTimeout(function(){  document.location.href = PHP_RESPONSE.redirect_link; }, 2000 );
                            }
                        }

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
            this.vendor_register_form();

            /**
             *  2. Login Form
             *  -------------
             */
            this.vendor_login();

            /**
             *  3. Claim Request Form
             *  ---------------------
             */
            this.submit_claim_request();

            /**
             *  4. Registration Account Type Toggle
             *  -----------------------------------
             */
            this.account_type_toggle();
        }
    };

    $(document).ready( function(){ SDWeddingDirectory_Vendor_Registration.init(); });

})(jQuery);
