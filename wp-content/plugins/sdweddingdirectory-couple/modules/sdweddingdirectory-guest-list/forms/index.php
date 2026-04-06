<?php
/**
 *  SDWeddingDirectory Couple - Guest list sidebar form handler
 *  ---------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Guest_List_Form_Handler' ) && class_exists( 'SDWeddingDirectory_Guest_List_Database' ) ){

	/**
	 *  SDWeddingDirectory Couple - Guest list sidebar form handler
	 *  ---------------------------------------------------
	 */
    class SDWeddingDirectory_Guest_List_Form_Handler extends SDWeddingDirectory_Guest_List_Database{

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
             *  Make sure is couple and on guest list page
             *  ------------------------------------------
             */
            if( parent:: is_couple() && parent:: dashboard_page_set( esc_attr( 'guest-management' ) ) ) {

                /**
                 *  Load one by one shortcode file
                 *  ------------------------------
                 */
                foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
               
                    require_once $file;
                }
            }
        }

    } /* class end **/

    /**
     *  Kicking this off by calling 'get_instance()' method
     *  ---------------------------------------------------
     */
    SDWeddingDirectory_Guest_List_Form_Handler:: get_instance();
}