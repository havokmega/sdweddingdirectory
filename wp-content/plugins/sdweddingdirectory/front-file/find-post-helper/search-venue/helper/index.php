<?php
/**
 *  SDWeddingDirectory Search Venue Helper
 *  --------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Search_Result_Helper' ) && class_exists( 'SDWeddingDirectory_Search_Result' ) ){

    /**
     *  SDWeddingDirectory Search Venue Helper
     *  --------------------------------
     */
    class SDWeddingDirectory_Search_Result_Helper extends SDWeddingDirectory_Search_Result{

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
             *  Load one by one shortcode file
             *  ------------------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once $file;
            }
        }

        /**
         *  View More
         *  ---------
         */
        public static function _filter_view_more(){

            /**
             *  View More
             *  ---------
             */
            return      sprintf(    '<div class="view-all border-top py-3">

                                        <a href="javascript:" class="fw-bold view-more-filter">%1$s</a>

                                    </div>',

                                    /**
                                     *  1. Translation Ready String
                                     *  ---------------------------
                                     */
                                    esc_attr__( '+ Show more', 'sdweddingdirectory' )
                        );
        }

        /**
         *  Get Lable of Range Term
         *  -----------------------
         */
        public static function _range_label( $list = [], $value = '' ){

            /**
             *  Create Array for min and max value
             *  ----------------------------------
             */
            $range      =   explode( '-', str_replace( [ '[', ']' ], '', $value ) );

            /**
             *  Max value search in checkbox
             *  ----------------------------
             */
            $index      =   array_search( $range[ '1' ], array_column( $list, 'max' ) );

            /**
             *  Label
             *  -----
             */
            return      esc_attr( $list[ $index ][ 'label' ] );
        }
    }

    /**
     *  Search Result Helper
     *  --------------------
     */
    SDWeddingDirectory_Search_Result_Helper::get_instance();
}