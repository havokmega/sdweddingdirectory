<?php
/**
 *  SDWeddingDirectory - Vendor Dashboard Filter & Hooks
 *  --------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Dashboard_Database' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory - Vendor Dashboard Filter & Hooks
     *  --------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Dashboard_Database extends SDWeddingDirectory_Config{

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

        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Vendor_Dashboard_Database:: get_instance();
}