/**
 *  Vendor Profile
 *  --------------
 */
(function($) {

    "use strict";

    var SDWeddingDirectory_Vendor_Profile = {

        get_input_data: function( name ){

            var i   =   "input[name="+name+"]",

                j   =   i+':checked',

                k   =   {};

            /**
             *  Have length ?
             *  -------------
             */
            if( $( i ).length ){

                $( j ).map(function( i ) {

                    k[ $(this).attr( 'data-value' ) ] = $(this).val()
                });

                return k;
            }

            return k;
        },

        /**
         *  1. My Profile
         *  -------------
         */
        profile_update: function(){

            if( $('#sdweddingdirectory_vendor_profile_update').length ){

                $(document).on('submit', '#sdweddingdirectory_vendor_profile_update', function (e){

                    var form_id =  '#' + $(this).attr( 'id' );

                    $('html, body').animate({ scrollTop: 0 }, 'slow');

                    $.ajax({

                        type        : 'POST',
                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                        dataType    : 'json',
                        data        : {

                            /**
                             *  1. Action + Security
                             *  --------------------
                             */
                            'action'            : 'sdweddingdirectory_vendor_profile_action',
                            'security'          : $( form_id + ' #vendor_profile_update').val(),

                            /**
                             *  2. Vendor Profile Fields
                             *  ------------------------
                             */
                            'first_name'        : $( form_id + ' #first_name').val(),
                            'last_name'         : $( form_id + ' #last_name').val(),
                            'user_contact'      : $( form_id + ' #user_contact').val(),
                            'user_address'      : $( form_id + ' #user_address').val(),
                            'sdweddingdirectory_vendor_slug' : $( form_id + ' #sdweddingdirectory_vendor_slug').val(),
                        },

                        success: function (data) {

                            sdweddingdirectory_alert( data );

                            if( data.claim_required === true && data.claim ){

                                SDWeddingDirectory_Vendor_Profile.open_claim_modal( data.claim );
                            }
                        }
                    });

                    e.preventDefault();

                });
            }
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
         *  Submit Claim
         *  ------------
         */
        submit_claim_request: function(){

            if( ! $('#sdwd_profile_claim_form').length ){
                return;
            }

            $(document).on( 'submit', '#sdwd_profile_claim_form', function( e ){

                e.preventDefault();

                $.ajax({

                    type        : 'POST',
                    url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                    dataType    : 'json',
                    data        : {
                        'action'            : 'sdwd_profile_claim_submit_action',
                        'security'          : $('#sdwd_profile_claim_form #sdwd_profile_claim_submit_nonce').val(),
                        'claimant_name'     : $('#sdwd_claimant_name').val(),
                        'claimant_phone'    : $('#sdwd_claimant_phone').val(),
                        'claimant_email'    : $('#sdwd_claimant_email').val(),
                        'target_post_id'    : $('#sdwd_target_post_id').val(),
                        'target_post_type'  : $('#sdwd_target_post_type').val(),
                        'target_slug'       : $('#sdwd_target_slug').val()
                    },
                    success: function ( PHP_RESPONSE ) {

                        sdweddingdirectory_alert( PHP_RESPONSE );

                        if( PHP_RESPONSE.notice == 1 ){

                            $('#sdwd_profile_claim_modal').modal( 'hide' );
                        }
                    }
                });
            } );
        },

        /**
         *  2. Vendor Business Profile
         *  --------------------------
         */
        business_profile: function(){

            if( $( 'form#sdweddingdirectory_vendor_business_profile' ).length ){

                $(document).on('submit', 'form#sdweddingdirectory_vendor_business_profile', function (e) {

                    var form_id =  '#' + $(this).attr( 'id' );

                    $('html, body').animate({ scrollTop: 0 }, 'slow');

                    $.ajax({

                        type        : 'POST',
                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                        dataType    : 'json',
                        data        : {

                            /**
                             *  1. Action + Security
                             *  --------------------
                             */
                            'action'                     : 'sdweddingdirectory_vendor_business_profile_action',

                            'security'                   : $( form_id + ' #vendor_business_profile').val(),

                            /**
                             *  2. Vendor Business Profile Fields
                             *  ---------------------------------
                             */
                            'company_name'               : $( form_id + ' #company_name').val(),

                            'company_website'            : $( form_id + ' #company_website').val(),

                            'company_email'              : $( form_id + ' #company_email' ).length 

                                                            ?   $( form_id + ' #company_email' ).val()

                                                            :   '',

                            'company_contact'            : $( form_id + ' #company_contact' ).val(),

                            'working_hours'              : {
                                'saturday_enable'   : $( '#saturday_enable' ).is(':checked')   ? 'on' : 'off',
                                'sunday_enable'     : $( '#sunday_enable' ).is(':checked')     ? 'on' : 'off',
                                'monday_enable'     : $( '#monday_enable' ).is(':checked')     ? 'on' : 'off',
                                'tuesday_enable'    : $( '#tuesday_enable' ).is(':checked')    ? 'on' : 'off',
                                'wednesday_enable'  : $( '#wednesday_enable' ).is(':checked')  ? 'on' : 'off',
                                'thursday_enable'   : $( '#thursday_enable' ).is(':checked')   ? 'on' : 'off',
                                'friday_enable'     : $( '#friday_enable' ).is(':checked')     ? 'on' : 'off',

                                'saturday_start'    : $( '#saturday_start' ).val(),
                                'sunday_start'      : $( '#sunday_start' ).val(),
                                'monday_start'      : $( '#monday_start' ).val(),
                                'tuesday_start'     : $( '#tuesday_start' ).val(),
                                'wednesday_start'   : $( '#wednesday_start' ).val(),
                                'thursday_start'    : $( '#thursday_start' ).val(),
                                'friday_start'      : $( '#friday_start' ).val(),

                                'saturday_close'    : $( '#saturday_close' ).val(),
                                'sunday_close'      : $( '#sunday_close' ).val(),
                                'monday_close'      : $( '#monday_close' ).val(),
                                'tuesday_close'     : $( '#tuesday_close' ).val(),
                                'wednesday_close'   : $( '#wednesday_close' ).val(),
                                'thursday_close'    : $( '#thursday_close' ).val(),
                                'friday_close'      : $( '#friday_close' ).val(),

                                'saturday_open'     : $( '#saturday_open' ).is(':checked')     ? 'on' : 'off',
                                'sunday_open'       : $( '#sunday_open' ).is(':checked')       ? 'on' : 'off',
                                'monday_open'       : $( '#monday_open' ).is(':checked')       ? 'on' : 'off',
                                'tuesday_open'      : $( '#tuesday_open' ).is(':checked')      ? 'on' : 'off',
                                'wednesday_open'    : $( '#wednesday_open' ).is(':checked')    ? 'on' : 'off',
                                'thursday_open'     : $( '#thursday_open' ).is(':checked')     ? 'on' : 'off',
                                'friday_open'       : $( '#friday_open' ).is(':checked')       ? 'on' : 'off',
                            },

                            'post_content'               : $( form_id + ' #post_content' ).summernote('code'),
                        },

                        success: function (PHP_RESPONSE) {

                            sdweddingdirectory_alert( PHP_RESPONSE );
                        }
                    });

                    e.preventDefault();

                });
            }
        },


        /**
         *  Vendor Filters
         *  --------------
         */
        vendor_filters: function(){

            if( $( 'form#sdweddingdirectory_vendor_filter_profile' ).length ){

                $(document).on('submit', 'form#sdweddingdirectory_vendor_filter_profile', function (e) {

                    var form_id =  '#' + $(this).attr( 'id' );

                    $('html, body').animate({ scrollTop: 0 }, 'slow');

                    $.ajax({

                        type        : 'POST',
                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                        dataType    : 'json',
                        data        : {

                            /**
                             *  1. Action + Security
                             *  --------------------
                             */
                            'action'                     : 'sdweddingdirectory_vendor_filter_profile_action',

                            'security'                   : $( form_id + ' #vendor_filter_profile').val(),

                            /**
                             *  2. Vendor Filter Fields
                             *  -----------------------
                             */
                            'vendor_pricing'             : SDWeddingDirectory_Vendor_Profile.get_input_data( 'vendor_pricing' ),
                            'vendor_services'            : SDWeddingDirectory_Vendor_Profile.get_input_data( 'vendor_services' ),
                            'vendor_style'               : SDWeddingDirectory_Vendor_Profile.get_input_data( 'vendor_style' ),
                            'vendor_specialties'         : SDWeddingDirectory_Vendor_Profile.get_input_data( 'vendor_specialties' ),
                        },

                        success: function (PHP_RESPONSE) {

                            sdweddingdirectory_alert( PHP_RESPONSE );
                        }
                    });

                    e.preventDefault();

                });
            }
        },

        /**
         *  3. Setup Password
         *  -----------------
         */
        setup_password: function(){

            if( $('#vendor_user_password_change').length ){

                $(document).on( 'submit', '#vendor_user_password_change', function (e) {

                    var form_id =  '#' + $(this).attr( 'id' );

                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                
                    $.ajax({
                        type: 'POST',
                        url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                        dataType    : 'json',
                        data: {

                            /**
                             *  Action + Secruity
                             *  -----------------
                             */
                            'action'        : 'sdweddingdirectory_vendor_password_action',
                            'security'      : $( form_id + ' #change_password_security').val(),

                            /**
                             *  Fields
                             *  ------
                             */
                            'old_pwd'       : $( form_id + ' #old_pwd').val(),
                            'new_pwd'       : $( form_id + ' #new_pwd').val(),
                            'confirm_pwd'   : $( form_id + ' #confirm_pwd').val(),
                        },
                        success: function (data) {

                            sdweddingdirectory_alert( data );
                        },
                        error: function (errorThrown) {

                            toastr.error( data.message ); // $('#vendor_user_password_change .status').show().html(data.message);
                        }
                    });

                    e.preventDefault();
                });
            }
        },

        vendor_social_profile: function(){

            if( $('#sdweddingdirectory_vendor_social_notification').length ){

                $(document).on('submit', '#sdweddingdirectory_vendor_social_notification', function(e) {

                    var form_id =  '#' + $(this).attr( 'id' );

                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                
                    $.ajax({

                        type        : 'POST',
                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                        dataType    : 'json',
                        data        : {

                            /**
                             *  1. Action + Security
                             *  --------------------
                             */
                            'action'            : 'sdweddingdirectory_vendor_social_action',

                            'security'          : $( form_id + ' #social_media_update').val(),

                            /**
                             *  2. Social Media Fields
                             *  ----------------------
                             */
                            'social_profile'    : SDWeddingDirectory_Vendor_Profile.social_profile()
                        },
                        success: function ( PHP_RESPONSE ) {

                            sdweddingdirectory_alert( PHP_RESPONSE );
                        }
                    });

                    e.preventDefault();
                });
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
         *  7. Upload Photos
         *  ----------------
         */
        upload_photos: function(){

            if( $( '#sdweddingdirectory_vendor_upload_photos' ).length ){

                $(document).on('submit', '#sdweddingdirectory_vendor_upload_photos', function (e){

                    var form_id = '#' + $(this).attr( 'id' );

                    $('html, body').animate({ scrollTop: 0 }, 'slow');

                    var gallery_val = $( form_id + ' .vendor_gallery_image' ).val();

                    // Enforce 20-photo limit on client side.
                    if( gallery_val ){
                        var ids = gallery_val.split(',').filter(function(v){ return v !== ''; });
                        if( ids.length > 20 ){
                            sdweddingdirectory_alert({
                                'notice'  : 2,
                                'message' : 'Maximum 20 photos allowed. Please remove some photos first.'
                            });
                            return false;
                        }
                    }

                    $.ajax({

                        type        : 'POST',
                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                        dataType    : 'json',
                        data        : {

                            'action'          : 'sdweddingdirectory_vendor_upload_photos_action',
                            'security'        : $( form_id + ' #vendor_upload_photos_security').val(),
                            'vendor_gallery'  : gallery_val,
                        },

                        success: function ( PHP_RESPONSE ) {

                            sdweddingdirectory_alert( PHP_RESPONSE );

                            if( PHP_RESPONSE.notice == 1 ){
                                // Update counter.
                                var count = gallery_val ? gallery_val.split(',').filter(function(v){ return v !== ''; }).length : 0;
                                $( '.sdwd-photo-count-num' ).text( count );
                            }
                        }
                    });

                    e.preventDefault();

                });
            }
        },

        /**
         *  Load Vendor Profile Page Script
         *  -------------------------------
         */
        init: function() {

            /**
             *  1. Vendor Profile
             *  -----------------
             */
            this.profile_update();

            /**
             *  2. Vendor Business Profile
             *  --------------------------
             */
            this.business_profile();

            /**
             *  3. Vendor Filters
             *  -----------------
             */
            this.vendor_filters();

            /**
             *  4. Setup Password
             *  -----------------
             */
            this.setup_password();

            /**
             *  5. Vendor Social Media
             *  ----------------------
             */
            this.vendor_social_profile();

            /**
             *  6. Claim Request
             *  ----------------
             */
            this.submit_claim_request();

            /**
             *  7. Upload Photos
             *  ----------------
             */
            this.upload_photos();

        },
    };

    $(document).ready( function() { SDWeddingDirectory_Vendor_Profile.init(); });

})(jQuery);
