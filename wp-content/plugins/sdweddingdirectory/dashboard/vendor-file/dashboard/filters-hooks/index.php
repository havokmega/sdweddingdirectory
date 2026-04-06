<?php
/**
 *  SDWeddingDirectory - Filter & Hooks
 *  ---------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Dashboard_Filters' ) && class_exists( 'SDWeddingDirectory_Vendor_Dashboard_Database' ) ){

    /**
     *  SDWeddingDirectory - Filter & Hooks
     *  ---------------------------
     */
    class SDWeddingDirectory_Vendor_Dashboard_Filters extends SDWeddingDirectory_Vendor_Dashboard_Database{

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

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Vendor_Dashboard_Filters:: get_instance();
}