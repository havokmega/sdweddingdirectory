/**
 *  SDWeddingDirectory Project Scripts
 *  --------------------------
 */
(function($) {

    "use strict";

    window._is_empty = function(a){

        if( a !== '' && a !== undefined && a !== null && a !== NaN && a !== 0 ){

            return true;

        }else{

            return false;
        }
    }

    window._wait = function(callback, ms){

        var timer = 0;
          
        return function() {

            var context = this, args = arguments;

            clearTimeout(timer);

            timer = setTimeout(function () {

                callback.apply(context, args);

            }, ms || 0);

        };
    }

    /**
     *  @link - https://stackoverflow.com/questions/1349404/generate-random-string-characters-in-javascript#answer-1349426
     *  ------------------------------------------------------------------------------------------------------------------
     */
    window._rand = function (length = 8) {

        let result = '';

        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        const charactersLength = characters.length;

        let counter = 0;

        while (counter < length) {

          result += characters.charAt(Math.floor(Math.random() * charactersLength));

          counter += 1;
        }

        return  SDWEDDINGDIRECTORY_AJAX_OBJ.brand_name + '_' + result;
    }

    var SDWeddingDirectory_Elements  = {

        /**
         *  Datepicker
         *  ----------
         *  http://$ui.com/datepicker/#min-max
         *  ----------------------------------
         */
        date_picker: function(){

            // if( $( '.sdweddingdirectory_datepicker' ).length ){

            //     $( ".sdweddingdirectory_datepicker" ).datepicker();
            // }
        },

        /**
         *  SDWeddingDirectory - Select Option
         *  --------------------------
         */
        sdweddingdirectory_select_option: function(){

            /**
             *  1. Light Select Option Style
             *  ----------------------------
             */
            if( $('.sdweddingdirectory-light-select').length ) {

                $('.sdweddingdirectory-light-select').select2( {

                    width                           :   '100%', // resolve

                    theme                           :   'form-light',

                    placeholder                     :   $(this).attr( 'data-placeholder' ) != '' 

                                                        ?   $(this).attr( 'data-placeholder' ) 

                                                        :   '',

                } );
            }

            /**
             *  2. Dark Select Option Style
             *  ---------------------------
             */
            if( $('.sdweddingdirectory-dark-select').length ) {

                $('.sdweddingdirectory-dark-select').select2( {

                    width                           :   '100%', // resolve

                    theme                           :   'form-dark',

                    placeholder                     :   $(this).attr( 'data-placeholder' )

                } );
            }

            /**
             *  3. Light Select Option Style
             *  ----------------------------
             */
            if( $('.sdweddingdirectory-light-multiple-select').length ) {

                $('.sdweddingdirectory-light-multiple-select').map( function(){

                    var _id             =    '#' + $(this).attr( 'id' ),

                        _placeholder    =   $( _id ).attr( 'data-placeholder' ),

                        _selection      =   parseInt( $( _id ).attr( 'data-selection-limit' ) );


                    if( _selection >= 2 ){

                        $( _id ).select2( {

                            width                           :   '100%', // resolve

                            theme                           :   'form-light',

                            tags                            :   true,

                            placeholder                     :   _placeholder,

                            maximumSelectionLength          :   _selection,

                        } );

                    }else{

                        $( _id ).select2( {

                            width                           :   '100%', // resolve

                            theme                           :   'form-light',

                            tags                            :   true,

                            placeholder                     :   _placeholder,

                        } );
                    }

                } );
            }

            /**
             *  4. Dark Select Option Style
             *  ---------------------------
             */
            if( $('.sdweddingdirectory-dark-multiple-select').length ) {

                $('.sdweddingdirectory-dark-multiple-select').map( function(){

                    var _id             =    '#' + $(this).attr( 'id' ),

                        _placeholder    =   $( _id ).attr( 'data-placeholder' ),

                        _selection      =   parseInt( $( _id ).attr( 'data-selection-limit' ) );


                    if( _selection >= 2 ){

                        $( _id ).select2( {

                            width                           :   '100%', // resolve

                            theme                           :   'form-dark',

                            tags                            :   true,

                            placeholder                     :   _placeholder,

                            maximumSelectionLength          :   _selection,

                        } );

                    }else{

                        $( _id ).select2( {

                            width                           :   '100%', // resolve

                            theme                           :   'form-dark',

                            tags                            :   true,

                            placeholder                     :   _placeholder,

                        } );
                    }

                } );
            }
        },

        /**
         *  SDWeddingDirectory - Accordion
         *  ----------------------
         */
        sdweddingdirectory_accordion: function() {
            
            /**
             *  Document Have Class ?
             *  ---------------------
             */
            if( $('.theme-accordian [data-toggle="collapse"]').length ) {

                $('.theme-accordian [data-toggle="collapse"]').on('click', function (e) {

                    if( $(this).parents('.accordion').find('.collapse.show') ) {

                        var idx = $(this).index('[data-toggle="collapse"]');

                        if (idx == $('.collapse.show').index('.collapse')) {

                            e.stopPropagation();
                        }
                    }

                });
            }
        },

        /**
         *  SDWeddingDirectory - Brand / Partner / Logo Carouse
         *  -------------------------------------------
         */
        sdweddingdirectory_brand_logo_carousel : function(){

            /**
             *  Have Class on Document ?
             *  ------------------------
             */
            if( $('.sdweddingdirectory-brand-logo-carousel').length ){

                /**
                 *  Each Class To Load Script
                 *  -------------------------
                 */
                $('.sdweddingdirectory-brand-logo-carousel').map( function(){

                    /**
                     *  Create Owl Carousel Slider
                     *  --------------------------
                     *  Owl Carousel v2.3.4
                     *  --------------------
                     *  Copyright 2013-2018 David Deutsch
                     *  ---------------------------------
                     *  Licensed under: SEE LICENSE IN https://github.com/OwlCarousel2/OwlCarousel2/blob/master/LICENSE
                     *  -----------------------------------------------------------------------------------------------
                     *  @credit - https://owlcarousel2.github.io/OwlCarousel2/
                     *  ------------------------------------------------------
                     */
                    $( this ).owlCarousel({
        
                        loop                : true,

                        stagePadding        : 0,

                        margin              : 30,

                        autoplay            : true,

                        autoplayTimeout     : 10000,

                        smartSpeed          : 1000,

                        nav                 : false,

                        dots                : true,

                        /**
                         *  Next  |  Pre  Text
                         *  ------------------
                         */
                        navText                 :  [
                                                        /**
                                                         *   1. Left Side Text
                                                         *   -----------------
                                                         */
                                                        '<i class="fa fa-angle-left"></i>',

                                                        /**
                                                         *   2. Right Side Text
                                                         *   ------------------
                                                         */
                                                        '<i class="fa fa-angle-right"></i>'
                                                    ],

                        /**
                         *  Responsive Brekpoint
                         *  --------------------
                         */
                        responsive              :   {
                                                        0       : {  items: 2  },

                                                        600     : {  items: 2  },

                                                        767     : {  items: 3  },

                                                        1000    : {  items: 5  }
                                                    }
                    });

                } );
            }
        },

        /**
         *  Gallery Open with Magnific Popup
         *  --------------------------------
         *  1. Load in Venue Singular Page Gallery
         *  ----------------------------------------
         *  2. Load in RealWedding Singular Page Gallery
         *  --------------------------------------------
         *  3. Load in Vendor Singular Page Gallery
         *  ---------------------------------------
         *  @credit - https://dimsemenov.com/plugins/magnific-popup/
         *  --------------------------------------------------------
         */
        sdweddingdirectory_gallery_popup : function(){

            /**
             *  Documemnt Have Class ?
             *  ----------------------
             */
            if( $('.sdweddingdirectory-gallery-popup').length ){

                /**
                 *  One by one class to load object
                 *  -------------------------------
                 */
                $('.sdweddingdirectory-gallery-popup').each(function() {

                    $( this ) .magnificPopup( {

                        delegate    :   'a',

                        type        :   'image',

                        tLoading    :   'Loading image #%curr%...',

                        mainClass   :   'mfp-img-mobile',

                        gallery     :   {
                                            enabled                 : true,
                                        
                                            preload                 : [0,2],
            
                                            navigateByImgClick      : true,
            
                                            arrowMarkup             : '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
                        
                                            tPrev                   : 'Previous (Left arrow key)',

                                            tNext                   : 'Next (Right arrow key)',

                                            tCounter                : '<span class="mfp-counter">%curr% of %total%</span>'
                                        },

                        image       :   {

                                            tError                  :   '<a href="%url%">The image #%curr%</a> could not be loaded.',

                                            titleSrc                :   function(item) {

                                                                            return item.el.attr('title');
                                                                        }
                        }

                    });

                });

            }
        },

        /**
         *  SDWeddingDirectory - Owl Carousel Script
         *  --------------------------------
         */
        sdweddingdirectory_owl: function(){

            /**
             *  Have Class on Document ?
             *  ------------------------
             */
            if( $('.sdweddingdirectory-owl-carousel').length ){

                /**
                 *  Each Class To Load Script
                 *  -------------------------
                 */
                $('.sdweddingdirectory-owl-carousel').map( function(){

                    /**
                     *  Collect Args
                     *  ------------
                     */
                    var     _args               =       [];

                    /**
                     *  Is RTL ?
                     *  --------
                     */
                    if( $( 'html' ).attr( 'dir' ) == 'rtl' ){

                        _args[ 'rtl' ]     =       true;
                    }

                    /**
                     *  Enable Loop ?
                     *  -------------
                     */
                    _args[ 'loop' ]             =       $(this).attr( 'data-loop' ) == true || $(this).attr( 'data-loop' ) == 'true' 

                                                        ?   true    : false;
                    /**
                     *  Padding
                     *  -------
                     */
                    _args[ 'stagePadding' ]     =       0;

                    /**
                     *  Margin
                     *  ------
                     */
                    _args[ 'margin' ]           =       $(this).attr( 'data-margin' ) !== ''

                                                        ?   parseInt( $(this).attr( 'data-margin' ) )

                                                        :   parseInt( 15 );
                    /**
                     *  Enable Auto Play
                     *  ----------------
                     */
                    if( $(this).attr( 'data-auto-play' ) == true || $(this).attr( 'data-auto-play' ) == 'true' ){

                        /**
                         *  Auto Play
                         *  ---------
                         */
                        _args[ 'autoplay' ]             =       true;

                        /**
                         *  Auto Play Timout
                         *  ----------------
                         */
                        _args[ 'autoplayTimeout' ]      =       _is_empty( $(this).attr( 'data-auto-play-timeout' ) )

                                                                ?   parseInt( $(this).attr( 'data-auto-play-timeout' ) )

                                                                :   10000;
                    }

                    /**
                     *  Auto Play Off
                     *  -------------
                     */
                    else{

                        /**
                         *  Auto Play
                         *  ---------
                         */
                        _args[ 'autoplay' ]         =       false;
                    }

                    /**
                     *  Auto Play Smart Speed
                     *  ---------------------
                     */
                    _args[ 'smartSpeed' ]      =       _is_empty( $(this).attr( 'data-auto-play-speed' ) )

                                                        ?   parseInt( $(this).attr( 'data-auto-play-speed' ) )

                                                        :   1000;

                    /**
                     *  Navigation
                     *  ----------
                     */
                    _args[ 'nav' ]      =       $(this).attr( 'data-nav' ) == true || $(this).attr( 'data-nav' ) == 'true' 

                                                ?   true    : false;

                    /**
                     *  Dots
                     *  ----
                     */
                    _args[ 'dots' ]     =       $(this).attr( 'data-dots' ) == true || $(this).attr( 'data-dots' ) == 'true'

                                                ?   true    : false;

                    /**
                     *  Next  |  Pre  Text
                     *  ------------------
                     */
                    _args[ 'navText' ]     =       [
                                                        /**
                                                         *   1. Left Side Text
                                                         *   -----------------
                                                         */
                                                        '<i class="fa fa-angle-left"></i>',

                                                        /**
                                                         *   2. Right Side Text
                                                         *   ------------------
                                                         */
                                                        '<i class="fa fa-angle-right"></i>'
                                                    ];

                    /**
                     *  Responsive
                     *  ----------
                     */
                    _args[ 'responsive' ]     =       $.parseJSON( $(this).attr( 'data-breakpoint' ) );


                    /**
                     *  Create Owl Carousel Slider
                     *  --------------------------
                     *  Owl Carousel v2.3.4
                     *  --------------------
                     *  Copyright 2013-2018 David Deutsch
                     *  ---------------------------------
                     *  Licensed under: SEE LICENSE IN https://github.com/OwlCarousel2/OwlCarousel2/blob/master/LICENSE
                     *  -----------------------------------------------------------------------------------------------
                     *  @credit - https://owlcarousel2.github.io/OwlCarousel2/
                     *  ------------------------------------------------------
                     */
                    $( this ).owlCarousel( Object.assign( {}, _args ) );

                } );
            }

        },

        /**
         *  SDWeddingDirectory - Testimonials Carousel
         *  ----------------------------------
         */
        sdweddingdirectory_testimonials_carousel : function(){

            /**
             *  Have Class on Document ?
             *  ------------------------
             */
            if( $('.sdweddingdirectory-testimonial-carousel').length ){

                /**
                 *  Each Class To Load Script
                 *  -------------------------
                 */
                $('.sdweddingdirectory-testimonial-carousel').map( function(){

                    /**
                     *  Create Owl Carousel Slider
                     *  --------------------------
                     *  Owl Carousel v2.3.4
                     *  --------------------
                     *  Copyright 2013-2018 David Deutsch
                     *  ---------------------------------
                     *  Licensed under: SEE LICENSE IN https://github.com/OwlCarousel2/OwlCarousel2/blob/master/LICENSE
                     *  -----------------------------------------------------------------------------------------------
                     *  @credit - https://owlcarousel2.github.io/OwlCarousel2/
                     *  ------------------------------------------------------
                     */
                    $( this ).owlCarousel({
        
                        loop                    : true,

                        stagePadding            : 0,

                        margin                  : 30,

                        autoplay                : true,

                        autoplayTimeout         : 10000,

                        smartSpeed              : 1000,

                        nav                     : false,

                        dots                    : true,

                        /**
                         *  Next  |  Pre  Text
                         *  ------------------
                         */
                        navText                 :  [
                                                        /**
                                                         *   1. Left Side Text
                                                         *   -----------------
                                                         */
                                                        '<i class="fa fa-angle-left"></i>',

                                                        /**
                                                         *   2. Right Side Text
                                                         *   ------------------
                                                         */
                                                        '<i class="fa fa-angle-right"></i>'
                                                    ],

                        responsive              :   {
                                                        0       : {  items: 1  },

                                                        600     : {  items: 1  },

                                                        767     : {  items: 2  }
                                                    }
                    });
                });
            }
        },

        /**
         *  SDWeddingDirectory - Location Carousel
         *  ------------------------------
         *  Home Page version two
         *  ---------------------
         */
        sdweddingdirectory_location_post_carousel : function(){
    
            /**
             *  Document Have Class ?
             *  ---------------------
             */
            if( $('.sdweddingdirectory-location-post-carousel').length ){

                /**
                 *  Each Class To Load Script
                 *  -------------------------
                 */
                $('.sdweddingdirectory-location-post-carousel').map( function(){

                    /**
                     *  Create Owl Carousel Slider
                     *  --------------------------
                     *  Owl Carousel v2.3.4
                     *  --------------------
                     *  Copyright 2013-2018 David Deutsch
                     *  ---------------------------------
                     *  Licensed under: SEE LICENSE IN https://github.com/OwlCarousel2/OwlCarousel2/blob/master/LICENSE
                     *  -----------------------------------------------------------------------------------------------
                     *  @credit - https://owlcarousel2.github.io/OwlCarousel2/
                     *  ------------------------------------------------------
                     */
                    $( this ).owlCarousel({
    
                        loop                :  true,

                        stagePadding        :  0,

                        margin              :  30,

                        autoplay            :  true,

                        autoplayTimeout     :  10000,

                        smartSpeed          :  1000,

                        nav                 :  false,

                        dots                :  true,

                        /**
                         *  Next | Prev
                         *  -----------
                         */
                        navText             :   [
                                                    /**
                                                     *   1. Left Side Text
                                                     *   -----------------
                                                     */
                                                    '<i class="fa fa-angle-left"></i>',

                                                    /**
                                                     *   2. Right Side Text
                                                     *   ------------------
                                                     */
                                                    '<i class="fa fa-angle-right"></i>'
                                                ],
                        /**
                         *  Responsive
                         *  ----------
                         */
                        responsive          :  {

                                                    0     : {  items: 1  },

                                                    600   : {  items: 1  },

                                                    767   : {  items: 2  },

                                                    992   : {  items: 3  },

                                                    1000  : {  items: 3  }
                                                }

                    });

                });
            }
        },

        /**
         *  SDWeddingDirectory - Venue Post Carousel
         *  ----------------------------------
         */
        sdweddingdirectory_venue_post_carousel : function(){
    
            /**
             *  Document Have Class ?
             *  ---------------------
             */
            if( $('.sdweddingdirectory-venue-post-carousel').length ){

                /**
                 *  Each Class To Load Script
                 *  -------------------------
                 */
                $('.sdweddingdirectory-venue-post-carousel').map( function(){

                    /**
                     *  Create Owl Carousel Slider
                     *  --------------------------
                     *  Owl Carousel v2.3.4
                     *  --------------------
                     *  Copyright 2013-2018 David Deutsch
                     *  ---------------------------------
                     *  Licensed under: SEE LICENSE IN https://github.com/OwlCarousel2/OwlCarousel2/blob/master/LICENSE
                     *  -----------------------------------------------------------------------------------------------
                     *  @credit - https://owlcarousel2.github.io/OwlCarousel2/
                     *  ------------------------------------------------------
                     */
                    $( this ).owlCarousel({
    
                        loop                :  true,

                        stagePadding        :  0,

                        margin              :  30,

                        autoplay            :  false,

                        autoplayTimeout     :  10000,

                        smartSpeed          :  1000,

                        nav                 :  false,

                        dots                :  true,

                        /**
                         *  Next | Prev
                         *  -----------
                         */
                        navText             :   [
                                                    /**
                                                     *   1. Left Side Text
                                                     *   -----------------
                                                     */
                                                    '<i class="fa fa-angle-left"></i>',

                                                    /**
                                                     *   2. Right Side Text
                                                     *   ------------------
                                                     */
                                                    '<i class="fa fa-angle-right"></i>'
                                                ],
                        /**
                         *  Responsive
                         *  ----------
                         */
                        responsive          :  $( this ).attr( 'data-venue-layout' ) == 1 

                                            ?   {

                                                    0     : {  items: 1  },

                                                    600   : {  items: 1  },

                                                    767   : {  items: 2  },

                                                    992   : {  items: 3  },

                                                    1000  : {  items: 3  }
                                                }

                                            :   {

                                                    0     : {  items: 1  },

                                                    600   : {  items: 1  },

                                                    767   : {  items: 1  },

                                                    992   : {  items: 1  },

                                                    1000  : {  items: 1  }
                                                }

                    });

                });
            }
        },

        /**
         *  About us page - Slider Carousel
         *  -------------------------------
         */
        sdweddingdirectory_about_us_slider : function(){

            /**
             *  Document Have Class ?
             *  ---------------------
             */
            if( $('.sdweddingdirectory-about-us-carousel').length ){
        
                /**
                 *  SDWeddingDirectory - About Us Slider
                 *  ----------------------------
                 */
                $('.sdweddingdirectory-about-us-carousel').map( function(){

                    /**
                     *  Create Owl Carousel Slider
                     *  --------------------------
                     *  Owl Carousel v2.3.4
                     *  --------------------
                     *  Copyright 2013-2018 David Deutsch
                     *  ---------------------------------
                     *  Licensed under: SEE LICENSE IN https://github.com/OwlCarousel2/OwlCarousel2/blob/master/LICENSE
                     *  -----------------------------------------------------------------------------------------------
                     *  @credit - https://owlcarousel2.github.io/OwlCarousel2/
                     *  ------------------------------------------------------
                     */
                    $( this ).owlCarousel({
        
                        loop                    :  true,

                        stagePadding            :  325,

                        margin                  :  30,

                        autoplay                :  true,

                        autoplayTimeout         :  10000,

                        smartSpeed              :  1000,

                        nav                     :  false,

                        dots                    :  true,

                        /**
                         *  Next  |  Pre  Text
                         *  ------------------
                         */
                        navText                 :  [
                                                        /**
                                                         *   1. Left Side Text
                                                         *   -----------------
                                                         */
                                                        '<i class="fa fa-angle-left"></i>',

                                                        /**
                                                         *   2. Right Side Text
                                                         *   ------------------
                                                         */
                                                        '<i class="fa fa-angle-right"></i>'
                                                    ],

                        /**
                         *  Responsive Break Point Object
                         *  -----------------------------
                         */
                        responsive              :  {

                                                        0       : { items: 1, stagePadding: 40  },

                                                        600     : { items: 1, stagePadding: 50  },

                                                        1200    : { items: 1, stagePadding: 325 }
                                                    }
                    });

                } );
            }
        },

        /**
         *  Home Page Slider Section
         *  ------------------------
         */
        sdweddingdirectory_home_page_slider : function(){

            /**
             *  Document Have Class ?
             *  ---------------------
             */
            if( $('.sdweddingdirectory-home-page-slider').length ){

                /**
                 *  Each Class To Load Script
                 *  -------------------------
                 */
                $('.sdweddingdirectory-home-page-slider').map( function(){

                    /**
                     *  Create Owl Carousel Slider
                     *  --------------------------
                     *  Owl Carousel v2.3.4
                     *  --------------------
                     *  Copyright 2013-2018 David Deutsch
                     *  ---------------------------------
                     *  Licensed under: SEE LICENSE IN https://github.com/OwlCarousel2/OwlCarousel2/blob/master/LICENSE
                     *  -----------------------------------------------------------------------------------------------
                     *  @credit - https://owlcarousel2.github.io/OwlCarousel2/
                     *  ------------------------------------------------------
                     */
                    $( this ).owlCarousel( {

                        rtl             :   $( 'html' ).attr( 'dir' ) == "rtl"  ?   true   :   false,
        
                        loop            :   true,

                        stagePadding    :   0,

                        margin          :   0,

                        items           :   1,

                        autoplay        :   true,

                        autoplayTimeout :   10000,

                        smartSpeed      :   1000,

                        nav             :   true,

                        dots            :   true,

                        touchDrag       :   false,

                        mouseDrag       :   false,

                        autoHeight      :   true,

                        navText         :   [   
                                                /**
                                                 *   1. Left Side Text
                                                 *   -----------------
                                                 */
                                                '<i class="fa fa-angle-left"></i>',

                                                /**
                                                 *   2. Right Side Text
                                                 *   ------------------
                                                 */
                                                '<i class="fa fa-angle-right"></i>'
                                            ]
                    });
                });
            }
        },

        /**
         *   Button On click scroll
         *   ----------------------
         */
        click_to_go : function( click_id, move_to_id ){

            /**
             *  Have lenght ?
             *  -------------
             */
            if( $( '#' + click_id ).length && $( '#' + move_to_id ).length ){

                /**
                 *  When click
                 *  ----------
                 */
                $( '#' + click_id ).on( 'click', function( e ) {

                     e.preventDefault();
                     
                    $('html, body').animate({

                        scrollTop: $( '#' + move_to_id ).offset().top - 170

                    }, 1500 );

                });
            }
        },

        /**
         *  Submit Button Click to show loader icon
         *  ---------------------------------------
         */
        button_loader_start: function(elm){

            if (elm === undefined) {
                elm = $( 'form button.loader' );
            }

            if( $( elm + ' button.loader' ).find( 'i' ).length ){
                $( elm + ' button.loader' ).find( 'i' ).remove();
            }

            $( elm +' button.loader' ).append( '<i class="ms-2 fa fa-spinner fa-spin"></i>' );
        },

        /**
         *  Submit Button Click to show loader icon
         *  ---------------------------------------
         */
        button_loader_end: function( elm ){

            if (elm === undefined) {
                elm = $( 'form button.loader' );
            }

            if( $( elm + ' button.loader' ).find( 'i' ).length ){

                $( elm + ' button.loader' ).find( 'i' ).remove();
            }
        },

        /**
         *  Function Check the Location link have ID ?
         *  ------------------------------------------
         *  @credit - https://stackoverflow.com/questions/4656843/get-querystring-from-url-using-jquery#answer-4656873
         *  ----------------------------------------------------------------------------------------------------------
         */
        check_link_args: function(){

            var vars = [], hash;

            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

            for(var i = 0; i < hashes.length; i++){

                hash = hashes[i].split('=');

                vars.push(hash[0]);

                vars[hash[0]] = hash[1];
            }

            return vars;
        },

        /**
         *  Show Model Popup By Adress have model ID
         *  ----------------------------------------
         */
        show_model_poup_by_id: function(){

            if( typeof SDWEDDINGDIRECTORY_AJAX_OBJ === 'object' ){

                $.map( SDWEDDINGDIRECTORY_AJAX_OBJ.popup_list, function( val, element ) {

                    var url = window.location.href;

                    if( SDWeddingDirectory_Elements.check_link_args()[ element ] == val ) {

                        $( '#' + val ).modal( 'show' );
                    }

                } );
            }
        },

        /**
         *  SDWeddingDirectory - Product Price update with Currency Position + Sign too
         *  -------------------------------------------------------------------
         */
        price_with_currency: function( price = '' ){

            /**
             *  Check possition to set pricing
             *  ------------------------------
             */
            var possition     =   SDWEDDINGDIRECTORY_AJAX_OBJ.currency_position,

                is_left       =   possition != '' && possition == 'left',

                is_right      =   possition != '' && possition == 'right',

                currency      =   SDWEDDINGDIRECTORY_AJAX_OBJ.currency_sign;

                /**
                 *  Is Left Side Currency Setting ?
                 *  -------------------------------
                 */
                if( is_left ){

                    return  currency + price;
                }

                /**
                 *  Is Right Side Currency Setting ?
                 *  --------------------------------
                 */
                if( is_right ){

                    return  price + currency;
                }
        },

        /**
         *  Textare Charactor Limit
         *  -----------------------
         */
        textarea_content_limit: function(){

            if( $( '.sdweddingdirectory-textarea-limit' ).length ){

                $( '.sdweddingdirectory-textarea-limit' ).map( function( e ){

                    /**
                     *  If Key Up Event pass
                     *  --------------------
                     */
                    $( this ).keyup( function() {

                        SDWeddingDirectory_Elements.textarea_content_limit_reload( this );

                    } );

                    /**
                     *  Reload Script
                     *  -------------
                     */
                    SDWeddingDirectory_Elements.textarea_content_limit_reload( this );

                } );
            }
        },

        /**
         *  textare script reload
         *  ---------------------
         *  https://www.codeply.com/go/s0F9Iz38yn/bootstrap-textarea-with-character-count-_-bootstrap-3
         *  -------------------------------------------------------------------------------------------
         */
        textarea_content_limit_reload: function( _this ){

            var _length         =  $(_this ).attr( 'data-limit' ),

                alert           =  $(_this ).next( 'span.count_message' ),

                text_length     =  $( _this  ).val().length,

                length          =  '';

            /**
             *  Text length Check
             *  -----------------
             */
            if( text_length > _length ){

                length = '<span style="color: red;">'+text_length+'</span>'; 

            }else{

                length = '<span style="color:green;">'+text_length+'</span>';
            }
          
            $( alert ).html( length + ' / ' + _length );           
        },

        /**
         *  11. Remove Group Member
         *  -----------------------
         */
        removed_accordion: function(){

            $( document ).on( 'click', '.remove_core_collapse, .remove_collapse', function(){

                $(this).closest( 'div.collpase_section' ).remove();

            } );
        },

        /**
         *  10. Add Group Member
         *  --------------------
         */
        add_new_group_member: function(){

            if( $('.sdweddingdirectory_core_group_accordion').length ){

                $( '.sdweddingdirectory_core_group_accordion' ).each( function(){

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
                                'action'            :   'sdweddingdirectory_add_new_request_handler',

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
                                    SDWeddingDirectory_Elements.removed_accordion();
                                }
                            },

                            beforeSend: function(){

                            },

                            error: function (xhr, ajaxOptions, thrownError) {

                                console.log( 'SDWeddingDirectory Plugin - Add New Repetable Group Error...' );

                                console.log(xhr.status);

                                console.log(thrownError);

                                console.log(xhr.responseText);
                            },

                            complete: function( event, request, settings ){

                                /**
                                 *  Wait... When Data not Load
                                 *  --------------------------
                                 */
                                $( _id ).removeClass( 'disabled' );
                            }
                        });

                        e.preventDefault();

                    } );

                } );
            }
        },

        /**
         *  Have Collpase ?
         *  ---------------
         */
        input_collapse: function(){

            /**
             *  Have Collapse ?
             *  ---------------
             *  @link - https://getbootstrap.com/docs/5.3/components/collapse/#methods
             *  ----------------------------------------------------------------------
             */
            if( $( 'input.input-collapse' ).length ){

                /**
                 *  Each loop
                 *  ---------
                 */
                $( 'input.input-collapse' ).map( function(){

                    /**
                     *  When click on input
                     *  -------------------
                     */
                    $( this ).on( 'focus focusin', function(){

                        var _target     =   $(this).attr( 'data-target' );

                        $( _target ).addClass( 'show' );

                    } );

                } );
            }

            /**
             *  When collapse off
             *  -----------------
             */
            if( $( '.input-collapse-box' ).length ){

                /**
                 *  Each loop
                 *  ---------
                 */
                $( '.input-collapse-box a' ).map( function(){

                    /**
                     *  When click on input
                     *  -------------------
                     */
                    $( this ).on( 'click', function(){

                        /**
                         *  Data
                         *  ----
                         */
                        var     location_id         =   $(this).attr( 'data-location-id' ),

                                location_name       =   $(this).attr( 'data-location-name' ),

                                target_handler      =   $(this).closest( '.select-item-data' ).attr( 'data-handler' ),

                                target_id           =   '#' + target_handler;

                        /**
                         *  Update the Data in Input Fields
                         *  -------------------------------
                         */
                        $( target_id + '-input' ).val( location_name );

                        $( target_id + '-input-data' ).val( location_id );

                        /**
                         *  First check class exists to removed after added
                         *  -----------------------------------------------
                         */
                        $( '#input-collapse-' + target_handler + ' a' ).removeClass( 'active' );

                        $( '#input-collapse-' + target_handler + ' a[data-location-id='+ location_id +']' ).addClass( 'active' );

                        $(this).closest( '.collapse' ).removeClass( 'show' );

                    } );

                } );
            }
        },

        /**
         *  Write Input Data to get options with search
         *  -------------------------------------------
         *  requird : input fields data attribute 
         *  -------------------------------------
         *  input attr = data-search-id ( target filter box )
         *  -------------------------------------------------
         *  input attr = data-search-target ( find target filter box tag )
         *  --------------------------------------------------------------
         */
        find_key: function(){

            if( $( '.input-search-data' ).length ){

                $( '.input-search-data' ).map( function(){

                    $(this).on( 'keyup', function( e ){

                        /**
                         *  Var
                         *  ---
                         */
                        var     target_id       =   $(this).attr( 'data-search-id' ),

                                target_tag      =   $(this).attr( 'data-search-target' ),

                                input_value     =   $( this ).val().toLowerCase();

                        /**
                         *  Target
                         *  ------
                         */
                        $( target_id + ' ' + target_tag ).filter( function() {

                            $(this).toggle($(this).text().toLowerCase().indexOf( input_value ) > -1);
                        });

                    } );

                } );
            }
        },

        /**
         *  Tooltip Load
         *  ------------
         *  https://getbootstrap.com/docs/5.0/components/tooltips/#overview
         *  ---------------------------------------------------------------
         */
        tooltip: function(){

            /**
             *  Tooltip Work
             *  ------------
             */
            if( $( '[data-bs-toggle="tooltip"]' ).length ){

                /**
                 *  Tooltip Work
                 *  ------------
                 */
                var tooltipTriggerList  =   [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]') );

                var tooltipList         =   tooltipTriggerList.map(function (tooltipTriggerEl) {

                                                return new bootstrap.Tooltip( tooltipTriggerEl );

                                            } );
            }
        },

        /**
         *  Copy Link
         *  ---------
         */
        clipboard: function(){

            /**
             *  Make sure class exists
             *  ----------------------
             */
            if( $( '.__copy_text__' ).length ){

                var clipboard = new ClipboardJS( '.__copy_text__' );

                clipboard.on('success', function(e) {

                    var message_id  =   $( '.__copy_text__' ).attr( 'data-success-message' );

                    $( message_id ).removeClass( 'd-none' );

                    $( message_id ).html( $( message_id ).attr( 'data-success-message' ) );

                    setTimeout( function(){

                        $( message_id ).addClass( 'd-none' );

                    }, 3000 );

                    e.clearSelection();
                });
            }
        },

        /**
         *  Input Password Show / Hide
         *  --------------------------
         *  Couple Profile / Vendor Profile / Vendor Login / Couple Login
         *  -------------------------------------------------------------
         */
        input_password_show_hide: function(){

            /**
             *  Toggle Password
             *  ---------------
             */
            if( $( '.password-eye' ).length ){

                $( '.password-eye span' ).off( 'click' ).on( 'click', function() {

                    /**
                     *  Toggle Two Class
                     *  ----------------
                     */
                    $(this).toggleClass( 'fa-eye fa-eye-slash' );

                    /**
                     *  Get Input Type
                     *  --------------
                     */
                    var input   =   $(this).closest( '.password-eye' ).find( 'input' );

                    /**
                     *  Show Password
                     *  -------------
                     */
                    if( $( input ).attr( 'type' ) == 'password' ){

                        $( input ).attr( 'type', 'text' );
                    }

                    else if( $( input ).attr( 'type' ) == 'text' ){

                        $( input ).attr( 'type', 'password' );
                    }

                });
            }
        },

        /**
         *  Modal Open with Redirection Link Update
         *  ---------------------------------------
         */
        modal_redirection_link: function(){

            /**
             *  Have Length ?
             *  -------------
             */
            if( $( 'a[data-modal-redirection], button[data-modal-redirection]' ).length ){

                $( 'a[data-modal-redirection], button[data-modal-redirection]' ).on( 'click', function(){

                    var     _modal_id           =   $(this).attr( 'data-modal-id' ),

                            _redirection_link   =   $(this).attr( 'data-modal-redirection' ),

                            _redirection        =   '#' + _modal_id + '_redirect_link';

                    /**
                     *  Replace Redirection Link
                     *  ------------------------
                     */
                    if( $( _redirection ).length ){

                        $( _redirection ).val( _redirection_link );
                    }

                } );
            }
        },

        /**
         *  By Default Load Script
         *  ----------------------
         */
        init: function(){

            /**
             *  Find Data
             *  ---------
             */
            this.find_key();

            /**
             *  Input Collapse
             *  --------------
             */
            this.input_collapse();

            /**
             *  SDWeddingDirectory - Date Picker
             *  ------------------------
             */
            this.date_picker();

            /**
             *  SDWeddingDirectory - Select Options
             *  ---------------------------
             */
            this.sdweddingdirectory_select_option();

            /**
             *  SDWeddingDirectory - Accordion
             *  ----------------------
             */
            this.sdweddingdirectory_accordion();

            /**
             *  SDWeddingDirectory - Brand / Partner / Logo Carousel
             *  ---------------------------------------------
             */
            this.sdweddingdirectory_brand_logo_carousel();

            /**
             *  SDWeddingDirectory - Magnific Popup Gallery
             *  ------------------------------------
             */
            this.sdweddingdirectory_gallery_popup();

            /**
             *  SDWeddingDirectory - Testimonial Carousel
             *  ---------------------------------
             */
            this.sdweddingdirectory_testimonials_carousel();

            /**
             *  SDWeddingDirectory - Location Post Carousel
             *  ------------------------------------
             */
            this.sdweddingdirectory_location_post_carousel();

            /**
             *  SDWeddingDirectory - About Us Slider Section
             *  ------------------------------------
             */
            this.sdweddingdirectory_about_us_slider();

            /**
             *  Home Page Slider Section
             *  ------------------------
             */
            this.sdweddingdirectory_home_page_slider();

            /**
             *  Venue Post Carousel
             *  ---------------------
             */
            this.sdweddingdirectory_venue_post_carousel();

            /**
             *  Show Model Popup with Target Address Model #ID
             *  ----------------------------------------------
             */
            this.show_model_poup_by_id();

            /**
             *  Textare Charactor Limit
             *  -----------------------
             */
            this.textarea_content_limit();

            /**
             *  Add New Fields
             *  --------------
             */
            this.add_new_group_member();

            /**
             *  Remove Fields
             *  -------------
             */
            this.removed_accordion();

            /**
             *  Owl
             *  ---
             */
            this.sdweddingdirectory_owl();

            /**
             *  Load ToolTip
             *  ------------
             */
            this.tooltip();

            /**
             *  Clipboard to Copy
             *  -----------------
             */
            this.clipboard();

            /**
             *  Input Password Show / Hide
             *  --------------------------
             */
            this.input_password_show_hide();

            /**
             *  Modal Open with Redirection Link Update
             *  ---------------------------------------
             */
            this.modal_redirection_link();
        }
    };

    /**
     *  Common Script updated in Window Variable
     *  ----------------------------------------
     */
    window.SDWeddingDirectory_Elements     = SDWeddingDirectory_Elements;

    /**
     *  Document Ready to Run Object
     *  ----------------------------
     */
    $(document).ready( function(){   SDWeddingDirectory_Elements.init(); } );

})(jQuery);