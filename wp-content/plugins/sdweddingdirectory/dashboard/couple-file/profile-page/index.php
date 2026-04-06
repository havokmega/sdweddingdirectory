<?php
/**
 * Exit if accessed directly.
 * --------------------------
 */
defined( 'ABSPATH' ) || exit;

/**
 * ---------------------------
 * SDWeddingDirectory - Couple Profile
 * ---------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Profile_Plugin' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

   /**
    *  SDWeddingDirectory - Couple Profile
    *  ---------------------------
    */
    class SDWeddingDirectory_Couple_Profile_Plugin extends SDWeddingDirectory_Config{

        /**
         * A reference to an instance of this class.
         */
        private static $instance;

        private $files = [];

        /**
         * Returns an instance of this class.
         */
        public static function get_instance() {

            if ( null == self::$instance ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Initializes the plugin by setting filters and administration functions.
         */
        public function __construct() {

            /**
             *  SDWeddingDirectory Core
             *  ---------------
             */
            $this->files();
        }


        private function files(){

            /**
             *  Database Access
             *  ---------------
             */
            $this->files[] = 'database/index.php';

            /**
             *  Admin Access File
             *  -----------------
             */
            if( is_admin() ){

                $this->files[] = 'admin-file/meta-box.php';

                $this->files[] = 'admin-file/setting-option.php';
            }

            /**
             *  Couple Menu + Filters
             *  ---------------------
             */
            $this->files[]      =   'filters-hooks/index.php';

            /**
             *  Defaut Tabs Load
             *  ----------------
             */
            $this->files[]      =   'default-tabs/index.php';

            /**
             *  Couple Profile Database
             *  -----------------------
             */
            $this->files[]      =   'couple-file/index.php';

            /**
             *  This Plugin AJAX Scripts Functions here
             *  ---------------------------------------
             */
            if ( wp_doing_ajax() ) {

                $this->files[] = 'ajax/index.php';
            }

            /**
             *  Load Files
             *  ----------
             */
            if( parent:: _is_array( $this->files ) ){

                foreach ( $this->files as $key => $value) {
                    
                    require_once        $value;
                }
            }
        }
    }

    /**
     *  Load Couple Profile Page
     *  ------------------------
     */
    SDWeddingDirectory_Couple_Profile_Plugin:: get_instance();
}