<?php
/**
 *  SDWeddingDirectory - Couple Login & Registration Form
 *  ---------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Login_Register_Form_AJAX' ) && class_exists( 'SDWeddingDirectory_Front_End_Modules' ) ){

    /**
     *  SDWeddingDirectory - Couple Login & Registration Form
     *  ---------------------------------------------
     */
    class SDWeddingDirectory_Couple_Login_Register_Form_AJAX extends SDWeddingDirectory_Front_End_Modules {

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

            add_action( 'wp_ajax_sdweddingdirectory_couple_register_form_action', [ $this, 'sdweddingdirectory_couple_register_form_action' ] );
            add_action( 'wp_ajax_nopriv_sdweddingdirectory_couple_register_form_action', [ $this, 'sdweddingdirectory_couple_register_form_action' ] );
            add_action( 'wp_ajax_sdweddingdirectory_couple_login_form_action', [ $this, 'sdweddingdirectory_couple_login_form_action' ] );
            add_action( 'wp_ajax_nopriv_sdweddingdirectory_couple_login_form_action', [ $this, 'sdweddingdirectory_couple_login_form_action' ] );
        }

        /**
         *  4. Couple Registration through AJAX action
         *  ------------------------------------------
         */
        public static function sdweddingdirectory_couple_register_form_action(){

            /**
             *  Couple Registration through AJAX action With Test Security
             *  ----------------------------------------------------------
             */
            if( wp_verify_nonce( $_POST['security'], 'sdweddingdirectory_couple_registration_security' ) ){

                /**
                 *  Couple Registration Code Goes here
                 *  ----------------------------------
                 */
                $_couple_username       =     sanitize_user( $_POST[ 'sdweddingdirectory_couple_register_username' ] );

                $_couple_email_id       =     sanitize_email( $_POST[ 'sdweddingdirectory_couple_register_email' ] );

                $_couple_password       =     wp_unslash( $_POST[ 'sdweddingdirectory_couple_register_password' ] );

                $_couple_first_name     =     esc_attr( $_POST[ 'sdweddingdirectory_couple_register_first_name' ] );

                $_couple_last_name      =     esc_attr( $_POST[ 'sdweddingdirectory_couple_register_last_name' ] );

                $_couple_wedding_date   =     esc_attr( $_POST[ 'sdweddingdirectory_couple_register_wedding_date' ] );

                $_couple_person         =     esc_attr( $_POST[ 'sdweddingdirectory_register_couple_person' ] );

                /**
                 *  Check the Fields are valid ?
                 *  ----------------------------
                 */
                if( isset( $_couple_username ) && $_couple_username == '' ){

                    /**
                     *  Couple User Name is Required
                     *  ----------------------------
                     */
                    die( json_encode( array(

                        'message'       => esc_attr__( 'Username is Required!', 'sdweddingdirectory' ),

                        'notice'        => absint( '0' ),

                        'redirect'      =>  false,

                    ) ) );
                }

                /**
                 *  Username Exists ?
                 *  -----------------
                 */
                elseif( isset( $_couple_username ) && $_couple_username !== '' && username_exists( $_couple_username ) ){

                    /**
                     *  Couple User Name Exists
                     *  -----------------------
                     */
                    die( json_encode( array(

                        'message'       => esc_attr__( 'Username already exitsts!', 'sdweddingdirectory' ),

                        'notice'        => absint( '0' ),

                        'redirect'      =>  false,

                    ) ) );
                }

                /**
                 *  Password Not Empty!
                 *  -------------------
                 */
                elseif( isset( $_couple_password ) && $_couple_password == '' ){

                   /**
                    *  Couple Password is Empty ?
                    *  --------------------------
                    */
                    die( json_encode( array(

                        'message'       => esc_attr__( 'Password is Empty!', 'sdweddingdirectory' ),

                        'notice'        => absint( '0' ),

                        'redirect'      =>  false,

                    ) ) );
                }

                /**
                 *  First name not Empty!
                 *  ---------------------
                 */
                elseif( isset( $_couple_first_name  ) && $_couple_first_name  == '' ){

                    /**
                     *  First Name is Empty
                     *  -------------------
                     */
                    die( json_encode( array(

                        'message'       => esc_attr__( 'First Name Fields Empty', 'sdweddingdirectory' ),

                        'notice'        => absint( '0' ),

                        'redirect'      =>  false,

                    ) ) );
                }

                /**
                 *  Last Name
                 *  ---------
                 */
                elseif( isset( $_couple_last_name  ) && $_couple_last_name  == '' ){

                    /**
                     *  Last Name is Empty
                     *  -------------------
                     */
                    die( json_encode( array(

                        'message'       => esc_attr__( 'Last Name Fields Empty', 'sdweddingdirectory' ),

                        'notice'        => absint( '0' ),

                        'redirect'      =>  false,

                    ) ) );
                }

                /**
                 *  Couple Email Not Empty!
                 *  -----------------------
                 */
                elseif( isset( $_couple_email_id ) && $_couple_email_id == '' ){

                    /**
                     *  Email is Required
                     *  -----------------
                     */
                    die( json_encode( array(

                        'message'       => esc_attr__( 'Email is Required!', 'sdweddingdirectory' ),

                        'notice'        => absint( '0' ),

                        'redirect'      =>  false,

                    ) ) );
                }

                /**
                 *  Is Valid Email ?
                 *  ----------------
                 */
                elseif( isset( $_couple_email_id ) && $_couple_email_id !== '' && ! is_email( $_couple_email_id ) ){

                    /**
                     *  Is Email
                     *  --------
                     */
                    die( json_encode( array(

                        'message'       => esc_attr__( 'Is Not Email ID', 'sdweddingdirectory' ),

                        'notice'        => absint( '0' ),

                        'redirect'      =>  false,

                    ) ) );
                }

                /**
                 *  Email Exists ?
                 *  --------------
                 */
                elseif( is_email( $_couple_email_id ) && email_exists( $_couple_email_id ) ){

                    /**
                     *  Email Already Exists
                     *  --------------------
                     */
                    die( json_encode( array(

                        'message'       => esc_attr__( 'Email Already Exists', 'sdweddingdirectory' ),

                        'notice'        => absint( '0' ),

                        'redirect'      =>  false,

                    ) ) );
                }

                /**
                 *  Wedding Date not empty!
                 *  -----------------------
                 */
                elseif( isset( $_couple_wedding_date ) && $_couple_wedding_date == '' ){

                    /**
                     *  Wedding Date is Required
                     *  ------------------------
                     */
                    die( json_encode( array(

                        'message'       => esc_attr__( 'Wedding Date is Required!', 'sdweddingdirectory' ),

                        'notice'        => absint( '0' ),

                        'redirect'      =>  false,

                    ) ) );
                }

                /**
                 *  I get required details now
                 *  --------------------------
                 */
                else{

                    /**
                     *  Create Couple Here
                     *  ------------------
                     */
                    $user_login         =       wp_slash( $_couple_username );

                    $user_email         =       wp_slash( $_couple_email_id );

                    $user_pass          =       esc_attr( $_couple_password );

                    $role               =       esc_attr( 'couple' );

                    $first_name         =       esc_attr( $_couple_first_name );

                    $last_name          =       esc_attr( $_couple_last_name );

                    $userdata           =       compact( 'user_login', 'user_email', 'user_pass', 'role', 'first_name', 'last_name' );

                    /**
                     *
                     *  Couple Register as WordPress user in Database
                     *  ---------------------------------------------
                     *  @link  https://codex.wordpress.org/Function_Reference/wp_insert_user
                     *  --------------------------------------------------------------------
                     */
                    $_user_id               =   wp_insert_user( $userdata );

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
                     *  Collection
                     *  ----------
                     */
                    $_post_meta_collection  =   [

                        'user_id'           =>  absint( $_user_id ),

                        'username'          =>  esc_attr( $_couple_username ),

                        'first_name'        =>  esc_attr( $_couple_first_name ),

                        'last_name'         =>  esc_attr( $_couple_last_name ),

                        'user_email'        =>  sanitize_email( $_couple_email_id ),

                        'wedding_date'      =>  esc_attr( $_couple_wedding_date ),

                        'register_user_is'  =>  esc_attr( $_couple_person ),

                    ];

                    /**
                     *  Is Admin Created Couple via SDWeddingDirectory > Setting Page > Import Couple ?
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
                        do_action( 'sdweddingdirectory/register/couple/configuration', $_post_meta_collection );

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
                                'setting_id'        =>      esc_attr( 'couple-register' ),

                                /**
                                 *  2. Sending Email ID
                                 *  -------------------
                                 */
                                'sender_email'      =>      sanitize_email( $_couple_email_id ),

                                /**
                                 *  3. Email Data Key and Value as Setting Body Have {{...}} all
                                 *  ------------------------------------------------------------
                                 */
                                'email_data'        =>      array(

                                                                'couple_username'      =>  sanitize_user( $_couple_username ),

                                                                'couple_email'         =>  sanitize_email( $_couple_email_id ),
                                                            )
                            ) );
                        }

                        /**
                         *  Successfull Whole process with redirection on vendor dashboad with alert
                         *  ------------------------------------------------------------------------
                         */
                        die( json_encode( array(

                            'notice'          =>  absint( '1' ),

                            'message'         =>  esc_attr__( 'Your account is created successfully!', 'sdweddingdirectory' ),

                        ) ) );
                    }

                    /**
                     *  Normal Couple Popup Process Here
                     *  --------------------------------
                     */
                    else{

                        /**
                         *  Popup registrations should be instantly usable.
                         */
                        update_user_meta( $_user_id, sanitize_key( 'sdweddingdirectory_user_verify' ), esc_attr( 'yes' ) );

                        do_action( 'sdweddingdirectory/register/couple/configuration', $_post_meta_collection );

                        $_couple_post_id = apply_filters( 'sdweddingdirectory/couple/post-id', sanitize_email( $_couple_email_id ) );

                        /**
                         *  Fallback: make sure profile post exists for dashboard workflows.
                         */
                        if( empty( $_couple_post_id ) ){

                            $_couple_post_id = wp_insert_post( [

                                'post_author'       =>  absint( $_user_id ),

                                'post_name'         =>  sanitize_title( $_couple_username ),

                                'post_title'        =>  esc_attr( $_couple_username ),

                                'post_status'       =>  esc_attr( 'publish' ),

                                'post_type'         =>  esc_attr( 'couple' ),

                                'post_content'      =>  sprintf( esc_attr__( 'Welcome %1$s', 'sdweddingdirectory' ), esc_attr( $_couple_username ) ),
                            ] );

                            if( ! is_wp_error( $_couple_post_id ) && absint( $_couple_post_id ) > 0 ){

                                update_post_meta( $_couple_post_id, sanitize_key( 'user_id' ), absint( $_user_id ) );
                                update_post_meta( $_couple_post_id, sanitize_key( 'first_name' ), esc_attr( $_couple_first_name ) );
                                update_post_meta( $_couple_post_id, sanitize_key( 'last_name' ), esc_attr( $_couple_last_name ) );
                                update_post_meta( $_couple_post_id, sanitize_key( 'user_email' ), sanitize_email( $_couple_email_id ) );
                                update_post_meta( $_couple_post_id, sanitize_key( 'wedding_date' ), esc_attr( $_couple_wedding_date ) );
                                update_post_meta( $_couple_post_id, sanitize_key( 'register_user_is' ), esc_attr( $_couple_person ) );

                                do_action( 'sdweddingdirectory/user-register/couple', array_merge( $_post_meta_collection, [ 'post_id' => absint( $_couple_post_id ) ] ) );
                            }
                        }

                        $user = get_user_by( 'id', $_user_id );

                        if( ! empty( $user ) ){

                            wp_set_current_user( $_user_id, $user->user_login );

                            wp_set_auth_cookie( $_user_id );

                            do_action( 'wp_login', $user->user_login, $user );
                        }

                        update_user_meta( $_user_id, sanitize_key( 'sdweddingdirectory_user_last_login' ), date( 'Y-m-d H:i:s' ) );

                        if( class_exists( 'SDWeddingDirectory_Email' ) ){

                            SDWeddingDirectory_Email:: sending_email( array(

                                'setting_id'        =>      esc_attr( 'couple-register' ),

                                'sender_email'      =>      sanitize_email( $_couple_email_id ),

                                'email_data'        =>      array(

                                                                'couple_username'      =>  sanitize_user( $_couple_username ),

                                                                'couple_email'         =>  sanitize_email( $_couple_email_id ),
                                                            )
                            ) );
                        }

                        $_redirect_link = ! empty( $_POST['redirect_link'] )

                                        ? esc_url( $_POST['redirect_link'] )

                                        : esc_url( home_url( '/' ) );

                        die( json_encode( [

                            'notice'                =>      absint( '1' ),

                            'message'               =>      esc_attr__( 'Your account is created successfully!', 'sdweddingdirectory' ),

                            'redirect'              =>      true,

                            'modal_close'           =>      true,

                            'redirect_link'         =>      $_redirect_link

                        ] ) );
                    }

                } // else end
            }

            /**
             *  Error Found
             *  -----------
             */
            else{

                die( json_encode( [

                    'notice'            =>      absint( '0' ),

                    'message'           =>      esc_attr__( 'Couple Registration Error...', 'sdweddingdirectory' ),

                    'redirect'          =>      false,

                ] ) );
            }
        }

        /**
         *  5. SDWeddingDirectory - Couple Login AJAX action
         *  ----------------------------------------
         */
        public static function sdweddingdirectory_couple_login_form_action(){

            /**
             *  SDWeddingDirectory Security Check
             *  -------------------------
             */
            if( wp_verify_nonce( $_POST['security'], esc_attr( 'sdweddingdirectory_couple_login_security' ) ) ){

                /**
                 *  Login User Details Here
                 *  -----------------------
                 */
                $info                               =       [];

                $info['user_login']                 =       sanitize_user( $_POST[ 'sdweddingdirectory_couple_login_username' ] );

                $info['user_password']              =       wp_unslash( $_POST[ 'sdweddingdirectory_couple_login_password' ] );

                $info['remember']                   =       true;

                $user_signon                        =       wp_signon( $info, is_ssl() );

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
                 *  No Error from WP
                 *  ----------------
                 */
                else{

                    /**
                     *  User now login and they have access to get value
                     *  ------------------------------------------------
                     */
                    $user_roles                =    get_userdata( $user_signon->ID )->roles;

                    /**
                     *  Is Couple user ?
                     *  ----------------
                     */
                    if ( in_array( 'couple', $user_roles, true ) ){

                        /**
                         *  ------------------------
                         *  Set Current User Process
                         *  ------------------------
                         *  @link : https://developer.wordpress.org/reference/functions/wp_set_current_user/#user-contributed-notes
                         *  -------------------------------------------------------------------------------------------------------
                         */
                        global $current_user;

                        /**
                         *  User ID
                         *  -------
                         */
                        $_user_id    =   absint( $user_signon->ID );

                        /**
                         *  Is verify user ?
                         *  ----------------
                         */
                        $_is_verify_user    =   get_user_meta( $_user_id, sanitize_key( 'sdweddingdirectory_user_verify' ), true );

                        /**
                         *  Dev environment bypass: auto-verify users when SDWD_AUTO_VERIFY is defined.
                         */
                        if( defined( 'SDWD_AUTO_VERIFY' ) && SDWD_AUTO_VERIFY && ( empty( $_is_verify_user ) || $_is_verify_user === 'no' ) ){

                            update_user_meta( $_user_id, sanitize_key( 'sdweddingdirectory_user_verify' ), esc_attr( 'yes' ) );

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

                                'modal_close'           =>      false,

                                'redirect_link'         =>      esc_url( $_POST[ 'redirect_link' ] ),

                                'button_string'         =>      esc_attr__( 'Check Your Email', 'sdweddingdirectory' ),

                                'button_class'          =>      'pe-none disabled fw-bold'

                            ] ) );
                        }

                        /**
                         *  Verification Done ?
                         *  -------------------
                         */
                        elseif( $_is_verify_user == esc_attr( 'yes' ) ){

                            /**
                             *  User
                             *  ----
                             */
                            $user       =   get_user_by( 'id', $_user_id ); 

                            /**
                             *  Make sure user is not empty!
                             *  ----------------------------
                             */
                            if( ! empty( $user ) ){

                                wp_set_current_user( $_user_id, $user->user_login );

                                wp_set_auth_cookie( $_user_id );

                                do_action( 'wp_login', $user->user_login, $user );
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
                                die( json_encode( [

                                      'message'         =>      $user_signon->get_error_message(),

                                      'notice'          =>      absint( '3' ),

                                      'redirect'        =>      false,

                                      'redirect_link'   =>      esc_url( home_url( '/' ) ),

                                ] ) );
                            }

                            /**
                             *  No Error Found Perfect for login
                             *  --------------------------------
                             */
                            else{

                                /**
                                 *  User Login Time Update
                                 *  ----------------------
                                 */
                                update_user_meta( $_user_id, sanitize_key( 'sdweddingdirectory_user_last_login' ), date( 'Y-m-d H:i:s' ) );

                                /**
                                 *  Successfull Whole process with redirection on vendor dashboad with alert
                                 *  ------------------------------------------------------------------------
                                 */
                                die( json_encode( [

                                    'notice'                =>      absint( '1' ),

                                    'message'               =>      esc_attr__( 'Login successfully!', 'sdweddingdirectory' ),

                                    'redirect'              =>      true,

                                    'modal_close'           =>      true,

                                    'redirect_link'         =>      esc_url( $_POST[ 'redirect_link' ] )

                                ] ) );
                            }
                        }

                        /**
                         *  Not Verify User
                         *  ---------------
                         */
                        else{

                            /**
                             *  Logout
                             *  ------
                             */
                            wp_logout();

                            /**
                             *  Is WP Error ?
                             *  -------------
                             */
                            die( json_encode( [

                                'notice'            =>      absint( '0' ),

                                'message'           =>      esc_attr__( 'Login Fail !', 'sdweddingdirectory' ),

                                'redirect'          =>      false,

                            ] ) );
                        }
                    }

                    /**
                     *  Is Not Couple
                     *  -------------
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

                            'notice'            =>      absint( '0' ),

                            'message'           =>      esc_attr__( 'You are not Couple !', 'sdweddingdirectory' ),

                            'redirect'          =>      false,

                        ] ) );
                    }
                }
            }

            /**
             *  Security Issue
             *  --------------
             */
            else {

                die( json_encode( [

                    'notice'            =>      absint( '0' ),

                    'message'           =>      esc_attr__( 'Security Issue Found!', 'sdweddingdirectory' ),

                    'redirect'          =>      false,

                ] ) );

            } // main else
        }
    }

    /**
     *  SDWeddingDirectory - Couple Login & Registration Form
     *  ---------------------------------------------
     */
    SDWeddingDirectory_Couple_Login_Register_Form_AJAX::get_instance();
}
