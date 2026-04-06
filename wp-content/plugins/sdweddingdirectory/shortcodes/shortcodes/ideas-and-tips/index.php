<?php
/**
 *  ----------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Idea & Tips ]
 *  ----------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Idea_And_Tips' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  ----------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Idea & Tips ]
     *  ----------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Idea_And_Tips extends SDWeddingDirectory_Shortcode {

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

            return  [
                        'name'          =>      esc_attr__( 'Wedding Services', 'sdweddingdirectory-shortcodes' ),

                        'icon'          =>      esc_attr( 'sdweddingdirectory-gender' ),

                        'media_url'     =>      esc_url( parent:: placeholder( 'ideas-and-tips' ) ),

                        'link'          =>      esc_url( home_url( '/' ) ),

                        'target'        =>      esc_attr( '_self' ),

                        'style'         =>      absint( '1' )
                    ];
        }

        /**
         *  Inside Content
         *  --------------
         */
        public static function inside_content(){

            return  sprintf( '<h4> <a href="%1$s" target="%2$s">%3$s</a> </h4>',

                            /**
                             *  1. Link ?
                             *  ---------
                             */
                            esc_url( home_url( '/' ) ),

                            /**
                             *  2. Target ?
                             *  -----------
                             */
                            esc_attr( '_self' ),

                            /**
                             *  3. Translation Ready String
                             *  ---------------------------
                             */
                            esc_attr__( 'Wedding Services', 'sdweddingdirectory-shortcodes' )
                    );
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
            add_shortcode( 'sdweddingdirectory_idea_and_tips', [ $this, 'sdweddingdirectory_idea_and_tips' ] );

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

                            'sdweddingdirectory_idea_and_tips'  =>  sprintf( '[sdweddingdirectory_idea_and_tips %1$s]%2$s[/sdweddingdirectory_idea_and_tips]',

                                                                /**
                                                                 *  1. Attribute
                                                                 *  ------------
                                                                 */
                                                                parent:: _shortcode_atts( self:: default_args() ),

                                                                /**
                                                                 *  2. Inside Content
                                                                 *  -----------------
                                                                 */
                                                                self:: inside_content()
                                                            )
                        ] );
            } );

            /**
             *  Placeholder Filter
             *  ------------------
             */
            add_filter( 'sdweddingdirectory/placeholder/shortcode', function( $args = [] ){

                return  array_merge( $args, array(

                            'ideas-and-tips'  =>  esc_url(  plugin_dir_url( __FILE__ ) . 'images/' . 'idea-and-tips.jpg' )

                        ) );
            } );

            /**
             *  Image Size ?
             *  ------------
             */
            add_filter( 'sdweddingdirectory/image-size', function( $args = [] ){

                return  array_merge( $args, array(

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_192x141' ),

                                'name'          =>      esc_attr__( 'Idea And Tips', 'sdweddingdirectory-shortcodes' ),

                                'width'         =>      absint( '192' ),

                                'height'        =>      absint( '141' )
                            ],

                        ) );
            } );
        }

        /**
         *  Slider Section Load
         *  -------------------
         */
        public static function sdweddingdirectory_idea_and_tips( $atts, $content = '' ){

            /**
             *  Extract Attr Data
             *  -----------------
             */
            extract( shortcode_atts( self:: default_args(), $atts, esc_attr( __FUNCTION__ ) ) );

            /**
             *  Handler
             *  -------
             */
            $collection         =       '';

            /**
             *  Style
             *  -----
             */
            $collection         .=      parent:: _shortcode_style_start( $style );

            /**
             *  Content
             *  -------
             */
            $collection         .=      sprintf(   '<div class="ideas-tips-wrap">

                                                        <div class="content">

                                                            <a href="%5$s" target="%6$s"><img src="%1$s" alt="%2$s" /></a>

                                                            <i class="%3$s"></i>

                                                            <a href="%5$s" target="%6$s">%4$s</a>

                                                        </div>

                                                    </div>',

                                                    /**
                                                     *  1. Team Image
                                                     *  -------------
                                                     */
                                                    esc_url( $media_url ),

                                                    /**
                                                     *  2. Image Alt
                                                     *  ------------
                                                     */
                                                    esc_attr( get_bloginfo( 'name' ) ),

                                                    /**
                                                     *  3. Icon
                                                     *  -------
                                                     */
                                                    esc_attr( $icon ),

                                                    /**
                                                     *  4. Content
                                                     *  ----------
                                                     */
                                                    do_shortcode(

                                                        apply_filters( 'sdweddingdirectory_clean_shortcode', $content )
                                                    ),

                                                    /**
                                                     *  5. Link
                                                     *  -------
                                                     */
                                                    esc_url( $link ),

                                                    /**
                                                     *  6. target
                                                     *  ---------
                                                     */
                                                    esc_attr( $target )
                                        );
            /**
             *  Style
             *  -----
             */
            $collection         .=      parent:: _shortcode_style_end( $style );

            /**
             *  Return Data
             *  -----------
             */
            return                      $collection;

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
                 *  Return : Team HTML
                 *  ------------------
                 */
                return  do_shortcode(

                            sprintf(   

                                '[sdweddingdirectory_idea_and_tips icon="%1$s" media_url="%2$s" link="%4$s" target="%5$s"] %3$s [/sdweddingdirectory_idea_and_tips]',

                                /**
                                 *  1. Icon ?
                                 *  ---------
                                 */
                                esc_attr( $icon ),

                                /**
                                 *  2. Media Link ?
                                 *  ---------------
                                 */
                                esc_url( $media_url ),

                                /**
                                 *  3. Team Designation ?
                                 *  ---------------------
                                 */
                                apply_filters( 'sdweddingdirectory_clean_shortcode', $content ),

                                /**
                                 *  4. Link
                                 *  -------
                                 */
                                esc_url( $link ),

                                /**
                                 *  5. target
                                 *  ---------
                                 */
                                esc_attr( $target )
                            )
                        );
            }
        }
    }

    /**
     *  ----------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Idea & Tips ]
     *  ----------------------------------------
     */
    SDWeddingDirectory_Shortcode_Idea_And_Tips::get_instance();
}