<?php 
/**
 *  SDWeddingDirectory! - Admin Settings
 *  ----------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Admin_Settings' ) && class_exists( 'SDWeddingDirectory_Config' )  ) {

	/**
	 *  SDWeddingDirectory! - Admin Settings
	 *  ----------------------------
	 */
	class SDWeddingDirectory_Admin_Settings extends SDWeddingDirectory_Config{

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
         *  Object Constructor
         *  ------------------
         */
	    public function __construct(){

            /**
             *  Load one by one shortcode file
             *  ------------------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once $file;
            }

            /**
             *  Custom WordPress Admin Dashboard
             *  --------------------------------
             */
            require_once plugin_dir_path( __FILE__ ) . 'admin-dashboard.php';
        }
	}

	/**
	 *  SDWeddingDirectory! - Admin Settings
	 *  ----------------------------
	 */
	SDWeddingDirectory_Admin_Settings:: get_instance();
}
