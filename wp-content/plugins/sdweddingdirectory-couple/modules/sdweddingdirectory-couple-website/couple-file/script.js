/**
 *  Couple Wedding Website
 *  ----------------------
 */
(function($) {

  'use strict';

    var SDWeddingDirectory_Couple_Wedding_Website = {

        /**
         *  AJAX query to load data
         *  -----------------------
         */
        add_new_group_member: function(){

            if( $('.sdweddingdirectory_couple_website_group_accordion').length ){

                $( '.sdweddingdirectory_couple_website_group_accordion' ).each( function(){

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

                        /**
                         *  Wait for event
                         *  --------------
                         */
                        e.preventDefault();

                        if( $( _id ).find( 'i' ).length ){
                            
                            $( _id ).find( 'i' ).remove();
                        }

                        /**
                         *  Wait... When Data not Load
                         *  --------------------------
                         */
                        $( _id ).addClass( 'disabled' );

                        /**
                         *  Create Counter
                         *  --------------
                         */
                        var     _count      =   $( _section + ' .collpase_section' ).length >= 1

                                            ?   $( _section + ' .collpase_section' ).length

                                            :   0;

                        $( _id ).append( '<i class="ms-2 fa fa-spinner fa-spin"></i>' );

                        $.ajax({

                            type: 'POST',

                            dataType: 'json',

                            url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                            data: {

                                /**
                                 *  Action + Security
                                 *  -----------------
                                 */
                                'action'            :   'sdweddingdirectory_couple_website_add_new_request_handler',

                                'class'             :   _class,

                                'member'            :   _member,

                                'counter'           :   _count
                            },

                            success: function( PHP_RESPONSE ){

                                /**
                                 *  Remove Loader
                                 *  -------------
                                 */
                                if( $( _id ).find( 'i' ).length ){
                                    
                                    $( _id ).find( 'i' ).remove();
                                }

                                /**
                                 *  Wait... When Data not Load
                                 *  --------------------------
                                 */
                                $( _id ).removeClass( 'disabled' );

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
                                    SDWeddingDirectory_Couple_Wedding_Website.repetable_fields_load();
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

                        } );

                    } );

                } );
            }
        },

        /**
         *  Multiple Time Run Events
         *  ------------------------
         */
        repetable_fields_load: function(){

            /**
             *  1. If click on removed icon to removed section
             *  ----------------------------------------------
             */
            SDWeddingDirectory_Couple_Wedding_Website.removed_accordion();

            /**
             *  Wedding Date
             *  ------------
             */
            if ( typeof SDWeddingDirectory_Elements === 'object' ){

                /**
                 *  1. Textare Script Loaded
                 *  ------------------------
                 */
                SDWeddingDirectory_Elements.date_picker();
            }

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

                /**
                 *  2. Have Select Option ?
                 *  -----------------------
                 */
                SDWeddingDirectory_Elements.sdweddingdirectory_select_option();
            }

            /**
             *  Found Google Map
             *  ----------------
             */
            if ( typeof SDWeddingDirectory_Google_Map === 'object' ){

                SDWeddingDirectory_Google_Map.dynamic_search_address_map()
            }

            /**
             *  Found Leaflet Map
             *  -----------------
             */
            if ( typeof SDWeddingDirectory_Leaflet_Map === 'object' ){

                SDWeddingDirectory_Leaflet_Map.dynamic_search_address_map()
            }
        },

        /**
         *  Removed Accordion
         *  -----------------
         */
        removed_accordion: function(){

            $( document ).on( 'click', '.remove_collapse', function(){

                $(this).closest( 'div.accordion_section' ).remove();

                $(this).closest( 'div.collpase_section' ).remove();

            } );
        },

        /**
         *  Venue Team
         *  ------------
         */
        collection_data: function( collection ){

            /**
             *  Get FAQ's
             *  ---------
             */
            var data = new Array();

            /**
             *  Collection for Couple Story ?
             *  -----------------------------
             */
            if( collection == 'couple_story' ){

                $( '#couple_story .collpase_section' ).map(function( index, value ) {

                    var story_image         =   $(this).find( '.store_media_ids' ).val(),

                        story_title         =   $(this).find( '.story_title' ).val(),

                        story_date          =   $(this).find( '.story_date' ).val(),

                        story_overview      =   $(this).find( '.story_overview.sdweddingdirectory-editor' ).length

                                            ?   $(this).find( '.story_overview.sdweddingdirectory-editor' ).summernote('code')

                                            :   $(this).find( '.story_overview' ).val();

                        /**
                         *  Make sure all fields are filled
                         *  -------------------------------
                         */
                        if(     _is_empty( story_image ) 
                            &&  _is_empty( story_title ) 
                            &&  _is_empty( story_date ) 
                            &&  _is_empty( story_overview ) 
                        ){

                            data.push( {

                                'title'             : story_title,

                                'story_image'       : story_image,

                                'story_title'       : story_title,

                                'story_date'        : story_date,

                                'story_overview'    : story_overview

                            } );
                        }
                } );
            }

            /**
             *  Collection for Couple Story ?
             *  -----------------------------
             */
            if( collection == 'couple_groom' ){

                /**
                 *  Couple Story
                 *  ------------
                 */
                $( '#couple_groom .collpase_section' ).map(function( index, value ){

                    var groom_image  =  $(this).find( '.store_media_ids' ).val(),

                        groom_name   =  $(this).find( '.groom_name' ).val();

                        /**
                         *  Make sure all fields are filled
                         *  -------------------------------
                         */
                        if( _is_empty( groom_image ) && _is_empty( groom_name ) ){

                            data.push( {

                                'title'             : groom_name,

                                'groom_image'       : groom_image,

                                'groom_name'        : groom_name,

                            } );
                        }
                } );
            }

            /**
             *  Collection for Couple Story ?
             *  -----------------------------
             */
            if( collection == 'couple_bride' ){

                /**
                 *  Couple Story
                 *  ------------
                 */
                $( '#couple_bride .collpase_section' ).map(function( index, value ){

                    var bride_image  =  $(this).find( '.store_media_ids' ).val(),

                        bride_name   =  $(this).find( '.bride_name' ).val();

                        /**
                         *  Make sure all fields are filled
                         *  -------------------------------
                         */
                        if( _is_empty( bride_image ) && _is_empty( groom_name ) ){

                            data.push( {

                                'title'         : bride_name,

                                'bride_image'   : bride_image,

                                'bride_name'    : bride_name,

                            } );
                        }
                } );
            }

            /**
             *  Collection for Couple Story ?
             *  -----------------------------
             */
            if( collection == 'couple_gallery' ){

                /**
                 *  Couple Story
                 *  ------------
                 */
                $( '#couple_gallery .collpase_section' ).map(function( index, value ){

                    var gallery_image  =  $(this).find( '.store_media_ids' ).val(),

                        gallery_name   =  $(this).find( '.gallery_name' ).val();

                        /**
                         *  Make sure all fields are filled
                         *  -------------------------------
                         */
                        if( _is_empty( gallery_image ) && _is_empty( groom_name ) ){

                            data.push( {

                                'title'           : gallery_name,

                                'gallery_image'   : gallery_image,

                                'gallery_name'    : gallery_name,

                            } );
                        }
                } );
            }

            /**
             *  Collection for Couple Testimonials ?
             *  ------------------------------------
             */
            if( collection == 'couple_testimonial' ){

                /**
                 *  Couple Story
                 *  ------------
                 */
                $( '#couple_testimonial .collpase_section' ).map(function( index, value ){

                    var name        =  $(this).find( '.name' ).val(),

                        content     =  $(this).find( '.content' ).val();

                        /**
                         *  Make sure all fields are filled
                         *  -------------------------------
                         */
                        if( _is_empty( name ) && _is_empty( content ) ){

                            data.push( {

                                'title'     : name,

                                'name'      : name,

                                'content'   : content,
                            } );
                        }
                } );
            }

            /**
             *  Collection for Couple Testimonials ?
             *  ------------------------------------
             */
            if( collection == 'couple_event' ){

                /**
                 *  Couple Story
                 *  ------------
                 */
                $( '#couple_event .collpase_section' ).map(function( index, value ){

                    var     name                =       $(this).find( '.name' ).val(),

                            content             =       $(this).find( '.content' ).val(),

                            date                =       $(this).find( '.date' ).val(),

                            icon                =       $(this).find( '.icon' ).val(),

                            latitude            =       $(this).find( '.map_latitude' ).val(),

                            longitude           =       $(this).find( '.map_longitude' ).val(),

                            image               =       $(this).find( '.store_media_ids' ).val(),

                            map_address         =       $(this).find( '.map_address' ).val();

                        /**
                         *  Make sure all fields are filled
                         *  -------------------------------
                         */
                        if( _is_empty( name ) 

                            && _is_empty( content ) 

                            && _is_empty( date ) 

                            && _is_empty( image ) 

                            && _is_empty( icon ) 

                            && _is_empty( latitude ) 

                            && _is_empty( longitude )  

                            &&  _is_empty( map_address )
                        ){

                            data.push( {

                                'title'             :       name,

                                'name'              :       name,

                                'content'           :       content,

                                'date'              :       date,

                                'image'             :       image,

                                'icon'              :       icon,

                                'latitude'          :       latitude,

                                'longitude'         :       longitude,

                                'map_address'       :       map_address,

                            } );
                        }
                } );
            }

            /**
             *  Return Data
             *  -----------
             */
            return          data;
        },

        /**
         *  Header Setting
         *  --------------
         */
        couple_wedding_website_header: function(){

            /**
             *  Form ID
             *  -------
             */
            var form        =   'couple_wedding_website_header',

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
                             *  Group Of Data
                             *  -------------
                             */
                            'group_of_data'            :    {

                                /**
                                 *  Wedding Information
                                 *  -------------------
                                 */
                                'bride_first_name'          : $( form_id + ' #bride_first_name' ).val(),

                                'bride_last_name'           : $( form_id + ' #bride_last_name' ).val(),

                                'groom_first_name'          : $( form_id + ' #groom_first_name' ).val(),

                                'groom_last_name'           : $( form_id + ' #groom_last_name' ).val(),
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
         *  About Us Setting
         *  ----------------
         */
        couple_wedding_website_about_us: function(){

            /**
             *  Form ID
             *  -------
             */
            var form        =   'couple_wedding_website_about_us',

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
                             *  Group Of Data
                             *  -------------
                             */
                            'group_of_data'            :    {

                                /**
                                 *  Couple Info 
                                 *  ----------
                                 */
                                'couple_info_heading'      :    $( form_id + ' #couple_info_heading' ).val(),

                                'couple_info_description'  :    $( form_id + ' #couple_info_description' ).val(),

                                /**
                                 *  Couple Info
                                 *  -----------
                                 */
                                'about_bride'              :    $( form_id + ' #about_bride' ).val(),

                                'bride_instagram'          :    $( form_id + ' #bride_instagram' ).val(),

                                'bride_facebook'           :    $( form_id + ' #bride_facebook' ).val(),

                                'bride_twitter'            :    $( form_id + ' #bride_twitter' ).val(),

                                'about_groom'              :    $( form_id + ' #about_groom' ).val(),

                                'groom_instagram'          :    $( form_id + ' #groom_instagram' ).val(),

                                'groom_facebook'           :    $( form_id + ' #groom_facebook' ).val(),

                                'groom_twitter'            :    $( form_id + ' #groom_twitter' ).val(),
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
         *  Our Story Setting
         *  -----------------
         */
        couple_wedding_website_our_story: function(){

            /**
             *  Form ID
             *  -------
             */
            var form        =   'couple_wedding_website_our_story',

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
                             *  Group Of Data
                             *  -------------
                             */
                            'group_of_data'            :    {

                                /**
                                 *  Couple Counter
                                 *  --------------
                                 */
                                'couple_story_heading'         :    $( form_id + ' #couple_story_heading' ).val(),

                                'couple_story_description'     :    $( form_id + ' #couple_story_description' ).val(),

                                /**
                                 *  Couple Story
                                 *  ------------
                                 */
                                'couple_story'        :  SDWeddingDirectory_Couple_Wedding_Website.collection_data( 'couple_story' ),
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
         *  Countdown Setting
         *  -----------------
         */
        couple_wedding_website_countdown: function(){

            /**
             *  Form ID
             *  -------
             */
            var form        =   'couple_wedding_website_countdown',

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
                             *  Group Of Data
                             *  -------------
                             */
                            'group_of_data'            :    {

                                /**
                                 *  Countdown
                                 *  ---------
                                 */
                                'couple_counter_heading'                :    $( form_id + ' #couple_counter_heading' ).val(),

                                'couple_counter_description'            :    $( form_id + ' #couple_counter_description' ).val(),

                                'couple_counter_date'                   :    $( form_id + ' #couple_counter_date' ).val(),
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
         *  RSVP Setting
         *  ------------
         */
        couple_wedding_website_rsvp: function(){

            /**
             *  Form ID
             *  -------
             */
            var form        =   'couple_wedding_website_rsvp',

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
                             *  Group Of Data
                             *  -------------
                             */
                            'group_of_data'            :    {

                                /**
                                 *  Couple RSVPs
                                 *  ------------
                                 */
                                'couple_rsvp_heading'           :    $( form_id + ' #couple_rsvp_heading' ).val(),

                                'couple_rsvp_description'       :    $( form_id + ' #couple_rsvp_description' ).val(),
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
         *  Groomsman Setting
         *  -----------------
         */
        couple_wedding_website_groomsman: function(){

            /**
             *  Form ID
             *  -------
             */
            var form        =   'couple_wedding_website_groomsman',

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
                             *  Group Of Data
                             *  -------------
                             */
                            'group_of_data'            :    {

                                /**
                                 *  Couple Groom
                                 *  ------------
                                 */
                                'couple_groom_heading'     :    $( form_id + ' #couple_groom_heading' ).val(),

                                'couple_groom_description' :    $( form_id + ' #couple_groom_description' ).val(),

                                /**
                                 *  Couple Groom
                                 *  ------------
                                 */
                                'couple_groom'        :  SDWeddingDirectory_Couple_Wedding_Website.collection_data( 'couple_groom' ),
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
         *  Bridesmaids Setting
         *  -------------------
         */
        couple_wedding_website_bridesmaids: function(){

            /**
             *  Form ID
             *  -------
             */
            var form        =   'couple_wedding_website_bridesmaids',

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
                             *  Group Of Data
                             *  -------------
                             */
                            'group_of_data'            :    {

                                /**
                                 *  Couple Counter
                                 *  --------------
                                 */
                                'couple_bride_heading'         :    $( form_id + ' #couple_bride_heading' ).val(),

                                'couple_bride_description'     :    $( form_id + ' #couple_bride_description' ).val(),

                                /**
                                 *  Couple Groom
                                 *  ------------
                                 */
                                'couple_bride'        :  SDWeddingDirectory_Couple_Wedding_Website.collection_data( 'couple_bride' ),
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
         *  Testimonials Setting
         *  --------------------
         */
        couple_wedding_website_testimonials: function(){

            /**
             *  Form ID
             *  -------
             */
            var form        =   'couple_wedding_website_testimonials',

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
                             *  Group Of Data
                             *  -------------
                             */
                            'group_of_data'            :    {

                                /**
                                 *  Couple Testimonials
                                 *  -------------------
                                 */
                                'couple_testimonial_heading'     :    $( form_id + ' #couple_testimonial_heading' ).val(),

                                'couple_testimonial_description' :    $( form_id + ' #couple_testimonial_description' ).val(),

                                /**
                                 *  Couple Testimonial
                                 *  ------------------
                                 */
                                'couple_testimonial'  :  SDWeddingDirectory_Couple_Wedding_Website.collection_data( 'couple_testimonial' ),
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
         *  Gallery Setting
         *  ---------------
         */
        couple_wedding_website_gallery: function(){

            /**
             *  Form ID
             *  -------
             */
            var form        =   'couple_wedding_website_gallery',

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
                             *  Group Of Data
                             *  -------------
                             */
                            'group_of_data'            :    {

                                /**
                                 *  Couple Gallery
                                 *  --------------
                                 */
                                'couple_gallery_heading'     :    $( form_id + ' #couple_gallery_heading' ).val(),

                                'couple_gallery_description' :    $( form_id + ' #couple_gallery_description' ).val(),


                                /**
                                 *  Couple Gallery
                                 *  --------------
                                 */
                                'couple_gallery'      :  SDWeddingDirectory_Couple_Wedding_Website.collection_data( 'couple_gallery' ),
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
         *  When & Where Setting
         *  --------------------
         */
        couple_wedding_website_when_and_where: function(){

            /**
             *  Form ID
             *  -------
             */
            var form        =   'couple_wedding_website_when_and_where',

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
                             *  Group Of Data
                             *  -------------
                             */
                            'group_of_data'            :    {

                                /**
                                 *  Couple Event
                                 *  ------------
                                 */
                                'couple_event_heading'     :    $( form_id + ' #couple_event_heading' ).val(),

                                'couple_event_description' :    $( form_id + ' #couple_event_description' ).val(),

                                /**
                                 *  Couple Event
                                 *  ------------
                                 */
                                'couple_event'        :  SDWeddingDirectory_Couple_Wedding_Website.collection_data( 'couple_event' ),
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
        init : function(){

            /**
             *  Header Setting
             *  --------------
             */
            this.couple_wedding_website_header();

            /**
             *  About Us Setting
             *  ----------------
             */
            this.couple_wedding_website_about_us();

            /**
             *  Our Story Setting
             *  -----------------
             */
            this.couple_wedding_website_our_story();

            /**
             *  Countdown Setting
             *  -----------------
             */
            this.couple_wedding_website_countdown();

            /**
             *  RSVP Setting
             *  ------------
             */
            this.couple_wedding_website_rsvp();

            /**
             *  Groomsman Setting
             *  -----------------
             */
            this.couple_wedding_website_groomsman();

            /**
             *  Bridesmaids Setting
             *  -------------------
             */
            this.couple_wedding_website_bridesmaids();

            /**
             *  Testimonials Setting
             *  --------------------
             */
            this.couple_wedding_website_testimonials();

            /**
             *  Gallery Setting
             *  ---------------
             */
            this.couple_wedding_website_gallery();

            /**
             *  When & Where Setting
             *  --------------------
             */
            this.couple_wedding_website_when_and_where();

            /**
             *  AJAX query to load data
             *  -----------------------
             */
            this.add_new_group_member();

            /**
             *  Script Read
             *  -----------
             */
            this.repetable_fields_load();
        },
    }

    $( document ).ready( function(){ SDWeddingDirectory_Couple_Wedding_Website.init(); } )

})(jQuery);