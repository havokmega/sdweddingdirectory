<?php
/**
 *  SDWeddingDirectory - Vendor Login & Registration Form
 *  ---------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Login_Register_Form' ) && class_exists( 'SDWeddingDirectory_Front_End_Modules' ) ){

    /**
     *  SDWeddingDirectory - Vendor Login & Registration Form
     *  ---------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Login_Register_Form extends SDWeddingDirectory_Front_End_Modules{

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
            add_action( 'wp_footer', [ $this, 'vendor_login_model_popup' ] );

            /**
             *  3. SDWeddingDirectory Register Model Popup
             *  ----------------------------------
             */
            add_action( 'wp_footer', [ $this, 'vendor_register_model_popup' ] );

            /**
             *  4. Register Popup ID
             *  --------------------
             */
            add_filter( 'sdweddingdirectory/model-popup', [ $this, 'register_model' ], absint( '5' ), absint( '1' ) );

            /**
             *  4.1 Claim Request Modal Popup
             *  -----------------------------
             */
            add_action( 'wp_footer', [ $this, 'claim_request_modal_popup' ] );

            /**
             *  6. SDWeddingDirectory - Vendor Login Button Attr
             *  ----------------------------------------
             */
            add_filter( 'sdweddingdirectory/vendor-login/attr', [$this, 'vendor_login_button_attr' ], absint( '10' ), absint( '1' ) );

            /**
             *  7. SDWeddingDirectory - Vendor Register Button Attr
             *  -------------------------------------------
             */
            add_filter( 'sdweddingdirectory/vendor-register/attr', [$this, 'vendor_register_button_attr' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  1. Load the vendor registration script.
         *  --------------------------------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Load Script
             *  -----------
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
         *  2. SDWeddingDirectory - Vendor Login Model Popup
         *  ----------------------------------------
         */
        public static function vendor_login_model_popup(){

            /**
             *  Make sure user not login
             *  ------------------------
             */
            if( ! is_user_logged_in() ){

                /**
                 *  Modal ID
                 *  --------
                 */
                $modal_id       =       esc_attr( 'vendor_login' );

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

                                                    esc_url(  parent:: placeholder( 'vendor-login-register-popup' ) )
                                                );

                                            ?>
                                        >
                                        </div>
                                        <!-- / col-md-5 -->
                                        
                                        <!-- col-md-7 -->
                                        <div class="col-lg-7 col-md-12 col-12">

                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>

                                            <div class="p-5 text-center">

                                                <?php

                                                    printf( '<h3>%1$s</h3>', 

                                                        /**
                                                         *  1. Translation Ready String
                                                         *  ---------------------------
                                                         */
                                                        esc_attr__( 'Log in Vendor / Supplier account', 'sdweddingdirectory' )
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
                                                         *  4. Vendor Register Model ID
                                                         *  ---------------------------
                                                         */
                                                        apply_filters( 'sdweddingdirectory/vendor-register/attr', '' )
                                                    );
                                                ?>
                                            </div>

                                            <hr/>

                                            <!-- login section -->
                                            <div class="login-sidebar-pad text-center">

                                                <form id="sdweddingdirectory-vendor-login-form" method="post">

                                                    <div class="row row-cols-1">
                                                    <?php

                                                        parent:: call_field_type( [

                                                            'field_type'    =>      esc_attr( 'input' ),

                                                            'echo'          =>      true,
                                                                    
                                                            'id'            =>      esc_attr( 'sdweddingdirectory_vendor_login_username' ),

                                                            'name'          =>      esc_attr( 'sdweddingdirectory_vendor_login_username' ),

                                                            'placeholder'   =>      esc_attr__( 'Username or email address', 'sdweddingdirectory' ),

                                                            'require'       =>      true,

                                                        ] );

                                                        parent:: call_field_type( [

                                                            'field_type'    =>      esc_attr( 'password' ),

                                                            'echo'          =>      true,
                                                                    
                                                            'id'            =>      esc_attr( 'sdweddingdirectory_vendor_login_password' ),

                                                            'name'          =>      esc_attr( 'sdweddingdirectory_vendor_login_password' ),

                                                            'placeholder'   =>      esc_attr__( 'Password','sdweddingdirectory' ),

                                                            'type'          =>      esc_attr( 'password' ),

                                                            'require'       =>      true,

                                                        ] );

                                                        /**
                                                         *  Forgot Password ?
                                                         *  -----------------
                                                         */
                                                        printf(  '<div class="col-12">

                                                                    <a  class="btn-link-primary" href="javascript:" role="button" 

                                                                        data-bs-toggle="modal" data-bs-target="#%1$s" data-bs-dismiss="modal">%2$s</a>

                                                                  </div>',

                                                                  /**
                                                                   *  1. Forgot Password Model ID
                                                                   *  ---------------------------
                                                                   */
                                                                  esc_attr( parent:: popup_id( 'forgot_password' ) ),

                                                                  /**
                                                                   *  2. Translation Ready String
                                                                   *  ---------------------------
                                                                   */
                                                                  esc_attr__( 'Forgot your password ?', 'sdweddingdirectory' )
                                                        );

                                                        /**
                                                         *  Login
                                                         *  -----
                                                         */
                                                        printf(  '<div class="col-12">

                                                                    <div class="mb-3 d-grid">

                                                                        <button type="submit" name="%1$s" id="%1$s" 

                                                                            data-message-wait="%4$s"

                                                                            class="loader btn btn-default btn-rounded mt-3 btn-block">%2$s</button>

                                                                        %3$s

                                                                    </div>

                                                                </div>',

                                                                /**
                                                                 *  1. Form Button ID
                                                                 *  -----------------
                                                                 */
                                                                esc_attr( 'sdweddingdirectory_vendor_login_form' ),

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

                                                                    'sdweddingdirectory_vendor_login_security', 

                                                                    'sdweddingdirectory_vendor_login_security', 

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
                                                        
                                                        <a  class="btn-link-default" %2$s>%3$s</a>',

                                                        /**
                                                         *  1. Translation Ready String
                                                         *  ---------------------------
                                                         */
                                                        esc_attr__( 'Are you a couple ?', 'sdweddingdirectory' ),

                                                        /**
                                                         *  2. Couple Login Model ID
                                                         *  ------------------------
                                                         */
                                                        apply_filters( 'sdweddingdirectory/couple-login/attr', '' ),

                                                        /**
                                                         *  3. Translation Ready String
                                                         *  ---------------------------
                                                         */
                                                        esc_attr__( 'Couple login', 'sdweddingdirectory' )
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

                </div><!-- Vendor - Login Modal Popup -->
                <?php
            }
        }

        /**
         *  3. SDWeddingDirectory - Vendor Registration Model Popup
         *  -----------------------------------------------
         */
        public static function vendor_register_model_popup(){

            /**
             *  Make sure user not login
             *  ------------------------
             */
            if( ! is_user_logged_in() ){

                /**
                 *  Modal ID
                 *  --------
                 */
                $modal_id       =       esc_attr( 'vendor_register' );

                /**
                 *  Popup ID
                 *  --------
                 */
                printf( '<div class="modal fade" id="%1$s" tabindex="-1" aria-labelledby="%1$s" aria-hidden="true">',

                    /**
                     *  1. Vendor Register Popup ID
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

                                                    esc_url(  parent:: placeholder( 'vendor-login-register-popup' ) )
                                                );

                                            ?>
                                        >
                                        </div>
                                        <!-- / col-md-5 -->

                                        <!-- col-md-7 -->
                                        <div class="col-lg-7 col-md-12 col-12">

                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>

                                            <!-- login section -->
                                            <div class="login-sidebar-pad text-center register-form">

                                                <div class="or-text">

                                                    <?php esc_attr_e( 'Register Vendor / Supplier Account', 'sdweddingdirectory' ); ?>
                                                    
                                                </div>

                                                <form id="sdweddingdirectory_vendor_registration_form" method="post" autocomplete="off" > 

                                                    <div class="row row-cols-1 row-cols-md-2 row-cols-sm-2">
                                                    <?php

                                                        parent:: call_field_type( [

                                                            'field_type'    =>      esc_attr( 'input' ),

                                                            'echo'          =>      true,
                                                                    
                                                            'id'            =>      esc_attr( 'sdweddingdirectory_vendor_register_first_name' ),

                                                            'name'          =>      esc_attr( 'sdweddingdirectory_vendor_register_first_name' ),

                                                            'placeholder'   =>      esc_attr__( 'First Name','sdweddingdirectory' ),

                                                            'require'       =>      true,

                                                        ] );

                                                        parent:: call_field_type( [

                                                            'field_type'    =>      esc_attr( 'input' ),

                                                            'echo'          =>      true,
                                                                    
                                                            'id'            =>      esc_attr( 'sdweddingdirectory_vendor_register_last_name' ),

                                                            'name'          =>      esc_attr( 'sdweddingdirectory_vendor_register_last_name' ),

                                                            'placeholder'   =>      esc_attr__( 'Last Name','sdweddingdirectory' ),

                                                            'require'       =>      true,

                                                        ] );

                                                        parent:: call_field_type( [

                                                            'field_type'    =>      esc_attr( 'input' ),

                                                            'echo'          =>      true,
                                                                    
                                                            'id'            =>      esc_attr( 'sdweddingdirectory_vendor_register_username' ),

                                                            'name'          =>      esc_attr( 'sdweddingdirectory_vendor_register_username' ),

                                                            'placeholder'   =>      esc_attr__( 'Username','sdweddingdirectory' ),

                                                            'pattern'       =>      '^[a-zA-Z0-9_]{4,35}$',

                                                            'title'         =>      esc_attr__( 'Allowed Character ( a-z, A-Z, 0-9, _ ) and length 4 mandatory', 'sdweddingdirectory' ),

                                                            'require'       =>      true,

                                                        ] );

                                                        parent:: call_field_type( [

                                                            'field_type'    =>      esc_attr( 'input' ),

                                                            'echo'          =>      true,
                                                                    
                                                            'id'            =>      esc_attr( 'sdweddingdirectory_vendor_register_email' ),

                                                            'name'          =>      esc_attr( 'sdweddingdirectory_vendor_register_email' ),

                                                            'placeholder'   =>      esc_attr__( 'Email','sdweddingdirectory' ),

                                                            'type'          =>      esc_attr( 'email' ),

                                                            'pattern'       =>      '[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$',

                                                            'title'         =>      esc_attr__( 'Please enter valid email ID', 'sdweddingdirectory' ),

                                                            'require'       =>      true,

                                                        ] );

                                                        parent:: call_field_type( [

                                                            'field_type'    =>      esc_attr( 'password' ),

                                                            'echo'          =>      true,
                                                                    
                                                            'id'            =>      esc_attr( 'sdweddingdirectory_vendor_register_password' ),

                                                            'name'          =>      esc_attr( 'sdweddingdirectory_vendor_register_password' ),

                                                            'placeholder'   =>      esc_attr__( 'Password','sdweddingdirectory' ),

                                                            'type'          =>      esc_attr( 'password' ),

                                                            'pattern'       =>      "(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}",

                                                            'title'         =>      esc_attr__( "Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters", 'sdweddingdirectory' ),

                                                            'require'       =>      true,

                                                        ] );

                                                        parent:: call_field_type( [

                                                            'field_type'    =>      esc_attr( 'input' ),

                                                            'echo'          =>      true,
                                                                    
                                                            'id'            =>      esc_attr( 'sdweddingdirectory_vendor_register_company_name' ),

                                                            'name'          =>      esc_attr( 'sdweddingdirectory_vendor_register_company_name' ),

                                                            'placeholder'   =>      esc_attr__( 'Company Name','sdweddingdirectory' ),

                                                        ] );

                                                        ?>
                                                        <div class="col-12">
                                                            <div class="mb-3 text-start">
                                                                <label class="form-label d-block"><?php esc_html_e( 'Business Type', 'sdweddingdirectory' ); ?></label>
                                                                <div class="d-flex align-items-center gap-4">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="sdweddingdirectory_vendor_register_account_type" id="sdweddingdirectory_vendor_register_account_type_vendor" value="vendor" checked />
                                                                        <label class="form-check-label" for="sdweddingdirectory_vendor_register_account_type_vendor"><?php esc_html_e( 'Vendor', 'sdweddingdirectory' ); ?></label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="sdweddingdirectory_vendor_register_account_type" id="sdweddingdirectory_vendor_register_account_type_venue" value="venue" />
                                                                        <label class="form-check-label" for="sdweddingdirectory_vendor_register_account_type_venue"><?php esc_html_e( 'Venue', 'sdweddingdirectory' ); ?></label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php

                                                        parent:: call_field_type( [

                                                            'field_type'    =>      esc_attr( 'input' ),

                                                            'echo'          =>      true,
                                                                    
                                                            'id'            =>      esc_attr( 'sdweddingdirectory_vendor_register_company_website' ),

                                                            'name'          =>      esc_attr( 'sdweddingdirectory_vendor_register_company_website' ),

                                                            'placeholder'   =>      esc_attr__( 'Company Website','sdweddingdirectory' ),

                                                            'pattern'       =>      "https?://.+",

                                                            'title'         =>      esc_attr__( "Include http://", 'sdweddingdirectory' )

                                                        ] );

                                                        parent:: call_field_type( [

                                                            'field_type'    =>      esc_attr( 'input' ),

                                                            'echo'          =>      true,
                                                                    
                                                            'id'            =>      esc_attr( 'sdweddingdirectory_vendor_register_contact_number' ),

                                                            'name'          =>      esc_attr( 'sdweddingdirectory_vendor_register_contact_number' ),

                                                            'placeholder'   =>      esc_attr__( 'Contact Number','sdweddingdirectory' ),

                                                            'type'          =>      esc_attr( 'tel' )

                                                        ] );

                                                        ?>
                                                        <div id="sdwd_vendor_category_wrap">
                                                        <?php

                                                        parent:: call_field_type( [

                                                            'field_type'    =>      esc_attr( 'select' ),

                                                            'echo'          =>      true,
                                                                    
                                                            'id'            =>      esc_attr( 'sdweddingdirectory_vendor_register_category' ),

                                                            'name'          =>      esc_attr( 'sdweddingdirectory_vendor_register_category' ),

                                                            'options'       =>      SDWeddingDirectory_Taxonomy:: create_select_option(

                                                                                        /**
                                                                                         *  Taxonomy Parent Values
                                                                                         *  ----------------------
                                                                                         */
                                                                                        SDWeddingDirectory_Taxonomy:: get_taxonomy_parent( 'vendor-category' ),

                                                                                        /**
                                                                                         *  Placeholder
                                                                                         *  -----------
                                                                                         */
                                                                                        [ '0'   =>  esc_attr__( 'Choose Category','sdweddingdirectory' ) ],

                                                                                        /**
                                                                                         *  Default select
                                                                                         *  --------------
                                                                                         */
                                                                                        '',

                                                                                        /**
                                                                                         *  Return Value
                                                                                         *  ------------
                                                                                         */
                                                                                        false
                                                                                    )
                                                        ] );

                                                        ?>
                                                        </div>
                                                        <?php

                                                    ?>

                                                    <!-- Privacy and policy -->
                                                    <div class="col-12 text-start">
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
                                                    <div class="col-12"><div class="mb-3">
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
                                                                esc_attr( 'sdweddingdirectory_vendor_register_form_button' ),

                                                                /**
                                                                 *  2. Form Button Text
                                                                 *  -------------------
                                                                 */
                                                                esc_attr__( 'Sign Up', 'sdweddingdirectory' ),

                                                                /**
                                                                 *  3. Vendor Registration Form Security
                                                                 *  ------------------------------------
                                                                 */
                                                                wp_nonce_field( 

                                                                    'sdweddingdirectory_vendor_registration_form_security', 

                                                                    'sdweddingdirectory_vendor_registration_form_security', 

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
                                                    </div></div>
                                                    <!-- / Submit Button -->

                                                  </div>

                                                  <?php

                                                      printf( '%1$s <a class="btn-link-primary" %2$s>%3$s</a>',

                                                          /**
                                                           *  1. Translation Ready String
                                                           *  ---------------------------
                                                           */
                                                          esc_attr__( 'Already have an account?', 'sdweddingdirectory' ),

                                                          /**
                                                           *  2. Vendor Login Model ID
                                                           *  ------------------------
                                                           */
                                                          apply_filters( 'sdweddingdirectory/vendor-login/attr', '' ),

                                                          /**
                                                           *  3. Translation Ready String
                                                           *  ---------------------------
                                                           */
                                                          esc_attr__( 'Log in', 'sdweddingdirectory' )
                                                      );

                                                  ?>

                                                </form>

                                            </div>
                                            <!-- / login section -->
                              
                                            <div class="login-footer">
                                            <?php

                                                printf( '<div class="or-text mb-1">

                                                            <div>%1$s</div>

                                                        </div>
                                                        
                                                        <a  class="btn-link-default" %2$s>%3$s</a>',

                                                        /**
                                                         *  1. Translation Ready String
                                                         *  ---------------------------
                                                         */
                                                        esc_attr__( 'Are you a couple ?', 'sdweddingdirectory' ),

                                                        /**
                                                         *  2. Couple Login Model ID
                                                         *  ------------------------
                                                         */
                                                        apply_filters( 'sdweddingdirectory/couple-login/attr', '' ),

                                                        /**
                                                         *  3. Translation Ready String
                                                         *  ---------------------------
                                                         */
                                                        esc_attr__( 'Couple login', 'sdweddingdirectory' )
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
                                'slug'              =>      esc_attr( 'vendor_register' ),

                                'modal_id'          =>      esc_attr( 'sdweddingdirectory_vendor_registration_model_popup' ),

                                'redirect_link'     =>      apply_filters( 'sdweddingdirectory/vendor-menu/page-link', esc_attr( 'vendor-dashboard' ) ),

                                'name'              =>      esc_attr__( 'Vendor Registration Modal Popup', 'sdweddingdirectory' )
                            ],

                            [
                                'slug'              =>      esc_attr( 'vendor_login' ),

                                'modal_id'          =>      esc_attr( 'sdweddingdirectory_vendor_login_model_popup' ),

                                'redirect_link'     =>      apply_filters( 'sdweddingdirectory/vendor-menu/page-link', esc_attr( 'vendor-dashboard' ) ),

                                'name'              =>      esc_attr__( 'Vendor Login Modal Popup', 'sdweddingdirectory' )
                            ]

                        ) );
        }

        /**
         *  Claim Request Modal
         *  -------------------
         */
        public static function claim_request_modal_popup(){

            if( is_user_logged_in() && ! parent:: is_vendor() ){
                return;
            }

            ?>
            <div class="modal fade" id="sdwd_profile_claim_modal" tabindex="-1" aria-labelledby="sdwd_profile_claim_modal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?php esc_html_e( 'Claim This Business Profile', 'sdweddingdirectory' ); ?></h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
                        </div>
                        <div class="modal-body">
                            <p class="small text-muted mb-3"><?php esc_html_e( 'This profile exists. If you are the owner, submit a claim request.', 'sdweddingdirectory' ); ?></p>
                            <form id="sdwd_profile_claim_form" method="post">
                                <div class="mb-2">
                                    <input type="text" class="form-control" id="sdwd_claimant_name" placeholder="<?php esc_attr_e( 'Full Name', 'sdweddingdirectory' ); ?>" required />
                                </div>
                                <div class="mb-2">
                                    <input type="tel" class="form-control" id="sdwd_claimant_phone" placeholder="<?php esc_attr_e( 'Phone Number', 'sdweddingdirectory' ); ?>" required />
                                </div>
                                <div class="mb-2">
                                    <input type="email" class="form-control" id="sdwd_claimant_email" placeholder="<?php esc_attr_e( 'Email Address', 'sdweddingdirectory' ); ?>" required />
                                </div>
                                <input type="hidden" id="sdwd_target_post_id" value="" />
                                <input type="hidden" id="sdwd_target_post_type" value="" />
                                <input type="hidden" id="sdwd_target_slug" value="" />
                                <?php wp_nonce_field( 'sdwd_profile_claim_submit', 'sdwd_profile_claim_submit_nonce', true, true ); ?>
                                <div class="d-flex justify-content-end gap-2 mt-3">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?php esc_html_e( 'Go Back', 'sdweddingdirectory' ); ?></button>
                                    <button type="submit" class="btn btn-default" id="sdwd_profile_claim_submit"><?php esc_html_e( 'Submit Claim', 'sdweddingdirectory' ); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

        /**
         *  Register Model Have Placeholders ?
         *  ----------------------------------
         */
        public static function register_model_images( $args = [] ){

            /**
             *  Add Vendor Model Popup Image
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

                            'vendor-login-register-popup'     =>    esc_url(

                                                                        /**
                                                                         *  1. Image Path
                                                                         *  -------------
                                                                         */
                                                                        plugin_dir_url( __FILE__ ) . 'images/' .

                                                                        /**
                                                                         *  2. Image Name
                                                                         *  -------------
                                                                         */
                                                                        esc_attr( 'vendor-login-register.jpg' )
                                                                    ),
                        )
                    );
        }

        /**
         *  6. SDWeddingDirectory - Couple Login Button Attr
         *  ----------------------------------------
         */
        public static function vendor_login_button_attr(){

            /**
             *  Vendor Login - Attributes
             *  -------------------------
             */
            return      sprintf( 'href="javascript:" role="button" data-bs-toggle="modal" data-bs-target="#%1$s" data-bs-dismiss="modal"', 

                            /**
                             *  1. Popup ID - Vendor Login
                             *  --------------------------
                             */
                            esc_attr( parent:: popup_id( 'vendor_login' ) )
                        );
        }

        /**
         *  7. SDWeddingDirectory - Couple Register Button Attr
         *  -------------------------------------------
         */
        public static function vendor_register_button_attr(){

            /**
             *  Vendor Register - Attributes
             *  ----------------------------
             */
            return      sprintf( 'href="javascript:" role="button" data-bs-toggle="modal" data-bs-target="#%1$s" data-bs-dismiss="modal"', 

                            /**
                             *  1. Popup ID - Vendor Register
                             *  -----------------------------
                             */
                            esc_attr( parent:: popup_id( 'vendor_register' ) )
                        );
        }
    }

    /**
     *  SDWeddingDirectory - Vendor Login & Registration Form
     *  ---------------------------------------------
     */
    SDWeddingDirectory_Vendor_Login_Register_Form::get_instance();
}
