<?php
/**
 *  SDWeddingDirectory Vendor My Profile AJAX Script Action HERE
 *  ----------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Profile_AJAX' ) && class_exists( 'SDWeddingDirectory_Vendor_Profile_Database' ) ){

    /**
     *  SDWeddingDirectory Vendor My Profile AJAX Script Action HERE
     *  ----------------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Profile_AJAX extends SDWeddingDirectory_Vendor_Profile_Database{

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
                    $allowed_actions    =   array(

                        /**
                         *  1. Vendor Profile Update
                         *  ------------------------
                         */
                        esc_attr( 'sdweddingdirectory_vendor_profile_action' ),

                        /**
                         *  2. Password Update Action
                         *  -------------------------
                         */
                        esc_attr( 'sdweddingdirectory_vendor_password_action' ),

                        /**
                         *  3. Social Media Updat Action
                         *  ----------------------------
                         */
                        esc_attr( 'sdweddingdirectory_vendor_social_action' ),

                        /**
                         *  4. Business Pofile Updat Action
                         *  -------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_vendor_business_profile_action' ),

                        /**
                         *  5. Vendor Filter Update Action
                         *  ------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_vendor_filter_profile_action' ),

                        /**
                         *  6. Vendor Onboarding Helper Save
                         *  --------------------------------
                         */
                        esc_attr( 'sdwd_vendor_onboarding_save_action' ),

                        /**
                         *  7. Vendor Gallery Upload Save
                         *  -----------------------------
                         */
                        esc_attr( 'sdweddingdirectory_vendor_upload_photos_action' ),
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
         *  If Found security issue
         *  -----------------------
         */
        public static function security_issue_found(){

            die( json_encode( array(

                'message'   => esc_attr__( 'Security issue!', 'sdweddingdirectory' ),

                'notice'    => absint( '2' )

            ) ) );
        }

        /**
         *  Resolve Current Vendor Post ID
         *  ------------------------------
         */
        public static function current_vendor_post_id(){

            $post_id = absint( parent:: post_id() );

            if( $post_id > 0 ){
                return $post_id;
            }

            $current_user = wp_get_current_user();

            if( empty( $current_user ) || empty( $current_user->user_email ) ){
                return absint( '0' );
            }

            $post_id = absint( apply_filters( 'sdweddingdirectory/vendor/post-id', sanitize_email( $current_user->user_email ) ) );

            if( $post_id > 0 ){
                return $post_id;
            }

            /**
             *  Recovery: create vendor profile post from stored registration data.
             */
            $user_id = absint( $current_user->ID );

            $company_name = sanitize_text_field( get_user_meta( $user_id, sanitize_key( 'sdwd_vendor_company_name' ), true ) );

            if( $company_name === '' ){
                return absint( '0' );
            }

            do_action( 'sdweddingdirectory/register/vendor/configuration', [

                'post_type'                 => esc_attr( 'vendor' ),

                'user_name'                 => sanitize_user( $current_user->user_login ),

                'password'                  => '',

                'user_id'                   => absint( $user_id ),

                'first_name'                => sanitize_text_field( $current_user->first_name ),

                'last_name'                 => sanitize_text_field( $current_user->last_name ),

                'user_email'                => sanitize_email( $current_user->user_email ),

                'company_name'              => sanitize_text_field( $company_name ),

                'company_contact'           => sanitize_text_field( get_user_meta( $user_id, sanitize_key( 'sdwd_vendor_company_contact' ), true ) ),

                'company_address'           => '',

                'company_website'           => esc_url_raw( get_user_meta( $user_id, sanitize_key( 'sdwd_vendor_company_website' ), true ) ),

                'company_location_pincode'  => '',

                'vendor_category'           => absint( get_user_meta( $user_id, sanitize_key( 'sdwd_vendor_category' ), true ) ),

                'account_type'              => sanitize_key( get_user_meta( $user_id, sanitize_key( 'sdwd_vendor_account_type' ), true ) ),
            ] );

            return absint( apply_filters( 'sdweddingdirectory/vendor/post-id', sanitize_email( $current_user->user_email ) ) );
        }

        /**
         *  Check Vendor Ownership
         *  ----------------------
         */
        public static function vendor_user_owns_post( $post_id = 0, $author_id = 0 ){

            $post_id = absint( $post_id );

            $author_id = absint( $author_id );

            if( $post_id === 0 || $author_id === 0 ){
                return false;
            }

            $post_data = get_post( $post_id );

            if( empty( $post_data ) ){
                return false;
            }

            if( absint( $post_data->post_author ) === $author_id ){
                return true;
            }

            $linked_user_id = absint( get_post_meta( $post_id, sanitize_key( 'user_id' ), true ) );

            return $linked_user_id > 0 && $linked_user_id === $author_id;
        }

        /**
         *  Build Business Time String
         *  --------------------------
         */
        public static function normalize_business_time( $time = '', $period = 'AM', $fallback = '' ){

            $time = sanitize_text_field( $time );

            $period = strtoupper( sanitize_text_field( $period ) );

            if( ! in_array( $period, [ 'AM', 'PM' ], true ) ){
                $period = 'AM';
            }

            if( ! preg_match( '/^(0?[1-9]|1[0-2]):[0-5][0-9]$/', $time ) ){
                return sanitize_text_field( $fallback );
            }

            return sanitize_text_field( sprintf( '%1$s %2$s', $time, $period ) );
        }

        /**
         *  1. Vendor Profile Update
         *  ------------------------
         */
        public static function sdweddingdirectory_vendor_profile_action(){

            $_condition_1   = isset( $_POST['security'] ) && $_POST['security'] !== '';

            $_condition_2   = wp_verify_nonce( $_POST['security'], esc_attr( 'vendor_profile_update' ) );

            $post_id        = absint( self:: current_vendor_post_id() );

            $post_data      = get_post( $post_id );

            $_condition_3   = ! empty( $post_data )

                              && absint( $post_id ) > 0

                              && self:: vendor_user_owns_post( absint( $post_id ), absint( parent:: author_id() ) );

            /**
             *  Security Check
             *  --------------
             */
            if ( $_condition_1 && $_condition_2 && $_condition_3 ) {

                /**
                 *  Form Data
                 *  ---------
                 */
                $_FORM_DATA   = array(

                    'first_name'    =>  sanitize_text_field( $_POST[ 'first_name' ] ),

                    'last_name'     =>  sanitize_text_field( $_POST[ 'last_name' ] ),

                    'user_contact'  =>  sanitize_text_field( $_POST[ 'user_contact' ] ),

                    'user_address'  =>  sanitize_text_field( $_POST[ 'user_address' ] ),
                );

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _is_array( $_FORM_DATA ) ){

                    /**
                     *  Normalize ownership mismatch from legacy records.
                     */
                    if( absint( $post_data->post_author ) !== absint( parent:: author_id() ) ){

                        wp_update_post( [

                            'ID'            => absint( $post_id ),

                            'post_author'   => absint( parent:: author_id() ),
                        ] );
                    }

                    foreach( $_FORM_DATA as $key => $value  ){

                        /**
                         *  Update Key + Value
                         *  ------------------
                         */
                            update_post_meta( $post_id, sanitize_key( $key ), $value );
                    }

                    if ( isset( $_POST['sdweddingdirectory_vendor_slug'] ) && $_POST['sdweddingdirectory_vendor_slug'] !== '' ) {

                        $slug_validation = parent:: validate_business_slug(
                                                sanitize_text_field( $_POST['sdweddingdirectory_vendor_slug'] ),
                                                absint( $post_id ),
                                                [
                                                    'user_id'       => absint( parent:: author_id() ),
                                                    'post_types'    => [ 'vendor', 'venue' ],
                                                ]
                                            );

                        if( $slug_validation['status'] === 'taken' ){

                            $current_user = wp_get_current_user();

                            die( json_encode( array(

                                'notice'            =>  absint( '2' ),

                                'message'           =>  esc_attr__( 'This profile exists. If you are the owner, submit a claim request.', 'sdweddingdirectory' ),

                                'claim_required'    =>  true,

                                'claim'             =>  [
                                    'claimant_name'     => sanitize_text_field( trim( $_FORM_DATA['first_name'] . ' ' . $_FORM_DATA['last_name'] ) ),
                                    'claimant_phone'    => sanitize_text_field( $_FORM_DATA['user_contact'] ),
                                    'claimant_email'    => sanitize_email( $current_user->user_email ),
                                    'target_post_id'    => absint( $slug_validation['target_post_id'] ),
                                    'target_post_type'  => sanitize_key( $slug_validation['target_post_type'] ),
                                    'target_slug'       => sanitize_title( $slug_validation['target_slug'] ),
                                ],

                            ) ) );
                        }

                        $slug_update = parent:: apply_validated_slug(
                                            absint( $post_id ),
                                            sanitize_text_field( $_POST['sdweddingdirectory_vendor_slug'] ),
                                            [
                                                'user_id'       => absint( parent:: author_id() ),
                                                'post_types'    => [ 'vendor', 'venue' ],
                                            ]
                                        );

                        if( empty( $slug_update['updated'] ) ){

                            die( json_encode( array(

                                'notice'    =>  absint( '2' ),

                                'message'   =>  ! empty( $slug_update['message'] ) ? esc_attr( $slug_update['message'] ) : esc_attr__( 'Profile slug update failed.', 'sdweddingdirectory' ),

                            ) ) );
                        }
                    }

                    /**
                     *  Successfully Updated Profile
                     *  ----------------------------
                     */
                    die( json_encode( array(

                        'notice'    =>  absint( '1' ),

                        'message'   =>  esc_attr__( 'Profile Updated Successfully', 'sdweddingdirectory' ),

                    ) ) );
                }

            }else{

                die( json_encode( array(

                    'notice'    =>  absint( '2' ),

                    'message'   =>  esc_attr__( 'Profile Updated Error... Please login again then update your profile.', 'sdweddingdirectory' )

                ) ) );
            }
        }

        /**
         *  2. Password Update Action
         *  -------------------------
         */
        public static function sdweddingdirectory_vendor_password_action(){

            $_condition_1   = isset( $_POST['security'] ) && $_POST['security'] !== '';

            $_condition_2   = wp_verify_nonce( $_POST['security'], esc_attr( 'change_password_security' ) );

            /**
             *  Security Check
             *  --------------
             */
            if ( $_condition_1 && $_condition_2 ) {

                $old_pwd        =   esc_attr( $_POST['old_pwd'] );

                $new_pwd        =   esc_attr( $_POST['new_pwd'] );

                $confirm_pwd    =   esc_attr( $_POST['confirm_pwd'] );

                if( $new_pwd == '' || $confirm_pwd == '' ){

                      die( json_encode( array(

                        'notice'    =>  absint( '2' ),

                        'message'   =>  esc_attr__( 'The new password is blank', 'sdweddingdirectory' )

                      ) ) );
                }
                 
                if( $new_pwd != $confirm_pwd ){

                  die( json_encode( array( 

                      'notice'    =>  absint( '2' ),

                      'message'   =>  esc_attr__( 'New Password and confirm password does not match.', 'sdweddingdirectory' )

                    ) ) );
                }            
                
                $user = get_user_by( 'id', SDWeddingDirectory_Config:: author_id() );

                if ( $user && wp_check_password( $old_pwd, $user->data->user_pass, $user->ID) ){

                    /**
                     *  Make sure this is not live website ( sdweddingdirectory.net )
                     *  -----------------------------------------------------
                     */
                    if( parent:: is_demo_user() ){

                        die( json_encode( array(

                            'redirect'  =>  false,

                            'notice'    =>  absint( '0' ),

                            'message'   =>  esc_attr__( 'Sorry! you can\'t Change the Password for demo user.', 'sdweddingdirectory' )

                        ) ) );

                    }else{

                        wp_set_password( $new_pwd, $user->ID );

                        $user_name  = get_userdata( $user->ID )->user_login;

                        $user_email = get_userdata( $user->ID )->user_email;

                        /**
                         *  Email Process
                         *  -------------
                         */
                        if( class_exists( 'SDWeddingDirectory_Email' ) ){

                            /**
                             *  Sending Email
                             *  -------------
                             */
                            SDWeddingDirectory_Email:: sending_email( array(

                                /**
                                 *  1. Setting ID : Email PREFIX_
                                 *  -----------------------------
                                 */
                                'setting_id'        =>      esc_attr( 'change-password' ),

                                /**
                                 *  2. Sending Email ID
                                 *  -------------------
                                 */
                                'sender_email'      =>      sanitize_email( $user_email ),

                                /**
                                 *  3. Email Data Key and Value as Setting Body Have {{...}} all
                                 *  ------------------------------------------------------------
                                 */
                                'email_data'        =>      array(

                                                                'username'          =>  sanitize_user( $user_name ),

                                                                'user_password'     =>  esc_attr( $new_pwd ),

                                                                'user_email'        =>  sanitize_email( $user_email ),
                                                            )
                            ) );
                        }

                        /**
                         *  Logout user
                         *  -----------
                         */
                        wp_logout();

                        /**
                        *  Successfully Change Password
                        *  ----------------------------
                        */
                        die( json_encode( array(

                            'notice'    =>  absint( '1' ),

                            'message'   =>  esc_attr__( 'Password Updated. Please login again.', 'sdweddingdirectory' )

                        ) ) );
                    }

                }else{

                    /**
                     *  Error
                     *  -----
                     */
                    die( json_encode( array(

                      'notice'    => absint( '0' ),

                      'message'   => esc_attr__( 'Old Password is not correct.', 'sdweddingdirectory' ) 

                    ) ) );
                }

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  5. Vendor Filter Update Action
         *  ------------------------------
         */
        public static function sdweddingdirectory_vendor_filter_profile_action(){

            $_condition_1   = isset( $_POST['security'] ) && $_POST['security'] !== '';

            $_condition_2   = wp_verify_nonce( $_POST['security'], esc_attr( 'vendor_filter_profile' ) );

            /**
             *  Security Check
             *  --------------
             */
            if ( $_condition_1 && $_condition_2 ) {

                $post_id    =   absint( parent:: post_id() );

                /**
                 *  Filter Fields
                 *  -------------
                 */
                $filter_fields  =   [

                    esc_attr( 'vendor_pricing' ),
                    esc_attr( 'vendor_services' ),
                    esc_attr( 'vendor_style' ),
                    esc_attr( 'vendor_specialties' ),
                ];

                foreach( $filter_fields as $field ){

                    $value = isset( $_POST[ $field ] ) && parent:: _is_array( $_POST[ $field ] )

                            ? $_POST[ $field ]

                            : [];

                    $clean = [];

                    if( parent:: _is_array( $value ) ){

                        foreach( $value as $key => $item ){

                            $clean_key = sanitize_text_field( $key );

                            if( $clean_key !== '' ){

                                $clean[ $clean_key ] = $clean_key;
                            }
                        }
                    }

                    update_post_meta( $post_id, sanitize_key( $field ), $clean );
                }

                /**
                 *  Successfully Updated Profile
                 *  ----------------------------
                 */
                die( json_encode( array(

                    'notice'    =>  absint( '1' ),

                    'message'   =>  esc_attr__( 'Filters Updated Successfully', 'sdweddingdirectory' ),

                ) ) );

            }else{

                die( json_encode( array(

                    'notice'    =>  absint( '2' ),

                    'message'   =>  esc_attr__( 'Filters Updated Error... Please login again then update your profile.', 'sdweddingdirectory' )

                ) ) );
            }
        }

        /**
         *  3. Social Media Updat Action
         *  ----------------------------
         */
        public static function sdweddingdirectory_vendor_social_action(){

            $_condition_1   = isset( $_POST['security'] ) && $_POST['security'] !== '';

            $_condition_2   = wp_verify_nonce( $_POST['security'], esc_attr( 'social_media_update' ) );

            if( $_condition_1 && $_condition_2 ){

                /**
                 *  Update Social Media
                 *  -------------------
                 */
                update_post_meta(

                    absint( parent:: post_id() ),

                    sanitize_key( 'social_profile' ),

                    isset( $_POST[ 'social_profile' ] ) && ! empty( $_POST[ 'social_profile' ] )

                    ?   $_POST[ 'social_profile' ]

                    :   ''
                );

                /**
                 *  Successfully updated
                 *  --------------------
                 */
                die( json_encode( array( 

                    'notice'    =>   absint( '1' ),

                    'message'   =>  esc_attr__( 'Social Media Updated Successfully', 'sdweddingdirectory' )

                ) ) );

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  4. Business Pofile Updat Action
         *  -------------------------------
         */
        public static function sdweddingdirectory_vendor_business_profile_action(){

            $_condition_1   =   isset( $_POST['security'] ) && $_POST['security'] !== '';

            $_condition_2   =   wp_verify_nonce( $_POST['security'], esc_attr( 'vendor_business_profile' ) );

            $_POST_ID       =   absint( self:: current_vendor_post_id() );

            $_condition_3   =   self:: vendor_user_owns_post( absint( $_POST_ID ), absint( parent:: author_id() ) );

            if( $_condition_1 && $_condition_2 && $_condition_3 ){

                    global $post, $wp_query;

                    $post_data = get_post( absint( $_POST_ID ) );

                    if( ! empty( $post_data ) && absint( $post_data->post_author ) !== absint( parent:: author_id() ) ){

                        wp_update_post( [

                            'ID'            => absint( $_POST_ID ),

                            'post_author'   => absint( parent:: author_id() ),
                        ] );
                    }

                    /**
                    *  Update Post Title + Description
                    *  -------------------------------
                    */
                    wp_update_post( array(

                        'ID'                =>   absint( $_POST_ID ),

                        'post_content'      =>   $_POST['post_content'],

                    ) );

                  /**
                   *  Form data
                   *  ---------
                   */
                  $_FORM_DATA = array(

                      'company_name'        =>      isset( $_POST[ 'company_name' ] ) && ! empty( $_POST[ 'company_name' ] ) 

                                                    ?       esc_attr( $_POST[ 'company_name' ] )

                                                    :       '',

                      'company_website'     =>      isset( $_POST[ 'company_website' ] ) && ! empty( $_POST[ 'company_website' ] ) 

                                                    ?       esc_url(  $_POST[ 'company_website' ] )

                                                    :       '',

                      'company_email'       =>      isset( $_POST[ 'company_email' ] ) && ! empty( $_POST[ 'company_email' ] ) 

                                                    ?       sanitize_email( $_POST[ 'company_email' ] )

                                                    :       '',

                     'company_contact'     =>        isset( $_POST[ 'company_contact' ] ) && ! empty( $_POST[ 'company_contact' ] ) 
                                                        
                                                    ?       esc_attr( $_POST[ 'company_contact' ] )

                                                    :       ''
                  );

                  if( parent:: _is_array( $_FORM_DATA ) ){

                      foreach( $_FORM_DATA as $key => $value  ){

                          /**
                           *  Update Post Meta
                           *  ----------------
                           */
                          update_post_meta( absint( $_POST_ID ), sanitize_key( $key ), esc_attr( $value ) );
                      }
                  }

                  if( isset( $_POST['working_hours'] ) && parent:: _is_array( $_POST['working_hours'] ) ){
                      foreach( $_POST['working_hours'] as $key => $value ){
                          update_post_meta( absint( $_POST_ID ), sanitize_key( $key ), sanitize_text_field( $value ) );
                      }
                  }

                  die( json_encode( array(

                      'notice'  =>  absint( '1' ),

                      'message' =>  esc_attr__( 'Business Profile Update Successfully', 'sdweddingdirectory' )

                  ) ) );

              }else{

                  self:: security_issue_found();
              }
        }

        /**
         *  6. Vendor Onboarding Helper Save
         *  -------------------------------
         */
        public static function sdwd_vendor_onboarding_save_action(){

            $has_nonce = isset( $_POST['security'] ) && $_POST['security'] !== '';

            $is_valid_nonce = $has_nonce ? wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), esc_attr( 'sdwd_vendor_onboarding_save' ) ) : false;

            $post_id = absint( parent:: post_id() );

            $post_data = get_post( $post_id );

            $is_vendor_owner = ! empty( $post_data )

                                && absint( $post_data->post_author ) === absint( parent:: author_id() )

                                && in_array( 'vendor', (array) wp_get_current_user()->roles, true );

            if( ! $is_valid_nonce || ! $is_vendor_owner || ! current_user_can( 'edit_post', absint( $post_id ) ) ){

                self:: security_issue_found();
            }

            $vendor_category = isset( $_POST['vendor_category'] ) ? absint( $_POST['vendor_category'] ) : 0;

            if( $vendor_category > 0 ){

                wp_set_post_terms( absint( $post_id ), [ absint( $vendor_category ) ], esc_attr( 'vendor-category' ) );
            }

            $filter_fields = [ 'vendor_pricing', 'vendor_services', 'vendor_style', 'vendor_specialties' ];

            foreach( $filter_fields as $field ){

                $input = isset( $_POST[ $field ] ) && parent:: _is_array( $_POST[ $field ] )

                        ? $_POST[ $field ]

                        : [];

                $clean = [];

                if( parent:: _is_array( $input ) ){

                    foreach( $input as $value ){

                        $clean_value = sanitize_text_field( wp_unslash( $value ) );

                        if( $clean_value !== '' ){

                            $clean[ $clean_value ] = $clean_value;
                        }
                    }
                }

                update_post_meta( absint( $post_id ), sanitize_key( $field ), $clean );
            }

            $valid_days = [ 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' ];

            $open_days = isset( $_POST['business_open_days'] ) && parent:: _is_array( $_POST['business_open_days'] )

                            ? $_POST['business_open_days']

                            : [];

            $clean_days = [];

            if( parent:: _is_array( $open_days ) ){

                foreach( $open_days as $day ){

                    $day = sanitize_key( wp_unslash( $day ) );

                    if( in_array( $day, $valid_days, true ) ){

                        $clean_days[] = $day;
                    }
                }
            }

            $open_time  = isset( $_POST['business_open_time'] ) ? sanitize_text_field( wp_unslash( $_POST['business_open_time'] ) ) : '';
            $close_time = isset( $_POST['business_close_time'] ) ? sanitize_text_field( wp_unslash( $_POST['business_close_time'] ) ) : '';

            if( ! preg_match( '/^([0-1][0-9]|2[0-3]):00$/', $open_time ) ){
                $open_time = '';
            }

            if( ! preg_match( '/^([0-1][0-9]|2[0-3]):00$/', $close_time ) ){
                $close_time = '';
            }

            update_post_meta( absint( $post_id ), sanitize_key( 'business_open_days' ), $clean_days );
            update_post_meta( absint( $post_id ), sanitize_key( 'business_open_time' ), $open_time );
            update_post_meta( absint( $post_id ), sanitize_key( 'business_close_time' ), $close_time );

            update_user_meta( absint( parent:: author_id() ), sanitize_key( 'sdwd_profile_helper_complete' ), absint( '1' ) );

            die( json_encode( array(

                'notice'    => absint( '1' ),

                'message'   => esc_attr__( 'Profile helper saved successfully.', 'sdweddingdirectory' ),

            ) ) );
        }

        /**
         *  7. Vendor Gallery Upload Save
         *  -----------------------------
         */
        public static function sdweddingdirectory_vendor_upload_photos_action(){

            $_condition_1 = isset( $_POST['security'] ) && $_POST['security'] !== '';

            $_condition_2 = wp_verify_nonce( $_POST['security'], esc_attr( 'vendor_upload_photos_security' ) );

            $post_id = absint( self:: current_vendor_post_id() );

            $post_data = get_post( $post_id );

            $_condition_3 = ! empty( $post_data )
                            && absint( $post_id ) > 0
                            && self:: vendor_user_owns_post( absint( $post_id ), absint( parent:: author_id() ) );

            if ( $_condition_1 && $_condition_2 && $_condition_3 ) {

                $gallery_raw = isset( $_POST['vendor_gallery'] ) ? sanitize_text_field( wp_unslash( $_POST['vendor_gallery'] ) ) : '';

                $gallery_ids = array_filter( array_map( 'absint', explode( ',', $gallery_raw ) ) );

                // Enforce 20-photo limit.
                $gallery_ids = array_slice( $gallery_ids, 0, 20 );

                $gallery_value = implode( ',', $gallery_ids );

                update_post_meta( $post_id, sanitize_key( 'venue_gallery' ), $gallery_value );

                die( json_encode( array(

                    'notice'    => absint( '1' ),

                    'message'   => esc_attr__( 'Gallery updated successfully.', 'sdweddingdirectory' ),

                ) ) );

            } else {

                die( json_encode( array(

                    'notice'    => absint( '2' ),

                    'message'   => esc_attr__( 'Gallery update failed. Please login again and try.', 'sdweddingdirectory' ),

                ) ) );
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Vendor_Profile_AJAX:: get_instance();
}
