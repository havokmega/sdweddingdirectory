(function($) {

    'use strict';

    var SDWeddingDirectory_Review = {

        /**
         *  2. Vendor Response Via : Dashboard > Review
         *  -------------------------------------------
         */
        vendor_comment: function(){

            if( $( '.sdweddingdirectory_vendor_comment_for_couple' ).length ){

                $( '.sdweddingdirectory_vendor_comment_for_couple' ).map( function(){

                    var form_id =  '#' + $(this).attr( 'id' );

                    if( $( form_id ).length ){

                        $( $( form_id ) ).on( 'submit', function( e ){

                            e.preventDefault();

                            var textarea = $(this).find( 'textarea' ).val(),

                                security = $(this).find( '.security' ).val(),

                                post_id  = $(this).find( '.review_post_id' ).val();


                            $.ajax({

                                type: 'POST',

                                dataType: 'json',

                                url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                                data: {

                                    /**
                                     *  Action + Security
                                     *  -----------------
                                     */
                                    'action'       :   'sdweddingdirectory_vendor_review_response_action',

                                    'security'     :   security,

                                    /**
                                     *  Review Post ID
                                     *  --------------
                                     */
                                    'review_post_id'   :   post_id,

                                    'vendor_comment'  :   textarea
                                },
                                success: function( PHP_RESPONSE ){

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
                                beforeSend: function(){

                                },
                                error: function (xhr, ajaxOptions, thrownError) {

                                    console.log( 'SDWeddingDirectory_Error_Venue_Removed' );
                                    console.log(xhr.status);
                                    console.log(thrownError);
                                    console.log(xhr.responseText);
                                },
                                complete: function( event, request, settings ){

                                }
                            });

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

                    $(".reviews-tabbing-wrap .theme-tabbing-vertical a").filter( function() {

                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                });
            }
        },

        /**
         *  Submit Review
         *  -------------
         */
        submit_review: function(){

            /**
             *  Couple Modify Rating
             *  --------------------
             */
            if( $( 'form.sdweddingdirectory_couple_update_rating' ).length ){

                $( 'form.sdweddingdirectory_couple_update_rating' ).on( 'submit', function( e ){

                    /**
                     *  Handler
                     *  -------
                     */
                    var     _this       =   $( this ),

                            _target     =   $( _this ).attr( 'id' ),

                            _form       =   '#'  +  _target,

                            _security   =   $( _form ).find( '#security_' +  _target ).val();

                    /**
                     *  Disable Button When Process not Completed
                     *  -----------------------------------------
                     */
                    $( '#submit-' + _target ).addClass( 'disabled' );

                    /**
                     *  Submit Review
                     *  -------------
                     */
                    $.ajax({

                        type: 'POST',

                        dataType: 'json',

                        url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data: {

                            /**
                             *  Action + security
                             *  -----------------
                             */
                            
                            'action'            :   'sdweddingdirectory_submit_venue_review',

                            'security'          :   _security,

                            'form_id'           :   _target,

                            /**
                             *  Rating + Comment
                             *  ----------------
                             */

                            'venue_id'        :   $( _form + ' #venue_id' ).length
                                                
                                                ?   $( _form + ' #venue_id' ).val() 

                                                :   0,

                            'post_id'           :   $( _form + ' #rating_id' ).length
                                                
                                                ?   $( _form + ' #rating_id' ).val() 

                                                :   0,

                            'quality_service'   :   $( _form + ' #quality_service' ).length
                                                
                                                ?   $( _form + ' #quality_service' ).val() 

                                                :   1,

                            'facilities'        :   $( _form + ' #facilities' ).length
                                                
                                                ?   $( _form + ' #facilities' ).val() 

                                                :   1,

                            'staff'             :   $( _form + ' #staff' ).length
                                                
                                                ?   $( _form + ' #staff' ).val() 

                                                :   1,

                            'flexibility'       :   $( _form + ' #flexibility' ).length
                                                
                                                ?   $( _form + ' #flexibility' ).val() 

                                                :   1,

                            'value_of_money'    :   $( _form + ' #value_of_money' ).length
                                                
                                                ?   $( _form + ' #value_of_money' ).val() 

                                                :   1,

                            'review_title'      :   $( _form + ' #sdweddingdirectory_venue_review_title' ).val(),

                            'couple_comment'    :   $( _form + ' #sdweddingdirectory_venue_review_comment' ).val(),
                        },

                        success: function( PHP_RESPONSE ) {

                            /**
                             *  Alert
                             *  -----
                             */
                            sdweddingdirectory_alert( PHP_RESPONSE );

                            $( _form ).find( 'button[type=submit] i' ).remove();

                            /**
                             *  Couple Submited review message
                             *  ------------------------------
                             */
                            if( PHP_RESPONSE.review_body != '' && $( '#sdweddingdirectory_review_comment_section' ).length ){

                                $( '#sdweddingdirectory_review_comment_section' ).html( PHP_RESPONSE.review_body );
                            }
                        },

                        beforeSend: function( PHP_RESPONSE ){

                            $( _form ).find( 'button[type=submit]' ).append( '<i class="ms-2 fa fa-spinner fa-spin"></i>' );
                        },

                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory - Review Submit Script Issue Found in Venue Singular Page' );
                            console.log(xhr.status);
                            console.log(thrownError);
                            console.log(xhr.responseText);
                        },

                        complete: function( event, request, settings ){

                            /**
                             *  Disable Button When Process not Completed
                             *  -----------------------------------------
                             */
                            $( '#submit-' + _target ).removeClass( 'disabled' );
                        }
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
             *  Vendor Response Via : Dashboard > Review
             *  ----------------------------------------
             */
            this.vendor_comment();

            /**
             *  Find Review
             *  -----------
             */
            this.find_reviews();

            /**
             *  Submit Rating
             *  -------------
             */
            this.submit_review();
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