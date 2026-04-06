<?php
/**
 *  Database information here
 *  -------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Follow_Vendor_Database' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  Database information here
     *  -------------------------
     */
    class SDWeddingDirectory_Follow_Vendor_Database extends SDWeddingDirectory_Config{

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
    SDWeddingDirectory_Follow_Vendor_Database:: get_instance();
}