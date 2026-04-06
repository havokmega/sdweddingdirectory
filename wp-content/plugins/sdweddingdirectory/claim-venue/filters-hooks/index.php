<?php
/**
 *  SDWeddingDirectory Claim Venue Database
 *  ---------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Claim_Venue_Filter_Hook' ) && class_exists( 'SDWeddingDirectory_Claim_Venue_Database' ) ){

    /**
     *  SDWeddingDirectory Claim Venue Database
     *  ---------------------------------
     */
    class SDWeddingDirectory_Claim_Venue_Filter_Hook extends SDWeddingDirectory_Claim_Venue_Database{

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
     *  SDWeddingDirectory Claim Venue Database
     *  ---------------------------------
     */
    SDWeddingDirectory_Claim_Venue_Filter_Hook:: get_instance();
}