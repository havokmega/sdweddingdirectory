<?php
/**
 *  ------------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Section Title ]
 *  ------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Section_Title' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  ------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Section Title ]
     *  ------------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Section_Title extends SDWeddingDirectory_Shortcode {

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
                            'id'            =>      '',

                            'align'         =>      sanitize_html_class( 'text-center' ),

                            'class'         =>      '',
                        ];
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  1. ShortCode : Section Title 
             *  ----------------------------
             */
            add_shortcode( 'sdweddingdirectory_section',  [ $this, 'sdweddingdirectory_section' ] );

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

                            'sdweddingdirectory_section'    =>  sprintf( '[sdweddingdirectory_section %1$s][/sdweddingdirectory_section]',

                                                            parent:: _shortcode_atts( self:: default_args() )
                                                        )
                        ] );
            } );
        }

        /**
         *  Slider Section Load
         *  -------------------
         */
        public static function sdweddingdirectory_section( $atts, $content = '' ){

            /**
             *  Extract Attr Data
             *  -----------------
             */
            extract( shortcode_atts( self:: default_args(), $atts, esc_attr( __FUNCTION__ ) ) );

            /**
             *  Load Slider
             *  -----------
             */
            return  sprintf(   '<div %1$s class="section-title col %2$s">%3$s</div>',

                            /**
                             *  1. ID
                             *  -----
                             */
                            parent:: _have_attr_value( array(

                                'val'   =>  $id,

                                'attr'  =>  esc_attr( 'id' )
                            ) ),

                            /**
                             *  2. Class
                             *  --------
                             */
                            $class,

                            /**
                             *  3. Content
                             *  ----------
                             */
                            $content
                    );
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
                 *  Print
                 *  -----
                 */
                return  do_shortcode(

                            sprintf( '[sdweddingdirectory_section %1$s class="%2$s"]%3$s[/sdweddingdirectory_section]',

                                /**
                                 *  1. Have ID ?
                                 *  ------------
                                 */
                                parent:: _have_attr_value( array(

                                    'val'   =>  $id,

                                    'attr'  =>  esc_attr( 'id' )
                                ) ),

                                /**
                                 *  2. Have Class ?
                                 *  ---------------
                                 */
                                $class,

                                /**
                                 *  3. Content
                                 *  ----------
                                 */
                                $content
                        ) );
            }
        }
    }

    /**
     *  ------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Section Title ]
     *  ------------------------------------------
     */
    SDWeddingDirectory_Shortcode_Section_Title::get_instance();
}