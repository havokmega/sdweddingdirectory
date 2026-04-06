/**
 *  Share Post Model Script
 *  -----------------------
 */

(function($) {

    "use strict";

    var SDWeddingDirectory_Share_Post = {

        /**
         *  1. Forgot Password Script Running
         *  ---------------------------------
         */
        sdweddingdirectory_share_post_model: function(){

            if( $('.sdweddingdirectory-share-post-model').length ){

                $('.sdweddingdirectory-share-post-model').on( 'click', function ( e ){

                    /**
                     *  Wait for the event
                     *  ------------------
                     */
                    e.preventDefault();

                    /**
                     *  Var
                     *  ---
                     */
                    var     _this   =   $(this),

                            _current_icon   =   $(_this).find( 'i' ).attr( 'class' ),

                            _loader_icon    =   'fa fa-spinner fa-spin';


                    $( _this ).addClass( 'disabled' );

                    /**
                     *  Start AJAX
                     *  ----------
                     */
                    $.ajax({

                        type: 'POST',

                        dataType: 'json',

                        url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data: {

                            /**
                             *  Action and Security
                             *  -------------------
                             */
                            'action'     :   'sdweddingdirectory_share_post_model',

                            'post_id'    :   $(this).attr( 'data-post-id' ),
                        },

                        beforeSend: function(){

                            /**
                             *  Have Post Share Model ?
                             *  -----------------------
                             */
                            if( $( '.sdweddingdirectory_post_share_model' ).length ){

                                $( '.sdweddingdirectory_post_share_model' ).remove();
                            }

                            /**
                             *  Button Loader Added
                             *  -------------------
                             */
                            $( _this ).find( 'i' ).attr( 'class', _loader_icon );
                        },

                        complete: function(){

                            /**
                             *  Button Loader Added
                             *  -------------------
                             */
                            $( _this ).find( 'i' ).attr( 'class', _current_icon );

                            /**
                             *  Removed Disabled
                             *  ----------------
                             */
                            $( _this ).removeClass( 'disabled' );
                        },

                        success: function ( PHP_RESPONSE ){

                            /**
                             *  Hide Model Popup
                             *  ----------------
                             */
                            if( $( 'body' ).length ){

                                $( 'body' ).append( PHP_RESPONSE.model_data );

                                $( '#' + PHP_RESPONSE.model_id ).modal( 'show' );
                            }

                            /**
                             *  Start Copy
                             *  ----------
                             */
                            var _button_id  =   '#button_' + PHP_RESPONSE.model_id,

                                clipboard   =   new ClipboardJS( _button_id );

                            /**
                             *  Event Start
                             *  -----------
                             */
                            clipboard.on( 'success', function(e) {

                                e.clearSelection();

                                var _success_msg_id     =   $( _button_id ).attr( 'data-message-id' ),

                                    _message_val        =   $( _button_id ).attr( 'data-success-string' );
                                
                                    $( _success_msg_id ).text( _message_val );
                            });
                        },
                    });
                });
            }
        },

        /**
         *  Forgot Password Object
         *  ----------------------
         */
        init: function() {

            /**
             *  1. Load Script
             *  --------------
             */
            this.sdweddingdirectory_share_post_model();
        }
    };

    window.SDWeddingDirectory_Share_Post = SDWeddingDirectory_Share_Post; 

    $(document).ready( function(){ SDWeddingDirectory_Share_Post.init(); });

})(jQuery);