<?php
/**
 *  SDWeddingDirectory - Real Wedding Left Widget Filter
 *  --------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Real_Wedding_Right_Widget_Filter' ) && class_exists( 'SDWeddingDirectory_Real_Wedding_Filters' ) ){

    /**
     *  SDWeddingDirectory - Real Wedding Left Widget Filter
     *  --------------------------------------------
     */
    class SDWeddingDirectory_Real_Wedding_Right_Widget_Filter extends SDWeddingDirectory_Real_Wedding_Filters{

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

    } /* class end **/

    /**
     *  SDWeddingDirectory - Real Wedding Left Widget Filter
     *  --------------------------------------------
     */
    SDWeddingDirectory_Real_Wedding_Right_Widget_Filter:: get_instance();
}