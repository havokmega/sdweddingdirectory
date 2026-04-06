<?php
/**
 *  Vendor Dashboard Managment
 *  --------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Dashboard_Managment' ) ){

    /**
     *  Vendor Dashboard Managment
     *  --------------------------
     */
    class SDWeddingDirectory_Vendor_Dashboard_Managment{

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
             *  Vendor Dashboard Menu
             *  ---------------------
             */
            require_once 'vendor-menu.php'; // vendor profile page

            /**
             *  vendor dashboard page
             *  ---------------------
             */
            require_once 'dashboard/index.php'; // vendor dashboard page

            /**
             *  Vendor Profile Page
             *  -------------------
             */
            require_once 'profile-page/index.php'; // vendor profile page

            /**
             *  Vendor Set Pricing Page
             *  -----------------------
             */
            require_once 'set-pricing/index.php';
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Vendor_Dashboard_Managment:: get_instance();
}
