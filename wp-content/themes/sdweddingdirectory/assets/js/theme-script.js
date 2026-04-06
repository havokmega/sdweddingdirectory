(function ($) {

    "use strict";

    /**
     *  SDWeddingDirectory - Theme Requred Script
     *  ---------------------------------
     */
    var SDWeddingDirectory_Theme_Setup = {

        /**
         *  Blog Post ( Gallery ) Carousel
         *  ------------------------------
         */
        blog_post_type_slider : function(){

            /**
             *  Have Class on Document ?
             *  ------------------------
             */
            if( $('.sdweddingdirectory-post-slider').length ){

                /**
                 *  Each Class To Load Script
                 *  -------------------------
                 */
                $('.sdweddingdirectory-post-slider').map( function(){

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

                        rtl                 :   false,

                        autoHeight          :   true,
        
                        loop                :   true,

                        stagePadding        :   0,

                        margin              :   30,

                        slideBy             :   1,

                        items               :   1,

                        autoplay            :   false,

                        autoplayTimeout     :   10000,

                        smartSpeed          :   1000,

                        nav                 :   true,

                        dots                :   true,

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
                                                ]
                    });

                } );
            }
        },

        /**
         *  Sticky Header Version One
         *  -------------------------
         */
        sticky_header_version_one: function() {

            /**
             *  Document Have Class ?
             *  ---------------------
             */
            if(  $('.header-version-one').length ){

                var admin_bar   =   '0';

                if( $( '#wpadminbar' ).length && $( window ).width() >= 600 ){

                    admin_bar   =   $( '#wpadminbar' ).height();
                }

                /**
                 *  If Document Scroll then load script
                 *  -----------------------------------
                 */
                if( $( 'html' ).height() >= 1100 ){

                    $( window ).scroll( function() {

                        if( $( '#masthead .top-bar-stripe' ).length ){

                            var i   =   $(this).scrollTop() > $( '#masthead .top-bar-stripe' ).height(),

                                j   =   $( window ).width() >= 991.99;

                            if( i && j ) {

                                $('.header-version-one').addClass('fixed-top fixed').attr( 'style', 'top:'+admin_bar+'px;' );


                            } else {

                                $('.header-version-one').removeClass('fixed-top fixed').removeAttr( 'style', '' );

                                $( 'main#content' ).css( 'padding-top', '' );
                            }

                        }else{

                            if( $( window ).width() >= 991.99 ) {

                                $('.header-version-one').addClass('fixed-top fixed').attr( 'style', 'top:'+admin_bar+'px;' );


                            } else {

                                $('.header-version-one').removeClass('fixed-top fixed').removeAttr( 'style', '' );

                                $( 'main#content' ).css( 'padding-top', '' );
                            }
                        }

                    } );
                }
            }
        },

        /**
         *  Sticky Header Version Two
         *  -------------------------
         */
        sticky_header_version_two: function() {

            /**
             *  Document Have Class ?
             *  ---------------------
             */
            if(  $('.header-version-two').length  ){

                var admin_bar   =   '0';

                if( $( '#wpadminbar' ).length && $( window ).width() >= 600 ){

                    admin_bar   =   $( '#wpadminbar' ).height();
                }

                /**
                 *  If Document Scroll then load script
                 *  -----------------------------------
                 */
                if( $( 'html' ).height() >= 1100 ){

                    $( window ).scroll( function() {

                        if( $(this).scrollTop() && $( window ).width() >= 991.99 ) {

                            $('.header-version-two').addClass('fixed-top fixed').attr( 'style', 'top:'+admin_bar+'px;' );


                        } else {

                            $('.header-version-two').removeClass('fixed-top fixed').removeAttr( 'style', '' );

                            $( 'main#content' ).css( 'padding-top', '' );
                        }

                    } );
                }
            }
        },

        /**
         *  Back To Top
         *  -----------
         */
        back_to_top: function () {

            /**
             *  Have id ?
             *  ---------
             */
            if( $( '#back-to-top' ).length) {

                /**
                 *  Document Scroll to check condition
                 *  ----------------------------------
                 */
                $( window ).scroll( function() {

                    if( $(this).scrollTop() > 100 ) {

                        $('#back-to-top').fadeIn();

                    } else {

                        $('#back-to-top').fadeOut();
                    }

                });

                /**
                 *  If "CLICK" on button to Jump on top
                 *  -----------------------------------
                 */
                $('#back-to-top').click( function() {

                    $('body, html').animate(

                                        /**
                                         *  1. Arguments as Object
                                         *  ----------------------
                                         */
                                        { scrollTop: 0 }, 

                                        /**
                                         *  2. Speed
                                         *  --------
                                         */
                                        800
                                    );

                    return false;
                });
            }
        },

        /**
         *  Select Option
         *  -------------
         */
        sdweddingdirectory_select_option_default: function(){

            $( 'body' ).find( 'select:not(.sdweddingdirectory-light-select):not(.sdweddingdirectory-dark-select):not([class*="sdweddingdirectory-"])' ).each( function() {

                if( $( this ).css( 'display' ) != 'none' && ! $( this ).hasClass( 'select2 form-control select2-hidden-accessible' ) ){

                    $( this ).addClass( 'form-control' );

                    $( this ).wrap( '<div class="select_container"></div>' );

                    if ( $( this ).parents( '.widget_categories' ).length > 0 ) {

                        $( this ).parent().get(0).submit = function() {

                            $(this).closest('form').submit();
                        };
                    }
                }

            } );
        },

        /**
         *  Keep top-level links clickable on mobile while allowing dropdown open
         *  ---------------------------------------------------------------------
         */
        nav_dropdown_tap: function(){

            var breakpoint = 1199.98;
            var selector = '.header-version-one .navbar-nav > li.dropdown > a.nav-link, .header-version-two .navbar-nav > li.dropdown > a.nav-link';

            $( document ).on( 'click', selector, function( e ) {

                if( window.innerWidth > breakpoint ){
                    return;
                }

                var $parent = $( this ).parent();

                if( ! $parent.hasClass( 'show' ) ){
                    e.preventDefault();
                }
            } );
        },

        /**
         *  Planning tools sticky CTA
         *  ------------------------
         */
        planning_scroll_cta: function(){

            var $cta = $( '[data-sdwd-planning-scroll-cta]' );

            if( ! $cta.length ){
                return;
            }

            var $window = $( window );
            var $nav = $( '#masthead .header-version-two .navbar, #masthead .header-version-one .navbar' ).first();
            var $collapse = $( '#navbarCollapse' );

            if( ! $nav.length ){
                return;
            }

            var setMetrics = function() {

                var adminBarHeight = 0;

                if( $( '#wpadminbar' ).length && window.innerWidth >= 600 ){
                    adminBarHeight = $( '#wpadminbar' ).outerHeight() || 0;
                }

                var navHeight = Math.round( $nav.outerHeight() || 0 );
                var trigger = navHeight;

                document.documentElement.style.setProperty( '--sdwd-planning-scroll-cta-top', adminBarHeight + 'px' );
                document.documentElement.style.setProperty( '--sdwd-planning-scroll-cta-height', navHeight + 'px' );

                $cta.data( 'sdwd-trigger', trigger );
            };

            var syncState = function() {

                var trigger = parseFloat( $cta.data( 'sdwd-trigger' ) ) || 0;
                var menuExpanded = window.innerWidth < 1200 && $collapse.length && $collapse.hasClass( 'show' );
                var visible = $window.scrollTop() > trigger && ! menuExpanded;

                $cta.toggleClass( 'is-visible', visible ).attr( 'aria-hidden', visible ? 'false' : 'true' );
            };

            $window.off( 'scroll.sdwPlanningScrollCta' ).on( 'scroll.sdwPlanningScrollCta', syncState );
            $window.off( 'resize.sdwPlanningScrollCta' ).on( 'resize.sdwPlanningScrollCta', function() {
                setMetrics();
                syncState();
            } );

            $( document )
                .off( 'shown.bs.collapse.sdwPlanningScrollCta hidden.bs.collapse.sdwPlanningScrollCta', '#navbarCollapse' )
                .on( 'shown.bs.collapse.sdwPlanningScrollCta hidden.bs.collapse.sdwPlanningScrollCta', '#navbarCollapse', syncState );

            setMetrics();
            syncState();
        },

        /**
         *  Load Script
         *  -----------
         */
        init: function (){

            /**
             *  Blog Post ( Gallery ) Carousel
             *  ------------------------------
             */
            this.blog_post_type_slider();

            /**
             *  Sticky Header Version One
             *  -------------------------
             */
            this.sticky_header_version_one();

            /**
             *  Sticky Header Version Two
             *  -------------------------
             */
            this.sticky_header_version_two();

            /**
             *  Back To Top
             *  -----------
             */
            this.back_to_top();

            /**
             *  Default Select Option
             *  ---------------------
             */
            this.sdweddingdirectory_select_option_default();

            /**
             *  Mobile dropdown behavior
             *  ------------------------
             */
            this.nav_dropdown_tap();

            /**
             *  Planning tools sticky CTA
             *  ------------------------
             */
            this.planning_scroll_cta();
        }
    }

    /**
     *  Document ready function
     *  -----------------------
     */
    $( document ).ready( function(){

        /**
         *  Read Object File
         *  ----------------
         */
        SDWeddingDirectory_Theme_Setup.init();

    } );

    /**
     *  Document Resize handlers
     *  ------------------------
     */
    $( window ).resize( function() {

        /**
         *  Sticky Header Version One
         *  -------------------------
         */
        SDWeddingDirectory_Theme_Setup.sticky_header_version_one();

        /**
         *  Sticky Header Version Two
         *  -------------------------
         */
        SDWeddingDirectory_Theme_Setup.sticky_header_version_two();

        /**
         *  Planning tools sticky CTA
         *  ------------------------
         */
        SDWeddingDirectory_Theme_Setup.planning_scroll_cta();
        
    } );

    /**
     *   Website Loader / Loading...
     *   ---------------------------
     */
    $( window ).on( 'load', function() {

        if( $( '.preloader' ).length ){

            $( '.status' ).fadeOut();

            $( '.preloader' ).delay( 350 ).fadeOut( 'slow' );
        }

    } );

})(jQuery);
