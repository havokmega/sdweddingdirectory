<?php
/**
 *  --------------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Home Slider One ]
 *  --------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Slider_Version' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  --------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Home Slider One ]
     *  --------------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Slider_Version extends SDWeddingDirectory_Shortcode {

        /**
         *  A reference to an instance of this class
         *  ----------------------------------------
         */
        private static $instance;

        /**
         *  Returns an instance of this class.
         *  ----------------------------------
         */
        public static function get_instance() {

            if ( null == self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         *  ShortCode Atts
         *  --------------
         */
        public static function default_args(){

            /**
             *  Add Slider Placeholder
             *  ----------------------
             */
            return  [

                        'layout'                        =>      absint( '1' ),

                        'slider_image'                  =>      '',

                        'background_image'              =>      esc_url( parent:: placeholder( 'slider' ) ),

                        'form_heading'                  =>      esc_attr__( 'Find the Perfect Wedding Vendor', 'sdweddingdirectory-shortcodes' ),

                        'form_description'              =>      esc_attr__( 'Search over 360,000 wedding vendors with reviews, pricing, availability and more', 'sdweddingdirectory-shortcodes' ),

                        'venue_category_desc'         =>      esc_attr__( 'Or browse featured categories', 'sdweddingdirectory-shortcodes' ),

                        'venue_category_ids'          =>      '',

                        'submit_button_text'            =>      esc_attr__( 'Search Now', 'sdweddingdirectory-shortcodes' ),
                    ];
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  1. Slider Section Start
             *  -----------------------
             */
            add_shortcode( 'sdweddingdirectory_slider_section', [ $this, 'sdweddingdirectory_slider_section' ] );

            /**
             *  2. Load Slider Section
             *  ----------------------
             */
            add_shortcode( 'sdweddingdirectory_slider', [ $this, 'sdweddingdirectory_slider' ] );

            /**
             *  3. Load Slider Images
             *  ---------------------
             */
            add_shortcode( 'sdweddingdirectory_slider_image',  [ $this, 'sdweddingdirectory_slider_image' ] );

            /**
             *  4. Background Image : Slider
             *  ----------------------------
             */
            add_shortcode( 'sdweddingdirectory_background_image', [ $this, 'sdweddingdirectory_background_image' ] );

            /**
             *  4.1 Filter : Randomize Homepage Banner
             *  --------------------------------------
             */
            add_filter( 'sdweddingdirectory/home-slider/background-image', [ $this, 'randomize_homepage_banner' ] );

            /**
             *  Button : Filters
             *  ----------------
             */
            self:: shortcode_filters();

            /**
             *  4. SDWeddingDirectory - Venue Location Taxonomy Image Size ?
             *  ------------------------------------------------------
             */
            add_filter( 'sdweddingdirectory/image-size', function( $args = [] ){

                return  array_merge(

                            $args,

                            array(

                                [
                                    'id'            =>      esc_attr( 'sdweddingdirectory_img_1900x700' ),

                                    'name'          =>      esc_attr__( 'Slider', 'sdweddingdirectory-shortcodes' ),

                                    'width'         =>      absint( '1900' ),

                                    'height'        =>      absint( '700' )
                                ],
                            )
                        );
            } );

            /**
             *  2. SDWeddingDirectory - Placeholder Filter
             *  ----------------------------------
             */
            add_filter( 'sdweddingdirectory/placeholder/shortcode', function( $args = [] ){

                /**
                 *  Add Slider Placeholder
                 *  ----------------------
                 */
                return  array_merge(  

                            /**
                             *  Have Args ?
                             *  -----------
                             */
                            $args,

                            /**
                             *  Merge New Args
                             *  --------------
                             */
                            array(

                                'slider'  =>  esc_url(  plugin_dir_url( __FILE__ ) . 'images/' . 'slider.jpg' )
                            )
                        );
            } );
        }

        /**
         *  Have Filters ?
         *  --------------
         */
        public static function shortcode_filters(){

            /**
             *  1. SDWeddingDirectory ShortCode Filter
             *  ------------------------------
             */
            add_filter( 'sdweddingdirectory_shortcode_list', function( $args = [] ){

                /**
                 *  Get Plugin Information
                 *  ----------------------
                 */
                return  array_merge(

                            /**
                             *  Have Args ?
                             *  -----------
                             */
                            $args,

                            /**
                             *  Merge New Args
                             *  --------------
                             */
                            array(

                                /**
                                 *  Slider Items
                                 *  ------------
                                 */
                                'sdweddingdirectory_slider_section'  =>

                                    /**
                                     *  ShortCode Example
                                     *  -----------------
                                     */
                                    sprintf( '[sdweddingdirectory_slider_section][sdweddingdirectory_slider][sdweddingdirectory_slider_image media_url="%1$s"][/sdweddingdirectory_slider_image][sdweddingdirectory_slider_image media_url="%1$s"][/sdweddingdirectory_slider_image][sdweddingdirectory_slider_image media_url="%1$s"][/sdweddingdirectory_slider_image][/sdweddingdirectory_slider][sdweddingdirectory_find_venue_form_section heading="%2$s" description="%3$s"][sdweddingdirectory_find_venue_form layout="1" venue_category="true" venue_location="true" search_button_text="%4$s" depth_level="2"][/sdweddingdirectory_find_venue_form]<p class="lead text-white text-center">%5$s</p>[sdweddingdirectory_venue_category_icon layout="1" ids="68,67"][/sdweddingdirectory_venue_category_icon][/sdweddingdirectory_find_venue_form_section][/sdweddingdirectory_slider_section]', 

                                            /**
                                             *  1. Media Link
                                             *  -------------
                                             */
                                            esc_url( parent:: placeholder( 'slider' ) ),

                                            /**
                                             *  2. Translation Ready String
                                             *  ---------------------------
                                             */
                                            esc_attr__( 'Find the Perfect Wedding Vendor', 'sdweddingdirectory-shortcodes' ),

                                            /**
                                             *  3. Translation Ready String
                                             *  ---------------------------
                                             */
                                            esc_attr__( 'Search over 360,000 wedding vendors with reviews, pricing, availability and more', 'sdweddingdirectory-shortcodes' ),

                                            /**
                                             *  4. Translation Ready String
                                             *  ---------------------------
                                             */
                                            esc_attr__( 'Search Now', 'sdweddingdirectory-shortcodes' ),

                                            /**
                                             *  5. Translation Ready String
                                             *  ---------------------------
                                             */
                                            esc_attr__( 'Or browse featured categories', 'sdweddingdirectory-shortcodes' )
                                    ),

                                /**
                                 *  Background Slider
                                 *  -----------------
                                 */
                                'sdweddingdirectory_background_image'  =>

                                    /**
                                     *  ShortCode Example
                                     *  -----------------
                                     */
                                    sprintf(   '[sdweddingdirectory_background_image media_url="%1$s"][sdweddingdirectory_find_venue_form_section heading="%2$s" description="%3$s"][sdweddingdirectory_find_venue_form layout="2" venue_category="true" venue_location="true" search_button_text="%4$s" depth_level="2"][/sdweddingdirectory_find_venue_form]<p class="lead text-white text-center">%5$s</p>[sdweddingdirectory_venue_category_icon layout="2" ids="68,67"][/sdweddingdirectory_venue_category_icon][/sdweddingdirectory_find_venue_form_section][/sdweddingdirectory_background_image]',

                                                /**
                                                 *  1. Media Link
                                                 *  -------------
                                                 */
                                                esc_url( parent:: placeholder( 'slider' ) ),

                                                /**
                                                 *  2. Translation Ready String
                                                 *  ---------------------------
                                                 */
                                                esc_attr__( 'Find the Perfect Wedding Vendor', 'sdweddingdirectory-shortcodes' ),

                                                /**
                                                 *  3. Translation Ready String
                                                 *  ---------------------------
                                                 */
                                                esc_attr__( 'Search over 360,000 wedding vendors with reviews, pricing, availability and more', 'sdweddingdirectory-shortcodes' ),

                                                /**
                                                 *  4. Translation Ready String
                                                 *  ---------------------------
                                                 */
                                                esc_attr__( 'Search Now', 'sdweddingdirectory-shortcodes' ),

                                                /**
                                                 *  5. Translation Ready String
                                                 *  ---------------------------
                                                 */
                                                esc_attr__( 'Or browse featured categories', 'sdweddingdirectory-shortcodes' )
                                    )
                            )
                        );
            } );
        }

        /**
         *  Slider Section Load
         *  -------------------
         */
        public static function sdweddingdirectory_slider_section( $atts, $content = null ){

            /**
             *  Load Slider
             *  -----------
             */
            return  sprintf(   '<div class="slider-versin-one"><div class="slider-wrap"> %1$s </div></div>',

                                /**
                                 *  1. Slider Images
                                 *  ----------------
                                 */
                                do_shortcode( 

                                    apply_filters( 'sdweddingdirectory_clean_shortcode', $content ) 
                                )
                    );
        }

        /**
         *  Load Slider
         *  -----------
         */
        public static function sdweddingdirectory_slider( $atts, $content = null ){

            /**
             *  Return : Slider HTML
             *  --------------------
             */
            return  sprintf(   '<div class="owl-carousel owl-theme sdweddingdirectory-home-page-slider">

                                    %1$s

                                </div>',

                                /**
                                 *  1. Load In Between ShortCode / Content
                                 *  --------------------------------------
                                 */
                                do_shortcode( 

                                    apply_filters( 'sdweddingdirectory_clean_shortcode', $content ) 
                                )
                    );
        }

        /**
         *  Load one by one slider images
         *  -----------------------------
         */
        public static function sdweddingdirectory_slider_image( $atts, $content = '' ){

            /**
             *  Extract Attr Data
             *  -----------------
             */
            extract( 

                shortcode_atts( 

                    /**
                     *  Default Args
                     *  ------------
                     */
                    array(

                        'media_url'     =>      esc_url( parent:: placeholder( 'slider' ) ),
                    ), 

                    /**
                     *  Get Args
                     *  --------
                     */
                    $atts,

                    /**
                     *  ShortCode Name
                     *  --------------
                     */
                    esc_attr( __FUNCTION__ )
                )
            );

            if( parent:: _have_data( $media_url ) ){

                /**
                 *   Return Items : HTML
                 *   -------------------
                 */
                return  sprintf(  '<div class="item">

                                        <div class="home-slider">

                                            <img src="%1$s" alt="%2$s %3$s" />

                                        </div>

                                    </div>',

                                    /** 
                                     *  1. Image
                                     *  --------
                                     */
                                    esc_url( $media_url ),

                                    /** 
                                     *  2. Image Alt
                                     *  ------------
                                     */
                                    esc_attr( get_bloginfo( 'name' ) ),

                                    /**
                                     *  3. Image Alt : Translation Ready String
                                     *  ---------------------------------------
                                     */
                                    esc_attr__( 'Carousel Slider', 'sdweddingdirectory-shortcodes' )
                        );
            }
        }

        /**
         *  Load one by one slider images
         *  -----------------------------
         */
        public static function sdweddingdirectory_background_image( $atts, $content = '' ){

            /**
             *  Extract Attr Data
             *  -----------------
             */
            extract( 

                shortcode_atts( 

                    /**
                     *  Default Args
                     *  ------------
                     */
                    array(

                        'media_url'     =>      esc_url( parent:: placeholder( 'slider' ) ),
                    ), 

                    /**
                     *  Get Args
                     *  --------
                     */
                    $atts,

                    /**
                     *  ShortCode Name
                     *  --------------
                     */
                    esc_attr( __FUNCTION__ )
                )
            );

            /**
             *  Filter Background URL
             *  ---------------------
             */
            $media_url = apply_filters( 'sdweddingdirectory/home-slider/background-image', $media_url );

            /**
             *   Return Items : HTML
             *   -------------------
             */
            return  sprintf(   '<div class="slider-versin-two">

                                    <div class="slider-wrap" style="background: url(%1$s) no-repeat center center;">

                                        %2$s

                                    </div>

                                </div>',

                                /** 
                                 *  1. Image
                                 *  --------
                                 */
                                esc_url( $media_url ),

                                /**
                                 *  2. Load In Between ShortCode / Content
                                 *  --------------------------------------
                                 */
                                do_shortcode( 

                                    apply_filters( 'sdweddingdirectory_clean_shortcode', $content ) 
                                )
                    );
        }

        /**
         *  Random Homepage Banner
         *  ----------------------
         */
        public static function randomize_homepage_banner( $image_url = '' ){

            /**
             *  Home page: randomize from home-hero-random-1 through -5
             */
            if( is_front_page() ){

                $banners = [];

                for( $i = 1; $i <= 5; $i++ ){

                    $path = 'assets/images/banners/home-hero-random-' . $i . '.jpg';

                    if( file_exists( get_theme_file_path( $path ) ) ){

                        $banners[] = get_theme_file_uri( $path );
                    }
                }

                if( ! empty( $banners ) ){

                    return $banners[ array_rand( $banners ) ];
                }
            }

            /**
             *  Wedding Planning page (4180) and children: randomize from wedding-planning-hero-random-1 through -5
             */
            if( is_page( 4180 ) || ( is_page() && wp_get_post_parent_id( get_the_ID() ) == 4180 ) ){

                $banners = [];

                for( $i = 1; $i <= 5; $i++ ){

                    $path = 'assets/images/banners/wedding-planning-hero-random-' . $i . '.jpg';

                    if( file_exists( get_theme_file_path( $path ) ) ){

                        $banners[] = get_theme_file_uri( $path );
                    }
                }

                if( ! empty( $banners ) ){

                    return $banners[ array_rand( $banners ) ];
                }
            }

            return $image_url;
        }

        /**
         *  Page Builder *Value* pass here to print features
         *  ------------------------------------------------
         */
        public static function page_builder( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Setting with Merge Empty Fields
                 *  ---------------------------------------
                 */
                extract(  wp_parse_args( $args, self:: default_args()  ) );

                /**
                 *  Slider
                 *  ------
                 */
                if( $layout == esc_attr( '1' ) ){

                    return  do_shortcode(

                                sprintf(   '[sdweddingdirectory_slider_section]

                                                %1$s

                                                <div class="slider-content">

                                                    <div class="container">

                                                        <div class="row">

                                                            <div class="col-xl-8 col-lg-12 mx-auto">

                                                                <h1>%2$s</h1>

                                                                <p class="lead text-white text-center">%3$s</p>

                                                                %6$s

                                                                %4$s

                                                                <div class="slider-category venue-type-layout-1">%5$s</div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            [/sdweddingdirectory_slider_section]',

                                            /**
                                             *  1. Load Slider items
                                             *  --------------------
                                             */
                                            $slider_image,

                                            /**
                                             *  2. Find Venue Form : Heading
                                             *  ------------------------------
                                             */
                                            esc_attr( $form_heading ),

                                            /**
                                             *  3. Find Venue Form : Description
                                             *  ----------------------------------
                                             */
                                            esc_attr( $form_description ),

                                            /**
                                             *  4. Venue Icon Description
                                             *  ---------------------------
                                             */
                                            parent:: _have_data( $venue_category_desc )

                                            ?   sprintf( '<p class="lead text-white text-center">%1$s</p>', 

                                                    /**
                                                     *  1. Have Description ?
                                                     *  ---------------------
                                                     */
                                                    esc_attr( $venue_category_desc )
                                                )

                                            :   '',

                                            /**
                                             *  5. Venue of Options
                                             *  ---------------------
                                             */
                                            SDWeddingDirectory_Shortcode_Venue_Category_Icon:: page_builder( [

                                                'layout'    =>      absint( '1' ),

                                                'ids'       =>      esc_attr( $venue_category_ids ),

                                            ] ),

                                            /**
                                             *  6. Search Button Text
                                             *  ---------------------
                                             */
                                            SDWeddingDirectory_Shortcode_Find_Venue_Form:: page_builder( [

                                                'layout'                        =>      absint( '1' ),

                                                'category_placeholder'          =>      esc_attr__( 'Search By Vendor', 'sdweddingdirectory-shortcodes' ),

                                                'location_placeholder'          =>      esc_attr__( 'Location', 'sdweddingdirectory-shortcodes' ),

                                                'search_button_text'            =>      esc_attr( $submit_button_text )

                                            ] )
                                )
                            );
                }

                /**
                 *  Background Image
                 *  ----------------
                 */
                if( $layout == esc_attr( '2' ) ){

                    return  do_shortcode(

                                sprintf(   '[sdweddingdirectory_background_image media_url="%1$s"]

                                                <div class="slider-content">

                                                    <div class="container">

                                                        <div class="row">

                                                            <div class="col-xl-8 col-lg-12 mx-auto">

                                                                <h1>%2$s</h1>

                                                                <p class="lead text-white text-center">%3$s</p>

                                                                %6$s

                                                                %4$s

                                                                <div class="slider-category venue-type-layout-1">%5$s</div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            [/sdweddingdirectory_background_image]',

                                            /**
                                             *  1. Load Slider items
                                             *  --------------------
                                             */
                                            esc_url( $background_image ),

                                            /**
                                             *  2. Find Venue Form : Heading
                                             *  ------------------------------
                                             */
                                            esc_attr( $form_heading ),

                                            /**
                                             *  3. Find Venue Form : Description
                                             *  ----------------------------------
                                             */
                                            esc_attr( $form_description ),

                                            /**
                                             *  4. Venue Icon Description
                                             *  ---------------------------
                                             */
                                            parent:: _have_data( $venue_category_desc )

                                            ?   sprintf( '<p class="lead text-white text-center">%1$s</p>', 

                                                    /**
                                                     *  1. Have Description ?
                                                     *  ---------------------
                                                     */
                                                    esc_attr( $venue_category_desc )
                                                )

                                            :   '',

                                            /**
                                             *  5. Venue of Options
                                             *  ---------------------
                                             */
                                            SDWeddingDirectory_Shortcode_Venue_Category_Icon:: page_builder( [

                                                'layout'    =>      absint( '2' ),

                                                'ids'       =>      esc_attr( $venue_category_ids ),

                                            ] ),

                                            /**
                                             *  6. Search Button Text
                                             *  ---------------------
                                             */
                                            SDWeddingDirectory_Shortcode_Find_Venue_Form:: page_builder( [

                                                'layout'                        =>      absint( '2' ),

                                                'category_placeholder'          =>      esc_attr__( 'Search By Vendor', 'sdweddingdirectory-shortcodes' ),

                                                'location_placeholder'          =>      esc_attr__( 'Location', 'sdweddingdirectory-shortcodes' ),

                                                'search_button_text'            =>      esc_attr( $submit_button_text )
                                            ] )
                                )
                            );
                }
            }
        }

        /**
         *  Load Script
         *  -----------
         */
        public static function load_script(){

            ?>
            <script>

                (function($) {

                    "use strict";

                    /**
                     *  Object Call
                     *  -----------
                     */
                    $(document).ready( function(){  

                        /**
                         *  Load Owl Carouse : Slider Script
                         *  --------------------------------
                         */
                        SDWeddingDirectory_Elements.init();

                    });

                })(jQuery); 

            </script>
            <?php
        }
    }

    /**
     *  --------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Home Slider One ]
     *  --------------------------------------------
     */
    SDWeddingDirectory_Shortcode_Slider_Version::get_instance();
}
