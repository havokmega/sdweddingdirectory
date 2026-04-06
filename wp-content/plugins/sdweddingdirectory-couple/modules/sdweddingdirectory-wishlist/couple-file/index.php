<?php
/**
 *   SDWeddingDirectory - Couple Dashboard Wishlist
 *   --------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Wishlist' ) && class_exists( 'SDWeddingDirectory_WishList_Database' ) ){

    /**
     *   SDWeddingDirectory - Couple Dashboard Wishlist
     *   --------------------------------------
     */
    class SDWeddingDirectory_Wishlist extends SDWeddingDirectory_WishList_Database{

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
            add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'], absint( '20' ) );

            /**
             *  2. Dashboard Page
             *  -----------------
             */
            add_action( 'sdweddingdirectory/couple-dashboard', [$this, 'dashboard_page'], absint( '20' ), absint( '1' ) );

            /**
             *  Make sure it's couple user
             *  --------------------------
             */
            if( parent:: dashboard_page_set( 'vendor-manager' ) ){

                /**
                 *  Load one by one shortcode file
                 *  ------------------------------
                 */
                foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
               
                    require_once $file;
                }
            }
        }

        /**
         *  1. Dashboard Scripts
         *  --------------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Condition
             *  ---------
             */
            $_condition_1   =   parent:: dashboard_page_set( 'vendor-manager' );

            $_condition_2   =   parent:: dashboard_page_set( 'couple-dashboard' );

            /**
             *  Load Script
             *  -----------
             */
            if( $_condition_1 || $_condition_2 ){

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
                    array( 'jquery', 'toastr' ),

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
                 *  If is wishlist page ?
                 *  ---------------------
                 */
                if( $_condition_1 ){

                    /**
                     *  Load Library Condition
                     *  -----------------------
                     */
                    add_filter( 'sdweddingdirectory/enable-script/review', function( $args = [] ){

                        return  array_merge( $args, [ 'vendor-manager'   =>  true ] );
                        
                    } );
                }
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
                if( ! empty( $page ) && $page == esc_attr( 'vendor-manager' )  ){

                    /**
                     *  Load one by one shortcode file
                     *  ------------------------------
                     */
                    foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
                   
                        require_once    $file;
                    }

                    ?><div class="container"><?php

                        /**
                         *  1. Dashboard Title
                         *  ------------------
                         */
                        SDWeddingDirectory_Dashboard:: dashboard_page_header(

                            // 1
                            esc_attr__( 'Vendor Manager', 'sdweddingdirectory-wishlist' )
                        );

                        /**
                         *  My Profile Tabs
                         *  ---------------
                         */
                        $_tabs  =  apply_filters( 'sdweddingdirectory/couple-wishlist/tabs', [] );

                        /**
                         *  2.2. Load Profile Tabs
                         *  ----------------------
                         */
                        if( parent:: _is_array( $_tabs ) ){

                            parent:: _create_tabs( $_tabs, [ 'structure_layout' => absint( '1' ) ] );
                        }

                    ?></div><?php
                }
            }
        }
    }

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Wishlist:: get_instance();
}