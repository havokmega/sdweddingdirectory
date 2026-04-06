<?php
/**
 *  SDWeddingDirectory - Couple Dashboard Filter & Hooks
 *  --------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Dashboard_Filters' ) && class_exists( 'SDWeddingDirectory_Couple_Dashboard_Database' ) ){

    /**
     *  SDWeddingDirectory - Couple Dashboard Filter & Hooks
     *  --------------------------------------------
     */
    class SDWeddingDirectory_Couple_Dashboard_Filters extends SDWeddingDirectory_Couple_Dashboard_Database{

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
            add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'], absint( '10' ) );

            /**
             *  Load one by one shortcode file
             *  ------------------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once $file;
            }
        }

        /**
         *  1. Dashboard Scripts
         *  --------------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Isset Couple Dashboard ?
             *  ------------------------
             */
            if( parent:: dashboard_page_set( 'couple-dashboard' ) ){

                /**
                 *  Load Library Condition
                 *  ----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/input-dropdown', function( $args = [] ){

                    return  array_merge( $args, [

                                'couple-dashboard'   =>  true

                            ] );
                } );

                /**
                 *  Load Library Condition
                 *  ----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/input-dropdown', function( $args = [] ){

                    return  array_merge( $args, [

                                'couple-dashboard'   =>  true

                            ] );
                } );
            }
        }
    }

   /**
    *  Couple Dashboard Filer Object Call
    *  ----------------------------------
    */
    SDWeddingDirectory_Couple_Dashboard_Filters:: get_instance();
}