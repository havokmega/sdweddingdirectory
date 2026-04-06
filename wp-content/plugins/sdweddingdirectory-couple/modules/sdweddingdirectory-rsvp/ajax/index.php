<?php
/**
 *  SDWeddingDirectory Couple Real Wedding AJAX Script Action
 *  -------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_RSVP_AJAX' ) && class_exists( 'SDWeddingDirectory_Couple_RSVP_Database' ) ){

    /**
     *  SDWeddingDirectory Couple Real Wedding AJAX Script Action
     *  -------------------------------------------------
     */
    class SDWeddingDirectory_Couple_RSVP_AJAX extends SDWeddingDirectory_Couple_RSVP_Database{

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
                         *  1. RSVP - Find Name in Database
                         *  -------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_couple_guest_find_name' ),

                        /**
                         *  2. RSVP Form
                         *  ------------
                         */
                        esc_attr( 'sdweddingdirectory_rsvp_form' ),

                        /**
                         *  3. RSVP - Guest Information Collection
                         *  --------------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_rsvp_guest_information' ),

                        /**
                         *  4. Guest Updated - RSVP
                         *  -----------------------
                         */
                        esc_attr( 'sdweddingdirectory_guest_submited_rsvp_info' )
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
         *  Basic per-IP rate limiter for public RSVP endpoints
         *  ---------------------------------------------------
         */
        private static function public_rate_limit( $action = '' ){

            $_ip_address = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';

            $_action     = ! empty( $action ) ? sanitize_key( $action ) : sanitize_key( 'rsvp' );

            $_key        = 'sdwd_rsvp_' . md5( $_ip_address . '|' . $_action );

            $_attempts   = absint( get_transient( $_key ) );

            if( $_attempts >= absint( '60' ) ){

                return false;
            }

            set_transient( $_key, $_attempts + absint( '1' ), 5 * MINUTE_IN_SECONDS );

            return true;
        }

        /**
         *  Validate nonce + honeypot + rate limit for public RSVP endpoints
         *  ----------------------------------------------------------------
         */
        private static function authorize_public_rsvp_request(){

            $_security   = isset( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';
            $_honeypot   = isset( $_POST['honeypot'] ) ? sanitize_text_field( wp_unslash( $_POST['honeypot'] ) ) : '';
            $_action     = isset( $_POST['action'] ) ? sanitize_key( wp_unslash( $_POST['action'] ) ) : sanitize_key( 'rsvp' );

            if( ! empty( $_honeypot ) ){

                wp_send_json_error( [
                    'notice'    =>  absint( '0' ),
                    'message'   =>  esc_attr__( 'Request rejected.', 'sdweddingdirectory-rsvp' ),
                ] );
            }

            if( empty( $_security ) || ! wp_verify_nonce( $_security, 'sdweddingdirectory_rsvp_guest_security' ) ){

                wp_send_json_error( [
                    'notice'    =>  absint( '0' ),
                    'message'   =>  esc_attr__( 'Invalid security token.', 'sdweddingdirectory-rsvp' ),
                ] );
            }

            if( ! self:: public_rate_limit( $_action ) ){

                wp_send_json_error( [
                    'notice'    =>  absint( '0' ),
                    'message'   =>  esc_attr__( 'Too many requests. Please try again shortly.', 'sdweddingdirectory-rsvp' ),
                ] );
            }
        }

        /**
         *  1. Insert Realwedding data through couple login : read wedding page ( backend )
         *  -------------------------------------------------------------------------------
         */
        public static function sdweddingdirectory_couple_guest_find_name(){

            global $post, $wp_query, $page;

            self:: authorize_public_rsvp_request();

            $_condition_1       =   isset( $_POST['first_name'] ) && $_POST['first_name'] !== '';

            $_condition_2       =   isset( $_POST['last_name'] ) && $_POST['last_name'] !== '';

            $_condition_3       =   isset( $_POST['post_id'] ) && $_POST['post_id'] !== '';

            $guest_list         =   [];

            /**
             *  Successfully Update Message
             *  ---------------------------
             */
            $_not_found =   sprintf(   '<div class="card-shadow position-relative" id="_not_found_">

                                            <div class="card-shadow-body">

                                                <h3 class="mb-0">%1$s %2$s %3$s</h3>

                                            </div>

                                        </div>',

                                        /**
                                         *  1. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'We have found 0 guests matching', 'sdweddingdirectory-rsvp' ),

                                        /**
                                         *  2. Find String
                                         *  --------------
                                         */
                                        $_condition_1   ?   esc_attr( $_POST['first_name'] ) : '',

                                        /**
                                         *  3. Last name
                                         *  ------------
                                         */
                                        $_condition_2   ?   esc_attr( $_POST['last_name'] ) : '',

                                        /**
                                         *  3. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'New Search', 'sdweddingdirectory-rsvp' )
                            );


            /**
             *  Security Check
             *  --------------
             */
            if( $_condition_1 || $_condition_2 && $_condition_3 ){

                /**
                 *  Post ID
                 *  -------
                 */
                $_couple_email      =    get_post_meta(

                                            /**
                                             *  1. Website ID
                                             *  -------------
                                             */
                                            absint( $_POST['post_id'] ),

                                            /**
                                             *  2. User Email ID
                                             *  ----------------
                                             */
                                            sanitize_key( 'user_email' ),

                                            /**
                                             *  3. TRUE
                                             *  -------
                                             */
                                            TRUE
                                        );

                /**
                 *  Couple Guest Data
                 *  -----------------
                 */
                $_guest_data    =   get_post_meta( 

                                        /**
                                         *  1. Couple Post ID
                                         *  -----------------
                                         */
                                        apply_filters( 'sdweddingdirectory/couple/post-id', sanitize_email( $_couple_email ) ),

                                        /**
                                         *  2. Data key
                                         *  -----------
                                         */
                                        sanitize_key( 'guest_list_data' ), 

                                        /**
                                         *  3. TRUE
                                         *  -------
                                         */
                                        true 
                                    );
                /**
                 *  Is Array ?
                 *  ----------
                 */
                if( parent:: _is_array( $_guest_data ) ){

                    foreach( $_guest_data as $key => $value ){

                        /**
                         *  Extract
                         *  -------
                         */
                        extract( $value );

                        /**
                         *  First name + Last name updated in post ?
                         *  ----------------------------------------
                         */
                        if( $_condition_1 && $_condition_2 ){

                            $_data_condition_1  =   strtolower( $_POST['first_name'] ) == strtolower( $first_name );

                            $_data_condition_2  =   strtolower( $_POST['last_name'] ) == strtolower( $last_name );

                            /**
                             *  First name + last name match in database ?
                             *  ------------------------------------------
                             */
                            if(  $_data_condition_1 && $_data_condition_2  ){

                                $guest_list[]     =   $value;
                            }
                        }

                        /**
                         *  Have First name ?
                         *  -----------------
                         */
                        elseif( $_condition_1 ){

                            $_data_condition_1  =   strtolower( $_POST['first_name'] ) == strtolower( $first_name );

                            /**
                             *  First name match in database ?
                             *  ------------------------------
                             */
                            if(  $_data_condition_1  ){

                                $guest_list[]     =   $value;
                            }
                        }

                        /**
                         *  Have Last Name ?
                         *  ----------------
                         */
                        elseif( $_condition_2 ){

                            $_data_condition_2  =   strtolower( $_POST['last_name'] ) == strtolower( $last_name );

                            /**
                             *  Last name match in database ?
                             *  ------------------------------
                             */
                            if(  $_data_condition_2  ){

                                $guest_list[]     =   $value;
                            }
                        }
                    }
                }

                /**
                 *  Have Guest List ?
                 *  -----------------
                 */
                if( parent:: _is_array( $guest_list ) ){

                    /**
                     *  RSVP Success Message
                     *  --------------------
                     */
                    die( json_encode( array(

                        'notice'    =>  absint( '1' ),

                        'data'      =>  self:: guest_can_select_name( $guest_list ),

                    ) ) );

                }else{

                    /**
                     *  RSVP Success Message
                     *  --------------------
                     */
                    die( json_encode( array(

                        'notice'    =>  absint( '0' ),

                        'data'      =>  $_not_found

                    ) ) );
                }

            }else{

                /** 
                 *  Securit Issue Found
                 *  -------------------
                 */
                die( json_encode( array(

                    'data'      =>  $_not_found,

                    'notice'    =>  absint( '0' )

                ) ) );
            }
        }

        /**
         *  Select Guest Name
         *  -----------------
         */
        public static function guest_can_select_name( $guest_list = [] ){

            $_collection    =   '';

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $guest_list ) ){

                $_collection    .=  '<form id="sdweddingdirectory-couple-guest-name-selection" method="post">';

                $_collection    .=  '<div class="card-shadow position-relative">';

                $_collection    .=  sprintf( '<div class="card-shadow-header"><h3>%1$s</h3></div><div class="card-shadow-body">',

                                        /**
                                         *  1. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Please select your name', 'sdweddingdirectory-rsvp' )
                                    );

                foreach( $guest_list as $key => $value ){

                    /**
                     *  Extract Values
                     *  --------------
                     */
                    extract( $value );

                    /**
                     *  Guest List
                     *  ----------
                     */
                    $_collection   .=  sprintf( '<div class="custom-radio mb-3 form-dark">

                                                    <input autocomplete="off"  class="form-check-input" type="radio" name="rsvp-guest-select" id="%1$s" 

                                                        data-member-id="%3$s" 

                                                        data-unique-id="%4$s" />
                                                    
                                                        <label class="form-check-label" for="%1$s">%1$s %2$s</label>

                                                </div>',

                                            /**
                                             *  1. First Name
                                             *  -------------
                                             */
                                            esc_attr( $first_name ),
                                            
                                            /**
                                             *  2. Last Name
                                             *  -------------
                                             */
                                            esc_attr( $last_name ),

                                            /**
                                             *  3. Guest Unique ID
                                             *  ------------------
                                             */
                                            absint( $guest_unique_id ),

                                            /**
                                             *  4. Member Unique ID
                                             *  -------------------
                                             */
                                            absint( $guest_member_id )
                                        );
                }

                $_collection    .=  '</div></div>';

                $_collection    .=  '<div class="mt-4">';

                $_collection    .=  sprintf( '<button class="btn btn-primary" id="new_search_rsvp_form">%1$s</button>',

                                        /**
                                         *  1. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'New Search', 'sdweddingdirectory-rsvp' )
                                    );

                $_collection    .=  sprintf( '<button class="float-end btn btn-default text-center" id="rsvp_continues">%1$s</button>', 

                                        /**
                                         *  1. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Continue', 'sdweddingdirectory-rsvp' )
                                    );

                $_collection    .=  '</div>';

                $_collection    .=  '</form>';
            }

            /**
             *  Return : data
             *  -------------
             */
            return      $_collection;
        }

        /**
         *  2. RSVP Form
         *  ------------
         */
        public static function sdweddingdirectory_rsvp_form(){

            self:: authorize_public_rsvp_request();

            die( json_encode( [

                'rsvp_form'     =>      SDWeddingDirectory_RSVP:: _find_name_in_rsvp_form()

            ] ) );
        }

        /**
         *  3. RSVP - Guest Information Collection
         *  --------------------------------------
         */
        public static function sdweddingdirectory_rsvp_guest_information(){

            global $post, $wp_query, $page;

            self:: authorize_public_rsvp_request();

            $_condition_1       =   isset( $_POST['member_id'] ) && $_POST['member_id'] !== '';

            $_condition_2       =   isset( $_POST['guest_id'] ) && $_POST['guest_id'] !== '';

            $_condition_3       =   isset( $_POST['post_id'] ) && $_POST['post_id'] !== '';

            $guest_list         =   [];

            /**
             *  Make sure both condition is true
             *  --------------------------------
             */
            if( $_condition_1 && $_condition_2 && $_condition_3 ){

                /**
                 *  Post ID
                 *  -------
                 */
                $_couple_email      =    get_post_meta(

                                            /**
                                             *  1. Website ID
                                             *  -------------
                                             */
                                            absint( $_POST['post_id'] ),

                                            /**
                                             *  2. User Email ID
                                             *  ----------------
                                             */
                                            sanitize_key( 'user_email' ),

                                            /**
                                             *  3. TRUE
                                             *  -------
                                             */
                                            TRUE
                                        );

                /**
                 *  Couple Guest Data
                 *  -----------------
                 */
                $_guest_data    =   get_post_meta( 

                                        /**
                                         *  1. Couple Post ID
                                         *  -----------------
                                         */
                                        apply_filters( 'sdweddingdirectory/couple/post-id', sanitize_email( $_couple_email ) ),

                                        /**
                                         *  2. Data key
                                         *  -----------
                                         */
                                        sanitize_key( 'guest_list_data' ), 

                                        /**
                                         *  3. TRUE
                                         *  -------
                                         */
                                        true 
                                    );
                /**
                 *  Is Array ?
                 *  ----------
                 */
                if( parent:: _is_array( $_guest_data ) ){

                    foreach( $_guest_data as $key => $value ){

                        /**
                         *  Extract
                         *  -------
                         */
                        extract( $value );

                        /**
                         *  Collection of Members
                         *  ---------------------
                         */
                        if( $_POST['member_id'] == $guest_member_id ){

                            $guest_list[]     =   $value;
                        }
                    }
                }

                /**
                 *  RSVP Success Message
                 *  --------------------
                 */
                die( json_encode( array(

                    'data'              =>  self:: guest_information(

                                                /**
                                                 *  1. Couple Post ID
                                                 *  -----------------
                                                 */
                                                absint( apply_filters( 'sdweddingdirectory/couple/post-id', sanitize_email( $_couple_email ) ) ),

                                                /**
                                                 *  2. Selected Member Group Data
                                                 *  -----------------------------
                                                 */
                                                $guest_list,

                                                /**
                                                 *  3. Request Handler Guest
                                                 *  ------------------------
                                                 */
                                                $_POST[ 'guest_id' ]
                                            ),

                ) ) );

            }else{

                /** 
                 *  Securit Issue Found
                 *  -------------------
                 */
                die( json_encode( array(

                    'message'   =>  esc_attr__( 'Security Error..', 'sdweddingdirectory-rsvp' ),

                    'notice'    =>  absint( '0' ),

                    'data'      =>  ''

                ) ) );
            }
        }

        /**
         *  Is Guest attended ?
         *  -------------------
         */
        public static function _guest_attended( $args = [] ){

            $_data  =   '';

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                extract( wp_parse_args( $args, [

                    'guest_id'          =>  '',

                    'event_id'          =>  '',

                    'couple_id'         =>  '',

                    'guest_event_data'  =>  [],

                    'first_name'        =>  '',

                    'last_name'         =>  ''

                ] ) );

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _have_data( $couple_id ) && parent:: _is_array( $guest_event_data ) ){

                    $menu_list      =   get_post_meta( absint( $couple_id ), sanitize_key( 'guest_list_menu_group' ), true );

                    $event_list     =   get_post_meta( absint( $couple_id ), sanitize_key( 'guest_list_event_group' ), true );

                    foreach( $event_list as $key => $value ){

                        extract( $value );

                        foreach( $guest_event_data as $guest_event_data_key => $_guest_event_data_value ){

                            extract( $_guest_event_data_value );

                                if( $event_id == $_guest_event_data_value['event_unique_id'] &&

                                    $value[ 'event_unique_id' ] == $event_id
                                ){

                                    $_is_visible    =   $guest_invited == absint( '0' ) || $guest_invited == ''

                                                    ?   sanitize_html_class( 'd-none' )

                                                    :   '';

                                    $_data  .=  sprintf( '<div class="row guest-id %2$s" data-guest-id="%1$s">', 

                                                    /**
                                                     *  1. Guest ID
                                                     *  -----------
                                                     */
                                                    esc_attr( $guest_id ),

                                                    /**
                                                     *  2. Guest Not Invited ?
                                                     *  ----------------------
                                                     */
                                                    $_is_visible
                                                );

                                    $_data  .=

                                    sprintf( '<div class="col-md-4 col-12"><h5>%1$s %2$s</h5></div>',

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

                                    $_choice    =   [

                                        '1'     =>  [

                                            'value'         =>  absint( '1' ),

                                            'placeholder'   =>  esc_attr__( 'Pending', 'sdweddingdirectory-rsvp' ),

                                            'id'            =>  esc_attr( parent:: _rand() ),

                                            'is_checked'    =>  $guest_invited == absint( '1' )

                                                            ?   esc_attr( 'checked' )

                                                            :   '',
                                        ],

                                        '2'     =>  [

                                            'value'         =>  absint( '2' ),

                                            'placeholder'   =>  esc_attr__( 'I Accept', 'sdweddingdirectory-rsvp' ),

                                            'id'            =>  esc_attr( parent:: _rand() ),

                                            'is_checked'    =>  $guest_invited == absint( '2' )

                                                            ?   esc_attr( 'checked' )

                                                            :   '',
                                        ],

                                        '3'     =>  [

                                            'value'         =>  absint( '3' ),

                                            'placeholder'   =>  esc_attr__( 'I Decline', 'sdweddingdirectory-rsvp' ),

                                            'id'            =>  esc_attr( parent:: _rand() ),

                                            'is_checked'    =>  $guest_invited == absint( '3' )

                                                            ?   esc_attr( 'checked' )

                                                            :   '',
                                        ]
                                    ];

                                    $_data  .=  '<div class="col-md-4 col-12">';

                                    $_input_name    =   esc_attr( parent:: _rand() );

                                    foreach( $_choice as $_choice_key => $_choice_value ){

                                        extract( $_choice_value, EXTR_PREFIX_ALL, '_choice' );

                                        $_data  .=

                                        sprintf(   '<div class="form-check form-check-inline form-dark">
                                                        <input autocomplete="off" class="form-check-input guest_attended" type="radio" name="%5$s" id="%1$s" value="%2$s" %3$s>
                                                        <label class="form-check-label" for="%1$s">%4$s</label>
                                                    </div>',

                                                    /**
                                                     *  1. ID
                                                     *  -----
                                                     */
                                                    $_choice_id,

                                                    /**
                                                     *  2. Value
                                                     *  --------
                                                     */
                                                    absint( $_choice_value ),

                                                    /**
                                                     *  3. Is Checked
                                                     *  -------------
                                                     */
                                                    $_choice_is_checked,

                                                    /**
                                                     *  4. Choice Placeholder
                                                     *  ---------------------
                                                     */
                                                    esc_attr( $_choice_placeholder ),

                                                    /**
                                                     *  5. Input Name
                                                     *  -------------
                                                     */
                                                    $_input_name
                                        );
                                    }

                                    $_data  .=  '</div>';

                                    /**
                                     *  Event Meal
                                     *  ----------
                                     */
                                    if( $value[ 'have_meal' ] == esc_attr( 'on' ) ){

                                        $_data  .=  '<div class="col-md-4 col-12">';

                                        $_data  .=  

                                        SDWeddingDirectory_Guest_List_Database::get_event_meals( 

                                            /**
                                             *  1. Option
                                             *  ---------
                                             */
                                            array(

                                                /**
                                                 *  1. Get Meal list
                                                 *  ----------------
                                                 */
                                                'event_meal'            =>   json_decode( $value[ 'event_meal' ], true ),

                                                /**
                                                 *  2. Guest Choose Meal
                                                 *  --------------------
                                                 */
                                                'guest_meal'            =>   isset( $_guest_event_data_value['meal'] ) && $_guest_event_data_value['meal'] !== ''

                                                                        ?    esc_attr( $_guest_event_data_value['meal'] )

                                                                        :    '',
                                                /**
                                                 *  3. Member ID
                                                 *  ------------
                                                 */
                                                'guest_unique_id'       =>   absint( $guest_id ),

                                                /**
                                                 *  4. Event ID
                                                 *  -----------
                                                 */
                                                'event_unique_id'      =>   absint( $value[ 'event_unique_id' ] ),
                                            ),

                                            /**
                                             *  2. Print ?
                                             *  ----------
                                             */
                                            $print = false,

                                            /**
                                             *  3. Selection Option Style
                                             *  -------------------------
                                             */
                                            $style = esc_attr( 'dark' )
                                        );

                                        $_data  .=  '</div>';
                                    }

                                    $_data  .=  '<div class="col-12 divider"><hr/></div>';

                                    $_data  .=  '</div><!-- / row -->';
                                }
                        }
                    }
                }
            }

            /**
             *  Return
             *  ------
             */
            return  $_data;
        }

        /**
         *  Guest Info
         *  ----------
         */
        public static function guest_information( $couple_post_id = 0, $guest_list = [], $request_handler_guest_id = '' ){

            $_collection    =   '';

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $guest_list ) && ! empty( $couple_post_id ) ){

                $menu_list      =       get_post_meta( absint( $couple_post_id ), sanitize_key( 'guest_list_menu_group' ), true );

                $event_list     =       get_post_meta( absint( $couple_post_id ), sanitize_key( 'guest_list_event_group' ), true );

                $_collection    .=      sprintf( '<h3 class="mb-4">%1$s</h3>',

                                            /**
                                             *  1. Translation Ready String
                                             *  ---------------------------
                                             */
                                            esc_attr__( 'Confirm attendance', 'sdweddingdirectory-rsvp' )
                                        );

                $_collection    .=      '<form id="sdweddingdirectory-couple-guest-info" method="post">';

                $_collection    .=      sprintf( '<input type="hidden" id="request_handling_member" value="%1$s" />', 

                                            $request_handler_guest_id
                                        );

                $_collection    .=      '[sdweddingdirectory_tabs id="" class="" layout="3"]';

                /**
                 *  Have Events ?
                 *  -------------
                 */
                if( parent:: _is_array( $event_list ) ){

                    foreach( $event_list as $event_key => $event_data ){

                        /**
                         *  Extract
                         *  -------
                         */
                        extract( $event_data );

                        $_guest_info    =   '';

                        foreach( $guest_list as $guest_key => $guest_data ){

                            /**
                             *  Extract
                             *  -------
                             */
                            extract( $guest_data );

                            $_guest_info    .=  self:: _guest_attended( [

                                                    'first_name'        =>  esc_attr( $first_name ),

                                                    'last_name'         =>  esc_attr( $last_name ),

                                                    'guest_id'          =>  $guest_unique_id,

                                                    'event_id'          =>  $event_data[ 'event_unique_id' ],

                                                    'couple_id'         =>  $couple_post_id,

                                                    'guest_event_data'  =>  json_decode( $guest_event, true )

                                                ] );
                        }

                        $_collection    .=

                        sprintf( '[sdweddingdirectory_tab id="" class="" title="%1$s"]

                                    <div class="card-shadow event-id mb-4" data-event-id="%3$s">

                                        <div class="card-shadow-header"><h3 class="event_name">%1$s</h3></div>

                                        <div class="card-shadow-body">

                                            <div class="row">

                                                <div class="col-12 guest-member-id" data-member-id="%4$s">

                                                    %2$s

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                [/sdweddingdirectory_tab]',


                                /**
                                 *  1. Event Name
                                 *  -------------
                                 */
                                esc_attr( $event_list ),

                                /**
                                 *  2. Guest Name
                                 *  -------------
                                 */
                                $_guest_info,

                                /**
                                 *  3. Event ID
                                 *  -----------
                                 */
                                esc_attr( $event_data[ 'event_unique_id' ] ),

                                /**
                                 *  4. Guest ID
                                 *  -----------
                                 */
                                esc_attr( $guest_member_id ),

                                /**
                                 *  5. Guest ID
                                 *  -----------
                                 */
                                esc_attr( $guest_unique_id )
                        );
                    }

                    $_collection    .=  '[/sdweddingdirectory_tabs]';


                    /**
                     *  Couple Have Any Message ?
                     *  -------------------------
                     */
                    $_collection    .=

                    sprintf( '<div class="row">

                                <div class="col-12">

                                    <h5 class="mb-3">%1$s</h5>

                                    <textarea class="form-control" rows="3" placeholder="%2$s" id="guest_comment"></textarea>

                                </div>

                            </div>',

                        /**
                         *  1. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr__( 'Leave a message for the couple', 'sdweddingdirectory-rsvp' ),

                        /**
                         *  2. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr__( 'Your message', 'sdweddingdirectory-rsvp' )
                    );

                    /**
                     *  Action Buttons
                     *  --------------
                     */
                    $_collection    .=

                    sprintf( '<div class="mt-4">

                                <button class="btn btn-primary" type="button" id="new_search_rsvp_form">%2$s</button>

                                <button class="float-end btn btn-default" type="button" id="sdweddingdirectory-gurest-rsvp-submit">%1$s</button>

                            </div>',

                                /**
                                 *  1. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'RSVP', 'sdweddingdirectory-rsvp' ),

                                /**
                                 *  2. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'New Search', 'sdweddingdirectory-rsvp' )
                    );
                }

                $_collection    .=  '</form>';
            }

            /**
             *  Return : data
             *  -------------
             */
            return      do_shortcode( $_collection );
        }

        /**
         *  Attended counter to get string
         *  ------------------------------
         */
        public static function _attended_string( $status = 0 ){

            /**
             *  Not Invited
             *  -----------
             */        
            if( $status == absint( '2' ) ){

                return  esc_attr( 'Attending' );
            }

            elseif( $status == absint( '3' ) ){

                return  esc_attr( 'Declined' );
            }

            else{

                return  esc_attr( 'Pending' );
            }
        }

        /**
         *  4. Guest Updated - RSVP
         *  -----------------------
         */
        public static function sdweddingdirectory_guest_submited_rsvp_info(){

            self:: authorize_public_rsvp_request();

            global $post, $wp_query, $page;

            $_condition_1       =   isset( $_POST['guest_rsvp'] ) && $_POST['guest_rsvp'] !== '' && parent:: _is_array( $_POST['guest_rsvp'] );

            $_condition_2       =   isset( $_POST['post_id'] ) && $_POST['post_id'] !== '';

            /**
             *  Make sure both condition is true
             *  --------------------------------
             */
            if( $_condition_1 && $_condition_2 ){

                /**
                 *  Email Data Collection
                 *  ---------------------
                 */
                $_guest_email_data      =   [];

                $_guest_email_id        =   '';

                $_guest_rsvp_content    =   '<p>Request addresses & collect RSVPs:</p>';

                /**
                 *  Post ID
                 *  -------
                 */
                $_couple_email      =    get_post_meta(

                                            /**
                                             *  1. Website ID
                                             *  -------------
                                             */
                                            absint( $_POST['post_id'] ),

                                            /**
                                             *  2. User Email ID
                                             *  ----------------
                                             */
                                            sanitize_key( 'user_email' ),

                                            /**
                                             *  3. TRUE
                                             *  -------
                                             */
                                            TRUE
                                        );

                /**
                 *  Couple Post ID
                 *  --------------
                 */
                $_couple_post_id    =   absint( apply_filters( 'sdweddingdirectory/couple/post-id', sanitize_email( $_couple_email ) ) );

                /**
                 *  Couple Guest Data
                 *  -----------------
                 */
                $_guest_data        =   get_post_meta(

                                            /**
                                             *  1. Couple Post ID
                                             *  -----------------
                                             */
                                            absint( $_couple_post_id ),

                                            /**
                                             *  2. Data key
                                             *  -----------
                                             */
                                            sanitize_key( 'guest_list_data' ), 

                                            /**
                                             *  3. TRUE
                                             *  -------
                                             */
                                            true
                                        );

                $_rsvp_data             =   $_POST['guest_rsvp'];

                $_guest_event_data      =   [];

                /**
                 *  Guest Event Data
                 *  ----------------
                 */
                if( parent:: _is_array( $_rsvp_data ) ){

                    foreach( $_rsvp_data as $_rsvp_key => $_rsvp_value ){

                        $_guest_id  =   $_rsvp_value[ 'guest_id' ];

                        unset( $_rsvp_value[ 'guest_id' ] );
                        
                        $_guest_event_data[ $_guest_id ][] =  $_rsvp_value;
                    }
                }

                /**
                 *  Guest Data Update with RSVp
                 *  ---------------------------
                 */
                if( parent:: _is_array( $_guest_event_data ) ){

                    foreach( $_guest_event_data as $_rsvp_key => $_rsvp_value ){

                        /**
                         *  Is Array ?
                         *  ----------
                         */
                        if( parent:: _is_array( $_guest_data ) ){

                            foreach( $_guest_data as $_guest_key => $guest_value ){

                                /**
                                 *  Extract
                                 *  -------
                                 */
                                extract( $guest_value );

                                /**
                                 *  Collection of Members
                                 *  ---------------------
                                 */
                                if( $_rsvp_key == $guest_unique_id ){

                                    /**
                                     *  Guest Name + Event Name + Attendece
                                     *  -----------------------------------
                                     */
                                    $_guest_rsvp_content    .=  sprintf( '<p><strong>%1$s %2$s</strong>\'s attendance to your <strong>%3$s</strong> is <strong>%4$s</strong>.</p>',

                                                                    /**
                                                                     *  1. First Name
                                                                     *  -------------
                                                                     */
                                                                    esc_attr( $guest_value[ 'first_name' ] ),

                                                                    /**
                                                                     *  2. Last Name
                                                                     *  ------------
                                                                     */
                                                                    esc_attr( $guest_value[ 'last_name' ] ),

                                                                    /**
                                                                     *  3. Event Name
                                                                     *  -------------
                                                                     */
                                                                    isset( $_rsvp_value[ 'event_name' ] ) && $_rsvp_value[ 'event_name' ] !== ''

                                                                    ?   esc_attr( $_rsvp_value[ 'event_name' ] )

                                                                    :   '',

                                                                    /**
                                                                     *  4. Guest Attended ?
                                                                     *  -------------------
                                                                     */
                                                                    isset( $_rsvp_value[ 'guest_invited' ] ) && $_rsvp_value[ 'guest_invited' ] !== ''

                                                                    ?   self:: _attended_string( $_rsvp_value[ 'guest_invited' ] )

                                                                    :   absint( '1' )
                                                                );

                                    /**
                                     *  Guest Meal
                                     *  ----------
                                     */
                                    $_guest_rsvp_content    .=  sprintf( '<p><strong>Menu</strong> selection: <strong>%1$s</strong></p>',

                                                                    /**
                                                                     *  1. Select Meal
                                                                     *  --------------
                                                                     */
                                                                    isset( $_rsvp_value[ 'meal' ] ) && $_rsvp_value[ 'meal' ] !== ''

                                                                    ?   esc_attr( $_rsvp_value[ 'meal' ] )

                                                                    :   absint( '0' )
                                                                );

                                    /**
                                     *  Is Sender ID ? Who Fill this form ?
                                     *  -----------------------------------
                                     */
                                    if( get_option( 'SDWeddingDirectory_RSVP_Guest' ) == absint( $guest_value[ 'guest_unique_id' ] ) ){

                                        $_guest_email_id    =   sanitize_email( $guest_value[ 'guest_email' ] );

                                        $_guest_email_data[ 'groom_firstname' ]     =   get_post_meta(

                                                                                            /**
                                                                                             *  1. Couple Post ID
                                                                                             *  -----------------
                                                                                             */
                                                                                            absint( $_couple_post_id ),

                                                                                            /**
                                                                                             *  2. Meta Key
                                                                                             *  -----------
                                                                                             */
                                                                                            sanitize_key( 'groom_first_name' ),

                                                                                            /**
                                                                                             *  TRUE
                                                                                             *  ----
                                                                                             */
                                                                                            TRUE
                                                                                        );

                                        $_guest_email_data[ 'groom_lastname' ]      =   get_post_meta(  

                                                                                            /**
                                                                                             *  1. Couple Post ID
                                                                                             *  -----------------
                                                                                             */
                                                                                            absint( $_couple_post_id ),

                                                                                            /**
                                                                                             *  2. Meta Key
                                                                                             *  -----------
                                                                                             */
                                                                                            sanitize_key( 'groom_last_name' ),

                                                                                            /**
                                                                                             *  TRUE
                                                                                             *  ----
                                                                                             */
                                                                                            TRUE
                                                                                        );

                                        $_guest_email_data[ 'bride_firstname' ]     =   get_post_meta(  

                                                                                            /**
                                                                                             *  1. Couple Post ID
                                                                                             *  -----------------
                                                                                             */
                                                                                            absint( $_couple_post_id ),

                                                                                            /**
                                                                                             *  2. Meta Key
                                                                                             *  -----------
                                                                                             */
                                                                                            sanitize_key( 'bride_first_name' ),

                                                                                            /**
                                                                                             *  TRUE
                                                                                             *  ----
                                                                                             */
                                                                                            TRUE
                                                                                        );

                                        $_guest_email_data[ 'bride_lastname' ]      =   get_post_meta(  

                                                                                            /**
                                                                                             *  1. Couple Post ID
                                                                                             *  -----------------
                                                                                             */
                                                                                            absint( $_couple_post_id ),

                                                                                            /**
                                                                                             *  2. Meta Key
                                                                                             *  -----------
                                                                                             */
                                                                                            sanitize_key( 'bride_last_name' ),

                                                                                            /**
                                                                                             *  TRUE
                                                                                             *  ----
                                                                                             */
                                                                                            TRUE
                                                                                        );

                                        $_guest_email_data[ 'wedding_date' ]        =   get_post_meta(  

                                                                                            /**
                                                                                             *  1. Couple Post ID
                                                                                             *  -----------------
                                                                                             */
                                                                                            absint( $_couple_post_id ),

                                                                                            /**
                                                                                             *  2. Meta Key
                                                                                             *  -----------
                                                                                             */
                                                                                            sanitize_key( 'wedding_date' ),

                                                                                            /**
                                                                                             *  TRUE
                                                                                             *  ----
                                                                                             */
                                                                                            TRUE
                                                                                        );

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
                                                                                            esc_attr( $last_name ),
                                                                                        );

                                        
                                    }


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

                                        'guest_event'             =>    json_encode( $_rsvp_value ),

                                        'guest_age'               =>    esc_attr( $guest_value[ 'guest_age' ] ),

                                        'guest_group'             =>    esc_attr( $guest_value[ 'guest_group' ] ),

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

                                        'guest_comment'           =>    $_POST['request_handling_member'] == absint( $guest_value[ 'guest_unique_id' ] )

                                                                        ?   self:: guest_message_handling( [

                                                                                'old_message'   =>      $guest_value[ 'guest_comment' ],

                                                                                'new_message'   =>      $_POST['guest_comment']

                                                                            ] )

                                                                        :   $guest_value[ 'guest_comment' ]
                                    );
                                }
                            }
                        }
                    }

                    /**
                     *  Update Data in Backend
                     *  ----------------------
                     */
                    update_post_meta(  

                        /**
                         *  1. Couple Post ID
                         *  -----------------
                         */
                        apply_filters( 'sdweddingdirectory/couple/post-id', sanitize_email( $_couple_email ) ),

                        /**
                         *  2. Data key
                         *  -----------
                         */
                        sanitize_key( 'guest_list_data' ),

                        /**
                         *  3. Value Update
                         *  ---------------
                         */
                        $_guest_data
                    );
                }

                /**
                 *  Updated Guest ID Reset
                 *  ----------------------
                 */
                update_option( 'SDWeddingDirectory_RSVP_Guest', '' );

                /**
                 *  Guest Fill RSVP event's data 
                 *  ----------------------------
                 */
                $_guest_email_data[ 'guest_data' ]     =   $_guest_rsvp_content;

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
                        'setting_id'        =>      esc_attr( 'guest-rsvp' ),

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
                 *  Successfully Update Message
                 *  ---------------------------
                 */
                $_new_form  =   sprintf(   '<div class="card-shadow position-relative">

                                                <div class="card-shadow-body">

                                                    <h3 class="mb-3">%1$s</h3>

                                                    <button class="btn btn-primary" id="new_search_rsvp_form">%2$s</button>

                                                </div>

                                            </div>', 

                                            /**
                                             *  1. Translation Ready String
                                             *  ---------------------------
                                             */
                                            esc_attr__( 'Your confirmation has been sent successfully.', 'sdweddingdirectory-rsvp' ),

                                            /**
                                             *  2. Translation Ready String
                                             *  ---------------------------
                                             */
                                            esc_attr__( 'New Search', 'sdweddingdirectory-rsvp' )
                                );

                /**
                 *  RSVP Success Message
                 *  --------------------
                 */
                die( json_encode( array(

                    'message'           =>  esc_attr__( 'RSVP Submited!', 'sdweddingdirectory-rsvp' ),

                    'notice'            =>  absint( '1' ),

                    'data'              =>  $_new_form

                ) ) );

            }else{

                /** 
                 *  Securit Issue Found
                 *  -------------------
                 */
                die( json_encode( array(

                    'message'   =>  esc_attr__( 'Security Error..', 'sdweddingdirectory-rsvp' ),

                    'notice'    =>  absint( '0' ),

                    'data'      =>  ''

                ) ) );
            }
        }

        /**
         *  Have Message ?
         *  --------------
         */
        public static function guest_message_handling( $args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $args, [

                'old_message'       =>      '',

                'new_message'       =>      '',

                'handler'           =>      []

            ] ) );

            /**
             *  Have Message
             *  ------------
             */
            if( ! empty( $new_message ) && ! empty( $old_message ) ){

                $handler[]      =       $old_message;

                $handler[]      =       $new_message;
            }

            elseif( ! empty( $new_message ) ){

                $handler[]      =       $new_message;
            }

            /**
             *  Return String
             *  -------------
             */
            return      implode( '||', $handler );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Couple_RSVP_AJAX:: get_instance();
}
