<?php
/**
 *  SDWeddingDirectory - Deactivate Venue
 *  -------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Deactivate_Venue_AJAX' ) && class_exists( 'SDWeddingDirectory_Vendor_Venue_AJAX' ) ){

    /**
     *  SDWeddingDirectory - Deactivate Venue
     *  -------------------------------
     */
    class SDWeddingDirectory_Vendor_Deactivate_Venue_AJAX extends SDWeddingDirectory_Vendor_Venue_AJAX{

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
             *  1. Have AJAX action ?
             *  ---------------------
             */
            if(  wp_doing_ajax()  ){

                /**
                 *  Have AJAX action ?
                 *  ------------------
                 */
                if( isset( $_POST['action'] ) && $_POST['action'] !== '' ){

                    /**
                     *  Have AJAX action ?
                     *  ------------------
                     */
                    $action     =   esc_attr( trim( $_POST['action'] ) );

                    /**
                     *  1.  Bit of security
                     *  -------------------
                     */
                    $allowed_actions = array(

                        /**
                         *  Deactive Publish
                         *  ----------------
                         */
                        esc_attr( 'sdweddingdirectory_deactivate_venue_action' ),
                    );

                    /**
                     *  If have action then load this object members
                     *  --------------------------------------------
                     */
                    if( in_array( $action, $allowed_actions) ) {

                        /**
                         *  Check the AJAX action with login user
                         *  -------------------------------------
                         */
                        if( is_user_logged_in() ){

                            /**
                             *  1. If user already login then AJAX action
                             *  -----------------------------------------
                             */
                            add_action( 'wp_ajax_' . $action, [ $this, $action ] );

                        }else{

                            /**
                             *  2. If user not login that mean is front end AJAX action
                             *  -------------------------------------------------------
                             */
                            add_action( 'wp_ajax_nopriv_' . $action, [ $this, $action ] );
                        }
                    }
                }
            }
        }

        /**
         *  3. Deactive Publish
         *  -------------------
         */
        public static function sdweddingdirectory_deactivate_venue_action(){

            global $current_user, $post, $wp_query, $wpdb;

            /**
             *  Check Security
             *  --------------
             */
            $_condition_1   = isset( $_POST['venue_id'] ) && $_POST['venue_id'] !== '';

            $_condition_2   = $_condition_1 ? self:: is_venue_owner( $_POST['venue_id'] ) : false;

            $_condition_3   = isset( $_POST[ 'security' ] ) && $_POST[ 'security' ] !== '';

            $_condition_4   = wp_verify_nonce( $_POST[ 'security' ], esc_attr( "deactivate_post-{$_REQUEST['venue_id']}" ) );

            /**
             *  Is Owner of Venue ?
             *  ---------------------
             */
            if( $_condition_1 && $_condition_2 && $_condition_3 && $_condition_4 ){

                $_venue_post_id = absint( $_POST['venue_id'] );

                /**
                 *  Post Move to Trash
                 *  ------------------
                 */
                wp_update_post( array(

                    'ID'            =>  absint( $_venue_post_id ),

                    'post_status'   =>  esc_attr( 'trash' )
                ));

                /**
                 *  Successfully Change Post Status
                 *  -------------------------------
                 */
                die( json_encode( array(

                    'notice'    =>  absint( '1' ),

                    'message'   =>  esc_attr__('Your Venue is Deactive Successfully!','sdweddingdirectory-venue')

                ) ) );

            }else{

                parent:: security_issue_found();
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Vendor_Deactivate_Venue_AJAX:: get_instance();
}
