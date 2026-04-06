<?php
/**
 *  -----------------------------------------------
 *  SDWeddingDirectory - Couple Profile Tab Fields - Object
 *  -----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Profile_Tabs_Fields' ) && class_exists( 'SDWeddingDirectory_Couple_Profile_Database' ) ){

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Couple Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    class SDWeddingDirectory_Couple_Profile_Tabs_Fields extends SDWeddingDirectory_Couple_Profile_Database{

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
             *  Load one by one shortcode file
             *  ------------------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once $file;
            }
        }
    }

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Couple Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    SDWeddingDirectory_Couple_Profile_Tabs_Fields::get_instance();
}