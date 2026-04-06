/**
 *  SDWeddingDirectory - RSVP
 *  -----------------
 */
(function($) {

    'use strict';

    var SDWeddingDirectory_RSVP     =   {

        /**
         *  Post ID
         *  -------
         */
        _post_id: function(){

            return  $( '#sdweddingdirectory-rsvp-process' ).attr( 'data-post-id' );
        },

        /**
         *  Guest RSVP security nonce
         *  -------------------------
         */
        _security: function(){

            return  SDWEDDINGDIRECTORY_AJAX_OBJ && SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_rsvp_guest_security

                    ?   SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_rsvp_guest_security

                    :   '';
        },

        /**
         *  Honeypot field value
         *  --------------------
         */
        _honeypot: function(){

            return  $( '#sdweddingdirectory_rsvp_honeypot' ).length

                    ?   $( '#sdweddingdirectory_rsvp_honeypot' ).val()

                    :   '';
        },

        _move_rsvp_section: function(){

            /**
             *  Move RSVP Section
             *  -----------------
             */
            if( $( '#navbarResponsive ul li a[href=#rsvp]' ).length ){

                $( '#navbarResponsive ul li a[href=#rsvp]' ).click();
            }
        },

        /**
         *  1. RSVP - Website template
         *  --------------------------
         */
        sdweddingdirectory_rsvp_submit : function(){

            /**
             *  Have Lenght ?
             *  -------------
             */
            if( $( 'form#sdweddingdirectory-rsvp-website-template-one' ).length ){

                $( 'form#sdweddingdirectory-rsvp-website-template-one' ).on( 'submit', function( e ){

                    e.preventDefault();

                    var   _first_name   = $(this).find( '#first_name' ).val(),

                          _last_name    = $(this).find( '#last_name' ).val(),

                          _post_id      = SDWeddingDirectory_RSVP._post_id(),

                          _alt          = $(this).find( '#_first_name_or_last_name' ).val(),

                          _button       = $(this).find( '#couple_rsvp_submit_layout_1' ).html();

                          /**
                           *  Show loader
                           *  -----------
                           */
                          $( '#couple_rsvp_submit_layout_1' ).append( '<i class="ms-2 fa fa-spinner fa-spin"></i>' );

                          /**
                           *  Not Found Banner Removed
                           *  ------------------------
                           */
                           $( '#_not_found_' ).remove();

                          /**
                           *  Make sure first name or last name not empty!
                           *  --------------------------------------------
                           */
                          if( _first_name !== '' || _last_name !== '' ){

                              /**
                               *  1. Message + Loader Removed
                               *  ---------------------------
                               */
                              $( '#_message' ).html( '' );

                              /**
                               *  2. Find First Name or Last Name
                               *  -------------------------------
                               */
                              SDWeddingDirectory_RSVP.find_surname_available(

                                  _first_name,

                                  _last_name
                              );

                          }else{

                              $( '#_message' ).html( '<p class="text-danger">' + _alt + '</p>' );

                              $( '#couple_rsvp_submit_layout_1' ).find( 'i' ).remove();
                          }
                } );
            }
        },

        /**
         *  2. Find Out Name or Surname is available in database ?
         *  ------------------------------------------------------
         */
        find_surname_available : function( first_name, last_name ){

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
                    'action'                    : 'sdweddingdirectory_couple_guest_find_name',

                    /**
                     *  Guest Name
                     *  ----------
                     */
                    'post_id'                   : SDWeddingDirectory_RSVP._post_id(),

                    'first_name'                : first_name,

                    'last_name'                 : last_name,

                    'security'                  : SDWeddingDirectory_RSVP._security(),

                    'honeypot'                  : SDWeddingDirectory_RSVP._honeypot(),
                },
                success: function( PHP_RESPONSE ){

                    /**
                     *  Have lenth ?
                     *  ------------
                     */
                    if( $( '#sdweddingdirectory-rsvp-process' ).length && _is_empty( PHP_RESPONSE.data ) && PHP_RESPONSE.notice == 1 ){

                        $( '#sdweddingdirectory-rsvp-process' ).html( PHP_RESPONSE.data );
                    }

                    if( PHP_RESPONSE.notice == 0 ){

                        $( '#_message' ).html( '<p class="text-danger">' + PHP_RESPONSE.data + '</p>' );

                        $( '#couple_rsvp_submit_layout_1' ).find( 'i' ).remove();
                    }

                    $( '#couple_rsvp_submit_layout_1' ).find( 'i' ).remove();

                    /**
                     *  If click on new search
                     *  ----------------------
                     */
                    SDWeddingDirectory_RSVP.rsvp_new_search();

                    /**
                     *  If click on Continues
                     *  ---------------------
                     */
                    SDWeddingDirectory_RSVP.rsvp_continues();
                }
            });
        },

        /**
         *  2. RSVP - New Search
         *  --------------------
         */
        rsvp_new_search: function(){

            /**
             *  Have lenght ?
             *  -------------
             */
            if( $( '#new_search_rsvp_form' ).length ){

                $( '#new_search_rsvp_form' ).on( 'click', function( e ){

                    e.preventDefault();

                    /**
                     *  Show loader
                     *  -----------
                     */
                    $( '#new_search_rsvp_form' ).append( '<i class="ms-2 fa fa-spinner fa-spin"></i>' );

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
                            'action'                    : 'sdweddingdirectory_rsvp_form',

                            'security'                  : SDWeddingDirectory_RSVP._security(),

                            'honeypot'                  : SDWeddingDirectory_RSVP._honeypot(),

                        },
                        success: function( PHP_RESPONSE ){

                            /**
                             *  Have lenth ?
                             *  ------------
                             */
                            if( $( '#sdweddingdirectory-rsvp-process' ).length ){

                                $( '#sdweddingdirectory-rsvp-process' ).html( PHP_RESPONSE.rsvp_form );
                            }

                            /**
                             *  Load Object Again
                             *  -----------------
                             */
                            SDWeddingDirectory_RSVP.init();

                            /**
                             *  Move RSVP Section
                             *  -----------------
                             */
                            SDWeddingDirectory_RSVP._move_rsvp_section();
                        }
                    });

                } );
            }
        },

        /**
         *  3. RSVP - Continues
         *  -------------------
         */
        rsvp_continues: function(){

            /**
             *  Have Length
             *  -----------
             */
            if( $( '#rsvp_continues' ).length ){

                $( '#rsvp_continues' ).on( 'click', function( e ){

                    e.preventDefault();

                    /**
                     *  Show loader
                     *  -----------
                     */
                    $( '#rsvp_continues' ).append( '<i class="ms-2 fa fa-spinner fa-spin"></i>' );

                    var _target       =   $( 'input[name=rsvp-guest-select]:checked' ),

                        _member_id    =   $( _target ).attr( 'data-member-id' ),

                        _guest_id     =   $( _target ).attr( 'data-unique-id' );

                        /**
                         *  RSVP - Guest Information
                         *  ------------------------
                         */
                        SDWeddingDirectory_RSVP.rsvp_guest_information(

                            _member_id,

                            _guest_id
                        );
                } );
            }
        },

        /**
         *  RSVP - Guest Information Fetch
         *  ------------------------------
         */
        rsvp_guest_information: function( guest_id, member_id ){

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
                    'action'                    : 'sdweddingdirectory_rsvp_guest_information',

                    /**
                     *  Guest Name
                     *  ----------
                     */
                    'post_id'                   : SDWeddingDirectory_RSVP._post_id(),

                    'member_id'                 : member_id,

                    'guest_id'                  : guest_id,

                    'security'                  : SDWeddingDirectory_RSVP._security(),

                    'honeypot'                  : SDWeddingDirectory_RSVP._honeypot(),
                },
                success: function( PHP_RESPONSE ){

                    /**
                     *  Have lenth ?
                     *  ------------
                     */
                    if( $( '#sdweddingdirectory-rsvp-process' ).length ){

                        $( '#sdweddingdirectory-rsvp-process' ).html( PHP_RESPONSE.data );
                    }

                    $( '#couple_rsvp_submit_layout_1' ).find( 'i' ).remove();

                    /**
                     *  If click on new search
                     *  ----------------------
                     */
                    SDWeddingDirectory_RSVP.rsvp_new_search();

                    /**
                     *  Submit RSVP
                     *  -----------
                     */
                    SDWeddingDirectory_RSVP.rsvp_submit();

                    /**
                     *  Select Option Script
                     *  --------------------
                     */
                    if( typeof SDWeddingDirectory_Elements === 'object' ){

                        SDWeddingDirectory_Elements.sdweddingdirectory_select_option();
                    }

                    /**
                     *  Move RSVP Section
                     *  -----------------
                     */
                    SDWeddingDirectory_RSVP._move_rsvp_section();
                }
            });
        },

        /**
         *  Submit RSVP
         *  -----------
         */
        rsvp_submit: function(){

            if( $( '#sdweddingdirectory-gurest-rsvp-submit' ).length ){

                $( '#sdweddingdirectory-gurest-rsvp-submit' ).on( 'click', function( e ){

                    e.preventDefault();

                    /**
                     *  Show loader
                     *  -----------
                     */
                    $( '#sdweddingdirectory-gurest-rsvp-submit' ).append( '<i class="ms-2 fa fa-spinner fa-spin"></i>' );

                    var data  =  [];

                    /**
                     *  Collection of Event
                     *  -------------------
                     */
                    $( '.event-id .guest-member-id .guest-id' ).map( function(){

                        var _member_id      =   $(this).closest( '.guest-member-id' ).attr( 'data-guest-id' ),

                            _guest_id       =   $(this).attr( 'data-guest-id' ),

                            _event_id       =   $(this).closest( '.event-id' ).attr( 'data-event-id' ),

                            _event_name     =   $(this).closest( '.event-id' ).find( '.event_name' ).html(),

                            _event_meal     =   $(this).find( '.sdweddingdirectory_event_meals' ).length && _is_empty( $(this).find( '.sdweddingdirectory_event_meals' ).val() )

                                                ?   $(this).find( '.sdweddingdirectory_event_meals' ).val()

                                                :   parseInt( '0' ),

                            _is_attended    =   $(this).find( 'input.guest_attended' ).length && _is_empty( $(this).find( 'input.guest_attended:checked' ).val() )

                                            ?   parseInt( $(this).find( 'input.guest_attended:checked' ).val() )

                                            :   parseInt( 0 );

                            /**
                             *  Push data in guest unique id
                             *  ----------------------------
                             */
                            data.push( {

                                guest_id : _guest_id,

                                event_name  :  _event_name,

                                guest_invited : _is_attended,

                                meal: _event_meal,

                                event_unique_id : _event_id

                            } );
                    } );

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
                            'action'                        :   'sdweddingdirectory_guest_submited_rsvp_info',

                            /**
                             *  Guest Name
                             *  ----------
                             */
                            'post_id'                       :   SDWeddingDirectory_RSVP._post_id(),

                            'guest_rsvp'                    :   data,

                            'guest_comment'                 :   $( '#guest_comment' ).val(),

                            'request_handling_member'        :   $( '#request_handling_member' ).val(),

                            'security'                      :   SDWeddingDirectory_RSVP._security(),

                            'honeypot'                      :   SDWeddingDirectory_RSVP._honeypot()
                        },
                        success: function( PHP_RESPONSE ){

                            /**
                             *  Have lenth ?
                             *  ------------
                             */
                            if( $( '#sdweddingdirectory-rsvp-process' ).length ){

                                $( '#sdweddingdirectory-rsvp-process' ).html( PHP_RESPONSE.data );
                            }

                            $( '#sdweddingdirectory-gurest-rsvp-submit' ).find( 'i' ).remove();

                            /**
                             *  If click on new search
                             *  ----------------------
                             */
                            SDWeddingDirectory_RSVP.rsvp_new_search();

                            /**
                             *  Move RSVP Section
                             *  -----------------
                             */
                            SDWeddingDirectory_RSVP._move_rsvp_section();
                        }
                    });

                } );
            }
        },

        /**
         *  Object load
         *  -----------
         */
        init: function(){

            /**
             *  1. RSVP - Website template
             *  --------------------------
             */
            this.sdweddingdirectory_rsvp_submit();
        }
    }

    /**
     *  Document Ready ?
     *  ----------------
     */
    $( document ).ready( function(){ SDWeddingDirectory_RSVP.init(); } );

})(jQuery);
