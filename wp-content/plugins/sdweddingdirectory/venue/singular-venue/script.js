/**
 *  SDWeddingDirectory Login User Form
 *  --------------------------
 */
(function($) {

    "use strict";

    var SDWeddingDirectory_Venue_Singular_Page = {

        /**
         *  1. Venue Singular Page Slider
         *  -------------------------------
         */
        venue_singular_page_slider : function(){

            /**
             *  Have Class
             *  ----------
             */
            if( $('#sdweddingdirectory-venue-singular-gallery-tab-carousel').length ){
    
                /**
                 *  Load Venue Gallery Carousel
                 *  -----------------------------
                 */
                $('#sdweddingdirectory-venue-singular-gallery-tab-carousel').owlCarousel({

                    rtl                 :   $( 'html' ).attr( 'dir' ) == "rtl"  ?   true   :   false,
    
                    loop                :   true,

                    stagePadding        :   325,

                    margin              :   0,

                    autoplay            :   true,

                    autoplayTimeout     :   10000,

                    smartSpeed          :   1000,

                    nav                 :   true,

                    dots                :   false,

                    /**
                     *  Next + Prev Text
                     *  ----------------
                     */
                    navText             :   [   
                                                /**
                                                 *   1. Left Side Text
                                                 *   -----------------
                                                 */
                                                '<i class="fa fa-chevron-left"></i>', 

                                                /**
                                                 *   2. Right Side Text
                                                 *   ------------------
                                                 */
                                                '<i class="fa fa-chevron-right"></i>'
                                            ],

                    /**
                     *  This object have Responsive Breakpoint
                     *  --------------------------------------
                     */
                    responsive          :   {
                                                0       :   {  items: 1,  stagePadding: 40  },

                                                600     :   {  items: 1,  stagePadding: 50  },

                                                1200    :   {  items: 1,  stagePadding: 325 }
                                            }
                });
            }
        },

        /**
         *  2. Venue Box - Carousel ( Home Page Version Two )
         *  ---------------------------------------------------
         */
        venue_box_carousel : function(){

            /**
             *  Have Class on Document ?
             *  ------------------------
             */
            if( $('.sdweddingdirectory-venue-box-carousel').length ){

                /**
                 *  Each Class To Load Script
                 *  -------------------------
                 */
                $('.sdweddingdirectory-venue-box-carousel').map( function(){

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

                        rtl                 :   $( 'html' ).attr( 'dir' ) == "rtl"  ?   true   :   false,
        
                        loop                :   true,

                        stagePadding        :   0,

                        margin              :   30,

                        slideBy             :   1,

                        autoplay            :   true,

                        autoplayTimeout     :   10000,

                        smartSpeed          :   1000,

                        nav                 :   false,

                        dots                :   true,

                        /**
                         *  Next + Prev Text
                         *  ----------------
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
                         *  This object have Responsive Breakpoint
                         *  --------------------------------------
                         */
                        responsive          :   {
                                                    0       : {  items: 1  },

                                                    600     : {  items: 1  },

                                                    767     : {  items: 2  },

                                                    992     : {  items: 3  },

                                                    1000    : {  items: 3  }
                                                }
                    });

                } );
            }
        },

        /**
         *  3. Venue Left Section Widget : Prefer Vendor
         *  ----------------------------------------------
         */
        preferred_venues : function(){

            if( $( '.sdweddingdirectory-preferred-vendors-carousel' ).length ){

                $( '.sdweddingdirectory-preferred-vendors-carousel' ).map( function( e ){

                    $( this ).owlCarousel({

                        rtl                 :   $( 'html' ).attr( 'dir' ) == "rtl"  ?   true   :   false,
        
                        loop: true,

                        stagePadding: 0,

                        margin: 15,

                        autoplay: true,

                        autoplayTimeout: 10000,

                        smartSpeed: 1000,

                        nav: false,

                        dots: true,

                        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],

                        responsive: {

                            0: {
                                items: 1
                            },

                            600: {
                                items: 2
                            },

                            1200: {
                                items: 2
                            }
                        }

                    } );

                } );
            }
        },

        /**
         *  Venue Singular Page - Video Section Show with Popup
         *  -----------------------------------------------------
         */
        venue_singular_page_video_section : function(){

            if( $('.vendor-video').length ){

                $('.popup-video').magnificPopup({

                    disableOn: 700,

                    type: 'iframe',

                    mainClass: 'mfp-fade',

                    removalDelay: 160,

                    preloader: true ,
                    
                    fixedContentPos: true
                });    
            }
        },

        /**
         *  2. Click to Scroll
         *  ------------------
         */
        tab_scrolling: function( elm ){

            if( $(elm).length ){

                $(elm).click(function(event) {
                        
                    if ( location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname ) {
                            
                        var target = $(this.hash);

                        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                            
                        if (target.length) {
                        
                            event.preventDefault();

                            $('html, body').animate({

                                scrollTop: target.offset().top - 40

                            }, 1000, function() {
                        
                                var $target = $(target);

                                $target.focus();

                                if ($target.is(":focus")) {

                                    return false;

                                } else {
                                    
                                    $target.attr('tabindex', '-1');

                                    $target.focus();
                                };
                            });
                        }

                    };

                    $(elm).each(function() { $(this).removeClass('active'); });

                    $(this).addClass('active');
                });
            }
        },

        /**
         *  Venue Gallery Popup
         *  ---------------------
         */
        venue_gallery : function(){

            if( $( '.venue-gallery' ).length ){

                $( '.venue-gallery' ).magnificPopup( {

                    delegate        :   'a',

                    type            :   'image',

                    tLoading        :   'Loading image #%curr%...',

                    mainClass       :   'mfp-img-mobile',

                    gallery         : {

                        enabled                 :   true,

                        navigateByImgClick      :   true,

                        preload                 :   [0,1] // Will preload 0 - before current, and 1 after the current image
                    },

                    image: {

                        tError                  :   '<a href="%url%">The image #%curr%</a> could not be loaded.',

                        titleSrc                :   function( item ){

                                                        return  item.el.attr( 'title' );
                                                    }
                    }

                } );
            }
        },

        /**
         *  SDWeddingDirectory - Venue Singular Page : Sidebar Featured Venue Box Widget
         *  ------------------------------------------------------------------------
         */
        sdweddingdirectory_venue_singular_sidebar_carousel_widget : function(){

            /**
             *  Document Have Class ?
             *  ---------------------
             */
            if( $('.sdweddingdirectory-featured-venue-sidebar-widget').length ){

                /**
                 *  Each Class To Load Script
                 *  -------------------------
                 */
                $('.sdweddingdirectory-featured-venue-sidebar-widget').map( function(){

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

                        rtl                 :   $( 'html' ).attr( 'dir' ) == "rtl"  ?   true   :   false,

                        loop                : true,

                        stagePadding        : 0,

                        margin              : 30,

                        items               : 1,

                        autoplay            : false,

                        autoplayTimeout     : 10000,

                        smartSpeed          : 1000,

                        nav                 : false,

                        dots                : true,

                        autoHeight          : true,

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
                    });

                });
            }
        },

        /**
         *  Venue : Widget : Featured Venue Carousel
         *  --------------------------------------------
         */
        venue_widget_carousel : function(){

            /**
             *  Have Class on Document ?
             *  ------------------------
             */
            if( $('.sdweddingdirectory-venue-widget-carousel').length ){

                /**
                 *  Each Class To Load Script
                 *  -------------------------
                 */
                $('.sdweddingdirectory-venue-widget-carousel').map( function(){

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

                        rtl                 :   $( 'html' ).attr( 'dir' ) == "rtl"  ?   true   :   false,
        
                        loop                :   true,

                        stagePadding        :   0,

                        margin              :   30,

                        slideBy             :   1,

                        autoplay            :   true,

                        autoplayTimeout     :   10000,

                        smartSpeed          :   1000,

                        nav                 :   false,

                        dots                :   true,

                        /**
                         *  Next + Prev Text
                         *  ----------------
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
                         *  This object have Responsive Breakpoint
                         *  --------------------------------------
                         */
                        responsive          :   {
                                                    0       : {  items: 1  },

                                                    600     : {  items: 1  },

                                                    767     : {  items: 1  },

                                                    992     : {  items: 1  },

                                                    1000    : {  items: 1  }
                                                }
                    });

                } );
            }
        },

        /**
         *  Read More String
         *  ----------------
         */
        read_more_string: function(){

            $(".show-read-more").each(function() {

                var str     =   $(this).text(),

                    string  =   $(this).attr( 'data-read-more-string' ),

                    _class  =   _is_empty( $(this).attr( 'data-class' ) )

                                ?   $(this).attr( 'data-class' )

                                :   '',

                    max     =   _is_empty( $(this).attr( 'data-word' ) )

                            ?   $(this).attr( 'data-word' )

                            :   200;

                if ($.trim(str).length > max) {

                    var subStr = str.substring(0, max);

                    var hiddenStr = str.substring(max, $.trim(str).length);

                    $(this).empty().html( '<span class="showTexts">' + subStr + '</span><span class="dots">...</span>' );

                    $(this).append(' <a href="javascript:void(0);" class="link '+_class+'">'+string+'</a>');

                    $(this).append( '<span class="addText">'+hiddenStr+'</span>' );
                }
            } );

            $( ".link" ).click(function() {

                var _parent         =   $(this).closest( '.show-read-more' ),

                    _hide_string    =   $( _parent ).find(".addText").text(),

                    _show_string    =   $( _parent ).find(".showTexts").text();

                                        $( _parent ).find( '.dots' ).remove();

                                        $( _parent ).text( _show_string + _hide_string );

                                        $(this).remove();
            } );
        },

        /**
         *  Venue : Menu
         *  --------------
         */
        venue_menu_carousel: function(){

            if( $('#venue-singular-menu').length ){
    
                $('#venue-singular-menu').owlCarousel({
    
                    loop: true,
                    rtl                 :   $( 'html' ).attr( 'dir' ) == "rtl"  ?   true   :   false,
                    stagePadding: 0,
                    margin: 30,
                    slideBy: 1,
                    items: 2,
                    autoplay: false,
                    autoplayTimeout: 10000,
                    smartSpeed: 1000,
                    nav: true,
                    dots: false,
                    navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
                    responsive: {
                        0: {
                            items: 1
                        },
                        600: {
                            items: 1
                        },
                        767: {
                            items: 2
                        },
                        992: {
                            items: 2
                        },
                        1000: {
                            items: 2
                        }
                    }
                });
            }
        },

        /**
         *  Venue Capacity + Facilities
         *  -----------------------------
         */
        venue_facilities_carousel: function(){

            if( $('.venue-singular-facilities').length ){
    
                $('.venue-singular-facilities').owlCarousel({

                    rtl                 :   $( 'html' ).attr( 'dir' ) == "rtl"  ?   true   :   false,
    
                    loop: false,

                    autoplayHoverPause:true,

                    stagePadding: 0,

                    margin: 30,

                    slideBy: 1,

                    items: 1,

                    autoplay: true,

                    autoplayTimeout: 3000,

                    smartSpeed: 1000,

                    nav: false,

                    dots: true,

                    navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>']

                });
            }

            if( $('.vendor-img-gallery').length ){

                $('.vendor-img-gallery').each(function() { // the containers for all your galleries
                    
                    $( this ).magnificPopup({

                        delegate: 'a', // the selector for gallery item

                        type: 'image',

                        gallery: {

                            enabled: true, // set to true to enable gallery
                        
                            preload: [0,2], // read about this option in next Lazy-loading section
                        
                            navigateByImgClick: true,
                        
                            arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>', // markup of an arrow button
                        
                            tPrev: 'Previous (Left arrow key)', // title for left button

                            tNext: 'Next (Right arrow key)', // title for right button

                            tCounter: '<span class="mfp-counter">%curr% of %total%</span>' // markup of counter
                        }
                    });
                });
            }
        },

        /**
         *  Venue Singular Page Section Nav
         *  ---------------------------------
         */
        venue_sectino_menu: function () {

            if ($('.vendormenu-anim').length) {

                $(window).scroll(function () {

                    if ( $(this).scrollTop() > 900 ) {

                        $('.vendormenu-anim').addClass('fixed animated fadeInDown');

                    } else {

                        $('.vendormenu-anim').removeClass('fixed animated fadeInDown');
                    }

                } );
            }
        },

        /**
         *  Run Login User Script
         *  ---------------------
         */
        init: function() {

            /**
             *  1. Venue Singular Page Slider
             *  -------------------------------
             */
            this.venue_singular_page_slider();

            /**
             *  2. Venue Box - Carousel ( Home Page Version Two )
             *  ---------------------------------------------------
             */
            this.venue_box_carousel();

            /**
             *  2. Click to Scroll
             *  ------------------
             */
            this.tab_scrolling( '.vendor-nav a' );

            /**
             *  3. Venue Gallery
             *  ------------------
             */
            this.venue_gallery();

            /**
             *  13. SDWeddingDirectory - Venue Singular Page : Sidebar Featured Venue Box Widget
             *  ----------------------------------------------------------------------------
             */
            this.sdweddingdirectory_venue_singular_sidebar_carousel_widget();

            /**
             *  Venue : Widget : Carousel
             *  ---------------------------
             */
            this.venue_widget_carousel();

            /**
             *  Venue : Team : Read More String
             *  ---------------------------------
             */
            this.read_more_string();

            /**
             *  Venue : Menu
             *  --------------
             */
            this.venue_menu_carousel();

            /**
             *  Venue : Facilities Carousel
             *  -----------------------------
             */
            this.venue_facilities_carousel();

            /**
             *  Preferred Vendor Carousel
             *  -------------------------
             */
            this.preferred_venues();

            /**
             *  Menu
             *  ----
             */
            this.venue_sectino_menu();

            /**
             *  Venue Singular Page - Video Section Show with Popup
             *  -----------------------------------------------------
             */
            // this.venue_singular_page_video_section();
        },
    };

    $(document).ready( function(){ SDWeddingDirectory_Venue_Singular_Page.init(); });

})(jQuery);
