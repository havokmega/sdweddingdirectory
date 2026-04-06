(function($) {

    "use strict";

    var SDWeddingDirectory_Couple_Dashboard_Wishlist = {

        /**
         *  Attach nonce to wishlist AJAX requests when missing
         *  ---------------------------------------------------
         */
        attach_ajax_security: function(){

            var _wishlist_actions = [
                'sdweddingdirectory_update_notes',
                'sdweddingdirectory_remove_wishlist',
                'sdweddingdirectory_add_wishlist',
                'sdweddingdirectory_update_estimate_price',
                'sdweddingdirectory_update_rating',
                'sdweddingdirectory_add_hired',
                'sdweddingdirectory_remove_hired',
                'sdweddingdirectory_wishlist_hire_vendor'
            ];

            $.ajaxPrefilter( function( options, originalOptions ){

                if( ! originalOptions || typeof originalOptions.data !== 'object' || ! originalOptions.data ){

                    return;
                }

                var _data = originalOptions.data;

                if( typeof _data.action !== 'string' ){

                    return;
                }

                if( $.inArray( _data.action, _wishlist_actions ) === -1 || _data.security ){

                    return;
                }

                if( SDWEDDINGDIRECTORY_AJAX_OBJ && SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_wishlist_security ){

                    _data.security = SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_wishlist_security;
                    options.data = _data;
                }
            } );
        },

        /**
         *  1. Update Notes
         *  ---------------
         */
        update_wishlist_notes: function(){

            if( $( '.wishlist-note' ).length ){

                $( '.wishlist-note' ).on( 'change', function( e ){

                    e.preventDefault();

                    var unique_id   =   $(this).closest( '.wedding-venue' ).attr( 'data-wishlist-id' );

                    $.ajax({

                        type: 'POST',

                        dataType: 'json',

                        url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data: { 

                            'action'            :   'sdweddingdirectory_update_notes',

                            'unique_id'         :  unique_id,

                            'wishlist_note'     :  $(this).val(),

                        },
                        success: function(data){

                            sdweddingdirectory_alert( data );
                        },
                        beforeSend: function(){

                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Wishlist' );
                            console.log(xhr.status);
                            console.log(thrownError);
                            console.log(xhr.responseText);
                        },
                        complete: function( event, request, settings ){

                        }
                    });

                } );
            }
        },

        /**
         *  2. Update Price
         *  ---------------
         */
        update_wishlist_price: function(){

            if( $( '.wishlist-estimate-price' ).length ){

                $( '.wishlist-estimate-price' ).on( 'change', function( e ){

                    e.preventDefault();

                    var unique_id   =   $(this).closest( '.wedding-venue' ).attr( 'data-wishlist-id' );

                    $.ajax({

                        type: 'POST',

                        dataType: 'json',

                        url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data: { 

                            'action'                    :  'sdweddingdirectory_update_estimate_price',

                            'unique_id'                 :  unique_id,

                            'wishlist_estimate_price'   :  $(this).val(),

                        },
                        success: function(data){

                            sdweddingdirectory_alert( data );
                        },
                        beforeSend: function(){

                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Wishlist' );
                            console.log(xhr.status);
                            console.log(thrownError);
                            console.log(xhr.responseText);
                        },
                        complete: function( event, request, settings ){

                        }
                    });

                } );
            }
        },

        /**
         *  3. Hire Vendor : Select Option
         *  ------------------------------
         */
        hire_vendor_option: function(){

            if( $( '.couple-hire-vendor' ).length ){

                $( '.couple-hire-vendor' ).on( 'change', function( e ){

                    e.preventDefault();

                    var unique_id   =   $(this).closest( '.wedding-venue' ).attr( 'data-wishlist-id' );

                    $.ajax({

                        type: 'POST',

                        dataType: 'json',

                        url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data: {

                            'action'                :  'sdweddingdirectory_wishlist_hire_vendor',

                            'unique_id'             :  unique_id,

                            'wishlist_hire_vendor'  :  $(this).val(),
                        },

                        success: function( data ){

                            sdweddingdirectory_alert( data );
                        },

                        beforeSend: function(){


                        },

                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Hire_Vendor' );
                            console.log(xhr.status);
                            console.log(thrownError);
                            console.log(xhr.responseText);
                        },

                        complete: function( event, request, settings ){

                        }
                    });

                } );
            }
        },

        /**
         *  4. Update Wishlist Vendor Rating
         *  --------------------------------
         */
        update_wishlist_rating: function(){

            if( $( '.sdweddingdirectory_wishlist_review' ).length ){

                $( '.sdweddingdirectory_wishlist_review' ).on( 'click change', function( e ){

                    e.preventDefault();

                    var unique_id   =   $(this).closest( '.wedding-venue' ).attr( 'data-wishlist-id' );

                    $.ajax({

                        type: 'POST',

                        dataType: 'json',

                        url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data: { 

                            'action'            :  'sdweddingdirectory_update_rating',

                            'unique_id'         :  unique_id,

                            'wishlist_rating'   :  $(this).attr( 'data-review' )
                        },
                        success: function(data){

                            sdweddingdirectory_alert( data );
                        },
                        beforeSend: function(){

                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Wishlist_Rating' );
                            console.log(xhr.status);
                            console.log(thrownError);
                            console.log(xhr.responseText);
                        },
                        complete: function( event, request, settings ){

                        }
                    });

                } );
            }
        },

        /**
         *  Couple Wishlist Page : Rating
         *  -----------------------------
         */
        couple_wishlist_page_rating: function(){

            if( $('.sdweddingdirectory_wishlist_review').length ){

                $('.sdweddingdirectory_wishlist_review').map(function( index, value ){

                    var review = $(this).attr( 'data-review' );

                    $( $(this) ).rateYo({

                        rating: review,

                        starWidth: "16px",

                        fullStar: true,

                        normalFill: '#dadada',

                        ratedFill: 'var( --sdweddingdirectory-color-cyan, #00aeaf )',

                        /**
                         *  Article : https://rateyo.fundoocode.ninja/#hacks
                         *  ------------------------------------------------
                         */
                        starSvg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 9.229c.234-1.12 1.547-6.229 5.382-6.229 2.22 0 4.618 1.551 4.618 5.003 0 3.907-3.627 8.47-10 12.629-6.373-4.159-10-8.722-10-12.629 0-3.484 2.369-5.005 4.577-5.005 3.923 0 5.145 5.126 5.423 6.231zm-12-1.226c0 4.068 3.06 9.481 12 14.997 8.94-5.516 12-10.929 12-14.997 0-7.962-9.648-9.028-12-3.737-2.338-5.262-12-4.27-12 3.737z"/></svg>',

                        spacing: "2px",

                        /**
                         *  On Change Script
                         *  ----------------
                         */
                        onChange: function (rating, rateYoInstance) {

                            $(this).attr( 'data-review', rating );

                            $(this).parent().parent().find( 'label' ).text( SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_wishlist[rating] );
                        }
                    });
                });
            }
        },

        /**
         *  Edit Icon To Active Tab ID
         *  --------------------------
         */
        show_tab: function() {

            if( $( '._show_tab_' ).length ){

                $( '._show_tab_' ).on( 'click', function( e ){

                    e.preventDefault();

                    var _tab    =   $(this).attr( 'data-show-tab' );

                    /**
                     *  Show Tab
                     *  --------
                     */
                    if( _is_empty( _tab ) ){

                        $( _tab ).tab( 'show' );    
                    }

                } );
            }
        },

        /**
         *  Couple Dashboard Wishlist Script
         *  --------------------------------
         */
        init: function() {

            /**
             *  Ensure nonce is present for wishlist AJAX requests
             *  --------------------------------------------------
             */
            this.attach_ajax_security();

            /**
             *  1. Update Notes
             *  ---------------
             */
            this.update_wishlist_notes();

            /**
             *  2. Update Price
             *  ---------------
             */
            this.update_wishlist_price();

            /**
             *  3. Hire Vendor
             *  --------------
             */
            this.hire_vendor_option();

            /**
             *  4. Wishlist Rating
             *  ------------------
             */
            this.update_wishlist_rating();

            /**
             *  Couple Wishlist Page Rating
             *  ---------------------------
             */
            this.couple_wishlist_page_rating();

            /**
             *  Edit Icon To Active Tab ID
             *  --------------------------
             */
            this.show_tab();
        },
    };

    /**
     *  Document Load
     *  --------------
     */
    $(document).ready( function() { 

        /**
         *  Couple Dashboard Wishlist Script Load
         *  -------------------------------------
         */
        SDWeddingDirectory_Couple_Dashboard_Wishlist.init(); 

    } );

})(jQuery);
