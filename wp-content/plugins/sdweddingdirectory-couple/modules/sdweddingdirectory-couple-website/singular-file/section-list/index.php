<?php
/**
 *  SDWeddingDirectory - Couple Wedding Website Section
 *  -------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Website_Section' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Files' ) ){

    /**
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    class SDWeddingDirectory_Couple_Website_Section extends SDWeddingDirectory_Couple_Website_Files{

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
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    SDWeddingDirectory_Couple_Website_Section::get_instance();
}