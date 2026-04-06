<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Dashboard_Database' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  Filter & Hooks
     *  --------------
     */
    class SDWeddingDirectory_Couple_Dashboard_Database extends SDWeddingDirectory_Config{

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


        }
    }

   /**
    *  Couple Dashboard Filer Object Call
    *  ----------------------------------
    */
    SDWeddingDirectory_Couple_Dashboard_Database:: get_instance();
}