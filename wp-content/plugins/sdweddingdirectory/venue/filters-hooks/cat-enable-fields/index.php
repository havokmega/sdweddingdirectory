<?php
/**
 *  SDWeddingDirectory - Category wise enable fields
 *  ----------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Category_Select_Event' ) && class_exists( 'SDWeddingDirectory_Vendor_Venue_Filters' ) ){

    /**
     *  SDWeddingDirectory - Category wise enable fields
     *  ----------------------------------------
     */
    class SDWeddingDirectory_Venue_Category_Select_Event extends SDWeddingDirectory_Vendor_Venue_Filters{

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
    SDWeddingDirectory_Venue_Category_Select_Event:: get_instance();
}