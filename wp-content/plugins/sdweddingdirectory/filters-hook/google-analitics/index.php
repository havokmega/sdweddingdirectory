<?php
/**
 *  SDWeddingDirectory - GA4 Analitics
 *  --------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_GA4_Analitics' ) && class_exists( 'SDWeddingDirectory_Core_Filters' ) ){

    /**
     *  SDWeddingDirectory - GA4 Analitics
     *  --------------------------
     */
    class SDWeddingDirectory_GA4_Analitics extends SDWeddingDirectory_Core_Filters{

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
             *  Menu Class
             *  ----------
             */
            add_action( 'wp_head', [ $this, 'install_GA4' ] );
        }

        /**
         *  If Dev Mode Enable to run this script in head
         *  ---------------------------------------------
         */
        public static function install_GA4(){

        	/**
        	 *  Make sure it's Dev Mode
        	 *  -----------------------
        	 */
        	if( SDWEDDINGDIRECTORY_DEV ){

	        	?>
				<!-- Google tag (gtag.js) -->
				<script async src="https://www.googletagmanager.com/gtag/js?id=G-JJLPBM4M57"></script>
				<script>

				  window.dataLayer = window.dataLayer || [];

				  function gtag(){  dataLayer.push(arguments);  }

				  gtag( 'js', new Date() );

				  gtag( 'config', 'G-JJLPBM4M57' );

				</script>
	        	<?php
        	}
        }
    }

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_GA4_Analitics:: get_instance();
}