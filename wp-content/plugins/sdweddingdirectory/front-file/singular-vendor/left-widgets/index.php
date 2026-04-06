<?php
/**
 *  SDWeddingDirectory - Vendor Left Side Widgets
 *  --------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Singular_Left_Side_Widget' ) && class_exists( 'SDWeddingDirectory_Singular_Vendor' ) ){

    /**
     *  SDWeddingDirectory - Vendor Left Side Widgets
     *  --------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Singular_Left_Side_Widget extends SDWeddingDirectory_Singular_Vendor{

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
           
                require_once    $file;
            }
        }

    }

    /**
     *  SDWeddingDirectory - Vendor Left Side Widgets
     *  --------------------------------------------
     */
    SDWeddingDirectory_Vendor_Singular_Left_Side_Widget::get_instance();
}
