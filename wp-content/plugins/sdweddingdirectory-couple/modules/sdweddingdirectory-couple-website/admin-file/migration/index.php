<?php
/**
 *  SDWeddingDirectory - Migration Filter 
 *  -----------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Website_Migration' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Database' ) ){

    /**
     *  SDWeddingDirectory - Migration Filter 
     *  -----------------------------
     */
    class SDWeddingDirectory_Couple_Website_Migration extends SDWeddingDirectory_Couple_Website_Database{

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
    SDWeddingDirectory_Couple_Website_Migration:: get_instance();
}