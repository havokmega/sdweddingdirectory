/**
 *  Couple Profile Update
 *  ---------------------
 */
(function($) {

  'use strict';

    var SDWeddingDirectory_Couple_Profile = {

        /**
         *  1. Couple Profile Update
         *  ------------------------
         */
        my_profile: function(){

            /**
             *  My Profile - Form
             *  -----------------
             */
            if( $( 'form#sdweddingdirectory_couple_profile_update' ).length ){

                $( 'form#sdweddingdirectory_couple_profile_update' ).on( 'submit', function( e ){

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

                            button_id       =   '#'     +   $( _this ).find( 'button.sdweddingdirectory-submit[type=submit]' ).attr( 'id' );

                            $('html, body').animate({ scrollTop: 0 }, 'slow' );

                    /**
                     *  Ajax Start
                     *  ----------
                     */
                    $.ajax( {

                        type        :   'POST',

                        url         :   SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        dataType    :   'json',

                        data        :   {

                            /**
                             *  Action + Security
                             *  -----------------
                             */
                            'action'            : 'sdweddingdirectory_couple_profile_action',

                            'security'          : $( form_id + ' #profile_update').val(),

                            /**
                             *  Form Fields
                             *  -----------
                             */
                            'first_name'        : $( form_id + ' #first_name').val(),

                            'last_name'         : $( form_id + ' #last_name').val(),

                            'user_email'        : $( form_id + ' #user_email').val(),

                            'user_contact'      : $( form_id + ' #user_contact').val(),

                            'user_address'      : $( form_id + ' #user_address').val(),

                            'post_content'      : $( form_id + ' #post_content' ).summernote('code')
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

                        success: function (data) {

                            toastr.success( data.message );
                        }

                    } );
                });
            }
        },

        /**
         *  2. Wedding Information
         *  -------------------
         */
        wedding_information: function(){

            /**
             *  Wedding Information Form
             *  ------------------------
             */
            if( $( 'form#sdweddingdirectory_couple_wedding_info_account' ).length ){

                $( 'form#sdweddingdirectory_couple_wedding_info_account' ).on( 'submit', function( e ){

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

                            button_id       =   '#'     +   $( _this ).find( 'button.sdweddingdirectory-submit[type=submit]' ).attr( 'id' );

                            $('html, body').animate({ scrollTop: 0 }, 'slow' );

                        /**
                         *  Ajax Start
                         *  ==========
                         */
                        $.ajax({

                            type        :   'POST',

                            url         :   SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                            dataType    :   'json',

                            data:       {

                                /**
                                 *  Action + Security
                                 *  =================
                                 */
                                'action'                : 'sdweddingdirectory_couple_wedding_information',

                                'security'              : $( form_id + ' #wedding_information').val(),

                                /**
                                 *  Form Fields
                                 *  -----------
                                 */
                                'wedding_date'          : $( form_id + ' #wedding_date' ).val(),

                                'wedding_address'       : $( form_id + ' #wedding_address' ).val(),

                                'bride_first_name'      : $( form_id + ' #bride_first_name' ).val(),

                                'bride_last_name'       : $( form_id + ' #bride_last_name' ).val(),

                                'groom_first_name'      : $( form_id + ' #groom_first_name' ).val(),

                                'groom_last_name'       : $( form_id + ' #groom_last_name' ).val(),
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

                            success: function (data) {
                                
                                toastr.success( data.message );
                            }

                        } );
                } );
            }
        },

        /**
         *  4. Password Form
         *  ----------------
         */
        password_update_form: function(){

            /**
             *  User Password
             *  -------------
             */
            if( $( '#user_password_change' ).length ){

                $( '#user_password_change' ).on( 'submit', function( e ){

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

                            button_id       =   '#'     +   $( _this ).find( 'button.sdweddingdirectory-submit[type=submit]' ).attr( 'id' );

                            $('html, body').animate({ scrollTop: 0 }, 'slow' );

                    /**
                     *  Ajax Start
                     *  ==========
                     */
                    $.ajax( {

                        type        :   'POST',

                        url         :   SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        dataType    :   'json',

                        data        :   {

                            /**
                             *  Action + Security
                             *  =================
                             */
                            'action'        :   'sdweddingdirectory_couple_password_change',

                            'security'      :   $( form_id + ' #change_password_security').val(),

                            /**
                             *  Form Fields
                             *  -----------
                             */
                            'old_pwd'       :   $( form_id + ' #old_pwd').val(),

                            'new_pwd'       :   $( form_id + ' #new_pwd').val(),

                            'confirm_pwd'   :   $( form_id + ' #confirm_pwd').val(),
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

                        success: function ( PHP_RESPONSE ) {

                            sdweddingdirectory_alert( PHP_RESPONSE );

                            if( PHP_RESPONSE.redirect == true ){

                                document.location.href = PHP_RESPONSE.redirect_link;
                            }
                        },
                        error: function (errorThrown) {

                            toastr.error( data.message );
                        }

                    } );

                } );
            }
        },

        /**
         *  5. Social Media
         *  ---------------
         */
        social_media: function(){

            /**
             *  Social Media Form
             *  -----------------
             */
            if( $('#sdweddingdirectory_couple_social_notification').length ){

                $('#sdweddingdirectory_couple_social_notification').on('submit', function (e) {

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

                            button_id       =   '#'     +   $( _this ).find( 'button.sdweddingdirectory-submit[type=submit]' ).attr( 'id' );

                            $('html, body').animate({ scrollTop: 0 }, 'slow' );

                    /**
                     *  Ajax Start
                     *  ----------
                     */
                    $.ajax({

                        type            :   'POST',

                        url             :   SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        dataType        :   'json',

                        data            :   {

                            /**
                             *  Action + Security
                             *  =================
                             */
                            'action'            :   'sdweddingdirectory_couple_social_profile_action',

                            'security'          :   $( form_id + ' #social_media_update').val(),

                            /**
                             *  Form Fields
                             *  -----------
                             */
                            'social_profile'    :   SDWeddingDirectory_Couple_Profile.social_profile()
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

                        success: function ( PHP_RESPONSE ) {

                            sdweddingdirectory_alert( PHP_RESPONSE );
                        }

                    } );
                } );
            }
        },

        /**
         *  Get Social Profiel
         *  ------------------
         */
        social_profile: function(){

            /**
             *  Get FAQ's
             *  ---------
             */
            var social_profile    =   new Array();

            $( '#social_profile .collpase_section' ).map(function( index, value ) {

                var platform      =   $(this).find( '.platform' ).val(),

                    link          =   $(this).find( '.link' ).val();

                /**
                 *  Make sure all fields are filled
                 *  -------------------------------
                 */
                if( _is_empty( platform ) && _is_empty( link ) ){

                    social_profile.push( {

                        'title'     : platform,

                        'platform'  : platform,

                        'link'      : link

                    } );
                }

            } );

            return  social_profile;
        },

        /**
         *  Initlize Script
         *  ---------------
         */
        init : function(){
            
            /**
             *  1. My Profile
             *  -------------
             */
            this.my_profile();

            /**
             *  2. Wedding Information
             *  ----------------------
             */
            this.wedding_information();

            /**
             *  4. Password Form
             *  ----------------
             */
            this.password_update_form();

            /**
             *  5. Social Media
             *  ---------------
             */
            this.social_media();
        },
    }

    /**
     *  Document Ready
     *  --------------
     */
    $( document ).ready( function(){    SDWeddingDirectory_Couple_Profile.init();    } )

})(jQuery);