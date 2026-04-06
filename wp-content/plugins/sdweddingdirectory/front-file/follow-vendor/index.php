<?php
/**
 *  --------------------------
 *  SDWeddingDirectory - Follow Vendor
 *  --------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Follow_Vendor_Plugin' ) && class_exists( 'SDWeddingDirectory_Front_End_Modules' ) ){

    /**
     *  --------------------------
     *  SDWeddingDirectory - Follow Vendor
     *  --------------------------
     */
    class SDWeddingDirectory_Follow_Vendor_Plugin extends SDWeddingDirectory_Front_End_Modules{

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

        /**
         *  Member variable
         *  ---------------
         *  @var array
         *  ----------
         */
        private $files = [];

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
         *  Load Class
         *  ----------
         */
        public function __construct() {

            /**
             *  Admin File
             *  ----------
             */
            if( is_admin() ){

                $this->files[] = 'admin-file/meta-box.php';

                $this->files[] = 'admin-file/setting-option.php';
            }

            /**
             *  Couple Real Wedding Database
             *  ----------------------------
             */
            $this->files[] = 'database/index.php';

            /**
             *  AJAX Scripts
             *  ------------
             */
            if ( wp_doing_ajax() ) {

                $this->files[] = 'ajax/index.php';
            }

            /**
             *  Follow Button Script
             *  --------------------
             */
            $this->files[] = 'follow-button/index.php';

            /**
             *  Load File
             *  ---------
             */
            if( parent:: _is_array( $this->files ) ){

                foreach ( $this->files as $key => $value) {

                    require_once $value;
                }
            }
        }
    }

    /**
     *  --------------------------
     *  SDWeddingDirectory - Follow Vendor
     *  --------------------------
     */
    SDWeddingDirectory_Follow_Vendor_Plugin:: get_instance();
}