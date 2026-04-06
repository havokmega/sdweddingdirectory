<?php
/**
 *  Admin Files
 *  -----------
 */
if( ! class_exists( 'SDWeddingDirectory_Guest_List_Admin_Files' ) && class_exists( 'SDWeddingDirectory_Guest_List_Database' ) ){

    /**
     *  Admin Files
     *  -----------
     */
    class SDWeddingDirectory_Guest_List_Admin_Files extends SDWeddingDirectory_Guest_List_Database{

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
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Guest_List_Admin_Files:: get_instance();
}