<?php
/**
 *  Couple Dashboard Managment
 *  --------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Dashboard_Managment' ) ){

    /**
     *  Couple Dashboard Managment
     *  --------------------------
     */
    class SDWeddingDirectory_Couple_Dashboard_Managment{

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
             *  Couple Dashboard Menu
             *  ---------------------
             */
            require_once    'couple-menu.php'; // couple profile page

            /**
             *  couple dashboard page
             *  ---------------------
             */
            require_once    'dashboard/index.php';

            /**
             *  Couple Profile Page
             *  -------------------
             */
            require_once    'profile-page/index.php'; // couple profile page
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Couple_Dashboard_Managment:: get_instance();
}