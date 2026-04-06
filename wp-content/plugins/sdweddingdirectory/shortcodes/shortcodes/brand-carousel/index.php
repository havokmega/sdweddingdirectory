<?php
/**
 *  -------------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Brand Carousel ]
 *  -------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Carousel_Images' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  -------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Brand Carousel ]
     *  -------------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Carousel_Images extends SDWeddingDirectory_Shortcode {

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

            return      [
                            'media_url'     =>      esc_url( parent:: placeholder( 'partners-1' ) ),

                            'style'         =>      absint( '1' ),
                        ];
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Brand Carousel item [ Child ] - ShortCode
             *  -----------------------------------------
             */
            add_shortcode( 'sdweddingdirectory_carousel', [ $this, 'sdweddingdirectory_carousel' ] );

            /**
             *  SDWeddingDirectory - ShortCode Info
             *  ---------------------------
             */
            add_filter( 'sdweddingdirectory_shortcode_list', function( $args = [] ){

                /**
                 *  Get Information
                 *  ---------------
                 */
                return  array_merge( $args, [

                            'sdweddingdirectory_carousel'     =>    sprintf(   '[sdweddingdirectory_carousel %1$s][/sdweddingdirectory_carousel]',

                                                                parent:: _shortcode_atts( self:: default_args() )
                                                            )
                        ] );
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
                return  array_merge( $args, array(

                            'partners-1'  =>  esc_url(  plugin_dir_url( __FILE__ ) . 'images/' . 'partners-1.png' ),

                            'partners-2'  =>  esc_url(  plugin_dir_url( __FILE__ ) . 'images/' . 'partners-2.png' ),

                            'partners-3'  =>  esc_url(  plugin_dir_url( __FILE__ ) . 'images/' . 'partners-3.png' ),

                            'partners-4'  =>  esc_url(  plugin_dir_url( __FILE__ ) . 'images/' . 'partners-4.png' ),

                            'partners-5'  =>  esc_url(  plugin_dir_url( __FILE__ ) . 'images/' . 'partners-5.png' ),
                            
                        ) );
            } );
        }

        /**
         *  Brand Carousel item [ Child ] - ShortCode
         *  -----------------------------------------
         */
        public static function sdweddingdirectory_carousel( $atts, $content = '' ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( shortcode_atts( self:: default_args(), $atts, esc_attr( __FUNCTION__ ) ) );

            /**
             *  Collection
             *  ----------
             */
            $collection         =       '';

            $collection         .=      parent:: _shortcode_style_start( $style );

            $collection         .=      sprintf(   '<div class="partners-slider">

                                                        <img src="%1$s" alt="%2$s %3$s" />

                                                    </div>',

                                            /** 
                                             *  1. Image Link
                                             *  -------------
                                             */
                                            parent:: _have_data( $media_url ) 

                                            ?   esc_url( $media_url )

                                            :   esc_url( parent:: placeholder( 'partners-1' ) ),

                                            /**
                                             *  2. Image Alt
                                             *  ------------
                                             */
                                            esc_attr( get_bloginfo( 'name' ) ),

                                            /**
                                             *  3. Image Alt : Translation Ready String
                                             *  ---------------------------------------
                                             */
                                            esc_attr__( 'Partner Brand Logo Company Collaboration', 'sdweddingdirectory-shortcodes' )
                                        );

            $collection         .=      parent:: _shortcode_style_end( $style );

            /**
             *  Return Button HTML
             *  ------------------
             */
            return      $collection;
        }

        /**
         *  Page Builder : Args
         *  -------------------
         */
        public static function page_builder( $args = [] ){

            /** 
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, self:: default_args() ) );

                /**
                 *  Render : HTML
                 *  -------------
                 */
                return  do_shortcode(

                            sprintf(  '[sdweddingdirectory_carousel media_url="%1$s" style="%2$s"][/sdweddingdirectory_carousel]',

                                /** 
                                 *  1. Media Link
                                 *  -------------
                                 */
                                $media_url,

                                /**
                                 *  2. Have Style
                                 *  -------------
                                 */
                                $style
                            )
                        );
            }
        }
    }

    /**
     *  -------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Brand Carousel ]
     *  -------------------------------------------
     */
    SDWeddingDirectory_Shortcode_Carousel_Images::get_instance();
}