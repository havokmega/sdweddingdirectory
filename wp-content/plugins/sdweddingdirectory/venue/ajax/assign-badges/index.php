<?php
/**
 *  SDWeddingDirectory - Assign Badge
 *  -------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Venue_Assign_Badge_AJAX' ) && class_exists( 'SDWeddingDirectory_Vendor_Venue_AJAX' ) ){

    /**
     *  SDWeddingDirectory - Assign Badge
     *  -------------------------
     */
    class SDWeddingDirectory_Vendor_Venue_Assign_Badge_AJAX extends SDWeddingDirectory_Vendor_Venue_AJAX{

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
                         *  Spotlight - Badge
                         *  -----------------
                         */
                        esc_attr( 'sdweddingdirectory_spotlight_badge_venues' ),

                        /**
                         *  Featured - Badge
                         *  -----------------
                         */
                        esc_attr( 'sdweddingdirectory_featured_badge_venues' ),

                        /**
                         *  Pro - Badge
                         *  -----------
                         */
                        esc_attr( 'sdweddingdirectory_professional_badge_venues' ),
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
         *  Spotlight - Badge
         *  -----------------
         */
        public static function sdweddingdirectory_spotlight_badge_venues(){

            /**
             *  Couple Registration through AJAX action With Test Security
             *  ----------------------------------------------------------
             */
            if( wp_verify_nonce( $_POST[ 'security' ], 'spotlight_badge_security' )  ){

                /**
                 *  Update Badge
                 *  ------------
                 */
                self:: sdweddingdirectory_badge_venues_update( [

                    'venue_ids'           =>      isset( $_POST[ 'venue_ids' ] )    ?   $_POST[ 'venue_ids' ]     :   [],

                    'name'                  =>      esc_attr( 'Spotlight' ),

                    'key'                   =>      esc_attr( 'spotlight' )

                ] );
            }

            /**
             *  Error Found
             *  -----------
             */
            else{

                die( json_encode( [

                    'notice'            =>      absint( '0' ),

                    'message'           =>      esc_attr__( 'Security Error Found !', 'sdweddingdirectory-venue' ),

                ] ) );
            }
        }

        /**
         *  Featured - Badge
         *  -----------------
         */
        public static function sdweddingdirectory_featured_badge_venues(){

            /**
             *  Couple Registration through AJAX action With Test Security
             *  ----------------------------------------------------------
             */
            if( wp_verify_nonce( $_POST[ 'security' ], 'featured_badge_security' )  ){

                /**
                 *  Update Badge
                 *  ------------
                 */
                self:: sdweddingdirectory_badge_venues_update( [

                    'venue_ids'           =>      isset( $_POST[ 'venue_ids' ] )    ?   $_POST[ 'venue_ids' ]     :   [],

                    'name'                  =>      esc_attr( 'Featured' ),

                    'key'                   =>      esc_attr( 'featured' )

                ] );
            }

            /**
             *  Error Found
             *  -----------
             */
            else{

                die( json_encode( [

                    'notice'            =>      absint( '0' ),

                    'message'           =>      esc_attr__( 'Security Error Found !', 'sdweddingdirectory-venue' ),

                ] ) );
            }
        }

        /**
         *  Professional - Badge
         *  -----------------
         */
        public static function sdweddingdirectory_professional_badge_venues(){

            /**
             *  Couple Registration through AJAX action With Test Security
             *  ----------------------------------------------------------
             */
            if( wp_verify_nonce( $_POST[ 'security' ], 'professional_badge_security' )  ){

                /**
                 *  Update Badge
                 *  ------------
                 */
                self:: sdweddingdirectory_badge_venues_update( [

                    'venue_ids'           =>      isset( $_POST[ 'venue_ids' ] )    ?   $_POST[ 'venue_ids' ]     :   [],

                    'name'                  =>      esc_attr( 'Professional' ),

                    'key'                   =>      esc_attr( 'professional' )

                ] );
            }

            /**
             *  Error Found
             *  -----------
             */
            else{

                die( json_encode( [

                    'notice'            =>      absint( '0' ),

                    'message'           =>      esc_attr__( 'Security Error Found !', 'sdweddingdirectory-venue' ),

                ] ) );
            }
        }


        /**
         *  Professional - Badge
         *  -----------------
         */
        public static function sdweddingdirectory_badge_venues_update( $args = [] ){

            /**
             *  Couple Registration through AJAX action With Test Security
             *  ----------------------------------------------------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'venue_ids'       =>      [],

                    'key'               =>      '',

                    'name'              =>      '',

                    'plan_id'           =>      parent:: get_data( 'pricing_plan_id' )

                ] ) );

                /**
                 *  Badge Venues
                 *  --------------
                 */
                $find_badge_venues      =     apply_filters( 'sdweddingdirectory/post/data', [

                                                    'post_type'     =>      esc_attr( 'venue' ),

                                                    'author_id'     =>      parent:: author_id(),

                                                    'meta_query'    =>      array(

                                                        'key'           =>      esc_attr( 'venue_badge' ),

                                                        'compare'       =>      esc_attr( '=' ),

                                                        'value'         =>      esc_attr( $key )
                                                    )

                                                ] );

                /**
                 *  Update no badge all professional badge find_badge_venues
                 *  --------------------------------------------
                 */
                if( parent:: _is_array( $find_badge_venues ) ){

                    /**
                     *  Update Professional Badge
                     *  ----------------------
                     */
                    foreach ( $find_badge_venues as $post_id => $post_name ) {
                     
                        /**
                         *  Update Post Badge
                         *  -----------------
                         */
                        update_post_meta( absint( $post_id ), sanitize_key( 'venue_badge' ), '' );
                    }
                }

                /**
                 *  Make sure venue post id exists
                 *  --------------------------------
                 */
                if( parent:: _is_array( $venue_ids )  ){

                    /**
                     *  Get Badge Limit
                     *  ---------------
                     */
                    $badge_limit        =       get_post_meta( $plan_id, sanitize_key( $key . '_venue_capacity' ), true  );

                    /**
                     *  Make sure professional badge capacity wise added venues
                     *  ------------------------------------------------------
                     */
                    if(  count( $venue_ids )  >  $badge_limit  ){

                        /**
                         *  Return Data
                         *  -----------
                         */
                        die( json_encode( [

                            'message'       =>      sprintf(  esc_attr__( 'Your Plan have [ %1$s ] %2$s Badge Capacity !', 'sdweddingdirectory-venue' ),

                                                        /**
                                                         *  1. Capacity
                                                         *  -----------
                                                         */
                                                        absint( $badge_limit ),

                                                        /**
                                                         *  2. Plan Name
                                                         *  ------------
                                                         */
                                                        esc_attr( $name )
                                                    ),

                            'notice'        =>      absint( '2' )

                        ] ) );
                    }

                    /**
                     *  Update Professional Badge
                     *  ----------------------
                     */
                    foreach ( $venue_ids as $index => $post_id ) {
                     
                        /**
                         *  Update Post Badge
                         *  -----------------
                         */
                        update_post_meta( absint( $post_id ), sanitize_key( 'venue_badge' ), esc_attr( $key ) );
                    }
                }

                /**
                 *  Return Data
                 *  -----------
                 */
                die( json_encode( [

                    'message'       =>      sprintf( esc_attr__( '%1$s Badge Updated !', 'sdweddingdirectory-venue' ), $name ),

                    'notice'        =>      absint( '1' )

                ] ) );
            }

            /**
             *  Error Found
             *  -----------
             */
            else{

                die( json_encode( [

                    'notice'            =>      absint( '0' ),

                    'message'           =>      esc_attr__( 'Security Error Found !', 'sdweddingdirectory-venue' ),

                ] ) );
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Vendor_Venue_Assign_Badge_AJAX:: get_instance();
}