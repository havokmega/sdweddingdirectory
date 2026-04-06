<?php
/**
 *  SDWeddingDirectory Vendor Profile
 *  -------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dashboard_Vendor_Profile' ) && class_exists( 'SDWeddingDirectory_Vendor_Profile_Database' ) ){

    /**
     *  SDWeddingDirectory Vendor Profile
     *  -------------------------
     */
    class SDWeddingDirectory_Dashboard_Vendor_Profile extends SDWeddingDirectory_Vendor_Profile_Database{

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
        public function __construct() {

            /**
             *  1. Dashboard Scripts
             *  --------------------
             */
            add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'], absint( '100' ) );

            /**
             *  2. Dashboard Page
             *  -----------------
             */
            add_action( 'sdweddingdirectory/vendor-dashboard', [$this, 'dashboard_page'], absint( '100' ), absint( '1' ) );
        }

        /**
         *  1. Load Script
         *  --------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Is Vendor Profile Page ?
             *  ------------------------
             */
            if( parent:: dashboard_page_set( 'vendor-profile' ) ){

                /**
                 *  Load Script
                 *  -----------
                 */
                wp_enqueue_script( 

                    /**
                     *  1. File Name
                     *  ------------
                     */
                    esc_attr( sanitize_title( __CLASS__ ) ),

                    /**
                     *  2. File Path
                     *  ------------
                     */
                    esc_url(   plugin_dir_url( __FILE__ )   .   'script.js'   ),

                    /**
                     *  3. Load After "JQUERY" Load
                     *  ---------------------------
                     */
                    array( 'jquery' ),

                    /**
                     *  4. Bootstrap - Library Version
                     *  ------------------------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ )   .   'script.js' ) ),

                    /**
                     *  5. Load in Footer
                     *  -----------------
                     */
                    true
                );

                /**
                 *  Load Library Condition
                 *  ----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/media-upload', function( $args = [] ){

                    return  array_merge( $args, [

                                'vendor-profile'   =>  true

                            ] );
                } );

                /**
                 *  Load Library Condition
                 *  ----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/summary-editor', function( $args = [] ){

                    return  array_merge( $args, [

                                'vendor-profile'   =>  true

                            ] );
                } );

                /**
                 *  Location Dropdown Field
                 *  -----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/input-dropdown', function( $args = [] ){

                        return  array_merge( $args, [

                                    'vendor-profile'   =>  true

                                ] );
                } );
            }
        }

        /**
         *  2. Dashboard Page
         *  -----------------
         */
        public static function dashboard_page( $args = [] ){

            /**
             *  Have Args
             *  ---------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args,[

                    'layout'        =>      absint( '1' ),

                    'post_id'       =>      '',

                    'page'          =>      ''

                ] ) );

                /**
                 *  Make sure this page is couple dashboard page
                 *  --------------------------------------------
                 */
                if( ! empty( $page ) && $page == esc_attr( 'vendor-profile' ) ){

                    ?><div class="container"><?php

                        /**
                         *  Title
                         *  -----
                         */
                        SDWeddingDirectory_Dashboard:: dashboard_page_header(

                              esc_attr__( 'My Profile', 'sdweddingdirectory' )
                        );

                        /**
                         *  My Profile Tabs
                         *  ---------------
                         */
                        $_tabs  =  apply_filters( 'sdweddingdirectory/vendor-profile/tabs', [] ); 

                        /**
                         *  2.2. Load Profile Tabs
                         *  ----------------------
                         */
                        if( parent:: _is_array( $_tabs ) ){

                            parent:: _create_tabs( $_tabs, [ 'structure_layout' => absint( '3' ) ] );
                        }

                    ?></div><?php
                }
            }
        }
    }

    /**
     *  Kicking this off by calling 'get_instance()' method
     */

    SDWeddingDirectory_Dashboard_Vendor_Profile::get_instance();
}