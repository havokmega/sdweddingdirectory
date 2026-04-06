<?php
/**
 *  SDWeddingDirectory Venue Request Quote AJAX Script Action HERE
 *  --------------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Request_Quote_AJAX' ) && class_exists( 'SDWeddingDirectory_Request_Quote_Database' ) ){

    /**
     *  SDWeddingDirectory Venue Request Quote AJAX Script Action HERE
     *  --------------------------------------------------------
     */
    class SDWeddingDirectory_Request_Quote_AJAX extends SDWeddingDirectory_Request_Quote_Database{

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
                         *  1. Review Post Publish
                         *  ----------------------
                         */
                        esc_attr( 'sdweddingdirectory_venue_request_form_action' ),

                        /**
                         *  2. Removed request via vendor
                         *  -----------------------------
                         */
                        esc_attr( 'sdweddingdirectory_vendor_remove_request_quote' ),

                        /**
                         *  3. Request Quote Fields
                         *  -----------------------
                         */
                        esc_attr( 'sdweddingdirectory_venue_request_form_fields_action' ),

                        /**
                         *  4. Update Lead Status
                         *  ---------------------
                         */
                        esc_attr( 'sdwd_update_lead_status' ),

                        /**
                         *  5. Add Activity Tag
                         *  -------------------
                         */
                        esc_attr( 'sdwd_add_activity_tag' ),

                        /**
                         *  6. Add Lead Note
                         *  ----------------
                         */
                        esc_attr( 'sdwd_add_lead_note' ),

                        /**
                         *  7. Get Lead History
                         *  -------------------
                         */
                        esc_attr( 'sdwd_get_lead_history' ),

                        /**
                         *  8. Update Booking Capacity
                         *  --------------------------
                         */
                        esc_attr( 'sdwd_update_booking_capacity' )
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
         *  1. Post Publish
         *  ---------------
         */
        public static function sdweddingdirectory_venue_request_form_action(){

            global $current_user, $post, $wp_query;

            /**
             *  Check Security
             *  --------------
             */
            $_condition_1       =   isset( $_POST['request_quote_venue_id'] ) && $_POST['request_quote_venue_id'] !== '';

            /**
             *  Venue ID Assign if have ?
             *  ---------------------------
             */
            $_venue_id        =   $_condition_1 ? absint( $_POST['request_quote_venue_id'] ) : absint( '0' );

            /**
             *  Check Venue ID is not empty!
             *  ------------------------------
             */
            $_condition_2       =   ( isset( $_venue_id ) && $_venue_id !== absint( '0' ) )

                                    ?   true

                                    :   false;
            /**
             *  Request Quote Security
             *  ----------------------
             */
            $_condition_3       =   ( isset( $_POST[ 'security' ] ) && $_POST[ 'security' ] !== '' )

                                    ?   wp_verify_nonce( 

                                            $_POST[ 'security' ],

                                            esc_attr( "sdweddingdirectory_request_quote_security-{$_REQUEST['request_quote_couple_id']}" )
                                        )

                                    :   false;

            /**
             *  Couple Security
             *  ---------------
             */
            $_condition_4       =   ( isset( $_POST[ 'couple_id_security' ] ) && $_POST[ 'couple_id_security' ] !== '' )

                                    ?   wp_verify_nonce( 

                                            $_POST[ 'couple_id_security' ], 

                                            esc_attr( "sdweddingdirectory_request_quote_couple_id_security-{$_REQUEST['request_quote_couple_id']}" ) 
                                        )

                                    :   false;

            /**
             *  Current User is ( COUPLE ) ?
             *  ----------------------------
             */
            $_condition_5           =   parent:: is_couple();

            /**
             *  This Venue Owner is ( VENDOR ) Confirm after process is start
             *  ---------------------------------------------------------------
             */
            $_post_type             =   get_post_type( absint( $_venue_id ) );

            $_condition_6           =   ( $_post_type === esc_attr( 'vendor' ) )

                                        ?   true

                                        :   parent:: venue_author_is_vendor( absint( $_venue_id ) );


            /**
             *  Security Check after process to submit request quote
             *  ----------------------------------------------------
             */
            if( $_condition_1 && $_condition_2 && $_condition_3 && $_condition_4 && $_condition_5 && $_condition_6 ){

                /**
                 *  Process to send request for vendor
                 *  ----------------------------------
                 */
                $_vendor_id     =   ( $_post_type === esc_attr( 'vendor' ) )

                                    ?   absint( $_venue_id )

                                    :   parent:: venue_author_post_id( absint( $_venue_id ) );

                /**
                 *  Couple Post ID
                 *  --------------
                 */
                $_couple_id     =   parent:: post_id();

                /**
                 *  Check user already submited review ?
                 *  ------------------------------------
                 *  Multiple Request Accepted with one venue
                 *  ------------------------------------------
                 */
                $_have_request =    parent:: couple_already_submit_request( array(

                                        /**
                                         *  1. Get the venue ID
                                         *  ---------------------
                                         */
                                        'venue_id'    =>   absint( $_venue_id ),

                                        /**
                                         *  2. Couple Post ID
                                         *  -----------------
                                         */
                                        'couple_id'     =>   absint( $_couple_id )

                                    ) );

                /**
                 *  Couple still not request for this request
                 *  -----------------------------------------
                 */
                if( $_have_request == absint( '0' ) ){

                    /**
                     *  Request Register Post Title ( Full Name of Couple )
                     *  ---------------------------------------------------
                     */
                    $_post_title    =   esc_attr( $_POST['request_quote_name'] );

                    /**
                     *  Request Post Register
                     *  ---------------------
                     */
                    $_request_register_post_id  =   wp_insert_post( array(
                        
                            'post_author'       =>  absint( '1' ),

                            'post_name'         =>  sanitize_title( $_post_title ),

                            'post_title'        =>  esc_attr( $_post_title ),

                            'post_content'      =>  '',

                            'post_status'       =>  sdweddingdirectory_option( 'request_quote_approval', 'publish' ),

                            'post_type'         =>  esc_attr( 'venue-request' )
                    ) );

                    /**
                     *  Have WordPress Error ?
                     *  ----------------------
                     */
                    if ( is_wp_error( $_request_register_post_id ) ) {

                        /**
                         *  Have WP Error ?
                         *  ---------------
                         */
                        die( json_encode( array(

                                'notice'    =>  absint( '2' ),

                                'message'   =>  $_request_register_post_id->get_error_messages()
                        ) ) );
                    }

                    /**
                     *  No Error Found
                     *  --------------
                     */
                    else{

                        /**
                         *  Request Quote Form Data
                         *  -----------------------
                         */
                        $_REQUEST_FORM_DATA                 =   [

                            'couple_id'                     =>  absint( $_couple_id ),

                            'vendor_id'                     =>  absint( $_vendor_id ),

                            'venue_id'                    =>  absint( $_venue_id ),

                            'request_quote_guest'           =>  isset( $_POST[ 'request_quote_guest' ] ) && ! empty( $_POST[ 'request_quote_guest' ] )

                                                                ?   esc_attr( $_POST[ 'request_quote_guest' ] )

                                                                :   '',

                            'request_quote_budget'          =>  isset( $_POST[ 'request_quote_budget' ] ) && ! empty( $_POST[ 'request_quote_budget' ] )

                                                                ?   esc_attr( $_POST[ 'request_quote_budget' ] )

                                                                :   '',

                            'request_quote_name'            =>  esc_attr( $_POST[ 'request_quote_name' ] ),

                            'request_quote_email'           =>  sanitize_email( $_POST[ 'request_quote_email' ] ),

                            'request_quote_contact'         =>  esc_attr( $_POST[ 'request_quote_contact' ] ),

                            'request_quote_wedding_date'    =>  esc_attr( $_POST[ 'request_quote_wedding_date' ] ),

                            'request_quote_message'         =>  esc_textarea( $_POST[ 'request_quote_message' ] ),

                            'request_quote_contact_option'  =>  esc_attr( $_POST[ 'request_quote_contact_option' ] ),

                            'request_quote_select_year'     =>  esc_attr( $_POST[ 'request_quote_select_year' ] ),

                            'request_quote_select_month'    =>  esc_attr( $_POST[ 'request_quote_select_month' ] ),

                            'request_quote_flexible_date'   =>  isset( $_POST['request_quote_flexible_date'] ) && $_POST['request_quote_flexible_date'] == true 

                                                                ?   esc_attr( 'on' )

                                                                :   esc_attr( 'off' )
                        ];

                        /**
                         *  Update Request Quote Data
                         *  -------------------------
                         */
                        if( parent:: _is_array( $_REQUEST_FORM_DATA ) ){

                            /**
                             *  Have Request Form Data ?
                             *  ------------------------
                             */
                            foreach ( $_REQUEST_FORM_DATA as $key => $value) {
                                
                                update_post_meta(

                                    /**
                                     *  Post ID
                                     *  -------
                                     */
                                    absint( $_request_register_post_id ),

                                    /**
                                     *  Meta Key
                                     *  --------
                                     */
                                    sanitize_key( $key ),

                                    /**
                                     *  Value
                                     *  -----
                                     */
                                    $value
                                );
                            }
                        }

                        /**
                         *  Set initial lead status
                         *  -----------------------
                         */
                        update_post_meta( absint( $_request_register_post_id ), sanitize_key( 'sdwd_lead_status' ), sanitize_key( 'new' ) );

                        parent:: add_lead_history( absint( $_request_register_post_id ), [
                            'type'    => 'status_change',
                            'from_status' => '',
                            'to_status'   => 'new',
                            'from_label'  => '',
                            'to_label'    => esc_html__( 'New', 'sdweddingdirectory' ),
                        ] );

                        /**
                         *  Checking Couple Contact Number is emtpy!
                         *  ----------------------------------------
                         */
                        $couple_contact     =   get_post_meta( $_couple_id, sanitize_key( 'user_contact' ), true );

                        if( empty( $couple_contact ) ){

                            update_post_meta( $_couple_id, sanitize_key( 'user_contact' ), esc_attr( $_POST[ 'request_quote_contact' ] ) );
                        }

                        /**
                         *  Request Quote Data convert as variable
                         *  --------------------------------------
                         */
                        extract( $_REQUEST_FORM_DATA );

                        /**
                         *  Email Fields
                         *  ------------
                         */
                        $fields       =       [];

                        if( ! empty( $request_quote_name )  ){

                            $fields[ 'Name' ]             =   $request_quote_name;    
                        }

                        if( ! empty(  $request_quote_guest  )  ){

                            $fields[ 'Number Of Guests' ]    =   $request_quote_guest;
                        }

                        if( ! empty( $request_quote_budget )  ){

                            $fields[ 'Budget' ]             =   $request_quote_budget;    
                        }

                        if( ! empty( $request_quote_email )  ){

                            $fields[ 'Email' ]            =   $request_quote_email;                            
                        }

                        if( ! empty( $request_quote_contact )  ){

                            $fields[ 'Contact' ]          =   $request_quote_contact;                            
                        }

                        if( ! empty( $request_quote_wedding_date )  ){

                            $fields[ 'Wedding Date' ]     =   $request_quote_wedding_date;                            
                        }

                        if( ! empty( $request_quote_message )  ){

                            $fields[ 'Message' ]          =   $request_quote_message;                            
                        }

                        if( ! empty( $request_quote_contact_option )  ){

                            $fields[ 'Contact Via' ]   =   esc_attr( parent:: vendor_contact_couple_via( $request_quote_contact_option ) );
                        }

                        if( ! empty( $request_quote_select_year ) && ! empty( $request_quote_select_month )  ){

                            $fields[ 'Event Date' ]      =   $request_quote_select_month . ' / ' . $request_quote_select_year;
                        }

                        if( ! empty( $request_quote_flexible_date ) ){

                            if(  $request_quote_flexible_date  ==  'on'  ){

                                $fields[ 'Date is Flexible' ]    =   'Yes';
                            }

                            elseif(  $request_quote_flexible_date  ==  'off'  ){

                                $fields[ 'Date is Flexible' ]    =   'No';
                            }
                        }

                        /**
                         *  Collection
                         *  ----------
                         */
                        $fields_collection          =   '';

                        /**
                         *  Fields
                         *  ------
                         */
                        if(  parent:: _is_array(  $fields  )  ){

                            foreach ( $fields as $key => $value) {
                                    
                                $fields_collection          .=      sprintf( '<p>%1$s : %2$s</p>', $key, $value );
                            }

                            /**
                             *  Request Quote Form Fields
                             *  -------------------------
                             */
                            $_REQUEST_FORM_DATA[ 'request_quote_form_fields' ]      =       $fields_collection;
                        }

                        /**
                         *  Email Process
                         *  -------------
                         */
                        if( class_exists( 'SDWeddingDirectory_Email' ) && sdweddingdirectory_option( 'request_quote_approval', 'publish' ) == esc_attr( 'publish' ) ){

                            /**
                             *  Get vendor / couple email ids
                             *  -----------------------------
                             */
                            $vendor_email = get_post_meta( absint( $vendor_id ), sanitize_key( 'user_email' ), true );

                            if( empty( $vendor_email ) ){

                                $vendor_email = get_post_meta( absint( $vendor_id ), sanitize_key( 'company_email' ), true );
                            }

                            $_EMAIL_ARGS        =   array_merge( array_replace(

                                /**
                                 *  Couple Send Request Data
                                 *  ------------------------
                                 */
                                $_REQUEST_FORM_DATA,

                                /**
                                 *  Add New Data via Vendor and Couple Post ID using
                                 *  ------------------------------------------------
                                 */
                                array(

                                    'vendor_email'      =>  sanitize_email( $vendor_email ),

                                    'couple_email'      =>  get_post_meta(  absint( $couple_id ),  sanitize_key( 'user_email' ), true ),

                                    'couple_username'   =>  sanitize_user(

                                                                esc_attr( get_the_title( absint( $couple_id ) ) ),
                                                            ),

                                    'vendor_username'   =>  sanitize_user(

                                                                esc_attr( get_the_title( absint( $vendor_id ) ) ),
                                                            ),

                                    'venue_name'      =>  esc_attr( get_the_title( absint( $venue_id ) ) ),

                                    'request_quote_contact_option'  =>  esc_attr( parent:: vendor_contact_couple_via( $request_quote_contact_option ) )
                                )

                            ) );

                            /**
                             *  Extract Args
                             *  ------------
                             */
                            extract( $_EMAIL_ARGS );

                            /**
                             *  Couple Sending Email
                             *  --------------------
                             */
                            SDWeddingDirectory_Email:: sending_email( array(

                                /**
                                 *  1. Setting ID : Email PREFIX_
                                 *  -----------------------------
                                 */
                                'setting_id'        =>      esc_attr( 'couple-request-venue' ),

                                /**
                                 *  2. Sending Email ID
                                 *  -------------------
                                 */
                                'sender_email'      =>      sanitize_email( $couple_email ),

                                /**
                                 *  3. Email Data Key and Value as Setting Body Have {{...}} all
                                 *  ------------------------------------------------------------
                                 */
                                'email_data'        =>      $_EMAIL_ARGS

                            ) );

                            /**
                             *  Vendor Sending Email
                             *  --------------------
                             */
                            SDWeddingDirectory_Email:: sending_email( array(

                                /**
                                 *  1. Setting ID : Email PREFIX_
                                 *  -----------------------------
                                 */
                                'setting_id'        =>      esc_attr( 'vendor-venue-form-request' ),

                                /**
                                 *  2. Sending Email ID
                                 *  -------------------
                                 */
                                'sender_email'      =>      sanitize_email( $vendor_email ),

                                /**
                                 *  3. Email Data Key and Value as Setting Body Have {{...}} all
                                 *  ------------------------------------------------------------
                                 */
                                'email_data'        =>      $_EMAIL_ARGS

                            ) );
                        }

                        /**
                         *  Successfully Submited Request Quote Form
                         *  ----------------------------------------
                         */
                        die( json_encode( array(

                            'notice'        =>   absint( '1' ),
                          
                            'message'       =>   esc_attr__( 'Your Request Send Successfully!', 'sdweddingdirectory-request-quote' ),

                        ) ) );
                    }

                }else{

                    /**
                     *  Couple Already Submit Request for this venue
                     *  ----------------------------------------------
                     */
                    die( json_encode( array(

                      'notice'    =>  absint( '2' ),

                      'message'   =>  sprintf(

                                            /**
                                             *  1. Translation Ready String
                                             *  ---------------------------
                                             */
                                            esc_attr__('Hello %1$s, You already request for this venue.','sdweddingdirectory-request' ),

                                            /**
                                             *  2. Login username
                                             *  -----------------
                                             */
                                            esc_attr( parent:: user_login() )
                                      )
                    ) ) );
                }

            }else{
                
                /**
                 *   Security Issue Found
                 *   --------------------
                 */
                parent:: security_issue_found();
            }
        }

        /**
         *  2. Removed request via vendor
         *  -----------------------------
         */
        public static function sdweddingdirectory_vendor_remove_request_quote(){

            global $current_user, $post, $wp_query;

            /**
             *  Venue Security
             *  ----------------
             */
            $_condition_1       =   isset( $_POST['venue_id'] ) && $_POST['venue_id'] !== '' && $_POST['venue_id'] !== absint( '0' );

            $_condition_2       =   ( isset( $_POST[ 'venue_security' ] ) && $_POST[ 'venue_security' ] !== '' )

                                    ?   wp_verify_nonce( $_POST[ 'venue_security' ], 

                                            esc_attr( "sdweddingdirectory_venue_unique_id-{$_REQUEST['venue_id']}" )
                                        )

                                    :   false;
            /**
             *  Request Security
             *  ----------------
             */
            $_condition_3       =   isset( $_POST['request_id'] ) && $_POST['request_id'] !== '' && $_POST['request_id'] !== absint( '0' );

            $_condition_4       =   ( isset( $_POST[ 'request_security' ] ) && $_POST[ 'request_security' ] !== '' )

                                    ?   wp_verify_nonce( $_POST[ 'request_security' ], 

                                            esc_attr( "sdweddingdirectory_request_quote_unique_id-{$_REQUEST['request_id']}" ) 
                                        )

                                    :   false;

            /**
             *  Is Vendor ?
             *  -----------
             */
            $_condition_3       =   parent:: is_vendor();

            /**
             *  This Request for current login vendor ?
             *  ---------------------------------------
             */
            $_request_quote_vendor_id   =   absint( 

                                                get_post_meta( 

                                                    /**
                                                     *  Request Quote Post ID
                                                     *  ---------------------
                                                     */
                                                    absint( $_POST['request_id'] ),

                                                    /**
                                                     *  Meta Key
                                                     *  --------
                                                     */
                                                    sanitize_key( 'vendor_id' ),

                                                    /**
                                                     *  TRUE
                                                     *  ----
                                                     */
                                                    true
                                                ) 
                                            );

            /**
             *  Current Login Vendor Post ID Set With Request Data Vendor ID ?
             *  --------------------------------------------------------------
             */
            $_condition_4       =   (  absint( parent:: post_id() )  ==  absint( $_request_quote_vendor_id )  );

            /**
             *  Have Request Quote Data ?
             *  -------------------------
             */
            if(  $_condition_1 && $_condition_2 && $_condition_3 && $_condition_4 ){

                /**
                 *  Post Move to Trash
                 *  ------------------
                 */
                wp_update_post( array(

                    'ID'            =>  absint( $_POST['request_id'] ),

                    'post_status'   =>  esc_attr( 'trash' )
                ));

                /**
                 *  Successfully Removed Request
                 *  ----------------------------
                 */
                die( json_encode( array(

                    'notice'    =>  absint( '1' ),

                    'message'   =>  esc_attr__( 'Request Removed Successfully!', 'sdweddingdirectory-request-quote' )

                ) ) );

            }else{

                /**
                 *  Security Issue Found!
                 *  ---------------------
                 */
                parent:: security_issue_found();
            }
        }

        /**
         *  Request Quote : Form Fields Check is enable as category condition ?
         *  -------------------------------------------------------------------
         */
        public static function sdweddingdirectory_venue_request_form_fields_action(){

            $_condition_1   =   isset( $_POST[ 'venue_id' ] ) && parent:: _have_data( $_POST[ 'venue_id' ] );

            /**
             *  Have Data ?
             *  -----------
             */
            if( $_condition_1 ){

                $_model_id              =   esc_attr( parent:: _rand() );

                /**
                 *  Request Form
                 *  ------------
                 */
                $_request_post_content  =

                sprintf( 

                    '<div class="modal fade sdweddingdirectory_request_quote_popup_handler" id="%1$s" tabindex="-1" aria-labelledby="%1$s" aria-hidden="true">

                        <div class="modal-dialog modal-dialog-centered register-tab">

                            <div class="modal-content">

                                <div class="modal-body p-0">

                                    <div class="d-flex justify-content-between align-items-center p-3 px-4 bg-light-gray">

                                        <h3 class="m-0">%2$s</h3>

                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>

                                    </div>

                                    <div class="card-body">

                                        <form id="%4$s" class="sdweddingdirectory_venue_request_quote" method="post">

                                            <div class="request-quote-form">%3$s</div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>',

                    /**
                     *  1. Request Quote - Model ID
                     *  ---------------------------
                     */
                    esc_attr( $_model_id ),

                    /**
                     *  2. Translation Ready String
                     *  ---------------------------
                     */
                    esc_attr__( 'Message Supplier', 'sdweddingdirectory-request-quote' ),

                    /**
                     *  3. Fields
                     *  ---------
                     */
                    apply_filters( 'sdweddingdirectory/request-quote/form-fields',

                        /**
                         *  1. Default
                         *  ----------
                         */
                        '',

                        /**
                         *  2. Args
                         *  -------
                         */
                        [
                          'venue_post_id'     =>      absint( $_POST[ 'venue_id' ] ),

                          'couple_post_id'      =>      parent:: is_couple() && is_user_logged_in()

                                                        ?   absint( parent:: post_id() )

                                                        :   absint( '0' )
                        ]
                    ),

                    /**
                     *  4. Form ID
                     *  ----------
                     */
                    esc_attr( parent:: _rand() )
                );

                /**
                 *  Get Request Quote Fields
                 *  ------------------------
                 */
                die( json_encode( array(

                    /**
                     *  Load Fields
                     *  -----------
                     */
                    'request_quote_popup_data'      =>  $_request_post_content,

                    'request_quote_popup_id'        =>  esc_attr( $_model_id )

                ) ) );
            }
        }

        /**
         *  4. Update Lead Status
         *  ---------------------
         */
        public static function sdwd_update_lead_status(){

            $request_id = isset( $_POST['request_id'] ) ? absint( $_POST['request_id'] ) : 0;
            $new_status = isset( $_POST['status'] ) ? sanitize_key( $_POST['status'] ) : '';
            $nonce      = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';

            if( ! wp_verify_nonce( $nonce, 'sdwd_lead_management_' . $request_id ) ){
                parent:: security_issue_found();
            }

            if( ! parent:: is_vendor() || empty( $request_id ) || empty( $new_status ) ){
                parent:: security_issue_found();
            }

            $valid_statuses = array_keys( parent:: lead_status_options() );

            if( ! in_array( $new_status, $valid_statuses, true ) ){
                die( json_encode( [ 'notice' => 2, 'message' => esc_attr__( 'Invalid status.', 'sdweddingdirectory' ) ] ) );
            }

            $old_status = get_post_meta( $request_id, sanitize_key( 'sdwd_lead_status' ), true );

            if( empty( $old_status ) ){
                $old_status = 'new';
            }

            update_post_meta( $request_id, sanitize_key( 'sdwd_lead_status' ), sanitize_key( $new_status ) );

            $statuses = parent:: lead_status_options();

            parent:: add_lead_history( $request_id, [
                'type'        => 'status_change',
                'from_status' => $old_status,
                'to_status'   => $new_status,
                'from_label'  => isset( $statuses[ $old_status ] ) ? $statuses[ $old_status ] : $old_status,
                'to_label'    => isset( $statuses[ $new_status ] ) ? $statuses[ $new_status ] : $new_status,
            ] );

            if( $new_status === 'booked' ){

                $wedding_date = get_post_meta( $request_id, sanitize_key( 'request_quote_wedding_date' ), true );

                if( ! empty( $wedding_date ) && preg_match( '/^\d{4}-\d{2}-\d{2}$/', $wedding_date ) ){

                    $vendor_id = absint( get_post_meta( $request_id, sanitize_key( 'vendor_id' ), true ) );

                    if( $vendor_id > 0 ){

                        $booked_dates = get_post_meta( $vendor_id, sanitize_key( 'vendor_booked_dates' ), true );

                        if( ! is_array( $booked_dates ) ){
                            $booked_dates = [];
                        }

                        if( ! in_array( $wedding_date, $booked_dates, true ) ){
                            $booked_dates[] = $wedding_date;
                            update_post_meta( $vendor_id, sanitize_key( 'vendor_booked_dates' ), $booked_dates );
                        }

                        parent:: add_lead_history( $request_id, [
                            'type'    => 'auto_booked',
                            'content' => sprintf( esc_attr__( 'Date %s automatically marked as booked on vendor calendar.', 'sdweddingdirectory' ), $wedding_date ),
                        ] );
                    }
                }
            }

            die( json_encode( [
                'notice'       => 1,
                'message'      => esc_attr__( 'Lead status updated.', 'sdweddingdirectory' ),
                'status'       => $new_status,
                'status_label' => isset( $statuses[ $new_status ] ) ? $statuses[ $new_status ] : $new_status,
            ] ) );
        }

        /**
         *  5. Add Activity Tag
         *  -------------------
         */
        public static function sdwd_add_activity_tag(){

            $request_id = isset( $_POST['request_id'] ) ? absint( $_POST['request_id'] ) : 0;
            $tag        = isset( $_POST['tag'] ) ? sanitize_key( $_POST['tag'] ) : '';
            $nonce      = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';

            if( ! wp_verify_nonce( $nonce, 'sdwd_lead_management_' . $request_id ) ){
                parent:: security_issue_found();
            }

            if( ! parent:: is_vendor() || empty( $request_id ) || empty( $tag ) ){
                parent:: security_issue_found();
            }

            $valid_tags = array_keys( parent:: activity_tag_options() );

            if( ! in_array( $tag, $valid_tags, true ) ){
                die( json_encode( [ 'notice' => 2, 'message' => esc_attr__( 'Invalid activity tag.', 'sdweddingdirectory' ) ] ) );
            }

            $tags = parent:: activity_tag_options();

            parent:: add_lead_history( $request_id, [
                'type'    => 'activity',
                'tag'     => $tag,
                'content' => $tags[ $tag ],
            ] );

            die( json_encode( [
                'notice'  => 1,
                'message' => sprintf( esc_attr__( 'Activity logged: %s', 'sdweddingdirectory' ), $tags[ $tag ] ),
            ] ) );
        }

        /**
         *  6. Add Lead Note
         *  ----------------
         */
        public static function sdwd_add_lead_note(){

            $request_id = isset( $_POST['request_id'] ) ? absint( $_POST['request_id'] ) : 0;
            $note       = isset( $_POST['note'] ) ? sanitize_textarea_field( $_POST['note'] ) : '';
            $nonce      = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';

            if( ! wp_verify_nonce( $nonce, 'sdwd_lead_management_' . $request_id ) ){
                parent:: security_issue_found();
            }

            if( ! parent:: is_vendor() || empty( $request_id ) || empty( $note ) ){
                die( json_encode( [ 'notice' => 2, 'message' => esc_attr__( 'Note cannot be empty.', 'sdweddingdirectory' ) ] ) );
            }

            parent:: add_lead_history( $request_id, [
                'type'    => 'note',
                'content' => $note,
            ] );

            die( json_encode( [
                'notice'  => 1,
                'message' => esc_attr__( 'Note added.', 'sdweddingdirectory' ),
            ] ) );
        }

        /**
         *  7. Get Lead History
         *  -------------------
         */
        public static function sdwd_get_lead_history(){

            $request_id = isset( $_POST['request_id'] ) ? absint( $_POST['request_id'] ) : 0;
            $nonce      = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';

            if( ! wp_verify_nonce( $nonce, 'sdwd_lead_management_' . $request_id ) ){
                parent:: security_issue_found();
            }

            if( ! parent:: is_vendor() || empty( $request_id ) ){
                parent:: security_issue_found();
            }

            $history = get_post_meta( $request_id, sanitize_key( 'sdwd_lead_history' ), true );

            if( ! is_array( $history ) ){
                $history = [];
            }

            $html = '';

            if( empty( $history ) ){

                $html = sprintf( '<p class="text-muted small">%s</p>', esc_html__( 'No history yet.', 'sdweddingdirectory' ) );

            } else {

                $html .= '<div class="sdwd-history-list">';

                foreach( $history as $entry ){

                    $time_ago = human_time_diff( absint( $entry['timestamp'] ), current_time( 'timestamp' ) );
                    $date_str = wp_date( 'M j, Y g:i A', absint( $entry['timestamp'] ) );

                    $icon  = 'fa-circle-o';
                    $label = '';
                    $class = '';

                    if( $entry['type'] === 'status_change' ){

                        $icon  = 'fa-exchange';
                        $class = 'sdwd-history-status';
                        $label = sprintf(
                            esc_html__( 'Status changed from %1$s to %2$s', 'sdweddingdirectory' ),
                            '<strong>' . esc_html( $entry['from_label'] ) . '</strong>',
                            '<strong>' . esc_html( $entry['to_label'] ) . '</strong>'
                        );

                    } elseif( $entry['type'] === 'activity' ){

                        $icon  = 'fa-bolt';
                        $class = 'sdwd-history-activity';
                        $label = esc_html( $entry['content'] );

                    } elseif( $entry['type'] === 'note' ){

                        $icon  = 'fa-sticky-note-o';
                        $class = 'sdwd-history-note';
                        $label = esc_html( $entry['content'] );

                    } elseif( $entry['type'] === 'auto_booked' ){

                        $icon  = 'fa-calendar-check-o';
                        $class = 'sdwd-history-auto';
                        $label = esc_html( $entry['content'] );
                    }

                    $html .= sprintf(
                        '<div class="sdwd-history-entry %s">
                            <div class="d-flex align-items-start">
                                <i class="fa %s mt-1 me-2 text-muted"></i>
                                <div class="flex-grow-1">
                                    <div class="small">%s</div>
                                    <div class="text-muted" style="font-size:0.75rem;" title="%s">%s ago</div>
                                </div>
                            </div>
                        </div>',
                        esc_attr( $class ),
                        esc_attr( $icon ),
                        $label,
                        esc_attr( $date_str ),
                        esc_html( $time_ago )
                    );
                }

                $html .= '</div>';
            }

            die( json_encode( [
                'notice' => 1,
                'html'   => $html,
            ] ) );
        }

        /**
         *  8. Update Booking Capacity
         *  --------------------------
         */
        public static function sdwd_update_booking_capacity(){

            $vendor_id = isset( $_POST['vendor_id'] ) ? absint( $_POST['vendor_id'] ) : 0;
            $capacity  = isset( $_POST['capacity'] ) ? absint( $_POST['capacity'] ) : 1;
            $nonce     = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';

            if( ! wp_verify_nonce( $nonce, 'sdwd_update_capacity_' . $vendor_id ) ){
                parent:: security_issue_found();
            }

            if( ! parent:: is_vendor() || empty( $vendor_id ) ){
                parent:: security_issue_found();
            }

            if( $capacity < 1 ){
                $capacity = 1;
            }

            update_post_meta( $vendor_id, sanitize_key( 'sdwd_daily_booking_capacity' ), absint( $capacity ) );

            die( json_encode( [
                'notice'  => 1,
                'message' => esc_attr__( 'Booking capacity updated.', 'sdweddingdirectory' ),
            ] ) );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Request_Quote_AJAX:: get_instance();
}
