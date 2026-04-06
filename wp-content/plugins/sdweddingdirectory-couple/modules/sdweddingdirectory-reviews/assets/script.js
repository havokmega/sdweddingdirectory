(function($) {

  'use strict';

    /**
     *  SDWeddingDirectory - Rating Object
     *  --------------------------
     */
    var     SDWeddingDirectory_Rating   =   {

            /**
             *  Init
             *  ----
             */
            init                    :       function(){

                                                this. rating_fill();
                                            },


            rating_fill             :       function(){

                                                if( $( '.sdweddingdirectory_review' ).length ){

                                                    $( '.sdweddingdirectory_review' ).map( function( index, value ) {

                                                        var     review      =       $(this).attr( 'data-review' );

                                                        $(this).rateYo( {

                                                            readOnly        :   true,

                                                            rating          :   review,

                                                            starWidth       :   "16px",

                                                            halfStar        :   true,

                                                            normalFill      :   SDWEDDINGDIRECTORY_REVIEW_AJAX.normalFill,

                                                            ratedFill       :   SDWEDDINGDIRECTORY_REVIEW_AJAX.ratedFill,

                                                        } );

                                                    } );
                                                }

                                                /**
                                                 *  If User Rate the Venue 
                                                 *  ------------------------
                                                 */
                                                if( $( '.user-rate-us' ).length ){
                                             
                                                    $( '.user-rate-us' ).map( function( index, value ){

                                                        var     _id     =   '#'  +  $( this ).attr( 'id' ),
                                                            
                                                                data    =   $( this ).attr( 'data-rating' );

                                                        /**
                                                         *  Have ID
                                                         *  -------
                                                         */
                                                        $(  _id  ).rateYo( {

                                                            readOnly        :   true,

                                                            rating          :   data,

                                                            starWidth       :   '16px',

                                                            halfStar        :   true

                                                        } );

                                                    } );
                                                }

                                                /**
                                                 *  Rating
                                                 *  ------
                                                 */
                                                if( $( '.couple_submit_rating' ).length ){

                                                    $( '.couple_submit_rating' ).map( function(){

                                                        var     _have_value     =   $( this ).parent().find( 'input' ).val();

                                                        if( _is_empty( _have_value ) ){

                                                            $( this ).rateYo( {

                                                                fullStar        :   true,

                                                                starWidth       :   '16px',

                                                                rating          :   _have_value,

                                                                ratedFill       :   SDWEDDINGDIRECTORY_REVIEW_AJAX.ratedFill,

                                                                onChange        :   function (rating) {

                                                                                        $( this ).parent().find( 'input' ).val( rating );
                                                                                    }
                                                            } );
                                                        }

                                                        else{

                                                            $( this ).rateYo( {

                                                                fullStar        :   true,

                                                                starWidth       :   '16px',

                                                                ratedFill       :   SDWEDDINGDIRECTORY_REVIEW_AJAX.ratedFill,

                                                                onChange        :   function (rating) {

                                                                                        $( this ).parent().find( 'input' ).val( rating );
                                                                                    }
                                                            } );
                                                        }

                                                    } );
                                                }
                                            }
    }

    /**
     *  Window Variable
     *  ---------------
     */
    window.SDWeddingDirectory_Rating        =       SDWeddingDirectory_Rating;

    /**
     *  Document
     *  --------
     */
    $( document ).ready( function(){        SDWeddingDirectory_Rating.init();       } );

})(jQuery);