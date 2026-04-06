<?php 
/**
 *  SDWeddingDirectory! - Front End Modules
 *  -------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Front_End_Modules' ) && class_exists( 'SDWeddingDirectory_Form_Fields' )  ) {

	/**
	 *  SDWeddingDirectory! - Front End Modules
	 *  -------------------------------
	 */
	class SDWeddingDirectory_Front_End_Modules extends SDWeddingDirectory_Form_Fields {

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
        }
	}

	/**
	 *  SDWeddingDirectory! - Front End Modules
	 *  -------------------------------
	 */
	SDWeddingDirectory_Front_End_Modules:: get_instance();
}