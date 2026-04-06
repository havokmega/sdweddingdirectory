/**
 *  SDWeddingDirectory - Request Quote Script
 *  ---------------------------------
 */
(function($) {

    'use strict';

    var SDWeddingDirectory_Follow_Vendor = {

        /**
         *  1. Follow Vendor
         *  ----------------
         */
        follow_vendor: function(){

            $( document ).delegate( '.sdweddingdirectory-follow-vendor', 'click' , function(e){

                e.preventDefault();

                var id                  =  '#' + $( this ).attr( 'id' ),

                    unfollow_alert      =  $(id).attr( 'data-alert-unfollow' ),

                    follow_alert        =  $(id).attr( 'data-alert-follow' );

                $.ajax({

                    type: 'POST',

                    dataType: 'json',

                    url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                    data: {

                        /** 
                         *  1. Security + Action
                         *  --------------------
                         */
                        'action'                        : 'sdweddingdirectory_follow_vendor_action',

                        'security'                      : $(this).attr( 'data-security' ),

                        /**
                         *  2. Fields
                         *  ---------
                         */
                        'vendor_id'                     : $(this).attr( 'data-vendor-id' ),
                    },
                    success: function( PHP_RESPONSE ){

                        /**
                         *  Showing Alert Success / Fail
                         *  ----------------------------
                         */
                        sdweddingdirectory_alert( PHP_RESPONSE );

                        /**
                         *  Change String
                         *  -------------
                         */
                        $(id).addClass( 'sdweddingdirectory-unfollow-vendor btn-default' ).removeClass( 'sdweddingdirectory-follow-vendor btn-light' ).text( unfollow_alert );
                    },
                    beforeSend: function(){                    

                    },
                    error: function (xhr, ajaxOptions, thrownError) {

                        console.log( 'SDWeddingDirectory - Follow Vendor Script' );

                        console.log(xhr.status);

                        console.log(thrownError);

                        console.log(xhr.responseText);
                    },
                    complete: function( event, request, settings ){

                    }
                });

            });
        },

        /**
         *  2. Unfollow Vendor
         *  ------------------
         */
        unfollow_vendor: function(){

            $( document ).delegate( '.sdweddingdirectory-unfollow-vendor', 'click' , function(e){

                e.preventDefault();

                var id                  =  '#' + $( this ).attr( 'id' ),

                    unfollow_alert      =  $(id).attr( 'data-alert-unfollow' ),

                    follow_alert        =  $(id).attr( 'data-alert-follow' );

                $.ajax({

                    type: 'POST',

                    dataType: 'json',

                    url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                    data: {

                        /** 
                         *  1. Security + Action
                         *  --------------------
                         */
                        'action'                        : 'sdweddingdirectory_unfollow_vendor_action',

                        'security'                      : $(id).attr( 'data-security' ),

                        /**
                         *  2. Fields
                         *  ---------
                         */
                        'vendor_id'                     : $(id).attr( 'data-vendor-id' ),
                    },
                    success: function( PHP_RESPONSE ){

                        /**
                         *  Showing Alert Success / Fail
                         *  ----------------------------
                         */
                        sdweddingdirectory_alert( PHP_RESPONSE );

                        /**
                         *  Change String
                         *  -------------
                         */
                        $(id).removeClass( 'sdweddingdirectory-unfollow-vendor btn-default' ).addClass( 'sdweddingdirectory-follow-vendor btn-light' ).text( follow_alert );
                    },
                    beforeSend: function(){

                    },
                    error: function (xhr, ajaxOptions, thrownError) {

                        console.log( 'SDWeddingDirectory - Unfollow Vendor Script' );

                        console.log(xhr.status);

                        console.log(thrownError);

                        console.log(xhr.responseText);
                    },
                    complete: function( event, request, settings ){

                    }
                });

            });
        },

        /**
         *  Load Request Quote Script
         *  -------------------------
         */
        init: function(){

            /**
             *  1. Follow Vendor
             *  ----------------
             */
            this.follow_vendor();

            /**
             *  2. Unfollow Vendor
             *  ------------------
             */
            this.unfollow_vendor();
        }
    }

    /**
     *  Set Global Variable
     *  -------------------
     */
    window.SDWeddingDirectory_Follow_Vendor = SDWeddingDirectory_Follow_Vendor;

    /**
     *  Document Ready to Load Obj
     *  --------------------------
     */
    $( document ).ready( function(){ SDWeddingDirectory_Follow_Vendor.init(); } );

})(jQuery);