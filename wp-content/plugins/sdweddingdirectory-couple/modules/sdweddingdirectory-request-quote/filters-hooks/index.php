<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_Request_Quote_Filters' ) && class_exists( 'SDWeddingDirectory_Request_Quote_Database' ) ){

    /**
     *  Filter & Hooks
     *  --------------
     */
    class SDWeddingDirectory_Request_Quote_Filters extends SDWeddingDirectory_Request_Quote_Database{

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

            /**
             *  3. Register Popup ID
             *  --------------------
             */
            add_filter( 'sdweddingdirectory/model-popup', function( $args = [] ){

                /**
                 *  Merge New Modal
                 *  ---------------
                 */
                return      array_merge( $args, array(

                                [
                                    'slug'              =>      esc_attr( 'request_quote' ),

                                    'modal_id'          =>      esc_attr( 'sdweddingdirectory_request_quote' ),

                                    'name'              =>      esc_attr__( 'Request Quote Modal Popup', 'sdweddingdirectory-request-quote' ),

                                    'redirect_link'     =>      '',
                                ]

                            ) );

            }, absint( '40' ), absint( '1' ) );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Request_Quote_Filters:: get_instance();
}