<?php
/**
 *  SDWeddingDirectory - Date Format
 *  ------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Date_Format' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory - Date Format
     *  ------------------------
     */
    class SDWeddingDirectory_Date_Format extends SDWeddingDirectory_Config{

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
             *  Date Format
             *  -----------
             */
            add_filter( 'sdweddingdirectory/date-format', [ $this, 'date_format' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Return Date - Format
         *  --------------------
         */
        public static function date_format( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'format'        =>      get_option( 'date_format' ),

                    'date'          =>      ''

                ] ) );

                /**
                 *  Have Date ?
                 *  -----------
                 */
                if( empty( $date ) ){

                    return;
                }

                /**
                 *  Return : Date
                 *  -------------
                 */
                return          esc_attr( date( $format, strtotime( $date ) ) );
            }
        }
    }

    /**
     *  Taxonomy Object
     *  ---------------
     */
    SDWeddingDirectory_Date_Format:: get_instance();
}