<?php
/**
 *  SDWeddingDirectory - Real Wedding Module (Core Integrated)
 *  ----------------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Real_Wedding_Module' ) && class_exists( 'SDWeddingDirectory_Loader' ) ){

    /**
     *  SDWeddingDirectory - Real Wedding Module (Core Integrated)
     *  ----------------------------------------------------------
     */
    class SDWeddingDirectory_Real_Wedding_Module extends SDWeddingDirectory_Loader{

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

        /**
         *  Module version
         *  --------------
         */
        const VERSION = '1.3.7';

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
         *  Define module constants
         *  -----------------------
         */
        private function constant(){

            if( ! defined( 'SDWEDDINGDIRECTORY_REALWEDDING_PLUGIN' ) ){

                define( 'SDWEDDINGDIRECTORY_REALWEDDING_PLUGIN', self:: VERSION );
            }

            if( ! defined( 'SDWEDDINGDIRECTORY_REALWEDDING_PLUGIN_URL' ) ){

                define( 'SDWEDDINGDIRECTORY_REALWEDDING_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
            }

            if( ! defined( 'SDWEDDINGDIRECTORY_REALWEDDING_PLUGIN_DIR' ) ){

                define( 'SDWEDDINGDIRECTORY_REALWEDDING_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
            }

            if( ! defined( 'SDWEDDINGDIRECTORY_REALWEDDING_PLUGIN_LIB' ) ){

                define( 'SDWEDDINGDIRECTORY_REALWEDDING_PLUGIN_LIB', SDWEDDINGDIRECTORY_REALWEDDING_PLUGIN_URL . '/assets/library/' );
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
                'couple-file/index.php',
                'singular-file/index.php',
                'realwedding-layout/index.php',
            ];

            foreach ( $this->files as $file ) {

                require_once plugin_dir_path( __FILE__ ) . $file;
            }
        }
    }

    /**
     *  SDWeddingDirectory - Real Wedding Module (Core Integrated)
     *  ----------------------------------------------------------
     */
    SDWeddingDirectory_Real_Wedding_Module::get_instance();
}
