<?php
/**
 *  -----------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Testimonials ]
 *  -----------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Testimonials' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  -----------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Testimonials ]
     *  -----------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Testimonials extends SDWeddingDirectory_Shortcode {

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
                            'style'             =>      absint( '0' ),

                            'layout'            =>      absint( '1' ),

                            'post_id'           =>      absint( '0' ),
                        ];
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  1. Testimonials Item
             *  --------------------
             */
            add_shortcode( 'sdweddingdirectory_testimonial', [ $this, 'sdweddingdirectory_testimonial' ] );

            /**
             *  SDWeddingDirectory - ShortCode Info
             *  ---------------------------
             */
            add_filter( 'sdweddingdirectory_shortcode_list', function( $args = [] ){

                /**
                 *  Get Plugin Information
                 *  ----------------------
                 */
                return  array_merge( $args, [

                            'sdweddingdirectory_testimonial'    =>  sprintf( '[sdweddingdirectory_testimonial %1$s][/sdweddingdirectory_testimonial]',

                                                                parent:: _shortcode_atts( self:: default_args() )
                                                            )
                        ] );
            } );

            /**
             *  2. SDWeddingDirectory - Placeholder Filter
             *  ----------------------------------
             */
            add_filter( 'sdweddingdirectory/placeholder/shortcode', function( $args = [] ){

                return  array_merge( $args, array(

                            'testimonial'  =>  esc_url(  plugin_dir_url( __FILE__ ) . 'images/' . 'testimonials.jpg' )

                        ) );
            } );
        }

        /**
         *  Load one by one testimonials section
         *  ------------------------------------
         */
        public static function sdweddingdirectory_testimonial( $atts, $content = '' ){

            /**
             *  Extract Attr Data
             *  -----------------
             */
            extract( shortcode_atts( self:: default_args(), $atts, esc_attr( __FUNCTION__ ) ) );

            /**
             *  Make sure post id not empty !
             *  -----------------------------
             */
            if( empty( $post_id  ) ){

                return;
            }

            /**
             *  Team Post Helper
             *  ----------------
             */
            extract( [

                'collection'    =>      '',

                'name'          =>      esc_attr( get_the_title( $post_id ) ),

                'image'         =>      apply_filters( 'sdweddingdirectory/media-data', [

                                            'media_id'         =>  get_post_meta( absint( $post_id ), sanitize_key( '_thumbnail_id' ), true ),

                                        ] ),

                'content'       =>      esc_attr( get_post_meta( $post_id, sanitize_key( 'content' ), true ) ),

                'location'      =>      esc_attr( get_post_meta( $post_id, sanitize_key( 'location' ), true ) ),

                'rating'        =>      absint( get_post_meta( $post_id, sanitize_key( 'rating' ), true ) )

            ] );

            /**
             *  Make sure Style not emtpy!
             *  --------------------------
             */
            if( ! empty( $style ) ){

                $collection         .=      parent:: _shortcode_style_start( $style );
            }

            /**
             *  Layout 1
             *  --------
             */
            if( $layout == absint( '1' ) ){

                $collection         .=

                sprintf(   '<div class="customer-feedback-wrap">

                                <div class="content">

                                    <div class="icon">

                                        <i class="sdweddingdirectory-chat"></i>

                                    </div>

                                    %1$s

                                    <div class="rating">

                                        <span class="sdweddingdirectory_review rating-star" data-review="%2$s"></span>

                                    </div>

                                </div>

                                <div class="name-wrap">

                                    <img src="%3$s" alt="%4$s %5$s %6$s %7$s" />

                                    <div class="text">

                                        <h3>%5$s</h3>

                                        <div>%6$s</div>

                                    </div>

                                </div>

                            </div>',

                            /**
                             *  1. Content
                             *  ----------
                             */
                            esc_textarea( $content ),

                            /** 
                             *  2. Star Rating
                             *  --------------
                             */
                            absint( $rating ),

                            /** 
                             *  3. Testimonials Image
                             *  ---------------------
                             */
                            esc_url( $image ),

                            /**
                             *  4. Image Alt
                             *  ------------
                             */
                            esc_attr( get_bloginfo( 'name' ) ),

                            /** 
                             *  5. Name
                             *  -------
                             */
                            esc_attr( $name ),

                            /** 
                             *  6. Location
                             *  -----------
                             */
                            esc_attr( $location ),

                            /**
                             *  7. Translation Ready String : Image Alt
                             *  ---------------------------------------
                             */
                            esc_attr__( 'Client Feedback Testimonial', 'sdweddingdirectory-shortcodes' )
                );
            }

            /**
             *  Layout 2
             *  --------
             */
            if( $layout == absint( '2' ) ){

                $collection         .=

                sprintf(   '<div class="customer-feedback-alternate">

                                <div class="content">

                                    <div class="name-wrap">

                                        <img src="%1$s" alt="%2$s %3$s %4$s %6$s" />

                                        <div class="head">

                                            <h3>%3$s</h3>

                                            <div>%4$s</div>

                                        </div>

                                        <div class="icon">

                                            <i class="sdweddingdirectory-chat"></i>

                                        </div>

                                    </div>

                                    <div class="text"> %5$s </div>

                                </div>

                            </div>',

                        /** 
                         *  1. Testimonials Image
                         *  ---------------------
                         */
                        esc_url( $image ),

                        /**
                         *  2. Image Alt
                         *  ------------
                         */
                        esc_attr( get_bloginfo( 'name' ) ),

                        /** 
                         *  3. Name
                         *  -------
                         */
                        esc_attr( $name ),

                        /** 
                         *  4. Location
                         *  -----------
                         */
                        esc_attr( $location ),

                        /**
                         *  5. Content
                         *  ----------
                         */
                        do_shortcode(

                            apply_filters(  'sdweddingdirectory_clean_shortcode',  $content )
                        ),

                        /**
                         *  6. Translation Ready String : Image Alt
                         *  ---------------------------------------
                         */
                        esc_attr__( 'Client Feedback Testimonial', 'sdweddingdirectory-shortcodes' )                            
                );
            }

            /**
             *  Make sure Style not emtpy!
             *  --------------------------
             */
            if( ! empty( $style ) ){

                $collection         .=      parent:: _shortcode_style_end( $style );
            }

            /**
             *  Return
             *  ------
             */
            return              $collection;
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
                extract( wp_parse_args( $args, self:: default_args() ) );

                /**
                 *  Return : Testimonials HTML
                 *  --------------------------
                 */
                return  do_shortcode(

                            sprintf(

                                '[sdweddingdirectory_testimonial layout="%1$s" style="%2$s" post_id="%3$s"][/sdweddingdirectory_testimonial]',

                                /**
                                 *  1. Have Layout ?
                                 *  ----------------
                                 */
                                esc_attr( $layout ),

                                /**
                                 *  2. Have Media ?
                                 *  ---------------
                                 */
                                esc_attr( $style ),

                                /**
                                 *  3. Post ID
                                 *  ----------
                                 */
                                esc_attr( $post_id )
                            )
                        );
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
                    $( document ).ready( function(){

                        /**
                         *  Call Object
                         *  -----------
                         */
                        if( typeof SDWeddingDirectory_Elements === 'object' ){

                            SDWeddingDirectory_Elements.init();
                        }

                        if( typeof SDWeddingDirectory_Rating === 'object' ){

                            SDWeddingDirectory_Rating.init();
                        }

                    } );

                })(jQuery);

            </script>
            <?php
        }
    }

    /**
     *  -----------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Testimonials ]
     *  -----------------------------------------
     */
    SDWeddingDirectory_Shortcode_Testimonials::get_instance();
}