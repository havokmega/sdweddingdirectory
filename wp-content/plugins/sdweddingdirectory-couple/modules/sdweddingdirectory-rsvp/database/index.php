<?php
/**
 *  SDWeddingDirectory - Couple RSVP
 *  ------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_RSVP_Database' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory - Couple RSVP
     *  ------------------------
     */
    class SDWeddingDirectory_Couple_RSVP_Database extends SDWeddingDirectory_Config{

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
     *  SDWeddingDirectory - Couple RSVP
     *  ------------------------
     */
    SDWeddingDirectory_Couple_RSVP_Database:: get_instance();
}