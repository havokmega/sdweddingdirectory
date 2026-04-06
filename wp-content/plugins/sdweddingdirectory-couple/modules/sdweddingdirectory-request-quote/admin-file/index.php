<?php
/**
 *  SDWeddingDirectory - Admin Files
 *  ------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Request_Quote_Plugin_Admin_Files' ) && class_exists( 'SDWeddingDirectory_Request_Quote_Database' ) ){

    /**
     *  SDWeddingDirectory - Admin Files
     *  ------------------------
     */
    class SDWeddingDirectory_Request_Quote_Plugin_Admin_Files extends SDWeddingDirectory_Request_Quote_Database{

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
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Request_Quote_Plugin_Admin_Files:: get_instance();
}