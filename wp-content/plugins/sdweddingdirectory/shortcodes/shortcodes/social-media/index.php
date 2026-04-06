<?php
/**
 *  -----------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Social Media ]
 *  -----------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Social_Media' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  -----------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Social Media ]
     *  -----------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Social_Media extends SDWeddingDirectory_Shortcode {

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
                            'layout'            =>      absint( '1' ),

                            'icon'              =>      esc_attr( 'fa fa-facebook' ),

                            'target'            =>      esc_attr( '_self' ),

                            'link'              =>      esc_url( home_url( '/' ) ),

                            'content'           =>      '',
                        ];
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Add ShortCode
             *  -------------
             */
            add_shortcode( 'sdweddingdirectory_social_media', [ $this, 'sdweddingdirectory_social_media' ] );

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

                            'sdweddingdirectory_social_media'   =>  sprintf( '[sdweddingdirectory_social_media %1$s][/sdweddingdirectory_social_media]',

                                                                parent:: _shortcode_atts( self:: default_args() )
                                                            )
                        ] );
            } );
        }

        /**
         *  Slider Section Load
         *  -------------------
         */
        public static function sdweddingdirectory_social_media( $atts, $content = '' ){

            /**
             *  Extract Attr Data
             *  -----------------
             */
            extract( shortcode_atts( self:: default_args(), $atts, esc_attr( __FUNCTION__ ) ) );

            /**
             *  Layout 1 ?
             *  ----------
             */
            if( $layout == absint( '1' ) ){

                /**
                 *  Team Layout 1
                 *  -------------
                 */
                return  sprintf(   '<li>

                                        <a href="%1$s" target="%2$s">

                                            <i class="fa %3$s"></i>

                                        </a>

                                    </li>',

                                    /**
                                     *  1. Social Media Link
                                     *  --------------------
                                     */
                                    esc_url( $link ),

                                    /**
                                     *  2. Target
                                     *  ---------
                                     */
                                    esc_attr( $target ),

                                    /**
                                     *  3. Icon ?
                                     *  ---------
                                     */
                                    esc_attr( $icon )
                        );
            }

            /**
             *  Layout 2 ?
             *  ----------
             */
            if( $layout == absint( '2' ) ){

                /**
                 *  Team Layout 1
                 *  -------------
                 */
                return  sprintf(   '<li>

                                        <a href="%1$s" target="%2$s">

                                            <i class="fa %3$s"></i>

                                        </a>

                                    </li>',

                                    /**
                                     *  1. Social Media Link
                                     *  --------------------
                                     */
                                    esc_url( $link ),

                                    /**
                                     *  2. Target
                                     *  ---------
                                     */
                                    esc_attr( $target ),

                                    /**
                                     *  3. Icon ?
                                     *  ---------
                                     */
                                    esc_attr( $icon )
                        );
            }
        }
    }

    /**
     *  -----------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Social Media ]
     *  -----------------------------------------
     */
    SDWeddingDirectory_Shortcode_Social_Media::get_instance();
}