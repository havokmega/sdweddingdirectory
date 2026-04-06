/**
 *  Vendor Venue Dashboard
 *  ------------------------
 */
(function($) {

    "use strict";

    var SDWeddingDirectory_Vendor_Venue_Update = {

        hide_button : function(elm){

            $('#'+elm).attr( 'type', 'button' ).css("cursor", "default").addClass( 'disabled' ).attr( 'aria-disabled', 'true"' );
        },

        get_input_data: function( name ){

            var i   =   "input[name="+name+"]",

                j   =   i+':checked',

                k   =   {};

            /**
             *  Have length ?
             *  -------------
             */
            if( $( i ).length ){

                $( j ).map(function( i ) {

                    k[ $(this).attr( 'data-value' ) ] = $(this).val()
                });

                $( 'input' ).map( function( i ){ $(this).attr( 'value', $(this).val() ); });

                return k;
            }
        },

        /**
         *  6. Add New Venue
         *  ------------------
         *  @link : ( Multiple checkboxes - http://jsfiddle.net/4kwnR/16/ )
         *  ---------------------------------------------------------------
         *  
         */
        venue_update: function(){

            /**
             *  Have Form ?
             *  -----------
             */
            if( $(  'form#venue_submit'  ).length  ){

                /**
                 *  When Submit the form
                 *  --------------------
                 */
                $(  'form#venue_submit'  ).on( 'submit', function( e ){

                    /**
                     *  Event
                     *  -----
                     */
                    e.preventDefault();

                    /**
                     *  Handler
                     *  -------
                     */
                    var     form_id                         =       '#'     +   $( this ).attr( 'id' ),

                            submit_btn                      =       $( form_id +' button[type=submit]' ),

                            have_customisation_enable       =       {};

                    /**
                     *  Input Filled
                     *  ------------
                     */
                    $( 'input' ).map( function( i ){   $( this ).attr( 'value', $( this ).val() );   } );

                    /**
                     *  Have Customisation Plugin Activated ?
                     *  -------------------------------------
                     */
                    if ( typeof SDWeddingDirectory_Venue_Update_Customisation_Service === 'object' ){

                        have_customisation_enable   =   {

                            'customisation_task' : SDWeddingDirectory_Venue_Update_Customisation_Service.init() 
                        };
                    }

                    /**
                     *  Sending Data AJAX
                     *  -----------------
                     */
                    $.ajax( {

                        type: 'POST',

                        dataType: 'json',

                        url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data: $.extend({}, have_customisation_enable, {

                            /**
                             *  Action + Security
                             *  -----------------
                             */
                            'action'                : 'sdweddingdirectory_update_venue_action',

                            'security'              :   $( form_id +' #sdweddingdirectory_update_venue').val(),

                            'venue_id'            :   $( form_id +' #venue_id' ).val(),

                            /**
                             *  Post Title + Description
                             *  ------------------------
                             */
                            'post_title'            : $( form_id +' #post_title').val(),

                            /**
                             *  If *wp_editor* function to get data
                             *  -----------------------------------
                             *  tinymce.get( 'post_content' ).getContent(),
                             *  ------------------------------------------
                             */
                            'post_content'          : $( form_id + ' #post_content' ).summernote('code'),

                            /**
                             *  Venue Category + Sub Category
                             *  -------------------------------
                             */
                            'venue_category'         :   $( form_id +' #venue_category').val(),

                            'venue_sub_category'     :   SDWeddingDirectory_Vendor_Venue_Update.get_input_data( 'venue_sub_category' ),

                            /**
                             *  Venue Location
                             *  ----------------
                             */
                            'venue_location'        : $( form_id +' .venue-location').attr( 'data-value-id' ),

                            /**
                             *  Seat Capacity
                             *  -------------
                             */
                            'venue_seat_capacity'     : $( form_id +' #venue_seat_capacity').val(),

                            /**
                             *  Venue Price
                             *  -------------
                             */
                            'venue_min_price'         : $( form_id +' #venue_min_price').val(),

                            'venue_max_price'         : $( form_id +' #venue_max_price').val(),

                            /**
                             *  Venue Address
                             *  ---------------
                             */
                            'venue_address'       : $( form_id +' #venue_address').val(),

                            /**
                             *  Venue Latitude + Longitude Information
                             *  ----------------------------------------
                             */
                            'map_address'           : $( form_id +' .map_address').val(),

                            'venue_latitude'      : $( form_id +' .map_latitude').val(),

                            'venue_longitude'     : $( form_id +' .map_longitude').val(),

                            'venue_pincode'       : $( form_id +' #venue_pincode').val(),

                            /**
                             *  Venue Video
                             *  -------------
                             */
                            'venue_video'         : $( form_id +' #venue_video').val(),

                            /**
                             *  Venue Media IDS
                             *  -----------------
                             */
                             '_thumbnail_id'        : $( form_id +' .venue_featured_image' ).val(),

                             /**
                              *  Venue Gallery
                              *  ---------------
                              */
                            'venue_gallery'       : $( form_id +' .venue_gallery_image' ).val(),

                            /**
                             *  Venue FAQ
                             *  -----------
                             */
                            'venue_faq'           : SDWeddingDirectory_Vendor_Venue_Update.get_faq_list(),

                            /**
                             *  Venue Menu
                             *  ------------
                             */
                            'venue_menu'          : SDWeddingDirectory_Vendor_Venue_Update.get_venue_menu(),

                            /**
                             *  Venue Facilities
                             *  ------------------
                             */
                            'venue_facilities'    : SDWeddingDirectory_Vendor_Venue_Update.venue_facilities(),

                            /**
                             *  Venue Team
                             *  ------------
                             */
                            'venue_team'          : SDWeddingDirectory_Vendor_Venue_Update.venue_team(),

                            /**
                             *  Preferred Vendors
                             *  -----------------
                             */
                            'preferred_venues'     :  $( form_id + ' #preferred_venues').val().join(","),

                            /**
                             *  Working Hours
                             *  -------------
                             */
                            'working_hours'             :  {

                                'saturday_enable'       :  $( '#saturday_enable' ).is(':checked')  ? 'on' : 'off',
                                'sunday_enable'         :  $( '#sunday_enable' ).is(':checked')    ? 'on' : 'off',
                                'monday_enable'         :  $( '#monday_enable' ).is(':checked')    ? 'on' : 'off',
                                'tuesday_enable'        :  $( '#tuesday_enable' ).is(':checked')   ? 'on' : 'off',
                                'wednesday_enable'      :  $( '#wednesday_enable' ).is(':checked') ? 'on' : 'off',
                                'thursday_enable'       :  $( '#thursday_enable' ).is(':checked')  ? 'on' : 'off',
                                'friday_enable'         :  $( '#friday_enable' ).is(':checked')    ? 'on' : 'off',

                                'saturday_start'        :  $( '#saturday_start' ).val(),
                                'sunday_start'          :  $( '#sunday_start' ).val(),
                                'monday_start'          :  $( '#monday_start' ).val(),
                                'tuesday_start'         :  $( '#tuesday_start' ).val(),
                                'wednesday_start'       :  $( '#wednesday_start' ).val(),
                                'thursday_start'        :  $( '#thursday_start' ).val(),
                                'friday_start'          :  $( '#friday_start' ).val(),

                                'saturday_close'        :  $( '#saturday_close' ).val(),
                                'sunday_close'          :  $( '#sunday_close' ).val(),
                                'monday_close'          :  $( '#monday_close' ).val(),
                                'tuesday_close'         :  $( '#tuesday_close' ).val(),
                                'wednesday_close'       :  $( '#wednesday_close' ).val(),
                                'thursday_close'        :  $( '#thursday_close' ).val(),
                                'friday_close'          :  $( '#friday_close' ).val(),

                                'saturday_open'         :  $( '#saturday_open' ).is(':checked')    ? 'on' : 'off',
                                'sunday_open'           :  $( '#sunday_open' ).is(':checked')      ? 'on' : 'off',
                                'monday_open'           :  $( '#monday_open' ).is(':checked')      ? 'on' : 'off',
                                'tuesday_open'          :  $( '#tuesday_open' ).is(':checked')     ? 'on' : 'off',
                                'wednesday_open'        :  $( '#wednesday_open' ).is(':checked')   ? 'on' : 'off',
                                'thursday_open'         :  $( '#thursday_open' ).is(':checked')    ? 'on' : 'off',
                                'friday_open'           :  $( '#friday_open' ).is(':checked')      ? 'on' : 'off',
                            },

                            /**
                             *  Term Group Box
                             *  --------------
                             */
                            'term_group_box'            :   SDWeddingDirectory_Vendor_Venue_Update.term_group_box(),

                            /**
                             *  Calendar Group
                             *  --------------
                             */
                            'calender_data'             :   SDWeddingDirectory_Vendor_Venue_Update.calender_data()
                        } ),

                        /**
                         *  Before Sending Data to Server
                         *  -----------------------------
                         */
                        beforeSend:         function(){

                            $( submit_btn ).addClass( 'disabled' ).append( '<i class="ms-2 fa fa-spinner fa-spin"></i>' );
                        },

                        /**
                         *  When Success
                         *  ------------
                         */
                        success: function( PHP_RESPONSE ){

                            /**
                             *  Stop Loader
                             *  -----------
                             */
                            $( submit_btn ).removeClass( 'disabled' );

                            if( $( submit_btn ).find( 'i' ).length ){

                                $( submit_btn ).find( 'i' ).remove();
                            }

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
                });
            }
        },

        /**
         *  Calender Data
         *  -------------
         */
        calender_data: function(){

            var     collection   =   [];

            /**
             *  Have Term Group Available ?
             *  ---------------------------
             */
            if( $( '.calender-data' ).length ){

                $( '.calender-data' ).map( function(){

                    var  _id    =   $(this).attr( 'id' );

                    collection[ _id ] =  $( '#' + _id + '-input-data' ).val();

                } );
            }

            const jsonString    =   JSON.stringify( Object.assign( {}, collection ) );

            const json_obj      =   JSON.parse( jsonString );

            /**
             *  Collection
             *  ----------
             */
            return          json_obj;
        },

        /**
         *  Term Group Box
         *  --------------
         */
        term_group_box: function(){

            var     collection   =   [];

            /**
             *  Have Term Group Available ?
             *  ---------------------------
             */
            if( $( '.term-data' ).length ){

                $( '.term-data' ).map( function(){

                    collection[ $(this).attr( 'id' ) ] = SDWeddingDirectory_Vendor_Venue_Update.get_input_data( $(this).attr( 'id' ) );

                } );
            }

            const jsonString    =   JSON.stringify( Object.assign( {}, collection ) );

            const json_obj      =   JSON.parse( jsonString );

            /**
             *  Collection
             *  ----------
             */
            return          json_obj;
        },

        /**
         *  8. Load Sub Categories
         *  ----------------------
         */
        get_sub_categories: function(){

            /**
             *  Have Venue Category
             *  ---------------------
             */
            if( $( '#venue_category' ).length ){

                /**
                 *  When Option Selected
                 *  --------------------
                 */
                $( document ).on( 'change', '#venue_category', function(e){

                    /**
                     *  Event Start
                     *  -----------
                     */
                    e.preventDefault();

                    /**
                     *  Make sure venue category have value
                     *  -------------------------------------
                     */
                    if( _is_empty( $( '#venue_category' ).val() ) ){

                        var     cat_id      =   $( '#venue_category' ).val(),

                                post_id     =   $( '#venue_id' ).val();

                        /**
                         *  Ajax Start
                         *  ----------
                         */
                        $.ajax( {

                            type        :   'POST',

                            dataType    :   'json',

                            url         :   SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                            data        :   {

                                'action'        :   'sdweddingdirectory_venue_category_related_data',

                                'term_id'       :   cat_id,

                                'post_id'       :   post_id
                            },

                            success: function( PHP_RESPONSE ){

                                /**
                                 *  Checkbox
                                 *  --------
                                 */
                                $.map( PHP_RESPONSE.checkbox_fields, function( val, element ) {

                                    SDWeddingDirectory_Vendor_Venue_Update.category_data(

                                        '.' + element, val
                                    );

                                } );

                                /**
                                 *  Multiple Selection
                                 *  ------------------
                                 */
                                $.map( PHP_RESPONSE.multiple_select, function( val, element ) {

                                    SDWeddingDirectory_Vendor_Venue_Update.multiple_option(

                                        '.' + element, val
                                    );

                                    /**
                                     *  Multiple Selection Option Update
                                     *  --------------------------------
                                     */
                                    SDWeddingDirectory_Elements.sdweddingdirectory_select_option();

                                } );

                                /**
                                 *  Label Update
                                 *  ------------
                                 */
                                $.map( PHP_RESPONSE.rename_label, function( val, element ) {

                                    SDWeddingDirectory_Vendor_Venue_Update.rename_label(

                                        '.' + element, val
                                    );

                                } );

                                /**
                                 *  Enable / Disable Fields
                                 *  -----------------------
                                 */
                                $.map( PHP_RESPONSE.show_hide_fields, function( val, element ) {

                                    SDWeddingDirectory_Vendor_Venue_Update.section_show_hide(

                                        '.' + element, val
                                    );

                                } );
                            }

                        } );
                    }

                } );
            }
        },

        /**
         *  Category Wise Data Show / Hide
         *  ------------------------------
         */
        multiple_option: function( elm, data ){

            /**
             *  Have Amenities ?
             *  ----------------
             */
            var _info_class     =   elm + '-label',

                _section        =   elm + '-section',

                _target         =   $( _info_class + ',' + _section );

            /**
             *  Have Data ?
             *  -----------
             */
            if( $( _target ).length && _is_empty( data ) ){

                $( _section + ' .section-data' ).html( data );

                $( _info_class + ',' + _section ).removeClass( 'd-none' );

            }else{

                $( _section + ' .section-data' ).html( '' );

                $( _info_class + ',' + _section ).addClass( 'd-none' );
            }
        },

        /**
         *  Category Wise Rename Fields
         *  ---------------------------
         */
        rename_label: function( elm, data ){

            /**
             *  Have Amenities ?
             *  ----------------
             */
            var _info_class     =   elm + '-label';
            /**
             *  Have Data ?
             *  -----------
             */
            if( $( _info_class + ' h3' ).length && _is_empty( data ) ){

                $( _info_class + ' h3' ).html( data );
            }
        },

        /**
         *  Category Wise Data Show / Hide
         *  ------------------------------
         */
        category_data: function( elm, data ){

            /**
             *  Have Amenities ?
             *  ----------------
             */
            var _info_class     =   elm + '-label',

                _section        =   elm + '-section',

                _target         =   $( _info_class + ',' + _section );

            /**
             *  Have Data ?
             *  -----------
             */
            if( $( _target ).length && _is_empty( data ) ){

                $( _section + ' .section-data' ).html( data );

                $( _info_class + ',' + _section ).removeClass( 'd-none' );

            }else{

                $( _section + ' .section-data' ).html( '' );

                $( _info_class + ',' + _section ).addClass( 'd-none' );
            }
        },

        /**
         *  Show / Hide Section
         *  -------------------
         */
        section_show_hide: function( elm, data ){

            var _info_class     =   elm + '-label',

                _section        =   elm + '-section',

                _target         =   $( _info_class + ',' + _section );

            /**
             *  Have class ?
             *  ------------
             */
            if( $( _target ).length && data == true ){

                $( _info_class + ',' + _section ).removeClass( 'd-none' );

            }else{

                $( _info_class + ',' + _section ).addClass( 'd-none' );
            }
        },

        /**
         *  10. Add Group Member
         *  --------------------
         */
        add_new_group_member: function(){

            if( $('.sdweddingdirectory_group_accordion').length ){

                $( '.sdweddingdirectory_group_accordion' ).each( function(){

                    var _id         =   '#' + $(this).attr( 'id' ),

                        _ajax       =   $(this).attr( 'data-ajax-action' ),

                        _section    =   '#' + $(this).attr( 'data-parent' ),

                        _class      =   $(this).attr( 'data-class' ),

                        _member     =   $(this).attr( 'data-member' );

                    /**
                     *  When Click to Add
                     *  -----------------
                     */
                    $(document).on( 'click', _id, function(e){

                        if( $( _id ).find( 'i' ).length ){
                            
                            $( _id ).find( 'i' ).remove();
                        }

                        $( _id ).append( '<i class="ms-2 fa fa-spinner fa-spin"></i>' );

                        $( _id ).addClass( 'disabled' );

                        var     _count      =   $( _section + ' .collpase_section' ).length >= 1

                                            ?   $( _section + ' .collpase_section' ).length

                                            :   0;

                        $.ajax( {

                            type: 'POST',

                            dataType: 'json',

                            url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                            data: {

                                /**
                                 *  Action + Security
                                 *  -----------------
                                 */
                                'action'            :   'sdweddingdirectory_venue_add_new_request_handler',

                                'class'             :   _class,

                                'member'            :   _member,

                                'counter'           :   _count
                            },

                            success: function( PHP_RESPONSE ){

                                if( $( _id ).find( 'i' ).length ){
                                    
                                    $( _id ).find( 'i' ).remove();
                                }

                                /**
                                 *  Is Object ?
                                 *  -----------
                                 */
                                if( $.type( PHP_RESPONSE.sdweddingdirectory_ajax_data ) == 'object' ){

                                    /**
                                     *  Alert
                                     *  -----
                                     */
                                    sdweddingdirectory_alert( PHP_RESPONSE.sdweddingdirectory_ajax_data );

                                }else{

                                    /**
                                     *  Have Length
                                     *  -----------
                                     */
                                    if( $( _section ).length ){

                                        $( _section ).append( PHP_RESPONSE.sdweddingdirectory_ajax_data );
                                    }

                                    /**
                                     *  Read the removed icon process
                                     *  -----------------------------
                                     */
                                    SDWeddingDirectory_Vendor_Venue_Update.repetable_fields_load();
                                }
                            },
                            beforeSend: function(){

                            },
                            error: function (xhr, ajaxOptions, thrownError) {

                                console.log( 'SDWeddingDirectory_Group_Accordion' );
                                console.log(xhr.status);
                                console.log(thrownError);
                                console.log(xhr.responseText);
                            },
                            complete: function( event, request, settings ){

                                $( _id ).removeClass( 'disabled' );
                            }
                        });

                        e.preventDefault();

                    } );

                } );
            }
        },

        /**
         *  11. Member Add / Removed to Run Script
         *  --------------------------------------
         */
        repetable_fields_load: function(){

            /**
             *  1. If click on removed icon to removed section
             *  ----------------------------------------------
             */
            SDWeddingDirectory_Vendor_Venue_Update.removed_accordion();

            /**
             *  Make sure : SDWeddingDirectory Product Media Object Exists
             *  --------------------------------------------------
             */
            if ( typeof SDWeddingDirectory_Summry_Editor === 'object' ){

                /**
                 *  1. Have Editor ?
                 *  ----------------
                 */
                SDWeddingDirectory_Summry_Editor.editor();
            }

            /**
             *  Textarea Object Call
             *  --------------------
             */
            if ( typeof SDWeddingDirectory_Elements === 'object' ){

                /**
                 *  1. Textare Script Loaded
                 *  ------------------------
                 */
                SDWeddingDirectory_Elements.textarea_content_limit();
            }

            /**
             *  Location Object
             *  ---------------
             */
            if ( typeof SDWeddingDirectory_Find_Post_Dropdown === 'object' ){

                /**
                 *  Multiple Location Script Exists then recall
                 *  -------------------------------------------
                 */
                SDWeddingDirectory_Find_Post_Dropdown.init();
            }

            /**
             *  Google Map
             *  ----------
             */
            if( typeof SDWeddingDirectory_Google_Map === 'object' ){

                SDWeddingDirectory_Google_Map.init();
            }

            /**
             *  Leaflet Map
             *  -----------
             */
            if( typeof SDWeddingDirectory_Leaflet_Map === 'object' ){

                SDWeddingDirectory_Leaflet_Map.init();
            }

        },

        /**
         *  11. Remove Group Member
         *  -----------------------
         */
        removed_accordion: function(){

            $( document ).on( 'click', '.remove_collapse', function(){

                $(this).closest( 'div.accordion_section' ).remove();

                $(this).closest( 'div.collpase_section' ).remove();

            } );
        },

        /**
         *  Get FAQ list in array
         *  ---------------------
         */
        get_faq_list: function(){

            /**
             *  Get FAQ's
             *  ---------
             */
            var faq_list    =   new Array();

            $( '#venue_faqs .collpase_section' ).map(function( index, value ) {

                var faq_title       =   $(this).find( '.faq_title' ).val(),

                    faq_desc        =   $(this).find( '.faq_description.sdweddingdirectory-editor' ).length

                                    ?   $(this).find( '.faq_description.sdweddingdirectory-editor' ).summernote('code')

                                    :   $(this).find( '.faq_description' ).val();

                /**
                 *  Make sure all fields are filled
                 *  -------------------------------
                 */
                if( _is_empty( faq_title ) && _is_empty( faq_desc ) ){

                    faq_list.push( {

                        'title'             : faq_title,

                        'faq_title'         : faq_title,

                        'faq_description'   : faq_desc
                    } );
                }

            } );

            return faq_list;
        },

        /**
         *  Get Venue Menu Data List
         *  --------------------------
         */
        get_venue_menu: function(){

            /**
             *  Get FAQ's
             *  ---------
             */
            var data = new Array();

            $( '#venue_menu .collpase_section' ).map(function( index, value ) {

                var menu_title      =   $(this).find( '.menu_title' ).val(),

                    menu_file       =   $(this).find( '.menu_file' ).val(),

                    menu_price      =   $(this).find( '.menu_price' ).val();

                /**
                 *  Make sure all fields are filled
                 *  -------------------------------
                 */
                if(     _is_empty( menu_title ) 
                    &&  _is_empty( menu_file ) 
                    &&  _is_empty( menu_price )
                ) {

                    data.push( {

                        'title'              : menu_title,

                        'menu_title'         : menu_title,

                        'menu_file'          : menu_file,

                        'menu_price'         : menu_price,

                    } );
                }
            });

            return data;
        },

        /**
         *  Get Venue Facilities Data List
         *  --------------------------------
         */
        venue_facilities: function(){

            /**
             *  Get FAQ's
             *  ---------
             */
            var data = new Array();

            $( '#venue_facilities .collpase_section' ).map(function( index, value ) {

                var facilities_name      =    $(this).find( '.facilities_name' ).val(),

                    facilities_desc      =    $(this).find( '.facilities_desc.sdweddingdirectory-editor' ).length 

                                         ?    $(this).find( '.facilities_desc.sdweddingdirectory-editor' ).summernote('code')

                                         :    $(this).find( '.facilities_desc' ).val(),

                    facilities_gallery   =    $(this).find( '.store_media_ids' ).val(),

                    facilities_seating   =    $(this).find( '.facilities_seating' ).val(),

                    facilities_price     =    $(this).find( '.facilities_price' ).val(),

                    facilities_cat       =    $(this).find( '.facilities_cat' ).val();

                    /**
                     *  Make sure all fields are filled
                     *  -------------------------------
                     */
                    if(     _is_empty( facilities_name )
                        &&  _is_empty( facilities_desc )
                        &&  _is_empty( facilities_gallery )
                        &&  _is_empty( facilities_seating )
                        &&  _is_empty( facilities_price )
                        &&  _is_empty( facilities_cat ) 
                    ){

                        data.push( {

                            'title'                 : facilities_name,

                            'facilities_name'       : facilities_name,

                            'facilities_desc'       : facilities_desc,

                            'facilities_gallery'    : facilities_gallery,

                            'facilities_seating'    : facilities_seating,

                            'facilities_price'      : facilities_price,

                            'facilities_cat'        : facilities_cat,

                        } );
                    }
            });

            return data;
        },

        /**
         *  Venue Team
         *  ------------
         */
        venue_team: function(){

            /**
             *  Get FAQ's
             *  ---------
             */
            var data = new Array();

            $( '#venue_team .collpase_section' ).map(function( index, value ) {

                var team_image       =   $(this).find( '.store_media_ids' ).val(),

                    first_name       =   $(this).find( '.first_name' ).val(),

                    last_name        =   $(this).find( '.last_name' ).val(),

                    position         =   $(this).find( '.position' ).val(),

                    bio              =   $(this).find( '.bio.sdweddingdirectory-editor' ).length

                                     ?   $(this).find( '.bio.sdweddingdirectory-editor' ).summernote('code')

                                     :   $(this).find( '.bio' ).val();

                    /**
                     *  Make sure all fields are filled
                     *  -------------------------------
                     */
                    if(     _is_empty( team_image ) 
                        &&  _is_empty( first_name ) 
                        &&  _is_empty( last_name ) 
                        &&  _is_empty( position ) 
                        &&  _is_empty( bio ) 
                    ){

                        data.push( {

                            'title'         : first_name + ' ' + last_name,

                            'image'         : team_image,

                            'first_name'    : first_name,

                            'last_name'     : last_name,

                            'position'      : position,

                            'bio'           : bio
                        } );
                    }
            } );

            return data;
        },

        /**
         *  Date Picker
         *  -----------
         */
        datepicker: function(){

            /**
             *  Have Class ?
             *  ------------
             */
            if( $( '.calender-data' ).length ){

                $( '.calender-data' ).map( function(){

                    var     _id             =   '#' + $(this).attr( 'id' );

                    /**
                     *  Create DatePicker
                     *  -----------------
                     */
                    $( _id ) .datepicker( {

                        format              :   'dd/mm/yyyy',

                        multidate           :   true,

                        startDate           :   '0d',

                        maxViewMode         :   2,

                    } );

                    /**
                     *  When Update Date
                     *  ----------------
                     */
                    $( _id ) .on( 'changeDate', function( e ){
                        
                        var collection      =   '';

                        /**
                         *  Make sure have date
                         *  -------------------
                         */
                        if( $( e.dates ).length ){

                            var collection     =

                            $( e.dates ).map( function( i, j ){

                                return      SDWeddingDirectory_Vendor_Venue_Update.date_format( j );

                            } ).get().join( ',' );
                        }

                        /**
                         *  Have Checked Value ?
                         *  --------------------
                         */
                        if( collection !== '' ){

                            /**
                             *  Update the Data in Input Fields
                             *  -------------------------------
                             */
                            $( _id + '-input-data' ).val( collection );
                        }

                        else{

                            /**
                             *  Update the Data in Input Fields
                             *  -------------------------------
                             */
                            $( _id + '-input-data' ).val( '' );
                        }

                    } );

                } );
            }
        },

        /**
         *  Date Format
         *  -----------
         */
        date_format: function( get_date ){

            const date                  =   new Date( get_date );

            const day                   =   date.getDate();

            const month                 =   date.getMonth() + 1;

            const year                  =   date.getFullYear();

            const dayOfWeek             =   date.getDay();

            const daysOfWeek            =   [ "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat" ];

            const formattedDate         =   `${day}/${month}/${year}`;

            return                           formattedDate;
        },

        /**
         *  1. Load vendor venue scripts.
         *  -------------------------------
         */
        init: function() {

            /**
             *  Add New Venue
             *  ---------------
             */
            this.venue_update();

            /**
             *  Load Sub Categories
             *  -------------------
             */
            this.get_sub_categories();

            /**
             *  Add New FAQ
             *  -----------
             */
            this.add_new_group_member();

            /**
             *  Remove FAQ
             *  ----------
             */
            this.repetable_fields_load();

            /**
             *  Date Picker
             *  -----------
             */
            this.datepicker();
        },
    };

    $(document).ready( function() {  SDWeddingDirectory_Vendor_Venue_Update.init(); });

})(jQuery);