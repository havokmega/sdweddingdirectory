/**
 *  Couple Dashboard - Real Wedding - Widget
 *  ----------------------------------------
 */
(function($) {

  'use strict';

    var SDWeddingDirectory_Real_Wedding_Dashboard_Widget = {

        /**
         *  Color
         *  -----
         */
        couple_real_wedding_select_color_filter: function(){

            /**
             *  Form ID
             *  -------
             */
            var _hook       =   'couple_real_wedding_select_color_filter',

                _target     =   '.' + _hook;

            /**
             *  Form Submit
             *  -----------
             */
            if( $( _target ).length ){

                /**
                 *  When Submit
                 *  -----------
                 */
                $(document).on( 'click', _target, function(e){

                    /**
                     *  Update ID
                     *  ---------
                     */
                    var     _this           =   $(this),

                            _target_id      =   $( _this ).attr( 'data-target' ),

                            _update_id      =   '#' + _target_id;

                    /**
                     *  Dropdown Close
                     *  --------------
                     */
                    $( _this ).closest( '.wedding-details-items' ).find( 'button' ).trigger( 'click' );

                    /**
                     *  Wait for event
                     *  --------------
                     */
                    e.preventDefault();

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
                            'action'        :   _hook,

                            /**
                             *  Name
                             *  ----
                             */
                            'taxonomy'      :   $( _this ).attr( 'data-taxonomy' ),

                            /**
                             *  Term ID
                             *  -------
                             */
                            'term_id'       :   $( _this ).attr( 'data-term-id' ),
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
                            $( _update_id + '-taxonomy-name' ).html( PHP_RESPONSE.name );

                            $( _update_id + '-taxonomy-count' ).html( PHP_RESPONSE.count );

                            /**
                             *  Id Found ?
                             *  ----------
                             */
                            if( $( _update_id + '-taxonomy-color' ).length ){

                                $( _update_id + '-taxonomy-color' ).css( 'color', PHP_RESPONSE.color );
                            }

                            else{

                                var _content    =   '<div class="couple-save-color-code"><i class="fa fa-3x fa-circle" id="' + _target_id + '-taxonomy-color" style="color:' + PHP_RESPONSE.color + ';" aria-hidden="true"></i></div>';

                                $( _this ).closest( '.wedding-details-items' ).find( '.question' ).replaceWith( _content )
                            }
                        }
                    });

                });
            }
        },

        /**
         *  Season
         *  ------
         */
        couple_real_wedding_select_season_filter: function(){

            /**
             *  Form ID
             *  -------
             */
            var _hook       =   'couple_real_wedding_select_season_filter',

                _target     =   '.' + _hook;

            /**
             *  Form Submit
             *  -----------
             */
            if( $( _target ).length ){

                /**
                 *  When Submit
                 *  -----------
                 */
                $(document).on( 'click', _target, function(e){

                    /**
                     *  Update ID
                     *  ---------
                     */
                    var     _this           =   $( this ),

                            _target_id      =   $( _this ).attr( 'data-target' ),

                            _update_id      =   '#' + _target_id;

                    /**
                     *  Dropdown Close
                     *  --------------
                     */
                    $( _this ).closest( '.wedding-details-items' ).find( 'button' ).trigger( 'click' );

                    /**
                     *  Wait for event
                     *  --------------
                     */
                    e.preventDefault();

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
                            'action'        :   _hook,

                            /**
                             *  Name
                             *  ----
                             */
                            'taxonomy'      :   $( _this ).attr( 'data-taxonomy' ),

                            /**
                             *  Term ID
                             *  -------
                             */
                            'term_id'       :   $( _this ).attr( 'data-term-id' ),
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
                            $( _update_id + '-taxonomy-name' ).html( PHP_RESPONSE.name );

                            $( _update_id + '-taxonomy-count' ).html( PHP_RESPONSE.count );

                            /**
                             *  Id Found ?
                             *  ----------
                             */
                            if( $( _update_id + '-taxonomy-icon' ).length ){

                                $( _update_id + '-taxonomy-icon' ).attr( 'class', '' );

                                $( _update_id + '-taxonomy-icon' ).addClass( 'fa fa-3x ' + PHP_RESPONSE.icon );
                            }

                            else{

                                var _content    =   '<div class="real-wedding-summary-widget-icon"><i class="fa fa-3x '+ PHP_RESPONSE.icon +'" id="'+ _target_id +'-taxonomy-icon" aria-hidden="true"></i></div>';

                                $( _this ).closest( '.wedding-details-items' ).find( '.question' ).replaceWith( _content )
                            }
                        }
                    });

                });
            }
        },

        /**
         *  Style
         *  -----
         */
        couple_real_wedding_select_style_filter: function(){

            /**
             *  Form ID
             *  -------
             */
            var _hook       =   'couple_real_wedding_select_style_filter',

                _target     =   '.' + _hook;

            /**
             *  Form Submit
             *  -----------
             */
            if( $( _target ).length ){

                /**
                 *  When Submit
                 *  -----------
                 */
                $(document).on( 'click', _target, function(e){

                    /**
                     *  Update ID
                     *  ---------
                     */
                    var     _this           =   $( this ),

                            _target_id      =   $( _this ).attr( 'data-target' ),

                            _update_id      =   '#' + _target_id;

                    /**
                     *  Dropdown Close
                     *  --------------
                     */
                    $( _this ).closest( '.wedding-details-items' ).find( 'button' ).trigger( 'click' );

                    /**
                     *  Wait for event
                     *  --------------
                     */
                    e.preventDefault();

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
                            'action'        :   _hook,

                            /**
                             *  Name
                             *  ----
                             */
                            'taxonomy'      :   $( _this ).attr( 'data-taxonomy' ),

                            /**
                             *  Term ID
                             *  -------
                             */
                            'term_id'       :   $( _this ).attr( 'data-term-id' ),
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
                            $( _update_id + '-taxonomy-name' ).html( PHP_RESPONSE.name );

                            $( _update_id + '-taxonomy-count' ).html( PHP_RESPONSE.count );

                            /**
                             *  Id Found ?
                             *  ----------
                             */
                            if( $( _update_id + '-taxonomy-icon' ).length ){

                                $( _update_id + '-taxonomy-icon' ).attr( 'class', '' );

                                $( _update_id + '-taxonomy-icon' ).addClass( 'fa fa-3x ' + PHP_RESPONSE.icon );
                            }

                            else{

                                var _content    =   '<div class="real-wedding-summary-widget-icon"><i class="fa fa-3x '+ PHP_RESPONSE.icon +'" id="'+ _target_id +'-taxonomy-icon" aria-hidden="true"></i></div>';

                                $( _this ).closest( '.wedding-details-items' ).find( '.question' ).replaceWith( _content )
                            }
                        }
                    });

                });
            }
        },

        /**
         *  Select Designer
         *  ---------------
         */
        couple_real_wedding_select_designer_filter: function(){

            /**
             *  Form ID
             *  -------
             */
            var _hook       =   'couple_real_wedding_select_designer_filter',

                _target     =   '.' + _hook;

            /**
             *  Form Submit
             *  -----------
             */
            if( $( _target ).length ){

                /**
                 *  When Submit
                 *  -----------
                 */
                $(document).on( 'change', _target, function(e){

                    /**
                     *  Dropdown Close
                     *  --------------
                     */
                    $(this).closest( '.wedding-details-items' ).find( 'button' ).trigger( 'click' );

                    /**
                     *  Update ID
                     *  ---------
                     */
                    var     _update_id  =   '#' + $(this).attr( 'data-target' );

                    /**
                     *  Wait for event
                     *  --------------
                     */
                    e.preventDefault();

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
                            'action'        :   _hook,

                            /**
                             *  Name
                             *  ----
                             */
                            'realwedding-dress'         :   $( this ).val()
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
                            $( _update_id + '-taxonomy-name' ).html( PHP_RESPONSE.name );

                            $( _update_id + '-taxonomy-count' ).html( PHP_RESPONSE.count );
                        }
                    });

                });
            }
        },

        /**
         *  Select Honeymoon Location
         *  -------------------------
         */
        couple_real_wedding_select_honeymoon_vendor_filter: function(){

            /**
             *  When click location
             *  -------------------
             */
            if( $( 'input.real-wedding-location' ).length ){


                var     dropdown_id     =   '#'     +   $( 'input.real-wedding-location' ).attr( 'data-dropdown-id' ),

                        _target_a       =   dropdown_id + ' a.search-item';


                $( document ).on( 'click', _target_a, function( e ){

                    /**
                     *  Dropdown Close
                     *  --------------
                     */
                    $(this).closest( '.wedding-details-popups' ).find( 'button' ).trigger( 'click' );

                    /**
                     *  Event Start
                     *  -----------
                     */
                    e.preventDefault();

                    /**
                     *  Wait for update the value in input
                     *  ----------------------------------
                     */
                    setTimeout( function(){

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
                                'action'        :   'couple_real_wedding_select_honeymoon_vendor_filter',

                                /**
                                 *  Name
                                 *  ----
                                 */
                                'term_id'       :   $( 'input.real-wedding-location' ).attr( 'data-value-id' )
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
                                $( '#honeymoon-taxonomy-name' ).html( PHP_RESPONSE.name );

                                $( '#honeymoon-taxonomy-count' ).html( PHP_RESPONSE.count );
                            }
                        });

                    }, 100 );

                } );
            }
        },

        /**
         *  Load the realweddign script data
         *  --------------------------------
         */
        init: function(){

            /**
             *  Select Color
             *  ------------
             */
            this.couple_real_wedding_select_color_filter();

            /**
             *  Select Season
             *  -------------
             */
            this.couple_real_wedding_select_season_filter();

            /**
             *  Select Style
             *  ------------
             */
            this.couple_real_wedding_select_style_filter();

            /**
             *  Select Designer
             *  ---------------
             */
            this.couple_real_wedding_select_designer_filter();

            /**
             *  Select Honeymoon Location
             *  -------------------------
             */
            this.couple_real_wedding_select_honeymoon_vendor_filter();
        },
    }

    $( document ).ready( function(){ SDWeddingDirectory_Real_Wedding_Dashboard_Widget.init(); } )

})(jQuery);