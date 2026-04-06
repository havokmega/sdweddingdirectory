<?php
/**
 *  SDWeddingDirectory - Venue Module (Core Integrated)
 *  -----------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Module' ) && class_exists( 'SDWeddingDirectory_Loader' ) ){

    /**
     *  SDWeddingDirectory - Venue Module (Core Integrated)
     *  -----------------------------------------------------
     */
    class SDWeddingDirectory_Venue_Module extends SDWeddingDirectory_Loader{

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

        /**
         *  Module version
         *  --------------
         */
        const VERSION = '1.7.8';

        /**
         *  File collection
         *  ---------------
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
         *  Construct
         *  ---------
         */
        public function __construct() {

            $this->constant();
            $this->files();
        }

        /**
         *  Define venue constants
         *  ------------------------
         */
        private function constant(){

            if( ! defined( 'SDWEDDINGDIRECTORY_VENUE_URL' ) ){

                define( 'SDWEDDINGDIRECTORY_VENUE_URL', plugin_dir_url( __FILE__ ) );
            }

            if( ! defined( 'SDWEDDINGDIRECTORY_VENUE_DIR' ) ){

                define( 'SDWEDDINGDIRECTORY_VENUE_DIR', plugin_dir_path( __FILE__ ) );
            }

            if( ! defined( 'SDWEDDINGDIRECTORY_VENUE_VERSION' ) ){

                define( 'SDWEDDINGDIRECTORY_VENUE_VERSION', self:: VERSION );
            }
        }

        /**
         *  Load module files
         *  -----------------
         */
        private function files(){

            $this->files = [
                'database/index.php',
                'filters-hooks/index.php',
                'admin-file/index.php',
                'singular-venue/index.php',
                'venue-layout/index.php',
                'vendor-file/index.php',
                'ajax/index.php',
            ];

            foreach ( $this->files as $file ) {

                require_once plugin_dir_path( __FILE__ ) . $file;
            }
        }
    }

    /**
     *  SDWeddingDirectory - Venue Module (Core Integrated)
     *  -----------------------------------------------------
     */
    SDWeddingDirectory_Venue_Module::get_instance();
}
