<?php
/**
 *  SDWeddingDirectory - Forgot Password
 *  ----------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Forgot_Password_Form' ) && class_exists( 'SDWeddingDirectory_Front_End_Modules' ) ){

    /**
     *  SDWeddingDirectory - Forgot Password
     *  ----------------------------
     */
    class SDWeddingDirectory_Forgot_Password_Form extends SDWeddingDirectory_Front_End_Modules {

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
             *  1. Load Script for couple registration
             *  --------------------------------------
             */
            add_action( 'wp_enqueue_scripts', array( $this, 'sdweddingdirectory_script' ) );

            /**
             *  2. SDWeddingDirectory Login Model Popup
             *  -------------------------------
             */
            add_action( 'wp_footer', [ $this, 'forgot_password_model_popup' ] );

            /**
             *  3. Register Popup ID
             *  --------------------
             */
            add_filter( 'sdweddingdirectory/model-popup', function( $args = [] ){

                return      array_merge( $args, array(

                                [
                                    'slug'              =>      esc_attr( 'forgot_password' ),

                                    'modal_id'          =>      esc_attr( 'sdweddingdirectory_forgot_password_model_popup' ),

                                    'redirect_link'     =>      '',

                                    'name'              =>      esc_attr__( 'Forgot Password Modal Popup', 'sdweddingdirectory' )
                                ]

                            ) );

            }, absint( '30' ), absint( '1' ) );

            /**
             *  4. Handle reset token from URL
             *  ------------------------------
             */
            add_action( 'init', [ $this, 'handle_reset_token' ], absint( '20' ) );

            /**
             *  5. Have AJAX action ?
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
                         *  1. Forgot Password AJAX Action
                         *  ------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_forgot_password_form_action' ),

                        /**
                         *  2. Reset Password AJAX Action
                         *  -----------------------------
                         */
                        esc_attr( 'sdweddingdirectory_reset_password_form_action' )
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
         *  1. Load the couple registration script.
         *  --------------------------------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Make sure user not login
             *  ------------------------
             */
            if( ! is_user_logged_in() ){

                /**
                 *  Load Script
                 *  -----------
                 */
                wp_enqueue_script(

                    /**
                     *  1. File Name
                     *  ------------
                     */
                    esc_attr( sanitize_title( __CLASS__ ) ),

                    /**
                     *  2. File Path
                     *  ------------
                     */
                    esc_url(   plugin_dir_url( __FILE__ )   .   'script.js'   ),

                    /**
                     *  3. Load After "JQUERY" Load
                     *  ---------------------------
                     */
                    array( 'jquery', 'toastr' ),

                    /**
                     *  4. Bootstrap - Library Version
                     *  ------------------------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ )   .   'script.js' ) ),

                    /**
                     *  5. Load in Footer
                     *  -----------------
                     */
                    true
                );
            }
        }

        /**
         *  2. SDWeddingDirectory - Forgot Password Model Popup
         *  --------------------------------------------
         */
        public static function forgot_password_model_popup(){

            if( ! is_user_logged_in() ){

                printf( '<!-- Forgot Password Modal Popup -->
                        <div class="modal fade" id="%1$s" tabindex="-1" aria-labelledby="%1$s" aria-hidden="true">',

                    /**
                     *  1. Forgot Password Popup ID
                     *  ---------------------------
                     */
                    esc_attr( parent:: popup_id( 'forgot_password' ) )
                );

                ?>
                    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <div class="">
                                    <div class="row g-0">

                                        <!-- col-md-5 -->
                                        <div class="col-lg-5 d-none d-lg-block d-xl-block sidebar-img"
                                            <?php

                                                /**
                                                 *  Background Image
                                                 *  ----------------
                                                 */
                                                printf( 'style="background: url(%1$s) no-repeat center;background-size: cover;"',

                                                    esc_url(  parent:: placeholder( 'forgot-password-popup' ) )
                                                );

                                            ?>
                                        >
                                        </div>
                                        <!-- / col-md-5 -->

                                        <!-- col-md-7 -->
                                        <div class="col-lg-7 col-md-12 col-12">

                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>

                                            <div class="p-5">
                                                <?php

                                                    printf( '<h3>%1$s</h3><p class="mb-0">%2$s</p>',

                                                        /**
                                                         *  1. Translation Ready String
                                                         *  ---------------------------
                                                         */
                                                        esc_attr__( 'Lost Password', 'sdweddingdirectory' ),

                                                        /**
                                                         *  2. Translation Ready String
                                                         *  ---------------------------
                                                         */
                                                        esc_attr__( 'Follow these simple steps to reset your account', 'sdweddingdirectory' )
                                                    );
                                                ?>
                                            </div>
                                            <hr/>


                                            <!-- login section -->
                                            <div class="login-sidebar-pad text-center">

                                                <div class="or-text">

                                                    <?php esc_attr_e( 'Enter your email to recover your account', 'sdweddingdirectory' ); ?>

                                                </div>

                                                <?php $required = esc_attr( 'required' ); ?>

                                                <form id="sdweddingdirectory-forgot-password-form" method="post">
                                                    <div class="row">

                                                        <?php

                                                        /**
                                                         *  Email ID
                                                         *  --------
                                                         */
                                                        printf(  '<div class="col-12"><div class="mb-3">

                                                                    <label class="control-label sr-only" for="%1$s"></label>

                                                                    <input autocomplete="off" id="%1$s" type="email" name="%1$s" placeholder="%2$s" class="form-control" %3$s />

                                                                  </div></div>',

                                                                  /**
                                                                   *  1. Name
                                                                   *  -------
                                                                   */
                                                                  esc_attr( 'sdweddingdirectory_forgot_password_email' ),

                                                                  /**
                                                                   *  2. Placeholder
                                                                   *  --------------
                                                                   */
                                                                  esc_attr__('Email ID','sdweddingdirectory'),

                                                                  /**
                                                                   *  3. Required Fields
                                                                   *  ------------------
                                                                   */
                                                                  $required
                                                        );

                                                        /**
                                                         *  Submit
                                                         *  ------
                                                         */
                                                        printf(  '<div class="col-12"><div class="mb-3 d-grid">

                                                                    <button type="submit" name="%1$s" id="%1$s" class="loader btn btn-default btn-rounded mt-3 btn-block">%2$s</button>

                                                                    %3$s <!-- security -->

                                                                  </div></div>',

                                                                  /**
                                                                   *  1. Form Button ID
                                                                   *  -----------------
                                                                   */
                                                                  esc_attr( 'sdweddingdirectory_forgot_password_form' ),

                                                                  /**
                                                                   *  2. Form Button Text
                                                                   *  -------------------
                                                                   */
                                                                  esc_attr__( 'Send Reset Link', 'sdweddingdirectory' ),

                                                                  /**
                                                                   *  3. Vendor Registration Form Security
                                                                   *  ------------------------------------
                                                                   */
                                                                  wp_nonce_field( 'sdweddingdirectory_forgot_password_security', 'sdweddingdirectory_forgot_password_security', true, false )
                                                        );

                                                        ?>

                                                    </div>
                                                </form>

                                            </div>
                                            <!-- / login section -->

                                            <div class="login-footer">
                                                <?php

                                                    printf( '<div class="or-text mb-1">

                                                                <div>%1$s</div>

                                                            </div>

                                                            <a class="btn-link-default" %2$s>%3$s</a>',

                                                            /**
                                                             *  1. Translation Ready String
                                                             *  ---------------------------
                                                             */
                                                            esc_attr__( 'Are you a vendor ?', 'sdweddingdirectory' ),

                                                            /**
                                                             *  2. Vendor Login Model ID
                                                             *  ------------------------
                                                             */
                                                            apply_filters( 'sdweddingdirectory/vendor-login/attr', '' ),

                                                            /**
                                                             *  3. Translation Ready String
                                                             *  ---------------------------
                                                             */
                                                            esc_attr__( 'Vendor login', 'sdweddingdirectory' )
                                                    );

                                                ?>
                                            </div>

                                        </div>
                                        <!-- / col-md-7 -->

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php

                print '</div><!-- Forgot Password Modal Popup -->';
            }
        }

        /**
         *  3. SDWeddingDirectory - Forgot Password AJAX action
         *  ---------------------------------------------------
         *  Sends a time-limited reset link instead of a plaintext password.
         */
        public static function sdweddingdirectory_forgot_password_form_action(){

            /**
             *  1. Security not empty!
             *  ----------------------
             */
            $_condition_1   =   isset( $_POST['security'] ) && $_POST['security'] !== '';

            /**
             *  2. Check Verify Security
             *  ------------------------
             */
            $_condition_2   =   wp_verify_nonce( $_POST['security'], esc_attr( 'sdweddingdirectory_forgot_password_security' ) );

            /**
             *  3. Have Email ?
             *  ---------------
             */
            $user_email     =   sanitize_email( $_POST['email'] );

            /**
             *  SDWeddingDirectory Security Check
             *  -------------------------
             */
            if (  $_condition_1 && $_condition_2  ) {

                /**
                 *  Make sure email id exist on website
                 *  -----------------------------------
                 */
                if ( email_exists( $user_email ) != false && parent:: _have_data( $user_email ) && is_email( $user_email ) ) {

                    $userData           =   get_user_by( 'email', $user_email );
                    $userID             =   $userData->ID;

                    /**
                     *  Only allow couple and vendor roles to reset.
                     */
                    $user_roles = (array) $userData->roles;
                    if ( ! array_intersect( $user_roles, [ 'couple', 'vendor' ] ) ) {

                        die( json_encode( array(

                            'notice'    =>  absint( '0' ),

                            'message'   =>  esc_html__( 'Password reset is not available for this account type.', 'sdweddingdirectory' )

                        ) ) );
                    }

                    /**
                     *  Rate limit: one reset request per 5 minutes.
                     */
                    $last_request = absint( get_user_meta( $userID, sanitize_key( 'sdwd_password_reset_requested_at' ), true ) );

                    if ( $last_request > 0 && ( time() - $last_request ) < 300 ) {

                        die( json_encode( array(

                            'notice'    =>  absint( '0' ),

                            'message'   =>  esc_html__( 'A reset link was already sent. Please check your email or try again in a few minutes.', 'sdweddingdirectory' )

                        ) ) );
                    }

                    /**
                     *  Generate secure token and store with 1-hour expiry.
                     */
                    $token      =   wp_generate_password( 32, false );
                    $token_hash =   wp_hash_password( $token );

                    update_user_meta( $userID, sanitize_key( 'sdwd_password_reset_token' ), $token_hash );
                    update_user_meta( $userID, sanitize_key( 'sdwd_password_reset_expires' ), time() + 3600 );
                    update_user_meta( $userID, sanitize_key( 'sdwd_password_reset_requested_at' ), time() );

                    /**
                     *  Build reset link.
                     */
                    $reset_link =   add_query_arg( [

                        'sdwd_reset_token'  =>  $token,
                        'sdwd_reset_email'  =>  rawurlencode( $user_email ),

                    ], home_url( '/' ) );

                    /**
                     *  Send email with reset link.
                     */
                    $site_name  = esc_attr( get_bloginfo( 'name' ) );
                    $subject    = sprintf( esc_attr__( 'Password Reset — %s', 'sdweddingdirectory' ), $site_name );
                    $message    = sprintf(
                        esc_html__( "Hi %s,\n\nYou requested a password reset. Click the link below to choose a new password:\n\n%s\n\nThis link expires in 1 hour. If you did not request this, you can ignore this email.\n\n— %s", 'sdweddingdirectory' ),
                        esc_html( $userData->display_name ),
                        esc_url( $reset_link ),
                        $site_name
                    );

                    $headers    = [
                        'Content-Type: text/plain; charset=UTF-8',
                        'From: ' . $site_name . ' <noreply@' . wp_parse_url( home_url(), PHP_URL_HOST ) . '>',
                    ];

                    wp_mail( $user_email, $subject, $message, $headers );

                    /**
                    *  Success response — same message whether email exists or not
                    *  to prevent email enumeration.
                    */
                    die( json_encode( array(

                        'notice'    =>  absint( '1' ),

                        'message'   =>  esc_attr__( 'If an account exists with that email, a password reset link has been sent. Please check your inbox.', 'sdweddingdirectory' )

                    ) ) );

                }else{

                    /**
                     *  Return same message to prevent email enumeration.
                     */
                    die( json_encode( array(

                        'notice'    =>  absint( '1' ),

                        'message'   =>  esc_attr__( 'If an account exists with that email, a password reset link has been sent. Please check your inbox.', 'sdweddingdirectory' )

                    ) ) );
                }

            } else {

                die( json_encode( array(

                    'notice'    =>  absint( '0' ),

                    'message'   =>  esc_attr__( 'SDWeddingDirectory Forgot Password Form Error...', 'sdweddingdirectory' ),

                    'redirect'  =>  false,

                ) ) );

            } // main else
        }

        /**
         *  4. Handle reset token from URL (?sdwd_reset_token=...&sdwd_reset_email=...)
         *  ---------------------------------------------------------------------------
         */
        public static function handle_reset_token(){

            if ( ! isset( $_GET['sdwd_reset_token'] ) || ! isset( $_GET['sdwd_reset_email'] ) ) {
                return;
            }

            $token      =   sanitize_text_field( wp_unslash( $_GET['sdwd_reset_token'] ) );
            $email      =   sanitize_email( wp_unslash( $_GET['sdwd_reset_email'] ) );

            if ( empty( $token ) || empty( $email ) ) {
                return;
            }

            $user = get_user_by( 'email', $email );

            if ( empty( $user ) ) {
                wp_die( esc_html__( 'Invalid or expired reset link.', 'sdweddingdirectory' ), esc_html__( 'Password Reset', 'sdweddingdirectory' ), [ 'response' => 403 ] );
            }

            /**
             *  Only allow couple and vendor roles.
             */
            $user_roles = (array) $user->roles;
            if ( ! array_intersect( $user_roles, [ 'couple', 'vendor' ] ) ) {
                wp_die( esc_html__( 'Password reset is not available for this account type.', 'sdweddingdirectory' ), esc_html__( 'Password Reset', 'sdweddingdirectory' ), [ 'response' => 403 ] );
            }

            $stored_hash    =   get_user_meta( $user->ID, sanitize_key( 'sdwd_password_reset_token' ), true );
            $expires        =   absint( get_user_meta( $user->ID, sanitize_key( 'sdwd_password_reset_expires' ), true ) );

            if ( empty( $stored_hash ) || empty( $expires ) ) {
                wp_die( esc_html__( 'Invalid or expired reset link.', 'sdweddingdirectory' ), esc_html__( 'Password Reset', 'sdweddingdirectory' ), [ 'response' => 403 ] );
            }

            if ( time() > $expires ) {
                delete_user_meta( $user->ID, sanitize_key( 'sdwd_password_reset_token' ) );
                delete_user_meta( $user->ID, sanitize_key( 'sdwd_password_reset_expires' ) );
                wp_die( esc_html__( 'This reset link has expired. Please request a new one.', 'sdweddingdirectory' ), esc_html__( 'Password Reset', 'sdweddingdirectory' ), [ 'response' => 403 ] );
            }

            if ( ! wp_check_password( $token, $stored_hash ) ) {
                wp_die( esc_html__( 'Invalid or expired reset link.', 'sdweddingdirectory' ), esc_html__( 'Password Reset', 'sdweddingdirectory' ), [ 'response' => 403 ] );
            }

            /**
             *  Token is valid — show the password reset form.
             */
            self:: render_reset_form( $user, $token, $email );
            exit;
        }

        /**
         *  Render the password reset form page.
         */
        private static function render_reset_form( $user, $token, $email ){

            $site_name = esc_attr( get_bloginfo( 'name' ) );

            ?><!DOCTYPE html>
            <html <?php language_attributes(); ?>>
            <head>
                <meta charset="<?php bloginfo( 'charset' ); ?>">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title><?php echo esc_html( sprintf( __( 'Reset Password — %s', 'sdweddingdirectory' ), $site_name ) ); ?></title>
                <?php wp_head(); ?>
                <style>
                    .sdwd-reset-wrap { max-width: 440px; margin: 80px auto; padding: 40px; background: #fff; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,.08); font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
                    .sdwd-reset-wrap h2 { margin: 0 0 8px; font-size: 22px; }
                    .sdwd-reset-wrap p.subtitle { color: #666; margin: 0 0 24px; font-size: 14px; }
                    .sdwd-reset-wrap label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 14px; }
                    .sdwd-reset-wrap input[type="password"] { width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 15px; margin-bottom: 16px; box-sizing: border-box; }
                    .sdwd-reset-wrap button { width: 100%; padding: 12px; background: #c9a96e; color: #fff; border: none; border-radius: 4px; font-size: 15px; cursor: pointer; font-weight: 600; }
                    .sdwd-reset-wrap button:hover { background: #b8923d; }
                    .sdwd-reset-wrap .sdwd-reset-error { background: #fef2f2; color: #991b1b; padding: 10px 14px; border-radius: 4px; margin-bottom: 16px; font-size: 14px; }
                    .sdwd-reset-wrap .sdwd-reset-success { background: #f0fdf4; color: #166534; padding: 10px 14px; border-radius: 4px; margin-bottom: 16px; font-size: 14px; }
                </style>
            </head>
            <body style="background: #f5f5f5; margin: 0;">
                <div class="sdwd-reset-wrap">
                    <?php

                    /**
                     *  Handle form submission.
                     */
                    if ( isset( $_POST['sdwd_reset_password_submit'] ) ) {

                        $nonce_valid = wp_verify_nonce( $_POST['sdwd_reset_nonce'] ?? '', 'sdwd_reset_password_action' );

                        $new_pass    = wp_unslash( $_POST['sdwd_new_password'] ?? '' );
                        $confirm     = wp_unslash( $_POST['sdwd_confirm_password'] ?? '' );

                        if ( ! $nonce_valid ) {
                            echo '<div class="sdwd-reset-error">' . esc_html__( 'Security check failed. Please try again.', 'sdweddingdirectory' ) . '</div>';
                        } elseif ( strlen( $new_pass ) < 8 ) {
                            echo '<div class="sdwd-reset-error">' . esc_html__( 'Password must be at least 8 characters.', 'sdweddingdirectory' ) . '</div>';
                        } elseif ( $new_pass !== $confirm ) {
                            echo '<div class="sdwd-reset-error">' . esc_html__( 'Passwords do not match.', 'sdweddingdirectory' ) . '</div>';
                        } else {
                            /**
                             *  Re-verify the token before changing the password.
                             */
                            $stored_hash = get_user_meta( $user->ID, sanitize_key( 'sdwd_password_reset_token' ), true );
                            $expires     = absint( get_user_meta( $user->ID, sanitize_key( 'sdwd_password_reset_expires' ), true ) );

                            if ( empty( $stored_hash ) || time() > $expires || ! wp_check_password( $token, $stored_hash ) ) {
                                echo '<div class="sdwd-reset-error">' . esc_html__( 'This reset link has expired. Please request a new one.', 'sdweddingdirectory' ) . '</div>';
                            } else {
                                wp_set_password( $new_pass, $user->ID );

                                delete_user_meta( $user->ID, sanitize_key( 'sdwd_password_reset_token' ) );
                                delete_user_meta( $user->ID, sanitize_key( 'sdwd_password_reset_expires' ) );
                                delete_user_meta( $user->ID, sanitize_key( 'sdwd_password_reset_requested_at' ) );

                                echo '<div class="sdwd-reset-success">' . esc_html__( 'Your password has been reset successfully!', 'sdweddingdirectory' ) . '</div>';
                                echo '<p style="text-align:center;"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Return to site and log in', 'sdweddingdirectory' ) . '</a></p>';
                                wp_footer();
                                echo '</div></body></html>';
                                return;
                            }
                        }
                    }

                    ?>
                    <h2><?php esc_html_e( 'Reset Your Password', 'sdweddingdirectory' ); ?></h2>
                    <p class="subtitle"><?php esc_html_e( 'Enter a new password for your account.', 'sdweddingdirectory' ); ?></p>

                    <form method="post">
                        <?php wp_nonce_field( 'sdwd_reset_password_action', 'sdwd_reset_nonce' ); ?>

                        <label for="sdwd_new_password"><?php esc_html_e( 'New Password', 'sdweddingdirectory' ); ?></label>
                        <input type="password" id="sdwd_new_password" name="sdwd_new_password" required minlength="8" autocomplete="new-password" />

                        <label for="sdwd_confirm_password"><?php esc_html_e( 'Confirm Password', 'sdweddingdirectory' ); ?></label>
                        <input type="password" id="sdwd_confirm_password" name="sdwd_confirm_password" required minlength="8" autocomplete="new-password" />

                        <button type="submit" name="sdwd_reset_password_submit" value="1"><?php esc_html_e( 'Reset Password', 'sdweddingdirectory' ); ?></button>
                    </form>
                </div>
                <?php wp_footer(); ?>
            </body>
            </html><?php
        }
    }

    /**
     *  SDWeddingDirectory - Forgot Password
     *  ----------------------------
     */
    SDWeddingDirectory_Forgot_Password_Form::get_instance();
}
