<?php
/**
 *  SDWeddingDirectory - Publish Venue
 *  ----------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Publish_Venue_AJAX' ) && class_exists( 'SDWeddingDirectory_Vendor_Venue_AJAX' ) ){

    /**
     *  SDWeddingDirectory - Publish Venue
     *  ----------------------------
     */
    class SDWeddingDirectory_Vendor_Publish_Venue_AJAX extends SDWeddingDirectory_Vendor_Venue_AJAX{

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
                         *  Post Publish
                         *  ------------
                         */
                        esc_attr( 'sdweddingdirectory_publish_venue_action' ),
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
         *  1. Post Publish
         *  ---------------
         */
        public static function sdweddingdirectory_publish_venue_action(){

            global $current_user, $post, $wp_query, $wpdb;

            /**
             *  Found Post
             *  ----------
             */
            $found_total_post   =   apply_filters( 'sdweddingdirectory/vendor/venue/found', [

                                        'post_status'       =>      [ 'publish' ],

                                    ] );

            $vendor_plan_id     =   get_post_meta( parent:: post_id(), sanitize_key( 'pricing_plan_id' ), true );

            $plan_capacity      =   get_post_meta( $vendor_plan_id, sanitize_key( 'create_venue_capacity' ), true );

            /**
             *  Have Capacity ?
             *  ---------------
             */
            $enforce_capacity = apply_filters( 'sdsdweddingdirectoryectory/enable_venue_capacity_check', false );

            if( $enforce_capacity && $found_total_post >= $plan_capacity ){

                /**
                 *  Alert with Stop
                 *  ---------------
                 */
                die( json_encode( array(

                    'notice'    =>  absint( '0' ),

                    'message'   =>  esc_attr__( 'You have reached the maximum number of venues allowed. To add more venues, please consider upgrading your plan.', 'sdweddingdirectory-venue' )

                ) ) );
            }

            /**
             *  Check Security
             *  --------------
             */
            $_condition_1   = isset( $_POST['venue_id'] ) && $_POST['venue_id'] !== '';

            $_condition_2   = $_condition_1 ? self:: is_venue_owner( $_POST['venue_id'] ) : false;

            $_condition_3   = isset( $_POST[ 'security' ] ) && $_POST[ 'security' ] !== '';

            $_condition_4   = wp_verify_nonce( $_POST[ 'security' ], esc_attr( "publish_post-{$_REQUEST['venue_id']}" ) );

            /**
             *  Is Owner of Venue ?
             *  ---------------------
             */
            if( $_condition_1 && $_condition_2 && $_condition_3 && $_condition_4 ){

                $_venue_post_id = absint( $_POST['venue_id'] );

                if( get_post_status( $_venue_post_id ) == esc_attr( 'publish' ) ){

                    die( json_encode( array(

                        'notice'    =>  absint( '2' ),

                        'message'   =>  esc_attr__('Your Venue is Already Publish!','sdweddingdirectory-venue')

                    ) ) );

                }elseif( esc_attr( parent:: sdweddingdirectory_lisitng_post_status() ) == esc_attr( 'pending' )  ){

                    wp_update_post( array(

                        'ID'            =>  absint( $_venue_post_id ),

                        'post_status'   =>  esc_attr( parent:: sdweddingdirectory_lisitng_post_status() )
                    ));

                    die( json_encode( array(

                        'notice'    =>  absint( '1' ),

                        'message'   =>  esc_attr__('Awaiting for Admin Approved!','sdweddingdirectory-venue')

                    ) ) );

                }elseif( esc_attr( parent:: sdweddingdirectory_lisitng_post_status() ) == esc_attr( 'publish' ) ){

                    wp_update_post( array(

                        'ID'            =>  absint( $_venue_post_id ),

                        'post_status'   =>  esc_attr( parent:: sdweddingdirectory_lisitng_post_status() )
                    ));

                    die( json_encode( array(

                        'notice'    =>  absint( '1' ),

                        'message'   =>  esc_attr__('Your Venue is Publish!','sdweddingdirectory-venue' )

                    ) ) );
                }

            }else{

                parent:: security_issue_found();
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Vendor_Publish_Venue_AJAX:: get_instance();
}
