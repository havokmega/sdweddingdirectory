<?php
/**
 *  SDWeddingDirectory - Vendor Filter & Hooks
 *  ----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Profile_Filters' ) && class_exists( 'SDWeddingDirectory_Vendor_Profile_Database' ) ){

    /**
     *  SDWeddingDirectory - Vendor Filter & Hooks
     *  ----------------------------------
     */
    class SDWeddingDirectory_Vendor_Profile_Filters extends SDWeddingDirectory_Vendor_Profile_Database{

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
             *  Load one by one shortcode file
             *  ------------------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once $file;
            }
        }
    }

    /**
     *  SDWeddingDirectory - Vendor Filter & Hooks
     *  ----------------------------------
     */
    SDWeddingDirectory_Vendor_Profile_Filters:: get_instance();
}