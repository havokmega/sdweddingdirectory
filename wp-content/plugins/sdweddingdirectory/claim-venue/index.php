<?php
/**
 *  SDWeddingDirectory - Claim Venue Module (Core Integrated)
 *  -----------------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Claim_Venue_Module' ) && class_exists( 'SDWeddingDirectory_Loader' ) ){

    /**
     *  SDWeddingDirectory - Claim Venue Module (Core Integrated)
     *  -----------------------------------------------------------
     */
    class SDWeddingDirectory_Claim_Venue_Module extends SDWeddingDirectory_Loader{

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

        /**
         *  Module version
         *  --------------
         */
        const VERSION = '1.2.3';

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
         *  Define claim constants
         *  ----------------------
         */
        private function constant(){

            if( ! defined( 'SDWEDDINGDIRECTORY_CLAIM_VENUE_PLUGIN' ) ){

                define( 'SDWEDDINGDIRECTORY_CLAIM_VENUE_PLUGIN', self:: VERSION );
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
                'ajax/index.php',
            ];

            foreach ( $this->files as $file ) {

                require_once plugin_dir_path( __FILE__ ) . $file;
            }
        }
    }

    /**
     *  SDWeddingDirectory - Claim Venue Module (Core Integrated)
     *  -----------------------------------------------------------
     */
    SDWeddingDirectory_Claim_Venue_Module::get_instance();
}
