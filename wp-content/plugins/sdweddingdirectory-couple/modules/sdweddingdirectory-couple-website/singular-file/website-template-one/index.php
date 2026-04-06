<?php
/**
 *  SDWeddingDirectory - Couple Wedding Website Template One
 *  ------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Website_Template_One' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Files' ) ){

    /**
     *  SDWeddingDirectory - Couple Wedding Website Template One
     *  ------------------------------------------------
     */
    class SDWeddingDirectory_Couple_Website_Template_One extends SDWeddingDirectory_Couple_Website_Files{

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

        /**
         *  Initiator
         *  ---------
         */
        public static function get_instance() {

            if ( ! isset( self::$instance ) ) {

                self::$instance = new self;
            }

            return self::$instance;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct(){

            /**
             *  1. Wedding Website script
             *  -------------------------
             */
            add_action( 'wp_enqueue_scripts', [ $this, 'sdweddingdirectory_script' ] );

            /**
             *  2. Singular Website
             *  -------------------
             */
            add_action( 'sdweddingdirectory/website/detail-page', [ $this, 'wedding_website_page_load' ] );
        }

        /**
         *  Template Condition
         *  ------------------
         */
        public static function website_template_load(){

            return  esc_attr( 'website_template_layout_1' );
        }

        /**
         *  Template Load ?
         *  ---------------
         */
        public static function load_layout(){

            return  parent:: get_website_meta( 'website_template_layout' ) ==  self:: website_template_load();
        }

        /**
         *  Get Post Data
         *  -------------
         */
        public static function get_website_meta( $args = '' ){

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _have_data( $args ) ){

                return  get_post_meta( absint( get_the_ID() ), sanitize_key( $args ), true );
            }
        }

        /**
         *   1. Wedding Website script
         *   -------------------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Make sure couple selected template is loaded on web
             *  ---------------------------------------------------
             */
            if( is_singular( 'website' ) && self:: load_layout() ){

                /**
                 *  Slug
                 *  ----
                 */
                $_slug      =   sanitize_title( __CLASS__ );

                /**
                 *  Load Style
                 *  ----------
                 */
                wp_enqueue_style(

                    /**
                     *  slug
                     *  ----
                     */
                    esc_attr( $_slug ),

                    /**
                     *  File link
                     *  ---------
                     */
                    is_rtl()

                    ?   esc_url( plugin_dir_url( __FILE__ ) .   'style.rtl.css' )

                    :   esc_url( plugin_dir_url( __FILE__ ) .   'style.css' ),

                    /**
                     *  Dependancy
                     *  ----------
                     */
                    array( 'global-style' ),

                    /**
                     *  Version
                     *  -------
                     */
                    is_rtl()

                    ?   esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ )   .   'style.rtl.css' ) )

                    :   esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ )   .   'style.css' ) ),

                    /**
                     *  All Media
                     *  ---------
                     */
                    esc_attr( 'all' )
                );

                /**
                 *  Load - Script
                 *  -------------
                 */
                wp_enqueue_script( 

                    /**
                     *  slug
                     *  ----
                     */
                    esc_attr( $_slug ),

                    /**
                     *  File link
                     *  ---------
                     */
                    esc_url( plugin_dir_url( __FILE__ ) .   'script.js' ),

                    /**
                     *  Dependancy
                     *  ----------
                     */
                    array( 'jquery' ),

                    /**
                     *  Version
                     *  -------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ )   .   'script.js' ) ),

                    /**
                     *  Load Bottom
                     *  -----------
                     */
                    true
                );

                /**
                 *  Load Library Condition
                 *  ----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/magnific-popup', function( $args = [] ){

                    return  array_merge( $args, [

                                'is_singular_website'   =>  true

                            ] );
                } );

                /**
                 *  Load Library Condition
                 *  ----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/map-marker', function( $args = [] ){

                    return  array_merge( $args, [

                                'is_singular_website'   =>  true

                            ] );
                } );

                /**
                 *  Load Library Condition
                 *  ----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/map', function( $args = [] ){

                    return  array_merge( $args, [

                                'is_singular_website'   =>  true

                            ] );
                } );

                /**
                 *  Load Isotop library
                 *  -------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/isotope', function( $args = [] ){

                    return  array_merge( $args, [

                                'is_singular_website'   =>  true

                            ] );
                } );
            }
        }

        /**
         *  2. Singular Website
         *  -------------------
         */
        public static function wedding_website_page_load(){

            /**
             *  1. Load Style 1
             *  ---------------
             */
            if( self:: load_layout() ){

                /**
                 *  Wedding Website Section
                 *  -----------------------
                 */
                $wedding_website_section     =   apply_filters( 'sdweddingdirectory/wedding-website/layout-1', [] );

                /**
                 *  Is Array ?
                 *  ----------
                 */
                if( parent:: _is_array( $wedding_website_section ) ){

                    foreach( $wedding_website_section as $key => $value ){

                        /**
                         *  Extract
                         *  -------
                         */
                        extract( $value );

                        /**
                         *  Section Start
                         *  -------------
                         */
                        printf( '<section id="%1$s">', esc_attr( $key ) );

                        /**
                         *  Function Call
                         *  -------------
                         */
                        call_user_func( $member );

                        /**
                         *  Section End
                         *  -----------
                         */
                        print '</section>';
                    }
                }
            }
        }
    }

    /**
     *  SDWeddingDirectory - Couple Wedding Website Template One
     *  ------------------------------------------------
     */
    SDWeddingDirectory_Couple_Website_Template_One::get_instance();
}
