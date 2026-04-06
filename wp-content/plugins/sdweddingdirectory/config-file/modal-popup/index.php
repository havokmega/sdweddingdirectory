<?php
/**
 *  SDWeddingDirectory - Modal Popups
 *  -------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Modal_Popups' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory - Modal Popups
     *  -------------------------
     */
    class SDWeddingDirectory_Modal_Popups extends SDWeddingDirectory_Config {

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
             *  Add Redirection Hidden Fields
             *  -----------------------------
             */
            add_action( 'sdweddingdirectory/modal-popup/redirection-field', [ $this, 'redirection_link' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Get Modals list
         *  ---------------
         */
        public static function get_modals(){

            return      apply_filters( 'sdweddingdirectory/model-popup', [] );
        }

        /**
         *  Modal Popup - Redirection Link
         *  ------------------------------
         */
        public static function redirection_link( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract 
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'modal_id'          =>      '',

                    'modals'            =>      array_column( self:: get_modals(), esc_attr( 'redirect_link' ), esc_attr( 'slug' ) ),

                    'redirect_link'     =>      ''

                ] ) );

                /**
                 *  Have Modal ID ?
                 *  ---------------
                 */
                if( empty( $modal_id ) ){

                    return;
                }

                /**
                 *  Have Any Redirection ?
                 *  ----------------------
                 */
                printf( '<input autocomplete="off" type="hidden" name="%1$s" id="%1$s" value="%2$s" />', 

                    /**
                     *  1. ID / Name
                     *  ------------
                     */
                    esc_attr( $modal_id . '_redirect_link' ),

                    /**
                     *  Have Redirection ?
                     *  ------------------
                     */
                    isset( $_GET[ $modal_id . '_redirect_link'] ) && ! empty( $_GET[ $modal_id . '_redirect_link'] )

                    ?   esc_url( $_GET[ $modal_id . '_redirect_link'] )

                    :   $modals[ $modal_id ]
                );
            }
        }
    }

    /**
     *  SDWeddingDirectory - Modal Popups
     *  -------------------------
     */
    SDWeddingDirectory_Modal_Popups::get_instance();
}