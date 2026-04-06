<?php
/**
 *  SDWeddingDirectory Couple Guest List AJAX Script Action HERE
 *  ----------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Guest_List_AJAX' ) && class_exists( 'SDWeddingDirectory_Guest_List_Database' ) ){

    /**
     *  SDWeddingDirectory Couple Guest List AJAX Script Action HERE
     *  ----------------------------------------------------
     */
    class SDWeddingDirectory_Guest_List_AJAX extends SDWeddingDirectory_Guest_List_Database{

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
                         *  1. Removed Menu List
                         *  --------------------
                         */
                        esc_attr( 'sdweddingdirectory_guest_list_menu_removed' ),

                        /**
                         *  2. Add Menu List
                         *  ----------------
                         */
                        esc_attr( 'sdweddingdirectory_guest_list_menu_add' ),

                        /**
                         *  3. GROUP : Removed
                         *  ------------------
                         */
                        esc_attr( 'sdweddingdirectory_group_item_removed' ),

                        /**
                         *  4. GROUP : ADDED
                         *  ----------------
                         */
                        esc_attr( 'sdweddingdirectory_guest_list_group_add' ),

                        /**
                         *  5. Event : Removed
                         *  ------------------
                         */
                        esc_attr( 'sdweddingdirectory_event_list_removed' ),

                        /**
                         *  6. Event : ADDED
                         *  ----------------
                         */
                        esc_attr( 'sdweddingdirectory_event_list_add' ),

                        /**
                         *  7. Create Event
                         *  ----------------
                         */
                        esc_attr( 'sdweddingdirectory_create_new_event' ),

                        /**
                         *  8. Create Event
                         *  ----------------
                         */
                        esc_attr( 'sdweddingdirectory_create_new_guest_data' ),

                        /**
                         *  9. Guest Meal Update for each event
                         *  -----------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_guest_event_meal_action' ),

                        /**
                         *  10. Guest Attendence Update for each event
                         *  ------------------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_guest_event_attendance_action' ),

                        /**
                         *  11. Get Guest Information
                         *  -------------------------
                         */
                        esc_attr( 'sdweddingdirectory_get_guest_info_action' ),

                        /**
                         *  12. Remove Guest Information
                         *  ----------------------------
                         */
                        esc_attr( 'sdweddingdirectory_remove_guest_info_action' ),

                        /**
                         *  13. Update Guest Information
                         *  ----------------------------
                         */
                        esc_attr( 'sdweddingdirectory_update_guest_data' ),

                        /**
                         *  14. Update Event Data in Edit Form
                         *  ----------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_update_event_form_data' ),

                        /**
                         *  15. Update Event Form Data
                         *  --------------------------
                         */
                        esc_attr( 'sdweddingdirectory_update_event_data' ),

                        /**
                         *  16. Remove Event
                         *  ----------------
                         */
                        esc_attr( 'sdweddingdirectory_remove_event_data' ),

                        /**
                         *  17. Update Guest Group
                         *  ----------------------
                         */
                        esc_attr( 'sdweddingdirectory_guest_group_action' ),

                        /**
                         *  18. Guest Data export ( CSV )
                         *  -----------------------------
                         */
                        esc_attr( 'guest_list_csv_download' ),

                        /**
                         *  19. View Summary Graph
                         *  ----------------------
                         */
                        esc_attr( 'sdweddingdirectory_event_summary_load' ),

                        /**
                         *  20. Guest Invitation Sent
                         *  -------------------------
                         */
                        esc_attr( 'sdweddingdirectory_invitation_sent' ),

                        /**
                         *  21. Guest List Import
                         *  ---------------------
                         */
                        esc_attr( 'sdweddingdirectory_guest_list_import' ),

                        /**
                         *  22. Update Guest Email ID
                         *  -------------------------
                         */
                        esc_attr( 'sdweddingdirectory_guest_email_update' ),

                        /**
                         *  23. Couple Sending RSVP Email
                         *  -----------------------------
                         */
                        esc_attr( 'sdweddingdirectory_couple_sending_guest_rsvp' )
                    );

                    /**
                     *  If have action then load this object members
                     *  --------------------------------------------
                     */
                    if( in_array( $action, $allowed_actions, true ) ) {

                        /**
                         *  Couple dashboard endpoints are authenticated-only
                         *  -------------------------------------------------
                         */
                        add_action( 'wp_ajax_' . $action, function() use ( $action ) {

                            self:: authorize_guest_list_request();

                            call_user_func( [ __CLASS__, $action ] );
                        } );
                    }
                }
            }
        }

        /**
         *  Validate nonce against accepted guest-list tokens
         *  -------------------------------------------------
         */
        private static function valid_guest_list_nonce( $nonce = '' ){

            if( empty( $nonce ) ){

                return false;
            }

            $nonce_actions = [
                'sdweddingdirectory_guest_list_security',
                'sdweddingdirectory_guest_group_security',
                'add_new_guest_security',
                'add_new_guest_event_security',
                'edit_guest_event_security',
                'edit_guest_member_security',
                'import_guest_security',
                'add_new_guest_menu_security',
                'sdweddingdirectory_rsvp_email_security',
            ];

            foreach( $nonce_actions as $nonce_action ){

                if( wp_verify_nonce( $nonce, $nonce_action ) ){

                    return true;
                }
            }

            return false;
        }

        /**
         *  Authorization gate for all guest-list AJAX handlers
         *  ---------------------------------------------------
         */
        private static function authorize_guest_list_request(){

            if( ! is_user_logged_in() || ! parent:: is_couple() ){

                wp_send_json_error( [
                    'message'   =>  esc_attr__( 'Unauthorized.', 'sdweddingdirectory-guest-list' ),
                    'notice'    =>  absint( '0' ),
                ] );
            }

            $_security = isset( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';

            if( ! self:: valid_guest_list_nonce( $_security ) ){

                wp_send_json_error( [
                    'message'   =>  esc_attr__( 'Invalid security token.', 'sdweddingdirectory-guest-list' ),
                    'notice'    =>  absint( '0' ),
                ] );
            }
        }

        /**
         *  If Found security issue
         *  -----------------------
         */
        public static function security_issue_found(){

            die( json_encode( array(

                'message'   => esc_attr__( 'Security issue!', 'sdweddingdirectory-guest-list' ),

                'notice'    => absint( '2' )

            ) ) );
        }

        /**
         *  1. MENU : Removed
         *  -----------------
         */
        public static function sdweddingdirectory_guest_list_menu_removed(){

            $_condition_1 =  isset( $_POST[ 'menu_unique_id' ] ) && $_POST[ 'menu_unique_id' ] !== '';

            if( $_condition_1 ){

                $_menu_list = parent:: menu_list();

                if( parent:: _is_array( $_menu_list ) ){

                    foreach ( $_menu_list as $key => $value) {
                        
                        /**
                         *  Match unique id after removed array *KEY*
                         *  -----------------------------------------
                         */
                        if( absint( $_POST[ 'menu_unique_id' ] ) == $value[ 'menu_unique_id' ]  ){

                            unset( $_menu_list[ $key ] );

                            /**
                             *  Update Menu list
                             *  ----------------
                             */
                            update_post_meta( 

                                /**
                                 *  1. Get Post Id
                                 *  --------------
                                 */
                                absint( parent:: post_id() ), 

                                /**
                                 *  2. Menu Key
                                 *  -----------
                                 */
                                sanitize_key( 'guest_list_menu_group' ),

                                /** 
                                 *  3. Menu Data
                                 *  ------------
                                 */
                                $_menu_list
                            );

                            /**
                             *  Success
                             *  -------
                             */
                            die( json_encode( array(

                                'message'    =>  esc_attr__( 'Removed Successfully!', 'sdweddingdirectory-guest-list' ),

                                'notice'     =>  absint( '1' )

                            ) ) );
                        }
                    }

                }else{

                    self:: security_issue_found();
                }

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  2. MENU : Add 
         *  -------------
         */
        public static function sdweddingdirectory_guest_list_menu_add(){

            $_condition_1 =  isset( $_POST[ 'menu_list' ] ) && $_POST[ 'menu_list' ] !== '';

            if( $_condition_1 ){

                $_menu_list = parent:: menu_list();

                $_new_array = [];

                $_form_data = array( array(

                    'title'             =>  esc_attr( $_POST[ 'menu_list' ] ),

                    'menu_list'         =>  esc_attr( $_POST[ 'menu_list' ] ),

                    'menu_unique_id'    =>  absint( rand() )

                ) );

                /**
                 *  Check database have menu item ?
                 *  -------------------------------
                 */
                if( parent:: _is_array( $_menu_list ) ){

                    $_new_array = array_merge_recursive( $_menu_list, $_form_data );

                }else{

                    $_new_array = $_form_data;
                }

                /**
                 *  Update Menu list
                 *  ----------------
                 */
                update_post_meta(

                    /**
                     *  1. Get Post Id
                     *  --------------
                     */
                    absint( parent:: post_id() ), 

                    /**
                     *  2. Menu Key
                     *  -----------
                     */
                    sanitize_key( 'guest_list_menu_group' ),

                    /** 
                     *  3. Menu Data
                     *  ------------
                     */
                    $_new_array

                );

                /**
                 *  Success
                 *  -------
                 */
                die( json_encode( array(

                    'message'    =>     esc_attr__( 'Menu Added Successfully!', 'sdweddingdirectory-guest-list' ),

                    'notice'     =>     absint( '1' ),

                    'menu_list'  =>     sprintf(   '<li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span>%1$s</span>
                                                        <a href="javascript:" data-remove="%2$s" class="action-links"><i class="fa fa-trash"></i></a>
                                                    </li>',

                                                    /**
                                                     *  1. Menu Name
                                                     *  ------------
                                                     */
                                                    esc_attr( $_form_data[ absint('0') ][ 'menu_list' ] ),

                                                    /**
                                                     *  2. Menu Unique ID
                                                     *  -----------------
                                                     */
                                                    absint( $_form_data[ absint('0') ][ 'menu_unique_id' ] )
                                        )

                ) ) );

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  3. GROUP : Removed
         *  ------------------
         */
        public static function sdweddingdirectory_group_item_removed(){

            $_condition_1 =  isset( $_POST[ 'group_unique_id' ] ) && $_POST[ 'group_unique_id' ] !== '';

            if( $_condition_1 ){

                $_menu_list = parent:: group_list();

                if( parent:: _is_array( $_menu_list ) ){

                    foreach ( $_menu_list as $key => $value) {
                        
                        /**
                         *  Match unique id after removed array *KEY*
                         *  -----------------------------------------
                         */
                        if( absint( $_POST[ 'group_unique_id' ] ) == $value[ 'group_unique_id' ]  ){

                            unset( $_menu_list[ $key ] );

                            /**
                             *  Update Menu list
                             *  ----------------
                             */
                            update_post_meta( 

                                /**
                                 *  1. Get Post Id
                                 *  --------------
                                 */
                                absint( parent:: post_id() ), 

                                /**
                                 *  2. Menu Key
                                 *  -----------
                                 */
                                sanitize_key( 'guest_list_group' ),

                                /** 
                                 *  3. Menu Data
                                 *  ------------
                                 */
                                $_menu_list
                            );

                            /**
                             *  Success
                             *  -------
                             */
                            die( json_encode( array(

                                'message'    =>  esc_attr__( 'Removed Successfully!', 'sdweddingdirectory-guest-list' ),

                                'notice'     =>  absint( '1' )

                            ) ) );
                        }
                    }

                }else{

                    self:: security_issue_found();
                }

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  4. GROUP : ADD
         *  --------------
         */
        public static function sdweddingdirectory_guest_list_group_add(){

            $_condition_1   =  isset( $_POST[ 'group_name' ] ) && $_POST[ 'group_name' ] !== '';

            $_condition_2   =  wp_verify_nonce( $_POST['security'], esc_attr( 'sdweddingdirectory_guest_group_security' ) );

            $_guest_group   =  parent:: group_list();

            $_condition_3   =  parent:: _is_array( $_guest_group );

            /**
             *  Security Check
             *  --------------
             */
            if( $_condition_1 && $_condition_2 && $_condition_3 ){

                $_handaler =    [];

                if( parent:: _is_array( $_POST[ 'group_name' ] ) ){

                    foreach ( $_POST[ 'group_name' ] as $key => $value) {
                        
                        $_handaler[] =          array(

                            'title'             =>  esc_attr( $value[ 'title' ] ),

                            'group_name'        =>  esc_attr( $value[ 'group_name' ] ),

                            'group_unique_id'   =>  ( isset( $value[ 'group_unique_id' ] ) && $value[ 'group_unique_id' ] !== '' )

                                                    ?   absint( $value[ 'group_unique_id' ] )

                                                    :   absint( rand() )
                        );
                    }
                }

                /**
                 *  Update Menu list
                 *  ----------------
                 */
                update_post_meta(

                    /**
                     *  1. Get Post Id
                     *  --------------
                     */
                    absint( parent:: post_id() ), 

                    /**
                     *  2. Menu Key
                     *  -----------
                     */
                    sanitize_key( 'guest_list_group' ),

                    /** 
                     *  3. Update Guest Group Data
                     *  --------------------------
                     */
                    $_handaler
                );

                /**
                 *  Guest Group
                 *  -----------
                 */
                $_get_group_list    =   [];

                $_get_new_group     =   parent:: group_list();

                if(  parent:: _is_array( $_get_new_group )   ){

                    foreach ( $_get_new_group as $key => $value) {
                        
                        $_get_group_list[] =    array(

                                                    'id'    =>  sanitize_title( $value[ 'group_name' ] ),

                                                    'text'  =>  esc_attr( $value[ 'group_name' ] )
                                                );
                    }
                }

                /**
                 *  Success
                 *  -------
                 */
                die( json_encode( array(

                    'message'           =>  esc_attr__( 'Group Added Successfully!', 'sdweddingdirectory-guest-list' ),

                    'notice'            =>  absint( '1' ),

                    'Handler'           =>  $_handaler,

                    'select_options'    =>  $_get_group_list

                ) ) );

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  5. Event : Removed
         *  ------------------
         */
        public static function sdweddingdirectory_event_list_removed(){

            $_condition_1 =  isset( $_POST[ 'event_unique_id' ] ) && $_POST[ 'event_unique_id' ] !== '';

            if( $_condition_1 ){

                $_data = parent:: event_list();

                if( parent:: _is_array( $_data ) ){

                    foreach ( $_data as $key => $value) {
                        
                        /**
                         *  Match unique id after removed array *KEY*
                         *  -----------------------------------------
                         */
                        if( absint( $_POST[ 'event_unique_id' ] ) == $value[ 'event_unique_id' ]  ){

                            unset( $_data[ $key ] );

                            /**
                             *  Update Menu list
                             *  ----------------
                             */
                            update_post_meta( 

                                /**
                                 *  1. Get Post Id
                                 *  --------------
                                 */
                                absint( parent:: post_id() ), 

                                /**
                                 *  2. Menu Key
                                 *  -----------
                                 */
                                sanitize_key( 'guest_list_event_group' ),

                                /** 
                                 *  3. Menu Data
                                 *  ------------
                                 */
                                $_data
                            );

                            /**
                             *  Success
                             *  -------
                             */
                            die( json_encode( array(

                                'message'    =>  esc_attr__( 'Removed Successfully!', 'sdweddingdirectory-guest-list' ),

                                'notice'     =>  absint( '1' )

                            ) ) );
                        }
                    }

                }else{

                    self:: security_issue_found();
                }

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  6. Event : ADDED
         *  ----------------
         */
        public static function sdweddingdirectory_event_list_add(){

            $_condition_1 =  isset( $_POST[ 'event_list' ] ) && $_POST[ 'event_list' ] !== '';

            if( $_condition_1 ){

                $_backend_data  = parent:: event_list();

                $_new_array     = [];

                $_form_data     = array( array(

                    'title'            =>  esc_attr( $_POST[ 'event_list' ] ),

                    'event_list'       =>  esc_attr( $_POST[ 'event_list' ] ),

                    'event_unique_id'  =>  absint( rand() )

                ) );

                /**
                 *  Check database have menu item ?
                 *  -------------------------------
                 */
                if( parent:: _is_array( $_backend_data ) ){

                    $_new_array = array_merge_recursive( $_backend_data, $_form_data );

                }else{

                    $_new_array = $_form_data;
                }

                /**
                 *  Update Menu list
                 *  ----------------
                 */
                update_post_meta(

                    /**
                     *  1. Get Post Id
                     *  --------------
                     */
                    absint( parent:: post_id() ), 

                    /**
                     *  2. Menu Key
                     *  -----------
                     */
                    sanitize_key( 'guest_list_event_group' ),

                    /** 
                     *  3. Menu Data
                     *  ------------
                     */
                    $_new_array

                );

                /**
                 *  Success
                 *  -------
                 */
                die( json_encode( array(

                    'message'    =>     esc_attr__( 'Event Added Successfully!', 'sdweddingdirectory-guest-list' ),

                    'notice'     =>     absint( '1' ),

                    'event_list' =>     sprintf(   '<li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span>%1$s</span>
                                                        <a href="javascript:" data-remove="%2$s" class="action-links"><i class="fa fa-trash"></i></a>
                                                    </li>',

                                                    /**
                                                     *  1. Menu Name
                                                     *  ------------
                                                     */
                                                    esc_attr( $_form_data[ absint('0') ][ 'event_list' ] ),

                                                    /**
                                                     *  2. Menu Unique ID
                                                     *  -----------------
                                                     */
                                                    absint( $_form_data[ absint('0') ][ 'event_unique_id' ] )
                                        )

                ) ) );

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  7. Create Event
         *  ----------------
         */
        public static function sdweddingdirectory_create_new_event(){

            $_condition_1 =  isset( $_POST[ 'event_list' ] ) && $_POST[ 'event_list' ] !== '';

            $_condition_2 =  isset( $_POST[ 'event_meal' ] ) && $_POST[ 'event_meal' ] !== '';

            $_condition_3 =  isset( $_POST[ 'have_meal' ] )  && $_POST[ 'have_meal' ]  !== '';

            /**
             *  Add Event Action
             *  ----------------
             */
            if( $_condition_1 && $_condition_2 && $_condition_3 ){

                $_backend_data          =   parent:: event_list();

                $_new_event_unique_id   =   absint( rand() );

                $_new_array             =   [];

                $_form_data             =   array( array(

                    'title'            =>   esc_attr( $_POST[ 'event_list' ] ),

                    'event_list'       =>   esc_attr( $_POST[ 'event_list' ] ),

                    'event_icon'       =>   esc_attr( $_POST[ 'event_icon' ] ),

                    'have_meal'        =>   ( absint( $_POST[ 'have_meal' ] ) == absint( '1' ) )

                                            ?   esc_attr( 'on' )

                                            :   esc_attr(  'off' ),

                    'event_meal'       =>   json_encode( $_POST[ 'event_meal' ] ),

                    'event_unique_id'  =>   absint( $_new_event_unique_id ),

                ) );

                /**
                 *  Check database have menu item ?
                 *  -------------------------------
                 */
                if( parent:: _is_array( $_backend_data ) ){

                    $_new_array = array_merge_recursive( $_backend_data, $_form_data );

                }else{

                    $_new_array = $_form_data;
                }

                /**
                 *  Update Menu list
                 *  ----------------
                 */
                update_post_meta( absint( parent:: post_id() ), sanitize_key( 'guest_list_event_group' ), $_new_array );


                /**
                 *  Event JSON Update in Guest List
                 *  -------------------------------
                 */
                $_event_json                =   array( array(

                        'event_name'        =>  esc_attr( $_POST[ 'event_list' ] ),

                        'guest_invited'     =>  absint( '0' ),

                        'meal'              =>  '',

                        'event_unique_id'   =>  absint( $_new_event_unique_id )
                ) );

                $_guest_data =  self:: guest_list();

                foreach ( $_guest_data as $key => $value ) {

                    $_guest_data[ $key ][ 'guest_event' ]    =

                    json_encode(

                            array_merge(

                                // 1
                                json_decode( $value[ 'guest_event' ], true ),

                                // 2
                                $_event_json
                            )
                    );
                }

                /**
                 *  Update Post Meta
                 *  ---------------
                 */
                update_post_meta( absint( self:: post_id() ), sanitize_key( 'guest_list_data' ), $_guest_data );

                /**
                 *  Success
                 *  -------
                 */
                die( json_encode( array(

                    'message'    =>     esc_attr__( 'Event Added Successfully!', 'sdweddingdirectory-guest-list' ),

                    'notice'     =>     absint( '1' ),

                    'new_event_data'    =>  $_guest_data

                ) ) );

            }else{

                self:: security_issue_found();
            }
        }

        public static function sdweddingdirectory_create_new_guest_data(){

            if (  isset( $_POST['security'] ) && wp_verify_nonce( $_POST['security'], esc_attr( 'add_new_guest_security' ) )  ){

                $_new_guest_list = $_form_data = [];   

                $_counter = absint( '0' );

                if( parent:: _is_array( $_POST[ 'guest_member_list' ] ) ){

                    $_member_group_id  = absint( rand() );

                    foreach ( $_POST[ 'guest_member_list' ] as $guest_key => $guest_value ){

                        if( $_counter == absint( '0' ) ){

                                /**
                                 *  Form Data
                                 *  ---------
                                 */
                                $_form_data[] =     array(

                                    'title'                   =>    sprintf( '%1$s %2$s',

                                                                        // 1
                                                                        esc_attr( $_POST[ 'guest_member_list' ][ absint( $_counter )][ 'first_name' ] ),

                                                                        // 2
                                                                        esc_attr( $_POST[ 'guest_member_list' ][ absint( $_counter )][ 'last_name' ] )
                                                                    ),

                                    'first_name'              =>    esc_attr( $_POST[ 'guest_member_list' ][ absint( $_counter )][ 'first_name' ] ),

                                    'last_name'               =>    esc_attr( $_POST[ 'guest_member_list' ][ absint( $_counter )][ 'last_name' ] ),

                                    'guest_event'             =>    json_encode( $_POST[ 'guest_event' ] ),

                                    'guest_age'               =>    esc_attr( $_POST[ 'guest_age' ] ),

                                    'guest_group'             =>    esc_attr( $_POST[ 'guest_group' ] ),

                                    'guest_need_hotel'        =>    (   absint( $_POST[ 'guest_need_hotel' ] ) == absint( '1' )   )

                                                                    ?   esc_attr( 'on' )

                                                                    :   esc_attr( 'off' ),

                                    'guest_email'             =>    sanitize_email( $_POST[ 'guest_email' ] ),

                                    'guest_contact'           =>    esc_attr( $_POST[ 'guest_contact' ] ),

                                    'guest_address'           =>    esc_attr( $_POST[ 'guest_address' ] ),

                                    'guest_city'              =>    esc_attr( $_POST[ 'guest_city' ] ),

                                    'guest_state'             =>    esc_attr( $_POST[ 'guest_state' ] ),

                                    'guest_zip_code'          =>    esc_attr( $_POST[ 'guest_zip_code' ] ),

                                    'guest_comment'           =>    '',

                                    'invitation_status'       =>    absint( '0' ),

                                    'guest_unique_id'         =>    absint( rand() ),

                                    'request_missing_info'    =>    absint( '0' ),

                                    'guest_member_id'         =>    absint( $_member_group_id ),

                                );

                        }else{

                                /**
                                 *  Form Data
                                 *  ---------
                                 */
                                $_form_data[]       =   array(

                                    'title'                   =>    sprintf( '%1$s %2$s',

                                                                        // 1
                                                                        esc_attr( $_POST[ 'guest_member_list' ][ absint( $_counter )][ 'first_name' ] ),

                                                                        // 2
                                                                        esc_attr( $_POST[ 'guest_member_list' ][ absint( $_counter )][ 'last_name' ] )
                                                                    ),

                                    'first_name'              =>    esc_attr( $_POST[ 'guest_member_list' ][ absint( $_counter )][ 'first_name' ] ),

                                    'last_name'               =>    esc_attr( $_POST[ 'guest_member_list' ][ absint( $_counter )][ 'last_name' ] ),

                                    'guest_event'             =>    json_encode( $_POST[ 'guest_event' ] ),

                                    'guest_age'               =>    '',

                                    'guest_group'             =>    esc_attr( $_POST[ 'guest_group' ] ),

                                    'guest_need_hotel'        =>    esc_attr( 'off' ),

                                    'guest_email'             =>    '',

                                    'guest_contact'           =>    '',

                                    'guest_address'           =>    '',

                                    'guest_city'              =>    '',

                                    'guest_state'             =>    '',

                                    'guest_zip_code'          =>    '',

                                    'guest_comment'           =>    '',

                                    'invitation_status'       =>    absint( '0' ),

                                    'request_missing_info'    =>    absint( '0' ),

                                    'guest_unique_id'         =>    absint( rand() ),

                                    'guest_member_id'         =>    absint( $_member_group_id ),
                                );
                        }

                        $_counter++;       
                    }
                }

                /**
                 *  Backend Data
                 *  ------------
                 */
                $_backend_data  = parent:: guest_list();

                $_merget_guest     = [];

                if( parent:: _is_array( $_backend_data ) ){

                    $_merget_guest     =   array_merge_recursive( $_form_data, $_backend_data );

                }else{

                    $_merget_guest     =   $_form_data;
                }

                /**
                 *  Update Guest list data
                 *  ----------------------
                 */
                update_post_meta( absint( parent:: post_id() ), sanitize_key( 'guest_list_data' ), $_merget_guest );

                /**
                 *  Success
                 *  -------
                 */
                die( json_encode( array(

                    'message'   =>  esc_attr__( 'Guest Added!', 'sdweddingdirectory-guest-list' ),

                    'notice'    =>  absint( '1' ),

                    'data'      =>  $_POST

                ) ) );

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  11. Event Guest Update Data
         *  ---------------------------
         */
        public static function update_guest_event_data( $_guest_data = []  ){

            if( parent:: _is_array( $_guest_data ) ){

                /**
                 *  1. Get Event Data
                 *  -----------------
                 */
                $_GUEST_DATA            =  parent:: guest_list();

                $_guest_unique_id       =  absint( $_guest_data[ 'guest_unique_id' ] );

                $_event_unique_id       =  absint( $_guest_data[ 'event_unique_id' ] );

                $_event_data_replace    =  $_guest_data[ 'update_data' ];

                if( parent:: _is_array( $_GUEST_DATA ) ){

                    foreach ( $_GUEST_DATA as $key => $value) {
                        
                        if( $value[ 'guest_unique_id' ] == $_guest_unique_id ){

                            /**
                             *  Get Event Guest
                             *  ---------------
                             */
                            $_event_list      =   json_decode( $_GUEST_DATA[ $key ][ 'guest_event' ], true );

                            if( parent:: _is_array( $_event_list ) ){

                                foreach ( $_event_list as $_event_key => $_event_value ) {

                                    if( $_event_value[ 'event_unique_id' ]  ==  $_event_unique_id  ){

                                        /**
                                         *  Update key with replace value
                                         *  -----------------------------
                                         */
                                        $_event_list[ absint( $_event_key ) ] = array_replace( 

                                            /**
                                             *  1. Member Key
                                             *  -------------
                                             */
                                            $_event_list[ absint( $_event_key ) ],

                                            /**
                                             *  2. Update Meal
                                             *  --------------
                                             */
                                            $_event_data_replace
                                        );

                                        /**
                                         *  1. Event Key ID Updated with new meal
                                         *  -------------------------------------
                                         */
                                        $_GUEST_DATA[ $key ][ 'guest_event' ] = json_encode( $_event_list );
                                    }
                                }
                            }

                            /**
                             *  Update Guest Meal in Event Data
                             *  -------------------------------
                             */
                            update_post_meta( absint( parent:: post_id() ), sanitize_key( 'guest_list_data' ), $_GUEST_DATA );

                        }
                    }
                }

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  9. Guest Attendance for each event
         *  ----------------------------------
         */
        public static function sdweddingdirectory_guest_event_meal_action(){

            $_condition_1 = parent:: _is_set( $_POST[ 'event_unique_id' ] );

            $_condition_2 = parent:: _is_set( $_POST[ 'guest_unique_id' ] );

            $_condition_3 = parent:: _is_set( $_POST[ 'member_meal' ] );     

            if( $_condition_1 && $_condition_2 && $_condition_3 ){

                /**
                 *  Update Meal in guest
                 *  --------------------
                 */
                self:: update_guest_event_data( array(

                    'event_unique_id'     =>  absint( $_POST[ 'event_unique_id' ] ),

                    'guest_unique_id'     =>  absint( $_POST[ 'guest_unique_id' ] ),

                    'update_data'         =>  array( 'meal' => esc_attr( $_POST[ 'member_meal' ] )  )
                ) );
                
                /**
                 *  Successfully
                 *  ------------
                 */
                die( json_encode( array(

                    'message'   =>  esc_attr__( 'Guest Meal Successfully Updated!', 'sdweddingdirectory-guest-list' ),

                    'notice'    =>  absint( '1' )

                ) ) );

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  10. Guest Attendence Update for each event
         *  ------------------------------------------
         */
        public static function sdweddingdirectory_guest_event_attendance_action(){

            $_condition_1 = parent:: _is_set( $_POST[ 'event_unique_id' ] );

            $_condition_2 = parent:: _is_set( $_POST[ 'guest_unique_id' ] );

            $_condition_3 = parent:: _is_set( $_POST[ 'guest_invited' ] );

            if( $_condition_1 && $_condition_2 && $_condition_3 ){

                /**
                 *  Update Meal in guest
                 *  --------------------
                 */
                self:: update_guest_event_data( array(

                    'event_unique_id'  =>  absint( $_POST[ 'event_unique_id' ] ),

                    'guest_unique_id'  =>  absint( $_POST[ 'guest_unique_id' ] ),

                    'update_data'      =>  array( 'guest_invited' => absint( $_POST[ 'guest_invited' ] ) )
                ) );

                /**
                 *  Successfully
                 *  ------------
                 */
                die( json_encode( array(

                    'message'   =>  esc_attr__( 'Guest Attendance Successfully Updated!', 'sdweddingdirectory-guest-list' ),

                    'notice'    =>  absint( '1' ),

                    'counter'   =>  parent:: get_counter_notification( 

                                        /**
                                         *  1. Pass Event ID
                                         *  ----------------
                                         */
                                        absint( $_POST[ 'event_unique_id' ] ) 
                                    )
                ) ) );

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  11. Get Guest Information
         *  -------------------------
         */
        public static function sdweddingdirectory_get_guest_info_action(){

            $_condition_1 = parent:: _is_set( $_POST[ 'guest_unique_id' ] );

            if( $_condition_1 ){

                $_guest_data    =       parent:: guest_list();

                if( parent:: _is_array( $_guest_data ) ){

                    foreach ( $_guest_data as $_guest_key => $guest_value ) {

                        if( absint( $guest_value[ 'guest_unique_id' ] ) == absint( $_POST[ 'guest_unique_id' ] ) ){

                            $_get_event_list        =   json_decode( $guest_value[ 'guest_event' ], true );

                            $_get_select_option     =   [];

                            if( parent:: _is_array( $_get_event_list ) ){

                                foreach ( $_get_event_list as $_event_key => $_event_value ) {

                                    $_invited_key   =   sprintf( 'guest_invited-%1$s', absint( $_event_value[ 'event_unique_id' ] ) );

                                    $_meal_key      =   sprintf( 'meal-%1$s', absint( $_event_value[ 'event_unique_id' ] ) );

                                    $_get_select_option[  $_invited_key  ]      =   esc_attr( $_event_value[ 'guest_invited' ] );

                                    $_get_select_option[  $_meal_key  ]         =   esc_attr( $_event_value[ 'meal' ] );
                                }
                            }

                            die( json_encode( array(

                                'missing_info_link'     =>      esc_url(  add_query_arg(

                                                                    /**
                                                                     *  Args
                                                                     *  ----
                                                                     */
                                                                    [
                                                                        'user'      =>      absint( parent:: post_id() ),

                                                                        'guest'     =>      esc_attr( $_POST[ 'guest_unique_id' ] )
                                                                    ],

                                                                    /**
                                                                     *  Page Template
                                                                     *  -------------
                                                                     */
                                                                    apply_filters( 'sdweddingdirectory/template/link', esc_attr( 'request-missing-info.php' ) )

                                                                ), null, null ),

                                'guest_data'            =>  $_guest_data,

                                'input'                 =>  array(

                                    'edit_first_name'       =>  isset( $guest_value[ 'first_name' ] ) && ( $guest_value[ 'first_name' ] !== '' ) 

                                                                ?   esc_attr( $guest_value[ 'first_name' ]      )

                                                                :   '',

                                    'edit_last_name'        =>  isset( $guest_value[ 'last_name' ] ) && ( $guest_value[ 'last_name' ] !== '' ) 

                                                                ?   esc_attr( $guest_value[ 'last_name' ]      )

                                                                :   '',

                                    'edit_guest_email'      =>  isset( $guest_value[ 'guest_email' ] ) && ( $guest_value[ 'guest_email' ] !== '' ) 

                                                                ?   sanitize_email( $guest_value[ 'guest_email' ] )

                                                                :   '',

                                    'edit_guest_contact'    =>  isset( $guest_value[ 'guest_contact' ] ) && ( $guest_value[ 'guest_contact' ] !== '' ) 

                                                                ?   esc_attr( $guest_value[ 'guest_contact' ]  )

                                                                :   '',

                                    'edit_guest_address'    =>  isset( $guest_value[ 'guest_address' ] ) && ( $guest_value[ 'guest_address' ] !== '' ) 

                                                                ?   esc_attr( $guest_value[ 'guest_address' ]  )

                                                                :   '',

                                    'edit_guest_city'       =>  isset( $guest_value[ 'guest_city' ] ) && ( $guest_value[ 'guest_city' ] !== '' ) 

                                                                ?   esc_attr( $guest_value[ 'guest_city' ]     )

                                                                :   '',


                                    'edit_guest_state'      =>  isset( $guest_value[ 'guest_state' ] ) && ( $guest_value[ 'guest_state' ] !== '' ) 

                                                                ?   esc_attr( $guest_value[ 'guest_state' ]     )

                                                                :   '',

                                    'edit_guest_zip_code'   =>  isset( $guest_value[ 'guest_zip_code' ] ) && ( $guest_value[ 'guest_zip_code' ] !== '' ) 

                                                                ?   esc_attr( $guest_value[ 'guest_zip_code' ]  )

                                                                :   '',

                                    'edit_guest_unique_id'  =>  isset( $guest_value[ 'guest_unique_id' ] ) && ( $guest_value[ 'guest_unique_id' ] !== '' ) 

                                                                ?   absint( $guest_value[ 'guest_unique_id' ]  )

                                                                :   '',
                                ),

                                'textarea'              =>  [

                                    'edit_guest_comment'        =>      isset( $guest_value[ 'guest_comment' ] ) && $guest_value[ 'guest_comment' ] !== ''

                                                                        ?   implode( esc_attr__( 'Message from Guest:', 'sdweddingdirectory-guest-list' ), 

                                                                                explode( '||', $guest_value[ 'guest_comment' ] )
                                                                            )

                                                                        :   '',
                                ],

                                'select'                =>  array_merge(

                                        /**
                                         *  1. Get Guest Dropdown options
                                         *  -----------------------------
                                         */
                                        array(

                                            'edit_guest_age'        =>  isset( $guest_value[ 'guest_age' ] ) && ( $guest_value[ 'guest_age' ] !== '' ) 

                                                                        ?   esc_attr( $guest_value[ 'guest_age' ]      )

                                                                        :   '',

                                            'edit_guest_group'      =>  isset( $guest_value[ 'guest_group' ] ) && ( $guest_value[ 'guest_group' ] !== '' ) 

                                                                        ?   esc_attr( $guest_value[ 'guest_group' ]      )

                                                                        :   '',
                                        ),

                                        /**
                                         *  2. Get Event options
                                         *  --------------------
                                         */
                                        $_get_select_option
                                ),

                                'checkbox'               => array(

                                    'edit_guest_need_hotel'       =>      

                                        isset( $guest_value[ 'guest_need_hotel' ] ) && $guest_value[ 'guest_need_hotel' ] == 'on' && $guest_value[ 'guest_need_hotel' ] !== '' 

                                        ?   absint( '1' )

                                        :   absint( '0' ),
                                )

                            ) ) );

                        }
                        
                    }


                }else{

                    self:: security_issue_found();
                }

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  12. Remove Guest Information
         *  ----------------------------
         */
        public static function sdweddingdirectory_remove_guest_info_action(){

            $_condition_1 = parent:: _is_set( $_POST[ 'guest_unique_id' ] );

            if( $_condition_1 ){

                $_guest_data    =       parent:: guest_list();

                if( parent:: _is_array( $_guest_data ) ){

                    foreach ( $_guest_data as $key => $value) {

                        /**
                         *  Get the member unique id
                         *  ------------------------
                         */
                        if( absint( $value[ 'guest_unique_id' ] )  == absint( $_POST[ 'guest_unique_id' ] )  ){

                            /**
                             *  Removed Guest Key Data
                             *  ----------------------
                             */
                            unset(  $_guest_data[  $key  ]  );

                            /**
                             *  Update Guest data
                             *  -----------------
                             */
                            update_post_meta( absint( parent:: post_id() ), sanitize_key( 'guest_list_data' ), $_guest_data );

                            /**
                             *  Success
                             *  -------
                             */
                            die( json_encode( array(

                                'notice'     =>     absint( '1' ),

                                'message'    =>     esc_attr__( 'Guest Removed Successfully!', 'sdweddingdirectory-guest-list' ),

                                'counter'    =>     parent:: global_counter_notification()

                            ) ) );
                        }
                    }

                }

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  13. Update Guest Information
         *  ----------------------------
         */
        public static function sdweddingdirectory_update_guest_data(){

            $_condition_1 = parent:: _is_set( $_POST[ 'guest_unique_id' ] );

            if( $_condition_1 ){

                $_guest_data    =       parent:: guest_list();

                if( parent:: _is_array( $_guest_data ) ){

                    foreach ( $_guest_data as $_guest_key => $guest_value ) {

                        if( absint( $guest_value[ 'guest_unique_id' ] ) == absint( $_POST[ 'guest_unique_id' ] ) ){

                            $_guest_data[ $_guest_key ]   =   '';

                            $_guest_data[ $_guest_key ]   =   array(

                                'title'                   =>    sprintf( '%1$s %2$s', 

                                                                    // 1
                                                                    esc_attr( $_POST[ 'first_name' ] ),

                                                                    // 2
                                                                    esc_attr( $_POST[ 'last_name' ] )
                                                                ),

                                'first_name'              =>    esc_attr( $_POST[ 'first_name' ] ),

                                'last_name'               =>    esc_attr( $_POST[ 'last_name' ] ),

                                'guest_event'             =>    json_encode( $_POST[ 'guest_event' ] ),

                                'guest_age'               =>    esc_attr( $_POST[ 'guest_age' ] ),

                                'guest_group'             =>    esc_attr( $_POST[ 'guest_group' ] ),

                                'guest_need_hotel'        =>    (   absint( $_POST[ 'guest_need_hotel' ] ) == absint( '1' )   )

                                                                ?   esc_attr( 'on' )

                                                                :   esc_attr( 'off' ),

                                'guest_email'             =>    sanitize_email( $_POST[ 'guest_email' ] ),

                                'guest_contact'           =>    esc_attr( $_POST[ 'guest_contact' ] ),

                                'guest_address'           =>    esc_attr( $_POST[ 'guest_address' ] ),

                                'guest_city'              =>    esc_attr( $_POST[ 'guest_city' ] ),

                                'guest_state'             =>    esc_attr( $_POST[ 'guest_state' ] ),

                                'guest_zip_code'          =>    esc_attr( $_POST[ 'guest_zip_code' ] ),

                                'request_missing_info'    =>    absint( $guest_value[ 'request_missing_info' ] ),

                                'invitation_status'       =>    absint( $guest_value[ 'invitation_status' ] ),

                                'guest_unique_id'         =>    absint( $guest_value[ 'guest_unique_id' ] ),

                                'guest_member_id'         =>    absint( $guest_value[ 'guest_member_id' ] ),

                                'guest_comment'           =>    esc_textarea( $_POST[ 'guest_comment' ] ),
                            );

                            /**
                             *  Update Post Meta
                             *  ----------------
                             */
                            update_post_meta( 

                                absint( parent:: post_id() ),

                                sanitize_key( 'guest_list_data' ),

                                $_guest_data
                            );

                            die( json_encode( array(

                                'notice'            =>      absint( '1' ),

                                'message'           =>      esc_attr__( 'Guest Data Updated!', 'sdweddingdirectory-guest-list' ),

                                'key_data'          =>      $_guest_data[ $_guest_key ]

                            ) ) );
                        }
                    }

                }else{

                    self:: security_issue_found();
                }

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  14. Update Event Data
         *  ---------------------
         */
        public static function sdweddingdirectory_update_event_form_data(){

            $_condition_1 = isset( $_POST[ 'event_unique_id' ] ) && $_POST[ 'event_unique_id' ] !== '';

            if( $_condition_1 ){

                $_event_data        =   parent:: event_list();

                $_event_unique_id   =   isset( $_POST[ 'event_unique_id' ] )  && $_POST[ 'event_unique_id' ] !== ''

                                        ?   absint( $_POST[ 'event_unique_id' ] )

                                        :   '';

                if( parent:: _is_array( $_event_data ) ){

                    foreach ( $_event_data as $_event_key => $event_value ) {

                        if( absint( $event_value[ 'event_unique_id' ] ) == absint( $_event_unique_id ) ){

                            $_get_event_list        =   json_decode( $event_value[ 'event_meal' ], true );

                            $_event_list_option     =   '';

                            if( parent:: _is_array( $_get_event_list ) ){

                                foreach ( $_get_event_list as $_key => $_value ) {

                                        $_event_list_option .=

                                        sprintf( '<li class="d-flex justify-content-between align-items-center">
                                                      <span>%1$s</span>
                                                      <a href="javascript:" class="action-links"><i class="fa fa-trash"></i></a>
                                                  </li>',

                                                  /**
                                                   *  1. Menu Name
                                                   *  ------------
                                                   */
                                                  esc_attr( $_value[ 'menu_list' ] )
                                        );
                                }
                            }

                            die( json_encode( array(

                                'event_data'            =>      $_event_data,

                                'input'                 =>      array(

                                    'edit_event_unique_id'      =>  absint( $_event_unique_id ),

                                    'edit_event_name'           =>  esc_attr( $event_value[ 'event_list' ] ),
                                ),

                                'select'                 =>      array(

                                    'edit_event_icon'           =>  esc_attr( $event_value[ 'event_icon' ] ),
                                ),

                                'html'                  =>      array(

                                    'edit_event_meal'           =>      $_event_list_option,
                                ),

                                'checkbox'              =>      array(

                                    'edit_have_meal'    =>      ( isset( $event_value[ 'have_meal' ] )        &&
                                                                         $event_value[ 'have_meal' ] == 'on'  &&
                                                                         $event_value[ 'have_meal' ] !== ''    )

                                                                ?   absint( '1' )

                                                                :   absint( '0' ),
                                ),

                            ) ) );

                        }
                        
                    }


                }else{

                    self:: security_issue_found();
                }

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  15. Update Event Data
         *  ---------------------
         */
        public static function sdweddingdirectory_update_event_data(){

            $_condition_1 =  isset( $_POST[ 'event_list' ] ) && $_POST[ 'event_list' ] !== '';

            $_condition_2 =  isset( $_POST[ 'event_meal' ] ) && $_POST[ 'event_meal' ] !== '';

            $_condition_3 =  isset( $_POST[ 'have_meal' ] )  && $_POST[ 'have_meal' ]  !== '';

            $_condition_4 =  isset( $_POST[ 'event_unique_id' ] ) && $_POST[ 'event_unique_id' ] !== '';

            /**
             *  Add Event Action
             *  ----------------
             */
            if( $_condition_1 && $_condition_2 && $_condition_3 && $_condition_4 ){

                $_event_data          =   parent:: event_list();

                if( parent:: _is_array( $_event_data ) ){

                    foreach ( $_event_data as $key => $value ) {
                        
                        if( $value[ 'event_unique_id' ] == $_POST[ 'event_unique_id' ] ){

                            $_event_data[ $key ]  =   array(

                                'title'            =>   esc_attr( $_POST[ 'event_list' ] ),

                                'event_list'       =>   esc_attr( $_POST[ 'event_list' ] ),

                                'event_icon'       =>   esc_attr( $_POST[ 'event_icon' ] ),

                                'have_meal'        =>   ( absint( $_POST[ 'have_meal' ] ) == absint( '1' ) )

                                                        ?   esc_attr( 'on' )

                                                        :   esc_attr(  'off' ),

                                'event_meal'       =>   json_encode( $_POST[ 'event_meal' ] ),

                                'event_unique_id'  =>   absint( $value[ 'event_unique_id' ] ),
                            );


                            /**
                             *  Event Meals
                             *  -----------
                             */
                            $_event_meals     =   [];

                            $_default_meal[]   =   array(

                                'id'    =>  absint( '0' ),

                                'text'  =>  esc_attr__( 'Select Your Meal', 'sdweddingdirectory-guest-list' ),
                            );

                            if(  parent:: _is_array( $_POST[ 'event_meal' ] )   ){

                                foreach ( $_POST[ 'event_meal' ] as $key => $value) {
                                    
                                    $_event_meals[] = array(

                                                        'id'    =>  sanitize_title( $value[ 'menu_list' ] ),

                                                        'text'  =>  esc_attr( $value[ 'menu_list' ] )
                                                    );
                                }
                            }


                            /**
                             *  Update Menu list
                             *  ----------------
                             */
                            update_post_meta( absint( parent:: post_id() ), sanitize_key( 'guest_list_event_group' ), $_event_data );

                            /**
                             *  Success
                             *  -------
                             */
                            
                            $_event_unique_id =     isset( $_POST[ 'event_unique_id' ] ) && $_POST[ 'event_unique_id' ] !== ''

                                                    ?   absint( $_POST[ 'event_unique_id' ] )

                                                    :   '';

                            die( json_encode( array(

                                'message'           =>      esc_attr__( 'Event Updated Successfully!', 'sdweddingdirectory-guest-list' ),

                                'notice'            =>      absint( '1' ),

                                'default_meal'      =>      $_default_meal,

                                'select_options'    =>      $_event_meals,

                                'html'              =>      array(

                                    /**
                                     *  Event Icon Change
                                     *  -----------------
                                     */
                                    sprintf( '%1$s_event_icon',  absint( $_event_unique_id ) )

                                    =>  sprintf( '<i class="%1$s"></i>', esc_attr( $_POST[ 'event_icon' ] ) ),

                                    /**
                                     *  Event Name Change in Tabs
                                     *  -------------------------
                                     */
                                    sprintf( '%1$s-tab', absint( $_event_unique_id ) )

                                    =>  esc_attr( $_POST[ 'event_list' ] ),

                                    /**
                                     *  Event Name Change
                                     *  -----------------
                                     */
                                    sprintf( '%1$s_event_name', absint( $_event_unique_id ) )

                                    =>  esc_attr( $_POST[ 'event_list' ] ),

                                    /**
                                     *  Table * TR * Change
                                     *  -------------------
                                     */
                                    sprintf( '%1$s_table_head', absint( $_event_unique_id ) )

                                    =>  esc_attr( $_POST[ 'event_list' ] ),

                                    /**
                                     *  Edit Guest Form Tabs Event Name Change
                                     *  --------------------------------------
                                     */
                                    sprintf( 'edit-guest-form-%1$s-tab', absint( $_event_unique_id ) )

                                    =>  esc_attr( $_POST[ 'event_list' ] ),

                                    /**
                                     *  Edit Guest Form Tabs Event Name Change
                                     *  --------------------------------------
                                     */
                                    sprintf( '%1$s_edit_guest_event_name', absint( $_event_unique_id ) )

                                    =>  esc_attr( $_POST[ 'event_list' ] ),

                                ),

                            ) ) );

                        }
                    }

                }else{

                    self:: security_issue_found();
                }

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  16. Remove Event
         *  ----------------
         */
        public static function sdweddingdirectory_remove_event_data(){

            $_condition_1 =  isset( $_POST[ 'event_unique_id' ] ) && $_POST[ 'event_unique_id' ] !== '';

            /**
             *  Add Event Action
             *  ----------------
             */
            if( $_condition_1 ){

                $_event_data          =   parent:: event_list();

                $_removed_event_id    =   absint( $_POST[ 'event_unique_id' ] );

                /**
                 *  1. Removed Event
                 *  ----------------
                 */
                if( parent:: _is_array( $_event_data ) ){

                    foreach ( $_event_data as $key => $value) {
                        
                        if( absint( $value[ 'event_unique_id' ] ) == absint( $_removed_event_id ) ){

                            unset( $_event_data[ $key ] );

                            /**
                             *  Update Event list
                             *  -----------------
                             */
                            update_post_meta( absint( parent:: post_id() ), sanitize_key( 'guest_list_event_group' ), $_event_data );
                        }
                    }
                }

                /**
                 *  2. Remove Event in Guest List Data
                 *  ----------------------------------
                 */
                $_guest_data            =  parent:: guest_list();

                if( parent:: _is_array( $_guest_data ) ){

                    foreach ( $_guest_data as $_guest_key => $_guest_value ) {

                        $_event_list = json_decode( $_guest_value[ 'guest_event' ], true );

                        if( parent:: _is_array(  $_event_list  ) ){

                            foreach ( $_event_list as $_event_key => $_event_value) {
                                
                                if( absint( $_event_value[ 'event_unique_id' ] ) == absint( $_removed_event_id ) ){

                                    unset( $_event_list[ absint( $_event_key ) ] );
                                }
                            }

                            $_guest_data[ $_guest_key ][ 'guest_event' ]  = json_encode( $_event_list );
                        }
                    }

                    /**
                     *  Update Post Meta
                     *  ---------------
                     */
                    update_post_meta( absint( parent:: post_id() ), sanitize_key( 'guest_list_data' ), $_guest_data );
                }

                /**
                 *  Success
                 *  -------
                 */
                die( json_encode( array(

                    'message'    =>     esc_attr__( 'Event Removed Successfully!', 'sdweddingdirectory-guest-list' ),

                    'notice'     =>     absint( '1' ),

                ) ) );

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  16. Update Guest Group
         *  ----------------------
         */
        public static function sdweddingdirectory_guest_group_action(){

            $_condition_1 = parent:: _is_set( $_POST[ 'guest_unique_id' ] );

            $_condition_2 = parent:: _is_set( $_POST[ 'guest_group' ] );

            if( $_condition_1 && $_condition_2 ){

                $_guest_data    =       parent:: guest_list();

                if( parent:: _is_array( $_guest_data ) ){

                    foreach ( $_guest_data as $_guest_key => $guest_value ) {

                        if( absint( $guest_value[ 'guest_unique_id' ] ) == absint( $_POST[ 'guest_unique_id' ] ) ){

                            $_guest_data[ $_guest_key ]   =   '';

                            $_guest_data[ $_guest_key ]   =   array(

                                'title'                   =>    sprintf( '%1$s %2$s', 

                                                                    // 1
                                                                    esc_attr( $guest_value[ 'first_name' ] ),

                                                                    // 2
                                                                    esc_attr( $guest_value[ 'last_name' ] )
                                                                ),

                                'first_name'              =>    esc_attr( $guest_value[ 'first_name' ] ),

                                'last_name'               =>    esc_attr( $guest_value[ 'last_name' ] ),

                                'guest_event'             =>    $guest_value[ 'guest_event' ],

                                'guest_age'               =>    esc_attr( $guest_value[ 'guest_age' ] ),

                                'guest_group'             =>    esc_attr( $_POST[ 'guest_group' ] ),

                                'guest_need_hotel'        =>    esc_attr( $guest_value[ 'guest_need_hotel' ] ),

                                'guest_email'             =>    sanitize_email( $guest_value[ 'guest_email' ] ),

                                'guest_contact'           =>    esc_attr( $guest_value[ 'guest_contact' ] ),

                                'guest_address'           =>    esc_attr( $guest_value[ 'guest_address' ] ),

                                'guest_city'              =>    esc_attr( $guest_value[ 'guest_city' ] ),

                                'guest_state'             =>    esc_attr( $guest_value[ 'guest_state' ] ),

                                'guest_zip_code'          =>    esc_attr( $guest_value[ 'guest_zip_code' ] ),

                                'invitation_status'       =>    absint( $guest_value[ 'invitation_status' ] ),

                                'guest_unique_id'         =>    absint( $guest_value[ 'guest_unique_id' ] ),

                                'guest_member_id'         =>    absint( $guest_value[ 'guest_member_id' ] ),
                            );

                            /**
                             *  Update Post Meta
                             *  ----------------
                             */
                            update_post_meta( 

                                absint( parent:: post_id() ),

                                sanitize_key( 'guest_list_data' ),

                                $_guest_data
                            );

                            die( json_encode( array(

                                'notice'            =>      absint( '1' ),

                                'message'           =>      esc_attr__( 'Guest Group Updated!', 'sdweddingdirectory-guest-list' ),

                            ) ) );
                        }
                    }

                }else{

                    self:: security_issue_found();
                }

            }else{

                self:: security_issue_found();
            }
        }

        public static function guest_list_csv_download(){

            $_condition_1  =   isset( $_POST[ 'security' ] ) && $_POST[ 'security' ] !== '';

            if( $_condition_1 ){

                /**
                 *  File Name
                 *  ---------
                 */
                $filename = esc_attr( 'guest-list.csv' );

                /**
                 *  Create File
                 *  -----------
                 */
                $file = fopen( $filename, "w" );

                /**
                 *  Is Event Export CSV ?
                 *  ---------------------
                 */
                $_condition_2 = isset( $_POST[ 'event_id' ] ) && $_POST[ 'event_id' ] !== '';

                if( $_condition_2 ){

                    /**
                     *  Export Data Key
                     *  ---------------
                     */
                    $_event_data            =     array(

                        'first_name'              =>    esc_attr__( 'First Name', 'sdweddingdirectory-guest-list' ),

                        'last_name'               =>    esc_attr__( 'Last Name', 'sdweddingdirectory-guest-list' ),

                        'event_name'              =>    esc_attr__( 'Event Name', 'sdweddingdirectory-guest-list' ),

                        'attended'                =>    esc_attr__( 'Attended ?', 'sdweddingdirectory-guest-list' ),

                        'meal'                    =>    esc_attr__( 'Guest Meal', 'sdweddingdirectory-guest-list' ),
                    );

                    /**
                     *  Update First Top Header Headings Only
                     *  -------------------------------------
                     */
                    fputcsv(

                        /**
                         *  File Name
                         *  ---------
                         */
                        $file,

                        /**
                         *  Update Headings Only
                         *  --------------------
                         */
                        $_event_data
                    );

                    foreach ( parent:: guest_list() as $key => $value) {


                        $_guest_info =

                        array(

                            'first_name'              =>    ( isset( $value[ 'first_name' ] )  && $value[ 'first_name' ] !== '' )
                                                            ? $value[ 'first_name' ]
                                                            : '',

                            'last_name'               =>    ( isset( $value[ 'last_name' ] )  && $value[ 'last_name' ] !== '' )
                                                            ? $value[ 'last_name' ]
                                                            : '',
                        );



                        $_event_data = json_decode( $value[ 'guest_event' ], true );

                        $_event_info    =   [];

                        if( parent:: _is_array( $_event_data ) ){

                            foreach ( $_event_data as $_event_key => $_event_value ) {

                                if(  absint( $_event_value[ 'event_unique_id' ] )  == absint( $_POST[ 'event_id' ] )   ){


                                    $_event_info  =

                                    array(

                                        'event_name'              =>    esc_attr( $_event_value[ 'event_name' ] ),

                                        'attended'                =>    esc_attr( 

                                                                            parent:: get_attendance_options()[ $_event_value[ 'guest_invited' ] ] 
                                                                        ),

                                        'meal'                    =>    esc_attr( $_event_value[ 'meal' ] ),
                                    );
                                }
                            }
                        }

                        /**
                         *  Guest info with update event list
                         *  ---------------------------------
                         */
                        fputcsv(

                            /**
                             *  Handler
                             *  -------
                             */
                            $file,

                            /**
                             *  Update Values
                             *  -------------
                             */
                            array_merge(  $_guest_info,  $_event_info  )
                        );
                    }

                }else{

                    /**
                     *  Export Data Key
                     *  ---------------
                     */
                    $_export_data_name            =     array(

                        'first_name'              =>    esc_attr__( 'First Name', 'sdweddingdirectory-guest-list' ),

                        'last_name'               =>    esc_attr__( 'Last Name', 'sdweddingdirectory-guest-list' ),

                        'guest_age'               =>    esc_attr__( 'Guest Age', 'sdweddingdirectory-guest-list' ),

                        'guest_group'             =>    esc_attr__( 'Guest Group', 'sdweddingdirectory-guest-list' ),

                        'guest_need_hotel'        =>    esc_attr__( 'Need Hotel ?', 'sdweddingdirectory-guest-list' ),

                        'guest_email'             =>    esc_attr__( 'Email Address', 'sdweddingdirectory-guest-list' ),

                        'guest_contact'           =>    esc_attr__( 'Contact Number', 'sdweddingdirectory-guest-list' ),

                        'guest_address'           =>    esc_attr__( 'Address', 'sdweddingdirectory-guest-list' ),

                        'guest_city'              =>    esc_attr__( 'City', 'sdweddingdirectory-guest-list' ),

                        'guest_state'             =>    esc_attr__( 'State', 'sdweddingdirectory-guest-list' ),

                        'guest_zip_code'          =>    esc_attr__( 'Zip Code', 'sdweddingdirectory-guest-list' ),

                        'guest_comment'           =>    esc_attr__( 'Guest Comment', 'sdweddingdirectory-guest-list' ),
                    );

                    /**
                     *  Update First Top Header Headings Only
                     *  -------------------------------------
                     */
                    fputcsv(

                        /**
                         *  File Name
                         *  ---------
                         */
                        $file,

                        /**
                         *  Update Headings Only
                         *  --------------------
                         */
                        $_export_data_name
                    );

                    foreach ( parent:: guest_list() as $key => $value) {

                        fputcsv(

                            /**
                             *  Handler
                             *  -------
                             */
                            $file,

                            /**
                             *  Update Values
                             *  -------------
                             */
                            array(

                                'first_name'              =>    ( isset( $value[ 'first_name' ] )  && $value[ 'first_name' ] !== '' )
                                                                ? $value[ 'first_name' ]
                                                                : '',

                                'last_name'               =>    ( isset( $value[ 'last_name' ] )  && $value[ 'last_name' ] !== '' )
                                                                ? $value[ 'last_name' ]
                                                                : '',

                                'guest_age'               =>    ( isset( $value[ 'guest_age' ] )  && $value[ 'guest_age' ] !== '' )
                                                                ? $value[ 'guest_age' ]
                                                                : '',

                                'guest_group'             =>    ( isset( $value[ 'guest_group' ] )  && $value[ 'guest_group' ] !== '' )
                                                                ? $value[ 'guest_group' ]
                                                                : '',

                                'guest_need_hotel'        =>    ( isset( $value[ 'guest_need_hotel' ] )  && $value[ 'guest_need_hotel' ] !== '' )

                                                                ?   ( $value[ 'guest_need_hotel' ] == 'on' )

                                                                    ?   esc_attr__( 'Yes', 'sdweddingdirectory-guest-list' )

                                                                    :   esc_attr__( 'No', 'sdweddingdirectory-guest-list' )

                                                                : '',

                                'guest_email'             =>    ( isset( $value[ 'guest_email' ] )  && $value[ 'guest_email' ] !== '' )
                                                                ? $value[ 'guest_email' ]
                                                                : '',

                                'guest_contact'           =>    ( isset( $value[ 'guest_contact' ] )  && $value[ 'guest_contact' ] !== '' )
                                                                ? $value[ 'guest_contact' ]
                                                                : '',

                                'guest_address'           =>    ( isset( $value[ 'guest_address' ] )  && $value[ 'guest_address' ] !== '' )
                                                                ? $value[ 'guest_address' ]
                                                                : '',

                                'guest_city'              =>    ( isset( $value[ 'guest_city' ] )  && $value[ 'guest_city' ] !== '' )
                                                                ? $value[ 'guest_city' ]
                                                                : '',

                                'guest_state'             =>    ( isset( $value[ 'guest_state' ] )  && $value[ 'guest_state' ] !== '' )
                                                                ? $value[ 'guest_state' ]
                                                                : '',

                                'guest_zip_code'          =>    ( isset( $value[ 'guest_zip_code' ] )  && $value[ 'guest_zip_code' ] !== '' )
                                                                ? $value[ 'guest_zip_code' ]
                                                                : '',

                                'guest_comment'           =>    ( isset( $value[ 'guest_comment' ] )  && $value[ 'guest_comment' ] !== '' )
                                                                ? $value[ 'guest_comment' ]
                                                                : '',
                            )
                        );
                    }
                }

                /**
                 *  File Close
                 *  ----------
                 */
                fclose($file); 

                /**
                 *  File Compatable with CSV header set
                 *  -----------------------------------
                 */
                header("Content-Description: File Transfer");

                header("Content-Disposition: attachment; filename=$filename");

                header("Content-Type: application/csv; "); 

                /**
                 *  Reading File
                 *  ------------
                 */
                readfile($filename);

                /**
                 *  Unlink File
                 *  -----------
                 */
                unlink($filename);

                /**
                 *  Success
                 *  -------
                 */
                die( json_encode( array(

                    'export_data'   =>  $file

                ) ) );

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  17. View Summary Graph
         *  ----------------------
         */
        public static function sdweddingdirectory_event_summary_load(){

            $_condition_1 = isset( $_POST[ 'event_unique_id' ] ) && $_POST[ 'event_unique_id' ] !== '';

            if( $_condition_1 ){

                /**
                 *  Get Event ID
                 *  ------------
                 */
                $_event_unique_id   =   ( isset( $_POST[ 'event_unique_id' ] ) && $_POST[ 'event_unique_id' ] !== '' )

                                        ?   absint( $_POST[ 'event_unique_id' ] )

                                        :   '';

                $_event_data        =   parent:: event_list();

                $_guest_data        =   parent:: guest_list();

                /**
                 *  Get Event Data
                 *  --------------
                 */
                
                $_graph_condition_1 = parent:: _is_array( $_event_data );

                $_graph_condition_2 = parent:: _is_array( $_guest_data );

                $_graph_condition_3 = isset( $_event_unique_id ) && $_event_unique_id !== '';

                /**
                 *  Graphe Data Load
                 *  ----------------
                 */
                if( $_graph_condition_1 && $_graph_condition_2 && $_graph_condition_3 ){

                    /**
                     *  Event Meal
                     *  ----------
                     */
                    $_guest_meal    =   '';

                    $_event_meal    =   json_decode( 

                                            parent:: get_event_details( array(

                                                    'event_unique_id'   =>  absint( $_event_unique_id ),

                                                    'get_value'         =>  esc_attr( 'event_meal' )
                                            ) ),

                                            true
                                        );

                    if( parent:: _is_array( $_event_meal ) ){

                        foreach ( $_event_meal as $key => $value) {

                            $_have_meal =

                            absint( parent:: get_event_counter( array(

                                /**
                                 *  Event Unique ID
                                 *  ---------------
                                 */
                                'event_unique_id'   =>  absint( $_event_unique_id ),

                                /**
                                 *  Find Key
                                 *  --------
                                 */
                                '__FIND__'          =>  array(

                                        
                                    'meal'      =>   sanitize_title( $value[ 'menu_list' ] )
                                )

                            ) ) );

                            /**
                             *  Have Meal at least 1 ?
                             *  ----------------------
                             */
                            if( $_have_meal >= absint( '1' ) ){

                                $_guest_meal   .=

                                sprintf('   <li class="d-flex justify-content-between align-items-center">%1$s
                                                <span class="badge bg-primary badge-pill">%2$s</span>
                                            </li>',

                                            /**
                                             *  1. Get Meal Name
                                             *  ----------------
                                             */
                                            esc_attr( $value[ 'menu_list' ] ),

                                            /**
                                             *  2. Number Of Guest Required This Meal
                                             *  -------------------------------------
                                             */
                                            absint( $_have_meal )
                                );
                            }
                        }
                    }

                    /**
                     *  Success
                     *  -------
                     */
                    die( json_encode( array(

                        /**
                         *  Update Inner HTML
                         *  -----------------
                         */
                        'html'          =>  array(

                            /**
                             *  Get Event Name
                             *  --------------
                             */
                            'view_summary_event_name'     =>    parent:: get_event_details( array(

                                                                        'event_unique_id'   =>  absint( $_event_unique_id ),

                                                                        'get_value'         =>  esc_attr( 'event_list' )
                                                                ) ),
                        ),

                        /**
                         *  Guest Age
                         *  ---------
                         */
                        'guest_age'     =>    array(


                                'adults' =>      array(


                                    'counter'   =>      ( absint( parent:: get_counter_counter( array(

                                                            /**
                                                             *  Find Key
                                                             *  --------
                                                             */
                                                            '__FIND__'          =>  array(

                                                                    
                                                                'guest_age' =>  esc_attr( 'adult' )
                                                            )

                                                        ) ) ) ),

                                    'percentage'   =>  number_format_i18n(

                                                          /**
                                                           *  1. percentage
                                                           *  -------------
                                                           */
                                                            ( absint( parent:: get_counter_counter( array(

                                                                /**
                                                                 *  Find Key
                                                                 *  --------
                                                                 */
                                                                '__FIND__'          =>  array(

                                                                        
                                                                    'guest_age' =>  esc_attr( 'adult' )
                                                                )

                                                            ) ) ) * absint( '100' )  ) 

                                                          /  absint( absint( count(  parent:: guest_list()  )  ) ),

                                                          /**
                                                           *  2. pointer
                                                           *  ----------
                                                           */
                                                          absint( '0' )

                                                        ) . '%',


                                ),

                                'child' =>      array(


                                    'counter'   =>      ( absint( parent:: get_counter_counter( array(

                                                            /**
                                                             *  Find Key
                                                             *  --------
                                                             */
                                                            '__FIND__'          =>  array(

                                                                    
                                                                'guest_age' =>  esc_attr( 'child' )
                                                            )

                                                        ) ) ) ),

                                    'percentage'   =>  number_format_i18n(

                                                          /**
                                                           *  1. percentage
                                                           *  -------------
                                                           */
                                                            ( absint( parent:: get_counter_counter( array(

                                                                /**
                                                                 *  Find Key
                                                                 *  --------
                                                                 */
                                                                '__FIND__'          =>  array(

                                                                        
                                                                    'guest_age' =>  esc_attr( 'child' )
                                                                )

                                                            ) ) ) * absint( '100' )  ) 

                                                          /  absint( absint( count(  parent:: guest_list()  )  ) ),

                                                          /**
                                                           *  2. pointer
                                                           *  ----------
                                                           */
                                                          absint( '0' )

                                                        ) . '%',


                                ),

                                'baby' =>      array(


                                    'counter'   =>      ( absint( parent:: get_counter_counter( array(

                                                            /**
                                                             *  Find Key
                                                             *  --------
                                                             */
                                                            '__FIND__'          =>  array(

                                                                    
                                                                'guest_age' =>  esc_attr( 'baby' )
                                                            )

                                                        ) ) ) ),

                                    'percentage'   =>  number_format_i18n(

                                                          /**
                                                           *  1. percentage
                                                           *  -------------
                                                           */
                                                            ( absint( parent:: get_counter_counter( array(

                                                                /**
                                                                 *  Find Key
                                                                 *  --------
                                                                 */
                                                                '__FIND__'          =>  array(

                                                                        
                                                                    'guest_age' =>  esc_attr( 'baby' )
                                                                )

                                                            ) ) ) * absint( '100' )  ) 

                                                          /  absint( absint( count(  parent:: guest_list()  )  ) ),

                                                          /**
                                                           *  2. pointer
                                                           *  ----------
                                                           */
                                                          absint( '0' )

                                                        ) . '%',


                                ),
                        ),

                        /**
                         *  Total Guest
                         *  -----------
                         */
                        'total_guest'   =>  absint( count(  parent:: guest_list()  )  ),

                        /**
                         *  Graph
                         *  -----
                         */
                        'chart_graph'       =>  array(

                            'Attending'     =>  array(


                                'count_guest'   =>  ( absint( parent:: get_event_counter( array(

                                                        /**
                                                         *  Event Unique ID
                                                         *  ---------------
                                                         */
                                                        'event_unique_id'   =>  absint( $_event_unique_id ),

                                                        /**
                                                         *  Find Key
                                                         *  --------
                                                         */
                                                        '__FIND__'          =>  array(

                                                                
                                                            'guest_invited' =>  absint( '2' )
                                                        )

                                                    ) ) ) ),

                                'percentage'    =>  number_format_i18n(

                                                      /**
                                                       *  1. percentage
                                                       *  -------------
                                                       */
                                                        ( absint( parent:: get_event_counter( array(

                                                            /**
                                                             *  Event Unique ID
                                                             *  ---------------
                                                             */
                                                            'event_unique_id'   =>  absint( $_event_unique_id ),

                                                            /**
                                                             *  Find Key
                                                             *  --------
                                                             */
                                                            '__FIND__'          =>  array(

                                                                    
                                                                'guest_invited' =>  absint( '2' )
                                                            )

                                                        ) ) ) * absint( '100' )  ) 

                                                      /  absint( absint( count(  parent:: guest_list()  )  ) ),

                                                      /**
                                                       *  2. pointer
                                                       *  ----------
                                                       */

                                                      absint( '1' )

                                                    ),
                            ),
                                                

                            'Pending'       =>  array(

                                'count_guest'   =>  ( absint( parent:: get_event_counter( array(

                                                        /**
                                                         *  Event Unique ID
                                                         *  ---------------
                                                         */
                                                        'event_unique_id'   =>  absint( $_event_unique_id ),

                                                        /**
                                                         *  Find Key
                                                         *  --------
                                                         */
                                                        '__FIND__'          =>  array(

                                                                
                                                            'guest_invited' =>  absint( '1' )
                                                        )

                                                    ) ) ) ),

                                'percentage'    =>  number_format_i18n(

                                                      /**
                                                       *  1. percentage
                                                       *  -------------
                                                       */
                                                        ( absint( parent:: get_event_counter( array(

                                                            /**
                                                             *  Event Unique ID
                                                             *  ---------------
                                                             */
                                                            'event_unique_id'   =>  absint( $_event_unique_id ),

                                                            /**
                                                             *  Find Key
                                                             *  --------
                                                             */
                                                            '__FIND__'          =>  array(

                                                                    
                                                                'guest_invited' =>  absint( '1' )
                                                            )

                                                        ) ) ) * absint( '100' )  ) 

                                                      /  absint( absint( count(  parent:: guest_list()  )  ) ),

                                                      /**
                                                       *  2. pointer
                                                       *  ----------
                                                       */

                                                      absint( '1' )

                                                    ),
                            ),

                            'Canceled'      =>  array(

                                'count_guest'   =>  ( absint( parent:: get_event_counter( array(

                                                        /**
                                                         *  Event Unique ID
                                                         *  ---------------
                                                         */
                                                        'event_unique_id'   =>  absint( $_event_unique_id ),

                                                        /**
                                                         *  Find Key
                                                         *  --------
                                                         */
                                                        '__FIND__'          =>  array(

                                                                
                                                            'guest_invited' =>  absint( '3' )
                                                        )

                                                    ) ) ) ),

                                'percentage'    =>  number_format_i18n(

                                                      /**
                                                       *  1. percentage
                                                       *  -------------
                                                       */
                                                        ( absint( parent:: get_event_counter( array(

                                                            /**
                                                             *  Event Unique ID
                                                             *  ---------------
                                                             */
                                                            'event_unique_id'   =>  absint( $_event_unique_id ),

                                                            /**
                                                             *  Find Key
                                                             *  --------
                                                             */
                                                            '__FIND__'          =>  array(

                                                                    
                                                                'guest_invited' =>  absint( '3' )
                                                            )

                                                        ) ) ) * absint( '100' )  ) 

                                                      /  absint( absint( count(  parent:: guest_list()  )  ) ),

                                                      /**
                                                       *  2. pointer
                                                       *  ----------
                                                       */

                                                      absint( '1' )

                                                    ),
                            ),
                        ),

                        /**
                         *  Update Invitation Status
                         *  ------------------------
                         */
                        'invitation'        =>  array(

                            'invitation_sent'          =>  ( absint( parent:: get_event_counter( array(

                                                                /**
                                                                 *  Event Unique ID
                                                                 *  ---------------
                                                                 */
                                                                'event_unique_id'   =>  absint( $_event_unique_id ),

                                                                /**
                                                                 *  Find Key
                                                                 *  --------
                                                                 */
                                                                '__FIND__'          =>  array(

                                                                        
                                                                    'guest_invited' =>  absint( '1' )
                                                                )

                                                            ) ) ) ),

                            'invitation_pending'       =>  ( absint( parent:: get_event_counter( array(

                                                                /**
                                                                 *  Event Unique ID
                                                                 *  ---------------
                                                                 */
                                                                'event_unique_id'   =>  absint( $_event_unique_id ),

                                                                /**
                                                                 *  Find Key
                                                                 *  --------
                                                                 */
                                                                '__FIND__'          =>  array(

                                                                        
                                                                    'guest_invited' =>  absint( '0' )
                                                                )

                                                            ) ) ) ),

                        ),

                        /**
                         *  Update Invitation Status
                         *  ------------------------
                         */
                        'event_meal'        =>  sprintf( '<ul class="list-unstyled">%1$s</ul>', 

                                                    /**
                                                     *  1. Get Meal list
                                                     *  ----------------
                                                     */
                                                    $_guest_meal
                                                ),

                    ) ) );

                }else{

                    self:: security_issue_found();
                }

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  18. Guest Invitation Sent
         *  -------------------------
         */
        public static function sdweddingdirectory_invitation_sent(){

            $_condition_1 = isset( $_POST[ 'guest_unique_id' ] ) && $_POST[ 'guest_unique_id' ] !== '';

            if( $_condition_1 ){

                $_guest_data            =   parent:: guest_list();

                $_guest_email_data      =   [];

                $_guest_email_id        =   '';

                /**
                 *  Is Array ?
                 *  ----------
                 */
                if( parent:: _is_array( $_guest_data ) ){

                    foreach ( $_guest_data as $key => $value) {

                        /**
                         *  Extract
                         *  -------
                         */
                        extract( $value );
                        
                        if( absint( $value[ 'guest_unique_id' ] ) == absint( $_POST[ 'guest_unique_id' ] ) ){

                            $_guest_email_id                            =   sanitize_email( $guest_email );

                            $_guest_email_data[ 'groom_firstname' ]     =   parent:: get_data( 'groom_first_name' );

                            $_guest_email_data[ 'groom_lastname' ]      =   parent:: get_data( 'groom_last_name' );

                            $_guest_email_data[ 'bride_firstname' ]     =   parent:: get_data( 'bride_first_name' );

                            $_guest_email_data[ 'bride_lastname' ]      =   parent:: get_data( 'bride_last_name' );

                            $_guest_email_data[ 'wedding_date' ]        =   parent:: get_data( 'wedding_date' );

                            $_guest_email_data[ 'guest_name' ]          =   sprintf( '%1$s %2$s',

                                                                                /**
                                                                                 *  1. First name
                                                                                 *  -------------
                                                                                 */
                                                                                esc_attr( $first_name ),

                                                                                /**
                                                                                 *  2. Last name
                                                                                 *  ------------
                                                                                 */
                                                                                esc_attr( $last_name )
                                                                            );

                            $_guest_email_data[ 'couple_website' ]      =   esc_url( get_the_permalink( absint( parent:: website_post_id() ) ) );

                            $_guest_data[ $key ]    =   array_replace(

                                                            /**
                                                             *  1. Guest Array Key ID
                                                             *  ---------------------
                                                             */
                                                            $_guest_data[ $key ],

                                                            /**
                                                             *  2. Invitation Sent
                                                             *  ------------------
                                                             */
                                                            array( 'invitation_status' =>  absint( '1' ) )
                                                        );

                            /**
                             *   Update Guest With Invitation Sent
                             *   ---------------------------------
                             */
                            update_post_meta( absint( parent:: post_id() ), sanitize_key( 'guest_list_data' ), $_guest_data );

                            /**
                             *  Email Process [ Sending RSVP ]
                             *  ------------------------------
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
                                    'setting_id'        =>      esc_attr( 'couple-rsvp' ),

                                    /**
                                     *  2. Sending Email ID
                                     *  -------------------
                                     */
                                    'sender_email'      =>      sanitize_email( $_guest_email_id ),

                                    /**
                                     *  3. Email Data Key and Value as Setting Body Have {{...}} all
                                     *  ------------------------------------------------------------
                                     */
                                    'email_data'        =>      $_guest_email_data
                                ) );
                            }

                            /**
                             *  Successfully sent!
                             *  ------------------
                             */
                            die( json_encode( array(

                                'notice'    =>  absint( '1' ),

                                'message'   =>  esc_attr__( 'Invitation Sent!', 'sdweddingdirectory-guest-list' ),

                            ) ) );
                        }
                    }
                }

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  19. Guest List Import
         *  ---------------------
         */
        public static function sdweddingdirectory_guest_list_import(){

            $_condition_1 =     isset( $_POST[ 'csv_file' ] ) && $_POST[ 'csv_file' ] !== '';

            if( $_condition_1 ){

                die( json_encode( array(

                    'notice'        =>  absint( '1' ),

                    'message'       =>  esc_attr__( 'Guest Data Import Successfully!', 'sdweddingdirectory-guest-list' ),

                ) ) );

            }else{

                self:: security_issue_found();
            }
        }

        /** 
         *  Guest Email ID
         *  --------------
         */
        public static function sdweddingdirectory_guest_email_update(){

            /**
             *  Guest List
             *  ----------
             */
            $_guest_list        =    parent:: guest_list();

            $_have_email        =    isset( $_POST[ 'guest_email' ] ) && ! empty( $_POST[ 'guest_email' ] );

            $_have_guest_id     =    isset( $_POST[ 'guest_unique_id' ] ) && ! empty( $_POST[ 'guest_unique_id' ] );

            $_found_guest       =    false;

            /**
             *  Make sure guest list have
             *  -------------------------
             */
            if( parent:: _is_array( $_guest_list ) && $_have_email && $_have_guest_id ){

                /**
                 *  Loop
                 *  ----
                 */
                foreach( $_guest_list as $key => $value ){

                    /**
                     *  Get User ?
                     *  ----------
                     */
                    if( $value['guest_unique_id' ] == $_POST[ 'guest_unique_id' ] ){

                        /**
                         *  Guest Found ?
                         *  -------------
                         */
                        $_found_guest           =   true;

                        /**
                         *  Get Value
                         *  ---------
                         */
                        $_guest_list[ $key ]    =   array_replace(

                                                        /**
                                                         *  Data
                                                         *  ----
                                                         */
                                                        $_guest_list[ $key ],

                                                        /**
                                                         *  Replace Value
                                                         *  -------------
                                                         */
                                                        [   'guest_email'  =>  $_POST[ 'guest_email' ]  ]
                                                    );

                        /**
                         *  Update Post Meta
                         *  ----------------
                         */
                        update_post_meta(  absint( parent:: post_id() ), sanitize_key( 'guest_list_data' ), $_guest_list );

                        /**
                         *  Success
                         *  -------
                         */
                        die( json_encode( array(

                            'notice'            =>      absint( '1' ),

                            'message'           =>      esc_attr__( 'Guest Email Updated!', 'sdweddingdirectory-guest-list' ),

                            'guest_email'       =>      $_POST[ 'guest_email' ]

                        ) ) );
                    }
                }

                /**
                 *  Found Not Guest ?
                 *  -----------------
                 */
                if( ! $_found_guest ){

                    self:: security_issue_found();
                }
            }

            /**
             *  Guest list is empty!
             *  --------------------
             */
            else{

                self:: security_issue_found();
            }
        }

        /**
         *  Sending RSVP Email
         *  ------------------
         */
        public static function sdweddingdirectory_couple_sending_guest_rsvp(){

            /**
             *  Couple Registration through AJAX action With Test Security
             *  ----------------------------------------------------------
             */
            if( wp_verify_nonce( $_POST['security'], 'sdweddingdirectory_rsvp_email_security' )  ){

                /**
                 *  Couple Post ID
                 *  --------------
                 */
                $_couple_post_id        =       parent:: post_id();

                /**
                 *  Get Guest List
                 *  --------------
                 */
                $_guest_list            =       parent:: guest_list();

                /**
                 *  Collection of Ids
                 *  -----------------
                 */
                $_guest_ids             =       $_POST[ 'guest_ids' ];


                /**
                 *  Make sure guest list not empty!
                 *  --------------------------------
                 */
                if( ! parent:: _is_array( $_guest_list ) ){

                    /**
                     *  Guest list empty!
                     *  -----------------
                     */
                    die( json_encode( [

                        'notice'        =>      absint( '0' ),

                        'message'       =>      esc_attr__( 'Guest List Empty!', 'sdweddingdirectory-guest-list' )

                    ] ) );
                }

                /**
                 *  Make sure guest id not empty!
                 *  -----------------------------
                 */
                if( ! parent:: _is_array( $_guest_ids ) ){

                    /**
                     *  Guest list empty!
                     *  -----------------
                     */
                    die( json_encode( [

                        'notice'        =>      absint( '2' ),

                        'message'       =>      esc_attr__( 'Please select at least one guest for RSVP!', 'sdweddingdirectory-guest-list' )

                    ] ) );
                }

                /**
                 *  I Have list of guest
                 *  --------------------
                 */
                else{

                    /**
                     *  Email Collection
                     *  ----------------
                     */
                    $email_collection   =   $_guest_rsvp_data   =   [];

                    /**
                     *  Guest list
                     *  ----------
                     */
                    if( parent:: _is_array( $_guest_list ) ){

                        /**
                         *  Loop
                         *  ----
                         */
                        foreach( $_guest_list as $key => $value ){

                            /**
                             *  Extract
                             *  -------
                             */
                            extract( $value );

                            /**
                             *  Guest id exits ?
                             *  ----------------
                             */
                            if( in_array( $guest_unique_id, $_guest_ids ) && ! empty( $guest_email ) ){

                                $email_collection[ $guest_unique_id ]   =   sanitize_email( $guest_email );

                                /**
                                 *  Guest RSVP Done
                                 *  ---------------
                                 */
                                $_guest_rsvp_data[]     =   array_replace( $value, array(

                                                                'invitation_status'     =>      absint( '1' )

                                                            ) );
                            }

                            else{

                                $_guest_rsvp_data[]     =   $value;
                            }
                        }

                        /**
                         *  RSVP Done
                         *  ---------
                         */
                        update_post_meta( $_couple_post_id, sanitize_key( 'guest_list_data' ), $_guest_rsvp_data );
                    }

                    /**
                     *  Make sure email ids get
                     *  -----------------------
                     */
                    if( parent:: _is_array( $email_collection ) ){

                        /**
                         *  Args
                         *  ----
                         */
                        $_replace_args                      =   [];

                        /**
                         *  1. First Name
                         *  -------------
                         */
                        $_replace_args[ 'first_name' ]      

                        =   get_post_meta( absint( $_couple_post_id ), sanitize_key( 'first_name' ), TRUE );

                        /**
                         *  2. Last Name
                         *  ------------
                         */
                        $_replace_args[ 'last_name' ]       

                        =   get_post_meta( absint( $_couple_post_id ), sanitize_key( 'last_name' ), TRUE );

                        /**
                         *  3. Wedding Date
                         *  ---------------
                         */
                        $_replace_args[ 'wedding_date' ]    

                        =   get_post_meta( absint( $_couple_post_id ), sanitize_key( 'wedding_date' ), TRUE );

                        /**
                         *  4. Wedding Date
                         *  ---------------
                         */
                        $_replace_args[ 'rsvp_image' ]      

                        =   esc_url( sdweddingdirectory_option( 'email-image-' . 'couple-rsvp' ) );

                        /**
                         *  5. RSVP Content
                         *  ---------------
                         */
                        $_replace_args[ 'rsvp_content' ]    

                        =   !   empty( $_POST[ 'rsvp_content' ] )

                            ?   esc_attr( $_POST[ 'rsvp_content' ] )

                            :   esc_attr__( 'You’re invited! Please RSVP to let us know if you’re able to attend.', 'sdweddingdirectory-guest-list' );

                        /**
                         *  6. Couple Website
                         *  -----------------
                         */
                        $_replace_args[ 'couple_website' ]

                        =   esc_url( get_the_permalink( absint( parent:: website_post_id() ) ) );

                        /**
                         *  Email Process
                         *  -------------
                         */
                        if( class_exists( 'SDWeddingDirectory_Email' ) ){

                            /**
                             *  Sending Email
                             *  -------------
                             */
                            foreach( $email_collection as $key => $value ){

                                /**
                                 *  Sending Email
                                 *  -------------
                                 */
                                SDWeddingDirectory_Email:: sending_email( array(

                                    /**
                                     *  1. Setting ID : Email PREFIX_
                                     *  -----------------------------
                                     */
                                    'setting_id'        =>      esc_attr( 'couple-rsvps' ),

                                    /**
                                     *  2. Sending Email ID
                                     *  -------------------
                                     */
                                    'sender_email'      =>      sanitize_email( $value ),

                                    /**
                                     *  3. Email Data Key and Value as Setting Body Have {{...}} all
                                     *  ------------------------------------------------------------
                                     */
                                    'email_data'        =>      $_replace_args

                                ) );
                            }

                            /**
                             *  Email Successfully Sent
                             *  -----------------------
                             */
                            die( json_encode( [

                                'message'       =>      esc_attr__( 'Your messages were successfully sent...', 'sdweddingdirectory-guest-list' ),

                                'notice'        =>      absint( '1' )

                            ] ) );
                        }

                    }

                    else{

                        /**
                         *  Email Successfully Sent
                         *  -----------------------
                         */
                        die( json_encode( [

                            'message'       =>      esc_attr__( 'Guest Email Not Founds!', 'sdweddingdirectory-guest-list' ),

                            'notice'        =>      absint( '0' )

                        ] ) );
                    }
                }
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Guest_List_AJAX:: get_instance();
}
