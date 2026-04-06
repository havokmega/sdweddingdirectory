/**
 *  SDWeddingDirectory Couple Real Wedding Singular Page
 *  --------------------------------------------
 */
(function($) {

    "use strict";

    var SDWeddingDirectory_Real_Wedding_Singular = {

        /**
         *  1. Real Wedding Gallery Popup
         *  -----------------------------
         */
        real_wedding_gallery : function(){

            if( $('.realwedding-gallery').length ){

                $('.realwedding-gallery').magnificPopup({

                    delegate    : 'a',

                    type        : 'image',

                    tLoading    : 'Loading image #%curr%...',

                    mainClass   : 'mfp-img-mobile',

                    gallery     : {

                        enabled: true,

                        navigateByImgClick: true,

                        preload: [0,1]
                    },

                    image: {

                        tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',

                        titleSrc: function(item) {

                            return item.el.attr('title');
                        }
                    }
                });
            }
        },

        /**
         *   Button On click scroll
         *   ----------------------
         */
        click_to_go : function( click_id, move_to_id ){

            if( $( '#' + click_id ).length && $( '#' + move_to_id ).length ){

                $( '#' + click_id ).on( 'click', function( e ) {

                     e.preventDefault();
                     
                    $('html, body').animate({

                        scrollTop: $( '#' + move_to_id ).offset().top - 170

                    }, 1500 );

                });
            }
        },

        /**
         *  Load the functions
         *  ------------------
         */
        init: function() {

            /**
             *  1. Real Wedding Gallery
             *  -----------------------
             */
            this.real_wedding_gallery();

            /**
             *  2. Click to Got Section ID
             *  --------------------------
             */
            this.click_to_go( 'sdweddingdirectory_vendor_request', 'couple_vendors_team' );
        },

    };

    $(document).ready( function(){ SDWeddingDirectory_Real_Wedding_Singular.init(); });

})(jQuery);