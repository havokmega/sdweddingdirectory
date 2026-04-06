<?php
/**
 *  SDWeddingDirectory - Couple Login & Registration Form
 *  ---------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Login_Register_Form' ) && class_exists( 'SDWeddingDirectory_Front_End_Modules' ) ){

    /**
     *  SDWeddingDirectory - Couple Login & Registration Form
     *  ---------------------------------------------
     */
    class SDWeddingDirectory_Couple_Login_Register_Form extends SDWeddingDirectory_Front_End_Modules {

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
             *  Have AJAX ?
             *  -----------
             */
            require_once    'ajax.php';

            /**
             *  1. Load Script for couple registration
             *  --------------------------------------
             */
            add_action( 'wp_enqueue_scripts', [ $this, 'sdweddingdirectory_script' ] );

            /**
             *  2. SDWeddingDirectory Login Model Popup
             *  -------------------------------
             */
            add_action( 'wp_footer', [ $this, 'couple_login_model_popup' ] );

            /**
             *  3. SDWeddingDirectory Register Model Popup
             *  ----------------------------------
             */
            add_action( 'wp_footer', [ $this, 'couple_register_model_popup' ] );

            /**
             *  4. Register Popup ID
             *  --------------------
             */
            add_filter( 'sdweddingdirectory/model-popup', [ $this, 'register_model' ], absint( '10' ), absint( '1' ) );

            /**
             *  6. SDWeddingDirectory - Couple Login Button Attr
             *  ----------------------------------------
             */
            add_filter( 'sdweddingdirectory/couple-login/attr', [$this, 'couple_login_button_attr' ], absint( '10' ), absint( '1' ) );

            /**
             *  7. SDWeddingDirectory - Couple Register Button Attr
             *  -------------------------------------------
             */
            add_filter( 'sdweddingdirectory/couple-register/attr', [$this, 'couple_register_button_attr' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  1. Load the couple registration script.
         *  --------------------------------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  User login
             *  ----------
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
                    array( 'jquery' ),

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
         *  2. SDWeddingDirectory - Couple Login Model Popup
         *  ----------------------------------------
         */
        public static function couple_login_model_popup(){

            /**
             *  Make sure user not login to enable popup
             *  ----------------------------------------
             */
            if( ! is_user_logged_in() ){

                /**
                 *  Modal ID
                 *  --------
                 */
                $modal_id       =       esc_attr( 'couple_login' );

                /**
                 *  Parent Div
                 *  ----------
                 */
                printf( '<div class="modal fade" id="%1$s" tabindex="-1" aria-labelledby="%1$s" aria-hidden="true">',

                        /**
                         *  1. Couple Register Popup ID
                         *  ---------------------------
                         */
                        esc_attr( parent:: popup_id( $modal_id ) )
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

                                                    esc_url(  parent:: placeholder( 'couple-login-register-popup' ) )
                                                );

                                            ?>
                                        >
                                        </div>
                                        <!-- / col-md-5 -->
                                        
                                        <!-- col-md-7 -->
                                        <div class="col-lg-7 col-md-12 col-12">

                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>

                                            <!-- login section -->
                                            <div class="login-sidebar-pad text-center">

                                                <?php

                                                    printf( '<h3>%1$s</h3>',

                                                        /**
                                                         *  1. Translation Ready String
                                                         *  ---------------------------
                                                         */
                                                        esc_attr__( 'Log in Couple account', 'sdweddingdirectory' )
                                                    );

                                                    printf( '%1$s <a class="btn-link-primary" %4$s>%2$s</a>',

                                                        /**
                                                         *  1. Translation Ready String
                                                         *  ---------------------------
                                                         */
                                                        esc_attr__( 'Not a member yet ?', 'sdweddingdirectory' ),

                                                        /**
                                                         *  2. Translation Ready String
                                                         *  ---------------------------
                                                         */
                                                        esc_attr__( 'Join now', 'sdweddingdirectory' ),

                                                        /**
                                                         *  3. Link
                                                         *  -------
                                                         */
                                                        esc_url( home_url( '/' ) ),

                                                        /**
                                                         *  4. Couple Register Model ID
                                                         *  ---------------------------
                                                         */
                                                        apply_filters( 'sdweddingdirectory/couple-register/attr', '' )
                                                    );

                                                    ?><div class="mb-5"></div><?php

                                                ?>

                                                <form id="sdweddingdirectory-couple-login-form" method="post">

                                                    <div class="row row-cols-1">
                                                    <?php

                                                        parent:: call_field_type( [

                                                            'field_type'    =>      esc_attr( 'input' ),

                                                            'echo'          =>      true,
                                                                    
                                                            'id'            =>      esc_attr( 'sdweddingdirectory_couple_login_username' ),

                                                            'name'          =>      esc_attr( 'sdweddingdirectory_couple_login_username' ),

                                                            'placeholder'   =>      esc_attr__('Username or email address','sdweddingdirectory' ),

                                                            'require'       =>      true,

                                                        ] );

                                                        parent:: call_field_type( [

                                                            'field_type'    =>      esc_attr( 'password' ),

                                                            'echo'          =>      true,
                                                                    
                                                            'id'            =>      esc_attr( 'sdweddingdirectory_couple_login_password' ),

                                                            'name'          =>      esc_attr( 'sdweddingdirectory_couple_login_password' ),

                                                            'placeholder'   =>      esc_attr__( 'Password','sdweddingdirectory' ),

                                                            'type'          =>      esc_attr( 'password' ),

                                                            'require'       =>      true,

                                                        ] );

                                                        /**
                                                         *  Forgot Password ?
                                                         *  -----------------
                                                         */
                                                        printf(  '<div class="col">

                                                                    <a class="btn-link-primary" href="javascript:" role="button" data-bs-toggle="modal" data-bs-target="#%2$s" data-bs-dismiss="modal">%1$s</a>

                                                                  </div>',

                                                                  /**
                                                                   *  1. Translation Ready String
                                                                   *  ---------------------------
                                                                   */
                                                                  esc_attr__( 'Forgot your password ?', 'sdweddingdirectory' ),

                                                                  /**
                                                                   *  2. Forgot Password Model ID
                                                                   *  ---------------------------
                                                                   */
                                                                  esc_attr( parent:: popup_id( 'forgot_password' ) )
                                                        );

                                                        /**
                                                         *  Login
                                                         *  -----
                                                         */
                                                        printf(  '<div class="col-12">

                                                                    <div class="mb-3 d-grid">

                                                                        <button type="submit" 

                                                                            data-message-wait="%4$s"

                                                                            name="%1$s" id="%1$s" 

                                                                            class="loader btn btn-default btn-rounded mt-3 btn-block">%2$s</button>

                                                                        %3$s

                                                                    </div>

                                                                </div>',

                                                                /**
                                                                 *  1. Form Button ID
                                                                 *  -----------------
                                                                 */
                                                                esc_attr( 'sdweddingdirectory_couple_login_form' ),

                                                                /**
                                                                 *  2. Form Button Text
                                                                 *  -------------------
                                                                 */
                                                                esc_attr__( 'Log In', 'sdweddingdirectory' ),

                                                                /**
                                                                 *  3. Vendor Registration Form Security
                                                                 *  ------------------------------------
                                                                 */
                                                                wp_nonce_field( 

                                                                    'sdweddingdirectory_couple_login_security', 

                                                                    'sdweddingdirectory_couple_login_security', 

                                                                    true, false 
                                                                ),

                                                                /**
                                                                 *  4. Translation Ready String
                                                                 *  ---------------------------
                                                                 */
                                                                esc_attr__( 'Wait a moment...', 'sdweddingdirectory' )
                                                        );

                                                        /**
                                                         *  Redirection Handler
                                                         *  -------------------
                                                         */
                                                        do_action( 'sdweddingdirectory/modal-popup/redirection-field', [

                                                            'modal_id'      =>      esc_attr( $modal_id )

                                                        ] );

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

                print '</div>';
            }
        }

        /**
         *  3. SDWeddingDirectory - Couple Registration Model Popup
         *  -----------------------------------------------
         */
        public static function couple_register_model_popup(){

            /**
             *  Make sure user not login
             *  ------------------------
             */
            if( ! is_user_logged_in() ){

                /**
                 *  Modal ID
                 *  --------
                 */
                $modal_id       =       esc_attr( 'couple_register' );

                /**
                 *  Model Start
                 *  -----------
                 */
                printf( '<div class="modal fade" id="%1$s" tabindex="-1" aria-labelledby="%1$s" aria-hidden="true">',

                    /**
                     *  1. Couple Register Popup ID
                     *  ---------------------------
                     */
                    esc_attr( parent:: popup_id( $modal_id ) )
                );

                ?>
                    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">            

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

                                                    esc_url(  parent:: placeholder( 'couple-login-register-popup' ) )
                                                );

                                            ?>
                                        >
                                        </div>
                                        <!-- / col-md-5 -->

                                        <!-- col-md-7 -->
                                        <div class="col-lg-7 col-md-12 col-12">

                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
                                            <?php

                                            ?>
                                            <!-- login section -->
                                            <div class="login-sidebar-pad text-center register-form">
                                            <?php

                                                printf( '<div class="or-text">%1$s</div>', 

                                                    /**
                                                     *  1. Translation Ready String
                                                     *  ---------------------------
                                                     */
                                                    esc_attr__( 'Register Couple Account', 'sdweddingdirectory' )
                                                );

                                                ?>

                                                <form id="sdweddingdirectory_couple_registration_form" method="post" autocomplete="off" > 

                                                    <div class="row row-cols-1 row-cols-md-2 row-cols-sm-2">

                                                    <?php

                                                        parent:: call_field_type( [

                                                            'field_type'    =>      esc_attr( 'input' ),

                                                            'echo'          =>      true,
                                                                    
                                                            'id'            =>      esc_attr( 'sdweddingdirectory_couple_register_first_name' ),

                                                            'name'          =>      esc_attr( 'sdweddingdirectory_couple_register_first_name' ),

                                                            'placeholder'   =>      esc_attr__('First Name','sdweddingdirectory'),

                                                            'require'       =>      true,

                                                        ] );

                                                        parent:: call_field_type( [

                                                            'field_type'    =>      esc_attr( 'input' ),

                                                            'echo'          =>      true,
                                                                    
                                                            'id'            =>      esc_attr( 'sdweddingdirectory_couple_register_last_name' ),

                                                            'name'          =>      esc_attr( 'sdweddingdirectory_couple_register_last_name' ),

                                                            'placeholder'   =>      esc_attr__('Last Name','sdweddingdirectory'),

                                                            'require'       =>      true,

                                                        ] );

                                                        parent:: call_field_type( [

                                                            'field_type'    =>      esc_attr( 'input' ),

                                                            'echo'          =>      true,
                                                                    
                                                            'id'            =>      esc_attr( 'sdweddingdirectory_couple_register_username' ),

                                                            'name'          =>      esc_attr( 'sdweddingdirectory_couple_register_username' ),

                                                            'placeholder'   =>      esc_attr__('Username','sdweddingdirectory'),

                                                            'pattern'       =>      '^[a-zA-Z0-9_]{4,35}$',

                                                            'title'         =>      esc_attr__( 'Allowed Character ( a-z, A-Z, 0-9, _ ) and length 4 mandatory', 'sdweddingdirectory' ),

                                                            'require'       =>      true,

                                                        ] );

                                                        parent:: call_field_type( [

                                                            'field_type'    =>      esc_attr( 'password' ),

                                                            'echo'          =>      true,
                                                                    
                                                            'id'            =>      esc_attr( 'sdweddingdirectory_couple_register_password' ),

                                                            'name'          =>      esc_attr( 'sdweddingdirectory_couple_register_password' ),

                                                            'placeholder'   =>      esc_attr__('Password','sdweddingdirectory'),

                                                            'type'          =>      esc_attr( 'password' ),

                                                            'pattern'       =>      "(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}",

                                                            'title'         =>      esc_attr__( "Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters", 'sdweddingdirectory' ),

                                                            'require'       =>      true,

                                                        ] );

                                                        parent:: call_field_type( [

                                                            'field_type'    =>      esc_attr( 'input' ),

                                                            'echo'          =>      true,
                                                                    
                                                            'id'            =>      esc_attr( 'sdweddingdirectory_couple_register_email' ),

                                                            'name'          =>      esc_attr( 'sdweddingdirectory_couple_register_email' ),

                                                            'placeholder'   =>      esc_attr__('Email ID','sdweddingdirectory'),

                                                            'type'          =>      esc_attr( 'email' ),

                                                            'pattern'       =>      '[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$',

                                                            'title'         =>      esc_attr__( 'Please enter valid email ID', 'sdweddingdirectory' ),

                                                            'require'       =>      true,

                                                        ] );

                                                        parent:: call_field_type( [

                                                            'field_type'    =>      esc_attr( 'input' ),

                                                            'echo'          =>      true,
                                                                    
                                                            'id'            =>      esc_attr( 'sdweddingdirectory_couple_register_wedding_date' ),

                                                            'name'          =>      esc_attr( 'sdweddingdirectory_couple_register_wedding_date' ),

                                                            'placeholder'   =>      esc_attr__('Wedding Date','sdweddingdirectory'),

                                                            'type'          =>      esc_attr( 'date' ),

                                                            'value'         =>      date( 'Y-m-d' )

                                                        ] );

                                                    ?>

                                                    </div>

                                                    <div class="row row-cols-1">

                                                    <?php


                                                        /**
                                                         *  I am Couple or Guest ?
                                                         *  ----------------------
                                                         */
                                                        printf( '<div class="col text-start">

                                                                    <div class="mb-3">

                                                                        <div class="form-check form-check-inline p-0"><strong>%1$s</strong></div>

                                                                        <div class="form-check form-check-inline">

                                                                            <input autocomplete="off" type="radio" id="%3$s" name="%2$s" class="form-check-input">

                                                                            <label class="form-check-label" for="%3$s">%5$s</label>

                                                                        </div>

                                                                        <div class="form-check form-check-inline">

                                                                            <input autocomplete="off" type="radio" id="%4$s" name="%2$s" class="form-check-input">

                                                                            <label class="form-check-label" for="%4$s">%6$s</label>

                                                                        </div>

                                                                    </div>

                                                                </div>',

                                                                /**
                                                                 *  1. Translation Ready String
                                                                 *  ---------------------------
                                                                 */
                                                                esc_attr__( 'I am', 'sdweddingdirectory' ),

                                                                /**
                                                                 *  2. Name
                                                                 *  -------
                                                                 */
                                                                esc_attr( 'sdweddingdirectory_register_couple_person' ),

                                                                /**
                                                                 *  3. Radio Button ID
                                                                 *  ------------------
                                                                 */
                                                                esc_attr( 'i_am_couple' ),

                                                                /**
                                                                 *  4. Radio Button ID
                                                                 *  ------------------
                                                                 */
                                                                esc_attr( 'i_am_guest' ),

                                                                /**
                                                                 *  5. Placeholder : I am Couple
                                                                 *  ----------------------------
                                                                 */
                                                                esc_attr__( 'Planning my wedding', 'sdweddingdirectory' ),

                                                                /**
                                                                 *  6. Placeholder : I am Guest
                                                                 *  ---------------------------
                                                                 */
                                                                esc_attr__( 'A wedding guest', 'sdweddingdirectory' )
                                                        );
                                                    ?>
                                                    </div>

                                                    <div class="text-start">
                                                    <?php 

                                                        /**
                                                         *  Privacy Policy Note
                                                         *  -------------------
                                                         */
                                                        echo    apply_filters( 'sdweddingdirectory/term_and_condition_note', [

                                                                    'name'          =>      esc_attr__( 'Sign Up', 'sdweddingdirectory' )

                                                                ] );
                                                    ?>
                                                    </div>

                                                    <!-- Submit Button -->
                                                    <div class="col-12">

                                                        <div class="mb-3 d-grid">
                                                        <?php

                                                            printf( '<div class="d-grid">

                                                                        <button type="submit" name="%1$s" id="%1$s"   

                                                                            data-message-wait="%4$s"

                                                                            class="loader btn btn-default btn-rounded mt-3 btn-block">%2$s</button>

                                                                        %3$s

                                                                    </div>',

                                                                    /**
                                                                     *  1. Form Button ID
                                                                     *  -----------------
                                                                     */
                                                                    esc_attr( 'sdweddingdirectory_register_couple_registration_form_button' ),

                                                                    /**
                                                                     *  2. Form Button Text
                                                                     *  -------------------
                                                                     */
                                                                    esc_attr__( 'Sign Up', 'sdweddingdirectory' ),

                                                                    /**
                                                                     *  3. Couple Registration Form Security
                                                                     *  ------------------------------------
                                                                     */
                                                                    wp_nonce_field( 

                                                                        'sdweddingdirectory_couple_registration_security', 

                                                                        'sdweddingdirectory_couple_registration_security', 

                                                                        true, false 
                                                                    ),

                                                                    /**
                                                                     *  4. Translation Ready String
                                                                     *  ---------------------------
                                                                     */
                                                                    esc_attr__( 'Wait a moment...', 'sdweddingdirectory' )
                                                            );

                                                            /**
                                                             *  Redirection Handler
                                                             *  -------------------
                                                             */
                                                            do_action( 'sdweddingdirectory/modal-popup/redirection-field', [

                                                                'modal_id'      =>      esc_attr( $modal_id )

                                                            ] );
                                                        ?>
                                                        </div>

                                                    </div>
                                                    <!-- / Submit Button -->
                                                    <?php

                                                        printf( '<span>%1$s</span> <a  class="btn-link-primary" %3$s>%2$s</a>',

                                                            /**
                                                             *  1. Translation Ready String
                                                             *  ---------------------------
                                                             */
                                                            esc_attr__( 'Already have an account?', 'sdweddingdirectory' ),

                                                            /**
                                                             *  2. Translation Ready String
                                                             *  ---------------------------
                                                             */
                                                            esc_attr__( 'Log in', 'sdweddingdirectory' ),

                                                            /**
                                                             *  3. Couple Login Attributes 
                                                             *  --------------------------
                                                             */
                                                            apply_filters( 'sdweddingdirectory/couple-login/attr', '' )
                                                        );

                                                    ?>
                                                </form>

                                            </div>
                                            <!-- / login section -->
                              
                                            <div class="login-footer">
                                            <?php

                                                printf( '<div class="or-text mb-1">%1$s</div> <a class="btn-link-default" %2$s>%3$s</a>',

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
                
                </div>
                <?php
            }
        }

        /**
         *  Register Model
         *  --------------
         */
        public static function register_model( $args = [] ){

            /**
             *  Merger Already Exists Model
             *  ---------------------------
             */
            return      array_merge( $args, array(

                            [
                                'slug'              =>      esc_attr( 'couple_register' ),

                                'modal_id'          =>      esc_attr( 'sdweddingdirectory_couple_registration_model_popup' ),

                                'redirect_link'     =>      apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'couple-dashboard' ) ),

                                'name'              =>      esc_attr__( 'Couple Registration Modal Popup', 'sdweddingdirectory' )
                            ],

                            [
                                'slug'              =>      esc_attr( 'couple_login' ),

                                'modal_id'          =>      esc_attr( 'sdweddingdirectory_couple_login_model_popup' ),

                                'redirect_link'     =>      apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'couple-dashboard' ) ),

                                'name'              =>      esc_attr__( 'Couple Login Modal Popup', 'sdweddingdirectory' )
                            ]

                        ) );
        }

        /**
         *  Register Model Have Placeholders ?
         *  ----------------------------------
         */
        public static function register_model_images( $args = [] ){

            /**
             *  Add Couple Model Popup Image
             *  ----------------------------
             */
            return  array_merge(

                        /**
                         *  Have Args ?
                         *  -----------
                         */
                        $args,

                        /**
                         *  Merge New Args
                         *  --------------
                         */
                        array(

                            'couple-login-register-popup'        =>     esc_url(

                                                                            /**
                                                                             *  1. Image Path
                                                                             *  -------------
                                                                             */
                                                                            plugin_dir_url( __FILE__ ) . 'images/' .

                                                                            /**
                                                                             *  2. Image Name
                                                                             *  -------------
                                                                             */
                                                                            esc_attr( 'couple-login-register.jpg' )
                                                                        ),
                        )
                    );
        }

        /**
         *  6. SDWeddingDirectory - Couple Login Button Attr
         *  ----------------------------------------
         */
        public static function couple_login_button_attr(){

            /**
             *  Couple Login - Attributes
             *  -------------------------
             */
            return      sprintf( 'href="javascript:" role="button" data-bs-toggle="modal" data-bs-target="#%1$s" data-bs-dismiss="modal"', 

                            /**
                             *  1. Popup ID - Couple Login
                             *  --------------------------
                             */
                            esc_attr( parent:: popup_id( 'couple_login' ) )
                        );
        }

        /**
         *  7. SDWeddingDirectory - Couple Register Button Attr
         *  -------------------------------------------
         */
        public static function couple_register_button_attr(){

            /**
             *  Couple Register - Attributes
             *  ----------------------------
             */
            return      sprintf( 'href="javascript:" role="button" data-bs-toggle="modal" data-bs-target="#%1$s" data-bs-dismiss="modal"', 

                            /**
                             *  1. Popup ID - Couple Register
                             *  -----------------------------
                             */
                            esc_attr( parent:: popup_id( 'couple_register' ) )
                        );
        }
    }  

    /**
     *  SDWeddingDirectory - Couple Login & Registration Form
     *  ---------------------------------------------
     */
    SDWeddingDirectory_Couple_Login_Register_Form::get_instance();
}
