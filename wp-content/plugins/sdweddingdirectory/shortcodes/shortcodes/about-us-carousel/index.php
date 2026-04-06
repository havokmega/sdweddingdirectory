<?php
/**
 *  --------------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Home Slider One ]
 *  --------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_About_Us_Carousel' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  --------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Home Slider One ]
     *  --------------------------------------------
     */
    class SDWeddingDirectory_Shortcode_About_Us_Carousel extends SDWeddingDirectory_Shortcode {

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
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  1. About Us Section
             *  -------------------
             */
            add_shortcode( 'sdweddingdirectory_about_us_carousel', [ $this, 'sdweddingdirectory_about_us_carousel' ] );

            /**
             *  3. Load Slider Images
             *  ---------------------
             */
            add_shortcode( 'sdweddingdirectory_about_us_image',  [ $this, 'sdweddingdirectory_about_us_image' ] );

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
                                    'id'            =>      esc_attr( 'sdweddingdirectory_img_1250x555' ),

                                    'name'          =>      esc_attr__( 'About Us Carousel', 'sdweddingdirectory-shortcodes' ),

                                    'width'         =>      absint( '1250' ),

                                    'height'        =>      absint( '555' )
                                ],
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
                                'sdweddingdirectory_about_us_carousel_section'  =>

                                    /**
                                     *  ShortCode Example
                                     *  -----------------
                                     */
                                    sprintf(   '[sdweddingdirectory_about_us_carousel][sdweddingdirectory_about_us_image media_url="%1$s"][/sdweddingdirectory_about_us_image][sdweddingdirectory_about_us_image media_url="%1$s"][/sdweddingdirectory_about_us_image][sdweddingdirectory_about_us_image media_url="%1$s"][/sdweddingdirectory_about_us_image][/sdweddingdirectory_about_us_carousel]',

                                                /**
                                                 *  1. Media Link
                                                 *  -------------
                                                 */
                                                esc_url( parent:: placeholder( 'about-us' ) )
                                    )
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

                                'about-us'  =>  esc_url(  plugin_dir_url( __FILE__ ) . 'images/' . 'about-us.jpg' )
                            )
                        );
            } );

            /**
             *  3. SDWeddingDirectory - Default Setting
             *  -------------------------------
             */
            add_filter( 'sdweddingdirectory_about_us_carousel_setting', function(){

                /**
                 *  Add Slider Placeholder
                 *  ----------------------
                 */
                return  array(

                            'id'            =>  '',

                            'class'         =>  '',

                            'media_url'     =>  esc_url( parent:: placeholder( 'about-us' ) ),

                            'content'       =>  '',
                        );
            } );
        }

        /**
         *  Load Slider
         *  -----------
         */
        public static function sdweddingdirectory_about_us_carousel( $atts, $content = null ){

            /**
             *  Return : Slider HTML
             *  --------------------
             */
            return  sprintf(   '<div class="owl-carousel owl-theme sdweddingdirectory-about-us-carousel">

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
        public static function sdweddingdirectory_about_us_image( $atts, $content = '' ){

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
                    apply_filters( 'sdweddingdirectory_about_us_carousel_setting', [] ),

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

                                        <div class="about-slider">

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
                extract(

                    wp_parse_args(

                        $args,

                        apply_filters( 'sdweddingdirectory_about_us_carousel_setting', [] )
                    )
                );

                if( parent:: _have_data( $content ) ){

                    return  do_shortcode(

                                sprintf( '[sdweddingdirectory_about_us_carousel] %1$s [/sdweddingdirectory_about_us_carousel]', 

                                    /**
                                     *  1. Content
                                     *  ----------
                                     */
                                    $content
                                )
                            );
                }
            }
        }
    }

    /**
     *  --------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Home Slider One ]
     *  --------------------------------------------
     */
    SDWeddingDirectory_Shortcode_About_Us_Carousel::get_instance();
}