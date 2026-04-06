(function($) {

    "use strict";

    var SDWeddingDirectory_Guest_List = {

        /**
         *  1. Button Click to Open Sidebar Form
         *  ------------------------------------
         */
        open_form_button_click: function(){

            if( $( '.sdweddingdirectory_open_form' ).length ){

                $( '.sdweddingdirectory_open_form' ).map( function(){

                    /**
                     *  Sidebar For Open with Button Click
                     *  ----------------------------------
                     */
                    var data_class = $(this).attr( 'data-class' ),

                        form_class = '',

                        click_event = '';

                    if( data_class !== '' && data_class !== undefined && data_class !== null ){

                        form_class = SDWeddingDirectory_Guest_List.get_data_ids( data_class );
                    }

                    if( form_class !== '' && form_class !== undefined && form_class !== null ){

                        click_event = form_class;

                    }else{

                        click_event = $( '#' + $(this).attr( 'id' ) );
                    }

                    if( $( '#' + $(this).attr( 'data-form' ) ).length) {

                        $( '#' + $(this).attr( 'data-form' ) ).slideReveal({

                            trigger: $( click_event ),

                            position: "right",

                            push: false,

                            overlay: true,

                            width: $(this).attr( 'data-width' ) ? $(this).attr( 'data-width' ) : 375,

                            speed: 450

                        });
                    }

                } );
            }
        },

        get_data_ids: function( elm ){

            return $("."+elm).map(function() {return '#'+this.id;}).get().join(',');
        },

        /**
         *  Attach nonce to guest-list AJAX requests when missing
         *  -----------------------------------------------------
         */
        attach_ajax_security: function(){

            var _guest_actions = [
                'sdweddingdirectory_guest_list_menu_removed',
                'sdweddingdirectory_guest_list_menu_add',
                'sdweddingdirectory_group_item_removed',
                'sdweddingdirectory_guest_list_group_add',
                'sdweddingdirectory_event_list_removed',
                'sdweddingdirectory_event_list_add',
                'sdweddingdirectory_create_new_event',
                'sdweddingdirectory_create_new_guest_data',
                'sdweddingdirectory_guest_event_meal_action',
                'sdweddingdirectory_guest_event_attendance_action',
                'sdweddingdirectory_get_guest_info_action',
                'sdweddingdirectory_remove_guest_info_action',
                'sdweddingdirectory_update_guest_data',
                'sdweddingdirectory_update_event_form_data',
                'sdweddingdirectory_update_event_data',
                'sdweddingdirectory_remove_event_data',
                'sdweddingdirectory_guest_group_action',
                'guest_list_csv_download',
                'sdweddingdirectory_event_summary_load',
                'sdweddingdirectory_invitation_sent',
                'sdweddingdirectory_guest_list_import',
                'sdweddingdirectory_guest_email_update',
                'sdweddingdirectory_couple_sending_guest_rsvp'
            ];

            $.ajaxPrefilter( function( options, originalOptions ){

                if( ! originalOptions || typeof originalOptions.data !== 'object' || ! originalOptions.data ){

                    return;
                }

                var _data = originalOptions.data;

                if( typeof _data.action !== 'string' ){

                    return;
                }

                var _is_guest_list_action = $.inArray( _data.action, _guest_actions ) !== -1;

                if( ! _is_guest_list_action || _data.security ){

                    return;
                }

                if( SDWEDDINGDIRECTORY_AJAX_OBJ && SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_guest_list_security ){

                    _data.security = SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_guest_list_security;
                    options.data = _data;
                }
            } );
        },

        /**
         *  2. Menu : Removed
         *  -----------------
         */
        menu_item_removed: function(){

            if( $( '#sdweddingdirectory_list_of_menu_items li' ).length ){

                $( '#sdweddingdirectory_list_of_menu_items li a' ).on( 'click', function(e){

                        e.preventDefault();

                        var menu_unique_id = $( this ).attr( 'data-remove' );

                        $(this).closest( 'li' ).remove();

                        $.ajax({
                            type        : 'POST',
                            url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                            dataType    : 'json',
                            data:       {

                                /**
                                 *  1. Action & Security
                                 *  --------------------
                                 */
                                'action'                  : 'sdweddingdirectory_guest_list_menu_removed',
                                'menu_unique_id'          : menu_unique_id
                            },
                            beforeSend: function(){

                            },
                            success: function ( PHP_RESPONSE ) {

                            },
                            error: function (xhr, ajaxOptions, thrownError) {

                                console.log( 'SDWeddingDirectory_Error_Menu_Item_Removed' );
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
         *  3. Menu : Add
         *  -------------
         */
        menu_item_added: function(){

            if( $( '#guest_list_add_menu_item_button' ).length ){

                $( '#guest_list_add_menu_item_button' ).on( 'click', function(e){

                    e.preventDefault();

                    var input_value = $( '#guest_list_add_menu_item' ).val();

                    if( input_value == '' && input_value == null && input_value == undefined ){

                        $( '#guest_list_add_menu_item' ).focus();
                        return false;

                    }else{

                        $.ajax({
                            type        : 'POST',
                            url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                            dataType    : 'json',
                            data:       {

                                /**
                                 *  1. Action & Security
                                 *  --------------------
                                 */
                                'action'     : 'sdweddingdirectory_guest_list_menu_add',
                                'menu_list'  : input_value
                            },
                            beforeSend: function(){

                            },
                            success: function ( PHP_RESPONSE ) {

                                if( $( '#sdweddingdirectory_list_of_menu_items' ).length ){

                                    $( '#sdweddingdirectory_list_of_menu_items' ).append( PHP_RESPONSE.menu_list );
                                }

                                if( $( '#guest_list_add_menu_item' ).length ){

                                    $( '#guest_list_add_menu_item' ).val( '' ).focus();
                                }

                                SDWeddingDirectory_Guest_List.menu_item_removed();
                            },
                            error: function (xhr, ajaxOptions, thrownError) {

                                console.log( 'SDWeddingDirectory_Error_Menu_Item_Removed' );
                                console.log(xhr.status);
                                console.log(thrownError);
                                console.log(xhr.responseText);
                            },
                            complete: function( event, request, settings ){

                            }
                        });

                    }

                } );
            }
        },

        /**
         *  4. Group : Removed
         *  ------------------
         */
        group_item_removed: function(){

            if( $( '#group_list_section li' ).length ){

                $( '#group_list_section li a' ).on( 'click', function(e){

                        e.preventDefault();

                        var unique_id = $( this ).attr( 'data-remove' );

                        $(this).closest( 'li' ).remove();

                        $.ajax({
                            type        : 'POST',
                            url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                            dataType    : 'json',
                            data:       {

                                /**
                                 *  1. Action & Security
                                 *  --------------------
                                 */
                                'action'                  : 'sdweddingdirectory_group_item_removed',
                                'group_unique_id'         : unique_id
                            },
                            beforeSend: function(){

                            },
                            success: function ( PHP_RESPONSE ) {

                            },
                            error: function (xhr, ajaxOptions, thrownError) {

                                console.log( 'SDWeddingDirectory_Error_Menu_Item_Removed' );
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
         *  5. Group : Added
         *  ----------------
         */
        group_item_added: function(){

            if( $( '#sdweddingdirectory_guest_group_managment_form' ).length ){

                $( '#sdweddingdirectory_guest_group_managment_form' ).on( 'submit', function(e){

                    e.preventDefault();

                    var security    = $( '#sdweddingdirectory_guest_group_security' ).val(),
                        group_data  = $('.sdweddingdirectory_guest_group, .edit_guest_group' ),
                        group_name  = new Array();

                        $( group_data ).map( function( e ){

                            $(this).val( $(this).val() );

                        } );

                        /**
                         *  After get data
                         *  --------------
                         */
                        $( '#guest_group_list_section li' ).map( function(e){

                            group_name.push({

                                'title'             :   $(this).find( 'span' ).html(),
                                'group_name'        :   $(this).find( 'span' ).html(),
                                'group_unique_id'   :   ''
                            });

                        } );

                    $.ajax({
                        type        : 'POST',
                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                        dataType    : 'json',
                        data:       {

                            /**
                             *  1. Action & Security
                             *  --------------------
                             */
                            'action'      : 'sdweddingdirectory_guest_list_group_add',
                            'group_name'  : group_name,
                            'security'    : security
                        },
                        beforeSend: function(){

                        },
                        success: function ( PHP_RESPONSE ) {

                            /**
                             *  Alert Success
                             *  -------------
                             */
                            sdweddingdirectory_alert( PHP_RESPONSE );

                            /**
                             *  Hide Sidebar panel
                             *  ------------------
                             */
                            $( '.sliding-panel' ).slideReveal( 'hide' );

                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Menu_Item_Removed' );
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
         *  6. Group : Removed
         *  ------------------
         */
        event_item_removed: function(){

            if( $( '#event_list_section li' ).length ){

                $( '#event_list_section li a' ).on( 'click', function(e){

                        e.preventDefault();

                        var unique_id = $( this ).attr( 'data-remove' );

                        $(this).closest( 'li' ).remove();

                        $.ajax({
                            type        : 'POST',
                            url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                            dataType    : 'json',
                            data:       {

                                /**
                                 *  1. Action & Security
                                 *  --------------------
                                 */
                                'action'                  : 'sdweddingdirectory_event_list_removed',
                                'event_unique_id'         : unique_id
                            },
                            beforeSend: function(){

                            },
                            success: function ( PHP_RESPONSE ) {

                            },
                            error: function (xhr, ajaxOptions, thrownError) {

                                console.log( 'SDWeddingDirectory_Error_Menu_Item_Removed' );
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
         *  7. Group : Added
         *  ----------------
         */
        event_item_added: function(){

            if( $( '#event_list_add_button' ).length ){

                $( '#event_list_add_button' ).on( 'click', function(e){

                    e.preventDefault();

                    var input_value = $( '#event_list_name' ).val();

                    if( input_value == '' && input_value == null && input_value == undefined ){

                        $( '#event_list_name' ).focus();

                        return false;

                    }else{

                        $.ajax({
                            type        : 'POST',
                            url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                            dataType    : 'json',
                            data:       {

                                /**
                                 *  1. Action & Security
                                 *  --------------------
                                 */
                                'action'     : 'sdweddingdirectory_event_list_add',
                                'event_list'  : input_value
                            },
                            beforeSend: function(){

                            },
                            success: function ( PHP_RESPONSE ) {

                                if( $( '#event_list_section' ).length ){

                                    $( '#event_list_section' ).append( PHP_RESPONSE.event_list );
                                }

                                if( $( '#event_list_name' ).length ){

                                    $( '#event_list_name' ).val( '' ).focus();
                                }

                                SDWeddingDirectory_Guest_List.event_item_removed();
                            },
                            error: function (xhr, ajaxOptions, thrownError) {

                                console.log( 'SDWeddingDirectory_Error_Menu_Item_Removed' );
                                console.log(xhr.status);
                                console.log(thrownError);
                                console.log(xhr.responseText);
                            },
                            complete: function( event, request, settings ){

                            }
                        });

                    }

                } );
            }
        },

        /**
         *  8. Meal Box Events Managmnet
         *  ----------------------------
         */
        meal_box_event_managment: function(){

            if( $( '.meal_option_list_box' ).length ){

                $( '.meal_option_list_box' ).map( function(){

                        /**
                         *  Handler
                         *  -------
                         */
                        var list_box_id         =   $(this).find( 'ul' ).attr( 'id' ),
                            write_item_name     =   $(this).find( 'input' ).attr( 'id' ),
                            add_item_btn        =   $(this).find( 'button' ).attr( 'id' );

                        /** 
                         *  Removed Mean
                         *  ------------
                         */
                        if( $( '#'+list_box_id+' li a' ).length ){

                            $( '#'+list_box_id+' li a' ).on( 'click', function(e){

                                e.preventDefault();

                                $(this).closest( 'li' ).remove();

                            } );
                        }

                        /**
                         *  Add New Mean Item
                         *  -----------------
                         */
                        if( $( '#'+add_item_btn ).length ){

                            $( '#'+add_item_btn ).on( 'click', function(e){

                                e.preventDefault();

                                var input_id    = $( '#'+ write_item_name ),
                                    input_val   = $( input_id ).val();

                                if( input_val == '' && input_val == null && input_val == undefined ){

                                    $( input_id ).focus();

                                    return false;

                                }else{

                                    if( $( '#'+list_box_id ).length && input_val !== null && input_val !== '' && input_val !== undefined ){

                                        $( '#'+list_box_id ).append( 

                                            '<li class="list-group-item d-flex justify-content-between align-items-center">'
                                                +'<span>'+ input_val +'</span>'
                                                +'<a href="javascript:" class="action-links"><i class="fa fa-trash"></i></a>'
                                            +'</li>'
                                        );

                                        $( input_id ).val( '' ).focus();

                                        SDWeddingDirectory_Guest_List.meal_box_event_managment();
                                    }
                                }

                            } );
                        }
                } );
            }
        },

        /**
         *  9. Create New Event
         *  -------------------
         */
        create_event: function(){

            if( $( '#sdweddingdirectory_guest_add_new_event_form' ).length ){

                $( '#sdweddingdirectory_guest_add_new_event_form' ).on( 'submit', function(e){

                    e.preventDefault();

                    var form_id     = $( '#' + $(this).attr( 'id' ) ),
                        event_name  = $( '#add_new_guest_event' ).val(),
                        event_icon  = $( '#event_icon' ).val(),
                        have_meal   = $( '#event_have_meal_option' ).prop('checked') == true ? 1 : 0,
                        event_meal  = new Array();

                    /**
                     *  After get data
                     *  --------------
                     */
                    $( '#add_new_event_meals_options li' ).map( function(){

                        event_meal.push({

                            'title'     : $(this).find( 'span' ).html(),

                            'menu_list' : $(this).find( 'span' ).html()

                        });

                    } );

                    /**
                     *  Create New Event
                     *  ----------------
                     */
                    $.ajax({
                        type        : 'POST',
                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                        dataType    : 'json',
                        data:       {

                            /**
                             *  1. Action & Security
                             *  --------------------
                             */
                            'action'     : 'sdweddingdirectory_create_new_event',
                            'event_meal' : event_meal,
                            'event_list' : event_name,
                            'have_meal'  : have_meal,
                            'event_icon' : event_icon,
                        },
                        beforeSend: function(){

                        },
                        success: function ( PHP_RESPONSE ) {

                            sdweddingdirectory_alert( PHP_RESPONSE );

                            console.log( PHP_RESPONSE );

                            setTimeout( function(){ window.location.reload(); }, 2000 );
                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Menu_Item_Removed' );
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
         *  12. Add Guest in Database
         *  -------------------------
         */
        create_new_guest: function(){

            if( $('#sdweddingdirectory_add_new_guest_form').length ){

                $( '#sdweddingdirectory_add_new_guest_form' ).on( 'submit', function(e){

                    /**
                     *  Event Start
                     *  -----------
                     */
                    e.preventDefault();

                    /**
                     *  Variable
                     *  --------
                     */
                    var     form_id                 =       '#' + $(this).attr( 'id' ),

                            guest_member_list       =       new Array(),

                            guest_event             =       new Array(),

                            submit_btn              =       $( form_id ).find( 'button[type=submit]' );

                    /**
                     *  Have Guest fields lenght ?
                     *  --------------------------
                     */
                    if( $('#add-new-guest .col').length ){

                        /**
                         *  Loop
                         *  ----
                         */
                        $('#add-new-guest .col').map(function( index ) {

                            guest_member_list.push( {

                                'first_name'   : $(this).find('.first_name').val(),

                                'last_name'    : $(this).find('.last_name').val(),

                            } );

                        });
                    }

                    /**
                     *  Have Events ?
                     *  -------------
                     */
                    if( $( '.sdweddingdirectory_event' ).length ){

                        $( '.sdweddingdirectory_event' ).map( function(){

                            guest_event.push( {

                                'event_name'        : $(this).next().text(),

                                'guest_invited'     : $(this).prop('checked') == true ? 1 : 0,

                                'meal'              : '',

                                'event_unique_id'   : $(this).attr( 'data-event-unique-id' ),

                            } );

                        } );
                    }

                    /**
                     *  Create New Event
                     *  ----------------
                     */
                    $.ajax({

                        type        :   'POST',

                        url         :   SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        dataType    :   'json',

                        data        :   {

                            /**
                             *  1. Action & Security
                             *  --------------------
                             */
                            'action'                :   'sdweddingdirectory_create_new_guest_data',

                            'security'              :   $( form_id + ' #add_new_guest_security' ).val(),

                            /**
                             *  2. Fields
                             *  ---------
                             */
                            'guest_member_list'     :   guest_member_list,

                            'guest_event'           :   guest_event,

                            'guest_age'             :   $( form_id + ' #guest_age' ).val(),

                            'guest_group'           :   $( form_id + ' #guest_group' ).val(),

                            'guest_need_hotel'      :   $( form_id + ' #need_hotel' ).prop('checked') == true ? 1 : 0,

                            'guest_email'           :   $( form_id + ' #guest_email' ).val(),

                            'guest_contact'         :   $( form_id + ' #guest_contact' ).val(),

                            'guest_address'         :   $( form_id + ' #guest_address' ).val(),

                            'guest_city'            :   $( form_id + ' #guest_city' ).val(),

                            'guest_state'           :   $( form_id + ' #guest_state' ).val(),

                            'guest_zip_code'        :   $( form_id + ' #guest_zip_code' ).val(),
                        },

                        beforeSend: function(){

                            $( submit_btn ).append( '<i class="ms-2 fa fa-spinner fa-spin"></i>' );
                        },

                        success: function ( PHP_RESPONSE ) {

                            $( submit_btn ).find( 'i' ).remove();

                            sdweddingdirectory_alert( PHP_RESPONSE );

                            setTimeout( function(){ window.location.reload(); }, 1000 );
                        },

                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory / Couple Tools / Guest list / Create New Guest Script Error' );

                            console.log( 'Error Function Name : create_new_guest' );

                            console.log( xhr.status );

                            console.log( thrownError );

                            console.log( xhr.responseText );
                        },

                        complete: function( event, request, settings ){

                        }

                    } );

                } );
            }
        },

        /**
         *  13. Event Attendance Dropdown
         *  -----------------------------
         */
        event_guest_meal_managment: function(){

            if( $( '.sdweddingdirectory_event_meals' ).length ){

                $( '.sdweddingdirectory_event_meals' ).on( 'change', function( e ){

                    e.preventDefault();

                    var guest_unique_id     =   $( this ).attr( 'data-guest-id' ),
                        event_unique_id     =   $( this ).attr( 'data-event-id' ),
                        member_meal         =   $( this ).val();

                    /**
                     *  Create New Event
                     *  ----------------
                     */
                    $.ajax({
                        type        : 'POST',
                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                        dataType    : 'json',
                        data:       {

                            /**
                             *  1. Action & Security
                             *  --------------------
                             */
                            'action'            : 'sdweddingdirectory_guest_event_meal_action',

                            /**
                             *  2. Fields
                             *  ---------
                             */
                            'event_unique_id'   : event_unique_id,
                            'guest_unique_id'   : guest_unique_id,
                            'member_meal'       : member_meal,
                        },
                        beforeSend: function(){

                        },
                        success: function ( PHP_RESPONSE ) {

                            console.log( PHP_RESPONSE );

                            sdweddingdirectory_alert( PHP_RESPONSE );

                            // setTimeout( function(){ window.location.reload(); }, 2000 );
                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Menu_Item_Removed' );
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
         *  14. Event Attendance Dropdown
         *  -----------------------------
         */
        event_guest_attandence: function(){

            if( $( '.sdweddingdirectory_event_attendance' ).length ){

                $( '.sdweddingdirectory_event_attendance' ).on( 'change', function( e ){

                    e.preventDefault();

                    var guest_unique_id     =   $( this ).attr( 'data-guest-id' ),
                        event_unique_id     =   $( this ).attr( 'data-event-id' ),
                        guest_invited       =   $( this ).val();

                    /**
                     *  Create New Event
                     *  ----------------
                     */
                    $.ajax({
                        type        : 'POST',
                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                        dataType    : 'json',
                        data:       {

                            /**
                             *  1. Action & Security
                             *  --------------------
                             */
                            'action'            : 'sdweddingdirectory_guest_event_attendance_action',

                            /**
                             *  2. Fields
                             *  ---------
                             */
                            'event_unique_id'     : event_unique_id,
                            'guest_unique_id'     : guest_unique_id,
                            'guest_invited'       : guest_invited,

                        },

                        beforeSend: function(){

                        },

                        success: function ( PHP_RESPONSE ) {

                            sdweddingdirectory_alert( PHP_RESPONSE );

                            /**
                             *  Update Event Counter
                             *  --------------------
                             */
                            SDWeddingDirectory_Guest_List.update_counter_notification( PHP_RESPONSE.counter );
                        },

                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Menu_Item_Removed' );
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
         *  - Update Counter Notification
         *  -----------------------------
         */
        update_counter_notification: function( data ){

            if( data !== '' ){

                $.map( data, function( val, element ) {

                    if( $( '.' + element ).length && val !== '' && val !== null && val !== undefined ){

                        $( '.' + element ).html( val );
                    }
                });
            }
        },

        /**
         *  15. Get Edit Guest Information
         *  ------------------------------
         */
        get_edit_guest_form_data: function(){

            if( $( '.guest_edit' ) ){

                $( '.guest_edit' ).on( 'click', function(e){

                    e.preventDefault();

                    var guest_unique_id     =   $( this ).attr( 'data-guest-id' );

                    /**
                     *  Create New Event
                     *  ----------------
                     */
                    $.ajax({

                        type        : 'POST',

                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        dataType    : 'json',

                        data:       {

                            /**
                             *  1. Action & Security
                             *  --------------------
                             */
                            'action'            : 'sdweddingdirectory_get_guest_info_action',

                            /**
                             *  2. Fields
                             *  ---------
                             */
                            'guest_unique_id'     : guest_unique_id,
                        },

                        beforeSend: function(){

                        },

                        success: function ( PHP_RESPONSE ) {

                            /**
                             *  Request Missing Info
                             *  --------------------
                             */
                            if( $( 'a.edit_form_request_missing_info_link' ).length ){

                                $( 'a.edit_form_request_missing_info_link' ).attr( 'data-href', PHP_RESPONSE.missing_info_link );
                            }

                            $.map( PHP_RESPONSE.input, function( val, element ) {

                                if( $( '#' + element ).length ){

                                    $( '#' + element ).val( val );
                                }
                                
                            });

                            $.map( PHP_RESPONSE.textarea, function( val, element ) {

                                if( $( '#' + element ).length ){

                                    $( '#' + element ).val( val );
                                }
                                
                            });

                            $.map( PHP_RESPONSE.select, function( val, element ) {

                                if( $( '#' + element ).length && val !== '' && val !== null && val !== undefined ){

                                    $( '#' + element ).val( val );
                                }

                            });

                            /**
                             *  -------------------------------------------------------------------------------------------------------------------
                             *  @link : https://stackoverflow.com/questions/25143083/check-uncheck-bootstrap-checkboxes-with-jquery#mainbar
                             *  @link : https://stackoverflow.com/questions/25143083/check-uncheck-bootstrap-checkboxes-with-jquery#answer-30660798
                             *  -------------------------------------------------------------------------------------------------------------------
                             *  Checkbox Checked
                             *  ----------------
                             */
                            $.map( PHP_RESPONSE.checkbox, function( val, element ) {

                                if( $( '#' + element ).length ){

                                    if( val == 1 ){

                                        $( '#' + element ).prop('checked', true);

                                    }else{

                                        $( '#' + element ).prop('checked', false );
                                    }
                                }
                                
                            });
                        },

                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Menu_Item_Removed' );
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
         *  16. Remove Guest Information
         *  ----------------------------
         */
        removed_guest_data: function(){

            if( $( '.guest_removed' ) ){

                $( '.guest_removed' ).on( 'click', function(e){

                    e.preventDefault();

                    if( ! confirm( $(this).attr( 'data-guest-removed-alert' ) ) ){

                        return false;
                    }

                    SDWeddingDirectory_Guest_List.removed_member_in_guest_list(

                        // 1- Guest ID
                        $( this ).attr( 'data-guest-id' )
                    );

                } );
            }

            if( $( '#removed_guest_member_button' ) ){

                $( '#removed_guest_member_button' ).on( 'click', function(e){

                    e.preventDefault();

                    if( ! confirm( $(this).attr( 'data-guest-removed-alert' ) ) ){

                        return false;
                    }

                    SDWeddingDirectory_Guest_List.removed_member_in_guest_list(

                        // 1- Guest ID
                        $( '#edit_guest_unique_id' ).val()
                    );

                    $( '.sliding-panel' ).slideReveal("hide");

                } );
            }
        },

        /**
         *  Remove Guest AJAX
         *  -----------------
         */
        removed_member_in_guest_list: function( guest_unique_id ){

            /**
             *  Removed Guest Member
             *  --------------------
             */
            $.ajax({
                type        : 'POST',
                url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                dataType    : 'json',
                data:       {

                    /**
                     *  1. Action & Security
                     *  --------------------
                     */
                    'action'            : 'sdweddingdirectory_remove_guest_info_action',

                    /**
                     *  2. Fields
                     *  ---------
                     */
                    'guest_unique_id'     : guest_unique_id,

                },
                beforeSend: function(){

                    if( $( '.'+guest_unique_id ).length ){

                        $( '.'+guest_unique_id ).attr( 'style', 'background:#dc354533' );
                    }
                },
                success: function ( PHP_RESPONSE ) {

                    if( $( '.'+guest_unique_id ).length ){

                        $( '.'+guest_unique_id ).remove();
                    }

                    $.map( PHP_RESPONSE.counter, function( index ) {

                        /**
                         *  Update Event Counter
                         *  --------------------
                         */
                        SDWeddingDirectory_Guest_List.update_counter_notification( index );

                    });
                },
                error: function (xhr, ajaxOptions, thrownError) {

                    console.log( 'SDWeddingDirectory_Error_Menu_Item_Removed' );
                    console.log(xhr.status);
                    console.log(thrownError);
                    console.log(xhr.responseText);
                },
                complete: function( event, request, settings ){

                }
            });
        },

        /**
         *  17. Update Guest Information
         *  ----------------------------
         */
        update_guest_data : function(){

            if( $( '#sdweddingdirectory_edit_guest_member_form' ).length ){

                $( '#sdweddingdirectory_edit_guest_member_form' ).on( 'submit', function(e){

                    e.preventDefault();

                    var form_id = '#' + $(this).attr( 'id' );

                    var guest_event = new Array();

                    if( $( '.edit_guest_events_list' ).length ){

                        $( '.edit_guest_events_list' ).map( function(){

                            guest_event.push({

                                'event_name'        : $(this).attr( 'data-event-name' ),

                                'guest_invited'     : $(this).find( 'select[name=guest_invited]' ).val(),

                                'meal'              : $(this).find( 'select[name=meal]' ).val(),

                                'event_unique_id'   : $(this).attr( 'data-event-unique-id' ),
                            });

                        } );
                    }

                    /**
                     *  Create New Event
                     *  ----------------
                     */
                    $.ajax({
                        type        : 'POST',
                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                        dataType    : 'json',
                        data:       {

                            /**
                             *  1. Action & Security
                             *  --------------------
                             */
                            'action'            : 'sdweddingdirectory_update_guest_data',

                            'security'          : $( form_id + ' #add_new_guest_security' ).val(),

                            /**
                             *  2. Fields
                             *  ---------
                             */
                            'first_name'        : $( form_id + ' #edit_first_name' ).val(),

                            'last_name'         : $( form_id + ' #edit_last_name' ).val(),

                            'guest_event'       : guest_event,

                            'guest_age'         : $( form_id + ' #edit_guest_age' ).val(),

                            'guest_group'       : $( form_id + ' #edit_guest_group' ).val(),

                            'guest_need_hotel'  : $( form_id + ' #edit_guest_need_hotel' ).prop('checked') == true ? 1 : 0,

                            'guest_email'       : $( form_id + ' #edit_guest_email' ).val(),

                            'guest_contact'     : $( form_id + ' #edit_guest_contact' ).val(),

                            'guest_address'     : $( form_id + ' #edit_guest_address' ).val(),

                            'guest_city'        : $( form_id + ' #edit_guest_city' ).val(),

                            'guest_state'       : $( form_id + ' #edit_guest_state' ).val(),

                            'guest_zip_code'    : $( form_id + ' #edit_guest_zip_code' ).val(),

                            'guest_unique_id'   : $( form_id + ' #edit_guest_unique_id' ).val(),

                            'guest_comment'     : $( form_id + ' #edit_guest_comment' ).val()

                        },
                        beforeSend: function(){

                        },
                        success: function ( PHP_RESPONSE ) {

                            sdweddingdirectory_alert( PHP_RESPONSE );
                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Menu_Item_Removed' );
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
         *  18. Edit Event Data Update in Form Details
         *  ------------------------------------------
         */
        edit_event_form: function(){

            if( $( '.open_edit_event' ).length ){

                $( '.open_edit_event' ).on( 'click', function( e ){

                    var form_id             =   $(this).attr( 'id' ),
                        event_unique_id     =   $(this).attr( 'data-event-unique-id' );

                    e.preventDefault();

                    /**
                     *  Update Event Data in Form
                     *  -------------------------
                     */
                    $.ajax({
                        type        : 'POST',
                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                        dataType    : 'json',
                        data:       {

                            /**
                             *  1. Action & Security
                             *  --------------------
                             */
                            'action'            : 'sdweddingdirectory_update_event_form_data',

                            /**
                             *  2. Fields
                             *  ---------
                             */
                            'event_unique_id'    : event_unique_id,

                        },
                        beforeSend: function(){

                        },
                        success: function ( PHP_RESPONSE ) {

                            sdweddingdirectory_alert( PHP_RESPONSE );

                            /**
                             *  Update input
                             *  ------------
                             */
                            $.map( PHP_RESPONSE.input, function( val, element ) {

                                if( $( '#' + element ).length ){

                                    $( '#' + element ).val( val );
                                }
                                
                            });

                            /**
                             *  Update HTML ( inner HTML )
                             *  --------------------------
                             */
                            $.map( PHP_RESPONSE.html, function( val, element ) {

                                if( $( '#' + element ).length ){

                                    $( '#' + element ).html( val );
                                }
                                
                            });

                            /**
                             *  Update Checkbox
                             *  ---------------
                             */
                            $.map( PHP_RESPONSE.checkbox, function( val, element ) {

                                if( $( '#' + element ).length ){

                                    if( val == 1 ){

                                        $( '#' + element ).prop('checked', true);

                                    }else{

                                        $( '#' + element ).prop('checked', false );
                                    }
                                }
                            });

                            /**
                             *  Update Select Options
                             *  ---------------------
                             */
                            $.map( PHP_RESPONSE.select, function( val, element ) {

                                if( $( '#' + element ).length && val !== '' && val !== null && val !== undefined ){

                                    $( '#' + element ).val( val );
                                }

                            });

                            SDWeddingDirectory_Guest_List.meal_box_event_managment();

                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Menu_Item_Removed' );
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
         *  19. Update Event Data
         *  ---------------------
         */
        update_event_data: function(){

            if( $( '#sdweddingdirectory_edit_event_form' ).length ){

                $( '#sdweddingdirectory_edit_event_form' ).on( 'submit', function( e ){

                    e.preventDefault();

                    var form_id             =   $( '#' + $(this).attr( 'id' ) ),

                        event_name          =   $( '#edit_event_name' ).val(),

                        event_icon          =   $( '#edit_event_icon' ).val(),

                        have_meal           =   $( '#edit_have_meal' ).prop('checked') == true ? 1 : 0,

                        event_unique_id     =   $('#edit_event_unique_id').val(),

                        event_meal          =   new Array();

                    /**
                     *  After get data
                     *  --------------
                     */
                    $( '#edit_event_meal li' ).map( function(){

                        event_meal.push({

                            'title'     : $(this).find( 'span' ).html(),

                            'menu_list' : $(this).find( 'span' ).html()

                        });

                    } );

                    /**
                     *  Update Event Data in Form
                     *  -------------------------
                     */
                    $.ajax({
                        type        : 'POST',
                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                        dataType    : 'json',
                        data:       {

                            /**
                             *  1. Action & Security
                             *  --------------------
                             */
                            'action'            : 'sdweddingdirectory_update_event_data',

                            /**
                             *  2. Fields
                             *  ---------
                             */
                            'event_meal'        :   event_meal,
                            'event_list'        :   event_name,
                            'have_meal'         :   have_meal,
                            'event_icon'        :   event_icon,
                            'event_unique_id'   :   event_unique_id,
                        },
                        beforeSend: function(){

                        },
                        success: function ( PHP_RESPONSE ) {

                            sdweddingdirectory_alert( PHP_RESPONSE );

                            /**
                             *  Update HTML ( inner HTML )
                             *  --------------------------
                             */
                            $.map( PHP_RESPONSE.html, function( val, element ) {

                                if( $( '.' + element ).length ){

                                    $( '.' + element ).html( val );
                                }
                                if( $( '#' + element ).length ){

                                    $( '#' + element ).html( val );
                                }
                            });

                            /**
                             *  Sliding Hide
                             *  ------------
                             */
                            $( '.sliding-panel' ).slideReveal( 'hide' );
                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Menu_Item_Removed' );
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
         *  20. Remove Event Data
         *  ---------------------
         */
        remove_event_data: function(){

            if( $( '#edit_event_remove' ).length ){

                $( '#edit_event_remove' ).on( 'click', function( e ){

                    if( ! confirm( $(this).attr( 'data-remove-event-alert' ) ) ){

                        return false;
                    }

                    e.preventDefault();

                    var event_unique_id     =   $('#edit_event_unique_id').val();

                    /**
                     *  Update Event Data in Form
                     *  -------------------------
                     */
                    $.ajax({
                        type        : 'POST',
                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                        dataType    : 'json',
                        data:       {

                            /**
                             *  1. Action & Security
                             *  --------------------
                             */
                            'action'            : 'sdweddingdirectory_remove_event_data',

                            /**
                             *  2. Fields
                             *  ---------
                             */
                            'event_unique_id'   :   event_unique_id,
                        },
                        beforeSend: function(){

                        },
                        success: function ( PHP_RESPONSE ) {

                            sdweddingdirectory_alert( PHP_RESPONSE );

                            if( $( '.sliding-panel' ).length ){

                                $( '.sliding-panel' ).slideReveal("hide");
                            }

                            setTimeout( function(){ window.location.reload(); }, 2000 );
                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Menu_Item_Removed' );
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
         *  Data Table Library Applying
         *  ----------------------------
         */
        datatable: function(){

            if( $('.sdweddingdirectory-datatable').length ){

                $('.sdweddingdirectory-datatable').map( function( i ){

                    $( '#' + $(this).attr('id') ).DataTable({

                        "searching": false,

                        "lengthChange": false
                    });

                } );
            }
        },

        /**
         *  22. Gues Group Data Update
         *  --------------------------
         */
        update_guest_group: function(){

            if( $( '.sdweddingdirectory_guest_group' ).length ){

                $( '.sdweddingdirectory_guest_group' ).on( 'change', function(e){

                    e.preventDefault();

                    var guest_unique_id     =   $( this ).attr( 'data-guest-id' ),
                        guest_group         =   $( this ).val();

                    /**
                     *  Create New Event
                     *  ----------------
                     */
                    $.ajax({
                        type        : 'POST',
                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                        dataType    : 'json',
                        data:       {

                            /**
                             *  1. Action & Security
                             *  --------------------
                             */
                            'action'            : 'sdweddingdirectory_guest_group_action',

                            /**
                             *  2. Fields
                             *  ---------
                             */
                            'guest_unique_id'   : guest_unique_id,

                            'guest_group'       : guest_group,
                        },
                        beforeSend: function(){

                        },
                        success: function ( PHP_RESPONSE ) {

                            sdweddingdirectory_alert( PHP_RESPONSE );
                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Menu_Item_Removed' );
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
         *  23. Download CSV File
         *  ---------------------
         */
        csv_download: function(){

            if( $( '.export_csv' ).length ){

                $( '.export_csv' ).on( 'click', function(e){

                        e.preventDefault();

                        var have_id        =  $(this).attr( 'data-event-unique-id' ),
                            event_name     =  $(this).attr( 'data-event-name' ),
                            event_id       =  '';

                        if( have_id !== '' && have_id !== null && have_id !== undefined ){

                            event_id    =   have_id;
                        }

                        if( event_name == '' || event_name == null || event_name == undefined ){

                            event_name = 'guest-list';
                        }

                        $.ajax({
                            type        : 'POST',
                            url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                            dataType    : 'text',
                            data:       {

                                /**
                                 *  1. Action & Security
                                 *  --------------------
                                 */
                                'action'    : 'guest_list_csv_download',

                                'security'  : SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_guest_list_security,

                                'event_id'  :  event_id
                            },
                            beforeSend: function(){

                            },
                            success: function ( PHP_RESPONSE ) {

                                var hiddenElement = document.createElement('a');
                                hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI( PHP_RESPONSE );
                                hiddenElement.target = '_blank';
                                
                                //provide the name for the CSV file to be downloaded
                                hiddenElement.download = event_name + '.csv';
                                hiddenElement.click();
                            },
                            error: function (xhr, ajaxOptions, thrownError) {

                                console.log( 'SDWeddingDirectory_Error_Export_CSV' );
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
         *  24. View Summary Per Event
         *  --------------------------
         */
        view_event_summary: function(){

            if( $( '.open_event_summary' ) ){

                $( '.open_event_summary' ).on( 'click', function( e ){

                    var event_unique_id = $(this).attr( 'data-event-unique-id' );

                    if( event_unique_id !== '' && event_unique_id !== null && event_unique_id !== undefined ){

                        e.preventDefault();

                        $.ajax({
                            type        : 'POST',
                            url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                            dataType    : 'json',
                            data:       {

                                /**
                                 *  1. Action & Security
                                 *  --------------------
                                 */
                                'action'            : 'sdweddingdirectory_event_summary_load',

                                'event_unique_id'   : event_unique_id
                            },
                            beforeSend: function(){

                            },
                            success: function ( PHP_RESPONSE ) {

                                /**
                                 *  Variable
                                 *  --------
                                 */
                                var _lable           = new Array(),

                                    _series          = new Array(),

                                    _total_guest     = parseInt( PHP_RESPONSE.total_guest );

                                /**
                                 *  Merge Graph Values
                                 *  ------------------
                                 */
                                $.map( PHP_RESPONSE.chart_graph, function( input_value, input_key ){

                                        _lable.push( parseFloat( input_value.percentage ) );

                                        _series.push( input_key );

                                        if( $( '#'+input_key+'_Guest' ).length ){

                                            $( '#'+input_key+'_Guest' ).html( parseInt( input_value.count_guest ) );
                                        }
                                } );

                                /**
                                 *  Load Chart
                                 *  ----------
                                 */
                                SDWeddingDirectory_Guest_List.load_graph(

                                    _series,

                                    _lable,

                                    _total_guest
                                );

                                /**
                                 *  Update HTML ( inner HTML )
                                 *  --------------------------
                                 */
                                $.map( PHP_RESPONSE.guest_age, function( val, elm ) {

                                        if( $( '#'+elm+'-guest-percentage' ).length ){

                                            $( '#'+elm+'-guest-percentage' ).attr( 'style', 'width:'+ val.percentage );
                                            $( '#'+elm+'-guest-percentage' ).html( '<span>' + val.percentage + '</span>' );
                                        }

                                        if( $( '#'+elm+'-guest-count' ).length ){

                                            $( '#'+elm+'-guest-count' ).html( val.counter );
                                        }
                                });


                                /**
                                 *  Update HTML ( inner HTML )
                                 *  --------------------------
                                 */
                                $.map( PHP_RESPONSE.html, function( val, elm ) {

                                    if( $( '#'+elm ).length ){

                                        $( '#'+elm ).html( val );
                                    }
                                });

                                /**
                                 *  Update Invitation Status
                                 *  ------------------------
                                 */
                                $.map( PHP_RESPONSE.invitation, function( val, elm ) {

                                    if( $( '#'+elm ).length ){

                                        $( '#'+elm ).html( val );
                                    }
                                });

                                /**
                                 *  Update Event Meal
                                 *  -----------------
                                 */
                                if( $( '#event_meal_summary' ).length ){

                                    $( '#event_meal_summary' ).html( PHP_RESPONSE.event_meal );
                                }

                            },
                            error: function (xhr, ajaxOptions, thrownError) {

                                console.log( 'SDWeddingDirectory_Error_Menu_Item_Removed' );
                                console.log(xhr.status);
                                console.log(thrownError);
                                console.log(xhr.responseText);
                            },
                            complete: function( event, request, settings ){

                            }
                        });
                    }

                } );
            }
        },

        /**
         *  Load Graph
         *  ----------
         */
        load_graph: function(_lable, _series, _total_guest ){

            var condition_1 = _lable !== '' && _lable !== null && _lable !== undefined,
                condition_2 = _series !== '' && _series !== null && _series !== undefined,
                condition_3 = _total_guest !== '' && _total_guest !== null && _total_guest !== undefined;

            /**
             *  Load Chart
             *  ----------
             */
            if( $( '#sdweddingdirectory_event_chart' ).length && condition_1 && condition_2 && condition_3 ){

                $( '#sdweddingdirectory_event_chart' ).html( '' );

                    var options = {

                        series: _series,

                        labels: _lable,

                        colors: ['#198754', '#ffc107', '#dc3545' ],

                        chart: {

                            height: 350,
                            type: 'radialBar',
                        },

                        plotOptions: {
                            radialBar: {
                                dataLabels: {

                                    name: {
                                        fontSize: '22px',
                                    },
                                    value: {
                                        fontSize: '16px',
                                    },

                                    total: {
                                        show: true,
                                        label: "Guest",
                                        formatter: function (w) { return _total_guest }
                                    }
                                }
                            }
                        },
                    };

                    /**
                     *  Load Chart
                     *  ----------
                     */
                    var chart = new ApexCharts(document.querySelector("#sdweddingdirectory_event_chart"), options);
                    chart.render();
            }
        },

        /**
         *  25. Invitation Sent
         *  -------------------
         */
        invitation_status: function(){

            if( $( '.sent_invitation' ).length ){

                $( '.sent_invitation' ).on( 'click', function(e){

                    var guest_unique_id = $(this).attr( 'data-guest-id' ),

                        input_id        = $(this).attr( 'id' );

                    if( $( '#'+input_id ).prop('checked') == true  ){

                        $.ajax({
                            type        : 'POST',
                            url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                            dataType    : 'json',
                            data:       {

                                /**
                                 *  1. Action & Security
                                 *  --------------------
                                 */
                                'action'             : 'sdweddingdirectory_invitation_sent',

                                'guest_unique_id'    : guest_unique_id
                            },
                            beforeSend: function(){

                            },
                            success: function ( PHP_RESPONSE ) {

                                sdweddingdirectory_alert( PHP_RESPONSE );

                                if( $( '#' + input_id ).length ){

                                    $( '#' + input_id ).prop('checked', true).attr( 'disabled', true );
                                }
                            },
                            error: function (xhr, ajaxOptions, thrownError) {

                                console.log( 'SDWeddingDirectory_Error_Menu_Item_Removed' );
                                console.log(xhr.status);
                                console.log(thrownError);
                                console.log(xhr.responseText);
                            },
                            complete: function( event, request, settings ){

                            }
                        });
                    }

                    e.preventDefault();

                } );
            }
        },

        /**
         *  26. CSV Import
         *  --------------
         */
        // csv_import_guest: function(){

        //     if( $( '#sdweddingdirectory_guest_import_form' ).length ){

        //         $( '#sdweddingdirectory_guest_import_form' ).on( 'submit', function(e){

        //             e.preventDefault();

        //             $.ajax({
        //                 type        : 'POST',
        //                 url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
        //                 dataType    : 'json',
        //                 data:       {

        //                     /**
        //                      *  1. Action & Security
        //                      *  --------------------
        //                      */
        //                     'action'    : 'sdweddingdirectory_guest_list_import',

        //                     'csv_file'  : FormData(this)
        //                 },
        //                 beforeSend: function(){

        //                 },
        //                 success: function ( PHP_RESPONSE ) {

        //                 },
        //                 error: function (xhr, ajaxOptions, thrownError) {

        //                     console.log( 'SDWeddingDirectory_Error_Menu_Item_Removed' );
        //                     console.log(xhr.status);
        //                     console.log(thrownError);
        //                     console.log(xhr.responseText);
        //                 },
        //                 complete: function( event, request, settings ){

        //                 }
        //             });

        //         } );
        //     }
        // },

        /**
         *  27. Copy Request Missing Info
         *  -----------------------------
         */
        copy_and_paste_in_modal_request_missing_info: function(){

            /**
             *  Have Lenght
             *  -----------
             */
            if( $( 'a.request_missing_info_link' ).length ){

                /**
                 *  Each Missing Info
                 *  -----------------
                 */
                $( 'a.request_missing_info_link' ).map( function(){

                    /**
                     *  When Click
                     *  ----------
                     */
                    $(this).on( 'click' , function(){

                        /**
                         *  Link Copy
                         *  ---------
                         */
                        var     _copy_link_ =   $(this).attr( 'data-href' );

                        /**
                         *  Make sure modal exists
                         *  ----------------------
                         */
                        if( $( 'input.copy_request_missing_info_link' ).length ){

                            $( 'input.copy_request_missing_info_link' ).val( _copy_link_ );
                        }

                    } );

                } );
            }
        },

        /**
         *  28. Search Guest
         *  ----------------
         */
        search_guest: function(){

            if( $( '.search-guest' ).length ){

                $( '.search-guest' ).map( function(){

                    var     _target         =       $(this).attr( 'id' ),

                            _target_id      =       '#' + _target;

                            /**
                             *  Target ID
                             *  ---------
                             */
                            $( _target_id ).on( "keyup", function() {

                                var value   = $( this ).val().toLowerCase();

                                $( this ).closest( 'div.card-shadow' ).find( 'div.table-responsive table tbody tr' ) .filter( function() {

                                    $( this ).toggle( $(this).text().toLowerCase().indexOf(value) > -1 );

                                } );

                            } );
                } );
            }
        },

        /**
         *  Guest list script
         *  -----------------
         */
        init: function() {

            /**
             *  Ensure nonce is present for guest-list AJAX requests
             *  ----------------------------------------------------
             */
            this.attach_ajax_security();

            /**
             *  1. Button Click to Open Sidebar Form
             *  ------------------------------------
             */
            this.open_form_button_click();

            /**
             *  2. Menu : Removed
             *  -----------------
             */
            this.menu_item_removed();

            /**
             *  3. Menu : Add
             *  -------------
             */
            this.menu_item_added();

            /**
             *  4. Group : Removed
             *  ------------------
             */
            this.group_item_removed();

            /**
             *  5. Group : Added
             *  ----------------
             */
            this.group_item_added();

            /**
             *  6. Event : Removed
             *  ------------------
             */
            this.event_item_removed();

            /**
             *  7. Event : Added
             *  ----------------
             */
            this.event_item_added();

            /**
             *  8. Add New Event Meal Option
             *  ----------------------------
             */
            this.meal_box_event_managment();

            /**
             *  9. Create New Event
             *  -------------------
             */
            this.create_event();

            /**
             *  12. Add Guest in Database
             *  -------------------------
             */
            this.create_new_guest();

            /**
             *  13. Event Guest Meal Managmet
             *  -----------------------------
             */
            this.event_guest_meal_managment();

            /**
             *  14. Event Attendance Dropdown
             *  -----------------------------
             */
            this.event_guest_attandence();

            /**
             *  15. Get Edit Guest Information
             *  ------------------------------
             */
            this.get_edit_guest_form_data();

            /**
             *  16. Remove Guest Information
             *  ----------------------------
             */
            this.removed_guest_data();

            /**
             *  17. Update Guest Information
             *  ----------------------------
             */
            this.update_guest_data();

            /**
             *  18. Edit Event Details
             *  ----------------------
             */
            this.edit_event_form();

            /**
             *  19. Update Event Data
             *  ---------------------
             */
            this.update_event_data();

            /**
             *  20. Remove Event Data
             *  ---------------------
             */
            this.remove_event_data();

            /**
             *  21. DataTable Load
             *  ------------------
             */
            this.datatable();

            /**
             *  22. Gues Group Data Update
             *  --------------------------
             */
            this.update_guest_group();

            /**
             *  23. Download CSV File
             *  ---------------------
             */
            this.csv_download();

            /**
             *  24. View Summary Per Event
             *  --------------------------
             */
            this.view_event_summary();

            /**
             *  25. Invitation Sent
             *  -------------------
             */
            this.invitation_status();

            /**
             *  26. CSV Import
             *  --------------
             */
            // this.csv_import_guest();

            /**
             *  27. Copy Request Missing Info
             *  -----------------------------
             */
            this.copy_and_paste_in_modal_request_missing_info();

            /**
             *  28. Search Guest
             *  ----------------
             */
            this.search_guest();
        },
    };

    $(document).ready( function() { SDWeddingDirectory_Guest_List.init(); });

})(jQuery);
