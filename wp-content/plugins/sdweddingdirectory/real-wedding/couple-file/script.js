/**
 *  Couple Profile Update
 *  ---------------------
 */
(function($) {

  'use strict';

    var SDWeddingDirectory_Real_Wedding = {

        /**
         *  Bride And Groom Setting
         *  -----------------------
         */
        couple_real_wedding_header_info: function(){

            /**
             *  Form ID
             *  -------
             */
            var form        =   'couple_real_wedding_header_info',

                form_id     =   '#' + form;

            /**
             *  Form Submit
             *  -----------
             */
            if( $( 'form' + form_id ).length ){

                /**
                 *  When Submit
                 *  -----------
                 */
                $(document).on( 'submit', 'form' + form_id, function(e){

                    /**
                     *  Wait for event
                     *  --------------
                     */
                    e.preventDefault();



                    var     ajax_condition      =       true;

                    /**
                     *  Move to Top
                     *  -----------
                     */
                    $('html, body').animate({ scrollTop: 0 }, 'slow');

                    /**
                     *  Update Input
                     *  ------------
                     */
                    $( 'input' ).map( function( i ){ $(this).attr( 'value', $(this).val() ); });

                    /**
                     *  Required fields
                     *  ---------------
                     */
                    var     require_fields                          =   {

                                '#groom_first_name'                  :   $( form_id + ' #groom_first_name' ).val(),

                                '#groom_last_name'                   :   $( form_id + ' #groom_last_name' ).val(),

                                '#bride_first_name'                  :   $( form_id + ' #bride_first_name' ).val(),

                                '#bride_last_name'                   :   $( form_id + ' #bride_last_name' ).val(),

                                '#wedding_date'                      :   $( form_id + ' #wedding_date' ).val(),

                                '.real-wedding-featured-image'       :   $( form_id + ' .real-wedding-featured-image' ).val(),

                                '.real-wedding-gallery-images'       :   $( form_id + ' .real-wedding-gallery-images' ).val()
                            };

                    /**
                     *  Required Fields
                     *  ---------------
                     */
                    $.map( require_fields, function( value, id ){

                        /**
                         *  Confirm this is media section
                         *  -----------------------------
                         */
                        if( $( id ).hasClass( 'media_section' ) ){

                            /**
                             *  Make sure it's empty!
                             *  ---------------------
                             */
                            if( ! _is_empty( value )  ){

                                /**
                                 *  Alert
                                 *  -----
                                 */
                                sdweddingdirectory_alert( {

                                    'notice'    :       0,

                                    'message'   :       $( id ).attr( 'data-placeholder' )

                                } );

                                /**
                                 *  Ajax Stop
                                 *  ---------
                                 */
                                ajax_condition      =       false;
                            }

                            else if( _is_empty( value )  ){

                                /**
                                 *  Max / Min
                                 *  ---------
                                 */
                                var     min     =   $( id ).attr( 'data-min-limit' ),

                                        max     =   $( id ).attr( 'data-max-limit' );

                                /**
                                 *  Min Required Images
                                 *  -------------------
                                 */
                                if( _is_empty( min ) && value.split(",").length < min  ){

                                    /**
                                     *  Alert
                                     *  -----
                                     */
                                    sdweddingdirectory_alert( {

                                        'notice'    :       0,

                                        'message'   :       'Upload Minimum Limit '+ $( id ).attr( 'data-min-limit' )

                                    } );

                                    /**
                                     *  Ajax Stop
                                     *  ---------
                                     */
                                    ajax_condition      =       false;
                                }

                                /**
                                 *  Min Required Images
                                 *  -------------------
                                 */
                                else if( _is_empty( max ) &&  value.split(",").length > max ){

                                    /**
                                     *  Alert
                                     *  -----
                                     */
                                    sdweddingdirectory_alert( {

                                        'notice'    :       0,

                                        'message'   :       'Upload Maximum Limit '+ $( id ).attr( 'data-max-limit' )

                                    } );

                                    /**
                                     *  Ajax Stop
                                     *  ---------
                                     */
                                    ajax_condition      =       false;
                                }
                            }
                        }

                        /**
                         *  Normal Fields
                         *  -------------
                         */
                        else{

                            /**
                             *  Make sure it's empty!
                             *  ---------------------
                             */
                            if( !  _is_empty( value )  ){

                                /**
                                 *  Focus
                                 *  -----
                                 */
                                $( id ).focus();

                                /**
                                 *  Alert
                                 *  -----
                                 */
                                sdweddingdirectory_alert( {

                                    'notice'    :       0,

                                    'message'   :       'Required : '  +  $( id ).attr( 'placeholder' )

                                } );

                                /**
                                 *  Ajax Stop
                                 *  ---------
                                 */
                                ajax_condition      =       false;
                            }
                        }

                    } );

                    /**
                     *  Make sure no any error found
                     *  ----------------------------
                     */
                    if( ajax_condition ){

                        /**
                         *  Move to Top
                         *  -----------
                         */
                        $('html, body').animate({ scrollTop: 0 }, 'slow');

                        /**
                         *  Update Input
                         *  ------------
                         */
                        $( 'input' ).map( function( i ){ $(this).attr( 'value', $(this).val() ); });

                        /**
                         *  Sending Data AJAX
                         *  -----------------
                         */
                        $.ajax({

                            type                :   'POST',

                            dataType            :   'json',

                            url                 :   SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                            data                :   {

                                /**
                                 *  Action + Security
                                 *  -----------------
                                 */
                                'action'                        :       form,

                                'security'                      :       $( form_id + '-security' ).val(),

                                /**
                                 *  Input Fields
                                 *  ------------
                                 */
                                'input_group'                   :       {

                                    /**
                                     *  Wedding Information
                                     *  -------------------
                                     */
                                    'groom_first_name'          :       $( form_id + ' #groom_first_name' ).val(),

                                    'groom_last_name'           :       $( form_id + ' #groom_last_name' ).val(),

                                    'bride_first_name'          :       $( form_id + ' #bride_first_name' ).val(),

                                    'bride_last_name'           :       $( form_id + ' #bride_last_name' ).val(),

                                    'wedding_date'              :       $( form_id + ' #wedding_date' ).val(),

                                    'realwedding_gallery'       :       $( form_id + ' .real-wedding-gallery-images' ).val(),

                                    '_thumbnail_id'             :       $( form_id + ' .real-wedding-featured-image' ).val(),
                                },

                                /**
                                 *  Editor Fields
                                 *  -------------
                                 */
                                'editor_group'                  :       {

                                    /**
                                     *  Description
                                     *  -----------
                                     */
                                    'post_content'              :       $( form_id + ' #post_content' ).summernote('code')
                                },

                            },

                            success: function( PHP_RESPONSE ){

                                /**
                                 *  Alert
                                 *  -----
                                 */
                                sdweddingdirectory_alert( PHP_RESPONSE );

                                /**
                                 *  Have Redirection
                                 *  ----------------
                                 */
                                if ( PHP_RESPONSE.redirect == true ){

                                    setTimeout(function(){ document.location.href = PHP_RESPONSE.redirect_link; }, 2000 );
                                }
                            }

                        } );
                    }

                } );
            }
        },

        /**
         *  Wedding Info Setting
         *  --------------------
         */
        couple_real_wedding_info: function(){

            /**
             *  Form ID
             *  -------
             */
            var form        =   'couple_real_wedding_info',

                form_id     =   '#' + form;

            /**
             *  Form Submit
             *  -----------
             */
            if( $( 'form' + form_id ).length ){

                /**
                 *  When Submit
                 *  -----------
                 */
                $(document).on('submit', 'form' + form_id, function(e){

                    /**
                     *  Wait for event
                     *  --------------
                     */
                    e.preventDefault();

                    /**
                     *  Move to Top
                     *  -----------
                     */
                    $('html, body').animate({ scrollTop: 0 }, 'slow');

                    /**
                     *  Update Input
                     *  ------------
                     */
                    $( 'input' ).map( function( i ){ $(this).attr( 'value', $(this).val() ); });

                    /**
                     *  Sending Data AJAX
                     *  -----------------
                     */
                    $.ajax({

                        type: 'POST',

                        dataType: 'json',

                        url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data: {

                            /**
                             *  Action + Security
                             *  -----------------
                             */
                            'action'                   : form,

                            'security'                 : $( form_id + '-security' ).val(),

                            /**
                             *  Input Fields
                             *  ------------
                             */
                            'input_group'            :    {

                                /**
                                 *  Functions / Events
                                 *  ------------------
                                 */
                                'realwedding_function'          : $( form_id + ' #realwedding_function' ).val(),
                            },

                            /**
                             *  Group Of Data
                             *  -------------
                             */
                            'taxonomy_group'            :    {

                                'real-wedding-tag'                 : $( form_id + ' #real-wedding-tag').val(),
                            }
                        },

                        success: function( PHP_RESPONSE ){

                            /**
                             *  Alert
                             *  -----
                             */
                            sdweddingdirectory_alert( PHP_RESPONSE );

                            /**
                             *  Have Redirection
                             *  ----------------
                             */
                            if ( PHP_RESPONSE.redirect == true ){

                                setTimeout(function(){ document.location.href = PHP_RESPONSE.redirect_link; }, 2000 );
                            }
                        }
                    });

                });
            }
        },

        /**
         *  Outside Vendors
         *  ---------------
         */
        couple_realwedding_vendors: function(){

            /**
             *  Form ID
             *  -------
             */
            var form        =   'couple_realwedding_vendors',

                form_id     =   '#' + form;

            /**
             *  Form Submit
             *  -----------
             */
            if( $( 'form' + form_id ).length ){

                /**
                 *  When Submit
                 *  -----------
                 */
                $(document).on('submit', 'form' + form_id, function(e){

                    /**
                     *  Wait for event
                     *  --------------
                     */
                    e.preventDefault();

                    /**
                     *  Move to Top
                     *  -----------
                     */
                    $('html, body').animate({ scrollTop: 0 }, 'slow');

                    /**
                     *  Update Input
                     *  ------------
                     */
                    $( 'input' ).map( function( i ){ $(this).attr( 'value', $(this).val() ); });

                    var vendor_credits = new Array();

                    $( '#outside_vendor .collpase_section' ).map(function( index, value ) {

                        vendor_credits.push({

                            'category'    : $(this).find('.category').val(),
                            'company'     : $(this).find('.company').val(),
                            'website'     : $(this).find('.website').val()
                        });
                    });

                    /**
                     *  Sending Data AJAX
                     *  -----------------
                     */
                    $.ajax({

                        type: 'POST',

                        dataType: 'json',

                        url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data: {

                            /**
                             *  Action + Security
                             *  -----------------
                             */
                            'action'                   :    form,

                            'security'                 :    $( form_id + '-security' ).val(),

                            /**
                             *  Group Of Data
                             *  -------------
                             */
                            'input_group'               :   {

                                /**
                                 *  Vendors Credit
                                 *  --------------
                                 */
                                'our_website_vendor_credits'    : $( form_id + ' #our_website_vendor_credits').val().join(","),

                                /**
                                 *  Vendors Credit
                                 *  --------------
                                 */                              
                                'out_side_vendor_credits'       : vendor_credits,
                            }
                        },

                        success: function( PHP_RESPONSE ){

                            /**
                             *  Alert
                             *  -----
                             */
                            sdweddingdirectory_alert( PHP_RESPONSE );

                            /**
                             *  Have Redirection
                             *  ----------------
                             */
                            if ( PHP_RESPONSE.redirect == true ){

                                setTimeout(function(){ document.location.href = PHP_RESPONSE.redirect_link; }, 2000 );
                            }
                        }
                    });

                });
            }
        },

        /**
         *  Load the realweddign script data
         *  --------------------------------
         */
        init: function(){

            /**
             *  Bride And Groom Setting
             *  -----------------------
             */
            this.couple_real_wedding_header_info();

            /**
             *  Wedding Info Setting
             *  --------------------
             */
            this.couple_real_wedding_info();

            /**
             *  Outside Vendors
             *  ---------------
             */
            this.couple_realwedding_vendors();
        },
    }

    $( document ).ready( function(){ SDWeddingDirectory_Real_Wedding.init(); } )

})(jQuery);