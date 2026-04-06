<?php
/**
 *  SDWeddingDirectory Couple Guest List
 *  ----------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dashboard_Guest_List' ) && class_exists( 'SDWeddingDirectory_Guest_List_Database' ) ){

    /**
     *  SDWeddingDirectory Couple Guest List
     *  ----------------------------
     */
    class SDWeddingDirectory_Dashboard_Guest_List extends SDWeddingDirectory_Guest_List_Database{

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
        public function __construct(){

            /**
             *  Make sure is couple and on guest list page
             *  ------------------------------------------
             */
            if( parent:: is_couple() ) {

                /**
                 *  Load one by one shortcode file
                 *  ------------------------------
                 */
                foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
               
                    require_once $file;
                }
            }
        }
    }

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Dashboard_Guest_List:: get_instance();
}