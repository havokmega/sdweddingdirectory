<?php
/**
 *  SDWeddingDirectory - Guest List Form
 *  ----------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Guest_List_Form_Filter' ) && class_exists( 'SDWeddingDirectory_Guest_List_Filters' ) ){

    /**
     *  SDWeddingDirectory - Guest List Form
     *  ----------------------------
     */
    class SDWeddingDirectory_Guest_List_Form_Filter extends SDWeddingDirectory_Guest_List_Filters{

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
        public function __construct(){

            /**
             *  1. Scripts
             *  ----------
             */
            add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'], absint( '50' ) );

            /**
             *  Guest List Form
             *  ---------------
             */
            add_action( 'sdweddingdirectory/guest-list/request-missing-info', [ $this, 'guest_list_form' ], absint( '10' ), absint( '1' ) );

            /**
             *  Guest List Form Error
             *  ---------------------
             */
            add_action( 'sdweddingdirectory/guest-list/request-missing-info/error', [ $this, 'error_message' ], absint( '10' ) );

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
                         *  1. Missing Guest Info Fill
                         *  --------------------------
                         */
                        esc_attr( 'sdweddingdirectory_missing_guest_info' )
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
         *  1. Dashboard Scripts
         *  --------------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  If is couple guest list
             *  -----------------------
             */
            if( is_page_template( 'user-template/request-missing-info.php' ) ) {

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
                    [ 'jquery' ],

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
         *  Guest List Form
         *  ---------------
         */
        public static function guest_list_form( $args = [] ){

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'couple_id'     =>      absint( '0' ),

                    'guest_id'      =>      absint( '0' )

                ] ) );

                /**
                 *  Make sure couple id not emtpy!
                 *  ------------------------------
                 */
                if( empty( $couple_id ) || empty( $guest_id ) ){

                    self:: error_message();
                }

                /**
                 *  Make sure it's couple post
                 *  --------------------------
                 */
                if( get_post_type( $couple_id ) == esc_attr( 'couple' ) ){

                    /**
                     *  Guest List
                     *  ----------
                     */
                    $_couple_guest_data     =   get_post_meta( $couple_id, sanitize_key( 'guest_list_data' ), true );

                    $_member_data           =   [];

                    /**
                     *  Make sure guest data available 
                     *  ------------------------------
                     */
                    if( parent:: _is_array( $_couple_guest_data ) ){

                        foreach( $_couple_guest_data as $key => $value ){

                            /**
                             *  Extract
                             *  -------
                             */
                            extract( $value );

                            /**
                             *  Guest Found ?
                             *  -------------
                             */
                            if( $guest_unique_id == $guest_id ){

                                /**
                                 *  Make sure this user already fill this form
                                 *  ------------------------------------------
                                 */
                                if( $request_missing_info != esc_attr( 'yes' ) ){

                                    $_member_data       =       $value;
                                }
                            }
                        }
                    }

                    /**
                     *  Guest Exists ?
                     *  --------------
                     */
                    if( parent:: _is_array( $_member_data ) ){

                        /**
                         *  Load Form
                         *  ---------
                         */
                        self:: load_form( $couple_id, $_member_data );
                    }

                    else{

                        self:: error_message();
                    }
                }

                /**
                 *  Is not couple
                 *  -------------
                 */
                else{

                    self:: error_message();
                }
            }

            /**
             *  Error
             *  -----
             */
            else{

                self:: error_message();
            }
        }

        /**
         *  Guest Info
         *  ----------
         */
        public static function load_form( $couple_id =  0,  $guest_data = [] ){

            /**
             *  Make sure it's array
             *  --------------------
             */
            if( parent:: _is_array( $guest_data ) && ! empty( $couple_id ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( $guest_data );

                ?>
                <div class="container py-5">
                    
                    <div class="row">

                        <form id="sdweddingdirectory_missing_guest_info_form" method="post">
                        
                            <div class="mx-auto col-6">
                            <?php

                                /**
                                 *  Translation Ready String
                                 *  ------------------------
                                 */
                                printf( '<h1 class="text-center">%1$s</h1>',

                                    /**
                                     *  Value
                                     *  -----
                                     */
                                    sprintf( esc_attr__( '%1$s, can you help us?', 'sdweddingdirectory-guest-list' ), 

                                        /**
                                         *  1. Guest Name
                                         *  -------------
                                         */
                                        esc_attr( $first_name )
                                    )
                                );

                                /**
                                 *  Translation Ready String
                                 *  ------------------------
                                 */
                                printf( '<p class="text-center lead">%1$s</p>',

                                    /**
                                     *  String
                                     *  ------
                                     */
                                    sprintf( 

                                        esc_attr__( 'Please complete the form below, so %1$s %2$s & %3$s %4$s can send you information about their wedding!', 'sdweddingdirectory-guest-list' ),

                                        /**
                                         *  1. Groom First Name
                                         *  -------------------
                                         */
                                        esc_attr( get_post_meta(  absint( $couple_id ), sanitize_key( 'groom_first_name' ), true ) ),

                                        /**
                                         *  2. Groom First Name
                                         *  -------------------
                                         */
                                        esc_attr( get_post_meta(  absint( $couple_id ), sanitize_key( 'groom_last_name' ), true ) ),

                                        /**
                                         *  3. Bride First Name
                                         *  -------------------
                                         */
                                        esc_attr( get_post_meta(  absint( $couple_id ), sanitize_key( 'bride_first_name' ), true ) ),

                                        /**
                                         *  4. Bride Last Name
                                         *  ------------------
                                         */
                                        esc_attr( get_post_meta(  absint( $couple_id ), sanitize_key( 'bride_last_name' ), true ) )
                                    )
                                );

                                print '<hr/>';

                                /**
                                 *  Heading
                                 *  -------
                                 */
                                printf( '<div class="py-4"><h4 class="mb-0">%1$s</h4></div>',

                                    /**
                                     *  1. Translation Ready String
                                     *  ---------------------------
                                     */
                                    esc_attr__( 'About you', 'sdweddingdirectory-guest-list' )
                                );

                                ?>

                                <div class="row row-cols-md-2 row-cols-1">
                                <?php

                                    parent:: guest_list_create_field( [

                                        'id'            =>      esc_attr( 'first_name' ),

                                        'placeholder'   =>      esc_attr__( 'First Name', 'sdweddingdirectory-guest-list' ),

                                        'value'         =>      esc_attr( $first_name )

                                    ] );

                                    parent:: guest_list_create_field( [

                                        'id'            =>      esc_attr( 'last_name' ),

                                        'placeholder'   =>      esc_attr__( 'Last Name', 'sdweddingdirectory-guest-list' ),

                                        'value'         =>      esc_attr( $last_name )

                                    ] );

                                    parent:: guest_list_create_radio_field( [

                                        'name'          =>      esc_attr( 'guest_need_hotel' ),

                                        'options'       =>      [

                                                                    'no'        =>      esc_attr__( 'No', 'sdweddingdirectory-guest-list' ),

                                                                    'yes'       =>      esc_attr__( 'Yes', 'sdweddingdirectory-guest-list' ),
                                                                ],

                                        'checked'       =>      $guest_need_hotel,

                                        'label'         =>      esc_attr__( 'Need Hotel', 'sdweddingdirectory-guest-list' )

                                    ] ); 

                                    parent:: guest_list_create_radio_field( [

                                        'name'          =>      esc_attr( 'guest_age' ),

                                        'options'       =>      array(

                                                                    'adult'     =>  esc_attr__( 'Adult', 'sdweddingdirectory-guest-list' ),

                                                                    'child'     =>  esc_attr__( 'Child', 'sdweddingdirectory-guest-list' ),

                                                                    'baby'      =>  esc_attr__( 'Baby', 'sdweddingdirectory-guest-list' ),
                                                                ),

                                        'checked'       =>      $guest_age,

                                        'label'         =>      esc_attr__( 'Age', 'sdweddingdirectory-guest-list' )

                                    ] );

                                ?>
                                </div>

                                <?php

                                /**
                                 *  Heading
                                 *  -------
                                 */
                                printf( '<div class="py-4"><h4 class="mb-0">%1$s</h4></div>',

                                    /**
                                     *  1. Translation Ready String
                                     *  ---------------------------
                                     */
                                    esc_attr__( 'Contact Information', 'sdweddingdirectory-guest-list' )
                                );

                                ?>

                                <div class="row row-cols-md-2 row-cols-1">
                                <?php

                                    parent:: guest_list_create_field( [

                                        'id'            =>      esc_attr( 'guest_email' ),

                                        'placeholder'   =>      esc_attr__( 'Email', 'sdweddingdirectory-guest-list' ),

                                        'type'          =>      esc_attr( 'email' ),

                                        'value'         =>      esc_attr( $guest_email )

                                    ] );

                                    parent:: guest_list_create_field( [

                                        'id'            =>      esc_attr( 'guest_contact' ),

                                        'placeholder'   =>      esc_attr__( 'Phone Number', 'sdweddingdirectory-guest-list' ),

                                        'type'          =>      esc_attr( 'tel' ),

                                        'value'         =>      esc_attr( $guest_contact )

                                    ] );

                                    parent:: guest_list_create_field( [

                                        'id'            =>      esc_attr( 'guest_address' ),

                                        'placeholder'   =>      esc_attr__( 'Address', 'sdweddingdirectory-guest-list' ),

                                        'value'         =>      esc_attr( $guest_address )

                                    ] );

                                    parent:: guest_list_create_field( [

                                        'id'            =>      esc_attr( 'guest_city' ),

                                        'placeholder'   =>      esc_attr__( 'City / Town', 'sdweddingdirectory-guest-list' ),

                                        'value'         =>      esc_attr( $guest_city )

                                    ] );

                                    parent:: guest_list_create_field( [

                                        'id'            =>      esc_attr( 'guest_state' ),

                                        'placeholder'   =>      esc_attr__( 'State', 'sdweddingdirectory-guest-list' ),

                                        'value'         =>      esc_attr( $guest_state )

                                    ] );

                                    parent:: guest_list_create_field( [

                                        'id'            =>      esc_attr( 'guest_zip_code' ),

                                        'placeholder'   =>      esc_attr__( 'Zip Code', 'sdweddingdirectory-guest-list' ),

                                        'value'         =>      esc_attr( $guest_zip_code )

                                    ] );

                                ?>
                                </div>

                                <div class="col-12">
                                <?php

                                    /**
                                     *  Privacy Policy Note
                                     *  -------------------
                                     */
                                    echo    apply_filters( 'sdweddingdirectory/term_and_condition_note', [

                                                'name'          =>      esc_attr__( 'Submit', 'sdweddingdirectory-guest-list' )

                                            ] );
                                    
                                ?>
                                </div>


                                <div class="col-12">
                                <?php

                                    /**
                                     *  Submit
                                     *  ------
                                     */
                                    printf( '<div class="mb-3">

                                                <button type="submit" name="%1$s" id="%1$s" class="loader btn btn-primary btn-rounded mt-3 btn-block">%2$s</button>

                                                %3$s <!-- security -->

                                                <input type="hidden" name="guest_unique_id" id="guest_unique_id" value="%4$s" />

                                                <input type="hidden" name="couple_post_id" id="couple_post_id" value="%5$s" />

                                            </div>',

                                            /**
                                             *  1. Form Button ID
                                             *  -----------------
                                             */
                                            esc_attr( 'sdweddingdirectory_missing_info_form' ),

                                            /**
                                             *  2. Form Button Text
                                             *  -------------------
                                             */
                                            esc_attr__( 'Submit!', 'sdweddingdirectory-guest-list' ),

                                            /**
                                             *  3. Vendor Registration Form Security
                                             *  ------------------------------------
                                             */
                                            wp_nonce_field( 'sdweddingdirectory_missing_info_security', 'sdweddingdirectory_missing_info_security', true, false ),

                                            /**
                                             *  4. Guest Unique ID
                                             *  ------------------
                                             */
                                            esc_attr( $guest_unique_id ),

                                            /**
                                             *  5. Couple Post ID
                                             *  -----------------
                                             */
                                            esc_attr( $couple_id ),
                                    );
                                    
                                ?>
                                </div>

                            </div>

                        </form>

                    </div>

                </div>
                <?php

            }
        }

        /**
         *  Error Message
         *  -------------
         */
        public static function error_message(){

            ?>
            <div class="container py-5">
                
                <div class="row">
                    
                    <div class="col-12 text-center">
                    <?php

                        /**
                         *  Message
                         *  -------
                         */
                        printf( '<h2>%1$s</h2><p>%2$s</p><p>%3$s</p>',

                            /**
                             *  1. Translation Ready String
                             *  ---------------------------
                             */
                            esc_attr__( 'Error!', 'sdweddingdirectory-guest-list' ),

                            /**
                             *  2. Translation Ready String
                             *  ---------------------------
                             */
                            esc_attr__( 'This link has expired.', 'sdweddingdirectory-guest-list' ),

                            /**
                             *  3. Translation Ready String
                             *  ---------------------------
                             */
                            esc_attr__( 'Please request new access from the couple.', 'sdweddingdirectory-guest-list' )
                        );

                    ?>
                    </div>

                </div>

            </div>
            <?php
        }

        public static function get_options( $_data ){

            $_option = '';

            if( parent:: _is_array( $_data ) ){

                foreach ( $_data as $key => $value) {
                    
                    $_option .= 

                    sprintf( '<option value="%1$s">%2$s</option>', 

                        // 1
                        sanitize_title( $key ),

                        // 2
                        esc_attr( $value )
                    );
                }
            }

            return $_option;
        }

        /**
         *  Request Missing Guest Info
         *  --------------------------
         */
        public static function sdweddingdirectory_missing_guest_info(){

            /**
             *  Verify Nounce
             *  -------------
             */
            $_condition_1   =   wp_verify_nonce( $_POST['security'], esc_attr( 'sdweddingdirectory_missing_info_security' ) );

            /**
             *  Have Unique ID
             *  --------------
             */
            $_condition_2   =   isset( $_POST[ 'guest_unique_id' ] ) &&  ! empty( $_POST[ 'guest_unique_id' ] );

            /**
             *  Security Check
             *  --------------
             */
            if( $_condition_1 && $_condition_2 ){

                /**
                 *  Get Guest List
                 *  --------------
                 */
                $_post_data     =   get_post_meta( $_POST[ 'couple_post_id' ], sanitize_key( 'guest_list_data' ), true );

                $_collection    =   [];

                /**
                 *  Have Data
                 *  ---------
                 */
                if( parent:: _is_array( $_post_data ) ){

                    /**
                     *  Loop
                     *  ----
                     */
                    foreach( $_post_data as $key => $value ){

                        /**
                         *  Extract Args
                         *  ------------
                         */
                        extract( $value );

                        /**
                         *  Have Data
                         *  ---------
                         */
                        if( $guest_unique_id == $_POST['guest_unique_id'] ){

                            $_collection[]  =   array_replace( $value, $_POST['input_group'] );
                        }

                        else{

                            $_collection[]  =   $value;
                        }
                    }

                    /**
                     *  Update Collection
                     *  -----------------
                     */
                    update_post_meta( $_POST[ 'couple_post_id' ], sanitize_key( 'guest_list_data' ), $_collection );

                    /**
                     *  Have Data
                     *  ---------
                     */
                    die( json_encode( [

                        'notice'        =>      absint( '1' ),

                        'message'       =>      esc_attr__( 'Successfully Updated Information !', 'sdweddingdirectory-guest-list' ),

                        'html'          =>      sprintf( '<div class="container">

                                                            <div class="row">

                                                                <div class="col-12 text-center">

                                                                    <h1>%1$s</h1><p>%2$s</p>

                                                                </div>

                                                            </div>

                                                        </div>',

                                                    /**
                                                     *  1. Translation Ready String
                                                     *  ---------------------------
                                                     */
                                                    esc_attr__( 'Thank you!', 'sdweddingdirectory-guest-list' ),

                                                    /**
                                                     *  2. Translation Ready String
                                                     *  ---------------------------
                                                     */
                                                    esc_attr__( 'Look out for more details about our wedding coming soon.', 'sdweddingdirectory-guest-list' )
                                                )

                    ] ) );
                }

                else{

                    /**
                     *  Security Error
                     *  --------------
                     */
                    SDWeddingDirectory_AJAX:: security_issue_found();
                }
            }

            else{

                /**
                 *  Security Error
                 *  --------------
                 */
                SDWeddingDirectory_AJAX:: security_issue_found();
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Guest_List_Form_Filter:: get_instance();
}