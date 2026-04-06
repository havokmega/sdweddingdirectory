/**
 *  Review Script
 *  -------------
 */
(function($) {

    'use strict';

    var SDWeddingDirectory_Review = {

        /**
         *  Have Review ? If yes - then start rating showing on page
         *  --------------------------------------------------------
         */
        get_review: function(){

            if( $('.sdweddingdirectory_review').length ){

                $('.sdweddingdirectory_review').map(function( index, value ){

                    var review = $(this).attr( 'data-review' );

                    $( $(this) ).rateYo({

                        readOnly: true,

                        rating: review,

                        starWidth: "16px",

                        halfStar: true,

                        normalFill: SDWEDDINGDIRECTORY_RATING_OBJ.normalFill,

                        ratedFill: SDWEDDINGDIRECTORY_RATING_OBJ.ratedFill,
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
            if( $( 'form.sdweddingdirectory_submit_review' ).length ){

                $( 'form.sdweddingdirectory_submit_review' ).on( 'submit', function( e ){

                    /**
                     *  Event Start
                     *  -----------
                     */
                    e.preventDefault();

                    /**
                     *  Handler
                     *  -------
                     */
                    var     _this       =   $( this ),

                            _target     =   $( _this ).attr( 'id' ),

                            _form       =   '#'  +  _target,

                            _security   =   $( _form ).find( '#security_' +  _target ).val();

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

                        }
                    });
                });
            }
        },

        /**
         *  Disable Button
         *  --------------
         */
        button_disable: function( $id ){

            $('#'+$id).attr( 'type', 'button' );
            $('#'+$id).css("cursor", "default");
            $('#'+$id).addClass( 'disabled' ).attr( 'aria-disabled', 'true"' );
        },

        /**
         *  Load More Review
         *  ----------------
         */
        load_more_review: function(){

            /**
             *  Have Load More Class ?
             *  ----------------------
             */
            if(  $( '.loadmore' ).length  ){

                $( '.loadmore' ).map( function(){

                    var     _this       =       '#'     +   $( this ).attr( 'id' ),

                            page        =       2;

                    /**
                     *  When click on button ID
                     *  -----------------------
                     */
                    $( _this ).on( 'click', function( e ) {

                        /**
                         *  Event Start
                         *  -----------
                         */
                        e.preventDefault();

                        var     _load_post_id       =       '#'     +   $( _this ).attr( 'data-load-rating-id' );

                        /**
                         *  Wait for data, Stop Button
                         *  --------------------------
                         */
                        $( _this ).addClass( 'disabled' );

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
                                'action'        : 'sdweddingdirectory_load_review_comment_posts',
                                
                                /**
                                 *  Fields
                                 *  ------
                                 */
                                'page'          :   page,
                                
                                'venue_id'    :   $(this).attr( 'data-venue-post-id' ),

                                'vendor_id'     :   $(this).attr( 'data-vendor-post-id' ),
                            },

                            success: function( PHP_RESPONSE ) {

                                /**
                                 *  Have Review Comment ?
                                 *  ---------------------
                                 */
                                if( $.trim( PHP_RESPONSE.review_comments ) != '' ){

                                    $( _load_post_id ).append( PHP_RESPONSE.review_comments );

                                    /**
                                     *  Update Reviews
                                     *  --------------
                                     */
                                    SDWeddingDirectory_Review.get_review();

                                    page++;

                                }else{

                                    $( _this ).hide();
                                }

                                /**
                                 *  Loader Show / Hide ?
                                 *  --------------------
                                 */
                                if( PHP_RESPONSE.hide_loader_button == true ){

                                    $( _this ).hide();
                                }
                            },

                            beforeSend: function( PHP_RESPONSE ){

                                /**
                                 *  Before Start AJAX ( Find Comment ) Loader is Showing After Button Text
                                 *  ----------------------------------------------------------------------
                                 */
                                if( $( _this ).find( 'i' ).length ){

                                    $( _this ).find( 'i' ).remove();
                                }

                                $( _this ).append( '<i class="fa fa-spinner fa-spin ms-2"></i>' );
                            },

                            error: function (xhr, ajaxOptions, thrownError) {

                                console.log( 'SDWeddingDirectory - Review Comments Load on Page' );
                                console.log(xhr.status);
                                console.log(thrownError);
                                console.log(xhr.responseText);
                            },

                            complete: function( event, request, settings ){

                                /**
                                 *  Remove Loader After complete AJAX
                                 *  ---------------------------------
                                 */
                                $( _this + ' i').remove();

                                $( _this ).removeClass( 'disabled' );
                            }

                        } );

                    } );

                } );
            }
        },

        /**
         *  Get Collection 
         *  --------------
         */
        init : function (){

            /**
             *  Submit review
             *  -------------
             */
            this.submit_review();

            /**
             *  Load More Review
             *  ----------------
             */
            this.load_more_review();
        }
    }

    /**
     *  Review Script Loaded in Window
     *  ------------------------------
     */
    window.SDWeddingDirectory_Review = SDWeddingDirectory_Review;

    /**
     *  Load Review Script
     *  ------------------
     */
    $( document ).ready( function(){ 

        /**
         *  Call Review Object
         *  ------------------
         */
        SDWeddingDirectory_Review.init();

        /**
         *  Click to Go Section
         *  -------------------
         */
        SDWeddingDirectory_Elements.click_to_go( 'write-review-button', 'sdweddingdirectory-write-review-form-section' );

    } );

})(jQuery);