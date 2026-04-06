/**
 *  SDWeddingDirectory Couple Wedding Website Page
 *  --------------------------------------
 */
(function($) {

    "use strict";

    var SDWeddingDirectory_Wedding_Website_One = {

        /**
         *  Header Animation
         *  ----------------
         */
        header_animation: function () {

            /**
             *  Document Have Class ?
             *  ---------------------
             */
            if(  $('.website-template-header-version-one .header-anim').length  ){

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

                        if( $(this).scrollTop() > 50 ) {

                            $('.website-template-header-version-one .header-anim').addClass('fixed').attr( 'style', 'top:'+admin_bar+'px;' );

                            $( 'main#content' ).attr( 'style', 'padding-top:'+ $('.website-template-header-version-one .header-anim').height() +'px;' );

                        } else {

                            $('.website-template-header-version-one .header-anim').removeClass('fixed').removeAttr( 'style', '' );

                            $( 'main#content' ).removeAttr( 'style', '' );
                        }

                    } );
                }
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
                            
                        var target      =   $(this.hash),

                            _scroll     =   _is_empty( $(this).attr( 'data-scroll' ) )

                                        ?   parseInt( $(this).attr( 'data-scroll' ) )

                                        :   0;

                        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                            
                        if (target.length) {
                        
                            event.preventDefault();

                            $('html, body').animate({

                                scrollTop: target.offset().top - _scroll

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
         *  1. Date Countdown
         *  -----------------
         */
        date_counter: function(){

            if( $( '#wedding-countdown' ).length ){

                var wedding_date = $( '#wedding-countdown' ).attr( 'data-wedding-date' );

                SDWeddingDirectory_Wedding_Website_One.countdown( wedding_date, 'wedding-countdown' );
            }
        },

        /**
         *  Countdown
         *  ---------
         */
        countdown: function(dt, id){

            var end = new Date(dt);
            
            var _second = 1000;
            var _minute = _second * 60;
            var _hour = _minute * 60;
            var _day = _hour * 24;
            var timer;

            var wedding_days    = $( '#wedding-countdown' ).attr( 'data-wedding-days'   ),
                wedding_hours   = $( '#wedding-countdown' ).attr( 'data-wedding-hours'  ),
                wedding_min     = $( '#wedding-countdown' ).attr( 'data-wedding-min'    ),
                wedding_sec     = $( '#wedding-countdown' ).attr( 'data-wedding-sec'    ),
                wedding_msg     = $( '#wedding-countdown' ).attr( 'data-wedding-msg'    );
            
            function showRemaining() {
                var now = new Date();
                var distance = end - now;
                if (distance < 0) {
                    
                    clearInterval(timer);
                    document.getElementById(id).innerHTML = '<h4 class="pt-3">'+wedding_msg+'</h4>';
                    
                    return;
                }
                var days = Math.floor(distance / _day);
                var hours = Math.floor((distance % _day) / _hour);
                var minutes = Math.floor((distance % _hour) / _minute);
                var seconds = Math.floor((distance % _minute) / _second);
                
                
                if (String(hours).length < 2){
                    hours = 0 + String(hours);
                }
                if (String(minutes).length < 2){
                    minutes = 0 + String(minutes);
                }
                if (String(seconds).length < 2){
                    seconds = 0 + String(seconds);
                }

                var datestr =

                '<li class="list-inline-item">'
                    +'<span class="days">'+days+'</span>'
                    +'<div class="days_text">'+ wedding_days +'</div>'
                +'</li>'
                +'<li class="list-inline-item">'
                    +'<span class="hours">'+hours+'</span>'
                    +'<div class="hours_text">'+ wedding_hours +'</div>'
                +'</li>'
                +'<li class="list-inline-item">'
                    +'<span class="minutes">'+minutes+'</span>'
                    +'<div class="minutes_text">'+ wedding_min +'</div>'
                +'</li>'
                +'<li class="list-inline-item">'
                    +'<span class="seconds">'+seconds+'</span>'
                    +'<div class="seconds_text">'+ wedding_sec +'</div>'
                +'</li>';

                document.getElementById(id).innerHTML = datestr;
            }

            timer = setInterval(showRemaining, 1000);
        },

        /**
         *  Porfolio isotope and filter
         *  ---------------------------
         */
        isotope_gallery: function () {

            $(window).on( 'load', function (){

                var portfolioIsotope = $('.isotope-gallery').isotope({

                    itemSelector: '.isotope-item'
                });

                $('#portfolio-flters li').on('click', function () {

                    $("#portfolio-flters li").removeClass('filter-active');

                    $(this).addClass('filter-active');

                    portfolioIsotope.isotope({

                        filter: $(this).data('filter')

                    });
                });
            });
        },

        /**
         *  Couple Website Gallery
         *  ----------------------
         */
        couple_website_gallery : function(){

            if( $('.couple-website-gallery').length ){

                $('.couple-website-gallery').magnificPopup({

                    delegate: 'a',
                    type: 'image',
                    tLoading: 'Loading image #%curr%...',
                    mainClass: 'mfp-img-mobile',
                    gallery: {
                        enabled: true,
                        navigateByImgClick: true,
                        preload: [0,1] // Will preload 0 - before current, and 1 after the current image
                    },
                    image: {
                        tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                        titleSrc: function(item) {
                            return item.el.attr('title');
                        }
                    }
                });
            }
        },

        /**
         *  5. Testimonial Slider
         *  ---------------------
         */
        testimonial : function(){

            if( $( '#slider-testimonail' ).length ){
    
                $( '#slider-testimonail' ).owlCarousel( {

                    rtl                 :   $( 'html' ).attr( 'dir' ) == "rtl"  ?   true   :   false,

                    items               :   1,

                    loop                :   true,

                    stagePadding        :   0,

                    margin              :   30,

                    autoplay            :   true,

                    autoplayTimeout     :   10000,

                    smartSpeed          :   1000,

                    nav                 :   false,

                    dots                :   true,

                    navText             :   [ '<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>' ]

                } );
            }
        },

        /**
         *  Load Data on Map
         *  ----------------
         */
        website_map_load: function(){

            /**
             *  Marker on Map
             *  -------------
             */
            if( $( '#map_extended' ).length ){

                /**
                 *  Map Data : Collection
                 *  ---------------------
                 */
                var SDWeddingDirectory_Map_Data = new Array();

                /**
                 *  Load Map ID
                 *  -----------
                 */
                SDWeddingDirectory_Map_Data[ 'sdweddingdirectory_map_id' ]     =    'map_extended';

                /**
                 *  Map Zoom Level
                 *  --------------
                 */
                SDWeddingDirectory_Map_Data[ 'sdweddingdirectory_map_zoom_level' ]  =  parseInt( '9' );

                /**
                 *  1. Defult Map : Latitude
                 *  ------------------------
                 */
                SDWeddingDirectory_Map_Data[ 'sdweddingdirectory_map_latitude' ]    =   '';

                /**
                 *  2. Defult Map : Longitude
                 *  -------------------------
                 */
                SDWeddingDirectory_Map_Data[ 'sdweddingdirectory_map_longitude' ]   =   '';

                /**
                 *  InfoBox Parent Class
                 *  --------------------
                 */
                SDWeddingDirectory_Map_Data[ 'sdweddingdirectory_info_window_parent_class' ] = 'sdweddingdirectory-map-venue-overview';

                /**
                 *  Have venue on page ?
                 *  ----------------------
                 */
                if( $( '.sdweddingdirectory_website_events_map_data' ).length ){

                    /**
                     *  Map Data
                     *  --------
                     */
                    SDWeddingDirectory_Map_Data[ 'sdweddingdirectory_map_data' ]   = SDWeddingDirectory_Wedding_Website_One.sdweddingdirectory_website_events_map_data();
                }

                /**
                 *  1. Google Map
                 *  -------------
                 */
                if( typeof SDWeddingDirectory_Google_Map === 'object' ){

                    SDWeddingDirectory_Google_Map.google_map_load_website_event_data( SDWeddingDirectory_Map_Data );
                }

                /**
                 *  2. Leaflet Map
                 *  --------------
                 */
                if( typeof SDWeddingDirectory_Leaflet_Map === 'object' ){

                    SDWeddingDirectory_Leaflet_Map.leaflet_map_load_website_event_data( SDWeddingDirectory_Map_Data );
                }
            }
        },

        /**
         *  If Have Map to load this function : Venue Map Information
         *  -----------------------------------------------------------
         */
        sdweddingdirectory_website_events_map_data : function(){

            var locations = new Array();

            $('.sdweddingdirectory_website_events_map_data').map(function( index, value ) {

                locations.push({

                    'id'     :  $(this).attr('id'),

                    'lat'    :  parseFloat( $(this).find('.latitude').val() )

                                ? parseFloat( $(this).find('.latitude').val() )

                                : SDWeddingDirectory_Google_Map.default.lat,

                    'lng'    :  parseFloat( $(this).find('.longitude').val() )

                                ? parseFloat( $(this).find('.longitude').val() )

                                :  SDWeddingDirectory_Google_Map.default.lng,

                    'title'  :  $(this).find('.title').val(),

                    'icon'   :  $(this).find('.icon').val(),

                    'image'  :  $(this).find('.image').val(),
                });
            });

            return locations;
        },

        /**
         *  Load the functions
         *  ------------------
         */
        init: function() {

            /**
             *  1. Header Animation
             *  -------------------
             */
            this.header_animation();

            /**
             *  2. Click to Got Section ID
             *  --------------------------
             */
            this.tab_scrolling( '.navbar-nav a' );

            /**
             *  3. Date Countdown
             *  -----------------
             */
            this.date_counter();

            /**
             *  4. Couple Gallery
             *  -----------------
             */
            this.isotope_gallery();

            /**
             *  5. Couple Website Gallery
             *  -------------------------
             */
            this.couple_website_gallery();

            /**
             *  5. Testimonial Slider
             *  ---------------------
             */
            this.testimonial();

            /**
             *  6. Load map
             *  -----------
             */
            this.website_map_load();
        },
    };

    $(document).ready( function(){ SDWeddingDirectory_Wedding_Website_One.init(); });

})(jQuery);