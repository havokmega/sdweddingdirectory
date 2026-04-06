<?php
/**
 *  SDWeddingDirectory Couple My Profile AJAX Script Action HERE
 *  ----------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Profile_AJAX' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory Couple My Profile AJAX Script Action HERE
     *  ----------------------------------------------------
     */
    class SDWeddingDirectory_Couple_Profile_AJAX extends SDWeddingDirectory_Config{

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
                         *  1. Couple Profile Update
                         *  ------------------------
                         */
                        esc_attr( 'sdweddingdirectory_couple_profile_action' ),

                        /**
                         *  2. Couple Password Change
                         *  -------------------------
                         */
                        esc_attr( 'sdweddingdirectory_couple_password_change' ),

                        /**
                         *  3. Couple Social Profile
                         *  ------------------------
                         */
                        esc_attr( 'sdweddingdirectory_couple_social_profile_action' ),

                        /**
                         *  4. Couple Wedding Information
                         *  -----------------------------
                         */
                        esc_attr( 'sdweddingdirectory_couple_wedding_information' )
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
         *  1. Couple Profile
         *  -----------------
         */
        public static function sdweddingdirectory_couple_profile_action(){

            $_condition_1   = isset( $_POST['security'] ) && $_POST['security'] !== '';

            $_condition_2   = wp_verify_nonce( $_POST['security'], 'profile_update' );

            /**
             *  Couple Registration through AJAX action With Test Security
             *  ----------------------------------------------------------
             */
            if ( $_condition_1 && $_condition_2 ) {

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _is_array( $_POST ) ){

                    foreach( $_POST as $key => $value  ){

                        /**
                         *  Update Key + Value
                         *  ------------------
                         */
                        update_post_meta( absint( parent:: post_id() ), sanitize_key( $key ), $value );
                    }

                    /**
                     *  Post Content Update
                     *  -------------------
                     */
                    wp_update_post( array(

                        'ID'                =>  absint( parent:: post_id() ),

                        'post_content'      =>   $_POST['post_content'],

                    ) );

                    die( json_encode( array(

                        'notice'    =>  absint( '1' ),

                        'message'   =>  esc_attr__( 'Profile Updated Successfully', 'sdweddingdirectory' ),

                    ) ) );
                }

            }else{

                die( json_encode( array(

                    'notice'    =>  absint( '0' ),

                    'message'   =>  esc_attr__( 'Profile Updated Error... Please login again then update your profile.', 'sdweddingdirectory' )

                ) ) );
            }
        }

        /**
         *  2. Couple Password Change
         *  -------------------------
         */
        public static function sdweddingdirectory_couple_password_change(){

            $_condition_1   = isset( $_POST['security'] ) && $_POST['security'] !== '';

            $_condition_2   = wp_verify_nonce( $_POST['security'], 'change_password_security' );

            /**
             *  Security Check
             *  --------------
             */
            if( $_condition_1 && $_condition_2 ){

              $old_pwd        =   esc_attr( $_POST['old_pwd'] );

              $new_pwd        =   esc_attr( $_POST['new_pwd'] );

              $confirm_pwd    =   esc_attr( $_POST['confirm_pwd'] );

              /**
               *  Password Reset Process
               *  ----------------------
               */
              if( $new_pwd == '' || $confirm_pwd == '' ){

                    die( json_encode( array(

                      'redirect'  =>  false,

                      'notice'    =>  absint( '2' ),

                      'message'   =>  esc_attr__( 'The new password is blank', 'sdweddingdirectory' )

                    ) ) );
              }
               
              if( $new_pwd != $confirm_pwd ){

                die( json_encode( array( 

                    'redirect'  =>  false,

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
                     *  Logout User
                     *  -----------
                     */
                    wp_logout();

                    /**
                     *  AJAX End
                     *  --------
                     */
                    die( json_encode( array(

                        'redirect'          =>  true,

                        'redirect_link'     =>  esc_url( home_url( '/' ) ),

                        'notice'            =>  absint( '1' ),

                        'message'           =>  esc_attr__( 'Password Updated. Please login again.', 'sdweddingdirectory' )

                    ) ) );
                }

              }else{

                  die( json_encode( array(

                    'redirect'  =>  false,

                    'notice'    =>  absint( '2' ),

                    'message'   =>  esc_attr__( 'Old Password is not correct.', 'sdweddingdirectory' )

                  ) ) );
              }

            }else{

                die( json_encode( array(

                  'redirect'  =>  false,

                  'notice'    =>  absint( '0' ),

                  'message'   =>  esc_attr__( 'Security issue found!', 'sdweddingdirectory' )

                ) ) );
            }
        }

        /**
         *  3. Couple Social Profile
         *  ------------------------
         */
        public static function sdweddingdirectory_couple_social_profile_action(){

            $_condition_1   = isset( $_POST['security'] ) && $_POST['security'] !== '';

            $_condition_2   = wp_verify_nonce( $_POST['security'], 'social_media_update' );

            /**
             *  Security Check
             *  --------------
             */
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

                /**
                 *  Security Error
                 *  --------------
                 */
                die( json_encode( array(

                  'notice'    =>  absint( '0' ),

                  'message'   =>  esc_attr__( 'Security issue found!', 'sdweddingdirectory' )

                ) ) );
            }
        }

        /**
         *  6. Couple Wedding Information
         *  -----------------------------
         */
        public static function sdweddingdirectory_couple_wedding_information(){

            $_condition_1   = isset( $_POST['security'] ) && $_POST['security'] !== '';

            $_condition_2   = wp_verify_nonce( $_POST['security'], 'wedding_information' );

            /**
             *  Security Check
             *  --------------
             */
            if( $_condition_1 && $_condition_2 ){

                /**
                 *  Form Data
                 *  ---------
                 */
                $_form_data   = array(

                    'wedding_date'      =>  esc_attr( $_POST[ 'wedding_date' ] ),

                    'wedding_address'   =>  esc_attr( $_POST[ 'wedding_address' ] ),

                    'bride_first_name'  =>  esc_attr( $_POST[ 'bride_first_name' ] ),

                    'bride_last_name'   =>  esc_attr( $_POST[ 'bride_last_name' ] ),

                    'groom_first_name'  =>  esc_attr( $_POST[ 'groom_first_name' ] ),

                    'groom_last_name'   =>  esc_attr( $_POST[ 'groom_last_name' ] ),
                );

                if( parent:: _is_array( $_form_data ) ){

                    foreach( $_form_data as $key => $value  ){

                        /**
                         *  Update Wedding Information
                         *  --------------------------
                         */
                        update_post_meta( absint( parent:: post_id() ), sanitize_key( $key ), esc_attr( $value ) );
                    }

                    /**
                     *  Successfully updated
                     *  --------------------
                     */
                    die( json_encode( array( 

                        'notice'    =>  absint( '1' ),

                        'message'   =>  esc_attr__( 'Wedding Information Successfully Updated', 'sdweddingdirectory' )

                    ) ) );
                }

            }else{

                /**
                 *  Security Error
                 *  --------------
                 */
                die( json_encode( array(

                  'notice'    =>  absint( '0' ),

                  'message'   =>  esc_attr__( 'Security issue found!', 'sdweddingdirectory' )

                ) ) );
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Couple_Profile_AJAX:: get_instance();
}