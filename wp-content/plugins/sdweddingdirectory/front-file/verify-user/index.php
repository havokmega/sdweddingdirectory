<?php
/**
 *  SDWeddingDirectory - User Verification
 *  ------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_User_Verification' ) && class_exists( 'SDWeddingDirectory_Front_End_Modules' ) ){

    /**
     *  SDWeddingDirectory - User Verification
     *  ------------------------------
     */
    class SDWeddingDirectory_User_Verification extends SDWeddingDirectory_Front_End_Modules{

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
             *  WordPress All function initialize
             *  ---------------------------------
             */
            add_action( 'init', [ $this, 'verify_user_to_setup_account' ], absint( '999' ) );
        }

        /**
         *  Verify User to Setup Account
         *  ----------------------------
         */
        public static function verify_user_to_setup_account(){

            /**
             *  Extract
             *  -------
             */
            extract( [

                'option_name'       =>      esc_attr( 'sdweddingdirectory_register_user_tokan' ),

            ] );

            /**
             *  Add Option
             *  ----------
             */
            add_option( $option_name, '', '', 'yes' );

            /**
             *  Get Verification Account Request in Address bar
             *  -----------------------------------------------
             */
            if( isset( $_GET[ 'sdweddingdirectory_user_token' ] ) && ! empty( $_GET[ 'sdweddingdirectory_user_token' ] ) && ! is_user_logged_in() ){

                /**
                 *  Current Tokan
                 *  -------------
                 */
                $current_tokan     =      get_option( $option_name );

                /**
                 *   Make sure is not current tokan 
                 *   ------------------------------
                 */
                if( $current_tokan === esc_attr( $_GET[ 'sdweddingdirectory_user_token' ] ) ){

                    /**
                     *  Redirection on Home Page
                     *  ------------------------
                     */
                    die( wp_redirect( home_url() ) );
                }

                /**
                 *  Update Verification Data in Database
                 *  ------------------------------------
                 */
                update_option( $option_name, $_GET[ 'sdweddingdirectory_user_token' ] );

                /**
                 *  User Args
                 *  ---------
                 */
                $user_query     =   new WP_User_Query( [

                                        'role__in'      =>  [ 'couple', 'vendor' ],

                                        'meta_query'    =>  [

                                            'relation'  =>  'AND',

                                            [   'key'       =>  esc_attr( 'sdweddingdirectory_user_token' ),

                                                'value'     =>  esc_attr( $_GET['sdweddingdirectory_user_token'] ),

                                                'compare'   =>  '='
                                            ],
                                        ]

                                    ] );

                /**
                 *  Have User Found ?
                 *  -----------------
                 */
                $_user_data     =   [];

                /**
                 *  Collection of user query
                 *  ------------------------
                 */
                if ( ! empty( $user_query->get_results() ) ) {

                    foreach ( $user_query->get_results() as $user ) {

                        /**
                         *  User ID Start Registration
                         *  --------------------------
                         */
                        self:: user_registration_process( $user->ID );
                    }
                }

                else{

                    /**
                     *  Redirection on Home Page
                     *  ------------------------
                     */
                    die( wp_redirect( home_url() ) );
                }
            }
        }

        /**
         *  User Registration Process
         *  -------------------------
         */
        public static function user_registration_process( $_user_id = 0 ){

            /**
             *  make sure user id not empty!
             *  ----------------------------
             */
            if( empty( $_user_id ) ){

                /**
                 *  Redirection on Home Page
                 *  ------------------------
                 */
                die( wp_redirect( home_url() ) );
            }

            /**
             *  User Token
             *  ----------
             */
            $_user_token            =   get_user_meta( $_user_id, sanitize_key( 'sdweddingdirectory_user_token' ), true );

            /**
             *  Verification Not Done!
             *  ----------------------
             */
            $_verify_user           =   get_user_meta( $_user_id, sanitize_key( 'sdweddingdirectory_user_verify' ), true );

            /**
             *  User Meta Store User Form Data
             *  ------------------------------
             */
            $_user_form_data        =   get_user_meta( $_user_id, sanitize_key( 'register_user_data' ), true );

            /**
             *  Make sure token verify perfect
             *  ------------------------------
             */
            if( $_verify_user === 'no' &&  ! empty( $_user_token ) ){

                /**
                 *  User Data
                 *  ---------
                 */
                $_user_data             =   get_user_by( sanitize_key( 'id' ), $_user_id );

                /**
                 *  User Role
                 *  ---------
                 */
                $user_roles             =   $_user_data->roles;

                /**
                 *  Is Couple ?
                 *  -----------
                 */
                if ( in_array( 'couple', $user_roles, true ) ){

                    /**
                     *  If user have form data store in database ?
                     *  ------------------------------------------
                     */
                    if( ! empty( $_user_form_data ) ){

                        /**
                         *  User Form Data
                         *  --------------
                         */
                        $_user_form_data        =   json_decode( stripslashes( $_user_form_data ), true );

                        /**
                         *  Make sure get details is collection of data
                         *  -------------------------------------------
                         */
                        if( parent:: _is_array( $_user_form_data ) && ! empty( $_user_form_data ) ){

                            /**
                             *  Extract 
                             *  -------
                             */
                            extract( $_user_form_data );

                            /**
                             *  Couple Registration - Core Configuration
                             *  ----------------------------------------
                             */
                            do_action( 'sdweddingdirectory/register/couple/configuration', $_user_form_data );

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
                                    'setting_id'        =>      esc_attr( 'couple-register' ),

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

                                                                    'couple_username'      =>  sanitize_user( $username ),

                                                                    'couple_email'         =>  sanitize_email( $user_email ),
                                                                )
                                ) );
                            }

                            /**
                             *  User Token
                             *  ----------
                             */
                            update_user_meta( $_user_id, sanitize_key( 'sdweddingdirectory_user_token' ), '' );

                            /**
                             *  Verification Not Done!
                             *  ----------------------
                             */
                            update_user_meta( $_user_id, sanitize_key( 'sdweddingdirectory_user_verify' ), esc_attr( 'yes' ) );

                            /**
                             *  User Meta Store User Form Data
                             *  ------------------------------
                             */
                            update_user_meta( $_user_id, sanitize_key( 'register_user_data' ), '' );

                            /**
                             *  User Login Time Update
                             *  ----------------------
                             */
                            update_user_meta( $_user_id, sanitize_key( 'sdweddingdirectory_user_last_login' ), date( 'Y-m-d H:i:s' ) );

                            /**
                             *  Login Process — use direct auth (no plaintext password needed)
                             *  --------------------------------------------------------------
                             */
                            $user = get_user_by( 'id', $_user_id );

                            if( ! empty( $user ) ) {

                                wp_set_current_user( $_user_id, $user->user_login );

                                wp_set_auth_cookie( $_user_id );

                                do_action( 'wp_login', $user->user_login, $user );
                            }

                            /**
                             *  Redirection to Couple Dashboard
                             *  -------------------------------
                             */
                            die( wp_redirect( apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'couple-dashboard' ) ) ) );
                        }
                    }

                    /**
                     *  Verify User
                     *  -----------
                     */
                    else{

                        /**
                         *  User Token
                         *  ----------
                         */
                        update_user_meta( $_user_id, sanitize_key( 'sdweddingdirectory_user_token' ), '' );

                        /**
                         *  Verification Not Done!
                         *  ----------------------
                         */
                        update_user_meta( $_user_id, sanitize_key( 'sdweddingdirectory_user_verify' ), esc_attr( 'yes' ) );

                        /**
                         *  User Meta Store User Form Data
                         *  ------------------------------
                         */
                        update_user_meta( $_user_id, sanitize_key( 'register_user_data' ), '' );

                        /**
                         *  User Login Time Update
                         *  ----------------------
                         */
                        update_user_meta( $_user_id, sanitize_key( 'sdweddingdirectory_user_last_login' ), date( 'Y-m-d H:i:s' ) );

                        /**
                         *  Redirection on Home Page
                         *  ------------------------
                         */
                        die( wp_redirect( esc_url_raw( add_query_arg(

                            /**
                             *  1. Query
                             *  --------
                             */
                            array(

                                'couple_login'      =>  esc_attr( 'sdweddingdirectory_couple_login_model_popup' )
                            ),

                            /**
                             *  2. Link
                             *  -------
                             */
                            esc_url( home_url( '/' ) )

                        ) ) ) );
                    }
                }

                /**
                 *  Is Vendor ?
                 *  -----------
                 */
                elseif ( in_array('vendor', $user_roles, true) ) {

                    /**
                     *  If user have form data store in database ?
                     *  ------------------------------------------
                     */
                    if( ! empty( $_user_form_data ) ){

                        /**
                         *  User Form Data
                         *  --------------
                         */
                        $_user_form_data        =   json_decode( stripslashes( $_user_form_data ), true );

                        /**
                         *  Make sure have data
                         *  -------------------
                         */
                        if( parent:: _is_array( $_user_form_data ) && ! empty( $_user_form_data ) ){

                            /**
                             *  Extract 
                             *  -------
                             */
                            extract( $_user_form_data );

                            /**
                             *  Couple Registration - Core Configuration
                             *  ----------------------------------------
                             */
                            do_action( 'sdweddingdirectory/register/vendor/configuration', $_user_form_data );

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
                                    'setting_id'        =>      esc_attr( 'vendor-register' ),

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

                                                                    'vendor_username'      =>  sanitize_user( $user_name ),

                                                                    'vendor_email'         =>  sanitize_email( $user_email ),
                                                                )
                                ) );
                            }

                            /**
                             *  User Token
                             *  ----------
                             */
                            update_user_meta( $_user_id, sanitize_key( 'sdweddingdirectory_user_token' ), '' );

                            /**
                             *  Verification Not Done!
                             *  ----------------------
                             */
                            update_user_meta( $_user_id, sanitize_key( 'sdweddingdirectory_user_verify' ), esc_attr( 'yes' ) );

                            /**
                             *  User Meta Store User Form Data
                             *  ------------------------------
                             */
                            update_user_meta( $_user_id, sanitize_key( 'register_user_data' ), '' );

                            /**
                             *  User Login Time Update
                             *  ----------------------
                             */
                            update_user_meta( $_user_id, sanitize_key( 'sdweddingdirectory_user_last_login' ), date( 'Y-m-d H:i:s' ) );

                            /**
                             *  Login Process — use direct auth (no plaintext password needed)
                             *  --------------------------------------------------------------
                             */
                            $user = get_user_by( 'id', $_user_id );

                            if( $user ) {

                                wp_set_current_user( $_user_id, $user->user_login );

                                wp_set_auth_cookie( $_user_id );

                                do_action( 'wp_login', $user->user_login, $user );
                            }

                            /**
                             *  Redirection to Vendor Dashboard
                             *  -------------------------------
                             */
                            die( wp_redirect(  apply_filters( 'sdweddingdirectory/vendor-menu/page-link', esc_attr( 'vendor-dashboard' ) )  ) );
                        }
                    }

                    /**
                     *  Verify user
                     *  -----------
                     */
                    else{

                        /**
                         *  User Token
                         *  ----------
                         */
                        update_user_meta( $_user_id, sanitize_key( 'sdweddingdirectory_user_token' ), '' );

                        /**
                         *  Verification Not Done!
                         *  ----------------------
                         */
                        update_user_meta( $_user_id, sanitize_key( 'sdweddingdirectory_user_verify' ), esc_attr( 'yes' ) );

                        /**
                         *  User Meta Store User Form Data
                         *  ------------------------------
                         */
                        update_user_meta( $_user_id, sanitize_key( 'register_user_data' ), '' );

                        /**
                         *  Redirection on Home Page
                         *  ------------------------
                         */
                        die( wp_redirect( esc_url_raw( add_query_arg(

                            /**
                             *  1. Query
                             *  --------
                             */
                            array(

                                'vendor_login'      =>  esc_attr( 'sdweddingdirectory_vendor_login_model_popup' )
                            ),

                            /**
                             *  2. Link
                             *  -------
                             */
                            esc_url( home_url( '/' ) )

                        ) ) ) );
                    }
                }

                /**
                 *  Redirection on Home Page
                 *  ------------------------
                 */
                else{

                    /**
                     *  Redirection on Home Page
                     *  ------------------------
                     */
                    die( wp_redirect( home_url() ) );
                }
            }
        }
    }  

    /**
     *  SDWeddingDirectory - User Verification
     *  ------------------------------
     */
    SDWeddingDirectory_User_Verification::get_instance();
}