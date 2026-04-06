<?php 
/**
 *  SDWeddingDirectory - Wishlist AJAX File
 *  -------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_WishList_AJAX' ) && class_exists( 'SDWeddingDirectory_WishList_Database' ) ){

    /**
     *  SDWeddingDirectory - Wishlist AJAX File
     *  -------------------------------
     */
    class SDWeddingDirectory_WishList_AJAX extends SDWeddingDirectory_WishList_Database{

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
                         *  1. WishList AJAX : Update Notes for WishList
                         *  --------------------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_update_notes' ),

                        /**
                         *  2. WishList AJAX : Removed Wishlist AJAX Process
                         *  ------------------------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_remove_wishlist' ),

                        /**
                         *  3. WishList AJAX : Add Wishlist AJAX Process
                         *  --------------------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_add_wishlist' ),

                        /**
                         *  4. Couple Hire Vendor Estimate Price
                         *  ------------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_update_estimate_price' ),

                        /**
                         *  5. Couple Rating for Wishlist Vendor
                         *  ------------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_update_rating' ),

                        /**
                         *  6. Couple Hire Vendor
                         *  ---------------------
                         */
                        esc_attr( 'sdweddingdirectory_add_hired' ),

                        /**
                         *  7. Couple Removed Hire Vendor
                         *  -----------------------------
                         */
                        esc_attr( 'sdweddingdirectory_remove_hired' ),

                        /**
                         *  8. Couple Dashboard > Wishlist > Hire Vendor Options
                         *  ----------------------------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_wishlist_hire_vendor' )
                    );

                    /**
                     *  If have action then load this object members
                     *  --------------------------------------------
                     */
                    if( in_array( $action, $allowed_actions, true ) ) {

                        /**
                         *  Wishlist endpoints are authenticated-only
                         *  ----------------------------------------
                         */
                        add_action( 'wp_ajax_' . $action, function() use ( $action ) {

                            self:: authorize_wishlist_request();

                            call_user_func( [ __CLASS__, $action ] );
                        } );
                    }
                }
            }
        }

        /**
         *  Authorization gate for all wishlist AJAX handlers
         *  -------------------------------------------------
         */
        private static function authorize_wishlist_request(){

            if( ! is_user_logged_in() || ! parent:: is_couple() ){

                wp_send_json_error( [
                    'message'   =>  esc_attr__( 'Unauthorized.', 'sdweddingdirectory-wishlist' ),
                    'notice'    =>  absint( '0' ),
                ] );
            }

            $_security = isset( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';

            if( empty( $_security ) || ! wp_verify_nonce( $_security, 'sdweddingdirectory_wishlist_security' ) ){

                wp_send_json_error( [
                    'message'   =>  esc_attr__( 'Invalid security token.', 'sdweddingdirectory-wishlist' ),
                    'notice'    =>  absint( '0' ),
                ] );
            }
        }

        /**
         *  1. Wishlist AJAX : Update Notes
         *  -------------------------------
         */
        public static function sdweddingdirectory_update_rating(){

            $_condition_1   =   isset( $_POST[ 'unique_id' ] ) && $_POST[ 'unique_id' ] !== '';

            $_condition_2   =   isset( $_POST[ 'wishlist_rating' ] ) && $_POST[ 'wishlist_rating' ] !== '';

            if( $_condition_1 && $_condition_2 ){                

                $get_data   =   parent:: get_wishlist();

                if(  SDWeddingDirectory_Loader:: _is_array( $get_data )  ){

                    foreach ( $get_data as $key => $value) {

                        if( $value[ 'wishlist_unique_id' ] ==  absint( $_POST[ 'unique_id' ] ) ){

                            $get_data[ $key ]                 =   array(

                                'wishlist_venue_id'         =>  absint( $value[ 'wishlist_venue_id' ] ),

                                'wishlist_venue_category'   =>  absint( $value[ 'wishlist_venue_category' ] ),

                                'wishlist_vendor_id'          =>  absint( $value[ 'wishlist_vendor_id' ] ),

                                'wishlist_timestrap'          =>  absint( $value[ 'wishlist_timestrap' ] ),

                                'wishlist_note'               =>  esc_textarea( $value[ 'wishlist_note' ] ),

                                'wishlist_unique_id'          =>  absint( $value[ 'wishlist_unique_id' ] ),

                                'wishlist_hire_vendor'        =>  esc_attr( $value[ 'wishlist_hire_vendor' ] ),

                                'wishlist_estimate_price'     =>  absint( $value[ 'wishlist_estimate_price' ] ),

                                'wishlist_rating'             =>  absint( $_POST[ 'wishlist_rating' ] )
                            );
                        }
                    }

                    /**
                     *  Update Wishlsit Data
                     *  --------------------
                     */
                    update_post_meta( 

                        /**
                         *  Couple Post ID
                         *  --------------
                         */
                        absint( parent:: post_id() ), 

                        /**
                         *  Meta Key
                         *  --------
                         */
                        sanitize_key( 'sdweddingdirectory_wishlist' ),

                        /**
                         *  Update Wishlist Data
                         *  --------------------
                         */
                        $get_data 
                    );

                    /**
                     *  Success
                     *  -------
                     */
                    die( json_encode( array(

                        'notice'  => absint( '1' ),

                        'message' => esc_attr__( 'Update Successfully! ', 'sdweddingdirectory-wishlist' )

                    ) ) );
                }
            }
        }

        /**
         *  1. Wishlist AJAX : Update Notes
         *  -------------------------------
         */
        public static function sdweddingdirectory_update_notes(){

            $_condition_1   =   isset( $_POST[ 'unique_id' ] ) && $_POST[ 'unique_id' ] !== '';

            $_condition_2   =   isset( $_POST[ 'wishlist_note' ] ) && $_POST[ 'wishlist_note' ] !== '';

            if( $_condition_1 && $_condition_2 ){                

                $get_data   =   parent:: get_wishlist();

                if(  SDWeddingDirectory_Loader:: _is_array( $get_data )  ){

                    foreach ( $get_data as $key => $value) {

                        if( $value[ 'wishlist_unique_id' ] ==  absint( $_POST[ 'unique_id' ] ) ){

                            $get_data[ $key ]                 =   array(

                                'wishlist_venue_id'         =>  absint( $value[ 'wishlist_venue_id' ] ),

                                'wishlist_venue_category'   =>  absint( $value[ 'wishlist_venue_category' ] ),

                                'wishlist_vendor_id'          =>  absint( $value[ 'wishlist_vendor_id' ] ),

                                'wishlist_timestrap'          =>  absint( $value[ 'wishlist_timestrap' ] ),

                                'wishlist_note'               =>  esc_textarea( $_POST[ 'wishlist_note' ] ),

                                'wishlist_unique_id'          =>  absint( $value[ 'wishlist_unique_id' ] ),

                                'wishlist_hire_vendor'        => esc_attr( $value[ 'wishlist_hire_vendor' ] ),

                                'wishlist_estimate_price'     =>  absint( $value[ 'wishlist_estimate_price' ] ),

                                'wishlist_rating'             =>  absint( $value[ 'wishlist_rating' ] )
                            );
                        }
                    }

                    /**
                     *  Update Wishlsit Data
                     *  --------------------
                     */
                    update_post_meta( 

                        /**
                         *  Couple Post ID
                         *  --------------
                         */
                        absint( parent:: post_id() ), 

                        /**
                         *  Meta Key
                         *  --------
                         */
                        sanitize_key( 'sdweddingdirectory_wishlist' ),

                        /**
                         *  Update Wishlist Data
                         *  --------------------
                         */
                        $get_data 
                    );

                    /**
                     *  Success
                     *  -------
                     */
                    die( json_encode( array(

                        'notice'  => absint( '1' ),

                        'message' => esc_attr__( 'Update Successfully! ', 'sdweddingdirectory-wishlist' )

                    ) ) );
                }
            }
        }

        /**
         *  1. Wishlist AJAX : Update Estimate Price
         *  ----------------------------------------
         */
        public static function sdweddingdirectory_update_estimate_price(){

            $_condition_1   =   isset( $_POST[ 'unique_id' ] ) && $_POST[ 'unique_id' ] !== '';

            $_condition_2   =   isset( $_POST[ 'wishlist_estimate_price' ] ) && $_POST[ 'wishlist_estimate_price' ] !== '';

            if( $_condition_1 && $_condition_2 ){                

                $get_data   =   parent:: get_wishlist();

                if(  SDWeddingDirectory_Loader:: _is_array( $get_data )  ){

                    foreach ( $get_data as $key => $value) {

                        if( $value[ 'wishlist_unique_id' ] ==  absint( $_POST[ 'unique_id' ] ) ){

                            $get_data[ $key ]                 =   array(

                                'wishlist_venue_id'         =>  absint( $value[ 'wishlist_venue_id' ] ),

                                'wishlist_venue_category'   =>  absint( $value[ 'wishlist_venue_category' ] ),

                                'wishlist_vendor_id'          =>  absint( $value[ 'wishlist_vendor_id' ] ),

                                'wishlist_timestrap'          =>  absint( $value[ 'wishlist_timestrap' ] ),

                                'wishlist_note'               =>  esc_textarea( $value[ 'wishlist_note' ] ),

                                'wishlist_unique_id'          =>  absint( $value[ 'wishlist_unique_id' ] ),

                                'wishlist_hire_vendor'        => esc_attr( $value[ 'wishlist_hire_vendor' ] ),

                                'wishlist_estimate_price'     =>   absint( $_POST['wishlist_estimate_price'] ),

                                'wishlist_rating'             =>  absint( $value[ 'wishlist_rating' ] )
                            );
                        }
                    }

                    /**
                     *  Update Wishlsit Data
                     *  --------------------
                     */
                    update_post_meta( 

                        /**
                         *  Couple Post ID
                         *  --------------
                         */
                        absint( parent:: post_id() ), 

                        /**
                         *  Meta Key
                         *  --------
                         */
                        sanitize_key( 'sdweddingdirectory_wishlist' ),

                        /**
                         *  Update Wishlist Data
                         *  --------------------
                         */
                        $get_data 
                    );

                    /**
                     *  Success
                     *  -------
                     */
                    die( json_encode( array(

                        'notice'  => absint( '1' ),

                        'message' => esc_attr__( 'Update Successfully! ', 'sdweddingdirectory-wishlist' )

                    ) ) );
                }
            }
        }

        /**
         *  2. WishList AJAX : Removed Wishlist AJAX Process
         *  ------------------------------------------------
         */
        public static function sdweddingdirectory_remove_wishlist(){

            if( ! is_user_logged_in() ){

                die( json_encode( array(

                    'notice'    =>  absint('2'),

                    'message'   =>  esc_attr__( 'You must be logged in.', 'sdweddingdirectory-wishlist' )

                ) ) );
            }

            $_condition_1   =   isset( $_POST[ 'venue_id' ] ) && $_POST[ 'venue_id' ] !== '' && $_POST[ 'venue_id' ] !== absint( '0' );

            $_condition_2   =   parent::is_couple();

            if( $_condition_1 && $_condition_2 ){

                $_venue_post_id   =   absint( $_POST[ 'venue_id' ] );

                /**
                 *  Remove from user meta saved profiles
                 *  ------------------------------------
                 */
                $user_id   = get_current_user_id();
                $saved     = get_user_meta( $user_id, 'sdwd_saved_profiles', true );

                if( is_array( $saved ) ){

                    $saved = array_values( array_diff( array_map( 'absint', $saved ), [ $_venue_post_id ] ) );
                    update_user_meta( $user_id, 'sdwd_saved_profiles', $saved );
                }

                die( json_encode( array(

                    'notice'    =>  absint('2'),

                    'message'   =>  esc_attr__( 'Removed from Saved!', 'sdweddingdirectory-wishlist' )

                ) ) );
            }
        }

        /**
         *  3. WishList AJAX : Add Wishlist AJAX Process
         *  --------------------------------------------
         */
        public static function sdweddingdirectory_add_wishlist(){

            global $current_user, $post, $wp_query;

            /**
             *  If user not login
             *  -----------------
             */
            if( ! is_user_logged_in() ){

                die( json_encode( array(

                    'notice'    =>  absint('2'),

                    'message'   =>  esc_attr__( 'You must be logged in to save this vendor.', 'sdweddingdirectory-wishlist' )

                ) ) );

            }else{

                $_condition_1   =   isset( $_POST[ 'venue_id' ] ) && $_POST[ 'venue_id' ] !== '' && $_POST[ 'venue_id' ] !== absint( '0' );

                $_condition_2   =   parent:: is_couple();

                if( $_condition_1 && $_condition_2 ){

                    $_venue_post_id   = absint( $_POST[ 'venue_id' ] );

                    /**
                     *  Add to user meta saved profiles
                     *  -------------------------------
                     */
                    $user_id   = get_current_user_id();
                    $saved     = get_user_meta( $user_id, 'sdwd_saved_profiles', true );

                    if( ! is_array( $saved ) ){
                        $saved = [];
                    }

                    if( in_array( $_venue_post_id, array_map( 'absint', $saved ) ) ){

                        die( json_encode( array(

                            'notice'    =>  absint('2'),

                            'message'   =>  esc_attr__( 'Already Saved!', 'sdweddingdirectory-wishlist' )

                        ) ) );
                    }

                    $saved[] = $_venue_post_id;
                    update_user_meta( $user_id, 'sdwd_saved_profiles', array_values( array_unique( array_map( 'absint', $saved ) ) ) );

                    die( json_encode( array(

                        'redirect'  =>  false,

                        'notice'    =>  absint('1'),

                        'message'   =>  esc_attr__( 'Saved!', 'sdweddingdirectory-wishlist' )

                    ) ) );
                }
            }
        }

        /**
         *  5. Couple Hire Vendor AJAX Process
         *  ----------------------------------
         */
        public static function sdweddingdirectory_add_hired(){

            $_condition_1   =   isset( $_POST[ 'venue_id' ] ) && $_POST[ 'venue_id' ] !== '';

            if( $_condition_1 ){

                $_post_id  = absint( $_POST[ 'venue_id' ] );
                $user_id   = get_current_user_id();
                $hired     = get_user_meta( $user_id, 'sdwd_hired_profiles', true );

                if( ! is_array( $hired ) ){
                    $hired = [];
                }

                if( ! in_array( $_post_id, array_map( 'absint', $hired ) ) ){

                    $hired[] = $_post_id;
                    update_user_meta( $user_id, 'sdwd_hired_profiles', array_values( array_unique( array_map( 'absint', $hired ) ) ) );
                }

                die( json_encode( array(

                    'notice'    =>  absint( '1' ),

                    'message'   =>  esc_attr__( 'Marked as Hired!', 'sdweddingdirectory-wishlist' ),

                    'string'    =>  sprintf( '<i class="fa fa-check-circle"></i> %1$s',

                                        esc_attr__( 'Hired', 'sdweddingdirectory-wishlist' )
                                    )

                ) ) );
            }
        }

        /**
         *  7. Couple Selection for Venue Hired / etc..
         *  ---------------------------------------------
         */
        public static function sdweddingdirectory_wishlist_hire_vendor(){

            $_condition_1   =   isset( $_POST[ 'unique_id' ] ) && $_POST[ 'unique_id' ] !== '';

            if( $_condition_1 ){

                $get_data   =   parent:: get_wishlist();

                if(  SDWeddingDirectory_Loader:: _is_array( $get_data )  ){

                    foreach ( $get_data as $key => $value ) {

                        extract( $value );

                        if( $value[ 'wishlist_unique_id' ] ==  absint( $_POST[ 'unique_id' ] ) ){

                            $get_data[ $key ]                 =   array(

                                'wishlist_venue_id'         =>  absint( $value[ 'wishlist_venue_id' ] ),

                                'wishlist_venue_category'   =>  absint( $value[ 'wishlist_venue_category' ] ),

                                'wishlist_vendor_id'          =>  absint( $value[ 'wishlist_vendor_id' ] ),

                                'wishlist_timestrap'          =>  absint( $value[ 'wishlist_timestrap' ] ),

                                'wishlist_note'               =>  esc_textarea( $value[ 'wishlist_note' ] ),

                                'wishlist_unique_id'          =>  absint( $value[ 'wishlist_unique_id' ] ),

                                'wishlist_hire_vendor'        =>  esc_attr( $_POST['wishlist_hire_vendor'] ),

                                'wishlist_estimate_price'     =>  absint( $value[ 'wishlist_estimate_price' ] ),

                                'wishlist_rating'             =>  absint( $value[ 'wishlist_rating' ] )
                            );
                        }
                    }

                    /**
                     *  Update Wishlsit Data
                     *  --------------------
                     */
                    update_post_meta( 

                        /**
                         *  Couple Post ID
                         *  --------------
                         */
                        absint( parent:: post_id() ), 

                        /**
                         *  Meta Key
                         *  --------
                         */
                        sanitize_key( 'sdweddingdirectory_wishlist' ),

                        /**
                         *  Update Wishlist Data
                         *  --------------------
                         */
                        $get_data 
                    );

                    /**
                     *  Success
                     *  -------
                     */
                    die( json_encode( array(

                        'notice'    =>  absint( '1' ),

                        'message'   =>  esc_attr__( 'Update Successfully! ', 'sdweddingdirectory-wishlist' ),

                    ) ) );
                }
            }
        }

        /**
         *  6. Couple Remove Hired AJAX Process
         *  -----------------------------------
         */
        public static function sdweddingdirectory_remove_hired(){

            $_condition_1   =   isset( $_POST[ 'venue_id' ] ) && $_POST[ 'venue_id' ] !== '';

            if( $_condition_1 ){

                $_post_id  = absint( $_POST[ 'venue_id' ] );
                $user_id   = get_current_user_id();
                $hired     = get_user_meta( $user_id, 'sdwd_hired_profiles', true );

                if( is_array( $hired ) ){

                    $hired = array_values( array_diff( array_map( 'absint', $hired ), [ $_post_id ] ) );
                    update_user_meta( $user_id, 'sdwd_hired_profiles', $hired );
                }

                die( json_encode( array(

                    'notice'    =>    absint( '2' ),

                    'message'   =>    esc_attr__( 'Removed from Hired!', 'sdweddingdirectory-wishlist' ),

                    'string'    =>  sprintf( '<i class="fa fa-handshake-o"></i> %1$s',

                                        esc_attr__( 'Hired ?', 'sdweddingdirectory-wishlist' )
                                    )

                ) ) );
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_WishList_AJAX:: get_instance();
}
