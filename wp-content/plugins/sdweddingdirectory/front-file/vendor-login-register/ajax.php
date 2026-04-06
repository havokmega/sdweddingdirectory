<?php
/**
 *  SDWeddingDirectory - Vendor Login & Registration Form
 *  ---------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Login_Register_Form_AJAX' ) && class_exists( 'SDWeddingDirectory_Front_End_Modules' ) ){

    /**
     *  SDWeddingDirectory - Vendor Login & Registration Form
     *  ---------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Login_Register_Form_AJAX extends SDWeddingDirectory_Front_End_Modules{

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

            add_action( 'wp_ajax_sdweddingdirectory_vendor_register_form_action', [ $this, 'sdweddingdirectory_vendor_register_form_action' ] );
            add_action( 'wp_ajax_nopriv_sdweddingdirectory_vendor_register_form_action', [ $this, 'sdweddingdirectory_vendor_register_form_action' ] );
            add_action( 'wp_ajax_sdweddingdirectory_vendor_login_action', [ $this, 'sdweddingdirectory_vendor_login_action' ] );
            add_action( 'wp_ajax_nopriv_sdweddingdirectory_vendor_login_action', [ $this, 'sdweddingdirectory_vendor_login_action' ] );
        }

        /**
         *  4. Vendor Registration through AJAX action
         *  ------------------------------------------
         */
        public static function sdweddingdirectory_vendor_register_form_action(){

            /**
             *  Vendor Registration through AJAX action With Test Security
             *  ----------------------------------------------------------
             */
            if( wp_verify_nonce( $_POST['security'], 'sdweddingdirectory_vendor_registration_form_security' ) ){

                /**
                 *  Vendor Registration Code Goes here
                 *  ----------------------------------
                 */
                $_vendor_first_name             =   esc_attr( $_POST[ 'first_name' ] );

                $_vendor_last_name              =   esc_attr( $_POST[ 'last_name' ] );

                $_vendor_username               =   sanitize_user( $_POST[ 'username' ] );

                $_vendor_email_id               =   sanitize_email( $_POST[ 'email' ] );

                $_vendor_password               =   wp_unslash( $_POST[ 'password' ] );

                $company_name                   =   esc_attr( $_POST[ 'company_name' ] );

                $company_website                =   esc_url( $_POST[ 'company_website' ] );

                $company_contact                =   esc_attr( $_POST[ 'contact_number' ] );

                $account_type                   =   isset( $_POST[ 'account_type' ] ) ? sanitize_key( $_POST[ 'account_type' ] ) : esc_attr( 'vendor' );

                if( ! in_array( $account_type, [ 'vendor', 'venue' ], true ) ){
                    $account_type = esc_attr( 'vendor' );
                }

                $vendor_category                =   $account_type === esc_attr( 'venue' )
                                                    ? absint( '0' )
                                                    : ( isset( $_POST[ 'vendor_category' ] ) ? absint( $_POST[ 'vendor_category' ] ) : absint( '0' ) );

                /**
                 *  Check the Fields are valid ?
                 *  ----------------------------
                 */
                if( isset( $_vendor_username ) && $_vendor_username == '' ){

                    /**
                     *  Vendor User Name is Required
                     *  ----------------------------
                     */
                    die( json_encode( array(

                          'message'       =>    esc_attr__( 'Username is Required!', 'sdweddingdirectory' ),

                          'notice'        =>    absint( '0' ),

                          'redirect'      =>    false,

                    ) ) );
                }

                /**
                 *  Username already exitsts!
                 *  -------------------------
                 */
                elseif( isset( $_vendor_username ) && $_vendor_username !== '' && username_exists( $_vendor_username ) ){

                    /**
                     *  Vendor User Name Exists
                     *  -----------------------
                     */
                    die( json_encode( array(

                          'message'       =>    esc_attr__( 'Username already exitsts!', 'sdweddingdirectory' ),

                          'notice'        =>    absint( '0' ),

                          'redirect'      =>    false,

                    ) ) );
                }

                /**
                 *  Password is Empty!
                 *  -----------------
                 */
                elseif( isset( $_vendor_password ) && $_vendor_password == '' ){

                    /**
                     *  Vendor Password is Empty ?
                     *  --------------------------
                     */
                    die( json_encode( array(

                          'message'       =>    esc_attr__( 'Password is Empty!', 'sdweddingdirectory' ),

                          'notice'        =>    absint( '0' ),

                          'redirect'      =>    false,

                    ) ) );
                }

                /**
                 *  First Name Fields Empty
                 *  -----------------------
                 */
                elseif( isset( $_vendor_first_name  ) && $_vendor_first_name  == '' ){

                    /**
                     *  First Name is Empty
                     *  -------------------
                     */
                    die( json_encode( array(

                          'message'       =>    esc_attr__( 'First Name Fields Empty', 'sdweddingdirectory' ),

                          'notice'        =>    absint( '0' ),

                          'redirect'      =>    false,

                    ) ) );
                }

                /**
                 *  Last Name Fields Empty
                 *  ----------------------
                 */
                elseif( isset( $_vendor_last_name  ) && $_vendor_last_name  == '' ){

                    /**
                     *  Last Name is Empty
                     *  -------------------
                     */
                    die( json_encode( array(

                          'message'       =>    esc_attr__( 'Last Name Fields Empty', 'sdweddingdirectory' ),

                          'notice'        =>    absint( '0' ),

                          'redirect'      =>    false,

                    ) ) );
                }

                /**
                 *  Email is Required!
                 *  ------------------
                 */
                elseif( isset( $_vendor_email_id ) && $_vendor_email_id == '' ){

                    /**
                     *  Email is Required
                     *  -----------------
                     */
                    die( json_encode( array(

                          'message'       =>    esc_attr__( 'Email is Required!', 'sdweddingdirectory' ),

                          'notice'        =>    absint( '0' ),

                          'redirect'      =>    false,

                    ) ) );
                }

                /**
                 *  Is Not Email ID
                 *  ---------------
                 */
                elseif( isset( $_vendor_email_id ) && $_vendor_email_id !== '' && ! is_email( $_vendor_email_id ) ){

                    /**
                     *  Is Email
                     *  --------
                     */
                    die( json_encode( array(

                          'message'       =>    esc_attr__( 'Is Not Email ID', 'sdweddingdirectory' ),

                          'notice'        =>    absint( '0' ),

                          'redirect'      =>    false,

                    ) ) );
                }

                /**
                 *  Email Already Exists
                 *  --------------------
                 */
                elseif( is_email( $_vendor_email_id ) && email_exists( $_vendor_email_id ) ){

                    /**
                     *  Email Already Exists
                     *  --------------------
                     */
                    die( json_encode( array(

                          'message'       =>    esc_attr__( 'Email Already Exists', 'sdweddingdirectory' ),

                          'notice'        =>    absint( '0' ),

                          'redirect'      =>    false,

                    ) ) );
                }

                /**
                 *  Company Name Fields Empty!
                 *  --------------------------
                 */
                elseif( isset( $company_name ) && $company_name == '' ){

                    /**
                     *  Email Already Exists
                     *  --------------------
                     */
                    die( json_encode( array(

                          'message'       =>    esc_attr__( 'Company Name Fields Empty!', 'sdweddingdirectory' ),

                          'notice'        =>    absint( '0' ),

                          'redirect'      =>    false,

                    ) ) );
                }

                /**
                 *  Contact Number Fields Empty!
                 *  ----------------------------
                 */
                elseif( isset( $company_contact ) && $company_contact == '' ){

                    /**
                     *  Email Already Exists
                     *  --------------------
                     */
                    die( json_encode( array(

                          'message'       =>    esc_attr__( 'Contact Number Fields Empty!', 'sdweddingdirectory' ),

                          'notice'        =>    absint( '0' ),

                          'redirect'      =>    false,

                    ) ) );
                }

                /**
                 *  All Fields Works Fine.
                 *  ----------------------
                 */
                else{

                    /**
                     *  Create Vendor Here
                     *  ------------------
                     */

                    $user_login         =       wp_slash( $_vendor_username );

                    $user_email         =       wp_slash( $_vendor_email_id );

                    $user_pass          =       esc_attr( $_vendor_password );

                    $role               =       esc_attr( 'vendor' );

                    $first_name         =       esc_attr( $_vendor_first_name );

                    $last_name          =       esc_attr( $_vendor_last_name );

                    $userdata           =       compact( 'user_login', 'user_email', 'user_pass', 'role', 'first_name', 'last_name' );

                    /**
                     *
                     *  Vendor Register as WordPress user in Database
                     *  ---------------------------------------------
                     *  @link  https://codex.wordpress.org/Function_Reference/wp_insert_user
                     *  
                     */
                    $_user_id                   =   wp_insert_user( $userdata );

                    /**
                     *  Registration Failed
                     *  -------------------
                     */
                    if( is_wp_error( $_user_id ) ){

                        die( json_encode( [

                            'notice'            =>      absint( '0' ),

                            'message'           =>      $_user_id->get_error_message(),

                            'redirect'          =>      false,

                            'modal_close'       =>      false,

                        ] ) );
                    }

                    /**
                     *  Form Collection Data
                     *  --------------------
                     */
                    $_post_meta_collection              =       array(

                        /**
                         *  Profile Information
                         *  -------------------
                         */
                        'user_name'                     =>      esc_attr( $_vendor_username ),

                        'user_id'                       =>      absint( $_user_id ),

                        'first_name'                    =>      esc_attr( $_vendor_first_name ),

                        'last_name'                     =>      esc_attr( $_vendor_last_name ),

                        'user_email'                    =>      sanitize_email( $_vendor_email_id ),

                       /**
                        *  Company Information
                        *  -------------------
                        */
                        'company_name'                  =>      esc_attr( $company_name ),

                        'company_contact'               =>      esc_attr( $company_contact ),

                        'company_website'               =>      !   empty( $company_website ) 

                                                                ?   esc_url( $company_website )

                                                                :   '',

                        'company_location_pincode'      =>      '',

                        'vendor_category'               =>      absint( $vendor_category ),

                        'account_type'                  =>      sanitize_key( $account_type ),
                    );

                    /**
                     *  Persist vendor signup fields for recovery / claim flow.
                     */
                    update_user_meta( absint( $_user_id ), sanitize_key( 'sdwd_vendor_company_name' ), sanitize_text_field( $company_name ) );
                    update_user_meta( absint( $_user_id ), sanitize_key( 'sdwd_vendor_company_contact' ), sanitize_text_field( $company_contact ) );
                    update_user_meta( absint( $_user_id ), sanitize_key( 'sdwd_vendor_company_website' ), esc_url_raw( $company_website ) );
                    update_user_meta( absint( $_user_id ), sanitize_key( 'sdwd_vendor_category' ), absint( $vendor_category ) );
                    update_user_meta( absint( $_user_id ), sanitize_key( 'sdwd_vendor_account_type' ), sanitize_key( $account_type ) );

                    /**
                     *  Is Admin Created Vendor via SDWeddingDirectory > Setting Page > Import Vendor ?
                     *  -----------------------------------------------------------------------
                     */
                    if( isset( $_POST['admin_created_user'] ) && $_POST['admin_created_user'] == absint( '1' ) ){

                        /**
                         *  Verification Not Done!
                         *  ----------------------
                         */
                        update_user_meta( $_user_id, sanitize_key( 'sdweddingdirectory_user_verify' ), esc_attr( 'yes' ) );

                        /**
                         *  Couple Registration - Core Configuration
                         *  ----------------------------------------
                         */
                        do_action( 'sdweddingdirectory/register/vendor/configuration', $_post_meta_collection );

                        /**
                         *  Email Process
                         *  -------------
                         */
                        if( class_exists( 'SDWeddingDirectory_Email' ) && $_POST['sending_email'] == absint( '1' ) ){

                            /**
                             *  Sending Email
                             *  -------------
                             */
                            SDWeddingDirectory_Email:: sending_email( array(

                                /**
                                 *  1. Setting ID : Email PREFIX_
                                 *  -----------------------------
                                 */
                                'setting_id'        =>      esc_attr( 'vendor-register' ),

                                /**
                                 *  2. Sending Email ID
                                 *  -------------------
                                 */
                                'sender_email'      =>      sanitize_email( $_vendor_email_id ),

                                /**
                                 *  3. Email Data Key and Value as Setting Body Have {{...}} all
                                 *  ------------------------------------------------------------
                                 */
                                'email_data'        =>      array(

                                                                'vendor_username'      =>  sanitize_user( $_vendor_username ),

                                                                'vendor_email'         =>  sanitize_email( $_vendor_email_id ),
                                                            )
                            ) );
                        }

                        /**
                         *  Successfull Whole process with redirection on vendor dashboad with alert
                         *  ------------------------------------------------------------------------
                         */
                        die( json_encode( array(

                            'notice'          =>  absint( '1' ),

                            'message'         =>  esc_attr__( 'Supplier account create successfully!', 'sdweddingdirectory' ),

                        ) ) );
                    }

                    /**
                     *  Normal Vendor Popup Process Here
                     *  --------------------------------
                     */
                    else{

                        /**
                         *  Popup registrations should be instantly usable.
                         */
                        update_user_meta( $_user_id, sanitize_key( 'sdweddingdirectory_user_verify' ), esc_attr( 'yes' ) );

                        do_action( 'sdweddingdirectory/register/vendor/configuration', $_post_meta_collection );

                        $_claim_target_id    = absint( get_user_meta( absint( $_user_id ), sanitize_key( 'sdwd_pending_claim_target_post_id' ), true ) );
                        $_claim_target_type  = sanitize_key( get_user_meta( absint( $_user_id ), sanitize_key( 'sdwd_pending_claim_target_post_type' ), true ) );
                        $_claim_target_slug  = sanitize_title( get_user_meta( absint( $_user_id ), sanitize_key( 'sdwd_pending_claim_target_slug' ), true ) );

                        $user = get_user_by( 'id', $_user_id );

                        if( ! empty( $user ) ) {

                            wp_set_current_user( $_user_id, $user->user_login );

                            wp_set_auth_cookie( $_user_id );

                            do_action( 'wp_login', $user->user_login, $user );
                        }

                        update_user_meta( $_user_id, sanitize_key( 'sdweddingdirectory_user_last_login' ), date( 'Y-m-d H:i:s' ) );

                        if( $_claim_target_id > 0 && in_array( $_claim_target_type, [ 'vendor', 'venue' ], true ) && $_claim_target_slug !== '' ){

                            die( json_encode( array(

                                'notice'            =>      absint( '2' ),

                                'message'           =>      esc_attr__( 'This profile exists. If you are the owner, submit a claim request.', 'sdweddingdirectory' ),

                                'redirect'          =>      false,

                                'modal_close'       =>      false,

                                'claim_required'    =>      true,

                                'claim'             =>      [
                                    'claimant_name'     => esc_attr( trim( $_vendor_first_name . ' ' . $_vendor_last_name ) ),
                                    'claimant_phone'    => esc_attr( $company_contact ),
                                    'claimant_email'    => sanitize_email( $_vendor_email_id ),
                                    'target_post_id'    => absint( $_claim_target_id ),
                                    'target_post_type'  => sanitize_key( $_claim_target_type ),
                                    'target_slug'       => sanitize_title( $_claim_target_slug ),
                                ],

                            ) ) );
                        }

                        if( class_exists( 'SDWeddingDirectory_Email' ) ){

                            SDWeddingDirectory_Email:: sending_email( array(

                                'setting_id'        =>      esc_attr( 'vendor-register' ),

                                'sender_email'      =>      sanitize_email( $_vendor_email_id ),

                                'email_data'        =>      array(

                                                                'vendor_username'      =>  sanitize_user( $_vendor_username ),

                                                                'vendor_email'         =>  sanitize_email( $_vendor_email_id ),
                                                            )
                            ) );
                        }

                        $_redirect_link = ! empty( $_POST['redirect_link'] )

                                        ? esc_url( $_POST['redirect_link'] )

                                        : esc_url( home_url( '/' ) );

                        die( json_encode( array(

                            'notice'            =>      absint( '1' ),

                            'message'           =>      esc_attr__( 'Supplier account create successfully!', 'sdweddingdirectory' ),

                            'redirect'          =>      true,

                            'redirect_link'     =>      $_redirect_link,

                            'modal_close'       =>      true

                        ) ) );

                    }

                } // else end

            } // main if

            /**
             *  Security Pass
             *  -------------
             */
            else {

                /**
                 *  Error
                 *  -----
                 */
                die( json_encode( [

                    'notice'            =>      absint( '0' ),

                    'message'           =>      esc_attr__( 'Vendor Registration Error...', 'sdweddingdirectory' ),

                    'redirect'          =>      false,

                ] ) );
            }
        }

        /**
         *  5. SDWeddingDirectory - Vendor Login AJAX action
         *  ----------------------------------------
         */
        public static function sdweddingdirectory_vendor_login_action(){

            /**
             *  SDWeddingDirectory Security Check
             *  -------------------------
             */
            if(  wp_verify_nonce( $_POST['security'], esc_attr( 'sdweddingdirectory_vendor_login_security' ) ) ){

                /**
                 *  Login User Details Here
                 *  -----------------------
                 */
                $info                           =     [];

                $info['user_login']             =     sanitize_user( $_POST[ 'sdweddingdirectory_vendor_login_username' ] );

                $info['user_password']          =     wp_unslash( $_POST[ 'sdweddingdirectory_vendor_login_password' ] );

                $info['remember']               =     true;

                $user_signon                    =     wp_signon( $info, is_ssl() );

                /**
                 *  Make sure no any WP Error
                 *  -------------------------
                 */
                if( is_wp_error( $user_signon ) ){

                    /**
                     *  Successfull Whole process with redirection on couple dashboad with alert
                     *  ------------------------------------------------------------------------
                     */
                    die( json_encode( array(

                        'notice'          =>    absint( '2' ),

                        'message'         =>    $user_signon->get_error_message(),

                        'redirect'        =>    false,

                        'modal_close'     =>    false,

                        'redirect_link'   =>    '',

                    ) ) );
                }

                /**
                 *  No WP Error Found
                 *  -----------------
                 */
                else{

                    /**
                     *  User now login and they have access to get value
                     *  ------------------------------------------------
                     */
                    $user_roles                =        get_userdata( $user_signon->ID )->roles;

                    /**
                     *  Is Vendor user ?
                     *  ----------------
                     */
                    if ( in_array( 'vendor', $user_roles, true) ) {

                        /**
                         *  ------------------------
                         *  Set Current User Process
                         *  ------------------------
                         *  @link : https://developer.wordpress.org/reference/functions/wp_set_current_user/#user-contributed-notes
                         *  -------------------------------------------------------------------------------------------------------
                         */
                        global $current_user;

                        $user_id    =   absint( $user_signon->ID );

                        /**
                         *  Is verify user ?
                         *  ----------------
                         */
                        $_is_verify_user    =   get_user_meta( $user_id, sanitize_key( 'sdweddingdirectory_user_verify' ), true );

                        /**
                         *  Dev environment bypass: auto-verify users when SDWD_AUTO_VERIFY is defined.
                         */
                        if( defined( 'SDWD_AUTO_VERIFY' ) && SDWD_AUTO_VERIFY && ( empty( $_is_verify_user ) || $_is_verify_user === 'no' ) ){

                            update_user_meta( $user_id, sanitize_key( 'sdweddingdirectory_user_verify' ), esc_attr( 'yes' ) );

                            $_is_verify_user = esc_attr( 'yes' );
                        }

                        /**
                         *  Verification Fields Empty!
                         *  --------------------------
                         */
                        if( empty( $_is_verify_user ) || $_is_verify_user == esc_attr( 'no' ) ){

                            /**
                             *  Logout
                             *  ------
                             */
                            wp_logout();

                            /**
                             *  Successfull Whole process with redirection on couple dashboad with alert
                             *  ------------------------------------------------------------------------
                             */
                            die( json_encode( [

                                'notice'                =>      absint( '0' ),

                                'message'               =>      esc_attr__( 'Please verify your account by checking your email.', 'sdweddingdirectory' ),

                                'redirect'              =>      false,

                                'redirect_link'         =>      esc_url( $_POST[ 'redirect_link' ] ),

                                'modal_close'           =>      false,

                                'button_string'         =>      esc_attr__( 'Check Your Email', 'sdweddingdirectory' ),

                                'button_class'          =>      'pe-none disabled fw-bold'

                            ] ) );
                        }

                        /**
                         *  Verification Done ?
                         *  -------------------
                         */
                        elseif( $_is_verify_user == esc_attr( 'yes' ) ){

                            $user       =   get_user_by( 'id', $user_id ); 

                            if( $user ) {

                                wp_set_current_user( $user_id, $user->user_login );

                                wp_set_auth_cookie( $user_id );

                                do_action( 'wp_login', $user->user_login, $user );
                            }
                        }

                        /**
                         *  Have WordPress Error with Login ?
                         *  ---------------------------------
                         */
                        if( is_wp_error( $user_signon ) ){

                            /**
                             *  WordPress Error
                             *  ---------------
                             */
                            die( json_encode( array(

                                  'message'         =>    $user_signon->get_error_message(),

                                  'notice'          =>    absint( '3' ),

                                  'redirect'        =>    false,

                                  'redirect_link'   =>    esc_url( home_url( '/' ) ),

                            ) ) );
                        }

                        /**
                         *  No WordPress Error
                         *  ------------------
                         */
                        else{

                            $vendor_post_id = absint( apply_filters( 'sdweddingdirectory/vendor/post-id', sanitize_email( $user->user_email ) ) );

                            /**
                             *  Recovery path: create vendor profile post if missing.
                             */
                            if( $vendor_post_id === 0 ){

                                $company_name = sanitize_text_field( get_user_meta( $user_id, sanitize_key( 'sdwd_vendor_company_name' ), true ) );
                                $company_contact = sanitize_text_field( get_user_meta( $user_id, sanitize_key( 'sdwd_vendor_company_contact' ), true ) );
                                $company_website = esc_url_raw( get_user_meta( $user_id, sanitize_key( 'sdwd_vendor_company_website' ), true ) );
                                $vendor_category = absint( get_user_meta( $user_id, sanitize_key( 'sdwd_vendor_category' ), true ) );
                                $account_type = sanitize_key( get_user_meta( $user_id, sanitize_key( 'sdwd_vendor_account_type' ), true ) );

                                if( $company_name !== '' ){

                                    do_action( 'sdweddingdirectory/register/vendor/configuration', [
                                        'post_type'                 => esc_attr( 'vendor' ),
                                        'user_name'                 => sanitize_user( $user->user_login ),
                                        'password'                  => '',
                                        'user_id'                   => absint( $user_id ),
                                        'first_name'                => sanitize_text_field( $user->first_name ),
                                        'last_name'                 => sanitize_text_field( $user->last_name ),
                                        'user_email'                => sanitize_email( $user->user_email ),
                                        'company_name'              => sanitize_text_field( $company_name ),
                                        'company_contact'           => sanitize_text_field( $company_contact ),
                                        'company_address'           => '',
                                        'company_website'           => esc_url_raw( $company_website ),
                                        'company_location_pincode'  => '',
                                        'vendor_category'           => absint( $vendor_category ),
                                        'account_type'              => in_array( $account_type, [ 'vendor', 'venue' ], true ) ? $account_type : esc_attr( 'vendor' ),
                                    ] );
                                }

                                $vendor_post_id = absint( apply_filters( 'sdweddingdirectory/vendor/post-id', sanitize_email( $user->user_email ) ) );
                            }

                            /**
                             *  No profile post still available: claim flow or safe stop.
                             */
                            if( $vendor_post_id === 0 ){

                                $_claim_target_id    = absint( get_user_meta( absint( $user_id ), sanitize_key( 'sdwd_pending_claim_target_post_id' ), true ) );
                                $_claim_target_type  = sanitize_key( get_user_meta( absint( $user_id ), sanitize_key( 'sdwd_pending_claim_target_post_type' ), true ) );
                                $_claim_target_slug  = sanitize_title( get_user_meta( absint( $user_id ), sanitize_key( 'sdwd_pending_claim_target_slug' ), true ) );
                                $_claim_target_owned = false;

                                if( $_claim_target_id > 0 ){

                                    $_claim_target_post = get_post( absint( $_claim_target_id ) );

                                    $_claim_target_owned = ! empty( $_claim_target_post )

                                                            && absint( $_claim_target_post->post_author ) === absint( $user_id );

                                    if( $_claim_target_owned ){

                                        delete_user_meta( absint( $user_id ), sanitize_key( 'sdwd_pending_claim_target_post_id' ) );
                                        delete_user_meta( absint( $user_id ), sanitize_key( 'sdwd_pending_claim_target_post_type' ) );
                                        delete_user_meta( absint( $user_id ), sanitize_key( 'sdwd_pending_claim_target_slug' ) );

                                        $vendor_post_id = absint( apply_filters( 'sdweddingdirectory/vendor/post-id', sanitize_email( $user->user_email ) ) );
                                    }
                                }

                                if( $vendor_post_id === 0 && $_claim_target_id > 0 && in_array( $_claim_target_type, [ 'vendor', 'venue' ], true ) && $_claim_target_slug !== '' && ! $_claim_target_owned ){

                                    $claim_name = trim( sanitize_text_field( $user->first_name ) . ' ' . sanitize_text_field( $user->last_name ) );
                                    $claim_phone = sanitize_text_field( get_user_meta( absint( $user_id ), sanitize_key( 'sdwd_vendor_company_contact' ), true ) );
                                    $claim_email = sanitize_email( $user->user_email );

                                    die( json_encode( [

                                        'notice'            => absint( '2' ),

                                        'message'           => esc_attr__( 'This profile exists. If you are the owner, submit a claim request.', 'sdweddingdirectory' ),

                                        'redirect'          => false,

                                        'modal_close'       => false,

                                        'claim_required'    => true,

                                        'claim'             => [
                                            'claimant_name'     => $claim_name !== '' ? $claim_name : sanitize_text_field( $user->display_name ),
                                            'claimant_phone'    => $claim_phone,
                                            'claimant_email'    => $claim_email,
                                            'target_post_id'    => absint( $_claim_target_id ),
                                            'target_post_type'  => sanitize_key( $_claim_target_type ),
                                            'target_slug'       => sanitize_title( $_claim_target_slug ),
                                        ],

                                    ] ) );
                                }
                            }

	                            /**
	                             *  User Token
	                             *  ----------
	                             */
	                            update_user_meta( $user_id, sanitize_key( 'sdweddingdirectory_user_last_login' ), date( 'Y-m-d H:i:s' ) );

	                            $_redirect_link      =   ! empty( $_POST[ 'redirect_link' ] )

	                                                    ?   esc_url_raw( wp_unslash( $_POST[ 'redirect_link' ] ) )

	                                                    :   '';

	                            $_redirect_dashboard =   '';

	                            if( ! empty( $_redirect_link ) ){

	                                $_redirect_query = wp_parse_url( $_redirect_link, PHP_URL_QUERY );

	                                if( ! empty( $_redirect_query ) ){

	                                    parse_str( $_redirect_query, $_redirect_args );

	                                    $_redirect_dashboard = isset( $_redirect_args[ 'dashboard' ] )

	                                                            ?   sanitize_key( $_redirect_args[ 'dashboard' ] )

	                                                            :   '';
	                                }
	                            }

	                            if( empty( $_redirect_link ) || $_redirect_dashboard === esc_attr( 'vendor-dashboard' ) ){

	                                $_redirect_link = apply_filters(

	                                    'sdweddingdirectory/vendor-menu/page-link',

	                                    esc_attr( parent:: vendor_dashboard_entry_page( $user, $_redirect_dashboard ) )
	                                );
	                            }

	                            /**
	                             *  Successfull Whole process with redirection on vendor dashboad with alert
	                             *  ------------------------------------------------------------------------
	                             */
                            die( json_encode( [

                                'notice'            =>      absint( '1' ),

                                'message'           =>      esc_attr__( 'Login successfully!', 'sdweddingdirectory' ),

	                                'redirect'          =>      true,

	                                'redirect_link'     =>      esc_url( $_redirect_link ),

	                                'modal_close'       =>      true

	                            ] ) );
                        }
                    }

                    /**
                     *  User is not Vendor
                     *  ------------------
                     */
                    else{

                        /**
                         *  Logout
                         *  ------
                         */
                        wp_logout();

                        /**
                         *  Error
                         *  -----
                         */
                        die( json_encode( [

                            'notice'            =>   absint( '0' ),

                            'message'           =>   esc_attr__( 'You are not Vendor !', 'sdweddingdirectory' ),

                            'redirect'          =>   false,

                        ] ) );
                    }
                }
            }

            /**
             *  Error
             *  -----
             */
            else{

                die( json_encode( [

                    'notice'            =>      absint( '0' ),

                    'message'           =>      esc_attr__( 'Security Error...', 'sdweddingdirectory' ),

                    'redirect'          =>      false,

                ] ) );

            } // main else
        }
    }  

    /**
     *  SDWeddingDirectory - Vendor Login & Registration Form
     *  ---------------------------------------------
     */
    SDWeddingDirectory_Vendor_Login_Register_Form_AJAX::get_instance();
}
