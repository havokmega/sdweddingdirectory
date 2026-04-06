<?php
/**
 *  -------------------------------
 *  SDWeddingDirectory - Dropdown - Filters
 *  -------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dropdown_Filters' ) && class_exists( 'SDWeddingDirectory_Dropdown_Script' ) ){

    /**
     *  -------------------------------
     *  SDWeddingDirectory - Dropdown - Filters
     *  -------------------------------
     */
    class SDWeddingDirectory_Dropdown_Filters extends SDWeddingDirectory_Dropdown_Script {

        /**
         *  A reference to an instance of this class
         *  ----------------------------------------
         */
        private static $instance;

        /**
         *  Returns an instance of this class.
         *  ----------------------------------
         */
        public static function get_instance() {

            if ( null == self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Load one by one file
             *  --------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once $file;
            }
        }
    }

    /**
     *  -------------------------------
     *  SDWeddingDirectory - Dropdown - Filters
     *  -------------------------------
     */
    SDWeddingDirectory_Dropdown_Filters::get_instance();
}