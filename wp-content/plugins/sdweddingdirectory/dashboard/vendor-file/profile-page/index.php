<?php
/**
 * Exit if accessed directly.
 * --------------------------
 */
defined( 'ABSPATH' ) || exit;

/**
 * ---------------------------
 * SDWeddingDirectory - Vendor Profile
 * ---------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Profile_Plugin' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

   /**
    *  SDWeddingDirectory - Vendor Profile
    *  ---------------------------
    */
    class SDWeddingDirectory_Vendor_Profile_Plugin extends SDWeddingDirectory_Config{

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
             *  Vendor Filters
             *  --------------
             */
            $this->files[] = 'filters-hooks/index.php';

            /**
             *  Admin Access
             *  ------------
             */
            if( is_admin() ){

                $this->files[] = 'admin-file/meta-box.php';

                $this->files[] = 'admin-file/setting-option.php';
            }

            /**
             *  Vendor Files
             *  ------------
             */
            $_condition_1 = SDWeddingDirectory_Config:: is_vendor();

            $_condition_2 = SDWeddingDirectory_Config:: dashboard_page_set( esc_attr( 'vendor-dashboard' ) );

            /**
             *  If is vendor
             *  ------------
             */
            if( $_condition_1 ){

                /**
                 *  Vendor Profile Fields
                 *  ---------------------
                 */
                $this->files[] = 'default-tabs/index.php';

                /**
                 *  Vendor Profile Database
                 *  -----------------------
                 */
                $this->files[] = 'vendor-file/index.php';

                /**
                 *  This Plugin AJAX Scripts Functions here
                 *  ---------------------------------------
                 */
                if ( wp_doing_ajax() ) {

                    $this->files[] = 'ajax/index.php';
                }
            }

            /**
             *  Load Files
             *  ----------
             */
            if( parent:: _is_array( $this->files ) ){

                foreach ( $this->files as $key => $value) {
                    
                    require_once $value;
                }
            }
        }
    }

    /**
     *  Load Vendor Profile Page
     *  ------------------------
     */
    SDWeddingDirectory_Vendor_Profile_Plugin:: get_instance();
}