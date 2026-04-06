(function($) {

    'use strict';

    var SDWeddingDirectory_Review = {

        /**
         *  1. Select Option to open Tab Content ( Overview + Reviews list )
         *  ----------------------------------------------------------------
         */
        select_venue_show_reviews: function(){

            /**
             *  Select Option To Show Tab Overview + Tab Content too
             *  ----------------------------------------------------
             */
            if( $( '.sdweddingdirectory-select-tab' ).length ){

                $( '.sdweddingdirectory-select-tab' ).map( function(){

                    $( this ).on( 'change', function(){

                        $('a[id="'+ $(this).val() +'"]').tab( 'show' );

                        $('a[id="'+ $(this).val() +'-summary"]').tab( 'show' );

                    } );

                } );
            }
        },

        /**
         *  2. Vendor Response Via : Dashboard > Review
         *  -------------------------------------------
         */
        vendor_comment: function(){

            /**
             *  Vendor Response
             *  ---------------
             */
            if( $( '.sdweddingdirectory_vendor_response' ).length ){

                $( '.sdweddingdirectory_vendor_response' ).map( function(){

                    /**
                     *  Form ID
                     *  -------
                     */
                    var     form            =       $( this ).attr( 'id' ),

                            form_id         =       '#'     +   form,

                            button          =       '#submit-' + form;

                    /**
                     *  Found Lenght ?
                     *  --------------
                     */
                    if( $( form_id ).length ){

                        /**
                         *  When Form Submited
                         *  ------------------
                         */
                        $( form_id ).on( 'submit', function( e ){

                            /**
                             *  Wait for event
                             *  --------------
                             */
                            e.preventDefault();

                            /**
                             *  Handler
                             *  -------
                             */
                            var     textarea        =       $( this ).find( 'textarea' ).val(),

                                    security        =       $( this ).find( '.security' ).val(),

                                    post_id         =       $( this ).find( '.review_post_id' ).val();

                            /**
                             *  Disable Button When Process not Completed
                             *  -----------------------------------------
                             */
                            $( button ).addClass( 'disabled' );

                            /**
                             *  AJAX Start
                             *  ----------
                             */
                            $.ajax( {

                                type            :       'POST',

                                dataType        :       'json',

                                url             :       SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                                data            :       {
                                                            'action'       :   'sdweddingdirectory_vendor_review_response_action',

                                                            'security'     :   security,

                                                            /**
                                                             *  Review Post ID
                                                             *  --------------
                                                             */
                                                            'review_post_id'   :   post_id,

                                                            'vendor_comment'  :   textarea
                                                        },

                                success         :       function( PHP_RESPONSE ){

                                                            /**
                                                             *  Response
                                                             *  --------
                                                             */
                                                            sdweddingdirectory_alert(  PHP_RESPONSE  );

                                                            console.log( PHP_RESPONSE );

                                                            console.log( '#vendor_comment_area_'+ post_id );

                                                            /**
                                                             *  Vendor Div Load
                                                             *  ---------------
                                                             */
                                                            if( $( '#vendor_comment_area_'+ post_id  ).length ){

                                                                $( '#vendor_comment_area_'+ post_id  ).html( PHP_RESPONSE.html );
                                                            }
                                                        },

                                beforeSend      :       function(){

                                                            SDWeddingDirectory_Elements.button_loader_start( form_id );
                                                        },

                                error           :       function (xhr, ajaxOptions, thrownError) {

                                                            console.log( 'SDWeddingDirectory_Error_Venue_Removed' );
                                                            console.log(xhr.status);
                                                            console.log(thrownError);
                                                            console.log(xhr.responseText);
                                                        },

                                complete        :       function( event, request, settings ){

                                                            SDWeddingDirectory_Elements.button_loader_end( form_id );
                                                        }
                            } );

                        } );
                    }

                } );
            }
        },

        /**
         *  3. Find Review
         *  --------------
         */
        find_reviews: function(){

            if( $("#sdweddingdirectory-review-search").length ){

                $("#sdweddingdirectory-review-search").on( "keyup", function() {

                    var value = $(this).val().toLowerCase();

                    $("#sdweddingdirectory-reviews-showcase .tab-pane.active .theme-tabbing-vertical a").filter( function() {

                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                });
            }
        },

        /**
         *  Vendor Dashboard - Review Page
         *  ------------------------------
         */
        init : function(){

            /**
             *  1. Select Option to open Tab Content ( Overview + Reviews list )
             *  ----------------------------------------------------------------
             */
            this.select_venue_show_reviews();

            /**
             *  2. Vendor Response Via : Dashboard > Review
             *  -------------------------------------------
             */
            this.vendor_comment();

            /**
             *  3. Find Review
             *  --------------
             */
            this.find_reviews();
        }
    }

    /**
     *  Vendor Review Dashboard Script Load
     *  -----------------------------------
     */
    $( document ).ready( function(){ 

        /**
         *  Review Object Call
         *  ------------------
         */
        SDWeddingDirectory_Review.init(); 

    } );

})(jQuery);